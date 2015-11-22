<?php
include_once 'Hb/Util/Request/WebRequest.class';
include_once 'Hb/Util/Encryption/Encryption.class';

class Hb_Util_Request_Request
{
	/**
	 * singleton instance
	 *
	 * @var Hb_Util_Request_Request
	 */
	protected static $__instance = null;
	
	/**
	 * Request Data Object
	 *
	 * @var Hb_Util_Request_WebRequest
	 */
	protected $__request = null;
	
	/**
	 * Enter description here...
	 *
	 */
	protected function __construct()
	{
		
	}
	
	/**
	 * Gets the request object
	 *
	 * @return Hb_Util_Request_WebRequest
	 */
	public static function GetInstance()
	{
		if (is_null(self::$__instance)) {
			self::$__instance = new Hb_Util_Request_Request();
			
			self::$__instance->__request = new Hb_Util_Request_WebRequest();
			
			self::$__instance->parse();	
		}
		
		return self::$__instance->__request;
	}
	
	/**
	 * Enter description here...
	 *
	 */
	protected function parse()
	{
		//parsing post
		foreach($_POST as $key => $value) { 
			$this->__request->$key = $value;
		}
			
		//parsing get
		foreach($_GET as $key => $value) {
			$this->__request->$key = $value;
		}

		$pairs = array();
		//parsing encripted items
		if (isset($_GET['e'])) {
			$str = ereg_replace(" ","+",$_GET['e']);		
			$e = Hb_Util_Encryption_Encryption::GetInstance();
			
	   		$str = $e->Decrypt($str);
	   		$pairs = explode("&",$str);
		}
	   
	   	for ($i=0; $i < count($pairs); $i++ ) {
	      	$pairs2 = split("=",$pairs[$i]);
	      	$this->__request->$pairs2[0] = $pairs2[1];
	   	}
	}
	
	/**
	 * Enter description here...
	 *
	 * @return Hb_Util_Request_WebRequest
	 */
	public function GetRequest()
	{		
		return $this->__request;
	}
	
}
?>