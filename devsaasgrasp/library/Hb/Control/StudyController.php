<?php
/**
 *  @version  2.0.alpha
 *  @author jvonica
 */
require_once 'Zend/Controller/Action.php';

class StudyController extends Zend_Controller_Action
{

    private $bind_map = array();

    /**
     * Sets study cost parameters with input from the request instance
     *
     * @param int $study_cost_id The study_cost_id for which we're setting the parameters
     */
    protected function doSetStudyCostParamFromRequest (Hb_App_Study_StudyCost $study_cost)
    {
        $request = Hb_Util_Request_Request::GetInstance();
        
        $study_cost_id = $study_cost->GetStudyCostId();
        
        $study_cost->SetStudyCostTypeId($request->getParam("study_cost_type_id_" . $study_cost_id));
        $study_cost->SetReferenceNumber($request->getParam("reference_number_" . $study_cost_id));
        
        $acct = explode("//", $request->getParam("account_" . $study_cost_id));
        $study_cost->SetAccountId($acct[0]);
        $study_cost->SetAccountName($acct[1]);
        
        $study_cost->SetContactName($request->getParam("contact_name_" . $study_cost_id));
        $study_cost->SetContactEmail($request->getParam("contact_email_" . $study_cost_id));
        
        $study_cost->SetProposedRate($request->getParam("proposed_rate_" . $study_cost_id));
        $study_cost->SetProposedQuantity($request->getParam("proposed_quantity_" . $study_cost_id));
        $study_cost->SetActualRate($request->getParam("actual_rate_" . $study_cost_id));
        $study_cost->SetActualQuantity($request->getParam("actual_quantity_" . $study_cost_id));
        $study_cost->SetInvoiceRate($request->getParam("invoice_rate_" . $study_cost_id));
        $study_cost->SetInvoiceQuantity($request->getParam("invoice_quantity_" . $study_cost_id));
    

    }

    /**
     * Adds a new study cost item with input from the request instance
     * 
     * 
     */
    protected function doAddNewStudyCostFromRequest ()
    {
        $request = Hb_Util_Request_Request::GetInstance();
        
        $acct = explode("//", $request->getParam("account"));
        
        $study_cost = new Hb_App_Study_StudyCost(null, $acct[0], $acct[1], $request->getParam("actual_quantity"), 
            $request->getParam("actual_rate"), $request->getParam("proposed_quantity"), $request->getParam("proposed_rate"), 
            $request->getParam("contact_email"), $request->getParam("contact_name"), $request->getParam("reference_number"), 
            $request->getParam("study_cost_type_id"), $request->getParam("study_id"), "PO" . Hb_App_Study_Manager_Cost::NextPONumber(), "A", 
            $request->getParam("invoice_rate"), $request->getParam("invoice_quantity"));
        
        // if the study has invoiced , create a alert message
        if ($study_cost->GetStudy()->IsInvoiceDateValid()) {
            $this->doSendNewStudyCostAlert($study_cost);
        }    	
        
        if ($request->hasFile("study_cost_file")) {
            $this->doSaveNewStudyCostFile($study_cost);
        }
        
        //if the Invoice Amount is not equal to 0 *and* the invoice file is attached then check 
        //if Manager approval for invoice payment is required (and not already approved or rejected) then trigger an alert. 
		  if ($study_cost->IsInvoiceApprovalRequired()) {
		  		$this->doSendInvoiceApprovalAlert($study_cost);
		  } 
        $this->doAddStudyCostCommentFromRequest($study_cost);
    }
    
    /**
     * Sends a new study cost alert if a study cost is added to an already invoiced/closed study
     * 
     * @param Hb_App_Study_StudyCost $study_cost Study cost object
     * @todo: this should be part of a studyCom component
     */
    protected function doSendNewStudyCostAlert(Hb_App_Study_StudyCost $study_cost)
    {
        $view = new Hb_View_Study_NewStudyCostEnteredForInvoicedStudy();
            
        $view->SetStudy($study_cost->GetStudy());
        $view->SetStudyCost($study_cost);
            
        $msg = $view->Fetch();
            
        $subject = 'An unaccounted for study cost has been entered for the following closed and invoiced study';

        $attr = $this->doGetStudyAlertAttr($study_cost);

        $rcpt = array();
            
        $message = array('body' => $msg , 'subject' => $subject);
            
        //sending alert, STM_MESSAGE_TYPE_STUDY_ALREADY_INVOICED = 18117 	
            
        Hb_Util_Messaging_Que::QueMessage(STM_MESSAGE_TYPE_STUDY_ALREADY_INVOICED, $message, $rcpt, $attr);
    }

