<?php

class pgen_RevisionManager
{

	/**
* CreateCustomRevision()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sat Dec 24 04:32:43 PST 2005
*/
function CreateCustomRevision($o)
{
	$pricing_manager = new pgen_PricingManager();
	
	global $smarty;
	$e = new Encryption();
   $p = new proposalDB();
   $p_new = new proposalDB();
   
   $p->SetRevisionId($o['proposal_revision_id']);
   $p->SetProposalId($o['proposal_id']);
   
   $p_new->SetProposalId($o['proposal_id']);
   
   //$partner = new partnerDB(0);
   
   $p->UpdateRevisionStatus(PROPOSAL_REVISION_STATUS_REVISED);

   //create new revision
   $r = $p->GetRevisionDetail();

   //we need to find out the revision count
   $revision_count = $p->GetRevisionCount();

   //basic detail
   $proposal = $p->GetBasicDetail();

   $revision = $revision_count + 1;

   $p_new->SetRevision($r['proposal_id'], $revision, $r['study_interview_type_id'], $r['study_setup_duration_id'] , $r['study_fieldwork_duration_id'], $r['study_data_processing_duration_id'], 
                       $r['proposal_option_type_id'], $r['proposal_type_id'], count($o['selected_country']), 1,
                       $_SESSION['admin_id'], $_SESSION['user_id'], $r['pricing_type_id']);

   $proposal_revision_id = $p_new->lastID;
   
   echo "1 --- ". $proposal_revision_id;

   $p_new->SetRevisionNumber($revision);
   $p_new->SetRevisionId($proposal_revision_id);
   $p_new->SetRevisionDateSent();
   
   //need to copy comments
   $rs = $p->GetRevisionComments();
   while ($r_rc = mysql_fetch_assoc($rs)) {
   	$p_new->SetRevisionComment($proposal_revision_id, $r_rc['proposal_revision_comment_type_id'], $r_rc['comment']);
   }


   $rs = $p->GetRevisionSampleTypes();
   while ($r_st = mysql_fetch_assoc($rs)) {
   	$p_new->SetRevisionSampleType($proposal_revision_id, $r_st['sample_type_id']);
   }

   $rs = $p->GetRevisionServiceList();
   while ($r_sl = mysql_fetch_assoc($rs)) {
   	$p_new->SetRevisionService($proposal_revision_id, $r_sl['service_id']);
   }

   //create pricing
   $account_id = $proposal['account_id'];
   $license_level_id = $proposal['license_level_id'];

   
   $pricing_manager->CalculatePricing($account_id, $license_level_id, $o['proposal_id'], $proposal_revision_id);


   //copy options
   $rs = $p->GetRevisionOptionByOptionNumber($o['selected_option']);

   $sort_order = 1;
   
   $action = 'display_revision';
   
   if ($r['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
   	$action = 'display_revision';
   }
   
   while ($r_option = mysql_fetch_assoc($rs)) {
   	if (in_array($r_option['sort_order'], $o['selected_country'] )) {
         $p_new->SetOption($proposal_revision_id, $r_option['country_code'], 1, $r_option['sub_group_description'],
   	                    $r_option['study_programming_type_id'], $r_option['translation'], $r_option['translation_language_code'],
   	                    $r_option['overlay'], $r_option['overlay_language_code'], $r_option['study_datasource_id'], $r_option['incidence_rate'],
   	                    $r_option['completes'], $r_option['questions_programmed'], $r_option['questions_per_interview'], $r_option['questions_per_screener'],
   	                    $r_option['data_recording_hours'], $r_option['data_tab_hours'], $r_option['data_import_hours'], $r_option['data_export_hours'],
   	                    $r_option['open_end_questions'], $r_option['incidence_of_open_end'], $r_option['avg_words_per_open_end'], $r_option['open_end_text_coding_hours'],
   	                    $r_option['respondent_portal_type_id'], $r_option['respondent_portal_programming_hours'], $r_option['panel_import_hours'], $sort_order, $r_option['client_portal_programming_hours']);
   	   $sort_order++;
   	   
   	   //setting the custom pricing
   	   if ($r['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
   	   	$proposal_revision_option_id = $p_new->lastID;
   	   	$rs_custom = $p->GetRevisionCustomPriceByOptionId($r_option['proposal_revision_option_id']);
   	   	while ($r_custom = mysql_fetch_assoc($rs_custom)) {
   	   		$p_new->SetRevisionCustomPrice($proposal_revision_option_id, $r_custom['pricing_item_group_id'], $r_custom['amount']);	
   	   	}
   	   	
   	   	$total += $o['total_'. $r_option['proposal_revision_option_id']];
   		}
   	   
   	}
   }
   
   CalculateProposal($o['proposal_id'], $proposal_revision_id);
   
   $p_new->UpdateRevisionStatus(PROPOSAL_REVISION_STATUS_CLIENT_ACCEPTED);
   
   if ($r['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
   	$p_new->SetRevisionMaxPrice($total);
   	$p_new->SetRevisionMinPrice($total);
   }
   
   $revision = $p_new->GetRevisionDetail();
   
   $workflow = new pgen_WorkflowManager();
   $status = $workflow->GetReviewStatusCode($o['proposal_id'], $proposal_revision_id);
   
   $summary = $pricing_manager->GetPricingSummary($o['proposal_id'], $proposal_revision_id);
   $smarty->assign('proposal', $proposal);
	$smarty->assign('revision', $revision);
	$smarty->assign('summary', $summary);
      
	$msg = $smarty->fetch('app/pgen/email_proposal_won.tpl');
	$subject = "PROPOSAL WON: ". $proposal['proposal_id'];
	$rcpt[] = $revision['created_by']; //add the creator as the default recepient
   
   if ($status['cs_watch_list'] == 1 || (float) $status['max_amount'] >= (float) 100000) {
   	$rs = $p->GetReviewGroupMembersByGroupId(PROPOSAL_REVIEW_GROUP_CS);      
      while ($r = mysql_fetch_assoc($rs)) {
      	$rcpt[] = $r['login'];
      }
   }
   
   //Send Proposal Won Message
   $params = array('message_type_id' => MODULE_ID_PROPOSAL . MESSAGE_PROPOSAL_WON,
   					 'msg' => 
   						array('subject' => $subject, 'body' => $msg),
   					 'attr' => $proposal,
   					 'rcpt' => $rcpt);

//	@runkit_function_remove("HBRPC_GetPortlet");
   HBRPCCall("com", "QueueMessage", $params);
   
   //copy approval notes
   $rs_action = $p->GetRevisionActionList();
   
   while ($r_action = mysql_fetch_assoc($rs_action)) {
   	$p_new->SetRevisionAction($r_action['proposal_review_group_id'], $r_action['login'], $r_action['proposal_action_id'], 
   									"'". $r_action['action_date'] ."'", $r_action['action_comment'], 1);
   }
   
   $p_new->UpdateProposalStatus(PROPOSAL_STATUS_WON);

   header("Location: ?e=". $e->Encrypt("action=". $action ."&proposal_id=". $o['proposal_id']."&proposal_revision_id=". $proposal_revision_id));
   
   //find the selected option
   //check the selected countries on a option
   //copy the pricing inputs for that option 
   //change the number of countries of the number of countris has reduced
   
   
}

	/**
* CopyRevision()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Dec 12 15:16:19 PST 2005
*/
function CopyRevision($o)
{
   $p = new proposalDB();
   $p->SetRevisionId($o['proposal_revision_id']);
   $p->SetProposalId($o['proposal_id']);
   
   $pricing_manager = new pgen_PricingManager();

   $e = new Encryption();
   
   /* this is a critial function we dont want double clicks to cause any data curruption */
   set_time_limit(0);
   ignore_user_abort(true);
	
   /* prevent double clicks on revising check if the revision is already revised */
   $revision_status = $p->GetRevisionStatus();
   
   if ($revision_status == PROPOSAL_REVISION_STATUS_REVISED) {
   	/* PROCEED WITH CAUTION, NEED TO CHECK IF THIS IS A POSSIBLE DOUBLE CLICK */
   	
   	$ts = (int) $p->GetRevisionAttr('PPM_LAST_REVISION_CREATED_DATE');
   	$s_elapsed = (time() - $ts);
   	if ($s_elapsed < 60) {
   		//most probably a double request, 
   		
   		while ($p->GetRevisionAttr('PPM_LAST_REVISION_COPY_STATUS') == 'PENDING') {
   			sleep(1);
   		}
   		
   		$proposal_revision_id = $p->GetRevisionAttr('PPM_LAST_REVISION_ID');
   		header("Location: ?e=". $e->Encrypt("action=display_add_revision&proposal_revision_id=". $proposal_revision_id ."&proposal_id=". $o['proposal_id']));
   		return true;
   		
   	} else {
   		
   		$p->UpdateRevisionAttr('PPM_LAST_REVISION_CREATED_DATE', time());	
   		$p->UpdateRevisionAttr('PPM_LAST_REVISION_CREATED_BY', $_SESSION['admin_id']);	
   		$p->UpdateRevisionAttr('PPM_LAST_REVISION_COPY_STATUS', 'PENDING');		
   	}
   	
   } else {
   	$p->UpdateRevisionStatus(PROPOSAL_REVISION_STATUS_REVISED);
   	$p->SetRevisionAttr('PPM_LAST_REVISION_CREATED_DATE', time());	
   	$p->SetRevisionAttr('PPM_LAST_REVISION_COPY_STATUS', 'PENDING');		
   	$p->SetRevisionAttr('PPM_LAST_REVISION_CREATED_BY', $_SESSION['admin_id']);	
   }

   //create new revision
   $r = $p->GetRevisionDetail();
   
   $panel_details = 0;
   if ($p->GetRevisionAttr("PPM_DETAILED_PANEL_CALCULATION") != 0) {
      $panel_details = 1;
   }

   //we need to find out the revision count
   $revision_count = $p->GetRevisionCount();

   //basic detail
   $proposal = $p->GetBasicDetail();

   $revision = $revision_count + 1;

   $p->SetRevision($r['proposal_id'], $revision, $r['study_interview_type_id'], $r['study_setup_duration_id'] , $r['study_fieldwork_duration_id'], $r['study_data_processing_duration_id'], 
                      $r['proposal_option_type_id'], $r['proposal_type_id'], $r['number_of_countries'], $r['number_of_options'],
                      $_SESSION['admin_id'], $_SESSION['admin_id'], $r['pricing_type_id']);

   $proposal_revision_id = $p->lastID;
   
   if ($p->isRevisionAttrSet('PPM_LAST_REVISION_ID')) {
   	$p->UpdateRevisionAttr('PPM_LAST_REVISION_ID', $proposal_revision_id);	
   } else {
   	$p->SetRevisionAttr('PPM_LAST_REVISION_ID', $proposal_revision_id);		
   }

   $p->SetRevisionNumber($revision);
   
   $p->SetRevisionMaxPrice($r['max_amount']);
   $p->SetRevisionMinPrice($r['min_amount']);


   $rs = $p->GetRevisionSampleTypes();
   while ($r_st = mysql_fetch_assoc($rs)) {
   	$p->SetRevisionSampleType($proposal_revision_id, $r_st['sample_type_id']);
   }

   $rs = $p->GetRevisionServiceList();
   while ($r_sl = mysql_fetch_assoc($rs)) {
   	$p->SetRevisionService($proposal_revision_id, $r_sl['service_id']);
   }
   
   //copy comments
   $rs = $p->GetRevisionComments();
   while ($r_rc = mysql_fetch_assoc($rs)) {
   	$p->SetRevisionComment($proposal_revision_id, $r_rc['proposal_revision_comment_type_id'], $r_rc['comment']);
   }

   //create pricing
   $account_id = $proposal['account_id'];
   $license_level_id = $proposal['license_level_id']; //$partner->GetLicenseLevelId($account_id);

   $pricing_manager->CalculatePricing($account_id, $license_level_id, $p->GetProposalId(), $proposal_revision_id);


   //copy options
   $rs_options = $p->GetRevisionOptions();

   while ($r_option = mysql_fetch_assoc($rs_options)) {
   	$p->SetOption($proposal_revision_id, $r_option['country_code'], $r_option['option_number'], $r_option['sub_group_description'],
   	         $r_option['study_programming_type_id'], $r_option['translation'], $r_option['translation_language_code'],
   	         $r_option['overlay'], $r_option['overlay_language_code'], $r_option['study_datasource_id'], $r_option['incidence_rate'],
   	         $r_option['completes'], $r_option['questions_programmed'], $r_option['questions_per_interview'], $r_option['questions_per_screener'],
   	         $r_option['data_recording_hours'], $r_option['data_tab_hours'], $r_option['data_import_hours'], $r_option['data_export_hours'],
   	         $r_option['open_end_questions'], $r_option['incidence_of_open_end'], $r_option['avg_words_per_open_end'], $r_option['open_end_text_coding_hours'],
   	         $r_option['respondent_portal_type_id'], $r_option['respondent_portal_programming_hours'], $r_option['panel_import_hours'], $r_option['sort_order'], $r_option['client_portal_programming_hours']);
   	         
   	$proposal_revision_option_id = $p->lastID;
   	         
   	$rs_custom = $p->GetRevisionCustomPriceByOptionId($r_option['proposal_revision_option_id']);
   	
   	while ($r_custom = mysql_fetch_assoc($rs_custom)) {
   		$p->SetRevisionCustomPrice($proposal_revision_option_id, $r_custom['pricing_item_group_id'], $r_custom['amount']);
   	}
   	
   	$p->UpdateRevisionOptionCPI($proposal_revision_option_id, $r_option['panel_cost_per_interview']);

   }
   
   //Copy the detailed panel data
   for($k = 1; $k <= $proposal["number_of_options"]; $k++) {
      $rs_panel = $p->GetProposalRevisionPanelData($o["proposal_revision_id"], $k);
   	   
      while ($panel = mysql_fetch_assoc($rs_panel)) {
         $panel_id = $p->SetProposalRevisionPanel($proposal_revision_id, $k, $panel["country_id"], $panel["sample_type_id"], $panel["prime"], $panel["completes"], $panel["incidence"], $panel["question_length"], $panel["cost_per_complete"], $panel["total_cost"], $panel["adjustment"]);
   	      
         if (!$panel["prime"]) {
            $rs_attr = $p->GetProposalCountryPanelAttr($panel["proposal_revision_panel_id"], "SAMPLE_TYPE_DESCRIPTION");
            $attr = mysql_fetch_assoc($rs_attr);
   	         
            $p->SetProposalRevisionPanelDescription($panel_id, $attr["proposal_revision_panel_attr_value"]);
   	         
            $rs_extra = $p->GetProposalCountryPanelExtraData($panel["proposal_revision_panel_id"]);
            while ($extra = mysql_fetch_assoc($rs_extra)) {
               $p->SetProposalRevisionPanelExtra($panel_id, $extra["list_id"], $extra["sample_type_pricing_id"]);
            }
         }
      }
   }
   
   //copy approval notes
   $rs_action = $p->GetRevisionActionList();
   
   $p->SetRevisionId($proposal_revision_id);
   
   while ($r_action = mysql_fetch_assoc($rs_action)) {
   	$p->SetRevisionAction($r_action['proposal_review_group_id'], $r_action['login'], $r_action['proposal_action_id'], 
   									"'". $r_action['action_date'] ."'", $r_action['action_comment'], 1);
   }
   
   
   //we should copy custom pricing
   if ($p->isAttrSet('WORKING_REVISION')) {
      $p->UpdateAttr('WORKING_REVISION', $proposal_revision_id);
   } else {
      $p->SetAttr('WORKING_REVISION', $$proposal_revision_id);
   }
      
   $p->UpdateAttr('NEXT_ACTION', 'display_add_revision');

   // Add the Panel Details attribute
   if ($p->isRevisionAttrSet("PPM_DETAILED_PANEL_CALCULATION")) {
      $p->UpdateRevisionAttr("PPM_DETAILED_PANEL_CALCULATION", $panel_details);
   } else {
      $p->SetRevisionAttr("PPM_DETAILED_PANEL_CALCULATION", $panel_details);
   }
   
   /* reset to the old revision since we need to write to the old one */
   $p->SetRevisionId($o['proposal_revision_id']);
   $p->UpdateRevisionAttr('PPM_LAST_REVISION_COPY_STATUS', 'COMPLETED');	

   header("Location: ?e=". $e->Encrypt("action=display_add_revision&proposal_revision_id=". $proposal_revision_id ."&proposal_id=". $o['proposal_id']));

}

/**
* DisplayRevision()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Dec 12 09:26:03 PST 2005
*/
function DisplayRevision($o)
{
   global $smarty;
   $e = new Encryption();
   $p = new proposalDB();
   $c = new commonDB();
   $p->SetRevisionId($o['proposal_revision_id']);
   $p->SetProposalId($o['proposal_id']);
   
   $next_action = $p->GetAttr('NEXT_ACTION');
   $revision_id = $p->GetAttr('WORKING_REVISION');
      
   if ($next_action != '' && $revision_id == $o['proposal_revision_id']) {         
      $url = "action=". $next_action ."&proposal_id=". $o['proposal_id'] ."&proposal_revision_id=". $revision_id;
      header("Location: ?e=". $e->Encrypt($url));
      return true;
   }
   
   //timezone stuff
   $tz = GetTimeZone($o);
   $p->tz = $tz;

   $proposal = $p->GetBasicDetail();
   $revision = $p->GetRevisionDetail();
   
   $meta['display_currency_converter'] = 0;
   $meta['preffered_currency']         = '';
   $meta['currency_unicode']           = 36;
   
   $convert_proposal_now = 0;
   $use_exchange_rate = 0;
   $preffered_currency = $p->GetAttr('GLOBAL_PREFFERED_CURRENCY');
   
   if ($preffered_currency && $preffered_currency != 'USD')
   {
   	$use_exchange_rate = 1;	
   	$meta['display_currency_converter'] = 1;
   	$meta['preffered_currency']         = $preffered_currency;
   	$exchange_rate      = $p->GetAttr('PPM_EXCHANGE_RATE_USED');
   	
   	//GET EXCHANGE RATE /* CHECK IF EXCHANGE RATE MATCHES */
   	if (!$exchange_rate) 
   	{
   	    switch ($proposal['version']) {
   	    	case 1:
   	    	    $exchange_rate = $c->GetExchangeRate($preffered_currency);
   	    	break;
   	    	
   	    	default:
   	    		$exchange_rate = $c->GetCurrencyMultiplier($preffered_currency);
   	    	break;
   	    }
   	    
   		
   		$p->SetAttr('PPM_EXCHANGE_RATE_USED', $exchange_rate);
   	}
   	
   	if ($o['convert_currency'] == 1)
   	{
   		$convert_proposal_now = 1;		
   		$meta['currency_unicode']           = $c->GetUnicodeCurrencySymbol($preffered_currency);
   	}
   }
   
   
   $sample_types = PrepareSmartyArray($p->GetRevisionSampleTypes());
   $qf_ctr = PrepareSmartyArray($p->GetRevisionComment(PPM_COMMENT_QUALIFYING_CRITERIA));
   $qf_file = PrepareSmartyArray($p->GetRevisionFilesByType(PPM_FILE_QUALIFYING_CRITERIA));
   $approval = PrepareSmartyArray($p->GetRevisionActionList());

   $service_list = PrepareSmartyArray($p->GetDetailedRevisionServiceList());

   //SERVICE LIST
   if (count($service_list) > 1) {
	   foreach ($service_list as $key => $value) {
	   	$list['service_list'][$service_list[$key]['pricing_item_group_id']][] = $service_list[$key];
	   }
   

	   foreach ($list['service_list'] as $key => $value) {
	      $counter = 1;
	      $i = 1;
	
	   	foreach ($list['service_list'][$key] as $x_key => $x_value) {
	   	   $orglist[$key]['group_description'] = $list['service_list'][$key][$x_key]['pricing_item_group_description'];
	   	   $orglist[$key][$counter][$i] = $list['service_list'][$key][$x_key];
	   	   $i++;
	
	   	   if ($i == 4) {
	   	     $counter++;
	   	     $i = 1;
	   	   }
	
	   	}
	   }
   }
   ////SERVICE LIST END
	   
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
      	$option_summary[$r_option['option_number']]['completes'] += $r_option['completes'];
         $options[$r_option['option_number']][$r_option['sort_order']] = $r_option;
      }
   }

   $rs_services = $p->GetRevisionServiceList();
   while ($r_sl = mysql_fetch_assoc($rs_services)) {
      $services[$r_sl['service_id']] = 1;
   }
   //options END/////////////////////////////////////////////////

   $revision["panel_details"] = 0;
   
   if ($p->GetRevisionAttr("PPM_DETAILED_PANEL_CALCULATION") != 0) {
       $revision["panel_details"] = 1; 
   }
   
   // PANEL OPTIONS BEGIN
   if ($revision["panel_details"]) {
      $sample_type_rst = $p->GetRevisionSampleTypes();
      
      $sample_type_ids[0] = "All";
      
      while ($sample_type = mysql_fetch_assoc($sample_type_rst)) {
         $sample_type_ids[$sample_type["sample_type_id"]] = $sample_type["sample_type_description"];
      }
        

     $panel_options = array();
     for ($k = 1; $k <= $proposal['number_of_options']; $k ++) {
            
         $data_result = $p->GetProposalRevisionPanelData($o['proposal_revision_id'], $k);
            
         $c = 0;
         $old_country = -1;
         while ($row = mysql_fetch_assoc($data_result)) {
             if (! in_array($row["sample_type_id"], array_keys($sample_type_ids))) {
                continue;
             }
             if ($row["country_id"] != $old_country) {
                $old_country = $row["country_id"];
                $c ++;
                if ($c > $proposal['number_of_countries'])
                   break;
             }
               
             if (! $row['sample_type_id']) {
                $row['sample_type_description'] = 'All';
             }
                
             $row['extras'] = array();
                
             if (! $row['prime']) {
                $extras_result = $p->GetProposalCountryPanelExtraData($row['proposal_revision_panel_id']);
                    
                while ($row_extras = mysql_fetch_array($extras_result)) {
                   $row['extras'][$row_extras['list_id']] = $row_extras['item_description'] . " (" . $row_extras["premium"] . ")";
                }
                   
                $sample_des_result = $p->GetProposalCountryPanelAttr($row['proposal_revision_panel_id'], 'SAMPLE_TYPE_DESCRIPTION');
                $row['sample_type_description'] = mysql_result($sample_des_result, 0, 'proposal_revision_panel_attr_value');
             }
                
             $panel_options[$k][$c][$row["sample_type_id"]][] = $row;
         }
            
         for ($c = 1; $c <= $proposal['number_of_countries']; $c ++) {
             foreach ($sample_type_ids as $sample_type_id => $sample_type_description) {
                 if (! isset($panel_options[$k][$c][$sample_type_id])) {
                     $panel_options[$k][$c][$sample_type_id][] = array("proposal_revision_panel_id" => 0 , "completes" => 0 , "incidence" => 0 , "question_length" => 0 , "prime" => 1 , "sample_type_id" => $sample_type_id , "sample_type_description" => $sample_type_description , "country_id" => 0 , "country_code" => "" , "country_descriptoin" => "" , "extras" => array());
                 }
             }
         }
      }
   }
   // PANEL OPTIONS END
   
   //DISCOUNTS//
   $list['budget_setup'] = PrepareSmartyArray($p->GetRevisionPricingByGroup(PRICING_GROUP_SETUP));
   $list['budget_hosting'] = PrepareSmartyArray($p->GetRevisionPricingByGroup(PRICING_GROUP_HOSTING));
   $list['budget_dp'] = PrepareSmartyArray($p->GetRevisionPricingByGroup(PRICING_GROUP_DP));
   $list['volume_discount'] = PrepareSmartyArray($p->GetPricingVolumeDiscountByLicenseLevel($proposal['license_level_id']));
   $list['budget_panel'] = PrepareSmartyArray($p->GetRevisionPanelCost());
   $list['group_discount']['setup'] = PrepareSmartyArray($p->GetRevisionGroupDiscountByGroup(PRICING_GROUP_SETUP));
   $list['group_discount']['panel'] = PrepareSmartyArray($p->GetRevisionGroupDiscountByGroup(PRICING_GROUP_PANEL));
   $list['group_discount']['hosting'] = PrepareSmartyArray($p->GetRevisionGroupDiscountByGroup(PRICING_GROUP_HOSTING));
   $list['group_discount']['dp'] = PrepareSmartyArray($p->GetRevisionGroupDiscountByGroup(PRICING_GROUP_DP));
   //DISCOUNTS END///


   //PROPOSAL//
   $rs_proposal = $p->GetRevisionBudgetLineItems();

   $row = 1;
   $last_option_number = 0;

   if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] > 1) {

      while ($r = mysql_fetch_assoc($rs_proposal)) 
      {
      	/* if we are dealing with exchange rate then calculate $r['amount'] */
      	
      	if ($convert_proposal_now == 1 && $r['value_type'] != 'N')
      	{
      		$r['amount'] = $r['amount'] * $exchange_rate;
      		//$r['unicode'] = $currency_unicode_decimal;
      	}
      	
         $p_options[$r['sort_order']][$r['option_number']] = $r;
         $p_country[$r['option_number']] = $r['country_description'];
         $p_subgroup[$r['option_number']] = $options[$r['option_number']]['sub_group_description'];
         
         if ($r['value_type'] == 'T') {
            $summary[$r['option_number']]['total'] += $r['amount'];
         }
         
         $summary[$r['option_number']]['cpc'] = $summary[$r['option_number']]['total'] / $options[$r['option_number']]['completes'];
      }

   } else {

      while ($r = mysql_fetch_assoc($rs_proposal)) {

      	if ($convert_proposal_now == 1 && $r['value_type'] != 'N')
      	{
      		$r['amount'] = $r['amount'] * $exchange_rate;
      		//$r['unicode'] = $currency_unicode_decimal;
      	}
      	
         //the tricky part here is if we are under the assumption that country will align up together
         $p_options[$r['option_number']][$r['sort_order']][] = $r;
         $p_total[$r['option_number']][$r['sort_order']] += $r['amount'];
         $p_country[$r['option_number']][$r['c_sort_order']] = $r['country_description'];
         $p_subgroup[$r['option_number']][$r['c_sort_order']] = $options[$r['option_number']][$r['c_sort_order']]['sub_group_description'];
         
         if ($r['value_type'] == 'T') {
            $summary[$r['option_number']][$r['c_sort_order']]['total'] += $r['amount'];
            $option_summary[$r['option_number']]['total'] += $r['amount'];
         }
         $option_summary[$r['option_number']]['cpc'] = 0;
         if($option_summary[$r['option_number']]['completes'] != 0) {
         	$option_summary[$r['option_number']]['cpc'] = $option_summary[$r['option_number']]['total'] / $option_summary[$r['option_number']]['completes'];
         }
         $summary[$r['option_number']][$r['c_sort_order']]['cpc'] = 0;
         
         if ($options[$r['option_number']][$r['c_sort_order']]['completes'])
         	$summary[$r['option_number']][$r['c_sort_order']]['cpc'] = $summary[$r['option_number']][$r['c_sort_order']]['total'] / $options[$r['option_number']][$r['c_sort_order']]['completes'];
         
      }
   }

