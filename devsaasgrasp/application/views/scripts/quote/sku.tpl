{include file='home/header.tpl'}
<h2>Add Sku</h2>
<form method='post' action='/Quote/savesku'>
<table>
	<tr>
		<td>Sku Name</td>
		<td><input type='text' name='sku_name' /></td>
	</tr>
	<tr>
		<td>Sku Description</td>
		<td><input type='text' name='sku_description' /></td>
	</tr>
	<tr>
		<td>Unit Price</td>
		<td><input type='text' name='unit_price' /></td>
	</tr>
	<tr>
		<td colspan='2'>
			<input type='submit' value='Save Sku' />
		</td>
	</tr>
</table>
</form>
{include file='home/footer.tpl'}