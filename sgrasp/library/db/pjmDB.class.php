<?php
//since v0.5 of study manager we have changed our coding standards so we are capilizing all the first letters of a method name including the very first letter.
class projectDB extends dbConnect {

   protected $timezone;

   /**
   * projectDB()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function projectDB($timezone="+00:00")
   {
      $this->timezone = $timezone;
      parent::dbConnect();
   }


   /**
   * SetProjectInfo()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function SetProjectInfo($pjm_id, $pjm_type_id, $pjm_status_id, $pjm_description, $pjm_start_date, $pjm_end_date)
   {
      settype($pjm_id, "integer");
      settype($pjm_type_id, "integer");
      settype($pjm_status_id, "integer");
      $pjm_description = mysql_real_escape_string($pjm_description);
      $pjm_start_date = mysql_real_escape_string($pjm_start_date);
      $pjm_end_date = mysql_real_escape_string($pjm_end_date);

      $qry  = "UPDATE pjm "
            . "SET "
            . "  status='A', "
            . "  pjm_type_id=$pjm_type_id, "
            . "  pjm_status_id=$pjm_status_id, "
            . "  pjm_description='$pjm_description', "
            . "  pjm_start_date='$pjm_start_date', "
            . "  pjm_end_date='$pjm_end_date', "
            . "  modified_by={$this->created_by}, "
            . "  modified_date=NOW() "
            . "WHERE pjm_id=$pjm_id ";

      $this->executeQuery($qry);
   }



   function SetProjectAlertLevel($pjm_id, $alert_level_id)
   {
      settype($pjm_id, "integer");
      settype($alert_level_id, "integer");

      $qry  = "UPDATE pjm "
            . "SET alert_level_id=$alert_level_id "
            . "WHERE pjm_id=$pjm_id ";

      $this->executeQuery($qry);
   }



   /**
   * AddProject()
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   * @since Tchan 2006-02-10
   */
   function AddProject(&$pjm_id, $pjm_type_id, $pjm_status_id, $pjm_description, $pjm_start_date, $pjm_end_date, $alert_level_id)
   {
      // escape inputs to prevent SQL hijack
      settype($pjm_type_id, "integer");
      settype($alert_level_id, "integer");
      settype($pjm_status_id, "integer");
      $pjm_description = mysql_real_escape_string($pjm_description);
      $pjm_start_date = mysql_real_escape_string($pjm_start_date);
      $pjm_end_date = mysql_real_escape_string($pjm_end_date);

		$qry  = "INSERT INTO pjm (pjm_type_id, pjm_status_id, pjm_description, pjm_start_date, pjm_end_date, alert_level_id, created_by, created_date) "
            . "VALUES ($pjm_type_id, $pjm_status_id, '$pjm_description', '$pjm_start_date', '$pjm_end_date', $alert_level_id, {$this->created_by}, NOW()) ";
      $this->executeQuery($qry);
      $pjm_id = mysql_insert_id();
   }



   /**
   * AddElementToProject()
   * @param integer $pjm_id - ID of Project which the element will be added to
   * @param integer $pjm_element_type_id - ID Type of the element, i.e. Net-MR Study
   * @param integer $pjm_element_reference_id - Reference ID, i.e. Study ID
   * @param string $pjm_element_reference_description - Description of the element included to the project
   * @return integer - ID assigned to the new project-element
   * @throws
   * @access
   * @global
   * @since Tchan 2006-02-10
   */
   function AddElementToProject($pjm_element_id, $pjm_id, $pjm_element_type_id, $pjm_element_reference_id, $pjm_element_reference_description)
   {
      // escape inputs to prevent SQL hijack
      settype($pjm_id, "integer");
      settype($pjm_element_type_id, "integer");
      settype($pjm_element_reference_id, "integer");
      $pjm_element_reference_description = mysql_real_escape_string($pjm_element_reference_description);

      // Check whether project already has this element
      $qry  = "SELECT pjm_element_id FROM pjm_element WHERE pjm_id={$pjm_id} AND pjm_element_type_id={$pjm_element_type_id} AND pjm_element_reference_id={$pjm_element_reference_id} LIMIT 1";
      $rs = $this->executeQuery($qry);

      // If the element is already incluced in the project, update it
      if (mysql_num_rows($rs)){
         $pjm_element_id = mysql_result($rs, FIRST_RECORD);
   		$qry  = "UPDATE pjm_element "
   		      . "SET "
   		      . "  pjm_element_type_id = $pjm_element_type_id, "
   		      . "  pjm_element_reference_id = $pjm_element_reference_id, "
   		      . "  pjm_element_reference_description = '$pjm_element_reference_description', "
   		      . "  modified_by = {$this->created_by}, "
   		      . "  modified_date = NOW(), "
   		      . "  status = 'A' "
               . "WHERE pjm_element_id = $pjm_element_id ";
         $this->executeQuery($qry);

      // if not, add it to the project
      } else {
   		$qry  = "INSERT INTO pjm_element (pjm_id, pjm_element_type_id, pjm_element_reference_id, pjm_element_reference_description, created_by, created_date) "
               . "VALUES ($pjm_id, $pjm_element_type_id, $pjm_element_reference_id, '$pjm_element_reference_description', {$this->created_by}, NOW() )";
         $this->executeQuery($qry);
         $pjm_element_id = mysql_insert_id();
      }
   }



   /**
   * DeleteElementFromProject()
   * @param integer $pjm_element_id [in] ID of the row in pjm_element table to be deleted
   * @return
   * @throws
   * @access
   * @global
   */
   function DeleteElementFromProject($pjm_element_id)
   {
      settype($pjm_element_id, "integer");
      $qry  = "UPDATE pjm_element "
            . "SET status='D' "
            . "WHERE pjm_element_id = $pjm_element_id ";
      $this->executeQuery($qry);
   }





   /**
   * AddCommentToProject()
   * @param integer $pjm_id - Project ID
   * @param integer $alert_level_id - Alert level
   * @param integer $pjm_comment_type_id - Comment Type
   * @param string $pjm_comment_text - Comment
   * @return integer - ID assigned to the new comment
   * @throws
   * @access
   * @global
   */
   function AddCommentToProject(&$pjm_comment_id, $pjm_id, $alert_level_id, $pjm_comment_type_id, $pjm_comment_text)
   {
     // escape inputs to prevent SQL hijack
      settype($pjm_id, "integer");
      settype($alert_level_id, "integer");
      settype($pjm_comment_type_id, "integer");
      $pjm_comment_text = mysql_real_escape_string($pjm_comment_text);
		$qry  = "INSERT INTO pjm_comment (pjm_id, alert_level_id, pjm_comment_type_id, pjm_comment_text, created_by, created_date) "
            . "VALUES ($pjm_id, $alert_level_id, $pjm_comment_type_id, '$pjm_comment_text', {$this->created_by}, NOW()) ";
      $this->executeQuery($qry);
      $pjm_comment_id = mysql_insert_id();
   }

