<?php

/*
   Project Manager business logic function
   $Id: functions.inc 2006-02-06 tchan $
*/

$module_name = "Project Manager";




/**
* FormatBackTrace()
*
* @param string $file - caller should pass in __FILE__
* @param integer $line - caller should pass in __LINE__
* @return string
* @throws
* @access
* @global
* @since TChan 2006-02-03
*/
function FormatBackTrace($file, $line){
    return "(".preg_replace("/^.*(([/][^/]+){3})$/","$1", $file).":$line)";
}

/**
* OverridePJMHeader()
*
* @param
* @todo NOT YET COMPLETED
* @return
* @since  - Thu Aug 10 18:10:55 PDT 2006
*/
function OverridePJMHeader()
{
   $project_headers = array(
      array('field'=>'p.pjm_id',             'title'=>'Project ID'),
      array('field'=>'p.pjm_description',    'title'=>'Project Name'),
      array('field'=>'ps.pjm_status_description', 'title'=>'Project Status'),
      array('field'=>'p.pjm_start_date',     'title'=>'Start Date'),
      array('field'=>'p.pjm_end_date',       'title'=>'End Date'),
      array('field'=>'total_elements',       'title'=>'Total Studies'),
   );
   return $project_headers;
}

/**
* OverrideStudyHeader()
*
* @param
* @todo NOT YET COMPLETED
* @return
* @since  - Fri Aug 11 09:53:08 PDT 2006
*/
function OverrideStudyHeader()
{
   $study_headers = array(
      array('field'=>'s.study_id',                          'title'=>'Study ID'),
      array('field'=>'s.study_name',                        'title'=>'Study Name'),
      array('field'=>'country_description',                 'title'=>'Country'),
      array('field'=>'study_type_description',              'title'=>'Type'),
      array('field'=>'fg.functional_group_description',     'title'=>'Group Owner'),
      array('field'=>'s.study_status_id',                   'title'=>'Status'),
      array('field'=>'s.start_date',                        'title'=>'Start Date'),
      array('field'=>'s.current_estimated_complete_date',   'title'=>'Est. Complete'),
      array('field'=>'percent_quota_progress',              'title'=>'Quota Filled')
   );
   return $study_headers;
}

/**
* PJMOverrideAccountID()
*
* @param
* @todo NOT YET COMPLETED
* @return
* @since  - Thu Aug 10 18:12:15 PDT 2006
*/
function PJMOverrideAccountID()
{
   return GetExternalPrimaryAccount();
}



/**
* PrintHeader()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function PrintHeader()
{  global $o, $smarty;

   $smarty->assign('PJM_LAST_VIEWED_PJM_ID', $_SESSION['pjm_details_last_viewed_pjm_id']);
   DisplayHeader("Project Manager", "pjm", $o['action']);
}

/**
* PrintFooter()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function PrintFooter()
{
   DisplayFooter();
}


/**
* DisplayNewProjectForm()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since 2006-02-06 Tchan
*/
function DisplayNewProjectForm()
{
   global $smarty;

   $common_db = new commonDB();
   $project_db = new projectDB();
   $study_db = new studyDB();

 	// Get a list of alert levels for user to choose

 	$templates[0] = "[--Use a template ?--]";
 	$rst = $study_db->GetTemplates(array($_SESSION["user_id"]));
 	while ($t = mysql_fetch_assoc($rst)) {
 	   $templates[$t["study_template_id"]] = $t["study_template_description"];
 	}
   $alert_levels = CreateSmartyArray($project_db->GetAlertLevels(), 'alert_level_id', 'alert_level_description');
   $project_db->GetProjectStatusLookup($project_status_lookup);


   PrintHeader();
   $smarty->assign('project_status_lookup', $project_status_lookup);
   $smarty->assign("templates_lookup", $templates);
   $smarty->assign('alert_levels', $alert_levels);
   $smarty->display('app/pjm/vw_new_project.tpl');
   PrintFooter();
}




/**
* DisplayNewNoteForm()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function DisplayNewNoteForm($pjm_id)
{
   global $smarty;
   $project_db = new projectDB();

   $project_db->GetLastProjectComment($comment, $pjm_id);

   // Get a list of alert levels for user to choose
   $alert_levels = CreateSmartyArray($project_db->GetAlertLevels(), 'alert_level_id', 'alert_level_description');

   $smarty->assign('pjm_id', $pjm_id);
   $smarty->assign('current_alert_level_id', $comment['alert_level_id']);
   $smarty->assign('alert_levels', $alert_levels);
   $smarty->assign('moduleName', $GLOBALS['module_name']);
   $smarty->display('app/pjm/vw_new_project_note.tpl');

}


/**
* DisplayEditProjectInfo()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function DisplayEditProjectInfo($pjm_id)
{
   global $smarty;
   global $auto_transfer_roles;
   $project_db = new projectDB();
   $user_db = new userDB();
   $common_db = new commonDB();

   $project_db->GetProjectInfo($project_info, $pjm_id);
   $project_db->GetProjectUserRoles($user_roles, $pjm_id);

   $project_db->GetProjectStatusLookup($project_status_lookup);

   // Get a list of roles for users to select
   $role_lookup = array();
   $users_lookup = array();
   $rs_roles = $common_db->GetRoleListByModule('PJM');
   while ($role = mysql_fetch_assoc($rs_roles)) {
      $role_id = $role['role_id'];
		$role_lookup[$role_id] = $role['role_description'];

		// Get users who are in this role
		$users_lookup[$role_id] = array();
		$rs_users = $common_db->GetUsersByRoleId($role_id);
		while ($user = mysql_fetch_assoc($rs_users)){
		   $login = $user['login'];
		   $users_lookup[$role_id][$login] = $user['name'];
		}

		// Remove the role if there are no users with it
		if (!count($users_lookup[$role_id])){
		   unset($role_lookup[$role_id]);
		   unset($users_lookup[$role_id]);
		}
   }



   // For safety, add current assigned users to the account manager list
   // Otherwise, if an assigned user is not in the list from GetSecurityGroupUsers(), he/she won't be seen in dropdown box!
   foreach ($user_roles as $user_role){
      $login = $user_role['user_id'];
      $role_id = $user_role['role_id'];
      if (!isset($role_lookup[$role_id])){
         $role_lookup[$role_id] = $user_role['role_description'];
      }
      if (!isset($users_lookup[$role_id][$login])){
         $users_lookup[$role_id][$login] = "{$user_role['first_name']} {$user_role['last_name']}";
      }
   }

   natcasesort($role_lookup);

	$smarty->assign('user_roles', $user_roles);
	$smarty->assign('users_lookup',$users_lookup);
	$smarty->assign('role_lookup', $role_lookup);
	$smarty->assign('project_status_lookup', $project_status_lookup);
   $smarty->assign('project_info', $project_info);
   $smarty->assign('moduleName', $GLOBALS['module_name']);
	$smarty->display('app/pjm/vw_edit_project_info.tpl');
}

function GetUsersByRoleId($role_id)
{
   $common_db = new commonDB();
   $rs_users = $common_db->GetUsersByRoleId($role_id);

   header("Content-Type: text/xml; charset=UTF-8");
   $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
   $xml .= "<users>";
   while ($user = $rs_users){
      $html_login = htmlspecialchars($user['login']);
      $html_name = htmlspecialchars($user['name']);
      $xml .= "<user login=\"$html_login\" name=\"$html_name\"/>";
   }
   $xml .= "</users>";
   $xml = Utf8SafeAscii($xml);
   $xml = utf8_encode($xml);
   echo $xml;
   exit();
}







/**
* AddProject()
*
* Create a brand new project
*
* @param integer $pjm_id  [out] ID assigned to the new project
* @param string $pjm_description
* @param string $pjm_start_date
* @param string $pjm_end_date
* @param integer $account_id,
* @param array $studies
* @param integer $alert_level_id - Starting alert level for the comment
* @param string $pjm_comment_text - Starting comment for the project; pass "" for no comment
* @return
* @throws
* @access
* @global
* @since 2006-02-06 Tchan
*/
function AddProject(&$pjm_id,
                     $pjm_type_id,
                     $pjm_status_id,
                     $pjm_description,
                     $pjm_start_date, $pjm_end_date,
                     $account_id,
                     $studies,
                     $alert_level_id, $pjm_comment_text, $study_template_id=0)
{
   $project_db = new projectDb();
   $study_db = new studyDB();
   $pjm_id = 0;

   $pjm_description = trim($pjm_description);

   // Add an entry to PRJ table
   $project_db->AddProject(
      $pjm_id,
      $pjm_type_id, // project type
      $pjm_status_id,
      $pjm_description,
      $pjm_start_date,
      $pjm_end_date,
      $alert_level_id
   );

   $project_db->SetProjectAttribute($dummy, $pjm_id, PJM_ATTR_NAME_ACCOUNT_ID, $account_id);

   if ($study_template_id) {
      //echo ("Use template ID : ".$study_template_id."<br>");
      $study_db->_template_id = $study_template_id;
      $rst = $project_db->GetProjectAttributeDefs();
      
      while ($ad = mysql_fetch_assoc($rst)) {
         $ta = array(
         "attribute_type"=>$ad["pjm_attr_type"],
         "study_attr"=>$ad["pjm_attr_name"]);
         $ta_value = $study_db->getTemplateAttr($ta);
         if ($ta_value) {
            if ($ta_value == "checked") $ta_value = "on";
            $project_db->SetProjectAttribute($dummy, $pjm_id, $ad["pjm_attr_name"], $ta_value, $ad["pjm_attr_type"]);
            //echo ("Set project attr ".$ad["pjm_attr_name"]." = ".$ta_value."<br>");
         }
      }

      $rst = $study_db->GetStudyTemplateFiles();
      while ($f = mysql_fetch_assoc($rst)) {
         //echo ("Found file <pre>".print_r($f, true)."</pre>");
         $file = $study_db->GetStudyTemplateFileById($f["study_template_file_id"]);
         $project_db->SetProjectFile($pjm_id, $file["file_type_id"], $file["study_file_type_id"], $file["study_template_file_name"], $file["study_template_file_size"], $file["study_template_file_data"], $file["study_template_file_title"], $_SESSION["admin_id"]);

      }

      $rst = $study_db->GetTemplateContacts($study_template_id);
      while ($c = mysql_fetch_assoc($rst)) {
         $project_db->AddProjectContact($dummy, $pjm_id, $c["study_contact_type_id"], $c["contact_id"], $c["first_name"], $c["last_name"], $c["phone"], $c["email"]);
      }
   }


   // Add study entries to the project
   $study_ids = array();
   
   foreach ($studies as $study_id){
      // skip blank entries
      if (!$study_id){ continue;}

      $study_ids[] = $study_id;
      $project_db->AddElementToProject(
         $dummy_pjm_element_id,
         $pjm_id,                      // project ID
         PJM_ELEMENT_TYPE_NETMR_STUDY, // element type
         $study_id,                    // reference ID
         "Net-MR Study [{$study_id}]"  // reference description
      );
   }

   // Add alert/note to the project (if user has entered)
   $pjm_comment_text = trim($pjm_comment_text);

   // If user didn't enter a note, the system will add a default note
   if (!$pjm_comment_text){
      if (count($study_ids)){
         $studies = $study_db->getListV2(
            " AND s.study_id IN (" .implode(',', $study_ids). ") ",   // extra conditions
            "",   // extra joins
            "",   // limit
            "s.alert_level_id DESC, s.study_name ASC " // order-by
         );
         // update the alert histogram
         $alert_historgram = array(4=>0, 3=>0, 2=>0, 1=>0, 0=>0);
         foreach ($studies as $study){
            $alert_level_id = $study['alert_level_id'];
            $alert_histogram[$alert_level_id] += 1;
         }
         $study = reset( $studies);
         $alert_level_id = $study['alert_level_id'];
         $total = $alert_histogram[ $alert_level_id ];
         $pjm_comment_text = "Auto Generated: There are $total study(s) with alert {$study['alert_level_description']}.";
      }
   }

   if (trim ($pjm_comment_text)){
      AddProjectNote($dummy_pjm_comment_id, $pjm_id, $alert_level_id, 1, $pjm_comment_text, DONT_SEND_PROJECT_NOTE);
   }

   // Add user roles and account/partner name (automatically populated from ACM)
   SynchronizeProjectUserRoles($pjm_id, $account_id);
   SynchronizeProjectAccountAttrs($pjm_id, $account_id);

   SendNewProjectAlert($pjm_id);
}




/**
* AddProjectNote()
*
* Add a project note AND set alert status to the project
*
* @param integer $pjm_comment_id      [out] ID assigned to the project note
* @param integer $pjm_id              [in] ID of the project which the note is added to
* @param integer $alert_level_id      [in] Alert level associate with the note
* @param integer $pjm_comment_type_id [in] type of the note
* @param string $pjm_comment_text     [in] the note
* @param boolean $send_project_note   [in] TRUE = send out email about the new note
* @return
* @throws
* @access
* @global
*/
function AddProjectNote(&$pjm_comment_id, $pjm_id, $alert_level_id, $pjm_comment_type_id, $pjm_comment_text, $send_project_note = true)
{
   if (!$pjm_id){
      echo "Project ID is not specified. ".FormatBackTrace(__FILE__,__LINE__);
      exit();
   }

   $pjm_comment_text = trim($pjm_comment_text);

   $project_db = new projectDb();
   $project_db->AddCommentToProject($pjm_comment_id, $pjm_id, $alert_level_id, $pjm_comment_type_id, $pjm_comment_text);

   // After we add new comment, we need to update alert level of the project
   $project_db->SetProjectAlertLevel($pjm_id, $alert_level_id);

   // Notify users about the new note/alert
   if ($send_project_note){
      SendProjectNote($pjm_id, $pjm_comment_id);
   }
}




