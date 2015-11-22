<?php

//incase if we called this by it self
require_once($cfg['base_dir']."/class/dbConnect.php");

class messageDB extends dbConnect  {

   //message user type 
   private $__message_type_user_id = 0;
   
   //message_type identifier
   private $__message_type_id      = 0;
   
	/**
	* __construct()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Feb 02 17:31:05 PST 2006
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
	* @since  - Thu Feb 02 17:31:22 PST 2006
	*/
	function __deconstruct()
	{
	   //TODO add code
	   
	}
	
	/**
	* SetMessage()
	*
	* @param
	* @param - Creates a new message in the que 
	* @return - message id
	* @throws
	* @access
	* @global
	* @since  - Sat Jun 11 17:26:16 PDT 2005
	*/
	
	function SetMessage($message_type_id, $message, $subject)
	{
	   $qry  = "INSERT INTO message (message_type_id, message_subject, message_text, created_by, created_date, status) ";
	   $qry .= "VALUES (". $message_type_id . ", '".mysql_escape_string($subject)."','".mysql_escape_string($message)."',". $this->created_by .",NOW(), 'A')";
	   $rs = $this->executeQuery($qry);
	   return $this->lastID;
	}
	
	/**
	* SetRecepient()
	*
	* @param
	* @param - message_id, recepient, flags
	* @return
	* @throws
	* @access
	* @global
	* @since  - Sat Jun 11 17:30:10 PDT 2005
	*/
	
	function SetRecepient($message_id, $recepient, $email = 0, $mobile = 0, $jabber = 0)
	{
		$qry  = "INSERT INTO message_user ( message_id, login, email, mobile, jabber, created_by, created_date ) ";
		$qry .= "VALUES (".$message_id.",".$recepient.",".$email.",".$mobile.",".$jabber.",". $this->created_by .",NOW()) ";
		$rs = $this->executeQuery($qry);
		return $this->lastID;
	}
	
	/**
	* GetPendingJabber()
	*
	* @param
	* @param - 
	* @return - list of pending jabber messages and their users OR false if no messages
	* @throws
	* @access
	* @global
	* @since  - Sat Jun 11 17:38:37 PDT 2005
	*/
	function GetPendingJabber()
	{
	   //TODO add code
	   $qry  = "SELECT m.message_id, m.message_subject, m.message_text, mu.message_user_id, mu.login ";
	   $qry .= "FROM message AS m ";
	   $qry .= "LEFT OUTER JOIN message_user AS mu ON m.message_id = mu.message_id ";
	   $qry .= "WHERE mu.jabber = 1 AND mu.jabber_sent_date IS NULL ";
	   return $this->executeQuery($qry);
	}
	
	/**
	* GetPendingEmail()
	*
	* COM protocol v1 que
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Feb 09 20:58:20 PST 2006
	*/
	function GetPendingEmail($server_id)
	{
		$q = "SELECT m.message_id, m.message_subject, m.message_text, mu.message_user_id, mu.login "
		   . "FROM message_user AS mu "
		   . "LEFT OUTER JOIN message AS m ON m.message_id = mu.message_id "
		   . "LEFT OUTER JOIN message_user_message_delivery_type AS mumdt ON mumdt.message_user_id = mu.message_user_id "
		   . "WHERE mu.email = 1 AND mu.email_sent_date IS NULL and mumdt.message_user_message_delivery_type_id IS NULL AND m.message_id MOD ". GLOBAL_COM_CLIENTS . " = ". $server_id;
		return $this->executeQuery($q);
	}
	
	/**
	* GetPendingMessages()
	*
	* COM protocol v2 que
	* @param
	* @param 
	* @return
	* @since  - 14:00:03
	*/
	function GetPendingMessages($message_delivery_type_id, $server_id)
	{
		$q = "SELECT mu.message_user_id, mu.login, mu.message_id, mumdt.message_user_message_delivery_type_id, m.message_subject "
		   . "FROM message_user AS mu "
		   . "LEFT OUTER JOIN message_user_message_delivery_type AS mumdt ON mumdt.message_user_id = mu.message_user_id "
		   . "LEFT OUTER JOIN message AS m ON m.message_id = mu.message_id "
		   . "WHERE mu.email = 1 AND mumdt.sent_date IS NULL AND mumdt.message_delivery_type_id = " . $message_delivery_type_id. " AND m.message_id MOD ". GLOBAL_COM_CLIENTS . " = ". $server_id;
		return $this->executeQuery($q);
	}
	
