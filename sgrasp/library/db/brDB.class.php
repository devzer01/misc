<?php

class brDB extends dbConnect
{
	/* Store the id of a BR here */
	var $_brID = 0;

	/* Flag us about merge or not */
	var $_isMerge = false;

	var $_dateFilter = '';

	var $_type = 0;

	/* Constructur we will store the ID of the BR here */
	function brDB()
	{
		$this->dbConnect();
	}

	/* deprictaed */
	function openReport($brID)
	{
		$this->_brID = $brID;
	}

	function getStatus()
	{
		$rsStatus = $this->executeQuery("SELECT br_status FROM br_main WHERE br_id = '".$this->_brID."'");
		return mysql_result($rsStatus,0,0);
	}

	function getMergeStatus($mergeID)
	{
		$qMerge = "SELECT br_status FROM br_main WHERE merge_id = $mergeID GROUP BY br_status";
		$rsMerge = $this->executeQuery($qMerge);
		if ($this->numRows == 1) {
			return mysql_result($rsMerge,0,0);
		} else {
			return 0;
		}
	}

	function destruct()
	{
		$this->dbClose();
	}

	/* check if a study exisit for this */
	function haveStudy($study_id)
	{
	 	$qHaveStudy = "SELECT * FROM study WHERE study_id = '$study_id'";
    	$rsHaveStudy = $this->executeQuery($qHaveStudy);

		if ($this->numRows == 0) {
      	return false;
      }
		return true;
	}

	/*Get Retainer Amount*/
	function getRetainerAmount($brID)
	{
		$rsRetainer = $this->executeQuery("SELECT SUM(actual_value) FROM br_budget WHERE br_id = $brID AND budget_id = '1783'");
		if ($this->numRows == 0) {
			return false;
		}
		return mysql_result($rsRetainer,0,0);
	}

	/* set the preliminary and post flags here */
	function setFlag($const)
	{
		$qFlag = "UPDATE br_main SET pre_flag = ".$const." WHERE br_id = ".$this->_brID;
		return $this->executeQuery($qFlag);
	}

	/* Check if what we have is a Retainer */
	function isRetainer($brID)
	{
		$qry = "SELECT br_type FROM br_main WHERE br_id = $brID";
		$rs = $this->executeQuery($qry);
		$brType = mysql_result($rs,0,0);

		if ($brType == TYPE_RETAINER || $brType == TYPE_RT_SERVICE || $brType == TYPE_RT_LICENSE || $brType == TYPE_RT_TRAINING) {
			return true;
		} else {
			return false;
		}
	}

	function isMerge($br)
	{
		$qMerge = "SELECT merge_id FROM br_main WHERE br_id = ".$br;
		return mysql_result($this->executeQuery($qMerge),0,0);
	}

	function setInvoiceDate($oDetails)
	{
		$qInvoiceDate = "UPDATE br_main SET invoice_date = '".$oDetails['invDate']."', br_date = '".$oDetails['invDate']."' WHERE br_id = ".$oDetails['br'];
		$this->executeQuery($qInvoiceDate);
		return true;
	}


	/* Get BR Type from all the differnt Variables We Got*/
	function getBRType()
	{
		if($_POST[br_type] == MAIN_BR) {
			if($_POST[type] == 'study') {
				return TYPE_STUDY;
			} else {
				if ($_POST[nonstudy_type] == 'SB') {
					return TYPE_SERVICE;
				} elseif ($_POST[nonstudy_type] == 'LI') {
					return TYPE_LICENSE;
				} elseif ($_POST[nonstudy_type] == 'TR') {
					return TYPE_TRAINING;
				} elseif ($_POST[nonstudy_type] == 'OT') {
					return TYPE_BR_SOFTWARE;
				}
			}
		} elseif ($_POST[br_type] == MAIN_CREDIT_MEMO) {
			if($_POST[type] == 'study') {
            return TYPE_CREDIT_MEMO;
         } else {
            if ($_POST[nonstudy_type] == 'SB') {
               return TYPE_CM_SERVICE;
            } elseif ($_POST[nonstudy_type] == 'LI') {
               return TYPE_CM_LICENSE;
            } elseif ($_POST[nonstudy_type] == 'TR') {
               return TYPE_CM_TRAINING;
            }
         }
		} elseif ($_POST[br_type] == MAIN_RETAINER) {
			if($_POST[type] == 'study') {
            return TYPE_RETAINER;
         } else {
            if ($_POST[nonstudy_type] == 'SB') {
               return TYPE_RT_SERVICE;
            } elseif ($_POST[nonstudy_type] == 'LI') {
               return TYPE_RT_LICENSE;
            } elseif ($_POST[nonstudy_type] == 'TR') {
               return TYPE_RT_TRAINING;
            }
         }
		}
		return -1;
	}

	/* Retrive br Details Using Key */
	function getDetails($key)
	{
		return $this->getKey('br_attr',$this->_brID,$key);
	}


	/* check if we have a BR for this study*/
	function haveBR($study)
	{
		$qHaveBR = "SELECT id FROM br_attr WHERE `key` = 'STUDY_ID' AND value = '$study'";
      $rsHaveBR = $this->executeQuery($qHaveBR);
      return $this->numRows;
	}

	/* Return prefix for each BR type*/
	function getBRPrefix($brType)
	{
		if ($brType == TYPE_LICENSE) {
      	$studyPrefix = 'LI';
      } elseif ($brType == TYPE_TRAINING) {
      	$studyPrefix = 'TR';
      } elseif ($brType == TYPE_SERVICE) {
      	$studyPrefix = 'SB';
		} elseif ($brType == TYPE_BR_SOFTWARE) {
			$studyPrefix = 'SW';
      } else {
			$studyPrefix = 'MS';
		}
		return $studyPrefix;
	}

	/* Add New BR record*/
	function addBR($brType,$adminId)
	{
		$qBrAdd = "INSERT INTO br_main (br_type,created_by,created_date) VALUES ('$brType','$adminId',NOW())";
     	$rsBrAdd = $this->executeQuery($qBrAdd);
		$this->_brID = $this->lastID;
		return $this->lastID;
	}

	function getNonStudyType($brType)
	{
		if ($brType == TYPE_RT_SERVICE || $brType == TYPE_CM_SERVICE) {
			return TYPE_SERVICE;
		} elseif ($brType == TYPE_RT_TRAINING || $brType == TYPE_CM_TRAINING) {
			return TYPE_TRAINING;
		} elseif ($brType == TYPE_RT_LICENSE || $brType == TYPE_CM_LICENSE) {
			return TYPE_LICENSE;
		} else {
			return $brType;
		}

	}

	function isOmniBus($id)
	{
      $qry = "SELECT count(*) AS omnibus FROM br_budget WHERE br_id = ".$id." AND budget_id = ".BUDGET_ID_OMNIBUS;
      $rs = $this->executeQuery($qry);
      return mysql_result($rs,0,'omnibus');
   }

	/* Retrive the next available NonStudyID for reference in BR*/
	function getNonStudyID($brType)
	{
		$brType = $this->getNonStudyType($brType);
		$studyID = $this->getKey('appAttr','BR',$brType);
      $studyID += 1;
		$this->updateKey('appAttr','BR',$brType,$studyID);
		return $studyID;
	}

	/* Get the primary contact ID
    * @input - partner_id
    * @output - contact_id */
	function getPrimaryContactByClient($partner)
	{
		$qPrimaryContact = "SELECT prt.primary_contact FROM partners AS prt WHERE prt.partner_id = '$partner'";
		$rsPrimaryContact = $this->executeQuery($qPrimaryContact);
		if ($this->numRows == 0) {
			return false;
		}
		return mysql_result($rsPrimaryContact,0,0);
	}

	/* Return Primary Contact ID using Study Number */
	function getPrimaryContactByStudy($studyID)
	{
		$qPrimaryContact = "
			SELECT
				prt.primary_contact
			FROM
				study AS study
			LEFT OUTER JOIN
				partners AS prt ON
					study.partner_id = prt.partner_id
			WHERE
				study.study_id = '$studyID'";

		$rsPrimaryContact = $this->executeQuery($qPrimaryContact);
		if ($this->numRows == 0) {
			return false;
		}
		return mysql_result($rsPrimaryContact,0,0);
	}

	/* add Attributes to Additional Billing Report */
	function addAttrAdditional($studyID,$brID)
	{
		 $qAddSelect = "SELECT count(*) FROM br_attr WHERE `key` = 'STUDY_ID' AND `value` = '$studyID'";
       $rsAddSelect = $this->executeQuery($qAddSelect);
       $vCount = mysql_result($rsAddSelect,0,0);
       $vCount -= 1;
       $qAdditional = "INSERT INTO br_attr (`id`,`key`,`value`) VALUES ('$brID','TCOUNT',$vCount)";
       $rsAdditional = $this->executeQuery($qAdditional);
	}

	function getBrId($studyId)
	{
		$qBrCheck = "SELECT id FROM br_attr WHERE `key` = 'STUDY_ID' AND value = '$studyId'";
      	$rsBrCheck = $this->executeQuery($qBrCheck);
		return mysql_result($rsBrCheck,0,0);
	}

