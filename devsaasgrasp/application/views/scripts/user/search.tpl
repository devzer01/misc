{include file='home/header.tpl}
<h2>User Search</h2>
<form method='post' action='/User/search'>
	<strong>Name</strong><input type='text' name='un' value='' size='50' />
	<input type='submit' value='Search' />
	<input type='hidden' value='1' name='dosearch' />
</form>
{include file='home/footer.tpl'}