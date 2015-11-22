<?php
require_once "Zend/Application/Bootstrap/Bootstrap.php";
require_once "Zend_View_Smarty.php";
require_once "hb.class.php";
require_once "constant.inc";

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	public function init()
	{
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
		
		 $smarty = new Smarty();
         $userRights = new userRights($_SESSION['admin_id']);
        
          $registry = new Zend_Registry(array('smarty' => $smarty, 'userrights' => $userRights, 'db' => $db));
         Zend_Registry::setInstance($registry);
         
         
	}

}

