<?php

class pgen_ProposalManager
{
	/**
* SaveProposalSubStatus()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 16 23:02:42 PST 2006
*/
function SaveProposalSubStatus($o)
{
	$p = new proposalDB();
	$p->SetProposalId($o['proposal_id']);
	
	$p->UpdateProposalStatus($o['proposal_status_id']);
	$p->UpdateProposalSubStatus($o['proposal_sub_status_id']);
	
	$p->SetComment($o['proposal_comment_text'], $o['proposal_status_id'], $p->created_by);
	
	header("Location: ?action=display_detail&proposal_id=". $o['proposal_id']);
	
	return true;
	
}

/**
* DisplayProposalSubStatus()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 16 22:26:34 PST 2006
*/
function DisplayProposalSubStatus($o)
{
	global $smarty;
	$p = new proposalDB();
	$p->SetProposalId($o['proposal_id']);
	
	$proposal = $p->GetBasicDetail();
	$proposal_sub_status = CreateSmartyArray($p->GetProposalSubStatusByStatus($o['proposal_status_id']), 'proposal_sub_status_id', 'proposal_sub_status_description');
	$proposal['selected_proposal_status_description'] = $p->GetStatusDescriptionByStatusId($o['proposal_status_id']);
	
	$smarty->assign('proposal', $proposal);
	$smarty->assign('proposal_sub_status', $proposal_sub_status);
	$smarty->assign('meta', $o);
	
	DisplayHeader("Proposal Manager", "pgen");
	
	$smarty->display('app/pgen/vw_sub_status.tpl');
	
	DisplayFooter();
}

/**
* DeleteProposal()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 11 21:54:09 PST 2006
*/
function DeleteProposal($o)
{
	$p = new proposalDB();
	$p->SetProposalId($o['proposal_id']);
	$p->SetRevisionId($o['proposal_revision_id']);
	
	if (isset($o['proposal_revision_id']) && is_numeric($o['proposal_revision_id'])) {
		
		$proposal = $p->GetBasicDetail();
		$revision = $p->GetRevisionDetail();
		
		//we need to reset the next action
		$p->UpdateAttr('NEXT_ACTION', '');
		
		//mark revision deleted, 
		$p->UpdateProposalRevisionAuditStatus('N');
		
		//if the revision we deleted is the current revision then we need to set the current revision to the last revision
		//and reset the min/max values
		
		$revision_count = $p->GetRevisionCount();
		
		if ($revision_count > 0) {
			$p->SetRevisionNumber($revision_count);	
			
			$revision_id = $p->GetRevisionIdByRevision($revision_count);
			
		} else {
			$p->UpdateProposalAuditStatus('N');		
		}
		
		
	} elseif (isset($o['proposal_id']) && is_numeric($o['proposal_id'])) {
		$p->UpdateProposalAuditStatus('N');	
	}
	
	header("Location: ?action=display_list");	
	
}

/**
* UpdateProposalStatus()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 11 21:27:53 PST 2006
*/
function UpdateProposalStatus($o)
{	
	$p = new proposalDB();
	$p->SetProposalId($o['proposal_id']);
	
	$p->UpdateProposalStatus($o['proposal_status_id']);
	
	header("Location: ?action=display_list");	
}

/**
* FinalizeProposal()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Fri Jan 06 11:29:52 PST 2006
*/
function FinalizeProposal($o)
{
	$e = new Encryption();
   $p = new proposalDB();
   $p->SetProposalId($o['proposal_id']);
   $p->SetRevisionId($o['proposal_revision_id']);
   
   
   $p->UpdateAttr('NEXT_ACTION', '');
   
   header("Location: ?e=". $e->Encrypt("action=display_revision&proposal_id=". $o['proposal_id'] ."&proposal_revision_id=". $o['proposal_revision_id']));
   
}

/**
* DisplayProposalWon()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sat Dec 24 01:43:00 PST 2005
*/
function DisplayProposalWon($o)
{
   global $smarty;
   
   $p = new proposalDB();
   $p->SetProposalId($o['proposal_id']);
   $p->SetRevisionId($o['proposal_revision_id']);
   
   
   $revision = $p->GetRevisionDetail();
   $proposal = $p->GetBasicDetail();
   
   //options ////////////////////////////////////////////////////
    //we need to see if the options has been saved
   $rs_options = $p->GetRevisionOptions();
   
   //for single country multi options
   if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] > 1) {
      while ($r_option = mysql_fetch_assoc($rs_options)) {
      	$options[$r_option['option_number']] = $r_option;
      }

   } else {
      //this is for multi country multi options, single country single option
      while ($r_option = mysql_fetch_assoc($rs_options)) {
         $options[$r_option['option_number']][$r_option['sort_order']] = $r_option;
      }
   }
   
	$account = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($proposal['account_id']);
	
	//check whether Account status is pending for Accounting review
	if($account->GetAccountStatus() == 0 || $account->GetAccountStatus() == ACM_REVIEW_CONFIRMED ) {
	   if ($account->GetAccountType() != ACCOUNT_TYPE_CUSTOMER) {
	   	
	   	$_SESSION['msg_account_review'] = "You are trying to accept a proposal on a prospect account.<br>
	 		Accounting Department need to review this before you can proceed. Please type a message for Accounting Team. 
	 		You will receive an email once a decision has been made. You may then come back to Proposal and mark it as won.";
	   	header("Location: ../Account/DisplayCustomerReviewRequest/proposal_id/" . $o['proposal_id'] ."/proposal_revision_id/". $o['proposal_revision_id']);
	   }
	  
	   $revision_value = $p->GetRevisionMaxPrice();
	   $account_term = $account->GetAccountTerm();
	   
	   if ($account_term->GetCreditLimit() > 0 && $account_term->GetCreditLimit() < $revision_value['max_amount']) {
	   	
	   	$_SESSION['msg_account_review'] = "You are trying to accept a proposal which has a greater amount than accepted credit limit on the account.<br>
			You may request accounting department to review this case. Please type a message for Accounting Team. You will receive an email once a decision has been made. You may then come back to Proposal and mark it as won.";
	   	header("Location: ../Account/DisplayCreditLimitRequest/proposal_id/"  . $o['proposal_id']."/proposal_revision_id/". $o['proposal_revision_id']);
	   }   
	} 
	     
	//CUSTOM PRICING//
   if ($revision['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
      $rs = $p->GetRevisionCustomPricing();
      
      if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] > 1) {
         
         while ($r = mysql_fetch_assoc($rs)) {
      	   $custom[$r['option_number']][$r['pricing_item_group_id']]['amount'] = $r['amount'];
      	   $custom[$r['option_number']][$r['pricing_item_group_id']]['prcp_id'] = $r['proposal_revision_custom_pricing_id'];
   			$p_country[$r['option_number']] = $options[$r['option_number']]['country_description'];
      	   $t_custom[$r['option_number']] += $r['amount'];
   
      	   //this is a way around of having to put another nested loop, either way its lost of CPU cycles 
      	   //but this method cost 2 less loops
            $cpc_custom[$r['option_number']] = $t_custom[$r['option_number']] / $options[$r['option_number']]['completes'];
         }   
         
         $template = 'app/pgen/vw_custom_won_single.tpl';      
         
      } else {
         while ($r = mysql_fetch_assoc($rs)) {
      	   $custom[$r['option_number']][$r['sort_order']][$r['pricing_item_group_id']]['amount'] = $r['amount'];
      	   $custom[$r['option_number']][$r['sort_order']][$r['pricing_item_group_id']]['prcp_id'] = $r['proposal_revision_custom_pricing_id'];
   			$p_country[$r['option_number']][$r['sort_order']] = $options[$r['option_number']][$r['sort_order']]['country_description'];
      	   $t_custom[$r['option_number']][$r['sort_order']] += $r['amount'];
   
      	   //this is a way around of having to put another nested loop, either way its lost of CPU cycles but this method cost 2 less loops
            $cpc_custom[$r['option_number']][$r['sort_order']] = $t_custom[$r['option_number']][$r['sort_order']] / $options[$r['option_number']][$r['sort_order']]['completes'];
         }

         $template = 'app/pgen/vw_custom_won.tpl';        
      }

      $pricing_group = PrepareSmartyArray($p->GetPricingItemGroups());

   } else {
   
	   $rs_proposal = $p->GetRevisionBudgetLineItems();
	
	   $row = 1;
	   $last_option_number = 0;
	
	   if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] > 1) {
	
	      while ($r = mysql_fetch_assoc($rs_proposal)) {
	
	         $p_options[$r['sort_order']][$r['option_number']] = $r;
	         $p_country[$r['option_number']] = $r['country_description'];
	
	         if ($r['value_type'] == 'T') {
	            $summary[$r['option_number']]['total'] += $r['amount'];
	         }
	         
	         $summary[$r['option_number']]['cpc'] = 0;
	         
	         if ($options[$r['option_number']]['completes'] != 0) {
	             $summary[$r['option_number']]['cpc'] = $summary[$r['option_number']]['total'] / $options[$r['option_number']]['completes'];
	         }
	         
	      }
	      
	      $template = 'app/pgen/vw_proposal_won_single.tpl';
	
	   } else {
	
	      while ($r = mysql_fetch_assoc($rs_proposal)) {
	
	         //the tricky part here is if we are under the assumption that country will align up together
	         $p_options[$r['option_number']][$r['sort_order']][] = $r;
	         $p_total[$r['option_number']][$r['sort_order']] += $r['amount'];
	         $p_country[$r['option_number']][$r['proposal_revision_option_id']] = $r['country_description'];
	         
	         if ($r['value_type'] == 'T') {
	            $summary[$r['option_number']][$r['c_sort_order']]['total'] += $r['amount'];
	         }
	         
	         $summary[$r['option_number']][$r['c_sort_order']]['cpc'] = 0;
	         
	         if($options[$r['option_number']][$r['c_sort_order']]['completes'] != 0) {
	         	$summary[$r['option_number']][$r['c_sort_order']]['cpc'] = $summary[$r['option_number']][$r['c_sort_order']]['total'] / $options[$r['option_number']][$r['c_sort_order']]['completes'];
	         }
	         
	      }
	      
	      $template = 'app/pgen/vw_proposal_won.tpl';
	   }
   }
	   
   $smarty->assign('revision', $revision); //TODO name revision
   $smarty->assign('proposal', $proposal);
   $smarty->assign('sample_types', $sample_types);
   $smarty->assign('qf_ctr', $qf_ctr);
   $smarty->assign('qf_file', $qf_file);
   $smarty->assign('orglist', $orglist);
   $smarty->assign('options', $options);
   $smarty->assign('p_options', $p_options);
   $smarty->assign('services', $services);
   $smarty->assign('list', $list);
   $smarty->assign('total', $p_total);
   $smarty->assign('country', $p_country);
   $smarty->assign('custom', $custom);
   $smarty->assign('pricing_group', $pricing_group);
   $smarty->assign('t_custom', $t_custom);
   $smarty->assign('cpc_custom', $cpc_custom);
   $smarty->assign('meta', $meta);
   $smarty->assign('approval', $approval);
   $smarty->assign('summary', $summary);
   
   DisplayHeader("Proposal Manager", "pgen");
   $smarty->display($template);
   DisplayFooter();
}

