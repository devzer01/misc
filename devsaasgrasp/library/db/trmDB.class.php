<?php

//training manager sql

class trmDB extends dbConnect {

	private  $__department_id = 10;
	
	private $__training_id = 0;

	/**
	* __construct()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Feb 02 12:52:10 PST 2006
	*/
	function __construct()
	{
	   parent::dbConnect();
	}
	
	/**
	* __deconstruct()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Feb 02 12:52:24 PST 2006
	*/
	function __deconstruct()
	{
	   //TODO add code
	}
	
	
	/**
	* SetTraining()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 15:08:32 PST 2006
	*/
	function SetTraining($account_id, $account_name, $country_code, $region_id, $product_id, $trm_training_type_id, $requested_by_login, $requested_start_date, $requested_end_date, 
									$trm_training_status_id)
	{
		$q = "INSERT INTO trm_training ( account_id, account_name, country_code, region_id, product_id, trm_training_type_id, requested_by_login, "
		   . "                           requested_start_date, requested_end_date, trm_training_status_id, created_by, created_date, status ) "
		   . "VALUES (". $account_id .", '". mysql_real_escape_string($account_name) ."', '". $country_code ."', ". $region_id .", ". $product_id .", ". $trm_training_type_id .", "
		   . "        ". $requested_by_login .", '". $requested_start_date ."', '". $requested_end_date ."', ". $trm_training_status_id .", ". $this->created_by .", NOW(), 'A') ";
		return $this->executeQuery($q);
	}
	
	/**
	* SetTrainingId()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 15:22:18 PST 2006
	*/
	function SetTrainingId($training_id)
	{
		$this->__training_id = $training_id;
	}
	
	/**
	* SetTrainingContact()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 15:21:39 PST 2006
	*/
	function SetTrainingContact($contact_id, $salutation, $first_name, $middle_initial, $last_name, $phone, $fax, $email)
	{
		$q = "INSERT INTO trm_training_contact (trm_training_id, contact_id, salutation, first_name, middle_initial, last_name, "
		   . "                                          phone, fax, email, created_by, created_date, status) "
		   . "VALUES (". $this->__training_id .", ". $contact_id .", '". $salutation ."', '". mysql_real_escape_string($first_name) ."', '". mysql_real_escape_string($middle_initial) ."', "
		   . "       '". mysql_real_escape_string($last_name) ."', '". $phone ."', '". $fax ."', '". $email ."', ". $this->created_by .", NOW(), 'A') ";
		return $this->executeQuery($q);
	}
	
	/**
	* GetTrainingContact()
	*
	* @param
	* @param 
	* @return
	* @since  - 23:59:36
	*/
	function GetTrainingContact()
	{
		$q = "SELECT trm_training_id, contact_id, salutation, first_name, middle_initial, last_name, "
		   . "       phone, fax, email, created_by, created_date, status "
		   . "FROM trm_training_contact "
		   . "WHERE status = 'A' AND trm_training_id = ". $this->__training_id;
		return $this->FetchAssoc($this->executeQuery($q));
	}
	
//	/**
//	* setRequest()
//	*
//	* @param
//	* @return
//	* @throws
//	* @access
//	* @global
//	*/
//	function setRequest($o)
//	{
//		//prepare query for the insert record
//		$qry  = "INSERT INTO trm_training";
//		$qry .= " (`partner_id`,`contact_id`,`trm_training_type_id`,`requested_by_login`,`requested_start_date`,`requested_end_date`,`training_status`,";
//		$qry .= "`created_by`,`created_date`,`status`)";
//		$qry .= " VALUES ('".$o['partner_id']."','".$o['contact_id']."','".$o['trm_training_type_id']."','".$o['requested_by_login']."','".$o['requested_start_date']."',";
//		$qry .= "'".$o['requested_end_date']."','R','".$o['created_by']."',NOW(),'A')";
//
//		//execute query
//		$this->executeQuery($qry);
//
//		//return the autogen id
//		return $this->lastID;
//
//	} //end setRequest

//	/**
//   * setRequest()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function getActiveTrainingList()
//   {
//		$qry  = "SELECT CONCAT('TR.',trm_training_id) AS training_id,trm_training_id AS id ";
//		$qry .= "FROM trm_training ";
//		$qry .= "WHERE training_status IN ('P','R') ";
//
//		$rs = $this->executeQuery($qry);
//
//		while ($r = mysql_fetch_assoc($rs)) {
//			$data[$r['id']] = $r['training_id'];
//		}
//
//		return $data;
//	}
	
	/**
	* SetTrainingComment()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 15:31:01 PST 2006
	*/
	function SetTrainingComment($comment, $public_comment = 0)
	{
		$q = "INSERT INTO trm_training_comment (trm_training_id, login, public_comment, comment_date, comments, created_by, created_date, status) "
		   . "VALUES (". $this->__training_id .", ". $this->created_by .", ". $public_comment .", NOW(), '". mysql_real_escape_string($comment) ."', "
		   . "        ". $this->created_by .", NOW(), 'A') ";
		return $this->executeQuery($q);
	}
	
//	/**
//   * setTrainingComments()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//	function setTrainingComments($o)
//	{
//		//escape our stuff
//      $o = $this->escapeIt($o);
//
//		//prepare query
//		$qry  = "INSERT INTO trm_training_comment";
//		$qry .= " (`trm_training_id`,`login`,`public_comment`,`comment_date`,`comments`,`created_by`,`created_date`,`status`)";
//		$qry .= " VALUES (".$o['trm_training_id'].",".$o['created_by'].",1,NOW(),'".$o['comments']."','".$o['created_by']."',NOW(),'A')";
//
//		//execute query
//		$this->executeQuery($qry);
//
//	}



