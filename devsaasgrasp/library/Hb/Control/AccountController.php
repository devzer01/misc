<?php
/**
 *  @version  2.0.alpha
 *  @author nayana
 */

require_once 'Zend/Controller/Action.php';

class AccountController extends Zend_Controller_Action
{
	
	public function indexAction()
	{
		echo "XXXX";
	}
	/**
	 * Save Audit log notes 
	 *
	 */
	public function SaveAccountLogAction()
 	{
  		$request = Hb_Util_Request_Request::GetInstance();
  
 		$account_comment = new Hb_App_Account_AccountComment(null, ACCOUNT_COMMENT_TYPE_AUDIT, $request->getParam('account_id'), $request->getParam('audit_notes'));

  		Hb_App_ObjectWatcher::commit();
  		
  		$this->getResponse()->setRedirect("/app/acm/?action=display_account_detail&account_id=". $request->getParam('account_id'));   
  		return true;
  
 	}
	
	/**
	 * Ready to Be customer action 
	 * 
	 */
	public function ReadyToBeCustomerAction()
	{ 		
		$request = Hb_Util_Request_Request::GetInstance();					
				
		//set the credit limit increase request review
		$account	= Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($this->getRequest()->getParam('account_id'));	

		$account->SetAccountName($request->getParam('account_name'));	
		$account->SetCustomer();	
		$account->SetAccountStatus(ACM_REVIEW_CONFIRMED);

		$account_term = $account->GetAccountTerm();
		
		$account_term->SetCreditLimit($request->getParam('credit_limit_new'), Hb_App_ObjectHelper::GetMapper('Hb_App_User_User')->FindByLogin($_SESSION['admin_id']));
		$account_term->UnSetReadyToBeCustomer();
		
		$account_comment = new Hb_App_Account_AccountComment(null, ACCOUNT_COMMENT_TYPE_AUDIT, $account->GetAccountId(), $request->getParam('credit_limit_reason'));
		
		//send the Alert to the Reqester
		$this->doSendProspectToCustomerReviewedAlert($account);
		
		//Add Message in to the Queue when Customer Account create
		$new_account_message = new Hb_Util_PMP_MessageQueue_AddAccount(array('marsc_account_id' 	=> $account->GetAccountId(), 
																									'marsc_account_Name' => $account->GetAccountName()));	
		//send the message in to the queue	
		$new_account_message->Process();
		
		//save or update primary billing contacts
		if($request->getParam('billing_contact') == "") {			
			$this->SavePrimaryBillingContact($account);			
		}else {
			$this->UpdatePrimaryBillingContact($account);
		}		
	}
	
	/**
	 * Send an alert notification to the requester
	 * 
	 */
	protected function doSendProspectToCustomerReviewedAlert($account)
	{
		$request = Hb_Util_Request_Request::GetInstance();
		
		$view = new Hb_View_Account_AccountCreditLimitConfirmedAlert();
		$view->SetAccount($account);
		$view->SetCreditLimit($request->getParam('credit_limit_new'));
		$view->SetRequestReason($request->getParam('credit_limit_reason'));
		
		$msg = $view->Fetch();
		
		$subject = 'Prospect to Customer Reviewed For Account '. $account->GetAccountName();
		
		try {
			$requester_attr = $account->GetAttribute('CREDIT_LIMIT_REVIEW_REQUESTER_ID');
			$requested_user = $requester_attr->GetAttributeValue();
			$requester_attr->SetAttributeValue(null);
		}catch (Hb_Data_ObjectNotInCollectionException $e){
			$requested_user = 0;
		}
		$attr = array('account_id'		=> $account->GetAccountId(),
	  					  'user_id'			=> $requested_user);
		
		$rcpt = array();
		
		$message = array('body' => $msg, 'subject' => $subject);
		
		Hb_Util_Messaging_Que::QueMessage(ACCOUNT_MESSAGE_TYPE_PROSPECT_TO_CUSTOMER, $message, $rcpt, $attr);
		
	}	
	
	/**
	 * Save Primary billing contact details
	 * 
	 */
	protected function SavePrimaryBillingContact($account)
	{
		$request = Hb_Util_Request_Request::GetInstance();		
		
		//set the existing primary billing conact to Billing contact type
		if($primary_billing_exist = $account->GetPrimaryBillingContact()) {
			$primary_billing_exist->GetContact()->SetTypeId(CONTACT_TYPE_BILLING);
		}
		
		$contact = new Hb_App_Common_Contact(null, 
														 $request->getParam('contact_first_name'), 
    													 null, 
    													 $request->getParam('contact_last_name'), 
    													 $request->getParam('contact_email'), 
    													 CONTACT_TYPE_PRIMARY_BILLING,
    													 $request->getParam('contact_title')); 

    													 
    	$contact_mapper = Hb_App_ObjectHelper::GetMapper('Hb_App_Common_Contact');		
    	$contact_mapper->Save($contact);    													 						 

    	
		// create the contact_phone object here
		$contact_phone  = new Hb_App_Common_ContactPhone(null, null, $contact->GetId(), $request->getParam('contact_phone_number'), null, PHONE_TYPE_WORK);
		
		if( $request->getParam('contact_fax_number') != '') {  
			$contact_phone_fax = new Hb_App_Common_ContactPhone(null, '0', $contact->getId(), $request->getParam('contact_fax_number'), '0', PHONE_TYPE_FAX);
		}
		
		//create the address object here
		$address = new Hb_App_Common_Address(null, $request->getParam('address_city'), $request->getParam('address_country_code'), $request->getParam('address_province'), 
			$request->getParam('address_state'), $request->getParam('address_street_1'), $request->getParam('address_street_2'), ADDRESS_TYPE_WORK, 
			$request->getParam('address_zip'), $contact->GetContactId());
			
		//create the account_contact object here
		$account_contact = new Hb_App_Account_AccountContact();
    	    	    	
    	$account_contact->SetContact($contact);
    	$account_contact->SetAccount($account);	  	   	
    	
    	$this->getResponse()->setRedirect("/app/acm/?action=display_account_detail&account_id=". $account->GetAccountId());   
    	return true;			
	}
	
	/**
	 * Update primary billing contact details
	 * 
	 */
	protected function UpdatePrimaryBillingContact($account)
	{
		$request = Hb_Util_Request_Request::GetInstance();	
		
		//set the existing primary billing conact to Billing contact type
		if($primary_billing_exist = $account->GetPrimaryBillingContact()) {
			$primary_billing_exist->GetContact()->SetTypeId(CONTACT_TYPE_BILLING);
		}
		
		$contact = Hb_App_ObjectHelper::GetMapper('Hb_App_Common_Contact')->Find($request->getParam('billing_contact'));
		$contact->SetTypeId(CONTACT_TYPE_PRIMARY_BILLING);
		$contact->SetTitle($request->getParam('contact_title'));
		$contact->SetFirstName($request->getParam('contact_first_name'));
		$contact->SetLastName($request->getParam('contact_last_name'));
		$contact->SetEmail($request->getParam('contact_email'));
		
		$address = Hb_App_ObjectHelper::GetMapper('Hb_App_Common_Address')->FindByContactId($contact->GetContactId());
		
		$address->SetContactId($contact->GetContactId());
		$address->SetAddressTypeId(ADDRESS_TYPE_WORK);
		$address->SetAddressCity($request->getParam('address_city'));
		$address->SetAddressCountryCode($request->getParam('address_country_code'));
		$address->SetAddressProvince($request->getParam('address_province'));
		$address->SetAddressState($request->getParam('address_state'));
		$address->SetAddressStreet1($request->getParam('address_street_1'));
		$address->SetAddressStreet2($request->getParam('address_street_2'));
		$address->SetAddressZip($request->getParam('address_zip'));
			
		$contact_phone = Hb_App_ObjectHelper::GetMapper('Hb_App_Common_ContactPhone')->FindContactByPhoneType($contact->GetContactId(), PHONE_TYPE_WORK);
		$contact_phone->SetContactPhoneNumber($request->getParam('contact_phone_number'));
		
		if($request->getParam('contact_fax_number') != '') {  
			try {
				$contact_fax = Hb_App_ObjectHelper::GetMapper('Hb_App_Common_ContactPhone')->FindContactByPhoneType($contact->GetContactId(), PHONE_TYPE_FAX);		
				$contact_fax->SetContactPhoneNumber($request->getParam('contact_fax_number'));	
			}catch (Hb_App_Common_Exception_CommonContactNotFoundException $e)
			{
				$contact_phone_fax = new Hb_App_Common_ContactPhone(null, '0', $contact->GetContactId(), $request->getParam('contact_fax_number'), '0', PHONE_TYPE_FAX);
			}			
		}
		
		$this->getResponse()->setRedirect("/app/acm/?action=display_account_detail&account_id=". $account->GetAccountId());   
    	return true;	
	}

	
	/**
	 * Display Ready to be Customer verify details page
	 * 
	 */
	public function DisplayReadyToBeCustomerAction()
	{
		$account			= Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($this->getRequest()->getParam('account_id'));	
		$counrites 		= Hb_App_ObjectHelper::GetMapper('Hb_App_Common_Countries')->FindAll();	
		$account_contacts = $account->GetContacts();
		
		$view = new Hb_View_Account_AccountReadyToBeCustomer();
	
		$view->SetAccount($account);
		$view->SetCountries($counrites);
		$view->SetContacts($account_contacts);		
		
		$header = Hb_View_Header_Header::factory('Account', 'acm', 'display_ready_to_be_customer');
    	$header->Display();
		
		$view->Display();
		
		$footer = Hb_View_Footer_Footer::factory();
    	$footer->Display();	
		
	}
	
