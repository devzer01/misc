{assign var=sub_menu
		  value="menu/menu_`$menu.module_code`.tpl"}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link href="/css/{$menu.css_file}" rel="stylesheet" type="text/css">
		<link href="/css/ajax.css" rel="stylesheet" type="text/css" type="text/css">
		<title>{$smarty.session.name} @ {$menu.module_name}</title>
		<script type="text/javascript" src="/js/report.js" type='text/javascript'></script>
		<script type="text/javascript" src="/js/valid.js" type='text/javascript'></script>
		<script type="text/javascript" src="/js/menu.js" type='text/javascript'> </script>
		<script type="text/javascript" src="/js/validate.js" type='text/javascript'></script>
		<script type="text/javascript" src="/js/common.js" type='text/javascript'></script>
		<script type="text/javascript" src="/js/toggleWindow.js" type='text/javascript'></script>
		<script type="text/vbscript" language="VBScript" src="/js/dialogBoxes.vbs"></script>
	</head>
	<body>
	{if $menu.display_menu eq "1"}
	{include file='menu/menu_main.tpl'}
	{include file=$sub_menu}

	<table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        	<td class="CellNav3"><div id="subsubmenu2" style="display: none;"></div></td>

        <td align="right" class="CellNav3">
          <table border="0" cellpadding="0" cellspacing="0" style="background-image: url(/images/bg_nav3_graydk.gif);">
          <tr> 
             <td width="2"><img src="/images/divider2.gif" width="2" height="26"></td> 
             <td>
             &nbsp;&nbsp;<a href="javascript:openRoboHelp('{$menu.module_code}');" class="LinkNav3" onmouseover="parent.status='Context sensitive help'; return true;" onmouseout="parent.status=''; return true;">Help</a> 
             &nbsp;&nbsp;<a href="mailto:hbsupport@gmi-mr.com" class="LinkNav3" onmouseover="parent.status='Ask our support team'; return true;" onmouseout="parent.status=''; return true;">Support</a> 
             &nbsp;&nbsp;<a target='_blank' href="http://www.gmi-mr.com/help/doc-training/login.php?code=pBaJHmoM-c7d69b273b73a80790e2a34571571114" class="LinkNav3" onmouseover="parent.status='Hummingbird training guide'; return true;" onmouseout="parent.status=''; return true;">Training Center</a> 
             &nbsp;&nbsp;<a href="/?main_action=do_logout" class="LinkNav3" onmouseover="parent.status='LOGOUT'; return true;" onmouseout="parent.status=''; return true;"><img src="/images/dot_logout.gif" width="16" height="16" border="0" align="absmiddle">&nbsp;&nbsp;Logout</a> 
             </td> 
          </tr>
          </table>
        </td>
      </tr>
    </table>
    {/if}

<!--<hr align="center" width="60%">-->