    /**
     * Returns a study alert attributes
     * 
     * @param object $study_cost Study cost object
     * @todo Explictly define the type of $study_cost to be Hb_Study_StudyCost  
     * @return array The study cost attributes
     */
    protected function doGetStudyAlertAttr ($study_cost)
    {        
        $config = Hb_Util_Config_SystemConfigReader::Read();
        
        $cfg['base_dir'] = $config->base_dir;
        
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/include/config.inc');
        require_once ($cfg['base_dir'] . '/class/dbConnect.php');
        require_once ($cfg['base_dir'] . '/class/dbClass/studyDB.class');

        $stbDb = new studyDB();
        $stbDb->_study = $study_cost->GetStudy()->GetStudyId();
        $role_ae = $stbDb->getRole(PRIMARY_ACCT_EXEC);
        $role_am = $stbDb->getRole(PRIMARY_ACCT_MGR);
        
        return array('primary_ae_id' => $role_ae , 
                      'primary_am_id' => $role_am , 
                      'invoice_amount' => $study_cost->GetInvoiceAmount() , 
        			  'actual_cost' => $study_cost->GetActualCost() , 
        			  'proposed_cost' => $study_cost->GetProposedCost() , 
        			  'study_cost_type_id' => $study_cost->GetStudyCostTypeId() , 
        			  'account_id' => $study_cost->GetStudy()->GetPartnerId() , 
        			  'study_id' => $study_cost->GetStudy()->GetStudyId());
    }

    /**
     * Saves a study cost file for a new study cost
     * 
     * @param object $study_cost Study cost object
     * @todo Explictly define the type of $study_cost to be Hb_Study_StudyCost      
     */
    protected function doSaveNewStudyCostFile ($study_cost)
    {
        $request = Hb_Util_Request_Request::GetInstance();
        
        $file = $request->getFile("study_cost_file");
        $file_title = ($request->getParam("study_cost_file_title") != "") ? $request->getParam("study_cost_file_title") : $file->getFileName();
        
        $f = new Hb_App_Common_File(null, $file->getFileTypeId(), $file->getFileName(), $file_title, $file->getFileSize());
        $f->SetFileData($file->getFileData());
        
        $study_cost->SetStudyCostFile(new Hb_App_Study_StudyCostFile(null, $request->getParam("study_id"), $study_cost->GetStudyCostId(), $f, $request->getParam("file_type")));
    }

    /**
     * Return a Study instance object for the request's study_id
     * 
     * @return Hb_App_Study_Study An instance of a Study object
     */
    protected function GetStudyFromRequest ()
    {
        $request = Hb_Util_Request_Request::GetInstance();
        
        return Hb_App_ObjectHelper::GetMapper("Hb_App_Study_Study")->Find($request->getParam("study_id"));
    }

    /**
     * Saves a study cost file for an existing study cost
     * 
     * @param object $study_cost An instance of a study cost object
     * @todo Some of the code is duplicated with doSaveNewStudyCostFile() and therefore see if we can refactor this code into 1 single function. Also explictly define the type of $study_cost to be Hb_Study_StudyCost           
     * 
     */
    protected function doSaveStudyCostFile ($study_cost)
    {
        $request = Hb_Util_Request_Request::GetInstance();
        
        $file = $request->getFile("study_cost_file_" . $study_cost->GetStudyCostId());
        
        $file_title = $file->getFileName();
        
        if ($request->getParam("study_cost_file_title_" . $study_cost->GetStudyCostId()) != "") {
            $file_title = $request->getParam("study_cost_file_title_" . $study_cost->GetStudyCostId());
        }
        
        $f = new Hb_App_Common_File(null, $file->getFileTypeId(), $file->getFileName(), $file_title, $file->getFileSize());
        
        $f->SetFileData($file->getFileData());
        

        $study_cost->SetStudyCostFile(new Hb_App_Study_StudyCostFile(null, $request->getParam("study_id"), $study_cost->GetStudyCostId(), $f, $request->getParam("file_type_" . $study_cost->GetStudyCostId())));
    

    }

