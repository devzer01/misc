<?php
require_once($cfg['base_dir'].'/common/constant.inc');
//Account manager role_id's
define('PRIMARY_ACCT_MGR',1);
define('OTHER_ACCT_MGR',2);
define('SECONDARY_ACCT_MGR',3);
define('PRIMARY_ACCT_EXEC',4);


/* Constants for different BR Types */
define('TYPE_STUDY',0);
define('TYPE_CREDIT_MEMO',1);
define('TYPE_RETAINER',2);
define('TYPE_LICENSE',100);
define('TYPE_TRAINING',900);
define('TYPE_SERVICE',3); //should be 300
define('TYPE_CREDIT_MEMO_NOSTUDY',10);

define('TYPE_CM_SERVICE',10); //30
define('TYPE_CM_LICENSE',10); //10
define('TYPE_CM_TRAINING',10); //90

define('TYPE_RT_SERVICE',230); //230
define('TYPE_RT_LICENSE',210); //210
define('TYPE_RT_TRAINING',290); //290

define('TYPE_BR_SOFTWARE',500);

/* New constants for BR types */
define("ARMC_TYPE_BR_STUDY", 1);
define("ARMC_TYPE_BR_SB", 2);
define("ARMC_TYPE_BR_LI", 3);
define("ARMC_TYPE_BR_TR", 4);
define("ARMC_TYPE_BR_OT", 5);
define("ARMC_TYPE_CM_STUDY", 6);
define("ARMC_TYPE_CM_SB", 7);
define("ARMC_TYPE_CM_LI", 8);
define("ARMC_TYPE_CM_TR", 9);
define("ARMC_TYPE_CM_OT", 10);
define("ARMC_TYPE_RT_STUDY", 11);
define("ARMC_TYPE_RT_SB", 12);
define("ARMC_TYPE_RT_LI", 13);
define("ARMC_TYPE_RT_TR", 14);
define("ARMC_TYPE_RT_OT", 15);
define("ARMC_TYPE_VAP_BR", 201);
define("ARMC_TYPE_VAP_CM", 206);
define("ARMC_TYPE_VAP_RT", 211);
define("ARMC_TYPE_ERS_BR", 501);
define("ARMC_TYPE_ERS_CM", 506);
define("ARMC_TYPE_ERS_RT", 511);
define("ARMC_TYPE_RSA_BR", 601);
define("ARMC_TYPE_RSA_CM", 606);
define("ARMC_TYPE_RSA_RT", 611);
define("ARMC_TYPE_ISS_BR", 801);
define("ARMC_TYPE_ISS_CM", 806);
define("ARMC_TYPE_ISS_RT", 811);
define("ARMC_TYPE_EFP_BR", 901);
define("ARMC_TYPE_EFP_CM", 906);
define("ARMC_TYPE_EFP_RT", 911);
define("ARMC_TYPE_EFS_BR", 1001);
define("ARMC_TYPE_EFS_CM", 1006);
define("ARMC_TYPE_EFS_RT", 1011);

/* Constants for Status Messages */
define('BR_OPEN',0);
define('BR_ENTRY',1);
define('BR_WAIT',4);
define('BR_MANUAL_INVOICE',5);
define('BR_READY_EXPORT',10);
define('BR_ROLLBACK_AM',40);
define('BR_ROLLBACK_AE',41);
define('BR_REVIEW_AM',51);
define('BR_REVIEW_AE',52);
define('BR_REVIEW_ACCT',53);
define('BR_INVOICED',20);
define('BR_STALLED',100);

/*New status constants*/
define("ARMC_STATUS_EDIT", 1);
define("ARMC_STATUS_AM_APPROVAL", 5);
define("ARMC_STATUS_AE_APPROVAL", 6);
define("ARMC_STATUS_ACCT_APPROVAL", 7);
define("ARMC_STATUS_READY_FOR_INVOICE", 10);
define("ARMC_STATUS_INVOICED_MANUALLY", 11);
define("ARMC_STATUS_INVOICED", 12);
define("ARMC_STATUS_INVOICED_PARTIAL_BALANCE", 13);
define("ARMC_STATUS_INVOICED_BALANCED", 14);
define("ARMC_STATUS_STALLED", 20);
define("ARMC_STATUS_DELETED", 100);