/**
 * Check proposal status on Check Proposal Status button
 *
 * @param array $o 
 */ 
function CheckProposalStatus($o) 
{ 
	$proposal = Hb_App_ObjectHelper::GetMapper('Hb_App_Proposal_Proposal')->Find($o['proposal_id']);
	 
   if ($proposal->GetProposalStatusId() == PROPOSAL_STATUS_ACCOUNT_REVIEW) {
   	$account = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($proposal->GetAccountId());
   	
   	if ($account->GetAccountType() == ACCOUNT_TYPE_CUSTOMER) {
   		$proposal->SetProposalStatusId(PROPOSAL_STATUS_WORK_PROGRESS);
   		
   		if ($proposal->GetAccountTypeId() == ACCOUNT_TYPE_PROSPECT) {
   			$proposal->SetAccountTypeId(ACCOUNT_TYPE_CUSTOMER);
   			$proposal_attrs 	= $proposal->GetAttributes();
   			
   			$account_type_attr 	= null;
   	 
   	 		try {
   	 	 		$account_type_attr = $proposal_attrs->GetAttribute('ACCOUNT_TYPE');
   	 	 		$account_type_attr->SetAttributeValue('C');
   	 		} catch (Hb_Data_ObjectNotInCollectionException $e) {
   	 	 		$account_type_attr = new Hb_App_Proposal_ProposalAttribute(null, $o['proposal_id'], 'ACCOUNT_TYPE', 'C');
   	 		}
   		}
   		Hb_App_ObjectWatcher::commit();
   		
   	} else {
   		$_SESSION['pgen_message'] = "Account is not verified, unable to make proposal won";
   	}
   }
   header("Location: ?action=display_revision&proposal_id=" . $o['proposal_id'] . "&proposal_revision_id=" . $o['proposal_revision_id']);
}