   //PROPOSAL END//

   //CUSTOM PRICING//
   if ($revision['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
      $rs = $p->GetRevisionCustomPricing();
      
      if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] > 1) {
         
         while ($r = mysql_fetch_assoc($rs)) {
         	
         	if ($convert_proposal_now == 1 && $r['value_type'] != 'N')
         	{
      			$r['amount'] = $r['amount'] * $exchange_rate;
         	}
         	
      	   $custom[$r['option_number']][$r['pricing_item_group_id']]['amount'] = $r['amount'];
      	   $custom[$r['option_number']][$r['pricing_item_group_id']]['prcp_id'] = $r['proposal_revision_custom_pricing_id'];
   
      	   $t_custom[$r['option_number']] += $r['amount'];
   
      	   //this is a way around of having to put another nested loop, either way its lost of CPU cycles 
      	   //but this method cost 2 less loops
            $cpc_custom[$r['option_number']] = $t_custom[$r['option_number']] / $options[$r['option_number']]['completes'];
         }         
         
      } else {
         while ($r = mysql_fetch_assoc($rs)) {
         	
         	if ($convert_proposal_now == 1 && $r['value_type'] != 'N')
      			$r['amount'] = $r['amount'] * $exchange_rate;
         	
      	   $custom[$r['option_number']][$r['sort_order']][$r['pricing_item_group_id']]['amount'] = $r['amount'];
      	   $custom[$r['option_number']][$r['sort_order']][$r['pricing_item_group_id']]['prcp_id'] = $r['proposal_revision_custom_pricing_id'];
   
      	   $t_custom[$r['option_number']][$r['sort_order']] += $r['amount'];
   
      	   //this is a way around of having to put another nested loop, either way its lost of CPU cycles but this method cost 2 less loops
            $cpc_custom[$r['option_number']][$r['sort_order']] = $t_custom[$r['option_number']][$r['sort_order']] / $options[$r['option_number']][$r['sort_order']]['completes'];
         }         
      }