/* Constants for event types */
define("ARMC_EVENT_TYPE_CREATED", 1);
define("ARMC_EVENT_TYPE_VIEWED", 2);
define("ARMC_EVENT_TYPE_STALLED", 3);
define("ARMC_EVENT_TYPE_DELETED", 4);
define("ARMC_EVENT_TYPE_SAVED_FOR_LATER", 5);
define("ARMC_EVENT_TYPE_SAVED_FOR_APPROVAL", 6);
define("ARMC_EVENT_TYPE_UPDATED_BILLING_CONTACT", 7);
define("ARMC_EVENT_TYPE_UPDATED_PROJECT_CONTACT", 8);
define("ARMC_EVENT_TYPE_UPDATED_INVOICE_DETAILS", 9);
define("ARMC_EVENT_TYPE_AM_APPROVED_HEADER", 10);
define("ARMC_EVENT_TYPE_AM_DENIED_HEADER", 11);
define("ARMC_EVENT_TYPE_AM_APPROVED_INVOICE", 14);
define("ARMC_EVENT_TYPE_AM_DENIED_INVOICE", 15);
define("ARMC_EVENT_TYPE_AM_APPROVED_LINE", 16);
define("ARMC_EVENT_TYPE_AM_DENIED_LINE", 17);
define("ARMC_EVENT_TYPE_STATUS_AM_APPROVAL", 19);
define("ARMC_EVENT_TYPE_AE_APPROVED_HEADER", 20);
define("ARMC_EVENT_TYPE_AE_DENIED_HEADER", 21);
define("ARMC_EVENT_TYPE_AE_APPROVED_INVOICE", 24);
define("ARMC_EVENT_TYPE_AE_DENIED_INVOICE", 25);
define("ARMC_EVENT_TYPE_AE_APPROVED_LINE", 26);
define("ARMC_EVENT_TYPE_AE_DENIED_LINE", 27);
define("ARMC_EVENT_TYPE_STATUS_AE_APPROVAL", 29);
define("ARMC_EVENT_TYPE_ACCT_APPROVED_HEADER", 30);
define("ARMC_EVENT_TYPE_ACCT_DENIED_HEADER", 31);
define("ARMC_EVENT_TYPE_ACCT_APPROVED_INVOICE", 34);
define("ARMC_EVENT_TYPE_ACCT_DENIED_INVOICE", 35);
define("ARMC_EVENT_TYPE_ACCT_APPROVED_LINE", 36);
define("ARMC_EVENT_TYPE_ACCT_DENIED_LINE", 37);
define("ARMC_EVENT_TYPE_STATUS_ACCT_APPROVAL", 39);
define("ARMC_EVENT_TYPE_INVOICED_MANUALLY", 40);
define("ARMC_EVENT_TYPE_INVOICED", 41);
define("ARMC_EVENT_TYPE_BALANCED_PARTIAL", 45);
define("ARMC_EVENT_TYPE_BALANCED_FULL", 46);
define("ARMC_EVENT_TYPE_STATUS_READY", 49);
define("ARMC_EVENT_TYPE_REFRESH", 50);
define("ARMC_EVENT_TYPE_CHANGED_USERS", 51);
define("ARMC_EVENT_TYPE_CHANGED_DATE", 52);
define("ARMC_EVENT_TYPE_STATUS_EDIT", 59);
define("ARMC_EVENT_TYPE_COMMENTED", 61);
define("ARMC_EVENT_TYPE_COMMENTED_LINE", 62);
define("ARMC_EVENT_TYPE_MERGED", 71);
define("ARMC_EVENT_TYPE_UNMERGED", 72);
define("ARMC_EVENT_TYPE_CORRECT", 90);
define("ARMC_EVENT_TYPE_REACTIVATED", 100);