/**
* DisplayReviewProposal()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Oct 20 15:40:53 PDT 2005
*/
function DisplayReviewProposal($o)
{
   global $smarty;

   $p = new proposalDB();
   $p->SetProposalId($o['proposal_id']);
   $p->SetRevisionId($o['proposal_revision_id']);

   //timezone stuff
   $tz = GetTimeZone($o);
   $p->tz = $tz;
   
   $proposal = $p->GetBasicDetail();
   $revision = $p->GetRevisionDetail();

   $rs = $p->GetRevisionBudgetLineItems();

   $row = 1;
   $last_option_number = 0;

   if ($proposal['number_of_countries'] == 1 && $proposal['number_of_options'] > 1) {

      while ($r = mysql_fetch_assoc($rs)) {
         $options[$r['sort_order']][$r['option_number']] = $r;
         $country[$r['option_number']] = $r['country_description'];
         
         if ($r['value_type'] == 'T') {
            $summary[$r['option_number']]['total'] += $r['amount'];
            
            $summary[$r['option_number']]['cpc'] = 0;
            
            if ($r['completes']) {
                $summary[$r['option_number']]['cpc'] = $summary[$r['option_number']]['total'] / $r['completes'];
            }
         }
         
      }

      $template = 'app/pgen/vw_review_proposal_single.tpl';

   } else {

      while ($r = mysql_fetch_assoc($rs)) {

         //the tricky part here is if we are under the assumption that country will align up together
         $options[$r['option_number']][$r['sort_order']][] = $r;
         $total[$r['option_number']][$r['sort_order']] += $r['amount'];
         $country[$r['option_number']][$r['c_sort_order']]['country_description'] = $r['country_description'];
         $country[$r['option_number']][$r['c_sort_order']]['sub_group_description'] = $r['sub_group_description'];
         
         
         if ($r['value_type'] == 'T') {
            $summary[$r['option_number']][$r['c_sort_order']]['total'] += $r['amount'];
            
            $summary[$r['option_number']][$r['c_sort_order']]['cpc'] = 0;
            if ($r['completes']) {
                $summary[$r['option_number']][$r['c_sort_order']]['cpc'] = $summary[$r['option_number']][$r['c_sort_order']]['total'] / $r['completes'];
            }
            
         }
      }

      $template = 'app/pgen/vw_review_proposal.tpl';
   }

	$meta['display_txt_sub_group'] = 0;
	
	if ($revision['proposal_option_type_id'] == PROPOSAL_OPTION_TYPE_SINGLE_SUB)
		$meta['display_txt_sub_group'] = 1;


	if (isset($_SESSION['ppm_message']) && $_SESSION['ppm_message'] != '') {
		$meta['message'] = $_SESSION['ppm_message'];
		unset($_SESSION['ppm_message']);
	}
   //$list['proposal_budget'] = PrepareSmartyArray($p->GetProposalBudgetLineItems());

   //DisplayHeader("Proposal Manager", "pgen");

   $smarty->assign('list', $list);
   $smarty->assign('proposal', $proposal);
   $smarty->assign('revision', $revision);
   $smarty->assign('options', $options);
   $smarty->assign('total', $total);
   $smarty->assign('country', $country);
   $smarty->assign('summary', $summary);
   $smarty->assign('meta', $meta);
   $smarty->display($template);

   //DisplayFooter();
}

