<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>GRASP Super User Management</title>
</head>
<body>
<form method='post' action='/Auth/Addsystem'>
<h1>Systems</h1>
<ul>
{foreach from=$systems item=system}
	<li><strong><a href='/Auth/super/users/{$system.id}'>{$system.db}</a></strong></li>
{/foreach}
</ul>
<div style='clear:both;'>Add System</div> 
Name <input type='text' name='system' value='' /> <br/>
Admin Email <input type='text' name='email' value='' /> <br/>
Admin Password <input type='password' name='password' value='' /> <br/>
<input type='submit' value='Add System' />
</form>
<form method='post' action='/Auth/Adduser'>
<h1>Users</h1>
<ul>
{foreach from=$users item=user}
	<li>{$user.email}</li>
{/foreach}
</ul>
<div style='clear:both;'>Add User</div> 
First Name <input type='text' name='firstname' value='' /> <br/>
Last <input type='text' name='lastname' value='' /> <br/>
Access <select name='user_type_id'>
			<option value='1'>Administrator</option>
			<option value='2'>Manager</option>
			<option value='3'>Employee</option>
			<option value='4'>Resource Only</option>
		</select>
Email <input type='text' name='email' value='' /> <br/>
Password <input type='password' name='password' value='' /> <br/>
<input type='hidden' name='system' value='{$system.db}' />
<input type='submit' value='Add System' />
</form>
</body>
</html>