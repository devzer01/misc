<?php

class atmDB extends dbConnect
{
	/* Constructur we will store the ID of the BR here */
	function atmDB()
	{
		$this->dbConnect();
	}


//	/**
//	* CreateTempAttrTable()
//	*
//	* @@param
//	* @@param -
//	* @@return
//	* @@throws
//	* @@access
//	* @@global
//	* @@since  - Tue Jan 24 10:35:21 PST 2006
//	*/
//	function CreateTempAttrTable()
//	{
//	   $q = "DROP TABLE IF EXISTS `armc_attr_temp`;";
//	   $this->executeQuery($q);
//
//	   $q = "CREATE TABLE `armc_attr_temp` ("
//	   ." `armc_id` int(10) unsigned default NULL,";
//
//	   $q_attr_def = "SELECT armc_attr_name FROM armc_attr_def WHERE status='A'";
//	   $rst = $this->executeQuery($q_attr_def);
//
//	   while ($attr=mysql_fetch_assoc($rst)) {
//	   	$q .= " `".$attr['armc_attr_name']."` varchar(100) default NULL,";
//	   }
//	   $q .= " PRIMARY KEY (`armc_id`)) TYPE = MyISAM;";
//
//	   $this->executeQuery($q);
//
//	   $fields = array();
//	   $values = array();
//
//	   $q = "SELECT `armc_id`, `armc_attr_name`, `armc_attr_value` FROM `armc_attr` WHERE `status`='A';";
//	   $rst = $this->executeQuery($q);
//	   while ($attr = mysql_fetch_assoc($rst)) {
//	      if (!isset($fields[$attr['armc_id']])) {
//	        $fields[$attr['armc_id']] = "(`armc_id`, `".$attr['armc_attr_name']."`";
//	      }else{
//	        $fields[$attr['armc_id']] .= ", `".$attr['armc_attr_name']."`";
//	      }
//
//	      if (!isset($values[$attr['armc_id']])) {
//	         $values[$attr['armc_id']] = "('".$attr['armc_id']."', '".$attr['armc_attr_value']."'";
//	      }else{
//	         $values[$attr['armc_id']] .= ", '".$attr['armc_attr_value']."'";
//	      }
//	   }
//
//      foreach($fields as $armc_id=>$qq) {
//   	   $q = "REPLACE INTO `armc_attr_temp` ";
//         $q .= $fields[$armc_id].")";
//         $q .= " VALUES ".$values[$armc_id].")";
//         $this->executeQuery($q);
//      }
//
//	}
//
//
//	/**
//	* CreateTempGroupAttrTable()
//	*
//	* @@param
//	* @@param -
//	* @@return
//	* @@throws
//	* @@access
//	* @@global
//	* @@since  - Tue Jan 24 10:35:21 PST 2006
//	*/
//	function CreateTempGroupAttrTable()
//	{
//	   $q = "DROP TABLE IF EXISTS `armc_group_attr_temp`;";
//	   $this->executeQuery($q);
//
//	   $q = "CREATE TABLE `armc_group_attr_temp` ("
//	   ." `armc_group_id` int(10) unsigned default NULL,";
//
//	   $q_attr_def = "SELECT armc_attr_name FROM armc_attr_def WHERE status='A'";
//	   $rst = $this->executeQuery($q_attr_def);
//
//	   while ($attr=mysql_fetch_assoc($rst)) {
//	   	$q .= " `".$attr['armc_attr_name']."` varchar(100) default NULL,";
//	   }
//	   $q .= " PRIMARY KEY (`armc_group_id`)) TYPE = MyISAM;";
//
//	   $this->executeQuery($q);
//
//	   $fields = array();
//	   $values = array();
//
//	   $q = "SELECT `armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value` FROM `armc_group_attr` WHERE `status`='A';";
//	   $rst = $this->executeQuery($q);
//	   while ($attr = mysql_fetch_assoc($rst)) {
//	      if (!isset($fields[$attr['armc_group_id']])) {
//	        $fields[$attr['armc_group_id']] = "(`armc_group_id`, `".$attr['armc_group_attr_name']."`";
//	      }else{
//	        $fields[$attr['armc_group_id']] .= ", `".$attr['armc_group_attr_name']."`";
//	      }
//
//	      if (!isset($values[$attr['armc_group_id']])) {
//	         $values[$attr['armc_group_id']] = "('".$attr['armc_group_id']."', '".$attr['armc_group_attr_value']."'";
//	      }else{
//	         $values[$attr['armc_group_id']] .= ", '".$attr['armc_group_attr_value']."'";
//	      }
//	   }
//
//      foreach($fields as $armc_group_id=>$qq) {
//   	   $q = "REPLACE INTO `armc_group_attr_temp` ";
//         $q .= $fields[$armc_group_id].")";
//         $q .= " VALUES ".$values[$armc_group_id].")";
//         $this->executeQuery($q);
//      }
//
//	}

   /**
   * GetEvents()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Fri Mar 10 11:06:27 PST 2006
   */
   function GetEvents($start_date, $end_date, $event_types=array())
   {
      $q =
      "SELECT
         a.armc_id,
         a.armc_type_id,
         ae.created_by,
         ae.created_date,
         at.armc_type_prefix,
         at.armc_type_description,
         ae.armc_event_type_id,
         aet.armc_event_type_description,
         aa_account_id.armc_attr_value AS account_id,
         aa_account_name.armc_attr_value AS account_name,
         aa_study_id.armc_attr_value AS study_id,
         aa_study_name.armc_attr_value AS study_name,
         aee.login AS account_executive_id,
         aee.last_name AS account_executive,
         am.login AS account_manager_id,
         am.last_name AS account_manager,
         aa_amount.armc_attr_value AS amount
      FROM armc_event AS ae
      LEFT JOIN armc_event_type AS aet ON aet.armc_event_type_id = ae.armc_event_type_id
      LEFT JOIN armc AS a ON a.armc_id = ae.armc_id AND a.status='A'
      LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
      LEFT JOIN armc_attr AS aa_account_id ON aa_account_id.armc_id = a.armc_id AND aa_account_id.armc_attr_name = 'ACCOUNT_ID'
      LEFT JOIN armc_attr AS aa_account_name ON aa_account_name.armc_id = a.armc_id AND aa_account_name.armc_attr_name = 'ACCOUNT_NAME'
      LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id = a.armc_id AND aa_study_id.armc_attr_name = 'STUDY_ID'
      LEFT JOIN armc_attr AS aa_study_name ON aa_study_name.armc_id = a.armc_id AND aa_study_name.armc_attr_name = 'STUDY_NAME'
      LEFT JOIN armc_user AS au_ae ON au_ae.armc_id = a.armc_id AND au_ae.role_id = '".ROLE_PRIMARY_ACCT_EXEC."' AND au_ae.status='A'
      LEFT JOIN user AS aee ON aee.login = au_ae.login
      LEFT JOIN armc_user AS au_am ON au_am.armc_id = a.armc_id AND au_am.role_id = '".ROLE_PRIMARY_ACCT_MGR."' AND au_am.status='A'
      LEFT JOIN user AS am ON am.login = au_am.login
      LEFT JOIN armc_attr AS aa_amount ON aa_amount.armc_id = a.armc_id AND aa_amount.armc_attr_name = 'AMOUNT'
      WHERE (ae.status='A') AND (ae.created_date BETWEEN '$start_date' AND '$end_date') ";
      if (sizeof($event_types)>0) {
         $q .= " AND (ae.armc_event_type_id IN (".implode(", ", $event_types).")) ";
      }
      $q .= "GROUP BY ae.armc_event_id, ae.armc_id";
      return $this->executeQuery($q);

   }

   /**
   * GetARMCDelayedAMApproval()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Apr 17 14:13:34 PDT 2006
   */
   function GetARMCDelayedAMApproval($ams=array())
   {
      $q =
      "SELECT
         a.armc_id,
         a.armc_status_id,
         a.armc_type_id,
         at.armc_type_prefix,
         at.armc_type_description,
         ae.armc_event_type_id,
         MAX(ae.created_date) AS waiting_since,
         aa_account_id.armc_attr_value AS account_id,
         aa_account_name.armc_attr_value AS account_name,
         aa_study_id.armc_attr_value AS study_id,
         aa_study_name.armc_attr_value AS study_name,
         am.login AS account_manager_id,
         CONCAT(am.first_name, ' ', am.last_name) AS account_manager,
         aa_amount.armc_attr_value AS amount
      FROM armc AS a
      LEFT JOIN armc_type AS at ON at.armc_type_id=a.armc_type_id
      LEFT JOIN armc_event AS ae ON ae.armc_id=a.armc_id AND ae.armc_event_type_id = ".ARMC_EVENT_TYPE_STATUS_AM_APPROVAL."
      LEFT JOIN armc_attr AS aa_account_id ON aa_account_id.armc_id=a.armc_id AND aa_account_id.armc_attr_name='ACCOUNT_ID'
      LEFT JOIN armc_attr AS aa_account_name ON aa_account_name.armc_id=a.armc_id AND aa_account_name.armc_attr_name='ACCOUNT_NAME'
      LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id=a.armc_id AND aa_study_id.armc_attr_name='STUDY_ID'
      LEFT JOIN armc_attr AS aa_study_name ON aa_study_name.armc_id=a.armc_id AND aa_study_name.armc_attr_name='STUDY_NAME'
      LEFT JOIN armc_user AS au ON au.armc_id=a.armc_id AND au.role_id=".ROLE_PRIMARY_ACCT_MGR." AND au.status='A'
      LEFT JOIN user AS am ON am.login=au.login
      LEFT JOIN armc_attr AS aa_amount ON aa_amount.armc_id=a.armc_id AND aa_amount.armc_attr_name='AMOUNT'
      WHERE a.status='A' AND a.armc_status_id = ".ARMC_STATUS_AM_APPROVAL;
      if (sizeof($ams)>0) {
         $q .= " AND am.login IN (".implode(", ", $ams).")";
      }
      $q .= " GROUP BY a.armc_id";
      $q .= " ORDER BY waiting_since";
      return $this->executeQuery($q);
   }

   /**
   * GetARMCDelayedStalled()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Jul 10 16:08:26 PDT 2006
   */
   function GetARMCDelayedStalled($ams=array())
   {
      $q =
      "SELECT
         a.armc_id,
         a.armc_status_id,
         a.armc_type_id,
         at.armc_type_prefix,
         at.armc_type_description,
         ae.armc_event_type_id,
         MAX(ae.created_date) AS waiting_since,
         aa_account_id.armc_attr_value AS account_id,
         aa_account_name.armc_attr_value AS account_name,
         aa_study_id.armc_attr_value AS study_id,
         aa_study_name.armc_attr_value AS study_name,
         am.login AS account_manager_id,
         CONCAT(am.first_name, ' ', am.last_name) AS account_manager,
         aa_amount.armc_attr_value AS amount
      FROM armc AS a
      LEFT JOIN armc_type AS at ON at.armc_type_id=a.armc_type_id
      LEFT JOIN armc_event AS ae ON ae.armc_id=a.armc_id AND ae.armc_event_type_id = ".ARMC_EVENT_TYPE_STALLED."
      LEFT JOIN armc_attr AS aa_account_id ON aa_account_id.armc_id=a.armc_id AND aa_account_id.armc_attr_name='ACCOUNT_ID'
      LEFT JOIN armc_attr AS aa_account_name ON aa_account_name.armc_id=a.armc_id AND aa_account_name.armc_attr_name='ACCOUNT_NAME'
      LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id=a.armc_id AND aa_study_id.armc_attr_name='STUDY_ID'
      LEFT JOIN armc_attr AS aa_study_name ON aa_study_name.armc_id=a.armc_id AND aa_study_name.armc_attr_name='STUDY_NAME'
      LEFT JOIN armc_user AS au ON au.armc_id=a.armc_id AND au.role_id=".ROLE_PRIMARY_ACCT_MGR." AND au.status='A'
      LEFT JOIN user AS am ON am.login=au.login
      LEFT JOIN armc_attr AS aa_amount ON aa_amount.armc_id=a.armc_id AND aa_amount.armc_attr_name='AMOUNT'
      WHERE a.status='A' AND a.armc_status_id = ".ARMC_STATUS_STALLED;
      if (sizeof($ams)>0) {
         $q .= " AND am.login IN (".implode(", ", $ams).")";
      }
      $q .= " GROUP BY a.armc_id";
      $q .= " ORDER BY waiting_since";
      return $this->executeQuery($q);
   }

   /**
   * GetARMCDelayedInvoiced()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Jul 10 17:12:49 PDT 2006
   */
   function GetARMCDelayedInvoiced($ams=array())
   {
      $q =
      "SELECT
         a.armc_id,
         a.armc_status_id,
         a.armc_type_id,
         at.armc_type_prefix,
         at.armc_type_description,
         ae.armc_event_type_id,
         MAX(ae.created_date) AS waiting_since,
         aa_account_id.armc_attr_value AS account_id,
         aa_account_name.armc_attr_value AS account_name,
         aa_study_id.armc_attr_value AS study_id,
         aa_study_name.armc_attr_value AS study_name,
         am.login AS account_manager_id,
         CONCAT(am.first_name, ' ', am.last_name) AS account_manager,
         aa_amount.armc_attr_value AS amount
      FROM armc AS a
      LEFT JOIN armc_type AS at ON at.armc_type_id=a.armc_type_id
      LEFT JOIN armc_event AS ae ON ae.armc_id=a.armc_id AND ae.armc_event_type_id IN (".ARMC_EVENT_TYPE_INVOICED.", ".ARMC_EVENT_TYPE_INVOICED_MANUALLY.")
      LEFT JOIN armc_attr AS aa_account_id ON aa_account_id.armc_id=a.armc_id AND aa_account_id.armc_attr_name='ACCOUNT_ID'
      LEFT JOIN armc_attr AS aa_account_name ON aa_account_name.armc_id=a.armc_id AND aa_account_name.armc_attr_name='ACCOUNT_NAME'
      LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id=a.armc_id AND aa_study_id.armc_attr_name='STUDY_ID'
      LEFT JOIN armc_attr AS aa_study_name ON aa_study_name.armc_id=a.armc_id AND aa_study_name.armc_attr_name='STUDY_NAME'
      LEFT JOIN armc_user AS au ON au.armc_id=a.armc_id AND au.role_id=".ROLE_PRIMARY_ACCT_MGR." AND au.status='A'
      LEFT JOIN user AS am ON am.login=au.login
      LEFT JOIN armc_attr AS aa_amount ON aa_amount.armc_id=a.armc_id AND aa_amount.armc_attr_name='AMOUNT'
      WHERE a.status='A' AND a.armc_status_id IN (".ARMC_STATUS_INVOICED.",".ARMC_STATUS_INVOICED_MANUALLY.", ".ARMC_STATUS_INVOICED_PARTIAL_BALANCE.", ".ARMC_STATUS_INVOICED_BALANCED.") AND (ae.created_date >= '".date("Y-m-d H:i:s", mktime(date("h"), date("i"), date("s"), date("m"), date("d"), date("Y"))-30*24*60*60)."')";
      if (sizeof($ams)>0) {
         $q .= " AND am.login IN (".implode(", ", $ams).")";
      }
      $q .= " GROUP BY a.armc_id";
      $q .= " ORDER BY waiting_since";
      return $this->executeQuery($q);
   }

   /**
   * GetARMCListByOffice()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Fri Mar 10 13:01:01 PST 2006
   */
   function GetARMCListByOffice($start_date, $end_date, $role_id=ROLE_PRIMARY_ACCT_EXEC)
   {
      $q = "
      SELECT
         a.armc_status_id,
         a_s.armc_status_description,
         a.armc_type_id,
         at.armc_type_description,
         l.location_id,
         l.location_description,
         r.region_id,
         r.region_description,
         aa_amount.armc_attr_value AS amount
      FROM armc AS a
      LEFT JOIN armc_status AS a_s ON a_s.armc_status_id = a.armc_status_id
      LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
      LEFT JOIN armc_attr AS aa_amount ON aa_amount.armc_id = a.armc_id AND aa_amount.armc_attr_name = 'AMOUNT'
      LEFT JOIN armc_user AS au ON au.armc_id = a.armc_id AND au.role_id = $role_id AND au.status='A'
      LEFT JOIN user AS u ON u.login = au.login
      LEFT JOIN location AS l ON l.location_id = u.location_id
      LEFT JOIN region AS r ON r.region_id = l.region_id
      WHERE a.armc_group_id = 0 AND a.status='A' AND a.armc_date BETWEEN '$start_date' AND '$end_date'
      UNION ALL
      SELECT
         -- MIN(a.armc_status_id) AS armc_status_id,
         aga_status_id.armc_group_attr_value AS armc_status_id,
         a_s.armc_status_description,
         a.armc_type_id,
         at.armc_type_description,
         l.location_id,
         l.location_description,
         r.region_id,
         r.region_description,
         aga_amount.armc_group_attr_value AS amount
      FROM armc AS a
      LEFT JOIN armc_group AS ag ON ag.armc_group_id = a.armc_group_id
      LEFT JOIN armc_group_attr AS aga_status_id ON aga_status_id.armc_group_id = a.armc_group_id AND aga_status_id.armc_group_attr_name = 'ARMC_STATUS_ID'
      LEFT JOIN armc_status AS a_s ON a_s.armc_status_id = aga_status_id.armc_group_attr_value
      LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
      LEFT JOIN armc_group_attr AS aga_amount ON aga_amount.armc_group_id = a.armc_group_id AND aga_amount.armc_group_attr_name='AMOUNT'
      LEFT JOIN armc_user AS au ON au.armc_id = a.armc_id AND au.role_id = $role_id
      LEFT JOIN user AS u ON u.login = au.login
      LEFT JOIN location AS l ON l.location_id = u.location_id
      LEFT JOIN region AS r ON r.region_id = l.region_id
      WHERE a.armc_group_id <> 0 AND ag.status='A' AND a.status='A' AND ag.armc_group_date BETWEEN '$start_date' AND '$end_date'
      GROUP BY a.armc_group_id";

      return $this->executeQuery($q);


   }

	/**
	* GetARMCTypes()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Thu Jan 19 09:36:48 PST 2006
	*/
	function GetARMCTypes()
	{
      $q =
      "SELECT
         at.armc_type_id,
         at.armc_type_prefix,
         at.armc_type_description,
         at.product_id,
         p.product_description,
         CONCAT(p.product_description, ' - ', at.armc_type_description) AS product_type,
         s.armc_setting_value
       FROM armc_type AS at
       LEFT JOIN product AS p ON p.product_id=at.product_id
       LEFT JOIN armc_setting AS s ON s.armc_setting_name = CONCAT('NEXT_', at.armc_type_prefix)
       WHERE at.status = 'A'
       ORDER BY at.armc_type_id";
      return $this->executeQuery($q);
	}

