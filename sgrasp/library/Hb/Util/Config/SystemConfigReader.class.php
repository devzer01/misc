<?php
include_once 'Hb/Util/Config/Config.class.php';

/**
 * This object will hold system configuration
 * 
 * @package  Util
 * @subpackage  Config
 * @version  2.0.1
 * @author  nayana
 * @copyright  2007 Global Market Insite
 * 
 */

class Hb_Util_Config_SystemConfigReader
{	
	/**
	 * Location of the application configuration file,
	 * 
	 * @todo  use a standard location like /etc/
	 *
	 */
	const CONFIG_FILE_LOCATION = '/etc/hb.ini';
	
	protected static $instance = array();
	
	/**
	 * This class can not be initiated as an Object
	 *
	 * 
	 */
	private function __construct() {}
	
	/**
	 * Reads the system log file
	 *
	 * 
	 * @return Zend_Config
	 */
	public static function Read($segment = 'production')
	{
		if (!isset(self::$instance[$segment])) {
			self::$instance[$segment] = Hb_Util_Config_Config::GetInstance(self::CONFIG_FILE_LOCATION, $segment);
		}
		
		return self::$instance[$segment];
	}
}
?>