   /**
   * SetProjectCommentFile()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 18:47:06 PDT 2005
   */
   function SetProjectCommentFile($pjm_comment_id, $file_type_id, $file_name, $file_size, $data, $file_title, $login)
   {
      $pjm_comment_id = sprintf("%d", $pjm_comment_id);
      $file_size = strlen($data);   // note: this line must go before addslashes($data)
      $data = addslashes($data);
      $file_title = mysql_real_escape_string($file_title);
      $file_name = mysql_real_escape_string($file_name);
      $file_type_id = sprintf('%d', $file_type_id);
      $login = sprintf($login);

      $qry = "INSERT INTO pjm_comment_file (pjm_comment_id, file_type_id, pjm_comment_file_name, pjm_comment_file_title, pjm_comment_file_data, pjm_comment_file_size, created_by, created_date, status) "
           . "VALUES ($pjm_comment_id, $file_type_id, '$file_name', '$file_title', '$data', $file_size, $login, NOW(), 'A')";
      //echo ($qry);
      return $this->executeQuery($qry);
   }

   /**
   * GetProjectCommentFiles()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri May 12 14:36:37 PDT 2006
   */
   function GetProjectCommentFiles(&$files, $pjm_comment_id)
   {
      $q = "SELECT pjm_comment_file_id, pjm_comment_file_name, pjm_comment_file_size, pjm_comment_file_title "
      ." FROM pjm_comment_file WHERE pjm_comment_id='$pjm_comment_id' AND status='A'";
      $rst = $this->executeQuery($q);
      while ($f = mysql_fetch_assoc($rst)) {
         $files[$f["pjm_comment_file_id"]] = $f;
      }

   }


   /**
   * AddProjectContact()
   *
   * @param integer $pjm_contact_id [out] ID of the row inserted/updated
   * @param integer $pjm_id [in] ID of the project whose attribute to be set
   * @param integer $pjm_contact_type_id [in]
   * @param integer $contact_id [in]
   * @param string $first_name [in]
   * @param string $last_name [in]
   * @param string $phone [in]
   * @param string $email [in]
   * @return
   * @throws
   * @access
   * @global
   */
   function AddProjectContact($pjm_contact_id, $pjm_id, $pjm_contact_type_id, $contact_id, $first_name, $last_name, $phone, $email)
   {
      settype($pjm_id, "integer");
      settype($pjm_contact_type_id, "integer");
      settype($contact_id, "integer");
      $first_name = mysql_real_escape_string($first_name);
      $last_name = mysql_real_escape_string($last_name);
      $phone = mysql_real_escape_string($phone);
      $email = mysql_real_escape_string($email);
      $qry  = "REPLACE INTO pjm_contact "
            . "(pjm_id, pjm_contact_type_id, contact_id, first_name, last_name, phone, email, created_by, created_date, status) "
            . "VALUES ($pjm_id, $pjm_contact_type_id, $contact_id, '$first_name', '$last_name', '$phone', '$email', {$this->created_by}, NOW(), 'A') ";
      $rs = $this->executeQuery($qry);
   }

   /**
   * GetProjectAttributeDefs()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu May 11 17:59:00 PDT 2006
   */
   function GetProjectAttributeDefs()
   {
      $q = "SELECT pjm_attr_name, pjm_attr_description, pjm_attr_type FROM pjm_attr_def WHERE status='A'";
      return $this->executeQuery($q);
   }

   /**
   * SetProjectAttribute()
   *
   * @param integer $pjm_attr_id   - [out] ID of the row inserted/updated
   * @param integer $pjm_id        - [in] ID of the project whose attribute to be set
   * @param string $pjm_attr_name  - [in] Name of the attribute to be set
   * @param string $pjm_attr_value = [in] Value of the attribute
   * @return
   * @throws
   * @access
   * @global
   */
   function SetProjectAttribute(&$pjm_attr_id, $pjm_id, $pjm_attr_name, $pjm_attr_value, $pjm_attr_type='')
   {

      $pjm_attr_name = mysql_escape_string($pjm_attr_name);
      $pjm_attr_value = mysql_escape_string($pjm_attr_value);
      settype($pjm_id, "integer");
      settype($set_by, "integer");

      if ($pjm_attr_type=="M")
         $pjm_attr_table = "pjm_attr_memo";
      else
         $pjm_attr_table = "pjm_attr";

      // Check whether the attribute has been set previously or it is a new attribute for the study
      $qry = "SELECT ".$pjm_attr_table."_id FROM $pjm_attr_table WHERE pjm_id=$pjm_id AND pjm_attr_name='$pjm_attr_name' LIMIT 1";
      $rs = $this->executeQuery($qry);
      // Update the attribute if it has been setp previously
      if ($this->rows){
         $pjm_attr_id = mysql_result($rs, FIRST_RECORD);
         $qry = "UPDATE $pjm_attr_table "
              . " SET pjm_attr_value='$pjm_attr_value', modified_by={$this->created_by}, modified_date=NOW(), status='A' "
              . " WHERE ".$pjm_attr_table."_id = $pjm_attr_id ";
         $rs = $this->executeQuery($qry);

      // Insert a new attribute if it is new
      } else {
         $qry = "INSERT INTO $pjm_attr_table "
              . "(pjm_id, pjm_attr_name, pjm_attr_value, created_by, created_date) "
              . "VALUES ($pjm_id, '$pjm_attr_name', '$pjm_attr_value', {$this->created_by}, NOW()) ";
         $rs = $this->executeQuery($qry);
         $pjm_attr_id = mysql_insert_id();
      }
   }

   /**
   * DelProjectAttribute()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu May 11 18:14:16 PDT 2006
   */
   function DelProjectAttribute($pjm_id, $pjm_attr_name, $pjm_attr_type='')
   {
      $pjm_attr_name=mysql_real_escape_string($pjm_attr_name);
      settype($pjm_id, "integer");
      if ($pjm_attr_type=="M")
         $pjm_attr_table = "pjm_attr_memo";
      else
         $pjm_attr_table = "pjm_attr";
      $q = "UPDATE $pjm_attr_table SET status='D', modified_by='".$this->created_by."', modified_date=NOW() WHERE pjm_id='$pjm_id' AND pjm_attr_name='$pjm_attr_name'";
      $this->executeQuery($q);
   }