/**
* DisplayAdd()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jul 20 11:39:30 PDT 2005
*/
function DisplayAdd($o)
{
   global $smarty;

   $c = new commonDB();
   $p = new proposalDB();
   $u = new userDB();
   $s = new studyDB();
   //$partner = new partnerDB(0);
   
   //error displaying
   if (isset($_SESSION['pgen_errors'])) {
   	$meta['message'] = 'Unable to Add Proposal Following Errors Were Found';
   	$smarty->assign('errors', $_SESSION['pgen_errors']);
   	unset($_SESSION['pgen_errors']);
   }
   
   $meta['do_update']   = 0;
   $meta['proposal_id'] = 0;
  
   if (isset($o['proposal_id']) && $o['proposal_id'] != '') {
   	
      $p->SetProposalId($o['proposal_id']);
      $proposal = $p->GetBasicDetail();
      //$partner->SetPartnerId($proposal['account_id']);

      $contacts = HBRPCCall("acm", "GetAccountContacts", array('account_id' => $proposal['account_id']));
      
      for ($i=0; $i < count($contacts['data']); $i++) {
      	$list['contact'][$contacts['data'][$i]['contact_id']] = $contacts['data'][$i]['contact_first_name'] . " ". $contacts['data'][$i]['contact_last_name'];
      }
      
     
      $list['account'] = array('account_id' => $proposal['account_id'], 'account_name' => $proposal['account_name']);
      
      //$list['contact'] = CreateSmartyArray($partner->getContacts(), 'contact_id', 'name');
      //$list['ae']      = CreateSmartyArray($c->GetUsersByRoleId(ROLE_ACCOUNT_EXECUTIVE), 'login', 'name');
      //$list['am']      = CreateSmartyArray($c->GetUsersByRoleId(ROLE_ACCOUNT_MANAGER), 'login', 'name');
      
      $meta['do_update'] = 1;
      $meta['proposal_id'] = $o['proposal_id'];
      
   }
 

   $list['region']     = CreateSmartyArray($c->GetCustomRegions(),'region_id','region_name');
   $list['country']    = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
   $list['department'] = CreateSmartyArray($c->GetSourceDepartments(), 'functional_group_id', 'functional_group_description');
   $list['p_writer']   = CreateSmartyArray($c->GetUsersByRoleId(ROLE_PROPOSAL_WRITER), 'login', 'name');
   $list['f_assesor']  = CreateSmartyArray($c->GetUsersByRoleId(ROLE_FEASIBILITY_ASSESSOR), 'login', 'name');
   $list['p_status']   = CreateSmartyArray($p->GetProposalStatusList(), 'proposal_status_id', 'proposal_status_description');
   $list['proposal_type'] = CreateSmartyArray($p->GetProposalTypeList(), 'proposal_type_id', 'proposal_type_description');

   $list['data_collection_method'] = CreateSmartyArray($s->GetStudyInterviewTypes(), 'study_interview_type_id', 'study_interview_type_description');
   $list['fieldwork_duration'] = CreateSmartyArray($s->GetStudyFieldWorkDurations(), 'study_fieldwork_duration_id', 'study_fieldwork_duration_description');
   $list['proposal_option_type'] = CreateSmartyArray($p->GetProposalOptionTypes(), 'proposal_option_type_id', 'proposal_option_type_description');
   $list['sample_type'] = CreateSmartyArray($p->GetSampleTypes(), 'sample_type_id', 'sample_type_description');
   
   if ($_SESSION['ppm_message'] != '') {
      $meta['message'] = $_SESSION['ppm_message'];
      unset($_SESSION['ppm_message']);
   }
  
   //DisplayHeader("Proposal Manager", "pgen", $o['action']);

   $smarty->assign('lang', $o['lbl']);
   $smarty->assign('list', $list);
   $smarty->assign('meta', $meta);
   $smarty->assign('proposal', $proposal);

   $smarty->display('app/pgen/vw_add_basic.tpl');

   //DisplayFooter();
}