	/**
	* SetAttr()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 19:54:34 PST 2006
	*/
	function SetAttr($attr_name, $attr_value)
	{
		$q = "INSERT INTO trm_training_attr (trm_training_id, training_attr, training_value, created_by, created_date, status) "
		   . "VALUES (". $this->__training_id .", '". $attr_name ."', '". $attr_value ."', ". $this->created_by .", NOW(), 'A') ";
		return $this->executeQuery($q);
	}
	
	/**
	* UpdateAtttr()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Sun Mar 12 08:41:17 PST 2006
	*/
	function UpdateAtttr($attr_name, $attr_value)
	{
		$q = "UPDATE trm_training_attr SET "
		   . "    training_value = '". $attr_value ."', modified_by = ". $this->created_by .", modified_date = NOW() "
		   . "WHERE trm_training_id = ". $this->__training_id ." AND training_attr = '". $attr_name ."' ";
		return $this->executeQuery($q);
	}

//	/**
//	* setAttr()
//	*
//	* @param
//	* @return
//	* @throws
//	* @access
//	* @global
//	*/
//	function	setAttr($o)
//	{
//		//get current attributes
//		$rs = $this->getAllAttr();
//
//		//loop through and check if we have index set
//		while ($r = mysql_fetch_assoc($rs)) {
//
//			//check if the given attributes are set
//			if (isset($o[$r['training_attr']])) {
//
//				//switch to figure our what type of attr we deal with
//				switch ($r['attribute_type']) {
//					case 'B':
//						$attr_type = 'bit';
//						$attr_value = 1; //since if the value is set it has to be 1 for bit type
//						break;
//				}
//
//				//do the actual insert
//				$qry  = "INSERT INTO `trm_training_attr_".$attr_type."`";
//				$qry .= "(`trm_training_id`,`training_attr`,`training_value`,`created_by`,`created_date`,`status`)";
//				$qry .= " VALUES (".$o['trm_training_id'].",'".$r['training_attr']."','".$attr_value."','".$o['created_by']."',NOW(),'A')";
//
//				$this->executeQuery($qry);
//			}
//
//		} //end setAttr()
//
//		//and set the right properties
//
//
//	}

	/**
	* GetAttrs()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 19:56:11 PST 2006
	*/
	function GetAttrs()
	{
		$q = "SELECT training_attr, training_value "
		   . "FROM trm_training_attr "
		   . "WHERE status = 'A' AND trm_training_id = ". $this->__training_id;
		return $this->executeQuery($q);
	}
	
	/**
	* GetAttr()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 19:57:14 PST 2006
	*/
	function GetAttr($attr_name)
	{
		$q = "SELECT training_attr, training_value "
		   . "FROM trm_training_attr "
		   . "WHERE status = 'A' AND trm_training_id = ". $this->__training_id ." AND training_attr = '". $attr_name ."' ";
		$r = $this->FetchAssoc($this->executeQuery($q));
		return ($r) ? $r['training_value'] : false;
	}

//	/**
//	* getAllAttr()
//	*
//	* @param
//	* @return
//	* @throws
//	* @access
//	* @global
//	*/
//	function getAllAttr()
//	{
//		//get the list of predefined attributes
//		$qry = "SELECT `attribute_type`,`training_attr`,`attribute_description` FROM `trm_training_attr_def`";
//		$rs = $this->executeQuery($qry);
//
//		return $rs;
//	}

	/**
	* GetTrainingTypes()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 15:03:49 PST 2006
	*/
	function GetTrainingTypes()
	{
		$q = "SELECT trm_training_type_id, training_type AS trm_training_type_description "
		   . "FROM trm_training_type "
		   . "WHERE status = 'A' ";
		return $this->executeQuery($q);		
	}
	
