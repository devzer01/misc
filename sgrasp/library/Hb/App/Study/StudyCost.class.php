<?php
/**
 * Domain Object for Study cost
 * 
 * @copyright 2007 Global Market Insite Inc
 * @author harsha
 * @version 1.0
 * @package App
 * @subpackage Study
 *
 */
class Hb_App_Study_StudyCost extends Hb_App_Object
{

    /**
     * Account id
     *
     * @var int 
     */
    protected $account_id = null;

    /**
     * Account name
     *
     * @var string 
     */
    protected $account_name = null;

    /**
     * Actual quantity
     *
     * @var float 
     */
    protected $actual_quantity = null;

    /**
     * Actual rate
     *
     * @var float 
     */
    protected $actual_rate = null;

    /**
     * Contact email
     *
     * @var string 
     */
    protected $contact_email = null;

    /**
     * Contact name
     *
     * @var string 
     */
    protected $contact_name = null;

    /**
     * Proposed quantity
     *
     * @var float 
     */
    protected $proposed_quantity = null;

    /**
     * Proposed rate
     *
     * @var float 
     */
    protected $proposed_rate = null;

    /**
     * Reference number
     *
     * @var string 
     */
    protected $reference_number = null;

    /**
     * Study cost id
     *
     * @var int 
     */
    protected $study_cost_id = null;

    /**
     * Study cost type id 
     *
     * @var int 
     */
    protected $study_cost_type_id = null;

    /**
     * Study cost comment id 
     *
     * @var int 
     */
    protected $study_cost_comment_id = null;

    /**
     * Study cost file id 
     *
     * @var int 
     */
    protected $study_cost_file_id = null;

    /**
     * Insetance of Study cost type 
     *
     * @var Hb_App_Study_StudyCostType 
     */
    protected $study_cost_type = null;

    /**
     * Insetance of Study Cost Comments
     *
     * @var Hb_App_Study_StudyCostComments
     */
    protected $study_cost_comments = null;

    /**
     * Study Cost Actions Collection
     *
     * @var Hb_App_Study_StudyCostActions
     * @access protected
     */
    protected $study_cost_actions = null;

    /**
     * Instance of Study Cost File
     *
     * @var Hb_App_Study_StudyCostFile
     */
    protected $study_cost_file = null;
    
    /**
     * Instance of Study cost files
     *
     * @var Hb_App_Study_StudyCostFiles 
     */
    protected $study_cost_files = null;

    /**
     * Study id 
     *
     * @var int 
     */
    protected $study_id = null;

    /**
     * PO number
     *
     * @var string 
     */
    protected $study_cost_po_number = null;

    /**
     * Status
     *
     * @var string 
     */
    protected $status = null;

    /**
     * Invoice rate
     *
     * @var double
     */
    protected $invoice_rate = null;
    
    /**
     * Invoice quantity
     *
     * @var int
     */
    protected $invoice_quantity = null;    
    
    /**
     * Study object 
     *
     * @var Hb_App_Study_Study 
     */
    protected $study = null;
    
    /**
     * Study cost action object
     *
     * @var Hb_App_Study_StudyCostAction 
     */
    protected $study_cost_action = null;
    
    /**
     * Invoice amount greater than VENDOR_INVOICE_AMOUNT_REQUIRE_APPROVAL study setting
     *
     * @var int 
     */
    const REASON_INVOICE_AMOUNT 				= 1;
    
    /**
     * Invoice amount more than VENDOR_INVOICE_PERCENT_REQUIRE_APPROVAL study setting
     *
     * @var int 
     */
    const REASON_INVOICE_PERCENT 			= 2;
    
    /**
     * The study cost has not yet been approved by the Account Manager at the Billing Report level
     *
     * @var int 
     */
    const REASON_NOT_APPROVED					= 3;
    
    /**
     * The study has not yet been invoiced
     *
     * @var int 
     */
    const REASON_NOT_INVOICED					= 4;
    
    /**
     * The client has been billed less than the total study costs
     *
     * @var int 
     */
    const REASON_CLIENT_INVOICE_TOO_LOW	= 5;
    
