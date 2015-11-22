<?php

/**
 *Standard Logger Object
 *  @package  Util
 *  @subpackage  Log
 */

class Hb_Util_Log_Logger
{
	/**
	 * Logger Name
	 *
	 * @var string
	 */
	private $__name;
	
	/**
	 * Holds the Logger Configuration
	 *
	 * @var  Zend_Log
	 */
	private $__config;
	
	
	/**
	 * Enter description here...
	 *
	 * @var unknown_type
	 */
	private $__formatter;
	
	/**
	 * Enter description here...
	 *
	 * @var Hb_Util_Log_LogHandler
	 */
	private $__handler;
	
	/**
	 * Log Level
	 *
	 * @var unknown_type
	 */
	private $__log_level = 0;

	/**
	 * Set the name of the logger and reads the configuration from the configuration file
	 *
	 * @param   string $name  Name of the logger
	 */
	private function __construct($name)
	{
		$this->__name   = $name;
	}
	
	public function SetHandler($handler)
	{
		$this->__handler = $handler;
	}

	/**
	 * Get A Logger instance, 
	 * This method works with the LogManager registry so you will also receive the same log object you had previously registerd
	 *
	 * @param string $name Name of the Logger
	 * @return Hb_Util_Log_Logger
	 */
	public static function GetInstance($name)
	{
		if (trim($name) == '') {
			throw new Hb_Util_Log_Exception_LoggerNameNotProvidedException("Logger Name Can Not Be Empty", 
				EXCEPTION_UTIL_LOG_LOGGER_NAME_EMPTY);
		}
		
		$manager = Hb_Util_Log_LogManager::GetInstance();

		try {
			$logger = $manager->GetLogger($name);
		} catch (Hb_Util_Log_Exception_LoggerNotRegisteredException  $e) {

			$logger = new Hb_Util_Log_Logger($name);
			
			$manager->AddLogger($name, $logger);
		}

		return $logger;
	}
	
	/**
	 * Returns the Name of the Logger
	 *
	 * @return string
	 */
	public function GetLoggerName()
	{
		return $this->__name;
	}
	

	/**
	 * Enter description here...
	 *
	 * @param Hb_Data_Event_Event $event
	 */
	public function Log($type, $name, $descripion)
	{
		$this->__Log($type, $name, $descripion);
	}
	
	/**
	 * Internal Log message
	 *
	 * @param string $type
	 * @param string $name
	 * @param string $descripion
	 */
	private function __Log($type, $name, $descripion)
	{
		if ($type & $this->__log_level) {
			$string = "[" . date("Y-m-d H:i:s") . "]\t" 
		          	     . Hb_Util_Log_LogType::GetLogType($type) . "\t" . $name . "\t" . $descripion;
			$this->__handler->Handle($string);
		}
	}
	
	/**
	 * Log an Exception
	 *
	 * @param Exception $e
	 */
	public function LogException(Exception $e)
	{
		$this->__Log(Hb_Util_Log_LogType::HB_EXCEPTION, $e->getMessage(), $e->getTraceAsString());
	}
	
	/**
	 * Log a warning message
	 *
	 * @param string $name
	 * @param string $description
	 */
	public function LogWarn($name, $description)
	{
		$this->__Log(Hb_Util_Log_LogType::HB_WARN, $name, $description);
	}
	
	/**
	 * Log non Exception Error
	 *
	 * @param string $name
	 * @param string $description
	 */
	public function LogError($name, $description)
	{
		$this->__Log(Hb_Util_Log_LogType::HB_ERROR, $name, $description);
	}
	
	/**
	 * Log a Info Message
	 *
	 * @param string $name
	 * @param string $description
	 */
	public function LogInfo($name, $description)
	{
		$this->__Log(Hb_Util_Log_LogType::HB_INFO, $name, $description);
	}
	
	/**
	 * Log a Level 1 debug message
	 *
	 * @param string $name
	 * @param string $description
	 */
	public function LogDebug($name, $description)
	{
		$this->__Log(Hb_Util_Log_LogType::HB_DEBUG, $name, $description);
	}
	
	/**
	 * Log a Level 2 debug message
	 *
	 * @param string $name
	 * @param string $description
	 */
	public function LogDebug2($name, $description)
	{
		$this->__Log(Hb_Util_Log_LogType::HB_DEBUG2, $name, $description);
	}
	
	public function SetLogLevel($level)
	{
		$this->__log_level = $level;
	}


}

?>