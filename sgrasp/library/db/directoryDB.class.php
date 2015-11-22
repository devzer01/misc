<?php


class directoryDB extends dbConnect
{

/**
* directoryDB()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function directoryDB($options = array())
	{
		$this->dbConnect($options);
	}


	/**
	 * Selects all active functional groups.
	 *
	 * @return	resource	A mysql result resource of the format (functional_group_id, functional_group_abbrev, 
	 *                       functional_group_description)
	 * @access
	 * @since
	 */
	function selectFunctionalGroups() {
		$q = "SELECT functional_group_id, functional_group_abbrev, functional_group_description ".
				"FROM functional_group WHERE status='A' ORDER BY functional_group_abbrev ASC";
		return $this->executeQuery($q);
	}
	

	/**
	 * Selects all the active time zones. 
	 *
	 * @return	resource	A mysql result resource of the format (time_zone_id, city_country_name, offset)
	 * @access
	 * @since	EMM 1.1
	 */
	function selectTimeZoneTypes() {
		$q = "SELECT time_zone_id, city_country_name, offset ".
				"FROM time_zone ".
				"WHERE status='A' ".
				"ORDER BY city_country_name ASC";
		return $this->executeQuery($q);
	}
	
	/**
	 * Selects time zones, grouped and concatenated together by offset
	 *
	 * @return 	resource	A mysql result resource of the format (offset, cities)
	 * @access
	 * @since	EMM 1.1
	 */
	function selectTimeZoneGroups() {
		$q = "SELECT offset, GROUP_CONCAT(city_country_name ORDER BY city_country_name) cities ".
				"FROM time_zone ".
				"WHERE status='A' ".
				"GROUP BY offset ".
				"ORDER BY offset ASC";
		return $this->executeQuery($q);
	}

