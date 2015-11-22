{include file='home/header.tpl'}
<h2>Inventory</h2>
<h3>Add Item</h3>
<form method='post' action='/Assets/save'>
Name <input type='text' name='name' size='20' /> <br/>
Description <textarea rows=2 cols=60 name='description'></textarea><br/>
Amount <input type='text' name='amount' size='5' /><br/>
Purchase Price <input type='text' name='purchase_price' size='6' /><br/>
Purchase Date <input type='text' name='purchase_date' size='6' /><br/>
Shelf Life <input type='text' name='shelf_life' size='5' /><br/>
<input type='submit' value='Save' />
<input type='hidden' name='asset_type_id' value='1' />
</form>
{include file='home/footer.tpl'}