	/**
	* GetTrainingList()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 19:49:23 PST 2006
	*/
	function GetTrainingList($filter = '', $page = '', $sort = 't.created_date DESC')
	{
		$q = "SELECT t.trm_training_id, t.account_id, t.account_name, tc.first_name, tc.last_name, tc.email, tt.training_type AS training_type_description, "
		   . "       t.requested_start_date, t.requested_end_date, t.training_start_date, t.training_end_date, ts.trm_training_description, "
		   . "       u_cntby.last_name AS cntby_last_name, p.product_description, u_trainer.last_name AS trainer_last_name "
		   . "FROM trm_training AS t "
		   . "LEFT OUTER JOIN trm_training_contact AS tc ON tc.trm_training_id = t.trm_training_id "
		   . "LEFT OUTER JOIN trm_training_type AS tt ON tt.trm_training_type_id = t.trm_training_type_id "
		   . "LEFT OUTER JOIN trm_training_status AS ts ON ts.trm_training_status_id = t.trm_training_status_id "
		   . "LEFT OUTER JOIN user AS u_cntby ON u_cntby.login = t.contacted_by_login "
		   . "LEFT OUTER JOIN user AS u_trainer ON u_trainer.login = t.trainer_login "
		   . "LEFT OUTER JOIN product AS p ON p.product_id = t.product_id "
		   . "WHERE t.status = 'A' "
		   . $filter . " "
		   . "ORDER BY ". $sort ." "
		   . $page;
		return $this->executeQuery($q);
	}
	
//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//	function getTrainingRecords()
//	{
//		$qry = "SELECT trm.trm_training_id AS training_id,
//                     prt.company_name AS company_name,
//							CONCAT(cnt.first_name,' ',cnt.last_name) AS contact_name,
//							trm_type.training_type AS training_type,
//						   CONCAT(trm.requested_start_date,' - ',trm.requested_end_date) AS requested_dates,
//						   CONCAT(trm.training_start_date,' - ',trm.training_end_date) AS training_dates,
//							trm.training_status,
//							IF(trainer_assinged.training_value IS NULL,0,1) AS trainer_assinged,
//						   CONCAT(cnt_by.first_name,' ',cnt_by.last_name) AS contact_by
//				  FROM
//						   trm_training AS trm
//				  LEFT OUTER JOIN partners AS prt ON prt.partner_id = trm.partner_id
//				  LEFT OUTER JOIN contacts AS cnt ON cnt.contact_id = trm.contact_id
//				  LEFT OUTER JOIN trm_training_type AS trm_type ON trm_type.trm_training_type_id = trm.trm_training_type_id
//				  LEFT OUTER JOIN user AS cnt_by ON cnt_by.login = trm.contacted_by_login
//				  LEFT OUTER JOIN trm_training_attr_bit AS trainer_assinged ON
//						trainer_assinged.trm_training_id = trm.trm_training_id
//				  AND trainer_assinged.training_attr = 'TRAINER_ASSIGN'
//			";
//		return $this->executeQuery($qry);
//	}

	/**
	* GetTrainingDetail()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 15:36:48 PST 2006
	*/
	function GetTrainingDetail()
	{
		$q = "SELECT t.trm_training_id, t.account_name, tc.first_name, tc.last_name, tt.training_type AS training_type_description, "
		   . "       t.requested_start_date, t.requested_end_date, t.training_start_date, t.training_end_date, t.trm_training_status_id, "
		   . "       u_req.last_name AS req_last_name, u_trainer.last_name AS trainer_last_name, u_cntby.last_name AS cntby_last_name, "
		   . "       t.created_date, t.account_id, p.product_description, t.trainer_login "
		   . "FROM trm_training AS t "
		   . "LEFT OUTER JOIN trm_training_contact AS tc ON tc.trm_training_id = t.trm_training_id "
		   . "LEFT OUTER JOIN trm_training_type AS tt ON tt.trm_training_type_id = t.trm_training_type_id "
		   . "LEFT OUTER JOIN user AS u_req ON u_req.login = t.requested_by_login "
		   . "LEFT OUTER JOIN user AS u_trainer ON u_trainer.login = t.trainer_login "
		   . "LEFT OUTER JOIN user AS u_cntby ON u_cntby.login = t.contacted_by_login "
		   . "LEFT OUTER JOIN product AS p ON p.product_id = t.product_id "
		   . "WHERE t.status = 'A' AND t.trm_training_id = ". $this->__training_id;
		return $this->FetchAssoc($this->executeQuery($q));
	}
	
//   /**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function getTrainingRecord($id)
//   {
//      $qry = "SELECT trm.trm_training_id AS training_id,
//                     prt.company_name AS company_name,
//                     CONCAT(cnt.first_name,' ',cnt.last_name) AS contact_name,
//                     trm_type.training_type AS training_type,
//                     CONCAT(trm.requested_start_date,' - ',trm.requested_end_date) AS requested_dates,
//                     CONCAT(trm.training_start_date,' - ',trm.training_end_date) AS training_dates,
//							trm.requested_start_date,
//							trm.requested_end_date,
//							trm.training_start_date,
//							trm.training_end_date,
//                     trm.training_status,
//							CONCAT(req.first_name,' ',req.last_name) AS requestor_name,
//							CONCAT(trainer.first_name,' ',trainer.last_name) AS trainer,
//							CONCAT(cnt_by.first_name,' ',cnt_by.last_name) AS contact_by,
//							trm.created_date,
//							prt.partner_id
//              FROM
//                     trm_training AS trm
//              LEFT OUTER JOIN partners AS prt ON prt.partner_id = trm.partner_id
//              LEFT OUTER JOIN contacts AS cnt ON cnt.contact_id = trm.contact_id
//              LEFT OUTER JOIN trm_training_type AS trm_type ON trm_type.trm_training_type_id = trm.trm_training_type_id
//				  LEFT OUTER JOIN user AS cnt_by ON cnt_by.login = trm.contacted_by_login
//				  LEFT OUTER JOIN user AS req ON req.login = trm.requested_by_login
//				  LEFT OUTER JOIN user AS trainer ON trainer.login = trm.trainer_login
//				  WHERE trm.trm_training_id = $id
//         ";
//      $rs = $this->executeQuery($qry);
//		$data = mysql_fetch_assoc($rs);
//
//		//we will add the rest of the attributes to our result data
//		$keys = array('TRAINER_ASSIGN','CONTACT_TRAINING','AGENDA_AGREED','CERT_INCLUDE');
//		$fields = array('trainer_assinged','contacted_for_training','agenda_agreed','certification_included');
//
//		//loop through all the keys and set the value
//		for($i=0;$i<count($keys);$i++) {
//			$data[$fields[$i]] = $this->getAttr('trm_training_attr_bit',$id,$keys[$i]);
//		}
//
//      return $data;
//   }

//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function getAttr($table,$id,$key)
//   {
//		$qry = "SELECT training_value FROM $table WHERE trm_training_id = $id AND training_attr = '$key'";
//		$rs = $this->executeQuery($qry);
//
//		if (mysql_num_rows($rs) == 0) {
//			return 0;
//		}
//		return mysql_result($rs,0,0);
//	}

