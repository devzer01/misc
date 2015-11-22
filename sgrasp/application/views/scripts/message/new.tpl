<html><head>
{literal}
	<style>
		body {
			background-color: white;
			padding: 5px;
		}
	</style>
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
</head>
<body>
<form name="theForm" method="post" action="/message/save">
<div>New Message</div>
<div>Account</div>
<div class="yui3-skin-sam">
	<input type='text' name='account_name' id='account_name' size=30 />
	<input type='hidden' name='account_id' id='account_id' value='' />
</div>
<div>Contact</div>
<div class="yui3-skin-sam">
	<input type='text' name='contact_name' id='contact_name' size=30 />
	<input type='hidden' name='contact_id' id='contact_id' value='' />
</div>
<div>Project Name</div>
<div><input type='text' name='project_name' id='project_name' size=30 /></div>
<div>Project Duration</div>
<div>
	<input type='text' name='start_date' id='start_date' size=10 /> 
	&nbsp;&nbsp; 
	<input type='text' name='end_date' id='end_date' size=10 />
</div>
<div><input type='button' value='Add Project' onclick='this.form.submit();' /></div>
</form>
</body>
</html>