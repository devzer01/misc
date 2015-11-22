<?php
session_start();

//do we have authentication
require_once($_SERVER['DOCUMENT_ROOT'].'/include/auth.inc');

//leave config file out of common
require_once($_SERVER['DOCUMENT_ROOT'].'/include/config.inc');

//all this includes should go on a common
require_once('common.inc');

//all pre defined constant
//require_once('constant.inc'); //Rmoved as per HB-402

//load functions
require_once('functions.inc');

//common parsing functions
require_once($_SERVER['DOCUMENT_ROOT'].'/include/common.inc');

//module specifc language
$o['m_lbl'] = $stm_lbl;

//if (IsEXTUser() && !ActionAllowedForEXT($o["action"])) {
//   header("Location: ");
//   exit;
//}

$functions = new armc();
//this is our main business logic decision tree
//$commonDB = new commonDB();
//$commonDB->debugPrint(print_r($o, true));
//print_r($o);
switch ($o['action']) {

	case 'add_armc' : {
      AddARMC($o);
	}break;

	case 'new_armc' : {
	   NewARMC($o);
	}break;

	case 'display_study_armcs' : {
	   DisplayStudyARMCs($o);
	}break;

	case 'add_comment' : {
	   AddComment($o);
	}break;

   case 'new_armc_comment' : {
      NewARMCComment($o);
   }break;
   
   case 'display_study_cost_comments': {
   	$atm_armc_billingmanager = new atm_armc_BillingManager();
		$atm_armc_billingmanager->SetParams($o);
		$atm_armc_billingmanager->ShowStudyCostComments($smarty);
   }break;

	case 'display_group_details' : {
		$atm_armc_billingmanager =new atm_armc_BillingManager();
		$atm_armc_billingmanager->SetParams($o);
		$atm_armc_billingmanager->DisplayGroupARMCDetails();
	   // DisplayGroupARMCDetails($o);
	}break;

	case 'save_group_contact' : {
	   //SaveGroupContact($o);
	  	$contact_manager	= new atm_armc_ContactsManager();
		$contact_manager->SetParams($o);
		$contact_manager->SaveGroupContact();
	}break;

	case 'save_group_invoice' : {
	   SaveGroupInvoice($o);
	}break;

	case 'display_armc_details': {
		$atm_armc_billingmanager =new atm_armc_BillingManager();
		$atm_armc_billingmanager->SetParams($o);
		$atm_armc_billingmanager->DisplayARMCDetails();
	}break;
	

	case 'refresh' : {
	    Refresh($o);
	}break;

	case 'save_approval' : {
		$functions->SetParams($o);
		$functions->SaveApproval();
	   //SaveApproval($o);
	}break;

	case 'save_invoice' : {
	   $functions->SetParams($o);
	   $functions->SaveInvoice();
	   //SaveInvoice($o);
	}break;
	
	case "save_study_cost_approvals" : {
		Hb_App_Billing_Manager_Approval::SaveStudyCostApprovals();
	}break;

	case 'save_lines' : {
      SaveLines($o);
	}break;

	case 'save_contact' : {
	   //SaveContact($o);
	   $contact_manager	= new atm_armc_ContactsManager();
		$contact_manager->SetParams($o);
		$contact_manager->SaveContact();
	}break;

	case 'display_armc_users' : {
	   DisplayARMCUsers($o);
	}break;

	case 'save_armc_users' : {
	   SaveARMCUsers($o);
	}break;

	case 'save_armc_group_description' : {
	   SaveARMCGroupDescription($o);
	}break;

	case 'save_armc' : {
	   SaveARMC($o);
	}break;

	case 'save_group' : {
		SaveARMCGroup($o);
	}break;

	case 'merge' : {
	   HandleMerge($o);
	}break;

	case 'unmerge' : {
	   Unmerge($o);
	}break;

	case 'display_report' : {
      DisplayReport($o);
	}break;

	case 'display_bulk_update' : {
	   DisplayBulkUpdate($o);
	}break;

	case 'batch_export' : {
	  HandleBatchExport();
	}break;

	case 'migrate' : {
	   ARMCMigrate($o);
	}break;

//	case "banner_report" : {
//	   DisplayBannerReport();
//	}break;
//
//	case "flash_report" : {
//	   DisplayFlashReport();
//	}break;

	case "summary_reports" : {
	   DisplayARMCSummaryReports($o);
	}break;

	case 'validate_account_search' : {
      ValidateAccountSearch($o);
	}break;

	case 'delayed_approval' : {
	   SendDelayedApprovalReport();
	}break;

	case 'display_stalled_report' : {
	   DisplayStalledReport($o);
	}break;

	case 'check_oracle_balances' : {
	   $functions->CheckOracleBalances();
	}break;

	case "check_oracle_invoices" : {
	   CheckOracleInvoices();
	}break;

	case 'TEST' : {
	   DoTest();
	}break;

   case 'get_salesrep_id' : {
      GetSalesrepByName($o);
   }break;

   case 'get_appian' : {
      $functions->GetAppianReport();
   }break;

   //implement the deleting part here
   case 'delete_additional_contact' :
   	//DeleteAdditionalContact($o);
		$contact_manager	= new atm_armc_ContactsManager();
		$contact_manager->SetParams($o);
		$contact_manager->DeleteAdditionalContacts();
   break;

   case 'display_account_contact':
		$contact_manager	= new atm_armc_ContactsManager();
		$contact_manager->SetParams($o);
		$contact_manager->DisplayAccountContacts();
		break;

   case 'save_additional_contacts':
     	$contact_manager	= new atm_armc_ContactsManager();
		$contact_manager->SetParams($o);
		$contact_manager->SaveAdditionalContacts();
		header('location:?action=display_armc_details&armc_id='.$o['armc_id']);
		break;

   case "get_armc_file": {
   	    $atm_armc_filemanager = new atm_armc_FileManager();
    	$atm_armc_filemanager->GetARMCFile($o["armc_file_id"]);
   }break;

   case "get_armc_group_file": {
    	$atm_armc_filemanager = new atm_armc_FileManager();
    	$atm_armc_filemanager->GetARMCGroupFile($o["armc_group_file_id"]);
   }break;
   
   case 'get_armc_group_file_by_id': {
   		//get_group_file_by_armc_id	
   		$atm_armc_filemanager = new atm_armc_FileManager();
			$atm_armc_filemanager->GetARMCGroupFileById($o['armc_id'],$o['armc_file_type_id']); 
   		//$atm_armc_filemanager->GetARMCGroupFileById($o['armc_id']);
   }break;
   
   
   case 'get_armc_file_by_id':{
   		//get_armc_file_by_armc_id
   		$atm_armc_filemanager = new atm_armc_FileManager();
   		//$atm_armc_filemanager->GetARMCFileById($o['armc_id']);
   		$atm_armc_filemanager->GetARMCFileById($o['armc_id'],$o['armc_file_type_id']); 
   }break;
    
   case 'save_study_cost_approval':{
   	echo "<pre>";
   	print_r($o);
   	echo "</pre>";
   	
   	die("Save cost here.");
   	
   }break;
   
   case 'do_test': {
   	   DoTest($o);
   } break;
   
	case 'default' :
		ARMCDefault($o);
		break;
		
	default : {
	   ARMCDefault($o);
	}break;
}


/* move the functions to functions.inc */
?>
