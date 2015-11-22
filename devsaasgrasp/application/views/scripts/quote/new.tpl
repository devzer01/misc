{include file='home/header.tpl'}
{literal}
	<script>
//Create a YUI sandbox on your page.
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
	});</script>
{/literal}
<div class='yui-skin-sam' style='background-color: white; padding 5px; margin: 10px; width: 950px; float: left;'>
<form name='accountadd' 
      id='accountadd' 
      action="/quote/save" 
      method="POST">
<div style='padding: 5px;'>
	<img style='width:128px; height: 128px;' src='/imgs/request_quote_icon.gif' />
	&nbsp;&nbsp;&nbsp;New Quote
<div id='notice' style='display:none; float: right; margin-top: 100px; margin-right: 40px;' class='reqfld'><strong>Please Input All Required Fields</strong></div> 
</div>
<div class='crlf'><strong>Quote Name</strong></div>
<div class='fld'><input type="text" size="40" id='proposal_name' name="proposal_name"></div>
<div class='crlf'><strong>Account</strong></div>
<div class='fld'><div id="divaccount" class="yui3-skin-sam">
  			<input id="account_name" type="text" name='account_name' id='account_name'/>
  			<input type='hidden' id='account_id' name='account_id' />
		</div></div>
<div class='crlf'><strong>Contact</strong></div>
<div class='fld'>
	<div id="divcontact" class="yui3-skin-sam">
  			<input id="contact_name" name='contact_name' type="text" id='contact_name' />
  			<input type='hidden' id='contact_id' name='contact_id' />
	</div>
</div>
<div class='crlf' style='padding: 10px;'>
<input type="button" onclick='validate() && this.form.submit();' value="Add Quote"></div>
         &nbsp;&nbsp;
<div class='fld' style='padding: 10px;'>
<input type="button" onclick="window.location.href = '/quote/list';" value="Cancel"></div>
</form>
</div>
<script>
{literal}
	function validate()
	{
		var valid = true;
		if ($('#account_name').val() == "") {
			$('#account_name').addClass('reqfld');
			valid = false;
		} else {
			$('#account_name').removeClass('reqfld');
		}
		
		if ($('#contact_name').val() == "") {
			$('#contact_name').addClass('reqfld');
			valid = false;
		} else {
			$('#contact_name').removeClass('reqfld');
		}
		
		if ($('#proposal_name').val() == "") {
			$('#proposal_name').addClass('reqfld');
			valid = false;
		} else {
			$('#proposal_name').removeClass('reqfld');
		}
		
		
		if (!valid) {
			$('#notice').show();
		} else {
			$('#notice').hide();
		}
		
		
			
		return valid;
	}
	{/literal}
</script>
{include file='home/footer.tpl'}