	/* Stall a BR */
	function stallBR($brID)
	{
		$qStallBR = "UPDATE br_main SET br_status = ".BR_STALLED." WHERE br_id = ".$brID;
		$rsStallBR = $this->executeQuery($qStallBR);
		return true;
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


	/* Saving Budget Lines*/
	function saveBudgetLines()
	{
		global $o;
		$save_records = Array();
		$new_records = Array();
		foreach($o as $key=>$value)
		{
			if (ereg("S_budget_item_*", $key)) {
				$save_records[] = substr($key, 14);
			}elseif (ereg("budget_item_*", $key) && $value!=0) {
				$new_records[] = substr($key, 12);
			}
		}
		//find the number of rows we need to loop and insert them (or update them)
		foreach($save_records as $x) {
			//have a flag to mark changed lines so we dont have to waste IO ( though mysql wont write if there is no change in Update
			if ($o["S_budget_item_$x"] != "clear") {
				$budgetSQL =
				"UPDATE br_budget SET
					budget_id = '".$o["S_budget_item_$x"]."',
					quantity = '".$o["S_units_$x"]."',
					A_quantity = '".$o["S_A_units_$x"]."',
					unit_cost = '".$o["S_cost_pu_$x"]."',
					actual_value = '".$o["S_actual_$x"]."',
					proposed_value = '".$o["S_proposed_$x"]."',
					group_name = '".$o["S_group_name_$x"]."'
				WHERE br_line_id = $x";
			}else{
				$budgetSQL =
				"DELETE FROM br_budget WHERE br_line_id = $x";
			}
			$this->executeQuery($budgetSQL);
		}

		foreach($new_records as $x) {
			$budgetSQL =
			"INSERT INTO br_budget
			( 	br_id,
				budget_id,
				quantity,
				A_quantity,
				unit_cost,
				actual_value,
				proposed_value,
				group_name)
			VALUES
			(	'".$o['br_id']."',
				'".$o["budget_item_$x"]."',
				'".$o["units_$x"]."',
				'".$o["A_units_$x"]."',
				'".$o["cost_pu_$x"]."',
				'".$o["actual_$x"]."',
				'".$o["proposed_$x"]."',
				'".$o["group_name_$x"]."')";

			$this->executeQuery($budgetSQL);
		}
		return true;
	}

	function getPendingUser($brID)
	{
		$qFlags = "SELECT * FROM br_main WHERE br_id = $brID";
      $rsFlags = $this->executeQuery($qFlags);
      $rFlags = mysql_fetch_array($rsFlags,MYSQL_ASSOC);

      if ($rFlags[br_type] == TYPE_LICENSE || $rFlags[br_type] == TYPE_TRAINING) {
         return 1; //TRAINING AND LICENSE BR`S DOESNT REQUIRE APPROVAL
      }

      //set the current BR type
      $this->_type = $rFlags['br_type'];

      $qAMflags = "SELECT count(*),flag_AM FROM br_budget WHERE br_id = $brID GROUP BY flag_AM";
      $qAEflags = "SELECT count(*),flag_AE FROM br_budget WHERE br_id = $brID GROUP BY flag_AE";
      $qACCTflags = "SELECT count(*),flag_INV FROM br_budget WHERE br_id = $brID GROUP BY flag_INV";

      $rsAMflags = $this->executeQuery($qAMflags);
      $rsAEflags = $this->executeQuery($qAEflags);
      $rsACCTflags = $this->executeQuery($qACCTflags);

		if ($rFlags[head_am_flag] == 0 || $rFlags[study_am_flag] == 0 ||  mysql_result($rsAMflags,0,1) == 0 || mysql_num_rows($rsAMflags) != 1) {
      	return BR_REVIEW_AM;
      } elseif ($rFlags[head_ae_flag] == 0 || $rFlags[study_ae_flag] == 0 || mysql_result($rsAEflags,0,1) == 0 || mysql_num_rows($rsAEflags) != 1) {
         //we need to see if this BR partner requires AE Approval
         if (brApplication::isAeApprovalRequired($brID)) {
            return BR_REVIEW_AE;
         } else {
      	  return BR_REVIEW_ACCT;
         }
      } elseif ($rFlags[head_acct_flag] == 0 || $rFlags[study_acct_flag] == 0 || mysql_result($rsACCTflags,0,1) == 0 || mysql_num_rows($rsACCTflags) != 1) {
			return BR_REVIEW_ACCT;
		}

		return BR_WAIT;


	}

	//check if the current contact really belongs to the current partner
	function chkContact($br)
	{
		$oDetail = $this->getSingleDetail($br);
		$oDetail['contact_id'] = $this->getKey('br_attr',$br,'BLCONTACT');
		//we need to return false when we dont have any keys
		if (!$oDetail['contact_id']) {
			return false;
		}

		$qry = "SELECT * FROM partner_contact WHERE p_id = ".$oDetail['partner_id']." AND c_id = ".$oDetail['contact_id'];
		$rs = $this->executeQuery($qry);
		if (mysql_num_rows($rs) == 0) {
			return false;
		}

		return true;
	}

	//set default contact for a partner
	function setDefaultContact($br)
	{
		$oDetail = $this->getSingleDetail($br);
		$oDetail['contact_id'] = $this->getDefaultContactByPartner($oDetail['partner_id']);

		if($oDetail['contact_id'] > 0) {
			$this->updateKey('br_attr',$br,'BLCONTACT',$oDetail['contact_id']);
			return true;
		}
		return false;
	}

	//get Default contact for a partner
	function getDefaultContactByPartner($partner)
	{
      //if partner is blank we dont want to do anything
      if ($partner == '') {
        return false;
      }

      $qry = "SELECT primary_contact FROM partners WHERE partner_id = ".$partner;
		$rs = $this->executeQuery($qry);
		if (mysql_num_rows($rs) > 0) {
			return mysql_result($rs,0,primary_contact);
		}
		return false;
	}


	function updateStatus($br)
	{
		$brApp = new brApplication();
		$qry = "UPDATE br_main SET br_status = ".BR_MANUAL_INVOICE.", invoice_date = CURDATE() WHERE br_id = ".$br;
		$rs = $this->executeQuery($qry);

		$oDetail = $brApp->getSingleDetail($br);

		//FIXME: maybe we need to seperate these out, what we are doing here is saving the state what we did above is update the status
		if ($brApp->requireStudy($this->GetType($br))) {
			$this->addKey('br_attr',$br,'PARTNERID',$oDetail['partner_id']);
      		$this->addKey('br_attr',$br,'STUDYNAME',$oDetail['study_name']);
		}

		$this->addKey('br_attr',$br,'GMIAE',$oDetail['ae']['login']);
		$this->addKey('br_attr',$br,'GMIAM',$oDetail['am']['login']);
	}

	/* Send an Alert to the User Who`s Approval is Pending
		if the notify amount is higher than 2, send to Report
		if AE = AM and notify is higher than 2, send to Lowell
		return 1 if approval is complete,

		//FIXME: there is too much logic in here we need to break this down to couple of differnt segments
	*/
	function notifyPendingUser($brID)
	{
	 	$qFlags = "SELECT * FROM br_main WHERE br_id = $brID";
      $rsFlags = $this->executeQuery($qFlags);
      $rFlags = mysql_fetch_array($rsFlags,MYSQL_ASSOC);

      if ($rFlags[br_type] == TYPE_LICENSE || $rFlags[br_type] == TYPE_TRAINING) {
         return 1; //TRAINING AND LICENSE BR`S DOESNT REQUIRE APPROVAL
      }

		$qAMflags = "SELECT count(*),flag_AM FROM br_budget WHERE br_id = $brID GROUP BY flag_AM";
      $qAEflags = "SELECT count(*),flag_AE FROM br_budget WHERE br_id = $brID GROUP BY flag_AE";
		$qACCTflags = "SELECT count(*),flag_INV FROM br_budget WHERE br_id = $brID GROUP BY flag_INV";

      $rsAMflags = $this->executeQuery($qAMflags);
      $rsAEflags = $this->executeQuery($qAEflags);
		$rsACCTflags = $this->executeQuery($qACCTflags);

		if ($rFlags[br_type] == TYPE_SERVICE || $rFlags == TYPE_CREDIT_MEMO_NOSTUDY) {
			$oBrDetails = $this->getAmAeByPartner($rFlags[br_id]);
		} else {
			$oBrDetails = $this->getAmAeByStudy($rFlags[br_id]);
		}

		//** NOTE** too much business logic but have to keep hacking it
		if ($oBrDetails['am_email'] == $oBrDetails['ae_email']) {
			//approve the AM columns
			$qry = "UPDATE br_budget SET flag_AM = 1 WHERE br_id = ".$brID;
			$this->executeQuery($qry);

			$qry = "UPDATE br_main SET head_am_flag = 1, study_am_flag = 1 WHERE br_id = ".$brID;
			$this->executeQuery($qry);
		}

		if ($rFlags[head_am_flag] == 0 || $rFlags[study_am_flag] == 0 || mysql_num_rows($rsAMflags) != 1 || mysql_result($rsAMflags,0,1) == 0) {
			$sentFlag = $this->sendApprovalNotice($oBrDetails,"AM");
			if ($rFlags[alert_count] >= 2) {
				$sentFlagEscalate = $this->escalateApprovalNotice($oBrDetails,"AM");
			}
		} elseif (($rFlags[head_ae_flag] == 0 || $rFlags[study_ae_flag] == 0 || mysql_result($rsAEflags,0,1) == 0 || mysql_num_rows($rsAEflags) != 1)
				&& ($oBrDetails['br_needs_ae_approval'] == 1)) {
         $sentFlag = $this->sendApprovalNotice($oBrDetails,"AE");
		  	if ($rFlags[alert_count] >= 2) {
            $sentFlagEscalate = $this->escalateApprovalNotice($oBrDetails,"AE");
         }
      } elseif ($rFlags[head_acct_flag] == 0 || $rFlags[study_acct_flag] == 0 || mysql_result($rsACCTflags,0,1) == 0 || mysql_num_rows($rsACCTflags) != 1) {
			$sentFlag = $this->sendApprovalNotice($oBrDetails,"ACCT");
			if ($rFlags[alert_count] >= 4) {
				$sentFlagEscalate = $this->escalateApprovalNotice($oBrDetails,"ACCT");
			}
		} else {
         $sentFlag = 1;
      }
		$noticeCount = 0;
		$noticeCount = $rFlags[alert_count] + 1;
		$rsNoticeCount = $this->executeQuery("UPDATE br_main SET alert_count = $noticeCount WHERE br_id = '$rFlags[br_id]'");
		return $sentFlag;
	}

	function sendApprovalNotice($oBrDetails,$sendTo)
	{
      global $smarty;
		//$enctyption = new Encryption();
		if ($sendTo == "AM") {
         $rcptEmail = $oBrDetails["am_email"];
         $rcptName = $oBrDetails["acct_mgr"];
      } elseif ($sendTo == "AE") {
         $rcptEmail = $oBrDetails["ae_email"];
         $rcptName = $oBrDetails["acct_exec"];
      } elseif ($sendTo == "ACCT") {
			$rcptEmail = "tdrinkwater@gmi-mr.com";
			$rcptName = "Tracy Drinkwater";
		}

		$smarty->assign("details", $oBrDetails);
		$smarty->assign("server_name", SERVER_NAME);
		$smarty->assign("rcptName", $rcptName);
		//$smarty->assign("br_id", $oBrDetails['br_id']);
		$mailBody = $smarty->fetch("app/atm/armc/email_approval.tpl");

		$adminEmail = "Client Services <root@hb.gmi-mr.com>";
		SendEmail($adminEmail, array(0 => array("email"=>$rcptEmail, "name"=>$rcptName)), "BR Waiting For Approval", "$mailBody");
		return $rcptName;
	}


	function escalateApprovalNotice($oBrDetails,$sendTo)
	{
	   global $smarty;
		//empty
		//$encryption = new Encryption();
		if ($sendTo == "AM") {
			$oReportsTo = $this->getReportsTo($oBrDetails[am_email]);
         $rcptEmail = $oReportsTo["email"];
         $rcptName = $oReportsTo["name"];
			$actualName = $oBrDetails['acct_mgr'];
      } elseif ($sendTo == "AE") {
			$oReportsTo = $this->getReportsTo($oBrDetails[ae_email]);
         $rcptEmail = $oReportsTo["email"];
         $rcptName = $oReportsTo["name"];
			$actualName = $oBrDetails['acct_exec'];
      } elseif ($sendTo == "ACCT") {
			$oReportsTo = $this->getReportsTo("tdrinkwater@gmi-mr.com");
         $rcptEmail = $oReportsTo["email"];
         $rcptName = $oReportsTo["name"];
			$actualName = "Tracy Drinkwater";
		}

		$smarty->assign("details", $oBrDetails);
		$smarty->assign("server_name", SERVER_NAME);
		$smarty->assign("rcptName", $rcptName);
		$smarty->assign("actualName", $actualName);
		$mailBody = $smarty->fetch("app/atm/armc/email_approval_escalation.tpl");

		$adminEmail = "Client Services <root@hb.gmi-mr.com>";
		SendEmail($adminEmail, array(0 => array("email"=>$rcptEmail, "name"=>$rcptName)), "BR Waiting For Approval", "$mailBody");
		return $rcptName;
	}

	/* */
	function getReportsTo($reportEmail)
	{
		$qReportsTo = "SELECT report.email_address AS email, CONCAT(report.first_name,' ',report.last_name) AS name
FROM user AS emp
LEFT OUTER JOIN reporting_hierarchy AS rh ON rh.reporting_login = emp.login
LEFT OUTER JOIN user AS report ON report.login = rh.report_to_login
WHERE emp.email_address = '$reportEmail'";
		$rsReportsTo = $this->executeQuery($qReportsTo);
		return mysql_fetch_array($rsReportsTo,MYSQL_ASSOC);
	}

	/* */
	function getAmAeByStudy($brID)
	{
		$qAcctMgrs = "SELECT
							study_id.id AS br_id,
                     prt.partner_id AS partner_id,
                     prt.company_name,
                     study.study_name,
                     study.study_id AS study_id,
                     CONCAT(ae.first_name,' ',ae.last_name) AS acct_exec,
                     CONCAT(am.first_name,' ',am.last_name) AS acct_mgr,
                     ae.email_address AS ae_email,
                     am.email_address AS am_email,
                     prt.br_needs_ae_approval
                    FROM
                     br_attr AS study_id
                    LEFT OUTER JOIN study AS study ON
                     study.study_id = study_id.value
                    LEFT OUTER JOIN partners AS prt ON
                     study.partner_id = prt.partner_id
                    LEFT OUTER JOIN user AS am ON
                     prt.proj_mgr = am.login
                    LEFT OUTER JOIN user AS ae ON
                     prt.acct_exec = ae.login
                    WHERE
                     study_id.id = $brID
                    AND study_id.key = 'STUDY_ID'";

		$rsAcctMgrs = $this->executeQuery($qAcctMgrs);
		if (mysql_num_rows($rsAcctMgrs) != 0) {
			return mysql_fetch_array($rsAcctMgrs,MYSQL_ASSOC);
		} else {
			return false;
		}
	}

	/* restrict filter */
	function getStrictFilter()
	{
		#$filterYearMonth = date('Y-m');
		#$filter = " AND br.br_date BETWEEN '$filterYearMonth-01' AND '$filterYearMonth-31'";
		if ($_POST[partnerID] != '') {
			$filter .= " AND prt.partner_id = '$_POST[partnerID]' ";
			$filterDisplay = "Partner ID : ".$_POST['partnerID']."";
		} elseif ($_POST[studyID] != '') {
			$filter .= " AND study.value = '".$_POST[studyID]."'";
			$filterDisplay = "Study ID : ".$_POST['studyID'];
		}
		$_SESSION['searchFilter'] = $filter;
		$_SESSION['searchFilterDisplay'] = $filterDisplay;

		return $filter;
	}

	/* default Query Filter */
	function getSingleDefaultFilter($o)
	{
		$_filter = "AND br.br_status NOT IN (20,5) AND prt.month_end_billing = 0";
      if($o['filter'] == 'invoiced') {
       	$_filter = "AND br.br_status IN (20,5)";
      } elseif($o['filter'] == 'month_end') {
        $_filter = "AND prt.month_end_billing = 1 AND br.br_status NOT IN (20,5)";
      } elseif ($o['filter'] == 'all') {
        $_filter = '';
    	}

		$_filter .= $this->getDateFilter($o);
		return $_filter;
	}

	/* default Merge Filter */
	function getMergeDefaultFilter($o)
	{
   	$_filter = "AND mrg.status NOT IN (20,5) AND prt.month_end_billing = 0";
      if($o['filter'] == 'invoiced') {
      	$_filter = "AND mrg.status IN (20,5)";
      } elseif ($o['filter'] == 'month_end') {
      	$_filter = "AND prt.month_end_billing = 1 AND mrg.status NOT IN (20,5)";
      } if ($o['filter'] == 'all') {
			$_filter = '';
      }

		$_filter .= $this->getDateFilter($o);

		return $_filter;

	}

	function getDateFilter($o)
	{
		if (isset($_SESSION['dateFilter']) && ($o['direction']==0) &&($o['filter_period_changed']==0)) {
			return $_SESSION['dateFilter'];
		}else{

			if ($o['filter_period'] == '' || $o['filter_period'] == 'month') {
				$oTime = $this->getMonthFilter($o);
			} elseif ($o['filter_period'] == 'qator') {
				$oTime = $this->getQuarterFilter($o);
			} elseif ($o['filter_period'] == 'year') {
				$oTime = $this->getYearFilter($o);
			}

			$_SESSION['dateFilterDisplay'] = $oTime['begTime']." - ".$oTime['endTime'];
			//$this->_dateString = $oTime['begTime']." - ".$oTime['endTime'];
			$_SESSION['dateFilter'] = " AND (br.br_date BETWEEN '".$oTime['begTime']."' AND '".$oTime['endTime']."' OR br.br_date = '0000-00-00')";
			//$this->_dateFilter =  " AND (br.br_date BETWEEN '".$oTime['begTime']."' AND '".$oTime['endTime']."' OR br.br_date = '0000-00-00')";

			return $_SESSION['dateFilter'];
		}

	}

	function getMonthFilter($o)
	{
		if (!isset($_SESSION[currentMonth])) {
			$year = date("Y");
			$month = date("m");
			$_SESSION[currentMonth] = $month;
			$_SESSION[currentYear] = $year;
		} else {
			$month = $_SESSION[currentMonth];
			$year = $_SESSION[currentYear];
		}

		//FIXME: need better class design here
		if ($o['direction'] == -1) {
			$month--;
			if ($month == 0) {
				$month = 12;
				$year--;
			}
		} elseif ($o['direction'] == 1) {
			$month++;
			if ($month == 13) {
				$month = 1;
				$year++;
			}
		}

		$_oTime['begTime'] = $year."-".$month."-01";
		$_oTime['endTime'] = $year."-".$month."-".GetLastDateOfMonth(mktime(0,0,0,$month,1,$year));

		$_SESSION[currentYear] = $year;
		$_SESSION[currentMonth] = $month;

		return $_oTime;

	}

	//move to diffeernt class
	function getCurrentQuarter()
	{
		$_month = date("m");
      $_validator1 = $_month / 3;
      $_validator2 = $_month % 3;
      if ($_validator2 > 0) {
      	$_quator = $_validator1+1;
      } else {
         $_quator = $_validator1;
      }
		return round($_quator);
	}

	function getQuarterFilter($o)
	{
		if(!isset($_SESSION[currentQuarter])) {
			$quarter = $this->getCurrentQuarter();
			$year = date("Y");
		} else {
			$quarter = $_SESSION[currentQuarter];
			$year = $_SESSION[currentYear];
		}

	  //FIXME: need better class design here
      if ($o['direction'] == -1) {
         $quarter--;
         if ($quarter == 0) {
            $quarter = 4;
            $year--;
         }
      } elseif ($o['direction'] == 1) {
         $quarter++;
         if ($quarter == 5) {
            $quarter = 1;
            $year++;
         }
      }

		global $_Q;

		$_oTime['begTime'] = $year."-".$_Q[$quarter]['begin']."-01";
		$_oTime['endTime'] = $year."-".$_Q[$quarter]['end']."-".GetLastDateOfMonth(mktime(0,0,0,$_Q[$quarter]['end'],1,$year));

		$_SESSION[currentQuarter] = $quarter;
		$_SESSION[currentYear] = $year;

		return $_oTime;


	}

	function getYearFilter($o)
	{
		if(!isset($_SESSION[currentYear])) {
			$year = date("Y");
		} else {
			$year = $_SESSION[currentYear];
		}

	 	if ($o['direction'] == -1) {
         $year--;
      } elseif ($o['direction'] == 1) {
         $year++;
      }

		$_SESSION[currentYear] = $year;

		$_oTime['begTime'] = $year."-01-01";
		$_oTime['endTime'] = $year."-12-31";

		return $_oTime;

	}

	/* Return the search Filter */
	function getSearchFilter()
	{
		if (isset($_SESSION['searchFilter'])) {
			return $_SESSION['searchFilter'];
		}else{
			$filter = "";
			$filterDisplay = "";
			if ($_POST['search_by'] == "partner") {
				$filter .= " AND prt.partner_id = '".$_POST['partnerID']."'";
				$filterDisplay .= "Partner ID : ".$_POST['partnerID'];
			}else{
				$filter .= "AND study.value = '".$_POST['studyID']."'";
				$filterDisplay .= "Study ID : ".$_POST['studyID'];
			}

			if ($_POST['beginDate'] != "") {
				if ($_POST['endDate'] != "") {
					$filter .= " AND ((br.invoice_date BETWEEN '".$_POST['beginDate']."' AND '".$_POST['endDate']."') OR (br.invoice_date = '0000-00-00'))";
					$filterDisplay .= "<br>Invoice Date between ".$_POST['beginDate']." and ".$_POST['endDate'];
				}else{
					$filter .= " AND ((br.invoice_date >= '".$_POST['beginDate']."') OR (br.invoice_date = '0000-00-00'))";
					$filterDisplay .= "<br>Invoice Date after ".$_POST['beginDate'];
				}
			}else{
				if ($_POST['endDate']) {
						$filter .= " AND ((br.invoice_date <= '".$_POST['endDate']."') OR (br.invoice_date = '0000-00-00'))";
					$filterDisplay .= "<br>Invoice Date before ".$_POST['endDate'];
				}
			}

			$_SESSION['searchFilter'] = $filter;
			$_SESSION['searchFilterDisplay'] = $filterDisplay;
			return $filter;
		}
	}

	/* delete a Br record */
	function deleteRecord($brID)
	{
		$qDeleteMain = "DELETE FROM br_main WHERE br_id = $brID";
		$qDeleteSub = "DELETE FROM br_attr WHERE `id` = $brID AND `key` REGEXP '^[^M][^_]'";
		$rsDeleteMain = $this->executeQuery($qDeleteMain);
		$rsDeleteSub = $this->executeQuery($qDeleteSub);
		return true;
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


	function getReportFilter($userID)
	{
//    $qReportFilter = "SELECT reporting_login as login FROM reporting_hierarchy rh
//WHERE rh.report_to_login = '$userID'";
//
//		$rsReportFilter = $this->executeQuery($qReportFilter);


      $reportFilter = "(".PrepareInString(GetReportees($userID, 0)).")";

		return $reportFilter;
	}

	function printStallButton()
	{
		$btnStall =  "<input type='button' value='Stall' onclick=\"if (confirm('Are you sure , you want to Stall ?')) { ";
		$btnStall .= "document.all('submit_action').value = 'STALL'; this.form.submit(); } else { alert('Stall Aborted'); }\">&nbsp;&nbsp;";
		return $btnStall;
	}

//	function printCancelButton()
//	{
//		$btnDelete =  "<input type='button' value='Cancel' onclick=\"if (confirm('Are you sure , you want to cancel ?')) { ";
//		$btnDelete .= "document.all('submit_action').value = 'DEL'; this.form.submit(); } else { alert('Cancel Aborted'); }\">&nbsp;&nbsp;";
//		echo $btnDelete;
//		return true;
//	}

	function printDeleteButton()
	{
		$btnDelete =  "<input type='button' value='Delete' onclick=\"if (confirm('Are you sure , you want to Delete This BR ?')) { ";
		$btnDelete .= "document.all('submit_action').value = 'DEL'; this.form.submit(); } else { alert('Delete Aborted'); }\">&nbsp;&nbsp;";
		return $btnDelete;
	}

	function getTitle($brType)
	{
		if ($brType == TYPE_CREDIT_MEMO || $brType == TYPE_CREDIT_MEMO_NOSTUDY) {
			return "Credit Memo";
		} elseif ($brType == TYPE_RETAINER) {
			return "Retainer";
		}
		return "Billing Report";
	}

	/* */
	function getAmAeByPartner($brID)
	{
 		$qAcctMgrs = "SELECT
							par_id.id AS br_id,
                     par_id.value AS partner_id,
                     prt.company_name,
                     study_id.value AS study_id,
                     study_name.value AS study_name,
                     CONCAT(ae.first_name,' ',ae.last_name) AS acct_exec,
                     CONCAT(am.first_name,' ',am.last_name) AS acct_mgr,
                     ae.email_address AS ae_email,
                     am.email_address AS am_email,
                     prt.br_needs_ae_approval
                    FROM
                     br_attr AS par_id
                    LEFT OUTER JOIN partners AS prt ON
                     prt.partner_id = par_id.value
                    LEFT OUTER JOIN br_attr AS study_id ON
                      study_id.id = par_id.id
                    AND study_id.key = 'STUDY_ID'
                    LEFT OUTER JOIN br_attr AS study_name ON
                     study_name.id = par_id.id
                    AND study_name.key = 'STUDYNAME'
                    LEFT OUTER JOIN user AS am ON
                     prt.proj_mgr = am.login
                    LEFT OUTER JOIN user AS ae ON
                     prt.acct_exec = ae.login
                    WHERE par_id.id = $brID
                    AND par_id.key = 'PARTNERID'";

		$rsAcctMgrs = $this->executeQuery($qAcctMgrs);
		if (mysql_num_rows($rsAcctMgrs) != 0) {
			return mysql_fetch_array($rsAcctMgrs,MYSQL_ASSOC);
		} else {
			return false;
		}

	}

	function mergeRecords($o, $merge_type)
	{
		if ($merge_type == SINGLE_MERGE) {
   		//$br_ids = $_POST[br_que];
   		//$br_array = split("/",$br_ids);
	   		$q_merge = "INSERT INTO br_merge (`m_by`,`m_date`) VALUES ('$_SESSION[admin_id]',NOW())";
   			$rst_merge = $this->executeQuery($q_merge);
	   		$m_id = $this->lastID;
   			for ($b=0;$b<count($o['brcheck']); $b++) {
      			$q_setid = "UPDATE br_main SET merge_id = '$m_id' WHERE br_id = '".$o['brcheck'][$b]."'";
      			$rst_setid = $this->executeQuery($q_setid);
   			}
			return $m_id;
		}elseif ($merge_type == MERGED_MERGE) {
   		/* remerge merged BR */
   			//$br_ids = $_POST[mbr_que];
   			//$br_array = split("/",$br_ids);
   			$master_mbr = array_shift($o['brcheck']); //make the first one our main 1

   			for ($b=0;$b< count($o['brcheck']); $b++) {
      			if ($o['brcheck'][$b] != '') {
         			$q_setid = "UPDATE br_main SET merge_id = '$master_mbr' WHERE merge_id = '".$o['brcheck'][$b]."'";
         			$rst_setid = $this->executeQuery($q_setid);
         			$qDel = "DELETE FROM br_merge WHERE m_id = '".$o['brcheck'][$b]."'";
         			$rsDel = $this->executeQuery($qDel);
      			}
   			}
			return $master_mbr;
		}
	}

	/**
	* GetMergedBR()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Nov 09 11:59:23 PST 2005
	*/
	function GetMergedBR($qFilter, $qOrderBy)
	{
		$q_merge =
		"SELECT mrg.m_id,
			DATE_FORMAT(mrg.m_date,'%Y-%m-%d') AS m_date,
			GROUP_CONCAT(DISTINCT study.value SEPARATOR ' ') AS studies,
			prt.company_name,
			prt.partner_id,
			SUM(budget.actual_value) AS total,
			mrg.status,
			br.*,
			t_acct_exec.last_name AS ae,
			t_acct_mgr.last_name AS am,
			studyname.value as studyname
			FROM br_merge AS mrg
			LEFT OUTER JOIN br_main AS br ON br.merge_id = mrg.m_id
			LEFT OUTER JOIN br_attr AS study ON study.id = br.br_id AND study.key = 'STUDY_ID'
			LEFT OUTER JOIN br_attr AS studyname ON studyname.id = mrg.m_id AND studyname.key = 'M_PRJNAME'
			LEFT OUTER JOIN study AS prj ON prj.study_id = study.value
			LEFT OUTER JOIN partners AS prt ON prj.partner_id = prt.partner_id
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			LEFT OUTER JOIN user AS t_acct_mgr ON prt.proj_mgr = t_acct_mgr.login
			LEFT OUTER JOIN user AS t_acct_exec ON prt.acct_exec = t_acct_exec.login
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			WHERE br.br_type IN (0,1,2) AND br.pre_flag = 0 $qFilter
			GROUP BY br.merge_id
			UNION ALL
		SELECT mrg.m_id,
			DATE_FORMAT(mrg.m_date,'%Y-%m-%d') AS m_date,
			GROUP_CONCAT(DISTINCT study.value SEPARATOR ' ') AS studies,
			prt.company_name,
			prt.partner_id,
			SUM(budget.actual_value) AS total,
			mrg.status,
			br.*,
			t_acct_exec.last_name AS ae,
			t_acct_mgr.last_name AS am,
			studyname.value as studyname
			FROM br_merge AS mrg
			LEFT OUTER JOIN br_main AS br ON br.merge_id = mrg.m_id
			LEFT OUTER JOIN br_attr AS study ON study.id = br.br_id AND study.key = 'STUDY_ID'
			LEFT OUTER JOIN br_attr AS studyname ON studyname.id = mrg.m_id AND studyname.key = 'M_PRJNAME'
			LEFT OUTER JOIN br_attr AS partner_id ON partner_id.id = br.br_id AND partner_id.key = 'PARTNERID'
			LEFT OUTER JOIN partners AS prt ON partner_id.value = prt.partner_id
			LEFT OUTER JOIN user AS t_acct_mgr ON prt.proj_mgr = t_acct_mgr.login
			LEFT OUTER JOIN user AS t_acct_exec ON prt.acct_exec = t_acct_exec.login
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			WHERE br.br_type NOT IN (0,1,2) AND br.pre_flag = 0 $qFilter
			GROUP BY br.merge_id
		$qOrderBy";

		return $this->executeQuery($q_merge);
	}

	/**
	* GetPendingMergedBRs()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Nov 21 14:42:56 PST 2005
	*/
	function GetPendingMergedBRs($qOrderBy)
	{
		global $userRights, $o;

		$qFilter = $this->GetUserFilter();

		if (isset($_SESSION['searchFilter']) || $userRights->userSecCheck('BRSTRICT') || ($_POST['frmName']=='frmSearch')) {
			$qFilter .= $this->getSearchFilter();
		}else{
			$qFilter .= $this->getDateFilter($o);
		}

		$this->debugPrint("Filter : ".$qFilter);
		if ($o['direction']!=0) $o['direction']=0;
		$q_merge =
		"SELECT mrg.m_id,
			DATE_FORMAT(mrg.m_date,'%Y-%m-%d') AS m_date,
			GROUP_CONCAT(DISTINCT study.value SEPARATOR ' ') AS studies,
			prt.company_name,
			prt.partner_id,
			SUM(budget.actual_value) AS total,
			mrg.status,
			br.*,
			t_acct_exec.last_name AS ae,
			t_acct_mgr.last_name AS am,
			studyname.value as studyname
			FROM br_merge AS mrg
			LEFT OUTER JOIN br_main AS br ON br.merge_id = mrg.m_id
			LEFT OUTER JOIN br_attr AS study ON study.id = br.br_id AND study.key = 'STUDY_ID'
			LEFT OUTER JOIN br_attr AS studyname ON studyname.id = mrg.m_id AND studyname.key = 'M_PRJNAME'
			LEFT OUTER JOIN study AS prj ON prj.study_id = study.value
			LEFT OUTER JOIN partners AS prt ON prj.partner_id = prt.partner_id
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			LEFT OUTER JOIN user AS t_acct_mgr ON prt.proj_mgr = t_acct_mgr.login
			LEFT OUTER JOIN user AS t_acct_exec ON prt.acct_exec = t_acct_exec.login
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			WHERE br.br_type IN (0,1,2) AND br.pre_flag = 0 AND mrg.status NOT IN (20,5) AND prt.month_end_billing = 0 $qFilter
			GROUP BY br.merge_id
			UNION ALL
		SELECT mrg.m_id,
			DATE_FORMAT(mrg.m_date,'%Y-%m-%d') AS m_date,
			GROUP_CONCAT(DISTINCT study.value SEPARATOR ' ') AS studies,
			prt.company_name,
			prt.partner_id,
			SUM(budget.actual_value) AS total,
			mrg.status,
			br.*,
			t_acct_exec.last_name AS ae,
			t_acct_mgr.last_name AS am,
			studyname.value as studyname
			FROM br_merge AS mrg
			LEFT OUTER JOIN br_main AS br ON br.merge_id = mrg.m_id
			LEFT OUTER JOIN br_attr AS study ON study.id = br.br_id AND study.key = 'STUDY_ID'
			LEFT OUTER JOIN br_attr AS studyname ON studyname.id = mrg.m_id AND studyname.key = 'M_PRJNAME'
			LEFT OUTER JOIN br_attr AS partner_id ON partner_id.id = br.br_id AND partner_id.key = 'PARTNERID'
			LEFT OUTER JOIN partners AS prt ON partner_id.value = prt.partner_id
			LEFT OUTER JOIN user AS t_acct_mgr ON prt.proj_mgr = t_acct_mgr.login
			LEFT OUTER JOIN user AS t_acct_exec ON prt.acct_exec = t_acct_exec.login
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			WHERE br.br_type NOT IN (0,1,2) AND br.pre_flag = 0 AND mrg.status NOT IN (20,5) AND prt.month_end_billing = 0 $qFilter
			GROUP BY br.merge_id
		$qOrderBy";

		return $this->executeQuery($q_merge);
	}

	/**
	* GetMonthEndMergedBRs()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Nov 21 15:25:56 PST 2005
	*/
	function GetMonthEndMergedBRs($qOrderBy)
	{
		global $userRights, $o;
		$qFilter = $this->GetUserFilter();

		if (isset($_SESSION['searchFilter']) || $userRights->userSecCheck('BRSTRICT') || ($_POST['frmName']=='frmSearch')) {
			$qFilter .= $this->getSearchFilter();
		}else{
			$qFilter .= $this->getDateFilter($o);
		}

		$this->debugPrint("Filter : ".$qFilter);
		if ($o['direction']!=0) $o['direction']=0;
		$q_merge =
		"SELECT mrg.m_id,
			DATE_FORMAT(mrg.m_date,'%Y-%m-%d') AS m_date,
			GROUP_CONCAT(DISTINCT study.value SEPARATOR ' ') AS studies,
			prt.company_name,
			prt.partner_id,
			SUM(budget.actual_value) AS total,
			mrg.status,
			br.*,
			t_acct_exec.last_name AS ae,
			t_acct_mgr.last_name AS am,
			studyname.value as studyname
			FROM br_merge AS mrg
			LEFT OUTER JOIN br_main AS br ON br.merge_id = mrg.m_id
			LEFT OUTER JOIN br_attr AS study ON study.id = br.br_id AND study.key = 'STUDY_ID'
			LEFT OUTER JOIN br_attr AS studyname ON studyname.id = mrg.m_id AND studyname.key = 'M_PRJNAME'
			LEFT OUTER JOIN study AS prj ON prj.study_id = study.value
			LEFT OUTER JOIN partners AS prt ON prj.partner_id = prt.partner_id
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			LEFT OUTER JOIN user AS t_acct_mgr ON prt.proj_mgr = t_acct_mgr.login
			LEFT OUTER JOIN user AS t_acct_exec ON prt.acct_exec = t_acct_exec.login
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			WHERE br.br_type IN (0,1,2) AND br.pre_flag = 0 AND prt.month_end_billing = 1 AND mrg.status NOT IN (20,5) $qFilter
			GROUP BY br.merge_id
			UNION ALL
		SELECT mrg.m_id,
			DATE_FORMAT(mrg.m_date,'%Y-%m-%d') AS m_date,
			GROUP_CONCAT(DISTINCT study.value SEPARATOR ' ') AS studies,
			prt.company_name,
			prt.partner_id,
			SUM(budget.actual_value) AS total,
			mrg.status,
			br.*,
			t_acct_exec.last_name AS ae,
			t_acct_mgr.last_name AS am,
			studyname.value as studyname
			FROM br_merge AS mrg
			LEFT OUTER JOIN br_main AS br ON br.merge_id = mrg.m_id
			LEFT OUTER JOIN br_attr AS study ON study.id = br.br_id AND study.key = 'STUDY_ID'
			LEFT OUTER JOIN br_attr AS studyname ON studyname.id = mrg.m_id AND studyname.key = 'M_PRJNAME'
			LEFT OUTER JOIN br_attr AS partner_id ON partner_id.id = br.br_id AND partner_id.key = 'PARTNERID'
			LEFT OUTER JOIN partners AS prt ON partner_id.value = prt.partner_id
			LEFT OUTER JOIN user AS t_acct_mgr ON prt.proj_mgr = t_acct_mgr.login
			LEFT OUTER JOIN user AS t_acct_exec ON prt.acct_exec = t_acct_exec.login
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			WHERE br.br_type NOT IN (0,1,2) AND br.pre_flag = 0 AND prt.month_end_billing = 1 AND mrg.status NOT IN (20,5) $qFilter
			GROUP BY br.merge_id
		$qOrderBy";

		return $this->executeQuery($q_merge);
	}

	/**
	* GetInvoicedMergedBRs()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Nov 21 15:25:56 PST 2005
	*/
	function GetInvoicedMergedBRs($qOrderBy)
	{
		global $userRights, $o;

		$qFilter = $this->GetUserFilter();

		if (isset($_SESSION['searchFilter']) || $userRights->userSecCheck('BRSTRICT') || ($_POST['frmName']=='frmSearch')) {
			$qFilter .= $this->getSearchFilter();
		}else{
			$qFilter .= $this->getDateFilter($o);
		}

		$this->debugPrint("Filter : ".$qFilter);
		if ($o['direction']!=0) $o['direction']=0;
		$q_merge =
		"SELECT mrg.m_id,
			DATE_FORMAT(mrg.m_date,'%Y-%m-%d') AS m_date,
			GROUP_CONCAT(DISTINCT study.value SEPARATOR ' ') AS studies,
			prt.company_name,
			prt.partner_id,
			SUM(budget.actual_value) AS total,
			mrg.status,
			br.*,
			ae_name.last_name AS ae,
			am_name.last_name AS am,
			studyname.value as studyname
			FROM br_merge AS mrg
			LEFT OUTER JOIN br_main AS br ON br.merge_id = mrg.m_id
			LEFT OUTER JOIN br_attr AS study ON study.id = br.br_id AND study.key = 'STUDY_ID'
			LEFT OUTER JOIN br_attr AS studyname ON studyname.id = mrg.m_id AND studyname.key = 'M_PRJNAME'
			LEFT OUTER JOIN br_attr AS am_login ON am_login.id = mrg.m_id AND am_login.key = 'M_GMIAM'
			LEFT OUTER JOIN user AS am_name ON am_name.login = am_login.value
			LEFT OUTER JOIN br_attr AS ae_login ON ae_login.id = mrg.m_id AND ae_login.key = 'M_GMIAE'
			LEFT OUTER JOIN user AS ae_name ON ae_name.login = ae_login.value
			LEFT OUTER JOIN study AS prj ON prj.study_id = study.value
			LEFT OUTER JOIN partners AS prt ON prj.partner_id = prt.partner_id
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			WHERE br.br_type IN (0,1,2) AND br.pre_flag = 0 AND mrg.status IN (20,5) $qFilter
			GROUP BY br.merge_id
			UNION ALL
		SELECT mrg.m_id,
			DATE_FORMAT(mrg.m_date,'%Y-%m-%d') AS m_date,
			GROUP_CONCAT(DISTINCT study.value SEPARATOR ' ') AS studies,
			prt.company_name,
			prt.partner_id,
			SUM(budget.actual_value) AS total,
			mrg.status,
			br.*,
			ae_name.last_name AS ae,
			am_name.last_name AS am,
			studyname.value as studyname
			FROM br_merge AS mrg
			LEFT OUTER JOIN br_main AS br ON br.merge_id = mrg.m_id
			LEFT OUTER JOIN br_attr AS study ON study.id = br.br_id AND study.key = 'STUDY_ID'
			LEFT OUTER JOIN br_attr AS studyname ON studyname.id = mrg.m_id AND studyname.key = 'M_PRJNAME'
			LEFT OUTER JOIN br_attr AS partner_id ON partner_id.id = br.br_id AND partner_id.key = 'PARTNERID'
			LEFT OUTER JOIN br_attr AS am_login ON am_login.id = mrg.m_id AND am_login.key = 'M_GMIAM'
			LEFT OUTER JOIN user AS am_name ON am_name.login = am_login.value
			LEFT OUTER JOIN br_attr AS ae_login ON ae_login.id = mrg.m_id AND ae_login.key = 'M_GMIAE'
			LEFT OUTER JOIN user AS ae_name ON ae_name.login = ae_login.value
			LEFT OUTER JOIN partners AS prt ON partner_id.value = prt.partner_id
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			WHERE br.br_type NOT IN (0,1,2) AND br.pre_flag = 0 AND mrg.status IN (20,5) $qFilter
			GROUP BY br.merge_id
		$qOrderBy";

		return $this->executeQuery($q_merge);
	}

	/**
	* GetAllMergedBRs()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Nov 21 15:25:56 PST 2005
	*/
	function GetAllMergedBRs($qOrderBy)
	{
		global $userRights, $o;

		$qFilter = $this->GetUserFilter();

		if (isset($_SESSION['searchFilter']) || $userRights->userSecCheck('BRSTRICT') || ($_POST['frmName']=='frmSearch')) {
			$qFilter .= $this->getSearchFilter();
		}else{
			$qFilter .= $this->getDateFilter($o);
		}

		$this->debugPrint("Filter : ".$qFilter);
		if ($o['direction']!=0) $o['direction']=0;
		$q_merge =
		"SELECT mrg.m_id,
			DATE_FORMAT(mrg.m_date,'%Y-%m-%d') AS m_date,
			GROUP_CONCAT(DISTINCT study.value SEPARATOR ' ') AS studies,
			prt.company_name,
			prt.partner_id,
			SUM(budget.actual_value) AS total,
			mrg.status,
			br.*,
			ae_name.last_name AS ae,
			am_name.last_name AS am,
			studyname.value as studyname
			FROM br_merge AS mrg
			LEFT OUTER JOIN br_main AS br ON br.merge_id = mrg.m_id
			LEFT OUTER JOIN br_attr AS study ON study.id = br.br_id AND study.key = 'STUDY_ID'
			LEFT OUTER JOIN br_attr AS studyname ON studyname.id = mrg.m_id AND studyname.key = 'M_PRJNAME'
			LEFT OUTER JOIN study AS prj ON prj.study_id = study.value
			LEFT OUTER JOIN partners AS prt ON prj.partner_id = prt.partner_id
			LEFT OUTER JOIN user AS am_name ON am_name.login = prt.proj_mgr
			LEFT OUTER JOIN user AS ae_name ON ae_name.login = prt.acct_exec
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			WHERE br.br_type IN (0,1,2) AND br.pre_flag = 0 $qFilter
			GROUP BY br.merge_id
			UNION ALL
		SELECT mrg.m_id,
			DATE_FORMAT(mrg.m_date,'%Y-%m-%d') AS m_date,
			GROUP_CONCAT(DISTINCT study.value SEPARATOR ' ') AS studies,
			prt.company_name,
			prt.partner_id,
			SUM(budget.actual_value) AS total,
			mrg.status,
			br.*,
			ae_name.last_name AS ae,
			am_name.last_name AS am,
			studyname.value as studyname
			FROM br_merge AS mrg
			LEFT OUTER JOIN br_main AS br ON br.merge_id = mrg.m_id
			LEFT OUTER JOIN br_attr AS study ON study.id = br.br_id AND study.key = 'STUDY_ID'
			LEFT OUTER JOIN br_attr AS studyname ON studyname.id = mrg.m_id AND studyname.key = 'M_PRJNAME'
			LEFT OUTER JOIN br_attr AS partner_id ON partner_id.id = br.br_id AND partner_id.key = 'PARTNERID'
			LEFT OUTER JOIN partners AS prt ON partner_id.value = prt.partner_id
			LEFT OUTER JOIN user AS am_name ON am_name.login = prt.proj_mgr
			LEFT OUTER JOIN user AS ae_name ON ae_name.login = prt.acct_exec
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			WHERE br.br_type NOT IN (0,1,2) AND br.pre_flag = 0 $qFilter
			GROUP BY br.merge_id
		$qOrderBy";

		return $this->executeQuery($q_merge);
	}

	/**
	* GetPendingBRs()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Nov 21 14:53:09 PST 2005
	*/
	function GetPendingBRs($qOrderBy)
	{
		global $userRights, $o;

		$qFilter = $this->GetUserFilter();

		if (isset($_SESSION['searchFilter']) || $userRights->userSecCheck('BRSTRICT') || ($_POST['frmName']=='frmSearch')) {
			$qFilter .= $this->getSearchFilter();
		}else{
			$qFilter .= $this->getDateFilter($o);
		}

		$this->debugPrint("Filter : ".$qFilter);
		if ($o['direction']!=0) $o['direction']=0;

		$brSQL =
		"SELECT
			study.value as study_id,
			prt.company_name,
			br.*,
			ae.last_name AS acct_exec,
			am.last_name AS acct_mgr,
			SUM(budget.actual_value) AS total,
			SUM(budget.actual_value) AS total_num,
			prt.partner_id,
			tcount.value AS tracker_id,
			prj.study_name as studyname
			FROM br_main AS br
			LEFT OUTER JOIN br_attr AS study ON study.key = 'STUDY_ID' AND study.id = br.br_id
			LEFT OUTER JOIN br_attr AS tcount ON tcount.key = 'TCOUNT' AND tcount.id = br.br_id
			LEFT OUTER JOIN study AS prj ON prj.study_id = study.value
			LEFT OUTER JOIN partners AS prt ON prj.partner_id = prt.partner_id
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			LEFT OUTER JOIN user AS am ON prt.proj_mgr = am.login
			LEFT OUTER JOIN user AS ae ON prt.acct_exec = ae.login
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			WHERE br.merge_id = 0 AND br.pre_flag = 0 AND br.br_type IN (0,1,2) AND br.br_status NOT IN (20,5) AND prt.month_end_billing = 0 $qFilter
			GROUP BY br.br_id
			UNION ALL
		SELECT
			study.value as study_id,
			prt.company_name,
			br.*,
			ae.last_name AS acct_exec,
			am.last_name AS acct_mgr,
			SUM(budget.actual_value) AS total,
			SUM(budget.actual_value) AS total_num,
			prt.partner_id,
			tcount.value AS tracker_id,
			studyname.value as studyname
			FROM br_main AS br
			LEFT OUTER JOIN br_attr AS study ON study.key = 'STUDY_ID' AND study.id = br.br_id
			LEFT OUTER JOIN br_attr AS studyname ON studyname.key = 'STUDYNAME' AND studyname.id = br.br_id
			LEFT OUTER JOIN br_attr AS tcount ON tcount.key = 'TCOUNT' AND tcount.id = br.br_id
			LEFT OUTER JOIN br_attr AS prt_id ON prt_id.id = br.br_id AND prt_id.key = 'PARTNERID'
			LEFT OUTER JOIN partners AS prt ON prt_id.value = prt.partner_id
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			LEFT OUTER JOIN user AS am ON prt.proj_mgr = am.login
			LEFT OUTER JOIN user AS ae ON prt.acct_exec = ae.login
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			WHERE br.merge_id = 0 AND br.pre_flag = 0 AND br.br_type NOT IN (0,1,2) AND br.br_status NOT IN (20,5) AND prt.month_end_billing = 0 $qFilter
			GROUP BY br.br_id
		$qOrderBy";

		return $this->executeQuery($brSQL);
	}

	/**
	* GetMonthEndBRs()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Nov 21 15:27:09 PST 2005
	*/
	function GetMonthEndBRs($qOrderBy)
	{
		global $userRights, $o;

		$qFilter = $this->GetUserFilter();

		if (isset($_SESSION['searchFilter']) || $userRights->userSecCheck('BRSTRICT') || ($_POST['frmName']=='frmSearch')) {
			$qFilter .= $this->getSearchFilter();
		}else{
			$qFilter .= $this->getDateFilter($o);
		}

		if ($o['direction']!=0) $o['direction']=0;

		$this->debugPrint("Filter : ".$qFilter);
		$brSQL =
		"SELECT
			study.value as study_id,
			prt.company_name,
			br.*,
			ae.last_name AS acct_exec,
			am.last_name AS acct_mgr,
			SUM(budget.actual_value) AS total,
			SUM(budget.actual_value) AS total_num,
			prt.partner_id,
			tcount.value AS tracker_id,
			prj.study_name as studyname
			FROM br_main AS br
			LEFT OUTER JOIN br_attr AS study ON study.key = 'STUDY_ID' AND study.id = br.br_id
			LEFT OUTER JOIN br_attr AS tcount ON tcount.key = 'TCOUNT' AND tcount.id = br.br_id
			LEFT OUTER JOIN study AS prj ON prj.study_id = study.value
			LEFT OUTER JOIN partners AS prt ON prj.partner_id = prt.partner_id
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			LEFT OUTER JOIN user AS am ON prt.proj_mgr = am.login
			LEFT OUTER JOIN user AS ae ON prt.acct_exec = ae.login
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			WHERE br.merge_id = 0 AND br.pre_flag = 0 AND br.br_type IN (0,1,2) AND br.br_status NOT IN (20,5) AND prt.month_end_billing = 1 $qFilter
			GROUP BY br.br_id
			UNION ALL
		SELECT
			study.value as study_id,
			prt.company_name,
			br.*,
			ae.last_name AS acct_exec,
			am.last_name AS acct_mgr,
			SUM(budget.actual_value) AS total,
			SUM(budget.actual_value) AS total_num,
			prt.partner_id,
			tcount.value AS tracker_id,
			studyname.value as studyname
			FROM br_main AS br
			LEFT OUTER JOIN br_attr AS study ON study.key = 'STUDY_ID' AND study.id = br.br_id
			LEFT OUTER JOIN br_attr AS studyname ON studyname.key = 'STUDYNAME' AND studyname.id = br.br_id
			LEFT OUTER JOIN br_attr AS tcount ON tcount.key = 'TCOUNT' AND tcount.id = br.br_id
			LEFT OUTER JOIN br_attr AS prt_id ON prt_id.id = br.br_id AND prt_id.key = 'PARTNERID'
			LEFT OUTER JOIN partners AS prt ON prt_id.value = prt.partner_id
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			LEFT OUTER JOIN user AS am ON prt.proj_mgr = am.login
			LEFT OUTER JOIN user AS ae ON prt.acct_exec = ae.login
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			WHERE br.merge_id = 0 AND br.pre_flag = 0 AND br.br_type NOT IN (0,1,2) AND br.br_status NOT IN (20,5) AND prt.month_end_billing = 1 $qFilter
			GROUP BY br.br_id
		$qOrderBy";

		return $this->executeQuery($brSQL);
	}

	/**
	* GetInvoicedBRs()
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Nov 21 15:27:09 PST 2005
	*/
	function GetInvoicedBRs($qOrderBy)
	{
		global $userRights, $o;

		$qFilter = $this->GetUserFilter();

		if (isset($_SESSION['searchFilter']) || $userRights->userSecCheck('BRSTRICT') || ($_POST['frmName']=='frmSearch')) {
			$qFilter .= $this->getSearchFilter();
		}else{
			$qFilter .= $this->getDateFilter($o);
		}

		$this->debugPrint("Filter : ".$qFilter);
		if ($o['direction']!=0) $o['direction']=0;

		$brSQL =
		"SELECT
			study.value as study_id,
			prt.company_name,
			br.*,
			ae.last_name AS acct_exec,
			am.last_name AS acct_mgr,
			SUM(budget.actual_value) AS total,
			SUM(budget.actual_value) AS total_num,
			prt.partner_id,
			tcount.value AS tracker_id,
			prj.study_name as studyname
			FROM br_main AS br
			LEFT OUTER JOIN br_attr AS study ON study.key = 'STUDY_ID' AND study.id = br.br_id
			LEFT OUTER JOIN br_attr AS tcount ON tcount.key = 'TCOUNT' AND tcount.id = br.br_id
			LEFT OUTER JOIN study AS prj ON prj.study_id = study.value
			LEFT OUTER JOIN partners AS prt ON prj.partner_id = prt.partner_id
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			LEFT OUTER JOIN br_attr AS ae_login ON ae_login.id = br.br_id AND ae_login.key = 'GMIAE'
			LEFT OUTER JOIN user AS ae ON ae.login = ae_login.value
			LEFT OUTER JOIN br_attr AS am_login ON am_login.id = br.br_id AND am_login.key = 'GMIAM'
			LEFT OUTER JOIN user AS am ON am.login = am_login.value
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			WHERE br.merge_id = 0 AND br.pre_flag = 0 AND br.br_type IN (0,1,2) AND br.br_status IN (20,5) $qFilter
			GROUP BY br.br_id
			UNION ALL
		SELECT
			study.value as study_id,
			prt.company_name,
			br.*,
			ae.last_name AS acct_exec,
			am.last_name AS acct_mgr,
			SUM(budget.actual_value) AS total,
			SUM(budget.actual_value) AS total_num,
			prt.partner_id,
			tcount.value AS tracker_id,
			studyname.value as studyname
			FROM br_main AS br
			LEFT OUTER JOIN br_attr AS study ON study.key = 'STUDY_ID' AND study.id = br.br_id
			LEFT OUTER JOIN br_attr AS studyname ON studyname.key = 'STUDYNAME' AND studyname.id = br.br_id
			LEFT OUTER JOIN br_attr AS tcount ON tcount.key = 'TCOUNT' AND tcount.id = br.br_id
			LEFT OUTER JOIN br_attr AS prt_id ON prt_id.id = br.br_id AND prt_id.key = 'PARTNERID'
			LEFT OUTER JOIN partners AS prt ON prt_id.value = prt.partner_id
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			LEFT OUTER JOIN user AS am ON prt.proj_mgr = am.login
			LEFT OUTER JOIN user AS ae ON prt.acct_exec = ae.login
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			WHERE br.merge_id = 0 AND br.pre_flag = 0 AND br.br_type NOT IN (0,1,2) AND br.br_status IN (20,5) $qFilter
			GROUP BY br.br_id
		$qOrderBy";

		return $this->executeQuery($brSQL);
	}


	/**
	* GetAllBRs()
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Nov 21 15:27:09 PST 2005
	*/
	function GetAllBRs($qOrderBy)
	{
		global $userRights, $o;

		$qFilter = $this->GetUserFilter();

		if (isset($_SESSION['searchFilter']) || $userRights->userSecCheck('BRSTRICT') || ($_POST['frmName']=='frmSearch')) {
			$qFilter .= $this->getSearchFilter();
		}else{
			$qFilter .= $this->getDateFilter($o);
		}

		$this->debugPrint("Filter : ".$qFilter);
		if ($o['direction']!=0) $o['direction']=0;

		$brSQL =
		"SELECT
			study.value as study_id,
			prt.company_name,
			br.*,
			ae.last_name AS acct_exec,
			am.last_name AS acct_mgr,
			SUM(budget.actual_value) AS total,
			SUM(budget.actual_value) AS total_num,
			prt.partner_id,
			tcount.value AS tracker_id,
			prj.study_name as studyname
			FROM br_main AS br
			LEFT OUTER JOIN br_attr AS study ON study.key = 'STUDY_ID' AND study.id = br.br_id
			LEFT OUTER JOIN br_attr AS tcount ON tcount.key = 'TCOUNT' AND tcount.id = br.br_id
			LEFT OUTER JOIN study AS prj ON prj.study_id = study.value
			LEFT OUTER JOIN partners AS prt ON prj.partner_id = prt.partner_id
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			LEFT OUTER JOIN user AS am ON prt.proj_mgr = am.login
			LEFT OUTER JOIN user AS ae ON prt.acct_exec = ae.login
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			WHERE br.merge_id = 0 AND br.pre_flag = 0 AND br.br_type IN (0,1,2) AND br.br_status NOT IN (20,5) $qFilter
			GROUP BY br.br_id
			UNION ALL
		SELECT
			study.value as study_id,
			prt.company_name,
			br.*,
			ae.last_name AS acct_exec,
			am.last_name AS acct_mgr,
			SUM(budget.actual_value) AS total,
			SUM(budget.actual_value) AS total_num,
			prt.partner_id,
			tcount.value AS tracker_id,
			studyname.value as studyname
			FROM br_main AS br
			LEFT OUTER JOIN br_attr AS study ON study.key = 'STUDY_ID' AND study.id = br.br_id
			LEFT OUTER JOIN br_attr AS studyname ON studyname.key = 'STUDYNAME' AND studyname.id = br.br_id
			LEFT OUTER JOIN br_attr AS tcount ON tcount.key = 'TCOUNT' AND tcount.id = br.br_id
			LEFT OUTER JOIN br_attr AS prt_id ON prt_id.id = br.br_id AND prt_id.key = 'PARTNERID'
			LEFT OUTER JOIN partners AS prt ON prt_id.value = prt.partner_id
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			LEFT OUTER JOIN user AS am ON prt.proj_mgr = am.login
			LEFT OUTER JOIN user AS ae ON prt.acct_exec = ae.login
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			WHERE br.merge_id = 0 AND br.pre_flag = 0 AND br.br_type NOT IN (0,1,2) AND br.br_status NOT IN (20,5) $qFilter
			GROUP BY br.br_id
			UNION ALL
		SELECT
			study.value as study_id,
			prt.company_name,
			br.*,
			ae.last_name AS acct_exec,
			am.last_name AS acct_mgr,
			SUM(budget.actual_value) AS total,
			SUM(budget.actual_value) AS total_num,
			prt.partner_id,
			tcount.value AS tracker_id,
			prj.study_name as studyname
			FROM br_main AS br
			LEFT OUTER JOIN br_attr AS study ON study.key = 'STUDY_ID' AND study.id = br.br_id
			LEFT OUTER JOIN br_attr AS tcount ON tcount.key = 'TCOUNT' AND tcount.id = br.br_id
			LEFT OUTER JOIN study AS prj ON prj.study_id = study.value
			LEFT OUTER JOIN partners AS prt ON prj.partner_id = prt.partner_id
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			LEFT OUTER JOIN br_attr AS ae_login ON ae_login.id = br.br_id AND ae_login.key = 'GMIAE'
			LEFT OUTER JOIN user AS ae ON ae.login = ae_login.value
			LEFT OUTER JOIN br_attr AS am_login ON am_login.id = br.br_id AND am_login.key = 'GMIAM'
			LEFT OUTER JOIN user AS am ON am.login = am_login.value
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			WHERE br.merge_id = 0 AND br.pre_flag = 0 AND br.br_type IN (0,1,2) AND br.br_status IN (20,5) $qFilter
			GROUP BY br.br_id
			UNION ALL
		SELECT
			study.value as study_id,
			prt.company_name,
			br.*,
			ae.last_name AS acct_exec,
			am.last_name AS acct_mgr,
			SUM(budget.actual_value) AS total,
			SUM(budget.actual_value) AS total_num,
			prt.partner_id,
			tcount.value AS tracker_id,
			studyname.value as studyname
			FROM br_main AS br
			LEFT OUTER JOIN br_attr AS study ON study.key = 'STUDY_ID' AND study.id = br.br_id
			LEFT OUTER JOIN br_attr AS studyname ON studyname.key = 'STUDYNAME' AND studyname.id = br.br_id
			LEFT OUTER JOIN br_attr AS tcount ON tcount.key = 'TCOUNT' AND tcount.id = br.br_id
			LEFT OUTER JOIN br_attr AS prt_id ON prt_id.id = br.br_id AND prt_id.key = 'PARTNERID'
			LEFT OUTER JOIN partners AS prt ON prt_id.value = prt.partner_id
			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
			LEFT OUTER JOIN user AS am ON prt.proj_mgr = am.login
			LEFT OUTER JOIN user AS ae ON prt.acct_exec = ae.login
			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
			WHERE br.merge_id = 0 AND br.pre_flag = 0 AND br.br_type NOT IN (0,1,2) AND br.br_status IN (20,5) $qFilter
			GROUP BY br.br_id
		$qOrderBy";

//		"SELECT
//			study.value as study_id,
//			prt.company_name,
//			br.*,
//			ae.last_name AS acct_exec,
//			am.last_name AS acct_mgr,
//			SUM(budget.actual_value) AS total,
//			SUM(budget.actual_value) AS total_num,
//			prt.partner_id,
//			tcount.value AS tracker_id,
//			prj.study_name as studyname
//			FROM br_main AS br
//			LEFT OUTER JOIN br_attr AS study ON study.key = 'STUDY_ID' AND study.id = br.br_id
//			LEFT OUTER JOIN br_attr AS tcount ON tcount.key = 'TCOUNT' AND tcount.id = br.br_id
//			LEFT OUTER JOIN study AS prj ON prj.study_id = study.value
//			LEFT OUTER JOIN partners AS prt ON prj.partner_id = prt.partner_id
//			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
//			LEFT OUTER JOIN br_attr AS ae_login ON ae_login.id = br.br_id AND ae_login.key = 'GMIAE'
//			LEFT OUTER JOIN user AS ae ON ae.login = ae_login.value
//			LEFT OUTER JOIN br_attr AS am_login ON am_login.id = br.br_id AND am_login.key = 'GMIAM'
//			LEFT OUTER JOIN user AS am ON am.login = am_login.value
//			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
//			WHERE br.merge_id = 0 AND br.pre_flag = 0 AND br.br_type IN (0,1,2) $qFilter
//			GROUP BY br.br_id
//			UNION ALL
//		SELECT
//			study.value as study_id,
//			prt.company_name,
//			br.*,
//			ae.last_name AS acct_exec,
//			am.last_name AS acct_mgr,
//			SUM(budget.actual_value) AS total,
//			SUM(budget.actual_value) AS total_num,
//			prt.partner_id,
//			tcount.value AS tracker_id,
//			studyname.value as studyname
//			FROM br_main AS br
//			LEFT OUTER JOIN br_attr AS study ON study.key = 'STUDY_ID' AND study.id = br.br_id
//			LEFT OUTER JOIN br_attr AS studyname ON studyname.key = 'STUDYNAME' AND studyname.id = br.br_id
//			LEFT OUTER JOIN br_attr AS tcount ON tcount.key = 'TCOUNT' AND tcount.id = br.br_id
//			LEFT OUTER JOIN br_attr AS prt_id ON prt_id.id = br.br_id AND prt_id.key = 'PARTNERID'
//			LEFT OUTER JOIN partners AS prt ON prt_id.value = prt.partner_id
//			LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br.br_id
//			LEFT OUTER JOIN user AS am ON prt.proj_mgr = am.login
//			LEFT OUTER JOIN user AS ae ON prt.acct_exec = ae.login
//			LEFT OUTER JOIN partner_user AS pu ON pu.partner_id = prt.partner_id AND pu.role_id = ".SECONDARY_ACCT_MGR."
//			WHERE br.merge_id = 0 AND br.pre_flag = 0 AND br.br_type NOT IN (0,1,2) $qFilter
//			GROUP BY br.br_id
//		$qOrderBy";

		return $this->executeQuery($brSQL);
	}

	/**
	* GetBRFlags()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Nov 10 14:45:16 PST 2005
	*/
	function GetBRFlags($br_id)
	{
	   $qry = "SELECT *, DATE_FORMAT(created_date, '%Y-%m-%d') AS created_date FROM br_main WHERE br_id = '$br_id'";
	   return $this->executeQuery($qry);
	}

	/**
	* GetBRByStudyID()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Nov 10 14:46:40 PST 2005
	*/
	function GetBRByStudyID($str_bl_contact, $study_id)
	{
		$qry =
		"SELECT
			clt.company_name,
			CONCAT(cnt.first_name,' ',cnt.last_name) AS contact_name,
			stdy.study_name,
			cnt.addr_1 AS address_1,
			cnt.addr_2 AS address_2,
			cnt.city,
			cnt.state,
			cnt.zip,
			country.country_description AS country,
			cnt.email,
			cnt.fax,
			cnt.phone_1 AS phone,
			clt.partner_id,
			CONCAT(am.first_name,' ',am.last_name) AS acct_mgr,
			CONCAT(ae.first_name,' ',ae.last_name) AS acct_exec,
			clt.month_end_billing
		FROM study AS stdy
		LEFT OUTER JOIN partners AS clt ON clt.partner_id = stdy.partner_id
		LEFT OUTER JOIN contacts AS cnt ON cnt.contact_id = $str_bl_contact
		LEFT OUTER JOIN user AS am ON clt.proj_mgr = am.login
		LEFT OUTER JOIN user AS ae ON clt.acct_exec = ae.login
		LEFT OUTER JOIN country AS country ON cnt.country = country.country_code
		WHERE stdy.study_id = '$study_id'";
		return $this->executeQuery($qry);
	}

	/**
	* GetBRByID()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Nov 10 14:49:12 PST 2005
	*/
	function GetBRByID($str_bl_contact, $br_id, $partner_id)
	{
		$qry =
		"SELECT
			clt.company_name,
			CONCAT(cnt.first_name,' ',cnt.last_name) AS contact_name,
			stdy.value AS study_name,
			cnt.addr_1 AS address_1,
			cnt.addr_2 AS address_2,
			cnt.city,
			cnt.state,
			cnt.zip,
			country.country_description AS country,
			cnt.email,
			cnt.fax,
			cnt.phone_1 AS phone,
			clt.partner_id,
			CONCAT(am.first_name,' ',am.last_name) AS acct_mgr,
			CONCAT(ae.first_name,' ',ae.last_name) AS acct_exec,
			clt.month_end_billing
		FROM partners AS clt
		LEFT OUTER JOIN contacts AS cnt ON cnt.contact_id = $str_bl_contact
		LEFT OUTER JOIN user AS am ON clt.proj_mgr = am.login
		LEFT OUTER JOIN user AS ae ON clt.acct_exec = ae.login
		LEFT OUTER JOIN br_attr AS stdy ON stdy.id = $br_id AND stdy.key = 'STUDYNAME'
		LEFT OUTER JOIN country ON country.country_code = cnt.country
		WHERE clt.partner_id = '$partner_id'";

		return $this->executeQuery($qry);
	}

	/**
	* GetStudyIDForBRID()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Nov 10 14:51:49 PST 2005
	*/
	function GetBRIDForMergedBR($mbr_id)
	{
		$qry = "SELECT `br_id` FROM br_main WHERE merge_id = $mbr_id";

		return $this->executeQuery($qry);
	}

	/**
	* GetStudiesForBRID()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Nov 10 15:19:53 PST 2005
	*/
	function GetStudiesForBRID($br_id)
	{
		$qry =
		"SELECT
			attr.value AS study_id,
			prj.study_name,
			SUM(bg.actual_value) AS amount,
			main.br_date,
			main.br_status,
			main.br_id AS br_id,
			main.br_type
		FROM br_attr AS attr
		LEFT OUTER JOIN study AS prj ON attr.value = prj.study_id
		LEFT OUTER JOIN br_budget AS bg ON attr.id = bg.br_id
		LEFT OUTER JOIN br_main AS main ON main.br_id = attr.id
		WHERE `key` = 'STUDY_ID' AND `id` = '$br_id'
		GROUP BY bg.br_id";

	return $this->executeQuery($qry);

	}

	/**
	* UpdateMergedBRStatus()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Nov 10 16:54:51 PST 2005
	*/
	function UpdateMergedBRStatus($mbr_id, $new_status)
	{
		$qry = "UPDATE br_main SET br_status = '$new_status' WHERE merge_id = '$mbr_id'";
		$this->executeQuery($qry);
		$qry = "UPDATE br_merge SET status = '$new_status' WHERE m_id = '$mbr_id'";
		$this->executeQuery($qry);

		return true;
	}

	/**
	* GetBudgetLinesForBRID()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Nov 11 18:00:23 PST 2005
	*/
	function GetBudgetLinesForBRID($br_id)
	{
		$qry =
		"SELECT
			bd.br_line_id,
			bd.br_id,
			bd.budget_id,
			bd.quantity,
			bd.A_quantity,
			bd.unit_cost,
			bd.actual_value,
			bd.proposed_value,
			bd.group_name,
			def.desc, def.type,def.price,
			bd.flag_AM,
			bd.flag_AE,
			bd.flag_INV
		FROM br_budget AS bd
		LEFT OUTER JOIN br_budget_def AS def ON def.id = bd.budget_id
		WHERE bd.br_id = $br_id";

		return $this->executeQuery($qry);
	}

	/**
	* SetBRFlag()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Nov 17 10:04:16 PST 2005
	*/
	function SetBRFlag($flag, $value, $br_id)
	{
		$qry = "UPDATE br_main SET `$flag` = $value, br_status = 1 WHERE br_id = '$br_id'";
		$this->executeQuery($qry);
		return true;
	}

	/**
	* SetBRBudgetFlag()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Nov 17 10:36:57 PST 2005
	*/
	function SetBRBudgetFlag($flag, $value, $br_line_id)
	{
		$qry = "UPDATE br_budget SET `$flag` = '$value' WHERE br_line_id = '$br_line_id'";
		$this->executeQuery($qry);
		return true;
	}

	/**
	* GetBRBudgetMemos()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Nov 17 11:28:34 PST 2005
	*/
	function GetBRBudgetMemos($br_id, $br_line_id, $all)
	{
		if ($all) {
			$qry =
			"SELECT
				DATE_FORMAT(memo.datetime,'%Y-%m-%d') as datetime,
				CONCAT(emp.first_name,' ',emp.last_name) as user_id,
				memo.memo as memo
			FROM br_memo AS memo
			LEFT OUTER JOIN user AS emp ON memo.user_id = emp.login
			WHERE memo.br_id = '$br_id'
			ORDER BY datetime DESC";
		}else{
			$qry =
			"SELECT
				DATE_FORMAT(memo.datetime,'%Y-%m-%d') as datetime,
				CONCAT(emp.first_name,' ',emp.last_name) as user_id,
				memo.memo as memo
			FROM br_memo AS memo
			LEFT OUTER JOIN user AS emp ON memo.user_id = emp.login
			WHERE memo.br_id = '$br_id' AND memo.br_line_id = '$br_line_id'
			ORDER BY datetime DESC";
		}
		return $this->executeQuery($qry);

	}

	/**
	* AddBRBudgetMemo()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Nov 17 11:42:53 PST 2005
	*/
	function AddBRBudgetMemo($br_id, $br_line_id, $memo)
	{
		$qry = "INSERT INTO br_memo ( br_id, br_line_id, user_id, datetime, memo ) VALUES ('$br_id', '$br_line_id', '".$_SESSION['admin_id']."', NOW(), '".addslashes($memo)."')";
		$this->executeQuery($qry);
		return true;

	}

	/**
	* GetBudgetDefinitions()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Nov 17 16:59:12 PST 2005
	*/
	function GetBudgetDefinitions($br_type)
	{
		if ($br_type == TYPE_LICENSE || $br_type == TYPE_TRAINING) {
			if ($br_type == TYPE_TRAINING) {
				$rowBrFlags = "('900','950')";
			} else {
				$rowBrFlags = "('100')";
			}
			$strListMain = " AND list_main IN $rowBrFlags ";
		} elseif ($br_type == TYPE_CREDIT_MEMO_NOSTUDY) {
			$strListMain = " AND 1 = 1 ";
		} elseif ($br_type == TYPE_BR_SOFTWARE) {
			$strListMain = " AND 1 = 1 ";
		} else {
			$strListMain = " AND list_main NOT IN ('100','900','950') ";
		}

		$sqlBudget = "SELECT * FROM br_budget_def WHERE list_sub != '0' $strListMain ORDER BY `desc`";
		return $this->executeQuery($sqlBudget);

	}

	/**
	* SetInvoiceMemo()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Nov 18 11:08:26 PST 2005
	*/
	function SetInvoiceMemo($br_id, $memo)
	{
		$qMemo = "UPDATE br_main SET invoice_memo = '$memo' WHERE br_id = '$br_id'";
		$this->executeQuery($qMemo);
		return true;
	}

	/**
	* GetType()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Nov 18 11:35:58 PST 2005
	*/
	function GetType($br_id, $merged=0)
	{
		if ($merged) {
			$q = "SELECT br_type FROM br_main WHERE merge_id = '$br_id' GROUP BY br_type";
		}else{
			$q = "SELECT br_type FROM br_main WHERE br_id = '$br_id'";
		}
		$r = $this->executeQuery($q);
		if (mysql_num_rows($r)==0)
			return false;
		else{
			return mysql_result($r, 0, 'br_type');
		}

	}

	/**
	* GetContactInfo()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Nov 29 10:30:04 PST 2005
	*/
	function GetContactInfo($br_id, $merged=0)
	{
		$qContactInfo =
		"SELECT
			cnt.contact_id,
			cnt.salutation,
			cnt.first_name,
			cnt.last_name,
			CONCAT(cnt.first_name,' ',cnt.last_name) AS contact_name,
			cnt.addr_1 AS address1,
			cnt.addr_2 AS address2,
			cnt.city AS city,
			cnt.state AS state,
			cnt.zip AS zip,
			city.county AS county,
			country.country_code AS country_code,
			country_iso_code.country_attr_value AS country,
			-- country.region AS region_code,
			country_region_code.country_attr_value AS region_code,
			cnt.phone_1,
			cnt.phone_2,
			cnt.fax,
			cnt.email,
			cnt.ora_contact_id,
			cnt.ora_site_id
		FROM br_main
		LEFT OUTER JOIN br_attr AS blcontact ON	br_main.br_id = blcontact.id AND blcontact.key = 'BLCONTACT'
		LEFT OUTER JOIN contacts AS cnt ON cnt.contact_id = blcontact.value
		LEFT OUTER JOIN country ON cnt.country = country.country_code
		LEFT OUTER JOIN city ON city.postal_code = SUBSTRING(cnt.zip,1,5) AND city.country_code = cnt.country
		LEFT OUTER JOIN country_attr AS country_iso_code ON country_iso_code.country_id = country.country_id AND country_iso_code.country_attr_name = 'ISO_2_CODE'
		LEFT OUTER JOIN country_attr AS country_region_code ON country_region_code.country_id = country.country_id AND country_region_code.country_attr_name = 'QB_REGION' ";
		if($merged) {
			$qContactInfo .= "WHERE br_main.merge_id = ".$br_id;
		} else {
			$qContactInfo .= "WHERE br_main.br_id = ".$br_id;
		}
		return $this->executeQuery($qContactInfo);
	}

	/**
	* GetCompanyInfo()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Nov 29 10:58:33 PST 2005
	*/
	function GetCompanyInfo($br_id, $merged=0, $require_study=0)
	{
		$qProjectInfo = "SELECT
							prt.company_name AS company_name,
							prt.partner_id,
							prt.alt_mail_name,
							prt.list_id AS partner_list_id,
							oracct.value AS oracle_account_id,
							ae_attr.user_value AS salesrep_id,
							ae_group.value AS group_salesrep_id,
							prt.qb_parent AS qb_parent,
						   	prt.inv_flag,
							prt.proj_mgr AS am_number,
							prt.acct_exec AS ae_number,
							ae_cost_center.department_attr_value AS cost_center,
							ae_company_code.vendor_value AS company_code
						FROM br_main";

		if ($require_study) {
			$qProjectInfo .= "
						LEFT OUTER JOIN br_attr AS study_id ON
							study_id.id = br_main.br_id
						AND study_id.key = 'STUDY_ID'
						LEFT OUTER JOIN study AS prj ON
							prj.study_id = study_id.value
						LEFT OUTER JOIN partners AS prt ON
							prt.partner_id = prj.partner_id
						LEFT OUTER JOIN partners_attr AS oracct ON oracct.id = prj.partner_id AND oracct.key = 'ORACCT'
						LEFT OUTER JOIN partners_attr AS ae_group ON ae_group.id = prj.partner_id AND ae_group.key = 'AE_GRP_ID'";
		} else {
			$qProjectInfo .= "
						LEFT OUTER JOIN br_attr AS partner_id ON
							partner_id.id = br_main.br_id
						AND partner_id.key = 'PARTNERID'
						LEFT OUTER JOIN partners AS prt ON
							prt.partner_id = partner_id.value
						LEFT OUTER JOIN partners_attr AS oracct ON oracct.id = partner_id.value AND oracct.key = 'ORACCT'
						LEFT OUTER JOIN partners_attr AS ae_group ON ae_group.id = prt.partner_id AND ae_group.key = 'AE_GRP_ID'";
		}

		$qProjectInfo .= "
						LEFT OUTER JOIN user AS ae ON prt.acct_exec = ae.login
						LEFT OUTER JOIN user_attr AS ae_attr ON ae_attr.login = ae.login AND ae_attr.user_attr = 'ORA_ID'
						LEFT OUTER JOIN department_attr AS ae_cost_center ON ae_cost_center.department_id = ae.department_id AND ae_cost_center.department_attr_name = 'ORACODE'
						LEFT OUTER JOIN vendor_attr AS ae_company_code ON ae_company_code.vendor_id = ae.vendor_id AND ae_company_code.vendor_attr_name = 'ORACODE'";

		if($merged) {
			$qProjectInfo .= "
						WHERE br_main.merge_id = ".$br_id;
		} else {
			$qProjectInfo .= "
						WHERE br_main.br_id = ".$br_id;
		}

		return $this->executeQuery($qProjectInfo);
	}


	/**
	* GetProjectName()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Nov 29 11:30:56 PST 2005
	*/
	function GetProjectName($br_id, $merged, $requires_study)
	{
		if($merged) {
			$qProjectName = "SELECT `value` AS project_name FROM br_attr WHERE `id` = '".$br_id."' AND `key` = 'M_PRJNAME'";
		} else {
			if($requires_study) {
				$qProjectName = "SELECT study.study_name AS project_name
								 FROM br_attr AS attr
								 LEFT OUTER JOIN study AS study ON
								 	study.study_id = attr.value
								 WHERE attr.id = ".$br_id."
								 AND attr.key = 'STUDY_ID'";
			} else {
				$qProjectName = "SELECT study.value AS project_name
								 FROM br_attr AS study
								 WHERE study.key = 'STUDYNAME'
								 	AND study.id = ".$br_id;
			}
		}

		return $this->getDataArray($this->executeQuery($qProjectName));
	}

	/**
	* GetJobName()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Nov 29 11:43:17 PST 2005
	*/
	function GetJobName($br_id, $merged, $require_study)
	{
		if($merged) {
			$qJobName = "SELECT value AS job_name FROM br_attr WHERE `id` = '".$br_id."' AND `key` = 'M_PRJNAME'";
		} else {
			if($require_study) {
				$qJobName = "SELECT attr.value AS job_name
								 FROM br_attr AS attr
								 WHERE attr.id = ".$br_id."
								 AND attr.key = 'STUDY_ID'";
			} else {
				$qJobName = "SELECT study.value AS job_name
								 FROM br_attr AS study
								 WHERE study.key = 'STUDYNAME'
								 	AND study.id = ".$br_id;
			}
		}

		return $this->getDataArray($this->executeQuery($qJobName));
	}

	/**
	* GetJobNumber()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Nov 29 12:30:15 PST 2005
	*/
	function GetJobNumber($br_id, $merged=0)
	{
		if ($merged) {
			$qJobNum = "SELECT `value` AS job_number FROM br_attr WHERE `id` = '".$br_id."' AND `key` = 'M_JOBNUMBER'";
		}else{
			$qJobNum = "SELECT `value` AS job_number FROM br_attr WHERE `id` = '".$br_id."' AND `key` = 'JOBNUMBER'";
		}

		return $this->getDataArray($this->executeQuery($qJobNum));

	}

	/**
	* GetBudgetLines()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Nov 29 11:57:31 PST 2005
	*/
	function GetBudgetLines($br_id, $merged=0)
	{
		$qBudgetInfo =
		"SELECT
			study_id.value AS study_id,
			-- b_def.list_name AS list_id, -- left this because of anomali in the code
			b_def.list_main,
			b_def.list_sub,
			b_def.desc,
			b_def.type,
			b_def.price,
			gl_account.gl_account_code,
			budget.br_line_id,
			budget.budget_id,
			-- budget.actual_value AS actual_value,
			budget.A_quantity,
			budget.unit_cost,
			budget.group_name
			-- TRUNCATE((budget.unit_cost * budget.A_quantity),2) AS amount
		FROM br_main
		LEFT OUTER JOIN br_budget AS budget ON budget.br_id = br_main.br_id
		LEFT OUTER JOIN br_budget_def AS b_def ON budget.budget_id = b_def.id
		LEFT OUTER JOIN gl_account ON gl_account.gl_account_id = b_def.gl_account_id
		LEFT OUTER JOIN br_attr AS study_id ON study_id.id = br_main.br_id AND study_id.key = 'STUDY_ID'";
		if($merged) {
			$qBudgetInfo .= "WHERE br_main.merge_id = ".$br_id;
		} else {
			$qBudgetInfo .= "WHERE br_main.br_id = ".$br_id;
		}

		return $this->executeQuery($qBudgetInfo);
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
	* @since  - Tue Nov 29 12:17:14 PST 2005
	*/
	function GetInvoiceDate($br_id, $merged)
	{
		if ($merged) {
			$qTxnDate = "SELECT `value` AS invoice_date FROM br_attr WHERE `id` = ".$br_id." AND `key` = 'M_INVDATE'";
		} else {
			$qTxnDate = "SELECT `value` AS invoice_date FROM br_attr WHERE `id` = ".$br_id." AND `key` = 'INVDATE'";
		}

		return $this->executeQuery($qTxnDate);

	}

	/**
	* GetPMName()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Nov 29 12:42:46 PST 2005
	*/
	function GetPMName($br_id, $merged)
	{
		if ($merged) {
			$qPM = "SELECT `value` AS pm_name FROM br_attr WHERE `id` = '".$br_id."' AND `key` = 'M_PMNAME'";
		}else{
			$qPM = "SELECT `value` AS pm_name FROM br_attr WHERE `id` = '".$br_id."' AND `key` = 'PMNAME'";
		}

		return $this->getDataArray($this->executeQuery($qPM));

	}

	/**
	* GetPONumber()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Nov 29 12:46:59 PST 2005
	*/
	function GetPONumber($br_id, $merged=0)
	{
		if ($merged) {
			$qPO = "SELECT `value` AS po_number FROM br_attr WHERE `id` = '".$br_id."' AND `key` = 'M_PONUM'";
		}else{
   			$qPO = "SELECT `value` AS po_number FROM br_attr WHERE `id` = '".$br_id."' AND `key` = 'S_PONUM'";
		}
		return $this->getDataArray($this->executeQuery($qPO));

	}

	/**
	* GetInvoiceMemo()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Nov 29 12:52:59 PST 2005
	*/
	function GetInvoiceMemo($br_id, $merged=0)
	{
		$qAttrInfo = "SELECT br_main.invoice_memo FROM br_main ";
		if($merged) {
			$qAttrInfo .= "WHERE br_main.merge_id = ".$br_id;
		} else {
			$qAttrInfo .= "WHERE br_main.br_id = ".$br_id;
		}

		return $this->getField($this->executeQuery($qAttrInfo), 0, 'invoice_memo');
	}

	/**
	* SetPartnersListID()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Nov 29 13:11:05 PST 2005
	*/
	function SetPartnersListID($partner_id, $list_id)
	{
		$qry = "UPDATE partners SET list_id = '$list_id' WHERE partner_id = '$partner_id'";
		$this->executeQuery($qry);
		return true;
	}

	/**
	* SetPartnersOracleAccountID()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 15:23:23 PST 2005
	*/
	function SetPartnersOracleAccountID($partner_id, $oracle_account_id)
	{
		$key = $this->getKey('partners_attr', $partner_id, 'ORACCT');
		if ($key === '') $key=true;
		if ($key) {
			$this->updateKey('partners_attr', $partner_id, 'ORACCT', $oracle_account_id);
		}else{
			$this->addKey('partners_attr', $partner_id, 'ORACCT', $oracle_account_id);
		}
		return true;
	}

	/**
	* SetContactsOracleContactID()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 15:29:39 PST 2005
	*/
	function SetContactsOracleContactID($contact_id, $ora_contact_id)
	{
		$q = "UPDATE contacts SET `ora_contact_id`=$ora_contact_id WHERE `contact_id` = $contact_id";
		$this->executeQuery($q);
		return true;
	}

	/**
	* SetContactsOracleSiteID()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Dec 07 15:29:39 PST 2005
	*/
	function SetContactsOracleSiteID($contact_id, $ora_site_id)
	{
		$q = "UPDATE contacts SET `ora_site_id`=$ora_site_id WHERE `contact_id` = $contact_id";
		$this->executeQuery($q);
		return true;
	}

	function isMonthEndBilling($br)
	{
		$this->getBrType($br);
		$qry = "SELECT month_end_billing FROM partners WHERE partner_id = ".$this->getPartnerID($br);
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

	/**
	* GetStudyList()
	*
	* Retrive a recordset full of study_ids for merged br
	* @param
	* @param
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jun 10 10:40:32 PDT 2005
	*/
	function GetStudyList($merge_id)
	{
		$qry  = "SELECT study_id.value AS study_id ";
		$qry .= "FROM br_main AS b ";
		$qry .= "LEFT OUTER JOIN br_attr AS study_id ON study_id.id = b.br_id AND study_id.key = 'STUDY_ID' ";
		$qry .= "WHERE b.merge_id = ".$merge_id;
		return $this->executeQuery($qry);
	}

	/**
	* SetStudyInvoiceDate()
	*
	* @param
	* @param
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jun 10 10:34:26 PDT 2005
	*/
	function SetStudyInvoiceDate($study_id, $date)
	{
		$qry = "UPDATE study SET study_status_id = 2, study_invoice_date = '$date', modified_date = NOW(), modified_by = 10312 WHERE study_id = '".$study_id."'";
		return $this->executeQuery($qry);
	}

	/**
	* GetBRStatus()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Dec 27 12:20:34 PST 2005
	*/
	function GetBRStatus($br_id, $merged)
	{
		if ($merged) {
			return $this->getMergeStatus($br_id);
		}else{
			$q = "SELECT br_status AS status FROM br_main WHERE br_id = $br_id";
			return mysql_result($this->executeQuery($q), 0, 0);
		}
	}

	/**
	* CopyBudgetLines()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Dec 29 09:52:44 PST 2005
	*/
	function CopyBudgetLines($new_br_id, $old_br_id)
	{
		$qry = "INSERT INTO br_budget
						(`br_id`,`budget_id`,`A_quantity`,`quantity`,`unit_cost`,`actual_value`,`proposed_value`,`group_name`)
				  SELECT
					   '".$new_br_id."',`budget_id`,`A_quantity`,`quantity`,`unit_cost`,`actual_value`,`proposed_value`,`group_name`
				  FROM br_budget
				  WHERE br_id = ".$old_br_id;
		$rs = $this->executeQuery($qry);
		return true;
	}

	/**
	* IssueCreditMemo()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Dec 29 09:32:45 PST 2005
	*/
	function IssueCreditMemo($id)
	{
//   	$qry = "SELECT br_type FROM br_main WHERE br_id = ".$id;
//		$rs = $this->executeQuery($qry);
//		$r = mysql_fetch_array($rs,MYSQL_ASSOC);

		$old_type = $this->GetType($id);

		if ($this->requireStudy($old_type)) {
			$type = TYPE_CREDIT_MEMO;
		} else {
			$type = TYPE_CREDIT_MEMO_NOSTUDY;
		}

	 	$br_id = $this->addBR($type, $_SESSION[admin_id]);

		$study_id = $this->getKey('br_attr',$id,'STUDY_ID');
		if (!$this->requireStudy($type)) {
      	$study_id = $this->getBRPrefix($old_type) . $this->getNonStudyID($old_type);

		}
		$this->addKey('br_attr',$br_id,'STUDY_ID',$study_id);
		$partner_id = $this->getKey('br_attr',$id,'PARTNERID');
		$study_name = $this->getKey('br_attr',$id,'STUDYNAME');
		$contact_id = $this->getKey('br_attr',$id,'BLCONTACT');
		$gmi_ae = $this->getKey('br_attr', $id, 'GMIAE');
		$gmi_am = $this->getKey('br_attr', $id, 'GMIAM');
		$invoice_number =	$this->getKey('br_attr',$id,'INVOICE');

     	if ($partner_id) $this->addKey('br_attr',$br_id,'PARTNERID',$partner_id);
      if ($study_name) $this->addKey('br_attr',$br_id,'STUDYNAME',$study_name);
      if ($contact_id) $this->addKey('br_attr',$br_id,'BLCONTACT',$contact_id);
      if ($gmi_ae) $this->addKey('br_attr', $br_id, 'GMIAE', $gmi_ae);
      if ($gmi_am) $this->addKey('br_attr', $br_id, 'GMIAM', $gmi_am);

		//put the invoice number in the PO number field
		if ($invoice_number) $this->addKey('br_attr',$br_id,'S_PONUM',$invoice_number);

		//put the original br_id in a new br_attr called CMAPPTO
		$this->addKey('br_attr', $br_id, 'CMAPPTO', $id);

		$this->copyBudgetLines($br_id, $id);

		//check if we issued a credit memo for a br that used a retainer, if so then we need to revert the retainer amount
		if ($this->getKey('br_attr',$id,'RETAINER_USED')) {
			$qry = "SELECT `amount`,`study_id`,`partner_id` FROM retainer_log WHERE br_id = ".$id;
			$rs = $this->executeQuery($qry);
			$r = mysql_fetch_array($rs,MYSQL_ASSOC);

			$retainer = $r['amount'] * -1;
			$qry = "UPDATE study_attr SET `study_value` = ".$retainer." WHERE `study_id` = ".$r['study_id']." AND `study_attr` = 'RETAINER'";
			$this->executeQuery($qry);
		}
		return $br_id;
	}

	/**
	* IssueMergedCreditMemo()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Fri Jan 06 11:03:17 PST 2006
	*/
	function IssueMergedCreditMemo($id, $brs)
	{
//      $old_type = $this->GetType($id, 1);
//		if ($this->requireStudy($old_type)) {
//			$type = TYPE_CREDIT_MEMO;
//		} else {
//			$type = TYPE_CREDIT_MEMO_NOSTUDY;
//		}

  		$qBrAdd = "INSERT INTO br_merge (`m_by`,`m_date`) VALUES ('$_SESSION[admin_id]',NOW())";
     	$rsBrAdd = $this->executeQuery($qBrAdd);
      $mbr_id = $this->lastID;
		foreach($brs as $i=>$br) {
  			$q_setid = "UPDATE br_main SET merge_id = '$mbr_id' WHERE br_id = '".$brs[$i]."'";
  			$rst_setid = $this->executeQuery($q_setid);
		}

		$partner_id = $this->getKey('br_attr',$id,'M_PARTNERID');
		$study_name = $this->getKey('br_attr',$id,'M_STUDYNAME');
		$contact_id = $this->getKey('br_attr',$id,'BLCONTACT');
		$gmi_ae = $this->getKey('br_attr', $id, 'M_GMIAE');
		$gmi_am = $this->getKey('br_attr', $id, 'M_GMIAM');
		$invoice_number =	$this->getKey('br_attr',$id,'M_INVOICE');

     	if ($partner_id) $this->addKey('br_attr',$mbr_id,'M_PARTNERID',$partner_id);
      if ($study_name) $this->addKey('br_attr',$mbr_id,'M_STUDYNAME',$study_name);
      if ($contact_id) $this->addKey('br_attr',$mbr_id,'BLCONTACT',$contact_id);
      if ($gmi_ae) $this->addKey('br_attr', $mbr_id, 'M_GMIAE', $gmi_ae);
      if ($gmi_am) $this->addKey('br_attr', $mbr_id, 'M_GMIAM', $gmi_am);

		//put the invoice number in the PO number field
		if ($invoice_number) $this->addKey('br_attr',$mbr_id,'M_PONUM',$invoice_number);

		//put the original br_id in a new br_attr called CMAPPTO
		$this->addKey('br_attr', $mbr_id, 'CMAPPTO', $id);

		return $mbr_id;
	}
	/*
	* CopyBR()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Thu Dec 29 10:33:47 PST 2005
	*/
	function CopyBR($id)
	{
      	$qry = "SELECT br_type FROM br_main WHERE br_id = ".$id;
      	$rs = $this->executeQuery($qry);
      	$r = mysql_fetch_array($rs,MYSQL_ASSOC);
      	$old_type = $r['br_type'];

		//create the br record
      	$br_id = $this->addBR($old_type, $_SESSION[admin_id]);

      	$study_id = $this->getKey('br_attr',$id,'STUDY_ID');

		//copy non study specific attributes
      	if (!$this->requireStudy($old_type)) {
         	$study_id = $this->getBRPrefix($old_type) . $this->getNonStudyID($old_type);

      	}

		$this->addKey('br_attr',$br_id,'STUDY_ID',$study_id);
     	$partner_id = $this->getKey('br_attr',$id,'PARTNERID');
     	$study_name = $this->getKey('br_attr',$id,'STUDYNAME');
     	$contact_id = $this->getKey('br_attr',$id,'BLCONTACT');

     	$this->addKey('br_attr',$br_id,'PARTNERID',$partner_id);
     	$this->addKey('br_attr',$br_id,'STUDYNAME',$study_name);
      $this->addKey('br_attr',$br_id,'BLCONTACT',$contact_id);

		//copy attributes from the br
		$this->addKey('br_attr',$br_id,'S_PONUM',$this->getKey('br_attr',$id,'S_PONUM'));
		$this->addKey('br_attr',$br_id,'PMNAME',$this->getKey('br_attr',$id,'PMNAME'));
		$this->addKey('br_attr',$br_id,'JOBNUMBER',$this->getKey('br_attr',$id,'JOBNUMBER'));


		//copy the budget line
		$this->copyBudgetLines($br_id, $id);

		//send the new br id back to who called us
      	return $br_id;

	}
}
?>