/**
* SendProjectNote()
*
* Notify project users of a project note
*
* @param integer $pjm_id         [in] ID of the project which the comment is belonged to
* @param integer $pjm_comment_id [in] ID of the comment
* @return
* @throws
* @access
* @global
*/
function SendProjectNote($pjm_id, $pjm_comment_id)
{
   global $smarty;

   $project_db = new projectDB();
   $project_db->GetProjectUserRoles($user_roles, $pjm_id);
   $project_db->GetProjectComment($comment, $pjm_id, $pjm_comment_id);
   $project_db->GetProjectInfo($project_info, $pjm_id);

   $account_id = $project_info['account_id'];

   // find out whom we should send the note to
   $recipients = array();
   foreach ($user_roles as $user_role ){
      $user_id = $user_role['user_id'];
      $recipients[$user_id] = $user_id;
   }

   // find out the prior project note
   $project_db->GetPriorProjectCommentId($prior_pjm_comment_id, $pjm_id, $pjm_comment_id);
   $project_db->GetProjectComment($prior_comment, $pjm_id, $prior_pjm_comment_id);

   if (!$prior_comment){
      $prior_comment = array(
         'alert_level_id' => 1,
         'alert_level_description' => 'Green'
      );
   }

   // Find out whether the alert of this note is higher or lower than previous one
   if ($comment['alert_level_id'] == $prior_comment['alert_level_id']){
      $comment['alert_level_direction'] = 0;
      $comment['alert_level_direction_description'] = 'UPDATE';

   } elseif ($comment['alert_level_id'] < $prior_comment['alert_level_id']){
      $comment['alert_level_direction'] = -1;
      $comment['alert_level_direction_description'] = 'DOWN';

   } else {
      $comment['alert_level_direction'] = -1;
      $comment['alert_level_direction_description'] = 'UP';
   }

   $comment['subject'] = "ALERT {$comment['alert_level_direction_description']} - {$comment['alert_level_description']} - {$project_info['account_name']} - {$project_info['pjm_description']}";


	// compose HTML alert message
	$smarty->assign('comment', $comment);
	$smarty->assign('prior_comment', $prior_comment);
	$smarty->assign('project_info', $project_info);
	$smarty->assign('view_project_url', "http://".SERVER_NAME."/app/pjm/?action=display_project_details&pjm_id=$pjm_id");
	$smarty->assign('view_account_url', "http://".SERVER_NAME."/app/acm/?action=display_account_detail&account_id=$account_id");

   $html_message = $smarty->fetch('app/pjm/email_project_note.tpl');

   // Send out the message
   QueueMessageThruCOM(
      PJM_MODULE_ID.MESSAGE_TYPE_NEW_PROJECT_NOTE, // message ID
      $recipients,   // recipients
      $comment['subject'], // subject
      $html_message, //body
      array_merge($project_info, $comment)  // attributes
   );
}



function SetProjectInfo($pjm_id, $pjm_type_id, $pjm_status_id, $pjm_description, $pjm_start_date, $pjm_end_date)
{
   if (!$pjm_id){
      echo "Project ID is not specified. ".FormatBackTrace(__FILE__,__LINE__);
      exit();
   }

   $project_db = new projectDB();
   $project_db->SetProjectInfo(
      $pjm_id,
      $pjm_type_id,
      $pjm_status_id,
      $pjm_description,
      $pjm_start_date,
      $pjm_end_date
   );

}



function GetProjectAccountId(&$account_id, $pjm_id)
{
   $project_db = new projectDB();
   $project_db->GetProjectAttributes($attributes, $pjm_id);
   $account_id = $attributes[PJM_ATTR_NAME_ACCOUNT_ID];

   if (!$account_id){
      echo "Unable to retrieve account ID for project [$pjm_id].";
      exit();
   }
}





function SynchronizeProjectAccountAttrs($pjm_id, $account_id)
{
   if (!$pjm_id){
      echo "Project ID is not specified. ".FormatBackTrace(__FILE__,__LINE__);
      exit();
   }
   if (!$account_id){
      echo "Account ID is not specified. ".FormatBackTrace(__FILE__,__LINE__);
      exit();
   }

   $account_detail = GetAccountDetailFromACM($account_id);
   $account_name = $account_detail['account_name'];

   $project_db = new projectDB();
   $project_db->SetProjectAttribute($dummy, $pjm_id, PJM_ATTR_NAME_ACCOUNT_NAME, $account_name);

   foreach ($account_detail["attr"] AS $account_attr) {
      $project_db->SetProjectAttribute($dummy, $pjm_id, $account_attr["account_attr_name"], $account_attr["account_attr_value"]);
   }
}




function SynchronizeProjectUserRoles($pjm_id, $account_id)
{
   global $auto_transfer_roles;
   $project_db = new projectDB();

   if (!$pjm_id){
      echo "Project ID is not specified. ".FormatBackTrace(__FILE__,__LINE__);
      exit();
   }
   if (!$account_id){
      echo "Account ID is not specified. ".FormatBackTrace(__FILE__,__LINE__);
      exit();
   }

   // Retrieve latest user roles associated with this partner from ACM
   $account_detail = GetAccountDetailFromACM($account_id);


   // Remove previous auto-populated entries
   foreach ($auto_transfer_roles as $role_id){
      $project_db->DeleteProjectUserRoleByRoleId($pjm_id, $role_id);
   }
   $user_roles = $account_detail['user'];
   if (!$user_roles)
      $user_roles = array();

   // Set new auto-populated entries
   foreach ($user_roles as $user_role){
      $project_db->SetProjectUserRole($dummy_pjm_user_id, $pjm_id, $user_role['user_id'], $user_role['role_id']);
   }
}



function SetProjectUserRoles($pjm_id, $new_user_roles)
{
   if (!$pjm_id){
      echo "Project ID is not specified. ".FormatBackTrace(__FILE__,__LINE__);
      exit();
   }
   $project_db = new projectDB();

   $project_db->DeleteAllProjectUserRoles($pjm_id);
   foreach ($new_user_roles as $user_role){
      $project_db->SetProjectUserRole($dummy_user_role_id, $pjm_id, $user_role['user_id'], $user_role['role_id']);
   }
}





function SynchronizeProjectContactsInfo($pjm_id)
{
   if (!$pjm_id){
      echo "Project ID is not specified. ".FormatBackTrace(__FILE__,__LINE__);
      exit();
   }

	/*
   // Get existing contacts for this project
   $project_db = new projectDB();
   $project_db->GetProjectContacts($contacts, $pjm_id);

   // Reset the contacts; SetProjectContacts() will get the latest email and phone info
   //SetProjectContacts($pjm_id, $contacts);

   	$contacts_manager = new pjm_ContactManager();
	global $o;
	$contacts_manager->SetParams($o);
   	$contacts_manager->SetProjectContacts();   
  */
  // Get existing contacts for this project 
   $project_db = new projectDB(); 
   $project_db->GetProjectContacts($contacts, $pjm_id); 
   // Prepare input for SetProjectContacts() method 
   $i = 0; 
   $o["pjm_id"] = $pjm_id; 
   foreach($contacts AS $pjm_contact_id=>$contact) { 
      $o["contact".$i] = $contact["contact_id"]; 
      $o["type".$i] = $contact["pjm_contact_type_id"]; 
      $i++; 
   } 
   $contact_manager = new pjm_ContactManager(); 
   $contact_manager->SetParams($o);
   // Reset the contacts; SetProjectContacts() will get the latest email and phone info 
   $contact_manager->SetProjectContacts(); 
  
   /*
   $contacts_manager = new pjm_ContactManager();
   $contacts_manager->SetProjectContacts($pjm_id, $contacts);
   */
}


/**
* ClosePopup()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function ClosePopup()
{
   global $smarty;
	$smarty->display('close_popup.tpl');
	return true;
}




function DisplayProjectDetails($pjm_id, $studies_order_by)
{
   global $smarty;
   $timezone = GetTimeZone(array('login'=>$_SESSION['admin_id']));
   $project_db = new projectDB($timezone);
   $study_db = new studyDB();
   
   // If study ID is not specified, try to get the previous viewed study
   if (!$pjm_id){
      $pjm_id = $_SESSION['pjm_details_last_viewed_pjm_id'];
   }

   // If there was not study viewed, go back to study list
   if (!$pjm_id){
      header("Location: ?action=display_projects");
      exit();
   }
   
   $project_db->GetProjectInfo($project_info, $pjm_id);
   if (!$project_info) {
   	$smarty->display("record_not_found.tpl");
   	return false;
   }
   
   $_SESSION['pjm_details_last_viewed_pjm_id'] = $pjm_id;

   // Headers in study section
   $study_headers = array(
      array('field'=>'s.alert_level_id',                    'title'=>'Alert Level'),
      array('field'=>'s.study_id',                          'title'=>'Study ID'),
      array('field'=>'s.study_name',                        'title'=>'Study Name'),
      array('field'=>'country_description',                 'title'=>'Country'),
      array('field'=>'study_type_description',              'title'=>'Type'),
      array('field'=>'acct_mgr',                            'title'=>'Account Manager'),
      array('field'=>'task_owner',                          'title'=>'Task Owner'),
      array('field'=>'fg.functional_group_description',     'title'=>'Group Owner'),
      array('field'=>'s.study_status_id',                   'title'=>'Status'),
      array('field'=>'sstatus.study_status_description',    'title'=>'Stage'),
      array('field'=>'s.start_date',                        'title'=>'Start Date'),
      array('field'=>'s.current_estimated_complete_date',   'title'=>'Est. Complete'),
      array('field'=>'percent_quota_progress',              'title'=>'Quota Filled')
   );

   $vendors = array("translation"=>array(), "dp"=>array());
   $accounts = HBRPCCall('acm', 'GetAccountList', array("account_type_id"=>ACCOUNT_TYPE_VENDOR, "account_sub_type_id"=>ACCOUNT_SUB_TYPE_TRANSLATION));
   //$accounts = array(array("account_id"=>"800001", "account_name"=>"Translation.net"));
   if (is_array($accounts))
   foreach ($accounts AS $account) {
      $vendors["translation"][$account["account_id"]]=$account["account_name"];
   }
   $accounts = HBRPCCall('acm', 'GetAccountList', array("account_type_id"=>ACCOUNT_TYPE_VENDOR, "account_sub_type_id"=>ACCOUNT_SUB_TYPE_DATA_PROCESSING));
   //$accounts = array(array("account_id"=>"2001", "account_name"=>"Data processing house 1"));
   if (is_array($accounts))
   foreach ($accounts AS $account) {
      $vendors["dp"][$account["account_id"]]=$account["account_name"];
   }

   //echo ("<pre>".print_r($vendors, true)."</pre>");
   // Users click on a column header to change studies' sorting?
   if ($studies_order_by){
      $_SESSION['pjm_detail_studies_sort'] = $studies_order_by;
      header("Location: ?action=display_project_details&pjm_id=$pjm_id");
      exit();
   }
   // Set default sorting if this is the first time browsing
   if (!@$_SESSION['pjm_detail_studies_sort']){
      $_SESSION['pjm_detail_studies_sort'] = "s.alert_level_id DESC";
   }


   $project_db->GetProjectAttributes($project_attributes, $pjm_id);
   $project_db->GetProjectFiles($files, $pjm_id);
   $project_db->GetProjectElements($elements, $pjm_id);
   //$project_db->GetProjectComments($comments, $pjm_id);

   $project_files = array("instruction"=>array(), "quota"=>array(), "delivery"=>array());
   if (is_array($files)) {
   foreach ($files AS $file) {
      switch ($file["pjm_file_type_id"]) {
         case STUDY_FILE_SPECIAL_INSTRUCTIONS: {
            $project_files["instruction"][] = $file;
         }break;
         case STUDY_FILE_DETAIL_QUOTA: {
            $project_files["quota"][]=$file;
         }break;
         case STUDY_FILE_DATA_DELIVERY: {
            $project_files["delivery"][] = $file;
         }break;
      }
   }
   }

   // Get studies for this project
   $study_ids = array();
   $data_sources = array();
   $sample_types = array();
   $products = array();
   foreach ($elements as $element){
      if ($element['pjm_element_type_id'] != PJM_ELEMENT_TYPE_NETMR_STUDY){
         continue;
      }
      $pjm_element_reference_id = $element['pjm_element_reference_id'];
      $study_ids[$pjm_element_reference_id] = $pjm_element_reference_id;
      $study_db->_study = $pjm_element_reference_id;
      $rst = $study_db->GetDataSource();
      while ($ds = mysql_fetch_assoc($rst)) {
         $data_sources[$ds["study_datasource_id"]] = $ds["study_datasource_description"];
      }
      $rst = $study_db->GetSampleTypes();
      while ($st = mysql_fetch_assoc($rst)) {
         $sample_types[$st["sample_type_id"]] = $ds["sample_type_description"];
      }

      $rst = $study_db->GetProducts();
      while ($p = mysql_fetch_assoc($rst)) {
         $products[$p["product_id"]] = $p["product_description"];
      }
   }
   $project_info["data_sources"] = "";
   foreach ($data_sources AS $ds)
      $project_info["data_sources"] .= $ds."<br>";
   $project_info["sample_types"] = "";
   foreach ($sample_types AS $st)
      $project_info["sample_types"] .= $st."<br>";
   $project_info["products"] = "";
   foreach ($products AS $p)
      $project_info["products"] .= $p."<br>";
   $project_studies = array();
   if (count($study_ids)){
      $extraFilters   = " AND s.study_id IN (" . implode(',', $study_ids) . ") ";
      $extraJoins = "";
      $limit = "";
      $order_by = "{$_SESSION['pjm_detail_studies_sort']} , s.study_name ASC ";
      $project_studies = $study_db->getListV2($extraFilters, $extraJoins, $limit, $order_by);
   }

   // Get contacts for this project
   $project_contacts = array();
   $project_db->GetProjectContacts($project_contacts, $pjm_id);


   // Get comments for this project
   $project_comments = array("alert_changes"=>array(), "spec_changes"=>array());
   $project_db->GetProjectComments($comments, $pjm_id);
   foreach ($comments AS $comment) {
      switch ($comment["pjm_comment_type_id"]) {
         case 1: $project_comments["alert_changes"][$comment["pjm_comment_id"]] = $comment; break;
         case 2: {
            $project_comments["spec_changes"][$comment["pjm_comment_id"]] = $comment;
            $project_db->GetProjectCommentFiles($comment_files, $comment["pjm_comment_id"]);
            if (is_array($comment_files)) {
               foreach ($comment_files AS $file) {
                  $project_comments["spec_changes"][$comment["pjm_comment_id"]]["pjm_comment_file_id"] = $file["pjm_comment_file_id"];
                  $project_comments["spec_changes"][$comment["pjm_comment_id"]]["pjm_comment_file_title"] = $file["pjm_comment_file_title"];
               }
            }
         }break;
      }
   }

   $template = "app/pjm/vr_display_project_details.tpl";
   if (!isInternalUser()) {
      $template = "app/pjm/ext/vr_display_project_details.tpl";
      $study_headers = OverrideStudyHeader();
   }

   // Get user roles for this project
   $project_db->GetProjectUserRoles($project_user_roles, $pjm_id);

   PrintHeader();
   $smarty->assign('project_info', $project_info);
   $smarty->assign('project_attributes', $project_attributes);
   $smarty->assign('project_files', $project_files);
   $smarty->assign('project_studies', $project_studies);
   $smarty->assign('vendors', $vendors);
   $smarty->assign('project_contacts', $project_contacts);
   $smarty->assign('project_comments', $project_comments);
   $smarty->assign('project_user_roles', $project_user_roles);

   list($studies_sort_by, $studies_sort_order) = explode(' ', $order_by);
   $smarty->assign('studies_sort_by', $studies_sort_by);
   $smarty->assign('studies_sort_order', $studies_sort_order);
   $smarty->assign('study_headers', $study_headers);
   $smarty->display($template);
   PrintFooter();
}



/**
* SaveAttributes()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu May 11 17:49:26 PDT 2006
*/
function SaveAttributes($o)
{
   $pjmDB = new projectDB();
   //echo("<pre>".print_r($o, true)."</pre>");
   $rs = $pjmDB->GetProjectAttributeDefs();
   while ($def = mysql_fetch_assoc($rs)) {
      if (isset($o[$def["pjm_attr_name"]])) {
         $pjmDB->SetProjectAttribute($pjm_attr_id, $o["pjm_id"], $def["pjm_attr_name"], $o[$def["pjm_attr_name"]], $def["pjm_attr_type"]);
      }elseif($def["pjm_attr_type"]=="X") {
         $pjmDB->DelProjectAttribute($o["pjm_id"], $def["pjm_attr_name"], $def["pjm_attr_type"]);
      }
   }
}