	/**
	* GetARMCCommentTypes()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Fri Feb 17 14:15:51 PST 2006
	*/
	function GetARMCCommentTypes($status="A")
	{
	   $q =
	   "SELECT "
	   ."armc_comment_type_id, "
	   ."armc_comment_type_description, "
	   ."created_by, "
	   ."created_date, "
	   ."modified_by, "
	   ."modified_date "
	   ."FROM armc_comment_type "
	   ."WHERE status='$status' "
	   ."ORDER BY armc_comment_type_id";
	   return $this->executeQuery($q);
	}

	/**
	* GetARMCCommentTypeValues()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Fri Apr 21 10:04:27 PDT 2006
	*/
	function GetARMCCommentTypeValues($armc_comment_type_ids = array())
	{
	   $q =
	   "SELECT "
	   ."armc_comment_type_value_id, "
	   ."armc_comment_type_id, "
	   ."armc_comment_type_value_description, "
	   ."created_by, "
	   ."created_date, "
	   ."modified_by, "
	   ."modified_date "
	   ."FROM armc_comment_type_value "
	   ."WHERE armc_comment_type_id IN (".implode(", ", $armc_comment_type_ids).") AND status='A' "
	   ."ORDER BY armc_comment_type_value_description";
	   return $this->executeQuery($q);
	}

	/**
	* GetARMCStatuses()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Tue Jan 24 14:30:32 PST 2006
	*/
	function GetARMCStatuses()
	{
      $q =
      "SELECT
         armc_status_id,
         armc_status_description
      FROM armc_status
      WHERE status='A'
      ORDER BY armc_status_id";
      return $this->executeQuery($q);
	}

	/**
	* GetARMCStatusID()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Thu Feb 02 13:36:41 PST 2006
	*/
	function GetARMCStatusID($armc_id)
	{
	   $q = "SELECT armc_status_id FROM armc WHERE armc_id = '$armc_id'";
	   $rst = $this->executeQuery($q);
	   return ($this->rows)?mysql_result($rst, 0, "armc_status_id"):false;
	}

	/**
	* GetARMCGroupStatusID()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Tue Feb 21 16:09:57 PST 2006
	*/
	function GetARMCGroupStatusID($armc_group_id)
	{
	   $q = "SELECT MIN(armc_status_id) AS armc_status_id, MAX(armc_status_id) AS is_stalled FROM armc WHERE armc_group_id = '$armc_group_id' AND status='A' GROUP BY armc_group_id";
	   $rst = $this->executeQuery($q);
	   if ($r = mysql_fetch_assoc($rst)) {
         if ($r["is_stalled"]==ARMC_STATUS_STALLED) {
            return ARMC_STATUS_STALLED;
         }else{
            return $r["armc_status_id"];
         }
	   }else{
	      return 0;
	   }
	}

	/**
	* GetARMCGroupDescription()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Tue Feb 21 16:34:43 PST 2006
	*/
	function GetARMCGroupDescription($armc_group_id)
	{
	   $q = "SELECT armc_group_description FROM armc_group WHERE armc_group_id = '$armc_group_id'";
	   $rst = $this->executeQuery($q);
	   return ($this->rows)?mysql_result($rst, 0, "armc_group_description"):false;

	}

	/**
	* GetARMCStatus()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Fri Feb 10 14:35:15 PST 2006
	*/
	function GetARMCStatus($armc_id)
	{
	   $q = "SELECT a.armc_status_id, a_s.armc_status_description FROM armc AS a LEFT JOIN armc_status AS a_s ON a_s.armc_status_id = a.armc_status_id WHERE armc_id = '$armc_id'";
	   $rst = $this->executeQuery($q);
	   return ($this->rows)?mysql_fetch_assoc($rst):false;
	}

	/**
	* GetARMCTypeID()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Mon Feb 20 10:45:40 PST 2006
	*/
	function GetARMCTypeID($armc_id)
	{
	   $q = "SELECT armc_type_id FROM armc WHERE armc_id = '$armc_id'";
	   $rst = $this->executeQuery($q);
	   return ($this->rows)?mysql_result($rst, 0, "armc_type_id"):false;
	}

	/**
	* GetARMCGroupTypeID()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Tue Feb 21 16:08:01 PST 2006
	*/
	function GetARMCGroupTypeID($armc_group_id)
	{
	   $q = "SELECT armc_type_id FROM armc WHERE armc_group_id = '$armc_group_id' GROUP BY armc_group_id";
	   $rst = $this->executeQuery($q);
	   return ($this->rows)?mysql_result($rst, 0, "armc_type_id"):false;
	}

	/**
	* GetARMCType()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Fri Feb 10 14:35:15 PST 2006
	*/
	function GetARMCType($armc_id)
	{
	   $q = "SELECT a.armc_type_id, at.armc_type_prefix, at.armc_type_description FROM armc AS a LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id WHERE armc_id = '$armc_id'";
	   $rst = $this->executeQuery($q);
	   return ($this->rows)?mysql_fetch_assoc($rst):false;
	}

	/**
	* GetARMCProduct()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Fri Feb 10 14:35:15 PST 2006
	*/
	function GetARMCProduct($armc_id)
	{
	   $q = "SELECT a.product_id, p.product_description FROM armc AS a LEFT JOIN product AS p ON p.product_id = a.product_id WHERE armc_id = '$armc_id'";
	   $rst = $this->executeQuery($q);
	   return ($this->rows)?mysql_fetch_assoc($rst):false;
	}

//	/**
//	* GetARMCList()
//	*
//	* @@param
//	* @@param -
//	* @@return
//	* @@throws
//	* @@access
//	* @@global
//	* @@since  - Mon Jan 23 16:26:58 PST 2006
//	*/
//	function GetARMCList($filter, $order, $limit="")
//	{
//	   //$this->CreateTempAttrTable();
//	   $filter_grouped = str_replace("created_date", "ag.created_date", $filter);
//	   $filter_grouped = str_replace("account_id", "aga_account_id.armc_group_attr_value", $filter_grouped);
//	   $filter = str_replace("created_date", "a.created_date", $filter);
//	   $filter = str_replace("account_id", "aa_account_id.armc_attr_value", $filter);
//	   $q =
//	   "SELECT
//	        'false' AS grouped,
//	        a.armc_id,
//	        a.armc_group_id,
//	        a.created_date,
//	        a.transaction_date,
//	        a.transaction_number,
//	        a.armc_status_id,
//	        a_s.armc_status_description,
//	        a.armc_type_id,
//	        at.armc_type_prefix,
//	        at.armc_type_description,
//	        aa_study_id.armc_attr_value AS study_id,
//	        aa_study_name.armc_attr_value AS study_name,
//	        aa_account_id.armc_attr_value AS account_id,
//	        aa_account_name.armc_attr_value AS account_name,
//	        aa_account_country_code.armc_attr_value AS country_code,
//	        country.country_description,
//	        country.region_id,
//	        region.region_description,
//	        aa_tracker.armc_attr_value AS tracker,
//	        aa_month_end.armc_attr_value AS is_month_end,
//	        ae.login AS account_executive_id,
//	        ae.last_name AS account_executive,
//	        am.login AS account_manager_id,
//	        am.last_name AS account_manager,
//	        SUM(abli.actual_quantity*abli.actual_rate) AS amount
//	    FROM armc AS a
//	    LEFT JOIN armc_status AS a_s ON a_s.armc_status_id = a.armc_status_id
//	    LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
//	    LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id = a.armc_id AND aa_study_id.armc_attr_name = 'STUDY_ID'
//	    LEFT JOIN armc_attr AS aa_study_name ON aa_study_name.armc_id = a.armc_id AND aa_study_name.armc_attr_name = 'STUDY_NAME'
//	    LEFT JOIN armc_attr AS aa_account_id ON aa_account_id.armc_id = a.armc_id AND aa_account_id.armc_attr_name = 'ACCOUNT_ID'
//	    LEFT JOIN armc_attr AS aa_account_name ON aa_account_name.armc_id = a.armc_id AND aa_account_name.armc_attr_name = 'ACCOUNT_NAME'
//	    LEFT JOIN armc_attr AS aa_account_country_code ON aa_account_country_code.armc_id = a.armc_id AND aa_account_country_code.armc_attr_name = 'ACCOUNT_COUNTRY_CODE'
//	    LEFT JOIN country ON country.country_code = aa_account_country_code.armc_attr_value
//	    LEFT JOIN region ON region.region_id = country.region_id
//	    LEFT JOIN armc_attr AS aa_tracker ON aa_tracker.armc_id = a.armc_id AND aa_tracker.armc_attr_name = 'STUDY_COUNT'
//	    LEFT JOIN armc_attr AS aa_month_end ON aa_month_end.armc_id = a.armc_id AND aa_month_end.armc_attr_name = 'ARMC_MONTH_END_BILLING'
//	    LEFT JOIN armc_user AS au_ae ON au_ae.armc_id = a.armc_id AND au_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC."
//	    LEFT JOIN user AS ae ON ae.login = au_ae.login
//	    LEFT JOIN armc_user AS au_am ON au_am.armc_id = a.armc_id AND au_am.role_id = ".ROLE_PRIMARY_ACCT_MGR."
//	    LEFT JOIN user AS am ON am.login = au_am.login
//	    LEFT JOIN armc_budget_line_item AS abli FORCE INDEX (armc_id) ON abli.armc_id = a.armc_id
//	    WHERE (a.armc_group_id=0) AND (a.status='A') $filter
//       GROUP BY a.armc_id
//       UNION ALL
//	    SELECT
//	       'true' AS grouped,
//	       a.armc_group_id AS armc_id,
//	       a.armc_group_id,
//	       ag.created_date,
//	       a.transaction_date,
//	       a.transaction_number,
//	       MIN(a.armc_status_id) AS armc_status_id,
//	       a_s.armc_status_description,
//	       a.armc_type_id,
//	       CONCAT('M', at.armc_type_prefix) AS armc_type_prefix,
//	       CONCAT('Merged ', at.armc_type_description) AS armc_type_description,
//          GROUP_CONCAT(DISTINCT aa_study_id.armc_attr_value SEPARATOR ', ') AS study_id,
//	       ag.armc_group_description AS study_name,
//	       aga_account_id.armc_group_attr_value AS account_id,
//	       aga_account_name.armc_group_attr_value AS account_name,
//	       aga_account_country_code.armc_group_attr_value AS country_code,
//          country.country_description,
//	       country.region_id,
//	       region.region_description,
//	       0 AS tracker,
//	       aa_month_end.armc_attr_value AS is_month_end,
//	       ae.login AS account_executive_id,
//	       ae.last_name AS account_executive,
//	       am.login AS account_manager_id,
//	       am.last_name AS account_manager,
//	       SUM(abli.actual_quantity*abli.actual_rate) AS amount
//	    FROM armc_group AS ag
//	    LEFT JOIN armc AS a ON a.armc_group_id = ag.armc_group_id AND a.status='A'
//	    LEFT JOIN armc_status AS a_s ON a_s.armc_status_id = a.armc_status_id
//	    LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
//	    LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id = a.armc_id AND aa_study_id.armc_attr_name = 'STUDY_ID'
//	    LEFT JOIN armc_group_attr AS aga_account_id ON aga_account_id.armc_group_id = a.armc_group_id AND aga_account_id.armc_group_attr_name = 'ACCOUNT_ID'
//	    LEFT JOIN armc_group_attr AS aga_account_name ON aga_account_name.armc_group_id = a.armc_group_id AND aga_account_name.armc_group_attr_name = 'ACCOUNT_NAME'
//	    LEFT JOIN armc_group_attr AS aga_account_country_code ON aga_account_country_code.armc_group_id = a.armc_group_id AND aga_account_country_code.armc_group_attr_name = 'ACCOUNT_COUNTRY_CODE'
//	    LEFT JOIN country ON country.country_code = aga_account_country_code.armc_group_attr_value
//	    LEFT JOIN region ON region.region_id = country.region_id
//	    LEFT JOIN armc_attr AS aa_month_end ON aa_month_end.armc_id = a.armc_id AND aa_month_end.armc_attr_name = 'ARMC_MONTH_END_BILLING'
//       LEFT JOIN armc_user AS au_ae ON au_ae.armc_id = a.armc_id AND au_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC."
//	    LEFT JOIN user AS ae ON ae.login = au_ae.login
//	    LEFT JOIN armc_user AS au_am ON au_am.armc_id = a.armc_id AND au_am.role_id = ".ROLE_PRIMARY_ACCT_MGR."
//	    LEFT JOIN user AS am ON am.login = au_am.login
//	    LEFT JOIN armc_budget_line_item AS abli FORCE INDEX (armc_id) ON abli.armc_id = a.armc_id
//	    WHERE (a.armc_group_id<>0) AND (ag.status='A') $filter_grouped
//	    GROUP BY a.armc_group_id
//  	    $order
//  	    $limit
//	    ";
//
//	   return $this->executeQuery($q);
//	}
//

	/**
	* GetARMCList()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Mon Jan 23 16:26:58 PST 2006
	*/
	function GetARMCList($filter, $order, $limit="")
	{
	   //$this->CreateTempAttrTable();
	   $filter_grouped = str_replace("armc_id", "a.armc_group_id", $filter);
	   $filter_grouped = str_replace("armc_date", "ag.armc_group_date", $filter_grouped);
	   $filter_grouped = str_replace("account_id", "aga_account_id.armc_group_attr_value", $filter_grouped);
	   $filter_grouped = str_replace("a.armc_status_id", "aga_status_id.armc_group_attr_value", $filter_grouped);
	   $filter_grouped = str_replace("aa_job.armc_attr_value", "aga_job.armc_group_attr_value", $filter_grouped);
	   $filter_grouped = str_replace("aa_po.armc_attr_value", "aga_po.armc_group_attr_value", $filter_grouped);
	   $filter = str_replace("armc_id", "a.armc_id", $filter);
	   $filter = str_replace("armc_date", "a.armc_date", $filter);
	   $filter = str_replace("account_id", "aa_account_id.armc_attr_value", $filter);
	   $q =
	   "SELECT
	        'false' AS grouped,
	        CONCAT(at.armc_type_prefix, '-', a.armc_id) AS armc_number,
	        a.armc_id,
	        a.armc_group_id,
	        a.armc_date,
	        a.transaction_date,
	        a.transaction_number,
	        a.armc_status_id,
	        a_s.armc_status_description,
	        a.armc_type_id,
	        at.armc_type_prefix,
	        at.armc_type_description,
	        a.product_id,
	        p.product_description,
	        aa_study_id.armc_attr_value AS study_id,
	        aa_study_name.armc_attr_value AS study_name,
	        aa_account_id.armc_attr_value AS account_id,
	        aa_account_name.armc_attr_value AS account_name,
	        aa_account_country_code.armc_attr_value AS country_code,
	        country.country_description,
	        country.region_id,
	        region.region_description,
	        aa_tracker.armc_attr_value AS tracker,
	        aa_month_end.armc_attr_value AS is_month_end,
	        ae.login AS account_executive_id,
	        ae.last_name AS account_executive,
	        am.login AS account_manager_id,
	        am.last_name AS account_manager,
	        au.login AS user_id,
	        CAST(aa_amount.armc_attr_value AS SIGNED) AS sort_amount,
           aa_amount.armc_attr_value AS amount,
 	        aa_currency_code.armc_attr_value AS i18n_currency_code,
 	        aa_exchange_rate.armc_attr_value AS i18n_exchange_rate
           
	    FROM armc AS a
	    LEFT JOIN armc_status AS a_s ON a_s.armc_status_id = a.armc_status_id
	    LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
	    LEFT JOIN product AS p ON p.product_id = a.product_id
	    LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id = a.armc_id AND aa_study_id.armc_attr_name = 'STUDY_ID'
	    LEFT JOIN armc_attr AS aa_study_name ON aa_study_name.armc_id = a.armc_id AND aa_study_name.armc_attr_name = 'STUDY_NAME'
	    LEFT JOIN armc_attr AS aa_account_id ON aa_account_id.armc_id = a.armc_id AND aa_account_id.armc_attr_name = 'ACCOUNT_ID'
	    LEFT JOIN armc_attr AS aa_account_name ON aa_account_name.armc_id = a.armc_id AND aa_account_name.armc_attr_name = 'ACCOUNT_NAME'
	    LEFT JOIN armc_attr AS aa_account_country_code ON aa_account_country_code.armc_id = a.armc_id AND aa_account_country_code.armc_attr_name = 'ACCOUNT_COUNTRY_CODE'
	    LEFT JOIN armc_attr AS aa_currency_code ON aa_currency_code.armc_id=a.armc_id AND aa_currency_code.armc_attr_name='I18N_CURRENCY_CODE'
	    LEFT JOIN armc_attr AS aa_exchange_rate ON aa_exchange_rate.armc_id=a.armc_id AND aa_exchange_rate.armc_attr_name='I18N_EXCHANGE_RATE'
	    LEFT JOIN armc_attr AS aa_job ON aa_job.armc_id = a.armc_id AND aa_job.armc_attr_name = 'JOBNUMBER'
	    LEFT JOIN armc_attr AS aa_po ON aa_po.armc_id = a.armc_id AND aa_po.armc_attr_name = 'PONUMBER'
	    LEFT JOIN country ON country.country_code = aa_account_country_code.armc_attr_value
	    LEFT JOIN region ON region.region_id = country.region_id
	    LEFT JOIN armc_attr AS aa_tracker ON aa_tracker.armc_id = a.armc_id AND aa_tracker.armc_attr_name = 'STUDY_COUNT'
	    LEFT JOIN armc_attr AS aa_month_end ON aa_month_end.armc_id = a.armc_id AND aa_month_end.armc_attr_name = 'ARMC_MONTH_END_BILLING'
	    LEFT JOIN armc_user AS au_ae ON au_ae.armc_id = a.armc_id AND au_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC." AND au_ae.status='A'
	    LEFT JOIN user AS ae ON ae.login = au_ae.login
	    LEFT JOIN armc_user AS au_am ON au_am.armc_id = a.armc_id AND au_am.role_id = ".ROLE_PRIMARY_ACCT_MGR." AND au_am.status='A'
	    LEFT JOIN user AS am ON am.login = au_am.login
	    LEFT JOIN armc_user AS au ON au.armc_id = a.armc_id AND au.status='A'
	    LEFT JOIN armc_attr AS aa_amount ON aa_amount.armc_id = a.armc_id AND aa_amount.armc_attr_name = 'AMOUNT'
	    WHERE (a.armc_group_id=0) AND (a.status='A') $filter
	    GROUP BY a.armc_id
       UNION ALL
	    SELECT
	       'true' AS grouped,
	       CONCAT(at.armc_type_prefix, '-', a.armc_group_id) AS armc_number,
	       a.armc_group_id AS armc_id,
	       a.armc_group_id,
	       ag.armc_group_date AS armc_date,
	       a.transaction_date,
	       a.transaction_number,
	      
	       -- MIN(a.armc_status_id) AS armc_status_id,
	       aga_status_id.armc_group_attr_value AS armc_status_id,
	       a_s.armc_status_description,
	       a.armc_type_id,
	       CONCAT('M', at.armc_type_prefix) AS armc_type_prefix,
	       CONCAT('Merged ', at.armc_type_description) AS armc_type_description,
	       a.product_id,
	       p.product_description,
          GROUP_CONCAT(DISTINCT aa_study_id.armc_attr_value SEPARATOR ', ') AS study_id,
	       ag.armc_group_description AS study_name,
	       aga_account_id.armc_group_attr_value AS account_id,
	       aga_account_name.armc_group_attr_value AS account_name,
	       aga_account_country_code.armc_group_attr_value AS country_code,
          country.country_description,
	       country.region_id,
	       region.region_description,
	       0 AS tracker,
	       aa_month_end.armc_attr_value AS is_month_end,
	       ae.login AS account_executive_id,
	       ae.last_name AS account_executive,
	       am.login AS account_manager_id,
	       am.last_name AS account_manager,
	       au.login AS user_id,
	       CAST(aga_amount.armc_group_attr_value AS SIGNED) AS sort_amount,
               aga_amount.armc_group_attr_value AS amount,
	       aga_currency_code.armc_group_attr_value AS i18n_currency_code,
	       aga_exchange_rate.armc_group_attr_value AS i18n_exchange_rate
	       
	    FROM armc_group AS ag
	    LEFT JOIN armc AS a ON a.armc_group_id = ag.armc_group_id AND a.status='A' 
	    LEFT JOIN armc_group_attr AS aga_status_id ON aga_status_id.armc_group_id = a.armc_group_id AND aga_status_id.armc_group_attr_name = 'ARMC_STATUS_ID'
	    LEFT JOIN armc_status AS a_s ON a_s.armc_status_id = aga_status_id.armc_group_attr_value
	    LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
	    LEFT JOIN product AS p ON p.product_id = a.product_id
	    LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id = a.armc_id AND aa_study_id.armc_attr_name = 'STUDY_ID'
	    LEFT JOIN armc_group_attr AS aga_account_id ON aga_account_id.armc_group_id = a.armc_group_id AND aga_account_id.armc_group_attr_name = 'ACCOUNT_ID'
	    LEFT JOIN armc_group_attr AS aga_account_name ON aga_account_name.armc_group_id = a.armc_group_id AND aga_account_name.armc_group_attr_name = 'ACCOUNT_NAME'
	    LEFT JOIN armc_group_attr AS aga_account_country_code ON aga_account_country_code.armc_group_id = a.armc_group_id AND aga_account_country_code.armc_group_attr_name = 'ACCOUNT_COUNTRY_CODE'
	    LEFT JOIN armc_group_attr AS aga_currency_code ON aga_currency_code.armc_group_id=a.armc_group_id AND aga_currency_code.armc_group_attr_name='I18N_CURRENCY_CODE'
	    LEFT JOIN armc_group_attr AS aga_exchange_rate ON aga_exchange_rate.armc_group_id=a.armc_group_id AND aga_exchange_rate.armc_group_attr_name='I18N_EXCHANGE_RATE'
	    LEFT JOIN armc_group_attr AS aga_job ON aga_job.armc_group_id = a.armc_group_id AND aga_job.armc_group_attr_name = 'JOBNUMBER'
	    LEFT JOIN armc_group_attr AS aga_po ON aga_po.armc_group_id = a.armc_group_id AND aga_po.armc_group_attr_name = 'PONUMBER'
	    LEFT JOIN country ON country.country_code = aga_account_country_code.armc_group_attr_value
	    LEFT JOIN region ON region.region_id = country.region_id
	    LEFT JOIN armc_attr AS aa_month_end ON aa_month_end.armc_id = a.armc_id AND aa_month_end.armc_attr_name = 'ARMC_MONTH_END_BILLING'
       LEFT JOIN armc_user AS au_ae ON au_ae.armc_id = a.armc_id AND au_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC." AND au_ae.status='A'
	    LEFT JOIN user AS ae ON ae.login = au_ae.login
	    LEFT JOIN armc_user AS au_am ON au_am.armc_id = a.armc_id AND au_am.role_id = ".ROLE_PRIMARY_ACCT_MGR." AND au_am.status='A'
	    LEFT JOIN user AS am ON am.login = au_am.login
	    LEFT JOIN armc_user AS au ON au.armc_id = a.armc_id AND au.status='A'
	    LEFT JOIN armc_group_attr AS aga_amount ON aga_amount.armc_group_id=a.armc_group_id AND aga_amount.armc_group_attr_name='AMOUNT' 
	    WHERE (a.armc_group_id<>0) AND (ag.status='A') $filter_grouped
	    GROUP BY a.armc_group_id
  	    $order
  	    $limit
	    ";

	   //echo ($q);

	   return $this->executeQuery($q);
	}

