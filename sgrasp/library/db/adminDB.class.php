<?php

/**
 *
 * NOTE: This document was designed with a tab stop of 4
 *
 * TODO: any references to $_SESSION should be removed
 * TODO: also, i don't like using $o as a parameter-- it's too hard to document.
 *
 */


class adminDB extends dbConnect
{

	/**
	 * adminDB()
	 *
	 * @param
	 * @return
	 * @access
	 * @since
	 */
	function adminDB($options = array())
	{
		$this->dbConnect($options);
	}



	/**
	 * Selects the mysql table description of a table
	 *
	 * @param	string		$table		The table to describe
	 * @return	resource	A mysql result resource of the format (Field, Type, Null, Key, Default, Extra)
	 * @access
	 * @since	8/2/2005
	 */
	function selectTableDescription($table) {
		$table = mysql_real_escape_string($table);
		$q = "DESCRIBE $table";
		return $this->executeQuery($q);
	}


	/**
	 * A generic method for selecting data from a table
	 *
	 * @param	string	$table		The table name
	 * @param	mixed	$col_names	The column names to retrieve, as an array, or in the format "col1, col2, col3"
	 * @param	string 	$order_by	The column to order things by
	 * @param	string	$order_dir	The ordering direction (ASC or DESC)
	 * @param	string	$where		Specifies additions to the WHERE clause in the format " AND a='B' AND c='D'"
	 * @param 	int		$start		First argument to LIMIT. If it is set to 'a', the limit argument is omitted.
	 * @param	int		$page		Second argument to LIMIT
	 * @return 	resource	A mysql result resource in the format {$col_names}
	 * @access
	 * @since 	8-8-2005
	 */
	function selectTableData($table, $col_names, $order_by = "", $order_dir = "ASC", $where = "", $start = 0, $page = UI_TABLE_PAGE) {
		if (is_array($col_names)) {
			$col_names = implode(", ", $col_names);
		}
		$q = "SELECT $col_names ";
		$q.= "FROM $table ";
		$q.= "WHERE 1=1$where ";
		if ($order_by) {
			$q.= "ORDER BY $order_by $order_dir ";
		}
		if ($start !== "a") {
			$q.= "LIMIT $start, $page";
		}
		return $this->executeQuery($q);
	}

	/**
	 * Selects a count of rows that would be returned in a query
	 *
	 * @param 	string	$table	The table name.
	 * @param	string	$where	The WHERE statement in the form " AND a='B' AND c='D'"...
	 * @return	resource	A mysql result resource in the format (COUNT(*))
	 * @access
	 * @since	8-8-2005
	 */
	function selectCountTableData($table, $where = "") {
		$q = "SELECT COUNT(*) FROM $table WHERE 1=1$where";
		return $this->executeQuery($q);
	}

	/**
	 * Selects a list of available tables in the database.
	 *
	 * @return	resource	A mysql result resource in the format (Tables_in_{*database name*})
	 * @access
	 * @since 	8-8-2005
	 */
	function selectTables() {
		$q = "SHOW TABLES";
		return $this->executeQuery($q);
	}

	/**
	 * A generic method to update data in a table.
	 *
	 * @param	array	$col_headers	The columns to update, as an array
	 * @param	array	$o				The parsed server variables, containing keys matching the values
	 * 									 in $col_headers that have the desired update values. $o['table'] is
	 * 									 the used as the default table.
	 * @param	string	$primary_key	The primary key of the table.
	 * @param	string	$pkid			The primary key field of the entry to be updated.
	 * @param	int		$admin_id		The login of the user doing the update.
	 * @param	string	$alttable		If set, it is used as the table name.
	 * @return	resource	Should return true on success, will throw an error on failure.
	 * @access
	 * @since 	8-8-2005
	 */
	function updateTableData($col_headers, $o, $primary_key, $pkid, $admin_id, $alttable="") {
		$q = "UPDATE ".($alttable? $alttable:$o['table'])." SET ";
		foreach($col_headers as $val) {
			$q .= "$val='".mysql_real_escape_string($o[$val])."', ";
		}
		$q .= "modified_by=$admin_id, modified_date=NOW() ";
		$q .= "WHERE $primary_key=$pkid";
		return $this->executeQuery($q);
	}

	/**
	 * A generic method for inserting data into a table.
	 *
	 * @param	array	$col_headers	The column names to be used in the insert statement.
	 * @param	array	$o				The parsed server variables, with keys matchine up to
	 * 									 the column headers specified in $col_headers.
	 * @param	int		$admin_id		The login of the user doing the insert.
	 * @return		Should return true on success, will throw an error on failure.
	 * @access
	 * @since 	8-8-2005
	 */
	function insertTableData($col_headers, $o, $admin_id) {
		$q = "INSERT INTO ".$o['table']." SET ";
		foreach($col_headers as $val) {
			$q .= "$val='".mysql_real_escape_string($o[$val])."', ";
		}
		$q .= "created_by=$admin_id, created_date=NOW()";
		return $this->executeQuery($q);
	}

	/**
	 * A generic method for deleting data from a table.
	 *
	 * CAUTION: This actually deletes things from the database!
	 *
	 * @param	string	$table		The table to delete from.
	 * @param	string	$pk			The column name of the primary key of the table.
	 * @param	string 	$pkid		The primary key id of the row to be deleted.
	 * @param	int		$admin_id	The login of the user doing the deletion.
	 * @return		Should return true on success, will throw an error on failure.
	 * @access
	 * @since 	8-8-2005
	 */
	function deleteTableData($table, $pk, $pkid) {
		$q = "DELETE FROM $table WHERE $pk=$pkid";
		return $this->executeQuery($q);
	}








	/**
	 * Selects user file information from the database.
	 * Currently only used to store pictures for the directory.
	 *
	 * @param	int			$id		The user_file_id
	 * @return	resource	A mysql result resource of the format (login, user_file_name, user_file_description,
	 *                        user_file_size, data)
	 * @access
	 * @since
	 */
	function selectUserFile($id) {
		$q = "SELECT login, user_file_name, user_file_description, user_file_size, user_file_data data ".
				"FROM user_file ".
				"WHERE user_file_id = $id";
		return $this->executeQuery($q);
	}

	/**
	 * Selects all active study tasks that are not associated with a study task type.
	 *
	 * @param	int			$study_type_id	The study_type_id
	 * @return	resource	A mysql result resource of the format (study_task_id, study_task_description, primary_task,
	 *                        task_complete_requires_memo)
	 * @access
	 * @since
	 */
	function selectStudyTasksNotInType($study_type_id) {
		$q = "SELECT study_task_id, study_task_description, primary_task, task_complete_requires_memo ".
				"FROM study_task ".
				"WHERE status='A' AND study_task_id NOT IN (".
															"SELECT study_task_id ".
															"FROM study_type_task ".
															"WHERE study_type_id=$study_type_id AND status='A'".
															")";
		return $this->executeQuery($q);
	}


	/**
	 * Selects all active study tasks.
	 *
	 * @return	resource	A mysql result resource of the format (study_task_id, study_task_description, primary_task,
	 *                        task_complete_requires_memo)
	 * @access
	 * @since
	 */
	function selectStudyTasks() {
		$q = "SELECT study_task_id, study_task_description, primary_task, task_complete_requires_memo ".
				"FROM study_task ".
				"WHERE status='A'";
		return $this->executeQuery($q);
	}

	/**
	 * Inserts a study type task into the database.
	 *
	 * @param	int			$study_type_id		The study_type_id
	 * @param	int			$study_task_id		The study_task_id
	 * @param	int			$task_duration		The task duration (used for what?)
	 * @param	int			$admin_id			The login of the user doing the insertion.
	 * @return	boolean		Should return true on success, will throw an error on failure.
	 * @access
	 * @since
	 */
	function insertStudyTypeTask($study_type_id, $study_task_id, $task_duration, $admin_id) {
		$q = "INSERT INTO study_type_task (study_type_id, study_task_id, task_duration, created_by, created_date) ".
				"VALUES ($study_type_id, $study_task_id, $task_duration, $admin_id, NOW())";
		return $this->executeQuery($q);
	}

	/**
	 * Inserts a study task into the database.
	 *
	 * @param	string		$description	The description of the study task
	 * @param	string		$primary		If primary, 1. If not, 0.
	 * @param	string		$memo			If the task completion requires a memo, 1. If not, 0.
	 * @param	int			$admin_id		The login of the user doing the insertion.
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function insertStudyTask($description, $primary, $memo, $admin_id) {
		$q = "INSERT INTO study_task (study_task_description, primary_task, task_complete_requires_memo, created_by, created_date) ".
				"VALUES ('$description', $primary, $memo, $admin_id, NOW())";
		return $this->executeQuery($q);
	}


	/**
	 * Selects user status from the databse.
	 *
	 * @param	int			$login	The login of the user in question.
	 * @return	resource	A mysql result resource of the format (status)
	 * @access
	 * @since
	 */
	function selectUserStatus($login) {
		$q = "SELECT status FROM user WHERE login=$login";
		return $this->executeQuery($q);
	}


	/**
	 * Sets the status (audit) field of a user.
	 *
	 * @param	int			$login		The login of the user to update
	 * @param	string		$status		The new status to give the user
	 * @param	int			$admin_id	The login of the user doing the update
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function setUserStatus($login, $status, $admin_id) {
		$q = "UPDATE user ".
				"SET modified_by=$admin_id, ".
					"modified_date=NOW(), ".
					"status='$status' ".
				"WHERE login=$login";
		return $this->executeQuery($q);
	}



	/**
	 * Deletes a study type task from the database.
	 *
	 * Doesn't actually delete anything, just sets the status to 'D'
	 *
	 * @param	int			$stt_id		The study type task id.
	 * @param	int			$admin_id	The login of the user doing the udpate.
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function deleteStudyTypeTask($stt_id, $admin_id) {
		$q = "UPDATE study_type_task ".
				"SET modified_by=$admin_id, modified_date=NOW(), status='D' ".
				"WHERE study_type_task_id=$stt_id AND status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Updates a study type task, allowing for null values.
	 *
	 * @param	int			$stt_id			The study type task id
	 * @param	int			$type_id		The new type_id
	 * @param	int			$task_id		The new task_id
	 * @param	int			$sort_order		The new sort_order
	 * @param	float		$task_duration	The new task_duration
	 * @param	int			$admin_id		The login of the user doing the update.
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function updateStudyTypeTask($stt_id, $type_id, $task_id, $sort_order, $task_duration, $admin_id) {
		$q = "UPDATE study_type_task ".
				"SET modified_by=$admin_id, modified_date=NOW()";
		if ($type_id !== null) $q.=", study_type_id=$type_id";
		if ($task_id !== null) $q.=", study_task_id=$task_id";
		if ($sort_order !== null) $q.=", sort_order=$sort_order";
		if ($task_duration !== null) $q.=", task_duration=$task_duration";
		$q.= " WHERE study_type_task_id=$stt_id AND status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Updates a study task, allowing for null values.
	 *
	 * See addStudyTask.
	 *
	 * @param	int			$study_task_id
	 * @param	string		$description
	 * @param	string		$primary_task
	 * @param	string		$memo
	 * @param	int			$admin_id
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function updateStudyTask($study_task_id, $description, $primary_task, $memo, $admin_id) {
		if ($description != null) $description=mysql_real_escape_string($description);
		$q = "UPDATE study_task ".
				"SET modified_by=$admin_id, modified_date=NOW()";
		if ($description !== null) $q.=", study_task_description='$description'";
		if ($primary_task !== null) $q.=", primary_task='$primary_task'";
		if ($memo !== null) $q.=", task_complete_requires_memo='$memo'";
		$q.= " WHERE study_task_id=$study_task_id AND status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Selects study tasks associated with a certain study type.
	 *
	 * @param	int			$study_type_id	The study type id.
	 * @return	resource	A mysql result resource of the format (study_type_task_id, study_task_id, study_task_description,
	 *                       primary_task, task_complete_requires_memo, sort_order, task_duration)
	 * @access
	 * @since
	 */
	function selectStudyTasksByStudyType($study_type_id) {
		$q = "SELECT stt.study_type_task_id, stt.study_task_id, st.study_task_description, st.primary_task, st.task_complete_requires_memo, stt.sort_order, stt.task_duration ".
				"FROM study_type_task stt ".
				"LEFT JOIN study_task st ON (stt.study_task_id = st.study_task_id) ".
				"WHERE stt.study_type_id = $study_type_id AND stt.status='A' AND st.status='A' ".
				"ORDER BY stt.sort_order";
		return $this->executeQuery($q);
	}


