<?php
/**
 * Log File Handler 
 * 
 * @package  Util
 * @subpackage  Log
 *
 */
class Hb_Util_Log_Handler_FileHandler implements Hb_Util_Log_Handler_HandlerInterface 
{
	private $__filename = '';
	
	private $__file_handler = null;
	
	public function __construct($config)
	{
		$this->__filename = $config->destination;
		$this->__Open();
	}
	
	public function __destruct()
	{
		fflush($this->__file_handler);
		fclose($this->__file_handler);
	}
	
	private function __Open()
	{
		$this->__file_handler = fopen($this->__filename, 'a');
		
		if (is_null($this->__file_handler)) {
			throw new Hb_Util_Log_Handler_Exception("Error Opening ". $this->__filename, 
				EXCEPTION_UTIL_LOG_HANDLER_ERROR_OPENING_HANDLE);
		}
	}
	
	private  function __Write($str)
	{
		fwrite($this->__file_handler, $str ."\n");
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $str
	 */
	public function Handle($str)
	{
		$this->__Write($str);
	}
}
?>