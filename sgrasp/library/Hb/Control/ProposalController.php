<?php

include_once 'class/Hb/View/Header/Header.class';
include_once 'class/Hb/View/Footer/Footer.class';
include_once 'Zend/Controller/Action.php';
include_once 'class/Hb/Util/Request/Request.class';

class ProposalController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$this->getResponse()->setHeader('Content-Type', 'text/html')->appendBody("Hello Hello");
	}

	public function displayAction()
	{
	  echo 'Hello from Display Proposal';
	}

	public function displayAddAction()
	{
		include_once 'class/Hb/View/Proposal/ProposalAdd.class';
		
		$header = Hb_View_Header_Header::factory('Proposal', 'pgen', 'display_add');
		$header->Display();
		
		$view = new Hb_View_Proposal_ProposalAdd();
		$view->Display();
		
		$footer = Hb_View_Footer_Footer::factory();
		$footer->Display();
	}

	public function saveProposalAction()
	{
		include_once 'class/Hb/App/Proposal/Manager/Repository.class';
		
		$request = Hb_Util_Request_Request::GetInstance();
		
		$proposal = Hb_App_Proposal_Manager_Repository::Create($request->getParam('account_id'), $request->getParam('proposal_name'));
		
		Hb_App_Proposal_Manager_Repository::AddContact($proposal, $request->getParam('contact_id'));
		
		header("Location: /app/pgen/?action=display_add_revision&proposal_id=". $proposal->GetId());
	
	}

	public function displayRevisionAction()
	{
		echo 'Hello from Display Proposal Revision';
	}

	public function noRouteAction()
	{
		echo "No Route Action";
	}

	public function DisplayTestAction()
	{     	
		$view = new Hb_View_Proposal_TestDisplay();
		$view->setValue($this->getRequest()->getParam('test_1'));
		header('Content-type: text/xml'); 
		$view->Display();
	}

	/**
	* Display Account Assignee
	* 
	*/
	public function DisplayAssigneeAction()
	{
		$view 	 = new Hb_View_Proposal_ProposalDisplayAssignee();
		$view->setAssignee($this->getRequest()->getParam('assignee'));
		header('Content-type: text/xml');
		$view->Display();
	}

	/**
	* Add contacts in to the proposal summary section
	* 
	*/
	public function AddContactsAction()
	{    	    	
		$view 	 = new Hb_View_Proposal_ProposalAddContacts();
		$contacts = array('1' => 'Harsha Udayanga [harsha@gmi-mr.com]',
								'2' => 'Nayana Hettiarachchi [nayana@gmi-mr.com]',
								'3' => 'Jon Vonica [jonvonica@gmi-mr.com]',
								'4' => 'jahufar Sadique [jsadique@gmi-mr.com]' );
								
		$count = $this->getRequest()->getParam('count');
	
		for ($i= 1 ;$i <= $count; $i++) {    		
			$view->SetCotnact($contacts[$this->getRequest()->getParam('var'.$i)], $this->getRequest()->getParam('var'.$i));     		
		}   	
		
		$view->setValue($this->getRequest()->getParam('test_1'));
		header('Content-type: text/xml'); 
		$view->Display();
	}
	
	/**
	 * Add Proposal managment fees 
	 * 
	 */
	public function DisplayProjectManagementFeesAction()
	{
		$view 	 = new Hb_View_Proposal_ProposalDisplayManagmentFees();
		$view->SetDescription($this->getRequest()->getParam('description'));
		$view->SetHours($this->getRequest()->getParam('hours'));
		$view->SetFocusTab($this->getRequest()->getParam('focusTab'));
				
		$_SESSION['project_managment_fees'.$this->getRequest()->getParam('focusTab')] = array('description' => $this->getRequest()->getParam('description'), 
			'hours' => $this->getRequest()->getParam('hours'));
			
		
		header('Content-type: text/xml');
		$view->Display();
	}

	/**
	 * Display Proposal Managment fees light box with data
	 * 
	 */
	public function DisplayProjectManagmentFeesLightBoxAction()
	{
		$view 	 	= new Hb_View_Proposal_ProposalProjectManagmentFeesLightbox();
		$element_id = $this->getRequest()->getParam('element_id');
		
		if(isset($_SESSION[$element_id])) {
			$view->SetDescriptin($_SESSION[$element_id]['description']);
			$view->SetHours($_SESSION[$element_id]['hours']);
		}	
		
		header('Content-type: text/xml');
		$view->Display();
	}
	
    /**
	 * Add Vendor Consumer
	 * 
	 */
	public function AddVendorAction()
	{ 

		$view = new Hb_View_Proposal_ProposalVendorAdd();
		
		$mode = $this->getRequest()->getParam('mode');
		
		
		$focus_tab = $this->getRequest()->getParam('focusTab');		
		$view->setValue($this->getRequest()->getParam('vendor_info'),$focus_tab);
		$view->SetMode($mode);					
		$vendor_info = explode(",",$this->getRequest()->getParam('vendor_info'));			
		
		if($mode == 'add') {
			$time = time();
			$_SESSION[$vendor_info[4].$time.$focus_tab]['row_id']         		= $time;			
			$view->SetRowId($time);
		}else {
			$time = $this->getRequest()->getParam('row_id');
			$view->SetRowId($time);
		}
		
		$_SESSION[$vendor_info[4].$time.$focus_tab]['vendor_name']         = $vendor_info[0];
		$_SESSION[$vendor_info[4].$time.$focus_tab]['contact_name'] 	    = $vendor_info[1];
		$_SESSION[$vendor_info[4].$time.$focus_tab]['cost_per_complete']   = $vendor_info[5];
		$_SESSION[$vendor_info[4].$time.$focus_tab]['number_of_completes'] = $vendor_info[3];
		$_SESSION[$vendor_info[4].$time.$focus_tab]['sample_cost_tab'] 	 = $vendor_info[4];
		
		header('Content-type: text/xml');
		$view->Display();		
	}	
   
    /**
	 * Display Account details
	 * 
	 */
	public function DisplayAccountDetailsAction()
	{
		$account_id = $this->getRequest()->getParam('account_id');
		
		$account = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($account_id);		
		try {
			$attribuite = $account->GetAttribute('GLOBAL_PREFFERED_CURRENCY');
		}catch (Hb_Data_ObjectNotInCollectionException $e) {
			$attribuite = new Hb_App_Account_AccountAttribute(null, $account_id ,'GLOBAL_PREFFERED_CURRENCY' , ACM_DEFAULT_CURRENCY);			
		}	
		
		$currency = Hb_App_ObjectHelper::GetMapper('Hb_Mapper_Common_Currency')->FindByCode($attribuite->GetAttributeValue());
		
		$view = new Hb_View_Proposal_ProposalDisplayAccountDetails();
		$view->SetAccountExecutive($this->getRequest()->getParam('account_ae'));
		$view->SetAccountManager($this->getRequest()->getParam('account_mgr'));
		
		$view->SetAccountId($this->getRequest()->getParam('account_id'));
		$view->SetExchangeRate($currency->GetExchangeRate());
		//print_r($currency->GetExchangeRate()); die();
		$view->SetAccountCurrency($currency);		
		
		$view->SetAccountName($account->GetAccountName());
		$view->SetProposalWriter($_SESSION['name']);
		header('Content-type: text/xml'); 
    	$view->Display();
		
	}
	
	/**
	 * Display Data Processing Fees
	 *
	 */
	public function DisplayDataProcessingFeesAction()
	{
		$focus_tab = $this->getRequest()->getParam('focusTab');
		$view = new Hb_View_Proposal_ProposalDisplayDataProcessingFees();
		
		$_SESSION['add_data_processing_'.$focus_tab]['hours'] 		= $this->getRequest()->getParam('hours');
		$_SESSION['add_data_processing_'.$focus_tab]['languages'] 	= $this->getRequest()->getParam('languages');
		$_SESSION['add_data_processing_'.$focus_tab]['words'] 		= $this->getRequest()->getParam('words');
		
		
		$view->SetProcessingValue($this->getRequest()->getParam('process_val'));
		$view->SetHours($this->getRequest()->getParam('hours'));
		$view->SetFocusTab($focus_tab);
		
		header('Content-type: text/xml'); 
    	$view->Display();
	}
	
	/**
	 * Display Edit Data Processing Light box
	 * 
	 */
	public function DisplayEditDataProcessingLightBoxAction()
	{
		$view = new Hb_View_Proposal_ProposalEditDataProcessingLightBox();
		$element_id = $this->getRequest()->getParam('element_id');
		
		$view->SetHours($_SESSION[$element_id]['hours']);
		$view->SetLanguages($_SESSION[$element_id]['languages']);
		$view->SetWords($_SESSION[$element_id]['words']);
		
		header('Content-type: text/xml'); 
    	$view->Display();
	}
	
	/**
	 * Display Edit Vendor Light box
	 * 
	 */
	public function DisplayEditVendorLightBoxAction()
	{
		$view = new Hb_View_Proposal_ProposalEditVendorLightBox();
		$element_id = $this->getRequest()->getParam('element_id');		
			
		$view->SetRowId($_SESSION[$element_id]['row_id']);
		$view->SetVendorName($_SESSION[$element_id]['vendor_name']);
		$view->SetVendorContact($_SESSION[$element_id]['contact_name']);
		$view->SetCostPerComplete($_SESSION[$element_id]['cost_per_complete']);
		$view->SetNumberOfCompletes($_SESSION[$element_id]['number_of_completes']);	
		$view->SetSampleCostTabId($_SESSION[$element_id]['sample_cost_tab']);
				
		header('Content-type: text/xml'); 
    	$view->Display();
	}

	/**
	 * Add Proposal other service
	 * 
	 */
	public function AddProposalOtherServiceAction()
	{
		$view = new Hb_View_Proposal_ProposalAddOtherService();	

		$view->SetServiceName($this->getRequest()->getParam('service_name'));
		$view->SetServiceHours($this->getRequest()->getParam('hours'));
		$view->SetSelId($this->getRequest()->getParam('selId'));
		$view->SetAction($this->getRequest()->getParam('doAction'));
		$view->SetFocusTab($this->getRequest()->getParam('focusTab'));
		
		header('Content-type: text/xml'); 
    	$view->Display();
	}

	/**
	 * Add Proposal sample fees
	 * 
	 */
	public function AddProposalSampleFeesAction()
	{	
		
		$view = new Hb_View_Proposal_ProposalAddSampleFees();		
		
		$countries   = $this->getRequest()->getParam('country')	;		
		$arr_country = explode(',',$countries) ;			
		$view->SetArrCountry($arr_country);
		
		$arr_row_id = array() ;
		foreach ($arr_country as $index => $country)
		{
			$suffix = '_'.$index ;
			
			$view->SetCountry($country);
			$view->SetType($this->getRequest()->getParam('type'));
			$view->SetFocusTab($this->getRequest()->getParam('focusTab'));	
			$view->SetAction($this->getRequest()->getParam('mode'));	
			
					
			if($this->getRequest()->getParam('mode') == 'add') {
				$time = time();
				$time = $time.$suffix ;
			}else {
				$time = $this->getRequest()->getParam('row_id');
			}
			
			$arr_row_id[$index] = $time ;
			
			$view->setRowId($time);				
			
			if($this->getRequest()->getParam('incidence') != '') {			
				$view->SetDescription($this->getRequest()->getParam('sample_des'));
				$view->SetIncidence($this->getRequest()->getParam('incidence'));
				$view->SetTotalNumberofQuestions($this->getRequest()->getParam('total_no_questions'));	
				$view->SetTotalNumberofSamples($this->getRequest()->getParam('tot_number_of_samples'));			
				
				$view->SetSampleType('consumer');	
				$_SESSION['sample_fees_consumer'.$time.$this->getRequest()->getParam('focusTab')]['country'] 	=  $country;
				$_SESSION['sample_fees_consumer'.$time.$this->getRequest()->getParam('focusTab')]['incidence'] 	=  $this->getRequest()->getParam('incidence');
				$_SESSION['sample_fees_consumer'.$time.$this->getRequest()->getParam('focusTab')]['total_no_questions'] 	=  $this->getRequest()->getParam('total_no_questions');
				$_SESSION['sample_fees_consumer'.$time.$this->getRequest()->getParam('focusTab')]['tot_number_of_samples'] 	=  $this->getRequest()->getParam('tot_number_of_samples');
				
				$_SESSION['sample_fees_consumer'.$time.$this->getRequest()->getParam('focusTab')]['sample_des'] =  $this->getRequest()->getParam('sample_des');	
				$_SESSION['sample_fees_consumer'.$time.$this->getRequest()->getParam('focusTab')]['row_id'] =  $time;	
				$view->SetTabNumber(0);
				
			}
			
			if($this->getRequest()->getParam('incidence_b2b') != '') 
			{
				$view->SetDescription($this->getRequest()->getParam('sample_des_b2b'));
				$_SESSION['sample_fees_b2b'.$time.$this->getRequest()->getParam('focusTab')]['country'] 	=  $country;
				$view->SetIncidence($this->getRequest()->getParam('incidence_b2b'));	
				$view->SetTotalNumberofQuestions($this->getRequest()->getParam('total_no_questions_b2b'));	
				$view->SetTotalNumberofSamples($this->getRequest()->getParam('tot_number_of_samples_b2b'));					
					
				$view->SetSampleType('b2b');		
				$_SESSION['sample_fees_b2b'.$time.$this->getRequest()->getParam('focusTab')]['incidence_b2b'] 	=  $this->getRequest()->getParam('incidence_b2b');
				$_SESSION['sample_fees_b2b'.$time.$this->getRequest()->getParam('focusTab')]['total_no_questions_b2b'] 	=  $this->getRequest()->getParam('total_no_questions_b2b');
				$_SESSION['sample_fees_consumer'.$time.$this->getRequest()->getParam('focusTab')]['tot_number_of_samples_b2b'] 	=  $this->getRequest()->getParam('tot_number_of_samples_b2b');
				
				$_SESSION['sample_fees_b2b'.$time.$this->getRequest()->getParam('focusTab')]['sample_des_b2b'] 	=  $this->getRequest()->getParam('sample_des');
				$_SESSION['sample_fees_b2b'.$time.$this->getRequest()->getParam('focusTab')]['row_id'] 	=  $time;
				$view->SetTabNumber(1);
        }
		}
		
		$view->SetArrRowId($arr_row_id);
		
    	header('Content-type: text/xml'); 
	   $view->Display();
	}
		
	/**
	 * Display Edit Sample Fees light box 
	 * 
	 */
	public function DisplayEditSampleFeesLightBoxAction()
	{ 
		$element_id = $this->getRequest()->getParam('element_id');
		
		$view = new Hb_View_Proposal_ProposalEditSampleFees();	
		
		if(isset($_SESSION[$element_id]['incidence'])) {
			$view->SetIncidanceRate($_SESSION[$element_id]['incidence']);
			$view->SetSampleDescription($_SESSION[$element_id]['sample_des']);
			$view->SetRowId($_SESSION[$element_id]['row_id']);
			$view->SetCountry($_SESSION[$element_id]['country']) ;
			
			$view->SetTotNumberOfQuestions($_SESSION[$element_id]['total_no_questions']) ;
			$view->SetTotNumberOfSamples($_SESSION[$element_id]['tot_number_of_samples']) ;
			$view->SetIncidence($_SESSION[$element_id]['incidence']) ;			
		}
		
		if(isset($_SESSION[$element_id]['incidence_b2b'])) {
			$view->SetIncidanceRateB2B($_SESSION[$element_id]['incidence_b2b']);
			$view->SetSampleDescriptionB2B($_SESSION[$element_id]['sample_des_b2b']);
			$view->SetRowId($_SESSION[$element_id]['row_id']);
			$view->SetCountry($_SESSION[$element_id]['country']) ;		
			
			$view->SetTotNumberOfQuestions($_SESSION[$element_id]['total_no_questions_b2b']) ;
			$view->SetTotNumberOfSamples($_SESSION[$element_id]['tot_number_of_samples_b2b']) ;
			$view->SetIncidence($_SESSION[$element_id]['incidence_b2b']) ;		
		}
		
		header('Content-type: text/xml'); 
    	$view->Display();
	}
		
	/**
	 * Display Consumer Grid
	 *
	 */
	public function DisplayAddConsumerGridAction()
	{
		$view = new Hb_View_Proposal_ProposalDisplayConsumerGrid();	
		
		$countries = $this->getRequest()->getParam('countries');
		$arr_country = explode(',',$countries);
		
		$view->SetArrCountry($arr_country) ;
		
		header('Content-type: text/xml'); 
    	$view->Display();
	}
	
	
	
	/**
	 * Get Account details
	 * 
	 */
	public function GetAccountDetailsAction()
	{
		$account_id = $this->getRequest()->getParam('account_id');
		$account = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($account_id);
		
		//get the credit limit value for the account
		try {
			$credit_limit_attr = $account->GetAttribute('ACM_CREDIT_LIMIT');
			$credit_limit = $credit_limit_attr->GetAttributeValue();			
		}catch (Hb_Data_ObjectNotInCollectionException $e) {
			$credit_limit = 0;
		}
		
		
		$view = new Hb_View_Proposal_ProposalGetAccountDetails();
		$view->SetCreditLimit($credit_limit);
		header('Content-type: text/xml'); 
    	$view->Display();
	}
	
	/**
	 * Add Programing fees 
	 * 
	 */
	public function AddProgrammingFeesAction()
	{
		$focus_tab 	= $this->getRequest()->getParam('focusTab');
		$display 	= array();
		
		$view = new Hb_View_Proposal_ProposalAddProgrammingFees();
		
		if($this->getRequest()->getParam('translation') > 0) {
			$_SESSION['programming_fees'.$focus_tab]['translation'] = $this->getRequest()->getParam('translation');
			$_SESSION['programming_fees'.$focus_tab]['q_length'] = $this->getRequest()->getParam('q_length');
			$_SESSION['programming_fees'.$focus_tab]['number_of_words'] = $this->getRequest()->getParam('number_of_words');
			$view->SetDisplay('translation');
			$view->SetTranslation($this->getRequest()->getParam('translation'));
		}
		
		if($this->getRequest()->getParam('hosting') > 0) {
			$_SESSION['programming_fees'.$focus_tab]['hosting'] = $this->getRequest()->getParam('hosting');
			$view->SetDisplay('hosting');
			$view->SetHosting($this->getRequest()->getParam('hosting'));
		}
		
		
		if($this->getRequest()->getParam('q_len_programming') > 0) {
			$_SESSION['programming_fees'.$focus_tab]['q_len_programming'] = $this->getRequest()->getParam('q_len_programming');
			$view->SetDisplay('programming');
			$view->SetQLenProgram($this->getRequest()->getParam('q_len_programming'));
			$view->SetComplexity($this->getRequest()->getParam('complexity'));
			$view->calculateProgramming();
		}
		
		if($this->getRequest()->getParam('dataexport') > 0) {
			$_SESSION['programming_fees'.$focus_tab]['dataexport'] = $this->getRequest()->getParam('dataexport');
			$_SESSION['programming_fees'.$focus_tab]['tab_hours'] = $this->getRequest()->getParam('tab_hours');
			$view->SetDisplay('dataexport');
			$view->SetDataExport($this->getRequest()->getParam('dataexport'));
		}
		$view->SetFocusTab($focus_tab);		
		header('Content-type: text/xml'); 
    	$view->Display();
	}
	
	/**
	 * Display Programming fees edit light box
	 * 
	 */
	public function DisplayEditProgrammingFeesLightBoxAction()
	{ 
		$element_id = $this->getRequest()->getParam('element_id');
		
		$view = new Hb_View_Proposal_ProposalEditProgrammingFeesLightBox();
		
		if(isset($_SESSION[$element_id]['translation'])) {
			$view->SetTranslation($_SESSION[$element_id]['translation']);
			$view->SetQuestioniareLength($_SESSION[$element_id]['q_length']);
			$view->SetNumberOfWords($_SESSION[$element_id]['number_of_words']);
		}
		
		if(isset($_SESSION[$element_id]['hosting'])) {					
			$view->SetHosting($_SESSION[$element_id]['hosting']);
			$view->SetQuestionLengthPrograming($_SESSION[$element_id]['q_len_programming']);			
		}
		
		if(isset($_SESSION[$element_id]['dataexport'])) {
			$view->SetDataExport($_SESSION[$element_id]['dataexport']);
			$view->SetTabHours($_SESSION[$element_id]['tab_hours']);
		}
		
		if(isset($_SESSION[$element_id]['q_len_programming'])) {
			$view->SetQuestionLengthPrograming($_SESSION[$element_id]['q_len_programming']);
		}
		
		if(isset($_SESSION[$element_id]['hours'])) {
			$view->SetManagementHours($_SESSION[$element_id]['hours']);
			$view->SetManagementDes($_SESSION[$element_id]['description']);
		}
		
		header('Content-type: text/xml'); 
    	$view->Display();
	}
	
	/**
	 * Download the Customer Credit Profile Excel document
	 * 
	 */
	public function DownloadCustomerCreditProfileAction()
	{
		$config = Hb_Util_Config_SystemConfigReader::Read();
     
	  	$cfg['base_dir'] = $config->base_dir;
		
		$file = $cfg['base_dir'].'/templates/app/pgen/ui_mocks/resources/Customer_Credit_Profile_Form.xls';	   
   
		header("Pragma: private"); // required
		header("Expires: ".date('r',time()+30));
		header("Content-Length: ".strlen($r['study_cost_file_data']));
		header("Content-Length: ".filesize($file));
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); // required for certain browsers
		header('Content-type: application/vnd.ms-excel');
		header("Content-disposition: filename=\"Customer_Credit_Profile_Form.xls\"");
		readfile($file) or die('file could not found');
	}
	
	public function LoadStudyCategoryChildAction()
	{
		$parent_id 	= $this->getRequest()->getParam('study_category_type_id');
		$level 		= $this->getRequest()->getParam('level');
		
		$study_categories = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_StudyCategoryTypes')->FindAllByParentId($parent_id);
		
		$view = new Hb_View_Proposal_ProposalLoadStudyCategorySubType();		
		$view->SetStudyCategoryTypes($study_categories);
		$view->SetStudyCategoryLevel($level);
		
		header('Content-type: text/xml'); 
    	$view->Display();
	}

}



?>
