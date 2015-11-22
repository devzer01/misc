<?php

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
   return ($type==TYPE_BR_STUDY || $type==TYPE_RT_STUDY || $type==TYPE_CM_STUDY);
}

/**
* CheckPartner()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Jan 20 17:27:15 PST 2006
*/
function CheckPartner($partner)
{
   return $partner;
}

/**
* CheckStudy()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Jan 20 17:21:57 PST 2006
*/
function CheckStudy($o)
{
   $study = new studyDB();
   $study->_study = $o['study_id'];

   if (!$study->onfile($o)) {
      $o['study_type_id'] = 1;
      $o['study_datasource_id'] = 1;
      $o['start_date'] = date("Y-m-d");
      $o['created_by'] = $_SESSION['admin_id'];

      $study->GetNetMr($o);

      $study->AddStudy($o);

      $header = $study->getHeaderDetail();
      $partner = new partnerDB($header['partner_id']);

      $partner = CheckPartner($partner);

      $o['login'] = $partner->getAM();
      $o['role_id'] = PRIMARY_ACCT_MGR;
      $study->setRole($o);
      $o['alert_level_id'] = STUDY_ALERT_GREEN;
      $study->setAlertLevel($o);

  }

  $study->setStatus(STUDY_CLOSED);

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
          . " o.innerHTML = '<select name=\'".$obj_name."\'> "
          . "                    <option value=\'\'>Select Account From List</option> ";

      while ($r = mysql_fetch_assoc($rs)) {
         $js .= " <option value=\'".$r['partner_id']."\'>".$r['partner_id']." - ".addslashes($r['company_name'])."</option>";
      }

      $js .= "  </select> &nbsp; "
          .  "  <a href=\'javascript:void(0)\' onclick=ResetValidation(\'".$obj_name."\',\'".$func_name."(this)\');>Reset</a>'; "
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
   if ($o['search_study_id']!="") {
      $filter .= " AND (STUDY_ID = '".$o['search_study_id']."')";
   }

   if ($o['search_partner_id']!="") {
      $filter .= " AND (PARTNER_ID = '".$o['search_partner_id']."')";
   }

   if ($o['search_type_id'][0]!=0 || sizeof($o['search_type_id'])>1) {
      $filter .= " AND (a.armc_type_id IN (";
      foreach($o['search_type_id'] as $type_id) {
         $filter .= "$type_id, ";
      }
      $filter = substr($filter, 0, strlen($filter)-2)."))";
   }

   if ($o['search_status_id'][0]!=0 || sizeof($o['search_status_id'])>1) {
      $filter .= " AND (a.armc_status_id IN (";
      foreach($o['search_status_id'] as $status_id) {
         $filter .= "$status_id, ";
      }
      $filter = substr($filter, 0, strlen($filter)-2)."))";
   }

   if ($o['search_account_executive_id'][0]!=0 || sizeof($o['search_account_executive_id'])>1) {
      $filter .= " AND (ae.login IN (";
      foreach($o['search_account_executive_id'] as $ae_id) {
         $filter .= "$ae_id, ";
      }
      $filter = substr($filter, 0, strlen($filter)-2)."))";
   }

   if ($o['search_account_manager_id'][0]!=0 || sizeof($o['search_account_manager_id'])>1) {
      $filter .= " AND (am.login IN (";
      foreach($o['search_account_manager_id'] as $ae_id) {
         $filter .= "$ae_id, ";
      }
      $filter = substr($filter, 0, strlen($filter)-2)."))";
   }

   if ($o['created_date_start']!="" || $o['created_date_end']!="") {
      $filter .= " AND a.created_date BETWEEN '";
      if ($o['created_date_start']!="") {
         $filter .= $o['created_date_start'];
      }else{
         $filter .= "1900-01-01";
      }
      $filter .= "' AND '";
      if ($o['created_date_end']!="") {
         $filter .= date("Y-m-d", mktime(0, 0, 0, substr($o['created_date_end'], 5, 2), substr($o['created_date_end'], 8, 2)+1, substr($o['created_date_end'], 0, 4)));
      }else{
         $filter .= date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")+1, date("Y")));
      }
      $filter .= "'";
   }

   if ($o['invoice_date_start']!="" || $o['invoice_date_end']!="") {
      $filter .= " AND a.transaction_date BETWEEN '";
      if ($o['invoice_date_start']!="") {
         $filter .= $o['invoice_date_start'];
      }else{
         $filter .= "1900-01-01";
      }
      $filter .= "' AND '";
      if ($o['invoice_date_end']!="") {
         $filter .= date("Y-m-d", mktime(0, 0, 0, substr($o['invoice_date_end'], 5, 2), substr($o['invoice_date_end'], 8, 2)+1, substr($o['invoice_date_end'], 0, 4)));
      }else{
         $filter .= date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")+1, date("Y")));
      }
      $filter .= "'";
   }

   echo ($filter);
   return $filter;
}

