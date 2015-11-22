<?php

require_once "dbConnect.class.php";

//all common db class should go here

class commonDB extends dbConnect
{

   /**
   * commonDB()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 18:30:42 PDT 2005
   */
   function commonDB()
   {
      parent::dbConnect();
   }

   /**
   * GetFileTypeIdByName()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 18:30:59 PDT 2005
   */
   function GetFileTypeIdByName($name)
   {
      $qry = "SELECT file_type_id FROM file_type WHERE file_type_description = '".$name."'";
      $r = mysql_fetch_assoc($this->executeQuery($qry));
      return ($r) ? $r['file_type_id'] : false;
   }

   /**
   * SetFileType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 18:34:20 PDT 2005
   */
   function SetFileType($description, $login)
   {
      $qry = "INSERT INTO file_type (file_type_description, created_by, created_date, status) "
           . "VALUES ('".$description."', ".$login.", NOW(), 'A')";
      return $this->executeQuery($qry);
   }

   /**
   * SetCountry()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 22 18:50:25 PDT 2005
   */
   function SetCountry($name, $code, $region = 0)
   {
      $q = "INSERT INTO country (country_code, country_description, region_id, created_by, created_date) "
         . "VALUES ('".$code."', '".mysql_real_escape_string($name)."', ".$region.", ".$this->created_by.", NOW()) ";
      return $this->executeQuery($q);
   }

   /**
   * SetCountryAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 22 18:54:22 PDT 2005
   */
   function SetCountryAttr($id, $name, $value)
   {
      $q = "INSERT INTO country_attr (country_id, country_attr_name, country_attr_value, created_by, created_date) "
         . "VALUES (".$id.", '".$name."', '".$value."', ".$this->created_by.", NOW()) ";
      return $this->executeQuery($q);
   }

   /**
   * GetCountryAttr()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - Thu Jun 07 20:45:37 PDT 2007
   */
   function GetCountryAttr($country_code, $country_attr_name)
   {
      $q = "SELECT ca.country_attr_value FROM country_attr AS ca LEFT JOIN country AS c ON c.country_id=ca.country_id WHERE c.country_code='$country_code' AND ca.country_attr_name='$country_attr_name'";
      $rs = $this->executeQuery($q);
      return mysql_result($rs, 0, 0);
   }

   /**
   * GetOldCountryRegion()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 22 18:58:30 PDT 2005
   */
   function GetOldCountryRegion($code)
   {
      $q = "SELECT region FROM country_old WHERE code = '".$code."'";
      return mysql_fetch_assoc($this->executeQuery($q));
   }

   /**
   * UpdateCountryRegion()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 22 19:04:45 PDT 2005
   */
   function UpdateCountryRegion($id, $region)
   {
      $q = "UPDATE country SET region_id = ".$region.", modified_by = ".$this->created_by.", modified_date = NOW() WHERE country_id = ".$id;
      return $this->executeQuery($q);
   }

   /**
   * SetRegion()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 22 19:07:27 PDT 2005
   */
   function SetRegion($code, $name)
   {
      $q = "INSERT INTO region (region_code, region_description, created_by, created_date) "
         . "VALUES ('".$code."', '".$name."', ".$this->created_by.", NOW()) ";
      return $this->executeQuery($q);
   }

   /**
   * SetRegionAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 22 19:10:15 PDT 2005
   */
   function SetRegionAttr($id, $name, $value)
   {
      $q = "INSERT INTO region_attr (region_id, region_attr_name, region_attr_value, created_by, created_date) "
         . "VALUES (".$id.", '".$name."', '".$value."', ".$this->created_by.", NOW())";
      return $this->executeQuery($q);
   }

   /**
   * GetRegionByCode()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 22 19:14:16 PDT 2005
   */
   function GetRegionByCode($code)
   {
      $q = "SELECT region_id FROM region WHERE region_code = '".$code."'";
      return mysql_fetch_assoc($this->executeQuery($q));
   }