define("ARMC_EVENT_TYPE_ADD_CONTACT", 101);
define("ARMC_EVENT_TYPE_DELETE_CONTACT", 102);

/* Constant for Approval Action types */
define("ARMC_ACTION_TYPE_AM_APPROVED_HEADER", 1);
define("ARMC_ACTION_TYPE_AM_APPROVED_INVOICE", 3);
define("ARMC_ACTION_TYPE_AM_APPROVED_LINE", 4);
define("ARMC_ACTION_TYPE_AE_APPROVED_HEADER", 11);
define("ARMC_ACTION_TYPE_AE_APPROVED_INVOICE", 13);
define("ARMC_ACTION_TYPE_AE_APPROVED_LINE", 14);
define("ARMC_ACTION_TYPE_ACCT_APPROVED_HEADER", 21);
define("ARMC_ACTION_TYPE_ACCT_APPROVED_INVOICE", 23);
define("ARMC_ACTION_TYPE_ACCT_APPROVED_LINE", 24);

/* Constants for Comment types */
define("ARMC_COMMENT_TYPE_AM_DENIED_HEADER", 1);
define("ARMC_COMMENT_TYPE_AM_ROLLBACK_HEADER", 2);
define("ARMC_COMMENT_TYPE_AE_DENIED_HEADER", 3);
define("ARMC_COMMENT_TYPE_AE_ROLLBACK_HEADER", 4);
define("ARMC_COMMENT_TYPE_ACCT_DENIED_HEADER", 5);
define("ARMC_COMMENT_TYPE_ACCT_ROLLBACK_HEADER", 6);
define("ARMC_COMMENT_TYPE_AM_DENIED_INVOICE", 11);
define("ARMC_COMMENT_TYPE_AM_ROLLBACK_INVOICE", 12);
define("ARMC_COMMENT_TYPE_AE_DENIED_INVOICE", 13);
define("ARMC_COMMENT_TYPE_AE_ROLLBACK_INVOICE", 14);
define("ARMC_COMMENT_TYPE_ACCT_DENIED_INVOICE", 15);
define("ARMC_COMMENT_TYPE_ACCT_ROLLBACK_INVOICE", 16);
define("ARMC_COMMENT_TYPE_DELETE", 21);
define("ARMC_COMMENT_TYPE_STALL", 22);
define("ARMC_COMMENT_TYPE_REACTIVATE", 23);
define("ARMC_COMMENT_TYPE_CORRECT", 24);
define("ARMC_COMMENT_TYPE_INVOICE_MEMO", 31);
define("ARMC_COMMENT_TYPE_GENERAL", 41);
define("ARMC_COMMENT_TYPE_DELETE_LINE", 101);
define("ARMC_COMMENT_TYPE_EDIT_LINE", 102);
define("ARMC_COMMENT_TYPE_AM_DENIED_LINE", 103);
define("ARMC_COMMENT_TYPE_AE_DENIED_LINE", 104);
define("ARMC_COMMENT_TYPE_ACCT_DENIED_LINE", 105);
define("ARMC_COMMENT_TYPE_GENERAL_LINE", 121);


/* Constants for Contact Types */
define("ARMC_CONTACT_TYPE_BILLING", 1);
define("ARMC_CONTACT_TYPE_PROJECT", 2);

define('STUDY_CONTACT_PROJECT_MANAGER', 1);
define('STUDY_CONTACT_BILLING', 2);

/* added on 21st May 2007 under HB-260*/
define("ARMC_CONTACT_TYPE_ADDITIONAL_BILLING", 3);