/**
* HandleAdd()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Jan 17 10:22:24 PST 2006
*/
function HandleAdd($o)
{
//   global $smarty;
//   $atmDB = new atmDB();
//   if (!isset($o['armc_type'])) {
//      DisplayHeader();
//      $types_result = $atmDB->GetARMCTypes();
//      while ($type = mysql_fetch_assoc($types_result)) {
//         if ($type['armc_type_prefix'] != 'CM')
//            $types[] = $type;
//      }
//      $smarty->assign("armc_types", $types);
//      $smarty->display("app/atm/newarmc/vw_add.tpl");
//      DisplayFooter();
//   }else{
//      $create_new_br = true;
//      if (RequireStudy($o['armc_type'])) {
//
//         CheckStudy($o);
//
//         if ($study_br = $atmDB->StudyHasBR($o['study_id'])) {
//            if (isRetainer($study_br['br_type'])) {
//               $create_new_br = true;
//            }else{
//               $create_new_br = false;
//            }
//         }
//
//      }
//      print_r($o);
//   }
   $list = HBRPCCall('dev4.mi.gmi-mr.com', 'amm', 'GetAccountDetail', array('account_id'=>'203182'));
   print_r($list);
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
* HandleDefault()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Jan 17 10:40:59 PST 2006
*/
function HandleDefault($o)
{
   global $smarty;
   $atmDB = new atmDB();
   $commonDB = new commonDB();


   if ($o['display_count_changed']==1 || isset($o['search_study_id']) || isset($o['order_tag']))
      $o['page'] = 1;



   $o['display_count'] = CheckSessionVariable($o, 'display_count', 1);
   $o['page'] = CheckSessionVariable($o, 'page', 1);
   $o['search_study_id'] = CheckSessionVariable($o, 'search_study_id', "");
   $o['search_partner_id'] = CheckSessionVariable($o, 'search_partner_id', "");
   $o['search_type_id'] = CheckSessionVariable($o, 'search_type_id', array("0"=>0));
   $o['search_status_id'] = CheckSessionVariable($o, 'search_status_id', array("0"=>0));
   $o['search_account_executive_id'] = CheckSessionVariable($o, 'search_account_executive_id', array("0"=>0));
   $o['search_account_manager_id'] = CheckSessionVariable($o, 'search_account_manager_id', array("0"=>0));
   $o['search_created_date_start'] = CheckSessionVariable($o, 'search_created_date_start', "");
   $o['search_created_date_end'] = CheckSessionVariable($o, 'search_created_date_end', "");
   $o['search_invoice_date_start'] = CheckSessionVariable($o, 'search_invoice_date_start', "");
   $o['search_invoice_date_end'] = CheckSessionVariable($o, 'search_invoice_date_end', "");
   $o['order_tag'] = CheckSessionVariable($o, 'order_tag', 'ORDER BY PARTNER_NAME');

   $filter = BuildSearchFilter($o);

   $header[0] = array("align"=>"center", "width"=>"2%", "sort"=>"no", "title"=>"", "field"=>"");
   $header[1] = array("align"=>"left", "width"=>"10%", "sort"=>"yes", "title"=>"Status", "field"=>"armc_status_description");
   $header[2] = array("align"=>"left", "width"=>"6%", "sort"=>"yes", "title"=>"BR #", "field"=>"armc_id");
   $header[3] = array("align"=>"left", "width"=>"6%", "sort"=>"yes", "title"=>"Study ID", "field"=>"STUDY_ID");
   $header[4] = array("align"=>"left", "width"=>"18%", "sort"=>"yes", "title"=>"Project Name", "field"=>"STUDY_NAME");
   $header[5] = array("align"=>"left", "width"=>"18%", "sort"=>"yes", "title"=>"Partner Name", "field"=>"PARTNER_NAME");
   $header[6] = array("align"=>"center", "width"=>"8%", "sort"=>"yes", "title"=>"BR Date", "field"=>"created_date");
   $header[7] = array("align"=>"center", "width"=>"10%", "sort"=>"yes", "title"=>"Invoice Date", "field"=>"transaction_date");
   $header[8] = array("align"=>"right", "width"=>"6%", "sort"=>"yes", "title"=>"AM", "field"=>"account_manager");
   $header[9] = array("align"=>"right", "width"=>"6%", "sort"=>"yes", "title"=>"AE", "field"=>"account_executive");
   $header[10] = array("align"=>"right", "width"=>"10%", "sort"=>"yes", "title"=>"Amount", "field"=>"amount");

   echo ($o['order_tag']);
   //$filter = "a.armc_group_id=0";
   $rst = $atmDB->GetARMCList($filter, $o['order_tag']);
   $count = mysql_num_rows($rst);
   $rst = $atmDB->GetARMCList($filter, $o['order_tag'], "LIMIT ".($o['page']-1) *  $o['display_count'].", ".$o['display_count']);
   $page_count = ceil($count / $o['display_count']);

   while ($armc = mysql_fetch_assoc($rst)) {
      $armc['checkbox'] = "LLL"; //Change this to put the checkbox...
      if ($armc['grouped']=="true") {
         $grouped_armc_rst = $atmDB->GetGroupARMCList(" AND a.armc_group_id = ".$armc['armc_id'], $o['order_tag']);
         $armc['array'] = "new Array(-1,";
         while ($group_armc = mysql_fetch_assoc($grouped_armc_rst)) {
            $group_armc['checkbox'] = "LLL";
            $armc['array'] .= $group_armc['armc_id'].",";
            $armc['armc'][] = $group_armc;
         }
         $armc['array'] = substr($armc['array'], 0, strlen($armc['array'])-1).")";
      }
      $list[] = $armc;
   }

   $lists['armc_type'] = CreateSmartyArray($atmDB->GetARMCTypes(), 'armc_type_id', 'armc_type_description');
   $lists['armc_status'] = CreateSmartyArray($atmDB->GetARMCStatuses(), 'armc_status_id', 'armc_status_description');
   $lists['armc_account_executive'] = CreateSmartyArray($commonDB->GetUsersByRoleId(ROLE_ACCOUNT_EXECUTIVE), 'login', 'name');
   $lists['armc_account_manager'] = CreateSmartyArray($commonDB->GetUsersByRoleId(ROLE_ACCOUNT_MANAGER), 'login', 'name');
   $lists['display_count'] = array(1=>"1", 10=>"10", 30=>"30", 50=>"50", 100=>"100");

   for ($i=1; $i<=$page_count; $i++) {
      $pages[$i] = $i;
   }

   $smarty->assign("lang", $o['lbl']);
   $smarty->assign("meta", $o);
   $smarty->assign("lists", $lists);
   $smarty->assign("page_count", $page_count);
   $smarty->assign("pages", $pages);
   $smarty->assign("header", $header);
   $smarty->assign("list", $list);
   DisplayHeader();
   $smarty->display("app/atm/newarmc/vw_list.tpl");
   DisplayFooter();
}


/**
* DisplayHeader()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Nov 03 11:00:37 PST 2005
*/
function DisplayHeader()
{
   global $cfg,$userRights,$version,$build; //we need globals here for our configs and version stuff
   //variables
   $moduleName = "Billing Reports";
   $moduleReference = "armc";

   //displaying page
   require_once($cfg['base_dir'].'/include/header.inc.php');
   //require_once($cfg['base_dir'].'/include/rpt_functions.inc.php');

   return true;

}

/**
* DisplayFooter()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Nov 03 11:01:45 PST 2005
*/
function DisplayFooter()
{
   global $cfg;
   //displaying page
   require_once($cfg['base_dir'].'/include/footer.inc.php');

   return true;
}

?>
