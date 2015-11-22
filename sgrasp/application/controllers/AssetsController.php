<?php

class AssetsController extends Zend_Controller_Action
{

    public function init()
    {
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
        
        $registry = Zend_Registry::getInstance();
        $objects = array('smarty' => $smarty, 'db' => $db);
        
        if ($registry === NULL) {
        
        	$registry = new Zend_Registry($objects);
        	Zend_Registry::setInstance($registry);
        } else {
        	$registry->set('db', $db);
        }
        
        $this->view->bgcolor = '99FF99';
        
    }

    public function indexAction()
    {
        // action body
    }
    
    public function listAction()
    {
        
    }

    public function newAction()
    {
        
    }
    
    /**
     * 
     */
    public function inventorylistAction()
    {
        $db = Zend_Registry::get('db');
        /* @var $db Zend_Db_Adapter_Mysqli */
        
        $sql = "SELECT * FROM asset WHERE asset_type_id = 1 ";
        $this->view->list = $db->fetchAssoc($sql);
    }
    
    /**
     * 
     */
    public function saveAction()
    {
        $db = Zend_Registry::get('db');         
        /* @var $db Zend_Db_Adapter_Mysqli */
        
        $params = $this->getRequest()->getParams();
        
        $sql = "INSERT INTO asset (asset_type_id, `name`, `description`, `amount`, purchase_price, purchase_date, shelf_life, created_by, created_date, status) "
             . "VALUES (?,?,?,?,?,?,?,?, NOW(), 'A') ";
        
        $db->query($sql, array($params['asset_type_id'], $params['name'], $params['description'], $params['amount'], $params['purchase_price'], $params['purchase_date'], $params['shelf_life'], $_SESSION['admin_id']));
    
        $this->_helper->redirector->setCode(301);
        $this->_redirect('/Assets/inventorylist');
        
    }
    
    /**
     * 
     */
    public function inventoryAction()
    {
        
    }

    /**
     * 
     */
    public function saveresourceAction()
    {
        $db = Zend_Registry::get('db');
        /* @var $db Zend_Db_Adapter_Mysqli */
        
        $params = $this->getRequest()->getParams();
        
        $sql = "INSERT INTO asset (asset_type_id, `name`, `description`, `amount`, purchase_price, purchase_date, shelf_life, created_by, created_date, status) "
        . "VALUES (?,?,?,?,?,?,?,?, NOW(), 'A') ";
        
        $db->query($sql, array($params['asset_type_id'], $params['name'], $params['description'], $params['amount'], $params['purchase_price'], $params['purchase_date'], $params['shelf_life'], $_SESSION['admin_id']));
        
        $this->_helper->redirector->setCode(301);
        $this->_redirect('/Assets/inventorylist');
        
    }
    
    /**
     * 
     */
    public function resourceAction()
    {
        
    }
}

