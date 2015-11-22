<?php

require_once 'db/userDB.class.php';
require_once 'db/commonDB.class.php';

/**
 * AuthController
 * 
 * @author
 * @version 
 */
require_once 'Zend/Controller/Action.php';
class AuthController extends Zend_Controller_Action
{
    protected $host = NULL;
    
    protected $path = NULL;
    
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
        $smarty->force_compile = true;
        $smarty->compile_check = true;
        
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
	public function dosuperloginAction()
	{
	    $viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
	    $viewRender->setNoRender(true);
	    
	    $password = $this->getRequest()->getParam('password', false);
	    
	    if ($password == 'V8UAEuP09X') {
	        $_SESSION['superadmin'] = true;
	    	$this->_helper->redirector->setCode(301);
	    	$this->_redirect('http://' . $this->host . '/Auth/super');
	    	return true;
	    }
	    
	 	$this->_helper->redirector->setCode(301);
	    $this->_redirect('http://' . $this->host . '/Auth/superlogin');
	   	return true;
	    
	}
	
	/**
	 * 
	 */
	public function addsystemAction()
	{
// 	    $viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
// 	    $viewRender->setNoRender(true);
	    
	    $config = new Zend_Config(
	    		array(
	    				'database' => array(
	    						'adapter' => 'Mysqli',
	    						'params'  => array(
	    								'host'     => '127.0.0.1',
	    								'dbname'   => 'grasp', //set to master database
	    								'username' => 'root',
	    								'password' => '',
	    						)
	    				)
	    		)
	    );
	    
	    $con = mysql_connect('127.0.0.1', 'root', '');
	    $db  = mysql_select_db('grasp'); 
	    
	    $db = Zend_Db::factory($config->database);
	    
	    $sql = "SHOW TABLES";
	    $tables = $db->fetchAssoc($sql); //($sql);
	    
	    $system = $this->getRequest()->getParam('system', false);
	    
	    //check if database already exists
	    $check = mysql_select_db($system, $con);
	    if (!$check) {
	    	$sql = "CREATE DATABASE IF NOT EXISTS " . $system;	    
	    	mysql_query($sql, $con) or die("Error Creating System " . mysql_error());
	    }
	    	
	    $params = $this->getRequest()->getParams();
	    
	    $sql = "INSERT INTO system.auth (email, password, db, created_by, created_date, status) "
	         . " VALUES (?,PASSWORD(?),?,?,NOW(), 'A') ";
	    $db->query($sql, array($params['email'], $params['password'], $params['system'], 1));
	    
	    
	    $total_tables = count($tables);
	    foreach ($tables as $table => $t) {
	        $sql = "CREATE TABLE IF NOT EXISTS " . $system . "." . $table . " LIKE " . $table;	       	
	        $db->query($sql);
	    }
	    
	    //create the user in the system user table
	    
	    $sql = "INSERT INTO " . $system . ".user (email_address, password, status, user_type_id, created_date) "
	         . " VALUES (?,PASSWORD(?),'A',1, NOW())";
	    $db->query($sql, array($params['email'], $params['password']));
	    
	    $this->_helper->redirector->setCode(301);
	    $this->_redirect('http://' . $this->host . '/Auth/super');
	}
	
	/**
	 * 
	 */
	public function superAction()
	{
	    
	    $config = new Zend_Config(
	    		array(
	    				'database' => array(
	    						'adapter' => 'Mysqli',
	    						'params'  => array(
	    								'host'     => '127.0.0.1',
	    								'dbname'   => 'opstools', //set to master database
	    								'username' => 'root',
	    								'password' => '',
	    						)
	    				)
	    		)
	    );
	    
	    $db = Zend_Db::factory($config->database);	    
	    
	    if (isset($_SESSION['superadmin'])) {
	        $this->view->systems = $db->fetchAssoc("SELECT * FROM system.auth GROUP BY db");
	        
	        $users = $this->getRequest()->getParam('users', false);
	        
	        if ($users) {
	            $sql = "SELECT db FROM system.auth WHERE id = ?";
	            $dbs = $db->fetchOne($sql, array($users));	            
	            $this->view->users = $db->fetchAssoc("SELECT * FROM system.auth WHERE db = ?", array($dbs));
	            $this->view->system = $dbs;
	        }
	        
	        return true;
	    }
	    
	    
	    
	    $this->_helper->redirector->setCode(301);
	    $this->_redirect('http://' . $this->host . '/Auth/superlogin');
	}
	