	/**
	 * Display Recruiter Account details page
	 * 
	 */
	public function DisplayRecruiterAccountDetailsAction()
	{ 
		$account			= Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($this->getRequest()->getParam('account_id'));	
		$account_contacts = $account->GetContacts();
		
		$campaigns = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Recruiter_Campaigns')->Find($this->getRequest()->getParam('account_id'));
		
		$demographics = Hb_App_ObjectHelper::GetMapper('Hb_Mapper_Account_Recruiter_Demographics')->FindByAll();
	
		$view = new Hb_View_Account_DisplayRecruiterAccountDetails();
		
		if(isset($_SESSION['ACM_MANAGE_BILLING_CONTACT'])){
			$view->DisplayBillingContact();
		}
		
		if(isset($_SESSION['ACM_CREATE_CUSTOMER'])){
   		$view->DisplayAccountEdit();
   	}
   	
   	if($account->GetProduct(PRODUCT_NETMR)){
			if($account->GetProduct(PRODUCT_NETMR)->GetAccountIdentifier() != '') {
				$view->DisplayUpdateNetMr();
			} else {
   			$view->DisplayCreateNetMr();
   		}
   	}else {
   		$view->DisplayCreateNetMr();
   	}
		
		$view->SetAccount($account);
		$view->SetCampaigns($campaigns);
		$view->SetContacts($account_contacts);		
		$view->SetDemographics($demographics);		
		
		$header = Hb_View_Header_Header::factory('Account', 'acm', 'display_ready_to_be_customer');
    	$header->Display();
		
		$view->Display();
		
		$footer = Hb_View_Footer_Footer::factory();
    	$footer->Display();	
		
	}
		
	/**
	 * Display Credit limit increase request memo
	 * 
	 */
	public function DisplayCreditLimitRequestAction()
	{
		$proposal_id   			= $this->getRequest()->getParam('proposal_id');
		$proposal_revision_id   = $this->getRequest()->getParam('proposal_revision_id');		
		
		$proposal 		= Hb_App_ObjectHelper::GetMapper('Hb_App_Proposal_Proposal')->Find($proposal_id);
			
		$account 		= Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($proposal->GetAccountId());	
		
		//create an object of view class and assign data on it.
		$increase_request = new Hb_View_Account_AccountCreditLimitIncreaseRequest();
		$increase_request->SetAccount($account);
		$increase_request->SetAccountTerm($account->GetAccountTerm());
		$increase_request->SetProposalId($proposal_id);	
		$increase_request->SetProposalRevisionId($proposal_id);	
		
		if (isset($_SESSION['msg_account_review'])) {
			$increase_request->SetMessage($_SESSION['msg_account_review']);
			unset($_SESSION['msg_account_review']);
		}

		$header = Hb_View_Header_Header::factory('Account', 'acm', 'display_credit_limit_increase_request');
    	$header->Display();
		
		$increase_request->Display();
		
		$footer = Hb_View_Footer_Footer::factory();
    	$footer->Display();		
	}
	
	/**
	 * Display Credit Limit Template
	 *
	 */ 
	function CreditLimitRequestAction() 
	{ 
		$account_id  	= $this->getRequest()->getParam('account_id'); 
		$account     	= Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($account_id);
		$account_term	= $account->GetAccountTerm();
		try {
			$requester		= Hb_App_ObjectHelper::GetMapper('Hb_App_User_User')->FindByLogin($account->GetAttribute('CREDIT_LIMIT_REVIEW_REQUESTER_ID')->GetAttributeValue());
		}catch (Hb_Data_ObjectNotInCollectionException $e)
		{
			$requester = null;
		}
		//view class instance
		$vw_credit_request = new Hb_View_Account_AccountCreditLimitRequest();
		$vw_credit_request->SetAccount($account);
		$vw_credit_request->SetAccountTerm($account->GetAccountTerm());
		$vw_credit_request->SetRequester($requester);
		
		$header = Hb_View_Header_Header::factory('Account', 'acm', 'display_credit_limit_request');
    	$header->Display();
		
		$vw_credit_request->Display();
		
		$footer = Hb_View_Footer_Footer::factory();
    	$footer->Display();		
	}
	
	/**
	 * Approve credit limit
	 *
	 */ 
	function ApproveCreditLimitAction() 
	{ 
		 $account_id 	= $this->getRequest()->getParam('account_id');
		 $credit_limit = $this->getRequest()->getParam('new_credit_limit');
		 
		 $account     	= Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($account_id);		 
		 
		 try {
		 	$account_attr = $account->GetAttribute('ACM_CREDIT_LIMIT');
		 	$account_attr->SetAttributeValue($credit_limit);		 	
		 } catch (Hb_Data_ObjectNotInCollectionException $e) {
		 	$account_attr = new Hb_App_Account_AccountAttribute(null, $account_id, 'ACM_CREDIT_LIMIT', $credit_limit);
		 }
		
		 $old_credit_limit = $this->getRequest()->getParam('old_credit_limit');
		 if ($old_credit_limit == "") {
		 	$old_credit_limit = 0;
		 }
		 
		 $comment = "Amount changed from " . $old_credit_limit . " to " . $credit_limit . " : ";
		 $comment = $comment . $this->getRequest()->getParam('comment');
		 
		 $account_comment = new Hb_App_Account_AccountComment(null, ACCOUNT_COMMENT_TYPE_AUDIT, $account_id, $comment);
		 $account->SetAccountStatus(ACM_REVIEW_CONFIRMED);
		 
		 //Send an alert notification to the requested user
		 $this->doSendCreditLimitIncreaseRequesterAlert($account);
		 
		 Hb_App_ObjectWatcher::commit();
		 $this->getResponse()->setRedirect("/app/acm/?action=display_account_detail&account_id=". $account_id);
	}
	