	/**
	* GetTasksList()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 20:12:50 PST 2006
	*/
	function GetTasksList()
	{
		$q = "SELECT task_t.task_type, task.task_type_id "
		   . "FROM task "
		   . "LEFT OUTER JOIN task_type AS task_t ON task_t.task_type_id = task.task_type_id "
		   . "WHERE task.status = 'A' ";
		return $this->executeQuery($q);
	}

//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function getParentTaskList()
//   {
//		$qry = "SELECT task.task_type AS parent_task,tk.task_type_id AS type_id
//				  FROM task AS tk
//				  LEFT OUTER JOIN task_type AS task ON task.task_type_id = tk.task_type_id
//				  GROUP BY parent_task";
//
//		$rs = $this->executeQuery($qry);
//
//		while ($r = mysql_fetch_assoc($rs)) {
//			$data[$r['type_id']] = $r['parent_task'];
//		}
//
//		return $data;
//	}

	/**
	* GetSubTaskList()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 20:20:24 PST 2006
	*/
	function GetSubTaskList($task_type_id)
	{
		$q = "SELECT task_t.task_subtype, task.task_id "
		   . "FROM task "
		   . "LEFT OUTER JOIN task_subtype AS task_t ON task_t.task_subtype_id = task.task_subtype_id "
		   . "WHERE task.status = 'A' AND task.task_type_id = ". $task_type_id;
		return $this->executeQuery($q);
	}

	/**
	* SetTrainingModule()
	*
	* @param
	* @param 
	* @return
	* @since  - 15:26:53
	*/
	function SetTrainingModule($module_id)
	{
		$q = "INSERT INTO trm_training_module (trm_training_id, module_id, created_by, created_date, status) "
		   . "VALUES (". $this->__training_id .", ". $module_id .", ". $this->created_by .", NOW(), 'A') ";
		return $this->executeQuery($q);
	}
	
	/**
	* GetTrainingModules()
	*
	* @param
	* @param 
	* @return
	* @since  - 15:29:38
	*/
	function GetTrainingModules()
	{
		$q = "SELECT ttm.trm_training_module_id, ttm.module_id, m.module_description "
		   . "FROM trm_training_module AS ttm "
		   . "LEFT OUTER JOIN module AS m ON m.module_id = ttm.module_id "
		   . "WHERE ttm.status = 'A' AND ttm.trm_training_id = ". $this->__training_id;
		return $this->executeQuery($q);
	}
//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function getSubTaskList($parent_id)
//   {
//
//		$qry = "SELECT task.task_subtype AS sub_task, tk.task_id AS type_id
//				  FROM task AS tk
//				  LEFT OUTER JOIN task_subtype AS task ON task.task_subtype_id = tk.task_subtype_id
//				  WHERE tk.task_type_id = $parent_id
//				  GROUP BY task_subtype";
//
//      return $this->executeQuery($qry);
//
//   }

	/**
	* SetTask()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 20:32:07 PST 2006
	*/
	function SetTask($task_id, $account_id, $account_name, $task_date, $task_minutes)
	{
		$q = "INSERT INTO time_reporting ( login, task_id, account_id, account_name, task_date, task_minutes, created_by, created_date, status) "
		   . "VALUES (". $this->created_by .", ". $task_id .", ". $account_id .", '". mysql_real_escape_string($account_name) ."', '". $task_date ."', "
		   . "        ". $task_minutes .", ". $this->created_by .", NOW(), 'A') ";
		return $this->executeQuery($q);
	}
   
//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function setTask($o)
//   {
//		$qry  = "INSERT INTO time_reporting ";
//		$qry .= " (`login`,`task_id`,`partner_id`,`task_date`,`task_minutes`,`created_by`,`created_date`) ";
//		$qry .= "VALUES (".$o['login'].",".$o['task_id'].",'".$o['partner_id']."','".$o['task_date']."',".$o['task_minutes'].",".$o['created_by'].",NOW())";
//
//		$this->executeQuery($qry);
//
//		return $this->lastID;
//	}


	/**
	* UpdateTask()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 20:44:28 PST 2006
	*/
	function UpdateTask($time_reporting_id, $task_minutes, $task_id, $task_date)
	{
		$q = "UPDATE time_reporting "
		   . "SET task_minutes = ". $task_minutes .", task_id = ". $task_id .", task_date = '". $task_date ."', " 
		   . "    modified_by = ". $this->created_by .", modified_date = NOW() "
		   . "WHERE time_reporting_id = ". $time_reporting_id;
		return $this->executeQuery($q);
	}
	
//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function updateTask($o)
//   {
//      $qry  = "UPDATE time_reporting ";
//		$qry .= "SET task_minutes = ".$o['task_minutes'].", modified_by = ".$o['created_by']." , modified_date = NOW() ";
//		$qry .= "WHERE time_reporting_id = ".$o['time_reporting_id'];
//
//      $this->executeQuery($qry);
//   }


