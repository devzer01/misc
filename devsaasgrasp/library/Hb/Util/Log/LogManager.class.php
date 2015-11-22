<?php

/**
  * This class provides functionality to manage differnt log objects.
 *
 * @package  Util
 * @subpackage  Log
 *
 *
 */

class Hb_Util_Log_LogManager
{
	/**
	 * registry to keep each object
	 *
	 * @var mixed
	 */
	private $__loggers = array();

	/**
	 * Singleton member to keep the class reference
	 *
	 * @var Hb_Util_Log
	 */
	private static $instance;

	/**
	 * This class can not be initiated directly
	 *
	 * @access  private
	 *
	 */
	private function __construct() { }

	/**
	 * Adds a Logger to the Log Manager
	 *
	 * @param string $name Name of the Log
	 * @param Hb_Util_Log $log Log Object
	 * @param boolean $force if object already exist overwrite it
	 * @return  boolean
	 * @throws Hb_Util_Log_Exception_LoggerRegisteredException
	 */
	public function AddLogger($name, Hb_Util_Log_Logger $log, $force = false)
	{
		if (isset($this->__loggers[$name]) && $force === false) {
			throw new Hb_Util_Log_Exception_LoggerRegisteredException("Logger Already Registered", EXCEPTION_UTIL_LOG_LOGGER_REGISTERED);
		}
		
		/** lets configure the log now **/
		$sysconfig = Hb_Util_Config_SystemConfigReader::Read();
		
		$log_config =  Hb_Util_Log_ConfigReader::Read($sysconfig->log->conf, $name);
		
		$log->SetHandler(
			Hb_Util_Log_LogHandler::factory($log_config)
		);
		
		$log->SetLogLevel($log_config->loglevel);

		$this->__loggers[$name] = $log;

		return true;
	}

	/**
	 * Return a Log Object
	 *
	 * @param string $name Name of the Log
	 * @return Hb_Util_Logger
	 * @throws Hb_Util_Log_Exception_LoggerRegisteredException
	 */
	public function GetLogger($name)
	{
		if (isset($this->__loggers[$name])) {
			return $this->__loggers[$name];
		}

		throw new Hb_Util_Log_Exception_LoggerNotRegisteredException("Logger Not Registered", EXCEPTION_UTIL_LOG_LOGGER_NOT_REGISTERED);
	}

	/**
	 * Enter description here...
	 *
	 * @return Hb_Util_Log_LogManager
	 */
	public static function GetInstance()
	{
		if (empty(self::$instance)) {
			self::$instance = new Hb_Util_Log_LogManager();
		}

		return self::$instance;
	}
	
}
?>