	/**
	 * Send an alert to the credit limit increase requester
	 * 
	 */
	protected function doSendCreditLimitIncreaseRequesterAlert($account)
	{
		$request = Hb_Util_Request_Request::GetInstance();
		
		$view = new Hb_View_Account_AccountCreditLimitConfirmedAlert();
		$view->SetAccount($account);
		$view->SetCreditLimit($request->getParam('new_credit_limit'));
		$view->SetRequestReason($request->getParam('comment'));
		
		$msg = $view->Fetch();
		
		$subject = 'Credit Limit Increase Request Reviewed For Account '. $account->GetAccountName();
		
		try {
			$requester_attr = $account->GetAttribute('CREDIT_LIMIT_REVIEW_REQUESTER_ID');
			$requested_user = $requester_attr->GetAttributeValue();
			$requester_attr->SetAttributeValue(null);
		}catch (Hb_Data_ObjectNotInCollectionException $e){
			$requested_user = 0;
		}
		$attr = array('account_id'					=> $account->GetAccountId(),
						  'approved_credit_limit' 	=> $request->getParam('new_credit_limit'),
	  					  'user_id'						=> $requested_user);
		
		$rcpt = array();
		
		$message = array('body' => $msg, 'subject' => $subject);
		
		Hb_Util_Messaging_Que::QueMessage(ACCOUNT_MESSAGE_TYPE_CONFIRMED_CREDIT_LIMIT_INCREASE, $message, $rcpt, $attr);
	}
	
	/**
	 * Set Credit limit increase request
	 * 
	 */
	public function CreditIncreaseRequestAction()
	{
		$request = Hb_Util_Request_Request::GetInstance();
		
		if($request->hasParam('credit_limit') && $request->hasParam('credit_limit_reason')) {
			$proposal_id				= $this->getRequest()->getParam('proposal_id');
			$proposal_revision_id	= $this->getRequest()->getParam('proposal_id');
			
			$proposal 		= Hb_App_ObjectHelper::GetMapper('Hb_App_Proposal_Proposal')->Find($proposal_id);
				
			$account 		= Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($proposal->GetAccountId());	

			//send the credit limit increase request alert		
			$this->doSendCreditLimitIncreaseRequestAlert($account);			
					
			$account_comment = new Hb_App_Account_AccountComment(null, ACCOUNT_COMMENT_TYPE_AUDIT, $account->GetAccountId(), $request->getParam('credit_limit_reason'));
			
			$account->SetAccountStatus(ACM_CREDIT_LIMIT_INCREASE_PENDING);
			$account->SetAttribute('CREDIT_LIMIT_REVIEW_REQUESTER_ID', $_SESSION['admin_id']);
			$account->SetAttribute('ACM_REQUESTED_CREDIT_LIMIT', $request->getParam('credit_limit'));
			
			//get the Proposal Settings
			$proposal_setting = Hb_App_ObjectHelper::GetMapper('Hb_App_Proposal_ProposalSetting')->FindBySettingName('PGEN_NO_WON_FOR_PROSPECT');
		
			//check the Proposal Setting
			if($proposal_setting->GetSettingValue() == 0){				
				$this->getResponse()->setRedirect("/app/pgen/?action=display_proposal_won&proposal_id=". $proposal_id ."&proposal_revision_id=".$proposal_revision_id);
				return true;
			}
			//if the Proposal setting is equal to 1 , set the Proposal status to Waiting for Accounting review status
			$proposal->SetProposalStatusId(PROPOSAL_STATUS_ACCOUNT_REVIEW);
			
			
			$this->getResponse()->setRedirect("/app/pgen/?action=display_detail&proposal_id=". $proposal_id);
			return true;		
		}
		
		$this->getResponse()->setRedirect("/app/Account/DisplayCreditLimitRequest/proposal_id/". $proposal_id);
	}
	
	/**
	 * Send new Credit limit increase request alert
	 */
	protected function doSendCreditLimitIncreaseRequestAlert(Hb_App_Account_Account $account)
	{
		$request = Hb_Util_Request_Request::GetInstance();
		
		//create the view object here and set data on it
		$view = new Hb_View_Account_AccountCreditLimitIncreaseRequestAlert();
	
		$view->SetAccount($account);
		$user = Hb_App_ObjectHelper::GetMapper('Hb_App_User_User')->FindByLogin($_SESSION['admin_id']);
		$view->SetUser($user);
		$view->SetRequestReason($request->getParam('credit_limit_reason'));
			
		try {
			$current_credit_limit = $account->GetAttribute('ACM_CREDIT_LIMIT')->GetAttributeValue();
		}catch (Hb_Data_ObjectNotInCollectionException $e){
			$current_credit_limit = 0;
		}
		
		$view->SetCurrentCreditLimit($current_credit_limit);
		$view->SetNewCreditLimit($request->getParam('credit_limit'));
		
		$msg = $view->Fetch();
		
		$subject = 'An request for Account Credit limit increase';
		
		$attr = $this->doGetCreditLimitIncreaseAttr($account);
		
		$rcpt = array();
		
		$message = array('body' => $msg, 'subject' => $subject);
		
		Hb_Util_Messaging_Que::QueMessage(ACCOUNT_MESSAGE_TYPE_CREDIT_LIMIT_INCREASE, $message, $rcpt, $attr);		
		
	}
	
	/**
	 * Set Credit limit increase reqest message attributes
	 * 
	 */
	protected function doGetCreditLimitIncreaseAttr(Hb_App_Account_Account $account)
	{
		$request = Hb_Util_Request_Request::GetInstance();
		
		$config = Hb_Util_Config_SystemConfigReader::Read();
        
	  	$cfg['base_dir'] = $config->base_dir;
	  
	  	require_once ($_SERVER['DOCUMENT_ROOT'] . '/include/config.inc');
	  	require_once ($cfg['base_dir'] . '/class/dbConnect.php');
	  	require_once ($cfg['base_dir'] . '/class/dbClass/accountDB.class');	
	  	
	  	$acm_db = new accountDB();
	  	$acm_db->SetAccountId($account->GetAccountId());
	  	$role_ae = $acm_db->getRole(PRIMARY_ACCT_EXEC);
	  	$role_am = $acm_db->getRole(PRIMARY_ACCT_MGR);

	  	try {
			$current_credit_limit = $account->GetAttribute('ACM_CREDIT_LIMIT')->GetAttributeValue();
		}catch (Hb_Data_ObjectNotInCollectionException $e){
			$current_credit_limit = 0;
		} 	
	  	
	  	return array('primary_ae_id' 	=> $role_ae,
	  					 'primary_am_id'	=> $role_am,
	  					 'account_id'		=> $account->GetAccountId(),
	  					 'current_credit_limit'	=> $current_credit_limit,
	  					 'request_credit_limit'	=> $request->getParam('credit_limit'));
	}
	
