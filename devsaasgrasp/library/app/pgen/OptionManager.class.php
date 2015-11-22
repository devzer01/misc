<?php

class pgen_OptionManager
{

    /**
     * SaveProposalOptions()
     *
     * @param
     * @param -
     * @return
     * @throws
     * @access
     * @global
     * @since  - Thu Oct 20 11:56:27 PDT 2005
     */
    function SaveOptions ($o)
    {
        //$e = new Encryption();
        $p = new proposalDB();
        //$p->SetProposalId($o['proposal_id']);
        $p->SetRevisionId($o['rev']);
        $o['proposal_id'] = $p->GetProposalId();
        $p->SetProposalId($o['proposal_id']);
        
        $revision = $p->GetRevisionDetail();
        
        $skip_save_options = 0;
        
        if ($o['update_options'] == 0) {
            //we need to see if the options was saved before and if they are comming from a newly opend window
            $p->GetRevisionOptions();
            if ($p->rows > 1) {
                $_SESSION['ppm_message'] = "Options has been saved previously, use previous button to update options";
                $skip_save_options = 1;
            }
        }
        
        if ($skip_save_options == 0) {
            for ($i = 1; $i <= $revision['number_of_options']; $i ++) {
                
                for ($x = 1; $x <= $revision['number_of_countries']; $x ++) {
                    
                    if ($o['update_options'] == 0 || (isset($o['proposal_revision_option_id_option_' . $i . '_country_' . $x]) && $o['proposal_revision_option_id_option_' . $i . '_country_' . $x] == '')) {
                        
                        $p->SetOption($o['rev'], $o['country_name_option_' . $i . '_country_' . $x], $o['option_number_' . $i . '_country_' . $x], $o['sub_group_description_option_' . $i . '_country_' . $x], $o['study_programming_type_id_option_' . $i . '_country_' . $x], $o['translation_option_' . $i . '_country_' . $x], $o['translation_language_code_option_' . $i . '_country_' . $x], $o['overlay_option_' . $i . '_country_' . $x], $o['overlay_language_code_option_' . $i . '_country_' . $x], $o['study_datasource_id_option_' . $i . '_country_' . $x], $o['incidence_rate_option_' . $i . '_country_' . $x], $o['completes_option_' . $i . '_country_' . $x], $o['questions_programmed_option_' . $i . '_country_' . $x], $o['questions_per_interview_option_' . $i . '_country_' . $x], $o['questions_per_screener_option_' . $i . '_country_' . $x], $o['data_recording_hours_option_' . $i . '_country_' . $x], $o['data_tab_hours_option_' . $i . '_country_' . $x], $o['data_import_hours_option_' . $i . '_country_' . $x], $o['data_export_hours_option_' . $i . '_country_' . $x], $o['open_end_questions_option_' . $i . '_country_' . $x], $o['incidence_of_open_end_option_' . $i . '_country_' . $x], $o['avg_words_per_open_end_option_' . $i . '_country_' . $x], $o['open_end_text_coding_hours_option_' . $i . '_country_' . $x], $o['respondent_portal_type_id_option_' . $i . '_country_' . $x], $o['respondent_portal_programming_hours_option_' . $i . '_country_' . $x], $o['panel_import_hours_option_' . $i . '_country_' . $x], $o['sort_order_option_' . $i . '_country_' . $x], $o['client_portal_programming_hours_option_' . $i . '_country_' . $x]);
                    } else {
                        $p->UpdateOption($o['proposal_revision_option_id_option_' . $i . '_country_' . $x], $o['country_name_option_' . $i . '_country_' . $x], $o['option_number_' . $i . '_country_' . $x], $o['sub_group_description_option_' . $i . '_country_' . $x], $o['study_programming_type_id_option_' . $i . '_country_' . $x], $o['translation_option_' . $i . '_country_' . $x], $o['translation_language_code_option_' . $i . '_country_' . $x], $o['overlay_option_' . $i . '_country_' . $x], $o['overlay_language_code_option_' . $i . '_country_' . $x], $o['study_datasource_id_option_' . $i . '_country_' . $x], $o['incidence_rate_option_' . $i . '_country_' . $x], $o['completes_option_' . $i . '_country_' . $x], $o['questions_programmed_option_' . $i . '_country_' . $x], $o['questions_per_interview_option_' . $i . '_country_' . $x], $o['questions_per_screener_option_' . $i . '_country_' . $x], $o['data_recording_hours_option_' . $i . '_country_' . $x], $o['data_tab_hours_option_' . $i . '_country_' . $x], $o['data_import_hours_option_' . $i . '_country_' . $x], $o['data_export_hours_option_' . $i . '_country_' . $x], $o['open_end_questions_option_' . $i . '_country_' . $x], $o['incidence_of_open_end_option_' . $i . '_country_' . $x], $o['avg_words_per_open_end_option_' . $i . '_country_' . $x], $o['open_end_text_coding_hours_option_' . $i . '_country_' . $x], $o['respondent_portal_type_id_option_' . $i . '_country_' . $x], $o['respondent_portal_programming_hours_option_' . $i . '_country_' . $x], $o['panel_import_hours_option_' . $i . '_country_' . $x], $o['sort_order_option_' . $i . '_country_' . $x], $o['client_portal_programming_hours_option_' . $i . '_country_' . $x]);
                    }
                }
            }
        }
        
        //WE NEED TO DELETE THE UNSED options if we are comming from a revision
        //$r_revision = $p->GetBasicDetail();
        


        //Find Out Actual # of Options there is, then see if its greator than provided # of optiions * countries
        //if greator then delete all unused options
        $actual_options = $p->GetRevisionOptionCount();
        $suggested_options = $revision['number_of_countries'] * $revision['number_of_options'];
        
        if ($actual_options > $suggested_options) {
            
            $rs = $p->GetRevisionOptions();
            
            while ($r = mysql_fetch_assoc($rs)) {
                if ($r['option_number'] > $revision['number_of_options'] || $r['sort_order'] > $revision['number_of_countries']) {
                    $p->DeleteRevisionOptionById($r['proposal_revision_option_id']);
                }
            }
        
        }
        

        //this is where it all going to happen, run stuff through formulas
        //need to get the base pricing grid, and all differnt options and countries
        $pricing_manager = new pgen_PricingManager();
        $pricing_manager->CalculateProposal($o['proposal_id'], $o['rev']);
        
        if ($revision['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
            $action = 'display_custom_pricing';
        } else {
            $action = 'display_review_proposal';
        }
        

        if ($p->isAttrSet('WORKING_REVISION')) {
            $p->UpdateAttr('WORKING_REVISION', $o['rev']);
        } else {
            $p->SetAttr('WORKING_REVISION', $o['rev']);
        }
        
        $p->UpdateAttr('NEXT_ACTION', $action);
        
        return $o['rev'];
        //header("Location: ?e=" . $e->Encrypt("action=" . $action . "&proposal_id=" . $o['proposal_id'] . "&proposal_revision_id=" . $o['proposal_revision_id']));
    

    }

    
    /**
     * DisplayOptions()
     *
     * @param
     * @param -
     * @return
     * @throws
     * @access
     * @global
     * @since  - Wed Oct 19 13:29:52 PDT 2005
     */
    function DisplayOptions ($o)
    {
        global $smarty;
        
        $p = new proposalDB();
        $c = new commonDB();
        $s = new studyDB();
        
        //get the basic proposal details
        $p->SetProposalId($o['proposal_id']);
        $p->SetRevisionId($o['proposal_revision_id']);
        
        //timezone stuff
        $tz = GetTimeZone($o);
        $p->tz = $tz;
        
        $proposal = $p->GetBasicDetail();
        $revision = $p->GetRevisionDetail();
        
        $p->UpdateAttr('NEXT_ACTION', "display_options");
        
        //get country list
        $list['country'] = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
        $list['study_programming_type'] = CreateSmartyArray($s->GetProgrammingTypes(), 'study_programming_type_id', 'study_programming_type_description');
        $list['yes_no'] = array("No" , "Yes");
        $list['sample_source'] = $s->getDataSources(); //this returns an array instead of a RS //we need to fix these things to be consistent
        $list['language'] = CreateSmartyArray($c->GetLanguageList(), 'language_code', 'language_description');
        $list['respondent_portal_type'] = CreateSmartyArray($p->GetRespondentPortalTypes(), 'respondent_portal_type_id', 'respondent_portal_type_description');
        
        $revision["panel_details"] = $p->GetRevisionAttr("PPM_DETAILED_PANEL_CALCULATION");
        
        //we need to see if the options has been saved
        $rs_options = $p->GetRevisionOptions();
        
        $o['update_options'] = 0;
        
        //we already have options saved
        if ($p->rows > 0)
            $o['update_options'] = 1;
            

        //for single country multi options
        if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] > 1) {
            while ($r_option = mysql_fetch_assoc($rs_options)) {
                $options[$r_option['option_number']] = $r_option;
            }
            
            $template_name = 'app/pgen/vw_options_single.tpl';
        } else {
            //this is for multi country multi options, single country single option
            while ($r_option = mysql_fetch_assoc($rs_options)) {
                $options[$r_option['option_number']][$r_option['sort_order']] = $r_option;
            }
            
            $template_name = 'app/pgen/vw_options.tpl';
        }
        
