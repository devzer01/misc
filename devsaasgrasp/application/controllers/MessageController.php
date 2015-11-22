<?php

class MessageController extends Zend_Controller_Action
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
        $smarty->force_compile = true;
        $smarty->compile_check = true;
        $userRights = new userRights($_SESSION['admin_id']);
        
        $config = new Zend_Config(
	        array(
	            'database' => array(
	                'adapter' => 'Mysqli',
	                'params'  => array(
	                    'host'     => '127.0.0.1',
	                    'dbname'   => 'opstools',
	                    'username' => 'root',
	                    'password' => '',
	                )
	            )
	        )
	    );
     
    	$db = Zend_Db::factory($config->database);
        
        $registry = new Zend_Registry(array('smarty' => $smarty, 'userrights' => $userRights, 'db' => $db));
        Zend_Registry::setInstance($registry);
    }

    public function indexAction()
    {
        // action body
    }
    
    public function navtreeAction()
    {
    	$this->getResponse()->setHeader('Content-Type', 'text/xml');
    }

    public function newAction()
    {
    	
    }
    
    public function listAction()
    {
        $this->getResponse()->setHeader("Content-Type", "text/xml");
    }

}