<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>MyGMI</title>
<link rel="stylesheet" href="/css/ext/main.css" type="text/css">
<link rel="stylesheet" href="/css/ext/search.css" type="text/css">
<link rel="shortcut icon" href="/images/ext/favicon.ico" type="image/x-icon">
<link rel="icon" href="/images/ext/favicon.ico" type="image/x-icon">
<script type="text/javascript" src="/js/report.js"></script>
		<script type="text/javascript" src="/js/valid.js"></script>
		<script type="text/javascript" src="/js/menu.js"> </script>
		<script type="text/javascript" src="/js/validate.js"></script>
		<script type="text/javascript" src="/js/common.js"></script>
		<script type="text/javascript" src="/js/toggleWindow.js"></script>
		<script type="text/vbscript" language="VBScript" src="/js/dialogBoxes.vbs"></script>
{literal}
<script language="Javascript" type="text/javascript">
function toggleDisplay(variable, thisImage, displayNoneImage, displayBlockImage)
{
	thisField=document.getElementById(variable);
	thisImage = document.getElementById(thisImage);
	if(thisField.style.display != "none") //If div1 is currently visible, hide it
	{
		thisField.style.display = "none";
		thisImage.src = displayNoneImage; //"/images/ext/plus.gif"
	}
	else //if div1 is currently invisible, display it
	{
		thisField.style.display = "";
		thisImage.src = displayBlockImage; //"/images/ext/minus.gif"
	}
}
</script>

<script>

function toggleDropdown(variable)
{
	var thisSelectMenu=document.getElementById(variable);

	var i=0;
	for (i=0;i<thisSelectMenu.length;i++)
	{
		thisField=thisSelectMenu.options[i].value;
		thisDivTag=document.getElementById(thisField);
		thisDivTag.style.display = "none";
	}

	var thisField=thisSelectMenu.options[thisSelectMenu.selectedIndex].value;
	var thisDivTag=document.getElementById(thisField);
	thisDivTag.style.display = "block";

}
</script>
{/literal}
</head>

<body>

<!-- BEGIN MAIN HEADER TABLE -->
<table cellspacing="0" cellpadding="0" class="headerTable" style="width:100%">
  <tr>
    <td width="242" rowspan="4" align="left" style="vertical-align:bottom"><a href="/"><img src="/images/ext/beta_logo.gif" width="242" height="89" border="0"></a></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>

    <td colspan="4" nowrap class="welcomeCell">Welcome, <span class="username"><a href="/app/acm/?e={"action=display_edit_contact&account_id=`$smarty.session.user_primary_account_id`&contact_id=`$smarty.session.user_primary_contact_id`"|url_encrypt}">{$smarty.session.name}</a></span></td>
  </tr>
  <tr>
    <td height="30">&nbsp;</td>
    <td></td>
    <td></td>

    <td width="9" nowrap><img src="/images/ext/icon-arrow-blue.gif" width="6" height="6"></td>
    <td width="96" nowrap class="headerLinks"><a href="http://www.gmi-mr.com/help/doc-training/login.php?code=M8aab9ZL-e40bd290e0976252cf578c9757eba098" target="training">Training Center </a></td>
    <td width="9" nowrap><img src="/images/ext/icon-arrow-blue.gif" width="6" height="6"></td>
    <td width="52" nowrap class="headerLinks"><a href="mailto:mygmisupport@gmi-mr.com">Support</a></td>
    <td width="18" nowrap><a href="#"><img src="/images/ext/x.gif" width="16" height="15" border="0"></a></td>
    <td width="75" nowrap class="headerLinks"><a href="/?main_action=do_logout">Logout</a></td>
  </tr>
  <tr>
    <td class="nav" nowrap style="background-image:url(/images/ext/nav_background.gif);background-position:bottom;background-repeat:repeat-x;"><a href="/app/ptm/"><img src="/images/ext/home.gif" width="61" height="21" border="0" onMouseOver="this.src='/images/ext/home2.gif';" onMouseOut="this.src='/images/ext/home.gif'"></a><a href="/app/pgen/"><img src="/images/ext/proposal.gif" width="79" height="21" border="0" onMouseOver="this.src='/images/ext/proposal2.gif';" onMouseOut="this.src='/images/ext/proposal.gif'"></a><a href="/app/pjm/"><img src="/images/ext/project.gif" width="74" height="21" border="0" onMouseOver="this.src='/images/ext/project2.gif';" onMouseOut="this.src='/images/ext/project.gif'"></a><a href="/app/stm/"><img src="/images/ext/study.gif" width="66" height="21" border="0" onMouseOver="this.src='/images/ext/study2.gif';" onMouseOut="this.src='/images/ext/study.gif'"></a><a href="/app/atm/armc/"><img src="/images/ext/billing.gif" width="74" height="21" border="0" onMouseOver="this.src='/images/ext/billing2.gif';" onMouseOut="this.src='/images/ext/billing.gif'"></a><a href="/app/acm/"><img src="/images/ext/account.gif" width="80" height="21" border="0" onMouseOver="this.src='/images/ext/account2.gif';" onMouseOut="this.src='/images/ext/account.gif'"></a></td>
    <td class="nav" style="background-image:url(/images/ext/right_nav.gif);background-position:bottom;background-repeat:repeat-x;">&nbsp;</td>

    <td colspan="8" background="/images/ext/right_nav.gif" class="nav" style="background-image:url(/images/ext/right_nav.gif);background-position:bottom;background-repeat:repeat-x">&nbsp;</td>
  </tr>
</table>
<!-- END MAIN HEADER TABLE -->

{include file=common/ext/v2/left.tpl}