/**
* PJMCheckSessionVariable()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 25 12:05:11 PST 2006
*/
function PJMCheckSessionVariable($o, $name, $default)
{
   if (!isset($o[$name])) {
      if (!isset($_SESSION['pjm_'.$name])) {
         $o[$name] = $default;
         $_SESSION['pjm_'.$name] = $default;
      }else{
         $o[$name] = $_SESSION['pjm_'.$name];
      }
   }else{
      $_SESSION['pjm_'.$name] = $o[$name];
   }
   return $o[$name];

}


function DisplayProjects($o)
{
   global $smarty;
   global $userRights;

   $login = $_SESSION['admin_id'];

   $timezone = GetTimeZone(array('login'=>$login));
   $project_db = new projectDB($timezone);
   $study_db = new studyDB($timezone);
   $common_db = new commonDB();

   // Headers in study section
   $project_headers = array(
      array('field'=>'p.alert_level_id',     'title'=>'Project Alert'),
      array('field'=>'p.pjm_id',             'title'=>'Project ID'),
      array('field'=>'p.pjm_description',    'title'=>'Project Name', 'type' => 'link'),
      array('field'=>'account_id',           'title'=>'Account ID'),
      array('field'=>'account_name',         'title'=>'Account Name'),
      array('field'=>'am_name',              'title'=>'Primary AM'),
      array('field'=>'ae_name',              'title'=>'Primary AE'),
      array('field'=>'ps.pjm_status_description', 'title'=>'Project Status'),
      array('field'=>'p.pjm_start_date',     'title'=>'Start Date'),
      array('field'=>'p.pjm_end_date',       'title'=>'End Date'),
      array('field'=>'total_elements',       'title'=>'Total Studies'),
      array('field'=>'elements_alert_score', 'title'=>'Study Alert')
   );

   if (!@$_SESSION['pjm_main_search_params']){
      $search_params = array(
         'sc_pjm_status_id' => STATUS_OPEN,
         'sf_pjm_status_id' => "p.pjm_status_id"
      );
      $_SESSION['pjm_main_search_params'] = $search_params;
   }

   $o["start"] = PJMCheckSessionVariable($o, "start", 0);
   if (isset($o["sort"])) {
      $o["start"] = 0;
   }
   $o["page_size"]=PJMCheckSessionVariable($o, "page_size", 30);
   $o["sort"] = PJMCheckSessionVariable($o, "sort", "p.alert_level_id DESC");
   $o["sort_by_column"] = $o["sort"];

   BuildPageLimit(&$o, &$start, &$page_size);

   //Build where clause if search parameteres are specified.
   $search_params = @$_SESSION['pjm_main_search_params'];
   $search_params["sc_account_executive_id"] = str_replace("//", "", $search_params["sc_account_executive_id"]);
   $search_params["sc_account_manager_id"] = str_replace("//", "", $search_params["sc_account_manager_id"]);

   $template = "app/pjm/vr_display_projects.tpl";
   if (!isInternalUser()) {
      $template = "app/pjm/ext/vr_display_projects.tpl";
      $project_headers = OverridePJMHeader();
      $search_params['sc_account_id'] = PJMOverrideAccountID();
      $search_params['sf_account_id'] = 'pa_account_id.pjm_attr_value';
      $search_params['user_assignment'] = "";
   }
   $filter = "";
   if ($search_params){
      $filter = $project_db->BuildSearchString($search_params);
      //$filter = $common_db->BuildSearchString($o);
   }

   // Add additional constraint...

   // the user can only view his/her projects?
   $pjm_can_view_my_projects_only = $userRights->userSecCheck('PJM_VIEW_MY_PROJECTS_ONLY');
   if ($pjm_can_view_my_projects_only) {
      $reportee_array = GetReportees($login, INCLUDE_MYSELF_IN_OUTPUT);
      $reportee_list = PrepareInString($reportee_array);
      $filter .= " AND pu.user_id IN ($reportee_list) ";
   }

   // Retrieve projects
   $projects = array();
   $order_by = "{$_SESSION['pjm_projects_sort']} , p.pjm_description ASC";

   $o["total_rows"] = $project_db->GetProjectsCount($filter);
   $page_count = ceil($o["total_rows"] / $page_size);

   $pages = array();
   for ($i=1; $i<=$page_count; $i++) {
      $pages[($i-1)*$page_size] = $i;
      $o["last_page_start"] = ($i-1)*$page_size;
   }

   $o["page_list"] = $pages;
   end($pages);

   if (isset($o["display_all"])&&($o["display_all"]==1)) {
      //mysql_data_seek($rst, 0);
      $rst = $project_db->GetProjects($filter, $o["sort"]);
   }else{
      $rst = $project_db->GetProjects($filter, $o["sort"], "LIMIT $start, $page_size");
   }
   $o["page_rows"] = $project_db->rows;

   $projects = array();
   while ($p = mysql_fetch_assoc($rst)) {
      $projects[$p["pjm_id"]] = $p;
   }

   // Retrieve details of each project
   foreach ($projects as $pjm_id => $project){
      $projects[$pjm_id]['studies'] = array();
      $projects[$pjm_id]['element_alert_histogram'] = array(4=>0, 3=>0, 2=>0, 1=>0, 0=>0);

      // Get studies linked to this project
      $project_db->GetProjectElements($elements, $pjm_id);
      // first, we need to build a list of studies associated with this project
      $study_ids = array();
      foreach ($elements as $element){
         if ($element['pjm_element_type_id'] != PJM_ELEMENT_TYPE_NETMR_STUDY){
            continue;
         }
         $pjm_element_reference_id = $element['pjm_element_reference_id'];
         $study_ids[$pjm_element_reference_id] = $pjm_element_reference_id;
      }
      // then, we create a where clause for database to retrieve these studies
      if (count($study_ids)){
         $projects[$pjm_id]['studies'] = $study_db->getListV2(
            " AND s.study_id IN (" .implode(',', $study_ids). ") ",   // extra conditions
            "",   // extra joins
            "",   // limit
            "s.alert_level_id DESC, s.study_name ASC " // order-by
         );
         // update the alert histogram
         foreach ($projects[$pjm_id]['studies'] as $study){
            $alert_level_id = $study['alert_level_id'];
            $projects[$pjm_id]['element_alert_histogram'][$alert_level_id] += 1;
         }
      }

      // Get alert/comment of the studies
      foreach ($study_ids as $study_id){
         $projects[$pjm_id]['studies'][$study_id]['last_alert'] = $study_db->GetLastStudyAlert($study_id);
      }

      // Get alert/comment for this project
      $project_db->GetLastProjectComment($comment, $pjm_id);
      $projects[$pjm_id]['last_comment'] = $comment;

   }

   $project_db->GetProjectStatusLookup($project_status_lookup);

   if (!$pjm_can_view_my_projects_only) 
   {
   	
	   $locations_rst = $common_db->GetLocationList();
	   $lists["pjm_account_executive"]["(1"] = "Account Executives in -----";
	   $lists["pjm_account_manager"]["(1"] = "Account Managers in -----";
   
	   while ($location = mysql_fetch_assoc($locations_rst)) {
	      $aes_at_location_rst = $common_db->GetUsersByLocationId($location["location_id"], array(ROLE_ACCOUNT_EXECUTIVE));
	      $aes_ids = "//";
	      while ($ae_at_location = mysql_fetch_assoc($aes_at_location_rst)) {
	         $aes_ids .= $ae_at_location["login"].", ";
	      }
	      if ($aes_ids!="//") {
	         $aes_ids = substr($aes_ids, 0, strlen($aes_ids)-2)."//";
	         $lists["pjm_account_executive"][$aes_ids] = $location["location_description"];
	      }
	      $ams_at_location_rst = $common_db->GetUsersByLocationId($location["location_id"], array(ROLE_ACCOUNT_MANAGER));
	      $ams_ids = "//";
	      while ($am_at_location = mysql_fetch_assoc($ams_at_location_rst)) {
	         $ams_ids .= $am_at_location["login"].", ";
	      }
	      if ($ams_ids!="//") {
	         $ams_ids = substr($ams_ids, 0, strlen($ams_ids)-2)."//";
	         $lists["pjm_account_manager"][$ams_ids] = $location["location_description"];
	      }
   }
   
   $lists["pjm_account_executive"]["1)"] = "";
   $lists["pjm_account_manager"]["1)"] = "";
   $lists["pjm_account_executive"]["(2"] = "Account Executive -----";
   $lists["pjm_account_manager"]["(2"] = "Account Manager -----";
   }

   $user_rst = $common_db->GetUsersByRoleId(ROLE_ACCOUNT_EXECUTIVE);
   while ($user = mysql_fetch_assoc($user_rst)) {
      $lists["pjm_account_executive"][$user["login"]] = $user["name"];
   }
   $user_rst = $common_db->GetUsersByRoleId(ROLE_ACCOUNT_MANAGER);
   while ($user = mysql_fetch_assoc($user_rst)) {
      $lists["pjm_account_manager"][$user["login"]] = $user["name"];
   }
   if (!$pjm_can_view_my_projects_only) {
      $lists["pjm_account_executive"]["2)"] = "";
      $lists["pjm_account_manager"]["2)"] = "";
   }

   $lists["project_status_lookup"] = $project_status_lookup;
   $lists["alert_level_lookup"] = CreateSmartyArray($project_db->GetAlertLevels(),'alert_level_id','alert_level_description');

   $o["page_size_list"] = array(10, 30, 50, 100);
   $o["report_title"] = "Projects";

   //PrintHeader();
   //$smarty->assign('project_headers', $project_headers);
   header("Content-Type: text/xml");
   $smarty->assign("header", $project_headers);
   $smarty->assign("meta", $o);
   $smarty->assign('projects', $projects);
   $smarty->assign('search_params', $search_params);
   $smarty->assign('lists', $lists);
   $smarty->assign('pjm_can_view_my_projects_only', $pjm_can_view_my_projects_only);

   list($projects_sort_by, $projects_sort_order) = explode(' ', $order_by);
   $smarty->assign('projects_sort_by', $projects_sort_by);
   $smarty->assign('projects_sort_order', $projects_sort_order);
   $smarty->display($template);
   //PrintFooter();

}

