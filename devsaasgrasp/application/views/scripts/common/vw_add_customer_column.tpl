{include file='header.tpl'}
<script src="/js/jquery-1.2.2/jquery-1.2.2.js" type='text/javascript'></script>
<script type="text/javascript" src="/js/common.js" type='text/javascript'></script>
<form action="/app/Common/SaveDashBoard/" method="POST" id="customize">
<table width="90%">
	<tr>
		<td width="5%">&nbsp;</td>
		<td >&nbsp;</td>
	</tr>
	<tr class="tab1">
		<td colspan="2" class="header1"><strong>Customize Your {$module->GetDescription()} Dashboard</strong></td>
	</tr>
	{foreach from=$module_columns item=module_column}
	<tr>
		<td>&nbsp;</td>
		<td ><input type="checkbox" name="module_column_{$module_column->GetFrontPageModuleColumnID()}" 
		{if $frontpage_manager->isColumnSet($module_column->GetFrontPageModuleColumnID()) eq 1}
		checked {/if}>{$module_column->GetColumnName()}</td>	
	</tr>
	{/foreach}	
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="center"><input type="button" value="Add" onclick="this.form.submit();">&nbsp;&nbsp;
			<input type="button" value="Select All" id="selectremoveall" onclick="SelectRemoveAll('customize')" >&nbsp;&nbsp;
		<input type="button" value="Cancel" onclick="window.close();"></td>
	</tr>
	
</table>
<input type="hidden" name="module_id" value="{$module->GetFrontPageModuleID()}">
</form>
{include file='footer.tpl}
