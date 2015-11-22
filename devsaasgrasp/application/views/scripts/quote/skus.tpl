{include file='home/header.tpl'}
<h2>Sku Management</h2>
<table>
	<tr>
		<td>Sku Id</td>
		<td>Sku Name</td>
		<td>Sku Description</td>
		<td>Unit Price</td>
		<td>Created By</td>
		<td>Created Date</td>
	</tr>
	{foreach from=$skus item=sku}
		<tr class='{cycle values="tab,tab1"}'>
			<td>{$sku.sku_id}</td>
			<td>{$sku.sku_name}</td>
			<td>{$sku.sku_description}</td>
			<td>{$sku.unit_price}</td>
			<td>{$sku.created_by}</td>
			<td>{$sku.created_date}</td>
		</tr>
	{/foreach}

</table>

{include file='home/footer.tpl'}