function DisplayProjectSearch($o)
{
	global $smarty;
	$smarty->display('app/pjm/vr_display_projects_search.tpl');
}

/**
* GetFlashReportPJM()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed May 03 14:20:28 PDT 2006
*/
function GetFlashReportPJM()
{
   $pjmDB = new projectDB();
   $stmDB = new studyDB();
   if ($_SESSION['admin_id'] == SYSTEM_USER) {

      global $cfg, $servername;
      $smarty = new Smarty();

      $smarty->template_dir = '/var/www/smarty/' . $servername . '/templates';
      $smarty->compile_dir = '/var/www/smarty/' . $servername . '/templates_c';
      $smarty->cache_dir = '/var/www/smarty/' . $servername . '/cache';
      $smarty->config_dir = '/var/www/smarty/' . $servername . '/configs';


      $smarty->plugins_dir = array('plugins', $cfg['base_dir'].'/include/smarty_plugins');

      //require_once ($smarty->_get_plugin_filepath('modifier', 'url_encrypt'));

      //$smarty->register_modifier('url_encrypt', 'url_encrypt');


      $smarty->compile_check = TRUE;
      $smarty->force_compile = TRUE;
   } else {
      global $smarty;
   }

   $rst = $pjmDB->GetProjects(" AND p.pjm_status_id = 1", "p.alert_level_id DESC");
   $summary = array(
         "count_green"=>0,
         "count_yellow"=>0,
         "count_orange"=>0,
         "count_red"=>0,
         "count_total"=>0);
   while ($project = mysql_fetch_assoc($rst)) {
      $pjm_id = $project["pjm_id"];
      $projects[$pjm_id] = $project;

      $summary["count_total"]++;
      switch ($project["alert_level_id"]) {
         case "1": $summary["count_green"]++; break;
         case "2": $summary["count_yellow"]++; break;
         case "3": $summary["count_orange"]++; break;
         case "4": $summary["count_red"]++; break;
      }

      $projects[$pjm_id]['studies'] = array();
      $projects[$pjm_id]['element_alert_histogram'] = array(4=>0, 3=>0, 2=>0, 1=>0, 0=>0);

      // Get studies linked to this project
      $pjmDB->GetProjectElements($elements, $pjm_id);
      // first, we need to build a list of studies associated with this project
      $study_ids = array();
      foreach ($elements as $element){
         if ($element['pjm_element_type_id'] != PJM_ELEMENT_TYPE_NETMR_STUDY){
            continue;
         }
         $pjm_element_reference_id = $element['pjm_element_reference_id'];
         $study_ids[$pjm_element_reference_id] = $pjm_element_reference_id;
      }
      // then, we create a where clause for database to retrieve these studies
      if (count($study_ids)){
         $projects[$pjm_id]['studies'] = $stmDB->getListV2(
            " AND s.study_id IN (" .implode(',', $study_ids). ") ",   // extra conditions
            "",   // extra joins
            "",   // limit
            "s.alert_level_id DESC, s.study_name ASC " // order-by
         );
         // update the alert histogram
         foreach ($projects[$pjm_id]['studies'] as $study){
            $alert_level_id = $study['alert_level_id'];
            $projects[$pjm_id]['element_alert_histogram'][$alert_level_id] += 1;
         }
      }

      // Get alert/comment of the studies
      //foreach ($study_ids as $study_id){
         //$projects[$pjm_id]['studies'][$study_id]['last_alert'] = $study_db->GetLastStudyAlert($study_id);
      //}

      // Get alert/comment for this project
      $pjmDB->GetLastProjectComment($comment, $pjm_id);
      $projects[$pjm_id]['last_comment'] = $comment;

   }

   $summary["percent_green"] = $summary["count_green"]*100/$summary["count_total"];
   $summary["percent_yellow"] = $summary["count_yellow"]*100/$summary["count_total"];
   $summary["percent_orange"] = $summary["count_orange"]*100/$summary["count_total"];
   $summary["percent_red"] = $summary["count_red"]*100/$summary["count_total"];

   $smarty->assign("summary", $summary);
   $smarty->assign("projects", $projects);
   return $smarty->fetch("app/pjm/vr_projects_flash.tpl");

}


/**
* DisplayFlashReportPJM()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed May 03 13:07:36 PDT 2006
*/
function DisplayFlashReportPJM($o)
{
   $flash = GetFlashReportPJM();
   printHeader();
   echo $flash;
   printFooter();

   SendFlashReport();

}

/**
* SendFlashReport()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed May 03 14:21:30 PDT 2006
*/
function SendFlashReport()
{
   $flash = GetFlashReportPJM();
   QueueMessageThruCOM(PJM_MODULE_ID.MESSAGE_TYPE_FLASH_REPORT, array(0), "Projects Flash Report", $flash, array());
}

/**
* SendNewProjectAlert()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jul 17 15:32:30 PDT 2006
*/
function SendNewProjectAlert($pjm_id)
{
   global $smarty;
   $pjmDB = new projectDB();
   $studyDB = new studyDB();
   

   $pjmDB->GetProjectInfo($info, $pjm_id);

   $info["pjm_url"] = "http://".SERVER_NAME."/app/pjm/?action=display_project_details&pjm_id=".$info["pjm_id"];
   //print_r("<pre>".print_r($info, true)."</pre>");

   $pjmDB->GetProjectElements($elements, $pjm_id);
   foreach ($elements AS $element) {
      switch ($element["pjm_element_type_id"]) {
         case PJM_ELEMENT_TYPE_NETMR_STUDY : {
            $studies[] = $element["pjm_element_reference_id"];
         }break;
      }
   }
   
   if (is_array($studies) )
   {
   	
	   $extraFilters = " AND (s.study_id IN (".implode(", ", $studies)."))";
	   $project_studies = $studyDB->getListV2($extraFilters);
	
	   foreach ($project_studies AS $study) {
	      $elements[$study["study_id"]] = array_merge($elements[$study["study_id"]], $study);
	   }
   }
   //print_r("<pre>".print_r($elements, true)."</pre>");

   $pjmDB->GetProjectUserRoles($users, $pjm_id);

   //print_r("<pre>".print_r($users, true)."</pre>");
   foreach ($users AS $user) {
      $rcpt[] = $user["user_id"];
   }

   $attr = array(
      "pjm_id"=>$info["pjm_id"],
      "alert_level_id"=>$info["alert_level_id"],
      "account_id"=>$info["account_id"],
      "primary_am_id"=>$info["acct_mgr_user_id"],
      "primary_ae_id"=>$info["acct_exec_user_id"]);


   $smarty->assign("info", $info);
   $smarty->assign("elements", $elements);
   $smarty->assign("users", $users);
   $screen = $smarty->fetch("app/pjm/email_new_project_alert.tpl");
   $handheld = $smarty->fetch("app/pjm/handheld_new_project_alert.tpl");
   $tty = $smarty->fetch("app/pjm/tty_new_project_alert.tpl");

   $params = array(
      "message_type_id"=>PJM_MODULE_ID.MESSAGE_TYPE_NEW_PROJECT,
      "protocol"=>"v2",
      "rcpt"=>$rcpt,
      "msg"=>array(
         "subject"=>"NEW PROJECT FOR [".$info["account_name"]."]",
         "headers"=>array("from"=>"projectalert+".$info["pjm_id"]."@".SERVER_NAME),
         "body"=>array(
            "screen"=>$screen,
            "handheld"=>$handheld,
            "tty"=>$tty)),
      "attr"=>$attr);
   //echo($screen."<br>".$handheld."<br>".$tty);
   QueueMessageThroughCOMV2($params);
   //QueueMessageThruCOM(PJM_MODULE_ID.MESSAGE_TYPE_NEW_PROJECT, $rcpt, "NEW PROJECT FOR [".$info["account_name"]."]", $body, $attr);
   //echo ($body);
}




function AddNetMrStudy($study_id)
{
   global $error_message;

   $study_db = new studyDB();
   $study_db->_study = $study_id;

   // get study info (i.e. account of the study) from Net-MR
	$study_detail = $study_db->GetNetMr(array('study_id'=>$study_id));
	if (!$study_detail) {
	   $error_message = $study_db->error_message;
	   return CANNOT_RETRIEVE_STUDY;
	}

	// add an entry in study table
   $study_db->AddStudyV2(
      $study_id,
      $study_detail['account_id'],
      $study_detail['study_name'],
      date('Y-M-d'), // start date
      3              // study type; 3=Self-service. We assume that if it's only added when the BR is created, it was a self-service study.
   );

   // set the data source of the new study
	$study_db->SetDataSource(array(
	  'study_datasource_id' => 1, // 1=GTM
	  'created_by' => SYSTEM_USER
   ));

   // retrieve detail info of the account of the study (i.e. user-role, country code)
   $partner_detail = GetAccountDetailFromACM($study_detail['account_id']);

   // Set user-role of the study
   if (!is_array($partner_detail['user'])){
      $partner_detail['user'] = array();
   }
   foreach ($partner_detail['user'] as $user){
      $study_db->SetStudyRoleV2($study_id, $user['user_id'], $user['role_id']);
   }

   // Set Company/Partner info for the study
   $study_db->SetStudyAttrV2($study_id, 'V', 'ACCOUNT_NAME', $partner_detail['account_name']);
   $study_db->SetStudyAttrV2($study_id, 'V', 'ACCOUNT_COUNTRY_CODE', $partner_detail['country_code']);

   //set the default alert level to green
   $study_db->setAlertLevel(array(
      'alert_level_id'=>ALRT_GREEN
   ));
}




/**
* HBRPC_GetStudyDetails()
*
* @param array $params - Parameters should contain key 'study_id'
* @return array
* @throws
* @access
* @global
* @since
*/
function HBRPC_PJM_GetStudyDetails($params)
{
   global $error_message;

   $study_id = @$params['study_id'];
   if (!$study_id){
      return array(
         "error_code" => 404,
         "error_description" => "Parameter [study_id] is blank."
      );
   }

   $o = array();
   $o['study_id'] = $study_id;
   $o['login'] = $_SESSION['admin_id'];
   $o['time_zone_id'] = $_SESSION['time_zone_id'];

   $study = new studyDB();
   $study->_study = $o['study_id'];

   // if the study is not in file, create it on the fly
   if (!$study->onfile(array("study_id"=>$study_id))) {

      $params = array(
         'study_id' => $study_id,
         'study_type_id' => 1,   // 1=Server Bureau
         'start_date' => date('Y-m-d'),
         'study_datasource_id' => array(1),   // 1=GMI Sample
         'created_by' => SYSTEM_USER
      );
      $error_code = AddNetMrStudy($study_id);
      if ($error_code){
         return array(
            "error_code" => 404,
            "error_description" => $error_message
         );
      }
   }

//   $study->SetStudyAttrV2($study_id, 'V', 'SAT_SURVEY_STATUS', 'PENDING');
//   $study->setStatus(STATUS_CLOSED);
   // Get general study info, like Partner/Account ID, Study Type, Start Date
   $study_info = $study->getHeaderDetail();

   // Get the Data Sources used by the study
   $rs = $study->GetDataSource($o);
	while ($r_ds = mysql_fetch_assoc($rs)) {
	   $study_datasource_id = $r_ds['study_datasource_id'];
		$study_info['data_sources'][$study_datasource_id] = $r_ds;
	}

	// Get employees who work on this study
	$rs = $study->GetStudyUsers();
	while ($r_user_role = mysql_fetch_assoc($rs)) {
	   $login = $r_user_role['login'];
		$study_info['user_roles'][$login] = $r_user_role;
	}

   // Get various study attribute, like PO Number,
	$r['attr'] = $study->getAttrDef();
   while ($a = mysql_fetch_assoc($r['attr'])) {
      $attr_name = $a['study_attr'];
      $study_info['attr'][$attr_name] = $study->getAttr($a);
   }

   // Get contacts for the study
   $rs = $study->GetContacts();
   while ($r_contact = mysql_fetch_assoc($rs)) {
      $study_contact_id = $r_contact['study_contact_id'];
      $study_info['contacts'][$study_contact_id] = $r_contact;
   }

   return $study_info;
}




function ConvertToArray($input){
   if (is_array($input)){
      return $input;
   }

   if ($input === false || $input === null){
      return array();
   }

   return array($input);
}


function MysqlInList($items){
   $list = "";
   foreach ($items as $item){
      $list .= "'".mysql_real_escape_string($item)."',";
   }
   return preg_replace('/,$/', '', $list);   // remove last comma
}