   /**
   * SetProjectFile($file_type_id, $study_file_type_id, $file_name, $file_size, $data)
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 18:47:06 PDT 2005
   */
   function SetProjectFile($pjm_id, $file_type_id, $pjm_file_type_id, $file_name, $file_size, $data, $file_title, $login)
   {
      $pjm_id = sprintf("%d", $pjm_id);
      $file_size = strlen($data);   // note: this line must go before addslashes($data)
      $data = addslashes($data);
      $file_title = mysql_real_escape_string($file_title);
      $file_name = mysql_real_escape_string($file_name);
      $file_type_id = sprintf('%d', $file_type_id);
      $pjm_file_type_id = sprintf('%d', $pjm_file_type_id);
      $login = sprintf($login);

      $qry = "INSERT INTO pjm_file (pjm_file_type_id, pjm_id, file_type_id, pjm_file_name, pjm_file_title, pjm_file_data, pjm_file_size, created_by, created_date, status) "
           . "VALUES ($pjm_file_type_id, $pjm_id, $file_type_id, '$file_name', '$file_title', '$data', $file_size, $login, NOW(), 'A')";

      return $this->executeQuery($qry);
   }

   /**
   * GetProjectFiles()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri May 12 12:40:57 PDT 2006
   */
   function GetProjectFiles(&$files, $pjm_id)
   {
      $q = "SELECT pjm_file_id, pjm_file_type_id, pjm_file_name, pjm_file_title, pjm_file_size FROM pjm_file WHERE pjm_id='$pjm_id'";
      $rst = $this->executeQuery($q);
      while ($f = mysql_fetch_assoc($rst)) {
         $files[$f["pjm_file_id"]] = $f;
      }

      return $files;
   }

   /**
   * GetProjectFileById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri May 12 12:52:36 PDT 2006
   */
   function GetProjectFileById($id)
   {
      $qry = "SELECT ft.file_type_description, pf.pjm_file_name, pf.pjm_file_title, pf.pjm_file_size, pf.pjm_file_data "
           . "FROM pjm_file AS pf "
           . "LEFT OUTER JOIN file_type AS ft ON ft.file_type_id = pf.file_type_id "
           . "WHERE pf.pjm_file_id = ".$id;

      return mysql_fetch_assoc($this->executeQuery($qry));

   }

   /**
   * GetProjectCommentFileById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri May 12 14:50:05 PDT 2006
   */
   function GetProjectCommentFileById($id)
   {
      $qry = "SELECT ft.file_type_description, pcf.pjm_comment_file_name, pcf.pjm_comment_file_title, pcf.pjm_comment_file_size, pcf.pjm_comment_file_data "
           . "FROM pjm_comment_file AS pcf "
           . "LEFT OUTER JOIN file_type AS ft ON ft.file_type_id = pcf.file_type_id "
           . "WHERE pcf.pjm_comment_file_id = ".$id;

      return mysql_fetch_assoc($this->executeQuery($qry));
   }

   /**
   * SetProjectUserRole()
   *
   * @param integer $pjm_user_id [out]
   * @param integer $pjm_id      [in] ID of the prject
   * @param integer $login       [in] ID of the person whose role is assigned
   * @param integer $role_id     [in] ID of the role assigned to
   * @return
   * @throws
   * @access
   * @global
   */
   function SetProjectUserRole(&$pjm_user_id, $pjm_id, $user_id, $role_id)
   {
      settype($pjm_id, "integer");
      settype($login, "integer");
      settype($role_id, "integer");

      // Check whether this user has assigned this role for the study
      $qry  = "SELECT pjm_user_id "
            . "FROM pjm_user "
            . "WHERE pjm_id={$pjm_id} AND user_id={$user_id} AND role_id={$role_id} "
            . "LIMIT 1 ";
      $rs = $this->executeQuery($qry);

      if (mysql_num_rows($rs)){
         $pjm_user_id = mysql_result($rs, FIRST_RECORD);
         $qry = "UPDATE pjm_user "
              . "SET status='A', role_id={$role_id}, modified_by={$this->created_by}, modified_date=NOW() "
              . "WHERE pjm_user_id=$pjm_user_id ";
         $rs = $this->executeQuery($qry);

      } else {
         // Insert a new attribute if it is new
         $qry = "REPLACE INTO pjm_user "
              . "(pjm_id, user_id, role_id, created_by, created_date, status ) "
              . "VALUES ($pjm_id, $user_id, $role_id, {$this->created_by}, NOW(), 'A') ";
         $rs = $this->executeQuery($qry);
         $pjm_user_id = mysql_insert_id();
      }
   }

   /**
   * DeleteProjectUserRoleByUserId()
   *
   * @param integer $pjm_id [in]
   * @param integer $user_id [in]
   * @return
   * @throws
   * @access
   * @global
   */
   function DeleteProjectUserRoleByUserId($pjm_id, $user_id)
   {
      settype($pjm_id, "integer");
      settype($user_id, "integer");
      $qry  = "UPDATE pjm_user "
            . "SET status='D', modified_by={$this->created_by}, modified_date=NOW() "
            . "WHERE pjm_id=$pjm_id AND user_id=$user_id ";
      return $this->executeQuery($qry);
   }



   /**
   * DeleteProjectUserRoleByRoleId()
   *
   * @param integer $pjm_id [in]
   * @param integer $role_id [in]
   * @return
   * @throws
   * @access
   * @global
   */
   function DeleteProjectUserRoleByRoleId($pjm_id, $role_id)
   {
      settype($pjm_id, "integer");
      settype($role_id, "integer");

      $qry  = "UPDATE pjm_user "
            . "SET status='D', modified_by={$this->created_by}, modified_date=NOW() "
            . "WHERE pjm_id=$pjm_id AND role_id=$role_id";
      return $this->executeQuery($qry);
   }



   /**
   * DeleteAllProjectUserRoles()
   *
   * @param integer $pjm_id [in]
   * @return
   * @throws
   * @access
   * @global
   */   function DeleteAllProjectUserRoles($pjm_id)
   {
      settype($pjm_id, "integer");
      $qry = "DELETE FROM pjm_user WHERE pjm_id=$pjm_id ";
      return $this->executeQuery($qry);
   }


   /**
   * DeleteAllProjectContacts()
   *
   * @param integer $pjm_id [in]
   * @return
   * @throws
   * @access
   * @global
   */
   function DeleteAllProjectContacts($pjm_id)
   {
      settype($pjm_id, "integer");
      $qry  = "DELETE FROM pjm_contact WHERE pjm_id=$pjm_id";
      return $this->executeQuery($qry);
   }



  /**
   * GetProjectStatusLookup
   *
   * @param array $lookup_table [out] project status lookup table (status-id => status-description)
   * @return
   * @throws
   * @access
   * @global
   */
   function GetProjectStatusLookup(&$lookup_table)
   {
      $lookup_table = array();
      $qry  = "SELECT pjm_status_id, pjm_status_description "
            . "FROM pjm_status "
            . "ORDER BY pjm_status_description ASC ";
      $rs = $this->executeQuery($qry);
      while ($r = mysql_fetch_assoc($rs)){
         $pjm_status_id = $r['pjm_status_id'];
         $lookup_table[$pjm_status_id] = $r['pjm_status_description'];
      }
   }


