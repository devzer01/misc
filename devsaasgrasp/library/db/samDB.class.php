<?php
//since v0.5 of study manager we have changed our coding standards so we are capilizing all the first letters of a method name including the very first letter.
class samDB extends dbConnect {


   /**
   * samDB()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function samDB()
   {
      parent::dbConnect();
   }

   /**
   * GetSAM()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 27 10:28:10 PST 2006
   */
   function GetSAM($sam_type_id, $year)
   {
      $q = "SELECT sam_type_id, MONTH(sam_date) AS month, amount FROM sam WHERE sam_type_id='$sam_type_id' AND sam_date BETWEEN '$year-01-01' AND '$year-12-31' AND status='A'";
      return $this->executeQuery($q);
   }
   
   function isSAM($sam_type_id, $year, $month)
   {
   	$q = "SELECT sam_id FROM sam WHERE sam_type_id='$sam_type_id' AND sam_date BETWEEN '$year-$month-01' AND '$year-$month-28' AND status='A'";
   	return mysql_num_rows($this->executeQuery($q));
   }

   /**
   * CalcSAM()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Apr 07 16:02:28 PDT 2006
   */
   function CalcSAM($sam_type_id, $year, $month)
   {
      if ($month<10)
         $month = "0".$month;
      $q = "SELECT SUM(sa.amount) AS amount FROM sam_account AS sa LEFT JOIN user AS u ON u.login=sa.user_id WHERE sa.sam_type_id='$sam_type_id' AND sa.sam_date LIKE '%$year-$month-01%' AND sa.user_id!=0 AND sa.region_id!=0 AND sa.account_id!=0 AND sa.status='A' AND u.status='A' GROUP BY sa.sam_type_id";
      $rst = $this->executeQuery($q);
      return ($this->rows?mysql_result($this->executeQuery($q), 0, "amount"):0);
   }

   /**
   * SetSAM()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 27 13:06:12 PST 2006
   */
   function SetSAM($sam_type_id, $year, $month, $amount)
   {
      $q = "SELECT sam_id FROM sam WHERE sam_type_id = '$sam_type_id' AND sam_date = '$year-$month-01' AND status='A'";
      $this->executeQuery($q);
      if ($this->rows) {
         $q = "UPDATE `sam` SET `amount`='$amount', `modified_by`='".$this->created_by."', `modified_date`=NOW() "
         ." WHERE sam_type_id = '$sam_type_id' AND sam_date = '$year-$month-01'";

      }else{
         $q = "INSERT INTO `sam` (`sam_type_id`, `sam_date`, `amount`, `created_by`, `created_date`, `status`) "
         ." VALUES ('$sam_type_id', '$year-$month-01', '$amount', '".$this->created_by."', NOW(), 'A')";
      }
      $this->executeQuery($q);
      return $this->rows;
   }

//   /**
//   * AddSAM()
//   *
//   * @param
//   * @param -
//   * @return
//   * @throws
//   * @access
//   * @global
//   * @since  - Mon Mar 06 12:55:17 PST 2006
//   */
//   function AddSAM($sam_type_id, $year, $month, $amount)
//   {
//      $q = "SELECT amount FROM sam WHERE sam_type_id='$sam_type_id' AND sam_date = '$year-$month-01' AND status='A'";
//      $amt = mysql_fetch_assoc($this->executeQuery($q));
//      if ($this->rows) {
//         $amt = $amt["amount"];
//         $q = "UPDATE sam SET `amount` = '".($amount+$amt)."', `modified_by`='".$this->created_by."', `modified_date`=NOW() "
//         ." WHERE sam_type_id='$sam_type_id' AND sam_date='$year-$month-01'";
//      }else{
//         $q = "INSERT INTO sam (`sam_type_id`, `sam_date`, `amount`, `created_by`, `created_date`, `status`) "
//         ."VALUES ('$sam_type_id', '$year-$month-01', '$amount', '".$this->created_by."', NOW(), 'A')";
//      }
//      $this->executeQuery($q);
//      return $this->rows;
//
//   }