	/**
	 * Display Custoemr review request memo 
	 * 
	 */
	public function DisplayCustomerReviewRequestAction()
	{	
		$proposal_id   			= $this->getRequest()->getParam('proposal_id');
		$proposal_revision_id   = $this->getRequest()->getParam('proposal_revision_id');
		
		$proposal 		= Hb_App_ObjectHelper::GetMapper('Hb_App_Proposal_Proposal')->Find($proposal_id);
			
		$account 		= Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($proposal->GetAccountId());
		
		$review_request = new Hb_View_Account_AccountCustomerReviewRequest();
		$review_request->SetAccount($account);
		$review_request->SetAccountTerm($account->GetAccountTerm());
		$review_request->SetProposalId($proposal_id);
		$review_request->SetProposalRevisionId($proposal_revision_id);
		
		if (isset($_SESSION['msg_account_review'])) {
			$review_request->SetMessage($_SESSION['msg_account_review']);
			unset($_SESSION['msg_account_review']);
		}
		
		$header = Hb_View_Header_Header::factory('Account', 'acm', 'display_customer_review_request');
    	$header->Display();
		
		$review_request->Display();
		
		$footer = Hb_View_Footer_Footer::factory();
    	$footer->Display();
	}
	
	
	/**
	 * Set Customer reveiw request
	 * 
	 */
	public function CustomerReviewRequestAction()
	{
		$request = Hb_Util_Request_Request::GetInstance();
		
		if($request->hasParam('review_reason') && strlen(trim($request->getParam('review_reason'))) > 0 ) {
			
			$proposal_id	= $this->getRequest()->getParam('proposal_id');
			$proposal_revision_id	= $this->getRequest()->getParam('proposal_revision_id');
			$proposal 		= Hb_App_ObjectHelper::GetMapper('Hb_App_Proposal_Proposal')->Find($proposal_id);
				
			$account 		= Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($proposal->GetAccountId());	
		
			if($account->GetAccountType() != ACCOUNT_TYPE_PROSPECT){
				$this->getResponse()->setRedirect("/app/pgen/?action=display_detail&proposal_id=". $proposal_id);   
				return true;
			}
			
			//Send the Custoemr reivew request alert
			$this->doSendCustoemrReviewReqestAlert($account);
        				
			$account_comment = new Hb_App_Account_AccountComment(null, ACCOUNT_COMMENT_TYPE_AUDIT, $account->GetAccountId(), $request->getParam('review_reason'));
			
			$account->SetAccountStatus(ACM_WAITING_FOR_REVIEW);
			$account_term = $account->GetAccountTerm();		
			$account_term->SetReadyToBeCustomer(Hb_App_ObjectHelper::GetMapper('Hb_App_User_User')->FindByLogin($_SESSION['admin_id']));
		
			//get the Proposal Settings
			$proposal_setting = Hb_App_ObjectHelper::GetMapper('Hb_App_Proposal_ProposalSetting')->FindBySettingName('PGEN_NO_WON_FOR_PROSPECT');
		
			//check the Proposal Setting
			if($proposal_setting->GetSettingValue() == 0){										
				$this->getResponse()->setRedirect("/app/pgen/?action=display_proposal_won&proposal_id=". $proposal_id ."&proposal_revision_id=".$proposal_revision_id);
				return true;
			}
			//if the Proposal setting is equal to 1 , set the Proposal status to Waiting for Accounting review status
			$proposal->SetProposalStatusId(PROPOSAL_STATUS_ACCOUNT_REVIEW);
							
			$this->getResponse()->setRedirect("/app/pgen/?action=display_detail&proposal_id=". $proposal_id);
			return true;
		}	
		
		$this->getResponse()->setRedirect("/app/Account/DisplayCustomerReviewRequest/proposal_id/". $proposal_id);
		
	}
		
	/**
	 * Set Custoemr Reveiw request calling from the Ajax call from the Jquery from the prospect_to_customer_review_request.tpl template
	 * 
	 */
	public function ProspectToCustomerAction()
	{
		//NOTE : will uncomment these commneted areas after live data is on the board
		//an test account id and will be removed later
			$account_id = 11223344;
//			$proposal_id	= $this->getRequest()->getParam('proposal_id');
//			$proposal 		= Hb_App_ObjectHelper::GetMapper('Hb_App_Proposal_Proposal')->Find($proposal_id);
//				
//			$account 		= Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($proposal->GetAccountId());				
//			
//			//Send the Custoemr reivew request alert
//			$this->doSendCustoemrReviewReqestAlert($account);
        				
			$account_comment = new Hb_App_Account_AccountComment(null, ACCOUNT_COMMENT_TYPE_AUDIT, $account_id,  $this->getRequest()->getParam('review_reason'));
			
//			$account->SetAccountStatus(ACM_WAITING_FOR_REVIEW);
//			$account_term = $account->GetAccountTerm();		
//			$account_term->SetReadyToBeCustomer(Hb_App_ObjectHelper::GetMapper('Hb_App_User_User')->FindByLogin($_SESSION['admin_id']));
			Hb_App_ObjectWatcher::commit();
			
	}
	
	/**
	 * Send new Customer review request alert
	 * 
	 */
	protected function doSendCustoemrReviewReqestAlert(Hb_App_Account_Account $account)
	{
		$request = Hb_Util_Request_Request::GetInstance();
				
		$view = new Hb_View_Account_ReviewAccount();
		
		$view->SetAccount($account);
		$user = Hb_App_ObjectHelper::GetMapper('Hb_App_User_User')->FindByLogin($_SESSION['admin_id']);			
		$view->SetUserRequestedReview($user);
		$view->SetReasonforReview($request->getParam('review_reason'));
		
		$msg = $view->Fetch();
		
		$subject = 'An request for Account Prospect to Customer';
		
		$attr = $this->doSetCustomerReveiwRequestAttr($account);
		
		$rcpt = array();
		
		$message = array('body' => $msg , 'subject' => $subject);	 	
		
		Hb_Util_Messaging_Que::QueMessage(ACCOUNT_MESSAGE_TYPE_REVIEW_ACCOUNT, $message, $rcpt, $attr);
	}
	
	
	/**
	 * Set Customer Reveiw Request message attributes
	 * 
	 */
	protected function doSetCustomerReveiwRequestAttr(Hb_App_Account_Account $account)
	{
		$config = Hb_Util_Config_SystemConfigReader::Read();
        
	  	$cfg['base_dir'] = $config->base_dir;
	  
	  	require_once ($_SERVER['DOCUMENT_ROOT'] . '/include/config.inc');
	  	require_once ($cfg['base_dir'] . '/class/dbConnect.php');
	  	require_once ($cfg['base_dir'] . '/class/dbClass/accountDB.class');	
	  	
	  	$acm_db = new accountDB();
	  	$acm_db->SetAccountId($account->GetAccountId());
	  	$role_ae = $acm_db->getRole(PRIMARY_ACCT_EXEC);
	  	$role_am = $acm_db->getRole(PRIMARY_ACCT_MGR);
	  		  		
	  	return array('primary_ae_id' 	=> $role_ae,
	  					 'primary_am_id'	=> $role_am,
	  					 'account_id'		=> $account->GetAccountId());
	}
	
	/**
	 * Display Account Basic Contacts 
	 * 
	 */
	public function DisplayBasicAccountContactAction()
	{		
		$request = Hb_Util_Request_Request::GetInstance();
		
		$account_id    = $this->getRequest()->getParam('account_id');		
		$account       = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($account_id);
		$counrites 		= Hb_App_ObjectHelper::GetMapper('Hb_App_Common_Countries')->FindAll();		
		
		$basic_contact = new Hb_View_Account_AccountBasicContact();
		//set the account term object
		if($account->GetAccountType() == ACCOUNT_TYPE_CUSTOMER || $account->GetAccountType() == ACCOUNT_TYPE_PROSPECT) {		
			$account_term = $account->GetAccountTerm();
			$basic_contact->SetAccountTerm($account_term);
			$basic_contact->SetDisplay('view_credit_hold_status');
		}		
		
		$basic_contact->SetAccount($account);		
		$basic_contact->SetCountries($counrites);	
		
		$header = Hb_View_Header_Header::factory('Account', 'acm', 'display_basic_contact');
    	$header->Display();
		
		$basic_contact->Display();
		
		$footer = Hb_View_Footer_Footer::factory();
    	$footer->Display();
	}
	