    /**
     * Send an alert when an study cost invoice is approved or rejected for an already invoiced study 
     * 
     * @param object $study_cost An instance of a study cost object
     * @todo Explictly define the type of $study_cost to be Hb_Study_StudyCost           
     */
    protected function doSaveAlertMessageApprovedOrRejected ($study_cost)
    {
        $request = Hb_Util_Request_Request::GetInstance();
        $study = $study_cost->GetStudy();
        
        //if the study cost approved or rejected , trigger an alert message here 
        $approved_or_rejected_view = new Hb_View_Study_NewStudyCostApprovedOrRejected();
        
        $user = Hb_App_ObjectHelper::GetMapper('Hb_App_User_User')->FindByLogin($_SESSION['admin_id']);
        $approved_or_rejected_view->SetActionType($request->getParam("approval_" . $study_cost->GetStudyCostId()));
        $approved_or_rejected_view->SetStudy($study);
        $approved_or_rejected_view->SetStudyCost($study_cost);
        $approved_or_rejected_view->SetUser($user);
        
        $approved_rejected_msg = $approved_or_rejected_view->Fetch();
        
        $action_type = 'Approved ';
        
        if ($request->getParam("approval_" . $study_cost->GetStudyCostId()) == ARMC_ACTION_TYPE_REJECTED_INVOICE)
            $action_type = 'Rejected ';
        
        $sub_approved_rejected = 'The following vendor invoice has been ' . $action_type . ' for payment by ' . $user->GetFirstName() . ' ' . $user->GetLastName();
        
        $attr = $this->doGetStudyAlertAttr($study_cost);
        
        $rcpt = array();
        
        $message_approved_rejected = array('body' => $approved_rejected_msg , 'subject' => $sub_approved_rejected);
        //sending alert, STM_MESSAGE_TYPE_STUDY_COST_APPROVED_OR_REJECTED = 18118 	
        Hb_Util_Messaging_Que::QueMessage(STM_MESSAGE_TYPE_STUDY_COST_APPROVED_OR_REJECTED, $message_approved_rejected, $rcpt, $attr);
    }

    /**
     * Sends an alert when a new study cost is entered.
     * 
     * @param object $study_cost An instance of a study cost object
     * @todo Explictly define the type of $study_cost to be Hb_Study_StudyCost           
     * 
     */
    protected function doSendInvoiceApprovalAlert ($study_cost)
    {   
        //load the study object
        $study = $study_cost->GetStudy();
        
        $view = new Hb_View_Study_StudyCostPaymentApprovalRequired();
        
        $user = Hb_App_ObjectHelper::GetMapper('Hb_App_User_User')->FindByLogin($_SESSION['admin_id']);
        
        $view->SetStudy($study);
        $view->SetStudyCost($study_cost);
        $view->SetUser($user);
        
        $msg = $view->Fetch();
        
        $subject = 'The Study Cost Payment Needs Approval';
        
        $attr = $this->doGetStudyAlertAttr($study_cost);
        
        $rcpt = array();     
        
        $message = array('body' => $msg , 'subject' => $subject);
        //sending alert, STM_MESSAGE_TYPE_PAYMENT_APPROVAL_REQUIRED = 18119
        Hb_Util_Messaging_Que::QueMessage(STM_MESSAGE_TYPE_PAYMENT_APPROVAL_REQUIRED, $message, $rcpt, $attr);
    }