	/**
	* GetARMCListCounts()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Tue Apr 18 17:19:09 PDT 2006
	*/
	function GetARMCListCounts($filter)
	{
	   $filter_grouped = str_replace("armc_id", "a.armc_group_id", $filter);
	   $filter_grouped = str_replace("armc_date", "ag.armc_group_date", $filter_grouped);
	   $filter_grouped = str_replace("account_id", "aga_account_id.armc_group_attr_value", $filter_grouped);
	   $filter_grouped = str_replace("a.armc_status_id", "aga_status_id.armc_group_attr_value", $filter_grouped);
	   $filter_grouped = str_replace("aa_job.armc_attr_value", "aga_job.armc_group_attr_value", $filter_grouped);
	   $filter_grouped = str_replace("aa_po.armc_attr_value", "aga_po.armc_group_attr_value", $filter_grouped);
	   $filter = str_replace("armc_id", "a.armc_id", $filter);
	   $filter = str_replace("armc_date", "a.armc_date", $filter);
	   $filter = str_replace("account_id", "aa_account_id.armc_attr_value", $filter);
	   $q =
	   "SELECT
         COUNT(DISTINCT a.armc_id) AS count
	    FROM armc AS a
	    LEFT JOIN armc_status AS a_s ON a_s.armc_status_id = a.armc_status_id
	    LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
	    LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id = a.armc_id AND aa_study_id.armc_attr_name = 'STUDY_ID'
	    LEFT JOIN armc_attr AS aa_study_name ON aa_study_name.armc_id = a.armc_id AND aa_study_name.armc_attr_name = 'STUDY_NAME'
	    LEFT JOIN armc_attr AS aa_account_id ON aa_account_id.armc_id = a.armc_id AND aa_account_id.armc_attr_name = 'ACCOUNT_ID'
	    LEFT JOIN armc_attr AS aa_account_name ON aa_account_name.armc_id = a.armc_id AND aa_account_name.armc_attr_name = 'ACCOUNT_NAME'
	    LEFT JOIN armc_attr AS aa_account_country_code ON aa_account_country_code.armc_id = a.armc_id AND aa_account_country_code.armc_attr_name = 'ACCOUNT_COUNTRY_CODE'
	    LEFT JOIN armc_attr AS aa_job ON aa_job.armc_id = a.armc_id AND aa_job.armc_attr_name = 'JOBNUMBER'
	    LEFT JOIN armc_attr AS aa_po ON aa_po.armc_id = a.armc_id AND aa_po.armc_attr_name = 'PONUMBER'
	    LEFT JOIN country ON country.country_code = aa_account_country_code.armc_attr_value
	    LEFT JOIN region ON region.region_id = country.region_id
	    LEFT JOIN armc_attr AS aa_tracker ON aa_tracker.armc_id = a.armc_id AND aa_tracker.armc_attr_name = 'STUDY_COUNT'
	    LEFT JOIN armc_attr AS aa_month_end ON aa_month_end.armc_id = a.armc_id AND aa_month_end.armc_attr_name = 'ARMC_MONTH_END_BILLING'
	    LEFT JOIN armc_user AS au_ae ON au_ae.armc_id = a.armc_id AND au_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC." AND au_ae.status='A'
	    LEFT JOIN user AS ae ON ae.login = au_ae.login
	    LEFT JOIN armc_user AS au_am ON au_am.armc_id = a.armc_id AND au_am.role_id = ".ROLE_PRIMARY_ACCT_MGR." AND au_am.status='A'
	    LEFT JOIN user AS am ON am.login = au_am.login
	    LEFT JOIN armc_user AS au ON au.armc_id = a.armc_id
	    LEFT JOIN armc_attr AS aa_amount ON aa_amount.armc_id = a.armc_id AND aa_amount.armc_attr_name = 'AMOUNT'
	    WHERE (a.armc_group_id=0) AND (a.status='A') $filter
       UNION ALL
	    SELECT
         COUNT(DISTINCT ag.armc_group_id) AS count
	    FROM armc_group AS ag
	    LEFT JOIN armc AS a ON a.armc_group_id = ag.armc_group_id AND a.status='A'
	    LEFT JOIN armc_group_attr AS aga_status_id ON aga_status_id.armc_group_id = a.armc_group_id AND aga_status_id.armc_group_attr_name = 'ARMC_STATUS_ID'
	    LEFT JOIN armc_status AS a_s ON a_s.armc_status_id = aga_status_id.armc_group_attr_value
	    LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
	    LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id = a.armc_id AND aa_study_id.armc_attr_name = 'STUDY_ID'
	    LEFT JOIN armc_group_attr AS aga_account_id ON aga_account_id.armc_group_id = a.armc_group_id AND aga_account_id.armc_group_attr_name = 'ACCOUNT_ID'
	    LEFT JOIN armc_group_attr AS aga_account_name ON aga_account_name.armc_group_id = a.armc_group_id AND aga_account_name.armc_group_attr_name = 'ACCOUNT_NAME'
	    LEFT JOIN armc_group_attr AS aga_account_country_code ON aga_account_country_code.armc_group_id = a.armc_group_id AND aga_account_country_code.armc_group_attr_name = 'ACCOUNT_COUNTRY_CODE'
	    LEFT JOIN armc_group_attr AS aga_job ON aga_job.armc_group_id = a.armc_group_id AND aga_job.armc_group_attr_name = 'JOBNUMBER'
	    LEFT JOIN armc_group_attr AS aga_po ON aga_po.armc_group_id = a.armc_group_id AND aga_po.armc_group_attr_name = 'PONUMBER'
	    LEFT JOIN country ON country.country_code = aga_account_country_code.armc_group_attr_value
	    LEFT JOIN region ON region.region_id = country.region_id
	    LEFT JOIN armc_attr AS aa_month_end ON aa_month_end.armc_id = a.armc_id AND aa_month_end.armc_attr_name = 'ARMC_MONTH_END_BILLING'
       LEFT JOIN armc_user AS au_ae ON au_ae.armc_id = a.armc_id AND au_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC." AND au_ae.status='A'
	    LEFT JOIN user AS ae ON ae.login = au_ae.login
	    LEFT JOIN armc_user AS au_am ON au_am.armc_id = a.armc_id AND au_am.role_id = ".ROLE_PRIMARY_ACCT_MGR." AND au_am.status='A'
	    LEFT JOIN user AS am ON am.login = au_am.login
	    LEFT JOIN armc_user AS au ON au.armc_id = a.armc_id
	    LEFT JOIN armc_group_attr AS aga_amount ON aga_amount.armc_group_id=a.armc_group_id AND aga_amount.armc_group_attr_name='AMOUNT'
	    WHERE (a.armc_group_id<>0) AND (ag.status='A') $filter_grouped
	    ";

	   $count = 0;
	   $rst = $this->executeQuery($q);
	   while ($line = mysql_fetch_assoc($rst)) {
	      $count += $line["count"];
	   }
	   return $count;
	}

	/**
	* GetARMCStalledList()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Fri Jun 30 15:41:10 PDT 2006
	*/
	function GetARMCStalledList($filter, $order, $limit="")
	{
	   $filter_grouped = str_replace("a.armc_status_id", "aga_status_id.armc_group_attr_value", $filter);
	   $q = "
SELECT
   'false' AS grouped,
	a.armc_id,
	a.armc_date,
	a.armc_status_id,
	a.armc_type_id,
	at.armc_type_prefix,
	at.armc_type_description,
	aa_study_id.armc_attr_value AS study_id,
	aa_study_name.armc_attr_value AS study_name,
	aa_account_id.armc_attr_value AS account_id,
	aa_account_name.armc_attr_value AS account_name,
	am.last_name AS account_manager,
	l.location_description AS am_location,
	aa_amount.armc_attr_value AS amount,
	GROUP_CONCAT(cmt.comment SEPARATOR '%%%%') AS stall_reason,
	MAX(cmt.created_date) AS stalled_on,
	aa_clear_date.armc_attr_value AS stall_clear_date
FROM armc AS a
LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id = a.armc_id
AND aa_study_id.armc_attr_name = 'STUDY_ID'
LEFT JOIN armc_comment AS cmt ON cmt.armc_id = a.armc_id
AND cmt.armc_comment_type_id = 22
LEFT JOIN armc_attr AS aa_clear_date ON aa_clear_date.armc_id = a.armc_id AND aa_clear_date.armc_attr_name='STALL_CLEAR_DATE'
LEFT JOIN armc_attr AS aa_study_name ON aa_study_name.armc_id = a.armc_id
AND aa_study_name.armc_attr_name = 'STUDY_NAME'
LEFT JOIN armc_attr AS aa_account_id ON aa_account_id.armc_id = a.armc_id
AND aa_account_id.armc_attr_name = 'ACCOUNT_ID'
LEFT JOIN armc_attr AS aa_account_name ON aa_account_name.armc_id = a.armc_id
AND aa_account_name.armc_attr_name = 'ACCOUNT_NAME'
LEFT JOIN armc_attr AS aa_tracker ON aa_tracker.armc_id = a.armc_id
AND aa_tracker.armc_attr_name = 'STUDY_COUNT'
LEFT JOIN armc_user AS au_am ON au_am.armc_id = a.armc_id
AND au_am.role_id =1
LEFT JOIN user AS am ON am.login=au_am.login
LEFT JOIN location AS l ON l.location_id=am.location_id
LEFT JOIN armc_attr AS aa_amount ON aa_amount.armc_id = a.armc_id
AND aa_amount.armc_attr_name = 'AMOUNT'
WHERE (a.armc_group_id =0) AND (a.status = 'A') $filter
GROUP BY a.armc_id
UNION ALL
SELECT
   'true' AS grouped,
	a.armc_group_id AS armc_id,
	ag.armc_group_date AS armc_date,
   aga_status_id.armc_group_attr_value AS armc_status_id,
   a.armc_type_id,
	CONCAT( 'M', at.armc_type_prefix) AS armc_type_prefix,
	CONCAT( 'Merged ', at.armc_type_description ) AS armc_type_description,
	GROUP_CONCAT( DISTINCT aa_study_id.armc_attr_value SEPARATOR ', ' ) AS study_id,
	ag.armc_group_description AS study_name,
	aga_account_id.armc_group_attr_value AS account_id,
	aga_account_name.armc_group_attr_value AS account_name,
	am.last_name AS account_manager,
	l.location_description AS am_location,
	aga_amount.armc_group_attr_value AS amount,
	GROUP_CONCAT(cmt.comment SEPARATOR '%%%%') AS stall_reason,
	MAX(cmt.created_date) AS stalled_on,
	aga_clear_date.armc_group_attr_value AS stall_clear_date
FROM armc_group AS ag
LEFT JOIN armc AS a ON a.armc_group_id = ag.armc_group_id
AND a.status = 'A'
LEFT JOIN armc_group_attr AS aga_status_id ON aga_status_id.armc_group_id = a.armc_group_id
AND aga_status_id.armc_group_attr_name = 'ARMC_STATUS_ID'
LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id = a.armc_id
AND aa_study_id.armc_attr_name = 'STUDY_ID'
LEFT JOIN armc_comment AS cmt ON cmt.armc_id = a.armc_id
AND cmt.armc_comment_type_id =22
LEFT JOIN armc_group_attr AS aga_clear_date ON aga_clear_date.armc_group_id = a.armc_group_id AND aga_clear_date.armc_group_attr_name='STALL_CLEAR_DATE'
LEFT JOIN armc_group_attr AS aga_account_id ON aga_account_id.armc_group_id = a.armc_group_id
AND aga_account_id.armc_group_attr_name = 'ACCOUNT_ID'
LEFT JOIN armc_group_attr AS aga_account_name ON aga_account_name.armc_group_id = a.armc_group_id
AND aga_account_name.armc_group_attr_name = 'ACCOUNT_NAME'
LEFT JOIN armc_user AS au_am ON au_am.armc_id = a.armc_id
AND au_am.role_id =1
LEFT JOIN user AS am ON am.login = au_am.login
LEFT JOIN location AS l ON l.location_id=am.location_id
LEFT JOIN armc_group_attr AS aga_amount ON aga_amount.armc_group_id = a.armc_group_id
AND aga_amount.armc_group_attr_name = 'AMOUNT'
WHERE (a.armc_group_id <>0) AND ( ag.status = 'A') $filter_grouped
GROUP BY a.armc_group_id
$order, stalled_on ASC
$limit";
	   return $this->executeQuery($q);

	}

