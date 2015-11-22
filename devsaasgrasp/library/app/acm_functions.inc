<?php
require_once 'db/accountDB.class.php';

//if (!defined(DEFAULT_PAGE_SIZE)) define('DEFAULT_PAGE_SIZE', 50);
/**
* HBRPC_ACM_SetProductSecurityToken()
*
* @param -
* @param - 
* @author - harsha
* @since  - Mon May 21 14:12:19 LKT 2007
*/
function HBRPC_ACM_SetProductSecurityToken($o)
{
	if(isset($o['account_id']) && isset($o['product_id']) && isset($o['auth_token'])) {		
		try {
			$product_manager = new acm_ProductManager();
			$product_manager->SetProductSecurityToken($o['account_id'], $o['product_id'], $o['auth_token']);
		
		} catch (Exception $e) {
			return array('error_code' => 1, 'error_message' => $e->getMessage());
		}		
		
		return array('error_code' => 0, 'error_message' => 'Sucess'); 
	}
}

/**
* HBRPC_ACM_CreateAccountUserFromContactId()
*
* @param
* @param 
* @return
* @since  - 09:29:07
*/
function HBRPC_ACM_CreateAccountUserFromContactId($o)
{
	$a = new accountDB();
	
	$r = $a->GetAccountByContactId($o['contact_id']);
	
	$r_contact = $a->GetAccountContactDetails($o['contact_id']);
	
	$account_type_id = ACCOUNT_TYPE_CUSTOMER;
	
	if (isset($o['account_type_id'])) {
		$account_type_id = $o['account_type_id'];
	}
	
	try {
		$login = SetupAccountPortalUser($r['account_contact_id'], $r['account_id'], 
				$o['email'], 'NULLDEFINED1234', $r_contact['contact_first_name'], 
				$r_contact['contact_last_name'], 1, $o['contact_id'], $account_type_id);	
	} catch (Exception $e) {
		return array('error_code' => 1, 'error_message' => $e->getMessage());
	}
	
				
	return array('error_code' => 0, 'data' => array('login' => $login));
}

/**
* HBRPC_ACM_CreateAccountUserFromEmail()
*
* @param
* @param 
* @return
* @since  - 11:33:03
*/
function HBRPC_ACM_CreateAccountUserFromEmail($o)
{
	try {
		$data = CreateAccountUserFromEmail($o['name'], $o['email_address']);
	} catch (Exception $e) {
		return array(
			'error_code' => $e->getCode(),
			'error_message' => $e->getMessage() . " == ". $e->getFile() ." == ". $e->getLine() . " == ". $e->getTraceAsString()
		);
	}
	
	return array('error_code' => 0, 'error_description' => 'Sucess', 'data' => $data);
	
}

/**
* CreateAccountUserFromEmail()
*
* @param
* @param 
* @return
* @since  - 12:31:41
*/
function CreateAccountUserFromEmail($name, $email)
{
	$email = new Hb_Util_Email_Address($email);
	
	try {
		
		$contact = Hb_App_Contact::getInstanceByEmail($email);
		
		
	} catch (Hb_Maint_Account_Exception $e) {
		
		/* @var $account Hb_Maint_Account */
		$account = Hb_Maint_Account_Query::GetAccountByEmailDomain($email);
		
		$name = split(" ", $name, 2);
		$contact = Hb_App_Contact::CreateContact($name[0], $name[1], $email);
		
		$account->AddContact($contact);
		
	} 
	
	$account_contact = Hb_Maint_Account_Contact::getInstance($contact);
	
	$login = SetupAccountPortalUser($account_contact->id, $account_contact->account->id, 
				$contact->email, 'NULLDEFINED1234', $contact->first_name, $contact->last_name, 1, $contact->id);
   
	return $data['login'] = $login;
}

//TODO add code
   /* list of accounts (id, name) */
   /* account defailt with attributes */
/**
* name()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 25 13:11:15 PST 2006
*/
function GetAccountList($account_type_id = ACCOUNT_TYPE_CUSTOMER, $account_sub_type_id = 0)
{
   $a = new accountDB();
   $list = ($account_sub_type_id != 0) ? PrepareSmartyArray($a->GetAccountListByType($account_type_id, $account_sub_type_id)) : PrepareSmartyArray($a->GetAccountListByType($account_type_id));
   return $list;
}

/**
* GetAccountDetail()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 25 13:36:15 PST 2006
*/
function GetAccountDetail($account_id, $product_id = PRODUCT_NETMR)
{
   $a = new accountDB();
   $a->SetAccountId($account_id);
   $account = $a->GetAccountDetail();
   
   if (!$account) {
      return array("error_code" => 1, "error_message" => "Account Not Found");
   }
   
   $account['error_code'] = 0;
   $account['error_message'] = "Sucess";
   
   $account['attr'] = PrepareSmartyArray($a->GetAccountAttr());
   $account['user'] = PrepareSmartyArray($a->GetAccountUser($product_id));
   $account['product'] = $a->GetAccountProduct($product_id);
   $account['type'] = mysql_fetch_assoc($a->GetAccountType());
   return $account;
}

/**
* GetAccountContacts()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 03 09:33:51 PST 2006
*/
function GetAccountContacts($account_id)
{
   $a = new accountDB();
   $a->SetAccountId($account_id);
   $data['error_code'] = 0;
   $data['data'] = PrepareSmartyArray($a->GetContacts());
   return $data;
}

/**
* HBRPC_ACM_GetAccountContacts()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 24 10:32:43 PST 2006
*/
function HBRPC_ACM_GetAccountContacts($o)
{
	return GetAccountContacts($o['account_id']);
}

/**
* GetAccountContactsDetail()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 03 09:38:20 PST 2006
*/
function GetAccountContactsDetails($account_id, $contact_type_id, $address_type_id = ADDRESS_TYPE_WORK)
{
   $a = new accountDB();
   $a->SetAccountId($account_id);
   
   if (!$a->isAccountOnFile()) {
      return array("error_code" => 1, "error_message" => "Account Not Found");
   }
   
   //handle multiple values
   if (is_array($contact_type_id)) {
   	$contact_type_id = implode(",", $contact_type_id);
   } 
   
   $rs = $a->GetAccountContactsDetails($contact_type_id, $address_type_id);
   
   while ($r = mysql_fetch_assoc($rs)) {
   	$r['phone'] = PrepareSmartyArray($a->GetContactPhoneDetails($r['contact_id']));
   	$data[$r['contact_id']] = $r;
   }
   
   $data['error_code'] = 0;
   $data['error_message'] = "Sucess";
   
   return $data;
}

/**
* HBRPC_ACM_GetAccountList()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 25 13:35:26 PST 2006
*/
function HBRPC_ACM_GetAccountList($o)
{
	if (isset($o['account_sub_type_id']) && is_numeric($o['account_sub_type_id']))
   	return GetAccountList($o['account_type_id'], $o['account_sub_type_id']);
   else 
   	return GetAccountList($o['account_type_id']);
}

/**
* HBRPC_ACM_GetAccountAttrValues()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 31 10:29:14 PST 2006
*/
function HBRPC_ACM_GetAccountAttrValues($o)
{
	if (isset($o['account_id']) && is_numeric($o['account_id'])) {
      return GetAccountAttrValues($o['account_id']);   
   }
   
   return array('error_code' => 1, 'error_message' => 'Account Id Not Defined');
}

/**
* GetAccountAttrValues()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 31 10:29:51 PST 2006
*/
function GetAccountAttrValues($account_id)
{
	$a = new accountDB();
	$a->SetAccountId($account_id);
	
	$data['attr'] = CreateSmartyArray($a->GetAccountAttr(), 'account_attr_name', 'account_attr_value');
	$data['error_code'] = 0;
	
	return $data;	
}

/**
* HBRPC_ACM_GetAccountDetail()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 25 13:40:41 PST 2006
*/
function HBRPC_ACM_GetAccountDetail($o)
{
   if (isset($o['account_id']) && is_numeric($o['account_id'])) {
      return GetAccountDetail($o['account_id']);   
   }
   
   return array('error_code' => 1, 'error_message' => 'Account Id Not Defined');
   
}

/**
* HBRPC_ACM_GetAccountDetailByProduct()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Mon Jan 30 16:39:14 PST 2006
*/
function HBRPC_ACM_GetAccountDetailByProduct($o)
{
	//this method will call from the Account manager component
   return GetAccountDetail($o['account_id'], $o['product_id']);
}

/**
* HBRPC_ACM_GetAccountIdByAccountProductId()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu May 18 09:11:16 PDT 2006
*/
function HBRPC_ACM_GetAccountIdByAccountProductId($o)
{
	if(isset($o['account_identifier'])) {         
		$product_manager	= new acm_ProductManager();
		                   
		try {           
			$data = $product_manager->GetAccountIdByAccountProductId($o['account_identifier']);       
		} catch (Exception $e) {            
			return array( 'error_code' => $e->getCode(), 'error_message' =>                 
				$e->getMessage() . " == ". $e->getFile() ." == ". $e->getLine() . " == ". $e->getTraceAsString() );         
		}               
		
		return $data;      	
	}
	
}

/**
* HBRPC_ACM_GetAccountContactsDetails()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 03 09:50:39 PST 2006
*/
function HBRPC_ACM_GetAccountContactsDetails($o)
{
   return GetAccountContactsDetails($o['account_id'], $o['contact_type_id'], $o['address_type_id']);
}

/**
* HBRPC_ACM_GetAttrValues()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 03 14:05:10 PST 2006
*/
function HBRPC_ACM_GetAttrValues($o)
{
	$a = new accountDB();
	$c = new commonDB();
	
   switch ($o['attr_name']) {
   	case 'partner_id':
   	case 'account_id':
   		$data['data'] = GetAccountList();
   		$data['meta'] = array('index' => 'account_id', 'value' => 'account_name');
   		break;
   
   	case 'account_type_id':
         $data['data'] = PrepareSmartyArray($a->GetAccountTypeList());
         $data['meta'] = array('index' => 'account_type_id', 'value' => 'account_type_description');
         break;

   	case 'account_sub_type_id':
         $data['data'] = PrepareSmartyArray($a->GetAccountSubTypeList());
         $data['meta'] = array('index' => 'account_sub_type_id', 'value' => 'account_sub_type_description');
         break;
   		
   	case 'product_id':
   		$data['data'] = PrepareSmartyArray($c->GetProductList());
   		$data['meta'] = array("index" => "product_id", "value" => "product_description");
   		break;
   		
   	case 'license_level_id':
   	case 'old_license_level_id':
   		$data['data'] = PrepareSmartyArray($c->GetLicenseLevels());
   		$data['meta'] = array("index" => "license_level_id", "value" => "license_level_description");
   		break;
   		
   	case 'pricing_regime_id':
   	case 'old_pricing_regime_id':
   		$data['data'] = PrepareSmartyArray($c->GetPricingRegimes());
   		$data['meta'] = array("index" => "pricing_regime_id", "value" => "pricing_regime_description");
   		break;
   		
   	case 'account_product_attr_name':
   		$data['data'] = PrepareSmartyArray($a->GetAccountProductAttrDef());
   		$data['meta'] = array("index" => "account_product_attr_name", "value" => "account_product_attr_description");
   		break;
   		
   	case 'license_expire_before':
   		$data['data'] = array(
            array("days"=>1, "description"=>"One day"), 
            array("days"=>3, "description"=>"Three days"), 
            array("days"=>7, "description"=>"One week"), 
            array("days"=>14, "description"=>"Two weeks"), 
            array("days"=>30, "description"=>"Thirty days"), 
            array("days"=>60, "description"=>"Sixty days"), 
            array("days"=>90, "description"=>"Ninety days"));
         $data['meta'] = array("index" => "days", "value" => "description");
         break;
         
      default:
   		break;
   }
   
   return $data;
}

/**
* DisplayAccountList()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Tue Jan 31 15:00:23 PST 2006
*/
function DisplayAccountList($o)
{
	if (isExternalUser()) {
		header("Location: ?action=display_account_detail&account_id=". GetExternalPrimaryAccount());
		return true;
	}
	
	global $smarty, $encryption;
   $a = new accountDB();
   $c = new commonDB();
	
	if (isset($o['page_size']) && is_numeric($o['page_size'])) {
   	$page_size = $o['page_size'];
   } else {
   	$page_size = DEFAULT_PAGE_SIZE;
   }
	
	$start = 0;

   $sort = ' a.account_name ';
   
   $redirect = 0;
   
   $meta['filter_on'] = 0;

   if ($o['filter_off'] == 1) {
      unset($_SESSION['acm_main_filter']);
      unset($_SESSION['acm_main_filter_on']);
      $redirect = 1;
   }
   
   //search
   if ($o['action'] == 'do_search') {
   	$filter = "";
      if (preg_match("/^location_id_(\d+)$/", $o['ae_login'], $matches)) {
          $location_id = $matches[1];
          $reportee_list = implode(", ", CreateSmartyArray($c->GetUsersByLocationId($location_id, array(ROLE_ACCOUNT_EXECUTIVE)), 'login', 'login'));
          $filter .= " AND au_ae.user_id IN ($reportee_list) ";
      }
      if (preg_match("/^login_(\d+)$/", $o['ae_login'], $matches)){
         $login = $matches[1];
         $filter .= " AND au_ae.user_id = {$login} ";
      }
      if (preg_match("/^location_id_(\d+)$/", $o['am_login'], $matches)) {
          $location_id = $matches[1];
          $reportee_list = implode(", ", CreateSmartyArray($c->GetUsersByLocationId($location_id, array(ROLE_ACCOUNT_EXECUTIVE)), 'login', 'login'));
          $filter .= " AND au_am.user_id IN ($reportee_list) ";
      }
      if (preg_match("/^login_(\d+)$/", $o['am_login'], $matches)){
         $login = $matches[1];
         $filter .= " AND au_am.user_id = {$login} ";
      }
      if (isset($o['account_status']) && $o['account_status'] != "") {
      	$filter .= " AND a.account_status_id in (". ACM_WAITING_FOR_REVIEW .",". ACM_CREDIT_LIMIT_INCREASE_PENDING .") ";
      }
      
      $filter .= $a->BuildSearchString($o);
     
      $_SESSION['acm_main_filter'] = $filter;
      $_SESSION['acm_main_filter_on'] = 1;
      $_SESSION['acm_main_search_o'] = $o;
      $_SESSION['acm_main_advanced_flag'] = $o['advanced_flag'];

      $redirect = 1;
   }

   //search advance
   if (isset($_SESSION['acm_main_advanced_flag'])) {
      $o['advanced_flag'] = $_SESSION['acm_main_advanced_flag'];
   }


   //sorting
   if ($o['sort'] != '') {
      $_SESSION['acm_main_sort'] = $o['sort'];
      $redirect = 1;
   }
   
   if ($redirect == 1) {
   	header("Location: ?action=display_account_list");
   	return false;
   }

   
   //are we sorting 
   if (isset($_SESSION['acm_main_sort'])) {
      $sort = $_SESSION['acm_main_sort'];
      $o['sort_by_column'] = $sort;
   }
   
   //searching
   if (isset($_SESSION['acm_main_filter'])) {
   	$filter = $_SESSION['acm_main_filter'];
   	$meta['so'] = $_SESSION['acm_main_search_o'];
   	$meta['filter_on'] = 1;
   }


   BuildPageLimit($o, $start, $page_size);
   

   if ($o['display_all'] == 1) {
      $page = '';
   } else {
      $page = 'LIMIT '.$start.','.$page_size;
   }
   
   $a->GetAccountList($filter);
   
   
   $meta['report_title'] = "Accounts";
   $meta['start']        = $start;
   $meta['total_rows']   = $a->rows;
  
   $page_list[1] = 0;
   
   for ($cnt = $page_size; $cnt <= $a->rows; $cnt += $page_size) {
   	$page_list[] = $cnt;
   }
   
   $meta['page_list']    = array_flip($page_list);
   $meta['last_page_start'] = $page_list[count($page_list)];
   $meta['page_size_list'] = array(10 => 10, 25 => 25, 50 => 50, 100 => 100);
   $meta['page_size'] = $page_size;
   
   $list['account']          = PrepareSmartyArray($a->GetAccountList($filter, $page, $sort));
   
   $meta['page_rows'] = $a->rows;
   
   $list['account_type']     = CreateSmartyArray($a->GetAccountTypeList(), 'account_type_id', 'account_type_description');
   $list['account_sub_type'] = CreateSmartyArray($a->GetAccountSubTypeList(), 'account_sub_type_id', 'account_sub_type_description');
   $locations 	= CreateSmartyArray($c->GetLocationList(), "location_id", "location_description");
   $aes 			= CreateSmartyArray($c->GetUsersByRoleId(ROLE_ACCOUNT_EXECUTIVE), "login", "name");
   
   $toggled_aes 	= CreateSmartyArray($c->GetUsersByRoleId(ROLE_ACCOUNT_EXECUTIVE, 'T'), "login", "name");
   $aes 				= array_flip(array_merge(array_flip($aes), array_flip($toggled_aes)));
   
   $ams 				= CreateSmartyArray($c->GetUsersByRoleId(ROLE_ACCOUNT_MANAGER), "login", "name");
   $toggled_ams 	= CreateSmartyArray($c->GetUsersByRoleId(ROLE_ACCOUNT_MANAGER, 'T'), "login", "name");
  	$ams 				= array_flip(array_merge(array_flip($ams), array_flip($toggled_ams)));
   
   $list['ae']["(1"] = "Account Executive in -------------";
   foreach ($locations as $location_id => $location_description){
      $list['ae']["location_id_{$location_id}"] = $location_description;
   }
   $list['ae']["1)"] = "";
   $list['ae']["(2"] = "User -----------------";
   foreach ($aes as $login => $name){
      $list['ae']["login_{$login}"] = $name;
   }
   $list['ae']["2)"] = "";
   //$list['ae']               = CreateSmartyArray($c->GetUsersByRoleId(ROLE_ACCOUNT_EXECUTIVE), 'login', 'name');
   $list['am']["(1"] = "Account Manager in -------------";
   foreach ($locations as $location_id => $location_description){
      $list['am']["location_id_{$location_id}"] = $location_description;
   }
   $list['am']["1)"] = "";
   $list['am']["(2"] = "User -----------------";
   foreach ($ams as $login => $name){
      $list['am']["login_{$login}"] = $name;
   }
   $list['am']["2)"] = "";
   //$list['am']               = CreateSmartyArray($c->GetUsersByRoleId(ROLE_ACCOUNT_MANAGER), 'login', 'name');
   $list['region']           = CreateSmartyArray($c->GetCustomRegions(),'region_id','region_name');
   $list['country']          = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
   $list['yesno']            = array(0 => 'No', 1 => 'Yes');
   $list['account_status']   = array(1 => 'Waiting for Accounting Review') ;
   $header = array(
					   	0 => 
					   			array(
					   				'field' => 'a.account_id', 
					   				'title' => 'Account ID'
					   			),
					      1 => array('field' => 'a.account_name', 'title' => 'Account Name'),
					      2 => array('field' => 'c.country_description', 'title' => 'Account Country'),
					      3 => array('field' => 'u_am.last_name', 'title' => 'Account Manager'),
					      4 => array('field' => 'u_ae.last_name', 'title' => 'Account Executive'),
					      5 => array('field' => 'aa_tier.account_attr_value', 'title' => 'Tier'),
					      6 => array('field' => 'aa_isam.account_attr_value', 'title' => 'Handled By AM'),
                   );
   
   $meta = array_merge($o, $meta);
   
   
   $smarty->assign('list', $list);
   $smarty->assign('meta', $meta);
   $smarty->assign('header', $header);
   

   if (isset($o['export_excel']) && $o['export_excel'] == 1) {
   	header("Pragma: public"); // required
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); // required for certain browsers 
   	header('Content-type: application/vnd.ms-excel');
      header("Content-disposition: attachment; filename=\"account_list_".date('Ymd').".xls\"");
      $smarty->display('app/acm/rp_list.tpl');
   } else {
	   DisplayHeader("Account Manager", "acm");
	
	   $smarty->display('app/acm/vw_list.tpl');
	   
	   DisplayFooter();  
   }
}


