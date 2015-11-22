<?php

/**
 * @author Nayana Hettiarachchi
 * @copyright Global Market Insite 2006 
 * @version 2.0
 * @package Hb_Util_Db
 * 
 * HB Framework 2.0 style db access layer utilizing Zend Framework Zend_DB
 * All Inserts/Updates are wraped around a table level __InsertTableName/__UpdateTableName with a Camolized Naming Convension
 * Each __InsertTableName/__UpdateTableName will utilize the underlying __Insert/__Update derived from the package db, 
 * No Overloading of DB __Insert/__Update allowed
 * Audit Fields are automatically set at the db level and can be overwritten from the specific update/insert function by passing in the
 * appropriate created_date, modified_date, created_by, modified_by in the fields associative array. 
 */

require_once 'Zend/Db.php';

class Hb_Util_Connector_Db {
	
	private $__default_params = array();
	
	private $config = '' ;	
	
	protected $db = null;
	
	protected $select = null;
	
	protected $audit_modify = array();
	
	protected $audit_create = array();
	
	protected $version = null;
	
	public    $last_insert_id = 0;
	
	public    $affected_rows = 0;
	
	public    $rows = 0;
	
	public    $tz = '+00:00';

	/**
	* __construct()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 18:00:49
	*/
	function __construct($params = array())
	{
		$this->config = Hb_Util_Config_SystemConfigReader::Read('db'); 
	   $params = array(
	   		'host' => $this->config->host, 
	   		'username' => $this->config->user, 
	   		'password' => $this->config->pass, 
	   		'dbname' => $this->config->name);
		
		//$params = array_merge($this->__default_params, $params);		
		
		if ($params['host'] == 'localhost' && Hb_Util::isPhp52()) {
			$params['unix_socket'] = '/tmp/mysql.sock';
		}
		
		if (Hb_Util::isPhp52()) {
			$this->db = Zend_Db::factory('PDO_MYSQL', $params);	
		} else {
			$this->db = Zend_Db::factory('Pdo_Mysql', $params);	
		}
		
		
		$this->audit_modify = array(
			'modified_by'   => $_SESSION['admin_id'],
			'modified_date' => 'NOW()'
		);
		
		$this->audit_create = array(
			'created_by'   => $_SESSION['admin_id'],
			'created_date' => 'NOW()', 
			'status'       => 'A'
		);

		$this->version = mysql_get_server_info();
		
		if (isset($_SESSION['tz'])) {
			$this->tz = $_SESSION['tz'];
		} elseif (isset($_SESSION['login'])) {
			$this->tz = GetTimeZone();
		}
	}
	
	/**
	* SetSelect()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 17:15:35
	*/
	public  function SetSelect()
	{
		$this->select = $this->db->select();
	}
	
	/**
	* SetAuditDate()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 22:49:46
	*/
	protected function SetAuditDate($ts = null)
	{
		$cts = date("Y/m/d H:i:s");
		if ($ts != null) {
			//
		}
		
		$this->audit_create['created_date']  = $cts;
		$this->audit_modify['modified_date'] = $cts;
	}
	
	/**
	* __Update()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 23:18:40
	*/
	protected function __Update($table, $fields, $where)
	{
		/* set the audit field */
		$this->SetAuditDate();
		
		/* fields to set */
		$set   = array_merge($fields, $this->audit_modify);
		
		/* the real work */
		$this->affected_rows = $this->db->update($table, $set, $where);
	}

	/**
	* __Insert()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 23:18:50
	*/
	protected function __Insert($table, $fields)
	{
		$this->SetAuditDate();
		
		$fields = array_merge($this->audit_create, $fields);
		
		$this->affected_rows  = $this->db->insert($table, $fields);
		
		$this->last_insert_id = $this->db->lastInsertId();
	}
	
	/**
	 * Performs a Insert Query Against The Given Table Returns the row id
	 *
	 * @param string $table Table to insert records
	 * @param array $fields Field,Value Pair Associative Array
	 * @return int
	 */
	public function Insert($table, $fields) 
	{
		$this->__Insert($table, $fields);
		return $this->last_insert_id;
	}
	
	/**
	 * Runs an Update Query Against A Table, returns the number of affected rows
	 *
	 * @param string $table table name
	 * @param array $fields key value pair of the fields to be updated
	 * @param string $where Where Clause
	 * @return int
	 */
	public function Update($table, $fields, $where) 
	{
		$this->__Update($table, $fields, $where);
		return $this->affected_rows;
	}
	
	/**
	 * Executes a Select Query
	 *
	 * @param Zend_Db_Select $select Zend DB Select Object
	 * @return mixed
	 */
	public function Select(Zend_Db_Select $select) 
	{
		return $this->db->fetchAssoc($select);
	}
	
	/**
	 * Get a Zend_DB_Select object
	 *
	 * @return Zend_Db_Select
	 */
	public function getSelect() 
	{
		return $this->db->select();
	}
	
	/**
	 * Select Query Execution
	 *
	 * @param Zend_Db_Select $select
	 * @return array
	 */
	public function FetchAssoc(Zend_Db_Select $select) 
	{
		return $this->db->fetchAssoc($select);
	}
}
?>