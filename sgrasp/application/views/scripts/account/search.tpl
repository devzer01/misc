{include file='home/header.tpl}
<h2>Account Search</h2>
<form method='post' action='/Account/search'>
	<strong>Name</strong><input type='text' name='an' value='' size='50' />
	<input type='submit' value='Search' />
	<input type='hidden' value='1' name='dosearch' />
</form>
{include file='home/footer.tpl'}