	/**
	* GetARMCStalledListCounts()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Fri Jun 30 16:21:39 PDT 2006
	*/
	function GetARMCStalledListCounts($filter)
	{
	   $filter_grouped = str_replace("a.armc_status_id", "aga_status_id.armc_group_attr_value", $filter);
	   $q = "
SELECT
         COUNT(DISTINCT a.armc_id) AS count
FROM armc AS a
LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id = a.armc_id
AND aa_study_id.armc_attr_name = 'STUDY_ID'
LEFT JOIN armc_comment AS cmt ON cmt.armc_id = a.armc_id
AND cmt.armc_comment_type_id =22
LEFT JOIN armc_attr AS aa_clear_date ON aa_clear_date.armc_id = a.armc_id AND aa_clear_date.armc_attr_name='STALL_CLEAR_DATE'
LEFT JOIN armc_attr AS aa_study_name ON aa_study_name.armc_id = a.armc_id
AND aa_study_name.armc_attr_name = 'STUDY_NAME'
LEFT JOIN armc_attr AS aa_account_id ON aa_account_id.armc_id = a.armc_id
AND aa_account_id.armc_attr_name = 'ACCOUNT_ID'
LEFT JOIN armc_attr AS aa_account_name ON aa_account_name.armc_id = a.armc_id
AND aa_account_name.armc_attr_name = 'ACCOUNT_NAME'
LEFT JOIN armc_attr AS aa_tracker ON aa_tracker.armc_id = a.armc_id
AND aa_tracker.armc_attr_name = 'STUDY_COUNT'
LEFT JOIN armc_user AS au_am ON au_am.armc_id = a.armc_id
AND au_am.role_id =1
LEFT JOIN user AS am ON am.login=au_am.login
LEFT JOIN location AS l ON l.location_id=am.location_id
LEFT JOIN armc_attr AS aa_amount ON aa_amount.armc_id = a.armc_id
AND aa_amount.armc_attr_name = 'AMOUNT'
WHERE (a.armc_group_id =0) AND (a.status = 'A') $filter
GROUP BY a.armc_id
UNION ALL
SELECT
         COUNT(DISTINCT a.armc_group_id) AS count
FROM armc_group AS ag
LEFT JOIN armc AS a ON a.armc_group_id = ag.armc_group_id
AND a.status = 'A'
LEFT JOIN armc_group_attr AS aga_status_id ON aga_status_id.armc_group_id = a.armc_group_id
AND aga_status_id.armc_group_attr_name = 'ARMC_STATUS_ID'
LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id = a.armc_id
AND aa_study_id.armc_attr_name = 'STUDY_ID'
LEFT JOIN armc_comment AS cmt ON cmt.armc_id = a.armc_id
AND cmt.armc_comment_type_id =22
LEFT JOIN armc_group_attr AS aga_clear_date ON aga_clear_date.armc_group_id = a.armc_group_id AND aga_clear_date.armc_group_attr_name='STALL_CLEAR_DATE'
LEFT JOIN armc_group_attr AS aga_account_id ON aga_account_id.armc_group_id = a.armc_group_id
AND aga_account_id.armc_group_attr_name = 'ACCOUNT_ID'
LEFT JOIN armc_group_attr AS aga_account_name ON aga_account_name.armc_group_id = a.armc_group_id
AND aga_account_name.armc_group_attr_name = 'ACCOUNT_NAME'
LEFT JOIN armc_user AS au_am ON au_am.armc_id = a.armc_id
AND au_am.role_id =1
LEFT JOIN user AS am ON am.login = au_am.login
LEFT JOIN location AS l ON l.location_id=am.location_id
LEFT JOIN armc_group_attr AS aga_amount ON aga_amount.armc_group_id = a.armc_group_id
AND aga_amount.armc_group_attr_name = 'AMOUNT'
WHERE (a.armc_group_id <>0) AND ( ag.status = 'A') $filter_grouped
GROUP BY a.armc_group_id
$order
$limit";
	   $count = 0;
	   $rst = $this->executeQuery($q);
	   while ($line = mysql_fetch_assoc($rst)) {
	      $count += $line["count"];
	   }
	   return $count;

	}

	/**
	* GetARMCInvoicedUnbalanced()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Thu Jul 20 11:20:42 PDT 2006
	*/
	function GetARMCInvoicedUnbalanced($limit = "")
	{
	   $q =
	   "SELECT
	        'false' AS grouped,
	        a.armc_id,
	        a.armc_group_id,
	        a.armc_date,
	        a.transaction_date,
	        a.transaction_number,
	        a.armc_type_id,
	        at.armc_type_prefix,
	        a.armc_status_id,
                aa_txn_id.armc_attr_value AS txn_id,
	        aa_account_id.armc_attr_value AS account_id,
	        aa_amount.armc_attr_value AS amount,
	        aa_amount_paid.armc_attr_value AS amount_paid,
 	        aa_currency_code.armc_attr_value AS i18n_currency_code,
 	        aa_exchange_rate.armc_attr_value AS i18n_exchange_rate
	    FROM armc AS a
	    LEFT JOIN armc_type AS at ON at.armc_type_id=a.armc_type_id
            LEFT JOIN armc_attr AS aa_txn_id ON aa_txn_id.armc_id = a.armc_id AND aa_txn_id.armc_attr_name='TXNID'
	    LEFT JOIN armc_attr AS aa_account_id ON aa_account_id.armc_id = a.armc_id AND aa_account_id.armc_attr_name = 'ACCOUNT_ID'
	    LEFT JOIN armc_attr AS aa_currency_code ON aa_currency_code.armc_id=a.armc_id AND aa_currency_code.armc_attr_name='I18N_CURRENCY_CODE'
	    LEFT JOIN armc_attr AS aa_exchange_rate ON aa_exchange_rate.armc_id=a.armc_id AND aa_exchange_rate.armc_attr_name='I18N_EXCHANGE_RATE'
	    LEFT JOIN armc_attr AS aa_amount ON aa_amount.armc_id = a.armc_id AND aa_amount.armc_attr_name = 'AMOUNT'
	    LEFT JOIN armc_attr AS aa_amount_paid ON aa_amount_paid.armc_id=a.armc_id AND aa_amount_paid.armc_attr_name='AMOUNT_PAID'
	    WHERE (a.armc_group_id=0) AND (a.status='A') AND (a.armc_status_id IN (".ARMC_STATUS_INVOICED.", ".ARMC_STATUS_INVOICED_MANUALLY.", ".ARMC_STATUS_INVOICED_PARTIAL_BALANCE."))
	    GROUP BY a.armc_id
       UNION ALL
	    SELECT
	       'true' AS grouped,
	       a.armc_group_id AS armc_id,
	       a.armc_group_id,
	       ag.armc_group_date AS armc_date,
	       a.transaction_date,
	       a.transaction_number,
	       a.armc_type_id,
	       CONCAT('M', at.armc_type_prefix) AS armc_type_prefix,
	       aga_status_id.armc_group_attr_value AS armc_status_id,
               aga_txn_id.armc_group_attr_value AS txn_id,
	       aga_account_id.armc_group_attr_value AS account_id,
	       aga_amount.armc_group_attr_value AS amount,
	       aga_amount_paid.armc_group_attr_value AS amount_paid,
	       aga_currency_code.armc_group_attr_value AS i18n_currency_code,
	       aga_exchange_rate.armc_group_attr_value AS i18n_exchange_rate
	    FROM armc_group AS ag
	    LEFT JOIN armc AS a ON a.armc_group_id = ag.armc_group_id AND a.status='A'
	    LEFT JOIN armc_type AS at ON at.armc_type_id=a.armc_type_id
	    LEFT JOIN armc_group_attr AS aga_status_id ON aga_status_id.armc_group_id = a.armc_group_id AND aga_status_id.armc_group_attr_name = 'ARMC_STATUS_ID'
            LEFT JOIN armc_group_attr AS aga_txn_id ON aga_txn_id.armc_group_id = a.armc_group_id AND aga_txn_id.armc_group_attr_name = 'TXNID'
	    LEFT JOIN armc_group_attr AS aga_account_id ON aga_account_id.armc_group_id = a.armc_group_id AND aga_account_id.armc_group_attr_name = 'ACCOUNT_ID'
	    LEFT JOIN armc_group_attr AS aga_currency_code ON aga_currency_code.armc_group_id=a.armc_group_id AND aga_currency_code.armc_group_attr_name='I18N_CURRENCY_CODE'
	    LEFT JOIN armc_group_attr AS aga_exchange_rate ON aga_exchange_rate.armc_group_id=a.armc_group_id AND aga_exchange_rate.armc_group_attr_name='I18N_EXCHANGE_RATE'
	    LEFT JOIN armc_group_attr AS aga_amount ON aga_amount.armc_group_id=a.armc_group_id AND aga_amount.armc_group_attr_name='AMOUNT'
	    LEFT JOIN armc_group_attr AS aga_amount_paid ON aga_amount_paid.armc_group_id=a.armc_group_id AND aga_amount_paid.armc_group_attr_name='AMOUNT_PAID'
	    WHERE (a.armc_group_id<>0) AND (ag.status='A') AND (aga_status_id.armc_group_attr_value IN (".ARMC_STATUS_INVOICED.", ".ARMC_STATUS_INVOICED_MANUALLY.", ".ARMC_STATUS_INVOICED_PARTIAL_BALANCE."))
	    GROUP BY a.armc_group_id
	    ORDER BY transaction_date $limit";

	   //echo ($q);

	   return $this->executeQuery($q);
	}

	/**
	* GetARMCStudyList()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Thu Feb 23 11:25:20 PST 2006
	*/
	function GetARMCStudyList($study_id, $armc_type_id, $limit="")
	{
	   $q = "
	   SELECT
	     a.armc_id,
	     a.armc_group_id,
	     a.armc_date,
	     a.armc_type_id,
        at.armc_type_prefix,
        at.armc_type_description,
	     a.armc_status_id,
	     a_s.armc_status_description,
	     a.status,
        aa_study_id.armc_attr_value AS study_id,
        aa_study_name.armc_attr_value AS study_name,
        aa_account_id.armc_attr_value AS account_id,
        aa_account_name.armc_attr_value AS account_name,
        aa_tracker.armc_attr_value AS tracker,
        ae.login AS account_executive_id,
        ae.last_name AS account_executive,
        am.login AS account_manager_id,
        am.last_name AS account_manager,
        aa_amount.armc_attr_value AS amount
 	     FROM armc AS a
 	     LEFT JOIN armc_status AS a_s ON a_s.armc_status_id = a.armc_status_id
	     LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
	     LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id = a.armc_id AND aa_study_id.armc_attr_name = 'STUDY_ID'
	     LEFT JOIN armc_attr AS aa_study_name ON aa_study_name.armc_id = a.armc_id AND aa_study_name.armc_attr_name = 'STUDY_NAME'
	     LEFT JOIN armc_attr AS aa_account_id ON aa_account_id.armc_id = a.armc_id AND aa_account_id.armc_attr_name = 'ACCOUNT_ID'
	     LEFT JOIN armc_attr AS aa_account_name ON aa_account_name.armc_id = a.armc_id AND aa_account_name.armc_attr_name = 'ACCOUNT_NAME'
	     LEFT JOIN armc_attr AS aa_tracker ON aa_tracker.armc_id = a.armc_id AND aa_tracker.armc_attr_name = 'STUDY_COUNT'
	     LEFT JOIN armc_user AS au_ae ON au_ae.armc_id = a.armc_id AND au_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC." AND au_ae.status='A'
	     LEFT JOIN user AS ae ON ae.login = au_ae.login
	     LEFT JOIN armc_user AS au_am ON au_am.armc_id = a.armc_id AND au_am.role_id = ".ROLE_PRIMARY_ACCT_MGR." AND au_am.status='A'
	     LEFT JOIN user AS am ON am.login = au_am.login
	     LEFT JOIN armc_attr AS aa_amount ON aa_amount.armc_id = a.armc_id AND aa_amount.armc_attr_name='AMOUNT'
        GROUP BY a.armc_id
        WHERE (a.status='A') AND (aa_study_id.armc_attr_value='$study_id') AND (a.armc_type_id='$armc_type_id')
        ORDER BY armc_date DESC
        $limit";
      return $this->executeQuery($q);
	}

	/**
	* GetARMCGroupList()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Wed Jan 25 17:40:44 PST 2006
	*/
	function GetARMCGroupList($filter, $order="ORDER BY a.armc_status_id", $limit="")
	{
	   //$this->CreateTempAttrTable();
	   $q =
	   "SELECT
	        'false' AS grouped,
	        CONCAT(at.armc_type_prefix, '-', a.armc_id) AS armc_number,
	        a.armc_id,
	        a.created_by,
	        a.armc_date,
	        a.transaction_number,
	        a.transaction_date,
	        a.armc_status_id,
	        a_s.armc_status_description,
	        a.armc_type_id,
	        at.armc_type_prefix,
	        aa_study_id.armc_attr_value AS study_id,
	        aa_study_name.armc_attr_value AS study_name,
	        aa_account_id.armc_attr_value AS account_id,
	        aa_account_name.armc_attr_value AS account_name,
	        aa_tracker.armc_attr_value AS tracker,
	        ae.login AS account_executive_id,
	        ae.last_name AS account_executive,
	        am.login AS account_manager_id,
	        am.last_name AS account_manager,
	        CAST(aa_amount.armc_attr_value AS SIGNED) AS sort_amount,
                aa_amount.armc_attr_value AS amount
	    FROM armc AS a
	    LEFT JOIN armc_status AS a_s ON a_s.armc_status_id = a.armc_status_id
	    LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
	    LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id = a.armc_id AND aa_study_id.armc_attr_name = 'STUDY_ID'
	    LEFT JOIN armc_attr AS aa_study_name ON aa_study_name.armc_id = a.armc_id AND aa_study_name.armc_attr_name = 'STUDY_NAME'
	    LEFT JOIN armc_attr AS aa_account_id ON aa_account_id.armc_id = a.armc_id AND aa_account_id.armc_attr_name = 'ACCOUNT_ID'
	    LEFT JOIN armc_attr AS aa_account_name ON aa_account_name.armc_id = a.armc_id AND aa_account_name.armc_attr_name = 'ACCOUNT_NAME'
	    LEFT JOIN armc_attr AS aa_tracker ON aa_tracker.armc_id = a.armc_id AND aa_tracker.armc_attr_name = 'TRACKER'
	    LEFT JOIN armc_user AS au_ae ON au_ae.armc_id = a.armc_id AND au_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC." AND au_ae.status='A'
	    LEFT JOIN user AS ae ON ae.login = au_ae.login
	    LEFT JOIN armc_user AS au_am ON au_am.armc_id = a.armc_id AND au_am.role_id = ".ROLE_PRIMARY_ACCT_MGR." AND au_am.status='A'
	    LEFT JOIN user AS am ON am.login = au_am.login
	    LEFT JOIN armc_attr AS aa_amount ON aa_amount.armc_id = a.armc_id AND aa_amount.armc_attr_name = 'AMOUNT'
       WHERE (a.status='A') $filter
       GROUP BY a.armc_id
       $order
       $limit";

	   return $this->executeQuery($q);
	}

	/**
	* GetARMCDetails()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Mon Jan 30 10:47:31 PST 2006
	*/
	function GetARMCHeader($id)
	{
      $q =
      "SELECT
         a.armc_id,
         a.armc_date,
         a.armc_group_id,
         a.armc_status_id,
         a_s.armc_status_description,
         a.armc_type_id,
         at.armc_type_prefix,
         at.armc_type_description,
         a.product_id,
         p.product_description,
         a.created_by,
         a.created_date,
         CONCAT(u.first_name, ' ', u.last_name) AS created_by_name,
         aa_account_id.armc_attr_value AS account_id,
         aa_account_name.armc_attr_value AS account_name,
         aa_study_id.armc_attr_value AS study_id,
         aa_study_name.armc_attr_value AS study_name,
         au_ae.login AS account_executive_id,
         -- CONCAT(ae.first_name, ' ', ae.last_name) AS account_executive,
         -- au_am.login AS account_manager_id,
         -- CONCAT(am.first_name, ' ', am.last_name) AS account_manager,
         -- CONCAT(acct.first_name, ' ', acct.last_name) AS accountant,
         ac_billing.contact_id AS billing_contact_id,
         ac_billing.salutation AS billing_contact_salutation,
         ac_billing.first_name AS billing_contact_first_name,
         ac_billing.last_name AS billing_contact_last_name,
         CONCAT(ac_billing.salutation, ' ', ac_billing.first_name, ' ', ac_billing.last_name) AS billing_contact_name,
         ac_billing.address_1 AS billing_contact_address1,
         ac_billing.address_2 AS billing_contact_address2,
         ac_billing.city AS billing_contact_city,
         ac_billing.state AS billing_contact_state,
         ac_billing.zip AS billing_contact_zip,
         ac_billing.country_code AS billing_country_code,
         country_billing.country_description AS billing_contact_country,
         country_billing_attr.country_attr_value AS billing_country_iso2_code,
         ac_billing.phone AS billing_contact_phone,
         ac_billing.fax AS billing_contact_fax,
         ac_billing.email AS billing_contact_email,
         ac_project.contact_id AS project_contact_id,
         ac_project.salutation AS project_contact_salutation,
         ac_project.first_name AS project_contact_first_name,
         ac_project.last_name AS project_contact_last_name,
         CONCAT(ac_project.salutation, ' ', ac_project.first_name, ' ', ac_project.last_name) AS project_contact_name,
         ac_project.address_1 AS project_contact_address1,
         ac_project.address_2 AS project_contact_address2,
         ac_project.city AS project_contact_city,
         ac_project.state AS project_contact_state,
         ac_project.zip AS project_contact_zip,
         ac_project.country_code AS project_country_code,
         country_project.country_description AS project_contact_country,
         ac_project.phone AS project_contact_phone,
         ac_project.fax AS project_contact_fax,
         ac_project.email AS project_contact_email
       FROM armc AS a
       LEFT JOIN armc_status AS a_s ON a_s.armc_status_id = a.armc_status_id
       LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
       LEFT JOIN product AS p ON p.product_id=a.product_id
       LEFT JOIN user AS u ON u.login = a.created_by
       LEFT JOIN armc_attr AS aa_account_id ON aa_account_id.armc_id = a.armc_id AND aa_account_id.armc_attr_name = 'ACCOUNT_ID'
       LEFT JOIN armc_attr AS aa_account_name ON aa_account_name.armc_id = a.armc_id AND aa_account_name.armc_attr_name = 'ACCOUNT_NAME'
       LEFT JOIN armc_attr AS aa_study_id ON aa_study_id.armc_id = a.armc_id AND aa_study_id.armc_attr_name = 'STUDY_ID'
       LEFT JOIN armc_attr AS aa_study_name ON aa_study_name.armc_id = a.armc_id AND aa_study_name.armc_attr_name = 'STUDY_NAME'
	    LEFT JOIN armc_user AS au_ae ON au_ae.armc_id = a.armc_id AND au_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC." AND au_ae.status='A'
	    -- LEFT JOIN user AS ae ON ae.login = au_ae.login
	    -- LEFT JOIN armc_user AS au_am ON au_am.armc_id = a.armc_id AND au_am.role_id = ".ROLE_PRIMARY_ACCT_MGR." AND au_am.status='A'
	    -- LEFT JOIN user AS am ON am.login = au_am.login
	    -- LEFT JOIN armc_user AS au_acct ON au_acct.armc_id = a.armc_id AND au_acct.role_id = ".ROLE_ACCOUNTING." AND au_acct.status='A'
	    -- LEFT JOIN user AS acct ON acct.login = au_acct.login
	    LEFT JOIN armc_contact AS ac_billing ON ac_billing.armc_id = a.armc_id AND ac_billing.armc_contact_type_id = ".ARMC_CONTACT_TYPE_BILLING." AND ac_billing.status = 'A'
	    LEFT JOIN country AS country_billing ON country_billing.country_code = ac_billing.country_code
	    LEFT JOIN country_attr AS country_billing_attr ON country_billing_attr.country_id = country_billing.country_id AND country_billing_attr.country_attr_name = 'ISO_2_CODE'
	    LEFT JOIN armc_contact AS ac_project ON ac_project.armc_id = a.armc_id AND ac_project.armc_contact_type_id = ".ARMC_CONTACT_TYPE_PROJECT." AND ac_project.status = 'A'
	    LEFT JOIN country AS country_project ON country_project.country_code = ac_project.country_code
       WHERE a.armc_id = '$id'";

      return $this->executeQuery($q);

	}

