<?php

require_once "dbConnect.class.php";

class accountDB extends dbConnect {

   private $__account_id = 0;

   private $compress_attribute	= array('ACCOUNT_HIERARCHY_SECURITY_TOKEN');

   /**
   * __construct()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jan 25 13:32:40 PST 2006
   */
   function __construct()
   {
      parent::dbConnect();


   }
   
   public function saveAccount($name, $type, $country, $contact, $email, $phone) 
   {
   		$sql = "INSERT INTO account (account_name, country_code, account_type_id, created_by, created_date, status) "
   		     . "VALUES ('" . mysql_real_escape_string($name) . "','" . mysql_real_escape_string($country) . "', '" 
   		                   . mysql_real_escape_string($type) . "', 10312, NOW(), 'A')";
		if (DEBUG_LEVEL_2) printf("debug %s", $sql);   		                   
   	 	$this->executeQuery($sql);
   	 	$account_id = mysql_insert_id();
   	 	
   	 	$names = preg_split('/ /', $contact, 2);
   	 	
   	 	$sql = "INSERT INTO contact (account_id, contact_phone, contact_first_name, contact_last_name, contact_email, created_date, created_by, status) "
   	 	    . " VALUES ($account_id, '" . mysql_real_escape_string($phone) . "' ,'" . mysql_real_escape_string($names[0]) . "', '" . mysql_real_escape_string($names[1]) . "', '" . mysql_real_escape_string($email) . "', 10312, NOW(), 'A')"; 
   		$this->executeQuery($sql);
   		
   		return $account_id;
   }
   