	/**
	* SetJabberSentDate()
	*
	* @param
	* @param - message user id
	* @return
	* @throws
	* @access
	* @global
	* @since  - Sat Jun 11 17:40:56 PDT 2005
	*/
	function SetJabberSentDate($message_user_id)
	{
	   //TODO add code
	   $qry  = "UPDATE message_user SET jabber_sent_date = NOW() WHERE message_user_id = ".$message_user_id;
	   return $this->executeQuery($qry);
	}
	
	/**
	* SetEmailSentDate()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Feb 09 21:15:00 PST 2006
	*/
	function SetEmailSentDate($message_user_id)
	{
		$q = "UPDATE message_user SET	email_sent_date = NOW() WHERE message_user_id = ". $message_user_id;
		return $this->executeQuery($q);
	}
	
	/**
	* HasPendingJabberMessages()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Sat Jun 11 19:05:31 PDT 2005
	*/
	function HasPendingJabberMessages()
	{
	   //TODO add code
	   $qry  = "SELECT mu.message_user_id FROM message_user AS mu ";
	   $qry .= "WHERE mu.jabber_sent_date IS NULL";
	   $rs = $this->executeQuery($qry);
	   return ($this->rows == 0) ? false : true;
	}
	
	/**
	* GetUsersByMessageId()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Feb 01 14:21:36 PST 2006
	*/
	function GetUsersByMessageId($message_type_id)
	{
	   $q = "SELECT mtu.message_type_user_id, mtu.user_id "
	      . "FROM message_type_user AS mtu "
	     // . "LEFT OUTER JOIN message_type_user_message_delivery_type AS mtumdt ON mtumdt.message_type_user_id = mtu.message_type_user_id "
	      . "WHERE mtu.status = 'A' AND mtu.message_type_id = ". $message_type_id;
	   return $this->executeQuery($q);
	}
	
	/**
	* GetRuleByMessageUserId()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Feb 01 15:45:25 PST 2006
	*/
	function GetRuleByMessageUserId($message_type_user_id)
	{
	   $q = "SELECT rule_text "
	      . "FROM message_type_rule "
	      . "WHERE status = 'A' AND message_type_user_id = ". $message_type_user_id;
	   return $this->executeQuery($q);
	}
	
	/**
	* SetRule()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Feb 09 17:38:32 PST 2006
	*/
	function SetRule($rule_text)
	{
	   $q = "INSERT INTO message_type_rule (message_type_user_id, rule_text, created_by, created_date, status) "
	      . "VALUES (". $this->__message_type_user_id .", '". $rule_text ."', ". $this->created_by .", NOW(), 'A') ";
	   return $this->executeQuery($q);
	}
	
	/**
	* GetMessageTypeList()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Feb 02 17:00:36 PST 2006
	*/
	function GetMessageTypeList($module_id)
	{
	   $q = "SELECT m.message_type_id, m.message_type_description "
	      . "FROM message_type AS m "
	      . "WHERE m.status = 'A' AND m.module_id = ". $module_id;
	   return $this->executeQuery($q);
	}
	
	/**
	* GetModuleListByMessage()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Mar 23 12:37:31 PST 2006
	*/
	function GetModuleListByMessage($product_id)
	{
		$q = "SELECT m.module_id, m.module_description "
	      . "FROM module AS m "
	      . "LEFT OUTER JOIN message_type AS mt ON mt.module_id = m.module_id "
	      . "WHERE m.status = 'A' AND m.product_id = ". $product_id . " AND mt.message_type_id IS NOT NULL ";
	   return $this->executeQuery($q);
	}
	
