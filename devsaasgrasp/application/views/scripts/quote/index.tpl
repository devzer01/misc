{include file='home/header.tpl'}

<input type='button' value='New Quote' />
<input type='button' value='List Quotes' />
<input type='button' value='Search Quotes' />
<input type='button' value='Manage Sku' onclick='document.location.href = "/Quote/skus";' />
<input type='button' value='Add Sku' onclick='document.location.href = "/Quote/sku";' />
{include file='home/footer.tpl'}