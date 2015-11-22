<?php

require_once "dbConnect.class.php";

define('DEFAULT_TZ_ID', 1);
define('SYSTEM_USER', 10367);

class userDB extends dbConnect {

   public  $user_id = 0; //holder for netmr data
   
   public  $login   = 0;

   /**
   * user()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function userDB()
   {
      parent::dbConnect();
   }

   /**
   * getName()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getName($login)
   {
      if ($login == '') {
        return false;
      }

      $qry = "SELECT CONCAT(u.first_name,' ',u.last_name) AS name FROM user AS u WHERE u.login = ".$login;
      $rs = $this->executeQuery($qry);
      return mysql_result($rs,0,'name');
   }

   /**
   * GetUserList()
   *
   * @param
   * @param - status flag
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 06 14:16:28 PDT 2005
   */
   function GetUserList($status)
   {
   	$qry  = "SELECT CONCAT(first_name,' ',last_name) AS name, login ";
   	$qry .= "FROM user ";
   	$qry .= "WHERE status = '".$status."' ";
   	$qry .= "ORDER BY first_name ";
   	return $this->executeQuery($qry);
   }	

   /**
   * GetDepartmentList()
   *
   * @param
   * @param - status flag
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 06 14:16:28 PDT 2005
   */
   function GetDepartmentList()
   {
   	$qry  = "SELECT department_id, department ";
   	$qry .= "FROM department ";
   	$qry .= "ORDER BY department_id ";
   	return $this->executeQuery($qry);
   }

   /**
   * getFunctionalGroup()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getFunctionalGroups()
   {
      $qry  = "SELECT f.functional_group_id, f.functional_group_abbrev, f.functional_group_description ";
      $qry .= "FROM functional_group AS f ";
      $qry .= "WHERE f.status = 'A' ORDER BY f.functional_group_description";

      $rs = $this->executeQuery($qry);
      while ($r = mysql_fetch_assoc($rs)) {
         $data[$r['functional_group_id']] = $r['functional_group_description'];
      }
      return $data;
   }

   /**
   * GetUserFunctionalGroup()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Aug 16 21:47:36 PDT 2005
   */
   function GetUserFunctionalGroup($login)
   {
      $q = "SELECT functional_group_id FROM user_functional_group WHERE login = " . $login . " "
         . "LIMIT 1";
      return mysql_fetch_assoc($this->executeQuery($q));
   }

   /**
   * GetEmailByLogin()
   *
   * @param
   * @param - login
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jun 07 08:53:38 PDT 2005
   */
   function GetEmailByLogin($login)
   {
   	$qry = "SELECT email_address FROM user WHERE login = ".$login;
   	$r = mysql_fetch_assoc($this->executeQuery($qry));
   	return ($r) ? $r['email_address'] : false;
   }

   /**
   * GetSmsByLogin()
   *
   * @param
   * @param - login
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jun 07 08:56:39 PDT 2005
   */
   function GetSmsByLogin($login)
   {
   	$qry = "SELECT sms_address FROM user WHERE login = ".$login;
   	$r = mysql_fetch_assoc($this->executeQuery($qry));
   	return ($r) ? $r['sms_address'] : false;
   }

   /**
   * GetFunctionalGroupOwner()
   *
   * @param
   * @param - functional_group_id
   * @return - int functional group owner
   * @throws
   * @access
   * @global
   * @since  - Tue Jun 07 16:42:47 PDT 2005
   */
   function GetFunctionalGroupOwner($functional_group_id)
   {
   	$qry  = "SELECT login FROM user_functional_group ";
   	$qry .= "WHERE functional_group_id = ".$functional_group_id." AND functional_group_owner = 'Y'";
   	$r = mysql_fetch_assoc($this->executeQuery($qry));
   	return ($r) ? $r['login'] : false;
   }