   /**
    * 
    * Returns a list of account contacts using account name
    * @param string $account
    */
   public function GetContactsById($account)
   {
   		$sql = "SELECT contact_id, CONCAT(contact_first_name, ' ', contact_last_name) AS contact_name "
   		     . "FROM contact "
   		     . "WHERE account_id = '" . mysql_real_escape_string($account) . "' ";
   		return $this->executeQuery($sql);
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
   * @since  - Wed Jan 25 13:32:53 PST 2006
   */
   function __deconstruct()
   {
      //TODO add code

   }
   
  /**
   * getRole()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function getRole($id)
   {
      $qry  = "SELECT user_id FROM account_user WHERE account_id = ". $this->__account_id. " AND role_id = ". $id ." AND status = 'A'";
      $rs = $this->executeQuery($qry);   
      
      return ($this->rows) ? mysql_result($rs, 0, 'user_id') : 0;
   }

   /**
   * GetAccountList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jan 25 13:33:04 PST 2006
   */
   function GetAccountListByType($account_type_id, $account_sub_type_id = 0)
   {
   	$where = '';
   	$joins = '';

   	if (is_numeric($account_type_id))
   	{
   		$where .= ' AND aat.account_type_id = '. $account_type_id;
   		$joins .= ' LEFT OUTER JOIN account_account_type AS aat ON aat.account_id = a.account_id ';
   	}

   	if ($account_sub_type_id != 0)
   	{
   		$where .= ' AND aast.account_sub_type_id = '. $account_sub_type_id;
   		$joins .= ' LEFT OUTER JOIN account_account_sub_type AS aast ON aast.account_id = a.account_id ';
   	}

      $q = "SELECT a.account_id, a.account_name "
         . "FROM account AS a "
         . $joins
         . "WHERE a.status = 'A' ". $where ." "
         . "ORDER BY a.account_name ";
      return $this->executeQuery($q);
   }

   /**
   * SetAccountId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jan 25 13:36:55 PST 2006
   */
   function SetAccountId($account_id)
   {
      $this->__account_id = $account_id;
   }

   /**
   * GetAccountDetail()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jan 25 13:37:13 PST 2006
   */
   function GetAccountDetail()
   {
      $q = "SELECT a.account_id, a.account_type_id, a.account_name, a.country_code, a.account_status_id, c.country_description, r.region_description, "      				
         . "       u_cb.last_name AS created_by_name, a.created_date, r.region_id, a.status AS account_status "
         . "FROM account AS a "
         . "LEFT OUTER JOIN country AS c ON c.country_code = a.country_code "
         . "LEFT OUTER JOIN region AS r ON r.region_id = c.region_id "
         . "LEFT OUTER JOIN user AS u_cb ON u_cb.login = a.created_by "
         . "WHERE a.account_id = ". $this->__account_id;
      return mysql_fetch_assoc($this->executeQuery($q));
   }

   /**
   * GetAccountAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jan 25 13:47:44 PST 2006
   */
   function GetAccountAttr()
   {
      $q = "SELECT at.account_attr_value, at.account_attr_name "
         . "FROM account_attr AS at "
         . "WHERE at.status = 'A' AND at.account_id = ". $this->__account_id;
      return $this->executeQuery($q);
   }

   /**
   * GetAccountAttrDef()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 01 17:54:18 PST 2006
   */
   function GetAccountAttrDef($system = "NULL")
   {
   	$q = "SELECT aad.account_attr_name, aad.account_attr_description, aad.value_type, aad.security_setting "
   	   . "FROM account_attr_def AS aad "
   	   . "WHERE aad.status = 'A' AND aad.system_setting IS " . $system;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountUser()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jan 25 13:55:33 PST 2006
   */
   function GetAccountUser($product_id)
   {
      $q = "SELECT au.user_id, au.role_id "
         . "FROM account_user AS au "
         . "WHERE status = 'A' AND au.account_id = ". $this->__account_id ." AND au.product_id = ". $product_id;
      return $this->executeQuery($q);
   }

   /**
   * GetAccountUserByRoleProduct()
   *
   * @param
   * @param
   * @return
   * @since  - 15:56:51
   */
   function GetAccountUserByRoleProduct($product_id, $role_id)
   {
   	$q = "SELECT au.user_id, au.role_id, u.email_address "
         . "FROM account_user AS au "
         . "JOIN user AS u ON u.login = au.user_id "
         . "WHERE au.status = 'A' AND au.account_id = ". $this->__account_id ." AND au.product_id = ". $product_id . " AND au.role_id = ". $role_id;
      return $this->fetchAssoc($this->executeQuery($q));
   }

   /**
   * GetAccountList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jan 31 15:02:17 PST 2006
   */
   function GetAccountList($filter, $page = '', $sort = ' a.account_name ')
   {
      $q = "SELECT a.account_id, a.account_name, a.country_code, c.country_description, CONCAT(u_ae.first_name,' ',u_ae.last_name) AS ae_name, "
         . "       CONCAT(u_am.first_name,' ',u_am.last_name) AS am_name, aa_tier.account_attr_value AS account_tier, aa_isam.account_attr_value AS handled_by_am "
         . "FROM account AS a "
         . "LEFT OUTER JOIN country AS c ON c.country_code = a.country_code "
         . "LEFT OUTER JOIN account_account_type AS aat ON aat.account_id = a.account_id "
         . "LEFT OUTER JOIN account_account_sub_type AS aast ON aast.account_id = a.account_id "
         . "LEFT OUTER JOIN account_user AS au_ae ON au_ae.account_id = a.account_id
         					AND au_ae.role_id = ". PRIMARY_ACCT_EXEC ."
         					AND au_ae.product_id = ". PRODUCT_NETMR  . "
         					AND au_ae.status = 'A' "
         . "LEFT OUTER JOIN account_user AS au_am ON au_am.account_id = a.account_id
         					AND au_am.role_id = ". PRIMARY_ACCT_MGR ."
         					AND au_am.product_id = ". PRODUCT_NETMR  . "
         					AND au_am.status = 'A' "
         . "LEFT OUTER JOIN account_attr AS aa_tier ON aa_tier.account_id = a.account_id
         					AND aa_tier.account_attr_name = 'GLOBAL_ACCOUNT_TIER_LEVEL' "
         . "LEFT OUTER JOIN account_attr AS aa_isam ON aa_isam.account_id = a.account_id
         					AND aa_isam.account_attr_name = 'GLOBAL_STUDY_HANDLED_BY_AM' "
         . "LEFT OUTER JOIN account_attr AS aa_ora_id ON aa_ora_id.account_id = a.account_id
         					AND aa_ora_id.account_attr_name = 'ARMC_ORA_ACCOUNT_ID' "
         . "LEFT OUTER JOIN user AS u_ae ON u_ae.login = au_ae.user_id "
         . "LEFT OUTER JOIN user AS u_am ON u_am.login = au_am.user_id "
         . "WHERE a.status = 'A' ". $filter ." "
         . "GROUP BY a.account_id "
         . "ORDER BY ". $sort ." "
         . $page ." ";
      return $this->executeQuery($q);
   }

   /**
   * GetContacts()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Feb 03 09:36:32 PST 2006
   */
   function GetContacts()
   {
      $q = "SELECT c.contact_id, c.contact_title, c.contact_first_name, c.contact_last_name, c.contact_email "
         . "FROM account_contact AS ac "
         . "LEFT OUTER JOIN contact AS c ON c.contact_id = ac.contact_id "
         . "WHERE ac.status = 'A' AND ac.account_id = ". $this->__account_id;
      return $this->executeQuery($q);
   }

   /**
   * GetAccountContactsDetails()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Feb 03 09:44:13 PST 2006
   */
   function GetAccountContactsDetails($contact_type_id, $address_type_id)
   {
      $q = "SELECT c.contact_id, c.contact_title, c.contact_first_name, c.contact_last_name, c.contact_email, "
         . "       adr.address_street_1, adr.address_street_2, adr.address_city, adr.address_state, adr.address_province, "
         . "       adr.address_zip, adr.address_country_code, ca.country_attr_value AS address_country_iso2_code, cnt.country_description, cp.contact_phone_number, "
         . "       cp_fax.contact_phone_number AS contact_fax_number "
         . "FROM account_contact AS ac "
         . "LEFT OUTER JOIN contact AS c ON c.contact_id = ac.contact_id "
         . "LEFT OUTER JOIN address AS adr ON adr.contact_id = c.contact_id "
         . "LEFT OUTER JOIN country AS cnt ON cnt.country_code = adr.address_country_code "
         . "LEFT OUTER JOIN country_attr AS ca ON ca.country_id=cnt.country_id AND ca.country_attr_name='ISO_2_CODE' "
         . "LEFT OUTER JOIN contact_phone AS cp ON cp.contact_id = c.contact_id AND cp.phone_type_id = ". PHONE_TYPE_WORK ." "
         . "LEFT OUTER JOIN contact_phone AS cp_fax ON cp_fax.contact_id = c.contact_id AND cp_fax.phone_type_id = ". PHONE_TYPE_FAX ." "
         . "WHERE ac.status = 'A' AND ac.account_id = ". $this->__account_id ." AND c.contact_type_id IN (". $contact_type_id .") AND "
         . "      adr.address_type_id = ". $address_type_id;
      return $this->executeQuery($q);
   }

   /**
   * GetAllAccountContactsDetails()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 31 19:42:06 PST 2006
   */
   function GetAllAccountContactsDetails($address_type_id)
   {
   	$q = "SELECT ac.account_contact_id, c.contact_designation, c.contact_id, c.contact_title, c.contact_first_name, c.contact_last_name, c.contact_email, "
         . "       adr.address_street_1, adr.address_street_2, adr.address_city, adr.address_state, adr.address_province, "
         . "       adr.address_zip, adr.address_country_code, cnt.country_description, cp.contact_phone_number, cnt.country_code_prefix, "
         . "       cp_fax.contact_phone_number AS contact_fax_number, ct.contact_type_description , ct.contact_type_id "
         . "FROM account_contact AS ac "
         . "LEFT OUTER JOIN contact AS c ON c.contact_id = ac.contact_id "
         . "LEFT OUTER JOIN address AS adr ON adr.contact_id = c.contact_id "
         . "LEFT OUTER JOIN country AS cnt ON cnt.country_code = adr.address_country_code "
         . "LEFT OUTER JOIN contact_type AS ct ON ct.contact_type_id = c.contact_type_id "
         . "LEFT OUTER JOIN contact_phone AS cp ON cp.contact_id = c.contact_id AND cp.phone_type_id = ". PHONE_TYPE_WORK ." "
         . "LEFT OUTER JOIN contact_phone AS cp_fax ON cp_fax.contact_id = c.contact_id AND cp_fax.phone_type_id = ". PHONE_TYPE_FAX ." "
         . "WHERE ac.status = 'A' AND ac.account_id = ". $this->__account_id ." AND adr.address_type_id = ". $address_type_id . " LIMIT 10";
      return $this->executeQuery($q);
   }
   
   
   function getInternalAuditLog()
   {
   		$q = "SELECT ae.account_event_type_id AS typeid, ae.comment AS comment, ae.created_date AS cdate, ae.created_by AS cby FROM account_event AS ae WHERE account_id = " . $this->__account_id . " GROUP BY typeid, cby "
   		   . " UNION ALL SELECT ac.account_comment_type_id AS typeid, ac.comment_text AS comment, ac.created_date AS cdate, ac.created_by AS cby FROM account_comment  AS ac WHERE account_id = " . $this->__account_id . " GROUP BY typeid, cby LIMIT 5";
   		return $this->executeQuery($q);	
   }

   function SetAccountEvent($event_id)
   {
   		$q = "INSERT INTO account_event (account_id, account_event_type_id, created_by, created_date, status) "
   		   . "VALUES ($this->__account_id, $event_id, 10312, now(), 'A')";
   		return $this->executeQuery($q);	
   }
   /**
   * isPortalUser()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 14 09:15:19 PDT 2006
   */
   function isPortalUser($account_contact_id)
   {
   	$q = "SELECT portal_user_id "
   	   . "FROM portal_user "
   	   . "WHERE status = 'A' AND account_contact_id = ". $account_contact_id;
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return ($r) ? true : false;
   }

   /**
   * SetPortalUser()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 14 09:16:47 PDT 2006
   */
   function SetPortalUser($account_contact_id, $username, $password)
   {
   	$q = "INSERT INTO portal_user (account_contact_id, username, password, created_by, created_date, status) "
   	   . "VALUES (". $account_contact_id .", '". mysql_real_escape_string($username) ."', '". mysql_real_escape_string($password) ."', "
   	   . "        ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * UpdatePortalUser()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 14 09:18:46 PDT 2006
   */
   function UpdatePortalUser($account_contact_id, $username, $password)
   {
   	$q = "UPDATE portal_user SET username = '". mysql_real_escape_string($username) ."', password = '". mysql_real_escape_string($password) ."', "
   	   . "                       modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE status = 'A' AND account_contact_id = ". $account_contact_id;
   	return $this->executeQuery($q);
   }
   
   /**
   * DeletePortalUser()
   *
   * @param 
   * @param  
   * @throws 
   * @return
   * @access
   * @since  - Thu Jul 05 13:03:55 IST 2007
   */
   function DeletePortalUser($account_contact_id)
   {
   	$q = "UPDATE portal_user SET status = 'D', modified_by = ". $this->created_by .", modified_date = NOW() "
   		. "WHERE account_contact_id=". $account_contact_id;
   		
   	return $this->executeQuery($q);
   }
   

   /**
   * GetAccountStatusByAccountType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jun 17 17:35:46 PDT 2006
   */
   function GetAccountStatusByAccountType($account_type_id)
   {
   	$q = "SELECT asat.account_status_account_type_id, ast.account_status_id, ast.account_status_description "
   	   . "FROM account_status_account_type as asat "
   	   . "LEFT OUTER JOIN account_status as ast ON asat.account_status_id = ast.account_status_id "
   	   . "WHERE asat.status = 'A' AND asat.account_type_id = ". $account_type_id;  	   
     
   	return $this->executeQuery($q);
   }

   /**
   * GetContactPhoneDetails()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Feb 03 09:57:02 PST 2006
   */
   function GetContactPhoneDetails($contact_id)
   {
      $q = "SELECT cp.phone_type_id, cp.contact_phone_number, cp.contact_phone_ext, cp.phone_country_code "
         . "FROM contact_phone AS cp "
         . "WHERE cp.status = 'A' AND cp.contact_id = ". $contact_id;
      return $this->executeQuery($q);
   }

   /**
   * isAccountOnFile()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Feb 03 10:02:38 PST 2006
   */
   function isAccountOnFile()
   {
      $q = "SELECT account_id FROM account WHERE account_id = ". $this->__account_id;
      $rs = $this->executeQuery($q);
      return $this->rows;
   }

   /**
   * GetAccountTypeList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Feb 09 21:50:47 PST 2006
   */
   function GetAccountTypeList()
   {
   	$q = "SELECT at.account_type_id, at.account_type_description "
   	   . "FROM account_type AS at "
   	   . "WHERE at.status = 'A' ";
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountSubTypeList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Feb 10 14:29:49 PST 2006
   */
   function GetAccountSubTypeList($account_type_id = 0)
   {
   	$where = ' ';
   	
   	if($account_type_id != 0) {
   		$where = " AND account_type_id = ". $account_type_id;
   	}
   	
      $q = "SELECT ast.account_sub_type_id, ast.account_sub_type_description "
   	   . "FROM account_sub_type AS ast "
   	   . "WHERE ast.status = 'A' ". $where ;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountAttrByAttrName()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Feb 17 17:35:37 PST 2006
   */
   function GetAccountAttrByAttrName($attr_name)
   {
      $q = "SELECT aa.account_attr_value "
         . "FROM account_attr AS aa "
         . "WHERE aa.status = 'A' AND aa.account_id = ". $this->__account_id ." AND aa.account_attr_name = '". $attr_name ."' ";
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return ($r) ? $r['account_attr_value'] : false;
   }

   /**
   * SetAccountAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Feb 17 17:38:30 PST 2006
   */
   function SetAccountAttr($attr_name, $attr_value)
   {
      $q = "INSERT INTO account_attr (account_id, account_attr_name, account_attr_value, created_by, created_date, status) "
         . "VALUES (". $this->__account_id .", '". $attr_name ."', '". mysql_real_escape_string($attr_value) ."', ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
   }

   /**
   * UpdateAccountAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Feb 17 17:40:25 PST 2006
   */
   function UpdateAccountAttr($attr_name, $attr_value)
   {
      $q = "UPDATE account_attr AS aa "
         . "SET aa.account_attr_value = '". mysql_real_escape_string($attr_value) ."', modified_by = ". $this->created_by .", modified_date = NOW() "
         . "WHERE aa.account_id = ". $this->__account_id ." AND aa.account_attr_name = '". $attr_name ."' ";
      return $this->executeQuery($q);
   }

   /**
   * isAccountAttrSet()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Feb 17 17:42:54 PST 2006
   */
   function isAccountAttrSet($attr_name)
   {
      $q = "SELECT aa.account_attr_value "
         . "FROM account_attr AS aa "
         . "WHERE aa.status = 'A' AND aa.account_id = ". $this->__account_id ." AND aa.account_attr_name = '". $attr_name ."' ";
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return ($r) ? true : false;
   }

   /**
   * SetAcount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Feb 19 18:24:14 PST 2006
   */
   function SetAccount($account_name, $country_code, $status = 'A')
   {
   	$q = "INSERT INTO account (account_name, country_code, created_by, created_date, status) "
   	   . "VALUES ('". mysql_real_escape_string($account_name) ."', '". $country_code ."', ". $this->created_by .", NOW(), '". $status. "') ";
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountSubTypeByType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Feb 19 20:17:01 PST 2006
   */
   function GetAccountSubTypeByType($account_type_id)
   {
   	$q = "SELECT ast.account_sub_type_id, ast.account_sub_type_description "
   	   . "FROM account_sub_type AS ast "
   	   . "WHERE ast.status = 'A' AND ast.account_type_id = ". $account_type_id;
   	return $this->executeQuery($q);
   }

   /**
   * SetAccountType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Feb 19 20:53:48 PST 2006
   */
   function SetAccountType($account_type_id)
   {
   	$q = "INSERT INTO account_account_type (account_id, account_type_id, created_by, created_date, status) "
   	   . "VALUES (". $this->__account_id .", ". $account_type_id .", ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * SetAccountSubType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Feb 19 20:53:54 PST 2006
   */
   function SetAccountSubType($account_sub_type_id)
   {
   	$q = "INSERT INTO account_account_sub_type (account_id, account_sub_type_id, created_by, created_date, status) "
   	   . "VALUES (". $this->__account_id .", ". $account_sub_type_id .", ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * isAccountType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Feb 19 20:58:52 PST 2006
   */
   function isAccountType($account_type_id)
   {
   	$q = "SELECT account_account_type_id "
   	   . "FROM account_account_type "
   	   . "WHERE status = 'A' AND account_id = ". $this->__account_id ." AND account_type_id = ". $account_type_id;
   	$r = mysql_fetch_assoc($this->executeQuery($q));
   	return ($r) ? 1 : 0;
   }

   /**
   * SetAccountProduct()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Feb 19 22:11:13 PST 2006
   */
   function SetAccountProduct($product_id, $license_level_id, $pricing_regime_id, $account_identifier)
   {
   	$q = "INSERT INTO account_product (account_id, product_id, license_level_id, pricing_regime_id, account_identifier, created_by, created_date, status) "
   	   . "VALUES (". $this->__account_id .", ". $product_id .", '". $license_level_id ."', '". $pricing_regime_id ."', '". mysql_real_escape_string($account_identifier) ."', "
   	   . "        ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * isAccountHasProduct()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 20 13:57:41 PST 2006
   */
   function isAccountHasProduct($product_id, $account_identifier)
   {
   	$q = "SELECT account_product_id "
   	   . "FROM account_product "
   	   . "WHERE product_id = ". $product_id ." AND account_identifier = ". $account_identifier;
   	$r =  $this->FetchAssoc($this->executeQuery($q));
   	return ($r) ? 1 : 0;
   }

   /**
   * GetAccountType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 20 14:08:00 PST 2006
   */
   function GetAccountType()
   {
   	$q = "SELECT aat.account_type_id, at.account_type_description "
   	   . "FROM account_account_type AS aat "
   	   . "LEFT OUTER JOIN account_type AS at ON at.account_type_id = aat.account_type_id "
   	   . "WHERE aat.status = 'A' AND aat.account_id = ". $this->__account_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountContactAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 12 13:40:08 PDT 2006
   */
   function GetAccountContactAttr($account_contact_id)
   {
   	$q = "SELECT account_contact_attr_name, account_contact_attr_value "
   	   . "FROM account_contact_attr "
   	   . "WHERE status = 'A' AND account_contact_id = ". $account_contact_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountSubType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 20 14:08:50 PST 2006
   */
   function GetAccountSubType()
   {
   	$q = "SELECT aast.account_sub_type_id, ast.account_sub_type_description "
   	   . "FROM account_account_sub_type AS aast "
   	   . "LEFT OUTER JOIN account_sub_type AS ast ON ast.account_sub_type_id = aast.account_sub_type_id "
   	   . "WHERE aast.status = 'A' AND aast.account_id = ". $this->__account_id;
   	return $this->executeQuery($q);
   }

   /**
   * SetUserRole()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 20 22:02:20 PST 2006
   */
   function SetUserRole($product_id, $role_id, $user_id)
   {
   	$q = "INSERT INTO account_user (account_id, product_id, user_id, role_id, created_by, created_date, status)  "
   	   . "VALUES (". $this->__account_id .", ". $product_id .", ". $user_id .", ". $role_id .", ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountByName()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Feb 23 16:52:02 PST 2006
   */
   function GetAccountByName($account_name)
   {
   	$q = "SELECT a.account_id, a.account_name "
   	   . "FROM account AS a "
   	   . "WHERE a.status = 'A' AND a.account_name LIKE '%". mysql_real_escape_string($account_name) ."%' ";
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountUsers()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Feb 24 19:39:44 PST 2006
   */
   function GetAccountUsers()
   {
   	$q = "SELECT au.user_id, au.role_id, au.account_id, au.product_id, au.created_by, au.created_date, "
   	   . "       p.product_description, u_au.first_name, u_au.last_name, u_cb.last_name AS created_by_name,  "
   	   . "       r.role_description, au.account_user_id, u_au.email_address "
   	   . "FROM account_user AS au "
   	   . "LEFT OUTER JOIN user AS u_au ON u_au.login = au.user_id "
   	   . "LEFT OUTER JOIN user AS u_cb ON u_cb.login = au.created_by "
   	   . "LEFT OUTER JOIN product AS p ON p.product_id = au.product_id "
   	   . "LEFT OUTER JOIN role AS r ON r.role_id = au.role_id "
   	   . "WHERE au.status = 'A' AND au.account_id = ". $this->__account_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountProducts()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Feb 24 19:48:56 PST 2006
   */
   function GetAccountProducts()
   {
   	$q = "SELECT ap.account_product_id, ap.product_id, ap.license_level_id, ap.pricing_regime_id, ap.account_identifier, "
   	   . "       p.product_description, ll.license_level_description, pr.pricing_regime_description "
   	   . "FROM account_product AS ap "
   	   . "LEFT OUTER JOIN product AS p ON p.product_id = ap.product_id "
   	   . "LEFT OUTER JOIN license_level AS ll ON ll.license_level_id = ap.license_level_id "
   	   . "LEFT OUTER JOIN pricing_regime AS pr ON pr.pricing_regime_id = ap.pricing_regime_id "
   	   . "WHERE ap.status = 'A' AND ap.account_id = ". $this->__account_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountProduct()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 08 21:50:45 PST 2006
   */
   function GetAccountProduct($product_id)
   {
   	$q = "SELECT ap.account_product_id, ap.product_id, ap.license_level_id, ap.pricing_regime_id, ap.account_identifier, "
   	   . "       p.product_description, ll.license_level_description, pr.pricing_regime_description, 'on' AS is_active "
   	   . "FROM account_product AS ap "
   	   . "LEFT OUTER JOIN product AS p ON p.product_id = ap.product_id "
   	   . "LEFT OUTER JOIN license_level AS ll ON ll.license_level_id = ap.license_level_id "
   	   . "LEFT OUTER JOIN pricing_regime AS pr ON pr.pricing_regime_id = ap.pricing_regime_id "
   	   . "WHERE ap.status = 'A' AND ap.account_id = ". $this->__account_id . " AND ap.product_id = ". $product_id;
   	return $this->FetchAssoc($this->executeQuery($q));
   }

   /**
   * SetAccountContact()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Feb 24 23:36:44 PST 2006
   */
   function SetAccountContact($contact_id)
   {
   	$q = "INSERT INTO account_contact (account_id, contact_id, created_by, created_date, status ) "
   	   . "VALUES (". $this->__account_id .", ". $contact_id .", ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * SetAccountContactAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jun 11 09:01:51 PDT 2006
   */
   function SetAccountContactAttr($account_contact_id, $account_contact_attr_name, $account_contact_attr_value)
   {
   	$q = "INSERT INTO account_contact_attr (account_contact_id, account_contact_attr_name, account_contact_attr_value, created_by, created_date, status) "
   	   . "VALUES (". $account_contact_id .", '". $account_contact_attr_name ."', '". mysql_real_escape_string($account_contact_attr_value) ."', "
   	   . "        ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * UpdateAccountContactAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jun 11 09:15:42 PDT 2006
   */
   function UpdateAccountContactAttr($account_contact_id, $account_contact_attr_name, $account_contact_attr_value)
   {
   	$q = "UPDATE account_contact_attr "
   	   . "SET account_contact_attr_value = '". mysql_real_escape_string($account_contact_attr_value) ."', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE status = 'A' AND account_contact_id = ". $account_contact_id ." AND account_contact_attr_name = '". $account_contact_attr_name ."' ";
   	return $this->executeQuery($q);
   }

   /**
   * isAccountContactAttrSet()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jun 11 09:17:59 PDT 2006
   */
   function isAccountContactAttrSet($account_contact_id, $account_contact_attr_name)
   {
   	$q = "SELECT account_contact_attr_id "
   	   . "FROM account_contact_attr "
   	   . "WHERE status = 'A' AND account_contact_id = ". $account_contact_id ." AND account_contact_attr_name = '". $account_contact_attr_name ."' ";
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return ($r) ? true : false;
   }

   /**
   * GetAccountContactAttrDef()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jun 11 09:05:57 PDT 2006
   */
   function GetAccountContactAttrDef()
   {
   	$q = "SELECT account_contact_attr_def_id, account_contact_attr_name "
   	   . "FROM account_contact_attr_def "
   	   . "WHERE status = 'A' ";
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountContactIdByContactId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jun 11 09:12:52 PDT 2006
   */
   function GetAccountContactIdByContactId($contact_id)
   {
   	$q = "SELECT account_contact_id "
   	   . "FROM account_contact "
   	   . "WHERE status = 'A' AND account_id = ". $this->__account_id ." AND contact_id = ". $contact_id;
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return ($r) ? $r['account_contact_id'] : false;
   }

   /**
   * SetAccountContactComment()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 27 18:09:06 PST 2006
   */
   function SetAccountContactComment($account_contact_id, $account_contact_comment_type_id, $comment_from, $comment_to, $comment_header, $comment_subject, $comment_text)
   {
   	$q = "INSERT INTO account_contact_comment (account_contact_id, account_contact_comment_type_id, comment_from, comment_to, comment_header, comment_subject, comment_text, created_by, created_date, status ) "
   	   . "VALUES ( ". $account_contact_id .", ". $account_contact_comment_type_id .", '". mysql_real_escape_string($comment_from) ."', '". mysql_real_escape_string($comment_to) ."', '". mysql_real_escape_string($comment_header) ."', "
   	   . "        '". mysql_real_escape_string($comment_subject) ."', '". mysql_real_escape_string($comment_text) ."', ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * GetContactsByEmail()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 27 21:59:44 PST 2006
   */
   function GetContactsByEmail($filter = '')
   {
   	$q = "SELECT c.contact_id "
   	   . "FROM contact AS c "
   	   . "WHERE c.status = 'A' ". $filter;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountContactsByEmail()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 01 10:10:09 PST 2006
   */
   function GetAccountContactsByEmail($email)
   {
   	if (is_array($email)) {
   		//check if we have any contacts with to,cc fields
			$in_string = implode("','" , $email);

			//add single quote to the begining of the string
			$in_string = preg_replace("/^/", "'", $in_string);

			//end of the string
			$in_string = preg_replace("/$/", "'", $in_string);

			$filter = " AND c.contact_email IN (". $in_string .") ";

   	} else {
   		$filter = " AND c.contact_email = '" . $email ."' ";
   	}

   	$q = "SELECT c.contact_id, ac.account_contact_id, ac.account_id "
   	   . "FROM contact AS c "
   	   . "LEFT OUTER JOIN account_contact AS ac ON ac.contact_id = c.contact_id "
   	   . "WHERE c.status = 'A' ". $filter . " AND ac.account_contact_id IS NOT NULL ";
   	return $this->executeQuery($q);

   }

   /**
   * GetAccountByContactId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 27 22:04:08 PST 2006
   */
   function GetAccountByContactId($contact_id)
   {
   	$q = "SELECT ac.account_contact_id, ac.account_id "
   	   . "FROM account_contact AS ac "
   	   . "WHERE ac.status = 'A' AND ac.contact_id = ". $contact_id;
   	return $this->FetchAssoc($this->executeQuery($q));
   }

   /**
   * GetAccountContactComments()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 01 10:25:41 PST 2006
   */
   function GetAccountContactComments()
   {
   	$q = "SELECT acc.account_contact_comment_id, acc.account_contact_id, acc.comment_from, acc.comment_to, acc.comment_header, acc.comment_subject, acc.comment_text, acc.created_by, acc.created_date "
   	   . "FROM account_contact_comment AS acc "
   	   . "LEFT OUTER JOIN account_contact AS ac ON ac.account_contact_id = acc.account_contact_id "
   	   . "WHERE acc.status = 'A' AND ac.account_id = ". $this->__account_id;
   	return $this->executeQuery($q);
   }

   /**
   * SetAccountContactCommentFile()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 01 10:49:49 PST 2006
   */
   function SetAccountContactCommentFile($account_contact_comment_id, $file_type_id, $file_name, $file_title, $file_data, $file_size)
   {
   	$q = "INSERT INTO account_contact_comment_file (account_contact_comment_id, file_type_id, file_name, file_title, file_data, file_size, created_by, created_date, status) "
   	   . "VALUES (". $account_contact_comment_id .", ". $file_type_id .", '". mysql_real_escape_string($file_name) ."', '". mysql_real_escape_string($file_title) ."', "
   	   . "       '". mysql_real_escape_string($file_data) ."', '". mysql_real_escape_string($file_size) ."', ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountContactCommentDetail()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 01 11:19:40 PST 2006
   */
   function GetAccountContactCommentDetail($account_contact_comment_id)
   {
   	$q = "SELECT acc.account_contact_comment_id, acc.comment_from, acc.comment_to, acc.comment_header, acc.comment_subject, acc.comment_text, acc.created_date "
   	   . "FROM account_contact_comment AS acc "
   	   . "WHERE acc.status = 'A' AND acc.account_contact_comment_id = ". $account_contact_comment_id;
   	return $this->FetchAssoc($this->executeQuery($q));
   }

   /**
   * GetAccountContactCommentFiles()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 01 13:50:34 PST 2006
   */
   function GetAccountContactCommentFiles($account_contact_comment_id)
   {
   	$q = "SELECT accf.account_contact_comment_file_id, accf.file_name "
   	   . "FROM account_contact_comment_file AS accf "
   	   . "WHERE accf.status = 'A' AND accf.account_contact_comment_id = ". $account_contact_comment_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountContactCommentFile()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 01 13:54:02 PST 2006
   */
   function GetAccountContactCommentFile($account_contact_comment_file_id)
   {
   	$q = "SELECT accf.account_contact_comment_file_id, accf.file_name, accf.file_data, accf.file_size "
   	   . "FROM account_contact_comment_file AS accf "
   	   . "WHERE accf.status = 'A' AND accf.account_contact_comment_file_id = ". $account_contact_comment_file_id;
   	return $this->FetchAssoc($this->executeQuery($q));
   }

   /**
   * UpdateUserRole()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 01 14:30:47 PST 2006
   */
   function UpdateUserRole($account_user_id, $product_id, $role_id, $user_id)
   {
   	$q = "UPDATE account_user "
   	   . "SET product_id = ". $product_id .", role_id = ". $role_id .", user_id = ". $user_id .", modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE account_user_id = ". $account_user_id;
   	return $this->executeQuery($q);
   }

   /**
   * UpdateAccountProduct()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 01 16:10:03 PST 2006
   */
   function UpdateAccountProduct($account_product_id, $product_id, $license_level_id, $pricing_regime_id, $account_identifier)
   {
   	$q = "UPDATE account_product "
   	   . "SET product_id = ". $product_id .", license_level_id = '". $license_level_id ."', pricing_regime_id = '". $pricing_regime_id ."', "
   	   . "    account_identifier = '". $account_identifier ."', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE account_product_id = ". $account_product_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountContactDetails()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Feb 03 09:44:13 PST 2006
   */
   function GetAccountContactDetails($contact_id)
   {
      $q = "SELECT c.contact_id, c.contact_title, c.contact_first_name, c.contact_last_name, c.contact_email, "
         . "       adr.address_street_1, adr.address_street_2, adr.address_city, adr.address_state, adr.address_province, "
         . "       adr.address_zip, adr.address_country_code, cnt.country_description, adr.address_id, cp.contact_phone_number, "
         . "       cp.contact_phone_id, cp.phone_type_id, cp_fax.contact_phone_number AS contact_fax_number, cp_fax.contact_phone_id AS contact_fax_id, "
         . "       c.contact_type_id, ac.account_contact_id "
         . "FROM account_contact AS ac "
         . "LEFT OUTER JOIN contact AS c ON c.contact_id = ac.contact_id "
         . "LEFT OUTER JOIN address AS adr ON adr.contact_id = c.contact_id "
         . "LEFT OUTER JOIN country AS cnt ON cnt.country_code = adr.address_country_code "
         . "LEFT OUTER JOIN contact_phone AS cp ON cp.contact_id = c.contact_id AND cp.phone_type_id = ". PHONE_TYPE_WORK . " "
         . "LEFT OUTER JOIN contact_phone AS cp_fax ON cp_fax.contact_id = c.contact_id AND cp_fax.phone_type_id = ". PHONE_TYPE_FAX . " "
         . "WHERE ac.status = 'A' AND ac.account_id = ". $this->__account_id ." AND c.contact_id = ". $contact_id ." ";              
         
      return $this->FetchAssoc($this->executeQuery($q));
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
   * GetAccountsUserByRegionRole()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 08 11:59:51 PST 2006
   */
   function GetAccountsUserByRegionRole($region_id, $role_id)
   {
      $q = "SELECT au.user_id, u.first_name, u.last_name "
         . "FROM account_user AS au "
         . "LEFT OUTER JOIN account AS a ON a.account_id = au.account_id "
         . "LEFT OUTER JOIN country AS c ON c.country_code = a.country_code "
         . "LEFT OUTER JOIN user AS u ON u.login = au.user_id "
         . "WHERE a.status = 'A' AND c.region_id = ". $region_id ." AND au.role_id = ". $role_id ." "
         . "GROUP BY au.user_id "
         . "ORDER BY first_name, last_name ";
      return $this->executeQuery($q);
   }

   /**
   * GetAccountsByUser()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 08 12:05:37 PST 2006
   */
   function GetAccountsByUser($user_id, $role_id=0)
   {
      $q = "SELECT a.account_id, a.account_name, a.country_code, c.region_id, aat.account_type_id, au.product_id, au.role_id  "
         . "FROM account_user AS au "
         . "LEFT OUTER JOIN account AS a ON a.account_id = au.account_id "
         . "LEFT OUTER JOIN country AS c ON c.country_code = a.country_code "
         . "LEFT OUTER JOIN account_account_type AS aat ON aat.account_id = a.account_id "
         . "WHERE a.status = 'A' AND au.status = 'A' AND au.user_id = ". $user_id ." AND aat.account_type_id IN (1, 2) ";
      if ($role_id) {
         $q .= "AND au.role_id = ". $role_id . " ";
      }
      $q .= "ORDER BY account_type_id, account_name ";
      return $this->executeQuery($q);
   }

   /**
   * UpdateAccount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 08 13:21:41 PST 2006
   */
   function UpdateAccount($account_name, $country_code)
   {
      $q = "UPDATE account "
         . "SET account_name = '". mysql_real_escape_string($account_name) ."', country_code = '". $country_code ."', modified_by = ". $this->created_by .", modified_date = NOW() "
         . "WHERE account_id = ". $this->__account_id;
      return $this->executeQuery($q);
   }

   /**
   * DeleteAccountType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 08 13:24:30 PST 2006
   */
   function DeleteAccountType()
   {
      $q = "DELETE FROM account_account_type "
          . "WHERE account_id = ". $this->__account_id;
      return $this->executeQuery($q);
   }

   /**
   * DeleteAccountSubType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 08 13:25:27 PST 2006
   */
   function DeleteAccountSubType()
   {
      $q = "DELETE FROM account_account_sub_type "
          . "WHERE account_id = ". $this->__account_id;
      return $this->executeQuery($q);
   }

   /**
   * hasProductAccount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Mar 21 16:52:31 PST 2006
   */
   function hasProductAccount($product_id)
   {
   	$q = "SELECT account_identifier "
   	   . "FROM account_product "
   	   . "WHERE status = 'A' AND account_id = ". $this->__account_id ." AND product_id = ". $product_id; // ." AND account_identifier != ''"
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return (!$r || empty($r['account_identifier'])) ? false : true;
   }

   /**
   * GetAccountByProductAccount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 22 08:48:47 PST 2006
   */
   function GetAccountByProductAccount($product_id, $account_identifier)
   {
      $q = "SELECT account_id "
         . "FROM account_product "
         . "WHERE status = 'A' AND product_id = ". $product_id ." AND account_identifier = ". $account_identifier;
      return mysql_fetch_assoc($this->executeQuery($q));
   }

   /**
   * GetAccountById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 22 11:35:48 PST 2006
   */
   function GetAccountById($account_id)
   {
      $q = "SELECT a.account_id, a.account_name "
   	   . "FROM account AS a "
   	   . "LEFT OUTER JOIN account_product AS ap ON ap.account_id = a.account_id "
   	   . "WHERE a.status = 'A' "
   	   . "AND (a.account_id LIKE '%". mysql_real_escape_string($account_id) ."%' OR ap.account_identifier LIKE '%". mysql_real_escape_string($account_id) ."%' ) "
   	   . "GROUP BY a.account_id ";
   	return $this->executeQuery($q);
   }

   /**
   * UpdateAccountStatus()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Mar 23 15:40:58 PST 2006
   */
   function UpdateAccountStatus($status)
   {
   	$q = "UPDATE account SET status = '". $status ."', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE account_id = ". $this->__account_id;
   	return $this->executeQuery($q);
   }

   /**
   * DeleteAccountContact()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Mar 25 10:37:31 PST 2006
   */
   function DeleteAccountContact($contact_id)
   {
   	$q = "DELETE FROM account_contact WHERE contact_id = ". $contact_id ." AND account_id = ". $this->__account_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountProductAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 29 15:25:15 PST 2006
   */
   function GetAccountProductAttr($account_product_id, $account_product_attr_name = "")
   {
   	$q 		= "SELECT account_product_attr_name, ";
   	$where	= "";

   	if($account_product_attr_name != "") {
   		$where 	.= "AND account_product_attr_name = '". $account_product_attr_name ."' ";
   	}

   	if (in_array($account_product_attr_name, $this->compress_attribute)) {
   		$q			.= "UNCOMPRESS(account_product_attr_value) as account_product_attr_value ";
   	}else {
   		$q			.= "account_product_attr_value ";
   	}

   	$q	.= "FROM account_product_attr "
   	  	 . "WHERE status = 'A' AND account_product_id = ". $account_product_id ." ".$where;

   	return $this->executeQuery($q);
   }

   /**
   * GetACcountProductByAccountProductId()
   *
   * @param
   * @param
   * @throws
   * @return
   * @access
   * @since  - Thu May 17 16:28:32 IST 2007
   */
   function GetAccountProductByAccountProductId($account_product_id)
   {
   	$q = "SELECT product_id "
   	   . "FROM account_product "
   	   . "WHERE status = 'A' AND account_product_id = ". $account_product_id;
   	return mysql_fetch_assoc($this->executeQuery($q));
   }

   /**
   * SetAccountProductAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 29 15:26:17 PST 2006
   */
   function SetAccountProductAttr($account_product_id, $account_product_attr_name, $account_product_attr_value)
   {
		$q = "INSERT INTO account_product_attr (account_product_id, account_product_attr_name, account_product_attr_value, created_by, created_date, status) "
   	   . "VALUES (". $account_product_id .", '". $account_product_attr_name ."', ";

   	//check whether attribute name is a compress attribute
		if (in_array($account_product_attr_name, $this->compress_attribute)) {
			$q .= "COMPRESS('". mysql_real_escape_string($account_product_attr_value, $this->_link) ."'), ";
		} else {
			$q	.= "'". mysql_real_escape_string($account_product_attr_value, $this->_link) ."', ";
		}

		$q	.=  $this->created_by .", NOW(), 'A') ";

   	return $this->executeQuery($q);

   }

   /**
   * DeleteAccountProductAttr()
   *
   * @param
   * @param
   * @throws
   * @return
   * @access
   * @since  - Thu May 17 16:04:49 IST 2007
   */
   function DeleteAccountProductAttr($account_product_id, $account_product_attr_name)
   {
   	$q = "UPDATE account_product_attr SET status = 'D', "
   	   . "       modified_date = NOW(), modified_by = ". $this->created_by ." "
   	   . "WHERE account_product_id = ". $account_product_id ." AND account_product_attr_name = '". $account_product_attr_name ."' ";


   	   return $this->executeQuery($q);

   }


   /**
   * isAccountProductAttrSet()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 29 15:28:26 PST 2006
   */
   function isAccountProductAttrSet($account_product_id, $account_product_attr_name)
   {
   	$q = "SELECT account_product_attr_id "
   	   . "FROM account_product_attr "
   	   . "WHERE	status = 'A' AND account_product_id = ". $account_product_id ." AND account_product_attr_name = '". $account_product_attr_name ."' ";
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return ($r) ? true : false;
   }

   /**
   * UpdateAccountProductAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 29 15:30:10 PST 2006
   */
   function UpdateAccountProductAttr($account_product_id, $account_product_attr_name, $account_product_attr_value)
   {
   	$q = "UPDATE account_product_attr ";

   	if(in_array($account_product_attr_name, $this->compress_attribute)) {
   		$q	.= "SET account_product_attr_value = COMPRESS('". mysql_real_escape_string($account_product_attr_value, $this->_link) ."'), ";
   	}else {
   		$q	.= "SET account_product_attr_value = '". mysql_real_escape_string($account_product_attr_value, $this->_link) ."', ";
   	}

   	$q	.= "modified_date = NOW(), modified_by = ". $this->created_by ." "
   	   . "WHERE status = 'A' AND account_product_id = ". $account_product_id ." AND account_product_attr_name = '". $account_product_attr_name ."' ";

   	return $this->executeQuery($q);
   }

   /**
   * GetAccountProductAttrDef()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 29 15:53:43 PST 2006
   */
   function GetAccountProductAttrDef()
   {
   	$q = "SELECT account_product_attr_name, account_product_attr_description "
   	   . "FROM account_product_attr_def "
   	   . "WHERE status = 'A' ";
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountsByRoleTier()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Apr 10 10:51:32 PDT 2006
   */
   function GetAccountsByRoleTier($role_id, $product_id)
   {
   	$q = "SELECT count(a.account_id) AS account_count, l.location_id, l.location_description, aa_tier.account_attr_value AS account_tier  "
   	   . "FROM account AS a "
   	   . "LEFT OUTER JOIN account_attr AS aa_isam ON aa_isam.account_id = a.account_id "
   	   . "            AND aa_isam.account_attr_name = 'GLOBAL_STUDY_HANDLED_BY_AM' "
   	   . "LEFT OUTER JOIN account_attr AS aa_tier ON aa_tier.account_id = a.account_id "
   	   . "            AND aa_tier.account_attr_name = 'GLOBAL_ACCOUNT_TIER_LEVEL' "
   	   . "LEFT OUTER JOIN account_user AS au ON au.account_id = a.account_id
   	                  AND au.role_id = ". $role_id ."
   	                  AND au.product_id = ". $product_id ."
   	                  AND au.status = 'A' "
   	   . "LEFT OUTER JOIN user AS u ON u.login = au.user_id "
   	   . "LEFT OUTER JOIN location AS l ON l.location_id = u.location_id "
   	   . "WHERE a.status = 'A' AND aa_isam.account_attr_value = 1 AND aa_tier.account_attr_value != '' "
   	   . "GROUP BY l.location_id, aa_tier.account_attr_value ";
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountsByRoleTierAm()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Apr 11 07:55:00 PDT 2006
   */
   function GetAccountsByRoleTierAm($role_id, $product_id)
   {
   	$q = "SELECT count(a.account_id) AS account_count, l.location_id, l.location_description, aa_tier.account_attr_value AS account_tier, au.user_id  "
   	   . "FROM account AS a "
   	   . "LEFT OUTER JOIN account_attr AS aa_isam ON aa_isam.account_id = a.account_id "
   	   . "            AND aa_isam.account_attr_name = 'GLOBAL_STUDY_HANDLED_BY_AM' "
   	   . "LEFT OUTER JOIN account_attr AS aa_tier ON aa_tier.account_id = a.account_id "
   	   . "            AND aa_tier.account_attr_name = 'GLOBAL_ACCOUNT_TIER_LEVEL' "
   	   . "LEFT OUTER JOIN account_user AS au ON au.account_id = a.account_id
   	                  AND au.role_id = ". $role_id ."
   	                  AND au.product_id = ". $product_id ."
   	                  AND au.status = 'A' "
   	   . "LEFT OUTER JOIN user AS u ON u.login = au.user_id "
   	   . "LEFT OUTER JOIN location AS l ON l.location_id = u.location_id "
   	   . "WHERE a.status = 'A' AND aa_isam.account_attr_value = 1 AND aa_tier.account_attr_value != '' "
   	   . "GROUP BY au.user_id, aa_tier.account_attr_value ";
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountsTiersNotByAm()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Apr 11 08:43:51 PDT 2006
   */
   function GetAccountsTiersNotByAm()
   {
   	$q = "SELECT count(a.account_id) AS account_count, aa_tier.account_attr_value AS account_tier  "
   	   . "FROM account AS a "
   	   . "LEFT OUTER JOIN account_attr AS aa_isam ON aa_isam.account_id = a.account_id "
   	   . "            AND aa_isam.account_attr_name = 'GLOBAL_STUDY_HANDLED_BY_AM' "
   	   . "LEFT OUTER JOIN account_attr AS aa_tier ON aa_tier.account_id = a.account_id "
   	   . "            AND aa_tier.account_attr_name = 'GLOBAL_ACCOUNT_TIER_LEVEL' "
   	   . "WHERE a.status = 'A' AND aa_isam.account_attr_value = 0 AND aa_tier.account_attr_value != '' "
   	   . "GROUP BY aa_tier.account_attr_value ";
   	return $this->executeQuery($q);
   }

   /**
   * DeleteAccountUser()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu May 18 08:57:49 PDT 2006
   */
   function DeleteAccountUser($account_user_id)
   { 
   	$q = "UPDATE account_user SET status = 'N', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE account_user_id = ". $account_user_id;
   	return $this->executeQuery($q);
   }

   /**
   * DeleteAccountProduct()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu May 18 08:59:06 PDT 2006
   */
   function DeleteAccountProduct($account_product_id)
   {
   	$q = "UPDATE account_product SET status = 'N', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE account_product_id = ". $account_product_id;
   	return $this->executeQuery($q);
   }

   /**
   * DeleteAccountFiles()
   * @param
   * @param
   * @return
   */
   function DeleteAccountFile($account_file_id)
   {
      $q = "UPDATE account_file SET status='D', modified_by='".$this->created_by."', modified_date=NOW() WHERE account_file_id='".$account_file_id."'";
      $this->executeQuery($q);
      return true;
   }
   
   /**
   * DeleteAccountFileFromComment()
   * @param
   * @param
   * @return
   */
   function DeleteAccountFileFromComment($account_file_id)
   {
      $q = "SELECT account_comment_id FROM account_comment WHERE comment_text LIKE '%account_file_id=" . $account_file_id ."%' " ;
      $r = $this->FetchAssoc($this->executeQuery($q));      
      $account_comment_id = $r['account_comment_id'] ;
   
   	  $q = "SELECT LOCATE('File', comment_text) position FROM account_comment  WHERE account_comment_id='$account_comment_id'" ;
      $r = $this->FetchAssoc($this->executeQuery($q));      
      $position = $r['position'] -1  ;
   
   	  $q = "UPDATE account_comment SET comment_text=SUBSTR(comment_text,1,$position), modified_by='".$this->created_by."', modified_date=NOW() WHERE account_comment_id='".$account_comment_id."'";
      $this->executeQuery($q);
      
      return true;
   }
     
   /**
   * GetAccountByProductAccountIdentifier()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu May 18 09:17:30 PDT 2006
   */
   function GetAccountByProductAccountIdentifier($account_identifier)
   {
   	$q = "SELECT account_id "
         . "FROM account_product "
         . "WHERE status = 'A' AND account_identifier = ". $account_identifier;
      $r = $this->FetchAssoc($this->executeQuery($q));
      return ($r) ? $r['account_id'] : false;
   }

   /**
   * SetAccountFile()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu May 18 11:16:20 PDT 2006
   */
   function SetAccountFile($file_type_id, $account_file_name, $account_file_title, $account_file_data, $account_file_size, $account_file_type_id = 1)
   {
   	$q = "INSERT INTO account_file (account_id, file_type_id, account_file_name, account_file_title, account_file_data, account_file_size, "
   	   . "            created_by, created_date, status, account_file_type_id) "
   	   . "VALUES (". $this->__account_id .", '". $file_type_id ."', '". mysql_real_escape_string($account_file_name) ."', "
   	   . "'". mysql_real_escape_string($account_file_title) ."', '". mysql_real_escape_string($account_file_data) ."', "
   	   . "'". mysql_real_escape_string($account_file_size) ."', ". $this->created_by .", NOW(), 'A', $account_file_type_id) ";
   	return $this->executeQuery($q);
   }

   /**
    * SetAccountComment()
    *
    * @param int $account_id
    * @param int $account_comment_type_id
    * @param string $comment_text
    * @return 
    */
   function SetAccountComment($account_id, $account_comment_type_id, $comment_text)
   {	
	
   	$sql = "INSERT INTO account_comment (account_id,account_comment_type_id,comment_text, created_by, created_date, status)"
   			."VALUES (".$account_id.", ".$account_comment_type_id. ",'".$comment_text."', ".$this->created_by .", NOW(), 'A')";

   	return $this->executeQuery($sql);
   	
   }
   
   /**
    * GetAccountComment
    *
    * @param int $account_id
    * @return 
    */
   function GetAccountComment($account_comment_type_id)
   {
   	$sql = "SELECT ac.account_id, ac.account_comment_type_id, ac.comment_text, ac.created_by, ac.created_date, ac.status, u.last_name as created_by_name  "
   	   . "FROM account_comment AS ac "
   	   . "LEFT OUTER JOIN user AS u ON u.login = ac.created_by "
   	   . "WHERE ac.status = 'A' AND ac.account_id =".$this->__account_id." AND ac.account_comment_type_id = ".$account_comment_type_id;

   	return $this->executeQuery($sql);
   	   	
   }
   /**
   * GetAccountFiles()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu May 18 11:28:44 PDT 2006
   */
   function GetAccountFiles()
   {
   	$q = "SELECT af.account_file_id, af.account_id, af.file_type_id, af.account_file_name, af.account_file_title, af.account_file_size, "
   	   . "af.created_by, af.created_date, u.last_name AS created_by_name, account_file_type_id "
   	   . "FROM account_file AS af "
   	   . "LEFT OUTER JOIN user AS u ON u.login = af.created_by "
   	   . "WHERE af.status = 'A' AND af.account_id = ". $this->__account_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountFileByAccountFileId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu May 18 11:57:03 PDT 2006
   */
   function GetAccountFileByAccountFileId($account_file_id)
   {
   	$q = "SELECT af.account_file_id, af.account_id, af.file_type_id, af.account_file_name, af.account_file_title, af.account_file_size, "
   	   . "       af.created_by, af.created_date, af.account_file_data  "
   	   . "FROM account_file AS af "
   	   . "WHERE af.status = 'A' AND af.account_id = ". $this->__account_id . " AND af.account_file_id = ". $account_file_id;
   	return $this->FetchAssoc($this->executeQuery($q));
   }

   /**
   * SetAccountPanelCountry()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 19 13:43:19 PDT 2006
   */
   function SetAccountPanelCountry($country_code, $panel_count, $response_rate)
   {
   	$q = "INSERT INTO account_panel_country (account_id, country_code, panel_count, response_rate, created_by, created_date, status) "
   	   . "VALUES (". $this->__account_id .", '". $country_code ."', '". $panel_count ."', '". $response_rate ."', ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountPanelCountry()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 19 13:47:09 PDT 2006
   */
   function GetAccountPanelCountry()
   {
   	$q = "SELECT apc.account_panel_country_id, apc.account_id, apc.country_code, apc.panel_count, apc.response_rate, c.country_description "
   	   . "FROM account_panel_country AS apc "
   	   . "LEFT JOIN country AS c ON c.country_code = apc.country_code "
   	   . "WHERE apc.status = 'A' AND apc.account_id = ". $this->__account_id;
   	return $this->executeQuery($q);
   }

   /**
   * UpdateAccountPanelCountry()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 19 13:59:07 PDT 2006
   */
   function UpdateAccountPanelCountry($account_panel_country_id, $country_code, $panel_count, $response_rate)
   {
   	$q = "UPDATE account_panel_country SET country_code = '". $country_code ."', panel_count = '". $panel_count ."', response_rate = '". $response_rate ."', "
   	   . "       modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE account_panel_country_id = ". $account_panel_country_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountPanelCountryType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 19 14:41:22 PDT 2006
   */
   function GetAccountPanelCountryTypes()
   {
   	$q = "SELECT account_panel_country_type_id, account_panel_country_type_description, sample_type_id, created_by "
   	   . "FROM account_panel_country_type "
   	   . "WHERE status = 'A' ";
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountPanelCountryType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 19 19:09:34 PDT 2006
   */
   function GetAccountPanelCountryType($account_panel_country_type_id)
   {
   	$q = "SELECT sample_type_id, account_panel_country_type_id, account_panel_country_type_description, country_specific_attr_description "
   	   . "FROM account_panel_country_type "
   	   . "WHERE status = 'A' AND account_panel_country_type_id = ". $account_panel_country_type_id;
   	return $this->FetchAssoc($this->executeQuery($q));
   }

   /**
   * SetAccountPanelCountryType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 19 15:16:26 PDT 2006
   */
   function SetAccountPanelCountryType($account_panel_country_type_description, $sample_type_id, $country_specific_attr_description)
   {
   	$q = "INSERT INTO account_panel_country_type (account_panel_country_type_description, sample_type_id, country_specific_attr_description, created_by, created_date, status) "
   	   . "VALUES ('". mysql_real_escape_string($account_panel_country_type_description) ."', ". $sample_type_id .", ". $country_specific_attr_description .", "
   	   . " " . $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * UpdateAccountPanelCountryType()
   * UpdateAccountPanelCountryType
   * @param
   * @param
   * @return
   * @since  - 11:58:58
   */
   function UpdateAccountPanelCountryType($account_panel_country_type_id, $account_panel_country_type_description, $sample_type_id, $country_specific_attr_description)
   {
   	$q = "UPDATE account_panel_country_type SET account_panel_country_type_description = '". mysql_real_escape_string($account_panel_country_type_description) ."', "
   	   . "       sample_type_id = ". $sample_type_id .", country_specific_attr_description = ". $country_specific_attr_description ." "
   	   . "WHERE account_panel_country_type_id = ". $account_panel_country_type_id;
   	return $this->executeQuery($q);
   }

   /**
   * SetAccountPanelCountryAttrDef()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 19 15:19:03 PDT 2006
   */
   function SetAccountPanelCountryAttrDef($account_panel_country_type_id, $country_code, $account_panel_country_attr_name, $account_panel_country_attr_description)
   {
   	$q = "INSERT INTO account_panel_country_attr_def (account_panel_country_type_id, country_code, account_panel_country_attr_name, account_panel_country_attr_description, "
   	   . "       created_by, created_date, status) "
   	   . "VALUES (". $account_panel_country_type_id .", '". $country_code ."',  '". $account_panel_country_attr_name ."', '". mysql_real_escape_string($account_panel_country_attr_description) ."', "
   	   . "      ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountPanelGroup()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 19 15:48:00 PDT 2006
   */
   function GetAccountPanelGroups($sample_type_id = 1)
   {
   	$q = "SELECT account_panel_group_id, account_panel_group_description, sample_type_id, created_by "
   	   . "FROM account_panel_group "
   	   . "WHERE status = 'A' AND sample_type_id = ". $sample_type_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountPanelGroupsBySampleType()
   *
   * @param
   * @param
   * @return
   * @since  - 14:26:33
   */
   function GetAccountPanelGroupsBySampleType($sample_type_id = 1)
   {
   	$q = "SELECT account_panel_group_id, account_panel_group_description, sample_type_id, created_by "
   	   . "FROM account_panel_group "
   	   . "WHERE status = 'A' AND sample_type_id = ". $sample_type_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountPanelAttrDefByType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 19 15:56:03 PDT 2006
   */
   function GetAccountPanelAttrDefByType($account_panel_country_type_id)
   {
   	$q = "SELECT account_panel_country_attr_def_id, country_code, account_panel_country_attr_name, account_panel_country_attr_description "
   	   . "FROM account_panel_country_attr_def "
   	   . "WHERE status = 'A' AND account_panel_country_type_id = ". $account_panel_country_type_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountPanelAttrDefByTypeCountryCode()
   *
   * @param
   * @param
   * @return
   * @since  - 18:50:43
   */
   function GetAccountPanelAttrDefByTypeCountryCode($account_panel_country_type_id, $country_code)
   {
   	$q = "SELECT account_panel_country_attr_def_id, country_code, account_panel_country_attr_name, account_panel_country_attr_description "
   	   . "FROM account_panel_country_attr_def "
   	   . "WHERE status = 'A' AND account_panel_country_type_id = ". $account_panel_country_type_id . " AND country_code = '". $country_code ."' ";
   	return $this->executeQuery($q);

   }

   /**
   * GetAccountPanelItemType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 19 15:58:11 PDT 2006
   */
   function GetAccountPanelItemType($sample_type_id = 1)
   {
   	$q = "SELECT account_panel_item_type_id, account_panel_item_type_description "
   	   . "FROM account_panel_item_type "
   	   . "WHERE status = 'A' AND sample_type_id = ". $sample_type_id;
   	return $this->executeQuery($q);
   }

   /**
   * SetAccountPanelCountryAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 19 17:54:18 PDT 2006
   */
   function SetAccountPanelCountryAttr($account_panel_country_type_id, $account_panel_group_id, $account_panel_country_attr_name,
   	$country_code, $account_panel_item_type_id, $account_panel_country_attr_count, $account_panel_country_attr_response_rate)
   {
   	$q = "INSERT INTO account_panel_country_attr (account_id, account_panel_country_type_id, account_panel_group_id, account_panel_country_attr_name, "
   	   . "       country_code, account_panel_item_type_id, account_panel_country_attr_count, account_panel_country_attr_response_rate, created_by, created_date, status) "
   	   . "VALUES (". $this->__account_id .", ". $account_panel_country_type_id .", ". $account_panel_group_id .", '". $account_panel_country_attr_name ."', "
   	   . "        '". $country_code ."', ". $account_panel_item_type_id .", ". $account_panel_country_attr_count .", ". $account_panel_country_attr_response_rate .", "
   	   . "         ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountPanelCountryAttrByPanelCountryType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 19 18:03:47 PDT 2006
   */
   function GetAccountPanelCountryAttrByPanelCountryType($account_panel_country_type_id)
   {
   	$q = "SELECT apca.account_panel_country_attr_id, apca.account_panel_country_type_id, apca.account_id, apca.account_panel_group_id, "
   	   . "       apca.account_panel_country_attr_name, apca.country_code, apca.account_panel_item_type_id, apca.account_panel_country_attr_count, "
   	   . "       apca.account_panel_country_attr_response_rate, apct.account_panel_country_type_description, apg.account_panel_group_description, "
   	   . "       apcad.account_panel_country_attr_description, c.country_description, apit.account_panel_item_type_description "
   	   . "FROM account_panel_country_attr AS apca "
   	   . "LEFT OUTER JOIN account_panel_country_type AS apct ON apct.account_panel_country_type_id = apca.account_panel_country_type_id "
   	   . "LEFT OUTER JOIN account_panel_group AS apg ON apg.account_panel_group_id = apca.account_panel_group_id "
   	   . "LEFT OUTER JOIN account_panel_country_attr_def AS apcad ON apcad.account_panel_country_attr_name = apca.account_panel_country_attr_name AND apcad.country_code = apca.country_code "
   	   . "LEFT OUTER JOIN country AS c ON c.country_code = apca.country_code "
   	   . "LEFT OUTER JOIN account_panel_item_type AS apit ON apit.account_panel_item_type_id = apca.account_panel_item_type_id "
   	   . "WHERE apca.status = 'A' AND apca.account_id = ". $this->__account_id ." AND apca.account_panel_country_type_id = ". $account_panel_country_type_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountPanelCountryAttrByAttrId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 19 19:54:57 PDT 2006
   */
   function GetAccountPanelCountryAttrByCountryGroup($country_code, $account_panel_group_id, $account_panel_country_type_id)
   {
   	$q = "SELECT apca.account_panel_country_attr_id, apca.account_panel_country_type_id, apca.account_id, apca.account_panel_group_id, "
   	   . "       apca.account_panel_country_attr_name, apca.country_code, apca.account_panel_item_type_id, apca.account_panel_country_attr_count, "
   	   . "       apca.account_panel_country_attr_response_rate, apct.account_panel_country_type_description, apg.account_panel_group_description, "
   	   . "       apcad.account_panel_country_attr_description, c.country_description, apit.account_panel_item_type_description "
   	   . "FROM account_panel_country_attr AS apca "
   	   . "LEFT OUTER JOIN account_panel_country_type AS apct ON apct.account_panel_country_type_id = apca.account_panel_country_type_id "
   	   . "LEFT OUTER JOIN account_panel_group AS apg ON apg.account_panel_group_id = apca.account_panel_group_id "
   	   . "LEFT OUTER JOIN account_panel_country_attr_def AS apcad ON apcad.account_panel_country_attr_name = apca.account_panel_country_attr_name "
   	   . "LEFT OUTER JOIN country AS c ON c.country_code = apca.country_code "
   	   . "LEFT OUTER JOIN account_panel_item_type AS apit ON apit.account_panel_item_type_id = apca.account_panel_item_type_id "
   	   . "WHERE apca.status = 'A' AND apca.account_id = ". $this->__account_id ." AND apca.country_code = '". $country_code ."' AND apca.account_panel_group_id = ". $account_panel_group_id ." AND apca.account_panel_country_type_id = ". $account_panel_country_type_id;
   	return $this->executeQuery($q);
   }

   /**
   * SetAccountPanelCountryCost()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 19 21:07:21 PDT 2006
   */
   function SetAccountPanelCountryCost($account_panel_country_attr_id, $survey_length, $incidence_rate, $cost_per_complete)
   {
   	$q = "INSERT INTO account_panel_country_cost (account_panel_country_attr_id, survey_length, incidence_rate, "
   	   . "            cost_per_complete, created_by, created_date, status) "
   	   . "VALUES (". $account_panel_country_attr_id .", ". $survey_length .", ". $incidence_rate .", ". $cost_per_complete .", ". $this->created_by .", "
   	   . "        NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountPanelCountryCostByPanelCountryType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 19 21:20:16 PDT 2006
   */
   function GetAccountPanelCountryCostByPanelCountryType($account_panel_country_type_id)
   {
   	$q = "SELECT apca.account_panel_country_attr_id, apca.account_panel_country_type_id, apca.account_id, apca.account_panel_group_id, "
   	   . "       apca.account_panel_country_attr_name, apca.country_code, apca.account_panel_item_type_id, apca.account_panel_country_attr_count, "
   	   . "       apca.account_panel_country_attr_response_rate, apct.account_panel_country_type_description, apg.account_panel_group_description, "
   	   . "       apcad.account_panel_country_attr_description, c.country_description, apit.account_panel_item_type_description,  "
   	   . "       apcc.account_panel_country_cost_id, apcc.survey_length, apcc.incidence_rate, apcc.cost_per_complete "
   	   . "FROM account_panel_country_attr AS apca "
   	   . "LEFT OUTER JOIN account_panel_country_type AS apct ON apct.account_panel_country_type_id = apca.account_panel_country_type_id "
   	   . "LEFT OUTER JOIN account_panel_group AS apg ON apg.account_panel_group_id = apca.account_panel_group_id "
   	   . "LEFT OUTER JOIN account_panel_country_attr_def AS apcad ON apcad.account_panel_country_attr_name = apca.account_panel_country_attr_name "
   	   . "LEFT OUTER JOIN country AS c ON c.country_code = apca.country_code "
   	   . "LEFT OUTER JOIN account_panel_item_type AS apit ON apit.account_panel_item_type_id = apca.account_panel_item_type_id "
   	   . "LEFT OUTER JOIN account_panel_country_cost AS apcc ON apcc.account_panel_country_attr_id = apca.account_panel_country_attr_id "
   	   . "WHERE apca.status = 'A' AND apca.account_id = ". $this->__account_id ." AND apca.account_panel_country_type_id = ". $account_panel_country_type_id;
   	return $this->executeQuery($q);

   }

   /**
   * GetAccountPanelCountryCostByPanelCountryTypeCountryCode()
   *
   * @param
   * @param
   * @return
   * @since  - 18:46:56
   */
   function GetAccountPanelCountryCostByPanelCountryTypeCountryCode($account_panel_country_type_id, $country_code)
   {
   	$q = "SELECT apca.account_panel_country_attr_id, apca.account_panel_country_type_id, apca.account_id, apca.account_panel_group_id, "
   	   . "       apca.account_panel_country_attr_name, apca.country_code, apca.account_panel_item_type_id, apca.account_panel_country_attr_count, "
   	   . "       apca.account_panel_country_attr_response_rate, apct.account_panel_country_type_description, apg.account_panel_group_description, "
   	   . "       apcad.account_panel_country_attr_description, c.country_description, apit.account_panel_item_type_description,  "
   	   . "       apcc.account_panel_country_cost_id, apcc.survey_length, apcc.incidence_rate, apcc.cost_per_complete "
   	   . "FROM account_panel_country_attr AS apca "
   	   . "LEFT OUTER JOIN account_panel_country_type AS apct ON apct.account_panel_country_type_id = apca.account_panel_country_type_id "
   	   . "LEFT OUTER JOIN account_panel_group AS apg ON apg.account_panel_group_id = apca.account_panel_group_id "
   	   . "LEFT OUTER JOIN account_panel_country_attr_def AS apcad ON apcad.account_panel_country_attr_name = apca.account_panel_country_attr_name "
   	   . "LEFT OUTER JOIN country AS c ON c.country_code = apca.country_code "
   	   . "LEFT OUTER JOIN account_panel_item_type AS apit ON apit.account_panel_item_type_id = apca.account_panel_item_type_id "
   	   . "LEFT OUTER JOIN account_panel_country_cost AS apcc ON apcc.account_panel_country_attr_id = apca.account_panel_country_attr_id "
   	   . "WHERE apca.status = 'A' AND apca.account_id = ". $this->__account_id ." AND apca.account_panel_country_type_id = ". $account_panel_country_type_id . " AND apca.country_code = '". $country_code ."' ";
   	return $this->executeQuery($q);
   }

   /**
   * isAccountPanelCountryCostSetByPanelCountryTypeCountryCode()
   *
   * @param
   * @param
   * @return
   * @since  - 19:10:46
   */
   function isAccountPanelCountryCostSetByPanelCountryTypeCountryCode($account_panel_country_type_id, $country_code)
   {
   	$q = "SELECT apca.account_panel_country_attr_id, apca.account_panel_country_type_id, apca.account_id, apca.account_panel_group_id, "
   	   . "       apca.account_panel_country_attr_name, apca.country_code, apca.account_panel_item_type_id, apca.account_panel_country_attr_count, "
   	   . "       apca.account_panel_country_attr_response_rate, apct.account_panel_country_type_description, apg.account_panel_group_description, "
   	   . "       apcad.account_panel_country_attr_description, c.country_description, apit.account_panel_item_type_description,  "
   	   . "       apcc.account_panel_country_cost_id, apcc.survey_length, apcc.incidence_rate, apcc.cost_per_complete "
   	   . "FROM account_panel_country_attr AS apca "
   	   . "LEFT OUTER JOIN account_panel_country_type AS apct ON apct.account_panel_country_type_id = apca.account_panel_country_type_id "
   	   . "LEFT OUTER JOIN account_panel_group AS apg ON apg.account_panel_group_id = apca.account_panel_group_id "
   	   . "LEFT OUTER JOIN account_panel_country_attr_def AS apcad ON apcad.account_panel_country_attr_name = apca.account_panel_country_attr_name "
   	   . "LEFT OUTER JOIN country AS c ON c.country_code = apca.country_code "
   	   . "LEFT OUTER JOIN account_panel_item_type AS apit ON apit.account_panel_item_type_id = apca.account_panel_item_type_id "
   	   . "LEFT OUTER JOIN account_panel_country_cost AS apcc ON apcc.account_panel_country_attr_id = apca.account_panel_country_attr_id "
   	   . "WHERE apca.status = 'A' AND apca.account_id = ". $this->__account_id ." AND apca.account_panel_country_type_id = ". $account_panel_country_type_id . " AND apca.country_code = '". $country_code ."' AND apcc.cost_per_complete IS NOT NULL";
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return ($r) ? true : false;
   }

   /**
   * GetAttrCountByPanelCountryType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 19 21:56:25 PDT 2006
   */
   function GetAttrCountByPanelCountryTypeCountryCode($account_panel_country_type_id, $country_code)
   {
   	$q = "SELECT count(*) AS count "
   	   . "FROM account_panel_country_attr_def "
   	   . "WHERE status = 'A' AND account_panel_country_type_id = ". $account_panel_country_type_id . " AND country_code = '". $country_code ."' ";
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return ($r) ? $r['count'] : 0;
   }

   /**
   * GetAttrCountByPanelCountryType()
   *
   * @param
   * @param
   * @return
   * @since  - 15:04:14
   */
   function GetAttrCountByPanelCountryType($account_panel_country_type_id)
   {
   	$q = "SELECT count(*) AS count "
   	   . "FROM account_panel_country_attr_def "
   	   . "WHERE status = 'A' AND account_panel_country_type_id = ". $account_panel_country_type_id . " "
   	   . "GROUP BY country_code ";
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return ($r) ? $r['count'] : 0;
   }

   /**
   * isAccountPanelCountryAttrSet()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 28 08:10:45 PDT 2006
   */
   function isAccountPanelCountryAttrSet($account_panel_country_type_id, $country_code)
   {
   	$q = "SELECT account_panel_country_attr_id "
   	   . "FROM account_panel_country_attr "
   	   . "WHERE status = 'A' AND account_id = ". $this->__account_id ." AND account_panel_country_type_id = ". $account_panel_country_type_id
   	   . "  AND country_code = '". $country_code ."' ";
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return ($r) ? true : false;
   }

   /**
   * isAccountPanelCountryGroupItemAttrSet()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 28 18:31:36 PDT 2006
   */
   function isAccountPanelCountryGroupItemAttrSet($account_panel_country_type_id, $country_code, $account_panel_group_id,
   	$account_panel_country_attr_name, $account_panel_item_type_id)
   {
   	$q = "SELECT account_panel_country_attr_id "
   	   . "FROM account_panel_country_attr "
   	   . "WHERE status = 'A' AND account_id = ". $this->__account_id ." AND account_panel_country_type_id = ". $account_panel_country_type_id
   	   . "  AND country_code = '". $country_code ."' AND account_panel_group_id = ". $account_panel_group_id
   	   . "  AND account_panel_country_attr_name = '". $account_panel_country_attr_name ."' AND account_panel_item_type_id = ". $account_panel_item_type_id;
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return ($r) ? true : false;
   }

   /**
   * GetAccountPanelCountryAttrByPanelCountryTypeAndCountryCode()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 28 10:10:49 PDT 2006
   */
   function GetAccountPanelCountryAttrByPanelCountryTypeAndCountryCode($account_panel_country_type_id, $country_code)
   {
   	$q = "SELECT apca.account_panel_country_attr_id, apca.account_panel_country_type_id, apca.account_id, apca.account_panel_group_id, "
   	   . "       apca.account_panel_country_attr_name, apca.country_code, apca.account_panel_item_type_id, apca.account_panel_country_attr_count, "
   	   . "       apca.account_panel_country_attr_response_rate, apct.account_panel_country_type_description, apg.account_panel_group_description, "
   	   . "       apcad.account_panel_country_attr_description, c.country_description, apit.account_panel_item_type_description "
   	   . "FROM account_panel_country_attr AS apca "
   	   . "LEFT OUTER JOIN account_panel_country_type AS apct ON apct.account_panel_country_type_id = apca.account_panel_country_type_id "
   	   . "LEFT OUTER JOIN account_panel_group AS apg ON apg.account_panel_group_id = apca.account_panel_group_id "
   	   . "LEFT OUTER JOIN account_panel_country_attr_def AS apcad ON apcad.account_panel_country_attr_name = apca.account_panel_country_attr_name AND apca.country_code = apcad.country_code "
   	   . "LEFT OUTER JOIN country AS c ON c.country_code = apca.country_code "
   	   . "LEFT OUTER JOIN account_panel_item_type AS apit ON apit.account_panel_item_type_id = apca.account_panel_item_type_id "
   	   . "WHERE apca.status = 'A' AND apca.account_id = ". $this->__account_id ." AND apca.account_panel_country_type_id = ". $account_panel_country_type_id
   	   . "  AND apca.country_code = '". $country_code ."' ";
   	return $this->executeQuery($q);

   }

   /**
   * UpdateAccountPanelCountryAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 28 10:49:57 PDT 2006
   */
   function UpdateAccountPanelCountryAttr($account_panel_country_attr_id, $account_panel_country_attr_count, $account_panel_country_attr_response_rate)
   {
   	$q = "UPDATE account_panel_country_attr "
   	   . "SET account_panel_country_attr_count = ". $account_panel_country_attr_count .", account_panel_country_attr_response_rate = ". $account_panel_country_attr_response_rate .", "
   	   . "    modified_by = ". $this->created_by ." , modified_date = NOW() "
   	   . "WHERE account_panel_country_attr_id = ". $account_panel_country_attr_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountPanelCountryTypeCountryList()
   *
   * @param
   * @param
   * @return
   * @since  - 15:42:37
   */
   function GetAccountPanelCountryTypeCountryList($account_panel_country_type_id)
   {
   	$q = "SELECT apctc.country_code, c.country_description "
   	   . "FROM account_panel_country_type_country AS apctc "
   	   . "LEFT OUTER JOIN country AS c ON c.country_code = apctc.country_code "
   	   . "WHERE apctc.status = 'A' AND apctc.account_panel_country_type_id = ". $account_panel_country_type_id;
   	return $this->executeQuery($q);
   }

   /**
   * isAccountPanelCountryCostSet()
   *
   * @param
   * @param
   * @return
   * @since  - 19:00:58
   */
   function isAccountPanelCountryCostSet($account_panel_country_attr_id)
   {

   	$q = "SELECT account_panel_country_cost_id "
   	   . "FROM account_panel_country_cost "
   	   . "WHERE status = 'A' AND account_panel_country_attr_id = ". $account_panel_country_attr_id;
   	$r = $this->FetchAssoc($this->executeQuery($q));

   	return ($r) ? true : false;
   }

   /**
   * UpdateAccountPanelCountryCost()
   *
   * @param
   * @param
   * @return
   * @since  - 21:25:05
   */
   function UpdateAccountPanelCountryCost($account_panel_country_cost_id, $cost_per_complete)
   {
   	$q = "UPDATE account_panel_country_cost SET "
   	   . "   cost_per_complete = '". $cost_per_complete ."', "
   	   . "   modified_by       = ". $this->created_by .", "
   	   . "   modified_date     = NOW() "
   	   . "WHERE account_panel_country_cost_id = ". $account_panel_country_cost_id;
   	return $this->executeQuery($q);
   }

   /**
   * SetAccountPanelCountryTypeCountry()
   *
   * @param
   * @param
   * @return
   * @since  - 11:46:42
   */
   function SetAccountPanelCountryTypeCountry($account_panel_country_type_id, $country_code)
   {
   	$q = "INSERT INTO account_panel_country_type_country (account_panel_country_type_id, country_code, created_by, created_date, status) "
   	   . "VALUES (". $account_panel_country_type_id .", '". $country_code ."', ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * UpdateAccountPanelCountryTypeStatus()
   *
   * @param
   * @param
   * @return
   * @since  - 19:14:06
   */
   function UpdateAccountPanelCountryTypeStatus($account_panel_country_type_id, $status)
   {
   	$q = "UPDATE account_panel_country_type SET status = '". $status ."', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE account_panel_country_type_id = ". $account_panel_country_type_id;
   	return $this->executeQuery($q);
   }

   /**
   * UpdateAccountPanelGroup()
   *
   * @param
   * @param
   * @return
   * @since  - 11:23:14
   */
   function UpdateAccountPanelGroup($account_panel_group_id, $account_panel_group_description, $sample_type_id)
   {
   	$q = "UPDATE account_panel_group SET account_panel_group_description = '". mysql_real_escape_string($account_panel_group_description) ."', "
   	   . "       sample_type_id = ". $sample_type_id .", modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE account_panel_group_id = ". $account_panel_group_id;
   	return $this->executeQuery($q);
   }

   /**
   * SetAccountPanelGroup()
   *
   * @param
   * @param
   * @return
   * @since  - 11:25:27
   */
   function SetAccountPanelGroup($account_panel_group_description, $sample_type_id)
   {
   	$q = "INSERT INTO account_panel_group (account_panel_group_description, sample_type_id, created_by, created_date, status) "
   	   . "VALUES ('". mysql_real_escape_string($account_panel_group_description) ."', ". $sample_type_id .", ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountPanelGroup()
   *
   * @param
   * @param
   * @return
   * @since  - 11:31:43
   */
   function GetAccountPanelGroup($account_panel_group_id)
   {
   	$q = "SELECT account_panel_group_id, account_panel_group_description, sample_type_id, created_by "
   	   . "FROM account_panel_group "
   	   . "WHERE account_panel_group_id = ". $account_panel_group_id;
   	return $this->FetchAssoc($this->executeQuery($q));
   }

   /**
   * GetAccountPanelItemTypes()
   *
   * @param
   * @param
   * @return
   * @since  - 11:51:35
   */
   function GetAccountPanelItemTypes()
   {
   	$q = "SELECT account_panel_item_type_id, account_panel_item_type_description, sample_type_id, created_by, created_date "
   	   . "FROM account_panel_item_type "
   	   . "WHERE status = 'A' ";
   	return $this->executeQuery($q);
   }

   /**
   * UpdateAccountPanelItemType()
   *
   * @param
   * @param
   * @return
   * @since  - 12:05:43
   */
   function UpdateAccountPanelItemType($account_panel_item_type_id, $account_panel_item_type_description, $sample_type_id)
   {
   	$q = "UPDATE account_panel_item_type SET account_panel_item_type_description = '". mysql_real_escape_string($account_panel_item_type_description) ."', "
   	   . "       sample_type_id = ". $sample_type_id .", modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE account_panel_item_type_id = ". $account_panel_item_type_id;
   	return $this->executeQuery($q);
   }

   /**
   * SetAccountPanelItemType()
   *
   * @param
   * @param
   * @return
   * @since  - 12:07:29
   */
   function SetAccountPanelItemType($account_panel_item_type_description, $sample_type_id)
   {
   	$q = "INSERT INTO account_panel_item_type (account_panel_item_type_description, sample_type_id, created_by, created_date, status) "
   	   . "VALUES ('". mysql_real_escape_string($account_panel_item_type_description) ."', ". $sample_type_id .", ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * GetAccountPanelItemTypeById()
   *
   * @param
   * @param
   * @return
   * @since  - 12:11:33
   */
   function GetAccountPanelItemTypeById($account_panel_item_type_id)
   {
   	$q = "SELECT account_panel_item_type_id, account_panel_item_type_description, sample_type_id, created_by "
   	   . "FROM account_panel_item_type "
   	   . "WHERE account_panel_item_type_id = ". $account_panel_item_type_id;
   	return $this->FetchAssoc($this->executeQuery($q));
   }

   /**
   * UpdateAccountPanelGroupStatus()
   *
   * @param
   * @param
   * @return
   * @since  - 12:15:32
   */
   function UpdateAccountPanelGroupStatus($account_panel_group_id, $status)
   {
   	$q = "UPDATE account_panel_group SET status = '". $status ."', modified_date = NOW(), modified_by = ". $this->created_by ." "
   	   . "WHERE account_panel_group_id = ". $account_panel_group_id;
   	return $this->executeQuery($q);
   }

   /**
   * UpdateAccountPanelItemTypeStatus()
   *
   * @param
   * @param
   * @return
   * @since  - 12:22:55
   */
   function UpdateAccountPanelItemTypeStatus($account_panel_item_type_id, $status)
   {
   	$q = "UPDATE account_panel_item_type SET status = '". $status ."', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE account_panel_item_type_id = ". $account_panel_item_type_id;
   	return $this->executeQuery($q);
   }

   /**
    * Provide if Account is Vendor Account Or Not
    *
    * @return boolean
    */
   function isVendor()
   {
      $q = "SELECT account_account_type_id FROM account_account_type WHERE status = 'A' AND account_id = ". $this->__account_id ." AND account_type_id = ". ACCOUNT_TYPE_VENDOR;
      $rs = $this->executeQuery($q);
      return ($this->rows == 1) ? true : false;
   }

   /**
   * GetAccountPanelCountryByCountryCode()
   *
   * @param
   * @param
   * @return
   * @since  - 10:53:06
   */
   function GetAccountPanelCountryByCountryCode($country_code)
   {
   	$q = "SELECT apc.account_id, apc.country_code, apc.panel_count, apc.response_rate, apc.created_by, apc.created_date, apc.status, "
   	   . "       a.account_name "
   	   . "FROM account_panel_country AS apc "
   	   . "LEFT OUTER JOIN account AS a ON a.account_id = apc.account_id "
   	   . "WHERE apc.country_code = '". $country_code ."' AND apc.status = 'A' ";
   	return $this->executeQuery($q);
   }

    /**
    * Check whether user has been assigned to current account
    *
    * @param int $user_id
    * @return boolean
    */
   function IsAccountSetToUser($user_id)
   {
   	$q = "SELECT account_id, user_id "
   	   . "FROM account_user "
   	   . "WHERE account_id = ". $this->__account_id ." AND user_id=". $user_id ." AND status = 'A' ";

   	$account_user = $this->executeQuery($q);
 		if(mysql_num_rows($account_user) != 0) {
 			return true;
 		}
		return false;
   }


   /**
   * GetNullAccountSecurityTokens()
   *
   * @return resource
   * @since  - 22:40:52
   */
   function GetNullNetMRSecurityTokenAccounts()
   {

     $q = "SELECT a.account_id, apa.account_product_id, ap.account_id, ap.product_id, ap.account_identifier FROM account AS a LEFT JOIN ".
          "account_product AS ap ON ap.account_id=a.account_id AND ap.product_id = ".PRODUCT_NETMR." AND ap.status='A' ".
          "LEFT JOIN account_product_attr AS apa ON apa.account_product_id=ap.account_product_id AND ".
          "apa.account_product_attr_name='ACCOUNT_HIERARCHY_SECURITY_TOKEN' AND apa.status='A' WHERE a.status='A' ".
          "AND (apa.account_product_attr_value = '' OR apa.account_product_attr_value IS NULL)";

     return $this->executeQuery($q);
   }

   /**
   * GetAccountAccountingDetails()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - Fri Jun 08 18:54:13 PDT 2007
   */
   function GetAccountAccountingDetails()
   {
		$q =
			"SELECT
			   a.account_id,
				a.account_name AS company_name,
				IF(aat.account_account_type_id IS NOT NULL, 1, 0) AS customer,
				ca_tax_id.country_attr_value AS tax_id,
				ca_tax_registration_number.country_attr_value AS tax_registration_number,
				aa_tax_code.account_attr_value AS tax_code,
				aa_payment_term.account_attr_value AS payment_term,
				aa_ora_account_id.account_attr_value AS cust_account_id,
				aa_approval_required.account_attr_value AS ora_update_acct_approval_required,
				aa_approved.account_attr_value AS ora_update_acct_approved
			FROM account AS a
			LEFT JOIN account_account_type AS aat ON aat.account_id=a.account_id AND aat.account_type_id=1
			LEFT JOIN country AS c ON c.country_code=a.country_code
			LEFT JOIN country_attr AS ca_tax_id ON ca_tax_id.country_id=c.country_id AND ca_tax_id.country_attr_name='ORA_GMI_TAX_ID'
			LEFT JOIN country_attr AS ca_tax_registration_number ON ca_tax_registration_number.country_id=c.country_id AND ca_tax_registration_number.country_attr_name='ORA_GMI_TAX_REGISTRATION'
			LEFT JOIN account_attr AS aa_tax_code ON aa_tax_code.account_id=a.account_id AND aa_tax_code.account_attr_name='ARMC_ORA_TAX_CODE'
			LEFT JOIN account_attr AS aa_payment_term ON aa_payment_term.account_id=a.account_id AND aa_payment_term.account_attr_name='ARMC_ORA_PAYMENT_TERM'
			LEFT JOIN account_attr AS aa_ora_account_id ON aa_ora_account_id.account_id=a.account_id AND aa_ora_account_id.account_attr_name='ARMC_ORA_ACCOUNT_ID'
			LEFT JOIN account_attr AS aa_approval_required ON aa_approval_required.account_id=a.account_id AND aa_approval_required.account_attr_name='ORA_UPDATE_ACCT_APPROVAL_REQUIRED'
			LEFT JOIN account_attr AS aa_approved ON aa_approved.account_id=a.account_id AND aa_approved.account_attr_name='ORA_UPDATE_ACCT_APPROVED'
			WHERE a.account_id='".$this->__account_id."' AND a.status='A'";

		$rst = $this->executeQuery($q);
		return $this->fetchAssoc($rst);
   }

   /**
   * GetAccountContactsAccountingDetails()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - Fri Jun 08 18:58:34 PDT 2007
   */
   function GetAccountContactsAccountingDetails()
   {
		$q =
			"SELECT
				IF(c.contact_type_id=". CONTACT_TYPE_PRIMARY_BILLING ."  , 1, 0) AS prim,
				c.contact_type_id AS contact_type_id,
				ca_site_id.contact_value AS cust_account_site_id,
				ca_contact_id.contact_value AS ora_contact_id,
				c.contact_id,
				c.contact_first_name AS first_name,
				c.contact_last_name AS last_name,
				c.contact_email AS email,
				a.address_street_1 AS address1,
				a.address_street_2 AS address2,
				a.address_city AS city,
				a.address_state AS state,
				a.address_zip AS zip,
				a.address_country_code AS country_code,
				ct.county,
				cnta.country_attr_value AS country,
				cp_phone.contact_phone_number AS phone,
				cp_fax.contact_phone_number AS fax
			FROM account_contact AS ac
			LEFT JOIN contact AS c ON c.contact_id=ac.contact_id
			LEFT JOIN contact_phone AS cp_phone ON cp_phone.contact_id=c.contact_id AND cp_phone.phone_type_id=2 AND cp_phone.status='A'
			LEFT JOIN contact_phone AS cp_fax ON cp_fax.contact_id=c.contact_id AND cp_fax.phone_type_id=4 AND cp_fax.status='A'
			LEFT JOIN address AS a ON a.contact_id=c.contact_id AND a.address_type_id=1 AND a.status='A'
			LEFT JOIN city AS ct ON ct.city_description = a.address_city AND ct.state=a.address_state AND ct.country_code=a.address_country_code AND ct.postal_code=SUBSTR(TRIM(a.address_zip), 1, 5)
			LEFT JOIN contact_attr AS ca_site_id ON ca_site_id.contact_id=c.contact_id AND ca_site_id.contact_attr='ORA_SITE_ID'
			LEFT JOIN contact_attr AS ca_contact_id ON ca_contact_id.contact_id=c.contact_id AND ca_contact_id.contact_attr='ORA_CONTACT_ID'
			LEFT JOIN country AS cnt ON cnt.country_code=a.address_country_code
			LEFT JOIN country_attr AS cnta ON cnta.country_id=cnt.country_id AND cnta.country_attr_name='ISO_2_CODE'
			WHERE ac.account_id='".$this->__account_id."' AND ac.status='A'";
	
		return $this->executeQuery($q);
   }

   function GetAccountsLicenseExpireBefore($before = null) {
   	$q = 
   	"SELECT
         a.account_id, a.account_name, c.country_description, 
         u_am.login AS am_login, CONCAT(u_am.first_name, ' ', u_am.last_name) AS am_name, 
         u_ae.login AS ae_login, CONCAT(u_ae.first_name, ' ', u_ae.last_name) AS ae_name, 
         at.account_type_description, p.product_description, ll.license_level_description, 
         pr.pricing_regime_description, ap.account_identifier, apa.account_product_attr_value AS license_expire_date
       FROM account AS a
       LEFT JOIN country AS c ON c.country_code=a.country_code
       LEFT JOIN account_product AS ap ON ap.account_id=a.account_id AND ap.status='A'
       LEFT JOIN account_account_type AS aat ON aat.account_id = a.account_id AND aat.status='A'
       LEFT JOIN account_type AS at ON at.account_type_id=aat.account_type_id
       LEFT JOIN account_user AS au_am ON au_am.account_id=a.account_id AND au_am.product_id=ap.product_id AND au_am.role_id='".ROLE_PRIMARY_ACCT_MGR."' AND au_am.status='A'
       LEFT JOIN user AS u_am ON u_am.login=au_am.user_id
       LEFT JOIN account_user AS au_ae ON au_ae.account_id=a.account_id AND au_ae.product_id=ap.product_id AND au_ae.role_id='".ROLE_PRIMARY_ACCT_EXEC."' AND au_ae.status='A'
       LEFT JOIN user AS u_ae ON u_ae.login=au_ae.user_id
       LEFT JOIN product AS p ON p.product_id = ap.product_id
       LEFT JOIN license_level AS ll ON ll.license_level_id=ap.license_level_id
       LEFT JOIN pricing_regime AS pr ON pr.pricing_regime_id=ap.pricing_regime_id
       LEFT JOIN account_product_attr AS apa ON apa.account_product_id=ap.account_product_id AND apa.account_product_attr_name='LICENSE_END_DATE' AND apa.status='A'
       WHERE a.status='A'";
   	if (!is_null($before)) {
   		$q .= " AND apa.account_product_attr_value <= '$before'";
   	}
   	$q .= " ORDER BY apa.account_product_attr_value DESC";
   	return $this->executeQuery($q);
   }
   
   /**
	 * Get Campaigns for Account Id
	 *
	 * @param int $account_id
	 * @return recordset
	 */ 
	function GetCampaignsByAccountId() 
	{ 
		$query = "SELECT account_recruiter_campaign_id, campaign_code, campaign_start_date, campaign_end_date, quality_rating, "
		 		. "campaign_description, profit_share, revenue_share, conversion_rate, cost_per_complete, cost_per_acquisition "
		 		. "FROM `account_recruiter_campaign` WHERE `account_id` = " . $this->__account_id;
		 		
		return $this->executeQuery($query);
	}

	 /**
	 * Get Account Contact Details by Email address
	 *
	 * @param string $email_address
	 * @return recordset
	 */	
	function GetAccountContactDetailsByEmail($email_address) 
	{
		$query = 'SELECT * FROM contact as c
					 LEFT JOIN account_contact as ac on c.contact_id = ac.contact_id 
					 WHERE contact_email = "' . $email_address . '"';
				
		return $this->executeQuery($query);
	}
	
 	/**
	 * Get Account Recruiter Campaign Details by Start Date
	 *
	 * @param string $start_date
	 * @return recordset
	 */	
	function GetAccRecCampCodesByStartDate($account_id, $start_date) 
	{
		
		$query = 'SELECT campaign_code FROM account_recruiter_campaign 
									WHERE account_id = "' . $account_id . '" AND campaign_start_date LIKE "' . $start_date . '%"';

		return $this->executeQuery($query);
		
	}
	
}

?>
