{assign var="account_id" value=$account.account_id}
<form name='information_agreement' 
      id='information_agreement' 
      action='?e={"action=save_info_pass_agreement&account_id=$account_id"|url_encrypt}'
      method="POST"
      enctype="multipart/form-data">
<a name="information_agreement"></a>
{include file='common/div_header.tpl' 
			div_title='Information Passing Agreement' 
			div_name='information_passing_agreement' 
			div_width='80%' 
			div_align='center'}
	<table width="100%">
   <tr class="header1">
      <td width="20%">User</td><td width="20%">Date</td><td width="60%">Agreement Text</td>
   </tr>
  {section name=id loop=$comments.infopass_agreement}
   
   <tr class="{cycle values='tab,tab1'}">
      <td>{$comments.infopass_agreement[id].created_by_name}</td>
      <td>{$comments.infopass_agreement[id].created_date|date_format:"%Y-%m-%d"}</td>
      <td>{$comments.infopass_agreement[id].comment_text|nl2br}</td>
   </tr>
{sectionelse}
	<tr class="tab1">
		<td colspan="9">
			<i>No Agreement</i>
		</td>
	</tr>
{/section}
</table>
<TABLE width="100%">
 <tr class="header1">
   <td colspan="2">Agreement Information</td>
   </tr>
    <tr class="tab">
      <td align="right"><strong>Agreement Text</strong></td>
      <td>
      <textarea name="agreement_text" cols="60" rows="5"></textarea>
      </td>
   </tr>
   <tr class="tab1">
      <td align="right">
         <strong>Agreement File Title</strong>
      </td>
      <td align="left" id='td_account_file_title' name='td_account_file_title'>
         <input type="text" name="account_file_title" size="30">
      </td>
   </tr>
   <tr class="tab1">
      <td align="right">
         <strong>Agreement File</strong>
      </td>
      <td align="left" id='td_account_file' name='td_account_file'>
         <input type="file" name="account_file" >
      </td>
   </tr> 
   <tr class="tab1">
      <td colspan="6" align="center">
         <input type="submit" 
                onclick="check(this.form);" 
                value="Save">
         &nbsp;&nbsp;
         <input type="button" 
                onclick="window.location.href ='?e={"action=display_account_detail&account_id=$account_id"|url_encrypt}#information_agreement';"
                value="Cancel">
      </td>
   </tr> 
   </TABLE>  
{include file='common/div_footer.tpl'}
</form>