<?php

class SettingsController extends Zend_Controller_Action
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
        
        //$db = Zend_Db::factory($config->database);
        
        $registry = Zend_Registry::getInstance();
        $objects = array('smarty' => $smarty, 'db' => $db);
        
        if ($registry === NULL) {
        
        	$registry = new Zend_Registry($objects);
        	Zend_Registry::setInstance($registry);
        } else {
        	$registry->set('db', $db);
        }
    }

    /**
     * 
     */
    public function indexAction()
    {
        $this->view->auth = 1;
        if ($_SESSION['user_type_id'] != 1) {
            //add code here to show not authorized 
            $this->view->auth = 0;
        }
        $db = Zend_Registry::get('db');
        /* @var $db Zend_Db_Adapter_Mysqli */
        $sql = "SELECT * FROM task";
        $this->view->tasks = $db->fetchAssoc($sql);
        
        $sql = "SELECT * FROM project_attr_def";
        $this->view->attrs = $db->fetchAssoc($sql);
    
        $sql = "SELECT * FROM contact WHERE contact_type_id = ? ";
    	$this->view->officeaddress = $db->fetchRow($sql, array(CONTACT_TYPE_OFFICE));
    }

    /**
     * 
     */
    public function addtaskAction()
    {
        $params = $this->getRequest()->getParams();
        $db = Zend_Registry::get('db');
        /* @var $db Zend_Db_Adapter_Mysqli */
        $sql = "INSERT INTO task (description, created_by, created_date, status) "
             . "VALUES (?,?, NOW(), 'A') ";
        
        $db->query($sql, array($params['taskname'], $_SESSION['admin_id']));
        
        $this->_helper->redirector->setCode(301);
        $this->_redirect('/settings/index');
    }
    
    /**
     *
     */
    public function addattrAction()
    {
    	$params = $this->getRequest()->getParams();
    	$db = Zend_Registry::get('db');
    	/* @var $db Zend_Db_Adapter_Mysqli */
    	$sql = "INSERT INTO project_attr_def (`key`, type, size, description, created_by, created_date, status) "
    	. "VALUES (?,?,?,?,?,NOW(), 'A') ";
    
    	$db->query($sql, array($params['attrname'], $params['attrtype'],$params['attrsize'],$params['attrdesc'], $_SESSION['admin_id']));
    
    	$this->_helper->redirector->setCode(301);
    	$this->_redirect('/settings/index');
    }
    
    /**
     * 
     */
    public function uploadlogoAction()
    {
        $params = $this->getRequest()->getParams();
        $db = Zend_Registry::get('db');
        /* @var $db Zend_Db_Adapter_Mysqli */
        
        $sql = "SELECT filename FROM file WHERE file_type_id = ? ";
        $row = $db->fetchRow($sql, array(FILE_TYPE_LOGO));

        $filedata = file_get_contents($_FILES['logo']['tmp_name']);
        $filename = $_FILES['logo']['name'];
        $filetype = $_FILES['logo']['type'];
        
        if (empty($row)) {
        	$sql = "INSERT INTO file (file_type_id, filename, filetype, filedata, created_by, created_date, status) "
            	 . "VALUES (?,?,?,?,?,NOW(),'A') ";
	    
        	$db->query($sql, array(FILE_TYPE_LOGO, $filename, $filetype, $filedata, $_SESSION['admin_id']));
        } else {
            $sql = "UPDATE file SET filename = ?, filetype = ?, filedata = ?, modified_by = ? WHERE file_type_id = ? ";
            $db->query($sql, array($filename, $filetype, $filedata, $_SESSION['admin_id'], FILE_TYPE_LOGO));
        }
        
        $path = $_SERVER['DOCUMENT_ROOT'] . "/images/res/" . $_SESSION['system'];
        mkdir($path, 0777, true);
        
        file_put_contents($path . "/logo.jpg", $filedata);
        
        $this->_helper->redirector->setCode(301);
        $this->_redirect('/settings/index/');
    }
    
    /**
     * 
     */
    public function getlogoAction()
    {
        $viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRender->setNoRender(true);
         
        $id = $this->getRequest()->getParam('id', 0);
        $db = Zend_Registry::get('db');
         
        $sql = "SELECT filename, filetype, filedata FROM file WHERE file_type_id = ?";
        $file = $db->fetchRow($sql, array(FILE_TYPE_LOGO));
         
        $this->getResponse()->setHeader('Content-Type', $file['filetype']);
        $this->getResponse()->setHeader('Content-Disposition', 'inline; filename="' . $file['filename'] . '"');
        print $file['filedata'];
    }

    /**
     * 
     */
    public function officeaddrAction()
    {
        $params = $this->getRequest()->getParams();
        $db = Zend_Registry::get('db');
        /* @var $db Zend_Db_Adapter_Mysqli */
        
        $sql = "SELECT contact_id FROM contact WHERE contact_type_id = ?";
        $row = $db->fetchRow($sql, array(CONTACT_TYPE_OFFICE));
        
        if (empty($row)) {
    		$sql = "INSERT INTO contact (contact_type_id, company_name, street1, city, state, postalcode, country_code, phone, created_by, created_date, status) "
    	    	 . "VALUES (?,?,?,?,?,?,?,?,?, NOW(), 'A') ";

    		$db->query($sql, array(CONTACT_TYPE_OFFICE, $params['company_name'], $params['street1'], $params['city'], 
    			$params['stateprovince'], $params['postalcode'], $params['country_code'], $params['phone'], $_SESSION['admin_id']));
        } else {
            $sql = "UPDATE contact SET company_name = ?, street1 = ?, city = ?, state = ?, postalcode = ?, "
                 . "country_code = ?, phone = ?, modified_by = ?, modified_date = NOW(), status = 'A' WHERE contact_type_id = ?";
            $db->query($sql, array($params['company_name'], $params['street1'], $params['city'], 
    			$params['provincestate'],  $params['postalcode'], $params['country_code'], $params['phone'], $_SESSION['admin_id'], CONTACT_TYPE_OFFICE));
        }
    	
    	$this->_helper->redirector->setCode(301);
    	$this->_redirect('/settings/index');
    }
    
    public function i18nAction()
    {
        $params = $this->getRequest()->getParams();
        $db = Zend_Registry::get('db');
        /* @var $db Zend_Db_Adapter_Mysqli */
        
        $sql = "INSERT INTO setting (attr, value) VALUES (?,?) ";
        $db->query($sql, array(SYSTEM_CURRENCY, $params['currency']));
        
        $this->_helper->redirector->setCode(301);
        $this->_redirect('/settings/index');
    }
}