    /**
     * Saves a study cost
     * 
     */
    public function SaveStudyCostsAction ()
    {
        
        $request = Hb_Util_Request_Request::GetInstance();
        if (! $request->hasParam("study_id")) {
            return false;
        }
        
        $study = $this->GetStudyFromRequest();
        /* @var $study Hb_App_Study_Study */
        $study_costs = $study->GetStudyCosts();
        
        foreach ($study_costs as $study_cost) {
            
			/* @var $study_cost Hb_App_Study_StudyCost */
            $study_cost_id = $study_cost->GetStudyCostId();
            
            if (!$request->hasParam("study_cost_type_id_" . $study_cost_id)) {
                continue;
            }
           
            $this->doUpdateCostChangeCommentFromRequest($study_cost);
                
            if ($request->getParam("study_cost_type_id_" . $study_cost_id) == 0) {
                $study_cost->SetStatus("D");
                continue;
            }
                
            if ($request->hasFile("study_cost_file_" . $study_cost_id)) {
                $this->doSaveStudyCostFile($study_cost);
            }
                
            //Trigger an alert when the study cost is approved or rejected		                
            if(($request->getParam("approval_" . $study_cost_id) == ARMC_ACTION_TYPE_APPROVED_INVOICE) || 
            	($request->getParam("approval_" . $study_cost_id) == ARMC_ACTION_TYPE_REJECTED_INVOICE)){
            		if(!($study_cost->IsInvoiceApproved() || $study_cost->IsInvoiceRejected())){            			
            			$this->doSetCostActionAndSaveAlertMessage($study_cost);
            			
            		}elseif(($study_cost->IsInvoiceApproved() && $request->getParam("approval_" . $study_cost_id) == ARMC_ACTION_TYPE_REJECTED_INVOICE) || 
                  	 ($study_cost->IsInvoiceRejected() && $request->getParam("approval_" . $study_cost_id) == ARMC_ACTION_TYPE_APPROVED_INVOICE)) {                  	 	
                  	 	$this->doSetCostActionAndSaveAlertMessage($study_cost);
                  }            	
            }         
            
            if ($study_cost->IsInvoiceApprovalRequired()) {
                $this->doSendInvoiceApprovalAlert($study_cost);
            }                
            
            $this->doSetStudyCostParamFromRequest($study_cost);
        }
        $this->_forward("AddStudyCost");
    }
    
	protected function doSetCostActionAndSaveAlertMessage($study_cost)
	{
		$request = Hb_Util_Request_Request::GetInstance();
		 
		$study_cost->SetCostAction($request->getParam("approval_" . $study_cost->GetStudyCostId()));
		$this->doSaveAlertMessageApprovedOrRejected($study_cost);
		
	}

    /**
     * Adds a new study cost
     *      
     */
    public function AddStudyCostAction ()
    {
        $request = Hb_Util_Request_Request::GetInstance();
        
        if (! $request->hasParam("study_id")) {
            return false;
        }
        
        if($request->hasParam('study_cost_type_id')){
	        if ($request->getParam("study_cost_type_id") !== "") {	        	  
	        	
	            $this->doAddNewStudyCostFromRequest();
	        }
        }       
        
        $this->getResponse()->setRedirect("/app/stm/?e=" . Hb_Util_Encryption_Encryption::GetInstance()->Encrypt("action=vw_detail&study_id=" . $request->getParam("study_id") . "&display_sop=1") . "#study_cost");
    }

    /**
     * Saves Study Cost approval
     * 
     */
    public function SaveStudyCostsApprovalsAction ()
    {
        $request = Hb_Util_Request_Request::GetInstance();
        
        if (! ($request->hasParam("study_id")) || ! ($request->hasParam("armc_id"))) {
            $this->getResponse()->setRedirect("/app/atm/armc/");
            return false;
        }
        
        $study = $this->GetStudyFromRequest();
        $costs = $study->GetStudyCosts();
        foreach ($costs as $cost) {
            $study_cost_id = $cost->GetStudyCostId();
            if ($request->hasParam("study_cost_id_" . $study_cost_id)) {
                $cost->SetCostAction($request->getParam("study_cost_action_" . $study_cost_id));
                $cost->AddCostRejectedComment($request->getParam("study_cost_comment_" . $study_cost_id));
            }
        }
        
        $this->getResponse()->setRedirect("/app/atm/armc/?e=" . Hb_Util_Encryption_Encryption::GetInstance()->Encrypt("action=display_armc_details&armc_id=" . $request->getParam("armc_id") . "&dont_log_view=1"));
        return true;
    }

    /**
     * Adds a new study cost comment. 
     * 
     * @param object $study_cost An instance of a study cost object
     * @todo Explictly define the type of $study_cost to be Hb_Study_StudyCost           
     * 
     * 
     */
    protected function doAddStudyCostCommentFromRequest ($study_cost)
    {
        $comment = 'Added new study cost.<br>Initial Values are';
        
        $comment .= '<br>Contact Name : ' . $study_cost->GetContactName();
        $comment .= '<br>Contact Email :' . $study_cost->GetContactEmail();
        $comment .= '<br>Proposed Rate :' . $study_cost->GetProposedRate();
        $comment .= '<br>Proposed Quantity :' . $study_cost->GetProposedQuantity();
        $comment .= '<br>Actual Rate :' . $study_cost->GetActualRate();
        $comment .= '<br>Actual Quantity :' . $study_cost->GetActualQuantity();
        $comment .= '<br>Proposed Cost :' . $study_cost->GetProposedCost();
        $comment .= '<br>Actual Cost :' . $study_cost->GetActualCost();
        
        $study_cost->AddCostNewComment($comment);
    }

