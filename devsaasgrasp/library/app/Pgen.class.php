<?php
require_once 'db/proposalDB.class.php';

/* new functions push to a class for bettter namespace handling */

class app_Pgen extends Common {
	
	/**
	 * Designated DB Layer Class Object
	 * @var proposalDB
	 */
	protected  $__db;
	
	/**
	 * Designated DB Layer Class Object ( v2.0 )
	 * @var db_ppm
	 */
	protected $db;
	
	private $__year       = 0;
	
	private $__year_month = 0;
	
	private $__date       = 0;
	
	private $__date_range =  array();
	
	private $__default_currency = 'USD';
	
	private $__currency  = 'USD';
	
	private $__exchange_rate = 0;
	
	private $__currency_unicode = 36;
	
	/**
	 * Used to Store the Differnt Segment Descriptions in The Report
	 *
	 * @var array
	 */
	
	private $__list = array();
	
	private $__filter = '';
	
	private $__first_key  = '';
	
	private $__second_key = '';
	
	private $__data_denominator = 1;
	
	private $__use_custom_denominator = true;
	
	/** 
	 * The Current Looping Date Range For Tabular Data
	 * @var mixed 
	 * 
	 */
	private $__current_range = array();
	
	protected  $proposal_id = 0;
	
	protected  $revision_id = 0;
	
	protected $proposal = array();
	
	protected $revision = array();
	
	protected $options  = array();
	
	protected $summary  = array();
   		
	const PROPOSAL_BUDGET_TOTAL_COST = 22;
	
	
	function __construct()
	{
		$this->db = new db_ppm();

		$this->__db = new proposalDB();
	}
	
	/**
	* CreateRevisionFromRevision()
	*
	* @param int current revision id
	* @return int new revision id
	* @throws
	* @access
	* @todo REFACTOR PHASE 1 COMPLETED
	* @since  - 16:07:37
	*/
	function CreateRevisionFromRevision()
	{
		$this->db->UpdateRevisionStatus($this->revision_id, PROPOSAL_REVISION_STATUS_REVISED);
		
		$this->__LoadProposalSummary();
		//$this->proposal = $this->db->GetProposalHeader($this->proposal_id);
		
		$this->__LoadRevisionSummary();
		//$this->revision = $this->db->GetRevisionDetail($this->revision_id); 
		
		$revision_count = $this->db->GetRevisionCount($this->proposal_id);		
		$revision_number = $revision_count + 1;
		
		$selected_countries = count($this->__o['selected_country']);
		$selected_options   = 1;
		
		$this->db->SetRevision(
			$this->revision['proposal_id'], 
			$revision_number, 
			$this->revision['study_interview_type_id'],
			$this->revision['study_setup_duration_id'],
			$this->revision['study_fieldwork_duration_id'],
			$this->revision['study_data_processing_duration_id'],
			$this->revision['proposal_option_type_id'],
			$this->revision['proposal_type_id'],
			$selected_countries,
			$selected_options,
			$_SESSION['admin_id'],
			$_SESSION['user_id'],
			$this->revision['pricing_type_id']);
			
   	$proposal_revision_id = $this->db->last_insert_id;

   	$this->db->UpdateCurrentRevisionNumber($this->proposal_id, $revision_number);
   	$this->db->UpdateRevisionSentDate($proposal_revision_id, date("Y/m/d H:i:s"));

   	$this->__CopyRevisionComments($this->revision_id, $proposal_revision_id);
		
		$this->__CopyRevisionSampleTypes($this->revision_id, $proposal_revision_id);
		
		$this->__CopyRevisionServiceList($this->revision_id, $proposal_revision_id);
		
		$this->__CopyRevisionActions($this->revision_id, $proposal_revision_id);
		
		$pricing_manager = new pgen_PricingManager();
		$pricing_manager->CalculatePricing($this->proposal['account_id'], $this->proposal['license_level_id'], $this->proposal_id, $proposal_revision_id);
		
		$this->__CopyProposalOptions($this->revision_id, $proposal_revision_id, $this->__o['selected_option'], $this->__o['selected_country']);
   	
		$pricing_manager->CalculateProposal($this->proposal_id, $proposal_revision_id);
		
		return $proposal_revision_id;
		
	}
	
