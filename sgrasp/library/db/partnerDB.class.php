<?php
/*
  NH partner dbClass
  03/09/2005
*/

class partnerDB extends dbConnect
{

   var $partner_id = 0;

   private $__id = 0;

/**
* partnerDB()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
   function partnerDB($id)
   {
      $this->partner_id = $id;
      $this->__id = $id;
      parent::dbConnect();
   }

   /**
   * SetPartnerId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Nov 22 20:08:56 PST 2005
   */
   function SetPartnerId($id)
   {
      $this->partner_id = $id;
      $this->__id = $id;
   }

/**
* setMonthEndBilling()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
   function setMonthEndBilling($flag)
   {
      $qry = "UPDATE partners SET month_end_billing = ".$flag." WHERE partner_id = ".$this->partner_id;
      echo $qry;
      return $this->executeQuery($qry);
   }

/**
* getMonthEndBilling()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
   function getMonthEndBilling()
   {
      $qry = "SELECT month_end_billing FROM partners WHERE partner_id = ".$this->partner_id;
      return mysql_result($this->executeQuery($qry),0,'month_end_billing');
   }

/**
* setInvoiceFlag()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
   function setInvoiceFlag($flag)
   {
      $qry = "UPDATE partners SET inv_flag = ".$flag." WHERE partner_id = ".$this->partner_id;
      return $this->executeQuery($qry);
   }

/**
* getInvoiceFlag()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
   function getInvoiceFlag()
   {
      $qry = "SELECT inv_flag FROM partners WHERE partner_id = ".$this->partner_id;
      return mysql_result($this->executeQuery($qry),0,'inv_flag');
   }

/**
* setAltMailName()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
   function setAltMailName($name)
   {
      $qry = "UPDATE partners SET alt_mail_name = '".$name."' WHERE partner_id = ".$this->partner_id;
      return $this->executeQuery($qry);
   }

/**
* getAltMailName()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
   function getAltMailName()
   {
      $qry = "SELECT alt_mail_name FROM partners WHERE partner_id = ".$this->partner_id;
      return mysql_result($this->executeQuery($qry),0,'alt_mail_name');
   }

/**
* validate()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
   function onfile()
   {
      $qry = "SELECT partner_id FROM partners WHERE partner_id = ".$this->partner_id;
      $rs = $this->executeQuery($qry);
      return $this->rows;
   }

/**
* getNetMR()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
   function getNetMR()
   {
      $qry  = "SELECT company_name, acc_executive, acc_manager, country, contact_name, ";
		$qry .= "       contact_street1, contact_street2, contact_city, contact_zipcode, ";
		$qry .= "       contact_state, contact_country, contact_phone, contact_fax, ";
		$qry .= "       contact_email, url, acc_executive2, acc_manager2, acc_programmer, ";
		$qry .= "       acc_programmer2, acc_project, acc_project2, user_id, ";
		$qry .= "       firstname, lastname ";
		$qry .= "FROM partners ";
		$qry .= "WHERE user_id = ".$this->partner_id;

      $config = Hb_Util_Config_SystemConfigReader::Read(); 
	   $link = mysql_connect($config->netmr->dbhost,$config->netmr->dbuser,$config->netmr->dbpassword) or sendAlert('Error Connecting to ...');

      $db = mysql_select_db($config->netmr->dbname);
      $rs = mysql_query($qry,$link) or die (mysql_error());
      mysql_close($link);
      return mysql_fetch_assoc($rs);
   }

/**
* setContact()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
   function setContact($o)
   {
      $q = "INSERT INTO contacts (`partner_id`,`first_name`,`last_name`,`addr_1`,`addr_2`,`phone_1`,`fax`,`email`,`city`,`state`,`zip`,`country`) "
         . " VALUES (".$this->partner_id.",'". mysql_real_escape_string($o['first_name']) ."','". mysql_real_escape_string($o['last_name']) ."',"
         . "'". mysql_real_escape_string($o['contact_street1']) ."','". mysql_real_escape_string($o['contact_street2']) ."','". mysql_real_escape_string($o['contact_phone']) ."',"
         . "'". mysql_real_escape_string($o['contact_fax']) ."','". mysql_real_escape_string($o['contact_email']) ."','". mysql_real_escape_string($o['contact_city']) ."',"
         . "'". mysql_real_escape_string($o['contact_state']) ."','". mysql_real_escape_string($o['contact_zipcode']) ."','". mysql_real_escape_string($o['contact_country']) ."')";
      $rs = $this->executeQuery($q);
      return $this->lastID;
   }

/**
* setPartner()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
   function setPartner($o)
   {
      $qry  = "INSERT INTO partners (`partner_id`,`company_name`,`country_code`,`acct_exec`,`proj_mgr`,`url`,`s_flag`,`partner_type`,`primary_contact`) ";
      $qry .= "VALUES (".$this->partner_id.",'". mysql_real_escape_string($o['company_name']) ."','".$o['country']."',".$o['acc_executive'].",".$o['acc_manager'].",'".$o['url']."','A','P',".$o['contact_id'].")";

      $rs = $this->executeQuery($qry);
      return true;
   }

/**
* assocContact()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
   function assocContact($o)
   {
      $qry = "INSERT INTO partner_contact_old (`p_id`,`c_id`) VALUES (".$this->partner_id.",".$o['contact_id'].")";
      $this->executeQuery($qry);
   }

   /**
   * getContact()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getContact($o)
   {
      $qry = "SELECT first_name,last_name,contact_id FROM contacts WHERE contact_id = ".$o['contact_id'];
      return mysql_fetch_assoc($this->executeQuery($qry));
   }

   /**
   * getAM()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getAM()
   {
      $qry = "SELECT proj_mgr FROM partners WHERE partner_id = ".$this->partner_id;
      return mysql_result($this->executeQuery($qry),0,'proj_mgr');
   }

   /**
   * getAeApprovalFlag()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function getAeApprovalFlag()
	{
		$qry = "SELECT br_needs_ae_approval FROM partners WHERE partner_id = ".$this->partner_id;
		return mysql_result($this->executeQuery($qry),0,'br_needs_ae_approval');
	}

	/**
	* setRequireAeFlag()
	*
	* @param
	* @param
	* @return
	* @throws
	* @access
	* @global
	*/
   function setRequireAeFlag($flag)
   {
      $qry = "UPDATE partners SET br_needs_ae_approval = ".$flag." WHERE partner_id = ".$this->partner_id;
      return $this->executeQuery($qry);
   }

