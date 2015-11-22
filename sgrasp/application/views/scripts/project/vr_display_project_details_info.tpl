{include file=common/div_header.tpl div_width='100%' div_name='project_info' div_title='Project Info'}
   <tr class="{cycle values='tab,tab1'}">
      <td align="right" nowrap width="20%"><strong>Name:</strong></td>
      <td align="left" nowrap width="30%">{$project_info.pjm_id|escape} - {$project_info.pjm_description|escape}</td>
      <td align="right" nowrap width="20%"><strong>Account:</strong></td>
      <td align="left" nowrap>
         {$project_attributes.ACCOUNT_ID|escape} -
         <a href='/app/acm/?action=display_account_detail&account_id={$project_attributes.ACCOUNT_ID}'>{$project_attributes.ACCOUNT_NAME|default:"No Account Name"|escape}</a>
      </td>
   </tr>
   <tr class="{cycle values='tab,tab1'}">
      <td align="right" nowrap><strong>Period:</strong></td>
      <td align="left" nowrap>
         {if $project_info.pjm_start_timestamp}From {$project_info.pjm_start_date|escape}{/if}
         {if $project_info.pjm_end_timestamp}Until {$project_info.pjm_end_date|escape}{/if}
         {if !$project_info.pjm_start_timestamp && !$project_info.pjm_end_timestamp}Unspecificed{/if}
      </td>
      <td align="right" nowrap><strong>Status:</strong></td>
      <td align="left" nowrap>
         {$project_info.pjm_status_description|escape}</td>
      </td>
   </tr>
   <tr class="{cycle values='tab,tab1'}">
      <td align="right" nowrap><strong>Sample Source(s):</strong></td>
      <td align="left">{$project_info.data_sources}</td>
      <td align="right" nowrap><strong>Sample Type(s)</td>
      <td align="left">{$project_info.sample_types}</td>
   </tr>
   <tr class="{cycle values='tab,tab1'}">
      <td align="right" nowrap><strong>Software Platform(s):</strong></td>
      <td align="left">{$project_info.products}</td>
      <td align="right" nowrap><strong>User Roles:</td>
      <td align="left" nowrap>
         <table cellspacing=0>
         {foreach from=$project_user_roles item=user}
         <tr><td nowrap>{$user.role_description|escape} -&nbsp;</td><td nowrap>{$user.first_name|escape} {$user.last_name|escape}</td></tr>
         {/foreach}
         </table>
      </td>
   </tr>
   <tr class="{cycle values='tab,tab1'}">
      <td align="right" nowrap><strong>Satisfaction Survey:</strong></td>
      <td align="left" colspan="3">

         	     {$project_attributes.SAT_SURVEY_STATUS|default:"Not Sent"|escape}
            	  {if $project_attributes.SAT_SURVEY_STATUS == 'SENT'}
            	     to {$project_attributes.SAT_SURVEY_CONTACT_NAME}
            	     &lt;<a href="mailto:{$project_attributes.SAT_SURVEY_CONTACT_EMAIL}">{$project_attributes.SAT_SURVEY_CONTACT_EMAIL}</a>&gt;
            	     on {$project_attributes.SAT_SURVEY_SENT_DATE|escape}
         	     {elseif $project_attributes.SAT_SURVEY_STATUS}
         	        : {$project_attributes.SAT_SURVEY_ERROR_MESSAGE|escape}
         	        <br>
         	        Last send attempt was on {$project_attributes.SAT_SURVEY_ERROR_DATE|escape}
            	  {/if}
      </td>
   </tr>
   <tr class="tab">
      <td align="center" colspan="4">
         <input type="button" value="Edit Info..." onclick="popup('?action=display_edit_project_info_form&pjm_id={$project_info.pjm_id}','display_edit_project_info',700,400);" style="width:8em;">
         <input type="button" value="Refresh" onclick="document.location.href='?action=sync_project_info_from_netmr&pjm_id={$project_info.pjm_id}';" style="width:9em;" {popup text="Update account name (from Net-MR) and user roles information (from Account Manager)."|escape|escape delay="300"} >
         {if $project_info.pjm_status_id neq 2}
            <input type="button" value="Close Project" onclick="document.location.href='?action=close_project&pjm_id={$project_info.pjm_id}';" {popup text="Close the project"|escape delay="300"}>
         {else}
            <input type="button" value="Reopen Project" onclick="document.location.href='?action=reopen_project&pjm_id={$project_info.pjm_id}';" {popup text="Reopen the project"|escape delay="300"}>
         {/if}
      </td>
   </tr>
{include file=common/div_footer.tpl}