	/**
	* __CopyProposalOptions()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @todo REFACTOR PHASE 1 COMPLETED, need to fix the option number from being static 1 to a variable
	* @since  - 17:12:31
	*/
	private function __CopyProposalOptions($current_revision_id, $new_revision_id, $options = array(), $sort_order = array())
	{
		$revision_options = $this->db->GetRevisionOptions($current_revision_id, $options, $sort_order);
		
		$index = 1;
		
		foreach ($revision_options as $row) {
			
			$this->db->SetRevisionOption(
				$new_revision_id, $row['country_code'], 1,  
				$index, $row['sub_group_description'], 
				$row['programming'], $row['study_programming_type_id'], 
				$row['translation'], $row['translation_language_code'], 
   			$row['overlay'], $row['overlay_language_code'], 
   			$row['study_datasource_id'], $row['incidence_rate'], 
   			$row['completes'], $row['questions_programmed'], 
   			$row['questions_per_interview'], $row['questions_per_screener'], 
   			$row['data_recording_hours'], $row['data_tab_hours'], 
   			$row['data_export_hours'], $row['data_import_hours'], 
   			$row['open_end_questions'], $row['incidence_of_open_end'],
   			$row['avg_words_per_open_end'], $row['open_end_text_coding_hours'], 
   			$row['respondent_portal_type_id'], $row['respondent_portal_programming_hours'],
   			$row['client_portal_programming_hours'], $row['panel_import_hours'], 
   			$row['panel_cost_per_interview'], $row['option_total'], 
   			$row['panel_total'], $row['option_cpc']);
   		
   		/* when we copy options we have to copy any custom pricing that goes along with it */
			if ($this->revision['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
				$proposal_revision_option_id = $this->db->last_insert_id;
				
				$this->__CopyCustomPricing($row['proposal_revision_option_id'], $proposal_revision_option_id);
				
			}
   		
   		$index++;
   		
		}
   }
	
   
   /**
   * __CopyCustomPricing()
   *
   * @param
   * @param -
   * @return
   * @throws
   * @access
   * @todo REFACTOR PHASE 1 COMPLETED
   * @since  - 18:44:17
   */
   function __CopyCustomPricing($current_proposal_revision_option_id, $new_proposal_revision_option_id)
   {
   	$pricing = $this->db->GetRevisionCustomPriceByOptionId($current_proposal_revision_option_id);
   	
   	foreach ($pricing as $row) {
   		$this->db->SetProposalRevisionCustomPrice($new_proposal_revision_option_id, $row['pricing_item_group_id'], $row['amount']);
   	}
   	
   }
	/**
	* __CopyRevisionComments()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @todo REFACTOR PHASE 1 COMPLETED
	* @since  - 11:20:16
	*/
	private function __CopyRevisionSampleTypes($current_revision_id, $new_revision_id)
	{
		$revision_st = $this->db->GetRevisionSampleTypes($current_revision_id);
		
		foreach ($revision_st as $row) {
			$this->db->SetRevisionSampleType($new_revision_id, $row['sample_type_id']);
		}
	}
	
	/**
	* __CopyRevisionComments()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 11:20:16
	*/
	private function __CopyRevisionServiceList($current_revision_id, $new_revision_id)
	{
		$revision_sl = $this->db->GetRevisionServiceList($current_revision_id);

		foreach ($revision_sl as $row) {
			$this->db->SetRevisionService($new_revision_id, $row['service_id']);
		}
	}
	
	/**
	* __CopyRevisionComments()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 11:20:16
	*/
	private function __CopyRevisionComments($current_revision_id, $new_revision_id)
	{
		/* copy old revision comment to the new revision */
		$revision_cmt = $this->db->GetRevisionComments($current_revision_id);
		
		foreach ($revision_cmt as $row) {
		 	$this->db->SetRevisionComment($new_revision_id, $row['proposal_revision_comment_type_id'], $row['comment']);
		} 
	}
	
	/**
	* __CopyRevisionActions()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 19:57:05
	*/
	private function __CopyRevisionActions($current_revision_id, $new_revision_id)
	{
		$revision_al = $this->db->GetRevisionActionList($current_revision_id);
		
		foreach ($revision_al as $row) {
			$this->db->SetProposalRevisionAction(
				$row['proposal_review_group_id'], 
				$row['login'], 
				$row['proposal_action_id'], 
				$row['action_date'], 
				$row['action_comment'], 
				1 /* mark the comment as a copy */
			);
		}		
	}
	
	/**
	* __SetProposalAndRevisionId()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 21:21:47
	*/
	protected  function __SetProposalAndRevisionId($proposal_id = 0, $proposal_revision_id = 0)
	{
		if ($proposal_id == 0 && !isset($this->__o['proposal_id'])) {
			//throw
			return false;
		} elseif ($proposal_id == 0 && isset($this->__o['proposal_id'])) { /* if proposal_id is passed in we dont want to use what came off the browser */
			$proposal_id          = $this->__o['proposal_id'];			
		} 
		 
		if ($proposal_revision_id == 0 && !isset($this->__o['proposal_revision_id'])) {
			//throw
			return false;
		} elseif ($proposal_revision_id == 0 && isset($this->__o['proposal_revision_id'])) { /* if proposal_id is passed in we dont want to use what came off the browser */
			$proposal_revision_id = $this->__o['proposal_revision_id'];
		} 
		
		/* verify the revision is part of the proposal_id */
		
		$this->proposal_id    = $proposal_id;
		$this->revision_id    = $proposal_revision_id;
		
		return true;
	}
	
	/**
	* CreateCustomRevision()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 15:43:43
	*/
	function CreateCustomRevision()
	{
		$this->LoadSmarty();
		$this->LoadEncryption();
   	
		/* this is a critical part of the application */
		$this->__SetProposalAndRevisionId();
		
		$proposal_revision_id = $this->CreateRevisionFromRevision();
		
		/* working with the new version */
		$this->__SetProposalAndRevisionId($this->proposal_id, $proposal_revision_id);
		
		/* check if the user sent a custom won date */
		$this->db->UpdateRevisionCreatedDate($this->revision_id, $this->__o['proposal_won_date']);
		
		$this->db->UpdateRevisionStatus($this->revision_id, PROPOSAL_REVISION_STATUS_CLIENT_ACCEPTED);
		$this->db->UpdateProposalStatus($this->proposal_id, PROPOSAL_STATUS_WON);
		
		$this->__UpdateRevisionMinMax();
   	
   	$this->__SendProposalWonMessage();
   
   	header("Location: ?e=". 
   		$this->encrypt->Encrypt("action=display_revision&proposal_id=" . $this->proposal_id . "&proposal_revision_id=" . $this->revision_id)
   	);
   
	}
	
	/**
	* __UpdateRevisionMinMax()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 20:33:32
	*/
	protected function __UpdateRevisionMinMax()
	{
		$minmax = $this->__GetRevisionMinMaxPrice();
		$this->db->UpdateRevisionMaxAmount($this->revision_id, $minmax['max']);
		$this->db->UpdateRevisionMinAmount($this->revision_id, $minmax['min']);
	}
	
	/**
	* __GetRevisionMinMaxPrice()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 22:23:46
	*/
	protected function __GetRevisionMinMaxPrice()
	{
		$min = 0; $max = 0;
		$totals = array();
		$this->__LoadRevisionSummary();
		
		$options = $this->db->GetRevisionOptions($this->revision_id);
		
		foreach ($options as $row) {
			switch ($this->revision['pricing_type_id']) {
				
				case PRICING_TYPE_STANDARD:
					$totals[$row['option_number']] += $this->db->GetProposalBudgetLineAmountByBudgetId($row['proposal_revision_option_id'], Pgen::PROPOSAL_BUDGET_TOTAL_COST);	
					break;
					
				case PRICING_TYPE_CUSTOM:
					$totals[$row['option_number']] += $this->db->GetOptionCustomPriceTotal($row['proposal_revision_option_id']);	
					break;
					
				default:
					break;
			}
		}
	
		sort($totals);
		
		$min = $totals[0];
		$max = $totals[ count($totals) - 1 ];
		
		return array('min' => $min, 'max' => $max);
	}
	
	/**
	* __LoadProposalUserSummary()
	*
	* @param
	* @param 
	* @return
	* @since  - 17:40:27
	*/
	function __LoadProposalUserSummary()
	{
		$this->proposal['user']	= $this->db->GetProposalUser($this->proposal_id);
	}
	
	/**
	* __SetupUsers()
	*
	* @param
	* @param 
	* @return
	* @since  - 19:23:51
	*/
	function __SetupUserArray($users)
	{
		$array = array();
		foreach ($users as $row) {
			$array[$row['role_id']]	= $row;
		}
		return $array;
	}
	
	/**
	* __LoadProposalUserDetails()
	*
	* @param
	* @param 
	* @return
	* @since  - 17:40:41
	*/
	function __LoadProposalUserDetails()
	{
		 $this->proposal['user'] = $this->db->GetProposalUser($this->proposal_id, true);
	}
	
	/**
	* __LoadProposalSummary()
	*
	* @param
	* @param 
	* @return
	* @since  - 11:36:03
	*/
	function __LoadProposalSummary()
	{
		if (empty($this->proposal) || $this->proposal['proposal_id'] != $this->proposal_id || $this->proposal['detail']  == true) {
			$this->proposal = $this->db->GetProposalDetail($this->proposal_id);
			$this->proposal['detail'] = false;
		}
	}	
	
	/**
	* __LoadProposalDetail()
	*
	* @param
	* @param 
	* @return
	* @since  - 11:36:31
	*/
	function __LoadProposalDetails()
	{
		if (empty($this->proposal) || $this->proposal['proposal_id'] != $this->proposal_id || $this->proposal['detail']  == false) {
			$this->proposal = $this->db->GetProposalDetail($this->proposal_id, true);
			$this->proposal['detail'] = true;
			
			//also load contact
			$this->proposal['contact'] = $this->db->GetProposalContact($this->proposal_id);
			
			//also load user
			$this->proposal['user'] = $this->db->GetProposalUser($this->proposal_id, true);
		}
	}
	/**
	* __LoadProposalAttr()
	*
	* @param
	* @param 
	* @return
	* @since  - 14:51:03
	*/
	function __LoadProposalAttr()
	{
		if (empty($this->proposa['attr']) || $this->proposal['attr']['proposal_id'] != $this->proposal_id) {
			$this->proposal['attr'] = $this->SetArrayKey($this->db->GetAllProposalAttr($this->proposal_id), 'proposal_attr_name', 'proposal_attr_value');
			$this->proposal['attr']['proposal_id'] = $this->proposal_id;
		}
	}
	/**
	* __LoadRevisionDetail()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 00:03:22
	*/
	protected function __LoadRevisionSummary()
	{
		if (empty($this->revision) || $this->revision['proposal_revision_id'] != $this->revision_id || $this->revision['detail']  == true) {
			$this->revision = $this->db->GetRevisionDetail($this->revision_id);
			$this->revision['detail'] = false;
		}
	}
	
	/**
	* __LoadRevisionDetails()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 00:06:36
	*/
	protected function __LoadRevisionDetails()
	{
		if (empty($this->revision) || $this->revision['proposal_revision_id'] != $this->revision_id || $this->revision['detail'] == false) {
			
			$this->revision = $this->db->GetRevisionDetail($this->revision_id, true);
			$this->revision['detail'] = true;			
			
			/* direct db */
			$this->revision['sample_type']   = $this->db->GetRevisionSampleTypes($this->revision_id, true);
			$this->revision['service_list']  = $this->db->GetRevisionServiceList($this->revision_id, true);
			$this->revision['comments']      = $this->db->GetRevisionComments($this->revision_id);
			
			/* special processing */
			$this->revision['country']       = $this->__GetRevisionCountryList();			
			$this->revision['sample_source'] = $this->__GetRevisionSampleSourceList();
			
			/* meta data */
			$this->revision['options']         = $this->__ComputeOptionSummary();
			$this->revision['budget']   = $this->__GetStandardPricingOptionAggr(false);
			$this->revision['meta']     = $this->__GetRevisionMetaData();
			$this->revision['budget_def'] = $this->__GetRevisionBudgetDef();
		}
	}
	
	
	
	/**
	* __GetRevisionBudgetDef()
	*
	* @param
	* @param 
	* @return
	* @since  - 13:24:33
	*/
	function __GetRevisionBudgetDef()
	{
		$b_def = array();
		$std = $this->db->GetRevisionStandardPrice($this->revision_id, false);
		
		foreach ($std as $bd) {
			
			if ($bd['display_on_pdf'] == 0 || $bd['amount'] == 0) continue;
						
			$b_def[$bd['proposal_budget_line_item_id']] = $bd;
		}
		
		return $b_def;
		
	}
	
	/**
	* __GetRevisionMetaData()
	*
	* @param
	* @param 
	* @return
	* @since  - 15:29:24
	*/
	function __GetRevisionMetaData()
	{
		$pos = array(6, 5, 4);
		$study_programming_type_id = 0;
		$questions_programmed      = 0;
		$option_cols               = 0;
		$option_rows               = 0;
		$country_cols              = 0;
		$country_rows              = 0;
		
		$options = $this->db->GetRevisionOptions($this->revision_id);
		
		foreach ($options as $option) {
			$questions_programmed += $option['questions_programmed'];
			
			if ($study_programming_type_id === 0) {
				$study_programming_type_id = $option['study_programming_type_id'];
			}
		}
		if(count($options) != 0)
			$questions_programmed = $questions_programmed / count($options);
		
		/* by default we decide to use 5 cols */
		$option_rows = ceil($this->revision['number_of_options'] / 5); 
		$option_cols = ($this->revision['number_of_options'] < 5) ? $this->revision['number_of_options'] : 5;
		
		/* by default we decide to use 5 cols */
		$country_rows = ceil($this->revision['number_of_countries'] / 5); 
		$country_cols = ($this->revision['number_of_countries'] < 5) ? $this->revision['number_of_countries'] : 5;
		
		/* lets see if we can get better alignment */
		foreach ($pos as $num) {
			if (($this->revision['number_of_options'] % $num) == 0)  {
				$option_cols = $num; 
				$option_rows = $this->revision['number_of_options'] / $num;
				break;
			}	
		}
		
		/* lets see if we can get better alignment */
		foreach ($pos as $num) {
			if (($this->revision['number_of_countries'] % $num) == 0)  {
				$country_cols = $num; 
				$country_rows = $this->revision['number_of_countries'] / $num;
				break;
			}	
		}
		
		$this->__SetCurrencyParameters();
		
		return array(
			'questions_programmed'      => $questions_programmed,
			'study_programming_type_id' => $study_programming_type_id,
			'option_rows'               => $option_rows,
			'option_cols'               => $option_cols,
			'country_rows'              => $country_rows,
			'country_cols'              => $country_cols,
			'currency_unicode'          => $this->__currency_unicode
		);
		
	}
	
	/**
	* __ComputeOptionSummary()
	*
	* @param
	* @param 
	* @return
	* @since  - 15:09:57
	*/
	function __ComputeOptionSummary()
	{
		$opt = array();
		
		$options = $this->db->GetRevisionOptions($this->revision_id);
		
		foreach ($options as $option) {
			$opt[$option['option_number']]['questions_programmed'] += $option['questions_programmed'];	
			$opt[$option['option_number']]['option_cpc'] += $option['option_cpc'];
			$opt[$option['option_number']]['completes'] += $option['completes'];
			$opt[$option['option_number']]['panel_total'] += $option['panel_total'];
			$opt[$option['option_number']]['option_total'] += $option['option_total'];
			$opt[$option['option_number']]['incidence_rate'] += $option['incidence_rate'];
		}
		
		foreach ($opt as $n => $option) {
			$opt[$n]['average_questions_programmed'] = $option['questions_programmed'] / $this->revision['number_of_countries'];
			$opt[$n]['q_length'] = $opt[$n]['average_questions_programmed'] / 2.5;
			$opt[$n]['average_option_cpc'] = $option['option_cpc'] / 1;
			$opt[$n]['average_panel_cpc'] = $option['panel_total'] / $option['completes'];
			$opt[$n]['average_incidence_rate'] = $option['incidence_rate'] / $this->revision['number_of_countries'];
		}
		
		return $opt;
		
	}
	
	/**
	* __GetRevisionSampleSourceList()
	*
	* @param
	* @param 
	* @return
	* @since  - 14:48:57
	*/
	function __GetRevisionSampleSourceList()
	{
		$sample_source = array();
		
		$rs = $this->db->GetRevisionOptions($this->revision_id);
		
		$ds = $this->db->GetStudyDataSources();
		
		foreach ($rs as $row) {
			$sample_source[$row['study_datasource_id']] = $ds[$row['study_datasource_id']];
		}
		
		return $sample_source;
		
	}
	
	/**
	* __GetRevisionCountryList()
	*
	* @param
	* @param 
	* @return
	* @since  - 14:20:03
	*/
	function __GetRevisionCountryList()
	{
		$country = array();
		
		$rs = $this->db->GetRevisionOptions($this->revision_id);
		
		foreach ($rs as $row) {
			$country[$row['country_code']] = $row['country_code'];
		}
		
		return $country;
	}
	
	/**
	* __GetRevisionComments()
	*
	* @param
	* @param 
	* @todo should reset 
	* @return
	* @since  - 23:41:03
	*/
	function __GetRevisionComments()
	{
		$rs = $this->db->GetRevisionComments($this->revision_id);
   
		foreach ($rs as $r) {
			$comment[$r['proposal_revision_comment_type_id']] = $r;
		}
		
		return $comment;
	}
	
	/**
	* __GetNotifyRcptList()
	*
	* @param
	* @param 
	* @return
	* @since  - 20:52:53
	*/
	protected function __GetNotifyRcptList()
	{
		$rcpt = array();
		$rcpt[] = $this->revision['created_by']; //add the creator as the default recepient
		$rcpt[] = $this->proposal['user'][ROLE_PRIMARY_ACCT_EXEC]['login'];
		$rcpt[] = $this->proposal['user'][ROLE_PRIMARY_ACCT_MGR ]['login'];
		
		$approval = $this->__GetApprovalAttr();
		
		if (empty($approval)) {
			return $rcpt;
		}
		
		$rcpt = array_merge($rcpt, $this->__GetApprovalGroupMembers($approval['proposal_review_group_id'])); 
		
		return $rcpt;
	}
	
	/**
	* __LoadOptionSummary()
	*
	* @param
	* @param 
	* @return
	* @since  - 20:57:35
	*/
	protected function __LoadOptionsSummary()
	{
		$revision_options = $this->db->GetRevisionOptions($this->revision_id);
		
		foreach ($revision_options as $option) {
			
			@$option['panel_cpi'] = $option['panel_total'] / $option['completes'];
			
			if ($this->revision['number_of_countries'] == 1 && $this->revision['number_of_options'] >= 1) {
				$this->options[ $option['option_number'] ] = $option;
			} else {
				$this->options[ $option['option_number'] ][ $option['sort_order'] ] = $option;
			}
		}
		
	}
	
	/**
	* __GetCustomPricingSummary()
	*
	* @param
	* @param 
	* @todo Refactoring not compelted,  Phase 1 complete (0811)
	* @return
	* @since  - 21:11:19
	*/
	function __GetCustomPricingSummary()
	{
		
		$pricing = array();

		$custom = $this->db->GetRevisionCustomPrice($this->revision_id);

		foreach ($custom as $idx => $row) {
			if ($this->revision['number_of_countries'] == 1 && $this->revision['number_of_options'] >= 1) {
				$pricing [$row['option_number']] ['group'] [$row['pricing_item_group_id']] = $row['amount'];	
				$pricing [$row['option_number']] ['total'] += $row['amount'];	
			} else {
				$pricing [$row['option_number']] [$row['sort_order']] ['group'] [$row['pricing_item_group_id']] = $row['amount'];	
				$pricing [$row['option_number']] [$row['sort_order']] ['total']  += $row['amount'];	
			}
		}
		
		return $pricing;
	}
	
	/**
	* __GetStandardPricingSummary()
	*
	* @param
	* @param 
	* @todo  Not Yet Completed
	* @return
	* @since  - 21:12:07
	*/
	function __GetStandardPricingSummary()
	{
		$pricing = array();
		
		$std = $this->db->GetRevisionStandardPrice($this->revision_id);
		
		foreach ($std as $idx => $row) {
			if ($this->revision['number_of_countries'] == 1 && $this->revision['number_of_options'] >= 1) {
				$pricing [$row['option_number']] ['group'] [$row['proposal_budget_line_item_id']] = $row['amount'];	
				$pricing [$row['option_number']] ['total'] += $row['amount'];	
			} else {
				$pricing [$row['option_number']] [$row['o_sort_order']] ['group'] [$row['proposal_budget_line_item_id']] = $row['amount'];	
				$pricing [$row['option_number']] [$row['o_sort_order']] ['total']  += $row['amount'];	
			}
		}
		
		return $pricing;
	}
	
	/**
	* __GetStandardPricingSummaryOptionAggr()
	*
	* @param
	* @param 
	* @return
	* @since  - 11:26:27
	*/
	function __GetStandardPricingOptionAggr($summary = true)
	{
		$pricing = array();
		
		$std = $this->db->GetRevisionStandardPrice($this->revision_id, $summary);
		
		foreach ($std as $idx => $row) {
			$pricing [$row['option_number']][$row['proposal_budget_line_item_id']] += $row['amount'];	
		}
		
		return $pricing;
	}
	
	/**
	* __LoadPricingSummary()
	*
	* @param
	* @param 
	* @return
	* @since  - 21:07:53
	*/
	protected function __LoadPricingSummary()
	{
		if (!isset($this->revision['pricing_type_id'])) {
			throw new Exception("Revision Pricing Type Not Set", 1);
		}
		
		if ($this->revision['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
			$this->revision['custom_pricing'] = $this->__GetCustomPricingSummary();
		} else {
			$this->revision['std_pricing'] = $this->__GetStandardPricingSummary();
		}
		
	}
	
	/**
	* __SendProposalWonMessage()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 20:27:01
	*/
	protected function __SendProposalWonMessage()
	{
		$this->__LoadProposalDetails();
		$this->__LoadRevisionDetails();
		
		$message_type_id = MODULE_ID_PROPOSAL . MESSAGE_PROPOSAL_WON;
		$subject         = "PROPOSAL WON: ". $this->proposal['account_name'] . " - " . $this->proposal['proposal_name'];
		
		$body = $this->__GetProposalMessage(MESSAGE_PROPOSAL_WON);
		$attr = $this->__GetProposalMessageAttr();
		$rcpt = $this->__GetNotifyRcptList();		
		
		$this->__SendMessage($message_type_id, $subject, $body, $attr, $rcpt);
   	
		return true;
		
	}
	
	/**
	* __GetProposalMessageAttr()
	*
	* @param
	* @param 
	* @return
	* @since  - 15:43:05
	*/
	function __GetProposalMessageAttr()
	{
		$sample_type_id = array();
		foreach($this->revision["sample_type"] as $id => $sample_type) {
			$sample_type_id[$id] = $sample_type["sample_type_id"];
		}
		return array(
			'account_id'    => $this->proposal['account_id'],
			'primary_ae_id' => $this->proposal['user'][ROLE_PRIMARY_ACCT_EXEC]['login'],
			'primary_am_id' => $this->proposal['user'][ROLE_PRIMARY_ACCT_MGR]['login'],
			'sample_type_id' => $sample_type_id
		);
		
	}
	
	/**
	* __SendMessage()
	*
	* @param
	* @param 
	* @return
	* @since  - 22:32:00
	*/
	function __SendMessage($message_type_id, $subject, $body, $attr, $rcpt)
	{
		$params = array(
   		'message_type_id' => $message_type_id,
   		'protocol' => 'v2',
   		'msg' => array(
   			'subject' => $subject, 
   			'body' => $body
   		),
   	 	'attr' => $attr,
   		'rcpt' => $rcpt
   	);
		
//   	@runkit_function_remove("HBRPC_GetPortlet");
   	HBRPCCall("com", "QueueMessage", $params);
	}
	
	/**
	* SendApprovalNotice()
	*
	* @param
	* @param 
	* @return
	* @since  - 19:43:24
	*/
	public function SendProposalApprovalMessage()
	{
		$this->LoadEncryption();
		$this->LoadSmarty();
		
		$this->__SetProposalAndRevisionId();
		
   		$_SESSION['pgen_message'] = "Request Sent For Approval";
		
		if (!$this->__SendProposalApprovalMessage()) {
			$_SESSION['pgen_message'] = "This proposal does not require approval";
		}
		
		header(
			"Location: ?e=". $this->encrypt->Encrypt(
				"action=display_revision&proposal_id=". $this->proposal_id ."&proposal_revision_id=". $this->revision_id
			)
		);
	}
	
	/**
	* __SendProposalMessage()
	*
	* @param
	* @param 
	* @return
	* @since  - 10:16:32
	*/
	private function __GetProposalMessage($message_type, $attr = array())
	{
		$this->__LoadProposalDetails();
		
		$this->__LoadRevisionDetails();
   	
		$this->__LoadProposalUserDetails();
		
		$this->__LoadOptionsSummary();
   	/* TODO add GetProposalWithAllDesc */
		
		switch ($message_type) {
			case MESSAGE_PROPOSAL_APPROVAL_REQUIRED:
				$meta['title'] = "This proposal requires your approval. Reason for approval: ". $attr['reason_description'];
				break;
				
			case MESSAGE_PROPOSAL_WON:
				$meta['title'] = 'The following proposal was accepted by client';
				break;
		
			default:
				break;
		}
		
		$this->smarty->assign('proposal', $this->proposal);
   		$this->smarty->assign('revision', $this->revision);
		$this->smarty->assign('options', $this->options);
		$this->smarty->assign('list', $this->list);
		$this->smarty->assign('meta', $meta);

		
		$msg['screen']   = $this->smarty->fetch('app/pgen/eml_internal_proposal.tpl');
		$msg['handheld'] = $this->smarty->fetch('app/pgen/hnd_internal_proposal.tpl');
		
		return $msg;
		
	}
	
	/**
	* __GetApprovalAttr()
	*
	* @access private
	* @return array('proposal_revision_group_id', 'reason_code', 'reason_description')
	* @since  - 19:49:51
	*/
	private function __GetApprovalAttr()
	{
		$this->__LoadProposalSummary();
		$this->__LoadProposalAttr();
		$this->__LoadRevisionSummary();
		
		if (isset($this->proposal['attr']['GLOBAL_CS_WATCH_LIST']) && $this->proposal['attr']['GLOBAL_CS_WATCH_LIST'] == 1) {
			
			return array(
				'proposal_review_group_id' => PROPOSAL_REVIEW_GROUP_CS,
				'reason_code'              => PROPOSAL_APPROVAL_REASON_CS_WATCH_LIST,
				'reason_description'       => 'Account is in client service watch list'
			);
		}
		
		if ($this->revision['max_amount'] >= 100000) {
			
			return array(
				'proposal_review_group_id' => PROPOSAL_REVIEW_GROUP_CS,
				'reason_code'              => PROPOSAL_APPROVAL_REASON_OVER_100K,
				'reason_description'       => 'Proposal value is over 100,000 Dollars'
			);
			
		}
		
		if ($this->revision['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
			
		}
		
		return array();
		
	}
	
	/**
	* __SendApprovalNotice()
	*
	* @param
	* @param 
	* @return
	* @since  - 19:48:41
	*/
	private function __SendProposalApprovalMessage()
	{
		$this->__LoadRevisionSummary();
		$this->__LoadProposalSummary();
		
		$approval = array();
		
		$approval = $this->__GetApprovalAttr();
		
		if (empty($approval)) {
			return false;
		}
		
		$this->__LoadProposalDetails();
		$this->__LoadRevisionDetails();
		$this->__LoadCountryList();
		
		$message_type_id = MODULE_ID_PROPOSAL . MESSAGE_PROPOSAL_APPROVAL_REQUIRED;
		$subject         = "PROPOSAL APPROVAL REQUIRED: ". $this->proposal['account_name'] . "-" . $this->proposal['proposal_name'];
		
		/* REFACTOR -> __GetApprovalGroupMembers() @return rcpt */
		$rcpt = $this->__GetApprovalGroupMembers($approval['proposal_review_group_id']);
		$body = $this->__GetProposalMessage(MESSAGE_PROPOSAL_APPROVAL_REQUIRED, $approval);
   	$attr = $this->__GetProposalMessageAttr();
   	  
  		$this->__SendMessage($message_type_id, $subject, $body, $attr, $rcpt);
	
  		$this->__SetReviewLog($approval['proposal_review_group_id'], $rcpt);
  		
   	//set the status to waiting for approval
   	$this->db->UpdateRevisionStatus($this->revision_id, PROPOSAL_REVISION_STATUS_WAITING_APPROVAL);
   	
   	return true;
		
	}
	
	/**
	* __GetApprovalGroupMembers()
	*
	* @param
	* @param 
	* @return
	* @since  - 20:00:12
	*/
	protected function __GetApprovalGroupMembers($proposal_review_group_id)
	{
		$members = array();
		$rows = $this->db->GetReviewGroupMembers($proposal_review_group_id);
		
		foreach ($rows as $row) {
			$members[] = $row['login'];			
		}
		
		return $members;
		
	}
	
	/**
	* __SetReviewLog()
	*
	* @param
	* @param 
	* @return
	* @since  - 15:33:21
	*/
	protected function __SetReviewLog($proposal_review_group_id, $members)
	{
		foreach ($members as $member) {
			if ($this->db->isMemberOfGroup($proposal_review_group_id, $member)) {
				$this->db->SetRevisionReviewLog($this->revision_id, $proposal_review_group_id, $member); 
			}
		}
	}
	
	/**
	* DownloadProposalDocument()
	*
	* @param
	* @param 
	* @return
	* @since  - 21:19:19
	*/
	public function DownloadProposalDocument()
	{
		$this->__SetProposalAndRevisionId();
		
		$this->__LoadProposalSummary();
		$this->__LoadRevisionSummary();
   
   	if (!$this->__isProposalApprovedForDownload()) {
   		$_SESSION['ppm_message'] = 'Proposal Not Approved for Download';
   		header("Location: ". $_SERVER['HTTP_REFERER']);
   	}

   	$this->__GenerateProposalDocument();
   	/* not refactored */
   	//$out_file = GenerateProposalDocument($o['proposal_id'], $o['proposal_revision_id']);
   
   
   	if ($o['manual_sent'] == 1) {
   		$p->UpdateRevisionStatus(PROPOSAL_REVISION_STATUS_MANUAL_SENT);
   		$p->SetRevisionDateSent();
   	}
   
   
   	$proposal['proposal_name'] = preg_replace("/[:\/\\\]/", "-", $proposal['proposal_name']);

   	//$attachment_name = "GMI_". $proposal['proposal_id']. "_". $revision['revision'] ."_". $proposal['proposal_name'].".pdf";
   	$attachment_name = "GMI_". $proposal['account_name']  . '_' 
      	              .         $proposal['proposal_name'] . '_'
         	           .         date("Y-m-d",strtotime($proposal['proposal_date'])) . '_'
            	        .         $proposal['proposal_id'] . "_"
               	     .         $revision['revision'] .".pdf";
   
		/* remaining code to be removed to common and SendStreamAsAttachment, SendFileAsAttachment */
	   header("Pragma: public"); // required
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); // required for certain browsers 
	   header('Content-Type: application/octet-stream');
		//header('Content-Type: application/pdf');
	   header('Content-Disposition: attachment; filename="'. $attachment_name);
	
	   $fp = fopen($out_file, 'r');
	   fpassthru($fp);
	   fclose($fp);

   	return true;
		
	}
	
	/**
	* __isProposalApprovedForDownload()
	*
	* @param
	* @param 
	* @return
	* @since  - 21:22:07
	*/
	protected function __isProposalApprovedForDownload()
	{
		switch ($this->revision['proposal_revision_status_id']) {
			case PROPOSAL_REVISION_STATUS_REJECTED:
			case PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL:
			case PROPOSAL_REVISION_STATUS_WAITING_APPROVAL:
			case PROPOSAL_REVISION_STATUS_PARTIALLY_APPROVED:
				return false;
				break;
		
			default:
				break;
		}
		
		return true;		
	}
	
	/**
	* __GenerateProposalDocument()
	*
	* @param
	* @param 
	* @return
	* @since  - 22:02:25
	*/
	function __GenerateProposalDocument()
	{
		$html = $this->__GenerateProposalDocumentHtml();

		/* should be generate PDF from HTML */
		$this->__GenerateProposalDocumentPdf();
   
   	return $out_file;
		
	}
	
	/**
	* __GenerateProposalDocumentPdf()
	*
	* @param
	* @param 
	* @return
	* @since  - 22:06:17
	*/
	function __GenerateProposalDocumentPdf()
	{
		$hash = md5(time());
   	$in_file  = "/tmp/proposal_input_". $hash .".html";
   	$out_file = "/tmp/proposal_output_". $hash .".pdf";
   
   	$fp = fopen($in_file, 'w+');
   	fputs($fp, $template_output);
   	fclose($fp);
   
   	$header['left'] =  $proposal['account_name'];
   	$header['center'] = "Proposal # ". $proposal_id .".". $revision['revision'];
   	$header['right'] = $proposal['proposal_date'];
   
   
   	CreatePdf($in_file, $out_file, $header);
		
	}
	
	/**
	* SetOptionTotal()
	*
	* Set the option_total fields in proposal_revision_option - TODO
	* 
	* @param
	* @param 
	* @return
	* @since  - 10:59:18
	*/
	function SetOptionTotal()
	{
		$this->proposal_id;
		$this->revision_id;
		
	}
	
	/**
	* __SetCurrencyParameters()
	*
	* @param
	* @param 
	* @return
	* @since  - 22:17:56
	*/
	function __SetCurrencyParameters()
	{
		$this->__LoadProposalAttr();
		
		if (!isset($this->proposal['attr']['GLOBAL_PREFFERED_CURRENCY']) ||  $this->proposal['attr']['GLOBAL_PREFFERED_CURRENCY'] == $this->__default_currency) {
			$this->__exchange_rate = 1;
			return true;
		}
		
		$this->__currency         = $this->proposal['attr']['GLOBAL_PREFFERED_CURRENCY'];
		$this->__exchange_rate    = $this->__GetExchangeRate();
		$this->__currency_unicode = $c->GetUnicodeCurrencySymbol($this->__currency);
   	
		if (!$this->__exchange_rate) {
			$this->__exchange_rate = $this->GetMonthlyExchangeRate($this->__currency);
			$this->__SetExchangeRate($this->__exchange_rate);
		}
   		
		return true;
	}
	
	/**
	* __SetExchangeRate()
	*
	* @param
	* @param 
	* @return
	* @since  - 22:29:33
	*/
	function __SetExchangeRate($exchange_rate)
	{
		 /* move to common db */
		$this->__SetProposalAttribute('PPM_EXCHANGE_RATE_USED', $exchange_rate);   	
	}
	
	/**
	* __GetExchangeRate()
	*
	* @param
	* @param 
	* @todo  move function to common rename as GetMonthlyExchangeRate 
	* @return
	* @since  - 22:34:07
	*/
	function __GetExchangeRate()
	{
		return $this->proposal['attr']['PPM_EXCHANGE_RATE_USED'];
	}
	
	/**
	* __SetProposalAttribute()
	*
	* @param
	* @param 
	* @return
	* @since  - 22:31:26
	*/
	function __SetProposalAttribute($attribute_name, $attribute_value)
	{
		if ($this->db->isProposalAttrSet($this->proposal_id, $attribute_name, $attribute_value)) {
			$this->db->UpdateProposalAttr($this->proposal_id, $attribute_name, $attribute_value);	
		} else {
			$this->db->SetProposalAttr($this->proposal_id, $attribute_name, $attribute_value);
		}
	}
	
	/**
	* __SetDocumentDisplayParams()
	*
	* @param
	* @param 
	* @return
	* @since  - 23:52:26
	*/
	function __GetDocumentDisplayParams()
	{
		$meta['display_tr_license_fee'] = 0;
		$meta['display_tr_setup_fee']   = 0;
		$meta['display_tr_hosting_fee'] = 0;
		$meta['display_tr_panel_fee']   = 0;
		$meta['display_tr_dp_fee']      = 0;
		$meta['display_txt_sub_group']  = 0;
		$meta['display_tr_fieldwork']   = 0;
		$meta['display_tr_setup']   = 0;
		$meta['display_tr_dataclean']   = 0;
		$meta['display_page_fd']        = 1;
		$meta['display_page_gc']        = 1;
		$meta['display_page_ps']        = 1;
		$meta['display_questions_programmed'] = 1;
		$meta['display_about_gmi_on_top'] = 0;
	
		if ($proposal['account_type'] == ACCOUNT_TYPE_PROSPECT) {
			$meta['display_about_gmi_on_top'] = 0;	
		}
		
	$rs = $p->GetRevisionPricingItemGroups();
	
	while ($r = mysql_fetch_assoc($rs)) {
		switch ($r['pricing_item_group_id']) {
		 	case PRICING_GROUP_SETUP:
		 		$meta['display_tr_setup'] = 1;
		 		$meta['display_tr_setup_fee'] = 1;
		 		break;
		 	case PRICING_GROUP_HOSTING:
		 	   $meta['display_tr_hosting_fee'] = 1;
		 	   break;
		 	case PRICING_GROUP_PANEL:
		 	   $meta['display_tr_panel_fee'] = 1;
		 	   $meta['display_tr_fieldwork'] = 1;
		 	   break;
		 	case PRICING_GROUP_DP:
		 	   $meta['display_tr_dp_fee'] = 1;
		 	   $meta['display_tr_dataclean'] = 1;
		 	default:
		 		break;
		 } 
	}
	
	if ($revision['proposal_type_id'] == PROPOSAL_TYPE_SAMPLEONLY) {
		$meta['display_tr_hosting_fee'] = 0;
		$meta['display_tr_dataclean']   = 0;
		$meta['display_tr_dp_fee']      = 0;
		$meta['display_questions_programmed'] = 0;
	}
		
	if ($revision['pricing_type_id'] == PRICING_TYPE_CUSTOM)
		$meta['display_tr_license_fee'] = 0;
		
	if ($revision['proposal_option_type_id'] == PROPOSAL_OPTION_TYPE_SINGLE_SUB) 
		$meta['display_txt_sub_group'] = 1;
		
	//print_r(count_chars(trim($comment['final_deliverables'])));		
	if (trim($comment['final_deliverables']) == '') 
		$meta['display_page_fd'] = 0;
		
	if (trim($comment['general_comment']) == '')
		$meta['display_page_gc'] = 0;
		
	if (trim($comment['qualifying_criteria']) == '')
		$meta['display_page_ps'] = 0;
		
	}
	
	/**
	* __GenerateProposalDocumentHtml()
	*
	* @param
	* @param 
	* @return
	* @since  - 22:03:13
	*/
	function __GenerateProposalDocumentHtml()
	{
		$this->LoadSmarty();
		
		$this->__LoadProposalDetails();
		$this->__LoadRevisionDetails();
		$this->__LoadOptionsSummary();
		$this->__LoadPricingSummary(); // load pricing summary
		
   	$list['study_programming_type'] = $this->db->GetStudyProgrammingTypes();   	
   	$list['country']                = $this->db->GetCountryList();
   	
//   	$proposal['rfp_email']        = $c->GetRegionAttrValue($proposal['region_id'], 'RFP_EMAIL_ADDRESS');
//   	$proposal['questionair_time'] = $proposal['questions_per_interview'] / 2.5;
		
		$this->smarty->assign('proposal', $this->proposal);
		$this->smarty->assign('revision', $this->revision);
		$this->smarty->assign('options', $this->options);
		$this->smarty->assign('list', $list);
		
		$this->__o['html'] = $this->smarty->fetch('app/pgen/pdf_proposal_single.tpl');
		if ($this->__o['htmlonly'] == 1) {
			echo $this->__o['html'];	
		} else {
			$this->PublishPdf();	
		}
		
		exit();
		
	}
	
	/**
	* LoadBackwardCompatibility()
	*
	* @param
	* @param 
	* @return
	* @since  - 17:06:16
	*/
	function LoadBackwardCompatibility()
	{
		/* BACKWARD COMPATIBILITY BEGIN */
		$this->proposal['ae_name'] = $this->proposal['user'][ROLE_PRIMARY_ACCT_EXEC]['first_name'] . " " . $this->proposal['user'][ROLE_PRIMARY_ACCT_EXEC]['last_name'];
		$this->proposal['am_name'] = $this->proposal['user'][ROLE_PRIMARY_ACCT_MGR]['first_name'] . " " . $this->proposal['user'][ROLE_PRIMARY_ACCT_MGR]['last_name'];
		
		$this->proposal['c_first_name'] = $this->proposal['contact']['first_name'];
		$this->proposal['c_last_name'] = $this->proposal['contact']['last_name'];
		$this->proposal['c_email_address'] = $this->proposal['contact']['email'];
		
		$this->proposal['account_type'] = $this->proposal['account_type_id'];
		/* BACKWORD COMPATIBILITY END */
	}
	

	/**
	* GetProposalReportData()
	*
	* @param
	* @param 
	* @return
	* @since  - 23:52:59
	*/
	function GetProposalReportData()
	{
		$c = new commonDB();
		
		$list['user'] = $this->CreateSmartyArray($c->GetUsers(), 'login', 'name');
		
		$proposals = $this->db->GetProposals();
		
		foreach ($proposals as $proposal_revision_id => $proposal) {
			//average cpc
			$proposals[$proposal_revision_id]['average_cpc'] = $this->GetAverageCostPerComplete($proposal_revision_id, 
				$proposal['number_of_options'], $proposal['number_of_countries']);
				
			//average incidence
			$proposals[$proposal_revision_id]['average_incidence_rate'] = $this->GetAverageIncidenceRate($proposal_revision_id, 
				$proposal['number_of_options'], $proposal['number_of_countries']);
			
			//average length of interview
			$proposals[$proposal_revision_id]['average_length_of_interview'] = $this->GetAverageLengthOfInterview($proposal_revision_id, 
				$proposal['number_of_options'], $proposal['number_of_countries']);
			
			//average number of compltes
			$proposals[$proposal_revision_id]['average_number_of_completes'] = $this->GetAverageNumberOfCompletes($proposal_revision_id, 
				$proposal['number_of_options'], $proposal['number_of_countries']);
			
			//SETUP SOME INITIAL values;
			$proposals[$proposal_revision_id]['project_setup_cost']   = 0;
			$proposals[$proposal_revision_id]['project_hosting_cost'] = 0;
			$proposals[$proposal_revision_id]['project_panel_cost']   = 0;
			$proposals[$proposal_revision_id]['project_dp_cost']      = 0;
				
			if ($this->revision['proposal_revision_status_id'] == PROPOSAL_REVISION_STATUS_CLIENT_ACCEPTED) {
				//project setup cost
				$proposals[$proposal_revision_id]['project_setup_cost'] = $this->GetProjectSetupCost($proposal['proposal_id'], $proposal_revision_id);
				
				//project hosting
				$proposals[$proposal_revision_id]['project_hosting_cost'] = $this->GetProjectHostingCost($proposal['proposal_id'], $proposal_revision_id);
				
				//project panel cost
				$proposals[$proposal_revision_id]['project_panel_cost'] = $this->GetProjectPanelCost($proposal['proposal_id'], $proposal_revision_id);
				
				//project dp cost
				$proposals[$proposal_revision_id]['project_dp_cost'] = $this->GetProjectDPCost($proposal['proposal_id'], $proposal_revision_id);
			}
		}
		
		$this->LoadSmarty();
		
		$this->smarty->assign('proposals', $proposals);
		$this->smarty->assign('list', $list);
		
		$file = $this->smarty->fetch('app/pgen/xls_proposal_report.tpl');
		
		$fp = fopen("/tmp/proposal_report.csv", "w+");
		fwrite($fp, $file);
		fclose($fp);
	}
	
	/**
	* GetAverageCostPerComplete()
	*
	* @param
	* @param 
	* @return
	* @since  - 00:04:28
	*/
	function GetAverageCostPerComplete($proposal_revision_id, $number_of_options, $number_of_countries)
	{
		if ($number_of_options == 0 || $number_of_countries == 0) return 0;
		
		$options = $this->db->GetRevisionOptionsV2($proposal_revision_id);
		
		$cpc = 0;
		
		foreach ($options as $proposal_revision_option_id => $option) {
			$cpc += $option['option_cpc'];
		}
		
		return $cpc / ($number_of_options * $number_of_countries);
	}
	
	/**
	* GetAverageIncidenceRate()
	*
	* @param
	* @param 
	* @return
	* @since  - 00:21:08
	*/
	function GetAverageIncidenceRate($proposal_revision_id, $number_of_options, $number_of_countries)
	{
		if ($number_of_options == 0 || $number_of_countries == 0) return 0;
		
		$options = $this->db->GetRevisionOptionsV2($proposal_revision_id);
		
		$incidence_rate = 0;
		
		foreach ($options as $proposal_revision_option_id => $option) {
			$incidence_rate += $option['incidence_rate'];
		}
		
		return $incidence_rate / ($number_of_options * $number_of_countries);
	}
	
//	/**
//	* GetAverageIncidenceRate()
//	*
//	* @param
//	* @param 
//	* @return
//	* @since  - 00:21:08
//	*/
//	function GetAverageIncidenceRate($proposal_revision_id, $number_of_options, $number_of_countries)
//	{
//		$options = $this->db->GetRevisionOptionsV2($proposal_revision_id);
//		
//		$incidence_rate = 0;
//		
//		foreach ($options as $proposal_revision_option_id => $option) {
//			$incidence_rate += $option['incidence_rate'];
//		}
//		
//		return $incidence_rate / ($number_of_options * $number_of_countries);
//	}
	
	/**
	* GetAverageLengthOfInterview()
	*
	* @param
	* @param 
	* @return
	* @since  - 00:36:37
	*/
	function GetAverageLengthOfInterview($proposal_revision_id, $number_of_options, $number_of_countries)
	{
		if ($number_of_options == 0 || $number_of_countries == 0) return 0;
		
		$options = $this->db->GetRevisionOptionsV2($proposal_revision_id);
		
		$length_of_interview = 0;
		
		foreach ($options as $proposal_revision_option_id => $option) {
			$length_of_interview += $option['questions_per_interview'] / 2.5;
		}
		
		return $length_of_interview / ($number_of_options * $number_of_countries);
		
	}
	
	/**
	* GetAverageNumberOfCompletes()
	*
	* @param
	* @param 
	* @return
	* @since  - 00:44:50
	*/
	function GetAverageNumberOfCompletes($proposal_revision_id, $number_of_options, $number_of_countries)
	{
		if ($number_of_options == 0 || $number_of_countries == 0) return 0;
		
		$options = $this->db->GetRevisionOptionsV2($proposal_revision_id);
		
		$number_of_completes = 0;
		
		foreach ($options as $proposal_revision_option_id => $option) {
			$number_of_completes += $option['completes'];
		}
		
		return $number_of_completes / ($number_of_options * $number_of_countries);
	}
	
	/**
	* GetProjectSetupCost()
	*
	* @param
	* @param 
	* @return
	* @since  - 00:48:22
	*/
	function GetProjectSetupCost($proposal_id, $proposal_revision_id)
	{
		$this->__SetProposalAndRevisionId($proposal_id, $proposal_revision_id);
		
		$this->__LoadRevisionDetails();
		
		$this->__LoadPricingSummary();
		
		if ($this->revision['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
			return $this->GetProjectSetupCostCustom();
		}
		
		return $this->GetProjectSetupCostStandard();
	}
	
	/**
	* GetProjectSetupCostCustom()
	*
	* @param
	* @param 
	* @return
	* @since  - 00:56:05
	*/
	function GetProjectSetupCostCustom()
	{
		$setup = 0;
		
		foreach ($this->revision['custom_pricing'][1] as $sort_order => $pricing) {
			$setup += $pricing['group'][PRICING_GROUP_SETUP];
		}
		
		return $setup;
	}
	
	/**
	* GetProjectSetupCostStandard()
	*
	* @param
	* @param 
	* @return
	* @since  - 00:56:24
	*/
	function GetProjectSetupCostStandard()
	{
		$setup = 0;
		
		foreach ($this->revision['std_pricing'][1] as $sort_order => $pricing) {
			$setup += $pricing['group'][PROPOSAL_BUDGET_TOTAL_PROJECT_SETUP];
		}
		
		return $setup;
	}
	
	/**
	* GetProjectHostingCost()
	*
	* @param
	* @param 
	* @return
	* @since  - 00:48:22
	*/
	function GetProjectHostingCost($proposal_id, $proposal_revision_id)
	{
		$this->__SetProposalAndRevisionId($proposal_id, $proposal_revision_id);
		
		$this->__LoadRevisionDetails();
		
		$this->__LoadPricingSummary();
		
		if ($this->revision['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
			return $this->GetProjectHostingCostCustom();
		}
		
		return $this->GetProjectHostingCostStandard();
	}
	
	/**
	* GetProjectHostingCostCustom()
	*
	* @param
	* @param 
	* @return
	* @since  - 00:56:05
	*/
	function GetProjectHostingCostCustom()
	{
		$hosting = 0;
		
		foreach ($this->revision['custom_pricing'][1] as $sort_order => $pricing) {
			$hosting += $pricing['group'][PRICING_GROUP_HOSTING];
		}
		
		return $hosting;
	}
	
	/**
	* GetProjectHostingCostStandard()
	*
	* @param
	* @param 
	* @return
	* @since  - 00:56:24
	*/
	function GetProjectHostingCostStandard()
	{
		$hosting = 0;
		
		foreach ($this->revision['std_pricing'][1] as $sort_order => $pricing) {
			$hosting += $pricing['group'][PROPOSAL_BUDGET_TOTAL_HOSTING];
		}
		
		return $hosting;
	}
	
	/**
	* GetProjectPanelCost()
	*
	* @param
	* @param 
	* @return
	* @since  - 00:48:22
	*/
	function GetProjectPanelCost($proposal_id, $proposal_revision_id)
	{
		$this->__SetProposalAndRevisionId($proposal_id, $proposal_revision_id);
		
		$this->__LoadRevisionDetails();
		
		$this->__LoadPricingSummary();
		
		if ($this->revision['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
			return $this->GetProjectPanelCostCustom();
		}
		
		return $this->GetProjectPanelCostStandard();
	}
	
	/**
	* GetProjectPanelCostCustom()
	*
	* @param
	* @param 
	* @return
	* @since  - 00:56:05
	*/
	function GetProjectPanelCostCustom()
	{
		$panel = 0;
		
		foreach ($this->revision['custom_pricing'][1] as $sort_order => $pricing) {
			$panel += $pricing['group'][PRICING_GROUP_PANEL];
		}
		
		return $panel;
	}
	
	/**
	* GetProjectPanelCostStandard()
	*
	* @param
	* @param 
	* @return
	* @since  - 00:56:24
	*/
	function GetProjectPanelCostStandard()
	{
		$panel = 0;
		
		foreach ($this->revision['std_pricing'][1] as $sort_order => $pricing) {
			$panel += $pricing['group'][PROPOSAL_BUDGET_TOTAL_PANEL];
		}
		
		return $panel;
	}
	
	/**
	* GetProjectDPCost()
	*
	* @param
	* @param 
	* @return
	* @since  - 00:48:22
	*/
	function GetProjectDPCost($proposal_id, $proposal_revision_id)
	{
		$this->__SetProposalAndRevisionId($proposal_id, $proposal_revision_id);
		
		$this->__LoadRevisionDetails();
		
		$this->__LoadPricingSummary();
		
		if ($this->revision['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
			return $this->GetProjectDPCostCustom();
		}
		
		return $this->GetProjectDPCostStandard();
	}
	
	/**
	* GetProjectDPCostCustom()
	*
	* @param
	* @param 
	* @return
	* @since  - 00:56:05
	*/
	function GetProjectDPCostCustom()
	{
		$dp = 0;
		
		foreach ($this->revision['custom_pricing'][1] as $sort_order => $pricing) {
			$dp += $pricing['group'][PRICING_GROUP_DP];
		}
		
		return $dp;
	}
	
	/**
	* GetProjectDPCostStandard()
	*
	* @param
	* @param 
	* @return
	* @since  - 00:56:24
	*/
	function GetProjectDPCostStandard()
	{
		$dp = 0;
		
		foreach ($this->revision['std_pricing'][1] as $sort_order => $pricing) {
			$dp += $pricing['group'][PROPOSAL_BUDGET_TOTAL_DP];
		}
		
		return $dp;
	}
	
	/**
	* GetCountryByRegion()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun Jul 24 00:03:47 PDT 2005
*/
	function GetCountryByRegion()
	{
		$c = new commonDB();
		$rs = $c->GetCountryByRegionId($this->__o['region_id']);


		$js = "<script language='javascript'> "
		. "  var o_fg = parent.document.getElementById('td_country'); "
		. "  o_fg.innerHTML = \"<select name='country_code'> ";

		while ($r = mysql_fetch_assoc($rs)) {
			$js .= " <option value='".$r['country_code']."'>".addslashes($r['country_description'])."</option>";
		}

		$js .= " </select>\"; "
		.  "</script>";

		print $js;

	}
	
	/**
* GetRegionByCountry()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun Jul 24 00:03:47 PDT 2005
*/
	function GetRegionByCountry()
	{
		$c = new commonDB();
		$r = $c->GetRegionByCountryCode($this->__o['country_code']);


		$js = "<script language='javascript'> "
		. "  var o_fg = parent.document.getElementById('region_id'); "
		. "  for (i=0; i< o_fg.options.length; i++) { "
		. "     if (o_fg.options[i].value == '".$r['region_id']."') { "
		. "        o_fg.selectedIndex = i; "
		. "     } "
		. "  } "
		. "</script>";

		print $js;

	}

}

?>