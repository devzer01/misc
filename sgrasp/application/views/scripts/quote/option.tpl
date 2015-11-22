<html>
<head>
<link rel="STYLESHEET" type="text/css" href="/dhtmlx.css">
<script src="/dhtmlx.js" type="text/javascript"></script>
<script src='/js/validate.js'></script>
<script src='/js/pgen.js'></script>
</head>
<body>
{include file='quote/summary.tpl}

{literal}
<style>
	TR {
		FONT-FAMILY: Tahoma, Geneva, Arial, sans-serif; 
		FONT-SIZE: 8pt;
		TEXT-ALIGN: left;
	}
</style>
{/literal}

<form name='searchform' 
      id='searchform' 
      action="/quote/saveoption/rev/{$revision.proposal_revision_id}" 
      method="POST" 
      enctype="multipart/form-data">

{include file='common/div_header.tpl'
			div_title='Options'
			div_name='p_options'
			div_width='70%'
			div_align='center'}

<tr><td>
<div id="a_tabbar" class="dhtmlxTabBar" imgpath="/imgs/" style="width:1000px; height:500px;"  skinColors="#FCFBFC,#F4F3EE" >
{section name="options" 
         loop=$revision.number_of_options}

{assign var="c_options" value=$options[$smarty.section.options.iteration]}
<div id='option_{$smarty.section.options.iteration}' Name='Option {$smarty.section.options.iteration}'>
<table width="100%">

<tr class="header1">
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>&nbsp;
         <!-- <input type="button" 
                value="Copy Countries" 
                onclick="copy_countries({$smarty.section.countries.iteration},{$smarty.section.options.iteration})">
         <br>
         <input type="button" 
                value="Copy Options" 
                onclick="copy_options({$smarty.section.options.iteration}, {$smarty.section.countries.iteration})">
         <br>
         <input type="checkbox"
                name="copy_selected"
                id="copy_selected"> Copy Selected -->
      </td>
   {/section}
</tr>

<tr class="tab">
   <td>Country Name</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         <select name="country_name_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                 tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}10"
                 onchange="copy_option(this, {$smarty.section.options.iteration}, {$smarty.section.countries.iteration});"
                 onkeyup="copy_option(this, {$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
         	<option value=""></option>
            {html_options options=$list.country 
                          selected=$c_options[$smarty.section.countries.iteration].country_code}
         </select>
         <input type="hidden" name="r_country_name_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" id="r_country_name_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" value="Please select Country {$smarty.section.countries.iteration} for Option {$smarty.section.options.iteration}">
         <input type="hidden" 
                name="proposal_revision_option_id_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                value="{$c_options[$smarty.section.countries.iteration].proposal_revision_option_id}">
         <input type="hidden" 
                name="sort_order_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                value="{$smarty.section.countries.iteration}">
         <input type="hidden"
                name="option_number_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="{$smarty.section.options.iteration}">
      </td>
   {/section}
</tr>

{if $proposal.proposal_option_type_id eq $smarty.const.PROPOSAL_OPTION_TYPE_SINGLE_SUB}
<tr class="tab1">
   <td>Sub Group Name</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         <input value="{$c_options[$smarty.section.countries.iteration].sub_group_description}" 
                type="text" 
                name="sub_group_description_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                size="30"
                tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}11">
         <input type="hidden" 
                name="r_sub_group_description_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Sub Group Name is Required">
      </td>
   {/section}
</tr>
{/if}


<!-- if programming is selected -->
{if $services[$smarty.const.SERVICE_QPROG] eq "1"}
<tr class="tab">
   <td>GMI Provides Programming</td>
   {section name="countries"
            loop=$revision.number_of_countries}
      <td>
         <select name="study_programming_type_id_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                 tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}12"
                 onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
            {html_options options=$list.study_programming_type 
                          selected=$c_options[$smarty.section.countries.iteration].study_programming_type_id}
         </select>
      </td>
   {/section}
</tr>
{/if}


