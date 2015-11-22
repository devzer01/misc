<?php
class armc extends Common {
	/* @var $atmDB atmDB */

	private $atmDB;
   private $oracleDB;

   /**
   * armc()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Tue Jul 25 11:52:34 PDT 2006
   */
   public function armc()
   {
      $this->atmDB = new atmDB();
   }

   /**
   * __LoadOracle()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.1 - Sun Jul 30 10:47:26 PDT 2006
   */
   protected function __LoadOracle()
   {
      $this->oracleDB = new oracleDB();
   }

   /**
   * SetARMCAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jul 10 13:25:43 PDT 2006
   */
   private function __SetARMCAttr($armc_id, $armc_attr_name, $armc_attr_value)
   {
      if ($this->atmDB->GetARMCAttr($armc_id, $armc_attr_name) !== false) {
         $this->atmDB->UpdateARMCAttr($armc_id, $armc_attr_name, $armc_attr_value);
      } else {
         $this->atmDB->InsertARMCAttr($armc_id, $armc_attr_name, $armc_attr_value);
      }
   }

   /**
   * SetARMCGroupAttr()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jul 10 14:12:21 PDT 2006
   */
   private function __SetARMCGroupAttr($armc_group_id, $armc_group_attr_name, $armc_group_attr_value)
   {
      if ($this->atmDB->GetARMCGroupAttr($armc_group_id, $armc_group_attr_name) !== false) {
         $this->atmDB->UpdateARMCGroupAttr($armc_group_id, $armc_group_attr_name, $armc_group_attr_value);
      } else {
         $this->atmDB->InsertARMCGroupAttr($armc_group_id, $armc_group_attr_name, $armc_group_attr_value);
      }
   }

   /**
   * SetARMCContact()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jul 10 13:39:04 PDT 2006
   */
   private function __SetARMCContact($armc_id, $info)
   {
      if (($armc_contact_id = $this->atmDB->isARMCContact($armc_id, $info["armc_contact_type_id"])) !== false) {
         $this->atmDB->UpdateARMCContact($armc_contact_id, $info);
      } else {
         $this->atmDB->InsertARMCContact($armc_id, $info);
      }
   }

   /**
   * SetARMCUser()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jul 10 14:19:16 PDT 2006
   */
   private function __SetARMCUser($armc_id, $role_id, $user_id)
   {
      if (($armc_user_id = $this->atmDB->isARMCUser($armc_id, $role_id, $user_id)) !== false) {
         $this->atmDB->UpdateARMCUser($armc_user_id, $armc_id, $role_id, $user_id);
      } else {
         $this->atmDB->InsertARMCUser($armc_id, $role_id, $user_id);
      }
   }

   /**
   * IsCreditMemo()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Feb 13 16:54:55 PST 2006
   */
   private function __IsCreditMemo($armc_type_id)
   {
      switch ($armc_type_id) {
      	case ARMC_TYPE_CM_STUDY :
      	case ARMC_TYPE_CM_TR :
      	case ARMC_TYPE_CM_LI :
      	case ARMC_TYPE_CM_SB :
      	case ARMC_TYPE_CM_OT :
      	case ARMC_TYPE_VAP_CM :
      	case ARMC_TYPE_ERS_CM :
      	case ARMC_TYPE_RSA_CM :
      	case ARMC_TYPE_ISS_CM :
      	case ARMC_TYPE_EFP_CM :
      	case ARMC_TYPE_EFS_CM :
            return true;
      		break;

      	default:
      	   return false;
      		break;
      }
   }

   /**
   * IsInvoiced()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 21 14:05:27 PDT 2006
   */
   private function __IsInvoiced($armc_status_id)
   {
      switch ($armc_status_id) {
         case ARMC_STATUS_INVOICED :
         case ARMC_STATUS_INVOICED_MANUALLY :
         case ARMC_STATUS_INVOICED_BALANCED :
         case ARMC_STATUS_INVOICED_PARTIAL_BALANCE :
            return true;
            break;

         default :
            return false;
            break;
      }
   }

   /**
   * IsRetainer()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Feb 22 16:15:54 PST 2006
   */
   private function __IsRetainer($type)
   {
      switch ($type) {
         case ARMC_TYPE_RT_STUDY :
         case ARMC_TYPE_RT_LI :
         case ARMC_TYPE_RT_OT :
         case ARMC_TYPE_RT_SB :
         case ARMC_TYPE_RT_TR :
         case ARMC_TYPE_VAP_RT :
         case ARMC_TYPE_ERS_RT :
         case ARMC_TYPE_RSA_RT :
         case ARMC_TYPE_ISS_RT :
         case ARMC_TYPE_EFP_RT :
         case ARMC_TYPE_EFS_RT :
            return true;
            break;

         default:
            return false;
            break;
      }
   }

   /**
   * RequireAMAEApproval()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed May 17 15:42:21 PDT 2006
   */
   private function __RequireAMAEApproval($type)
   {
//      switch ($type) {
//         case ARMC_TYPE_BR_LI :
//         case ARMC_TYPE_CM_LI :
//         case ARMC_TYPE_RT_LI :
//         case ARMC_TYPE_BR_TR :
//         case ARMC_TYPE_CM_TR :
//         case ARMC_TYPE_RT_TR :
//            return false;
//            break;
//
//         default :
//            return true;
//            break;
//      }
		return true;
   }

   /**
   * __RequireStudy()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.2 - Thu Apr 12 14:10:59 PDT 2007
   */
   private function __RequireStudy($type)
   {
	   switch ($type) {
	   	case ARMC_TYPE_BR_STUDY :
	   	case ARMC_TYPE_RT_STUDY :
	   	case ARMC_TYPE_CM_STUDY :
	   		return true;
	   		break;

	   	default :
	   		return false;
	   		break;
	   }
   }

   /**
   * __CalculateAmountPaid()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 28 17:43:14 PDT 2006
   */
   private function __CalculateAmountPaid($payments)
   {
      $amount_paid=0;
      $p = array("amount_paid"=>0);
      foreach ($payments AS $payment) {
         $amount_paid += (($payment["INVOICE_CURRENCY_CODE"]=="USD")?($payment["AMOUNT_PAID"]):($payment["AMOUNT_PAID"]*$payment["INVOICE_EXCHANGE_RATE"]));
         $p = array(
            "payment_type"=>$payment["PAYMENT_TYPE"],
            "payment_id"=>$payment["PAYMENT_ID"],
            "payment_date"=>date("Y-m-d", strtotime($payment["GL_DATE"], time())),
            "document_number"=>$payment["DOCUMENT_NUMBER"],
            "amount_paid"=>$amount_paid);
      }

      return $p;
   }

   /**
   * __SetSinglePayment()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 28 18:05:57 PDT 2006
   */
   private function __SetSinglePayment($armc_id, $payment, $full_payment = true)
   {
      if ($full_payment) {
         $armc_status = ARMC_STATUS_INVOICED_BALANCED;
         $armc_event = ARMC_EVENT_TYPE_BALANCED_FULL;
      } else {
         $armc_status = ARMC_STATUS_INVOICED_PARTIAL_BALANCE;
         $armc_event = ARMC_EVENT_TYPE_BALANCED_PARTIAL;
      }

      $this->atmDB->SetARMCStatus($armc_id, $armc_status);
      $this->atmDB->WriteARMCEvent($armc_id, $armc_event, $payment["payment_type"]." # ".$payment["document_number"]." on ".$payment["payment_date"]);
      $this->__SetARMCAttr($armc_id, "AMOUNT_PAID", $payment["amount_paid"]);

      $this->__SetARMCAttr($armc_id, "PAYMENT_TYPE", $payment["payment_type"]);
      $this->__SetARMCAttr($armc_id, "PAYMENT_ID", $payment["payment_id"]);
      $this->__SetARMCAttr($armc_id, "PAYMENT_DATE", $payment["payment_date"]);
      $this->__SetARMCAttr($armc_id, "PAYMENT_NUMBER", $payment["document_number"]);
   }

   /**
   * __SetGroupPayment()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 28 17:57:58 PDT 2006
   */
   private function __SetGroupPayment($armc_group_id, $payment, $full_payment = true)
   {
      if ($full_payment) {
         $armc_status = ARMC_STATUS_INVOICED_BALANCED;
      } else {
         $armc_status = ARMC_STATUS_INVOICED_PARTIAL_BALANCE;
      }

      $this->__SetARMCGroupAttr($armc_group_id, "ARMC_STATUS_ID", $armc_status);
      $this->__SetARMCGroupAttr($armc_group_id, "AMOUNT_PAID", $payment["amount_paid"]);

      $this->__SetARMCGroupAttr($armc_group_id, "PAYMENT_TYPE", $payment["payment_type"]);
      $this->__SetARMCGroupAttr($armc_group_id, "PAYMENT_ID", $payment["payment_id"]);
      $this->__SetARMCGroupAttr($armc_group_id, "PAYMENT_DATE", $payment["payment_date"]);
      $this->__SetARMCGroupAttr($armc_group_id, "PAYMENT_NUMBER", $payment["document_number"]);

      $rst = $this->atmDB->GetARMCGroupList(" AND a.armc_group_id='".$armc_group_id."'");

      while ($a = mysql_fetch_assoc($rst)) {
         $this->__SetSinglePayment($a["armc_id"], $payment, $full_payment);
      }
   }

   /**
   * __SetPayment()
   *
   * @param
   * @todo
   * @return
   * @since  - 2.0.1 - Fri Jul 28 19:02:29 PDT 2006
   */
   private function __SetPayment($armc_id, $payment, $grouped, $full_payment = true)
   {
      //echo ("<pre>ArmcID:$armc_id\n".print_r($payment, true)."</pre>");
      if ($grouped) {
         $this->__SetGroupPayment($armc_id, $payment, $full_payment);
      } else {
         $this->__SetSinglePayment($armc_id, $payment, $full_payment);
      }
   }



   /**
   * CheckOracleBalance()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Mon Jul 24 16:17:32 PDT 2006
   */
   public function CheckOracleBalance($armc)
   {
      //$this->__LoadOracle();
      $log = Hb_Util_Log_Logger::GetInstance("oracle");
      $log->LogInfo("Oracle Check Balances", "Checking invoice : ".$armc["transaction_number"]);

      $payments = $this->oracleDB->GetInvoicePayments($armc["transaction_number"]);

      $p = $this->__CalculateAmountPaid($payments);
      //echo ("<pre>".print_r($armc, true).print_r($payments, true).print_r($p, true)."</pre>");
      $log->LogDebug("Oracle Check Balances", "Amount paid for ".$armc["transaction_number"]." : $".$p["amount_paid"]);

      if (($p["amount_paid"]     == 0) ||
          ($armc["amount_paid"]  == $p["amount_paid"])) {
         $ret = array(
            "status"          => "pending",
            "armc"            => $armc["armc_type_prefix"]."-".$armc["armc_id"],
            "invoice"         => $armc["transaction_number"],
            "payment_type"    => "N/A",
            "payment_number"  => "N/A",
            "payment_date"    => "N/A",
            "payment_amount"  => 0,
            "amount_due"      => $armc["amount"] - $armc["amount_paid"]);

      } else {
         $ret = array(
            "armc"            => $armc["armc_type_prefix"]."-".$armc["armc_id"],
            "invoice"         => $armc["transaction_number"],
            "payment_type"    => $p["payment_type"],
            "payment_number"  => $p["document_number"],
            "payment_date"    => $p["payment_date"],
            "payment_amount"  => ($p["amount_paid"]-$armc["amount_paid"]),
            "amount_due"      => $armc["amount"]-$armc["amount_paid"]);

         if (abs($p["amount_paid"]) >= abs($armc["amount"] - ($armc["amount"] / 100))) {
            //Ballance less than 1% of the original amount, consider it paid in full (allow 1% margin for exchange rate loss)
            $ret["status"] = "full";
            $this->__SetPayment($armc["armc_id"], $p, $armc["grouped"] == "true", true);
         }else{
            $ret["status"] = "partial";
            $this->__SetPayment($armc["armc_id"], $p, $armc["grouped"] == "true", false);
         }
      }


      //echo ("<pre>".print_r($armc, true).print_r($payments, true).print_r($p, true).print_r($ret, true)."</pre>");
      $log->LogDebug("Oracle Check Balances", "Status : ".$ret["status"].", Payment Type : ".$ret["payment_type"].", Payment Number : ".$ret["payment_number"].", Payment date : ".$ret["payment_date"].", Amount due : $".$ret["amount_due"]);

      return $ret;
   }

   /**
   * __SendCheckOracleBalancesMessage()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Fri Jul 28 18:29:17 PDT 2006
   */
   private function __SendCheckOracleBalancesMessage($summary, $list)
   {
      $this->LoadSmarty();

      $default = $this->atmDB->GetARMCSetting("DEFAULT_ROLE_ID_17");
      $default = $default["armc_setting_value"];

      $this->smarty->assign("summary", $summary);
      $this->smarty->assign("list", $list);

      $screen = $this->smarty->fetch("app/atm/armc/email_check_balances.tpl");
      $handheld = $this->smarty->fetch("app/atm/armc/handheld_check_balances.tpl");
      $tty = $this->smarty->fetch("app/atm/armc/tty_check_balances.tpl");

      $params = array(
         "message_type_id" =>ATM_MESSAGE_TYPE_ORACLE_CHECK_BALANCES,
         "protocol"        =>"v2",
         "rcpt"            => array($default),
         "msg"             =>array(
            "subject"   => "Oracle Check Invoice Balances Results",
            "body"      => array(
               "screen"    => $screen,
               "handheld"  => $handheld,
               "tty"       => $tty)),
         "attr"   =>array());
      //echo ("<pre>".print_r($params, true)."</pre>");
      HBRPCCall('com', 'QueueMessage', $params);
   }

   /**
   * CheckOracleBalances()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @global
   * @since  - Wed Jul 12 14:20:18 PDT 2006
   */
   public function CheckOracleBalances($include_armc=array())
   {

      $log = Hb_Util_Log_Logger::GetInstance("oracle");
      $log->LogInfo("Oracle Check Balances", basename(__FILE__) . " started");

      //$atmDB = new atmDB();
      //$oracleDB = new oracleDB();

      $summary = array(
         "counts"          => array(
                              "processed" =>0,
                              "full"      =>0,
                              "partial"   =>0,
                              "pending"   =>0),
         "payment_amount"  => array(
                              "processed" =>0,
                              "full"      =>0,
                              "partial"   =>0,
                              "pending"   =>0),
         "amount_due"      => array(
                              "processed" =>0,
                              "full"      =>0,
                              "partial"   =>0,
                              "pending"   =>0));

      $rst = $this->atmDB->GetARMCInvoicedUnbalanced();
      $this->__LoadOracle();
      while (($armc = mysql_fetch_assoc($rst))){
         //echo ("ARMC : ".$armc["armc_id"]."<br/>");
      	$include = true;
      	if (count($include_armc) && (!in_array($armc["armc_id"], $include_armc)))
            $include = false;
         if (($include) && (!$this->__IsCreditMemo($armc["armc_type_id"]))) {
            $result = $this->CheckOracleBalance($armc);

            $list[$result["status"]][] = $result;

            $summary["counts"]["processed"]++;
            $summary["counts"][$result["status"]]++;

            $summary["payment_amount"]["processed"]+=$result["payment_amount"];
            $summary["payment_amount"][$result["status"]]+=$result["payment_amount"];

            $summary["amount_due"]["processed"]+=$result["amount_due"];
            $summary["amount_due"][$result["status"]]+=$result["amount_due"];
         }
      }

      $this->__SendCheckOracleBalancesMessage($summary, $list);
   }

   /**
   * GetAppianReport()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.1 - Fri Dec 15 03:12:44 PST 2006
   */
   public function GetAppianReport()
   {
      $this->LoadSmarty();

//      $line_buckets = array(
//         "setup"     => array(1,2,3),
//         "hosting"   => array(4,5,6),
//         "panel"     => array(7,8,9),
//         "data"      => array(10,11,12)
//      );


      $atmDB = new atmDB();
      $rst = $atmDB->GetATMGLAccounts();
      while ($gl = mysql_fetch_assoc($rst)) {
         $line_buckets[$gl["atm_gl_account_description"]] = array($gl["atm_gl_account_id"]);
      }

      $rst = $atmDB->GetARMCList("", "ORDER BY armc_date");

      while ($armc = mysql_fetch_assoc($rst)) {
         $lines_rst = $atmDB->GetARMCLines($armc["armc_id"]);
         foreach ($line_buckets AS $bucket => $accounts) {
            $armc[$bucket] = 0;
         }

         if ($armc["grouped"] == "true") {
            $armc["study_id"] = str_replace(",", ";", $armc["study_id"]);
         }
         $armc["study_name"] = str_replace(",", ";", $armc["study_name"]);
         $armc["account_name"] = str_replace(",", ";", $armc["account_name"]);

         while ($line = mysql_fetch_assoc($lines_rst)) {

            foreach ($line_buckets AS $bucket => $accounts) {
         	  if (in_array($line["atm_gl_account_id"], $accounts)) {
         	     $armc[$bucket] += $line["actual_amount"];
         	  }
         	}
         }
         $armcs[] = $armc;
         //echo ("<pre>".print_r($armc, true)."</pre>");
      }

      $this->smarty->assign("buckets", $line_buckets);
      $this->smarty->assign("list", $armcs);
      $content = $this->smarty->fetch("app/atm/armc/xls_appian_report.tpl");
      $fp = fopen("/tmp/billing_information.csv", "w+");
      fwrite($fp, $content);
      fclose($fp);
      //header("Content-type: text/csv; charset=iso-8859-1; header=present;");
      //header("Content-Disposition: attachment; filename=billing_information.csv");
      //echo $content;
   }

   /**
   * __CheckAccountExecutive()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.2 - Wed Apr 11 15:06:30 PDT 2007
   */
   private function __CheckAccountExecutive($armc_id)
   {
   	$ret = 0;
   	$user_db = new userDB();
   	$ae_rst = $this->atmDB->GetARMCUsers($armc_id, array(ROLE_PRIMARY_ACCT_EXEC));

      if ($armc_ae = mysql_fetch_assoc($ae_rst)) {
         
      	$comDB = new commonDB();
         $aes = array();
         $aes_rst = $comDB->GetUsersByRoleId(ROLE_ACCOUNT_EXECUTIVE);
         while ($ae = mysql_fetch_assoc($aes_rst)) {
            $aes[] = $ae["login"];
         }
         
         //echo ("This BR AE : ".$armc_ae["login"].", AEs : ".print_r($aes, true));
         if (!in_array($armc_ae["login"], $aes) && !$user_db->isValidUser($armc_ae["login"]))   
         {
				/** return value made to '0' because AE of the BR is not an active user now.*/
				return 0;
         	
         }
         else if (!in_array($armc_ae["login"], $aes)) {

     	   	$ret = -2;
        	}
      } else {
			$ret = -1;
      }
      return $ret;
   }

   /**
   * __CheckBillingContact()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.2 - Wed Apr 11 15:11:44 PDT 2007
   */
   //Moved to Contact Manager
   /*
   private function __CheckBillingContact($armc_id)
   {
   	$ret = 0;
	  	$billing_contact_rst = $this->db_atm_armc_ContactsManager->GetARMCContactsGetARMCContacts($armc_id, array(ARMC_CONTACT_TYPE_BILLING));
   	$billing_contact_rst = $this->atmDB->GetARMCContacts($armc_id, array(ARMC_CONTACT_TYPE_BILLING));

   	if ($billing_contact = mysql_fetch_assoc($billing_contact_rst)) {
      	if ($billing_contact["address_1"]=="" || $billing_contact["city"]=="" || $billing_contact["country_code"]=="" || $billing_contact["email"]=="") {
         	$ret = -2;
         }
      } else {
      	$ret = -1;
      }
      return $ret;
   }
   */


   /**
   * __CheckProjectContact()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.2 - Wed Apr 11 15:14:59 PDT 2007
   */

   //Moved to Contacts Manager class
   /*
   private function __CheckProjectContact($armc_id)
   {
   	$ret = 0;
      $sat_survey = $this->atmDB->GetARMCAttr($armc_id, "STM_SEND_SAT_SURVEY");
		if ($sat_survey) {
         $project_contact_rst = $this->atmDB->GetARMCContacts($armc_id, array(ARMC_CONTACT_TYPE_PROJECT));
         if ($project_contact = mysql_fetch_assoc($project_contact_rst)) {
         	if ($project_contact["last_name"]=="" || $project_contact["email"]=="") {
      	   	$ret = -2;
   	      }
	     	} else {
      		$ret = -1;
      	}
		}
		return $ret;
   }
   */

   /**
   * __CheckHeader()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.2 - Wed Apr 11 16:38:00 PDT 2007
   */
   private function __CheckHeader($armc_id)
   {
   	$error = "";
		$ae_check = $this->__CheckAccountExecutive($armc_id);
      if ($ae_check == -1) {
			$error = "&error=The Primary Account Executive is required !!!";
		} else if ($ae_check == -2) {
   		$error = "&error=The Primary Account Executive on this Billing Report does not belong to the Account Executives group. Please make sure the Primary Account Executive at the account level is an Account Executive, and refresh the Billing Report to reflect the correct Primary Account Execuvite.";
   	}

      //Directs to Contacts Manager class
      $contact_manager = new atm_armc_ContactsManager();
      $billing_contact_check = $contact_manager->CheckBillingContact($armc_id);
      //$billing_contact_check = $this->__CheckBillingContact($armc_id);

      if ($billing_contact_check == -1) {
      	$error = "&error=Billing contact is required.";
      } else if ($billing_contact_check == -2) {
      	$error = "&error=Address 1, City, Country and email are required fields for the Billing Contact.";
      }
      //Directed to contact manager class
      //$project_contact_check = $this->__CheckProjectContact($armc_id);
      $project_contact_check = $contact_manager->CheckProjectContact($armc_id);

      if ($project_contact_check == -1) {
      	$error = "&error=Project Contact is required for satisfaciton survey.";
      } else if ($billing_contact_check == -2) {
      	$error = "&error=Name and email are required fields for the Project Contact.";
      }
      return $error;
   }

   /**
   * __CheckInvoice()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.2 - Wed Apr 11 17:34:58 PDT 2007
   */
   private function __CheckInvoice($armc_id)
   {
   	$error = "";
		$po_required = $this->atmDB->GetARMCAttr($armc_id, "ARMC_PO_REQUIRED");
      if ($po_required=="" || $po_required==null) $po_required = false;
      $po = $this->atmDB->GetARMCAttr($armc_id, "PONUMBER");
      if ($po=="" || $po==null) $po = false;
      if ($po_required && !$po) {
			$error = "&error=P.O. number is required";
      }

      $job_required = $this->atmDB->GetARMCAttr($armc_id, "ARMC_JOB_REQUIRED");
      if ($job_required=="" || $job_required==null) $job_required = false;
      $job = $this->atmDB->GetARMCAttr($armc_id, "JOBNUMBER");
      if ($job=="" || $job==null) $job = false;
      if ($job_required && !$job) {
       	$error = "&error=Job number is required";
      }

      $pm_required = $this->atmDB->GetARMCAttr($armc_id, "ARMC_PM_REQUIRED");
      if ($pm_required=="" || $pm_required==null) $pm_required = false;
      $pm = $this->atmDB->GetARMCAttr($armc_id, "PMNAME");
      if ($pm=="" || $pm==null) $pm = false;
      if ($pm_required && !$pm) {
      	$error = "&error=Project Manager is required";
      }
      return $error;
   }

   /**
   * __ARMCIsApprovedByAM()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.2 - Wed Apr 11 15:50:43 PDT 2007
   */
   private function __ARMCIsApprovedByAM($armc_id)
   {  
	   $sections = ($this->atmDB->ARMCIsApproved($armc_id, ARMC_ACTION_TYPE_AM_APPROVED_HEADER) && $this->atmDB->ARMCIsApproved($armc_id, ARMC_ACTION_TYPE_AM_APPROVED_INVOICE));
      if ($sections) {
      	if ($this->atmDB->GetARMCTypeID($armc_id) == ARMC_TYPE_BR_STUDY) {
      		$study_id = $this->atmDB->GetARMCAttr($armc_id, "STUDY_ID");

      	   $study_costs = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_Study')->Find($study_id)->GetStudyCostsActive();
                 
            foreach($study_costs AS $cost) 
            {                        	               
               if (!($cost->IsAMApproved() || $cost->IsAMRejected()))
                  return false;
            }
      	   
      	}
         $rst = $this->atmDB->GetARMCLines($armc_id);
         while ($line = mysql_fetch_assoc($rst)) {
            if (!$this->atmDB->ARMCLineIsApproved($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AM_APPROVED_LINE))
               return false;
         }
         return true;
      }else{
         return false;
      }
   }

   /**
   * __ARMCIsApprovedByAE()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.2 - Wed Apr 11 15:59:36 PDT 2007
   */
   private function __ARMCIsApprovedByAE($armc_id)
   {
	   $sections = ($this->atmDB->ARMCIsApproved($armc_id, ARMC_ACTION_TYPE_AE_APPROVED_HEADER) && $this->atmDB->ARMCIsApproved($armc_id, ARMC_ACTION_TYPE_AE_APPROVED_INVOICE));

      if ($sections) {
         if ($this->atmDB->GetARMCTypeID($armc_id) == ARMC_TYPE_BR_STUDY) {
            $study_id = $this->atmDB->GetARMCAttr($armc_id, "STUDY_ID");
            
            $study_costs = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_Study')->Find($study_id)->GetStudyCostsActive();
                 
            foreach($study_costs AS $cost) 
            {                        	               
               if (!($cost->IsAMApproved() || $cost->IsAMRejected()))
                  return false;
            }
            
         }
         $rst = $this->atmDB->GetARMCLines($armc_id);
         while ($line = mysql_fetch_assoc($rst)) {
            if (!$this->atmDB->ARMCLineIsApproved($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AE_APPROVED_LINE))
               return false;
         }
         return true;
      }else{
         return false;
      }
   }

   /**
   * __ARMCIsApprovedByACCT()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.2 - Wed Apr 11 16:00:37 PDT 2007
   */
   private function __ARMCIsApprovedByACCT($armc_id)
   {
	   $sections = ($this->atmDB->ARMCIsApproved($armc_id, ARMC_ACTION_TYPE_ACCT_APPROVED_HEADER) && $this->atmDB->ARMCIsApproved($armc_id, ARMC_ACTION_TYPE_ACCT_APPROVED_INVOICE));

      if ($sections) {
         if ($this->atmDB->GetARMCTypeID($armc_id) == ARMC_TYPE_BR_STUDY) {
            $study_id = $this->atmDB->GetARMCAttr($armc_id, "STUDY_ID");

            $study_costs = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_Study')->Find($study_id)->GetStudyCostsActive();
                 
            foreach($study_costs AS $cost) 
            {                        	               
               if (!($cost->IsAMApproved() || $cost->IsAMRejected()))
                  return false;
            }
            
         }
      	$rst = $this->atmDB->GetARMCLines($armc_id);
         while ($line = mysql_fetch_assoc($rst)) {
            if (!$this->atmDB->ARMCLineIsApproved($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_ACCT_APPROVED_LINE))
               return false;
         }
         return true;
      }else{
         return false;
      }
   }

   /**
   * __ARMCRequiresAEApproval()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.2 - Wed Apr 11 15:54:58 PDT 2007
   */
   private function __ARMCRequiresAEApproval($armc_id)
   {
      if ($this->atmDB->ARMCRequiresAEApproval($armc_id))
      {
      	if(isAEValidUser($armc_id)) 
      	{
      		return true;
      	}

      	$this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_APPROVAL_SKIPPED,"Account Executive Inactive");
      	
      	return false;
      	
      }
      
      return false;
   }

   /**
   * __ARMCRequiresACCTApproval()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.2 - Wed Apr 11 15:57:06 PDT 2007
   */
   private function __ARMCRequiresACCTApproval($armc_id)
   {
   	
   	if ($this->atmDB->ARMCRequiresACCTApproval($armc_id)) 
   	{
			return true;
		}
   	
		if ($this->atmDB->ARMCRequiresAEApproval($armc_id) && !isAEValidUser($armc_id))
      {
	     	return true;
      }		
   	
   	return false;
   }


   /**
   * __RecordApproval()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.2 - Wed Apr 11 15:47:00 PDT 2007
   */
   private function __RecordApproval($armc_id, $section, $by)
   {
		switch ($section) {
			case "header" : {
				switch ($by) {
					case "AM" : {
     			      $this->atmDB->WriteARMCAction($armc_id, ARMC_ACTION_TYPE_AM_APPROVED_HEADER);
	               $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_AM_APPROVED_HEADER);
     			      if ($this->__ARMCIsApprovedByAM($armc_id)) {
              			if ($this->__ARMCRequiresAEApproval($armc_id)) {
	        	            $this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_AE_APPROVAL);
   		               $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_AE_APPROVAL);
     	   		         SendApprovalAlert($armc_id, ATM_MESSAGE_TYPE_AE_APPROVAL);
	                 	} else if ($this->__ARMCRequiresACCTApproval($armc_id)) {
                  	   $this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_ACCT_APPROVAL);
		                  $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_ACCT_APPROVAL);
      		         	SendApprovalAlert($armc_id, ATM_MESSAGE_TYPE_ACCT_APPROVAL);
            			} else {
            				$this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_READY_FOR_INVOICE);
            				$this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_READY);
							}
     			      }
					} break;

					case "AE" : {
        			   $this->atmDB->WriteARMCAction($armc_id, ARMC_ACTION_TYPE_AE_APPROVED_HEADER);
		            $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_AE_APPROVED_HEADER);
        			   if ($this->__ARMCIsApprovedByAE($armc_id)) {
        			   	if ($this->__ARMCRequiresACCTApproval($armc_id)) {
			               $this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_ACCT_APPROVAL);
   	     			      $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_ACCT_APPROVAL);
			               SendApprovalAlert($armc_id, ATM_MESSAGE_TYPE_ACCT_APPROVAL);
        			   	} else {
            				$this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_READY_FOR_INVOICE);
            				$this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_READY);
        			   	}
        			   }
					} break;

					case "ACCT" : {
	      			$this->atmDB->WriteARMCAction($armc_id, ARMC_ACTION_TYPE_ACCT_APPROVED_HEADER);
    	         	$this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_ACCT_APPROVED_HEADER);
        	      	if ($this->__ARMCIsApprovedByACCT($armc_id)) {
            	   	$this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_READY_FOR_INVOICE);
                  	$this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_READY);
        	      	}
					} break;
				}
			}break;

			case "invoice" : {
				switch ($by) {
					case "AM" : {
     			      $this->atmDB->WriteARMCAction($armc_id, ARMC_ACTION_TYPE_AM_APPROVED_INVOICE);
	               $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_AM_APPROVED_INVOICE);
     			      if ($this->__ARMCIsApprovedByAM($armc_id)) {
              			if ($this->__ARMCRequiresAEApproval($armc_id)) {
	        	            $this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_AE_APPROVAL);
   		               $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_AE_APPROVAL);
     	   		         SendApprovalAlert($armc_id, ATM_MESSAGE_TYPE_AE_APPROVAL);
	                 	} else if ($this->__ARMCRequiresACCTApproval($armc_id)) {
                  	   $this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_ACCT_APPROVAL);
		                  $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_ACCT_APPROVAL);
      		         	SendApprovalAlert($armc_id, ATM_MESSAGE_TYPE_ACCT_APPROVAL);
            			} else {
            				$this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_READY_FOR_INVOICE);
            				$this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_READY);
							}
     			      }
					} break;

					case "AE" : {
		            $this->atmDB->WriteARMCAction($armc_id, ARMC_ACTION_TYPE_AE_APPROVED_INVOICE);
      		      $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_AE_APPROVED_INVOICE);
            		if ($this->__ARMCIsApprovedByAE($armc_id)) {
            			if ($this->__ARMCRequiresACCTApproval($armc_id)) {
			               $this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_ACCT_APPROVAL);
   	   		         $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_ACCT_APPROVAL);
      	      		   SendApprovalAlert($armc_id, ATM_MESSAGE_TYPE_ACCT_APPROVAL);
            			} else {
            				$this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_READY_FOR_INVOICE);
            				$this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_READY);
            			}
		            }
					} break;

					case "ACCT" : {
						$this->atmDB->WriteARMCAction($armc_id, ARMC_ACTION_TYPE_ACCT_APPROVED_INVOICE);
			         $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_ACCT_APPROVED_INVOICE);
            		if ($this->__ARMCIsApprovedByACCT($armc_id)) {
            			$this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_READY_FOR_INVOICE);
                  	$this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_READY);
            		}
					} break;
				}
			}break;
		}
   }

   /**
   * __RecordReject()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.2 - Wed Apr 11 16:23:54 PDT 2007
   */
   private function __RecordReject($armc_id, $section, $by, $comment="")
   {
		switch ($section) {
			case "header" : {
				switch ($by) {
					case "AM" : {
		            $this->atmDB->DeleteARMCAction($armc_id, ARMC_ACTION_TYPE_AM_APPROVED_HEADER);
		            $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_ACCT_DENIED_HEADER, $comment);
		            $this->atmDB->WriteARMCComment($armc_id, ARMC_COMMENT_TYPE_AM_DENIED_HEADER, $comment);
		            $this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_EDIT);
		            $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_DATA_ENTRY);
					} break;

					case "AE" : {
        			   $this->atmDB->DeleteARMCAction($armc_id, ARMC_ACTION_TYPE_AE_APPROVED_HEADER);
		            $this->atmDB->DeleteARMCAction($armc_id, ARMC_ACTION_TYPE_AM_APPROVED_HEADER);
        			   $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_AE_DENIED_HEADER, $comment);
		            $this->atmDB->WriteARMCComment($armc_id, ARMC_COMMENT_TYPE_AE_DENIED_HEADER, $comment);
        			   $this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_AM_APPROVAL);
		            $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_AM_APPROVAL);
        			   SendApprovalAlert($armc_id, ATM_MESSAGE_TYPE_AM_APPROVAL);
					}break;

					case "ACCT" : {
		            $this->atmDB->DeleteARMCAction($armc_id, ARMC_ACTION_TYPE_ACCT_APPROVED_HEADER);
        			   $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_ACCT_DENIED_HEADER, $comment);
		            $this->atmDB->WriteARMCComment($armc_id, ARMC_COMMENT_TYPE_ACCT_DENIED_HEADER, $comment);
      		      if ($this->__ARMCRequiresAEApproval($armc_id)) {
            		   $this->atmDB->DeleteARMCAction($armc_id, ARMC_ACTION_TYPE_AE_APPROVED_HEADER);
		               $this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_AE_APPROVAL);
      		         $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_AE_APPROVAL);
            		   SendApprovalAlert($armc_id, ATM_MESSAGE_TYPE_AE_APPROVAL);
		            }else{
      		         $this->atmDB->DeleteARMCAction($armc_id, ARMC_ACTION_TYPE_AM_APPROVED_HEADER);
            		   $this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_AM_APPROVAL);
		               $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_AM_APPROVAL);
      		         SendApprovalAlert($armc_id, ATM_MESSAGE_TYPE_AM_APPROVAL);
            		}
					} break;
				}
			} break;

			case "invoice" : {
				switch ($by) {
					case "AM" : {
			         $this->atmDB->DeleteARMCAction($armc_id, ARMC_ACTION_TYPE_AM_APPROVED_INVOICE);
         			$this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_ACCT_DENIED_INVOICE, $comment);
				      $this->atmDB->WriteARMCComment($armc_id, ARMC_COMMENT_TYPE_AM_DENIED_INVOICE, $comment);
            		$this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_EDIT);
					   $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_EDIT);
					} break;

					case "AE" : {
		            $this->atmDB->DeleteARMCAction($armc_id, ARMC_ACTION_TYPE_AE_APPROVED_INVOICE);
            		$this->atmDB->DeleteARMCAction($armc_id, ARMC_ACTION_TYPE_AM_APPROVED_INVOICE);
      		      $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_AE_DENIED_INVOICE, $comment);
		            $this->atmDB->WriteARMCComment($armc_id, ARMC_COMMENT_TYPE_AE_DENIED_INVOICE, $comment);
            		$this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_AM_APPROVAL);
      		      $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_AM_APPROVAL);
		            SendApprovalAlert($armc_id, ATM_MESSAGE_TYPE_AM_APPROVAL);
					} break;

					case "ACCT" : {
            		$this->atmDB->DeleteARMCAction($armc_id, ARMC_ACTION_TYPE_ACCT_APPROVED_INVOICE);
		            $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_ACCT_DENIED_INVOICE, $comment);
      		      $this->atmDB->WriteARMCComment($armc_id, ARMC_COMMENT_TYPE_ACCT_DENIED_INVOICE, $comment);
            		if ($this->__ARMCRequiresAEApproval($armc_id)) {
		               $this->atmDB->DeleteARMCAction($armc_id, ARMC_ACTION_TYPE_AE_APPROVED_INVOICE);
      		         $this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_AE_APPROVAL);
            		   $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_AE_APPROVAL);
		               SendApprovalAlert($armc_id, ATM_MESSAGE_TYPE_AE_APPROVAL);
      		      }else{
            		   $this->atmDB->DeleteARMCAction($armc_id, ARMC_ACTION_TYPE_AM_APPROVED_INVOICE);
		               $this->atmDB->SetARMCStatus($armc_id, ARMC_STATUS_AM_APPROVAL);
      		         $this->atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_AM_APPROVAL);
            		   SendApprovalAlert($armc_id, ATM_MESSAGE_TYPE_AM_APPROVAL);
		            }
					} break;
				}
			} break;
		}
   }

   /**
   * SaveApproval()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.2 - Wed Apr 11 14:28:43 PDT 2007
   */
   public function SaveApproval()
   {
   	// Logic to chk for Billing Amount > Proposal Amount and having client email in comment
   	$billing_domain = Hb_App_ObjectHelper::GetMapper('Hb_App_Billing_BillingReport')->Find($this->__o["armc_id"]);
   	
   	if ($billing_domain->isRequireStudy() && $billing_domain->isProposedAmountDifferent() && !$billing_domain->HasProposalToInvoiceAdjustmentApproval())
   	{
   		header("Location: ?action=add_comment&armc_id=".$this->__o['armc_id']."&armc_comment_type_id=" . ARMC_COMMENT_TYPE_INVOICE_ADJUSTMENT_APPROVAL ."&armc_require_proposal_to_invoice_approval=1");
   		exit;   		
   	}
   	
   	switch ($this->__o["approve_section"]) {
			case "header" : {
				switch ($this->__o["header_approve_by"]) {
					case "AM" : {
			         if ($this->__o['header_approve']) {
         				$error = $this->__CheckHeader($this->__o["armc_id"]);
			         	if ($error == "") {
			         		$this->__RecordApproval($this->__o["armc_id"], "header", "AM");
			         	}
			         } else {
							$this->__RecordReject($this->__o["armc_id"], "header", "AM", $this->__o["header_comment"]);
			         }
					} break;

					case "AE" : {
			         if ($this->__o['header_approve']) {
			         	$this->__RecordApproval($this->__o["armc_id"], "header", "AE");
			         }else{
			         	$this->__RecordReject($this->__o["armc_id"], "header", "AE", $this->__o["header_comment"]);
         			}
					}break;

					case "ACCT" : {
			         if ($this->__o['header_approve']) {
							$error = $this->__CheckHeader($this->__o["armc_id"]);
			         	if ($error == "") {
			         		$this->__RecordApproval($this->__o["armc_id"], "header", "ACCT");
			         	}
			         } else {
			         	$this->__RecordReject($this->__o["armc_id"], "header", "ACCT", $this->__o["header_comment"]);
			         }
					}break;
				}
			}break;
			case "invoice" : {
				switch ($this->__o["invoice_approve_by"]) {
					case "AM" : {
			         if ($this->__o['invoice_approve']) {
						   $error = $this->__CheckInvoice($this->__o["armc_id"]);
			         	if ($error == "") {
			         		$this->__RecordApproval($this->__o["armc_id"], "invoice", "AM");
			         	}
         			}else{
         				$this->__RecordReject($this->__o["armc_id"], "invoice", "AM");
				      }
					} break;

					case "AE" : {
						if ($this->__o['invoice_approve']) {
							$this->__RecordApproval($this->__o["armc_id"], "invoice", "AE");
			         } else {
			         	$this->__RecordReject($this->__o["armc_id"], "invoice", "AE", $this->__o["invoice_comment"]);
			         }
					} break;

					case "ACCT" : {
			         if ($this->__o['invoice_approve']) {
						   $error = $this->__CheckInvoice($this->__o["armc_id"]);

			         	if ($error == "") {
			         		$this->__RecordApproval($this->__o["armc_id"], "invoice", "ACCT");
			         	}
         			} else {
         				$this->__RecordReject($this->__o["armc_id"], "invoice", "ACCT", $this->__o["invoice_comment"]);
			         }
					} break;
				}
			}break;
		}
		$this->LoadEncryption();
   	header("Location: ?e=".$this->encrypt->Encrypt("action=display_armc_details&armc_id=".$this->__o['armc_id']."&dont_log_view=1".$error));
   	exit;
   }

   /**
   * SaveInvoice()
   *
   * @param
   * @todo NOT YET COMPLETED
   * @return
   * @since  - 2.0.2 - Thu Apr 12 12:28:59 PDT 2007
   */
   public function SaveInvoice()
   {
   	$this->atmDB->WriteARMCEvent($this->__o["armc_id"], ARMC_EVENT_TYPE_UPDATED_INVOICE_DETAILS, "Date:".$this->__o["invoice_date"].", PO:".$this->__o["po_number"].", Job:".$this->__o["job_number"].", PM:".$this->__o["pm_name"].", Memo:".$this->__o["invoice_memo"]);
	   if ($this->__o["invoice_date"]=="")
   	   $this->__o["invoice_date"]="NULL";
   	else
      	$this->__o["invoice_date"] = "'".$this->__o["invoice_date"]."'";

   	//echo ("Invoice date : ".$this->__o["invoice_date"]);
   	$this->atmDB->SetARMCTransactionDate($this->__o["armc_id"], $this->__o["invoice_date"]);
   	$this->__SetARMCAttr($this->__o["armc_id"], "PONUMBER", $this->__o["po_number"]);
	   $this->__SetARMCAttr($this->__o["armc_id"], "JOBNUMBER", $this->__o["job_number"]);
   	$this->__SetARMCAttr($this->__o["armc_id"], "PMNAME", $this->__o["pm_name"]);
   	$this->__SetARMCAttr($this->__o["armc_id"], "INVMEMO", $this->__o["invoice_memo"]);
   	//$atmDB->SetARMCComment($this->__o["armc_id"], ARMC_COMMENT_TYPE_INVOICE_MEMO, $this->__o["invoice_memo"]);

	   if ($this->__o["i_invoice_approve"] == 1) {
   	   $this->__o["invoice_approve"] = 1;
	      $this->__o["invoice_approve_by"] = $this->__o["i_invoice_approve_by"];
      	$this->__o["approve_section"] = "invoice";
   	   $this->SaveApproval();
	   } else {
   		$this->LoadEncryption();
   	   header("Location: ?e=".$this->encrypt->Encrypt("action=display_armc_details&armc_id=".$this->__o["armc_id"]."&dont_log_view=1"));
	   }
   }
   
   /**
    * Public fn to see if the ARMC is require AE approval
    *
    * @param int $armc_id
    * @return boolean
    */
   public function ARMCRequiresAEApproval($armc_id)
   {
   	return $this->__ARMCRequiresAEApproval($armc_id);
   }
   
   /**
    * Public fn to see if ArmC is require ACCT approval 
    *
    * @param int $armc_id
    * @return boolean
    */
   public function ARMCRequiresACCTApproval($armc_id)
   {
   	return $this->__ARMCRequiresACCTApproval($armc_id);
   }
   
}//END OF CLASS ARMCFunctions
//**************************************************************************************************
//**************************************************************************************************

/**
* SetARMCAttr()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jul 10 13:25:43 PDT 2006
*/
function SetARMCAttr($armc_id, $armc_attr_name, $armc_attr_value)
{
   $atmDB = new atmDB();
   if ($atmDB->GetARMCAttr($armc_id, $armc_attr_name)!==false)
      $atmDB->UpdateARMCAttr($armc_id, $armc_attr_name, $armc_attr_value);
   else
      $atmDB->InsertARMCAttr($armc_id, $armc_attr_name, $armc_attr_value);
}

/**
* SetARMCGroupAttr()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jul 10 14:12:21 PDT 2006
*/
function SetARMCGroupAttr($armc_group_id, $armc_group_attr_name, $armc_group_attr_value)
{
   $atmDB = new atmDB();
   if ($atmDB->GetARMCGroupAttr($armc_group_id, $armc_group_attr_name)!==false)
      $atmDB->UpdateARMCGroupAttr($armc_group_id, $armc_group_attr_name, $armc_group_attr_value);
   else
      $atmDB->InsertARMCGroupAttr($armc_group_id, $armc_group_attr_name, $armc_group_attr_value);
}

/**
* SetARMCContact()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jul 10 13:39:04 PDT 2006
*/
//Moved to the ContactManager business layer.
/*
function SetARMCContact($armc_id, $info)
{
   $atmDB = new atmDB();

	if (($armc_contact_id=$atmDB->isARMCContact($armc_id, $info["armc_contact_type_id"]))!==false)
      $atmDB->UpdateARMCContact($armc_contact_id, $info);
   else
      $atmDB->InsertARMCContact($armc_id, $info);
}
*/
/**
* SetARMCUser()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jul 10 14:19:16 PDT 2006
*/
function SetARMCUser($armc_id, $role_id, $user_id)
{
   $atmDB = new atmDB();
   if (($armc_user_id=$atmDB->isARMCUser($armc_id, $role_id, $user_id))!==false)
      $atmDB->UpdateARMCUser($armc_user_id, $armc_id, $role_id, $user_id);
   else
      $atmDB->InsertARMCUser($armc_id, $role_id, $user_id);
}

/**
* AddAccount()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Nov 29 14:25:07 PST 2005
*/
function AddAccount($i)
{
	$oracleDB = new oracleDB();
//	$settings = $oracleDB->GetSystemParams();

	$oracleDB->SetOrgID($oracleDB->_settings['ORG_ID']);

	if ($tmp = $oracleDB->CreateCustAccount($i['account']['account_name'])) {
		$cust_account_id = $tmp['x_cust_account_id'];
		$party_id = $tmp['x_party_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreateLocation($i['contact'])) {
		$location_id = $tmp['x_location_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreatePartySite($party_id, $location_id)) {
		$party_site_id = $tmp['x_party_site_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreateCustAcctSite($cust_account_id, $party_site_id)) {
		$cust_acct_site_id = $tmp['x_cust_acct_site_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreateCustAcctSiteUseBillTo($cust_acct_site_id, substr($i['contact']['address1'], 0, 30))) {
		$bill_to_site_use_id = $tmp['x_site_use_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreateCustAcctSiteUseShipTo($cust_acct_site_id, substr($i['contact']['address1'], 0, 30), $bill_to_site_use_id)) {
		$ship_to_site_use_id = $tmp['x_site_use_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreatePerson($i['contact'])) {
		$contact_party_id = $tmp['x_party_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreateOrgContact($contact_party_id, $party_id)) {
		$org_contact_party_id = $tmp['x_party_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreateCustAccountRole($org_contact_party_id, $cust_account_id, $cust_acct_site_id, 'Y')) {
		$cust_account_role_id = $tmp['x_cust_account_role_id'];
	}else{
		return false;
	}

	if ($i['contact']['phone'] !== '') {
		if ($tmp = $oracleDB->CreatePhoneContactPoint($org_contact_party_id, $i['contact']['phone'], 'GEN')) {
			$phone_1_contact_point_id = $tmp['x_contact_point_id'];
		}else{
			return false;
		}
	}

	if ($i['contact']['fax'] !== '') {
		if ($tmp = $oracleDB->CreatePhoneContactPoint($org_contact_party_id, $i['contact']['fax'], 'FAX')) {
			$fax_contact_point_id = $tmp['x_contact_point_id'];
		}else{
			return false;
		}
	}
	if ($i['contact']['email'] !== '') {
		if ($tmp = $oracleDB->CreateEmailContactPoint($org_contact_party_id, $i['contact']['email'])) {
			$email_contact_point_id = $tmp['x_contact_point_id'];
		}else{
			return false;
		}
	}
	if ($tmp = $oracleDB->CreateRoleResponsibility($cust_account_role_id, 'BILL_TO')) {
		$responsibility_id = $tmp['x_responsibility_id'];
	}else{
		return false;
	}
	$oracleDB->commit();
	$ret['oracle_account_id'] = $cust_account_id;
	$ret['ora_contact_id'] = $cust_account_role_id;
	$ret['ora_site_id'] = $cust_acct_site_id;
	return $ret;

}

/**
* AddSite()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Dec 08 14:06:47 PST 2005
*/
function AddSite($i)
{
	$oracleDB = new oracleDB();

	//$settings = $oracleDB->GetSystemParams();
	$oracleDB->SetOrgID($oracleDB->_settings['ORG_ID']);

	$cust_account_id = $i['account']['oracle_account_id'];
	$party_id = $oracleDB->GetPartyIDByAccountID($cust_account_id);
	if (!$party_id)
		return false;

	if ($tmp = $oracleDB->CreateLocation($i['contact'])) {
		$location_id = $tmp['x_location_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreatePartySite($party_id, $location_id)) {
		$party_site_id = $tmp['x_party_site_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreateCustAcctSite($cust_account_id, $party_site_id)) {
		$cust_acct_site_id = $tmp['x_cust_acct_site_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreateCustAcctSiteUseBillTo($cust_acct_site_id, substr($i['contact']['address1'], 0, 30))) {
		$bill_to_site_use_id = $tmp['x_site_use_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreateCustAcctSiteUseShipTo($cust_acct_site_id, substr($i['contact']['address1'], 0,30), $bill_to_site_use_id)) {
		$ship_to_site_use_id = $tmp['x_site_use_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreatePerson($i['contact'])) {
		$contact_party_id = $tmp['x_party_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreateOrgContact($contact_party_id, $party_id)) {
		$org_contact_party_id = $tmp['x_party_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreateCustAccountRole($org_contact_party_id, $cust_account_id, $cust_acct_site_id,'Y')) {
		$cust_account_role_id = $tmp['x_cust_account_role_id'];
	}else{
		return false;
	}

	if ($i['contact']['phone'] !== '') {
		if ($tmp = $oracleDB->CreatePhoneContactPoint($org_contact_party_id, $i['contact']['phone'], 'GEN')) {
			$phone_1_contact_point_id = $tmp['x_contact_point_id'];
		}else{
			return false;
		}
	}

	if ($i['contact']['fax'] !== '') {
		if ($tmp = $oracleDB->CreatePhoneContactPoint($org_contact_party_id, $i['contact']['fax'], 'FAX')) {
			$fax_contact_point_id = $tmp['x_contact_point_id'];
		}else{
			return false;
		}
	}


	if ($i['contact']['email'] !== '') {
		if ($tmp = $oracleDB->CreateEmailContactPoint($org_contact_party_id, $i['contact']['email'])) {
			$email_contact_point_id = $tmp['x_contact_point_id'];
		}else{
			return false;
		}
	}

	if ($tmp = $oracleDB->CreateRoleResponsibility($cust_account_role_id, 'BILL_TO')) {
		$responsibility_id = $tmp['x_responsibility_id'];
	}else{
		return false;
	}

	$oracleDB->commit();
	$ret['ora_contact_id'] = $cust_account_role_id;
	$ret['ora_site_id'] = $cust_acct_site_id;
	return $ret;
}

/**
* AddContact()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Dec 08 15:11:23 PST 2005
*/
function AddContact($i)
{
	$oracleDB = new oracleDB();
	//$settings = $oracleDB->GetSystemParams();

	$oracleDB->SetOrgID($oracleDB->_settings['ORG_ID']);

	$cust_account_id = $i['account']['oracle_account_id'];
	$party_id = $oracleDB->GetPartyIDByAccountID($cust_account_id);
	$cust_acct_site_id = $i['contact']['ora_site_id'];

	if ($tmp = $oracleDB->CreatePerson($i['contact'])) {
		$contact_party_id = $tmp['x_party_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreateOrgContact($contact_party_id, $party_id)) {
		$org_contact_party_id = $tmp['x_party_id'];
	}else{
		return false;
	}

	if ($tmp = $oracleDB->CreateCustAccountRole($org_contact_party_id, $cust_account_id, $cust_acct_site_id, 'N')) {
		$cust_account_role_id = $tmp['x_cust_account_role_id'];
	}else{
		return false;
	}

	if ($i['contact']['phone'] !== '') {
		if ($tmp = $oracleDB->CreatePhoneContactPoint($org_contact_party_id, $i['contact']['phone'], 'GEN')) {
			$phone_1_contact_point_id = $tmp['x_contact_point_id'];
		}else{
			return false;
		}
	}

	if ($i['contact']['fax'] !== '') {
		if ($tmp = $oracleDB->CreatePhoneContactPoint($org_contact_party_id, $i['contact']['fax'], 'FAX')) {
			$fax_contact_point_id = $tmp['x_contact_point_id'];
		}else{
			return false;
		}
	}


	if ($i['contact']['email'] !== '') {
		if ($tmp = $oracleDB->CreateEmailContactPoint($org_contact_party_id, $i['contact']['email'])) {
			$email_contact_point_id = $tmp['x_contact_point_id'];
		}else{
			return false;
		}
	}

	if ($tmp = $oracleDB->CreateRoleResponsibility($cust_account_role_id, 'BILL_TO')) {
		$responsibility_id = $tmp['x_responsibility_id'];
	}else{
		return false;
	}

	$oracleDB->commit();
	$ret['ora_contact_id'] = $cust_account_role_id;
	return $ret;
}

	/**
	* ARMCIsApprovedByAM()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Feb 01 14:45:51 PST 2006
	*/
	function ARMCIsApprovedByAM($armc_id)
	{	
	   $atmDB = new atmDB();

	   $sections = ($atmDB->ARMCIsApproved($armc_id, ARMC_ACTION_TYPE_AM_APPROVED_HEADER)&&$atmDB->ARMCIsApproved($armc_id, ARMC_ACTION_TYPE_AM_APPROVED_INVOICE));

      if ($sections) {
         if ($atmDB->GetARMCTypeID($armc_id) == ARMC_TYPE_BR_STUDY) {
            $study_id = $atmDB->GetARMCAttr($armc_id, "STUDY_ID");
            
            $study_costs = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_Study')->Find($study_id)->GetStudyCostsActive();
               
            foreach($study_costs AS $cost) {                        	               
               if (!($cost->IsAMApproved() || $cost->IsAMRejected()))
                  return false;                                
            }
         }
         
      	$rst = $atmDB->GetARMCLines($armc_id);
         while ($line = mysql_fetch_assoc($rst)) {
            if (!$atmDB->ARMCLineIsApproved($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AM_APPROVED_LINE))
               return false;
         }
         return true;
      }else{
         return false;
      }
	}

	/**
	* ARMCIsApprovedByAE()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Feb 01 14:45:51 PST 2006
	*/
	function ARMCIsApprovedByAE($armc_id)
	{		
	   $atmDB = new atmDB();

	   $sections = ($atmDB->ARMCIsApproved($armc_id, ARMC_ACTION_TYPE_AE_APPROVED_HEADER)&&$atmDB->ARMCIsApproved($armc_id, ARMC_ACTION_TYPE_AE_APPROVED_INVOICE));

      if ($sections) {
         if ($atmDB->GetARMCTypeID($armc_id) == ARMC_TYPE_BR_STUDY) {
            $study_id = $atmDB->GetARMCAttr($armc_id, "STUDY_ID");
            
            $study_costs = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_Study')->Find($study_id)->GetStudyCostsActive();
                 
            foreach($study_costs AS $cost) 
            {                        	               
               if (!($cost->IsAMApproved() || $cost->IsAMRejected()))
                  return false;                                
            }
            
         }
      	$rst = $atmDB->GetARMCLines($armc_id);
         while ($line = mysql_fetch_assoc($rst)) {
            if (!$atmDB->ARMCLineIsApproved($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AE_APPROVED_LINE))
               return false;
         }
         return true;
      }else{
         return false;
      }
	}

		/**
	* ARMCIsApprovedByACCT()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Wed Feb 01 14:45:51 PST 2006
	*/
	function ARMCIsApprovedByACCT($armc_id)
	{		
	   $atmDB = new atmDB();

	   $sections = ($atmDB->ARMCIsApproved($armc_id, ARMC_ACTION_TYPE_ACCT_APPROVED_HEADER)&&$atmDB->ARMCIsApproved($armc_id, ARMC_ACTION_TYPE_ACCT_APPROVED_INVOICE));

      if ($sections) {
         if ($atmDB->GetARMCTypeID($armc_id) == ARMC_TYPE_BR_STUDY) {
            $study_id = $atmDB->GetARMCAttr($armc_id, "STUDY_ID");
            
            $study_costs = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_Study')->Find($study_id)->GetStudyCostsActive();
                 
            foreach($study_costs AS $cost) 
            {                        	               
               if (!($cost->IsAMApproved() || $cost->IsAMRejected()))
                  return false;                                
            }                        
         }
         $rst = $atmDB->GetARMCLines($armc_id);
         while ($line = mysql_fetch_assoc($rst)) {
            if (!$atmDB->ARMCLineIsApproved($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_ACCT_APPROVED_LINE))
               return false;
         }
         return true;
      }else{
         return false;
      }
	}

/**
* RequireStudy()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Jan 20 16:52:01 PST 2006
*/
function RequireStudy($type)
{
   return ($type==ARMC_TYPE_BR_STUDY || $type==ARMC_TYPE_RT_STUDY || $type==ARMC_TYPE_CM_STUDY);
}

/**
* GetAccountingPeriod()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Apr 05 16:12:40 PDT 2006
*/
function GetAccountingPeriod()
{
   $atmDB = new atmDB();
   $a = $atmDB->GetARMCSetting("ACCOUNTING_PERIOD");
   return ($a?$a["armc_setting_value"]:date("Y-m"));
}

///**
//* CheckPartner()
//*
//* @param
//* @param -
//* @return
//* @throws
//* @access
//* @global
//* @since  - Fri Jan 20 17:27:15 PST 2006
//*/
//function CheckPartner($partner)
//{
//   return $partner;
//}

///**
//* CheckStudy()
//*
//* @param
//* @param -
//* @return
//* @throws
//* @access
//* @global
//* @since  - Fri Jan 20 17:21:57 PST 2006
//*/
//function CheckStudy($o)
//{
//   $study = new studyDB();
//   $study->_study = $o['study_id'];
//
//   if (!$study->onfile($o)) {
//      $o['study_type_id'] = 1;
//      $o['study_datasource_id'] = 1;
//      $o['start_date'] = date("Y-m-d");
//      $o['created_by'] = $_SESSION['admin_id'];
//
//      $study->GetNetMr($o);
//
//      $study->AddStudy($o);
//
//      $header = $study->getHeaderDetail();
//      $partner = new partnerDB($header['partner_id']);
//
//      $partner = CheckPartner($partner);
//
//      $o['login'] = $partner->getAM();
//      $o['role_id'] = PRIMARY_ACCT_MGR;
//      $study->setRole($o);
//      $o['alert_level_id'] = STUDY_ALERT_GREEN;
//      $study->setAlertLevel($o);
//
//  }
//
//  $study->setStatus(STUDY_CLOSED);
//
//}

/**
* IsCreditMemo()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 13 16:54:55 PST 2006
*/
function IsCreditMemo($armc_type_id)
{
   return
      (($armc_type_id == ARMC_TYPE_CM_STUDY) ||
      ($armc_type_id == ARMC_TYPE_CM_TR) ||
      ($armc_type_id == ARMC_TYPE_CM_LI) ||
      ($armc_type_id == ARMC_TYPE_CM_SB) ||
      ($armc_type_id == ARMC_TYPE_CM_OT) ||
      ($armc_type_id == ARMC_TYPE_VAP_CM) ||
      ($armc_type_id == ARMC_TYPE_ERS_CM) ||
      ($armc_type_id == ARMC_TYPE_RSA_CM) ||
      ($armc_type_id == ARMC_TYPE_ISS_CM) ||
      ($armc_type_id == ARMC_TYPE_EFP_CM) ||
      ($armc_type_id == ARMC_TYPE_EFS_CM));
}

/**
* IsInvoiced()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Jul 21 14:05:27 PDT 2006
*/
function IsInvoiced($armc_status_id)
{
   return in_array($armc_status_id, array(ARMC_STATUS_INVOICED, ARMC_STATUS_INVOICED_MANUALLY, ARMC_STATUS_INVOICED_PARTIAL_BALANCE, ARMC_STATUS_INVOICED_BALANCED));
}

/**
* IsRetainer()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Feb 22 16:15:54 PST 2006
*/
function IsRetainer($type)
{
   return
      ($type == ARMC_TYPE_RT_STUDY ||
       $type == ARMC_TYPE_RT_LI ||
       $type == ARMC_TYPE_RT_OT ||
       $type == ARMC_TYPE_RT_SB ||
       $type == ARMC_TYPE_RT_TR ||
       $type == ARMC_TYPE_VAP_RT ||
       $type == ARMC_TYPE_ERS_RT ||
       $type == ARMC_TYPE_RSA_RT ||
       $type == ARMC_TYPE_ISS_RT ||
       $type == ARMC_TYPE_EFP_RT ||
       $type == ARMC_TYPE_EFS_RT);
}

/**
* RequireAMAEApproval()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed May 17 15:42:21 PDT 2006
*/
function RequireAMAEApproval($type)
{
//   return
//      !($type == ARMC_TYPE_BR_LI ||
//       $type == ARMC_TYPE_CM_LI ||
//       $type == ARMC_TYPE_RT_LI ||
//       $type == ARMC_TYPE_BR_TR ||
//       $type == ARMC_TYPE_CM_TR ||
//       $type == ARMC_TYPE_RT_TR);
	return true;
}

///**
//* IsGMIUser()
//*
//* @param
//* @todo NOT YET COMPLETED
//* @return
//* @since  - Wed Aug 09 09:07:28 PDT 2006
//*/
//function IsGMIUser()
//{
//   return ($_SESSION["user_type_id"]==1);
//}
//
///**
//* IsEXTUser()
//*
//* @param
//* @todo NOT YET COMPLETED
//* @return
//* @since  - Wed Aug 09 09:11:43 PDT 2006
//*/
//function IsEXTUser()
//{
//   return ($_SESSION["user_type_id"]==2);
//}
//
///**
//* UserCanSeeAccount()
//*
//* @param
//* @todo NOT YET COMPLETED
//* @return
//* @since  - Wed Aug 09 09:12:05 PDT 2006
//*/
//function UserCanSeeAccount($account_id)
//{
//   return ($_SESSION["user_primary_account_id"]==$account_id);
//}

/**
* IsVisibleByEXT()
*
* @param
* @todo NOT YET COMPLETED
* @return
* @since  - Wed Aug 09 09:13:47 PDT 2006
*/
function IsVisibleByEXT($armc_status_id)
{
   switch ($armc_status_id) {
   case ARMC_STATUS_ACCT_APPROVAL :
   case ARMC_STATUS_READY_FOR_INVOICE :
   case ARMC_STATUS_INVOICED :
   case ARMC_STATUS_INVOICED_MANUALLY :
   case ARMC_STATUS_INVOICED_PARTIAL_BALANCE :
   case ARMC_STATUS_INVOICED_BALANCED :
      return true;
      break;

   default:
      return false;
      break;
   }
}

///**
//* ActionAllowedForEXT()
//*
//* @param
//* @todo NOT YET COMPLETED
//* @return
//* @since  - Wed Aug 09 12:12:13 PDT 2006
//*/
//function ActionAllowedForEXT($action)
//{
//   switch ($action) {
//      case "display_group_details" :
//      case "display_armc_details" :
//      case "default" :
//      case "" : return true; break;
//
//      default: return false; break;
//   }
//}

/**
* OverrideARMCHeader()
*
* @param
* @todo NOT YET COMPLETED
* @return
* @since  - Wed Aug 09 12:31:59 PDT 2006
*/
function OverrideARMCHeader()
{
   switch ($_SESSION["user_type_id"]) {
   	case 2 : {
         $header[0] = array("width"=>"2%", "sort"=>"yes", "title"=>"++", "field"=>"grouped");
         $header[1] = array("align"=>"left", "width"=>"10%", "sort"=>"yes", "title"=>"Status", "field"=>"armc_status_description");
         $header[2] = array("align"=>"left", "width"=>"6%", "sort"=>"yes", "title"=>"BR #", "field"=>"armc_id");
         $header[3] = array("align"=>"left", "width"=>"6%", "sort"=>"yes", "title"=>"Study ID", "field"=>"study_id");
         $header[4] = array("align"=>"left", "width"=>"15%", "sort"=>"yes", "title"=>"Study Name", "field"=>"study_name");
         $header[5] = array("align"=>"center", "width"=>"8%", "sort"=>"yes", "title"=>"BR Date", "field"=>"armc_date");
         $header[6] = array("align"=>"center", "width"=>"10%", "sort"=>"yes", "title"=>"Invoice #", "field"=>"transaction_number");
         $header[7] = array("align"=>"center", "width"=>"10%", "sort"=>"yes", "title"=>"Invoice Date", "field"=>"transaction_date");
         $header[8] = array("align"=>"right", "width"=>"10%", "sort"=>"yes", "title"=>"Amount", "field"=>"amount");

   	}break;
   }
   return $header;
}

/**
* ARMCOverrideAccountID()
*
* @param
* @todo NOT YET COMPLETED
* @return
* @since  - Wed Aug 09 13:00:41 PDT 2006
*/
function ARMCOverrideAccountID()
{
   return GetExternalPrimaryAccount();
}

/**
* OverrideStatus()
*
* @param
* @todo NOT YET COMPLETED
* @return
* @since  - Wed Aug 09 13:02:30 PDT 2006
*/
function OverrideStatus($status_list)
{
   $ext_status_list = array(
      ARMC_STATUS_ACCT_APPROVAL=>$status_list[ARMC_STATUS_ACCT_APPROVAL],
      ARMC_STATUS_READY_FOR_INVOICE=>$status_list[ARMC_STATUS_READY_FOR_INVOICE],
      ARMC_STATUS_INVOICED=>$status_list[ARMC_STATUS_INVOICED],
      ARMC_STATUS_INVOICED_MANUALLY=>$status_list[ARMC_STATUS_INVOICED_MANUALLY],
      ARMC_STATUS_INVOICED_PARTIAL_BALANCE=>$status_list[ARMC_STATUS_INVOICED_PARTIAL_BALANCE],
      ARMC_STATUS_INVOICED_BALANCED=>$status_list[ARMC_STATUS_INVOICED_BALANCED]);
   return array_intersect_assoc($status_list, $ext_status_list);
}

/**
* OverrideSearchStatus()
*
* @param
* @todo NOT YET COMPLETED
* @return
* @since  - Wed Aug 09 13:33:30 PDT 2006
*/
function OverrideSearchStatus($search_status_id)
{
   if ($search_status_id[0]==0) {
      return array(ARMC_STATUS_ACCT_APPROVAL, ARMC_STATUS_READY_FOR_INVOICE, ARMC_STATUS_INVOICED_MANUALLY, ARMC_STATUS_INVOICED, ARMC_STATUS_INVOICED_PARTIAL_BALANCE, ARMC_STATUS_INVOICED_BALANCED);
   } else {
      return $search_status_id;
   }
}

/**
* CheckOracleInvoice()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 13 12:01:32 PST 2006
*/
function CheckOracleInvoice($trx_number, $armc_id)
{
   $oracleDB = new oracleDB();
   $atmDB = new atmDB();
//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");
   $account_details = HBRPCCall('acm','GetAccountDetail', array("account_id"=>$atmDB->GetARMCAttr($armc_id, "ACCOUNT_ID")));
   //print_r($account_details);
   $oracct = "";
   foreach ($account_details["attr"] AS $attr){
      if ($attr["account_attr_name"] == "ARMC_ORA_ACCOUNT_ID") $oracct = $attr["account_attr_value"];
   }
   //print_r($account_details);
   if ($oracct != "") {
      $oracleDB->GetTxnHeader($trx_number);
      if ($ora_header = $oracleDB->fetch_assoc()) {
         if ($ora_header["BILL_TO_CUSTOMER_ID"] == $oracct) {
            $currency_code = $atmDB->GetARMCAttr($armc_id, "I18N_CURRENCY_CODE");
            $exchange_rate = $atmDB->GetARMCAttr($armc_id, "I18N_EXCHANGE_RATE");
            if ($currency_code=="USD" || ($ora_header["INVOICE_CURRENCY_CODE"]==$currency_code && $ora_header["EXCHANGE_RATE"]==$exchange_rate)) {
               $is_credit_memo = IsCreditMemo($atmDB->GetARMCTypeID($armc_id));
               $ora_lines = $oracleDB->GetTxnLines($ora_header["CUSTOMER_TRX_ID"]);
               while ($ora_line = $oracleDB->fetch_assoc()) {
                  if ($is_credit_memo) {
                    $ora_total += $ora_line["QUANTITY_CREDITED"]*$ora_line["UNIT_SELLING_PRICE"];
                  }else{
               	  $ora_total += $ora_line["QUANTITY_INVOICED"]*$ora_line["UNIT_SELLING_PRICE"];
                  }
               }
               $hb_lines = $atmDB->GetARMCLines($armc_id);
               while ($hb_line = mysql_fetch_assoc($hb_lines)) {
            	  $hb_total += $hb_line["actual_amount_i18n"];
               }

               if (round(abs($ora_total), 2) == round(abs($hb_total), 2)) {
               return array(
                  "error_code"=>0,
                  "transaction_number"=>$ora_header["TRX_NUMBER"],
                  "transaction_date"=>"'".date("Y-m-d", strtotime($ora_header["TRX_DATE"]))."'",
                  "transaction_id"=>$ora_header["CUSTOMER_TRX_ID"],
                  "transaction_amount"=>$ora_total);
               }else{
                  return array("error_code"=>400, "error_message"=>"The amount on Oracle invoice $trx_number is $".number_format($ora_total, 2).", while the amount on this BR is $".number_format($hb_total, 2));
               }
            }else{
               return array("error_code"=>350, "error_message"=>"Oracle invoice ".$trx_number." was invoiced in ".$ora_header["INVOICE_CURRENCY_CODE"]." at a rate of ".$ora_header["EXCHANGE_RATE"].", while in Hummingbird, it's in $currency_code at a rate of $exchange_rate.");
            }
         }else{
            return array("error_code"=>300, "error_message"=>"Oracle invoice ".$trx_number." does not correspond to ".$account_details["account_name"]);
         }
      }else{
         return array("error_code"=>200, "error_message"=>"Oracle invoice $trx_number not found !!!");
      }
      //$header = $atmDB->GetARMCHeader($armc_id);


   }else{
      return array("error_code"=>100, "error_message"=>"Oracle Account ID not set for ".$account_details["account_name"]);
   }
   return false;
}

/**
* CheckOracleInvoices()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Oct 06 14:42:03 PDT 2006
*/
function CheckOracleInvoices()
{
   //TODO add code
   $atmDB = new atmDB();
   $oracleDB = new oracleDB();

   $rst = $atmDB->GetARMCList(" AND (a.transaction_date BETWEEN '2006-01-01' AND '2006-03-01') AND a.armc_status_id IN (".ARMC_STATUS_INVOICED.", ".ARMC_STATUS_INVOICED_MANUALLY.", ".ARMC_STATUS_INVOICED_PARTIAL_BALANCE.", ".ARMC_STATUS_INVOICED_BALANCED.")", "ORDER BY armc_id");
   while ($armc = mysql_fetch_assoc($rst)) {
//      if ($armc["grouped"]=="true") {
//         $result = CheckOracleGroupInvoice($armc["transaction_number"], $armc["armc_id"]);
//      }else{
//         $result = CheckOracleInvoice($armc["transaction_number"], $armc["armc_id"]);
//      }
//      if ($result["error_code"]!=0) {
//         echo ($armc["armc_type_prefix"]."-".$armc["armc_id"]." : ".$result["error_message"]."<br/>");
//      }else{
//         echo ($armc["armc_type_prefix"]."-".$armc["armc_id"]." : OK<br/>");
//      }
      $trx[$armc["transaction_number"]]["armc"] = $armc["armc_type_prefix"]."-".$armc["armc_id"];
      $trx[$armc["transaction_number"]]["transaction_number"] = $armc["transaction_number"];
      $trx[$armc["transaction_number"]]["hb_amount"] = $armc["amount"];
   }

   $oracleDB->parse("SELECT T.TRX_NUMBER, SUM(L.QUANTITY_INVOICED*L.UNIT_SELLING_PRICE) AS AMOUNT_INVOICED, SUM(L.QUANTITY_CREDITED*L.UNIT_SELLING_PRICE) AS AMOUNT_CREDITED FROM RA_CUSTOMER_TRX_ALL T LEFT JOIN RA_CUSTOMER_TRX_LINES_ALL L ON L.CUSTOMER_TRX_ID = T.CUSTOMER_TRX_ID WHERE T.TRX_DATE BETWEEN '01-JAN-2006' AND '01-MAR-2006' GROUP BY T.TRX_NUMBER");
   $oracleDB->execute();
   while ($o = $oracleDB->fetch_assoc()) {
      $trx[$o["TRX_NUMBER"]]["transaction_number"] = $o["TRX_NUMBER"];
      $trx[$o["TRX_NUMBER"]]["oracle_amount"] = $o["AMOUNT_INVOICED"];
      $trx[$o["TRX_NUMBER"]]["oracle_credit"] = $o["AMOUNT_CREDITED"];
   }

   foreach ($trx AS $transaction) {
      if (abs($transaction["oracle_amount"]) != abs($transaction["hb_amount"])) {
         if (abs($transaction["oracle_credit"]) != abs($transaction["hb_amount"])) {
            echo ("<pre>".print_r($transaction, true)."</pre>");
         }
      }
   }
}

/**
* CheckOracleGroupInvoice()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 13 12:01:32 PST 2006
*/
function CheckOracleGroupInvoice($trx_number, $armc_group_id)
{
   $oracleDB = new oracleDB();
   $atmDB = new atmDB();
//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");
   $account_details = HBRPCCall('acm','GetAccountDetail', array("account_id"=>$atmDB->GetARMCGroupAttr($armc_group_id, "ACCOUNT_ID")));
   //print_r($account_details);
   $oracct = "";
   foreach ($account_details["attr"] AS $attr){
      if ($attr["account_attr_name"] == "ARMC_ORA_ACCOUNT_ID") $oracct = $attr["account_attr_value"];
   }
   //print_r($account_details);
   if ($oracct != "") {
      $oracleDB->GetTxnHeader($trx_number);
      if ($ora_header = $oracleDB->fetch_assoc()) {
         if ($ora_header["BILL_TO_CUSTOMER_ID"]==$oracct) {
            $currency_code = $atmDB->GetARMCGroupAttr($armc_group_id, "I18N_CURRENCY_CODE");
            $exchange_rate = $atmDB->GetARMCGroupAttr($armc_group_id, "I18N_EXCHANGE_RATE");
            if ($currency_code=="USD" || ($ora_header["INVOICE_CURRENCY_CODE"]==$currency_code && $ora_header["EXCHANGE_RATE"]==$exchange_rate)) {
               $is_credit_memo = IsCreditMemo($atmDB->GetARMCGroupTypeID($armc_group_id));
               $ora_lines = $oracleDB->GetTxnLines($ora_header["CUSTOMER_TRX_ID"]);
               while ($ora_line = $oracleDB->fetch_assoc()) {
                  if ($is_credit_memo)
               	   $ora_total += (-1)*$ora_line["QUANTITY_CREDITED"]*$ora_line["UNIT_SELLING_PRICE"];
            	  else
            	      $ora_total += $ora_line["QUANTITY_INVOICED"]*$ora_line["UNIT_SELLING_PRICE"];
               }
               $hb_lines = $atmDB->GetARMCGroupList(" AND a.armc_group_id = '$armc_group_id'", "");
               while ($hb_line = mysql_fetch_assoc($hb_lines)) {
               	$hb_total += $hb_line["amount"]/$exchange_rate;
               }

               if (round($ora_total, 2) == round($hb_total, 2)) {
               return array(
                  "error_code"=>0,
                  "transaction_number"=>$ora_header["TRX_NUMBER"],
                  "transaction_date"=>"'".date("Y-m-d", strtotime($ora_header["TRX_DATE"]))."'",
                  "transaction_id"=>$ora_header["CUSTOMER_TRX_ID"],
                  "transaction_amount"=>$ora_total);
               }else{
                  return array("error_code"=>400, "error_message"=>"The amount on Oracle invoice $trx_number is $".number_format($ora_total, 2).", while the amount on this BR is $".number_format($hb_total, 2));
               }
            }else{
               return array("error_code"=>350, "error_message"=>"Oracle invoice ".$trx_number." was invoiced in ".$ora_header["INVOICE_CURRENCY_CODE"]." at a rate of ".$ora_header["EXCHANGE_RATE"].", while in Hummingbird, it's in $currency_code at a rate of $exchange_rate.");
            }
         }else{
            return array("error_code"=>300, "error_message"=>"Oracle invoice ".$trx_number." does not correspond to ".$account_details["account_name"]);
         }
      }else{
         return array("error_code"=>200, "error_message"=>"Oracle invoice $trx_number not found !!!");
      }
      //$header = $atmDB->GetARMCHeader($armc_id);


   }else{
      return array("error_code"=>100, "error_message"=>"Oracle Account ID not set for ".$account_details["account_name"]);
   }
   return false;
}

/**
* ValidateAccountSearch()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jul 25 11:20:52 PDT 2005
*/
function ValidateAccountSearch($o)
{
   $p = new partnerDB(0);

   $obj_name = 'search_partner_id';

   $func_name = 'ValidateAccountName';

   if (isset($o['obj_name']) && $o['obj_name'] != '') {
      $obj_name = $o['obj_name'];
   }

   if (isset($o['func_name']) && $o['func_name'] != '') {
      $func_name = $o['func_name'];
   }

   if (preg_match("/^[0-9]+$/", $o['account_name'])) {
      $rs = $p->LookupPartnerById($o['account_name']);
   } else {
      $rs = $p->LookupPartnerByName($o['account_name']);
   }

   //no items found
   if ($p->rows == 0) {
      $js = "<script language='javascript'> "
          . "  var p = parent.document.forms[0].".$obj_name."; "
          . "  p.value = 'Account Not Found'; "
          . "</script>";
   } else {
      $js = "<script language='javascript'> "
          . " var o = parent.document.getElementById('td_".$obj_name."'); "
          . " var i = parent.document.getElementById('".$obj_name."'); "
          . " o.innerHTML = '<select name=\'".$obj_name."\' id=\'".$obj_name."\'>"
          . "                    <option value=\'\'>Select Account From List</option> ";

      while ($r = mysql_fetch_assoc($rs)) {
         $js .= " <option value=\'".$r['partner_id']."\'>".$r['partner_id']." - ".addslashes($r['company_name'])."</option>";
      }

      $js .= "  </select> &nbsp; "
          .  "  <a href=\'javascript:void(0)\' onclick=ResetValidation(\'".$obj_name."\',\'".$func_name."(this)\');>Clear</a>'; "
          .  "</script>";
   }

   print $js;

}

/**
* BuildSearchFilter()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Jan 24 15:14:00 PST 2006
*/
function BuildSearchFilter($o)
{
   $filter = "";
   if ($o['search_armc_id']!="") {
      $filter .= " AND (armc_id='".$o["search_armc_id"]."')";
   }
   if ($o['search_study_id']!="") {
      $filter .= " AND (aa_study_id.armc_attr_value LIKE '%".$o['search_study_id']."%')";
   }
   if ($o["search_invoice_number"]!="") {
      $filter .= " AND (a.transaction_number='".$o["search_invoice_number"]."')";
   }
   if ($o['search_account_id']!="") {
      $filter .= " AND (account_id = '".$o['search_account_id']."')";
   }

   if ($o['search_type_id'][0]!=0 || sizeof($o['search_type_id'])>1) {
      $filter .= " AND (a.armc_type_id IN (";
      foreach($o['search_type_id'] as $type_id) {
         $filter .= "$type_id, ";
      }
      $filter = substr($filter, 0, strlen($filter)-2)."))";
   }

   if ($o["search_product_id"][0]!=0 || sizeof($o["search_product_id"])>1) {
      $filter .= " AND (a.product_id IN (".implode(", ", $o["search_product_id"])."))";
   }

   if ($o['search_status_id'][0]!=0 || sizeof($o['search_status_id'])>1) {
      $filter .= " AND (a.armc_status_id IN (";
      foreach($o['search_status_id'] as $status_id) {
         $filter .= "$status_id, ";
      }
      $filter = substr($filter, 0, strlen($filter)-2)."))";
   }

   if (isset($o["search_account_executive_id"]) && ($o['search_account_executive_id'][0]!="all" || sizeof($o['search_account_executive_id'])>1)) {
      $ae_list = "";
      foreach($o['search_account_executive_id'] as $ae_id) {
         $ae_id = str_replace("//", "", $ae_id);
         $ae_list .= "$ae_id, ";
      }
      if ($ae_list != "") {
         $ae_list = substr($ae_list, 0, strlen($ae_list)-2);
      }
      $filter .= " AND (ae.login IN ($ae_list))";
   }

   if (isset($o["search_account_manager_id"]) && ($o['search_account_manager_id'][0]!="all" || sizeof($o['search_account_manager_id'])>1)) {
      $filter .= " AND (am.login IN (";
      foreach($o['search_account_manager_id'] as $am_id) {
         $am_id = str_replace("//", "", $am_id);
         $filter .= "$am_id, ";
      }
      $filter = substr($filter, 0, strlen($filter)-2)."))";
   }

   if ($o['search_armc_date_start']!="" || $o['search_armc_date_end']!="") {
      $filter .= " AND armc_date BETWEEN '";
      if ($o['search_armc_date_start']!="") {
         $filter .= $o['search_armc_date_start'];
      }else{
         $filter .= "1900-01-01";
      }
      $filter .= "' AND '";
      if ($o['search_armc_date_end']!="") {
         $filter .= $o['search_armc_date_end'];
      }else{
         $filter .= date("Y-m-d");
      }
      $filter .= " 23:59:59'";
   }

   if ($o['search_invoice_date_start']!="" || $o['search_invoice_date_end']!="") {
      $filter .= " AND transaction_date BETWEEN '";
      if ($o['search_invoice_date_start']!="") {
         $filter .= $o['search_invoice_date_start'];
      }else{
         $filter .= "1900-01-01";
      }
      $filter .= "' AND '";
      if ($o['search_invoice_date_end']!="") {
         $filter .= $o['search_invoice_date_end'];
      }else{
         $filter .= date("Y-m-d");
      }
      $filter .= " 23:59:59'";
   }

   if ($o["search_job_number"] != "") {
      $filter .= " AND aa_job.armc_attr_value LIKE '%".$o["search_job_number"]."%'";
   }

   if ($o["search_po_number"] != "") {
      $filter .= " AND aa_po.armc_attr_value LIKE '%".$o["search_po_number"]."%'";
   }

   if ($o['search_merged']!="both") {
      if ($o["search_merged"]=="single")
         $filter .= " AND a.armc_group_id = 0";
      if ($o["search_merged"]=="merged")
         $filter .= " AND a.armc_group_id != 0";
   }

   if ($o['search_month_end']!="both") {
      if ($o["search_month_end"]=="month_end")
         $filter .= " AND aa_month_end.armc_attr_value = 1";
      if ($o["search_month_end"]=="not")
         $filter .= " AND (aa_month_end.armc_attr_value != 1 OR aa_month_end.armc_attr_value IS NULL)";
   }
   //echo ($filter);
   return $filter;
}

/**
* AddARMC()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Jan 17 10:22:24 PST 2006
*/
function AddARMC($o)
{
   global $smarty;
   $atmDB = new atmDB();
   $commonDB = new commonDB();

   $types_result = $atmDB->GetARMCTypes();
   while ($type = mysql_fetch_assoc($types_result)) {
      if (!IsCreditMemo($type["armc_type_id"]))
         $types[$type["product_id"]][] = $type;
   }

   $armc_type_names = "new Array(new Array(), ";
   $armc_type_ids = "new Array(new Array(), ";
   foreach ($types AS $ts) {   	
   	   	
      $tmp_names = "new Array('--Please select the type--', ";
      $tmp_ids = "new Array('-1', ";
      foreach ($ts AS $type) {
         $tmp_names .= "'".$type["product_description"]." - ".$type["armc_type_description"]."', ";
         $tmp_ids .= "'".$type["armc_type_id"]."', ";
      }
      //set the product list here to make the same correct index with armc_type_names array
      $products[$type["product_id"]] =   $type["product_description"];
      
      if ($tmp_ids != "new Array('-1', ") {
         $tmp_names = substr($tmp_names, 0, strlen($tmp_names)-2);
         $tmp_ids = substr($tmp_ids, 0, strlen($tmp_ids)-2);
      }
      $tmp_names .= "), ";
      $tmp_ids .= "), ";
      $armc_type_names .= $tmp_names;
      $armc_type_ids .= $tmp_ids;
   }   
 
   if ($armc_type_ids != "new Array(new Array(), ") {
      $armc_type_names = substr($armc_type_names, 0, strlen($armc_type_names)-2);
      $armc_type_ids = substr($armc_type_ids, 0, strlen($armc_type_ids)-2);
   }
   $armc_type_names .= ")";
   $armc_type_ids .= ")";

   $rst = $commonDB->GetCurrencyList();
   $exchange_rates_array = "new Array(";
   while ($currency = mysql_fetch_assoc($rst)) {
      $currencies[$currency["currency_code"]] = $currency["currency_code"]."-".$currency["currency_description"];
      $exchange_rate = $commonDB->GetExchangeRate("USD", $currency["currency_code"]);
      if ($exchange_rate)
         $exchange_rates_array .= "'$exchange_rate', ";
      else
         $exchange_rates_array .= "'', ";
   }

   if ($exchange_rates_array != "new Array(")
      $exchange_rates_array = substr($exchange_rates_array, 0, strlen($exchange_rates_array)-2);
   $exchange_rates_array .= ")";
   $smarty->assign("products", $products);
   $smarty->assign("armc_types", array("ids"=>$armc_type_ids, "names"=>$armc_type_names));
   $smarty->assign("currencies", $currencies);
   $smarty->assign("exchange_rates_array", $exchange_rates_array);
   $smarty->assign("meta", $o);
   DisplayHeader('Billing Reports', 'armc', $o["action"]);
   $smarty->display("app/atm/armc/vw_add.tpl");
   DisplayFooter();
//   $list = HBRPCCall('pjm', 'GetStudyDetails', array('study_id'=>'18907'));
//   print_r($list);
}

/**
* CreateARMC()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 09 17:28:27 PST 2006
*/
function CreateARMC($type, $account_details, $study_details, $product_id=1)
{
   global $encryption;
   $atmDB = new atmDB();
   //Added to call new Contact Manger class
   $contact_manager = new atm_armc_ContactsManager();

            if (!isset($account_details["currency"])) $account_details["currency"] = "USD";
            if (!isset($account_details["exchange_rate"])) $account_details["exchange_rate"] = 1;
            $account_details["currency_code"] = $account_details["currency"];
            $armc_id = $atmDB->InsertARMC($type, $product_id);

            //Now go through all the data we got from ACM and STM, to set all the right attributes, user roles and contacts.

            //First, from STM we set the account ID
            $atmDB->InsertARMCAttr($armc_id, "ACCOUNT_ID", $study_details["partner_id"]);
            //Next, from ACM we set the account name, country code, and account-level attributes.
            $atmDB->InsertARMCAttr($armc_id, "ACCOUNT_NAME", $account_details["account_name"]);
            $atmDB->InsertARMCAttr($armc_id, "ACCOUNT_COUNTRY_CODE", $account_details["country_code"]);
            
            // insert close study when invoiced attribute
            $ret = $atmDB->InsertARMCAttr($armc_id, "CLOSE_STUDY_WHEN_INVOICED", $study_details["close_study_when_invoiced"]); 
            
            if (isset($account_details["attr"])) {
            foreach($account_details["attr"] AS $attr) {
               SetARMCAttr($armc_id, $attr["account_attr_name"], $attr["account_attr_value"]);
               switch ($attr["account_attr_name"]) {
//               case "AMRC_AE_APPROVAL_REQUIRED" : {
//                  $atmDB->InsertARMCAttr($armc_id, "REQUIRE_AE_APPROVAL", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_EMAIL_INVOICE" : {
//                  SetARMCAttr($armc_id, "EMAIL_INVOICE", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_PRINT_INVOICE" : {
//                  $atmDB->InsertARMCAttr($armc_id, "PRINT_INVOICE", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_MONTH_END_BILLING" : {
//                  $atmDB->InsertARMCAttr($armc_id, "ARMC_MONTH_END_BILLING", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_PO_REQUIRED" : {
//                  $atmDB->InsertARMCAttr($armc_id, "PO_REQUIRED", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_JOB_REQUIRED" : {
//                  $atmDB->InsertARMCAttr($armc_id, "JOB_REQUIRED", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_PM_REQUIRED" : {
//                  $atmDB->InsertARMCAttr($armc_id, "PM_REQUIRED", $attr["account_attr_value"]);
//               }break;
               case "ARMC_RETAINER_VALUE" : {
                  if ($attr["account_attr_value"] != 0) {
                     $retainer_url = "&retainer_type=account&retainer_value=".$attr["account_attr_value"];
                  }
               }break;
               case "GLOBAL_PREFFERED_CURRENCY" : {
                  if ($attr["account_attr_value"]=="") $attr["account_attr_value"]="USD";
                  $account_details["currency_code"] = $attr["account_attr_value"];
               }break;
               }
            }
            }

            $atmDB->InsertARMCAttr($armc_id, "I18N_CURRENCY_CODE", $account_details["currency_code"]);
            $atmDB->InsertARMCAttr($armc_id, "I18N_EXCHANGE_RATE", $account_details["exchange_rate"]);
            if ($account_details["currency"]!="USD")
               $atmDB->InsertARMCAttr($armc_id, "I18N_CURRENCY_EDIT", "1");

            //Next we set the user roles from ACM, but they might get overwriten
            //if the same role is also set at the Study level.
            if (isset($account_details["user"])) {
            foreach ($account_details["user"] AS $user) {
               $atmDB->InsertARMCUser($armc_id, $user["role_id"], $user["user_id"]);
            }
            }

            //Next, there are a few attributes that could already be set from STM
            $atmDB->InsertARMCAttr($armc_id, "STUDY_ID", $study_details["study_id"]);
            $atmDB->InsertARMCAttr($armc_id, "STUDY_NAME", $study_details["study_name"]);
            if (isset($study_details["attr"])) {
            foreach ($study_details["attr"] AS $attr_name=>$attr_value) {
               switch ($attr_name) {
               case "JOBNUMBER": {
                  $atmDB->InsertARMCAttr($armc_id, "JOBNUMBER", $attr_value);
               }break;
               case "PONUMBER": {
                  $atmDB->InsertARMCAttr($armc_id, "PONUMBER", $attr_value);
               }break;
               case "ISTRACKER": {
                  $atmDB->InsertARMCAttr($armc_id, "ISTRACKER", $attr_value);
               }break;
               case "PROPOSAL_ID": {
                  $atmDB->InsertARMCAttr($armc_id, "PROPOSAL_ID", $attr_value);                  
               }break;
               case "PROPOSAL_REVISION_ID": {
                  $atmDB->InsertARMCAttr($armc_id, "PROPOSAL_REVISION_ID", $attr_value);                  
               }break;
               case "PROPOSAL_NAME": {
                  $atmDB->InsertARMCAttr($armc_id, "PROPOSAL_NAME", $attr_value);                  
               }break;
               case "PROPOSAL_REVISION_NUM": {
                  $atmDB->InsertARMCAttr($armc_id, "PROPOSAL_REVISION_NUM", $attr_value);                  
               }break;
               case "RETAINER": {
                  if ($attr_value != 0) {
                     $retainer_url = "&retainer_type=study&retainer_value=".$attr_value;
                  }
               }break;

               }
            }
            }

            //Next we set the user roles from STM, which in some cases might overwrite
            //the user roles that we got from ACM.
            if (isset($study_details["user_roles"])) {
            foreach($study_details["user_roles"] AS $user) {
               SetARMCUser($armc_id, $user["role_id"], $user["login"]);
            }
            }


            //And finaly, if the billing and/or project contacts are set in STM, set them at BR level as well.
            if (isset($study_details["contacts"])) {
               //echo ("<pre>".print_r($study_details["contacts"], true)."</pre>");
            foreach ($study_details["contacts"] AS $contact) {
               switch ($contact["study_contact_type_id"]) {
               	case STUDY_CONTACT_BILLING:
               	   $contact["armc_contact_type_id"] = ARMC_CONTACT_TYPE_BILLING;
               		break;
                  case STUDY_CONTACT_PROJECT_MANAGER:
                     $contact["armc_contact_type_id"] = ARMC_CONTACT_TYPE_PROJECT;
                     SetARMCAttr($armc_id, "PMNAME", $contact["salutation"]." ".$contact["first_name"]." ".$contact["last_name"]);
                     break;
               }
             	$contact_manager->SetARMCContact($armc_id, $contact);
               //SetARMCContact($armc_id, $contact);
            }
            }

            //MOVED DOWN TO OVERRIDE BILLING CONTACT FROM THE ACCOUNT OVER THE ONE FROM STUDY
            if (isset($account_details["billing_contact"])) {
               //$atmDB->InsertARMCContact($armc_id, $account_details["billing_contact"]);

               $contact_manager->SetARMCContact($armc_id, $account_details["billing_contact"]);
               //SetARMCContact($armc_id, $account_details["billing_contact"]);
            }

            //This is temporary measure to make sure all BRs have an accounting person assigned.
            $users_rst = $atmDB->GetARMCUsers($armc_id, array(ROLE_ACCOUNTING));
            if ($atmDB->rows==0) {
               $default_acct = $atmDB->GetARMCSetting("DEFAULT_ROLE_ID_17");
               $default_acct = $default_acct["armc_setting_value"];
               SetARMCUser($armc_id, ROLE_ACCOUNTING, $default_acct);

            }

            //Finally set the study_count attribute
            $atmDB->InsertARMCAttr($armc_id, "STUDY_COUNT", $atmDB->GetARMCAttrsCount("STUDY_ID", $study_details["study_id"]));
            $atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_CREATED);
            $atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_EDIT);
            header("Location: ?e=".$encryption->Encrypt("action=display_armc_details&armc_id=$armc_id&dont_log_view=1".$retainer_url));
}

/**
* /armc/()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 09 12:11:16 PST 2006
*/
function NewARMC($o)
{
   global $encryption;
   $atmDB = new atmDB();
   $commonDB = new commonDB();
//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");
   switch ($o["armc_type"]) {
   case ARMC_TYPE_BR_STUDY :
   case ARMC_TYPE_RT_STUDY : {
      //18907
      
      $study_details = HBRPCCall('pjm', 'GetStudyDetails', array('study_id'=>$o["study_id"]));

      // Set close study when invoiced attribute
     	$study_details["close_study_when_invoiced"] = '0';
      if( isset($o["close_study_when_invoiced"]) )
      {
	     	$study_details["close_study_when_invoiced"] = '1';
   	}
    	     
      if ($study_details) {
         if ($study_details["error_code"]!=0) {
            echo ($study_details["error_description"]);
         }else{
            //$atmDB->debugPrint(print_r($study_details, true));
            $account_details = HBRPCCall('acm', 'GetAccountDetail', array("account_id"=>$study_details["partner_id"]));
            if ($account_details) {
               if ($account_details["error_code"]!=0) {
                  echo ($account_details["error_message"]);
               }else{
                  $account_details["currency"] = $o["currency"];
                  $account_details["exchange_rate"] = $o["exchange_rate"];
                  //print_r($account_details);
                  $account_preferred_currency = "USD";
                  if (is_array($account_details["attr"])) {
                     $account_preferred_currency = "USD";
                     foreach ($account_details["attr"] AS $attr) {
                        if ($attr["account_attr_name"]=="GLOBAL_PREFFERED_CURRENCY") {
                           $account_preferred_currency = $attr["account_attr_value"];
                           if ($o["currency"]=="USD" && $account_preferred_currency!="USD")
                              $account_details["exchange_rate"] = $commonDB->GetExchangeRate("USD", $account_preferred_currency);
                        }
                     }
                  }
                  if (($o["currency"]!="USD") && ($o["currency"] != $account_preferred_currency)) {
                     header("Location: ?action=add_armc&error=The selected currency was ".$o["currency"].", but the account preferred currency is ".$account_preferred_currency." !!!");
                  }else{
                  //$atmDB->debugPrint("<p>".print_r($account_details, true));

                  if ((isset($o["additional"]) && ($o["additional"]=="on")) || (isset($study_details["attr"]["ISTRACKER"]) && ($study_details["attr"]["ISTRACKER"]==1)) || ($atmDB->GetARMCAttrsCount("STUDY_ID", $o["study_id"])==0)) {
                     $billing_contacts = HBRPCCall('acm', 'GetAccountContactsDetails', array("account_id"=>$account_details["account_id"], "address_type_id"=>1, "contact_type_id"=>array(4)));
                     if ($billing_contacts["error_code"]==0) {
                        foreach($billing_contacts AS $billing_contact) {
                           if (is_array($billing_contact)) {
                              $account_details["billing_contact"] = array(
                              "armc_contact_type_id"=>ARMC_CONTACT_TYPE_BILLING,
                              "contact_id"=>$billing_contact["contact_id"],
                              "salutation"=>$billing_contact["contact_title"],
                              "first_name"=>mysql_real_escape_string($billing_contact["contact_first_name"]),
                              "last_name"=>mysql_real_escape_string($billing_contact["contact_last_name"]),
                              "address_1"=>mysql_real_escape_string($billing_contact["address_street_1"]),
                              "address_2"=>mysql_real_escape_string($billing_contact["address_street_2"]),
                              "city"=>mysql_real_escape_string($billing_contact["address_city"]),
                              "state"=>mysql_real_escape_string($billing_contact["address_state"]),
                              "zip"=>mysql_real_escape_string($billing_contact["address_zip"]),
                              "country_code"=>mysql_real_escape_string($billing_contact["address_country_code"]),
                              "phone"=>$billing_contact["contact_phone_number"],
                              "fax"=>$billing_contact["contact_fax_number"],
                              "email"=>$billing_contact["contact_email"]);
                           }
                        }
                     }
                     CreateARMC($o["armc_type"], $account_details, $study_details);
                  }else{
                     header("Location: ?e=".$encryption->Encrypt("action=default&search_study_id=".$o["study_id"]."&search_armc_date_start=&search_armc_date_end=&error=This are Billing Reports already created for study ID ".$o["study_id"].".<br>If you need to create another one, check the additional box."));
                  }
                  }
               }
            }
         }
      }
   }break;

   case ARMC_TYPE_BR_TR :
   case ARMC_TYPE_BR_SB :
   case ARMC_TYPE_BR_LI :
   case ARMC_TYPE_BR_OT :
   case ARMC_TYPE_RT_TR :
   case ARMC_TYPE_RT_SB :
   case ARMC_TYPE_RT_LI :
   case ARMC_TYPE_RT_OT :
   case ARMC_TYPE_VAP_BR :
   case ARMC_TYPE_VAP_RT :
   case ARMC_TYPE_ERS_BR :
   case ARMC_TYPE_ERS_RT :
   case ARMC_TYPE_RSA_BR :
   case ARMC_TYPE_RSA_RT :
   case ARMC_TYPE_ISS_BR :
   case ARMC_TYPE_ISS_RT :
   case ARMC_TYPE_EFP_BR :
   case ARMC_TYPE_EFP_RT :
   case ARMC_TYPE_EFS_BR :
   case ARMC_TYPE_EFS_RT : {
      $account_details = HBRPCCall('acm', 'GetAccountDetail', array("account_id"=>$o["account_id"]));
      $account_details["currency"] = $o["currency"];
      $account_details["exchange_rate"] = $o["exchange_rate"];
      //print_r($account_details);
      $account_preferred_currency = "USD";
      if (is_array($account_details["attr"])) {
         foreach ($account_details["attr"] AS $attr) {
            if ($attr["account_attr_name"]=="GLOBAL_PREFFERED_CURRENCY") {
               $account_preferred_currency = $attr["account_attr_value"];
               if ($o["currency"]=="USD" && $account_preferred_currency!="USD")
                  $account_details["exchange_rate"] = $commonDB->GetExchangeRate("USD", $account_preferred_currency);
            }
         }
      }
      if (($o["currency"]!="USD") && ($o["currency"] != $account_preferred_currency)) {
         header("Location: ?action=add_armc&error=The selected currency was ".$o["currency"].", but the account preferred currency is ".$account_preferred_currency." !!!");
      }else{
      $type_details = $atmDB->GetARMCTypeDetails($o["armc_type"]);
      $next_study_id = $atmDB->GetARMCSetting("NEXT_".$type_details["armc_type_prefix"]);
      $next_study_id = $next_study_id["armc_setting_value"];
      $atmDB->SetARMCSetting("NEXT_".$type_details["armc_type_prefix"], $next_study_id+1);

      $study_details["study_id"] = $type_details["armc_type_prefix"].$next_study_id;
      $study_details["study_name"] = $o["study_name"];
      $study_details["partner_id"] = $o["account_id"];
      if ($account_details) {
         if ($account_details["error_code"]!=0) {
            echo ($account_details["error_message"]);
         }else{
            $billing_contacts = HBRPCCall('acm', 'GetAccountContactsDetails', array("account_id"=>$account_details["account_id"], "address_type_id"=>1, "contact_type_id"=>array(4)));
            if ($billing_contacts["error_code"]==0) {
               foreach($billing_contacts AS $billing_contact) {
                  if (is_array($billing_contact)) {
                     $account_details["billing_contact"] = array(
                     "armc_contact_type_id"=>ARMC_CONTACT_TYPE_BILLING,
                     "contact_id"=>$billing_contact["contact_id"],
                     "salutation"=>$billing_contact["contact_title"],
                     "first_name"=>mysql_real_escape_string($billing_contact["contact_first_name"]),
                     "last_name"=>mysql_real_escape_string($billing_contact["contact_last_name"]),
                     "address_1"=>mysql_real_escape_string($billing_contact["address_street_1"]),
                     "address_2"=>mysql_real_escape_string($billing_contact["address_street_2"]),
                     "city"=>mysql_real_escape_string($billing_contact["address_city"]),
                     "state"=>mysql_real_escape_string($billing_contact["address_state"]),
                     "zip"=>mysql_real_escape_string($billing_contact["address_zip"]),
                     "country_code"=>mysql_real_escape_string($billing_contact["address_country_code"]),
                     "phone"=>$billing_contact["contact_phone_number"],
                     "fax"=>$billing_contact["contact_fax_number"],
                     "email"=>$billing_contact["contact_email"]);
                  }
               }
            }
            //echo ("<pre>".print_r($account_details, true)."</pre>");
            CreateARMC($o["armc_type"], $account_details, $study_details, $o["product_id"]);
         }
      }
      }
   }break;
   }
}

/**
* DisplayStudyARMCs()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 23 10:33:10 PST 2006
*/
function DisplayStudyARMCs($o)
{
   global $smarty;
   $atmDB = new atmDB();
   if (isset($o['page_size']) || isset($o['search_study_id']) || isset($o['sort']))
      $o["start"] = 0;

   if (!isset($o["page_size"]))
      $o["page_size"]=30;

   if (!isset($o["start"]))
      $o['start'] = 1;

   BuildPageLimit($o, $start, $page_size);

   $header[0] = array("align"=>"left", "width"=>"10%", "sort"=>"yes", "title"=>"Status", "field"=>"armc_status_description");
   $header[1] = array("align"=>"left", "width"=>"6%", "sort"=>"yes", "title"=>"BR #", "field"=>"armc_id");
   $header[2] = array("align"=>"left", "width"=>"6%", "sort"=>"yes", "title"=>"Study ID", "field"=>"study_id");
   $header[3] = array("align"=>"left", "width"=>"15%", "sort"=>"yes", "title"=>"Study Name", "field"=>"study_name");
   $header[4] = array("align"=>"left", "width"=>"5%%", "sort"=>"yes", "title"=>"Account ID", "field"=>"account_id");
   $header[5] = array("align"=>"left", "width"=>"16%", "sort"=>"yes", "title"=>"Account Name", "field"=>"account_name");
   $header[6] = array("align"=>"center", "width"=>"8%", "sort"=>"yes", "title"=>"BR Date", "field"=>"armc_date");
   $header[7] = array("align"=>"right", "width"=>"6%", "sort"=>"yes", "title"=>"AE", "field"=>"account_executive");
   $header[8] = array("align"=>"right", "width"=>"6%", "sort"=>"yes", "title"=>"AM", "field"=>"account_manager");
   $header[9] = array("align"=>"right", "width"=>"10%", "sort"=>"yes", "title"=>"Amount", "field"=>"amount");

//   echo ($o['order_tag']);
   //$filter = "a.armc_group_id=0";
   $rst = $atmDB->GetARMCStudyList($o["study_id"], $o["armc_type_id"]);
   $o["total_rows"] = $atmDB->rows;
   $page_count = ceil($o["total_rows"] / $page_size);

   $o['page_size_list'] = array(1=>"1", 10=>"10", 30=>"30", 50=>"50", 100=>"100");

   for ($i=1; $i<=$page_count; $i++) {
      $pages[($i-1)*$page_size] = $i;
   }

   $o["report_title"] = "Billing Reports";
   $o["page_list"] = $pages;


   $armc_rst = $atmDB->GetARMCStudyList($o["study_id"], $o["armc_type_id"], "LIMIT ".$o["start"].", ".$o["page_size"]);
   //echo("<pre>");
   while ($armc = mysql_fetch_assoc($armc_rst)) {
      //print_r($armc);
      //echo ("<p>");
      $list[] = $armc;
   }
   //print_r($list);
   //echo ("</pre>");
   $smarty->assign("header", $header);
   $smarty->assign("meta", $o);
   $smarty->assign("list", $list);
   DisplayHeader('Billing Reports', 'armc', $o["action"]);
   $smarty->display("app/atm/armc/vw_study_list.tpl");
   DisplayFooter();
}

/**
* CheckSessionVariable()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 25 12:05:11 PST 2006
*/
function CheckSessionVariable($o, $name, $default)
{
   if (!isset($o[$name])) {
      if (!isset($_SESSION['armc_'.$name])) {
         $o[$name] = $default;
         $_SESSION['armc_'.$name] = $default;
      }else{
         $o[$name] = $_SESSION['armc_'.$name];
      }
   }else{
      $_SESSION['armc_'.$name] = $o[$name];
   }
   return $o[$name];

}

/**
* ARMCDefault()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Jan 17 10:40:59 PST 2006
*/
function ARMCDefault($o)
{
	
   global $smarty, $userRights;
   $atmDB = new atmDB();
   $commonDB = new commonDB();

   //$banner = GetBannerReport();
   
   if ($o["search_vendor_po_no"] != "")  
	{ 
    	$o["search_vendor_po_no"] = 'PO' . preg_replace("/([^0-9]|\s)/i", "",$o["search_vendor_po_no"]);
    	$study_cost = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_StudyCost')->FindByVendorPONumber($o["search_vendor_po_no"]);

    	if (is_null($o['search_study_id'] = $study_cost->GetStudyId()))  
		{ 
	    	$o["search_vendor_po_no"] = '';
		}
	} 
	
   $o["is_strict"] = $userRights->userSecCheck("BRSTRICT") &&
                     (!isset($o["search_study_id"]) || $o["search_study_id"]=="") &&
                     (!isset($o["search_account_id"]) || $o["search_account_id"]=="") &&
                     (!isset($o["search_armc_id"]) || $o["search_armc_id"]=="") &&
                     (!isset($o["search_invoice_number"]) || $o["search_invoice_number"]=="") &&
                     (!isset($o["start"]));

   if (isset($o['search_study_id']) || isset($o['sort'])) $o["start"] = 0;


   $o['page_size'] = CheckSessionVariable($o, 'page_size', 30);
   $o['start'] = CheckSessionVariable($o, 'start', 0);

   BuildPageLimit($o, $start, $page_size);

   $accounting_period = GetAccountingPeriod();

   $o['search_armc_id'] = CheckSessionVariable($o, 'search_armc_id', "");
   $o['search_study_id'] = CheckSessionVariable($o, 'search_study_id', "");
   $o['search_account_id'] = CheckSessionVariable($o, 'search_account_id', "");
   $o['search_type_id'] = CheckSessionVariable($o, 'search_type_id', array("0"=>0));
   $o['search_product_id'] = CheckSessionVariable($o, 'search_product_id', array("0"=>0));
   $o['search_status_id'] = CheckSessionVariable($o, 'search_status_id', array("0"=>0));
   $o['search_account_executive_id'] = CheckSessionVariable($o, 'search_account_executive_id', array("0"=>"all"));
   $o['search_account_manager_id'] = CheckSessionVariable($o, 'search_account_manager_id', array("0"=>"all"));
   $o['search_armc_date_start'] = CheckSessionVariable($o, 'search_armc_date_start', $accounting_period."-01");
   $o['search_armc_date_end'] = CheckSessionVariable($o, 'search_armc_date_end', $accounting_period."-".GetLastDateOfMonth(mktime(0, 0, 0, substr($accounting_period, 5), 10, substr($accounting_period, 0, 4))));
   $o['search_invoice_number'] = CheckSessionVariable($o, 'search_invoice_number', "");
   $o['search_invoice_date_start'] = CheckSessionVariable($o, 'search_invoice_date_start', "");
   $o['search_invoice_date_end'] = CheckSessionVariable($o, 'search_invoice_date_end', "");
   $o['search_job_number'] = CheckSessionVariable($o, 'search_job_number', '');
   $o['search_po_number'] = CheckSessionVariable($o, 'search_po_number', '');
   $o['search_vendor_po_no'] = CheckSessionVariable($o, 'search_vendor_po_no', '');
   $o['search_merged'] = CheckSessionVariable($o, 'search_merged', 'both');
   $o['search_month_end'] = CheckSessionVariable($o, 'search_month_end', 'both');
   $o['sort'] = CheckSessionVariable($o, 'sort', 'armc_date DESC');
   $o['sort_by_column'] = $o['sort'];

   //print_r($o["search_account_executive_id"]);
   if ((isset($o["search_study_id"]) && ($o["search_study_id"]!="")) ||
       (isset($o["search_armc_id"]) && ($o["search_armc_id"]!="")) ||
       (isset($o["search_invoice_number"]) && ($o["search_invoice_number"]!=""))) {
      $o["search_armc_date_start"] = "";
      $o["search_armc_date_end"] = "";
      $o["search_invoice_date_start"] = "";
      $o["search_invoice_date_end"] = "";
      $o["search_merged"] = "";
      $o["search_month_end"] = "";
   }

   $user_sql_filter="";
   $can_search_location = true;
   $o["can_override_viewmybr"] = $userRights->userSecCheck("GLOBAL_VIEW_MY_ACCOUNTS_ITEMS");
   
   if ($userRights->userSecCheck("VIEWMYBR")&&($o["search_armc_id"]=="")&&(!isset($o["override_viewmybr"]))) {
      $can_search_location = false;
      if ($userRights->userSecCheck("VIEWOTHERSBR")) {
         $can_search_location = true;
         $reportees = GetReportees($_SESSION["admin_id"], 0);
      }else{
         $reportees = array(0=>$_SESSION["admin_id"]);
      }
      $user_filter="";
      foreach ($reportees AS $reportee) {
         $user_filter .= $reportee.", ";
      }
      $user_filter=substr($user_filter, 0, strlen($user_filter)-2)."";
      //$user_sql_filter = " AND (ae.login IN ($user_filter) OR am.login IN ($user_filter)) ";
      $user_sql_filter = " AND (au.login IN ($user_filter)) ";
   }
      
//   if (strstr($o["sort_by_column"], "DESC"))
//      $o["sort_by_column"] = substr($o["sort_by_column"], 0, strlen($o["sort_by_column"])-5);


   //echo ("Filter : $filter<br>");
   
   $header[] = array("align"=>"left", "width"=>"6%", "sort"=>"yes", "title"=>"BR #", "field"=>"armc_number", 'ftype' => 'link');
   $header[] = array("align"=>"left", "width"=>"10%", "sort"=>"yes", "title"=>"Status", "field"=>"armc_status_description");
   $header[] = array("align"=>"left", "width"=>"6%", "sort"=>"yes", "title"=>"Study ID", "field"=>"study_id");
   $header[] = array("align"=>"left", "width"=>"15%", "sort"=>"yes", "title"=>"Study Name", "field"=>"study_name");
   $header[] = array("align"=>"left", "width"=>"5%%", "sort"=>"yes", "title"=>"Account ID", "field"=>"account_id");
   $header[] = array("align"=>"left", "width"=>"16%", "sort"=>"yes", "title"=>"Account Name", "field"=>"account_name");
   $header[] = array("align"=>"center", "width"=>"8%", "sort"=>"yes", "title"=>"BR Date", "field"=>"armc_date");
   $header[] = array("align"=>"center", "width"=>"10%", "sort"=>"yes", "title"=>"Invoice #", "field"=>"transaction_number");
   $header[] = array("align"=>"center", "width"=>"10%", "sort"=>"yes", "title"=>"Invoice Date", "field"=>"transaction_date");
   $header[] = array("align"=>"right", "width"=>"6%", "sort"=>"yes", "title"=>"AE", "field"=>"account_executive");
   $header[] = array("align"=>"right", "width"=>"6%", "sort"=>"yes", "title"=>"AM", "field"=>"account_manager");
   $header[] = array("align"=>"right", "width"=>"10%", "sort"=>"yes", "title"=>"Amount", "field"=>"sort_amount");
   
   

   $lists['product'] = CreateSmartyArray($commonDB->GetProductList(), 'product_id', 'product_description');
   $lists['armc_type'] = CreateSmartyArray($atmDB->GetARMCTypes(), 'armc_type_id', 'product_type');
   $lists['armc_status'] = CreateSmartyArray($atmDB->GetARMCStatuses(), 'armc_status_id', 'armc_status_description');
//   $lists['armc_account_executive'] = array_merge($locations, CreateSmartyArray($commonDB->GetUsersByRoleId(ROLE_ACCOUNT_EXECUTIVE), 'login', 'name'));
   //$lists['armc_account_manager'] = CreateSmartyArray($commonDB->GetUsersByRoleId(ROLE_ACCOUNT_MANAGER), 'login', 'name');
   $lists['merged'] = array('both'=>'Both', 'single'=>'Single only', 'merged'=>'Merged only');
   $lists['month_end'] = array('both'=>'Both', 'month_end'=>'Month end only', 'not'=>'Not month end only');

   $template = "app/atm/armc/vw_armc_list.tpl";
   if (!isInternalUser()) {
      $template = "app/atm/armc/vw_list.tpl";
      $header = OverrideARMCHeader();
      $o["search_account_id"] = ARMCOverrideAccountID();
      $lists['armc_status'] = OverrideStatus($lists['armc_status']);
      $o["search_status_id"] = OverrideSearchStatus($o["search_status_id"]);
      $template = "app/atm/armc/ext/vw_list.tpl";
   }
   $filter  = BuildSearchFilter($o);
   $filter .= $user_sql_filter;
  
//   $filter_grouped .= $user_sql_filter;
//   echo ($o['order_tag']);
   //$filter = "a.armc_group_id=0";
   //$rst = $atmDB->GetARMCList($filter, "ORDER BY ".$o["sort"]);
   //echo ($filter);
   $o["total_rows"] = $atmDB->GetARMCListCounts($filter);
   //$o["total_rows"] = $atmDB->rows;
   $page_count = ceil($o["total_rows"] / $page_size);

   if (isset($o["display_all"])&&($o["display_all"]==1)) {
      //mysql_data_seek($rst, 0);
      $rst = $atmDB->GetARMCList($filter, "ORDER BY ".$o["sort"]);
   }else{
      $rst = $atmDB->GetARMCList($filter, "ORDER BY ".$o['sort'], "LIMIT ".$o["start"].", ".$o["page_size"]);
   }
  
   
   $o["page_rows"] = $atmDB->rows;
   while ($armc = mysql_fetch_assoc($rst)) {
      if ($armc['grouped']=="true") {
         $grouped_armc_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id = ".$armc['armc_id'], "ORDER BY ".$o['sort']);
         $armc['array'] = "new Array(-1,";
         while ($group_armc = mysql_fetch_assoc($grouped_armc_rst)) {
            $armc['array'] .= $group_armc['armc_id'].",";
            if (IsCreditMemo($group_armc["armc_type_id"])) {
               $group_armc["display_amount"] = "<font color=Red>-$".number_format($group_armc["amount"], 2)."</font>";
            }else{
               $group_armc["display_amount"] = "$".number_format($group_armc["amount"], 2);
            }
            $armc['armc'][] = $group_armc;
         }
         $armc['array'] = substr($armc['array'], 0, strlen($armc['array'])-1).")";
      }
      if (IsCreditMemo($armc["armc_type_id"])) {
         $total -= $armc["amount"];
         $armc["display_amount"] = "<font color=Red>-$".number_format($armc["amount"], 2)."</font>";
      }else{
         $total += $armc["amount"];
         $armc["display_amount"] = "$".number_format($armc["amount"], 2);
      }
      $list[] = $armc;
   }

   if ($o["search_armc_date_start"]!="") {
      $fast_search_armc_date_year = substr($o["search_armc_date_start"], 0, 4);
      $fast_search_armc_date_month = substr($o["search_armc_date_start"], 5, 2);
   }else{
      $fast_search_armc_date_year = substr($accounting_period, 0, 4);
      $fast_search_armc_date_month = substr($accounting_period, 5);
   }

   $lists["fast_search_armc_date"][$accounting_period."-01"."//".$accounting_period."-".GetLastDateOfMonth(mktime(0, 0, 0, substr($accounting_period, 5), 10, substr($accounting_period, 0, 4)))] = "Current accounting period ($accounting_period)";
   for($m=$fast_search_armc_date_month-2; $m<=$fast_search_armc_date_month+2; $m++) {
      if ($m!=substr($accounting_period, 5)) {
      if ($m<=0) {
         $mon = 12+$m;
         $yr = $fast_search_armc_date_year-1;
      }elseif ($m>=13) {
         $mon = $m-12;
         $yr = $fast_search_armc_date_year+1;
      }else{
         $mon=$m;
         $yr = $fast_search_armc_date_year;
      }
      $dy = GetLastDateOfMonth(mktime(0, 0, 0, $mon, 10, $yr));
      if ($mon<10) {
         $mon = "0".$mon;
      }
      $lists["fast_search_armc_date"]["$yr-$mon-01//$yr-$mon-$dy"] = $yr."-".$mon;
      }
   }
   for($y=$fast_search_armc_date_year-1; $y<=$fast_search_armc_date_year; $y++) {
      for($q=1; $q<=4; $q++) {
         $m = (($q-1)*3+1);
         $dy = GetLastDateOfMonth(mktime(0, 0, 0, $m+2, 10, $y));
         if ($m<10) {
            $mon = "0".($m+2);
            $m = "0".$m;
         }else{
            $mon = $m+2;
         }
         $lists["fast_search_armc_date"]["$y-$m-01//$y-$mon-$dy"] = "Quarter $q, $y";
      }
      $lists["fast_search_armc_date"]["$y-01-01//$y-12-31"] = "Year $y";
   }
   if ($o["search_invoice_date_start"]!="") {
      $fast_search_invoice_date_year = substr($o["search_invoice_date_start"], 0, 4);
      $fast_search_invoice_date_month = substr($o["search_invoice_date_start"], 5, 2);
   }else{
      $fast_search_invoice_date_year = substr($accounting_period, 0, 4);
      $fast_search_invoice_date_month = substr($accounting_period, 5);
   }

   $lists["fast_search_invoice_date"][$accounting_period."-01"."//".$accounting_period."-".GetLastDateOfMonth(mktime(0, 0, 0, substr($accounting_period, 5), 10, substr($accounting_period, 0, 4)))] = "Current accounting period ($accounting_period)";
   for($m=$fast_search_invoice_date_month-2; $m<=$fast_search_invoice_date_month+2; $m++) {
      if ($m!=substr($accounting_period, 5)) {
      if ($m<=0) {
         $mon = 12+$m;
         $yr = $fast_search_invoice_date_year-1;
      }elseif ($m>=13) {
         $mon = $m-12;
         $yr = $fast_search_invoice_date_year+1;
      }else{
         $mon=$m;
         $yr = $fast_search_invoice_date_year;
      }
      $dy = GetLastDateOfMonth(mktime(0, 0, 0, $mon, 10, $yr));
      if ($mon<10) {
         $mon = "0".$mon;
      }
      $lists["fast_search_invoice_date"]["$yr-$mon-01//$yr-$mon-$dy"] = $yr."-".$mon;
      }
   }
   for($y=$fast_search_invoice_date_year-1; $y<=$fast_search_invoice_date_year; $y++) {
      for($q=1; $q<=4; $q++) {
         $m = (($q-1)*3+1);
         $dy = GetLastDateOfMonth(mktime(0, 0, 0, $m+2, 10, $y));
         if ($m<10) {
            $mon = "0".($m+2);
            $m = "0".$m;
         }else{
            $mon = $m+2;
         }
         $lists["fast_search_invoice_date"]["$y-$m-01//$y-$mon-$dy"] = "Quarter $q, $y";
      }
      $lists["fast_search_invoice_date"]["$y-01-01//$y-12-31"] = "Year $y";
   }

   if (isset($_SESSION['err_filemgr'])) {
   	  $smarty->assign('error', $_SESSION['err_filemgr']);
   	  unset($_SESSION['err_filemgr']);
   }
   
   if ($can_search_location) {
   $locations_rst = $commonDB->GetLocationList();
   $lists["armc_account_executive"]["(1"] = "Account Executives in -----";
   $lists["armc_account_manager"]["(1"] = "Account Managers in -----";
   while ($location = mysql_fetch_assoc($locations_rst)) {
      $aes_at_location_rst = $commonDB->GetUsersByLocationId($location["location_id"], array(ROLE_ACCOUNT_EXECUTIVE));
      $aes_ids = "//";
      while ($ae_at_location = mysql_fetch_assoc($aes_at_location_rst)) {
         $aes_ids .= $ae_at_location["login"].", ";
      }
      if ($aes_ids!="//") {
         $aes_ids = substr($aes_ids, 0, strlen($aes_ids)-2)."//";
         $lists["armc_account_executive"][$aes_ids] = $location["location_description"];
      }
      $ams_at_location_rst = $commonDB->GetUsersByLocationId($location["location_id"], array(ROLE_ACCOUNT_MANAGER));
      $ams_ids = "//";
      while ($am_at_location = mysql_fetch_assoc($ams_at_location_rst)) {
         $ams_ids .= $am_at_location["login"].", ";
      }
      if ($ams_ids!="//") {
         $ams_ids = substr($ams_ids, 0, strlen($ams_ids)-2)."//";
         $lists["armc_account_manager"][$ams_ids] = $location["location_description"];
      }
   }
   $lists["armc_account_executive"]["1)"] = "";
   $lists["armc_account_manager"]["1)"] = "";
   $lists["armc_account_executive"]["(2"] = "Account Executive -----";
   $lists["armc_account_manager"]["(2"] = "Account Manager -----";
   }

   $user_rst = $commonDB->GetUsersByRoleId(ROLE_ACCOUNT_EXECUTIVE);
   while ($user = mysql_fetch_assoc($user_rst)) {
      $lists["armc_account_executive"][$user["login"]] = $user["name"];
   }
   $user_rst = $commonDB->GetUsersByRoleId(ROLE_ACCOUNT_MANAGER);
   while ($user = mysql_fetch_assoc($user_rst)) {
      $lists["armc_account_manager"][$user["login"]] = $user["name"];
   }
   if ($can_search_location) {
      $lists["armc_account_executive"]["2)"] = "";
      $lists["armc_account_manager"]["2)"] = "";
   }

   $o['page_size_list'] = array(1=>"1", 10=>"10", 30=>"30", 50=>"50", 100=>"100");

   $pages = array();
   for ($i=1; $i<=$page_count; $i++) {
      $pages[($i-1)*$page_size] = $i;
   }

   $o["report_title"] = "Billing Reports";
   $o["page_list"] = $pages;
   end($pages);
   $o["last_page_start"] = key($pages);
   //$o["header_table"] = "app/atm/armc/vw_stat_banner_brief.tpl";

   header("Content-type: text/xml");
   
   $smarty->assign("lang", $o['lbl']);
   $smarty->assign("meta", $o);
   $smarty->assign("lists", $lists);
   $smarty->assign("header", $header);
   $smarty->assign("list", $list);
   $smarty->assign("total", $total);

   $smarty->display($template);
   
}

/**
* DisplayStalledReport()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Jun 30 15:09:43 PDT 2006
*/
function DisplayStalledReport($o)
{
   global $smarty, $userRights;

   $atmDB = new atmDB();

   $filter = " AND a.armc_status_id='".ARMC_STATUS_STALLED."'";
   $o['page_size'] = CheckSessionVariable($o, 'page_size', 30);
   $o['start'] = CheckSessionVariable($o, 'start', 0);

   BuildPageLimit($o, $start, $page_size);
   if (!isset($o["sort"])) $o['sort'] = "stalled_on DESC";
   $o['sort_by_column'] = $o['sort'];

   $o["total_rows"] = $atmDB->GetARMCListCounts($filter);
   //$o["total_rows"] = $atmDB->rows;
   $page_count = ceil($o["total_rows"] / $page_size);

   if (isset($o["display_all"])&&($o["display_all"]==1)) {
      //mysql_data_seek($rst, 0);
      $rst = $atmDB->GetARMCStalledList($filter, "ORDER BY ".$o["sort"]);
   }else{
      $rst = $atmDB->GetARMCStalledList($filter, "ORDER BY ".$o['sort'], "LIMIT ".$o["start"].", ".$o["page_size"]);
   }
   $o["page_rows"] = $atmDB->rows;
   while ($armc = mysql_fetch_assoc($rst)) {
      //echo ($armc["armc_type_prefix"]."-".$armc["armc_id"]."<br>");
      if (IsCreditMemo($armc["armc_type_id"])) {
         $total -= $armc["amount"];
         $armc["display_amount"] = "<font color=Red>-$".number_format($armc["amount"], 2)."</font>";
      }else{
         $total += $armc["amount"];
         $armc["display_amount"] = "$".number_format($armc["amount"], 2);
      }
      if (is_null($armc["stall_reason"]))
         $armc["stall_reason"] = "Unknown";
      elseif (strrpos($armc["stall_reason"], "%%%%"))
         $armc["stall_reason"] = substr($armc["stall_reason"], strrpos($armc["stall_reason"], "%%%%")+4);
      $list[] = $armc;
   }
   $o['page_size_list'] = array(1=>"1", 10=>"10", 30=>"30", 50=>"50", 100=>"100");

   $pages = array();
   for ($i=1; $i<=$page_count; $i++) {
      $pages[($i-1)*$page_size] = $i;
   }

   $header[0] = array("align"=>"left", "width"=>"6%", "sort"=>"yes", "title"=>"BR #", "field"=>"armc_id");
   $header[1] = array("align"=>"center", "width"=>"6%", "sort"=>"yes", "title"=>"Stall date", "field"=>"stalled_on");
   $header[2] = array("align"=>"left", "width"=>"10%", "sort"=>"yes", "title"=>"Reason", "field"=>"stall_reason");
   $header[3] = array("align"=>"center", "width"=>"10%", "sort"=>"yes", "title"=>"Exp. clear date", "field"=>"stall_clear_date");
   $header[4] = array("align"=>"left", "width"=>"6%", "sort"=>"yes", "title"=>"Study ID", "field"=>"study_id");
   $header[5] = array("align"=>"left", "width"=>"15%", "sort"=>"yes", "title"=>"Study Name", "field"=>"study_name");
   $header[6] = array("align"=>"left", "width"=>"5%%", "sort"=>"yes", "title"=>"Account ID", "field"=>"account_id");
   $header[7] = array("align"=>"left", "width"=>"16%", "sort"=>"yes", "title"=>"Account Name", "field"=>"account_name");
   $header[8] = array("align"=>"center", "width"=>"8%", "sort"=>"yes", "title"=>"BR Date", "field"=>"armc_date");
   $header[9] = array("align"=>"right", "width"=>"6%", "sort"=>"yes", "title"=>"AM", "field"=>"account_manager");
   $header[10] = array("align"=>"right", "width"=>"10%", "sort"=>"yes", "title"=>"AM Location", "field"=>"am_location");
   $header[11] = array("align"=>"right", "width"=>"10%", "sort"=>"yes", "title"=>"Amount", "field"=>"amount");

   $o["report_title"] = "Stalled Billing Reports";
   $o["page_list"] = $pages;
   end($pages);
   $o["last_page_start"] = key($pages);
   //$o["header_table"] = "app/atm/armc/vw_stat_banner_brief.tpl";

   if (isset($o["export_excel"])&&$o["export_excel"]==1) {
      $smarty->assign("list", $list);
      $smarty->assign("total", $total);
      header('Content-type: application/vnd.ms-excel');
      header("Content-disposition: attachment; filename=\"stalled_billing_reports_".date('Ymd').".xls\"");
      $smarty->display("app/atm/armc/rp_stalled_report.tpl");
   }else{
      $smarty->assign("lang", $o['lbl']);
      $smarty->assign("meta", $o);
//      $smarty->assign("columns", $banner["columns"]);
//      $smarty->assign("lines", $banner["lines"]);
//      $smarty->assign("lists", $lists);
      //$smarty->assign("page_count", $page_count);
      //$smarty->assign("pages", $pages);
      $smarty->assign("header", $header);
      $smarty->assign("list", $list);
      $smarty->assign("total", $total);
      DisplayHeader('Billing Reports', 'armc', $o["action"]);
      $smarty->display("app/atm/armc/vw_stalled_report.tpl");
      DisplayFooter();
   }

}

/**
* DisplayARMCDetails()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jan 30 10:46:09 PST 2006
*/
/*
function DisplayARMCDetails($o)
{
   global $smarty, $userRights, $encryption;
   $atmDB = new atmDB();
   $commonDB = new commonDB();

   $header_rst = $atmDB->GetARMCHeader($o["armc_id"]);
   if ($header = mysql_fetch_assoc($header_rst)) {
      //print_r($header);
      //echo (GetExternalPrimaryAccount());
      if (isInternalUser() || (isExternalUser() && (GetExternalPrimaryAccount() == $header["account_id"]) && IsVisibleByEXT($header["armc_status_id"]))) {
      if (!isset($o["dont_log_view"])) $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_VIEWED);
      $header["armc_date"] = substr($header["armc_date"], 0, 10);
      if ($header["armc_status_id"]==ARMC_STATUS_STALLED) {
         $header["stall_reason"] = $atmDB->GetARMCLastEvent($header["armc_id"], ARMC_EVENT_TYPE_STALLED);
         $header["stall_reason"] = ($header["stall_reason"]?$header["stall_reason"]["comment"]:"Unknown");
      }

      if (IsCreditMemo($header["armc_type_id"])) {
         $header["cm_applied_to"] = $atmDB->GetARMCAttr($o["armc_id"], "CMAPPTO");
      }
      $reportees = GetReportees($_SESSION['admin_id'], 0);
      //$atmDB->debugPrint("Reportees : ".print_r($reportees, true));
      $armc_users_rst = $atmDB->GetARMCUsers($o["armc_id"]);
      while ($armc_user = mysql_fetch_assoc($armc_users_rst)) {
      	$armc_users[$armc_user['role_id'].$armc_user['login']] = $armc_user['login'];
      	$header["users"][] = $armc_user;
      }
      $armc_users[] = $header["created_by"];
      $armc_users_rst = $atmDB->GetARMCUsers($o["armc_id"], array(ROLE_ACCOUNT_EXECUTIVE, ROLE_PRIMARY_ACCT_EXEC, ROLE_SECONDARY_ACCT_EXEC));
      $armc_aes = array();
      while ($armc_user = mysql_fetch_assoc($armc_users_rst)) {
         $armc_aes[] = $armc_user['login'];
      }
      $armc_users_rst = $atmDB->GetARMCUsers($o["armc_id"], array(ROLE_ACCOUNT_MANAGER, ROLE_PRIMARY_ACCT_MGR, ROLE_PROJECT_MANAGER, ROLE_PRIMARY_PROJECT_MANAGER, ROLE_SECONDARY_PROJECT_MANAGER, ROLE_SECONDARY_ACCT_MGR));
      $armc_ams = array();
      while ($armc_user = mysql_fetch_assoc($armc_users_rst)) {
         $armc_ams[] = $armc_user['login'];
      }
      $armc_users_rst = $atmDB->GetARMCUsers($o["armc_id"], array(ROLE_ACCOUNTING));
      $armc_accts = array();
      while ($armc_user = mysql_fetch_assoc($armc_users_rst)) {
         $armc_accts[] = $armc_user['login'];
      }
      $armc_users_rst = $atmDB->GetARMCUsers($o["armc_id"], array(ROLE_CREATOR));
      $armc_creators = array($header["created_by"]);
      while ($armc_user = mysql_fetch_assoc($armc_users_rst)) {
         $armc_creators[] = $armc_user['login'];
      }

      $invoice_rst = $atmDB->GetARMCInvoice($o["armc_id"]);
      $invoice = mysql_fetch_assoc($invoice_rst);

//      $atmDB->debugPrint("ARMC Users : ".print_r($armc_users, true));
//      $atmDB->debugPrint("ARMC AEs : ".print_r($armc_aes, true));
//      $atmDB->debugPrint("ARMC AMs : ".print_r($armc_ams, true));
//      $atmDB->debugPrint("ARMC Accountants : ".print_r($armc_accts, true));
      //If the currently logged in user, or one of his/her reportees is one of the users for this ARMC record, he/she can edit
      //the details on the Billing Report. NO EXCEPTIONS.

      $i18n_currency = $atmDB->GetARMCAttr($o["armc_id"], "I18N_CURRENCY_CODE");
      if ($i18n_currency) {
         $i18n_rate = $atmDB->GetARMCAttr($o["armc_id"], "I18N_EXCHANGE_RATE");
         $i18n_edit = $atmDB->GetARMCAttr($o["armc_id"], "I18N_CURRENCY_EDIT");
      }else{
         $i18n_currency = "USD";
         $i18n_rate = 1;
         $i18n_edit = false;
      }
      $i18n_currency_unicode = $commonDB->GetUnicodeCurrencySymbol($i18n_currency);
      $i18n_message = "";
      if ($i18n_currency != "USD") {
         if (IsInvoiced($header["armc_status_id"])) {
            $i18n_message = "This Billing Reports was invoiced in $i18n_currency, at an exchange rate of ".number_format($i18n_rate, 4)." USD for 1 $i18n_currency !!!<br>To switch between $i18n_currency and USD views, use the links to the right !!!";
         }elseif ($i18n_edit) {
            $i18n_message = "This Billing Report was created and will be invoiced in $i18n_currency, at an exchange rate of ".number_format($i18n_rate, 4)." USD for 1 $i18n_currency !!!<br>To switch between $i18n_currency (editable) and USD views, use the links to the right !!!";
         }else{
            $i18n_message = "This Billing Report was created in USD, but the account requires it to be invoiced in $i18n_currency. The exchange rate will be ".number_format($i18n_rate, 4)." USD for 1 $i18n_currency !!!<br>To switch between USD (editable) and $i18n_currency views, use the links to the right !!!";
         }
      }

      $total_proposed = 0;
      $total_actual = 0;
      $total_net = 0;
      $total_proposed_i18n = 0;
      $total_actual_i18n = 0;
      $total_net_i18n = 0;
      $total_delta = 0;
      $lines = array();
      $lines_rst = $atmDB->GetARMCLines($o["armc_id"]);
      while ($line = mysql_fetch_assoc($lines_rst)) {
         if ($line['proposed_amount']=="")
            $line['proposed_amount'] = 0;
         if ($line["proposed_amount_i18n"]=="")
            $line["proposed_amount_i18n"]=0;
         if ($line['actual_amount']=="")
            $line['actual_amount'] = 0;
         if ($line['actual_amount_i18n']=="")
            $line['actual_amount_i18n'] = 0;
         $line['net'] = $line['actual_amount'] - $line['proposed_amount'];
         $line['net_i18n'] = $line['actual_amount_i18n'] - $line['proposed_amount_i18n'];
   		if ($line['proposed_amount']!=0) {
	  		   $line['delta'] = $line['net'] * 100 / $line['proposed_amount'];
		   }else{
			   $line['delta'] = 0;
		   }
		   $line["am_approved"] = $atmDB->ARMCLineIsApproved($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AM_APPROVED_LINE);
		   $line["ae_approved"] = $atmDB->ARMCLineIsApproved($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AE_APPROVED_LINE);
		   $line["acct_approved"] = $atmDB->ARMCLineIsApproved($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_ACCT_APPROVED_LINE);

		   $total_proposed += $line['proposed_amount'];
		   $total_proposed_i18n += $line["proposed_amount_i18n"];
		   $total_actual += $line['actual_amount'];
		   $total_actual_i18n += $line["actual_amount_i18n"];
		   $total_net += $line['net'];
		   $total_net_i18n += $line["net_i18n"];
         $lines[] = $line;
      }
      if ($total_proposed != 0)
         $total_delta = $total_net * 100 / $total_proposed;
      else
         $total_delta = 0;

      $new_lines = array();
	   for($k=0; $k<NUM_BUDGET_LINES; $k++) {
		   $new_lines[] = array(
			   "armc_budget_line_item_id"=>$k,
			);
   	}

      //$lines_defs = CreateSmartyArray($atmDB->GetARMCLineDefinitions(), 'armc_budget_line_item_def_id', 'armc_budget_line_item_description');
      $line_def_rst = $atmDB->GetARMCLineDefinitions($header["armc_type_id"]);
      while ($line_def = mysql_fetch_assoc($line_def_rst)) {
      	$lines_defs[$line_def['armc_budget_line_item_def_id']] = $line_def['armc_budget_line_item_description'];
      	$lines_defs_rate[$line_def['armc_budget_line_item_def_id']] = $line_def['default_rate'];
      	$lines_defs_quantity[$line_def['armc_budget_line_item_def_id']] = $line_def['default_quantity'];
      }

      $o["can_see_log"] = $userRights->userSecCheck("ARMC_SEE_EVENT_LOG");
      if ($o["can_see_log"]) {
         $event_log_rst = $atmDB->GetARMCEvents($o["armc_id"]);
         while ($event = mysql_fetch_assoc($event_log_rst)) {
            $events[] = $event;
         }
         $smarty->assign("events", $events);
      }

      $o["can_see_comments"] = $userRights->userSecCheck("ARMC_SEE_COMMENTS");
      if ($o["can_see_comments"]) {
         $comments_rst = $atmDB->GetARMCComments($o["armc_id"]);
         while ($comment = mysql_fetch_assoc($comments_rst)) {
         	$comments[] = $comment;
         }
         foreach($lines AS $index=>$line) {
            $line_comments_rst = $atmDB->GetARMCLineComments($line["armc_budget_line_item_id"]);
            while ($line_comment = mysql_fetch_assoc($line_comments_rst)) {
            	$lines[$index]["comments"][] = $line_comment;
            }
         }

         $smarty->assign("comments", $comments);
//         $smarty->assign("line_comments", $line_comments);
      }

      if ($o["change_billing_contact"]) {
//         @runkit_function_remove("HBRPC_GetPortlet");
//         @runkit_function_remove("HBRPC_GetAttrValues");
         $contact_type_ids = $atmDB->GetARMCSetting("BILLING_CONTACT_TYPES");
         $billing_contacts = HBRPCCall('acm', 'GetAccountContactsDetails', array('account_id'=>$header["account_id"], "address_type_id"=>"1", "contact_type_id"=>explode(",", $contact_type_ids["armc_setting_value"])));
         if ($billing_contacts["error_code"]!=0) {
            $header["billing_contacts"][] = $billing_contacts["error_code"]." - ".$billing_contacts["error_message"];
         }else{
            //$atmDB->debugPrint(print_r($billing_contacts, true));
            $details_array = "new Array(";
            foreach($billing_contacts as $billing_contact) {
               if (is_array($billing_contact)) {
               $details_array .=
               "new Array("
               ."'".mysql_real_escape_string($billing_contact["contact_title"])."', "
               ."'".mysql_real_escape_string($billing_contact["contact_first_name"])."', "
               ."'".mysql_real_escape_string($billing_contact["contact_last_name"])."', "
               ."'".mysql_real_escape_string($billing_contact["address_street_1"])."', "
               ."'".mysql_real_escape_string($billing_contact["address_street_2"])."', "
               ."'".mysql_real_escape_string($billing_contact["address_city"])."', "
               ."'".mysql_real_escape_string($billing_contact["address_state"])."', "
               ."'".mysql_real_escape_string($billing_contact["address_zip"])."', "
               ."'".mysql_real_escape_string($billing_contact["address_country_code"])."', "
               ."'".mysql_real_escape_string($billing_contact["country_description"])."', "
               ."'".mysql_real_escape_string($billing_contact["contact_phone_number"])."', "
               ."'".mysql_real_escape_string($billing_contact["contact_fax_number"])."', "
               ."'".mysql_real_escape_string($billing_contact["contact_email"])."'), ";
//               $set = false;
//               //echo ("<pre>");
//               //print_r($billing_contact["phone"]);
//               //echo ("</pre>");
//               if (is_array($billing_contact["phone"])) {
//               foreach($billing_contact["phone"] AS $phone) {
//                  if ($phone["phone_type_id"]==2 || $phone["phone_type_id"]==3) {
//                     if (!$set) $details_array .= "'".$phone["phone_country_code"].$phone["contact_phone_number"].$phone["contact_phone_ext"]."', ";
//                     $set = true;
//                  }
//               }
//               }
//               //echo ("Details : ".$details_array.", Set : ".$set."<br>");
//               if (!$set) $details_array .= "'', ";
//
//               $set = false;
//               if (is_array($billing_contact["phone"])) {
//               foreach($billing_contact["phone"] AS $phone) {
//                  if ($phone["phone_type_id"]==4) {
//                     if (!$set) $details_array .= "'".$phone["phone_country_code"].$phone["contact_phone_number"].$phone["contact_phone_ext"]."', ";
//                     $set = true;
//                  }
//               }
//               }
//               if (!$set) $details_array .= "'', ";
//               $details_array .= "'".$billing_contact["contact_email"]."'), ";
               $header["billing_contacts"][$billing_contact["contact_id"]] = $billing_contact["contact_title"]." ".$billing_contact["contact_first_name"]." ".$billing_contact["contact_last_name"];
               }
            }
         $billing_contacts_details_array = substr($details_array, 0, strlen($details_array)-2).")";
         $smarty->assign("contacts_details_array", $billing_contacts_details_array);
         }

      }

      if ($o["change_project_contact"]) {
//         @runkit_function_remove("HBRPC_GetPortlet");
//         @runkit_function_remove("HBRPC_GetAttrValues");
         $contact_type_ids = $atmDB->GetARMCSetting("PROJECT_CONTACT_TYPES");
         $project_contacts = HBRPCCall('acm', 'GetAccountContactsDetails', array('account_id'=>$header["account_id"], "address_type_id"=>"1", "contact_type_id"=>explode(",", $contact_type_ids["armc_setting_value"])));
         //$atmDB->debugPrint(print_r($project_contacts, true));
         if ($project_contacts["error_code"]!=0) {
            $header["project_contacts"][] = $project_contacts["error_code"]." - ".$project_contacts["error_message"];
         }else{
            $details_array = "new Array(";
            foreach($project_contacts as $project_contact) {
               if (is_array($project_contact)) {
               $details_array .=
               "new Array("
               ."'".mysql_real_escape_string($project_contact["contact_title"])."', "
               ."'".mysql_real_escape_string($project_contact["contact_first_name"])."', "
               ."'".mysql_real_escape_string($project_contact["contact_last_name"])."', "
               ."'".mysql_real_escape_string($project_contact["address_street_1"])."', "
               ."'".mysql_real_escape_string($project_contact["address_street_2"])."', "
               ."'".mysql_real_escape_string($project_contact["address_city"])."', "
               ."'".mysql_real_escape_string($project_contact["address_state"])."', "
               ."'".mysql_real_escape_string($project_contact["address_zip"])."', "
               ."'".mysql_real_escape_string($project_contact["address_country_code"])."', "
               ."'".mysql_real_escape_string($project_contact["country_description"])."', "
               ."'".mysql_real_escape_string($project_contact["contact_phone_number"])."', "
               ."'".mysql_real_escape_string($project_contact["contact_fax_number"])."', "
               ."'".mysql_real_escape_string($project_contact["contact_email"])."'), ";
//               echo ($details_array);
//               $set = false;
//               if (is_array($project_contact["phone"])) {
//               foreach($project_contact["phone"] AS $phone) {
//                  if ($phone["phone_type_id"]==2 || $phone["phone_type_id"]==3) {
//                     if (!$set) $details_array .= "'".$phone["phone_country_code"].$phone["contact_phone_number"].$phone["contact_phone_ext"]."', ";
//                     $set = true;
//                  }
//               }
//               }
//               if (!$set) $details_array .= "'', ";
//
//               $set = false;
//               if (is_array($project_contact["phone"])) {
//               foreach($project_contact["phone"] AS $phone) {
//                  if ($phone["phone_type_id"]==4) {
//                     if (!$set) $details_array .= "'".$phone["phone_country_code"].$phone["contact_phone_number"].$phone["contact_phone_ext"]."', ";
//                     $set = true;
//                  }
//               }
//               }
//               if (!$set) $details_array .= "'', ";
//               $details_array .= "'".$project_contact["contact_email"]."'), ";
               $header["project_contacts"][$project_contact["contact_id"]] = $project_contact["contact_title"]." ".$project_contact["contact_first_name"]." ".$project_contact["contact_last_name"];
               }
            }

            $project_contacts_details_array = substr($details_array, 0, strlen($details_array)-2).")";
            $smarty->assign("contacts_details_array", $project_contacts_details_array);
         }
      }

      $stall_comment_values = array();
      $delete_comment_values = array();
      $correct_comment_values = array();
      $rst = $atmDB->GetARMCCommentTypeValues(array(ARMC_COMMENT_TYPE_STALL, ARMC_COMMENT_TYPE_DELETE, ARMC_COMMENT_TYPE_CORRECT));
      while ($comment = mysql_fetch_assoc($rst)) {
         switch ($comment["armc_comment_type_id"]) {
         	case ARMC_COMMENT_TYPE_STALL :
              $stall_comment_values[] = "'".$comment["armc_comment_type_value_description"]."'";
        		  break;
        		case ARMC_COMMENT_TYPE_DELETE :
        		  $delete_comment_values[] = "'".$comment["armc_comment_type_value_description"]."'";
        		  break;
        		case ARMC_COMMENT_TYPE_CORRECT :
        		  $correct_comment_values[] = "'".$comment["armc_comment_type_value_description"]."'";
        		  break;
         }
      }

      $header['am_approved'] = $atmDB->ARMCIsApproved($header['armc_id'], ARMC_ACTION_TYPE_AM_APPROVED_HEADER);
      $header['ae_approved'] = $atmDB->ARMCIsApproved($header['armc_id'], ARMC_ACTION_TYPE_AE_APPROVED_HEADER);
      $header['acct_approved'] = $atmDB->ARMCIsApproved($header['armc_id'], ARMC_ACTION_TYPE_ACCT_APPROVED_HEADER);

      $invoice['am_approved'] = $atmDB->ARMCIsApproved($header['armc_id'], ARMC_ACTION_TYPE_AM_APPROVED_INVOICE);
      $invoice['ae_approved'] = $atmDB->ARMCIsApproved($header['armc_id'], ARMC_ACTION_TYPE_AE_APPROVED_INVOICE);
      $invoice['acct_approved'] = $atmDB->ARMCIsApproved($header['armc_id'], ARMC_ACTION_TYPE_ACCT_APPROVED_INVOICE);

      $o["can_change_roles"] = $userRights->userSecCheck("ATM_ARMC_CHG_ROLES");
      $o["can_see_account"] = $userRights->userSecCheck("ACM_CUST_VIEW_PARTNER");
      $o["can_change_invoice_date"] = $userRights->userSecCheck("ATM_ARMC_CHG_INV_DATE");
      $o['can_delete'] = $userRights->userSecCheck("CANDELBR");
      $o['can_stall'] = $userRights->userSecCheck("CANSTALL");
      $o['can_invoice'] = $userRights->userSecCheck("CINVOICE")&&($header['armc_status_id']==ARMC_STATUS_READY_FOR_INVOICE);
      $o["is_invoiced"] = IsInvoiced($header["armc_status_id"]);
      $o["is_cm"] = IsCreditMemo($header["armc_type_id"]);
      $o['can_edit'] =

         ((($header["armc_status_id"]==ARMC_STATUS_EDIT || $header["armc_status_id"]==ARMC_STATUS_STALLED)&&sizeof(array_intersect($armc_users, $reportees))!=0) ||
         (($header["armc_status_id"]==ARMC_STATUS_AM_APPROVAL)&&sizeof(array_intersect($armc_ams, $reportees))!=0) ||
         (($header["armc_status_id"]==ARMC_STATUS_AE_APPROVAL)&&sizeof(array_intersect($armc_aes, $reportees))!=0) ||
         (($header["armc_status_id"]==ARMC_STATUS_ACCT_APPROVAL)&&sizeof(array_intersect($armc_accts, $reportees))!=0));
//         ((sizeof(array_intersect($armc_users, $reportees))!=0)&&
//         (in_array($header['armc_status_id'], array(ARMC_STATUS_EDIT, ARMC_STATUS_AM_APPROVAL, ARMC_STATUS_AE_APPROVAL, ARMC_STATUS_ACCT_APPROVAL)))&&
//         (in_array($header['armc_type_id'], array(ARMC_TYPE_BR_STUDY, ARMC_TYPE_BR_TR, ARMC_TYPE_BR_LI, ARMC_TYPE_BR_SB, ARMC_TYPE_BR_OT))));

      $o["is_creator"] = ((sizeof(array_intersect($armc_creators, $reportees))!=0) &&($header['armc_status_id']==ARMC_STATUS_AM_APPROVAL));
      $o['is_am'] = ((sizeof(array_intersect($armc_ams, $reportees))!=0)&&($header['armc_status_id']==ARMC_STATUS_AM_APPROVAL));
      $o['is_ae'] = ((sizeof(array_intersect($armc_aes, $reportees))!=0)&&($header['armc_status_id']==ARMC_STATUS_AE_APPROVAL));
      $o['is_acct'] = ((sizeof(array_intersect($armc_accts, $reportees))!=0)&&($header['armc_status_id']==ARMC_STATUS_ACCT_APPROVAL));


      if (isset($o["retainer_value"])) {
         if ($o["retainer_type"]=="account") {
            $retainer["message"] = "There is a $".number_format($o["retainer_value"])." retainer for this account.";
            $retainer["value"] = $o["retainer_value"];
            $retainer["armc_budget_line_item_def_id"] = ARMC_BUDGET_LINE_DEF_RETAINER;
         }elseif ($o["retainer_type"]=="study") {
            $retainer["message"] = "There is a $".number_format($o["retainer_value"])." retainer for this study.";
            $retainer["value"] = $o["retainer_value"];
            $retainer["armc_budget_line_item_def_id"] = ARMC_BUDGET_LINE_DEF_RETAINER;
         }
      }
      // crys modify this area to display Add_Contact_Section---||---
      $flags['ADD_ADDITIONAL_CONTACT'] = 1;
      $contacts = array(
      0 => array(
      'salutation' 	=> 'Mr.',
      'first_name'	=> 'Singer',
      'last_name'		=> 'Nicholsan',
      'addr_1' 		=> 'cell membrane model',
      'phone' =>'03403423423',
      'city' => 'Newyork',
      'state' => 'NY',
      'zip' => '00500',
      'country' => 'US',
      'email' => 'cell@membranes.com'
      )
      );
      //end of Add_Contact_Section-----------------------------||---

      $smarty->assign('contacts', $contacts);
      $smarty->assign('flags', $flags);
      $smarty->assign("hdr", $header);
      $smarty->assign("invoice", $invoice);
      $smarty->assign("retainer", $retainer);
      $smarty->assign("i18n", array("currency_code"=>$i18n_currency, "exchange_rate"=>$i18n_rate, "edit"=>$i18n_edit, "unicode"=>$i18n_currency_unicode, "message"=>$i18n_message));
      $smarty->assign("lines", $lines);
      $smarty->assign("new_lines", $new_lines);
      $smarty->assign("budget_line_item_defs", array('descriptions'=>$lines_defs, 'descr'=>"new Array('', '".implode('\', \'', $lines_defs)."')", 'rates'=>"new Array('', '".implode('\', \'', $lines_defs_rate)."')", 'quantities'=>"new Array('', '".implode('\', \'', $lines_defs_quantity)."')"));
      $smarty->assign("comment_type_values", array("stall"=>"new Array(".implode(", ", $stall_comment_values).")", "delete"=>"new Array(".implode(", ", $delete_comment_values).")", "correct"=>"new Array(".implode(", ", $correct_comment_values).")"));
      $smarty->assign("totals", Array("proposed"=>$total_proposed, "proposed_i18n"=>$total_proposed_i18n, "actual"=>$total_actual, "actual_i18n"=>$total_actual_i18n, "net"=>$total_net, "net_i18n"=>$total_net_i18n, "delta"=>$total_delta));
      $smarty->assign("meta", $o);
      if (isInternalUser()) {
         $template = "app/atm/armc/vw_armc_details.tpl";
      } else {
         $template = "app/atm/armc/ext/vw_armc_details.tpl";
      }
      DisplayHeader('Billing Reports', 'armc', $o["action"]);
      $smarty->display($template);
      DisplayFooter();
      } else {
         header("Location: index.php");
      }
   }else{
      $atmDB->errorAlert("Invalid Billing Report ID : ".$o["armc_id"]);
   }

}
*/
/**
* DisplayARMCUsers()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sat Mar 25 16:52:39 PST 2006
*/
function DisplayARMCUsers($o)
{
   global $smarty;
   $atmDB = new atmDB();
   $comDB = new commonDB();
   $users_rst = $atmDB->GetARMCUsers($o["armc_id"]);
   while ($user = mysql_fetch_assoc($users_rst)) {
      $users[] = array("user_id"=>$user["login"], "user_name"=>$user["user_name"], "role_id"=>$user["role_id"]);
   }
   $roles_rst = $comDB->GetRoleListByModule('ATM');
   $roles_usernames_array = "new Array(new Array(), ";
   $roles_userids_array = "new Array(new Array(), ";
   while ($role = mysql_fetch_assoc($roles_rst)) {
      $roles[$role["role_id"]] = $role["role_description"];
      $roles_usernames_array .= "new Array(";
      $roles_userids_array .= "new Array(";
      $users_rst = $comDB->GetUsersByRoleId($role["role_id"]);
      while ($user = mysql_fetch_assoc($users_rst)) {
         $roles_usernames_array .= "'".$user["name"]."', ";
         $roles_userids_array .= "'".$user["login"]."', ";
      }
      if (substr($roles_usernames_array, strlen($roles_usernames_array)-2)==", ") {
         $roles_usernames_array = substr($roles_usernames_array, 0, strlen($roles_usernames_array)-2);
      }
      $roles_usernames_array .= "), ";
      if (substr($roles_userids_array, strlen($roles_userids_array)-2)==", ") {
         $roles_userids_array = substr($roles_userids_array, 0, strlen($roles_userids_array)-2);
      }
      $roles_userids_array .= "), ";
   }
   if (substr($roles_usernames_array, strlen($roles_usernames_array)-2)==", ") {
      $roles_usernames_array = substr($roles_usernames_array, 0, strlen($roles_usernames_array)-2);
   }
   $roles_usernames_array .= ")";
   if (substr($roles_userids_array, strlen($roles_userids_array)-2)==", ") {
      $roles_userids_array = substr($roles_userids_array, 0, strlen($roles_userids_array)-2);
   }
   $roles_userids_array .= ")";
//   echo ($roles_userids_array."<br>");
//   echo ($roles_usernames_array."<br>");
   $smarty->assign("roles_users", array("ids"=>$roles_userids_array, "names"=>$roles_usernames_array));
   $smarty->assign("users", $users);
   $smarty->assign("roles", $roles);
   $smarty->assign("armc_id", $o["armc_id"]);
   $smarty->assign("armc_id", $o["armc_id"]);
   $smarty->display("app/atm/armc/popup_armc_users.tpl");
}

/**
* SaveARMCUsers()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun Mar 26 13:17:18 PST 2006
*/
function SaveARMCUsers($o)
{
   global $smarty;
   $atmDB = new atmDB();
   $atmDB->DeleteARMCUsers($o["armc_id"]);
   for($i=1; $i<=10; $i++) {
      if ($o["role_id_$i"] != -1) {
         SetARMCUser($o["armc_id"], $o["role_id_$i"], $o["user_id_$i"]);
      }
   }
   $users_rst = $atmDB->GetARMCUsers($o["armc_id"]);
   $comment = "";
   while ($user = mysql_fetch_assoc($users_rst)) {
      $comment .= $user["user_name"]."-".$user["role_description"]."<br>";
   }
   $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_CHANGED_USERS, $comment);
   $smarty->display("close_popup.tpl");
}

/**
* DisplayGroupARMCDetails()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jan 30 10:46:09 PST 2006
*/
/*
function DisplayGroupARMCDetails($o)
{
   global $smarty, $userRights;
   $atmDB = new atmDB();
   $commonDB = new commonDB();

   $i18n_currency = $atmDB->GetARMCGroupAttr($o["armc_group_id"], "I18N_CURRENCY_CODE");
   if ($i18n_currency) {
      $i18n_rate = $atmDB->GetARMCGroupAttr($o["armc_group_id"], "I18N_EXCHANGE_RATE");
   }else{
      $i18n_currency = "USD";
      $i18n_rate = 1;
   }
   $i18n_currency_unicode = $commonDB->GetUnicodeCurrencySymbol($i18n_currency);
   $header_rst = $atmDB->GetARMCGroupHeader($o["armc_group_id"]);
   if ($header = mysql_fetch_assoc($header_rst)) {
      if (isInternalUser() || (isExternalUser() && (GetExternalPrimaryAccount() == $header["account_id"]) && IsVisibleByEXT($header["armc_status_id"]))) {
         if (IsCreditMemo($header["armc_type_id"])) {
            $header["cm_applied_to"] = $atmDB->GetARMCGroupAttr($o["armc_group_id"], "CMAPPTO");
         }
         $i18n_message = "";
         if ($i18n_currency!="USD") {
            if (IsInvoiced($header["armc_status_id"])) {
               $i18n_message = "This Billing Reports was invoiced in $i18n_currency, at an exchange rate of ".number_format($i18n_rate, 4)." USD for 1 $i18n_currency !!!<br>To switch between $i18n_currency and USD views, use the links to the right !!!";
            }else{
               $i18n_message = "This Billing Report will be invoiced in $i18n_currency at an exchange rate of ".number_format($i18n_rate, 4)." USD for 1 $i18n_currency !!!<br>To switch between USD (editable) and $i18n_currency views, use the links to the right !!!";
            }
         }
         $header["armc_group_date"] = substr($header["armc_group_date"], 0, 10);
         $reportees = GetReportees($_SESSION['admin_id'], 0);
         //$atmDB->debugPrint("Reportees : ".print_r($reportees, true));
         $armc_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id='".$header["armc_group_id"]."'");
         $armc_aes = array();
         $armc_ams = array();
         $armc_accts = array();
         $armc_creators = array();
         $total = 0;
         while ($armc = mysql_fetch_assoc($armc_rst)) {
            $total += $armc["amount"];
            $lines[] = $armc;
            //$armc_pr
            $armc_users_rst = $atmDB->GetARMCUsers($armc["armc_id"]);
            while ($armc_user = mysql_fetch_assoc($armc_users_rst)) {
               $armc_users[$armc_user['role_id'].$armc_user["login"]] = $armc_user['login'];
               $armc_user["armc_id"] .= $users[$armc_user["role_id"].$armc_user["login"]]["armc_id"].", ".$armc["armc_type_prefix"]."-".$armc["armc_id"];
               $users[$armc_user["role_id"].$armc_user["login"]] = $armc_user;
            }
            $armc_users[] = $armc["created_by"];
            $armc_creators[] = $armc["created_by"];

            $armc_users_rst = $atmDB->GetARMCUsers($armc["armc_id"], array(ROLE_ACCOUNT_EXECUTIVE, ROLE_PRIMARY_ACCT_EXEC, ROLE_SECONDARY_ACCT_EXEC));
            while ($armc_user = mysql_fetch_assoc($armc_users_rst)) {
               $armc_aes[] = $armc_user['login'];
            }
            $armc_users_rst = $atmDB->GetARMCUsers($armc["armc_id"], array(ROLE_ACCOUNT_MANAGER, ROLE_PRIMARY_ACCT_MGR, ROLE_PROJECT_MANAGER, ROLE_PRIMARY_PROJECT_MANAGER, ROLE_SECONDARY_PROJECT_MANAGER, ROLE_SECONDARY_ACCT_MGR));
            while ($armc_user = mysql_fetch_assoc($armc_users_rst)) {
               $armc_ams[] = $armc_user['login'];
            }
            $armc_users_rst = $atmDB->GetARMCUsers($armc["armc_id"], array(ROLE_ACCOUNTING));
            while ($armc_user = mysql_fetch_assoc($armc_users_rst)) {
               $armc_accts[] = $armc_user['login'];
            }
            $armc_users_rst = $atmDB->GetARMCUsers($armc["armc_id"], array(ROLE_CREATOR));
            while ($armc_user = mysql_fetch_assoc($armc_users_rst)) {
               $armc_creators[] = $armc_user['login'];
            }
            if (($header["armc_status_id"]==ARMC_STATUS_STALLED)&&($armc["armc_status_id"]==ARMC_STATUS_STALLED)) {
               $header["stall_reason"] = $atmDB->GetARMCLastEvent($armc["armc_id"], ARMC_EVENT_TYPE_STALLED);
               $header["stall_reason"] = ($header["stall_reason"]?$header["stall_reason"]["comment"]:"Unknown");
            }

         }
         $armc_users[] = $header["created_by"];
         $armc_creators[] = $header["created_by"];
         foreach($users AS $user) {
            $user["armc_id"] = substr($user["armc_id"], 2);
            $header["users"][] = $user;
         }
         $invoice_rst = $atmDB->GetARMCGroupInvoice($header["armc_group_id"]);
         $invoice = mysql_fetch_assoc($invoice_rst);

         //      $atmDB->debugPrint("ARMC Users : ".print_r($armc_users, true));
         //      $atmDB->debugPrint("ARMC AEs : ".print_r($armc_aes, true));
         //      $atmDB->debugPrint("ARMC AMs : ".print_r($armc_ams, true));
         //      $atmDB->debugPrint("ARMC Accountants : ".print_r($armc_accts, true));
         //If the currently logged in user, or one of his/her reportees is one of the users for this ARMC record, he/she can edit
         //the details on the Billing Report. NO EXCEPTIONS.

         //      $lines_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id = ".$header["armc_group_id"]);
         //      while ($line = mysql_fetch_assoc($lines_rst)) {
         //      }

         if ($o["change_billing_contact"]) {
//            @runkit_function_remove("HBRPC_GetPortlet");
//            @runkit_function_remove("HBRPC_GetAttrValues");
            $contact_type_ids = $atmDB->GetARMCSetting("BILLING_CONTACT_TYPES");
            $billing_contacts = HBRPCCall('acm', 'GetAccountContactsDetails', array('account_id'=>$header["account_id"], "address_type_id"=>"1", "contact_type_id"=>explode(",", $contact_type_ids["armc_setting_value"])));
            if ($billing_contacts["error_code"]!=0) {
               $header["billing_contacts"][] = $billing_contacts["error_code"]." - ".$billing_contacts["error_message"];
            }else{
               //$atmDB->debugPrint(print_r($billing_contacts, true));
               $details_array = "new Array(";
               foreach($billing_contacts as $billing_contact) {
                  if (is_array($billing_contact)) {
                     $details_array .=
                     "new Array("
                     ."'".$billing_contact["contact_title"]."', "
                     ."'".mysql_real_escape_string($billing_contact["contact_first_name"])."', "
                     ."'".mysql_real_escape_string($billing_contact["contact_last_name"])."', "
                     ."'".mysql_real_escape_string($billing_contact["address_street_1"])."', "
                     ."'".mysql_real_escape_string($billing_contact["address_street_2"])."', "
                     ."'".mysql_real_escape_string($billing_contact["address_city"])."', "
                     ."'".mysql_real_escape_string($billing_contact["address_state"])."', "
                     ."'".mysql_real_escape_string($billing_contact["address_zip"])."', "
                     ."'".mysql_real_escape_string($billing_contact["address_country_code"])."', "
                     ."'".mysql_real_escape_string($billing_contact["country_description"])."', ";
                     $set = false;
                     if (is_array($billing_contact["phone"])) {
                        foreach($billing_contact["phone"] AS $phone) {
                           if ($phone["phone_type_id"]==2 || $phone["phone_type_id"]==3) {
                              if (!$set) $details_array .= "'".$phone["phone_country_code"].$phone["contact_phone_number"].$phone["contact_phone_ext"]."', ";
                              $set = true;
                           }
                        }
                     }
                     if (!$set) $details_array .= "'', ";

                     $set = false;
                     if (is_array($billing_contact["phone"])) {
                        foreach($billing_contact["phone"] AS $phone) {
                           if ($phone["phone_type_id"]==4) {
                              if (!$set) $details_array .= "'".$phone["phone_country_code"].$phone["contact_phone_number"].$phone["contact_phone_ext"]."', ";
                              $set = true;
                           }
                        }
                     }
                     if (!$set) $details_array .= "'', ";
                     $details_array .= "'".$billing_contact["contact_email"]."'), ";
                     $header["billing_contacts"][$billing_contact["contact_id"]] = $billing_contact["contact_title"]." ".$billing_contact["contact_first_name"]." ".$billing_contact["contact_last_name"];
                  }
               }
               $billing_contacts_details_array = substr($details_array, 0, strlen($details_array)-2).")";
               $smarty->assign("contacts_details_array", $billing_contacts_details_array);
            }

         }

         $stall_comment_values = array();
         $delete_comment_values = array();
         $rst = $atmDB->GetARMCCommentTypeValues(array(ARMC_COMMENT_TYPE_STALL, ARMC_COMMENT_TYPE_DELETE));
         while ($comment = mysql_fetch_assoc($rst)) {
            switch ($comment["armc_comment_type_id"]) {
               case ARMC_COMMENT_TYPE_STALL :
               $stall_comment_values[] = "'".$comment["armc_comment_type_value_description"]."'";
               break;
               case ARMC_COMMENT_TYPE_DELETE :
               $delete_comment_values[] = "'".$comment["armc_comment_type_value_description"]."'";
               break;
            }
         }

         $o["can_see_account"] = $userRights->userSecCheck("ACM_CUST_VIEW_PARTNER");
         $o["can_change_invoice_date"] = $userRights->userSecCheck("ATM_ARMC_CHG_INV_DATE");
         $o['can_delete'] = $userRights->userSecCheck("CANDELBR");
         $o['can_stall'] = $userRights->userSecCheck("CANSTALL");
         $o['can_invoice'] = $userRights->userSecCheck("CINVOICE")&&($header['armc_status_id']==ARMC_STATUS_READY_FOR_INVOICE);
         $o["is_invoiced"] = IsInvoiced($header["armc_status_id"]);
         $o['can_edit'] =

         ((($header["armc_status_id"]==ARMC_STATUS_EDIT || $header["armc_status_id"]==ARMC_STATUS_STALLED)&&sizeof(array_intersect($armc_users, $reportees))!=0) ||
         (($header["armc_status_id"]==ARMC_STATUS_AM_APPROVAL)&&sizeof(array_intersect($armc_ams, $reportees))!=0) ||
         (($header["armc_status_id"]==ARMC_STATUS_AE_APPROVAL)&&sizeof(array_intersect($armc_aes, $reportees))!=0) ||
         (($header["armc_status_id"]==ARMC_STATUS_ACCT_APPROVAL)&&sizeof(array_intersect($armc_accts, $reportees))!=0));
         //         ((sizeof(array_intersect($armc_users, $reportees))!=0)&&
         //         (in_array($header['armc_status_id'], array(ARMC_STATUS_EDIT, ARMC_STATUS_AM_APPROVAL, ARMC_STATUS_AE_APPROVAL, ARMC_STATUS_ACCT_APPROVAL)))&&
         //         (in_array($header['armc_type_id'], array(ARMC_TYPE_BR_STUDY, ARMC_TYPE_BR_TR, ARMC_TYPE_BR_LI, ARMC_TYPE_BR_SB, ARMC_TYPE_BR_OT))));
         $o["is_creator"] = ((sizeof(array_intersect($armc_creators, $reportees))!=0)&&(in_array($header["armc_status_id"], array(ARMC_STATUS_EDIT, ARMC_STATUS_AM_APPROVAL, ARMC_STATUS_AE_APPROVAL, ARMC_STATUS_ACCT_APPROVAL, ARMC_STATUS_STALLED))));
         //      $o['is_am'] = ((sizeof(array_intersect($armc_ams, $reportees))!=0)&&($header['armc_status_id']==ARMC_STATUS_AM_APPROVAL));
         //      $o['is_ae'] = ((sizeof(array_intersect($armc_aes, $reportees))!=0)&&($header['armc_status_id']==ARMC_STATUS_AE_APPROVAL));
         //      $o['is_acct'] = ((sizeof(array_intersect($armc_accts, $reportees))!=0)&&($header['armc_status_id']==ARMC_STATUS_ACCT_APPROVAL));

         $smarty->assign("hdr", $header);
         $smarty->assign("invoice", $invoice);
         $smarty->assign("i18n", array("currency_code"=>$i18n_currency, "exchange_rate"=>$i18n_rate, "unicode"=>$i18n_currency_unicode, "message"=>$i18n_message));
         $smarty->assign("comment_type_values", array("stall"=>"new Array(".implode(", ", $stall_comment_values).")", "delete"=>"new Array(".implode(", ", $delete_comment_values).")"));
         $smarty->assign("lines", $lines);
         $smarty->assign("total", $total);
         $smarty->assign("meta", $o);
         if (isInternalUser()) {
            $template = "app/atm/armc/vw_armc_group_details.tpl";
         } else {
            $template = "app/atm/armc/ext/vw_armc_group_details.tpl";
         }
         DisplayHeader('Billing Reports', 'armc', $o["action"]);
         $smarty->display($template);
         DisplayFooter();
      } else {
         header("Location: index.php");
      }
   }else{
      $atmDB->errorAlert("Invalid Billing Report ID : ".$o["armc_group_id"]);
   }

}
*/
/**
* Refresh()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 03 17:09:30 PST 2006
*/
function Refresh($o)
{
   global $encryption;
   //Added to call new ContactManager class
   $contact_manager = new atm_armc_ContactsManager();

   $atmDB = new atmDB();
//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");
//   @runkit_function_remove("GetFlashReport");
//   @runkit_function_remove("DisplayFlashReport");
   switch ($o["refresh_type"]) {
   case "single" : {
      $armc_id = $o["armc_id"];
      $atmDB->DeleteARMCUsers($armc_id);
      $account_id = $atmDB->GetARMCAttr($armc_id, "ACCOUNT_ID");
      $study_id = $atmDB->GetARMCAttr($armc_id, "STUDY_ID");
      $require_study = RequireStudy($atmDB->GetARMCTypeID($o["armc_id"]));
      if ($require_study) {
         $study_details = HBRPCCall('pjm', 'GetStudyDetails', array("study_id"=>$study_id));
         //echo ("<pre>"); print_r($study_details); echo ("</pre>");
         if ($study_details["partner_id"]!=$account_id) {
            $account_id = $study_details["partner_id"];
         }
      }

      $account_details = HBRPCCall('acm', 'GetAccountDetail', array('account_id'=>$account_id));
      //echo ("<pre>"); print_r($account_details); echo ("</pre>");
      if ($account_details["error_code"]==0) {
            $atmDB->DeleteARMCUsers($armc_id);
            $atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_REFRESH);
            //SetARMCAttr($armc_id, "ACCOUNT_ID", $ac_details["partner_id"]);
            //Next, from ACM we set the account name, country code, and account-level attributes.
            SetARMCAttr($armc_id, "ACCOUNT_ID", $account_details["account_id"]);
            SetARMCAttr($armc_id, "ACCOUNT_NAME", $account_details["account_name"]);
            SetARMCAttr($armc_id, "ACCOUNT_COUNTRY_CODE", $account_details["country_code"]);
            if (isset($account_details["attr"])) {
            foreach($account_details["attr"] AS $attr) {
               SetARMCAttr($armc_id, $attr["account_attr_name"], $attr["account_attr_value"]);
//               switch ($attr["account_attr_name"]) {
//               case "AMRC_AE_APPROVAL_REQUIRED" : {
//                  SetARMCAttr($armc_id, "REQUIRE_AE_APPROVAL", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_EMAIL_INVOICE" : {
//                  SetARMCAttr($armc_id, "EMAIL_INVOICE", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_PRINT_INVOICE" : {
//                  SetARMCAttr($armc_id, "PRINT_INVOICE", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_MONTH_END_BILLING" : {
//                  SetARMCAttr($armc_id, "IS_MONTH_END", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_PO_REQUIRED" : {
//                  SetARMCAttr($armc_id, "PO_REQUIRED", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_JOB_REQUIRED" : {
//                  SetARMCAttr($armc_id, "JOB_REQUIRED", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_PM_REQUIRED" : {
//                  SetARMCAttr($armc_id, "PM_REQUIRED", $attr["account_attr_value"]);
//               }break;
//               }
            }
            }

            //Next we set the user roles from ACM, but they might get overwriten
            //if the same role is also set at the Study level.
            if (isset($account_details["user"])) {
            foreach ($account_details["user"] AS $user) {
               SetARMCUser($armc_id, $user["role_id"], $user["user_id"]);
            }
            }

      }else{
         $error = "&error=".$account_details["error_message"].".";
      }


      if ($require_study) {
      if ($study_details["error_code"]==0) {
            //Next, there are a few attributes that could already be set from STM
            //SetARMCAttr($armc_id, "STUDY_ID", $study_details["study_id"]);
            SetARMCAttr($armc_id, "STUDY_NAME", $study_details["study_name"]);
            if (isset($study_details["attr"])) {
            foreach ($study_details["attr"] AS $attr_name=>$attr_value) {
               switch ($attr_name) {
               case "JOBNUMBER": {
                  SetARMCAttr($armc_id, "JOBNUMBER", $attr_value);
               }break;
               case "PONUMBER": {
                  SetARMCAttr($armc_id, "PONUMBER", $attr_value);
               }break;
               case "PROPOSAL_ID": {
                  SetARMCAttr($armc_id, "PROPOSAL_ID", $attr_value);                  
               }break;
               case "PROPOSAL_REVISION_ID": {
                  SetARMCAttr($armc_id, "PROPOSAL_REVISION_ID", $attr_value);                  
               }break;
               case "PROPOSAL_NAME": {
                  SetARMCAttr($armc_id, "PROPOSAL_NAME", $attr_value);                  
               }break;
               case "PROPOSAL_REVISION_NUM": {
                  SetARMCAttr($armc_id, "PROPOSAL_REVISION_NUM", $attr_value);                  
               }break;
               }
            }
            }

            //Next we set the user roles from STM, which in some cases might overwrite
            //the user roles that we got from ACM.
            if (isset($study_details["user_roles"])) {
            foreach($study_details["user_roles"] AS $user) {
               SetARMCUser($armc_id, $user["role_id"], $user["login"]);
            }
            }

            //And finaly, if the billing and/or project contacts are set in STM, set them at BR level as well.
            if (isset($study_details["contacts"])) {
               foreach ($study_details["contacts"] AS $contact) {
               switch ($contact["study_contact_type_id"]) {
                  case STUDY_CONTACT_PROJECT_MANAGER:
               	   $contact["armc_contact_type_id"] = ARMC_CONTACT_TYPE_PROJECT;

               	   $contact_manager->SetARMCContact($armc_id, $contact);
               	   //SetARMCContact($armc_id, $contact);

                     SetARMCAttr($armc_id, "PMNAME", $contact["salutation"]." ".$contact["first_name"]." ".$contact["last_name"]);
                     break;
               }
               }
            }

      }else{
         $error = "&error=".$study_details["error_message"].".";
      }
      }
            //This is temporary measure to make sure all BRs have an accounting person assigned.
      $users_rst = $atmDB->GetARMCUsers($armc_id, array(ROLE_ACCOUNTING));
      if ($atmDB->rows==0) {
          $default_acct = $atmDB->GetARMCSetting("DEFAULT_ROLE_ID_17");
          $default_acct = $default_acct["armc_setting_value"];
          SetARMCUser($armc_id, ROLE_ACCOUNTING, $default_acct);

      }
      //print_r($details);
      if (!isset($o["dont_redirect"])) {
         header("Location: ?e=".$encryption->Encrypt("action=display_armc_details&armc_id=".$o["armc_id"]."&dont_log_view=1$error"));
         exit;
      }
   }break;

   case "group" : {
      $armc_group_id = $o["armc_group_id"];
      $account_id = $atmDB->GetARMCGroupAttr($armc_group_id, "ACCOUNT_ID");
      $account_details = HBRPCCall('acm', 'GetAccountDetail', array('account_id'=>$account_id));
      $require_study = RequireStudy($atmDB->GetARMCGroupTypeID($armc_group_id));
      if ($account_details["error_code"]==0) {
         SetARMCGroupAttr($armc_group_id, "ACCOUNT_NAME", $account_details["account_name"]);
         SetARMCGroupAttr($armc_group_id, "ACCOUNT_COUNTRY_CODE", $account_details["country_code"]);
         foreach($account_details["attr"] AS $attr) {
            SetARMCGroupAttr($o["armc_group_id"], $attr["account_attr_name"], $attr["account_attr_value"]);
//            switch ($attr["account_attr_name"]) {
//            case "AMRC_AE_APPROVAL_REQUIRED" : {
//               SetARMCGroupAttr($o["armc_group_id"], "REQUIRE_AE_APPROVAL", $attr["account_attr_value"]);
//            }break;
//            case "ARMC_EMAIL_INVOICE" : {
//               SetARMCGroupAttr($o["armc_group_id"], "EMAIL_INVOICE", $attr["account_attr_value"]);
//            }break;
//            case "ARMC_PRINT_INVOICE" : {
//               SetARMCGroupAttr($o["armc_group_id"], "PRINT_INVOICE", $attr["account_attr_value"]);
//            }break;
//            case "ARMC_MONTH_END_BILLING" : {
//               SetARMCGroupAttr($o["armc_group_id"], "IS_MONTH_END", $attr["account_attr_value"]);
//            }break;
//            case "ARMC_PO_REQUIRED" : {
//               SetARMCGroupAttr($o["armc_group_id"], "PO_REQUIRED", $attr["account_attr_value"]);
//            }break;
//            case "ARMC_JOB_REQUIRED" : {
//               SetARMCGroupAttr($o["armc_group_id"], "JOB_REQUIRED", $attr["account_attr_value"]);
//            }break;
//            case "ARMC_PM_REQUIRED" : {
//               SetARMCGroupAttr($o["armc_group_id"], "PM_REQUIRED", $attr["account_attr_value"]);
//            }break;
//            }
         }
         $lines_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id = '".$o["armc_group_id"]."'", "ORDER BY armc_id");
         while ($line = mysql_fetch_assoc($lines_rst)) {
            $atmDB->DeleteARMCUsers($line["armc_id"]);
            $atmDB->WriteARMCEvent($line["armc_id"], ARMC_EVENT_TYPE_REFRESH);
            SetARMCAttr($line["armc_id"], "ACCOUNT_ID", $account_id);
            SetARMCAttr($line["armc_id"], "ACCOUNT_NAME", $account_details["account_name"]);
            SetARMCAttr($line["armc_id"], "ACCOUNT_COUNTRY_CODE", $account_details["country_code"]);
            foreach($account_details["attr"] AS $attr) {
               SetARMCAttr($line["armc_id"], $attr["account_attr_name"], $attr["account_attr_value"]);
//               switch ($attr["account_attr_name"]) {
//               case "AMRC_AE_APPROVAL_REQUIRED" : {
//                  SetARMCAttr($line["armc_id"], "REQUIRE_AE_APPROVAL", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_EMAIL_INVOICE" : {
//                  SetARMCAttr($line["armc_id"], "EMAIL_INVOICE", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_PRINT_INVOICE" : {
//                  SetARMCAttr($line["armc_id"], "PRINT_INVOICE", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_MONTH_END_BILLING" : {
//                  SetARMCAttr($line["armc_id"], "IS_MONTH_END", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_PO_REQUIRED" : {
//                  SetARMCAttr($line["armc_id"], "PO_REQUIRED", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_JOB_REQUIRED" : {
//                  SetARMCAttr($line["armc_id"], "JOB_REQUIRED", $attr["account_attr_value"]);
//               }break;
//               case "ARMC_PM_REQUIRED" : {
//                  SetARMCAttr($line["armc_id"], "PM_REQUIRED", $attr["account_attr_value"]);
//               }break;
//               }
            }
            //Next we set the user roles from ACM, but they might get overwriten
            //if the same role is also set at the Study level.
            if (isset($account_details["user"])) {
            foreach ($account_details["user"] AS $user) {
               SetARMCUser($line["armc_id"], $user["role_id"], $user["user_id"]);
            }
            }
            $study_id = $atmDB->GetARMCAttr($line["armc_id"], "STUDY_ID");
            if ($require_study && $study_id) {
               $study_details = HBRPCCall('pjm', 'GetStudyDetails', array("study_id"=>$study_id));
               if ($study_details["error_code"]==0) {

                  SetARMCAttr($line["armc_id"], "STUDY_NAME", $study_details["study_name"]);
                  if (isset($study_details["attr"])) {
                  foreach ($study_details["attr"] AS $attr_name=>$attr_value) {
                     switch ($attr_name) {
                     case "JOBNUMBER": {
                        SetARMCAttr($line["armc_id"], "JOBNUMBER", $attr_value);
                     }break;
                     case "PONUMBER": {
                        SetARMCAttr($line["armc_id"], "PONUMBER", $attr_value);
                     }break;
                     case "PROPOSAL_ID": {
                        SetARMCAttr($line["armc_id"], "PROPOSAL_ID", $attr_value);                  
                     }break;
                     case "PROPOSAL_REVISION_ID": {
                        SetARMCAttr($line["armc_id"], "PROPOSAL_REVISION_ID", $attr_value);                  
                     }break;
                     case "PROPOSAL_NAME": {
                        SetARMCAttr($line["armc_id"], "PROPOSAL_NAME", $attr_value);                  
                     }break;
                     case "PROPOSAL_REVISION_NUM": {
                        SetARMCAttr($line["armc_id"], "PROPOSAL_REVISION_NUM", $attr_value);                  
                     }break;
                     }
                  }
                  }

                  //Next we set the user roles from STM, which in some cases might overwrite
                  //the user roles that we got from ACM.
                  if (isset($study_details["user_roles"])) {
                  foreach($study_details["user_roles"] AS $user) {
                     SetARMCUser($line["armc_id"], $user["role_id"], $user["login"]);
                  }
                  }

                  //And finaly, if the billing and/or project contacts are set in STM, set them at BR level as well.
                  if (isset($study_details["contacts"])) {
                  foreach ($study_details["contacts"] AS $contact) {

                  	//SetARMCContact($line["armc_id"], $contact);
                  	$contact_manager->SetARMCContact($line["armc_id"], $contact);

                  }
                  }
            }else{
               $error = "&error=".$study_details["error_message"].".";
            }
            }

            $users_rst = $atmDB->GetARMCUsers($line["armc_id"], array(ROLE_ACCOUNTING));
            if ($atmDB->rows==0) {
                $default_acct = $atmDB->GetARMCSetting("DEFAULT_ROLE_ID_17");
                $default_acct = $default_acct["armc_setting_value"];
                SetARMCUser($line["armc_id"], ROLE_ACCOUNTING, $default_acct);

            }

         }
      }else{
         $error = "&error=".$account_details["error_message"].".";
      }
      if (!isset($o["dont_redirect"])) {
         header("Location: ?e=".$encryption->Encrypt("action=display_group_details&armc_group_id=".$o["armc_group_id"].$error));
         exit;
      }
   }break;

   }

}

/**
* DisplayBulkUpdate()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon May 01 12:49:50 PDT 2006
*/
function DisplayBulkUpdate($o)
{
	global $smarty;
   $atmDB = new atmDB();
   if (isset($o["updates"])) {
      foreach ($o["update_list"] as $update_item) {
      	$update_list[] = preg_split("/_/", $update_item);
      }
      //print_r($update_list);
      $updates = $o["updates"];
      foreach ($update_list AS $update_item) {
         foreach ($updates AS $update_type) {
            switch ($update_type) {
            	case "refresh": {
            		$param["refresh_type"]=($update_item[0]=="true")?"group":"single";
            		$param["armc_id"] = $update_item[1];
            		$param["dont_redirect"] = 1;
            		Refresh($param);
            	}break;

            	case "set_transaction_date": {
            	   if ($update_item[0]=="true") {
            	        $rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id = ".$update_item[1]);
            	        while ($a = mysql_fetch_assoc($rst)) {
            	           $atmDB->SetARMCTransactionDate($a["armc_id"], "'".date("Y-m-".GetLastDateOfMonth(strtotime("last month", time())), strtotime("last month", time()))."'");
            	        }
            	   } else {
            	        $atmDB->SetARMCTransactionDate($update_item[1], "'".date("Y-m-".GetLastDateOfMonth(strtotime("last month", time())), strtotime("last month", time()))."'");
            	   }
            	}break;
            }
         }
      }
      $url = "?action=default&search_account_id=".$o["search_account_id"]."&search_study_id=".$o["search_study_id"];
      foreach ($o["search_status_id"] AS $id) {
         $url .= "&search_status_id[]=$id";
      }
      header("Location: $url");
   } elseif (isset($o["search_account_id"])) {
      $filter = BuildSearchFilter($o);
      $rst = $atmDB->GetARMCList($filter, "ORDER BY armc_status_id");
      while ($armc = mysql_fetch_assoc($rst)) {
         if (IsCreditMemo($armc["armc_type_id"])) {
            $total -= $armc["amount"];
            $armc["display_amount"] = "<font color=red>$".number_format($armc["amount"], 2)."</font>";
         }else{
            $total += $armc["amount"];
            $armc["display_amount"] = "$".number_format($armc["amount"], 2);
         }
         $list[] = $armc;
      }
      //$header[0] = array("width"=>"2%", "sort"=>"yes", "title"=>"++", "field"=>"grouped");
      $header[0] = array("align"=>"left", "width"=>"10%", "sort"=>"yes", "title"=>"Status", "field"=>"armc_status_description");
      $header[1] = array("align"=>"left", "width"=>"6%", "sort"=>"yes", "title"=>"BR #", "field"=>"armc_id");
      $header[2] = array("align"=>"left", "width"=>"6%", "sort"=>"yes", "title"=>"Study ID", "field"=>"study_id");
      $header[3] = array("align"=>"left", "width"=>"15%", "sort"=>"yes", "title"=>"Study Name", "field"=>"study_name");
      $header[4] = array("align"=>"left", "width"=>"5%%", "sort"=>"yes", "title"=>"Account ID", "field"=>"account_id");
      $header[5] = array("align"=>"left", "width"=>"16%", "sort"=>"yes", "title"=>"Account Name", "field"=>"account_name");
      $header[6] = array("align"=>"center", "width"=>"8%", "sort"=>"yes", "title"=>"BR Date", "field"=>"armc_date");
      $header[7] = array("align"=>"center", "width"=>"10%", "sort"=>"yes", "title"=>"Invoice Date", "field"=>"transaction_date");
      $header[8] = array("align"=>"right", "width"=>"6%", "sort"=>"yes", "title"=>"AE", "field"=>"account_executive");
      $header[9] = array("align"=>"right", "width"=>"6%", "sort"=>"yes", "title"=>"AM", "field"=>"account_manager");
      $header[10] = array("align"=>"right", "width"=>"10%", "sort"=>"yes", "title"=>"Amount", "field"=>"amount");

      $smarty->assign("header", $header);
      $smarty->assign("list", $list);
      $smarty->assign("total", $total);
      $smarty->assign("meta", $o);
      $smarty->assign("update_options", array("refresh"=>"Refresh header", "set_transaction_date"=>"Set invoice date to ".date("Y-m-".GetLastDateOfMonth(strtotime("last month", time())), strtotime("last month", time()))));
      $smarty->assign("update_options_selected", array("refresh"));
      DisplayHeader("Billing Reports", "armc", $o["action"]);
      $smarty->display("app/atm/armc/vw_search_bulk_update.tpl");
      $smarty->display("app/atm/armc/vw_bulk_update_list.tpl");
      DisplayFooter();
   }else{
      DisplayHeader("Billing Reports", "armc", $o["action"]);
      $smarty->display("app/atm/armc/vw_search_bulk_update.tpl");
      DisplayFooter();
   }
}

/**
* SendApprovalAlert()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 17 15:59:00 PST 2006
*/
function SendApprovalAlert($armc_id, $message_type_id)
{
   global $smarty;
   $atmDB = new atmDB();
   $commonDB = new commonDB();

   $armc_type = $atmDB->GetARMCType($armc_id);
   $armc_type_id = $armc_type["armc_type_id"];
   $armc_type_prefix = $armc_type["armc_type_prefix"];
   $armc_status_id = $atmDB->GetARMCStatusID($armc_id);
   $account_id = $atmDB->GetARMCAttr($armc_id, "ACCOUNT_ID");
   $account_name = $atmDB->GetARMCAttr($armc_id, "ACCOUNT_NAME");
   $study_id = $atmDB->GetARMCAttr($armc_id, "STUDY_ID");
   $study_name = $atmDB->GetARMCAttr($armc_id, "STUDY_NAME");
   $primary_am_id_rst = $atmDB->GetARMCUsers($armc_id, array(ROLE_PRIMARY_ACCT_MGR));
   $primary_am_id = mysql_fetch_assoc($primary_am_id_rst);
   if ($primary_am_id) {
      $primary_am_name = $primary_am_id["user_name"];
      $primary_am_id = $primary_am_id["login"];
   }
   $primary_ae_id_rst = $atmDB->GetARMCUsers($armc_id, array(ROLE_PRIMARY_ACCT_EXEC));
   $primary_ae_id = mysql_fetch_assoc($primary_ae_id_rst);
   if ($primary_ae_id) {
      $primary_ae_name = $primary_ae_id["user_name"];
      $primary_ae_id = $primary_ae_id["login"];
   }
   $primary_acct_id_rst = $atmDB->GetARMCUsers($armc_id, array(ROLE_ACCOUNTING));
   $primary_acct_id = mysql_fetch_assoc($primary_acct_id_rst);
   if ($primary_acct_id) {
      $primary_acct_name = $primary_acct_id["user_name"];
      $primary_acct_id = $primary_acct_id["login"];
   }

   $amount = 0;
   $lines_rst = $atmDB->GetARMCLines($armc_id);
   while ($line = mysql_fetch_assoc($lines_rst)) {
   	$amount += $line["actual_amount"];
   }
//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");

   switch ($message_type_id) {
   case ATM_MESSAGE_TYPE_AM_APPROVAL : {
      $rcpt_rst = $atmDB->GetARMCUsers($armc_id, array(ROLE_SECONDARY_ACCT_MGR, ROLE_PRIMARY_ACCT_MGR, ROLE_ACCOUNT_MANAGER, ROLE_PROJECT_MANAGER, ROLE_PRIMARY_PROJECT_MANAGER, ROLE_SECONDARY_PROJECT_MANAGER));
      while ($rcpt = mysql_fetch_assoc($rcpt_rst)) {
      	$rcpts[] = $rcpt["login"];
      }
      $smarty->assign("server_name", SERVER_NAME);
      $smarty->assign("details", array("armc_id"=>$armc_id, "study_id"=>$study_id, "study_name"=>$study_name, "account_id"=>$account_id, "account_name"=>$account_name));
      $message = $smarty->fetch("app/atm/armc/email_approval.tpl");
      $queue_message_attrs = array(
         "message_type_id"=>$message_type_id,
         "rcpt"=>$rcpts,
         "msg"=>array("subject"=>"BR APPROVAL [".$account_name."] - [".$armc_type_prefix."-".$armc_id."]", "body"=>$message),
         "attr"=>array(
            "armc_id"=>$armc_id,
            "armc_status_id"=>$armc_status_id,
            "armc_type_id"=>$armc_type_id,
            "account_id"=>$account_id,
            "study_id"=>$study_id,
            "primary_ae_id"=>$primary_ae_id,
            "primary_am_id"=>$primary_am_id,
            "amount"=>$amount));
      //$atmDB->debugPrint("Queue Message Params : ".print_r($queue_message_attrs, true));
      $received_by = HBRPCCall('com', 'QueueMessage', $queue_message_attrs);
      //$atmDB->debugPrint("<p>Received by : ".print_r($received_by, true));
      $alert_count = $atmDB->GetARMCEventCount($armc_id, ARMC_EVENT_TYPE_AM_APPROVED_HEADER);
      if ($alert_count > ARMC_APPROVAL_ESCALATION_THRESHOLD) {
         $rcpt_rst = $commonDB->GetReportsTo($primary_am_id);
         while ($rcpt = mysql_fetch_assoc($rcpt_rst)) {
         	$escalation_rcpts[] = $rcpt["login"];
         }
         $smarty->assign("server_name", SERVER_NAME);
         $smarty->assign("details", array("armc_id"=>$armc_id, "name"=>$primary_am_name, "study_id"=>$study_id, "study_name"=>$study_name, "account_id"=>$account_id, "account_name"=>$account_name));
         $message = $smarty->fetch("app/atm/armc/email_approval_escalation.tpl");
         $queue_message_attrs = array(
            "message_type_id"=>ATM_MESSAGE_TYPE_AM_APPROVAL_ESCALATION,
            "rcpt"=>$escalation_rcpts,
            "msg"=>array("subject"=>"BR APPROVAL [".$account_name."] - [".$armc_type_prefix."-".$armc_id."]", "body"=>$message),
            "attr"=>array(
               "armc_id"=>$armc_id,
               "armc_status_id"=>$armc_status_id,
               "armc_type_id"=>$armc_type_id,
               "account_id"=>$account_id,
               "study_id"=>$study_id,
               "primary_ae_id"=>$primary_ae_id,
               "primary_am_id"=>$primary_am_id,
               "amount"=>$amount,
               "alert_count"=>$alert_count));
         //$atmDB->debugPrint("<p>Escalation Queue Message Params : ".print_r($queue_message_attrs, true));
         $received_by = HBRPCCall('com', 'QueueMessage', $queue_message_attrs);
         //$atmDB->debugPrint("<p>Received by : ".print_r($received_by, true));
      }
   }break;

   case ATM_MESSAGE_TYPE_AE_APPROVAL : {
      $rcpt_rst = $atmDB->GetARMCUsers($armc_id, array(ROLE_PRIMARY_ACCT_EXEC, ROLE_ACCOUNT_EXECUTIVE, ROLE_SECONDARY_ACCT_EXEC));
      while ($rcpt = mysql_fetch_assoc($rcpt_rst)) {
      	$rcpts[] = $rcpt["login"];
      }
      $smarty->assign("server_name", SERVER_NAME);
      $smarty->assign("details", array("armc_id"=>$armc_id, "study_id"=>$study_id, "study_name"=>$study_name, "account_id"=>$account_id, "account_name"=>$account_name));
      $message = $smarty->fetch("app/atm/armc/email_approval.tpl");
      $queue_message_attrs = array(
         "message_type_id"=>$message_type_id,
         "rcpt"=>$rcpts,
         "msg"=>array("subject"=>"BR APPROVAL [".$account_name."] - [".$armc_type_prefix."-".$armc_id."]", "body"=>$message),
         "attr"=>array(
            "armc_id"=>$armc_id,
            "armc_status_id"=>$armc_status_id,
            "armc_type_id"=>$armc_type_id,
            "account_id"=>$account_id,
            "study_id"=>$study_id,
            "primary_ae_id"=>$primary_ae_id,
            "primary_am_id"=>$primary_am_id,
            "amount"=>$amount));
      //$atmDB->debugPrint("Queue Message Params : ".print_r($queue_message_attrs, true));
      $received_by = HBRPCCall('com', 'QueueMessage', $queue_message_attrs);
      //$atmDB->debugPrint("<p>Received by : ".print_r($received_by, true));
      $alert_count = $atmDB->GetARMCEventCount($armc_id, ARMC_EVENT_TYPE_AE_APPROVED_HEADER);
      if ($alert_count > ARMC_APPROVAL_ESCALATION_THRESHOLD) {
         $rcpt_rst = $commonDB->GetReportsTo($primary_ae_id);
         while ($rcpt = mysql_fetch_assoc($rcpt_rst)) {
         	$escalation_rcpts[] = $rcpt["login"];
         }
         $smarty->assign("server_name", SERVER_NAME);
         $smarty->assign("details", array("armc_id"=>$armc_id, "name"=>$primary_ae_name, "study_id"=>$study_id, "study_name"=>$study_name, "account_id"=>$account_id, "account_name"=>$account_name));
         $message = $smarty->fetch("app/atm/armc/email_approval_escalation.tpl");
         $queue_message_attrs = array(
            "message_type_id"=>ATM_MESSAGE_TYPE_AE_APPROVAL_ESCALATION,
            "rcpt"=>$escalation_rcpts,
            "msg"=>array("subject"=>"BR APPROVAL [".$account_name."] - [".$armc_type_prefix."-".$armc_id."]", "body"=>$message),
            "attr"=>array(
               "armc_id"=>$armc_id,
               "armc_status_id"=>$armc_status_id,
               "armc_type_id"=>$armc_type_id,
               "account_id"=>$account_id,
               "study_id"=>$study_id,
               "primary_ae_id"=>$primary_ae_id,
               "primary_am_id"=>$primary_am_id,
               "amount"=>$amount,
               "alert_count"=>$alert_count));
         //$atmDB->debugPrint("<p>Escalation Queue Message Params : ".print_r($queue_message_attrs, true));
         $received_by = HBRPCCall('com', 'QueueMessage', $queue_message_attrs);
         //$atmDB->debugPrint("<p>Received by : ".print_r($received_by, true));
      }
   }break;

   case ATM_MESSAGE_TYPE_ACCT_APPROVAL : {
      $rcpt_rst = $atmDB->GetARMCUsers($armc_id, array(ROLE_ACCOUNTING));
      while ($rcpt = mysql_fetch_assoc($rcpt_rst)) {
      	$rcpts[] = $rcpt["login"];
      }
      $smarty->assign("server_name", SERVER_NAME);
      $smarty->assign("details", array("armc_id"=>$armc_id, "study_id"=>$study_id, "study_name"=>$study_name, "account_id"=>$account_id, "account_name"=>$account_name));
      $message = $smarty->fetch("app/atm/armc/email_approval.tpl");
      $queue_message_attrs = array(
         "message_type_id"=>$message_type_id,
         "rcpt"=>$rcpts,
         "msg"=>array("subject"=>"BR APPROVAL [".$account_name."] - [".$armc_type_prefix."-".$armc_id."]", "body"=>$message),
         "attr"=>array(
            "armc_id"=>$armc_id,
            "armc_status_id"=>$armc_status_id,
            "armc_type_id"=>$armc_type_id,
            "account_id"=>$account_id,
            "study_id"=>$study_id,
            "primary_ae_id"=>$primary_ae_id,
            "primary_am_id"=>$primary_am_id,
            "amount"=>$amount));
      //$atmDB->debugPrint("Queue Message Params : ".print_r($queue_message_attrs, true));
      $received_by = HBRPCCall('com', 'QueueMessage', $queue_message_attrs);
      //$atmDB->debugPrint("<p>Received by : ".print_r($received_by, true));
      $alert_count = $atmDB->GetARMCEventCount($armc_id, ARMC_EVENT_TYPE_ACCT_APPROVED_HEADER);
      if ($alert_count > ARMC_APPROVAL_ESCALATION_THRESHOLD) {
         $rcpt_rst = $commonDB->GetReportsTo($primary_acct_id);
         while ($rcpt = mysql_fetch_assoc($rcpt_rst)) {
         	$escalation_rcpts[] = $rcpt["login"];
         }
         $smarty->assign("server_name", SERVER_NAME);
         $smarty->assign("details", array("armc_id"=>$armc_id, "name"=>$primary_acct_name, "study_id"=>$study_id, "study_name"=>$study_name, "account_id"=>$account_id, "account_name"=>$account_name));
         $message = $smarty->fetch("app/atm/armc/email_approval_escalation.tpl");
         $queue_message_attrs = array(
            "message_type_id"=>ATM_MESSAGE_TYPE_ACCT_APPROVAL_ESCALATION,
            "rcpt"=>$escalation_rcpts,
            "msg"=>array("subject"=>"BR APPROVAL [".$account_name."] - [".$armc_type_prefix."-".$armc_id."]", "body"=>$message),
            "attr"=>array(
               "armc_id"=>$armc_id,
               "armc_status_id"=>$armc_status_id,
               "armc_type_id"=>$armc_type_id,
               "account_id"=>$account_id,
               "study_id"=>$study_id,
               "primary_ae_id"=>$primary_ae_id,
               "primary_am_id"=>$primary_am_id,
               "amount"=>$amount,
               "alert_count"=>$alert_count));
         //$atmDB->debugPrint("<p>Escalation Queue Message Params : ".print_r($queue_message_attrs, true));
         $received_by = HBRPCCall('com', 'QueueMessage', $queue_message_attrs);
         //$atmDB->debugPrint("<p>Received by : ".print_r($received_by, true));
      }
   }break;

   }

}

/**
* SendManualExportResults()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Feb 22 13:09:06 PST 2006
*/
function SendManualExportResults($armc_id, $inv, $errors, $merged=false)
{
   global $smarty;
   $atmDB = new atmDB();
   if ($merged) {
      $details_rst = $atmDB->GetARMCGroupHeader($armc_id);
      $details = mysql_fetch_assoc($details_rst);
      $list_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id = '$armc_id'", "");
      while ($armc = mysql_fetch_assoc($list_rst)) {
         $users_rst = $atmDB->GetARMCUsers($armc["armc_id"], array(ROLE_ACCOUNTING));
         while ($user = mysql_fetch_assoc($users_rst)) {
         	$rcpts[] = $user["login"];
         }
      }
   }else{
      $details_rst = $atmDB->GetARMCHeader($armc_id);
      $details = mysql_fetch_assoc($details_rst);
      $users_rst = $atmDB->GetARMCUsers($details["armc_id"], array(ROLE_ACCOUNTING));
      while ($user = mysql_fetch_assoc($users_rst)) {
      	$rcpts[] = $user["login"];
      }
   }
   $rcpts[] = $_SESSION["admin_id"];
   $default_acct = $atmDB->GetARMCSetting("DEFAULT_ROLE_ID_17");
   $rcpts[] = $default_acct["armc_setting_value"];

   $smarty->assign("errors", array("count"=>sizeof($errors), "list"=>$errors));
   $smarty->assign("invoices", $inv);
   $message = $smarty->fetch("app/atm/armc/email_manual_export.tpl");
   $queue_message_attrs = array(
      "message_type_id"=>ATM_MESSAGE_TYPE_ORACLE_MANUAL_EXPORT,
      "rcpt"=>$rcpts,
      "msg"=>array(
         "subject"=>"Oracle Manual Export Results",
         "body"=>$message),
      "attr"=>array(
         "armc_id"=>$armc_id,
         "account_id"=>$details["account_id"],
         "study_id"=>$details["study_id"],
         "armc_type_id"=>$details["armc_type_id"],
         "errors"=>sizeof($errors)));

//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");
   $received_by = HBRPCCall('com', 'QueueMessage', $queue_message_attrs);
//   echo ($message);
//   print_r($received_by);
}

/**
* SendBatchExportResults()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Feb 22 14:22:55 PST 2006
*/
function SendBatchExportResults($inv, $errors, $counts)
{
   global $smarty;
   $atmDB = new atmDB();

   $default_acct = $atmDB->GetARMCSetting("DEFAULT_ROLE_ID_17");
   $rcpts[] = $default_acct["armc_setting_value"];

   $smarty->assign("errors", array("count"=>sizeof($errors), "list"=>$errors));
   $smarty->assign("invoices", $inv);
   $smarty->assign("counts", $counts);
   $message = $smarty->fetch("app/atm/armc/email_batch_export.tpl");
   $queue_message_attrs = array(
      "message_type_id"=>ATM_MESSAGE_TYPE_ORACLE_MANUAL_EXPORT,
      "rcpt"=>$rcpts,
      "msg"=>array(
         "subject"=>"Oracle Batch Export Results",
         "body"=>$message),
      "attr"=>array(
         "total"=>$counts["inv"]["total"]+$counts["cm"]["total"],
         "success"=>$counts["inv"]["success"]+$counts["cm"]["success"],
         "failures"=>$counts["inv"]["fail"]+$counts["cm"]["fail"],
         "errors"=>sizeof($errors)));

//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");
   $received_by = HBRPCCall('com', 'QueueMessage', $queue_message_attrs);
   //echo ($message);
   //print_r($received_by);
}

/**
* SaveApproval()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Feb 01 11:43:59 PST 2006
*/

//OBSOLETE, MOVED TO THE armc CLASS
function SaveApproval($o)
{
   global $encryption;
   $atmDB = new atmDB();
   switch ($o['approve_section']) {
   case 'header' : {
      //print_r($o);
      switch ($o['header_approve_by']) {
      case 'AM' : {
         if ($o['header_approve']) {
         	$error = "";
         	$ae_rst = $atmDB->GetARMCUsers($o["armc_id"], array(ROLE_PRIMARY_ACCT_EXEC));
         	if ($armc_ae = mysql_fetch_assoc($ae_rst)) {
         	   $comDB = new commonDB();
         	   $aes = array();
         	   $aes_rst = $comDB->GetUsersByRoleId(ROLE_ACCOUNT_EXECUTIVE);
         	   while ($ae = mysql_fetch_assoc($aes_rst)) {
         	      $aes[] = $ae["login"];
         	   }
         	   //echo ("This BR AE : ".$armc_ae["login"].", AEs : ".print_r($aes, true));

         	   if (!in_array($armc_ae["login"], $aes))
         	     $error="&error=The Primary Account Executive (".$armc_ae["user_name"].") is not an Account Executive. Please make sure the Primary Account Executive at the account level is an Account Executive, and refresh both the Study and the Billing Report to reflect the correct Primary Account Execuvite.";
         	}else {
         		$error = "The Primary Account Executive is required !!!";
         	}
         	$billing_contact_rst = $atmDB->GetARMCContacts($o["armc_id"], array(ARMC_CONTACT_TYPE_BILLING));
         	if ($billing_contact = mysql_fetch_assoc($billing_contact_rst)) {
         		if ($billing_contact["address_1"]=="" || $billing_contact["city"]=="" || $billing_contact["country_code"]=="" || $billing_contact["email"]=="") {
         			$error = "&error=Address 1, City, Country and email are required fields for the Billing Contact.";
         		}
         	}else{
         		$error = "&error=Billing Contact required.";
         	}

         	$sat_survey = $atmDB->GetARMCAttr($o["armc_id"], "STM_SEND_SAT_SURVEY");
         	if ($sat_survey) {
         		$project_contact_rst = $atmDB->GetARMCContacts($o["armc_id"], array(ARMC_CONTACT_TYPE_PROJECT));
         		if ($project_contact = mysql_fetch_assoc($project_contact_rst)) {
         			if ($project_contact["last_name"]=="" || $project_contact["email"]=="") {
      	   			$error = "&error=Name and email are required fields for the Project Contact.";
   	      		}
	         	}else{
         			$error = "&error=Project Contact required for satisfaction survey.";
         		}
         	}

         	if ($error=="") {
               $atmDB->WriteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_AM_APPROVED_HEADER);
               $atmDB->WriteARMCEvent($o['armc_id'], ARMC_EVENT_TYPE_AM_APPROVED_HEADER);
               if (ARMCIsApprovedByAM($o['armc_id']))
                  if ($atmDB->ARMCRequiresAEApproval($o['armc_id'])) {
                     $atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_AE_APPROVAL);
                     $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_AE_APPROVAL);
                     SendApprovalAlert($o["armc_id"], ATM_MESSAGE_TYPE_AE_APPROVAL);
                  }else{
                     $atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_ACCT_APPROVAL);
                     $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_ACCT_APPROVAL);
                     SendApprovalAlert($o["armc_id"], ATM_MESSAGE_TYPE_ACCT_APPROVAL);
                  }
         	}
         }else{
            $atmDB->DeleteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_AM_APPROVED_HEADER);
            $atmDB->WriteARMCEvent($o['armc_id'], ARMC_EVENT_TYPE_ACCT_DENIED_HEADER, $o["header_comment"]);
            $atmDB->WriteARMCComment($o["armc_id"], ARMC_COMMENT_TYPE_AM_DENIED_HEADER, $o["header_comment"]);
            $atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_EDIT);
            $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_DATA_ENTRY);
         }
      }break;
      case 'AE' : {
         if ($o['header_approve']) {
            $atmDB->WriteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_AE_APPROVED_HEADER);
            $atmDB->WriteARMCEvent($o['armc_id'], ARMC_EVENT_TYPE_AE_APPROVED_HEADER);
            if (ARMCIsApprovedByAE($o['armc_id'])) {
               $atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_ACCT_APPROVAL);
               $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_ACCT_APPROVAL);
               SendApprovalAlert($o["armc_id"], ATM_MESSAGE_TYPE_ACCT_APPROVAL);
            }
         }else{
            $atmDB->DeleteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_AE_APPROVED_HEADER);
            $atmDB->DeleteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_AM_APPROVED_HEADER);
            $atmDB->WriteARMCEvent($o['armc_id'], ARMC_EVENT_TYPE_AE_DENIED_HEADER, $o["header_comment"]);
            $atmDB->WriteARMCComment($o["armc_id"], ARMC_COMMENT_TYPE_AE_DENIED_HEADER, $o["header_comment"]);
            $atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_AM_APPROVAL);
            $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_AM_APPROVAL);
            SendApprovalAlert($o["armc_id"], ATM_MESSAGE_TYPE_AM_APPROVAL);
         }
      }break;
      case 'ACCT' : {
         if ($o['header_approve']) {
         	$error = "";
         	$ae_rst = $atmDB->GetARMCUsers($o["armc_id"], array(ROLE_PRIMARY_ACCT_EXEC));
         	if ($armc_ae = mysql_fetch_assoc($ae_rst)) {
         	   $comDB = new commonDB();
         	   $aes = array();
         	   $aes_rst = $comDB->GetUsersByRoleId(ROLE_ACCOUNT_EXECUTIVE);
         	   while ($ae = mysql_fetch_assoc($aes_rst)) {
         	      $aes[] = $ae["login"];
         	   }
         	   if (!in_array($armc_ae["login"], $aes))
         	     $error="&error=The Primary Account Executive (".$armc_ae["user_name"].") is not an Account Executive. Please make sure the Primary Account Executive at the account level is an Account Executive, and refresh both the Study and the Billing Report to reflect the correct Primary Account Execuvite.";
         	}else {
         		$error = "The Primary Account Executive is required !!!";
         	}

         	$billing_contact_rst = $atmDB->GetARMCContacts($o["armc_id"], array(ARMC_CONTACT_TYPE_BILLING));
         	if ($billing_contact = mysql_fetch_assoc($billing_contact_rst)) {
         		if ($billing_contact["address_1"]=="" || $billing_contact["city"]=="" || $billing_contact["country_code"]=="" || $billing_contact["email"]=="") {
         			$error = "&error=Address 1, City, Country and email are required fields for the Billing Contact.";
         		}
         	}else{
         		$error = "&error=Billing Contact required.";
         	}

         	$sat_survey = $atmDB->GetARMCAttr($o["armc_id"], "STM_SEND_SAT_SURVEY");
         	if ($sat_survey) {
         		$project_contact_rst = $atmDB->GetARMCContacts($o["armc_id"], array(ARMC_CONTACT_TYPE_PROJECT));
         		if ($project_contact = mysql_fetch_assoc($project_contact_rst)) {
         			if ($project_contact["last_name"]=="" || $project_contact["email"]=="") {
      	   			$error = "&error=Name and email are required fields for the Project Contact.";
   	      		}
	         	}else{
         			$error = "&error=Project Contact required for satisfaction survey.";
         		}
         	}

         	if ($error=="") {
	            $atmDB->WriteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_ACCT_APPROVED_HEADER);
    	         $atmDB->WriteARMCEvent($o['armc_id'], ARMC_EVENT_TYPE_ACCT_APPROVED_HEADER);
        	      if (ARMCIsApprovedByACCT($o['armc_id'])) {
            	   $atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_READY_FOR_INVOICE);
                  $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_READY);
        	      }
         	}
         }else{
            $atmDB->DeleteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_ACCT_APPROVED_HEADER);
            $atmDB->WriteARMCEvent($o['armc_id'], ARMC_EVENT_TYPE_ACCT_DENIED_HEADER, $o["header_comment"]);
            $atmDB->WriteARMCComment($o["armc_id"], ARMC_COMMENT_TYPE_ACCT_DENIED_HEADER, $o["header_comment"]);
            if ($atmDB->ARMCRequiresAEApproval($o['armc_id'])) {
               $atmDB->DeleteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_AE_APPROVED_HEADER);
               $atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_AE_APPROVAL);
               $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_AE_APPROVAL);
               SendApprovalAlert($o["armc_id"], ATM_MESSAGE_TYPE_AE_APPROVAL);
            }else{
               $atmDB->DeleteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_AM_APPROVED_HEADER);
               $atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_AM_APPROVAL);
               $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_AM_APPROVAL);
               SendApprovalAlert($o["armc_id"], ATM_MESSAGE_TYPE_AM_APPROVAL);
            }
         }
      }break;
      }
   }break;

   case 'invoice' : {
      switch ($o['invoice_approve_by']) {
      case 'AM' : {
         if ($o['invoice_approve']) {
			   $error = "";
         	$po_required = $atmDB->GetARMCAttr($o["armc_id"], "ARMC_PO_REQUIRED");
         	if ($po_required=="" || $po_required==null) $po_required = false;
       		$po = $atmDB->GetARMCAttr($o["armc_id"], "PONUMBER");
       		if ($po=="" || $po==null) $po = false;
         	if ($po_required && !$po) {
         		$error = "&error=P.O. number is required";
         	}

         	$job_required = $atmDB->GetARMCAttr($o["armc_id"], "ARMC_JOB_REQUIRED");
         	if ($job_required=="" || $job_required==null) $job_required = false;
         	$job = $atmDB->GetARMCAttr($o["armc_id"], "JOBNUMBER");
         	if ($job=="" || $job==null) $job = false;
         	if ($job_required && !$job) {
         		$error = "&error=Job number is required";
         	}

         	$pm_required = $atmDB->GetARMCAttr($o["armc_id"], "ARMC_PM_REQUIRED");
         	if ($pm_required=="" || $pm_required==null) $pm_required = false;
         	$pm = $atmDB->GetARMCAttr($o["armc_id"], "PMNAME");
         	if ($pm=="" || $pm==null) $pm = false;
         	if ($pm_required && !$pm) {
         		$error = "&error=Project Manager is required";
         	}

         	if ($error == "") {
               $atmDB->WriteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_AM_APPROVED_INVOICE);
               $atmDB->WriteARMCEvent($o['armc_id'], ARMC_EVENT_TYPE_AM_APPROVED_INVOICE);
               if (ARMCIsApprovedByAM($o['armc_id']))
                  if ($atmDB->ARMCRequiresAEApproval($o['armc_id'])) {
                     $atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_AE_APPROVAL);
                     $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_AE_APPROVAL);
                     SendApprovalAlert($o["armc_id"], ATM_MESSAGE_TYPE_AE_APPROVAL);
                  }else{
                     $atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_ACCT_APPROVAL);
                     $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_ACCT_APPROVAL);
                     SendApprovalAlert($o["armc_id"], ATM_MESSAGE_TYPE_ACCT_APPROVAL);
                  }
         	}
         }else{
            $atmDB->DeleteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_AM_APPROVED_INVOICE);
            $atmDB->WriteARMCEvent($o['armc_id'], ARMC_EVENT_TYPE_ACCT_DENIED_INVOICE, $o["invoice_comment"]);
            $atmDB->WriteARMCComment($o["armc_id"], ARMC_COMMENT_TYPE_AM_DENIED_INVOICE, $o["invoice_comment"]);
            $atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_EDIT);
            $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_EDIT);
         }
      }break;
      case 'AE' : {
         if ($o['invoice_approve']) {
            $atmDB->WriteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_AE_APPROVED_INVOICE);
            $atmDB->WriteARMCEvent($o['armc_id'], ARMC_EVENT_TYPE_AE_APPROVED_INVOICE);
            if (ARMCIsApprovedByAE($o['armc_id'])) {
               $atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_ACCT_APPROVAL);
               $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_ACCT_APPROVAL);
               SendApprovalAlert($o["armc_id"], ATM_MESSAGE_TYPE_ACCT_APPROVAL);
            }
         }else{
            $atmDB->DeleteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_AE_APPROVED_INVOICE);
            $atmDB->DeleteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_AM_APPROVED_INVOICE);
            $atmDB->WriteARMCEvent($o['armc_id'], ARMC_EVENT_TYPE_AE_DENIED_INVOICE, $o["invoice_comment"]);
            $atmDB->WriteARMCComment($o['armc_id'], ARMC_COMMENT_TYPE_AE_DENIED_INVOICE, $o["invoice_comment"]);
            $atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_AM_APPROVAL);
            $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_AM_APPROVAL);
            SendApprovalAlert($o["armc_id"], ATM_MESSAGE_TYPE_AM_APPROVAL);
         }
      }break;
      case 'ACCT' : {
         if ($o['invoice_approve']) {
			   $error = "";
         	$po_required = $atmDB->GetARMCAttr($o["armc_id"], "ARMC_PO_REQUIRED");
         	if ($po_required=="" || $po_required==null) $po_required = false;
       		$po = $atmDB->GetARMCAttr($o["armc_id"], "PONUMBER");
       		if ($po=="" || $po==null) $po = false;
         	if ($po_required && !$po) {
         		$error = "&error=P.O. number is required";
         	}

         	$job_required = $atmDB->GetARMCAttr($o["armc_id"], "ARMC_JOB_REQUIRED");
         	if ($job_required=="" || $job_required==null) $job_required = false;
         	$job = $atmDB->GetARMCAttr($o["armc_id"], "JOBNUMBER");
         	if ($job=="" || $job==null) $job = false;
         	if ($job_required && !$job) {
         		$error = "&error=Job number is required";
         	}

         	$pm_required = $atmDB->GetARMCAttr($o["armc_id"], "ARMC_PM_REQUIRED");
         	if ($pm_required=="" || $pm_required==null) $pm_required = false;
         	$pm = $atmDB->GetARMCAttr($o["armc_id"], "PMNAME");
         	if ($pm=="" || $pm==null) $pm = false;
         	if ($pm_required && !$pm) {
         		$error = "&error=Project Manager is required";
         	}

         	if ($error == "") {
            	$atmDB->WriteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_ACCT_APPROVED_INVOICE);
            	$atmDB->WriteARMCEvent($o['armc_id'], ARMC_EVENT_TYPE_ACCT_APPROVED_INVOICE);
            	if (ARMCIsApprovedByACCT($o['armc_id'])) {
            		$atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_READY_FOR_INVOICE);
                  $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_READY);
            	}
         	}
         }else{
            $atmDB->DeleteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_ACCT_APPROVED_INVOICE);
            $atmDB->WriteARMCEvent($o['armc_id'], ARMC_EVENT_TYPE_ACCT_DENIED_INVOICE, $o["invoice_comment"]);
            $atmDB->WriteARMCComment($o["armc_id"], ARMC_COMMENT_TYPE_ACCT_DENIED_INVOICE, $o["invoice_comment"]);
            if ($atmDB->ARMCRequiresAEApproval($o['armc_id'])) {
               $atmDB->DeleteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_AE_APPROVED_INVOICE);
               $atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_AE_APPROVAL);
               $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_AE_APPROVAL);
               SendApprovalAlert($o["armc_id"], ATM_MESSAGE_TYPE_AE_APPROVAL);
            }else{
               $atmDB->DeleteARMCAction($o['armc_id'], ARMC_ACTION_TYPE_AM_APPROVED_INVOICE);
               $atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_AM_APPROVAL);
               $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_AM_APPROVAL);
               SendApprovalAlert($o["armc_id"], ATM_MESSAGE_TYPE_AM_APPROVAL);
            }
         }
      }break;
      }
   }break;
   }

   header("Location: ?e=".$encryption->Encrypt("action=display_armc_details&armc_id=".$o['armc_id']."&dont_log_view=1".$error));
   exit;
}

/**
* SaveInvoice()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 03 15:56:12 PST 2006
*/

//OBSOLETE, MOVED TO THE armc CLASS
function SaveInvoice($o)
{
   global $encryption;
   $atmDB = new atmDB();

   //print_r($o);

   $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_UPDATED_INVOICE_DETAILS, "Date:".$o["invoice_date"].", PO:".$o["po_number"].", Job:".$o["job_number"].", PM:".$o["pm_name"].", Memo:".$o["invoice_memo"]);
   if ($o["invoice_date"]=="")
      $o["invoice_date"]="NULL";
   else
      $o["invoice_date"] = "'".$o["invoice_date"]."'";

   //echo ("Invoice date : ".$o["invoice_date"]);
   $atmDB->SetARMCTransactionDate($o["armc_id"], $o["invoice_date"]);
   SetARMCAttr($o["armc_id"], "PONUMBER", $o["po_number"]);
   SetARMCAttr($o["armc_id"], "JOBNUMBER", $o["job_number"]);
   SetARMCAttr($o["armc_id"], "PMNAME", $o["pm_name"]);
   SetARMCAttr($o["armc_id"], "INVMEMO", $o["invoice_memo"]);
   //$atmDB->SetARMCComment($o["armc_id"], ARMC_COMMENT_TYPE_INVOICE_MEMO, $o["invoice_memo"]);

   if ($o["i_invoice_approve"] == 1) {
      $o["invoice_approve"] = 1;
      $o["invoice_approve_by"] = $o["i_invoice_approve_by"];
      $o["approve_section"] = "invoice";
      $f = new armc();
      $f->SetParms($o);
      $f->SaveApproval();
      //SaveApproval($o);
   } else {
      header("Location: ?e=".$encryption->Encrypt("action=display_armc_details&armc_id=".$o["armc_id"]."&dont_log_view=1"));
   }
}

/**
* SaveGroupInvoice()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 03 15:56:12 PST 2006
*/
function SaveGroupInvoice($o)
{
   global $encryption;
   $atmDB = new atmDB();

   if ($o["invoice_date"]=="")
      $invoice_date="NULL";
   else
      $invoice_date = "'".$o["invoice_date"]."'";

   SetARMCGroupAttr($o["armc_group_id"], "PONUMBER", $o["po_number"]);
   SetARMCGroupAttr($o["armc_group_id"], "JOBNUMBER", $o["job_number"]);
   SetARMCGroupAttr($o["armc_group_id"], "PMNAME", $o["pm_name"]);
   SetARMCGroupAttr($o["armc_group_id"], "INVMEMO", $o["invoice_memo"]);

   //echo ("<pre>".print_r($o, true)."</pre>");

   $lines_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id='".$o["armc_group_id"]."'", "ORDER BY a.armc_id");
   while ($line = mysql_fetch_assoc($lines_rst)) {
      //echo ("Armc ID : ".$line["Armc_id"]."<br>");
      $atmDB->WriteARMCEvent($line["armc_id"], ARMC_EVENT_TYPE_UPDATED_INVOICE_DETAILS, "Date:".$o["invoice_date"].", PO:".$o["po_number"].", Job:".$o["job_number"].", PM:".$o["pm_name"].", Memo:".$o["invoice_memo"]);
      $atmDB->SetARMCTransactionDate($line["armc_id"], $invoice_date);
      SetARMCAttr($line["armc_id"], "PONUMBER", $o["po_number"]);
      SetARMCAttr($line["armc_id"], "JOBNUMBER", $o["job_number"]);
      SetARMCAttr($line["armc_id"], "PMNAME", $o["pm_name"]);
      SetARMCAttr($line["armc_id"], "INVMEMO", $o["invoice_memo"]);
      //$atmDB->SetARMCComment($line["armc_id"], ARMC_COMMENT_TYPE_INVOICE_MEMO, $o["invoice_memo"]);
   }

   header("Location: ?e=".$encryption->Encrypt("action=display_group_details&armc_group_id=".$o["armc_group_id"]));
}

/**
* SaveLines()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 02 11:36:03 PST 2006
*/
function SaveLines($o)
{
   global $encryption;
//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");
   $atmDB = new atmDB();
   $armc = new armc();
   
   if ($o["get_approval"])
      $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_SAVED_FOR_APPROVAL);
   else
      $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_SAVED_FOR_LATER);

   $total = 0;
   $i = 0;
   $type_id = $atmDB->GetARMCType($o["armc_id"]);
   $type_id = $type_id["armc_type_id"];
   $new_status = -1;
   while (isset($o["armc_budget_line_item_id_$i"])) {
      $o["proposed_quantity_$i"] = str_replace(",", "", $o["proposed_quantity_$i"]);
      $o["proposed_rate_$i"] = str_replace(",", "", $o["proposed_rate_$i"]);
      $o["actual_quantity_$i"] = str_replace(",", "", $o["actual_quantity_$i"]);
      $o["actual_rate_$i"] = str_replace(",", "", $o["actual_rate_$i"]);
      if ($o["armc_budget_line_item_def_id_$i"]==-11) {
         $atmDB->DeleteARMCLine($o["armc_id"], $o["armc_budget_line_item_id_$i"]);
         $atmDB->WriteARMCLineComment($o["armc_budget_line_item_id_$i"], ARMC_COMMENT_TYPE_DELETE_LINE, $o["delete_comment_$i"]);
      }else{
         if ($o["currency_code"]=="USD"){
            $proposed_rate = round($o["proposed_rate_$i"], 3);
            $proposed_rate_i18n = round($proposed_rate/$o["exchange_rate"], 3);
            $actual_rate = round($o["actual_rate_$i"], 3);
            $actual_rate_i18n = round($actual_rate/$o["exchange_rate"], 3);
         }else{
            $proposed_rate_i18n = round($o["proposed_rate_$i"], 3);
            $proposed_rate = round($proposed_rate_i18n*$o["exchange_rate"], 3);
            $actual_rate_i18n = round($o["actual_rate_$i"], 3);
            $actual_rate = round($actual_rate_i18n*$o["exchange_rate"], 3);
         }
         $line = array(
            "armc_id"=>$o["armc_id"],
            "armc_budget_line_item_id"=>$o["armc_budget_line_item_id_$i"],
            "armc_budget_line_item_def_id"=>$o["armc_budget_line_item_def_id_$i"],
            "armc_budget_line_item_description"=>$o["armc_budget_line_item_description_$i"],
            "group_description"=>$o["group_description_$i"],
            "unit_type"=>$o["unit_type_$i"],
            "proposed_quantity"=>$o["proposed_quantity_$i"],
            "proposed_rate"=>$proposed_rate,
            "proposed_rate_i18n"=>$proposed_rate_i18n,
            "actual_quantity"=>$o["actual_quantity_$i"],
            "actual_rate"=>$actual_rate,
            "actual_rate_i18n"=>$actual_rate_i18n,
            "am_approval"=>$o["am_approval_$i"],
            "ae_approval"=>$o["ae_approval_$i"],
            "acct_approval"=>$o["acct_approval_$i"],
            "edit_comment"=>$o["edit_comment_$i"],
            "deny_comment"=>$o["deny_comment_$i"]);
         //$atmDB->debugPrint(print_r($line, true));
         $atmDB->UpdateARMCLine($line);
         $total += $line["actual_quantity"]*$line["actual_rate"];
         if ($line["edit_comment"]!="") {
            $atmDB->WriteARMCLineComment($line["armc_budget_line_item_id"], ARMC_COMMENT_TYPE_EDIT_LINE, $line["edit_comment"]);
         }
         if (isset($o["am_approval_$i"])) {
            if (!$atmDB->ARMCLineIsApproved($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AM_APPROVED_LINE)) {
               if (!IsCreditMemo($type_id)&&($line["actual_quantity"]==0 || $line["actual_rate"]==0)) {
                  $error_message_url = "&error=Budget line ".$line["armc_budget_line_item_description"]." has amount $0.00. Please correct !!!";
               }else{
                  $atmDB->WriteARMCLineAction($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AM_APPROVED_LINE);
                  $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_AM_APPROVED_LINE, $line["armc_budget_line_item_description"]." - ".$line["group_description"]." (P:".$line["proposed_quantity"]."@".$line["proposed_rate"].", A:".$line["actual_quantity"]."@".$line["actual_rate"].")");
               }
            }
         }else{
            if ($atmDB->ARMCLineIsApproved($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AM_APPROVED_LINE) && ($atmDB->GetARMCStatusID($o["armc_id"])==ARMC_STATUS_AM_APPROVAL)) {
               $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_AM_DENIED_LINE, $line["armc_budget_line_item_description"]." - ".$line["group_description"]." (P:".$line["proposed_quantity"]."@".$line["proposed_rate"].", A:".$line["actual_quantity"]."@".$line["actual_rate"]."), Reason: ".$line["deny_comment"]);
               $atmDB->WriteARMCLineComment($line["armc_budget_line_item_id"], ARMC_COMMENT_TYPE_AM_DENIED_LINE, $line["deny_comment"]);
               $atmDB->DeleteARMCLineAction($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AM_APPROVED_LINE);
               $new_status = ARMC_STATUS_EDIT;
               //$atmDB->SetARMCStatus($o['armc_id'], ARMC_STATUS_EDIT);
            }
         }
         if (isset($o["ae_approval_$i"])) {
            if (!$atmDB->ARMCLineIsApproved($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AE_APPROVED_LINE)) {
               if (!IsCreditMemo($type_id)&&($line["actual_quantity"]==0 || $line["actual_rate"]==0)) {
                  $error_message_url = "&error=Budget line ".$line["armc_budget_line_item_description"]." has amount $0.00. Please correct !!!";
               }else{
                  $atmDB->WriteARMCLineAction($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AE_APPROVED_LINE);
                  $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_AE_APPROVED_LINE, $line["armc_budget_line_item_description"]." - ".$line["group_description"]." (P:".$line["proposed_quantity"]."@".$line["proposed_rate"].", A:".$line["actual_quantity"]."@".$line["actual_rate"].")");
               }
            }
         }else{
            if ($atmDB->ARMCLineIsApproved($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AE_APPROVED_LINE) && ($atmDB->GetARMCStatusID($o["armc_id"])==ARMC_STATUS_AE_APPROVAL)) {
               $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_AE_DENIED_LINE, $line["armc_budget_line_item_description"]." - ".$line["group_description"]." (P:".$line["proposed_quantity"]."@".$line["proposed_rate"].", A:".$line["actual_quantity"]."@".$line["actual_rate"]."), Reason: ".$line["deny_comment"]);
               $atmDB->WriteARMCLineComment($line["armc_budget_line_item_id"], ARMC_COMMENT_TYPE_AE_DENIED_LINE, $line["deny_comment"]);
               $atmDB->DeleteARMCLineAction($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AE_APPROVED_LINE);
               $atmDB->DeleteARMCLineAction($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AM_APPROVED_LINE);
               $new_status = ARMC_STATUS_AM_APPROVAL;
               //$atmDB->SetARMCStatus($o["armc_id"], ARMC_STATUS_AM_APPROVAL);
            }
         }
         if (isset($o["acct_approval_$i"])) {
            if (!$atmDB->ARMCLineIsApproved($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_ACCT_APPROVED_LINE)) {
               if (!IsCreditMemo($type_id) && ($line["actual_quantity"]==0 || $line["actual_rate"]==0)) {
                  $error_message_url = "&error=Budget line ".$line["armc_budget_line_item_description"]." has amount $0.00. Please correct !!!";
               }
               if ($line["armc_budget_line_item_def_id"]==ARMC_BUDGET_LINE_DEF_RETAINER) {
                  if (IsRetainer($type_id)) {
                     if (RequireStudy($type_id)) {
                        $study_id = $atmDB->GetARMCAttr($o["armc_id"], "STUDY_ID");
                        if ($study_id) {
                           $study_retainer = HBRPCCall('pjm', 'SetStudyRetainer', array("study_id"=>$study_id, "retainer_amount"=>$line["actual_quantity"]*$line["actual_rate"]));
                           if ($study_retainer["error_code"]!=0) {
                              $error_message_url = "&error=".$study_retainer["error_message"].".";
                           }
                        }
                     }else{
                        $account_id = $atmDB->GetARMCAttr($o["armc_id"], "ACCOUNT_ID");
                        if ($account_id) {
                           //echo ("SetAccountRetainer($account_id, ".($line["actual_quantity"]*$line["actual_rate"]).")");
                           $account_retainer = HBRPCCall('acm', 'SetAccountRetainer', array("account_id"=>$account_id, "retainer_amount"=>$line["actual_quantity"]*$line["actual_rate"]));
                           if ($account_retainer["error_code"]!=0) {
                              $error_message_url = "&error=".$account_retainer["error_message"].".";
                           }
                        }
                     }
                  }else{
                     if (RequireStudy($type_id)) {
                        $study_id = $atmDB->GetARMCAttr($o["armc_id"], "STUDY_ID");
                        if ($study_id) {
                           $study_retainer = HBRPCCall('pjm', 'UseStudyRetainer', array("study_id"=>$study_id, "retainer_amount"=>$line["actual_quantity"]*$line["actual_rate"]));
                           if ($study_retainer["error_code"]!=0) {
                              if ($study_retainer["error_code"]==500) {
                                 $account_id = $atmDB->GetARMCAttr($o["armc_id"], "ACCOUNT_ID");
                                 if ($account_id) {
                                    $account_retainer = HBRPCCall('acm', 'UseAccountRetainer', array("account_id"=>$account_id, "retainer_amount"=>$line["actual_quantity"]*$line["actual_rate"]-$study_retainer["error_message"]));
                                    if ($account_retainer["error_code"]!=0) {
                                       if ($account_retainer["error_code"]==500) {
                                          $error_message_url = "&error=Max retainer value $".number_format($account_retainer["error_message"]+$study_retainer["error_message"]).".";
                                       }else{
                                          $error_message_url = "&error=".$account_retainer["error_message"].".";
                                       }
                                    }else{
                                       $study_retainer = HBRPCCall('pjm', 'UseStudyRetainer', array("study_id"=>$study_id, "retainer_amount"=>$study_retainer["error_message"]));
                                    }
                                 }else {
                                    $error_message_url = "&error=Account ID not found !!!";
                                 }
                              }else{
                                 $error_message_url = "&error=".$study_retainer["error_message"].".";
                              }
                           }elseif ($study_id["retainer_balance"]!=0) {
                              $account_id = $atmDB->GetARMCAttr($o["armc_id"], "ACCOUNT_ID");
                              if ($account_id) {
                                 $account_retainer = HBRPCCall('acm', 'SetAccountRetainer', array("account_id"=>$account_id, "retainer_amount"=>$study_retainer["retainer_balance"]));
                                 if ($account_retainer["error_code"]==0) {
                                    HBRPCCall("pjm", "UseStudyRetainer", array("study_id"=>$study_id, "retainer_amount"=>$study_retainer["retainer_balance"]));
                                    $error_message_url = "&error=The remaining study retainer for $".number_format($study_retainer["retainer_balance"], 2)." was transfered to the account !!!";
                                    $do_approval = 1;
                                 }else{
                                    $error_message_url = "&error=".$account_retainer["error_message"];
                                 }
                              }else{
                                 $error_message_url = "&error=Account ID not found !!!";
                              }
                           }
                        }else
                           $error_message_url = "&error=Study ID not found !!!";
                     }else{
                        //$error_message_url = "&error=TEST";
                        $account_id = $atmDB->GetARMCAttr($o["armc_id"], "ACCOUNT_ID");
                        if ($account_id) {
//                           echo ("UseAccountRetainer($account_id, ".($line["actual_quantity"]*$line["actual_rate"]).")");
                           $account_retainer = HBRPCCall('acm', 'UseAccountRetainer', array("account_id"=>$account_id, "retainer_amount"=>$line["actual_quantity"]*$line["actual_rate"]));
                           if ($account_retainer["error_code"]!=0) {
                              if ($account_retainer["error_code"]==500) {
                                 $error_message_url = "&error=Max account retainer value $".number_format($account_retainer["error_message"]).".";
                              }else{
                                 $error_message_url = "&error=".$account_retainer["error_message"].".";
                              }
                           }
                        }
                     }
                  }
               }
               if ($error_message_url=="" || $do_approval) {
                  $atmDB->WriteARMCLineAction($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_ACCT_APPROVED_LINE);
                  $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_ACCT_APPROVED_LINE, $line["armc_budget_line_item_description"]." - ".$line["group_description"]." (P:".$line["proposed_quantity"]."@".$line["proposed_rate"].", A:".$line["actual_quantity"]."@".$line["actual_rate"].")");
               }
            }
         }else{
            if ($atmDB->ARMCLineIsApproved($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_ACCT_APPROVED_LINE) && ($atmDB->GetARMCStatusID($o["armc_id"])==ARMC_STATUS_ACCT_APPROVAL)) {
               $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_ACCT_DENIED_LINE, $line["armc_budget_line_item_description"]." - ".$line["group_description"]." (P:".$line["proposed_quantity"]."@".$line["proposed_rate"].", A:".$line["actual_quantity"]."@".$line["actual_rate"]."), Reason: ".$line["deny_comment"]);
               $atmDB->WriteARMCLineComment($line["armc_budget_line_item_id"], ARMC_COMMENT_TYPE_ACCT_DENIED_LINE, $line["deny_comment"]);
               $atmDB->DeleteARMCLineAction($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_ACCT_APPROVED_LINE);
               if ($atmDB->ARMCRequiresAEApproval($o["armc_id"])) {
                  $atmDB->DeleteARMCLineAction($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AE_APPROVED_LINE);
                  $new_status = ARMC_STATUS_AE_APPROVAL;
                  //$atmDB->SetARMCStatus($o["armc_id"], ARMC_STATUS_AE_APPROVAL);
               }else{
                  $atmDB->DeleteARMCLineAction($line["armc_budget_line_item_id"], ARMC_ACTION_TYPE_AM_APPROVED_LINE);
                  $new_status = ARMC_STATUS_AM_APPROVAL;
                  //$atmDB->SetARMCStatus($o["armc_id"], ARMC_STATUS_AM_APPROVAL);
               }
            }
         }
      }

      //$atmDB->debugPrint("line item id : ".$o["armc_budget_line_item_id_$i"]);
      $i++;
   }

   $new_lines = false;

   $i = 0;
   while (isset($o["new_armc_budget_line_item_def_id_$i"]) && ($o["new_armc_budget_line_item_def_id_$i"] != -1)) {
      if ($o["currency_code"]=="USD"){
         $proposed_rate = round($o["new_proposed_rate_$i"], 3);
         $proposed_rate_i18n = round($proposed_rate/$o["exchange_rate"], 3);
         $actual_rate = round($o["new_actual_rate_$i"], 3);
         $actual_rate_i18n = round($actual_rate/$o["exchange_rate"], 3);
      }else{
         $proposed_rate_i18n = round($o["new_proposed_rate_$i"], 3);
         $proposed_rate = round($proposed_rate_i18n*$o["exchange_rate"], 3);
         $actual_rate_i18n = round($o["new_actual_rate_$i"], 3);
         $actual_rate = round($actual_rate_i18n*$o["exchange_rate"], 3);
      }
      $line = array(
         "armc_id"=>$o["armc_id"],
         "armc_budget_line_item_id"=>$o["new_armc_budget_line_item_id_$i"],
         "armc_budget_line_item_def_id"=>$o["new_armc_budget_line_item_def_id_$i"],
         "group_description"=>$o["new_group_description_$i"],
         "unit_type"=>$o["new_unit_type_$i"],
         "proposed_quantity"=>$o["new_proposed_quantity_$i"],
         "proposed_rate"=>$proposed_rate,
         "proposed_rate_i18n"=>$proposed_rate_i18n,
         "actual_quantity"=>$o["new_actual_quantity_$i"],
         "actual_rate"=>$actual_rate,
         "actual_rate_i18n"=>$actual_rate_i18n);
      //$atmDB->debugPrint(print_r($line, true));
      $atmDB->InsertARMCLine($line);

      $total += $line["actual_quantity"]*$line["actual_rate"];
      $i++;
      $new_lines = true;
   }

   SetARMCAttr($o["armc_id"], "AMOUNT", $total);
   if ($o["armc_group_id"]!=0) {
      //echo ("setting the group amount as well $".$atmDB->GetARMCGroupAmount($o["armc_group_id"])." !!!<br>");
      SetARMCGroupAttr($o["armc_group_id"], "AMOUNT", $atmDB->GetARMCGroupAmount($o["armc_group_id"]));
   }

    //approve the BR line and set the new BR status
	$billing_report = Hb_App_ObjectHelper::GetMapper('Hb_App_Billing_BillingReport')->Find($o['armc_id']);
	$approval 		 = new Hb_App_Billing_Approval($new_lines, $o["get_approval"]);
	$approval->SetBilling($billing_report);
	$approval->RequireApproval();
   Hb_App_ObjectWatcher::commit();
   
	//check whether this billing report is part of the MBR 
   if($billing_report->GetMergedBillingReportID() != 0) {
   
		$config = Hb_Util_Config_SystemConfigReader::Read();
	  
	  	$cfg['base_dir'] = $config->base_dir;
	  
	  	require_once ($_SERVER['DOCUMENT_ROOT'] . '/include/config.inc');
	  	require_once ($cfg['base_dir'] . '/class/dbConnect.php');
	  	require_once ($cfg['base_dir'] . '/class/dbClass/atmDB.class');	
	  	
	  	$atm_db 		= new atmDB();			
		$new_status = $approval->GetBillingReport()->GetBillingReportStatusID();		
		$atm_db->SetARMCStatus($o['armc_id'], $new_status);  	
	   
   }   
   
   if ($new_status != -1 && $o["get_approval"]) {

      switch ($new_status) {
      case ARMC_STATUS_AM_APPROVAL : {
         $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_AM_APPROVAL);
         SendApprovalAlert($o["armc_id"], ATM_MESSAGE_TYPE_AM_APPROVAL);
      }break;
      case ARMC_STATUS_AE_APPROVAL : {
         $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_AE_APPROVAL);
         SendApprovalAlert($o["armc_id"], ATM_MESSAGE_TYPE_AE_APPROVAL);
      }break;
      case ARMC_STATUS_ACCT_APPROVAL : {
         $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_ACCT_APPROVAL);
         SendApprovalAlert($o["armc_id"], ATM_MESSAGE_TYPE_ACCT_APPROVAL);
      }break;
      case ARMC_STATUS_READY_FOR_INVOICE: {
         $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_READY);
      }break;
      }
   }

   header("Location: ?e=".$encryption->Encrypt("action=display_armc_details&armc_id=".$o['armc_id']."&dont_log_view=1".$error_message_url));
   exit;
}

/**
* SaveContact()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 03 11:13:14 PST 2006
*/
//Moved to Contacts Manager
/*
function SaveContact($o)
{
   global $encryption;
   $atmDB = new atmDB();
   switch ($o["contact_type"]) {
   case "billing" : {
      $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_UPDATED_BILLING_CONTACT, "New contact : ".$o["billing_contact_salutation"]." ".$o["billing_contact_first_name"]." ".$o["billing_contact_last_name"]);
      $contact = array(
         "armc_contact_type_id"=>ARMC_CONTACT_TYPE_BILLING,
         "contact_id"=>$o["billing_contact_id"],
         "salutation"=>$o["billing_contact_salutation"],
         "first_name"=>$o["billing_contact_first_name"],
         "last_name"=>$o["billing_contact_last_name"],
         "address_1"=>$o["billing_contact_address1"],
         "address_2"=>$o["billing_contact_address2"],
         "city"=>$o["billing_contact_city"],
         "state"=>$o["billing_contact_state"],
         "zip"=>$o["billing_contact_zip"],
         "country_code"=>$o["billing_contact_country_code"],
         "phone"=>$o["billing_contact_phone"],
         "fax"=>$o["billing_contact_fax"],
         "email"=>$o["billing_contact_email"]);
      SetARMCContact($o["armc_id"], $contact);
   }break;

   case "project" : {
      $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_UPDATED_PROJECT_CONTACT, "New contact : ".$o["project_contact_salutation"]." ".$o["project_contact_first_name"]." ".$o["project_contact_last_name"]);
      SetARMCAttr($o["armc_id"], "PMNAME", $o["project_contact_salutation"]." ".$o["project_contact_first_name"]." ".$o["project_contact_last_name"]);
      $contact = array(
         "armc_contact_type_id"=>ARMC_CONTACT_TYPE_PROJECT,
         "contact_id"=>$o["project_contact_id"],
         "salutation"=>$o["project_contact_salutation"],
         "first_name"=>$o["project_contact_first_name"],
         "last_name"=>$o["project_contact_last_name"],
         "address_1"=>$o["project_contact_address1"],
         "address_2"=>$o["project_contact_address2"],
         "city"=>$o["project_contact_city"],
         "state"=>$o["project_contact_state"],
         "zip"=>$o["project_contact_zip"],
         "country_code"=>$o["project_contact_country_code"],
         "phone"=>$o["project_contact_phone"],
         "fax"=>$o["project_contact_fax"],
         "email"=>$o["project_contact_email"]);
      SetARMCContact($o["armc_id"], $contact);
   }break;

   }

   header("Location: ?e=".$encryption->Encrypt("action=display_armc_details&armc_id=".$o["armc_id"]."&dont_log_view=1"));
   exit;
}
*/

/**
* SaveGroupContact()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 03 11:13:14 PST 2006
*/
//Moved to Contacts Manager
/*
function SaveGroupContact($o)
{
   global $encryption;
   $atmDB = new atmDB();
   $lines_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id='".$o["armc_group_id"]."'", "ORDER BY a.armc_id");
   while ($line=mysql_fetch_assoc($lines_rst)) {
      $atmDB->WriteARMCEvent($line["armc_id"], ARMC_EVENT_TYPE_UPDATED_BILLING_CONTACT, "New contact : ".$o["billing_contact_salutation"]." ".$o["billing_contact_first_name"]." ".$o["billing_contact_last_name"]);
      $contact = array(
         "armc_contact_type_id"=>ARMC_CONTACT_TYPE_BILLING,
         "contact_id"=>$o["billing_contact_id"],
         "salutation"=>$o["billing_contact_salutation"],
         "first_name"=>$o["billing_contact_first_name"],
         "last_name"=>$o["billing_contact_last_name"],
         "address_1"=>$o["billing_contact_address1"],
         "address_2"=>$o["billing_contact_address2"],
         "city"=>$o["billing_contact_city"],
         "state"=>$o["billing_contact_state"],
         "zip"=>$o["billing_contact_zip"],
         "country_code"=>$o["billing_contact_country_code"],
         "phone"=>$o["billing_contact_phone"],
         "fax"=>$o["billing_contact_fax"],
         "email"=>$o["billing_contact_email"]);
      //print_r($contact);
      SetARMCContact($line["armc_id"], $contact);
   }
   header("Location: ?e=".$encryption->Encrypt("action=display_group_details&armc_group_id=".$o["armc_group_id"]));
   exit;
}
*/

/**
* SaveARMCGroupDescription()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 09 11:36:52 PST 2006
*/
function SaveARMCGroupDescription($o)
{
   global $encryption;
   $atmDB = new atmDB();
   $atmDB->SetARMCGroupDescription($o["armc_group_id"], $o["armc_group_description"]);

   header("Location: ?e=".$encryption->Encrypt("action=display_group_details&armc_group_id=".$o["armc_group_id"]));
}

function DoARMCStall($armc_id, $memo, $date="") {
	 $atmDB = new atmDB();
    $atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STALLED, $memo);
    $atmDB->WriteARMCComment($armc_id, ARMC_COMMENT_TYPE_STALL, $memo);
    SetARMCAttr($armc_id, "STALL_CLEAR_DATE", $date);
    $atmDB->SetARMCStatus($armc_id, ARMC_STATUS_STALLED);
}

/**
 * DoChangeARMCStatus()
 *
 * @param 
 * @author - krathnayake
 * @since  - Tue Nov 06 15:28:42 IST 2007
 **/
function DoChangeARMCStatus($armc_id)
{
	$atmDB = new atmDB();
	$atmDB->SetARMCStatus($armc_id, ARMC_STATUS_READY_FOR_INVOICE);
   $atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_STATUS_READY);
} 

function DoARMCReactivate($armc_id) {
	$atmDB = new atmDB();
	$new_status = ARMC_STATUS_AM_APPROVAL;
	$event_type = ARMC_EVENT_TYPE_STATUS_AM_APPROVAL;
	if (ARMCIsApprovedByAM($armc_id)) {
	    if ($atmDB->ARMCRequiresAEApproval($armc_id)) {
	       $new_status = ARMC_STATUS_AE_APPROVAL;
	       $event_type = ARMC_EVENT_TYPE_STATUS_AE_APPROVAL;
	    }else{
	       $new_status = ARMC_STATUS_ACCT_APPROVAL;
	       $event_type = ARMC_EVENT_TYPE_STATUS_ACCT_APPROVAL;
	    }
	}

	if ($new_status == ARMC_STATUS_AE_APPROVAL) {
	   if (ARMCIsApprovedByAE($armc_id)) {
	      $new_status = ARMC_STATUS_ACCT_APPROVAL;
	      $event_type = ARMC_EVENT_TYPE_STATUS_ACCT_APPROVAL;
	   }
	}

	if ($new_status == ARMC_STATUS_ACCT_APPROVAL) {
	   if (ARMCIsApprovedByACCT($armc_id)) {
	      $new_status = ARMC_STATUS_READY_FOR_INVOICE;
	      $event_type = ARMC_EVENT_TYPE_STATUS_READY;
	   }
	}
   $atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_REACTIVATED);
   $atmDB->SetARMCStatus($armc_id, $new_status);
   $atmDB->WriteARMCEvent($armc_id, $event_type);
}

/**
* DoARMCCorrect()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 29 12:29:28 PST 2006
*/
function DoARMCCorrect($armc_id, $memo)
{
   $atmDB = new atmDB();
   $atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_CORRECT, $memo);
   $atmDB->WriteARMCComment($armc_id, ARMC_COMMENT_TYPE_CORRECT, $memo);
   $atmDB->SetARMCStatus($armc_id, ARMC_STATUS_EDIT);
   $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_STATUS_EDIT);
}

function DoARMCDelete($armc_id, $memo) {
	$atmDB = new atmDB();
    $atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_DELETED, $memo);
    $atmDB->WriteARMCComment($armc_id, ARMC_COMMENT_TYPE_DELETE, $memo);
    $atmDB->SetARMCStatus($armc_id, ARMC_STATUS_DELETED);
    $atmDB->DeleteARMC($armc_id);
}

/**
* UpdateSAM()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Mar 09 09:35:08 PST 2006
*/
function UpdateSAM($invoice)
{
   $comDB = new commonDB();
   $region_id = $comDB->GetRegionByCountryCode($invoice["account"]["account_country_code"]);
   if ($region_id) {
      $region_id = $region_id["region_id"];
   }

   if (!isset($invoice["amount"])) {
   $amount = 0;
   foreach ($invoice["lines"] AS $line) {
      $amount+= $line["actual_rate"]*$line["actual_quantity"];
   }
   }else{
      $amount = $invoice["amount"];
   }

   switch ($invoice["type"]["armc_type_id"]) {
      case ARMC_TYPE_BR_STUDY:
      case ARMC_TYPE_RT_STUDY: {
         $sam_type_id = SAM_TYPE_INVOICED_STUDY;
      }break;
      case ARMC_TYPE_CM_STUDY: {
         $sam_type_id = SAM_TYPE_INVOICED_STUDY;
         $amount = 0-$amount;
      }break;
      case ARMC_TYPE_BR_SB:
      case ARMC_TYPE_RT_SB: {
         $sam_type_id = SAM_TYPE_INVOICED_SB;
      }break;
      case ARMC_TYPE_CM_SB: {
         $sam_type_id = SAM_TYPE_INVOICED_SB;
         $amount = 0-$amount;
      }break;
      case ARMC_TYPE_BR_LI:
      case ARMC_TYPE_RT_LI: {
         $sam_type_id = SAM_TYPE_INVOICED_LI;
      }break;
      case ARMC_TYPE_CM_LI: {
         $sam_type_id = SAM_TYPE_INVOICED_LI;
         $amount = 0-$amount;
      }break;
      case ARMC_TYPE_BR_TR:
      case ARMC_TYPE_RT_TR: {
         $sam_type_id = SAM_TYPE_INVOICED_TR;
      }break;
      case ARMC_TYPE_CM_TR: {
         $sam_type_id = SAM_TYPE_INVOICED_TR;
         $amount = 0-$amount;
      }break;
      case ARMC_TYPE_BR_OT:
      case ARMC_TYPE_RT_OT: {
         $sam_type_id = SAM_TYPE_INVOICED_OT;
      }break;
      case ARMC_TYPE_CM_OT: {
         $sam_type_id = SAM_TYPE_INVOICED_OT;
         $amount = 0-$amount;
      }break;
   }

   if (strlen($invoice["invoice"]["transaction_date"])==12) {
      $invoice["invoice"]["transaction_date"] = substr($invoice["invoice"]["transaction_date"], 1, 10);
   }

//   echo ("AccountID : ".$invoice["account"]["account_id"]."<br>");
//   echo ("Region ID: $region_id<br>");
//   echo ("Transction date : ".$invoice["invoice"]["transaction_date"]."<br>");
//   echo ("Amount : $amount<br>");
//   echo ("AE ID : ".$invoice["account"]["primary_ae_id"]."<br>");
//   echo ("Sam type id : $sam_type_id<br>");
//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");
   $add_sam = HBRPCCall('sam', 'AddSAM',
      array(
         "account_id"=>$invoice["account"]["account_id"],
         "region_id"=>$region_id,
         "year"=>substr($invoice["invoice"]["transaction_date"], 0, 4),
         "month"=>substr($invoice["invoice"]["transaction_date"], 5, 2),
         "amount"=>$amount,
         "user_id"=>$invoice["account"]["primary_ae_id"],
         "sam_type_id"=>$sam_type_id));

//   print_r($add_sam);
}

/**
* SetInvoiceAttributes
*
* @param
* @return
* @throws
* @version 2.0.2
* @since Mon Jul 30 18:57:22 PDT 2007
*/
function SetInvoiceAttributes($invoice_id, $attributes)
{
	if (!is_array($attributes)) 
	{ 
      return; 
	}
	
   $oraDB = new oracleDB();
   
   foreach ($attributes AS $attribute=>$values) {
      $seq = 1;
      foreach ($values AS $value) {
         $oraDB->InsertTrxAttr($invoice_id, $seq++, $attribute, $value);
      }
   }
}

/**
* UpdateARMC()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 20 15:21:11 PST 2006
*/
function UpdateARMC($invoice)
{
   $atmDB = new atmDB();

   $atmDB->WriteARMCEvent($invoice["armc_id"], ARMC_EVENT_TYPE_INVOICED, "Invoice number: ".$invoice["invoice"]["transaction_number"]);
   $atmDB->SetARMCStatus($invoice["armc_id"], ARMC_STATUS_INVOICED);
   $atmDB->SetARMCTransactionDate($invoice["armc_id"], "'".$invoice["invoice"]["transaction_date"]."'");
   $atmDB->SetARMCDate($invoice["armc_id"], $invoice["invoice"]["transaction_date"]);
   $atmDB->SetARMCTransactionNumber($invoice["armc_id"], $invoice["invoice"]["transaction_number"]);
//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");

	$result = $atmDB->GetARMCAttr($invoice["armc_id"], "CLOSE_STUDY_WHEN_INVOICED");
	HBRPCCall('pjm', 'SetStudyInvoiceDate', array("study_id"=>$invoice["study"]["study_id"], "armc_id"=>$invoice["armc_id"], "invoice_date"=>$invoice["invoice"]["transaction_date"], "close_study_when_invoiced" => $result));
	
	SetARMCAttr($invoice["armc_id"], "TXNID", $invoice["invoice"]["customer_trx_id"]);
	
	SetInvoiceAttributes($invoice["invoice"]["customer_trx_id"], $invoice["attributes"]);
   //UpdateSAM($invoice);
   //print_r($invoice);
//   echo ("Add SAM RPC call result");
//   print_r($add_sam);
}

function UpdateARMCFailedExport($armc_id, $error_message) {
   $atmDB = new atmDB();

   $atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_INVOICING_FAILED, $error_message);
   $atmDB->SetARMCStatus($armc_id, ARMC_STATUS_ORACLE_EXPORT_FAILED);
}

/**
* UpdateARMCGroup()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 20 15:21:11 PST 2006
*/
function UpdateARMCGroup($invoice)
{
   $atmDB = new atmDB();

   foreach ($invoice["list"] AS $armc_id=>$study_id) {
      $atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_INVOICED, "Invoice number: ".$invoice["invoice"]["transaction_number"]);
      $atmDB->SetARMCStatus($armc_id, ARMC_STATUS_INVOICED);
      $atmDB->SetARMCTransactionDate($armc_id, "'".$invoice["invoice"]["transaction_date"]."'");
      $atmDB->SetARMCDate($armc_id, $invoice["invoice"]["transaction_date"]);
      $atmDB->SetARMCTransactionNumber($armc_id, $invoice["invoice"]["transaction_number"]);
	
      $result = $atmDB->GetARMCAttr($armc_id, "CLOSE_STUDY_WHEN_INVOICED");
      HBRPCCall('pjm', 'SetStudyInvoiceDate', array("study_id"=>$study_id, "armc_id"=>$armc_id, "invoice_date"=>$invoice["invoice"]["transaction_date"], "close_study_when_invoiced" => $result));

      SetARMCAttr($armc_id, "TXNID", $invoice["invoice"]["customer_trx_id"]);
   }
   $atmDB->SetARMCGroupDate($invoice["armc_group_id"], $invoice["invoice"]["transaction_date"]);
   SetARMCGroupAttr($invoice["armc_group_id"], "TXNID", $invoice["invoice"]["customer_trx_id"]);

   SetInvoiceAttributes($invoice["invoice"]["customer_trx_id"], $invoice["attributes"]);
   //UpdateSAM($invoice);
}

function UpdateARMCGroupFailedExport($armc_group_id, $error_message) {
   $atmDB = new atmDB();

   $rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id = '$armc_group_id'");
   while($armc = mysql_fetch_assoc($rst)) {
      $armc_id = $armc["armc_id"];
      $atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_INVOICING_FAILED, $error_message);
      $atmDB->SetARMCStatus($armc_id, ARMC_STATUS_ORACLE_EXPORT_FAILED);
   }
}

/**
* GetInvoiceExportInfo()
*
* @param
* @todo NOT YET COMPLETED
* @return
* @since  - Tue Jun 12 12:43:17 PDT 2007
*/
function GetInvoiceExportInfo($armc_id)
{
   $atmDB = new atmDB();
   $commDB = new commonDB();
   
   try {
   	$oraDB = new oracleDB();
   }
   catch (Hb_Util_Db_Ora_Exception_ConnectException $e)
   {
		$oracle_exception_log = Hb_Util_Log_Logger::GetInstance("oracle_exception");
		$oracle_exception_log->LogException($e);
		$_SESSION['oracle_exception'] = $e->getMessage();
   }
   
   $userDB = new userDB();
   //First things first, the armc_id
   $_invoice = array("armc_id"=>$armc_id);

   //Next, we set the type information
   //array("armc_type_id", "armc_type_prefix", "armc_type_description")
   $header_rst = $atmDB->GetARMCHeader($armc_id);

   $header = mysql_fetch_assoc($header_rst);

   if (!$header)
      return array("error_code"=>100, "error_message"=>"Invalid BR id");

   $_invoice["type"] = array(
      "armc_type_id"=>$header["armc_type_id"],
      "armc_type_prefix"=>$header["armc_type_prefix"],
      "armc_type_description"=>$header["armc_type_description"]);

   //Next, we set the status
   //array("armc_status_id", "armc_status_description")
   $_invoice["status"] = array(
      "armc_status_id"=>$header["armc_status_id"],
      "armc_status_description"=>$header["armc_status_description"]);

   //Next, the study information ("study_id", "study_name")
   $_invoice["study"] = array(
      "study_id"=>$header["study_id"],
      "study_name"=>str_replace("'", "`", $header["study_name"]));

//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");

   $log = Hb_Util_Log_Logger::GetInstance("oracle");
   
   $account = HBRPCCall("acm", "GetAccountDetail", array("account_id"=>$header["account_id"]));

   $log->LogDebug2("Batch Account Ora ID", "ARMCID - " . $armc_id . "  Account ID - " . $header["account_id"] . "  Account Details : " . print_r($account, true));
   
   //$_invoice["account"] = $account;
   if ($account["error_code"]) {
      return $account;
   }

   if (!(isset($account["attr"]))) {
      return array("error_code"=>303, "error_message"=>"Account does not have attributes set for ".$account["account_name"]);
   }
   
   $acct = array("account_id"=>$account["account_id"], "account_name"=>$account["account_name"], "country_code"=>$account["country_code"]);
   foreach ($account["attr"] as $attr) {
   	if ($attr["account_attr_name"] == "ARMC_ORA_ACCOUNT_ID") $acct["oracle_account_id"] = $attr["account_attr_value"];
   }

   if (!(isset($acct["oracle_account_id"]) && ($acct["oracle_account_id"]!=""))) {
      return array("error_code"=>300, "error_message"=>"Oracle Account ID not set for ".$account["account_name"]);
   }

   $i18n_currency_code = $atmDB->GetARMCAttr($armc_id, "I18N_CURRENCY_CODE");
   if ($i18n_currency_code){
      $i18n_exchange_rate = $atmDB->GetARMCAttr($armc_id, "I18N_EXCHANGE_RATE");
   }else{
      $i18n_currency_code = "USD";
      $i18n_exchange_rate = 1;
   }
   $acct["currency"] = $i18n_currency_code;
   $acct["exchange_rate"] = $i18n_exchange_rate;
   $product = $commDB->GetProduct($header["product_id"]);
   $acct["transaction_type_id"] = $commDB->GetVendorAttr($product["vendor_id"], "ORATRANSID");

   //Figure out the salesrepID
   //Else use the PRIMARY ACCCOUNT EXECUTIVE ARMC user role.
   $salesrep_rst = $atmDB->GetARMCUsers($armc_id, array(ROLE_PRIMARY_ACCT_EXEC));
   $salesrep_id = mysql_fetch_assoc($salesrep_rst);
   if (!$salesrep_id)
      return array("error_code"=>302, "error_message"=>"Primary Account Executive not set !!!");

   $salesrep_name = $salesrep_id["user_name"];
   $salesrep_login = $salesrep_id["login"];
   $salesrep_id = $salesrep_id["login"];
   $salesrep_details = $commDB->GetUserDetails($salesrep_id);
   $salesrep_id = $commDB->GetUserAttr($salesrep_id, "ORA_ID");
   if (!$salesrep_id) {
      return array("error_code"=>301, "error_message"=>"$salesrep_name is not setup as a Salesrep in Oracle !!!");
   }

   $acct["salesrep_id"] = $salesrep_id;
   $_invoice["account"] = $acct;
//   $_invoice["account"]["primary_ae_id"] = $salesrep_details["login"];

   $timezone = $atmDB->GetARMCSetting("ORA_TIMEZONE_OFFSET");
   $timezone = $timezone["armc_setting_value"];

   $accounting_period = GetAccountingPeriod();
   $invoice_rst = $atmDB->GetARMCInvoice($armc_id);
   $inv = mysql_fetch_assoc($invoice_rst);
   if ($inv["transaction_date"]=="" || $inv["transaction_date"]=="0000-00-00 00:00:00") {
		if (date("Y-m-d")>=$accounting_period."-01" && date("Y-m-d")<=$accounting_period."-".GetLastDateOfMonth(mktime(0, 0, 0, substr($accounting_period, 5), 10, substr($accounting_period, 0, 4)))) {
	      $inv["transaction_date"] = date("Y-m-d", time()+$timezone*60*60);
		}else{
			$inv["transaction_date"] = $accounting_period."-".GetLastDateOfMonth(mktime(0, 0, 0, substr($accounting_period, 5), 10, substr($accounting_period, 0, 4)));
		}
   }
   $inv["job_name"] = str_replace("'", "`", $inv["job_name"]);
   $inv["po_number"] = str_replace("'", "`", $inv["po_number"]);
   $inv["pm_name"] = str_replace("'", "`", $inv["pm_name"]);
   $inv["invoice_memo"] = str_replace("'", "`", $inv["invoice_memo"]);

   $_invoice["invoice"] = $inv;

   if (!$header["billing_contact_id"]) {
      return array("error_code"=>200, "error_message"=>"Billing Contact not set");
   }

   if ($header["billing_contact_address1"]=="" || $header["billing_contact_city"]=="" || $header["billing_country_code"]=="" || $header["billing_contact_email"]=="") {
      return array("error_code"=>201, "error_message"=>"Billing Contact Address 1, City, Country and email are required !!!");
   }
   
   $attributes = array();
   $email_invoices = $atmDB->GetARMCAttr($armc_id, "ARMC_EMAIL_INVOICE");
   $print_invoices = $atmDB->GetARMCAttr($armc_id, "ARMC_PRINT_INVOICE");
   $fax_invoices = $atmDB->GetARMCAttr($armc_id, "ARMC_FAX_INVOICE");
   
   if (!$email_invoices && !$print_invoices) {
      $email_invoices = true;
   }
   
   $proposal_number = $atmDB->GetARMCAttr($armc_id, "PROPOSAL_ID");
   if (!$proposal_number) {
      $proposal_number = 0;
   }
   $_invoice["proposal_number"] = $proposal_number;
   
   $log->LogDebug2("Batch Account Info", "Account : ".$header["account_id"] . " BR - " . $armc_id . "Contact ID" . $header["billing_contact_id"]);
   
   $contact_details = HBRPCCall('acm', 'GetAccountContactDetails', array("account_id"=>$header["account_id"], "contact_id"=>$header["billing_contact_id"]));
   if ($contact_details["error_code"]) {
      $log->LogDebug2("Batch Account Ora ID Error", "ARMCID - " . $armc_id . "Contact Details : " . print_r($contact_details, true));
   	return $contact_details;
   }
   
   $log->LogDebug2("Batch Account Ora ID", "ARMCID - " . $armc_id . "Contact Details : " . print_r($contact_details, true));
   
   if ($contact_details["contact_id"] != $header["billing_contact_id"]) {
      return array("error_code" => 202, "error_message" => "The Billing Contact on the Billing Report is no longer a Contact on the Account. Please select another Billing Contact.");
   }
   
   $contact_details["ora_contact_id"] = $commDB->GetContactAttr($contact_details["contact_id"], "ORA_CONTACT_ID");
   $contact_details["ora_site_id"] = $commDB->GetContactAttr($contact_details["contact_id"], "ORA_SITE_ID");
   $contact_details["email"] = $contact_details["contact_email"];

   if (!($contact_details["ora_contact_id"] && $contact_details["ora_site_id"])) {
      return array("error_code"=>203, "error_message"=>"Oracle Site ID or Oracle Contact ID not set for ".$contact_details["contact_first_name"]." ".$contact_details["contact_last_name"].". Try updating the Oracle Customer Account !");
   }

//   $emails[$contact_details["contact_email"]] = $contact_details["contact_email"];
   if ($email_invoices) {
      $attributes["CLIENT_EMAIL_ADDRESS"][] = $contact_details["contact_email"];
   }

   $config = Hb_Util_Config_SystemConfigReader::Read();
//   $emails["br+".$armc_id."@".$config->server_name] = "br+".$armc_id."@".$config->server_name;
   $attributes["HB_EMAIL_ALIAS"][] = "br+".$armc_id."@".$config->server_name;

   if ($email_invoices) {
      $contact_manager = new db_atm_armc_ContactsManager();
      $additional_contacts = $contact_manager->GetARMCContacts($armc_id, array(ARMC_CONTACT_TYPE_ADDITIONAL_BILLING));
//       $_invoice["additional"] = $additional_contacts;

      foreach ($additional_contacts AS $contact) {
         //$emails[$contact["email"]] = $contact["email"];
         $attributes["CLIENT_EMAIL_ADDRESS"][] = $contact["email"];
      }
   }
   //$contact_details["email"] = implode(", ", $emails);
   
   $_invoice["contact"] = $contact_details;

   $lines_rst = $atmDB->GetARMCLines($armc_id);
   while ($line = mysql_fetch_assoc($lines_rst)) {
      $def_attr = $atmDB->GetARMCBudgetLineItemDefAttr($line["armc_budget_line_item_def_id"], "ORA_INVENTORY_ITEM_ID");
      $line["inventory_item_id"] = $def_attr["armc_budget_line_item_def_attr_value"];

      $def_attr = $atmDB->GetARMCBudgetLineItemDefAttr($line["armc_budget_line_item_def_id"], "ORA_ACCOUNTING_RULE_ID");
      if ($def_attr) {
         $line["accounting_rule_id"] = $def_attr["armc_budget_line_item_def_attr_value"];
      } else {
         $line["accounting_rule_id"] = 1;
      }

      $def_attr = $atmDB->GetARMCBudgetLineItemDefAttr($line["armc_budget_line_item_def_id"], "ORA_INVOICING_RULE_ID");
      if ($def_attr) {
         $line["invoicing_rule_id"] = $def_attr["armc_budget_line_item_def_attr_value"];
      } else {
         $line["invoicing_rule_id"] = -2;
      }


      $def_attr = $atmDB->GetARMCBudgetLineItemDefAttr($line["armc_budget_line_item_def_id"], "ORA_ACCOUNTING_RULE_FREQUENCY");
      $line["accounting_rule_frequency"] = $def_attr["armc_budget_line_item_def_attr_value"];

      $def_attr = $atmDB->GetARMCBudgetLineItemDefAttr($line["armc_budget_line_item_def_id"], "ORA_ACCOUNTING_RULE_OCCURENCES");
      $line["accounting_rule_ocurrences"] = $def_attr["armc_budget_line_item_def_attr_value"];

      if (!($line["inventory_item_id"])) {
         return array("error_code"=>400, "error_message"=>"Oracle Inventory not properly defined for line item ".$line["armc_budget_line_item_description"]);
      }

      $cmb = $atmDB->GetATMGLAccount($line["atm_gl_account_id"]);
      if (!$cmb) {
         return array("error_code"=>402, "error_message"=>"GL account distribution not set for item ".$line["armc_budget_line_item_description"]);
      }

   	$combinations[$cmb["atm_gl_account_segment1"]."_000_".$cmb["atm_gl_account_segment3"]."_".$cmb["atm_gl_account_segment4"]."_".$cmb["atm_gl_account_segment5"]."_".$cmb["atm_gl_account_segment6"]."_".$cmb["atm_gl_account_segment7"]] = array('SEGMENT1'=>$cmb["atm_gl_account_segment1"], 'SEGMENT2'=>'000', 'SEGMENT3'=>$cmb["atm_gl_account_segment3"], 'SEGMENT4'=>$line['atm_gl_account_segment4'], 'SEGMENT5'=>$cmb["atm_gl_account_segment5"], 'SEGMENT6'=>$cmb["atm_gl_account_segment6"], 'SEGMENT7'=>$cmb["atm_gl_account_segment7"]);
      $gl_distribution = $oraDB->GetAccountCombinations($combinations);
      if (!isset($gl_distribution[$cmb["atm_gl_account_segment1"]."_000_".$cmb["atm_gl_account_segment3"]."_".$cmb['atm_gl_account_segment4']."_".$cmb['atm_gl_account_segment5']."_".$cmb['atm_gl_account_segment6']."_".$cmb['atm_gl_account_segment7']]))
         return array("error_code"=>401, "error_message"=>"Oracle Distribution Account Combination invalid ".$cmb['atm_gl_account_segment1']."_000_".$cmb['atm_gl_account_segment3']."_".$cmb['atm_gl_account_segment4']."_".$cmb['atm_gl_account_segment5']."_".$cmb['atm_gl_account_segment6']."_".$cmb['atm_gl_account_segment7']);
      $line["code_combination_id"] = $gl_distribution[$cmb['atm_gl_account_segment1']."_000_".$cmb['atm_gl_account_segment3']."_".$cmb['atm_gl_account_segment4']."_".$cmb['atm_gl_account_segment5']."_".$cmb['atm_gl_account_segment6']."_".$cmb['atm_gl_account_segment7']];
      $line["study_id"] = $_invoice["study"]["study_id"];

      if (IsCreditMemo($_invoice["type"]["armc_type_id"])) {
         $line["actual_rate_i18n"] = -$line["actual_rate_i18n"];
      }

      $line["group_description"] = str_replace("'", "`", $line["group_description"]);
   	$_invoice["lines"][] = $line;
   }
   
   $_invoice["attributes"] = $attributes;
   $_invoice["delivery_type"] = array("email"=>$email_invoices, "print"=>$print_invoices, "fax"=>$fax_invoices);

   return array("error_code"=>0, "invoice"=>$_invoice);
}

/**
* GetGroupInvoiceExportInfo()
*
* @param
* @todo NOT YET COMPLETED
* @return
* @since  - Tue Jun 12 21:30:48 PDT 2007
*/
function GetGroupInvoiceExportInfo($armc_group_id)
{
   $atmDB = new atmDB();
   $commDB = new commonDB();
   $oraDB = new oracleDB();
   $userDB = new userDB();
   //First things first, the armc_id
   $_invoice = array("armc_group_id"=>$armc_group_id, "armc_id"=>$armc_group_id);

   //Next, we set the type information
   //array("armc_type_id", "armc_type_prefix", "armc_type_description")
   $header_rst = $atmDB->GetARMCGroupHeader($armc_group_id);
   $header = mysql_fetch_assoc($header_rst);

   if (!$header)
      return array("error_code"=>100, "error_message"=>"Invalid BR id");

   $_invoice["type"] = array(
      "armc_type_id"=>$header["armc_type_id"],
      "armc_type_prefix"=>$header["armc_type_prefix"],
      "armc_type_description"=>$header["armc_type_description"]);

   //Next, we set the status
   //array("armc_status_id", "armc_status_description")
   $_invoice["status"] = array(
      "armc_status_id"=>$header["armc_status_id"],
      "armc_status_description"=>$header["armc_status_description"]);

   //Next, the study information ("study_id", "study_name")
   $_invoice["study"] = array(
      "study_id"=>"MERGED",
      "study_name"=>str_replace("'", "`", $header["armc_group_description"]));

//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");

   $account = HBRPCCall("acm", "GetAccountDetail", array("account_id"=>$header["account_id"]));
   //$_invoice["account"] = $account;
   if ($account["error_code"]) {
      return $account;
   }

   $acct = array("account_id"=>$account["account_id"], "account_name"=>$account["account_name"], "country_code"=>$account["country_code"]);
   foreach ($account["attr"] as $attr) {
   	if ($attr["account_attr_name"] == "ARMC_ORA_ACCOUNT_ID") $acct["oracle_account_id"] = $attr["account_attr_value"];
   }

   if (!(isset($acct["oracle_account_id"]) && ($acct["oracle_account_id"]!=""))) {
      return array("error_code"=>300, "error_message"=>"Oracle Account ID not set for ".$account["account_name"]);
   }

   $acct["currency"] = $atmDB->GetARMCGroupAttr($armc_group_id, "I18N_CURRENCY_CODE");
   $acct["exchange_rate"] = $atmDB->GetARMCGroupAttr($armc_group_id, "I18N_EXCHANGE_RATE");
   $product = $commDB->GetProduct($header["product_id"]);
   $acct["transaction_type_id"] = $commDB->GetVendorAttr($product["vendor_id"], "ORATRANSID");

   $invoices_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id = '$armc_group_id'", "");
   $armc = mysql_fetch_assoc($invoices_rst);
   //Figure out the salesrepID
   //Else use the PRIMARY ACCCOUNT EXECUTIVE ARMC user role.
   $salesrep_rst = $atmDB->GetARMCUsers($armc["armc_id"], array(ROLE_PRIMARY_ACCT_EXEC));
   $salesrep_id = mysql_fetch_assoc($salesrep_rst);
   if (!$salesrep_id)
      return array("error_code"=>302, "error_message"=>"Primary Account Executive not set !!!");

   $salesrep_name = $salesrep_id["user_name"];
   $salesrep_login = $salesrep_id["login"];
   $salesrep_id = $salesrep_id["login"];
   $salesrep_details = $commDB->GetUserDetails($salesrep_id);
   $salesrep_id = $commDB->GetUserAttr($salesrep_id, "ORA_ID");
   if (!$salesrep_id) {
      return array("error_code"=>301, "error_message"=>"$salesrep_name is not setup as a Salesrep in Oracle !!!");
   }

   $acct["salesrep_id"] = $salesrep_id;
   $_invoice["account"] = $acct;

   $timezone = $atmDB->GetARMCSetting("ORA_TIMEZONE_OFFSET");
   $timezone = $timezone["armc_setting_value"];

   $accounting_period = GetAccountingPeriod();
   $invoice_rst = $atmDB->GetARMCGroupInvoice($armc_group_id);
   $inv = mysql_fetch_assoc($invoice_rst);
   if ($inv["transaction_date"]=="" || $inv["transaction_date"]=="0000-00-00 00:00:00") {
		if (date("Y-m-d")>=$accounting_period."-01" && date("Y-m-d")<=$accounting_period."-".GetLastDateOfMonth(mktime(0, 0, 0, substr($accounting_period, 5), 10, substr($accounting_period, 0, 4)))) {
	      $inv["transaction_date"] = date("Y-m-d", time()+$timezone*60*60);
		}else{
			$inv["transaction_date"] = $accounting_period."-".GetLastDateOfMonth(mktime(0, 0, 0, substr($accounting_period, 5), 10, substr($accounting_period, 0, 4)));
		}
   }
   $inv["job_name"] = str_replace("'", "`", $inv["job_name"]);
   $inv["po_number"] = str_replace("'", "`", $inv["po_number"]);
   $inv["pm_name"] = str_replace("'", "`", $inv["pm_name"]);
   $inv["invoice_memo"] = str_replace("'", "`", $inv["invoice_memo"]);

   $_invoice["invoice"] = $inv;

   if (!$header["billing_contact_id"]) {
      return array("error_code"=>200, "error_message"=>"Billing Contact not set");
   }

   if ($header["billing_contact_address1"]=="" || $header["billing_contact_city"]=="" || $header["billing_country_code"]=="" || $header["billing_contact_email"]=="") {
      return array("error_code"=>201, "error_message"=>"Billing Contact Address 1, City, Country and email are required !!!");
   }

   $attributes = array();
   $email_invoices = $atmDB->GetARMCAttr($header["armc_id"], "ARMC_EMAIL_INVOICE");
   $print_invoices = $atmDB->GetARMCAttr($header["armc_id"], "ARMC_PRINT_INVOICE");
   $fax_invoices = $atmDB->GetARMCAttr($header["armc_id"], "ARMC_FAX_INVOICE");
   
   if (!$email_invoices && !$print_invoices) {
      $email_invoices = true;
   }
   
   $log = Hb_Util_Log_Logger::GetInstance("oracle");
   $log->LogDebug2("Batch Account Info Group ", "Account : ".$header["account_id"] . " BR - " . $armc_id . "Contact ID" . $header["billing_contact_id"]);
   
   $contact_details = HBRPCCall('acm', 'GetAccountContactDetails', array("account_id"=>$header["account_id"], "contact_id"=>$header["billing_contact_id"]));
   if ($contact_details["error_code"]) {
      return $contact_details;
   }
   $log->LogDebug2("Batch Account Ora ID Group", "ARMCID - " . $armc_id . "Contact Details : " . print_r($contact_details, true));
   
   if ($contact_details["contact_id"] != $header["billing_contact_id"]) {
      return array("error_code" => 202, "error_message" => "The Billing Contact on the Billing Report is no longer a Contact on the Account. Please select another Billing Contact.");
   }
      
   $contact_details["ora_contact_id"] = $commDB->GetContactAttr($contact_details["contact_id"], "ORA_CONTACT_ID");
   $contact_details["ora_site_id"] = $commDB->GetContactAttr($contact_details["contact_id"], "ORA_SITE_ID");
   if (!($contact_details["ora_contact_id"] && $contact_details["ora_site_id"])) {
      return array("error_code"=>203, "error_message"=>"Oracle Site ID or Oracle Contact ID not set for ".$contact_details["contact_first_name"]." ".$contact_details["contact_last_name"].". Try updating the Oracle Customer Account !");
   }
//   $emails[$contact_details["contact_email"]] = $contact_details["contact_email"];
   $contact_details["email"] = $contact_details["contact_email"];
   if ($email_invoices) {
      $attributes["CLIENT_EMAIL_ADDRESS"][] = $contact_details["contact_email"];
   }

   $config = Hb_Util_Config_SystemConfigReader::Read();
   //$emails["mbr+".$armc_group_id."@".$config->server_name] = "mbr+".$armc_group_id."@".$config->server_name;
   $attributes["HB_EMAIL_ALIAS"][] = "mbr+".$armc_group_id."@".$config->server_name;
   
   $proposals = array();
   $contact_manager = new db_atm_armc_ContactsManager();
   mysql_data_seek($invoices_rst, 0);
   while ($armc = mysql_fetch_assoc($invoices_rst)) {
      $_invoice["list"][$armc["armc_id"]] = $armc["study_id"];
      $proposal_number = $atmDB->GetARMCAttr($armc["armc_id"], "PROPOSAL_ID");
      if ($proposal_number)
         $proposals[$proposal_number] = $proposal_number;
         
      if (sizeof($proposals)) {
         $_invoice["proposal_number"] = implode(",", $proposals);
      } else {
      	$_invoice["proposal_number"] = "0"; 
      }

      if ($email_invoices) {
         $additional_contacts = $contact_manager->GetARMCContacts($armc["armc_id"], array(ARMC_CONTACT_TYPE_ADDITIONAL_BILLING));
         //   $_invoice["additional"] = $additional_contacts;

         foreach ($additional_contacts AS $contact) {
            //$emails[$contact["email"]] = $contact["email"];
            $attributes["CLIENT_EMAIL_ADDRESS"][] = $contact["email"];
         }
      }

      $lines_rst = $atmDB->GetARMCLines($armc["armc_id"]);
      while ($line = mysql_fetch_assoc($lines_rst)) {
         $def_attr = $atmDB->GetARMCBudgetLineItemDefAttr($line["armc_budget_line_item_def_id"], "ORA_INVENTORY_ITEM_ID");
         $line["inventory_item_id"] = $def_attr["armc_budget_line_item_def_attr_value"];

         $def_attr = $atmDB->GetARMCBudgetLineItemDefAttr($line["armc_budget_line_item_def_id"], "ORA_ACCOUNTING_RULE_ID");
         if ($def_attr) {
            $line["accounting_rule_id"] = $def_attr["armc_budget_line_item_def_attr_value"];
         } else {
            $line["accounting_rule_id"] = 1;
         }

         $def_attr = $atmDB->GetARMCBudgetLineItemDefAttr($line["armc_budget_line_item_def_id"], "ORA_INVOICING_RULE_ID");
         if ($def_attr) {
            $line["invoicing_rule_id"] = $def_attr["armc_budget_line_item_def_attr_value"];
         } else {
            $line["invoicing_rule_id"] = -2;
         }


         $def_attr = $atmDB->GetARMCBudgetLineItemDefAttr($line["armc_budget_line_item_def_id"], "ORA_ACCOUNTING_RULE_FREQUENCY");
         $line["accounting_rule_frequency"] = $def_attr["armc_budget_line_item_def_attr_value"];

         $def_attr = $atmDB->GetARMCBudgetLineItemDefAttr($line["armc_budget_line_item_def_id"], "ORA_ACCOUNTING_RULE_OCCURENCES");
         $line["accounting_rule_ocurrences"] = $def_attr["armc_budget_line_item_def_attr_value"];

         if (!($line["inventory_item_id"])) {
            return array("error_code"=>400, "error_message"=>"Oracle Inventory not properly defined for line item ".$line["armc_budget_line_item_description"]);
         }

         $cmb = $atmDB->GetATMGLAccount($line["atm_gl_account_id"]);
         if (!$cmb) {
            return array("error_code"=>402, "error_message"=>"GL account distribution not set for item ".$line["armc_budget_line_item_description"]);
         }

         $combinations[$cmb["atm_gl_account_segment1"]."_000_".$cmb["atm_gl_account_segment3"]."_".$cmb["atm_gl_account_segment4"]."_".$cmb["atm_gl_account_segment5"]."_".$cmb["atm_gl_account_segment6"]."_".$cmb["atm_gl_account_segment7"]] = array('SEGMENT1'=>$cmb["atm_gl_account_segment1"], 'SEGMENT2'=>'000', 'SEGMENT3'=>$cmb["atm_gl_account_segment3"], 'SEGMENT4'=>$cmb['atm_gl_account_segment4'], 'SEGMENT5'=>$cmb["atm_gl_account_segment5"], 'SEGMENT6'=>$cmb["atm_gl_account_segment6"], 'SEGMENT7'=>$cmb["atm_gl_account_segment7"]);
         $gl_distribution = $oraDB->GetAccountCombinations($combinations);
         if (!isset($gl_distribution[$cmb["atm_gl_account_segment1"]."_000_".$cmb["atm_gl_account_segment3"]."_".$cmb['atm_gl_account_segment4']."_".$cmb['atm_gl_account_segment5']."_".$cmb['atm_gl_account_segment6']."_".$cmb['atm_gl_account_segment7']]))
            return array("error_code"=>401, "error_message"=>"Oracle Distribution Account Combination invalid ".$cmb['atm_gl_account_segment1']."_000_".$cmb['atm_gl_account_segment3']."_".$cmb['atm_gl_account_segment4']."_".$cmb['atm_gl_account_segment5']."_".$cmb['atm_gl_account_segment6']."_".$cmb['atm_gl_account_segment7']);
         $line["code_combination_id"] = $gl_distribution[$cmb['atm_gl_account_segment1']."_000_".$cmb['atm_gl_account_segment3']."_".$cmb['atm_gl_account_segment4']."_".$cmb['atm_gl_account_segment5']."_".$cmb['atm_gl_account_segment6']."_".$cmb['atm_gl_account_segment7']];
         $line["study_id"] = $_invoice["study"]["study_id"];

         if (IsCreditMemo($_invoice["type"]["armc_type_id"])) {
            $line["actual_rate_i18n"] = -$line["actual_rate_i18n"];
         }
                
         $line["group_description"] = $armc['study_id'] . " - " . str_replace("'", "`", $line["group_description"]);
         $_invoice["lines"][] = $line;
      }
   }

   //$contact_details["email"] = implode(", ", $emails);
   $_invoice["contact"] = $contact_details;

   $_invoice["attributes"] = $attributes;
   $_invoice["delivery_type"] = array("email"=>$email_invoices, "print"=>$print_invoices, "fax"=>$fax_invoices);
   return array("error_code"=>0, "invoice"=>$_invoice);
}

/**
* GetInvoiceExportInfo()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 20 10:38:42 PST 2006
*/
//function GetInvoiceExportInfo($armc_id)
//{
//   $atmDB = new atmDB();
//   $commDB = new commonDB();
//   $oraDB = new oracleDB();
//   $userDB = new userDB();
//   //First things first, the armc_id
//   $_invoice = array("armc_id"=>$armc_id);
//
//   //Next, we set the type information
//   //array("armc_type_id", "armc_type_prefix", "armc_type_description")
//   $header_rst = $atmDB->GetARMCHeader($armc_id);
//   $header = mysql_fetch_assoc($header_rst);
//
//   if (!$header)
//      return array("error_code"=>100, "error_message"=>"Invalid BR id");
//
//   $_invoice["type"] = array(
//      "armc_type_id"=>$header["armc_type_id"],
//      "armc_type_prefix"=>$header["armc_type_prefix"],
//      "armc_type_description"=>$header["armc_type_description"]);
//
//   //Next, we set the status
//   //array("armc_status_id", "armc_status_description")
//   $_invoice["status"] = array(
//      "armc_status_id"=>$header["armc_status_id"],
//      "armc_status_description"=>$header["armc_status_description"]);
//
//   //Next, the study information ("study_id", "study_name")
//   $_invoice["study"] = array(
//      "study_id"=>$header["study_id"],
//      "study_name"=>str_replace("'", "`", $header["study_name"]));
//
//   $_invoice["account"] = array(
//      "account_id"=>$header["account_id"],
//      "account_name"=>str_replace("'", "`", $header["account_name"]));
//   $_invoice["account"]["account_country_code"]=$atmDB->GetARMCAttr($armc_id, "ACCOUNT_COUNTRY_CODE");
//
//   $i18n_currency_code = $atmDB->GetARMCAttr($armc_id, "I18N_CURRENCY_CODE");
//   if ($i18n_currency_code){
//      $i18n_exchange_rate = $atmDB->GetARMCAttr($armc_id, "I18N_EXCHANGE_RATE");
//   }else{
//      $i18n_currency_code = "USD";
//      $i18n_exchange_rate = 1;
//   }
//   $_invoice["account"]["currency"] = $i18n_currency_code;
//   $_invoice["account"]["exchange_rate"] = $i18n_exchange_rate;
//
//   //Next comes the account information
//   //Figure out the salesrepID
//   //If a group salesrep ID is set at account level, use that
//   //Else use the PRIMARY ACCCOUNT EXECUTIVE ARMC user role.
//   $salesrep_rst = $atmDB->GetARMCUsers($armc_id, array(ROLE_PRIMARY_ACCT_EXEC));
//   $salesrep_id = mysql_fetch_assoc($salesrep_rst);
//   if (!$salesrep_id)
//      return array("error_code"=>302, "error_message"=>"Primary Account Executive not set !!!");
//
//   $salesrep_name = $salesrep_id["user_name"];
//   $salesrep_login = $salesrep_id["login"];
//   $salesrep_id = $salesrep_id["login"];
//   $salesrep_details = $commDB->GetUserDetails($salesrep_id);
//   $salesrep_id = $commDB->GetUserAttr($salesrep_id, "ORA_ID");
//   if (!$salesrep_id) {
//      $salesrep_details = $oraDB->GetSalesrepByName($salesrep_name);
//      if (!$salesrep_details) {
//         return array("error_code"=>301, "error_message"=>"$salesrep_name is not setup as a Salesrep in Oracle !!!");
//      }else{
//         $salesrep_id = $salesrep_details["SALESREP_ID"];
//         $userDB->SetUserAttr($salesrep_login, "ORA_ID", $salesrep_id);
//      }
//   }
//
//
//   $_invoice["account"]["salesrep_id"] = $salesrep_id;
//   $_invoice["account"]["primary_ae_id"] = $salesrep_details["login"];
//   $_invoice["account"]["cost_center"] = $commDB->GetDepartmentAttr($salesrep_details["department_id"], "ORACODE");
//   $vendor_id = $commDB->GetProduct($header["product_id"]);
//   $vendor_id = $vendor_id["vendor_id"];
//   $_invoice["product"]["company_code"] = $commDB->GetVendorAttr($vendor_id, "ORACODE");
//   $_invoice["product"]["transaction_type_id"] = $commDB->GetVendorAttr($vendor_id, "ORATRANSID");
//   //echo ("Vendor : ".$vendor_id.", company code : ".$_invoice["product"]["company_code"].", transaction type id : ".$_invoice["product"]["transaction_type_id"]."<br>");
//
//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");
//   $account_details = HBRPCCall('acm', 'GetAccountDetail', array("account_id"=>$_invoice["account"]["account_id"]));
//   if ($account_details["error_code"]!=0)
//      return array("error_code"=>300, "error_message"=>"ACM error : ".$account_details["error_message"]);
//
//   foreach ($account_details["attr"] AS $attr) {
//      if ($attr["account_attr_name"]=="ARMC_ORA_ACCOUNT_ID")
//         $_invoice["account"]["hb_ora_account_id"] = $attr["account_attr_value"];
//      if (($attr["account_attr_name"]=="ARMC_ORA_AE_GROUP_ID")&&($attr["account_attr_value"]!=""))
//         $_invoice["account"]["salesrep_id"] = $attr["account_attr_value"];
//   }
//
////   $billing_contact_rst = $atmDB->GetARMCContacts($armc_id, array(ARMC_CONTACT_TYPE_BILLING));
////   $_invoice["contact"] = mysql_fetch_assoc($billing_contact_rst);
//
//   $_invoice["contact"] = array(
//      "contact_id"=>$header["billing_contact_id"],
//      "salutation"=>$header["billing_contact_salutation"],
//      "first_name"=>str_replace("'", "`", $header["billing_contact_first_name"]),
//      "last_name"=>str_replace("'", "`", $header["billing_contact_last_name"]),
//      "address1"=>str_replace("'", "`", $header["billing_contact_address1"]),
//      "address2"=>str_replace("'", "`", $header["billing_contact_address2"]),
//      "city"=>$header["billing_contact_city"],
//      "state"=>$header["billing_contact_state"],
//      "zip"=>$header["billing_contact_zip"],
//      "country"=>$header["billing_country_iso2_code"],
//      "country_code"=>$header["billing_country_code"],
//      "phone"=>$header["billing_contact_phone"],
//      "fax"=>$header["billing_contact_fax"],
//      "email"=>$header["billing_contact_email"]);
//
//   if (!$_invoice["contact"]) {
//      return array("error_code"=>200, "error_message"=>"Billing Contact not set");
//   }
//
//   if ($_invoice["contact"]["address1"]=="" || $_invoice["contact"]["city"]=="" || $_invoice["contact"]["country_code"]=="" || $_invoice["contact"]["email"]=="") {
//      return array("error_code"=>201, "error_message"=>"Billing Contact Address 1, City, Country and email are required !!!");
//   }
//
//   //$contact_details = HBRPCCall('acm', 'GetContactDetails', array("contact_id"=>$invoice["contact"]["contact_id"]));
//
//   $_invoice["contact"]["county"] = $commDB->GetCityDetails($_invoice["contact"]["zip"], $_invoice["contact"]["country_code"]);
//   if (!$_invoice["contact"]["county"])
//      $_invoice["contact"]["county"] = "???";
//   else
//      $_invoice["contact"]["county"] = $_invoice["contact"]["county"]["county"];
//
//   $ora_account_id = $oraDB->GetAccountByName($_invoice["account"]["account_name"]);
//   if (!$ora_account_id) {
//      //$ora_account_id = $oraDB->GetAccountByFuzzyName($_invoice["account"]["account_name"]);
//      //if (!$ora_account_id) {
//         $new_account = AddAccount($_invoice);
//         $_invoice["account"]["oracle_account_id"] = $new_account["oracle_account_id"];
//         $_invoice["contact"]["ora_site_id"] = $new_account["ora_site_id"];
//         $_invoice["contact"]["hb_ora_site_id"] = $new_account["ora_site_id"];
//         $_invoice["contact"]["ora_contact_id"] = $new_account["ora_contact_id"];
//         $_invoice["contact"]["hb_ora_contact_id"] = $new_account["ora_contact_id"];
//         //HBRPCCall('acm', 'SetAccountAttr', array("account_id"=>$_invoice["account"]["account_id"], "account_attr_name"=>"ARMC_ORA_ACCOUNT_ID", "account_attr_value"=>$new_account["oracle_account_id"]));
//         //HBRPCCall('acm', 'SetContactAttr', ...); //Oracle Site ID
//         //HBRPCCall('acm', 'SetContactAttr', ...); //Oracle Contact ID
//         //echo ("AddAccount();<br>");
//  }else{
//         $_invoice["account"]["oracle_account_id"] = $ora_account_id["CUST_ACCOUNT_ID"];
//         if (!isset($_invoice["account"]["hb_oracle_account_id"]) || (isset($_invoice["account"]["hb_oracle_account_id"]) && $_invoice["account"]["oracle_account_id"]!=$_invoice["account"]["hb_oracle_account_id"])) {
//            //HBRPCCall('acm', 'SetAccountAttr', array("account_id"=>$_invoice["account"]["account_id"], "account_attr_name"=>"ARMC_ORA_ACCOUNT_ID", "account_attr_value"=>$invoice["account"]["oracle_account_id"]));
//         }
//      //}
//   }
//   $ora_site_id = $oraDB->NewGetSiteByAddress($_invoice["contact"], $_invoice["account"]["oracle_account_id"]);
//   if (!$ora_site_id) {
//      $new_site = AddSite($_invoice);
//      $_invoice["contact"]["ora_site_id"] = $new_site["ora_site_id"];
//      $_invoice["contact"]["hb_ora_site_id"] = $new_site["ora_site_id"];
//      $_invoice["contact"]["ora_contact_id"] = $new_site["ora_contact_id"];
//      $_invoice["contact"]["hb_ora_contact_id"] = $new_site["ora_contact_id"];
//      //HBRPCCall('acm', 'SetContactAttr', ...); //Oracle Site ID
//      //HBRPCCall('acm', 'SetContactAttr', ...); //Oracle Contact ID
//      //echo ("AddSite();<br>");
//   }else{
//      $_invoice["contact"]["ora_site_id"] = $ora_site_id["CUST_ACCT_SITE_ID"];
//      if (!isset($_invoice["contact"]["hb_ora_site_id"]) || (isset($_invoice["contact"]["hb_ora_site_id"]) && $_invoice["contact"]["ora_site_id"]!=$_invoice["contact"]["hb_ora_site_id"])) {
//         //HBRPCCall('acm', 'SetContactAttr', ...);
//      }
//   }
//
//   $ora_contact_id = $oraDB->GetContactByName($_invoice["contact"]["first_name"], $_invoice["contact"]["last_name"], $_invoice["account"]["oracle_account_id"], $_invoice["contact"]["ora_site_id"]);
//   if (!$ora_contact_id) {
//      $new_contact = AddContact($_invoice);
//      $_invoice["contact"]["ora_contact_id"] = $new_contact["ora_contact_id"];
//      $_invoice["contact"]["hb_ora_contact_id"] = $new_contact["ora_contact_id"];
//      //HBRPCCall('acm', 'SetContactAttr', ...); //Oracle Contact ID
//      //echo ("AddContact();<br>");
//   }else{
//      $_invoice["contact"]["ora_contact_id"] = $ora_contact_id["CONTACT_ID"];
//      if (!isset($_invoice["contact"]["hb_ora_contact_id"]) || (isset($_invoice["contact"]["hb_ora_contact_id"]) && $_invoice["contact"]["ora_contact_id"]!=$_invoice["contact"]["hb_ora_contact_id"])) {
//         //HBRPCCall('acm', 'SetContactAttr', ...);
//      }
//   }
//
//   $timezone = $atmDB->GetARMCSetting("ORA_TIMEZONE_OFFSET");
//   $timezone = $timezone["armc_setting_value"];
//
//   $accounting_period = GetAccountingPeriod();
//   $invoice_rst = $atmDB->GetARMCInvoice($armc_id);
//   $_invoice["invoice"] = mysql_fetch_assoc($invoice_rst);
//   if ($_invoice["invoice"]["transaction_date"]=="" || $_invoice["invoice"]["transaction_date"]=="0000-00-00 00:00:00") {
//		if (date("Y-m-d")>=$accounting_period."-01" && date("Y-m-d")<=$accounting_period."-".GetLastDateOfMonth(mktime(0, 0, 0, substr($accounting_period, 5), 10, substr($accounting_period, 0, 4)))) {
//	      $_invoice["invoice"]["transaction_date"] = date("Y-m-d", time()+$timezone*60*60);
//		}else{
//			$_invoice["invoice"]["transaction_date"] = $accounting_period."-".GetLastDateOfMonth(mktime(0, 0, 0, substr($accounting_period, 5), 10, substr($accounting_period, 0, 4)));
//		}
//   }
//   $_invoice["invoice"]["job_name"] = str_replace("'", "`", $_invoice["invoice"]["job_name"]);
//   $_invoice["invoice"]["po_number"] = str_replace("'", "`", $_invoice["invoice"]["po_number"]);
//   $_invoice["invoice"]["pm_name"] = str_replace("'", "`", $_invoice["invoice"]["pm_name"]);
//   $_invoice["invoice"]["invoice_memo"] = str_replace("'", "`", $_invoice["invoice"]["invoice_memo"]);
//
//   //$accounting_rules = array("100:030"=>"1004", "100:040"=>"1003", "500:100"=>"1");
//
//   $lines_rst = $atmDB->GetARMCLines($armc_id);
//   while ($line = mysql_fetch_assoc($lines_rst)) {
//      $inventory_item = $oraDB->GetInventoryItem($line["item_number"]);
//      if (!$inventory_item)
//         return array("error_code"=>400, "error_message"=>"Oracle Inventory not defined for line item ".$line["armc_budget_line_item_description"]);
//
//      $line["inventory_item_id"] = $inventory_item["INVENTORY_ITEM_ID"];
//      $line["accounting_rule_id"] = $inventory_item["ACCOUNTING_RULE_ID"];
//      if (is_null($line["accounting_rule_id"])) {
//         $line["accounting_rule_id"] = 1;
//      }
//
//   	$combinations[$_invoice['product']['company_code']."_".$_invoice['contact']['country_code']."_".$_invoice['account']['cost_center']."_".$line['atm_gl_account_segment4']."_00_000_000000"] = Array('SEGMENT1'=>$_invoice['product']['company_code'], 'SEGMENT2'=>$_invoice['contact']['country_code'], 'SEGMENT3'=>$_invoice['account']['cost_center'], 'SEGMENT4'=>$line['atm_gl_account_segment4'], 'SEGMENT5'=>'00', 'SEGMENT6'=>'000', 'SEGMENT7'=>'000000');
//      $gl_distribution = $oraDB->GetAccountCombinations($combinations);
//      if (!isset($gl_distribution[$_invoice['product']['company_code']."_".$_invoice['contact']['country_code']."_".$_invoice['account']['cost_center']."_".$line['atm_gl_account_segment4']."_00_000_000000"]))
//         return array("error_code"=>401, "error_message"=>"Oracle Distribution Account Combination invalid ".$_invoice['product']['company_code']."_".$_invoice['contact']['country_code']."_".$_invoice['account']['cost_center']."_".$line['atm_gl_account_segment4']."_00_000_000000");
//      $line["code_combination_id"] = $gl_distribution[$_invoice['product']['company_code']."_".$_invoice['contact']['country_code']."_".$_invoice['account']['cost_center']."_".$line['atm_gl_account_segment4']."_00_000_000000"];
//      $line["study_id"] = $_invoice["study"]["study_id"];
//
//      if (IsCreditMemo($_invoice["type"]["armc_type_id"]))
//         $line["actual_rate_i18n"] = -$line["actual_rate_i18n"];
//
//      $line["group_description"] = str_replace("'", "`", $line["group_description"]);
//   	$_invoice["lines"][] = $line;
//   }
//
//   //$atmDB->debugPrint("<pre>".print_r($_invoice, true)."</pre>");
//   //return array("error_code"=>503, "error_message"=>"THLDF");
//   return array("error_code"=>0, "invoice"=>$_invoice);
//}

/**
* GetGroupInvoiceExportInfo()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Feb 21 14:47:04 PST 2006
*/
//function GetGroupInvoiceExportInfo($armc_group_id)
//{
//   $atmDB = new atmDB();
//   $commDB = new commonDB();
//   $oraDB = new oracleDB();
//   $userDB = new userDB();
//   //First things first, the armc_id
//   $_invoice = array("armc_group_id"=>$armc_group_id, "armc_id"=>$armc_group_id);
//
//   //Next, we set the type information
//   //array("armc_type_id", "armc_type_prefix", "armc_type_description")
//   $header_rst = $atmDB->GetARMCGroupHeader($armc_group_id);
//   $header = mysql_fetch_assoc($header_rst);
//
//   if (!$header)
//      return array("error_code"=>100, "error_message"=>"Invalid BR id");
//
//   $_invoice["type"] = array(
//      "armc_type_id"=>$header["armc_type_id"],
//      "armc_type_prefix"=>$header["armc_type_prefix"],
//      "armc_type_description"=>$header["armc_type_description"]);
//
//   //Next, we set the status
//   //array("armc_status_id", "armc_status_description")
//   $_invoice["status"] = array(
//      "armc_status_id"=>$header["armc_status_id"],
//      "armc_status_description"=>$header["armc_status_description"]);
//
//   //Next, the study information ("study_id", "study_name")
//   $_invoice["study"] = array(
//      "study_id"=>"MERGED",
//      "study_name"=>str_replace("'", "`", $header["armc_group_description"]));
//
//   $_invoice["account"] = array(
//      "account_id"=>$header["account_id"],
//      "account_name"=>str_replace("'", "`", $header["account_name"]));
//   $_invoice["account"]["account_country_code"] = $atmDB->GetARMCGroupAttr($armc_group_id, "ACCOUNT_COUNTRY_CODE");
//   $_invoice["account"]["currency"] = $atmDB->GetARMCGroupAttr($armc_group_id, "I18N_CURRENCY_CODE");
//   $_invoice["account"]["exchange_rate"] = $atmDB->GetARMCGroupAttr($armc_group_id, "I18N_EXCHANGE_RATE");
//
//   $invoices_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id = '$armc_group_id'", "");
//   $armc = mysql_fetch_assoc($invoices_rst);
//   //Next comes the account information
//   //Figure out the salesrepID
//   //If a group salesrep ID is set at account level, use that
//   //Else use the PRIMARY ACCCOUNT EXECUTIVE ARMC user role.
//   $salesrep_rst = $atmDB->GetARMCUsers($armc["armc_id"], array(ROLE_PRIMARY_ACCT_EXEC));
//   $salesrep_id = mysql_fetch_assoc($salesrep_rst);
//   if (!$salesrep_id)
//      return array("error_code"=>302, "error_message"=>"Primary Account Executive not set !!!");
//
//   $salesrep_name = $salesrep_id["user_name"];
//   $salesrep_login = $salesrep_id["login"];
//   $salesrep_id = $salesrep_id["login"];
//   $salesrep_details = $commDB->GetUserDetails($salesrep_id);
//   $salesrep_id = $commDB->GetUserAttr($salesrep_id, "ORA_ID");
//   if (!$salesrep_id) {
//      $salesrep_details = $oraDB->GetSalesrepByName($salesrep_name);
//      if (!$salesrep_details) {
//         return array("error_code"=>301, "error_message"=>"$salesrep_name is not setup as a Salesrep in Oracle !!!");
//      }else{
//         $salesrep_id = $salesrep_details["SALESREP_ID"];
//         $userDB->SetUserAttr($salesrep_login, "ORA_ID", $salesrep_id);
//      }
//
//   }
//
//
//   $_invoice["account"]["salesrep_id"] = $salesrep_id;
//   $_invoice["account"]["primary_ae_id"] = $salesrep_details["login"];
//   $_invoice["account"]["cost_center"] = $commDB->GetDepartmentAttr($salesrep_details["department_id"], "ORACODE");
//   //$_invoice["account"]["company_code"] = $commDB->GetVendorAttr($salesrep_details["vendor_id"], "ORACODE");
//   $vendor_id = $commDB->GetProduct($header["product_id"]);
//   $vendor_id = $vendor_id["vendor_id"];
//   $_invoice["product"]["company_code"] = $commDB->GetVendorAttr($vendor_id, "ORACODE");
//   $_invoice["product"]["transaction_type_id"] = $commDB->GetVendorAttr($vendor_id, "ORATRANSID");
//
//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");
//   $account_details = HBRPCCall('acm', 'GetAccountDetail', array("account_id"=>$_invoice["account"]["account_id"]));
//   if ($account_details["error_code"]!=0)
//      return array("error_code"=>300, "error_message"=>"ACM error : ".$account_details["error_message"]);
//
//   foreach ($account_details["attr"] AS $attr) {
//      if ($attr["account_attr_name"]=="ARMC_ORA_ACCOUNT_ID")
//         $_invoice["account"]["hb_ora_account_id"] = $attr["account_attr_value"];
//      if (($attr["account_attr_name"]=="ARMC_ORA_AE_GROUP_ID")&&($attr["account_attr_value"]!=""))
//         $_invoice["account"]["salesrep_id"] = $attr["account_attr_value"];
//   }
//
////   $billing_contact_rst = $atmDB->GetARMCContacts($armc_id, array(ARMC_CONTACT_TYPE_BILLING));
////   $_invoice["contact"] = mysql_fetch_assoc($billing_contact_rst);
//
//   $_invoice["contact"] = array(
//      "contact_id"=>$header["billing_contact_id"],
//      "salutation"=>$header["billing_contact_salutation"],
//      "first_name"=>str_replace("'", "`", $header["billing_contact_first_name"]),
//      "last_name"=>str_replace("'", "`", $header["billing_contact_last_name"]),
//      "address1"=>str_replace("'", "`", $header["billing_contact_address1"]),
//      "address2"=>str_replace("'", "`", $header["billing_contact_address2"]),
//      "city"=>$header["billing_contact_city"],
//      "state"=>$header["billing_contact_state"],
//      "zip"=>$header["billing_contact_zip"],
//      "country"=>$header["billing_country_iso2_code"],
//      "country_code"=>$header["billing_country_code"],
//      "phone"=>$header["billing_contact_phone"],
//      "fax"=>$header["billing_contact_fax"],
//      "email"=>$header["billing_contact_email"]);
//
//   if (!$_invoice["contact"]) {
//      return array("error_code"=>200, "error_message"=>"Billing Contact not set");
//   }
//
//   if ($_invoice["contact"]["address1"]=="" || $_invoice["contact"]["city"]=="" || $_invoice["contact"]["country_code"]=="" || $_invoice["contact"]["email"]=="") {
//      return array("error_code"=>201, "error_message"=>"Billing Contact Address 1, City, Country and email are required !!!");
//   }
//
//   //$contact_details = HBRPCCall('acm', 'GetContactDetails', array("contact_id"=>$invoice["contact"]["contact_id"]));
//
//   $_invoice["contact"]["county"] = $commDB->GetCityDetails($_invoice["contact"]["zip"], $_invoice["contact"]["country_code"]);
//   if (!$_invoice["contact"]["county"])
//      $_invoice["contact"]["county"] = "???";
//   else
//      $_invoice["contact"]["county"] = $_invoice["contact"]["county"]["county"];
//   $ora_account_id = $oraDB->GetAccountByName($_invoice["account"]["account_name"]);
//   if (!$ora_account_id) {
//      //$ora_account_id = $oraDB->GetAccountByFuzzyName($_invoice["account"]["account_name"]);
//      //if (!$ora_account_id) {
//         $new_account = AddAccount($_invoice);
//         $_invoice["account"]["oracle_account_id"] = $new_account["oracle_account_id"];
//         $_invoice["contact"]["ora_site_id"] = $new_account["ora_site_id"];
//         $_invoice["contact"]["hb_ora_site_id"] = $new_account["ora_site_id"];
//         $_invoice["contact"]["ora_contact_id"] = $new_account["ora_contact_id"];
//         $_invoice["contact"]["hb_ora_contact_id"] = $new_account["ora_contact_id"];
//         //HBRPCCall('acm', 'SetAccountAttr', array("account_id"=>$_invoice["account"]["account_id"], "account_attr_name"=>"ARMC_ORA_ACCOUNT_ID", "account_attr_value"=>$new_account["oracle_account_id"]));
//         //HBRPCCall('acm', 'SetContactAttr', ...); //Oracle Site ID
//         //HBRPCCall('acm', 'SetContactAttr', ...); //Oracle Contact ID
//         //echo ("AddAccount();<br>");
//   }else{
//         $_invoice["account"]["oracle_account_id"] = $ora_account_id["CUST_ACCOUNT_ID"];
//         if (!isset($_invoice["account"]["hb_oracle_account_id"]) || (isset($_invoice["account"]["hb_oracle_account_id"]) && $_invoice["account"]["oracle_account_id"]!=$_invoice["account"]["hb_oracle_account_id"])) {
//            //HBRPCCall('acm', 'SetAccountAttr', array("account_id"=>$_invoice["account"]["account_id"], "account_attr_name"=>"ARMC_ORA_ACCOUNT_ID", "account_attr_value"=>$invoice["account"]["oracle_account_id"]));
//         }
//      //}
//   }
//
//   $ora_site_id = $oraDB->NewGetSiteByAddress($_invoice["contact"], $_invoice["account"]["oracle_account_id"]);
//   if (!$ora_site_id) {
//      $new_site = AddSite($_invoice);
//      $_invoice["contact"]["ora_site_id"] = $new_site["ora_site_id"];
//      $_invoice["contact"]["hb_ora_site_id"] = $new_site["ora_site_id"];
//      $_invoice["contact"]["ora_contact_id"] = $new_site["ora_contact_id"];
//      $_invoice["contact"]["hb_ora_contact_id"] = $new_site["ora_contact_id"];
//      //HBRPCCall('acm', 'SetContactAttr', ...); //Oracle Site ID
//      //HBRPCCall('acm', 'SetContactAttr', ...); //Oracle Contact ID
//      //echo ("AddSite();<br>");
//   }else{
//      $_invoice["contact"]["ora_site_id"] = $ora_site_id["CUST_ACCT_SITE_ID"];
//      if (!isset($_invoice["contact"]["hb_ora_site_id"]) || (isset($_invoice["contact"]["hb_ora_site_id"]) && $_invoice["contact"]["ora_site_id"]!=$_invoice["contact"]["hb_ora_site_id"])) {
//         //HBRPCCall('acm', 'SetContactAttr', ...);
//      }
//   }
//
//   $ora_contact_id = $oraDB->GetContactByName($_invoice["contact"]["first_name"], $_invoice["contact"]["last_name"], $_invoice["account"]["oracle_account_id"], $_invoice["contact"]["ora_site_id"]);
//   if (!$ora_contact_id) {
//      $new_contact = AddContact($_invoice);
//      $_invoice["contact"]["ora_contact_id"] = $new_contact["ora_contact_id"];
//      $_invoice["contact"]["hb_ora_contact_id"] = $new_contact["ora_contact_id"];
//      //HBRPCCall('acm', 'SetContactAttr', ...); //Oracle Contact ID
//      //echo ("AddContact();<br>");
//   }else{
//      $_invoice["contact"]["ora_contact_id"] = $ora_contact_id["CONTACT_ID"];
//      if (!isset($_invoice["contact"]["hb_ora_contact_id"]) || (isset($_invoice["contact"]["hb_ora_contact_id"]) && $_invoice["contact"]["ora_contact_id"]!=$_invoice["contact"]["hb_ora_contact_id"])) {
//         //HBRPCCall('acm', 'SetContactAttr', ...);
//      }
//   }
//
//   $timezone = $atmDB->GetARMCSetting("ORA_TIMEZONE_OFFSET");
//   $timezone = $timezone["armc_setting_value"];
//
//   $accounting_period = GetAccountingPeriod();
//   $invoice_rst = $atmDB->GetARMCGroupInvoice($armc_group_id);
//   $_invoice["invoice"] = mysql_fetch_assoc($invoice_rst);
//   if ($_invoice["invoice"]["transaction_date"]=="" || $_invoice["invoice"]["transaction_date"]=="0000-00-00 00:00:00") {
//		if (date("Y-m-d")>=$accounting_period."-01" && date("Y-m-d")<=$accounting_period."-".GetLastDateOfMonth(mktime(0, 0, 0, substr($accounting_period, 5), 10, substr($accounting_period, 0, 4)))) {
//	      $_invoice["invoice"]["transaction_date"] = date("Y-m-d", time()+$timezone*60*60);
//		}else{
//			$_invoice["invoice"]["transaction_date"] = $accounting_period."-".GetLastDateOfMonth(mktime(0, 0, 0, substr($accounting_period, 5), 10, substr($accounting_period, 0, 4)));
//		}
//   }
////   $_invoice["invoice"]["project_name"] = $atmDB->GetARMCAttr($armc_id, "STUDY_NAME");
////   $_invoice["invoice"]["job_name"] = $atmDB->GetARMCAttr($armc_id, "JOBNUMBER");
////   $_invoice["invoice"]["po_number"] = $atmDB->GetARMCAttr($armc_id, "PONUMBER");
////   $_invoice["invoice"]["pm_name"] = $atmDB->GetARMCAttr($armc_id, "PMNAME");
////   $_invoice["invoice"]["invoice_memo"] = $atmDB->GetARMCAttr($armc_id, "INVMEMO");
////   $_invoice["invoice"]["invoice_date"] = $atmDB->GetARMCInvoice()
//
//   mysql_data_seek($invoices_rst, 0);
//   while ($inv = mysql_fetch_assoc($invoices_rst)) {
//      $_invoice["list"][$inv["armc_id"]] = $inv["study_id"];
//      $lines_rst = $atmDB->GetARMCLines($inv["armc_id"]);
//      while ($line = mysql_fetch_assoc($lines_rst)) {
//         $inventory_item = $oraDB->GetInventoryItem($line["item_number"]);
//         if (!$inventory_item)
//            return array("error_code"=>400, "error_message"=>"Oracle Inventory not defined for line item ".$line["armc_budget_line_item_description"]);
//
//         $line["inventory_item_id"] = $inventory_item["INVENTORY_ITEM_ID"];
//         $line["accounting_rule_id"] = $inventory_item["ACCOUNTING_RULE_ID"];
//         if (is_null($line["accounting_rule_id"])) {
//            $line["accounting_rule_id"] = 1;
//         }
//
//      	$combinations[$_invoice['product']['company_code']."_".$_invoice["contact"]["country_code"]."_".$_invoice['account']['cost_center']."_".$line['atm_gl_account_segment4']."_00_000_000000"] = Array('SEGMENT1'=>$_invoice['product']['company_code'], 'SEGMENT2'=>$_invoice["contact"]["country_code"], 'SEGMENT3'=>$_invoice['account']['cost_center'], 'SEGMENT4'=>$line['atm_gl_account_segment4'], 'SEGMENT5'=>'00', 'SEGMENT6'=>'000', 'SEGMENT7'=>'000000');
//         $gl_distribution = $oraDB->GetAccountCombinations($combinations);
//         if (!isset($gl_distribution[$_invoice['product']['company_code']."_".$_invoice["contact"]["country_code"]."_".$_invoice['account']['cost_center']."_".$line['atm_gl_account_segment4']."_00_000_000000"]))
//            return array("error_code"=>401, "error_message"=>"Oracle Distribution Account Combination invalid ".$_invoice['product']['company_code']."_".$_invoice["contact"]["country_code"]."_".$_invoice['account']['cost_center']."_".$line['atm_gl_account_segment4']."_00_000_000000");
//         $line["code_combination_id"] = $gl_distribution[$_invoice['product']['company_code']."_".$_invoice["contact"]["country_code"]."_".$_invoice['account']['cost_center']."_".$line['atm_gl_account_segment4']."_00_000_000000"];
//         $line["study_id"] = $inv["study_id"];
//
//         if (IsCreditMemo($_invoice["type"]["armc_type_id"]))
//            $line["actual_rate_i18n"] = -$line["actual_rate_i18n"];
//
//         $line["group_description"] = str_replace("'", "`", $line["group_description"]);
//      	$_invoice["lines"][] = $line;
//      }
//
//   }
//
//   //$atmDB->debugPrint("<pre>".print_r($_invoice, true)."</pre>");
//   return array("error_code"=>0, "invoice"=>$_invoice);
//
//}

/**
* GetCMExportInfo()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Feb 21 12:25:35 PST 2006
*/
function GetCMExportInfo($cm_id)
{
   $atmDB = new atmDB();
   $oraDB = new oracleDB();

   $_cm = array("armc_id"=>$cm_id);

   $_cm["type"] = $atmDB->GetARMCType($cm_id);
   if (!$_cm["type"]) {
      return array("error_code"=>100, "error_message"=>"Invalid BR id");
   }

   $_cm["account"]["account_id"] = $atmDB->GetARMCAttr($cm_id, "ACCOUNT_ID");
   $_cm["account"]["account_name"] = str_replace("'", "`", $atmDB->GetARMCAttr($cm_id, "ACCOUNT_NAME"));
   $_cm["account"]["account_country_code"] = $atmDB->GetARMCAttr($cm_id, "ACCOUNT_COUNTRY_CODE");
   $_cm["study"]["study_id"] = $atmDB->GetARMCAttr($cm_id, "STUDY_ID");
   $_cm["study"]["study_name"] = str_replace("'", "`", $atmDB->GetARMCAttr($cm_id, "STUDY_NAME"));

   $apply_to_armc_id = $atmDB->GetARMCAttr($cm_id, "CMAPPTO");
   if (!$apply_to_armc_id)
      return array("error_code"=>101, "error_message"=>"Couldn't find the Billing Report this Credit Memo applies to !!!");

   $apply_to_trx_id = $atmDB->GetARMCAttr($apply_to_armc_id, "TXNID");
   if (!$apply_to_trx_id)
      return array("error_code"=>102, "error_message"=>"Couldn't find the Oracle Transaction ID for Billing Report #".$apply_to_armc_id);

   $_cm["apply_to_trx_id"] = $apply_to_trx_id;

   $ae_rst = $atmDB->GetARMCUsers($cm_id, array(ROLE_PRIMARY_ACCT_EXEC));
   $ae = mysql_fetch_assoc($ae_rst);
   $_cm["account"]["primary_ae_id"] = $ae["login"];
   $lines_rst = $atmDB->GetARMCLines($cm_id);
   while ($line = mysql_fetch_assoc($lines_rst)) {
      $line["study_id"] = $_cm["study"]["study_id"];
   	$_cm["lines"][] = $line;
   }

   $oraDB->GetTxnLines($apply_to_trx_id);
   while ($txn_line = $oraDB->fetch_assoc()) {
      if (!isset($txn_line["ITEM_NUMBER"])) $txn_line["ITEM_NUMBER"]="";
      if (!isset($txn_line["STUDY_ID"])) $txn_line["STUDY_ID"]="";
      if (!isset($txn_line["GROUP_NAME"])) $txn_line["GROUP_NAME"]="";
      foreach ($_cm["lines"] AS $index=>$line) {
         if (($txn_line["ITEM_NUMBER"]==$line["item_number"])&&($txn_line["STUDY_ID"]==$line["study_id"])&&($txn_line["GROUP_NAME"]==str_replace("'", "`", $line["group_description"])))
            $_cm["lines"][$index]["customer_trx_line_id"] = $txn_line["CUSTOMER_TRX_LINE_ID"];
      }
   }

   foreach ($_cm["lines"] AS $line) {
      if (!isset($line["customer_trx_line_id"]))
         return array("error_code"=>200, "error_message"=>"Oracle Line Item ID for ".$line["armc_budget_line_item_description"]." not found !!!");
   }

   //$atmDB->debugPrint("<pre>".print_r($_cm, true)."</pre>");
   return array("error_code"=>0, "cm"=>$_cm);
}

/**
* GetGroupCMExportInfo()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Feb 21 12:25:35 PST 2006
*/
function GetGroupCMExportInfo($cm_group_id)
{
   $atmDB = new atmDB();
   $oraDB = new oracleDB();

   $_cm = array("armc_group_id"=>$cm_group_id, "armc_id"=>$cm_group_id);

   $header_rst = $atmDB->GetARMCGroupHeader($cm_group_id);
   $header = mysql_fetch_assoc($header_rst);
   if (!$header)
      return array("error_code"=>100, "error_message"=>"Invalid BR id");

   $_cm["type"] = array(
      "armc_type_id"=>$header["armc_type_id"],
      "armc_type_prefix"=>$header["armc_type_prefix"],
      "armc_type_description"=>$header["armc_type_description"]);

   $_cm["account"]["account_id"] = $header["account_id"];
   $_cm["account"]["account_name"] = str_replace("'", "`", $header["account_name"]);
   $_cm["account"]["account_country_code"] = $atmDB->GetARMCGroupAttr($cm_group_id, "ACCOUNT_COUNTRY_CODE");
   $_cm["study"]["study_id"] = "MERGED";
   $_cm["study"]["study_name"] = str_replace("'", "`", $header["group_description"]);

   $apply_to_armc_id = $atmDB->GetARMCGroupAttr($cm_group_id, "CMAPPTO");
   if (!$apply_to_armc_id)
      return array("error_code"=>101, "error_message"=>"Couldn't find the Billing Report this Credit Memo applies to !!!");

   $apply_to_trx_id = $atmDB->GetARMCGroupAttr($apply_to_armc_id, "TXNID");
   if (!$apply_to_trx_id)
      return array("error_code"=>102, "error_message"=>"Couldn't find the Oracle Transaction ID for Billing Report #".$apply_to_armc_id);

   $_cm["apply_to_trx_id"] = $apply_to_trx_id;

   $invoices_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id = '$cm_group_id'", "");
   while ($inv = mysql_fetch_assoc($invoices_rst)) {
      $_cm["list"][$inv["armc_id"]] = $inv["study_id"];
      $_cm["account"]["primary_ae_id"] = $inv["account_executive_id"];
      $lines_rst = $atmDB->GetARMCLines($inv["armc_id"]);
      while ($line = mysql_fetch_assoc($lines_rst)) {
         $line["study_id"] = $inv["study_id"];
      	$_cm["lines"][] = $line;
      }

   }

   $oraDB->GetTxnLines($apply_to_trx_id);
   while ($txn_line = $oraDB->fetch_assoc()) {
      if (!isset($txn_line["ITEM_NUMBER"])) $txn_line["ITEM_NUMBER"]="";
      if (!isset($txn_line["STUDY_ID"])) $txn_line["STUDY_ID"]="";
      if (!isset($txn_line["GROUP_NAME"])) $txn_line["GROUP_NAME"]="";
      foreach ($_cm["lines"] AS $index=>$line) {
         if (($txn_line["ITEM_NUMBER"]==$line["item_number"])&&($txn_line["STUDY_ID"]==$line["study_id"])&&($txn_line["GROUP_NAME"]==str_replace("'", "`", $line["group_description"])))
            $_cm["lines"][$index]["customer_trx_line_id"] = $txn_line["CUSTOMER_TRX_LINE_ID"];
      }
   }

   foreach ($_cm["lines"] AS $line) {
      if (!isset($line["customer_trx_line_id"]))
         return array("error_code"=>200, "error_message"=>"Oracle Line Item ID for study ".$line["study_id"].", line ".$line["armc_budget_line_item_description"]." not found !!!");
   }

   //$atmDB->debugPrint("<pre>".print_r($_cm, true)."</pre>");
   return array("error_code"=>0, "cm"=>$_cm);
}

function GetSalesrepByName($o) {
   $oracleDB = new oracleDB();

   $details = $oracleDB->GetSalesrepByName($o["name"]);
   if ($details) {
      echo ("Oracle ID for ".$o["name"]." is : ".$details["SALESREP_ID"]);
   }else{
      echo ("Oracle ID for ".$o["name"]." NOT FOUND !!!");
   }
}

function RunARMCExport($armc_id, &$errors, &$email_invoices) {
   $atmDB = new atmDB();
   
   $lock = $atmDB->GetARMCSetting("ORA_EXPORT_LOCK");
   if ($lock["armc_setting_value"]) {
      $errors[] = array("message"=>"Export process is running. This Billing Report might already be exported !!!");
      return;
   }

   if (IsCreditMemo($atmDB->GetARMCTypeId($armc_id))) {
      $cm = GetCMExportInfo($armc_id);
      if ($cm["error_code"] != 0) {
         $details_rst = $atmDB->GetARMCHeader($armc_id);
         $details = mysql_fetch_assoc($details_rst);
         if ($details)
            $email_invoices[] = array("success"=>0, "armc_id"=>$details["armc_type_prefix"]."-".$details["armc_id"], "account_name"=>$details["account_id"]." : ".$details["account_name"], "type"=>$details["armc_type_description"], "invoice"=>"Failure");

         $errors[] = array("message"=>$cm["error_message"], "value"=>$email_invoices[0]["armc_id"]);
            
         UpdateARMCFailedExport($armc_id, $cm["error_message"]);
         return;
      }
         
      ExportCMs(array($cm["cm"]), $errors, $email_invoices);

   } else {
      $inv = GetInvoiceExportInfo($armc_id);
      
      if ($inv["error_code"] != 0) {
         $details_rst = $atmDB->GetARMCHeader($armc_id);
         $details = mysql_fetch_assoc($details_rst);
         if ($details)
            $email_invoices[] = array("success"=>0, "armc_id"=>$details["armc_type_prefix"]."-".$details["armc_id"], "account_name"=>$details["account_id"]." : ".$details["account_name"], "type"=>$details["armc_type_description"], "invoice"=>"Failure");

         $errors[] = array("message"=>$inv["error_message"], "value"=>$email_invoices[0]["armc_id"]);
         UpdateARMCFailedExport($armc_id, $inv["error_message"]);
         return;
      }
      
      ExportInvoices(array($inv["invoice"]), $errors, $email_invoices);
   }   
}

/**
* SaveARMC()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 09 14:15:38 PST 2006
*/
function SaveARMC($o)
{
   global $encryption;
   $atmDB = new atmDB();
   $comDB = new commonDB();

   $contact_manager = new atm_armc_ContactsManager();
   $contact_manager_db = new db_atm_armc_ContactsManager();
   
   switch ($o["save_action"]) {
   case "correct" : {
      DoARMCCorrect($o["armc_id"], $o["save_param"]);
      header("Location: ?e=".$encryption->Encrypt("action=display_armc_details&armc_id=".$o["armc_id"]."&dont_log_view=1"));
   }break;
   case "stall" : {
   	DoARMCStall($o["armc_id"], $o["save_param"], $o["stall_clear_date"]);
      header("Location: ?e=".$encryption->Encrypt("action=default"));
   }break;

   case "reactivate" : {
  	  	DoARMCReactivate($o["armc_id"]);
     	header("Location: ?e=".$encryption->Encrypt("action=display_armc_details&armc_id=".$o["armc_id"]."&dont_log_view=1"));
   }break;

   case "change_status" : {
     	DoChangeARMCStatus($o["armc_id"]);
     	header("Location: ?e=".$encryption->Encrypt("action=display_armc_details&armc_id=".$o["armc_id"]."&dont_log_view=1"));
   }break;
   
  	case "delete" : {
   	DoARMCDelete($o["armc_id"], $o["save_param"]);
   	if ($o["armc_group_id"]!=0) {
   	   SetARMCGroupAttr($o["armc_group_id"], "AMOUNT", $atmDB->GetARMCGroupAmount($o["armc_group_id"]));
   	   SetARMCGroupAttr($o["armc_gorup_id"], "ARMC_STATUS_ID", $atmDB->GetARMCGroupStatusID($o["armc_group_id"]));
   	   header("Location: ?e=".$encryption->Encrypt("action=display_group_details&armc_group_id=".$o["armc_group_id"]));
   	}else{
         header("Location: ?e=".$encryption->Encrypt("action=default"));
   	}
   }break;

   case 'manually_invoiced' : {
      //echo ($o["save_param"]);
      $invoice_details = CheckOracleInvoice($o["save_param"], $o["armc_id"]);
      if ($invoice_details["error_code"]==0) {
         $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_INVOICED_MANUALLY);
         $atmDB->SetARMCTransactionDate($o["armc_id"], $invoice_details["transaction_date"]);
         $atmDB->SetARMCDate($o["armc_id"], $invoice_details["transaction_date"]);
         $atmDB->SetARMCTransactionNumber($o["armc_id"], $invoice_details["transaction_number"]);
         SetARMCAttr($o["armc_id"], "TXNID", $invoice_details["transaction_id"]);
         $atmDB->SetARMCStatus($o["armc_id"], ARMC_STATUS_INVOICED_MANUALLY);

         $_invoice["account"]["account_id"] = $atmDB->GetARMCAttr($o["armc_id"], "ACCOUNT_ID");
         $_invoice["account"]["account_country_code"] = $atmDB->GetARMCAttr($o["armc_id"], "ACCOUNT_COUNTRY_CODE");
         $ae_rst = $atmDB->GetARMCUsers($o["armc_id"], array(ROLE_PRIMARY_ACCT_EXEC));
         $ae = mysql_fetch_assoc($ae_rst);
         $_invoice["account"]["primary_ae_id"] = $ae["login"];
         $_invoice["type"] = $atmDB->GetARMCType($o["armc_id"]);
         $_invoice["invoice"]["transaction_date"] = $invoice_details["transaction_date"];
         $_invoice["amount"] = $invoice_details["transaction_amount"];
         //UpdateSAM($_invoice);

         header("Location: ?e=".$encryption->Encrypt("action=display_armc_details&armc_id=".$o["armc_id"]."&dont_log_view=1"));
      }else{
         header("Location: ?e=".$encryption->Encrypt("action=display_armc_details&armc_id=".$o["armc_id"]."&dont_log_view=1&error=".$invoice_details["error_message"]));
      }
   }break;

	case 'export' : {
		
	   $errors = array();
	   $email_invoices = array();
	   
	   RunARMCExport($o["armc_id"], $errors, $email_invoices);
	   
      SendManualExportResults($o["armc_id"], $email_invoices, $errors, false);      
      
      if (count($errors)) {
         $err = "&error=The Export to Oracle failed with the following message:<br/>";
         foreach($errors AS $error) {
            $err .= $error["message"]."<br/>";
         }
      }
      
      header("Location: ?e=".$encryption->Encrypt("action=display_armc_details&armc_id=".$o["armc_id"] . $err . "&dont_log_view=1"));
	   
	}break;

   case 'credit_memo' : {
      $correspondence = array(
         ARMC_TYPE_BR_STUDY=>ARMC_TYPE_CM_STUDY,
         ARMC_TYPE_RT_STUDY=>ARMC_TYPE_CM_STUDY,
         ARMC_TYPE_BR_TR=>ARMC_TYPE_CM_TR,
         ARMC_TYPE_RT_TR=>ARMC_TYPE_CM_TR,
         ARMC_TYPE_BR_LI=>ARMC_TYPE_CM_LI,
         ARMC_TYPE_RT_LI=>ARMC_TYPE_CM_LI,
         ARMC_TYPE_BR_SB=>ARMC_TYPE_CM_SB,
         ARMC_TYPE_RT_SB=>ARMC_TYPE_CM_SB,
         ARMC_TYPE_BR_OT=>ARMC_TYPE_CM_OT,
         ARMC_TYPE_RT_OT=>ARMC_TYPE_CM_OT,
         ARMC_TYPE_VAP_BR=>ARMC_TYPE_VAP_CM,
         ARMC_TYPE_VAP_RT=>ARMC_TYPE_VAP_CM,
         ARMC_TYPE_ERS_BR=>ARMC_TYPE_ERS_CM,
         ARMC_TYPE_ERS_RT=>ARMC_TYPE_ERS_CM,
         ARMC_TYPE_RSA_BR=>ARMC_TYPE_RSA_CM,
         ARMC_TYPE_RSA_RT=>ARMC_TYPE_RSA_CM,
         ARMC_TYPE_ISS_BR=>ARMC_TYPE_ISS_CM,
         ARMC_TYPE_ISS_RT=>ARMC_TYPE_ISS_CM,
         ARMC_TYPE_EFP_BR=>ARMC_TYPE_EFP_CM,
         ARMC_TYPE_EFP_RT=>ARMC_TYPE_EFP_CM,
         ARMC_TYPE_EFS_BR=>ARMC_TYPE_EFS_CM,
         ARMC_TYPE_EFS_RT=>ARMC_TYPE_EFS_CM);

      $armc_type = $atmDB->GetARMCType($o["armc_id"]);
      $cm_type = $correspondence[$armc_type["armc_type_id"]];
      $cm_product = $atmDB->GetARMCProduct($o["armc_id"]);
      $cm_product = $cm_product["product_id"];
      $cm_id = $atmDB->InsertARMC($cm_type, $cm_product);

      $attr_rst = $atmDB->GetARMCAttrs($o["armc_id"]);
      while ($attr = mysql_fetch_assoc($attr_rst)) {
      	SetARMCAttr($cm_id, $attr["armc_attr_name"], $attr["armc_attr_value"]);
      }
      $total = 0;
      SetARMCAttr($cm_id, "CMAPPTO", $o["armc_id"]);
      $lines_rst = $atmDB->GetARMCLines($o["armc_id"]);
      while ($line = mysql_fetch_assoc($lines_rst)) {
         $line["armc_id"] = $cm_id;
      	$atmDB->InsertARMCLine($line);
      	$total += $line["actual_quantity"]*$line["actual_rate"];
      }

      SetARMCAttr($cm_id, "AMOUNT", $total);

      $users_rst = $atmDB->GetARMCUsers($o["armc_id"]);
      while ($user = mysql_fetch_assoc($users_rst)) {
      	SetARMCUser($cm_id, $user["role_id"], $user["login"]);
      }
      $contacts = $contact_manager_db->GetARMCContacts($o["armc_id"]);
      foreach($contacts AS $$contact) {
      	$contact_manager->SetARMCContact($cm_id, $contact);
      	//SetARMCContact($cm_id, $contact);
      }
      $atmDB->WriteARMCEvent($cm_id, ARMC_EVENT_TYPE_CREATED);
      $atmDB->WriteARMCEvent($cm_id, ARMC_EVENT_TYPE_STATUS_EDIT);

      header("Location: ?e=".$encryption->Encrypt("action=display_armc_details&armc_id=$cm_id&dont_log_view=1"));
   }break;

   case 'copy' : {
      $copy_type = $atmDB->GetARMCType($o["armc_id"]);
      $copy_type = $copy_type["armc_type_id"];
      $copy_product = $atmDB->GetARMCProduct($o["armc_id"]);
      $copy_product = $copy_product["product_id"];
      $copy_id = $atmDB->InsertARMC($copy_type, $copy_product);

      $attr_rst = $atmDB->GetARMCAttrs($o["armc_id"]);
      while ($attr = mysql_fetch_assoc($attr_rst)) {
      	SetARMCAttr($copy_id, $attr["armc_attr_name"], $attr["armc_attr_value"]);
      }
      $total = 0;
      $lines_rst = $atmDB->GetARMCLines($o["armc_id"]);
      while ($line = mysql_fetch_assoc($lines_rst)) {
         $line["armc_id"] = $copy_id;
      	$atmDB->InsertARMCLine($line);
      	$total += $line["actual_quantity"]*$line["actual_rate"];
      }
      SetARMCAttr($copy_id, "AMOUNT", $total);

      $users_rst = $atmDB->GetARMCUsers($o["armc_id"]);
      while ($user = mysql_fetch_assoc($users_rst)) {
      	SetARMCUser($copy_id, $user["role_id"], $user["login"]);
      }
      $contacts = $contact_manager_db->GetARMCContacts($o["armc_id"]);
      foreach ($contacts AS $contact) {
      	//SetARMCContact($copy_id, $contact);
      	$contact_manager->SetARMCContact($copy_id, $contact);
      }
      $atmDB->WriteARMCEvent($copy_id, ARMC_EVENT_TYPE_CREATED);
      $atmDB->WriteARMCEvent($copy_id, ARMC_EVENT_TYPE_STATUS_EDIT);
      header("Location: ?e=".$encryption->Encrypt("action=display_armc_details&armc_id=$copy_id&dont_log_view=1"));
   }break;
   }
}

function RunARMCGroupExport($armc_group_id, &$errors, &$email_invoices) {
      $atmDB = new atmDB();
   
      $lock = $atmDB->GetARMCSetting("ORA_EXPORT_LOCK");
      if ($lock["armc_setting_value"]) {
         $errors[] = array("message"=>"Export process is running. This Billing Report might already be exported !!!");
         return;
      }

      if (IsCreditMemo($atmDB->GetARMCGroupTypeId($armc_group_id))) {
         $cm = GetGroupCMExportInfo($armc_group_id);
         if ($cm["error_code"] != 0) {
            $details_rst = $atmDB->GetARMCGroupHeader($armc_group_id);
            $details = mysql_fetch_assoc($details_rst);
            if ($details)
               $email_invoices[] = array("success"=>0, "armc_id"=>$details["armc_type_prefix"]."-".$details["armc_id"], "account_name"=>$details["account_id"]." : ".$details["account_name"], "type"=>$details["armc_type_description"], "invoice"=>"Failure");

            $errors[] = array("message"=>$cm["error_message"], "value"=>$email_invoices[0]["armc_id"]);
            UpdateARMCGroupFailedExport($armc_group_id, $cm["error_message"]);
            return;
         }
         
         ExportCMs(array($cm["cm"]), $errors, $email_invoices);
         
         return;         
      } else {
         $inv = GetGroupInvoiceExportInfo($armc_group_id);
         if ($inv["error_code"] != 0) {
            $details_rst = $atmDB->GetARMCGroupHeader($armc_group_id);
            $details = mysql_fetch_assoc($details_rst);
            if ($details)
               $email_invoices[] = array("success"=>0, "armc_id"=>$details["armc_type_prefix"]."-".$details["armc_id"], "account_name"=>$details["account_id"]." : ".$details["account_name"], "type"=>$details["armc_type_description"], "invoice"=>"Failure");

            $errors[] = array("message"=>$inv["error_message"], "value"=>$email_invoices[0]["armc_id"]);
            UpdateARMCGroupFailedExport($armc_group_id, $inv["error_message"]);
            return;
         }
         
         ExportInvoices(array($inv["invoice"]), $errors, $email_invoices);
         return;
      }
}
   
/**
* SaveARMCGroup()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Feb 14 11:33:37 PST 2006
*/
function SaveARMCGroup($o) {
	global $encryption;
	//print_r($o);
	$atmDB = new atmDB();

	//to call new Contact Manager class
	$contact_manager = new atm_armc_ContactsManager();
	$contact_manager_db = new db_atm_armc_ContactsManager();

	switch ($o["save_action"]) {
	case 'stall' : {
		$armc_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id=".$o["armc_group_id"], "");
		while ($armc = mysql_fetch_assoc($armc_rst)) {
			DoARMCStall($armc["armc_id"], $o["save_param"], $o["stall_clear_date"]);
		}
		//SetARMCGroupAttr($o["armc_group_id"], "ARMC_STATUS_ID", ARMC_STATUS_STALLED);
		SetARMCGroupAttr($o["armc_group_id"], "STALL_CLEAR_DATE", $o["stall_clear_date"]);
		header("Location: ?e=".$encryption->Encrypt("action=default"));
	}break;

	case 'reactivate' : {
		$armc_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id=".$o["armc_group_id"], "");
		while ($armc = mysql_fetch_assoc($armc_rst)) {
			DoARMCReactivate($armc["armc_id"]);
		}
		//SetARMCGroupAttr($o["armc_group_id"], "ARMC_STATUS_ID", $atmDB->GetARMCGroupStatusID($o["armc_group_id"]));
		header("Location: ?e=".$encryption->Encrypt("action=display_group_details&armc_group_id=".$o["armc_group_id"]));
	}break;

	case 'change_status' : {
		$armc_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id=".$o["armc_group_id"], "");
		while ($armc = mysql_fetch_assoc($armc_rst)) {
			DoChangeARMCStatus($armc["armc_id"]);
		}
		header("Location: ?e=".$encryption->Encrypt("action=display_group_details&armc_group_id=".$o["armc_group_id"]));
	}break;

	case 'delete' : {
		$armc_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id=".$o["armc_group_id"], "");
		while ($armc = mysql_fetch_assoc($armc_rst)) {
			DoARMCDelete($armc["armc_id"], $o["save_param"]);
		}
		header("Location: ?e=".$encryption->Encrypt("action=default"));
	}break;

	case 'manually_invoiced' : {
	  //print_r($o);
      $invoice_details = CheckOracleGroupInvoice($o["save_param"], $o["armc_group_id"]);
      //print_r($invoice_details);
      if ($invoice_details["error_code"]==0) {
      	 $armc_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id = ".$o["armc_group_id"], "");
      	 while ($armc = mysql_fetch_assoc($armc_rst)) {
	         $atmDB->WriteARMCEvent($armc["armc_id"], ARMC_EVENT_TYPE_INVOICED_MANUALLY);
            $atmDB->SetARMCTransactionDate($armc["armc_id"], $invoice_details["transaction_date"]);
            $atmDB->SetARMCDate($armc["armc_id"], $invoice_details["transaction_date"]);
        	   $atmDB->SetARMCTransactionNumber($armc["armc_id"], $invoice_details["transaction_number"]);
    	      SetARMCAttr($armc["armc_id"], "TXNID", $invoice_details["transaction_id"]);
	         $atmDB->SetARMCStatus($armc["armc_id"], ARMC_STATUS_INVOICED_MANUALLY);
	         $armc_id = $armc["armc_id"];
      	 }
      	 $atmDB->SetARMCGroupDate($o["armc_group_id"], $invoice_details["transaction_date"]);

      	 $_invoice["account"]["account_id"] = $atmDB->GetARMCGroupAttr($o["armc_group_id"], "ACCOUNT_ID");
          $_invoice["account"]["account_country_code"] = $atmDB->GetARMCGroupAttr($o["armc_group_id"], "ACCOUNT_COUNTRY_CODE");
      	 $ae_rst = $atmDB->GetARMCUsers($armc_id, array(ROLE_PRIMARY_ACCT_EXEC));
      	 $ae = mysql_fetch_assoc($ae_rst);
      	 $_invoice["account"]["primary_ae_id"] = $ae["login"];
      	 $_invoice["invoice"]["transaction_date"] = $invoice_details["transaction_date"];
      	 $_invoice["type"]["armc_type_id"] = $atmDB->GetARMCGroupTypeID($o["armc_group_id"]);
      	 $_invoice["amount"] = $invoice_details["transaction_amount"];

      	 //UpdateSAM($_invoice);


      	 SetARMCGroupAttr($o["armc_group_id"], "TXNID", $invoice_details["transaction_id"]);
          header("Location: ?e=".$encryption->Encrypt("action=display_group_details&armc_group_id=".$o["armc_group_id"]));
      }else{
         header("Location: ?e=".$encryption->Encrypt("action=display_group_details&armc_group_id=".$o["armc_group_id"]."&error=".$invoice_details["error_message"]));
      }
	}break;

	case 'export' : {
	   $errors = array();
	   $email_invoices = array(); 
	   
	   RunARMCGroupExport($o["armc_group_id"], $errors, $email_invoices);

      SendManualExportResults($o["armc_group_id"], $email_invoices, $errors, true);

      if (count($errors)) {
         $err = "&error=The Export to Oracle failed with the following message:<br/>";
         foreach($errors AS $error) {
            $err .= $error["message"]."<br/>";
         }
      }
      header("Location: ?e=".$encryption->Encrypt("action=display_group_details&armc_group_id=".$o["armc_group_id"].$err));
	}break;

	case 'credit_memo' : {
      $correspondence = array(
         ARMC_TYPE_BR_STUDY=>ARMC_TYPE_CM_STUDY,
         ARMC_TYPE_RT_STUDY=>ARMC_TYPE_CM_STUDY,
         ARMC_TYPE_BR_TR=>ARMC_TYPE_CM_TR,
         ARMC_TYPE_RT_TR=>ARMC_TYPE_CM_TR,
         ARMC_TYPE_BR_LI=>ARMC_TYPE_CM_LI,
         ARMC_TYPE_RT_LI=>ARMC_TYPE_CM_LI,
         ARMC_TYPE_BR_SB=>ARMC_TYPE_CM_SB,
         ARMC_TYPE_RT_SB=>ARMC_TYPE_CM_SB,
         ARMC_TYPE_BR_OT=>ARMC_TYPE_CM_OT,
         ARMC_TYPE_RT_OT=>ARMC_TYPE_CM_OT);

	   $group_cm_id = $atmDB->InsertARMCGroup();
	   $attr_rst = $atmDB->GetARMCGroupAttrs($o["armc_group_id"]);
      while ($attr = mysql_fetch_assoc($attr_rst)) {
         SetARMCGroupAttr($group_cm_id, $attr["armc_group_attr_name"], $attr["armc_group_attr_value"]);
      }
      SetARMCGroupAttr($group_cm_id, "CMAPPTO", $o["armc_group_id"]);

	   $armc_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id = ".$o["armc_group_id"], "");
	   while ($armc = mysql_fetch_assoc($armc_rst)) {
	      $armc_type = $atmDB->GetARMCType($armc["armc_id"]);
	      //print_r($armc_type);
         $cm_type = $correspondence[$armc_type["armc_type_id"]];
         $cm_product = $atmDB->GetARMCProduct($armc["armc_id"]);
         $cm_product = $cm_product["product_id"];
         $cm_id = $atmDB->InsertARMC($cm_type, $cm_product);

         $atmDB->SetARMCGroupID($cm_id, $group_cm_id);

         $attr_rst = $atmDB->GetARMCAttrs($armc["armc_id"]);
         while ($attr = mysql_fetch_assoc($attr_rst)) {
      	  SetARMCAttr($cm_id, $attr["armc_attr_name"], $attr["armc_attr_value"]);
         }
         SetARMCAttr($cm_id, "CMAPPTO", $armc["armc_id"]);
         $lines_rst = $atmDB->GetARMCLines($armc["armc_id"]);
         while ($line = mysql_fetch_assoc($lines_rst)) {
            $line["armc_id"] = $cm_id;
         	$atmDB->InsertARMCLine($line);
         }

         $users_rst = $atmDB->GetARMCUsers($armc["armc_id"]);
         while ($user = mysql_fetch_assoc($users_rst)) {
         	SetARMCUser($cm_id, $user["role_id"], $user["login"]);
         }
         $contacts = $contact_manager_db->GetARMCContacts($armc["armc_id"]);
         foreach ($contacts AS $contact) {
         	//SetARMCContact($cm_id, $contact);
         	$contact_manager->SetARMCContact($cm_id, $contact);
         }
         $atmDB->WriteARMCEvent($cm_id, ARMC_EVENT_TYPE_CREATED);
         $atmDB->WriteARMCEvent($cm_id, ARMC_EVENT_TYPE_STATUS_EDIT);
	   }
	   $atmDB->SetARMCGroupDate($group_cm_id, $atmDB->GetARMCGroupDate($group_cm_id));
	   SetARMCGroupAttr($group_cm_id, "ARMC_STATUS_ID", $atmDB->GetARMCGroupStatusID($group_cm_id));
	   SetARMCGroupAttr($group_cm_id, "AMOUNT", $atmDB->GetARMCGroupAmount($group_cm_id));
	   header("Location: ?e=".$encryption->Encrypt("action=display_group_details&armc_group_id=".$group_cm_id));
	}break;

	case 'copy' : {
	   $group_copy_id = $atmDB->InsertARMCGroup();
	   $attr_rst = $atmDB->GetARMCGroupAttrs($o["armc_group_id"]);
      while ($attr = mysql_fetch_assoc($attr_rst)) {
         SetARMCGroupAttr($group_copy_id, $attr["armc_group_attr_name"], $attr["armc_group_attr_value"]);
      }

	   $armc_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id = ".$o["armc_group_id"], "");
	   while ($armc = mysql_fetch_assoc($armc_rst)) {
	      $armc_type = $atmDB->GetARMCType($armc["armc_id"]);
	      //print_r($armc_type);
         $cm_type = $armc_type["armc_type_id"];
         $cm_product = $atmDB->GetARMCProduct($armc["armc_id"]);
         $cm_product = $cm_product["product_id"];
         $cm_id = $atmDB->InsertARMC($cm_type, $cm_product);

         $atmDB->SetARMCGroupID($cm_id, $group_copy_id);

         $attr_rst = $atmDB->GetARMCAttrs($armc["armc_id"]);
         while ($attr = mysql_fetch_assoc($attr_rst)) {
      	  SetARMCAttr($cm_id, $attr["armc_attr_name"], $attr["armc_attr_value"]);
         }
         $lines_rst = $atmDB->GetARMCLines($armc["armc_id"]);
         while ($line = mysql_fetch_assoc($lines_rst)) {
            $line["armc_id"] = $cm_id;
         	$atmDB->InsertARMCLine($line);
         }

         $users_rst = $atmDB->GetARMCUsers($armc["armc_id"]);
         while ($user = mysql_fetch_assoc($users_rst)) {
         	SetARMCUser($cm_id, $user["role_id"], $user["login"]);
         }
         $contacts = $contact_manager_db->GetARMCContacts($armc["armc_id"]);
         foreach ($contacts AS $contact) {
         	$contact_manager->SetARMCContact($cm_id, $contact);
         	//SetARMCContact($cm_id, $contact);
         }
         $atmDB->WriteARMCEvent($cm_id, ARMC_EVENT_TYPE_CREATED);
         $atmDB->WriteARMCEvent($cm_id, ARMC_EVENT_TYPE_STATUS_EDIT);
	   }
	   $atmDB->SetARMCGroupDate($group_copy_id, $atmDB->GetARMCGroupDate($group_copy_id));
	   SetARMCGroupAttr($group_copy_id, "ARMC_STATUS_ID", $atmDB->GetARMCGroupStatusID($group_copy_id));
	   SetARMCGroupAttr($group_copy_id, "AMOUNT", $atmDB->GetARMCGroupAmount($group_copy_id));
	   header("Location: ?e=".$encryption->Encrypt("action=display_group_details&armc_group_id=".$group_copy_id));
	}break;
	}
}

/**
* Unmerge()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 06 15:28:37 PST 2006
*/
function Unmerge($o)
{
   global $encryption;
   $atmDB = new atmDB();
   foreach ($o AS $name=>$value) {
      if (preg_match("/armcs_/", $name)) {
         $armc_id = substr($name, 6);
         $atmDB->Unmerge($armc_id);
         $atmDB->WriteARMCEvent($armc_id, ARMC_EVENT_TYPE_UNMERGED);
      }
   }

   SetARMCGroupAttr($o["armc_group_id"], "AMOUNT", $atmDB->GetARMCGroupAmount($o["armc_group_id"]));
   SetARMCGroupAttr($o["armc_group_id"], "ARMC_STATUS_ID", $atmDB->GetARMCGroupStatusID($o["armc_group_id"]));
   $atmDB->SetARMCGroupDate($o["armc_group_id"], $atmDB->GetARMCGroupDate($o["armc_group_id"]));

   $atmDB->GetARMCGroupList(" AND a.armc_group_id = '".$o["armc_group_id"]."'", "");
   if ($atmDB->rows==0) {
      $atmDB->DeleteARMCGroup($o["armc_group_id"]);
      header("Location: ?e=".$encryption->Encrypt("action=default"));
      exit;
   }else{
      header("Location: ?e=".$encryption->Encrypt("action=display_group_details&armc_group_id=".$o["armc_group_id"]));
      exit;
   }
}

/**
* HandleMerge()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 06 16:23:43 PST 2006
*/
function HandleMerge($o)
{
   global $smarty, $encryption;
   $atmDB = new atmDB();
   $commonDB = new commonDB();
   if (!isset($o["search"])) {
      $types_result = $atmDB->GetARMCTypes();
      while ($type = mysql_fetch_assoc($types_result)) {
         if (!IsCreditMemo($type["armc_type_id"]))
            $lists["armc_type"][$type["armc_type_id"]] = $type["product_description"]." - ".$type["armc_type_description"];
      }

      //$lists['armc_type'] = $types;
      //$lists['armc_account_executive'] = CreateSmartyArray($commonDB->GetUsersByRoleId(ROLE_ACCOUNT_EXECUTIVE), 'login', 'name');
      $smarty->assign("lists", $lists);
      DisplayHeader('Billing Reports', 'armc', $o["action"]);
      $smarty->display("app/atm/armc/vw_merge_search.tpl");
      DisplayFooter();
   }elseif ($o["search"]==1){
      $o["report_title"] = "Merge Billing Reports";
      if (!is_array($o["search_type_id"]))
         $o["search_type_id"] = array($o["search_type_id"]);
      if (!isset($o["sort"]))
         $o["sort"] = "armc_id";
      $o["search_status_id"] = array(ARMC_STATUS_EDIT, ARMC_STATUS_AM_APPROVAL, ARMC_STATUS_AE_APPROVAL, ARMC_STATUS_ACCT_APPROVAL, ARMC_STATUS_READY_FOR_INVOICE, ARMC_STATUS_STALLED);
      $o["sort_by_column"] = $o["sort"];
      $filter = BuildSearchFilter($o);
      $header[0] = array("align"=>"center", "width"=>"2%", "sort"=>"no", "title"=>"", "field"=>"");
      $header[1] = array("align"=>"left", "width"=>"10%", "sort"=>"yes", "title"=>"Status", "field"=>"armc_status_description");
      $header[2] = array("align"=>"left", "width"=>"6%", "sort"=>"yes", "title"=>"BR #", "field"=>"armc_id");
      $header[3] = array("align"=>"left", "width"=>"6%", "sort"=>"yes", "title"=>"Study ID", "field"=>"study_id");
      $header[4] = array("align"=>"left", "width"=>"18%", "sort"=>"yes", "title"=>"Project Name", "field"=>"study_name");
      $header[5] = array("align"=>"left", "width"=>"18%", "sort"=>"yes", "title"=>"Partner Name", "field"=>"account_name");
      $header[6] = array("align"=>"center", "width"=>"8%", "sort"=>"yes", "title"=>"BR Date", "field"=>"armc_date");
      $header[7] = array("align"=>"right", "width"=>"6%", "sort"=>"yes", "title"=>"AE", "field"=>"account_executive");
      $header[8] = array("align"=>"right", "width"=>"6%", "sort"=>"yes", "title"=>"AM", "field"=>"account_manager");
      $header[9] = array("align"=>"right", "width"=>"10%", "sort"=>"yes", "title"=>"Amount", "field"=>"amount");

      //echo ("$filter");
      $list_rst = $atmDB->GetARMCList($filter, "ORDER BY ".$o["sort"]);
      while ($item = mysql_fetch_assoc($list_rst)) {
      	//print_r($item);
      	$list[] = $item;
      }
      $hidden_fields = array(
         array("name"=>"search_account_id", "value"=>$o["search_account_id"]),
         array("name"=>"search_type_id", "value"=>$o["search_type_id"][0]),
         array("name"=>"search", "value"=>1));
      $smarty->assign("header", $header);
      $smarty->assign("hidden_fields", $hidden_fields);
      $smarty->assign("list", $list);
      $smarty->assign("meta", $o);
      DisplayHeader('Billing Reports', 'armc', $o["action"]);
      $smarty->display("app/atm/armc/vw_merge_list.tpl");
      DisplayFooter();
   }else{
      $grouped = array();
      $single = array();
      foreach($o AS $name=>$value) {
         if (preg_match('/group_armcs_/', $name)) {
            $id = substr($name, 12);
            $g = mysql_fetch_assoc($atmDB->GetARMCGroupHeader($id));
            $g["invoice"] = mysql_fetch_assoc($atmDB->GetARMCGroupInvoice($id));
            $g["i18n_currency_code"]=$atmDB->GetARMCGroupAttr($id, "I18N_CURRENCY_CODE");
            $g["proposal"] = $atmDB->GetARMCAttr($g["armc_id"], "PROPOSAL_ID");
            //$g["i18n_exchange_rate"]=$atmDB->GetARMCGroupAttr($id, "I18N_EXCHANGE_RATE");
            $grouped[] = $g;
         }elseif (preg_match('/armcs_/', $name)) {
            $id = substr($name, 6);
            $s = mysql_fetch_assoc($atmDB->GetARMCHeader($id));
            $s["i18n_currency_code"] = $atmDB->GetARMCAttr($id, "I18N_CURRENCY_CODE");
            $s["proposal"] = $atmDB->GetARMCAttr($id, "PROPOSAL_ID");
            //$s["i18n_exchange_rate"]=$atmDB->GetARMCAttr($id, "I18N_EXCHANGE_RATE");
            $single[] = $s;
         }
      }

      $error_message = "";
      if (sizeof($grouped)>0) {
         $group_description = $grouped[0]["armc_group_description"];
         $group_invoice = $grouped[0]["invoice"];
         $ae = $grouped[0]["account_executive_id"];
         $billing_contact = $grouped[0]["billing_contact_id"];
         $currency_code = $grouped[0]["i18n_currency_code"];
         $proposal_id = $grouped[0]["proposal"];
         //$exchange_rate = $grouped[0]["i18n_exchange_rate"];
      }elseif (sizeof($single)>0){
         $ae = $single[0]["account_executive_id"];
         $billing_contact = $single[0]["billing_contact_id"];
         $currency_code = $single[0]["i18n_currency_code"];
         $proposal_id = $single[0]["proposal"];
         //$exchange_rate = $single[0]["i18n_exchange_rate"];
      }else{
         $error_message = "Please select the Billing Reports you want to merge";
      }
      $check_proposal = $atmDB->GetARMCSetting("MERGE_ONLY_SAME_PROPOSAL");
      $check_proposal = $check_proposal["armc_setting_value"];

      $failures = array("ae"=>0, "contact"=>0, "currency"=>0, "exchange"=>0);
      foreach($grouped AS $armc) {
         if ($armc["account_executive_id"]!=$ae)
            $failures["ae"] = 1;
         if ($armc["billing_contact_id"]!=$billing_contact)
            $failures["contact"] = 1;
         if ($armc["i18n_currency_code"]!=$currency_code)
            $failures["currency"] = 1;
         if ($check_proposal && $armc["proposal"] != $proposal_id)
            $failures["proposal"] = 1;
//         if ($armc["i18n_exchange_rate"]!=$exchange_rate)
//            $failures["exchange"] = 1;
      }
      foreach($single AS $armc) {
         if ($armc["account_executive_id"]!=$ae)
            $failures["ae"] = 1;
         if ($armc["billing_contact_id"]!=$billing_contact)
            $failures["contact"] = 1;
         if ($armc["i18n_currency_code"]!=$currency_code)
            $failures["currency"] = 1;
         if ($check_proposal && $armc["proposal"] != $proposal_id)
            $failures["proposal"] = 1;
//         if ($armc["i18n_exchange_rate"]!=$exchange_rate)
//            $failures["exchange"] = 1;
      }

      $f = array_count_values($failures);
      if ($f[1]) $error_message .= "The selected Billing Reports cannot be merged because : ";
      if ($failures["ae"])
         $error_message.="<li>They have to belong to the same Account Executive";
      if ($failures["contact"])
         $error_message.="<li>They need to have the same Billing Contact";
      if ($failures["currency"])
         $error_message.="<li>They need to be invoiced in the same currency";
      if ($failures["proposal"])
         $error_message .= "<li>They must be based on the same proposal";
//      if (!$failures["currency"]&&$failures["exchange"])
//         $error_message.="<li>They need to have the same exchange rate for the foreign currency";


      $exchange_rate = $commonDB->GetExchangeRate("USD", $currency_code);
      if (!$exchange_rate) $exchange_rate = 1;

      if ($error_message==="") {
         $new_group_id = $atmDB->InsertARMCGroup();
         SetARMCGroupAttr($new_group_id, "ACCOUNT_ID", $o["search_account_id"]);
         SetARMCGroupAttr($new_group_id, "I18N_CURRENCY_CODE", $currency_code);
         SetARMCGroupAttr($new_group_id, "I18N_EXCHANGE_RATE", $exchange_rate);
         foreach ($grouped AS $index=>$group) {
            $armcs_rst = $atmDB->GetARMCGroupList(" AND a.armc_group_id='".$group["armc_group_id"]."'", "");
            while ($armc = mysql_fetch_assoc($armcs_rst)) {
               $i18n = $atmDB->GetARMCAttr($armc["armc_id"], "I18N_CURRENCY_EDIT");
               $lines_rst = $atmDB->GetARMCLines($armc["armc_id"]);
               while ($line = mysql_fetch_assoc($lines_rst)) {
                  if (!$i18n) {
                     $proposed_rate = round($line["proposed_rate"], 3);
                     $proposed_rate_i18n = round($proposed_rate/$exchange_rate, 3);
                     $actual_rate = round($line["actual_rate"], 3);
                     $actual_rate_i18n = round($actual_rate/$exchange_rate, 3);
                  }else{
                     $proposed_rate_i18n = round($line["proposed_rate_i18n"], 3);
                     $proposed_rate = round($proposed_rate_i18n*$exchange_rate, 3);
                     $actual_rate_i18n = round($line["actual_rate_i18n"], 3);
                     $actual_rate = round($actual_rate_i18n*$exchange_rate, 3);
                  }
                  $line = array(
                     "armc_id"=>$armc["armc_id"],
                     "armc_budget_line_item_id"=>$line["armc_budget_line_item_id"],
                     "armc_budget_line_item_def_id"=>$line["armc_budget_line_item_def_id"],
                     "armc_budget_line_item_description"=>$line["armc_budget_line_item_description"],
                     "group_description"=>$line["group_description"],
                     "unit_type"=>$line["unit_type"],
                     "proposed_quantity"=>$line["proposed_quantity"],
                     "proposed_rate"=>$proposed_rate,
                     "proposed_rate_i18n"=>$proposed_rate_i18n,
                     "actual_quantity"=>$line["actual_quantity"],
                     "actual_rate"=>$actual_rate,
                     "actual_rate_i18n"=>$actual_rate_i18n);
                  //$atmDB->debugPrint(print_r($line, true));
                  $atmDB->UpdateARMCLine($line);
               }

               SetARMCAttr($armc["armc_id"], "AMOUNT", $atmDB->GetARMCAmount($armc["armc_id"]));
               SetARMCAttr($armc["armc_id"], "I18N_EXCHANGE_RATE", $exchange_rate);
            	$atmDB->SetARMCGroupID($armc["armc_id"], $new_group_id);
            	$atmDB->WriteARMCEvent($armc["armc_id"], ARMC_EVENT_TYPE_MERGED, "Merged BR : ".$new_group_id);
            }
            $atmDB->DeleteARMCGroup($group["armc_group_id"]);
         }
         foreach ($single AS $index=>$armc) {
            $i18n = $atmDB->GetARMCAttr($armc["armc_id"], "I18N_CURRENCY_EDIT");
            $lines_rst = $atmDB->GetARMCLines($armc["armc_id"]);
            while ($line = mysql_fetch_assoc($lines_rst)) {
               if (!$i18n) {
                  $proposed_rate = round($line["proposed_rate"], 3);
                  $proposed_rate_i18n = round($proposed_rate/$exchange_rate, 3);
                  $actual_rate = round($line["actual_rate"], 3);
                  $actual_rate_i18n = round($actual_rate/$exchange_rate, 3);
               }else{
                  $proposed_rate_i18n = round($line["proposed_rate_i18n"], 3);
                  $proposed_rate = round($proposed_rate_i18n*$exchange_rate, 3);
                  $actual_rate_i18n = round($line["actual_rate_i18n"], 3);
                  $actual_rate = round($actual_rate_i18n*$exchange_rate, 3);
               }
               $line = array(
                  "armc_id"=>$armc["armc_id"],
                  "armc_budget_line_item_id"=>$line["armc_budget_line_item_id"],
                  "armc_budget_line_item_def_id"=>$line["armc_budget_line_item_def_id"],
                  "armc_budget_line_item_description"=>$line["armc_budget_line_item_description"],
                  "group_description"=>$line["group_description"],
                  "unit_type"=>$line["unit_type"],
                  "proposed_quantity"=>$line["proposed_quantity"],
                  "proposed_rate"=>$proposed_rate,
                  "proposed_rate_i18n"=>$proposed_rate_i18n,
                  "actual_quantity"=>$line["actual_quantity"],
                  "actual_rate"=>$actual_rate,
                  "actual_rate_i18n"=>$actual_rate_i18n);
               //$atmDB->debugPrint(print_r($line, true));
               $atmDB->UpdateARMCLine($line);
            }

            SetARMCAttr($armc["armc_id"], "AMOUNT", $atmDB->GetARMCAmount($armc["armc_id"]));
            SetARMCAttr($armc["armc_id"], "I18N_EXCHANGE_RATE", $exchange_rate);
            $atmDB->SetARMCGroupID($armc["armc_id"], $new_group_id);
           	$atmDB->WriteARMCEvent($armc["armc_id"], ARMC_EVENT_TYPE_MERGED, "Merged BR : ".$new_group_id);
         }

         if (sizeof($grouped)>0) {
            //echo ("<pre>Description : $group_description<br>".print_r($group_invoice, true)."</pre>");
            $group_invoice["armc_group_id"] = $new_group_id;
            $group_invoice["invoice_date"] = $group_invoice["transaction_date"];
            $atmDB->SetARMCGroupDescription($new_group_id, $group_description);
            SaveGroupInvoice($group_invoice);
         }
   	   $atmDB->SetARMCGroupDate($new_group_id, $atmDB->GetARMCGroupDate($new_group_id));
         SetARMCGroupAttr($new_group_id, "ARMC_STATUS_ID", $atmDB->GetARMCGroupStatusID($new_group_id));
         SetARMCGroupAttr($new_group_id, "AMOUNT", $atmDB->GetARMCGroupAmount($new_group_id));

         header("Location: ?e=".$encryption->Encrypt("action=refresh&armc_group_id=$new_group_id&refresh_type=group"));
      }else{
         //echo ($error_message);
         $o["search_status_id"] = array(ARMC_STATUS_EDIT, ARMC_STATUS_AM_APPROVAL, ARMC_STATUS_AE_APPROVAL, ARMC_STATUS_ACCT_APPROVAL, ARMC_STATUS_READY_FOR_INVOICE, ARMC_STATUS_STALLED);
         $filter = BuildSearchFilter($o);
         $header[0] = array("align"=>"center", "width"=>"2%", "sort"=>"no", "title"=>"", "field"=>"");
         $header[1] = array("align"=>"left", "width"=>"10%", "sort"=>"yes", "title"=>"Status", "field"=>"armc_status_description");
         $header[2] = array("align"=>"left", "width"=>"6%", "sort"=>"yes", "title"=>"BR #", "field"=>"armc_id");
         $header[3] = array("align"=>"left", "width"=>"6%", "sort"=>"yes", "title"=>"Study ID", "field"=>"study_id");
         $header[4] = array("align"=>"left", "width"=>"18%", "sort"=>"yes", "title"=>"Project Name", "field"=>"study_name");
         $header[5] = array("align"=>"left", "width"=>"18%", "sort"=>"yes", "title"=>"Partner Name", "field"=>"account_name");
         $header[6] = array("align"=>"center", "width"=>"8%", "sort"=>"yes", "title"=>"BR Date", "field"=>"armc_date");
         $header[7] = array("align"=>"right", "width"=>"6%", "sort"=>"yes", "title"=>"AE", "field"=>"account_executive");
         $header[8] = array("align"=>"right", "width"=>"6%", "sort"=>"yes", "title"=>"AM", "field"=>"account_manager");
         $header[9] = array("align"=>"right", "width"=>"10%", "sort"=>"yes", "title"=>"Amount", "field"=>"amount");

         $list_rst = $atmDB->GetARMCList($filter, "ORDER BY armc_id");
         while ($item = mysql_fetch_assoc($list_rst)) {
         	//print_r($item);
      	  $list[] = $item;
         }
         $smarty->assign("header", $header);
         $smarty->assign("list", $list);
         $smarty->assign("error", $error_message);
         $smarty->assign("meta", $o);
         DisplayHeader('Billing Reports', 'armc', $o["action"]);
         $smarty->display("app/atm/armc/vw_merge_list.tpl");
         DisplayFooter();
      }


   }
}

/**
* DisplayReport()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Feb 08 11:46:14 PST 2006
*/
function DisplayReport($o)
{

   global $smarty;
   $atmDB = new atmDB();

   $accounting_period = GetAccountingPeriod();

   $o['report_armc_date_start'] = CheckSessionVariable($o, 'report_armc_date_start', $accounting_period."-01");
   $o['report_armc_date_end'] = CheckSessionVariable($o, 'report_armc_date_end', $accounting_period."-".GetLastDateOfMonth(mktime(0, 0, 0, substr($accounting_period, 5), 10, substr($accounting_period, 0, 4))));
   $o['report_invoice_date_start'] = CheckSessionVariable($o, 'report_invoice_date_start', "");
   $o['report_invoice_date_end'] = CheckSessionVariable($o, 'report_invoice_date_end', "");
   if ($o['report_armc_date_start']!="" || $o['report_armc_date_end']!="") {
      $filter .= " AND armc_date BETWEEN '";
      if ($o['report_armc_date_start']!="") {
         $filter .= $o['report_armc_date_start'];
      }else{
         $filter .= "1900-01-01";
      }
      $filter .= "' AND '";
      if ($o['report_armc_date_end']!="") {
         $filter .= $o['report_armc_date_end'];
      }else{
         $filter .= date("Y-m-d");
      }
      $filter .= " 23:59:59'";
   }

   if ($o['report_invoice_date_start']!="" || $o['report_invoice_date_end']!="") {
      $filter .= " AND transaction_date BETWEEN '";
      if ($o['report_invoice_date_start']!="") {
         $filter .= $o['report_invoice_date_start'];
      }else{
         $filter .= "1900-01-01";
      }
      $filter .= "' AND '";
      if ($o['report_invoice_date_end']!="") {
         $filter .= $o['report_invoice_date_end'];
      }else{
         $filter .= date("Y-m-d");
      }
      $filter .= " 23:59:59'";
   }


   switch ($o["by"]) {
   case "account" : {
      $armc_rst = $atmDB->GetARMCList($filter, "ORDER BY account_id, armc_date");
      $summary_index = -1;
      $old_id = -1;
      while ($armc = mysql_fetch_assoc($armc_rst)) {
         if ($armc["account_id"]!=$old_id) {
            $summary_index++;
            $old_id = $armc["account_id"];
            $list["summary"][$summary_index] = array("id"=>$armc["account_id"], "description"=>$armc["account_name"]);
         }

         if (IsCreditMemo($armc["armc_type_id"]))
            $armc["amount"] = 0-$armc["amount"];

         $list["summary"][$summary_index]["total"][ARMC_STATUS_STALLED] += ($armc["armc_status_id"]==ARMC_STATUS_STALLED?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_EDIT] += ($armc["armc_status_id"]==ARMC_STATUS_EDIT?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_AM_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AM_APPROVAL?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_AE_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AE_APPROVAL?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_ACCT_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_ACCT_APPROVAL?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_READY_FOR_INVOICE] += ($armc["armc_status_id"]==ARMC_STATUS_READY_FOR_INVOICE?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_INVOICED] += (IsInvoiced($armc["armc_status_id"])?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][0] += $armc["amount"];

         $list["totals"][ARMC_STATUS_STALLED] += ($armc["armc_status_id"]==ARMC_STATUS_STALLED?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_EDIT] += ($armc["armc_status_id"]==ARMC_STATUS_EDIT?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_AM_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AM_APPROVAL?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_AE_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AE_APPROVAL?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_ACCT_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_ACCT_APPROVAL?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_READY_FOR_INVOICE] += ($armc["armc_status_id"]==ARMC_STATUS_READY_FOR_INVOICE?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_INVOICED] += (IsInvoiced($armc["armc_status_id"])?$armc["amount"]:0);
         $list["totals"][0] += $armc["amount"];

         $detail["detail_id"] = $armc["account_id"];
         $detail["description"] = $armc["armc_type_prefix"]."-".$armc["armc_id"]." (".substr($armc["armc_date"], 0, 10).")";

         $detail["amount"][ARMC_STATUS_STALLED] = ($armc["armc_status_id"]==ARMC_STATUS_STALLED?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_EDIT] = ($armc["armc_status_id"]==ARMC_STATUS_EDIT?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_AM_APPROVAL] = ($armc["armc_status_id"]==ARMC_STATUS_AM_APPROVAL?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_AE_APPROVAL] = ($armc["armc_status_id"]==ARMC_STATUS_AE_APPROVAL?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_ACCT_APPROVAL] = ($armc["armc_status_id"]==ARMC_STATUS_ACCT_APPROVAL?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_READY_FOR_INVOICE] = ($armc["armc_status_id"]==ARMC_STATUS_READY_FOR_INVOICE?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_INVOICED] = (IsInvoiced($armc["armc_status_id"])?$armc["amount"]:0);
         $detail["amount"][0] = $armc["amount"];

         //print_r($detail);
         $list["details"][$summary_index][] = $detail;

      }
      //print_r($list);
      $smarty->assign("list", $list);
      $smarty->assign("title", "Billing Reports by Account");
      $smarty->assign("meta", $o);

      if (isset($o["excel"])&&($o["excel"]==1)) {
         header('Content-type: application/vnd.ms-excel');
         header("Content-disposition: attachment; filename=\"billing_reports_by_account_".date('Ymd').".xls\"");
         $smarty->display("app/atm/armc/rp_report.tpl");
      }else{
         DisplayHeader('Billing Reports', 'armc', 'display_report_by_account');
         $smarty->display("app/atm/armc/vw_report.tpl");
         DisplayFooter();
      }
   }break;

   case "region" : {

      $armc_rst = $atmDB->GetARMCList($filter, "ORDER BY region_description, country_description");
      $summary_index = -1;
      $detail_index = -1;
      $old_detail_id = -1;
      $old_id = -1;
      while ($armc = mysql_fetch_assoc($armc_rst)) {
         if ($armc["country_code"]!=$old_detail_id) {
            $list["details"][$summary_index][$detail_index] = $detail;
            $detail["amount"] = array(0, 0, 0, 0, 0, 0, 0);
            $old_detail_id = $armc["country_code"];
            $detail_index++;
         }

         if ($armc["region_id"]!=$old_id) {
            $summary_index++;
            $detail_index = 0;
            $old_detail_id = $armc["country_code"];
            $old_id = $armc["region_id"];
            $list["summary"][$summary_index] = array("id"=>$armc["region_id"], "description"=>$armc["region_description"]);
         }

         if (IsCreditMemo($armc["armc_type_id"]))
            $armc["amount"] = 0-$armc["amount"];

         $list["summary"][$summary_index]["total"][ARMC_STATUS_STALLED] += ($armc["armc_status_id"]==ARMC_STATUS_STALLED?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_EDIT] += ($armc["armc_status_id"]==ARMC_STATUS_EDIT?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_AM_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AM_APPROVAL?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_AE_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AE_APPROVAL?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_ACCT_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_ACCT_APPROVAL?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_READY_FOR_INVOICE] += ($armc["armc_status_id"]==ARMC_STATUS_READY_FOR_INVOICE?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_INVOICED] += (IsInvoiced($armc["armc_status_id"])?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][0] += $armc["amount"];

         $list["totals"][ARMC_STATUS_STALLED] += ($armc["armc_status_id"]==ARMC_STATUS_STALLED?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_EDIT] += ($armc["armc_status_id"]==ARMC_STATUS_EDIT?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_AM_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AM_APPROVAL?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_AE_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AE_APPROVAL?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_ACCT_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_ACCT_APPROVAL?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_READY_FOR_INVOICE] += ($armc["armc_status_id"]==ARMC_STATUS_READY_FOR_INVOICE?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_INVOICED] += (IsInvoiced($armc["armc_status_id"])?$armc["amount"]:0);
         $list["totals"][0] += $armc["amount"];

         $detail["amount"][ARMC_STATUS_STALLED] += ($armc["armc_status_id"]==ARMC_STATUS_STALLED?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_EDIT] += ($armc["armc_status_id"]==ARMC_STATUS_EDIT?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_AM_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AM_APPROVAL?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_AE_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AE_APPROVAL?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_ACCT_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_ACCT_APPROVAL?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_READY_FOR_INVOICE] += ($armc["armc_status_id"]==ARMC_STATUS_READY_FOR_INVOICE?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_INVOICED] += (IsInvoiced($armc["armc_status_id"])?$armc["amount"]:0);
         $detail["amount"][0] += $armc["amount"];

         $detail["detail_id"] = $armc["region_id"];
         $detail["description"] = $armc["country_description"];

      }
      $list["details"][$summary_index][$detail_index] = $detail;
      $smarty->assign("list", $list);
      $smarty->assign("title", "Billing Reports by Region");
      $smarty->assign("meta", $o);

      if (isset($o["excel"])&&($o["excel"]==1)) {
         header('Content-type: application/vnd.ms-excel');
         header("Content-disposition: attachment; filename=\"billing_reports_by_region_".date('Ymd').".xls\"");
         $smarty->display("app/atm/armc/rp_report.tpl");
      }else{
         DisplayHeader('Billing Reports', 'armc', 'display_report_by_region');
         $smarty->display("app/atm/armc/vw_report.tpl");
         DisplayFooter();
      }
   }break;

   case "ae" : {

      $armc_rst = $atmDB->GetARMCList($filter, "ORDER BY account_executive, account_name");
      $summary_index = -1;
      $detail_index = -1;
      $old_detail_id = -1;
      $old_id = -1;
      while ($armc = mysql_fetch_assoc($armc_rst)) {
         if ($armc["account_id"]!=$old_detail_id) {
            $list["details"][$summary_index][$detail_index] = $detail;
            $detail["amount"] = array(0, 0, 0, 0, 0, 0, 0);
            $old_detail_id = $armc["account_id"];
            $detail_index++;
         }

         if ($armc["account_executive_id"]!=$old_id) {
            $summary_index++;
            $detail_index = 0;
            $old_detail_id = $armc["account_id"];
            $old_id = $armc["account_executive_id"];
            $list["summary"][$summary_index] = array("id"=>$armc["account_executive_id"], "description"=>$armc["account_executive"]);
         }

         if (IsCreditMemo($armc["armc_type_id"]))
            $armc["amount"] = 0-$armc["amount"];

         $list["summary"][$summary_index]["total"][ARMC_STATUS_STALLED] += ($armc["armc_status_id"]==ARMC_STATUS_STALLED?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_EDIT] += ($armc["armc_status_id"]==ARMC_STATUS_EDIT?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_AM_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AM_APPROVAL?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_AE_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AE_APPROVAL?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_ACCT_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_ACCT_APPROVAL?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_READY_FOR_INVOICE] += ($armc["armc_status_id"]==ARMC_STATUS_READY_FOR_INVOICE?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_INVOICED] += (IsInvoiced($armc["armc_status_id"])?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][0] += $armc["amount"];

         $list["totals"][ARMC_STATUS_STALLED] += ($armc["armc_status_id"]==ARMC_STATUS_STALLED?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_EDIT] += ($armc["armc_status_id"]==ARMC_STATUS_EDIT?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_AM_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AM_APPROVAL?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_AE_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AE_APPROVAL?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_ACCT_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_ACCT_APPROVAL?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_READY_FOR_INVOICE] += ($armc["armc_status_id"]==ARMC_STATUS_READY_FOR_INVOICE?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_INVOICED] += (IsInvoiced($armc["armc_status_id"])?$armc["amount"]:0);
         $list["totals"][0] += $armc["amount"];

         $detail["amount"][ARMC_STATUS_STALLED] += ($armc["armc_status_id"]==ARMC_STATUS_STALLED?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_EDIT] += ($armc["armc_status_id"]==ARMC_STATUS_EDIT?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_AM_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AM_APPROVAL?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_AE_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AE_APPROVAL?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_ACCT_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_ACCT_APPROVAL?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_READY_FOR_INVOICE] += ($armc["armc_status_id"]==ARMC_STATUS_READY_FOR_INVOICE?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_INVOICED] += (IsInvoiced($armc["armc_status_id"])?$armc["amount"]:0);
         $detail["amount"][0] += $armc["amount"];

         $detail["detail_id"] = $armc["account_executive_id"];
         $detail["description"] = $armc["account_name"];

      }
      $list["details"][$summary_index][$detail_index] = $detail;
      $smarty->assign("list", $list);
      $smarty->assign("title", "Billing Reports by Account Executive");
      $smarty->assign("meta", $o);

      if (isset($o["excel"])&&($o["excel"]==1)) {
         header('Content-type: application/vnd.ms-excel');
         header("Content-disposition: attachment; filename=\"billing_reports_by_ae_".date('Ymd').".xls\"");
         $smarty->display("app/atm/armc/rp_report.tpl");
      }else{
         DisplayHeader('Billing Reports', 'armc', 'display_report_by_ae');
         $smarty->display("app/atm/armc/vw_report.tpl");
         DisplayFooter();
      }
   }break;

   case "am" : {

      $armc_rst = $atmDB->GetARMCList($filter, "ORDER BY account_manager, account_name");
      $summary_index = -1;
      $detail_index = -1;
      $old_detail_id = -1;
      $old_id = -1;
      while ($armc = mysql_fetch_assoc($armc_rst)) {
         if ($armc["account_id"]!=$old_detail_id) {
            $list["details"][$summary_index][$detail_index] = $detail;
            $detail["amount"] = array(0, 0, 0, 0, 0, 0, 0);
            $old_detail_id = $armc["account_id"];
            $detail_index++;
         }

         if ($armc["account_manager_id"]!=$old_id) {
            $summary_index++;
            $detail_index = 0;
            $old_detail_id = $armc["account_id"];
            $old_id = $armc["account_manager_id"];
            $list["summary"][$summary_index] = array("id"=>$armc["account_manager_id"], "description"=>$armc["account_manager"]);
         }

         if (IsCreditMemo($armc["armc_type_id"]))
            $armc["amount"] = 0-$armc["amount"];

         $list["summary"][$summary_index]["total"][ARMC_STATUS_STALLED] += ($armc["armc_status_id"]==ARMC_STATUS_STALLED?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_EDIT] += ($armc["armc_status_id"]==ARMC_STATUS_EDIT?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_AM_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AM_APPROVAL?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_AE_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AE_APPROVAL?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_ACCT_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_ACCT_APPROVAL?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_READY_FOR_INVOICE] += ($armc["armc_status_id"]==ARMC_STATUS_READY_FOR_INVOICE?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][ARMC_STATUS_INVOICED] += (IsInvoiced($armc["armc_status_id"])?$armc["amount"]:0);
         $list["summary"][$summary_index]["total"][0] += $armc["amount"];

         $list["totals"][ARMC_STATUS_STALLED] += ($armc["armc_status_id"]==ARMC_STATUS_STALLED?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_EDIT] += ($armc["armc_status_id"]==ARMC_STATUS_EDIT?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_AM_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AM_APPROVAL?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_AE_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AE_APPROVAL?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_ACCT_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_ACCT_APPROVAL?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_READY_FOR_INVOICE] += ($armc["armc_status_id"]==ARMC_STATUS_READY_FOR_INVOICE?$armc["amount"]:0);
         $list["totals"][ARMC_STATUS_INVOICED] += (IsInvoiced($armc["armc_status_id"])?$armc["amount"]:0);
         $list["totals"][0] += $armc["amount"];

         $detail["amount"][ARMC_STATUS_STALLED] += ($armc["armc_status_id"]==ARMC_STATUS_STALLED?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_EDIT] += ($armc["armc_status_id"]==ARMC_STATUS_EDIT?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_AM_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AM_APPROVAL?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_AE_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_AE_APPROVAL?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_ACCT_APPROVAL] += ($armc["armc_status_id"]==ARMC_STATUS_ACCT_APPROVAL?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_READY_FOR_INVOICE] += ($armc["armc_status_id"]==ARMC_STATUS_READY_FOR_INVOICE?$armc["amount"]:0);
         $detail["amount"][ARMC_STATUS_INVOICED] += (IsInvoiced($armc["armc_status_id"])?$armc["amount"]:0);
         $detail["amount"][0] += $armc["amount"];

         $detail["detail_id"] = $armc["account_manager_id"];
         $detail["description"] = $armc["account_name"];

      }
      $list["details"][$summary_index][$detail_index] = $detail;
      $smarty->assign("list", $list);
      $smarty->assign("title", "Billing Reports by Account Manager");
      $smarty->assign("meta", $o);

      if (isset($o["excel"])&&($o["excel"]==1)) {
         header('Content-type: application/vnd.ms-excel');
         header("Content-disposition: attachment; filename=\"billing_reports_by_ae_".date('Ymd').".xls\"");
         $smarty->display("app/atm/armc/rp_report.tpl");
      }else{
         DisplayHeader('Billing Reports', 'armc', 'display_report_by_am');
         $smarty->display("app/atm/armc/vw_report.tpl");
         DisplayFooter();
      }
   }break;

   }
}

/**
* AddComment()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 17 14:06:56 PST 2006
*/
function AddComment($o)
{
   global $smarty;
   $atmDB = new atmDB();
   $types_result = $atmDB->GetARMCCommentTypes('A');
   while ($type = mysql_fetch_assoc($types_result)) {
      $types[$type["armc_comment_type_id"]] = $type["armc_comment_type_description"];
   }

   $lines_rst = $atmDB->GetARMCLines($o["armc_id"]);
   while ($line = mysql_fetch_assoc($lines_rst)) {
      $lines[$line["armc_budget_line_item_id"]] = $line["armc_budget_line_item_description"];
   }
   $smarty->assign("armc_lines", $lines);
   $smarty->assign("armc_comment_types", $types);
   $smarty->assign("meta", $o);
   DisplayHeader('Billing Reports', 'armc', $o["action"]);
   $smarty->display("app/atm/armc/vw_add_comment.tpl");
   DisplayFooter();

}

/**
* NewARMCComment()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 17 14:34:01 PST 2006
*/
function NewARMCComment($o)
{
   global $encryption;
   $atmDB = new atmDB();
//   echo ("<pre>");
//   print_r($o);
//   echo ("</pre>");
   $comment_type_description = $atmDB->GetARMCCommentTypeDetails($o["armc_comment_type_id"]);
   $comment_type_description = $comment_type_description["armc_comment_type_description"];
   if ($o["armc_comment_type_id"]>=100) {
      $atmDB->InsertARMCLineComment($o["armc_budget_line_item_id"], $o["armc_comment_type_id"], $o["comment"]);
      $lines_rst = $atmDB->GetARMCLines($o["armc_id"]);
      while ($line = mysql_fetch_assoc($lines_rst)) {
      	if ($line["armc_budget_line_item_id"]==$o["armc_budget_line_item_id"])
      	  $line_description = $line["armc_budget_line_item_description"];
      }
      $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_COMMENTED_LINE, "Comment type: $comment_type_description, Line item: $line_description, Comment:".$o["comment"]);
   }else{
      $atmDB->InsertARMCComment($o["armc_id"], $o["armc_comment_type_id"], $o["comment"]);
      $atmDB->WriteARMCEvent($o["armc_id"], ARMC_EVENT_TYPE_COMMENTED, "Comment type: $comment_type_description, Comment:".$o["comment"]);
   }

   header("Location: ?e=".$encryption->Encrypt("action=display_armc_details&armc_id=".$o["armc_id"]."&dont_log_view=1"));
}

function GetExportInfo(&$invs, &$cms, &$errors, &$email_invoices) {
   
   $atmDB = new atmDB();
   
   $accounting_period = GetAccountingPeriod();

   if (date("Y-m-d")<$accounting_period."-28") {
      $month_end_filter = " AND (aa_month_end.armc_attr_value !=1 OR aa_month_end.armc_attr_value IS NULL)";
   }

   $armc_rst = $atmDB->GetARMCList(" AND a.armc_status_id = '".ARMC_STATUS_READY_FOR_INVOICE."' $month_end_filter", " ORDER BY armc_type_id, grouped, armc_id");

   while ($armc = mysql_fetch_assoc($armc_rst)) {
      if ($armc["grouped"]==="true") {
         if (IsCreditMemo($armc["armc_type_id"])) {
            $cm = GetGroupCMExportInfo($armc["armc_id"]);
            if ($cm["error_code"]==0) {
               $cms[] = $cm["cm"];
            }else{
               UpdateARMCGroupFailedExport($armc["armc_id"], $cm["error_message"]);
               $errors[] = array("message"=>$cm["error_message"], "value"=>$armc["armc_type_prefix"]."-".$armc["armc_id"]);
               $email_invoices[] = Array("success"=>0, "armc_id"=>$armc["armc_type_prefix"]."-".$armc["armc_id"], "account_name"=>$armc['account_id']." : ".$armc['account_name'], "type"=>$armc["armc_type_description"], "invoice"=>"Failure");
            }
         }else{
            $inv = GetGroupInvoiceExportInfo($armc["armc_id"]);
            if ($inv["error_code"]==0) {
               $invs[] = $inv["invoice"];
            }else{
               UpdateARMCGroupFailedExport($armc["armc_id"], $inv["error_message"]);
               $errors[] = array("message"=>$inv["error_message"], "value"=>$armc["armc_type_prefix"]."-".$armc["armc_id"]);
               $email_invoices[] = Array("success"=>0, "armc_id"=>$armc["armc_type_prefix"]."-".$armc["armc_id"], "account_name"=>$armc['account_id']." : ".$armc['account_name'], "type"=>$armc["armc_type_description"], "invoice"=>"Failure");
            }
         }
      }else{
         if (IsCreditMemo($armc["armc_type_id"])) {
            $cm = GetCMExportInfo($armc["armc_id"]);
            if ($cm["error_code"]==0) {
               $cms[] = $cm["cm"];
            }else{
               UpdateARMCFailedExport($armc["armc_id"], $cm["error_message"]);
               $errors[] = array("message"=>$cm["error_message"], "value"=>$armc["armc_type_prefix"]."-".$armc["armc_id"]);
               $email_invoices[] = Array("success"=>0, "armc_id"=>$armc["armc_type_prefix"]."-".$armc["armc_id"], "account_name"=>$armc['account_id']." : ".$armc['account_name'], "type"=>$armc["armc_type_description"], "invoice"=>"Failure");
            }
         } else {
            $inv = GetInvoiceExportInfo($armc["armc_id"]);
            if ($inv["error_code"]==0) {
               $invs[] = $inv["invoice"];
            }else{
               UpdateARMCFailedExport($armc["armc_id"], $inv["error_message"]);
               $errors[] = array("message"=>$inv["error_message"], "value"=>$armc["armc_type_prefix"]."-".$armc["armc_id"]);
               $email_invoices[] = Array("success"=>0, "armc_id"=>$armc["armc_type_prefix"]."-".$armc["armc_id"], "account_name"=>$armc['account_id']." : ".$armc['account_name'], "type"=>$armc["armc_type_description"], "invoice"=>"Failure");
            }
         }
      }
   }
}

function PullInvoiceInformation($invoices, &$email_invoices) {
   
   $oraDB = new oracleDB();
   $batch_id = $oraDB->GetLastBatchID();
   $oraDB->GetBatchTransactions($batch_id);
            
   while ($id = $oraDB->fetch_assoc()) {
      $trx_ids[$id['INTERFACE_HEADER_ATTRIBUTE2']] = $id['CUSTOMER_TRX_ID'];
      $trx_nums[$id['INTERFACE_HEADER_ATTRIBUTE2']] = $id['TRX_NUMBER'];
   }

   foreach($invoices as $index=>$i) {
      if (isset($trx_ids[$i["armc_id"]])) {
         $invoices[$index]["invoice"]["customer_trx_id"] = $trx_ids[$i["armc_id"]];
         $invoices[$index]["invoice"]["transaction_number"] = $trx_nums[$i["armc_id"]];

         $inv_success++;

         if (isset($invoices[$index]["armc_group_id"]) && ($invoices[$index]["armc_group_id"]!=0))
            UpdateARMCGroup($invoices[$index]);
         else
            UpdateARMC($invoices[$index]);

         $email_invoices[] = Array("success"=>1, "armc_id"=>$i["type"]["armc_type_prefix"]."-".$i["armc_id"], "account_name"=>$i['account']['account_id']." : ".$i['account']['account_name'], "type"=>$i["type"]["armc_type_description"], "invoice"=>$invoices[$index]["invoice"]['transaction_number']);
      }else{
         $email_invoices[] = Array("success"=>0, "armc_id"=>$i["type"]["armc_type_prefix"]."-".$i["armc_id"], "account_name"=>$i['account']['account_id']." : ".$i['account']['account_name'], "type"=>$i["type"]["armc_type_description"], "invoice"=>"Failure");
      }
   }
}

function ExportInvoices($invs, &$errors, &$email_invoices) {
   try {
      $oraDB = new oracleDB();
      $oraDB->GetSystemParams();
      if (!isset($oraDB->_settings['ORG_ID']) or 
          !isset($oraDB->_settings['BATCH_SOURCE_ID']) or 
          !isset($oraDB->_settings['USER_ID']) or 
          !isset($oraDB->_settings['APPLICATION_ID']) or
          !isset($oraDB->_settings['RESPONSIBILITY_ID'])) 
      {  
         throw new Hb_Util_Db_Ora_Exception_OracleSystemErrorException("Unable to retrieve Oracle Settings");
      }
   }  catch (Hb_Util_Db_Ora_Exception_OracleException $e) {
      $errors[] = array("message" => $e->getMessage(), "value" => "");
      
      $oracle_exception_log = Hb_Util_Log_Logger::GetInstance("oracle_exception");
      $oracle_exception_log->LogException($e);
      
      return;
   }

   if (!empty($invs)) {
      foreach ($invs AS $inv) {
         $invoices = array($inv);

         try  {
            $var = $oraDB->NewCreateInvoices($invoices);
                     
            if ($var['x_return_status'] !== 'S') {  
               $oraDB->rollback();
               
               throw new Hb_Util_Db_Ora_Exception_OracleException("CreateInvoices returned with status : ".$var['x_return_status']." and ".$var['x_msg_count']." message(s)\n".$var['x_msg_data']);
            }
            
            if ($oraDB->GetTrxErrorsCount() != 0) {
               $oraDB->GetTrxErrors(); 
               $oraDB->rollback();
                  
               $err = "";
               while ($e = $oraDB->fetch_assoc()) {
                  $err .= $e["ERROR_MESSAGE"].", ".$e["INVALID_VALUE"]."<br/>";
                  $errors[] = Array("message"=>$e['ERROR_MESSAGE'], "value"=>$e['INVALID_VALUE']);
               }
               foreach($invoices AS $index=>$i) {
                  if (isset($invoices[$index]["armc_group_id"]) && ($invoices[$index]["armc_group_id"]!=0)) {
                     UpdateARMCGroupFailedExport($invoices[$index]["armc_group_id"], $err);
                  } else {
                     UpdateARMCFailedExport($invoices[$index]["armc_id"], $err);
                  }
               }
            } else {
            
               $oraDB->commit();

               PullInvoiceInformation($invoices, $email_invoices);
            }
            
         } catch (Hb_Util_Db_Ora_Exception_OracleException $e) {
            
            $values = array();

            foreach ($invoices as $index=>$failed_armc) {
               if (isset($invoices[$index]["armc_group_id"]) && ($invoices[$index]["armc_group_id"]!=0)) {
                  UpdateARMCGroupFailedExport($invoices[$index]["armc_group_id"], $e->getMessage());
               } else {
                  UpdateARMCFailedExport($invoices[$index]["armc_id"], $e->getMessage());
               }

               $values[] = $failed_armc["type"]["armc_type_prefix"]."-".$failed_armc["armc_id"];
               $email_invoices[] = Array("success"=>0, "armc_id"=>$failed_armc["type"]["armc_type_prefix"]."-".$failed_armc["armc_id"], "account_name"=>$failed_armc['account']['account_id']." : ".$failed_armc['account']['account_name'], "type"=>$failed_armc["type"]["armc_type_description"], "invoice"=>"Failure");
            }
            $errors[] = Array("message"=>$e->getMessage(), "value"=>explode(", ", $values));
                                    
            $oracle_exception_log = Hb_Util_Log_Logger::GetInstance("oracle_exception");
            $oracle_exception_log->LogException($e);
         }
      }
   }
}

function ExportCMs($cms, &$errors, &$email_invoices) {
   try {
      $oraDB = new oracleDB();
      $oraDB->GetSystemParams();
      if (!isset($oraDB->_settings['ORG_ID']) or 
          !isset($oraDB->_settings['BATCH_SOURCE_ID']) or 
          !isset($oraDB->_settings['USER_ID']) or 
          !isset($oraDB->_settings['APPLICATION_ID']) or
          !isset($oraDB->_settings['RESPONSIBILITY_ID'])) 
      {  
         throw new Hb_Util_Db_Ora_Exception_OracleSystemErrorException("Unable to retrieve Oracle Settings");
      }
   }  catch (Hb_Util_Db_Ora_Exception_OracleException $e) {
      $errors[] = array("message" => $e->getMessage(), "value" => "");
      
      $oracle_exception_log = Hb_Util_Log_Logger::GetInstance("oracle_exception");
      $oracle_exception_log->LogException($e);
      
      return;
   }
   
   if (!empty($cms)) {
      foreach ($cms AS $index=>$cm) {
         try {
            $var = $oraDB->NewCreateCM($cm);
            if ($var["x_return_status"]!=='S') {
               $oraDB->rollback();
               
               throw new Hb_Util_Db_Ora_Exception_OracleException("CreateCM returned with status : ".$var['x_return_status']." and ".$var['x_msg_count']." message(s)<br/>".$var['x_msg_data']);
            }
         
            $oraDB->commit();

            $cms[$index]["invoice"]["customer_trx_id"] = $var["x_customer_trx_id"];
            $cms[$index]["invoice"]["transaction_date"] = date("Y-m-d", strtotime($var["x_trx_date"]));
            $cms[$index]["invoice"]["transaction_number"] = $var["x_trx_number"];

            if (isset($cms[$index]["armc_group_id"]) && ($cms[$index]["armc_group_id"]!=0)) {
               UpdateARMCGroup($cms[$index]);
            }else{
               UpdateARMC($cms[$index]);
            }

            $email_invoices[] = Array("success"=>1, "armc_id"=>$cms[$index]["type"]["armc_type_prefix"]."-".$cms[$index]["armc_id"], "account_name"=>$cms[$index]["account"]["account_id"]." : ".$cms[$index]["account"]["account_name"], "type"=>$cms[$index]["type"]["armc_type_description"], "invoice"=>$cms[$index]["invoice"]["transaction_number"]);
         
         } catch (Hb_Util_Db_Ora_Exception_OracleException $e) {
            if (isset($cms[$index]["armc_group_id"]) && ($cms[$index]["armc_group_id"]!=0))
               UpdateARMCGroupFailedExport($cms[$index]["armc_group_id"], $e->getMessage());
            else
               UpdateARMCFailedExport($cms[$index]["armc_id"], $e->getMessage());

            $email_invoices[] = Array("success"=>0, "armc_id"=>$cm["type"]["armc_type_prefix"]."-".$cm["armc_id"], "account_name"=>$cm['account']['account_id']." : ".$cm['account']['account_name'], "type"=>$cm["type"]["armc_type_description"], "invoice"=>"Failure");

            $errors[] = Array("message"=>$e->getMessage(), "value"=>$cm["type"]["armc_type_prefix"]."-".$cm["armc_id"]);
                                    
            $oracle_exception_log = Hb_Util_Log_Logger::GetInstance("oracle_exception");
            $oracle_exception_log->LogException($e);
         }
      }
   }   
}

function RunBatchExport(&$email_invoices, &$errors, &$counts) {

	$commDB = new commonDB();
   $atmDB = new atmDB();
   
   if (!$commDB->GetCronServiceStatus("BLRPT_ORAEXPORT")) {
      $errors[] = array("message"=>"Oracle Batch Export Stopped", "value"=>"");
      return;
   }
   
   $lock = $atmDB->GetARMCSetting("ORA_EXPORT_LOCK");
      
   if ($lock["armc_setting_value"]) {
      $errors[] = array("message"=>"Oracle Batch Export Lock Found", "value"=>"");
      return;
   }
 
   $atmDB->SetARMCSetting("ORA_EXPORT_LOCK", 1);

   $info = GetExportInfo($invs, $cms, $errors, $email_invoices);
   
   $inv_count = sizeof($invs);
   $cm_count = sizeof($cms);
   
   ExportInvoices($invs, $errors, $email_invoices);

   $inv_success = 0;
   foreach($email_invoices AS $inv) {
      if ($inv["success"]) $inv_success++;
   }
   
   ExportCMs($cms, $errors, $email_invoices);
   $cm_success = 0;
   foreach($email_invoices AS $cm) {
      if ($cm["success"]) $cm_success++;
   }
   //Because $email_invoices has both invoices and CMs
   $cm_success = $cm_success - $inv_success;
   
   $atmDB->SetARMCSetting("ORA_EXPORT_LOCK", 0);
   
   $counts = array("cm"=>array("total"=>$cm_count, "success"=>$cm_success, "fail"=>$cm_count-$cm_success), "inv"=>array("total"=>$inv_count, "success"=>$inv_success, "fail"=>$inv_count-$inv_success));
   
}
/**
* HandleBatchExport()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Feb 22 09:34:30 PST 2006
*/
function HandleBatchExport()
{
   $email_invoices = array();
   $errors = array();
   $counts = array();
   
   RunBatchExport($email_invoices, $errors, $counts);

   SendBatchExportResults($email_invoices, $errors, $counts);

}

/**
* TestAmounts()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 15 09:32:05 PST 2006
*/
function TestAmounts()
{
   $atmDB = new atmDB();
   $msg = "Here are the results of Test Amounts : \n";
   $query = "";
   $found=0;
   $q = "SELECT a.armc_id, SUM(abli.actual_quantity*abli.actual_rate) AS lines_amount, aa.armc_attr_value AS attr_amount"
   ." FROM armc AS a LEFT JOIN armc_budget_line_item AS abli ON abli.armc_id = a.armc_id AND abli.status='A' LEFT JOIN armc_attr AS aa ON aa.armc_id=a.armc_id AND aa.armc_attr_name='AMOUNT'"
   ." GROUP BY a.armc_id";
   $rst = $atmDB->executeQuery($q);
   while ($armc = mysql_fetch_assoc($rst)) {
      if ($armc["lines_amount"]=="")
         $armc["lines_amount"]=0;
      if ($armc["lines_amount"]!=$armc["attr_amount"]) {
         //echo ("ARMC ".$armc["armc_id"]." out of sync. Lines amount $".$armc["lines_amount"].", Attr amount $".$armc["attr_amount"]."\n");
         $msg .= "ARMC ".$armc["armc_id"]." out of sync. Lines amount $".$armc["lines_amount"].", Attr amount $".$armc["attr_amount"]."\n";
         $query .= "UPDATE armc_attr SET armc_attr_value='".$armc["lines_amount"]."' WHERE armc_id='".$armc["armc_id"]."' AND armc_attr_name='AMOUNT';\n";
         $found++;
      }
   }

   $q = "SELECT ag.armc_group_id, SUM(abli.actual_quantity*abli.actual_rate) AS lines_amount, aga.armc_group_attr_value AS attr_amount"
   ." FROM armc_group AS ag LEFT JOIN armc AS a ON a.armc_group_id = ag.armc_group_id AND a.status='A' LEFT JOIN armc_budget_line_item AS abli ON abli.armc_id = a.armc_id AND abli.status='A' LEFT JOIN armc_group_attr AS aga ON aga.armc_group_id=ag.armc_group_id AND aga.armc_group_attr_name='AMOUNT'"
   ." GROUP BY a.armc_group_id";
   $rst = $atmDB->executeQuery($q);
   while ($armc = mysql_fetch_assoc($rst)) {
      if ($armc["lines_amount"]=="")
         $armc["lines_amount"]=0;
      if ($armc["lines_amount"]!=$armc["attr_amount"]) {
         //echo ("Merged ARMC ".$armc["armc_group_id"]." out of sync. Lines amount $".$armc["lines_amount"].", Attr amount $".$armc["attr_amount"]."\n");
         $msg .= "Merged ARMC ".$armc["armc_group_id"]." out of sync. Lines amount $".$armc["lines_amount"].", Attr amount $".$armc["attr_amount"]."\n";
         $query .= "UPDATE armc_group_attr SET armc_group_attr_value='".$armc["lines_amount"]."' WHERE armc_group_id='".$armc["armc_group_id"]."' AND armc_group_attr_name='AMOUNT';\n";
         $found++;
      }

   }

   if ($found==0) {
      $msg .= "No Billing Reports found to be out of sync. GOOD JOB !!!\n";
   }

   $msg .= "\nHere are the results of Test Group Status : \n";
   $found = 0;
   $q = "SELECT ag.armc_group_id, MIN(a.armc_status_id) AS lines_status_id, aga.armc_group_attr_value AS attr_status_id "
   ."FROM armc_group AS ag LEFT JOIN armc AS a ON a.armc_group_id = ag.armc_group_id LEFT JOIN armc_group_attr AS aga ON aga.armc_group_id = ag.armc_group_id AND aga.armc_group_attr_name='ARMC_STATUS_ID' GROUP BY ag.armc_group_id";
   $rst = $atmDB->executeQuery($q);
   while ($armc = mysql_fetch_assoc($rst)) {
      if ($armc["lines_status_id"]!=$armc["attr_status_id"]) {
         $msg .= "Merged ARMC ".$armc["armc_group_id"]." out of sync. Lines min status (".$armc["lines_status_id"]."), Attr status (".$armc["attr_status_id"].")\n";
         $query .= "UPDATE armc_group_attr SET armc_group_attr_value='".$armc["lines_status_id"]."' WHERE armc_group_id='".$armc["armc_group_id"]."' AND armc_group_attr_name='ARMC_STATUS_ID';\n";
         $found++;
      }
   }

   if ($found==0) {
      $msg .= "No Billing Reports found to be out of sync. GOOD JOB !!!\n";
   }

   $msg .= "\nHere are the results of Test Group Date : \n";
   $found = 0;
   $q = "SELECT ag.armc_group_id, ag.armc_group_date, MIN(a.armc_date) AS lines_date FROM armc_group AS ag LEFT JOIN armc AS a ON a.armc_group_id = ag.armc_group_id GROUP BY a.armc_group_id";
   $rst = $atmDB->executeQuery($q);
   while ($armc = mysql_fetch_assoc($rst)) {
      if ($armc["armc_group_date"]!=$armc["lines_date"]) {
         $msg .= "Merged ARMC ".$armc["armc_group_id"]." out of sync. Lines min date (".$armc["lines_date"]."), group date (".$armc["armc_group_date"].")\n";
         $query .= "UPDATE armc_group SET armc_group_date='".$armc["lines_date"]."' WHERE armc_group_id='".$armc["armc_group_id"]."';\n";
         $found++;
      }
   }
   if ($found==0) {
      $msg .= "No Billing Reports found to be out of sync. GOOD JOB !!!\n";
   }

   if ($query != "")
      $msg .= "\n\nQuery to fix these sync failures : \n".$query."\n\n";
   mail("jvonica@gmi-mr.com", "Billing Reports Sync Tests Results", $msg, "From: hb-deamon@hb.gmi-mr.com");
}

/**
* GetBannerReport()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 10 10:51:46 PST 2006
*/
function GetBannerReport()
{
   $smarty = $GLOBALS["smarty"];
   $atmDB = new atmDB();

   $userDB = new userDB();
   //$userDB->getDefaultTimeZone()
   $lines = array(
      0=>array(
         "description"=>"Past 24 hours",
         "start_date"=>date("Y-m-d H:i:s", time()-24*60*60),
         "end_date"=>date("Y-m-d H:i:s"),
         "amounts"=>array(0, 0, 0, 0, 0, 0, 0),
         "counts"=>array(0, 0, 0, 0, 0, 0, 0)),
      1=>array(
         "description"=>"Past 7 days",
         "start_date"=>date("Y-m-d H:i:s", time()-7*24*60*60),
         "end_date"=>date("Y-m-d H:i:s"),
         "amounts"=>array(0, 0, 0, 0, 0, 0, 0),
         "counts"=>array(0, 0, 0, 0, 0, 0, 0)),
      2=>array(
         "description"=>"Past 30 days",
         "start_date"=>date("Y-m-d H:i:s", time()-30*24*60*60),
         "end_date"=>date("Y-m-d H:i:s"),
         "amounts"=>array(0, 0, 0, 0, 0, 0, 0),
         "counts"=>array(0, 0, 0, 0, 0, 0, 0)),
      3=>array(
         "description"=>"Month to date",
         "start_date"=>date("Y-m-01"),
         "end_date"=>date("Y-m-d H:i:s"),
         "amounts"=>array(0, 0, 0, 0, 0, 0, 0),
         "counts"=>array(0, 0, 0, 0, 0, 0, 0)));

   $indices = array(
      ARMC_EVENT_TYPE_STATUS_EDIT=>0,
      ARMC_EVENT_TYPE_STALLED=>1,
      ARMC_EVENT_TYPE_STATUS_AM_APPROVAL=>2,
      ARMC_EVENT_TYPE_STATUS_AE_APPROVAL=>3,
      ARMC_EVENT_TYPE_STATUS_ACCT_APPROVAL=>4,
      ARMC_EVENT_TYPE_STATUS_READY=>5,
      ARMC_EVENT_TYPE_INVOICED=>6,
      ARMC_EVENT_TYPE_INVOICED_MANUALLY=>6);
   $columns = array("Data Entry", "Stalled", "AM approval", "AE approval", "ACCT approval", "Ready Export", "Invoiced");
   $ret = "";
   $event_type_array = array();
   foreach($indices AS $event_type=>$column) {
      $event_type_array[] = $event_type;
   }
   //print_r($event_type_array);
   foreach($lines AS $index=>$line) {
      $armc_rst = $atmDB->GetEvents($line["start_date"], $line["end_date"], $event_type_array);
      while ($armc = mysql_fetch_assoc($armc_rst)) {
         //echo ("ARMC ID : ".$armc["armc_id"]."(".$armc["armc_type_id"]."), Event Type : ".$armc["armc_event_type_id"]." Amount : $".number_format($armc["amount"], 2)."<br>");
         if (IsCreditMemo($armc["armc_type_id"]))
            $amount = 0-$armc["amount"];
         else
            $amount = (int)$armc["amount"];
         $lines[$index]["amounts"][$indices[$armc["armc_event_type_id"]]] += $amount;
         $lines[$index]["counts"][$indices[$armc["armc_event_type_id"]]] += 1;
      }
   }
   //print_r($lines);
//   return array("columns"=>$columns, "lines"=>$lines);
   $smarty->assign("columns", $columns);
   $smarty->assign("lines", $lines);
   return $smarty->fetch("app/atm/armc/vw_stat_banner.tpl");
}

/**
* DisplayBannerReport()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 10 10:50:29 PST 2006
*/
function DisplayBannerReport()
{
   $html = GetBannerReport();
   DisplayHeader("Billing Reports", "armc", $o["action"]);
   echo $html;
   DisplayFooter();
}

/**
* GetFlashReport()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 10 13:59:53 PST 2006
*/
function GetFlashReport($o)
{
   global $smarty;
   $atmDB = new atmDB();

   $accounting_period = GetAccountingPeriod();

   if (!isset($o["role_id"])) $o["role_id"] = ROLE_PRIMARY_ACCT_EXEC;
   $roles[ROLE_PRIMARY_ACCT_EXEC] = "By AE's Location";
   $roles[ROLE_PRIMARY_ACCT_MGR] = "By AM's Location";
   $start_date = $accounting_period."-01";

   $end_date = $accounting_period."-".GetLastDateOfMonth(mktime(0, 0, 0, substr($accounting_period, 5), 10, substr($accounting_period, 0, 4)))." 23:59:59";
   $rst = $atmDB->GetARMCListByOffice($start_date, $end_date, $o["role_id"]);
   while ($armc = mysql_fetch_assoc($rst)) {
      //echo "Status : ".$armc["armc_status_description"].", Type : ".$armc["armc_type_description"].", Location : ".$armc["location_description"].", Region : ".$armc["region_description"].", Amount : $".number_format($armc["amount"], 2)."<br>";
      //echo ("<pre>"); var_dump($armc); echo ("</pre>");
      if (IsCreditMemo($armc["armc_type_id"])) {
         $amount = 0-$armc["amount"];
      }else{
         $amount = (int)$armc["amount"];
      }
      if (IsInvoiced($armc["armc_status_id"])) {
//         echo ("MANUAL TRANSFORMED INTO INVOICED<br>");
         $armc["armc_status_id"]=ARMC_STATUS_INVOICED;
      }
      //echo ("<pre>"); print_r($armc); echo ("</pre>");
      $list[$armc["armc_status_id"]]["description"] = $armc["armc_status_description"];
      $list[$armc["armc_status_id"]]["regions"][$armc["region_id"]]["description"] = $armc["region_description"];
      $list[$armc["armc_status_id"]]["regions"][$armc["region_id"]]["locations"][$armc["location_id"]]["description"] = $armc["location_description"];
      $list[$armc["armc_status_id"]]["regions"][$armc["region_id"]]["locations"][$armc["location_id"]]["amount"] += $amount;
   }

   //echo("<pre>".print_r($list, true)."</pre>");

   $status_count=0;
   foreach ($list AS $status_id=>$status) {
      $final_list[$status_count]["description"] = $status["description"];
      $region_count=0;
      foreach($status["regions"] AS $region_id=>$region) {
         $final_list[$status_count]["regions"][$region_count]["description"] = $region["description"];
         $location_count = 0;
         foreach($region["locations"] AS $location_id=>$location) {
            if ($location["amount"]==NULL)
               $location["amount"] = 0;
            $final_list[$status_count]["regions"][$region_count]["locations"][$location_count]["description"] = $location["description"];
            $final_list[$status_count]["regions"][$region_count]["locations"][$location_count]["amount"] = $location["amount"];
            $final_list[$status_count]["regions"][$region_count]["amount"] += $location["amount"];
            $final_list[$status_count]["amount"] += $location["amount"];
            $total += $location["amount"];
            $location_count++;
         }
         $region_count++;
      }
      $status_count++;
   }
   //echo("<pre>".print_r($final_list, true)."</pre>");
   $smarty->assign("roles", $roles);
   $smarty->assign("meta", $o);
   $smarty->assign("list", $final_list);
   $smarty->assign("total", $total);
   $smarty->assign("dates", array("start"=>$start_date, "end"=>substr($end_date, 0, 10)));
   return $smarty->fetch("app/atm/armc/vw_flash_report.tpl");
}

/**
* DisplayFlashReport()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 10 13:58:47 PST 2006
*/
function DisplayFlashReport()
{
   $html = GetFlashReport();
   DisplayHeader("Billing Reports", "armc", $o["action"]);
   echo $html;
   DisplayFooter();
}

/**
* SendDelayedApprovalReport()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Apr 17 13:41:38 PDT 2006
*/
function SendDelayedApprovalReport()
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

   $h = HBRPCCall('atm', 'GetAttrValues', array("attr_name"=>"delay"));
   //print_r($h);

   $hours_array = array();
   foreach ($h["data"] AS $id=>$data) {
      $hours_array[$data["hours"]] = $data["description"];
   }
   //$hours_array = array(1=>"One hour", 4=>"Four hours", 12=>"Twelve hours", 24=>"One day (24 hours)", 48=>"Two days (48 hours)", 72=>"Three days (72 hours)", 96=>"Four days (96 hours)");
   foreach ($hours_array AS $hours=>$desc) {
      $list[$hours] = array();
      $total[$hours] = 0;
      $count[$hours] = 0;
   }
   $am_list = array();
   $am_count = array();
   $am_total = array();
   $atmDB = new atmDB();
   $rst = $atmDB->GetARMCDelayedAMApproval();
   while ($armc = mysql_fetch_assoc($rst)) {
      $armc["hours"] = date("U")-date("U", mktime(substr($armc["waiting_since"], 11, 2), substr($armc["waiting_since"], 14, 2), substr($armc["waiting_since"], 17, 2), substr($armc["waiting_since"], 5, 2), substr($armc["waiting_since"], 8, 2), substr($armc["waiting_since"], 0, 4)));
      $armc["hours"] = floor($armc["hours"] / (60*60));
      if (IsCreditMemo($armc["armc_type_id"])) {
         $is_credit_memo = 1;
         $armc["display_amount"] = "<font color=Red>-$".number_format($armc["amount"], 2)."</font>";
      }else{
         $is_credit_memo = 0;
         $armc["display_amount"] = "$".number_format($armc["amount"], 2);
      }
      foreach ($hours_array AS $hours=>$desc) {
         //echo ($armc["armc_id"]." - ".$armc["hours"]."<br>");
         if ($armc["hours"]>=$hours) {
            if ($is_credit_memo) {
               $total[$hours] -= $armc["amount"];
            }else{
               $total[$hours] += $armc["amount"];
            }
            //if ($armc["hours"]>672) $armc["hours"]=">672";
            //echo ("Adding ".$armc["armc_id"]." to list $hours<br>");
            $count[$hours]++;
            $list[$hours][] = $armc;
         }
      }
      if ($armc["hours"]>=48) {
         if ($is_credit_memo) {
            $am_total[$armc["account_manager_id"]] -= $armc["amount"];
         }else{
            $am_total[$armc["account_manager_id"]] += $armc["amount"];
         }
         $am_name[$armc["account_manager_id"]] = $armc["account_manager"];
         $am_count[$armc["account_manager_id"]]++;
         $am_list[$armc["account_manager_id"]][] = $armc;
      }
   }
//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");
   foreach ($hours_array AS $hours=>$desc) {
      //print_r($list[$hours]);
//      $smarty->assign("hours", $desc);
      $smarty->assign("list", $list[$hours]);
      $smarty->assign("total", "$".number_format($total[$hours], 2));
      //$smarty->display("app/atm/armc/email_delayed_approval.tpl");
      $message = $smarty->fetch("app/atm/armc/email_delayed_approval.tpl");
      $queue_message_attrs = array(
         "message_type_id"=>ATM_MESSAGE_TYPE_FLASH_DELAYED_AM_APPROVAL,
         "rcpt"=>array(0),
         "msg"=>array(
            "subject"=>"Billing Reports Waiting for AM approval for more than {$desc}",
            "body"=>$message),
         "attr"=>array(
            "count"=>$count[$hours],
            "amount"=>$total[$hours],
            "delay"=>$hours));
      $received_by = HBRPCCall('com', 'QueueMessage', $queue_message_attrs);
      //print_r($received_by);
   }

   foreach ($am_list AS $id=>$list) {
      $smarty->assign("list", $am_list[$id]);
      $smarty->assign("total", "$".number_format($am_total[$id], 2));
      //$smarty->display("app/atm/armc/email_delayed_approval.tpl");
      $message = $smarty->fetch("app/atm/armc/email_delayed_approval.tpl");
      $queue_message_attrs = array(
         "message_type_id"=>ATM_MESSAGE_TYPE_DELAYED_AM_APPROVAL,
         "rcpt"=>array(0),
         "msg"=>array(
            "subject"=>"Billing Reports Waiting for ".$am_name[$id]."'s approval for more than 48 hours",
            "body"=>$message),
         "attr"=>array(
            "count"=>$am_count[$id],
            "amount"=>$am_total[$id],
            "primary_am_id"=>$id));
      $received_by = HBRPCCall('com', 'QueueMessage', $queue_message_attrs);
      //print_r($received_by);
   }
}

/**
* SendDelayedStalledReport()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jul 10 16:07:03 PDT 2006
*/
function SendDelayedStalledReport()
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

   $h = HBRPCCall('atm', 'GetAttrValues', array("attr_name"=>"delay"));
   //print_r($h);

   $hours_array = array();
   foreach ($h["data"] AS $id=>$data) {
      $hours_array[$data["hours"]] = $data["description"];
   }
   //$hours_array = array(1=>"One hour", 4=>"Four hours", 12=>"Twelve hours", 24=>"One day (24 hours)", 48=>"Two days (48 hours)", 72=>"Three days (72 hours)", 96=>"Four days (96 hours)");
   foreach ($hours_array AS $hours=>$desc) {
      $list[$hours] = array();
      $total[$hours] = 0;
      $count[$hours] = 0;
   }
   $am_list = array();
   $am_count = array();
   $am_total = array();
   $atmDB = new atmDB();
   $rst = $atmDB->GetARMCDelayedStalled();
   while ($armc = mysql_fetch_assoc($rst)) {
      $armc["hours"] = date("U")-date("U", mktime(substr($armc["waiting_since"], 11, 2), substr($armc["waiting_since"], 14, 2), substr($armc["waiting_since"], 17, 2), substr($armc["waiting_since"], 5, 2), substr($armc["waiting_since"], 8, 2), substr($armc["waiting_since"], 0, 4)));
      $armc["hours"] = floor($armc["hours"] / (60*60));
      if (IsCreditMemo($armc["armc_type_id"])) {
         $is_credit_memo = 1;
         $armc["display_amount"] = "<font color=Red>-$".number_format($armc["amount"], 2)."</font>";
      }else{
         $is_credit_memo = 0;
         $armc["display_amount"] = "$".number_format($armc["amount"], 2);
      }
      foreach ($hours_array AS $hours=>$desc) {
         //echo ($armc["armc_id"]." - ".$armc["hours"]."<br>");
         if ($armc["hours"]>=$hours) {
            if ($is_credit_memo) {
               $total[$hours] -= $armc["amount"];
            }else{
               $total[$hours] += $armc["amount"];
            }
            //if ($armc["hours"]>672) $armc["hours"]=">672";
            //echo ("Adding ".$armc["armc_id"]." to list $hours<br>");
            $count[$hours]++;
            $list[$hours][] = $armc;
         }
      }
      if ($armc["hours"]>=336) {
         if ($is_credit_memo) {
            $am_total[$armc["account_manager_id"]] -= $armc["amount"];
         }else{
            $am_total[$armc["account_manager_id"]] += $armc["amount"];
         }
         $am_name[$armc["account_manager_id"]] = $armc["account_manager"];
         $am_count[$armc["account_manager_id"]]++;
         $am_list[$armc["account_manager_id"]][] = $armc;
      }
   }
//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");
   foreach ($hours_array AS $hours=>$desc) {
      //print_r($list[$hours]);
//      $smarty->assign("hours", $desc);
      $smarty->assign("list", $list[$hours]);
      $smarty->assign("total", "$".number_format($total[$hours], 2));
      //$smarty->display("app/atm/armc/email_delayed_approval.tpl");
      $message = $smarty->fetch("app/atm/armc/email_delayed_approval.tpl");
      $queue_message_attrs = array(
         "message_type_id"=>ATM_MESSAGE_TYPE_FLASH_DELAYED_STALLED,
         "rcpt"=>array(0),
         "msg"=>array(
            "subject"=>"Billing Reports Stalled for more than {$desc}",
            "body"=>$message),
         "attr"=>array(
            "count"=>$count[$hours],
            "amount"=>$total[$hours],
            "delay"=>$hours));
      $received_by = HBRPCCall('com', 'QueueMessage', $queue_message_attrs);
      //print_r($received_by);
   }

   foreach ($am_list AS $id=>$list) {
      $smarty->assign("list", $am_list[$id]);
      $smarty->assign("total", "$".number_format($am_total[$id], 2));
      //$smarty->display("app/atm/armc/email_delayed_approval.tpl");
      $message = $smarty->fetch("app/atm/armc/email_delayed_approval.tpl");
      $queue_message_attrs = array(
         "message_type_id"=>ATM_MESSAGE_TYPE_DELAYED_STALLED,
         "rcpt"=>array(0),
         "msg"=>array(
            "subject"=>"Billing Reports Stalled for more than two weeks for AM ".$am_name[$id],
            "body"=>$message),
         "attr"=>array(
            "count"=>$am_count[$id],
            "amount"=>$am_total[$id],
            "primary_am_id"=>$id));
      $received_by = HBRPCCall('com', 'QueueMessage', $queue_message_attrs);
      //print_r($received_by);
   }
}

/**
* SendInvoicedByAMReport()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jul 10 17:06:07 PDT 2006
*/
function SendInvoicedByAMReport()
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

   $h = HBRPCCall('atm', 'GetAttrValues', array("attr_name"=>"delay"));
   //print_r($h);

   $hours_array = array();
   foreach ($h["data"] AS $id=>$data) {
      $hours_array[$data["hours"]] = $data["description"];
   }
   //$hours_array = array(1=>"One hour", 4=>"Four hours", 12=>"Twelve hours", 24=>"One day (24 hours)", 48=>"Two days (48 hours)", 72=>"Three days (72 hours)", 96=>"Four days (96 hours)");
   foreach ($hours_array AS $hours=>$desc) {
      $list[$hours] = array();
      $total[$hours] = array();
      $count[$hours] = array();
   }
//   $am_list = array();
//   $am_count = array();
//   $am_total = array();
   $atmDB = new atmDB();
   $rst = $atmDB->GetARMCDelayedInvoiced();
   while ($armc = mysql_fetch_assoc($rst)) {
      $am_names[$armc["account_manager_id"]] = $armc["account_manager"];

      $armc["hours"] = date("U")-date("U", mktime(substr($armc["waiting_since"], 11, 2), substr($armc["waiting_since"], 14, 2), substr($armc["waiting_since"], 17, 2), substr($armc["waiting_since"], 5, 2), substr($armc["waiting_since"], 8, 2), substr($armc["waiting_since"], 0, 4)));
      $armc["hours"] = floor($armc["hours"] / (60*60));
      if (IsCreditMemo($armc["armc_type_id"])) {
         $is_credit_memo = 1;
         $armc["display_amount"] = "<font color=Red>-$".number_format($armc["amount"], 2)."</font>";
      }else{
         $is_credit_memo = 0;
         $armc["display_amount"] = "$".number_format($armc["amount"], 2);
      }
      foreach ($hours_array AS $hours=>$desc) {
         //echo ($armc["armc_id"]." - ".$armc["hours"]."<br>");
         if ($armc["hours"]<=$hours) {
            if ($is_credit_memo) {
               $total[$hours][$armc["account_manager_id"]] -= $armc["amount"];
            }else{
               $total[$hours][$armc["account_manager_id"]] += $armc["amount"];
            }
            //if ($armc["hours"]>672) $armc["hours"]=">672";
            //echo ("Adding ".$armc["armc_id"]." to list $hours<br>");
            $count[$hours][$armc["account_manager_id"]]++;
            $list[$hours][$armc["account_manager_id"]][] = $armc;
         }
      }
//      if ($armc["hours"]>=336) {
//         if ($is_credit_memo) {
//            $am_total[$armc["account_manager_id"]] -= $armc["amount"];
//         }else{
//            $am_total[$armc["account_manager_id"]] += $armc["amount"];
//         }
//         $am_name[$armc["account_manager_id"]] = $armc["account_manager"];
//         $am_count[$armc["account_manager_id"]]++;
//         $am_list[$armc["account_manager_id"]][] = $armc;
//      }
   }
//   @runkit_function_remove("HBRPC_GetPortlet");
//   @runkit_function_remove("HBRPC_GetAttrValues");
   foreach ($hours_array AS $hours=>$desc) {
      foreach ($am_names AS $id=>$name) {
         //print_r($list[$hours]);
//          $smarty->assign("hours", $desc);
         $smarty->assign("list", $list[$hours][$id]);
         $smarty->assign("total", "$".number_format($total[$hours][$id], 2));
         //$smarty->display("app/atm/armc/email_delayed_approval.tpl");
         $message = $smarty->fetch("app/atm/armc/email_delayed_approval.tpl");
         $queue_message_attrs = array(
            "message_type_id"=>ATM_MESSAGE_TYPE_INVOICED_BY_AM,
            "rcpt"=>array(0),
            "msg"=>array(
               "subject"=>"Billing Reports invoiced for {$name} in the last {$desc}",
               "body"=>$message),
            "attr"=>array(
               "count"=>$count[$hours],
               "amount"=>$total[$hours],
               "delay"=>$hours,
               "primary_am_id"=>$id));
         $received_by = HBRPCCall('com', 'QueueMessage', $queue_message_attrs);

      }
      //print_r($received_by);
   }

//   foreach ($am_list AS $id=>$list) {
//      $smarty->assign("list", $am_list[$id]);
//      $smarty->assign("total", "$".number_format($am_total[$id], 2));
//      //$smarty->display("app/atm/armc/email_delayed_approval.tpl");
//      $message = $smarty->fetch("app/atm/armc/email_delayed_approval.tpl");
//      $queue_message_attrs = array(
//         "message_type_id"=>ATM_MESSAGE_TYPE_DELAYED_STALLED,
//         "rcpt"=>array(0),
//         "msg"=>array(
//            "subject"=>"Billing Reports Stalled approval for more than two weeks for AM ".$am_name[$id],
//            "body"=>$message),
//         "attr"=>array(
//            "count"=>$am_count[$id],
//            "amount"=>$am_total[$id],
//            "primary_am_id"=>$id));
//      $received_by = HBRPCCall('com', 'QueueMessage', $queue_message_attrs);
//      //print_r($received_by);
//   }
}

/**
* DisplayARMCSummaryReports()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 24 14:28:00 PST 2006
*/
function DisplayARMCSummaryReports($o)
{
   global $smarty;
   $banner = GetBannerReport();
   $flash = GetFlashReport($o);
   DisplayHeader("Billing Reports", "armc", $o["action"]);
   $smarty->assign("reports", array("banner"=>$banner, "flash"=>$flash));
   $smarty->display("app/atm/armc/vw_summary_reports.tpl");
   DisplayFooter();
}

///**
//* DisplayHeader()
//*
//* @param
//* @param -
//* @return
//* @throws
//* @access
//* @global
//* @since  - Thu Nov 03 11:00:37 PST 2005
//*/
//function DisplayHeader()
//{
//   global $cfg,$userRights,$version,$build; //we need globals here for our configs and version stuff
//   //variables
//   $moduleName = "Billing Reports";
//   $moduleReference = "armc";
//
//   DisplayHeader($moduleName, $moduleReference);
//   //displaying page
//   //require_once($cfg['base_dir'].'/include/header.inc.php');
//   //require_once($cfg['base_dir'].'/include/rpt_functions.inc.php');
//
//   return true;
//
//}

///**
//* DisplayFooter()
//*
//* @param
//* @param -
//* @return
//* @throws
//* @access
//* @global
//* @since  - Thu Nov 03 11:01:45 PST 2005
//*/
//function DisplayFooter()
//{
//   global $cfg;
//   //displaying page
//   //require_once($cfg['base_dir'].'/include/footer.inc.php');
//
//   return true;
//}


/**
* MigrateGLAccounts()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 23 12:55:22 PST 2006
*/
function MigrateGLAccounts()
{
   $atmDB = new atmDB();
   echo ("Affected rows : ".$atmDB->rows);
}

/**
* MigrateBudgetDef()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 23 13:02:20 PST 2006
*/
function MigrateBudgetDef($o)
{
   $atmDB = new atmDB();
   $q ="INSERT INTO armc_budget_line_item_def (
      `armc_budget_line_item_def_id`, `atm_gl_account_id`, `armc_budget_line_item_description`, `item_number`, `default_rate`, `default_quantity`, `created_by`, `created_date`, `modified_by`, `modified_date`, `status`)

      SELECT `id`, `gl_account_id`, `desc`, CONCAT(`list_main`, ':', `list_sub`), `price`, '1', '10816', NOW(), NULL, NULL, `status` FROM br_budget_def";
   if (isset($o["min_id"]))
      $min_id = $o["min_id"];
   if (isset($o["max_id"]))
      $max_id = $o["max_id"];
   if (isset($min_id)&&(isset($max_id)))
      $q .= " WHERE `id` BETWEEN '$min_id' AND '$max_id'";
   $atmDB->executeQuery($q);
   echo ("Affected rows : ".$atmDB->rows);
}


/**
* GetRealStatus()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 23 16:23:06 PST 2006
*/
function GetRealStatus($br_id)
{
   $atmDB = new atmDB();
   $q = "SELECT head_am_flag, head_ae_flag, head_acct_flag, study_am_flag, study_ae_flag, study_acct_flag FROM br_main WHERE br_id = '$br_id'";
   $br = mysql_fetch_assoc($atmDB->executeQuery($q));
   $qq = "SELECT min(flag_AM) AS line_am_flag, min(flag_AE) AS line_ae_flag, min(flag_INV) AS line_acct_flag FROM br_budget WHERE br_id = '$br_id' GROUP BY br_id";
   $line = mysql_fetch_assoc($atmDB->executeQuery($qq));

   if ($br["head_am_flag"]==0 || $br["study_am_flag"]==0 || $line["line_am_flag"]==0) {
      return 51;
   }elseif($br["head_ae_flag"]==0 || $br["study_ae_flag"]==0 || $line["line_ae_flag"]==0){
      return 53;
   }elseif($br["head_acct_flag"]==0 || $br["study_acct_flag"]==0 || $line["line_acct_flag"]==0) {
      return 53;
   }else{
      return 10;
   }

}
/**
* ARMCMigrate()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 23 13:39:51 PST 2006
*/
function ARMCMigrate($o=array())
{
   $atmDB = new atmDB();
   $status_correspondence = array(
      0=>ARMC_STATUS_EDIT,
      1=>ARMC_STATUS_EDIT,
      4=>ARMC_STATUS_AM_APPROVAL,
      5=>ARMC_STATUS_INVOICED_MANUALLY,
      10=>ARMC_STATUS_READY_FOR_INVOICE,
      20=>ARMC_STATUS_INVOICED,
      51=>ARMC_STATUS_AM_APPROVAL,
      52=>ARMC_STATUS_AE_APPROVAL,
      53=>ARMC_STATUS_ACCT_APPROVAL,
      100=>ARMC_STATUS_STALLED);


   $type_correspondence = array(
      0=>ARMC_TYPE_BR_STUDY,
      1=>ARMC_TYPE_CM_STUDY,
      2=>ARMC_TYPE_RT_STUDY,
      3=>ARMC_TYPE_BR_SB,
      10=>ARMC_TYPE_CM_SB,
      100=>ARMC_TYPE_BR_LI,
      210=>ARMC_TYPE_RT_LI,
      230=>ARMC_TYPE_RT_SB,
      290=>ARMC_TYPE_RT_TR,
      500=>ARMC_TYPE_BR_OT,
      900=>ARMC_TYPE_BR_TR);

   if (isset($o["limit"]))
      $limit = $o["limit"];
   else
      $limit = 0;
   if (isset($o["max_id"]))
      $max_id = $o["max_id"];
   else
      $max_id = 20000;
   $limit_query = "";
   if ($limit!=0)
      $limit_query = "LIMIT $limit";

   $q = "TRUNCATE TABLE armc_event";
   $atmDB->executeQuery($q);
   $q = "TRUNCATE TABLE armc_action";
   $atmDB->executeQuery($q);
   $q = "TRUNCATE TABLE armc_budget_line_item_action";
   $atmDB->executeQuery($q);

   $q = "TRUNCATE TABLE atm_gl_account";
   $atmDB->executeQuery($q);

   $q = "INSERT INTO atm_gl_account (
      `atm_gl_account_id`,
      `atm_gl_account_segment4`,
      `atm_gl_account_description`,
      `created_by`,
      `created_date`,
      `modified_by`,
      `modified_date`,
      `status`)
      SELECT `gl_account_id`, `gl_account_code`, `gl_account_description`, `created_by`, `created_date`, `modified_by`, `modified_date`, `status` FROM gl_account";
   $atmDB->executeQuery($q);

   echo ("atm_gl_account migrated : ".date("H:i:s")." !!!\n");

   $q = "TRUNCATE TABLE armc_budget_line_item_def";
   $atmDB->executeQuery($q);
   $q ="INSERT INTO armc_budget_line_item_def (
      `armc_budget_line_item_def_id`, `atm_gl_account_id`, `armc_budget_line_item_description`, `item_number`, `default_rate`, `default_quantity`, `created_by`, `created_date`, `modified_by`, `modified_date`, `status`)
      SELECT `id`, `gl_account_id`, `desc`, CONCAT(`list_main`, ':', `list_sub`), `price`, '1', '10816', NOW(), NULL, NULL, `status` FROM br_budget_def";
   $atmDB->executeQuery($q);

   echo ("atm_buget_line_item_def migrated : ".date("H:i:s")." !!!\n");

   $q = "TRUNCATE TABLE armc";
   $atmDB->executeQuery($q);
   $q = "TRUNCATE TABLE armc_user";
   $atmDB->executeQuery($q);
   $q = "SELECT * FROM br_main $limit_query";
   $br_main_rst = $atmDB->executeQuery($q);
   while ($br = mysql_fetch_assoc($br_main_rst)) {
      if ($br["br_status"]==4) {
         $new_status = $status_correspondence[GetRealStatus($br["br_id"])];
      }else{
         $new_status = $status_correspondence[$br["br_status"]];
      }

      if ($br["invoice_date"]=="0000-00-00")
         $invoice_date = 'NULL';
      else{
         $invoice_date = "'".$br["invoice_date"]."'";
      }

      $qq =
       "INSERT INTO armc (`armc_id`, `armc_status_id`, `armc_type_id`, `product_id`, `armc_group_id`, `transaction_date`, `created_by`, `created_date`, `status`) "
      ."VALUES ('".$br["br_id"]."', '".$new_status."', '".$type_correspondence[$br["br_type"]]."', '1', '".$br["merge_id"]."', ".$invoice_date.", '".$br["created_by"]."', '".$br["created_date"]."', 'A')";
      $atmDB->executeQuery($qq);

      $qq = "INSERT INTO `armc_user` (`armc_id`, `login`, `role_id`, `created_by`, `created_date`, `status`) "
      ." VALUES ('".$br["br_id"]."', '10875', '".ROLE_ACCOUNTING."', '10816', NOW(), 'A')";
      $atmDB->executeQuery($qq);
   }

   echo ("armc migrated : ".date("H:i:s")." !!!\n");

   $q = "TRUNCATE TABLE armc_budget_line_item";
   $atmDB->executeQuery($q);
   $qq = "INSERT INTO `armc_budget_line_item` (`armc_budget_line_item_id`, `armc_id`, `armc_budget_line_item_def_id`, `group_description`, `proposed_rate`, `proposed_quantity`, `actual_rate`, `actual_quantity`, `created_by`, `created_date`, `status`) "
   ." SELECT `br_line_id`, `br_id`, `budget_id`, `group_name`, `unit_cost`, `quantity`, `unit_cost`, `A_quantity`, '10816', NOW(), 'A' FROM `br_budget`";
   $atmDB->executeQuery($qq);

   echo ("armc_budget_line_item migrated : ".date("H:i:s")." !!!\n");

   $q = "TRUNCATE TABLE armc_budget_line_item_comment";
   $atmDB->executeQuery($q);
   $qq = "INSERT INTO `armc_budget_line_item_comment` (`armc_budget_line_item_id`, `armc_comment_type_id`, `comment`, `created_by`, `created_date`, `status`) "
   ." SELECT `br_line_id`, '".ARMC_COMMENT_TYPE_GENERAL_LINE."', `memo`, `user_id`, `datetime`, 'A' FROM `br_memo` WHERE `br_line_id`!=0";
   $atmDB->executeQuery($qq);

   echo ("armc_budget_line_item_comment migrated : ".date("H:i:s")." !!!\n");

   $q = "TRUNCATE TABLE armc_group";
   $atmDB->executeQuery($q);
   $qq = "INSERT INTO `armc_group` (`armc_group_id`, `created_by`, `created_date`, `status`) "
   ." SELECT `m_id`, `m_by`, `m_date`, 'A' FROM br_merge";
   $atmDB->executeQuery($qq);

   echo ("armc_group migrated : ".date("H:i:s")." !!!\n");

   $q = "TRUNCATE TABLE armc_attr";
   $atmDB->executeQuery($q);
   $q = "TRUNCATE TABLE armc_group_attr";
   $atmDB->executeQuery($q);

   $amounts_count=0;
   $q = "SELECT armc_id FROM armc";
   $rst = $atmDB->executeQuery($q);
   while ($armc = mysql_fetch_assoc($rst)) {
      $amount = $atmDB->GetARMCAmount($armc["armc_id"]);
      SetARMCAttr($armc["armc_id"], "AMOUNT", $amount);
      //echo ("Amount($amounts_count) : $amount\n");
      $amounts_count++;
   }

   $q = "SELECT armc_group_id FROM armc_group";
   $rst = $atmDB->executeQuery($q);
   while ($armc = mysql_fetch_assoc($rst)) {
      $amount = $atmDB->GetARMCGroupAmount($armc["armc_group_id"]);
      SetARMCGroupAttr($armc["armc_group_id"], "AMOUNT", $amount);
      //echo ("Amount($amounts_count) : $amount\n");
      $amounts_count++;
   }

   echo ("$amounts_count amounts migrated : ".date("H:i:s")." !!!\nStarting on armc_attr !!!\n");

   $q = "TRUNCATE TABLE armc_contact";
   $atmDB->executeQuery($q);
   $q = "SELECT * FROM br_attr ORDER BY `id` DESC, `key` ASC $limit_query";
   $br_attr_rst = $atmDB->executeQuery($q);
   while ($attr = mysql_fetch_assoc($br_attr_rst)) {
      $attr_count++;
      if (($attr_count % 10000) ==0) {
         echo ("Processed $attr_count attributes : ".date("H:i:s")." !!!\n");
      }
      switch (strtoupper($attr["key"])) {
         case "BLCONTACT" : {
            $qContact = "SELECT * FROM contacts WHERE contact_id = '".$attr["value"]."'";
            $contact_rst = $atmDB->executeQuery($qContact);
            if ($contact = mysql_fetch_assoc($contact_rst)) {
               $qq = "INSERT INTO `armc_contact` (`armc_id`, `armc_contact_type_id`, `contact_id`, `salutation`, `first_name`, `last_name`, `address_1`, `address_2`, `city`, `state`, `zip`, `country_code`, `phone`, `fax`, `email`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', '".ARMC_CONTACT_TYPE_BILLING."', '".$contact["contact_id"]."', '".$contact["salutation"]."', '".addslashes($contact["first_name"])."', '".addslashes($contact['last_name'])."', '".addslashes($contact["addr_1"])."', '".addslashes($contact["addr_2"])."', '".$contact["city"]."', '".$contact["state"]."', '".$contact["zip"]."', '".$contact["country"]."', '".$contact["phone_1"]."', '".$contact["fax"]."', '".$contact["email"]."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qq);
            }
         }break;
         case "PJCONTACT" : {
            $qContact = "SELECT * FROM contacts WHERE contact_id = '".$attr["value"]."'";
            $contact_rst = $atmDB->executeQuery($qContact);
            if ($contact = mysql_fetch_assoc($contact_rst)) {
               $qq = "INSERT INTO `armc_contact` (`armc_id`, `armc_contact_type_id`, `contact_id`, `salutation`, `first_name`, `last_name`, `address_1`, `address_2`, `city`, `state`, `zip`, `country_code`, `phone`, `fax`, `email`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', '".ARMC_CONTACT_TYPE_PROJECT."', '".$contact["contact_id"]."', '".$contact["salutation"]."', '".addslashes($contact["first_name"])."', '".addslashes($contact['last_name'])."', '".addslashes($contact["addr_1"])."', '".addslashes($contact["addr_2"])."', '".$contact["city"]."', '".$contact["state"]."', '".$contact["zip"]."', '".$contact["country"]."', '".$contact["phone_1"]."', '".$contact["fax"]."', '".$contact["email"]."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qq);
            }
         }break;
         case "CMAPPTO" : {
            $qq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
            ." VALUES ('".$attr["id"]."', 'CMAPPTO', '".$attr["value"]."', '10816', NOW(), 'A')";
            $atmDB->executeQuery($qq);

            $qq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
            ." VALUES ('".$attr["id"]."', 'CMAPPTO', '".$attr["value"]."', '10816', NOW(), 'A')";
            $atmDB->executeQuery($q);
         }break;

         case "GMIAE" : {
            $qq = "INSERT INTO `armc_user` (`armc_id`, `role_id`, `login`, `created_by`, `created_date`, `status`) "
            ." VALUES ('".$attr["id"]."', '".ROLE_PRIMARY_ACCT_EXEC."', '".$attr["value"]."', '10816', NOW(), 'A')";
            $atmDB->executeQuery($qq);
         }break;

         case "GMIAM" : {
            $qq = "INSERT INTO `armc_user` (`armc_id`, `role_id`, `login`, `created_by`, `created_date`, `status`) "
            ." VALUES ('".$attr["id"]."', '".ROLE_PRIMARY_ACCT_MGR."', '".$attr["value"]."', '10816', NOW(), 'A')";
            $atmDB->executeQuery($qq);
         }break;

         case "INVDATE" : {
            $date="";
            if(strlen($attr["value"]==8)) {
               $date = substr($attr["value"], 0, 4)."-".substr($attr["value"], 4, 2)."-".substr($attr["value"], 6, 2);
            }elseif(strlen($attr["value"]==10)){
               $date = $attr["value"];
            }
            if ($date!="") {
               $qq = "UPDATE `armc` SET `transaction_date` = '$date' WHERE `armc_id`='".$attr["id"]."'";
               $atmDB->executeQuery($qq);
            }
         }break;

         case "INVOICE" : {
            if (strlen($attr["value"]>0)) {
               $qq = "UPDATE `armc` SET `transaction_number` = '".$attr["value"]."' WHERE `armc_id`='".$attr["id"]."'";
               $atmDB->executeQuery($qq);
            }
         }break;

//         case "JOBNUMBER" : {
//            $qq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
//            ." VALUES ('".$attr["id"]."', 'JOBNUMBER', '".$attr["value"]."', '10816', NOW(), 'A')";
//            $atmDB->executeQuery($qq);
//         }break;

         case "PARTNERID" : {
            $qq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
            ." VALUES ('".$attr["id"]."', 'ACCOUNT_ID', '".$attr["value"]."', '10816', NOW(), 'A')";
            $atmDB->executeQuery($qq);

//            $qq = "SELECT account_name FROM account WHERE account_id = '".$attr["value"]."'";
//            $rst = $atmDB->executeQuery($qq);
//            if ($company_name = mysql_fetch_assoc($rst)) {
//               $qq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
//               ." VALUES ('".$attr["id"]."', 'ACCOUNT_NAME', '".$company_name["account_name"]."', '10816', NOW(), 'A')";
//               $atmDB->executeQuery($qq);
//            }

            $qq = "SELECT
               a.account_name,
               a.country_code,
               aa_month_end.account_attr_value AS is_month_end,
               aa_require_ae_approval.account_attr_value AS require_ae_approval,
               aa_email_invoice.account_attr_value AS email_invoice,
               aa_print_invoice.account_attr_value AS print_invoice,
               aa_po_required.account_attr_value AS po_required,
               aa_pm_required.account_attr_value AS pm_required,
               aa_job_required.account_attr_value AS job_required
            FROM account AS a
            LEFT JOIN account_attr AS aa_month_end ON aa_month_end.account_id = a.account_id AND aa_month_end.account_attr_name = 'ARMC_MONTH_END_BILLING'
            LEFT JOIN account_attr AS aa_require_ae_approval ON aa_require_ae_approval.account_id = a.account_id AND aa_require_ae_approval.account_attr_name = 'AMRC_AE_APPROVAL_REQUIRED'
            LEFT JOIN account_attr AS aa_email_invoice ON aa_email_invoice.account_id = a.account_id AND aa_email_invoice.account_attr_name = 'ARMC_EMAIL_INVOICE'
            LEFT JOIN account_attr AS aa_print_invoice ON aa_print_invoice.account_id = a.account_id AND aa_print_invoice.account_attr_name = 'ARMC_PRINT_INVOICE'
            LEFT JOIN account_attr AS aa_po_required ON aa_po_required.account_id = a.account_id AND aa_po_required.account_attr_name = 'ARMC_PO_REQUIRED'
            LEFT JOIN account_attr AS aa_pm_required ON aa_pm_required.account_id = a.account_id AND aa_pm_required.account_attr_name = 'ARMC_PM_REQUIRED'
            LEFT JOIN account_attr AS aa_job_required ON aa_job_required.account_id = a.account_id AND aa_job_required.account_attr_name = 'ARMC_JOB_REQUIRED'
            WHERE a.account_id = '".$attr["value"]."'";
            $account_rst = $atmDB->executeQuery($qq);
            if ($account = mysql_fetch_assoc($account_rst)) {
               $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', 'ACCOUNT_NAME', '".addslashes($account["account_name"])."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qqq);
               $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', 'ACCOUNT_COUNTRY_CODE', '".$account["country_code"]."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qqq);
               $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', 'ARMC_MONTH_END_BILLING', '".$account["is_month_end"]."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qqq);
               $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', 'ARMC_AE_APPROVAL_REQUIRED', '".$account["require_ae_approval"]."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qqq);
               $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', 'ARMC_EMAIL_INVOICE', '".$account["email_invoice"]."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qqq);
               $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', 'ARMC_PRINT_INVOICE', '".$account["print_invoice"]."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qqq);
               $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', 'ARMC_PO_REQUIRED', '".$account["po_required"]."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qqq);
               $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', 'ARMC_PM_REQUIRED', '".$account["pm_required"]."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qqq);
               $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', 'ARMC_JOB_REQUIRED', '".$account["job_required"]."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qqq);
            }
         }break;

//         case "PMNAME" : {
//            $qq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
//            ." VALUES ('".$attr["id"]."', 'PMNAME', '".$attr["value"]."', '10816', NOW(), 'A')";
//            $atmDB->executeQuery($qq);
//         }break;

//         case "STUDY_ID" : {
//            $qq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
//            ." VALUES ('".$attr["id"]."', 'STUDY_ID', '".$attr["value"]."', '10816', NOW(), 'A')";
//            $atmDB->executeQuery($qq);
//         }break;

         case "STUDYNAME" : {
            $qq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
            ." VALUES ('".$attr["id"]."', 'STUDY_NAME', '".addslashes($attr["value"])."', '10816', NOW(), 'A')";
            $atmDB->executeQuery($qq);
         }break;

         case "S_PONUM" : {
            $qq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
            ." VALUES ('".$attr["id"]."', 'PONUMBER', '".$attr["value"]."', '10816', NOW(), 'A')";
            $atmDB->executeQuery($qq);
         }break;

         case "TCOUNT" : {
            $qq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
            ." VALUES ('".$attr["id"]."', 'STUDY_COUNT', '".$attr["value"]."', '10816', NOW(), 'A')";
            $atmDB->executeQuery($qq);
         }break;

         case "STUDY_ID" : {
            $qq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
            ." VALUES ('".$attr["id"]."', 'STUDY_ID', '".$attr["value"]."', '10816', NOW(), 'A')";
            $atmDB->executeQuery($qq);

            $qq = "SELECT study_id, partner_id, study_name FROM study WHERE study_id = '".$attr["value"]."'";
            $study_rst = $atmDB->executeQuery($qq);
            if ($study = mysql_fetch_assoc($study_rst)) {
               $qq = "DELETE FROM armc_attr WHERE armc_id = '".$attr["id"]."' AND armc_attr_name = 'STUDY_NAME'";
               $atmDB->executeQuery($qq);
               $qq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', 'STUDY_NAME', '".addslashes($study["study_name"])."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qq);

               $qq = "DELETE FROM armc_attr WHERE armc_id = '".$attr["id"]."' AND armc_attr_name = 'ACCOUNT_ID'";
               $atmDB->executeQuery($qq);
               $qq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', 'ACCOUNT_ID', '".$study["partner_id"]."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qq);

               $qq = "SELECT
                  a.account_name,
                  a.country_code,
                  aa_month_end.account_attr_value AS is_month_end,
                  aa_require_ae_approval.account_attr_value AS require_ae_approval,
                  aa_email_invoice.account_attr_value AS email_invoice,
                  aa_print_invoice.account_attr_value AS print_invoice,
                  aa_po_required.account_attr_value AS po_required,
                  aa_pm_required.account_attr_value AS pm_required,
                  aa_job_required.account_attr_value AS job_required,
                  au_ae.user_id AS ae_login,
                  au_am.user_id AS am_login
               FROM account AS a
               LEFT JOIN account_attr AS aa_month_end ON aa_month_end.account_id = a.account_id AND aa_month_end.account_attr_name = 'ARMC_MONTH_END_BILLING'
               LEFT JOIN account_attr AS aa_require_ae_approval ON aa_require_ae_approval.account_id = a.account_id AND aa_require_ae_approval.account_attr_name = 'AMRC_AE_APPROVAL_REQUIRED'
               LEFT JOIN account_attr AS aa_email_invoice ON aa_email_invoice.account_id = a.account_id AND aa_email_invoice.account_attr_name = 'ARMC_EMAIL_INVOICE'
               LEFT JOIN account_attr AS aa_print_invoice ON aa_print_invoice.account_id = a.account_id AND aa_print_invoice.account_attr_name = 'ARMC_PRINT_INVOICE'
               LEFT JOIN account_attr AS aa_po_required ON aa_po_required.account_id = a.account_id AND aa_po_required.account_attr_name = 'ARMC_PO_REQUIRED'
               LEFT JOIN account_attr AS aa_pm_required ON aa_pm_required.account_id = a.account_id AND aa_pm_required.account_attr_name = 'ARMC_PM_REQUIRED'
               LEFT JOIN account_attr AS aa_job_required ON aa_job_required.account_id = a.account_id AND aa_job_required.account_attr_name = 'ARMC_JOB_REQUIRED'
               LEFT JOIN account_user AS au_ae ON au_ae.account_id = a.account_id AND au_ae.role_id = '4'
               LEFT JOIN account_user AS au_am ON au_am.account_id = a.account_id AND au_am.role_id = '1'
               WHERE a.account_id = '".$study["partner_id"]."'";
               $account_rst = $atmDB->executeQuery($qq);
               if ($account = mysql_fetch_assoc($account_rst)) {
                  $qqq = "DELETE FROM armc_attr WHERE armc_id = '".$attr["id"]."' AND armc_attr_name = 'ACCOUNT_NAME'";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ACCOUNT_NAME', '".addslashes($account["account_name"])."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
                  $qqq = "DELETE FROM armc_attr WHERE armc_id = '".$attr["id"]."' AND armc_attr_name = 'ACCOUNT_COUNTRY_CODE'";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ACCOUNT_COUNTRY_CODE', '".$account["country_code"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
                  $qqq = "DELETE FROM armc_attr WHERE armc_id = '".$attr["id"]."' AND armc_attr_name = 'ARMC_MONTH_END_BILLING'";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ARMC_MONTH_END_BILLING', '".$account["is_month_end"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
                  $qqq = "DELETE FROM armc_attr WHERE armc_id = '".$attr["id"]."' AND armc_attr_name = 'ARMC_AE_APPROVAL_REQUIRED'";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ARMC_AE_APPROVAL_REQUIRED', '".$account["require_ae_approval"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
                  $qqq = "DELETE FROM armc_attr WHERE armc_id = '".$attr["id"]."' AND armc_attr_name = 'ARMC_EMAIL_INVOICE'";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ARMC_EMAIL_INVOICE', '".$account["email_invoice"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
                  $qqq = "DELETE FROM armc_attr WHERE armc_id = '".$attr["id"]."' AND armc_attr_name = 'ARMC_PRINT_INVOICE'";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ARMC_PRINT_INVOICE', '".$account["print_invoice"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
                  $qqq = "DELETE FROM armc_attr WHERE armc_id = '".$attr["id"]."' AND armc_attr_name = 'ARMC_PO_REQUIRED'";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ARMC_PO_REQUIRED', '".$account["po_required"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
                  $qqq = "DELETE FROM armc_attr WHERE armc_id = '".$attr["id"]."' AND armc_attr_name = 'ARMC_PM_REQUIRED'";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ARMC_PM_REQUIRED', '".$account["pm_required"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
                  $qqq = "DELETE FROM armc_attr WHERE armc_id = '".$attr["id"]."' AND armc_attr_name = 'ARMC_JOB_REQUIRED'";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ARMC_JOB_REQUIRED', '".$account["job_required"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);

                  $qqq = "DELETE FROM `armc_user` WHERE `armc_id` = '".$attr["id"]."' AND `role_id`='4'";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_user` (`armc_id`, `role_id`, `login`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', '4', '".$account["ae_login"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);

                  $qqq = "DELETE FROM `armc_user` WHERE `armc_id` = '".$attr["id"]."' AND `role_id`='1'";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_user` (`armc_id`, `role_id`, `login`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', '1', '".$account["am_login"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
               }

//               $qq = "SELECT * FROM study_contact WHERE study_id = '".$study["study_id"]."'";
//               $contact_rst = $atmDB->executeQuery($qq);
//               while ($contact = mysql_fetch_assoc($contact_rst)) {
//                  if ($contact["study_contact_type_id"]==1) {
//                     $qq = "DELETE FROM `armc_contact` WHERE `armc_id`='".$attr["id"]."' AND `armc_contact_type_id`='".ARMC_CONTACT_TYPE_PROJECT."' AND `contact_id`='".$contact["contact_id"]."'";
//                     $atmDB->executeQuery($qq);
//
//                     $qq = "INSERT INTO `armc_contact` (`armc_id`, `armc_contact_type_id`, `contact_id`, `salutation`, `first_name`, `last_name`, `phone`, `fax`, `email`, `created_by`, `created_date`, `status`) "
//                     ." VALUES ('".$attr["id"]."', '".ARMC_CONTACT_TYPE_PROJECT."', '".$contact["contact_id"]."', '".$contact["salutation"]."', '".addslashes($contact["first_name"])."', '".addslashes($contact['last_name'])."', '".$contact["phone"]."', '".$contact["fax"]."', '".$contact["email"]."', '10816', NOW(), 'A')";
//                     $atmDB->executeQuery($qq);
//                  }elseif ($contact["study_contact_type_id"]==2) {
//                     $qq = "DELETE FROM `armc_contact` WHERE `armc_id`='".$attr["id"]."' AND `armc_contact_type_id`='".ARMC_CONTACT_TYPE_BILLING."' AND `contact_id`='".$contact["contact_id"]."'";
//                     $atmDB->executeQuery($qq);
//
//                     $qq = "INSERT INTO `armc_contact` (`armc_id`, `armc_contact_type_id`, `contact_id`, `salutation`, `first_name`, `last_name`, `phone`, `fax`, `email`, `created_by`, `created_date`, `status`) "
//                     ." VALUES ('".$attr["id"]."', '".ARMC_CONTACT_TYPE_BILLING."', '".$contact["contact_id"]."', '".$contact["salutation"]."', '".addslashes($contact["first_name"])."', '".addslashes($contact['last_name'])."', '".$contact["phone"]."', '".$contact["fax"]."', '".$contact["email"]."', '10816', NOW(), 'A')";
//                     $atmDB->executeQuery($qq);
//                  }
//               }
//
               $qq = "SELECT * FROM study_user WHERE study_id = '".$study["study_id"]."'";
               $user_rst = $atmDB->executeQuery($qq);
               while ($user = mysql_fetch_assoc($user_rst)) {
                  $qq = "DELETE FROM `armc_user` WHERE `armc_id`='".$attr["id"]."' AND `role_id`='".$user["role_id"]."'";
                  $atmDB->executeQuery($qq);

                  $qq = "INSERT INTO `armc_user` (`armc_id`, `login`, `role_id`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', '".$user["login"]."', '".$user["role_id"]."', '10816', NOW(), '".$user["status"]."')";
                  $atmDB->executeQuery($qq);
               }
            }
         }break;

//         case "M_JOBNUMBER" : {
//               $qq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
//               ." VALUES ('".$attr["id"]."', 'JOBNUMBER', '".$attr["value"]."', '10816', NOW(), 'A')";
//               $atmDB->executeQuery($q);
//         }break;

         case "M_PARTNERID" : {
               $qq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', 'ACCOUNT_ID', '".$attr["value"]."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qq);

               $qq = "SELECT
                  a.account_name,
                  a.country_code,
                  aa_month_end.account_attr_value AS is_month_end,
                  aa_require_ae_approval.account_attr_value AS require_ae_approval,
                  aa_email_invoice.account_attr_value AS email_invoice,
                  aa_print_invoice.account_attr_value AS print_invoice,
                  aa_po_required.account_attr_value AS po_required,
                  aa_pm_required.account_attr_value AS pm_required,
                  aa_job_required.account_attr_value AS job_required
               FROM account AS a
               LEFT JOIN account_attr AS aa_month_end ON aa_month_end.account_id = a.account_id AND aa_month_end.account_attr_name = 'ARMC_MONTH_END_BILLING'
               LEFT JOIN account_attr AS aa_require_ae_approval ON aa_require_ae_approval.account_id = a.account_id AND aa_require_ae_approval.account_attr_name = 'AMRC_AE_APPROVAL_REQUIRED'
               LEFT JOIN account_attr AS aa_email_invoice ON aa_email_invoice.account_id = a.account_id AND aa_email_invoice.account_attr_name = 'ARMC_EMAIL_INVOICE'
               LEFT JOIN account_attr AS aa_print_invoice ON aa_print_invoice.account_id = a.account_id AND aa_print_invoice.account_attr_name = 'ARMC_PRINT_INVOICE'
               LEFT JOIN account_attr AS aa_po_required ON aa_po_required.account_id = a.account_id AND aa_po_required.account_attr_name = 'ARMC_PO_REQUIRED'
               LEFT JOIN account_attr AS aa_pm_required ON aa_pm_required.account_id = a.account_id AND aa_pm_required.account_attr_name = 'ARMC_PM_REQUIRED'
               LEFT JOIN account_attr AS aa_job_required ON aa_job_required.account_id = a.account_id AND aa_job_required.account_attr_name = 'ARMC_JOB_REQUIRED'
               WHERE a.account_id = '".$attr["value"]."'";
               $account_rst = $atmDB->executeQuery($qq);
               if ($account = mysql_fetch_assoc($account_rst)) {
                  $qqq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ACCOUNT_NAME', '".addslashes($account["account_name"])."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ACCOUNT_COUNTRY_CODE', '".$account["country_code"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ARMC_MONTH_END_BILLING', '".$account["is_month_end"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ARMC_AE_APPROVAL_REQUIRED', '".$account["require_ae_approval"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ARMC_EMAIL_INVOICE', '".$account["email_invoice"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ARMC_PRINT_INVOICE', '".$account["print_invoice"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ARMC_PO_REQUIRED', '".$account["po_required"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ARMC_PM_REQUIRED', '".$account["pm_required"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$attr["id"]."', 'ARMC_JOB_REQUIRED', '".$account["job_required"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
               }
         }break;

         case "M_PONUM" : {
               $qq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', 'PONUMBER', '".$attr["value"]."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qq);

               $qq = "SELECT br_id FROM br_main WHERE merge_id = '".$attr["id"]."'";
               $br_rst = $atmDB->executeQuery($qq);
               while ($br = mysql_fetch_assoc($br_rst)) {
                  $qqq = "DELETE FROM `armc_attr` WHERE `armc_id`='".$br["br_id"]."' AND `armc_attr_name`='PONUMBER'";
                  $atmDB->executeQuery($qqq);
                  $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
                  ." VALUES ('".$br["br_id"]."', 'PONUMBER', '".$attr["value"]."', '10816', NOW(), 'A')";
                  $atmDB->executeQuery($qqq);
               }
         }break;

//         case "M_PMNAME" : {
//               $qq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
//               ." VALUES ('".$attr["id"]."', 'PMNAME', '".$attr["value"]."', '10816', NOW(), 'A')";
//               $atmDB->executeQuery($qq);
//         }break;

         case "M_PRJNAME" : {
            $qq = "UPDATE `armc_group` SET `armc_group_description`='".addslashes($attr["value"])."' WHERE `armc_group_id`='".$attr["id"]."'";
            $atmDB->executeQuery($qq);
         }break;

         case "M_INVOICE" : {
            $qq = "SELECT br_id FROM br_main WHERE merge_id = '".$attr["id"]."'";
            $br_rst = $atmDB->executeQuery($qq);
            while ($br = mysql_fetch_assoc($br_rst)) {
               $qqq = "UPDATE `armc` SET `transaction_number` = '".$attr["value"]."' WHERE `armc_id`='".$br["br_id"]."'";
               $atmDB->executeQuery($qqq);
            }
         }break;

         case "M_INVDATE" : {
            $date="";
            if(strlen($attr["value"]==8)) {
               $date = substr($attr["value"], 0, 4)."-".substr($attr["value"], 4, 2)."-".substr($attr["value"], 6, 2);
            }elseif(strlen($attr["value"]==10)){
               $date = $attr["value"];
            }
            if ($date!="") {
               $qq = "SELECT br_id FROM br_main WHERE merge_id = '".$attr["id"]."'";
               $br_rst = $atmDB->executeQuery($qq);
               while ($br = mysql_fetch_assoc($br_rst)) {
                  $qqq = "UPDATE `armc` SET `transaction_date` = '$date' WHERE `armc_id`='".$br["br_id"]."'";
                  $atmDB->executeQuery($qqq);
               }
            }
         }break;

         default: {
            if (strpos(strtoupper($attr["key"]), "M_")!==false) {
               $qq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', '".substr(strtoupper($attr["key"]), 2)."', '".addslashes($attr["value"])."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qq);
               if (strtoupper($attr["key"])=="M_JOBNUMBER" || strtoupper($attr["key"])=="M_PMNAME") {
                  $qq = "SELECT br_id FROM br_main WHERE merge_id = '".$attr["id"]."'";
                  $br_rst = $atmDB->executeQuery($qq);
                  while ($br = mysql_fetch_assoc($br_rst)) {
                     $qqq = "DELETE FROM `armc_attr` WHERE `armc_id`='".$br["br_id"]."' AND `armc_attr_name`='".substr(strtoupper($attr["key"]), 2)."'";
                     $atmDB->executeQuery($qqq);
                     $qqq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
                     ." VALUES ('".$br["br_id"]."', '".substr(strtoupper($attr["key"]), 2)."', '".$attr["value"]."', '10816', NOW(), 'A')";
                     $atmDB->executeQuery($qqq);
                  }
               }
            }else{
               $qq = "DELETE FROM `armc_attr` WHERE `armc_id`='".$attr["id"]."' AND `armc_attr_name`='".strtoupper($attr["key"])."'";
               $atmDB->executeQuery($qq);
               $qq = "INSERT INTO `armc_attr` (`armc_id`, `armc_attr_name`, `armc_attr_value`, `created_by`, `created_date`, `status`) "
               ." VALUES ('".$attr["id"]."', '".strtoupper($attr["key"])."', '".addslashes($attr["value"])."', '10816', NOW(), 'A')";
               $atmDB->executeQuery($qq);
            }
         }
      }
   }

   echo ("armc_attr migrated : ".date("H:i:s")." !!!\n");

   $qq =
      "SELECT
         ag.armc_group_id,
         aga.armc_group_attr_value AS group_account_id,
         aa_account_id.armc_attr_value AS armc_account_id,
         aa_account_name.armc_attr_value AS armc_account_name
      FROM armc_group AS ag
      LEFT JOIN armc_group_attr AS aga ON aga.armc_group_id=ag.armc_group_id AND aga.armc_group_attr_name = 'ACCOUNT_ID'
      LEFT JOIN armc ON armc.armc_group_id = ag.armc_group_id
      LEFT JOIN armc_attr AS aa_account_id ON aa_account_id.armc_id=armc.armc_id AND aa_account_id.armc_attr_name = 'ACCOUNT_ID'
      LEFT JOIN armc_attr AS aa_account_name ON aa_account_name.armc_id=armc.armc_id AND aa_account_name.armc_attr_name = 'ACCOUNT_NAME'
      GROUP BY armc_group_id";
   $armc_rst = $atmDB->executeQuery($qq);
   while ($armc = mysql_fetch_assoc($armc_rst)) {
      if ($armc["group_account_id"]=="") {
         $qqq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
         ." VALUES ('".$armc["armc_group_id"]."', 'ACCOUNT_ID', '".$armc["armc_account_id"]."', '10816', NOW(), 'A')";
         $atmDB->executeQuery($qqq);
         $qqq = "INSERT INTO `armc_group_attr` (`armc_group_id`, `armc_group_attr_name`, `armc_group_attr_value`, `created_by`, `created_date`, `status`) "
         ." VALUES ('".$armc["armc_group_id"]."', 'ACCOUNT_NAME', '".$armc["armc_account_name"]."', '10816', NOW(), 'A')";
         $atmDB->executeQuery($qqq);
      }
   }

   echo ("armc_group_attr migrated : ".date("H:i:s")." !!!\n");

   $qq = "TRUNCATE TABLE armc_type_budget_line_item_def";
   $atmDB->executeQuery($qq);

   $qq = "SELECT * FROM br_budget_def WHERE list_sub != 0";
   $def_rst = $atmDB->executeQuery($qq);
   while ($def = mysql_fetch_assoc($def_rst)) {
      if ($def["list_main"] == "100") {
         $types_array = array(ARMC_TYPE_BR_LI, ARMC_TYPE_CM_LI);
      }elseif ($def["list_main"] == "900" || $def["list_main"] == "950") {
         $types_array = array(ARMC_TYPE_BR_TR, ARMC_TYPE_CM_TR);
      }elseif ($def["list_main"]=="800" && $def["list_sub"]=="100") {
         $types_array = array(ARMC_TYPE_BR_LI, ARMC_TYPE_CM_LI, ARMC_TYPE_BR_TR, ARMC_TYPE_CM_TR, ARMC_TYPE_BR_STUDY, ARMC_TYPE_BR_SB, ARMC_TYPE_BR_OT, ARMC_TYPE_CM_STUDY, ARMC_TYPE_CM_SB, ARMC_TYPE_CM_OT,
         ARMC_TYPE_RT_STUDY, ARMC_TYPE_RT_LI, ARMC_TYPE_RT_OT, ARMC_TYPE_RT_SB, ARMC_TYPE_RT_TR);
      }else{
         $types_array = array(ARMC_TYPE_BR_STUDY, ARMC_TYPE_BR_SB, ARMC_TYPE_BR_OT, ARMC_TYPE_CM_STUDY, ARMC_TYPE_CM_SB, ARMC_TYPE_CM_OT,
         ARMC_TYPE_RT_STUDY, ARMC_TYPE_RT_LI, ARMC_TYPE_RT_OT, ARMC_TYPE_RT_SB, ARMC_TYPE_RT_TR);
      }

      foreach($types_array AS $type) {
         $qq = "INSERT INTO armc_type_budget_line_item_def (`armc_type_id`, `armc_budget_line_item_def_id`, `created_by`, `created_date`, `status`) "
         ." VALUES ('$type', '".$def["id"]."', '10816', NOW(), 'A')";
         $atmDB->executeQuery($qq);
      }
   }

   echo ("armc_type_budget_line_item_def migrated : ".date("H:i:s")." !!!\n");

}
	/**
	* DoTest()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Tue Nov 22 15:34:28 PST 2005
	*/
	function DoTest()
	{
//	   global $smarty;
		//$brDB = new brDB();
//		$o=new oracleDB();
//		$o->SetOrgID(0);
//		$settings = $o->GetSystemParams();
//		$ret = $o->CreateCMs($cms, $settings);
//		print_r ($ret);
//   	if (($errors_count = $o->GetTrxErrorsCount())!=0) {
//	     $o->rollback();
//		//$error_msg = "There are $errors_count error messages as follows :";
//		   $o->GetTrxErrors();
//		    while ($e = $o->fetch_assoc()) {
//			      $errors[] = Array("message"=>$e['ERROR_MESSAGE'], "value"=>$e['INVALID_VALUE']);
////			$error_msg .= "\nERROR_MESSAGE : ".$e['ERROR_MESSAGE'].", INVALID_VALUE : ".$e['INVALID_VALUE']."\n";
//		    }
//		    print_r($errors);
//	   }
//
//	   $o->commit();
		//$q = "SELECT P.PARTY_NAME, L.ADDRESS1, L.CITY, L.STATE, L.POSTAL_CODE, L.COUNTRY FROM HZ_PARTY_SITES PS LEFT JOIN HZ_PARTIES P ON P.PARTY_ID = PS.PARTY_ID LEFT JOIN HZ_LOCATIONS L ON L.LOCATION_ID = PS.LOCATION_ID";
		//$q = "SELECT CS.CUST_ACCT_SITE_ID FROM HZ_CUST_ACCT_SITES CS LEFT JOIN HZ_PARTY_SITES PS ON PS.PARTY_SITE_ID = CS.PARTY_SITE_ID LEFT JOIN HZ_LOCATIONS L ON L.LOCATION_ID = PS.LOCATION_ID WHERE UPPER(TRIM(L.POSTAL_CODE)) LIKE UPPER('%48009%') AND UPPER(TRIM(L.COUNTRY)) LIKE UPPER('%US%') AND UPPER(TRIM(L.STATE)) LIKE UPPER('%MI%') AND UPPER(TRIM(L.ADDRESS1)) LIKE UPPER('%725 SOUTH ADAMS%') AND CS.CUST_ACCOUNT_ID = '1440'";
		//$q = "SELECT CS.CUST_ACCT_SITE_ID FROM HZ_CUST_ACCT_SITES CS LEFT JOIN HZ_PARTY_SITES PS ON PS.PARTY_SITE_ID = CS.PARTY_SITE_ID LEFT JOIN HZ_LOCATIONS L ON L.LOCATION_ID = PS.LOCATION_ID WHERE UPPER(TRIM(L.POSTAL_CODE)) LIKE UPPER('%91361%') AND UPPER(TRIM(L.COUNTRY)) LIKE UPPER('%US%') AND UPPER(TRIM(L.STATE)) LIKE UPPER('%CA%') AND UPPER(TRIM(L.ADDRESS1)) LIKE UPPER('%2625 TOWNSGATE ROAD%') AND CS.CUST_ACCOUNT_ID = '1156'";
		//$q = "SELECT TEXT FROM USER_SOURCE WHERE NAME LIKE UPPER('%arw_cm_cover%')";
		//$q = "DECLARE p_trx_header_tbl ar_invoice_api_pub.trx_header_tbl_type; p_trx_lines_tbl ar_invoice_api_pub.trx_line_tbl_type; p_trx_dist_tbl ar_invoice_api_pub.trx_dist_tbl_type; p_trx_salescredits_tbl ar_invoice_api_pub.trx_salescredits_tbl_type; p_batch_source_rec ar_invoice_api_pub.batch_source_rec_type; x_customer_trx_id number; x_return_status varchar2(2000); x_msg_count number; x_msg_data varchar2(2000); BEGIN fnd_global.apps_initialize(1092, 20678, 222, 0); p_batch_source_rec.batch_source_id := 1003; p_trx_header_tbl(1).trx_header_id := 1; p_trx_header_tbl(1).cust_trx_type_id := 1002; p_trx_header_tbl(1).bill_to_customer_id := 1381; p_trx_header_tbl(1).bill_to_address_id := 1136; p_trx_header_tbl(1).bill_to_contact_id := 1807; p_trx_header_tbl(1).ship_to_customer_id := 1381; p_trx_header_tbl(1).ship_to_address_id := 1136; p_trx_header_tbl(1).ship_to_contact_id := 1807; p_trx_header_tbl(1).printing_option := 'NOT'; p_trx_header_tbl(1).comments := ''; p_trx_lines_tbl(1).trx_header_id := 1; p_trx_lines_tbl(1).trx_line_id := 17292; p_trx_lines_tbl(1).line_number := 1; p_trx_lines_tbl(1).description := '29975 - Analyzer'; p_trx_lines_tbl(1).quantity_invoiced := 1.000; p_trx_lines_tbl(1).unit_standard_price := 38.00; p_trx_lines_tbl(1).unit_selling_price := 38.000; p_trx_lines_tbl(1).line_type := 'LINE'; AR_INVOICE_API_PUB.create_single_invoice( 1.0, 'F', 'F', p_batch_source_rec, p_trx_header_tbl, p_trx_lines_tbl, p_trx_dist_tbl, p_trx_salescredits_tbl, x_customer_trx_id, x_return_status, x_msg_count, x_msg_data); END;";
		//$q = "SELECT * FROM xxgmi_country_codes ORDER BY country_description";
		//$q = "SELECT * FROM RA_BATCH_SOURCES";
		//$q = "SELECT * FROM RA_CUST_TRX_TYPES";
//		$q = "SELECT TEXT FROM USER_SOURCE WHERE NAME LIKE UPPER('%ar_invoice_utils%')";
//		$o->SetServerOutput(true);
//		$q = "BEGIN dbms_output.put_line('Hello world !!!'); dbms_output.put_line('How are you ???'); END;";
//		echo ("<p>$q</p>");
		//$q = "SELECT * FROM RA_CUST_TRX_TYPES";
//		$q = "SELECT 'X' FROM ra_cust_trx_types ctt"
//			." where type IN ('INV', 'DM')"
//			." and trunc(sysdate) between ctt.start_date and nvl(ctt.end_date, trunc(sysdate))"
//			." and ctt.cust_trx_type_id = 1003";
		//$q = "SELECT * FROM HZ_PARTIES WHERE PARTY_ID = 5060";
//		$q = "SELECT * FROM AR_ACTIVE_REMIT_TO_ADDRESSES_V";
//		$o->parse($q);
//		$o->execute();
////
//		if ($output = $o->GetDBMSOutput()) {
//			foreach($output as $line) {
//				echo ($line."<br>");
//			}
//		}
//		while ($bs = $o->fetch_assoc()) {
//			foreach($bs as $field=>$value) {
//				echo ("$field : $value<br>");
//			}
//			echo("<hr>");
//		}
//      $o->parse($q);
//      $o->execute();
//		echo ("<pre>");
//		while ($c = $o->fetch_assoc()) {
//			echo ($c['TEXT']."<br>");
//		}
//		echo ("</pre>");
//
//      $invoice = $o->GetInvoiceHeader(1275);
//      $invoice['lines'] = $o->GetInvoiceLines(1275);
//      $invoice['total'] = 0;
//      foreach ($invoice['lines'] as $line) {
//         $invoice['total'] += $line['VALUE'];
//      }
//      print_r($invoice);

//      $smarty->assign("invoice", $invoice);
//      $HTML = $smarty->fetch("app/atm/armc/invoice_pdf.tpl");
//      $file_name = "/tmp/invoice_".$invoice['TRX_NUMBER'].".html";
//      $f = fopen($file_name, "w");
//      fprintf($f, "%s", $HTML);
//      fclose($f);
//      $smarty->display("app/atm/armc/invoice_pdf.tpl");
//      echo date("Y-m-".GetLastDateOfMonth(strtotime("last month", time())), strtotime("last month", time()));
	}
	
	
	
	/**
	 * Check the User is allowed to Skip approval
	 *	
	 * @return boolean
	 */
	function isUserAllowedToSkipApproval($login) 
	{
		
		$user = new userDB();
		$billing_report_setting = Hb_App_Billing_BillingReportSetting::GetSetting('SKIP_APPROVAL_ALLOWED_GROUPS');
		
		$billing_report_setting = explode(',',$billing_report_setting);
		
		foreach ($billing_report_setting as $group_id) 
		{
			if ($user->isUserMemberOfSecGroup($group_id,$login))
			{
				return true;
			}
		}
		
		return false;		
	}
	

	/**
	 * Check the User is valid AE user
	 *	
	 * @return boolean
	 */
	function isAEValidUser($armc_id) 
	{
		$user_db = new userDB();
		$atm_db 	= new atmDB(); 
		
		$rs_ae_users = $atm_db->GetARMCUsers($armc_id,array(0 => ROLE_PRIMARY_ACCT_EXEC));
     		
		while($ae_user  = mysql_fetch_assoc($rs_ae_users)) {
			
			$ae_users[] = $ae_user["login"];
			
		}
		
		foreach ($ae_users as $user)
   	{
   		if ($user_db->isValidUser($user)) 
   		{
   			return true;
   		}
   		
   	}
   	
   	return false;
	}
	
?>
