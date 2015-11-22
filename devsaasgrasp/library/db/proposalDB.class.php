<?php

class proposalDB extends dbConnect {

   private $__id = 0;

   private $__revision    = 0;
   
   public $tz = '+00:00';


   /**
   * __construct()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 20 04:33:26 PDT 2005
   */
   function __construct()
   {
       parent::dbConnect();
   }

   /**
   * __destruct()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 20 04:34:01 PDT 2005
   */
   function __destruct()
   {
      //TODO add code

   }

   /**
   * GetProposalTypeList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 22 20:58:51 PDT 2005
   */
   function GetProposalTypeList()
   {
      $q = "SELECT proposal_type_id, proposal_type_description "
         . "FROM proposal_type "
         . "WHERE status = 'A'";
      return $this->executeQuery($q);
   }

   /**
   * GetProposalStatusList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 22 21:09:34 PDT 2005
   */
   function GetProposalStatusList()
   {
      $q = "SELECT proposal_status_id, proposal_status_description "
         . "FROM proposal_status "
         . "WHERE status = 'A'";
      return $this->executeQuery($q);
   }

//   /**
//   * SetProposal()
//   *
//   * @param
//   * @param - Code Replaced by V2.0 ACM Intergration Code See Below - NH
//   * @return
//   * @throws
//   * @access
//   * @global
//   * @since  - Sat Jul 23 10:45:45 PDT 2005
//   */
//   function SetProposal($name, $fg_id, $status_id, $region_id, $country_code, $date, $revision, $fg_login, $fg_user_id, $tz = '-08:00')
//   {
//      $q = "INSERT INTO proposal (proposal_name, functional_group_id, proposal_status_id, region_id, country_code, proposal_date, current_revision, login, user_id, created_by, created_date ) "
//         . "VALUES ('".mysql_real_escape_string($name)."', ".$fg_id.", ".$status_id.", ".$region_id.", '".$country_code."', CONVERT_TZ('".$date."','".$tz."','+0:00'), ".$revision.", ".$fg_login.", ". $fg_user_id .", ".$this->created_by.", NOW()) ";
//      return $this->executeQuery($q);
//
//   }
   
