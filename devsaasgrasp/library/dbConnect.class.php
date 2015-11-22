<?php
/* the key database configuration file */
/* TODO: do we want to use this class to run queries, or is it simply just creation of a connection;
 *       1. add a method to run queries
 *			1.1 should have a log intergrated with every query execution
 * 				* query, application, user, timestamp, execution time
 */
//include ("/var/www/config/global.inc.php"); //FIXME: remove when all the files have a global configuration file
//require_once ($cfg['base_dir']."/include/db.inc.php");
require_once ("base.class.php");
require_once ("Hb/Util/Config/SystemConfigReader.class.php");
define('LOG_QUERY', 0);

/**
 * connector class for database calls
 *
 */
class dbConnect extends base
{
	private $config = '' ;
	
	private $_defaultOptions = array();
	/* hold options after init */
	private $_options = array();

	/* reference to the link we create later on; */
	protected $_link = 0;

	/* store the last query we executed */
	private $_lastQuery = '';

	private $_xTime = 0;

	/* stores number of rows returned or affected after a query*/
	public $numRows = 0;

	/* depricating numRows adding rows*/
	public $rows = 0;

	public $rs = null;

	/* Last Inserted ID number after an Insert */
	public $lastID = 0;

	// will depricate lastID //
	public $last_id = 0;

	public $ex_where = '';

	public $sort_by  = '';

	public $page_limit = '';

	public $created_by = 0;
	
	public $dump_queries = 0;
	
	public $sql_dump = 1;

