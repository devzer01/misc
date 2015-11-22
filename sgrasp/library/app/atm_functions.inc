<?php
require_once 'db/atmDB.class.php';
require_once 'app/armc/functions.inc';
/**
* HBRPC_ATM_GetMTDInvoicedForAE()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun Mar 05 15:59:30 PST 2006
*/
function HBRPC_ATM_GetMTDInvoicedForAE($params)
{
   $atmDB = new atmDB();
//   $armc_rst = $atmDB->GetARMCList(" AND account_executive_id = '".$params["user_id"]."' AND armc_status_id IN (".ARMC_STATUS_INVOICED.", ".ARMC_STATUS_INVOICED_MANUALLY.")" , "ORDER BY armc_type_id");
//   while ($armc = mysql_fetch_assoc($armc_rst)) {
//      $armcs[] = $armc;
//   }
   if (!isset($params["user_id"])&&(!isset($params["date"]))) {
      return array("error_code"=>500, "error_message"=>"user_id and date are required parameters !!!");
   }
   $year = substr($params["date"], 0, 4);
   $month = substr($params["date"], 5, 2);
   $day = substr($params["date"], 8, 2);

   $armc_rst = $atmDB->GetMTDInvoicedForAE($params["user_id"], $year, $month, $day);
   while ($armc = mysql_fetch_assoc($armc_rst)) {
      $armcs[] = $armc;
   }

   return array("error_code"=>0, "armcs"=>$armcs);
}

/**
* HBRPC_ATM_GetApprovedBRValues()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 03 11:02:20 PST 2006
*/
function HBRPC_ATM_GetApprovedBRValues($o)
{
   if (!isset($o["user_id"]))
      return array("error_code"=>100, "error_message"=>"user_id is required !!!");

   if (!isset($o["start_date"]) || !isset($o["end_date"]))
      return array("error_code"=>101, "error_message"=>"start_date and end_date are required !!!");

   if (!isset($o["role_id"]))
      $o["role_id"]=ROLE_PRIMARY_ACCT_MGR;

   $atmDB = new atmDB();

   switch ($o["role_id"]) {
      case ROLE_PRIMARY_ACCT_EXEC : $armc_action_type_id = ARMC_ACTION_TYPE_AE_APPROVED_HEADER; break;
      case ROLE_ACCOUNTING : $armc_action_type_id = ARMC_ACTION_TYPE_ACCT_APPROVED_HEADER; break;
      case ROLE_PRIMARY_ACCT_MGR :
      default : $armc_action_type_id = ARMC_ACTION_TYPE_AM_APPROVED_HEADER; break;
   }

   $rst = $atmDB->GetActionARMC($o["start_date"], $o["end_date"], $o["user_id"], $armc_action_type_id);
   $credit_memo = array("count"=>0, "amount"=>0);
   $invoice = array("count"=>0, "amount"=>0);
   while ($app = mysql_fetch_assoc($rst)) {
      if (IsCreditMemo($app["armc_type_id"])) {
         $credit_memo["count"] += $app["count"];
         $credit_memo["amount"] += $app["amount"];
      }else{
         $invoice["count"] += $app["count"];
         $invoice["amount"] += $app["amount"];
      }
//      echo ($app["count"]." of type :".$app["armc_type_id"]." value : ".$app["amount"]."<br>");
   }

   return array("error_code"=>0, "invoices"=>$invoice, "credit_memos"=>$credit_memo);

}

/**
* HBRPC_ATM_GetPortlet()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 01 15:57:20 PST 2006
*/
function HBRPC_ATM_GetPortlet($o)
{
   if (!isset($o["portlet_id"])) return array("error_code"=>100, "error_message"=>"portlet_id is required !!!");
   switch ($o["portlet_id"]) {
      case ATM_PORTLET_RECENT : {
         return array("error_code"=>0, "body"=>GetRecentPortlet($o["filter"]));
      }break;

      case ATM_PORTLET_MTD : {
         return array("error_code"=>0, "body"=>GetATMMTDPortlet($o["filter"], $o));
      }break;

      case ATM_PORTLET_MTD_DETAILED : {
         return array("error_code"=>0, "body"=>GetATMMTDDetailedPortlet($o["filter"]));
      }break;
      
      case ATM_PORTLET_APPROVAL : {
         //return array("error_code"=>0, "body"=>"TEST");
         if (!isset($o["filter"]["user_id"])) {
            return array("error_code"=>200, "body"=>"User ID is required !!!");
         }else{
            return array("error_code"=>0, "body"=>GetApprovalPortlet($o));
         }
      }
   }

}

