{include file='home/header.tpl'}
<div class='yui-skin-sam' style='background-color: white; padding 5px; margin: 10px; width: 950px; float: left;'>
<form name='useradd' 
      id='useradd' 
      action="/User/save" 
      method="POST">
<div style='padding: 5px;'>
	<img style='width: 128px; height: 128px;' src='/imgs/User-icon.png' />
	&nbsp;&nbsp;&nbsp;New User
</div>
<div class='crlf'><strong>Email</strong></div>
<div class='fld'><input type="text" size="40" name="email_address"></div>
<div class='crlf'><strong>Type of Account</strong></div>
<div class='fld'><select name='user_type_id'>
            <option value="1">Administrator</option>
            <option value="2">Manager</option>
            <option value="3">Employee</option>
         </select></div>
<div class='crlf'><strong>Contact Name</strong></div>
<div class='fld'>First <input type='text' name='first_name' size='40' /> Last <input type='text' name='last_name' size='40' /></div>
<div class='crlf'><strong>Contact Phone</strong></div>
<div class='fld'><input type='text' name='contact_phone' size='40' /></div>
<div class='crlf'><strong>Password</strong></div>
<div class='fld'><input type='password' name='password' size='40' /></div>

<div class='crlf'><strong>Country</strong></div>
<div class='fld'><select name='country_code'>
            <option value="">Select Country</option>
            {html_options options=$list.country}
         </select></div>
<div class='crlf'><strong>Quick</strong></td></div>
<div class='fld'><input type='checkbox' name='quick' checked=true /></div>
<div class='crlf' style='padding: 10px;'><input type="button" onclick='this.form.submit();' value="Add"></div>
         &nbsp;&nbsp;
<div class='fld' style='padding: 10px;'><input type="button" onclick="window.location.href = '/User/list';" value="Cancel"></div>
</form>
</div>
{include file='home/footer.tpl'}