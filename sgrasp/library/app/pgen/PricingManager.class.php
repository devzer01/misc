<?php

class pgen_PricingManager
{
	/**
* GetPricingSummary()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun Jan 08 14:02:40 PST 2006
*/
	function GetPricingSummary($proposal_id, $proposal_revision_id)
	{
		$p = new proposalDB();
		$p->SetProposalId($proposal_id);
		$p->SetRevisionId($proposal_revision_id);

		$revision = $p->GetRevisionDetail();

		$rs_options = $p->GetRevisionOptions();

		while ($r_option = mysql_fetch_assoc($rs_options)) {

			if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] > 1) {
				$country[$r_option['option_number']] = $r_option['country_description'];
				$incidence[$r_option['option_number']] = $r_option['incidence_rate'];
				$completes[$r_option['option_number']] = $r_option['completes'];
				$qlength[$r_option['option_number']] = $r_option['questions_programmed'];
			} else {
				$country[$r_option['option_number']][$r_option['sort_order']] = $r_option['country_description'];
				$incidence[$r_option['option_number']][$r_option['sort_order']] = $r_option['incidence_rate'];
				$completes[$r_option['option_number']][$r_option['sort_order']] = $r_option['completes'];
				$qlength[$r_option['option_number']][$r_option['sort_order']] = $r_option['questions_programmed'];
			}


		}

		if ($revision['pricing_type_id'] == PRICING_TYPE_CUSTOM) {

			$rs = $p->GetRevisionCustomPricing();

			if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] > 1) {

				while ($r = mysql_fetch_assoc($rs)) {
					switch ($r['pricing_item_group_id']) {
						case PRICING_GROUP_SETUP:
							$setup[$r['option_number']] = $r['amount'];
							$total[$r['option_number']] += $r['amount'];
							break;
						case PRICING_GROUP_PANEL:
							$panel[$r['option_number']] = $r['amount'];
							$total[$r['option_number']] += $r['amount'];
							break;
						case PRICING_GROUP_HOSTING:
							$hosting[$r['option_number']] = $r['amount'];
							$total[$r['option_number']] += $r['amount'];
							break;
						case PRICING_GROUP_DP:
							$dp[$r['option_number']] = $r['amount'];
							$total[$r['option_number']] += $r['amount'];
							break;
					}
				}

				for ($i = 1; $i <= $revision['number_of_options']; $i++) {
					$cpc[$i] = $total[$i] / $completes[$i];
				}

			} else {

				while ($r = mysql_fetch_assoc($rs)) {
					switch ($r['pricing_item_group_id']) {
						case PRICING_GROUP_SETUP:
							$setup[$r['option_number']][$r['sort_order']] = $r['amount'];
							$total[$r['option_number']][$r['sort_order']] += $r['amount'];
							break;
						case PRICING_GROUP_PANEL:
							$panel[$r['option_number']][$r['sort_order']] = $r['amount'];
							$total[$r['option_number']][$r['sort_order']] += $r['amount'];
							break;
						case PRICING_GROUP_HOSTING:
							$hosting[$r['option_number']][$r['sort_order']] = $r['amount'];
							$total[$r['option_number']][$r['sort_order']] += $r['amount'];
							break;
						case PRICING_GROUP_DP:
							$dp[$r['option_number']][$r['sort_order']] = $r['amount'];
							$total[$r['option_number']][$r['sort_order']] += $r['amount'];
							break;
					}
				}

				for ($o = 1; $o <= $revision['number_of_options']; $o++) {
					for ($c = 1; $c <= $revision['number_of_countries']; $c++) {
						$cpc[$o][$c] = $total[$o][$c] / $completes[$o][$c];
					}
				}

			}

		} else {

			$rs = $p->GetRevisionBudgetLineItemSummary($proposal_revision_id);

			if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] > 1) {

				while ($r = mysql_fetch_assoc($rs)) {
					switch ($r['proposal_budget_line_item_id']) {
						//            	case PROPOSAL_BUDGET_LICENSE:
						//            	  $license[$r['option_number']]	= $r['amount'];
						//            	  $total[$r['option_number']] += $r['amount'];
						//            	  break;
						case PROPOSAL_BUDGET_TOTAL_PROJECT_SETUP:
							$setup[$r['option_number']]	= $r['amount'];
							$total[$r['option_number']] += $r['amount'];
							break;
						case PROPOSAL_BUDGET_TOTAL_HOSTING:
							$hosting[$r['option_number']]	= $r['amount'];
							$total[$r['option_number']] += $r['amount'];
							break;
						case PROPOSAL_BUDGET_TOTAL_PANEL:
							$panel[$r['option_number']]	= $r['amount'];
							$total[$r['option_number']] += $r['amount'];
							break;
						case PROPOSAL_BUDGET_TOTAL_DP:
							$dp[$r['option_number']]	= $r['amount'];
							$total[$r['option_number']] += $r['amount'];
							break;
						default:
							break;
					}
					$options[$r['sort_order']][$r['option_number']] = $r;
				}

				for ($i = 1; $i <= $revision['number_of_options']; $i++) {
					$cpc[$i] = $total[$i] / $completes[$i];
				}

			} else {

				while ($r = mysql_fetch_assoc($rs)) {
					switch ($r['proposal_budget_line_item_id']) {
						//            	case PROPOSAL_BUDGET_LICENSE:
						//            	  $license[$r['option_number']][$r['o_sort_order']] = $r['amount'];
						//            	  $total[$r['option_number']][$r['o_sort_order']] += $r['amount'];
						//            	  break;
						case PROPOSAL_BUDGET_TOTAL_PROJECT_SETUP:
							$setup[$r['option_number']][$r['o_sort_order']]	= $r['amount'];
							$total[$r['option_number']][$r['o_sort_order']] += $r['amount'];
							break;
						case PROPOSAL_BUDGET_TOTAL_HOSTING:
							$hosting[$r['option_number']][$r['o_sort_order']]	= $r['amount'];
							$total[$r['option_number']][$r['o_sort_order']] += $r['amount'];
							break;
						case PROPOSAL_BUDGET_TOTAL_PANEL:
							$panel[$r['option_number']][$r['o_sort_order']]	= $r['amount'];
							$total[$r['option_number']][$r['o_sort_order']] += $r['amount'];
							break;
						case PROPOSAL_BUDGET_TOTAL_DP:
							$dp[$r['option_number']][$r['o_sort_order']]	= $r['amount'];
							$total[$r['option_number']][$r['o_sort_order']] += $r['amount'];
							break;
						default:
							break;
					}
					$options[$r['option_number']][$r['sort_order']][] = $r;
				}

				for ($o = 1; $o <= $revision['number_of_options']; $o++) {
					for ($c = 1; $c <= $revision['number_of_countries']; $c++) {
						$cpc[$o][$c] = $total[$o][$c] / $completes[$o][$c];
					}
				}
			}
		}

		$summary['cpc'] = $cpc;
		$summary['total'] = $total;
		$summary['country'] = $country;

		return $summary;

	}

	/**
* GetProposalSummaryGroups()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Feb 28 12:06:58 PST 2006
*/
	function GetProposalSummaryGroups($proposal_id, $proposal_revision_id)
	{
		$group = array('setup' => 0, 'hosting' => 0, 'panel' => 0, 'dp' => 0);

		$p = new proposalDB();

		$p->SetProposalId($proposal_id);
		$p->SetRevisionId($proposal_revision_id);

		$rs = $p->GetRevisionPricingItemGroups();

		while ($r = mysql_fetch_assoc($rs)) {
			switch ($r['pricing_item_group_id']) {
				case PRICING_GROUP_SETUP:
					$group['setup'] = 1;
					break;
				case PRICING_GROUP_HOSTING:
					$group['hosting'] = 1;
					break;
				case PRICING_GROUP_PANEL:
					$group['panel'] = 1;
					break;
				case PRICING_GROUP_DP:
					$group['dp'] = 1;
				default:
					break;
			}
		}
		return $group;
	}


	/**
* CalculatePricing()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Dec 12 18:26:32 PST 2005
*/
	function CalculatePricing($account_id, $license_level_id, $proposal_id, $proposal_revision_id, $update_flag = 0)
	{
		//when doing an update erase them and re-set them again
		$p = new proposalDB();
		$p->SetProposalId($proposal_id);
		$p->SetRevisionId($proposal_revision_id);


		//retrive pricing and then create the pricing grid and save it to proposal_revision_pricing

		/*
		1. get basic license level price
		2. apply additional rules based on user input
		3. apply inflators
		4. apply discounts
		5. save to table
		*/

		//get base pricing
		$ar_budget = PrepareSmartyArray($p->GetDefaultPartnerPricing($account_id));

		//set the pricing items on a index array
		foreach ($ar_budget as $key => $value) {
			$ar_partner_budget[$ar_budget[$key]['pricing_item_id']] = $ar_budget[$key];
		}

		//get license level base pricing and discounts
		$ar_budget = PrepareSmartyArray($p->GetDefaultPricingByLicenseLevel($license_level_id));

		//set the pricing on array index
		foreach ($ar_budget as $key => $value) {
			$ar_license_budget[$ar_budget[$key]['pricing_item_id']] = $ar_budget[$key];
		}

		//we can do this also on a query but i choose to do this here since i am trying to stay away from using business logic
		//in the queries
		foreach ($ar_license_budget as $key => $value) {
			$budget[$key] = $ar_license_budget[$key];

			if (!is_null($ar_partner_budget[$key]['amount'])) {
				$budget[$key]['amount'] = $ar_partner_budget[$key]['amount'];
			}

			if (!is_null($ar_partner_budget[$key]['cd_amount'])) {
				$budget[$key]['cd_amount'] = $ar_partner_budget[$key]['cd_amount'];
			}

			if (!is_null($ar_partner_budget[$key]['ncd_amount'])) {
				$budget[$key]['ncd_amount'] = $ar_partner_budget[$key]['ncd_amount'];
			}

			if (!is_null($ar_partner_budget[$key]['pd_amount'])) {
				$budget[$key]['pd_amount'] = $ar_partner_budget[$key]['pd_amount'];
			}

			if (!is_null($ar_partner_budget[$key]['inflator_amount'])) {
				$budget[$key]['inflator_amount'] = $ar_partner_budget[$key]['inflator_amount'];
			}

		}

		//get basic proposal attributes
		//$r_proposal = $p->GetBasicDetail();
		$r_proposal = $p->GetRevisionDetail();

		//we need to retrive custom rules
		$rs_pricing_rules = $p->GetPricingRules();
		//array_walk($list['partner_budget'], 'MergePricingById', $list['budget']);

		//get available fields for rules
		$rs_rule_input = $p->GetPricingRuleTables();

		while ($r_rule_input = mysql_fetch_assoc($rs_rule_input)) {
			if (isset($r_proposal[$r_rule_input['input_field_name']])) { /* we need to check if we have the input_fields in our basic proposal detail */
				while ($r_pricing_rule = mysql_fetch_assoc($rs_pricing_rules)) {
					$rule_result = 0;
					$result = preg_replace("/" . $r_rule_input['input_field_name'] ."/", $r_proposal[$r_rule_input['input_field_name']], $r_pricing_rule['rule_condition']);
					eval("if (". $result .") \$rule_result = 1;");

					if ($rule_result === 1) {
						$this->ModifyLicenseLevelPricingItem($budget, $r_pricing_rule['license_level_pricing_item_id'], $r_pricing_rule['amount']);
					}
				}
			}
		}

		$this->ApplyInflatorsAndDiscount($budget);

		//save in proposal_reivion_pricing
		foreach ($budget as $key => $value) {
			if ($update_flag == 0) {
				$p->SetRevisionPrice($proposal_revision_id, $budget[$key]['pricing_item_id'], $budget[$key]['amount'], $budget[$key]['inflator_amount'],
				$budget[$key]['cd_amount'], $budget[$key]['ncd_amount'], $budget[$key]['pd_amount'], '0', $budget[$key]['net_amount']);
			} else {
				$p->UpdateRevisionPriceByRevision($proposal_revision_id, $budget[$key]['pricing_item_id'], $budget[$key]['amount'], $budget[$key]['inflator_amount'],
				$budget[$key]['cd_amount'], $budget[$key]['ncd_amount'], $budget[$key]['pd_amount'], '0', $budget[$key]['net_amount']);
			}
		}


		//panel cost
		//Get Base Panel Discount and Inflators based on pricing regime
		$ar_panel = PrepareSmartyArray($p->GetPanelCostByPartner($account_id));

		foreach ($ar_panel as $key => $value) {
			$ar_partner_panel[$ar_panel[$key]['panel_cost_type_id']] = $ar_panel[$key];
		}

		//Get Partner Override Discounts
		$ar_panel = PrepareSmartyArray($p->GetPanelCostByLicenseLevel($license_level_id));

		foreach ($ar_panel as $key => $value) {
			$ar_license_panel[$ar_panel[$key]['panel_cost_type_id']] = $ar_panel[$key];
		}

		foreach ($ar_license_panel as $key => $value) {

			$panel[$key] = $ar_license_panel[$key];

			if (!is_null($ar_partner_panel[$key]['inflator_amount'])) {
				$panel[$key]['inflator_amount'] = $ar_partner_panel[$key]['inflator_amount'];
			}

			if (!is_null($ar_partner_panel[$key]['contracted'])) {
				$panel[$key]['contracted'] = $ar_partner_panel[$key]['contracted'];
			}

			if (!is_null($ar_partner_panel[$key]['non_contracted'])) {
				$panel[$key]['non_contracted'] = $ar_partner_panel[$key]['non_contracted'];
			}

			if (!is_null($ar_partner_panel[$key]['promotional'])) {
				$panel[$key]['promotional'] = $ar_partner_panel[$key]['promotional'];
			}

			if ($update_flag == 0) {
				//set the panel cost
				$p->SetRevisionPanelCost($panel[$key]['panel_cost_type_id'], $proposal_revision_id, $panel[$key]['inflator_amount'],
				$panel[$key]['contracted'], $panel[$key]['non_contracted'], $panel[$key]['promotional']);
			} else {
				//updateRevisionPanelCost
				$p->UpdateRevisionPanelCostByRevision($proposal_revision_id, $panel[$key]['panel_cost_type_id'], $panel[$key]['inflator_amount'],
				$panel[$key]['contracted'], $panel[$key]['non_contracted'], $panel[$key]['promotional']);
			}
		}

		$group = PrepareSmartyArray($p->GetGroupDiscountByLicenseLevel($license_level_id));

		foreach ($group as $key => $value) {
			$group_license[$group[$key]['pricing_item_group_id']] = $group[$key];
		}

		$group = PrepareSmartyArray($p->GetGroupDiscountByPartner($account_id));

		foreach ($group as $key => $value) {
			$group_partner[$group[$key]['pricing_item_group_id']] = $group[$key];
		}


		//override license level with partner level
		foreach ($group_license as $key => $value) {
			$group_discount[$key] = $group_license[$key];

			if (!is_null($group_partner[$key]['inflator'])) {
				$group_discount[$key]['inflator'] = $group_partner[$key]['inflator'];
			}

			if (!is_null($group_partner[$key]['contracted'])) {
				$group_discount[$key]['contracted'] = $group_partner[$key]['contracted'];
			}

			if (!is_null($group_partner[$key]['non_contracted'])) {
				$group_discount[$key]['non_contracted'] = $group_partner[$key]['non_contracted'];
			}

			if (!is_null($group_partner[$key]['promotional'])) {
				$group_discount[$key]['promotional'] = $group_partner[$key]['promotional'];
			}

			if ($update_flag == 0) {
				$p->SetRevisionGroupDiscount($group_discount[$key]['pricing_item_group_id'], $proposal_revision_id, $group_discount[$key]['inflator'], $group_discount[$key]['contracted'],
				$group_discount[$key]['non_contracted'], $group_discount[$key]['promotional']);
			} else {
				//update revision group discount
				$p->UpdateRevisionGroupDiscountByRevision($proposal_revision_id, $group_discount[$key]['pricing_item_group_id'], $group_discount[$key]['inflator'], $group_discount[$key]['contracted'],
				$group_discount[$key]['non_contracted'], $group_discount[$key]['promotional']);
			}
		}


		//ok now if the revision number for this is not 1 we need to copy all the exisiting ad_hoc discounts to the new one
		if ($r_proposal['revision'] > 1) {

			$old_proposal_revision_id  = $p->GetRevisionIdByRevision($r_proposal['revision'] - 1);

			//main pricing items discounts
			$p->SetRevisionId($old_proposal_revision_id);

			//we need to get pricing from the old revision
			$rs = $p->GetRevisionPricing();

			$p->SetRevisionId($proposal_revision_id);

			//      while ($r = mysql_fetch_assoc($rs)) {
			//      	$p->UpdateRevisionAdHocDiscount($r['pricing_item_id'], $r['ad_hoc_discount'], $r['net_price']);
			//      }


			//group discounts
			$p->SetRevisionId($old_proposal_revision_id);

			$rs = $p->GetRevisionGroupDiscount();

			$p->SetRevisionId($proposal_revision_id);

			while ($r = mysql_fetch_assoc($rs)) {
				$p->UpdateRevisionGroupAdHocDiscountByPricingGroup($r['pricing_item_group_id'], $r['ad_hoc_discount']);
			}

			//panel discounts
			$p->SetRevisionId($old_proposal_revision_id);

			$rs = $p->GetRevisionPanelCost();

			$p->SetRevisionId($proposal_revision_id);

			while ($r = mysql_fetch_assoc($rs)) {
				$p->UpdateRevisionPanelCostAdHocDiscountByPanelType($r['panel_cost_type_id'], $r['ad_hoc_discount']);
			}

		}

		return true;
	}

	/**
* ApplyGroupDiscount()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Dec 21 13:43:27 PST 2005
*/
	function ApplyGroupDiscount($revision_id, $gross_amount, $pricing_item_group_id)
	{
		$p = new proposalDB();
		$p->SetRevisionId($revision_id);

		$r = mysql_fetch_assoc($p->GetRevisionGroupDiscountByGroup($pricing_item_group_id));

		$discount_types = array('contracted_discount', 'non_contracted_discount', 'promotional_discount', 'ad_hoc_discount');

		foreach ($discount_types as $key => $discount_type) {
			$gross_amount = $gross_amount - ($gross_amount * $r[$discount_type] / 100);
		}

		return $gross_amount;
	}

	/**
* CalculateProposal()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 12:53:30 PST 2005
*/
	function CalculateProposal($proposal_id, $revision_id)
	{
		//we should just clear out the existing pricing and then re-do it rather than trying to update it
		$p = new proposalDB();
		$p->SetProposalId($proposal_id);
		$p->SetRevisionId($revision_id);

		$c = new commonDB();

		$r_proposal = $p->GetBasicDetail();
		$revision   = $p->GetRevisionDetail();
		
		$detailed_panel = 0;
		
		if ($p->GetRevisionAttr("PPM_DETAILED_PANEL_CALCULATION") != 0) {
		    $detailed_panel = 1;
		}

		//$partner = new partnerDB($r_proposal['account_id']);

		$license_level_id = $r_proposal['license_level_id']; //$partner->GetLicenseLevelId($r_proposal['account_id']);

		$pricing_regime_id = $r_proposal['pricing_regime_id']; //$partner->GetPricingRegimeId($r_proposal['account_id']);

		$country_code = $r_proposal['country_code']; //$partner->GetPartnerCountryCode($r_proposal['account_id']);

		$region_id = 0;

		$country_tier_id  = 0;

		if ($country_code && $country_code != '') {
			$r_region = $c->GetRegionByCountryCode($country_code);
			$region_id = $r_region['region_id'];
		}

		$rs_budget = $p->GetProposalBudgetLineItems();

		$rs_revision_pricing = $p->GetRevisionPricing();

		while ($r_revision_pricing = mysql_fetch_assoc($rs_revision_pricing)) {
			$revision_pricing[$r_revision_pricing['pricing_item_id']] = array('l_price' => $r_revision_pricing['license_level_price'], 'inflator' => $r_revision_pricing['inflator'],
			'contracted_discount' => $r_revision_pricing['contracted_discount'], 'non_contracted_discount' => $r_revision_pricing['non_contracted_discount'],
			'promotional_discount' => $r_revision_pricing['promotional_discount'], 'ad_hoc_discount' => $r_revision_pricing['ad_hoc_discount'], 'net_price' => $r_revision_pricing['net_price']);
		}

		#print_r($revision_pricing);


		$rs_options = $p->GetRevisionOptions();
		$options = array();
		
		//we need to build a proposal x n_country grid
		while ($r_option = mysql_fetch_assoc($rs_options)) {
			$options[$r_option['option_number']][] = $r_option;
		}

		//we need to create a selected service list to see if we need to perform calculations on certain budget lines
		$rs_service = $p->GetRevisionServiceList();

		while ($r_service = mysql_fetch_assoc($rs_service)) {
			$service[$r_service['service_id']] = 1;
		}
		
		 $st_pricing_index = array(
						                SAMPLE_TYPE_CONSUMER_GENERAL => array(
						                	'CPC' => PROPOSAL_BUDGET_CPC_CONSUMER, 
						                	'COMPLETE_FEE' => PROPOSAL_BUDGET_PANEL_COMPLETE_CONSUMER,
						                    'USAGE_STD'    => PROPOSAL_BUDGET_PANEL_USAGE_CONSUMER,
						                    'USAGE_ADHOC'  => PROPOSAL_BUDGET_TOTAL_PANEL_CONSUMER
						                ),
						                SAMPLE_TYPE_B2B_GENERAL     => array(
						                    'CPC' => PROPOSAL_BUDGET_CPC_B2B, 
						                	'COMPLETE_FEE' => PROPOSAL_BUDGET_PANEL_COMPLETE_B2B,
						                    'USAGE_STD'    => PROPOSAL_BUDGET_PANEL_USAGE_B2B,
						                    'USAGE_ADHOC'  => PROPOSAL_BUDGET_TOTAL_PANEL_B2B
						                ),
						                SAMPLE_TYPE_B2BIT           => array(
						                    'CPC' => PROPOSAL_BUDGET_CPC_B2BIT, 
						                	'COMPLETE_FEE' => PROPOSAL_BUDGET_PANEL_COMPLETE_B2BIT,
						                    'USAGE_STD'    => PROPOSAL_BUDGET_PANEL_USAGE_B2BIT,
						                    'USAGE_ADHOC'  => PROPOSAL_BUDGET_TOTAL_PANEL_B2BIT
						                )
						            );

		$country_id_cache = CreateSmartyArray($c->GetCountries(), 'country_code', 'country_id');
						            
		foreach ($options as $key => $value) {

			$n_country = 1;

			//now we need to loop through each country in the option
			foreach ($options[$key] as $kc => $cv) {
			    
			    $country_id = 0;
			    $pricing = array();
			    
			    if (isset($country_id_cache[ $options[$key][$kc]['country_code'] ])) {
			        $country_id = $country_id_cache[ $options[$key][$kc]['country_code'] ];
			    }

				if ($region_id && $region_id != 0) {
					$country_tier_id = $c->GetRegionCountryTier($region_id, $options[$key][$kc]['country_code']);
				}

				if (!$country_tier_id || $country_tier_id == 0) {
					$country_tier_id = $c->GetCountryTierByCountryCode($options[$key][$kc]['country_code']);
				}

				//print_r($options[$key][$kc]);

				$pricing[PROPOSAL_BUDGET_LICENSE]       = $this->GetProjectLicenseFee($revision_pricing[BASE_PRICE_LICENSE]['net_price'], $options[$key][$kc]['sort_order'], $options[$key][$kc]['country_code'], $r_proposal['proposal_option_type_id'] );

				//PROJECT MANAGMENT
				if ($service[SERVICE_PM] == 1) {
					$pricing['pm_hours']                    = $this->GetProjectManagmentHours($r_proposal['proposal_type_id'], $r_proposal['proposal_option_type_id'], $options[$key][$kc]['sort_order'] , $options[$key][$kc]['country_code']);
					$pricing[PROPOSAL_BUDGET_PM]            = $this->GetProjectManagmentFee($pricing['pm_hours'], $revision_pricing[BASE_PRICE_PM]['net_price']);
				}


				//QUESTIANIR PROGRAMMING
				if (isset($service[SERVICE_QPROG]) && $service[SERVICE_QPROG] == 1) {
					$pricing[PROPOSAL_BUDGET_Q_PROG]        = $this->GetQuestionairProgrammingFee($options[$key][$kc]['study_programming_type_id'], $options[$key][$kc]['questions_programmed'], $options[$key][$kc]['sort_order'], $revision_pricing[BASE_PRICE_Q_PROG]['net_price']);
				}

				//TRANSLATION
				if (isset($service[SERVICE_QTRANS]) && $service[SERVICE_QTRANS] == 1) {
					$pricing[PROPOSAL_BUDGET_Q_TRANSLATION] = $this->GetQuestionairTranslationFee($options[$key][$kc]['questions_programmed'], $revision_pricing[BASE_PRICE_Q_TRANSLATION]['net_price'], $options[$key][$kc]['translation']);
				}

				//OVERLAY
				if (isset($service[SERVICE_LOLAY]) && $service[SERVICE_LOLAY] == 1) {
					$pricing[PROPOSAL_BUDGET_LANG_OVERLAY]  = $this->GetLanguageOverlayFee($options[$key][$kc]['questions_programmed'], $revision_pricing[BASE_PRICE_LANG_OVERLAY]['net_price'], $options[$key][$kc]['overlay']);
				}

				//DATA RECODE
				if (isset($service[SERVICE_DATA_RECODING]) && $service[SERVICE_DATA_RECODING] == 1) {
					$pricing[PROPOSAL_BUDGET_DATA_RECORD]   = $this->GetDataRecordingFee($options[$key][$kc]['data_recording_hours'], $revision_pricing[BASE_PRICE_DATA_RECORD]['net_price']);
				}

				//DATA TAB
				if (isset($service[SERVICE_DATA_TAB]) && $service[SERVICE_DATA_TAB] == 1) {
					$pricing[PROPOSAL_BUDGET_DATA_TAB]      = $this->GetDataTabFee($options[$key][$kc]['data_tab_hours'], $revision_pricing[BASE_PRICE_DATA_TAB]['net_price']);
				}

				@$pricing[32] = $pricing[PROPOSAL_BUDGET_PM] + $pricing[PROPOSAL_BUDGET_Q_PROG] + $pricing[PROPOSAL_BUDGET_Q_TRANSLATION] +
				$pricing[PROPOSAL_BUDGET_LANG_OVERLAY] + $pricing[PROPOSAL_BUDGET_LICENSE];

				$pricing[PROPOSAL_BUDGET_TOTAL_PROJECT_SETUP] = $this->ApplyGroupDiscount($revision_id, $pricing[32], PRICING_GROUP_SETUP);



				//HAS TO BE ONE FOR IMPORT AND ANOTHER FOR EXPORT
				if (@isset($service[SERVICE_DATA_IMPORT]) && $service[SERVICE_DATA_IMPORT] == 1) {
					$pricing[PROPOSAL_BUDGET_DATA_IMPORT] = $this->GetDataImportExportFee($options[$key][$kc]['data_import_hours'], $revision_pricing[BASE_PRICE_DATA_IMPORT]['net_price']);
				}

				//DATA EXPORT
				if (isset($service[SERVICE_DATA_EXPORT]) && $service[SERVICE_DATA_EXPORT] == 1) {
					$pricing[PROPOSAL_BUDGET_DATA_EXPORT] = $this->GetDataImportExportFee($options[$key][$kc]['data_export_hours'], $revision_pricing[BASE_PRICE_DATA_EXPORT]['net_price']);
				}

				//OE TRANSLATION
				if (isset($service[SERVICE_OE_RESPONSE_TRANS]) && $service[SERVICE_OE_RESPONSE_TRANS] == 1) {
					$pricing[PROPOSAL_BUDGET_OE_TRANSLATION]     = $this->GetOpenEndTranslationFee($revision_pricing[BASE_PRICE_OE_TRANSLATION]['net_price'], $options[$key][$kc]['open_end_questions'], $options[$key][$kc]['incidence_of_open_end'], $options[$key][$kc]['avg_words_per_open_end'], $options[$key][$kc]['completes']);
				}

				//OPEN END TXT CODING
				if (isset($service[SERVICE_OE_TXT_CODING]) && $service[SERVICE_OE_TXT_CODING] == 1) {
					$pricing[PROPOSAL_BUDGET_OE_TXT_CODING]      = $this->GetOpenEndTextCodingFee($options[$key][$kc]['open_end_text_coding_hours'], $revision_pricing[BASE_PRICE_OE_TXT_CODING]['net_price']);
				}

				/* HMBD-347 */
				if (@$service[SERVICE_RPORTAL] == 1) {
					$pricing[PROPOSAL_BUDGET_RESPONDANT_PORTAL]      = $this->GetRespondentPortalFee($options[$key][$kc]['respondent_portal_type_id'], $revision_pricing[BASE_PRICE_RPORTAL_SETUP]['net_price'],
					$revision_pricing[BASE_PRICE_RPORTAL_PROGRAMMING]['net_price'], $options[$key][$kc]['respondent_portal_programming_hours'],
					$options[$key][$kc]['sort_order']);
				}

				if (@$service[SERVICE_CPORTAL] == 1) {
					$pricing[PROPOSAL_BUDGET_CLIENT_PORTAL]      = $this->GetClientPortalFee($revision_pricing[BASE_PRICE_CPORTAL_PROGRAMMING]['net_price'], $options[$key][$kc]['client_portal_programming_hours'],$options[$key][$kc]['sort_order']);
				}

				if (@$service[SERVICE_PANEL_IMPORT] == 1) {
					$pricing[PROPOSAL_BUDGET_PANEL_IMPORT] = $this->GetPanelImportFee($revision_pricing[BASE_PRICE_PANEL_IMPORT]['net_price'], $options[$key][$kc]['panel_import_hours']);
				}

				//IF PANEL OR HOSTING
				$pricing[PROPOSAL_BUDGET_TOTAL_CONTACT]      = $this->GetTotalContacts($options[$key][$kc]['completes'], $options[$key][$kc]['incidence_rate']);

				//PANEL
				$pricing[PROPOSAL_BUDGET_TOTAL_SCREENER]     = $this->GetTotalScreeners($options[$key][$kc]['completes'], $pricing[PROPOSAL_BUDGET_TOTAL_CONTACT]);

				if ($revision['proposal_type_id'] != PROPOSAL_TYPE_SAMPLEONLY) {

					//PANEL OR HOSTING
					$pricing[PROPOSAL_BUDGET_TQS_ANSWERED]       = $this->GetTotalQuestionsAnswered($options[$key][$kc]['completes'], $options[$key][$kc]['questions_per_interview'], $options[$key][$kc]['questions_per_screener'],$pricing[PROPOSAL_BUDGET_TOTAL_SCREENER]);

					//PANEL OR HOSTING
					$pricing[PROPOSAL_BUDGET_PQS_ANSWERED]       = $revision_pricing[BASE_PRICE_D_HOSTING]['net_price'];  //GetPricePer$questionsuestion();

					if (@$service[SERVICE_HOSTING] == 1) {
						$pricing[PROPOSAL_BUDGET_HOSTING]            = $this->GetHostingCosts($pricing[PROPOSAL_BUDGET_TQS_ANSWERED], $pricing[PROPOSAL_BUDGET_PQS_ANSWERED]);
					}

					$pricing[PROPOSAL_BUDGET_TOTAL_HOSTING] = $this->ApplyGroupDiscount($revision_id, $pricing[PROPOSAL_BUDGET_HOSTING], PRICING_GROUP_HOSTING);

				}

				//HOSTING


				//HB-1853 1.5.8144qfe
				if (@$service[SERVICE_PANEL] == 1) 
				{
					
					switch ($r_proposal['version']) {
						case 1:
							$pricing[PROPOSAL_BUDGET_CPC] = $this->GetCostPerComplete($options[$key][$kc]['study_datasource_id'], $country_tier_id, ($options[$key][$kc]['questions_per_interview'] + $options[$key][$kc]['questions_per_screener']) , $pricing_regime_id ,$revision_pricing[BASE_PRICE_CPC]['net_price'], $license_level_id);
							$pricing[PROPOSAL_BUDGET_CPS] = $this->GetCostPerScreener($options[$key][$kc]['study_datasource_id'], $country_tier_id, $options[$key][$kc]['incidence_rate'],  $pricing_regime_id ,$revision_pricing[BASE_PRICE_CPS]['net_price']);
							break;
						default:
							
						    //if panel detail specification is used lets read the prime lines (assuming prime lines are calculated properly)
						    //we will stick the all line in the current PARENT and then each sample type line in the new descriptions 
						    //same on lines 714 through 716
						    if ($detailed_panel == 0) {
						    
    						    $pricing[PROPOSAL_BUDGET_CPC] = Hb_App_Proposal_RateCard::GetCostPerInterview(SAMPLE_TYPE_CONSUMER_GENERAL, 
    							    $options[$key][$kc]['country_code'], $options[$key][$kc]['incidence_rate'], 
    							    ($options[$key][$kc]['questions_per_interview'] + $options[$key][$kc]['questions_per_screener']));
						    } else {
						        
						        $sample_types = $p->GetRevisionSampleTypes();
						        
						        while (($st = mysql_fetch_assoc($sample_types))) {
						        	
						            $panel_pricing = $p->GetProposalRevisionPanelPrimedData($revision_id, $key, $country_id, $st['sample_type_id']);
						            
						            $pricing[ $st_pricing_index[$st['sample_type_id']]['CPC'] ] = $panel_pricing['cost_per_complete'];
						            $pricing[ $st_pricing_index[$st['sample_type_id']]['COMPLETE_FEE'] ] = $panel_pricing['total_cost'] + $panel_pricing['adjustment'];
						            $pricing[ $st_pricing_index[$st['sample_type_id']]['USAGE_STD'] ] = $panel_pricing['total_cost'];
						            
						            //ad-oc discoun calculation
						            $pricing[ $st_pricing_index[$st['sample_type_id']]['USAGE_ADHOC'] ] =  $this->ApplyGroupDiscount($revision_id, 
						                        $pricing[ $st_pricing_index[$st['sample_type_id']]['USAGE_STD'] ], PRICING_GROUP_PANEL);
						            
						        }
						        
						        $panel_pricing = $p->GetProposalRevisionPanelPrimedData($revision_id, $key, $country_id);
						        
						        //we still need the overall line here
						        $pricing[PROPOSAL_BUDGET_CPC]            = $panel_pricing['cost_per_complete'];
						        $pricing[PROPOSAL_BUDGET_PANEL_COMPLETE] = $panel_pricing['total_cost'] + $panel_pricing['adjustment'];
						        $pricing[PROPOSAL_BUDGET_PANEL_USAGE]    = $panel_pricing['total_cost'];
						        
						        
						    }
							    
							$pricing[PROPOSAL_BUDGET_CPS] = 0;
							break;
					}
					
					//we dont need a else block here since we handled the calculation logic at line 717, no need to loop twice for the same stuff
					if ($detailed_panel == 0 || $r_proposal['version'] == 1) {

    					$pricing[PROPOSAL_BUDGET_PANEL_COMPLETE] = $this->GetPanelCompleteFee($pricing[PROPOSAL_BUDGET_CPC], $options[$key][$kc]['completes'], $license_level_id, $r_proposal['account_id']);
    					$pricing[PROPOSAL_BUDGET_PANEL_SCREENER] = $this->GetPanelScreenerFee($pricing[PROPOSAL_BUDGET_CPS], $pricing[PROPOSAL_BUDGET_TOTAL_SCREENER], $options[$key][$kc]['completes'], $pricing[PROPOSAL_BUDGET_CPC], $pricing[PROPOSAL_BUDGET_PANEL_COMPLETE]);
    					$pricing[PROPOSAL_BUDGET_PANEL_USAGE]    = $pricing[PROPOSAL_BUDGET_PANEL_COMPLETE] + $pricing[PROPOSAL_BUDGET_PANEL_SCREENER];
    					
					} 
				}

				//HOSTING COST + PANEL_USAGE_FEE
				@$pricing[PROPOSAL_BUDGET_TOTAL_FW_COST] = $pricing[PROPOSAL_BUDGET_HOSTING] + $pricing[PROPOSAL_BUDGET_PANEL_USAGE];
				
				//this line should reflect the panel detail pricing discounts
				//apply the adhoc discount to each sample type line
				// we cant move this line up because if we do the price for panel will not show up as 0 on proposals which we dont pick panel
				@$pricing[PROPOSAL_BUDGET_TOTAL_PANEL]   = $this->ApplyGroupDiscount($revision_id, $pricing[PROPOSAL_BUDGET_PANEL_USAGE], PRICING_GROUP_PANEL);

				
				
				@$pricing[PROPOSAL_BUDGET_TOTAL_DP] = $pricing[PROPOSAL_BUDGET_DATA_TAB] + $pricing[PROPOSAL_BUDGET_DATA_IMPORT] + $pricing[PROPOSAL_BUDGET_OE_TRANSLATION] +
				@$pricing[PROPOSAL_BUDGET_OE_TXT_CODING] + $pricing[PROPOSAL_BUDGET_DATA_EXPORT] + $pricing[PROPOSAL_BUDGET_CLIENT_PORTAL] +
				@$pricing[PROPOSAL_BUDGET_RESPONDANT_PORTAL] + $pricing[PROPOSAL_BUDGET_PANEL_IMPORT] + $pricing[PROPOSAL_BUDGET_DATA_RECORD];

				@$pricing[33] = $this->ApplyGroupDiscount($revision_id, $pricing[PROPOSAL_BUDGET_TOTAL_DP], PRICING_GROUP_DP);

				@$pricing[PROPOSAL_BUDGET_TOTAL_COST] = $pricing[PROPOSAL_BUDGET_TOTAL_PROJECT_SETUP] + $pricing[PROPOSAL_BUDGET_TOTAL_HOSTING] + $pricing[PROPOSAL_BUDGET_TOTAL_PANEL] + $pricing[PROPOSAL_BUDGET_TOTAL_DP];


				/* WE DONT WANT TO SET THE OPTION TOTALS AT THE proposal_revision_option level when there is custom pricing */
				if ($revision['pricing_type_id'] == PRICING_TYPE_STANDARD) {
					//set total panel at option level
					$p->SetOptionPanelTotal($options[$key][$kc]['proposal_revision_option_id'], $pricing[PROPOSAL_BUDGET_TOTAL_PANEL]);
					$p->SetOptionTotal($options[$key][$kc]['proposal_revision_option_id'], $pricing[PROPOSAL_BUDGET_TOTAL_COST]);
					$p->SetOptionCpc($options[$key][$kc]['proposal_revision_option_id'], ($pricing[PROPOSAL_BUDGET_TOTAL_COST] / $options[$key][$kc]['completes']));
				}

				$pricing[PROPOSAL_BUDGET_FW_CPC] = $pricing[PROPOSAL_BUDGET_TOTAL_FW_COST] / $options[$key][$kc]['completes'];

				$pricing[PROPOSAL_BUDGET_TOTAL_CPC] = $pricing[PROPOSAL_BUDGET_TOTAL_COST] / $options[$key][$kc]['completes'];

				@$revision_total[$key] += $pricing[PROPOSAL_BUDGET_TOTAL_COST];

				while (($r_budget = mysql_fetch_assoc($rs_budget))) {
					if (isset($pricing[$r_budget['proposal_budget_line_item_id']])) {
						//check if pricing exist, if so then update it
						if ($p->isRevisionOptionBudgetLineItem($options[$key][$kc]['proposal_revision_option_id'], $r_budget['proposal_budget_line_item_id'])) {
							$p->UpdateRevisionOptionBudgetLineItem($options[$key][$kc]['proposal_revision_option_id'], $r_budget['proposal_budget_line_item_id'], $pricing[$r_budget['proposal_budget_line_item_id']]);
						} else {
							$p->SetRevisionOptionBudgetLineItem($options[$key][$kc]['proposal_revision_option_id'], $r_budget['proposal_budget_line_item_id'], $pricing[$r_budget['proposal_budget_line_item_id']]);
						}
					} else {
						//delete the revision option
						$p->DeleteRevisionOptionBudgetLineItem($options[$key][$kc]['proposal_revision_option_id'], $r_budget['proposal_budget_line_item_id']);
					}
				}

				mysql_data_seek($rs_budget, 0);
			}
		}

		$min = 0;
		$max = 0;
		for ($i=1; $i <= count($revision_total); $i++) {

			if ($min == 0) {
				$min = $revision_total[$i];
			}

			if ($revision_total[$i] > $max) {
				$max = $revision_total[$i];
			}

			if ($revision_total[$i] < $min) {
				$min = $revision_total[$i];
			}
		}

		$p->SetRevisionMaxPrice($max);
		$p->SetRevisionMinPrice($min);
	}

	/**
* CalculateCustomDiscount()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Dec 20 23:01:11 PST 2005
*/
	function CalculateCustomDiscount($o)
	{
		$e = new Encryption();
		$p = new proposalDB();
		$p->SetProposalId($o['proposal_id']);
		$p->SetRevisionId($o['proposal_revision_id']);

		$proposal = $p->GetBasicDetail();

		for ($i=1; $i <= $proposal['number_of_options']; $i++ ) {

			//see if hosting or panel discounts are set
			$panel_original   = $o['original_'. $i .'_'. PROPOSAL_BUDGET_PANEL_USAGE];
			$panel_discounted = $o['discount_'. $i .'_'. PROPOSAL_BUDGET_PANEL_USAGE];

			if ($panel_original != $panel_discounted) {

				$panel_discount = (1 - ($panel_discounted / $panel_original)) * 100;

				$r = mysql_fetch_assoc($p->GetRevisionGroupDiscountByGroup(PRICING_GROUP_PANEL));

				$p->UpdateRevisionGroupAdHocDiscount($r['proposal_revision_group_discount_id'], $panel_discount);

			}

			$hosting_original   = $o['original_'. $i .'_'. PROPOSAL_BUDGET_HOSTING];
			$hosting_discounted = $o['discount_'. $i .'_'. PROPOSAL_BUDGET_HOSTING];

			if ($hosting_original != $hosting_discounted && $hosting_original > 0) {

				$hosting_discount = (1 - ($hosting_discounted / $hosting_original)) * 100;

				$r = mysql_fetch_assoc($p->GetRevisionGroupDiscountByGroup(PRICING_GROUP_HOSTING));

				$p->UpdateRevisionGroupAdHocDiscount($r['proposal_revision_group_discount_id'], $hosting_discount);

			}

		}

		//run through calculate proposal again
		$this->CalculateProposal($o['proposal_id'], $o['proposal_revision_id']);

		header("Location: ?e=". $e->Encrypt("action=display_review_proposal&proposal_id=". $o['proposal_id']."&proposal_revision_id=". $o['proposal_revision_id']));

	}

	/**
* DisplayProposalApplyDiscount()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Dec 20 22:15:59 PST 2005
*/
	function DisplayProposalApplyDiscount($o)
	{
		global $smarty;

		$p = new proposalDB();
		$p->SetProposalId($o['proposal_id']);
		$p->SetRevisionId($o['proposal_revision_id']);

		//timezone stuff
		$tz = GetTimeZone($o);
		$p->tz = $tz;

		$proposal = $p->GetBasicDetail();
		$revision = $p->GetRevisionDetail();

		$rs = $p->GetRevisionBudgetLineItems();

		$row = 1;
		$last_option_number = 0;

		//   if ($proposal['number_of_countries'] == 1 && $proposal['number_of_options'] > 1) {
		//
		//      while ($r = mysql_fetch_assoc($rs)) {
		//
		//         if ($r['option_number'] != $last_option_number) {
		//            $row = 1;
		//            $last_option_number = $r['option_number'];
		//         }
		//
		//         $options[$row][$r['option_number']] = $r;
		//         $row++;
		//      }
		//
		//      $template = 'app/pgen/vw_review_proposal_single.tpl';
		//
		//   } else {

		while ($r = mysql_fetch_assoc($rs)) {

			//the tricky part here is if we are under the assumption that country will align up together
			$options[$r['option_number']][$r['sort_order']][] = $r;
			$total[$r['option_number']][$r['sort_order']]['amount'] += $r['amount'];
			$total[$r['option_number']][$r['sort_order']]['pbli_id'] = $r['proposal_budget_line_item_id'];
			$total[$r['option_number']][$r['sort_order']]['value_type'] = $r['value_type'];
			$country[$r['option_number']][$r['proposal_revision_option_id']] = $r['country_description'];

		}

		$template = 'app/pgen/vw_proposal_apply_discount.tpl';
		//   }




		//$list['proposal_budget'] = PrepareSmartyArray($p->GetProposalBudgetLineItems());

		DisplayHeader("Proposal Manager", "pgen");

		$smarty->assign('list', $list);
		$smarty->assign('proposal', $proposal);
		$smarty->assign('revision', $revision);
		$smarty->assign('options', $options);
		$smarty->assign('total', $total);
		$smarty->assign('country', $country);
		$smarty->display($template);

		DisplayFooter();

	}

	/**
* SaveCustomPricing()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Dec 20 13:50:03 PST 2005
*/
	function SaveCustomPricing($o)
	{
		global $smarty;
		$e = new Encryption();

		$p = new proposalDB();
		$p->SetProposalId($o['proposal_id']);
		$p->SetRevisionId($o['proposal_revision_id']);

		$rs_pig = $p->GetPricingItemGroups();

		$rs_options = $p->GetRevisionOptions();

		while ($r_option = mysql_fetch_assoc($rs_options)) {

			while ($r_pig = mysql_fetch_assoc($rs_pig)) {

				if ($o['do_update'] == 0 || $o['prcp_id_'. $r_pig['pricing_item_group_id'] .'_'. $r_option['proposal_revision_option_id']] == '') {
					$p->SetRevisionCustomPrice($r_option['proposal_revision_option_id'], $r_pig['pricing_item_group_id'],
					$o['amount_'. $r_pig['pricing_item_group_id'] .'_'. $r_option['proposal_revision_option_id']]);
				} else {
					$p->UpdateRevisionCustomPrice($o['prcp_id_'. $r_pig['pricing_item_group_id'] .'_'. $r_option['proposal_revision_option_id']],
					$o['amount_'. $r_pig['pricing_item_group_id'] .'_'. $r_option['proposal_revision_option_id']]);
				}
			}

			$p->UpdateRevisionOptionCPI($r_option['proposal_revision_option_id'], $o['cpi_'. $r_option['proposal_revision_option_id']]);

			$total[$r_option['option_number']] += $o['total_'. $r_option['proposal_revision_option_id']];

			//saving summary budget at option level
			$p->SetOptionTotal($r_option['proposal_revision_option_id'], $o['total_'. $r_option['proposal_revision_option_id']]);
			$p->SetOptionPanelTotal($r_option['proposal_revision_option_id'], $o['amount_3_'. $r_option['proposal_revision_option_id']]);
			$p->SetOptionCpc($r_option['proposal_revision_option_id'], ($o['total_'. $r_option['proposal_revision_option_id']] / $r_option['completes']));

			//we need to set the pointer back to top for other options can save
			mysql_data_seek($rs_pig, 0);

		}

		//print_r($o);

		//need to set the high and low price
		$min_amount = 0;
		$max_amount = 0;

		foreach ($total as $key => $value) {

			if ($min_amount == 0) {
				$min_amount = $value;
			}

			if ($value > $max_amount) {
				$max_amount = $value;
			}

			if ($value < $min_amount) {
				$min_amount = $value;
			}
		}

		$p->SetRevisionMaxPrice($max_amount);

		$p->SetRevisionMinPrice($min_amount);

		$p->UpdateAttr('NEXT_ACTION', '');

		header("Location: ?e=". $e->Encrypt("action=display_revision&proposal_id=". $o['proposal_id'] ."&proposal_revision_id=". $o['proposal_revision_id']));

	}


	/**
* DisplayCustomPricing()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Dec 20 13:13:20 PST 2005
*/
	function DisplayCustomPricing($o)
	{
		global $smarty;

		$pricing_manager = new pgen_PricingManager();

		$p = new proposalDB();
		$p->SetProposalId($o['proposal_id']);
		$p->SetRevisionId($o['proposal_revision_id']);

		$revision = $p->GetRevisionDetail();
		$proposal = $p->GetBasicDetail();

		$meta['display_sub_group'] = 0;

		if ($revision['proposal_option_type_id'] == PROPOSAL_OPTION_TYPE_SINGLE_SUB)
		$meta['display_sub_group'] = 1;


		$meta['display_tr_cpi'] = 0;

		$group = $pricing_manager->GetProposalSummaryGroups($o['proposal_id'], $o['proposal_revision_id']);

		$meta['display_tr_cpi'] = $group['panel'];

		if (isset($_SESSION['ppm_message']) && $_SESSION['ppm_message'] != '') {
			$meta['message'] = $_SESSION['ppm_message'];
			unset($_SESSION['ppm_message']);
		}

		//we need to find out the selected service groups by services

		$pricing_group = PrepareSmartyArray($p->GetPricingItemGroups());

		$rs = $p->GetRevisionOptions();

		if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] > 1) {

			while ($r = mysql_fetch_assoc($rs)) {
				$option[$r['option_number']] = $r;
			}

			$template = 'app/pgen/vw_custom_pricing_single.tpl';

		} else {

			while ($r = mysql_fetch_assoc($rs)) {
				$option[$r['option_number']][$r['sort_order']] = $r;
			}

			$template = 'app/pgen/vw_custom_pricing.tpl';

		}

		$meta['do_update'] = 0;
		//we gotta see if we already have data
		$rs = $p->GetRevisionCustomPricing();

		if ($p->rows > 0) {

			$meta['do_update'] = 1;

			if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] > 1) {
				while ($r = mysql_fetch_assoc($rs)) {

					switch ($r['pricing_item_group_id']) {
						case PRICING_GROUP_SETUP:
							if ($group['setup'] == 0) {
								$r['amount'] = 0;
								$p->UpdateRevisionCustomPrice($r['proposal_revision_custom_pricing_id'], 0);
							}
							break;
						case PRICING_GROUP_HOSTING:
							if ($group['hosting'] == 0) {
								$r['amount'] = 0;
								$p->UpdateRevisionCustomPrice($r['proposal_revision_custom_pricing_id'], 0);
							}
							break;
						case PRICING_GROUP_PANEL:
							if ($group['panel'] == 0) {
								$r['amount'] = 0;
								$p->UpdateRevisionCustomPrice($r['proposal_revision_custom_pricing_id'], 0);
							}
							else 	{
								$r['amount'] = $option[$r['option_number']]['completes'] * $option[$r['option_number']]['panel_cost_per_interview'];
								$p->UpdateRevisionCustomPrice($r['proposal_revision_custom_pricing_id'], $r['amount']);
							}
							break;
						case PRICING_GROUP_DP:
							if ($group['dp'] == 0) {
								$r['amount'] = 0;
								$p->UpdateRevisionCustomPrice($r['proposal_revision_custom_pricing_id'], 0);
							}
						default:
							break;
					}

					$o_value[$r['option_number']][$r['pricing_item_group_id']]['amount'] = $r['amount'];
					$o_value[$r['option_number']][$r['pricing_item_group_id']]['prcp_id'] = $r['proposal_revision_custom_pricing_id'];
					$o_value[$r['option_number']]['total'] += $r['amount'];
					$o_value[$r['option_number']]['cpi'] = $option[$r['option_number']]['panel_cost_per_interview'];
					$o_value[$r['option_number']]['cpc'] = $o_value[$r['option_number']]['total'] / $option[$r['option_number']]['completes'];
				}
			} else {
				while ($r = mysql_fetch_assoc($rs)) {

					switch ($r['pricing_item_group_id']) {
						case PRICING_GROUP_SETUP:
							if ($group['setup'] == 0) {
								$r['amount'] = 0;
								$p->UpdateRevisionCustomPrice($r['proposal_revision_custom_pricing_id'], 0);
							}
							break;
						case PRICING_GROUP_HOSTING:
							if ($group['hosting'] == 0) {
								$r['amount'] = 0;
								$p->UpdateRevisionCustomPrice($r['proposal_revision_custom_pricing_id'], 0);
							}
							break;
						case PRICING_GROUP_PANEL:
							if ($group['panel'] == 0) {
								$r['amount'] = 0;
								$p->UpdateRevisionCustomPrice($r['proposal_revision_custom_pricing_id'], 0);
							} else {
								$r['amount'] = $option[$r['option_number']][$r['sort_order']]['completes'] * $option[$r['option_number']][$r['sort_order']]['panel_cost_per_interview'];
								$p->UpdateRevisionCustomPrice($r['proposal_revision_custom_pricing_id'], $r['amount']);
							}
							break;
						case PRICING_GROUP_DP:
							if ($group['dp'] == 0) {
								$r['amount'] = 0;
								$p->UpdateRevisionCustomPrice($r['proposal_revision_custom_pricing_id'], 0);
							}
						default:
							break;
					}

					$o_value[$r['option_number']][$r['sort_order']][$r['pricing_item_group_id']]['amount'] = $r['amount'];
					$o_value[$r['option_number']][$r['sort_order']][$r['pricing_item_group_id']]['prcp_id'] = $r['proposal_revision_custom_pricing_id'];
					$o_value[$r['option_number']][$r['sort_order']]['total'] += $r['amount'];
					$o_value[$r['option_number']][$r['sort_order']]['cpi'] = $option[$r['option_number']][$r['sort_order']]['panel_cost_per_interview'];
					$o_value[$r['option_number']][$r['sort_order']]['cpc'] = $o_value[$r['option_number']][$r['sort_order']]['total'] / $option[$r['option_number']][$r['sort_order']]['completes'];
				}
			}

		}


		$smarty->assign('proposal', $proposal);
		$smarty->assign('revision', $revision);
		$smarty->assign('pricing_group', $pricing_group);
		$smarty->assign('option', $option);
		$smarty->assign('o_value', $o_value);
		$smarty->assign('meta', $meta);

		DisplayHeader("Proposal Manager", "pgen");
		$smarty->display($template);
		DisplayFooter();

	}


	/**
* ApplyVolumeDiscount()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Nov 29 15:23:00 PST 2005
*/
	function ApplyVolumeDiscount($completes, $cpc)
	{
		$p = new proposalDB();
		$p->GetPricingVolumeDiscountByLicenseLevel();
		//get the volume discount amount

		//loop through and apply the discounts

		//return the sum of all
	}



	/**
* GetProjectLicenseFee()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 11:32:12 PST 2005
*/
	function GetProjectLicenseFee($license_fee, $country, $country_code, $proposal_option_type_id)
	{
		static $last_country;

		if ((int)$license_fee == 0) return $license_fee;

		if ($proposal_option_type_id == PROPOSAL_OPTION_TYPE_MULTI || $proposal_option_type_id == PROPOSAL_OPTION_TYPE_SINGLE) {
			if ($country == 1) {
				$l_fee = $license_fee;
			} else {
				$l_fee = 150;
			}

		} elseif ($proposal_option_type_id == PROPOSAL_OPTION_TYPE_SINGLE_SUB) {

			if ($country == 1) {
				$l_fee = $license_fee;
			} elseif ($last_country != $country_code) {
				$l_fee = 150;
			} else {
				$l_fee = 0;
			}

			$last_country = $country_code;
		}


		return $l_fee;
	}

	/**
* GetProjectManagmentFee()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 11:37:58 PST 2005
*/
	function GetProjectManagmentFee($pm_hours, $pm_fee)
	{
		return $pm_hours * $pm_fee;
	}

	/**
* GetProjectManagmentHours()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 11:44:40 PST 2005
*/
	function GetProjectManagmentHours($study_type, $study_options, $country_number, $country_code)
	{
		static $last_country;

		if ($study_type == PROPOSAL_TYPE_SAMPLEONLY) {
			if ($study_options == PROPOSAL_OPTION_TYPE_SINGLE) {
				return 3;
			} elseif ($study_options == PROPOSAL_OPTION_TYPE_SINGLE_SUB && $country_number == 1) {
				$last_country = $country_code;
				return 3;
			} elseif ($study_options == PROPOSAL_OPTION_TYPE_SINGLE_SUB && $country_number != 1) {
				if ($country_code == $last_country) {
					return 0;
				} else {
					$last_country = $country_code;
					return 3;
				}
			} elseif ($study_options == PROPOSAL_OPTION_TYPE_MULTI && $country_number == 1) {
				return 3;
			} elseif ($study_options == PROPOSAL_OPTION_TYPE_MULTI && $country_number != 1) {
				return 3;
			}
		} else {
			if ($study_options == PROPOSAL_OPTION_TYPE_SINGLE) {
				return 2;
			} elseif (($study_options == PROPOSAL_OPTION_TYPE_MULTI || $study_options == PROPOSAL_OPTION_TYPE_SINGLE_SUB) && $country_number == 1) {
				$last_country = $country_code;
				return 2;
			} elseif ($study_options == PROPOSAL_OPTION_TYPE_MULTI && $country_number != 1) {
				return 2;
			} elseif ($study_options == PROPOSAL_OPTION_TYPE_SINGLE_SUB && $country_number != 1) {
				if ($last_country == $country_code) {
					return 0;
				} else {
					$last_country = $country_code;
					return 2;
				}
			}
		}
	}
	/**
* Get$questionsuestionairProgrammingFee()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 11:53:36 PST 2005
*/
	function GetQuestionairProgrammingFee($questions_type, $n_qs, $sort_order, $base_price)
	{
		if ($sort_order > 1)
		return 0;

		if ($base_price == 0)
		return 0;

		//pass in sort_order and return 0 if sort_order is > 1

		if ($questions_type == PROPOSAL_QUESTION_SIMPLE) {
			return $n_qs * 20;
		} elseif ($questions_type == PROPOSAL_QUESTION_COMPLEX) {
			return $n_qs * 30;
		} else {
			return 0;
		}

		//TODO APPLY INFLATORS
	}

	/**
* Get$questionsuestionairTranslationFee()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 11:54:45 PST 2005
*/
	function GetQuestionairTranslationFee($n_qs, $translation_fee, $flag)
	{
		if ($flag == 0) return 0;
		return $n_qs * PROPOSAL_AVERAGE_NUMBER_OF_WORDS_PER_QUESTION * $translation_fee;
	}

	/**
* GetLanguageOverlayFee()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 11:55:35 PST 2005
*/
	function GetLanguageOverlayFee($n_qs, $overlay_fee, $flag)
	{
		if ($flag == 0) return 0;
		return $n_qs * $overlay_fee;
	}

	/**
* GetDataRecordingFee()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 11:56:10 PST 2005
*/
	function GetDataRecordingFee($dr_hours, $dr_fee)
	{
		return $dr_hours * $dr_fee;
	}

	/**
* GetDataTabFee()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 11:56:44 PST 2005
*/
	function GetDataTabFee($dt_hours, $dt_fee)
	{
		return $dt_hours * $dt_fee;
	}

	/**
* GetDataImportExportFee()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 12:05:40 PST 2005
*/
	function GetDataImportExportFee($d_ie_hours, $d_ie_fee)
	{
		return $d_ie_hours * $d_ie_fee;
	}

	/**
* GetOpenEndTranslationFee()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 12:06:58 PST 2005
*/
	function GetOpenEndTranslationFee($oe_translation, $oe_qs, $incidence_open_end, $avg_words_per_openend, $n)
	{
		return $oe_translation * $oe_qs * ($incidence_open_end / 100) * $avg_words_per_openend * $n;
	}

	/**
* GetOpenEndTextCodingFee()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 12:08:57 PST 2005
*/
	function GetOpenEndTextCodingFee($oe_txt_coding_hours, $oe_txt_coding_fee)
	{
		return $oe_txt_coding_fee * $oe_txt_coding_hours;
	}

	/**
* GetTotal$questionsuestionsAnswered()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 12:09:49 PST 2005
*/
	function GetTotalQuestionsAnswered($n, $qs_per_interview, $qs_per_screener, $screeners)
	{
		return ((($qs_per_interview + $qs_per_screener) * $n) + ($screeners * $qs_per_screener));
	}

	/**
* GetPricePer$questionsuestion()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 12:18:25 PST 2005
*/
	function GetPricePerQuestion($contact_country_code, $option_country_code)
	{
		//TODO add code

	}

	/**
* GetHostingCosts()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 12:23:32 PST 2005
*/
	function GetHostingCosts($tot_qs, $price_per_qs)
	{
		return $tot_qs * $price_per_qs;
	}

	/**
* GetTotalContacts()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 12:24:02 PST 2005
*/
	function GetTotalContacts($n, $incidence)
	{
		return round($n / $incidence * 100);
	}

	/**
* GetTotalScreeners()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 12:25:37 PST 2005
*/
	function GetTotalScreeners($n, $n_contacts)
	{
		return $n_contacts - $n;
	}

	/**
* GetCostPerComplete()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 12:26:20 PST 2005
*/
	function GetCostPerComplete($sample, $country_tier, $questions, $pricing_regime, $cpc_net, $license_level_id)
	{
		//echo "$sample, $country_tier, $questions, $pricing_regime, $cpc_net, $license_level_id";

		if ($sample != STUDY_DATASOURCE_GTM)
		return 0;

		$p = new proposalDB();

		//if the sample is provided by the client then there will not be a CPC
		if ($license_level_id == LICENSE_LEVEL_0) $country_tier = $country_tier -1;

		$panel_cost_id = $p->GetPanelCostIdByCostRegimeCountry(PANEL_COST_COMPLETE, $pricing_regime, $country_tier);

		if (!$panel_cost_id) {
			//we might need to notify admin here because this is critical error
			return false;
		}

		$rs = $p->GetPanelCostAttrByPanelCostId($panel_cost_id);

		//find minumum MP ( what ever that is ) for each pricing regime
		//$$questionsuestions = $$questions_per_interview + $$questions_per_screener;

		if ($pricing_regime == PRICING_REGIME_ONE) {

			while ($r = mysql_fetch_assoc($rs)) {

				switch ($r['panel_cost_attr_name']) {
					case 'MINIMUM':
						$temp_min = $r['panel_cost_attr_value'];
						break;

					case 'M':
						$tmpM = $r['panel_cost_attr_value'];
						break;

					case 'B':
						$tmpB = $r['panel_cost_attr_value'];
						break;

					default:
						break;
				}
			}

			$val = sqrt($questions) * $tmpM + $tmpB;

			$base_complete = ($val < $temp_min) ? $temp_min : $val;

		} elseif ($pricing_regime == PRICING_REGIME_TWO) {

			//reduce tier by 1 if license_level is 0
			while ($r = mysql_fetch_assoc($rs)) {

				switch ($r['panel_cost_attr_name']) {
					case 'A':
						$a = $r['panel_cost_attr_value'];
						break;

					case 'B':
						$b = $r['panel_cost_attr_value'];
						break;

					case 'C':
						$c = $r['panel_cost_attr_value'];
						break;

					case 'D':
						$d = $r['panel_cost_attr_value'];
						break;

					case 'E':
						$e = $r['panel_cost_attr_value'];
						break;

					case 'F':
						$f = $r['panel_cost_attr_value'];
						break;

					case 'MINIMUM':
						$min = $r['panel_cost_attr_value'];
						break;

					default:
						break;
				}
			}

			//echo $a . " $b $c $d $e $f $questions";
			$questions = ((0.008008)*(($questions/150)*1000))-4;

			for ($i=1; $i <=5; $i++) {
				$val[$i] = pow($questions, $i);
			}

			$base_complete = ($a * $val[5]) + ($b * $val[4]) +
			($c * $val[3]) + ($d * $val[2]) + ($e * $questions) + $f;

			if ($base_complete < $min) $base_complete = $min;

			//echo " --- $base_complete --- ";

			//$base_complete = ($base_complete < $min_price ) ? $min_price : $base_complete;


		} elseif ($pricing_regime == PRICING_REGIME_THREE) {

			while ($r = mysql_fetch_assoc($rs)) {

				switch ($r['panel_cost_attr_name']) {
					case 'A':
						$a = $r['panel_cost_attr_value'];
						break;

					case 'B':
						$b = $r['panel_cost_attr_value'];
						break;

					case 'C':
						$c = $r['panel_cost_attr_value'];
						break;

					case 'D':
						$d = $r['panel_cost_attr_value'];
						break;

					case 'E':
						$e = $r['panel_cost_attr_value'];
						break;

					case 'F':
						$f = $r['panel_cost_attr_value'];
						break;

					case 'MINIMUM':
						$min = $r['panel_cost_attr_value'];
						break;

					default:
						break;
				}
			}

			$questions = ((0.008008)*(($questions/150)*1000))-4;

			for ($i=1; $i <=5; $i++) {
				$val[$i] = pow($questions, $i);
			}

			$base_complete = ($a * $val[5]) + ($b * $val[4]) +
			($c * $val[3]) + ($d * $val[2]) + ($e * $questions) + $f;

			if ($base_complete < $min) $base_complete = $min;

		} elseif ($pricing_regime == PRICING_REGIME_FOUR) {

			$tmpMin = 4.2125;
		}


		$cpc_net = $cpc_net / 100;

		if ($cpc_net < 1) {
			$base_complete = $base_complete - ($base_complete * $cpc_net);
		} elseif ($cpc_net > 1) {
			$base_complete = $base_complete + ($base_complete * $cpc_net);
		}


		return $base_complete;

	}

	/**
* GetCostPerScreener()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 12:29:09 PST 2005
*/
	function GetCostPerScreener($sample, $country_tier, $incidence, $pricing_regime, $cps_net)
	{
		if ($sample != STUDY_DATASOURCE_GTM)
		return 0;

		$p = new proposalDB();

		$panel_cost_id = $p->GetPanelCostIdByCostRegimeCountry(PANEL_COST_SCREENER, $pricing_regime, $country_tier);

		if (!$panel_cost_id) {
			//we might need to notify admin here because this is critical error
			return false;
		}

		$rs = $p->GetPanelCostAttrByPanelCostId($panel_cost_id);

		if ($pricing_regime == PRICING_REGIME_ONE) {

			while ($r = mysql_fetch_assoc($rs)) {

				switch ($r['panel_cost_attr_name']) {
					case 'MINIMUM':
						$temp_min = $r['panel_cost_attr_value'];
						break;

					case 'MAXIMUM':
						$temp_max = $r['panel_cost_attr_value'];
						break;

					case 'M':
						$tmpM = $r['panel_cost_attr_value'];
						break;

					case 'B':
						$tmpB = $r['panel_cost_attr_value'];
						break;

					default:
						break;
				}
			}

			if ($incidence > 70) {
				$base_screener = $temp_max;
			} else {
				$val = sqrt($tmpM * $incidence) + $tmpB;

				$base_screener = ($val < $temp_min) ? $temp_min : $val;
			}

		} elseif ($pricing_regime == PRICING_REGIME_TWO) {

			while ($r = mysql_fetch_assoc($rs)) {

				switch ($r['panel_cost_attr_name']) {
					case 'A':
						$a = $r['panel_cost_attr_value'];
						break;

					case 'B':
						$b = $r['panel_cost_attr_value'];
						break;

					case 'C':
						$c = $r['panel_cost_attr_value'];
						break;

					case 'D':
						$d = $r['panel_cost_attr_value'];
						break;

					case 'E':
						$e = $r['panel_cost_attr_value'];
						break;

					case 'F':
						$f = $r['panel_cost_attr_value'];
						break;

					case 'MINIMUM':
						$min = $r['panel_cost_attr_value'];
						break;

					case 'MAXIMUM':
						$max = $r['panel_cost_attr_value'];
						break;

					default:
						break;
				}
			}

			$incd = ($incidence * 10 * 0.008008) - 4;

			$base_screener = ($a * pow($incd,5)) + ($b * pow($incd,4)) + ($c * pow($incd,3)) + ( $d * pow($incd,2)) + ($e* $incd) + $f;

			$base_screener = $base_screener / 100;

			if ($base_screener > $max) $base_screener = $max;

			if ($base_screener < $min) $base_screener = $min;



		} elseif ($pricing_regime == PRICING_REGIME_THREE) {

			while ($r = mysql_fetch_assoc($rs)) {

				switch ($r['panel_cost_attr_name']) {
					case 'A':
						$a = $r['panel_cost_attr_value'];
						break;

					case 'B':
						$b = $r['panel_cost_attr_value'];
						break;

					case 'C':
						$c = $r['panel_cost_attr_value'];
						break;

					case 'D':
						$d = $r['panel_cost_attr_value'];
						break;

					case 'E':
						$e = $r['panel_cost_attr_value'];
						break;

					case 'F':
						$f = $r['panel_cost_attr_value'];
						break;

					case 'MINIMUM':
						$min = $r['panel_cost_attr_value'];
						break;

					case 'MAXIMUM':
						$max = $r['panel_cost_attr_value'];
						break;

					default:
						break;
				}
			}


			$incd = ($incidence * 10 * 0.008008) - 4;

			$base_screener = ($a * pow($incd,5)) + ($b * pow($incd,4)) + ($c * pow($incd,3)) + ( $d * pow($incd,2)) + ($e* $incd) + $f;

			$base_screener = $base_screener / 100;

			if ($base_screener > $max) $base_screener = $max;

			if ($base_screener < $min) $base_screener = $min;


		} elseif ($pricing_regime == PRICING_REGIME_FOUR) {

			$tmpMin = 4.2125;
		}

		$cps_net = $cps_net / 100;

		if ($cps_net < 1) {
			$base_screener = $base_screener - ($base_screener * $cps_net);
		} elseif ($cps_net > 1) {
			$base_screener = $base_screener + ($base_screener * $cps_net);
		}

		return $base_screener;
	}

	/**
* GetPanelCompleteFee()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 12:30:20 PST 2005
*/
	function GetPanelCompleteFee($cpc, $n, $license_level_id = 0, $account_id = 0)
	{
		//echo $n . "NNNN";

		//we should just run the volume discount here
		$p = new proposalDB();

		//check if account level volume discount exisits
		$rs = $p->GetAccountVolumeDiscount($account_id, $n, SORT_D);

		//check for license level volume discount
		if ($p->rows == 0) {
			$rs = $p->GetPricingVolumeDiscountByLicenseLevel($license_level_id, $n, SORT_D);
		}

		//return the cpc * n if there are no volume discounts
		if ($p->rows == 0) {
			return $cpc * $n;
		}

		//this is where volume discounts are being applied, we can run this through a function if we must
		$last_volume = 0;

		while ($r = mysql_fetch_assoc($rs)) {

			if ($last_volume == 0) {
				$remainder = $n - (int) $r['volume_amount'];
			} else {
				$remainder =  $last_volume - $r['volume_amount'];
			}

			$total += ($cpc * $remainder) * (1 - ($r['discount_amount'] / 100));
			$last_volume = $r['volume_amount'];
		}

		//   echo $last_volume;


		$total += ($cpc * $last_volume);

		return $total;

	}

	/**
* GetPanelScreenerFee()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 12:30:41 PST 2005
*/
	function GetPanelScreenerFee($cps, $s, $n, $cpc, $discount_cpc)
	{
		if ($cps == 0)
		return 0;

		$total_cpc = $n * $cpc;

		$discount = $discount_cpc / $total_cpc;

		$total = $cps * $s * $discount;

		return $total;
	}

	/**
* GetPanelUsageFee()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 12:31:59 PST 2005
*/
	function GetPanelUsageFee($pc_fee, $ps_fee)
	{
		return $pc_fee + $ps_fee;
	}

	/**
* GetTotalFieldWorkCost()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 12:33:17 PST 2005
*/
	function GetTotalFieldWorkCost($hosting_cost, $panel_cost)
	{
		return $hosting_cost + $panel_cost;
	}

	/**
* GetTotalCosts()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Nov 11 12:33:57 PST 2005
*/
	function GetTotalCosts($project_cost, $fieldword_cost)
	{
		return $project_cost + $fieldword_cost;
	}


	/**
* GetPanelImportFee()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Thu Jan 05 17:57:36 PST 2006
*/
	function GetPanelImportFee($panel_import_fee, $panel_import_hours)
	{
		return $panel_import_fee * $panel_import_hours;
	}

	/**
* GetClientPortalFee()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Thu Jan 05 17:37:34 PST 2006
*/
	function GetRespondentPortalFee($portal_type_id, $portal_setup_fee, $portal_programming_fee, $portal_programming_hours, $country_number)
	{
		if ($country_number != 1) {
			return 0;
		}

		if ($portal_type_id == RESPONDENT_PORTAL_SIMPLE) {
			return $portal_setup_fee;
		}

		$total = $portal_programming_fee * $portal_programming_hours;
		$total += $portal_setup_fee;

		return $total;
	}

	/**
* GetClientPortalFee()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 29 07:20:17 PST 2006
*/
	function GetClientPortalFee($portal_programming_fee, $portal_programming_hours, $country_number)
	{
		if ($country_number != 1) {
			return 0;
		}

		$total = $portal_programming_fee * $portal_programming_hours;

		return $total;
	}
	
	/**
* ModifyLicenseLevelPricingItem()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Nov 10 10:29:01 PST 2005
*/
	function ModifyLicenseLevelPricingItem(&$budget, $license_level_pricing_item_id, $amount)
	{
		foreach ($budget as $key => $val) {
			if ($budget[$key]['license_level_pricing_item_id'] == $license_level_pricing_item_id) {
				$budget[$key]['amount'] = $amount;
				return true;
			}
		}
	}

	/**
* SaveProposalDiscount()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Nov 08 09:48:08 PST 2005
*/
	function SaveDiscount($o)
	{
		$e = new Encryption();
		$p = new proposalDB();

		$p->SetProposalId($o['proposal_id']);
		$p->SetRevisionId($o['proposal_revision_id']);

		//find the current revision,
		//$revision_id = $p->GetCurrentRevisionId($o['id']);
		//$p->SetRevisionId($revision_id);

		//save pricing for the current revision
		$rs_pricing_items = $p->GetPricingItemList();

		while ($r_pricing_item = mysql_fetch_assoc($rs_pricing_items)) {

			//update pricing
			$p->UpdateRevisionPrice($o['proposal_revision_price_'.$r_pricing_item['pricing_item_id']], $o['license_level_price_'.$r_pricing_item['pricing_item_id']],
			$o['inflator_'.$r_pricing_item['pricing_item_id']], $o['contracted_discount_'.$r_pricing_item['pricing_item_id']],
			$o['non_contracted_discount_'.$r_pricing_item['pricing_item_id']], $o['promotional_discount_'.$r_pricing_item['pricing_item_id']],
			$o['ad_hoc_discount_'.$r_pricing_item['pricing_item_id']], $o['net_pricing_'.$r_pricing_item['pricing_item_id']]);
		}

		//lets save group pricing
		$rs = $p->GetRevisionGroupDiscount();

		while ($r = mysql_fetch_assoc($rs)) {
			if ($o['group_discount_'. $r['proposal_revision_group_discount_id']] != '') {
				$p->UpdateRevisionGroupAdHocDiscount($r['proposal_revision_group_discount_id'], $o['group_discount_'. $r['proposal_revision_group_discount_id']]);
			}
		}

		//save panel cost adhoc
		$rs = $p->GetRevisionPanelCost();

		while ($r = mysql_fetch_assoc($rs)) {
			if ($o['panel_cost_'. $r['proposal_revision_panel_cost_id']] != '') {
				$p->UpdateRevisionPanelCostAdHocDiscount($r['proposal_revision_panel_cost_id'], $o['panel_cost_'. $r['proposal_revision_panel_cost_id']]);
			}
		}

		$p->UpdateAttr('NEXT_ACTION', 'display_options');
		$p->UpdateAttr('WORKING_REVISION', $o['proposal_revision_id']);


		header("Location: ?e=". $e->Encrypt("action=display_options&proposal_id=". $o['proposal_id'] ."&proposal_revision_id=". $o['proposal_revision_id']));

	}

	/**
* DisplayReviewDiscount()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Oct 19 13:10:32 PDT 2005
*/
function DisplayReviewDiscount($o)
{
   global $smarty;

   $p = new proposalDB();
   $p->SetProposalId($o['proposal_id']);
   $p->SetRevisionId($o['proposal_revision_id']);
   
   $proposal = $p->GetBasicDetail();
   $revision = $p->GetRevisionDetail();

   //when  we get here we should read the pricing from a table with all rules applied,
   $list['budget_setup'] = PrepareSmartyArray($p->GetRevisionPricingByGroup(PRICING_GROUP_SETUP));
   $list['budget_hosting'] = PrepareSmartyArray($p->GetRevisionPricingByGroup(PRICING_GROUP_HOSTING));
   $list['budget_dp'] = PrepareSmartyArray($p->GetRevisionPricingByGroup(PRICING_GROUP_DP));
   $list['volume_discount'] = PrepareSmartyArray($p->GetPricingVolumeDiscountByLicenseLevel($proposal['license_level_id']));
   $list['budget_panel'] = PrepareSmartyArray($p->GetRevisionPanelCost());
   $list['group_discount']['setup'] = PrepareSmartyArray($p->GetRevisionGroupDiscountByGroup(PRICING_GROUP_SETUP));
   $list['group_discount']['panel'] = PrepareSmartyArray($p->GetRevisionGroupDiscountByGroup(PRICING_GROUP_PANEL));
   $list['group_discount']['hosting'] = PrepareSmartyArray($p->GetRevisionGroupDiscountByGroup(PRICING_GROUP_HOSTING));
   $list['group_discount']['dp'] = PrepareSmartyArray($p->GetRevisionGroupDiscountByGroup(PRICING_GROUP_DP));


   $smarty->assign('list', $list);
   $smarty->assign('proposal', $proposal);
   $smarty->assign('revision', $revision);

   DisplayHeader("Proposal Manager", "pgen");

   $smarty->display('app/pgen/vw_review_discount.tpl');

   DisplayFooter();

}

/**
* ApplyInflators()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Nov 10 14:50:12 PST 2005
*/
function ApplyInflatorsAndDiscount(&$budget)
{
   foreach ($budget as $key => $value) {
   	//pricing inflator
      $budget[$key]['net_amount'] = $budget[$key]['amount'] + ($budget[$key]['amount'] * ($budget[$key]['inflator_amount'] / 100));

      //contracted discounts
      $budget[$key]['net_amount'] = $budget[$key]['net_amount'] - ($budget[$key]['net_amount'] * ($budget[$key]['cd_amount'] / 100)) ;

      //non contracted discounts
   	$budget[$key]['net_amount'] = $budget[$key]['net_amount'] - ($budget[$key]['net_amount'] * ($budget[$key]['ncd_amount'] / 100)) ;

   	//promotional discounts
   	$budget[$key]['net_amount'] = $budget[$key]['net_amount'] - ($budget[$key]['net_amount'] * ($budget[$key]['pd_amount'] / 100)) ;
   }

}

}

?>