    /**
     * The study has been invoiced more than VENDOR_INVOICE_DELAY_REQUIRE_APPROVAL study setting
     *
     * @var int 
     */
    const REASON_TOO_LATE						= 6;
    
    
    /**
     * Array showing the reverse action type of each study cost action type
     *
     * @var array
     */
    protected $reverse_action_type = array(
                     ARMC_ACTION_TYPE_AM_APPROVED_STUDY_COST => ARMC_ACTION_TYPE_AM_REJECTED_STUDY_COST,
                     ARMC_ACTION_TYPE_AE_APPROVED_STUDY_COST => ARMC_ACTION_TYPE_AE_REJECTED_STUDY_COST,
                     ARMC_ACTION_TYPE_ACCT_APPROVED_STUDY_COST => ARMC_ACTION_TYPE_ACCT_REJECTED_STUDY_COST,
                     ARMC_ACTION_TYPE_APPROVED_INVOICE => ARMC_ACTION_TYPE_REJECTED_INVOICE,
                     ARMC_ACTION_TYPE_AM_REJECTED_STUDY_COST => ARMC_ACTION_TYPE_AM_APPROVED_STUDY_COST,
                     ARMC_ACTION_TYPE_AE_REJECTED_STUDY_COST => ARMC_ACTION_TYPE_AE_APPROVED_STUDY_COST,
                     ARMC_ACTION_TYPE_ACCT_REJECTED_STUDY_COST => ARMC_ACTION_TYPE_ACCT_APPROVED_STUDY_COST,
                     ARMC_ACTION_TYPE_REJECTED_INVOICE => ARMC_ACTION_TYPE_APPROVED_INVOICE);
    
    /**
     * Create Study Cost Object
     *
     * @param int $id Study cost id
     * @param int $account_id Account id
     * @param string $account_name Account name
     * @param float $actual_quantity Actual quantity
     * @param float $actual_rate Actual rate
     * @param float $proposed_quantity Proposed quantity
     * @param float $proposed_rate Proposed rate
     * @param string $contact_email Contact email
     * @param string $contact_name Contact name
     * @param string $reference_number Reference number
     * @param int $study_cost_type_id Stduy cost type id
     * @param int $study_id Study id
     * @param string $study_cost_po_number PO number
     * @param double $invoice_rate Invoice Rate
     * @param int $invoice_quantity Invoice Quantity
     * @param string $status Status
     */
    function __construct (
    								$id 						= null, 
    								$account_id 			= null, 
    								$account_name 			= null, 
    								$actual_quantity 		= null, 
    								$actual_rate 			= null, 
    								$proposed_quantity 	= null, 
    								$proposed_rate 		= null, 
    								$contact_email 		= null, 
    								$contact_name 			= null, 
    								$reference_number 	= null, 
    								$study_cost_type_id 	= null, 
    								$study_id 				= null, 
    								$study_cost_po_number = null, 
    								$status 					= null,
    								$invoice_rate 			= null, 
    								$invoice_quantity 	= null    								
    								)
    {        
        $this->study_cost_id 			= $id;
        $this->account_id 				= $account_id;
        $this->account_name 			= $account_name;
        $this->actual_quantity 		= $actual_quantity;
        $this->actual_rate 			= $actual_rate;
        $this->proposed_quantity 	= $proposed_quantity;
        $this->proposed_rate 			= $proposed_rate;
        $this->contact_email 			= $contact_email;
        $this->contact_name 			= $contact_name;
        $this->reference_number 		= $reference_number;
        $this->study_cost_type_id 	= $study_cost_type_id;
        $this->study_id 				= $study_id;
        $this->study_cost_po_number = $study_cost_po_number;
        $this->status 					= $status;        
        $this->invoice_rate 			= $invoice_rate;
        $this->invoice_quantity 		= $invoice_quantity;        

        parent::__construct($id);
    }

