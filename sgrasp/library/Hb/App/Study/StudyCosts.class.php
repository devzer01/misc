<?php 
include_once 'Hb/Data/ObjectNotInCollectionException.class.php';
include_once 'Hb/Data/Exception.class';
include_once 'Hb/Exception.class';
include_once 'Hb/App/Collection.class';
include_once 'Hb/App/Study/StudyCost.class.php';
include_once 'Hb/Data/ObjectInCollectionException.class';

/**
 * Domain Object for Study Costs
 * 
 * @copyright 2007 Global Market Insite Inc
 * @author ddayananda
 * @version 1.0
 * @package App
 * @subpackage Study
 *
 */ 
class Hb_App_Study_StudyCosts extends Hb_App_Collection 
{
	/**
	 * StudyCost instance
	 *
	 * @var HB_App_Study_StudyCost 
	 */
	protected $study_cost = array();
	
	/**
	 * Study Cost Id
	 *
	 * @var int 
	 */
	protected $study_cost_id	= null;
	
	protected $total_actual_cost = null;
	
	protected $total_proposed_cost = null;
	
	protected $total_invoice_amount = null;
	
	protected $total_approved_actual_cost = null;
	
	protected $total_rejected_actual_cost = null;
	
	protected $total_not_sure_actual_cost = null;
	
	/**
	 *  Add Item to the Collection
	 * 
	 */
	public function AddItem(Hb_App_Study_StudyCost $study_cost) 
	{
		try {
			
			parent::AddItem($study_cost);
			
			if ($study_cost->GetStatus() == "A") {
				$this->total_actual_cost   += $study_cost->GetActualCost();
				$this->total_proposed_cost += $study_cost->GetProposedCost();
				$this->total_invoice_amount += $study_cost->GetInvoiceAmount();
			}
			
		} catch (Hb_Data_ObjectInCollectionException $e) {
			throw new Hb_Data_ObjectInCollectionException("Study Cost Already Exists In Collection For StudyCosts ". $study_cost->study_cost_id,
				HB_DATA_EXCEPTION_ATTR_IN_COLLECTION);
		}
	}
	
	
	/**
	 * Get Item from the Collection
	 *
	 * @return the relevant domain object
	 */
	public function GetItem($study_cost_id)
	{
		try {
			return parent::GetItem($study_cost_id);
		}catch (Hb_Data_ObjectNotInCollectionException $e) {
			throw new Hb_Data_ObjectNotInCollectionException("Requested Study Cost Not Found For Study Costs Collection" . $study_cost_id,
				HB_DATA_EXCEPTION_ATTR_NOT_IN_COLLECTION);
		}
	}	
	
	/**
	 * Return the Total invoice amount 
	 *
	 * @return float Total invoice amount
	 */
	public function GetTotalInvoiceAmount()
	{
		return (float) $this->total_invoice_amount;
	}
	
	/**
	 * 
	 * aggregated total of the actual cost
	 * 
	 * @return float
	 */
	public function GetTotalActualCost()
	{
		return (float) $this->total_actual_cost;	
	}
	
	/**
	 * aggregated total of the proposed cost
	 * 
	 * @return float
	 */
	public function GetTotalProposedCost()
	{
		return (float) $this->total_proposed_cost;
	}
	
	/**
	 * Add Study Cost values to the Properties
	 *
	 * @todo Find out who wrote this
	 */
	public function AddStudyCost(Hb_App_Study_StudyCost $study_cost)
	{
		$this->study_cost		= $study_cost;
		$this->study_cost_id	= $study_cost->GetStudyCostId();
	}	
	
	/**
	 * Calculate the total actual cost for approved, rejected and not sure yet status.
	 *
	 */
	protected function doCalActualCost()
	{
		foreach ($this->__collection as $study_cost) {
			if($study_cost->IsInvoiceApproved()) {
				$this->total_approved_actual_cost += $study_cost->GetActualCost();
			}elseif($study_cost->IsInvoiceRejected()) {
				$this->total_rejected_actual_cost += $study_cost->GetActualCost();
			}else {
				$this->total_not_sure_actual_cost += $study_cost->GetActualCost();
			}
		}
	}
	
	/**
	 * Return the Total actual cost for the Approved study costs for the Study Costs
	 *
	 * @return float Total actual cost for the Approved study costs
	 */
	public function GetTotalApprovedActualCost()
	{
		if(is_null($this->total_approved_actual_cost)) {
			$this->doCalActualCost();
		}
		
		return $this->total_approved_actual_cost;
	}
	
	/**
	 * Return the Total actual cost for the rejected study costs for the Study Costs
	 *
	 * @return float Total actual cost for the rejected study costs
	 */
	public function GetTotalRejectedActualCost()
	{
		if(is_null($this->total_rejected_actual_cost)) {
			$this->doCalActualCost();
		}
		
		return $this->total_rejected_actual_cost;
	}
	
	/**
	 * Return the Total actual cost for the not approved/rejected study costs for the Study Costs
	 *
	 * @return float Total actual cost for the not approved/rejected study costs
	 */
	public function GetTotalNotApprovedRejectedActualCost()
	{
		if(is_null($this->total_not_sure_actual_cost)) {
			$this->doCalActualCost();
		}
		
		return $this->total_not_sure_actual_cost;
	}
}
?>