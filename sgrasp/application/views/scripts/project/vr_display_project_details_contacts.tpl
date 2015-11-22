{include file=common/div_header.tpl div_width='100%' div_name='contacts' div_title='Client Contacts'}
   <tr>
      <td nowrap class='header1'>Contact <img src="/images/sort1.gif"></td>
      <td nowrap class='header1'>Type</td>
      <td nowrap class='header1'>Email</td>
      <td nowrap class='header1' width="99%">Phone</td>
   </tr>

   {if !count($project_contacts)}
      <tr><td align="left" colspan=99>There are no client contacts assigned for this project.</td></tr>
   {/if}
   {foreach from=$project_contacts item=contact}
   <tr class='{cycle values="tab,tab1"}'>
   	<td align="left" nowrap>
   	  	  {$contact.first_name|escape} {$contact.last_name|escape}
   	</td>
      <td align="left" nowrap>
         {$contact.pjm_contact_type_description|escape}
    	</td>
      <td align="left" nowrap>
         {$contact.email|escape}
      </td>
      <td align="left" nowrap>
         {$contact.phone|escape}
      </td>
   </tr>
   {/foreach}
   <input type="button" value="Add/Remove Contacts..." onclick="popup('?action=display_add_remove_contacts_form&pjm_id={$project_info.pjm_id}','display_add_remove_contacts_form',650,300);" style="width:15em;">
   <input type="button" value="Sync Email/Phone" onclick="document.location.href='?action=sync_project_contacts_info&pjm_id={$project_info.pjm_id}';" style="width:12em;">
{include file=common/div_footer.tpl}