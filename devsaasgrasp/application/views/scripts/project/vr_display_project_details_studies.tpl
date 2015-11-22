

{include file=common/div_header.tpl div_width='100%' div_name='studies' div_title='Studies'}
<form name="studiesForm" method="post" action="?">
   <input type="hidden" name="pjm_id" value="{$project_info.pjm_id}">
   <input type="hidden" name="action" value="display_project_details">
   <input type='hidden' name='sort_order' value=''>

   {***** List Header *****}
   <tr>
      {if $studies_sort_order eq "ASC"}
         {assign var="rev_sort_order" value="DESC"}
         {assign var="icon" value="sort1.gif"}
      {else}
         {assign var="rev_sort_order" value="ASC"}
         {assign var="icon" value="sort2.gif"}
      {/if}

      {foreach from=$study_headers item=header}
        <TD style='text-align:center;' class='header1'>
         {if $studies_sort_by eq $header.field}
            <A onclick="document.studiesForm.sort_order.value='{$header.field} {$rev_sort_order}'; document.studiesForm.submit();">{$header.title|escape} <img src="/images/{$icon}"></A>
         {else}
             <A onclick="document.studiesForm.sort_order.value='{$header.field} {$studies_sort_order}'; document.studiesForm.submit();">{$header.title|escape}</A>
          {/if}
         </TD>
      {/foreach}
   </tr>


   {if !$project_studies}
      <tr>
         <td align="left" colspan="99">There are no studies associated with this project.</td>
      </tr>
   {/if}
   {foreach from=$project_studies item=study}
   <tr class='{cycle values="tab,tab1"}'>
   	{***** Alert Level *****}
   	<td align="left" nowrap class="{$study.alert_level_description}">
         <img src="/images/alert{$study.alert_level_id}.gif" width="12" height="12"> {$study.alert_level_description}
      </td>

       {***** study ID *****}
   	 <td nowrap style='text-align:right;'>
   	  	  <a class='netmr' href="/forms/gw-netmr-study.php?gotostudy={$study.study_id|escape}" target="_blank" {popup text="View `$study.study_name` in Net-MR"|escape|escape delay="300"}>{$study.study_id}</a>
   	 </td>

   	 {***** study name *****}
   	 <td align="left" nowrap>
   	     {assign var="url" value="action=vw_detail&study_id=`$study.study_id`&time_zone_id=`$o_params.time_zone_id`"}
           {**encryption->Encrypt p1=$url assign="encrypted_url"**}
   	     <a href='/project/tasks/id/{$study.study_id}' {popup text="`$study.study_name`"|escape|escape delay="300"}>{$study.study_name|default:"No Study Name"|truncate:30:"...":true|escape}</a>
   	</td>
   	
   	{***** country *****}
   	<td align="left" nowrap>
   	{$study.country_description|escape}
   	</td>

   	{***** study type *****}
   	<td align="left" nowrap {popup text="`$study.study_type_description`"|escape|escape delay="300"}>
         {$study.study_type_description|truncate:10:""|escape}
      </td>

      {***** Account Manager *****}
      <td align="left" nowrap {popup text="`$study.acct_mgr`"|escape|escape delay="300"}>
         {$study.acct_mgr|regex_replace:"/^(.).*\s([^\s]+)/":"\$1. \$2"|escape}
      </td>

      {***** Task Owner *****}
   	<td align="left" nowrap {popup text="`$study.task_owner`"|escape|escape delay="300"}>
         {$study.task_owner|regex_replace:"/^(.).*\s([^\s]+)/":"\$1. \$2"|escape}
      </td>

      {***** Owner Group *****}
   	<td align="left" nowrap>
   	  <div {popup text="`$study.functional_group_description`"|escape|escape delay="300"}>
   	     {$study.functional_group_abbrev|escape}
   	  </div>
   	</td>


      {***** Status *****}
      <td align="left">
         {$study.study_status_description|escape}
      </td>

      {***** Stage *****}
      <td align="left">
         {$study.study_stage_description|escape}
      </td>

      {***** Start Date *****}
   	<td align="left" nowrap>
         {$study.start_date|escape}
      </td>

      {***** Estimated Complete Date *****}
   	<td align="left" nowrap>
         {$study.current_estimated_complete_date|escape}
      </td>

      {***** Quota Filled *****}
   	<td style='text-align:right;'>
      	{if $study.percent_quota_progress neq ""}
      	  <a href='/app/stm/?action=vw_quota&study_id={$study.study_id}'>{$study.percent_quota_progress}%</a>
      	{/if}
   	</td>

   </tr>
   {/foreach}
</table>
<div class="button_bar">
   <input type="button" value="Add/Delete Studies..." onclick="popup('?action=display_add_remove_elements_form&pjm_id={$project_info.pjm_id}','display_add_remove_elements_form',400,400);" style="width:14em;">
</div>

</form>
{include file=common/div_footer.tpl}