    /**
     * Return the Study cost approval reasons array for the Study Cost
     *
     * @return array Study cost approval reasons array
     */
    public static function GetStudyCostApprovalReasons()
    {
    	return array(
    		self::REASON_INVOICE_AMOUNT 	=> "Invoice amount greater than $".Hb_App_Study_StudySetting::GetSetting("VENDOR_INVOICE_AMOUNT_REQUIRE_APPROVAL"),
		   self::REASON_INVOICE_PERCENT 	=> "Invoice amount more than ".Hb_App_Study_StudySetting::GetSetting("VENDOR_INVOICE_PERCENT_REQUIRE_APPROVAL")."% different than actual cost",
		   self::REASON_NOT_APPROVED 		=> "The study cost has not yet been approved by the Account Manager at the Billing Report level",
		   self::REASON_NOT_INVOICED 		=> "The study has not yet been invoiced",
		   self::REASON_CLIENT_INVOICE_TOO_LOW => "The client has been billed less than the total study costs",
		   self::REASON_TOO_LATE 			=> "The study has been invoiced more than ".Hb_App_Study_StudySetting::GetSetting("VENDOR_INVOICE_DELAY_REQUIRE_APPROVAL")." days ago");
    }
    
    /**
     * Return the Study cost PO number for the Study cost
     *
     * @return string Study cost PO number
     */
    public function GetStudyCostPONumber ()
    {

        return $this->study_cost_po_number;
    }

    /**
     * Return the Account id for the Study Cost
     *
     * @return int Account id
     */
    public function GetAccountId ()
    {

        return $this->account_id;
    }

    /**
     * Return the Account name for the Study Cost
     *
     * @return string Account name
     */
    public function GetAccountName ()
    {

        return $this->account_name;
    }

    /**
     * Return the Actual quantity for the Study Cost
     *
     * @return float Actual quantity
     */
    public function GetActualQuantity ()
    {		
        return $this->actual_quantity;
		
    }

    /**
     * Return the Actual rate for the Study Cost
     *
     * @return float Actual rate
     */
    public function GetActualRate ()
    {		
        return $this->actual_rate;	
    }

    /**
     * Return the contact email for the Study Cost
     *
     * @return string contact email
     */
    public function GetContactEmail ()
    {

        return $this->contact_email;
    }

    /**
     * Return the Contact name for the Study Cost
     *
     * @return string Contact name
     */
    public function GetContactName ()
    {

        return $this->contact_name;
    }

    /**
     * Return the Proposed quantity for the Study Cost
     *
     * @return float Proposed quantity
     */
    public function GetProposedQuantity ()
    {

        return $this->proposed_quantity;
    }

    /**
     * Return the Proposed rate for the Study Cost
     *
     * @return float Proposed rate
     */
    public function GetProposedRate ()
    {

        return $this->proposed_rate;
    }

    /**
     * Return the Proposed cost
     * 
     * @return float Proposed cost
     */
    public function GetProposedCost ()
    {

        return $this->proposed_rate * $this->proposed_quantity;
    }

    /**
     * Return the Invoice Rate
     * 
     * @return double Invoice Rate
     *
     */
    public function GetInvoiceRate()
    {
    	return $this->invoice_rate;
    }
    
    /**
     * Return the Invoice Quantity
     * 
     *@return int Invoice Quantity
     */
    public function GetInvoiceQuantity()
    {
    	return $this->invoice_quantity;
    }
    /**
     * Return the Invoice Amount
     *
     * @return float Invoice Amount
     */
    public function GetInvoiceAmount()
    {
    	return $this->invoice_rate * $this->invoice_quantity;
    }
    /**
     * Return the Actual cost
     * 
     * @return float Actual cost
     */
    public function GetActualCost ()
    {

        return $this->actual_rate * $this->actual_quantity;
    }

    /**
     * Return the Reference number for the Study Cost
     *
     * @return string Reference number
     */
    public function GetReferenceNumber ()
    {

        return $this->reference_number;
    }

    /**
     * Return the Study cost id for the Study Cost
     *
     * @return int Study cost id
     */
    public function GetStudyCostId ()
    {

        return $this->study_cost_id;
    }

    /**
     * Return the Study cost type id  for the Study Cost
     *
     * @return int Study cost type id 
     */
    public function GetStudyCostTypeId ()
    {

        return $this->study_cost_type_id;
    }

    /**
     * Return the Study cost comment id  for the Study Comment
     *
     * @return int Study cost comment id 
     */
    public function GetStudyCostCommentId ()
    {

        return $this->study_cost_comment_id;
    }