   /**
   * SetProposal()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 10 07:54:59 PST 2006
   */
   function SetProposal($proposal_name, $account_id, $account_name, $account_type_id, $license_level_id, $pricing_regime_id, $product_id, 
   								$functional_group_id, $proposal_status_id, $region_id, $country_code, $proposal_date, $current_revision, $login, $user_id, $version, $tz = '-08:00')
   {
   	$q = "INSERT INTO proposal (proposal_name, account_id, account_name, account_type_id, license_level_id, pricing_regime_id, product_id, functional_group_id, "
   	   . "          proposal_status_id, region_id, country_code, proposal_date, current_revision, login, user_id, version, created_by, created_date, status ) "
   	   . "VALUES ( '". mysql_real_escape_string($proposal_name) ."', ". $account_id .", '". mysql_real_escape_string($account_name) ."', ". $account_type_id .", "
   	   . "         ". $license_level_id .", ". $pricing_regime_id .", ". $product_id .", ". $functional_group_id .", ". $proposal_status_id .", ". $region_id .", "
   	   . "        '". $country_code ."', CONVERT_TZ('".$proposal_date."','".$tz."','+0:00'), ". $current_revision .", ". $login .", ". $user_id . ", ". $version .", ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }

   /**
   * SetAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 11:56:47 PDT 2005
   */
   function SetAttr($proposal_attr_name, $proposal_attr_value)
   {
      $q = "INSERT INTO proposal_attr (proposal_id, proposal_attr_name, proposal_attr_value, created_by, created_date, status) "
         . "VALUES (". $this->__id .", '". $proposal_attr_name ."', '". $proposal_attr_value ."', ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
   }
   
   /**
   * GetAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Dec 29 21:50:32 PST 2005
   */
   function GetAttr($proposal_attr_name)
   {
      $q = "SELECT proposal_attr_value "
         . "FROM proposal_attr "
         . "WHERE proposal_id = ". $this->__id ." AND proposal_attr_name = '". $proposal_attr_name ."' ";
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return ($r) ? $r['proposal_attr_value'] : false;
   }

   /**
   * SetRole()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 11:57:56 PDT 2005
   */
   function SetRole($role_id, $login)
   {
      $q = "INSERT INTO proposal_user (proposal_id, role_id, login, created_by, created_date) "
         . "VALUES (". $this->__id .", ". $role_id .", ". $login .", ". $this->created_by .", NOW()) ";
      return $this->executeQuery($q);
   }

   /**
   * SetRevision()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 14:03:13 PDT 2005
   */
   function SetRevision($id, $revision, $study_interview_type_id, $study_setup_duration_id, $study_fieldwork_duration_id,
                        $study_data_processing_duration_id, $proposal_option_type_id,
                        $proposal_type_id, $number_of_countries, $number_of_options, $login, $user_id, $pricing_type_id,
                        $revision_date = 'NOW()', $text = '')
   {
      $revision_date = ($revision_date != 'NOW()') ? "'".$revision_date."'" : $revision_date; //we need to add single quotes if we are not calling a mysql function

      $q = "INSERT INTO proposal_revision (proposal_id, revision, study_interview_type_id, study_setup_duration_id, study_fieldwork_duration_id, "
         . "            study_data_processing_duration_id, proposal_option_type_id, proposal_type_id, number_of_countries, number_of_options,  "
         . "            login, user_id, pricing_type_id, created_by, created_date, status) "
         . "VALUES (". $id .", ". $revision .", ". $study_interview_type_id . ", ". $study_setup_duration_id .", ". $study_fieldwork_duration_id .", "
         . "        ". $study_data_processing_duration_id .", ". $proposal_option_type_id .", "
         . "        ". $proposal_type_id .", ". $number_of_countries .", ". $number_of_options .", ". $login .", ". $user_id .", "
         . "        ". $pricing_type_id .", "
         . "        ". $this->created_by ."," . $revision_date .", 'A') ";

      return $this->executeQuery($q);
   }

   /**
   * GetRevision()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 19:09:35 PDT 2005
   */
   function GetRevisionNumber()
   {
      $q = "SELECT current_revision FROM proposal WHERE proposal_id = ".$this->__id;
      return mysql_result($this->executeQuery($q), 0, 'current_revision');
   }

   /**
   * SetRevision()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 19:10:34 PDT 2005
   */
   function SetRevisionNumber($revision)
   {
      $q = "UPDATE proposal SET current_revision = ".$revision.", modified_by = ".$this->created_by.", modified_date = NOW() "
         . "WHERE proposal_id = ".$this->__id;
      return $this->executeQuery($q);
   }

   /**
   * SetProposalId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 14:49:39 PDT 2005
   */
   function SetProposalId($id)
   {
      $this->__id = $id;
   }

   /**
   * SetRevisionId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 10 15:25:39 PST 2005
   */
   function SetRevisionId($revision_id)
   {
      $this->__revision = $revision_id;
   }

   /**
   * GetList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 14:57:59 PDT 2005
   */
   function GetList($filter = '', $page = 'LIMIT 0,50', $sort = 'p.created_date DESC')
   {
      $q = "SELECT p.proposal_id, p.proposal_name, p.account_id, p.account_name, c.country_description, "
         . "CONCAT(am.last_name,', ',am.first_name) AS am_name, pr.proposal_type_id, pr.pricing_type_id, "
         . "CONCAT(ae.last_name,', ',ae.first_name) AS ae_name, pt.proposal_type_description, prt.pricing_type_description, "
         . "CONCAT(fg_user.last_name,', ',fg_user.first_name) fg_user_name, st.sample_type_description, "
         . "am.login AS am_login, ae.login AS ae_login, pr.max_amount, pr.min_amount,  "
         . "fg.functional_group_description, p.proposal_date, p.current_revision, ps.proposal_status_description, "
         . "prs.proposal_revision_status_description, pc.first_name, pc.last_name "
         . "FROM proposal AS p "
         . "LEFT OUTER JOIN country AS c ON c.country_code = p.country_code "
         . "LEFT OUTER JOIN proposal_user AS pu_am ON pu_am.proposal_id = p.proposal_id AND pu_am.role_id = ".ROLE_PRIMARY_ACCT_MGR." "
         . "LEFT OUTER JOIN proposal_user AS pu_ae ON pu_ae.proposal_id = p.proposal_id AND pu_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC." "
         . "LEFT OUTER JOIN proposal_user AS pu_pw ON pu_pw.proposal_id = p.proposal_id AND pu_pw.role_id = ".ROLE_PROPOSAL_WRITER." "
         . "LEFT OUTER JOIN proposal_user AS pu_fa ON pu_fa.proposal_id = p.proposal_id AND pu_fa.role_id = ".ROLE_FEASIBILITY_ASSESSOR." "
         . "LEFT OUTER JOIN user AS am ON am.login = pu_am.login "
         . "LEFT OUTER JOIN user AS ae ON ae.login = pu_ae.login "
         . "LEFT OUTER JOIN functional_group AS fg ON fg.functional_group_id = p.functional_group_id "
         . "LEFT OUTER JOIN user AS fg_user ON fg_user.login = p.login "
         . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision AND pr.status = 'A' "
         . "LEFT OUTER JOIN proposal_status AS ps ON ps.proposal_status_id = p.proposal_status_id "
         . "LEFT OUTER JOIN proposal_revision_status AS prs ON prs.proposal_revision_status_id = pr.proposal_revision_status_id "
         . "LEFT OUTER JOIN proposal_revision_sample_type AS prst ON prst.proposal_revision_id = pr.proposal_revision_id "
         . "LEFT OUTER JOIN sample_type AS st ON st.sample_type_id = prst.sample_type_id "
         . "LEFT OUTER JOIN proposal_type AS pt ON pt.proposal_type_id = pr.proposal_type_id "
         . "LEFT OUTER JOIN pricing_type AS prt ON prt.pricing_type_id = pr.pricing_type_id "
         . "LEFT OUTER JOIN proposal_contact AS pc ON pc.proposal_id = p.proposal_id "
         . "WHERE p.status = 'A' "
         . $filter
         . " GROUP BY p.proposal_id "
         . " ORDER BY p.proposal_id DESC "
         . $page;
      return $this->executeQuery($q);
   }
   
   /**
   * GetListGroupedBy()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 24 16:45:21 PST 2006
   */
   function GetListGroupedBy($filter = '', $page = 'LIMIT 0,50', $sort = 'p.created_date DESC')
   {
   	$date = date("Y-m-d h:i:s");
   	
   	$q = "SELECT p.proposal_id, p.proposal_name, p.account_id, p.account_name, c.country_description, "
         . "CONCAT(am.last_name,', ',am.first_name) AS am_name, pr.proposal_type_id, pr.pricing_type_id, "
         . "CONCAT(ae.last_name,', ',ae.first_name) AS ae_name, pt.proposal_type_description, prt.pricing_type_description, "
         . "CONCAT(fg_user.last_name,', ',fg_user.first_name) fg_user_name, st.sample_type_description, "
         . "am.login AS am_login, ae.login AS ae_login, pr.max_amount, pr.min_amount,  "
         . "fg.functional_group_description, p.proposal_date, p.current_revision, ps.proposal_status_description, prs.proposal_revision_status_description, "
         . "DATEDIFF('". $date ."', pr.modified_date) AS age "
         . "FROM proposal AS p "
         . "LEFT OUTER JOIN country AS c ON c.country_code = p.country_code "
         . "LEFT OUTER JOIN proposal_user AS pu_am ON pu_am.proposal_id = p.proposal_id AND pu_am.role_id = ".ROLE_PRIMARY_ACCT_MGR." "
         . "LEFT OUTER JOIN proposal_user AS pu_ae ON pu_ae.proposal_id = p.proposal_id AND pu_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC." "
         . "LEFT OUTER JOIN proposal_user AS pu_pw ON pu_pw.proposal_id = p.proposal_id AND pu_pw.role_id = ".ROLE_PROPOSAL_WRITER." "
         . "LEFT OUTER JOIN proposal_user AS pu_fa ON pu_fa.proposal_id = p.proposal_id AND pu_fa.role_id = ".ROLE_FEASIBILITY_ASSESSOR." "
         . "LEFT OUTER JOIN user AS am ON am.login = pu_am.login "
         . "LEFT OUTER JOIN user AS ae ON ae.login = pu_ae.login "
         . "LEFT OUTER JOIN functional_group AS fg ON fg.functional_group_id = p.functional_group_id "
         . "LEFT OUTER JOIN user AS fg_user ON fg_user.login = p.login "
         . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision "
         . "LEFT OUTER JOIN proposal_status AS ps ON ps.proposal_status_id = p.proposal_status_id "
         . "LEFT OUTER JOIN proposal_revision_status AS prs ON prs.proposal_revision_status_id = pr.proposal_revision_status_id "
         . "LEFT OUTER JOIN proposal_revision_sample_type AS prst ON prst.proposal_revision_id = pr.proposal_revision_id "
         . "LEFT OUTER JOIN sample_type AS st ON st.sample_type_id = prst.sample_type_id "
         . "LEFT OUTER JOIN proposal_type AS pt ON pt.proposal_type_id = pr.proposal_type_id "
         . "LEFT OUTER JOIN pricing_type AS prt ON prt.pricing_type_id = pr.pricing_type_id "
         . "LEFT OUTER JOIN proposal_user AS pu ON pu.proposal_id = p.proposal_id "
         . "WHERE p.status = 'A' AND pr.status = 'A' "
         . $filter
         . " GROUP BY p.proposal_id "
         . " ORDER BY ".$sort." "
         . $page;
      return $this->executeQuery($q);
   }

   /**
   * GetBasicDetail()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 15:58:48 PDT 2005
   */
   function GetBasicDetail()
   {
       $q = "SELECT p.proposal_id, p.proposal_name, p.account_id, p.account_name, c.country_description, "
          . "CONCAT(am.first_name,' ',am.last_name) AS am_name, am.login AS am_login, r.region_description, p.region_id, "
          . "CONCAT(ae.first_name,' ',ae.last_name) AS ae_name, ae.login AS ae_login, pt.proposal_type_description, p.country_code, "
          . "CONCAT(fg_user.first_name,' ',fg_user.last_name) fg_user_name, p.functional_group_id, pr.proposal_type_id, pr.max_amount, pr.min_amount,  "
          . "p.proposal_status_id, fg_user.login AS fg_user_login, fg.functional_group_description, DATE_FORMAT(p.proposal_date, '%Y-%m-%d') AS proposal_date, pr.proposal_revision_id,  "
          . "p.current_revision, ps.proposal_status_description, p.account_type_id AS account_type, pr.number_of_countries, pr.number_of_options, pr.proposal_option_type_id, "
          . "ua_am_title.user_value AS am_title, ua_ae_title.user_value AS ae_title, cp_am.contact_phone_number AS am_phone, cp_ae_c.contact_phone_number AS ae_cell_phone, cp_ae.contact_phone_number AS ae_phone, "
          . "am.email_address AS am_email, ae.email_address AS ae_email, sit.study_interview_type_description, pc.first_name AS c_first_name, pc.last_name AS c_last_name, "
          . "pc.email AS c_email_address, p.pricing_regime_id, p.license_level_id, pr.proposal_revision_id, pr.study_interview_type_id, pr.study_setup_duration_id, "
          . "pr.study_data_processing_duration_id, pr.pricing_type_id, pr.proposal_revision_status_id, pr.revision, pc.contact_id AS c_contact_id, pr.study_fieldwork_duration_id, "
          . "u.last_name AS created_by_name, CONVERT_TZ(p.created_date,'+00:00','". $this->tz ."') AS created_date, ll.license_level_description, preg.pricing_regime_description, "
          . "pss.proposal_sub_status_description, p.version "
          . "FROM proposal AS p "
          . "LEFT OUTER JOIN proposal_attr AS a_type ON a_type.proposal_id = p.proposal_id AND a_type.proposal_attr_name = 'ACCOUNT_TYPE' "
          . "LEFT OUTER JOIN country AS c ON c.country_code = p.country_code "
          . "LEFT OUTER JOIN proposal_user AS pu_am ON pu_am.proposal_id = p.proposal_id AND pu_am.role_id = ".ROLE_PRIMARY_ACCT_MGR." "
          . "LEFT OUTER JOIN proposal_user AS pu_ae ON pu_ae.proposal_id = p.proposal_id AND pu_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC." "
          . "LEFT OUTER JOIN user AS am ON am.login = pu_am.login "
          . "LEFT OUTER JOIN user AS ae ON ae.login = pu_ae.login "
          . "LEFT OUTER JOIN functional_group AS fg ON fg.functional_group_id = p.functional_group_id "
          . "LEFT OUTER JOIN user AS fg_user ON fg_user.login = p.login "
          . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision "
          . "LEFT OUTER JOIN proposal_status AS ps ON ps.proposal_status_id = p.proposal_status_id "
          . "LEFT OUTER JOIN region AS r ON r.region_id = p.region_id "
          . "LEFT OUTER JOIN proposal_type AS pt ON pt.proposal_type_id = pr.proposal_type_id "
          . "LEFT OUTER JOIN user_attr AS ua_am_title ON ua_am_title.login = pu_am.login AND ua_am_title.user_attr = 'TITLE' "
          . "LEFT OUTER JOIN user_attr AS ua_ae_title ON ua_ae_title.login = pu_ae.login AND ua_ae_title.user_attr = 'TITLE' "
          . "LEFT OUTER JOIN user_contact AS uc_am ON uc_am.login = pu_am.login  "
          . "LEFT OUTER JOIN user_contact AS uc_ae ON uc_ae.login = pu_ae.login "
          . "LEFT OUTER JOIN contact AS c_am ON c_am.contact_id = uc_am.contact_id AND c_am.contact_type_id = 3 "
          . "LEFT OUTER JOIN contact AS c_ae ON c_ae.contact_id = uc_ae.contact_id AND c_ae.contact_type_id = 3 "
          . "LEFT OUTER JOIN contact_phone AS cp_am ON cp_am.contact_id = c_am.contact_id AND cp_am.phone_type_id = 2 "
          . "LEFT OUTER JOIN contact_phone AS cp_ae ON cp_ae.contact_id = c_ae.contact_id AND cp_ae.phone_type_id = 2 "
          . "LEFT OUTER JOIN contact_phone AS cp_ae_c ON cp_ae_c.contact_id = c_ae.contact_id AND cp_ae_c.phone_type_id = 3 "
          . "LEFT OUTER JOIN study_interview_type AS sit ON sit.study_interview_type_id = pr.study_interview_type_id "
          . "LEFT OUTER JOIN proposal_contact AS pc ON pc.proposal_id = p.proposal_id "
          . "LEFT OUTER JOIN user AS u ON u.login = p.created_by "
          . "LEFT OUTER JOIN license_level AS ll ON ll.license_level_id = p.license_level_id "
          . "LEFT OUTER JOIN pricing_regime AS preg ON preg.pricing_regime_id = p.pricing_regime_id "
          . "LEFT OUTER JOIN proposal_sub_status AS pss ON pss.proposal_sub_status_id = p.proposal_sub_status_id "
          . "WHERE p.proposal_id = ".$this->__id;
        
      return mysql_fetch_assoc($this->executeQuery($q));


   }

//   /**
//   * UpdateProposal()
//   *
//   * @param
//   * @param -
//   * @return
//   * @throws ****** NOTE ******* REPLACED BY V2.0 code
//   * @access
//   * @global
//   * @since  - Sat Jul 23 18:14:47 PDT 2005
//   */
//   function UpdateProposal($name, $group_id, $status_id, $region_id, $country_code, $login)
//   {
//      $q = "UPDATE proposal "
//         . "SET proposal_name = '". mysql_real_escape_string($name) ."', functional_group_id = ". $group_id .", "
//         . "    proposal_status_id = ". $status_id .", region_id = ". $region_id .", "
//         . "    country_code = '". $country_code ."', login = ". $login .", "
//         . "    modified_by = ". $this->created_by .", modified_date = NOW() "
//         . "WHERE proposal_id = ".$this->__id;
//
//      return $this->executeQuery($q);
//   }
   
   /**
   * UpdateProposal()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 10 08:45:31 PST 2006
   */
   function UpdateProposal($proposal_name, $account_id, $account_name, $account_type_id, $license_level_id, $pricing_regime_id, $product_id, 
   								$functional_group_id, $proposal_status_id, $region_id, $country_code, $proposal_date, $current_revision, $login, $user_id, $tz = '-08:00')
   {
   	$q = "UPDATE proposal "
         . "SET proposal_name = '". mysql_real_escape_string($proposal_name) ."', account_id = ". $account_id .", "
         . "    account_name = '". mysql_real_escape_string($account_name) ."', "
         . "    account_type_id = ". $account_type_id .", "
         . "    license_level_id = ". $license_level_id .", "
         . "    pricing_regime_id = ". $pricing_regime_id .", "
         . "    product_id = ". $product_id .", "
         . "    functional_group_id = ". $functional_group_id .", "
         . "    proposal_status_id = ". $proposal_status_id .", "
         . "    region_id = ". $region_id .", "
         . "    country_code = '". $country_code ."', "
         . "    proposal_date = '". $proposal_date ."', "
         . "    current_revision = ". $current_revision .", "
         . "    login = ". $login .", "
         . "    user_id = ". $user_id .", "
         . "    modified_by = ". $this->created_by .", modified_date = NOW() "
         . "WHERE proposal_id = ".$this->__id;
		return $this->executeQuery($q);
   }

   /**
   * UpdateAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 18:17:51 PDT 2005
   */
   function UpdateAttr($proposal_attr_name, $proposal_attr_value)
   {
      $q = "UPDATE proposal_attr "
         . "SET proposal_attr_value = '". $proposal_attr_value ."', modified_by = ". $this->created_by .", modified_date = NOW()"
         . "WHERE proposal_id = ". $this->__id ." AND proposal_attr_name = '". $proposal_attr_name ."' ";
      return $this->executeQuery($q);
   }

   /**
   * UpdateRole()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 18:19:04 PDT 2005
   */
   function UpdateRole($role_id, $login)
   {
      $q = "UPDATE proposal_user SET login = ".$login." "
         . "WHERE proposal_id = ".$this->__id." AND role_id = ".$role_id;

      return $this->executeQuery($q);

   }

   /**
   * GetRevisions()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 18:37:03 PDT 2005
   */
   function GetRevisions()
   {
      $q = "SELECT pr.proposal_revision_id, pr.proposal_id, pr.revision, pr.login, pr.proposal_revision_text, pr.created_date, "
         . "       CONCAT(u.first_name, ' ', u.last_name) AS p_writer_name, u.email_address AS p_writer_email, pr.sent_date,  "
         . "       prf.file_name, prf.proposal_revision_file_id, pr.min_amount, pr.max_amount, pr.proposal_revision_status_id, prs.proposal_revision_status_description "
         . "FROM proposal_revision AS pr "
         . "LEFT OUTER JOIN user AS u ON u.login = pr.login "
         . "LEFT OUTER JOIN proposal_revision_status AS prs ON prs.proposal_revision_status_id = pr.proposal_revision_status_id "
         . "LEFT OUTER JOIN proposal_revision_file AS prf ON prf.proposal_revision_id = pr.proposal_revision_id "
         . "WHERE pr.status = 'A' AND pr.proposal_id = ".$this->__id;
      return $this->executeQuery($q);
   }

   /**
   * GetSummaryListByAccount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 20:05:56 PDT 2005
   */
   function GetSummaryListByAccount($filter)
   {
      $q = "SELECT p.proposal_id, p.proposal_name, p.account_id, p.account_name, pu_ae.login AS ae_login, "
         . "       p.proposal_date, SUM(pr.min_amount) AS min_proposal_value, SUM(pr.max_amount) AS max_proposal_value, SUM(pv.proposal_attr_value) AS project_value,pu_am.login AS am_login, "
         . "       CONCAT(ae.first_name,' ',ae.last_name) AS ae_name, CONCAT(am.first_name,' ',am.last_name) AS am_name, "
         . "       SUM(IF( p.proposal_status_id = 1, 1, 0)) AS n_in_progress, "
         . "       SUM(IF( p.proposal_status_id = 2, 1, 0)) AS n_won, "
         . "       SUM(IF( p.proposal_status_id = 3, 1, 0)) AS n_lost, "
         . "       SUM(IF( p.proposal_status_id = 4, 1, 0)) AS n_cancelled, "
         . "       SUM(IF( p.proposal_status_id = 5, 1, 0)) AS n_postponed "
         . "FROM proposal AS p "
         . "LEFT OUTER JOIN proposal_attr AS pv ON pv.proposal_id = p.proposal_id AND pv.proposal_attr_name = 'PROJECT_VALUE' "
         . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision  "
         . "LEFT OUTER JOIN proposal_status AS ps ON ps.proposal_status_id = p.proposal_status_id "
         . "LEFT OUTER JOIN proposal_user AS pu_ae ON pu_ae.proposal_id = p.proposal_id AND pu_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC." "
         . "LEFT OUTER JOIN proposal_user AS pu_am ON pu_am.proposal_id = p.proposal_id AND pu_am.role_id = ".ROLE_PRIMARY_ACCT_MGR." "
         . "LEFT OUTER JOIN user AS ae ON ae.login = pu_ae.login "
         . "LEFT OUTER JOIN user AS am ON am.login = pu_am.login "
         . "WHERE p.status = 'A' ".$filter
         . "GROUP BY account_id "
         . "ORDER BY account_name ";
     return $this->executeQuery($q);
   }

   /**
   * GetSummaryListByAccountAndAm()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 20:05:56 PDT 2005
   */
   function GetSummaryListByAccountAndAe($filter)
   {
      $q = "SELECT p.proposal_id, p.proposal_name, p.account_id, p.account_name, pu_ae.login AS ae_login, "
         . "       p.proposal_date, SUM(pr.min_amount) AS min_proposal_value, SUM(pr.max_amount) AS max_proposal_value, SUM(pv.proposal_attr_value) AS project_value,pu_am.login AS am_login, "
         . "       CONCAT(ae.first_name,' ',ae.last_name) AS ae_name, CONCAT(am.first_name,' ',am.last_name) AS am_name, "
         . "       SUM(IF( p.proposal_status_id = 1, 1, 0)) AS n_in_progress, "
         . "       SUM(IF( p.proposal_status_id = 2, 1, 0)) AS n_won, "
         . "       SUM(IF( p.proposal_status_id = 3, 1, 0)) AS n_lost, "
         . "       SUM(IF( p.proposal_status_id = 4, 1, 0)) AS n_cancelled, "
         . "       SUM(IF( p.proposal_status_id = 5, 1, 0)) AS n_postponed "
         . "FROM proposal AS p "
         . "LEFT OUTER JOIN proposal_attr AS pv ON pv.proposal_id = p.proposal_id AND pv.proposal_attr_name = 'PROJECT_VALUE' "
         . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision  "
         . "LEFT OUTER JOIN proposal_status AS ps ON ps.proposal_status_id = p.proposal_status_id "
         . "LEFT OUTER JOIN proposal_user AS pu_ae ON pu_ae.proposal_id = p.proposal_id AND pu_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC." "
         . "LEFT OUTER JOIN proposal_user AS pu_am ON pu_am.proposal_id = p.proposal_id AND pu_am.role_id = ".ROLE_PRIMARY_ACCT_MGR." "
         . "LEFT OUTER JOIN user AS ae ON ae.login = pu_ae.login "
         . "LEFT OUTER JOIN user AS am ON am.login = pu_am.login "
         . "WHERE p.status = 'A' ".$filter
         . "GROUP BY account_id, ae_login "
         . "ORDER BY account_name ";
     return $this->executeQuery($q);
   }

   /**
   * GetSummaryListByAccountAndAm()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 20:05:56 PDT 2005
   */
   function GetSummaryListByAccountAndAm($filter)
   {
      $q = "SELECT p.proposal_id, p.proposal_name, p.account_id, p.account_name, pu_ae.login AS ae_login, "
         . "       p.proposal_date, SUM(pr.min_amount) AS min_proposal_value, SUM(pr.max_amount) AS max_proposal_value, SUM(pv.proposal_attr_value) AS project_value,pu_am.login AS am_login, "
         . "       CONCAT(ae.first_name,' ',ae.last_name) AS ae_name, CONCAT(am.first_name,' ',am.last_name) AS am_name, "
         . "       SUM(IF( p.proposal_status_id = 1, 1, 0)) AS n_in_progress, "
         . "       SUM(IF( p.proposal_status_id = 2, 1, 0)) AS n_won, "
         . "       SUM(IF( p.proposal_status_id = 3, 1, 0)) AS n_lost, "
         . "       SUM(IF( p.proposal_status_id = 4, 1, 0)) AS n_cancelled, "
         . "       SUM(IF( p.proposal_status_id = 5, 1, 0)) AS n_postponed "
         . "FROM proposal AS p "
         . "LEFT OUTER JOIN proposal_attr AS pv ON pv.proposal_id = p.proposal_id AND pv.proposal_attr_name = 'PROJECT_VALUE' "
         . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision  "
         . "LEFT OUTER JOIN proposal_status AS ps ON ps.proposal_status_id = p.proposal_status_id "
         . "LEFT OUTER JOIN proposal_user AS pu_ae ON pu_ae.proposal_id = p.proposal_id AND pu_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC." "
         . "LEFT OUTER JOIN proposal_user AS pu_am ON pu_am.proposal_id = p.proposal_id AND pu_am.role_id = ".ROLE_PRIMARY_ACCT_MGR." "
         . "LEFT OUTER JOIN user AS ae ON ae.login = pu_ae.login "
         . "LEFT OUTER JOIN user AS am ON am.login = pu_am.login "
         . "WHERE p.status = 'A' ".$filter
         . "GROUP BY account_id, am_login "
         . "ORDER BY account_name ";
     return $this->executeQuery($q);
   }

   /**
   * GetStatusListByAccount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 20:08:22 PDT 2005
   */
   function GetStatusListByAccount($filter)
   {
      $q = "SELECT p.proposal_id, p.proposal_name, p.account_id, p.account_name, "
         . "       p.proposal_date, pr.min_amount, pr.max_amount, p.proposal_status_id, pv.proposal_attr_value AS project_value "
         . "FROM proposal AS p "
         . "LEFT OUTER JOIN proposal_attr AS pv ON pv.proposal_id = p.proposal_id AND pv.proposal_attr_name = 'PROJECT_VALUE' "
         . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision  "
         . "LEFT OUTER JOIN proposal_status AS ps ON ps.proposal_status_id = p.proposal_status_id "
         . "WHERE p.status = 'A' ".$filter
         . "ORDER BY account_name ";

     return $this->executeQuery($q);

   }

   /**
   * GetStatusListByRegion()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 20:08:22 PDT 2005
   */
   function GetStatusListByCountry($filter)
   {
      $q = "SELECT p.proposal_id, c.country_description, p.proposal_date, SUM(pr.min_amount) AS min_proposal_value, SUM(pr.max_amount) AS max_proposal_value, p.proposal_status_id, SUM(pv.proposal_attr_value) AS project_value, p.region_id, "
         . "       SUM(IF( p.proposal_status_id = 1, 1, 0)) AS n_in_progress, "
         . "       SUM(IF( p.proposal_status_id = 2, 1, 0)) AS n_won, "
         . "       SUM(IF( p.proposal_status_id = 3, 1, 0)) AS n_lost, "
         . "       SUM(IF( p.proposal_status_id = 4, 1, 0)) AS n_cancelled, "
         . "       SUM(IF( p.proposal_status_id = 5, 1, 0)) AS n_postponed "
         . "FROM proposal AS p "
         . "LEFT OUTER JOIN proposal_attr AS pv ON pv.proposal_id = p.proposal_id AND pv.proposal_attr_name = 'PROJECT_VALUE' "
         . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision  "
         . "LEFT OUTER JOIN proposal_status AS ps ON ps.proposal_status_id = p.proposal_status_id "
         . "LEFT OUTER JOIN country AS c ON c.country_code = p.country_code "
         . "WHERE p.status = 'A' ".$filter
         . "GROUP BY c.country_id "
         . "ORDER BY country_description ";

     return $this->executeQuery($q);

   }

   /**
   * GetSummaryListByRegion()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 20:05:56 PDT 2005
   */
   function GetSummaryListByRegion($filter)
   {
      $q = "SELECT p.proposal_id, r.region_description, p.region_id,  "
         . "       p.proposal_date, SUM(pr.min_amount) AS min_proposal_value, SUM(pr.max_amount) AS max_proposal_value, SUM(pv.proposal_attr_value) AS project_value, "
         . "       COUNT(IF( p.proposal_status_id = 1, 1, NULL)) AS n_in_progress, "
         . "       COUNT(IF( p.proposal_status_id = 2, 1, NULL)) AS n_won, "
         . "       COUNT(IF( p.proposal_status_id = 3, 1, NULL)) AS n_lost, "
         . "       COUNT(IF( p.proposal_status_id = 4, 1, NULL)) AS n_cancelled, "
         . "       COUNT(IF( p.proposal_status_id = 5, 1, NULL)) AS n_postponed "
         . "FROM proposal AS p "
         . "LEFT OUTER JOIN proposal_attr AS pv ON pv.proposal_id = p.proposal_id AND pv.proposal_attr_name = 'PROJECT_VALUE' "
         . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision  "
         . "LEFT OUTER JOIN proposal_status AS ps ON ps.proposal_status_id = p.proposal_status_id "
         . "LEFT OUTER JOIN region AS r ON r.region_id = p.region_id "
         . "WHERE p.status = 'A' ".$filter
         . "GROUP BY p.region_id "
         . "ORDER BY r.region_description ";
    //print $q;

     return $this->executeQuery($q);
   }

   /**
   * GetSummaryListByAe()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 23:25:57 PDT 2005
   */
   function GetSummaryListByAe($filter)
   {

      $q = "SELECT p.proposal_id, p.proposal_name, p.account_id, p.account_name, pu_ae.login AS ae_login, "
         . "       p.proposal_date, SUM(pr.min_amount) AS min_proposal_value, SUM(pr.max_amount) AS max_proposal_value, SUM(pv.proposal_attr_value) AS project_value,pu_am.login AS am_login, "
         . "       CONCAT(u.first_name,' ',u.last_name) AS ae_name, "
         . "       SUM(IF( p.proposal_status_id = 1, 1, 0)) AS n_in_progress, "
         . "       SUM(IF( p.proposal_status_id = 2, 1, 0)) AS n_won, "
         . "       SUM(IF( p.proposal_status_id = 3, 1, 0)) AS n_lost, "
         . "       SUM(IF( p.proposal_status_id = 4, 1, 0)) AS n_cancelled, "
         . "       SUM(IF( p.proposal_status_id = 5, 1, 0)) AS n_postponed "
         . "FROM proposal AS p "
         . "LEFT OUTER JOIN proposal_attr AS pv ON pv.proposal_id = p.proposal_id AND pv.proposal_attr_name = 'PROJECT_VALUE' "
         . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision  "
         . "LEFT OUTER JOIN proposal_status AS ps ON ps.proposal_status_id = p.proposal_status_id "
         . "LEFT OUTER JOIN proposal_user AS pu_ae ON pu_ae.proposal_id = p.proposal_id AND pu_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC." "
         . "LEFT OUTER JOIN proposal_user AS pu_am ON pu_am.proposal_id = p.proposal_id AND pu_am.role_id = ".ROLE_PRIMARY_ACCT_MGR." "
         . "LEFT OUTER JOIN user AS u ON u.login = pu_ae.login "
         . "WHERE p.status = 'A' ".$filter
         . "GROUP BY ae_login "
         . "ORDER BY ae_name, account_name ";

     return $this->executeQuery($q);
   }

      /**
   * GetSummaryListByAm()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jul 23 23:25:57 PDT 2005
   */
   function GetSummaryListByAm($filter)
   {

      $q = "SELECT p.proposal_id, p.proposal_name, p.account_id, p.account_name, pu_ae.login AS ae_login, "
         . "       p.proposal_date, SUM(pr.min_amount) AS min_proposal_value,SUM(pr.max_amount) AS max_proposal_value, SUM(pv.proposal_attr_value) AS project_value,pu_am.login AS am_login, "
         . "       CONCAT(u.first_name,' ', u.last_name) AS am_name, "
         . "       SUM(IF( p.proposal_status_id = 1, 1, 0)) AS n_in_progress, "
         . "       SUM(IF( p.proposal_status_id = 2, 1, 0)) AS n_won, "
         . "       SUM(IF( p.proposal_status_id = 3, 1, 0)) AS n_lost, "
         . "       SUM(IF( p.proposal_status_id = 4, 1, 0)) AS n_cancelled, "
         . "       SUM(IF( p.proposal_status_id = 5, 1, 0)) AS n_postponed "
         . "FROM proposal AS p "
         . "LEFT OUTER JOIN proposal_attr AS pv ON pv.proposal_id = p.proposal_id AND pv.proposal_attr_name = 'PROJECT_VALUE' "
         . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision  "
         . "LEFT OUTER JOIN proposal_status AS ps ON ps.proposal_status_id = p.proposal_status_id "
         . "LEFT OUTER JOIN proposal_user AS pu_ae ON pu_ae.proposal_id = p.proposal_id AND pu_ae.role_id = ".ROLE_PRIMARY_ACCT_EXEC." "
         . "LEFT OUTER JOIN proposal_user AS pu_am ON pu_am.proposal_id = p.proposal_id AND pu_am.role_id = ".ROLE_PRIMARY_ACCT_MGR." "
         . "LEFT OUTER JOIN user AS u ON u.login = pu_am.login "
         . "WHERE p.status = 'A' ".$filter
         . "GROUP BY am_login "
         . "ORDER BY am_name, account_name ";

     return $this->executeQuery($q);
   }

   /**
   * GetComments()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jul 24 12:14:34 PDT 2005
   */
   function GetComments()
   {
      $q = "SELECT pc.proposal_comment_id, pc.proposal_comment_text, pc.proposal_comment_date, pc.login, "
         . "       CONCAT(u.first_name,' ',u.last_name) AS name, ps.proposal_status_description "
         . "FROM proposal_comment AS pc "
         . "LEFT OUTER JOIN user AS u ON u.login = pc.login "
         . "LEFT OUTER JOIN proposal_status AS ps ON ps.proposal_status_id = pc.proposal_status_id "
         . "WHERE pc.proposal_id = ".$this->__id;

      return $this->executeQuery($q);
   }

   /**
   * SetComment()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jul 24 12:19:07 PDT 2005
   */
   function SetComment($text, $proposal_status_id, $login, $date = 'NOW()')
   {
      $date = ($date != 'NOW()') ? "'".$date."'" : $date;
      $q = "INSERT INTO proposal_comment ( proposal_id, proposal_status_id, proposal_comment_text, proposal_comment_date, login, created_by, created_date) "
         . "VALUES (".$this->__id.", ". $proposal_status_id .", '".mysql_real_escape_string($text)."', ".$date.", ".$login.", ".$this->created_by.", NOW()) ";
      return $this->executeQuery($q);

   }

   /**
   * SetRevisionFile()
   *
   * @param - int $revision_id
   * @param - int $file_type_id
   * @param - string $file_name
   * @param - blob $file_data
   * @param - int $file_size
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Aug 01 23:16:43 PDT 2005
   */
   function SetRevisionFile($revision_id, $proposal_file_type_id,  $file_type_id, $file_name, $file_data, $file_size)
   {
      $q = "INSERT INTO proposal_revision_file ( proposal_revision_id, proposal_file_type_id, file_type_id, file_name, file_data, file_size, created_by, created_date, status ) "
         . "VALUES (". $revision_id. ", ". $proposal_file_type_id .", ". $file_type_id .", '". mysql_real_escape_string($file_name) ."', '"
         . "        ". mysql_real_escape_string($file_data) ."', ". $file_size .", ". $this->created_by .", NOW(), 'A') ";
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
            } elseif (isset($o['so_'.$so]) && $o['so_'.$so] == 'GT' && $o[$key] != '') {
               $str .= " AND ".$sc." >= '".mysql_real_escape_string($o[$key])."' ";
				} elseif (is_array($o[$key])) {
					$str .= " AND ".$sc." IN (". implode(",", $o[$key]) .") ";
            } elseif ($o[$key] != '') {
               $str .= " AND ".$sc." = '".mysql_real_escape_string($o[$key])."' ";
            }
         } elseif (preg_match("/^dtc_/",$key)) {
            $sc    = $o[$key];

            $sf = preg_replace("/^dtc_/","",$key);

            $begin = $o[$sf."_begin"];
            $end   = $o[$sf."_end"];
            
            if (isset($o['ignore_'. $sc]))
            	continue;
            
            if ($begin != '' && $end != '') {
	            if (isset($o[$sf."_tz"]) && $o[$sf."_tz"] == 0) {
	            	$str .= " AND ". $sc ." BETWEEN '".$begin." 00:00:00' AND '".$end." 23:59:59' ";	
	            } else {
	            	$str .= " AND CONVERT_TZ(". $sc .", '+00:00','". $tz ."') BETWEEN '".$begin." 00:00:00' AND '".$end." 23:59:59' ";	
	            	//$str .= " AND CONVERT_TZ(". $sc .",'". $tz ."', '+00:00') BETWEEN '".$begin." 00:00:00' AND '".$end." 23:59:59' ";	
	            }
            }
         }
      }
      return $str;
   }

   /**
   * GetRevisionFileById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Aug 02 10:21:05 PDT 2005
   */
   function GetRevisionFileById($id)
   {
      $q = "SELECT prf.file_name, prf.file_data, prf.file_size, prf.file_type_id "
         . "FROM proposal_revision_file AS prf "
         . "LEFT OUTER JOIN file_type AS ft ON ft.file_type_id = prf.file_type_id "
         . "WHERE prf.proposal_revision_file_id = ".$id;
      return mysql_fetch_assoc($this->executeQuery($q));
   }

   /**
   * GetProposalOptionTypes()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Oct 18 20:11:24 PDT 2005
   */
   function GetProposalOptionTypes()
   {
      $q = "SELECT proposal_option_type_id, proposal_option_type_description "
         . "FROM proposal_option_type "
         . "WHERE status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * SetRevisionComment()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Oct 19 12:00:49 PDT 2005
   */
   function SetRevisionComment($revision_id, $comment_type_id, $comment, $created_by = 0)
   {
   	$created_by = ($created_by == 0) ? $this->created_by : $created_by;
   	
      $q = "INSERT INTO proposal_revision_comment (proposal_revision_id, proposal_revision_comment_type_id, comment, created_by, created_date, status) "
         . "VALUES (". $revision_id . ", ". $comment_type_id .", '". mysql_real_escape_string($comment) ."', ". $created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
   }

   /**
   * GetPricingItemList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Oct 19 15:06:04 PDT 2005
   */
   function GetPricingItemList()
   {
      $q = "SELECT pi.pricing_item_id, pi.pricing_item_description   "
         . "FROM pricing_item AS pi "
         . "LEFT OUTER JOIN license_level_pricing_item AS pipr ON pipr.pricing_item_id = pi.pricing_item_id "
         . "WHERE pi.status = 'A' -- AND pipr.pricing_regime_id = '' AND pipr.license_level_id = '' ";
      return $this->executeQuery($q);
   }

   /**
   * SetOption()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Oct 20 12:54:46 PDT 2005
   */
   function SetOption($revision_id, $country_code, $option_number, $sub_group_description, $study_programming_type_id, $translation, $translation_language_code,
                      $overlay, $overlay_language_code, $study_datasource_id, $incidence_rate, $completes,
                      $questions_programmed, $questions_per_interview, $questions_per_screener, $data_recording_hours = 0, $data_tab_hours = 0,
                      $data_import_hours = 0, $data_export_hours = 0,
                      $open_end_questions = 0, $incidence_of_open_end = 0, $avg_words_per_open_end = 0, $open_end_text_coding_hours = 0,
                      $respondent_portal_type_id = 0, $respondent_portal_programming_hours = 0, $panel_import_hours = 0, $sort_order = 0, $client_portal_programming_hours)
   {
      $q = "INSERT INTO proposal_revision_option "
         . "           (proposal_revision_id, country_code, option_number, sub_group_description, study_programming_type_id, translation, "
         . "           translation_language_code, overlay, overlay_language_code,  "
         . "           study_datasource_id, incidence_rate, completes, questions_programmed, questions_per_interview,  "
         . "           questions_per_screener, data_recording_hours, data_tab_hours, data_import_hours, data_export_hours,  "
         . "           open_end_questions, incidence_of_open_end, avg_words_per_open_end, open_end_text_coding_hours, "
         . "           respondent_portal_type_id, respondent_portal_programming_hours, panel_import_hours, sort_order, client_portal_programming_hours, "
         . "           created_by, created_date, status )  "
         . "VALUES (". $revision_id.", '".$country_code."', ". $option_number .",'". mysql_real_escape_string($sub_group_description) ."', "
         . "        '". $study_programming_type_id ."', '". $translation ."', '". $translation_language_code ."', '". $overlay ."', '". $overlay_language_code ."', "
         . "        '". $study_datasource_id ."', '". $incidence_rate ."', '". $completes ."', '". $questions_programmed ."', '". $questions_per_interview ."', "
         . "        '". $questions_per_screener ."', '". $data_recording_hours ."', '". $data_tab_hours ."', '". $data_import_hours ."', '". $data_export_hours ."', "
         . "        '". $open_end_questions ."', '". $incidence_of_open_end ."', '". $avg_words_per_open_end ."', '". $open_end_text_coding_hours ."', "
         . "        '". $respondent_portal_type_id ."', '". $respondent_portal_programming_hours ."', '". $panel_import_hours ."', '". $sort_order ."', '". $client_portal_programming_hours ."', "
         . "        ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
   }

   /**
   * UpdateOption()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 10 13:22:23 PST 2005
   */
   function UpdateOption($proposal_revision_option_id, $country_code, $option_number,
                      $sub_group_description, $study_programming_type_id, $translation, $translation_language_code,
                      $overlay, $overlay_language_code, $study_datasource_id, $incidence_rate, $completes,
                      $questions_programmed, $questions_per_interview, $questions_per_screener, $data_recording_hours = 0,
                      $data_tab_hours = 0, $data_import_hours = 0, $data_export_hours = 0,
                      $open_end_questions = 0, $incidence_of_open_end = 0, $avg_words_per_open_end = 0, $open_end_text_coding_hours = 0,
                      $respondent_portal_type_id = 0, $respondent_portal_programming_hours = 0, $panel_import_hours = 0, $sort_order = 0, $client_portal_programming_hours = 0)
   {
      $q = "UPDATE proposal_revision_option "
         . "SET country_code = '" . $country_code ."', option_number = ". $option_number .", sub_group_description = '". mysql_real_escape_string($sub_group_description) ."', "
         . "    study_programming_type_id = '". $study_programming_type_id ."', translation = '" . $translation . "', translation_language_code = '". $translation_language_code ."', "
         . "    overlay = '". $overlay ."', overlay_language_code = '". $overlay_language_code ."', study_datasource_id = '". $study_datasource_id ."', "
         . "    incidence_rate = '". $incidence_rate ."', completes = '". $completes ."', questions_programmed = '". $questions_programmed ."', "
         . "    questions_per_interview = '". $questions_per_interview ."', questions_per_screener = '". $questions_per_screener ."', "
         . "    data_recording_hours = '". $data_recording_hours ."', data_tab_hours = '". $data_tab_hours ."', data_import_hours = '". $data_import_hours ."', data_export_hours = '". $data_export_hours ."', "
         . "    open_end_questions = '". $open_end_questions ."', incidence_of_open_end = '". $incidence_of_open_end ."', avg_words_per_open_end = '". $avg_words_per_open_end ."', "
         . "    open_end_text_coding_hours = '". $open_end_text_coding_hours ."', respondent_portal_type_id = '". $respondent_portal_type_id ."', respondent_portal_programming_hours = '". $respondent_portal_programming_hours ."', panel_import_hours = '". $panel_import_hours ."', "
         . "    sort_order = '". $sort_order ."', client_portal_programming_hours = '". $client_portal_programming_hours ."', "
         . "    modified_by = ". $this->created_by .", modified_date = NOW() "
         . "WHERE proposal_revision_option_id = ". $proposal_revision_option_id;
      return $this->executeQuery($q);
   }

   function SetOptionFromPanel($revision_id, $option_number, $country_code, $incidence_rate, $completes, $questions, $sort_order) {
      $q = "SELECT proposal_revision_option_id FROM proposal_revision_option WHERE proposal_revision_id='$revision_id' AND option_number = '$option_number' AND sort_order='$sort_order'";
      $rst = $this->executeQuery($q);
      if ($option = mysql_fetch_assoc($rst)) {
         $qq = "UPDATE proposal_revision_option SET country_code='$country_code', incidence_rate = '$incidence_rate', completes = '$completes', questions_programmed = '$questions', questions_per_interview = '$questions', sort_order = '$sort_order', modified_by = '".$this->created_by ."', modified_date=NOW() WHERE proposal_revision_option_id = '" . $option["proposal_revision_option_id"] . "'";
         $this->executeQuery($qq);
         
         return $option["proposal_revision_option_id"];
      } else {
         $qq = "INSERT INTO proposal_revision_option (proposal_revision_id, option_number, country_code, incidence_rate, completes, questions_programmed, questions_per_interview, sort_order, created_by, created_date, status) VALUES 
         		('$revision_id', '$option_number', '$country_code', '$incidence_rate', '$completes', '$questions', '$questions', '$sort_order', '" . $this->created_by ."', NOW(), 'A')";
         $this->executeQuery($qq);
         
         return $this->lastID;
         		
      }
   }
      
   /**
   * GetPricingRegimeList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Oct 25 13:24:27 PDT 2005
   */
   function GetPricingRegimeList()
   {
      $q = "SELECT pr.pricing_regime_id, pr.pricing_regime_description "
         . "FROM pricing_regime AS pr "
         . "WHERE pr.status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * GetLicenseLevelList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Oct 25 13:25:15 PDT 2005
   */
   function GetLicenseLevelList()
   {
      $q = "SELECT ll.license_level_id, ll.license_level_description "
         . "FROM license_level AS ll "
         . "WHERE ll.status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * SetLicenseLevelPricingItem()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Nov 08 16:15:08 PST 2005
   */
   function SetLicenseLevelPricingItem($license_level_id, $pricing_item_id, $amount)
   {
      $q = "INSERT INTO license_level_pricing_item (pricing_item_id, license_level_id, amount, created_by, created_date, status) "
         . "VALUES (". $pricing_item_id .", ". $license_level_id .", '". $amount ."', ". $this->created_by .", NOW(), 'A')";
      return $this->executeQuery($q);
   }

   /**
   * UpdateLicenseLevelPricingItem()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 10 17:33:25 PST 2005
   */
   function UpdateLicenseLevelPricingItem($license_level_pricing_item_id, $amount)
   {
      $q = "UPDATE license_level_pricing_item "
         . "SET amount = '" . $amount ."', modified_by = " . $this->created_by . ", modified_date = NOW() "
         . "WHERE license_level_pricing_item_id = ". $license_level_pricing_item_id;
      return $this->executeQuery($q);
   }

   /**
   * GetDefaultPricingByLicenseLevel()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Nov 08 16:27:32 PST 2005
   */
   function GetDefaultPricingByLicenseLevel($license_level_id)
   {
      $q = "SELECT pi.pricing_item_description, llpi.amount, pi.pricing_item_id, "
         . "       IF(llpi.amount IS NULL, 0, 1) AS do_update, llpi.license_level_pricing_item_id, pinf.inflator_amount, "
         . "       pd_c.discount_amount AS cd_amount, pd_nc.discount_amount AS ncd_amount, pd_p.discount_amount AS pd_amount "
         . "FROM pricing_item AS pi "
         . "LEFT OUTER JOIN license_level_pricing_item AS llpi ON pi.pricing_item_id = llpi.pricing_item_id "
         . "            AND llpi.license_level_id = ". $license_level_id ." "
         . "LEFT OUTER JOIN pricing_inflator AS pinf ON pinf.license_level_id = llpi.license_level_id "
         . "            AND pinf.effective_date <= NOW() "
         . "            AND pinf.expire_date >= NOW() "
         . "            AND pinf.pricing_item_id = pi.pricing_item_id "
         . "LEFT OUTER JOIN pricing_discount AS pd_c ON pd_c.license_level_id = llpi.license_level_id "
         . "            AND pd_c.effective_date <= NOW() "
         . "            AND pd_c.expire_date >= NOW() "
         . "            AND pd_c.pricing_item_id = pi.pricing_item_id "
         . "            AND pd_c.pricing_discount_type_id = ". PRICING_DISCOUNT_CONTRACT ." "
         . "LEFT OUTER JOIN pricing_discount AS pd_nc ON pd_nc.license_level_id = llpi.license_level_id "
         . "            AND pd_nc.effective_date <= NOW() "
         . "            AND pd_nc.expire_date >= NOW() "
         . "            AND pd_nc.pricing_item_id = pi.pricing_item_id "
         . "            AND pd_nc.pricing_discount_type_id = ". PRICING_DISCOUNT_NON_CONTRACT ." "
         . "LEFT OUTER JOIN pricing_discount AS pd_p ON pd_p.license_level_id = llpi.license_level_id "
         . "            AND pd_p.effective_date <= NOW() "
         . "            AND pd_p.expire_date >= NOW() "
         . "            AND pd_p.pricing_item_id = pi.pricing_item_id "
         . "            AND pd_p.pricing_discount_type_id = ". PRICING_DISCOUNT_PROMOTIONAL ." "
         . "WHERE pi.status = 'A'";
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
   * @since  - Wed Nov 09 09:35:29 PST 2005
   */
   function GetDefaultPartnerPricing($account_id)
   {
      $q = "SELECT pi.pricing_item_id, pp.partner_id, pp.amount, "
         . "       pp.is_value, pp.is_percent, pi.pricing_item_description,  "
         . "       ppinf.inflator_amount, ppd_c.discount_amount AS cd_amount, ppd_nc.discount_amount AS ncd_amount, "
         . "       ppd_p.discount_amount AS pd_amount   "
         . "FROM pricing_item AS pi "
         . "LEFT OUTER JOIN account AS a ON a.account_id = ". $account_id ." "
         . "LEFT OUTER JOIN partner_pricing AS pp ON pp.pricing_item_id = pi.pricing_item_id 
         					AND pp.partner_id = a.account_id "
         . "LEFT OUTER JOIN partner_pricing_inflator AS ppinf ON ppinf.partner_id = a.account_id
                        AND ppinf.effective_date <= NOW()
                        AND ppinf.expire_date >= NOW()
                        AND ppinf.pricing_item_id = pi.pricing_item_id "
         . "LEFT OUTER JOIN partner_pricing_discount AS ppd_c ON ppd_c.pricing_item_id = pi.pricing_item_id
                        AND ppd_c.partner_id = ". $account_id ."
                        AND ppd_c.effective_date <= NOW()
                        AND ppd_c.expire_date >= NOW()
                        AND ppd_c.pricing_discount_type_id = ". PRICING_DISCOUNT_CONTRACT ." "
         . "LEFT OUTER JOIN partner_pricing_discount AS ppd_nc ON ppd_nc.pricing_item_id = pi.pricing_item_id
                        AND ppd_nc.partner_id = ". $account_id ."
                        AND ppd_nc.effective_date <= NOW()
                        AND ppd_nc.expire_date >= NOW()
                        AND ppd_nc.pricing_discount_type_id = ". PRICING_DISCOUNT_NON_CONTRACT ." "
         . "LEFT OUTER JOIN partner_pricing_discount AS ppd_p ON ppd_p.pricing_item_id = pi.pricing_item_id
                        AND ppd_p.partner_id = ". $account_id ."
                        AND ppd_p.effective_date <= NOW()
                        AND ppd_p.expire_date >= NOW()
                        AND ppd_p.pricing_discount_type_id = ". PRICING_DISCOUNT_PROMOTIONAL ." "
         . "WHERE pi.status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * CreatePriceMatchTable()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Nov 09 11:05:09 PST 2005
   */
   function CreatePriceMatchTable()
   {
      $q = "DROP TABLE IF EXISTS proposal_price_match";
      $this->executeQuery($q);

      $q = "CREATE TEMPORARY TABLE proposal_price_match ( "
         . " proposal_price_match_id INT AUTO_INCREMENT,  "
         . " pricing_item_id INT NOT NULL, "
         . " amount DECIMAL(10,3) NOT NULL, "
         . " PRIMARY KEY (proposal_price_match_id)) ";
      return $this->executeQuery($q);
   }

   /**
   * SetPriceMatchItem()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Nov 09 11:09:03 PST 2005
   */
   function SetPriceMatchItem($pricing_item_id, $amount)
   {
      $q = "INSERT INTO proposal_price_match (pricing_item_id, amount) "
         . "VALUES (". $pricing_item_id .", '". $amount ."') ";
      return $this->executeQuery($q);
   }

   /**
   * GetMissMatchPricing()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Nov 09 11:11:54 PST 2005
   */
   function GetMissMatchPricing($license_level_id)
   {
      $q = "SELECT ppm.pricing_item_id, ppm.amount "
         . "FROM proposal_price_match AS ppm "
         . "LEFT OUTER JOIN license_level_pricing_item AS llpi ON llpi.pricing_item_id = ppm.pricing_item_id "
         . "WHERE llpi.amount != ppm.amount AND llpi.license_level_id = ". $license_level_id;
      return $this->executeQuery($q);
   }

   /**
   * SetPartnerPricingById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Nov 09 11:21:48 PST 2005
   */
   function SetPartnerPricingById($partner_id, $pricing_item_id, $amount)
   {
      $q = "INSERT INTO partner_pricing ( partner_id, pricing_item_id, amount, created_by, created_date, status ) "
         . "VALUES (". $partner_id .", ". $pricing_item_id .", '". $amount ."', ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
   }

   /**
   * isPartnerCustomPriceSet()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Nov 09 11:40:20 PST 2005
   */
   function isPartnerCustomPriceSet($partner_id, $pricing_item_id)
   {
      $q = "SELECT pp.pricing_item_id "
         . "FROM partner_pricing AS pp "
         . "WHERE pp.partner_id = ". $partner_id ." AND pp.pricing_item_id = ". $pricing_item_id;
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return ($r) ? true : false;
   }

   /**
   * UpdatePartnerPricingById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Nov 09 11:42:16 PST 2005
   */
   function UpdatePartnerPricingById($partner_id, $pricing_item_id, $amount)
   {
      $q = "UPDATE partner_pricing SET amount = '". $amount ."' "
         . "WHERE partner_id = ". $partner_id ." AND pricing_item_id = ". $pricing_item_id;
      return $this->executeQuery($q);

   }

   /**
   * GetProposalPartner()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Nov 09 11:58:01 PST 2005
   */
   function GetProposalPartner()
   {
      $q = "SELECT pa_partner.proposal_attr_value "
         . "FROM proposal AS p "
         . "LEFT OUTER JOIN proposal_attr AS pa_partner ON pa_partner.proposal_id = p.proposal_id "
         . "WHERE pa_partner.proposal_attr_name = 'ACCOUNT_ID' AND p.proposal_id = ". $this->__id;
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return $r['proposal_attr_value'];
   }

   /**
   * GetPricingRuleTables()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Nov 09 16:46:47 PST 2005
   */
   function GetPricingRuleTables()
   {
      $q = "SELECT pricing_item_rule_table_id, pricing_item_rule_table_name, pricing_item_rule_table_description, input_field_name "
         . "FROM pricing_item_rule_table "
         . "WHERE status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * GetRuleTableById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Nov 09 17:08:13 PST 2005
   */
   function GetRuleTableById($pricing_item_rule_table_id)
   {
      $q = "SELECT pricing_item_rule_table_name "
         . "FROM pricing_item_rule_table "
         . "WHERE pricing_item_rule_table_id = ". $pricing_item_rule_table_id;
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return $r['pricing_item_rule_table_name'];
   }

   /**
   * GetRuleDataByTableName()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Nov 09 17:17:54 PST 2005
   */
   function GetRuleDataByTableName($table_name)
   {
      $q = "SELECT ". $table_name ."_id, ". $table_name ."_description "
         . "FROM ". $table_name ." "
         . "WHERE status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * GetLicenseLevelPricingItemId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Nov 09 19:07:02 PST 2005
   */
   function GetLicenseLevelPricingItemId($license_level_id, $pricing_item_id)
   {
      $q = "SELECT license_level_pricing_item_id "
         . "FROM license_level_pricing_item "
         . "WHERE license_level_id = ". $license_level_id ." AND pricing_item_id = ". $pricing_item_id . " AND status = 'A' ";
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return $r['license_level_pricing_item_id'];
   }

   /**
   * GetRuleInputFieldByRuleTableId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Nov 09 19:15:10 PST 2005
   */
   function GetRuleInputFieldByRuleTableId($pricing_item_rule_table_id)
   {
      $q = "SELECT input_field_name "
         . "FROM pricing_item_rule_table "
         . "WHERE pricing_item_rule_table_id = ". $pricing_item_rule_table_id;
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return $r['input_field_name'];
   }

   /**
   * SetPricingItemRule()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Nov 09 19:23:43 PST 2005
   */
   function SetPricingItemRule($license_level_pricing_item_id, $rule_condition, $amount)
   {
      $q = "INSERT INTO pricing_item_rule ( license_level_pricing_item_id, rule_condition, amount, created_by, created_date, status) "
         . "VALUES (" . $license_level_pricing_item_id .", '". $rule_condition ."', '". $amount ."', ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
   }

   /**
   * GetPricingRules()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Nov 09 21:26:43 PST 2005
   */
   function GetPricingRules()
   {
      $q = "SELECT license_level_pricing_item_id, rule_condition, amount "
         . "FROM pricing_item_rule "
         . "WHERE status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * GetCurrentRevisionId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 10 10:59:13 PST 2005
   */
   function GetCurrentRevisionId($proposal_id)
   {
      $q = "SELECT pr.proposal_revision_id "
         . "FROM proposal AS p "
         . "LEFT OUTER JOIN proposal_revision AS pr ON pr.revision = p.current_revision AND pr.proposal_id = p.proposal_id "
         . "WHERE p.proposal_id = ". $proposal_id;
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return $r['proposal_revision_id'];

   }

   /**
   * SetRevisionPrice()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 10 11:03:06 PST 2005
   */
   function SetRevisionPrice($proposal_revision_id, $pricing_item_id, $license_level_price, $inflator, $contracted_discount,
                                $non_contacted_discount, $promotional_discount, $ad_hoc_discount, $net_price)
   {
      $q = "INSERT INTO proposal_revision_pricing (proposal_revision_id, pricing_item_id, license_level_price, inflator, contracted_discount, non_contracted_discount, promotional_discount, ad_hoc_discount, net_price, created_by, created_date, status) "
         . "VALUES (" . $proposal_revision_id . ", " . $pricing_item_id . ", '" . $license_level_price ."', '" . $inflator . "', '" . $contracted_discount . "', '" . $non_contacted_discount ."', '" . $promotional_discount . "', '" . $ad_hoc_discount . "', '" . $net_price . "', " . $this->created_by . ", NOW(), 'A')";
      return $this->executeQuery($q);
   }

   /**
   * UpdateRevisionPrice()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 10 15:34:04 PST 2005
   */
   function UpdateRevisionPrice($proposal_revision_pricing_id, $license_level_price, $inflator, $contracted_discount,
                                $non_contacted_discount, $promotional_discount, $ad_hoc_discount, $net_price)
   {
      $q = "UPDATE proposal_revision_pricing "
         . "SET license_level_price = '". $license_level_price ."', inflator = '". $inflator ."', contracted_discount = '". $contracted_discount ."', "
         . "    non_contracted_discount = '". $non_contacted_discount ."', promotional_discount = '". $promotional_discount ."', ad_hoc_discount = '". $ad_hoc_discount ."', "
         . "    net_price = '". $net_price ."', modified_by = ". $this->created_by .", modified_date = NOW() "
         . "WHERE proposal_revision_pricing_id = ". $proposal_revision_pricing_id;
      return $this->executeQuery($q);
   }

   /**
   * GetRevisionOptions()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 10 11:21:16 PST 2005
   */
   function GetRevisionOptions()
   {
      $q = "SELECT pro.proposal_revision_option_id, pro.proposal_revision_id, pro.country_code, pro.option_number, pro.sub_group_description, "
         . "       pro.study_programming_type_id, pro.translation, pro.translation_language_code, pro.overlay, pro.overlay_language_code, "
         . "       pro.study_datasource_id, pro.incidence_rate, pro.completes, pro.questions_programmed, pro.questions_per_interview,  "
         . "       pro.questions_per_screener, pro.data_recording_hours, pro.data_tab_hours, pro.data_export_hours, pro.data_import_hours, pro.open_end_questions, "
         . "       pro.incidence_of_open_end, pro.avg_words_per_open_end, pro.open_end_text_coding_hours, c.country_id, c.country_description, sd.study_datasource_description, "
         . "       pro.respondent_portal_type_id, pro.respondent_portal_programming_hours, pro.client_portal_programming_hours, pro.panel_import_hours, spt.study_programming_type_description,  "
         . "       rpt.respondent_portal_type_description, pro.sort_order, l.language_description AS translation_language_description, pro.panel_cost_per_interview "
         . "FROM proposal_revision_option AS pro "
         . "LEFT OUTER JOIN country AS c ON c.country_code = pro.country_code "
         . "LEFT OUTER JOIN study_datasource AS sd ON sd.study_datasource_id = pro.study_datasource_id "
         . "LEFT OUTER JOIN study_programming_type AS spt ON spt.study_programming_type_id = pro.study_programming_type_id "
         . "LEFT OUTER JOIN respondent_portal_type AS rpt ON rpt.respondent_portal_type_id = pro.respondent_portal_type_id "
         . "LEFT OUTER JOIN language AS l ON l.language_code = pro.translation_language_code "
         . "WHERE pro.proposal_revision_id = ". $this->__revision;
      return $this->executeQuery($q);
   }

   /**
   * GetRevisionPricing()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 10 15:27:11 PST 2005
   */
   function GetRevisionPricing()
   {
      $q = "SELECT prp.proposal_revision_pricing_id, pi.pricing_item_id, pi.pricing_item_description, prp.license_level_price, prp.inflator, prp.contracted_discount, "
         . "       prp.non_contracted_discount, prp.promotional_discount, prp.ad_hoc_discount, prp.net_price "
         . "FROM pricing_item AS pi "
         . "LEFT OUTER JOIN proposal_revision_pricing AS prp ON prp.pricing_item_id = pi.pricing_item_id "
         . "WHERE pi.status = 'A' AND prp.proposal_revision_id = ". $this->__revision;
      return $this->executeQuery($q);
   }

   /**
   * GetRevisionPricingByGroup()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Dec 01 10:41:55 PST 2005
   */
   function GetRevisionPricingByGroup($group_id)
   {
      $q = "SELECT prp.proposal_revision_pricing_id, pi.pricing_item_id, pi.pricing_item_description, prp.license_level_price, prp.inflator, prp.contracted_discount, "
         . "       prp.non_contracted_discount, prp.promotional_discount, prp.ad_hoc_discount, prp.net_price "
         . "FROM pricing_item AS pi "
         . "LEFT OUTER JOIN proposal_revision_pricing AS prp ON prp.pricing_item_id = pi.pricing_item_id "
         . "WHERE pi.status = 'A' AND prp.proposal_revision_id = ". $this->__revision . " AND pi.pricing_item_group_id = ". $group_id;
      return $this->executeQuery($q);
   }

   /**
   * SetPartnerInflator()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 10 20:12:17 PST 2005
   */
   function SetPartnerInflator($pricing_item_id, $partner_id, $inflator_amount, $effective_date, $expire_date)
   {
      $expire_date = ($expire_date == '') ? '2050-12-31' : $expire_date;
      $effective_date = ($effective_date == '') ? date("Y-m-d") : $effective_date;

      $q = "INSERT INTO partner_pricing_inflator (pricing_item_id, partner_id, inflator_amount, effective_date, expire_date, created_by, created_date, status ) "
         . "VALUES (". $pricing_item_id .", ". $partner_id .", '". $inflator_amount ."', '". $effective_date ."', '". $expire_date ."', ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
   }

   /**
   * isPartnerInflatorSet()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 10 20:18:45 PST 2005
   */
   function isPartnerInflatorSet($pricing_item_id, $partner_id)
   {
      $q = "SELECT partner_pricing_inflator_id "
         . "FROM partner_pricing_inflator "
         . "WHERE pricing_item_id = ". $pricing_item_id ." AND partner_id = ". $partner_id ." "
         . "AND effective_date <= NOW() AND expire_date >= NOW() AND status = 'A' ";
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return ($r) ? true : false;
   }

   /**
   * UpdatePartnerInflator()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 10 20:24:14 PST 2005
   */
   function UpdatePartnerInflator($pricing_item_id, $partner_id, $inflator_amount, $effective_date, $expire_date)
   {
      $q = "UPDATE partner_pricing_inflator "
         . "SET inflator_amount = '". $inflator_amount ."', effective_date = '". $effective_date ."', expire_date = '". $expire_date ."' "
         . "WHERE pricing_item_id = ". $pricing_item_id ." AND partner_id = ". $partner_id ." AND effective_date <= NOW() AND expire_date >= NOW() AND status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * ExpirePartnerInflator()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 17 09:10:58 PST 2005
   */
   function ExpirePartnerInflator($pricing_item_id, $partner_id)
   {
      $q = "UPDATE partner_pricing_inflator "
         . "SET expire_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY), modified_by = ". $this->created_by .", modified_date = NOW() "
         . "WHERE pricing_item_id = ". $pricing_item_id ." AND partner_id = ". $partner_id ." AND effective_date <= NOW() AND expire_date >= NOW() AND status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * isPartnerDiscountSet()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 10 21:00:18 PST 2005
   */
   function isPartnerDiscountSet($pricing_item_id, $partner_id, $pricing_discount_type_id)
   {
      $q = "SELECT pricing_discount_id "
         . "FROM partner_pricing_discount "
         . "WHERE pricing_item_id = ". $pricing_item_id ." AND partner_id = ". $partner_id ." AND pricing_discount_type_id = ". $pricing_discount_type_id . " "
         . " AND effective_date <= NOW() AND expire_date >= NOW() AND status = 'A'";
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return ($r) ? true : false;
   }

   /**
   * UpdatePartnerDiscount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 10 21:05:02 PST 2005
   */
   function UpdatePartnerDiscount($pricing_item_id, $partner_id, $pricing_discount_type_id, $amount, $effective_date, $expire_date)
   {
      $q = "UPDATE partner_pricing_discount "
         . "SET discount_amount = '". $amount ."', modified_by = ". $this->created_by .", modified_date = NOW() "
         . "WHERE pricing_item_id = ". $pricing_item_id ." AND partner_id = ". $partner_id ." AND pricing_discount_type_id = ". $pricing_discount_type_id ." "
         . " AND effective_date <= NOW() AND expire_date >= NOW() AND status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * SetPartnerDiscount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 10 21:05:09 PST 2005
   */
   function SetPartnerDiscount($pricing_item_id, $partner_id, $pricing_discount_type_id, $amount, $effective_date, $expire_date)
   {
      $expire_date = ($expire_date == '') ? '2050-12-31' : $expire_date;
      $effective_date = ($effective_date == '') ? date("Y-m-d") : $effective_date;

      $q = "INSERT INTO partner_pricing_discount (pricing_item_id, partner_id, pricing_discount_type_id, discount_amount, effective_date, expire_date, created_by, created_date, status) "
         . "VALUES (". $pricing_item_id .", ". $partner_id .", ". $pricing_discount_type_id .", '". $amount ."', '". $effective_date ."', '". $expire_date ."', ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
   }

   /**
   * ExpirePartnerDiscount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Nov 17 10:06:08 PST 2005
   */
   function ExpirePartnerDiscount($pricing_item_id, $partner_id, $pricing_discount_type_id)
   {
      $q = "UPDATE partner_pricing_discount "
         . "SET expire_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY), modified_by = ". $this->created_by .", modified_date = NOW() "
         . "WHERE pricing_item_id = ". $pricing_item_id ." AND partner_id = ". $partner_id ." "
         . "  AND pricing_discount_type_id = ". $pricing_discount_type_id ." AND effective_date <= NOW() AND expire_date >= NOW() AND status = 'A' ";
      return $this->executeQuery($q);
   }


   /**
   * GetProposalBudgetLineItems()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Nov 11 10:22:58 PST 2005
   */
   function GetProposalBudgetLineItems()
   {
      $q = "SELECT proposal_budget_line_item_id, proposal_budget_line_item_description "
         . "FROM proposal_budget_line_item "
         . "WHERE status = 'A' "
         . "ORDER BY sort_order ";
      return $this->executeQuery($q);
   }

   /**
   * GetRevisionBudgetLineItems()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Nov 11 16:24:10 PST 2005
   */
   function GetRevisionBudgetLineItems()
   {

      $q = "SELECT pro.proposal_revision_option_id, pro.proposal_revision_id, pro.country_code, pro.option_number, pro.sub_group_description, "
         . "       pro.study_programming_type_id, pro.translation, pro.translation_language_code, pro.overlay, pro.overlay_language_code,  "
         . "       pro.study_datasource_id, pro.incidence_rate, pro.completes, pro.questions_programmed, pro.questions_per_interview,  "
         . "       pro.questions_per_screener, pro.data_recording_hours, pro.data_tab_hours, pro.data_import_hours, pro.data_export_hours, pro.open_end_questions, "
         . "       pro.incidence_of_open_end, pro.avg_words_per_open_end, pro.open_end_text_coding_hours, prbli.amount, pbli.proposal_budget_line_item_description, "
         . "       pbli.proposal_budget_line_item_id, pbli.sort_order, pbli.value_type, pro.respondent_portal_type_id, pro.respondent_portal_programming_hours, pro.client_portal_programming_hours, pro.panel_import_hours, "
         . "       c.country_description, pig.pricing_item_group_description, pig.pricing_item_group_id, pro.sort_order AS c_sort_order, pbli.precision "
         . "FROM proposal_revision_option AS pro "
         . "LEFT OUTER JOIN proposal_revision_budget_line_item AS prbli ON prbli.proposal_revision_option_id = pro.proposal_revision_option_id "
         . "LEFT OUTER JOIN proposal_budget_line_item AS pbli ON pbli.proposal_budget_line_item_id = prbli.proposal_budget_line_item_id "
         . "LEFT OUTER JOIN country AS c ON c.country_code = pro.country_code "
         . "LEFT OUTER JOIN pricing_item_group AS pig ON pig.pricing_item_group_id = pbli.pricing_item_group_id "
         . "WHERE pbli.pricing_item_group_id != 0 AND pro.proposal_revision_id = ". $this->__revision ." "
         . "ORDER BY pro.option_number, pro.sort_order, pig.pricing_item_group_id, pbli.sort_order ";
      return $this->executeQuery($q);
   }

   /**
   * GetRevisionBudgetLineItemSummary()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Nov 11 16:24:10 PST 2005
   */
   function GetRevisionBudgetLineItemSummary($revision_id)
   {

      $q = "SELECT pro.proposal_revision_option_id, pro.proposal_revision_id, pro.country_code, pro.option_number, pro.sub_group_description, "
         . "       pro.study_programming_type_id, pro.translation, pro.translation_language_code, pro.overlay, pro.overlay_language_code, "
         . "       pro.study_datasource_id, pro.incidence_rate, pro.completes, pro.questions_programmed, pro.questions_per_interview,  "
         . "       pro.questions_per_screener, pro.data_recording_hours, pro.data_tab_hours, pro.data_import_hours, pro.data_export_hours, "
         . "       pro.open_end_questions, pro.respondent_portal_type_id, pro.respondent_portal_programming_hours, pro.client_portal_programming_hours, pro.panel_import_hours,  "
         . "       pro.incidence_of_open_end, pro.avg_words_per_open_end, pro.open_end_text_coding_hours, prbli.amount, "
         . "       pbli.proposal_budget_line_item_description, pbli.proposal_budget_line_item_id, pbli.sort_order, pbli.value_type, pro.sort_order AS o_sort_order "
         . "FROM proposal_revision_option AS pro "
         . "LEFT OUTER JOIN proposal_revision_budget_line_item AS prbli ON prbli.proposal_revision_option_id = pro.proposal_revision_option_id "
         . "LEFT OUTER JOIN proposal_budget_line_item AS pbli ON pbli.proposal_budget_line_item_id = prbli.proposal_budget_line_item_id "
         . "WHERE pro.proposal_revision_id = ". $revision_id ." AND pbli.proposal_budget_line_item_id IN ( ". PROPOSAL_BUDGET_LICENSE .", ". PROPOSAL_BUDGET_TOTAL_PROJECT_SETUP .", ". PROPOSAL_BUDGET_TOTAL_HOSTING .", ". PROPOSAL_BUDGET_TOTAL_PANEL .", ". PROPOSAL_BUDGET_TOTAL_DP .") "
         . "ORDER BY pbli.sort_order ";
      return $this->executeQuery($q);

   }

   /**
   * SetRevisionOptionBudgetLineItem()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Nov 11 16:14:03 PST 2005
   */
   function SetRevisionOptionBudgetLineItem($proposal_revision_option_id, $proposal_budget_line_item_id, $amount)
   {
      $q = "INSERT INTO proposal_revision_budget_line_item (proposal_revision_option_id, proposal_budget_line_item_id, amount, created_by, created_date, status) "
         . "VALUES ('". $proposal_revision_option_id ."', '". $proposal_budget_line_item_id ."', '". $amount ."', ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
   }

   /**
   * isRevisionOptionBudgetLineItem()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Nov 18 11:24:44 PST 2005
   */
   function isRevisionOptionBudgetLineItem($proposal_revision_option_id, $proposal_budget_line_item_id)
   {
      $q = "SELECT proposal_revision_budget_line_item_id "
         . "FROM proposal_revision_budget_line_item "
         . "WHERE proposal_revision_option_id = ". $proposal_revision_option_id ." AND proposal_budget_line_item_id = ". $proposal_budget_line_item_id ." AND status = 'A' ";
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return ($r) ? true : false;
   }

   /**
   * UpdateRevisionOptionBudgetLineItem()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Nov 18 11:26:43 PST 2005
   */
   function UpdateRevisionOptionBudgetLineItem($proposal_revision_option_id, $proposal_budget_line_item_id, $amount)
   {
      $q = "UPDATE proposal_revision_budget_line_item "
         . "SET amount = '". $amount ."' "
         . "WHERE proposal_revision_option_id = ". $proposal_revision_option_id ." AND proposal_budget_line_item_id = ". $proposal_budget_line_item_id ." AND status = 'A' ";
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
   * @since  - Tue Nov 22 20:28:01 PST 2005
   */
   function SetContact($contact_id, $first_name, $last_name, $email)
   {
      $q = "INSERT INTO proposal_contact (proposal_id, contact_id, first_name, last_name, email, created_by, created_date, status ) "
         . "VALUES (". $this->__id .", ". $contact_id .", '". mysql_real_escape_string($first_name) ."', '". mysql_real_escape_string($last_name) ."', '". mysql_real_escape_string($email) ."', ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
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
   * @since  - Sat Dec 31 00:43:03 PST 2005
   */
   function UpdateContact($contact_id, $first_name, $last_name, $email)
   {
      $q = "UPDATE proposal_contact "
         . "SET contact_id = ". $contact_id .", first_name = '". $first_name ."', last_name = '". $last_name ."', "
         . "    email = '". $email ."', modified_by = ". $this->created_by .", modified_date = NOW() "
         . "WHERE proposal_id = ". $this->__id;
      return $this->executeQuery($q);
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
   * SetRevisionSampleType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Nov 29 08:09:38 PST 2005
   */
   function SetRevisionSampleType($revision_id, $sample_type_id)
   {
      $q = "INSERT INTO proposal_revision_sample_type ( proposal_revision_id, sample_type_id, created_by, created_date, status ) "
         . "VALUES (". $revision_id .", ". $sample_type_id .", ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
   }

   /**
   * GetPricingVolumeDiscountByLicenseLevel()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Nov 29 10:21:56 PST 2005
   */
   function GetPricingVolumeDiscountByLicenseLevel($license_level_id, $volume_amount = 100000000, $sort = 0)
   {
      $order_by = ($sort == 1) ? "ORDER BY volume_amount DESC " : " ";

      $q = "SELECT volume_amount, discount_amount "
         . "FROM pricing_volume_discount "
         . "WHERE effective_date <= NOW() AND expire_date >= NOW() AND license_level_id = ". $license_level_id
         . "  AND volume_amount <= ". $volume_amount ." "
         . $order_by;
      return $this->executeQuery($q);
   }

    /**
   * GetAccountVolumeDiscount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Nov 29 10:21:56 PST 2005
   */
   function GetAccountVolumeDiscount($account_id, $volume_amount = 100000000, $sort = 0)
   {
      $order_by = ($sort == 1) ? "ORDER BY volume_amount DESC " : " ";

      $q = "SELECT volume_amount, discount_amount "
         . "FROM partner_volume_discount "
         . "WHERE effective_date <= NOW() AND expire_date >= NOW() AND partner_id = ". $account_id
         . "  AND volume_amount <= ". $volume_amount ." "
         . $order_by;
      return $this->executeQuery($q);
   }

   /**
   * GetPanelCostType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Dec 05 10:58:44 PST 2005
   */
   function GetPanelCostType()
   {
      $q = "SELECT panel_cost_type_id, panel_cost_type_description "
         . "FROM panel_cost_type "
         . "WHERE status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * GetPanelCostByLicenseLevel()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Dec 05 12:36:39 PST 2005
   */
   function GetPanelCostByLicenseLevel($license_level_id)
   {
      $q = "SELECT pd_c.discount_amount AS contracted, pd_nc.discount_amount AS non_contracted, "
         . "       pd_p.discount_amount AS promotional, pi.inflator_amount, pct.panel_cost_type_description, "
         . "       pct.panel_cost_type_id "
         . "FROM panel_cost_type AS pct "
         . "LEFT OUTER JOIN panel_discount AS pd_c ON pd_c.panel_cost_type_id = pct.panel_cost_type_id "
         . "            AND pd_c.pricing_discount_type_id = ". PRICING_DISCOUNT_CONTRACT ." "
         . "            AND pd_c.effective_date <= NOW() AND pd_c.expire_date >= NOW() "
         . "            AND pd_c.license_level_id = ". $license_level_id ." "
         . "LEFT OUTER JOIN panel_discount AS pd_nc ON pd_nc.panel_cost_type_id = pct.panel_cost_type_id "
         . "            AND pd_nc.pricing_discount_type_id = ". PRICING_DISCOUNT_NON_CONTRACT ." "
         . "            AND pd_nc.effective_date <= NOW() AND pd_nc.expire_date >= NOW() "
         . "            AND pd_nc.license_level_id = ". $license_level_id ." "
         . "LEFT OUTER JOIN panel_discount AS pd_p ON pd_p.panel_cost_type_id = pct.panel_cost_type_id "
         . "            AND pd_p.pricing_discount_type_id = ". PRICING_DISCOUNT_PROMOTIONAL ." "
         . "            AND pd_p.effective_date <= NOW() AND pd_p.expire_date >= NOW() "
         . "            AND pd_p.license_level_id = ". $license_level_id ." "
         . "LEFT OUTER JOIN panel_inflator AS pi ON pi.panel_cost_type_id = pi.panel_cost_type_id "
         . "            AND pi.effective_date <= NOW() AND pi.expire_date >= NOW() "
         . "            AND pi.license_level_id = ". $license_level_id ." "
         . "WHERE pct.status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * GetPanelCostByPartner()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Dec 05 16:26:56 PST 2005
   */
   function GetPanelCostByPartner($partner_id)
   {
      $q = "SELECT ppd_c.discount_amount AS contracted, ppd_nc.discount_amount AS non_contracted, "
         . "       ppd_p.discount_amount AS promotional, pi.inflator_amount, pct.panel_cost_type_description, "
         . "       pct.panel_cost_type_id "
         . "FROM panel_cost_type AS pct "
         . "LEFT OUTER JOIN partner_panel_discount AS ppd_c ON ppd_c.panel_cost_type_id = pct.panel_cost_type_id "
         . "            AND ppd_c.pricing_discount_type_id = ". PRICING_DISCOUNT_CONTRACT ." "
         . "            AND ppd_c.effective_date <= NOW() AND ppd_c.expire_date >= NOW() "
         . "            AND ppd_c.partner_id = ". $partner_id ." "
         . "LEFT OUTER JOIN partner_panel_discount AS ppd_nc ON ppd_nc.panel_cost_type_id = pct.panel_cost_type_id "
         . "            AND ppd_nc.pricing_discount_type_id = ". PRICING_DISCOUNT_NON_CONTRACT ." "
         . "            AND ppd_nc.effective_date <= NOW() AND ppd_nc.expire_date >= NOW() "
         . "            AND ppd_nc.partner_id = ". $partner_id ." "
         . "LEFT OUTER JOIN partner_panel_discount AS ppd_p ON ppd_p.panel_cost_type_id = pct.panel_cost_type_id "
         . "            AND ppd_p.pricing_discount_type_id = ". PRICING_DISCOUNT_PROMOTIONAL ." "
         . "            AND ppd_p.effective_date <= NOW() AND ppd_p.expire_date >= NOW() "
         . "            AND ppd_p.partner_id = ". $partner_id ." "
         . "LEFT OUTER JOIN partner_panel_inflator AS pi ON pi.panel_cost_type_id = pi.panel_cost_type_id "
         . "            AND pi.effective_date <= NOW() AND pi.expire_date >= NOW() "
         . "            AND pi.partner_id = ". $partner_id ." "
         . "WHERE pct.status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * SetRevisionPanelCost()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Dec 05 17:07:26 PST 2005
   */
   function SetRevisionPanelCost($panel_cost_type_id, $proposal_revision_id, $inflator,
                                 $contracted_discount, $non_contracted_discount, $promotional_discount, $ad_hoc_discount = 0)
   {
      $q = "INSERT INTO proposal_revision_panel_cost "
         . " (panel_cost_type_id, proposal_revision_id, inflator, contracted_discount, non_contracted_discount, "
         . "  promotional_discount, ad_hoc_discount, created_by, created_date, status) "
         . "VALUES (". $panel_cost_type_id .", ". $proposal_revision_id .", '". $inflator ."', '". $contracted_discount ."', "
         . "       '". $non_contracted_discount ."', '". $promotional_discount ."', '". $ad_hoc_discount ."', "
         . "        ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
   }

   /**
   * GetRevisionPanelCost()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Dec 05 17:18:23 PST 2005
   */
   function GetRevisionPanelCost()
   {
      $q = "SELECT prpc.proposal_revision_panel_cost_id, prpc.panel_cost_type_id, prpc.inflator, prpc.contracted_discount, "
         . "       prpc.non_contracted_discount, prpc.promotional_discount, prpc.ad_hoc_discount, pct.panel_cost_type_description "
         . "FROM proposal_revision_panel_cost AS prpc "
         . "LEFT OUTER JOIN panel_cost_type AS pct ON pct.panel_cost_type_id = prpc.panel_cost_type_id "
         . "WHERE prpc.status = 'A' AND prpc.proposal_revision_id = ". $this->__revision;
      return $this->executeQuery($q);
   }

   /**
   * GetGroupDiscountByLicenseLevel()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Dec 05 17:49:47 PST 2005
   */
   function GetGroupDiscountByLicenseLevel($license_level_id)
   {
      $q = "SELECT pig.pricing_item_group_id, pig.pricing_item_group_description, pigd_c.discount_amount AS contracted,  "
         . "       pigd_nc.discount_amount AS non_contracted, pigd_p.discount_amount AS promotional, pigi.inflator_amount AS inflator "
         . "FROM pricing_item_group AS pig "
         . "LEFT OUTER JOIN pricing_item_group_discount AS pigd_c ON pigd_c.pricing_item_group_id = pig.pricing_item_group_id "
         . "            AND pigd_c.license_level_id = ". $license_level_id ." "
         . "            AND pigd_c.pricing_discount_type_id = ". PRICING_DISCOUNT_CONTRACT ." "
         . "            AND pigd_c.effective_date <= NOW() AND pigd_c.expire_date >= NOW() "
         . "LEFT OUTER JOIN pricing_item_group_discount AS pigd_nc ON pigd_nc.pricing_item_group_id = pig.pricing_item_group_id "
         . "            AND pigd_nc.license_level_id = ". $license_level_id ." "
         . "            AND pigd_nc.pricing_discount_type_id = ". PRICING_DISCOUNT_NON_CONTRACT ." "
         . "            AND pigd_nc.effective_date <= NOW() AND pigd_nc.expire_date >= NOW() "
         . "LEFT OUTER JOIN pricing_item_group_discount AS pigd_p ON pigd_p.pricing_item_group_id = pig.pricing_item_group_id "
         . "            AND pigd_p.license_level_id = ". $license_level_id ." "
         . "            AND pigd_p.pricing_discount_type_id = ". PRICING_DISCOUNT_PROMOTIONAL ." "
         . "            AND pigd_p.effective_date <= NOW() AND pigd_p.expire_date >= NOW() "
         . "LEFT OUTER JOIN pricing_item_group_inflator AS pigi ON pigi.pricing_item_group_id = pig.pricing_item_group_id "
         . "            AND pigi.license_level_id = ". $license_level_id ." "
         . "            AND pigi.effective_date <= NOW() AND pigi.expire_date >= NOW() "
         . "WHERE pig.status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * GetGroupDiscountByPartner()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Dec 05 17:50:14 PST 2005
   */
   function GetGroupDiscountByPartner($partner_id)
   {
       $q = "SELECT pig.pricing_item_group_id, pig.pricing_item_group_description, pigd_c.discount_amount AS contracted,  "
         . "       pigd_nc.discount_amount AS non_contracted, pigd_p.discount_amount AS promotional, pigi.inflator_amount AS inflator "
         . "FROM pricing_item_group AS pig "
         . "LEFT OUTER JOIN partner_pricing_item_group_discount AS pigd_c ON pigd_c.pricing_item_group_id = pig.pricing_item_group_id "
         . "            AND pigd_c.partner_id = ". $partner_id ." "
         . "            AND pigd_c.pricing_discount_type_id = ". PRICING_DISCOUNT_CONTRACT ." "
         . "            AND pigd_c.effective_date <= NOW() AND pigd_c.expire_date >= NOW() "
         . "LEFT OUTER JOIN partner_pricing_item_group_discount AS pigd_nc ON pigd_nc.pricing_item_group_id = pig.pricing_item_group_id "
         . "            AND pigd_nc.partner_id = ". $partner_id ." "
         . "            AND pigd_nc.pricing_discount_type_id = ". PRICING_DISCOUNT_NON_CONTRACT ." "
         . "            AND pigd_nc.effective_date <= NOW() AND pigd_nc.expire_date >= NOW() "
         . "LEFT OUTER JOIN partner_pricing_item_group_discount AS pigd_p ON pigd_p.pricing_item_group_id = pig.pricing_item_group_id "
         . "            AND pigd_p.partner_id = ". $partner_id ." "
         . "            AND pigd_p.pricing_discount_type_id = ". PRICING_DISCOUNT_PROMOTIONAL ." "
         . "            AND pigd_p.effective_date <= NOW() AND pigd_p.expire_date >= NOW() "
         . "LEFT OUTER JOIN partner_pricing_item_group_inflator AS pigi ON pigi.pricing_item_group_id = pig.pricing_item_group_id "
         . "            AND pigi.partner_id = ". $partner_id ." "
         . "            AND pigi.effective_date <= NOW() AND pigi.expire_date >= NOW() "
         . "WHERE pig.status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * SetRevisionGroupDiscount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Dec 05 18:00:49 PST 2005
   */
   function SetRevisionGroupDiscount($pricing_item_group_id, $proposal_revision_id, $inflator, $contracted_discount,
                        $non_contracted_discount, $promotional_discount, $ad_hoc_discount = 0)
   {
      $q = "INSERT INTO proposal_revision_group_discount "
         . "        (pricing_item_group_id, proposal_revision_id, inflator, contracted_discount, non_contracted_discount, "
         . "         promotional_discount, ad_hoc_discount, created_by, created_date, status) "
         . "VALUES (". $pricing_item_group_id .", ". $proposal_revision_id .", '". $inflator ."', '". $contracted_discount ."', "
         . "       '". $non_contracted_discount ."', '". $promotional_discount ."', '". $ad_hoc_discount ."', ". $this->created_by .", NOW(), 'A' ) ";
      return $this->executeQuery($q);
   }

   /**
   * GetRevisionGroupDiscount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Dec 05 18:15:26 PST 2005
   */
   function GetRevisionGroupDiscountByGroup($pricing_item_group_id)
   {
      $q = "SELECT prgd.proposal_revision_group_discount_id, prgd.pricing_item_group_id, prgd.inflator, "
         . "       prgd.contracted_discount, prgd.non_contracted_discount, prgd.promotional_discount, prgd.ad_hoc_discount "
         . "FROM proposal_revision_group_discount AS prgd "
         . "LEFT OUTER JOIN pricing_item_group AS pig ON pig.pricing_item_group_id = prgd.pricing_item_group_id "
         . "WHERE prgd.status = 'A' AND prgd.proposal_revision_id = ". $this->__revision ." "
         . "  AND prgd.pricing_item_group_id = ". $pricing_item_group_id;
      return $this->executeQuery($q);
   }

   /**
   * GetServiceListByGroup()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Dec 06 13:59:53 PST 2005
   */
   function GetServiceList()
   {
      $q = "SELECT s.service_description, s.service_id, pig.pricing_item_group_description, s.pricing_item_group_id "
         . "FROM service AS s "
         . "LEFT OUTER JOIN pricing_item_group AS pig ON pig.pricing_item_group_id = s.pricing_item_group_id "
         . "WHERE s.status = 'A' "
         . "ORDER BY pig.pricing_item_group_id, s.sort_order ";
      return $this->executeQuery($q);
   }

   /**
   * UpdateRevision()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Dec 06 22:57:57 PST 2005
   */
   function UpdateRevision($proposal_revision_id, $study_interview_type_id, $study_setup_duration_id, $study_fieldwork_duration_id, $study_data_processing_duration_id,
                     $proposal_option_type_id, $proposal_type_id, $number_of_countries, $number_of_options, $pricing_type_id )
   {
      $q = "UPDATE proposal_revision "
         . "SET study_interview_type_id = ". $study_interview_type_id .", "
         . "    study_setup_duration_id = ". $study_setup_duration_id .", "
         . "    study_fieldwork_duration_id = ". $study_fieldwork_duration_id .", "
         . "    study_data_processing_duration_id = ". $study_data_processing_duration_id .", "
         . "    proposal_option_type_id = ". $proposal_option_type_id .", "
         . "    proposal_type_id = ". $proposal_type_id .", "
         . "    number_of_countries = ". $number_of_countries .", "
         . "    number_of_options = ". $number_of_options .", "
         . "    pricing_type_id = ". $pricing_type_id .", "
         . "    modified_by = ". $this->created_by .", "
         . "    modified_date = NOW() "
         . "WHERE proposal_revision_id = ". $proposal_revision_id;
      return $this->executeQuery($q);
   }

   /**
   * UpdateRevisionComment()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Dec 06 23:02:32 PST 2005
   */
   function UpdateRevisionComment($proposal_revision_id, $proposal_revision_comment_type_id, $comment)
   {
      $q = "UPDATE proposal_revision_comment "
         . "SET comment = '". mysql_real_escape_string($comment) ."', "
         . "    modified_by = ". $this->created_by .", "
         . "    modified_date = NOW() "
         . "WHERE proposal_revision_id = ". $proposal_revision_id . " AND proposal_revision_comment_type_id = ". $proposal_revision_comment_type_id;
      return $this->executeQuery($q);
   }

   /**
   * DeleteRevisionSampleType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Dec 06 23:14:47 PST 2005
   */
   function DeleteRevisionSampleType($proposal_revision_id)
   {
      $q = "DELETE FROM proposal_revision_sample_type WHERE proposal_revision_id = ". $proposal_revision_id;
      return $this->executeQuery($q);
   }

   /**
   * UpdateRevisionPriceByRevision()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Dec 06 23:22:21 PST 2005
   */
   function UpdateRevisionPriceByRevision($proposal_revision_id, $pricing_item_id, $license_level_price, $inflator, $contracted_discount,
                     $non_contracted_discount, $promotional_discount, $ad_hoc_discount, $net_price)
   {
      $q = "UPDATE proposal_revision_pricing "
         . "SET license_level_price = '". $license_level_price ."', "
         . "    inflator = '". $inflator ."', "
         . "    contracted_discount = '". $contracted_discount ."', "
         . "    non_contracted_discount = '". $non_contacted_discount ."', "
         . "    promotional_discount = '". $promotional_discount ."', "
         . "    ad_hoc_discount = '". $ad_hoc_discount ."', "
         . "    net_price = '". $net_price ."', "
         . "    modified_by = ". $this->created_by .", "
         . "    modified_date = NOW() "
         . "WHERE proposal_revision_id = ". $proposal_revision_id ." AND pricing_item_id = ". $pricing_item_id;
      return $this->executeQuery($q);
   }

   /**
   * UpdateRevisionPanelCostByRevision()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Dec 06 23:30:37 PST 2005
   */
   function UpdateRevisionPanelCostByRevision($proposal_revision_id, $panel_cost_type_id, $inflator, $contracted_discount,
                  $non_contracted_discount, $promotional_discount, $ad_hoc_discount = 0)
   {
      $q = "UPDATE proposal_revision_panel_cost "
         . "SET inflator = '". $inflator ."', "
         . "    contracted_discount = '". $contracted_discount ."', "
         . "    non_contracted_discount = '". $non_contracted_discount ."', "
         . "    promotional_discount = '". $promotional_discount ."', "
         . "    ad_hoc_discount = '". $ad_hoc_discount ."', "
         . "    modified_by = ". $this->created_by .", "
         . "    modified_date = NOW() "
         . "WHERE panel_cost_type_id = ". $panel_cost_type_id ." AND proposal_revision_id = ". $proposal_revision_id;
      return $this->executeQuery($q);
   }

   /**
   * UpdateRevisionGroupDiscountByRevision()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Dec 06 23:35:49 PST 2005
   */
   function UpdateRevisionGroupDiscountByRevision($proposal_revision_id, $pricing_item_group_id, $inflator, $contracted_discount,
                     $non_contracted_discount, $promotional_discount, $ad_hoc_discount = 0)
   {
      $q = "UPDATE proposal_revision_group_discount "
         . "SET inflator = '". $inflator ."', "
         . "    contracted_discount = '". $contracted_discount ."', "
         . "    non_contracted_discount = '". $non_contracted_discount ."', "
         . "    promotional_discount = '". $promotional_discount ."', "
         . "    ad_hoc_discount = '". $ad_hoc_discount ."', "
         . "    modified_by = ". $this->created_by .", "
         . "    modified_date = NOW() "
         . "WHERE proposal_revision_id = ". $proposal_revision_id ." AND pricing_item_group_id = ". $pricing_item_group_id;
      return $this->executeQuery($q);
   }

   /**
   * SetRevisionService()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 07 10:50:50 PST 2005
   */
   function SetRevisionService($proposal_revision_id, $service_id)
   {
      $q = "INSERT INTO proposal_revision_service "
         . "    (proposal_revision_id, service_id, created_by, created_date, status) "
         . "VALUES (". $proposal_revision_id .", ". $service_id .", ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
   }

   /**
   * DeleteRevisionServiceList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 07 10:53:09 PST 2005
   */
   function DeleteRevisionServiceList($proposal_revision_id)
   {
      $q = "DELETE FROM proposal_revision_service WHERE proposal_revision_id = ". $proposal_revision_id;
      return $this->executeQuery($q);
   }

   /**
   * GetServiceListByRevision()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 07 10:58:57 PST 2005
   */
   function GetServiceListByRevision()
   {
      $q = "SELECT s.service_description, s.service_id, pig.pricing_item_group_description, s.pricing_item_group_id, "
         . "       IF(prs.service_id IS NULL, '', 'checked') AS is_selected "
         . "FROM service AS s "
         . "LEFT OUTER JOIN pricing_item_group AS pig ON pig.pricing_item_group_id = s.pricing_item_group_id "
         . "LEFT OUTER JOIN proposal_revision_service AS prs ON prs.service_id = s.service_id "
         . "            AND prs.proposal_revision_id = ". $this->__revision ." "
         . "WHERE s.status = 'A' "
         . "ORDER BY pig.pricing_item_group_id, s.sort_order ";
      return $this->executeQuery($q);
   }

   /**
   * GetRevisionSampleTypeIds()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 07 11:06:49 PST 2005
   */
   function GetRevisionSampleTypeIds($proposal_revision_id)
   {
      $q = "SELECT sample_type_id "
         . "FROM proposal_revision_sample_type "
         . "WHERE status = 'A' AND proposal_revision_id = ". $proposal_revision_id;
      return $this->executeQuery($q);
   }

   /**
   * GetRevisionServiceList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 07 12:24:45 PST 2005
   */
   function GetRevisionServiceList()
   {
      $q = "SELECT prs.service_id "
         . "FROM proposal_revision_service AS prs "
         . "WHERE prs.status = 'A' AND prs.proposal_revision_id = ". $this->__revision;
      return $this->executeQuery($q);
   }

   /**
   * GetRespondentPortalTypes()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Dec 08 14:17:21 PST 2005
   */
   function GetRespondentPortalTypes()
   {
      $q = "SELECT rpt.respondent_portal_type_id, rpt.respondent_portal_type_description "
         . "FROM respondent_portal_type AS rpt "
         . "WHERE status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * DeleteRevisionOptionBudgetLineItem()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Dec 08 19:46:23 PST 2005
   */
   function DeleteRevisionOptionBudgetLineItem($proposal_revision_option_id, $proposal_budget_line_item_id)
   {
      $q = "DELETE FROM proposal_revision_budget_line_item "
         . "WHERE proposal_revision_option_id = ". $proposal_revision_option_id ." AND proposal_budget_line_item_id = ". $proposal_budget_line_item_id;
      return $this->executeQuery($q);
   }

   /**
   * GetProposalId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Dec 12 09:27:29 PST 2005
   */
   function GetProposalId()
   {
      $q = "SELECT proposal_id "
         . "FROM proposal_revision "
         . "WHERE proposal_revision_id = ". $this->__revision;
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return $r['proposal_id'];
   }

   /**
   * GetRevisionDetail()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Dec 12 09:31:15 PST 2005
   */
   function GetRevisionDetail()
   {
      $q = "SELECT pr.proposal_type_id, pt.proposal_type_description, pr.min_amount, pr.max_amount, "
         . "       pr.study_interview_type_id, sit.study_interview_type_description, "
         . "       pr.study_fieldwork_duration_id, sfd.study_fieldwork_duration_description, "
         . "       pr.proposal_option_type_id, pot.proposal_option_type_description,  "
         . "       pr.number_of_countries, pr.number_of_options, pr.proposal_revision_id, pr.proposal_id,  "
         . "       pr.study_setup_duration_id, ssd.study_setup_duration_description, "
         . "       pr.study_data_processing_duration_id, sdpd.study_data_processing_duration_description, "
         . "       pr.pricing_type_id, pr_t.pricing_type_description, u.last_name AS created_by_name, CONVERT_TZ(pr.created_date,'+00:00','". $this->tz ."') AS created_date, "
         . "       pr.proposal_revision_status_id, pr.revision, prs.proposal_revision_status_description, pr.created_by "
         . "FROM proposal_revision AS pr "
         . "LEFT OUTER JOIN proposal_type AS pt ON pt.proposal_type_id = pr.proposal_type_id "
         . "LEFT OUTER JOIN study_interview_type AS sit ON sit.study_interview_type_id = pr.study_interview_type_id "
         . "LEFT OUTER JOIN study_fieldwork_duration AS sfd ON sfd.study_fieldwork_duration_id = pr.study_fieldwork_duration_id "
         . "LEFT OUTER JOIN proposal_option_type AS pot ON pot.proposal_option_type_id = pr.proposal_option_type_id "
         . "LEFT OUTER JOIN study_setup_duration AS ssd ON ssd.study_setup_duration_id = pr.study_setup_duration_id "
         . "LEFT OUTER JOIN study_data_processing_duration AS sdpd ON sdpd.study_data_processing_duration_id = pr.study_data_processing_duration_id "
         . "LEFT OUTER JOIN pricing_type AS pr_t ON pr_t.pricing_type_id = pr.pricing_type_id "
         . "LEFT OUTER JOIN proposal_revision_status AS prs ON prs.proposal_revision_status_id = pr.proposal_revision_status_id "
         . "LEFT OUTER JOIN user AS u ON u.login = pr.created_by "
         . "WHERE pr.proposal_revision_id = ". $this->__revision;
      return mysql_fetch_assoc($this->executeQuery($q));
   }

   /**
   * GetRevisionSampleTypes()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Dec 12 09:48:41 PST 2005
   */
   function GetRevisionSampleTypes()
   {
      $q = "SELECT prst.sample_type_id, st.sample_type_description "
         . "FROM proposal_revision_sample_type AS prst "
         . "LEFT OUTER JOIN sample_type AS st ON st.sample_type_id = prst.sample_type_id "
         . "WHERE prst.proposal_revision_id = ". $this->__revision;
      return $this->executeQuery($q);
   }
   
   function GetSampleTypePricingLists($sample_type_id) {
      $q = "SELECT list_id, list_description FROM sample_type_pricing WHERE sample_type_id='$sample_type_id' GROUP BY list_id ORDER BY list_id";
      return $this->executeQuery($q);   
   }

   function GetSampleTypePricingItems($sample_type_id, $list_id) {
      $q = "SELECT sample_type_pricing_id, item_id, item_description, premium FROM sample_type_pricing WHERE sample_type_id = '$sample_type_id' AND list_id='$list_id' ORDER BY item_description";
      return $this->executeQuery($q);
   }
   
   function GetSampleTypePricingItem($sample_type_pricing_id) {
      $q = "SELECT sample_type_pricing_id, item_id, item_description, premium FROM sample_type_pricing WHERE sample_type_pricing_id = '$sample_type_pricing_id'";
      return $this->executeQuery($q);      
   }

   /**
   * GetRevisionComment()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Dec 12 09:55:18 PST 2005
   */
   function GetRevisionComment($proposal_revision_comment_type_id)
   {
      $q = "SELECT comment "
         . "FROM proposal_revision_comment "
         . "WHERE status = 'A' AND proposal_revision_id = ". $this->__revision ." AND proposal_revision_comment_type_id = ". $proposal_revision_comment_type_id;
      return $this->executeQuery($q);
   }
   
   /**
   * GetRevisionComments()
   *
   * @param
   * @param - 
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Jan 05 18:05:17 PST 2006
   */
   function GetRevisionComments()
   {
      $q = "SELECT comment, proposal_revision_comment_type_id, created_by "
         . "FROM proposal_revision_comment "
         . "WHERE status = 'A' AND proposal_revision_id = ". $this->__revision;
      return $this->executeQuery($q);
   }

   /**
   * GetRevisionFileByType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Dec 12 10:01:18 PST 2005
   */
   function GetRevisionFilesByType($proposal_file_type_id)
   {
      $q = "SELECT proposal_revision_file_id, file_name, file_size "
         . "FROM proposal_revision_file "
         . "WHERE proposal_revision_id = ". $this->__revision ." AND proposal_file_type_id = ". $proposal_file_type_id;
      return $this->executeQuery($q);
   }

   /**
   * GetRevisionServiceList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Dec 12 10:23:17 PST 2005
   */
   function GetDetailedRevisionServiceList()
   {
       $q = "SELECT s.service_description, s.service_id, pig.pricing_item_group_description, s.pricing_item_group_id, "
         . "       IF(prs.service_id IS NULL, '', 'checked') AS is_selected "
         . "FROM proposal_revision_service AS prs "
         . "LEFT OUTER JOIN service AS s ON s.service_id = prs.service_id "
         . "LEFT OUTER JOIN pricing_item_group AS pig ON pig.pricing_item_group_id = s.pricing_item_group_id "
         . "WHERE s.status = 'A' AND prs.proposal_revision_id = ". $this->__revision ." "
         . "ORDER BY pig.pricing_item_group_id, s.sort_order ";
      return $this->executeQuery($q);
   }

   /**
   * GetRevisionCount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Dec 12 17:54:37 PST 2005
   */
   function GetRevisionCount()
   {
      $q = "SELECT count(proposal_revision_id) AS revision_count "
         . "FROM proposal_revision "
         . "WHERE status = 'A' AND proposal_id = ". $this->__id;
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return $r['revision_count'];
   }

   /**
   * GetPanelCostIdByRegimeCountry()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Dec 15 22:00:55 PST 2005
   */
   function GetPanelCostIdByCostRegimeCountry($panel_cost_type_id, $pricing_regime_id, $country_tier_id)
   {
      $q = "SELECT pc.panel_cost_id "
         . "FROM panel_cost AS pc "
         . "WHERE pc.panel_cost_type_id = ". $panel_cost_type_id ." AND pc.pricing_regime_id = ". $pricing_regime_id ." "
         . "  AND pc.country_tier_id = ". $country_tier_id ." AND pc.effective_date <= NOW() AND pc.expire_date >= NOW() "
         . "  AND pc.status = 'A' ";
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return ($r) ? $r['panel_cost_id'] : false;
   }

   /**
   * GetPanelCostAttrByPanelCostId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Dec 15 22:06:34 PST 2005
   */
   function GetPanelCostAttrByPanelCostId($panel_cost_id)
   {
      $q = "SELECT pca.panel_cost_id, pca.panel_cost_attr_name, pca.panel_cost_attr_value "
         . "FROM panel_cost_attr AS pca "
         . "WHERE pca.panel_cost_id = ". $panel_cost_id ." AND pca.status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * GetPricingTypes()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Dec 20 10:31:47 PST 2005
   */
   function GetPricingTypes()
   {
      $q = "SELECT pt.pricing_type_id, pt.pricing_type_description "
         . "FROM pricing_type AS pt "
         . "WHERE pt.status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * GetPricingItemGroups()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Dec 20 13:24:13 PST 2005
   */
   function GetPricingItemGroups()
   {
      $q = "SELECT pig.pricing_item_group_id, pig.pricing_item_group_description "
         . "FROM pricing_item_group AS pig "
         . "LEFT OUTER JOIN service AS s ON s.pricing_item_group_id = pig.pricing_item_group_id "
         . "LEFT OUTER JOIN proposal_revision_service AS prs ON prs.service_id = s.service_id "
         . "WHERE pig.status = 'A' AND prs.proposal_revision_id = ". $this->__revision ." "
         . "GROUP BY s.pricing_item_group_id ";
      return $this->executeQuery($q);
   }

   /**
   * SetRevisionCustomPrice()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Dec 20 14:00:19 PST 2005
   */
   function SetRevisionCustomPrice($proposal_revision_option_id, $pricing_item_group_id, $amount)
   {
      $q = "INSERT INTO proposal_revision_custom_pricing (proposal_revision_option_id, pricing_item_group_id, amount, created_by, created_date, status) "
         . "VALUES (". $proposal_revision_option_id .", ". $pricing_item_group_id .", '". $amount ."', ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
   }

   /**
   * GetRevisionCustomPricing()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Dec 20 14:31:15 PST 2005
   */
   function GetRevisionCustomPricing()
   {
      $q = "SELECT prcp.amount, prcp.pricing_item_group_id, pro.option_number, pro.sort_order, prcp.proposal_revision_custom_pricing_id "
         . "FROM proposal_revision AS pr "
         . "LEFT OUTER JOIN proposal_revision_option AS pro ON pro.proposal_revision_id = pr.proposal_revision_id "
         . "LEFT OUTER JOIN proposal_revision_custom_pricing AS prcp ON prcp.proposal_revision_option_id = pro.proposal_revision_option_id "
         . "WHERE pr.proposal_revision_id = ". $this->__revision;
      return $this->executeQuery($q);
   }

   /**
   * GetRevisionCustomPriceByOptionId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Dec 20 14:31:15 PST 2005
   */
   function GetRevisionCustomPriceByOptionId($proposal_revision_option_id)
   {
      $q = "SELECT prcp.amount, prcp.pricing_item_group_id, pro.option_number, pro.sort_order, prcp.proposal_revision_custom_pricing_id "
         . "FROM proposal_revision AS pr "
         . "LEFT OUTER JOIN proposal_revision_option AS pro ON pro.proposal_revision_id = pr.proposal_revision_id "
         . "LEFT OUTER JOIN proposal_revision_custom_pricing AS prcp ON prcp.proposal_revision_option_id = pro.proposal_revision_option_id "
         . "WHERE pr.proposal_revision_id = ". $this->__revision ." AND prcp.proposal_revision_option_id = ". $proposal_revision_option_id;
      return $this->executeQuery($q);
   }
   
   /**
   * UpdateRevisionCustomPrice()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Dec 20 15:36:24 PST 2005
   */
   function UpdateRevisionCustomPrice($proposal_revision_custom_pricing_id, $amount)
   {
      $q = "UPDATE proposal_revision_custom_pricing "
         . "SET amount = '". $amount ."', "
         . "    modified_by = ". $this->created_by .", "
         . "    modified_date = NOW(), "
         . "    status = 'A' "
         . "WHERE proposal_revision_custom_pricing_id = ". $proposal_revision_custom_pricing_id;
      return $this->executeQuery($q);
   }

   /**
   * SetPanelCost()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 21 09:58:46 PST 2005
   */
   function SetPanelCost($panel_cost_type_id, $pricing_regime_id, $country_tier_id, $effective_date = 'NOW()', $expire_date = '2050-12-31')
   {
      $q = "INSERT INTO panel_cost (panel_cost_type_id, pricing_regime_id, country_tier_id, effective_date, expire_date, created_by, created_date, status) "
         . "VALUES (". $panel_cost_type_id .", ". $pricing_regime_id .", ". $country_tier_id .", ". $effective_date .", '". $expire_date ."', ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
   }

   /**
   * SetPanelCostAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 21 10:01:55 PST 2005
   */
   function SetPanelCostAttr($panel_cost_id, $panel_cost_attr_name, $panel_cost_attr_value)
   {
      $q = "INSERT INTO panel_cost_attr (panel_cost_id, panel_cost_attr_name, panel_cost_attr_value, created_by, created_date, status ) "
         . "VALUES (". $panel_cost_id .", '". $panel_cost_attr_name ."', '". $panel_cost_attr_value ."', ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
   }

   /**
   * SetRevisionMaxPrice()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 21 20:01:33 PST 2005
   */
   function SetRevisionMaxPrice($max_amount)
   {
      $q = "UPDATE proposal_revision "
         . "SET max_amount = '". $max_amount ."', "
         . "    modified_by = ". $this->created_by .", "
         . "    modified_date = NOW() "
         . "WHERE proposal_revision_id = ". $this->__revision;
      return $this->executeQuery($q);
   }

   /**
   * SetRevisionMaxPrice()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 21 20:01:33 PST 2005
   */
   function SetRevisionMinPrice($min_amount)
   {
      $q = "UPDATE proposal_revision "
         . "SET min_amount = '". $min_amount ."', "
         . "    modified_by = ". $this->created_by .", "
         . "    modified_date = NOW() "
         . "WHERE proposal_revision_id = ". $this->__revision;
      return $this->executeQuery($q);
   }

   /**
   * GetRevisionMaxPrice()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 21 20:11:27 PST 2005
   */
   function GetRevisionMaxPrice()
   {
      $q = "SELECT max_amount "
         . "FROM proposal_revision "
         . "WHERE proposal_revision_id = ". $this->__revision;
      return mysql_fetch_assoc($this->executeQuery($q));
   }
   
   /**
   * GetReviewGroupMembersByGroupId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - 
   */
   function GetReviewGroupMembersByGroupId($proposal_review_group_id)
   {
      $q = "SELECT prgl.login, u.first_name, u.last_name, u.email_address "
         . "FROM proposal_review_group_login AS prgl "
         . "LEFT OUTER JOIN user AS u ON u.login = prgl.login "
         . "WHERE prgl.status = 'A' AND prgl.proposal_review_group_id = ". $proposal_review_group_id;
      return $this->executeQuery($q);
   }
   
   /**
   * SetRevisionReviewLog()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 21 21:25:13 PST 2005
   */
   function SetRevisionReviewLog($proposal_review_group_id, $login)
   {
      $q = "INSERT INTO proposal_revision_review_log (proposal_revision_id, proposal_review_group_id, login, notification_sent_date, created_by, created_date, status) "
         . "VALUES (". $this->__revision .", ". $proposal_review_group_id .", ". $login .", NOW(), ". $this->created_by .", NOW(), 'A') ";
      return $this->executeQuery($q);
      
   }
   
   /**
   * UpdateRevisionStatus()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 21 21:52:25 PST 2005
   */
   function UpdateRevisionStatus($proposal_revision_status_id)
   {
      $q = "UPDATE proposal_revision "
         . "SET proposal_revision_status_id = ". $proposal_revision_status_id .", "
         . "    modified_by = ". $this->created_by .", "
         . "    modified_date = NOW() "
         . "WHERE proposal_revision_id = ". $this->__revision;
      return $this->executeQuery($q);
   }
   
   /**
   * isUserOnRevisionApprovalList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 21 22:06:17 PST 2005
   */
   function isUserOnRevisionApprovalList($login)
   {
      $q = "SELECT prrg.login, prrg.proposal_review_group_id "
         . "FROM proposal_revision_review_log AS prrg "
         . "WHERE prrg.proposal_revision_id = ". $this->__revision ." AND prrg.login = ". $login ." "
         . "  AND NOT EXISTS (SELECT pra.proposal_revision_action_id "
         . "                  FROM proposal_revision_action AS pra "
         . "                  WHERE pra.proposal_review_group_id = prrg.proposal_review_group_id AND pra.is_copy = 0 "
         . "                    AND pra.proposal_revision_id = ". $this->__revision ." AND pra.proposal_action_id = ". PROPOSAL_ACTION_APPROVED .") ";
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return ($r) ? true : false;
   }
   
   /**
   * GetProposalActionList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 21 22:24:03 PST 2005
   */
   function GetProposalActionList()
   {
      $q = "SELECT proposal_action_id, proposal_action_description "
         . "FROM proposal_action "
         . "WHERE status = 'A' ";
      return $this->executeQuery($q);
   }
   
   /**
   * SetRevisionAction()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 21 23:03:07 PST 2005
   */
   function SetRevisionAction($proposal_review_group_id, $login, $proposal_action_id, $action_date, $action_comment, $is_copy = 0)
   {
       $q = "INSERT INTO proposal_revision_action (proposal_revision_id, proposal_review_group_id, login, 
                  proposal_action_id, action_date, action_comment, is_copy, created_by, created_date, status) "
         . "VALUES (". $this->__revision .", ". $proposal_review_group_id .", ". $login .", ". $proposal_action_id .", "
         . "        ". $action_date .", '". mysql_real_escape_string($action_comment) ."', ". $is_copy .", ". $this->created_by .", NOW(), 'A') ";
       return $this->executeQuery($q);
   }
   
   /**
   * GetProposalRevisionActionList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 21 23:24:55 PST 2005
   */
   function GetRevisionActionList()
   {
      $q = "SELECT pra.action_date, pra.action_comment, u.first_name, u.last_name, prg.proposal_review_group_description, "
         . "       pa.proposal_action_description, pra.login, pra.proposal_review_group_id, pra.proposal_action_id "
         . "FROM proposal_revision_action AS pra "
         . "LEFT OUTER JOIN user AS u ON u.login = pra.login "
         . "LEFT OUTER JOIN proposal_review_group AS prg ON prg.proposal_review_group_id = pra.proposal_review_group_id "
         . "LEFT OUTER JOIN proposal_action AS pa ON pa.proposal_action_id = pra.proposal_action_id "
         . "WHERE pra.proposal_revision_id = ". $this->__revision;
      return $this->executeQuery($q);
   }
   
   /**
   * GetRevisionGroupDiscount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Dec 23 00:05:17 PST 2005
   */
   function GetRevisionGroupDiscount()
   {
      $q = "SELECT prgd.proposal_revision_group_discount_id, prgd.pricing_item_group_id, prgd.inflator, prgd.contracted_discount, "
         . "       prgd.non_contracted_discount, prgd.promotional_discount, prgd.ad_hoc_discount "
         . "FROM proposal_revision_group_discount AS prgd "
         . "WHERE prgd.proposal_revision_id = ". $this->__revision;
      return $this->executeQuery($q);
   }
   
   /**
   * UpdateRevisionGroupAdHocDiscount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Dec 23 00:18:28 PST 2005
   */
   function UpdateRevisionGroupAdHocDiscountByPricingGroup($pricing_item_group_id, $ad_hoc_amount)
   {
      $q = "UPDATE proposal_revision_group_discount "
         . "SET ad_hoc_discount = '". $ad_hoc_amount ."', "
         . "    modified_by = ". $this->created_by .", "
         . "    modified_date = NOW() "
         . "WHERE pricing_item_group_id = ". $pricing_item_group_id ." AND proposal_revision_id = ". $this->__revision;
      return $this->executeQuery($q);
   }
   
   /**
   * UpdateRevisionPanelCostAdHocDiscount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Dec 23 00:49:45 PST 2005
   */
   function UpdateRevisionPanelCostAdHocDiscount($proposal_revision_panel_cost_id, $ad_hoc_discount)
   {
      $q = "UPDATE proposal_revision_panel_cost "
         . "SET ad_hoc_discount = '". $ad_hoc_discount ."', "
         . "    modified_by = ". $this->created_by .", "
         . "    modified_date = NOW() "
         . "WHERE proposal_revision_panel_cost_id = ". $proposal_revision_panel_cost_id;
      return $this->executeQuery($q);
   }
   
   /**
   * GetRevisionIdByRevision()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Dec 23 13:20:33 PST 2005
   */
   function GetRevisionIdByRevision($revision)
   {
      $q = "SELECT proposal_revision_id "
         . "FROM proposal_revision "
         . "WHERE status = 'A' AND proposal_id = ". $this->__id ." AND revision = ". $revision;
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return $r['proposal_revision_id'];
   }
   
   /**
   * UpdateRevisionAdHocDiscount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Dec 23 13:40:21 PST 2005
   */
   function UpdateRevisionAdHocDiscount($pricing_item_id, $ad_hoc_discount, $net_price)
   {
      $q = "UPDATE proposal_revision_pricing "
         . "SET ad_hoc_discount = '". $ad_hoc_discount ."', "
         . "    net_price = '". $net_price ."', "
         . "    modified_by = ". $this->created_by .", "
         . "    modified_date = NOW() "
         . "WHERE pricing_item_id = ". $pricing_item_id ." AND proposal_revision_id = ". $this->__revision;
      return $this->executeQuery($q);
   }
   
   /**
   * UpdateRevisionGroupAdHocDiscount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Dec 23 13:57:11 PST 2005
   */
   function UpdateRevisionGroupAdHocDiscount($proposal_revision_group_discount_id, $ad_hoc_discount)
   {
      $q = "UPDATE proposal_revision_group_discount "
         . "SET ad_hoc_discount = '". $ad_hoc_discount ."', "
         . "    modified_by = ". $this->created_by .", "
         . "    modified_date = NOW() "
         . "WHERE proposal_revision_group_discount_id = ". $proposal_revision_group_discount_id;
      return $this->executeQuery($q);
   }
   
   /**
   * UpdateRevisionPanelCostAdHocDiscountByPanelType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Dec 23 14:05:04 PST 2005
   */
   function UpdateRevisionPanelCostAdHocDiscountByPanelType($panel_cost_type_id, $ad_hoc_discount)
   {
      $q = "UPDATE proposal_revision_panel_cost "
         . "SET ad_hoc_discount = '". $ad_hoc_discount ."', "
         . "    modified_by = ". $this->created_by .", "
         . "    modified_date = NOW() "
         . "WHERE proposal_revision_id = ". $this->__revision ." AND panel_cost_type_id = ". $panel_cost_type_id;
      return $this->executeQuery($q);     
   }
   
   /**
   * GetRevisionReviewGroups()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Dec 23 18:47:05 PST 2005
   */
   function GetRevisionReviewGroups()
   {
      $q = "SELECT prrl.proposal_review_group_id "
         . "FROM proposal_revision_review_log AS prrl "
         . "WHERE prrl.proposal_revision_id = ". $this->__revision ." "
         . "GROUP BY prrl.proposal_review_group_id ";
      return $this->executeQuery($q);
   }
   
   /**
   * GetRemainingReviewGroups()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Dec 23 18:59:57 PST 2005
   */
   function GetRemainingReviewGroups()
   {
      $q = "SELECT prrl.proposal_review_group_id "
         . "FROM proposal_revision_review_log AS prrl "
         . "WHERE prrl.proposal_revision_id = ". $this->__revision ." "
         . "  AND NOT EXISTS (SELECT pra.proposal_review_group_id "
         . "                  FROM proposal_revision_action AS pra  "
         . "                  WHERE pra.proposal_review_group_id = prrl.proposal_review_group_id AND pra.proposal_revision_id = ". $this->__revision .") ";
      return $this->executeQuery($q);
   }
   
   /**
   * GetRevisionOptionByOptionNumber()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Dec 24 04:48:17 PST 2005
   */
   function GetRevisionOptionByOptionNumber($option_number)
   {
      $q = "SELECT pro.proposal_revision_option_id, pro.proposal_revision_id, pro.country_code, pro.option_number, pro.sub_group_description, "
         . "       pro.study_programming_type_id, pro.translation, pro.translation_language_code, pro.overlay, pro.overlay_language_code, "
         . "       pro.study_datasource_id, pro.incidence_rate, pro.completes, pro.questions_programmed, pro.questions_per_interview,  "
         . "       pro.questions_per_screener, pro.data_recording_hours, pro.data_tab_hours, pro.data_export_hours, pro.data_import_hours, pro.open_end_questions, "
         . "       pro.incidence_of_open_end, pro.avg_words_per_open_end, pro.open_end_text_coding_hours, c.country_description, sd.study_datasource_description, "
         . "       pro.respondent_portal_type_id, pro.respondent_portal_programming_hours, pro.client_portal_programming_hours, pro.panel_import_hours, spt.study_programming_type_description,  "
         . "       rpt.respondent_portal_type_description, pro.sort_order "
         . "FROM proposal_revision_option AS pro "
         . "LEFT OUTER JOIN country AS c ON c.country_code = pro.country_code "
         . "LEFT OUTER JOIN study_datasource AS sd ON sd.study_datasource_id = pro.study_datasource_id "
         . "LEFT OUTER JOIN study_programming_type AS spt ON spt.study_programming_type_id = pro.study_programming_type_id "
         . "LEFT OUTER JOIN respondent_portal_type AS rpt ON rpt.respondent_portal_type_id = pro.respondent_portal_type_id "
         . "WHERE pro.proposal_revision_id = ". $this->__revision ." AND option_number = ". $option_number ." "
         . "ORDER BY pro.sort_order ";
      return $this->executeQuery($q);
      
   }
   
   /**
   * GetRevisionReviewLog()
   *
   * @param
   * @param - 
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 28 12:10:27 PST 2005
   */
   function GetRevisionReviewLog()
   {
      $q = "SELECT prrl.login, u.first_name, u.last_name, u.email_address "
         . "FROM proposal_revision_review_log AS prrl "
         . "LEFT OUTER JOIN user AS u ON u.login = prrl.login "
         . "WHERE prrl.proposal_revision_id = ". $this->__revision;
      return $this->executeQuery($q);
   }
   
   /**
   * GetActionDescription()
   *
   * @param
   * @param - 
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Dec 28 12:23:01 PST 2005
   */
   function GetActionDescription($proposal_action_id)
   {
      $q = "SELECT proposal_action_description "
         . "FROM proposal_action "
         . "WHERE proposal_action_id = ". $proposal_action_id;
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return $r['proposal_action_description'];
   }
   
   /**
   * isAttrSet()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Dec 29 22:49:58 PST 2005
   */
   function isAttrSet($proposal_attr_name)
   {
      $q = "SELECT proposal_attr_value "
         . "FROM proposal_attr "
         . "WHERE proposal_id = ". $this->__id ." AND proposal_attr_name = '". $proposal_attr_name ."' ";
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return ($r) ? true : false;
   }
   
   /**
   * DeleteRevisionOptionById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jan 07 15:57:48 PST 2006
   */
   function DeleteRevisionOptionById($proposal_revision_option_id)
   {
   	$q = "DELETE FROM proposal_revision_option WHERE proposal_revision_option_id = ". $proposal_revision_option_id;
   	return $this->executeQuery($q);
   }
   
   /**
   * UpdateProposalStatus()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sat Jan 07 21:36:08 PST 2006
   */
   function UpdateProposalStatus($proposal_status_id)
   {
   	$q = "UPDATE proposal "
   	   . "SET proposal_status_id = ". $proposal_status_id .", "
   	   . "    modified_by = ". $this->created_by .", "
   	   . "    modified_date = NOW() "
   	   . "WHERE proposal_id = ". $this->__id;
   	return $this->executeQuery($q);
   }
   
   /**
   * SetRevisionDateSent()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jan 08 17:39:42 PST 2006
   */
   function SetRevisionDateSent($sent_date = 'NOW()')
   {
   	$sent_date = ($sent_date != 'NOW()') ? "'". $sent_date ."'" : $sent_date;
   	
   	$q = "UPDATE proposal_revision "
   	   . "SET sent_date = ". $sent_date .", "
   	   . "    modified_by = ". $this->created_by .", "
   	   . "    modified_date = NOW() "
   	   . "WHERE proposal_revision_id = ". $this->__revision;
   	return $this->executeQuery($q);
   }
   
   /**
   * UpdateProposalAuditStatus()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jan 11 21:55:12 PST 2006
   */
   function UpdateProposalAuditStatus($status)
   {
   	$q = "UPDATE proposal SET status = '". $status ."', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE proposal_id = ". $this->__id;
   	return $this->executeQuery($q);
   }
   
   /**
   * UpdateProposalRevisionAuditStatus()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Mar 28 07:30:23 PST 2006
   */
   function UpdateProposalRevisionAuditStatus($status)
   {
   	$q = "UPDATE proposal_revision SET status = '". $status ."', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE proposal_revision_id = ". $this->__revision;
   	return $this->executeQuery($q);
   }
   
    /**
   * GetProposalCount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jan 11 22:22:07 PST 2006
   */
   function GetProposalCount($filter = '', $start_date, $end_date)
   {
   	$q = "SELECT count(p.proposal_id) AS proposal_count "
         . "FROM proposal AS p "
         . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision AND pr.status = 'A' "
         . "WHERE p.status = 'A' AND pr.created_date BETWEEN '". $start_date ."' AND '". $end_date ."' "
         . $filter
         . "  ";
   	$r = mysql_fetch_assoc($this->executeQuery($q));
   	return ($r) ? $r['proposal_count'] : 0;   	
   }   
   
   /**
   * GetProposalRevisionCount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jan 11 22:22:07 PST 2006
   */
   function GetProposalRevisionCount($filter = '', $start_date, $end_date)
   {
   	$q = "SELECT count(pr.proposal_revision_id) AS revision_count "
         . "FROM proposal_revision AS pr "
         . "WHERE pr.status = 'A' AND pr.created_date BETWEEN '". $start_date ."' AND '". $end_date ."' "
         . $filter
         . "  ";
   	$r = mysql_fetch_assoc($this->executeQuery($q));
   	return ($r) ? $r['revision_count'] : 0;   	
   }
   
   /**
   * GetProposalRevisionValue()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 24 09:18:33 PST 2006
   */
   function GetProposalRevisionValue($filter = '', $start_date, $end_date)
   {
   	$q = "SELECT SUM(pr.max_amount) AS max_amount "
         . "FROM proposal AS p "
         . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision "
         . "WHERE pr.status = 'A' AND pr.created_date BETWEEN '". $start_date ."' AND '". $end_date ."' "
         . $filter
         . "  ";
   	$r = mysql_fetch_assoc($this->executeQuery($q));
   	return ($r && $r['max_amount'] != '') ? $r['max_amount'] : 0;   	
   }
   
   /**
   * GetProposalCountBySampleType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 24 10:12:27 PST 2006
   */
   function GetProposalCountBySampleType($filter, $start_date, $end_date)
   {
   	$q = "SELECT COUNT(pr.proposal_revision_id) AS proposal_count "
         . "FROM proposal AS p "
         . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision "
         . "LEFT OUTER JOIN proposal_revision_sample_type AS prst ON prst.proposal_revision_id = pr.proposal_revision_id "
         . "WHERE p.status = 'A' AND pr.status = 'A' AND pr.created_date BETWEEN '". $start_date ."' AND '". $end_date ."' "
         . $filter
         . "  ";
   	$r = mysql_fetch_assoc($this->executeQuery($q));
   	return ($r) ? $r['proposal_count'] : 0;   	
   	
   }
   
   /**
   * GetProposalCountBySampleType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 24 10:12:27 PST 2006
   */
   function GetProposalValueBySampleType($filter, $start_date, $end_date)
   {
   	$q = "SELECT SUM(pr.max_amount) AS max_amount "
         . "FROM proposal AS p "
         . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision "
         . "LEFT OUTER JOIN proposal_revision_sample_type AS prst ON prst.proposal_revision_id = pr.proposal_revision_id "
         . "WHERE pr.status = 'A' AND pr.created_date BETWEEN '". $start_date ."' AND '". $end_date ."' "
         . $filter
         . "  ";
   	$r = mysql_fetch_assoc($this->executeQuery($q));
   	return ($r && $r['max_amount'] != '') ? $r['max_amount'] : 0;   	
   	
   }
   
   /**
   * UpdateRevisionOptionCPI()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jan 13 22:14:56 PST 2006
   */
   function UpdateRevisionOptionCPI($proposal_revision_option_id, $panel_cost_per_interview)
   {
   	$q = "UPDATE proposal_revision_option "
   	   . "SET panel_cost_per_interview = '". $panel_cost_per_interview ."', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE proposal_revision_option_id = ". $proposal_revision_option_id;
   	return $this->executeQuery($q);
   }
   
   /**
   * GetRevisionStatusList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jan 17 23:24:06 PST 2006
   */
   function GetRevisionStatusList()
   {
   	$q = "SELECT proposal_revision_status_id, proposal_revision_status_description "
   	   . "FROM proposal_revision_status "
   	   . "WHERE status = 'A'";
   	return $this->executeQuery($q);
   }
   
   /**
   * GetRevisionPricingItemGroups()
   *
   * @param
   * @param - 
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Jan 19 11:16:09 PST 2006
   */
   function GetRevisionPricingItemGroups()
   {
      $q = "SELECT prs.service_id, s.pricing_item_group_id "
         . "FROM proposal_revision_service AS prs "
         . "LEFT OUTER JOIN service AS s ON s.service_id = prs.service_id "
         . "WHERE prs.proposal_revision_id = ". $this->__revision ." "
         . "GROUP BY s.pricing_item_group_id ";
      return $this->executeQuery($q);
   }
   
    /**
   * GetProposalSubStatusByStatus()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Feb 16 22:30:09 PST 2006
   */
   function GetProposalSubStatusByStatus($proposal_status_id)
   {
   	$q = "SELECT pss.proposal_sub_status_id, pss.proposal_sub_status_description "
   	   . "FROM proposal_sub_status AS pss "
   	   . "WHERE pss.status = 'A' AND pss.proposal_status_id = ". $proposal_status_id;
   	return $this->executeQuery($q);
   }
   
   /**
   * GetStatusDescriptionByStatusId()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Feb 16 22:40:05 PST 2006
   */
   function GetStatusDescriptionByStatusId($proposal_status_id)
   {
   	$q = "SELECT proposal_status_description "
   	   . "FROM proposal_status "
   	   . "WHERE status = 'A' AND proposal_status_id = ". $proposal_status_id;
   	$r = mysql_fetch_assoc($this->executeQuery($q));
   	return $r['proposal_status_description'];
   }
   
   /**
   * UpdateProposalSubStatus()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Feb 16 23:10:13 PST 2006
   */
   function UpdateProposalSubStatus($proposal_sub_status_id)
   {
   	$q = "UPDATE proposal "
   	   . "SET proposal_sub_status_id = ". $proposal_sub_status_id .", modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE proposal_id = ". $this->__id;
   	return $this->executeQuery($q);
   }
   
    /**
   * SetOptionTotalByOptionCountry()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 03 09:38:08 PST 2006
   */
   function SetOptionTotalByOptionCountry($option_number, $sort_order, $option_total)
   {
   	$q = "UPDATE proposal_revision_option "
   	   . "SET option_total = '". $option_total ."', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE proposal_revision_id = ". $this->__revision ." AND option_number = ". $option_number ." AND sort_order = ". $sort_order;
   	return $this->executeQuery($q);
   }
   
   /**
   * SetOptionPanelTotalByOptionCountry()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 03 09:38:08 PST 2006
   */
   function SetOptionPanelTotalByOptionCountry($option_number, $sort_order, $panel_total)
   {
   	$q = "UPDATE proposal_revision_option "
   	   . "SET panel_total = '". $panel_total ."', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE proposal_revision_id = ". $this->__revision ." AND option_number = ". $option_number ." AND sort_order = ". $sort_order;
   	return $this->executeQuery($q);
   }
   
   /**
   * GetAllProposals()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 03 09:50:02 PST 2006
   */
   function GetActiveRevisions()
   {
   	$q = "SELECT pr.proposal_id, pr.proposal_revision_id "
   	   . "FROM proposal AS p "
   	   . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id "
   	   . "WHERE p.status = 'A' AND pr.proposal_id IS NOT NULL ";
   	return $this->executeQuery($q);
   }
   
   /**
   * SetOptionPanelTotal()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 03 10:11:02 PST 2006
   */
   function SetOptionPanelTotal($proposal_revision_option_id, $panel_total)
   {
   	$q = "UPDATE proposal_revision_option "
   	   . "SET panel_total = '". $panel_total ."', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE proposal_revision_option_id = ". $proposal_revision_option_id;
   	return $this->executeQuery($q);
   }
   
   /**
   * SetOptionTotal()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 03 10:11:02 PST 2006
   */
   function SetOptionTotal($proposal_revision_option_id, $option_total)
   {
   	$q = "UPDATE proposal_revision_option "
   	   . "SET option_total = '". $option_total ."', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE proposal_revision_option_id = ". $proposal_revision_option_id;
   	return $this->executeQuery($q);
   }
   
   /**
   * GetCostPerComplete()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 03 11:43:36 PST 2006
   */
   function GetOptionTotal($filter = '', $having)
   {
   	$q = "SELECT p.proposal_id, pr.proposal_revision_id, p.proposal_name, pro.country_code, pro.completes, pro.incidence_rate, pro.questions_programmed, pro.panel_total, "
   	   . "       pro.option_total, pro.option_cpc, p.account_name AS company_name, pr.revision, p.account_id AS partner_id, "
   	   . "       (pro.option_total / pro.completes) AS panel_cpc "
   	   . "FROM proposal_revision_option AS pro "
   	   . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_revision_id = pro.proposal_revision_id "
   	   . "LEFT OUTER JOIN proposal AS p ON p.proposal_id = pr.proposal_id "
   	   . "LEFT OUTER JOIN proposal_revision_sample_type AS prst ON prst.proposal_revision_id = pr.proposal_revision_id "
   	   . "LEFT OUTER JOIN proposal_user AS pu ON pu.proposal_id = p.proposal_id "
   	   . "WHERE p.status = 'A' AND pro.completes > 0 AND pro.incidence_rate <= 100 AND pro.incidence_rate >= 1 "
   	   . " " . $filter . " "
   	   . " GROUP BY pro.proposal_revision_option_id "
   	   . " HAVING 1 = 1 ". $having;
   	return $this->executeQuery($q);
   }
   
   /**
   * GetOptionTotalByProposal()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Mar 06 21:09:54 PST 2006
   */
   function GetOptionTotalByProposal($filter = '', $having)
   {
   	$q = "SELECT p.proposal_id, pr.proposal_revision_id, p.proposal_name, pro.country_code, pro.completes, pro.incidence_rate, pro.questions_programmed, pro.panel_total, "
   	   . "       pro.option_total, pro.option_cpc, p.account_name AS company_name, pr.revision, p.account_id AS partner_id, "
   	   . "       (pro.option_total / pro.completes) AS panel_cpc "
   	   . "FROM proposal_revision_option AS pro "
   	   . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_revision_id = pro.proposal_revision_id "
   	   . "LEFT OUTER JOIN proposal AS p ON p.proposal_id = pr.proposal_id AND p.current_revision = pr.revision "
   	   . "LEFT OUTER JOIN proposal_revision_sample_type AS prst ON prst.proposal_revision_id = pr.proposal_revision_id "
   	   . "LEFT OUTER JOIN proposal_user AS pu ON pu.proposal_id = p.proposal_id "
   	   . "WHERE p.status = 'A' AND pro.completes > 0 AND pro.incidence_rate <= 100 AND pro.incidence_rate >= 1 "
   	   . " " . $filter . " "
   	   . " GROUP BY pro.proposal_revision_option_id"
   	   . " HAVING 1 = 1 ". $having;
   	return $this->executeQuery($q);
   }
   
   /**
   * GetGroupByOptionTotal()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 03 16:42:07 PST 2006
   */
   function GetGroupByOptionTotal($filter = '')
   {
   	$q = "SELECT MAX(pro.completes) AS max_completes, MIN(pro.completes) AS min_completes, MAX(pro.incidence_rate) AS max_incidence_rate, "
   	   . "       MIN(pro.incidence_rate) AS min_incidence_rate, MAX(pro.questions_programmed) AS max_question_programmed, MIN(pro.questions_programmed) AS min_question_programmed "
   	   . "FROM proposal_revision_option AS pro "
   	   . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_revision_id = pro.proposal_revision_id "
   	   . "LEFT OUTER JOIN proposal AS p ON p.proposal_id = pr.proposal_id "
   	   . "LEFT OUTER JOIN proposal_revision_sample_type AS prst ON prst.proposal_revision_id = pr.proposal_revision_id "
   	   . "LEFT OUTER JOIN proposal_user AS pu ON pu.proposal_id = p.proposal_id "
   	   . "LEFT OUTER JOIN proposal_attr AS a_id ON a_id.proposal_id = p.proposal_id AND a_id.proposal_attr_name = 'ACCOUNT_ID' "
   	   . "WHERE p.status = 'A' AND pro.completes > 0 AND pro.incidence_rate <= 100 AND pro.incidence_rate >= 1 "
   	   . $filter . " "
   	   . " GROUP BY pro.proposal_revision_option_id ";
   	//echo $q;
   	return mysql_fetch_assoc($this->executeQuery($q));
   }
   
   /**
   * SetOptionCpcByOptionCountry()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 03 15:34:45 PST 2006
   */
   function SetOptionCpcByOptionCountry($option_number, $sort_order, $option_cpc)
   {
   	$q = "UPDATE proposal_revision_option "
   	   . "SET option_cpc = '". $option_cpc ."', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE proposal_revision_id = ". $this->__revision ." AND option_number = ". $option_number ." AND sort_order = ". $sort_order;
   	return $this->executeQuery($q);
   }
   
   /**
   * SetOptionCpc()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 03 15:35:56 PST 2006
   */
   function SetOptionCpc($proposal_revision_option_id, $option_cpc)
   {
   	$q = "UPDATE proposal_revision_option "
   	   . "SET option_cpc = '". $option_cpc ."', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE proposal_revision_option_id = ". $proposal_revision_option_id;
   	return $this->executeQuery($q);
   }
   
   /**
   * GetRevisionOptionCount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Mar 28 07:57:32 PST 2006
   */
   function GetRevisionOptionCount()
   {
   	$q = "SELECT count(proposal_revision_option_id) AS options "
   	   . "FROM proposal_revision_option "
   	   . "WHERE status = 'A' AND proposal_revision_id = ". $this->__revision;
   	return $this->executeQuery($q);
   }
   
   /**
   * GetProjectBookings()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Apr 17 14:56:36 PDT 2006
   */
   function GetProjectBookings($filter, $start_date, $end_date, $group_by = 0, $group_by2 = '')
   {
   	$q = "SELECT p.status, p.region_id AS region_id, p.country_code AS country_code, p.account_id AS account_id, "
   	   . "       count(*) AS project_count, SUM(pr.max_amount) AS project_value, AVG(DATEDIFF(pr.created_date, p.created_date)) AS conversion_time, "
   	   . "       l.region_id AS user_region_id, l.location_id, pu.login AS user_login, IF(pa_tier.proposal_attr_value != '', pa_tier.proposal_attr_value, 0) AS tier, prst.sample_type_id "
   	   . "FROM proposal AS p "
   	   . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision "
   	   . "LEFT OUTER JOIN proposal_revision_sample_type AS prst ON prst.proposal_revision_id = pr.proposal_revision_id "
   	   . "LEFT OUTER JOIN proposal_user AS pu ON pu.proposal_id = p.proposal_id "
   	   . "LEFT OUTER JOIN user AS u ON u.login = pu.login "
   	   . "LEFT OUTER JOIN location AS l ON l.location_id = u.location_id "
   	   . "LEFT OUTER JOIN proposal_attr AS pa_tier ON pa_tier.proposal_id = p.proposal_id AND pa_tier.proposal_attr_name = 'GLOBAL_ACCOUNT_TIER_LEVEL' "
   	   . "WHERE p.status = 'A' AND p.proposal_status_id = ". PROPOSAL_STATUS_WON ." AND pr.created_date BETWEEN '". $start_date ."' AND '". $end_date ."' "
   	   . $filter ." ";
	  
   	if ($group_by != '0') $q .= "GROUP BY ". $group_by . $group_by2;
		//mail('root@localhost.localdomain', 'query', $q);
		return $this->executeQuery($q);
   	//return $this->FetchAssoc($this->executeQuery($q));
   }
   
   /**
   * GetProposalAccounts()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Apr 19 12:51:03 PDT 2006
   */
   function GetProposalAccounts($filter = ' ')
   {
   	$q = "SELECT p.account_id, p.account_name "
   	   . "FROM proposal AS p "
   	   . "WHERE p.status = 'A' ". $filter ." "
   	   . "GROUP BY p.account_id ";
		return $this->executeQuery($q);   	
   }
   
   /**
   * GetProposalCountryList()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Apr 20 19:50:40 PDT 2006
   */
   function GetProposalCountryList($filter = ' ')
   {
   	$q = "SELECT p.country_code, c.country_description "
   	   . "FROM proposal AS p "
   	   . "LEFT OUTER JOIN country AS c ON c.country_code = p.country_code "
   	   . "WHERE p.status = 'A' ". $filter ." "
   	   . "GROUP BY p.country_code ";
   	return $this->executeQuery($q);
   }
   
   /**
   * GetProposalAccountsByUser()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Apr 28 09:39:43 PDT 2006
   */
   function GetProposalAccountsByFilter($filter)
   {
   	$q = "SELECT count(*) AS accounts "
   	   . "FROM proposal_user AS pu "
   	   . "LEFT OUTER JOIN proposal AS p ON p.proposal_id = pu.proposal_id "
   	   . "LEFT OUTER JOIN user AS u ON u.login = pu.login "
   	   . "LEFT OUTER JOIN location AS l ON l.location_id = u.location_id "
   	   . "WHERE pu.status = 'A' " .$filter ." "
   	   . "GROUP BY p.account_id ";
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return $this->rows;
   }
   
   /**
   * GetMonthlyTotals()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Apr 17 14:56:36 PDT 2006
   */
   function GetMonthlyTotals($filter, $start_date, $end_date, $group_by = 0, $group_by2 = '')
   {
   	$q = "SELECT p.status, p.region_id AS region_id, p.country_code AS country_code, p.account_id AS account_id, "
   	   . "       COUNT(*) AS project_count, SUM(pr.max_amount) AS project_value, SUM(IF(p.proposal_status_id = ". PROPOSAL_STATUS_WON .", 1, 0)) AS won_project_count, "
   	   . "       SUM(IF(p.proposal_status_id = ". PROPOSAL_STATUS_WON .", pr.max_amount, 0)) AS won_project_value, "
   	   . "       l.region_id AS user_region_id, l.location_id, pu.login AS user_login, IF(pa_tier.proposal_attr_value != '', pa_tier.proposal_attr_value, 0) AS tier, prst.sample_type_id "
   	   . "FROM proposal AS p "
   	   . "LEFT OUTER JOIN proposal_revision AS pr ON pr.proposal_id = p.proposal_id AND pr.revision = p.current_revision "
   	   . "LEFT OUTER JOIN proposal_revision_sample_type AS prst ON prst.proposal_revision_id = pr.proposal_revision_id "
   	   . "LEFT OUTER JOIN proposal_user AS pu ON pu.proposal_id = p.proposal_id "
   	   . "LEFT OUTER JOIN user AS u ON u.login = pu.login "
   	   . "LEFT OUTER JOIN location AS l ON l.location_id = u.location_id "
   	   . "LEFT OUTER JOIN proposal_attr AS pa_tier ON pa_tier.proposal_id = p.proposal_id AND pa_tier.proposal_attr_name = 'GLOBAL_ACCOUNT_TIER_LEVEL' "
   	   . "WHERE p.status = 'A' AND p.created_date BETWEEN '". $start_date ."' AND '". $end_date ."' "
   	   . $filter ." ";
	  
   	if ($group_by != '0') $q .= "GROUP BY ". $group_by . $group_by2;
		//mail('root@localhost.localdomain', 'query', $q);
		//echo $q;
		return $this->executeQuery($q);
   	//return $this->FetchAssoc($this->executeQuery($q));
   }
   
   /**
   * GetRevisionStatus()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - 10:28:57
   */
   function GetRevisionStatus()
   {
   	$q = "SELECT proposal_revision_status_id FROM proposal_revision WHERE status = 'A' AND proposal_revision_id = ". $this->__revision;
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return ($r) ? $r['proposal_revision_status_id'] : 0;
   }
   
   /**
   * SetRevisionAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - 10:31:20
   */
   function SetRevisionAttr($proposal_revision_attr_name, $proposal_revision_attr_value)
   {
   	$q = "INSERT INTO proposal_revision_attr (proposal_revision_id, proposal_revision_attr_name, proposal_revision_attr_value, created_by, created_date, status) "
   	   . "VALUES (". $this->__revision .", '". $proposal_revision_attr_name ."', '". mysql_real_escape_string($proposal_revision_attr_value) ."', ". $this->created_by .", NOW(), 'A') ";
   	return $this->executeQuery($q);
   }
   
   /**
   * GetRevisionAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - 10:39:34
   */
   function GetRevisionAttr($proposal_revision_attr_name)
   {
   	$q = "SELECT proposal_revision_attr_value FROM proposal_revision_attr "
   	   . "WHERE status = 'A' AND proposal_revision_id = ". $this->__revision ." AND proposal_revision_attr_name = '". $proposal_revision_attr_name ."' ";
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return ($r) ? $r['proposal_revision_attr_value'] : 0;
   }
   
   /**
   * UpdateRevisionAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - 11:37:45
   */
   function UpdateRevisionAttr($proposal_revision_attr_name, $proposal_revision_attr_value)
   {
   	$q = "UPDATE proposal_revision_attr "
   	   . "   SET proposal_revision_attr_value = '". mysql_real_escape_string($proposal_revision_attr_value) ."', modified_by = ". $this->created_by .", modified_date = NOW() "
   	   . "WHERE status = 'A' AND proposal_revision_id = ". $this->__revision ." AND proposal_revision_attr_name = '". $proposal_revision_attr_name ."' ";
   	return $this->executeQuery($q);
   }
   
   /**
   * isRevisionAttrSet()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - 11:46:52
   */
   function isRevisionAttrSet($proposal_revision_attr_name)
   {
   	$q = "SELECT proposal_revision_attr_value FROM proposal_revision_attr "
   	   . "WHERE status = 'A' AND proposal_revision_id = ". $this->__revision ." AND proposal_revision_attr_name = '". $proposal_revision_attr_name ."' ";
   	$r = $this->FetchAssoc($this->executeQuery($q));
   	return ($r) ? true : false;
   }
   
   /**
   * SetProposalOption()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 10 07:54:59 PST 2006
   */
   function SetProposalRevisionPanel($proposal_revision_id, $option_number, $country_id, $sample_type_id, $prime, $completes, $incidence, $question_length, $cpc, $total_cost, $adjustment, $sort_order)
   {   	

   	$q = "INSERT INTO proposal_revision_panel (proposal_revision_id, option_number, country_id, sample_type_id, prime, completes, incidence, "
   	   . "question_length, cost_per_complete, total_cost, adjustment, sort_order, created_by, created_date, status ) "
   	   . "VALUES ('$proposal_revision_id', '". $option_number ."', '". $country_id ."', '". $sample_type_id ."', '"
   	   . $prime ."', '". $completes ."', '". $incidence ."', '". $question_length ."', '" . $cpc . "', '" . $total_cost . "', '" . $adjustment . "', '" . $sort_order . "', '". $this->created_by ."', NOW(), 'A')";
   	
   	$this->executeQuery($q);
   	return $this->lastID;
   }  
   
   /**
   * UpdateProposalOption()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 10 07:54:59 PST 2006
   */
   function UpdateProposalRevisionPanel($proposal_revision_panel_id, $country_id, $completes, $incidence, $question_length, $cpc, $total_cost, $adjustment )
   {	
   	$q = "UPDATE proposal_revision_panel SET country_id = '$country_id', 
   		 completes = ". $completes .", incidence = ". $incidence .", question_length = ". $question_length .",
   		 cost_per_complete = '$cpc', total_cost = '$total_cost', adjustment = '$adjustment', modified_by = ". 
   		 $this->created_by .", modified_date = NOW() WHERE proposal_revision_panel_id = " . $proposal_revision_panel_id;
   	return $this->executeQuery($q);
   }     
   

   
   /**
   * SetProposalOptionExtra()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 10 07:54:59 PST 2006
   */
   function SetProposalRevisionPanelExtra($proposal_revision_panel_id, $list_id, $sample_type_pricing_id)
   {
   	   $q = "INSERT INTO proposal_revision_panel_extra (proposal_revision_panel_id, list_id, sample_type_pricing_id, created_by,  created_date, status)"
   	      . "VALUES ( '". $proposal_revision_panel_id ."', '$list_id', '". $sample_type_pricing_id ."', '". $this->created_by ."', NOW(), 'A')";
   	
   	   $this->executeQuery($q);
   	   return $this->lastID;  
   }   

   /**
   * UpdateProposalOptionExtra()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 10 07:54:59 PST 2006
   */
   function UpdateProposalRevisionPanelExtra($proposal_revision_panel_id, $list_id, $sample_type_pricing_id)
   {
   	$q = "UPDATE proposal_revision_panel_extra SET sample_type_pricing_id =' " . $sample_type_pricing_id . "', modified_by = " . $this->created_by 
   	.", modified_date = NOW() WHERE proposal_revision_panel_id = " . $proposal_revision_panel_id . " AND list_id = $list_id";

   	return $this->executeQuery($q);
   } 
   
   
   /**
   * SetProposalOptionDescription()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 10 07:54:59 PST 2006
   */
   function SetProposalRevisionPanelDescription($proposal_revision_panel_id, $proposal_revision_panel_attr_value)
   {
   	$q = "INSERT INTO proposal_revision_panel_attr (proposal_revision_panel_id, proposal_revision_panel_attr_name, proposal_revision_panel_attr_value, "
	   . "created_by, created_date, status)"
   	   . "VALUES ( '". $proposal_revision_panel_id ."', 'SAMPLE_TYPE_DESCRIPTION', '". $proposal_revision_panel_attr_value ."', '". $this->created_by ."', NOW(), 'A')";
   	return $this->executeQuery($q);
   } 
   
   /**
   * UpdateProposalOptionDescription()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 10 07:54:59 PST 2006
   */
   function UpdateProposalRevisionPanelDescription($proposal_revision_panel_id, $proposal_revision_panel_attr_value)
   {
   	$q = "UPDATE proposal_revision_panel_attr SET proposal_revision_panel_attr_value = '" . $proposal_revision_panel_attr_value . "', modified_by = " . $this->created_by 
   	.", modified_date = NOW() WHERE proposal_revision_panel_id = " . $proposal_revision_panel_id . " AND proposal_revision_panel_attr_name = 'SAMPLE_TYPE_DESCRIPTION'";
	
   	return $this->executeQuery($q);
   } 
   
   /**
   * GetProposalCountryPanelData()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 10 07:54:59 PST 2006
   */
   function GetProposalRevisionPanelData($proposal_revision_id, $option_number)
   {
   	$q = "SELECT prp.sort_order, prp.proposal_revision_panel_id, prp.completes, prp.incidence, prp.question_length, prp.prime, prp.cost_per_complete, prp.total_cost, prp.adjustment, prp.sample_type_id, st.sample_type_description, prp.country_id, c.country_code, c.country_description "
         . "FROM  proposal_revision_panel prp left outer join sample_type st ON prp.sample_type_id=st.sample_type_id LEFT OUTER JOIN country AS c ON c.country_id=prp.country_id "
         . "WHERE prp.proposal_revision_id = ". $proposal_revision_id . " AND prp.option_number = " . $option_number. " ORDER BY sample_type_id, proposal_revision_panel_id";
     return $this->executeQuery($q);
   }

   /**
   * GetProposalCountryPanelExtraData()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 10 07:54:59 PST 2006
   */
   function GetProposalCountryPanelExtraData($proposal_revision_panel_id, $list_id=0)
   {
   	$q = "SELECT stp.sample_type_pricing_id, stp.list_id, stp.item_id, stp.item_description, stp.premium "
         . "FROM  proposal_revision_panel_extra prpe inner join sample_type_pricing stp ON prpe.sample_type_pricing_id=stp.sample_type_pricing_id "
         . "WHERE prpe.proposal_revision_panel_id = ". $proposal_revision_panel_id; 
    if ($list_id) {
       $q .= " AND prpe.list_id = '$list_id'";
    }
    return $this->executeQuery($q);
   }
     
   /**
   * GetProposalCountryPanelAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 10 07:54:59 PST 2006
   */
   function GetProposalCountryPanelAttr($proposal_revision_panel_id, $attr_name='')
   {
   	$q = "SELECT proposal_revision_panel_attr_value "
         . "FROM   proposal_revision_panel_attr "
         . "WHERE proposal_revision_panel_id = ". $proposal_revision_panel_id; 
    if($attr_name!=''){
    	$q .= " AND proposal_revision_panel_attr_name='" . $attr_name . "'";
    }
    return $this->executeQuery($q);
   }   

   function GetExtraPanelDetails($proposal_revision_id, $number_of_options, $country_ids, $sample_type_ids) {
      $q = "SELECT proposal_revision_panel_id FROM proposal_revision_panel WHERE proposal_revision_id = '$proposal_revision_id' AND ";
      $q .= "(option_number > '$number_of_options' OR country_id NOT IN (".implode(",", $country_ids).") OR sample_type_id NOT IN (".implode(",", $sample_type_ids)."))";
      
      return $this->executeQuery($q);
   }
   
   /**
   * DeletePanelDetails()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Mar 10 07:54:59 PST 2006
   */
   function DeletePanelDetails($proposal_revision_panel_id)
   {
      if (is_array($proposal_revision_panel_id)) {
         $where = " IN (". implode(",", $proposal_revision_panel_id). ")";
      } else {
         $where = " = '$proposal_revision_panel_id'";
      }
      
   	  $q = "DELETE FROM proposal_revision_panel_attr WHERE proposal_revision_panel_id " . $where;
   	  $this->executeQuery($q);
   	  $q = "DELETE FROM proposal_revision_panel_extra WHERE proposal_revision_panel_id " . $where;
   	  $this->executeQuery($q);
      $q = "DELETE FROM proposal_revision_panel WHERE proposal_revision_panel_id " . $where;
      $this->executeQuery($q);
      
   }   
   
    /**
    * Get Proposal Revistion Panal where sample type equal to 0 and Prime equeal to 1
    *
    * @param int $proposal_revision_id
    * @param int $option_number
    * @param int $country_id
    * @return 
    */
   function GetProposalRevisionPanelPrimedData($proposal_revision_id, $option_number, $country_id, $sample_type_id = 0)
   {
   	$q = "SELECT proposal_revision_panel_id, completes, incidence, question_length, cost_per_complete, total_cost, adjustment "
       . "FROM  proposal_revision_panel "
       . "WHERE proposal_revision_id = ". $proposal_revision_id . " AND option_number = " . $option_number 
       . " AND country_id = ".$country_id." AND sample_type_id = " . $sample_type_id . " AND prime = 1 ";
         
     return mysql_fetch_assoc($this->executeQuery($q));
   }
}
?>
