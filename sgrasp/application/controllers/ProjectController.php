<?php

require_once 'db/pjmDB.class.php';
require_once 'db/studyDB.class.php';
require_once 'db/commonDB.class.php';
require_once 'app/pjm_functions.inc';
require_once 'kinesis.php';

class ProjectController extends Zend_Controller_Action
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
        
    	//$db = Zend_Db::factory($config->database);
        
        $registry = Zend_Registry::getInstance();
        $objects = array('smarty' => $smarty, 'db' => $db);
        
        if ($registry === NULL) {
        
        	$registry = new Zend_Registry($objects);
        	Zend_Registry::setInstance($registry);
        } else {
            $registry->set('db', $db);
        }
        
        $this->view->bgcolor = '99CCFF';
    }

    public function indexAction()
    {
        // action body
        
    }

	public function newAction()
	{
		
	}
	
	public function viewAction()
	{
		
	}
	
	/**
	 * 
	 */
	public function listAction()
	{
	    $results = $this->getRequest()->getParam('results', false);
	     
	    $sql = "SELECT * FROM project ORDER BY id DESC";
	    if ($results) {
	    	$ids = array_keys($results);
	    	//$sql = "SELECT * FROM account WHERE account_id IN (?)";
	    	$sql = "SELECT * FROM project WHERE pjm_id IN (" . mysql_real_escape_string(implode(",", $ids)) .")";
	    }
	     
	    $db = Zend_Registry::get('db');
	    /* @var $db Zend_Db_Adapter_Mysqli */
	     
	    $this->view->projects = $db->fetchAssoc($sql);
	    $this->view->pt       = $db->fetchAssoc("SELECT project_type_id, project_type_description FROM project_type");
	    $this->view->country  = $db->fetchAssoc("SELECT country_code, country_description FROM country");
	    $this->view->user     = $db->fetchAssoc("SELECT login, last_name FROM user");

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
	
	public function datapointAction()
	{
		$db = Zend_Registry::get('db');
		/* @var $db Zend_Db_Adapter_Mysqli */
		$sql = "SELECT * FROM pjm_addon_datapoint WHERE status = 'A' ";
		
		$this->view->dp = $db->fetchAll($sql);
	}
	
	public function datapointsAction()
	{
		$db = Zend_Registry::get('db');
		/* @var $db Zend_Db_Adapter_Mysqli */
		$sql = "SELECT * FROM pjm_addon_datapoint WHERE status = 'A' ";
		
		$this->view->dp = $db->fetchAll($sql);
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
	}
	
	public function datapointchoicesAction()
	{
		$params = $this->getRequest()->getParams();
		$db = Zend_Registry::get('db');
		/* @var $db Zend_Db_Adapter_Mysqli */
		$sql = "SELECT choices FROM pjm_addon_datapoint WHERE status = 'A' AND id = ? ";
	
		$this->view->dp = unserialize($db->fetchOne($sql, array($params['id'])));
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
	}
	
	public function savedatapointAction()
	{
		$db = Zend_Registry::get('db');
		/* @var $db Zend_Db_Adapter_Mysqli */
		
		$this->_helper->viewRenderer->setNoRender(true);
		
		$params = $this->getRequest()->getParams();
		
		$i = 0;
		$choices = array();
		foreach ($params['name'] as $n) {
			$choices[] = array('choiceid' => $i+188, 'name' => $n, 'custom' => $params['custom'][$i]);
			$i++;
		}
		
		$sql = "INSERT INTO pjm_addon_datapoint (label, qtext, type, choices, created_by, created_date, status) "
		     . "VALUES (?,?,?,?,?, NOW(), 'A') ";
		$stmt = $db->query($sql, array(
				$params['label'], $params['qtext'], $params['type'], serialize($choices), $_SESSION['admin_id']
				));
		
		$datapoint = $db->lastInsertId();
		
		$k = new kinesis();
		$auth = $k->login('Grasp', 'V8UAEyP09X');
		$result = $k->dataPointCreate($params['label'], $params['qtext'], $params['type'], $choices);
		
		print_r($result);
	}
	
	public function saveAction()
	{
		$db = Zend_Registry::get('db');
		
		/* @var $db Zend_Db_Adapter_Mysqli */
		$params = $this->getRequest()->getParams();
		
		$sql = "INSERT INTO project (account_id, account_name, project_name, start_date, end_date, created_by, created_date, status) "
		     . "VALUES (?,?,?,?,?,?,NOW(),'A')";
		
		$stmt = $db->query($sql, array(
						$params['account_id'],
						$params['account_name'],
						$params['project_name'],
						$params['start_date'],
						$params['end_date'],
						$_SESSION['admin_id']));
		
		$project_id = $db->lastInsertId();

		$sql = "SELECT * FROM contact WHERE contact_id = ?";
		$contact = $db->fetchRow($sql, array($params['contact_id']));
		
		$sql = "INSERT INTO project_contact (project_id, contact_id, contact_name, phone, created_by, created_date, status) "
		. "VALUES (?,?,?,?,?, NOW(), 'A') ";
		$db->query($sql, array($project_id, $params['contact_id'], $params['contact_name'], $contact['contact_phone'] ,$_SESSION['admin_id']));
		
		//TODO: test to see the service template type of the Platform Account
		// then decide the next screen type
		
		$this->_helper->redirector->setCode(301);
		$this->_redirect('/project/detail/id/' . $project_id);
	}
	
	public function savefileAction()
	{
	    $id = $this->getRequest()->getParam('project_id', 0);
	    $params = $this->getRequest()->getParams();
	    
	    $db = Zend_Registry::get('db');
	    
	    $sql = "INSERT INTO project_file (project_id, project_file_name, project_file_type, project_file_data, created_by, created_date, status) "
	         . "VALUES (?,?,?,?,?,NOW(), 'A') ";
	    
	    $filedata = file_get_contents($_FILES['fileupload']['tmp_name']);
	    $filename = $_FILES['fileupload']['name'];
	    $filetype = $_FILES['fileupload']['type'];
	    
	    $db->query($sql, array($id, $filename, $filetype, $filedata, $_SESSION['admin_id']));
	    
	    $this->_helper->redirector->setCode(301);
	    $this->_redirect('/project/detail/id/' . $id);
	}
	
	
	/**
	 * 
	 */
	public function getfileAction()
	{
	    $viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
	    $viewRender->setNoRender(true);
	    
	    $id = $this->getRequest()->getParam('id', 0);
	    $db = Zend_Registry::get('db');
	    
	    $sql = "SELECT project_file_name, project_file_type, project_file_data FROM project_file WHERE id = ?";
	    $file = $db->fetchRow($sql, array($id));
	    
	    $this->getResponse()->setHeader('Content-Type', $file['project_file_type']);
	    $this->getResponse()->setHeader('Content-Disposition', 'attachment; filename="' . $file['project_file_name'] . '"');
	    print $file['project_file_data'];
	}
	
	/**
	 * 
	 */
	public function savememoAction()
	{
	    $id = $this->getRequest()->getParam('project_id', 0);
	    $params = $this->getRequest()->getParams();
	     
	    $db = Zend_Registry::get('db');
	     
	    $sql = "INSERT INTO project_memo (project_id, memo, created_by, created_date, status) "
	    . "VALUES (?,?,?,NOW(), 'A') ";
	     
	    $db->query($sql, array($id, $params['memo'], $_SESSION['admin_id']));
	     
	    $this->_helper->redirector->setCode(301);
	    $this->_redirect('/project/detail/id/' . $id);
	}
	
	/**
	 * 
	 */
	public function detailAction()
	{
		$id = $this->getRequest()->getParam('id', 0);
		$db = Zend_Registry::get('db');
		
		/* @var $db Zend_Db_Adapter_Mysqli */
		$sql = "SELECT * FROM project WHERE id = ?";
		$this->view->project = $db->fetchRow($sql, $id);
		
		$sql = "SELECT * FROM project_contact WHERE project_id = ?";
		$this->view->contact = $db->fetchRow($sql, $id);
		
		$sql = "SELECT * FROM project_attr_def WHERE status = 'A'";
		$this->view->attrs = $db->fetchAssoc($sql);
		
		$sql = "SELECT `key`,`value` FROM project_attr WHERE id = ?";
		$this->view->attrvals = $db->fetchAssoc($sql, array($id));
		
		$sql = "SELECT id, project_file_name, created_by, created_date FROM project_file WHERE project_id = ? ";
		$this->view->files = $db->fetchAssoc($sql, array($id));
		
		$sql = "SELECT id, memo, created_by, created_date FROM project_memo WHERE project_id = ? ";
		$this->view->memos = $db->fetchAssoc($sql, array($id));
		
		$sql = "SELECT id, name, description FROM task ";
		$this->view->tasks = $db->fetchAssoc($sql);
		
		$sql = "SELECT user_id, first_name, last_name FROM user ";
		$this->view->users = $db->fetchAssoc($sql);
		
		$sql = "SELECT * FROM project_task WHERE project_id = ? ";
		$this->view->projecttasks = $db->fetchAssoc($sql, array($id));
		
	}
	
	/**
	 * 
	 */
	public function saveattrAction()
	{
	    $id = $this->getRequest()->getParam('project_id', 0);
	    $params = $this->getRequest()->getParams();
	    $db = Zend_Registry::get('db');
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    
	    $sql = "SELECT * FROM project_attr_def WHERE status = 'A'";
	    $attrs = $db->fetchAssoc($sql);
	    
	    foreach ($attrs as $attr) {
	        if (!isset($params[$attr['key']])) continue;
	        
	        $sql = "INSERT INTO project_attr (id, `key`, value) VALUES (?,?,?)";
	        $db->query($sql, array($id, $attr['key'], $params[$attr['key']]));
	    }
	    
	    $this->_helper->redirector->setCode(301);
	    $this->_redirect('/project/detail/id/' . $id);
	}
	
	/**
	 * 
	 */
	public function savetaskAction()
	{
// 	    $viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
// 	    $viewRender->setNoRender(true);
	    
	    $id = $this->getRequest()->getParam('project_id', 0);
	    $params = $this->getRequest()->getParams();
	    $db = Zend_Registry::get('db');
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    	  
	    
	    if ($params['btnpress'] == 'S') {
	   		
	        $user = $db->fetchRow("SELECT * FROM user WHERE user_id = ?", array($params['assign']));
	        
	        $task = $db->fetchRow("SELECT * FROM task WHERE id = ?", array($params['taskname']));
	        
	        $sql = "INSERT INTO project_task (project_id, task_id, task_name, user_id, first_name, "
	             . " last_name, email, due_date, created_by, created_date, status) "
	             . " VALUES (?,?,?,?,?,?,?,?,?, NOW(), 'A')";
	        
	        $db->query($sql, array($id, $params['taskname'], $task['name'], $params['assign'], $user['first_name'], 
	        	$user['last_name'], $user['email_address'], $params['quotedate'], $_SESSION['admin_id']));
	        
	    } elseif ($params['btnpress'] == 'D') {
	        
	    }
	    $this->_helper->redirector->setCode(301);
		$this->_redirect('/project/detail/id/' . $id);
	}
	
	/**
	 * 
	 */
	public function deltaskAction()
	{
	    $viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
	    $viewRender->setNoRender(true);
	    
	    $project_task_id = $this->getRequest()->getParam('project_task_id', 0);
	    $id = $this->getRequest()->getParam('project_id', 0);
	    $db = Zend_Registry::get('db');
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    
	    $sql = "DELETE FROM project_task WHERE id = ?";
		$db->query($sql, array($project_task_id));
		//echo $sql . $project_task_id;
		$this->_helper->redirector->setCode(301);
		$this->_redirect('/project/detail/id/' . $id);
	
	}

	public function ganttAction()
	{
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
	}
	
	public function ganttviewAction()
	{
		
	}
}

