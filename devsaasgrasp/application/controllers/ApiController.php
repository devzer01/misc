<?php

require_once('kinesis.php');

class ApiController extends Zend_Controller_Action
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

    public function createAction()
    {
    	
    	$kn = new kinesis();
    	$ret = $kn->login('Grasp', 'V8UAEyP09X');
    	print_r($ret);
//     	//integration.panel.select
//     	$data = array('sesKey', 'panelid');
    		
//     	//integration.panelist.create	
//     	$data = array('sesKey', 'settings');
    	
//     	//integration.auth.logout
//     	$data = array('sesKey');
    }
    
    public function getdatapointsAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        
        $db = Zend_Registry::get('db');
        /* @var $db Zend_Db_Adapter_Mysqli */
        $sql = "SELECT * FROM pjm_addon_datapoint WHERE status = 'A' ";
        
        $dp = $db->fetchAll($sql);
        $this->getResponse()->setHeader('Content-Type', 'text/html');
        $result = array();
        for($i=0; $i <= count($dp); $i++) {
             if (!isset($dp[$i])) continue;
			 $data = array('label' => $dp[$i]['label'], 'qtext' => $dp[$i]['qtext'], 'type' => $dp[$i]['type']);
             $data['choices'] = unserialize($dp[$i]['choices']);
             $result[] = $data;
         }
        $return['error'] = 0;
        $return['result'] = $result;
        print json_encode($return);
        
    }

}