  /**
   * GetProjectContactTypeLookup
   *
   * @param array $lookup_table [out] project contact type lookup table (contact-type-id => contact-type-description)
   * @return
   * @throws
   * @access
   * @global
   */
   function GetProjectContactTypeLookup(&$lookup_table)
   {
      $lookup_table = array();
      $qry  = "SELECT pjm_contact_type_id, pjm_contact_type_description "
            . "FROM pjm_contact_type "
            . "ORDER BY pjm_contact_type_description ASC ";
      $rs = $this->executeQuery($qry);
      while ($r = mysql_fetch_assoc($rs)){
         $pjm_contact_type_id = $r['pjm_contact_type_id'];
         $lookup_table[$pjm_contact_type_id] = $r['pjm_contact_type_description'];
      }
   }



  /**
   * GetProjectsWithSatisfactionSurveyStatusIn
   *
   * Note: The logic in this function is slightly different than studyDb::GetStudiesWithSatisfactionSurveyStatusIn()
   *       because we have to include projects with NULL status.
   *
   * @param array $projects [out]
   * @param array $statuses [in]  use "" to include NULL status
   * @return
   * @throws
   * @access
   * @global
   */
   function GetProjectsWithSatisfactionSurveyStatusIn(&$projects, $statuses)
   {
      $extra_conditions = "";

      if (!is_array($statuses)){
         $statuses = array($statuses);
      }

      $include_null_status = false;
      $status_list = "";
      foreach ($statuses as $status){
         if ($status == ''){
            $include_null_status = true;
         }
         $status_list .= "'".mysql_real_escape_string($status)."',";
      }
      $status_list = preg_replace("/,$/", "", $status_list);   // remove last comma in the list

      if ($include_null_status){
         $extra_conditions .= " AND (pa_sat_survey_status.pjm_attr_value IS NULL OR pa_sat_survey_status.pjm_attr_value IN ($status_list))";
      } else {
         $extra_conditions .= " AND pa_sat_survey_status.pjm_attr_value IN ($status_list) ";
      }

      $qry  = "SELECT "
            . "  p.pjm_id, "
            . "  p.pjm_description, "
            . "  pa_account_name.pjm_attr_value AS account_name, "
            . "  pa_account_id.pjm_attr_value AS account_id, "
            . "  pa_sat_survey_status.pjm_attr_value AS sat_survey_status "
            . "FROM pjm AS p "
            . "  LEFT OUTER JOIN pjm_attr AS pa_account_name ON pa_account_name.pjm_attr_name='ACCOUNT_NAME' AND pa_account_name.pjm_id = p.pjm_id "
            . "  LEFT OUTER JOIN pjm_attr AS pa_account_id ON pa_account_id.pjm_attr_name='ACCOUNT_ID' AND pa_account_id.pjm_id = p.pjm_id "
            . "  LEFT OUTER JOIN pjm_attr AS pa_sat_survey_status ON pa_sat_survey_status.pjm_attr_name='SAT_SURVEY_STATUS' AND pa_sat_survey_status.pjm_id = p.pjm_id "
            . "WHERE p.status='A' $extra_conditions "
            . "GROUP BY p.pjm_id ";

      $projects = array();
      $rs = $this->executeQuery($qry);
      while($r = mysql_fetch_assoc($rs)) {
         $pjm_id = $r['pjm_id'];
         $projects[$pjm_id] = $r;
      }
   }


  /**
   * GetProjects
   *
   * @param array $projects [out]
   * @param integer $conditions [in]
   * @param integer $order_by [in] i.e "p.pjm_id ASC", do not insert "ORDER BY "prefix
   * @return
   * @throws
   * @access
   * @global
   */
   function GetProjects($conditions="", $order_by="", $limit="")
   {
      //$projects = array();
      $qry  = "SELECT "
            . "  p.pjm_id, "
            . "  p.pjm_type_id, "
            . "  p.pjm_description, "
            . "  p.alert_level_id, "
            . "  a.alert_level_description, "
            . "  DATE_FORMAT(p.pjm_start_date,'%Y-%m-%d') AS pjm_start_date, "
            . "  DATE_FORMAT(p.pjm_end_date,'%Y-%m-%d') AS pjm_end_date, "
            . "  UNIX_TIMESTAMP(p.pjm_start_date) AS pjm_start_timestamp, "
            . "  UNIX_TIMESTAMP(p.pjm_end_date) AS pjm_end_timestamp, "
            . "  COUNT(DISTINCT pe.pjm_element_id) AS total_elements, "
            . "  SUM(POW(20,s.alert_level_id)) AS elements_alert_score,"
            . "  pa_account_name.pjm_attr_value AS account_name, "
            . "  pa_account_id.pjm_attr_value AS account_id, "
            . "  p.pjm_status_id, "
            . "  ps.pjm_status_description, "
            . "  pu_am.user_id AS am_user_id, "
            . "  pu_ae.user_id AS ae_user_id, "
            . "  CONCAT(u_am.first_name,' ',u_am.last_name) AS am_name, "
            . "  CONCAT(u_ae.first_name,' ',u_ae.last_name) AS ae_name "
            . "FROM pjm AS p "
            . "  LEFT OUTER JOIN pjm_element AS pe ON pe.pjm_id=p.pjm_id AND pe.status='A' "
            . "  LEFT OUTER JOIN alert_level AS a ON a.alert_level_id=p.alert_level_id "
            . "  LEFT OUTER JOIN pjm_attr AS pa_account_name ON pa_account_name.pjm_attr_name='ACCOUNT_NAME' AND pa_account_name.pjm_id = p.pjm_id "
            . "  LEFT OUTER JOIN pjm_attr AS pa_account_id ON pa_account_id.pjm_attr_name='ACCOUNT_ID' AND pa_account_id.pjm_id = p.pjm_id "
            . "  LEFT OUTER JOIN pjm_status AS ps ON ps.pjm_status_id=p.pjm_status_id "
            . "  LEFT OUTER JOIN study AS s ON s.study_id=pe.pjm_element_reference_id AND pe.pjm_element_type_id=1 "
            . "  LEFT OUTER JOIN pjm_user AS pu ON pu.pjm_id = p.pjm_id "
            . "  LEFT OUTER JOIN pjm_user AS pu_am ON pu_am.pjm_id = p.pjm_id AND pu_am.role_id = ".PRIMARY_ACCT_MGR
            . "  LEFT OUTER JOIN pjm_user AS pu_ae ON pu_ae.pjm_id = p.pjm_id AND pu_ae.role_id = ".PRIMARY_ACCT_EXEC
            . "  LEFT OUTER JOIN user AS u_am ON u_am.login = pu_am.user_id "
            . "  LEFT OUTER JOIN user AS u_ae ON u_ae.login = pu_ae.user_id "
            . "WHERE p.status='A' $conditions "
            . "GROUP BY p.pjm_id ";

      if ($order_by){
         $qry .= "ORDER BY $order_by, p.pjm_description ASC $limit";
      } else {
         $qry .= "ORDER BY p.pjm_description ASC $limit";
      }
      $rs = $this->executeQuery($qry);
//      while($r = mysql_fetch_assoc($rs)) {
//         $pjm_id = $r['pjm_id'];
//         $projects[$pjm_id] = $r;
//      }
      return $rs;
   }