   /**
   * GetSAMRegion()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 27 09:56:17 PST 2006
   */
   function GetSAMRegion($region_id, $sam_type_id, $year)
   {
      $q = "SELECT region_id, sam_type_id, MONTH(sam_date) AS month, amount FROM sam_region WHERE region_id='$region_id' AND sam_type_id = '$sam_type_id' AND sam_date BETWEEN '$year-01-01' AND '$year-12-31' AND status='A'";
      return $this->executeQuery($q);
   }
   
   function isSAMRegion($region_id, $sam_type_id, $year, $month)
   {
   	$q = "SELECT sam_region_id FROM sam_region WHERE region_id='$region_id' AND sam_type_id='$sam_type_id' AND sam_date BETWEEN '$year-$month-01' AND '$year-$month-28' AND status='A'";
   	return mysql_num_rows($this->executeQuery($q));
   }

   /**
   * CalcSAMRegion()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Apr 07 16:14:47 PDT 2006
   */
   function CalcSAMRegion($region_id, $sam_type_id, $year, $month)
   {
      if ($month<10)
         $month="0".$month;
      $q = "SELECT SUM(sa.amount) AS amount FROM sam_account AS sa LEFT JOIN user AS u ON u.login=sa.user_id WHERE sa.region_id = '$region_id' AND sa.sam_type_id='$sam_type_id' AND sa.sam_date LIKE '%$year-$month-01%' AND sa.user_id!=0 AND sa.region_id!=0 AND sa.account_id!=0 AND sa.status='A' AND u.status='A' GROUP BY sa.sam_type_id, sa.region_id";
      $rst = $this->executeQuery($q);
      return ($this->rows?mysql_result($this->executeQuery($q), 0, "amount"):0);
   }

   /**
   * SetSAMRegion()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 27 12:17:35 PST 2006
   */
   function SetSAMRegion($region_id, $sam_type_id, $year, $month, $amount)
   {
      $q = "SELECT sam_region_id FROM sam_region WHERE region_id = '$region_id' AND sam_type_id='$sam_type_id' AND sam_date = '$year-$month-01' AND status='A'";
      $this->executeQuery($q);
      if ($this->rows) {
         $q =
         "UPDATE
            sam_region
         SET
            `amount`='$amount',
            `modified_by`='".$this->created_by."',
            `modified_date`=NOW()
         WHERE region_id='$region_id' AND sam_type_id='$sam_type_id' AND sam_date='$year-$month-01'";
      }else{
         $q = "INSERT INTO sam_region (`region_id`, `sam_type_id`, `sam_date`, `amount`, `created_by`, `created_date`, `status`) "
         ."VALUES ('$region_id', '$sam_type_id', '$year-$month-01', '$amount', '".$this->created_by."', NOW(), 'A')";
      }
      $this->executeQuery($q);
      return $this->rows;
   }

//   /**
//   * AddSAMRegion()
//   *
//   * @param
//   * @param -
//   * @return
//   * @throws
//   * @access
//   * @global
//   * @since  - Mon Mar 06 12:53:04 PST 2006
//   */
//   function AddSAMRegion($region_id, $sam_type_id, $year, $month, $amount)
//   {
//      $q = "SELECT amount FROM sam_region WHERE region_id='$region_id' AND sam_type_id='$sam_type_id' AND sam_date = '$year-$month-01' AND status='A'";
//      $amt = mysql_fetch_assoc($this->executeQuery($q));
//      if ($this->rows) {
//         $amt = $amt["amount"];
//         $q = "UPDATE sam_region SET `amount` = '".($amount+$amt)."', `modified_by`='".$this->created_by."', `modified_date`=NOW() "
//         ." WHERE region_id='$region_id' AND sam_type_id='$sam_type_id' AND sam_date='$year-$month-01'";
//      }else{
//         $q = "INSERT INTO sam_region (`region_id`, `sam_type_id`, `sam_date`, `amount`, `created_by`, `created_date`, `status`) "
//         ."VALUES ('$region_id', '$sam_type_id', '$year-$month-01', '$amount', '".$this->created_by."', NOW(), 'A')";
//      }
//      $this->executeQuery($q);
//      return $this->rows;
//   }