	/**
	* GetARMCGroupHeader()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Mon Feb 06 10:58:28 PST 2006
	*/
	function GetARMCGroupHeader($id)
	{
	   $q =
	   "SELECT
	     a.armc_id,
	     a.armc_group_id,
	     ag.armc_group_date,
	     ag.armc_group_description,
	     a.armc_type_id AS armc_type_id,
	     CONCAT('M', at.armc_type_prefix) AS armc_type_prefix,
	     CONCAT('Merged ', at.armc_type_description) AS armc_type_description,
	     -- MIN(a.armc_status_id) AS armc_status_id,
	     a.product_id,
	     p.product_description,
	     aga_status_id.armc_group_attr_value AS armc_status_id,
	     a_s.armc_status_description,
	     ag.created_by,
	     ag.created_date,
	     CONCAT(u.first_name, ' ', u.last_name) AS created_by_name,
	     aga_account_id.armc_group_attr_value AS account_id,
	     aga_account_name.armc_group_attr_value AS account_name,
	     GROUP_CONCAT(DISTINCT au_ae.login SEPARATOR '') AS account_executive_id,
	     ac_billing.contact_id AS billing_contact_id,
        ac_billing.salutation AS billing_contact_salutation,
        ac_billing.first_name AS billing_contact_first_name,
        ac_billing.last_name AS billing_contact_last_name,
        CONCAT(ac_billing.salutation, ' ', ac_billing.first_name, ' ', ac_billing.last_name) AS billing_contact_name,
        ac_billing.address_1 AS billing_contact_address1,
        ac_billing.address_2 AS billing_contact_address2,
        ac_billing.city AS billing_contact_city,
        ac_billing.state AS billing_contact_state,
        ac_billing.zip AS billing_contact_zip,
        ac_billing.country_code AS billing_country_code,
        country_billing.country_description AS billing_contact_country,
        country_billing_attr.country_attr_value AS billing_country_iso2_code,
        ac_billing.phone AS billing_contact_phone,
        ac_billing.fax AS billing_contact_fax,
        ac_billing.email AS billing_contact_email
      FROM armc AS a
      LEFT JOIN armc_group AS ag ON ag.armc_group_id = a.armc_group_id
      LEFT JOIN armc_type AS at ON at.armc_type_id = a.armc_type_id
      LEFT JOIN product AS p ON p.product_id=a.product_id
      LEFT JOIN armc_group_attr AS aga_status_id ON aga_status_id.armc_group_id = a.armc_group_id AND aga_status_id.armc_group_attr_name = 'ARMC_STATUS_ID'
      LEFT JOIN armc_status AS a_s ON a_s.armc_status_id = aga_status_id.armc_group_attr_value
      LEFT JOIN user AS u ON u.login = ag.created_by
      LEFT JOIN armc_group_attr AS aga_account_id ON aga_account_id.armc_group_id = a.armc_group_id AND aga_account_id.armc_group_attr_name = 'ACCOUNT_ID'
      LEFT JOIN armc_group_attr AS aga_account_name ON aga_account_name.armc_group_id = a.armc_group_id AND aga_account_name.armc_group_attr_name = 'ACCOUNT_NAME'
      LEFT JOIN armc_user AS au_ae ON au_ae.armc_id = a.armc_id AND au_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC." AND au_ae.status='A'
      LEFT JOIN armc_contact AS ac_billing ON ac_billing.armc_id = a.armc_id AND ac_billing.armc_contact_type_id = ".ARMC_CONTACT_TYPE_BILLING." AND ac_billing.status = 'A'
      LEFT JOIN country AS country_billing ON country_billing.country_code = ac_billing.country_code
      LEFT JOIN country_attr AS country_billing_attr ON country_billing_attr.country_id = country_billing.country_id AND country_billing_attr.country_attr_name = 'ISO_2_CODE'
      WHERE a.armc_group_id = '$id'
      GROUP BY a.armc_group_id";

      return $this->executeQuery($q);
	}

	/**
	* GetARMCInvoice()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Fri Feb 03 15:10:49 PST 2006
	*/
	function GetARMCInvoice($armc_id)
	{
      $q =
      "SELECT
         a.transaction_number,
         a.transaction_date,
         aa_po_number.armc_attr_value AS po_number,
         aa_job_number.armc_attr_value AS job_number,
         aa_pm_name.armc_attr_value AS pm_name,
         aa_invoice_memo.armc_attr_value AS invoice_memo,
         aa_payment_type.armc_attr_value AS payment_type,
         aa_payment_number.armc_attr_value AS payment_number,
         aa_payment_date.armc_attr_value AS payment_date,
         aa_amount_paid.armc_attr_value AS amount_paid
       FROM armc AS a
       LEFT JOIN armc_attr AS aa_po_number ON aa_po_number.armc_id = a.armc_id AND aa_po_number.armc_attr_name = 'PONUMBER'
       LEFT JOIN armc_attr AS aa_job_number ON aa_job_number.armc_id = a.armc_id AND aa_job_number.armc_attr_name = 'JOBNUMBER'
       LEFT JOIN armc_attr AS aa_pm_name ON aa_pm_name.armc_id = a.armc_id AND aa_pm_name.armc_attr_name = 'PMNAME'
       LEFT JOIN armc_attr AS aa_invoice_memo ON aa_invoice_memo.armc_id = a.armc_id AND aa_invoice_memo.armc_attr_name = 'INVMEMO'
       LEFT JOIN armc_attr AS aa_payment_type ON aa_payment_type.armc_id=a.armc_id AND aa_payment_type.armc_attr_name='PAYMENT_TYPE'
       LEFT JOIN armc_attr AS aa_payment_number ON aa_payment_number.armc_id=a.armc_id AND aa_payment_number.armc_attr_name='PAYMENT_NUMBER'
       LEFT JOIN armc_attr AS aa_payment_date ON aa_payment_date.armc_id=a.armc_id AND aa_payment_date.armc_attr_name='PAYMENT_DATE'
       LEFT JOIN armc_attr AS aa_amount_paid ON aa_amount_paid.armc_id=a.armc_id AND aa_amount_paid.armc_attr_name='AMOUNT_PAID'
       WHERE a.armc_id = '$armc_id'";
       return $this->executeQuery($q);
	}

	/**
	* GetGroupARMCInvoice()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Fri Feb 03 15:10:49 PST 2006
	*/
	function GetARMCGroupInvoice($armc_group_id)
	{
      $q =
      "SELECT
         a.transaction_number,
         a.transaction_date,
         aga_po_number.armc_group_attr_value AS po_number,
         aga_job_number.armc_group_attr_value AS job_number,
         aga_pm_name.armc_group_attr_value AS pm_name,
         aga_invoice_memo.armc_group_attr_value AS invoice_memo,
         aga_payment_type.armc_group_attr_value AS payment_type,
         aga_payment_number.armc_group_attr_value AS payment_number,
         aga_payment_date.armc_group_attr_value AS payment_date,
         aga_amount_paid.armc_group_attr_value AS amount_paid
       FROM armc AS a
       LEFT JOIN armc_group_attr AS aga_po_number ON aga_po_number.armc_group_id = a.armc_group_id AND aga_po_number.armc_group_attr_name = 'PONUMBER'
       LEFT JOIN armc_group_attr AS aga_job_number ON aga_job_number.armc_group_id = a.armc_group_id AND aga_job_number.armc_group_attr_name = 'JOBNUMBER'
       LEFT JOIN armc_group_attr AS aga_pm_name ON aga_pm_name.armc_group_id = a.armc_group_id AND aga_pm_name.armc_group_attr_name = 'PMNAME'
       LEFT JOIN armc_group_attr AS aga_invoice_memo ON aga_invoice_memo.armc_group_id = a.armc_group_id AND aga_invoice_memo.armc_group_attr_name = 'INVMEMO'
       LEFT JOIN armc_group_attr AS aga_payment_type ON aga_payment_type.armc_group_id=a.armc_group_id AND aga_payment_type.armc_group_attr_name='PAYMENT_TYPE'
       LEFT JOIN armc_group_attr AS aga_payment_number ON aga_payment_number.armc_group_id=a.armc_group_id AND aga_payment_number.armc_group_attr_name='PAYMENT_NUMBER'
       LEFT JOIN armc_group_attr AS aga_payment_date ON aga_payment_date.armc_group_id=a.armc_group_id AND aga_payment_date.armc_group_attr_name='PAYMENT_DATE'
       LEFT JOIN armc_group_attr AS aga_amount_paid ON aga_amount_paid.armc_group_id=a.armc_group_id AND aga_amount_paid.armc_group_attr_name='AMOUNT_PAID'
       WHERE a.armc_group_id = '$armc_group_id'
       GROUP BY a.armc_group_id";
       return $this->executeQuery($q);
	}

	/**
	* GetARMCLines()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Mon Jan 30 15:58:47 PST 2006
	*/
	function GetARMCLines($armc_id)
	{
	   $q = "
	   SELECT
	      abli.armc_budget_line_item_id,
	      ablid.armc_budget_line_item_def_id,
	      ablid.armc_budget_line_item_description,
	      ablid.atm_gl_account_id,
	      aga.atm_gl_account_segment4,
	      ablid.item_number,
	      abli.group_description,
	      abli.proposed_rate,
	      abli.proposed_rate_i18n,
	      abli.proposed_quantity,
	      (abli.proposed_rate*abli.proposed_quantity) AS proposed_amount,
	      (abli.proposed_rate_i18n*abli.proposed_quantity) AS proposed_amount_i18n,
	      abli.actual_rate,
	      abli.actual_rate_i18n,
	      abli.actual_quantity,
	      (abli.actual_rate*abli.actual_quantity) AS actual_amount,
	      (abli.actual_rate_i18n*abli.actual_quantity) AS actual_amount_i18n
	   FROM armc_budget_line_item AS abli
	   LEFT JOIN armc_budget_line_item_def AS ablid ON ablid.armc_budget_line_item_def_id = abli.armc_budget_line_item_def_id
	   LEFT JOIN atm_gl_account AS aga ON aga.atm_gl_account_id = ablid.atm_gl_account_id
	   WHERE abli.armc_id = '$armc_id' AND abli.status='A'";

	   return $this->executeQuery($q);
	}

	/**
	* GetARMCUsers()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Mon Jan 30 14:08:08 PST 2006
	*/
	function GetARMCUsers($armc_id, $role_ids=array())
	{
      $q =
      "SELECT
         au.login,
         CONCAT(u.first_name, ' ', u.last_name) AS user_name,
         au.role_id,
         r.role_description
       FROM
         armc_user AS au
         LEFT JOIN user AS u ON u.login = au.login
         LEFT JOIN role AS r ON r.role_id = au.role_id
       WHERE (au.status='A') AND (au.armc_id = '$armc_id')";
      if (sizeof($role_ids)!=0) {
         $q .= " AND (au.role_id IN (".implode(", ", $role_ids)."))";
      }
      return $this->executeQuery($q);
	}

	/**
	* GetARMCContacts()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Mon Feb 13 15:50:29 PST 2006
	* Moved this function to ContactsManager.class
	*/

	/**
	* GetARMCLineDefinitions()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Mon Jan 30 16:23:35 PST 2006
	*/
	function GetARMCLineDefinitions($armc_type_id)
	{
      $q = "
      SELECT
         ablid.armc_budget_line_item_def_id,
         ablid.atm_gl_account_id,
         ablid.armc_budget_line_item_description,
         ablid.item_number,
         ablid.default_rate,
         ablid.default_quantity
      FROM armc_type_budget_line_item_def AS atblid
      LEFT JOIN armc_budget_line_item_def AS ablid ON ablid.armc_budget_line_item_def_id = atblid.armc_budget_line_item_def_id AND ablid.status='A'
      WHERE atblid.armc_type_id = '$armc_type_id' AND atblid.status='A'
      ORDER BY ablid.armc_budget_line_item_description";

      return $this->executeQuery($q);
	}

	/**
	* WriteARMCEvent()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Wed Feb 01 10:47:29 PST 2006
	*/
	function WriteARMCEvent($armc_id, $armc_event_type_id, $comment="")
	{
	   $q = "INSERT INTO `armc_event` (`armc_id`, `armc_event_type_id`, `remote_address`, `user_agent`, `comment`, `created_by`, `created_date`) VALUES ('$armc_id', '$armc_event_type_id', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', '".mysql_real_escape_string($comment)."', '".$this->created_by."', NOW())";
	   $this->executeQuery($q);
	   return true;
	}

	/**
	* WriteARMCComment()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Wed Feb 01 10:47:29 PST 2006
	*/
	function WriteARMCComment($armc_id, $armc_comment_type_id, $comment)
	{
	   $q = "INSERT INTO `armc_comment` (`armc_id`, `armc_comment_type_id`, `comment`, `created_by`, `created_date`) VALUES ('$armc_id', '$armc_comment_type_id', '".mysql_real_escape_string($comment)."', '".$this->created_by."', NOW())";
	   $this->executeQuery($q);
	   return true;
	}

	/**
	* WriteARMCLineComment()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Wed Feb 01 10:47:29 PST 2006
	*/
	function WriteARMCLineComment($armc_budget_line_item_id, $armc_comment_type_id, $comment)
	{
	   $q = "INSERT INTO `armc_budget_line_item_comment` (`armc_budget_line_item_id`, `armc_comment_type_id`, `comment`, `created_by`, `created_date`) VALUES ('$armc_budget_line_item_id', '$armc_comment_type_id', '".mysql_real_escape_string($comment)."', '".$this->created_by."', NOW())";
	   $this->executeQuery($q);
	   return true;
	}

	/**
	* WriteARMCAction()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Wed Feb 01 12:09:20 PST 2006
	*/
	function WriteARMCAction($armc_id, $armc_action_type_id)
	{
	   $q = "SELECT `armc_action_id` FROM `armc_action` WHERE `armc_id`='$armc_id' AND `armc_action_type_id`='$armc_action_type_id'";
	   $this->executeQuery($q);
	   if ($this->rows==0){
	      $q = "INSERT INTO `armc_action` (`armc_id`, `armc_action_type_id`, `created_by`, `created_date`, `status`) "
	      ."VALUES ('$armc_id', '$armc_action_type_id', '".$this->created_by."', NOW(), 'A')";
	   }else{
         $q = "UPDATE `armc_action` SET `modified_by` = '".$this->created_by."', `modified_date`=NOW() "
         ." WHERE `armc_id`='$armc_id' AND `armc_action_type_id`='$armc_action_type_id'";
	   }
      $this->executeQuery($q);
      return true;
	}

   /**
	* WriteARMCLineAction()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Wed Feb 01 12:09:20 PST 2006
	*/
	function WriteARMCLineAction($armc_budget_line_item_id, $armc_action_type_id)
	{
	   $q = "SELECT `armc_budget_line_item_action_id` FROM `armc_budget_line_item_action` WHERE `armc_budget_line_item_id`='$armc_budget_line_item_id' AND `armc_action_type_id`='$armc_action_type_id'";
	   $this->executeQuery($q);
	   if ($this->rows==0){
	      $q = "INSERT INTO `armc_budget_line_item_action` (`armc_budget_line_item_id`, `armc_action_type_id`, `created_by`, `created_date`, `status`) "
	      ."VALUES ('$armc_budget_line_item_id', '$armc_action_type_id', '".$this->created_by."', NOW(), 'A')";
	   }else{
         $q = "UPDATE `armc_budget_line_item_action` SET `modified_by` = '".$this->created_by."', `modified_date`=NOW() "
         ." WHERE `armc_budget_line_item_id`='$armc_budget_line_item_id' AND `armc_action_type_id`='$armc_action_type_id'";
	   }
      $this->executeQuery($q);
      return true;
	}


	/**
	* DeleteARMCAction()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Wed Feb 01 14:57:51 PST 2006
	*/
	function DeleteARMCAction($armc_id, $armc_action_type_id)
	{
      $q = "DELETE FROM `armc_action` WHERE armc_id = $armc_id AND armc_action_type_id = $armc_action_type_id";
      $this->executeQuery($q);
      return true;
	}

	/**
	* DeleteARMCLineAction()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Wed Feb 01 14:57:51 PST 2006
	*/
	function DeleteARMCLineAction($armc_budget_line_item_id, $armc_action_type_id)
	{
      $q = "DELETE FROM `armc_budget_line_item_action` WHERE armc_budget_line_item_id = $armc_budget_line_item_id AND armc_action_type_id = $armc_action_type_id";
      $this->executeQuery($q);
      return true;
	}

	/**
	* DeleteARMC()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Thu Feb 09 14:20:21 PST 2006
	*/
	function DeleteARMC($armc_id)
	{
	   $q = "UPDATE `armc_attr` SET `modified_by`='".$this->created_by."', `modified_date`=NOW(), `status` = 'D' WHERE `armc_id`='$armc_id'";
	   $this->executeQuery($q);
	   $q = "UPDATE `armc` SET `modified_by`='".$this->created_by."', `modified_date`=NOW(), `status`='D' WHERE `armc_id`='$armc_id'";
	   $this->executeQuery($q);
	   return $this->rows;
	}

	/**
	* DeleteARMCLine()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Fri Feb 10 14:44:31 PST 2006
	*/
	function DeleteARMCLine($armc_id, $armc_budget_line_item_id)
	{
      $q = "UPDATE `armc_budget_line_item` SET `modified_by`='".$this->created_by."', `modified_date`=NOW(), `status`='D' WHERE `armc_id`='$armc_id' AND `armc_budget_line_item_id`='$armc_budget_line_item_id'";
      $this->executeQuery($q);
      return $this->rows;
	}

