<?php

class netmrDB extends base
{
   //holds the current dbhost
   private $_host = '';
   
   //holds the current db
   private $_db = '';
   
   //holds the username
   private $_user = '';
   
   //holds the password
   private $_pass = '';
   
   //holds the connection
   private $_link_main = null;
   
   //holds the connection to study db
   private $_link_sub  = null;

	private $__options = array();
   
   /**
   * netmrDB()
   *
   * @param
   * @param - 
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 29 21:28:47 PDT 2005
   */
   function __construct($options = array())
   {
		$this->__options = $options;

      //TODO: WHAT DO WE DO HERE, SHALL WE CONNECT OR JUST NOT DO ANYTHING
      $this->_host = $options['host'];
      $this->_user = $options['user'];
      $this->_pass = $options['pass'];
      $this->_db   = $options['db'];  
      
      $this->_link_main = $this->_connect();
   }

	private function __reconnect()
	{
		$options = $this->__options;
	
		 $this->_host = $options['host'];
      $this->_user = $options['user'];
      $this->_pass = $options['pass'];
      $this->_db   = $options['db'];

      $this->_link_main = $this->_connect();
	}
 
   /**
   * __destruct()
   *
   * @param
   * @param - 
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 29 21:54:12 PDT 2005
   */
   function __destruct()
   {
      //TODO add code
      if ($this->_link_main) $this->_disconnect($this->_link_main);
      if ($this->_link_sub) $this->_disconnect($this->_link_sub);
      
   }
   
   /**
   * netmrDB()
   *
   * @param
   * @param - 
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Jun 30 16:22:45 PDT 2005
   */
   function netmrDB($options = array())
   {
      
   }
   
   /**
   * Connect()
   *
   * @param
   * @param - 
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 29 21:48:39 PDT 2005
   */
   private function _connect()
   {
      $link = mysql_connect($this->_host, $this->_user, $this->_pass) or $this->errorAlert($this->_get_vars().mysql_error());
      return $link;
   }
   
   /**
   * Disconnect()
   *
   * @param
   * @param - 
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 29 21:51:11 PDT 2005
   */
   private function _disconnect($link)
   {
      mysql_close($link) or die(mysql_error());   
   }
   
   /**
   * _select_db()
   *
   * @param
   * @param - 
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 29 21:51:52 PDT 2005
   */
   private function _select_db()
   {
      mysql_select_db($this->_db) or die(mysql_error());
      
   }

   function _get_vars() {
      $ret = "NetMR HOST -> ".$this->_host."\n";
      $ret .= "NetMR DB -> ".$this->_db."\n";
      $ret .= "=========================================\n";
      return $ret;
   }

   /**
   * GetQuotaOnResponse()
   *
   * @param
   * @param - 
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 29 21:32:33 PDT 2005
   */
   function GetQuotaOnResponse($study_id)
   {
		$logger = Hb_Util_Log_Logger::GetInstance("netmr");

		$logger->LogDebug("NetMR Logger", "Entering MySQL Function");

      if (!mysql_select_db($this->_db, $this->_link_main)) { 
			$logger->LogError("NetMR Logger", "Reconnecting " . mysql_error($this->_link_main));
			
			$this->__reconnect();
			mysql_select_db($this->_db, $this->_link_main);
		}

      
      $this->_host = $this->_get_study_host($study_id);
      
      if (!$this->_host) return false;
         
		$logger->LogDebug("NetMR Logger", "Setting Study Host " . $this->_host);

      $this->_link_sub = $this->_connect();
      
      mysql_select_db("s_".$study_id, $this->_link_sub) or die($logger->LogError("NetMR Logger", "MySQL Error on Select DB s_". $study_id . " " . mysql_error($this->_link_sub)));
      
      $qry = "SELECT q.id AS quota_id, q.label AS quota_label, q.quota AS quota_required, q.progress AS quota_progress, "
           . "       q.oldprogress AS quota_old, q.comment AS quota_comment "
           . "FROM qpercents AS q ";
           
      $rs = mysql_query($qry, $this->_link_sub) or die($logger->LogError("NetMR Logger", "MySQL Error on Query " . $qry . mysql_error($this->_link_sub)));
      
      return $rs;
   }

   
   /**
   * GetQuotaOnResponse()
   *
   * @param
   * @param - 
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 29 21:32:33 PDT 2005
   */
   function GetQuotaByRegion($study_id)
   {
      mysql_select_db($this->_db, $this->_link_main) or die(mysql_error());
      
      $this->_host = $this->_get_study_host($study_id);
      
      if (!$this->_host) return false;
         
      $this->_link_sub = $this->_connect();
      
      mysql_select_db("s_".$study_id, $this->_link_sub) or die(mysql_error());
      
      $qry = "SELECT p.id AS precent_id, p.region_id, p.percent, p.ratio, p.clause, p.quota, p.progress, p.oldprogress,  "
           . "       s.id AS sample_id, s.contact_country, s.subregion, s.number, c.id AS clause_id, c.cl_name, c.cl_text, c.cl_id "
           . "FROM percents AS p "
           . "LEFT OUTER JOIN samples AS s ON s.id = p.region_id "
           . "LEFT OUTER JOIN clauses AS c ON c.cl_id = p.clause "
           ." ORDER BY c.id, c.cl_id ";
           
      $rs = mysql_query($qry, $this->_link_sub) or die(mysql_error());
      
      return $rs;
           
   }
   
   /**
   * GetQuotaOnResponse()
   *
   * @param
   * @param - 
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 29 21:32:33 PDT 2005
   */
   function GetClause($study_id)
   {
      mysql_select_db($this->_db, $this->_link_main) or die(mysql_error());
      
      $this->_host = $this->_get_study_host($study_id);
      
      if (!$this->_host) return false;
         
      $this->_link_sub = $this->_connect();
      
      mysql_select_db("s_".$study_id, $this->_link_sub) or die(mysql_error());
      
      $qry = "SELECT * "
           . "FROM clauses "
           . "ORDER BY id, cl_id ";
           
      $rs = mysql_query($qry, $this->_link_sub) or die(mysql_error());
      
      return $rs;
           
   }
   
   
   /**
   * GetStudyHost()
   *
   * @param
   * @param - 
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 29 21:40:57 PDT 2005
   */
   function _get_study_host($study_id)
   {
      $qry = "SELECT location FROM studies WHERE study_number = ".$study_id;
      $rs = mysql_query($qry,$this->_link_main) or die(mysql_error());
      $r = mysql_fetch_assoc($rs);
      return ($r) ? $r['location'] : false;
   }
   
}
?>
