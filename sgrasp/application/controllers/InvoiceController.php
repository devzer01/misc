<?php

require_once "db/studyDB.class.php";
require_once "db/atmDB.class.php";
require_once "db/commonDB.class.php";
require_once "db/accountDB.class.php";
require_once "lang/en_US.inc";
require_once "app/armc_functions.inc";

class InvoiceController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
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
        $smarty->force_compile = true;
        $smarty->compile_check = true;
        
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
        
        $this->view->bgcolor = 'FF9999';
    }

    public function indexAction()
    {
        // action body
    }
    
	public function newAction()
	{
		
	}
	
	/**
	 * 
	 */
	public function saveAction()
	{
	    $db = Zend_Registry::get('db');
	    
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    $params = $this->getRequest()->getParams();
	    $stmt = $db->query("INSERT INTO armc (
	    		account_id, account_name, invoice_name, armc_status_id,
	    		created_by, created_date, status) "
	    		. "VALUES (?,?,?,?,?,NOW(),'A')", array(
	    				$params['account_id'],
	    				$params['account_name'],
	    				$params['invoice_name'],
	    				ARMC_STATUS_READY_FOR_INVOICE,
	    				$_SESSION['admin_id']));
	    
	    $sql = "SELECT * FROM contact WHERE contact_id = ? ";
	    $contact = $db->fetchRow($sql, array($params['contact_id']));
	    
	    $invoice_id = $db->lastInsertId();
	    
	    $sql = "INSERT INTO armc_contact (armc_id, contact_id, contact_name, first_name, last_name, 
	    	address_1, city, province, zip, country_code, email, phone, created_by, created_date, status) "
	    . "VALUES (?,?,?,?, NOW(), 'A') ";
	    
	    $db->query($sql, array($invoice_id, $params['contact_id'], $params['contact_name'], 
	    	$contact['contact_first_name'], $contact['contact_last_name'], $contact['street1'], $contact['city'], 
	    	$contact['state'], $contact['postalcode'], $contact['country_code'], $contact['contact_email'], 
	    	$contact['contact_phone'], $_SESSION['admin_id']));
	    
	    //TODO: test to see the service template type of the Platform Account
	    // then decide the next screen type
	    
	    $this->_helper->redirector->setCode(301);
	    $this->_redirect('/invoice/detail/invoice/' . $invoice_id);
	}
	
	/**
	 * 
	 */
	public function detailAction()
	{
	    $db = Zend_Registry::get('db');
	    
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    $id = $this->getRequest()->getParam('invoice', 0);
	    
	    $this->view->invoice = $db->fetchRow("select * from armc where armc_id = ?", $id);
	    $this->view->contact = $db->fetchRow("select * from armc_contact where armc_id = ?", $id);
	
	    $sql = "SELECT * FROM armc_item WHERE armc_id = ? ";
	    $this->view->lines = $db->fetchAll($sql, array($id));
	    
	    $sql = "SELECT * FROM armc_comment WHERE armc_id = ? AND armc_comment_type_id = ? ";
	    $this->view->terms = $db->fetchRow($sql, array($id, ARMC_COMMENT_TYPE_TERMS));
	    $this->view->notes = $db->fetchRow($sql, array($id, ARMC_COMMENT_TYPE_MESSAGE));
	    
	}
	
	/**
	 * 
	 */
	public function savedetailAction()
	{
	    $db = Zend_Registry::get('db');
	    
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    $params = $this->getRequest()->getParams();
	    $id = $params['id'];
	    
	    $sql = "UPDATE armc SET invoice_date = ?, ponumber = ?, discount = ? WHERE armc_id = ?";
	    $data = array($params['invoicedate'], $params['ponumber'], $params['discount'], $params['id']);
	    
	    $db->query($sql, $data);
	    
	    $sql = "SELECT * FROM armc_comment WHERE armc_id = ? AND armc_comment_type_id = ? ";
	    $terms = $db->fetchRow($sql, array($id, ARMC_COMMENT_TYPE_TERMS));
	    
	    if (!empty($terms)) {

	        $sql = "UPDATE armc_comment SET comment = ?, modified_by = ? WHERE armc_id = ? AND armc_comment_type_id = ? ";
	        $db->query($sql, array($params['terms'], $_SESSION['admin_id'], $id, ARMC_COMMENT_TYPE_TERMS));

	    } else {
	        $sql = "INSERT INTO armc_comment (armc_id, armc_comment_type_id, comment, created_by, created_date, status) "
	             . "VALUES (?, ?, ?, ?, NOW(), 'A') ";
	         
	        $data = array($params['id'], ARMC_COMMENT_TYPE_TERMS, $params['terms'], $_SESSION['admin_id']);
	        $db->query($sql, $data);
	    }
	    
	    $sql = "SELECT * FROM armc_comment WHERE armc_id = ? AND armc_comment_type_id = ? ";
	    $message = $db->fetchRow($sql, array($id, ARMC_COMMENT_TYPE_MESSAGE));
	    
	    
	    if (!empty($message)) {
	       $sql = "UPDATE armc_comment SET comment = ?, modified_by = ? WHERE armc_id = ? AND armc_comment_type_id = ? ";
	        $db->query($sql, array($params['notes'], $_SESSION['admin_id'], $id, ARMC_COMMENT_TYPE_MESSAGE));  
	        
	    } else {
	        
	        $sql = "INSERT INTO armc_comment (armc_id, armc_comment_type_id, comment, created_by, created_date, status) "
	        	 . "VALUES (?, ?, ?, ?, NOW(), 'A') ";	         
	        
	        $data = array($params['id'], ARMC_COMMENT_TYPE_MESSAGE, $params['notes'], $_SESSION['admin_id']);
	        $db->query($sql, $data);
	    }
	    
	    $t = 0;
	    
	    $sql = "DELETE FROM armc_item WHERE armc_id = ?";
	    $db->query($sql, array($id));
	    
	    for ($i=0; $i < 10; $i++) {
	    	if (!isset($params['sku_' . $i]) || trim($params['sku_' . $i]) == '') continue;
	    	$sql = "INSERT INTO armc_item (armc_id, sku, description, unit_cost, units, total, created_by, created_date, status) "
	    	. "VALUES (?,?,?,?,?,?,?,NOW(),'A') ";
	    	$data = array(
	    			$params['id'],
	    			$params['sku_' . $i],
	    			$params['desc_' . $i],
	    			$params['uc_' . $i],
	    			$params['u_' . $i],
	    			$params['t_' . $i],
	    			$_SESSION['admin_id']);
	    	$t += $params['t_' . $i];
	    	$db->query($sql, $data);
	    }
	    
	    $sql = "UPDATE armc SET invoice_amount = ? WHERE armc_id = ?";
	    $db->query($sql, array($t, $params['id']));
	    
	    $this->_helper->redirector->setCode(301);
	    $this->_redirect('/invoice/invoice/id/' . $params['id']);
	} 
	
	/**
	 * 
	 */
	public function printAction()
	{
	    $db = Zend_Registry::get('db');
	    
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    $params = $this->getRequest()->getParams();
	    
	    //$this->view->user = array('account_name' => 'Research For Good Inc');
	     
	    $sql = "SELECT * FROM contact WHERE contact_type_id = ?";
	    $this->view->office = $db->fetchRow($sql, array(CONTACT_TYPE_OFFICE));
	     
	    $sql = "SELECT * FROM armc WHERE armc_id = ?";
	    $data = array($params['invoice']);
	    $this->view->armc = $db->fetchRow($sql, $data);
	     
	    $sql = "SELECT SUM(total) AS total FROM armc_item WHERE armc_id = ? GROUP BY armc_id ";
	    $this->view->total = $db->fetchOne($sql, $data);
	     
	    $sql = "SELECT * FROM armc_contact WHERE armc_id = ? ";
	    $this->view->contact = $db->fetchRow($sql, $data);
	     
	    $sql = "SELECT * FROM armc_item WHERE armc_id = ? ";
	    $this->view->items = $db->fetchAll($sql, $data);
	     
	    $sql = "SELECT * FROM armc_comment WHERE armc_id = ?  AND armc_comment_type_id = " . ARMC_COMMENT_TYPE_TERMS;
	    $this->view->terms = $db->fetchRow($sql, $data);
	}
	
	/**
	 * 
	 */
	public function pdfAction()
	{
	    $db = Zend_Registry::get('db');
	     
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    $params = $this->getRequest()->getParams();
	     
	    //$this->view->user = array('account_name' => 'Research For Good Inc');
	    
	    $sql = "SELECT * FROM contact WHERE contact_type_id = ?";
	    $this->view->office = $db->fetchRow($sql, array(CONTACT_TYPE_OFFICE));
	    
	    $sql = "SELECT * FROM armc WHERE armc_id = ?";
	    $data = array($params['invoice']);
	    $this->view->armc = $db->fetchRow($sql, $data);
	    
	    $sql = "SELECT SUM(total) AS total FROM armc_item WHERE armc_id = ? GROUP BY armc_id ";
	    $this->view->total = $db->fetchOne($sql, $data);
	    
	    $sql = "SELECT * FROM armc_contact WHERE armc_id = ? ";
	    $this->view->contact = $db->fetchRow($sql, $data);
	    
	    $sql = "SELECT * FROM armc_item WHERE armc_id = ? ";
	    $this->view->items = $db->fetchAll($sql, $data);
	    
	    $sql = "SELECT * FROM armc_comment WHERE armc_id = ?  AND armc_comment_type_id = " . ARMC_COMMENT_TYPE_TERMS;
	    $this->view->terms = $db->fetchRow($sql, $data);
	     
	    $this->view->logopath = 'http://' . $_SERVER['HTTP_HOST'] . '/images/res/' . $_SESSION['system'] . "/logo.jpg";
	     
	    $file = $this->view->render('invoice/pdf.tpl');
	    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/dompdf/www/test/pdfdocinvoice.html', $file);
	     
	    $this->_helper->viewRenderer->setNoRender(true);
	     
	    $this->_helper->redirector->setCode(301);
	    $this->_redirect('/dompdf/dompdf.php?base_path=www%2Ftest%2F&options[Attachment]=0&input_file=pdfdocinvoice.html#toolbar=0&view=FitH&statusbar=0&messages=0&navpanes=0');
	     
	}
	
	/**
	 * 
	 */
	public function invoiceAction()
	{
	    $id = $this->getRequest()->getParam('id', 0);
	    $db = Zend_Registry::get('db');
	     
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    $sql = "SELECT * FROM armc WHERE armc_id = ?";
	    $this->view->invoice = $db->fetchRow($sql, array($id));
	    
	    $sql = "SELECT * FROM armc_item WHERE armc_id = ? ";
	    $this->view->items = $db->fetchAssoc($sql, array($id));
	    
	    $sql = "SELECT * FROM armc_contact WHERE armc_id = ? ";
	    $this->view->contact = $db->fetchRow($sql, array($id));
	    
	    $this->view->terms   = $db->fetchRow("select * from armc_comment WHERE armc_id = ? AND armc_comment_type_id = ?", 
	    	array($id, ARMC_COMMENT_TYPE_TERMS));
	    $this->view->message = $db->fetchRow("select * from armc_comment WHERE armc_id = ? AND armc_comment_type_id = ?", 
	    	array($id, ARMC_COMMENT_TYPE_MESSAGE));
	    
	    $sql = "SELECT * FROM contact WHERE contact_type_id = ? AND status = 'A' ";
	    $this->view->officeaddress = $db->fetchRow($sql, CONTACT_TYPE_OFFICE);
	}
	
	/**
	 * 
	 */
	public function listAction()
	{
	    $id = $this->getRequest()->getParam('id', 0);
	    $db = Zend_Registry::get('db');
	    
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    $sql = "SELECT * FROM armc WHERE status = 'A' ORDER BY armc_id DESC LIMIT 10";
	    $this->view->armcs = $db->fetchAssoc($sql);
	    
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

}

