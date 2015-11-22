<?php

class Hb_Util 
{
	public static function isPhp52()	
	{
		return self::GetPhpVersion() == 52; 
	}
	
	public static function GetPhpVersion()
	{
		$version = explode('.', phpversion());
		return $version[0] . $version[1];
	}
}
?>