	/**
	 * Save Account Basic contacts
	 * 
	 */
	public function SaveBasicContactAction()
	{
		$request = Hb_Util_Request_Request::GetInstance();     
		
		$account_id    = $this->getRequest()->getParam('account_id');	
		$account       = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($account_id);
		$contact = new Hb_App_Common_Contact(null, 
														 $request->getParam('contact_first_name'), 
    													 null, 
    													 $request->getParam('contact_last_name'), 
    													 $request->getParam('contact_email'), 
    													 CONTACT_TYPE_BUSINESS,
    													 $request->getParam('contact_title')); 

    	// To do : have to remove this part from here , just put, because the references didn't work correctly.												 
    	$contact_mapper = Hb_App_ObjectHelper::GetMapper('Hb_App_Common_Contact');		
    	$contact_mapper->Save($contact);    													 						 

		// create the contact_phone object here
		$contact_phone  = new Hb_App_Common_ContactPhone(null, null, $contact->GetId(), $request->getParam('contact_phone_number'), null, PHONE_TYPE_WORK);
		
		//create the address object here
		$address = new Hb_App_Common_Address(null, null, $request->getParam('address_country_code'), null, null, null, null, ADDRESS_TYPE_WORK, null, $contact->GetId());

		//create the account_contact object here
		$account_contact = new Hb_App_Account_AccountContact();
    	    	    	
    	$account_contact->SetContact($contact);
    	$account_contact->SetAccount($account);	  	

    	//check whether account is a new account
    	if($account->GetAudit()->GetStatus() == 'I') {
    		$this->getResponse()->setRedirect("/app/acm/?action=display_set_account_user_role&account_id=". $account_id);   
    	} else {    	
    		$this->getResponse()->setRedirect("/app/acm/?action=display_account_detail&account_id=". $account_id);   
    	}   
    	    	
    	if($account->GetAccountType() == ACCOUNT_TYPE_RECRUITER){
    			$this->getResponse()->setRedirect("/app/Account/DisplayRecruiterAccountDetails/account_id/". $account_id);   
    	}
    	 	 	
	}
	
	
	/**
	* Save the Account Credit Hold Attributes
	*
	*/
	public function PlaceCreditHoldAction()
	{	
		
		$request = Hb_Util_Request_Request::GetInstance();     
		
		$account_id    = $this->getRequest()->getParam('account_id');		  
		
		//check whether user has place a credit hold
		if($request->hasParam('credit_hold_list')) {
			
			$proposalble	= false;
			$projectable 	= false;
			$invoicable		= false;
			
			$account       = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($account_id);
			
			$account_term  = $account->GetAccountTerm();				  
			
			$credit_hold_list = $request->getParam('credit_hold_list');
			
			if(in_array('PROPOSABLE', $credit_hold_list)) {
			 $proposalble	= true;
			}
			
			if(in_array('PROJECTABLE', $credit_hold_list)) {
			$projectable 	= true;
			}
			
			if(in_array('INVOICABLE', $credit_hold_list)) {
			$invoicable	= true;  
			}	
			
			//try catch section to get the product for the account, exception will throw if there is no product for the account
			try{							
				$account_product = $account->GetProduct(PRODUCT_NETMR);					
			}catch (Hb_App_Account_Exception_ContactNotFoundException $e) {								
				$account_product = false;
			}		
			
			if($account_product && $account_product->GetAudit()->GetStatus() == 'A') {				
				$account_term->SetCreditHold($proposalble, $projectable, $invoicable);
				$account_product_attributes = $account_product->GetAttributes();	
				$license_expire_date 		 = '2008-12-31';
				
				try 
				{		
					$license_expire_date_attr = $account_product_attributes->GetAttribute('LICENSE_END_DATE');
					$license_expire_date = $license_expire_date_attr->GetAttributeValue();
					$license_expire_date_attr->SetAttributeValue(DEFAULT_LICENCE_EXPIRE_DATE); 
				} 
				catch(Hb_Data_ObjectNotInCollectionException $e) {
					$attr = new Hb_App_Account_AccountProductAttribute(null, $account_product->GetAccountProductId(), 'LICENSE_END_DATE', DEFAULT_LICENCE_EXPIRE_DATE);
					$account_product_attributes->AddAttribute($attr);
				}

				try 
				{
					$license_expire_date_attr_ori = $account_product_attributes->GetAttribute('ORIGINAL_LICENSE_EXPIRE_DATE');
					if ($license_expire_date != DEFAULT_LICENCE_EXPIRE_DATE) {
						$license_expire_date_attr_ori->SetAttributeValue($license_expire_date);
					}
				}
				catch (Hb_Data_ObjectNotInCollectionException $e) 
				{					
					$attr = new Hb_App_Account_AccountProductAttribute(null, $account_product->GetAccountProductId() , 'ORIGINAL_LICENSE_EXPIRE_DATE', $license_expire_date);
		     		$account_product_attributes->AddAttribute($attr);
				}
				
				Hb_App_ObjectWatcher::commit();
				$this->getResponse()->setRedirect("/app/acm/?action=update_product_account&account_id=". $account_id . "&product_id=" . PRODUCT_NETMR . "&msg=1");    	  	
				
			} elseif(!$projectable) {				
				 $account_term->SetCreditHold($proposalble, $projectable, $invoicable);
				 Hb_App_ObjectWatcher::commit();
			}else {
				$_SESSION['error_code'] = 'no_product';
			}
		}		
		
		
		$this->getResponse()->setRedirect("/app/acm/?action=display_account_detail&account_id=". $account_id);    	  	
		
	}

	/**
	 * Unloack the Credit hold attributes
	 * 
	 */
	public function RemoveCreditHoldAction()
	{
		$account_id    = $this->getRequest()->getParam('account_id');			
		$account       = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($account_id);
			
		$account_term  = $account->GetAccountTerm();	
		
		$account_term->SetCreditHold(false, false, false);
		
		try{							
			$account_product = $account->GetProduct(PRODUCT_NETMR);		
		}catch (Hb_App_Account_Exception_ContactNotFoundException $e) {				
			$account_product = false;
		}		
		
		if($account_product && $account_product->GetAudit()->GetStatus() == 'A') {	
		
			$account_product_attributes = $account_product->GetAttributes();			
			$license_expire_date_attr_ori = $account_product_attributes->GetAttribute('ORIGINAL_LICENSE_EXPIRE_DATE');
			$license_expire_date = $license_expire_date_attr_ori->GetAttributeValue();
			$license_expire_date_attr_ori->SetAttributeValue('');		
		
			try 
			{
				$license_expire_date_attr = $account_product_attributes->GetAttribute('LICENSE_END_DATE');
				$license_expire_date_attr->SetAttributeValue($license_expire_date);
			}
			catch (Hb_Data_ObjectNotInCollectionException $e) 
			{
				$attr = new Hb_App_Account_AccountProductAttribute(null, 
																				$account_product->GetAccountProductId() , 
																				'LICENSE_END_DATE', 
																				$license_expire_date);
				$account_product_attributes->AddAttribute($attr);
			}
		
			$this->getResponse()->setRedirect("/app/acm/?action=update_product_account&account_id=". $account_id . "&product_id=" . PRODUCT_NETMR . "&msg=1"); 
		} else {
		
			$this->getResponse()->setRedirect("/app/acm/?action=display_account_detail&account_id=". $account_id);
		}	
	}
	
   /**
    * Save the Account Attributes
    *
    */
	public function SaveAccountAttributesAction()
	{
	   $account_id = $this->getRequest()->getParam('account_id');
		$account    = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($account_id);
		$account_status = $account->GetAudit()->GetStatus();
		    
		$request = Hb_Util_Request_Request::GetInstance();
				
		$account_attributes_definitions = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_AccountAttributeDefinitions')->FindAll();		
			
		foreach ($account_attributes_definitions as $account_attribute_def) {		
		    
			/* @var $account_attribute_def Hb_App_Account_AccountAttributeDefinition */
			if (!$account_attribute_def->isEditable() && $account_status != 'I') {
			  continue;
			}
			
			$attr_name  = $account_attribute_def->GetAttributeName();
			$attr_value = $request->getParam($attr_name);
			 			
			if ($request->getParam($attr_name) == 'on') {
				$attr_value = 1;					
			} elseif (!$request->hasParam($attr_name) && $account->HasAttribute($attr_name)) { 
                //TODO: this is not a very good assumption and can be used to change data by a user, must rely on an internal definition
			    $attr_value = 0;
			}
			
			$account->SetAttribute($attr_name, $attr_value);
		}
		//If The Account is Customer Type, Send a message to the Queue
		// message contents - Account Id, Account Name
		if($account->GetAccountType() == ACCOUNT_TYPE_CUSTOMER){
			//
			$new_account_message = new Hb_Util_PMP_MessageQueue_AddAccount(array('marsc_account_id' 	=> $account->GetAccountId(), 
																												'marsc_account_Name' => $account->GetAccountName()));	
			//send the message in to the queue	
			$new_account_message->Process();
		}
		
		$account->SetStatus('A');
		
		if($account->GetAccountType() == ACCOUNT_TYPE_RECRUITER){
			header("Location:/app/Account/DisplayRecruiterAccountDetails/account_id/". $account_id);
			return false;	
		}
		
		$this->getResponse()->setRedirect("/app/acm/?action=display_account_detail&account_id=". $account_id);    	
	}
	