/**
* SaveProposal()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sat Jul 23 10:38:13 PDT 2005
*/
function SaveProposal($o)
{
	//$e 		= new Encryption();
	$errors 	= array();
	
	$account 		= Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($o['account_id']);
	$account_term 	= $account->GetAccountTerm();

	if($account_term->isProposable()) {
		$errors[] = 'Account on Credit Hold, You are not allowed to create a proposal for this account. Please Contact Accounting Department for Further Details.';
		$_SESSION['pgen_errors'] = $errors;
		
		//header("Location: ?e=". $e->Encrypt("action=display_add"));
		return false;
	}
	
	//all references to partnerDB must be replaced with HBRPC ACM code
	
   $c = new commonDB();
   $p = new proposalDB();   
   $u = new userDB();
	//   $partner = new partnerDB(0);

   //find the functional group of the user
   $fg_id = 1;
   //$r = $u->GetUserFunctionalGroup($_SESSION['user_id']);
   //if ($r) {
   //	$fg_id = $r['functional_group_id'];
   //}
   $revision = 1;
   
   //this is to make sure there is no fire on April first when pricing goes live
   try {
   		$version  = Hb_App_ObjectHelper::GetMapper('Hb_App_Proposal_ProposalSetting')->FindBySettingName('CURRENT_VERSION')->GetSettingValue();	
   } catch (Exception $ex) {
   		$version = 2;
   }

   $o['proposal_date'] = date("Y-m-d");

   //ACM GET CONTACT DETAIL
   //$r_contact = $partner->GetContactDetail($o['contact_id']);
   $params = array("account_id" => $o['account_id'], "contact_id" => $o['contact_id']);
   $account = HBRPCCall("acm", "GetAccountDetail", $params);
   $contact = HBRPCCall("acm", "GetAccountContactDetails", $params);
   //ACM Get ACCOUNT DETAIL 
   //get country_code, region_id, account_type_id, account_name, get account roles
   
   
   /* account type not set */
   if (!isset($account['type']['account_type_id']) || $account['type']['account_type_id'] == '') {
   	$errors[] = 'ACM: Account Type Not Defined';	
   }
   
   /* license level */
   if (!isset($account['product']['license_level_id']) || $account['product']['license_level_id'] == '') {
   	$errors[] = 'ACM: License Level Not Found';	
   }
   
   /* pricing regime */
   if (!isset($account['product']['pricing_regime_id']) || $account['product']['pricing_regime_id'] == '') {
   	$errors[] = 'ACM: Pricing Regime Not Found';	
   }
   
   if (!empty($errors)) {
   	$_SESSION['pgen_errors'] = $errors;
   	header("Location: ?action=display_add");
   	return true;
   }
   
   $proposal_id = 0;
   //need update proposal
   if ($o['do_update'] == 0) {
//		print 'update';
		$p->SetProposal($o['proposal_name'], $account['account_id'], $account['account_name'], $account['type']['account_type_id'], 
								$account['product']['license_level_id'], $account['product']['pricing_regime_id'], $account['product']['product_id'], 
								$fg_id, PPM_STATUS_IN_PROGRESS, $account['region_id'], $account['country_code'], $o['proposal_date'], $revision, $_SESSION['user_id'], $_SESSION['user_id'], $version);
								                
      $proposal_id = $p->lastID;
      
      $p->SetProposalId($proposal_id);
      
      $p->SetContact($o['contact_id'], $contact['contact_first_name'], $contact['contact_last_name'], $contact['contact_email']);

		for ($i=0; $i < count($account['user']); $i++) {
			$p->SetRole($account['user'][$i]['role_id'], $account['user'][$i]['user_id']);	
		}
		
		for ($i=0; $i < count($account['attr']); $i++) {
			$p->SetAttr($account['attr'][$i]['account_attr_name'], $account['attr'][$i]['account_attr_value']);	
		}
                      
   } else {
//      print 'new';
      $proposal_id = $o['proposal_id'];
      
      $p->SetProposalId($o['proposal_id']);
      
      $p->UpdateProposal($o['proposal_name'], $account['account_id'], $account['account_name'], $account['type']['account_type_id'], 
								$account['product']['license_level_id'], $account['product']['pricing_regime_id'], $account['product']['product_id'], 
								$fg_id, PPM_STATUS_IN_PROGRESS, $account['region_id'], $account['country_code'], $o['proposal_date'], $revision, $_SESSION['user_id'], $_SESSION['user_id']);
                        
      $p->UpdateContact($o['contact_id'], $contact['contact_first_name'], $contact['contact_last_name'], $contact['contact_email']);

		for ($i=0; $i < count($account['user']); $i++) {
			$p->UpdateRole($account['user'][$i]['role_id'], $account['user'][$i]['user_id']);	
		}
		
		for ($i=0; $i < count($account['attr']); $i++) {
			$p->UpdateAttr($account['attr'][$i]['account_attr_name'], $account['attr'][$i]['account_attr_value']);	
		}
			
		$preffered_currency = $p->GetAttr('GLOBAL_PREFFERED_CURRENCY');
   
   	if ($preffered_currency && $preffered_currency != 'USD') {
   	    
   		$exchange_rate = $c->GetCurrencyMultiplier($preffered_currency);
   	
   		if ($p->isAttrSet('PPM_EXCHANGE_RATE_USED')) {
   			$p->UpdateAttr('PPM_EXCHANGE_RATE_USED', $exchange_rate);
   		} else {
   			$p->SetAttr('PPM_EXCHANGE_RATE_USED', $exchange_rate);
   		}   		
   		
   	}
   	
   }

   $p->SetAttr('NEXT_ACTION', 'display_add_revision');
   
   return $proposal_id;

}