      $pricing_group = PrepareSmartyArray($p->GetPricingItemGroups());

   }
   //CUSTOM PRICING END//
   
   //meta settings
   $meta['show_approval'] = 0;
   $meta['proposal_review_group_id'] = 0;
   
   //if status is waiting for approval check for the pending approvals //
   if ($revision['proposal_revision_status_id'] == PROPOSAL_REVISION_STATUS_WAITING_APPROVAL || 
         $revision['proposal_revision_status_id'] == PROPOSAL_REVISION_STATUS_PARTIALLY_APPROVED) {
      if ($p->isUserOnRevisionApprovalList($_SESSION['admin_id'])) {

         $meta['show_approval'] = 1;
         
         mysql_data_seek($p->rs, 0);
         
         $r = mysql_fetch_assoc($p->rs);
         
         $meta['proposal_review_group_id'] = $r['proposal_review_group_id'];
      }
      
      $list['proposal_action'] = CreateSmartyArray($p->GetProposalActionList(), 'proposal_action_id', 'proposal_action_description');
   }
   
   //see if this requires approval
   if ($revision['proposal_revision_status_id'] == 0 || 
   		$revision['proposal_revision_status_id'] == PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL ||
   		$revision['proposal_revision_status_id'] == PROPOSAL_REVISION_STATUS_WAITING_APPROVAL) {
      
   	$workflow = new pgen_WorkflowManager();
   	$status = $workflow->GetReviewStatusCode($o['proposal_id'], $o['proposal_revision_id']);
   	$approval_required = 0;
      
//      if ($status['cs_watch_list'] == 1) {
//         $meta['message'] = 'This Proposal Requires Client Services Review and Approval';
//         $approval_required = 1;
//      }
      
      if ((float)$status['max_amount'] > (float) 100000.00) {
         $meta['message'] = 'This Proposal Requires Client Services Review and Approval';
         $approval_required = 1;
      }
      
//      if ($status['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
//         $meta['message'] = 'This is a Non Standard Proposal, Please Send For Approval';
//         $approval_required = 1;
//      }
      
      if ($approval_required == 1) {
      	if ($revision['proposal_revision_status_id'] != PROPOSAL_REVISION_STATUS_WAITING_APPROVAL) {
      		$p->UpdateRevisionStatus(PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL);
      	} else {
      		
      	}
      } else {
      	$meta['message'] = 'Proposal is Ready to be Sent to Client';
      	$p->UpdateRevisionStatus(PROPOSAL_REVISION_STATUS_READY_TO_SEND);
      }
      
      $revision = $p->GetRevisionDetail();
      
   }
   
   $revision["panel_details"] = 0;
   
   if ($p->GetRevisionAttr("PPM_DETAILED_PANEL_CALCULATION") != 0) {
       $revision["panel_details"] = 1; 
   }
   
   //comments
   $rs = $p->GetRevisionComments();
   
   while ($r = mysql_fetch_assoc($rs)) {
   	switch ($r['proposal_revision_comment_type_id']) {
   		case PPM_COMMENT_FINAL_DELIVERABLES:
   			$comment['final_deliverables'] = $r['comment'];
   			break;
   	   case PPM_COMMENT_QUALIFYING_CRITERIA:
   			$comment['qualifying_criteria'] = $r['comment'];
   			break;
   	   case PPM_COMMENT_GENERAL:
   	      $comment['general_comment'] = $r['comment'];
   	      break;
   		default:
   			break;
   	}
   }
   
   //handle disposition buttons
   $meta['display_button_revise']      = 1;
   $meta['display_button_send_client'] = 1;
   $meta['display_button_download']            = 1;
   $meta['display_button_send_approval']      = 1;
   $meta['display_button_won']                = 1;
   $meta['display_button_manual_sent']                = 1;
   $meta['display_txt_sub_group'] = 0;
   $display['proposal_status_review'] = 0;
   
   if ($revision['proposal_option_type_id'] == PROPOSAL_OPTION_TYPE_SINGLE_SUB) {
   	$meta['display_txt_sub_group'] = 1;	
   }
   
   //when requires approval
   switch ($revision['proposal_revision_status_id']) {
   	
   	case PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL:
   		$meta['display_button_send_client'] = 0;
   		$meta['display_button_download']    = 0;
   		$meta['display_button_won']         = 0;
   		$meta['display_button_manual_sent']  = 0;
   		break;
   		
   	case PROPOSAL_REVISION_STATUS_WAITING_APPROVAL:
   	case PROPOSAL_REVISION_STATUS_PARTIALLY_APPROVED:
   		$meta['display_button_send_client'] = 0;
   		$meta['display_button_download']    = 0;
   		$meta['display_button_won']         = 0;
   		$meta['display_button_manual_sent'] = 0;
   		break;
   		
   	case PROPOSAL_REVISION_STATUS_SENT_TO_CLIENT:
   		$meta['display_button_send_approval'] = 0;
   		$meta['display_button_send_client']   = 0;
   		$meta['display_button_manual_sent']   = 0;
   		break;
   		
   	case PROPOSAL_REVISION_STATUS_CLIENT_ACCEPTED:
   		$meta['display_button_send_client'] = 0;
   		$meta['display_button_send_approval']      = 0;
   		$meta['display_button_won']                = 0;
   		$meta['display_button_manual_sent']   = 0;
   		$meta['display_button_revise']      = 0;
   		break;
   		
   	case PROPOSAL_REVISION_STATUS_REVISED:
   		$meta['display_button_send_client'] = 0;
   		$meta['display_button_send_approval']      = 0;
   		$meta['display_button_manual_sent']  = 0;
   		break;
   		
   	case PROPOSAL_REVISION_STATUS_READY_TO_SEND:
   		$meta['display_button_send_approval']      = 0;
   		$meta['display_button_won']                = 0;	
   		break;
   		
   	case PROPOSAL_REVISION_STATUS_MANUAL_SENT:
   		$meta['display_button_send_approval'] = 0;
   		$meta['display_button_send_client']   = 0;
   		$meta['display_button_manual_sent']   = 0;
   		break;
   		
   	case PROPOSAL_REVISION_STATUS_APPROVED:
   		$meta['display_button_send_approval'] = 0;	
   		break;
   		
   	case PROPOSAL_REVISION_STATUS_REJECTED:
   		$meta['display_button_send_client'] = 0;
   		$meta['display_button_send_approval']      = 0;
   		$meta['display_button_won']                = 0;	
   		$meta['display_button_manual_sent']  = 0;
   		$meta['display_button_download'] = 0;

   	default:
   		break;
   }
   
   if (ISDEV) {
   	$meta['display_button_send_client'] = 1;
   	$meta['display_button_send_approval']      = 1;
   }   
   
   
   //handle messages
   if ($_SESSION['pgen_message'] != '') {
      $meta['message'] = $_SESSION['pgen_message'];
      unset($_SESSION['pgen_message']);
   }
   
	if ($proposal['proposal_status_id'] == PROPOSAL_STATUS_ACCOUNT_REVIEW) {
		$display['proposal_status_review'] 	= 1;
		$meta['display_button_won']			= 0;
	}
   
   if ($use_exchange_rate)
   {
   	$meta['message'] .= "<br> This Proposal Will be Sent in ". $preffered_currency .", Todays Exchange Rate is ". $exchange_rate;
   }
   
   $account = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($proposal['account_id']);
   $revision_value = $p->GetRevisionMaxPrice();
   
   /* @var $account Hb_App_Account_Account */
   $account_term = $account->GetAccountTerm();
   
	if ($account->GetAccountType() == ACCOUNT_TYPE_CUSTOMER && $account_term->GetCreditLimit() > 0 && $account_term->GetCreditLimit() < $revision_value['max_amount']) {
		$meta['message'] .= "<br> You are about to send out a proposal on an account which has a lower credit limit than the proposal amount";
	}

   $min_engagement_fee = Hb_App_ObjectHelper::GetMapper("Hb_App_Proposal_ProposalSetting")->FindBySettingName("MIN_PROPOSAL_ENGAGEMENT_VALUE")->GetSettingValue();
   if ($revision["min_amount"] < $min_engagement_fee) {
      try {
         $bound_low_proposal = $account->GetAttribute("PGEN_BOUND_LOW_PROPOSAL")->GetAttributeValue();
      }catch (Hb_Data_ObjectNotInCollectionException $e) {
         $bound_low_proposal = 0;
      }
   
      if (!$bound_low_proposal) {
         $meta['message'] .= "<br/>This proposal is BELOW the minimum engagement fee of $".number_format($min_engagement_fee, 2)." required by company policy, and the account is NOT setup as contractually obligated to conduct low value projects.";
         $meta['popup_low_value_confirmation'] = 1;
      }      
   }
   
   $template = 'app/pgen/vr_revision.tpl';
   
   if ($_SESSION['user_type_id'] == USER_TYPE_EXTERNAL) {
   	$template = 'app/pgen/ext/vr_revision.tpl';	
   }
   
   /* auction code */
   
   $db = new db_ppm();
   
   $list['auctions'] = $db->GetRevisionAuctionList($o['proposal_revision_id']);
   
   $auctions = array();
   
   foreach ($list['auctions'] as $auction) {
   	$auctions[$auction['proposal_revision_option_id']][$auction['proposal_auction_id']] = $auction;
   }
   
   /* */

   $smarty->assign('revision', $revision); //TODO name revision COMPLETED
   $smarty->assign('proposal', $proposal);
   $smarty->assign('sample_types', $sample_types);
   $smarty->assign('qf_ctr', $qf_ctr);
   $smarty->assign('qf_file', $qf_file);
   $smarty->assign('orglist', $orglist);
   $smarty->assign('options', $options);
   $smarty->assign('panel_options', $panel_options);
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
   $smarty->assign('comment', $comment);
   $smarty->assign('p_subgroup', $p_subgroup);
   $smarty->assign('option_summary', $option_summary);
   $smarty->assign('auctions', $auctions);
   $smarty->assign('display', $display);
   
   DisplayHeader("Proposal Manager", "pgen");
   $smarty->display($template);
   DisplayFooter();
}

