<?php

/* userRights.php is a class to maniplulate user functions
 * 2004-11-28 NH */

define('GROUP_AM',6);
define('GROUP_AE',7);
define('GROUP_ACCT',5);
define('GROUP_ADMIN',4);

class userRights extends dbConnect
{
	
	//default user name
	var $_username = '';

	//default user level
	var $_userLevel = 0;

	function userRights($username)
	{
		$this->dbConnect();
		$this->_username = $username;
		$this->loadRights();
	}

	function userSecCheck($key)
	{
		$qSecCheck = "SELECT s.security_type
          from security s
          left outer join security_group_security sgs on (sgs.security_id = s.security_id)
          left outer join user_security_group usg on (usg.security_group_id = sgs.security_group_id)
          where s.security_type = '" . $key . "' and usg.login = ".$this->_username;
 		$rsSecCheck = mysql_query($qSecCheck,$this->_link) or die(mysql_error());
		if (mysql_num_rows($rsSecCheck) == 0) {
			return false;
		} else {
			return true;
		}
	}

	function isAM() 
	{
		$qCheck = "SELECT s.security_type
          from security s
          left outer join security_group_security sgs on (sgs.security_id = s.security_id)
          left outer join user_security_group usg on (usg.security_group_id = sgs.security_group_id)
          where (s.security_type = 'IS_AM' or s.security_type='CADMIN') and usg.login = ".$this->_username;
		$rsCheck = mysql_query($qCheck,$this->_link);
    if (mysql_num_rows($rsCheck) > 0) { //admins have access to everything
			return true;
		} else {
			return false;
		}
	
	}
	
	function isAE() 
	{
		$qCheck = "SELECT s.security_type
          from security s
          left outer join security_group_security sgs on (sgs.security_id = s.security_id)
          left outer join user_security_group usg on (usg.security_group_id = sgs.security_group_id)
          where (s.security_type = 'IS_AE' or s.security_type = 'CADMIN') and usg.login = ".$this->_username;
		$rsCheck = mysql_query($qCheck,$this->_link);
    if (mysql_num_rows($rsCheck) > 0) { //admins have access to everything
			return true;
		} else {
			return false;
		}
	}
	
	function isACCT()
	{
		$qCheck = "SELECT s.security_type
          from security s
          left outer join security_group_security sgs on (sgs.security_id = s.security_id)
          left outer join user_security_group usg on (usg.security_group_id = sgs.security_group_id)
          where( s.security_type = 'IS_ACCT' or s.security_type='CADMIN') and usg.login = ".$this->_username;
		$rsCheck = mysql_query($qCheck,$this->_link);
    if (mysql_num_rows($rsCheck) > 0) { //admins have access to everything
			return true;
		} else {
			return false;
		}
	}
	
	function isADMIN()
	{
		$qCheck = "SELECT s.security_type
          from security s
          left outer join security_group_security sgs on (sgs.security_id = s.security_id)
          left outer join user_security_group usg on (usg.security_group_id = sgs.security_group_id)
          where s.security_type = 'CADMIN' and usg.login = ".$this->_username;
		$rsCheck = mysql_query($qCheck,$this->_link);
		if (mysql_num_rows($rsCheck) > 0) { //admins have access to everything
			return true;
		} else {
			return false;
		}
	}
	
	function isTraining()
	{
		$qCheck = "SELECT s.security_type
          from security s
          left outer join security_group_security sgs on (sgs.security_id = s.security_id)
          left outer join user_security_group usg on (usg.security_group_id = sgs.security_group_id)
          where s.security_type = 'IS_TRAINING' and usg.login = ".$this->_username;
		$rsCheck = mysql_query($qCheck,$this->_link);
    if (mysql_num_rows($rsCheck) > 0) { //admins have access to everything
			return true;
		} else {
			return false;
		}

	}


	function getUserDetails()
	{
		$qDetails = "SELECT * FROM `users` WHERE login = ".$this->_username;
		return mysql_fetch_array($this->executeQuery($qDetails,MYSQL_ASSOC));
	}

	/* @desc - load all the security settings to the current session
      @input - null
      @output - null
	 */
	function loadRights()
	{
		$qry = "SELECT s.security_type
          from security s
          left outer join security_group_security sgs on (sgs.security_id = s.security_id)
          left outer join user_security_group usg on (usg.security_group_id = sgs.security_group_id)
          where usg.login = ".$this->_username;
		$rs  = $this->executeQuery($qry);
		while ($r = mysql_fetch_array($rs,MYSQL_ASSOC)) {
			$_SESSION[$r['security_type']] = true;
		}
	}
	
	/**
	* GetUserRights()
	*
	* @param
	* @param - 
	* @return - associative array  
	* @throws
	* @access
	* @global
	* @since  - Thu Jun 16 17:46:02 PDT 2005
	*/
	function GetUserRights()
	{
	   $data = array();
	   
	   $qry = "SELECT s.security_type "
           . "from security s "
           . "left outer join security_group_security sgs on (sgs.security_id = s.security_id) "
           . "left outer join user_security_group usg on (usg.security_group_id = sgs.security_group_id) "
           . "where usg.login = ".$this->_username;
           
      $rs = $this->executeQuery($qry);
      
      while ($r = mysql_fetch_assoc($rs)) {
         $data[$r['security_type']] = 1;
      }
      
      return $data;
	}

}	
