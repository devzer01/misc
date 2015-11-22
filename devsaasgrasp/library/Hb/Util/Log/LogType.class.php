<?php

/**
 * Enumuration
 *
 * @package Util
 * @subpackage  Log
 */

class Hb_Util_Log_LogType
{
	const HB_ERROR = 1;

	const HB_WARN   = 2;
	
	const HB_INFO     = 4;
	
	const HB_DEBUG = 8;
	
	const HB_DEBUG2 = 16;
	
	const HB_EXCEPTION = 32;
	
	public static function GetLogType($event_type)
	{
		$list = array(
			self::HB_ERROR => "ERROR",
			self::HB_INFO    => "INFO",
			self::HB_WARN  => "WARNNING",
			self::HB_DEBUG => "DEBUG",
			self::HB_DEBUG2 => "DEBUG2",
			self::HB_EXCEPTION => "EXCEPTION"
		);
		
		if (isset($list[$event_type])) {
			return $list[$event_type];
		}
		
		return "UNKNOWN";
	}
}
?>