        $rs_services = $p->GetRevisionServiceList();
        while ($r_sl = mysql_fetch_assoc($rs_services)) {
            $services[$r_sl['service_id']] = 1;
        }
        
        DisplayHeader("Proposal Manager", "pgen");
        
        $smarty->assign('proposal', $proposal);
        $smarty->assign('revision', $revision);
        $smarty->assign('list', $list);
        $smarty->assign('options', $options);
        $smarty->assign('meta', $o);
        $smarty->assign('services', $services);
        $smarty->display($template_name);
        
        DisplayFooter();
    }

    function DisplayPanelDetails ($o)
    {
        global $smarty;
        
        $p = new proposalDB();
        $c = new commonDB();
        $s = new studyDB();
        

        //get the basic proposal details
        $p->SetProposalId($o['proposal_id']);
        $p->SetRevisionId($o['proposal_revision_id']);
        
        //timezone stuff
        $tz = GetTimeZone($o);
        $p->tz = $tz;
        
        $proposal = $p->GetBasicDetail();
        $revision = $p->GetRevisionDetail();
        
        $p->UpdateAttr('NEXT_ACTION', "display_panel_details");
        
        //get country list
        $list['country'] = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
        $sample_type_rst = $p->GetRevisionSampleTypes();
        
        $sample_type_ids[0] = "All";
        while ($sample_type = mysql_fetch_assoc($sample_type_rst)) {
            $sample_type_ids[$sample_type["sample_type_id"]] = $sample_type["sample_type_description"];
            
            $list['sample_type_list'][$sample_type["sample_type_id"]] = $sample_type["sample_type_description"];
            
            $pricing_options_list_rst = $p->GetSampleTypePricingLists($sample_type["sample_type_id"]);
            
            while ($l = mysql_fetch_assoc($pricing_options_list_rst)) {
                $items_rst = $p->GetSampleTypePricingItems($sample_type["sample_type_id"], $l["list_id"]);
                
                while ($item = mysql_fetch_assoc($items_rst)) {
                    $list['sample_type_options_list'][$sample_type["sample_type_id"]][$l["list_id"]]['values'][$item["sample_type_pricing_id"]] = $item["item_description"] . " ($" . number_format($item["premium"], 2) . ")";
                }
            }
        }
        

        $options = array();
        for ($k = 1; $k <= $proposal['number_of_options']; $k ++) {
            
            $data_result = $p->GetProposalRevisionPanelData($o['proposal_revision_id'], $k);
            
            $c = 0;
            $old_country = - 1;
            while ($row = mysql_fetch_assoc($data_result)) {
                
                if (! in_array($row["sample_type_id"], array_keys($sample_type_ids))) {
                    continue;
                }
                if ($row["country_id"] != $old_country) {
                    $old_country = $row["country_id"];
                    $c ++;
                    if ($c > $proposal['number_of_countries'])
                        break;
                }
                
                if (! $row['sample_type_id']) {
                    $row['sample_type_description'] = 'All';
                }
                
                $row['extras'] = array();
                
                if (! $row['prime']) {
                    $extras_result = $p->GetProposalCountryPanelExtraData($row['proposal_revision_panel_id']);
                    
                    while ($row_extras = mysql_fetch_array($extras_result)) {
                        $row['extras'][$row_extras['list_id']] = $row_extras['sample_type_pricing_id'];
                    }
                    
                    $sample_des_result = $p->GetProposalCountryPanelAttr($row['proposal_revision_panel_id'], 'SAMPLE_TYPE_DESCRIPTION');
                    $row['sample_type_description'] = mysql_result($sample_des_result, 0, 'proposal_revision_panel_attr_value');
                }
                
                $options[$k][$c][$row["sample_type_id"]][] = $row;
            }
            
            for ($c = 1; $c <= $proposal['number_of_countries']; $c ++) {
                foreach ($sample_type_ids as $sample_type_id => $sample_type_description) {
                    if (! isset($options[$k][$c][$sample_type_id])) {
                        $options[$k][$c][$sample_type_id][] = array("proposal_revision_panel_id" => 0 , "completes" => 0 , "incidence" => 0 , "question_length" => 0 , "prime" => 1 , "sample_type_id" => $sample_type_id , "sample_type_description" => $sample_type_description , "country_id" => 0 , "country_code" => "" , "country_descriptoin" => "" , "extras" => array());
                    }
                }
            }
        }
        //        echo "<pre>"; print_r($options); echo "</pre>"; 
        


        //we need to see if the options has been saved
        DisplayHeader("Proposal Manager", "pgen");
        
        $smarty->assign('options', $options);
        $smarty->assign('proposal', $proposal);
        $smarty->assign('revision', $revision);
        $smarty->assign('list', $list);
        $smarty->assign('meta', $o);
        $smarty->display('app/pgen/vw_panel.tpl');
        
        DisplayFooter();
    
    }

    private function RemoveExtraPanelDetails ($proposal_revision_id, $number_of_options, $country_ids, $sample_type_ids)
    {
        $p = new proposalDB();
        
        if (count($country_ids) && count($sample_type_ids)) {
           $rst = $p->GetExtraPanelDetails($proposal_revision_id, $number_of_options, $country_ids, $sample_type_ids);
        
           $ids = array();
        
           while ($r = mysql_fetch_assoc($rst)) {
              $ids[] = $r["proposal_revision_panel_id"];
           }
        
           if (sizeof($ids)) {
              $p->DeletePanelDetails($ids);
           }
        }    
    }

    public function SavePanelDetails ($o)
    {
        $p = new proposalDB();
        $c = new commonDB();
        $s = new studyDB();
        
        $pricing = new pgen_PricingManager();
        
        //get the basic proposal details
        $p->SetProposalId($o['proposal_id']);
        $p->SetRevisionId($o['proposal_revision_id']);
        $proposal = $p->GetBasicDetail();
        $revision = $p->GetRevisionDetail();

        //timezone stuff
        $tz = GetTimeZone($o);
        $p->tz = $tz;
        
        // get the sample type ids related to proposal revision and store in an array
        $sample_type_ids = array();
        
        $sample_type_ids_result = $p->GetRevisionSampleTypeIds($o['proposal_revision_id']);
        
        $sample_type_ids[] = 0;
        while ($row = mysql_fetch_array($sample_type_ids_result)) {
            
            $sample_type_ids[] = $row['sample_type_id'];
        
        }
        
        for ($option_count = 1; $option_count <= $revision['number_of_options']; $option_count ++) {
            
            for ($country_count = 1; $country_count <= $revision['number_of_countries']; $country_count ++) {
                
                $suffix = "_option_" . $option_count . "_country_" . $country_count;
                
                // getting country id using country code
                $country_code = $o['country_name_option_' . $option_count . '_country_' . $country_count];

                if ($country_code == "") {
                    continue;
                }

                $country = Hb_App_ObjectHelper::GetMapper('Hb_App_Common_Country')->Find($country_code);
                $country_id = $country->getCountryId();
                //Needed to delete all the entries that are NOT for one of these countries
                $country_ids[$country_id] = $country_id;
                                
                foreach ($sample_type_ids as $sample_type_id) {
                    
                    $cpc = 0; $total_cost = 0; $adjustment = 0;
                    
                    $unique_suffix = $suffix . '_' . $sample_type_id;
                    $prime = $o["prime" . $unique_suffix];
                    $proposal_revision_panel_id = $o["proposal_revision_panel_id" . $unique_suffix];
                    $completes = ($o['completes' . $unique_suffix] != '') ? $o['completes' . $unique_suffix] : 0;
                    $incidence = ($o['incidence' . $unique_suffix] != '') ? $o['incidence' . $unique_suffix] : 0;
                    $length = ($o['length' . $unique_suffix] != '') ? $o['length' . $unique_suffix] : 0;
                    
                    //$num_of_lines = count( $o['completes' . $unique_suffix] );
                    
                    if (in_array($sample_type_id, array(SAMPLE_TYPE_CONSUMER_GENERAL, SAMPLE_TYPE_B2B_GENERAL, SAMPLE_TYPE_B2BIT))) {
                       $cpc = Hb_App_Proposal_RateCard::GetCostPerInterview($sample_type_id, $country_code, $incidence, $length, $proposal["country_code"]);
                    }
                    
                    $total_cost = $pricing->GetPanelCompleteFee($cpc, $completes, $proposal["license_level_id"], $proposal["account_id"]);                    
                    $adjustment = ($cpc * $completes) - $total_cost;
                    
                    $sums[$option_count][$country_count][$sample_type_id]["cpc"] = 0;
                    $sums[$option_count][$country_count][$sample_type_id]["total"] = 0;
                    $sums[$option_count][$country_count][$sample_type_id]["adjustment"] = 0;
                    
                    if ($proposal_revision_panel_id) {
                        $p->UpdateProposalRevisionPanel($proposal_revision_panel_id, $country_id, $completes, $incidence, $length, $cpc, $total_cost, $adjustment);
                    } else {
                        $proposal_revision_panel_id = $p->SetProposalRevisionPanel($o["proposal_revision_id"], $option_count, $country_id, $sample_type_id, $prime, $completes, $incidence, $length, $cpc, $total_cost, $adjustment);
                    }
                    
                    $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["id"] = $proposal_revision_panel_id;
                    $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["country_id"] = $country_id;
                    $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["country_code"] = $country_code;
                    $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["completes"] = $completes;
                    $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["incidence"] = $incidence;
                    $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["length"] = $length;
                    $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["cpc"] = $cpc;
                    $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["total_cost"] = $total_cost;
                    $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["adjustment"] = $adjustment;
                    
                    
                    $i = 0;
                    while (($i < 300) || (isset($o["prime" . $unique_suffix . "_" . $i]))) 
                    {
                        if (!isset($o["prime" . $unique_suffix . "_$i"])) {
                           $i++;
                           continue;
                        }
                        
                        $extra_unique_suffix = $unique_suffix . "_$i";
                        
                        $prime = $o["prime" . $extra_unique_suffix];
                        $proposal_revision_panel_id = $o["proposal_revision_panel_id" . $extra_unique_suffix];
                        $description = $o["description" . $extra_unique_suffix];
                        $completes = ($o['completes' . $extra_unique_suffix] != '') ? $o['completes' . $extra_unique_suffix] : 0;
                        $incidence = ($o['incidence' . $extra_unique_suffix] != '') ? $o['incidence' . $extra_unique_suffix] : 0;
                        $length = ($o['length' . $extra_unique_suffix] != '') ? $o['length' . $extra_unique_suffix] : 0;
                        
                        if (in_array($sample_type_id, array(SAMPLE_TYPE_CONSUMER_GENERAL, SAMPLE_TYPE_B2B_GENERAL, SAMPLE_TYPE_B2BIT))) {
                           $cpc = Hb_App_Proposal_RateCard::GetCostPerInterview($sample_type_id, $country_code, $incidence, $length, $proposal["country_code"]);
                        }
                        if ($proposal_revision_panel_id) 
                        {
                            $pricing_options_list_rst = $p->GetSampleTypePricingLists($sample_type_id);
                            
                            while ($row = mysql_fetch_assoc($pricing_options_list_rst)) {
                                $extra = $o['extra_' . $row['list_id'] . $extra_unique_suffix];
                                $p->UpdateProposalRevisionPanelExtra($proposal_revision_panel_id, $row["list_id"], $extra);
                                
                                $premium_rst = $p->GetSampleTypePricingItem($extra);
                                $premium = mysql_fetch_assoc($premium_rst);
                                $cpc += $premium["premium"];
                            }
                            
                            $total_cost = $pricing->GetPanelCompleteFee($cpc, $completes, $proposal["license_level_id"], $proposal["account_id"]);                    
                            $adjustment = ($cpc * $completes) - $total_cost;
                            
                            $p->UpdateProposalRevisionPanel($proposal_revision_panel_id, $country_id, $completes, $incidence, $length, $cpc, $total_cost, $adjustment);
                            $p->UpdateProposalRevisionPanelDescription($proposal_revision_panel_id, $description);
                            
                        } else {
                            $id = $p->SetProposalRevisionPanel($o["proposal_revision_id"], $option_count, $country_id, $sample_type_id, $prime, $completes, $incidence, $length, 0, 0, 0);
                            $p->SetProposalRevisionPanelDescription($id, $description);
                            
                            $pricing_options_list_rst = $p->GetSampleTypePricingLists($sample_type_id);
                            while ($row = mysql_fetch_assoc($pricing_options_list_rst)) {
                                $extra = $o['extra_' . $row['list_id'] . $extra_unique_suffix];
                                $extra_id = $p->SetProposalRevisionPanelExtra($id, $row["list_id"], $extra);

                                $premium_rst = $p->GetProposalCountryPanelExtraData($extra_id);
                                $premium = mysql_fetch_assoc($premium_rst);
                                $cpc += $premium["premium"];
                            }

                            $total_cost = $pricing->GetPanelCompleteFee($cpc, $completes, $proposal["license_level_id"], $proposal["account_id"]);                    
                            $adjustment = ($cpc * $completes) - $total_cost;
                                                        
                            $p->UpdateProposalRevisionPanel($id, $country_id, $completes, $incidence, $length, $cpc, $total_cost, $adjustment);
                        }
                        
                        $sums[$option_count][$country_count][$sample_type_id]["cpc"] += $cpc; 
                        $sums[$option_count][$country_count][$sample_type_id]["total"] += $total_cost; 
                        $sums[$option_count][$country_count][$sample_type_id]["adjustment"] += $adjustment; 
                        
                        $counts[$option_count][$country_count][$sample_type_id] += 1;
                        
                        $i++;
                    }
                    
                }
            }
        }
        $this->RemoveExtraPanelDetails($o["proposal_revision_id"], $proposal["number_of_options"], $country_ids, $sample_type_ids);
        
        //print_r($proposal_revision_panels);
        
        for ($option_count = 1; $option_count <= $revision['number_of_options']; $option_count ++) {
            
            for ($country_count = 1; $country_count <= $revision['number_of_countries']; $country_count ++) {
               $total_cpc = 0; $total_total_cost = 0;  $total_adjustment = 0;
               foreach($sample_type_ids AS $sample_type_id) {
                  if ($counts[$option_count][$country_count][$sample_type_id]) {                  
                     $cpc = $sums[$option_count][$country_count][$sample_type_id]["cpc"] / $counts[$option_count][$country_count][$sample_type_id];
                     $total_cost = $sums[$option_count][$country_count][$sample_type_id]["total"];
                     $adjustment = $sums[$option_count][$country_count][$sample_type_id]["adjustment"];

                     $total_cpc += $cpc;
                     $total_total_cost += $total_cost;
                     $total_adjustment += $adjustment; 
                  
                     $p->UpdateProposalRevisionPanel(
                         $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["id"], 
                         $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["country_id"], 
                         $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["completes"], 
                         $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["incidence"], 
                         $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["length"],
                         $cpc, $total_cost, $adjustment);
                  } else {
                     $total_cpc += $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["cpc"]; 
                     $total_total_cost += $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["total_cost"]; 
                     $total_cpc += $proposal_revision_panels[$option_count][$country_count][$sample_type_id]["adjustment"];                      
                  }
               }
                                       
               $p->UpdateProposalRevisionPanel(
                         $proposal_revision_panels[$option_count][$country_count][0]["id"], 
                         $proposal_revision_panels[$option_count][$country_count][0]["country_id"], 
                         $proposal_revision_panels[$option_count][$country_count][0]["completes"], 
                         $proposal_revision_panels[$option_count][$country_count][0]["incidence"], 
                         $proposal_revision_panels[$option_count][$country_count][0]["length"], 
                         ($total_cpc / (sizeof ($sample_type_ids)-1)), $total_total_cost, $total_adjustment);
               $p->SetOptionFromPanel(
                        $o["proposal_revision_id"], $option_count, 
                        $proposal_revision_panels[$option_count][$country_count][0]["country_code"],
                        $proposal_revision_panels[$option_count][$country_count][0]["incidence"], 
                        $proposal_revision_panels[$option_count][$country_count][0]["completes"], 
                        $proposal_revision_panels[$option_count][$country_count][0]["length"],
                        $country_count);
            }
        }
        
        
        
        if ($p->isAttrSet('WORKING_REVISION')) {
            $p->UpdateAttr('WORKING_REVISION', $o['proposal_revision_id']);
        } else {
            $p->SetAttr('WORKING_REVISION', $o['proposal_revision_id']);
        }
        
        $p->UpdateAttr('NEXT_ACTION', "display_options");
        
        header("Location: ?action=display_options&proposal_id=" . $o["proposal_id"] . "&proposal_revision_id=" . $o["proposal_revision_id"]);
    }

    function DeletePanelDetails ($o)
    {
        
        $p = new proposalDB();
        
        $p->SetProposalId($o['proposal_id']);
        $p->SetRevisionId($o['proposal_revision_id']);
        
        $p->DeletePanelDetails($o['proposal_revision_panel_id']);
        
        header("Location: ?action=display_panel_details&proposal_id=" . $o["proposal_id"] . "&proposal_revision_id=" . $o["proposal_revision_id"]);
    
    }
}

?>