/**
* HBRPC_GetPortlet()
*
* @param integer $o['portlet_id']
* @param integer/array $o['filter']
* @return
* @throws
* @access
* @global
*/
function HBRPC_PJM_GetPortlet($o)
{

   // If user_id is specified and only one user is given,
   //   expand user_id to include all people reporting the specified user, so
   //   account manager can see account executive's records
   $filter_user_id =& $o['filter']['user_id'];
   if (!is_null($filter_user_id) && !is_array($filter_user_id)){
      $filter_user_id = GetReportees($filter_user_id, INCLUDE_MYSELF_IN_OUTPUT);
      $filter_user_id = array_unique($filter_user_id);
   }

   // initialize Smarty object if needed
   if ($_SESSION['admin_id'] == SYSTEM_USER) {
      global $cfg, $servername, $smarty;
      $GLOBALS['smarty'] = new Smarty();

      $smarty->template_dir = '/var/www/smarty/' . $servername . '/templates';
      $smarty->compile_dir  = '/var/www/smarty/' . $servername . '/templates_c';
      $smarty->cache_dir    = '/var/www/smarty/' . $servername . '/cache';
      $smarty->config_dir   = '/var/www/smarty/' . $servername . '/configs';
      $smarty->plugins_dir = array('plugins', $cfg['base_dir'].'/include/smarty_plugins');
      $smarty->compile_check = TRUE;
      $smarty->force_compile = TRUE;
   }


   switch ($o['portlet_id']){
      case PJM_MODULE_ID.PORTLET_ID_PROJECT_ALERTS:
         return GetProjectAlertPortlet($o);

      case PJM_MODULE_ID.PORTLET_ID_STUDY_ALERTS:
         return GetStudyAlertPortlet($o);

      case PJM_MODULE_ID.PORTLET_ID_EXPECTED_STUDY_CLOSURES:
         return GetExpectedStudyClosuresPortlet($o);

      case PJM_MODULE_ID.PORTLET_ID_OPEN_STUDIES:
         return GetOpenStudiesPortlet($o["filter"]);

      case PJM_MODULE_ID.PORTLET_ID_OPEN_PROJECTS:
         return GetOpenProjectsPortlet($o["filter"]);

      default:
         return array("error_code"=>404, "error_message"=>"Unknown portlet ID [$portlet_id].");
   }
}




/* GetProjectAlertPortlet()
*
* @param integer/array $o['filter']['alert_level_id']  projects that are in this alert level will be returned
* @param integer/array $o['filter']['account_id']  projects that are associated with this company will be returned
* @param integer/array $o['filter']['user_id']  projects that are managed by this user will be returned
* @param integer/array $o['filter']['pjm_status_id'] projects that are in this specific status will be returned
* @return
* @throws
* @access
* @global
*/
function GetProjectAlertPortlet($o)
{
   global $userRights, $encryption, $smarty;

   $condition = "";
   $default_filter = array( 'pjm_status_id' => 1 ); // pjmstatus_id=1 means OPEN projects


   $filter = ConvertToArray($o['filter']);
   $filter = array_merge($default_filter, $filter);

   // display projects with certain alert level?
   $alert_level_ids = ConvertToArray($filter['alert_level_id']);
   if ($alert_level_ids){
      $condition .= " AND p.alert_level_id IN (".MysqlInList($alert_level_ids).") ";
   }

   // display projects associated with certain accounts/customers only?
   $account_ids = ConvertToArray($filter['account_id']);
   if ($account_ids){
      $condition .= " AND pa_account_id.pjm_attr_value IN (".MysqlInList($account_ids).") ";
   }

   // display projects that are handled by specific people?
   $user_ids = ConvertToArray($filter['user_id']);
   if ($user_ids){
      $condition .= " AND pu.user_id IN (".MysqlInList($user_ids).") ";
   }

   // display projects that are in open/close/on-hold status?
   $pjm_status_ids = ConvertToArray($filter['pjm_status_id']);
   if ($pjm_status_ids){
      $condition .= " AND p.pjm_status_id IN (".MysqlInList($pjm_status_ids).") ";
   }


   $timezone = GetTimeZone(array('login'=>$_SESSION['admin_id']));
   $project_db = new projectDB($timezone);
   $study_db = new studyDB($timezone);

   // Retrieve projects
   $projects = array();
   $rst = $project_db->GetProjects($condition, "p.alert_level_id DESC, p.pjm_description ASC ");
   while ($p = mysql_fetch_assoc($rst)) {
      $projects[$p["pjm_id"]] = $p;
   }

   // Retrieve details of each project
   $study_ids = array();
   foreach ($projects as $pjm_id => $project){
      $projects[$pjm_id]['studies'] = array();
      $projects[$pjm_id]['element_alert_histogram'] = array(4=>0, 3=>0, 2=>0, 1=>0, 0=>0);

      // Get studies linked to this project
      $project_db->GetProjectElements($elements, $pjm_id);
      // first, we need to build a list of studies associated with this project
      $study_ids = array();
      foreach ($elements as $element){
         if ($element['pjm_element_type_id'] != PJM_ELEMENT_TYPE_NETMR_STUDY){
            continue;
         }
         $pjm_element_reference_id = $element['pjm_element_reference_id'];
         $study_ids[$pjm_element_reference_id] = $pjm_element_reference_id;
      }
      // then, we create a DB-where clause for retrieving these studies
      if (count($study_ids)){
         $projects[$pjm_id]['studies'] = $study_db->getListV2(
            " AND s.study_id IN (" .implode(',', $study_ids). ") ",   // extra conditions
            "",   // extra joins
            "30",   // limit
            "s.alert_level_id DESC, s.study_name ASC " // order-by
         );
         // update the alert histogram
         foreach ($projects[$pjm_id]['studies'] as $study){
            $alert_level_id = $study['alert_level_id'];
            $projects[$pjm_id]['element_alert_histogram'][$alert_level_id] += 1;
         }
      }

      // Get alert/comment of the studies
      foreach ($study_ids as $study_id){
         $projects[$pjm_id]['studies'][$study_id]['last_alert'] = $study_db->GetLastStudyAlert($study_id);
      }

      // Get alert/comment for this project
      $project_db->GetLastProjectComment($comment, $pjm_id);
      $projects[$pjm_id]['last_comment'] = $comment;
   }

   $msg = array();
   $smarty->assign('page_size', PORTLET_PAGE_SIZE );
   $smarty->assign('projects', $projects);
   $msg['body'] = $smarty->fetch('app/pjm/ptl_display_projects.tpl');
   return $msg;

}




/**
* GetStudyAlertPortlet()
*
* @param integer/array $o['filter']['alert_level_id']  studies that are in this alert level will be returned
* @param integer/array $o['filter']['account_id']  studies that are associated with this company will be returned
* @param integer/array $o['filter']['user_id']  studies that are managed by this user will be returned
* @param integer/array $o['filter']['study_status_id'] studies that are in this OPEN/CLOSE/HOLD-ON status will be returned
* @param integer/array $o['filter']['study_id'] specific a set of studies to be returned
* @return
* @throws
* @access
* @global
*/
function GetStudyAlertPortlet($o)
{
   global $userRights, $encryption, $smarty;

   $conditions = "";
   $joins = "";
   $default_filter = array('study_status_id'=>STATUS_OPEN); // study_status_id=1 means OPEN study

   $filter = ConvertToArray($o['filter']);
   $filter = array_merge($default_filter, $filter);

   // display studies with certain alert level?
   $alert_level_ids = ConvertToArray($filter['alert_level_id']);
   if ($alert_level_ids){
      $conditions .= " AND s.alert_level_id IN (".MysqlInList($alert_level_ids).") ";
   }

   // display studies for certain accounts/customers only?
   $account_ids = ConvertToArray($filter['account_id']);
   if ($account_ids){
      $conditions .= " AND s.partner_id IN (".MysqlInList($account_ids).") ";
   }

   // display studies that are handled by specific people?
   $user_ids = ConvertToArray($filter['user_id']);
   if ($user_ids){
      $conditions .= " AND su.login IN (".MysqlInList($user_ids).") ";
   }

   // display specific studies by study ID?
   $study_ids = ConvertToArray($filter['study_id']);
   if ($study_ids){
      $conditions .= " AND s.study_id IN (".MysqlInList($study_ids).") ";
   }

   // display studies that are in open/close/on-hold status?
   $study_status_ids = ConvertToArray($filter['study_status_id']);
   if ($study_status_ids){
      $conditions .= " AND s.study_status_id IN (".MysqlInList($study_status_ids).") ";
   }



   // retrieve studies
   $study_db = new studyDB();
   $study_db->timezone = GetTimeZone($o);
   $studies = array();
   $studies = $study_db->getListV2(
      $conditions,
      $joins,
      "", // limit clause
      "s.alert_level_id DESC, s.study_name ASC"
   );


   // Get the last comment of each study
   foreach ($studies as $study_id => $study){
      $study_db->GetLastStudyComment($comment, $study_id);
      $studies[$study_id]['last_comment'] = $comment;
   }

   $smarty->assign('page_size', PORTLET_PAGE_SIZE );
   $smarty->assign('studies', $studies);
   $msg = array();
   $msg['body'] = $smarty->fetch('app/stm/ptl_display_study_alerts.tpl');

   return $msg;
}









/**
* GetExpectedStudyClosuresPortlet()
*
* @param integer/array $o['filter']['alert_level_id']  projects that are in this alert level will be returned
* @param integer/array $o['filter']['account_id']  projects that are associated with this company will be returned
* @param integer/array $o['filter']['user_id']  projects that are managed by this user will be returned
* @return
* @throws
* @access
* @global
*/
function GetExpectedStudyClosuresPortlet($o)
{
   global $userRights, $encryption, $smarty;

   $conditions = "";
   $joins = "";

   $filter = ConvertToArray($o['filter']);

   // display studies with certain alert level only?
   $alert_level_ids = ConvertToArray($filter['alert_level_id']);
   if ($alert_level_ids){
      $conditions .= " AND s.alert_level_id IN (".MysqlInList($alert_level_ids).") ";
   }

   // display studies associated with certain accounts/customers only?
   $account_ids = ConvertToArray($filter['account_id']);
   if ($account_ids){
      $conditions .= " AND s.partner_id IN (".MysqlInList($account_ids).") ";
   }

   // display studies that are handled by specific people?
   $user_ids = ConvertToArray($filter['user_id']);
   if ($user_ids){
      $conditions .= " AND su.login IN (".MysqlInList($user_ids).") ";
   }

   // For displaying expected-study-closures portlet, we want to show only studies that are not closed yet.
   $conditions .= " AND s.study_status_id = ".STATUS_OPEN." ";

   // Only display studies that have date/task set
   $conditions .= " AND s.current_estimated_complete_date <> 0 ";


   // retrieve studies
   $study_db = new studyDB();
   $study_db->timezone = GetTimeZone($o);
   $studies = array();
   $studies = $study_db->getListV2(
      $conditions,
      $joins,
      "30", // limit clause
      "s.current_estimated_complete_date ASC, s.study_name ASC"
   );

   // Get the last comment of each study
   foreach ($studies as $study_id => $study){
      $study_db->GetLastStudyComment($comment, $study_id);
      $studies[$study_id]['last_comment'] = $comment;
   }

   $smarty->assign('page_size', PORTLET_PAGE_SIZE );
   $smarty->assign('studies', $studies);
   $msg = array();
   $msg['body'] = $smarty->fetch('app/stm/ptl_display_expected_study_closures.tpl');

   return $msg;

}

/**
* GetOpenStudiesPortlet()
*
* @param
* @todo NOT YET COMPLETED
* @return
* @since  - Thu Sep 07 20:12:29 PDT 2006
*/
function GetOpenStudiesPortlet($filter)
{
   //$smarty = $GLOBALS["smarty"];
   //global $smarty;

   if ($_SESSION['admin_id'] == SYSTEM_USER) {

      global $cfg, $servername;
      $smarty = new Smarty();

      $smarty->template_dir = '/var/www/smarty/' . $servername . '/templates';
      $smarty->compile_dir = '/var/www/smarty/' . $servername . '/templates_c';
      $smarty->cache_dir = '/var/www/smarty/' . $servername . '/cache';
      $smarty->config_dir = '/var/www/smarty/' . $servername . '/configs';


      $smarty->plugins_dir = array('plugins', $cfg['base_dir'].'/include/smarty_plugins');

      //require_once ($smarty->_get_plugin_filepath('modifier', 'url_encrypt'));

      //$smarty->register_modifier('url_encrypt', 'url_encrypt');


      $smarty->compile_check = TRUE;
      $smarty->force_compile = TRUE;
   } else {
      global $smarty;
   }

   $studyDB = new studyDB();

   $columns = array("display_account_name"=>1, "display_account_executive"=>0, "display_account_manager"=>0, "display_invoice_number"=>1, "count"=>3);
   $f = " AND s.study_status_id IN (1)";
   if (isset($filter["account_id"])) {
      $columns["display_account_name"] = 0;
      if (is_array($filter["account_id"])) {
         $f .= " AND s.partner_id IN (".implode(", ", $filter["account_id"]).") ";
      } else {
         $f .= " AND s.partner_id = '".$filter["account_id"]."' ";
      }
   }

   if (isset($filter["user_id"])) {
      //$columns["display_account_name"]=1;
      if (!is_array($filter["user_id"]))
         $user_ids = GetReportees($filter["user_id"], 0);
      elseif (sizeof($filter["user_id"])==1)
         $user_ids = GetReportees($filter["user_id"][0], 0);
      else
         $user_ids = $filter["user_id"];
      //print_r($user_ids);
      $f .= "AND (su.login IN (".implode(", ", $user_ids).")) ";
   }

   if (isset($filter["display_account_name"])&&($filter["display_account_name"]==0)) {
      $columns["display_account_name"] = 0;
   }
   if (isset($filter["display_account_executive"])&&($filter["display_account_executive"]==1)) {
      $columns["display_account_executive"] = 1;
   }
   if (isset($filter["display_account_manager"])&&($filter["display_account_manager"]==1)) {
      $columns["display_account_manager"] = 1;
   }

   $col_count = array_count_values($columns);
   $col_count = $col_count[1];

   $columns["count"] = 7+$col_count;

   $total = 0;
//   $study_rst = $studyDB->getListV2($f, "", "LIMIT 10", "s.start_date DESC");
//   while ($study = mysql_fetch_assoc($study_rst)) {
//      if (IsCreditMemo($armc["armc_type_id"])) {
//         $total -= $armc["amount"];
//         $armc["display_amount"] = "<font color='Red'>$".number_format(0-$armc["amount"], 2)."</font>";
//      }else{
//         $total += $armc["amount"];
//         $armc["display_amount"] = "$".number_format($armc["amount"], 2);
//      }
//      $total += $study[""]
//      $list[] = $study;
//   }
   $list = $studyDB->getListV2($f, "", "10", "s.start_date DESC, s.study_status_id");
   $total = 0;
   foreach ($list AS $s)
      $total += $s["project_value"];
   $smarty->assign("list", $list);
   $smarty->assign("total", $total);
   $smarty->assign("columns", $columns);
   $template = "app/stm/ptl_open_studies.tpl";
   if (isExternalUser())
      $template = "app/stm/ext/ptl_open_studies.tpl";
   return array("body"=>$smarty->fetch($template));

}

