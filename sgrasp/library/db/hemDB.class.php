<?php
define('HBDEV_SECURITY_GROUP_ID', 19);


class hemDB extends dbConnect
{

/**
* hemDB()
*
* @param	
* @return	
* @access	
* @since	
*/
	function hemDB($options = array())
	{
		$this->dbConnect($options);
	}


function selectNextOldestBugFeature($id) {
	$q = "SELECT bugfeature_id FROM bugfeature b WHERE b.bugfeature_id < $id AND b.status='A' ORDER BY bugfeature_id DESC LIMIT 1";
	return $this->executeQuery($q);
}

function selectNextNewestBugFeature($id) {
	$q = "SELECT bugfeature_id FROM bugfeature b WHERE b.bugfeature_id > $id AND b.status='A' ORDER BY bugfeature_id ASC LIMIT 1";
	return $this->executeQuery($q);
}

/**
* Selects the active components that correspond to a certain module
*
* @param	int		$module_id	The module_id of the associated module
* @return	resource	A mysql result resource of the form (component_id,  component_description)
*/
function selectComponentsByModule($module_id) {
	$q = "SELECT component_id, component_description FROM component WHERE module_id=$module_id AND status='A'";
	return $this->executeQuery($q);
}

/**
 * Selects version information
 * 
 * @param	int		$version_id		The version_id
 * @return	resource	A mysql result resource of the form (verstion_title, version_description)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectVersion($version_id) {
	$q = "SELECT version_title, version_description ".
			"FROM version ".
			"WHERE version_id=$version_id AND status='A'";
	return $this->executeQuery($q);
}

/**
 * Selects all the active version numbers from the version table.
 * 
 * @return	resource	A mysql result resource of the form (version_id, verstion_title, version_description)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectVersions() {
	$q = "SELECT version_id, version_title, version_description ".
			"FROM version ".
			"WHERE status='A'";
	return $this->executeQuery($q);
}

/**
 * Selects all the bugfeature requests initiated by a certain user.
 * 
 * @param	int		$login	The login of the user
 * @return	resource	A mysql result resource of the form (bugfeature_id, bugfeature_title, 
 *                       bugfeature_status_description, first_name, last_name)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectBugFeaturesByUser($login) {
	$q = "SELECT b.bugfeature_id, b.bugfeature_title, bs.bugfeature_status_description, u.first_name, u.last_name ".
	        "FROM bugfeature b ".
	        "LEFT JOIN bugfeature_status bs ON (b.bugfeature_status_id = bs.bugfeature_status_id) ".
	        "LEFT JOIN bugfeature_comment bc ON (b.bugfeature_id = bc.bugfeature_id) ".
	        "LEFT JOIN user u ON (u.login = bc.login) ".
			"WHERE bc.bugfeature_comment_type_id=1 AND bc.status='A' AND b.status='A' AND bc.login=$login ".
			"ORDER BY b.bugfeature_id DESC";
	return $this->executeQuery($q);
}

/**
 * Selects information about a specific component.
 * 
 * @param	int		$component_id	The component id
 * @return	resource	A mysql result resource of the form (component_description)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectComponent($component_id) {
	$q = "SELECT component_description FROM component WHERE component_id=$component_id AND status='A'";
	return $this->executeQuery($q);
}

/**
 * Selects information about a specific module.
 * 
 * @param	int		$module_id	The module id
 * @return	resource	A mysql result resource of the form (module_description)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectModule($module_id) {
	$q = "SELECT module_description FROM module WHERE module_id=$module_id AND status='A'";
	return $this->executeQuery($q);
}

/**
 * Selects users associated with a certain role in a bug/feature request.
 * 
 * @param	int		$bfid		The bugfeature id
 * @param	int		$role_id	The role id
 * @return	resource	A mysql result resource of the form (bugfeature_user_id, role_id, login, 
 *                       first_name, last_name, email_address)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectBugFeatureUsersByRole($bfid, $role_id) {
	$q = "SELECT b.bugfeature_user_id, b.role_id, b.login, u.first_name, u.last_name, u.email_address ".
			"FROM bugfeature_user b ".
			"LEFT JOIN user u ON (b.login = u.login) ".
			"WHERE bugfeature_id=$bfid AND role_id=$role_id AND b.status='A' ".
			"ORDER BY u.last_name ASC";
	return $this->executeQuery($q);
}

/**
 * Selects files associated with a bug/feature
 * 
 * @param	int		$bfid	The bugfeature id
 * @return	resource	A mysql result resource of the form (bugfeature_file_id, bugfeature_file_name, bugfeature_file_size)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectBugFeatureFiles($bfid) {
	$q = "SELECT bugfeature_file_id, bugfeature_file_name, bugfeature_file_size ".
			"FROM bugfeature_file ".
			"WHERE bugfeature_id=$bfid AND status='A'";
	return $this->executeQuery($q);
}

/**
 * Selects a bugfeature file
 * 
 * @param	int		$bffid	The bugfeature_file id
 * @return	resource	A mysql result resource of the form (bugfeature_file_name, bugfeature_file_size,
 *                       bugfeature_file_data, file_type_description)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectBugFeatureFile($bffid) {
	$q = "SELECT b.bugfeature_file_name, b.bugfeature_file_size, b.bugfeature_file_data, f.file_type_description ".
			"FROM bugfeature_file b ".
			"JOIN file_type f ON (b.file_type_id = f.file_type_id) ".
			"WHERE b.bugfeature_file_id=$bffid AND b.status='A'";
	return $this->executeQuery($q);
}

/**
 * Inserts a new bugfeature file into the bugfeature_file table
 * 
 * @param	int		$bfid		The bugfeature id
 * @param	int		$ftid		The file type id
 * @param	string	$name		The name of the file
 * @param	int		$size		The size of the file
 * @param 	string	$data		The file data
 * @param 	int		$admin_id	The login of the user inserting the file
 * @return	resource	Should return true on success, will cause an error on failure
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function insertBugFeatureFile($bfid, $ftid, $name, $size, $data, $admin_id) {
	$data = mysql_real_escape_string($data);
	$name = mysql_real_escape_string($name);
	$q = "INSERT INTO bugfeature_file (bugfeature_id, ".
									  "file_type_id, ".
									  "bugfeature_file_name, ".
									  "bugfeature_file_size, ".
									  "bugfeature_file_data, ".
									  "created_by, ".
									  "created_date) ".
			"VALUES ($bfid, $ftid, '$name', $size, '$data', $admin_id, NOW())";
	return $this->executeQuery($q);
}

/**
 * Selects all users that are members of the HB Developer security group
 * 
 * @return	resource	A mysql result resource of the form (devel_login, devel_name, devel_email)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectHBDevelopers() {
	$q = "SELECT u.login devel_login, CONCAT(u.first_name, ' ', u.last_name) devel_name, u.email_address devel_email ".
			"FROM user_security_group usg ".
			"LEFT JOIN user u ON (usg.login = u.login) ".
			"WHERE usg.security_group_id=".HBDEV_SECURITY_GROUP_ID." AND usg.status='A' AND u.status='A'";
	return $this->executeQuery($q);
}

/**
 * Selects the active statuses in the bugfeature_status table.
 * 
 * @return	resource	A mysql result resource of the form (bugfeature_status_id, bugfeature_status_description)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectBugFeatureStatuss() {
	$q = "SELECT bugfeature_status_id, bugfeature_status_description FROM bugfeature_status WHERE status='A'";
	return $this->executeQuery($q);
}

/**
 * Selects the active priorities in the bugfeature_priority table
 * 
 * @return	resource	A mysql result resource of the form (bugfeature_priority_id, bugfeature_priority_description)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectBugFeaturePriorities() {
	$q = "SELECT bugfeature_priority_id, bugfeature_priority_description FROM bugfeature_priority WHERE status='A'";
	return $this->executeQuery($q);
}

/**
 * Selects info on a bugfeature priority
 * 
 * @param	int		$priority_id	The bugfeature_priority_id
 * @return	resource	A mysql result resource of the form (bugfeature_priority_description)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectBugFeaturePriority($priority_id) {
	$q = "SELECT bugfeature_priority_description FROM bugfeature_priority WHERE status='A' AND bugfeature_priority_id=$priority_id";
	return $this->executeQuery($q);
}

/**
 * Selects info on a bugfeature type
 * 
 * @param	int		$bf_type_id		The bugfeature_type
 * @return	resource	A mysql result resource of the form (bugfeature_type_description)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectBugFeatureType($bf_type_id) {
	$q = "SELECT bugfeature_type_description FROM bugfeature_type WHERE bugfeature_type_id=$bf_type_id AND status='A'";
	return $this->executeQuery($q);
}

/**
 * Selects comments associated with a bugfeature
 * 
 * @param	int		$bugfeature_id	The bugfeature_id
 * @return	resource	A mysql result resource of the form (bugfeature_comment_id, bugfeature_comment_type_id, 
 *                       bugfeature_comment_text, bugfeature_comment_date, login)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectBugFeatureComments($bugfeature_id) {
	$q = "SELECT bugfeature_comment_id, bugfeature_comment_type_id, bugfeature_comment_text, bugfeature_comment_date, c.login, ".
			"u.first_name, u.last_name, u.email_address ".
			"FROM bugfeature_comment c ".
			"LEFT JOIN user u ON (c.login = u.login) ".
			"WHERE bugfeature_id=$bugfeature_id AND c.status='A'";
	return $this->executeQuery($q);
}

/**
 * Selects information about the user who reported a bugfeature
 * 
 * @param	int		$bfid	The bugfeature_id
 * @return	resource	A mysql result resource of the form (login, bugfeature_comment_date, first_name, last_name, email_address)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectBugFeatureReporter($bfid) {
	$q = "SELECT c.login, c.bugfeature_comment_date, u.first_name, u.last_name, u.email_address ".
			"FROM bugfeature_comment c ".
			"LEFT JOIN user u ON (c.login = u.login) ".
			"WHERE bugfeature_id=$bfid AND bugfeature_comment_type_id=1 AND c.status='A'";
	return $this->executeQuery($q);
}

/**
 * Selects information about a bugfeature
 * 
 * @param	int		$bugfeature_id	The bugfeature_id
 * @return	resource	A mysql result resource of the form (bugfeature_type_id, bugfeature_type_description, 
 *                       bugfeature_title, bugfeature_priority_id, bugfeature_user_agent_id, bugfeature_user_agent_description, 
 *                       bugfeature_status_id, bugfeature_status_description, module_id, module_description, 
 *                       component_id, component_description)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectBugFeature($bugfeature_id) {
	$q = "SELECT b.bugfeature_type_id, ".
				"bt.bugfeature_type_description, ".
				"b.bugfeature_title, ".
				"b.bugfeature_priority_id, ".
				"b.bugfeature_user_agent_id, ".
				"ua.bugfeature_user_agent_description, ".
				"b.bugfeature_status_id, ".
				"bs.bugfeature_status_description, ".
				"b.module_id, ".
				"m.module_description, ".
				"b.component_id, ".
				"c.component_description ".
			"FROM bugfeature b ".
			"LEFT JOIN bugfeature_type bt ON (b.bugfeature_type_id = bt.bugfeature_type_id) ".
			"LEFT JOIN bugfeature_status bs ON (b.bugfeature_status_id = bs.bugfeature_status_id) ".
			"LEFT JOIN module m ON (b.module_id = m.module_id) ".
			"LEFT JOIN component c ON (b.component_id = c.component_id) ".
			"LEFT JOIN bugfeature_user_agent ua ON (b.bugfeature_user_agent_id = ua.bugfeature_user_agent_id) ".
			"WHERE b.bugfeature_id=$bugfeature_id AND b.status='A' ".
			"ORDER BY b.bugfeature_id DESC";
	return $this->executeQuery($q);
}

/**
 * Selects some active bugfeatures
 * 
 * @param	int		$start	The starting result row to return (1st arg to LIMIT)
 * @param 	int		$page	The total number of results to return (2nd arg to LIMIT)
 * @return	resource	A mysql result resource of the form (bugfeature_id, bugfeature_type_id, bugfeature_type_description, 
 *                       bugfeature_title, bugfeature_priority_id, bugfeature_user_angent_id, bugfeature_user_agent_desription, 
 *                       bugfeature_status_id, module_id, module_description, component_id, component_description)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectBugFeatures($start, $page) {
	$q = "SELECT b.bugfeature_id, ".
				"b.bugfeature_type_id, ".
				"bt.bugfeature_type_description, ".
				"b.bugfeature_title, ".
				"b.bugfeature_priority_id, ".
				"b.bugfeature_user_agent_id, ".
				"ua.bugfeature_user_agent_description, ".
				"b.bugfeature_status_id, ".
				"b.module_id, ".
				"m.module_description, ".
				"b.component_id, ".
				"c.component_description ".
			"FROM bugfeature b ".
			"LEFT JOIN bugfeature_type bt ON (b.bugfeature_type_id = bt.bugfeature_type_id) ".
			"LEFT JOIN module m ON (b.module_id = m.module_id) ".
			"LEFT JOIN component c ON (b.component_id = c.component_id) ".
			"LEFT JOIN bugfeature_user_agent ua ON (b.bugfeature_user_agent_id = ua.bugfeature_user_agent_id) ".
			"WHERE b.status='A' ".
			"ORDER BY b.bugfeature_id DESC ".
			"LIMIT $start, $page";
	return $this->executeQuery($q);
}

/**
 * Selects active bugfeatures that have not yet been reviewed.
 * 
 * @param	int		$start	The starting result row to return (1st arg to LIMIT)
 * @param 	int		$page	The total number of results to return (2nd arg to LIMIT)
 * @return	resource	A mysql result resource of the form (bugfeature_id, bugfeature_type_id, bugfeature_type_description, 
 *                       bugfeature_title, bugfeature_priority_id, bugfeature_user_angent_id, bugfeature_user_agent_desription, 
 *                       bugfeature_status_id, module_id, module_description, component_id, component_description)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectUnreviewedBugFeatures($start, $page) {
	$q = "SELECT b.bugfeature_id, ".
				"b.bugfeature_type_id, ".
				"bt.bugfeature_type_description, ".
				"b.bugfeature_title, ".
				"b.bugfeature_priority_id, ".
				"b.bugfeature_user_agent_id, ".
				"ua.bugfeature_user_agent_description, ".
				"b.bugfeature_status_id, ".
				"b.module_id, ".
				"m.module_description, ".
				"b.component_id, ".
				"c.component_description ".
			"FROM bugfeature b ".
			"LEFT JOIN bugfeature_type bt ON (b.bugfeature_type_id = bt.bugfeature_type_id) ".
			"LEFT JOIN module m ON (b.module_id = m.module_id) ".
			"LEFT JOIN component c ON (b.component_id = c.component_id) ".
			"LEFT JOIN bugfeature_user_agent ua ON (b.bugfeature_user_agent_id = ua.bugfeature_user_agent_id) ".
			"WHERE b.status='A' AND (b.bugfeature_status_id=0 OR b.bugfeature_status_id=1) ".
			"ORDER BY b.bugfeature_id DESC ".
			"LIMIT $start, $page";
	return $this->executeQuery($q);
}

/**
 * Updates a bugfeature
 * 
 * @param	int 	$bfid			The bugfeature_id
 * @param 	int		$type_id		The bugfeature_type_id
 * @param	string	$title			The bugfeature_title
 * @param 	int		$priority_id	The bugfeature_priority_id
 * @param 	int		$status_id		The bugfeature_status_id
 * @param 	int		$module_id		The module_id
 * @param 	int		$component_id	The component_id
 * @param 	int		$admin_id		The login of the user doing the updating
 * @return	resource	Returns true on success, should throw an error on failure. 
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function updateBugFeature($bfid, $type_id, $title, $priority_id, $status_id, $module_id, $component_id, $admin_id) {
	$title = mysql_real_escape_string($title);
	$q = "UPDATE bugfeature SET ".
				"bugfeature_type_id=$type_id, ".
				"bugfeature_title='$title', ".
				"bugfeature_priority_id=$priority_id, ".
				"bugfeature_status_id=$status_id, ".
				"module_id=$module_id, ".
				"component_id=$component_id, ".
				"modified_by=$admin_id, ".
				"modified_date=NOW() ".
			"WHERE bugfeature_id=$bfid AND status='A'";
	return $this->executeQuery($q);
}

/**
 * Updates (adds if necessary) a bugfeature_attr, of which a bugfeature may only have one.
 * 
 * @param	int		$bfid		The bugfeature_id
 * @param 	string	$name		The name of the bugfeature_attr
 * @param	string	$value		The new value
 * @param 	int		$admin_id	The login of the user doing the updating
 * @return	resource	Returns true on success, should throw an error on failure.
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function updateSingleBugFeatureAttr($bfid, $name, $value, $admin_id) {
	$q = "SELECT bugfeature_attr_id FROM bugfeature_attr WHERE bugfeature_id=$bfid AND bugfeature_attr_name='$name' AND status='A'";
	$this->executeQuery($q);
	if ($this->rows > 0) {
		$q = "UPDATE bugfeature_attr SET bugfeature_attr_value='$value', modified_by=$admin_id, modified_date=NOW() ".
				"WHERE bugfeature_id=$bfid AND bugfeature_attr_name='$name' AND status='A'";
	} else {
		$q = "INSERT INTO bugfeature_attr (bugfeature_id, bugfeature_attr_name, bugfeature_attr_value, created_by, created_date) ".
				"VALUES ($bfid, '$name', '$value', $admin_id, NOW())";
	}
	return $this->executeQuery($q);
}

/**
 * Ties a user to a particular role on a bugfeature. Also adds them and their role to
 *  the user_role table if necessary.
 * 
 * @param	int		$login		The login of the user being added
 * @param	int		$role_id	The rold_id of the role for the user
 * @param	int		$bfid		The bugfeature_id to associate with
 * @param	int		$admin_id	The login of the user doing the adding
 * @return	resource	Returns true on success, should throw an error on failure.
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function addBugFeatureUserRole($login, $role_id, $bfid, $admin_id) {
	$q = "SELECT login FROM user_role WHERE login=$login AND role_id=$role_id AND status='A'";
	if (!mysql_fetch_assoc($this->executeQuery($q))) {
		if ($login != 0) {
			$q = "INSERT INTO user_role (login, role_id, created_by, created_date) ".
					"VALUES ($login, $role_id, $admin_id, NOW())";
			$this->executeQuery($q);
		}
	}

	$q = "INSERT INTO bugfeature_user (login, role_id, bugfeature_id, created_by, created_date) ".
			"VALUES ($login, $role_id, $bfid, $admin_id, NOW())";
	return $this->executeQuery($q);
}


/**
 * Sets a bugfeature_user status to 'D', effectively deleting it.
 * 
 * @param	int		$login		The login of the user to remove
 * @param 	int		$role_id	The role_id of the role that user plays
 * @param	int		$bfid		The bugfeature_id of the bugfeature with which the user is associated
 * @param	int		$admin_id	The login of the user doing the deleting
 * @return	resource	Returns true on success, should throw an error on failure. 
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function deleteBugFeatureUserRole($login, $role_id, $bfid, $admin_id) {
	$q = "UPDATE bugfeature_user ".
			"SET status='D', modified_by=$admin_id, modified_date=NOW() ".
			"WHERE login=$login AND role_id=$role_id AND bugfeature_id=$bfid AND status='A'";
	return $this->executeQuery($q);
}

/**
 * Updates bugfeature_user in a role that can only have one user associated with it (assigned_to, qa_contact)
 * 
 * @param	int		$login		The login of the user being set
 * @param	int		$role_id	The role of the user
 * @param	int		$bfid		The bugfeature_id
 * @param	int		$admin_id	The login of the user doing the update
 * @return	resource	Returns true on success, should throw an error on failure. 
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function updateSingularUserRole($login, $role_id, $bfid, $admin_id) {
	$q = "SELECT login FROM user_role WHERE login=$login AND role_id=$role_id AND status='A'";
	if (!mysql_fetch_assoc($this->executeQuery($q))) {
		if ($login != 0) {
			$q = "INSERT INTO user_role (login, role_id, created_by, created_date) ".
					"VALUES ($login, $role_id, $admin_id, NOW())";
			$this->executeQuery($q);
		}
	}
	
	$q = "SELECT bugfeature_user_id ".
			"FROM bugfeature_user ".
			"WHERE bugfeature_id=$bfid AND login=$login AND role_id=$role_id AND status='A'";
	if (!mysql_fetch_assoc($this->executeQuery($q))) {
		$q = "UPDATE bugfeature_user ".
				"SET status='D' ".
				"WHERE bugfeature_id=$bfid AND role_id=$role_id";
		$this->executeQuery($q);
		$q = "INSERT INTO bugfeature_user (bugfeature_id, role_id, login, created_by, created_date) ".
				"VALUES ($bfid, $role_id, $login, $admin_id, NOW())";
		$this->executeQuery($q);
	}
}

/**
 * Selects values from bugfeature_attr
 * 
 * @param	int		$bfid	The bugfeature_id
 * @param	int		$name	The name of the bugfeature_attr
 * @return	resource	A mysql result resource of the form (bugfeature_attr_value)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectBugFeatureAttr($bfid, $name) {
	$q = "SELECT bugfeature_attr_value FROM bugfeature_attr WHERE bugfeature_id=$bfid AND bugfeature_attr_name='$name' AND status='A'";
	return $this->executeQuery($q);
}

/**
 * Counts the number of active bugfeatures
 * 
 * @return	resource	A mysql result resource of the form (COUNT(*))
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function countBugFeatures() {
	$q = "SELECT COUNT(*) FROM bugfeature WHERE status='A'";
	return $this->executeQuery($q);
}

/**
 * Counts the number of active bugfeatures that have yet to be reviewed (bugfeature_status_id = 0 or 1)
 * 
 * @return	resource	A mysql result resource of the form (COUNT(*))
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function countUnreviewedBugFeatures() {
	$q = "SELECT COUNT(*) FROM bugfeature WHERE status='A' AND (bugfeature_status_id=0 OR bugfeature_status_id=1)";
	return $this->executeQuery($q);
}

/**
 * Inserts a comment for a bugfeature
 * 
 * @param	int		$bugfeature_id		The bugfeature_id
 * @param	int		$comment_type_id	The comment_type_id
 * @param	string	$text				The text of the comment
 * @param	int		$login				The login of the user adding the comment
 * @return	resource	Returns true on success, should throw an error on failure.
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function insertBugFeatureComment($bugfeature_id, $comment_type_id, $text, $admin_id) {
	$text = mysql_real_escape_string($text);
	$q = "INSERT INTO bugfeature_comment (bugfeature_id, bugfeature_comment_type_id, bugfeature_comment_text, login, bugfeature_comment_date, created_by, created_date) ".
			"VALUES ($bugfeature_id, $comment_type_id, '$text', $admin_id, NOW(), $admin_id, NOW())";
	return $this->executeQuery($q);
}

/**
 * Selects all active bugfeature types
 * 
 * @return	resource	A mysql result resource of the form (bugfeature_type_id, bugfeature_type_description)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectBugFeatureTypes() {
	$q = "SELECT bugfeature_type_id, bugfeature_type_description FROM bugfeature_type WHERE status='A'";
	return $this->executeQuery($q);
}

/**
 * Inserts a new bugfeature
 * 
 * @param	int		$type_id		The bugfeature_type_id
 * @param	int		$title			The title (subject) of the bugfeature
 * @param	int		$user_agent_id	The bugfeature_user_agent_id
 * @param	int		$module_id		The module_id
 * @param	int		$component_id	The component_id
 * @param	int		$admin_id		The login of the user inserting the comment
 * @return	resource	Returns true on success, should throw an error on failure.
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function insertBugFeature($type_id, $title, $user_agent_id, $module_id, $component_id, $admin_id) {
	$title = mysql_real_escape_string($title);
	$q = "INSERT INTO bugfeature ".
			"(bugfeature_type_id, bugfeature_title, ".
				"bugfeature_user_agent_id, module_id, component_id, created_by, created_date) ".
			"VALUES ($type_id, '$title', ".
				"$user_agent_id, $module_id, $component_id, $admin_id, NOW())";
	return $this->executeQuery($q);
}

/**
 * Inserts a new user agent string
 * 
 * @param	string	$description	The full user_agent string pulled from the browser
 * @param	int		$admin_id		The login of the user adding this string
 * @return	resource	Returns true on success, will throw an error on failure. 
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function insertBugFeatureUserAgent($description, $admin_id) {
	$description = mysql_real_escape_string($description);
	$q = "INSERT INTO bugfeature_user_agent (bugfeature_user_agent_description, created_by, created_date) ".
			"VALUES ('$description', $admin_id, NOW())";
	return $this->executeQuery($q);
}

/**
 * Selects a user agent string
 * Just used to check if a specific string already exists in the table.
 * 
 * @param	string	$description	The full user_agent string pulled from the browser.
 * @return	resource	A mysql result resource of the form (bugfeature_user_agent_id)
 * @access	
 * @since	HEM 1.0, BRD 1.5
 */
function selectBugFeatureUserAgentByDescription($description) {
	$description = mysql_real_escape_string($description);
	$q = "SELECT bugfeature_user_agent_id ".
		 "FROM bugfeature_user_agent ".
		 "WHERE bugfeature_user_agent_description='$description' AND status='A'";
	return $this->executeQuery($q);
}

	
/**
* Searches for users whose first name, last name, or login are LIKE '%val%'
*
* @param	string	$val	The string to search for.
* @param 	int		$max	The maximum number of results to return.
* @return	resource	A mysql result resource of the form (login, first_name, last_name)
* @access	
* @since	HEM 1.0, BRD 1.4
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
* Selects all active release notes from the database.
*
* @return	resource	A mysql result resource of the form (release_note_id, release_note_title, 
*                   	 release_note_version, release_note_date, file_type_id)
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function selectReleaseNotes() {
		$q = "SELECT release_note_id, release_note_title, release_note_version, DATE(release_note_date) release_note_date, r.file_type_id, ft.file_type_description ".
				"FROM release_note r ".
				"LEFT JOIN file_type ft ON (r.file_type_id = ft.file_type_id) ".
				"WHERE r.status='A' ".
				"ORDER BY r.sort_order ASC";
		return $this->executeQuery($q);
	}

/**
* Selects information on one release note, including the file data.
*
* @param	int		$id		The release_note_id of the desired release note.
* @return	resource	A mysql result resource of the form (release_note_file, release_note_file_type,
*                        file_type_description)
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function selectReleaseNote($id) {
		$q = "SELECT release_note_file, release_note_file_name, file_type_description ".
				"FROM release_note rn ".
				"JOIN file_type ft ON (rn.file_type_id = ft.file_type_id) ".
				"WHERE rn.release_note_id=$id";
		return $this->executeQuery($q);
	}
	
/**
* Inserts a new release note into the database
*
* @param	string	$title		The title or description of the release note
* @param	string	$version	The version of the release note
* @param	int		$ftid		The file_type_id matching the MIME type of the release note (pulled from the file_type table)
* @param	string	$data		The file data, read from disk with getFile();
* @param	string	$filename	The file name of the file to store
* @param	int		$admin_id	The admin_id of the user creating this file
* @return	boolean	Should return true on success, will cause an error on failure.
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function insertReleaseNote($title, $version, $date, $ftid, $data, $filename, $admin_id) {
		$title = mysql_real_escape_string($title);
		$version = mysql_real_escape_string($version);
		$data = mysql_real_escape_string($data);
		$filename = mysql_real_escape_string($filename);
		$q = "INSERT INTO release_note (release_note_title, release_note_version, release_note_date, file_type_id, release_note_file, release_note_file_name, created_by, created_date) ".
				"VALUES ('$title', '$version', $date, $ftid, '$data', '$filename', $admin_id, NOW())";
		return $this->executeQuery($q);
	}

/**
* Deletes a release note from the database.
*
* Does not actually remove the record, but rather sets its status to 'D'
*
* @param	int		$id			The release_note_id of the release note to be deleted.
* @param	int		$admin_id	The admin_id of the user doing the deletion 
* @return	boolean Should return true on success, will cause an error on failure. 
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function deleteReleaseNote($id, $admin_id) {
		$q = "UPDATE release_note ".
				"SET status='D', modified_by=$admin_id, modified_date=NOW() ".
				"WHERE release_note_id=$id";
		return $this->executeQuery($q);
	}
	


/**
* Inserts a new file type into the file_type table.
*
* @param	string	$description	The MIME type description of the file
* @param	int		$admin_id		The user's admin_id value
* @return	boolean	Should return true on success, will throw error on failure
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function insertFileType($description, $admin_id) {
		$description = mysql_real_escape_string($description);
		$q = "INSERT INTO file_type (file_type_description, created_by, created_date) ".
				"VALUES ('$description', $admin_id, NOW())";
		return $this->executeQuery($q);
	}
	
/**
* Selects a file_type_id from a MIME type description
*
* @param	string	$description	The MIME type of the file ("text/plain", "application/msword", etc.)
* @return	resource	The mysql result resource in the format (file_type_id)
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function selectFileTypeId($description) {
		$description = mysql_real_escape_string($description);
		$q = "SELECT file_type_id ".
				"FROM file_type ".
				"WHERE status='A' AND file_type_description='".$description."'";
		return $this->executeQuery($q);
	}
	
	
	
/**
* Selects simple user information by login from the user table
*
* @param	int		$login	The user's login number
* @return	resource	A mysql result resource in the format (first_name, last_name, email_address)
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function selectUserDetail($login) {
		$q = "SELECT first_name, last_name, email_address ".
				"FROM user ".
				"WHERE login=$login";
		return $this->executeQuery($q);
	}
	


	//<<<<<<<<<<<<<<<<<<<<<
	//FAQ Functions  
	//<<<<<<<<<<<<<<<<<<<<<

/**
* Updates the sort_order field of a release note 
*
* @param	int		$id			The release_note_id of the release note to update
* @param	int		$order		The new value for the sort_order field
* @param 	int		$admin_id	The admin_id of the user updating this value
* @return	boolean	Should return true on success, will throw an error on failure.
* @access	
* @since	HEM 1.0, BRD 1.4
*/
	function updateReleaseNoteSortOrder($id, $order, $admin_id) {
		$q = "UPDATE release_note ".
				"SET sort_order=$order, modified_by=$admin_id, modified_date=NOW() ".
				"WHERE release_note_id=$id AND status='A'";
		return $this->executeQuery($q);
	}


/**
* Updates the sort_order field of a frequently asked question
*
* @param	int		$id			The FAQ's frequently_asked_question_id
* @param	int		$order		The value for the sort_order field
* @param	int		$admin_id	The admin_id of the user updating this value
* @return	boolean	Should return true on success, will throw an error on failure.
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function updateFrequentlyAskedQuestionSortOrder($id, $order, $admin_id) {
		$q = "UPDATE frequently_asked_question ".
				"SET sort_order=$order, modified_by=$admin_id, modified_date=NOW() ".
				"WHERE frequently_asked_question_id=$id AND status='A'";
		return $this->executeQuery($q);
	}
	
/**
* Updates the sort_order field of a frequently asked question group
*
* @param	int		$id			The FAQ's frequently_asked_question_group_id
* @param	int		$order		The value for the sort_order field
* @param	int		$admin_id	The admin_id of the user updating this value
* @return	boolean	Should return true on success, will throw an error on failure.
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function updateFrequentlyAskedQuestionGroupSortOrder($id, $order, $admin_id) {
		$q = "UPDATE frequently_asked_question_group ".
				"SET sort_order=$order, modified_by=$admin_id, modified_date=NOW() ".
				"WHERE frequently_asked_question_group_id=$id AND status='A'";
		return $this->executeQuery($q);
	}


/**
* Selects information about a frequently asked question
*
* @param	int		$id		The FAQ's frequently_asked_question_id
* @return	resource	A mysql result resource of the format (frequently_asked_question_title,
*                        frequently_asked_question_answer, frequently_asked_question_group_id, module_id)
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function selectFrequentlyAskedQuestion($id) {
		$q = "SELECT faq.frequently_asked_question_title, faq.frequently_asked_question_answer, faq.frequently_asked_question_group_id, faq.module_id ".
				"FROM frequently_asked_question faq ".
				"WHERE faq.frequently_asked_question_id=$id AND faq.status='A'";
		return $this->executeQuery($q);
	}

/**
* Inserts a new FAQ into the frequently_asked_question table
*
* @param	string	$title			The question
* @param	string	$answer			The answeer
* @param	int		$module_id		The module_id to associate this question with 
* @param	int		$faq_group_id	The frequently_asked_question_group_id of the group to which this question will belong
* @param	int		$admin_id		The admin_id of the user adding this question
* @return	boolean	Should return true on success, will throw an error on failure.
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function insertFrequentlyAskedQuestion($title, $answer, $module_id, $faq_group_id, $admin_id) {
		$q = "INSERT INTO frequently_asked_question (frequently_asked_question_title, frequently_asked_question_answer, module_id, frequently_asked_question_group_id, created_by, created_date) ".
				"VALUES ('".mysql_real_escape_string($title)."', '".mysql_real_escape_string($answer)."', $module_id, $faq_group_id, $admin_id, NOW())";
		return $this->executeQuery($q);
	}

/**
* Deletes a FAQ
*
* This does not actually delete the record, but rather sets its status to 'D'
*
* @param	int		$id			The frequently_asked_question_id of the FAQ to be deleted
* @param	int		$admin_id	The admin_id of the user doing the deletion. 
* @return	boolean	Should return true on success, will throw an error on failure.
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function deleteFrequentlyAskedQuestion($id, $admin_id) {
		$q = "UPDATE frequently_asked_question ".
				"SET status='D', modified_by=$admin_id, modified_date=NOW() ".
				"WHERE frequently_asked_question_id=$id AND status='A'";
		return $this->executeQuery($q);
	}

/**
* Updates a FAQ record in the frequently_asked_question table
*
* @param	int		$id				The frequently_asked_question_id of the FAQ to be updated
* @param	string	$title			The updated question
* @param	string	$answer			The updated answer
* @param	int		$module_id		The udpated module_id 
* @param	int		$faq_group_id	The updated frequently_asked_question_group_id
* @param	int		$admin_id		The admin_id of the user doing the update
* @return	boolean	Should return true on success, will throw an error on failure.
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function updateFrequentlyAskedQuestion($id, $title, $answer, $module_id, $faq_group_id, $admin_id) {
		$q = "UPDATE frequently_asked_question ".
				"SET frequently_asked_question_title='".mysql_real_escape_string($title)."', ".
					"frequently_asked_question_answer='".mysql_real_escape_string($answer)."', ".
					"frequently_asked_question_group_id=$faq_group_id, ".
					"module_id=$module_id, ".
					"modified_by=$admin_id, ".
					"modified_date=NOW() ".
				"WHERE frequently_asked_question_id=$id AND status='A'";
		return $this->executeQuery($q);
	}
	
	
	
/**
* Selects all the FAQs in a specific FAQ group
*
* @param	int		$faq_group_id	The frequently_asked_question_group_id of the group to select
* @return	resource	A mysql result resource of the format (frequently_asked_question_id, frequently_asked_question_title,
*                        frequently_asked_question_answer)
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function selectFrequentlyAskedQuestionsByGroup($faq_group_id) {
		$q = "SELECT faq.frequently_asked_question_id, faq.frequently_asked_question_title, faq.frequently_asked_question_answer ".
				"FROM frequently_asked_question faq ".
				"WHERE faq.frequently_asked_question_group_id=$faq_group_id AND faq.status='A' ".
				"ORDER BY faq.sort_order ASC";
		return $this->executeQuery($q);
	}

/**
* Deletes all the FAQs in a specific FAQ group
*
* This does not actually remove records, but rather sets their status to 'D'
* Also, this does not delete the frequently_asked_question_group, just the FAQs associated to it.
*
* @param	int		$faq_group_id	The frequently_asked_question_group_id
* @param	int		$admin_id		The admin_id of the user doing the deletion
* @return	boolean	Should return true on success, will throw an erorr on failure.
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function deleteFrequentlyAskedQuestionsByGroup($faq_group_id, $admin_id) {
		$q = "UPDATE frequently_asked_question ".
				"SET status='D', modified_by=$admin_id, modified_date=NOW() ".
				"WHERE frequently_asked_question_group_id=$faq_group_id AND status='A'";
		return $this->executeQuery($q);
	}




/**
* Selects all of the active FAQ groups
*
* @return	resource	A mysql result resource of the format (frequently_asked_question_group_id,
*                        frequently_asked_question_group_description)
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function selectFrequentlyAskedQuestionGroups() {
		$q = "SELECT faqg.frequently_asked_question_group_id, faqg.frequently_asked_question_group_description ".
				"FROM frequently_asked_question_group faqg ".
				"WHERE faqg.status='A' ".
				"ORDER BY faqg.sort_order ASC";
		return $this->executeQuery($q);
	}

/**
* Selects a single FAQ group
*
* @param	int		$faqg_id	The frequently_asked_question_group_id
* @return	resource	A mysql result resource of the format (frequently_asked_question_group_id,
*                        frequently_asked_question_group_description)
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function selectFrequentlyAskedQuestionGroup($faqg_id) {
		$q = "SELECT faqg.frequently_asked_question_group_id, faqg.frequently_asked_question_group_description ".
				"FROM frequently_asked_question_group faqg ".
				"WHERE faqg.status='A' AND faqg.frequently_asked_question_group_id=$faqg_id";
		return $this->executeQuery($q);
	}
	
/**
* Inserts a new FAQ group into the database
*
* @param	string	$description	The description of the group
* @param	int		$admin_id		The admin_id of the user doing the insertion
* @return	boolean Should return true on success, will throw an error on failure.
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function insertFrequentlyAskedQuestionGroup($description, $admin_id) {
		$description = mysql_real_escape_string($description);
		$q = "INSERT INTO frequently_asked_question_group (frequently_asked_question_group_description, created_by, created_date) ".
				"VALUES ('$description', $admin_id, NOW())";
		return $this->executeQuery($q);
	}
	
/**
* Updates information on a FAQ group.
*
* @param	int		$id				The frequently_asked_question_group_id
* @param	string	$description	The updated description of the group
* @param	int		$admin_id		The admin_id of the user doing the update
* @return	boolean	Should return true on success, will throw an error on failure.
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function updateFrequentlyAskedQuestionGroup($id, $description, $admin_id) {
		$description = mysql_real_escape_string($description);
		$q = "UPDATE frequently_asked_question_group ".
				"SET frequently_asked_question_group_description='$description', ".
					"modified_by=$admin_id, ".
					"modified_date=NOW() ".
				"WHERE frequently_asked_question_group_id=$id";
		return $this->executeQuery($q);
	}
	
/**
* Deletes a FAQ group
*
* This does not actually remove any records, but rather sets status to 'D'
*
* @param	int		$id			The frequently_asked_question_group_id of the FAQ group to delete
* @param	int		$admin_id	The admin_id of the user doing the deletion.
* @return	boolean Should return true on success, will throw an error on failure.
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function deleteFrequentlyAskedQuestionGroup($id, $admin_id) {
		$q = "UPDATE frequently_asked_question_group ".
				"SET status='D', modified_by=$admin_id, modified_date=NOW() ".
				"WHERE frequently_asked_question_group_id=$id AND status='A'";
		return $this->executeQuery($q);
	}
	

	//>>>>>>>>>>>>>>>>>>>>>>
	//end FAQ Functions
	//>>>>>>>>>>>>>>>>>>>>>>
	


/**
* Selects all the modules from the module table.
*
* @return	resource	A mysql result resrouce of the format (module_id, module_description, module_code)
* @access	
* @since	HEM 1.0, BRD 1.3
*/
	function selectModules() {
		$q = "SELECT m.module_id, m.module_description, m.module_code ".
				"FROM module m ".
				"WHERE m.status='A' ".
				"ORDER BY m.sort_order ASC, m.module_description ASC";
		return $this->executeQuery($q);
	}
	
	
}


?>
