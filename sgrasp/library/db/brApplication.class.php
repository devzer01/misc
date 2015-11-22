<?php

class brApplication extends brDB
{

	function brApplication()
	{
		$this->dbConnect();
	}

	function getUserFilter()
	{
		global $userRights;

		if ($userRights->userSecCheck('VIEWMYBR')) {
			if ($userRights->userSecCheck('VIEWOTHERSBR')) {
				$qFilter .= " AND (prt.acct_exec IN ".$this->getReportFilter($_SESSION[admin_id]);
				$qFilter .= " OR prt.proj_mgr IN ".$this->getReportFilter($_SESSION[admin_id]);
				$qFilter .= " )";
			} else {
				$qFilter .= " AND (prt.acct_exec = '$_SESSION[admin_id]' OR prt.proj_mgr = '$_SESSION[admin_id]' OR pu.login = '".$_SESSION['admin_id']."') ";
			}
		}

		if ($userRights->userSecCheck('ATM_ARMC_VIEW_MY_BR')) {
		   $qFilter =  " AND br.created_by = '".$_SESSION['admin_id']."' ";
		}

		return $qFilter;

	}

	function getMergeFilter($o)
	{
		global $userRights;

		$qFilter = $this->getUserFilter();

		if ($userRights->userSecCheck('BRSTRICT') || $_POST['frmName'] == 'frmSearch') {
			$qFilter .= $this->getSearchFilter();
		} else {
			$qFilter .= $this->getMergeDefaultFilter($o);
		}

		return $qFilter;

	}

	function getSingleFilter($o)
	{

 		global $userRights;

      $qFilter = $this->getUserFilter();

      if ($userRights->userSecCheck('BRSTRICT') || $_POST['frmName'] == 'frmSearch') {
         $qFilter .= $this->getSearchFilter();
      } else {
         $qFilter .= $this->getSingleDefaultFilter($o);
      }

      return $qFilter;

	}

	function stallSingle($brID)
	{
		$this->stallBR($brID);
		return true;
	}

	function stallMerge($brID)
	{
		$qry = "SELECT br_id FROM br_main WHERE merge_id = ".$brID;
		$rs = $this->executeQuery($qry);
		while ($r = mysql_fetch_array($rs,MYSQL_ASSOC)) {
			$this->stallBR($r['br_id']);
		}
		return true;
	}

	function handleExceptions($br)
	{
		//RESET INVOICE DATE
		$oDetails['invDate'] = $this->getKey('br_attr',$br,'INVDATE');
		$oDetails['br'] = $br;
		if($oDetails['invDate']){
			$this->setInvoiceDate($oDetails);
		}
	}

	//* return true if this br is for month end billing
	function isMonthEndBilling($br)
	{
		$this->getBrType($br);
		$qry = "SELECT month_end_billing FROM partners WHERE partner_id = ".$this->getPartnerID($br);
		return mysql_result($this->executeQuery($qry),0,0);
	}

	function isAeApprovalRequired($br) {
	   $this->getBrType($br);
		$qry = "SELECT br_needs_ae_approval FROM partners WHERE partner_id = '". $this->getPartnerID($br) ."'";
		return mysql_result($this->executeQuery($qry),0,0);
	}

	function getPartnerID($br)
	{
		if ($this->requireStudy($this->GetType($br))) {
			$qPartnerID = "SELECT prj.partner_id
								FROM br_attr AS study_id
								LEFT OUTER JOIN study AS prj ON
									study_id.value = prj.study_id
								WHERE study_id.key = 'STUDY_ID'
								AND study_id.id = ".$br;
		} else {
			$qPartnerID = "SELECT partner_id.value AS partner_id
								FROM br_attr AS partner_id
								WHERE partner_id.key = 'PARTNERID'
								AND partner_id.id = ".$br;
		}

		return mysql_result($this->executeQuery($qPartnerID),0,0);
	}

	function requireStudy($type)
	{
		if ($type == TYPE_STUDY || $type == TYPE_CREDIT_MEMO || $type == TYPE_RETAINER){
			return true;
		}
		return false;
	}

	function getBrType($br)
	{
		$qGetType = "SELECT br_type FROM br_main WHERE br_id = ".$br;
		$this->_type = mysql_result($this->executeQuery($qGetType),0,0);
		return true;
	}

	function isMerge($br)
	{
		$qMerge = "SELECT merge_id FROM br_main WHERE br_id = ".$br;
		return mysql_result($this->executeQuery($qMerge),0,0);
	}