/**
* GetOpenProjectsPortlet()
*
* @param
* @todo NOT YET COMPLETED
* @return
* @since  - Thu Sep 07 20:54:41 PDT 2006
*/
function GetOpenProjectsPortlet($filter)
{
   if ($_SESSION['admin_id'] == SYSTEM_USER) {

      global $cfg, $servername;
      $smarty = new Smarty();

      $smarty->template_dir = '/var/www/smarty/' . $servername . '/templates';
      $smarty->compile_dir = '/var/www/smarty/' . $servername . '/templates_c';
      $smarty->cache_dir = '/var/www/smarty/' . $servername . '/cache';
      $smarty->config_dir = '/var/www/smarty/' . $servername . '/configs';


      $smarty->plugins_dir = array('plugins', $cfg['base_dir'].'/include/smarty_plugins');

      //require_once ($smarty->_get_plugin_filepath('modifier', 'url_encrypt'));

      //$smarty->register_modifier('url_encrypt', 'url_encrypt');


      $smarty->compile_check = TRUE;
      $smarty->force_compile = TRUE;
   } else {
      global $smarty;
   }

   $pjmDB = new projectDB();

   $columns = array("display_account_name"=>1, "display_account_executive"=>0, "display_account_manager"=>0, "display_invoice_number"=>1, "count"=>3);
   $f = " AND p.pjm_status_id IN (1)";
   if (isset($filter["account_id"])) {
      $columns["display_account_name"] = 0;
      if (is_array($filter["account_id"])) {
         $f .= " AND pa_account_id.pjm_attr_value IN (".implode(", ", $filter["account_id"]).") ";
      } else {
         $f .= " AND pa_account_id.pjm_attr_value = '".$filter["account_id"]."' ";
      }
   }

   if (isset($filter["user_id"])) {
      //$columns["display_account_name"]=1;
      if (!is_array($filter["user_id"]))
         $user_ids = GetReportees($filter["user_id"], 0);
      elseif (sizeof($filter["user_id"])==1)
         $user_ids = GetReportees($filter["user_id"][0], 0);
      else
         $user_ids = $filter["user_id"];
      //print_r($user_ids);
      $f .= "AND (pu.login IN (".implode(", ", $user_ids).")) ";
   }

   if (isset($filter["display_account_name"])&&($filter["display_account_name"]==0)) {
      $columns["display_account_name"] = 0;
   }
   if (isset($filter["display_account_executive"])&&($filter["display_account_executive"]==1)) {
      $columns["display_account_executive"] = 1;
   }
   if (isset($filter["display_account_manager"])&&($filter["display_account_manager"]==1)) {
      $columns["display_account_manager"] = 1;
   }

   $col_count = array_count_values($columns);
   $col_count = $col_count[1];

   $columns["count"] = 4+$col_count;

   $total = 0;
//   $study_rst = $studyDB->getListV2($f, "", "LIMIT 10", "s.start_date DESC");
//   while ($study = mysql_fetch_assoc($study_rst)) {
//      if (IsCreditMemo($armc["armc_type_id"])) {
//         $total -= $armc["amount"];
//         $armc["display_amount"] = "<font color='Red'>$".number_format(0-$armc["amount"], 2)."</font>";
//      }else{
//         $total += $armc["amount"];
//         $armc["display_amount"] = "$".number_format($armc["amount"], 2);
//      }
//      $total += $study[""]
//      $list[] = $study;
//   }
   $rst = $pjmDB->GetProjects($f, "p.pjm_start_date DESC", "LIMIT 10");
   while ($p = mysql_fetch_assoc($rst)) {
      $list[$p["pjm_id"]] = $p;
   }
   $smarty->assign("list", $list);
   $smarty->assign("columns", $columns);
   $template = "app/pjm/ptl_open_projects.tpl";
   if (isExternalUser())
      $template = "app/pjm/ext/ptl_open_projects.tpl";
   return array("body"=>$smarty->fetch($template));

}

/**
* SetStudySatisfactionSurveySendError()
* Mark down the problem we encounterred when sending out satisfaction survey
*
* @param integer $study_id
* @param string $error_message
* @return
* @throws
* @access
* @global
*/
function SetStudySatisfactionSurveySendError($study_id, $status, $error_message)
{
   $study_db = new studyDB();
   $study_db->SetStudyAttrV2($study_id, 'V', 'SAT_SURVEY_STATUS', $status);
   $study_db->SetStudyAttrV2($study_id, 'V', 'SAT_SURVEY_ERROR', $error_message);
   $study_db->SetStudyAttrV2($study_id, 'V', 'SAT_SURVEY_ERROR_DATE', gmdate("Y-m-d"));
}

/**
* SetStudySatisfactionSurveySendSuccess()
* Mark down that satisfaction survey has been sent for a specific study, so
* we won't send the survey again in the future
*
* @param integer $study_id
* @param string $contact_name
* @param string $contact_email
* @return
* @throws
* @access
* @global
*/
function SetStudySatisfactionSurveySendSuccess($study_id, $contact_name, $contact_email)
{
   $study_db = new studyDB();
   $study_db->SetStudyAttrV2($study_id, 'V', 'SAT_SURVEY_STATUS', 'SENT');
   $study_db->SetStudyAttrV2($study_id, 'V', 'SAT_SURVEY_CONTACT_NAME', $contact_name);
   $study_db->SetStudyAttrV2($study_id, 'V', 'SAT_SURVEY_CONTACT_EMAIL', $contact_email);
   $study_db->SetStudyAttrV2($study_id, 'V', 'SAT_SURVEY_SENT_DATE', gmdate("Y-m-d"));
}

function SetProjectSatisfactionSurveySendError($pjm_id, $status, $error_message)
{
   $project_db = new projectDB();
   $project_db->SetProjectAttribute($not_used, $pjm_id, 'SAT_SURVEY_STATUS', $status);
   $project_db->SetProjectAttribute($not_used, $pjm_id, 'SAT_SURVEY_ERROR_MESSAGE', $error_message);
   $project_db->SetProjectAttribute($not_used, $pjm_id, 'SAT_SURVEY_ERROR_DATE', gmdate("Y-m-d"));
}


function SetProjectSatisfactionSurveySendSuccess($pjm_id, $status, $contact_name, $contact_email)
{
   $project_db = new projectDB();
   $project_db->SetProjectAttribute($not_used, $pjm_id, 'SAT_SURVEY_STATUS', $status);
   $project_db->SetProjectAttribute($not_used, $pjm_id, 'SAT_SURVEY_CONTACT_NAME', $contact_name);
   $project_db->SetProjectAttribute($not_used, $pjm_id, 'SAT_SURVEY_CONTACT_EMAIL', $contact_email);
   $project_db->SetProjectAttribute($not_used, $pjm_id, 'SAT_SURVEY_SENT_DATE', gmdate("Y-m-d"));
}


/**
* PrepareSatSurvey()
*
* @param
* @todo NOT YET COMPLETED
* @return
* @since  - Mon Nov 27 09:25:51 PST 2006
*/
function PrepareSatSurvey($study_id)
{
   $studyDB = new studyDB();
   $pjmDB = new projectDB();
   $userDB = new userDB();
//   $max[7] = $studyDB->GetStudySetting("SAT_SURVEY_WEEK_LIMIT");
//   $max[28] = $studyDB->GetStudySetting("SAT_SURVEY_MONTH_LIMIT");
   $info = $studyDB->GetSatSurveyInfo($study_id);
   $account_info = HBRPCCall('acm', 'GetAccountDetail', array("account_id"=>$info["account_id"]));
   //echo ("<pre>".print_r($info, true)."</pre>");
   foreach ($account_info["user"] AS $user) {
      switch ($user["role_id"]) {
      	case ROLE_PRIMARY_ACCT_EXEC:
      		$info["acct_ae_login"] = $user["user_id"];
      		break;
      	case ROLE_PRIMARY_ACCT_MGR:
      		$info["acct_am_login"] = $user["user_id"];
      		break;
      	case ROLE_FULFILLMENT_MANAGER:
      		$info["acct_fm_login"] = $user["user_id"];
      		break;
      	case ROLE_CS_LEAD:
      		$info["acct_cs_login"] = $user["user_id"];
      		break;
      }
   }

   foreach ($account_info["attr"] AS $attr) {
      switch ($attr["account_attr_name"]) {
         case "STM_SEND_SAT_SURVEY":
            $info["enabled"] = $attr["account_attr_value"];
            break;
         case "GLOBAL_ACCOUNT_TIER_LEVEL":
            $info["account_tier"] = $attr["account_attr_value"];
            break;
      }
   }


   $info["account_name"] = ($info["account_name"]?$info["account_name"]:$account_info["account_name"]);
   $info["country_code"] = ($info["country_code"]?$info["country_code"]:$account_info["country_code"]);
   $info["region"] = ($info["region"]?$info["region"]:$account_info["region_id"]);

   $pjmDB->GetProjectsByElementID($projects, PJM_ELEMENT_TYPE_NETMR_STUDY, $info["study_id"]);
   if ($project = reset($projects)) {
      $info["pjm_id"] = $project["pjm_id"];
      $project_info = $pjmDB->GetSatSurveyInfo($info["pjm_id"]);
      $info["pjm_description"] = $project_info["pjm_description"];
      $info["ae_login"] = $project_info["ae_login"];
      $info["ae_name"] = $project_info["ae_name"];
      $info["ae_email"] = $project_info["ae_email"];
      $info["am_login"] = $project_info["am_login"];
      $info["am_name"] = $project_info["am_name"];
      $info["am_email"] = $project_info["am_email"];
      $info["fm_login"] = $project_info["fm_login"];
      $info["fm_name"] = $project_info["fm_name"];
      $info["fm_email"] = $project_info["fm_email"];
      $info["cs_login"] = $project_info["cs_login"];
      $info["cs_name"] = $project_info["cs_name"];
      $info["cs_title"] = $project_info["cs_title"];
      $info["cs_email"] = $project_info["cs_email"];
//      $info["contact_name"] = $project_info["contact_name"];
//      $info["contact_email"] = $project_info["contact_email"];
   }else{
      $info["pjm_id"] = 0;
   }

   if (is_null($info["ae_login"])) {
      $info["ae_login"] = $info["acct_ae_login"];
      if (!is_null($info["ae_login"])) {
         $info["ae_name"] = $userDB->getName($info["ae_login"]);
         $info["ae_email"] = $userDB->GetEmailByLogin($info["ae_login"]);
      }
   }
   if (is_null($info["am_login"])) {
      $info["am_login"] = $info["acct_am_login"];
      if (!is_null($info["am_login"])) {
         $info["am_name"] = $userDB->getName($info["am_login"]);
         $info["am_email"] = $userDB->GetEmailByLogin($info["am_login"]);
      }
   }
   if (is_null($info["fm_login"])) {
      $info["fm_login"] = $info["acct_fm_login"];
      if (!is_null($info["fm_login"])) {
         $info["fm_name"] = $userDB->getName($info["fm_login"]);
         $info["fm_email"] = $userDB->GetEmailByLogin($info["fm_login"]);
      }
   }
   if (is_null($info["cs_login"])) {
      $info["cs_login"] = $info["acct_cs_login"];
      if (!is_null($info["cs_login"])) {
         $info["cs_name"] = $userDB->getName($info["cs_login"]);
         $info["cs_title"] = $userDB->GetUserAttr($info["cs_login"], "TITLE");
         $info["cs_email"] = $userDB->GetEmailByLogin($info["cs_login"]);
      }
   }

   $info["teritory"] = $info["cs"];
//   $counts[7] = $studyDB->GetSatSurveyCounts($info["account_id"], 7);
//   $counts[28] = $studyDB->GetSatSurveyCounts($info["account_id"], 28);
   if ($info["enabled"]) {
      if (($info["contact_name"] == "") || ($info["contact_email"] == "")) {
         $info["delivery_status"] = "ERROR";
         SetStudySatisfactionSurveySendError($info["study_id"], "ERROR", "Missing client's project manager or the project manager's email");
         if ($info["pjm_id"]) {
            SetProjectSatisfactionSurveySendError($info["pjm_id"], "ERROR", "Missing client's project manager or the project manager's email");
         }
      }else{
         $info["delivery_status"] = "PENDING";
      }
   } else {
      $info["delivery_status"] = "DISABLED";
      SetStudySatisfactionSurveySendError($info["study_id"], "DISABLED", "Satisfaction survey is disabled for account ".$info["account_name"].".");
      if ($info["pjm_id"]) {
         SetProjectSatisfactionSurveySendError($info["pjm_id"], "DISABLED", "Satisfaction survey is disabled for account ".$info["account_name"].".");
      }
   }
   //echo ("<pre>".print_r($account_info, true).print_r($info, true)."</pre>");
   if ($studyDB->IsStudySatSurveyLog($info["study_id"])) {
      $studyDB->UpdateStudySatSurveyLog($info);
   }else{
      $studyDB->InsertStudySatSurveyLog($info);
   }

   return $info;
}

