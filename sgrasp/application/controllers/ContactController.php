<?php
include_once "db/accountDB.class.php";
require_once 'db/commonDB.class.php';
class ContactController extends Zend_Controller_Action
{

	public function init()
    {
        /* Initialize action controller here */
    	 /* Initialize action controller here */
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
        
        $registry = new Zend_Registry(array('smarty' => $smarty, 'db' => $db));
        Zend_Registry::setInstance($registry);
    }

    public function indexAction()
    {
        // action body
    }
    
    public function lookupAction()
    {
    	$account  = $this->getRequest()->getParam('account', false);
    	$lookup   = $this->getRequest()->getParam('name', false);
    	$callback = $this->getRequest()->getParam('callback', false);
    	if (!$lookup) return true;
		
    	$sql = "SELECT contact_id, CONCAT(contact_first_name, ' ', contact_last_name) AS contact_name "
   		     . "FROM contact "
   		     . "WHERE account_id = ?";
    	$db = Zend_Registry::get('db');
    	/* @var $db Zend_Db_Adapter_Mysqli */
    	 
    	$l = array();
    	$l['contacts'] = $db->fetchAll($sql, array($account));
    	
		$this->getResponse()->setHeader('Content-type', 'application/json');
		$this->_helper->viewRenderer->setNoRender(true);
    	printf("%s(%s)", $callback, Zend_Json::encode($l));
    }

    public function newAction()
    {
        $db = Zend_Registry::get('db');
        /* @var $db Zend_Db_Adapter_Mysqli */
        $id = $this->getRequest()->getParam('account_id', 0); 
        
        $this->view->country         = $db->fetchPairs("SELECT country_code, country_description FROM opstools.country");
    	$this->view->account_id      = $id;
    	$this->view->account         = $db->fetchRow("SELECT * FROM account WHERE account_id = ?", array($id));
    	$this->view->primary_contact = $db->fetchRow("SELECT * FROM contact WHERE account_id = ? AND contact_type_id = ?", array($id, CONTACT_TYPE_PRIMARY_BILLING));
    }
    
    public function navtreeAction()
    {
    	$this->getResponse()->setHeader('Content-Type', 'text/xml');
    }
    
    public function listAction()
    {
        $this->getResponse()->setHeader("Content-Type", "text/xml");
    }
    
    public function editAction()
    {
        $c = new commonDB();
         
        $this->view->country = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
         
        $db = Zend_Registry::get('db');
         
        /* @var $db Zend_Db_Adapter_Mysqli */
        $params = $this->getRequest()->getParams();
        
        $sql = "SELECT * FROM contact WHERE contact_id = ?";
        $this->view->contact = $db->fetchRow($sql, $params['id']);
        
        $viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRender->setScriptAction('new');
    }
    
    public function saveAction()
    {
    	$db = Zend_Registry::get('db');
    	
    	/* @var $db Zend_Db_Adapter_Mysqli */
    	$params = $this->getRequest()->getParams();
    	
    	$contact_type = 2;
    	if (isset($params['primary'])) {
    	    $contact_type = 1;
    	    $sql ="UPDATE contact SET contact_type_id = 2 WHERE contact_type_id = 1 AND account_id = ?";
    	    $db->query($sql, array($params['account_id']));
    	}
    	
    	if (isset($params['contact_id'])) {
    	    $sql = "UPDATE contact SET contact_type_id = ?, contact_first_name = ?, contact_last_name = ?, 
    	    contact_email = ?, contact_phone = ?, contact_country = ?, street1 = ?, city = ?, state = ?, postalcode = ?, modified_by = ?, modified_date = NOW(), status = 'A'
    	    WHERE contact_id = ? ";
    	    $stmt = $db->query($sql, array(
    	    		$contact_type,
    	    		$params['firstname'],
    	    		$params['lastname'],
    	    		$params['email'],
    	    		$params['phone'],
    	    		$params['country_code'],
		    	    $params['street1'],
    	    		$params['city'],
    	    		$params['stateprovince'],
    	    		$params['postalcode'],
    	    		$_SESSION['admin_id'], $params['contact_id']));
    	} else {
	    	$sql = "INSERT INTO contact (account_id, contact_type_id, contact_first_name, contact_last_name, contact_email, contact_phone, contact_country, 
	    						street1, city, state, postalcode, created_by, created_date, status) "
	    			. "VALUES (?,?,?,?,?,?,?,?,?,?,?,?,NOW(),'A')";
	    	$stmt = $db->query($sql, array(
	    					$params['account_id'],
	    					$contact_type,
	    					$params['firstname'],
	    					$params['lastname'],
	    					$params['email'],
	    					$params['phone'],
	    					$params['country_code'],
	    					$params['street1'],
	    					$params['city'],
	    					$params['stateprovince'],
	    					$params['postalcode'],
	    					$_SESSION['admin_id']));
    	}
    	
    	//TODO: test to see the service template type of the Platform Account
    	// then decide the next screen type
    	
    	$this->_helper->redirector->setCode(301);
    	$this->_redirect('/Account/view/id/' . $params['account_id']);
   }

}