function DisplayTest()
{
	global $smarty ;
	 
	DisplayHeader("Account Manager", "acm");
	
	$smarty->display('app/acm/credit_limit_verification.tpl');
	   
	DisplayFooter();  
}


/**
* HBRPC_ACM_SetAccountRetainer()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 09 15:32:57 PST 2006
*/
function HBRPC_ACM_SetAccountRetainer($o)
{
   SetAccountRetainer($o['account_id'], $o['retainer_amount']);
   return array('error_code' => 0, 'error_message' => 'Sucess'); 
}

/**
* HBRPC_ACM_UseAccountRetainer()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 09 16:18:30 PST 2006
*/
function HBRPC_ACM_UseAccountRetainer($o)
{
   //TODO add code
   return UseAccountRetainer($o['account_id'], $o['retainer_amount']);
}

/**
* DisplayAddAccount()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 10 14:04:57 PST 2006
*/
function DisplayAddAccount($o)
{
   global $smarty;
   
   $a = new accountDB();
   $c = new commonDB();
   
   $list['account_type'] = CreateSmartyArray($a->GetAccountTypeList(), 'account_type_id', 'account_type_description');
   $list['country'] = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
   $list['account_sub_type'] = CreateSmartyArray($a->GetAccountSubTypeList(), 'account_sub_type_id', 'account_sub_type_description');
   
   $smarty->assign('list', $list);
   
   DisplayHeader("Accounts", "acm", $o['action']);
   
   $smarty->display('app/acm/vw_add.tpl');

   DisplayFooter();
}

/**
* SaveAccount()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 10 15:29:16 PST 2006
*/
function SaveAccount($o)
{
	
   global $smarty;
	
	$a = new accountDB();
	
	if (isset($o['new_account']) && $o['new_account'] == 0) {
		
		$a->SetAccountId($o['account_id']);
	   $a->UpdateAccount($o['account_name'], $o['country_code']);
	   
	   $a->DeleteAccountType();
   
      $a->SetAccountType($o['account_type_id']); 
	   
	   $a->DeleteAccountSubType();	   

	   $a->SetAccountSubType($o['account_sub_type_id']); 
	   
	  	//load the Account domain object
	  	$account    = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($o['account_id']);
	   
	   //If The Account is Customer Type, Send a message to the Queue
		// message contents - Account Id, Account Name
		if($account->GetAccountType() == ACCOUNT_TYPE_CUSTOMER){
			//
			$modify_account_message = new Hb_Util_PMP_MessageQueue_ModifyAccount(array('marsc_account_id' 	=> $o['account_id'], 
																														'marsc_account_name' => $account->GetAccountName()));	
			//send the message in to the queue	
			$modify_account_message->Process();
		}
	   
		//check whether Account type is Recruiter Account
		if($a->isAccountType(ACCOUNT_TYPE_RECRUITER))
		{  
			header("Location:/app/Account/DisplayRecruiterAccountDetails/account_id/". $o['account_id']);
		} else {
			header("Location: ?action=display_account_detail&account_id=". $o['account_id']);
		}
		
	   return true;
	}

   $filter = " AND a.account_name LIKE '". substr($o['account_name'], 0, 1) ."%' ";
   $similar_accounts = array();
   
   $rs = $a->GetAccountList($filter, ' ');
   
   //$orig = metaphone(strtolower($o['account_name']));
   
   while ($r = mysql_fetch_assoc($rs)) {
   	if (levenshtein(strtolower($o['account_name']), strtolower($r['account_name'])) <= 4) {
   	        $similar_accounts[] = $r;
   	}
   }
   
   $filter = " AND a.account_name LIKE '%". mysql_real_escape_string($o['account_name']) ."%' ";
   $rs = $a->GetAccountList($filter, ' ');
   
   while ($r = mysql_fetch_assoc($rs)) {
   	$similar_accounts[] = $r;
   }
   
   $similar_accounts = array_unique($similar_accounts);
   
   if (count($similar_accounts) > 0) {
   	//display Similar Accounts
   	DisplayHeader("Account Manager", "acm");
   	$smarty->assign('meta', $o);
   	$smarty->assign('similar_accounts', $similar_accounts);
   	$smarty->display('app/acm/vw_similar_accounts.tpl');
   	DisplayFooter();
   		
   } else {
   	ForceSaveAccount($o); 
   }   
}

/**
* SetAccountAttr()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 17 17:27:57 PST 2006
*/
function SetAccountRetainer($account_id, $retainer_amount)
{
   $a = new accountDB();
   $a->SetAccountId($account_id);
   $retainer_value_on_file = $a->GetAccountAttrByAttrName('ARMC_RETAINER_VALUE');
   
   $total_retainer = $retainer_value_on_file + $retainer_amount;
   
   if ($a->isAccountAttrSet('ARMC_RETAINER_VALUE')) {
      $a->UpdateAccountAttr('ARMC_RETAINER_VALUE', $total_retainer);  
   } else {
      $a->SetAccountAttr('ARMC_RETAINER_VALUE', $total_retainer);
   }
   
   return true;  
}

/**
* UseAccountRetainer()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 08 11:18:07 PST 2006
*/
function UseAccountRetainer($account_id, $retainer_amount)
{
   $a = new accountDB();
   $a->SetAccountId($account_id);
   $retainer_value_on_file = $a->GetAccountAttrByAttrName('ARMC_RETAINER_VALUE');
   
   //no retainer on file
   if (!$retainer_value_on_file) {
      $retainer_value_on_file = 0;
   }
   
   //requested more 
   if ($retainer_amount > $retainer_value_on_file) {
      return array("error_code" => 500, "error_message" => $retainer_value_on_file);
   }
   
   
   $total_retainer = $retainer_value_on_file - $retainer_amount;
   
   $a->UpdateAccountAttr('ARMC_RETAINER_VALUE', $total_retainer);  
   return array("error_code" => 0, "error_message" => "sucess");  
   
}

/**
* ForceSaveAccount()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun Feb 19 18:22:09 PST 2006
*/
function ForceSaveAccount($o)
{
	$a = new accountDB();
	
	$a->SetAccount($o['account_name'], $o['country_code'], 'I');
	
	$account_id = $a->last_id;
	
	header("Location: /Account/type/id/". $account_id);
	
	return true;
}

/**
* DisplaySetAccountType()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun Feb 19 18:27:32 PST 2006
*/
function DisplaySetAccountType($o)
{
	global $smarty;
	
	if (isset($_SESSION['acm_message']) && $_SESSION['acm_message'] != '') {
		$meta['message'] = $_SESSION['acm_message'];
		unset($_SESSION['acm_message']);
	}
	
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	
	$account = $a->GetAccountDetail();
	$list['account_type'] =  CreateSmartyArray($a->GetAccountTypeList(), 'account_type_id', 'account_type_description');
	
	$rs = $a->GetAccountType();
	
	while ($r = $a->FetchAssoc($rs)) {
		$account['account_type'][] = $r['account_type_id'];		
		$list['account_sub_type'] 	= CreateSmartyArray($a->GetAccountSubTypeList($r['account_type_id']), 'account_sub_type_id', 'account_sub_type_description');
	}
	
	$rs = $a->GetAccountSubType();
	if ($a->rows > 0) {	
		
		while ($r = $a->FetchAssoc($rs)) {
			$account['account_sub_type'][] = $r['account_sub_type_id'];
		}		
	}	
	
  if(!isset($_SESSION['ACM_CREATE_CUSTOMER']))
  {
    unset($list['account_type'][ACCOUNT_TYPE_CUSTOMER]);
  }
  
	$smarty->assign('account', $account);
	$smarty->assign('list', $list);
	$smarty->assign('meta', $meta);
	
	DisplayHeader("Account Manager", "acm");
	
	$smarty->display('app/acm/vw_set_account_type.tpl');
	
	DisplayFooter();
	
}

/**
* GetAccountSubType()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun Feb 19 19:01:59 PST 2006
*/
function GetAccountSubType($o)
{
	$a = new accountDB();
	global $smarty;
	
	$account_type_id = explode(",", $o['account_type_id']);
	
	for ($i=0; $i < count($account_type_id); $i++) {
		if ($account_type_id[$i] != '') {
			$rs = $a->GetAccountSubTypeByType($account_type_id[$i]);	
			
			while ($r = mysql_fetch_assoc($rs)) {
				$sub_type[] = $r;
			}
			
		}
	}
	
	header("Content-type: text/xml");
	
	$smarty->assign('sub_type', $sub_type);
	$smarty->display('app/acm/xml_account_sub_type.tpl');
	
}

/**
* SaveAccountType()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun Feb 19 20:47:17 PST 2006
*/
function SaveAccountType($o)
{
	
	//Check whether user has permission to create customer account
	if($_POST['account_type_id']== ACCOUNT_TYPE_CUSTOMER){
		 if(!isset($_SESSION['ACM_CREATE_CUSTOMER'])){
		 		header("Location: ?action=display_add_account");
    			return true;
		}
	}

	$a = new accountDB();
	
	$a->SetAccountId($_POST['account_id']);

	//check whether Account types are set for the account id	
	if(mysql_num_rows($a->GetAccountType()) > 0){
		$a->DeleteAccountType();
		
		for ($i = 0; $i < count($_POST['account_type_id']); $i++) {			
			$a->SetAccountType($_POST['account_type_id'][$i]);	
		}		
	}else {
		for ($i = 0; $i < count($_POST['account_type_id']); $i++) {
			$a->SetAccountType($_POST['account_type_id'][$i]);	
		}		
	}	

	//check whether Account sub types are set for the account id	
	if(mysql_num_rows($a->GetAccountSubType()) > 0) {
		$a->DeleteAccountSubType();
				
		for ($i =0; $i < count($_POST['account_sub_type_id']); $i++) {
			$a->SetAccountSubType($_POST['account_sub_type_id'][$i]);
		}		
	}else {
		for ($i =0; $i < count($_POST['account_sub_type_id']); $i++) {
			$a->SetAccountSubType($_POST['account_sub_type_id'][$i]);
		}
	}	
	
	if (isset($_POST['client_has_account']) == 1) {
		if (!$a->isAccountHasProduct($_POST['product_id'], $_POST['product_account_id'])) {
			CreateAccountFromProduct($_POST['account_id'], $_POST['product_id'], $_POST['product_account_id']);
			return true;
		} else {
			$_SESSION['acm_message'] = "That account already exists in Accounts ";
			//header("Location: ?action=display_set_account_type&account_id=". $_POST['account_id']);
			return true;
		}
	}
	
	//need a decision point 
	//if ($a->isAccountType(ACCOUNT_TYPE_CUSTOMER) || $a->isAccountType(ACCOUNT_TYPE_PROSPECT)) {
	//	header("Location: ?action=display_select_product&account_id=". $_POST['account_id']);
	//	return true;
	//} 
	
	header("Location: /Account/newcontact/id/". $_POST['account_id']);
	return true;
}


function DisplayAddBasicAccountContact($o)
{
	
	global $smarty;
	
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	$account = $a->GetAccountDetail();
	$account['account_type']     = PrepareSmartyArray($a->GetAccountType());
	$account['account_sub_type'] = PrepareSmartyArray($a->GetAccountSubType());
	
	if (!isset($o['new_account'])) {
		$o['new_account'] = 1;
	}
	
	$c = new commonDB();
	$list['country'] = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
	$list['contact_type'] = CreateSmartyArray($c->GetContactTypes(), 'contact_type_id', 'contact_type_description');
	$list['title'] = array('MR.', 'MRS.', 'MS.');
	$list['portal_access_type'] = array(1 => 'No Access', 2 => "User", 3 => "Administrator");
	
	$template_attr = GetFormValidationAttr('vw_basic_account_contact.tpl');
	
	$smarty->assign('account', $account);
	$smarty->assign('meta', $o);
	$smarty->assign('list', $list);
	$smarty->assign('template_attr', $template_attr);
	
	DisplayHeader("Account Manager", "acm");
	
	$template = 'app/acm/vw_basic_account_contact.tpl';
	
	if ($_SESSION['user_type_id'] == USER_TYPE_EXTERNAL && $_SESSION['is_vendor'] == 0) {
	  $template = 'app/acm/ext/vw_basic_account_contact.tpl';   
	}	
	
	$smarty->display($template);
	
	DisplayFooter();
}

/**
* DisplayAddAccountContact()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun Feb 19 22:31:48 PST 2006
*/
function DisplayAddAccountContact($o)
{	
	global $smarty;	
	
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	$account = $a->GetAccountDetail();
	$account['account_type']     = PrepareSmartyArray($a->GetAccountType());
	$account['account_sub_type'] = PrepareSmartyArray($a->GetAccountSubType());
	
	if (!isset($o['new_account'])) {
		$o['new_account'] = 1;
	}
	
	$c = new commonDB();
	$list['country'] = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
	$list['contact_type'] = CreateSmartyArray($c->GetContactTypes(), 'contact_type_id', 'contact_type_description');
	$list['title'] = array('MR.', 'MRS.', 'MS.');
	$list['portal_access_type'] = array(1 => 'No Access', 2 => "User", 3 => "Administrator");
	
	$template_attr = GetFormValidationAttr('vw_account_contact');
	
	$account_obj = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($o['account_id']);
	$account_term = $account_obj->GetAccountTerm();
	
	$display['view_credit_hold_status'] = 0;	
	if(($a->isAccountType(ACCOUNT_TYPE_CUSTOMER) || $a->isAccountType(ACCOUNT_TYPE_PROSPECT))){
	   $display['view_credit_hold_status'] = 1;	  	
	}

	
	if(!isset($_SESSION['ACM_MANAGE_BILLING_CONTACT']))
  	{
    unset($list['contact_type'][CONTACT_TYPE_PRIMARY_BILLING]);
    unset($list['contact_type'][CONTACT_TYPE_BILLING]);
  	}

	$smarty->assign('account_term',$account_term);
	$smarty->assign('display', $display);
	$smarty->assign('account', $account);
	$smarty->assign('meta', $o);
	$smarty->assign('list', $list);
	$smarty->assign('template_attr', $template_attr);
	
	
	DisplayHeader("Account Manager", "acm");
	
	$template = 'app/acm/vw_account_contact.tpl';
	
	if ($_SESSION['user_type_id'] == USER_TYPE_EXTERNAL && $_SESSION['is_vendor'] == 0) {
	  $template = 'app/acm/ext/vw_account_contact.tpl';   
	}	
	
	$smarty->display($template);
	
	DisplayFooter();
}




/**
* GetFormValidationAttr()
*
* @param
* @param 
* @return
* @since  - 20:33:28
*/
function GetFormValidationAttr($template_name)
{
	switch ($template_name) {
		case 'vw_account_contact':
			/* data structure for defining required field */
		$template_attr['validation'] = array(
			'contact_first_name' => array(
				'required'     => true,
				'fail_message' => 'First Name is Required',
				),
			'contact_last_name'  => array(
				'required'     => true,
				'fail_message' => 'Last Name is Required', 
				),
			'contact_email'  => array(
				'required'     => true,
				'fail_message' => 'Email is Required', 
				),
			'contact_phone_number' => array(
				'required'     => true,
				'fail_message' => 'Phone Number is Required',
				),
			'address_street_1' => array(
				'required'  => true,
				'fail_message' => 'Address is required',
				),
			'address_city' => array(
				'required'  => true,
				'fail_message' => 'City is required',
				),
			'address_zip' => array(
				'required'  => true,
				'fail_message' => 'Zip is required',
				),
			'address_country_code' => array(
				'required'  => true,
				'fail_message' => 'Country is required',
				)
			);
			break;
	}
	
	return $template_attr;
	
}

/**
 * Validate Account Contact
 *
 * @param  $o
 */