	/**
	* SetMessageTypeUser()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Feb 02 17:12:22 PST 2006
	*/
	function SetMessageTypeUser($message_type_id, $message_type_user_description, $user_id)
	{
	   $q = "INSERT INTO message_type_user (message_type_id, message_type_user_description, user_id, created_by, created_date, status ) "
	      . "VALUES (". $message_type_id .", '". mysql_real_escape_string($message_type_user_description) ."', ". $user_id .", ". $this->created_by .", NOW(), 'A') ";
	   return $this->executeQuery($q);
	}
	
	/**
	* SetMessageTypeUserId()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Feb 02 17:31:55 PST 2006
	*/
	function SetMessageTypeUserId($message_type_user_id)
	{
	   $this->__message_type_user_id = $message_type_user_id;
	}
	
	/**
	* GetMessageTypeUserDetail()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Feb 02 17:32:24 PST 2006
	*/
	function GetMessageTypeUserDetail()
	{
	   $q = "SELECT mtu.message_type_user_id, mtu.message_type_id, mtu.message_type_user_description, mtu.user_id, "
	      . "       mt.message_type_description "
	      . "FROM message_type_user AS mtu "
	      . "LEFT OUTER JOIN message_type AS mt ON mt.message_type_id = mtu.message_type_id "
	      . "WHERE mtu.message_type_user_id = ". $this->__message_type_user_id;
	   return mysql_fetch_assoc($this->executeQuery($q));
	}
	
	/**
	* SetMessageTypeId()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Feb 02 17:35:52 PST 2006
	*/
	function SetMessageTypeId($message_type_id)
	{
	   $this->__message_type_id = $message_type_id;
	}
	
	/**
	* GetRuleDef()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Feb 02 17:36:27 PST 2006
	*/
	function GetRuleDef()
	{
	   $q = "SELECT mtrd.message_type_rule_def_id, mtrd.attr_description  "
	      . "FROM message_type_rule_def AS mtrd "
	      . "WHERE status = 'A' AND message_type_id = ". $this->__message_type_id;
	   return $this->executeQuery($q);
	}
	
	/**
	* GetRuleDefDetail()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Feb 03 13:58:27 PST 2006
	*/
	function GetRuleDefDetail($message_type_rule_def_id)
	{
	   $q = "SELECT mtrd.attr_name, mtrd.attr_description, mtrd.attr_type, mtrd.module_code, mtrd.user_filter "
	      . "FROM message_type_rule_def AS mtrd "
	      . "WHERE mtrd.status = 'A' AND mtrd.message_type_rule_def_id = ". $message_type_rule_def_id;
	   return mysql_fetch_assoc($this->executeQuery($q));
	}
	
	/**
	* GetUserMessageList()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Feb 09 21:23:24 PST 2006
	*/
	function GetUserMessageList()
	{
		$q = "SELECT mtu.message_type_user_id, mtu.message_type_id, mtu.message_type_user_description, mt.message_type_description, "
		   . "       modu.module_description "
		   . "FROM message_type_user AS mtu "
		   . "LEFT OUTER JOIN message_type AS mt ON mt.message_type_id = mtu.message_type_id "
		   . "LEFT OUTER JOIN module AS modu ON modu.module_id = mt.module_id "
		   . "WHERE mtu.user_id = ". $this->created_by;
		return $this->executeQuery($q);
	}
	
	/**
	* GetMessageList()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Mar 14 20:45:27 PST 2006
	*/
	function GetMessageList($filter, $page = ' ', $sort = 'm.created_date DESC')
	{
		$q = "SELECT m.message_id, m.message_subject, SUBSTRING(m.message_text,0,100) AS message_text, m.created_date "
		   . "FROM message_user AS mu "
		   . "LEFT OUTER JOIN message AS m ON m.message_id = mu.message_id "
		   . "WHERE mu.status = 'A' "
		   . $filter . " "
		   . "ORDER BY ". $sort ." "
		   . $page;
		return $this->executeQuery($q);
		
	}
	
	/**
	* GetMessagesByUser()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Feb 24 08:25:25 PST 2006
	*/
	function GetMessagesByUser($filter, $page, $sort = ' m.created_date DESC ')
	{
		$q = "SELECT m.message_id, m.message_subject, m.message_text "
		   . "FROM message AS m "
		   . "LEFT OUTER JOIN message_user AS mu ON mu.message_id = m.message_id "
		   . "WHERE m.status = 'A' ". $filter ." "
		   . "ORDER BY ". $sort ." "
		   . $page; 
		return $this->executeQuery($q);
	}
	
