{include file='common/div_header.tpl'
			div_title='Internal Notes'
			div_name='internal_notes'
			div_width='70%'
			div_align='center'}
			
			            <TR class="header1">
                           <TD>User</TD>
                           <TD>Date</TD>
                           <TD>Status</TD>
                           <TD>Notes</TD>
                        </TR>
                        <tr class="{cycle values="tab,tab1"}">
                           <TD>{$proposal.comments[id].name}</TD>
                           <TD nowrap>{$proposal.comments[id].proposal_comment_date|date_format:"%Y-%m-%d %H:%M"}</TD>
                           <TD nowrap>{$proposal.comments[id].proposal_status_description}</TD>
                           <TD align="left">{$proposal.comments[id].proposal_comment_text|nl2br}</TD>
                        </TR>
                            <TR>
	                           <TD colspan='3'>Notes</TD>
	                           <TD>
	                              <TEXTAREA name='proposal_comment_text' rows=5 cols=100 id='proposal_comment_text'></TEXTAREA>
	                              <input type='hidden' name='r_proposal_comment_text' value='Notes are required to change status' />
	                            </TD>
	                        </TR>
	                  		<tr>
	                  			<td colspan="4">
				                     <INPUT onclick="check(this.form) && AjaxSaveComment({$proposal.proposal_id});" type='button' value="Save">
				                     <input type="hidden" id='auto_submit' name="auto_submit" value="0">
				                     &nbsp;&nbsp;
				                     <INPUT onclick="this.form.elements.proposal_comment_text.value = '';" type='button' value="Clear">
	                 				</td>
									</tr>
{include file='common/div_footer.tpl'}