/**
* GetSatSurveyLog()
*
* @param
* @todo NOT YET COMPLETED
* @return
* @since  - Mon Dec 04 09:08:08 PST 2006
*/
function GetSatSurveyLog()
{
   global $smarty;
   $studyDB = new studyDB();
   $pjmDB = new projectDB();

   $where = " AND delivery_status IN ('ERROR') AND created_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
   $rst = $studyDB->GetStudySatSurveyLog($where);
   $errors = array();
   $all_errors = array();
//   while ($log = mysql_fetch_assoc($rst)) {
//      $info = PrepareSatSurvey($log["study_id"]);
//      if ($info["delivery_status"] == "ERROR") {
//         $send_to = "";
//         if ($info["am_login"]) {
//            $send_to = $info["am_login"];
//         } else {
//            if ($info["ae_login"]) {
//               $send_to = $info["am_login"];
//            }
//         }
//         if ($info["pjm_id"]) {
//            $info["url_project"] = "http://".SERVER_NAME."/app/pjm/?action=display_project_details&pjm_id=".$info["pjm_id"];
//         } else {
//            $info["url_study"] = "http://".SERVER_NAME."/app/stm/?action=vw_detail&study_id=".$info["study_id"];
//         }
//			$info["url_armc"] = "http://".SERVER_NAME."/app/atm/armc/?action=display_armc_details&armc_id=".$info["armc_id"];
//         $info["url_account"] = "http://".SERVER_NAME."/app/acm/?action=display_account_detail&account_id=".$info["account_id"];
//
//         if ($info["armc_id"] == "") {
//         	$info["error_message"] = "Missing Billing Report Reference";
//         	$info["html_instruction"] = "The study reference to the Billing Report seems to be missing. Please contact the <a href='mailto:hbsupport@gmi-mr.com?subject=Study ".$info["study_id"]." BR reference missing'>HB Support team</a> to have this issue corrected";
//         } else if ($info["contact_name"] == "") {
//            $info["error_message"] = "Missing client's project manager";
//            $info["html_instruction"] = "Go to <a href='".$info["url_armc"]."'>Billing Report page</a> and select the Project Contact.";
//            if ($info["pjm_id"]) {
//               $info["html_instruction"] = "Go to <a href='".$info["url_project"]."'>Project page</a> and ".
//                                    "click 'Add/Remove Contacts...' to assign a client contact with Project Manager role.";
//            } else {
//               $info["html_instruction"] = "Go to <a href='".$info["url_study"]."'>Study page</a> and ".
//                               "click 'Associate Contact' button to assign a client contact with project manager role.";
//            }
//         } else {
//            $info["error_message"] = "Missing email address for client contact - ".$info["contact_name"];
//            $info["html_instruction"] = "Go to the <a href='".$info["url_account"]."'>Account</a> to add/edit contacts, ";
//            $info["html_instruction"] .= "and then to the <a href='".$info["url_armc"]."'>Billing Report</a> and re-assign the Project Contact";
//         }
//         if ($send_to) {
//            $errors[$send_to][] = $info;
//         }
//         $all_errors[] = $info;
//      }
//   }

//   foreach ($errors AS $send_to=>$studies_with_errors) {
//      if (count($studies_with_errors)) {
//         $smarty->assign("errors", $studies_with_errors);
//         $html_message = $smarty->fetch("app/stm/email_satisfaction_survey_delivery_report.tpl");
//
//         $params = array(
//            "message_type_id" => PJM_MODULE_ID.MESSAGE_TYPE_SATISFACTION_SURVEY_INDIVIDUAL_DELIVERY_REPORT,
//            "rcpt" => array($send_to),
//            "msg" => array(
//               "subject" => "Satisfaction Survey Delivery Report",
//               "body" => wordwrap($html_message, 60)
//            ),
//            "attr" => array("user_id" => $send_to)
//         );
//         //echo ("<pre>".print_r($params, true)."</pre>");
//         HBRPCCall('com', 'QueueMessage', $params);
//      }
//   }

//   if (count($all_errors)) {
//      $smarty->assign("errors", $all_errors);
//      $html_message = $smarty->fetch("app/stm/email_satisfaction_survey_delivery_report.tpl");
//      $params = array(
//         "message_type_id" => PJM_MODULE_ID.MESSAGE_TYPE_SATISFACTION_SURVEY_ALL_DELIVERY_REPORT,
//         "rcpt" => array(0),
//         "msg" => array(
//            "subject" => "Satisfaction Survey Delivery Report",
//            "body" => wordwrap($html_message, 60)
//         ),
//      );
//      //echo ("<pre>".print_r($params, true)."</pre>");
//      HBRPCCall('com', 'QueueMessage', $params);
//   }


   $max[7] = $studyDB->GetStudySetting("SAT_SURVEY_WEEK_LIMIT");
   $max[28] = $studyDB->GetStudySetting("SAT_SURVEY_MONTH_LIMIT");

   $where = " AND delivery_status IN ('PENDING')";
   $rst = $studyDB->GetProjectSatSurveyLog($where);
   while ($log = mysql_fetch_assoc($rst)) {
      $count[7] = $studyDB->GetSatSurveyCounts($log["account_id"], 7);
      $count[28] = $studyDB->GetSatSurveyCounts($log["account_id"], 28);

      $pjmDB->GetProjectElements($elements, $log["pjm_id"]);

      $studies = explode(";", $log["study_id"]);
      $study_names = explode("/", $log["study_names"]);
      $country_codes = explode("/", $log["country_codes"]);
      $regions = explode("/", $log["regions"]);
      $teritories = explode("/", $log["teritories"]);
      $study_types = explode("/", $log["study_types"]);
      $datasources = explode("/", $log["study_datasources"]);
      $products = explode("/", $log["products"]);

      //echo ("<pre>".print_r($count, true)."</pre>");
      $log["study_id"] = $log["pjm_id"]." (".$log["study_id"].")";
      $first = true;
      $cache = $log;
      foreach ($studies AS $key=>$study_id) {
         $log["study_id"] = $study_id;
         $log["study_name"] = $study_names[$key];
         $log["country_code"] = $country_codes[$key];
         $log["region"] = $regions[$key];
         $log["teritory"] = $teritories[$key];
         $log["study_type"] = $study_types[$key];
         $log["study_datasource"] = $datasources[$key];
         $log["product"] = $products[$key];

         if (($count[7] < $max[7]) && ($count[28] < $max[28])) {
            if (($log["study_count"]==count($elements))) {
               if ($first) {
                  $log["delivery_status"] = "SENT";
                  SetProjectSatisfactionSurveySendSuccess($log["pjm_id"], "SENT", $log["contact_name"], $log["contact_email"]);
                  $logs[] = $cache;
               } else {
                  $log["delivery_status"] = "PROJECT";
               }
               $log["delivery_date"] = date("Y-m-d h:i:s");
               $studyDB->UpdateStudySatSurveyLog($log);
               SetStudySatisfactionSurveySendSuccess($study_id, $log["contact_name"], $log["contact_email"]);
            }
         } else {
            $log["delivery_status"] = "SKIP";
            $studyDB->UpdateStudySatSurveyLog($log);
            SetStudySatisfactionSurveySendError($study_id, "SKIP", "Skip sending satisfaction survey. ".$count[7][$log["account_id"]]." surveys last week and ".$count[28][$log["account_id"]]." surveys last month.");
            if ($first) {
               SetProjectSatisfactionSurveySendError($log["pjm_id"], "SKIP", "Skip sending satisfaction survey. ".$count[7][$log["account_id"]]." surveys sent in the last week and ".$count[28][$log["account_id"]]." surveys sent in the last month.");
            }
         }
         $first = false;
      }
   }

   $where = " AND pjm_id = 0 AND delivery_status IN ('PENDING')";
   $rst = $studyDB->GetStudySatSurveyLog($where);
   while ($log = mysql_fetch_assoc($rst)) {
      $count[7] = $studyDB->GetSatSurveyCounts($log["account_id"], 7);
      $count[28] = $studyDB->GetSatSurveyCounts($log["account_id"], 28);

      if (($count[7] < $max[7]) && ($count[28] < $max[28])) {
      	$log["delivery_status"] = "SENT";
      	$log["delivery_date"] = date("Y-m-d h:i:s");
         $studyDB->UpdateStudySatSurveyLog($log);
         SetStudySatisfactionSurveySendSuccess($log["study_id"], $log["contact_name"], $log["contact_email"]);
         $logs[] = $log;
   	} else {
      	$log["delivery_status"] = "SKIP";
      	$studyDB->UpdateStudySatSurveyLog($log);
      	SetStudySatisfactionSurveySendError($log["study_id"], "SKIP", "Skip sending satisfaction survey. ".$count[7][$log["account_id"]]." surveys sent in the last week and ".$count[28][$log["account_id"]]." surveys sent in the last month.");
   	}
   }
   //echo("<pre>".print_r($max, true).print_r($count, true).print_r($logs, true)."</pre>");
   if (count($logs)) {
      $smarty->assign("logs", $logs);
      header("Content-type: text/csv; charset=iso-8859-1; header=present;");
      header("Content-Disposition: attachment; filename=satisfaction_suvery_".date("Ymd").".csv");
      $smarty->display("app/pjm/vr_sat_survey_log.tpl");
   }

}


/**
* HBRPC_SetStudyRetainer()
*
* @param array $params
*
* $params['study_id']
* $params['retainer_amount']
*
* @return
* @throws
* @access
* @global
* @since
*/
function HBRPC_PJM_SetStudyRetainer($params)
{
   $study_id = @$params['study_id'];
   $retainer_amount = @$params['retainer_amount'];

   $study_db = new studyDB();
   $study_db->_study = $study_id;

   // make sure the study is valid
   if (!$study_db->onfile(array("study_id"=>$study_id))) {
      return array(
         "error_code" => 404,
         "error_message" => "Study [$study_id] is not found."
      );
   }
   $current_balance = $study_db->GetStudyAttribute($study_id, 'RETAINER', 0);
   $new_balance = $current_balance + $retainer_amount;
   $study_db->SetStudyAttrV2($study_id, ATTR_TYPE_DIGITS, 'RETAINER', $new_balance);

   return array('retainer_balance' => $new_balance, 'error_code'=>0);
}




/**
* HBRPC_UseStudyRetainer()
*
* @param array $params
*
* $params['study_id']
* $params['retainer_amount']
*
* @return
* @throws
* @access
* @global
* @since
*/
function HBRPC_PJM_UseStudyRetainer($params)
{
   $study_id = @$params['study_id'];
   $retainer_amount = @$params['retainer_amount'];

   $study_db = new studyDB();
   $study_db->_study = $study_id;

   // make sure the study is valid
   if (!$study_db->onfile(array("study_id"=>$study_id))) {
      return array(
         "error_code" => 404,
         "error_message" => "Study [$study_id] is not found."
      );
   }
   $current_balance = $study_db->GetStudyAttribute($study_id, 'RETAINER', 0);
   $new_balance = $current_balance - $retainer_amount;

   // caller wants to use more than available?
   if ($new_balance < 0){
      return array(
         "error_code" => 500,
         "error_message" => $current_balance,
         "retainer_balance" => $current_balance
      );
   }
   $study_db->SetStudyAttrV2($study_id, ATTR_TYPE_DIGITS, 'RETAINER', $new_balance);

   return array('retainer_balance'=>$new_balance, 'error_code'=>0);
}

/**
* __CalculatePriority()
*
* @param -
* @return -
* @author - Jon Vonica
* @since  - Tue Oct 24 14:33:48 PDT 2006
*/
function __CalculatePriority($study)
{
   //TODO add code
   $study["value"] = preg_replace("/[^0-9.]/", "", $study["value"]);
   $study["completes"] = preg_replace("/[^0-9.]/", "", $study["completes"]);
   $study["estimated_completed_timestamp"] = strtotime($study["current_estimated_complete_date"]);
   $study["hours_to_deadline"] = ($study["estimated_completed_timestamp"] - time()) / 3600;
   //echo ("<pre>".print_r($study, true)."</pre>");
   $score = 0;
   if ($study["completes"] == 0) {
      return -1;
   }

   $score += round($study["value"] / $study["completes"]);

   switch ($study["account_tier"]) {
      case 0 :
      case 4 : $score += 0; break;
      case 1 : $score += 6; break;
      case 2 : $score += 4; break;
      case 3 : $score += 2; break;
   }

   if ($study["hours_to_deadline"] > 48) {
      $score += 0;
   }elseif ($study["hours_to_deadline"] > 24) {
      $score += 2;
   }else{
      $score += 4;
   }
   return $score;
}