	function getCompanyName($partnerID)
	{
		$qCompanyName = "SELECT company_name FROM partners WHERE partner_id = '".$partnerID."'";
		return @ mysql_result($this->executeQuery($qCompanyName),0,0);
	}

	function getBrDetail($br)
	{
		if($this->isMerge($br)) {
			//get the merge detail

		} else {
			//get single detail
		}
	}

	function getMergeDetail($br)
	{

	}


	function getSingleDetail($br)
	{
		$this->getBrType($br);
		$oDetail['partner_id'] = $this->getPartnerID($br);
		$oDetail['company_name'] = $this->getCompanyName($oDetail['partner_id']);
		$oDetail['study_name'] = $this->getBrPrjName($br);
		$oDetail['ae'] = $this->getAe($oDetail['partner_id']);
		$oDetail['am'] = $this->getAm($oDetail['partner_id']);
		$oDetail['br_total'] = $this->getBrTotal($br);
		return $oDetail;
	}

	function getBrPrjName($br)
	{
		if($this->requireStudy($this->GetType($br))) {
			return $this->getStudyName($br);
		}
		return $this->getProjectName($br);
	}

	function getStudyName($br)
	{
		$qry  = "SELECT study.study_name ";
      $qry .= "FROM study AS study ";
		$qry .= "LEFT OUTER JOIN br_attr AS study_id ON study_id.key = 'STUDY_ID' AND study_id.value = study.study_id ";
      $qry .= "WHERE study_id.id = ".$br;

		$rs = $this->executeQuery($qry);
		return mysql_result($rs,0,study_name);
	}

	function getStudyNumber($br)
   {
      $qry  = "SELECT study.study_id ";
      $qry .= "FROM study AS study ";
      $qry .= "LEFT OUTER JOIN br_attr AS study_id ON study_id.key = 'STUDY_ID' AND study_id.value = study.study_id ";
      $qry .= "WHERE study_id.id = ".$br;

      $rs = $this->executeQuery($qry);
      return mysql_result($rs,0,study_id);
   }

	function getProjectName($br)
	{
		return $this->getKey('br_attr',$br,'STUDYNAME');
	}


	function getAm($partner)
	{
		$qry  = "SELECT u.last_name,u.login,u.first_name ";
      $qry .= "FROM partners ";
		$qry .= "LEFT OUTER JOIN user AS u ON partners.proj_mgr = u.login ";
		$qry .= "WHERE partner_id = '".$partner."'";

		return mysql_fetch_array($this->executeQuery($qry),MYSQL_ASSOC);
	}

	function getAe($partner)
	{
		$qry  = "SELECT u.last_name,u.login,u.first_name ";
      $qry .= "FROM partners ";
		$qry .= "LEFT OUTER JOIN user AS u ON partners.acct_exec = u.login ";
		$qry .= "WHERE partner_id = '".$partner."'";

		return mysql_fetch_array($this->executeQuery($qry),MYSQL_ASSOC);
	}

	function getBrTotal($br)
	{
		$qry = "SELECT SUM(actual_value) AS actual FROM br_budget WHERE br_id = ".$br;
		$rs = $this->executeQuery($qry);
		return mysql_result($rs,0,actual);
	}

	function unmerge($br)
	{
		$qry = "UPDATE br_main SET merge_id = 0 WHERE br_id = ".$br;
		$rs =	$this->executeQuery($qry);
		return true;
	}

   /**
   * GetApprovalFlags()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Sep 12 16:22:56 PDT 2005
   */
   function GetMainApprovalFlags($br_id)
   {
      $q = "SELECT head_am_flag, study_am_flag FROM br_main WHERE br_id = " . $br_id;
      return mysql_fetch_assoc($this->executeQuery($q));
   }

   /**
   * GetBudgetApprovalFlags()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Sep 12 16:24:27 PDT 2005
   */
   function GetBudgetApprovalFlags($br_id)
   {
      $q = "SELECT flag_AM FROM br_budget WHERE br_id = " . $br_id;
      return $this->executeQuery($q);
   }

   /**
   * GetInvoiceDate()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Sep 12 16:29:28 PDT 2005
   */
   function GetInvoiceDate($br_id)
   {
      $q = "SELECT invoice_date FROM br_main WHERE br_id = " . $br_id
         . " AND ( invoice_date != '0000-00-00 00:00:00' AND invoice_date IS NOT NULL)";
      return mysql_fetch_assoc($this->executeQuery($q));
   }

}

?>