function ValidateAccountContact($o)
{	
	
 if(!isset($_SESSION['ACM_MANAGE_BILLING_CONTACT'])AND (($o['contact_type_id'])== CONTACT_TYPE_PRIMARY_BILLING or ($o['contact_type_id'])== CONTACT_TYPE_BILLING))
   {
    header("Location: ?action=display_account_detail&account_id=". $o['account_id']);
    return true;
   }
  	
	if ('false' == $o['validation'])
	{
		$address				 = new Hb_App_Common_Address(null,$o['address_city'],$o['address_country_code'],null,$o['address_state'],$o['address_street_1'],$o['address_street_2'],null,$o['address_zip'],null,null) ;
		$address_validator = Hb_Util_Service_AddressValidation_AddressValidationFactory::GetInstance($address);
		if ($address_validator->IsValid()) 
		{
			SaveAccountContact($o); 
		}
		else 
		{
			DisplayAddContactWithRecommendedAddresses($address_validator->GetStatusCode(),$address_validator->GetRecommendedAddresses(),$o) ;
		}		
	}
	else
	{
		SaveAccountContact($o); 
	}
}

/**
 * Displays Add Contact form with Recommended Addresses
 *
 * @param  int $status_code Status Code of the Validation
 * @param  array $recommended_addresses Recommended Addressses
 * @param  array $o
 */
function DisplayAddContactWithRecommendedAddresses($status_code,$recommended_addresses,$o)
{
	global $smarty ;
	
	$smarty->assign('status_code', $status_code);			
	$smarty->assign('recommended_addresses',array_slice($recommended_addresses, 0, 5)) ;
	
	if ($o['contact_id'] == 0)
	{		
		DisplayAddAccountContact($o);
	}
	else
	{
		DisplayEditContact($o);
	}
}


/**
* SaveAccountContact()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 20 11:29:23 PST 2006
*/
function SaveAccountContact($o)
{
	global $encryption;		
	
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	$r = $a->FetchAssoc($a->GetAccountType());
	$account_type_id = $r['account_type_id'];
	
	$rs_contact_attr_def = $a->GetAccountContactAttrDef();
	
	$c = new commonDB();	
	
	//we are adding a new contact to the account
	if ($o['contact_id'] == 0) {
		$c->SetContact($o['contact_type_id'], $o['contact_title'], $o['contact_first_name'], $o['contact_middle_name'], 
								$o['contact_last_name'], $o['contact_suffix'], $o['contact_email']);
		$contact_id = $c->last_id;		

		$c->SetAddress($contact_id, ADDRESS_TYPE_WORK, $o['address_street_1'], $o['address_street_2'], $o['address_city'], $o['address_state'],
		 						$o['address_province'], $o['address_zip'], $o['address_country_code']);
		
		$c->SetContactPhone($contact_id, PHONE_TYPE_WORK, '0', $o['contact_phone_number'], '0');
		
		if ($o['contact_fax_number'] != '') {
			$c->SetContactPhone($contact_id, PHONE_TYPE_FAX, '0', $o['contact_fax_number'], '0');	
		}
		
		$a->SetAccountContact($contact_id);
		$account_contact_id = $a->last_id;

		/* setting account contact attributes */
		while ($r_contact_attr_def = $a->FetchAssoc($rs_contact_attr_def)) {
			
			if (isset($o[$r_contact_attr_def['account_contact_attr_name']]) && $o[$r_contact_attr_def['account_contact_attr_name']] != '') {
				$a->SetAccountContactAttr($account_contact_id, $r_contact_attr_def['account_contact_attr_name'], 
					$o[$r_contact_attr_def['account_contact_attr_name']]);
			}
			
		}
		
		
	} else {
		$contact_id = $o['contact_id'];
		//updating an existing contact

		$c->UpdateContact($o['contact_id'], $o['contact_type_id'], $o['contact_title'], $o['contact_first_name'], $o['contact_middle_name'], 
								$o['contact_last_name'], $o['contact_suffix'], $o['contact_email']);
								
		$c->UpdateAddress($o['address_id'], ADDRESS_TYPE_WORK, $o['address_street_1'], $o['address_street_2'], $o['address_city'], $o['address_state'],
		 						$o['address_province'], $o['address_zip'], $o['address_country_code']);
		
		if (is_numeric($o['contact_phone_id']) && $o['contact_phone_id'] != 0) {
			$c->UpdateContactPhone($o['contact_phone_id'], PHONE_TYPE_WORK, '0', $o['contact_phone_number'], '0');
		}else {
			$c->SetContactPhone($contact_id, PHONE_TYPE_WORK, '0', $o['contact_phone_number'], '0');
		}		
		
		if (is_numeric($o['contact_fax_id']) && $o['contact_fax_id'] != 0) {
			//$c->SetContactPhone($o['contact_id'], PHONE_TYPE_FAX, '0', $o['contact_fax_number'], '0');	
			$c->UpdateContactPhone($o['contact_fax_id'], PHONE_TYPE_FAX, '0', $o['contact_fax_number'], '0');	
		} else {
			$c->SetContactPhone($o['contact_id'], PHONE_TYPE_FAX, '0', $o['contact_fax_number'], '0');	
		}
		
		/* lets find out the account contact id */
		$account_contact_id = $a->GetAccountContactIdByContactId($o['contact_id']);

		if ($account_contact_id) {

			while ($r_contact_attr_def = $a->FetchAssoc($rs_contact_attr_def)) {
				
				if (isset($o[$r_contact_attr_def['account_contact_attr_name']]) && $o[$r_contact_attr_def['account_contact_attr_name']] != '') {

					if ($a->isAccountContactAttrSet($account_contact_id, $r_contact_attr_def['account_contact_attr_name'])) {
						$a->UpdateAccountContactAttr($account_contact_id, $r_contact_attr_def['account_contact_attr_name'], 
							$o[$r_contact_attr_def['account_contact_attr_name']]);
					} else {
						$a->SetAccountContactAttr($account_contact_id, $r_contact_attr_def['account_contact_attr_name'], 
							$o[$r_contact_attr_def['account_contact_attr_name']]);
					}					
				}
			}			
		}	
	}
	
	/* setup access */
	if ($o['PORTAL_ACCESS_TYPE_ID'] > 1) {
		
		if($o['PORTAL_PASSWORD'] == ''){
			$_SESSION['acm_message'] = "Error Occured :<br> Please Enter the Portal Access Password for the valid Portal User.";
	            
	      $encrypted = $encryption->Encrypt("action=display_edit_contact&account_id=". $o['account_id'] ."&contact_id=". $contact_id ."&new_account=".$o['new_account']);
	      
	      header("Location: ?e=". $encrypted);
			return true;
		}
			
	   try {
	     SetupAccountPortalUser($account_contact_id, $o['account_id'], $o['contact_email'], $o['PORTAL_PASSWORD'], 
	                             $o['contact_first_name'], $o['contact_last_name'], $o['PORTAL_ACCESS_TYPE_ID'],
	                             $contact_id);

		  //SendWelcomeMessage($o['contact_email'], $o['PORTAL_PASSWORD']);

	   } catch (Exception $e) {
	      $_SESSION['acm_message'] = 'Error Occured :' . $e->getMessage();
	            
	      $encrypted = $encryption->Encrypt("action=display_edit_contact&account_id=". $o['account_id'] ."&contact_id=". $contact_id ."&new_account=".$o['new_account']);
	      
			header("Location: ?e=". $encrypted);
			return true;
	   }
	   
	} else {
		/* need to test if the user already has an account and remove it */
		$pesudo_login  = $account_contact_id + 1000000;
		
		$u = new userDB();
		
		$u->SetLogin($pesudo_login);
		$u->SetStatus('T');
		
	}
	
	$account = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($o['account_id']);  
	
	//check whether Account type is Recruiter Account
	if($account->GetAccountType() == ACCOUNT_TYPE_RECRUITER)
	{  
		$a->UpdateAccountStatus('A');
		
		header("Location:/app/Account/DisplayRecruiterAccountDetails/account_id/". $o['account_id']);
		return true;
	}
	
	if ($o['new_account'] == 1) {
		header("Location: ?action=display_set_account_user_role&account_id=". $o['account_id']);
	} else {
		header("Location: ?action=display_account_detail&account_id=". $o['account_id']);
	}
	
	return true;
	
}

/**
* SendWelcomeMessage()
*
* @param
* @param 
* @return
* @since  - 12:14:39
*/
function SendWelcomeMessage($email, $password)
{
	global $smarty;
	
	$smarty->assign('username', $email);
	$smarty->assign('password', $password);
	
	$html = $smarty->fetch('app/acm/email_welcome_mygmi_html.tpl');
	$text = $smarty->fetch('app/acm/email_welcome_mygmi_text.tpl');
	
	$from = "MyGMI <nobody@". SERVER_NAME .">";
	$subject = "Welcome To MyGMI";
	
	$mail = new htmlMimeMail();
	$mail->setFrom($from);
	$mail->setSubject($subject);
	$mail->setHtml($html, $text);

	if (ISDEV == 1) {
		$mail->send(array('nayana@gmi-mr.com','jvonica@gmi-mr.com'));	
	} else {
		$mail->send(array($email));
	}	
}

/**
* SetupAccountPortalUser()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Thu Aug 31 14:41:04 PDT 2006
*/
function SetupAccountPortalUser($account_contact_id, $account_id, $contact_email, $password, 
                                $first_name, $last_name, $portal_access_type_id, $contact_id, $account_type_id = ACCOUNT_TYPE_CUSTOMER)
{
   $a = new accountDB();
   $u = new userDB();
	$admin = new adminDB();
	
	$a->SetAccountId($account_id);
		
	$pesudo_login  = $account_contact_id + 1000000;
		
   if ($u->isValidUsername($contact_email) && $u->GetLoginByEmail($contact_email) != $pesudo_login) {
      throw new Exception("The portal user is not setup because Email Address is Already Used By Another User (" . $contact_email .")", 101);
   }
				
	if ($a->isPortalUser($account_contact_id)) {
		$a->UpdatePortalUser($account_contact_id, $contact_email, $password);
	} else {
		$a->SetPortalUser($account_contact_id, $contact_email, $password);
	}
		
	$pesudo_login  = $account_contact_id + 1000000;
	$r             = $a->FetchAssoc($a->executeQuery("SELECT OLD_PASSWORD('". mysql_real_escape_string($password) ."') AS e_pass"));
		
	$encrypted_password = $r['e_pass'];
	$security_group_id = SECURITY_GROUP_CLIENT;
			
	if ($account_type_id == ACCOUNT_TYPE_VENDOR) {
		$security_group_id = SECURITY_GROUP_VENDOR;
	}
		
	$account_type = mysql_fetch_assoc($a->GetAccountType());
	
	if ($u->isValidUser($pesudo_login)) {		
		if($account_type['account_type_id'] == ACCOUNT_TYPE_RECRUITER) {
		
			$u->UpdateUser($pesudo_login, $first_name, $last_name, $contact_email, $encrypted_password, 1, USER_TYPE_RECRUITER);
			$account_type_id = ACCOUNT_TYPE_RECRUITER;
		
		}else {
			$u->UpdateUser($pesudo_login, $first_name, $last_name, $contact_email, $encrypted_password, 1, USER_TYPE_EXTERNAL);
			
			if (!$u->isUserMemberOfSecGroup($security_group_id, $pesudo_login)) {
				$admin->addUserSecurityGroup($pesudo_login, $security_group_id, $_SESSION['admin_id']);	
			}
		}
			
		if ($u->isUserAttrSet($pesudo_login, 'USER_ACCOUNT_ID')) {
			$u->UpdateUserAttr($pesudo_login, 'USER_ACCOUNT_ID', $account_id);	
		} else {
			$u->SetUserAttr($pesudo_login, 'USER_ACCOUNT_ID', $account_id);
		}
			
			
		$u->UpdateUserAttr($pesudo_login, 'USER_ACCOUNT_CONTACT_ID', $account_contact_id);
			
		$u->UpdateUserAttr($pesudo_login, 'USER_PORTAL_ACCESS_TYPE_ID', $portal_access_type_id);
			
		$u->UpdateUserAttr($pesudo_login, 'USER_ACCOUNT_TYPE_ID', $account_type_id);
			
		if ($u->isUserAttrSet($pesudo_login, 'USER_CONTACT_ID')) {
			$u->UpdateUserAttr($pesudo_login, 'USER_CONTACT_ID', $contact_id);
		} else {
			$u->SetUserAttr($pesudo_login, 'USER_CONTACT_ID', $contact_id);	
		}
		
		
      if (!$u->isUserAttrSet($pesudo_login, 'DUMMY_USER')) {
      	$u->SetUserAttr($pesudo_login, 'DUMMY_USER', '');
      }
		
      if ($account_type_id == ACCOUNT_TYPE_VENDOR) {
         $admin->deleteUserSecurityGroup($pesudo_login, SECURITY_GROUP_CLIENT);   
      } else {
         $admin->deleteUserSecurityGroup($pesudo_login, SECURITY_GROUP_VENDOR);   
      }     	
					
			
	} else {
			
		if($account_type['account_type_id'] == ACCOUNT_TYPE_RECRUITER) {
			$u->SetUser($pesudo_login, $first_name, $last_name, $contact_email, $encrypted_password, 1, USER_TYPE_RECRUITER);
			$account_type_id = ACCOUNT_TYPE_RECRUITER;
		}else {		
	  		/* need to check if email address is already used */			
	  		$u->SetUser($pesudo_login, $first_name, $last_name, $contact_email, $encrypted_password, 1, USER_TYPE_EXTERNAL);
	  		$admin->addUserSecurityGroup($pesudo_login, $security_group_id, $_SESSION['admin_id']);
		}	
	  
			
     $u->SetUserAttr($pesudo_login, 'USER_ACCOUNT_ID', $account_id);
     $u->SetUserAttr($pesudo_login, 'USER_ACCOUNT_CONTACT_ID', $account_contact_id);
	  $u->SetUserAttr($pesudo_login, 'USER_PORTAL_ACCESS_TYPE_ID', $portal_access_type_id);
	  $u->SetUserAttr($pesudo_login, 'USER_ACCOUNT_TYPE_ID', $account_type_id);
	  $u->SetUserAttr($pesudo_login, 'USER_CONTACT_ID', $contact_id);
	  
	  $u->SetUserAttr($pesudo_login, 'DUMMY_USER', '');
	  
	  if ($portal_access_type_id != 1 && CAN_SEND_WELCOME_MESSAGE == 1) {
	  		SendWelcomeMessage($contact_email, $password);
	  }
	  
	}
	
	$u->SetLogin($pesudo_login);
	$u->SetStatus('A');
	
	return $pesudo_login;
   
}

/**
* CreateAccountFromProduct()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 20 12:12:15 PST 2006
*/
function CreateAccountFromProduct($account_id, $product_id, $product_account_id)
{
	$p = array('account_id' => $product_account_id, 'product_id' => $product_id);
	
	$data = HBRPCCall("cnm", "GetAccountDetails", $p);

	$a = new accountDB();
	$c = new commonDB();

	$a->SetAccountId($account_id);
	
	$account_sub_type = $a->FetchAssoc($a->GetAccountSubType());
	
	$pricing_regime_id = PRICING_REGIME_TWO;
	
	if ($account_sub_type['account_sub_type_id'] == 4 || $account_sub_type['account_sub_type_id'] == 2) {
		$pricing_regime_id = PRICING_REGIME_THREE;
	}
	
	$o = $data['contact'];
	$o['contact_type_id'] = CONTACT_TYPE_BUSINESS;
	
	$c->SetContact($o['contact_type_id'], $o['contact_title'], $o['contact_first_name'], $o['contact_middle_name'], 
								$o['contact_last_name'], $o['contact_suffix'], $o['contact_email']);
	$contact_id = $c->last_id;
	
	unset($o);
	
	$o = $data['address'];
	$c->SetAddress($contact_id, ADDRESS_TYPE_WORK, $o['address_street_1'], $o['address_street_2'], $o['address_city'], $o['address_state'],
		 						$o['address_province'], $o['address_zip'], $o['address_country_code']);

	unset($o);
	
	$o = $data['contact_phone'];
	
	for ($i=0; $i < count($o); $i++) {
		$c->SetContactPhone($contact_id, $o[$i]['phone_type_id'], '0', $o[$i]['contact_phone_number'], '0');	
	}
		
	$a->SetAccountContact($contact_id);
	
	unset($o);
	
	$o = $data['account_attr'];
	
	for ($i=0; $i < count($o); $i++) {
		$a->SetAccountAttr($o[$i]['account_attr_name'], $o[$i]['account_attr_value']);
	}
	
	unset($o);
	
	$o = $data['account_user'];
	
	for ($i=0; $i < count($o); $i++) {
		if ($o[$i]['user_id'] != '') {
			$a->SetUserRole($product_id, $o[$i]['role_id'], $o[$i]['user_id']);
		}
	}
	
	unset($o);
	
	$o = $data['account_product'];
	
	$a->SetAccountProduct($product_id, $o['license_level_id'], $pricing_regime_id, $o['account_identifier']);
	
	$account_product_id = $a->last_id;
	
	unset($o);
	
	$o = $data['product_attr'];
	
	for($i=0; $i < count($o); $i++) {
		$a->SetAccountProductAttr($account_product_id, $o[$i]['account_product_attr_name'], $o[$i]['account_product_attr_value']);
	}
	
//	header("Location: ?action=display_attribute_form&account_id=". $account_id);
//	return true;	
	header("Location: ?action=display_account_detail&account_id=". $account_id);
	return true;
	
	
}