{if $services[$smarty.const.SERVICE_RPORTAL] eq "1"}
<tr class="tab1">
   <td>Respondent Portal </td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         <select name="respondent_portal_type_id_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                 onchange="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});"
                 onclick="display_portal_hours(this, {$smarty.section.options.iteration}, {$smarty.section.countries.iteration} );"
                 tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}13"
                 onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
            {html_options options=$list.respondent_portal_type 
                          selected=$c_options[$smarty.section.countries.iteration].respondent_portal_type_id}
         </select>
         &nbsp;&nbsp;&nbsp;
         <div id='dv_portal_hours_{$smarty.section.options.iteration}_{$smarty.section.countries.iteration}' style="display: none;">
            Programming Hours
            <input size="3" 
                   type="text" 
                   name="respondent_portal_programming_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                   value="{$c_options[$smarty.section.countries.iteration].respondent_portal_programming_hours}"
                   tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}14">
            <input type="hidden"
                   name="i_respondent_portal_programming_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                   value="Respondent Portal Programming Hours Must Be A Number">
         </div>
      </td>
   {/section}
</tr>
{/if}

{if $services[$smarty.const.SERVICE_CPORTAL] eq "1"}
<tr class="tab1">
   <td>Client Portal </td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         &nbsp;&nbsp;&nbsp;
            Programming Hours
            <input size="3" 
                   type="text" 
                   name="client_portal_programming_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                   value="{$c_options[$smarty.section.countries.iteration].client_portal_programming_hours}"
                   tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}14">
            <input type="hidden"
                   name="i_client_portal_programming_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                   value="Portal Programming Hours Must Be A Number">
      </td>
   {/section}
</tr>
{/if}



{if $services[$smarty.const.SERVICE_QTRANS] eq "1"}
<tr class="tab">
   <td>GMI Provides Translation</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         <select name="translation_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                 onchange="display_translation_language_list(this, {$smarty.section.options.iteration}, {$smarty.section.countries.iteration});"
                 tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}15"
                 onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
            {html_options options=$list.yes_no 
                          selected=$c_options[$smarty.section.countries.iteration].translation}
         </select>
         &nbsp;&nbsp;&nbsp;
         <div id='dv_translation_language_{$smarty.section.options.iteration}_{$smarty.section.countries.iteration}'
              style="display: none;">
            Select Language
            <select name="translation_language_code_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                    tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}16">
               {html_options options=$list.language 
                             selected=$c_options[$smarty.section.countries.iteration].translation_language_code}
            </select>
         </div>
      </td>
   {/section}
</tr>
{/if}


{if $services[$smarty.const.SERVICE_LOLAY] eq "1"}
<tr class="tab1">
   <td>GMI Provides Language Overlay</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         <select name="overlay_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                 tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}17">
            {html_options options=$list.yes_no 
                          selected=$c_options[$smarty.section.countries.iteration].overlay}
         </select>
         &nbsp;&nbsp;&nbsp;
        <!-- <div id='dv_overlay_language_{$smarty.section.options.iteration}_{$smarty.section.countries.iteration}'
            style="display: none;">
            Select Language
            <select name="overlay_language_code_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                    tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}18">
               {html_options options=$list.language 
                             selected=$c_options[$smarty.section.countries.iteration].overlay_language_code}
            </select>
         </div>-->
      </td>
   {/section}
</tr>
{/if}


{if $services[$smarty.const.SERVICE_PANEL] eq "1"}
<tr>
   <td>Sample Source</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         <select name="study_datasource_id_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                 tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}19"
                 onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
            {html_options options=$list.sample_source 
                          selected=$c_options[$smarty.section.countries.iteration].study_datasource_id}
         </select>
      </td>
   {/section}
</tr>
{/if}


<tr>
   <td>Incidence rate</td>
      {section name="countries" 
               loop=$revision.number_of_countries}
         <td>
            <input type="text" 
                   name="incidence_rate_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                   value="{$c_options[$smarty.section.countries.iteration].incidence_rate}"
                   tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}20"
                   onkeyup="copy_value(this, {$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
            <input type="hidden"
                   name="i_incidence_rate_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                   value="Incidence Must be a Number">
            <input type="hidden"
                   name="r_incidence_rate_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                   value="Incidence Rate is Required">
            <input type="hidden"
                   name="p_incidence_rate_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                   value="Incidence Rate Must be Between 1 and 100">
         </td>
      {/section}