   /**
   * GetProjectsCount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed May 03 09:55:03 PDT 2006
   */
   function GetProjectsCount($conditions)
   {
      $qry  = "SELECT "
//            . "  p.pjm_id, "
//            . "  p.pjm_type_id, "
//            . "  p.pjm_description, "
//            . "  p.alert_level_id, "
//            . "  a.alert_level_description, "
//            . "  DATE_FORMAT(p.pjm_start_date,'%Y-%m-%d') AS pjm_start_date, "
//            . "  DATE_FORMAT(p.pjm_end_date,'%Y-%m-%d') AS pjm_end_date, "
//            . "  UNIX_TIMESTAMP(p.pjm_start_date) AS pjm_start_timestamp, "
//            . "  UNIX_TIMESTAMP(p.pjm_end_date) AS pjm_end_timestamp, "
//            . "  COUNT(DISTINCT pe.pjm_element_id) AS total_elements, "
//            . "  SUM(POW(20,s.alert_level_id)) AS elements_alert_score,"
//            . "  pa_account_name.pjm_attr_value AS account_name, "
//            . "  pa_account_id.pjm_attr_value AS account_id, "
//            . "  p.pjm_status_id, "
//            . "  ps.pjm_status_description, "
//            . "  pu_am.user_id AS am_user_id, "
//            . "  pu_ae.user_id AS ae_user_id, "
//            . "  CONCAT(u_am.first_name,' ',u_am.last_name) AS am_name, "
//            . "  CONCAT(u_ae.first_name,' ',u_ae.last_name) AS ae_name "
            . " COUNT(DISTINCT p.pjm_id) AS count "
            . "FROM pjm AS p "
            . "  LEFT OUTER JOIN pjm_element AS pe ON pe.pjm_id=p.pjm_id AND pe.status='A' "
            . "  LEFT OUTER JOIN alert_level AS a ON a.alert_level_id=p.alert_level_id "
            . "  LEFT OUTER JOIN pjm_attr AS pa_account_name ON pa_account_name.pjm_attr_name='ACCOUNT_NAME' AND pa_account_name.pjm_id = p.pjm_id "
            . "  LEFT OUTER JOIN pjm_attr AS pa_account_id ON pa_account_id.pjm_attr_name='ACCOUNT_ID' AND pa_account_id.pjm_id = p.pjm_id "
            . "  LEFT OUTER JOIN pjm_status AS ps ON ps.pjm_status_id=p.pjm_status_id "
            . "  LEFT OUTER JOIN study AS s ON s.study_id=pe.pjm_element_reference_id AND pe.pjm_element_type_id=1 "
            . "  LEFT OUTER JOIN pjm_user AS pu ON pu.pjm_id = p.pjm_id "
            . "  LEFT OUTER JOIN pjm_user AS pu_am ON pu_am.pjm_id = p.pjm_id AND pu_am.role_id = ".PRIMARY_ACCT_MGR
            . "  LEFT OUTER JOIN pjm_user AS pu_ae ON pu_ae.pjm_id = p.pjm_id AND pu_ae.role_id = ".PRIMARY_ACCT_EXEC
            . "  LEFT OUTER JOIN user AS u_am ON u_am.login = pu_am.user_id "
            . "  LEFT OUTER JOIN user AS u_ae ON u_ae.login = pu_ae.user_id "
            . "WHERE p.status='A' $conditions ";
//            . "GROUP BY p.pjm_id ";
      $rst = $this->executeQuery($qry);
      return ($this->rows?mysql_result($rst, 0, "count"):0);
   }


   /**
   * GetProjectInfo()
   *
   * @param array $info     [out] project info stored in associative array
   * @param integer $pjm_id [in] ID of the project whose info to be retrieved
   * @return
   * @throws
   * @access
   * @global
   */
   function GetProjectInfo(&$info, $pjm_id)
   {
      settype($pjm_id, "integer");

      $qry  = "SELECT p.pjm_id, "
            . "  p.pjm_type_id, "
            . "  p.alert_level_id, "
            . "  al.alert_level_description, "
            . "  p.pjm_description, "
            . "  p.pjm_status_id, "
            . "  ps.pjm_status_description, "
            . "  DATE_FORMAT(p.pjm_start_date,'%Y-%m-%d') AS pjm_start_date, "
            . "  DATE_FORMAT(p.pjm_end_date,'%Y-%m-%d') AS pjm_end_date, "
            . "  UNIX_TIMESTAMP(p.pjm_start_date) AS pjm_start_timestamp, "
            . "  UNIX_TIMESTAMP(p.pjm_end_date) AS pjm_end_timestamp, "
            . "  pa_account_name.pjm_attr_value AS account_name, "
            . "  pa_account_id.pjm_attr_value AS account_id, "
            . "  pu_acct_mgr.user_id AS acct_mgr_user_id,  "
            . "  pu_acct_exec.user_id AS acct_exec_user_id "
            . "FROM pjm AS p "
            . "  LEFT OUTER JOIN pjm_status AS ps ON ps.pjm_status_id=p.pjm_status_id "
            ."   LEFT OUTER JOIN alert_level AS al ON al.alert_level_id=p.alert_level_id "
            . "  LEFT OUTER JOIN pjm_attr AS pa_account_name ON pa_account_name.pjm_attr_name='ACCOUNT_NAME' AND pa_account_name.pjm_id=p.pjm_id "
            . "  LEFT OUTER JOIN pjm_attr AS pa_account_id ON pa_account_id.pjm_attr_name='ACCOUNT_ID' AND pa_account_id.pjm_id=p.pjm_id "
            . "  LEFT OUTER JOIN pjm_user AS pu_acct_mgr ON pu_acct_mgr.role_id=".PRIMARY_ACCT_MGR." AND pu_acct_mgr.pjm_id=p.pjm_id "
            . "  LEFT OUTER JOIN pjm_user AS pu_acct_exec ON pu_acct_exec.role_id=".PRIMARY_ACCT_EXEC." AND pu_acct_exec.pjm_id = p.pjm_id "
            . "WHERE p.status='A' AND p.pjm_id = $pjm_id "
            . "GROUP BY p.pjm_id ";

      $rs = $this->executeQuery($qry);
      $info = mysql_fetch_assoc($rs);
   }




