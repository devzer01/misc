<script src='/js/validate.js'></script>
<script src='/js/pgen.js'></script>
{popup_init src="/js/overlib/overlib.js"}

{include file='app/pgen/vw_summary.tpl}
<br>

<div id='prop_comment_top'>&nbsp;</div>
<br>


{literal}
<style>
	TR {
		FONT-FAMILY: Tahoma, Geneva, Arial, sans-serif; 
		FONT-SIZE: 8pt;
		TEXT-ALIGN: left;
		COLOR: black;
	}
</style>
{/literal}


<form name='searchform' 
      id='searchform' 
      action="?e={"action=save_options&proposal_id=`$proposal.proposal_id`&proposal_revision_id=`$revision.proposal_revision_id`"|url_encrypt}" 
      method="POST" 
      enctype="multipart/form-data">

{include file='common/div_header.tpl'
			div_title='Options'
			div_name='p_options'
			div_width='70%'
			div_align='center'}
			
<tr><td>

<table width="100%">
<tr class="header1">
   <td>&nbsp;</td>
   {section name="options" loop=$revision.number_of_options}
      <td>
      <strong>Option {$smarty.section.options.iteration}</strong>
     <!--    <strong>Option {$smarty.section.options.iteration}</strong>
         <input type="button" 
                value="Copy" 
                onclick="copy_options({$smarty.section.options.iteration}, 1);"> -->
      </td> 
   {/section}
</tr>
<tr>
   <td>Country Name</td>
   {section name="options" loop=$revision.number_of_options}
      <td>
         <select tabindex="{$smarty.section.options.iteration}10" 
                 name="country_name_option_{$smarty.section.options.iteration}_country_1"
                 onchange="copy_country(this,{$smarty.section.options.iteration}, 1);"
                 onkeyup="copy_country(this,{$smarty.section.options.iteration}, 1);">
         	<option value=""></option>
            {html_options options=$list.country 
                          selected=$options[$smarty.section.options.iteration].country_code}
         </select>
         <input type="hidden" name="r_country_name_option_{$smarty.section.options.iteration}_country_1" id="r_country_name_option_{$smarty.section.options.iteration}_country_1" value="Please select Country 1 for Option {$smarty.section.options.iteration}">
         <input type="hidden" 
                name="proposal_revision_option_id_option_{$smarty.section.options.iteration}_country_1" 
                value="{$options[$smarty.section.options.iteration].proposal_revision_option_id}">
         <input type="hidden" 
                name="sort_order_option_{$smarty.section.options.iteration}_country_1" 
                value="1">
         <input type="hidden"
                name="option_number_{$smarty.section.options.iteration}_country_1"
                value="{$smarty.section.options.iteration}">
      </td>
   {/section}
</tr>
{if $revision.proposal_option_type_id eq $smarty.const.PROPOSAL_OPTION_TYPE_SINGLE_SUB}
<tr>
   <td>Sub Group Name</td>
   {section name="options" loop=$revision.number_of_options}
      <td>
         <input value="{$options[$smarty.section.options.iteration].sub_group_description}"
                tabindex="{$smarty.section.options.iteration}11" 
                type="text"
                name="sub_group_description_option_{$smarty.section.options.iteration}_country_1" 
                size="30"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
         <input type="hidden" 
                name="r_sub_group_description_option_{$smarty.section.options.iteration}_country_1"
                value="Sub Group Name is Required">
      </td>
   {/section}
</tr>
{/if}

{if $services[$smarty.const.SERVICE_QPROG] eq "1"}
<tr>
   <td>GMI Provides Programming</td>
   {section name="options" 
            loop=$revision.number_of_options}
      <td>
         <select tabindex="{$smarty.section.options.iteration}12" 
                 name="study_programming_type_id_option_{$smarty.section.options.iteration}_country_1"
                 onchange="copy_value(this,{$smarty.section.options.iteration}, 1);"
                 onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
            {html_options options=$list.study_programming_type 
                          selected=$options[$smarty.section.options.iteration].study_programming_type_id}
         </select>
      </td>
   {/section}
</tr>
{/if}

{if $services[$smarty.const.SERVICE_RPORTAL] eq "1"}
<tr>
   <td>Respondent Portal </td>
   {section name="options" loop=$revision.number_of_options}
      <td>
         <select name="respondent_portal_type_id_option_{$smarty.section.options.iteration}_country_1" 
                 tabindex="{$smarty.section.options.iteration}13" 
                 onchange="copy_value(this,{$smarty.section.options.iteration}, 1) && display_portal_hours(this, {$smarty.section.options.iteration}, 1 );"
                 onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
            {html_options options=$list.respondent_portal_type 
                          selected=$options[$smarty.section.options.iteration].respondent_portal_type_id}
         </select>
         &nbsp;&nbsp;&nbsp;
         {if $options[$smarty.section.options.iteration].respondent_portal_type_id eq $smarty.const.RESPONDENT_PORTAL_COMPLEX}
         	<div id='dv_portal_hours_{$smarty.section.options.iteration}_1'>
			{else}
         	<div id='dv_portal_hours_{$smarty.section.options.iteration}_1' style="display: none;">
         {/if}
            Programming Hours
            <input size="3" 
                   type="text"
                   tabindex="{$smarty.section.options.iteration}14"  
                   name="respondent_portal_programming_hours_option_{$smarty.section.options.iteration}_country_1" 
                   value="{$options[$smarty.section.options.iteration].respondent_portal_programming_hours}"
                   onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
            <input type="hidden"
                   name="i_respondent_portal_programming_hours_option_{$smarty.section.options.iteration}_country_1"
                   value="Respondent Portal Programming Hours Must Be A Number">
         </div>
      </td>
   {/section}
</tr>
{/if}

{if $services[$smarty.const.SERVICE_CPORTAL] eq "1"}
<tr>
   <td>Client Portal </td>
   {section name="options" loop=$revision.number_of_options}
      <td>
            Programming Hours
            <input size="3" 
                   type="text"
                   tabindex="{$smarty.section.options.iteration}14"  
                   name="client_portal_programming_hours_option_{$smarty.section.options.iteration}_country_1" 
                   value="{$options[$smarty.section.options.iteration].client_portal_programming_hours}"
                   onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
            <input type="hidden"
                   name="i_client_portal_programming_hours_option_{$smarty.section.options.iteration}_country_1"
                   value="Client Portal Programming Hours Must Be A Number">
      </td>
   {/section}
</tr>
{/if}


{if $services[$smarty.const.SERVICE_QTRANS] eq "1"}
<tr>
   <td>GMI Provides Translation</td>
      {section name="options" loop=$revision.number_of_options}
      <td>
         <select tabindex="{$smarty.section.options.iteration}15" 
                 name="translation_option_{$smarty.section.options.iteration}_country_1"
                 onchange="copy_value(this,{$smarty.section.options.iteration}, 1) && display_translation_language_list(this, {$smarty.section.options.iteration}, 1);"
                 onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
            {html_options options=$list.yes_no 
                          selected=$options[$smarty.section.options.iteration].translation}
         </select>
         &nbsp;&nbsp;&nbsp;
         <div id='dv_translation_language_{$smarty.section.options.iteration}_1'
              style="display: none;">
            Select Language
            <select tabindex="{$smarty.section.options.iteration}16" 
                    name="translation_language_code_option_{$smarty.section.options.iteration}_country_1"
                    onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
               {html_options options=$list.language 
                             selected=$options[$smarty.section.options.iteration].translation_language_code}
            </select>
         </div>
      </td>
   {/section}

</tr>
{/if}
{if $services[$smarty.const.SERVICE_LOLAY] eq "1"}
<tr>
   <td>GMI Provides Language Overlay</td>
      {section name="options" loop=$revision.number_of_options}
       <td>
         <select tabindex="{$smarty.section.options.iteration}17" 
                 name="overlay_option_{$smarty.section.options.iteration}_country_1"
                 onchange="copy_value(this,{$smarty.section.options.iteration}, 1);"
                 onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
            {html_options options=$list.yes_no 
                          selected=$options[$smarty.section.options.iteration].overlay}
         </select>
         &nbsp;&nbsp;&nbsp;
         <!--<div id='dv_overlay_language_{$smarty.section.options.iteration}_1'
                display_overlay_language_list(this, {$smarty.section.options.iteration}, 1);
            style="display: none;">
            Select Language
            <select name="overlay_language_code_option_{$smarty.section.options.iteration}_country_1"
                    tabindex="{$smarty.section.options.iteration}18" 
                    onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
               {html_options options=$list.language 
                             selected=$options[$counter].overlay_language_code}
            </select>
         </div>-->
      </td>
   {/section}
</tr>
{/if}
{if $services[$smarty.const.SERVICE_PANEL] eq "1"}
<tr>
   <td>Sample Source</td>
      {section name="options" 
               loop=$revision.number_of_options}
      <td>
         <select tabindex="{$smarty.section.options.iteration}19" 
                 name="study_datasource_id_option_{$smarty.section.options.iteration}_country_1"
                 onchange="copy_value(this,{$smarty.section.options.iteration}, 1);"
                 onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
            {html_options options=$list.sample_source 
                          selected=$options[$smarty.section.options.iteration].study_datasource_id}
         </select>
      </td>
   {/section}
</tr>
{/if}
<tr>
   <td>Incidence rate</td>
      {section name="options" 
               loop=$revision.number_of_options}
         <td>
            <input tabindex="{$smarty.section.options.iteration}20" 
                   type="text" name="incidence_rate_option_{$smarty.section.options.iteration}_country_1" 
                   value="{$options[$smarty.section.options.iteration].incidence_rate}"
                   onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
            <input type="hidden"
                   name="i_incidence_rate_option_{$smarty.section.options.iteration}_country_1"
                   value="Incidence Must be a Number">
            <input type="hidden"
                   name="r_incidence_rate_option_{$smarty.section.options.iteration}_country_1"
                   value="Incidence Rate is Required">
            <input type="hidden"
                   name="p_incidence_rate_option_{$smarty.section.options.iteration}_country_1"
                   value="Incidence Rate Must be Between 1 and 100 ">
         </td>
      {/section}
</tr>
<tr>
   <td>Total Completes</td>
   {section name="options" 
            loop=$revision.number_of_options}
      <td>
         <input tabindex="{$smarty.section.options.iteration}21" 
                type="text" 
                name="completes_option_{$smarty.section.options.iteration}_country_1" 
                value="{$options[$smarty.section.options.iteration].completes}"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
         <input type="hidden"
                name="i_completes_option_{$smarty.section.options.iteration}_country_1"
                value="Total Completes Must Be A Number">
         <input type="hidden"
                name="r_completes_option_{$smarty.section.options.iteration}_country_1"
                value="Total Completes is Required">
      </td>
   {/section}

</tr>
<tr>
   <td>Total Questions Programmed/Translated</td>
   {section name="options" 
            loop=$revision.number_of_options}
      <td>
         <input tabindex="{$smarty.section.options.iteration}22" 
                type="text" 
                name="questions_programmed_option_{$smarty.section.options.iteration}_country_1" 
                value="{$options[$smarty.section.options.iteration].questions_programmed}"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
         <input type="hidden" 
                name="i_questions_programmed_option_{$smarty.section.options.iteration}_country_1"
                value="Total Questions Programmed Must Be a Number">
         <input type="hidden" 
                name="r_questions_programmed_option_{$smarty.section.options.iteration}_country_1"
                value="Total Questions Programmed is Required">
      </td>
   {/section}

</tr>
<tr>
   <td>Questions per completed interview</td>
   {section name="options" 
            loop=$revision.number_of_options}
      <td>
         <input tabindex="{$smarty.section.options.iteration}23" 
                type="text" 
                name="questions_per_interview_option_{$smarty.section.options.iteration}_country_1" 
                value="{$options[$smarty.section.options.iteration].questions_per_interview}"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
         <input type="hidden"
                name="i_questions_per_interview_option_{$smarty.section.options.iteration}_country_1"
                value="Question Per Completed Interview Must Be a Number">
         <input type="hidden"
                name="r_questions_per_interview_option_{$smarty.section.options.iteration}_country_1"
                value="Question Per Completed Interview is Required">
      </td>
   {/section}

</tr>
<tr>
   <td>Questions per screener interview</td>
   {section name="options" 
            loop=$revision.number_of_options}
      <td>
         <input tabindex="{$smarty.section.options.iteration}24" 
                type="text" 
                name="questions_per_screener_option_{$smarty.section.options.iteration}_country_1" 
                value="{$options[$smarty.section.options.iteration].questions_per_screener}"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
         <input type="hidden"
                name="i_questions_per_screener_option_{$smarty.section.options.iteration}_country_1"
                value="Question Per Screener Interview Must Be a Number">
         <input type="hidden"
                name="r_questions_per_screener_option_{$smarty.section.options.iteration}_country_1"
                value="Question Per Screener Interview is Required">
      </td>
   {/section}

</tr>
{if $services[$smarty.const.SERVICE_DATA_RECODING] eq "1"}
<tr>
   <td>Data Recoding Hours:</td>
   {section name="options" 
            loop=$revision.number_of_options}
      <td>
         <input tabindex="{$smarty.section.options.iteration}25" 
                type="text" 
                name="data_recording_hours_option_{$smarty.section.options.iteration}_country_1" 
                value="{$options[$smarty.section.options.iteration].data_recording_hours}"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
          <input type="hidden"
                name="i_data_recording_hours_option_{$smarty.section.options.iteration}_country_1"
                value="Data Recording Hours Must Be a Number">
         <input type="hidden"
                name="r_data_recording_hours_option_{$smarty.section.options.iteration}_country_1"
                value="Data Recording Hours is Required">
      </td>
   {/section}
</tr>
{/if}
{if $services[$smarty.const.SERVICE_DATA_TAB] eq "1"}
<tr>
   <td>Data Tab Hours</td>
   {section name="options" 
            loop=$revision.number_of_options}
      <td>
         <input tabindex="{$smarty.section.options.iteration}26" 
                type="text" 
                name="data_tab_hours_option_{$smarty.section.options.iteration}_country_1" 
                value="{$options[$smarty.section.options.iteration].data_tab_hours}"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
         <input type="hidden"
                name="i_data_tab_hours_option_{$smarty.section.options.iteration}_country_1"
                value="Data Tab Hours Must Be a Number">
         <input type="hidden"
                name="r_data_tab_hours_option_{$smarty.section.options.iteration}_country_1"
                value="Data Tab Hours is Required">
      </td>
   {/section}
</tr>
{/if}
{if $services[$smarty.const.SERVICE_DATA_IMPORT] eq "1"}
<tr>
   <td>Data Importing Hours</td>
   {section name="options" 
            loop=$revision.number_of_options}
      <td>
         <input tabindex="{$smarty.section.options.iteration}27" 
                type="text" 
                name="data_import_hours_option_{$smarty.section.options.iteration}_country_1" 
                value="{$options[$smarty.section.options.iteration].data_import_hours}"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
         <input type="hidden"
                name="i_data_import_hours_option_{$smarty.section.options.iteration}_country_1"
                value="Data Import Hours Must Be a Number">
         <input type="hidden"
                name="r_data_import_hours_option_{$smarty.section.options.iteration}_country_1"
                value="Data Import Hours is Required">
      </td>
   {/section}
</tr>
{/if}
{if $services[$smarty.const.SERVICE_DATA_EXPORT] eq "1"}
<tr>
   <td>Data Exporting Hours</td>
   {section name="options" 
            loop=$revision.number_of_options}
      <td>
         <input tabindex="{$smarty.section.options.iteration}28" 
                type="text" 
                name="data_export_hours_option_{$smarty.section.options.iteration}_country_1" 
                value="{$options[$smarty.section.options.iteration].data_export_hours}"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
         <input type="hidden"
                name="i_data_export_hours_option_{$smarty.section.options.iteration}_country_1"
                value="Data Export Hours Must Be a Number">
         <input type="hidden"
                name="r_data_export_hours_option_{$smarty.section.options.iteration}_country_1"
                value="Data Export Hours is Required">
      </td>
   {/section}
</tr>
{/if}
{if $services[$smarty.const.SERVICE_OE_RESPONSE_TRANS] eq "1"}
<tr>
   <td>Open-end Text Translation (# of Q's)</td>
   {section name="options" 
            loop=$revision.number_of_options}
      <td>
         <input tabindex="{$smarty.section.options.iteration}29" 
                type="text" 
                name="open_end_questions_option_{$smarty.section.options.iteration}_country_1" 
                value="{$options[$smarty.section.options.iteration].open_end_questions}"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
         <input type="hidden"
                name="i_open_end_questions_option_{$smarty.section.options.iteration}_country_1"
                value="Open End Questions Must Be a Number">
         <input type="hidden"
                name="r_open_end_questions_option_{$smarty.section.options.iteration}_country_1"
                value="Open End Questions is Required">
      </td>
   {/section}

</tr>
<tr>
   <td>Incidence of Open-ended Responses</td>
   {section name="options" 
            loop=$revision.number_of_options}
      <td>
         <input tabindex="{$smarty.section.options.iteration}30" 
                type="text" 
                name="incidence_of_open_end_option_{$smarty.section.options.iteration}_country_1" 
                value="{$options[$smarty.section.options.iteration].incidence_of_open_end}"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
         <input type="hidden"
                name="i_incidence_of_open_end_option_{$smarty.section.options.iteration}_country_1"
                value="Incidence of Open End Must Be a Number">
         <input type="hidden"
                name="r_incidence_of_open_end_option_{$smarty.section.options.iteration}_country_1"
                value="Incidence of Open End is Required">
      </td>
   {/section}

</tr>
<tr>
   <td>Ave. Words per Open-ended Resp.</td>
   {section name="options" 
            loop=$revision.number_of_options}
      <td>
         <input tabindex="{$smarty.section.options.iteration}31" 
                type="text" 
                name="avg_words_per_open_end_option_{$smarty.section.options.iteration}_country_1" 
                value="{$options[$smarty.section.options.iteration].avg_words_per_open_end}"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
         <input type="hidden"
                name="i_avg_words_per_open_end_option_{$smarty.section.options.iteration}_country_1"
                value="Average Words Per Open End Response Must Be a Number">
         <input type="hidden"
                name="r_avg_words_per_open_end_option_{$smarty.section.options.iteration}_country_1"
                value="Average Words Per Open End Response is Required">
      </td>
   {/section}

</tr>
{/if}
{if $services[$smarty.const.SERVICE_OE_TXT_CODING] eq "1"}
<tr>
   <td>Open-end Text Coding (Hrs.)</td>
   {section name="options" 
            loop=$revision.number_of_options}
      <td>
         <input tabindex="{$smarty.section.options.iteration}32" 
                type="text" 
                name="open_end_text_coding_hours_option_{$smarty.section.options.iteration}_country_1" 
                value="{$options[$smarty.section.options.iteration].open_end_text_coding_hours}"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
         <input type="hidden"
                name="i_open_end_text_coding_hours_option_{$smarty.section.options.iteration}_country_1"
                value="Open End Text Coding Hours Must Be a Number">
         <input type="hidden"
                name="r_open_end_text_coding_hours_option_{$smarty.section.options.iteration}_country_1"
                value="Open End Text Coding Hours is Required">
      </td>
   {/section}
</tr>
{/if}
{if $services[$smarty.const.SERVICE_PANEL_IMPORT] eq "1"}
<tr>
   <td>Panel Import (Hrs.)</td>
   {section name="options" 
            loop=$revision.number_of_options}
      <td>
         <input tabindex="{$smarty.section.options.iteration}33" 
                type="text" 
                name="panel_import_hours_option_{$smarty.section.options.iteration}_country_1" 
                value="{$options[$smarty.section.options.iteration].panel_import_hours}"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, 1);">
         <input type="hidden"
                name="i_panel_import_hours_option_{$smarty.section.options.iteration}_country_1"
                value="Panel Import Hours Must Be a Number">
         <input type="hidden"
                name="r_panel_import_hours_option_{$smarty.section.options.iteration}_country_1"
                value="Panel Import Hours is Required">
      </td>
   {/section}
</tr>
{/if}

{if $revision.panel_details}
{assign var="previous_action" value="display_panel_details"}
{else}
{assign var="previous_action" value="display_add_revision"}
{/if}

<tr class="tab1">
      <td colspan="8" align="center">
      	<input type="button" 
					 onclick="window.location.href = '?e={"action=`$previous_action`&proposal_id=`$revision.proposal_id`&proposal_revision_id=`$revision.proposal_revision_id`"|url_encrypt}';" 
                value="Previous ">
      	&nbsp;&nbsp;
         <input type="button" 
                onclick="check(this.form) && this.form.submit(); " 
                value="Next ">
         &nbsp;&nbsp;
        <input type="button" 
					 onclick="CancelProposal({$revision.proposal_id}, {$revision.proposal_revision_id|default:0});" 
                value="Cancel">
      </td>
</tr>
</table>
<input type="hidden" name="number_of_options" value="{$revision.number_of_options}">
<input type="hidden" name="number_of_countries" value="{$revision.number_of_countries}">
<input type="hidden" name="proposal_revision_id" value="{$revision.proposal_revision_id}">
<input type="hidden" name="proposal_id" value="{$revision.proposal_id}">
<input type="hidden" name="update_options" value="{$meta.update_options}">

</td></tr>
{include file='common/div_footer.tpl'}

</form>

<br>
<br>
<div id='prop_comment_bottom'>
{include file='app/pgen/vw_comment.tpl'}
</div>

{literal}
<script>
		new Draggable('prop_comment_top', {revert:true});
		new Draggable('prop_comment_bottom', {revert:true});
		
		Droppables.add('prop_comment_top',{
		hoverclass: 'dark_border',
	   onDrop: function(element) 
	     { 
	     		$('prop_comment_top').innerHTML = element.innerHTML;
	     		element.innerHTML = '&nbsp';
	     		}});	
	     		
		Droppables.add('prop_comment_bottom',{
		hoverclass: 'dark_border',
	   onDrop: function(element) 
	     { 
	     		$('prop_comment_bottom').innerHTML = element.innerHTML;
	     		element.innerHTML = '&nbsp';
	     		}});	
</script>
{/literal}