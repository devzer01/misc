<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>GRASP 0.1-beta</title>
<script src="http://yui.yahooapis.com/3.4.1/build/yui/yui-min.js"></script>
{literal}
<style>
   	
</style>

{/literal}
</head>
<div id="demo" class="yui3-skin-sam">
  <label for="ac-input">Enter a GitHub username:</label><br>
  <input id="ac-input" type="text">
</div>

<script>{literal}
YUI().use('autocomplete', 'autocomplete-highlighters', function (Y) {
  Y.one('#ac-input').plug(Y.Plugin.AutoComplete, {
    resultHighlighter: 'phraseMatch',
    resultListLocator: 'accounts',
    resultTextLocator: 'account_name',
    source: 'http://dev.saasgrasp.com/account/validate/name/{query}/callback/{callback}'
  });
});{/literal}
</script>
</body>
</html>