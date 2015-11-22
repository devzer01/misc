<?php 
class app_HierarchyManager
{
	/**
	 * Designated DB Layer Class Object 
	 * @var db_acm_HierarchyManager
	 */
	private $db;
	
	private $parent_account_id;

	/**
	* __construct()
	*
	* @param 
	* @param  
	* @throws 
	* @return
	* @access
	* @since  - Wed May 09 14:43:24 LKT 2007
	*/
	function __construct($account_id)
	{
	 	$this->db	= new db_acm_HierarchyManager();
	 	$this->parent_account_id	= $account_id;	 
	}
	
	/**
	* DisplayRelatedAccount()
	*
	* @param 
	* @param  
	* @throws 
	* @return
	* @access
	* @since  - Wed May 09 14:50:07 LKT 2007
	*/
	function DisplayRelatedAccount()
	{				
	 	if($this->IsUserSecuritySetToAH()) {
	 		return true;
	 	}	 	
	 	return false;
	}
	
	/**
	* IsUserSecuritySetToAH()
	*
	* @param 
	* @param  
	* @throws 
	* @return
	* @access
	* @since  - Wed May 09 15:30:36 LKT 2007
	*/
	private function IsUserSecuritySetToAH()
	{		
		//check for MyGMI user is administrator type. 
		if(isset($_SESSION['user_portal_access_type_id']) && $_SESSION['user_type_id'] == USER_TYPE_EXTERNAL 
			&& $_SESSION['user_portal_access_type_id'] == PORTAL_ACCESS_TYPE_ADMINSTRATOR) {				
			return true;
		}
		
		//check for HB administrators
		if (isset($_SESSION['ACM_SET_ANY_ACCOUNT_HIERARCHY'])) {
			return true;
		}
		
		//check whether account id is set to the login user
		//accountdb function here 
		if($this->IsAccountSetToUser()) {			
		 	//check security key for users 
		 	if (isset($_SESSION['ACM_SET_MY_ACCOUNT_HIERARCHY'])) {		 		
		 		return true;
		 	}	 	
		}
	 
	 	return false;
	}		

	/**
	* IsAccountSet()
	*
	* @param 
	* @param  
	* @throws 
	* @return
	* @access
	* @since  - Wed May 09 16:21:15 LKT 2007
	*/
	private function IsAccountSetToUser()
	{
		$accountdb	= new accountDB();
		$accountdb->SetAccountId($this->parent_account_id);
		
	 	if ($accountdb->IsAccountSetToUser($_SESSION['user_id'])){	
	 		return true;
	 	}	 		 	
	 	return false;
	}
	
	/**
	* GetAccountHierarchy()
	*
	* @param 
	* @param  
	* @throws 
	* @return
	* @access
	* @since  - Wed May 09 17:10:03 LKT 2007
	*/
	function GetAccountHierarchy($parent_id = 0)
	{
		//this logic is come because of the delete process. have to implemnt an onther logic for this.
		if($parent_id == 0) 
			$parent_account_id 	= $this->parent_account_id;
		else 
			$parent_account_id	= $parent_id;
		
		$accounts = $this->GetChildAccount($parent_account_id);
		
	 	return $accounts;
	}
	
	/**
	* GetChildAccount()
	*
	* @param 
	* @param  
	* @throws 
	* @return
	* @access
	* @since  - Fri May 11 12:17:30 LKT 2007
	*/
	private function GetChildAccount($parent_account_id, $depth = 1)
	{
	 	if ($depth >= 20) return false; 	 
	 	
	 	$child_accounts = $this->db->GetAccountChildren($parent_account_id);	 	
	 	$child_list		= array();
	 	
	 	//object to read configuration settings
	 	$config_obj = Hb_Util_Config_SystemConfigReader::Read();
	 	
	 	foreach ($child_accounts as $child) {	 		
	 		//get the NetMr token from the account_product_attr table
	 		$token = $this->GetAccountProductAttr($child['child_account_id'], PRODUCT_NETMR); 
	 		
	 		//get the NetMr link	 		
	 		$child['netmrlink']	= "";	 
	 				
	 		if($token != "") {	 		
	 			$child['netmrlink']	= $config_obj->netmr->webhost.$config_obj->netmr->url_direct_login.'?s='.$token;
	 		} 
	 		
	 		$child_list[]	= array(
	 			'depth' 		=> $depth ,	 			
	 			'account' 	=> $child
	 		);
	 		//check for account hierarchy type , stop loop if it's type is fictional
	 		if($child['account_hierarchy_type_id'] != HIERARCHY_TYPE_FICTIONAL) {
	 			
	 			$list = $this->GetChildAccount($child['child_account_id'], $depth + 1);
	 		
	 			if(false === $list) {
	 				return false;
	 			} else {
	 				$child_list	= array_merge($child_list, $list);
	 			}	 			
	 		}
	 	}	 	
	 	return $child_list;
	}
	