	/**
	* ARMCIsApproved()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Wed Feb 01 13:09:42 PST 2006
	*/
	function ARMCIsApproved($armc_id, $armc_action_type_id)
	{
      $q = "SELECT armc_action_id FROM armc_action WHERE armc_id = $armc_id AND armc_action_type_id = $armc_action_type_id";
      $this->executeQuery($q);
      return ($this->rows);
	}

	/**
	* ARMCLineIsApproved()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Wed Feb 01 13:09:42 PST 2006
	*/
	function ARMCLineIsApproved($armc_budget_line_item_id, $armc_action_type_id)
	{
      $q = "SELECT armc_budget_line_item_action_id FROM armc_budget_line_item_action WHERE armc_budget_line_item_id = $armc_budget_line_item_id AND armc_action_type_id = $armc_action_type_id";
      $this->executeQuery($q);
      return ($this->rows);
	}


	/**
	* ARMCRequiresAEApproval()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Wed Feb 01 13:19:07 PST 2006
	*/
	function ARMCRequiresAEApproval($armc_id)
	{
	   $q = "SELECT armc_attr_value FROM armc_attr WHERE armc_id = $armc_id AND armc_attr_name = 'AMRC_AE_APPROVAL_REQUIRED'";
	   $rst = $this->executeQuery($q);
	   $value = mysql_fetch_assoc($rst);
	   if ($value)
	     return $value['armc_attr_value'];
	   else
	     return $value;
	}

	/**
	* ARMCRequiresACCTApproval()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Wed Feb 01 13:19:07 PST 2006
	*/
	function ARMCRequiresACCTApproval($armc_id)
	{

	   $q = "SELECT armc_attr_value FROM armc_attr WHERE armc_id = $armc_id AND armc_attr_name = 'AMRC_ACCT_APPROVAL_REQUIRED'";
	   $rst = $this->executeQuery($q);
	   $attr = mysql_fetch_assoc($rst);
	   $attr = $attr["armc_attr_value"];
	   
	   $q = "SELECT armc_type_id FROM armc WHERE armc_id = $armc_id";
	   $rst = $this->executeQuery($q);
	   $type = mysql_fetch_assoc($rst);
	   $type = $type["armc_type_id"];
	   
		$type_value = $this->GetARMCSetting('ARMC_TYPES_REQUIRE_ACCT_APPROVAL');
		   
		$type_value = explode(",",$type_value['armc_setting_value']);
		
	   return ($attr == "1" || in_array($type, $type_value));
	}

	/**
	* SetARMCStatus()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Wed Feb 01 13:25:44 PST 2006
	*/
	function SetARMCStatus($armc_id, $armc_status_id)
	{
	   $q = "UPDATE `armc` SET `armc_status_id`=$armc_status_id, `modified_by`='".$this->created_by."', `modified_date`=NOW() WHERE armc_id = $armc_id";
	   $this->executeQuery($q);
	   $q = "SELECT armc_group_id FROM armc WHERE armc_id = '$armc_id'";
	   $group_id = mysql_fetch_assoc($this->executeQuery($q));
	   if ($group_id && $group_id["armc_group_id"]) {
	      if ($this->GetARMCGroupAttr($group_id["armc_group_id"], "ARMC_STATUS_ID")!==false)
	        $this->UpdateARMCGroupAttr($group_id["armc_group_id"], "ARMC_STATUS_ID", $this->GetARMCGroupStatusID($group_id["armc_group_id"]));
	      else
	        $this->InsertARMCGroupAttr($group_id["armc_group_id"], "ARMC_STATUS_ID", $this->GetARMCGroupStatusID($group_id["armc_group_id"]));
	   }
	   return true;
	}

	/**
	* UpdateARMCLine()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Thu Feb 02 12:09:32 PST 2006
	*/
	function UpdateARMCLine($info)
	{
	   $q = "UPDATE `armc_budget_line_item` SET "
	   ." `armc_budget_line_item_def_id` = '".$info["armc_budget_line_item_def_id"]."',"
	   ." `group_description` = '".mysql_real_escape_string($info["group_description"])."',"
	   ." `proposed_quantity` = '".$info["proposed_quantity"]."',"
	   ." `proposed_rate` = '".$info["proposed_rate"]."',"
	   ." `proposed_rate_i18n` = '".$info["proposed_rate_i18n"]."',"
	   ." `actual_quantity` = '".$info["actual_quantity"]."',"
	   ." `actual_rate` = '".$info["actual_rate"]."',"
	   ." `actual_rate_i18n` = '".$info["actual_rate_i18n"]."',"
	   ." `modified_by` = '".$_SESSION["admin_id"]."',"
	   ." `modified_date` = NOW()"
	   ."WHERE `armc_id`='".$info["armc_id"]."' AND `armc_budget_line_item_id` = '".$info["armc_budget_line_item_id"]."'";

	   $this->executeQuery($q);
	   return true;
	}

	/**
	* InsertARMCLine()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Thu Feb 02 17:02:09 PST 2006
	*/
	function InsertARMCLine($info)
	{
	   $q = "INSERT INTO `armc_budget_line_item` ("
	   ." `armc_id`, "
	   ." `armc_budget_line_item_def_id`, "
	   ." `group_description`, "
	   ." `proposed_rate`, "
	   ." `proposed_rate_i18n`, "
	   ." `proposed_quantity`, "
	   ." `actual_rate`, "
	   ." `actual_rate_i18n`, "
	   ." `actual_quantity`, "
	   ." `created_by`, "
	   ." `created_date`) VALUES ("
	   ." '".$info["armc_id"]."', "
	   ." '".$info["armc_budget_line_item_def_id"]."', "
	   ." '".mysql_real_escape_string($info["group_description"])."', "
	   ." '".$info["proposed_rate"]."', "
	   ." '".$info["proposed_rate_i18n"]."', "
	   ." '".$info["proposed_quantity"]."', "
	   ." '".$info["actual_rate"]."', "
	   ." '".$info["actual_rate_i18n"]."', "
	   ." '".$info["actual_quantity"]."', "
	   ." '".$this->created_by."', "
	   ." NOW())";

	   $this->executeQuery($q);
	   return $this->last_id;
	}
/**
	* GetARMCContacts()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Mon Feb 13 15:50:29 PST 2006
	* Moved this function to ContactsManager.class
	*/
/*	function GetARMCContacts($armc_id, $type_ids = array())
	{
      $q =
      "SELECT
         ac.armc_contact_type_id,
         ac.contact_id,
         ac.salutation,
         ac.first_name,
         ac.last_name,
         ac.address_1,
         ac.address_2,
         ac.city,
         ac.state,
         ac.zip,
         ac.country_code,
         c.country_description,
         ca.country_attr_value AS iso2_country_code,
         ac.phone,
         ac.fax,
         ac.email,
         act.armc_contact_type_description
      FROM armc_contact AS ac
      LEFT JOIN armc_contact_type AS act ON act.armc_contact_type_id = ac.armc_contact_type_id AND act.status = 'A'
      LEFT JOIN country AS c ON c.country_code = ac.country_code
      LEFT JOIN country_attr AS ca ON ca.country_id = c.country_id AND ca.country_attr_name = 'ISO_2_CODE'
      WHERE ac.armc_id = '$armc_id'";
      if (sizeof($type_ids)!=0)
         $q .= " AND ac.armc_contact_type_id IN (".implode(", ", $type_ids).")";

      return $this->executeQuery($q);

	}

	*/
	/**
	* SetARMCContact()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Fri Feb 03 13:07:47 PST 2006
	* Moved to ContactsManager.class
	*/
/*	function SetARMCContact($armc_id, $info)
	{
	   $q = "SELECT `armc_contact_id` FROM `armc_contact` WHERE `armc_id` = '$armc_id' AND `armc_contact_type_id`='".$info["armc_contact_type_id"]."'";
	   $this->executeQuery($q);
	   if ($this->rows==0) {
	      $q = "INSERT INTO `armc_contact`"
	           ." (`armc_id`, `armc_contact_type_id`, `contact_id`, `salutation`, `first_name`, `last_name`, `address_1`, `address_2`, "
	           ." `city`, `state`, `zip`, `country_code`, `phone`, `fax`, `email`, `created_by`, `created_date`, `status`) "
	           ." VALUES"
	           ." ('$armc_id', '".$info["armc_contact_type_id"]."', '".$info["contact_id"]."', '".$info["salutation"]."', '"
	           .mysql_real_escape_string($info["first_name"])."', '".mysql_real_escape_string($info["last_name"])."', '".mysql_real_escape_string($info["address_1"])."', '".mysql_real_escape_string($info["address_2"])."', '"
	           .mysql_real_escape_string($info["city"])."', '".mysql_real_escape_string($info["state"])."', '".mysql_real_escape_string($info["zip"])."', '".mysql_real_escape_string($info["country_code"])."', '".mysql_real_escape_string($info["phone"])."', '"
	           .mysql_real_escape_string($info["fax"])."', '".mysql_real_escape_string($info["email"])."', '".$this->created_by."', NOW(), 'A')";
	   }else{
         $q = "UPDATE `armc_contact` SET "
         ."`contact_id`='".$info["contact_id"]."', "
         ."`salutation`='".$info["salutation"]."', "
         ."`first_name`='".mysql_real_escape_string($info["first_name"])."', "
         ."`last_name`='".mysql_real_escape_string($info["last_name"])."', "
         ."`address_1`='".mysql_real_escape_string($info["address_1"])."', "
         ."`address_2`='".mysql_real_escape_string($info["address_2"])."', "
         ."`city`='".mysql_real_escape_string($info["city"])."', "
         ."`state`='".mysql_real_escape_string($info["state"])."', "
         ."`zip`='".mysql_real_escape_string($info["zip"])."', "
         ."`country_code`='".mysql_real_escape_string($info["country_code"])."', "
         ."`phone`='".mysql_real_escape_string($info["phone"])."', "
         ."`fax`='".mysql_real_escape_string($info["fax"])."', "
         ."`email`='".mysql_real_escape_string($info["email"])."', "
         ."`modified_by`='".$this->created_by."', "
         ."`modified_date`=NOW() "
         ."WHERE `armc_id`='$armc_id' AND `armc_contact_type_id`='".$info["armc_contact_type_id"]."'";
	   }
      $this->executeQuery($q);
   }
*/

   /**
   * isARMCContact()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Jul 10 13:35:06 PDT 2006
   * Moved to ContactaManager.class
   */

   /*function isARMCContact($armc_id, $armc_contact_type_id)
   {
	   $q = "SELECT `armc_contact_id` FROM `armc_contact` WHERE `armc_id` = '$armc_id' AND `armc_contact_type_id`='".$armc_contact_type_id."'";
	   $ret = mysql_fetch_assoc($this->executeQuery($q));
	   return ($ret?$ret["armc_contact_id"]:false);
   } */

   /**
   * InsertARMCContact()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Jul 10 13:36:56 PDT 2006
   * Moved to ContactsManager.class
   */
  /* function InsertARMCContact($armc_id, $info)
   {
	   $q = "INSERT INTO `armc_contact`"
	           ." (`armc_id`, `armc_contact_type_id`, `contact_id`, `salutation`, `first_name`, `last_name`, `address_1`, `address_2`, "
	           ." `city`, `state`, `zip`, `country_code`, `phone`, `fax`, `email`, `created_by`, `created_date`, `status`) "
	           ." VALUES"
	           ." ('$armc_id', '".$info["armc_contact_type_id"]."', '".$info["contact_id"]."', '".$info["salutation"]."', '"
	           .mysql_real_escape_string($info["first_name"])."', '".mysql_real_escape_string($info["last_name"])."', '".mysql_real_escape_string($info["address_1"])."', '".mysql_real_escape_string($info["address_2"])."', '"
	           .mysql_real_escape_string($info["city"])."', '".mysql_real_escape_string($info["state"])."', '".mysql_real_escape_string($info["zip"])."', '".mysql_real_escape_string($info["country_code"])."', '".mysql_real_escape_string($info["phone"])."', '"
	           .mysql_real_escape_string($info["fax"])."', '".mysql_real_escape_string($info["email"])."', '".$this->created_by."', NOW(), 'A')";

	    $this->executeQuery($q);
	    return $this->last_id;
   }
   */
   /**
   * UpdateARMCContact()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Jul 10 13:37:44 PDT 2006
   * Moved to ContactsManager.class
   */
   /*function UpdateARMCContact($armc_contact_id, $info)
   {
         $q = "UPDATE `armc_contact` SET "
         ."`contact_id`='".$info["contact_id"]."', "
         ."`salutation`='".$info["salutation"]."', "
         ."`first_name`='".mysql_real_escape_string($info["first_name"])."', "
         ."`last_name`='".mysql_real_escape_string($info["last_name"])."', "
         ."`address_1`='".mysql_real_escape_string($info["address_1"])."', "
         ."`address_2`='".mysql_real_escape_string($info["address_2"])."', "
         ."`city`='".mysql_real_escape_string($info["city"])."', "
         ."`state`='".mysql_real_escape_string($info["state"])."', "
         ."`zip`='".mysql_real_escape_string($info["zip"])."', "
         ."`country_code`='".mysql_real_escape_string($info["country_code"])."', "
         ."`phone`='".mysql_real_escape_string($info["phone"])."', "
         ."`fax`='".mysql_real_escape_string($info["fax"])."', "
         ."`email`='".mysql_real_escape_string($info["email"])."', "
         ."`modified_by`='".$this->created_by."', "
         ."`modified_date`=NOW() "
         ."WHERE `armc_contact_id`='$armc_contact_id'";
      $this->executeQuery($q);
      return $this->rows;
   }
   */
   /**
   * SetARMCUser()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Thu Feb 09 12:37:49 PST 2006
   */
   function SetARMCUser($armc_id, $role_id, $user_id)
   {
      $q = "SELECT `armc_user_id` FROM `armc_user` WHERE `armc_id`='$armc_id' AND `role_id`='$role_id' AND `login`='$user_id'";
      $this->executeQuery($q);
      if ($this->rows==0) {
         $q = "INSERT INTO `armc_user`"
         ." (`armc_id`, `role_id`, `login`, `created_by`, `created_date`, `status`) VALUES "
         ." ('$armc_id', '$role_id', '$user_id', '".$this->created_by."', NOW(), 'A')";
      }else{
         $q = "UPDATE `armc_user` SET "
         ."`login` = '$user_id', "
         ."`modified_by` = '".$this->created_by."', "
         ."`modified_date` = NOW() "
         ."WHERE `armc_id`='$armc_id' AND `role_id`='$role_id' AND `login`='$user_id'";
      }
      $this->executeQuery($q);
   }

   /**
   * isARMCUser()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Jul 10 14:16:36 PDT 2006
   */
   function isARMCUser($armc_id, $role_id, $user_id)
   {
      $q = "SELECT `armc_user_id` FROM `armc_user` WHERE `armc_id`='$armc_id' AND `role_id`='$role_id' AND `login`='$user_id'";
      $ret = mysql_fetch_assoc($this->executeQuery($q));
      return ($ret?$ret["armc_user_id"]:false);
   }

   /**
   * InsertARMCUser()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Jul 10 14:18:34 PDT 2006
   */
   function InsertARMCUser($armc_id, $role_id, $user_id)
   {
      $q = "INSERT INTO `armc_user`"
         ." (`armc_id`, `role_id`, `login`, `created_by`, `created_date`, `status`) VALUES "
         ." ('$armc_id', '$role_id', '$user_id', '".$this->created_by."', NOW(), 'A')";
      $this->executeQuery($q);
      return $this->last_id;
   }

   /**
   * UpdateARMCUser()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Jul 10 14:17:27 PDT 2006
   */
   function UpdateARMCUser($armc_user_id, $armc_id, $role_id, $user_id)
   {
      $q = "UPDATE `armc_user` SET "
         ."`armc_id`='$armc_id', "
         ."`role_id`='$role_id', "
         ."`login` = '$user_id', "
         ."`modified_by` = '".$this->created_by."', "
         ."`modified_date` = NOW(), "
         ."`status`='A' "
         ."WHERE `armc_user_id`='$armc_user_id'";
      $this->executeQuery($q);
      return $this->rows;
   }

   /**
   * DeleteARMCUsers()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Sun Mar 26 13:18:54 PST 2006
   */
   function DeleteARMCUsers($armc_id)
   {
      $q = "UPDATE `armc_user` SET modified_by='".$this->created_by."', modified_date=NOW(), status='D' WHERE `armc_id`='$armc_id'";
      $this->executeQuery($q);
      return $this->rows;
   }

   /**
   * GetARMCEvents()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Fri Feb 03 13:49:34 PST 2006
   */
   function GetARMCEvents($armc_id)
   {
      $q =
         "SELECT "
         ."CONCAT(u.first_name, ' ', u.last_name) AS user, "
         ."ae.created_date AS date, "
         ."ae.remote_address, "
         ."ae.user_agent, "
         ."aet.armc_event_type_description, "
         ."ae.comment "
         ."FROM armc_event AS ae "
         ."LEFT JOIN armc_event_type AS aet ON aet.armc_event_type_id = ae.armc_event_type_id "
         ."LEFT JOIN user AS u ON u.login = ae.created_by "
         ."WHERE ae.armc_id = '$armc_id' AND ae.status='A'"
         ."ORDER BY ae.created_date";
      return $this->executeQuery($q);
   }

   /**
   * GetARMCLastEvent()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Thu Apr 20 14:55:54 PDT 2006
   */
   function GetARMCLastEvent($armc_id, $event_type_id)
   {
      $q =
         "SELECT "
         ."CONCAT(u.first_name, ' ', u.last_name) AS user, "
         ."ae.created_date AS date, "
         ."ae.remote_address, "
         ."ae.user_agent, "
         ."aet.armc_event_type_description, "
         ."ae.comment "
         ."FROM armc_event AS ae "
         ."LEFT JOIN armc_event_type AS aet ON aet.armc_event_type_id = ae.armc_event_type_id "
         ."LEFT JOIN user AS u ON u.login = ae.created_by "
         ."WHERE ae.armc_id = '$armc_id' AND ae.armc_event_type_id = '$event_type_id' AND ae.status='A'"
         ."ORDER BY ae.created_date DESC";

      return mysql_fetch_assoc($this->executeQuery($q));
   }

   /**
   * GetARMCComments()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Fri Feb 17 13:16:57 PST 2006
   */
   function GetARMCComments($armc_id)
   {
      $q =
         "SELECT "
         ."CONCAT(u.first_name, ' ', u.last_name) AS user, "
         ."ac.created_date AS date, "
         ."act.armc_comment_type_description, "
         ."ac.comment "
         ."FROM armc_comment AS ac "
         ."LEFT JOIN user AS u ON u.login = ac.created_by "
         ."LEFT JOIN armc_comment_type AS act ON act.armc_comment_type_id = ac.armc_comment_type_id "
         ."WHERE ac.armc_id = '$armc_id' AND ac.status='A' "
         ."ORDER BY ac.created_date";

      return $this->executeQuery($q);
   }

