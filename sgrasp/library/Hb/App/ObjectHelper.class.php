<?php


/**
 * @author nayana
 * @version 1.0
 * @created 18-Jul-2007 9:55:18 PM
 */
class Hb_App_ObjectHelper
{

	function __construct()
	{
	}

	function __destruct()
	{
	}



	/**
	 * 
	 * @param class
	 * @return Hb_Mapper_Mapper
	 */
	public static function GetMapper($class)
	{
		$mapper = preg_replace("/App/", "Mapper", $class);
		
		self::ClassLoader($mapper);
		
		return new $mapper($class);
	}
	
	protected static function ClassLoader($class)
	{
		$base = '';
		$class_name = preg_replace("/_/", DIRECTORY_SEPARATOR, $class);
		$stage1 = $base . "/" . $class_name .".class.php";
		if (is_readable($stage1)) require_once($stage1);
		$stage2 = $base . "/" . $class_name .".class";
		if (is_readable($stage2)) require_once($stage2);
		$stage3 = $base . "/Hb/" . $class_name . ".class";
		if (is_readable($stage3)) require_once($stage3);
		$stage4 = $base . "/Hb/" . $class_name . ".class.php";
		if (is_readable($stage4)) require_once($stage4);
		
		
		//$class_name = preg_replace("/_/", DIRECTORY_SEPARATOR, $class);
	
		//$file = $class_name .".class";
	
//		if (!is_file($file)) {
//			throw new Hb_Exception("Unable to load class " . $file);
//		}
	
		//require_once($file);
	}

}
?>