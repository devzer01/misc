{include file='home/header.tpl'}
<h2>Resource</h2>
<h3>Add Item</h3>
<form method='post' action='/Assets/saveresource'>
Type <input type='radio' name='type' value='human' /> Human <input type='radio' name='type' value='material' /> Material<br/>
Name <input type='text' name='name' size='20' /> <br/>
Value <textarea rows=2 cols=60 name='value'></textarea><br/>
Life Span <input type='text' name='life_span' size='5' /><br/>
<input type='submit' value='Save' />
<input type='hidden' name='asset_type_id' value='2' />
</form>
{include file='home/footer.tpl'}