/**
* DisplaySetAccountUserRole()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 20 15:06:06 PST 2006
*/
function DisplaySetAccountUserRole($o)
{
	global $smarty;
	
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	
	$c = new commonDB();
	$u = new userDB();
	
	$account = $a->GetAccountDetail();
	$account['new_account'] = 1;
	
	if ($o['new_account'] == 0) {
		//get current assingments
		$account['user'] = PrepareSmartyArray($a->GetAccountUsers());
		$account['new_account'] = 0;
	}
	
	//accoun type id
	$r_account_type_id          = $a->FetchAssoc($a->GetAccountType());
	$account['account_type_id'] = $r_account_type_id['account_type_id'];
	
	$module_code = 'acm';
	
	if ($account['account_type_id'] == ACCOUNT_TYPE_VENDOR) {
		$module_code = 'vem';
	}
	
	$smarty->assign('account', $account);
	
	/* lets hack this for now */
	$list['role'] = CreateSmartyArray($c->GetRoleListByModule($module_code), 'role_id', 'role_description');
	
	
	$list['product'] = CreateSmartyArray($c->GetProductList(), 'product_id', 'product_description');
	
	
	$list['user'] = CreateSmartyArray($u->GetUserList('A'), 'login', 'name');
	
	$smarty->assign('list', $list);
	
	DisplayHeader("Account Manager", "acm");
	
	$smarty->display('app/acm/vw_set_account_user_role.tpl');
	
	DisplayFooter();
	
}

/**
* GetMatchingUsers()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 20 18:31:21 PST 2006
*/
function GetMatchingUsers($o)
{	
	global $smarty;
	
	$name = $o['name_' . $o['row_id']];
	
//	$debug = $name;
//	$debug .= print_r($o, true);
//	mail("root@localhost.localdomain", "debug", $debug);
	
	$c = new commonDB();
	
	$rs = $c->GetUsersByLogin($name);
	
	while ($r = $c->FetchAssoc($rs)) {
		$data[] = $r;
	}
	
	
	$rs = $c->GetUsersByName($name);
	
	while ($r = $c->FetchAssoc($rs)) {
		$data[] = $r;
	}
	
//	$debug = print_r($data, true);
//	mail("root@localhost.localdomain", "debug", $debug);


	$smarty->assign('data', $data);
	
	//header("Content-type: text/xml");
	$smarty->display('app/acm/ul_matching_users.tpl');
	
	return true;
}

/**
* SaveAccountUserRole()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 20 22:01:03 PST 2006
*/
function SaveAccountUserRole($o)
{
	
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);

	$product = array();
	
	$c = new commonDB();

	$rs = $c->GetProductList();
	
	while ($r = mysql_fetch_assoc($rs)) {
		$product[$r['product_id']] = array();
	}
	
	for ($i=0; $i< 10; $i++) {
		
		if ($o['role_id_'. $i] == ROLE_PRIMARY_ACCT_EXEC && isset($product[$o['product_id_'. $i]][ROLE_PRIMARY_ACCT_EXEC])) {
			$o['role_id_'. $i] = ROLE_SECONDARY_ACCT_EXEC;
		} elseif ($o['role_id_' . $i] == ROLE_PRIMARY_ACCT_MGR && isset($product[$o['product_id_'. $i]][ROLE_PRIMARY_ACCT_MGR])) {
			$o['role_id_'. $i] = ROLE_SECONDARY_ACCT_MGR;
		}
		
		$product[$o['product_id_' . $i]][$o['role_id_'. $i]] = 1;
		
		if ($o['login_'.$i] != '') {
			if (isset($o['account_user_id_'.$i])) {
				$a->UpdateUserRole($o['account_user_id_'.$i], $o['product_id_' . $i], $o['role_id_'. $i], $o['login_'. $i]);
			} else {
				$a->SetUserRole($o['product_id_' . $i], $o['role_id_'. $i], $o['login_'. $i]);
			}
		}		
	}

    
	if  ($o['new_account'] == 1) {
		header("Location: ?action=display_attribute_form&account_id=". $o['account_id']);	
	} else {
    	header("Location: ?action=display_account_detail&account_id=". $o['account_id']);	
   }
		
	if($a->isAccountType(ACCOUNT_TYPE_RECRUITER)){
    	header("Location: /app/Account/DisplayRecruiterAccountDetails/account_id/". $o['account_id']);	  
   } 
	return true;
	
}

/**
* DisplayAttributeForm()
*
* @param -
* @param - 
* @author - harsha
* @since  - Wed Apr 11 11:46:45 LKT 2007
*/
function DisplayAttributeForm($o)
{
	global $smarty;
	$template  	= 'app/acm/vw_account_attr_vendor.tpl';
	
	$common 		= new commonDB();	
	$acc 			= new accountDB();
	$acc->SetAccountId($o['account_id']);
	
	$account 				= $acc->GetAccountDetail();
	$list['currency'] 	= CreateSmartyArray($common->GetCurrencyList(), 'currency_code', 'currency_description');	
	$meta['new_account']	= 1;
	$meta['customer_type']	= 0;
	$display['preferred_currency'] = 1;
	
	//check whether account type is customer or perspect type
	if ($acc->isAccountType(ACCOUNT_TYPE_CUSTOMER) || $acc->isAccountType(ACCOUNT_TYPE_PROSPECT)) {
				
		//check whether security is set
		$security 		= CheckSecurityKey($_SESSION['admin_id'], GetSecurityKeys());		
		$smarty->assign('security', $security);		
		//get account attribtes that security key is set
		$security_account	= GetAccountAttributeSetForScurity();
		$account				= array_merge($account, $security_account);
		
		if($acc->isAccountType(ACCOUNT_TYPE_CUSTOMER)) {
			
			$meta['customer_type']	= 1;
			
			//get tax code list
			$list['tax_code_num'] = 1;
		
			$list['tax_code']	= CreateSmartyArray($common->GetCountryTaxCodes($account['country_code']), 'tax_code', 'tax_code_description');
			if(count($list['tax_code'] > 1)) {
				$list['tax_code_num'] = count($list['tax_code']);
			}   
		   //end of getting tax code
		   
		   //get payment terms
		   $list['payment_terms']	= CreateSmartyArray($common->GetPaymentTerms(), 'accounting_payment_term_id', 'payment_term_name');
		   //end of getting payment terms
		}
		$template = 'app/acm/vw_account_attr.tpl';
	}else {
		/* vendor specific list items */
		$list['account_status'] = CreateSmartyArray($acc->GetAccountStatusByAccountType(ACCOUNT_TYPE_VENDOR), 
											'account_status_id', 'account_status_description');
		$list['time_segment']   = array(
			'0-4' 	=> '0-4 Hours', 
			'5-24' 	=> '5-24 Hours', 
			'25+' 	=> '25+ Hours'
		);		
	}
	
	
	$smarty->assign('display', $display);
	$smarty->assign('account', $account);
	$smarty->assign('list', $list);	
	$smarty->assign('meta', $meta);
	
	DisplayHeader("Accounts", "acm");
	
	$smarty->display($template);
	
	DisplayFooter();
}


/**
* GetAccoutAttributeSetForScurity()
*
* @param -
* @param - 
* @return - return account attributes which attributes defaultly set to one
* @author - harsha
* @since  - Tue Apr 17 14:17:13 LKT 2007
*/
function GetAccountAttributeSetForScurity()
{ 
			$account_attr['attr']['STM_SEND_SAT_SURVEY']				= 1; 
			$account_attr['attr']['AMRC_ACCT_APPROVAL_REQUIRED']	= 1; 
			$account_attr['attr']['AMRC_AE_APPROVAL_REQUIRED']		= 0; 
	
	return $account_attr;	
}

/**
* CheckSecurityKey()
*
* @param -
* @param - 
* @return - return the array of security key's status 
* @author - harsha
* @since  - Thu Apr 12 11:26:06 LKT 2007
*/
function CheckSecurityKey($user_id, $security_keys)
{
	$security	= array();
	
 	foreach ($security_keys as $key) {
 		if(isset($_SESSION[$key]) && $_SESSION[$key] == 1) { 			
			$security[$key]	= 1;
 			continue;
 		}
 		
 		$security[$key]	= 0;
 	}
 	
 	return $security;
}


/**
* GetSecurityKeys()
*
* @param -
* @param - 
* @return - return array of security keys
* @author - harsha
* @since  - Thu Apr 12 12:49:20 LKT 2007
*/
function GetSecurityKeys()
{
  return array(
  	'ACM_CAN_SET_SAT_SURVEY_SEND_FLAG',
  	'ACM_CHANGE_AMRC_AE_APPROVAL',
  	'ACM_CHANGE_AMRC_ACCT_APPROVAL',
  	'ACM_CAN_SET_ORA_UPDATE',
  	'ACM_CAN_SET_LEAD_CREATION_DATA',
  	'ACM_CHANGE_PGEN_BOUND_LOW_PROPOSAL'
  	);
}

/**
* DisplayAccountDetail()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Feb 21 14:11:39 PST 2006
*/
function DisplayAccountDetail($o) 
{	
	global $smarty;
	
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	
	if($a->isAccountType(ACCOUNT_TYPE_RECRUITER)){
    	header("Location: /app/Account/DisplayRecruiterAccountDetails/account_id/". $o['account_id']);	  
    	return true;
   } 
   
	if ($_SESSION['user_type_id'] == USER_TYPE_EXTERNAL) {
		$o['account_id'] = $_SESSION['user_primary_account_id'];
	}
	$meta['display_btn_update_netmr'] = 0;
	$meta['display_btn_create_netmr'] = 0;
	
	//custom portlets
	$output['pgen'] = HBRPCCall("pgen", "GetPortlet", array(
		'filter' => array(
			'sc_account_id' => $o['account_id'], 
			'sf_account_id' => 'p.account_id'), 
		'portlet_width' => '100%', 
		'portlet_id' => 19001));
			
	//@runkit_function_remove("HBRPC_GetAttrValues");
	//@runkit_function_remove("HBRPC_GetPortlet");
	
	$output['atm'] = HBRPCCall('atm', "GetPortlet", array(
		'portlet_id' => 11001, 
		'filter' => array(
			'account_id' => array($o['account_id']), 
		'display_account_name' => 0)));
	
	$c = new commonDB();
	
	$list['currency'] = CreateSmartyArray($c->GetCurrencyList(), 'currency_code', 'currency_description');
	
	$account = $a->GetAccountDetail();
	$account['account_type']     = PrepareSmartyArray($a->GetAccountType());
	$account['account_sub_type'] = PrepareSmartyArray($a->GetAccountSubType());
	$account['contacts']         = PrepareSmartyArray($a->GetAllAccountContactsDetails(1));
	$account['user']             = PrepareSmartyArray($a->GetAccountUsers());
	$account['product']          = PrepareSmartyArray($a->GetAccountProducts());
	$account['file']             = PrepareSmartyArray($a->GetAccountFiles());
	$account['contact_msg']      = PrepareSmartyArray($a->GetAccountContactComments());
	$account['attr']             = CreateSmartyArray($a->GetAccountAttr(),'account_attr_name', 'account_attr_value');
	$account['recent_proposals'] = $output['pgen']['body'];	
	$account['recent_billing']   = $output['atm']['body'];
	
	/*Need to pass agreement text infor */
	$comments['infopass_agreement']  = PrepareSmartyArray($a->GetAccountComment(ACCOUNT_COMMENT_TYPE_INFO_PASS_AGREEMENT));
	
	/*Need to commecila agreement text infor */
	$comments['comterms_agreement']  = PrepareSmartyArray($a->GetAccountComment(ACCOUNT_COMMENT_TYPE_COMMERCIAL_TERMS));
	

	//need to test if we have product_account and deside to display either update or create partner buttons
	if ($a->hasProductAccount(PRODUCT_NETMR)) {
		$meta['display_btn_update_netmr'] = 1;
	} else {
		$meta['display_btn_create_netmr'] = 1;
	}
	
	/* default ons */
	$meta['display_div_products']    = 1;
	$meta['display_div_appointment'] = 1;
	$meta['display_div_proposals']   = 1;
	$meta['display_div_billing']     = 1;
	$meta['display_div_attr']        = 1;
	$meta['display_div_operations']  = 1;

	/* default offs */
	$meta['display_div_vendor_attr'] = 0;
	$meta['display_div_std_tb1']     = 0;
	$meta['display_div_std_tb2']     = 0;
	
	/* additional test for account_sub_type VENDOR */
	if ((ACCOUNT_TYPE_VENDOR == $account['account_type'][0]['account_type_id'])) {
		
		$account['panel_country'] = PrepareSmartyArray($a->GetAccountPanelCountry());
	
		$list['account_panel_country_type'] = PrepareSmartyArray($a->GetAccountPanelCountryTypes());
		
		/* turn offs */
		$meta['display_btn_create_netmr'] = 0;
		$meta['display_div_products']     = 0;
		$meta['display_div_appointment']  = 0;
		$meta['display_div_proposals']    = 0;
		$meta['display_div_billing']      = 0;
		$meta['display_div_attr']         = 0;
		/* turn ons*/
		$meta['display_div_vendor_attr'] = 1;
		$meta['display_div_std_tb1']     = 1;
		$meta['display_div_std_tb2']     = 1;
		$meta['display_div_agreement']	= 1;
		
		/* vendor specific list items */
		$list['account_status'] = CreateSmartyArray($a->GetAccountStatusByAccountType(ACCOUNT_TYPE_VENDOR), 'account_status_id', 'account_status_description');
		$list['time_segment']   = array('0-4' => '0-4 Hours', '5-24' => '5-24 Hours', '25+' => '25+ Hours');		
	}
	
	if ($_SESSION['user_type_id'] == USER_TYPE_EXTERNAL) {		
		$meta['display_div_operations']     = 0;
		$meta['display_div_communications'] = 0;		
	}
	
	//Account Hierarchy section here

	$acc_hiararchy = new acm_HierarchyManager($o['account_id']);	
	$acc_child		= $acc_hiararchy->GetAccountHierarchy();
	
	
	$account['parent_acc_id']	= $o['account_id'];	
	$related_account_flag		= 0;
	
	if($acc_hiararchy->DisplayRelatedAccount() && $_SESSION['user_type_id'] == USER_TYPE_INTERNAL) {
		$related_account_flag	= 1;		
	} elseif ($acc_hiararchy->DisplayRelatedAccount() && $acc_child ? true : false) {
		$related_account_flag	= 1;
	}
	
	//this error display setting should be refactore.
	$meta['account_error']	= 0;
	
	if(isset($_SESSION['error_code']) && $_SESSION['error_code'] != "") {
		$meta['account_error']	= 1;
		$meta['error_message'] 	= SetErrorMassange($_SESSION['error_code']);
	}
	
	$hierarchy_types = $acc_hiararchy->GetAccountHierarchyTypes();
	
	foreach ($hierarchy_types as $type) {
		$account_hierarchy_type['id'][]				= $type['account_hierarchy_type_id'];
		$account_hierarchy_type['description'][]	= $type['account_hierarchy_type_description'];
	}

	// Display warning if the Account has been merged into another account. (HB-1857).
	if ($account["account_status_id"] == ACCOUNT_STATUS_MERGED) {
	   $meta['account_error']	= 1;
	   $meta['error_message'] .= "THIS ACCOUNT HAS BEEN MERGED, PLEASE DO NOT USE";
	}
	

   $meta['customer_type']		= 0;
   
   $display['view_credit_hold'] 			= 0;
   $display['view_credit_hold_status'] = 0;
   $display['view_vendor_study_costs']	= 0;
   $display['preferred_currency']		= 0;
   $display['verify_details']				= 0;
   $display['credit_limit_request']		= 0;
   $display['edit_credit_limit'] 		= 0;
   
   if($a->isAccountType(ACCOUNT_TYPE_CUSTOMER)) {  	
   	
		$meta['customer_type']	= 1;
	   
	   //get tax code list
		$list['tax_code_num'] = 1;
	
		$list['tax_code']	= CreateSmartyArray($c->GetCountryTaxCodes($account['country_code']), 'tax_code', 'tax_code_description');
		if(count($list['tax_code'] > 1)) {
			$list['tax_code_num'] = count($list['tax_code']);
		}   
	   //end of getting tax code
	   
	   //get payment terms
	   $list['payment_terms']	= CreateSmartyArray($c->GetPaymentTerms(), 'accounting_payment_term_id', 'payment_term_name');
	   //end of getting payment terms
	   
	   
	   if(isset($_SESSION['ACM_MODIFY_CREDIT_LIMIT'])){
	   		$display['edit_credit_limit'] = 1;
	   }
   }
 	
   //if user posses permission and account status is credit limit pending
   if (isset($_SESSION['ACM_VERIFY_DETAILS']) && $account['account_status_id'] == ACM_CREDIT_LIMIT_INCREASE_PENDING) {
   	$display['credit_limit_request'] = 1;
   }
   	 
   //if the account type is Customer or Prospect and if the security key is set
   if(($a->isAccountType(ACCOUNT_TYPE_CUSTOMER) || $a->isAccountType(ACCOUNT_TYPE_PROSPECT))){
   	 
   	$display['view_credit_hold_status'] = 1;	
   	if(isset($_SESSION['ACM_ACCOUNT_CREDIT_HOLD'])){
   		$display['view_credit_hold'] = 1;
   	}
   	
   	if(isset($_SESSION['ACM_CHANGE_CURRENCY'])) {
   		$display['preferred_currency'] = 1;
   	}
   }else { 
   	     	
   	$display['view_vendor_study_costs']	= 1;
   	
   	$study_costs = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_StudyCosts')->FindByAccountId($o['account_id']);  
   	 	
   	if($study_costs->count() == 0) {
   		$display['view_vendor_study_costs']	= 0;
   	}   	
   	$smarty->assign('study_costs', $study_costs);   	  	
   }  
    
   if(isset($_SESSION['ACM_MANAGE_BILLING_CONTACT'])){
   	$display['view_add_basic_contact_button']	= 1;
   }
   
   if(isset($_SESSION['ACM_CREATE_CUSTOMER'])){
   	$display['view_account_edit_button']	= 1;
   }
   
   $account_obj = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($o['account_id']);
   $account_term = $account_obj->GetAccountTerm();
  
   $account_comments = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_AccountComments')->GetAuditComments($o['account_id'], ACCOUNT_COMMENT_TYPE_AUDIT);

   //check whether account type is prospect and verify details security is set and account status is waiting for request reveiw
   if($a->isAccountType(ACCOUNT_TYPE_PROSPECT)) {
   	if(($account_obj->GetAccountStatus() == ACM_WAITING_FOR_REVIEW) && isset($_SESSION['ACM_VERIFY_DETAILS'])){
   		$display['verify_details'] = 1;
   	}
   } 
   
   $account_vendors = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_AccountVendors')->FindByAccountId($o['account_id']);
   
   
   //set securities by security keys  
	$security 		= CheckSecurityKey($_SESSION['admin_id'], GetSecurityKeys());

	$smarty->assign('display', $display);
	$smarty->assign('account_term',$account_term);
	$smarty->assign('security', $security);	
	$smarty->assign('account', $account);
	$smarty->assign('lang', $o['lbl']);
	$smarty->assign('meta', $meta);
	$smarty->assign('list', $list);
	$smarty->assign('acc_child', $acc_child);
	$smarty->assign('hierarchy_types', $account_hierarchy_type);
	$smarty->assign('related_account_flag', $related_account_flag);
	$smarty->assign('comments', $comments);
	$smarty->assign('vendors', $account_vendors);
	
	$smarty->assign('audit_log',$account_comments);
	
	//DisplayHeader("Accounting Module", "acm");
	
	$template = 'app/acm/vw_account_detail.tpl';
	
	if ($_SESSION['user_type_id'] == USER_TYPE_EXTERNAL) {
	  $template = 'app/acm/ext/vw_account_detail.tpl';   
	}
	
	if ($_SESSION['is_vendor'] == 1) {
	   $template = 'app/acm/vw_account_detail.tpl';
	}
	
	
	$smarty->display($template);
	
	DisplayFooter();
	
}