   /**
   * getFunctionalMembers()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetFunctionalGroupMembers($o)
   {
      $qry  = "SELECT g.login, CONCAT(u.first_name,' ',u.last_name) AS name, u.email_address, u.first_name, u.last_name ";
      $qry .= "FROM user_functional_group AS g ";
      $qry .= "LEFT OUTER JOIN user AS u ON u.login = g.login ";
      $qry .= "WHERE g.status = 'A' AND g.functional_group_id = ".$o['group_id'];
		$qry .= " ORDER BY g.functional_group_owner DESC, name ";

      return $this->executeQuery($qry);
   }

   /**
   * getTimeZoneList()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getTimeZoneList()
   {
      $qry  = "SELECT time_zone_id,city_country_name ";
      $qry .= "FROM time_zone ";
      $qry .= "WHERE status = 'A'";
      $qry .= "ORDER BY sort_order ";

      $rs = $this->executeQuery($qry);

      while($r = mysql_fetch_assoc($rs)) {
         $data[$r['time_zone_id']] = $r['city_country_name'];
      }
      return $data;
   }

   /**
   * getTimeZone()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getTimeZone($o)
   {
      if(!isset($o['time_zone_id']) || $o['time_zone_id'] == '') {
         return '+00.00';
      }

      $qry = "SELECT offset FROM time_zone WHERE time_zone_id = ".$o['time_zone_id'];
      return mysql_result($this->executeQuery($qry),0,'offset');
   }

   /**
   * getReportee()
   * we need to add self to reportees - DONE
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getReportee($o)
   {
      $qry  = "SELECT '".$o['login']."' AS reporting_login ";
      $qry .= "UNION ALL ";
      $qry .= "SELECT reporting_login FROM reporting_hierarchy  WHERE report_to_login = ".$o['login'];
      return $this->executeQuery($qry);
   }

   /**
   * getDefaultTimeZone()
   * we need to add self to reportees
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getDefaultTimeZone($o)
   {
      $qry  = "SELECT tz.offset FROM time_zone AS tz ";
      $qry .= "LEFT OUTER JOIN user AS u ON u.time_zone_id = tz.time_zone_id ";
      $qry .= "WHERE u.login = ".$o['login'];
      return mysql_result($this->executeQuery($qry),0,'offset');;
   }

	/**
   * GetSecurityGroupUsers()
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function GetSecurityGroupUsers($security_group)
	{
		$qry  = "SELECT usg.login, CONCAT(u.first_name,' ',u.last_name) AS name ";
      $qry .= "FROM security s ";
		$qry .= "LEFT OUTER JOIN security_group_security sgs ON ( sgs.security_id = s.security_id ) ";
		$qry .= "LEFT OUTER JOIN user_security_group usg ON ( usg.security_group_id = sgs.security_group_id ) ";
		$qry .= "LEFT OUTER JOIN user AS u ON ( u.login = usg.login ) ";
		$qry .= "WHERE s.security_type = '".$security_group."' ";
		$qry .= "ORDER BY name ";
		return $this->executeQuery($qry);
	}

	/**
   * GetSecurityGroupUsers()
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function GetRoles()
	{
		$qry  = "SELECT role_id,role_description ";
		$qry .= "FROM role ";
		$qry .= "WHERE status = 'A'";
		return $this->executeQuery($qry);
	}

	/**
   * GetUserDetail()
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function GetUserDetail($o)
	{
	  $qry  = "SELECT first_name,last_name,login,email_address ";
	  $qry .= "FROM user ";
	  $qry .= "WHERE login = ".$o['login']." AND STATUS = 'A'";
	  return $this->executeQuery($qry);
	}

	/**
	* GetUserTipic()
	*
	* return the users tipic address if found or false
	* @param
	* @param - login_id
	* @return
	* @throws
	* @access
	* @global
	* @since  - Sat Jun 11 19:21:15 PDT 2005
	*/
	function GetUserTipic($login)
	{
	   $qry  = "SELECT ua.user_value ";
	   $qry .= "FROM user_attr AS ua ";
	   $qry .= "WHERE ua.login = ".$login." AND ua.user_attr = 'TIPIC'";
	   $r = mysql_fetch_assoc($this->executeQuery($qry));
	   return ($r) ? $r['user_value'] : false;
	}

	/**
	* GetTimeZoneByLogin()
	*
	* @param
	* @param - integer login
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Jun 16 22:07:03 PDT 2005
	*/
	function GetTimeZoneByLogin($login)
	{
	   $qry = "SELECT REPLACE(tz.offset,'.',':') AS time_zone "
	        . "FROM time_zone AS tz "
	        . "LEFT OUTER JOIN user AS u ON u.time_zone_id = tz.time_zone_id "
	        . "WHERE u.login = ".$login;

	   $r = mysql_fetch_assoc($this->executeQuery($qry));

	   return ($r) ? $r['time_zone'] : false;
	}

	/**
	* GetReporteeByLogin()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Jun 21 17:31:06 PDT 2005
	*/
	function GetReporteeByLogin($login)
	{
	   $qry = "SELECT reporting_login, reporting_type_id FROM reporting_hierarchy  WHERE status = 'A' AND report_to_login = ".$login;
	   return $this->executeQuery($qry);
	}

	/**
	* GetUserByTipic()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jun 24 09:33:21 PDT 2005
	*/
	function GetLoginByTipic($jid)
	{
	   $qry = "SELECT login "
	        . "FROM user_attr "
	        . "WHERE user_attr = 'TIPIC' AND user_value = '".$jid."'";
	   return mysql_fetch_assoc($this->executeQuery($qry));
	}

