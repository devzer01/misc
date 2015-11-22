<?php

require_once 'Zend/Db.php';

class Hb_Util_Db_Connection 
{
	protected static $instance = null;
	
	protected function __construct()
	{
		
	}
	
	public static function GetInstance()
	{
		if (is_null(self::$instance)) {
			
			$config = Hb_Util_Config_SystemConfigReader::Read('db');
			
			$driver_options = array(PDO::ATTR_PERSISTENT => true);
			
			$params = array(
				'host'     => $config->host, 
				'username' => $config->user, 
				'password' => $config->pass, 
				'dbname'   => $config->name,
				'driver_options' => $driver_options);
				
			self::$instance = Zend_Db::factory('PDO_MYSQL', $params); 
			
			self::$instance->setFetchMode(Zend_Db::FETCH_OBJ);
		}
		
		return self::$instance;
	}
}
?>