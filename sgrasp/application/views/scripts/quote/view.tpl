<script src='/js/validate.js'></script>
<script src='/js/ppm.js'></script>
{popup_init src="/js/overlib/overlib.js"}
<TABLE width="100%">
<TR><TD>

{if $meta.message neq ""}
<div align="center">
   <font color="Red"><strong>{$meta.message}</strong></font>
</div>
{/if}

{include file='quote/summary.tpl'}

</td></tr>
<tr align="center">
	<td align="center">

		<table align="center">
			<tr>
				<td>
					<input type="button"
							 value="Lost"
							 onclick="document.location.href = '?e={"action=display_proposal_sub_status&proposal_id=`$proposal.proposal_id`&proposal_status_id=`$smarty.const.PROPOSAL_STATUS_LOST`"|url_encrypt}';">
					&nbsp;&nbsp;
					<input type="button"
							 value="Cancelled"
							 onclick="document.location.href = '?e={"action=display_proposal_sub_status&proposal_id=`$proposal.proposal_id`&proposal_status_id=`$smarty.const.PROPOSAL_STATUS_CANCELLED`"|url_encrypt}';">
					&nbsp;&nbsp;
					<input type="button"
							 value="Postponed"
							 onclick="document.location.href = '?e={"action=update_proposal_status&proposal_id=`$proposal.proposal_id`&proposal_status_id=`$smarty.const.PROPOSAL_STATUS_POSTPONED`"|url_encrypt}';">
					{if $smarty.session.PPM_ACTION_DELETE_PROPOSAL eq "1"}
					&nbsp;&nbsp;
					<input type="button"
							 value="Delete"
							 onclick="document.location.href = '?e={"action=delete_proposal&proposal_id=`$proposal.proposal_id`"|url_encrypt}';">
					{/if}
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr><td>

{include file='common/div_header.tpl'
			div_title='Revision History'
			div_name='revision_history'
			div_width='70%'
			div_align='center'}
			
   <tr class="header1">
      <td>Revision Number</td>
      <td>Proposal Writer</td>
      <td>Min Proposal Value</td>
      <td>Max Proposal Value</td>
      <td>Status</td>
      <td>Date Created</td>
      <td>Date Sent</td>
      <td>Document</td>
   </tr>
   {section name=id loop=$revisions}
      <tr class="{cycle values='tab,tab1'}">
         <td>
            <a href="/quote/viewrev/rev/{$revisions[id].proposal_revision_id}">
            {$revisions[id].revision}
            </a>
          </td>
         <td><a href="mailto:{$revisions[id].p_writer_email}">{$revisions[id].p_writer_name}</a></td>
         <td align="right">${$revisions[id].min_amount|number_format:2:".":","}</td>
         <td align="right">${$revisions[id].max_amount|number_format:2:".":","}</td>
         <td align="center">{$revisions[id].proposal_revision_status_description}</td>
         <td align="center">{$revisions[id].created_date|date_format:"%Y-%m-%d"}</td>
         <td align="center">{$revisions[id].sent_date|date_format:"%Y-%m-%d"}</td>
         <td>
         	<a href='?e={"action=download_proposal_document&proposal_id=`$revisions[id].proposal_id`&proposal_revision_id=`$revisions[id].proposal_revision_id`"|url_encrypt}'>Download</a>
         	{if $smarty.const.ISDEV eq "1"}
         	<a href='?e={"action=download_proposal_document_v2&proposal_id=`$revisions[id].proposal_id`&proposal_revision_id=`$revisions[id].proposal_revision_id`"|url_encrypt}'>(Beta - PDF) DevOnly</a>
         	<a href='?e={"action=download_proposal_document_v2&proposal_id=`$revisions[id].proposal_id`&proposal_revision_id=`$revisions[id].proposal_revision_id`&htmlonly=1"|url_encrypt}'>(Beta -Html)  DevOnly</a>
         	{/if}
         </td>
      </tr>
      {if $revisions[id].proposal_revision_text neq ""}
      <tr style="border: 1">
         <td colspan="4" style="border: 1">
            {$revisions[id].proposal_revision_text}
         </td>
      </tr>
      {/if}
   {/section}

{include file='common/div_footer.tpl}
   
</td></tr>
<tr><td> &nbsp;&nbsp;&nbsp; </td></tr>
<tr><td>

{include file='quote/vw_comment.tpl'}

</td>
</tr>
</table>


<script language="javascript">

</script>