/**
* GetRecentPortlet()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 01 16:09:06 PST 2006
*/
function GetRecentPortlet($filter=array())
{
   //$smarty = $GLOBALS["smarty"];
   //global $smarty;
   $registry = Zend_Registry::getInstance();
   $smarty = $registry['smarty'];
   $list = array();

   $atmDB = new atmDB();

   $columns = array("display_account_name"=>1, "display_account_executive"=>0, "display_account_manager"=>0, "display_invoice_number"=>1, "count"=>3);
   $f = " AND a.armc_status_id IN (".ARMC_STATUS_INVOICED_MANUALLY.", ".ARMC_STATUS_INVOICED.", ".ARMC_STATUS_INVOICED_PARTIAL_BALANCE.", ".ARMC_STATUS_INVOICED_BALANCED.")";
   if (isset($filter["account_id"])) {
      $columns["display_account_name"] = 0;
      if (is_array($filter["account_id"])) {
         $f .= " AND account_id IN (".implode(", ", $filter["account_id"]).") ";
      } else {
         $f .= " AND account_id = '".$filter["account_id"]."' ";
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
      $f .= "AND (au.login IN (".implode(", ", $user_ids).")) ";
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

   $columns["count"] = 3+$col_count;

   $total = 0;
   $armc_rst = $atmDB->GetARMCList($f, "ORDER BY transaction_date DESC", "LIMIT 10");
   while ($armc = mysql_fetch_assoc($armc_rst)) {
      if (IsCreditMemo($armc["armc_type_id"])) {
         $total -= $armc["amount"];
         $armc["display_amount"] = "<font color='Red'>$".number_format(0-$armc["amount"], 2)."</font>";
      }else{
         $total += $armc["amount"];
         $armc["display_amount"] = "$".number_format($armc["amount"], 2);
      }
      $list[] =
         array(
            "armc_link"=>($armc["grouped"]=="true"?"action=display_group_details&armc_group_id=".$armc["armc_id"]:"action=display_armc_details&armc_id=".$armc["armc_id"]),
            "armc_id"=>$armc["armc_type_prefix"]."-".$armc["armc_id"],
            "project_link"=>($armc["grouped"]=="false" && RequireStudy($armc["armc_type_id"])),
            "study_id"=>$armc["study_id"],
            "project_name"=>$armc["study_name"],
            "account_id"=>$armc["account_id"],
            "account_name"=>$armc["account_name"],
            "account_executive"=>$armc["account_executive"],
            "account_manager"=>$armc["account_manager"],
            "transaction_date"=>$armc["transaction_date"],
            "transaction_number"=>$armc["transaction_number"],
            "amount"=>$armc["display_amount"]);
   }
   $smarty->assign("list", $list);
   $smarty->assign("total", $total);
   $smarty->assign("columns", $columns);
   $template = "invoice/ptl_recent_invoiced.tpl";
   if (isExternalUser())
      $template = "atm/armc/ext/ptl_recent_invoiced.tpl";
      //print_r($smarty);
   return $smarty->fetch($template);
}

/**
* GetATMMTDPortlet()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 01 18:27:44 PST 2006
*/
function GetATMMTDPortlet($filter=array(), $meta = array())
{
   //$smarty = $GLOBALS['smarty'];
   //$comDB = new commonDB();
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

   $accounting_period = GetAccountingPeriod();
   $list = array(
      0=>array("description"=>"Pending Approval", "amount"=>0),
      1=>array("description"=>"Month End", "amount"=>0),
      2=>array("description"=>"Invoiced", "amount"=>0),
      3=>array("description"=>"Total", "amount"=>0));

   $f = "";
   if (isset($filter["account_id"])) {
      if (is_array($filter["account_id"])) {
         $f .= " AND account_id IN (".implode(", ", $filter["account_id"]).") ";
      } else {
         $f .= " AND account_id = '".$filter["account_id"]."' ";
      }
   }

   if (isset($filter["user_id"])) {
      if (!is_array($filter["user_id"]) && $meta['my_data_only'] == 0)
         $user_ids = GetReportees($filter["user_id"], 0);
      elseif (sizeof($filter["user_id"])==1 && $meta['my_data_only'] == 0)
         $user_ids = GetReportees($filter["user_id"][0], 0);
      else
         $user_ids = $filter["user_id"];
      $f .= "AND (au.login IN (".implode(", ", $user_ids).")) ";
   }

   if (isset($filter["year"])) {
      $year = $filter["year"];
   }else{
      $year = substr($accounting_period, 0, 4);
   }
   if (isset($filter["month"])) {
      $month = $filter["month"];
   }else{
      $month = substr($accounting_period, 5);
   }
   if (isset($filter["day"])) {
      $day = $filter["day"];
   }else{
      $day = GetLastDateOfMonth(mktime(0, 0, 0, $month, 10, $year));
   }

   $ff = " AND armc_date BETWEEN '$year-$month-01' AND '$year-$month-$day 23:59:59' AND a.armc_status_id IN (".ARMC_STATUS_STALLED.", ".ARMC_STATUS_EDIT.", ".ARMC_STATUS_AM_APPROVAL.", ".ARMC_STATUS_AE_APPROVAL.", ".ARMC_STATUS_ACCT_APPROVAL.", ".ARMC_STATUS_READY_FOR_INVOICE.") $f";
   $atmDB = new atmDB();
   $armc_rst = $atmDB->GetARMCList($ff, "");
   while ($armc = mysql_fetch_assoc($armc_rst)) {
      if ($armc["is_month_end"]==1) {
         if (IsCreditMemo($armc["armc_type_id"])) {
            $list[1]["amount"] -= $armc["amount"];
            $list[3]["amount"] -= $armc["amount"];
         }else{
            $list[1]["amount"] += $armc["amount"];
            $list[3]["amount"] += $armc["amount"];
         }
      }else{
         if (IsCreditMemo($armc["armc_type_id"])) {
            $list[0]["amount"] -= $armc["amount"];
            $list[3]["amount"] -= $armc["amount"];
         }else{
            $list[0]["amount"] += $armc["amount"];
            $list[3]["amount"] += $armc["amount"];
         }
      }
   }
   $ff = " AND armc_date BETWEEN '$year-$month-01' AND '$year-$month-$day 23:59:59' AND a.armc_status_id IN (".ARMC_STATUS_INVOICED_MANUALLY.", ".ARMC_STATUS_INVOICED.", ".ARMC_STATUS_INVOICED_PARTIAL_BALANCE.", ".ARMC_STATUS_INVOICED_BALANCED.") $f";
   $armc_rst = $atmDB->GetARMCList($ff,"");
   while ($armc = mysql_fetch_assoc($armc_rst)) {
      if (IsCreditMemo($armc["armc_type_id"])) {
         $list[2]["amount"] -= $armc["amount"];
         $list[3]["amount"] -= $armc["amount"];
      }else{
         $list[2]["amount"] += $armc["amount"];
         $list[3]["amount"] += $armc["amount"];
      }
   }
   $smarty->assign("list", $list);
   $smarty->assign("month", date("F Y", mktime(0, 0, 0, $month, $day, $year)));
   $smarty->assign("meta", $meta);
   return $smarty->fetch("app/atm/armc/ptl_mtd.tpl");
}

/**
* GetATMMTDDetailedPortlet()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Mar 28 11:52:36 PST 2006
*/
function GetATMMTDDetailedPortlet($filter = array())
{
   $atmDB = new atmDB();
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

   $accounting_period = GetAccountingPeriod();

   $f = "";
   if (isset($filter["account_id"])) {
      if (is_array($filter["account_id"])) {
         $f .= " AND account_id IN (".implode(", ", $filter["account_id"]).") ";
      } else {
         $f .= " AND account_id = '".$filter["account_id"]."' ";
      }
   }

   if (isset($filter["user_id"])) {
      if (!is_array($filter["user_id"]))
         $user_ids = GetReportees($filter["user_id"], 0);
      elseif (sizeof($filter["user_id"])==1)
         $user_ids = GetReportees($filter["user_id"][0], 0);
      else
         $user_ids = $filter["user_id"];
      $f .= "AND (au.login IN (".implode(", ", $user_ids).")) ";
   }

   if (isset($filter["year"])) {
      $year = $filter["year"];
   }else{
      $year = substr($accounting_period, 0, 4);
   }
   if (isset($filter["month"])) {
      $month = $filter["month"];
   }else{
      $month = substr($accounting_period, 5);
   }
   if (isset($filter["day"])) {
      $day = $filter["day"];
   }else{
      $day = GetLastDateOfMonth(mktime(0, 0, 0, $month, 10, $year));
   }

   $list_status = array(
      ARMC_STATUS_STALLED=>array("description"=>"Stalled by user", "amounts"=>array(0, 0, 0, 0)),
      ARMC_STATUS_EDIT=>array("description"=>"Waiting Data Entry", "amounts"=>array(0, 0, 0, 0)),
      ARMC_STATUS_AM_APPROVAL=>array("description"=>"Waiting for AM approval", "amounts"=>array(0, 0, 0, 0)),
      ARMC_STATUS_AE_APPROVAL=>array("description"=>"Waiting for AE approval", "amounts"=>array(0, 0, 0, 0)),
      ARMC_STATUS_ACCT_APPROVAL=>array("description"=>"Waiting for ACCT approval", "amounts"=>array(0, 0, 0, 0)),
      ARMC_STATUS_READY_FOR_INVOICE=>array("description"=>"Ready to be Invoiced", "amounts"=>array(0, 0, 0, 0)),
      ARMC_STATUS_INVOICED=>array("description"=>"Invoiced", "amounts"=>array(0, 0, 0, 0)),
      "total"=>array("description"=>"Total", "amounts"=>array(0, 0, 0, 0)));
   $ff = " AND armc_date BETWEEN '$year-$month-01' AND '$year-$month-$day 23:59:59' AND a.armc_status_id IN (".ARMC_STATUS_STALLED.", ".ARMC_STATUS_EDIT.", ".ARMC_STATUS_AM_APPROVAL.", ".ARMC_STATUS_AE_APPROVAL.", ".ARMC_STATUS_ACCT_APPROVAL.", ".ARMC_STATUS_READY_FOR_INVOICE.", ".ARMC_STATUS_INVOICED.", ".ARMC_STATUS_INVOICED_MANUALLY.", ".ARMC_STATUS_INVOICED_BALANCED.", ".ARMC_STATUS_INVOICED_PARTIAL_BALANCE.") $f";
   $armc_rst = $atmDB->GetARMCList($ff, "");
   while ($armc = mysql_fetch_assoc($armc_rst)) {
      if (in_array($armc["armc_status_id"], array(ARMC_STATUS_INVOICED_MANUALLY, ARMC_STATUS_INVOICED_PARTIAL_BALANCE, ARMC_STATUS_INVOICED_BALANCED)))
         $armc["armc_status_id"]=ARMC_STATUS_INVOICED;
      if (IsCreditMemo($armc["armc_type_id"]))
         $amount = 0-$armc["amount"];
      else
         $amount = $armc["amount"];
      if ($armc["is_month_end"]) {
         $amount_index = 2;
      }else{
         if ($armc["grouped"]=="true") {
            $amount_index = 1;
         }else{
            $amount_index = 0;
         }
      }
      $list_status[$armc["armc_status_id"]]["amounts"][$amount_index] += $amount;
      $list_status[$armc["armc_status_id"]]["amounts"][3] += $amount;
      $list_status["total"]["amounts"][$amount_index] += $amount;
      $list_status["total"]["amounts"][3] += $amount;
   }
   $list = array();
   foreach($list_status AS $item) {
      $list[] = $item;
   }
   $smarty->assign("list", $list);
   $smarty->assign("month", date("F Y", mktime(0, 0, 0, $month, $day, $year)));
   return $smarty->fetch("app/atm/armc/ptl_mtd_detailed.tpl");

}

/**
* GetApprovalPortlet()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Apr 11 12:15:13 PDT 2006
*/
function GetApprovalPortlet($o)
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
   
   $atmDB = new atmDB();
   $filter = $o["filter"];
   
   //return $filter;

   $f = "";
   if (isset($filter["account_id"])) {
      if (is_array($filter["account_id"])) {
         $f .= " AND account_id IN (".implode(", ", $filter["account_id"]).") ";
      } else {
         $f .= " AND account_id = '".$filter["account_id"]."' ";
      }
   }

   if (isset($filter["user_id"])) {
      if (!is_array($filter["user_id"]) && $o['my_data_only'] == 0)
         $user_ids = GetReportees($filter["user_id"], 0);
      elseif (sizeof($filter["user_id"])==1 && $o['my_data_only'] == 0)
         $user_ids = GetReportees($filter["user_id"][0], 0);
      else
         $user_ids = $filter["user_id"];
      $f .= "AND (au.login IN (".implode(", ", $user_ids).")) ";
   }

   $f .= " AND (a.armc_status_id IN (".ARMC_STATUS_AM_APPROVAL.", ".ARMC_STATUS_AE_APPROVAL.", ".ARMC_STATUS_ACCT_APPROVAL."))";
   
   //return $f;
   $armc_rst = $atmDB->GetARMCList($f, "");
   $list = array();
   //$ret = "";
   $total = 0;
   while ($armc = mysql_fetch_assoc($armc_rst)) {
      //$ret .= print_r($armc, true);
      $armc_users_rst = $atmDB->GetARMCUsers($armc["armc_id"], array(ROLE_ACCOUNT_EXECUTIVE, ROLE_PRIMARY_ACCT_EXEC, ROLE_SECONDARY_ACCT_EXEC));
      $armc_aes = array();
      while ($armc_user = mysql_fetch_assoc($armc_users_rst)) {
         $armc_aes[] = $armc_user['login'];
      }
      $armc_users_rst = $atmDB->GetARMCUsers($armc["armc_id"], array(ROLE_ACCOUNT_MANAGER, ROLE_PRIMARY_ACCT_MGR, ROLE_PROJECT_MANAGER, ROLE_PRIMARY_PROJECT_MANAGER, ROLE_SECONDARY_PROJECT_MANAGER));
      $armc_ams = array();
      while ($armc_user = mysql_fetch_assoc($armc_users_rst)) {
         $armc_ams[] = $armc_user['login'];
      }
      $armc_users_rst = $atmDB->GetARMCUsers($armc["armc_id"], array(ROLE_ACCOUNTING));
      $armc_accts = array();
      while ($armc_user = mysql_fetch_assoc($armc_users_rst)) {
         $armc_accts[] = $armc_user['login'];
      }

      if (((sizeof(array_intersect($armc_ams, $user_ids))!=0)&&($armc['armc_status_id']==ARMC_STATUS_AM_APPROVAL)) || 
         ((sizeof(array_intersect($armc_aes, $user_ids))!=0)&&($armc['armc_status_id']==ARMC_STATUS_AE_APPROVAL)) || 
         ((sizeof(array_intersect($armc_accts, $user_ids))!=0)&&($armc['armc_status_id']==ARMC_STATUS_ACCT_APPROVAL))) {
         if (IsCreditMemo($armc["armc_type_id"])) {
            $total -= $armc["amount"];
            $armc["display_amount"] = "<font color='Red'>$".number_format(0-$armc["amount"], 2)."</font>";
         }else{
            $total += $armc["amount"];
            $armc["display_amount"] = "$".number_format($armc["amount"], 2);
         }
         $list[] =
            array(
               "armc_link"=>($armc["grouped"]=="true"?"action=display_group_details&armc_group_id=".$armc["armc_id"]:"action=display_armc_details&armc_id=".$armc["armc_id"]),
               "armc_id"=>$armc["armc_type_prefix"]."-".$armc["armc_id"],
               "armc_status_description"=>$armc["armc_status_description"],
               "project_link"=>($armc["grouped"]=="false" && RequireStudy($armc["armc_type_id"])),
               "study_id"=>$armc["study_id"],
               "project_name"=>$armc["study_name"],
               "account_id"=>$armc["account_id"],
               "account_name"=>$armc["account_name"],
               "account_executive"=>$armc["account_executive"],
               "account_manager"=>$armc["account_manager"],
               "amount"=>$armc["display_amount"]);
      }
   }
   $o["columns"] = ($o["my_data_only"]?5:7);
   if (!isset($o["portlet_title"]) && !$o["my_data_only"]) $o["portlet_title"] = "Billing Reports requiring my team's approval";
   $smarty->assign("list", $list);
   $smarty->assign("total", $total);
   $smarty->assign("meta", $o);
   //return $ret;
   return $smarty->fetch("app/atm/armc/ptl_approval_required.tpl");
}

/**
* HBRPC_ATM_GetAttrValues()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Mar 16 10:41:34 PST 2006
*/
function HBRPC_ATM_GetAttrValues($params)
{
   $atmDB = new atmDB();
   if (!isset($params["attr_name"])) {
      return array("error_code"=>100, "attr_name is required !!!");
   }

   switch ($params["attr_name"]) {
   case "armc_type_id" : {
      $rst = $atmDB->GetARMCTypes();
      $types = PrepareSmartyArray($rst);
      return array("error_code"=>0, "error_message"=>"Success", "meta"=>array("index"=>"armc_type_id", "value"=>"armc_type_description"),
         "data"=>$types);
   }break;
   case "armc_status_id" : {
      $rst = $atmDB->GetARMCStatuses();
      $statuses = PrepareSmartyArray($rst);
      return array("error_code"=>0, "error_message"=>"Success", "meta"=>array("index"=>"armc_status_id", "value"=>"armc_status_description"),
         "data"=>$statuses);
   }break;
   case "delay" : {
      return array("error_code"=>0, "error_message"=>"Success", "meta"=>array("index"=>"hours", "value"=>"description"), 
         "data"=>array(array("hours"=>1, "description"=>"One hour"), 
                       array("hours"=>4, "description"=>"Four hours"), 
                       array("hours"=>12, "description"=>"Twelve hours"), 
                       array("hours"=>24, "description"=>"One day (24 hours)"), 
                       array("hours"=>48, "description"=>"Two days (48 hours)"), 
                       array("hours"=>72, "description"=>"Three days (72 hours)"), 
                       array("hours"=>96, "description"=>"Four days (96 hours)"),
                       array("hours"=>168, "description"=>"One week (168 hours)"),
                       array("hours"=>336, "description"=>"Two weeks (336 hours)")));
   }break;
   default: {
      return array("error_code"=>404, "error_message"=>"Unknown attribute name");
   }break;
   }
}

/**
 * HBRPC_ATM_SetARMCInvoice($o)
 * 
 * This is the HBRPC call to set the captured mail from oracle
 * 	which includes the generated invoice as a PDF attachment
 *
 * @param array $o
 * @return an array with complete mail data or an error info in case of no mail data
 * 
 * @since Tue May 29 2007  
 * @author msilva
 */
function HBRPC_ATM_SetARMCInvoice($o)
{

	$atm_armc_filemanager =new atm_armc_FileManager();

	// do insert billing report
	if ($atm_armc_filemanager->SetARMCInvoice($o))
	{
		return array("error_code" => 0, "error_message" => "Data successfully saved");		
	}

	// no success in inserting billing report
	return array("error_code" => -1, "error_message" => "Cannot find expected data inside input params `mail_set`");
}

/**
 * HBRPC_ATM_SetARMCGroupInvoice($o)
 * 
 * This is the HBRPC call to set the captured mail from oracle
 * 	which includes the generated invoice as a PDF attachment
 *
 * @param array $o
 * @return an array with complete mail data or an error info in case of no mail data
 * 
 * @since Tue May 29 2007  
 * @author msilva
 */
function HBRPC_ATM_SetARMCGroupInvoice($o)
{

	$atm_armc_filemanager =new atm_armc_FileManager();

	// do insert billing report
	if ($atm_armc_filemanager->SetARMCGroupInvoice($o))
	{
		return array("error_code" => 0, "error_message" => "Data successfully saved");		
	}

	// no success in inserting billing report
	return array("error_code" => -1, "error_message" => "Cannot find expected data inside input params `mail_set`");
}
?>