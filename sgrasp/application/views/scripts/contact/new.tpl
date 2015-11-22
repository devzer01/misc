{include file='home/header.tpl'}
<style>
{literal}
   body {
   	background-color: #ffffff;
}
    div.crlf {
    	clear:both;
    	width: 100px;
    	text-align: right;
    	padding-right: 10px;
    	margin-top: 10px;
    	font-size: 14px;
    	float: left;
}
    div.fld {
    	width: 400px;
    	margin-top: 10px;
    	float: left;
}
   input, select {
   	font-family:"Times New Roman";
		font-size: 14pt;
}
{/literal}
</style>
{debug}
<div id='main' style='width: 300px; height: 200px; border: 1px solid black; background-color: white; padding: 5px; float: left;'>
<div style='padding: 5px 0px 0px 10px; float: right;'>
	<img style='width: 100px; height: 100px;' src='/imgs/logo_default.png' /></div>
<div style='clear: both; float: right; text-align: right;' class='accountname'>{$atypes[$account.account_type_id]} #[{$account.account_id}] {$account.account_name}</div>
<div style='clear: both; float: right; text-align: right; font-size: 16pt; padding-right: 10px;'><a href='mailto:{$primary_contact.contact_email}'>{$primary_contact.contact_first_name} {$primary_contact.contact_last_name}</a></div>
<div style='clear: both; float: right; text-align: right; font-size: 14pt; padding-right: 10px;'>{$primary_contact.contact_phone|phone_format:"`$contact.country_code_prefix`"} - {$country[$account.country_code]}</div>
</div>
<div style='background-color: white; width: 600px; float: left; margin-left: 10px; padding: 5px;'>
<form method='post' action='/Contact/save'>
<div class='crlf'>First Name</div>
<div class='fld'><input type='text' name='firstname' id='firstname' size='25' value='{$contact.contact_first_name}' /></div>
<div class='crlf'>Last Name</div>
<div class='fld'><input type='text' name='lastname' id='lastname' size='25' value='{$contact.contact_last_name}' /></div>
<div class='crlf'>Email</div>
<div class='fld'><input type='text' name='email' id='email' size='25' value='{$contact.contact_email}' /></div>
<div class='crlf'>Phone</div>
<div class='fld'><input type='text' name='phone' id='phone' size='25' value='{$contact.contact_phone}' /></div>
<div class='crlf'>Country</div>
<div class='fld'>
	<select name='country_code'>
            <option value="">Select Country</option>
            {html_options options=$country}
         </select>
</div>
<div class='crlf'>Street</div>
<div class='fld'><input type='text' name='street1' size='25' value='{$officeaddress.street1}' /></div>
<div class='crlf'>City</div>
<div class='fld'><input type='text' name='city' size='25' value='{$officeaddress.city}' /></div>
<div class='crlf'>State / Province</div>
<div class='fld'><input type='text' name='stateprovince' size='25' value='{$officeaddress.state}' /> </div>
<div class='crlf'>Postal Code</div>
<div class='fld'><input type='text' name='postalcode' size='25' value='{$officeaddress.postalcode}' /> </div>
<div class='crlf'>Set As Primary</div>
{if $contact.contact_type_id eq "1"}
<input type='hidden' name='contact_id' value='{$contact.contact_id}' />
<div  class='fld'><input type='checkbox' name='primary' id='primary' checked/></div>
{else} 
<div  class='fld'><input type='checkbox' name='primary' id='primary' /></div>
{/if}
<input type='hidden' name='account_id' value='{$account_id}' />
<div class='crlf'>
	<input type='button' value='Add Contact' onclick='this.form.submit();' />
</div>
</form></div>
{include file='home/footer.tpl'}