	/**
	 * Display Purchase Order list
	 * 
	 */
    public function DisplayPurchaseOrderListAction()
    {
    	$account = new Hb_App_Account(200100);
    	
    	$po = $account->GetPurchaseOrders();
    	
    	$this->getResponse()->setHeader('Content-Type', 'text/html')->appendBody($po->name);
    }
    
    /**
     * Display Account
     * 
     */
    public function DisplayAccountAction()
    {
    	$account_id = $this->getRequest()->getParam('account_id');
    	
    	$view = new Hb_View_Account_Account();
    	
    	$view->AddAccount(
    		Hb_Transaction_Account_Account::GetAccountDetail($account_id)
    	);
    	
    	$view->AddCountryList(
    		Hb_Transaction_Common_Country::GetList()
    	);
    	
    	$this->getResponse()->setHeader('Content-Type', 'text/html')
    					->appendBody(
    						Hb_Util_View_View::Render(
    							new Hb_View_Header_Header()
    						)
    					)
    					->appendBody(
    						Hb_Util_View_View::Render($view)
    					)
    					->appendBody(
    						Hb_Util_View_View::Render(
    							new Hb_View_Footer_Footer()
    						)
    					);
    }
    
    /**
     * Save Account
     *
     */
    public function SaveAccount()
    {
    	
    }
    
    
    /**
     * Display Account list
     *
     */
    public function displayaccountlistAction()
    {
    	$header = Hb_View_Header_Header::factory('Account', 'acm', 'display_list');
    	$header->Display();
    	
    	//$view = new Hb_View_Account_AccountList();
    	//$view->Display();
    	
    	//$footer = Hb_View_Footer_Footer::factory();
    	//$footer->Display();
    }
    
    /**
     * Search Fields
     * 
     *
     */
    public function SetSearchFieldsAction()
    {
    	$definition = new Hb_App_Report_ReportDefinitionDefault();
    	$condition_fields = $definition->GetConditionFields();
    	$user_pref = Hb_App_Report_ReportPreferenceFactory::GetPreference($definition);
    	$request = Hb_Util_Request_Request::GetInstance();
    	$session = new Hb_Util_Session_Session();
    	$filter = new Hb_App_Report_ReportFilter();
    	
    	foreach ($condition_fields as $condition_field)
    	{
    		$field_name = $condition_field->GetFormName();
    		
    		/** @var $condition_field Hb_App_Common_ReportField **/
    		if ($request->GetParam($field_name) != '') 
    		{
    			$value = $request->GetParam($field_name);
    			
    			if (is_array($value)) {
    				$condition = new Hb_App_Report_ReportCondition($condition_field, $value, Hb_App_Report_ReportCondition::INARRAY);
    			} else {
    				$condition = new Hb_App_Report_ReportCondition($condition_field, $value, Hb_App_Report_ReportCondition::EQUAL);
    			}
    			$filter->AddCondition($condition);
    		}
    	}
    	
    	$user_pref->SetFilter($filter);
    	
    	$session->user_pref = $user_pref; 
    	
    	$this->_redirect('/Account/DisplayAccountList');
    }
    
    /**
     * User Preference
     *
     * @return boolean
     */
    public function SetUserPreferenceAction()
    {
    	$report = new Hb_App_Report_DefaultReport(); //TODO: this should really be called something else
    	$definition = new Hb_App_Report_ReportDefinitionDefault();
    	
    	$report->SetReport($definition);
    	$user_pref = Hb_App_Report_ReportPreferenceFactory::GetPreference($definition);
    	
    	$request = Hb_Util_Request_Request::GetInstance();
    	
    	$session = new Hb_Util_Session_Session();
    	
    	if ($request->getParam('command') == 'default') {
    		unset($session->user_pref);
    		$this->_redirect('/Account/DisplayAccountList');
    		return true;
    	}
    	
    	if ($request->getParam('columns')) {
			foreach ($request->getParam('columns') as $f) {
				$user_pref->AddColumn(
					$definition->GetField($f)
				);
			}
		}
		
		if ($request->getParam('direction') != '')
		{
			switch ($request->getParam('direction'))
			{
				case 'next':
					$start = $user_pref->GetPageSize() + $user_pref->GetPageStart();
					break;
			}
			
			$user_pref->SetPageStart($start);
		}
		
		
		$session->user_pref = $user_pref; 
		
		$this->_redirect('/Account/DisplayAccountList');
    }
    
    function images50Action()
    {
    	
    }
     
    /**
     * Save Account Contact
     *
     */
    function SaveAccountContactAction()
    {
    	$request = Hb_Util_Request_Request::GetInstance();    	
    	
		if (! $request->hasParam("account_id"))
      {
      	throw new Exception("Invalid request: Account Id not found");
      }   
      
      if( 0 == $request->contact_id) {
      	$this->doAddContact();
      } else { 
      	$this->doUpdateContact();
      }	
       	        	
    	$this->getResponse()->setRedirect("/app/acm/?e=".Hb_Util_Encryption_Encryption::GetInstance()->Encrypt("action=display_account_detail&account_id=".$request->account_id));
    	
    }
    
    function doAddContact()
    {
    	$request = Hb_Util_Request_Request::GetInstance();
    	
    	$account = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($request->account_id); 
    	    	    	    
    	$contact = new Hb_App_Common_Contact(null, $request->contact_title, $request->contact_first_name, 
    													 null, $request->contact_last_name, 
    													 $request->contact_email, $request->contact_type_id);    	
    	
    	$contact_mapper = Hb_App_ObjectHelper::GetMapper('Hb_App_Common_Contact');		
    	$contact_mapper->Save($contact);    	
    	
    	$address = new Hb_App_Common_Address(null, $request->address_city, $request->address_country_code, $request->address_province,
    													 $request->address_state, $request->address_street_1, $request->address_street_2, ADDRESS_TYPE_WORK,
    													 $request->address_zip, $contact->getId()
    													);
    													
    	$contact_phone  = new Hb_App_Common_ContactPhone(null, '0', $contact->getId(), $request->contact_phone_number, '0', PHONE_TYPE_WORK);
    	
		if( $request->contact_fax_number != '') {  
			$contact_phone_fax = new Hb_App_Common_ContactPhone(null, '0', $contact->getId(), $request->contact_fax_number, '0', PHONE_TYPE_FAX);
		}
    	    											
    	$account_contact = new Hb_App_Account_AccountContact();
    	    	    	
    	$account_contact->SetContact($contact);
    	$account_contact->SetAccount($account);
    	    	    	    	
    }
    
    /**
     * Update Contact
     *
     */
    function doUpdateContact()
    {     
		$request = Hb_Util_Request_Request::GetInstance();
		
    	$contact_mapper= Hb_App_ObjectHelper::GetMapper('Hb_App_Common_Contact'); 
    	$contact = $contact_mapper->Find($request->contact_id);
    	    	
    	$contact->SetTitle($request->contact_title);
    	$contact->SetFirstName($request->contact_first_name);
    	$contact->SetLastName($request->contact_last_name);
    	$contact->SetEmail($request->contact_email);
    	$contact->SetTypeId($request->contact_type_id);
    	    	
    	
		if(is_numeric($request->contact_phone_id) && $request->contact_phone_id != 0) {			
			Hb_App_ObjectHelper::GetMapper('Hb_App_Common_ContactPhone')->Find($request->contact_phone_id)->SetContactPhoneNumber($request->contact_phone_number);  		
		} else { 				
			$contact_phone  = new Hb_App_Common_ContactPhone(null, '0', $request->contact_id, $request->contact_phone_number, '0', PHONE_TYPE_WORK);
		}
					
		if (is_numeric($request->contact_fax_id) && $request->contact_fax_id != 0) {			
			Hb_App_ObjectHelper::GetMapper('Hb_App_Common_ContactPhone')->Find($request->contact_fax_id)->SetContactPhoneNumber($request->contact_fax_number);  		
		} else {
			$contact_fax  = new Hb_App_Common_ContactPhone(null, '0', $request->contact_id, $request->contact_fax_number, '0', PHONE_TYPE_FAX);
		}		
        	
    	$address_mapper = Hb_App_ObjectHelper::GetMapper('Hb_App_Common_Address');		
    	$address = $address_mapper->Find($request->address_id);
    	 
    	$address->SetAddressStreet1($request->address_street_1);
    	$address->SetAddressStreet2($request->address_street_2);
    	$address->SetAddressCity($request->address_city);
    	$address->SetAddressState($request->address_state);
    	$address->SetAddressProvince($request->address_province);
    	$address->SetAddressZip($request->address_zip);
    	$address->SetAddressCountryCode($request->address_country_code);
    	$address->SetContactId($request->contact_id);
    	$address->SetAddressTypeId(ADDRESS_TYPE_WORK);
    	 
    }
    