/**
* SetErrorMassange()
*
* @param -
* @param - 
* @author - harsha
* @since  - Mon May 14 09:26:42 LKT 2007
*/
function SetErrorMassange($error_code)
{
	$error_message = '';
	
 	switch ($error_code) 
 	{ 		
 		case 'duplication':
 			$error_message 	= "You are either trying to associate the current account itself, or the account is already associated to the parent.";
			break;
 		case 'no_product':
 			$error_message = "There is no Product for this Account. So you can not place a Project Credit hold for this Account.";
 			break;
 			
 		case 'customer_type':
 			$error_message 	= "Check whether account's (parent or child) account type is Customer. 
 				You can associate only customer type accounts.";
			break;
					
 		case 'infinite_loop':
 			$error_message		= " System has stoped associating child account in to this account due to invalid hierarchy relationship. 
 				Please choose the Hierarchy Type as Fictional";
 		case 'oracle_synchronize_contact_error':
 			if(isset($_SESSION['error_message'])) {
 				$error_message = "Updating the Oracle Customer failed with the following message(s) <br>";
 				$error_message .= $_SESSION['error_message'];
 			} 			
 			unset($_SESSION['error_message']); 			
 			break;
 	}
 	
 	unset($_SESSION['error_code']);		
	return $error_message;
}


/**
* GetAccountByName()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 23 16:50:35 PST 2006
*/
function GetAccountByName($account_name)
{
	$a = new accountDB();
	$data = PrepareSmartyArray($a->GetAccountByName($account_name));
	return $data;
}

/**
* HBRPC_ACM_GetAccountByName()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 23 16:50:22 PST 2006
*/
function HBRPC_ACM_GetAccountByName($o)
{
	return GetAccountByName($o['account_name']);
}

/**
* HBRPC_ACM_GetAccountById()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 22 11:34:30 PST 2006
*/
function HBRPC_ACM_GetAccountById($o)
{
   return GetAccountById($o['account_id']);  
}

/**
* GetAccountById()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 22 11:35:08 PST 2006
*/
function GetAccountById($account_id)
{
   $a = new accountDB();
	$data = PrepareSmartyArray($a->GetAccountById($account_id));
	return $data;
}


/**
* GetAccountContactDetails()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 10 08:24:59 PST 2006
*/
function GetAccountContactDetails($account_id, $contact_id)
{
	$a = new accountDB();
	$a->SetAccountId($account_id);
	return $a->GetAccountContactDetails($contact_id);
}

/**
* HBRPC_ACM_GetAccountContactDetails()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 10 08:26:32 PST 2006
*/
function HBRPC_ACM_GetAccountContactDetails($o)
{
	$data = GetAccountContactDetails($o['account_id'], $o['contact_id']);
	$data['error_code'] = 0;
	$data['error_message'] = "Sucess";
	return $data;
}

/**
* SetContactMessage()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 27 21:43:38 PST 2006
*/
function HBRPC_ACM_SetContactMessage($o)
{	
	$file_type_id = 0;
	$emails = array();
	
	$meta = $o['meta'];
	
	$o = $o['msg'];
	
	foreach ($o['to'] as $email) {
		$emails[] = $email;
	}
	
	foreach ($o['cc'] as $email) {
		$emails[] = $email;
	}
	
	$a = new accountDB();
	$rs = $a->GetAccountContactsByEmail($emails); //get contacts that are associated with an account so we dont need the 2nd query
	
	if ($a->rows <= 0) {
		return array('error_code' => 0, 'error_message' => 'sucess');
	}
	
	if (is_array($o['headers']['received'])) {
		$header = implode("\r\n", $o['headers']['received']);
	} else {
		$header = $o['headers']['received'];
	}
	
	while ($r = mysql_fetch_assoc($rs)) {
		$a->SetAccountContactComment($r['account_contact_id'], 1, $o['headers']['from'], $o['headers']['to'], $header, $o['headers']['subject'], $o['body']['text']);
		
		//has file attachments
		if (count($o['file']) > 0) {
			$account_contact_comment_id = $a->last_id;
			for ($i=0; $i < count($o['file']); $i++) {
				$a->SetAccountContactCommentFile($account_contact_comment_id, $file_type_id, $o['file'][$i]['file_name'], $o['file'][$i]['file_name'], base64_decode($o['file'][$i]['data']), 1024);
			}
		}
		
	}
		
		
	

	return array('error_code' => 0, 'error_message' => 'sucess');
	
}

/**
* DisplayContactCommentDetail()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 01 11:14:28 PST 2006
*/
function DisplayContactCommentDetail($o)
{
	global $smarty;
	$a = new accountDB();
	
	$message = $a->GetAccountContactCommentDetail($o['account_contact_comment_id']);
	
	$message['file'] = PrepareSmartyArray($a->GetAccountContactCommentFiles($o['account_contact_comment_id']));
	
	$smarty->assign('message', $message);
	
	DisplayHeader("Comment Detail", "acm");
	$smarty->display('app/acm/vw_contact_comment_detail.tpl');
	DisplayFooter();
	return true;
}

/**
* GetAccountCommentFile()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 01 13:53:13 PST 2006
*/
function GetAccountCommentFile($o)
{
	$a = new accountDB();
	$r = $a->GetAccountContactCommentFile($o['account_contact_comment_file_id']);
	
	header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); // required for certain browsers 
   header('Content-type: application/octet-stream;');
   header("Content-disposition: attachment; filename=\"". $r['file_name'] ."\"");
  	echo $r['file_data'];
	
}

/**
* SaveAccountAttr()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 01 17:51:17 PST 2006
*/
function SaveAccountAttr($o)
{
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	
	$vendor_exclude = array(
		'ARMC_GMI_PO_REQUIRED', 
		'ARMC_VENDOR_PO_REQUIRED', 
		'ARMC_PAYMENT_AUTH_SUPERVISOR_REQUIRED', 
		'ARMC_PAYMENT_AUTH_MANAGER_REQUIRED',
		'GLOBAL_ACCOUNT_STATUS_ID',
		'ARMC_CREDIT_BALANCE'
	);
	
	/* set default permission */
//	if (!isset($_SESSION['ACM_TIER_1_SETUP_ALLOWED'])) {
//		$_SESSION['ACM_TIER_1_SETUP_ALLOWED'] = 0;
//	}
	
	$attr = CreateSmartyArray($a->GetAccountAttr(), 'account_attr_name', 'account_attr_value');
	$rs = $a->GetAccountAttrDef();
	while ($r = mysql_fetch_assoc($rs)) {
		
		/* dont overwrite the attributes that are not shown in the screen */
		if ($_SESSION['user_type_id'] == USER_TYPE_EXTERNAL) {
			if (in_array($r['account_attr_name'], $vendor_exclude)) {
				continue;
			}
		}
		
		/* test permission for tier 1*/
//		if ($_SESSION['ACM_TIER_1_SETUP_ALLOWED'] == 0 && $r['account_attr_name'] == 'GLOBAL_ACCOUNT_TIER_LEVEL' && $o[$r['account_attr_name']] == 1) {
//			continue;
//		}
		
		if ($o[$r['account_attr_name']] == 'on') 
				$o[$r['account_attr_name']] = 1;
				
		if (isset($o[$r['account_attr_name']]) && isset($attr[$r['account_attr_name']])) {
			$a->UpdateAccountAttr($r['account_attr_name'], $o[$r['account_attr_name']]);
		} elseif(!isset($o[$r['account_attr_name']]) && isset($attr[$r['account_attr_name']])){
				$o[$r['account_attr_name']] = 0;
			$a->UpdateAccountAttr($r['account_attr_name'], $o[$r['account_attr_name']]);
		} else {
			$a->SetAccountAttr($r['account_attr_name'], $o[$r['account_attr_name']]);			
			$a->UpdateAccountStatus('A');
		}
		
	}
	
	header("Location: ?action=display_account_detail&account_id=". $o['account_id']);
	return true;
}

/**
* DisplayEditContact()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Mar 06 07:43:34 PST 2006
*/
function DisplayEditContact($o)
{	
	global $smarty;
	
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	
	if (isset($_SESSION['acm_message']) && $_SESSION['acm_message'] != '') {
		$o['message'] = $_SESSION['acm_message'];
		unset($_SESSION['acm_message']);
	}
	
	$account = $a->GetAccountDetail();
	$account['account_type']     = PrepareSmartyArray($a->GetAccountType());
	$account['account_sub_type'] = PrepareSmartyArray($a->GetAccountSubType());
		
	$account['contact'] = $a->GetAccountContactDetails($o['contact_id']);
	$account['contact']['attr'] = CreateSmartyArray($a->GetAccountContactAttr($account['contact']['account_contact_id']), 'account_contact_attr_name', 'account_contact_attr_value');
	
	//print_r($account['contact']['attr']);
	
	$c = new commonDB();
	$list['country'] = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
	$list['contact_type'] = CreateSmartyArray($c->GetContactTypes(), 'contact_type_id', 'contact_type_description');
	$list['title'] = array('MR.', 'MRS.', 'MS.');
	$list['portal_access_type'] = array(1 => 'No Access', 2 => "User", 3 => "Administrator");
	
	$template_attr = GetFormValidationAttr('vw_account_contact');
	
	$display['view_credit_hold_status'] = 0;	
	
	if(($a->isAccountType(ACCOUNT_TYPE_CUSTOMER) || $a->isAccountType(ACCOUNT_TYPE_PROSPECT))){
		$display['view_credit_hold_status'] = 1;	
		
		$account_obj = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($o['account_id']);
   	$account_term = $account_obj->GetAccountTerm();
   	   	
   	$smarty->assign('account_term',$account_term);
	}	
   $smarty->assign('display', $display);   
	$smarty->assign('account', $account);
	$smarty->assign('meta', $o);
	$smarty->assign('list', $list);
	$smarty->assign('template_attr', $template_attr);
	
	DisplayHeader("Account Manager", "acm");
	
	$template = 'app/acm/vw_account_contact.tpl';
	
	if ($_SESSION['user_type_id'] == USER_TYPE_EXTERNAL && $_SESSION['is_vendor'] == 0) {
	  $template = 'app/acm/ext/vw_account_contact.tpl';   
	}
	
	$smarty->display($template);
	
	DisplayFooter();
}

/**
* HBRPC_ACM_SetAccountAttrs()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 08 11:28:45 PST 2006
*/
function HBRPC_ACM_SetAccountAttrs($o)
{
   return SetAccountAttrs($o['account_id'], $o['attr']);
   
}

/**
* SetAccountAttrs()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 08 11:29:49 PST 2006
*/
function SetAccountAttrs($account_id, $attr)
{
   $a = new accountDB();
   $a->SetAccountId($account_id);
   
   if (!$a->isAccountOnFile()) {
      return array("error_code" => 1, "error_message" => "Account Not Found");
   }
   
   for ($i=0; $i< count($attr); $i++) {
      if ($a->isAccountAttrSet($attr[$i]['account_attr_name'])) {
         $a->UpdateAccountAttr($attr[$i]['account_attr_name'], $attr[$i]['account_attr_value']);
      } else {
         $a->SetAccountAttr($attr[$i]['account_attr_name'], $attr[$i]['account_attr_value']);
      }
   }
   
   return array("error_code" => 0, "error_message" => "Sucess");
}

/**
* HBRPC_ACM_SetAccountContactAttrs()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 08 11:34:08 PST 2006
*/
function HBRPC_ACM_SetAccountContactAttrs($o)
{
   return SetAccountContactAttr($o['account_id'], $o['contact_id'], $o['attr']);
}

/**
* SetAccountContactAttr()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 08 11:34:45 PST 2006
*/
function SetAccountContactAttr($account_id, $contact_id, $attr)
{
   $c = new commonDB();
   $a = new accountDB();
   
   $a->SetAccountId($account_id);
   
   if (!$a->isAccountOnFile()) {
      return array("error_code" => 1, "error_message" => "Account Not Found");
   }
   
   for ($i=0; $i< count($attr); $i++) {
      if ($c->GetContactAttr($attr[$i]['contact_attr_name'])) {
         $c->UpdateContactAttr($contact_idm, $attr[$i]['contact_attr_name'], $attr[$i]['contact_attr_value']);
      } else {
         $c->SetContactAttr($contact_id, $attr[$i]['contact_attr_name'], $attr[$i]['contact_attr_value']);
      }
   }
   
   return array("error_code" => 0, "error_message" => "Sucess");
   
}

/**
* HBRPC_ACM_GetUsersByRegion()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 08 11:53:26 PST 2006
*/
function HBRPC_ACM_GetUsersByRegion($o)
{
   return GetUsersByRegion($o['region_id'], $o['role_id']);
}

/**
* GetUserByRegion()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 08 11:58:18 PST 2006
*/
function GetUsersByRegion($region_id, $role_id)
{
   $a = new accountDB();
   
   $rs = $a->GetAccountsUserByRegionRole($region_id, $role_id);
   
   if ($a->rows == 0) {
      return array("error_code" => 1, "error_message" => "No Entries");
   }
   
   $data['login'] = PrepareSmartyArray($rs);
   $data['error_code'] = 0;
   $data['error_message'] = "Sucess";
   
   return $data;
}

/**
* GetAccountsByUser()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 08 12:03:14 PST 2006
*/
function GetAccountsByUser($user_id, $role_id=0)
{
   $a = new accountDB();
   
   $data['accounts'] = PrepareSmartyArray($a->GetAccountsByUser($user_id, $role_id));
   $data['error_code'] = 0;
   $data['error_message'] = "Sucess";
   return $data;
}

/**
* HBRPC_ACM_GetAccountsByUser()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 08 12:04:17 PST 2006
*/
function HBRPC_ACM_GetAccountsByUser($o)
{
   if (isset($o['role_id'])) {
      return GetAccountsByUser($o['user_id'], $o['role_id']);
   } else {
      return GetAccountsByUser($o['user_id']);
   }
}

/**
* DisplayEditAccount()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 08 12:58:09 PST 2006
*/
function DisplayEditAccount($o)
{
   global $smarty;
   
   $c = new commonDB();
   
   $list['country'] = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
   
   $a = new accountDB();
   $a->SetAccountId($o['account_id']);
   
   $account = $a->GetAccountDetail();
   $rs = $a->GetAccountType();
   
   while ($r = mysql_fetch_assoc($rs)) {
   	$account['account_type'][] = $r['account_type_id'];
   	$list['account_sub_type'] = CreateSmartyArray($a->GetAccountSubTypeList($r['account_type_id']), 'account_sub_type_id', 'account_sub_type_description');
   }
   
   $rs = $a->GetAccountSubType();
   
   while ($r = mysql_fetch_assoc($rs)) {
   	$account['account_sub_type'][] = $r['account_sub_type_id'];
   }
   
   $list['account_type'] = CreateSmartyArray($a->GetAccountTypeList(), 'account_type_id', 'account_type_description');
   
   DisplayHeader("Accounts", "acm");
   
   $smarty->assign('account', $account);
   $smarty->assign('list', $list);
   
   $smarty->display('app/acm/vw_edit_account.tpl');
   
   DisplayFooter();
   
}

/**
* CreateProductAccount()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 08 19:38:09 PST 2006
*/
function CreateProductAccount($o)
{
	global $smarty;
	
	$a = new accountDB();
	$c = new commonDB();
	$a->SetAccountId($o['account_id']);
	
	DisplayHeader("Accounts", "acm");
	
	DisplayProgressMessage();
	
	DisplayProgressBar(300, 15);
	
	$data['account_product'] = $a->GetAccountProduct($o['product_id']);
	
	if ($data['account_product']) {
	   $data['account'] = $a->GetAccountDetail();

	   $data['contact'] = mysql_fetch_assoc($a->GetContacts());
	   
	   $data['address'] = $a->GetAccountContactDetails($data['contact']['contact_id']);
	
	   $data['contact_phone'][] = $data['address'];
	   $data['contact_phone'][] = $c->GetContactPhoneByPhoneType($data['contact']['contact_id'], PHONE_TYPE_FAX);
	
	   $rs = $a->GetAccountUser($o['product_id']);
	
	   while ($r = mysql_fetch_assoc($rs)) {
	      $data['account_user'][] = $r;	
	   }
	   
	   $rs = $a->GetAccountAttr();
	
	   while ($r = mysql_fetch_assoc($rs)) {
		   $data['account_attr'][] = $r;
	   }
	
	   $product = $data['account_product'];
	
	   $rs = $a->GetAccountProductAttr($product['account_product_id']);
	
	   while ($r = mysql_fetch_assoc($rs)) {
		
		   if($r['account_product_attr_name'] == 'ACCOUNT_LANGUAGE' && $r['account_product_attr_value'] == '') {
			   $r['account_product_attr_value'] = 'ENG';
		   }
		
		   $r['product_id'] = $o['product_id'];
		   $data['product_attr'][] = $r;
	   }
	
	
	   $p = array('product_id' => $o['product_id'], 'data' => $data);	
	   $data = HBRPCCall("cnm", "CreateProductAccount", $p);
	
	   $a->UpdateAccountProduct($product['account_product_id'], $o['product_id'], $product['license_level_id'], $product['pricing_regime_id'], $data['product_account_id']);
	
	   $message = "Account Created in Product with ID - ". $data['product_account_id'] . " ";
	
	   $smarty->assign('message', $message);
	} else {
	   $smarty->assign('message', "Product ID " . $o["product_id"] . " not found !!!");
	}
	$smarty->assign('account', array('account_id' => $o['account_id']));
	
	$smarty->display('app/acm/vw_continue.tpl');
	
	DisplayFooter();
}