	/**
	* GetMessageDetail()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Feb 24 09:13:40 PST 2006
	*/
	function GetMessageDetail($message_id)
	{
		$q = "SELECT m.message_id, m.message_subject, m.message_text, mu.email_sent_date "
		   . "FROM message AS m "
		   . "LEFT OUTER JOIN message_user AS mu ON mu.message_id = m.message_id "
		   . "WHERE m.message_id = ". $message_id;
		return $this->FetchAssoc($this->executeQuery($q));
	}
	
	/**
	* GetInBoundMessages()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Feb 27 17:12:16 PST 2006
	*/
	function GetInBoundMessages()
	{
		$q = "SELECT mi.message_inbound_id, mi.message_text, mi.created_date "
		   . "FROM message_inbound AS mi "
		   . "WHERE mi.status = 'A' ";
		return $this->executeQuery($q);		
	}
	
	/**
	* UpdateInboundMessageStatus()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Mar 21 08:20:38 PST 2006
	*/
	function UpdateInboundMessageStatus($message_inbound_id, $status = 'A')
	{
		$q = "UPDATE message_inbound SET status = '". $status ."', modified_by = ". $this->created_by .", modified_date = NOW() "
		   . "WHERE message_inbound_id = ". $message_inbound_id;
		return $this->executeQuery($q);
	}
	
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
   * GetMessageTypeDetailByTypeUserId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Mar 25 07:21:40 PST 2006
   */
   function GetMessageTypeDetailByTypeUserId($message_type_user_id)
   {
   	$q = "SELECT mt.message_type_description, modl.module_description "
   	   . "FROM message_type_user AS mtu "
   	   . "LEFT OUTER JOIN message_type AS mt ON mt.message_type_id = mtu.message_type_id "
   	   . "LEFT OUTER JOIN module AS modl ON modl.module_id = mt.module_id "
   	   . "WHERE mtu.status = 'A' AND mtu.message_type_user_id = ". $message_type_user_id;
   	return $this->FetchAssoc($this->executeQuery($q));
   }
   
   /**
   * DeleteMessageTypeUser()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Mar 25 07:39:26 PST 2006
   */
   function DeleteMessageTypeUser($message_type_user_id)
   {
   	$q = "DELETE FROM message_type_user WHERE message_type_user_id = ". $message_type_user_id;
   	return $this->executeQuery($q);
   }
   
   /**
   * DeleteMessageTypeRule()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Mar 25 07:40:17 PST 2006
   */
   function DeleteMessageTypeRule($message_type_user_id)
   {
   	$q = "DELETE FROM message_type_rule WHERE message_type_user_id = ". $message_type_user_id;
   	return $this->executeQuery($q);
   }
   
   /**
   * SetMarkAsRead()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 31 15:40:30 PST 2006
   */
   function SetMarkAsRead($message_id, $login)
   {
   	$q = "UPDATE message_user SET mark_as_read = 1, modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE message_id = ". $message_id ." AND login = ". $login;
   	return $this->executeQuery($q);
   }
   
   /**
   * GetMessageTypes()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 31 16:20:17 PST 2006
   */
   function GetMessageTypes()
   {
   	$q = "SELECT message_type_id, message_type_description "
   	   . "FROM message_type "
   	   . "WHERE status = 'A' ";
   	return $this->executeQuery($q);
   }
   
   /**
   * DeleteMessageUser()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 31 16:59:49 PST 2006
   */
   function DeleteMessageUser($message_id, $login)
   {
   	$q = "UPDATE message_user SET status = 'N', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE message_id = ". $message_id ." AND login = ". $login;
   	return $this->executeQuery($q);
   }
   