    /**
     * Display Recruiting campaign details
     * 
     */
    public function DisplayCampaignDetailsAction()
    {
    	$request = Hb_Util_Request_Request::GetInstance();
    	
    	$view = new Hb_View_Account_DisplayCampaignDetail();
    	if($this->getRequest()->getParam('campaign_id') != '') {
    	
	    	$campaign_id 	= $this->getRequest()->getParam('campaign_id');
	 
	    	$campaign 		= Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Recruiter_Campaign')->Find($campaign_id);
	    	
			$account = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($campaign->GetAccountId());

			$recruiter_term = new Hb_App_Account_Recruiter_RecruiterTerm($campaign->GetAccountId(), $campaign->GetRecruiterCampaignCode());
			
			//load recuriter campaign rating comments
			$comments = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Recruiter_CampaignComments')->FindByCommentType($campaign_id, ACCOUNT_RECRUITER_COMMENT_TYPE_RATING);
			
			//set parameters in tothe view class
			$view->SetAccount($account);
			
			$view->SetCampaign($campaign);
			$view->SetRectuiterTerm($recruiter_term);
				
	    	$header = Hb_View_Header_Header::factory('Account', 'acm', 'display_ready_to_be_customer');
	    	$header->Display();
			
			$view->Display();
			
			$footer = Hb_View_Footer_Footer::factory();
	    	$footer->Display();
    	
    	}else {
    		//redirect to the Account module
    		$this->getResponse()->setRedirect("/app/acm/");
    	}

    }
    
    /**
     * Display Recruiter campaign add page
     * 
     */
    public function DisplayAddCampaignAction()
    {
    	$view = new Hb_View_Account_Recruiter_DisplayAddCampaign();
    	$account = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($this->getRequest()->getParam('account_id'));
		
		$view->SetAccount($account);    	
    	
    	$header = Hb_View_Header_Header::factory('Account', 'acm', 'display_add_campaign');
    	$header->Display();
		
		$view->Display();
		
		$footer = Hb_View_Footer_Footer::factory();
    	$footer->Display();
    }
	
    /**
     * Display Credit Limit editing screen
     * 
     */
    public function DisplayEditCreditLimitAction()
    {
		$view = new Hb_View_Account_DisplayEditCreditLimit();
		$view->SetAccount($this->getRequest()->getParam('account_id'));

    	$header = Hb_View_Header_Header::factory('Account', 'acm', 'display_ready_to_be_customer');
    	$header->Display();
		
		$view->Display();
		
		$footer = Hb_View_Footer_Footer::factory();
    	$footer->Display();	
    }

    /**
     * Update credit limit
     * 
     */
    public function EditCreditLimitAction()
    {
		$account_id = $this->getRequest()->getParam('account_id');
		$new_credit_limit =  $this->getRequest()->getParam('new_credit_limit');

		$account	= Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($account_id);	
		try{
			$account_attr = $account->GetAttribute('ACM_CREDIT_LIMIT');
			$account_attr->SetAttributeValue($new_credit_limit);
		}catch(Exception $e){
			$account->SetAttribute('ACM_CREDIT_LIMIT', $new_credit_limit);
		}
		 $this->getResponse()->setRedirect("/app/acm/?action=display_account_detail&account_id=".$this->getRequest()->getParam('account_id'));
    }
    
    /**
     * Save Campaign
     * 
     */
    public function SaveCampaignAction()
    {
		$request = Hb_Util_Request_Request::GetInstance();   
		
		if($this->getRequest()->getParam('campaign_id') != '') 
		{
			$campaign = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Recruiter_Campaign')->Find($this->getRequest()->getParam('campaign_id'));
			
			$campaign->SetRecruiterCampaignCode($request->getParam('campaign_code'));
			$campaign->SetCampaignStartDate($request->getParam('campaign_start_date'));
			$campaign->SetCampaignEndDate($request->getParam('campaign_end_date'));
			$campaign->SetCampaignDescription($request->getParam('campaign_description'));
			
			$this->getResponse()->setRedirect("/app/Account/DisplayCampaignDetails/campaign_id/". $this->getRequest()->getParam('campaign_id'));   
		}
		else 
		{
			$campaign = new Hb_App_Account_Recruiter_Campaign(null, 
															 	           $request->getParam('campaign_code'), 
															 	           $request->getParam('account_id'), 
															 	           $request->getParam('campaign_start_date'), 
															 	        	  $request->getParam('campaign_end_date'), 
															 	        	  $request->getParam('campaign_description'), 
															 	           null,
															 	           null,
															 	        	  null,
															 	        	  null,
															 	           null,
															 	        	  null,
															 	        	  'A'
	    													 				 );      		
			Hb_App_ObjectWatcher::commit();
	    	$this->getResponse()->setRedirect("/app/Account/DisplayAddCampaignPricing/campaign_id/". $campaign->GetRecruiterCampaignId());
		}	
    	   
    }
    
    /**
     * Add Campaign Pricing 
     * 
     */
    public function AddRecruiterPricingAction()
    {
    	$request = Hb_Util_Request_Request::GetInstance();   
    	if($request->hasParam('campaign_id')) 
    	{
   		$campaign = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Recruiter_Campaign')->Find($this->getRequest()->getParam('campaign_id'));
   		$campaign->SetCostPerAcquisition($request->getParam('cost_per_aquisition'));
   		$campaign->SetCostPerComplete($request->getParam('cost_per_complete'));
   		$campaign->SetRevenueShare($request->getParam('cpr_revenue_share'));
   		$campaign->SetProfitShare($request->getParam('cpr_profit_share'));
   		
   		//build the Pricing comment
   		$comment = new Hb_App_Account_Recruiter_CampaignComment(null, $this->getRequest()->getParam('campaign_id'), ACCOUNT_RECRUITER_COMMENT_TYPE_PRICING, $request->getParam('notes'));
   		
   		$this->getResponse()->setRedirect("/app/Account/DisplayCampaignDetails/campaign_id/".$this->getRequest()->getParam('campaign_id'));
    	}
    	
    }
    
    /**
     * Display Add Campaign Pricing 
     * 
     */
    public function DisplayAddCampaignPricingAction()
    {
    	$view = new Hb_View_Account_Recruiter_DisplayAddCampaignPricing();
    	$campaign 	= Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Recruiter_Campaign')->Find($this->getRequest()->getParam('campaign_id'));
		$account		= $campaign->GetAccount();
    	    	
    	$view->SetAccount($account);
    	$view->SetCampaign($campaign);
    	$header = Hb_View_Header_Header::factory('Account', 'acm', 'display_add_campaign_pricing');
    	$header->Display();
		
		$view->Display();
		
		$footer = Hb_View_Footer_Footer::factory();
    	$footer->Display();
    }
    
	/**
	 * Update Recruiter Pricing Details
	 * 
	 */
	public function UpdateRecruiterPricingAction()
	{
		$request = Hb_Util_Request_Request::GetInstance();
		$campain_id = $this->getRequest()->getParam('campaign_id');
		
		$campaign = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Recruiter_Campaign')->Find($campain_id);
		
		$campaign->SetPricingComment($request->getParam('notes'));
		$campaign->SetCostPerAcquisition($request->getParam('cost_per_aquisition'));
		$campaign->SetCostPerComplete($request->getParam('cost_per_complete'));
		$campaign->SetRevenueShare($request->getParam('cpr_revenue_share'));
		$campaign->SetProfitShare($request->getParam('cpr_profit_share'));	
		
		$this->getResponse()->setRedirect("/app/Account/DisplayCampaignDetails/campaign_id/". $campain_id);

	}    
    