/**
* UpdateProductAccount()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 08 19:38:09 PST 2006
*/
function UpdateProductAccount($o)
{
	global $smarty;
	
	$a = new accountDB();
	$c = new commonDB();
	$a->SetAccountId($o['account_id']);
	
	DisplayHeader("Accounts", "acm");
	
	DisplayProgressMessage();
	
	DisplayProgressBar(300, 15);
	
	$account_type_recruiter = 0;
	
	$data['account_product'] = $a->GetAccountProduct($o['product_id']);
	
	if ($data['account_product']) {
	   $data['account'] = $a->GetAccountDetail();

	   $data['contact'] = mysql_fetch_assoc($a->GetContacts());
	
	   $data['address'] = $a->GetAccountContactDetails($data['contact']['contact_id']);
	
	   $data['contact_phone'][] = $data['address'];
	   $data['contact_phone'][] = $c->GetContactPhoneByPhoneType($data['contact']['contact_id'], PHONE_TYPE_FAX);
	
	   $rs = $a->GetAccountUser($o['product_id']);
	
	   while ($r = mysql_fetch_assoc($rs)) {
		   $data['account_user'][] = $r;	
	   }
		
	   $rs = $a->GetAccountAttr();
	
		while ($r = mysql_fetch_assoc($rs)) {
			$data['account_attr'][] = $r;
		}
		$product = $data["account_product"];
	
		$rs = $a->GetAccountProductAttr($product['account_product_id']);
	
		while ($r = mysql_fetch_assoc($rs)) {
			if($r['account_product_attr_name'] == 'ACCOUNT_LANGUAGE' && $r['account_product_attr_value'] == '') {
				$r['account_product_attr_value'] = 'ENG';
			}
		
			$r['product_id'] = $o['product_id'];
			$data['product_attr'][] = $r;		
		}

	
		$p = array('product_id' => $o['product_id'], 'product_account_id' => $product['account_identifier'], 'data' => $data);
		$data = HBRPCCall("cnm", "UpdateProductAccount", $p);

	
	//$a->UpdateAccountProduct($product['account_product_id'], $o['product_id'], $product['license_level_id'], $product['pricing_regime_id'], $data['product_account_id']);
	
		$message = "Account Update in Product with ID - ". $product['account_identifier'] . " ";
	
		$smarty->assign('message', $message);
	} else {
		$smarty->assign('message', "Product ID " . $o["product_id"] . " not found !!!");
	} 
	
	if($a->isAccountType(ACCOUNT_TYPE_RECRUITER)){
		$account_type_recruiter = 1;
	}

	$smarty->assign('account', array('account_id' => $o['account_id']));
	$smarty->assign('account_type_recruiter', $account_type_recruiter);
	
	$smarty->display('app/acm/vw_continue.tpl');
	
	DisplayFooter();
	
}

/**
* HBRPC_ACM_GetAccountByProductAccount()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 22 08:45:45 PST 2006
*/
function HBRPC_ACM_GetAccountByProductAccount($o)
{
	if(isset($o['product_id']) && isset($o['account_identifier'])) {         
		$product_manager	= new acm_ProductManager();
		                   
		try {           
			$data = $product_manager->GetAccountByProductAccount($o['product_id'], $o['account_identifier']);       
		} catch (Exception $e) {            
			return array( 'error_code' => $e->getCode(), 'error_message' =>                 
				$e->getMessage() . " == ". $e->getFile() ." == ". $e->getLine() . " == ". $e->getTraceAsString() );         
		}               
		
		return $data;      	
	}	
}

/**
* DeleteContact()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sat Mar 25 10:35:35 PST 2006
*/
function DeleteContact($o)
{
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	$account_contact_id = $a->GetAccountContactIdByContactId($o['contact_id']);
	
	$a->DeleteAccountContact($o['contact_id']);
	
	//delete poratal user
	if($a->isPortalUser($account_contact_id)) {	
		$a->DeletePortalUser($account_contact_id);
	}
	//delete user
	$u = new userDB();
	
	$login = $account_contact_id + 1000000;
	
	if ($u->isValidUser($login)) {
		$u->SetLogin($login);
		$u->SetStatus('D');
	}

	$c = new commonDB();
	
	$c->DeleteContact($o['contact_id']);
	
	if ($a->isAccountType(ACCOUNT_TYPE_RECRUITER)){
		header("Location:/app/Account/DisplayRecruiterAccountDetails/account_id/". $o['account_id']);
		return false;	
	}
	
	header("Location: ?action=display_account_detail&account_id=". $o['account_id']);
	
	
}

/**
* MigrateProductAttr()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 29 18:44:02 PST 2006
*/
function MigrateProductAttr($o)
{
	$a = new accountDB();
	
	echo "<pre>";
	
	$rs = $a->GetAccountList(' ');
	
	while ($r = mysql_fetch_assoc($rs)) {
		
		$a->SetAccountId($r['account_id']);

		$r_product = $a->GetAccountProduct(PRODUCT_NETMR);
		
		if ($r_product && $r_product['account_identifier'] != '' && $r_product['account_identifier'] < 500000) {
			
			$p = array('account_id' => $r_product['account_identifier'], 'product_id' => PRODUCT_NETMR);
	
			$data = HBRPCCall("cnm", "GetAccountDetails", $p);			
			
			if ($data['error_code'] == 404) {
				print "No Attr Found For ID: ". $r['account_id'] ." Account_Product_ID: ". $r_product['account_product_id'] ."\n";	
				continue;
			} else {
				print "Setting Attr For ID: ". $r['account_id'] ." Account_Product_ID: ". $r_product['account_product_id'] ."\n";	
			}
						
			for ($i=0; $i < count($data['product_attr']); $i++) {
				if ($a->isAccountProductAttrSet($r_product['account_product_id'], $data['product_attr'][$i]['account_product_attr_name'])) {
					$a->UpdateAccountProductAttr($r_product['account_product_id'], $data['product_attr'][$i]['account_product_attr_name'], $data['product_attr'][$i]['account_product_attr_value']);
				} else {
					$a->SetAccountProductAttr($r_product['account_product_id'], $data['product_attr'][$i]['account_product_attr_name'], $data['product_attr'][$i]['account_product_attr_value']);					
				}
			}						
		}		
	}
	
	echo "</pre>";	
}

/**
* DisplayReport()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Apr 10 10:46:31 PDT 2006
*/
function DisplayTierReport($o)
{
	global $smarty;

	
	$a = new accountDB();
	$u = new userDB();
	$c = new commonDB();
	
	$location_users = CreateSmartyArray($u->GetUsersByRoleLocation(ROLE_ACCOUNT_MANAGER), 'location_id', 'user_count');
	$locations      = CreateSmartyArray($c->GetLocationList(), 'location_id', 'location_description');
	$users          = CreateSmartyArray($c->GetUsers(), 'login', 'name');
	
	$rs = $a->GetAccountsByRoleTier(ROLE_PRIMARY_ACCT_MGR, PRODUCT_NETMR);
	
	while ($r = mysql_fetch_assoc($rs)) {
		
		if (is_numeric($location_users[$r['location_id']]) && $location_users[$r['location_id']] > 0) {
		
			switch ($r['account_tier']) {
				case 1:
				case 2:
					$tier[$r['location_id']]['tier_12']  += ($r['account_count'] / $location_users[$r['location_id']]);
					$tier[$r['location_id']]['tier_123'] += ($r['account_count'] / $location_users[$r['location_id']]);
					break;
					
				case 3:
					$tier[$r['location_id']]['tier_123'] += ($r['account_count'] / $location_users[$r['location_id']]);
					break;
			
				default:
					break;
			}
			
			$tier[$r['location_id']]['number_of_ams'] = $location_users[$r['location_id']];
		
		}
	}
	
	$rs = $a->GetAccountsByRoleTierAm(ROLE_PRIMARY_ACCT_MGR, PRODUCT_NETMR);
	
	while ($r = mysql_fetch_assoc($rs)) {
		if ($r['account_tier'] > 0) {
			$user[$r['location_id']][$r['user_id']]['tier_'.$r['account_tier']] = $r['account_count'];
		}
	}
	
	//summary of not by am
	$rs = $a->GetAccountsTiersNotByAm();
	while ($r = mysql_fetch_assoc($rs)) {
		$notam['tier_'.$r['account_tier']] = $r['account_count'];
	}
	
	//accounts by sofia
	$filter = ' AND aa_isam.account_attr_value = 0 ';
	$list['account'] = PrepareSmartyArray($a->GetAccountList($filter));
	
	$smarty->assign('tier', $tier);
	$smarty->assign('locations', $locations);
	$smarty->assign('user', $user);
	$smarty->assign('users', $users);
	$smarty->assign('list', $list);
	$smarty->assign('notam', $notam);
	
	DisplayHeader("Accounts", "acm");
	
	$smarty->display('app/acm/vr_tier_assingment.tpl');
	
	
	DisplayFooter();
	
	
}

/**
* ReadTierFile()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Apr 10 12:59:21 PDT 2006
*/
function ReadTierFile($o)
{
	require_once('File.php');
   require_once('File/CSV.php');

   $FILENAME = "customer_tier_assignments.csv";

   $a = new accountDB();

   $conf = File_CSV::discoverFormat($FILENAME);

   
   while ($row = File_CSV::readquoted($FILENAME, $conf)) {
   	
   	$account_product_identifier = $row[0];
   	$account_tier               = $row[1];
   	
   	$account = $a->GetAccountByProductAccount(PRODUCT_NETMR, $account_product_identifier);
   	
   	if (!$account) {
   		echo "Account Not Found For ". $account_product_identifier ."\n";
   		continue;
   	}
   	
   	$a->SetAccountId($account['account_id']);
   	
   	echo "Account ". $account['account_id'] ." Prodcuct ID - ". $account_product_identifier ." - Tier - " . $account_tier ."\n";
   	
   	if ($o['write'] == 1) {
   		$a->UpdateAccountAttr('GLOBAL_ACCOUNT_TIER_LEVEL', $account_tier);	
   		
   		//write to study attr
   		
   		$q = "UPDATE study_attr SET study_value = '". $account_tier ."' "
   		   . "WHERE study_id = (SELECT study_id FROM study WHERE partner_id = ". $account['account_id'] .") AND study_attr = 'GLOBAL_ACCOUNT_TIER_LEVEL' ";
   		$a->executeQuery($q);
   		
   	}
   
   }
	
}

/**
* ReadNoAmFile()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Apr 10 13:20:00 PDT 2006
*/
function ReadNoAmFile($o)
{
	require_once('File.php');
   require_once('File/CSV.php');

   $FILENAME = "lt3000.csv";

   $a = new accountDB();

   $conf = File_CSV::discoverFormat($FILENAME);

   
   while ($row = File_CSV::readquoted($FILENAME, $conf)) {
   	
   	$account_product_identifier = $row[0];
   	$account_tier               = $row[1];
   	
   	$account = $a->GetAccountByProductAccount(PRODUCT_NETMR, $account_product_identifier);
   	
   	if (!$account) {
   		echo "Account Not Found For ". $account_product_identifier ."\n";
   		continue;
   	}
   	
   	$a->SetAccountId($account['account_id']);
   	
   	echo "Account ". $account['account_id'] ." Prodcuct ID - ". $account_product_identifier ." - Tier - " . $account_tier ."\n";
   	
   	if ($o['write'] == 1) {
   		$a->UpdateAccountAttr('GLOBAL_ACCOUNT_TIER_LEVEL', $account_tier);		
			$a->UpdateAccountAttr('GLOBAL_STUDY_HANDLED_BY_AM', 0);
   		
   		/* updating stm from the same migration script */
   		$q = "UPDATE study_attr SET study_value = '". $account_tier ."' "
   		   . "WHERE study_id = (SELECT study_id FROM study WHERE partner_id = ". $account['account_id'] .") AND study_attr = 'GLOBAL_ACCOUNT_TIER_LEVEL' ";   		
   		$a->executeQuery($q);
   		
   		$q = "UPDATE study_attr SET study_value = 0 "
   		   . "WHERE study_id = (SELECT study_id FROM study WHERE partner_id = ". $account['account_id'] .") AND study_attr = 'GLOBAL_STUDY_HANDLED_BY_AM' ";   		
   		$a->executeQuery($q);
   	}
   
   }
	
}


/**
* DeleteAccountUser()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu May 18 08:55:55 PDT 2006
*/
function DeleteAccountUser($o)
{
	$a = new accountDB();
	$a->SetAccountID($o['account_id']);
	
	$a->DeleteAccountUser($o['account_user_id']);

	if($a->isAccountType(ACCOUNT_TYPE_RECRUITER)){
    	header("Location: /app/Account/DisplayRecruiterAccountDetails/account_id/". $o['account_id']);	  
    	return false;
	}
	header("Location: ?action=display_account_detail&account_id=". $o['account_id']);
}

/**
* DisplayUploadFile()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu May 18 11:01:59 PDT 2006
*/
function DisplayUploadFile($o)
{
	global $smarty;
	
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	
	$c = new commonDB();
	$u = new userDB();
	
	$account = $a->GetAccountDetail();
	$smarty->assign('account', $account);
	$smarty->assign('list', $list);
	
	DisplayHeader("Account Manager", "acm");
	
	$smarty->display('app/acm/vw_upload_file.tpl');
	
	DisplayFooter();
}

/**
* UploadAccountFile()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu May 18 11:10:58 PDT 2006
*/
function UploadAccountFile($o)
{

	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	
	$fdata = GetFileData('account_file');

	if (!$fdata)
	{
			header("Location: ?action=display_upload_file&account_id=". $o['account_id'] ."&new_account=0");
			return false;	
	}
	
	$a->SetAccountFile($fdata['type_id'], $fdata['name'], $o['account_file_title'], $fdata['data'], $fdata['size']);
	
	if ($a->isAccountType(ACCOUNT_TYPE_RECRUITER)){
		header("Location:/app/Account/DisplayRecruiterAccountDetails/account_id/". $o['account_id']);
		return false;	
	}
	header("Location: ?action=display_account_detail&account_id=". $o['account_id']);	
}

/**
 * SaveInformationPassingAgreement()
 *
 * @param 
 * @param 
 */
function SaveInformationPassingAgreement($o)
{

	$account_db = new accountDB();
	$account_db->SetAccountId($o['account_id']);
	
	$fdata = GetFileData('account_file');

	if ($fdata)
	{		
		$account_db->SetAccountFile($fdata['type_id'], $fdata['name'], $o['account_file_title'], $fdata['data'], $fdata['size'], ACCOUNT_COMMENT_FILE_TYPE_INFO_PASS_AGREEMENT);		
	}
	
	if( $o['agreement_text'])
	{
		if($fdata)
		{
			$account_db->SetAccountComment($o['account_id'], ACCOUNT_COMMENT_TYPE_INFO_PASS_AGREEMENT, $o['agreement_text'].'<br><br> File Attached :'.$o['account_file_title'].'(<a href="?action=get_account_file&account_id='.$o['account_id'].'&account_file_id=' . $account_db->lastID .'"> '.$fdata['name'].' </a>)');		
		}
		else
		{
			$account_db->SetAccountComment($o['account_id'], ACCOUNT_COMMENT_TYPE_INFO_PASS_AGREEMENT, $o['agreement_text']);		
		}
		
	}

	header("Location: ?action=display_account_detail&account_id=". $o['account_id'] ."&#information_agreement");
	
}

/**
 * SaveCommecialAgreement()
 *
 * @param
 * @param
 */
function SaveCommecialAgreement($o)
{

	$account_db = new accountDB();
	$account_db->SetAccountId($o['account_id']);
	
	$fdata = GetFileData('account_file');

	if ($fdata)
	{		
		$account_db->SetAccountFile($fdata['type_id'], $fdata['name'], $o['account_file_title'], $fdata['data'], $fdata['size'], ACCOUNT_COMMENT_FILE_TYPE_COMMERCIAL_TERMS);		
	}
	
	if( $o['agreement_text'] ){
		if($fdata)
		{
				$account_db->SetAccountComment($o['account_id'], ACCOUNT_COMMENT_TYPE_COMMERCIAL_TERMS, $o['agreement_text'].'<br><br> File Attached :'.$o['account_file_title'].'(<a href="?action=get_account_file&account_id='.$o['account_id'].'&account_file_id=' . $account_db->lastID .'"> '.$fdata['name'].' </a>)');		
		}
		else
		{
				$account_db->SetAccountComment($o['account_id'], ACCOUNT_COMMENT_TYPE_COMMERCIAL_TERMS, $o['agreement_text']);
		}	
	}
	
	header("Location: ?action=display_account_detail&account_id=". $o['account_id'] ."#commercial_agreement");	
}

/**
 * DeleteAccountFile()
 *
 * @param 
 * @param
 */
function DeleteAccountFile($o)
{
	$account_db = new accountDB();
	$account_db->SetAccountId($o['account_id']);
		
	$account_db->DeleteAccountFile($o['account_file_id']);
	$account_db->DeleteAccountFileFromComment($o['account_file_id']);
	
	if ($account_db->isAccountType(ACCOUNT_TYPE_RECRUITER)){
		header("Location:/app/Account/DisplayRecruiterAccountDetails/account_id/". $o['account_id']);
		return false;	
	}
	header("Location: ?action=display_account_detail&account_id=". $o['account_id'] );	
}