/* Constants for ATM message types */
define("ATM_MESSAGE_TYPE_AM_APPROVAL", 11011);
define("ATM_MESSAGE_TYPE_AE_APPROVAL", 11012);
define("ATM_MESSAGE_TYPE_ACCT_APPROVAL", 11013);
define("ATM_MESSAGE_TYPE_AM_APPROVAL_ESCALATION", 11021);
define("ATM_MESSAGE_TYPE_AE_APPROVAL_ESCALATION", 11022);
define("ATM_MESSAGE_TYPE_ACCT_APPROVAL_ESCALATION", 11023);
define("ATM_MESSAGE_TYPE_ORACLE_MANUAL_EXPORT", 11101);
define("ATM_MESSAGE_TYPE_ORACLE_BATCH_EXPORT", 11102);
define("ATM_MESSAGE_TYPE_ORACLE_CHECK_BALANCES", 11111);
define("ATM_MESSAGE_TYPE_DELAYED_AM_APPROVAL", 11201);
define("ATM_MESSAGE_TYPE_DELAYED_STALLED", 11202);
define("ATM_MESSAGE_TYPE_INVOICED_BY_AM", 11203);
define("ATM_MESSAGE_TYPE_FLASH_DELAYED_AM_APPROVAL", 11501);
define("ATM_MESSAGE_TYPE_FLASH_DELAYED_STALLED", 11502);

/* Constants for Portlet IDs */
define("ATM_PORTLET_RECENT", 11001);
define("ATM_PORTLET_MTD", 11011);
define("ATM_PORTLET_MTD_DETAILED", 11021);
define("ATM_PORTLET_APPROVAL", 11031);

/* Special constant for Retainer Budget Line Item Def IDs */
define("ARMC_BUDGET_LINE_DEF_RETAINER", 1783);

/* Constans for SAM types */
define("SAM_TYPE_FORECAST", 1);
define("SAM_TYPE_QUOTA", 11);
define("SAM_TYPE_INVOICED_STUDY", 21);
define("SAM_TYPE_INVOICED_SB", 22);
define("SAM_TYPE_INVOICED_LI", 23);
define("SAM_TYPE_INVOICED_TR", 24);
define("SAM_TYPE_INVOICED_OT", 25);


/* Constants for Roles */
define("ROLE_PRIMARY_ACCT_MGR", 1);
define("ROLE_PROJECT_MANAGER", 2);
define("ROLE_PRIMARY_ACCT_EXEC", 4);
define("ROLE_SECONDARY_ACCT_EXEC", 5);
define("ROLE_SECONDARY_ACCT_MGR", 3);
define("ROLE_PRIMARY_PROJECT_MANAGER", 8);
define("ROLE_SECONDARY_PROJECT_MANAGER", 9);
define("ROLE_ACCOUNT_MANAGER", 12);
define("ROLE_ACCOUNT_EXECUTIVE", 13);
define("ROLE_ACCOUNTING", 17);
define("ROLE_CREATOR", 24);

/* Constants For Version 2.0 */
define('MAIN_BR',0);
define('MAIN_CREDIT_MEMO',1);
define('MAIN_RETAINER',2);

define('NUM_BUDGET_LINES',10); //number of budget lines to display
define("ARMC_APPROVAL_ESCALATION_THRESHOLD", 1);

define('SINGLE_MERGE',1);
define('MERGED_MERGE',2);

define('BR_PRELIMINARY',1);
define('BR_POST',0);

define('BUDGET_ID_OMNIBUS',1781);

//Study related constants
define('PRIMARY_ACCT_MGR',1);
define('STUDY_ALERT_GREEN',1);
define("STUDY_CLOSED", 2);


/* status messages */
$statusMessage = array();
$statusMessage[BR_OPEN] = "Open";
$statusMessage[BR_ENTRY] =  "Waiting Data Entry";
$statusMessage[BR_WAIT] = "Waiting For Approval";
$statusMessage[BR_MANUAL_INVOICE] = "Invoiced - Manually";
$statusMessage[BR_READY_EXPORT] = "Ready To Export";
$statusMessage[BR_ROLLBACK_AM] = "Rolled Back to Account Manager";
$statusMessage[BR_ROLLBACK_AE] = "Rolled Back to Account Executive";
$statusMessage[BR_REVIEW_AM] = "Waiting AM Approval";
$statusMessage[BR_REVIEW_AE] = "Waiting AE Approval";
$statusMessage[BR_REVIEW_ACCT] = "Waiting ACCT Approval";
$statusMessage[BR_INVOICED] = "Invoiced";
$statusMessage[BR_STALLED] = "Stalled By User";

