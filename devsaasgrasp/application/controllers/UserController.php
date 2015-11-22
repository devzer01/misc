<?php

class UserController extends Zend_Controller_Action
{

    /**
     * (non-PHPdoc)
     * @see Zend_Controller_Action::init()
     */
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
        
        $registry = Zend_Registry::getInstance();
        $objects = array('smarty' => $smarty, 'db' => $db);
        
        if ($registry === NULL) {
        	$registry = new Zend_Registry($objects);
        	Zend_Registry::setInstance($registry);
        } else {
        	$registry->set('db', $db);
        }
         
        $this->view->bgcolor = 'FFCC99';
    }
    
    /**
     * 
     */
    public function searchAction()
    {
        if ($this->getRequest()->getParam('dosearch', false) == false) {
        	return true;
        }
         
//         $viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
//         $viewRender->setNoRender(true);
        
        $db = Zend_Registry::get('db');
         
        /* @var $db Zend_Db_Adapter_Mysqli */
        $params = $this->getRequest()->getParams();
        $sql = "SELECT user_id FROM user WHERE last_name LIKE ?";
        $results = $db->fetchAssoc($sql, array('%' . $this->getRequest()->getParam('un', '%test%') . '%'));
         
        //print_r($results);
        $this->_forward('list', null, null, array('results' => $results));
    }

    /**
     * 
     */
    public function savecommentAction()
    {
        $db = Zend_Registry::get('db');
         
        /* @var $db Zend_Db_Adapter_Mysqli */
        $params = $this->getRequest()->getParams();
        
        $sql = "INSERT INTO user_comment (user_comment_type_id, user_id, comment, created_by, created_date, status) "
             . "VALUES (?,?,?,?,NOW(), 'A') ";
        $db->query($sql, array($params['comment_type_id'], $params['user_id'], $params['comment'], $_SESSION['admin_id']));
    
        $this->_helper->redirector->setCode(301);
        $this->_redirect('/User/view/id/' . $params['user_id']);
    }
    
    /**
     * 
     */
    public function saveattrAction()
    {
        $db = Zend_Registry::get('db');
         
        /* @var $db Zend_Db_Adapter_Mysqli */
        $params = $this->getRequest()->getParams();
        
    	foreach ($params as $param => $value) {
			$sql = "SELECT user_attr_id FROM user_attr WHERE user_attr = ? and login = ? ";
			$user_attr_id = $db->fetchOne($sql, array($param, $params['user_id']));
			if ($user_attr_id) {
			 	$sql = "UPDATE user_attr SET user_value = ? WHERE user_attr_id = ? ";
			 	$db->query($sql, array($value, $user_attr_id));   
			} else {
    	    	$sql = "INSERT INTO user_attr (login, user_attr, user_value, created_by, created_date, status) "
    	         . "VALUES (?, ?, ?, ?, NOW(), 'A') ";
    	    	$db->query($sql, array($params['user_id'], $param, $value, $_SESSION['admin_id']));
			}
    	}
    	
    	if (isset($params['firstname']) && isset($params['lastname'])) {
    	    $sql = "UPDATE user SET first_name = ?, last_name = ? WHERE user_id = ? ";
    	    $db->query($sql, array($params['firstname'], $params['lastname'], $params['user_id']));
    	}
    
    	$this->_helper->redirector->setCode(301);
    	$this->_redirect('/User/view/id/' . $params['user_id']);
    }
    /**
     * 
     */
    public function indexAction()
    {
        // action body
    }

    /**
     * 
     */
    public function listAction()
    {
        $results = $this->getRequest()->getParam('results', false);
         
        $sql = "SELECT * FROM user WHERE status = 'A' ORDER BY user_id DESC LIMIT 10";
        if ($results) {
        	$ids = array_keys($results);
        	//$sql = "SELECT * FROM account WHERE account_id IN (?)";
        	$sql = "SELECT * FROM user WHERE user_id IN (" . mysql_real_escape_string(implode(",", $ids)) .")";
        }
         
        $id = $this->getRequest()->getParam('id', 0);
        $db = Zend_Registry::get('db');
         
        /* @var $db Zend_Db_Adapter_Mysqli */
        $this->view->users = $db->fetchAssoc($sql);
    }
    
    /**
     * 
     */
    public function saveAction()
    {
        $params = $this->getRequest()->getParams();
        $db = Zend_Registry::get('db');
         
        /* @var $db Zend_Db_Adapter_Mysqli */
        $sql = "INSERT INTO user (email_address, first_name, last_name, user_type_id, contact_phone, country_code, password, created_by, created_date, status) "
             . " VALUES (?,?,?,?,?,?,?,PASSWORD(?), NOW(), 'A')";
        $db->query($sql, array(
        	$params['email_address'], 
        	$params['first_name'], 
        	$params['last_name'],
        	$params['user_type_id'],
        	$params['contact_phone'],
        	$params['country_code'],
        	$params['password'], $_SESSION['admin_id']));
        
        $user_id = $db->lastInsertId();
        
        //TODO: check if the user type is not equal to the no access params
        $sql = "INSERT INTO system.auth (user_type_id, db, email, password) VALUES (?,?,?,PASSWORD(?)) ";
        $db->query($sql, array($params['user_type_id'], $_SESSION['system'], $params['email_address'], $params['password']));
        //INSERT INTO system.auth (sup
        //id, user_type_id, db FROM system.auth WHERE email = ? AND password = PASSWORD(?)
        
        $this->_helper->redirector->setCode(301);
        $this->_redirect('/User/view/id/' . $user_id);
    }
    
    
    /**
     * 
     */
    public function leaveAction()
    {
        $params = $this->getRequest()->getParams();
        $db = Zend_Registry::get('db'); 
        /* @var $db Zend_Db_Adapter_Mysqli */

        $sql = "INSERT INTO user_leave (leave_type_id, user_id, leave_from, leave_to, comment, contact_number, created_by, created_date, status) "
             . " VALUES (?,?,?,?,?,?,?, NOW(), 'A') ";
        $db->query($sql, array($params['leave_type_id'], $params['user_id'], $params['leave_from'], $params['leave_to'], $params['comments'], $params['contact_number'], $_SESSION['admin_id']));
        
        $this->_helper->redirector->setCode(301);
        $this->_redirect('/User/view/id/' . $params['user_id']);
    }
    
    /**
     * 
     */
    public function viewAction()
    {
        $id = $this->getRequest()->getParam('id', 0);
        $db = Zend_Registry::get('db');
        
        $sql = "SELECT * FROM user WHERE user_id = ?";
        $this->view->user = $db->fetchRow($sql, array($id));
        $this->view->attr = $db->fetchAssoc("SELECT user_attr, user_value FROM user_attr WHERE login = ?", array($id));
        $this->view->lifecycles = $db->fetchAssoc("SELECT user_comment_id, user_comment_type_id, created_by, created_date, comment FROM user_comment WHERE user_id = ? ", array($id));
        $this->view->users = $db->fetchPairs("SELECT user_id, last_name FROM user WHERE status = 'A' ");
        $this->view->user_comment_types = $db->fetchPairs("SELECT user_comment_type_id, user_comment_description FROM user_comment_type WHERE status = 'A' ");
    	$this->view->leaves = $db->fetchAssoc("SELECT user_leave_id, leave_type_id, leave_from, leave_to, approved_by, comment, contact_number, created_by, created_date FROM user_leave WHERE user_id = ?", array($id));
    
    }
    
    /**
     * 
     */
    public function passwordAction() {
        $id = $this->getRequest()->getParam('user_id', 0);
        $db = Zend_Registry::get('db');
        $params = $this->getRequest()->getParams();
        
        $sql = "UPDATE user SET password = PASSWORD(?), modified_by = ?, modified_date = NOW() WHERE user_id = ?";
        $db->query($sql, array($params['password'], $_SESSION['admin_id'], $id));
        
        $this->_helper->redirector->setCode(301);
        $this->_redirect('/User/view/id/' . $id);
    }
    
    /**
     * 
     */
    public function newAction()
    {
        
    }

}