</tr>


<tr>
   <td>Total Completes</td>
      {section name="countries" 
               loop=$revision.number_of_countries}
      <td>
         <input type="text" 
                name="completes_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                value="{$c_options[$smarty.section.countries.iteration].completes}"
                tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}21"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
         <input type="hidden"
                name="i_completes_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Total Completes Must Be A Number">
         <input type="hidden"
                name="r_completes_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Total Completes is Required">
      </td>
   {/section}
</tr>


<tr>
   <td>Total Questions Programmed/Translated</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         <input type="text" 
                name="questions_programmed_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                value="{$c_options[$smarty.section.countries.iteration].questions_programmed}"
                tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}22"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
         <input type="hidden" 
                name="i_questions_programmed_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Total Questions Programmed Must Be a Number">
         <input type="hidden" 
                name="r_questions_programmed_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Total Questions Programmed is Required">
      </td>
   {/section}
</tr>


<tr>
   <td>Questions per completed interview</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         <input type="text" 
                name="questions_per_interview_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                value="{$c_options[$smarty.section.countries.iteration].questions_per_interview}"
                tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}23"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
         <input type="hidden"
                name="i_questions_per_interview_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Question Per Completed Interview Must Be a Number">
         <input type="hidden"
                name="r_questions_per_interview_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Question Per Completed Interview is Required">
      </td>
   {/section}
</tr>


<tr>
   <td>Questions per screener interview</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         <input type="text" 
                name="questions_per_screener_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                value="{$c_options[$smarty.section.countries.iteration].questions_per_screener}"
                tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}24"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
         <input type="hidden"
                name="i_questions_per_screener_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Question Per Screener Interview Must Be a Number">
         <input type="hidden"
                name="r_questions_per_screener_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Question Per Screener Interview is Required">
      </td>
   {/section}

</tr>


{if $services[$smarty.const.SERVICE_DATA_RECODING] eq "1"}
<tr>
   <td>Data Recoding Hours:</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         <input type="text" 
                name="data_recording_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                value="{$c_options[$smarty.section.countries.iteration].data_recording_hours}"
                tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}25"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
         <input type="hidden"
                name="i_data_recording_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Data Recording Hours Must Be a Number">
         <input type="hidden"
                name="r_data_recording_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Data Recording Hours is Required">
      </td>
   {/section}
</tr>
{/if}

{if $services[$smarty.const.SERVICE_DATA_TAB] eq "1"}
<tr>
   <td>Data Tab Hours</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         <input type="text" 
                name="data_tab_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                value="{$c_options[$smarty.section.countries.iteration].data_tab_hours}"
                tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}26"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
         <input type="hidden"
                name="i_data_tab_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Data Tab Hours Must Be a Number">
         <input type="hidden"
                name="r_data_tab_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Data Tab Hours is Required">
      </td>
   {/section}
</tr>
{/if}


{if $services[$smarty.const.SERVICE_DATA_IMPORT] eq "1"}
<tr>
   <td>Data Importing Hours</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         <input type="text" 
                name="data_import_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                value="{$c_options[$smarty.section.countries.iteration].data_import_hours}"
                tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}27"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
         <input type="hidden"
                name="i_data_import_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Data Import Hours Must Be a Number">
         <input type="hidden"
                name="r_data_import_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Data Import Hours is Required">
      </td>
   {/section}
</tr>
{/if}


{if $services[$smarty.const.SERVICE_DATA_EXPORT] eq "1"}
<tr>
   <td>Data Exporting Hours</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         <input type="text" 
                name="data_export_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                value="{$c_options[$smarty.section.countries.iteration].data_export_hours}"
                tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}28"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
         <input type="hidden"
                name="i_data_export_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Data Export Hours Must Be a Number">
         <input type="hidden"
                name="r_data_export_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Data Export Hours is Required">
      </td>
   {/section}