/**
* SaveRevision()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Dec 06 11:11:59 PST 2005
*/
function SaveRevision($o)
{
   $c = new commonDB();
   $p = new proposalDB();
   //$e = new Encryption();
   $u = new userDB();
   //$partner = new partnerDB(0);
	
   
   $proposal_revision_id = 0;

   if ($o['proposal_revision_id'] != '') {
      $proposal_revision_id = $o['proposal_revision_id'];
   }

   $id = $o['quote'];
   $p->SetProposalId($o['quote']);
   $revision_count = $p->GetRevisionCount();
   $revision = $revision_count + 1;
   
   if ($proposal_revision_id == 0) {
      $p->SetRevision($id, $revision, $o['study_interview_type_id'], $o['study_setup_duration_id'], $o['study_fieldwork_duration_id'],
                     $o['study_data_processing_duration_id'], $o['proposal_option_type_id'], $o['proposal_type_id'], $o['number_of_countries'],
                     $o['number_of_options'], $_SESSION['admin_id'], $_SESSION['user_id'], $o['pricing_type_id']);
      //get the last AUTO_INCREMENT
      $revision_id = $p->lastID;
   } else {
      $p->UpdateRevision($proposal_revision_id, $o['study_interview_type_id'], $o['study_setup_duration_id'], $o['study_fieldwork_duration_id'],
                     $o['study_data_processing_duration_id'], $o['proposal_option_type_id'], $o['proposal_type_id'], $o['number_of_countries'],
                     $o['number_of_options'], $o['pricing_type_id']);
      $revision_id = $proposal_revision_id;
   }
   
   //
   $p->SetRevisionId($revision_id);
   
   $r_revision = $p->GetRevisionDetail();
   
   if ($r_revision['revision'] > 1) {
   	
   	$rs_option = $p->GetRevisionOptions();
   	
   	while ($r_option = mysql_fetch_assoc($rs_option)) {
   		if ($r_option['option_number'] > $r_revision['number_of_options']) {
   			$p->DeleteRevisionOptionById($r_option['proposal_revision_option_id']);
   		} elseif ($r_option['sort_order'] > $r_revision['number_of_countries']) {
   			$p->DeleteRevisionOptionById($r_option['proposal_revision_option_id']);
   		}
   	}
   	
   	//see if the current number of countries is lower than what we suppose to have
   }

   if ($proposal_revision_id == 0) {
      //set the comment $questionsUALIFYING_CRITERIA, save the FILE PPM_COMMENT_GENERAL
      $p->SetRevisionComment($revision_id, PPM_COMMENT_QUALIFYING_CRITERIA, $o['qualifying_criteria']);
      $p->SetRevisionComment($revision_id, PPM_COMMENT_FINAL_DELIVERABLES, $o['final_deliverable']);
      $p->SetRevisionComment($revision_id, PPM_COMMENT_GENERAL, $o['general_comment']);
   } else {
      $p->UpdateRevisionComment($revision_id, PPM_COMMENT_QUALIFYING_CRITERIA, $o['qualifying_criteria']);
      $p->UpdateRevisionComment($revision_id, PPM_COMMENT_FINAL_DELIVERABLES, $o['final_deliverable']);
      $p->UpdateRevisionComment($revision_id, PPM_COMMENT_GENERAL, $o['general_comment']);
   }

   //print_r($_FILES);
   //still need to figure out what to do with files
   
   if ($_FILES['qualifying_criteria_file']['size'] > 0) {
      $f = GetFileData('qualifying_criteria_file');
      $p->SetRevisionFile($revision_id, PPM_FILE_QUALIFYING_CRITERIA, $f['type_id'], $f['name'], $f['data'], $f['size']);
   }

   // i am going to clear all the sample types and then re-do them for updates, saves
   //time and cpu trying to check each to see if its set and then unset the once that are not set
   if ($proposal_revision_id != 0) {
      $p->DeleteRevisionSampleType($revision_id);
   }

   //set sample type
   for ($i_st = 0; $i_st < count($o['sample_type_id']); $i_st++) {
      $p->SetRevisionSampleType($revision_id, $o['sample_type_id'][$i_st]);
   }

   //set the service list
   if ($proposal_revision_id != 0) {
      $p->DeleteRevisionServiceList($revision_id);
   }
   //get the list of services available
   $rs = $p->GetServiceList();
   //loop through the service to see if they are set, if set then insert them..
   while ($r = mysql_fetch_assoc($rs)) {
   	if (isset($o['S_'.$r['service_id']])) {
   	   $p->SetRevisionService($revision_id, $r['service_id']);
   	}
   }

   //store the license_level_id, and regime also 
   //get the partner license level
   //$o['license_level_id'] = $partner->GetLicenseLevelId($o['account_id']);
   $proposal = $p->GetBasicDetail();
   
   $pricing_manager = new pgen_PricingManager();

   $pricing_manager->CalculatePricing($proposal['account_id'], $proposal['license_level_id'], $id, $revision_id, $proposal_revision_id);
   
   if ($p->isAttrSet('WORKING_REVISION')) {
      $p->UpdateAttr('WORKING_REVISION', $revision_id);
   } else {
      $p->SetAttr('WORKING_REVISION', $revision_id);
   }
   
   $action = 'display_options';
   if ($p->isRevisionAttrSet("PPM_DETAILED_PANEL_CALCULATION")) { 
      $p->UpdateRevisionAttr("PPM_DETAILED_PANEL_CALCULATION", 0);
   } else {
      $p->SetRevisionAttr("PPM_DETAILED_PANEL_CALCULATION", 0);
   }
   
   //if we are doing panel pricing and detailed panel price calculation is turned on then
   //lets display the page to capture detail panel parameters.
   if ($proposal['version'] != 1 && isset($o['S_' . SERVICE_PANEL]) && isset($o['panel_detail'])) 
   {
       $action = 'display_panel_details';
       $p->UpdateRevisionAttr("PPM_DETAILED_PANEL_CALCULATION", 1);
   }
   
   //see if we want to review pricing or go straight to provide options
   if (isset($o['review_discount'])) {      
      $action = 'display_review_discount';
   } 
   
   $p->UpdateAttr('NEXT_ACTION', $action);
   
   return $revision_id;
   //header("Location: ?e=". $e->Encrypt("action=". $action ."&proposal_id=". $id ."&proposal_revision_id=". $revision_id));

   //now take me to the detail screen
   //header("Location: ?e=".$e->Encrypt("action=display_detail&id=".$id));


   //print_r($o);
}