	/**
	* GetLastLoginDate()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jun 24 09:47:42 PDT 2005
	*/
	function GetLastLoginDate($login)
	{
	   $qry = "SELECT last_login_date FROM user WHERE login = ".$login;
	   $r = mysql_fetch_assoc($this->executeQuery($qry));
	   return $r['last_login_date'];
	}

	/**
	* GetLastLoginDays()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jun 24 23:25:26 PDT 2005
	*/
	function GetDaysSinceLastLogin($login)
	{
	   $qry = "SELECT DATEDIFF(NOW(), last_login) AS days_since_last_login "
	        . "FROM user "
	        . "WHERE login = ".$login." AND last_login != '0000-00-00 00:00:00' ";
	   $r = mysql_fetch_assoc($this->executeQuery($qry));
	   return ($r) ? $r['days_since_last_login'] : false;
	}
	
	/**
	* SetUser()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Nov 03 11:35:02 PST 2005
	*/
	function SetUser($login, $first_name, $last_name, $email_address, $password, $time_zone_id = DEFAULT_TZ_ID, $user_type_id = 1)
	{
	   $q = "INSERT INTO user (login, first_name, last_name, email_address, password, time_zone_id, user_type_id, created_by, created_date, status) "
	      . "VALUES ('". $login ."', '". mysql_real_escape_string($first_name) ."', '". mysql_real_escape_string($last_name) ."', "
	      . "        '". mysql_real_escape_string($email_address) ."', '". $password ."', '". $time_zone_id ."', ". $user_type_id .",". SYSTEM_USER .", NOW(), 'A') "; 
	   return $this->executeQuery($q);
	}
	
	/**
	* UpdateUser()
	*
	* @param
	* @param 
	* @return
	* @since  - 17:58:40
	*/
	function UpdateUser($login, $first_name, $last_name, $email_address, $password, $time_zone_id = DEFAULT_TZ_ID, $user_type_id = 1)
	{
		$q = "UPDATE user SET first_name = '". mysql_real_escape_string($first_name) . "', last_name = '". mysql_real_escape_string($last_name) ."', "
		   . "       email_address = '". mysql_real_escape_string($email_address) ."', password = '". $password ."', "
		   . "       time_zone_id = ". $time_zone_id .", user_type_id = ". $user_type_id .", modified_by = ". $this->created_by .", modified_date = NOW() "
		   . "WHERE login = ". $login;
		return $this->executeQuery($q);
	}
	
	/**
	* GetUsersBySecurity()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Dec 22 13:47:49 PST 2005
	*/
	function GetUsersBySecurity($security_type)
	{
	   $q = "SELECT distinct CONCAT(u.first_name,' ',u.last_name) AS name, u.login "
         . "FROM user AS u "
         . "left outer join user_security_group usg on (usg.login = u.login) "
         . "left outer join security_group_security sgs on (sgs.security_group_id = usg.security_group_id) "
         . "left outer join security s on (sgs.security_id = s.security_id) "
         . "where s.security_type = '". $security_type ."' ";
      return $this->executeQuery($q);
	}
	
	/**
	* isValidUser()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Sat Mar 25 07:43:16 PST 2006
	*/
	function isValidUser($login)
	{
		$q = "SELECT status FROM user WHERE login = '". $login ."'";
		$r = $this->FetchAssoc($this->executeQuery($q));
		return ($r && $r['status'] == 'A') ? true : false;
	}
	
	/**
	* isValidLogin()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Mar 30 01:10:18 PST 2006
	*/
	function isValidLogin($login, $password)
	{
		$q = "SELECT concat(first_name, ' ', last_name) as name, login as admin_id, time_zone_id, user_id, user_type_id "
		   . "FROM user "
		   . "WHERE (login = '" . mysql_real_escape_string($login) . "' OR email_address = '" . mysql_real_escape_string($login) . "') "
			. "  AND password = OLD_PASSWORD('". $password. "') AND status = 'A' ";
		echo $q;
		return $this->FetchAssoc($this->executeQuery($q));
	}
	
	/**
	* SetLastLogin()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Mar 30 01:26:59 PST 2006
	*/
	function SetLastLogin($login)
	{
		$q = "UPDATE user SET last_login = NOW() WHERE login = '". $login ."'";
		return $this->executeQuery($q);
	}
	
	/**
	* GetUsersByRoleLocation()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Apr 10 11:08:19 PDT 2006
	*/
	function GetUsersByRoleLocation($role_id)
	{
		$q = "SELECT COUNT(u.login) AS user_count, u.location_id "
		   . "FROM user AS u "
		   . "LEFT OUTER JOIN user_role AS ur ON ur.login = u.login "
		   . "WHERE u.status = 'A' AND ur.status = 'A' AND ur.role_id = ". $role_id ." "
		   . "GROUP BY u.location_id ";
		return $this->executeQuery($q);
	}
	