	/**
	 * Selects all active study types
	 *
	 * @return	resource	A mysql result resource of the format (study_type_id, study_type_description)
	 * @access
	 * @since
	 */
	function selectStudyTypes() {
		$q = "SELECT study_type_id, study_type_description ".
				"FROM study_type ".
				"WHERE status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Selects information about a certain study type.
	 *
	 * @param	int			$study_type_id		The study_type_id
	 * @return	resource	A mysql result resource of the format (study_type_id, study_type_description)
	 * @access
	 * @since
	 */
	function selectStudyType($study_type_id) {
		$q = "SELECT study_type_id, study_type_description ".
				"FROM study_type ".
				"WHERE status='A' AND study_type_id=$study_type_id";
		return $this->executeQuery($q);
	}




	/**
	 * Ha. This function selects information about the CEO, currently login 10497.
	 *
	 * This was only used for testing purposes, and is not used in production anywhere.
	 *
	 * @return	resource	A mysql result resource of the format (login, first_name, last_name, department)
	 * @access
	 * @since
	 */
	function selectCEO() {
		$q = "SELECT login, first_name, last_name, department ".
				"FROM user u ".
				"JOIN department d ON (u.department_id = d.department_id) ".
				"WHERE login=10497";
		return $this->executeQuery($q);
	}


	/**
	 * Searches for users whose logins, first names, or last names contain a value.
	 *
	 * This is the databse call behind the 'quick' user search function invoked by javascript.
	 *
	 * @param	string		$val	The value to look for in a user's first name, last name, or login
	 * @param	int			$max	The maximum number of results to return.
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function userSearch($val, $max) {
		$val = mysql_real_escape_string($val);
		$q = "SELECT login, first_name, last_name ".
				"FROM user u ".
				"WHERE (login LIKE '%$val%' OR first_name LIKE '%$val%' OR last_name LIKE '%$val%') AND (u.status='A' OR u.status='N') ".
				"ORDER BY login ".
				"LIMIT $max";
		return $this->executeQuery($q);
	}




	/**
	 * Removes a reportee relationsihp from the reporting hierarchy
	 *
	 * This does not actually remove anything, it only sets the status to 'D'
	 *
	 * @param	int			$rhid		The reporting_hierarchy_id to remove
	 * @param	int			$admin_id	The user doing the removal.
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function removeReportee($rhid, $admin_id) {
		$q = "UPDATE reporting_hierarchy ".
				"SET status='D', modified_by=$admin_id, modified_date=NOW() ".
				"WHERE reporting_hierarchy=$rhid";
		return $this->executeQuery($q);
	}


	/**
	 * Adds a reportee relationship to the reporting_hierarchy structure.
	 *
	 * @param	int			$reporting_type_id	The reporting_type_id, see reporting_type table.
	 * @param	int		$reporting_login	The login of the reporting user
	 * @param	int		$report_to_login	The login of the user that user reports to
	 * @param	int			$admin_id			The login of the user adding this relationship.
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function addReportee($reporting_type_id, $reporting_login, $report_to_login, $admin_id) {
		$q = "INSERT INTO reporting_hierarchy (reporting_type_id, reporting_login, report_to_login, created_by, created_date) ".
				"VALUES ($reporting_type_id, $reporting_login, $report_to_login, $admin_id, NOW())";
		return $this->executeQuery($q);
	}



	/**
	 * Selects all active reports-to information about a certain user.
	 *
	 * @param	int			$login	The login of the user in question.
	 * @return	resource	A mysql result resource of the format (login, first_name, last_name, reporting_hierarchy,
	 *                       reporting_type_id, reporting_type_description)
	 * @access
	 * @since
	 */
	function selectReportsTo($login) {
		$q = "SELECT login, first_name, last_name, reporting_hierarchy, rh.reporting_type_id, rt.reporting_type_description ".
				"FROM reporting_hierarchy rh ".
				"JOIN user u ON (rh.report_to_login = u.login) ".
				"LEFT JOIN reporting_type rt ON (rh.reporting_type_id = rt.reporting_type_id) ".
				"WHERE rh.reporting_login=$login AND (u.status='A' OR u.status='N') AND rh.status='A' ".
				"ORDER BY last_name ASC";
		return $this->executeQuery($q);
	}


	/**
	 * Selects info an all users that report to a certain user.
	 *
	 * @param	int			$login	The login of the reports-to user.
	 * @return	resource	A mysql result resource of the format (login, first_name, last_name, reporting_hierarchy,
	 *                       reporting_type_id, reporting_type_description)
	 * @access
	 * @since
	 */
	function selectUsersThatReportTo($login) {
		$q = "SELECT login, first_name, last_name, reporting_hierarchy, rh.reporting_type_id, rt.reporting_type_description ".
				"FROM reporting_hierarchy rh ".
				"JOIN user u ON (rh.reporting_login = u.login) ".
				"LEFT JOIN reporting_type rt ON (rh.reporting_type_id = rt.reporting_type_id) ".
				"WHERE rh.report_to_login=$login AND (u.status='A' OR u.status='N') AND rh.status='A' ".
				"ORDER BY last_name ASC";
		return $this->executeQuery($q);
	}


	/**
	 * Selects all active reporting types from the reporting_type table.
	 *
	 * @return	resource	A mysql result resource of the format (reporting_type_id, reporting_type_description)
	 * @access
	 * @since
	 */
	function selectReportingTypes() {
		$q = "SELECT reporting_type_id, reporting_type_description ".
				"FROM reporting_type";
		return $this->executeQuery($q);
	}



	/**
	 * Selects all active users who do not report to any other users.
	 *
	 * This was only used in testing, and is not used in production. In fact, currently it is not used anywhere!
	 *
	 * @return	resource	A mysql result resource of the format ()
	 * @access
	 * @since
	 */
	function selectTopLevelUsers() {
		$q = "SELECT distinct login, first_name, last_name from reporting_hierarchy rh ".
		"JOIN user u on (u.login = report_to_login) ".
		"WHERE report_to_login not in (SELECT DISTINCT reporting_login ".
									  "FROM reporting_hierarchy ".
									  "WHERE reporting_login<>report_to_login AND status='A') ".
			"AND rh.status='A' AND (u.status='A' OR u.status='N')";
		return $this->executeQuery($q);
	}


	/**
	 * Select users who report into a user  -- depreciated (almost) -- replaced by selectUsersThatReportTo
	 *  --- Actually not quite depreciated... used in cdir
	 *
	 * @param	int			$login
	 * @return	resource	A mysql result resource of the format ()
	 * @access
	 * @since
	 */
	function selectSubUsers($login) {
		$q = "SELECT distinct login, first_name, last_name, department ".
				"FROM reporting_hierarchy rh ".
				"JOIN user u ON (login = reporting_login) ".
				"LEFT JOIN department d ON (u.department_id = d.department_id) ".
				"WHERE report_to_login=$login AND report_to_login<>reporting_login AND u.status='A' AND rh.status='A' ".
				"ORDER BY last_name ASC, first_name ASC";
		return $this->executeQuery($q);
	}


	/**
	 * Selects a count of the number of users that report to a certain user -- depreciated (almost).
	 *  --- Actually not quite depreciated... used in cdir
	 *
	 * @param	int			$login
	 * @return	resource	A mysql result resource of the format (c)
	 * @access
	 * @since
	 */
	function countSubUsers($login) {
		$q = "SELECT COUNT(*) c ".
				"FROM reporting_hierarchy rh ".
				"JOIN user u ON (login = reporting_login) ".
				"WHERE report_to_login=$login AND report_to_login<>reporting_login AND u.status='A' AND rh.status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Updates the owner of a functional group, or adds a user as the owner if there is no current owner.
	 *
	 * @param	int			$fgid		The functional_group_id
	 * @param	int			$login		The login of the new owner
	 * @param	int			$admin_id	The login of the user doing the update.
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function updateFunctionalGroupOwner($fgid, $login, $admin_id) {
		$q1 = "UPDATE user_functional_group ".
				"SET functional_group_owner='N', modified_by=$admin_id, modified_date=NOW() ".
				"WHERE functional_group_id=$fgid AND status='A' AND functional_group_owner='Y'";
		$this->executeQuery($q1);
		$q2 = "UPDATE user_functional_group ".
				"SET functional_group_owner='Y', modified_by=$admin_id, modified_date=NOW() ".
				"WHERE functional_group_id=$fgid AND login=$login";
		$this->executeQuery($q2);

		if ($this->rows == 0) {
			$q3 = "INSERT INTO user_functional_group (login, functional_group_id, functional_group_owner, created_by, created_date) ".
					"VALUES ($login, $fgid, 'Y', $admin_id, NOW())";
			$this->executeQuery($q3);
		}
	}


	/**
	 * Updates the description of a functional group.
	 *
	 * @param	int			$fgid			The functional_group_id
	 * @param	string		$description	The new description
	 * @param	int			$admin_id		The login of the user doing the update.
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function updateFunctionalGroupDescription($fgid, $description, $admin_id) {
		$q = "UPDATE functional_group ".
				"SET functional_group_description='".mysql_real_escape_string($description)."', modified_by=$admin_id, modified_date=NOW() ".
				"WHERE functional_group_id=$fgid";
		return $this->executeQuery($q);
	}


	/**
	 * Updates a functional group's abbreviation
	 *
	 * @param	int			$fgid		The functional_group_id
	 * @param	stringa		$abbrev		The new functiona_group_abbreviation
	 * @param	int			$admin_id	The login of the user doing the update.
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function updateFunctionalGroupAbbrev($fgid, $abbrev, $admin_id) {
		$q = "UPDATE functional_group ".
				"SET functional_group_abbrev='".mysql_real_escape_string($abbrev)."', modified_by=$admin_id, modified_date=NOW() ".
				"WHERE functional_group_id=$fgid";
		return $this->executeQuery($q);
	}


	/**
	 * Inserts a new functional group into the database.
	 *
	 * @param	string		$abbrev			The abbreviation for the new functional group
	 * @param	string		$description	The description of the functional group
	 * @param	int			$admin_id		The login of the user inserting the group.
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function addFGroup($abbrev, $description, $admin_id) {
		$q = "INSERT INTO functional_group (functional_group_abbrev, functional_group_description, created_by, created_date) ".
				"VALUES ('".mysql_real_escape_string($abbrev)."', '".mysql_real_escape_string($description)."', $admin_id, NOW())";
		$this->executeQuery($q);
		return $this->lastID;
	}


	/**
	 * Removes a functional group.
	 *
	 * Doesn't actually remove anything from the db, just sets the status to 'D'
	 *
	 * @param	int			$fgid		The functional_group_id
	 * @param	int			$admin_id	The login of the user doing the removal
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function deleteFGroup($fgid, $admin_id) {
		$q = "UPDATE functional_group SET status='D', modified_by=$admin_id, modified_date=NOW() WHERE functional_group_id=$fgid";
		return $this->executeQuery($q);
	}


	/**
	 * Selects the owners of a certain functional group.
	 *
	 * By design, there may be more than one owner, but in practice, there is only one owner per group.
	 *
	 * @param	int			$fgid	The functional_group_id
	 * @return	resource	A mysql result resource of the format (login, first_name, last_name)
	 * @access
	 * @since
	 */
	function selectFunctionalGroupOwners($fgid) {
		$q = "SELECT u.login, u.first_name, u.last_name ".
				"FROM user_functional_group ufg ".
				"JOIN user u ON (ufg.login = u.login) ".
				"WHERE ufg.functional_group_id=$fgid AND ufg.functional_group_owner='Y' AND u.status='A' AND ufg.status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Selects all active users in a certian functional group.
	 *
	 * @param	int			$fgid	The functional_group_id
	 * @return	resource	A mysql result resource of the format (login, first_name, last_name)
	 * @access
	 * @since
	 */
	function selectUsersByFunctionalGroup($fgid) {
		$q = "SELECT u.login, u.first_name, u.last_name ".
				"FROM user_functional_group ufg ".
				"JOIN user u ON (ufg.login = u.login) ".
				"WHERE ufg.functional_group_id=$fgid AND u.status='A' AND ufg.status='A' ".
				"ORDER BY u.last_name ASC";
		return $this->executeQuery($q);
	}


	/**
	 * Selects the count of users in a functional group.
	 *
	 * @param	int			$fgid	The functional_group_id
	 * @return	resource	A mysql result resource of the format (c)
	 * @access
	 * @since
	 */
	function countUsersByFunctionalGroup($fgid) {
		$q = "SELECT COUNT(*) c ".
				"FROM user_functional_group ufg ".
				"JOIN user u ON (ufg.login = u.login) ".
				"WHERE ufg.functional_group_id=$fgid AND u.status='A' AND ufg.status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Adds a user to a functional group.
	 *
	 * @param	int			$login					The login of the user to add
	 * @param	int			$functional_group_id	The functional_group_id
	 * @param	int			$admin_id				The login of the user doing the adding
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function addUserFunctionalGroup($login, $functional_group_id, $admin_id) {
		$q = "INSERT INTO user_functional_group (login, functional_group_id, created_by, created_date) ".
				"VALUES ($login, $functional_group_id, $admin_id, NOW())";
		return $this->executeQuery($q);
	}


	/**
	 * Deletes a user from a functional group.
	 *
	 * Doesn't actually delete anything, just sets the status to 'D'
	 *
	 * @param	int			$login					The login of the user to remove
	 * @param	int			$functional_group_id	The functional_group_id of the group to remove from
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function deleteUserFunctionalGroup($login, $functional_group_id) {
		$q = "DELETE FROM user_functional_group ".
				"WHERE login=$login AND functional_group_id=$functional_group_id";
		return $this->executeQuery($q);
	}


	/**
	 * Checks if a user is in a functional group
	 *
	 * @param	int			$login					The login of the user in question
	 * @param	int			$functional_group_id	The functional_group_id
	 * @return	resource	A mysql result resource of the format (functional_group_id)
	 * @access
	 * @since
	 */
	function selectUserFunctionalGroup($login, $functional_group_id) {
		$q	= "SELECT functional_group_id FROM user_functional_group ".
				"WHERE login=$login AND functional_group_id=$functional_group_id AND status='A'";
		return $this->executeQuery($q);
	}

	/**
	 * Selects all the functional groups with which a user is associated
	 *
	 * @param	int			$login					The login of the user in question
	 * @return	resource	A mysql result resource of the format (functional_group_id)
	 * @access
	 * @since
	 */
	function selectUserFunctionalGroups($login) {
		$q = "SELECT functional_group_id FROM user_functional_group ".
				"WHERE login=$login AND status='A'";
		return $this->executeQuery($q);
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
	 * Selects information about a functional group
	 *
	 * @param	int			$fgid	The functional_group_id
	 * @return	resource	A mysql result resource of the format (functional_group_id, functional_group_abbrev,
	 *                       functional_group_description)
	 * @access
	 * @since
	 */
	function selectFunctionalGroup($fgid) {
		$q = "SELECT functional_group_id, functional_group_abbrev, functional_group_description ".
				"FROM functional_group WHERE functional_group_id=$fgid AND status='A' ORDER BY functional_group_abbrev ASC";
		return $this->executeQuery($q);
	}







	/**
	 * Selects users that have a certain security key
	 *
	 * @param	int			$secid	The secuirity_id
	 * @return	resource	A mysql result resource of the format (login, first_name, last_name)
	 * @access
	 * @since
	 */
	function selectUsersBySecurity($secid) {
		$q = "SELECT DISTINCT u.login, u.first_name, u.last_name ".
				"FROM security s ".
				"JOIN security_group_security sgs ON (s.security_id = sgs.security_id) ".
				"JOIN user_security_group usg ON (sgs.security_group_id = usg.security_group_id) ".
				"JOIN user u ON (usg.login = u.login) ".
				"WHERE s.security_id=$secid ".
				"ORDER BY u.last_name ASC";
		return $this->executeQuery($q);
	}


	/**
	 * Selects a count of users that have a certain security key.
	 *
	 * @param	int			$secid	The security_id
	 * @return	resource	A mysql result resource of the format (c)
	 * @access
	 * @since
	 */
	function countUsersBySecurity($secid) {
		$q = "SELECT COUNT(*) c ".
				"FROM (SELECT DISTINCT u.login, u.first_name, u.last_name ".
				"FROM security s ".
				"JOIN security_group_security sgs ON (s.security_id = sgs.security_id) ".
				"JOIN user_security_group usg ON (sgs.security_group_id = usg.security_group_id) ".
				"JOIN user u ON (usg.login = u.login) ".
				"WHERE s.security_id=$secid) a";
		return $this->executeQuery($q);
	}


	/**
	 * Selects all active users associated with a certain security group.
	 *
	 * @param	int			$sgid	The security_group_id
	 * @return	resource	A mysql result resource of the format (login, first_name, last_name)
	 * @access
	 * @since
	 */
	function selectUsersBySecurityGroup($sgid) {
		$q = "SELECT u.login, u.first_name, u.last_name ".
				"FROM user_security_group usg ".
				"JOIN user u ON (usg.login = u.login) ".
				"WHERE usg.security_group_id=$sgid ".
				"ORDER BY u.last_name ASC";
		return $this->executeQuery($q);
	}


	/**
	 * Selects a count of users associated with a certain security group.
	 *
	 * @param	int			$sgid	The security_group_id
	 * @return	resource	A mysql result resource of the format (c)
	 * @access
	 * @since
	 */
	function countUsersBySecurityGroup($sgid) {
		$q = "SELECT COUNT(*) c ".
				"FROM user_security_group usg ".
				"JOIN user u ON (usg.login = u.login) ".
				"WHERE usg.security_group_id=$sgid";
		return $this->executeQuery($q);
	}





	/**
	 * Adds a new security group to the database
	 *
	 * @param	string		$name		The name of the new security group
	 * @param	int			$admin_id	The login of the user adding the group
	 * @return	boolean		Returns the security_group_id of the new row ($this->lastID).
	 * @access
	 * @since
	 */
	function addSGroup($name, $admin_id) {
		$q = "INSERT INTO security_group (security_group_name, created_by, created_date) ".
							"VALUES ('".mysql_real_escape_string($name)."', $admin_id, NOW())";
		$this->executeQuery($q);
		return $this->lastID;
	}


	/**
	 * Deletes a security group
	 *
	 * @param	int			$sgid		The security_group_id of the group to remove.
	 * @param	int			$admin_id	The login of the user doing the removal.
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function deleteSGroup($sgid, $admin_id) {
		$q = "UPDATE security_group SET status='D', modified_by=$admin_id, modified_date=NOW() WHERE security_group_id=$sgid";
		return $this->executeQuery($q);
	}


	/**
	 * Updates the name of a security group.
	 *
	 * @param	int			$sgid		The security_group_id
	 * @param	string		$name		The new name of the group
	 * @param	int			$admin_id	The login of the user doing the update
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function updateSecurityGroupName($sgid, $name, $admin_id) {
		$q = "UPDATE security_group ".
				"SET security_group_name='".mysql_real_escape_string($name)."', ".
					"modified_by=$admin_id, ".
					"modified_date=NOW() ".
				"WHERE security_group_id=$sgid AND status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Adds an association between a security group and a certain security key
	 *
	 * @param	int			$sgid		The security_group_id
	 * @param	int			$sid		The security_id of the security key
	 * @param	int			$admin_id	The login of the user doing the addition
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function addSecurityGroupSecurity($sgid, $sid, $admin_id) {
		$q = "INSERT INTO security_group_security (security_group_id, security_id, created_by, created_date) ".
				"VALUES ($sgid, $sid, $admin_id, NOW())";
		return $this->executeQuery($q);
	}


	/**
	 * Removes (ACTUALLY DELETES) an association between a security group and a security key.
	 *
	 * @param	int			$sgid	The security_group_id
	 * @param	int			$sid	The security_id
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function deleteSecurityGroupSecurity($sgid, $sid) {
		$q = "DELETE FROM security_group_security ".
				"WHERE security_group_id=$sgid AND security_id=$sid";
		return $this->executeQuery($q);
	}


	/**
	 * Checks to see if a security is associated with a certain security group.
	 *
	 * @param	int			$sgid	The security_group_id
	 * @param	int			$sid	The security_id
	 * @return	resource	A mysql result resource of the format (security_id)
	 * @access
	 * @since
	 */
	function selectSecurityWithGroup($sgid, $sid) {
		$q = "SELECT s.security_id FROM security s ".
				"JOIN security_group_security sgs ON (s.security_id = sgs.security_id) ".
				"WHERE sgs.security_group_id=$sgid AND s.security_id=$sid AND s.status='A' AND sgs.status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Selects all active security type groups.
	 *
	 * These are the broad categories into which the individual security keys are classified.
	 *
	 * @return	resource	A mysql result resource of the format (security_type_group_id, security_type_group)
	 * @access
	 * @since
	 */
	function selectSecurityTypeGroups() {
		$q = "SELECT security_type_group_id, security_type_group ".
				"FROM security_type_group ".
				"WHERE status='A' ORDER BY security_type_group ASC";
		return $this->executeQuery($q);
	}


	/**
	 * Selects all security keys that fall into a certain security type group.
	 *
	 * @param	int			$stgid	The security_type_group_id
	 * @return	resource	A mysql result resource of the format (security_type, description, security_id)
	 * @access
	 * @since
	 */
	function selectSecuritiesByTypeGroup($stgid) {
		$q = "SELECT security_type, description, security_id ".
				"FROM security ".
				"WHERE security_type_group_id=$stgid AND status='A' ORDER BY security_type ASC";
		return $this->executeQuery($q);
	}
	
	/**
	 * Selects information about a certain security group.
	 *
	 * @param	int			$sgid	The security_group_id
	 * @return	resource	A mysql result resource of the format (security_group_id, security_group_name)
	 * @access
	 * @since
	 */
	function selectSecurityGroup($sgid) {
		$q = "SELECT security_group_id, security_group_name FROM security_group WHERE status='A' AND security_group_id=$sgid";
		return $this->executeQuery($q);
	}

	/**
	 * 
	 * 
	 * @param
	 * @return
	 * @access
	 * @since Fri Dec 08 11:24:11 IST 2006
	 */
	function GetSecurityId($user)
	{
		
		$q = "SELECT s.security_id, 0 AS invert, 1 AS is_group
				FROM security s
				LEFT OUTER JOIN security_group_security sgs ON ( sgs.security_id = s.security_id ) 
				LEFT OUTER JOIN user_security_group usg ON ( usg.security_group_id = sgs.security_group_id ) 
				WHERE usg.login = " . $user['login'] ."
				UNION ALL 
				SELECT s.security_id, us.invert, 0 AS is_group
				FROM security s
				LEFT OUTER JOIN user_security AS us ON ( s.security_id = us.security_id ) 
				WHERE us.login = " . $user['login']. " and us.status = 'A'";
		
//		$q = "SELECT s.security_id, us.invert, IF( sgs.security_id, 1, 0 ) AS is_group
//				FROM security s
//				LEFT OUTER JOIN security_group_security sgs ON ( sgs.security_id = s.security_id ) 
//				LEFT OUTER JOIN user_security_group usg ON ( usg.security_group_id = sgs.security_group_id ) 
//				LEFT OUTER JOIN user_security AS us ON ( usg.login = us.login ) 
//				WHERE usg.login = ". $user['login'];
		
		//$q = "select security_id 
			//	from security_group_security 
			//	where security_group_id in (
				//		select security_group_id 
				//		from user_security_group 
				//		where login = ". $user['login'] .")";
		
		return $this->executeQuery($q);
	}
	
	/**
	 * Selects all active security groups
	 *
	 * @return	resource	A mysql result resource of the format (security_group_id, security_group_name)
	 * @access
	 * @since
	 */
	function selectSecurityGroups() {
		$q = "SELECT security_group_id, security_group_name ".
				"FROM security_group WHERE status='A'  ORDER BY security_group_name ASC";
		return $this->executeQuery($q);
	}


	/**
	 * Selects information on all active security keys associated with a security group
	 *
	 * @param	int			$security_group_id	The security_group_id
	 * @return	resource	A mysql result resource of the format (security_type, description, security_id)
	 * @access
	 * @since
	 */
	function selectSecurityInfo($security_group_id) {
		$q = "SELECT security_type, description, s.security_id ".
				"FROM security_group_security sgs ".
				"LEFT JOIN security s ON (sgs.security_id = s.security_id) ".
				"WHERE sgs.security_group_id=$security_group_id AND sgs.status='A' AND s.status='A' ".
				"ORDER BY security_type ASC";
		return $this->executeQuery($q);
	}


	/**
	 * Selects all active security keys.
	 *
	 * @return	resource	A mysql result resource of the format (security_id, security_type, security_type_group_id,
	 *                       display_size, display_field_size, description)
	 * @access
	 * @since
	 */
	function selectSecurities() {
		$q = "SELECT security_id, security_type, security_type_group_id, display_type, display_field_size, description ".
				"FROM security WHERE status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Selects information on a certain security
	 *
	 * @param	int			$secid	The security_id
	 * @return	resource	A mysql result resource of the format (security_id, security_type, security_type_group_id,
	 *                       display_type, display_field_size, description)
	 * @access
	 * @since
	 */
	function selectSecurity($secid) {
		$q = "SELECT security_id, security_type, security_type_group_id, display_type, display_field_size, description ".
				"FROM security WHERE status='A' AND security_id=$secid";
		return $this->executeQuery($q);
	}


	/**
	 * Updates the description of a particular security
	 *
	 * @param	int		$secid			The security_id
	 * @param	string	$description	The new description
	 * @param	int		$admin_id		The login of the user doing the update
	 * @return	boolean					Should return true on success, will throw an error on failure.
	 * @access
	 * @since
	 */
	function updateSecurityDescription($secid, $description, $admin_id) {
		$description = mysql_real_escape_string($description);
		$q = "UPDATE security ".
				"SET description='$description', modified_by=$admin_id, modified_date=NOW() ".
				"WHERE security_id=$secid";
		return $this->executeQuery($q);
	}

	/**
	 * Checks to see if a user is in a certain security group
	 *
	 * @param	int			$login				The login of the user
	 * @param	int			$security_group_id	The security_group_id of the group
	 * @return	resource	A mysql result resource of the format (security_group_id)
	 * @access
	 * @since
	 */
	function selectUserSecurityGroup($login, $security_group_id) {
		$q = "SELECT security_group_id FROM user_security_group WHERE login=$login AND security_group_id=$security_group_id AND status='A'";
		return $this->executeQuery($q);
	}

	/**
	 * Selects security groups that a user is associated with.
	 *
	 * @param	int			$login	The login of the user.
	 * @return	resource	A mysql result resource of the format (security_group_id)
	 * @access
	 * @since
	 */
	function selectUserSecurityGroups($login) {
		$q = "SELECT security_group_id FROM user_security_group WHERE login=$login AND status='A'";
		return $this->executeQuery($q);
	}

	/**
	 * Adds a user - security group association
	 *
	 * @param	int		$sgid		The security_group_id
	 * @param 	int		$login		The login of the user being associated
	 * @param	int		$login		The login of the user doing the update
	 * @return	resource	Should return true on success, will throw an error on failure.
	 * @access
	 * @since
	 */
	function replaceUserSecurityGroup($sgid, $login, $admin_id) {
		$q = "REPLACE INTO user_security_group ".
				"SET login=$login, security_group_id=$sgid, created_by=$admin_id, created_date=NOW()";
		return $this->executeQuery($q);
	}

	/**
	 * ---NOT USED---
	 *
	 * Good thing, because thie function doesn't do anything useful anyway!
	 *
	 * @param	int			$sgid
	 * @return	resource	A mysql result resource of the format ()
	 * @access
	 * @since
	 */
	function selectSecurityGroupSecurities($sgid) {
		$q = "SELECT security_id FROM security_group_security WHERE status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Adds a user to a security group
	 *
	 * @param	int			$login		The login of the user to add
	 * @param	int			$sgid		The security_group_id
	 * @param	int			$admin_id	The login of the user doing the adding
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function addUserSecurityGroup($login, $sgid, $admin_id) {
		$q = "INSERT INTO user_security_group (login, security_group_id, created_by, created_date) ".
				"VALUES ($login, $sgid, $admin_id, NOW())";
		return $this->executeQuery($q);
	}


	/**
	 * Removes a user from a security group -- ACTUALLY DELETES THINGS -- BE CAREFUL! ;)
	 *
	 * @param	int			$login	The login of the user to remove
	 * @param	int			$sgid	The security_group_id of the group to remove from
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function deleteUserSecurityGroup($login, $sgid) {
		$q = "DELETE FROM user_security_group ".
				"WHERE login=$login AND security_group_id=$sgid";
		return $this->executeQuery($q);
	}





	/**
	 * Sets a users password
	 *
	 * @param	int			$login		The login of the user who will have the new password
	 * @param	string		$pass		The new password of the user (not escaped)
	 * @param	int			$admin_id	The login of the user updating the password
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function setPassword($login, $pass, $admin_id) {
		$q = "UPDATE user SET password=OLD_PASSWORD('".mysql_real_escape_string($pass)."'), modified_by=".$admin_id.", modified_date=NOW() WHERE login=".$login;
		return $this->executeQuery($q);
	}




	/**
	 * Adds a confirmation code to the database that will be used to verify a changed email or SMS address.
	 *
	 * @param	string		$address		The 'new' email or SMS address
	 * @param	int			$login			The login of the user changing their email or SMS address
	 * @param	string		$code			The confirmation code
	 * @param	int			$admin_id		The login of the user calling this function
	 * @param	string		$attr_conf		The user_attr to store the confirmation code under (E_CONF or S_CONF)
	 * @param	string		$attr_conf_addr	The user_attr to store the new address under (E_CONF_ADR or S_CONF_ADR)
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function addConfirm($address, $login, $code, $admin_id, $attr_conf, $attr_conf_addr) {
		//clear all other email confirmations before adding this one
		$query = "UPDATE user_attr ".
				"SET status='D', modified_by=".$admin_id.", modified_date=NOW() ".
				"WHERE login=$login AND user_attr='$attr_conf' OR user_attr='$attr_conf_addr'";
		$this->executeQuery($query);

		$query = "INSERT INTO user_attr (login, user_attr, user_value, created_by, created_date) ".
				"VALUES (".$login.", '".mysql_real_escape_string($attr_conf)."', '".$code."', ".$admin_id.", NOW())";
		$this->executeQuery($query);

		$query = "INSERT INTO user_attr (login, user_attr, user_value, created_by, created_date) ".
				"VALUES (".$login.", '$attr_conf_addr', '".mysql_real_escape_string($address)."', ".$admin_id.", NOW())";
		$this->executeQuery($query);
	}


	/**
	 * Selects the new email or SMS address given a confirmation code
	 *
	 * @param	string		$code				The code
	 * @param	int			$login				The login of the user
	 * @param	string		$attr_conf_addr		The name of the user_attr under which the new address is stored (E_CONF or S_CONF)
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function getConfirmFromCode($code, $login, $attr_conf_addr) {
		$q = "SELECT b.user_value, a.user_attr_id a_id, b.user_attr_id b_id ".
				"FROM user_attr a, user_attr b ".
				"WHERE a.login=".$login." AND b.login=".$login." AND a.user_value='".$code."' AND b.user_attr='".$attr_conf_addr."' AND a.status='A' AND b.status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Updates a specified column in the user table
	 *
	 * @param	int			$login			The login of the user to update
	 * @param	string		$address		The new value for the updating column
	 * @param	string		$user_column	The column to update (email_address, sms_address)
	 * @param	int			$admin_id		The login of the user doing the update
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function setConfirmAddress($login, $address, $user_column, $admin_id) {
		$q = "UPDATE user ".
				"SET $user_column='".mysql_real_escape_string($address)."', modified_by=".$admin_id.", modified_date=NOW() ".
				"WHERE login=".$login;
		return $this->executeQuery($q);
	}


	/**
	 * Sets an email confirmation non-active by setting two rows in user_attr to status='D'
	 *
	 * @param	int			$a_id		One user_attr_id
	 * @param	int			$b_id		Another user_attr_id
	 * @param	int			$admin_id	The login of the user calling this function
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function setConfirmDead($a_id, $b_id, $admin_id) {
		$q = "UPDATE user_attr ".
				"SET status='D', modified_by=".$admin_id.", modified_date=NOW() ".
				"WHERE user_attr_id=".$a_id." OR user_attr_id=".$b_id;
		return $this->executeQuery($q);
	}

	//for adding a new contact

	/**
	 * Adds a contact to be associated with a user.
	 *
	 * @param 	int		$login					The login of the user adding this contact
	 * @param	int		$contact_type_id		The contact_type_id of this new contact
	 * @param	string	$contact_title			The title or salutation of the new contact
	 * @param	string	$contact_first_name		The first name of the new contact
	 * @param	string	$contact_middle_name	The middle name of the new contact
	 * @param	string	$contact_last_name		The last name of the new contact
	 * @param	string	$contact_suffix			The suffix of the new contact
	 * @param	string	$contact_email			The email address of the new contact
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function addContact($login,
				$contact_type_id,
				$contact_title,
				$contact_first_name,
				$contact_middle_name,
				$contact_last_name,
				$contact_suffix,
				$contact_email)
	{
		$query = "INSERT INTO contact ".
					"(contact_type_id, contact_title,contact_first_name,contact_middle_name,contact_last_name,contact_suffix,contact_email,created_by,created_date) ".
					"VALUES ($contact_type_id, '".mysql_real_escape_string($contact_title)."','".mysql_real_escape_string($contact_first_name)."','".mysql_real_escape_string($contact_middle_name)."','".mysql_real_escape_string($contact_last_name)."','".mysql_real_escape_string($contact_suffix)."','".mysql_real_escape_string($contact_email)."', ".$login.", NOW())";
		$rs = $this->executeQuery($query);

		$cid = $this->lastID;
		$query = "INSERT INTO user_contact ".
					"(login, contact_id,created_by,created_date) ".
					"VALUES (".$login.", ".$this->lastID.", ".$login.", NOW())";
		$rs = $this->executeQuery($query);

		return $cid;
	}


	/**
	 * Removes a contact (not really... only sets status to 'D')
	 *
	 * This also grabs $_SESSION['admin_id'], which should be changed.
	 *
	 * $o['cid'] should be the contact_id
	 * $o['login'] should be the login of the user in the user-contact relationship
	 *
	 * @param	array		$o	The parsed server variables
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function removeContact($o) {
		$query = "UPDATE user_contact ".
					"SET status = 'D', modified_by=".$_SESSION['admin_id'].", modified_date=NOW() ".
					"WHERE contact_id = ".$o['cid']." ".
					"AND login = ".$o['login'];
		return $this->executeQuery($query);
	}


	/**
	 * Updates a contact with new information.
	 *
	 * $o should hold the details of the contact, ie., $o['contact_type_id'], $o['contact_first_name'], etc.
	 *
	 * @param	int			$admin_id	The login of the user doing the update
	 * @param	array		$o			The array of parsed server variables
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function updateContact($admin_id, $o) {
		$query = "UPDATE contact ".
					"SET contact_type_id='".$o['contact_type_id']."', ".
						"contact_title='".mysql_real_escape_string($o['contact_title'])."', ".
						"contact_first_name='".mysql_real_escape_string($o['contact_first_name'])."', ".
						"contact_middle_name='".mysql_real_escape_string($o['contact_middle_name'])."', ".
						"contact_last_name='".mysql_real_escape_string($o['contact_last_name'])."', ".
						"contact_suffix='".mysql_real_escape_string($o['contact_suffix'])."', ".
						"contact_email='".mysql_real_escape_string($o['contact_email'])."', ".
						"modified_by='".$admin_id."', ".
						"modified_date=NOW() ".
					"WHERE contact_id=".$o['cid'];
		return $this->executeQuery($query);
	}


	/**
	 * Adds an address
	 *
	 * $o should contain indecies of address_type_id, addres_street_1, address_street_2, address_city,
	 *  address_state, address_province, address_zip, and address_country_code.
	 *
	 * @param	array		$o		The array of parsed server variables
	 * @param	int			$cid	The contact_id to which the address will be associated
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function addAddress($o, $cid)
	{
		$query = "INSERT INTO address ".
					"(created_by, created_date, contact_id, address_type_id, address_street_1, address_street_2, address_city, address_state, address_province, address_zip";
		if($o['address_country_code'] != "0") {
			$query .= ", address_country_code";
		}
		$query .= ") VALUES (".$_SESSION['admin_id'].", NOW(), ".$cid.", ".
							"'".mysql_real_escape_string($o['address_type_id'])."', ".
							"'".mysql_real_escape_string($o['address_street_1'])."', ".
							"'".mysql_real_escape_string($o['address_street_2'])."', ".
							"'".mysql_real_escape_string($o['address_city'])."', ".
							"'".mysql_real_escape_string($o['address_state'])."', ".
							"'".mysql_real_escape_string($o['address_province'])."', ".
							"'".mysql_real_escape_string($o['address_zip'])."'";
		if($o['address_country_code'] != "0") {
			$query .= ", '".$o['address_country_code']."'";
		}
		$query .= ")";

		$rs = $this->executeQuery($query);

		return $rs;
	}


	/**
	 * Updates an address with new information
	 *
	 * $o should contain indecies of address_type_id, addres_street_1, address_street_2, address_city,
	 *  address_state, address_province, address_zip, and address_country_code.
	 *
	 * @param	array		$o
	 * @param	int			$aid
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function updateAddress($o, $aid) {
		$q = "UPDATE address ".
				"SET modified_by=".$_SESSION['admin_id'].", ".
					"modified_date=NOW(), ".
					"address_type_id=".mysql_real_escape_string($o['address_type_id']).", ".
					"address_street_1='".mysql_real_escape_string($o['address_street_1'])."', ".
					"address_street_2='".mysql_real_escape_string($o['address_street_2'])."', ".
					"address_city='".mysql_real_escape_string($o['address_city'])."', ".
					"address_state='".mysql_real_escape_string($o['address_state'])."', ".
					"address_province='".mysql_real_escape_string($o['address_province'])."', ".
					"address_zip='".mysql_real_escape_string($o['address_zip'])."', ".
					"address_country_code='".mysql_real_escape_string($o['address_country_code'])."' ".
				"WHERE address_id=$aid";
		return $this->executeQuery($q);
	}



	/**
	 * Adds a new phone to be associated with a contact
	 *
	 * @param	int			$admin_id				The login of the user adding the phone
	 * @param	int			$contact_id				The contact_id of the contact with which this phone will be associated (yay for grammer!)
	 * @param	int			$phone_type_id			The phone_type_id
	 * @param	string		$phone_country_code		The phone's country code (without the '+')
	 * @param	string		$contact_phone_number	The phone number, including area or region code
	 * @param	string		$contact_phone_ext		The extension on thephone, if applicable
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function addPhone($admin_id, $contact_id, $phone_type_id, $phone_country_code, $contact_phone_number, $contact_phone_ext) {
		$query = "INSERT INTO contact_phone ".
					"(contact_id, phone_type_id, phone_country_code, contact_phone_number";
		if ($contact_phone_ext != "") {
			$query .= ", contact_phone_ext";
		}
		$query .= ", created_by, created_date";
		$query .= ") VALUES (".$contact_id.", ".
							$phone_type_id.", ".
							"'".$phone_country_code."', ".
							"'".$contact_phone_number."'".
							($contact_phone_ext != "" ? ", '".$contact_phone_ext."'" : "").
							", ".$admin_id.", NOW()".
							")";
		$rs = $this->executeQuery($query);

		return $rs;
	}


	/**
	 * Removes a phone (just sets the status to 'D')
	 *
	 * @param	int			$admin_id	The login of the user removing the phone
	 * @param	int			$phone_id	The phone_id of the phone to be removed;
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function removePhone($admin_id, $phone_id) {
		$q = "UPDATE contact_phone SET status='D', modified_by=$admin_id, modified_date=NOW() WHERE contact_phone_id=$phone_id";
		return $this->executeQuery($q);
	}


	/**
	 * Removes an address (just sets the status to 'D'
	 *
	 * @param	int			$admin_id		The login of the user doing the removal
	 * @param	int			$address_id		The address_id
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function removeAddress($admin_id, $address_id) {
		$q = "UPDATE address SET status='D', modified_by=$admin_id, modified_date=NOW() WHERE address_id=$address_id";
		return $this->executeQuery($q);
	}


	/**
	 * Updates a phone record with new information
	 *
	 * @param	int			$admin_id				The login of the user doing the update
	 * @param	int			$phone_id				The phone_id
	 * @param	int			$phone_type_id			The new phone_type_id
	 * @param	string		$phone_country_code		The new phone country code
	 * @param	string		$contact_phone_number	The new phone number, including area or region code
	 * @param	string		$contact_phone_ext		The new phone extension
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function updatePhone($admin_id, $phone_id, $phone_type_id, $phone_country_code, $contact_phone_number, $contact_phone_ext) {
		$q = "UPDATE contact_phone ".
				"SET modified_by=$admin_id, modified_date=NOW(), phone_type_id=$phone_type_id, phone_country_code='$phone_country_code', contact_phone_number='$contact_phone_number', contact_phone_ext='$contact_phone_ext' ".
				"WHERE contact_phone_id=$phone_id";
		return $this->executeQuery($q);
	}



	/**
	 * Selects all the active time zones.
	 *
	 * @return	resource	A mysql result resource of the format (time_zone_id, city_country_name, offset)
	 * @access
	 * @since
	 */
	function selectTimeZoneTypes() {
		$q = "SELECT time_zone_id, city_country_name, offset ".
				"FROM time_zone ".
				"WHERE status='A' ".
				"ORDER BY offset ASC";
		return $this->executeQuery($q);
	}




	/**
	 * Selects all active contact types.
	 *
	 * @return	resource	A mysql result resource of the format (contact_type_id, contact_type)
	 * @access
	 * @since
	 */
	function selectContactTypes() {
		$q = "SELECT contact_type_id, contact_type_description ".
				"FROM contact_type ".
				"WHERE status='A'";
		return $this->executeQuery($q);
	}



	/**
	 * Selects all active countries from the database, ordered alphabeticall by name
	 *
	 * @return	resource	A mysql result resource of the format (code, name)
	 * @access
	 * @since
	 */
	function selectCountryTypes() {
		$rs = $this->executeQuery("SELECT country_code AS code, country_description AS name ".
						"FROM country ".
						"ORDER BY country_description "
						);
		return $rs;
	}



	/**
	 * Selects all active address types
	 *
	 * @return	resource	A mysql result resource of the format (address_type_idd, address_type_description)
	 * @access
	 * @since
	 */
	function selectAddressTypes() {
		$q = "SELECT address_type_id, address_type_description ".
						"FROM address_type ".
						"WHERE status='A'";
		return $this->executeQuery($q);
	}



	/**
	 * Sets detailed information about a user
	 *
	 * @param	int			$admin_id		The login of the user doing the update
	 * @param	int			$login			The login of the user being updated
	 * @param	string		$first_name		The new first name of the user
	 * @param	string		$last_name		The new last name of the user
	 * @param	int			$department_id	The new department_id of the user
	 * @param	int			$time_zone_id	The new time_zone_id of the user
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function setUserDetail($admin_id, $login, $first_name, $last_name, $department_id, $location_id, $time_zone_id) {
		$q = "UPDATE user ".
				"SET first_name = '".mysql_real_escape_string($first_name)."', ".
					"last_name = '".mysql_real_escape_string($last_name)."', ".
					"department_id = '".$department_id."', ".
					"location_id = '".$location_id."', ".
					"time_zone_id = ".$time_zone_id.", ".
					"modified_by = ".$admin_id.", ".
					"modified_date = NOW() ".
				"WHERE login = ".$login;
		return $this->executeQuery($q);
	}



	/**
	 * Selects a user_attr from the user_attr table
	 *
	 * @param	int			$login		The login of the user
	 * @param	string		$user_attr	The user_attr to be selected
	 * @return	resource	A mysql result resource of the format (user_value)
	 * @access
	 * @since
	 */
	function selectAttr($login, $user_attr) {
		$q = "SELECT user_value ".
				"FROM user_attr ".
				"WHERE status='A' AND user_attr='".$user_attr."' AND login=".$login;
		return $this->executeQuery($q);
	}


	/**
	 * Removes all user_attrs of a certain type from a user
	 *
	 * @param	int			$login		The user to remove things from
	 * @param	string		$user_attr	The user_attr of the attribute to remove
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function deleteAttr($login, $user_attr) {
		$q = "DELETE FROM user_attr ".
				"WHERE user_attr='$user_attr' AND login=$login";
		return $this->executeQuery($q);
	}


	/**
	 * Inserts a new user attribute
	 *
	 * @param	int			$admin_id		The login of the user doing this insertion
	 * @param	int			$login			The login of the user who will have this attr
	 * @param	string		$user_attr		The user_attr
	 * @param	string		$user_value		The value of the attribute
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function insertAttr($admin_id, $login, $user_attr, $user_value) {
		$q = "INSERT INTO user_attr ".
				"SET status='A', user_attr='$user_attr', user_value='".mysql_real_escape_string($user_value)."', login=$login, created_by=$admin_id, created_date=NOW()";
		return $this->executeQuery($q);
	}


	/**
	 * Updates a user attribute
	 *
	 * Note: if the user has multiple attributes of the same kind, they will all be updated with the new value.
	 *
	 * @param	int			$admin_id		The login of the user doing this update
	 * @param	int			$login			The login of the user whose attribute is being updated
	 * @param	string		$attr_type		The user_attr
	 * @param	string		$attr_value		The new value of the attribute
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function updateAttr($admin_id, $login, $attr_type, $attr_value) {
		$q = "UPDATE user_attr ".
				"SET user_value='".mysql_real_escape_string($attr_value). "', modified_by=".$admin_id.", modified_date=NOW() ".
				"WHERE status='A' AND login=".$login." AND user_attr='".$attr_type."'";
		return $this->executeQuery($q);
	}


	/**
	 * Gets the useer_attr_def_id of a named user_attr
	 *
	 * @param	string		$name	The name of the user_attr
	 * @return	String		The actual user_attr_def_id (not a resource)
	 * @access
	 * @since
	 */
	function getUserAttrByName($name) {
		$q = "SELECT user_attr_def_id ".
				"FROM user_attr_def ".
				"WHERE user_attr='$name'";
		$row = mysql_fetch_assoc($rs);
		return $row['user_attr_def_id'];
	}










	/**
	 * Adds a contact attribute
	 *
	 * @param	int			$admin_id		The login of the user doing the addition
	 * @param	int			$cid			The contact_id of the contact this attribute will belong to
	 * @param	string		$contact_attr	The contact_attr
	 * @param	string		$contact_value	The contact_value
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function addContactAttr($admin_id, $cid, $contact_attr, $contact_value) {
		$q = "INSERT INTO contact_attr (contact_id, contact_attr, contact_value, created_by, created_date) ".
				"VALUES ($cid, '$contact_attr', '".mysql_real_escape_string($contact_value)."', $admin_id, NOW())";
		return $this->executeQuery($q);
	}


	/**
	 * Selects a contact attribute
	 *
	 * @param	int			$cid			The contact_id
	 * @param	string		$contact_attr	The name of the attribute (the contact_attr)
	 * @return	resource	A mysql result resource of the format (contact_id, contact_attr, contact_value)
	 * @access
	 * @since
	 */
	function selectContactAttr($cid, $contact_attr) {
		$q = "SELECT contact_id, contact_attr, contact_value ".
				"FROM contact_attr ".
				"WHERE contact_id=$cid AND status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Updates a contact attribute
	 *
	 * NOTE: if a contact has multiple attributes of the same type, they will all be updated with the new value.
	 *
	 * @param	int			$admin_id		The login of the user doing the update
	 * @param	int			$cid			The contact_id
	 * @param	string		$contact_attr	The contact_attr
	 * @param	string		$contact_value	The new value for the attribute
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function setContactAttr($admin_id, $cid, $contact_attr, $contact_value) {
		$q = "UPDATE contact_attr ".
				"SET contact_value='$contact_value', modified_by=$admin_id, modified_date=NOW() ".
				"WHERE contact_id=$cid AND contact_attr='$contact_attr' AND status='A'";
		return $this->executeQuery($q);
	}











	/**
	 * --- DEPRECIATED ---
	 * See selectActiveUsers2()
	 *
	 * @param	int			$start
	 * @param	int			$end
	 * @param	string		$where
	 * @param	string		$sort
	 * @return	resource	A mysql result resource of the format ()
	 * @access
	 * @since
	 */
	function selectActiveUsers($start,$end,$where,$sort)
	{

		$q = "SELECT " .
			"u.login, " .
			"u.first_name, ".
			"u.last_name, " .
			"u.last_login, " .
			"u.email_address, " .
			"u.sms_address, " .
			"u.list_id, " .
			"d.department " .
			"FROM user u " .
  			"LEFT JOIN department d ON (u.department_id = d.department_id) " .
 			"WHERE u.status = 'A' " .
  			$where .
  			$sort .
 			"LIMIT " . $start . " , " .  $end;
		return $this->executeQuery($q);
	}


	/**
	 * Selects information about a certain subset of active users.
	 *
	 * $o['searchLogin'] is set by the search script to match against the user's login
	 * $o['searchFirstName'] is set by the search script to match against the user's first name
	 * $o['searchLastName'] is set by the search script to match against the user's last name
	 * $o['searchDepartmentId'] is set by the search script to match against the user's department_id
	 * $o['orderField'] is the field by which to order the results
	 * $o['orderDir'] is the direction to sort the results (ASC|DESC)
	 * $o['start'] and $o['end'] limit the rows returned in the result set
	 *
	 * @param	array		$o		The parsed document variables
	 * @return	resource	A mysql result resource of the format (login, first_name, last_name, email_address,
	 *                       department, last_login, status)
	 * @access
	 * @since
	 */
	function selectActiveUsers2($o)
	{
		$where = "";
		if ($o['searchLogin'] != "") $where .= "AND (u.login LIKE '" . $o['searchLogin'] . "' OR u.email_address LIKE '" . $o['searchLogin']. "') ";
		if ($o['searchFirstName'] != "") $where .= "AND u.first_name LIKE '%" . mysql_real_escape_string($o['searchFirstName']) . "%' ";
		if ($o['searchLastName'] != "") $where .= "AND u.last_name LIKE '%" . mysql_real_escape_string($o['searchLastName']) . "%' ";
		if ($o['searchDepartmentId'] != "") $where .= "AND u.department_id = " . $o['searchDepartmentId'] . " ";

		if (substr($where,0,3) == "AND") $where = substr($where, 4);

		$sort = "ORDER BY ".$o['orderField']." ".$o['orderDir']." ";

		$q = "SELECT " .
			"u.login, " .
			"u.first_name, ".
			"u.last_name, " .
			"u.email_address, " .
			"d.department, " .
			"u.last_login, " .
			"u.status ".
			"FROM user u " .
  			"LEFT JOIN department d ON (u.department_id = d.department_id) " .
 			($where == "" ? "" : "WHERE ") .
  			$where .
  			$sort .
 			"LIMIT " . $o['start'] . " , " .  ($o['end']-$o['start']);
		$rs = $this->executeQuery($q);
		return $rs;
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
	 * Selects a count of a certain subset of users.
	 *
	 * See selectActiveUsers2()
	 *
	 * @param	array		$o		The parsed document variable array
	 * @return	resource	A mysql result resource of the format (count(*))
	 * @access
	 * @since
	 */
	function selectNumberOfActiveUsers($o)
	{
		$where = "";
		if ($o['searchLogin'] != "") $where .= "AND (u.login LIKE '" . $o['searchLogin'] . "' OR u.email_address LIKE '" . $o['searchLogin'] . "') ";
		if ($o['searchFirstName'] != "") $where .= "AND u.first_name LIKE '%" . mysql_real_escape_string($o['searchFirstName']) . "%' ";
		if ($o['searchLastName'] != "") $where .= "AND u.last_name LIKE '%" . mysql_real_escape_string($o['searchLastName']) . "%' ";
		if ($o['searchDepartmentId'] != "") $where .= "AND u.department_id = " . $o['searchDepartmentId'] . " ";

		if (substr($where,0,3) == "AND") $where = substr($where, 4);

      $rs = $this->executeQuery("SELECT count(*) FROM `user` u ".($where == "" ? "" : "WHERE ") . $where );
		return mysql_result($rs,0);
	}



	/**
	 * Selects information on a certain user.
	 *
	 * @param	int			$login		The login of the user.
	 * @return	resource	A mysql result resource of the format (login, first_name, last_name, last_login, email_address, sms_address,
	 *                       time_zone_id, list_id, status, department, )
	 * @access
	 * @since
	 */
	function selectUserDetail($login)
	{

		$q = "SELECT " .
                        "u.user_id, ".
			"u.login, " .
			"u.first_name, ".
			"u.last_name, " .
                        "u.user_type_id, ".
			"u.last_login, " .
			"u.email_address, " .
			"u.sms_address, " .
			"u.time_zone_id, ".
			"u.list_id, " .
			"u.status, ".
			"d.department, ".
			"d.department_id, " .
			"l.location_id, ".
			"l.location_description, ".
//			"ua_tipic.user_value tipic, " .
//			"ua_title.user_value title, " .
//			"ua_website.user_value website, " .
//			"ua_nickname.user_value nickname, ".
			"tz.city_country_name, " .
			"tz.offset ".
			"FROM user u " .
  			"LEFT JOIN department d ON (u.department_id = d.department_id) " .
  			"LEFT JOIN location AS l ON l.location_id = u.location_id " .
			"LEFT JOIN time_zone tz ON (u.time_zone_id = tz.time_zone_id) " .
//			"LEFT JOIN (SELECT login, user_value FROM user_attr WHERE user_attr='TIPIC' AND status='A') ua_tipic ON (ua_tipic.login = u.login) " .
//			"LEFT JOIN (SELECT login, user_value FROM user_attr WHERE user_attr='TITLE' AND status='A') ua_title ON (ua_title.login = u.login) " .
//			"LEFT JOIN (SELECT login, user_value FROM user_attr WHERE user_attr='NICKNAME' AND status='A') ua_nickname ON (ua_nickname.login = u.login) " .
//			"LEFT JOIN (SELECT login, user_value FROM user_attr WHERE user_attr='WEBSITE' AND status='A') ua_website ON (ua_website.login = u.login) " .
 			"WHERE u.login = '" . $login . "'";
		return $this->executeQuery($q);
	}




	/**
	 * Selects information about a contact
	 *
	 * @param	int			$login	The contact_id
	 * @return	resource	A mysql result resource of the format (contact_id, contact_title, contact_first_name, contact_last_name,
	 *                       contact_suffix, contact_email)
	 * @access
	 * @since
	 */
	function selectContactInfo($login) {
		$q = "SELECT ".
					"uc.contact_id, ".
					"c.contact_title, ".
					"c.contact_first_name, ".
					"c.contact_last_name, ".
					"c.contact_suffix, ".
					"c.contact_email ".
				"FROM user_contact uc ".
				"LEFT JOIN contact c ON (uc.contact_id = c.contact_id) ".
				"WHERE uc.login = '". $login ."' AND uc.status='A' AND c.status='A' ".
				"ORDER BY c.contact_id";
		return $this->executeQuery($q);
	}


	/**
	 * Selects information about a contact, given their contact_id
	 *
	 * @param	int			$cid	The contact_id
	 * @return	resource	A mysql result resource of the format (contact_id, contact_type_id, contact_title, contact_first_name,
	 *                       contact_middle_name, contact_last_name, contact_suffix, contact_email)
	 * @access
	 * @since
	 */
	function selectContact($cid) {
		$q = "SELECT contact_id, contact_type_id, contact_title, contact_first_name, contact_middle_name, contact_last_name, ".
				"contact_suffix, contact_email ".
				"FROM contact ".
				"WHERE contact_id=$cid";
		return $this->executeQuery($q);
	}

	/**
	* selectLocations()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 24 11:04:17 PST 2006
	*/
	function selectLocations()
	{
      $q = "SELECT l.location_id, l.location_description, l.region_id, r.region_code, r.region_description ".
      "FROM location AS l LEFT JOIN region AS r ON r.region_id = l.region_id ".
      "WHERE l.status='A' AND r.status='A'";
      return $this->executeQuery($q);
	}


	/**
	 * Selects all addresses associated with a certain contact
	 *
	 * @param	int			$contact_id		The contact_id
	 * @return	resource	A mysql result resource of the format (address_id, address_street_1, address_street_2, address_city,
	 *                       address_state, address_province, address_zip, address_type_description, name)
	 * @access
	 * @since
	 */
	function selectContactAddresses($contact_id) {
		$rs = $this->executeQuery("SELECT ".
									"a.address_id, ".
									"a.address_street_1, ".
									"a.address_street_2, ".
									"a.address_city, ".
									"a.address_state, ".
									"a.address_province, ".
									"a.address_zip, ".
									"at.address_type_description, ".
									"c.country_description AS name ".
									"FROM address a ".
									"LEFT JOIN country c ON (a.address_country_code = c.country_code) ".
									"LEFT JOIN address_type at ON (a.address_type_id = at.address_type_id) ".
									"WHERE a.contact_id = '".$contact_id."' AND a.status='A'"
									);
		return $rs;
	}


	/**
	 * Selects all active phones associated with a certain contact.
	 *
	 * @param	int			$contact_id		The contact_id
	 * @return	resource	A mysql result resource of the format (contact_phone_id, contact_phone_number, contact_phone_ext,
	 *                       phone_country_code, phone_type_description)
	 * @access
	 * @since
	 */
	function selectContactPhones($contact_id) {
		$q = "SELECT ".
				"cp.contact_phone_id, ".
				"cp.contact_phone_number, ".
				"cp.contact_phone_ext, ".
				"cp.phone_country_code, ".
				"pt.phone_type_description ".
				"FROM contact_phone cp ".
				"LEFT JOIN phone_type pt ON (cp.phone_type_id = pt.phone_type_id) ".
				"WHERE cp.contact_id = '".$contact_id."' AND cp.status='A'";
		return $this->executeQuery($q);
	}

	//similar... but not quite the same

	/**
	 * Selects information on a single phone
	 *
	 * @param	int			$contact_phone_id	The contact_pohone_id
	 * @return	resource	A mysql result resource of the format (contact_phone_id, contact_phone_number, contact_phone_ext,
	 *                       phone_country_code, phone_type_description)
	 * @access
	 * @since
	 */
	function selectPhone($contact_phone_id) {
		$q = "SELECT ".
				"cp.contact_phone_id, ".
				"cp.contact_phone_number, ".
				"cp.contact_phone_ext, ".
				"cp.phone_country_code, ".
				"cp.phone_type_id, ".
				"pt.phone_type_description ".
				"FROM contact_phone cp ".
				"LEFT JOIN phone_type pt ON (cp.phone_type_id = pt.phone_type_id) ".
				"WHERE contact_phone_id=".$contact_phone_id." AND cp.status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Selects all active phone types
	 *
	 * @return	resource	A mysql result resource of the format (phone_type_id, phone_type_description)
	 * @access
	 * @since
	 */
	function selectPhoneTypes() {
		$q = "SELECT phone_type_id, phone_type_description ".
				"FROM phone_type";
		return $this->executeQuery($q);
	}


	/**
	 * Selects information about an address, given the address_id
	 *
	 * @param	int			$address_id		The address_id
	 * @return	resource	A mysql result resource of the format (address_id, contact_id, address_type_id, address_street_1,
	 *                       address_street_2, address_city, address_state, address_province, address_zip, address_type_description, name)
	 * @access
	 * @since
	 */
	function selectAddress($address_id) {
		$q = "SELECT ".
				"a.address_id, ".
				"a.contact_id, ".
				"a.address_type_id, ".
				"a.address_street_1, ".
				"a.address_street_2, ".
				"a.address_city, ".
				"a.address_state, ".
				"a.address_province, ".
				"a.address_zip, ".
				"a.address_country_code ".
				"FROM address a ".
				"WHERE a.address_id=$address_id AND a.status='A'";
		return $this->executeQuery($q);
	}



  /**
   * --- Depreciated and NOT USED ---
   *
   * @param	array		$oData
   * @param	string		&$sortField
   * @param	string		&$sortOrder
   * @param	string		&$sort
   * @return	boolean		Should return true on success, will throw an error on failure
   * @access
   * @since
   */
  function setSortOrder($oData, &$sortField, &$sortOrder, &$sort )
    {

    $sortField = (isset($oData[sortField]) ?  $oData[sortField] : "");
    $sortOrder = (isset($oData[sortOrder]) ?  $oData[sortOrder] : "");
    $sort = (($sortField <> "") ?  "ORDER BY " . $sortField . " " . $sortOrder . " " : "");

    } // end func setSortOrder



	/**
	 * Adds a module - role association
	 *
	 * @param	int			$module_id	The module_id
	 * @param	int			$role_id	The role_id
	 * @param	int			$admin_id	The login of the user doing the addition
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function addModuleRole($module_id, $role_id, $admin_id) {
		$q = "SELECT module_role_id ".
				"FROM module_role ".
				"WHERE module_id=$module_id AND role_id=$role_id AND status='A'";
		if (!mysql_fetch_assoc($this->executeQuery($q))) {
			$q = "INSERT INTO module_role (module_id, role_id, created_by, created_date) ".
					"VALUES ($module_id, $role_id, $admin_id, NOW())";
			return $this->executeQuery($q);
		}
	}


	/**
	 * Removes a module role (ACTUALLY DELETES FROM DATABASE)
	 *
	 * @param	int			$module_id	The module_id
	 * @param	int			$role_id	The role_id
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function deleteModuleRole($module_id, $role_id) {
		$q = "DELETE FROM module_role ".
				"WHERE module_id=$module_id AND role_id=$role_id";
		return $this->executeQuery($q);
	}


	/**
	 * Selects all active module roles
	 *
	 * @return	resource	A mysql result resource of the format (module_role_id, module_id, role_id)
	 * @access
	 * @since
	 */
	function selectModuleRoles() {
		$q = "SELECT module_role_id, module_id, role_id ".
				"FROM module_role ".
				"WHERE status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Selects all active modules
	 *
	 * @return	resource	A mysql result resource of the format (module_id, module_description)
	 * @access
	 * @since
	 */
	function selectModules($product_id=array()) {
		$q = "SELECT module_id, product_id, module_description ".
				"FROM module ".
				"WHERE status='A' ";
	   if (sizeof($product_id)>0) {
         $q .= "AND (product_id IN (".implode(", ", $product_id).")) ";
	   }
	   $q .= "ORDER BY module_description ASC";
		return $this->executeQuery($q);
	}


	/**
	 * Selects information about a certain module
	 *
	 * @param	int			$id		The module_id
	 * @return	resource	A mysql result resource of the format (module_description)
	 * @access
	 * @since
	 */
	function selectModule($id) {
		$q = "SELECT module_description ".
				"FROM module ".
				"WHERE status='A' AND module_id=$id";
		return $this->executeQuery($q);
	}


	/**
	 * Selects a count of the number of active modules
	 *
	 * @return	resource	A mysql result resource of the format (COUNT(*))
	 * @access
	 * @since
	 */
	function countModules() {
		$q = "SELECT COUNT(*) count FROM module WHERE status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Selects a count of the number of active roles
	 *
	 * @return	resource	A mysql result resource of the format (COUNT(*))
	 * @access
	 * @since
	 */
	function countRoles() {
		$q = "SELECT COUNT(*) count FROM role WHERE status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Selects information on a certain role
	 *
	 * @param	int			$role_id	The role_id
	 * @return	resource	A mysql result resource of the format (role_id, role_description)
	 * @access
	 * @since
	 */
	function selectRole($role_id) {
		$q = "SELECT role_id, role_description FROM role WHERE status='A' AND role_id=$role_id";
		return $this->executeQuery($q);
	}


	/**
	 * Selects all active roles
	 *
	 * @return	resource	A mysql result resource of the format (role_id, role_description)
	 * @access
	 * @since
	 */
	function selectRoles() {
		$q = "SELECT role_id, role_description FROM role WHERE status='A' ORDER BY role_description";
		return $this->executeQuery($q);
	}


	/**
	 * Selects all active roels along with count of the number of users that are associate with each role
	 *
	 * @return	resource	A mysql result resource of the format (role_id, role_description, count)
	 * @access
	 * @since
	 */
	function selectRolesWithCount() {
		$q = "SELECT r.role_id, r.role_description, COUNT(ur.role_id) count ".
				"FROM role r ".
				"LEFT JOIN user_role ur ON (r.role_id = ur.role_id) ".
				"WHERE r.status='A' AND (ur.status='A'OR ur.status IS NULL) ".
				"GROUP BY r.role_id ".
				"ORDER BY r.role_description ASC";
		return $this->executeQuery($q);
	}


	/**
	 * Selects the users associated with a certain role
	 *
	 * @param	int			$role_id	The role_id
	 * @return	resource	A mysql result resource of the format (user_role_id, login, first_name, last_name)
	 * @access
	 * @since
	 */
	function selectUserRolesByRole($role_id) {
		$q = "SELECT ur.user_role_id, ur.login, u.first_name, u.last_name ".
				"FROM user_role ur ".
				"LEFT JOIN user u ON (ur.login=u.login) ".
				"WHERE ur.role_id=$role_id AND ur.status='A' ".
				"ORDER BY u.last_name ASC, u.first_name ASC";
		return $this->executeQuery($q);
	}


	/**
	 * Selects a count of the number of users associated with a certain role
	 *
	 * @param	int			$role_id	The role_id
	 * @return	resource	A mysql result resource of the format (COUNT(*))
	 * @access
	 * @since
	 */
	function countUserRolesByRole($role_id) {
		$q = "SELECT COUNT(*) FROM user_role WHERE role_id=$role_id AND status='A'";
		return $this->executeQuery($q);
	}


	/**
	 * Inserts a new role
	 *
	 * @param	string		$description	The description of the role
	 * @param	int			$admin_id		The login of the user doing the insertion
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function insertRole($description, $admin_id) {
		$description = mysql_real_escape_string($description);
		$q = "INSERT INTO role (role_description, created_by, created_date) ".
				"VALUES ('$description', $admin_id, NOW())";
		return $this->executeQuery($q);
	}


	/**
	 * Updates a role with new information
	 *
	 * @param	int			$role_id		The role_id
	 * @param	string		$description	The new description
	 * @param	int			$admin_id		The login of the user doing the update
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function updateRole($role_id, $description, $admin_id) {
		$description = mysql_real_escape_string($description);
		$q = "UPDATE role SET role_description='$description', modified_by=$admin_id, modified_date=NOW() ".
				"WHERE role_id=$role_id AND status='A'";
		return $this->executeQuery($q);
   }


	/**
	 * Deletes a role (just sets the status to 'D')
	 *
	 * @param	int			$role_id	The role_id
	 * @param	int			$admin_id	The login of the user doing the deletion
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function deleteRole($role_id, $admin_id) {
		$q = "UPDATE role SET status='D', modified_by=$admin_id, modified_date=NOW() ".
				"WHERE role_id=$role_id;";
		$this->executeQuery($q);
		$q = "DELETE FROM user_role WHERE role_id=$role_id;";
		return $this->executeQuery($q);
	}


	/**
	 * Inserts a user_role, associating a user with a certain role
	 *
	 * @param	int			$login		The login of the user being added
	 * @param	int			$role_id	The role_id
	 * @param	int			$admin_id	The login of the user doing the insertion
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function insertUserRole($login, $role_id, $admin_id) {
		$q = "INSERT INTO user_role (login, role_id, created_by, created_date) ".
				"VALUES ($login, $role_id, $admin_id, NOW())";
		return $this->executeQuery($q);
	}


	/**
	 * Deletes a user-role association (just sets status to 'D')
	 *
	 * @param	int			$user_role_id	The user_role_id
	 * @param	int			$admin_id		The login of the user doing the deletion
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function deleteUserRole($user_role_id, $admin_id) {
		$q = "UPDATE user_role SET status='D', modified_by=$admin_id, modified_date=NOW() ".
				"WHERE user_role_id=$user_role_id";
		return $this->executeQuery($q);
	}


	/**
	 * Checks a password against the hash stored in the user table.
	 *
	 * Result resource will be populated with 1 row if the password matches, 0 otherwise.
	 *
	 * @param	int			$login		The login of the user whose password is being checked
	 * @param	string		$password	The password in question
	 * @return	boolean		Should return true on success, will throw an error on failure
	 * @access
	 * @since
	 */
	function checkPassword($login, $password) {
		$password = mysql_real_escape_string($password);
		$q = "SELECT login FROM user WHERE login=$login AND password=OLD_PASSWORD('$password')";
		return $this->executeQuery($q);
	}


	/**
	 * Selects the password hash of a user from the database
	 *
	 * @param	int			$login	The login of the user
	 * @return	resource	A mysql result resource of the format (password)
	 * @access
	 * @since
	 */
	function selectPassword($login) {
		$q = "SELECT password FROM user WHERE login=$login";
		return $this->executeQuery($q);
	}

	/**
	* GetAdminTables()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 03 13:24:03 PST 2006
	*/
	function GetAdminTables()
	{
      $q =
      "SELECT admin_table_id, admin_table_name, admin_table_description, created_by, created_date, modified_by, modified_date
       FROM admin_table WHERE status='A' ORDER BY `display_order`";

      return $this->executeQuery($q);
	}

	/**
	* GetAdminTable()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 03 13:40:37 PST 2006
	*/
	function GetAdminTable($table_name)
	{
      $q =
      "SELECT admin_table_id, admin_table_name, admin_table_description, created_by, created_date, modified_by, modified_date
       FROM admin_table WHERE admin_table_name = '$table_name' AND status='A'";

      return mysql_fetch_assoc($this->executeQuery($q));

	}

	/**
	* GetARMCBudgetLineDefs()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 31 10:52:07 PST 2006
	*/
	function GetARMCBudgetLineDefs()
	{
      $q = "SELECT
               ablid.armc_budget_line_item_def_id,
               ablid.atm_gl_account_id,
               ablid.armc_budget_line_item_description,
               ablid.item_number,
               ablid.default_rate,
               ablid.default_quantity,
               aga.atm_gl_account_segment1,
               aga.atm_gl_account_segment2,
               aga.atm_gl_account_segment3,
               aga.atm_gl_account_segment4,
               aga.atm_gl_account_segment5,
               aga.atm_gl_account_segment6,
               aga.atm_gl_account_segment7,
               aga.atm_gl_account_description,
               atblid.armc_type_id,
               at.armc_type_description,
               p.product_description
            FROM armc_budget_line_item_def AS ablid
            LEFT JOIN atm_gl_account AS aga ON aga.atm_gl_account_id = ablid.atm_gl_account_id AND aga.status='A'
            LEFT JOIN armc_type_budget_line_item_def AS atblid ON atblid.armc_budget_line_item_def_id = ablid.armc_budget_line_item_def_id AND atblid.status='A'
            LEFT JOIN armc_type AS at ON at.armc_type_id = atblid.armc_type_id AND at.status='A'
            LEFT JOIN product AS p ON p.product_id=at.product_id
            WHERE ablid.status='A'
            ORDER BY ablid.armc_budget_line_item_description, atblid.armc_type_id";
      return $this->executeQuery($q);

	}

	/**
	* GetARMCBudgetLineDef()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 31 16:45:49 PST 2006
	*/
	function GetARMCBudgetLineDef($id)
	{
	   $q = "SELECT
	           ablid.armc_budget_line_item_def_id,
	           ablid.armc_budget_line_item_description,
	           ablid.item_number,
	           ablid.default_rate,
	           ablid.default_quantity,
	           atblid.armc_type_id,
	           ablid.atm_gl_account_id
            FROM armc_budget_line_item_def AS ablid
            LEFT JOIN armc_type_budget_line_item_def AS atblid ON atblid.armc_budget_line_item_def_id = ablid.armc_budget_line_item_def_id AND atblid.status='A'
            WHERE ablid.armc_budget_line_item_def_id='$id' AND ablid.status='A'
            ORDER BY atblid.armc_type_id";

	   return $this->executeQuery($q);
	}

	/**
	* GetATMGLAccounts()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 31 16:58:38 PST 2006
	*/
	function GetATMGLAccounts()
	{
	   $q = "SELECT atm_gl_account_id, atm_gl_account_segment1, atm_gl_account_segment2, atm_gl_account_segment3, atm_gl_account_segment4, atm_gl_account_segment5, atm_gl_account_segment6, atm_gl_account_segment7, atm_gl_account_description FROM atm_gl_account WHERE status='A'";
	   return $this->executeQuery($q);
	}

	/**
	* UpdateARMCBudgetLineDef()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 31 17:19:22 PST 2006
	*/
	function UpdateARMCBudgetLineDef($info)
	{
	   $q = "UPDATE armc_budget_line_item_def SET
	           armc_budget_line_item_description = '".$info["armc_budget_line_item_description"]."',
	           item_number = '".$info["item_number"]."',
	           default_rate = '".$info["default_rate"]."',
	           default_quantity = '".$info["default_quantity"]."',
	           atm_gl_account_id = '".$info["atm_gl_account_id"]."',
	           modified_by = '".$this->created_by."',
	           modified_date = NOW()
	         WHERE armc_budget_line_item_def_id = '".$info["armc_budget_line_item_def_id"]."'";
	   $this->executeQuery($q);
	   return $this->rows;
	}

	/**
	* InsertARMCTypeBudgetLineDef()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 31 17:30:36 PST 2006
	*/
	function InsertARMCTypeBudgetLineDef($armc_budget_line_item_def_id, $armc_type_id)
	{
	   $q = "INSERT INTO armc_type_budget_line_item_def (armc_type_id, armc_budget_line_item_def_id, created_by, created_date, status)
	   VALUES ('$armc_type_id', '$armc_budget_line_item_def_id', '".$this->created_by."', NOW(), 'A')";
	   $this->executeQuery($q);
	   return $this->rows;
	}

	/**
	* DeleteARMCTypeBudgetLineDef()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 31 17:32:42 PST 2006
	*/
	function DeleteARMCTypeBudgetLineDef($armc_budget_line_item_def_id, $armc_type_id=0)
	{
	   $q = "DELETE FROM armc_type_budget_line_item_def WHERE armc_budget_line_item_def_id ='$armc_budget_line_item_def_id'";
	   if ($armc_type_id!=0)
	     $q .= " AND armc_type_id='$armc_type_id'";
	   $this->executeQuery($q);
	   return $this->rows;
	}

	/**
	* InsertARMCBudgetLineDef()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 31 18:13:23 PST 2006
	*/
	function InsertARMCBudgetLineDef($o)
	{
	   $q = "INSERT INTO armc_budget_line_item_def (`armc_budget_line_item_description`, `item_number`, `default_rate`, `default_quantity`, `atm_gl_account_id`, `created_by`, `created_date`, `status`)
	   VALUES ('".$o["armc_budget_line_item_description"]."', '".$o["item_number"]."', '".$o["default_rate"]."', '".$o["default_quantity"]."', '".$o["atm_gl_account_id"]."', '".$this->created_by."', NOW(), 'A')";
	   $this->executeQuery($q);
	   return $this->last_id;
	}

	/**
	* DeleteARMCBudgetLineDef()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Mar 31 17:49:00 PST 2006
	*/
	function DeleteARMCBudgetLineDef($armc_budget_line_item_def_id)
	{
	   $q = "UPDATE armc_budget_line_item_def SET status='D', modified_by='".$this->created_by."', modified_date=NOW() WHERE armc_budget_line_item_def_id='$armc_budget_line_item_def_id'";
	   $this->executeQuery($q);
	   return $this->rows;
	}

	/**
	* GetAccountMergeUpdateDefs()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Sep 26 12:12:13 PDT 2006
	*/
	function GetAccountMergeUpdateDefs()
	{
	   //TODO add code
	   $q = "SELECT account_merge_update_def_id, table_name, table_name_id_field_name, update_field_name, update_expression, where_expression FROM account_merge_update_def WHERE status='A' ORDER BY account_merge_update_def_id";
	   return $this->executeQuery($q);
	}

	/**
	* GetTableFieldValues()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Sep 26 12:23:43 PDT 2006
	*/
	function GetTableFieldValues($table_name, $table_name_id_field, $update_field, $where_expression)
	{
	   //TODO add code
      $q = "SELECT $table_name_id_field, $update_field FROM $table_name WHERE $where_expression";
      return $this->executeQuery($q);
	}

	/**
	* UpdateTableFieldValues()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Sep 26 13:57:54 PDT 2006
	*/
	function UpdateTableFieldValues($table_name, $update_field, $update_expression, $where_expression)
	{
	   //TODO add code
	   $q = "UPDATE $table_name SET $update_field = $update_expression, modified_by='".$this->created_by."', modified_date=NOW() WHERE $where_expression";
	   $this->executeQuery($q);
	   return $this->rows;
	}

	/**
	* InsertAccountMerge()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Sep 26 13:48:27 PDT 2006
	*/
	function InsertAccountMerge($master_account_id, $slave_account_id, $version=2)
	{
	   //TODO add code
	   $q = "INSERT INTO account_merge (`master_account_id`, `slave_account_id`, `version`, `created_by`, `created_date`, `status`) ";
	   $q .= "VALUES ('$master_account_id', '$slave_account_id', '$version', '".$this->created_by."', NOW(), 'A')";
	   $this->executeQuery($q);
	   return $this->last_id;
	}

	/**
	* InsertAccountMergeAttr()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Sep 26 13:51:15 PDT 2006
	*/
	function InsertAccountMergeAttr($account_merge_id, $account_merge_attr_name, $account_merge_attr_value)
	{
	   //TODO add code
	   $q = "INSERT INTO account_merge_attr (`account_merge_id`, `account_merge_attr_name`, `account_merge_attr_value`, `created_by`, `created_date`, `status`) ";
	   $q .= "VALUES ('$account_merge_id', '$account_merge_attr_name', '$account_merge_attr_value', '".$this->created_by."', NOW(), 'A')";
	   $this->executeQuery($q);
	   return $this->last_id;
	}

	/**
	* InsertAccountMergeUpdate()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Sep 26 14:34:19 PDT 2006
	*/
	function InsertAccountMergeUpdate($account_merge_update_def_id, $account_merge_id, $table_name_id_field_value, $update_field_value_before_update, $update_field_value_after_update)
	{
	   //TODO add code
	   $q = "INSERT INTO account_merge_update (`account_merge_update_def_id`, `account_merge_id`, `table_name_id_field_value`, `update_field_value_before_update`, `update_field_value_after_update`, `created_by`, `created_date`, `status`) ";
	   $q .= "VALUES ('$account_merge_update_def_id', '$account_merge_id', '$table_name_id_field_value', '$update_field_value_before_update', '$update_field_value_after_update', '".$this->created_by."', NOW(), 'A')";
	   $this->executeQuery($q);
	   return $this->last_id;
	}

	/**
	* InsertAccountMergeComment()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Sep 29 10:38:23 PDT 2006
	*/
	function InsertAccountMergeComment($account_merge_id, $account_comment_type_id, $comment_text)
	{
	   //TODO add code
	   $q = "INSERT INTO account_merge_comment (`account_merge_id`, `account_comment_type_id`, `comment_text`, `created_by`, `created_date`, `status`) ";
	   $q .= "VALUES ('$account_merge_id', '$account_comment_type_id', '".mysql_real_escape_string($comment_text)."', '".$this->created_by."', NOW(), 'A')";
	   $this->executeQuery($q);
	   return $this->last_id;
	}

	/**
	* InsertAccountEvent()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Sep 29 10:42:47 PDT 2006
	*/
	function InsertAccountEvent($account_id, $account_event_type_id, $comment)
	{
	   //TODO add code
	   $q = "INSERT INTO account_event (`account_id`, `account_event_type_id`, `remote_address`, `user_agent`, `comment`, `created_by`, `created_date`, `status`) ";
	   $q .= "VALUES ('$account_id', '$account_event_type_id', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', '".mysql_real_escape_string($comment)."', '".$this->created_by."', NOW(), 'A')";
	   $this->executeQuery($q);
	   return $this->last_id;
	}

	/**
	* GetAccountMergesByMaster()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Sep 26 14:59:22 PDT 2006
	*/
	function GetAccountMergesByMaster($master_account_id)
	{
	   //TODO add code
	   $q = "SELECT
	           am.account_merge_id,
	           am.master_account_id,
	           am.slave_account_id,
	           am.created_by,
	           CONCAT(u.first_name, ' ', u.last_name) AS created_by_name,
	           am.created_date,
	           ama_master_name.account_merge_attr_value AS master_account_name,
	           ama_slave_name.account_merge_attr_value AS slave_account_name,
	           amc.comment_text
	         FROM account_merge AS am
	         LEFT JOIN user AS u ON u.login = am.created_by
	         LEFT JOIN account_merge_attr AS ama_master_name ON ama_master_name.account_merge_id=am.account_merge_id AND ama_master_name.account_merge_attr_name = 'MASTER_ACCOUNT_NAME'
	         LEFT JOIN account_merge_attr AS ama_slave_name ON ama_slave_name.account_merge_id=am.account_merge_id AND ama_slave_name.account_merge_attr_name = 'SLAVE_ACCOUNT_NAME'
	         LEFT JOIN account_merge_comment AS amc ON amc.account_merge_id=am.account_merge_id AND amc.account_comment_type_id=".ACCOUNT_COMMENT_TYPE_MERGE."
	         WHERE am.master_account_id = '$master_account_id' AND am.status='A'
	         GROUP BY am.account_merge_id";
	   return $this->executeQuery($q);
	}

	/**
	* GetAccountMerge()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Sep 29 11:04:33 PDT 2006
	*/
	function GetAccountMerge($account_merge_id)
	{
	   //TODO add code
	   $q = "SELECT
	           am.account_merge_id,
	           am.master_account_id,
	           ama1.account_merge_attr_value AS master_account_name,
	           am.slave_account_id,
	           ama2.account_merge_attr_value AS slave_account_name,
	           am.version
	         FROM account_merge AS am
	         LEFT JOIN account_merge_attr AS ama1 ON ama1.account_merge_id=am.account_merge_id AND ama1.account_merge_attr_name='MASTER_ACCOUNT_NAME'
	         LEFT JOIN account_merge_attr AS ama2 ON ama2.account_merge_id=am.account_merge_id AND ama2.account_merge_attr_name='SLAVE_ACCOUNT_NAME'
	         WHERE am.account_merge_id='$account_merge_id'";
	   return mysql_fetch_assoc($this->executeQuery($q));
	}

	/**
	* GetAccountMergeUpdatesByMergeId()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Sep 26 15:42:20 PDT 2006
	*/
	function GetAccountMergeUpdatesByMergeId($account_merge_id)
	{
	   //TODO add code
	   $q = "SELECT
	           amud.table_name,
	           amud.table_name_id_field_name,
	           amu.table_name_id_field_value,
	           amud.update_field_name,
	           amu.update_field_value_before_update,
	           amu.update_field_value_after_update
	         FROM account_merge_update AS amu
	         LEFT JOIN account_merge_update_def AS amud ON amud.account_merge_update_def_id = amu.account_merge_update_def_id
	         WHERE amu.account_merge_id = '$account_merge_id' AND amu.status='A'";
	   return $this->executeQuery($q);
	}

	/**
	* DeleteAccountMergeUpdates()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Sep 29 10:54:20 PDT 2006
	*/
	function DeleteAccountMergeUpdates($account_merge_id)
	{
	   //TODO add code
	   $q = "UPDATE account_merge_update SET status='D', modified_by='".$this->created_by."', modified_date=NOW() WHERE account_merge_id='$account_merge_id'";
	   $this->executeQuery($q);
	   return $this->rows;
	}

	/**
	* DeleteAccountMergeComments()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Sep 29 10:55:14 PDT 2006
	*/
	function DeleteAccountMergeComments($account_merge_id)
	{
	   //TODO add code
	   $q = "UPDATE account_merge_comment SET status='D', modified_by='".$this->created_by."', modified_date=NOW() WHERE account_merge_id='$account_merge_id'";
	   $this->executeQuery($q);
	   return $this->rows;
	}

	/**
	* DeleteAccountMergeAttrsByMergeId()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Sep 26 15:50:10 PDT 2006
	*/
	function DeleteAccountMergeAttrsByMergeId($account_merge_id)
	{
	   //TODO add code
	   $q = "UPDATE account_merge_attr SET status='D', modified_by='".$this->created_by."', modified_date=NOW() WHERE account_merge_id='$account_merge_id'";
	   $this->executeQuery($q);
	   return $this->rows;
	}

	/**
	* DeleteAccountMerge()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Sep 26 15:51:09 PDT 2006
	*/
	function DeleteAccountMerge($account_merge_id)
	{
	   //TODO add code
	   $q = "UPDATE account_merge SET status='D', modified_by='".$this->created_by."', modified_date=NOW() WHERE account_merge_id='$account_merge_id'";
	   $this->executeQuery($q);
	   return $this->rows;
	}
}

?>