/**
* AddRevision()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sat Jul 23 18:53:20 PDT 2005
*/
function DisplayAddRevision($o)
{
   global $smarty;
   $p = new proposalDB();
   $s = new studyDB();
   $p->SetProposalId($o['proposal_id']);
   
   $proposal = $p->GetBasicDetail();
   
   if (isset($o['proposal_revision_id']) && $o['proposal_revision_id'] != '') {
      $proposal['proposal_revision_id'] = $o['proposal_revision_id'];
   }

   //we should pass revision id to this page when ever we are calling this page 
   //to load data which doesnt belong to the most recent revision.
   //get basic detail only load data from the latest revision
   //print_r($r);

   $list['p_status']   = CreateSmartyArray($p->GetProposalStatusList(), 'proposal_status_id', 'proposal_status_description');
   $list['proposal_type'] = CreateSmartyArray($p->GetProposalTypeList(), 'proposal_type_id', 'proposal_type_description');

   $list['data_collection_method'] = CreateSmartyArray($s->GetStudyInterviewTypes(), 'study_interview_type_id', 'study_interview_type_description');
   $list['fieldwork_duration'] = CreateSmartyArray($s->GetStudyFieldWorkDurations(), 'study_fieldwork_duration_id', 'study_fieldwork_duration_description');
   $list['proposal_option_type'] = CreateSmartyArray($p->GetProposalOptionTypes(), 'proposal_option_type_id', 'proposal_option_type_description');
   $list['sample_type'] = CreateSmartyArray($p->GetSampleTypes(), 'sample_type_id', 'sample_type_description');
   $list['pricing_type'] = CreateSmartyArray($p->GetPricingTypes(), 'pricing_type_id', 'pricing_type_description');

   $p->UpdateAttr('NEXT_ACTION', "display_add_revision");
   
   if ($proposal['proposal_revision_id'] != 0) {
      
      $p->SetRevisionId($proposal['proposal_revision_id']);
      $revision = $p->GetRevisionDetail();
      
      $list['file_qc'] = PrepareSmartyArray($p->GetRevisionFilesByType(PPM_FILE_QUALIFYING_CRITERIA));

      $rs = $p->GetRevisionSampleTypeIds($proposal['proposal_revision_id']);
      
      while ($r = mysql_fetch_assoc($rs)) {
         $list['sample_type_selected'][] = $r['sample_type_id'];
      }
 
      $service_list = PrepareSmartyArray($p->GetServiceListByRevision());
      
      $r = mysql_fetch_assoc($p->GetRevisionComment(PPM_COMMENT_QUALIFYING_CRITERIA));
      
      $list['qualifying_criteria'] = $r['comment'];
      
      $r = mysql_fetch_assoc($p->GetRevisionComment(PPM_COMMENT_FINAL_DELIVERABLES));
      
      $list['final_deliverable'] = $r['comment'];
      
      $r = mysql_fetch_assoc($p->GetRevisionComment(PPM_COMMENT_GENERAL));
      
      $list['general_comment'] = $r['comment'];
   
      $revision["panel_detail"] = $p->GetRevisionAttr("PPM_DETAILED_PANEL_CALCULATION");
   } else {

      $service_list = PrepareSmartyArray($p->GetServiceList());

      $proposal['study_setup_duration_id']           = 7;
      $proposal['study_fieldwork_duration_id']       = 2;
      $proposal['study_data_processing_duration_id'] = 6;
   
   }

   foreach ($service_list as $key => $value) {
   	$list['service_list'][$service_list[$key]['pricing_item_group_id']][] = $service_list[$key];
   }


   foreach ($list['service_list'] as $key => $value) {
      $counter = 1;
      $i = 1;

   	foreach ($list['service_list'][$key] as $x_key => $x_value) {
   	   $orglist[$key]['group_description'] = $list['service_list'][$key][$x_key]['pricing_item_group_description'];
   	   $orglist[$key][$counter][$i] = $list['service_list'][$key][$x_key];
   	   $i++;

   	   if ($i == 4) {
   	     $counter++;
   	     $i = 1;
   	   }

   	}
   }





   DisplayHeader("Proposal Manager", "pgen");

   $smarty->assign('lang', $o['lbl']);
   $smarty->assign('proposal', $proposal);
   $smarty->assign('revision', $revision);
   $smarty->assign('list', $list);
   $smarty->assign('orglist', $orglist);
   $smarty->display('app/pgen/vw_add_revision.tpl');

   DisplayFooter();
}

	
}
?>