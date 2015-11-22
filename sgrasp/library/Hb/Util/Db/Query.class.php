<?php

class Hb_Util_Db_Query {

	protected static $instance = null;
	
	protected $connection = null;
	
	private function __construct($params)
	{
		$this->connection = mysql_pconnect($params['host'], $params['user'], $params['pass']);
		
		if (!$this->connection) {
			throw new Hb_Exception("Unable to connect to database server");
		}
		
		mysql_select_db($params['db'], $this->connection);
		
	}
	
	/**
	 * 
	 * @return Hb_Util_Db_Query
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			
			$config = Hb_Util_Config_SystemConfigReader::Read('db');
			
			$params = array(
				'host' => $config->host, 
				'user' => $config->user, 
				'pass' => $config->pass, 
				'db'   => $config->name);
				
			self::$instance = new Hb_Util_Db_Query($params); 
		}
		
		return self::$instance;
	}

	function escapeString($str)
	{
		return mysql_real_escape_string($str, $this->connection);
	}

	function q($query)
	{
		$rs = mysql_query($query, $this->connection);
		
		if (!$rs) {
			throw new Hb_Exception("MySQL Error" . $query . " " . mysql_error($this->connection));
		}

		if (preg_match("/^SELECT/",$query)) {
			$this->rows = mysql_num_rows($rs);
		} else {
			$this->rows = mysql_affected_rows($this->connection);
		}

		if (preg_match("/^INSERT/",$query)) {
			$this->last_id = mysql_insert_id($this->connection);
		}
		
		return $rs;
	}
	
	public function getLastInsertId()
	{
		return $this->last_id;
	}

	public function getArray($rs)
	{
		return mysql_fetch_array($rs);	
	}
	
	public function getAssoc($rs)
	{
		return mysql_fetch_assoc($rs);
	}		
}

?>