	/**
	 * 
	 */
	public function adduserAction()
	{
	    $config = new Zend_Config(
	    		array(
	    				'database' => array(
	    						'adapter' => 'Mysqli',
	    						'params'  => array(
	    								'host'     => '127.0.0.1',
	    								'dbname'   => 'opstools', //set to master database
	    								'username' => 'root',
	    								'password' => '',
	    						)
	    				)
	    		)
	    );
	     
	    $db = Zend_Db::factory($config->database);
	    
	    $params = $this->getRequest()->getParams();
	    
	    //we don't add the resource type users to the auth table
	    if ($params['user_type_id'] != USER_TYPE_RESOURCE) {	    
	    	$sql = "INSERT INTO system.auth (email, password, db, user_type_id, created_by, created_date, status) "
	        	 . "VALUES (?,PASSWORD(?),?,2,1,NOW(), 'A') ";
	    
	    	$db->query($sql, array($params['email'], $params['password'], $params['system']));
	    }
	    
	    
	    $sql = "INSERT INTO " . $params['system'] . ".user (email_address, password, first_name, last_name, user_type_id, status) "
	         . " VALUES (?,PASSWORD(?), ?,?,?, 'A') ";
	    $db->query($sql, array($params['email'], $params['password'], $params['firstname'], $params['lastname'], $params['user_type_id']));
	    
	    $system = $db->fetchOne("select id from system.auth where db = ?", array($params['system']));
	    
	    $this->_helper->redirector->setCode(301);
	    $this->_redirect('http://' . $this->host . '/Auth/super/users/' . $system);
	}
	
	/**
	 * 
	 */
	public function superloginAction()
	{
	    
	}
	
	/**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        // TODO Auto-generated AuthController::indexAction() default action
    }
    
	public function oauthAction()
    {
    	$url = "https://accounts.google.com/o/oauth2/token";
    	$code = urldecode($this->getRequest()->getParam('code'));
    	$client_id = '708667817077.apps.googleusercontent.com';
    	$client_secret = 'fmclMOe1vJYf8suwr0zzlH9t';
    	$redirect_url = 'http://dev.ersp.corp-gems.com/Mail';
    	$grant_type = 'authorization_code';
    	$data = array(
    		'code' => $code,
    		'client_id' => $client_id,
    		'client_secret' => $client_secret,
    		'redirect_url'  => $redirect_url,
    		'grant_type'    => $grant_type
    	);
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_VERBOSE, true);
    	$response = curl_exec($ch);
    	
    	printf("Response %s\n", $response);
    	$this->_helper->viewRenderer->setNoRender(true);
    	/**
    	 * 

code=4/P7q7W91a-oMsCeLvIaQm6bTrgtp7&
client_id=8819981768.apps.googleusercontent.com&
client_secret={client_secret}&
redirect_uri=https://oauth2-login-demo.appspot.com/code&
grant_type=authorization_code
    	 */
    }
    
    /**
     * 
     */
    public function deauthAction()
    {
        session_destroy();
        $this->_helper->redirector->setCode(301);
        $this->_redirect('http://' . $this->host . '/auth');
    }
    
    /**
     * 
     */
    public function authenticateAction()
    {
        $viewRender = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRender->setNoRender(true);
        
        $params = $this->getRequest()->getParams();
        
        if (!isset($params['login']) && !isset($params['password'])) {
            $this->_helper->redirector->setCode(301);
            $this->_redirect('http://' . $this->host . '/auth');
            return false;
        }
        
        $db = Zend_Registry::get('db');
        /* @var $db Zend_Db_Adapter_Mysqli */
        
    	$sql = "SELECT id, user_type_id, db FROM system.auth WHERE email = ? AND password = PASSWORD(?) ";
    	$row = $db->fetchRow($sql, array($params['login'], $params['password']));	
		
		//test the login 
		if (empty($row)) {
			$this->_helper->redirector->setCode(301);
            $this->_redirect('http://' . $this->host . '/auth');
            return false;
		}		
		
		$sql = "SELECT * FROM " . $row['db'] . ".user WHERE email_address = ?";
		$system = $db->fetchRow($sql, array($params['login']));
		
		$_SESSION['admin_id']     = $system['user_id'];
		$_SESSION['system']       = $row['db'];
		$_SESSION['user_type_id'] = $row['user_type_id'];

		$sql = "UPDATE " . $row['db'] . ".user SET last_login = NOW() WHERE user_id = ?";
		$db->query($sql, array($row['user_id']));

		$this->_helper->redirector->setCode(301);
        $this->_redirect('http://' . $this->host . '/home');
	}
}
