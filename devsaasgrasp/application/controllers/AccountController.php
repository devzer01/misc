<?php

require_once 'db/accountDB.class.php';
require_once 'db/commonDB.class.php';
require_once 'app/acm_functions.inc';

class AccountController extends Zend_Controller_Action
{

	public $view = NULL;
	
    public function init()
    {
        $front = Zend_Controller_Front::getInstance();
         
        $front->setParam('noErrorHandler', true);
        $front->throwExceptions(true);
         
        $this->host = $_SERVER['HTTP_HOST'];
        $this->path = $_SERVER['DOCUMENT_ROOT'];
         
        //printf("Path %s", $this->path);
        $this->view   = new Zend_View_Smarty($this->path . '/../application/views/scripts');
    	$viewRenderer = $this->_helper->getHelper('viewRenderer');
        $viewRenderer->setView($this->view)
                     //->setViewBasePathSpec($this->view->template_dir)
                     ->setViewScriptPathSpec(':controller/:action.:suffix')
                     ->setViewScriptPathNoControllerSpec(':action.:suffix')
                     ->setViewSuffix('tpl');
                     
        $config = Hb_Util_Config_SystemConfigReader::Read('smarty'); 
		$smarty = new Smarty();
		$smarty->template_dir = $this->path . '/../application/views/scripts/';
        $smarty->compile_dir  = '/var/tmp';
        $smarty->plugins_dir[] = $this->path . '/../library/smarty_plugins/';
		
        $config = new Zend_Config(
        		array(
        				'database' => array(
        						'adapter' => 'Mysqli',
        						'params'  => array(
        								'host'     => '127.0.0.1',
        								'dbname'   => $_SESSION['system'],
        								'username' => 'root',
        								'password' => '',
        						)
        				)
        		)
        );
         
        $db = Zend_Db::factory($config->database);
        
        $registry = Zend_Registry::getInstance();
        $objects = array('smarty' => $smarty, 'db' => $db);
        
        if ($registry === NULL) {
        
        	$registry = new Zend_Registry($objects);
        	Zend_Registry::setInstance($registry);
        } else {
            $registry->set('db', $db);
        }
        
        $this->view->bgcolor = '99FF99';
        $this->view->session = $_SESSION;
    }
    
    /**
     * 
     */
    public function searchAction()
    {
    	if ($this->getRequest()->getParam('dosearch', false) == false) {
    	    return true;
    	}    
    	
    	$db = Zend_Registry::get('db');  	
    	/* @var $db Zend_Db_Adapter_Mysqli */
    	$params = $this->getRequest()->getParams();
    	$sql = "SELECT account_id FROM account WHERE account_name LIKE ?";
    	$results = $db->fetchAssoc($sql, array($this->getRequest()->getParam('an', '%test%')));
    	
    	$this->_forward('list', null, null, array('results' => $results));
    	
//     	$this->_helper->redirector->setCode(301);
//     	$this->_redirect('/quote/detail/quote/' . $quote_id);
    }
    
    public function savefileAction()
    {
        $db = Zend_Registry::get('db');
        /* @var $db Zend_Db_Adapter_Mysqli */
        
        $params = $this->getRequest()->getParams();
        
        $filedata = file_get_contents($_FILES['af']['tmp_name']);
        $filename = $_FILES['af']['name'];
        $filetype = $_FILES['af']['type'];
        $filesize = $_FILES['af']['size'];
         
        
        $sql = "INSERT INTO account_file (account_id, account_file_type_id, account_file_type, 
        	account_file_name, account_file_title, account_file_data, 
        account_file_size, created_by, created_date, status) VALUES (?,?,?,?,?,?,?,?,NOW(), 'A') ";
        $db->query($sql, array(
        	$params['id'], 1, $filetype, $filename, $params['title'], $filedata, $filesize, $_SESSION['admin_id']
        ));