   /**
   * GetSAMUserRegion()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 03 15:07:40 PST 2006
   */
   function GetSAMUserRegion($user_id, $region_id, $sam_type_id, $year)
   {
      $q = "SELECT user_id, region_id, MONTH(sam_date) AS month, amount FROM sam_user "
      ."WHERE user_id='$user_id' AND region_id='$region_id' AND sam_type_id='$sam_type_id' AND sam_date BETWEEN '$year-01-01' AND '$year-12-31' AND status='A'";
      return $this->executeQuery($q);
   }

   function isSAMUserRegion($user_id, $region_id, $sam_type_id, $year, $month)
   {
      $q = "SELECT sam_user_id FROM sam_user WHERE user_id='$user_id' AND region_id='$region_id' AND sam_type_id='$sam_type_id' AND sam_date BETWEEN '$year-$month-01' AND '$year-$month-28' AND status='A'";
      return mysql_num_rows($this->executeQuery($q));	
   }
   
   /**
   * CalcSAMUserRegion()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Apr 07 16:16:46 PDT 2006
   */
   function CalcSAMUserRegion($user_id, $region_id, $sam_type_id, $year, $month)
   {
      if ($month<10)
         $month="0".$month;
      $q = "SELECT SUM(sa.amount) AS amount FROM sam_account AS sa LEFT JOIN user AS u ON u.login=sa.user_id WHERE sa.user_id = '$user_id' AND sa.region_id = '$region_id' AND sa.sam_type_id='$sam_type_id' AND sa.sam_date LIKE '%$year-$month-01%' AND sa.user_id!=0 AND sa.region_id!=0 AND sa.account_id!=0 AND sa.status='A' AND u.status='A' GROUP BY sa.sam_type_id, sa.region_id, sa.user_id";
      $rst = $this->executeQuery($q);
      return ($this->rows?mysql_result($this->executeQuery($q), 0, "amount"):0);
   }

   /**
   * GetSAMUser()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 03 15:54:09 PST 2006
   */
   function GetSAMUser($user_id, $sam_type_id, $year) {
      $q = "SELECT user_id, MONTH(sam_date) AS month, SUM(amount) AS amount FROM sam_user "
      ."WHERE user_id='$user_id' AND sam_type_id='$sam_type_id' AND sam_date BETWEEN '$year-01-01' AND '$year-12-31' AND status='A' "
      ."GROUP BY user_id, month";
     
      return $this->executeQuery($q);
   }