   /**
   * GetProjectUserRoles()
   *
   * @param array $user_roles [out] data in associative array( pjm_user_id => user-role-info)
   *                                   note: the key is not user_id, but pjm_user_id
   * @param integer $pjm_id   [in] ID of the project whose user-roles to be retrieved
   * @return
   * @throws
   * @access
   * @global
   */
   function GetProjectUserRoles(&$user_roles, $pjm_id)
   {
      $user_roles = array();
      settype($pjm_id, "integer");

      $qry  = "SELECT "
            . "  pu.pjm_user_id, "
            . "  pu.user_id, "
            . "  pu.role_id, "
            . "  u.first_name, "
            . "  u.last_name, "
            . "  r.role_description "
            . "FROM pjm_user AS pu "
            . "  LEFT OUTER JOIN user AS u ON u.login=pu.user_id "
            . "  LEFT OUTER JOIN role AS r ON r.role_id=pu.role_id "
            . "WHERE pu.status='A' AND pu.pjm_id=$pjm_id "
            . "ORDER BY r.role_description ASC, u.first_name ASC, u.last_name ASC ";
      $rs = $this->executeQuery($qry);
      while($r = mysql_fetch_assoc($rs)) {
         $pjm_user_id = $r['pjm_user_id'];
         $user_roles[$pjm_user_id] = $r;
      }
   }


   /**
   * GetProjectUsersByRoleId()
   *
   * @param array $user_roles [out] users in associative array( user-id => user-role-info)
   * @param integer $pjm_id   [in] ID of the project whose user-roles to be retrieved
   * @param integer/array $role_ids [in] role ID or array of role IDs
   *
   * @return
   * @throws
   * @access
   * @global
   */
   function GetProjectUsersByRoleId(&$user_roles, $pjm_id, $role_ids)
   {
      $user_roles = array();
      settype($pjm_id, "integer");

      // build pu.role_id IN (...) condition
      if (!is_array($role_ids)){
         $role_ids = array($role_ids);
      }

      $roles = "";
      foreach ($role_ids as $role_id){
         $roles .= sprintf("%0d,", $role_id);
      }
      $roles = preg_replace("/,$/", "", $roles);   // remove last comma in the string

      $qry  = "SELECT "
            . "  pu.pjm_user_id, "
            . "  pu.user_id, "
            . "  pu.role_id, "
            . "  u.first_name, "
            . "  u.last_name, "
            . "  r.role_description "
            . "FROM pjm_user AS pu "
            . "  LEFT OUTER JOIN user AS u ON u.login=pu.user_id "
            . "  LEFT OUTER JOIN role AS r ON r.role_id=pu.role_id "
            . "WHERE pu.status='A' AND pu.pjm_id=$pjm_id AND pu.role_id IN ($roles) "
            . "ORDER BY u.first_name ASC, u.last_name ASC ";
      $rs = $this->executeQuery($qry);
      while($r = mysql_fetch_assoc($rs)) {
         $pjm_user_id = $r['pjm_user_id'];
         $user_roles[$pjm_user_id] = $r;
      }
   }


   /**
   * GetSatSurveyInfo()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - Thu Nov 30 09:53:30 PST 2006
   */
   function GetSatSurveyInfo($pjm_id)
   {
      $q = "SELECT "
         . "  p.pjm_id, "
         . "  p.pjm_description, "
         . "  pu_ae.user_id AS ae_login, "
         . "  CONCAT(u_ae.first_name, ' ', u_ae.last_name) AS ae_name, "
         . "  u_ae.email_address AS ae_email, "
         . "  pu_am.user_id AS am_login, "
         . "  CONCAT(u_am.first_name, ' ', u_am.last_name) AS am_name, "
         . "  u_am.email_address AS am_email, "
         . "  pu_fm.user_id AS fm_login, "
         . "  CONCAT(u_fm.first_name, ' ', u_fm.last_name) AS fm_name, "
         . "  u_fm.email_address AS fm_email, "
         . "  pu_cs.user_id AS cs_login, "
         . "  CONCAT(u_cs.first_name, ' ', u_cs.last_name) AS cs_name, "
         . "  ua_cs.user_value AS cs_title, "
         . "  u_cs.email_address AS cs_email "
//         . "  CONCAT(pc.first_name, ' ', pc.last_name) AS contact_name, "
//         . "  pc.email AS contact_email "
         . "FROM pjm AS p "
         . "LEFT JOIN pjm_user AS pu_ae ON pu_ae.pjm_id=p.pjm_id AND pu_ae.role_id=".ROLE_PRIMARY_ACCT_EXEC." AND pu_ae.status='A' "
         . "LEFT JOIN user AS u_ae ON u_ae.login = pu_ae.user_id "
         . "LEFT JOIN pjm_user AS pu_am ON pu_am.pjm_id=p.pjm_id AND pu_am.role_id=".ROLE_PRIMARY_ACCT_MGR." AND pu_am.status='A' "
         . "LEFT JOIN user AS u_am ON u_am.login = pu_am.user_id "
         . "LEFT JOIN pjm_user AS pu_fm ON pu_fm.pjm_id=p.pjm_id AND pu_fm.role_id=".ROLE_FULFILLMENT_MANAGER." AND pu_fm.status='A' "
         . "LEFT JOIN user AS u_fm ON u_fm.login = pu_fm.user_id "
         . "LEFT JOIN pjm_user AS pu_cs ON pu_cs.pjm_id=p.pjm_id AND pu_cs.role_id=".ROLE_CS_LEAD." AND pu_cs.status='A' "
         . "LEFT JOIN user AS u_cs ON u_cs.login = pu_cs.user_id "
         . "LEFT JOIN user_attr AS ua_cs ON ua_cs.login = u_cs.login AND ua_cs.user_attr='TITLE' "
//         . "LEFT JOIN pjm_contact AS pc ON pc.pjm_id=p.pjm_id AND pc.pjm_contact_type_id=".STUDY_CONTACT_PROJECT_MANAGER." AND pc.status='A' "
         . "WHERE p.pjm_id='$pjm_id' AND p.status='A' ";

      return mysql_fetch_assoc($this->executeQuery($q));
   }





/**
   * GetProjectAttributes
   *
   * @param array $attributes [out] attributes of the specified project stored in associative array
   * @param integer $pjm_id   [in] ID of the project whose info to be retrieved
   * @return
   * @throws
   * @access
   * @global
   */
   function GetProjectAttributes(&$attributes, $pjm_id)
   {
      $attributes = array();
      settype($pjm_id, "integer");

      $qry  = "SELECT pjm_attr_name, pjm_attr_value "
            . "FROM pjm_attr "
            . "WHERE status='A' AND pjm_id = $pjm_id ";
      $rs = $this->executeQuery($qry);
      while($r = mysql_fetch_assoc($rs)) {
         $pjm_attr_name = $r['pjm_attr_name'];
         $attributes[$pjm_attr_name] = $r['pjm_attr_value'];
      }

      $qry = "SELECT pjm_attr_name, pjm_attr_value "
         . "FROM pjm_attr_memo "
         . "WHERE status='A' AND pjm_id = '$pjm_id'";
      $rs = $this->executeQuery($qry);
      while ($r = mysql_fetch_assoc($rs)) {
          $attributes[$r["pjm_attr_name"]] = $r["pjm_attr_value"];
       }

   }