	/* Constructor, we will connect to the db with defaultOptions if we didnt pass anything in here, and store the
	   reference to the link here and also pass it back incase we need to use it in the code */
	function dbConnect($options = array())
	{
	   global $_SESSION;
	   
	   $this->config = Hb_Util_Config_SystemConfigReader::Read('db'); 
	   $this->_defaultOptions = array('host' => $this->config->host, 'user' => $this->config->user, 'pass' => $this->config->pass, 'db' => $this->config->name);
		//print_r($this->_defaultOptions);
		$this->_options = $this->_defaultOptions; //, $options);
		$this->_link = mysql_connect($this->_options['host'], $this->_options['user'], $this->_options['pass']) or die(mysql_error());
		mysql_select_db($this->_options['db'],$this->_link) or die(mysql_error());

		if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] != '') {
		   $this->created_by = $_SESSION['admin_id'];
		}

	}


	/* close the connection */
	function dbClose($sqldb = 0)
	{
		if ($sqldb) {
			mysql_close($sqldb);
		} else {
			mysql_close($this->_link);
		}
	}


	/* return the reference to the link we create ealier */
	function getLink()
	{
		if ($this->_link) {
			return $this->_link;
		} else {
			return false;
		}
	}

	//depricated function
	function escapeString($o)
	{
		return mysql_escape_string($o);
	}

	//unescape our stuff
	function unescapeIt($o)
	{
		foreach ($o as $key => $val) {
			$o[$key] = stripslashes($val);
		}
		return $o;
	}

	//escape our stuff
	function escapeIt($o)
	{
		foreach ($o as $key => $val) {
			$o[$key] = mysql_escape_string($val);
		}
		return $o; //we have to escape it so we have clean data
	}

	function executeQuery($query)
	{
		$this->_lastQuery = $query;

		$beginTime = microtime();
		$rsExecute = mysql_query($query,$this->_link) or $this->errorAlert($query." ".mysql_error());
		$endTime = microtime();
		$this->_xTime = $endTime - $beginTime;

		if (preg_match("/^SELECT/",$query)) {
			$this->rows = mysql_num_rows($rsExecute);
		} else {
			$this->rows = mysql_affected_rows($this->_link);
		}

		if (preg_match("/^INSERT/",$query)) {
			$this->last_id = mysql_insert_id($this->_link);
		}

		if (LOG_QUERY == 1)
      {
         $this->logQuery();
      }

      $this->numRows = $this->rows;

      $this->lastID = $this->last_id;

      $this->rs = $rsExecute;

		return $rsExecute;
	}

	function logQuery()
	{
		$query = mysql_escape_string($this->_lastQuery);

		$qLog = "INSERT INTO queryLog (`query`,`xTime`,`numRows`) VALUES ('".$query."','".$this->_xTime."','".$this->numRows."')";
		$rsLog = mysql_query($qLog,$this->_link);

		return true;

	}


	/* select the db */
	function selectDb($dbName = 0)
	{
		if(!$dbName) {
			return mysql_select_db($this->_options['db']);
		} else {
			return mysql_select_db($dbName);
		}
	}

	/*Add Attribute*/
	function addKey($tName,$reference,$key,$value)
	{
		$qKey = "INSERT INTO $tName VALUES ('$reference','$key','$value')";
		$rsKey = mysql_query($qKey,$this->_link);
		return ($rsKey) ? true : false;
	}

	/*Get Attribute */
	function getKey($tName,$reference,$key)
	{
		$qKey = "SELECT value FROM $tName WHERE `key` = '$key' AND id = '$reference'";
		$rsKey = mysql_query($qKey,$this->_link) or die (mysql_error());
		return (mysql_num_rows($rsKey) != 0) ? mysql_result($rsKey,0,0) : false;
	}

	/* Update Attribute */
	function updateKey($tName,$reference,$key,$value)
	{
		$qKey = "REPLACE INTO $tName VALUES ('$reference','$key','$value')";
		$rsKey = mysql_query($qKey,$this->_link) or die (mysql_error());
		return true;
	}

	/**
* setSortOrder()
*
* @param
* @param
* @return
* @throws	PhpdocError
* @access	public
* @global array $argc, string $PHP_SELF
*/

  function setSortOrder($oData, &$sortField, &$sortOrder, &$sort )
    {

    $sortField = (isset($oData[sortField]) ?  $oData[sortField] : "");
    $sortOrder = (isset($oData[sortOrder]) ?  $oData[sortOrder] : "");
    $sort = (($sortField <> "") ?  "ORDER BY " . $sortField . " " . $sortOrder . " " : "");

    } // end func setSortOrder

    /**
    * StartTransaction()
    *
    * @param
    * @param -
    * @return
    * @throws
    * @access
    * @global
    * @since  - Fri Oct 07 17:41:03 PDT 2005
    */
    function StartTransaction()
    {
       $q = "START TRANSACTION";
       return $this->executeQuery($q);
    }

    /**
    * CommitTransaction()
    *
    * @param
    * @param -
    * @return
    * @throws
    * @access
    * @global
    * @since  - Fri Oct 07 17:41:46 PDT 2005
    */
    function CommitTransaction()
    {
       $q = "COMMIT";
       return $this->executeQuery($q);
    }

    /**
    * GetServerStatus()
    *
    * @param
    * @param -
    * @return
    * @throws
    * @access
    * @global
    * @since  - Thu Nov 10 14:53:30 PST 2005
    */
    function GetServerStatus()
    {
       return explode('   ',mysql_stat($this->_link));
    }

    function executeQuery2($query)
	{
		if ($this->dump_queries && $_SESSION['admin_id']!=10441) {
			//die("sorry ".$_SESSION['admin_id'].". Eric's testing some stuff right now. Try again later.");
		}
		$this->_lastQuery = $query;

		$beginTime = microtime();
		$rsExecute = mysql_query($query,$this->_link) or $this->errorAlert($query." ".mysql_error());
		$endTime = microtime();
		$this->_xTime = $endTime - $beginTime;
		
		//begin more code
		if ($this->dump_queries) {
			fwrite($this->log_fp, $query.";\n") or die("sql log error");
		}
		//end more code

		if (preg_match("/^SELECT/",$query)) {
			$this->rows = mysql_num_rows($rsExecute);

			//debug code--
			if ($this->sql_dump) {
				$nokey = 0;
				$rs = mysql_query("EXPLAIN ".$query, $this->_link) or die(mysql_error());
				$html = "$query<br>";
				$row = mysql_fetch_assoc($rs);
				$html.= "<table width='1' border=1>\n<tr>";
				foreach($row as $key=>$val) {
					$html.= "<th>$key</th>";
				}
				$html.= "</tr>\n";
				while ($row) {
					if ($dump_all || $row['key'] == "") $nokey = 1;
					$html.= "<tr>";
					foreach ($row as $x) {
						$x = str_replace(",", ", ", $x);
						$html.= "<td>$x</td>";
					}
					$html.= "</tr>\n";
					$row = mysql_fetch_assoc($rs);
				}
				$html.= "</table><br/><br/>";
				if ($nokey) echo $html;

				//fwrite($this->log_fp, $html) or die("sql log error");
			}
			//--end debug code
		} else {
			$this->rows = mysql_affected_rows($this->_link);
		}

		if (preg_match("/^INSERT/",$query)) {
			$this->lastID = mysql_insert_id($this->_link);
		}

		if (LOG_QUERY == 1)
      {
         $this->logQuery();
      }

      $this->numRows = $this->rows;

      $this->rs = $rsExecute;
      
      $this->lastID = $this->last_id;

		return $rsExecute;
	}

	/**
	* getDataArray()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Nov 29 10:38:02 PST 2005
	*/
	function getDataArray($rs)
	{
		if (mysql_num_rows($rs)>0) {
			return mysql_fetch_array($rs,MYSQL_ASSOC);
		}
		return Array();

	}

	/**
	* GetField()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Nov 29 12:21:42 PST 2005
	*/
	function GetField($rs, $row, $col)
	{
		if(mysql_num_rows($rs) > $row) {
			return mysql_result($rs,$row,$col);
		}
		return false;
	}
	
	/**
	* MysqlFetchAssoc()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Sun Feb 19 21:07:02 PST 2006
	*/
	function FetchAssoc($rs)
	{
		return mysql_fetch_assoc($rs);
	}

}
?>