	/**
	* GetAccountProductAttr()
	*
	* @param 
	* @param  
	* @throws 
	* @return
	* @access
	* @since  - Thu May 17 09:52:08 IST 2007
	*/
	private function GetAccountProductAttr($account_id, $product_id)
	{
		$product_manager	= new acm_ProductManager();
		$token				=  $product_manager->GetProductSecurityToken($account_id, $product_id);		
		
		return $token;
	}
	
	
	/**
	* AddRelatedAccount()
	*
	* @param 
	* @param  
	* @throws 
	* @return
	* @access
	* @since  - Wed May 09 17:43:46 LKT 2007
	*/
	private function AddRelatedAccount($o)
	{
		
		if($this->IsAccountIdSet($o['account_id'], $o['account_hierarchy_type'])){
			$this->db->AddAccountHierarchy($o['account_hierarchy_type'], $this->parent_account_id, $o['account_id']);
			
			if($this->GetChildAccount($o['account_id']) === false) {
				//update the inserted account status to 'D', because it's making an infinite loop.
				$this->db->DeleteAccountHierarchy($this->parent_account_id, $o['account_id']);
			
				$_SESSION['error_code']	= 'infinite_loop';
				return false;
			}				
			return true;				
	 	}	 		 		 
	 		
	 	$_SESSION['error_code']	= 'duplication';
	 	return false;
	}	
	
	/**
	* IsAccountIdSet()
	*
	* @param 
	* @param  
	* @throws 
	* @return
	* @access
	* @since  - Thu May 10 08:58:00 LKT 2007
	*/
	private function IsAccountIdSet($account_id, $hierarchy_type)
	{
		//An account can not associate it self	 	
	 	//check for multiple association as child
	 	if($this->parent_account_id == $account_id || ($hierarchy_type != HIERARCHY_TYPE_FICTIONAL && $this->db->IsChildAccountSet($account_id))) {	 		
	 		return false;
	 	}
	 	return true;
	}
	
	/**
	* DeleteChildAccount()
	*
	* @param 
	* @param  
	* @throws 
	* @return
	* @access
	* @since  - Thu May 10 13:21:35 LKT 2007
	*/
	private function DeleteChildAccount($o)
	{
		$this->db->DeleteAccountHierarchy($o['parent_id'], $o['account_id']);
		
	}

	
	/**
	* GetAccountHierarchyTypes()
	*
	* @param 
	* @param  
	* @throws 
	* @return
	* @access
	* @since  - Thu May 10 14:24:56 LKT 2007
	*/
	function GetAccountHierarchyTypes()
	{
	 	return $this->db->GetAccountHierarchyTypes();
	}
	
	/**
	* IsAccountCustomerType()
	*
	* @param 
	* @param  
	* @throws 
	* @return
	* @access
	* @since  - Fri May 11 09:05:38 LKT 2007
	*/
	private function IsAccountCustomerType($account_id)
	{		
		$account_db	= new accountDB();
		$account_db->SetAccountId($account_id);

		if($account_db->isAccountType(ACCOUNT_TYPE_CUSTOMER)){
			return true;
		}		
		$_SESSION['error_code']	= 'customer_type';		
	 	return false;
	}	
	
	/**
	* AddRelatedChildAccount()
	*
	* @param 
	* @param  
	* @throws 
	* @return
	* @access
	* @since  - Wed May 09 17:37:47 LKT 2007
	*/
	function AddRelatedChildAccount($o)
	{		
		if($this->IsUserSecuritySetToAH()) {	
			
			if(!$this->IsParentChildAccountTypeCustomer($o) || !$this->AddRelatedAccount($o)) {
				//add the account
			}					
		} 
		
		header('location:?action=display_account_detail&account_id='.$o['parent_acc_id']);
		exit();
	}
	
	/**
	* IsParentChildAccountTypeCustomer()
	*
	* @param -
	* @param - 
	* @author - harsha
	* @since  - Mon May 14 16:28:05 LKT 2007
	*/
	private function IsParentChildAccountTypeCustomer($o)
	{
	 	if($this->IsAccountCustomerType($o['parent_acc_id']) && $this->IsAccountCustomerType($o['account_id'])) {			
	 		return true;	 	
	 	}	 	 
	 	
	 	return false;
	}

	
	/**
	* DeleteRelatedAccount()
	*
	* @param 
	* @param  
	* @throws 
	* @return
	* @access
	* @since  - Thu May 10 13:18:58 LKT 2007
	*/
	function DeleteRelatedAccount($o)
	{
	 	//check whether user is suitable to delete such a relationship
	 	if($this->IsUserSecuritySetToAH()) {	
	 		//check whether delete account has childs
//	 		if(!$this->GetAccountHierarchy($o['account_id'])) {
	 			$this->DeleteChildAccount($o);
//	 			$this->DeleteAccountProductAttr($o);
//	 		}else {
//	 			$_SESSION['error_code'] = 'delete_has_child';
//	 		}	 		
		}
	
		header('location:?action=display_account_detail&account_id='.$o['main_id']);
		exit();
	}
	
	
	/**
	* DeleteAccountProductAttr()
	*
	* @param 
	* @param  
	* @throws 
	* @return
	* @access
	* @todo  this function will be removed , not want at this time.
	* @since  - Thu May 17 16:01:57 IST 2007
	*/
//	private function DeleteAccountProductAttr($o)
//	{
//		$account_db	= new accountDB();
//		$account_db->SetAccountId($o['account_id']);
//		
//		$account_product	= $account_db->GetAccountProduct(PRODUCT_NETMR);
//		
//		$account_db->DeleteAccountProductAttr($account_product['account_product_id'], 'ACCOUNT_HIERARCHY_SECURITY_TOKEN');
//		
//	}

}
?>