</tr>
{/if}


{if $services[$smarty.const.SERVICE_OE_RESPONSE_TRANS] eq "1"}
<tr>
   <td>Open-end Text Translation (# of Q's)</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         <input type="text" 
                name="open_end_questions_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                value="{$c_options[$smarty.section.countries.iteration].open_end_questions}"
                tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}29"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
         <input type="hidden"
                name="i_open_end_questions_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Open End Questions Must Be a Number">
         <input type="hidden"
                name="r_open_end_questions_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Open End Questions is Required">
      </td>
   {/section}
</tr>

<tr>
   <td>Incidence of Open-ended Responses</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         <input type="text" 
                name="incidence_of_open_end_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                value="{$c_options[$smarty.section.countries.iteration].incidence_of_open_end}"
                tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}30"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
         <input type="hidden"
                name="i_incidence_of_open_end_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Incidence of Open End Must Be a Number">
         <input type="hidden"
                name="r_incidence_of_open_end_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Incidence of Open End is Required">
      </td>
   {/section}
</tr>
<tr>
   <td>Ave. Words per Open-ended Resp.</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td>
         <input type="text" 
                name="avg_words_per_open_end_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                value="{$c_options[$smarty.section.countries.iteration].avg_words_per_open_end}"
                tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}31"
                onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
         <input type="hidden"
                name="i_avg_words_per_open_end_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Average Words Per Open End Response Must Be a Number">
         <input type="hidden"
                name="r_avg_words_per_open_end_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                value="Average Words Per Open End Response is Required">
      </td>
   {/section}
</tr>
{/if}

{if $services[$smarty.const.SERVICE_OE_TXT_CODING] eq "1"}
<tr>
   <td>Open-end Text Coding (Hrs.)</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
         <td>
            <input type="text" 
                   name="open_end_text_coding_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                   value="{$c_options[$smarty.section.countries.iteration].open_end_text_coding_hours}"
                   tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}32"
                   onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
            <input type="hidden"
                   name="i_open_end_text_coding_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                   value="Open End Text Coding Hours Must Be a Number">
            <input type="hidden"
                   name="r_open_end_text_coding_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                   value="Open End Text Coding Hours is Required">
         </td>
      {/section}
</tr>
{/if}

{if $services[$smarty.const.SERVICE_PANEL_IMPORT] eq "1"}
<tr>
   <td>Panel Import (Hrs.)</td>
   {section name="countries" 
            loop=$revision.number_of_countries}
         <td>
            <input type="text" 
                   name="panel_import_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}" 
                   value="{$c_options[$smarty.section.countries.iteration].panel_import_hours}"
                   tabindex="{$smarty.section.options.iteration}{$smarty.section.countries.iteration}33"
                   onkeyup="copy_value(this,{$smarty.section.options.iteration}, {$smarty.section.countries.iteration});">
            <input type="hidden"
                   name="i_panel_import_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
                   value="Panel Import Hours Must Be a Number">
            <input type="hidden"
                   name="r_panel_import_hours_option_{$smarty.section.options.iteration}_country_{$smarty.section.countries.iteration}"
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
                onclick="check(this.form) && this.form.submit();" 
                value="Next ">
         &nbsp;&nbsp;
         <input type="button" 
					 onclick="CancelProposal({$revision.proposal_id}, {$revision.proposal_revision_id|default:0});" 
                value="Cancel">
      </td>
</tr>
</table>
</div>
{/section}
</tr></td>
</div>
{include file='common/div_footer.tpl'}
<input type="hidden" name="number_of_options" value="{$revision.number_of_options}">
<input type="hidden" name="number_of_countries" value="{$revision.number_of_countries}">
<input type="hidden" name="proposal_revision_id" value="{$revision.proposal_revision_id}">
<input type="hidden" name="proposal_id" value="{$revision.proposal_id}">
<input type="hidden" name="update_options" value="{$meta.update_options}">
</form>
<br>
<br>
<div id='prop_comment_bottom'>
{include file='quote/vw_comment.tpl'}
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
</body></html>