  /**
   * GetProjectsByElementID
   * Retreive a list of projects which contains a specific element(study)
   *
   * @param array $projects [out] a list of project ID
   * @param integer $pjm_id [in]
   * @return
   * @throws
   * @access
   * @global
   */
   function GetProjectsByElementID(&$projects, $pjm_element_type_id, $pjm_element_reference_id)
   {
      $projects = array();
      settype($pjm_element_type_id, "integer");
      settype($pjm_element_reference_id, "integer");

      $qry  = "SELECT p.pjm_id, p.pjm_type_id, p.pjm_status_id, p.alert_level_id, p.pjm_description  "
            . "FROM pjm_element AS pe "
            . "  LEFT OUTER JOIN pjm AS p ON pe.pjm_id = p.pjm_id "
            . "WHERE pe.status='A' AND pe.pjm_element_type_id=$pjm_element_type_id AND pe.pjm_element_reference_id=$pjm_element_reference_id ";
      $rs = $this->executeQuery($qry);
      while($r = mysql_fetch_assoc($rs)) {
         $pjm_id = $r['pjm_id'];
         $projects[$pjm_id] = $r;
      }
   }


  /**
   * GetProjectElements
   *
   * @param array $elements [out]
   * @param integer $pjm_id [in]
   * @return
   * @throws
   * @access
   * @global
   */
   function GetProjectElements(&$elements, $pjm_id)
   {
      $elements = array();
      settype($pjm_id, "integer");

      $qry  = "SELECT pe.pjm_element_id, pe.pjm_element_reference_id, pe.pjm_element_type_id, pet.pjm_element_type_description "
            . "FROM pjm_element AS pe "
            . "LEFT JOIN pjm_element_type AS pet ON pet.pjm_element_type_id=pe.pjm_element_type_id "
            . "WHERE pe.status='A' AND pe.pjm_id=$pjm_id ";
      $rs = $this->executeQuery($qry);
      while($r = mysql_fetch_assoc($rs)) {
         $pjm_element_reference_id = $r['pjm_element_reference_id'];
         $elements[$pjm_element_reference_id] = $r;
      }
   }

  /**
   * GetProjectComment
   *
   * @param array $comment [out]
   * @param integer $pjm_id [in]
   * @param integer $pjm_comment_id [in]
   * @return
   * @throws
   * @access
   * @global
   */
   function GetProjectComment(&$comment, $pjm_id, $pjm_comment_id)
   {
      $comment = array();
      settype($pjm_id, "integer");
      settype($pjm_comment_id, "integer");
      $qry  = "SELECT "
            . "  pc.pjm_comment_id, "
            . "  pc.alert_level_id, "
            . "  a.alert_level_description, "
            . "  pc.pjm_comment_type_id, "
            . "  pc.pjm_comment_text, "
            . "  pc.created_by, "
            . "  u_created_by.first_name AS created_by_first_name, "
            . "  u_created_by.last_name AS created_by_last_name, "
            . "  CONVERT_TZ(pc.created_date,'+00:00','{$this->timezone}') AS created_date, "
            . "  UNIX_TIMESTAMP(CONVERT_TZ(pc.created_date,'+00:00','{$this->timezone}')) AS created_timestamp "
            . "FROM pjm_comment AS pc "
            . "  LEFT OUTER JOIN alert_level AS a ON a.alert_level_id = pc.alert_level_id "
	         . "  LEFT OUTER JOIN user AS u_created_by ON u_created_by.login = pc.created_by "
            . "WHERE pc.status='A' AND pc.pjm_id=$pjm_id AND pc.pjm_comment_id=$pjm_comment_id";
      $rs = $this->executeQuery($qry);
      $comment = mysql_fetch_assoc($rs);
   }


  /**
   * GetPriorProjectCommentId
   *
   * @param integer $prior_pjm_comment_id [out]
   * @param integer $pjm_id [in]
   * @param integer $pjm_comment_id [in]
   * @return
   * @throws
   * @access
   * @global
   */
   function GetPriorProjectCommentId(&$prior_pjm_comment_id, $pjm_id, $pjm_comment_id)
   {
      $pjm_comment_id = sprintf('%d', $pjm_comment_id);
      $pjm_id = sprintf('%0d', $pjm_id);

      $qry  = "SELECT pc_prior.pjm_comment_id "
            . "FROM pjm_comment AS pc_prior "
            . "  JOIN pjm_comment AS pc ON pc.pjm_comment_id={$pjm_comment_id} AND pc.pjm_comment_type_id=pc_prior.pjm_comment_type_id AND pc_prior.pjm_id=pc.pjm_id "
            . "WHERE pc.pjm_id={$pjm_id} AND pc_prior.pjm_comment_id<{$pjm_comment_id} "
            . "ORDER BY pc_prior.pjm_comment_id DESC "
            . "LIMIT 1 ";
      $rs = $this->executeQuery($qry);
      $prior_pjm_comment_id = @mysql_result($rs, FIRST_RECORD);
   }



  /**
   * GetProjectComments
   *
   * @param array comments [out]
   * @param integer $pjm_id [in]
   * @return
   * @throws
   * @access
   * @global
   */
   function GetProjectComments(&$comments, $pjm_id)
   {
      $comments = array();
      settype($pjm_id, "integer");
      $qry  = "SELECT "
            . "  p.pjm_comment_id, "
            . "  p.alert_level_id, "
            . "  a.alert_level_description, "
            . "  p.pjm_comment_type_id, "
            . "  p.pjm_comment_text, "
            . "  p.created_by, "
            . "  u_created_by.first_name AS created_by_first_name, "
            . "  u_created_by.last_name AS created_by_last_name, "
            . "  CONVERT_TZ(p.created_date,'+00:00','{$this->timezone}') AS created_date, "
            . "  UNIX_TIMESTAMP(CONVERT_TZ(p.created_date,'+00:00','{$this->timezone}')) AS created_timestamp "
            . "FROM pjm_comment AS p "
            . "  LEFT OUTER JOIN alert_level AS a ON a.alert_level_id = p.alert_level_id "
	         . "  LEFT OUTER JOIN user AS u_created_by ON u_created_by.login = p.created_by "
            . "WHERE p.status='A' AND pjm_id = $pjm_id "
            . "ORDER BY p.pjm_comment_id ASC ";
      $rs = $this->executeQuery($qry);
      while($r = mysql_fetch_assoc($rs)) {
         $pjm_comment_id = $r['pjm_comment_id'];
         $comments[$pjm_comment_id] = $r;
      }
   }