	/**
	 * Selects all the active departments
	 *
	 * @return	resource	A mysql result resource of the format (department_id, department)
	 * @access
	 * @since
	 */
	function selectActiveDepartments()
	{

		$q = "SELECT distinct d.department_id, d.department ".
				"FROM department d " .
				"WHERE d.status = 'A' " .
				"ORDER BY department";
		return $this->executeQuery($q);
	}


/**
* selectUser()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function selectUser($login) {
		$q = "SELECT login FROM user WHERE login=$login";
		return $this->executeQuery($q);
	}
	
	
/**
* addUserFile()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function addUserFile($login, $filename, $description, $size, $data, $admin_id, $mimetype = "image/gif") {
		$q = "SELECT file_type_id FROM file_type WHERE file_type_description='$mimetype' AND status='A'";
		if ($row = mysql_fetch_assoc($this->executeQuery($q))) {
			$file_type_id=$row['file_type_id'];
		} else {
			$q = "INSERT INTO file_type SET file_type_description='$mimetype', created_by=$admin_id";
			$this->executeQuery($q);
			$file_type_id=$this->lastID;
		}
		
		$q = "INSERT INTO user_file (login, file_type_id, user_file_name, user_file_description, user_file_size, user_file_data, created_by, created_date) ".
				"VALUES ($login, $file_type_id, '".mysql_real_escape_string($filename)."', '".mysql_real_escape_string($description)."', $size, '$data', $admin_id, NOW())";
		return $this->executeQuery($q);
	}
	
/**
* selectUserFile()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function selectUserFile($id) {
		$q = "SELECT login, file_type_description, user_file_name, user_file_description, user_file_size, user_file_data data ".
				"FROM user_file uf ".
				"LEFT JOIN file_type ft ON (ft.file_type_id=uf.file_type_id) ".
				"WHERE user_file_id = $id";
		return $this->executeQuery($q);
	}
	

/**
* addPhone()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function addPhone($cid, $phone, $admin_id) {
		$q = "INSERT INTO contact_phone (contact_id, phone_type_id, contact_phone_number, created_by, created_date) ".
				"VALUES ($cid, 2, '".mysql_real_escape_string($phone)."', $admin_id, NOW())";
		return $this->executeQuery($q);
	}

/**
* setDepartmentDescription()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function setDepartmentDescription($dep_id, $description, $admin_id) {
		$q = "REPLACE INTO department (department_id, department, created_by, created_date) VALUES ($dep_id, '".mysql_real_escape_string($description)."', $admin_id, NOW())";
		return $this->executeQuery($q);
	}
	
/**
* setUserField()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function setUserField($login, $field_name, $field_value, $admin_id) {
		$q = "UPDATE user SET $field_name='$field_value', modified_by=$admin_id, modified_date=NOW() WHERE login=$login";
		return $this->executeQuery($q);
	}
	
	
/**
* setSingleAttr()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function setSingleAttr($login, $user_attr, $user_value, $admin_id) {
		$q = "DELETE FROM user_attr WHERE login=$login AND user_attr='$user_attr'";
		$this->executeQuery($q);
		$q = "INSERT INTO user_attr (login, user_attr, user_value, created_by, created_date) ".
				"VALUES ($login, '$user_attr', '".mysql_real_escape_string($user_value)."', $admin_id, NOW())";
		return $this->executeQuery($q);
	}
	
/**
* addFunctionalReportTo()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function addFunctionalReportTo($login, $reports_to, $admin_id) {
		$row = mysql_fetch_assoc($this->executeQuery("SELECT reporting_hierarchy FROM reporting_hierarchy WHERE report_to_login=$reports_to AND reporting_login=$login AND status='A'"));
		if ($row) {
			return $this->executeQuery("UPDATE reporting_hierarchy SET reporting_type_id=1 WHERE reporting_hierarchy=".$row['reporting_hierarchy']);
		}

		$q	= "INSERT INTO reporting_hierarchy ".
				"(reporting_type_id, report_to_login, reporting_login, created_by, created_date) ".
				"VALUES (1, $reports_to, $login, $admin_id, NOW())";
		return $this->executeQuery($q);
	}


	
/**
* addContact()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function addContact($login, $first_name, $last_name, $email, $admin_id) {
		$q = "INSERT INTO contact (contact_type_id, contact_first_name, contact_last_name, contact_email, created_by, created_date) ".
				"VALUES (3, '".mysql_real_escape_string($first_name)."', '".mysql_real_escape_string($last_name)."', '".mysql_real_escape_string($email)."', $admin_id, NOW())";
		$this->executeQuery($q);
		$temp = $this->lastID;
		
		$q = "INSERT INTO user_contact (login, contact_id, created_by, created_date) ".
				"VALUES ($login, $temp, $admin_id, NOW())";
		$this->executeQuery($q);
		return $temp;
	}
	
/**
* addAddress()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function addAddress($cid, $street1, $street2, $city, $state, $prov, $zip, $country, $admin_id) {
		$q = "INSERT INTO address (contact_id, address_type_id, address_street_1, address_street_2, address_city, address_state, address_province, address_zip, address_country_code, created_by, created_date) ".
				"VALUES ($cid, 0, '".mysql_real_escape_string($street1)."', '".mysql_real_escape_string($street2)."', '".mysql_real_escape_string($city)."', '".mysql_real_escape_string($state)."', '".mysql_real_escape_string($prov)."', '".mysql_real_escape_string($zip)."', '".mysql_real_escape_string($country)."', $admin_id, NOW())";
		return $this->executeQuery($q);
	}
	


/**
* userSearch()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function userSearch($val, $max) {
		$val = mysql_real_escape_string($val);
		$q = "SELECT login, first_name, last_name ".
				"FROM user ".
				"WHERE (login LIKE '%$val%' OR first_name LIKE '%$val%' OR last_name LIKE '%$val%') AND status='A' AND login NOT IN (SELECT login FROM user_attr WHERE user_attr='DUMMY_USER') ".
				"ORDER BY login ".
				"LIMIT $max";
		return $this->executeQuery($q);
	}

/**
* selectReportsTo()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function selectReportsTo($login) {
		$q = "SELECT login, first_name, last_name, department, city_country_name ".
				"FROM reporting_hierarchy rh ".
				"JOIN user u ON (rh.report_to_login = u.login) ".
				"LEFT JOIN department d ON (u.department_id = d.department_id) ".
				"LEFT JOIN time_zone tz on (u.time_zone_id = tz.time_zone_id) ".
				"WHERE rh.reporting_login=$login AND u.status='A' AND rh.reporting_type_id=1 AND rh.status='A' ".
				"ORDER BY city_country_name ASC, last_name ASC";
		return $this->executeQuery($q);
	}

/**
* selectReportees()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function selectReportees($login) {
		$q = "SELECT login, first_name, last_name, department, city_country_name ".
				"FROM reporting_hierarchy rh ".
				"JOIN user u ON (rh.reporting_login = u.login) ".
				"LEFT JOIN department d ON (u.department_id = d.department_id) ".
				"LEFT JOIN time_zone tz on (u.time_zone_id = tz.time_zone_id) ".
				"WHERE rh.report_to_login=$login AND u.status='A' AND rh.reporting_type_id=1 AND rh.status='A' ".
				"ORDER BY city_country_name ASC, last_name ASC";
		return $this->executeQuery($q);
	}
	
/**
* selectUserDetail()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function selectUserDetail($login) {
		$q = "SELECT first_name, last_name, email_address, sms_address, department, u.login, city_country_name, offset ".
				"FROM user u ".
				"LEFT JOIN department d ON (u.department_id = d.department_id) ".
				"LEFT JOIN time_zone tz ON (u.time_zone_id = tz.time_zone_id) ".
				"WHERE u.login=$login AND u.status='A' AND tz.status='A'";
		return $this->executeQuery($q);
	}
	
/**
* selectUserAddresses()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function selectUserAddresses($login) {
		$q = "SELECT address_street_1 address_1, address_street_2 address_2, address_city city2, address_state state, ".
					"address_province province, address_zip zipcode, cy.country_description country ".
				"FROM user u ".
				"LEFT JOIN user_contact uc ON (u.login = uc.login) ".
				"LEFT JOIN contact c ON (uc.contact_id = c.contact_id) ".
				"LEFT JOIN address a ON (a.contact_id = uc.contact_id) ".
				"LEFT JOIN country cy ON (a.address_country_code = cy.country_code) ".
				"WHERE u.login=$login AND c.contact_type_id=3 AND u.status='A' AND uc.status='A' AND c.status='A' AND a.status='A'";
		return $this->executeQuery($q);
	}
	
/**
* selectUserCompany()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function selectUserCompany($login) {
		$q = "SELECT vendor_name company ".
				"FROM vendor v ".
				"JOIN user u ON (u.vendor_id = v.vendor_id) ".
				"WHERE u.login=$login AND u.status='A' AND v.status='A'";
		return $this->executeQuery($q);
	}
	
/**
* selectUserAttr()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function selectUserAttr($login, $user_attr) {
		$q = "SELECT user_value FROM user_attr WHERE login=$login AND user_attr='$user_attr' AND status='A'";
		return $this->executeQuery($q);
	}
	
/**
* selectUserAttrByUser()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function selectUserAttrByUser($login) {
		$q = "SELECT user_attr, user_value FROM user_attr WHERE login=$login AND status='A'";
		return $this->executeQuery($q);
	}
	
/**
* selectUserPhones()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function selectUserPhones($login) {
		$q = "SELECT contact_phone_number, phone_country_code, contact_phone_ext, phone_type_description ".
				"FROM user_contact uc ".
				"JOIN contact_phone cp ON (cp.contact_id = uc.contact_id) ".
				"JOIN contact c ON (uc.contact_id = c.contact_id) ".
				"LEFT JOIN phone_type pt ON (cp.phone_type_id = pt.phone_type_id) ".
				"WHERE uc.login=$login AND c.contact_type_id=3 AND uc.status='A' AND cp.status='A' AND c.status='A'";
		return $this->executeQuery($q);
	}
	

/**
* selectUsers()
*
* @param	
* @return	
* @access	
* @since	EMM 1.0
*/
	function selectUsers($order_field, $order_dir, $start, $select, $more_where = "") {
		global $MAX_DISPLAY;
		$whereString = "";
		if ($select != "") $whereString .= "AND $order_field LIKE '".$select."%' ";
		
		$q = "SELECT u.first_name, u.last_name, ua_nick.user_value nickname, u.login ".
				"FROM user u ".
				"LEFT JOIN (SELECT login, user_value FROM user_attr WHERE user_attr='NICKNAME') ua_nick ON (ua_nick.login = u.login) ".
				"LEFT JOIN user_functional_group ufg ON (u.login = ufg.login) ".
				"LEFT JOIN time_zone tz ON (u.time_zone_id = tz.time_zone_id) ".
				"WHERE u.status='A' $whereString $more_where AND u.login NOT IN (SELECT login FROM user_attr WHERE user_attr='DUMMY_USER') ".
				"ORDER BY $order_field $order_dir ".
				"LIMIT $start,$MAX_DISPLAY";
		return $this->executeQuery($q);
	}


	
}



?>