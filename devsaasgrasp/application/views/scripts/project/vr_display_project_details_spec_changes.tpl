{include file=common/div_header.tpl div_width='100%' div_name='spec_change_tracking' div_title='Specification Change Tracking'}
<FORM method='post' action='index.php?action=save_spec_change' id='frm_stm_spec' name='frm_stm_spec' enctype="multipart/form-data">
         <TR>
            <TD class='header1'>User</TD>
            <TD class='header1'>Date</TD>
            <TD class='header1'>Notes</TD>
         </TR>
         {foreach from=$project_comments.spec_changes item=comment}
         <tr class="{cycle values="tab,tab1"}">
            <TD>{$comment.created_by_first_name} {$comment.created_by_last_name}</TD>
            <TD nowrap>{$comment.created_date}</TD>
            <TD align="left">
               {if $comment.pjm_comment_file_title neq ""}
                  <a href="?action=get_comment_file&id={$comment.pjm_comment_file_id}">{$comment.pjm_comment_file_title}</a><br><br>
               {/if}
               {$comment.pjm_comment_text|escape|regex_replace:"/\n/":"<br/>"}
            </TD>
         </TR>
         {/foreach}

         <tr class="{cycle values="tab,tab1"}">
            <td>
               <strong>New Specification:</strong>
            </td>
            <td colspan="2">
               <table>
                  <tr><td>Memo: </td><t><TEXTAREA name='pjm_comment_text' rows=5 cols=100></TEXTAREA><input type='hidden' name='r_pjm_comment_text' value='Notes are required before saving'></td></tr>
                  <tr><td>File: </td><td><input type="file" name="file_spec_tracking" style="width:50em;" width=50></td></tr>
                  <tr><td>Title: </td><td><input type="text" name="file_spec_tracking_title" style="width:50em;"></td></tr>
            </td>
         </tr>
<INPUT onclick="check(this.form);" type='button' value="Save Specification Change">
<INPUT type='hidden' value='{$project_info.pjm_id}' name='pjm_id'>
</form>
{include file=common/div_footer.tpl}