   /**
   * InsertARMCComment()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Fri Feb 17 14:36:59 PST 2006
   */
   function InsertARMCComment($armc_id, $armc_comment_type_id, $comment)
   {
      $q = "INSERT INTO `armc_comment` (`armc_id`, `armc_comment_type_id`, `comment`, `created_by`, `created_date`, `status`) "
      ." VALUES ('$armc_id', '$armc_comment_type_id', '".mysql_real_escape_string($comment)."', '".$this->created_by."', NOW(), 'A')";
      $this->executeQuery($q);
      return $this->rows;
   }

   /**
   * GetARMCLineComments()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Fri Feb 17 13:47:37 PST 2006
   */
   function GetARMCLineComments($armc_budget_line_item_id)
   {
      $q =
         "SELECT "
         ."CONCAT(u.first_name, ' ', u.last_name) AS user, "
         ."ablic.created_date AS date, "
         ."ablid.armc_budget_line_item_description, "
         ."abli.group_description, "
         ."act.armc_comment_type_description, "
         ."ablic.comment "
         ."FROM armc_budget_line_item_comment AS ablic "
         ."LEFT JOIN user AS u ON u.login = ablic.created_by "
         ."LEFT JOIN armc_comment_type AS act ON act.armc_comment_type_id = ablic.armc_comment_type_id "
         ."LEFT JOIN armc_budget_line_item AS abli ON abli.armc_budget_line_item_id = ablic.armc_budget_line_item_id "
         ."LEFT JOIN armc_budget_line_item_def AS ablid ON ablid.armc_budget_line_item_def_id = abli.armc_budget_line_item_def_id "
         ."WHERE ablic.armc_budget_line_item_id = '$armc_budget_line_item_id' AND ablic.status='A' "
         ."ORDER BY ablic.created_date";
      return $this->executeQuery($q);
   }

   /**
   * InsertARMCLineComment()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Fri Feb 17 14:38:44 PST 2006
   */
   function InsertARMCLineComment($armc_budget_line_item_id, $armc_comment_type_id, $comment)
   {
      $q = "INSERT INTO `armc_budget_line_item_comment` (`armc_budget_line_item_id`, `armc_comment_type_id`, `comment`, `created_by`, `created_date`, `status`) "
      ." VALUES ('$armc_budget_line_item_id', '$armc_comment_type_id', '".mysql_real_escape_string($comment)."', '".$this->created_by."', NOW(), 'A')";
      $this->executeQuery($q);
      return $this->rows;

   }

   /**
   * SetARMCAttr()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Fri Feb 03 15:57:56 PST 2006
   */
   function SetARMCAttr($armc_id, $armc_attr_name, $armc_attr_value)
   {
      $q = "SELECT `armc_attr_id` FROM `armc_attr` WHERE `armc_id`='$armc_id' AND `armc_attr_name`='$armc_attr_name'";
      $this->executeQuery($q);
      if ($this->rows==0) {
         $q = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`)"
         ." VALUES ('$armc_id', '$armc_attr_name', '".mysql_real_escape_string($armc_attr_value)."', '".$this->created_by."', NOW(), 'A')";
      }else{
         $q = "UPDATE `armc_attr` SET "
         ."`armc_attr_value` = '".mysql_real_escape_string($armc_attr_value)."', "
         ."`modified_by` = '".$this->created_by."', "
         ."`modified_date` = NOW() "
         ."WHERE `armc_id` = '$armc_id' AND `armc_attr_name` = '$armc_attr_name'";
      }
      $this->executeQuery($q);
      return true;
   }

   /**
   * InsertARMCAttr()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Jul 10 13:20:14 PDT 2006
   */
   function InsertARMCAttr($armc_id, $armc_attr_name, $armc_attr_value)
   {
      $q = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`)"
         ." VALUES ('$armc_id', '$armc_attr_name', '".mysql_real_escape_string($armc_attr_value)."', '".$this->created_by."', NOW(), 'A')";
      $this->executeQuery($q);
      return $this->last_id;
   }



   /**
   * UpdateARMCAttr()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Jul 10 13:16:36 PDT 2006
   */
   function UpdateARMCAttr($armc_id, $armc_attr_name, $armc_attr_value)
   {
      $q = "UPDATE `armc_attr` SET "
         ."`armc_attr_value` = '".mysql_real_escape_string($armc_attr_value)."', "
         ."`modified_by` = '".$this->created_by."', "
         ."`modified_date` = NOW() "
         ."WHERE `armc_id` = '$armc_id' AND `armc_attr_name` = '$armc_attr_name'";

      $this->executeQuery($q);
      return $this->rows;
   }

   /**
   * GetARMCAttr()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Thu Feb 09 16:13:04 PST 2006
   */
   function GetARMCAttr($armc_id, $armc_attr_name)
   {
      $q = "SELECT `armc_attr_value` FROM `armc_attr` WHERE `armc_id`='$armc_id' AND `armc_attr_name`='$armc_attr_name'";
      $rst = $this->executeQuery($q);
      if ($this->rows==0)
         return false;
      else
         return mysql_result($rst, 0, 0);
   }

   /**
   * GetARMCAttrs()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Feb 13 15:30:46 PST 2006
   */
   function GetARMCAttrs($armc_id)
   {
      $q = "SELECT armc_attr_name, armc_attr_value FROM armc_attr WHERE armc_id = '$armc_id' AND status='A'";
      return $this->executeQuery($q);
   }

   /**
   * SetARMCGroupAttr()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Fri Feb 03 15:57:56 PST 2006
   */
   function SetARMCGroupAttr($armc_group_id, $armc_group_attr_name, $armc_group_attr_value)
   {
      $q = "SELECT `armc_group_attr_id` FROM `armc_group_attr` WHERE `armc_group_id`='$armc_group_id' AND `armc_group_attr_name`='$armc_group_attr_name'";
      $this->executeQuery($q);
      if ($this->rows==0) {
         $q = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`)"
         ." VALUES ('$armc_group_id', '$armc_group_attr_name', '".mysql_real_escape_string($armc_group_attr_value)."', '".$this->created_by."', NOW(), 'A')";
      }else{
         $q = "UPDATE `armc_group_attr` SET "
         ."`armc_group_attr_value` = '".mysql_real_escape_string($armc_group_attr_value)."', "
         ."`modified_by` = '".$this->created_by."', "
         ."`modified_date` = NOW() "
         ."WHERE `armc_group_id` = '$armc_group_id' AND `armc_group_attr_name` = '$armc_group_attr_name'";
      }
      $this->executeQuery($q);
      return true;
   }

   /**
   * GetARMCGroupAttr()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Fri Feb 03 15:57:56 PST 2006
   */
	function GetARMCGroupAttr($armc_group_id, $armc_group_attr_name) {
		$q = "SELECT `armc_group_attr_value` FROM `armc_group_attr` WHERE `armc_group_id`='$armc_group_id' AND `armc_group_attr_name`='$armc_group_attr_name' AND `status`='A'";
      	$rst = $this->executeQuery($q);
	    if ($this->rows==0)
    		return false;
		else
        	return mysql_result($rst, 0, 0);
	}

	/**
	* InsertARMCGroupAttr()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Mon Jul 10 13:43:33 PDT 2006
	*/
	function InsertARMCGroupAttr($armc_group_id, $armc_group_attr_name, $armc_group_attr_value)
	{
      $q = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`)"
         ." VALUES ('$armc_group_id', '$armc_group_attr_name', '".mysql_real_escape_string($armc_group_attr_value)."', '".$this->created_by."', NOW(), 'A')";
      $this->executeQuery($q);
      return $this->last_id;
	}

	/**
	* UpdateARMCGroupAttr()
	*
	* @@param
	* @@param -
	* @@return
	* @@throws
	* @@access
	* @@global
	* @@since  - Mon Jul 10 13:42:35 PDT 2006
	*/
	function UpdateARMCGroupAttr($armc_group_id, $armc_group_attr_name, $armc_group_attr_value)
	{
      $q = "UPDATE `armc_group_attr` SET "
         ."`armc_group_attr_value` = '".mysql_real_escape_string($armc_group_attr_value)."', "
         ."`modified_by` = '".$this->created_by."', "
         ."`modified_date` = NOW() "
         ."WHERE `armc_group_id` = '$armc_group_id' AND `armc_group_attr_name` = '$armc_group_attr_name'";
      $this->executeQuery($q);
      return $this->rows;
	}

   /**
   * GetARMCGroupAttrs()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Feb 13 15:30:46 PST 2006
   */
   function GetARMCGroupAttrs($armc_group_id)
   {
      $q = "SELECT armc_group_attr_name, armc_group_attr_value FROM armc_group_attr WHERE armc_group_id = '$armc_group_id' AND status='A'";
      return $this->executeQuery($q);
   }

   /**
   * SetARMCGroupDescription()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Thu Feb 09 11:37:54 PST 2006
   */
   function SetARMCGroupDescription($armc_group_id, $armc_group_description)
   {
      $q = "UPDATE `armc_group` SET `armc_group_description` = '".mysql_real_escape_string($armc_group_description)."', `modified_by`='".$this->created_by."', `modified_date`=NOW() WHERE `armc_group_id` = '$armc_group_id'";
      $this->executeQuery($q);
      return $this->rows;
   }

   /**
   * SetARMCGroupCreatedDate()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Mar 27 09:33:26 PST 2006
   */
   function SetARMCGroupDate($armc_group_id, $date)
   {
      if (substr($date, 0, 1)=="'") $date = substr($date, 1);
      if (substr($date, strlen($date)-1)=="'") $date = substr($date, 0, strlen($date)-1);
      $q = "UPDATE `armc_group` SET `armc_group_date`='$date', `modified_by`='".$this->created_by."', `modified_date`=NOW() WHERE `armc_group_id`='$armc_group_id'";
      $this->executeQuery($q);
      return true;
   }

   /**
   * GetARMCGroupDate()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Wed Apr 05 15:39:10 PDT 2006
   */
   function GetARMCGroupDate($armc_group_id)
   {
      $q = "SELECT MIN(armc_date) AS armc_group_date FROM armc WHERE armc_group_id='$armc_group_id' AND status='A' GROUP BY armc_group_id";
      $rst = $this->executeQuery($q);
	   return ($this->rows)?mysql_result($rst, 0, "armc_group_date"):"NULL";
   }

   /**
   * SetARMCTransactionDate()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Fri Feb 03 16:06:34 PST 2006
   */
   function SetARMCTransactionDate($armc_id, $date)
   {
      $q = "UPDATE `armc` SET `transaction_date` = $date, `modified_by`='".$this->created_by."', `modified_date`=NOW() WHERE `armc_id`='$armc_id'";
      $this->executeQuery($q);
      return true;
   }

   /**
   * SetARMCCreatedDate()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Mar 27 09:26:30 PST 2006
   */
   function SetARMCDate($armc_id, $date)
   {
      if (substr($date, 0, 1)=="'") $date = substr($date, 1);
      if (substr($date, strlen($date)-1)=="'") $date = substr($date, 0, strlen($date)-1);
      $q = "UPDATE `armc` SET `armc_date`='$date', `modified_by`='".$this->created_by."', `modified_date`=NOW() WHERE armc_id='$armc_id'";
      $this->executeQuery($q);
      return true;
   }

   /**
   * SetARMCTransactionNumber()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Feb 13 11:58:39 PST 2006
   */
   function SetARMCTransactionNumber($armc_id, $transaction_number)
   {
      $q = "UPDATE `armc` SET `transaction_number` = '$transaction_number', `modified_by`='".$this->created_by."', `modified_date`=NOW() WHERE `armc_id`='$armc_id'";
      $this->executeQuery($q);
      return true;
   }

   /**
   * Unmerge()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Feb 06 15:51:34 PST 2006
   */
   function Unmerge($armc_id)
   {
      $q = "UPDATE `armc` SET `armc_group_id`=0 WHERE `armc_id`='$armc_id'";
      $this->executeQuery($q);
      return $this->rows;

   }

   /**
   * DeleteARMCGroup()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Mon Feb 06 15:55:01 PST 2006
   */
   function DeleteARMCGroup($armc_group_id)
   {
      $q = "DELETE FROM `armc_group_attr` WHERE `armc_group_id`='$armc_group_id'";
      $this->executeQuery($q);

      $q = "DELETE FROM `armc_group` WHERE `armc_group_id`='$armc_group_id'";
      $this->executeQuery($q);
      return true;
   }

   /**
   * InsertARMCGroup()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Tue Feb 07 17:01:17 PST 2006
   */
   function InsertARMCGroup()
   {
      $q = "INSERT INTO `armc_group` (`created_by`, `created_date`, `status`) VALUES ('".$this->created_by."', NOW(), 'A')";
      $this->executeQuery($q);
      return $this->last_id;
   }

   /**
   * InsertARMC()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Thu Feb 09 12:29:09 PST 2006
   */
   function InsertARMC($armc_type_id, $product_id=1)
   {
      $q = "INSERT INTO `armc` (`armc_date`, `armc_status_id`, `armc_type_id`, `product_id`, `armc_group_id`, `created_by`, `created_date`, `status`) VALUES "
      ." (NOW(), '".ARMC_STATUS_EDIT."', '$armc_type_id', '$product_id', '0', '".$this->created_by."', NOW(), 'A')";
      $this->executeQuery($q);
      return $this->last_id;
   }

   /**
   * SetARMCGroupID()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Tue Feb 07 17:09:23 PST 2006
   */
   function SetARMCGroupID($armc_id, $armc_group_id)
   {
      $q = "UPDATE `armc` SET `armc_group_id`='$armc_group_id' WHERE `armc_id`='$armc_id'";
      $this->executeQuery($q);
      return true;
   }

   /**
   * GetARMCTypeDetails()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Thu Feb 09 17:22:04 PST 2006
   */
   function GetARMCTypeDetails($armc_type_id)
   {
      $q = "SELECT `armc_type_id`, `armc_type_prefix`, `armc_type_description`, `created_by`, `created_date`, `modified_by`, `modified_date` "
      ."FROM `armc_type` WHERE `armc_type_id`='$armc_type_id' AND `status`='A'";
      return mysql_fetch_assoc($this->executeQuery($q));
   }

   /**
   * GetARMCCommentTypeDetails()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Fri Feb 17 14:48:12 PST 2006
   */
   function GetARMCCommentTypeDetails($armc_comment_type_id)
   {
      $q = "SELECT `armc_comment_type_id`, `armc_comment_type_description`, `created_by`, `created_date`, `modified_by`, `modified_date` "
      ."FROM `armc_comment_type` WHERE `armc_comment_type_id`='$armc_comment_type_id' AND `status`!='D'";
      return mysql_fetch_assoc($this->executeQuery($q));
   }

   /**
   * GetARMCSetting()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Thu Feb 09 17:38:02 PST 2006
   */
   function GetARMCSetting($armc_setting_name)
   {
      $q = "SELECT `armc_setting_id`, `armc_setting_name`, `armc_setting_value`, `armc_setting_description`, `created_by`, `created_date`, `modified_by`, `modified_date`"
      ." FROM `armc_setting` WHERE `armc_setting_name` = '$armc_setting_name' AND `status`='A'";
      return mysql_fetch_assoc($this->executeQuery($q));
   }

   /**
   * SetARMCSetting()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Thu Feb 09 17:53:56 PST 2006
   */
   function SetARMCSetting($armc_setting_name, $armc_setting_value)
   {
      $q = "UPDATE `armc_setting` SET `armc_setting_value` = '".mysql_real_escape_string($armc_setting_value)."', `modified_by`='".$this->created_by."', `modified_date`=NOW() WHERE `armc_setting_name`='$armc_setting_name' AND `status`='A'";
      $this->executeQuery($q);
      return $this->rows;
   }

   /**
   * GetARMCAmount()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Tue Mar 14 19:47:08 PST 2006
   */
   function GetARMCAmount($armc_id)
   {
      $q = "SELECT SUM(abli.actual_quantity*abli.actual_rate) AS amount FROM armc_budget_line_item AS abli WHERE abli.armc_id = '$armc_id' AND abli.status='A' GROUP BY abli.armc_id ";
      if ($row = mysql_fetch_assoc($this->executeQuery($q))) {
         return $row["amount"];
      }else{
         return 0;
      }
   }

   /**
   * GetARMCGroupAmount()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Tue Mar 14 20:12:03 PST 2006
   */
   function GetARMCGroupAmount($armc_group_id)
   {
      $q = "SELECT SUM(abli.actual_quantity*abli.actual_rate) AS amount FROM armc AS a LEFT JOIN armc_budget_line_item AS abli ON abli.armc_id=a.armc_id WHERE a.armc_group_id='$armc_group_id' AND a.status='A' AND abli.status='A' GROUP BY a.armc_group_id";
      if ($row = mysql_fetch_assoc($this->executeQuery($q))) {
         return $row["amount"];
      }else{
         return 0;
      }
   }

   /**
   * GetARMCEventCount()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Fri Feb 17 16:36:20 PST 2006
   */
   function GetARMCEventCount($armc_id, $armc_event_type_id)
   {
      $q = "
      SELECT
         count(armc_event_id) AS count
      FROM armc_event
      WHERE `armc_id`='$armc_id' AND `armc_event_type_id`='$armc_event_type_id' AND `status`='A'";
      return mysql_result($this->executeQuery($q), 0, "count");
   }

   /**
   * GetARMCAttrsCount()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Thu Feb 23 10:51:26 PST 2006
   */
   function GetARMCAttrsCount($armc_attr_name, $armc_attr_value)
   {
      //TODO add code
      $q = "SELECT count(armc_id) AS count FROM `armc_attr` WHERE `armc_attr_name`='$armc_attr_name' AND `armc_attr_value`='$armc_attr_value' AND `status`='A'";
      $rst = $this->executeQuery($q);
      return (($this->rows==0)?0:mysql_result($rst, 0, "count"));
   }

