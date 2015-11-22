<script src='/js/validate.js'></script>
<script src='/js/acm.js'></script>
{popup_init src="/js/overlib/overlib.js"}

{if $meta.message neq ""}
<div align="center">
   <font color="Red"><strong>{$meta.message}</strong></font>
</div>
{/if}

<table>
<form name='searchform' 
      id='searchform' 
      action="/Account/saveforce" 
      method="POST">

	<tr class="tab">
		<td colspan="2">
			We Found The Following Accounts That Sounds Similar, Please See if the Account Already Exists, If not Press Add
		</td>
	</tr>

	<tr class="header1">
		<td><strong>Account ID</strong></td>
		<td><strong>Account Name</strong></td>
	</tr>
	{section name=id loop=$similar_accounts}
		<tr class="{cycle values='tab,tab1'}">
			<td>{$similar_accounts[id].account_id}</td>
			<td>{$similar_accounts[id].account_name}</td>
		</tr>
	{/section}

	<tr class="tab">
		<td colspan="2">
			Account Name: {$meta.account_name}&nbsp;&nbsp;
			<input type="hidden" name="account_name" value="{$meta.account_name}">
			<input type="hidden" name="country_code" value="{$meta.country_code}">
			<input type="hidden" name="account_type_id" value="{$meta.account_type_id}">
			<input type="hidden" name="account_subtype_id" value="{$meta.account_subtype_id}">
			<input type="button" value="Add" onclick="this.form.submit();">
			<input type="button" value="Cancel" onclick="document.location.href = '?';">	
		</td>
	</tr>
</form>
</table>