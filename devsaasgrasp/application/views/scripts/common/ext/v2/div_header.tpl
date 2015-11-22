<table cellspacing="0" cellpadding="0" class="mainDataOutput" align="center">
   <tr>
      <td colspan="4" align="center"></td>
   </tr>
   <tr>
      <td class="dataOutputHeaderLeft"><img src="/images/ext/dataheaderleft.gif"></td>
      <td class="dataOutputHeader" width="50%" nowrap><span>{$div_title|default:"report_title"}</span>&nbsp;<img src="/images/ext/minus.gif" width="20" height="12" name="pic_{$div_name}" id="pic_{$div_name}" onClick="toggleDisplay('div_{$div_name}','pic_{$div_name}','/images/ext/plus.gif','/images/ext/minus.gif');"></td>
      <td class="dataOutputCustomize" width="50%" nowrap align="right">&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td class="dataOutputHeaderRight"><img src="/images/ext/dataheaderright.gif"></td>
   </tr>
   <tr>
      <td colspan="4">
      <div id="div_{$div_name}" style="display:block;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="innerDetailTable">
      <tr>
        <td colspan="99" class="innerTableHeader">{$div_title}</td>
      </tr>