	/**
	* SetUserAttr()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Jun 19 20:28:44 PDT 2006
	*/
	function SetUserAttr($login, $user_attr, $user_value)
	{
		$q = "INSERT INTO user_attr (login, user_attr, user_value, created_by, created_date, status) "
		   . "VALUES (". $login .", '". $user_attr ."', '". $user_value ."', ". $this->created_by .", NOW(), 'A') ";
		return $this->executeQuery($q);
	}
	
	/**
	* GetUserAttr()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Jun 19 20:33:08 PDT 2006
	*/
	function GetUserAttr($login, $user_attr)
	{
		$q = "SELECT user_value FROM user_attr WHERE login = ". $login ." AND user_attr = '". $user_attr ."' AND status = 'A' ";
		$r = $this->FetchAssoc($this->executeQuery($q));
		return ($r) ? $r['user_value'] : false;
	}
	
	/**
	* isValidUsername()
	*
	* @param
	* @param 
	* @return
	* @since  - 19:55:38
	*/
	function isValidUsername($email_address)
	{
		$q = "SELECT login FROM user WHERE email_address = '". mysql_real_escape_string($email_address) ."' AND status = 'A' ";
		$r = $this->FetchAssoc($this->executeQuery($q));
		return ($r) ? true : false;
	}
	
	/**
	* UpdateUserAttr()
	*
	* @param
	* @param 
	* @return
	* @since  - 17:56:11
	*/
	function UpdateUserAttr($login, $user_attr, $user_value)
	{
		$q = "UPDATE user_attr SET user_value = '". mysql_real_escape_string($user_value) ."', modified_by = ". $this->created_by .", "
		   . "       modified_date = NOW() "
		   . "WHERE login = ". $login ." AND user_attr = '". $user_attr ."' ";
		return $this->executeQuery($q);
	}
	
	/**
	* isUserMemberOfSecGroup()
	*
	* @param
	* @param 
	* @return
	* @since  - 19:03:05
	*/
	function isUserMemberOfSecGroup($security_group_id, $login)
	{
		$q	 = "SELECT security_group_id FROM user_security_group WHERE status = 'A' AND login = ". $login ." AND security_group_id = ". $security_group_id;
		$r = $this->FetchAssoc($this->executeQuery($q));
		return ($r) ? true : false;
	}
	
	/**
	* isUserAttrSet()
	*
	* @param
	* @param 
	* @return
	* @since  - 16:52:50
	*/
	function isUserAttrSet($login, $user_attr)
	{
		$q = "SELECT user_attr_id FROM user_attr WHERE login = ". $login ." AND user_attr = '". $user_attr ."' ";
		$r = $this->FetchAssoc($this->executeQuery($q));
		return ($r) ? true : false;
	}
	
	/**
	* GetLoginByEmail()
	*
	* @param
	* @param 
	* @return
	* @since  - 17:09:46
	*/
	function GetLoginByEmail($email_address)
	{
		$q = "SELECT login FROM user WHERE email_address = '". mysql_real_escape_string($email_address) ."' AND status = 'A' ";
		$r = $this->FetchAssoc($this->executeQuery($q));
		return ($r) ? $r['login'] : 0;
	}
	
	/**
	* GetLoginByAttr()
	*
	* @param
	* @param 
	* @return
	* @since  - 09:24:43
	*/
	function GetLoginByAttr($user_attr, $user_value)
	{
		$q = "SELECT login FROM user_attr WHERE status = 'A' AND user_attr = '". $user_attr ."' AND user_value = '". mysql_real_escape_string($user_value) ."' ";
		$r = $this->FetchAssoc($this->executeQuery($q));
		return ($r) ? $r['login'] : 0;
	}
	
	/**
	* SetLogin()
	*
	* @param
	* @param 
	* @return
	* @since  - 18:40:03
	*/
	function SetLogin($login)
	{
		$this->login = $login;
	}
	
	/**
	* SetStatus()
	*
	* @param
	* @param 
	* @return
	* @since  - 18:40:53
	*/
	function SetStatus($status)
	{
		if ($this->login == 0) throw new Exception("Login Is Not Set", 101);
		
		$q = "UPDATE user SET status = '". $status ."' WHERE login = ". $this->login;
		return $this->executeQuery($q);
	}
	
	/**
	* GetUserDetailsV2()
	*
	* @param
	* @param 
	* @return
	* @since  - 18:31:10
	*/
	function GetUserDetailsV2($login, $status = '')
	{
		$q = "SELECT concat(first_name, ' ', last_name) as name, login as admin_id, time_zone_id, user_id, user_type_id "
		   . "FROM user "
		   . "WHERE login = '" . mysql_real_escape_string($login) ."' AND status = 'A' ";
		return $this->FetchAssoc($this->executeQuery($q));
	}
}

?>