   /**
   * SetSAMUserRegion()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 03 15:19:29 PST 2006
   */
   function SetSAMUserRegion($user_id, $region_id, $sam_type_id, $year, $month, $amount)
   {
      $q = "SELECT sam_user_id FROM sam_user WHERE user_id = '$user_id' AND region_id='$region_id' AND sam_type_id='$sam_type_id' AND sam_date = '$year-$month-01' AND status='A'";
      $this->executeQuery($q);
      if ($this->rows) {
         $q =
         "UPDATE
            sam_user
         SET
            `amount`='$amount',
            `modified_by`='".$this->created_by."',
            `modified_date`=NOW()
         WHERE user_id='$user_id' AND region_id='$region_id' AND sam_type_id='$sam_type_id' AND sam_date='$year-$month-01'";
      }else{
         $q = "INSERT INTO sam_user (`user_id`, `region_id`, `sam_type_id`, `sam_date`, `amount`, `created_by`, `created_date`, `status`) "
         ."VALUES ('$user_id', '$region_id', '$sam_type_id', '$year-$month-01', '$amount', '".$this->created_by."', NOW(), 'A')";
      }
      $this->executeQuery($q);
      return $this->rows;
   }

//   /**
//   * AddSAMUserRegion()
//   *
//   * @param
//   * @param -
//   * @return
//   * @throws
//   * @access
//   * @global
//   * @since  - Mon Mar 06 12:41:52 PST 2006
//   */
//   function AddSAMUserRegion($user_id, $region_id, $sam_type_id, $year, $month, $amount)
//   {
//      $q = "SELECT amount FROM sam_user WHERE user_id = '$user_id' AND region_id='$region_id' AND sam_type_id='$sam_type_id' AND sam_date LIKE '%$year-$month-01%' AND status='A'";
//      $amt = mysql_fetch_assoc($this->executeQuery($q));
//      if ($this->rows) {
//         $amt = $amt["amount"];
//         $q = "UPDATE sam_user SET `amount` = '".($amount+$amt)."', `modified_by`='".$this->created_by."', `modified_date`=NOW() "
//         ." WHERE user_id='$user_id' AND region_id='$region_id' AND sam_type_id='$sam_type_id' AND sam_date='$year-$month-01'";
//      }else{
//         $q = "INSERT INTO sam_user (`user_id`, `region_id`, `sam_type_id`, `sam_date`, `amount`, `created_by`, `created_date`, `status`) "
//         ."VALUES ('$user_id', '$region_id', '$sam_type_id', '$year-$month-01', '$amount', '".$this->created_by."', NOW(), 'A')";
//      }
//      $this->executeQuery($q);
//      return $this->rows;
//   }

   /**
   * GetSAMAccountUserRegion()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 03 16:02:57 PST 2006
   */
   function GetSAMAccountUserRegion($account_id, $user_id, $region_id, $sam_type_id, $year)
   {
      $q = "SELECT account_id, user_id, region_id, MONTH(sam_date) AS month, amount FROM sam_account "
      ."WHERE account_id='$account_id' AND user_id='$user_id' AND region_id='$region_id' AND sam_type_id='$sam_type_id' AND sam_date BETWEEN '$year-01-01' AND '$year-12-31' AND status='A'";
      return $this->executeQuery($q);
   }

   function isSAMAccountUserRegion($account_id, $user_id, $region_id, $sam_type_id, $year, $month)
   {
      $q = "SELECT sam_account_id FROM sam_account WHERE account_id='$account_id' AND user_id='$user_id' AND region_id='$region_id' AND sam_type_id='$sam_type_id' AND sam_date BETWEEN '$year-$month-01' AND '$year-$month-28' AND status='A'";
      return mysql_num_rows($this->executeQuery($q));	
   }
   
   /**
   * SetSAMAccountUserRegion()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 03 17:55:54 PST 2006
   */
   function SetSAMAccountUserRegion($account_id, $user_id, $region_id, $sam_type_id, $year, $month, $amount)
   {
      $q = "SELECT sam_account_id FROM sam_account WHERE account_id = '$account_id' AND user_id = '$user_id' AND region_id='$region_id' AND sam_type_id='$sam_type_id' AND sam_date = '$year-$month-01' AND status='A'";
      $this->executeQuery($q);
      if ($this->rows) {
         $q =
         "UPDATE
            sam_account
         SET
            `amount`='$amount',
            `modified_by`='".$this->created_by."',
            `modified_date`=NOW()
         WHERE account_id='$account_id' AND user_id='$user_id' AND region_id='$region_id' AND sam_type_id='$sam_type_id' AND sam_date='$year-$month-01'";
      }else{
         $q = "INSERT INTO sam_account (`account_id`, `user_id`, `region_id`, `sam_type_id`, `sam_date`, `amount`, `created_by`, `created_date`, `status`) "
         ."VALUES ('$account_id', '$user_id', '$region_id', '$sam_type_id', '$year-$month-01', '$amount', '".$this->created_by."', NOW(), 'A')";
      }
      $this->executeQuery($q);
      return $this->rows;
   }

//   /**
//   * AddSAMAccountUserRegion()
//   *
//   * @param
//   * @param -
//   * @return
//   * @throws
//   * @access
//   * @global
//   * @since  - Mon Mar 06 16:26:42 PST 2006
//   */
//   function AddSAMAccountUserRegion($account_id, $user_id, $region_id, $sam_type_id, $year, $month, $amount)
//   {
//      $q = "SELECT amount FROM sam_account WHERE account_id='$account_id' AND user_id = '$user_id' AND region_id='$region_id' AND sam_type_id='$sam_type_id' AND sam_date = '$year-$month-01' AND status='A'";
//      $amt = mysql_fetch_assoc($this->executeQuery($q));
//      if ($this->rows) {
//         $amt = $amt["amount"];
//         $q = "UPDATE sam_account SET `amount` = '".($amount+$amt)."', `modified_by`='".$this->created_by."', `modified_date`=NOW() "
//         ." WHERE account_id = '$account_id' AND user_id='$user_id' AND region_id='$region_id' AND sam_type_id='$sam_type_id' AND sam_date='$year-$month-01'";
//      }else{
//         $q = "INSERT INTO sam_account (`account_id`, `user_id`, `region_id`, `sam_type_id`, `sam_date`, `amount`, `created_by`, `created_date`, `status`) "
//         ."VALUES ('$account_id', '$user_id', '$region_id', '$sam_type_id', '$year-$month-01', '$amount', '".$this->created_by."', NOW(), 'A')";
//      }
//      $this->executeQuery($q);
//      return $this->rows;
//   }

