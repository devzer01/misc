<?php

/**
 * Log Configuration File Reader Utility
 *
 * @package  Util
 * @subpackage  Log
 */

class Hb_Util_Log_ConfigReader
{
	/**
	 * Reads the Logger Configuration from configuration file, if the specific logger configuration is not found then the default configuration is used
	 *
	 * @param string $log_config_file 
	 * @param string $logger_name
	 * @return  Zend_Config
	 */
	public static function Read($log_config_file, $logger_name)
	{		
		if (!is_file($log_config_file)) {
			throw new Hb_Util_Log_Exception_LoggerConfigFileNotFoundException("Log Config File Not Found " , 
				EXCEPTION_UTIL_CONFIG_LOG_CONFIG_FILE_NOT_FOUND);
		}
		
		try  {
			$config = Hb_Util_Config_Config::GetInstance($log_config_file, $logger_name);
		} catch (Zend_Config_Exception $e)  {
			try {
				$config = Hb_Util_Config_Config::GetInstance($log_config_file, 'default');
			} catch (Zend_Config_Exception $e)  {
				throw new Hb_Util_Log_Exception_LoggerDefaultConfigurationNotFoundException("Default Logger configuration section not set in Log config", 
					EXCEPTION_UTIL_LOG_LOGGER_DEFAULT_CONFIG_NOT_FOUND);
			}
		}
		
		return $config;
	}

}
?>