	/**
	* SetRole()
	*
	* @param
	* @param
	* @return
	* @throws
	* @access
	* @global
	*/
	function SetRole($role_id,$login,$created_by)
	{
		$qry  = "INSERT INTO partner_user (partner_id,login,role_id,created_by,created_date,status) ";
		$qry .= "VALUES (".$this->partner_id.",".$login.",".$role_id.",".$created_by.",NOW(),'A') ";
		return $this->executeQuery($qry);
	}


	/**
	* GetRoles()
	*
	* @param
	* @param
	* @return
	* @throws
	* @access
	* @global
	*/
	function GetRoles()
	{
	   $qry  = "SELECT login, role_id ";
	   $qry .= "FROM partner_user ";
	   $qry .= "WHERE status = 'A' AND partner_id = ".$this->partner_id;
	   return $this->executeQuery($qry);
	}

	/**
	* GetPartnerList()
	*
	* @param
	* @param
	* @return
	* @throws
	* @access
	* @global
	*/
	function GetPartnerList($flag)
	{
	   $qry = "SELECT partner_id FROM partners WHERE s_flag = '".$flag."'";
	   return $this->executeQuery($qry);
	}

	/**
	* GetContacts()
	*
	* retrive list of contacts associated with the partner record
	* @param
	* @param
	* @return - recordset
	* @throws
	* @access
	* @global
	* @since  - Wed Jun 08 20:42:51 PDT 2005
	*/
	function GetContacts()
	{
      $qry  = "SELECT DISTINCT c.first_name, c.last_name, c.phone_1 AS phone, c.email, c.contact_id, ";
	   $qry .= "       c.addr_1 AS address, c.contact_id, c.city, c.state, c.zip, CONCAT(c.first_name,' ',c.last_name) AS name ";
	   $qry .= "FROM contacts AS c ";
		$qry .= "JOIN partner_contact_old AS pc ON pc.c_id = c.contact_id ";
		$qry .= "WHERE pc.p_id = ".$this->partner_id." ";
		$qry .= "GROUP BY name ";
		return $this->executeQuery($qry);
	}

	/**
	* GetContactDetail()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Nov 22 20:24:10 PST 2005
	*/
	function GetContactDetail($id)
	{
	   $qry  = "SELECT c.first_name, c.last_name, c.phone_1 AS phone, c.email, c.contact_id, "
	         . "       c.addr_1 AS address, c.contact_id, c.city, c.state, c.zip, CONCAT(c.first_name,' ',c.last_name) AS name "
	         . "FROM contacts AS c "
	         . "WHERE c.contact_id = ". $id;
		return mysql_fetch_assoc($this->executeQuery($qry));
	}