   /**
   * GetSAMTypeDetails()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 03 18:21:54 PST 2006
   */
   function GetSAMTypeDetails($sam_type_id)
   {
      $q = "SELECT sam_type_id, sam_type_description, created_by, created_date, modified_by, modified_date FROM sam_type WHERE sam_type_id='$sam_type_id' AND status='A'";
      return mysql_fetch_assoc($this->executeQuery($q));
   }
   
   function GetSAMTypes()
   {
   	$q = "SELECT sam_type_id, sam_type_description FROM sam_type WHERE status='A'";
   	return $this->executeQuery($q);
   }

   /**
   * CreateLeaderBoard()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Mar 07 16:35:07 PST 2006
   */
   function CreateLeaderBoard($list)
   {
      $q = "DROP TABLE IF EXISTS `sam_leader_board`";
      $this->executeQuery($q);
      $q =
      "CREATE TEMPORARY TABLE IF NOT EXISTS `sam_leader_board` ("
      ." `user_id` INT(11) NOT NULL DEFAULT '0',"
      ." `user_name` VARCHAR(40),"
      ." `study` DECIMAL(10,3) NOT NULL DEFAULT '0.000',"
      ." `sb` DECIMAL(10,3) NOT NULL DEFAULT '0.000',"
      ." `li` DECIMAL(10,3) NOT NULL DEFAULT '0.000',"
      ." `tr` DECIMAL(10,3) NOT NULL DEFAULT '0.000',"
      ." `ot` DECIMAL(10,3) NOT NULL DEFAULT '0.000',"
      ." PRIMARY KEY (`user_id`))";
      $this->executeQuery($q);
      $this->executeQuery("TRUNCATE TABLE `sam_leader_board`");
      foreach($list AS $item) {
         if ($item["user_id"]!=0) {
            $q = "INSERT INTO `sam_leader_board` (`user_id`, `user_name`, `study`, `sb`, `li`, `tr`, `ot`) "
            ." VALUES ('".$item["user_id"]."', '".$item["user_name"]."', '".$item["study"]."', '".$item["sb"]."', '".$item["li"]."', '".$item["tr"]."', '".$item["ot"]."')";
            $this->executeQuery($q);
         }
      }
   }

   /**
   * GetLeaderBoard()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Mar 07 16:39:24 PST 2006
   */
   function GetLeaderBoard($order, $limit="")
   {
      $q = "SELECT user_id, user_name, study, sb, li, tr, ot, (study+sb+li+tr+ot) AS total "
      ." FROM sam_leader_board $order $limit";
      return $this->executeQuery($q);
   }