/**
* DisplayDetail()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sat Jul 23 14:48:12 PDT 2005
*/
function DisplayDetail($o)
{
   global $smarty;
   $e = new Encryption();
   $p = new proposalDB();
   $p->SetProposalId($o['proposal_id']);
   
   //we should redirect here if the revision count is only 1
   
   if ($_SESSION['user_type_id'] != USER_TYPE_EXTERNAL) {
	   if ($p->GetRevisionCount() <= 1) {
	   
	      $next_action = $p->GetAttr('NEXT_ACTION');
	      
	      if ($next_action != '') {
	         
	         $url = "action=". $next_action ."&proposal_id=". $o['proposal_id'];
	         
	         $revision_id = $p->GetAttr('WORKING_REVISION');
	         
	         if ($revision_id != '') {
	            $url .= "&proposal_revision_id=". $revision_id;
	         }
	         header("Location: ?e=". $e->Encrypt($url));
	         return true;
	      }
	   }
   }
   
   $template = 'app/pgen/vw_detail.tpl';
   
   if ($_SESSION['user_type_id'] == USER_TYPE_EXTERNAL) {
   	$template = 'app/pgen/ext/vw_detail.tpl';
   }
   
   //timezone stuff
   $tz = GetTimeZone($o);
   $p->tz = $tz;

   $proposal = $p->GetBasicDetail();
   
   $revisions = PrepareSmartyArray($p->GetRevisions());
   $proposal['comments'] = PrepareSmartyArray($p->GetComments());
   //print_r($proposal);
   if (isset($_SESSION['ppm_message']) && $_SESSION['ppm_message'] != '') {
   	$meta['message'] = $_SESSION['ppm_message'];
   	unset($_SESSION['ppm_message']);
   }

   $smarty->assign('lang', $o['lbl']);
   $smarty->assign('proposal', $proposal);
   $smarty->assign('revisions', $revisions);
   $smarty->assign('meta', $meta);
   
   DisplayHeader("Proposal Manager", "pgen");
   
   $smarty->display($template);

   DisplayFooter();

}

/**
* GetComments()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Mar 28 12:15:48 PST 2006
*/
function GetComments($o)
{
	$p = new proposalDB();
   $p->SetProposalId($o['proposal_id']);
   
   $proposal = $p->GetBasicDetail();
   
   if ($o['send_xml_comment_list'] == 1) {
   	global $smarty;
   	$list['comment'] = PrepareSmartyArray($p->GetComments());
   	
   	$smarty->assign('list', $list);
   	header("Content-Type: text/xml");
   	$smarty->display('app/pgen/xml_comments.tpl');
   	return true;
   }
}


/**
* SaveComment()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun Jul 24 12:09:20 PDT 2005
*/
function SaveComment($o)
{
  	$p = new proposalDB();
   $p->SetProposalId($o['proposal_id']);
   
   $proposal = $p->GetBasicDetail();

   $p->SetComment($o['proposal_comment_text'], $proposal['proposal_status_id'], $o['created_by']);
   
   if ($o['send_xml_comment_list'] == 1) {
   	global $smarty;
   	$list['comment'] = PrepareSmartyArray($p->GetComments());
   	
   	$smarty->assign('list', $list);
   	header("Content-Type: text/xml");
   	$smarty->display('app/pgen/xml_comments.tpl');
   	return true;
   }

   $e = new Encryption();

   $url = "action=display_detail&proposal_id=".$o['proposal_id'];
   $e_url = $e->Encrypt($url);
   header("Location: ?e=".$e_url);
}


/**
 * 
 * @return unknown_type
 */
function DisplaySearch()
{
	global $smarty;
	
	$smarty->display('app/pgen/vw_search.tpl');
}