	/**
	* LookupProspectByName()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jul 22 10:08:31 PDT 2005
	*/
	function LookupProspectByName($name)
	{
	   $q = "SELECT partner_id, company_name FROM sfcst_prospects WHERE company_name LIKE '%". mysql_real_escape_string($name) ."%' ORDER BY company_name";
	   return $this->executeQuery($q);
	}

	/**
	* LookupProspectById()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Jul 25 12:13:51 PDT 2005
	*/
	function LookupProspectById($id)
	{
	   $q = "SELECT partner_id, company_name FROM sfcst_prospects WHERE partner_id = ".$id." ORDER BY company_name";
	   return $this->executeQuery($q);
	}

	/**
	* LookupPartnerByName()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jul 22 10:09:50 PDT 2005
	*/
	function LookupPartnerByName($name)
	{
	   $q = "SELECT partner_id, company_name FROM partners WHERE company_name LIKE '%". mysql_real_escape_string($name) ."%' ORDER BY company_name";
	   return $this->executeQuery($q);
	}

	/**
	* LookupPartnerById()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Jul 25 12:13:00 PDT 2005
	*/
	function LookupPartnerById($id)
	{
	   $q = "SELECT partner_id, company_name FROM partners WHERE partner_id LIKE '%".$id."%' ORDER BY company_name";
	   return $this->executeQuery($q);
	}

	/**
	* GetProspectAe()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jul 22 12:29:08 PDT 2005
	*/
	function GetProspectAe($id)
	{
	   $q = "SELECT acct_exec FROM sfcst_prospects WHERE partner_id = ".$id;
	   return mysql_fetch_assoc($this->executeQuery($q));
	}

	/**
	* GetPartnerAeAm()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jul 22 12:29:22 PDT 2005
	*/
	function GetPartnerAeAm($id)
	{
	   $q = "SELECT acct_exec, proj_mgr FROM partners WHERE partner_id = ".$id;
	   return mysql_fetch_assoc($this->executeQuery($q));
	}

	/**
	* GetPartnersByType()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Jul 26 12:29:18 PDT 2005
	*/
	function GetPartnersByType($type)
	{
	   $q = "SELECT partner_id, company_name FROM partners WHERE s_flag != 'N' AND partner_type = '". $type ."' ORDER BY company_name";
	   return $this->executeQuery($q);

	}

	/**
	* GetCountryCodeByPartner()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jul 29 09:36:35 PDT 2005
	*/
	function GetCountryCodeByPartner($id)
	{
	   $q = "SELECT country_code FROM partners WHERE partner_id = '".$id."'";
	   $r = mysql_fetch_assoc($this->executeQuery($q));
	   return $r['country_code'];
	}

	/**
	* CanSendSatSurvey()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Oct 17 13:00:13 PDT 2005
	*/
	function CanSendSatSurvey()
	{
	   $q = "SELECT `key`, `value` FROM partners_attr WHERE `key` = 'SAT_SURVEY' AND `id` = " . $this->partner_id;
	   $r = mysql_fetch_assoc($this->executeQuery($q));
	   return (!$r || $r['value'] == 0) ? true : false;
	}

	/**
	* SetSatSurveyStatus()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Oct 17 13:10:03 PDT 2005
	*/
	function SetSatSurveyStatus($status)
	{
	   $q = "REPLACE INTO partners_attr (`id`, `key`, `value`) VALUES (" . $this->partner_id . ", 'SAT_SURVEY'," . $status . ")";
	   return $this->executeQuery($q);
	}

	/**
	* GetPartnerPricing()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Sat Oct 22 14:23:04 PDT 2005
	*/
	function GetPartnerPricing()
	{
	   $q = "SELECT pipr.pricing_item_id, pipr.pricing_regime_id, pipr.license_level_id, pipr.amount, pipr.is_value, pipr.is_percent,  "
	      . "       pi.pricing_item_description, pd.discount_amount, pd.pricing_discount_type_id, pinft.inflator_amount "
	      . "FROM pricing_item AS pi "
	      . "LEFT OUTER JOIN pricing_item_pricing_regime AS pipr ON pipr.pricing_item_id = pi.pricing_item_id "
	      . "LEFT OUTER JOIN partner_pricing_discount AS pd ON pd.pricing_item_id = pi.pricing_item_id "
	      . "LEFT OUTER JOIN pricing_discount_type AS pdt ON pdt.pricing_discount_type_id = pd.pricing_discount_type_id "
	      . "LEFT OUTER JOIN partner_pricing_inflator AS pinft ON pinft.pricing_item_id = pi.pricing_item_id "
	      . "LEFT OUTER JOIN partners AS p ON p.pricing_regime_id = pipr.pricing_regime_id AND p.license_level_id = pipr.license_level_id "
	      . "WHERE p.partner_id = ". $this->partner_id;
      return $this->executeQuery($q);
	}