    /**
     * Logs a study cost change - adds it as a Study Cost Comment
     * 
     * @param Hb_App_Study_StudyCost $study_cost Study cost object    
     */
    protected function doUpdateCostChangeCommentFromRequest (Hb_App_Study_StudyCost $study_cost)
    {
        $request = Hb_Util_Request_Request::GetInstance();
        
        $changes_done = '';
        
        $fields = array(
        	'contact_name'      => 'Contact Name',
            'contact_email'     => 'Contact Email',
            'proposed_rate'     => 'Proposed Rate',
            'proposed_quantity' => 'Proposed Quantity',
            'actual_rate'       => 'Actual Rate',
            'actual_quantity'   => 'Actual Quantity',
            'proposed_cost'     => 'Proposed Cost',
            'actual_cost'       => 'Actual Cost'
        );
        
        $study_cost_id = $study_cost->GetStudyCostId();
        
        foreach ($fields as $field => $desc) {
            
            $old_field     = $request->getParam('old_' . $field . '_' . $study_cost_id);
            $current_field = $request->getParam( $field . '_' . $study_cost_id);
            
            if ($old_field != $current_field) {
                $changes_done .= '<br>' . $desc . 'changed from <i>' . $old_field . '</i> to <i>' . $current_field . '</i>';
            }
            
        }

        $comment = $request->getParam("study_cost_comment_" . $study_cost_id) . $changes_done;

        $study_cost->AddCostChangeComment($comment);
    }

    /**
     * Displays the study cost log (comments, really) as a popup window.
     * 
     * @todo Should this be renamed to DisplayStudyCostComnmentsAction()? The button on the UI is captioned "View Log" though.
     */
    protected function DisplayStudyCostLogAction ()
    {
        $view = new Hb_View_Study_StudyCostComment();
        $view->SetStudyID($this->getRequest()->getParam("study_id"));
        $view->Display();
    }

    /**
     * Save Study and Proposal categroies
     * 
     */
    public function SaveStudyCategoryAction()
    {
    	$request = Hb_Util_Request_Request::GetInstance();
    	
    	$study_id = $this->getRequest()->getParam('study_id');
    	$study_term = new Hb_App_Study_StudyTerm();
    	$study_term->SetStudyId($study_id);
    	
    	//save the Study Classification category
    	$study_term->SetStudyClassification($request->getParam('study_category_level_2'));
    	
    	//save the Proposal Categories   
	   //delete existing study proposal categories
	   $study_proposal_categories = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_StudyProposalCategories')->Find($study_id);
	   
	   foreach ($study_proposal_categories as $study_proposal_category) {   	
	   	$study_proposal_category->SetStatus('D');	   	
	   }
	 	  
	   $categories	= array(1 => 'Consumer', 3	=> 'IT', 4 => 'Consumer Medical', 6 => 'B2B', 2 => 'Medical'); 
	   //saveing the proposal revision categories
	   foreach ($categories as $key=>$value) {
	   	$proposal_categories = Hb_App_ObjectHelper::GetMapper('Hb_App_Proposal_ProposalCategories')->Find($key);
	   	foreach ($proposal_categories as $proposal_category) {	   		
	   		if(!$request->hasParam('categories_'.$proposal_category->GetProposalCategoryId())) {
	   			continue;
	   		}
	   		
	   		$category_types = $request->getParam('categories_'.$proposal_category->GetProposalCategoryId());
	   		
	   		foreach ($category_types as $type_value) {
	   			$new_study_proposal_category = new Hb_App_Study_StudyProposalCategory(null,$study_id, $type_value);
	   			$new_study_proposal_category->SetStatus('A');   			
	   		}   		
	   	} 	
	   }    	  	   
    	
	   //redirect to Study details page back
    	$this->getResponse()->setRedirect("/app/stm/index.php?e=".Hb_Util_Encryption_Encryption::GetInstance()->Encrypt("action=vw_detail&study_id=".$study_id."&time_zone_id=".$this->getRequest()->getParam('time_zone_id')));
    	   
    }
}
?>