	/**
	 * Update Recruiter Quality Metrics Details
	 * 
	 */
	public function UpdateQualityMetricsAction()
	{		
		
		$request 	= Hb_Util_Request_Request::GetInstance();
		$campain_id = $this->getRequest()->getParam('campaign_id');
		
		$campaign 	= Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Recruiter_Campaign')->Find($campain_id);
		$from 		= 'changed initial quality rating';
		
		if(!is_null($campaign->GetQualityRating())) {
			$from 	= "changed quality rating from " . str_repeat("*", $campaign->GetQualityRating());
		}
		
		$comment = $request->getParam('rating_notes') . ", " . $from . " to " . str_repeat("*", $request->getParam('quality_rating'));
		
		$campaign_comment = new Hb_App_Account_Recruiter_CampaignComment(null, $campain_id, ACCOUNT_RECRUITER_COMMENT_TYPE_RATING, $comment);
																								  
		$campaign->SetQualityRating($request->getParam('quality_rating'));
		//$campaign->SetConversionRate($request->getParam('conversion_rate'));
		$this->getResponse()->setRedirect("/app/Account/DisplayCampaignDetails/campaign_id/". $campain_id);    
			
	}
	
	/**
	 * Delete Recruiter Quality Metrics Details
	 * 
	 */
	public function DeleteRecruiterPricingAction()
	{
		
		$request = Hb_Util_Request_Request::GetInstance();	
				
		$campaign = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Recruiter_Campaign')->Find($this->getRequest()->getParam('campaign_id'));
	
		$campaign->SetPricingComment('');
		$campaign->SetCostPerAcquisition();
		$campaign->SetCostPerComplete();
		$campaign->SetRevenueShare();
		$campaign->SetProfitShare();
	
		$this->getResponse()->setRedirect("/app/Account/DisplayCampaignDetails/campaign_id/". $this->getRequest()->getParam('campaign_id'));   
	}
	
	/**
	 * Save Demographics
	 * 
	 */	
	public function SaveDemographicsAction(){
		
		// deleting currently selected demographic types before saving new types
		$demographics = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Recruiter_CampaignDemographics')->FindByAccountId($this->getRequest()->getParam('account_id'));
		
		foreach ($demographics as $demographic) {
			$demographic->SetStatus('D');
			Hb_App_ObjectWatcher::commit();
		}
		
		//saving the demographic types
		$demographics = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Recruiter_Demographics')->FindByAll();
		
	   foreach ($demographics as $demographic) {
   			
   		$demographic_types = $this->getRequest()->getParam('demographics_'.$demographic->GetRecruiterDemographicId());
   		
   		if(!isset( $demographic_types )) {
   			continue;
   		}
   		
   		foreach($demographic_types as $type_value) {
   			$campaign_demographic = new Hb_App_Account_Recruiter_CampaignDemographic(null, $this->getRequest()->getParam('account_id'), $type_value);
   			$campaign_demographic->SetStatus('A');
   			Hb_App_ObjectWatcher::commit();
   		}
	   } 
	   
	   $this->getResponse()->setRedirect("/app/Account/DisplayRecruiterAccountDetails/account_id/" . 
													 $this->getRequest()->getParam('account_id'));

	}
	
	/**
	 * Delete a Campaign from the account 
	 * 
	 */
	public function DeleteRecruiterCampaignAction()
	{
		if($this->getRequest()->getParam('campaign_id') != '') {
			$campaign = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Recruiter_Campaign')->Find($this->getRequest()->getParam('campaign_id'));
			$campaign->SetStatus('D');
			
			$this->getResponse()->setRedirect("/app/Account/DisplayRecruiterAccountDetails/account_id/". $campaign->GetAccountId());
		}else {
			//if the campaign id is not set redirect to the Account module default page
			$this->getResponse()->setRedirect("/app/acm/");
		}
		
	}
	
	/**
	 *  Display Add Account Vendor popup
	 * 
	 */
	public function DisplayAddAccountVendorAction()
	{ 
		$config = Hb_Util_Config_SystemConfigReader::Read();
    
      $cfg['base_dir'] = $config->base_dir;
        
      require_once($_SERVER['DOCUMENT_ROOT'].'/include/config.inc');
      require_once($cfg['base_dir'].'/class/dbConnect.php');
      require_once($cfg['base_dir'].'/class/dbClass/accountDB.class');
      require_once($cfg['base_dir'].'/common/functions.inc');
        
      $a = new accountDB();
            
		$sub_types = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_AccountSubTypes')->FindByAccountTypeId(ACCOUNT_TYPE_VENDOR);   	
		
		foreach ($sub_types as $sub_type){
			 $vendor_subtypes[$sub_type->GetAccountSubTypeId()] = $sub_type->GetAccountSubTypeDescription();
			 
			 $list = ($sub_type->GetAccountSubTypeId() != 0) ? PrepareSmartyArray($a->GetAccountListByType(ACCOUNT_TYPE_VENDOR, $sub_type->GetAccountSubTypeId())) : PrepareSmartyArray($a->GetAccountListByType(ACCOUNT_TYPE_VENDOR));
			 
			 $vendors[$sub_type->GetAccountSubTypeId()] = $list;
		}
				
      $vendors_array = "Array(Array(), ";
      foreach ($vendors as $subtype=>$subtype_vendors) {
      	$v_array = "Array(";
         if (is_array($subtype_vendors) && !empty($subtype_vendors)) {
      	foreach($subtype_vendors AS $subtype_vendor) {
      	   $v_array .= "'".$subtype_vendor["account_id"]."//".mysql_real_escape_string($subtype_vendor["account_name"])."', ";
      	}
      	$v_array = substr($v_array, 0, strlen($v_array)-2);
         }
         $v_array .= ")";
      	$vendors_array .= $v_array.", ";
      }
      
      $vendors_array = substr($vendors_array, 0, strlen($vendors_array)-2).")";
      
      $account_vendors = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_AccountVendors')->FindByAccountId($this->getRequest()->getParam('account_id'));
     
     	$view = new Hb_View_Account_AccountVendor();
		$view->SetAccount($this->getRequest()->getParam('account_id'));
		$view->SetVendors($vendors_array);
		$view->SetVendorSubTypes($vendor_subtypes);
		$view->SetAccountVendors($account_vendors);
		
		$view->Display();
		
		
	}	
	
	/**
	 *  Save Account Vendor
	 * 
	 */	
	public function SaveAccountVendorAction()
	{ 
		for($i=1; $i<=5; $i++) {
			$vendor_sub_type_id = $this->getRequest()->getParam("new_vendor_sub_type_id_$i"); 
			$vendor_account_id = $this->getRequest()->getParam("new_vendor_account_id_$i"); 
         if (isset($vendor_sub_type_id) && ($vendor_sub_type_id!="")) {
            $acct = split("//", $vendor_account_id);
            
            $account_vendor = new Hb_App_Account_AccountVendor(null, $this->getRequest()->getParam("account_id"), $acct[0], $vendor_sub_type_id);
            $account_vendor->SetStatus("A");
             Hb_App_ObjectWatcher::commit();
         }
      }
     
      
      $this->getResponse()->setRedirect("/app/acm/?action=close_popup");
      
	}
	
	/**
	 *  Delete Account Vendor
	 * 
	 */	
	public function DeleteAccountVendorAction()
	{
		$vendor = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_AccountVendor')->Find($this->getRequest()->getParam('study_account_id'));
		$vendor->SetStatus("D");
		Hb_App_ObjectWatcher::commit();
		
		$this->getResponse()->setRedirect("/app/acm/?action=close_popup");
	}
}
?>