   /**
   * SetMessageAccountUser()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - 15:51:45
   */
   function SetMessageAccountUser($account_id, $account_name, $user_id)
   {
   	$q = "INSERT INTO message_account_user (account_id, account_name, user_id, created_by, created_date, status) "
   	   . "VALUES (". $account_id .", '". mysql_real_escape_string($account_name) ."', ". $user_id .", ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }
   
   /**
   * GetMessageAccountUserByAccountId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - 15:56:56
   */
   function GetMessageAccountUserByAccountId($account_id)
   {
   	$q = "SELECT message_account_user_id, user_id FROM message_account_user WHERE status = 'A' AND account_id = ". $account_id;
   	return $this->executeQuery($q);
   }
   
   /**
   * GetMessageAccountUserList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - 23:50:12
   */
   function GetMessageAccountUserList($user_id)
   {
   	$q = "SELECT mau.message_account_user_id, mau.account_id, mau.account_name "
   	   . "FROM message_account_user AS mau "
   	   . "WHERE mau.status = 'A' AND mau.user_id = ". $user_id;
   	return $this->executeQuery($q);
   }
   
   /**
   * isAccountUserSet()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - 00:02:12
   */
   function isAccountUserSet($account_id, $user_id)
   {
   	$q = "SELECT user_id FROM message_account_user WHERE status = 'A' AND account_id = ". $account_id . " AND user_id = ". $user_id;
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return ($r) ? true : false;
   }
   
   /**
   * DeleteMessageAccountUser()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - 10:50:10
   */
   function DeleteMessageAccountUser($message_account_user_id)
   {
   	$q = "DELETE FROM message_account_user WHERE message_account_user_id = ". $message_account_user_id;
   	return $this->executeQuery($q);
   }
   
   /**
   * GetMessageDeliveryTypes()
   *
   * @param
   * @param 
   * @return
   * @since  - 08:43:25
   */
   function GetMessageDeliveryTypes()
   {
   	$q = "SELECT message_delivery_type_id, message_delivery_type_description "
   	   . "FROM message_delivery_type "
   	   . "WHERE status = 'A' ";
   	return $this->executeQuery($q);
   }
   
   /**
   * SetMessageUserDeliveryType()
   *
   * @param
   * @param 
   * @return
   * @since  - 10:41:45
   */
   function SetMessageUserDeliveryType($message_type_user_id, $message_delivery_type_id)
   {
   	$q = "INSERT INTO message_type_user_message_delivery_type (message_type_user_id, message_delivery_type_id, created_by, created_date, status) "
   	   . "VALUES (". $message_type_user_id .", ". $message_delivery_type_id .", ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }
   
   /**
   * SetMessageAccountUserDeliveryType()
   *
   * @param
   * @param 
   * @return
   * @since  - 10:41:57
   */
   function SetMessageAccountUserDeliveryType($message_account_user_id, $message_delivery_type_id)
   {
   	$q = "INSERT INTO message_account_user_message_delivery_type (message_account_user_id, message_delivery_type_id, created_by, created_date, status) "
   	   . "VALUES (". $message_account_user_id .", ". $message_delivery_type_id .", ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }
   
   /**
   * GetDeliveryTypeByMessageUserId()
   *
   * @param
   * @param 
   * @return
   * @since  - 11:23:32
   */
   function GetDeliveryTypeByMessageUserId($message_type_user_id)
   {
   	$q = "SELECT message_delivery_type_id "
   	   . "FROM message_type_user_message_delivery_type "
   	   . "WHERE status = 'A' AND message_type_user_id = ". $message_type_user_id;
   	return $this->executeQuery($q);
   }
   
   /**
   * GetDeliveryTypeByAccountUserId()
   *
   * @param
   * @param 
   * @return
   * @since  - 14:18:03
   */
   function GetDeliveryTypeByAccountUserId($message_account_user_id)
   {
   	$q = "SELECT message_delivery_type_id "
   	   . "FROM message_account_user_message_delivery_type "
   	   . "WHERE status = 'A' AND message_account_user_id = ". $message_account_user_id;
   	return $this->executeQuery($q);
   	
   }
   
   /**
   * SetMessageDeliveryType()
   *
   * @param
   * @param 
   * @return
   * @since  - 16:23:04
   */
   function SetMessageDeliveryType($message_user_id, $message_delivery_type_id)
   {
   	$q = "INSERT INTO message_user_message_delivery_type (message_user_id, message_delivery_type_id, created_by, created_date, status) "
   	   . "VALUES (". $message_user_id .", ". $message_delivery_type_id .", ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }
   
   /**
   * SetMessageHeader()
   *
   * @param
   * @param 
   * @return
   * @since  - 19:17:50
   */
   function SetMessageHeader($message_id, $header_name, $header_value)
   {
   	$q = "INSERT INTO message_header (message_id, header_name, header_value, created_by, created_date, status) "
   	   . "VALUES (". $message_id .", '". $header_name ."', '". $header_value ."', ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }
   
   /**
   * SetMessageAttr()
   *
   * @param
   * @param 
   * @return
   * @since  - 07:35:36
   */
   function SetMessageAttr($message_id, $message_attr_name, $message_attr_value)
   {
   	$q = "INSERT INTO message_attr (message_id, message_attr_name, message_attr_value, created_by, created_date, status) "
   	   . "VALUES (". $message_id .", '". $message_attr_name ."', '". mysql_real_escape_string($message_attr_value) ."', ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }
   
   /**
   * SetMessageBody()
   *
   * @param
   * @param 
   * @return
   * @since  - 07:41:39
   */
   function SetMessageBody($message_id, $message_body_type_id, $message_body_content)
   {
   	$q = "INSERT INTO message_body (message_id, message_body_type_id, message_body_content, created_by, created_date, status) "
   	   . "VALUES (". $message_id .", ". $message_body_type_id .", '". mysql_real_escape_string($message_body_content) ."', ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }
   
   /**
   * GetMessageContent()
   *
   * @param
   * @param 
   * @return
   * @since  - 14:07:15
   */
   function GetMessageBody($message_id, $message_body_type_id)
   {
   	$q = "SELECT message_body_content "
   	   . "FROM message_body "
   	   . "WHERE status = 'A' AND message_id = ". $message_id ." AND message_body_type_id = ". $message_body_type_id;
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return $r['message_body_content'];
   }
   
   /**
   * SetMessageUserMessageDeliverySentDate()
   *
   * @param
   * @param 
   * @return
   * @since  - 14:40:49
   */
   function SetMessageUserMessageDeliverySentDate($message_user_message_delivery_type_id)
   {
   	$q = "UPDATE message_user_message_delivery_type SET sent_date = NOW() "
   	   . "WHERE message_user_message_delivery_type_id = ". $message_user_message_delivery_type_id;
   	return $this->executeQuery($q);
   }
   
   /**
   * GetMessageAttr()
   *
   * @param
   * @param 
   * @return
   * @since  - 15:51:35
   */
   function GetMessageAttr($message_id)
   {
   	$q = "SELECT message_attr_name, message_attr_value "
   	   . "FROM message_attr "
   	   . "WHERE message_id = ". $message_id;
   	return $this->executeQuery($q);
   }
   
   /**
   * GetMessageTypeDetail()
   *
   * @param
   * @param 
   * @return
   * @since  - 16:02:20
   */
   function GetMessageTypeDetail($message_type_id)
   {
   	$q = "SELECT module_id, message_content_type_id "
   	   . "FROM message_type "
   	   . "WHERE status = 'A' AND message_type_id = ". $message_type_id;
   	return $this->FetchAssoc($this->executeQuery($q));
   }
   
   /**
   * GetMessageHeaders()
   *
   * @param
   * @param 
   * @return
   * @since  - 10:27:43
   */
   function GetMessageHeaders($message_id)
   {
   	$q = "SELECT header_name, header_value "
   	   . "FROM message_header "
   	   . "WHERE	status = 'A' AND message_id = ". $message_id;
   	return $this->executeQuery($q);
   }
   
   /**
   * GetUserByMessageUserId()
   *
   * @param
   * @param 
   * @return
   * @since  - 20:46:58
   */
   function GetUserByMessageUserId($message_user_id)
   {
   	$q = "SELECT login FROM message_user WHERE message_user_id = ". $message_user_id;
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return ($r) ? $r['login'] : false;
   }
}