        $this->_helper->redirector->setCode(301);
        $this->_redirect('/account/view/id/' . $params['id']);
        
    }
    
    /**
     * 
     */
    public function newfileAction()
    {
        $db = Zend_Registry::get('db');
        /* @var $db Zend_Db_Adapter_Mysqli */
        
        $id = $this->getRequest()->getParam('id', 0);
         
        $this->view->account = $db->fetchRow("SELECT * FROM account WHERE account_id = ?", array($id));
        $this->view->primary_contact = $db->fetchRow("SELECT * FROM contact WHERE account_id = ? AND contact_type_id = ?", array($id, CONTACT_TYPE_PRIMARY_BILLING));
        $this->view->country = $db->fetchAssoc("SELECT country_code, country_description FROM opstools.country");
         
        
    }
    
    /**
     * 
     */
    public function settingAction()
    {
    	$id = $this->getRequest()->getParam('id', 0);
    	$a = new accountDB();
    	$a->SetAccountId($id);
    	$this->view->attrdef = PrepareSmartyArray($a->GetAccountAttrDef(), 'account_attr_name');
    	$this->view->attr = CreateSmartyArray($a->GetAccountAttr(), 'account_attr_name', 'account_attr_value');
    }
    
    public function saveSettingAction()
    {
    	
    }
    
    /**
     * 
     */
    public function validateAction()
    {
    	
    	$lookup   = $this->getRequest()->getParam('name', false);
    	$callback = $this->getRequest()->getParam('callback', false);
    	if (!$lookup) return true;	
    	
    	$sql = "SELECT account_id, account_name FROM account WHERE account_name LIKE ?";
    	$db = Zend_Registry::get('db');
    	/* @var $db Zend_Db_Adapter_Mysqli */
    	
    	$l = array();
    	$l['accounts'] = $db->fetchAll($sql, array('%' . $lookup . '%'));
    	
		$this->getResponse()->setHeader('Content-type', 'application/json');
		$this->_helper->viewRenderer->setNoRender(true);
    	printf("%s(%s)", $callback, Zend_Json::encode($l));
    }

    public function indexAction()
    {
    	
    }
    
    public function listsubtypeAction()
    {
    	$a = new accountDB();
	
    	$account_type_id = $this->getRequest()->getParam('type', 0);
    	
		$account_type_id = explode(",", $account_type_id);
	
		for ($i=0; $i < count($account_type_id); $i++) {
			if ($account_type_id[$i] != '') {
				$rs = $a->GetAccountSubTypeByType($account_type_id[$i]);	
				
				while ($r = mysql_fetch_assoc($rs)) {
					$sub_type[] = $r;
				}
				
			}
		}
	
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
		
		
		$this->view->sub_type = $sub_type;
		$viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
   		$viewRender->setScriptAction('xml_account_sub_type');
	}
    
	/**
	 * 
	 */
	public function savecontactAction()
	{
	    $id = $this->getRequest()->getParam('id', 0);
	    $db = Zend_Registry::get('db');
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    
	    $params = $this->getRequest()->getParams();
	    
	    $sql = "UPDATE contact SET company_name = ?, street1 = ?, city = ?, state = ?, postalcode = ?, country_code = ?, modified_by = ?, modified_date = NOW() "
	         . "WHERE account_id = ? AND contact_type_id = ? ";
	    $db->query($sql, array($params['company_name'], $params['street1'], 
	    $params['city'], $params['stateprovince'], $params['postalcode'], 
	    $params['country_code'], $_SESSION['admin_id'], $params['id'], CONTACT_TYPE_PRIMARY_BILLING));
	    
		$this->_helper->redirector->setCode(301);
	    $this->_redirect('/account/view/id/' . $id);
	    	  
	}
	
	/**
	 * 
	 */
	public function saveuserAction()
	{
		$a = new accountDB();
		$a->SetAccountId($this->getRequest()->getParam('id', 0));

		$product = array();
	
		$c = new commonDB();

		$rs = $c->GetProductList();
	
		while ($r = mysql_fetch_assoc($rs)) {
			$product[$r['product_id']] = array();
		}
	
		for ($i=0; $i< 10; $i++) {
		
			if ($_POST['role_id_'. $i] == ROLE_PRIMARY_ACCT_EXEC && isset($product[$_POST['product_id_'. $i]][ROLE_PRIMARY_ACCT_EXEC])) {
				$_POST['role_id_'. $i] = ROLE_SECONDARY_ACCT_EXEC;
			} elseif ($_POST['role_id_' . $i] == ROLE_PRIMARY_ACCT_MGR && isset($product[$_POST['product_id_'. $i]][ROLE_PRIMARY_ACCT_MGR])) {
				$_POST['role_id_'. $i] = ROLE_SECONDARY_ACCT_MGR;
			}
		
			$product[$_POST['product_id_' . $i]][$_POST['role_id_'. $i]] = 1;
		
			if ($_POST['login_'.$i] != '') {
				if (isset($_POST['account_user_id_'.$i])) {
					$a->UpdateUserRole($_POST['account_user_id_'.$i], $_POST['product_id_' . $i], $_POST['role_id_'. $i], $_POST['login_'. $i]);
				} else {
					$a->SetUserRole($_POST['product_id_' . $i], $_POST['role_id_'. $i], $_POST['login_'. $i]);
				}
			}		
		}

		$this->_helper->redirector->setCode(301);
        $this->_redirect('/Account/setattr/id/' . $_POST['account_id']);
	}
	
	public function viewAction()
	{
		$id = $this->getRequest()->getParam('id', 0);
		$db = Zend_Registry::get('db');
		 
		/* @var $db Zend_Db_Adapter_Mysqli */
		$params = $this->getRequest()->getParams();
		$sql = "SELECT * FROM contact WHERE account_id = ?";
		$this->view->primary_contact = $db->fetchRow("SELECT * FROM contact WHERE account_id = ? AND contact_type_id = ?", array($id, CONTACT_TYPE_PRIMARY_BILLING));
		$this->view->contacts = $db->fetchAssoc($sql, array($id));		
		$this->view->country = $db->fetchAssoc("SELECT country_code, country_description FROM opstools.country");
	    $this->view->account = $db->fetchRow("SELECT * FROM account WHERE account_id = ?", array($id));
		
	    $this->view->quotes = $db->fetchAssoc("SELECT * FROM proposal WHERE account_id = ?", array($id));
	    $this->view->projects = $db->fetchAssoc("SELECT * FROM project WHERE account_id = ?", array($id));
	    $this->view->invoices = $db->fetchAssoc("SELECT * FROM armc WHERE account_id = ?", array($id));
	    $this->view->files = $db->fetchAssoc("SELECT * FROM account_file WHERE account_file_type_id = 1 AND account_id = ?", array($id));
	     
	    
	    $sql = "SELECT * FROM account_comment WHERE account_id = ? AND account_comment_type_id = ?";
	    $this->view->accountmemo = $db->fetchRow($sql, array($id, ACCOUNT_COMMENT_TYPE_GLOBAL_MEMO));
	    
// 		$rsswy = 'http://weather.yahooapis.com/forecastrss?w=2502265';
// 		$feed = new Zend_Feed_Rss($rsswy);
// 		$this->view->wy = $feed->current();
		
	
	}
	
	public function contactAction()
	{
	    $id = $this->getRequest()->getParam('id', 0);
	    $db = Zend_Registry::get('db');
	    
	    $this->view->account = $db->fetchRow("SELECT * FROM account WHERE account_id = ?", array($id));
	    $this->view->primary_contact = $db->fetchRow("SELECT * FROM contact WHERE account_id = ? AND contact_type_id = ?", array($id, CONTACT_TYPE_PRIMARY_BILLING));
	    $this->view->country = $db->fetchAssoc("SELECT country_code, country_description FROM opstools.country");
	    
	    $sql = "SELECT * FROM contact WHERE account_id = ? AND contact_type_id = ? ";
	    $this->view->contact = $db->fetchRow($sql, array($id, CONTACT_TYPE_PRIMARY_BILLING));
	}
	
	/**
	 * 
	 */
	public function downloadAction()
	{
	    $viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
	    $viewRender->setNoRender(true);
	     
	    $id = $this->getRequest()->getParam('id', 0);
	    $db = Zend_Registry::get('db');
	     
	    $sql = "SELECT account_file_name, account_file_type, account_file_data FROM account_file WHERE account_file_id = ?";
	    $file = $db->fetchRow($sql, array($id));
	     
	    $this->getResponse()->setHeader('Content-Type', $file['account_file_type']);
	    $this->getResponse()->setHeader('Content-Disposition', 'attachment; filename="' . $file['account_file_name'] . '"');
	    print $file['account_file_data'];
	}
	
	/**
	 * 
	 */
	public function editcontactAction()
	{
		 
	}
	
	/**
	 * 
	 */
	public function setattrAction()
	{
		$template  	= 'app/acm/vw_account_attr_vendor.tpl';
	
		$common 		= new commonDB();	
		$acc 			= new accountDB();
		$acc->SetAccountId($this->getRequest()->getParam('id', 0));
	
		$account 				= $acc->GetAccountDetail();
		$list['currency'] 	= CreateSmartyArray($common->GetCurrencyList(), 'currency_code', 'currency_description');	
		$meta['new_account']	= 1;
		$meta['customer_type']	= 0;
		$display['preferred_currency'] = 1;
	
		//check whether account type is customer or perspect type
		//check whether security is set
		//$security 		= CheckSecurityKey($_SESSION['admin_id'], GetSecurityKeys());		
		$security = array();
		$this->view->security = $security;		
		//get account attribtes that security key is set
		$security_account	= GetAccountAttributeSetForScurity();
		$account			= array_merge($account, $security_account);
		
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
		
		$template = 'vw_attr';	
	
		$this->view->display = $display;
		$this->view->account = $account;
		$this->view->list    = $list;	
		$this->view->meta    = $meta;
		
		$viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
   		$viewRender->setScriptAction('vw_attr');
	}
	
	public function saveaccountattributesAction()
	{
	   	$account_id = $this->getRequest()->getParam('id');
		$account    = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($account_id);
		//$account_status = $account->GetAudit()->GetStatus();
		    
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
			//header("Location:/app/Account/DisplayRecruiterAccountDetails/account_id/". $account_id);
			return false;	
		}
		
		$this->getResponse()->setRedirect("/Account/view/id/". $account_id);    	
	}
	
	public function userlookupAction()
	{
		$rowid = $this->getRequest()->getParam('rowid', 0);
		$name = $_POST['name_' . $rowid];
	
		$c = new commonDB();
	
		$rs = $c->GetUsersByLogin($name);
	
		while ($r = $c->FetchAssoc($rs)) {
			$data[] = $r;
		}
	
	
		$rs = $c->GetUsersByName($name);
	
		while ($r = $c->FetchAssoc($rs)) {
			$data[] = $r;
		}
	
		$this->view->data = $data;
	
		//$this->getResponse()->setHeader('Content-Type', 'text/xml');
		$viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
   		$viewRender->setScriptAction('ul_user');
		//header("Content-type: text/xml");
		//$smarty->display('app/acm/ul_matching_users.tpl');
	}
	
	public function setuserAction()
	{
		$a = new accountDB();
		$a->SetAccountId($this->getRequest()->getParam('id', 0));
	
		$c = new commonDB();
		$u = new userDB();
	
		$account = $a->GetAccountDetail();
		$account['new_account'] = 1;
	
		//if ($o['new_account'] == 0) {
			//get current assingments
		//	$account['user'] = PrepareSmartyArray($a->GetAccountUsers());
		//	$account['new_account'] = 0;
		//}
	
	//accoun type id
	$r_account_type_id          = $a->FetchAssoc($a->GetAccountType());
	$account['account_type_id'] = $r_account_type_id['account_type_id'];
	
	$module_code = 'acm';
	
	if ($account['account_type_id'] == ACCOUNT_TYPE_VENDOR) {
		$module_code = 'vem';
	}
	
	$this->view->account = $account;
	
	/* lets hack this for now */
	$list['role'] = CreateSmartyArray($c->GetRoleListByModule($module_code), 'role_id', 'role_description');
	$list['product'] = CreateSmartyArray($c->GetProductList(), 'product_id', 'product_description');	
	$list['user'] = CreateSmartyArray($u->GetUserList('A'), 'login', 'name');
	
	$this->view->list = $list;
	
		$viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
   		$viewRender->setScriptAction('vw_userrole');
	}
	
	public function newcontactAction()
	{
		$a = new accountDB();
		$account_id = $this->getRequest()->getParam('id', 0);
		$a->SetAccountId($account_id);
		$account = $a->GetAccountDetail();
		$account['account_type']     = PrepareSmartyArray($a->GetAccountType());
		$account['account_sub_type'] = PrepareSmartyArray($a->GetAccountSubType());
	
		$c = new commonDB();
		$list['country'] = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
		$list['contact_type'] = CreateSmartyArray($c->GetContactTypes(), 'contact_type_id', 'contact_type_description');
		$list['title'] = array('MR.', 'MRS.', 'MS.');
		$list['portal_access_type'] = array(1 => 'No Access', 2 => "User", 3 => "Administrator");
		
		$template_attr = GetFormValidationAttr('vw_account_contact');
	
		//$account_obj = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($account_id);
		//$account_term = $account_obj->GetAccountTerm();
	
		$display['view_credit_hold_status'] = 0;	
		if(($a->isAccountType(ACCOUNT_TYPE_CUSTOMER) || $a->isAccountType(ACCOUNT_TYPE_PROSPECT))){
	   		$display['view_credit_hold_status'] = 1;	  	
		}

	
		if(!isset($_SESSION['ACM_MANAGE_BILLING_CONTACT']))
  		{
		    unset($list['contact_type'][CONTACT_TYPE_PRIMARY_BILLING]);
		    unset($list['contact_type'][CONTACT_TYPE_BILLING]);
		}

		//$this->view->account_term = $account_term;
		$this->view->display = $display;
		$this->view->account = $account;
		$this->view->meta    = $_POST;
		$this->view->list    = $list;
		$this->view->template_attr = $template_attr;
		
		$template = 'vw_account_contact';
	
		if ($_SESSION['user_type_id'] == USER_TYPE_EXTERNAL && $_SESSION['is_vendor'] == 0) {
	  		$template = '/ext/vw_account_contact';   
		}

		$viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
   		$viewRender->setScriptAction($template);
   		
	}
	
    public function typeAction()
    {
    	if (isset($_SESSION['acm_message']) && $_SESSION['acm_message'] != '') {
			$meta['message'] = $_SESSION['acm_message'];
			unset($_SESSION['acm_message']);
		}
		$meta = $_POST;
		$a = new accountDB();
		$account_id = $this->getRequest()->getParam('id', 0);
		$a->SetAccountId($account_id);
	
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
  
		$this->view->account = $account;
		$this->view->list    = $list;
		$this->view->meta    = $meta;
		
		//DisplayHeader("Account Manager", "acm");
		$viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
   		$viewRender->setScriptAction('vw_set_account_type');
   		
		//$smarty->display('app/acm/vw_set_account_type.tpl');
    	
    }
	
	public function newAction()
	{
		$a = new accountDB();
   		$c = new commonDB();
   
   		$list['account_type'] = CreateSmartyArray($a->GetAccountTypeList(), 'account_type_id', 'account_type_description');
   		$list['country'] = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
   		$list['account_sub_type'] = CreateSmartyArray($a->GetAccountSubTypeList(), 'account_sub_type_id', 'account_sub_type_description');
   
   		$this->view->list = $list;
		
	}
	
	/**
	 * 
	 */
	public function listAction()
	{
	    $results = $this->getRequest()->getParam('results', false);
	    
	    $sql = "SELECT * FROM account";
	    if ($results) {
	        $ids = array_keys($results);
	        //$sql = "SELECT * FROM account WHERE account_id IN (?)";
	        $sql = "SELECT * FROM account WHERE account_id IN (" . mysql_real_escape_string(implode(",", $ids)) .")";
	    }
	    
	    $db = Zend_Registry::get('db');
	     /* @var $db Zend_Db_Adapter_Mysqli */
	    
	    
	    
	    $this->view->accounts = $db->fetchAssoc($sql);
	    $this->view->at       = $db->fetchAssoc("SELECT account_type_id, account_type_description FROM opstools.account_type");
	  	$this->view->country  = $db->fetchAssoc("SELECT country_code, country_description FROM opstools.country");
 	    $this->view->user     = $db->fetchAssoc("SELECT user_id, login, last_name FROM user");
	  	//$db->select()->from('account', '*')->where("account_id IN (?)", implode(",", $ids));
	    
	    //$this->view->accounts = $db->fetchAssoc($sql, $ids);
		//$this->getResponse()->setHeader("Content-Type", "text/xml");
	}

	public function menuAction()
	{
		$this->getResponse()->setHeader("Content-Type", "text/xml");
	}
	
	public function toolbarAction()
	{
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
	}
	
	public function treeAction()
	{
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
	}
	
	/**
	 * 
	 */
	public function saveforceAction()
	{
		ForceSaveAccount($_POST);	
		$viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
   		$viewRender->setNoRender(true);
	}
	
	/**
	 * 
	 */
	public function saveAction()
	{
	    //session_start();
	    $db = Zend_Registry::get('db');
	     
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    $params = $this->getRequest()->getParams();
	    $sql = "INSERT INTO account (account_name, account_type_id, country_code, created_by, created_date, status) "
	         . "VALUES (?,?,?,?,NOW(), 'A') ";

	    $db->query($sql, array($params['account_name'], $params['account_type_id'], $params['country_code'], $_SESSION['admin_id']));
	    
	    $account_id = $db->lastInsertId();
	    
	    $sql = "INSERT INTO contact (account_id, contact_type_id, contact_first_name, contact_last_name, 
	    			contact_email, contact_phone, country_code, created_by, created_date, status) "
	         . "VALUES (?,?,?,?,?,?,?,?, NOW(), 'A') ";
	    
	    $db->query($sql, array($account_id, CONTACT_TYPE_PRIMARY_BILLING, $params['contact_first_name'], $params['contact_last_name'], $params['contact_email'], 
	    	$params['contact_phone'], $params['country_code'], $_SESSION['admin_id']));
	    
	    $this->_helper->redirector->setCode(301);
        $this->_redirect('/Account/view/id/' . $account_id);
	}
	
	/**
	 * 
	 */
	public function memoAction()
	{
	    $id = $this->getRequest()->getParam('id', 0);
	    $db = Zend_Registry::get('db');
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    
	    $this->view->account = $db->fetchRow("SELECT * FROM account WHERE account_id = ?", array($id));
	    $this->view->primary_contact = $db->fetchRow("SELECT * FROM contact WHERE account_id = ? AND contact_type_id = ?", array($id, CONTACT_TYPE_PRIMARY_BILLING));
	    $this->view->country = $db->fetchAssoc("SELECT country_code, country_description FROM opstools.country");
	     
	    
	    $sql = "SELECT * FROM account_comment WHERE account_id = ? AND account_comment_type_id = ?";
	    $this->view->accountmemo = $db->fetchRow($sql, array($id, ACCOUNT_COMMENT_TYPE_GLOBAL_MEMO));
	    	  
	}
	
	/**
	 * 
	 */
	public function savememoAction()
	{
	    $id = $this->getRequest()->getParam('id', 0);
	    $db = Zend_Registry::get('db');
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    
	    $params = $this->getRequest()->getParams();
	    
	    $sql = "SELECT * FROM account_comment WHERE account_id = ? AND account_comment_type_id = ?";
	    $row = $db->fetchRow($sql, array($id, ACCOUNT_COMMENT_TYPE_GLOBAL_MEMO));
		if ($row) {
		    $sql = "UPDATE account_comment SET comment_text = ?, modified_by = ?, modified_date = NOW() WHERE account_id = ? AND account_comment_type_id = ?";
			$db->query($sql, array($params['accountmemo'], $_SESSION['admin_id'], $id, ACCOUNT_COMMENT_TYPE_GLOBAL_MEMO));
		} else {
		    $sql = "INSERT INTO account_comment (account_id, account_comment_type_id, comment_text, created_by, created_date, status) "
		         . "VALUES (?,?,?,?, NOW(), 'A')";
		    $db->query($sql, array($id, ACCOUNT_COMMENT_TYPE_GLOBAL_MEMO, $params['accountmemo'], $_SESSION['admin_id']));
		}
		
		$this->_helper->redirector->setCode(301);
		$this->_redirect('/Account/view/id/' . $id);
	}

}

