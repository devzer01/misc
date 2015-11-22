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
<form name="theForm" method="post" action="/task/save">
<div>New Task</div>
<div>Name</div>
<div class="yui3-skin-sam">
	<input type='text' name='task_name' id='task_name' size=30 />
</div>
<div>Description</div>
<div class="yui3-skin-sam">
	<textarea name='task_desc' id='task_desc' rows=5 cols=40></textarea>
</div>
<div>Billable</div>
<div><input type='checkbox' name='billable' id='billable' /></div>
<div>Rate</div>
<div><input type='text' name='rate' id='rate' /></div>
<div><input type='button' value='Add Task' onclick='this.form.submit();' /></div>
</form>
</body>
</html>