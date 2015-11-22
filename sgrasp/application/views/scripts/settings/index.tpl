{include file='home/header.tpl'}
{if $auth eq "0"} 
<div>Your Not Authorized to View System Settings</div>
{else}
<script src="http://yui.yahooapis.com/3.4.1/build/yui/yui-min.js"></script>
<div class='yui3-skin-sam'>
<div id="demo">
    <ul>
        <li><a href="#taskmanager">Task Manager</a></li>
        <li><a href="#projectparams">Project Params</a></li>
        <li><a href="#officeaddress">Office Address</a></li>
        <li><a href="#logoupload">Upload Logo</a></li>
        <li><a href="#i18n">Internationalization (i18n)</a></li>
    </ul>
    <div>
        <div id="taskmanager">
        	<h2>Add Task</h2>
				{foreach from=$tasks item=task}
					<li> {$task.description} 
				{/foreach}
				<form method='post' action='/Settings/addtask'>
				Task Name <input type='text' name='taskname' size='40' />
				<input type='button' value='Add Task' onclick='this.form.submit();' />
				</form>
        </div>
        <div id="projectparams">
        	<h2>Add Params</h2>
				{foreach from=$attrs item=attr}
					<li> {$attr.key} , {$attr.size} , {$attr.type}, {$attr.description} 
				{/foreach}
				<form method='post' action='/Settings/addattr'>
				Param Name <input type='text' name='attrname' size='40' /> <br/>
				Param Type <select name='attrtype'>
								<option value='text'>Text Box</option>
								<option value='radio'>Radio</option>
								<option value='checkbox'>Checkbox</option>
							</select> <br/>
				Param Size <input type='text' name='attrsize' size='40' /> <br/>
				Param Description <input type='text' name='attrdesc' size='40' /> <br/>
				<input type='button' value='Add Param' onclick='this.form.submit();' />
				</form>
        </div>
        <div id="officeaddress">
        	<h2>Office Address</h2>
				<form method='post' action='/Settings/officeaddr'>
				Company Name <input type='text' name='company_name' size='40' value='{$officeaddress.company_name}' /> <br/>
				Street <input type='text' name='street1' size='40' value='{$officeaddress.street1}' /> <br/> <br/>
				City <input type='text' name='city' size='40' value='{$officeaddress.city}' /> <br/>
				State / Province <input type='text' name='stateprovince' size='40' value='{$officeaddress.state}' /> <br/>
				Postal Code <input type='text' name='postalcode' size='40' value='{$officeaddress.postalcode}' /> <br/>
				Country <input type='text' name='country_code' size='40' value='{$officeaddress.country_code}' /> <br/>
				Phone <input type='text' name='phone' size='40' value='{$officeaddress.phone}' /> <br/>
				<input type='button' value='Set Office Address' onclick='this.form.submit();' />
				</form>
        </div>
        <div id='logoupload'>
        	<h2>Logo Upload</h2>
				<img src='/Settings/getlogo' />
				<form method='post' action='/Settings/uploadlogo' enctype="multipart/form-data">
					<input type='file' name='logo' />
					<input type='submit' value='Upload' />
				</form>
        </div>
        <div id='i18n'>
        	<h2>Internationalization</h2>
        		<form method='post' action='/Settings/i18n' enctype="multipart/form-data">
				Currency <select name='currency'>
							<option value='EUR'>Euro</option>
							<option value='USD'>US Dollar</option>
							</select>
					<input type='submit' value='Save' />
				</form>
        </div>
    </div>
</div>
</div>
<script>{literal}
// Create a new YUI instance and populate it with the required modules.
YUI().use('tabview', function (Y) {
	var tabview = new Y.TabView({
        srcNode: '#demo'
    });

    tabview.render();
});{/literal}
</script>
{/if}
{include file='home/footer.tpl'}