    /**
     * Return the Study cost file id  for the Study File
     *
     * @return int Study cost file id 
     */
    public function GetStudyCostFileId ()
    {

        return $this->study_cost_file_id;
    }

    /**
     * Return the Instance of Study cost type for the Study Cost
     *
     * @return Hb_App_Study_StudyCostType Instance of Study cost type
     */
    public function GetStudyCostType ()
    {

        if (is_null($this->study_cost_type)) {
            if (is_null($this->study_cost_type_id))
                throw new Hb_App_Study_Exception_StudyCostNotFoundException();
            
            $this->study_cost_type = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_StudyCostType')->Find($this->study_cost_type_id);
        }
        
        return $this->study_cost_type;
    }

    
    /**
     * Return the Instance of Study cost comments collection
     *
     * @return Hb_App_Study_StudyCostComments 
     */
    public function GetStudyCostComments ()
    {

        if (is_null($this->study_cost_comments)) 
        {
            $study_cost_comments = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_StudyCostComments')->Find($this->study_cost_id);
           
			/* @var $study_cost_comments Hb_App_Study_StudyCostComments */
            $study_cost_comments->SetStudyCost($this);
            
            $this->study_cost_comments = $study_cost_comments; 
        }
        
        return $this->study_cost_comments;
    }

    protected function doGetStudyCostCommentsStatus ($status = 'A')
    {

        $this->GetStudyCostComments();
        $comments = new Hb_App_Study_StudyCostComments();
        foreach ($this->study_cost_comments as $comment) {
            /* @var $comment Hb_App_Study_StudyCostComment */
            if ($comment->GetStatus() == $status) {
                $comments->AddItem($comment);
            }
        }
        return $comments;
    }

    /**
     * Return the ACTIVE study cost comments
     */
    public function GetStudyCostCommentsActive ()
    {

        return $this->doGetStudyCostCommentsStatus('A');
    }

    /**
     * Returns the Study Cost Actions Collection object
     * 	
     * @return Hb_App_Study_StudyCostActions
     */
    public function GetStudyCostActions ()
    {

        if (is_null($this->study_cost_actions)) {
            $this->study_cost_actions = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_StudyCostActions')->Find($this->study_cost_id);
        }
        
        return $this->study_cost_actions;
    }
    