  /**
   * GetProjectContacts
   *
   * @param array contacts [out] contacts in array
   * @param integer $pjm_id [in] ID of the project whose contacts to be retrieved
   * @return
   * @throws
   * @access
   * @global
   */
   function GetProjectContacts(&$contacts, $pjm_id)
   {
      $contacts = array();
      settype($pjm_id, "integer");
      $qry  = "SELECT "
            . "  pc.pjm_contact_id, "
            . "  pc.pjm_contact_type_id, "
            . "  pct.pjm_contact_type_description, "
            . "  pc.contact_id, "
            . "  pc.first_name, pc.last_name, "
            . "  pc.phone, "
            . "  pc.email "
            . "FROM pjm_contact AS pc "
            . "  LEFT OUTER JOIN pjm_contact_type AS pct ON pct.pjm_contact_type_id = pc.pjm_contact_type_id "
            . "WHERE pc.status='A' AND pc.pjm_id=$pjm_id "
            . "ORDER BY pc.first_name ASC, pc.middle_initial ASC, pc.last_name ASC ";
      $rs = $this->executeQuery($qry);
      while($r = mysql_fetch_assoc($rs)) {
         $pjm_contact_id = $r['pjm_contact_id'];
         $contacts[$pjm_contact_id] = $r;
      }
   }



   function GetProjectContactsByType(&$contacts, $pjm_id, $pjm_contact_type_ids)
   {
      $contacts = array();
      settype($pjm_id, "integer");

      // build pu.role_id IN (...) condition
      if (!is_array($pjm_contact_type_ids)){
         $pjm_contact_type_ids = array($pjm_contact_type_ids);
      }
      $contacts_types = "";
      foreach ($pjm_contact_type_ids as $pjm_contact_type_id){
         $contacts_types .= sprintf("%0d,", $pjm_contact_type_id);
      }
      $contacts_types = preg_replace("/,$/", "", $contacts_types);   // remove last comma in the string

      $qry  = "SELECT "
            . "  pc.pjm_contact_id, "
            . "  pc.pjm_contact_type_id, "
            . "  pct.pjm_contact_type_description, "
            . "  pc.contact_id, "
            . "  pc.first_name, pc.last_name, "
            . "  pc.phone, "
            . "  pc.email "
            . "FROM pjm_contact AS pc "
            . "  LEFT OUTER JOIN pjm_contact_type AS pct ON pct.pjm_contact_type_id = pc.pjm_contact_type_id "
            . "WHERE pc.status='A' AND pc.pjm_id=$pjm_id AND pc.pjm_contact_type_id IN ($contacts_types) "
            . "ORDER BY pc.first_name ASC, pc.middle_initial ASC, pc.last_name ASC ";
      $rs = $this->executeQuery($qry);
      while($r = mysql_fetch_assoc($rs)) {
         $pjm_contact_id = $r['pjm_contact_id'];
         $contacts[$pjm_contact_id] = $r;
      }
   }



  /**
   * GetLastProjectComment
   *
   * @param array $comments [out] comment/alert info in associative array
   * @param integer $pjm_id [in] ID of the project whose comment to be retrieved
   * @return
   * @throws
   * @access
   * @global
   */
   function GetLastProjectComment(&$comment, $pjm_id)
   {
      $comment = array();
      settype($pjm_id, "integer");

      $qry  = "SELECT "
            . "  p.pjm_comment_id, "
            . "  p.alert_level_id, "
            . "  a.alert_level_description, "
            . "  p.pjm_comment_type_id, "
            . "  p.pjm_comment_text, "
            . "  p.created_by, "
            . "  u_created_by.first_name AS created_by_first_name, "
            . "  u_created_by.last_name AS created_by_last_name, "
            . "  CONVERT_TZ(p.created_date,'+00:00','{$this->timezone}') AS created_date, "
            . "  UNIX_TIMESTAMP(CONVERT_TZ(p.created_date,'+00:00','{$this->timezone}')) AS created_timestamp "
            . "FROM pjm_comment AS p "
            . "  LEFT OUTER JOIN alert_level AS a ON a.alert_level_id = p.alert_level_id "
	         . "  LEFT OUTER JOIN user AS u_created_by ON u_created_by.login = p.created_by "
            . "WHERE p.status='A' AND pjm_id = $pjm_id "
            . "ORDER BY p.created_date DESC "
            . "LIMIT 1 ";

      $rs = $this->executeQuery($qry);
      $comment = mysql_fetch_assoc($rs);
   }




   /**
   * GetAlertLevels()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tchan
   */
   function GetAlertLevels()
   {
      $qry  =  "SELECT alert_level_id, alert_level_description, sort_order ";
      $qry .=  "FROM alert_level ";
      $qry .=  "ORDER BY sort_order ";
      return $this->executeQuery($qry);
   }



  /**
   * BuildSearchString()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function BuildSearchString($search_params)
   {
      $str = '';

      $tz = GetTimeZone($search_params);

      foreach ($search_params as $key => $val) {
         if( !preg_match("/^([^_]+)_(.+)$/",$key,$matches)){
            continue;
         }
         $prefix = $matches[1];
         $name = $matches[2];

         switch( $prefix ){
            case "sc":
               $field = $search_params["sf_{$name}"];
               $value = $search_params[$key];

               if ($value == ''){
                  break;
               }
               if (@$search_params["so_{$name}"] == 'WILDCARD') {
                  $str .= " AND $field LIKE '%".mysql_real_escape_string($value)."%' ";
               } elseif (@$search_params["so_{$name}"] == 'IN') {
                  $str .= " AND $field IN (".mysql_real_escape_string($value).") ";
               } else {
                  if (is_numeric($search_params[$key])){
                     $str .= " AND $field = $value ";
                  } else {
                     $str .= " AND $field = '".mysql_real_escape_string($value)."' ";
                  }
               }
               break;

            case "dtc":
               $field = $val;
               $begin = $search_params["{$name}_begin"];
               $end   = $search_params["{$name}_end"];
               if ($begin && $end) {
                  $str .= " AND $field BETWEEN CONVERT_TZ('$begin','$tz','+00:00') AND CONVERT_TZ('$end','$tz','+00:00') ";
               } elseif ($begin) {
               	$str .= " AND $field >= CONVERT_TZ('$begin','$tz','+00:00') ";
               } elseif($end) {
                  $str .= " AND $field <= CONVERT_TZ('$end','$tz','+00:00') ";
               }
               break;
         }
      }
      return $str;
   }



}
?>