/**
* HBRPC_GetStudyPriority()
*
* @param -
* @return -
* @author - Jon Vonica
* @since  - Tue Oct 24 12:09:50 PDT 2006
*/
function HBRPC_PJM_GetStudyPriority($params)
{
   //TODO add code
      if (isset($params["study_id"])) {
         if (!is_array($params["study_id"])) {
            $params["study_id"] = array($params["study_id"]);
         }
      }else{
         $params["study_id"] = array();
      }
      //echo ("<pre>".print_r($params["study_id"], true)."</pre>");
      $stmDB = new db_stm();
      $studies = $stmDB->GetStudyDetailsForPriority($params["study_id"]);
      $scores = array();
      //echo ("<pre>".print_r($studies, true)."</pre>");
      foreach ($studies AS $study) {
         $hb_studies[] = $study["study_id"];
         $scores[] = array("study_number"=>$study["study_id"], "priority"=>__CalculatePriority($study));
      }
      $missing_studies = array_diff($params["study_id"], $hb_studies);
      foreach ($missing_studies as $study_id) {
      	$scores[] = array("study_number" => $study_id, "priority"=>-1);
      }
      $ret = array("error_code"=>0, "error_message"=>"OK", "scores"=>$scores);
      echo ("<pre>".print_r($ret, true)."</pre>");

      return $ret;


}


/**
* HBRPC_SetStudyInvoiceDate()
*
* @param array $params
*
* $params['invoice_date']
* $params['study_id']
*
* @return
* @throws
* @access
* @global
* @since
*/
function HBRPC_PJM_SetStudyInvoiceDate($params)
{
   $invoice_date = @$params['invoice_date'];
   $study_id =     @$params['study_id'];
   $armc_id = @$params['armc_id'];
	$close_study = @$params['close_study_when_invoiced'];
   
   $study_db = new studyDB();
   $study_db->_study = $study_id;

   // make sure the study is valid
   if (!$study_db->onfile(array("study_id"=>$study_id))) {
      return array(
         "error_code" => 404,
         "error_message" => "Study [$study_id] is not found."
      );
   }
   // make sure the date given is valid
   if (! (strtotime($invoice_date)>0)){   // note: some PHP version returns -1 and some returns FALSE for error
      return array(
         "error_code" => 404,
         "error_message" => "Invalid invoice date [$invoice_date]."
      );
   }

   // make sure the study has not been invoiced before
   if ($study_db->isInvoiced()){
      return array(
         "error_code" => 404,
         "error_message" => "Study [$study_id] is already invoiced."
      );
   }

   $study_db->SetStudyAttrV2($study_id, 'V', 'BR_REFERENCE', $armc_id);
   $study_db->SetInvoiceDate($study_id, $invoice_date);
   if( $close_study == '1')
   {
   	$study_db->setStatus(STATUS_CLOSED);
   	PrepareSatSurvey($study_id);
   }
   return array('error_code'=>0);
}

/**
* PrepareHTMLSpecialChars()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Jul 21 12:07:11 PDT 2006
*/
function PrepareHTMLSpecialChars($msg)
{
   $html_from = htmlspecialchars($msg['headers']['from']);
   $html_subject = htmlspecialchars($msg['headers']['subject']);

   $body = preg_replace("/<br[^>]*>/", " ", $msg['body']['text']);

   if (!$body) {
      $body = preg_replace("/[\n\r\s]+/", " ",$msg['body']['html']);
   }

   return array("from"=>$html_from, "subject"=>$html_subject, "body"=>$body);
}

/**
* SetStudyCommentFromEmail()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Jul 21 12:11:44 PDT 2006
*/
function SetStudyCommentFromEmail($comment_type_id, $params)
{
   $study_id = $params['meta'];
   $study = new studyDB();

   $html = PrepareHTMLSpecialChars($params['msg']);

   $message = "<div class=\"smtp_headers\">"
            . "<span class=\"smtp_header\">From:</span><span class=\"email_from\">".$html["from"]."</span><br>"
            . "<span class=\"smtp_header\">Subject:</span><span class=\"email_subject\">".$html["subject"]."</span>"
            . "</div>";
   $message .= $html["body"];

   $last_alert = $study->GetLastStudyAlert($study_id);

   // Add the email message as study comment
   $study->setNote(array(
      'study_id' => $study_id,
      'comment_type_id' => $comment_type_id,
      'notes' => $message,
      'alert_level_id' => $last_alert["alert_level_id"],
      'department_id' => $last_alert["department_id"]
      )
   );
   $study_comment_id = $study->lastID;

   // If there are email attachments, add them as comment files
   if (is_array($params['msg']['file'])){
      foreach ($params['msg']['file'] as $file){

         $file_content = base64_decode($file['data']);
         $study->SetCommentFile(
            $study_comment_id,
            0,                   // file type id
            $file['file_name'],
            strlen($file_content), // file size
            $file_content,       // file data
            $file['file_name'],  // file title
            SYSTEM_USER    // login
         );
      }
   }
   return array("error_code"=>0);

}


/**
* HBRPC_SetStudyEmailCommentFromEmail()
*
* @param array $params
*
* $params['meta'] - ID of the study which the email is belonged to
* $params['msg']['headers']['form']
* $params['msg']['headers']['subject']
* $params['msg']['body']['html']
* $params['msg']['body']['text']
* $params['msg']['file'][]['file_name']
* $params['msg']['file'][]['data']
*
* @return
* @throws
* @access
* @global
* @since
*/
function HBRPC_PJM_SetStudyEmailCommentFromEmail($params)
{
   SetStudyCommentFromEmail(COMMENT_FROM_EMAIL, $params);
}

/**
* HBRPC_SetStudyAlertFromEmail()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Jul 21 12:04:14 PDT 2006
*/
function HBRPC_PJM_SetStudyAlertFromEmail($params)
{
   SetStudyCommentFromEmail(COMMENT_ALERT_CHANGE, $params);
}





/**
* HBRPC_GetAttrValues()
*
* @param array $params
*
* $params['attr_name']
*
* @return
* @throws
* @access
* @global
* @since
*/
function HBRPC_PJM_GetAttrValues($params)
{
   $project_db = new projectDB();
   $study_db = new studyDB();
   $commonDB = new commonDB();
   switch ($params['attr_name']) {
   	case 'study_type_id':
         $study_type_lookup = $study_db->getStudyTypes();
         $study_types = array();
         foreach ($study_type_lookup as $study_type_id => $study_type_description){
            $study_types[] = array('study_type_id'=>$study_type_id, 'study_type_description'=>$study_type_description);
         }
         $data = array(
            'meta' => array('index'=>'study_type_id', 'value'=>'study_type_description'),
   	      'data' => $study_types,
            'error_code' => 0
         );
         break;

   	case 'department_id':
   	   $department_lookup = $study_db->GetDepartments();
   	   $departments = array();
   	   foreach ($department_lookup as $department_id => $department){
   	      $departments[] = array('department_id'=>$department_id, 'department'=>$department);
   	   }
   	   $data = array(
   	     'meta' => array('index'=>'department_id', 'value'=>'department'),
   	     'data' => $departments,
   	     'error_code' => 0
	     );
	     break;

   	case 'study_task_id':
         $data = array(
            'meta' => array('index'=>'study_task_id', 'value'=>'study_task_description'),
            'data' => $study_db->GetStudyTasks(),
            'error_code' => 0
         );
         break;


   	case 'alert_level_direction':
   	   $data = array(
   	     'meta' => array('index' => 'alert_level_direction',
   	                     'value' => 'alert_level_direction_description'
   	                     ),
   	     'data' => array( array('alert_level_direction'=>'-1', 'alert_level_direction_description'=>'Down' ),
   	                      array('alert_level_direction'=>'0',  'alert_level_direction_description'=>'Same' ),
   	                      array('alert_level_direction'=>'1',  'alert_level_direction_description'=>'Up')
   	                      ),
            'error_code' => 0
         );
         break;


   	case 'alert_level_id':
   	   $data = array(
   	      'meta' => array('index'=>'alert_level_id', 'value'=>'alert_level_description'),
            'data' => PrepareSmartyArray($project_db->GetAlertLevels()),
            'error_code' => 0
         );
   	   break;


   	case 'pjm_status_id':
   	   $project_db->GetProjectStatusLookup($status_lookup);
   	   $statuses = array();
   	   foreach ($status_lookup as $pjm_status_id => $pjm_status_description){
   	      $statuses[] = array('pjm_status_id'=>$pjm_status_id, 'pjm_status_description'=>$pjm_status_description);
   	   }
   	   $data = array(
            'meta' => array('index'=>'pjm_status_id', 'value'=>'pjm_status_description'),
            'data' => $statuses,
            'error_code' => 0
         );
         break;


   	case 'study_status_id':
   	   $data = array(
            'meta' => array('index'=>'study_status_id', 'value'=>'study_status_description'),
            'data' => PrepareSmartyArray($study_db->GetStudyStatus()),
            'error_code' => 0
         );
         break;

      case 'sample_type_id':{
         $data = array(
            'meta'=>array('index'=>'sample_type_id', 'value'=>'sample_type_description'),
            'data'=>PrepareSmartyArray($commonDB->GetSampleTypes()),
            'error_code'=>0);
      }break;

      //study_cost_approval_reason_id --need to return this value as well
      case 'study_cost_approval_reason_id':
      	
      	 $study_reason = Hb_App_Study_StudyCost::GetStudyCostApprovalReasons();
      	
   	   $data = array(
   	     'meta' => array('index' => 'study_cost_approval_reason_id',
   	                     'value' => 'study_cost_approval_reason'
   	                     ),
   	     'data' => array( array('study_cost_approval_reason_id'=>REASON_INVOICE_AMOUNT, 'study_cost_approval_reason'=>$study_reason[REASON_INVOICE_AMOUNT]),
   	                      array('study_cost_approval_reason_id'=>REASON_INVOICE_PERCENT,  'study_cost_approval_reason'=>$study_reason[REASON_INVOICE_PERCENT]),
   	                      array('study_cost_approval_reason_id'=>REASON_NOT_APPROVED,  'study_cost_approval_reason'=>$study_reason[REASON_NOT_APPROVED]),
   	                      array('study_cost_approval_reason_id'=>REASON_NOT_INVOICED,  'study_cost_approval_reason'=>$study_reason[REASON_NOT_INVOICED]),
   	                      array('study_cost_approval_reason_id'=>REASON_CLIENT_INVOICE_TOO_LOW,  'study_cost_approval_reason'=>$study_reason[REASON_CLIENT_INVOICE_TOO_LOW]),
   	                      array('study_cost_approval_reason_id'=>REASON_TOO_LATE,  'study_cost_approval_reason'=>$study_reason[REASON_TOO_LATE])
   	                      ),
            'error_code' => 0
         );
         break;
      
      
      case 'study_cost_type_id':
      {
      	
      	$study_cost_value = array();
      	
      	$study_cost_type_list = $study_db->GetStudyCostList();
     
      	foreach ($study_cost_type_list AS $study_cost_type)
      	{
      		$study_cost_type_values[] = array('study_cost_type_id'=>$study_cost_type['study_cost_type_id'],'study_cost_type_description'=>$study_cost_type['study_cost_type_description']);
      	}
     
         $data = array(
            'meta'=>array('index'=>'study_cost_type_id', 'value'=>'study_cost_type_description'),
            'data'=>$study_cost_type_values,
            'error_code'=>0);
      }break;
      	
      	
   	default:
   	   $data = array(
            'error_code' => 404,
            'error_message' => "Unknown attr_name [{$params['attr_name']}]"
         );
   		break;
   }

   return $data;
}







/**
* GetAccountDetailFromACM()
* Retrieve details of an account
*
* @param integer $account_id [in]
* @return
* @throws
* @access
* @global
* @since
*/
function GetAccountDetailFromACM($account_id)
{
   //@runkit_function_remove("HBRPC_GetPortlet");
   //@runkit_function_remove("HBRPC_GetAttrValues");

   $params = array('account_id'=>$account_id);
   $account_detail = HBRPCCall('acm', 'GetAccountDetail', $params);
   return $account_detail;
}

/**
 * Set the project status
 * 
 * @param pjm_id The id of the project to close
 * @param status_id The id of the new project status
 */
function SetProjectStatus($pjm_id, $status_id)
{
   global $smarty;
	$projectDB = new projectDB();
	$projectDB->GetProjectInfo(&$info, $pjm_id);
   if (!$info) {
      $smarty->display("record_not_found.tpl");
      return false;
	}
	
	$projectDB->SetProjectInfo($info["pjm_id"], $info["pjm_type_id"], $status_id, $info["pjm_description"], $info["pjm_start_date"], $info["pjm_end_date"]);
	
	header("Location: ?action=display_project_details&pjm_id=" . $info["pjm_id"]);
	return true;
}




/**
* QueueMessageThruCOM()
* Send out a message through COM module
*
* @param integer $message_type_id [in]
* @param array $rcpt [in]
* @param string $subject [in]
* @param string $body [in]
* @param array $attr [in]
* @return
* @throws
* @access
* @global
* @since
*/
function QueueMessageThruCOM($message_type_id, $rcpt, $subject, $body, $attr)
{

   //@runkit_function_remove("HBRPC_GetPortlet");
   //@runkit_function_remove("HBRPC_GetAttrValues");

   HBRPCCall('com', 'QueueMessage', array(
      "message_type_id" => $message_type_id,
      "rcpt" => $rcpt,
      "msg" => array(
         "subject" => $subject,
         "body" => $body
      ),
      "attr" => $attr
      )
   );
}

/**
* QueueMessageThroughCOMV2()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jul 19 09:48:44 PDT 2006
*/
function QueueMessageThroughCOMV2($message_params)
{
   //@runkit_function_remove("HBRPC_GetPortlet");
   //@runkit_function_remove("HBRPC_GetAttrValues");

   HBRPCCall('com', 'QueueMessage', $message_params);

}


?>