/**
* GetAccountFile()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu May 18 11:36:07 PDT 2006
*/
function GetAccountFile($o)
{
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);

	$data = $a->GetAccountFileByAccountFileId($o['account_file_id']);
	header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); // required for certain browsers 
   header('Content-Type: application/octet-stream');
   header('Content-Disposition: attachment; filename="'. $data['account_file_name'] .'"');
   echo $data['account_file_data'];
   exit();
}

/**
* DisplayAccountPanel()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jun 19 10:50:36 PDT 2006
*/
function DisplayAccountPanel($o)
{
	global $smarty;
	
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	
	$c = new commonDB();
	$u = new userDB();
	
	$list['country'] = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
	
	$account = $a->GetAccountDetail();
	
	$account['panel_country'] = PrepareSmartyArray($a->GetAccountPanelCountry());
	
	$meta['db_records'] = $a->rows;
	$meta['total_rows'] = $meta['db_records'] + SHOW_BLANK_RECORDS_LIMIT;
	
	$smarty->assign('account', $account);
	$smarty->assign('list', $list);
	$smarty->assign('meta', $meta);
	
	DisplayHeader("Account Manager", "acm");
	
	$smarty->display('app/acm/vw_panel_country.tpl');
	
	DisplayFooter();
	
}

/**
* SaveAccountPanelCountry()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jun 19 13:41:44 PDT 2006
*/
function SaveAccountPanelCountry($o)
{
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	
	$c = new commonDB();
	$u = new userDB();
	
	
	for ($i=0; $i < 200; $i++) {
		if ($o['country_code_'. $i] != '') {
			if (isset($o['account_panel_country_id_'. $i]) && is_numeric($o['account_panel_country_id_'. $i])) {
				$a->UpdateAccountPanelCountry($o['account_panel_country_id_'. $i], $o['country_code_'. $i], $o['panel_count_'. $i], $o['response_rate_'. $i]);
			} else {
				$a->SetAccountPanelCountry($o['country_code_'. $i], $o['panel_count_'. $i], $o['response_rate_'. $i]);	
			}			
		}
	}
	
	header("Location: ?action=display_account_detail&account_id=". $o['account_id']);	
	
}

/**
* DisplayAddAccountPanelCountryType()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jun 19 14:57:31 PDT 2006
*/
function DisplayAddAccountPanelCountryType($o)
{
	global $smarty;
	
	$c = new commonDB();
	
	$list['country'] = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
	$list['sample_type'] = CreateSmartyArray($c->GetSampleTypes(), 'sample_type_id', 'sample_type_description');
	
	$smarty->assign('list', $list);
	
	DisplayHeader("Accounts", "acm");
	$smarty->display('app/acm/vw_panel_table_type.tpl');
	DisplayFooter();
}

/**
* SaveAccountPanelTableType()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jun 19 15:16:07 PDT 2006
*/
function SaveAccountPanelTableType($o)
{
	$a = new accountDB();
	
	$country_spec_flag = 0;
	
	if (isset($o['is_attr_country_specific'])) {
			$country_spec_flag = 1;	
	}
	
	if ($o['account_panel_country_type_id'] != 0) { /* update record */
		
		$a->UpdateAccountPanelCountryType($o['account_panel_country_type_id'], $o['account_panel_country_type_description'], $o['sample_type_id'], $country_spec_flag);
		
		if (isset($o['limited_countries'])) {
			foreach ($o['country_code'] as $country_code) {
				$a->SetAccountPanelCountryTypeCountry($account_panel_country_type_id, $country_code);
			}
		}	
		
		
	} else {
		
		$a->SetAccountPanelCountryType($o['account_panel_country_type_description'], $o['sample_type_id'], $country_spec_flag);
	
		$account_panel_country_type_id = $a->last_id;
	
		if (isset($o['limited_countries'])) {
			foreach ($o['country_code'] as $country_code) {
				$a->SetAccountPanelCountryTypeCountry($account_panel_country_type_id, $country_code);
			}
		}	
	}
	
	header("Location: ?action=display_add_account_panel_table_type_attr&account_panel_country_type_id=". $account_panel_country_type_id ."&attr_count=". $o['number_of_attributes']);
	
	return true;
	
	
	
}

/**
* DisplayAddAccountPanelTableTypeAttr()
*
* @param
* @param 
* @return
* @since  - 11:50:24
*/
function DisplayAddAccountPanelTableTypeAttr($o)
{
	global $smarty;
	
	$a = new accountDB();
	
	$meta['number_of_attributes']          = $o['attr_count'];
	$meta['account_panel_country_type_id'] = $o['account_panel_country_type_id'];
	
	$meta = array_merge($meta, $a->GetAccountPanelCountryType($o['account_panel_country_type_id']));
	
	if ($meta['country_specific_attr_description'] == 1) {
		$list['panel_country'] = CreateSmartyArray($a->GetAccountPanelCountryTypeCountryList($o['account_panel_country_type_id']), 'country_code', 'country_description');
	}
	
	$smarty->assign('list', $list);
	$smarty->assign('meta', $meta);
	
	DisplayHeader("Accounts", "acm");
	
	$smarty->display('app/acm/vw_panel_table_type_attr.tpl');
	
	DisplayFooter();
	
}

/**
* SaveAccountPanelTableTypeAttr()
*
* @param
* @param 
* @return
* @since  - 11:50:39
*/
function SaveAccountPanelTableTypeAttr($o)
{
	$a = new accountDB();
	
	$meta = $a->GetAccountPanelCountryType($o['account_panel_country_type_id']);
	
	if ($meta['country_specific_attr_description'] == 1) {
		
		$rs = $a->GetAccountPanelCountryTypeCountryList($o['account_panel_country_type_id']);
		
		while ($r = $a->FetchAssoc($rs)) {
			
			for ($i=1; $i <= $o['number_of_attributes']; $i++) {
				$a->SetAccountPanelCountryAttrDef(
					$o['account_panel_country_type_id'],
					$r['country_code'], 
					$o['attr_name_' . $r['country_code'] . '_' . $i], 
					$o['attr_description_' . $r['country_code'] . '_' . $i]);	
			}		
			
		}
		
	} else {
		
		for ($i=1; $i <= $o['number_of_attributes']; $i++) {
				$a->SetAccountPanelCountryAttrDef(
					$o['account_panel_country_type_id'],
					'NNN', 
					$o['attr_name_' . $i], 
					$o['attr_description_' . $i]);	
		}		
		
	}

	
	header("Location: ?action=display_standard_table_manager");
	
}

/**
* DisplayAccountPanelCountryType()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jun 19 15:31:56 PDT 2006
*/
function DisplayAccountPanelCountryType($o)
{
	global $smarty;
	
	$c = new commonDB();
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	
	$meta = array();
	$account = $a->GetAccountDetail();
	
	$meta = $a->GetAccountPanelCountryType($o['account_panel_country_type_id']);
	
	if ($meta['country_specific_attr_description'] == 1) {
		
		if (isset($o['country_code'])) {
			$meta['attr_count'] = $a->GetAttrCountByPanelCountryTypeCountryCode($o['account_panel_country_type_id'], $o['country_code']);		
		} else {
			$meta['attr_count'] = $a->GetAttrCountByPanelCountryType($o['account_panel_country_type_id']);		
		}
		
		//$meta['attr_count'] = 2; //$a->GetAttrCountByPanelCountryType($o['account_panel_country_type_id']);	
		
	} else {
		$meta['attr_count'] = $a->GetAttrCountByPanelCountryType($o['account_panel_country_type_id']);	
	}
	
	$template = 'app/acm/vw_account_panel_country_type_multi.tpl';
	
	if ($meta['attr_count'] == 1) {
		$template = 'app/acm/vw_account_panel_country_type.tpl';
	}
	
	$meta['readonly'] = true;
	
	if ($o['mode'] == 'edit') {
		$meta['readonly'] = false;
	}
	
	if (isset($o['country_code']) && $o['country_code'] != '') {
		$rs = $a->GetAccountPanelCountryAttrByPanelCountryTypeAndCountryCode($o['account_panel_country_type_id'], $o['country_code']); 
	} else {
		$rs = $a->GetAccountPanelCountryAttrByPanelCountryType($o['account_panel_country_type_id']);	
	}
	
	while ($r = $a->FetchAssoc($rs)) {
		
		if ($meta['attr_count'] == 1) {
			$panel[$r['country_code']][$r['account_panel_group_id']][$r['account_panel_item_type_id']] = $r;				
		} else {
			$panel[$r['country_code']][$r['account_panel_group_id']][$r['account_panel_country_attr_name']][$r['account_panel_item_type_id']] = $r;	
		}
		
	}
	
	$account['panel'] = $panel;
	
	$smarty->assign('account', $account);
	
	/* we need to see if there are records in the panel_country_type table and only display those we if we */	
	$list['country']  = CreateSmartyArray($a->GetAccountPanelCountryTypeCountryList($o['account_panel_country_type_id']), 'country_code', 'country_description');
	
	if (empty($list['country'])) {
		$list['country']         = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
	}
		
	$list['panel_group']     = CreateSmartyArray($a->GetAccountPanelGroups($meta['sample_type_id']), 'account_panel_group_id', 'account_panel_group_description');
	
	/* add a country layer requires special array preperation */
	$rs = $a->GetAccountPanelAttrDefByType($o['account_panel_country_type_id']);
	
	while ($r = $a->FetchAssoc($rs)) {
		if ($meta['country_specific_attr_description'] == 1) {
			$list['panel_attr'][$r['country_code']][$r['account_panel_country_attr_name']] = $r['account_panel_country_attr_description'];
		} else {
			$list['panel_attr'][$r['account_panel_country_attr_name']] = $r['account_panel_country_attr_description'];
		}
	}
	
	$list['panel_item_type'] = CreateSmartyArray($a->GetAccountPanelItemType($meta['sample_type_id']), 'account_panel_item_type_id', 'account_panel_item_type_description');
	
	if ($o['mode'] == 'edit') {
		$meta['grid_size'] = count($list['panel_group']) * count($list['panel_attr'][$o['country_code']]) * count($list['panel_item_type']);
	}
	
	$smarty->assign('list', $list);
	
	
	
	if (isset($_SESSION['acm_message'])) {
		$meta['message'] = $_SESSION['acm_message'];
		unset($_SESSION['acm_message']);
	}
	
	if (isset($_SESSION['acm_errors'])) {
		$meta['errors'] = $_SESSION['acm_errors'];
		unset($_SESSION['acm_errors']);
	}
	
	$smarty->assign('meta', $meta);
	
	$smarty->assign('custom_sub_header', $smarty->fetch('app/acm/hd_panel_country.tpl'));
	
	DisplayHeader("Accounts", "acm");
	//echo $template;
	$smarty->display($template);
	DisplayFooter();
	
}

/**
* SaveAccountPanelCountryAttr()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jun 28 07:49:41 PDT 2006
*/
function SaveAccountPanelCountryAttr($o)
{
	global $encryption;
	
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);

	/* check if the country we are adding already exists */
	if (!$a->isAccountPanelCountryAttrSet($o['account_panel_country_type_id'], $o['country_code'])) {
		SetEmptyPanelCountryAttrGrid($o['account_id'], $o['account_panel_country_type_id'], $o['country_code']);
	} else {
		$_SESSION['acm_message'] = "Country Already Exisists in Pricing Grid!";
	}

	
	header("Location: ?e=" . $encryption->Encrypt("action=display_account_panel_country_type&account_panel_country_type_id=" . $o['account_panel_country_type_id'] 
		. "&account_id=" . $o['account_id'] . "&country_code=" . $o['country_code'] . "&mode=edit")
	);
	
}

/**
* SetEmptyPanelCountryAttrGrid()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jun 28 17:17:47 PDT 2006
*/
function SetEmptyPanelCountryAttrGrid($account_id, $account_panel_country_type_id, $country_code)
{
	$a = new accountDB();
	$a->SetAccountId($account_id);
	
	$meta = $a->GetAccountPanelCountryType($account_panel_country_type_id);
	
	$rs_group = $a->GetAccountPanelGroups();
	
	/* the country code should be used for tables that have 2nd level attribute */
	if ($a->GetAttrCountByPanelCountryType($account_panel_country_type_id) == 1 || $meta['country_specific_attr_description'] == 0) {
		$rs_def = $a->GetAccountPanelAttrDefByType($account_panel_country_type_id);
	} else {
		$rs_def = $a->GetAccountPanelAttrDefByTypeCountryCode($account_panel_country_type_id, $country_code);	
	}
	
	$rs_item  = $a->GetAccountPanelItemType();

	while ($r_group = $a->FetchAssoc($rs_group)) {
		
		while ($r_def = $a->FetchAssoc($rs_def)) {			
			
			while ($r_item = $a->FetchAssoc($rs_item)) {
				
				/* only if it is not set or not active */
				if (! $a->isAccountPanelCountryGroupItemAttrSet($account_panel_country_type_id, 
																				$country_code, 
																				$r_group['account_panel_group_id'], 
																				$r_def['account_panel_country_attr_name'], 
																				$r_item['account_panel_item_type_id'])) {
																					
					$a->SetAccountPanelCountryAttr($account_panel_country_type_id, 
															 $r_group['account_panel_group_id'], 
															 $r_def['account_panel_country_attr_name'], 
															 $country_code, 
															 $r_item['account_panel_item_type_id'], 0, 0);
				}
			}
			mysql_data_seek($rs_item, 0);
		}
		mysql_data_seek($rs_def, 0);
	}
		
}

/**
* SetEmptyPanelCountryAttrPriceGrid()
*
* @param
* @param 
* @return
* @since  - 17:02:15
*/
function SetEmptyPanelCountryAttrPriceGrid($account_id, $account_panel_country_type_id, $country_code)
{
	$list['length_of_interview'] = array(5, 15, 25, 35, 45);
	$list['incidence']           = array(5, 20, 50, 75, 95);
	
	$a = new accountDB();
	$a->SetAccountId($account_id);
	
	$rs_group = $a->GetAccountPanelGroups();
	
	$rs = $a->GetAccountPanelCountryAttrByPanelCountryTypeAndCountryCode($account_panel_country_type_id, $country_code); 
	
	while ($r = $a->FetchAssoc($rs)) {
		
		if ($a->isAccountPanelCountryCostSet($r['account_panel_country_attr_id'])) {
			continue;
		}
		
		/* loop through the available incidence range and LOI to set default price to 0 */
		foreach ($list['length_of_interview'] as $survey_length) {
			foreach ($list['incidence'] as $incidence_rate) {
				$a->SetAccountPanelCountryCost($r['account_panel_country_attr_id'], $survey_length, $incidence_rate, 0);
			}
		}
		
	}
	
}

/**
* UpdateAccountPanelCountryAttr()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jun 28 09:55:32 PDT 2006
*/
function UpdateAccountPanelCountryAttr($o)
{
	global $encryption;
	
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	
	$errors = array(); /* array to hold errors */
	
	if (!$a->isAccountPanelCountryAttrSet($o['account_panel_country_type_id'], $o['country_code'])) {
		$_SESSION['acm_message'] = 'System Error Country Not Defined in Attr REF'. $o['account_panel_country_type_id'] . ".". $o['country_code'];
		header("Location: ?e=". $encryption->Encrypt("action=display_account_panel_country_type&account_panel_country_type_id=". $o['account_panel_country_type_id'] ."&account_id=". $o['account_id']));		
		return true;
	}
	
	$count = 0;
	$response_rate = 0;
	
	$rs = $a->GetAccountPanelCountryAttrByPanelCountryTypeAndCountryCode($o['account_panel_country_type_id'], $o['country_code']); 
	
	if ($o['grid_size'] != $a->rows) { /* we have a problem, we have some missing blank values */
		SetEmptyPanelCountryAttrGrid($o['account_id'], $o['account_panel_country_type_id'], $o['country_code']);
		
		$rs = $a->GetAccountPanelCountryAttrByPanelCountryTypeAndCountryCode($o['account_panel_country_type_id'], $o['country_code']); 
	}
	
	while ($r = $a->FetchAssoc($rs)) {
		
		if (isset($o['account_panel_country_attr_count_' . $r['account_panel_country_attr_id']])) {
			$key          = $r['account_panel_country_attr_id'];
		} else {
			$key          = $r['country_code'] . "_" . $r['account_panel_group_id'] . "_" . $r['account_panel_country_attr_name'] . "_" . $r['account_panel_item_type_id'];
		}
		
		$key_count    = 'account_panel_country_attr_count_' . $key;
		$key_response = 'account_panel_country_attr_response_rate_' . $key;
			             
		$count         = 	preg_replace("/[^0-9\.]/", "", $o[$key_count]);
		$response_rate = 	preg_replace("/[^0-9\.]/", "", $o[$key_response]);
		
		if ($response_rate > 100) {
			
			$errors['record_' . $r['account_panel_country_attr_id']]['response_rate'] = array(
				'message' => "Response Rate is Greator Than 100%",
				'value'   => $response_rate);
				
			$errors['record_' . $r['account_panel_country_attr_id']]['count'] = array(
				'message' => "Response Rate is Greator Than 100%",
				'value'   => $count);
				
			continue;		
		} 
			
		if (is_numeric($response_rate) && is_numeric($count)) {
			$a->UpdateAccountPanelCountryAttr($r['account_panel_country_attr_id'], $count, $response_rate);	
		}
			
		//$a->SetAccountPanelCountryAttr()
		
		//account_panel_country_attr_count_
		//account_panel_country_attr_response_rate_
	}
	
	if (count($errors) > 0) {
		$_SESSION['acm_errors'] = $errors;
		header("Location: ?e=" . $encryption->Encrypt("action=display_account_panel_country_type&account_panel_country_type_id=" 
			. $o['account_panel_country_type_id'] . "&account_id=" . $o['account_id'] . "&country_code=" . $o['country_code'] ."&mode=edit"));		
		return true;
	}
	
	header("Location: ?e=". $encryption->Encrypt("action=display_account_panel_country_type&account_panel_country_type_id=". $o['account_panel_country_type_id'] ."&account_id=". $o['account_id']));		
	
}

