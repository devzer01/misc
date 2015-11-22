{include file='home/header.tpl'}
<style>
{literal}
	input {
		margin: 2px;
	}
{/literal}
</style>
<div id='main' style='width: 300px; height: 200px; border: 1px solid black; background-color: white; padding: 5px; float: left;'>
<div style='padding: 5px 0px 0px 10px; float: right;'>
	<img style='width: 100px; height: 100px;' src='/imgs/logo_default.png' /></div>
<!-- <div style='padding: 5px 0px 0px 10px; float: left; font-size: 10pt; overflow: hidden; height: 100px;'>
	{** $wy->description() **}
</div>
 --><div style='clear: both; float: right; text-align: right;' class='accountname'>{$atypes[$account.account_type_id]} #[{$account.account_id}] {$account.account_name}</div>
<div style='clear: both; float: right; text-align: right; font-size: 16pt; padding-right: 10px;'><a href='mailto:{$primary_contact.contact_email}'>{$primary_contact.contact_first_name} {$primary_contact.contact_last_name}</a></div>
<div style='clear: both; float: right; text-align: right; font-size: 14pt; padding-right: 10px;'>{$primary_contact.contact_phone|phone_format:"`$contact.country_code_prefix`"} - {$country[$account.country_code].country_description}</div>
</div>
<div style='background-color: white; width: 500px; float: left; margin-left: 10px; padding: 5px;'>
<form method='post' action='/Account/savememo/id/{$account.account_id}'>
<textarea name='accountmemo' rows=10 cols=50>
{$accountmemo.comment_text}
</textarea>				
				<input type='button' value='Set Memo' onclick='this.form.submit();' />
				</form></div>
</body>
{include file='home/footer.tpl'}