   /**
   * GetCustomRegions()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 22 19:35:39 PDT 2005
   */
   function GetCustomRegions()
   {
      $q = "SELECT r.region_id, r.region_description AS region_name "
         . "FROM region AS r "
         . "ORDER BY r.region_description ";

      return $this->executeQuery($q);
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
   * GetCountryList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 22 19:49:00 PDT 2005
   */
   function GetCountryList()
   {
      $q = "SELECT country_code, country_description FROM country ORDER BY sort_order ";
      return $this->executeQuery($q);
   }

   /**
   * GetSourceDepartments()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 22 19:56:35 PDT 2005
   */
   function GetSourceDepartments()
   {
      $q = "SELECT f.functional_group_id, f.functional_group_abbrev, f.functional_group_description "
         . "FROM functional_group AS f "
         . "WHERE f.status = 'A'";
      return $this->executeQuery($q);
   }

   /**
   * GetGroupMembersById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 22 20:05:48 PDT 2005
   */
   function GetGroupMembersById($id)
   {
      $qry  = "SELECT g.login, CONCAT(u.first_name,' ',u.last_name) AS name, u.email_address, u.first_name, u.last_name ";
      $qry .= "FROM user_functional_group AS g ";
      $qry .= "LEFT OUTER JOIN user AS u ON u.login = g.login ";
      $qry .= "WHERE g.status = 'A' AND g.functional_group_id = ".$id;
		$qry .= " ORDER BY functional_group_owner DESC, name ";

		return $this->executeQuery($qry);
   }

   /**
   * GetUsersByRoleId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 22 20:48:45 PDT 2005
   */
   function GetUsersByRoleId($id, $status = 'A')
   {
      $q = "SELECT ur.login, CONCAT(u.first_name,' ',u.last_name) AS name "
         . "FROM user_role AS ur "
         . "LEFT OUTER JOIN user AS u ON ur.login = u.login "
         . "WHERE ur.role_id = ". $id ." AND ur.status = 'A' AND u.status = '" . $status . "'"
         . "ORDER BY name";
      return $this->executeQuery($q);
   }

   /**
   * GetSourceDepartmentMembers()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 17:53:16 PDT 2005
   */
   function GetSourceDepartmentMembers($id)
   {
      $qry  = "SELECT g.login, CONCAT(u.first_name,' ',u.last_name) AS name, u.email_address, u.first_name, u.last_name ";
      $qry .= "FROM user_functional_group AS g ";
      $qry .= "LEFT OUTER JOIN user AS u ON u.login = g.login ";
      $qry .= "WHERE g.status = 'A' AND g.functional_group_id = ".$id;
		$qry .= " ORDER BY functional_group_owner DESC, name ";

      return $this->executeQuery($qry);

   }

   /**
   * GetCountryByRegionId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jul 24 00:05:48 PDT 2005
   */
   function GetCountryByRegionId($id)
   {
      $q = "SELECT c.country_description, c.country_code "
         . "FROM country AS c "
         . "LEFT OUTER JOIN region AS r on r.region_id = c.region_id "
         . "WHERE r.region_id = ".$id." "
         . "ORDER BY c.country_description ";
      return $this->executeQuery($q);
   }

   /**
   * GetRegionByCountryCode()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jul 26 12:57:49 PDT 2005
   */
   function GetRegionByCountryCode($code)
   {
      $q = "SELECT region_id FROM country WHERE country_code = '".$code."'";
      return mysql_fetch_assoc($this->executeQuery($q));
   }

    /**
	* GetContactByID()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Nov 10 14:42:33 PST 2005
	*/
	function GetContactByID($id)
	{
   		$qry = "SELECT c.contact_id, c.partner_id, c.salutation, c.title, c.first_name, c.last_name, c.addr_1, c.addr_2, c.phone_1, c.phone_2, c.fax, c.email, c.city, c.state, c.zip, c.country, country.country_description FROM contacts AS c JOIN country ON c.country=country.country_code WHERE contact_id = '$id'";
   		return $this->executeQuery($qry);
	}


	/**
	* GetContactsForPartner()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Nov 11 12:33:30 PST 2005
	*/
	function GetContactsForPartner($partner_id)
	{
		$qry =
		"SELECT
			DISTINCT CONCAT(cnt.first_name,' ',cnt.last_name) AS name,
			cnt.addr_1 AS address,
			CONCAT(cnt.city,' / ',cnt.state,' / ',cnt.zip) AS citystate,
			country.country_description,
			cnt.contact_id
		FROM contacts AS cnt
		JOIN partner_contact_old AS pct ON pct.c_id = cnt.contact_id
		LEFT JOIN country ON cnt.country=country.country_code
		WHERE pct.p_id = '$partner_id' GROUP BY name, address ";

		return $this->executeQuery($qry);
	}


	/**
	* InsertContact()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Nov 11 14:30:56 PST 2005
	*/
	function InsertContact($o)
	{
		$qry =
		"INSERT INTO contacts
			(partner_id, salutation, first_name, last_name, addr_1, addr_2, phone_1, phone_2, fax,email, city, state, zip, country)
		VALUES ('".$o['partner_id']."','".$o['salutation']."','".$o['first_name']."','".$o['last_name']."','".$o['address_1']."','".$o['address_2']."',
			'".$o['phone_1']."','".$o['phone_2']."','".$o['fax']."','".$o['email']."','".$o['city']."','".$o['state']."','".$o['zip']."','".$o['country']."')";

		$this->executeQuery($qry);
		$contact_id = $this->lastID;

		$qry = "INSERT INTO partner_contact_old (p_id, c_id) VALUES ('".$o['partner_id']."','$contact_id');";
		$this->executeQuery($qry);

		return $contact_id;

	}
	/**
	* UpdateContact()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Nov 11 14:05:28 PST 2005
	*/
	function UpdateContact($contact_id, $contact_type_id, $contact_title, $contact_first_name, $contact_middle_name, $contact_last_name, $contact_suffix, $contact_email)
	{
		$q = "UPDATE contact AS c "
		   . "SET c.contact_type_id = ". $contact_type_id .", contact_title = '". mysql_real_escape_string($contact_title) ."', contact_first_name = '". mysql_real_escape_string($contact_first_name) ."', "
		   . "    c.contact_middle_name = '". mysql_real_escape_string($contact_middle_name) ."', c.contact_last_name = '". mysql_real_escape_string($contact_last_name) ."', "
		   . "    c.contact_suffix = '". mysql_real_escape_string($contact_suffix) ."', c.contact_email = '". mysql_real_escape_string($contact_email) ."' "
		   . "WHERE c.contact_id = ". $contact_id;
		return $this->executeQuery($q);
	}

	/**
	* SetPartnersPrimaryContact()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Nov 11 14:48:07 PST 2005
	*/
	function SetPartnersPrimaryContact($partner_id, $contact_id)
	{
		$qry = "UPDATE partners SET primary_contact = '$contact_id' WHERE partner_id = '$partner_id'";
		$this->executeQuery($qry);

		return true;

	}

	/**
	* GetCountries()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Nov 11 13:49:08 PST 2005
	*/
	function GetCountries()
	{
		$qry = "SELECT country_id, country_description, country_code, region_id, status FROM country ORDER BY country_description";
		return $this->executeQuery($qry);
	}


   /**
   * GetCountryTierByCountryCode()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 17 12:54:19 PST 2005
   */
   function GetCountryTierByCountryCode($country_code)
   {
      $q = "SELECT country_tier_id "
         . "FROM country "
         . "WHERE country_code = '". $country_code ."' ";
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return $r['country_tier_id'];
   }

   /**
   * GetLanguageList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Dec 08 11:49:54 PST 2005
   */
   function GetLanguageList()
   {
      $q = "SELECT l.language_description, l.language_code "
         . "FROM language AS l "
         . "WHERE status = 'A' "
         . "ORDER BY l.sort_order ";
      return $this->executeQuery($q);
   }

   /**
   * GetCountryTierList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 21 09:24:41 PST 2005
   */
   function GetCountryTierList()
   {
      $q = "SELECT country_tier_id, country_tier_description "
         . "FROM country_tier "
         . "WHERE status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * SetCountrySortOrder()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 21 09:48:03 PST 2005
   */
   function SetCountrySortOrder($country_code, $sort_order)
   {
      $q = "UPDATE country "
         . "SET sort_order = ". $sort_order . ", "
         . "    modified_by = ". $this->created_by .", "
         . "    modified_date = NOW() "
         . "WHERE country_code = '". $country_code ."' ";
      return $this->executeQuery($q);
   }

   /**
   * SetCountryTierId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 21 09:49:25 PST 2005
   */
   function SetCountryTierId($country_code, $country_tier_id)
   {
      $q = "UPDATE country "
         . "SET country_tier_id = ". $country_tier_id . ", "
         . "    modified_by = ". $this->created_by .", "
         . "    modified_date = NOW() "
         . "WHERE country_code = '". $country_code ."' ";
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
               $str .= " AND ".$sc." BETWEEN '".$begin."' AND '".$end."' ";
            }
         }
      }
      return $str;
   }

   /**
   * SetLanguage()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jan 03 15:48:48 PST 2006
   */
   function SetLanguage($language_code, $language_description, $sort_order, $status = 'A')
   {
      $q = "INSERT INTO language (language_code, language_description, sort_order, created_by, created_date, status) "
         . "VALUES ('". mysql_real_escape_string($language_code) ."', '". mysql_real_escape_string($language_description) ."', "
         . "        ". $sort_order .", ". $this->created_by .", NOW(), '". $status ."') ";
      return $this->executeQuery($q);
   }

   /**
   * GetModuleListByProductId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Feb 03 10:30:24 PST 2006
   */
   function GetModuleListByProductId($product_id)
   {
      $q = "SELECT m.module_description, m.module_id "
         . "FROM module AS m "
         . "WHERE m.status = 'A' AND m.product_id = ". $product_id;
      return $this->executeQuery($q);
   }

   /**
   * GetModuleServerByModuleCode()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Feb 17 09:12:27 PST 2006
   */
   function GetModuleServerByModuleCode($module_code)
   {
      $q = "SELECT module_server "
         . "FROM module "
         . "WHERE module_code = '". $module_code ."'";
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return $r['module_server'];
   }

   /**
   * GetProductList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Feb 19 22:07:19 PST 2006
   */
   function GetProductList()
   {
   	$q = "SELECT p.product_id, p.product_description "
   	   . "FROM product AS p "
   	   . "WHERE p.status = 'A'";
   	return $this->executeQuery($q);
   }

   /**
   * SetContact()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 20 11:30:26 PST 2006
   */
   function SetContact($contact_type_id, $contact_title, $contact_first_name, $contact_middle_name,
   								$contact_last_name, $contact_suffix, $contact_email)
   {
   	$q = "INSERT INTO contact (contact_type_id, contact_title, contact_first_name, contact_middle_name, contact_last_name, contact_suffix, contact_email, created_by, created_date, status ) "
   	   . "VALUES (". $contact_type_id .", '". mysql_real_escape_string($contact_title) ."', '". mysql_real_escape_string($contact_first_name) ."', '". mysql_real_escape_string($contact_middle_name) ."', "
   	   . "        '". mysql_real_escape_string($contact_last_name) ."', '". mysql_real_escape_string($contact_suffix) ."', '". mysql_real_escape_string($contact_email) ."', ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * SetContactPhone()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 20 11:36:08 PST 2006
   */
   function SetContactPhone($contact_id, $phone_type_id = 0, $phone_country_code, $contact_phone_number, $contact_phone_ext)
   {
   	$q = "INSERT INTO contact_phone (contact_id, phone_type_id, phone_country_code, contact_phone_number, contact_phone_ext, created_by, created_date, status) "
   	   . "VALUES (". $contact_id .", ". $phone_type_id .", '". mysql_real_escape_string($phone_country_code) ."', '". mysql_real_escape_string($contact_phone_number) ."', "
   	   . "        '". mysql_real_escape_string($contact_phone_ext) ."', ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * UpdateContactPhone()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Mar 07 13:40:02 PST 2006
   */
   function UpdateContactPhone($contact_phone_id, $phone_type_id = 0, $phone_country_code, $contact_phone_number, $contact_phone_ext)
   {
   	$q = "UPDATE contact_phone SET phone_type_id = '". mysql_real_escape_string($phone_type_id) ."', phone_country_code = '". mysql_real_escape_string($phone_country_code) ."', "
   	   . "       contact_phone_number = '". mysql_real_escape_string($contact_phone_number) ."', contact_phone_ext = '". mysql_real_escape_string($contact_phone_ext) ."' "
   	   . "WHERE contact_phone_id = ". $contact_phone_id;
   	return $this->executeQuery($q);
   }

   /**
   * SetAddress()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 20 11:39:15 PST 2006
   */
   function SetAddress($contact_id, $address_type_id = 0, $address_street_1, $address_street_2, $address_city, $address_state, $address_province, $address_zip, $address_country_code)
   {
   	$q = "INSERT INTO address (contact_id, address_type_id, address_street_1, address_street_2, address_city, address_state, address_province, address_zip, address_country_code, created_by, created_date, status )"
   	   . "VALUES (". $contact_id .", ". $address_type_id .", '". mysql_real_escape_string($address_street_1) ."', '". mysql_real_escape_string($address_street_2) ."', '". mysql_real_escape_string($address_city) ."', "
   	   . "       '". mysql_real_escape_string($address_state) ."', '". mysql_real_escape_string($address_province) ."', '". mysql_real_escape_string($address_zip) ."', '". mysql_real_escape_string($address_country_code) ."', "
   	   . "        ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * UpdateAddress()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Mar 07 13:32:10 PST 2006
   */
   function UpdateAddress($address_id, $address_type_id = 0, $address_street_1, $address_street_2, $address_city, $address_state, $address_province, $address_zip, $address_country_code)
   {
   	$q = "UPDATE address SET address_type_id = ". $address_type_id .", address_street_1 = '". mysql_real_escape_string($address_street_1) ."', address_street_2 = '". mysql_real_escape_string($address_street_2) ."', "
   	   . "       address_city = '". mysql_real_escape_string($address_city) ."', address_state = '". mysql_real_escape_string($address_state) ."', address_province = '". mysql_real_escape_string($address_province) ."', "
   	   . "       address_zip  = '". mysql_real_escape_string($address_zip) ."', address_country_code = '". mysql_real_escape_string($address_country_code) ."' "
   	   . "WHERE address_id = ". $address_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetRoleList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 20 17:59:26 PST 2006
   */
   function GetRoleList()
   {
   	$q = "SELECT r.role_id, r.role_description "
   	   . "FROM role AS r "
   	   . "WHERE r.status = 'A'";
   	return $this->executeQuery($q);
   }

   /**
   * GetRoleListByModule()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Mar 21 09:48:08 PST 2006
   */
   function GetRoleListByModule($module_code)
   {
   	$q = "SELECT r.role_id, r.role_description "
   	   . "FROM role AS r "
   	   . "LEFT OUTER JOIN module_role AS mr ON mr.role_id = r.role_id "
   	   . "LEFT OUTER JOIN module AS m ON m.module_id = mr.module_id "
   	   . "WHERE r.status = 'A' AND m.module_code = '". $module_code ."'";
   	return $this->executeQuery($q);
   }

   /**
   * GetUsersByLogin()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 20 18:45:50 PST 2006
   */
   function GetUsersByLogin($login)
   {
   	$q = "SELECT first_name, last_name, login, email_address "
   	   . "FROM user "
   	   . "WHERE login LIKE '". $login ."%' AND status = 'A' and user_type_id = 1 ";

   	   return $this->executeQuery($q);
   }

   /**
   * GetUsersByName()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 20 18:46:47 PST 2006
   */
   function GetUsersByName($name)
   {
   	$q = "SELECT first_name, last_name, login, email_address "
   	   . "FROM user "
   	   . "WHERE status = 'A' AND (first_name LIKE '%". mysql_real_escape_string($name) ."%' "
   	   . "	 OR last_name LIKE '%". mysql_real_escape_string($name) ."%') AND user_type_id = 1 ";
   	return $this->executeQuery($q);
   }

   function GetAlertLevels()
   {
      $qry  =  "SELECT alert_level_id, alert_level_description, sort_order ";
      $qry .=  "FROM alert_level ";
      $qry .=  "ORDER BY sort_order ";
      return $this->executeQuery($qry);
   }

   /**
   * GetStudyStatus()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tchan
   */
   function GetStudyStatus()
   {
      $qry  = "SELECT study_status_id, study_status_description ";
      $qry .= "FROM study_status ";
      $qry .= "ORDER BY study_status_description ";
      return $this->executeQuery($qry);
   }

   /**
   * GetStudyStages()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tchan
   */
   function GetStudyStages()
   {
      $qry  = "SELECT study_stage_id, study_stage_description ";
      $qry .= "FROM study_stage ";
      $qry .= "ORDER BY study_stage_description ";

      return $this->executeQuery($qry);

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
   * @since  - Mon Feb 20 11:32:26 PST 2006
   */
   function GetUserAttr($user_id, $attr_name)
   {
      $q = "SELECT `user_value` FROM `user_attr` WHERE `login`='$user_id' AND `user_attr`='$attr_name' AND `status`='A'";
      $rst = $this->executeQuery($q);
      return ($this->rows?mysql_result($rst, 0, "user_value"):false);
   }

   /**
   * GetUserDetails()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 20 12:23:40 PST 2006
   */
   function GetUserDetails($user_id)
   {
      $q =
      "SELECT
         u.user_id,
         u.login,
         u.first_name,
         u.last_name,
         u.department_id,
         dep.department AS department_description,
         u.division_id,
         d.division_description,
         u.function_id,
         f.function_description,
         u.vendor_id,
         u.email_address,
         u.time_zone_id
      FROM user AS u
      LEFT JOIN department AS dep ON dep.department_id = u.department_id
      LEFT JOIN division AS d ON d.division_id = u.division_id
      LEFT JOIN function AS f ON f.function_id = u.function_id
      WHERE `login`='$user_id' AND u.status='A'";
      $rst = $this->executeQuery($q);
      return mysql_fetch_assoc($rst);
   }

   /**
   * GetDepartmentAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 20 12:37:47 PST 2006
   */
   function GetDepartmentAttr($department_id, $attr_name)
   {
      $q = "SELECT `department_attr_value` FROM `department_attr` WHERE `department_id`='$department_id' AND `department_attr_name`='$attr_name' AND `status`='A'";
      $rst = $this->executeQuery($q);
      return ($this->rows?mysql_result($rst, 0, "department_attr_value"):false);
   }

   /**
   * GetVendorAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 20 12:39:22 PST 2006
   */
   function GetVendorAttr($vendor_id, $attr_name)
   {
      $q = "SELECT `vendor_value` FROM `vendor_attr` WHERE `vendor_id`='$vendor_id' AND `vendor_attr_name`='$attr_name' AND `status`='A'";
      $rst = $this->executeQuery($q);
      return ($this->rows?mysql_result($rst, 0, "vendor_value"):false);
   }

   /**
   * GetCityDetails()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 20 13:01:22 PST 2006
   */
   function GetCityDetails($postal_code, $country_code)
   {
      $q = "
      SELECT
         city_id,
         city_description,
         state,
         province,
         county,
         postal_code,
         country_code,
         created_by,
         created_date,
         modified_by,
         modified_date
      FROM city
      WHERE postal_code='$postal_code' AND country_code='$country_code' AND status='A'";
      $rst = $this->executeQuery($q);
      return mysql_fetch_assoc($rst);
   }

   /**
   * GetCronServiceStatus()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Feb 22 10:11:31 PST 2006
   */
   function GetCronServiceStatus($cron_job)
   {
      $q = "SELECT `value` FROM `cron_service` WHERE `key`='$cron_job'";
      $rst = $this->executeQuery($q);
      return ($this->rows?mysql_result($rst, 0, "value"):false);
   }

   /**
   * GetRegionAttrValue()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Feb 28 13:35:38 PST 2006
   */
   function GetRegionAttrValue($region_id, $region_attr_name)
   {
   	$q = "SELECT region_attr_value "
   	   . "FROM region_attr "
   	   . "WHERE region_id = ". $region_id . " AND region_attr_name = '". $region_attr_name ."' ";
   	$r = mysql_fetch_assoc($this->executeQuery($q));
   	return ($r) ? $r['region_attr_value'] : false;
   }

   /**
   * GetLicenseLevels()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 01 14:51:49 PST 2006
   */
   function GetLicenseLevels()
   {
   	$q = "SELECT ll.license_level_id, ll.license_level_description "
   	   . "FROM license_level AS ll "
   	   . "WHERE ll.status = 'A' ";
   	return $this->executeQuery($q);

   }

   /**
   * GetPricingRegimes()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 01 14:51:52 PST 2006
   */
   function GetPricingRegimes()
   {
   	$q = "SELECT pr.pricing_regime_id, pr.pricing_regime_description "
   	   . "FROM pricing_regime AS pr "
   	   . "WHERE pr.status = 'A'";
   	return $this->executeQuery($q);
   }

   /**
   * GetRegionCountryTier()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Mar 02 19:58:09 PST 2006
   */
   function GetRegionCountryTier($region_id, $country_code)
   {
   	$q = "SELECT country_tier_id "
   	   . "FROM region_country_tier "
   	   . "WHERE status = 'A' AND region_id = ". $region_id ." AND country_code = '". $country_code ."' ";
   	$r = mysql_fetch_assoc($this->executeQuery($q));
   	return ($r) ? $r['country_tier_id'] : false;
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
   * @since  - Wed Mar 08 11:39:20 PST 2006
   */
   function SetContactAttr($contact_id, $contact_attr, $contact_value)
   {
      $q = "INSERT INTO contact_attr (contact_id, contact_attr, contact_value, created_by, created_date, status) "
         . "VALUES (". $contact_id .", '". mysql_real_escape_string($contact_attr) ."', '". mysql_real_escape_string($contact_value) ."', "
         . "        ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
   }

   /**
   * UpdateContactAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 08 11:45:47 PST 2006
   */
   function UpdateContactAttr($contact_id, $contact_attr, $contact_value)
   {
      $q = "UPDATE contact_attr "
         . "SET contact_value = '". mysql_real_escape_string($contact_value) ."', modified_by = ". $this->created_by .", modified_date = NOW() "
         . "WHERE contact_id = ". $contact_id ." AND contact_attr = '". $contact_attr ."' ";
      return $this->executeQuery($q);
   }

   /**
   * GetContactAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 08 11:47:37 PST 2006
   */
   function GetContactAttr($contact_id, $contact_attr)
   {
      $q = "SELECT contact_value "
         . "FROM contact_attr "
         . "WHERE contact_id = ". $contact_id ." AND contact_attr = '". $contact_attr ."' ";
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return ($r) ? $r['contact_value'] : false;
   }

   /**
   * GetContactPhoneByPhoneType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Mar 08 21:40:26 PST 2006
   */
   function GetContactPhoneByPhoneType($contact_id, $phone_type_id)
   {
   	$q = "SELECT cp.phone_type_id, cp.contact_phone_number "
   	   . "FROM contact_phone AS cp "
   	   . "WHERE cp.contact_id = ". $contact_id ." AND cp.phone_type_id = ". $phone_type_id;
   	return $this->FetchAssoc($this->executeQuery($q));
   }

      /**
   * GetRegionDetails()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 03 17:46:50 PST 2006
   */

   function GetRegionDetails($region_id)
   {
      $q = "SELECT region_id, region_description, region_code, created_by, created_date, modified_by, modified_date FROM region WHERE region_id='$region_id' AND status='A'";
      return mysql_fetch_assoc($this->executeQuery($q));
   }



   /**
   * GetReportsTo()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Feb 17 16:31:26 PST 2006
   */
   function GetReportsTo($user_id)
   {
                        $qReportsTo =
                        "SELECT
                            report.login
      FROM user AS emp
      LEFT OUTER JOIN reporting_hierarchy AS rh ON rh.reporting_login = emp.login
      LEFT OUTER JOIN user AS report ON report.login = rh.report_to_login
      WHERE emp.login = '$user_id'";
      return $this->executeQuery($qReportsTo);
                        //return mysql_fetch_array($rsReportsTo,MYSQL_ASSOC);
   }

   /**
   * DeleteContact()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Mar 25 10:38:42 PST 2006
   */
   function DeleteContact($contact_id)
   {
   	$q = "UPDATE contact SET status = 'D', modified_by = ". $this->created_by .", modified_date = NOW() WHERE contact_id = ". $contact_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetPortletList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Mar 26 08:42:56 PST 2006
   */
   function GetPortletList($display_mask = array(1, 3))
   {
   	$q = "SELECT p.portlet_id, p.module_code, p.portlet_description, p.sort_order "
   	   . "FROM portlet AS p "
   	   . "WHERE status = 'A' AND display_mask IN  (" . implode(",", $display_mask) . ") "
   	   . "ORDER BY p.sort_order ";
   	return $this->executeQuery($q);
   }

   /**
   * GetPortletsByUser()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Mar 26 08:43:07 PST 2006
   */
   function GetPortletsByUser($user_id)
   {
   	$q = "SELECT pu.portlet_user_id, p.portlet_id, p.module_code, pu.sort_order "
   	   . "FROM portlet_user AS pu "
   	   . "LEFT OUTER JOIN portlet AS p ON p.portlet_id = pu.portlet_id "
   	   . "WHERE pu.status = 'A' AND pu.user_id = ". $user_id ." "
   	   . "ORDER BY pu.sort_order ";
   	return $this->executeQuery($q);
   }

   /**
   * SetUserPortlet()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Mar 26 18:07:32 PST 2006
   */
   function SetUserPortlet($portlet_id, $user_id, $sort_order)
   {
   	$q = "INSERT INTO portlet_user (portlet_id, user_id, sort_order, created_by, created_date, status) "
   	   . "VALUES (". $portlet_id .", ". $user_id .", ". $sort_order .", ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * DeleteUserPortlet()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Mar 26 18:10:45 PST 2006
   */
   function DeleteUserPortlet($user_id)
   {
   	$q = "DELETE FROM portlet_user WHERE user_id = ". $user_id;
   	return $this->executeQuery($q);
   }

   /**
   * GetContactTypes()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 31 19:47:17 PST 2006
   */
   function GetContactTypes()
   {
   	$q = "SELECT contact_type_id, contact_type_description "
   	   . "FROM contact_type "
   	   . "WHERE status = 'A' ";
   	return $this->executeQuery($q);
   }

   /**
   * GetLocationList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Apr 10 13:34:23 PDT 2006
   */
   function GetLocationList()
   {
   	$q = "SELECT location_id, location_description "
   	   . "FROM location "
   	   . "WHERE status = 'A' ";
   	return $this->executeQuery($q);
   }

   /**
   * GetUsers()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Apr 11 08:07:05 PDT 2006
   */
   function GetUsers()
   {
   	$q = "SELECT CONCAT(first_name, ' ', last_name) AS name, login "
   	   . "FROM user ";
   	return $this->executeQuery($q);
   }

   /**
   * GetRoleUsersByRegion()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Apr 28 08:15:57 PDT 2006
   */
   function GetRoleUserCountByFilter($role_id, $filter = '')
   {
   	$q = "SELECT count(*) AS users "
   	   . "FROM user_role AS ru "
   	   . "LEFT OUTER JOIN user AS u ON u.login = ru.login "
   	   . "LEFT OUTER JOIN location AS l ON l.location_id = u.location_id "
   	   . "WHERE ru.status = 'A' AND ru.status = 'A' AND ru.role_id = ". $role_id . " "
   	   . $filter;
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return ($r) ? $r['users'] : 0;
   }

   /**
   * GetCurrencyList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue May 16 21:42:23 PDT 2006
   */
   function GetCurrencyList()
   {
   	$q = "SELECT c.currency_code, c.currency_description "
   	   . "FROM currency AS c "
   	   . "WHERE c.status = 'A' "
   	   . "ORDER BY c.sort_order ";
   	return $this->executeQuery($q);
   }

   /**
   * GetExchangeRate()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed May 17 09:49:11 PDT 2006
   */
   function GetExchangeRate($currency_code, $primary_currency_code = 'USD')
   {
   	$q = "SELECT exchange_rate "
   	   . "FROM currency_rate "
   	   . "WHERE status = 'A' AND primary_currency_code = '". $primary_currency_code ."' AND currency_code = '". $currency_code ."' ";
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return $r['exchange_rate'];
   }
   
   /**
    * Get Currency Multiplier
    * 
    * @return float;
    */
   function GetCurrencyMultiplier($currency_code, $primary_currency_code = 'USD') {
      $q = "SELECT multiplier "
         . "FROM currency_rate "
         . "WHERE status = 'A' AND primary_currency_code = '". $primary_currency_code ."' AND currency_code = '". $currency_code ."' ";
      $r = $this->FetchAssoc($this->executeQuery($q));
      
      return $r['multiplier'];      
   }

   /**
   * GetUnicodeCurrencySymbol()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed May 17 10:32:05 PDT 2006
   */
   function GetUnicodeCurrencySymbol($currency_code)
   {
   	$q = "SELECT symbol_unicode_decimal "
   	   . "FROM currency "
   	   . "WHERE status = 'A' AND currency_code = '". $currency_code ."' ";
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return $r['symbol_unicode_decimal'];
   }

   /**
   * InsertCurrencyRate()
   *
   * @param
   * @throws
   * @return
   * @author - sujith
   * @since  - Thu May 24 12:00:32 IST 2007 +GMT 5.30
   */
   function InsertCurrencyRate($currency_code, $exchange_rate, $primary_currency_code = 'USD')
  {
   	$query = "INSERT INTO currency_rate (primary_currency_code, currency_code, exchange_rate, created_by, created_date, status)"
          	 . "VALUES ('". $primary_currency_code ."', '". $currency_code ."', "
             . $exchange_rate . ", " . $this->created_by . ", NOW(), 'A') ";
   	return $this->executeQuery($query);
   }

   /**
   * UpdateCurrencyRate()
   * @param -
   * @param -
   * @author - sujith
   * @since  - Thu May 24 20:27:16 LKT 2007 +GMT 5.30
   */
   function UpdateCurrencyRate($currency_code, $exchange_rate, $primary_currency_code = 'USD')
   {
      $query = "UPDATE currency_rate "
             . "SET exchange_rate = ". mysql_real_escape_string($exchange_rate) .", modified_by = ". $this->created_by .", modified_date = NOW() "
             . "WHERE primary_currency_code = '" . $primary_currency_code . "' AND currency_code = '" . $currency_code . "'";
      return $this->executeQuery($query);
   }

   /**
   * InsertTaxCode()
   *
   * @param
   * @throws
   * @return
   * @author - sujith
   * @since  - Fri May 25 12:27:20 IST 2007 +GMT 5.30
   */
   function InsertTaxCode($vat_tax_id, $tax_code, $tax_code_description, $tax_rate, $tax_type)
   {
      $query = "INSERT INTO tax_code (vat_tax_id, tax_code, tax_code_description, tax_rate, tax_type, created_by, created_date, status) "
             . "VALUES (". $vat_tax_id . ", '". $tax_code . "', '" . $tax_code_description . "', " . $tax_rate . ", '" . $tax_type . "', " . $this->created_by . ", NOW(), 'A')";

      return $this->executeQuery($query);
   }

   /**
   * UpdateTaxCode()
   *
   * @param
   * @throws
   * @return
   * @author - sujith
   * @since  - Fri May 25 12:58:56 IST 2007 +GMT 5.30
   */
   function UpdateTaxCode($tax_code_id, $vat_tax_id, $tax_code, $tax_code_description, $tax_rate, $tax_type)
   {
      $query = "UPDATE tax_code SET vat_tax_id = " . $vat_tax_id . ", tax_code = '" . $tax_code . "', tax_code_description = '" . $tax_code_description
         	 . "', tax_rate = " . $tax_rate . ", tax_type = '" . $tax_type . "', modified_by = " . $this->created_by
         	 . ", modified_date = Now() WHERE status = 'A' and tax_code_id = " . $tax_code_id ;

      return $this->executeQuery($query);
   }

   /**
   * GetCountryTaxCodes()
   *
   * @param
   * @throws
   * @return
   * @author - sujith
   * @since  - Fri May 25 14:19:43 IST 2007 +GMT 5.30
   */
   function GetCountryTaxCodes($country_code = 'USA')
   {
      $query = "SELECT tc.tax_code_id, tax_code, vat_tax_id, tax_code_description, tax_rate, tax_type FROM tax_code AS tc "
         . "JOIN tax_code_country AS tcc ON tc.tax_code_id = tcc.tax_code_id "
         . "WHERE tcc.status = 'A' and country_code = '" . $country_code . "'";

      return $this->executeQuery($query);
   }

   /**
   * DeleteTaxCode()
   *
   * @param
   * @throws
   * @return
   * @author - sujith
   * @since  - Fri May 25 14:31:22 IST 2007 +GMT 5.30
   */
   function DeleteTaxCode($tax_code)
   {
      $query = "UPDATE tax_code set status = 'D',
      			modified_by = " . $this->created_by . ",
      			modified_date = Now() WHERE tax_code = '" . $tax_code . "'";

      return $this->executeQuery($query);
   }

   /**
   * GetTaxCode()
   *
   * @param
   * @throws
   * @return
   * @author - sujith
   * @since  - Fri May 25 15:11:23 IST 2007 +GMT 5.30
   */
   function GetTaxCode($tax_code)
   {
     $query = "SELECT tax_code_id, vat_tax_id, tax_code, tax_code_description, tax_rate, tax_type FROM tax_code WHERE status = 'A' "
            . "AND tax_code = '" . $tax_code . "'";

     $res   = $this->executeQuery($query);
     return mysql_fetch_assoc($res);
   }

    /**
    * GetTaxCodes()
    * @param -
    * @param -
    * @author - sujith
    * @since  - Wed May 30 13:34:46 LKT 2007 +GMT 5.30
    */
    function GetTaxCodes()
    {
       $query = "SELECT tax_code_id, vat_tax_id, tax_code, tax_code_description, tax_rate, tax_type FROM tax_code WHERE status = 'A' ";
       return $this->executeQuery($query);
    }
   /**
   * DeleteTaxCodeCountry()
   *
   * @param
   * @throws
   * @return
   * @author - sujith
   * @since  - Fri May 25 15:23:55 IST 2007 +GMT 5.30
   */
   function DeleteTaxCodeCountry($tax_code_id)
   {
      $query = "UPDATE tax_code_country set status = 'D',
      			modified_by = " . $this->created_by . ",
      			modified_date = Now() WHERE tax_code_id = " . $tax_code_id;

      return $this->executeQuery($query);
   }

  /**
   * InsertPaymentTerm()
   * @param -
   * @param -
   * @author - sujith
   * @since  - Thu May 31 20:00:57 LKT 2007 +GMT 5.30
   */
   function InsertPaymentTerm($accounting_payment_term_id, $payment_term_name, $payment_term_description)
   {
    	$query = "INSERT INTO payment_term (accounting_payment_term_id, payment_term_name, payment_term_description, created_by, created_date, status) "
             . "VALUES(" . $accounting_payment_term_id . ",'" . mysql_real_escape_string($payment_term_name) . "','" . mysql_real_escape_string($payment_term_description) . "',"
             . $this->created_by . ", Now(), 'A')";

      return $this->executeQuery($query);
   }

  /**
   * UpdatePaymentTerm()
   * @param -
   * @param -
   * @author - sujith
   * @since  - Thu May 31 20:10:22 LKT 2007 +GMT 5.30
   */
   function UpdatePaymentTerm($payment_term_id, $accounting_payment_term_id, $payment_term_name, $payment_term_description)
   {
    	$query = "UPDATE payment_term SET accounting_payment_term_id = " . $accounting_payment_term_id
             . ", payment_term_name = '" . mysql_real_escape_string($payment_term_name) . "', payment_term_description = '" .  mysql_real_escape_string($payment_term_description)
             . "', modified_by = " . $this->created_by . ", modified_date = Now() "
             . "WHERE payment_term_id = " . $payment_term_id . " AND status = 'A'";

      return $this->executeQuery($query);
   }

   /**
   * GetPaymentTerm()
   * @param -
   * @param -
   * @author - sujith
   * @since  - Thu May 31 20:12:57 LKT 2007 +GMT 5.30
   */
   function GetPaymentTerm($payment_term_id)
   {
    	$query = "SELECT accounting_payment_term_id, payment_term_name, payment_term_description FROM payment_term "
          	 . "WHERE payment_term_id = " . $payment_term_id . " AND status = 'A'";

   	$rec   = $this->executeQuery($query);
   	return mysql_fetch_assoc($rec);
   }

   /**
   * DeletePaymentTerm()
   * @param -
   * @param -
   * @author - sujith
   * @since  - Thu May 31 20:16:03 LKT 2007 +GMT 5.30
   */
   function DeletePaymentTerm($payment_term_id)
   {
    	$query = "UPDATE payment_term SET status = 'D', modified_by = " . $this->created_by
          	 . ", modified_date = Now() WHERE payment_term_id = " . $payment_term_id;
       return $this->executeQuery($query);
   }

   /**
   * GetPaymentTermByAccountingPaymentTermID()
   * @param -
   * @param -
   * @author - sujith
   * @since  - Thu May 31 20:19:55 LKT 2007 +GMT 5.30
   */
   function GetPaymentTermByAccountingPaymentTermID($accounting_payment_term_id)
   {
    	$query = "SELECT payment_term_id, payment_term_name, payment_term_description FROM payment_term "
             . "WHERE accounting_payment_term_id = " . $accounting_payment_term_id . " AND status = 'A'";

      $rec   = $this->executeQuery($query);
      return mysql_fetch_assoc($rec);
   }

  /**
   * GetPaymentTerms()
   * @param -
   * @param -
   * @author - sujith
   * @since  - Thu May 31 20:22:53 LKT 2007 +GMT 5.30
   */
   function GetPaymentTerms()
   {
    	$query = "SELECT payment_term_id, accounting_payment_term_id, payment_term_name, payment_term_description FROM payment_term "
          	 . "WHERE status = 'A'";
    	return $this->executeQuery($query);
   }

   /**
   * GetSampleTypes()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Nov 29 08:02:29 PST 2005
   */
   function GetSampleTypes()
   {
      $q = "SELECT sample_type_id, sample_type_description "
         . "FROM sample_type "
         . "WHERE status = 'A' ";
      return $this->executeQuery($q);
   }


     
   /**
   * GetUsersByLocationId
   *
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetUsersByLocationId($location_id, $role_ids=array())
   {
      if (sizeof($role_ids)>0)
      {
      	$q = "SELECT u.login, u.first_name, u.last_name "
            . "FROM user_role AS ur "
            . "LEFT JOIN user AS u ON u.login = ur.login "
            . "WHERE ur.role_id IN (".implode(", ", $role_ids).") AND ur.status='A' AND u.status='A' AND u.location_id='$location_id'";
      }
      else
      {
         $q  = "SELECT u.login, u.first_name, u.last_name "
             . "FROM user AS u "
             . "WHERE u.status = 'A' AND u.location_id='$location_id'";
      }
      return $this->executeQuery($q);
   }

   /**
   * GetUsersByAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - 18:44:46
   */
   function GetUsersByAttr($user_attr, $user_value)
   {
   	$q = "SELECT login FROM user_attr "
   	   . "WHERE status = 'A' AND user_attr = '". $user_attr ."' AND user_value = '". mysql_real_escape_string($user_value) ."' ";
   	return $this->executeQuery($q);
   }

   /**
    * GetProduct()
    *
    * @param
    * @since  - Thu Jun 15 10:41:58 PDT 2006
    */
   function GetProduct($product_id)
   {
      $q = "SELECT product_id, product_description, vendor_id FROM product WHERE product_id=" . $product_id;
      return mysql_fetch_assoc($this->executeQuery($q));
   }

   /**
   * GetRegionsList()
   *
   * @param
   * @param
   * @return
   * @since  - 22:40:52
   */
   function GetRegionsList()
   {
   	$q = "SELECT region_id, region_description "
   	   . "FROM region "
   	   . "WHERE status = 'A' ";
   	return $this->executeQuery($q);
   }

}
