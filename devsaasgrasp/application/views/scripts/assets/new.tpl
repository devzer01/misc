{include file='home/header.tpl'}
<div class='yui-skin-sam' style='background-color: white; padding 5px; margin: 10px; width: 950px; float: left;'>
<form name='addasset' 
      id='addasset' 
      action="/Asset/save" 
      method="POST">
<div style='padding: 5px;'>
	<img style='width: 128px; height: 128px;' src='/imgs/accountsicon.png' />
	&nbsp;&nbsp;&nbsp;New Asset
</div>
<div class='crlf'><strong>Tangibility</strong></div>
<div class='fld'>Tangible <input type="radio" value='tangible' name="tangibility" /> Intangible <input type="radio" value='intangible' name="tangibility" /></div>
<div class='crlf'><strong>Type of Account</strong></div>
<div class='fld'><select name='account_type_id'>
            <option value="1">Sales Lead</option>
            <option value="2">Sales Prospect</option>
            <option value="3">Customer</option>
            <option value="4">Vendor</option>
            <option value="5">Contractor</option>
            <option value="6">Vendor</option>
            <option value="7">Employee</option>
         </select></div>
<div class='crlf'><strong>Contact Name</strong></div>
<div class='fld'>First <input type='text' name='contact_first_name' size='20' />Last <input type='text' name='contact_last_name' size='20' /></div>
<div class='crlf'><strong>Contact Email</strong></div>
<div class='fld'><input type='text' name='contact_email' size='40' /></div>
<div class='crlf'><strong>Contact Phone</strong></div>
<div class='fld'><input type='text' name='contact_phone' size='40' /></div>

<div class='crlf'><strong>Country</strong></div>
<div class='fld'><select name='country_code'>
            <option value="">Select Country</option>
            {html_options options=$list.country}
         </select></div>
<div class='crlf' style='padding: 10px;'>&nbsp;</div>
<div class='fld' style='padding: 10px;'>
	<input type="button" onclick="window.location.href = '/Account/list';" value="Cancel">
	&nbsp;&nbsp;<input type="button" onclick='this.form.submit();' value="Add"></div>

</form>
</div>
{include file='home/footer.tpl'}