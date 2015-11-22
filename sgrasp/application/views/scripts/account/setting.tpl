<html>
<head><link rel="STYLESHEET" type="text/css" href="/dhtmlx.css">
<script src="/dhtmlx.js" type="text/javascript"></script>
<style>
{literal}
	body {  background-color: #ffffff; width: 400px; padding: 5px; margin: 0px; font-family: "Times New Roman";}
	div { padding: 2px 0px 2px 5px; margin: 2px 0px 2px 5px; }
{/literal}
</style>
</head>
<body>	
<form method="POST"        
		action="/Account/saveSetting/id/{$id}" id="setting" name="setting">

{foreach from=$attrdef item=def}
<div style='width: 400px; float: left; height: 45px; clear: both; background-color: {cycle values="white,gray"}'>
<div style='width: 270px; float: left;'>{$def.account_attr_description}</div>			
{if $security[$def.security_setting] eq "1"}
{else}
<div style='width: 90px; float: left;'>
	{if $def.value_type eq $smarty.const.ATTR_VALUE_TYPE_BOOL}
		<input type='checkbox' name='{$def.account_attr_name}' {if $attr[$def.account_attr_name] eq "1"}checked{/if} />
	{/if}
</div>	
{/if}
</div>
{/foreach}
<div>
		<input type="button" value="Save Settings" onclick="this.form.submit();">
		<input type="button" value="Close" onclick="closeWindow();">
</div>
</form>
</body></html>