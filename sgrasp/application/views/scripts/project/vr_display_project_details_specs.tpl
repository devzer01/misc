<div class='validation' name='dv_valid' id='dv_valid' style='display:none;' align='center'></div>
<FORM method=post action='index.php?action=do_attr' name='frm_attr' id="frm_attr" enctype="multipart/form-data">
   <INPUT type="hidden" value="{$project_info.pjm_id}" name='pjm_id'>
   <input type="hidden" name="file_upload" value="">
   <input type="hidden" name="SPECLOCK" id="spec_lock" value="{$project_attributes.SPECLOCK}">

{include file=common/div_header.tpl div_width='100%' div_name='contacts' div_title='Project Specification'}
<TABLE width="100%" align=center>
   <TR class="{cycle values="tab,tab1"}">
       <TD align=left>
         <B>GMI Provides Programming</B>
       </TD>
       <TD align=left>
         <INPUT type='checkbox' name='GMI_PROG' {if $project_attributes.GMI_PROG eq "on"}checked{/if}>
       </TD>
       <TD align=left>
         <B>GMI Provides Translation</B>
       </TD>
       <TD align=left>
         <INPUT type='checkbox' name='GMI_TRANS' {if $project_attributes.GMI_TRANS eq "on"}checked{/if}>
       </TD>
   </TR>

   <TR class="{cycle values="tab,tab1"}">
       <TD align=left>
         <B>Client Provides Translation</B>
       </TD>
       <TD align=left>
         <INPUT type='checkbox' name='CLI_TRANS' {if $project_attributes.CLI_TRANS eq "on"}checked{/if}>
       </TD>
       <TD align=left>
         <B>Translation House</B>
       </TD>
       <TD align=left>
         <select name="TRANS_VEND">
            {html_options options=$vendors.translation selected=$project_attributes.TRANS_VEND}
         </select>
       </TD>
   </TR>

   <TR class="{cycle values="tab,tab1"}">
       <TD  align=left>
         <B>Total Questions Programmed</B>
       </TD>
       <TD  align=left>
         <INPUT type='text' name='TOTALQP' size='10' value='{$project_attributes.TOTALQP}'>
         <input type='hidden' name='i_TOTALQP' value='Total Questions Programmed Must Be A Number'>
       </TD>
         <TD  align=left>
            <B>GMI Provides Language Overlay</B>
         </TD>
         <TD  align=left>
            <INPUT type='checkbox' name='GMI_LANG' {if $project_attributes.GMI_LANG eq "on"}checked{/if}>
         </TD>
   </TR>

   <TR class="{cycle values="tab,tab1"}">
       <TD align=left>
         <B>Proposed Incidence Rate</B>
       </TD>
       <TD align=left>
         <strong>Min</strong>&nbsp;<INPUT size="5" name='MIN_INCIDENCE' type='text' value='{$project_attributes.MIN_INCIDENCE}'>&nbsp;&nbsp;&nbsp;
         <input type='hidden' name='i_MIN_INCIDENCE' value='Minimum Incidence Rate Must Be A Number'>
         <strong>Max</strong>&nbsp;<INPUT size="5" name='MAX_INCIDENCE' type='text' value='{$project_attributes.MAX_INCIDENCE}'>
         <input type='hidden' name='i_MAX_INCIDENCE' value='Maxmimum Incidence Rate Must Be A Number'>
         <!--<INPUT size="10" name='INCIDENT' type='text' value='{$INCIDENT}'>-->
         <!--<input type='hidden' name='i_INCIDENT' value='Incidence Rate Must Be A Number'>-->
       </TD>
       <TD align=left>
         <B>Number of Languages</B>
       </TD>
       <TD align=left>
         <INPUT size="10" name='NLANG' type='text' value='{$project_attributes.NLANG}'>
         <input type='hidden' name='i_NLANG' value='Number of Languages Must Be A Number'>
       </TD>
   </TR>

   <TR class="{cycle values="tab,tab1"}">
       <TD  align=left>
         <B>Number of Completes</B>
       </TD>
       <TD  align=left>
         <INPUT size="10" name='N_COMPLETE' type='text' value='{$project_attributes.N_COMPLETE}'>
         <input type='hidden' name='i_N_COMPLETE' value='Number of Completes Must Be A Number'>
         <input type='hidden' name='DR_N_COMPLETE' value='Number of Complete(s) is Required'>
       </TD>
       <TD  align=left>
         <B>Data Tab Hours</B>
       </TD>
       <TD  align=left>
         <INPUT size="10" type='text' name='TABHR' value='{$project_attributes.TABHR}'>
         <input type='hidden' name='i_TABHR' value='Data Tab Hours Must Be A Number'>
       </TD>
   </TR>

   <TR class="{cycle values="tab,tab1"}">
       <TD align=left>
         <B>Number of Outside Sample Supplier</B>
       </TD>
       <TD align=left>
         <INPUT size="10" name='NSUPPLY' type='text' value='{$project_attributes.NSUPPLY}'>
         <input type='hidden' name='i_NSUPPLY' value='Number of Sample Suppliers Must Be A Number'>
       </TD>
       <TD align=left>
         <B>Data Delivery Hours</B>
       </TD>
       <TD align=left>
         <INPUT size="10" name='DDHOUR' type='text' value='{$project_attributes.DDHOUR}'>
         <input type='hidden' name='i_DDHOUR' value='Data Delivery Hours Must Be A Number'>
       </TD>
   </TR>

   <TR class="{cycle values="tab,tab1"}">
       <TD  align=left>
         <B>Tracker Project</B>
       </TD>
       <TD  align=left>
         <input type='checkbox' name='ISTRACKER' {if $project_attributes.ISTRACKER eq "on"}checked{/if}>
       </TD>
       <TD  align=left>
         <B>Client Maintenance</B>
       </TD>
       <TD  align=left>
         <INPUT size="10" name='CLTMAINT' type='text' value='{$project_attributes.CLTMAINT}'>
            <input type='hidden' name='i_CLTMAINT' value='Client Maintainence Must Be A Number'>
       </TD>
   </TR>

   <TR class="{cycle values="tab,tab1"}">
       <TD align=left>
         <B>Project Estimated Value</B>
       </TD>
       <TD align=left>
         <INPUT size="10" name='PVALUE' type='text' value='{$project_attributes.PVALUE}' onChange='format_currency(this);'>
            <input type='hidden' name='m_PVALUE' value='Project Estimated Value Must Be Currency'>
       </TD>
       <TD align=left>
         <B> Retainer Amount Received</B>
       </TD>
       <TD align=left>
       {$project_attributes.RETAINER|string_format:"$%0.2f"}<!--
         <INPUT size="10" name='RETAINER' type='text' value='{$RETAINER}' onChange='format_currency(this);'>
         <input type='hidden' name='m_RETAINER' value='Retainer Amount Must Be Currency'>-->
       </TD>
   </TR>

   <TR class="{cycle values="tab,tab1"}">
       <TD  align=left>
         <B>Project Quoted Value</B>
       </TD>
       <TD  align=left>
         <INPUT size="10" name='QVALUE' type='text' value='{$project_attributes.QVALUE}' onChange='format_currency(this);'>
            <input type='hidden' name='m_QVALUE' value='Project Quoted Value Must Be Currency'>
            <input type='hidden' name='DO_QVALUE_OR_PVALUE' value='Project Quoted Value or Project Estimated Value is Required'>
       </TD>
       <TD  align=left>
         <B>Data Processing House</B>
       </TD>
       <TD  align=left>
         <select name="DP_VEND">
            {html_options options=$vendors.dp selected=$project_attributes.DP_VEND}
         </select>
       </TD>
   </TR>

   <TR class="{cycle values="tab,tab1"}">
       <TD align=left>
         <B>Quoted Survey Length</B>
       </TD>
       <TD align=left>
         <INPUT size="10" name='QUOTED_SURVEY_LENGTH' type='text' value='{$project_attributes.QUOTED_SURVEY_LENGTH}'>
         <input type='hidden' name='i_QUOTED_SURVEY_LENGTH' value='Quoted suvery length must be a number'>
       </TD>
       <TD align=left>
         <B>Actual Survey Length</B>
       </TD>
       <TD align=left>
         <INPUT size="10" name='ACTUAL_SURVEY_LENGTH' type='text' value='{$project_attributes.ACTUAL_SURVEY_LENGTH}'>
         <input type='hidden' name='i_ACTUAL_SURVEY_LENGTH' value='Actual suvery length must be a number'>
       </TD>
   </TR>

   <TR class="{cycle values="tab,tab1"}">
       <TD align=left>
         <B>Special Project Instructions</B>
       </TD>
       <TD align=left colspan='3'>
         <textarea rows='5' cols='100' name='SPSL_INSTR'>{$project_attributes.SPSL_INSTR}</textarea>
         <input type='hidden' name='DR_SPSL_INSTR' value='Special Instructions is Required'>
       </TD>
   </tr>

   <tr class="{cycle values="tab,tab1"}">
         <td align="left"><strong>Instruction File(s)</strong></td>
         <td colspan="3" align="left">
         {section name=id loop=$project_files.instruction}
            <a href="?action=get_project_file&id={$project_files.instruction[id].pjm_file_id}">{$project_files.instruction[id].pjm_file_title}</a><br>
         {/section}
         </td>
   </tr>

   <tr class="{cycle values="tab,tab1"}">
         <td align="left"><strong>Upload Instruction File</strong></td>
         <td><input type="file" name='file_SPSL_INSTR'></td>
         <td><strong>Title</strong>&nbsp;&nbsp;<input type="text" name="file_SPSL_INSTR_title"></td>
         <td><input type="button" value='Attach' onclick="upload_file(this.form);"></td>
   </tr>

   <TR class="{cycle values="tab,tab1"}">
       <TD  align=left>
         <B>Detailed Quota Description</B>
       </TD>
       <TD  align=left colspan='3'>
         <textarea rows='5' cols='100' name='QUOTA_DESC'>{$project_attributes.QUOTA_DESC}</textarea>
         <input type='hidden' name='DR_QUOTA_DESC' value='Detailed Quota Descriptions is Required'>
       </TD>
   </tr>

   <tr class="{cycle values="tab,tab1"}">
         <td align="left"><strong>Quota File(s)</strong></td>
         <td colspan="3" align="left">
         {section name=id loop=$project_files.quota}
            <a href="?action=get_project_file&id={$project_files.quota[id].pjm_file_id}">{$project_files.quota[id].pjm_file_title}</a><br>
         {/section}
         </td>
   </tr>

   <tr class="{cycle values="tab,tab1"}">
         <td align="left"><strong>Upload Quota File</strong></td>
         <td><input type="file" name='file_QUOTA_DESC'></td>
         <td><strong>Title</strong>&nbsp;&nbsp;<input type="text" name="file_QUOTA_DESC_title"></td>
         <td><input type="button" value='Attach' onclick="upload_file(this.form);"></td>
   </tr>

   <TR class="{cycle values="tab,tab1"}">
       <TD  align=left>
         <B>Data Delivery Instructions</B>
       </TD>
       <TD  align=left colspan='3'>
         <textarea rows='5' cols='100' name='DD_INSTR'>{$project_attributes.DD_INSTR}</textarea>
         <input type='hidden' name='DR_DD_INSTR' value='Data Delivery Instructions is Required'>
       </TD>
   </tr>

   <tr class="{cycle values="tab,tab1"}">
         <td align="left"><strong>Delivery File(s)</strong></td>
         <td colspan="3" align="left">
         {section name=id loop=$project_files.delivery}
            <a href="?action=get_project_file&id={$project_files.delivery[id].pjm_file_id}">{$project_files.delivery[id].pjm_file_title}</a><br>
         {/section}
         </td>
   </tr>

   <tr class="{cycle values="tab,tab1"}">
         <td align="left"><strong>Upload Delivery File</strong></td>
         <td><input type="file" name='file_DD_INSTR'></td>
         <td><strong>Title</strong>&nbsp;&nbsp;<input type="text" name="file_DD_INSTR_title"></td>
         <td><input type="button" value='Attach' onlcick="upload_file(this.form);"></td>
   </tr>

   <tr class="{cycle values="tab,tab1"}">
       <TD  align=left>
         <B>Key Service Ticket(s)</B>
       </TD>
       <TD  align=left colspan='3'>
         <INPUT size=100 name='SUPPORT_TI' type='text' value='{$project_attributes.SUPPORT_TI}'>
       </TD>
   </TR>
</table>
<div style="margin:1em 0 1em 0; text-align:center;">
   <INPUT type='button' value="Save & Lock Specification" onclick="can_save_specs() && check(this.form);">
</div>
{include file=common/div_footer.tpl}


</FORM>