   /**
   * GetMTDForAE()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Sun Mar 05 16:08:46 PST 2006
   */
   function GetMTDInvoicedForAE($ae_user_id, $year, $month, $day)
   {
      $q =
      "SELECT
         a.armc_id,
         a.transaction_date,
         COUNT(abli.armc_id) AS count,
         SUM(abli.actual_rate*abli.actual_quantity) AS amount,
         au.login AS ae_login,
         a.armc_type_id
      FROM armc AS a
      LEFT JOIN armc_budget_line_item AS abli ON abli.armc_id = a.armc_id
      LEFT JOIN armc_user AS au ON au.armc_id = a.armc_id AND au.role_id = ".ROLE_PRIMARY_ACCT_EXEC." AND au.status='A'
      WHERE
         a.armc_status_id IN (".ARMC_STATUS_INVOICED.", ".ARMC_STATUS_INVOICED_MANUALLY.", ".ARMC_STATUS_INVOICED_PARTIAL_BALANCE.", ".ARMC_STATUS_INVOICED_BALANCED.")
         AND au.login = '$ae_user_id' AND a.transaction_date BETWEEN '$year-$month-01' AND '$year-$month-$day'
         AND a.status='A'
      GROUP BY a.armc_type_id";

      return $this->executeQuery($q);
   }

//   /**
//   * GetMTDByStatus()
//   *
//   * @@param
//   * @@param -
//   * @@return
//   * @@throws
//   * @@access
//   * @@global
//   * @@since  - Wed Mar 01 18:16:29 PST 2006
//   */
//   function GetMTDForStatus($status_ids, $year, $month, $day)
//   {
//      $q =
//	   "SELECT
//	        'false' AS grouped,
//	        a.status,
//	        a.armc_id,
//	        a.armc_group_id,
//	        a.created_date,
//	        a.armc_status_id,
//	        a.armc_type_id,
//	        aa_account_id.armc_attr_value AS account_id,
//	        aa_month_end.armc_attr_value AS is_month_end,
//	        ae.login AS account_executive_id,
//	        ae.last_name AS account_executive,
//	        am.login AS account_manager_id,
//	        am.last_name AS account_manager,
//	        SUM(abli.actual_quantity*abli.actual_rate) AS amount
//	    FROM armc AS a
//	    LEFT JOIN armc_attr AS aa_account_id ON aa_account_id.armc_id = a.armc_id AND aa_account_id.armc_attr_name = 'ACCOUNT_ID'
//	    LEFT JOIN armc_attr AS aa_month_end ON aa_month_end.armc_id = a.armc_id AND aa_month_end.armc_attr_name = 'ARMC_MONTH_END_BILLING'
//	    LEFT JOIN armc_user AS au_ae ON au_ae.armc_id = a.armc_id AND au_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC."
//	    LEFT JOIN user AS ae ON ae.login = au_ae.login
//	    LEFT JOIN armc_user AS au_am ON au_am.armc_id = a.armc_id AND au_am.role_id = ".ROLE_PRIMARY_ACCT_MGR."
//	    LEFT JOIN user AS am ON am.login = au_am.login
//	    LEFT JOIN armc_budget_line_item AS abli ON abli.armc_id = a.armc_id AND abli.status='A'
//       WHERE (a.armc_group_id = 0) AND (a.status='A') AND a.armc_status_id IN (".implode(", ", $status_ids).") AND (a.created_date BETWEEN '$year-$month-01' AND '$year-$month-$day')
//       GROUP BY a.armc_id
//       UNION ALL
//	    SELECT
//	       'true' AS grouped,
//	       ag.status,
//	       a.armc_group_id AS armc_id,
//	       a.armc_group_id,
//	       ag.created_date,
//	       MIN(a.armc_status_id) AS armc_status_id,
//	       a.armc_type_id,
//	       aga_account_id.armc_group_attr_value AS account_id,
//	       aa_month_end.armc_attr_value AS is_month_end,
//	       ae.login AS account_executive_id,
//	       ae.last_name AS account_executive,
//	       am.login AS account_manager_id,
//	       am.last_name AS account_manager,
//	       SUM(abli.actual_quantity*abli.actual_rate) AS amount
//	    FROM armc_group AS ag
//	    LEFT JOIN armc AS a ON a.armc_group_id = ag.armc_group_id AND a.status='A'
//	    LEFT JOIN armc_group_attr AS aga_account_id ON aga_account_id.armc_group_id = a.armc_group_id AND aga_account_id.armc_group_attr_name = 'ACCOUNT_ID'
//	    LEFT JOIN armc_attr AS aa_month_end ON aa_month_end.armc_id = a.armc_id AND aa_month_end.armc_attr_name = 'ARMC_MONTH_END_BILLING'
//       LEFT JOIN armc_user AS au_ae ON au_ae.armc_id = a.armc_id AND au_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC."
//	    LEFT JOIN user AS ae ON ae.login = au_ae.login
//	    LEFT JOIN armc_user AS au_am ON au_am.armc_id = a.armc_id AND au_am.role_id = ".ROLE_PRIMARY_ACCT_MGR."
//	    LEFT JOIN user AS am ON am.login = au_am.login
//	    JOIN armc_budget_line_item AS abli ON abli.armc_id = a.armc_id
//	    WHERE (a.armc_group_id <> 0) AND (ag.status='A') AND a.armc_status_id IN (".implode(", ", $status_ids).") AND (ag.created_date BETWEEN '$year-$month-01' AND '$year-$month-$day')
//	    GROUP BY armc_group_id";
//
//      return $this->executeQuery($q);
//   }

   /**
   * GetActionARMC()
   *
   * @@param
   * @@param -
   * @@return
   * @@throws
   * @@access
   * @@global
   * @@since  - Fri Mar 03 11:07:43 PST 2006
   */
   function GetActionARMC($start_date, $end_date, $user_id, $armc_action_type_id)
   {
      $q = "
      SELECT
         a.armc_type_id,
         COUNT(DISTINCT a.armc_id) AS count,
         SUM(abli.actual_quantity*abli.actual_rate) AS amount
      FROM armc_action AS aa
      LEFT JOIN armc AS a ON a.armc_id=aa.armc_id AND a.status='A'
      LEFT JOIN armc_budget_line_item AS abli ON abli.armc_id = aa.armc_id AND abli.status='A'
      WHERE aa.armc_action_type_id='$armc_action_type_id' AND aa.created_by='$user_id' AND aa.created_date BETWEEN '$start_date' AND '$end_date'
      GROUP BY a.armc_type_id";
      return $this->executeQuery($q);
   }

   /**
   * GetATMGLAccounts()
   *
   * @@param
   * @@todo NOT YET COMPLETED
   * @@return
   * @@since  - Thu Dec 21 08:05:32 PST 2006
   */
   function GetATMGLAccounts()
   {
      $q = "SELECT atm_gl_account_id, atm_gl_account_segment4, atm_gl_account_description FROM atm_gl_account WHERE status='A'";
      return $this->executeQuery($q);
   }

   /**
   * GetARMCBudgetLineItemDefAttr()
   *
   * @param
   * @param
   * @throws
   * @return
   * @access
   * @since  - Mon Jun 04 11:20:51 IST 2007
   */
   function GetARMCBudgetLineItemDefAttr($budget_line_item_def_id, $budget_line_item_def_attr_name)
   {
   	$q = "SELECT armc_budget_line_item_def_attr_value "
   		. "FROM armc_budget_line_item_def_attr "
   		. "WHERE armc_budget_line_item_def_id = ". $budget_line_item_def_id ." AND armc_budget_line_item_def_attr_name = '". $budget_line_item_def_attr_name ."'";
   	
   	$rst = $this->executeQuery($q);

   	if ($this->rows == 0)
         return false;
      else
         return mysql_fetch_assoc($rst);
   }

   /**
   * GetARMCBudgetLineItemDefAttrs()
   *
   * @param
   * @param
   * @throws
   * @return
   * @access
   * @since  - Wed Jun 06 07:46:27 IST 2007
   */
   function GetARMCBudgetLineItemDefAttrs($budget_line_item_def_id)
   {
   	$q = "SELECT armc_budget_line_item_def_attr_name, armc_budget_line_item_def_attr_value "
   		. "FROM armc_budget_line_item_def_attr "
   		. "WHERE armc_budget_line_item_def_id = ". $budget_line_item_def_id;

   	return $this->executeQuery($q);
   }


   /**
   * UpdateARMCBudgetLineItemDefAttr()
   *
   * @param
   * @param
   * @throws
   * @return
   * @access
   * @since  - Mon Jun 04 13:27:15 IST 2007
   */
   function UpdateARMCBudgetLineItemDefAttr($attr_value, $attr_name, $budget_line_item_def_id)
   {
   	$q = "UPDATE armc_budget_line_item_def_attr "
   		. "SET armc_budget_line_item_def_attr_value = '". $attr_value ."', modified_by = '". $this->created_by ."', modified_date = NOW(), status = 'A' "
   		. "WHERE armc_budget_line_item_def_id = ". $budget_line_item_def_id ." AND armc_budget_line_item_def_attr_name ='". $attr_name ."'";


   	return $this->executeQuery($q);
   }


   /**
   * InsertARMCBudgetLineItemDefAttr()
   *
   * @param
   * @param
   * @throws
   * @return
   * @access
   * @since  - Mon Jun 04 13:54:26 IST 2007
   */
   function InsertARMCBudgetLineItemDefAttr($budget_line_item_def_id, $attribute_name , $attribute_value)
   {
   	$q = "INSERT INTO armc_budget_line_item_def_attr(armc_budget_line_item_def_id, armc_budget_line_item_def_attr_name, armc_budget_line_item_def_attr_value,
   			created_by, created_date) "
   		. "VALUES(". $budget_line_item_def_id .", '". $attribute_name ."', '". $attribute_value ."', '". $this->created_by ."', NOW())";

   	return $this->executeQuery($q);
   }


   /**
   * DeleteARMCBudgetLineItemDefAttr()
   *
   * @param
   * @param
   * @throws
   * @return
   * @access
   * @since  - Tue Jun 05 06:22:20 IST 2007
   */
   function DeleteARMCBudgetLineItemDefAttr($budget_line_item_def_id, $attr_name)
   {
   	$q = "UPDATE armc_budget_line_item_def_attr "
   		. "SET status = 'D', modified_by = '". $this->created_by ."', modified_date = NOW() "
   		. "WHERE armc_budget_line_item_def_id = ". $budget_line_item_def_id ." AND armc_budget_line_item_def_attr_name ='". $attr_name ."'";

   	return $this->executeQuery($q);
   }


   /**
   * GetATMGLAccount()
   *
   * @param
   * @param
   * @throws
   * @return
   * @access
   * @since  - Tue Jun 05 07:48:32 IST 2007
   */
   function GetATMGLAccount($atm_gl_account_id)
   {
   	$q = "SELECT atm_gl_account_segment1, atm_gl_account_segment2, atm_gl_account_segment3, atm_gl_account_segment4, atm_gl_account_segment5,
   			atm_gl_account_segment6, atm_gl_account_segment7, atm_gl_account_description "
   		. "FROM atm_gl_account "
   		. "WHERE status = 'A' AND atm_gl_account_id = ". $atm_gl_account_id;


   	$rst = $this->executeQuery($q);

   	if ($this->rows == 0)
         return false;
      else
         return mysql_fetch_assoc($rst);

   }
   
   /**
   *  GetATMGLAccountBySegments
   *
   * @param 
   * @param  
   * @throws 
   * @return
   * @access
   * @since  - Fri Jun 08 10:18:51 IST 2007
   */
   function  GetATMGLAccountBySegments($segment1, $segment2, $segment3, $segment4, $segment5, $segment6, $segment7)
   {
   	$q = "SELECT atm_gl_account_id "
   		. "FROM atm_gl_account "
   		. "WHERE atm_gl_account_segment1 = '". $segment1. "' and atm_gl_account_segment2 = '". $segment2. "' and atm_gl_account_segment3 = '". $segment3. "' and 
   			atm_gl_account_segment4 = '". $segment4. "' and atm_gl_account_segment5 = '". $segment5. "' and atm_gl_account_segment6 = '". $segment6. "' and 
   			atm_gl_account_segment7 = '". $segment7. "' and status = 'A'";
   		
    	$rst = $this->executeQuery($q);
   	
   	if ($this->rows == 0)
         return false;
      else
         return mysql_fetch_assoc($rst);
   }

   /**
   * InsertATMGLAccount()
   *
   * @param
   * @param
   * @throws
   * @return
   * @access
   * @since  - Tue Jun 05 07:53:39 IST 2007
   */
   function InsertATMGLAccount($segment1, $segment2, $segment3, $segment4, $segment5, $segment6, $segment7, $description)
   {
   	$q = "INSERT INTO atm_gl_account(atm_gl_account_segment1, atm_gl_account_segment2, atm_gl_account_segment3, atm_gl_account_segment4, atm_gl_account_segment5,
   			atm_gl_account_segment6, atm_gl_account_segment7, atm_gl_account_description, created_by, created_date) "
   		. "VALUES('". $segment1 ."', '". $segment2 ."', '". $segment3 ."', '". $segment4 ."', '". $segment5 ."', '". $segment6 ."', '". $segment7 ."', '". $description ."',
   			'". $this->created_by ."', NOW())";

			$this->executeQuery($q);

		return $this->lastID;
   }


   /**
   * UpdateATMGLAccount()
   *
   * @param
   * @param
   * @throws
   * @return
   * @access
   * @since  - Tue Jun 05 08:00:56 IST 2007
   */
   function UpdateATMGLAccount($segment1, $segment2, $segment3, $segment4, $segment5, $segment6, $segment7, $description, $atm_gl_account_id)
   {
   	$q = "UPDATE atm_gl_account "
			. "SET atm_gl_account_segment1 = '". $segment1 ."', atm_gl_account_segment2 = '". $segment2 ."', atm_gl_account_segment3 = '". $segment3 ."',
				atm_gl_account_segment4 = '". $segment4 ."', atm_gl_account_segment5 = '". $segment5 ."', atm_gl_account_segment6 = '". $segment6 ."',
				atm_gl_account_segment7 = '". $segment7 ."', atm_gl_account_description = '". $description ."', modified_by = '". $this->created_by ."', modified_date = NOW() "
			. "WHERE atm_gl_account_id = ". $atm_gl_account_id;

   	return $this->executeQuery($q);
   }

   /**
   * DeleteATMGLAccount()
   *
   * @param
   * @param
   * @throws
   * @return
   * @access
   * @since  - Tue Jun 05 09:05:16 IST 2007
   */
   function DeleteATMGLAccount($atm_gl_account_id)
   {
   	$q = "UPDATE atm_gl_account "
   		. "SET status = 'D', modified_by = '". $this->created_by ."', modified_date = NOW() "
   		. "WHERE atm_gl_account_id = ". $atm_gl_account_id;

   	return $this->executeQuery($q);
   }

   /**
   * GetARMCBudgetLineItemDefByItemNumber()
   *
   * @param
   * @param
   * @throws
   * @return
   * @access
   * @since  - Fri Jun 08 07:44:03 IST 2007
   */
   function GetARMCBudgetLineItemDefByItemNumber($item_number, $status = 'A')
   {
   		$where = " AND status = '". $status ."'";  	
   		
   		if ($status != 'A') {
   			$where = " AND status <> 'A'"; 
   		}	
   	
	   	$q = "SELECT armc_budget_line_item_def_id, atm_gl_account_id, armc_budget_line_item_description, default_rate, default_quantity "
	   		. "FROM armc_budget_line_item_def "
	   		. "WHERE item_number = '". $item_number . "'" . $where;
	
	   	$rst = $this->executeQuery($q);
	
	   	if ($this->rows == 0)
	         return false;
	      else
	         return mysql_fetch_assoc($rst);

   }


   /**
   * InsertARMCBudgetItemDef()
   *
   * @param
   * @param
   * @throws
   * @return
   * @access
   * @since  - Fri Jun 08 09:39:13 IST 2007
   */
   function InsertARMCBudgetLineItemDef($gl_account_id, $line_item_description, $item_number, $default_rate, $default_quantity)
   {
   	$q = "INSERT INTO armc_budget_line_item_def(atm_gl_account_id, armc_budget_line_item_description, item_number, default_rate, default_quantity, created_by, created_date, status) "
   		. "VALUES (". $gl_account_id .", '". $line_item_description ."', '". $item_number ."', ". $default_rate .", ". $default_quantity .", ". $this->created_by .", NOW(), 'A')";

   	$this->executeQuery($q);

   	return $this->lastID;

   }


   /**
   * UpdateARMCBudgetLineItemDef()
   *
   * @param
   * @param
   * @throws
   * @return
   * @access
   * @since  - Fri Jun 08 09:44:19 IST 2007
   */
   function UpdateARMCBudgetLineItemDef($line_item_def_id, $gl_account_id, $line_item_description, $item_number, $default_rate, $default_quantity)
   {
   	$q = "UPDATE armc_budget_line_item_def "
   		. "SET atm_gl_account_id = ". $gl_account_id .", armc_budget_line_item_description = '". $line_item_description ."', item_number = '". $item_number ."',
   		   default_rate = ". $default_rate .", default_quantity = ". $default_quantity .", modified_by = ". $this->created_by .", modified_date = NOW(), status = 'A' "
   		. "WHERE armc_budget_line_item_def_id = ". $line_item_def_id;

   	$this->executeQuery($q);
   }


   /**
   * GetARMCBudgetLineItemDefAtrrDef()
   *
   * @param
   * @param
   * @throws
   * @return
   * @access
   * @since  - Fri Jun 08 12:31:10 IST 2007
   */
   function GetARMCBudgetLineItemDefAttrDef()
   {
   	$q = "SELECT armc_budget_line_item_def_attr_name, armc_budget_line_item_def_attr_description "
   		. "FROM armc_budget_line_item_def_attr_def "
   		. "WHERE status = 'A'";

   	$this->executeQuery($q);

   }

   /**
   * GetARMCBudgetLineItemDefs()
   *
   * @param
   * @throws
   * @return
   * @author - sujith
   * @since  - Fri Jun 08 10:19:07 IST 2007 +GMT 5.30
   */
   function GetARMCBudgetLineItemDefs()
   {
     $q = "SELECT armc_budget_line_item_def_id, item_number, atm_gl_account_id, armc_budget_line_item_description, default_rate, default_quantity "
   		. "FROM armc_budget_line_item_def "
   		. "WHERE status = 'A'";

   	 $rst = $this->executeQuery($q);

   	 return $rst;
   }

   /**
   * DeleteARMCBudgetLineItemDef()
   *
   * @param
   * @throws
   * @return
   * @author - sujith
   * @since  - Fri Jun 08 10:37:20 IST 2007 +GMT 5.30
   */
   function DeleteARMCBudgetLineItemDef($line_item_def_id)
   {
     $query = "UPDATE armc_budget_line_item_def "
     		. "SET status = 'D', modified_by = ". $this->created_by . ", modified_date = NOW() "
   		    . "WHERE armc_budget_line_item_def_id = ". $line_item_def_id;


   	 $this->executeQuery($query);
   }

}
?>
