{include file=common/div_header.tpl div_width='100%' div_name='alerts' div_title='Project Notes'}
   {***** List Header *****}
   <tr>
      <td nowrap class='header1'>Date <img src="/images/sort1.gif"></td>
      <td nowrap class='header1'>User</td>
      <td nowrap class='header1'>Alert Level</td>
      <td nowrap class='header1' width="99%">Notes</td>
   </tr>

   {if !count($project_comments.alert_changes)}
      <tr><td align="left" colspan=99>There are no comments</td></tr>
   {/if}
   {foreach from=$project_comments.alert_changes item=comment}
   <tr class='{cycle values="tab,tab1"}'>
   	<td align="left" nowrap>
   	  	  {$comment.created_timestamp|date_format:"%Y-%m-%d %H:%M"|escape}
   	</td>
      <td align="left" nowrap {popup text="`$comment.created_by_first_name` `$comment.created_by_last_name`"|escape|escape delay="300"}>
         {$comment.created_by_first_name|regex_replace:"/^(.).*$/":"\$1"|escape}. {$comment.created_by_last_name|escape}
    	</td>
    	<td align="left" nowrap class="{$comment.alert_level_description|escape}">
    	    <img src="/images/alert{$comment.alert_level_id}.gif" width="12" height="12">
         {$comment.alert_level_description|escape}
      </td>
      <td align="left">
         {$comment.pjm_comment_text|escape|regex_replace:"/\n/":"<br/>"}
      </td>
   </tr>
   {/foreach}
   <input type="button" value="Add Note..." onclick="popup('?action=display_new_note_form&pjm_id={$project_info.pjm_id}','display_new_note_form',650,300);" style="width:8em;">
{include file=common/div_footer.tpl}