/**
* DisplayProposalList()
*
* @since  - Wed Jul 20 04:43:23 PDT 2005
*/
function DisplayProposalList($o)
{
	global $userRights, $status_titles, $encryption, $smarty;
   $p = new proposalDB();
   $c = new commonDB();
   $a = new accountDB();

	$start = 0;
   $sort = 'p.created_date DESC';
   $meta['filter_on'] = 0;

   
   //timezone stuff
   $p->tz = GetTimeZone($o);
   $o['time_zone'] = $p->tz;

   if (isset($o['page_size']) && is_numeric($o['page_size'])) {
   	$page_size = $o['page_size'];
   } else {
   	$page_size = DEFAULT_PAGE_SIZE;
   }

   //turning off filter
   if ($o['filter_off'] == 1) {
      unset($_SESSION['ppm_main_filter']);
      unset($_SESSION['ppm_main_filter_on']);
   }

   //we are doing a search
   if ($o['action'] == 'do_search') {
      $_SESSION['ppm_main_filter']        = $p->BuildSearchString($o);
      $_SESSION['ppm_main_filter_on']     = 1;
      $_SESSION['ppm_main_search_o']      = $o;
      $_SESSION['ppm_main_advanced_flag'] = $o['advanced_flag'];

      //lets do a redirect after building our search so we dont end up with page expired
      $url = "action=display_list&advanced_flag=".$o['advanced_flag'];
      $encrypted_url = $encryption->Encrypt($url);
      header("Location: ?e=".$encrypted_url);
      return true;
   }

   if (isset($_SESSION['ppm_main_advanced_flag'])) {
      $o['advanced_flag'] = $_SESSION['ppm_main_advanced_flag'];
   }

   if ($o['sort'] != '') {
      $_SESSION['ppm_main_sort'] = $o['sort'];
      $url = "action=display_list&s=1";
      $encrypted_url = $encryption->Encrypt($url);
      header("Location: ?e=".$encrypted_url);
      return true;
   }

   if (isset($_SESSION['ppm_main_filter'])) {
      $filter = $_SESSION['ppm_main_filter'];
      $o['so'] = $_SESSION['ppm_main_search_o'];
      $meta['filter_on'] = 1;
   } else { /* default search is only for the last 30 days to improve load times only for internal users */
   	
   	if ($_SESSION['user_type_id'] == 1) {
   		$end_date   = date("Y-m-d 23:59:59");
   		$start_date = date("Y-m-d 00:00:00", strtotime("-30 day"));
   		$filter = " AND p.created_date BETWEEN '". $start_date ."' AND '". $end_date ."' ";   	
   	}
   	
   }
   
   BuildPageLimit($o, $start, $page_size);

   if (isset($_SESSION['ppm_main_sort']) && $o['s'] == 1) {
      $sort = $_SESSION['ppm_main_sort'];
      $o['sort_by_column'] = $sort;
   }

   if ($o['display_all'] == 1) {
      $page = '';
   } else {
      $page = 'LIMIT '.$start.','.$page_size;
   }
   
   //number of rows in the current page
   $list = PrepareSmartyArray($p->GetList('', $page, $sort));
   $o['page_rows'] = $p->rows;

   //see the total rows
   $rs = $p->GetList('', '');

   //total number of rows
   $o['total_rows'] = $p->rows;
   $o['start_row'] = $start;
   $o['page_size'] = $page_size;
   $o['report_title'] = 'Proposal List';
   
   $page_list[1] = 0;
   
   for ($cnt = $page_size; $cnt <= $p->rows; $cnt += $page_size) {
   	$page_list[] = $cnt;
   }
   
   $meta['page_list']    = array_flip($page_list);
   $meta['last_page_start'] = $page_list[count($page_list)];
   $meta['page_size_list'] = array(10 => 10, 25 => 25, 50 => 50, 100 => 100);

   $l['region']          = CreateSmartyArray($c->GetCustomRegions(),'region_id','region_name');
   $l['country']         = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
   $l['department']      = CreateSmartyArray($c->GetSourceDepartments(), 'functional_group_id', 'functional_group_description');
   $l['p_writer']        = CreateSmartyArray($c->GetUsersByRoleId(ROLE_PROPOSAL_WRITER), 'login', 'name');
   $l['f_assesor']       = CreateSmartyArray($c->GetUsersByRoleId(ROLE_FEASIBILITY_ASSESSOR), 'login', 'name');
   $l['p_type']          = CreateSmartyArray($p->GetProposalTypeList(), 'proposal_type_id', 'proposal_type_description');
   $l['p_status']        = CreateSmartyArray($p->GetProposalStatusList(), 'proposal_status_id', 'proposal_status_description');
   $l['am']              = CreateSmartyArray($c->GetUsersByRoleId(ROLE_ACCOUNT_MANAGER), 'login', 'name');
   $l['ae']              = CreateSmartyArray($c->GetUsersByRoleId(ROLE_ACCOUNT_EXECUTIVE), 'login', 'name');
   $l['pricing_type']    = CreateSmartyArray($p->GetPricingTypes(), 'pricing_type_id', 'pricing_type_description');
   $l['sample_type']     = CreateSmartyArray($p->GetSampleTypes(), 'sample_type_id', 'sample_type_description');
   $l['revision_status'] = CreateSmartyArray($p->GetRevisionStatusList(), 'proposal_revision_status_id', 'proposal_revision_status_description');
   $l['account_type']    = CreateSmartyArray($a->GetAccountTypeList(), 'account_type_id', 'account_type_description');
      
   $report_header_name = 'pgen_main_internal';
   $template_name      = 'app/pgen/vw_list2.tpl';
   
   if ($_SESSION['user_type_id'] == 2) {
   	$report_header_name = 'pgen_main_external';
   	$template_name      = 'app/pgen/ext/vw_list2.tpl';
   }
   
   $header = $this->GetPgenReportHeaders($report_header_name);

   $o = array_merge($o, $meta);

   $smarty->assign('header',$header);
   $smarty->assign('list', $list);
   $smarty->assign('lang', $o['lbl']);
   $smarty->assign('l', $l);
   $smarty->assign('meta',$o);

//   if ($o['export_excel'] == 1) {
//   	header('Content-type: application/vnd.ms-excel');
//      header("Content-disposition: attachment; filename=\"proposal_list_".date('Ymd').".xls\"");
//      $smarty->display('app/pgen/rp_list.tpl');
//   		
//   } else {
////   		header("Content-Type: text/xml");
////   		$smarty->display($template_name);
//   }

   return true;
}

