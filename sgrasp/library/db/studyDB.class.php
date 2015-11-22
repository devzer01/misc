<?php
//since v0.5 of study manager we have changed our coding standards so we are capilizing all the first letters of a method name including the very first letter.
class studyDB extends dbConnect {

   var $_rsStudy = 0; //holder for netmr data

   var $_study = 0;

   var $_type = 0; //place holder for study type

   var $timezone = '';

   var $_datasource = 0;

   var $_template_id = 0;

   /**
   * studyDB()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function studyDB($timezone="+00:00")
   {
      $this->timezone = $timezone;
      parent::dbConnect();
   }






   /**
   * AddStudyV2()
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   * @since Tchan 2006-02-01
   */
   function AddStudyV2($study_id, $partner_id, $study_name, $start_date, $study_type_id)
   {
      // escape inputs to prevent SQL hijack
      settype($study_id, "integer");
      settype($partner_id, "integer");
      settype($study_type_id, "integer");
      settype($created_by, "integer");
      $study_name = mysql_real_escape_string($study_name);
      $start_date = mysql_real_escape_string($start_date);

		$qry  = "INSERT INTO study (study_id, partner_id, study_name, start_date, study_type_id, created_by, created_date) ";
      $qry .= "VALUES ($study_id, $partner_id, '$study_name','$start_date', $study_type_id,{$this->created_by}, NOW())";
      $this->executeQuery($qry);

      $this->_type = $study_type_id;
   }

   /**
   * add()
   * This function needs to be written again because there is business logic built on this *****
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function AddStudy($o)
   {
		$r = mysql_fetch_assoc($this->_rsStudy);
		$r['title'] = ereg_replace("'","",$r['title']);

		$qry  = "INSERT INTO study (study_id,partner_id,study_name,start_date,study_type_id,created_by,created_date,status) ";
      $qry .= "VALUES (".$o['study_id'].",".$r['user_id'].",'".mysql_real_escape_string($r['title'])."','".$o['start_date']."','".$o['study_type_id']."',";
      $qry .=            $o['created_by'].",NOW(),'A')";

      $this->executeQuery($qry);

      $this->_type = $o['study_type_id'];
      $this->_datasource = $o['study_datasource_id'];

      return true;
   }

   /**
   * AddStudyTemplate()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */

   function AddStudyTemplate($o)
   {
      $qry  = "INSERT INTO study_template (study_template_description, study_type_id, created_by, created_date, status ) ";
      $qry .= "VALUES ('" . mysql_real_escape_string($o['study_template_description']) . "',".$o['study_type_id'].",".$o['created_by'].",NOW(),'A')";

      return $this->executeQuery($qry);
   }

   /**
   * SetStudyTemplateDescription()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Apr 27 18:12:06 PDT 2006
   */
   function SetStudyTemplateDescription($desc)
   {
      $q = "UPDATE study_template SET study_template_description='".mysql_real_escape_string($desc)."', modified_by='".$this->created_by."', modified_date=NOW() WHERE study_template_id='".$this->_template_id."'";
      return $this->executeQuery($q);
   }

   /**
   * SetStudyTemplateStudyTypeID()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Apr 27 18:13:36 PDT 2006
   */
   function SetStudyTemplateStudyTypeID($study_type_id)
   {
      $q = "UPDATE study_template SET study_type_id='".$study_type_id."', modified_by='".$this->created_by."', modified_date=NOW() WHERE study_template_id='".$this->_template_id."'";
      return $this->executeQuery($q);
   }
   /**
   * SetDataSource()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
 	function SetDataSource($o)
	{
		$qry  = "REPLACE INTO study_study_datasource (study_id,study_datasource_id,created_by,created_date,status) ";
		$qry .= "VALUES (".$this->_study.",".$o['study_datasource_id'].",".$o['created_by'].",NOW(),'A')";
		return $this->executeQuery($qry);
	}

	/**
	* SetStudyTemplateDatasource()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue May 09 17:14:44 PDT 2006
	*/
	function SetStudyTemplateDatasource($template_id, $datasource_id)
	{
	   $q = "SELECT study_template_datasource_id FROM study_template_datasource WHERE study_template_id='$template_id' AND study_datasource_id='$datasource_id'";
	   $this->executeQuery($q);
	   if ($this->rows) {
	      $q = "UPDATE study_template_datasource SET modified_by='".$this->created_by."', modified_date=NOW(), status='A' WHERE study_template_id='$template_id' AND study_datasource_id='$datasource_id'";
	   }else{
	      $q = "INSERT INTO study_template_datasource (`study_template_id`, `study_datasource_id`, `created_by`, `created_date`, `status`) "
	      ." VALUES ('$template_id', '$datasource_id', '".$this->created_by."', NOW(), 'A')";
	   }
	   $this->executeQuery($q);
	   return true;
	}

	/**
	* DeleteTemplateDatasources()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed May 10 15:00:28 PDT 2006
	*/
	function DeleteTemplateDatasources($template_id)
	{
	   $q = "UPDATE study_template_datasource SET status='D', modified_by='".$this->created_by."', modified_date=NOW() WHERE study_template_id='$template_id'";
	   $this->executeQuery($q);
	   return true;
	}

	/**
	* SetSampleType()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Apr 26 12:54:00 PDT 2006
	*/
	function SetSampleType($sample_type_id)
	{
	   $q = "SELECT study_sample_type_id FROM study_sample_type WHERE study_id='".$this->_study."' AND sample_type_id='".$sample_type_id."'";
	   $this->executeQuery($q);
	   if ($this->rows) {
	      $q = "UPDATE study_sample_type SET modified_by = '".$this->created_by."', modified_date=NOW(), status='A' WHERE study_id='".$this->_study."' AND sample_type_id='".$sample_type_id."'";
	   }else{
	      $q = "INSERT INTO study_sample_type (study_id, sample_type_id, created_by, created_date, status) VALUES ('".$this->_study."', '$sample_type_id', '".$this->created_by."', NOW(), 'A')";
	   }
	   $this->executeQuery($q);
	   return true;
	}

	/**
	* SetStudyTemplateSampleType()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue May 09 17:22:27 PDT 2006
	*/
	function SetStudyTemplateSampleType($template_id, $sample_type_id)
	{
	   $q = "SELECT study_template_sample_type_id FROM study_template_sample_type WHERE study_template_id='$template_id' AND sample_type_id='".$sample_type_id."'";
	   $this->executeQuery($q);
	   if ($this->rows) {
	      $q = "UPDATE study_template_sample_type SET modified_by = '".$this->created_by."', modified_date=NOW(), status='A' WHERE study_template_id='$template_id' AND sample_type_id='".$sample_type_id."'";
	   }else{
	      $q = "INSERT INTO study_template_sample_type (study_template_id, sample_type_id, created_by, created_date, status) VALUES ('$template_id', '$sample_type_id', '".$this->created_by."', NOW(), 'A')";
	   }
	   $this->executeQuery($q);
	   return true;
	}

	/**
	* DeleteTemplateSampleTypes()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed May 10 15:10:24 PDT 2006
	*/
	function DeleteTemplateSampleTypes($template_id)
	{
	   $q = "UPDATE study_template_sample_type SET status='D', modified_by='".$this->created_by."', modified_date=NOW() WHERE study_template_id='$template_id'";
	   $this->executeQuery($q);
	   return true;
	}

	/**
	* GetTemplateSampleTypes()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed May 10 14:30:49 PDT 2006
	*/
	function GetTemplateSampleTypes($template_id)
	{
	   $q = "SELECT st.sample_type_id, st.sample_type_description FROM study_template_sample_type AS stst LEFT JOIN sample_type AS st ON st.sample_type_id=stst.sample_type_id WHERE stst.study_template_id='$template_id' AND stst.status='A'";
	   return $this->executeQuery($q);
	}

	/**
	* isTemplateContact()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Jul 10 12:56:14 PDT 2006
	*/
	function isTemplateContact($template_id, $contact)
	{
	   $q = "SELECT study_template_contact_id FROM study_template_contact WHERE study_template_id='$template_id' AND first_name='".$contact["first_name"]."' AND last_name='".$contact["last_name"]."' AND email='".$contact["email"]."' AND study_contact_type_id='".$contact["study_contact_type_id"]."'";
      $ret = mysql_fetch_assoc($this->executeQuery($q));
      return ($ret?$ret["study_template_contact_id"]:false);
	}

	/**
	* InsertTemplateContact()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Jul 06 14:53:34 PDT 2006
	*/
	function InsertTemplateContact($template_id, $contact)
	{
      $q = "INSERT INTO study_template_contact (`study_template_id`, `study_contact_type_id`, `contact_id`, `salutation`, `first_name`, `middle_initial`, `last_name`, `phone`, `fax`, `email`, `created_by`, `created_date`, `status`)
            VALUES ('$template_id', '".$contact["study_contact_type_id"]."', '".$contact["contact_id"]."', '".mysql_real_escape_string($contact["salutation"])."', '".mysql_real_escape_string($contact["first_name"])."', '".mysql_real_escape_string($contact["middle_initial"])."', '".mysql_real_escape_string($contact["last_name"])."', '".mysql_real_escape_string($contact["phone"])."', '".mysql_real_escape_string($contact["fax"])."', '".mysql_real_escape_string($contact["email"])."', '".$this->created_by."', NOW(), 'A')";
      $this->executeQuery($q);
      return $this->last_id;
	}

	/**
	* UpdateTemplateContact()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Jul 10 12:59:55 PDT 2006
	*/
	function UpdateTemplateContact($study_template_contact_id, $template_id, $contact)
	{
      $q = "UPDATE study_template_contact SET
               study_template_id='$template_id',
               study_contact_type_id='".$contact["study_contact_type_id"]."',
               contact_id='".$contact["contact_id"]."',
               salutation='".mysql_real_escape_string($contact["salutation"])."',
               first_name='".mysql_real_escape_string($contact["first_name"])."',
               middle_initial='".mysql_real_escape_string($contact["middle_initial"])."',
               last_name='".mysql_real_escape_string($contact["last_name"])."',
               phone='".mysql_real_escape_string($contact["phone"])."',
               fax='".mysql_real_escape_string($contact["fax"])."',
               email='".mysql_real_escape_string($contact["email"])."',
               modified_by='".$this->created_by."',
               modified_date=NOW(),
               status='A'
            WHERE study_template_contact_id='$study_template_contact_id'";

      $this->executeQuery($q);
      return $this->rows;
	}

	/**
	* GetTemplateContacts()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Jul 06 15:32:33 PDT 2006
	*/
	function GetTemplateContacts($template_id)
	{
	   $q = "
	   SELECT
	     stc.study_template_contact_id,
	     stc.study_template_id,
	     stc.study_contact_type_id,
	     sct.study_contact_type_description,
	     stc.contact_id,
	     stc.salutation,
	     stc.first_name,
	     stc.middle_initial,
	     stc.last_name,
	     stc.phone,
	     stc.fax,
	     stc.email
	   FROM study_template_contact AS stc
	   LEFT JOIN study_contact_type AS sct ON sct.study_contact_type_id=stc.study_contact_type_id
	   WHERE stc.study_template_id='$template_id' AND stc.status='A'";
	   return $this->executeQuery($q);
	}

	/**
	* DeleteTemplateContact()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Jul 06 17:20:20 PDT 2006
	*/
	function DeleteTemplateContact($study_template_contact_id)
	{
	   $q = "UPDATE study_template_contact SET status='D', modified_by='".$this->created_by."', modified_date=NOW() WHERE study_template_contact_id='$study_template_contact_id'";
	   $this->executeQuery($q);
	   return $this->rows;
	}

	/**
	* SetProduct()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon May 08 14:11:44 PDT 2006
	*/
	function SetProduct($product_id)
	{
      $q = "SELECT study_product_id FROM study_product WHERE study_id='".$this->_study."' AND product_id='".$product_id."'";
      $this->executeQuery($q);
      if ($this->rows) {
         $q = "UPDATE study_product SET modified_by='".$this->created_by."', modified_date=NOW(), status='A' WHERE study_id='".$this->_study."' AND product_id='$product_id'";
      }else{
         $q = "INSERT INTO study_product (study_id, product_id, created_by, created_date, status) VALUES ('".$this->_study."', '$product_id', '".$this->created_by."', NOW(), 'A')";
      }
      $this->executeQuery($q);
      return true;
	}

	/**
	* SetStudyTemplateProduct()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue May 09 17:25:04 PDT 2006
	*/
	function SetStudyTemplateProduct($template_id, $product_id)
	{
      $q = "SELECT study_template_product_id FROM study_template_product WHERE study_template_id='$template_id' AND product_id='".$product_id."'";
      $this->executeQuery($q);
      if ($this->rows) {
         $q = "UPDATE study_template_product SET modified_by='".$this->created_by."', modified_date=NOW(), status='A' WHERE study_template_id='$template_id' AND product_id='$product_id'";
      }else{
         $q = "INSERT INTO study_template_product (study_template_id, product_id, created_by, created_date, status) VALUES ('$template_id', '$product_id', '".$this->created_by."', NOW(), 'A')";
      }
      $this->executeQuery($q);
      return true;
	}

	/**
	* DeleteTemplateProducts()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed May 10 15:12:50 PDT 2006
	*/
	function DeleteTemplateProducts($template_id)
	{
	   $q = "UPDATE study_template_product SET status='D', modified_by='".$this->created_by."', modified_date=NOW() WHERE study_template_id='$template_id'";
	   $this->executeQuery($q);
	   return true;
	}

	/**
	* GetTemplateProducts()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed May 10 14:39:00 PDT 2006
	*/
	function GetTemplateProducts($template_id)
	{
	   $q = "SELECT p.product_id, p.product_description FROM study_template_product AS stp LEFT JOIN product AS p ON p.product_id=stp.product_id WHERE stp.study_template_id='$template_id' AND stp.status='A'";
	   return $this->executeQuery($q);
	}

	/**
	* DeleteSampleTypes()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Apr 26 14:22:46 PDT 2006
	*/
	function DeleteSampleTypes()
	{
	   $q = "UPDATE study_sample_type SET status='D', modified_by='".$this->created_by."', modified_date=NOW() WHERE study_id='".$this->_study."'";
	   $this->executeQuery($q);
	   return true;
	}

	/**
	* DeleteProducts()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon May 08 15:25:57 PDT 2006
	*/
	function DeleteProducts()
	{
	   $q = "UPDATE study_product SET status='D', modified_by='".$this->created_by."', modified_date=NOW() WHERE study_id='".$this->_study."'";
	   $this->executeQuery($q);
	   return true;
	}

	/**
   * UnsetDataSource()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function UnsetDataSource($o)
	{
		$qry  = "UPDATE study_study_datasource SET status = 'D', modified_by = ".$o['created_by']." , modified_date = NOW() ";
		$qry .= "WHERE study_datasource_id = ".$o['study_datasource_id']." AND study_id = ".$this->_study;
		return $this->executeQuery($qry);
	}

	/**
   * GetDataSource()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function GetDataSource()
	{
		$qry  = "SELECT sd.study_datasource_id, sd.study_datasource_description ";
		$qry .= "FROM study_study_datasource AS ssd ";
		$qry .= "LEFT OUTER JOIN study_datasource AS sd ON ssd.study_datasource_id = sd.study_datasource_id ";
		$qry .= "WHERE ssd.study_id = ".$this->_study." AND ssd.status = 'A'";

		$this->_datasource = $this->executeQuery($qry);
		return $this->_datasource;
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
	* @since  - Wed Apr 26 13:55:15 PDT 2006
	*/
	function GetSampleTypes()
	{
	   $q = "SELECT st.sample_type_id, st.sample_type_description FROM study_sample_type AS sst LEFT JOIN sample_type AS st ON st.sample_type_id=sst.sample_type_id WHERE sst.study_id='".$this->_study."' AND sst.status='A'";
	   return $this->executeQuery($q);
	}

	/**
	* DeleteStudySampleTypes()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Jul 05 14:12:56 PDT 2006
	*/
	function DeleteStudySampleTypes($study_id)
	{
	   $study_id = sprintf("%d", $study_id);
	   $q = "UPDATE study_sample_type SET status='D', modified_by={$this->created_by}, modified_date=NOW() WHERE study_id='$study_id'";
	   $this->executeQuery($q);
	   return true;
	}

	/**
	* GetProducts()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon May 08 14:24:43 PDT 2006
	*/
	function GetProducts()
	{
	   $q = "SELECT p.product_id, p.product_description FROM study_product AS sp LEFT JOIN product AS p ON p.product_id=sp.product_id WHERE sp.study_id='".$this->_study."' AND sp.status='A'";
	   return $this->executeQuery($q);
	}

	/**
   * GetTemplateNames()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function GetTemplateNames($o)
	{
		$qry  = "SELECT st.study_template_id, CONCAT(st.created_by,'.', UPPER(st.study_template_description)) AS study_template_name,  ";
		$qry .= "       IF(st.created_by = ".$o['created_by'].",1,0) AS owner_flag ";
		$qry .= "FROM study_template AS st ";
		$qry .= "WHERE st.status = 'A' ";
		$qry .= "ORDER BY owner_flag DESC ";
      return $this->executeQuery($qry);
	}

   /**
   * getnetmr()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
 	function getNetMr($o)
	{

      $params = array(
         'study_id' => $o['study_id'] 
      );
      $responses = HBRPCCall('cnm', 'GetStudyDetail', $params);

      if (!is_array($responses) || $responses['error_code'] ){
	      $this->error_message = "CNM error: {$responses['error_message']}";
	      return false;
	   }
	   return $responses;

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
	function onfile($o)
	{
	   $study_id = sprintf("%d", $o['study_id'] );

      $qry = "SELECT study_id FROM study WHERE study_id=$study_id ";
      $rs = $this->executeQuery($qry);
      return $this->rows;
   }

   /**
   * SetPartnerId()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function SetPartnerId($o,$partner_id)
	{
      $qry  = "UPDATE study SET partner_id = ".$partner_id.", modified_by = ".$o['created_by'].", modified_date = NOW() ";
      $qry .= "WHERE study_id = ".$this->_study;
      return $this->executeQuery($qry);
   }

     /**
   *  SetTitle() -- obsoleted; replaced by SetStudyName()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function SetTitle($o,$title)
	{
      $qry  = "UPDATE study SET study_name = '". mysql_escape_string($title) ."', modified_by = ".$o['created_by'].", modified_date = NOW() ";
      $qry .= "WHERE study_id = ".$this->_study;
      return $this->executeQuery($qry);
   }



   /**
   *  SetStudyName()
   *
   * @param integer $study_id - ID of study whose name to be updated
   * @param string $study_name - New study name
   * @param integer $modified_by - ID of the user who change the name
   * @return
   * @throws
   * @access
   * @global
   * @since TChan 2006-02-02
   */
   function SetStudyName($study_id, $study_name, $modified_by)
   {
      settype($study_id, "integer");
      settype($modified_by, "integer");
      $study_name = mysql_real_escape_string($study_name);

      $qry = "UPDATE study SET study_name = '$study_name', modified_by = $modified_by, modified_date = NOW() "
           . " WHERE study_id = $study_id";

      return $this->executeQuery($qry);
   }


   /**
   * GetStudyType()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetStudyType()
   {
      $qry  = "SELECT s.study_type_id ";
      $qry .= "FROM study AS s ";
      $qry .= "WHERE s.study_id = ".$this->_study;

      return mysql_result($this->executeQuery($qry),'0','study_type_id');
   }


  /**
   * getHeaderDetail()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getHeaderDetail()
   {
      return $this->GetHeaderDetailV2($this->_study);
   }

   /**
   * GetHeaderDetailV2()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetHeaderDetailV2($study_id)
   {
      settype($study_id, "integer");
      $qry  = "SELECT ";
      $qry .= "  s.study_id, ";
      $qry .= "  s.study_name, ";
      $qry .= "  s.start_date, ";
      $qry .= "  s.partner_id, ";
      $qry .= "  sa_company_name.study_value AS company_name, ";
      $qry .= "  c.country_description, ";
      $qry .= "  t.study_type_description, ";
      $qry .= "  s.study_type_id,";
      $qry .= "  DATE_FORMAT(CONVERT_TZ(s.timeline_created_date,'+00:00','$this->timezone'),'%Y-%m-%d %h:%i %p') AS timeline_created_date,";
      $qry .= "  s.study_status_id, ";
      $qry .= "  s.study_stage_id, ";
      $qry .= "  CONCAT(u.first_name,' ',u.last_name) AS created_by_name, ";
      $qry .= "  ss.study_status_description, s.alert_level_id, al.alert_level_description,";
      $qry .= "  DATE_FORMAT(CONVERT_TZ(s.created_date,'+00:00','$this->timezone'),'%Y-%m-%d %h:%i %p') AS created_date,";
      $qry .= "  DATE_FORMAT(CONVERT_TZ(s.study_invoice_date,'+00:00','$this->timezone'),'%Y-%m-%d %h:%i %p') AS study_invoice_date ";
      $qry .= "  FROM study AS s ";
      $qry .= "  LEFT OUTER JOIN study_type AS t ON t.study_type_id = s.study_type_id ";
      $qry .= "  LEFT OUTER JOIN study_status AS ss ON ss.study_status_id = s.study_status_id ";
      $qry .= "  LEFT OUTER JOIN study_attr AS sa_company_name ON sa_company_name.study_id = s.study_id AND sa_company_name.study_attr='ACCOUNT_NAME' ";
      $qry .= "  LEFT OUTER JOIN study_attr AS sa_country_code ON sa_country_code.study_id = s.study_id AND sa_country_code.study_attr='COUNTRY_CODE' ";
      $qry .= "  LEFT OUTER JOIN country AS c ON c.country_code = sa_country_code.study_value ";
      $qry .= "  LEFT OUTER JOIN user AS u ON u.login = s.created_by ";
      $qry .= "  LEFT OUTER JOIN alert_level AS al ON al.alert_level_id = s.alert_level_id ";
      $qry .= "  WHERE s.study_id = $study_id AND s.status = 'A'";

      $rs = $this->executeQuery($qry);
      return mysql_fetch_assoc($rs);
   }

   /**
   * GetSatSurveyInfo()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - Mon Nov 27 09:41:22 PST 2006
   */
   function GetSatSurveyInfo($study_id)
   {
      $q = "SELECT "
         . "  s.study_id, "
         . "  s.study_name, "
         . "  s.partner_id AS account_id, "
         . "  sa_company_name.study_value AS account_name, "
         . "  su_ae.login AS ae_login, "
         . "  CONCAT(u_ae.first_name, ' ', u_ae.last_name) AS ae_name, "
         . "  u_ae.email_address AS ae_email, "
         . "  su_am.login AS am_login, "
         . "  CONCAT(u_am.first_name, ' ', u_am.last_name) AS am_name, "
         . "  u_am.email_address AS am_email, "
         . "  su_fm.login AS fm_login, "
         . "  CONCAT(u_fm.first_name, ' ', u_fm.last_name) AS fm_name, "
         . "  u_fm.email_address AS fm_email, "
         . "  su_cs.login AS cs_login, "
         . "  CONCAT(u_cs.first_name, ' ', u_cs.last_name) AS cs_name, "
         . "  u_cs.email_address AS cs_email, "
         . "  ua_cs.user_value AS cs_title, "
         . "  sa_br.study_value AS armc_id, "
         . "  CONCAT(ac.first_name, ' ', ac.last_name) AS contact_name, "
         . "  ac.phone AS contact_phone, "
         . "  ac.email AS contact_email, "
         . "  CONCAT(ac.address_1, ', ', ac.city, ', ', ac.state, ', ', ac.country_code) AS contact_address, "
         . "  sa_account_country_code.study_value AS country_code, "
         . "  c.region_id AS region, "
         . "  s.study_type_id AS study_type, "
         . "  GROUP_CONCAT(DISTINCT ssd.study_datasource_id SEPARATOR ';') AS study_datasource, "
         //. "  aa_tier.account_attr_value AS account_tier, "
         . "  ".PRODUCT_NETMR." AS product "
         . "FROM study AS s "
         . "LEFT JOIN study_attr AS sa_company_name ON sa_company_name.study_id=s.study_id AND sa_company_name.study_attr='ACCOUNT_NAME' "
         . "LEFT JOIN study_user AS su_ae ON su_ae.study_id=s.study_id AND su_ae.role_id=".ROLE_PRIMARY_ACCT_EXEC." AND su_ae.status='A' "
         . "LEFT JOIN user AS u_ae ON u_ae.login = su_ae.login "
         . "LEFT JOIN study_user AS su_am ON su_am.study_id=s.study_id AND su_am.role_id=".ROLE_PRIMARY_ACCT_MGR." AND su_am.status='A' "
         . "LEFT JOIN user AS u_am ON u_am.login = su_am.login "
         . "LEFT JOIN study_user AS su_fm ON su_fm.study_id=s.study_id AND su_fm.role_id=".ROLE_FULFILLMENT_MANAGER." AND su_fm.status='A' "
         . "LEFT JOIN user AS u_fm ON u_fm.login = su_fm.login "
         . "LEFT JOIN study_user AS su_cs ON su_cs.study_id=s.study_id AND su_cs.role_id=".ROLE_CS_LEAD." AND su_cs.status='A' "
         . "LEFT JOIN user AS u_cs ON u_cs.login = su_cs.login "
         . "LEFT JOIN user_attr AS ua_cs ON ua_cs.login = u_cs.login AND ua_cs.user_attr='TITLE' AND ua_cs.status='A' "
         . "LEFT JOIN study_attr AS sa_br ON sa_br.study_id=s.study_id AND sa_br.study_attr='BR_REFERENCE' "
         . "LEFT JOIN armc_contact AS ac ON ac.armc_id=sa_br.study_value AND ac.armc_contact_type_id=2 AND ac.status='A' "
         //. "LEFT JOIN study_contact AS sc ON sc.study_id=s.study_id AND sc.study_contact_type_id=".STUDY_CONTACT_PROJECT_MANAGER." AND sc.status='A' "
         //." LEFT JOIN address AS a ON a.contact_id = sc.contact_id "
         //. "LEFT JOIN account_user AS au_ae ON au_ae.account_id=s.partner_id AND au_ae.product_id=".PRODUCT_NETMR." AND au_ae.role_id=".ROLE_PRIMARY_ACCT_EXEC." AND au_ae.status='A' "
         //. "LEFT JOIN account_user AS au_am ON au_am.account_id=s.partner_id AND au_ae.product_id=".PRODUCT_NETMR." AND au_am.role_id=".ROLE_PRIMARY_ACCT_MGR." AND au_am.status='A' "
         //. "LEFT JOIN account_user AS au_fm ON au_fm.account_id=s.partner_id AND au_ae.product_id=".PRODUCT_NETMR." AND au_fm.role_id=".ROLE_FULFILLMENT_MANAGER." AND au_fm.status='A' "
         //. "LEFT JOIN account_user AS au_cs ON au_cs.account_id=s.partner_id AND au_ae.product_id=".PRODUCT_NETMR." AND au_cs.role_id=".ROLE_CS_LEAD." AND au_cs.status='A' "
         . "LEFT JOIN study_attr AS sa_account_country_code ON sa_account_country_code.study_id=s.study_id AND sa_account_country_code.study_attr='ACCOUNT_COUNTRY_CODE' "
         . "LEFT JOIN country AS c ON c.country_code = sa_account_country_code.study_value "
         . "LEFT JOIN study_study_datasource AS ssd ON ssd.study_id=s.study_id AND ssd.status='A' "
         //. "LEFT JOIN account_attr AS aa_tier ON aa_tier.account_id=s.partner_id AND aa_tier.account_attr_name='GLOBAL_ACCOUNT_TIER_LEVEL' "
         . "WHERE s.study_id='$study_id' AND s.status='A' "
         . "GROUP BY s.study_id";

         return mysql_fetch_assoc($this->executeQuery($q));
   }