   /**
   * SetTaskComment()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 10 20:40:50 PST 2006
   */
   function SetTaskComment($time_reporting_id, $public_comment, $comments)
   {
   	$q = "INSERT INTO time_reporting_comment (time_reporting_id, public_comment, comment_date, comments, created_by, created_date, status) "
   	   . "VALUES (". $time_reporting_id .", ". $public_comment .", NOW(), '". mysql_real_escape_string($comments) ."', ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }
   
//   /**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function setTaskComments($o)
//   {
//		$o = $this->escapeIt($o);
//
//		$qry  = "INSERT INTO time_reporting_comment ";
//		$qry .= " (`time_reporting_id`,`public_comment`,`comment_date`,`comments`,`created_by`,`created_date`) ";
//		$qry .= "VALUES (".$o['time_reporting_id'].",".$o['public_comment'].",NOW(),'".$o['comments']."',".$o['created_by'].",NOW())";
//
//		$this->executeQuery($qry);
//
//	}


	/**
	* UpdateTaskComment()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 20:46:16 PST 2006
	*/
	function UpdateTaskComment($time_reporting_id, $public_comment, $comments)
	{
		$q = "UPDATE time_reporting_comment "
		   . "SET public_comment = ". $public_comment .", comments = '". mysql_real_escape_string($comments) ."', "
		   . "    modified_by = ". $this->created_by .", modified_date = NOW() "
		   . "WHERE time_reporting_id = ". $time_reporting_id;
		return $this->executeQuery($q);
	}
	
//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function updateTaskComments($o)
//   {
//		$o = $this->escapeIt($o);
//
//      $qry  = "UPDATE time_reporting_comment ";
//      $qry .= "SET public_comment = ".$o['public_comment'].", comments = '".$o['comments']."', modified_by = ".$o['created_by'].", modified_date = NOW()";
//		$qry .= "WHERE time_reporting_id = ".$o['time_reporting_id'];
//      $this->executeQuery($qry);
//   }


	/**
	* AssociateTask()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 20:53:57 PST 2006
	*/
	function AssociateTask($time_reporting_id)
	{
		$q = "INSERT INTO trm_training_time_reporting (trm_training_id, time_reporting_id, created_by, created_date, status ) "
		   . "VALUES (". $this->__training_id .", ". $time_reporting_id .", ". $this->created_by .", NOW(), 'A') ";
		return $this->executeQuery($q);
	}
	
//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function assocTask($o)
//   {
//      $qry  = "INSERT INTO trm_training_time_reporting ";
//      $qry .= " (`trm_training_id`,`time_reporting_id`,`created_by`,`created_date`) ";
//      $qry .= "VALUES (".$o['training_id'].",".$o['time_reporting_id'].",".$o['created_by'].",NOW())";
//
//      $this->executeQuery($qry);
//
//   }
//   
   
   /**
   * GetTrainingTasks()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 10 16:00:14 PST 2006
   */
   function GetTrainingTasks()
   {
   	$q  = "SELECT tg.task_group, tt.task_type, st.task_subtype, c.comments, "
		    . "       tr.task_date, tr.task_minutes, tr.time_reporting_id, (tr.task_minutes DIV 60) AS hours, "
		    . "       MOD(tr.task_minutes,60) AS mins "
		    . "FROM trm_training_time_reporting AS ttr "
		    . "LEFT OUTER JOIN time_reporting AS tr ON tr.time_reporting_id = ttr.time_reporting_id "
		    . "LEFT OUTER JOIN task AS tk ON tk.task_id = tr.task_id "
		    . "LEFT OUTER JOIN task_group AS tg ON tg.task_group_id = tk.task_group_id "
		    . "LEFT OUTER JOIN task_type AS tt ON tt.task_type_id = tk.task_type_id "
		    . "LEFT OUTER JOIN task_subtype AS st ON st.task_subtype_id = tk.task_subtype_id "
		    . "LEFT OUTER JOIN time_reporting_comment AS c ON c.time_reporting_id = tr.time_reporting_id "
		    . "WHERE ttr.trm_training_id = ".$this->__training_id;
		return $this->executeQuery($q);
   }

//	  /**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function getTrainingTasks($id)
//   {
//		$qry  = "SELECT CONCAT( tg.task_group, '::', tt.task_type, '::', st.task_subtype ) AS task_name, SUBSTR(c.comments,1,50) AS comments, ";
//		$qry .= "       tr.task_date, tr.task_minutes, tr.time_reporting_id ";
//		$qry .= "FROM trm_training_time_reporting AS t ";
//		$qry .= "LEFT OUTER JOIN time_reporting AS tr ON tr.time_reporting_id = t.time_reporting_id ";
//		$qry .= "LEFT OUTER JOIN task AS tk ON tk.task_id = tr.task_id ";
//		$qry .= "LEFT OUTER JOIN task_group AS tg ON tg.task_group_id = tk.task_group_id ";
//		$qry .= "LEFT OUTER JOIN task_type AS tt ON tt.task_type_id = tk.task_type_id ";
//		$qry .= "LEFT OUTER JOIN task_subtype AS st ON st.task_subtype_id = tk.task_subtype_id ";
//		$qry .= "LEFT OUTER JOIN time_reporting_comment AS c ON c.time_reporting_id = tr.time_reporting_id ";
//		$qry .= "WHERE t.trm_training_id = ".$id;
//
//      $rs = $this->executeQuery($qry);
//
//		while ($r = mysql_fetch_assoc($rs)) {
//			$data[] = $this->unescapeIt($r);
//		}
//
//		return $data;
//   }


	/**
	* GetTaskDetail()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 20:48:48 PST 2006
	*/
	function GetTaskDetail($time_reporting_id)
	{
		$q = "SELECT tt.task_type, ts.task_subtype, trc.comments, tr.task_date, trc.public_comment, tr.task_minutes, tr.time_reporting_id, tr.task_id, "
		   . "       t_tr.account_id, t_tr.account_name, t_tr.trm_training_id, t_tr.training_name, t.task_type_id, t.task_subtype_id "
		   . "FROM time_reporting AS tr "
		   . "LEFT OUTER JOIN task AS t ON t.task_id = tr.task_id "
		   . "LEFT OUTER JOIN task_group AS tg ON tg.task_group_id = t.task_group_id "
		   . "LEFT OUTER JOIN task_type AS tt ON tt.task_type_id = t.task_type_id "
		   . "LEFT OUTER JOIN task_subtype AS ts ON ts.task_subtype_id = t.task_subtype_id "
		   . "LEFT OUTER JOIN time_reporting_comment AS trc ON trc.time_reporting_id = tr.time_reporting_id "
		   . "LEFT OUTER JOIN trm_training_time_reporting AS tttr ON tttr.time_reporting_id = tr.time_reporting_id "
		   . "LEFT OUTER JOIN trm_training AS t_tr ON t_tr.trm_training_id = tttr.trm_training_id "
		   . "WHERE tr.time_reporting_id = ". $time_reporting_id;
		return $this->FetchAssoc($this->executeQuery($q));
	}
	
//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function getTaskDetail($id)
//   {
//		$qry  = "SELECT tt.task_type, st.task_subtype, c.comments, tr.task_date, c.public_comment, ";
//		$qry .= "(MOD(tr.task_minutes,60) DIV 5) AS mins, (tr.task_minutes DIV 60) AS hours,tr.time_reporting_id, tr.task_id  ";
//      $qry .= "FROM time_reporting AS tr ";
//      $qry .= "LEFT OUTER JOIN task AS tk ON tk.task_id = tr.task_id ";
//      $qry .= "LEFT OUTER JOIN task_group AS tg ON tg.task_group_id = tk.task_group_id ";
//      $qry .= "LEFT OUTER JOIN task_type AS tt ON tt.task_type_id = tk.task_type_id ";
//      $qry .= "LEFT OUTER JOIN task_subtype AS st ON st.task_subtype_id = tk.task_subtype_id ";
//      $qry .= "LEFT OUTER JOIN time_reporting_comment AS c ON c.time_reporting_id = tr.time_reporting_id ";
//      $qry .= "LEFT OUTER JOIN trm_training_time_reporting AS t ON t.time_reporting_id = tr.time_reporting_id ";
//      $qry .= "WHERE tr.time_reporting_id = ".$id;
//
//      return $this->unescapeIt(mysql_fetch_assoc($this->executeQuery($qry)));
//   }


   
   /**
   * GetTrainingComments()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 10 16:07:24 PST 2006
   */
   function GetTrainingComments()
   {
   	$q = "SELECT tc.comments, tc.comment_date, tc.created_by, u_cb.last_name "
   	   . "FROM trm_training_comment AS tc "
   	   . "LEFT OUTER JOIN user AS u_cb ON u_cb.login = tc.created_by "
   	   . "WHERE tc.trm_training_id = ". $this->__training_id;
   	return $this->executeQuery($q);
   }
   
//   /**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function getTrainingComments($id)
//   {
//		$qry  = "SELECT CONCAT(u.first_name,' ',u.last_name) AS name, c.comment_date, c.comments ";
//		$qry .= "FROM trm_training_comment AS c ";
//		$qry .= "LEFT OUTER JOIN user AS u ON u.login = c.login ";
//		$qry .= "WHERE c.trm_training_id = ".$id;
//
//		$rs = $this->executeQuery($qry);
//
//		while ($r = mysql_fetch_assoc($rs)) {
//			$data[] = $this->unescapeIt($r);
//		}
//
//		return $data;
//	}



	/**
	* GetTasks()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 21:03:44 PST 2006
	*/
	function GetTasks($login, $task_date_begin, $task_date_end)
	{
		if (is_array($login)) 
			$login = implode(",", $login);
		
		$q  = "SELECT tg.task_group, tt.task_type, st.task_subtype, c.comments, tr.task_date, tr.task_minutes, "
		    . "		  tr.time_reporting_id "
          . "FROM time_reporting AS tr "
          . "LEFT OUTER JOIN task AS tk ON tk.task_id = tr.task_id "
          . "LEFT OUTER JOIN task_group AS tg ON tg.task_group_id = tk.task_group_id "
          . "LEFT OUTER JOIN task_type AS tt ON tt.task_type_id = tk.task_type_id "
          . "LEFT OUTER JOIN task_subtype AS st ON st.task_subtype_id = tk.task_subtype_id "
          . "LEFT OUTER JOIN time_reporting_comment AS c ON c.time_reporting_id = tr.time_reporting_id "
		    . "LEFT OUTER JOIN trm_training_time_reporting AS t ON t.time_reporting_id = tr.time_reporting_id "
          . "WHERE tr.login IN (". $login .") AND tr.status = 'A' "
		    . "AND tr.task_date BETWEEN '". $task_date_begin ."' AND '". $task_date_end ."'";
		return $this->executeQuery($q);
	}
	
//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function getMyTasks($o)
//   {
//      $qry  = "SELECT CONCAT( tg.task_group, '::', tt.task_type, '::', st.task_subtype ) AS task_name, SUBSTR(c.comments,1,50) AS comments, tr.task_date, tr.task_minutes, ";
//		$qry .= "		 tr.time_reporting_id ";
//      $qry .= "FROM time_reporting AS tr ";
//      $qry .= "LEFT OUTER JOIN task AS tk ON tk.task_id = tr.task_id ";
//      $qry .= "LEFT OUTER JOIN task_group AS tg ON tg.task_group_id = tk.task_group_id ";
//      $qry .= "LEFT OUTER JOIN task_type AS tt ON tt.task_type_id = tk.task_type_id ";
//      $qry .= "LEFT OUTER JOIN task_subtype AS st ON st.task_subtype_id = tk.task_subtype_id ";
//      $qry .= "LEFT OUTER JOIN time_reporting_comment AS c ON c.time_reporting_id = tr.time_reporting_id ";
//		$qry .= "LEFT OUTER JOIN trm_training_time_reporting AS t ON t.time_reporting_id = tr.time_reporting_id ";
//      $qry .= "WHERE tr.login = ".$o['created_by']." AND  tr.status = 'A' ";
//		$qry .= "AND tr.task_date >= '".$o['date_begin']."' AND tr.task_date <= '".$o['date_end']."'";
//
//		$rs = $this->executeQuery($qry);
//
//      while ($r = mysql_fetch_assoc($rs)) {
//         $data[] = $this->unescapeIt($r);
//      }
//
//      return $data;
//   }



//  	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function getPartnerName($id)
//	{
//		$qry = "SELECT p.company_name FROM partners AS p WHERE partner_id = ".$id;
//		$rs = $this->executeQuery($qry);
//
//		return mysql_result($rs,0,0);
//
//	} //end getPartnerName




//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function getTrainerList()
//   {
//	$qry = "SELECT DISTINCT CONCAT(u.first_name,' ',u.last_name) AS name, u.login
//          FROM user u
//          left outer join user_security_group usg on (usg.login = u.login)
//          left outer join security_group_security sgs on (sgs.security_group_id = usg.security_group_id)
//          left outer join security s on (sgs.security_id = s.security_id)
//          where s.security_type = 'IS_TRAINING'
//          ORDER BY first_name";
//		$rs = $this->executeQuery($qry);
//
//		while($r = mysql_fetch_assoc($rs)) {
//			$data[$r['login']] = $r['name'];
//		}
//
//		return $data;
//	}

	/**
	* SetTrainingDates()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 19:46:13 PST 2006
	*/
	function SetTrainingDates($training_start_date, $training_end_date)
	{
		$q = "UPDATE trm_training "
		   . "SET training_start_date = '". $training_start_date ."', training_end_date = '". $training_end_date ."', "
		   . "    modified_by = ". $this->created_by .", modified_date = NOW() "
		   . "WHERE trm_training_id = ". $this->__training_id;
		return $this->executeQuery($q);
		
	}

//	 /**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function setTrainingDate($o)
//   {
//		$qry  = "UPDATE trm_training ";
//		$qry .= "SET training_start_date = '".$o['training_start_date']."', training_end_date = '".$o['training_end_date']."' ";
//		$qry .= "WHERE trm_training_id = ".$o['training_id'];
//
//		$this->executeQuery($qry);
//	}

	/**
	* SetTrainer()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 19:48:02 PST 2006
	*/
	function SetTrainer($trainer_login)
	{
		$q = "UPDATE trm_training "
		   . "SET trainer_login = ". $trainer_login .", modified_by = ". $this->created_by .", modified_date = NOW() "
		   . "WHERE trm_training_id = ". $this->__training_id;
		return $this->executeQuery($q);
	}

//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function setTrainer($o)
//   {
//		$qry  = "UPDATE trm_training ";
//		$qry .= "SET trainer_login = ".$o['trainer_login']." ";
//		$qry .= "WHERE trm_training_id = ".$o['training_id'];
//
//		$this->executeQuery($qry);
//
//
//		//we are setting predefined attribute here
//	 	$qry  = "REPLACE INTO `trm_training_attr_bit`";
//      $qry .= "(`trm_training_id`,`training_attr`,`training_value`,`created_by`,`created_date`,`status`)";
//      $qry .= " VALUES (".$o['training_id'].",'TRAINER_ASSIGN',1,'".$o['created_by']."',NOW(),'A')";
//
//		$this->executeQuery($qry);
//	}

//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function getContact($o)
//   {
//		$qry  = "SELECT c.first_name,c.last_name,c.email FROM trm_training AS t ";
//		$qry .= "LEFT OUTER JOIN contacts AS c ON c.contact_id = t.contact_id ";
//		$qry .= "WHERE t.trm_training_id = ".$o['training_id'];
//
//		$rs = $this->executeQuery($qry);
//
//		return mysql_fetch_assoc($rs);
//	}


	/**
	* UpdateTrainingStatus()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 19:59:32 PST 2006
	*/
	function UpdateTrainingStatus($training_status_id)
	{
		$q = "UPDATE trm_training "
		   . "SET trm_training_status_id = ". $training_status_id .", modified_by = ". $this->created_by .", modified_date = NOW() "
		   . "WHERE trm_training_id = ". $this->__training_id;
		return $this->executeQuery($q);
	}

//   /**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function setTrainingStatus($o)
//   {
//		//set training status;
//		$qry = "UPDATE trm_training SET training_status = '".$o['training_status']."' WHERE trm_training_id = ".$o['training_id'];
//
//		$this->executeQuery($qry);
//	}

//   /**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function setAgendaAgreed($o)
//   {
// 	 	//we are setting predefined attribute here
//      $qry  = "INSERT INTO `trm_training_attr_bit`";
//      $qry .= "(`trm_training_id`,`training_attr`,`training_value`,`created_by`,`created_date`,`status`)";
//      $qry .= " VALUES (".$o['training_id'].",'AGENDA_AGREED',1,'".$o['created_by']."',NOW(),'A')";
//
//		$this->executeQuery($qry);
//   }

	/**
	* SetContactBy()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Sun Mar 12 00:05:16 PST 2006
	*/
	function SetContactBy($contacted_by_login)
	{
		$q = "UPDATE trm_training SET contacted_by_login = ". $contacted_by_login .", modified_by = ". $this->created_by .", modified_date = NOW() "
		   . "WHERE trm_training_id = ". $this->__training_id;
		return $this->executeQuery($q);
	}

//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function setContactTraining($o)
//   {
//      //we are setting predefined attribute here
//      $qry  = "INSERT INTO `trm_training_attr_bit`";
//      $qry .= "(`trm_training_id`,`training_attr`,`training_value`,`created_by`,`created_date`,`status`)";
//      $qry .= " VALUES (".$o['training_id'].",'CONTACT_TRAINING',1,'".$o['created_by']."',NOW(),'A')";
//
//      $this->executeQuery($qry);
//
//		$qry = "UPDATE trm_training SET contacted_by_login = ".$o['created_by']." WHERE trm_training_id = ".$o['training_id'];
//		$this->executeQuery($qry);
//
//   }

//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function addContact($o)
//   {
//		$qry  = "INSERT INTO contacts ";
//		$qry .= "(partner_id,first_name,last_name,email,phone_1) ";
//		$qry .= "VALUES ('".$o['partner_id']."','".$o['first_name']."','".$o['last_name']."','".$o['email']."','".$o['phone']."') ";
//
//		$rs = $this->executeQuery($qry);
//
//		return $this->lastID;
//	}

//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function assocContact($o)
//   {
//		$qry = "INSERT INTO partner_contact_old (p_id,c_id) VALUES (".$o['partner_id'].",".$o['contact_id'].")";
//		$this->executeQuery($qry);
//   }


	/**
	* DeleteTask()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 10 21:02:20 PST 2006
	*/
	function DeleteTask($time_reporting_id)
	{
		$q = "UPDATE time_reporting "
		   . "SET status = 'D', modified_by = ". $this->created_by .", modified_date = NOW() "
		   . "WHERE time_reporting_id = ". $time_reporting_id;
		return $this->executeQuery($q);
	}
	
//	/**
//   * getAllAttr()
//   *
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function delTask($o)
//   {
//		$qry  = "UPDATE time_reporting SET status = 'D', modified_by = ".$o['created_by'].", modified_date = NOW() ";
//		$qry .= "WHERE time_reporting_id = ".$o['id'];
//
//		$this->executeQuery($qry);
//
//	}

	 /**
   * BuildSearchString()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jul 24 14:13:32 PDT 2005
   */
   function BuildSearchString($o)
   {
      $str = '';
      
      $tz = GetTimeZone($o);
      
      foreach ($o as $key => $val) {
         if (preg_match("/^sc_/",$key)) {

            $sc = preg_replace("/^sc_/","",$key);
            $so = $sc;

            if (isset($o['sf_'.$sc])) {
               $sc = $o['sf_'.$sc];
            }

            if (isset($o['so_'.$so]) && $o['so_'.$so] == 'WILDCARD' && $o[$key] != '') {
               $str .= " AND ".$sc." LIKE '%".mysql_real_escape_string($o[$key])."%' ";
            } elseif ($o[$key] != '') {
               $str .= " AND ".$sc." = '".mysql_real_escape_string($o[$key])."' ";
            }
         } elseif (preg_match("/^dtc_/",$key)) {
            $sc    = $o[$key];

            $sf = preg_replace("/^dtc_/","",$key);

            $begin = $o[$sf."_begin"];
            $end   = $o[$sf."_end"];
            
            if ($begin != '' && $end != '') {
	            if (isset($o[$sf."_tz"]) && $o[$sf."_tz"] == 0) {
	            	$str .= " AND ". $sc ." BETWEEN '".$begin."' AND '".$end."' ";	
	            } else {
	            	$str .= " AND CONVERT_TZ(". $sc .",'+00:00','". $tz ."') BETWEEN '".$begin."' AND '".$end."' ";	
	            }
            }
         }
      }
      return $str;
   }
   
   /**
   * GetTrainingStatus()
   *
   * @param
   * @param 
   * @return
   * @since  - 09:03:52
   */
   function GetTrainingStatus()
   {
   	$q = "SELECT trm_training_status_id, trm_training_description "
   	   . "FROM trm_training_status "
   	   . "WHERE status = 'A' ";
   	return $this->executeQuery($q);
   }

}
?>
