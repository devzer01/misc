<?php

class Hb_Db_Connection 
{
	
	private $config = '' ;
	
	public $server = '';
	
	public $user   = '';
	
	public $pass   = '';
	
	public $db     ='';
	
	function __construct()
	{
		$this->config = Hb_Util_Config_SystemConfigReader::Read('db');
		
		$this->server = $this->config->host;
		$this->user = $this->config->user;
		$this->pass = $this->config->pass;
		$this->db = $this->config->name;
	}
	
}
?>
