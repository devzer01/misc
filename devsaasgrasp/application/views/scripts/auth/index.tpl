<h1 align="center">
	<font size="5">
		<span style="font-weight: 400">GRASP - Login</span>
	</font>
</h1>

<form action="/auth/authenticate" method="post">
	<table width="200" border="1" align="center">
	  	<tr>
	    	<td>Email:</td>
	    	<td><input type="text" name="login" size="10" value="{$meta.login}"/></td>
	  	</tr>
	  	<tr>
	    	<td>Password:</td>
	    	<td><input type="password" name="password" size="10" /></td>
	  	</tr>
	  	<tr>
	   	<td colspan="2" align="center"><input type="submit" value="Sign In" /></td>
	  	</tr>
	</table>
</form>