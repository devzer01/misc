{include file='home/header.tpl'}
<div class='yui-skin-sam' style='background-color: white; padding 5px; margin: 10px; width: 950px; float: left;'>
<form name='accountadd' 
      id='accountadd' 
      action="/Account/save" 
      method="POST">
<div style='padding: 5px;'>
	<img style='width: 128px; height: 128px;' src='/imgs/accountsicon.png' />
	&nbsp;&nbsp;&nbsp;New Account
<div id='notice' style='display:none; float: right; margin-top: 100px; margin-right: 40px;' class='reqfld'><strong>Please Input All Required Fields</strong></div>
</div>
<div class='crlf'><strong>Account Name</strong></div>
<div class='fld'><input type="text" size="40" name="account_name" id='account_name'></div>
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
<div class='fld'>First <input type='text' id='contact_first_name' name='contact_first_name' size='20' />
				 Last <input type='text' id='contact_last_name' name='contact_last_name' size='20' /></div>
<div class='crlf'><strong>Contact Email</strong></div>
<div class='fld'><input type='text' id='contact_email' name='contact_email' size='40' /></div>
<div class='crlf'><strong>Contact Phone</strong></div>
<div class='fld'><input type='text' id='contact_phone' name='contact_phone' size='40' /></div>

<div class='crlf'><strong>Country</strong></div>
<div class='fld'><select name='country_code' id='country_code'>
            <option value="">Select Country</option>
            {html_options options=$list.country}
         </select></div>
<div class='crlf' style='padding: 10px;'>&nbsp;</div>
<div class='fld' style='padding: 10px;'>
	<input type="button" onclick="window.location.href = '/Account/list';" value="Cancel">
	&nbsp;&nbsp;<input type="button" onclick='validate() && this.form.submit();' value="Add"></div>

</form>
</div>
<script>
{literal}
	function validate()
	{
		var valid = true;
		if ($('#account_name').val() == "") {
			$('#account_name').addClass('reqfld');
			valid = false;
		} else {
			$('#account_name').removeClass('reqfld');
		}
		
		if ($('#contact_first_name').val() == "") {
			$('#contact_first_name').addClass('reqfld');
			valid = false;
		} else {
			$('#contact_first_name').removeClass('reqfld');
		}
		
		if ($('#contact_last_name').val() == "") {
			$('#contact_last_name').addClass('reqfld');
			valid = false;
		} else {
			$('#contact_last_name').removeClass('reqfld');
		}
		
		if ($('#contact_email').val() == "") {
			$('#contact_email').addClass('reqfld');
			valid = false;
		} else {
			$('#contact_email').removeClass('reqfld');
		}
		
		if ($('#contact_phone').val() == "") {
			$('#contact_phone').addClass('reqfld');
			valid = false;
		} else {
			$('#contact_phone').removeClass('reqfld');
		}
		
		if ($("#country_code").val() == "") {
			$('#country_code').addClass('reqfld');
			valid = false;
		} else {
			$('#country_code').removeClass('reqfld');
		}
		
		if (!valid) {
			$('#notice').show();
		} else {
			$('#notice').hide();
		}
		
		
			
		return valid;
	}
	{/literal}
</script>
{include file='home/footer.tpl'}