   /**
    * Retrieve the result set of Users in the sam_user table for the specified region, period and sam type.
    * 
    * @param int $region_id The ID of the region for which we need to get the list of users for.
    * @param string $start_date The start of the period we're looking to get the list of users for.
    * @param string $end_date The end of the period we're looking to get the list of users for.
    * @param int $sam_type_id The ID of sam type we're looking to get the list of users for.
    */
   function GetSAMUsersByRegion($region_id, $start_date, $end_date, $sam_type_id=SAM_TYPE_QUOTA)
   {
   	$q = "SELECT su.user_id, u.first_name, u.last_name FROM sam_user AS su LEFT JOIN user AS u ON u.login=su.user_id";
   	$q .= " WHERE su.status='A' AND su.sam_type_id='$sam_type_id' AND su.region_id='$region_id' AND su.sam_date BETWEEN '$start_date' AND '$end_date'";
   	$q .= " GROUP BY su.user_id";
   	$q .= " ORDER BY u.first_name, u.last_name";
   	
   	return $this->executeQuery($q);
   }
   
   /**
    * Retrieve the result set of regions in the sam_user table for the specified user, period and sam type.
    * 
    * @param int $user_id The ID of the user for which we need to get the list of regions for.
    * @param string $start_date The start of the period we're looking to get the list of users for.
    * @param string $end_date The end of the period we're looking to get the list of users for.
    */
   function GetSAMRegionByUser($user_id, $start_date, $end_date )
   {
   	$q = "SELECT su.region_id, r.region_description, r.region_code FROM sam_user AS su LEFT JOIN region AS r ON r.region_id=su.region_id";
   	$q .= " WHERE su.status='A' AND su.user_id='$user_id' AND su.sam_date BETWEEN '$start_date' AND '$end_date'";
   	$q .= " GROUP BY su.region_id";
   	$q .= " ORDER BY r.region_description";
   	return $this->executeQuery($q);
   }
   
   /**
    * Retrieve the result set of Users in the sam_user table for the specified period and sam type.
    * 
    * @param string $start_date The start of the period we're looking to get the list of users for.
    * @param string $end_date The end of the period we're looking to get the list of users for.
    * @param int $sam_type_id The ID of sam type we're looking to get the list of users for.
    */
   function GetSAMUsers($start_date, $end_date, $sam_type_id=SAM_TYPE_QUOTA)
   {
      $q = "SELECT su.user_id, u.first_name, u.last_name FROM sam_user AS su LEFT JOIN user AS u ON u.login=su.user_id";
      $q .= " WHERE su.status='A' AND su.sam_type_id='$sam_type_id' AND su.sam_date BETWEEN '$start_date' AND '$end_date'";
      $q .= " GROUP BY su.user_id";
      $q .= " ORDER BY u.first_name, u.last_name";
      
      return $this->executeQuery($q);
   }
   
   /**
    * Return the result set of Accounts in the sam_account table for the specified user, region, period and sam type.
    * 
    * @param int $user_id The ID of the user for which we need to get the list of accounts.
    * @param string $start_date The start of the period we're looking to get the list of users for.
    * @param string $end_date The end of the period we're looking to get the list of users for.
    * @param int $sam_type_id The ID of sam type we're looking to get the list of users for.
    */
   function GetSAMAccountsByUser($user_id, $start_date, $end_date, $sam_type_id=SAM_TYPE_QUOTA)
   {
   	$q = "SELECT sa.account_id, sa.region_id, a.account_name FROM sam_account AS sa LEFT JOIN account AS a ON a.account_id=sa.account_id";
   	$q .= " WHERE sa.user_id='$user_id' AND sa.sam_date BETWEEN '$start_date' AND '$end_date' AND sa.sam_type_id='$sam_type_id' AND sa.status='A'";
   	$q .= " GROUP BY sa.account_id";
   	$q .= " ORDER BY sa.region_id, a.account_name";
   	
   	return $this->executeQuery($q);
   }
}
?>