/**
* GetPgenReportHeaders()
*
* @param
* @param 
* @return
* @since  - 21:02:54
*/
function GetPgenReportHeaders($report_type)
{
	switch ($report_type) {
		case 'pgen_main_internal':
			$header[0]['field'] = 'p.proposal_name';
		   $header[0]['title'] = 'Proposal<br>Name';
		
		   $header[1]['field'] = 'account_id';
		   $header[1]['title'] = 'Account<br>ID';
		
		   $header[2]['field'] = 'account_name';
		   $header[2]['title'] = 'Account<br>Name';
		   
		   $header[3]['field'] = 'pc.last_name';
		   $header[3]['title'] = 'Account Contact';
		
		   $header[4]['field'] = 'pr.proposal_type_id';
		   $header[4]['title'] = 'Proposal Type';
		   
		   $header[5]['field'] = 'pr.pricing_type_id';
		   $header[5]['title'] = 'Pricing Type';
		
		   $header[6]['field'] = 'ae_name';
		   $header[6]['title'] = 'Account<br>Executive';
		
		   $header[7]['field'] = 'am_name';
		   $header[7]['title'] = 'Account<br>Manager';
		
		   $header[8]['field'] = 'fg_user_name';
		   $header[8]['title'] = 'Proposal Writer';
		
		   $header[9]['field'] = 'p.proposal_date';
		   $header[9]['title'] = 'Proposal<br>Date';
		
		   $header[10]['field'] = 'p.proposal_id';
		   $header[10]['title'] = 'Proposal Number';
		
		   $header[11]['field'] = 'prst.proposal_revision_sample_type_id';
		   $header[11]['title'] = 'Sample Type';
		
		   $header[12]['field'] = 'pr.max_amount';
		   $header[12]['title'] = 'Max Proposal<br>Value';
		
		   $header[13]['field'] = 'ps.proposal_status_description';
		   $header[13]['title'] = 'Status';
		   
		   $header[14]['field'] = 'prs.proposal_revision_status_description';   
		   $header[14]['title'] = 'Revision Status';
			break;
			
		case 'pgen_main_external':
			
			$header[0]['field'] = 'p.proposal_id';
		   $header[0]['title'] = 'Proposal<br>Number';
			
			$header[1]['field'] = 'p.proposal_name';
		   $header[1]['title'] = 'Proposal<br>Name';
		   
		   $header[2]['field'] = 'pc.last_name';
		   $header[2]['title'] = 'Account Contact';
		
		   $header[3]['field'] = 'pr.proposal_type_id';
		   $header[3]['title'] = 'Proposal Type';
		   
		   $header[4]['field'] = 'p.proposal_date';
		   $header[4]['title'] = 'Proposal<br>Date';
		   
		   $header[5]['field'] = 'prst.proposal_revision_sample_type_id';
		   $header[5]['title'] = 'Sample Type';
		
		   $header[6]['field'] = 'pr.max_amount';
		   $header[6]['title'] = 'Max Proposal<br>Value';
		
		   $header[7]['field'] = 'ps.proposal_status_description';
		   $header[7]['title'] = 'Status';
		   
		   $header[8]['field'] = 'prs.proposal_revision_status_description';   
		   $header[8]['title'] = 'Revision Status';
			
			break;
			
		case 'pgen_auction_internal':
			$header[0]['field'] = 'pa.auction_name';
		   $header[0]['title'] = 'Auction<br>Name';
			
			$header[1]['field'] = 'pa.proposal_auction_id';
		   $header[1]['title'] = 'Auction<br>#';
		   
		   $header[2]['field'] = 'paa_aname.proposal_auction_attr_value';
		   $header[2]['title'] = 'Account Name';
		
		   $header[3]['field'] = 'paa_pname.proposal_auction_attr_value';
		   $header[3]['title'] = 'Proposal Name';
		   
		   $header[4]['field'] = 'pa.proposal_id';
		   $header[4]['title'] = 'Proposal Number';
		   
		   $header[5]['field'] = 'pa.sample_type_id';
		   $header[5]['title'] = 'Sample Type';
		   
		   $header[6]['field'] = 'pa.country_code';
		   $header[6]['title'] = 'Country';
		
		   $header[7]['field'] = 'pau.login';
		   $header[7]['title'] = 'Auction Owner';
		
		   $header[8]['field'] = 'paac_cb.contact_first_name';
		   $header[8]['title'] = 'Low Bidder';
		   
		   $header[9]['field'] = 'hrs_left';   
		   $header[9]['title'] = 'Hrs Left';
		   
		   $header[10]['field'] = 'pa.auction_end_date';   
		   $header[10]['title'] = 'Date Closed';
			
			break;
			
		case 'pgen_auction_external':
			$header[0]['field'] = 'pa.auction_name';
		   $header[0]['title'] = 'Auction<br>Name';
			
			$header[1]['field'] = 'pa.proposal_auction_id';
		   $header[1]['title'] = 'Auction<br>#';

		   $header[2]['field'] = 'pa.proposal_id';
		   $header[2]['title'] = 'Proposal Number';
		   
		   $header[3]['field'] = 'pa.sample_type_id';
		   $header[3]['title'] = 'Sample Type';
		   
		   $header[4]['field'] = 'pa.country_code';
		   $header[4]['title'] = 'Country';
		
		   $header[5]['field'] = 'pau.login';
		   $header[5]['title'] = 'Auction Owner';
		
		   $header[6]['field'] = 'paac_cb.contact_first_name';
		   $header[6]['title'] = 'Low Bidder';
		   
		   $header[7]['field'] = 'hrs_left';   
		   $header[7]['title'] = 'Hrs Left';
		   
		   $header[8]['field'] = 'pa.auction_end_date';   
		   $header[8]['title'] = 'Date Closed';
			
			break;
	
		default:
			break;
	}
	
	
   
   return $header;
	
}




}


?>
