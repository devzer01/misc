<form name='searchform' 
      id='searchform' 
      action="?e={"action=save_action&proposal_revision_id=`$revision.proposal_revision_id`&proposal_id=`$revision.proposal_id`"|url_encrypt}" 
      method="POST" 
      enctype="multipart/form-data">

{include file='common/div_header.tpl'
			div_title='Actions'
			div_name='p_actions'
			div_width='70%'
			div_align='center'}
			
<tr><td>

<table align="center" width="100%">
   <tr class="header1">
      <td><strong>Name</strong></td>
      <td><strong>Date</strong></td>
      <td><strong>Group</strong></td>
      <td><strong>Status</strong></td>
      <td><strong>Comment</strong></td>
   </tr>
   {section name=ap loop=$approval}
      <tr class="{cycle values='tab,tab1'}">
         <td>{$approval[ap].first_name} {$approval[ap].last_name}</td>
         <td>{$approval[ap].action_date}</td>
         <td>{$approval[ap].proposal_review_group_description}</td>
         <td>{$approval[ap].proposal_action_description}</td>
         <td>{$approval[ap].action_comment|nl2br}</td>
      </tr>
   {/section}
</table>

</td></tr>

{if $meta.show_approval eq "1"}
	<tr><td>
		<table align="center">
			<tr>
			   <td colspan="2">Since you are on the review list for this proposal, You may take action on this proposal.</td>
			</tr>
			<tr>
			<td><strong>Action </strong> </td>
			<td><select name='proposal_action_id'>
			   {html_options options=$list.proposal_action}
			</select>
			</td>   
			</tr>
			<tr>
			   <td><strong>Comment</strong></td>
			   <td><textarea cols="30" rows="5" name="action_comment"></textarea></td>
			</tr>
			<tr>
			   <td colspan="2">
			      <input type="button" value="Update" onclick="this.form.submit();">
			   </td>
			</tr>
		</table>
		</td>
		</tr>
	<input type="hidden" name="proposal_review_group_id" value="{$meta.proposal_review_group_id}">
{/if}

{include file='common/div_footer.tpl'}

</form>