   /**
   * GetSatSurveyCounts()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - Mon Nov 27 11:07:52 PST 2006
   */
   function GetSatSurveyCounts($account_id, $days)
   {
      $q = "SELECT count(study_satisfaction_survey_log_id) AS c FROM study_satisfaction_survey_log WHERE account_id='$account_id' AND delivery_status='SENT' AND delivery_date BETWEEN DATE_SUB(NOW(), INTERVAL $days DAY) AND NOW()";
      return mysql_result($this->executeQuery($q), 0);
   }

   /**
   * IsSatSurveyLog()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - Thu Nov 30 08:03:46 PST 2006
   */
   function IsStudySatSurveyLog($study_id)
   {
      $q = "SELECT study_id FROM study_satisfaction_survey_log WHERE study_id='$study_id'";
      return mysql_fetch_array($this->executeQuery($q));
   }

   /**
   * InsertStudySatSurveyLog()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - Mon Nov 27 11:11:56 PST 2006
   */
   function InsertStudySatSurveyLog($info)
   {
      //echo ("<pre>".print_r($info, true)."</pre>");
      $q = "INSERT INTO study_satisfaction_survey_log
               (`study_id`,
                `study_name`,
                `pjm_id`,
                `pjm_description`,
                `account_id`,
                `account_name`,
                `contact_name`,
                `contact_phone`,
                `contact_email`,
                `contact_address`,
                `ae_login`,
                `ae_name`,
                `ae_email`,
                `am_login`,
                `am_name`,
                `am_email`,
                `fm_login`,
                `fm_name`,
                `fm_email`,
                `cs_login`,
                `cs_name`,
                `cs_title`,
                `cs_email`,
                `country_code`,
                `region`,
                `teritory`,
                `study_type`,
                `study_datasource`,
                `account_tier`,
                `product`,
                `delivery_status`,
                `created_by`,
                `created_date`,
                `status`)
             VALUES
               ('".$info["study_id"]."',
                '".mysql_real_escape_string($info["study_name"])."',
                '".$info["pjm_id"]."',
                '".mysql_real_escape_string($info["pjm_description"])."',
                '".$info["account_id"]."',
                '".mysql_real_escape_string($info["account_name"])."',
                '".mysql_real_escape_string($info["contact_name"])."',
                '".$info["contact_phone"]."',
                '".$info["contact_email"]."',
                '".mysql_real_escape_string($info["contact_address"])."',
                '".$info["ae_login"]."',
                '".$info["ae_name"]."',
                '".$info["ae_email"]."',
                '".$info["am_login"]."',
                '".$info["am_name"]."',
                '".$info["am_email"]."',
                '".$info["fm_login"]."',
                '".$info["fm_name"]."',
                '".$info["fm_email"]."',
                '".$info["cs_login"]."',
                '".$info["cs_name"]."',
                '".$info["cs_title"]."',
                '".$info["cs_email"]."',
                '".$info["country_code"]."',
                '".$info["region"]."',
                '".$info["teritory"]."',
                '".$info["study_type"]."',
                '".$info["study_datasource"]."',
                '".$info["account_tier"]."',
                '".$info["product"]."',
                '".$info["delivery_status"]."',
                '".$this->created_by."',
                NOW(),
                'A')";
      $this->executeQuery($q);
      return $thsis->last_id;
   }

   /**
   * UpdateStudySatSurveyLog()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - Thu Nov 30 07:54:17 PST 2006
   */
   function UpdateStudySatSurveyLog($info)
   {
      $q = "UPDATE study_satisfaction_survey_log SET ";
      $q .= "`study_name` = '".mysql_real_escape_string($info["study_name"])."', ";
      $q .= "`account_id` = '".$info["account_id"]."', ";
      $q .= "`account_name` = '".mysql_real_escape_string($info["account_name"])."', ";
      $q .= "`pjm_id` = '".$info["pjm_id"]."', ";
      $q .= "`pjm_description` = '".mysql_real_escape_string($info["pjm_description"])."', ";
      $q .= "`contact_name` = '".mysql_real_escape_string($info["contact_name"])."', ";
      $q .= "`contact_phone` = '".mysql_real_escape_string($info["contact_phone"])."', ";
      $q .= "`contact_email` = '".$info["contact_email"]."', ";
      $q .= "`contact_address` = '".mysql_real_escape_string($info["contact_address"])."', ";
      $q .= "`ae_login` = '".$info["ae_login"]."', ";
      $q .= "`ae_name` = '".$info["ae_name"]."', ";
      $q .= "`ae_email` = '".$info["ae_email"]."', ";
      $q .= "`am_login` = '".$info["am_login"]."', ";
      $q .= "`am_name` = '".$info["am_name"]."', ";
      $q .= "`am_email` = '".$info["am_email"]."', ";
      $q .= "`fm_login` = '".$info["fm_login"]."', ";
      $q .= "`fm_name` = '".$info["fm_name"]."', ";
      $q .= "`fm_email` = '".$info["fm_email"]."', ";
      $q .= "`cs_login` = '".$info["cs_login"]."', ";
      $q .= "`cs_name` = '".$info["cs_name"]."', ";
      $q .= "`cs_title` = '".$info["cs_title"]."', ";
      $q .= "`cs_email` = '".$info["cs_email"]."', ";
      $q .= "`country_code` = '".$info["country_code"]."', ";
      $q .= "`region` = '".$info["region"]."', ";
      $q .= "`teritory` = '".$info["teritory"]."', ";
      $q .= "`study_type` = '".$info["study_type"]."', ";
      $q .= "`study_datasource` = '".$info["study_datasource"]."', ";
      $q .= "`account_tier` = '".$info["account_tier"]."', ";
      $q .= "`product` = '".$info["product"]."', ";
      $q .= "`delivery_status` = '".$info["delivery_status"]."', ";
      $q .= "`delivery_date` = '".$info["delivery_date"]."', ";
      $q .= "`modified_by` = '".$this->created_by."', ";
      $q .= "`modified_date` = NOW() ";
      $q .= "WHERE study_id='".$info["study_id"]."'";

      $this->executeQuery($q);
      return $this->rows;
   }

   /**
   * GetStudySatSurveyLog()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - Mon Dec 04 09:16:03 PST 2006
   */
   function GetStudySatSurveyLog($where)
   {
      $q = "SELECT study_id, study_name, pjm_id, pjm_description, account_id, account_name, contact_name, contact_phone, contact_email,";
      $q .= " contact_address, ae_login, ae_name, ae_email, am_login, am_name, am_email, fm_login, fm_name, fm_email, ";
      $q .= "cs_login, cs_name, cs_title, cs_email, country_code, region, teritory, study_type, study_datasource, account_tier,";
      $q .= " product, delivery_status, delivery_date FROM study_satisfaction_survey_log WHERE status='A' $where";
      return $this->executeQuery($q);
   }

   /**
   * GetProjectSatSurveyLog()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - Wed Dec 06 09:01:43 PST 2006
   */
   function GetProjectSatSurveyLog($where)
   {
      $q = "SELECT
               pjm_id,
               pjm_description,
               pjm_description AS study_name,
               GROUP_CONCAT(study_name SEPARATOR '/') AS study_names,
               COUNT(study_id) AS study_count,
               GROUP_CONCAT(study_id SEPARATOR ';') AS study_id,
               account_id,
               account_name,
               contact_name,
               contact_phone,
               contact_email,
               contact_address,
               ae_login,
               ae_name,
               ae_email,
               am_login,
               am_name,
               am_email,
               fm_login,
               fm_name,
               fm_email,
               cs_login,
               cs_name,
               cs_title,
               cs_email,
               GROUP_CONCAT(DISTINCT country_code SEPARATOR ';') AS country_code,
               GROUP_CONCAT(country_code SEPARATOR '/') AS country_codes,
               GROUP_CONCAT(DISTINCT region SEPARATOR ';') AS region,
               GROUP_CONCAT(region SEPARATOR '/') AS regions,
               GROUP_CONCAT(DISTINCT teritory SEPARATOR ';') AS teritory,
               GROUP_CONCAT(teritory SEPARATOR '/') AS teritories,
               GROUP_CONCAT(DISTINCT study_type SEPARATOR ';') AS study_type,
               GROUP_CONCAT(study_type SEPARATOR '/') AS study_types,
               GROUP_CONCAT(DISTINCT study_datasource SEPARATOR ';') AS study_datasource,
               GROUP_CONCAT(study_datasource SEPARATOR '/') AS study_datasources,
               account_tier,
               GROUP_CONCAT(DISTINCT product SEPARATOR ',') AS product,
               GROUP_CONCAT(product SEPARATOR '/') AS products,
               MIN(delivery_status) AS delivery_status,
               MIN(delivery_date) AS delivery_date
            FROM study_satisfaction_survey_log
            WHERE status='A' AND pjm_id != 0 $where GROUP BY pjm_id";

      return $this->executeQuery($q);
   }

   /**
   * GetStudySetting()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - Mon Nov 27 11:13:29 PST 2006
   */
   function GetStudySetting($study_setting_name)
   {
      $q = "SELECT study_setting_value FROM study_setting WHERE study_setting_name='$study_setting_name'";
      return mysql_result($this->executeQuery($q), 0, "study_setting_value");
   }
   
   function SetStudySetting($study_setting_name, $study_setting_value)
   {
   	$q = "UPDATE study_setting SET study_setting_value='$study_setting_value', modified_by='".$this->created_by."', modified_date=NOW() WHERE study_setting_name='$study_setting_name'";
   	$this->executeQuery($q);
   }
   

   /**
   * GetTemplateHeader()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetTemplateHeader()
   {
      $qry  = "SELECT st.study_template_id, st.study_template_description, st.study_type_id, stt.study_type_description, st.created_by, st.created_date ";
      $qry .= "FROM study_template AS st ";
      $qry .= "LEFT JOIN study_type AS stt ON stt.study_type_id=st.study_type_id ";
      $qry .= "WHERE st.study_template_id = ".$this->_template_id." AND st.status = 'A'";
      return mysql_fetch_assoc($this->executeQuery($qry));
   }

   /**
   * setRole()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function setRole($o)
	{
      $qry  = "INSERT INTO study_user (study_id,login,role_id,created_by,created_date) ";
      $qry .= "VALUES (".$this->_study.",".$o['login'].",'".$o['role_id']."',".$o['created_by'].",NOW())";
      $rs = $this->executeQuery($qry);
      return $this->rows;
   }

	/**
   * updateRole()
   *
   * @param
   * @param
   * @return - number of rows updated must be 1 on a sucessful execution
   * @throws
   * @access
   * @global
   */
	function updateRole($o)
	{
      $qry  = "REPLACE INTO study_user (study_id,login,role_id,modified_by,modified_date) ";
      $qry .= "VALUES (".$this->_study.",".$o['login'].",'".$o['role_id']."',".$o['created_by'].",NOW())";
      $rs = $this->executeQuery($qry);
      return $this->rows;
   }


   /**
   * SetStudyRoleV2()
   *
   * @param integer $study_id - ID of the study
   * @param integer $login - ID of the person whose role is assigned
   * @param integer $role_id - ID of the role assigned to
   * @param integer $created_by
   * @return
   * @throws
   * @access
   * @global
   * @since TChan 2006-02-02
   */
   function SetStudyRoleV2($study_id, $login, $role_id)
   {
      settype($study_id, "integer");
      settype($login, "integer");
      settype($role_id, "integer");
      settype($created_by, "integer");

      // Check whether this user has assigned this role for the study
      $qry = "SELECT study_user_id FROM study_user WHERE study_id={$study_id} AND login={$login} AND role_id={$role_id} LIMIT 1";
      $rs = $this->executeQuery($qry);

      if (mysql_num_rows($rs)){
         $study_user_id = mysql_result($rs, FIRST_RECORD);
         $qry = "UPDATE study_user "
              . "SET status='A', modified_by={$this->created_by}, modified_date=NOW() "
              . "WHERE study_user_id=$study_user_id ";

      } else {
         // Insert a new attribute if it is new
         $qry = "REPLACE INTO study_user "
              . "(study_id, login, role_id, created_by, created_date, status ) "
              . "VALUES ($study_id, $login, $role_id, {$this->created_by}, NOW(), 'A') ";
      }
      $rs = $this->executeQuery($qry);

   }


   /**
   * DeleteStudyRoles()
   *
   * @param
   * @param
   * @return -
   * @throws
   * @access
   * @global
   */
	function DeleteStudyRoles()
	{
      $qry  = "DELETE FROM study_user ";
		$qry .= "WHERE study_id = ".$this->_study; //" AND login = ".$o['login'];
      return $this->executeQuery($qry);
   }


   /**
   * DeleteAllStudyRolesV2()
   *
   * @param integer $study_id
   * @param
   * @return -
   * @throws
   * @access
   * @global
   */
   function DeleteAllStudyRolesV2($study_id)
   {
      settype($study_id, "integer");
      $qry = "DELETE FROM study_user WHERE study_id = $study_id";
      return $this->executeQuery($qry);
   }


	/**
   * DeleteStudyRole()
   *
   * @param
   * @param
   * @return -
   * @throws
   * @access
   * @global
   */
	function DeleteStudyRole($o)
	{
      $qry  = "DELETE FROM study_user ";
		$qry .= "WHERE study_id = ".$this->_study." AND login = ".$o['login'];
      return $this->executeQuery($qry);
   }



	/**
   * DeleteStudyRoleByLoginV2()
   *
   * @param integer $study_id
   * @param integer $login
   * @return -
   * @throws
   * @access
   * @global
   * @since TChan 2006-02-03
   */
	function DeleteStudyRoleByLogin($study_id, $login)
   {
      settype($study_id, "integer");
      settype($login, "integer");
      $qry = "DELETE FROM study_user WHERE study_id={$study_id} AND login={$login}";
      return $this->executeQuery($qry);
   }


   /**
   * DeleteStudyRoleByRoleId()
   *
   * @param
   * @param - int role_id
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Jun 16 09:01:26 PDT 2005
   */
   function DeleteStudyRoleByRoleId($role_id)
   {
      $qry = "DELETE FROM study_user WHERE study_id = ".$this->_study." AND role_id = ".$role_id;
      return $this->executeQuery($qry);
   }


  /**
   * GetTaskOwners()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function GetTaskOwners()
   {
      $qry  = "SELECT st.login, u.first_name, u.last_name, u.email_address, st.functional_group_id ";
      $qry .= "FROM study_timeline AS st ";
      $qry .= "LEFT OUTER JOIN user AS u ON u.login = st.login ";
      $qry .= "WHERE st.study_id = ".$this->_study." AND st.status = 'A' AND st.login != 0 ";
      $qry .= "ORDER BY u.first_name, u.last_name ";
      return $this->executeQuery($qry);
   }

   /**
   * GetTaskDetail()
   * returns a recordset
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function GetTaskDetail($study_timeline_id)
   {
      $qry  = "SELECT st.login, st.study_task_id, st.task_duration, st.revision_number, st.functional_group_id, ";
      $qry .= "       u.first_name, u.last_name, fg.functional_group_description, stk.study_task_description, ";
      $qry .= "       st.study_timeline_id, st.study_id, sta.study_timeline_value AS estimated_task_duedate ";
      $qry .= "FROM study_timeline AS st ";
      $qry .= "LEFT OUTER JOIN user AS u ON u.login = st.login ";
      $qry .= "LEFT OUTER JOIN study_task AS stk ON stk.study_task_id = st.study_task_id ";
      $qry .= "LEFT OUTER JOIN functional_group AS fg ON fg.functional_group_id = st.functional_group_id ";
      $qry .= "LEFT OUTER JOIN study_timeline_attr AS sta ON sta.study_timeline_id = st.study_timeline_id AND sta.study_timeline_attr = 'CUREST' ";
      $qry .= "WHERE st.study_id = ".$this->_study." AND st.status = 'A' AND st.study_timeline_id = ".$study_timeline_id;
      return $this->executeQuery($qry);
   }

   /**
   * TaskHasLog()
   *
   * @param
   * @param - study_timeline_id
   * @return - bool
   * @throws
   * @access
   * @global
   * @since  - Tue Jun 07 17:08:42 PDT 2005
   */
   function TaskHasLog($study_timeline_id)
   {
   	$qry = "SELECT study_timeline_id FROM study_timeline_comment WHERE study_timeline_id = ".$study_timeline_id;
   	$r = mysql_fetch_assoc($this->executeQuery($qry));
   	return ($r) ? true : false;
   }


   /**
   * SetTaskWatcher()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 06 14:34:50 PDT 2005
   */
   function SetTaskWatcher($o)
   {
      $qry  = "INSERT INTO study_timeline_user (study_timeline_id,login,created_by,created_date) ";
      $qry .= "VALUES (".$o['study_timeline_id'].",".$o['functional_user_login_1'].",".$o['created_by'].",NOW())";
      return $this->executeQuery($qry);
   }

   /**
   * GetTaskWatchers()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 06 14:38:44 PDT 2005
   */
   function GetTaskWatchers($study_timeline_id)
   {
   	$qry  = "SELECT stu.study_timeline_id, stu.study_timeline_user_id, stu.login, ";
   	$qry .= "       u.first_name, u.last_name, u.email_address, s.status_description, stu.status  ";
   	$qry .= "FROM study_timeline_user AS stu ";
   	$qry .= "LEFT OUTER JOIN user AS u ON stu.login = u.login ";
   	$qry .= "LEFT OUTER JOIN status AS s ON s.status_code = stu.status ";
   	$qry .= "WHERE stu.study_timeline_id = ".$study_timeline_id;
   	return $this->executeQuery($qry);
   }