    /**
     * Returns the Study Cost Action object
     * 	
     * @return Hb_App_Study_StudyCostAction
     */
    public function GetStudyCostAction()
    {
		if (is_null($this->study_cost_action)) {
			try {
		   	$this->study_cost_action = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_StudyCostAction')->FindByStudyCostId($this->study_cost_id);
		   	return $this->study_cost_action->GetStudyCostActionTypeId();
			}catch (Exception $e){				
				return 0;
			}
		}		
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $status
     * @return unknown
     */
    protected function doGetStudyCostActionsStatus ($status = 'A')
    {

        $this->GetStudyCostActions();
        $actions = new Hb_App_Study_StudyCostActions();
        foreach ($this->study_cost_actions as $action) {
            /* @var $action Hb_App_Study_StudyCostAction */
            if ($action->GetStatus() == $status) {
                $actions->AddItem($action);
            }
        }
        return $actions;
    }

    /**
     * Return the ACTIVE study cost actions collection
     * 
     * @return Hb_App_Study_StudyCostActions
     */
    public function GetStudyCostActionsActive ()
    {

        return $this->doGetStudyCostActionsStatus('A');
    }
    
    /**
     * Get the ID of the Study Cost action of the specified type.
     *
     * @param int $study_cost_action_type_id
     * @return int
     */
    protected function getStudyCostActionIdOfType($study_cost_action_type_id)
    {
       $actions = $this->GetStudyCostActionsActive();
       foreach ($actions as $action) {
           /* @var $action Hb_App_Study_StudyCostAction */
           if ($action->GetStudyCostActionTypeId() == $study_cost_action_type_id) {
               return $action->GetStudyCostActionId();
           }
       }
       return 0;
    }
    /**
	 * Return the Study object
	 * 
	 * Hb_App_Study_Study
	 */
	public function GetStudy()
	{
		if(is_null($this->study)){
			if(is_null($this->study_id)){				
				throw new Hb_App_Study_Exception_StudyCostNotFoundException('Study id is not set...!');
			}			
			$this->study = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_Study')->Find($this->study_id);
		}	
		
		return $this->study;
	}		
		
  /**
	* Get the code for the reason why manager's approval is needed for paying the vendor invoice
	*
	* @return int 
	*/
	public function GetInvoicePaymentApprovalCode() 
	{
		//If the invoice amount is greater than VENDOR_INVOICE_AMOUNT_REQUIRE_APPROVAL Study Setting		
		if($this->GetInvoiceAmount() > Hb_App_Study_StudySetting::GetSetting('VENDOR_INVOICE_AMOUNT_REQUIRE_APPROVAL'))
			return self::REASON_INVOICE_AMOUNT;
		
		//If the invoice amount differs from the actual cost by more than VENDOR_INVOICE_PERCENT_REQUIRE_APPROVAL percent
		if(abs($this->GetActualCost() - $this->GetInvoiceAmount())/$this->GetActualCost() > Hb_App_Study_StudySetting::GetSetting('VENDOR_INVOICE_PERCENT_REQUIRE_APPROVAL'))
			return self::REASON_INVOICE_PERCENT;
		
		//If the study cost has NOT YET been approved by the Account Manager at the Billing Report stage
		if(!$this->IsAMApproved())
			return self::REASON_NOT_APPROVED;				
		
		//If Study was not invoiced (invoice date not set at study level)	
		if(!($this->GetStudy()->IsInvoiceDateValid()))
			return self::REASON_NOT_INVOICED;
			
		//If the study has been invoiced, but the Billing Report amount is less than the sum of Study Costs
		if($this->GetStudy()->IsInvoiceDateValid())
		{
			//load the study attribute that has a billing report id which study is invoiced.					
			//get the billing amount
			$armc = Hb_App_ObjectHelper::GetMapper('Hb_App_Billing_BillingReport')->Find($this->GetStudy()->GetBillingReportId());	
						
			if($armc->GetAmount() < $this->GetStudy()->GetStudyCosts()->GetTotalInvoiceAmount()) {			
				return self::REASON_CLIENT_INVOICE_TOO_LOW;
			}
		}
			
		//Current date is more than VENDOR_INVOICE_DELAY_REQUIRE_APPROVAL days after the Study Invoice date
		if($this->GetStudy()->IsInvoiceDateValid()) {
			if(Hb_Util_Lang_Date::GetDateDifference(date("Y-m-d H:i:s"), $this->GetStudy()->GetStudyInvoiceDate()) > Hb_App_Study_StudySetting::GetSetting('VENDOR_INVOICE_DELAY_REQUIRE_APPROVAL'))
				return self::REASON_TOO_LATE;
		}
		
	}

    /**
     * Check to see if the Study Cost has been approved by the Account Manager
     * 
     * @return boolean
     */
    public function IsAMApproved ()
    {

        return ($this->getStudyCostActionIdOfType(ARMC_ACTION_TYPE_AM_APPROVED_STUDY_COST) != 0);
    }

    /**
     * Check to see if the Study Cost has been rejected by the Account Manager
     * 
     * @return boolean
     */
    public function IsAMRejected ()
    {

        return ($this->getStudyCostActionIdOfType(ARMC_ACTION_TYPE_AM_REJECTED_STUDY_COST) != 0);
    }

    /**
     * Check to see if the Study Cost has been approved by the Account Executive
     * 
     * @return boolean
     */
    public function IsAEApproved ()
    {

        return ($this->getStudyCostActionIdOfType(ARMC_ACTION_TYPE_AE_APPROVED_STUDY_COST) != 0);
    }

    /**
     * Check to see if the Study Cost has been rejected by the Account Executive
     * 
     * @return boolean
     */
    public function IsAERejected ()
    {

        return ($this->getStudyCostActionIdOfType(ARMC_ACTION_TYPE_AE_REJECTED_STUDY_COST) != 0);
    }

    /**
     * Check to see if the Study Cost has been approved by the Accounting team
     * 
     * @return boolean
     */
    public function IsACCTApproved ()
    {

        return ($this->getStudyCostActionIdOfType(ARMC_ACTION_TYPE_ACCT_APPROVED_STUDY_COST) != 0);
    }

    /**
     * Check to see if the Study Cost has been rejected by the Accounting team
     * 
     * @return boolean
     */
    public function IsACCTRejected ()
    {

        return ($this->getStudyCostActionIdOfType(ARMC_ACTION_TYPE_ACCT_REJECTED_STUDY_COST) != 0);
    }

    
    /**
     * Check to see if the Study Invoice Approved
     *
     * @return boolean
     */
    public function IsInvoiceApproved()
    {
    	 return ($this->getStudyCostActionIdOfType(ARMC_ACTION_TYPE_APPROVED_INVOICE) != 0);
    }
 
    /**
     * Check to see if the Study Invoice Rejected
     *
     * @return boolean
     */
    public function IsInvoiceRejected()
    {
    	 return ($this->getStudyCostActionIdOfType(ARMC_ACTION_TYPE_REJECTED_INVOICE) != 0);
    }
   
   /**
    * Return the Study Cost action type 
    *
    * @return string
    */
	public function GetStatusDescription()
	{
		if($this->IsInvoiceApproved()) {
			return 'Approved';
		}elseif($this->IsInvoiceRejected()) {
			return 'Rejected';
		}else {
			return 'Not Sure Yet';
		}
	}    
        
    /**
     * Return the Instance of Study Cost File for the Study Cost
     *
     * @return Hb_App_Study_StudyCostFile Instance of Study Cost File
     */
    public function GetStudyCostFile()
    {

        if (is_null($this->study_cost_file)) {        	
            $this->study_cost_file = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_StudyCostFile')->Find($this->study_cost_file_id);
            $this->study_cost_file_id = $this->study_cost_file->GetId();
        }
        
        return $this->study_cost_file;
    }
    
    /**
     * Return the Instance of Study Cost Files for the Study cost
     *
     * @return Hb_App_Study_StudyCostFiles Instance of Study Cost Files
     */
    public function GetStudyCostFiles()
    {
    	 if (is_null($this->study_cost_files)){        	    	 	
       	$this->study_cost_files = Hb_App_ObjectHelper::GetMapper('Hb_App_Study_StudyCostFiles')->Find($this->study_cost_id);            
       }    
          
       return $this->study_cost_files;
    }

    /**
     * Return the Study id for the Study Cost
     *
     * @return int Study id
     */
    public function GetStudyId ()
    {
        return $this->study_id;
    }

    /**
     * Return the status
     *
     * @return string Status
     */
    public function GetStatus()
    {
        return $this->status;
    }

    /**
     * Set the Study cost po number for the  Study cost
     * 
     * @param string $study_cost_po_number Study cost po number
     */
    public function SetStudyCostPONumber ($study_cost_po_number)
    {

        $this->study_cost_po_number = $study_cost_po_number;
        $this->MarkDirty();
    }

    
    /**
     * Set the Account id  for the  Study Cost
     * 
     * @param int $account_id Account id 
     */
    public function SetAccountId ($account_id)
    {

        $this->account_id = $account_id;
        $this->MarkDirty();
    }

    /**
     * Set the Account name for the  Study Cost
     * 
     * @param string $account_name Account name
     */
    public function SetAccountName ($account_name)
    {

        $this->account_name = $account_name;
        $this->MarkDirty();
    }

    /**
     * Set the Actual quantity for the  Study Cost
     * 
     * @param float $actual_quantity Actual quantity
     */
    public function SetActualQuantity ($actual_quantity)
    {
		if ( $this->isActualEditable() )
		{
        $this->actual_quantity = $actual_quantity;
        $this->MarkDirty();
		}
    }

    /**
     * Set the Actual rate for the  Study Cost
     * 
     * @param float $actual_rate Actual rate
     */
    public function SetActualRate ($actual_rate)
    {
		if ( $this->isActualEditable() )
		{
        $this->actual_rate = $actual_rate;
        $this->MarkDirty();
		}
    }

    /**
     * Set the Contact email for the  Study Cost
     * 
     * @param string $contact_email Contact email
     */
    public function SetContactEmail ($contact_email)
    {

        $this->contact_email = $contact_email;
        $this->MarkDirty();
    }

    /**
     * Set the Contact name for the  Study Cost
     * 
     * @param string $contact_name Contact name
     */
    public function SetContactName ($contact_name)
    {

        $this->contact_name = $contact_name;
        $this->MarkDirty();
    }

    /**
     * Set the Proposed quantity for the  Study Cost
     * 
     * @param float $proposed_quantity Proposed quantity
     */
    public function SetProposedQuantity ($proposed_quantity)
    {
		  if ( $this->isProposedEditable())
		  {
	        $this->proposed_quantity = $proposed_quantity;
	        $this->MarkDirty();
		  }
    }

    /**
     * Set the Proposed rate for the  Study Cost
     * 
     * @param flaot $proposed_rate Proposed rate
     */
    public function SetProposedRate ($proposed_rate)
    {
		  if ( $this->isProposedEditable())
		  {
	        $this->proposed_rate = $proposed_rate;
	        $this->MarkDirty();
		  }
    }

    /**
     * Set the Invoice Rate
     *
     * @param double $invoice_rate Invoice Rate
     */
    public function SetInvoiceRate($invoice_rate)
    {
    	if ( $this->isInvoiceEditable())
    	{
	    	$this->invoice_rate = $invoice_rate;
	    	$this->MarkDirty();
    	}
    }
    
    /**
     * Set the Invoice Quantity
     *
     * @param int $invoice_quantity
     */
    public function SetInvoiceQuantity($invoice_quantity)
    {
    	if ( $this->isInvoiceEditable())
    	{
	    	$this->invoice_quantity = $invoice_quantity;
	    	$this->MarkDirty();
    	}
    }
    
    /**
     * Set the Reference number for the  Study Cost
     * 
     * @param string $reference_number Reference number
     */
    public function SetReferenceNumber ($reference_number)
    {

        $this->reference_number = $reference_number;
        $this->MarkDirty();
    }

    /**
     * Set the Study cost id for the  Study Cost
     * 
     * @param int $study_cost_id Study cost id
     */
    public function SetStudyCostId ($study_cost_id)
    {

        $this->study_cost_id = $study_cost_id;
        $this->__id = $study_cost_id;
        $this->MarkDirty();
    }

    /**
     * Set the Study cost type id for the  Study Cost
     * 
     * @param int $study_cost_type_id Study cost type id
     */
    public function SetStudyCostTypeId ($study_cost_type_id)
    {

        $this->study_cost_type_id = $study_cost_type_id;
        $this->MarkDirty();
    }

    /**
     * Set the Study cost comment id for the  Study Cost
     * 
     * @param int $study_cost_comment_id Study cost comment id
     */
    public function SetStudyCostCommentId ($study_cost_comment_id)
    {

        $this->study_cost_comment_id = $study_cost_comment_id;
        $this->MarkDirty();
    }

    
    /**
     * Set the Study cost type id for the  Study File
     * 
     * @param int $study_cost_file_id Study cost file id
     */
    public function SetStudyCostFileId ($study_cost_file_id)
    {

        $this->study_cost_file_id = $study_cost_file_id;
        
        $this->MarkDirty();
    }

    public function SetStudyCostFile (Hb_App_Study_StudyCostFile $file)
    {

        $this->study_cost_file = $file;        
        $this->study_cost_file_id = $file->GetStudyCostFileId();
        $file->SetStudyCost($this);
    }

    /**
     * Set the Study id for the  Study Cost
     * 
     * @param int $study_id Study id
     */
    public function SetStudyId ($study_id)
    {

        $this->study_id = $study_id;
        $this->MarkDirty();
    }

    public function SetStatus ($status)
    {

        $this->status = $status;
        $this->MarkDirty();
    }
    
    public function AddCostChangeComment($comment)
    {
        $this->doAddComment(STUDY_COMMENT_TYPE_STUDY_COST_CHANGE, $comment);
    }
    
    public function AddCostNewComment($comment)
    {
        $this->doAddComment(STUDY_COMMENT_TYPE_STUDY_COST_ADDED, $comment);
    }
    
    public function AddCostRejectedComment($comment)
    {
       $this->doAddComment(STUDY_COMMENT_TYPE_STUDY_COST_REJECTED, $comment);
    }
    
    protected function doAddComment($type, $comment)
    {
        if (trim($comment) == "") return;
        
        $study_cost_comments = $this->GetStudyCostComments();
        
        $study_comment = new Hb_App_Study_StudyCostComment(null, $this->study_cost_id, $type, date("Y-m-d h:i:s"), $comment, "A");
        $study_comment->SetStudyCost($this);
        
        $study_cost_comments->AddItem($study_comment);
    }
    
    public function SetCostAction($study_cost_action_type_id)
    {

       $study_cost_action_id = $this->getStudyCostActionIdOfType($study_cost_action_type_id);
      
       if ($study_cost_action_id == 0) {
          $study_cost_action_id = $this->getStudyCostActionIdOfType($this->reverse_action_type[$study_cost_action_type_id]);
    	 }

    	 if ($study_cost_action_id == 0) {
    	 
          $this->doAddAction($study_cost_action_type_id);
    	
    	 } else {
    	
          $action = Hb_App_ObjectHelper::GetMapper("Hb_App_Study_StudyCostAction")->Find($study_cost_action_id);
          $action->SetStudyCostActionTypeId($study_cost_action_type_id);
    	
    	 }
    }
    
    protected function doAddAction($type)
    {
    	$study_cost_actions = $this->GetStudyCostActions();
    	$study_cost_actions->AddItem(
    		new Hb_App_Study_StudyCostAction(null, $this->study_cost_id, $type, "A"));
    }
    
    /**
     * Defines whether proposed inputs should be enabled 
     *
     * @return boolean
     */    
    public function isProposedEditable()
	{
		if ( Hb_Util_Security::HasSecurity('PJM_STMC_COST_OVERRIDE_PROPOSED') )
			return true ;
		else 
			return false ;	
	}
	
	/**
     * Defines whether actual inputs should be enabled 
     *
     * @return boolean
     */  
	public function isActualEditable()
	{	
		//$this->GetStudy()->isInvoiced() would have made more sense :)
		if ( !$this->GetStudy()->IsInvoiceDateValid() || Hb_Util_Security::HasSecurity('PJM_STMC_COST_OVERRIDE_ACTUAL'))	
			return true ;
		else 
			return false ;	
	}
	
	/**
     * Defines whether invoice inputs should be enabled 
     *
     * @return boolean
     */  
	public function isInvoiceEditable()
	{
		if ((($this->GetActualCost() != 0) && !($this->IsInvoiceApproved() || $this->IsInvoiceRejected())) || 
		    Hb_Util_Security::HasSecurity('PJM_STMC_COST_OVERRIDE_INVOICE'))
			return true ;
		else 
			return false ;		
	}
	
	/**
     * Defines whether approval buttons should be enabled 
     *
     * @return boolean
     */ 
	public function isApprovalEditable()
	{
		if (($this->GetInvoiceAmount() != 0) && ($this->isInvoiceFileAttached()) && 
		((!($this->IsInvoiceApproved() || $this->IsInvoiceRejected())) ||  
		(($this->IsInvoiceApproved() || $this->IsInvoiceRejected()) && Hb_Util_Security::HasSecurity('PJM_STMC_COST_OVERRIDE_APPROVAL'))))
			return true ;
		else 
			return false ;		
	}
		
	/**
     * Checks whether the invoice file is attached to the study cost
     *
     * @return boolean
     */ 
	protected function isInvoiceFileAttached()
	{
		$study_cost_files = $this->GetStudyCostFiles();
		
		foreach ($study_cost_files as $study_cost_file) 
		{
			if ( $study_cost_file->GetStudyCostFileType()->GetTypeId() == INVOICE_STUDY_COST_FILE ) 
			{
				return true ;
			}
		}
		
		if(!is_null($this->study_cost_file))
		{
			return true;
		}
		
		return false ;
	}
	
	/**
     * Checks whether it is required to send the invoice approval alert
     *
     * @return boolean
     */ 
	public function isInvoiceApprovalRequired()
	{
		return (($this->GetInvoiceAmount() != 0) && 
			 $this->isInvoiceFileAttached() && 
			 ($this->GetInvoicePaymentApprovalCode() != 0) && 
			 !($this->IsInvoiceApproved() || $this->IsInvoiceRejected()));
	}
}
?>