/* definitions for each BR type */
$brAttr[TYPE_STUDY]['reqStudy']   = 1;
$brAttr[TYPE_STUDY]['reqPartner'] = 1;

define ('COMMON_REPORTING_TYPE_FICTIONAL', 3);


global $invoiceTypeArray;
$invoiceTypeArray = array();
$invoiceTypeArray[TYPE_LICENSE]             = "License Invoice";
$invoiceTypeArray[TYPE_STUDY]               = "Net-MR Study";
$invoiceTypeArray[TYPE_TRAINING]            = "Training Invoice";
$invoiceTypeArray[TYPE_CREDIT_MEMO]         = "Credit Memo per Study";
$invoiceTypeArray[TYPE_RETAINER]            = "Retainer For Study";
$invoiceTypeArray[TYPE_SERVICE] 		        = "Service Beuro Work";
$invoiceTypeArray[TYPE_CREDIT_MEMO_NOSTUDY] = "Credit Memo Non Study";
$invoiceTypeArray[TYPE_BR_OTHER] 			  = "Other";


define('RCPT','jvonica@gmi-mr.com');
define('SUBJECT','New item in quickbooks');
define('FROM','orabatch@gmi-mr.com');


/* for calculating quators */
$_Q = array();
global $_Q;
$_Q[1]['begin'] = 1;
$_Q[1]['end'] = 3;
$_Q[2]['begin'] = 4;
$_Q[2]['end'] = 6;
$_Q[3]['begin'] = 7;
$_Q[3]['end'] = 9;
$_Q[4]['begin'] = 10;
$_Q[4]['end'] = 12;


$main_tbl1_header = Array(
	Array("title"=>"&nbsp;", "field"=>"", "width"=>"2%", "align"=>"center", "sort"=>"no"),
	Array("title"=>"Status", "field"=>"status", "width"=>"10%", "align"=>"left", "sort"=>"no"),
	Array("title"=>"Merge BR#", "field"=>"m_id", "width"=>"8%", "align"=>"left"),
	Array("title"=>"Merged Studies", "field"=>"studies", "width"=>"10%", "align"=>"left", "sort"=>"no"),
	Array("title"=>"Project Name", "field"=>"studyname", "width"=>"22%", "align"=>"left"),
	Array("title"=>"Partner Name", "field"=>"company_name", "width"=>"18%", "align"=>"left"),
	Array("title"=>"Merge Date", "field"=>"m_date", "width"=>"8%", "align"=>"center"),
	Array("title"=>"AM", "field"=>"am", "width"=>"7%", "align"=>"right"),
	Array("title"=>"AE", "field"=>"ae", "width"=>"7%", "align"=>"right"),
	Array("title"=>"Total", "field"=>"total", "width"=>"8%", "align"=>"right"));

$main_tbl2_header = Array(
	Array("title"=>"&nbsp;", "field"=>"", "width"=>"2%", "align"=>"center", "sort"=>"no"),
	Array("title"=>"Status", "field"=>"br_status", "width"=>"10%", "align"=>"left", "sort"=>"no"),
	Array("title"=>"BR#", "field"=>"br_id", "width"=>"8%", "align"=>"left"),
	Array("title"=>"Study ID", "field"=>"study_id", "width"=>"10%", "align"=>"left"),
	Array("title"=>"Project Name", "field"=>"studyname", "width"=>"22%", "align"=>"left"),
	Array("title"=>"Partner Name", "field"=>"company_name", "width"=>"18%", "align"=>"left"),
	Array("title"=>"Date", "field"=>"br_date", "width"=>"8%", "align"=>"center"),
	Array("title"=>"AM", "field"=>"acct_mgr", "width"=>"7%", "align"=>"right"),
	Array("title"=>"AE", "field"=>"acct_exec", "width"=>"7%", "align"=>"right"),
	Array("title"=>"Total", "field"=>"total", "width"=>"8%", "align"=>"right"));
?>