      /**
   * GetTaskWatchers()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 06 14:38:44 PDT 2005
   */
   function GetActiveTaskWatchers($study_timeline_id)
   {
   	$qry  = "SELECT stu.study_timeline_id, stu.study_timeline_user_id, stu.login, ";
   	$qry .= "       u.first_name, u.last_name, u.email_address, s.status_description, stu.status  ";
   	$qry .= "FROM study_timeline_user AS stu ";
   	$qry .= "LEFT OUTER JOIN user AS u ON stu.login = u.login ";
   	$qry .= "LEFT OUTER JOIN status AS s ON s.status_code = stu.status ";
   	$qry .= "WHERE stu.study_timeline_id = ".$study_timeline_id." AND stu.status = 'A'";
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
   * GetAlertLevels()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Jun 23 14:14:41 PDT 2005
   */
   function GetAlertLevels()
   {
      $qry = "SELECT alert_level_description, alert_level_id "
           . "FROM alert_level "
           . "WHERE status = 'A' ";
      return $this->executeQuery($qry);
   }


   /**
   * GetStudyAlertLevel()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Jun 23 14:14:41 PDT 2005
   */
   function GetStudyAlertLevel($study_id=0)
   {
      if (!$study_id){
         $study_id = $this->_study;
      }
      $qry = "SELECT alert_level_id "
           . "FROM study "
           . "WHERE status = 'A' AND study_id = {$study_id} ";

      $rs = $this->executeQuery($qry);
      $r = mysql_fetch_assoc($rs);
      return $r['alert_level_id'];
   }

   /**
   * GetAllTaskWatchers()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 06 14:38:44 PDT 2005
   */
   function GetAllTaskWatchers()
   {
   	$qry  = "SELECT stu.study_timeline_id, stu.study_timeline_user_id, stu.login, ";
   	$qry .= "       u.first_name, u.last_name, u.email_address, s.status_description, stu.status  ";
   	$qry .= "FROM study_timeline_user AS stu ";
   	$qry .= "LEFT OUTER JOIN user AS u ON stu.login = u.login ";
   	$qry .= "LEFT OUTER JOIN status AS s ON s.status_code = stu.status ";
   	$qry .= "LEFT OUTER JOIN study_timeline AS st ON st.study_timeline_id = stu.study_timeline_id ";
   	$qry .= "WHERE st.study_id = ".$this->_study;
   	return $this->executeQuery($qry);
   }

   /**
   * isTaskWatcher()
   *
   * @param - int|login
   * @param - int|timeline id
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 06 14:56:48 PDT 2005
   */
   function isTaskWatcher($login,$study_timeline_id)
   {
   	$qry  = "SELECT study_timeline_user_id FROM study_timeline_user ";
   	$qry .= "WHERE study_timeline_id = ".$study_timeline_id." AND login = ".$login;
   	return mysql_fetch_assoc($this->executeQuery($qry));
   }

   /**
   * hasWatcher()
   *
   * @param
   * @param - study timeline id
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 06 19:51:25 PDT 2005
   */
   function hasWatcher($study_timeline_id)
   {
   	$qry = "SELECT study_timeline_user_id FROM study_timeline_user WHERE study_timeline_id = ".$study_timeline_id;
   	return mysql_fetch_assoc($this->executeQuery($qry));
   }

   /**
   * UnsetTaskWatcherById()
   *
   * @param
   * @param - id|int
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 06 15:11:04 PDT 2005
   */
   function SetTaskWatcherStatusById($id,$status)
   {
   	$qry = "UPDATE study_timeline_user SET status = '".$status."' WHERE study_timeline_user_id = ".$id;
   	return $this->executeQuery($qry);
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
	function getRole($role_id)
   {
      $qry  = "SELECT login FROM study_user WHERE study_id = ".$this->_study." AND role_id = '".$role_id."' AND status = 'A'";
      $rs = $this->executeQuery($qry);
      return ($this->rows) ? mysql_result($rs,0,'login') : 0;
   }

   /**
   * getStudyType()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function getStudyTypes()
   {
      $qry = "SELECT study_type_id, study_type_description FROM study_type WHERE status = 'A'";
      $rs = $this->executeQuery($qry);

      $study_types = array();
      while ($r = mysql_fetch_assoc($rs)) {
         $study_type_id = $r['study_type_id'];
         $study_types[$study_type_id] = $r['study_type_description'];
      }
      return $study_types;
   }

   /**
   * SetStudyType()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function SetStudyType($o)
   {
      $qry  = "UPDATE study SET study_type_id = ".$o['study_type_id'].", modified_by = ".$o['created_by'].", modified_date = NOW() ";
      $qry .= "WHERE study_id = ".$this->_study;
      return $this->executeQuery($qry);
   }

   /**
   * getAttrDef()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getAttrDef()
   {
      $qry = "SELECT study_attr, attribute_type, study_attribute_description FROM study_attr_def WHERE status = 'A'";
      return $this->executeQuery($qry);
   }

   /**
   * getAttrDef()
   * we have some db logic here, maybe we need two functions, getAttrText, GetAttrChar etc
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getAttr($o)
   {
      $table_name = $this->getAttrTableName($o['attribute_type']);

      $qry  = "SELECT s.study_value ";
      $qry .= "FROM ".$table_name." AS s ";
      $qry .= "WHERE s.status = 'A' AND s.study_id = ".$this->_study." AND s.study_attr = '".$o['study_attr']."'";

      $rs = $this->executeQuery($qry);

      if ($this->rows == 0) {
         return false;
      }
      $a = mysql_result($rs, 0, 'study_value');
      if ($o['attribute_type'] == 'X') {
         if ($a == "on") {
            return 'checked';
         }else{
            return "";
         }
      }

      return stripslashes($a);
   }

   /**
   * getAttrID()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 28 11:13:49 PDT 2006
   */
   function getAttrID($o)
   {
      $qry  = "SELECT study_attr_id ";
      $qry .= "FROM study_attr AS s ";
      $qry .= "WHERE s.status = 'A' AND s.study_id = ".$this->_study." AND s.study_attr = '".$o['study_attr']."'";

      $rs = $this->executeQuery($qry);
      if ($this->rows == 0)
         return false;
      return mysql_result($rs, 0, 'study_attr_id');
   }



   /**
   * GetStudyAttribute()
   * Retrieve an attriute value for a study
   * @param integer $study_id - ID of the study whose attribute value to be returned
   * @param string $attr_name - name of the attribute whose value to be returned
   * @param string $default_value - value to be returned if there is no such attribute for the given study
   * @return value of an attribute of a study
   * @throws
   * @access
   * @global
   */
   function GetStudyAttribute($study_id, $attr_name, $default_value='')
   {
      settype($study_id, "integer");
      $attr_name = mysql_real_escape_string($attr_name);

      $qry  = "SELECT study_value "
            . "FROM study_attr "
            . "WHERE status = 'A' AND study_id = $study_id AND study_attr = '$attr_name'";

      $rs = $this->executeQuery($qry);

      if (!$this->rows) {
         return $default_value;
      }

      return stripslashes(mysql_result($rs, 0, 'study_value'));
   }




    /**
   * getTemplateAttr()
   * we have some db logic here, maybe we need two functions, getAttrText, GetAttrChar etc
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getTemplateAttr($o)
   {
      $table_name = $this->GetTemplateAttrTableName($o['attribute_type']);

      $qry  = "SELECT s.study_value ";
      $qry .= "FROM ".$table_name." AS s ";
      $qry .= "WHERE s.status = 'A' AND s.study_template_id = ".$this->_template_id." AND s.study_attr = '".$o['study_attr']."'";

      $rs = $this->executeQuery($qry);

      if ($this->rows == 0) {
         return false;
      }

      if ($o['attribute_type'] == 'X') {
         return 'checked';
      }

      return stripslashes(mysql_result($rs,0,'study_value'));
   }

   /**
   * SetTemplateAttr()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function SetTemplateAttr($o)
   {
      $table_name = $this->GetTemplateAttrTableName($o['attr_type']);
      $o[$o['attr_key']] = mysql_escape_string($o[$o['attr_key']]);

      $qry  = "REPLACE INTO ".$table_name." ";
      $qry .= "(study_template_id,study_attr,study_value,created_by,created_date) ";
      $qry .= "VALUES (".$this->_template_id.",'".$o['attr_key']."','".$o[$o['attr_key']]."',".$o['created_by'].",NOW())";
      return  $this->executeQuery($qry);
   }

   /**
   * getAttrDef()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function setAttr($o)
   {
      $table_name = $this->getAttrTableName($o['attr_type']);
      $study_attr = mysql_real_escape_string($o['attr_key']);
      $study_value = mysql_real_escape_string($o[$o['attr_key']]);
      $created_by = sprintf('%d', $o['created_by']);

      $qry  = "REPLACE INTO ".$table_name." ";
      $qry .= "(study_id,study_attr,study_value,created_by,created_date) ";
      $qry .= "VALUES ({$this->_study},'$study_attr','$study_value', $created_by, NOW())";
      $rs = $this->executeQuery($qry);

      return true;
   }


   /**
   * SetStudyAttrV2()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function SetStudyAttrV2($study_id, $attr_type, $attr_key, $attr_value)
   {
      $table_name = $this->getAttrTableName($attr_type);
      $attr_key = mysql_escape_string($attr_key);
      $attr_value = mysql_escape_string($attr_value);
      settype($set_by, "integer");

      // Check whether the attribute has been set previously or it is a new attribute for the study
      $qry = "SELECT study_attr_id FROM $table_name WHERE study_id=$study_id AND study_attr='$attr_key' LIMIT 1";
      $rs = $this->executeQuery($qry);

      // Update the attribute if it has been setp previously
      if (mysql_num_rows($rs)){
         $study_attr_id = mysql_result($rs, FIRST_RECORD);
         $qry = "UPDATE $table_name "
              . " SET study_value='$attr_value', modified_by={$this->created_by}, modified_date=NOW() "
              . " WHERE study_attr_id = $study_attr_id ";

      // Insert a new attribute if it is new
      } else {
         $qry = "INSERT INTO $table_name "
              . "(study_id, study_attr, study_value, created_by, created_date) "
              . "VALUES ($study_id, '$attr_key', '$attr_value', {$this->created_by}, NOW()) ";
      }
      $rs = $this->executeQuery($qry);
   }


   /**
   * getAttrDef()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function delAttr($key)
   {
      $study_id = sprintf("%d", $this->_study);
      $key = mysql_real_escape_string($key);

      $qry = "DELETE FROM study_attr WHERE study_id =$study_id AND study_attr = '".$key."'";
      return $this->executeQuery($qry);
   }


   /**
   * DelTemplateAttr()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function DelTemplateAttr($key)
   {
      $qry = "DELETE FROM study_template_attr WHERE study_template_id = ".$this->_template_id." AND study_attr = '".$key."'";
      return $this->executeQuery($qry);
   }

   /**
   * getAttrDef()
   * study_task_id 26 is the study_on_hold task we dont want to include it in the default list
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getTaskList()
   {
      $qry  = "SELECT t.study_task_description, s.study_task_id, s.task_duration, s.sort_order, t.primary_task ";
      $qry .= "FROM study_type_task AS s ";
      $qry .= "LEFT OUTER JOIN study_task AS t ON s.study_task_id = t.study_task_id ";
      $qry .= "WHERE s.status = 'A' AND s.study_type_id = ".$this->_type." AND s.study_task_id != 26 ";
      $qry .= "ORDER BY s.sort_order ";

      $rs = $this->executeQuery($qry);
      while ($r = mysql_fetch_assoc($rs)) {
         $data[] = $r;
      }
      return $data;
   }


   /**
   * GetStudyTasks()
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetStudyTasks()
   {
      $qry  = "SELECT st.study_task_id, st.study_task_description, st.primary_task, st.study_stage_id ";
      $qry .= "FROM study_task AS st ";
      $qry .= "WHERE st.status = 'A' ";
      $rs = $this->executeQuery($qry);

      $study_tasks = array();
      while ($r = mysql_fetch_assoc($rs)){
         $study_task_id = $r['study_task_id'];
         $study_tasks[$study_task_id] = $r;
      }
      return $study_tasks;
   }



   /**
   * getAttrDef()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function setTimeLine($o)
   {
      $qry  = "INSERT INTO study_timeline (study_id,study_task_id,functional_group_id,login,sort_order,task_duration,created_by,created_date,revision_number) ";
      $qry .= "VALUES ({$this->_study},{$o['study_task_id']},{$o['functional_group_id']},{$o['login']},{$o['sort_order']},{$o['task_duration']},{$o['created_by']},NOW(),{$o['revision_number']})";
      $rs = $this->executeQuery($qry);
      return $this->rows;
   }

   /**
   * SetTemplateTimeLine()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function SetTemplateTimeLine($o)
   {
      $sort_order = sprintf('%d', $o['sort_order']);
      $task_duration = sprintf('%0.2f', $o['task_duration']);
      $created_by = sprintf('%d', $o['created_by']);


      $qry  = "INSERT INTO study_template_timeline ";
      $qry .= "(study_template_id,study_task_id,functional_group_id,login,sort_order,task_duration,created_by,created_date,revision_number) VALUES";
      $qry .= "(".$this->_template_id.",".$o['study_task_id'].",".$o['functional_group_id'].",".$o['login'].",".$o['sort_order'].",".$o['task_duration'].",".$o['created_by'].",NOW(),".$o['revision_number'].")";
      return $this->executeQuery($qry);
   }

   /**
   * UpdateTemplateTimeLine()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function UpdateTemplateTimeLine($o)
   {
      $qry  = "UPDATE study_template_timeline ";
      $qry .= "SET functional_group_id = ".$o['functional_group_id'].", login = ".$o['login'].", task_duration = ".$o['task_duration'].", ";
      $qry .= "    modified_by = ".$o['created_by'].", modified_date = NOW() ";
      $qry .= "WHERE study_template_timeline_id = ".$o['study_template_timeline_id'];
      return $this->executeQuery($qry);
   }
   /**
   * getTimeLine()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getTimeLine($revision)
   {
     $qry  = "SELECT study_timeline_id, study_id, study_task_id, task_duration, revision_number, ";
	  $qry .= "       functional_group_id, login, sort_order, estimated_complete_date_updated ";
     $qry .= "FROM study_timeline ";
     $qry .= "WHERE study_id = ".$this->_study." AND revision_number = ".$revision." AND status = 'A'";

     return $this->executeQuery($qry);
   }

   /**
   * getAttrDef()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function setTimeLineAttr($o)
   {
      $qry  = "REPLACE INTO study_timeline_attr (study_timeline_id,study_timeline_attr,study_timeline_value,created_by,created_date) ";
      $qry .= "VALUES (".$o['study_timeline_id'].",'".$o['study_timeline_attr']."',".$o['study_timeline_value'].",".$o['created_by'].",NOW())";
      return $this->executeQuery($qry);
   }

   /**
   * getTimeLineAttr()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getTimeLineAttr($study_timeline_id)
   {
      $qry  = "SELECT study_timeline_id,study_timeline_attr,study_timeline_value ";
      $qry .= "FROM study_timeline_attr ";
      $qry .= "WHERE study_timeline_id = ".$study_timeline_id;
      return $this->executeQuery($qry);
   }

	/**
   * GetTimeLineAttrByKey()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetTimeLineAttrByKey($o)
   {
      $qry  = "SELECT study_timeline_id, study_timeline_attr, study_timeline_value ";
      $qry .= "FROM study_timeline_attr ";
      $qry .= "WHERE study_timeline_id = ".$o['study_timeline_id']." AND study_timeline_attr = '".$o['study_timeline_attr']."'";
      return mysql_fetch_assoc($this->executeQuery($qry));
   }



	/**
   * DeleteTimeLineAttr()
   *
   * @param integer $study_timeline_id
   * @param string $study_timeline_attr
   * @return
   * @throws
   * @access
   * @global
   */
   function DeleteTimeLineAttr($study_timeline_id, $study_timeline_attr)
   {
      $study_timeline_id = sprintf("%d", $study_timeline_id);
      $study_timeline_attr = mysql_real_escape_string($study_timeline_attr);

      $qry = "DELETE FROM study_timeline_attr WHERE study_timeline_id=$study_timeline_id AND study_timeline_attr='$study_timeline_attr'";
      $this->executeQuery($qry);
   }


   /**
   * GetTaskNotifyList()
   *
   * @param
   * @param - study_timeline_id
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jun 07 08:25:55 PDT 2005
   */
   function GetTaskNotifyList($study_timeline_id)
   {
   	$qry  = "SELECT login, alert_mobile, alert_email, alert_jabber ";
   	$qry .= "FROM study_timeline_alert ";
   	$qry .= "WHERE study_timeline_id = ".$study_timeline_id;
   	return $this->executeQuery($qry);
   }

   /**
   * GetStudyIdByTimelineId()
   *
   * @param
   * @param - study_timeline_id
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jun 07 08:43:14 PDT 2005
   */
   function GetStudyIdByTimelineId($study_timeline_id)
   {
   	$qry  = "SELECT study_id FROM study_timeline WHERE study_timeline_id = ".$study_timeline_id;
   	$r = mysql_fetch_assoc($this->executeQuery($qry));
   	return ($r) ? $r['study_id'] : false;
   }

   /**
   * getAttrDef()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function setTimeLineCreated()
   {
      $qry = "UPDATE study SET timeline_created_date = NOW() WHERE study_id = ".$this->_study;
      return $this->executeQuery($qry);
   }

    /**
   * UnsetTimeLineCreated()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function UnsetTimeLineCreated()
   {
      $qry = "UPDATE study SET timeline_created_date = '' WHERE study_id = ".$this->_study;
      return $this->executeQuery($qry);
   }


   /**
   * GetStudyTimelineDetail()
   * returns
   * @param
   * @param - reference to timelineid
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jun 06 13:43:37 PDT 2005
   */


   /**
   * GetTimeLineDetails()
   *
   * returns study timeline details
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetTimeLineDetails($o = null)
   {
      $qry  =
      "SELECT
         m.study_timeline_id,
         t.study_task_description,
         g.functional_group_description,
         m.login,
         CONCAT(u.first_name,' ',u.last_name) AS task_owner,
         DATE_FORMAT(CONVERT_TZ(oest.study_timeline_value,'+00:00','".$this->timezone."'),'%Y-%m-%d %h:%i %p') AS orgest_date,
         DATE_FORMAT(CONVERT_TZ(cest.study_timeline_value,'+00:00','".$this->timezone."'),'%Y-%m-%d %h:%i %p') AS curest_date,
         DATE_FORMAT(CONVERT_TZ(comp.study_timeline_value,'+00:00','".$this->timezone."'),'%Y-%m-%d %h:%i %p') AS comp_date,
         DATE_FORMAT(CONVERT_TZ(oest.study_timeline_value,'+00:00','".$this->timezone."'),'%Y-%m-%d %H:%i:%S') AS mil_orgest_date,
         DATE_FORMAT(CONVERT_TZ(cest.study_timeline_value,'+00:00','".$this->timezone."'),'%Y-%m-%d %H:%i:%S') AS mil_curest_date,
         DATE_FORMAT(CONVERT_TZ(comp.study_timeline_value,'+00:00','".$this->timezone."'),'%Y-%m-%d %H:%i:%S') AS mil_comp_date,
         m.task_duration,
         IF(cest.study_timeline_value < NOW(),1,0) AS timepassed,
         m.sort_order,
         comp_by.study_timeline_value AS task_completed_by_login,
		   t.primary_task,
		   t.task_complete_requires_memo,
		   t.study_task_id,
		   tcb.last_name AS task_completed_by_name,
		   sta.alert_mobile,
		   sta.alert_email,
		   sta.alert_jabber,
			m.functional_group_id,
			IF(sh.parent_study_type_task_id IS NULL,0,1) AS is_child,
			COUNT(DISTINCT stc.study_timeline_id) AS n_comments,
		   COUNT(DISTINCT stu.login) AS task_watchers ";
      $qry .= "FROM study_timeline AS m ";
      $qry .= "LEFT OUTER JOIN study_task AS t ON t.study_task_id = m.study_task_id ";
      $qry .= "LEFT OUTER JOIN functional_group AS g ON g.functional_group_id = m.functional_group_id ";
      $qry .= "LEFT OUTER JOIN user AS u ON u.login = m.login ";
      $qry .= "LEFT OUTER JOIN study_timeline_attr AS oest ON oest.study_timeline_attr = 'ORIGEST' AND oest.study_timeline_id = m.study_timeline_id AND oest.status='A'";
      $qry .= "LEFT OUTER JOIN study_timeline_attr AS cest ON cest.study_timeline_attr = 'CUREST' AND cest.study_timeline_id = m.study_timeline_id AND cest.status='A'";
      $qry .= "LEFT OUTER JOIN study_timeline_attr AS comp ON comp.study_timeline_attr = 'COMPDATE' AND comp.study_timeline_id = m.study_timeline_id AND comp.status='A'";
      $qry .= "LEFT OUTER JOIN study_timeline_attr AS comp_by ON comp_by.study_timeline_attr = 'COMPBY' AND comp_by.study_timeline_id = m.study_timeline_id AND comp_by.status='A'";
      $qry .= "LEFT OUTER JOIN study_timeline_alert AS sta ON sta.study_timeline_id = m.study_timeline_id AND sta.login = '".$o['created_by']."' ";
      $qry .= "LEFT OUTER JOIN study_timeline_comment AS stc ON stc.study_timeline_id = m.study_timeline_id ";
      $qry .= "LEFT OUTER JOIN study_timeline_user AS stu ON stu.study_timeline_id = m.study_timeline_id ";
      $qry .= "LEFT OUTER JOIN user AS tcb ON tcb.login = comp_by.study_timeline_value ";
      $qry .= "LEFT OUTER JOIN study AS s ON s.study_id = m.study_id ";
      $qry .= "LEFT OUTER JOIN study_type_task_hiearchy AS sh ON sh.child_study_type_task_id = m.study_task_id ";
      $qry .= "WHERE m.status = 'A' AND m.study_id = ".$this->_study." AND m.revision_number = s.current_revision_number ";
      $qry .= "GROUP BY m.study_timeline_id ";
      $qry .= "ORDER BY m.sort_order ";

      $rs = $this->executeQuery($qry);
      while ($r = mysql_fetch_assoc($rs)) {
         $study_timeline_id = $r['study_timeline_id'];
         $data[$study_timeline_id] = $r;
      }

      return $data;
   }

   /**
   * getTemplateTimeLineDetail()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getTemplateTimeLineDetail()
   {
      $qry  =
      "SELECT stt.study_template_timeline_id AS study_timeline_id,
               st.study_task_description,
               fg.functional_group_description,
               stt.login,
               CONCAT(u.first_name,' ',u.last_name) AS task_owner,
               stt.task_duration,
               stt.sort_order,
					st.primary_task,
					st.task_complete_requires_memo,
					st.study_task_id,
					stt.functional_group_id,
					IF(sh.parent_study_type_task_id IS NULL,0,1) AS is_child ";
      $qry .= "FROM study_template_timeline AS stt ";
      $qry .= "LEFT OUTER JOIN study_task AS st ON st.study_task_id = stt.study_task_id ";
      $qry .= "LEFT OUTER JOIN functional_group AS fg ON fg.functional_group_id = stt.functional_group_id ";
      $qry .= "LEFT OUTER JOIN user AS u ON u.login = stt.login ";
      $qry .= "LEFT OUTER JOIN study_template AS stp ON stp.study_template_id = stt.study_template_id ";
      $qry .= "LEFT OUTER JOIN study_type_task_hiearchy AS sh ON sh.child_study_type_task_id = stt.study_task_id ";
      $qry .= "WHERE stt.status = 'A' AND stt.study_template_id = ".$this->_template_id." ";
      $qry .= "ORDER BY stt.sort_order ";

      $rs = $this->executeQuery($qry);
      while ($r = mysql_fetch_assoc($rs)) {
         $data[] = $r;
      }

      return $data;
   }

   /**
   * getFunctionalGroupId()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getFunctionalGroupId($study_timeline_id)
   {
      $qry = "SELECT functional_group_id FROM study_timeline WHERE study_timeline_id = ".$study_timeline_id." AND status = 'A'";
      return mysql_result($this->executeQuery($qry),0,'functional_group_id');
   }

    /**
   * GetTemplateFunctionalGroupId()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetTemplateFunctionalGroupId($study_timeline_id)
   {
      $qry = "SELECT functional_group_id FROM study_template_timeline WHERE study_template_timeline_id = ".$study_timeline_id." AND status = 'A'";
      return mysql_result($this->executeQuery($qry),0,'functional_group_id');
   }

   /**
   * updateTaskOwner() --> setTaskOwner();
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function setTaskOwner($o)
   {
      $login = sprintf('%d', $o['login']);   // it is possible that a user selected Functional Group, but didn't select a task user

      $qry  = "UPDATE study_timeline SET login = $login ";
      $qry .= "WHERE study_timeline_id = ".$o['study_timeline_id'];
      return $this->executeQuery($qry);
   }

    /**
   * GetTemplateAttrTableName()
   *
   * @param
   * @param -- this is not a dbClass function
   * @return
   * @throws
   * @access
   * @global
   */
   function GetTemplateAttrTableName($attr_type)
   {
       //default attr table
      $table_name = 'study_template_attr';
      switch ($attr_type){
         case 'M':
            $table_name = 'study_template_attr_memo';
            break;
      }
      return $table_name;
   }

   /**
   * getAttrDef()
   *
   * @param
   * @param -- this is not a dbClass function
   * @return
   * @throws
   * @access
   * @global
   */
   function getAttrTableName($attr_type)
   {
       //default attr table
      $table_name = 'study_attr';
      switch ($attr_type){
         case 'M':
            $table_name = 'study_attr_memo';
            break;
      }
      return $table_name;
   }

   /**
   * GetTimelineAlert()
   *
   * @param
   * @param - reference to timelineid
   * @return
   * @throws
   * @access
   * @global
   */
   function GetTimelineAlert($timeline_id,$login)
   {
      $qry  = "SELECT alert_mobile, alert_email, alert_jabber, alert_sent_date ";
      $qry .= "FROM study_timeline_alert ";
      $qry .= "WHERE study_timeline_id = ".$timeline_id." AND login = ".$login;
   	return $this->executeQuery($qry);
   }

   /**
   * SetTimelineAlert()
   *
   * @param
   * @param - GET/POST array
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jun 05 11:24:34 PDT 2005
   */
   function SetTimelineAlert($o)
   {
   	$qry =  "INSERT INTO study_timeline_alert (study_timeline_id,login,created_by,created_date) ";
   	$qry .= "VALUES (".$o['timeline_id'].",".$o['created_by'].",".$o['created_by'].",NOW())";
   	return $this->executeQuery($qry);
   }


   /**
   * SetTimelineEmailAlert()
   *
   * @param
   * @param - reference to timelineid
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jun 05 10:56:09 PDT 2005
   */
   function SetTimelineEmailAlert($o)
   {
      $qry  = "UPDATE study_timeline_alert SET alert_email = 1, modified_by = ".$o['created_by'].", modified_date = NOW() ";
      $qry .= "WHERE study_timeline_id = ".$o['timeline_id']." AND login = ".$o['created_by'];
      return $this->executeQuery($qry);
   }

    /**
   * UnsetTimelineEmailAlert()
   *
   * @param
   * @param - reference to timelineid
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jun 05 10:56:09 PDT 2005
   */
   function UnsetTimelineEmailAlert($o)
   {
      $qry  = "UPDATE study_timeline_alert SET alert_email = 0, modified_by = ".$o['created_by'].", modified_date = NOW() ";
      $qry .= "WHERE study_timeline_id = ".$o['timeline_id']." AND login = ".$o['created_by'];
      return $this->executeQuery($qry);
   }

   /**
   * SetTimelineMobileAlert()
   *
   * @param
   * @param - reference to timelineid
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jun 05 13:22:29 PDT 2005
   */
   function SetTimelineMobileAlert($o)
   {
   	$qry  = "UPDATE study_timeline_alert SET alert_mobile = 1, modified_by = ".$o['created_by'].", modified_date = NOW() ";
      $qry .= "WHERE study_timeline_id = ".$o['timeline_id']." AND login = ".$o['created_by'];
      return $this->executeQuery($qry);
   }

   /**
   * SetTimelineJabberAlert()
   *
   * @param
   * @param - reference to timelineid
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jun 05 13:22:29 PDT 2005
   */
   function SetTimelineJabberAlert($o)
   {
   	$qry  = "UPDATE study_timeline_alert SET alert_jabber = 1, modified_by = ".$o['created_by'].", modified_date = NOW() ";
      $qry .= "WHERE study_timeline_id = ".$o['timeline_id']." AND login = ".$o['created_by'];
      return $this->executeQuery($qry);
   }

   /**
   * UnsetTimelineMobileAlert()
   *
   * @param
   * @param - reference to timelineid
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jun 05 13:23:28 PDT 2005
   */
   function UnsetTimelineMobileAlert($o)
   {
   	$qry  = "UPDATE study_timeline_alert SET alert_mobile = 0, modified_by = ".$o['created_by'].", modified_date = NOW() ";
      $qry .= "WHERE study_timeline_id = ".$o['timeline_id']." AND login = ".$o['created_by'];
      return $this->executeQuery($qry);
   }

   /**
   * UnsetTimelineJabberAlert()
   *
   * @param
   * @param - reference to timelineid
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jun 05 13:23:28 PDT 2005
   */
   function UnsetTimelineJabberAlert($o)
   {
   	$qry  = "UPDATE study_timeline_alert SET alert_jabber = 0, modified_by = ".$o['created_by'].", modified_date = NOW() ";
      $qry .= "WHERE study_timeline_id = ".$o['timeline_id']." AND login = ".$o['created_by'];
      return $this->executeQuery($qry);
   }

   /**
   * GetTimelineComments()
   *
   * @param
   * @param - reference to timelineid
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jun 05 18:10:50 PDT 2005
   */
   function GetTimelineComments($timeline_id)
   {
      // for backward compatibility
      if (is_array($timeline_id)){
         $id = sprintf("%d", $timeline_id['p1']);
      } else {
         $id = sprintf("%d", $timeline_id);
      }

   	$qry  = "SELECT stc.study_timeline_id, stc.notes, stc.created_by, ";
   	$qry .= "       DATE_FORMAT(CONVERT_TZ(stc.created_date,'+00:00','".$this->timezone."'),'%Y-%m-%d %h:%i %p') AS comment_date, ";
   	$qry .= "       cb.last_name AS comment_by ";
   	$qry .= "FROM study_timeline_comment AS stc ";
   	$qry .= "LEFT OUTER JOIN user AS cb ON cb.login = stc.created_by ";
   	$qry .= "WHERE stc.study_timeline_id = $id ";

   	$rs = $this->executeQuery($qry);
   	while ($r = mysql_fetch_assoc($rs)) {
   	   $r['notes'] = stripslashes($r['notes']);
   		$data[] = $r;
   	}
   	return $data;
   }

   /**
   * SetTimelineComment()
   *
   * @param
   * @param - reference to timelineid
   * @return
   * @throws
   * @access
   * @global
   * @since  - Sun Jun 05 18:35:40 PDT 2005
   */
   function SetTimelineComment($o)
   {
      $o['notes'] = mysql_escape_string($o['notes']);

   	$qry  = "INSERT INTO study_timeline_comment ";
   	$qry .= " (study_timeline_id, notes, created_by, created_date) ";
   	$qry .= "VALUES (".$o['study_timeline_id'].",'".$o['notes']."',".$o['created_by'].",NOW()) ";
   	return $this->executeQuery($qry);
   }

   /**
   * setNote()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function setNote($o)
   {
      $study_id = sprintf('%d', $o['study_id']);
      $comment_type_id = sprintf("%d", $o['comment_type_id']);
      $alert_level_id  = sprintf("%d", $o['alert_level_id']);
      $department_id   = sprintf("%d", $o['department_id']);
      $notes = mysql_real_escape_string($o['notes']);

      if (!$study_id){
         echo "Error. Cannot save study note because study_id is not defined!. Please contact to HB Team.";
         var_dump($o);
         exit();
      }

      $qry  = "INSERT INTO study_comment (study_id,department_id,comment_date,comment_type_id,alert_level_id,notes,created_by,created_date) ";
      $qry .= "VALUES ($study_id,$department_id,NOW(),$comment_type_id,$alert_level_id,'$notes',{$this->created_by},NOW())";
      return $this->executeQuery($qry);
   }

   /**
   * getAttrDef()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getNotes($comment_type_id)
   {
      $qry  = "SELECT DATE_FORMAT(CONVERT_TZ(n.comment_date,'+00:00','".$this->timezone."'),'%Y-%m-%d %H:%i') AS comment_date, ";
      $qry .= "  n.comment_date AS comment_timestamp, ";
      $qry .= "  a.alert_level_description, ";
      $qry .= "  n.notes, CONCAT(u.first_name,' ',u.last_name) AS name, ";
      $qry .= "  n.study_comment_id, ";
      $qry .= "  n.department_id, d.department, ";
      $qry .= "  scf.study_comment_file_name, scf.study_comment_file_title ";
      $qry .= "FROM study_comment AS n ";
      $qry .= "LEFT OUTER JOIN alert_level AS a ON a.alert_level_id = n.alert_level_id ";
      $qry .= "LEFT OUTER JOIN user AS u ON u.login = n.created_by ";
      $qry .= "LEFT OUTER JOIN study_comment_file AS scf ON scf.study_comment_id = n.study_comment_id ";
      $qry .= "LEFT OUTER JOIN department AS d ON d.department_id = n.department_id ";
      $qry .= "WHERE n.status = 'A' AND n.study_id = ".$this->_study." AND n.comment_type_id = ".$comment_type_id. " ";
      $qry .= "GROUP BY n.study_comment_id ";

      $rs = $this->executeQuery($qry);

      $data = array();
      while($r = mysql_fetch_assoc($rs)) {
         $r['comment_timestamp'] = strtotime($r['comment_timestamp']);
         $study_comment_id = $r['study_comment_id'];
         $data[] = $r;
      }

      return $data;
   }

   /**
   * GetAlertTracking()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Jun 23 10:30:48 PDT 2005
   */
   function GetAlertTracking()
   {
      $qry  = "SELECT DATE_FORMAT(CONVERT_TZ(n.comment_date,'+00:00','".$this->timezone."'),'%Y-%m-%d %h:%i %p') AS comment_date, n.alert_level_id, a.alert_level_description, n.notes, CONCAT(u.first_name,' ',u.last_name) AS name, n.study_comment_id ";
      $qry .= "FROM study_comment AS n ";
      $qry .= "LEFT OUTER JOIN alert_level AS a ON a.alert_level_id = n.alert_level_id ";
      $qry .= "LEFT OUTER JOIN user AS u ON u.login = n.created_by ";
      $qry .= "WHERE n.status = 'A' AND n.study_id = ".$this->_study." AND n.comment_type_id = 1 ";
      $qry .= "ORDER BY n.comment_date";
      //echo $qry;
      return $this->executeQuery($qry);
   }

   /**
   * getAttrDef()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getActiveTimeLineId()
   {
      $qry  = "SELECT m.study_timeline_id ";
      $qry .= "FROM study_timeline AS m ";
      $qry .= "LEFT OUTER JOIN study_timeline_attr AS comp ON comp.study_timeline_attr = 'COMPDATE' AND comp.study_timeline_id = m.study_timeline_id ";
      $qry .= "LEFT OUTER JOIN study AS s ON s.study_id = m.study_id ";
      $qry .= "WHERE m.status = 'A' AND s.current_revision_number = m.revision_number AND m.study_id = ".$this->_study." AND comp.study_timeline_value IS NULL ";
		$qry .= "AND m.login != 0 ";
      $qry .= "ORDER BY m.sort_order ";

      $r = mysql_fetch_assoc($this->executeQuery($qry));
      return ($r) ? $r['study_timeline_id'] : $r;
   }

   /**
   * setAlertLevel()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function setAlertLevel($o)
   {
      $qry = "UPDATE study SET alert_level_id = ".$o['alert_level_id']." WHERE study_id = ".$this->_study;
      return $this->executeQuery($qry);
   }

   /**
   * getAlertLevel()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getAlertLevel()
   {
      $qry = "SELECT alert_level_id FROM study WHERE study_id = ".$this->_study;
      return mysql_result($this->executeQuery($qry),0,'alert_level_id');
   }





   /**
   * GetLastStudyAlert()
   * Retrieve latest/last alert history of a study
   *
   * @param integer $study_id - ID of the study whose alerts to be retrieved
   * @return array
   * @throws
   * @access
   * @global
   */
   function GetLastStudyAlert($study_id)
   {
      settype($study_id, 'integer');
      $qry  = "SELECT "
            . "  sc.study_comment_id, "
            . "  CONVERT_TZ(sc.comment_date,'+00:00','{$this->timezone}') AS comment_date, "
            . "  UNIX_TIMESTAMP(CONVERT_TZ(sc.comment_date,'+00:00','{$this->timezone}')) AS comment_timestamp, "
            . "  sc.comment_type_id, "
            . "  sc.department_id, "
            . "  sc.alert_level_id, "
            . "  a.alert_level_description, "
            . "  sc.notes, "
            . "  u.first_name, "
            . "  u.last_name "
            . "FROM study_comment AS sc "
            . "  LEFT OUTER JOIN alert_level AS a ON a.alert_level_id = sc.alert_level_id "
            . "  LEFT OUTER JOIN user AS u ON u.login = sc.created_by "
            . "WHERE sc.study_id = $study_id "
            . "ORDER BY sc.comment_date DESC "
            . "LIMIT 1 ";
      $rs = $this->executeQuery($qry);
      return mysql_fetch_assoc($rs);
   }


   /**
   * GetAlertHistory()
   * Retrieve alert history of a study within a specific period of time
   *
   * @param integer $study_id - ID of the study whose alerts to be retrieved
   * @param integer/string $start_date - time period of the alerts to be retrieved
   * @param integer/string $end_date     time can be in Unix timestamp or MySQL datetime format
   * @return array
   * @throws
   * @access
   * @global
   */
   function GetAlertHistory($study_id, $start_period="", $end_period="")
   {
      settype($study_id, 'integer');

      $start_period_condition = "";
      if( $start_period ){
         $mysql_start_period = $start_period;
         if (is_numeric($start_period)){
            $mysql_start_period = @date("Y-m-d H:i:s", $start_period); // convert timestamp to mySQL datetime format
         }
         $start_period_condition = "AND comment_date >= CONVERT_TZ('{$mysql_start_period}','{$this->timezone}','+00:00')";
      }

      $end_period_contidiont = "";
      if( $end_period ){
         $mysql_end_period = $end_period;
         if (is_numeric($end_period)){
            $mysql_end_period = @date("Y-m-d H:i:s", $end_period); // convert timestamp to mySQL datetime format
         }
         $end_period_contidiont   = "AND comment_date <= CONVERT_TZ('{$mysql_end_period}','{$this->timezone}','+00:00')";
      }

      $qry  = "SELECT "
            . "  s.study_comment_id, "
            . "  CONVERT_TZ(s.comment_date,'+00:00','{$this->timezone}') AS comment_date, "
            . "  UNIX_TIMESTAMP(CONVERT_TZ(s.comment_date,'+00:00','{$this->timezone}')) AS comment_timestamp, "
            . "  s.notes, "
            . "  s.alert_level_id, "
            . "  al.alert_level_description "
            . "FROM "
            . "  study_comment AS s "
            . "  LEFT OUTER JOIN alert_level AS al ON s.alert_level_id = al.alert_level_id "
            . "WHERE "
            . "  study_id = $study_id "
            . "  $start_period_condition "
            . "  $end_period_contidiont "
            . "ORDER BY "
            . "  s.comment_date ASC, s.created_date ASC ";

      $rs = $this->executeQuery($qry);

      $alerts = array();
      while($r = mysql_fetch_assoc($rs)){
         $study_comment_id = $r['study_comment_id'];
         $alerts[$study_comment_id] = $r;
      }

      return $alerts;
   }


   /**
   * setStatus()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function setStatus($status)
   {
      $qry = "UPDATE study SET study_status_id = ".$status." WHERE study_id = ".$this->_study;
      return $this->executeQuery($qry);
   }





   /**
   * GetOpenStudiesAsOf()
   * Retrieve a list of studies that are open at a point in time.
   *
   * @param  $integer $start_period - studies to be returned if they were open anytime within this time period
   * @param  $integer $end_period
   * @param  $integer $employee_number - return the studies whose account manager is this person.
   * @return $array - studies in array format with Study ID as the key
   * @throws
   * @access
   */
   function GetOpenStudiesInPeriod($start_period, $end_period, $employee_number)
   {
      settype($employee_number, "integer");

      $mysql_start_period = @date("Y-m-d H:i:s", $start_period); // convert timestamp to mySQL's datetime format
      $mysql_end_period = @date("Y-m-d H:i:s", $end_period); // convert timestamp to mySQL's datetime format

      $qry  = "SELECT "
            . "  s.study_id, "
            . "  s.study_name, "
            . "  s.study_type_id, "
            . "  st.study_type_description, "
            . "  s.created_date, "
            . "  CONVERT_TZ(s.created_date,'+00:00','{$this->timezone}')  AS start_date, "
            . "  CONVERT_TZ(s.study_invoice_date,'+00:00','{$this->timezone}') AS end_date, "
            . "  t.study_type_description, "
            . "  IF(sa_company_name.study_value != '', sa_company_name.study_value, 'COMPLETE NET-MR PROFILE') AS company_name, "
            . "  a.alert_level_description "
            . "FROM study AS s "
            . "  LEFT OUTER JOIN study_attr  AS sa_company_name ON sa_company_name.study_id = s.study_id AND sa_company_name.study_attr='ACCOUNT_NAME' "
            . "  LEFT OUTER JOIN study_type  AS t  ON t.study_type_id = s.study_type_id "
            . "  LEFT OUTER JOIN study_user  AS su ON su.role_id = ".PRIMARY_ACCT_MGR." AND su.study_id = s.study_id "
            . "  LEFT OUTER JOIN alert_level AS a  ON s.alert_level_id = a.alert_level_id "
            . "  LEFT OUTER JOIN study_stage AS ss ON ss.study_stage_id = s.study_stage_id "
            . "  LEFT OUTER JOIN study_type  AS st ON st.study_type_id = s.study_type_id "
            . "WHERE "
            . "  s.status = 'A' "
            . "  AND su.login = $employee_number "
            . "  AND s.created_date < CONVERT_TZ('{$mysql_end_period}','{$this->timezone}','+00:00') "   // don't include studies that are in the future
            . "  AND (s.study_invoice_date = '0000-00-00 00:00:00' "                               // studies that are still in process should be included
            . "       OR s.study_invoice_date > CONVERT_TZ('{$mysql_start_period}','{$this->timezone}','+00:00') "  // don't include studies that are already ended
            . "      ) "
            . "GROUP BY s.study_id "
            . "ORDER BY s.study_name "
            . "";
      $rs = $this->executeQuery($qry);

      $data = array();
      while($r = mysql_fetch_assoc($rs)){
         $study_id = $r['study_id'];
         $data[$study_id] = $r;
      };

      return $data;
   }

   /**
   * GetOpenStudyList()
   * Retrieve current list of open studie.
   * @return $array - studies in array format with Study ID as the key
   * @throws
   * @access
   */
function GetOpenStudyList(){

	      $qry = 	"SELECT study_id FROM study  WHERE study_status_id = ".STATUS_OPEN;
     		$rs = $this->executeQuery($qry);
     		return $rs;
     		
     		 
	
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
         if( !preg_match("/^([^_]+)_(.+)$/",$key,$matches)){
            continue;
         }
         $prefix = $matches[1];
         $name = $matches[2];

         switch( $prefix ){
            case "sc":
               $field = $o["sf_{$name}"];
               $value = $o[$key];

               if ($value == ''){
                  break;
               }
               if (@$o["so_{$name}"] == 'WILDCARD') {
                  $str .= " AND $field LIKE '%".mysql_real_escape_string($value)."%' ";
               } else {
                  if (is_numeric($o[$key])){
                     $str .= " AND $field = $value ";
                  } else {
                     $str .= " AND $field = '".mysql_real_escape_string($value)."' ";
                  }
               }
               break;

            case "dtc":
               $field = $val;
               $begin = $o["{$name}_begin"];
               $end   = $o["{$name}_end"];
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


   /**
   * GetPartnerId()
   *
   * @param integer $study_id
   * @return integer $partner_id
   * @throws
   * @access
   * @global
   */
   function GetPartnerId($study_id){
      settype($study_id, "integer");
      $qry  = "SELECT partner_id FROM study WHERE study_id={$study_id}";
      $rs = $this->executeQuery($qry);
      if (!$this->numRows){
         return false;
      }
      return mysql_result($rs, FIRST_RECORD);
   }





   function GetTotalStudies($extraFilters="", $extraJoins="")
   {
      $qry  = "SELECT ";
      $qry .= "  COUNT(DISTINCT s.study_id) AS count ";
      $qry .= "FROM study AS s ";
      $qry .= "  LEFT OUTER JOIN alert_level AS a ON s.alert_level_id = a.alert_level_id ";
      $qry .= "  LEFT OUTER JOIN functional_group AS fg ON fg.functional_group_id = s.current_task_functional_group_id ";
      $qry .= "  LEFT OUTER JOIN study_attr AS sa_country ON sa_country.study_id = s.study_id AND sa_country.study_attr='COUNTRY_CODE' ";
      $qry .= "  LEFT OUTER JOIN country ON country.country_code=sa_country.study_value ";
      $qry .= "  LEFT OUTER JOIN study_attr AS sa_company_name ON sa_company_name.study_id = s.study_id AND sa_company_name.study_attr='ACCOUNT_NAME' ";
      $qry .= "  LEFT OUTER JOIN study_attr AS sa_n ON sa_n.study_id = s.study_id AND sa_n.study_attr = 'N_COMPLETE' ";
      $qry .= "  LEFT OUTER JOIN study_attr AS sa_nc ON sa_nc.study_id = s.study_id AND sa_nc.study_attr = 'NUMBER_COMPLETED' ";
      $qry .= "  LEFT OUTER JOIN study_attr AS sa_pvalue ON sa_pvalue.study_id = s.study_id AND sa_pvalue.study_attr = 'PVALUE' ";
      $qry .= "  LEFT OUTER JOIN study_attr AS sa_job ON sa_job.study_id = s.study_id AND sa_job.study_attr = 'JOBNUMBER' ";
      $qry .= "  LEFT OUTER JOIN study_attr AS sa_po ON sa_po.study_id = s.study_id AND sa_po.study_attr = 'PONUMBER' ";
      $qry .= "  LEFT OUTER JOIN study_stage AS ss ON ss.study_stage_id = s.study_stage_id ";
      $qry .= "  LEFT OUTER JOIN study_status AS sstatus ON sstatus.study_status_id = s.study_status_id ";
      $qry .= "  LEFT OUTER JOIN study_sample_type AS sst ON sst.study_id=s.study_id AND sst.status='A'";
      $qry .= "  LEFT OUTER JOIN study_type AS t ON t.study_type_id = s.study_type_id ";
      $qry .= "  LEFT OUTER JOIN study_user AS su_acct_exec ON su_acct_exec.role_id = ".PRIMARY_ACCT_EXEC." AND su_acct_exec.study_id = s.study_id ";
      $qry .= "  LEFT OUTER JOIN study_user AS su_acct_mgr ON su_acct_mgr.role_id = ".PRIMARY_ACCT_MGR." AND su_acct_mgr.study_id = s.study_id ";
      $qry .= "  LEFT OUTER JOIN study_user AS su ON su.study_id = s.study_id ";
      $qry .= "  LEFT OUTER JOIN study_cost AS sc ON sc.study_id = s.study_id ";
      $qry .= "  LEFT OUTER JOIN study_timeline AS st ON st.study_id = s.study_id ";
      $qry .= "  LEFT OUTER JOIN user AS u_acct_mgr ON u_acct_mgr.login = su_acct_mgr.login ";
      $qry .= "  LEFT OUTER JOIN user AS tow ON s.current_task_owner_login = tow.login ";
      $qry .= "$extraJoins ";
      $qry .= "WHERE s.status = 'A' $extraFilters ";
      //$qry .= "GROUP BY s.study_id ";

      $rs = $this->executeQuery($qry);
      return mysql_result($rs, 0, "count");
   }



   function GetLastStudyComment(&$comment, $study_id)
   {
      $comment = array();
      settype($study_id, "integer");

      $qry  = "SELECT "
            . "  sc.study_id, "
            . "  sc.alert_level_id, "
            . "  a.alert_level_description, "
            . "  sc.comment_type_id, "
            . "  sc.notes, "
            . "  sc.created_by, "
            . "  u_created_by.first_name AS created_by_first_name, "
            . "  u_created_by.last_name AS created_by_last_name, "
            . "  CONVERT_TZ(sc.created_date,'+00:00','{$this->timezone}') AS created_date, "
            . "  UNIX_TIMESTAMP(CONVERT_TZ(sc.created_date,'+00:00','{$this->timezone}')) AS created_timestamp "
            . "FROM study_comment AS sc "
            . "  LEFT OUTER JOIN alert_level AS a ON a.alert_level_id = sc.alert_level_id "
	         . "  LEFT OUTER JOIN user AS u_created_by ON u_created_by.login = sc.created_by "
            . "WHERE sc.status='A' AND sc.study_id = $study_id "
            . "ORDER BY sc.created_date DESC "
            . "LIMIT 1 ";

      $rs = $this->executeQuery($qry);
      $comment = mysql_fetch_assoc($rs);
   }



   function GetAccountTierSummary()
   {
      $qry  = "(SELECT "
            . "  su_am.login, "
            . "  u_am.first_name, u_am.last_name, "
            . "  u_am.location_id, "
            . "  l_am.location_description, "
            . "  sa_account_tier.study_value AS account_tier_level, "
            . "  COUNT(s.study_id) AS total_studies "
            . "FROM study AS s "
            . "  LEFT OUTER JOIN study_user AS su_am ON su_am.role_id=".PRIMARY_ACCT_MGR." AND su_am.study_id=s.study_id "
            . "  LEFT OUTER JOIN user AS u_am ON u_am.login=su_am.login "
            . "  LEFT OUTER JOIN location AS l_am ON l_am.location_id = u_am.location_id "
            . "  LEFT OUTER JOIN study_attr AS sa_account_tier ON sa_account_tier.study_attr='GLOBAL_ACCOUNT_TIER_LEVEL' AND sa_account_tier.study_id=s.study_id "
            . "  JOIN study_attr AS sa_global_study_handled ON sa_global_study_handled.study_attr='GLOBAL_STUDY_HANDLED_BY_AM' AND sa_global_study_handled.study_value='1' AND sa_global_study_handled.study_id=s.study_id "
            . "  LEFT OUTER JOIN pjm_element AS pe_netmr ON pe_netmr.pjm_element_type_id=".PJM_ELEMENT_TYPE_NETMR_STUDY." AND pe_netmr.pjm_element_reference_id=s.study_id "
            . "WHERE s.study_status_id=".STATUS_OPEN." AND pe_netmr.pjm_element_id IS NULL "
            . "GROUP BY su_am.login, sa_account_tier.study_value )"

            . "UNION "

            . "(SELECT "
            . "  pu_am.user_id, "
            . "  u_am.first_name, u_am.last_name, "
            . "  u_am.location_id, "
            . "  l_am.location_description, "
            . "  MAX(sa_account_tier.study_value) AS account_tier_level, "
             . " 1 AS total_studies "
            . "FROM pjm AS p "
            . "  LEFT OUTER JOIN pjm_user AS pu_am ON pu_am.role_id=".PRIMARY_ACCT_MGR." AND pu_am.pjm_id=p.pjm_id "
            . "  LEFT OUTER JOIN user AS u_am ON u_am.login=pu_am.user_id "
            . "  LEFT OUTER JOIN location AS l_am ON l_am.location_id = u_am.location_id "
            . "  JOIN pjm_element AS pe ON pe.pjm_id=p.pjm_id AND pe.pjm_element_type_id=".PJM_ELEMENT_TYPE_NETMR_STUDY." "
            . "  LEFT OUTER JOIN study_attr AS sa_account_tier ON sa_account_tier.study_attr='GLOBAL_ACCOUNT_TIER_LEVEL' AND sa_account_tier.study_id=pe.pjm_element_reference_id "
            . "  JOIN study_attr AS sa_global_study_handled ON sa_global_study_handled.study_attr='GLOBAL_STUDY_HANDLED_BY_AM' AND sa_global_study_handled.study_value='1' AND sa_global_study_handled.study_id=pe.pjm_element_reference_id "
            . "WHERE p.pjm_status_id=".STATUS_OPEN." "
            . "GROUP BY pu_am.user_id, p.pjm_id )";


      $rs = $this->executeQuery($qry);
      $locations = array();
      while($r = mysql_fetch_assoc($rs)) {
         $am_login = $r['login'];
         $location_id = $r['location_id'] ? $r['location_id'] : 0;
         $account_tier_level = $r['account_tier_level'] ? $r['account_tier_level'] : 0;


         if (!isset($locations[$location_id])){
            $locations[$location_id] = array(
               'location_description' => $r['location_description'],
               'location_id' => $location_id,
               'account_managers' => array(),
               'account_tier_summary' => array()
            );
         }
         $location =& $locations[$location_id];
         $location_account_tier_summary =& $locations[$location_id]['account_tier_summary'];

         if (!isset($location['account_managers'][$am_login])){
            $location['account_managers'][$am_login] = array(
               'last_name' => $r['last_name'],
               'first_name' => $r['first_name'],
               'login' => $r['login'],
               'account_tier_summary' => array()
            );
         }
         $am_account_tier_summary =& $location['account_managers'][$am_login]['account_tier_summary'];


         @$am_account_tier_summary[$account_tier_level] += $r['total_studies'];
         @$location_account_tier_summary[$account_tier_level] += $r['total_studies'];

      }
      return $locations;
   }



   /**
   * getListV2()
   *
   * @param string $extraFilters - additional SQL where clause (must begin with AND operator)
   * @param string $extraJoins - additional join operations
   * @param string $limit - "<rout_count>" or "<offset>, <row_count>"
   * @param string $order_by - <col_name> <expr>
   * @return array - list of studies.
   * @throws
   * @access
   * @global
   */
   function getListV2($extraFilters="", $extraJoins="", $limit="", $order_by="")
   {
      $qry  = "SELECT ";
      $qry .= "  s.study_id, ";
      $qry .= "  IF(sa_company_name.study_value != '', sa_company_name.study_value, 'No Company Name') AS company_name, ";
      $qry .= "  s.study_name, t.study_type_description, ";
      $qry .= "  s.start_date, ";
      $qry .= "  sa_country.study_value AS country_code, ";
      $qry .= "  country.country_description, ";
      $qry .= "  CONCAT(u_acct_mgr.first_name,' ',u_acct_mgr.last_name) AS acct_mgr, ";
      $qry .= "  CONCAT(u_acct_exec.first_name,' ',u_acct_exec.last_name) AS acct_exec, ";
      $qry .= "  CONCAT(tow.first_name,' ',tow.last_name) AS task_owner, ";
      $qry .= "  DATE_FORMAT(CONVERT_TZ(s.current_estimated_complete_date,'+00:00','".$this->timezone."'),'%Y-%m-%d')  AS current_estimated_complete_date, ";
      $qry .= "  a.alert_level_description, ";
      $qry .= "  s.alert_level_id, ";
      $qry .= "  IF (fg.functional_group_description != '', fg.functional_group_description, fg.functional_group_id) AS functional_group_description,  ";
      $qry .= "  fg.functional_group_abbrev, ";
      $qry .= "  ss.study_stage_description, ROUND(sa_nc.study_value / sa_n.study_value * 100) AS percent_quota_progress  ,  ";
      $qry .= "  s.partner_id,  ";
      $qry .= "  sstatus.study_status_description AS study_status_description, ";
      $qry .= "  CAST(REPLACE(REPLACE(TRIM(sa_pvalue.study_value), '$', ''), ',', '') AS SIGNED) AS project_value, ";
      $qry .= "  sa_job.study_value AS job_number, ";
      $qry .= "  sa_po.study_value AS po_number ";
      $qry .= "FROM study AS s ";
      $qry .= "  LEFT OUTER JOIN alert_level AS a ON s.alert_level_id = a.alert_level_id ";
      $qry .= "  LEFT OUTER JOIN functional_group AS fg ON fg.functional_group_id = s.current_task_functional_group_id ";
      $qry .= "  LEFT OUTER JOIN study_attr AS sa_country ON sa_country.study_id = s.study_id AND sa_country.study_attr='COUNTRY_CODE' ";
      $qry .= "  LEFT OUTER JOIN country ON country.country_code=sa_country.study_value ";
      $qry .= "  LEFT OUTER JOIN study_attr AS sa_company_name ON sa_company_name.study_id = s.study_id AND sa_company_name.study_attr='ACCOUNT_NAME' ";
      $qry .= "  LEFT OUTER JOIN study_attr AS sa_n ON sa_n.study_id = s.study_id AND sa_n.study_attr = 'N_COMPLETE' ";
      $qry .= "  LEFT OUTER JOIN study_attr AS sa_nc ON sa_nc.study_id = s.study_id AND sa_nc.study_attr = 'NUMBER_COMPLETED' ";
      $qry .= "  LEFT OUTER JOIN study_attr AS sa_pvalue ON sa_pvalue.study_id = s.study_id AND sa_pvalue.study_attr = 'PVALUE' ";
      $qry .= "  LEFT OUTER JOIN study_attr AS sa_job ON sa_job.study_id = s.study_id AND sa_job.study_attr = 'JOBNUMBER' ";
      $qry .= "  LEFT OUTER JOIN study_attr AS sa_po ON sa_po.study_id = s.study_id AND sa_po.study_attr = 'PONUMBER' ";
      $qry .= "  LEFT OUTER JOIN study_stage AS ss ON ss.study_stage_id = s.study_stage_id ";
      $qry .= "  LEFT OUTER JOIN study_status AS sstatus ON sstatus.study_status_id = s.study_status_id ";
      $qry .= "  LEFT OUTER JOIN study_sample_type AS sst ON sst.study_id=s.study_id AND sst.status='A'";
      $qry .= "  LEFT OUTER JOIN study_type AS t ON t.study_type_id = s.study_type_id ";
      $qry .= "  LEFT OUTER JOIN study_user AS su_acct_exec ON su_acct_exec.role_id = ".PRIMARY_ACCT_EXEC." AND su_acct_exec.study_id = s.study_id ";
      $qry .= "  LEFT OUTER JOIN study_user AS su_acct_mgr ON su_acct_mgr.role_id = ".PRIMARY_ACCT_MGR." AND su_acct_mgr.study_id = s.study_id ";
      $qry .= "  LEFT OUTER JOIN study_user AS su ON su.study_id = s.study_id ";
      $qry .= "  LEFT OUTER JOIN study_timeline AS st ON st.study_id = s.study_id ";
      $qry .= "  LEFT OUTER JOIN user AS u_acct_mgr ON u_acct_mgr.login = su_acct_mgr.login ";
      $qry .= "  LEFT OUTER JOIN user AS u_acct_exec ON u_acct_exec.login = su_acct_exec.login ";
      $qry .= "  LEFT OUTER JOIN user AS tow ON s.current_task_owner_login = tow.login ";
      $qry .= "$extraJoins ";
      $qry .= "WHERE s.status = 'A' $extraFilters ";
      $qry .= "GROUP BY s.study_id ";

      if ($order_by) $qry .= "ORDER BY $order_by , s.study_name ASC, s.study_id ASC ";
      if ($limit)    $qry .= "LIMIT $limit ";
      $data = array();
      $rs = $this->executeQuery($qry);
      while($r = mysql_fetch_assoc($rs)) {
         $stuy_id = $r['study_id'];
         //$r['project_value'] = str_replace(array("$", ","), "", $r["project_value"]);
         $data[$stuy_id] = $r;
      }
      return $data;
   }

   /**
   * getList()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getList($o)
   {
      $qry  = "SELECT s.study_id, ";
      $qry .= "IF(sa_company_name.study_value != '', sa_company_name.study_value, 'COMPLETE NET-MR PROFILE') AS company_name, ";
      $qry .= "s.study_name, t.study_type_description, ";
      $qry .= "CONCAT(u.first_name,' ',u.last_name) AS acct_mgr, ";
      $qry .= "DATE_FORMAT(CONVERT_TZ(s.current_estimated_complete_date,'+00:00','".$this->timezone."'),'%Y-%m-%d')  AS current_estimated_complete_date, a.alert_level_description, ";
      $qry .= "IF (fg.functional_group_description != '', fg.functional_group_description, '') AS functional_group_description,  ";
      $qry .= "ss.study_stage_description, ROUND(sa_nc.study_value / sa_n.study_value * 100) AS percent_quota_progress  ,  ";
      $qry .= "CONCAT(tow.first_name,' ',tow.last_name) AS task_owner, ";
      $qry .= "s.partner_id,  ";
      $qry .= "sstatus.study_status_description AS study_status_description ";
      $qry .= "FROM study AS s ";
      $qry .= "LEFT OUTER JOIN study_type AS t ON t.study_type_id = s.study_type_id ";
      $qry .= "LEFT OUTER JOIN study_user AS su ON su.role_id = ".PRIMARY_ACCT_MGR." AND su.study_id = s.study_id ";
      $qry .= "LEFT OUTER JOIN study_user AS su_2 ON su_2.study_id = s.study_id ";
      $qry .= "LEFT OUTER JOIN user AS u ON u.login = su.login ";
      $qry .= "LEFT OUTER JOIN functional_group AS fg ON fg.functional_group_id = s.current_task_functional_group_id ";
      $qry .= "LEFT OUTER JOIN user AS tow ON s.current_task_owner_login = tow.login ";
      $qry .= "LEFT OUTER JOIN alert_level AS a ON s.alert_level_id = a.alert_level_id ";
      $qry .= "LEFT OUTER JOIN study_stage AS ss ON ss.study_stage_id = s.study_stage_id ";
      $qry .= "LEFT OUTER JOIN study_attr AS sa_n ON sa_n.study_id = s.study_id AND sa_n.study_attr = 'N_COMPLETE' ";
      $qry .= "LEFT OUTER JOIN study_attr AS sa_nc ON sa_nc.study_id = s.study_id AND sa_nc.study_attr = 'NUMBER_COMPLETED' ";
      $qry .= "LEFT OUTER JOIN study_attr AS sa_company_name ON sa_company_name.study_id = s.study_id AND sa_company_name.study_attr='ACCOUNT_NAME' ";
      $qry .= "LEFT OUTER JOIN study_timeline AS st ON st.study_id = s.study_id ";
      $qry .= "LEFT OUTER JOIN study_status AS sstatus ON sstatus.study_status_id = s.study_status_id ";
      $qry .= "WHERE s.status = 'A' AND s.study_status_id IN (".$o['study_status_id'].") ".$o['ex_where'];
      $qry .= " GROUP BY s.study_id ";
      $qry .= $o['order_tag1'];

      $rs = $this->executeQuery($qry);

      while($r = mysql_fetch_assoc($rs)) {
         $data[] = $r;
      }
      return $data;
   }

   /**
   * GetTemplateList()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetTemplateList($o)
   {
      $qry  = "SELECT st.study_template_id, st.study_template_description, st.study_type_id, stt.study_type_description, ";
      $qry .= "       CONCAT(st.created_by,'.',UPPER(st.study_template_description)) AS study_template_name, ";
      $qry .= "DATE_FORMAT(CONVERT_TZ(st.created_date,'+00:00','".$this->timezone."'),'%Y-%m-%d %h:%i %p')  AS created_date, ";
      $qry .= "CONCAT(ucb.first_name,' ',ucb.last_name) AS created_by ";
      $qry .= "FROM study_template AS st ";
      $qry .= "LEFT OUTER JOIN study_type AS stt ON stt.study_type_id = st.study_type_id ";
      $qry .= "LEFT OUTER JOIN user AS ucb ON ucb.login = st.created_by ";
      $qry .= "WHERE stt.status = 'A' ".$o['ex_where']." ";
      $qry .= $o['order_tag1'];

      $rs = $this->executeQuery($qry);

      while($r = mysql_fetch_assoc($rs)) {
         $data[] = $r;
      }
      return $data;
   }

   /**
   * GetTemplateCount()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Apr 26 16:37:20 PDT 2006
   */
   function GetTemplateCount($created_by=array())
   {
      $q =
      "SELECT
         COUNT(st.study_template_id) AS count
      FROM study_template AS st
      LEFT OUTER JOIN study_type AS stt ON stt.study_type_id = st.study_type_id
      LEFT OUTER JOIN user AS ucb ON ucb.login = st.created_by
      WHERE st.status='A' AND stt.status = 'A'";
      if (sizeof($created_by)>0)
         $q .=" AND st.created_by IN (".implode(", ", $created_by).")";

      return mysql_result($this->executeQuery($q), 0, "count");
   }

   /**
   * GetTemplates()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Apr 26 16:41:31 PDT 2006
   */
   function GetTemplates($created_by=array(), $order="ORDER BY study_template_description", $limit="")
   {
      $q =
      "SELECT
         st.study_template_id,
         st.study_template_description,
         st.study_type_id,
         sta.study_value AS country_code,
         stt.study_type_description,
         CONCAT(st.created_by,'.',UPPER(st.study_template_description)) AS study_template_name,
         DATE_FORMAT(CONVERT_TZ(st.created_date,'+00:00','".$this->timezone."'),'%Y-%m-%d %h:%i %p') AS created_date,
         CONCAT(ucb.first_name,' ',ucb.last_name) AS created_by
      FROM study_template AS st
      LEFT JOIN study_template_attr AS sta ON sta.study_template_id=st.study_template_id AND sta.study_attr='COUNTRY_CODE'
      LEFT OUTER JOIN study_type AS stt ON stt.study_type_id = st.study_type_id
      LEFT OUTER JOIN user AS ucb ON ucb.login = st.created_by
      WHERE st.status='A' AND stt.status = 'A' ";
      if (sizeof($created_by)>0)
         $q .="AND st.created_by IN (".implode(", ", $created_by).") ";
      $q .= $order." ".$limit;

      return $this->executeQuery($q);
   }

   /**
   * getClosedList()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getClosedList($o)
   {
      $qry  = "SELECT s.study_id, sa_company_name.study_value AS company_name, s.study_name, t.study_type_description, ";
      $qry .= "CONCAT(u.first_name,' ',u.last_name) AS acct_mgr, ";
      $qry .= "DATE_FORMAT(CONVERT_TZ(s.current_estimated_complete_date,'+00:00','".$this->timezone."'),'%Y-%m-%d %h:%i %p')  AS current_estimated_complete_date, a.alert_level_description, ";
      $qry .= "fg.functional_group_description, CONCAT(tow.first_name,' ',tow.last_name) AS task_owner ";
      $qry .= "FROM study AS s ";
      $qry .= "LEFT OUTER JOIN study_attr AS sa_company_name ON sa_company_name.study_id = s.study_id AND sa_company_name.study_attr='ACCOUNT_NAME' ";
      $qry .= "LEFT OUTER JOIN study_type AS t ON t.study_type_id = s.study_type_id ";
      $qry .= "LEFT OUTER JOIN study_user AS su ON su.role_id = ".PRIMARY_ACCT_MGR." AND su.study_id = s.study_id ";
      $qry .= "LEFT OUTER JOIN user AS u ON u.login = su.login ";
      $qry .= "LEFT OUTER JOIN functional_group AS fg ON fg.functional_group_id = s.current_task_functional_group_id ";
      $qry .= "LEFT OUTER JOIN user AS tow ON s.current_task_owner_login = tow.login ";
      $qry .= "LEFT OUTER JOIN alert_level AS a ON s.alert_level_id = a.alert_level_id ";
      $qry .= "WHERE s.status = 'A' AND s.study_status_id IN (".STATUS_CLOSED.") ";


      $rs = $this->executeQuery($qry);

      while($r = mysql_fetch_assoc($rs)) {
         $data[] = $r;
      }
      return $data;
   }


   /**
   * setEstFinDate()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function setEstFinDate($timestamp)
   {
      $qry = "UPDATE study SET current_estimated_complete_date = CONVERT_TZ('".$timestamp."','".$this->timezone."','+0:00') WHERE study_id = ".$this->_study;
      return $this->executeQuery($qry);
   }

   /**
   * ResetEstFinDate()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function ResetEstFinDate()
   {
      $qry = "UPDATE study SET current_estimated_complete_date = '' WHERE study_id = ".$this->_study;
      return $this->executeQuery($qry);
   }


   /**
   * setTaskOwner() --> setCurrentTaskOwner()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function setCurrentTaskOwner($login)
   {
      $qry = "UPDATE study SET current_task_owner_login = ".$login." WHERE study_id = ".$this->_study;
      return $this->executeQuery($qry);
   }

   /**
   * setFunctionalGroup()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function setFunctionalGroup($group_id)
   {
      $qry = "UPDATE study SET current_task_functional_group_id = ".$group_id." WHERE study_id = ".$this->_study;
      return $this->executeQuery($qry);
   }

   /**
   * setRevision()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function setRevision($revision)
   {
      $qry = "UPDATE study SET current_revision_number = ".$revision." WHERE study_id = ".$this->_study;
      return $this->executeQuery($qry);
   }

   /**
   * getDataSources()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getDataSources()
   {
      $qry = "SELECT study_datasource_id, study_datasource_description FROM study_datasource WHERE status = 'A'";
      $rs = $this->executeQuery($qry);
      while ($r = mysql_fetch_assoc($rs)) {
         $data[$r['study_datasource_id']] = $r['study_datasource_description'];
      }
      mysql_free_result($rs);

      return $data;
   }

   /**
   * GetTemplateDatasources()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed May 10 13:54:58 PDT 2006
   */
   function GetTemplateDatasources($template_id)
   {
      $q = "SELECT sd.study_datasource_id, sd.study_datasource_description FROM study_template_datasource AS std LEFT JOIN study_datasource AS sd ON sd.study_datasource_id=std.study_datasource_id WHERE std.study_template_id='$template_id' AND std.status='A'";
      return $this->executeQuery($q);
   }

   /**
   * setTaskDuration()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function setTaskDuration($o)
   {
      $qry  = "UPDATE study_timeline SET task_duration = ".$o['task_duration']." ";
      $qry .= "WHERE study_timeline_id = ".$o['study_timeline_id'];
      $rs = $this->executeQuery($qry);
      return $this->rows;
   }

   /**
   * setTaskFunctionalGroup()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function setTaskFunctionalGroup($o)
   {
      $qry  = "UPDATE study_timeline SET functional_group_id = ".$o['functional_group_id']." ";
      $qry .= "WHERE study_timeline_id = ".$o['study_timeline_id'];
      $rs = $this->executeQuery($qry);
      return $this->rows;
   }

   /**
   * getCurrentRevision()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getCurrentRevision()
   {
     $qry = "SELECT current_revision_number FROM study WHERE study_id = ".$this->_study;
     $rs = $this->executeQuery($qry);
     return mysql_result($rs,0,'current_revision_number');
   }

   /**
   * setCurrentRevision()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function setCurrentRevision($revision)
   {
     $qry = "UPDATE study SET current_revision_number = ".$revision." WHERE study_id = ".$this->_study;
     return $this->executeQuery($qry);
   }




   function GetTheStudyNoteBefore($study_comment_id)
   {
      $study_comment_id = sprintf('%d', $study_comment_id);

      $qry  = "SELECT sc_before.study_comment_id, "
            . "  sc_before.study_id, "
            . "  sc_before.comment_date, "
            . "  sc_before.alert_level_id, "
            . "  al.alert_level_description, "
            . "  sc_before.notes "
            . "FROM study_comment AS sc_before "
            . "  JOIN study_comment AS sc ON sc.study_comment_id={$study_comment_id} AND sc.comment_type_id=sc_before.comment_type_id AND sc_before.study_id=sc.study_id "
            . "  LEFT OUTER JOIN alert_level AS al ON al.alert_level_id=sc_before.alert_level_id "
            . "WHERE sc_before.study_comment_id<{$study_comment_id} "
            . "ORDER BY sc_before.study_comment_id DESC "
            . "LIMIT 1 ";
      $rs = $this->executeQuery($qry);
      return mysql_fetch_assoc($this->executeQuery($qry));
   }

   /**
   * GetUnreportedAlerts()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetUnreportedAlerts($comment_type_id)
   {
      $qry  = "SELECT sc.study_comment_id, ";
      $qry .= "       sc.comment_date, ";
      $qry .= "       sc.department_id, d.department, ";
      $qry .= "       sc.notes,  ";
      $qry .= "       sc.study_id, ";
      $qry .= "       sc.alert_level_id, ";
      $qry .= "       al.alert_level_description, ";
      $qry .= "       s.study_name, ";
      $qry .= "       s.partner_id, ";
      $qry .= "       s.study_type_id, ";
      $qry .= "       su_acct_mgr.login AS acct_mgr_login,  ";
      $qry .= "       su_acct_exec.login AS acct_exec_login, ";
      $qry .= "       sc.created_by AS comment_by_login, ";
      $qry .= "       sa_company_name.study_value AS company_name, ";
      $qry .= "       CONCAT(u_acct_mgr.first_name,' ',u_acct_mgr.last_name) AS acct_mgr, ";
      $qry .= "       CONCAT(u_acct_exec.first_name,' ',u_acct_exec.last_name) AS acct_exec, ";
      $qry .= "       CONCAT(u_created_by.first_name,' ',u_created_by.last_name) AS comment_by ";
      $qry .= "FROM study_comment AS sc ";
      $qry .= "   LEFT OUTER JOIN study AS s ON s.study_id = sc.study_id ";
      $qry .= "   LEFT OUTER JOIN study_attr AS sa_company_name ON sa_company_name.study_id = s.study_id AND sa_company_name.study_attr='ACCOUNT_NAME' ";
      $qry .= "   LEFT OUTER JOIN study_attr AS pa ON pa.study_id = sc.study_id AND pa.study_attr = 'PALERT' ";
      $qry .= "   LEFT OUTER JOIN alert_level AS al ON al.alert_level_id = sc.alert_level_id ";
      $qry .= "   LEFT OUTER JOIN study_user AS su_acct_mgr ON su_acct_mgr.role_id = ".PRIMARY_ACCT_MGR." AND su_acct_mgr.study_id = sc.study_id ";
      $qry .= "   LEFT OUTER JOIN study_user AS su_acct_exec ON su_acct_exec.role_id = ".PRIMARY_ACCT_EXEC." AND su_acct_exec.study_id = sc.study_id ";
      $qry .= "   LEFT OUTER JOIN user AS u_acct_mgr ON u_acct_mgr.login = su_acct_mgr.login ";
      $qry .= "   LEFT OUTER JOIN user AS u_acct_exec ON u_acct_exec.login = su_acct_exec.login ";
      $qry .= "   LEFT OUTER JOIN user AS u_created_by ON u_created_by.login = sc.created_by ";
      $qry .= "   LEFT OUTER JOIN department AS d ON d.department_id = sc.department_id ";
      $qry .= "WHERE sc.status = 'A' AND sc.comment_reported = 0 AND sc.comment_type_id = ".$comment_type_id." AND sc.study_id > 0 ";
      $qry .= "GROUP BY sc.study_comment_id ";
      $qry .= "ORDER BY sc.comment_date ASC ";

      return $this->executeQuery($qry);
   }

      /**
   * SetCommentSentDate()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function SetCommentSentDate($study_comment_id)
   {
      $qry  = "UPDATE study_comment SET comment_reported = 1, comment_reported_date = NOW()";
      $qry .= "WHERE study_comment_id = ".$study_comment_id;
      return $this->executeQuery($qry);
   }

   /**
   * deleteStudy()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function deleteStudy()
	{
		$qry = "DELETE FROM study WHERE study_id = ".$this->_study;
		return $this->executeQuery($qry);
	}

	  /**
   * DeleteStudyAttr()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function DeleteStudyAttr()
	{
	   $qry = "DELETE FROM study_attr WHERE study_id = ".$this->_study;
	   $this->executeQuery($qry);

	   $qry = "DELETE FROM study_attr_memo WHERE study_id = ".$this->_study;
	   return $this->executeQuery($qry);
	}


	/**
   * DeleteStudyDataSource()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function DeleteStudyDataSource()
	{
	   $qry = "DELETE FROM study_study_datasource WHERE study_id = ".$this->_study;
	   return $this->executeQuery($qry);
	}

	  /**
   * DeleteStudyTimeLine()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function DeleteStudyTimeLine()
	{
	   $qry  = "DELETE st, sta ";
	   $qry .= "FROM study_timeline AS st ";
	   $qry .= "LEFT OUTER JOIN study_timeline_attr AS sta ON st.study_timeline_id = sta.study_timeline_id ";
	   $qry .= "WHERE study_id = ".$this->_study;
	   return $this->executeQuery($qry);
	}

	  /**
   * DeleteComments()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function DeleteComments()
	{
	   $qry = "DELETE FROM study_comment WHERE study_id = ".$this->_study;
	   return $this->executeQuery($qry);
	}

    /**
   * setLastAlertLevel()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function setLastAlertLevel($alert_level_id)
	{
      $qry  = "REPLACE INTO study_attr (study_id,study_attr,study_value,modified_date) ";
      $qry .= "VALUES (".$this->_study.",'PALERT','".$alert_level_id."',NOW()) ";
      return $this->executeQuery($qry);
   }

   /**
   * SetSpecLock()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function SetSpecLock($o)
	{
      $qry  = "REPLACE INTO study_attr (study_id,study_attr,study_value,modified_date,modified_by) ";
      $qry .= "VALUES (".$this->_study.",'SPECLOCK','1',NOW(),".$o['created_by'].") ";
      return $this->executeQuery($qry);
   }

    /**
   * isSpecLocked()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function isSpecLocked()
	{
      $qry  = "SELECT study_value FROM study_attr WHERE study_attr = 'SPECLOCK' AND study_id = '". $this->_study ."'";
	   $r = mysql_fetch_assoc($this->executeQuery($qry));
	   return ($r&&$r["study_value"]=="on")?true:false;
   }

   /**
   * isTimeLineSaved()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function isTimeLineSaved()
	{
      $qry  = "SELECT timeline_created_date FROM study WHERE study_id = ".$this->_study." AND timeline_created_date != '0000-00-00 00:00:00'";
	   $r = mysql_fetch_assoc($this->executeQuery($qry));
	   return $this->rows;
   }

   /**
   * isTimelineCreated()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jun 22 21:52:09 PDT 2005
   */
   function isTimelineCreated()
   {
      $qry  = "SELECT timeline_created_date FROM study WHERE study_id = ".$this->_study." AND timeline_created_date != '0000-00-00 00:00:00'";
      return (mysql_fetch_assoc($this->executeQuery($qry))) ? true : false;
   }

   /**
   * isInvoiced()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Jun 23 08:27:05 PDT 2005
   */
   function isInvoiced()
   {
      $study_id = sprintf("%d", $this->_study);
      $qry = "SELECT study_invoice_date FROM study WHERE study_id = $study_id AND study_invoice_date != '0000-00-00 00:00:00'";
      return mysql_fetch_assoc($this->executeQuery($qry)) ? true : false;
   }


   function isUserAssignedToStudy($study_id, $logins)
   {
      if (!is_array($logins)){
         $logins = array($logins);
      }
      $login_list = "";
      foreach ($logins as $login){
         $login_list .= sprintf('%d,', $login);
      }

      $study_id = sprintf('%d', $study_id);

      $login_list = preg_replace('/,$/', '', $login_list);  // remove the extra comma at the end of list
      $qry  = "SELECT login FROM study_user WHERE study_id=$study_id AND login IN ($login_list) ";
      $this->executeQuery($qry);
      return $this->rows ? true : false;
   }



   function isUserAssignedToTaskInStudy($study_id, $logins)
   {
      if (!is_array($logins)){
         $logins = array($logins);
      }
      $login_list = "";
      foreach ($logins as $login){
         $login_list .= sprintf('%d,', $login);
      }

      $study_id = sprintf('%d', $study_id);

      $login_list = preg_replace('/,$/', '', $login_list);  // remove the extra comma at the end of list
      $qry  = "SELECT stu.login "
            . "FROM study_timeline_user AS stu "
            . "JOIN study_timeline AS st ON st.study_timeline_id=stu.study_timeline_id AND st.study_id=$study_id "
            . "WHERE stu.login IN ($login_list) ";

      $this->executeQuery($qry);
      return $this->rows ? true : false;
   }




   function SetInvoiceDate($study_id, $study_invoice_date)
   {
      $study_id = sprintf("%d", $study_id);
      $study_invoice_date = mysql_real_escape_string($study_invoice_date);
      $qry  = "UPDATE study "
            . "SET study_invoice_date='$study_invoice_date', modified_by={$this->created_by}, modified_date=NOW() "
            . "WHERE study_id = $study_id ";
      return $this->executeQuery($qry);
   }

    /**
   * GetFlashList()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getFlashList()
   {
      $qry  = "SELECT s.study_id, "
            . "  sa_company_name.study_value AS company_name, "
            . "  s.study_name, "
            . "  s.partner_id, "
            . "  s.alert_level_id, "
            . "  a.alert_level_description, "
            . "  DATE_FORMAT(s.start_date,'%Y-%m-%d') AS start_date, "
            . "  ROUND(sa_nc.study_value / sa_n.study_value * 100) AS percent_quota_progress, "
            . "  CONCAT(u_acct_mgr.first_name,' ',u_acct_mgr.last_name) AS acct_mgr, "
            . "  CONCAT(u_acct_exec.first_name,' ',u_acct_exec.last_name) AS acct_exec "
            . "FROM study AS s "
            . "  LEFT OUTER JOIN study_attr AS sa_company_name ON sa_company_name.study_id = s.study_id AND sa_company_name.study_attr='ACCOUNT_NAME' "
            . "  LEFT OUTER JOIN study_user AS su_acct_mgr ON su_acct_mgr.study_id=s.study_id AND su_acct_mgr.role_id=1 "
            . "  LEFT OUTER JOIN study_user AS su_acct_exec ON su_acct_exec.study_id=s.study_id AND su_acct_exec.role_id=4 "
            . "  LEFT OUTER JOIN user AS u_acct_mgr ON u_acct_mgr.login = su_acct_mgr.login "
            . "  LEFT OUTER JOIN user AS u_acct_exec ON u_acct_exec.login = su_acct_exec.login "
            . "  LEFT OUTER JOIN alert_level AS a ON a.alert_level_id = s.alert_level_id "
            . "  LEFT OUTER JOIN study_attr AS sa_n ON sa_n.study_id = s.study_id AND sa_n.study_attr = 'N_COMPLETE' "
            . "  LEFT OUTER JOIN study_attr AS sa_nc ON sa_nc.study_id = s.study_id AND sa_nc.study_attr = 'NUMBER_COMPLETED' "
            . "WHERE s.study_status_id != 2 AND s.status = 'A' "
            . "GROUP BY s.study_id "
            . "ORDER BY s.alert_level_id DESC, s.study_name ASC ";
      return $this->executeQuery($qry);
   }



    /**
   * GetFlashListV2()
   *
   * @param string $extraFilters
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetFlashListV2($extraFilters)
   {
      $qry  = "SELECT "
            . "  s.study_id,"
            . "  sa_company_name.study_value AS company_name, "
            . "  s.study_name, "
            . "  a.alert_level_description, "
            . "  DATE_FORMAT(s.start_date,'%Y-%m-%d') AS start_date, "
            . "  ROUND(sa_nc.study_value / sa_n.study_value * 100) AS percent_quota_progress, "
            . "  CONCAT(u_acct_mgr.first_name,' ',u_acct_mgr.last_name) AS acct_mgr, "
            . "  s.alert_level_id, "
            . "  s.partner_id  "
            . "FROM study AS s "
            . "  LEFT OUTER JOIN alert_level AS a ON a.alert_level_id = s.alert_level_id "
            . "  LEFT OUTER JOIN study_attr AS sa_n ON sa_n.study_id = s.study_id AND sa_n.study_attr = 'N_COMPLETE' "
            . "  LEFT OUTER JOIN study_attr AS sa_nc ON sa_nc.study_id = s.study_id AND sa_nc.study_attr = 'NUMBER_COMPLETED' "
            . "  LEFT OUTER JOIN study_attr AS sa_company_name ON sa_company_name.study_id = s.study_id AND sa_company_name.study_attr='ACCOUNT_NAME' "
            . "  LEFT OUTER JOIN study_user AS su_acct_mgr ON su_acct_mgr.study_id = s.study_id AND su_acct_mgr.role_id = 1 "
            . "  LEFT OUTER JOIN user AS u_acct_mgr ON u_acct_mgr.login = su_acct_mgr.login "
            . "WHERE s.study_status_id != 2 AND s.status = 'A' $extraFilters "
            . "ORDER BY s.alert_level_id DESC, s.start_date";
      return $this->executeQuery($qry);
   }



//    /**
//   * getFlashSummary()
//   *
//   * @param
//   * @param
//   * @return
//   * @throws
//   * @access
//   * @global
//   */
//   function getFlashSummary()
//   {
//      $qry ="SELECT sum(if(prj.alert_level_id = 1,1,0)) AS p_green,
//              sum(if(prj.alert_level_id = 2,1,0)) AS p_yellow,
//              sum(if(prj.alert_level_id = 3,1,0)) AS p_orange,
//              sum(if(prj.alert_level_id = 4,1,0)) AS p_red
//            FROM study AS prj
//            WHERE prj.study_status_id != 2 AND prj.status = 'A'";
//
//      return $this->executeQuery($qry);
//   }

   /**
   * getFlashNotes()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function getFlashNotes($study_id)
   {
      $study_id = sprintf('%d', $study_id);
      $qry  = "SELECT sc.notes, d.department "
            . "FROM study_comment AS sc "
            . "LEFT OUTER JOIN department AS d ON d.department_id=sc.department_id "
            . "WHERE study_id = $study_id AND comment_type_id=1 "
            . "ORDER BY sc.created_date DESC "
            . "LIMIT 1";
      return $this->executeQuery($qry);
   }


   /**
   * GetNotesByStudyId()
   *
   * @param integer $study_id - ID of the study which notes to be retrieved
   * @param string $order_by - ORDER BY clause; how to sort the notes (i.e. "created_date DESC")
   * @param string $limit - LIMIT clause; number of notes to be retrieved (i.e. "1")
   * @return array of string
   * @throws
   * @access
   * @global
   * @since TChan 2006-02-03
   */
   function GetNotesByStudyId($study_id, $order_by="created_date DESC", $limit="")
   {
      $qry = "SELECT notes FROM study_comment WHERE study_id = $study_id ";
      if ($order_by) { $qry .= "ORDER BY $order_by "; }
      if ($limit)    { $qry .= "LIMIT $limit "; }

      $rs = $this->executeQuery($qry);
      $notes = array();
      while ($r = mysql_fetch_assoc($rs)) {
         $notes[] = $r['notes'];
      }
      return $notes;
   }


	/**
   * getGraphSummary()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function getGraphSummary($o)
	{
		$qry  = "SELECT DATE_FORMAT(comment_date,'%Y-%m-%d') AS comment_date_f, ";
		$qry .= "       SUM(IF(alert_level_id = 1,1,0)) AS p_green, ";
		$qry .= "       SUM(IF(alert_level_id = 2,1,0)) AS p_yellow, ";
		$qry .= "       SUM(IF(alert_level_id = 3,1,0)) AS p_orange, ";
		$qry .= "       SUM(IF(alert_level_id = 4,1,0)) AS p_red ";
		$qry .= "FROM study_comment ";
		$qry .= "WHERE comment_date BETWEEN '".$o['start_date']."' AND '".$o['end_date']."' ";
		$qry .= "GROUP BY comment_date_f ";
		return $this->executeQuery($qry);
	}



	/**
   * GetStudyCosts()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function GetStudyCosts($study_id)
	{
	   $study_id = sprintf("%d", $study_id);

	   $qry  =
	   "SELECT
	        sc.study_cost_id,
	        sc.study_id,
	        sc.reference_number,
	        sc.account_id,
	        sc.account_name,
	        sc.contact_name,
	        sc.contact_email,
	        sc.study_cost_type_id,
	        sct.study_cost_type_description,
	        sc.actual_rate,
	        sc.actual_quantity,
	        (sc.actual_rate*sc.actual_quantity) AS actual_cost,
	        sc.proposed_rate,
	        sc.proposed_quantity,
	        (sc.proposed_rate*sc.proposed_quantity) AS proposed_cost
        FROM study_cost AS sc
        LEFT OUTER JOIN study_cost_type AS sct ON sct.study_cost_type_id = sc.study_cost_type_id
        WHERE sc.status = 'A' AND sc.study_id = $study_id ";

      $rs = $this->executeQuery($qry);
      $costs = array();
      while ($r = mysql_fetch_assoc($rs)) {
         $study_cost_id = $r['study_cost_id'];
         $costs[$study_cost_id] = $r;
      }
      return $costs;
	}

	/**
	* GetStudyCostFiles()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue May 09 15:07:42 PDT 2006
	*/
	function GetStudyCostFiles($study_id, $study_cost_id)
	{
	   $q = "SELECT study_cost_file_id, study_cost_file_title FROM study_cost_file WHERE study_id='$study_id' AND study_cost_id='$study_cost_id' AND status='A'";
	   return $this->executeQuery($q);
	}

	/**
   * GetStudyCostList()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function GetStudyCostList()
	{
	   $qry  = "SELECT study_cost_type_id, study_cost_type_description, default_unit_cost, account_subtype_id "
	         . "FROM study_cost_type "
	         . "WHERE status='A' ";

      $rs = $this->executeQuery($qry);
      $list = array();
      while ($r = mysql_fetch_assoc($rs)) {
         $study_cost_type_id = $r['study_cost_type_id'];
         $list[$study_cost_type_id] = $r;
      }
      return $list;
	}



	/**
   * GetStudyUsers()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function GetStudyUsers()
	{
	   $study_id = sprintf('%d', $this->_study);

		$qry  = "SELECT su.study_user_id, su.login, CONCAT(u.first_name,' ',u.last_name) AS name, r.role_description, su.role_id, su.tcm_recipient ";
		$qry .= "FROM study_user AS su ";
		$qry .= "LEFT OUTER JOIN user AS u ON u.login = su.login ";
		$qry .= "LEFT OUTER JOIN role AS r ON r.role_id = su.role_id ";
		$qry .= "WHERE su.status = 'A' AND su.study_id = $study_id ";
		$qry .= "ORDER BY r.role_description ASC, name ASC ";
		return $this->executeQuery($qry);
	}

	/**
	* GetStudyAccounts()
	*
	* @param
	* @todo NOT YET COMPLETED
	* @return
	* @since  - Wed Aug 23 14:33:07 PDT 2006
	*/
	function GetStudyAccounts($account_type_id, $account_sub_type_id=0)
	{
	   $qry = "SELECT sa.study_account_id, sa.account_id, sa.account_name, sa.account_type_id, at.account_type_description, sa.account_sub_type_id, ast.account_sub_type_description ";
	   $qry .= "FROM study_account AS sa ";
	   $qry .= "LEFT JOIN account_type AS at ON at.account_type_id = sa.account_type_id ";
	   $qry .= "LEFT JOIN account_sub_type AS ast ON ast.account_sub_type_id = sa.account_sub_type_id ";
	   $qry .= "WHERE sa.status = 'A' AND sa.study_id = '".$this->_study."' AND sa.account_type_id = '".$account_type_id."'";
	 
	   if ($account_sub_type_id) {
	      $qry .= " AND sa.account_sub_type_id = '".$account_sub_type_id."'";
	   }

	   return $this->executeQuery($qry);
	}

	/**
	* GetStudyContacts()
	*
	* @param
	* @todo NOT YET COMPLETED
	* @return
	* @since  - Wed Aug 23 16:28:52 PDT 2006
	*/
	function GetStudyContacts($study_account_id)
	{
		$qry  = "SELECT sc.study_contact_id, sc.contact_id, sc.salutation, sc.first_name, sc.middle_initial, sc.last_name, ";
		$qry .= "       sc.phone, sc.fax, sc.email, sc.tcm_recipient, s.status_description, sc.status, sc.study_contact_type_id, sct.study_contact_type_description, ";
		$qry .= "       sa.study_id, sa.account_id, sa.account_name ";
		$qry .= "FROM study_contact AS sc ";
		$qry .= "LEFT JOIN study_account AS sa ON sa.study_account_id = sc.study_account_id ";
		$qry .= "LEFT JOIN study_contact_type AS sct ON sct.study_contact_type_id = sc.study_contact_type_id ";
		$qry .= "LEFT OUTER JOIN status AS s ON sc.status = s.status_code ";
		$qry .= "WHERE sc.study_account_id = $study_account_id";
		return $this->executeQuery($qry);
	}

	/**
   * GetPastDueTasks()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function GetPastDueTasks()
	{
	    $qry  = "SELECT m.study_timeline_id, m.study_task_id, t.study_task_description, g.functional_group_description, m.login,
               oest.study_timeline_value AS orgest_date,
               cest.study_timeline_value AS curest_date,
               comp.study_timeline_value AS comp_date,
               alrt_sent.study_timeline_value AS alert_sent_time,
               m.task_duration, m.sort_order, m.functional_group_id,
               t.primary_task, t.task_complete_requires_memo, s.study_id, s.partner_id,
               CONCAT(u.first_name,' ',u.last_name) AS task_owner,
               IF(cest.study_timeline_value < NOW(),1,0) AS timepassed,
               TIMEDIFF(cest.study_timeline_value, NOW()) AS time_diff, m.task_duration,
					IF(sh.parent_study_type_task_id IS NULL,0,1) AS is_child ";
      $qry .= "FROM study_timeline AS m ";
      $qry .= "LEFT OUTER JOIN study_task AS t ON t.study_task_id = m.study_task_id ";
      $qry .= "LEFT OUTER JOIN functional_group AS g ON g.functional_group_id = m.functional_group_id ";
      $qry .= "LEFT OUTER JOIN user AS u ON u.login = m.login ";
      $qry .= "LEFT OUTER JOIN study_timeline_attr AS oest ON oest.study_timeline_attr = 'ORIGEST' AND oest.study_timeline_id = m.study_timeline_id ";
      $qry .= "LEFT OUTER JOIN study_timeline_attr AS cest ON cest.study_timeline_attr = 'CUREST' AND cest.study_timeline_id = m.study_timeline_id ";
      $qry .= "LEFT OUTER JOIN study_timeline_attr AS comp ON comp.study_timeline_attr = 'COMPDATE' AND comp.study_timeline_id = m.study_timeline_id ";
      $qry .= "LEFT OUTER JOIN study_timeline_attr AS alrt_sent ON alrt_sent.study_timeline_attr = 'ALERTSENT' AND alrt_sent.study_timeline_id = m.study_timeline_id ";
      $qry .= "LEFT OUTER JOIN study AS s ON s.study_id = m.study_id ";
      $qry .= "LEFT OUTER JOIN study_type_task_hiearchy AS sh ON sh.child_study_type_task_id = m.study_task_id ";
      $qry .= "WHERE m.status = 'A' AND m.revision_number = s.current_revision_number AND comp.study_timeline_value IS NULL ";
      $qry .= "  AND cest.study_timeline_value IS NOT NULL AND cest.study_timeline_value < NOW() ";
      $qry .= "  AND (HOUR(TIMEDIFF(alrt_sent.study_timeline_value,NOW())) > 24 OR alrt_sent.study_timeline_value IS NULL ) ";
      $qry .= "  AND s.study_status_id = 1 ";
      $qry .= "ORDER BY m.sort_order ";

      //echo $qry;

      return $this->executeQuery($qry);

	}

	/**
   * GetPastDueTaskCountsByOwner()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function GetPastDueTaskCountsByOwner()
	{
	   $qry  = "SELECT COUNT(m.study_timeline_id) AS missed_tasks, m.login, u.first_name, u.last_name, COUNT(DISTINCT s.study_id) AS missed_studies ";
      $qry .= "FROM study_timeline AS m ";
      $qry .= "LEFT OUTER JOIN study_task AS t ON t.study_task_id = m.study_task_id ";
      $qry .= "LEFT OUTER JOIN functional_group AS g ON g.functional_group_id = m.functional_group_id ";
      $qry .= "LEFT OUTER JOIN user AS u ON u.login = m.login ";
      $qry .= "LEFT OUTER JOIN study_timeline_attr AS oest ON oest.study_timeline_attr = 'ORIGEST' AND oest.study_timeline_id = m.study_timeline_id ";
      $qry .= "LEFT OUTER JOIN study_timeline_attr AS cest ON cest.study_timeline_attr = 'CUREST' AND cest.study_timeline_id = m.study_timeline_id ";
      $qry .= "LEFT OUTER JOIN study_timeline_attr AS comp ON comp.study_timeline_attr = 'COMPDATE' AND comp.study_timeline_id = m.study_timeline_id ";
      $qry .= "LEFT OUTER JOIN study_timeline_attr AS alrt_sent ON alrt_sent.study_timeline_attr = 'ALERTSENT' AND alrt_sent.study_timeline_id = m.study_timeline_id ";
      $qry .= "LEFT OUTER JOIN study AS s ON s.study_id = m.study_id ";
      $qry .= "LEFT OUTER JOIN study_type_task_hiearchy AS sh ON sh.child_study_type_task_id = m.study_task_id ";
      $qry .= "WHERE m.status = 'A' AND m.revision_number = s.current_revision_number AND comp.study_timeline_value IS NULL ";
      $qry .= "  AND cest.study_timeline_value IS NOT NULL AND cest.study_timeline_value < NOW() ";
      $qry .= "  AND s.study_status_id = 1 ";
      $qry .= "GROUP BY m.login ";
      $qry .= "ORDER BY first_name ASC, last_name ASC";

      return $this->executeQuery($qry);
	}



	function GetStudiesWithPastDueTasksByOwner($login)
	{
	   $login = sprintf("%d", $login);

	   $qry  = "SELECT DISTINCT s.study_id, s.study_name ";
      $qry .= "FROM study_timeline AS m ";
      $qry .= "  LEFT OUTER JOIN study_task AS t ON t.study_task_id = m.study_task_id ";
      $qry .= "  LEFT OUTER JOIN functional_group AS g ON g.functional_group_id = m.functional_group_id ";
      $qry .= "  LEFT OUTER JOIN user AS u ON u.login = m.login ";
      $qry .= "  LEFT OUTER JOIN study_timeline_attr AS oest ON oest.study_timeline_attr = 'ORIGEST' AND oest.study_timeline_id = m.study_timeline_id ";
      $qry .= "  LEFT OUTER JOIN study_timeline_attr AS cest ON cest.study_timeline_attr = 'CUREST' AND cest.study_timeline_id = m.study_timeline_id ";
      $qry .= "  LEFT OUTER JOIN study_timeline_attr AS comp ON comp.study_timeline_attr = 'COMPDATE' AND comp.study_timeline_id = m.study_timeline_id ";
      $qry .= "  LEFT OUTER JOIN study_timeline_attr AS alrt_sent ON alrt_sent.study_timeline_attr = 'ALERTSENT' AND alrt_sent.study_timeline_id = m.study_timeline_id ";
      $qry .= "  LEFT OUTER JOIN study AS s ON s.study_id = m.study_id ";
      $qry .= "  LEFT OUTER JOIN study_type_task_hiearchy AS sh ON sh.child_study_type_task_id = m.study_task_id ";
      $qry .= "WHERE m.status = 'A' AND m.revision_number = s.current_revision_number AND comp.study_timeline_value IS NULL ";
      $qry .= "  AND cest.study_timeline_value IS NOT NULL AND cest.study_timeline_value < NOW() ";
      $qry .= "  AND s.study_status_id = 1 ";
      $qry .= "  AND m.login = $login ";
      $qry .= "ORDER BY study_name ASC, study_id ASC ";

      $rs = $this->executeQuery($qry);
      $studies = array();
      while ($r = mysql_fetch_assoc($rs)) {
         $study_id = $r['study_id'];
         $studies[$study_id] = $r;
      }
      return $studies;
	}



	/**
   * GetAlertCount()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function GetAlertCount($timeline_id)
	{
	   $qry  = "SELECT ac.study_timeline_value ";
	   $qry .= "FROM study_timeline_attr AS ac ";
	   $qry .= "WHERE ac.study_timeline_attr = 'ALERTCOUNT' AND ac.study_timeline_id = ".$timeline_id;

	   return $this->executeQuery($qry);
	}

	/**
   * SetAlertCount()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function SetAlertCount($count,$timeline_id)
	{
	   $qry  = "REPLACE INTO study_timeline_attr ";
	   $qry .= "(study_timeline_id, study_timeline_attr, study_timeline_value, modified_by, modified_date,status ) ";
	   $qry .= "VALUES (".$timeline_id.",'ALERTCOUNT',".$count.", 10312, NOW(),'A') ";

	   return $this->executeQuery($qry);
	}

	/**
   * SetAlertSent()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
	function SetAlertSent($timeline_id)
	{
	   $qry  = "REPLACE INTO study_timeline_attr ";
	   $qry .= "(study_timeline_id, study_timeline_attr, study_timeline_value, modified_by, modified_date,status ) ";
	   $qry .= "VALUES (".$timeline_id.", 'ALERTSENT', NOW(), 10312, NOW(),'A') ";

	   return $this->executeQuery($qry);
	}



	function DeleteStudyCost($study_id, $study_cost_id)
	{
	   $study_id = sprintf('%d', $study_id);
	   $study_cost_id = sprintf('%d', $study_cost_id);

      $qry = "UPDATE study_cost "
              . "SET status='D', modified_by={$this->created_by}, modified_date=NOW() "
              . "WHERE study_cost_id='$study_cost_id' AND study_id = '$study_id' ";
      return $this->executeQuery($qry);
	}

	/**
	* DeleteStudyCosts()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Jul 05 14:08:37 PDT 2006
	*/
	function DeleteStudyCosts($study_id)
	{
	   $study_id = sprintf("%d", $study_id);
	   $q = "UPDATE study_cost SET status='D', modified_by={$this->created_by}, modified_date=NOW() WHERE study_id='$study_id'";
	   $this->executeQuery($q);
	   return true;
	}

	function DeleteStudyCostFile($study_id, $study_cost_id) {
	   $study_id = sprintf('%d', $study_id);
	   $study_cost_id = sprintf('%d', $study_cost_id);

      $qry = "UPDATE study_cost_file "
              . "SET status='D', modified_by={$this->created_by}, modified_date=NOW() "
              . "WHERE study_cost_id='$study_cost_id' AND study_id = '$study_id' ";
      return $this->executeQuery($qry);

	}

	/**
	* DeleteStudyCostFiles()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Jul 05 14:10:01 PDT 2006
	*/
	function DeleteStudyCostFiles($study_id)
	{
	   $study_id = sprintf("%d", $study_id);
	   $q = "UPDATE study_cost_file SET status='D', modified_by={$this->created_by}, modified_date=NOW() WHERE study_id='$study_id'";
	   $this->executeQuery($q);
	   return true;
	}



	function AddStudyCost($study_cost_type_id, $reference_number, $account_id, $account_name, $contact_name, $contact_email, $proposed_rate, $proposed_quantity, $actual_rate, $actual_quantity)
	{
	   $study_cost_type_id = sprintf('%d', $study_cost_type_id);
	   $reference_number = mysql_real_escape_string($reference_number);
	   $account_id = sprintf('%d', $account_id);
	   $account_name = mysql_real_escape_string($account_name);
	   $contact_name = mysql_real_escape_string($contact_name);
	   $contact_email = mysql_real_escape_string($contact_email);
	   $proposed_rate = sprintf('%0f', $proposed_rate);
	   $proposed_quantity = sprintf('%0f', $proposed_quantity);
	   $actual_rate = sprintf('%0f', $actual_rate);
	   $actual_quantity = sprintf('%0f', $actual_quantity);

	   $qry  = "INSERT INTO study_cost (study_id, study_cost_type_id, reference_number, account_id, account_name, contact_name, contact_email, proposed_rate, proposed_quantity, actual_rate, actual_quantity, created_by, created_date, status) ";
	   $qry .= "VALUES ('".$this->_study."', '$study_cost_type_id', '$reference_number', '$account_id', '$account_name', '$contact_name', '$contact_email', '$proposed_rate', '$proposed_quantity', '$actual_rate', '$actual_quantity', '".$this->created_by."', NOW(), 'A')";
	   return $this->executeQuery($qry);
	}

	function UpdateStudyCost($study_id, $study_cost_id, $study_cost_type_id, $reference_number, $account_id, $account_name, $contact_name, $contact_email, $proposed_rate, $proposed_quantity, $actual_rate, $actual_quantity ) {
	   $study_cost_type_id = sprintf('%d', $study_cost_type_id);
	   $reference_number = mysql_real_escape_string($reference_number);
	   $account_id = sprintf('%d', $account_id);
	   $account_name = mysql_real_escape_string($account_name);
	   $contact_name = mysql_real_escape_string($contact_name);
	   $contact_email = mysql_real_escape_string($contact_email);
	   $proposed_rate = sprintf('%0f', $proposed_rate);
	   $proposed_quantity = sprintf('%0f', $proposed_quantity);
	   $actual_rate = sprintf('%0f', $actual_rate);
	   $actual_quantity = sprintf('%0f', $actual_quantity);
      $q = "UPDATE study_cost SET
         `study_cost_type_id`='$study_cost_type_id',
         `reference_number`='$reference_number',
         `account_id`='$account_id',
         `account_name`='$account_name',
         `contact_name`='$contact_name',
         `contact_email`='$contact_email',
         `proposed_rate`='$proposed_rate',
         `proposed_quantity`='$proposed_quantity',
         `actual_rate`='$actual_rate',
         `actual_quantity`='$actual_quantity',
         `modified_by`='".$this->created_by."',
         `modified_date`=NOW()
       WHERE study_id='$study_id' AND study_cost_id='$study_cost_id'";
      $this->executeQuery($q);
      
      
      return true;
	}

	/**
	* AddStudyCostFile()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue May 09 13:00:33 PDT 2006
	*/
	function AddStudyCostFile($study_cost_id, $name, $title, $data, $size)
	{
	   $name = mysql_escape_string($name);
	   $title = mysql_escape_string($title);
	   $data = addslashes($data);

	   $q = "INSERT INTO study_cost_file (`study_id`, `study_cost_id`, `study_cost_file_name`, `study_cost_file_title`, `study_cost_file_data`, `study_cost_file_size`, `created_by`, `created_date`, `status`) "
	   ." VALUES ('".$this->_study."', '$study_cost_id', '$name', '$title', '$data', '$size', '".$this->created_by."', NOW(), 'A')";
	   $this->executeQuery($q);
	}


	/**
	* AddStudyContact()
	*
	* @param
	* @return - recordset
	* @throws
	* @access
	* @global
	*/
	function AddStudyContact($study_id, $contact_id, $first_name, $middle_initial, $last_name, $phone, $email, $study_contact_type_id=1)
	{
	   $study_id = sprintf('%d', $study_id);
	   $contact_id = sprintf('%d', $contact_id);

	   $first_name = mysql_real_escape_string($first_name);
	   $middle_name = mysql_real_escape_string($middle_initial);
	   $last_name = mysql_real_escape_string($last_name);
	   $phone = mysql_real_escape_string($phone);
	   $email = mysql_real_escape_string($email);
	   $study_contact_type_id = sprintf("%d", $study_contact_type_id);

		$qry  = "INSERT INTO study_contact (study_id, study_contact_type_id, contact_id, first_name, middle_initial, last_name, phone, email, tcm_recipient, created_by, created_date) ";
		$qry .= "VALUES ($study_id,$study_contact_type_id,$contact_id,'$first_name','$middle_name','$last_name','$phone','$email', '1', {$this->created_by},NOW())";
		return $this->executeQuery($qry);
	}

	/**
	* DeleteStudyContacts()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Jul 05 14:31:10 PDT 2006
	*/
	function DeleteStudyContacts($study_id)
	{
	   $study_id = sprintf("%d", $study_id);
	   $q = "UPDATE study_contact SET status='D', modified_by='{$this->created_by}', modified_date=NOW() WHERE study_id='$study_id' AND study_account_id IS NULL";
	   $this->executeQuery($q);
	   return true;
	}

	/**
	* GetContacts()
	*
	* get study contacts
	* @param
	* @param -
	* @return - recordset
	* @throws
	* @access
	* @global
	* @since  - Wed Jun 08 23:35:55 PDT 2005
	*/
	function GetContacts()
	{
		$qry  = "SELECT st.study_contact_id, st.contact_id, st.salutation, st.first_name, st.middle_initial, st.last_name, ";
		$qry .= "       st.phone, st.fax, st.email, st.tcm_recipient, s.status_description, st.status, st.study_contact_type_id, sct.study_contact_type_description,  ";
		$qry .= "DATE_FORMAT(CONVERT_TZ(st.schedule_sent_date,'+00:00','".$this->timezone."'),'%Y-%m-%d %H:%i:%S') AS schedule_sent_date ";
		$qry .= "FROM study_contact AS st ";
		$qry .= "LEFT JOIN study_contact_type AS sct ON sct.study_contact_type_id = st.study_contact_type_id ";
		$qry .= "LEFT OUTER JOIN status AS s ON st.status = s.status_code ";
		$qry .= "WHERE st.study_id = ".$this->_study." AND st.study_account_id IS NULL";
		return $this->executeQuery($qry);
	}

	/**
	* SetContactStatus()
	*
	* @param
	* @param - study_contact_id, status
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Jun 09 09:45:51 PDT 2005
	*/
	function SetContactStatus($study_contact_id,$status)
	{
		$qry = "UPDATE study_contact SET status = '".$status."' WHERE study_contact_id = ".$study_contact_id;
		return $this->executeQuery($qry);
	}

	/**
	* SetContactTCMRecipient()
	*
	* @param
	* @todo NOT YET COMPLETED
	* @return
	* @since  - Wed Aug 23 16:09:31 PDT 2006
	*/
	function SetContactTCMRecipient($study_contact_id, $tcm_recipient)
	{
	   $qry = "UPDATE study_contact SET tcm_recipient = '$tcm_recipient' WHERE study_contact_id='$study_contact_id'";
	   return $this->executeQuery($qry);
	}

	/**
	* SetStudyLog()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jun 17 09:16:23 PDT 2005
	*/
	function SetStudyLog($event_id, $login, $remote_address, $user_agent)
	{
	   $event_id = sprintf("%d", $event_id);
	   $login = sprintf("%d", $login);
	   $remote_address = mysql_real_escape_string($remote_address);
	   $user_agent = mysql_real_escape_string($user_agent);

	   $qry = "INSERT INTO study_event_log (study_id, study_event_id, login, created_by, remote_address, user_agent, created_date, status) "
	        . "VALUES (".$this->_study.",".$event_id.",".$login.",".$login.",'".$remote_address."','".$user_agent."',NOW(), 'A') ";

	   return $this->executeQuery($qry);
	}

	/**
	* GetStudyLog()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jun 17 09:46:03 PDT 2005
	*/
	function GetStudyLog()
	{
	   $qry = "SELECT se.study_event_id, se.study_event_description, sel.login, u.first_name, u.last_name, sel.remote_address, sel.user_agent, "
	        . "DATE_FORMAT(CONVERT_TZ(sel.created_date,'+00:00','".$this->timezone."'),'%Y-%m-%d %h:%i %p')  AS created_date "
	        . "FROM study_event_log AS sel "
	        . "LEFT OUTER JOIN study_event AS se ON se.study_event_id = sel.study_event_id "
	        . "LEFT OUTER JOIN user AS u ON u.login = sel.login "
	        . "WHERE sel.study_id = ".$this->_study
	        . " ORDER BY created_date DESC ";


	   return $this->executeQuery($qry);
	}

	/**
	* SetStudyTimelineLog()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jun 17 13:16:22 PDT 2005
	*/
	function SetTimelineLog($timeline_id, $created_by)
	{
	   $qry = "INSERT INTO study_timeline_log (study_timeline_id, created_by, created_date) "
	        . "VALUES (".$timeline_id.",".$created_by.",NOW()) ";
	   return $this->executeQuery($qry);
	}

	/**
	* SetTimelineLogLogin()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jun 17 13:18:03 PDT 2005
	*/
	function SetTimelineLogLogin($timeline_log_id, $login, $created_by)
	{
	   // Note:  User may select Functional Group and does not select Task Owner
	   $login = sprintf('%d', $login);

	   $qry = "UPDATE study_timeline_log SET login={$login}, modified_by={$created_by}, modified_date=NOW() "
	        . "WHERE study_timeline_log_id = ".$timeline_log_id;
	   return $this->executeQuery($qry);
	}

	/**
	* SetTimelineLogDueDate()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jun 17 13:20:13 PDT 2005
	*/
	function SetTimelineLogDueDate($timeline_log_id, $task_due_date, $created_by)
	{
	   $qry = "UPDATE study_timeline_log SET task_due_date = ".$task_due_date.", modified_by = ".$created_by.", modified_date = NOW() "
	        . "WHERE study_timeline_log_id = ".$timeline_log_id;
	   return $this->executeQuery($qry);
	}

	/**
	* GetTimelineLog()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jun 17 13:55:09 PDT 2005
	*/
	function GetTimelineLog()
	{
	   $qry = "SELECT stl.study_timeline_log_id, stl.login, stl.created_by, stsk.study_task_description,  "
	        . "CONCAT(tow.first_name, ' ', tow.last_name) AS task_owner_name,  "
	        . "CONCAT(cb.first_name, ' ', cb.last_name) AS created_by_name,  "
	        . "DATE_FORMAT(CONVERT_TZ(stl.created_date,'+00:00','".$this->timezone."'),'%Y-%m-%d %h:%i %p')  AS created_date, "
	        . "DATE_FORMAT(CONVERT_TZ(stl.task_due_date,'+00:00','".$this->timezone."'),'%Y-%m-%d %h:%i %p')  AS task_due_date "
	        . "FROM study_timeline_log AS stl "
	        . "LEFT OUTER JOIN study_timeline AS st ON st.study_timeline_id = stl.study_timeline_id "
	        . "LEFT OUTER JOIN study_task AS stsk ON stsk.study_task_id = st.study_task_id "
	        . "LEFT OUTER JOIN user AS tow ON tow.login = stl.login "
	        . "LEFT OUTER JOIN user AS cb ON cb.login = stl.created_by "
	        . "WHERE st.study_id = ".$this->_study;

	  return $this->executeQuery($qry);
	}

	/**
	* GetStudyQuotedValue()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Sun Jun 19 14:39:58 PDT 2005
	*/
	function GetStudyQuotedValue()
	{
	   $qry = "SELECT study_value AS quoted_value "
	        . "FROM study_attr "
	        . "WHERE study_id = ".$this->_study." AND study_attr = 'QVALUE'";

	   $r = mysql_fetch_assoc($this->executeQuery($qry));

   	return ($r) ? $r['quoted_value'] : "0.00";
	}

	/**
	* GetStudyEstimatedValue()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Sun Jun 19 14:42:39 PDT 2005
	*/
	function GetStudyEstimatedValue()
	{
	   $qry = "SELECT study_value AS estimated_value "
	        . "FROM study_attr "
	        . "WHERE study_id = ".$this->_study." AND study_attr = 'PVALUE'";

	   $r = mysql_fetch_assoc($this->executeQuery($qry));

   	return ($r) ? $r['estimated_value'] : "0.00";
	}

	/**
	* hasClientContact()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Jun 22 22:05:16 PDT 2005
	*/
	function hasClientContact()
	{
	   $qry = "SELECT study_contact_id "
	        . "FROM study_contact "
	        . "WHERE study_id = ".$this->_study." AND study_account_id IS NULL";

	   $r = mysql_fetch_assoc($this->executeQuery($qry));

	   return ($r) ? true : false;
	}

	/**
	* GetInvoiceTotal()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Jun 23 08:30:21 PDT 2005
	*/
	function GetInvoiceTotal()
	{
	   $qry = "SELECT ba.value, ba.id, SUM(bb.actual_value) AS invoice_total, bm.invoice_date "
	        . "FROM br_attr AS ba "
	        . "LEFT OUTER JOIN br_budget AS bb ON bb.br_id = ba.id "
	        . "LEFT OUTER JOIN br_main AS bm ON bm.br_id = ba.id "
	        . "WHERE ba.key = 'STUDY_ID' AND ba.value = ".$this->_study." AND bm.br_type = 0 "
	        . " GROUP BY bb.br_id ";
	   //echo $qry;
	   return $this->executeQuery($qry);
	}

	/**
	* GetAccountManager()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jun 24 10:50:54 PDT 2005
	*/
	function GetAccountManager()
	{
	   $qry = "SELECT CONCAT(u.first_name,' ',u.last_name) AS name "
	        . "FROM study_user AS su "
	        . "LEFT OUTER JOIN user AS u ON u.login = su.login "
	        . "WHERE su.role_id = ".PRIMARY_ACCT_MGR." AND su.study_id = ".$this->_study;

	  $r = mysql_fetch_assoc($this->executeQuery($qry));

	  return ($r) ? $r['name'] : "not assinged";
	}

	/**
	* GetAccountExecutive()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jun 24 10:50:54 PDT 2005
	*/
	function GetAccountExecutive()
	{
	   $qry = "SELECT CONCAT(u.first_name,' ',u.last_name) AS name "
	        . "FROM study AS s "
	        . "LEFT OUTER JOIN study_user AS su ON su.role_id = ".PRIMARY_ACCT_EXEC." AND su.study_id = s.study_id "
	        . "LEFT OUTER JOIN user AS u ON u.login = su.login "
	        . "WHERE s.study_id= ".$this->_study;

	  $r = mysql_fetch_assoc($this->executeQuery($qry));

	  return ($r) ? $r['name'] : "not assinged";
	}

	/**
	* SetStage()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Jun 29 14:51:11 PDT 2005
	*/
	function SetStage($study_stage_id)
	{
	   $qry = "UPDATE study SET study_stage_id = ".$study_stage_id." WHERE study_id = ".$this->_study;
	   //echo $qry;
	   return $this->executeQuery($qry);
	}

	/**
	* GetStageByTask()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Jun 29 14:55:04 PDT 2005
	*/
	function GetStageByTask($study_task_id)
	{
	   $qry = "SELECT study_stage_id FROM study_task WHERE study_task_id = ".$study_task_id;
	   $r = mysql_fetch_assoc($this->executeQuery($qry));
	   return ($r) ? $r['study_stage_id'] : false;
	}

	/**
	* GetStageByTaskAndType()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Jul 28 15:56:52 PDT 2005
	*/
	function GetStageByTaskAndType($task_id, $type_id)
	{
	   $q = "SELECT stt.study_stage_id FROM study_type_task AS stt "
	      . "WHERE stt.study_task_id = ".$task_id." AND stt.study_type_id = ".$type_id." ";
	   $r = mysql_fetch_assoc($this->executeQuery($q));
	   return ($r) ? $r['study_stage_id'] : false;
	}

	/**
   * GetLastCompletedTimelineId()
   *
   * @param
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetLastCompletedTimelineId()
   {
      $qry  = "SELECT m.study_timeline_id ";
      $qry .= "FROM study_timeline AS m ";
      $qry .= "LEFT OUTER JOIN study_timeline_attr AS comp ON comp.study_timeline_attr = 'COMPDATE' AND comp.study_timeline_id = m.study_timeline_id ";
      $qry .= "LEFT OUTER JOIN study AS s ON s.study_id = m.study_id ";
      $qry .= "LEFT OUTER JOIN study_task AS st ON st.study_task_id = m.study_task_id ";
      $qry .= "WHERE m.status = 'A' AND s.current_revision_number = m.revision_number AND m.study_id = ".$this->_study." AND comp.study_timeline_value IS NOT NULL AND st.primary_task = 1 ";
		$qry .= "AND m.login != 0 ";
      $qry .= "ORDER BY m.sort_order DESC ";


      $r = mysql_fetch_assoc($this->executeQuery($qry));
      return ($r) ? $r['study_timeline_id'] : $r;
   }


   /**
   * GetVarCharAttr()
   *
   * @param
   * @param $key string
   * @return false or recordset
   * @throws
   * @access
   * @global
   * @since  - Mon Jul 04 20:39:05 PDT 2005
   */
   function GetVarCharAttr($key)
   {
      $qry = "SELECT study_value AS ".$key." FROM study_attr WHERE study_id = ".$this->_study." AND study_attr = '".$key."'";
      return mysql_fetch_assoc($this->executeQuery($qry));
   }

   /**
   * SetVarCharAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jul 04 20:45:33 PDT 2005
   */
   function SetVarCharAttr($key, $value)
   {
      $qry = "REPLACE INTO study_attr (study_id, study_attr, study_value) "
           . "VALUES (".$this->_study.", '".$key."', '".$value."' )";
      return $this->executeQuery($qry);
   }

   /**
   * GetStudyByStageId()
   *
   * @param
   * @param - int $study_stage_id
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jul 05 07:02:46 PDT 2005
   */
   function GetStudyByStageId($study_stage_id)
   {
      $qry = "SELECT s.study_id, s.study_name "
           . "FROM study AS s "
           . "WHERE s.study_stage_id = ".$study_stage_id." AND s.status = 'A' AND s.study_status_id = 1";

      return $this->executeQuery($qry);
   }

   /**
   * SetQuotaOnResponse()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jul 05 08:10:04 PDT 2005
   */
   function SetQuotaOnResponse($label, $required = 0, $progress = 0)
   {
      $qry = "INSERT INTO study_quota_on_response (study_id, study_quota_label, study_quota_required, study_quota_progress, created_by, created_date, status) "
           . "VALUES (".$this->_study.", '".$label."', ".$required.", ".$progress.", ".SYSTEM_USER.", NOW(), 'A') ";
      return $this->executeQuery($qry);
   }

   /**
   * GetQuotaOnResponse()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jul 05 11:50:29 PDT 2005
   */
   function GetQuotaOnResponse()
   {
      $qry = "SELECT study_quota_on_response_id, study_quota_label, study_quota_name, study_quota_required, study_quota_progress "
           . "FROM study_quota_on_response "
           . "WHERE study_id = ".$this->_study;

      return $this->executeQuery($qry);
   }

   /**
   * GetQuotaOnResponseIdByLabel()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jul 05 08:14:33 PDT 2005
   */
   function GetQuotaOnResponseIdByLabel($label)
   {
      $qry = "SELECT study_quota_on_response_id FROM study_quota_on_response WHERE study_quota_label = '".$label."'";
      $r = mysql_fetch_assoc($this->executeQuery($qry));
      return ($r) ? $r['study_quota_on_response_id'] : false;
   }

   /**
   * SetQuotaOnResponseById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jul 05 08:21:33 PDT 2005
   */
   function SetQuotaOnResponseById($id, $progress = 0, $required = 0)
   {
      $qry = "UPDATE study_quota_on_response "
           . "SET study_quota_required = ".$required.", study_quota_progress = ".$progress.", modified_by = ".SYSTEM_USER.", modified_date = NOW() "
           . "WHERE study_quota_on_response_id = ".$id;

      return $this->executeQuery($qry);
   }

   /**
   * SetQuotaOnResponseLog()
   *
   * @param
   * @param - ($row_id, $r_quota_on_response['quota_progress']);
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jul 05 08:26:34 PDT 2005
   */
   function SetQuotaOnResponseLog($id, $progress = 0)
   {
      $qry = "INSERT INTO study_quota_on_response_log ( study_quota_on_response_id, study_quota_progress, study_quota_on_response_date, created_by, created_date, status ) "
           . "VALUES (".$id.", ".$progress.", NOW(), ".SYSTEM_USER.", NOW(), 'A')";

      return $this->executeQuery($qry);
   }

   /**
   * SetStudyQuotaSummary()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 08:45:26 PDT 2005
   */
   function SetStudyQuotaSummary($o)
   {
      $qry = "INSERT INTO study_quota_summary "
           . "( study_id, study_quota_summary_date, completed_panel, completed_non_panel, invited_panel, invited_non_panel, "
           . "  started_panel, started_non_panel, incomplete_panel, incomplete_non_panel, screened_panel, screened_non_panel, routed_panel, routed_non_panel, "
           . "  created_by, created_date, status ) "
           . "VALUES (".$this->_study.", NOW(), '"
           .            $o['completed']['panel']."', '".$o['completed']['nonpanel']."', '"
           .            $o['invited']['panel']."', '".$o['invited']['nonpanel']."', '"
           .            $o['started']['panel']."', '".$o['started']['nonpanel']."', '"
           .            $o['incomplete']['panel']."', '".$o['incomplete']['nonpanel']."', '"
           .            $o['screened']['panel']."', '".$o['screened']['nonpanel']."', '"
           .            $o['routed']['panel']."', '".$o['routed']['non_panel']."', "
           .          SYSTEM_USER.", NOW(), 'A') ";

      return $this->executeQuery($qry);
   }


   /**
   * GetStudyQuotaSummary()
   *
   * Query Explanation - We need to get a summary for query data by date, but we store them on hourly basis, if we just group by the converted date
   * MySQL will give us the first count for the first record for that date, instead we need the last record for the date, but sorting happens after grouping
   * So we need a subquery that returns us the max hour stored, there is another way to do this.
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 09:05:25 PDT 2005
   */
   function GetStudyQuotaSummary($interval = 7)
   {
      $qry = "SELECT study_id, DATE_FORMAT(CONVERT_TZ(study_quota_summary_date,'+00:00','".$this->timezone."'),'%Y-%m-%d') AS study_quota_summary_date, completed_panel, completed_non_panel, invited_panel, invited_non_panel, "
           . "       started_panel, started_non_panel, incomplete_panel, incomplete_non_panel, screened_panel, screened_non_panel, routed_panel, routed_non_panel, "
           . "       DATE_FORMAT(CONVERT_TZ(study_quota_summary_date,'+00:00','".$this->timezone."'),'%Y%m%d') AS date_label "
           . "FROM study_quota_summary "
           . "WHERE study_id = ".$this->_study." AND DATEDIFF(NOW(),study_quota_summary_date) <= ".$interval
           //. "      AND (completed_panel != 0 OR completed_non_panel != 0) "
           . "      AND HOUR(CONVERT_TZ(study_quota_summary_date, '+00:00', '".$this->timezone."')) IN ( "
           . "   SELECT MAX(HOUR(CONVERT_TZ(study_quota_summary_date, '+00:00', '".$this->timezone."'))) "
           . "   FROM study_quota_summary "
           . "   WHERE study_id = ".$this->_study
           . "    GROUP BY DATE(CONVERT_TZ(study_quota_summary_date, '+00:00', '".$this->timezone."'))) "
           . " GROUP BY DATE(CONVERT_TZ(study_quota_summary_date,'+00:00','".$this->timezone."')) ";

      return $this->executeQuery($qry);

   }


   /**
   * GetLatestStudyQuotaSummary()
   * Return the latest actual incidence result of a study
   * @param
   * @return array
   * @throws
   * @access
   * @global integer $this->_study
   * @global string $this->timezone
   * @since  Tchan 2006-01-24
   */
   function GetLatestStudyQuotaSummary($study_id='')
   {
      if (!$study_id){
         $study_id = $this->_study;
      }
      settype($study_id, "integer");

      $qry = "SELECT study_id, "
           . "       study_quota_summary_date, "
           . "       completed_panel, "
           . "       completed_non_panel, "
           . "       incomplete_panel, "
           . "       incomplete_non_panel, "
           . "       invited_panel, "
           . "       invited_non_panel, "
           . "       screened_panel, "
           . "       screened_non_panel, "
           . "       started_panel, "
           . "       started_non_panel "
           . "       routed_panel, "
           . "       routed_non_panel, "
           . "FROM study_quota_summary "
           . "WHERE study_id = $study_id "
           . "ORDER BY study_quota_summary_date DESC "
           . "LIMIT 1 ";

      $r = mysql_fetch_assoc($this->executeQuery($qry));
      $r['study_quota_summary_timestamp'] = @strtotime($r['study_quota_summary_date']);

      return $r;
   }


   /**
   * GetStudyQuotaDetail()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 09:05:25 PDT 2005
   */
   function GetStudyQuotaDetail()
   {
      $qry = "SELECT study_id, DATE_FORMAT(CONVERT_TZ(study_quota_summary_date,'+00:00','".$this->timezone."'),'%Y-%m-%d') AS study_quota_summary_date, completed_panel, completed_non_panel, invited_panel, invited_non_panel, "
           . "       started_panel, started_non_panel, incomplete_panel, incomplete_non_panel, screened_panel, screened_non_panel, "
           . "       DATE_FORMAT(CONVERT_TZ(study_quota_summary_date,'+00:00','".$this->timezone."'),'%Y%m%d') AS date_label "
           . "FROM study_quota_summary "
           . "WHERE study_id = ".$this->_study;

      return $this->executeQuery($qry);

   }

   /**
   * GetTaskDurationById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 10:35:29 PDT 2005
   */
   function GetTaskDurationById($study_task_id)
   {
      $qry = "SELECT task_duration "
           . "FROM study_timeline "
           . "WHERE study_id = ".$this->_study." AND study_task_id = ".$study_task_id;

      $r = mysql_fetch_assoc($this->executeQuery($qry));

      return ($r) ? $r['task_duration'] : 0;
   }

   /**
   * UpdateStudyQuotaSummary()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 14:15:42 PDT 2005
   */
   function UpdateStudyQuotaSummary($o, $date = 'CURDATE()', $hour = 'HOUR(NOW())')
   {
      $qry = "UPDATE study_quota_summary SET "
           . "  completed_panel = '".$o['completed']['panel']."', completed_non_panel = '".$o['completed']['nonpanel']."', invited_panel = '".$o['invited']['panel']."', "
           . "   invited_non_panel = '".$o['invited']['nonpanel']."', started_panel = '".$o['started']['panel']."', started_non_panel = '".$o['started']['nonpanel']."', "
           . "   incomplete_panel = '".$o['incomplete']['panel']."', incomplete_non_panel = '".$o['incomplete']['nonpanel']."', screened_panel = '".$o['screened']['panel']."', "
           . "   screened_non_panel = '".$o['screened']['nonpanel']."', routed_panel = '".$o['routed']['panel']."', routed_non_panel = '".$o['routed']['non_panel']."', "
           . "   modified_by = ".SYSTEM_USER.", created_date = NOW() "
           . "WHERE study_id = ".$this->_study." AND DATE(study_quota_summary_date) = ".$date." AND HOUR(study_quota_summary_date) = ".$hour;

      return $this->executeQuery($qry);
   }

   /**
   * HasQuotaSummary()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 14:24:43 PDT 2005
   */
   function HasQuotaSummary($date = 'CURDATE()', $hour = 'HOUR(NOW())')
   {
      $qry = "SELECT study_quota_summary_id FROM study_quota_summary "
           . "WHERE study_id = ".$this->_study." AND DATE(study_quota_summary_date) = ".$date." AND HOUR(study_quota_summary_date) = ".$hour;
      $r = mysql_fetch_assoc($this->executeQuery($qry));
      return ($r) ? true : false;
   }

   /**
   * GetOnResponseQuotaLabelById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 16:53:14 PDT 2005
   */
   function GetOnResponseQuotaNameById($id)
   {
      $qry = "SELECT study_quota_name FROM study_quota_on_response WHERE study_quota_on_response_id = ".$id;
      $r = mysql_fetch_assoc($this->executeQuery($qry));
      return ($r) ? $r['study_quota_name'] : '';
   }

   /**
   * SetOnResponseQuotaNameById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 16:58:26 PDT 2005
   */
   function SetOnResponseQuotaNameById($quota_name, $id)
   {
      $qry = "UPDATE study_quota_on_response SET study_quota_name = '".$quota_name."' WHERE study_quota_on_response_id = ".$id;
      return $this->executeQuery($qry);
   }

   /**
   * SetStudyFile($file_type_id, $study_file_type_id, $file_name, $file_size, $data)
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 18:47:06 PDT 2005
   */
   function SetStudyFile($file_type_id, $study_file_type_id, $file_name, $file_size, $data, $file_title, $login)
   {
      $file_size = strlen($data);   // note: this line must go before addslashes($data)
      $file_title = mysql_real_escape_string($file_title);
      $file_name = mysql_real_escape_string($file_name);
      $file_type_id = sprintf('%d', $file_type_id);
      $study_file_type_id = sprintf('%d', $study_file_type_id);
      $login = sprintf($login);

      $qry = "INSERT INTO study_file (study_file_type_id, study_id, file_type_id, study_file_name, study_file_title, study_file_data, study_file_size, created_by, created_date, status) "
           . "VALUES ({$study_file_type_id}, {$this->_study}, {$file_type_id}, '{$file_name}', '{$file_title}', '".mysql_real_escape_string($data)."', {$file_size}, {$login}, NOW(), 'A')";

      return $this->executeQuery($qry);
   }

   /**
   * SetStudyTemplateFile()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Apr 27 15:07:08 PDT 2006
   */
   function SetStudyTemplateFile($file_type_id, $study_file_type_id, $file_name, $file_size, $data, $file_title, $login)
   {
      $file_size = strlen($data);   // note: this line must go before addslashes($data)
      $data = addslashes($data);
      $file_title = mysql_real_escape_string($file_title);
      $file_name = mysql_real_escape_string($file_name);
      $file_type_id = sprintf('%d', $file_type_id);
      $study_file_type_id = sprintf('%d', $study_file_type_id);
      $login = sprintf($login);

      $qry = "INSERT INTO study_template_file (study_file_type_id, study_template_id, file_type_id, study_template_file_name, study_template_file_title, study_template_file_data, study_template_file_size, created_by, created_date, status) "
           . "VALUES ({$study_file_type_id}, {$this->_template_id}, {$file_type_id}, '{$file_name}', '{$file_title}', '{$data}', {$file_size}, {$login}, NOW(), 'A')";

      return $this->executeQuery($qry);
   }

   /**
   * GetStudyTemplateFiles()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Apr 27 15:23:51 PDT 2006
   */
   function GetStudyTemplateFiles()
   {
      $q = "SELECT
               study_template_file_id,
               study_file_type_id,
               study_template_file_title
            FROM study_template_file
            WHERE study_template_id='".$this->_template_id."' AND status='A'";
      return $this->executeQuery($q);
   }

   /**
   * GetStudyFilesByType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 20:00:07 PDT 2005
   */
   function GetStudyFileListByType($file_type_id)
   {
      $qry = "SELECT study_file_id, study_id, study_file_type_id, file_type_id, study_file_name, study_file_size, study_file_data, study_file_title "
           . "FROM study_file "
           . "WHERE study_id = ".$this->_study." AND study_file_type_id = ".$file_type_id." AND status='A'";

      return $this->executeQuery($qry);
   }
   
   /**
    * Returns Study Files without file data
    *
    * @param int $file_type_id 
    * @return
    */ 
   function GetStudyFilesByType($file_type_id) 
   { 
   	$qry = "SELECT study_file_id, study_id, study_file_type_id, file_type_id, study_file_name, study_file_size, study_file_title "
           . "FROM study_file "
           . "WHERE study_id = ".$this->_study." AND study_file_type_id = ".$file_type_id." AND status='A'";

      return $this->executeQuery($qry);
   }

   /**
   * GetStudyFileById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 20:18:22 PDT 2005
   */
   function GetStudyFileById($id)
   {
      $qry = "SELECT ft.file_type_description, sf.study_file_name, sf.study_file_title, sf.study_file_size, sf.study_file_data "
           . "FROM study_file AS sf "
           . "LEFT OUTER JOIN file_type AS ft ON ft.file_type_id = sf.file_type_id "
           . "WHERE sf.study_file_id = ".$id;

      return mysql_fetch_assoc($this->executeQuery($qry));
   }

   /**
   * GetStudyCostFileById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed May 17 13:43:59 PDT 2006
   */
   function GetStudyCostFileById($id)
   {
      $qry = "SELECT scf.study_cost_file_name, scf.study_cost_file_title, scf.study_cost_file_size, scf.study_cost_file_data "
           . "FROM study_cost_file AS scf "
           . "WHERE scf.study_cost_file_id = ".$id;

      return mysql_fetch_assoc($this->executeQuery($qry));
   }

   /**
   * GetStudyTemplateFileById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Apr 27 15:42:33 PDT 2006
   */
   function GetStudyTemplateFileById($id)
   {
      $qry = "SELECT
                ft.file_type_description,
                stf.file_type_id,
                stf.study_file_type_id,
                stf.study_template_file_name,
                stf.study_template_file_title,
                stf.study_template_file_size,
                stf.study_template_file_data "
           . "FROM study_template_file AS stf "
           . "LEFT OUTER JOIN file_type AS ft ON ft.file_type_id = stf.file_type_id "
           . "WHERE stf.study_template_file_id = ".$id;

      return mysql_fetch_assoc($this->executeQuery($qry));
   }

   /**
   * DeleteStudyTemplateFile()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Apr 27 15:51:07 PDT 2006
   */
   function DeleteStudyTemplateFile($id)
   {
      $q = "UPDATE study_template_file SET status='D', modified_by='".$this->created_by."', modified_date=NOW() WHERE study_template_file_id='$id'";
      $this->executeQuery($q);
      return true;
   }

   /**
   * DeleteStudyFiles()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu May 18 13:50:34 PDT 2006
   */
   function DeleteStudyFiles($study_id)
   {
      $q = "UPDATE study_file SET status='D', modified_by='".$this->created_by."', modified_date=NOW() WHERE study_id='".$study_id."'";
      $this->executeQuery($q);
      return true;
   }

    /**
   * SetCommentFile($file_type_id, $study_file_type_id, $file_name, $file_size, $data)
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 18:47:06 PDT 2005
   */
   function SetCommentFile($study_comment_id, $file_type_id, $file_name, $file_size, $data, $file_title, $login)
   {
      $file_size = strlen($data);   // note: this line must go before addslashes($data)
      $data = addslashes($data);
      $file_name = mysql_real_escape_string($file_name);
      $file_title = mysql_real_escape_string($file_title);
      $login = sprintf('%d', $login);
      $file_type_id = sprintf('%d', $file_type_id);

      $qry = "INSERT INTO study_comment_file (study_comment_id, file_type_id, study_comment_file_name, study_comment_file_title, study_comment_file_data, study_comment_file_size, created_by, created_date, status) "
           . "VALUES (".$study_comment_id.", ".$file_type_id.", '".$file_name."', '".$file_title."', '".$data."', ".$file_size.", ".$login.", NOW(), 'A')";

      return $this->executeQuery($qry);
   }

   /**
   * GetCommentFileById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 20:18:22 PDT 2005
   */
   function GetCommentFileByCommentId($id)
   {
      $qry = "SELECT ft.file_type_description, scf.study_comment_file_name, scf.study_comment_file_title, "
           . "scf.study_comment_file_size, scf.study_comment_file_data "
           . "FROM study_comment_file AS scf "
           . "LEFT OUTER JOIN file_type AS ft ON ft.file_type_id = scf.file_type_id "
           . "WHERE scf.study_comment_id = ".$id;

      return mysql_fetch_assoc($this->executeQuery($qry));
   }



   function GetCommentFilesByCommentId($study_comment_id)
   {
      $study_comment_id = sprintf('%d', $study_comment_id);

      $qry  = "SELECT scf.study_comment_file_id, "
            . "  scf.file_type_id, "
            . "  scf.study_comment_file_name, "
            . "  scf.study_comment_file_title, "
            . "  scf.study_comment_file_size "
            . "FROM study_comment_file AS scf "
            . "WHERE scf.study_comment_id = $study_comment_id ";
      $files = array();
      $rs = $this->executeQuery($qry);
      while ($r = mysql_fetch_assoc($rs)){
         $study_comment_file_id = $r['study_comment_file_id'];
         $files[$study_comment_file_id] = $r;
      }
      return $files;
   }


   function GetDepartments()
   {
      $qry = "SELECT department_id, department FROM department WHERE status='A' ORDER BY department ";
      $departments = array();
      $rs = $this->executeQuery($qry);
      while ($r = mysql_fetch_assoc($rs)){
         $department_id = $r['department_id'];
         $departments[$department_id] = $r['department'];
      }
      return $departments;
   }


   /**
   * GetCommentFileById()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 06 20:18:22 PDT 2005
   */
   function GetCommentFile($study_comment_file_id)
   {
      $study_comment_file_id = sprintf('%d', $study_comment_file_id);

      $qry = "SELECT ft.file_type_description, scf.study_comment_file_name, scf.study_comment_file_title, "
           . "scf.study_comment_file_size, scf.study_comment_file_data "
           . "FROM study_comment_file AS scf "
           . "LEFT OUTER JOIN file_type AS ft ON ft.file_type_id = scf.file_type_id "
           . "WHERE scf.study_comment_file_id = ".$study_comment_file_id;

      return mysql_fetch_assoc($this->executeQuery($qry));
   }


   /**
   * GetFlashSummary()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Jul 07 22:09:33 PDT 2005
   */
   function GetFlashSummary()
   {
      $qry = "SELECT SUM(IF(s.alert_level_id = 1,1,0)) AS count_green, "
           . "       SUM(IF(s.alert_level_id = 2,1,0)) AS count_yellow, "
           . "       SUM(IF(s.alert_level_id = 3,1,0)) AS count_orange, "
           . "       SUM(IF(s.alert_level_id = 4,1,0)) AS count_red, "
           . "       COUNT(*) AS count_total "
           . "FROM study AS s "
           . "WHERE s.study_status_id = ".STATUS_OPEN;

      return mysql_fetch_assoc($this->executeQuery($qry));
   }

   /**
   * GetTimelineCreatedDate()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jul 12 19:40:16 PDT 2005
   */
   function GetTimelineCreatedDate()
   {
      $qry = "SELECT DATE_FORMAT(CONVERT_TZ(timeline_created_date ,'+00:00','".$this->timezone."'),'%Y-%m-%d %H:%i:%S') AS timeline_created_date "
           . "FROM study "
           . "WHERE study_id = ".$this->_study;
      $r = mysql_fetch_assoc($this->executeQuery($qry));
      return ($r) ? $r['timeline_created_date'] : null;
   }

   /**
   * SetTaskDurationByTimelineId()
   *
   * @param int $study_timeline_id reference to the timeline id
   * @param int $duration study task duration
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jul 12 19:55:13 PDT 2005
   */
   function SetTaskDurationByTimelineId($study_timeline_id, $duration = 0)
   {
      $qry = "UPDATE study_timeline SET task_duration = ".$duration
           . " WHERE study_timeline_id = ".$study_timeline_id;
      return $this->executeQuery($qry);
   }

   /**
   * GetPendingSatSurvey()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Sep 12 15:38:51 PDT 2005
   */
   function GetStudiesWithSatisfactionSurveyStatusIn($statuses)
   {
      if (!is_array($statuses)){
         $statuses = array($statuses);
      }

      $status_list = "";
      foreach ($statuses as $status){
         $status_list .= "'".mysql_real_escape_string($status)."',";
      }
      $status_list = preg_replace("/,$/", "", $status_list);   // remove last comma in the list

      $q = "SELECT study_id "
         . "FROM study_attr "
         . "WHERE study_attr = 'SAT_SURVEY_STATUS' AND study_value IN ($status_list) "
         . "ORDER BY study_id DESC ";
      return $this->executeQuery($q);
   }



   function GetSatSurveysSentTo($email, $from_date_timestamp)
   {
      // note: we use FULL JOIN to perform the constraint.

      $qry  = "SELECT sa_status.study_id,  "
            . "       sa_date.study_value AS sat_survey_sent_date, "
            . "       sa_status.study_value AS sat_survey_status "
            . "FROM study_attr AS sa_status "
            . "JOIN study_attr AS sa_email ON "
            . "       sa_email.study_attr='SAT_SURVEY_CONTACT_EMAIL' AND "
            . "       sa_email.study_id=sa_status.study_id AND sa_email.study_value='{$email}' "
            . "JOIN study_attr AS sa_date ON "
            . "       sa_date.study_attr = 'SAT_SURVEY_SENT_DATE' AND "
            . "       sa_date.study_id = sa_status.study_id AND "
            . "      sa_date.study_value >= FROM_UNIXTIME($from_date_timestamp) "
            . "WHERE sa_status.study_attr = 'SAT_SURVEY_STATUS' AND "
            . "      sa_status.study_value = 'SENT' "
            . "ORDER BY sa_date.study_value DESC ";

      $rs = $this->executeQuery($qry);
      $studies = array();
      while ($r = mysql_fetch_assoc($rs)) {
         $study_id = $r['study_id'];
         $studies[$study_id] = $r;
      }
      return $studies;
   }


   /**
   * GetStudyContactTypes()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Sep 14 11:55:43 PDT 2005
   */
   function GetStudyContactTypes()
   {
      $q = "SELECT study_contact_type_description, study_contact_type_id "
         . "FROM study_contact_type "
         . "WHERE status = 'A'";
      return $this->executeQuery($q);
   }

   /**
   * SetStudyContactType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Sep 15 09:33:21 PDT 2005
   */
   function SetStudyContactType($contact_id, $contact_type_id)
   {
      $q = "UPDATE study_contact SET study_contact_type_id = " . $contact_type_id
         . " WHERE study_contact_id = " . $contact_id;
      return $this->executeQuery($q);
   }

   /**
   * GetStudyContactByType()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Sep 15 09:53:44 PDT 2005
   */
   function GetStudyContactByType($study_contact_type_id)
   {
      $study_id = sprintf('%d', $this->_study);
      $study_contact_type_id = sprintf('%d', $study_contact_type_id);

      $q = "SELECT study_id, contact_id, salutation, first_name, middle_initial, last_name, phone, fax, email, tcm_recipient "
         . "FROM study_contact "
         . "WHERE study_id = $study_id AND status = 'A' AND study_contact_type_id = $study_contact_type_id";
     return $this->executeQuery($q);
   }

   /**
   * GetStudyInterviewTypes()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Oct 18 20:05:55 PDT 2005
   */
   function GetStudyInterviewTypes()
   {
      $q = "SELECT study_interview_type_id, study_interview_type_description "
         . "FROM  study_interview_type "
         . "WHERE status = 'A' ";
      return $this->executeQuery($q);
   }

   /**
   * GetFieldWorkDurations()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Oct 18 20:08:56 PDT 2005
   */
   function GetStudyFieldWorkDurations()
   {
      $q = "SELECT study_fieldwork_duration_id, study_fieldwork_duration_description "
         . "FROM study_fieldwork_duration "
         . "WHERE status = 'A' ORDER BY study_fieldwork_duration_description";
      return $this->executeQuery($q);
   }

   /**
   * isTranslated()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Oct 21 14:08:51 PDT 2005
   */
   function isTranslated()
   {
      $q = "SELECT study_value FROM study_attr WHERE study_attr = 'GMI_TRANS' AND study_id = ". $this->_study;
      $r = mysql_fetch_assoc($this->executeQuery($q));
      return ($r['study_value'] == 'on') ? 1 : 0;
   }

   /**
   * GetProgrammingTypes()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Thu Dec 08 11:52:06 PST 2005
   */
   function GetProgrammingTypes()
   {
      $q = "SELECT spt.study_programming_type_id, spt.study_programming_type_description "
         . "FROM study_programming_type AS spt "
         . "WHERE status = 'A' ";
      return $this->executeQuery($q);
   }



   /**
   * GetUserLocationList
   *
   * @param
   * @return
   * @throws
   * @access
   * @global
   */   function GetUserLocationList()
   {
      $qry  = "SELECT location_id, location_description "
            . "FROM location  "
            . "WHERE status = 'A' "
            . "ORDER BY location_description ASC ";
      return $this->executeQuery($qry);
   }


   /**
   * GetUsersByLocationId
   *
   * @param
   * @return
   * @throws
   * @access
   * @global
   */   function GetUsersByLocationId($location_id)
   {
      $location_id = sprintf('%d', $location_id);
      $qry  = "SELECT login, first_name, last_name "
            . "FROM user "
            . "WHERE status = 'A' AND location_id=$location_id ";
      return $this->executeQuery($qry);
   }


   /**
   * GetStudyUserRoles
   *
   * @param
   * @return
   * @throws
   * @access
   * @global
   */   function GetStudyUserRoles()
   {
      $qry  = "SELECT su.roles_id, IF(r.role_description IS NULL, su_roles_id, r.role_description) AS role_description "
            . "FROM study_user AS su "
            . "LEFT OUTER JOIN role AS r "
            . "GROUP BY su.roles_id ";
      return $this->executeQuery($qry);
   }


   /**
   * GetAllAssignedStudyUsers
   *
   * @param
   * @return
   * @throws
   * @access
   * @global
   */
   function GetAllAssignedStudyUsers()
   {
      $qry  = "SELECT su.login, CONCAT(u.first_name,' ',u.last_name) AS name "
            . "FROM study_user AS su "
            . "JOIN user AS u ON u.login = su.login "
            . "GROUP BY su.login "
            . "ORDER BY u.first_name, u.last_name ";
      return $this->executeQuery($qry);
   }

   /**
   * DeleteTemplateAttrs()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.1 - Thu Nov 02 17:24:57 PST 2006
   */
   public function DeleteTemplateAttrs($template_id)
   {
      $q = "UPDATE study_template_attr SET status='D' WHERE study_template_id='$template_id'";
      $this->executeQuery($q);

      $q = "UPDATE study_template_attr_memo SET status='D' WHERE study_template_id='$template_id'";
      $this->executeQuery($q);
   }

   /**
   * DeleteTemplateContacts()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.1 - Thu Nov 02 17:25:51 PST 2006
   */
   public function DeleteTemplateContacts($template_id)
   {
      $q = "UPDATE study_template_contact SET status='D' WHERE study_template_id='$template_id'";
      $this->executeQuery($q);
   }

   /**
   * DeleteTemplateFiles()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.1 - Thu Nov 02 17:26:24 PST 2006
   */
   public function DeleteTemplateFiles($template_id)
   {
      $q = "UPDATE study_template_file SET status='D' WHERE study_template_id='$template_id'";
      $this->executeQuery($q);
   }

   /**
   * DeleteTemplateTimeline()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.1 - Thu Nov 02 17:29:01 PST 2006
   */
   public function DeleteTemplateTimeline($template_id)
   {
      $q = "UPDATE study_template_timeline SET status='D' WHERE study_template_id='$template_id'";
      $this->executeQuery($q);
   }

   /**
   * DeleteTemplate()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.1 - Thu Nov 02 17:29:25 PST 2006
   */
   public function DeleteTemplate($template_id)
   {
      $q = "UPDATE study_template SET status='D' WHERE study_template_id='$template_id'";
      $this->executeQuery($q);
   }
   
   
  
}


?>
