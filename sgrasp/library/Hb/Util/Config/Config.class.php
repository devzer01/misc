<?php

require_once 'Zend/Config/Ini.php';

require_once 'Zend/Config/Xml.php';

/**
 *  Standard Configuration Class Factory
 *
 * @package  Util
 * @subpackage  Config
 */

class Hb_Util_Config_Config
{
	/**
	 * Returns a Zend Config Object Based on The Config Schema
	 *
	 * @param mixed $config Array to be converted. or ini/xml file location
	 * @param string $namespace Namespace of the configuration file to be read
	 * @return Zend_Config
	 * @throws  Hb_Util_Config_InvalidSchemaException
	 */
	public static function GetInstance($config, $namespace = '')
	{
		if (is_array($config)) {
			return new Zend_Config($config);
		}
		
		if (preg_match("/\.ini$/", $config)) {
			return new Zend_Config_Ini($config, $namespace);
		}

		if (preg_match("/\.xml$/", $config)) {
			return new Zend_Config_Xml($config, $namespace);
		}

		throw new Hb_Util_Config_InvalidSchemaException("Invalid Configuration Schema",
			EXCEPTION_UTIL_CONFIG_INVALID_SCHEMA);
	}
}
?>