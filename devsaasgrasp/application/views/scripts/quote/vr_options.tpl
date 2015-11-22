{literal}
<style>
	TR {
		FONT-FAMILY: Tahoma, Geneva, Arial, sans-serif; 
		FONT-SIZE: 8pt;
		TEXT-ALIGN: left;
	}
</style>
{/literal}

<TABLE class=groupborder cellSpacing=0 width="70%" align="center">
   <TR>
      <TD class=tab>
<TABLE border='0' width='100%' cellpadding='0' cellspacing='0' style='background-color:white;'>
<TR>
   <TD align='center'>
      <table width='100%' align='center'>
      <tr>
         <td align=left class='header1'>
            <b>Proposal Options</b>
         </TD>
         <TD class=disabled vAlign=top align=right width="75%" rowSpan=2>
            &nbsp;
         </TD>
      </TR>
      <TR>
         <TD class=tab vAlign=bottom align='left'>
            <A onmouseover="status='Show/Hide'; return true;" onmouseout="status=''; return true;" href="javascript:toggleWindow('proposal_search');">
            <IMG id=proposal_search:arrow alt=Show/Hide src="/images/rollminus.gif" border=0></A>
         </TD>
      </TR></TABLE>
   </td>
</tr>
</table>
</td></tr><tr><td>

<DIV id='pgen_step4' style="display: block;">

<form name='searchform' 
      id='searchform' 
      action="?e={"action=save_options&id=`$proposal.proposal_id`"|url_encrypt}" 
      method="POST" 
      enctype="multipart/form-data">

{section name="options" 
         loop=$revision.number_of_options}
         
   {assign var="c_options" 
           value=$options[$smarty.section.options.iteration]}

<table width="100%">

<tr class="header1">
   <td align="left">
      <strong>Option {$smarty.section.options.iteration}</strong>
   </td>
   <td colspan="{$revision.number_of_countries}">&nbsp;</td>
</tr>

<tr>
   <td align="left"><strong>Country Name</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right" width="15%">
         {$c_options[$smarty.section.countries.iteration].country_description}
      </td>
   {/section}
</tr>

{if $revision.proposal_option_type_id eq $smarty.const.PROPOSAL_OPTION_TYPE_SINGLE_SUB}
<tr>
   <td align="left"><strong>Sub Group Name</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].sub_group_description}
      </td>
   {/section}
</tr>
{/if}

<!-- if programming is selected -->
{if $services[$smarty.const.SERVICE_QPROG] eq "1"}
<tr>
   <td align="left"><strong>GMI Provides Programming</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].study_programming_type_description}
      </td>
   {/section}
</tr>
{/if}


{if $services[$smarty.const.SERVICE_CPORTAL] eq "1"}
<tr>
   <td align="left"><strong>Client Portal</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].client_portal_type_description}
         <br>
         Setup Hours {$c_options[$smarty.section.countries.iteration].client_portal_programming_hours}
      </td>
   {/section}
</tr>
{/if}

{if $services[$smarty.const.SERVICE_QTRANS] eq "1"}
<tr>
   <td align="left"><strong>GMI Provides Translation</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].translation}
         &nbsp;&nbsp;&nbsp;
         {$c_options[$smarty.section.countries.iteration].translation_language_code}
      </td>
   {/section}
</tr>
{/if}

{if $services[$smarty.const.SERVICE_LOLAY] eq "1"}
<tr>
   <td align="left"><strong>GMI Provides Language Overlay</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
       <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].overlay}
         &nbsp;
         {$c_options[$smarty.section.countries.iteration].overlay_language_code}
      </td>
   {/section}
</tr>
{/if}


{if $services[$smarty.const.SERVICE_PANEL] eq "1"}
<tr>
   <td align="left"><strong>Sample Source</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].study_datasource_description}
      </td>
   {/section}
</tr>
{/if}


<tr>
   <td align="left"><strong>Incidence rate</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].incidence_rate|number_format:"0"}%
      </td>
   {/section}
</tr>

<tr>
   <td align="left"><strong>Total Completes</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].completes}
      </td>
   {/section}
</tr>

<tr>
   <td align="left"><strong>Total Questions Programmed/Translated</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].questions_programmed}
      </td>
   {/section}
</tr>

<tr>
   <td align="left"><strong>Questions per completed interview</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].questions_per_interview}
      </td>
   {/section}
</tr>

<tr>
   <td align="left"><strong>Questions per screener interview</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].questions_per_screener}
      </td>
   {/section}
</tr>

{if $services[$smarty.const.SERVICE_DATA_RECODING] eq "1"}
<tr>
   <td align="left"><strong>Data Recoding Hours:</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].data_recording_hours}
      </td>
   {/section}
</tr>
{/if}

{if $services[$smarty.const.SERVICE_DATA_TAB] eq "1"}
<tr>
   <td align="left"><strong>Data Tab Hours</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].data_tab_hours}
      </td>
   {/section}
</tr>
{/if}

{if $services[$smarty.const.SERVICE_DATA_IMPORT] eq "1"}
<tr>
   <td align="left"><strong>Data Importing Hours</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].data_import_hours}
      </td>
   {/section}
</tr>
{/if}


{if $services[$smarty.const.SERVICE_DATA_EXPORT] eq "1"}
<tr>
   <td align="left"><strong>Data Exporting Hours</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].data_export_hours}
      </td>
   {/section}
</tr>
{/if}


{if $services[$smarty.const.SERVICE_OE_RESPONSE_TRANS] eq "1"}
<tr>
   <td align="left"><strong>Open-end Text Translation (# of Q's)</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].open_end_questions}
      </td>
   {/section}
</tr>

<tr>
   <td align="left"><strong>Incidence of Open-ended Responses</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].incidence_of_open_end}
      </td>
   {/section}
</tr>

<tr>
   <td align="left"><strong>Ave. Words per Open-ended Resp.</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1'}" align="right">
         {$c_options[$smarty.section.countries.iteration].avg_words_per_open_end}
      </td>
   {/section}
</tr>
{/if}

{if $services[$smarty.const.SERVICE_OE_TXT_CODING] eq "1"}
<tr>
   <td align="left"><strong>Open-end Text Coding (Hrs.)</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1'}" align="right">
         {$c_options[$smarty.section.countries.iteration].open_end_text_coding_hours}
      </td>
   {/section}
</tr>
{/if}


{if $services[$smarty.const.SERVICE_PANEL_IMPORT] eq "1"}
<tr>
   <td align="left"><strong>Panel Import (Hrs.)</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td class="{cycle values='tab,tab1}" align="right">
         {$c_options[$smarty.section.countries.iteration].panel_import_hours}
      </td>
   {/section}
</tr>
{/if}

<tr class="tab1">
      <td colspan="8" align="center">
         &nbsp;
      </td>
</tr>
</table>
{/section}
<input type="hidden" name="number_of_options" value="{$revision.number_of_options}">
<input type="hidden" name="number_of_countries" value="{$revision.number_of_countries}">
<input type="hidden" name="proposal_revision_id" value="{$revision.proposal_revision_id}">
<input type="hidden" name="proposal_id" value="{$revision.proposal_id}">
<input type="hidden" name="update_options" value="{$meta.update_options}">
</form>
</div>
</td>
</tr>
</table>