/**
* DisplaySetAccountPanelCostAttr()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jun 19 19:53:25 PDT 2006
*/
function DisplaySetAccountPanelCostAttr($o)
{
	global $smarty;
	
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	
	$account = $a->GetAccountDetail();
	
	$account['panel_cost'] = PrepareSmartyArray($a->GetAccountPanelCountryAttrByCountryGroup($o['country_code'], $o['account_panel_group_id'], $o['account_panel_country_type_id']));
	
	$smarty->assign('account', $account);
	$smarty->assign('meta', $o);
	
	DisplayHeader("Accounts", "acm");
	$smarty->display('app/acm/vw_account_panel_country_cost_attr.tpl');
	DisplayFooter();
}

/**
* UpdateAccountPanelCountryCost()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jun 19 20:59:24 PDT 2006
*/
function UpdateAccountPanelCountryCost($o)
{
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	
	$rs = $a->GetAccountPanelCountryCostByPanelCountryTypeCountryCode($o['account_panel_country_type_id'], $o['country_code']);	
	
	while ($r = $a->FetchAssoc($rs)) {
		$a->UpdateAccountPanelCountryCost($r['account_panel_country_cost_id'], $o['account_panel_country_cost_' . $r['account_panel_country_cost_id']]);
	}
	
	header("Location: ?action=display_account_panel_country_cost&account_panel_country_type_id=". $o['account_panel_country_type_id'] ."&account_id=". $o['account_id'] ."&country_code=". $o['country_code']);
	
}

/**
* DisplayAccountPanelCountryCost()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jun 19 21:18:35 PDT 2006
*/
function DisplayAccountPanelCountryCost($o)
{
	global $smarty;
	
	$c = new commonDB();
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	
	$account = $a->GetAccountDetail();
	
	$meta = $a->GetAccountPanelCountryType($o['account_panel_country_type_id']);
	
	if ($meta['country_specific_attr_description'] == 1) {
		$meta['attr_count'] = $a->GetAttrCountByPanelCountryTypeCountryCode($o['account_panel_country_type_id'], $o['country_code']);	
	} else {
		$meta['attr_count'] = $a->GetAttrCountByPanelCountryType($o['account_panel_country_type_id']);	
	}
	
	//$attr_count = $a->GetAttrCountByPanelCountryType($o['account_panel_country_type_id']);
	
	/* check to see if the requested view has data if not create an empty view then redirect to the logic below here */
	if (!$a->isAccountPanelCountryCostSetByPanelCountryTypeCountryCode($o['account_panel_country_type_id'], $o['country_code'])) {
		SetEmptyPanelCountryAttrPriceGrid($o['account_id'], $o['account_panel_country_type_id'], $o['country_code']);
	}
	
	$template = 'app/acm/vw_account_panel_country_cost_multi.tpl';
	
//	if ($meta['attr_count'] == 1) {
//		$template = 'app/acm/vw_account_panel_country_cost.tpl';
//	}
	
	$meta['readonly'] = true;
	
	if ($o['mode'] == 'edit') {
		$meta['readonly'] = false;
	}
	
	//$rs = $a->GetAccountPanelCountryCostByPanelCountryTypeCountryCode($o['account_panel_country_type_id'], $o['country_code']);	
	
	if (isset($o['country_code']) && $o['country_code'] != '') {
		$rs = $a->GetAccountPanelCountryCostByPanelCountryTypeCountryCode($o['account_panel_country_type_id'], $o['country_code']);	
	} else {
		$rs = $a->GetAccountPanelCountryCostByPanelCountryType($o['account_panel_country_type_id']);	
	}
	
	while ($r = $a->FetchAssoc($rs)) {
		if ($attr_count == 1) {
			$panel[$r['country_code']][$r['account_panel_group_id']][$r['account_panel_item_type_id']][$r['survey_length']][$r['incidence_rate']] = $r;
		} else {
			$panel[$r['country_code']][$r['account_panel_group_id']][$r['account_panel_country_attr_name']][$r['account_panel_item_type_id']][$r['survey_length']][$r['incidence_rate']] = $r;	
		}
	}
	
	$account['panel'] = $panel;
	
	$smarty->assign('account', $account);
	
	/* we need to see if there are records in the panel_country_type table and only display those we if we */	
	$list['country']  = CreateSmartyArray($a->GetAccountPanelCountryTypeCountryList($o['account_panel_country_type_id']), 'country_code', 'country_description');
	
	if (empty($list['country'])) {
		$list['country']         = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
	}
	
	$list['panel_group'] = CreateSmartyArray($a->GetAccountPanelGroups(), 'account_panel_group_id', 'account_panel_group_description');
	
	/* add a country layer requires special array preperation */
	$rs = $a->GetAccountPanelAttrDefByType($o['account_panel_country_type_id']);
	
	while ($r = $a->FetchAssoc($rs)) {
		if ($meta['country_specific_attr_description'] == 1) {
			$list['panel_attr'][$r['country_code']][$r['account_panel_country_attr_name']] = $r['account_panel_country_attr_description'];
		} else {
			$list['panel_attr'][$r['account_panel_country_attr_name']] = $r['account_panel_country_attr_description'];
		}
	}
	
	$list['panel_item_type'] = CreateSmartyArray($a->GetAccountPanelItemType(), 'account_panel_item_type_id', 'account_panel_item_type_description');
	
	if ($o['mode'] == 'edit') {
		$meta['grid_size'] = count($list['panel_group']) * count($list['panel_attr'][$o['country_code']]) * count($list['panel_item_type']);
	}
	
	$list['length_of_interview'] = array(5, 15, 25, 35, 45);
	$list['incidence']           = array(5, 20, 50, 75, 95);
	
	$smarty->assign('list', $list);
	
	//$meta = $a->GetAccountPanelCountryType($o['account_panel_country_type_id']);
	//$meta['attr_count'] = $attr_count;
	$meta['readonly'] = true;
	
	if ($o['mode'] == 'edit') {
		$meta['readonly'] = false;	
	}
	
	if ($o['mode'] == 'edit') {
		$meta['grid_size'] = count($list['panel_group']) * count($list['panel_attr'][$o['country_code']]) * count($list['panel_item_type']);
	}
	
	$smarty->assign('custom_sub_header', $smarty->fetch('app/acm/hd_panel_country.tpl'));
	
	$smarty->assign('meta', $meta);
	
	DisplayHeader("Accounts", "acm");
	$smarty->display($template);
	DisplayFooter();
	
}

/**
* DisplayStandardTableManager()
*
* @param
* @param 
* @return
* @since  - 11:02:33
*/
function DisplayStandardTableManager($o)
{
	global $smarty;
	
	$a = new accountDB();
	$c = new commonDB();
	
	$rs = $a->GetAccountPanelCountryTypes();
	
	while ($r = $a->FetchAssoc($rs)) {
		$r['number_of_attributes'] = $a->GetAttrCountByPanelCountryType($r['account_panel_country_type_id']);
		$table[] = $r;
	}
	
	$list['table'] = $table;
	
	$list['sample_type'] = CreateSmartyArray($c->GetSampleTypes(), 'sample_type_id', 'sample_type_description');
	$list['user'] = CreateSmartyArray($c->GetUsers(), 'login', 'name');
	
	
	$list['group'] = PrepareSmartyArray($a->GetAccountPanelGroups());
	
	$list['item']  = PrepareSmartyArray($a->GetAccountPanelItemTypes());
	
	$smarty->assign('list', $list);
	
	DisplayHeader("Accounts", "acm");
	
	$smarty->display('app/acm/vw_standard_table_list.tpl');
	
	DisplayFooter();
	
}

/**
* DeleteAccountPanelTableType()
*
* @param
* @param 
* @return
* @since  - 19:13:43
*/
function DeleteAccountPanelTableType($o)
{
	$a = new accountDB();
	
	$a->UpdateAccountPanelCountryTypeStatus($o['account_panel_country_type_id'], 'D');
	
	header("Location: ?action=display_standard_table_manager");
	
}

/**
* DisplayEditAccountPanelType()
*
* @param
* @param 
* @return
* @since  - 20:40:37
*/
function DisplayEditAccountPanelType($o)
{
	global $smarty;
	
	$a = new accountDB();
	$c = new commonDB();
	
	$list['table']       = $a->GetAccountPanelCountryType($o['account_panel_country_type_id']);
	$list['sample_type'] = CreateSmartyArray($c->GetSampleTypes(), 'sample_type_id', 'sample_type_description');
	$list['country']     = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
	
	$list['table']['number_of_attr'] = $a->GetAttrCountByPanelCountryType($o['account_panel_country_type_id']);
	$list['table']['limited_country'] = 0;
	$list['table']['country_list'] = CreateSmartyArray($a->GetAccountPanelCountryTypeCountryList($o['account_panel_country_type_id']), 'country_code', 'country_code');
	
	if (!empty($list['table']['country_list'])) {
		$list['table']['limited_country'] = 1;
	}
	
	$smarty->assign('list', $list);
	
	DisplayHeader("Accounts", "acm");
	
	$smarty->display('app/acm/vw_panel_table_type.tpl');
	
	DisplayFooter();
	
}

/**
* DisplayEditAccountPanelGroup()
*
* @param
* @param 
* @return
* @since  - 11:04:58
*/
function DisplayEditAccountPanelGroup($o)
{
	global $smarty;
	
	$a = new accountDB();
	$c = new commonDB();
	
	DisplayHeader("Accounts", "acm");
	
	$list['group'] = $a->GetAccountPanelGroup($o['account_panel_group_id']);
	$list['sample_type'] = CreateSmartyArray($c->GetSampleTypes(), 'sample_type_id', 'sample_type_description');
	$list['user'] = CreateSmartyArray($c->GetUsers(), 'login', 'name');
	
	$smarty->assign('list', $list);
	
	$smarty->display('app/acm/vw_account_panel_group.tpl');
	
	DisplayFooter();
}

/**
* SaveAccountPanelGroup()
*
* @param
* @param 
* @return
* @since  - 11:21:54
*/
function SaveAccountPanelGroup($o)
{
	$a = new accountDB();
	
	if ($o['account_panel_group_id'] != 0) {
		
		$a->UpdateAccountPanelGroup($o['account_panel_group_id'], $o['account_panel_group_description'], $o['sample_type_id']);
		
	} else {
		
		$a->SetAccountPanelGroup($o['account_panel_group_description'], $o['sample_type_id']);
		
	}
	
	header("Location: ?action=display_standard_table_manager");
	
}

/**
* DisplayAddAccountPanelGroup()
*
* @param
* @param 
* @return
* @since  - 11:40:36
*/
function DisplayAddAccountPanelGroup($o)
{
	global $smarty;
	
	$a = new accountDB();
	$c = new commonDB();
	
	DisplayHeader("Accounts", "acm");
	
	$list['sample_type'] = CreateSmartyArray($c->GetSampleTypes(), 'sample_type_id', 'sample_type_description');
	$list['user'] = CreateSmartyArray($c->GetUsers(), 'login', 'name');
	
	$smarty->assign('list', $list);
	
	$smarty->display('app/acm/vw_account_panel_group.tpl');
	
	DisplayFooter();
	
}

/**
* DisplayEditAccountPanelItemType()
*
* @param
* @param 
* @return
* @since  - 12:02:58
*/
function DisplayEditAccountPanelItemType($o)
{
	global $smarty;
	
	$a = new accountDB();
	$c = new commonDB();
	
	$list['item'] = $a->GetAccountPanelItemTypeById($o['account_panel_item_type_id']);
	$list['sample_type'] = CreateSmartyArray($c->GetSampleTypes(), 'sample_type_id', 'sample_type_description');

	$smarty->assign('list', $list);
	
	DisplayHeader("Accounts", "acm");
	
	$smarty->display('app/acm/vw_account_panel_item_type.tpl');
	
	DisplayFooter();
	
}

/**
* SaveAccountPanelItemType()
*
* @param
* @param 
* @return
* @since  - 12:04:27
*/
function SaveAccountPanelItemType($o)
{
	$a = new accountDB();
	
	if ($o['account_panel_item_type_id'] != 0) {
		
		$a->UpdateAccountPanelItemType($o['account_panel_item_type_id'], $o['account_panel_item_type_description'], $o['sample_type_id']);
		
	} else {
		
		$a->SetAccountPanelItemType($o['account_panel_item_type_description'], $o['sample_type_id']);
		
	}
	
	header("Location: ?action=display_standard_table_manager");
	
}

/**
* DeleteAccountPanelGroup()
*
* @param
* @param 
* @return
* @since  - 12:14:53
*/
function DeleteAccountPanelGroup($o)
{
	$a = new accountDB();
	
	$a->UpdateAccountPanelGroupStatus($o['account_panel_group_id'], 'D');
	
	header("Location: ?action=display_standard_table_manager");
	
}

/**
* DisplayAddAccountPanelItemType()
*
* @param
* @param 
* @return
* @since  - 12:19:34
*/
function DisplayAddAccountPanelItemType($o)
{
	global $smarty;
	
	$a = new accountDB();
	$c = new commonDB();
	
	$list['sample_type'] = CreateSmartyArray($c->GetSampleTypes(), 'sample_type_id', 'sample_type_description');

	$smarty->assign('list', $list);
	
	DisplayHeader("Accounts", "acm");
	
	$smarty->display('app/acm/vw_account_panel_item_type.tpl');
	
	DisplayFooter();
	
}

/**
* HBRPC_ACM_GetVendorsByCountry()
*
* @param
* @param 
* @return
* @since  - 10:49:52
*/
function HBRPC_ACM_GetVendorsByCountry($o)
{
	
	return GetVendorsByCountry($o['country_code'], $o['comples'], $o['incidence_rate']);
}

/**
* GetVendorsByCountry()
*
* @param
* @param 
* @return
* @since  - 10:50:25
*/
function GetVendorsByCountry($country_code, $completes, $incidence_rate)
{
	$a = new accountDB();
	
	$vendors = PrepareSmartyArray($a->GetAccountPanelCountryByCountryCode($country_code));
	
	return array(
		'error_code' => 0,
		'error_description' => 'Sucess',
		'vendors' => $vendors
	);
	
}

/**
* HBRPC_ACM_GetProductSecurityToken()
*
* @param 
* @param  
* @throws 
* @return
* @access
* @since  - Fri May 11 16:47:20 LKT 2007
*/
function HBRPC_ACM_GetProductSecurityToken($o)
{
	if(isset($o['account_id']) && isset($o['product_id'])) {
		$product_object = new acm_ProductManager();
		try {
			$data = $product_object-> GetProductSecurityToken($o['account_id'], $o['product_id']);
		} catch (Exception $e) {
			return array( 'error_code' => $e->getCode(), 'error_message' =>
				$e->getMessage() . " == ". $e->getFile() ." == ". $e->getLine() . " == ". $e->getTraceAsString() );
		}

		return array('error_code' => 0, 'error_description' => 'Sucess', 'data' => $data);      
	} 
	
	
	 	
}


/**
* HBRPC_ACM_GetAccountHierarchy()
*
* @param 
* @param  
* @throws 
* @return
* @access
* @since  - Fri May 11 16:52:02 LKT 2007
*/
function HBRPC_ACM_GetAccountHierarchy($o)
{
	if(isset($o['account_id'])) {         
		$hierarchy_manager = new acm_HierarchyManager();  
		                   
		try {           
			$data = $hierarchy_manager->GetAccountHierarchy($o['account_id']);        
		} catch (Exception $e) {            
			return array( 'error_code' => $e->getCode(), 'error_message' =>                 
				$e->getMessage() . " == ". $e->getFile() ." == ". $e->getLine() . " == ". $e->getTraceAsString() );         
		}               
		
		return array('error_code' => 0, 'error_description' => 'Sucess', 'data' => $data);      	
	}	
}

/**
 * Display the Progress Message
 *
 * @param
 */ 
function DisplayProgressMessage()
{
	global $smarty, $o;
	
	if (isset($o['msg']) && $o['msg'] == ACCOUNT_MSG_TYPE_PROGRESS_MSG)
	{
		$msg = "Updating Credit Hold";
	}

	$smarty->assign('msg', $msg);
	
	$smarty->display('app/acm/vw_account_msg.tpl');
}


/**
 * Display Recruiter Campaign Detail
 *
 * @param array $o
 */ 
function DisplayRecruiterCampaignDetail($o) 
{ 
	global $smarty;
	
	$a = new accountDB();
	$a->SetAccountId($o['account_id']);
	$account = $a->GetAccountDetail();
		
	$account['account_type']     = PrepareSmartyArray($a->GetAccountType());
	$account['account_sub_type'] = PrepareSmartyArray($a->GetAccountSubType());
	
	$acc_obj = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($o['account_id']);
	$account_term = $acc_obj->GetAccountTerm();
	DisplayHeader("Accounts", "acm");
	
	
	$smarty->assign('account', $account);
	$smarty->assign('account_term', $account_term);
	$smarty->assign('display', $display);
	$smarty->display('app/acm/recruiter/vw_campaign_detail.tpl');
	DisplayFooter();
}

/**
 * Display Recruiter Detail
 *
 * @param array $o
 */ 
function DisplayRecruiterDetails()
{
 global $smarty ;
  
 DisplayHeader("Recruiter Manager", "acm");
 //$smarty->display('app/acm/recruiter/vw_campaign_detail.tpl');
 $smarty->display('app/acm/recruiter/vw_recruiter_campaign_details.tpl');
 $smarty->display('app/acm/recruiter/vw_recruiter_pricing.tpl');
 $smarty->display('app/acm/recruiter/vw_recruiter_quality_matrics.tpl');
 
 DisplayFooter();
 
}

/**
* ClosePopup()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function ClosePopup1($smarty)
{
	$smarty->display('close_popup.tpl');
	return true;
}
?>
