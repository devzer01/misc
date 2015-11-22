{include file='home/header.tpl'}
<table>
	<tr>
		<th>Name</th>
		<th>Amount</th>
		<th>Purchase Price</th>
		<th>Purchase Date</th>
		<th>Shelf Life</th>
	</tr>
	{foreach from=$list item=inventory}
		<tr>
			<td>{$inventory.name}</td>
			<td>{$inventory.amount}</td>
			<td>{$inventory.purchase_price}</td>
			<td>{$inventory.purchase_date}</td>
			<td>{$inventory.shelf_life}</td>			
		</tr>
	{/foreach}
</table>
{include file='home/footer.tpl'}