	/**
	* GetLicenseLevelId()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Dec 05 15:02:15 PST 2005
	*/
	function GetLicenseLevelId($partner_id)
	{
	   $q = "SELECT license_level_id "
	      . "FROM partners "
	      . "WHERE partner_id = ". $partner_id;
	   $r = mysql_fetch_assoc($this->executeQuery($q));
	   return $r['license_level_id'];
	}

	/**
	* SetPricingRegimeId()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Dec 19 13:54:13 PST 2005
	*/
	function SetPricingRegimeId($pricing_regime_id)
	{
	   $q = "UPDATE partners SET pricing_regime_id = ". $pricing_regime_id ." "
	      . "WHERE partner_id = ". $this->__id;
	   return $this->executeQuery($q);
	}

	/**
	* SetLicenseLevelId()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Dec 19 13:57:47 PST 2005
	*/
	function SetLicenseLevelId($license_level_id)
	{
	   $q = "UPDATE partners SET license_level_id = ". $license_level_id ." "
	      . "WHERE partner_id = ". $this->__id;
	   return $this->executeQuery($q);
	}

	/**
	* SetCountryCode()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Dec 19 13:58:32 PST 2005
	*/
	function SetCountryCode($country_code)
	{
	   $q = "UPDATE partners SET country_code = '". $country_code ."' "
	      . "WHERE partner_id = ". $this->__id;
	   return $this->executeQuery($q);
	}

	/**
	* isAccountCSWatchList()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 21 20:08:09 PST 2005
	*/
	function isAccountCSWatchList()
	{
	   $q = "SELECT value "
	      . "FROM partners_attr "
	      . "WHERE `id` = ". $this->__id ." AND `key` = 'FLAG_CS_WATCH_LIST' ";
	   $r = mysql_fetch_assoc($this->executeQuery($q));

	   return (!$r || $r['value'] == 0) ? false : true;

	}
	
	/**
	* SetProspect()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Dec 22 13:51:17 PST 2005
	*/
	function SetSFMProspect($company_name, $acct_exec, $contact_name, $email_addr, $country)
	{
	  $q = "INSERT INTO sfcst_prospects (company_name, acct_exec, contact_name, email_addr, country) "
	     . "VALUES ('". mysql_real_escape_string($company_name) ."', ". $acct_exec .", '". mysql_real_escape_string($contact_name) ."', "
	     . "        '". mysql_real_escape_string($email_addr) ."', '". $country ."') ";
	  return $this->executeQuery($q);
	}
	
	/**
	* SetProspect()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Dec 22 14:07:20 PST 2005
	*/
	function SetProspect($company_name, $acct_exec, $country_code)
	{
	   $q  = "INSERT INTO partners (partner_id, company_name, acct_exec, country_code , partner_type) "
	       . "VALUES ('".$this->__id."','". mysql_real_escape_string($company_name) ."', ". $acct_exec .", '". $country_code ."','S')";
	   return $this->executeQuery($q);
	}
	
	/**
	* GetPricingRegimeId()
	*
	* @param
	* @param - 
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 28 12:29:38 PST 2005
	*/
	function GetPricingRegimeId($account_id)
	{
	   $q = "SELECT pricing_regime_id "
	      . "FROM partners "
	      . "WHERE partner_id = ". $account_id;
	   $r = mysql_fetch_assoc($this->executeQuery($q));
	   return $r['pricing_regime_id'];
	}
	
	/**
	* GetAccountName()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Sun Jan 08 12:18:34 PST 2006
	*/
	function GetAccountName()
	{
		$q = "SELECT company_name AS account_name "
		   . "FROM partners "
		   . "WHERE partner_id = ". $this->__id;
		$r = mysql_fetch_assoc($this->executeQuery($q));
		return $r['account_name'];
	}
	
	/**
	* GetPartnerCountryCode()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Mar 02 19:53:58 PST 2006
	*/
	function GetPartnerCountryCode($partner_id)
	{
		$q = "SELECT country_code "
		   . "FROM partners "
		   . "WHERE partner_id = ". $partner_id;
		$r = mysql_fetch_assoc($this->executeQuery($q));
		return ($r) ? $r['country_code'] : false;
	}
}
?>
