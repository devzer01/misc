{include file='home/header.tpl'}
{literal}
	<script src="http://yui.yahooapis.com/3.4.1/build/yui/yui-min.js"></script>
	<script>
// Create a YUI sandbox on your page.
YUI().use('autocomplete', 'autocomplete-highlighters', function (Y) {
	  Y.one('#account_name').plug(Y.Plugin.AutoComplete, {
	    resultHighlighter: 'phraseMatch',
	    resultListLocator: 'accounts',
	    resultTextLocator: 'account_name',
	    queryDelay: 300,
	    source: '/account/validate/name/{query}/callback/{callback}'
	  });
	  Y.one('#account_name').ac.on('select', function (e) {
		  document.getElementById('account_id').value = e.result.raw.account_id
		  Y.one('#contact_name').plug(Y.Plugin.AutoComplete, {
			    resultHighlighter: 'phraseMatch',
			    resultListLocator: 'contacts',
			    resultTextLocator: 'contact_name',
			    queryDelay: 300,
			    source: '/contact/lookup/account/' + e.result.raw.account_id + '/name/{query}/callback/{callback}'
			  });
		  Y.one('#contact_name').ac.on('select', function (e) {
			  	//alert(e.result.raw.contact_id);
				document.getElementById('contact_id').value = e.result.raw.contact_id;
			  });
		  });
	});
	</script>
{/literal}
<div class='yui-skin-sam' style='background-color: white; padding 5px; margin: 10px; width: 950px; float: left;'>
<form name="theForm" method="post" action="/project/save">
<div style='padding: 5px;'>
	<img style='width: 128px; height: 128px;' src='/imgs/project.png' />
	&nbsp;&nbsp;&nbsp;New Project 
</div>
<div class='crlf'><strong>Project Name</strong></div>
<div class="yui3-skin-sam fld"><input type='text' name='project_name' id='project_name' size=30 /></div>
<div class='crlf'><strong>Account</strong></div>
<div class='fld'><div id="divaccount" class="yui3-skin-sam">
  			<input id="account_name" type="text" name='account_name' />
  			<input type='hidden' id='account_id' name='account_id' />
		</div></div>
<div class='crlf'><strong>Contact</strong></div>
<div class='fld'>
	<div id="divcontact" class="yui3-skin-sam">
  			<input id="contact_name" name='contact_name' type="text" />
  			<input type='hidden' id='contact_id' name='contact_id' />
	</div>
</div>
<div class='crlf' style='padding: 10px;'><input type='button' value='Add Project' onclick='this.form.submit();' /></div>
&nbsp;&nbsp;
<div class='fld' style='padding: 10px;'><input type="button" onclick="window.location.href = '/project/list';" value="Cancel"></div>
</form>
</div>
{include file='home/footer.tpl'}