<?php

/**
 * Abstract Log Handler
 * 
 * @todo Create a Log Handler factory and setup different handlers
 *@package  Util
 * @subpackage  Log
 */
class Hb_Util_Log_LogHandler 
{
	public static function factory($config)
	{
		$handler = "Hb_Util_Log_Handler_{$config->handler}Handler"; 
		return new $handler($config);
	}
}
?>