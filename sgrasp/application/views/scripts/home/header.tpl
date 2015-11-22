<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>GRASP 0.1-beta</title>
<script src="http://yui.yahooapis.com/3.4.1/build/yui/yui-min.js"></script>
<script src='http://code.jquery.com/jquery-1.7.2.min.js'></script>
<style>
    html, body {literal} {
        width: 100%;
        margin: 0px;
        padding: 0px;
    } 
    
    table {
		border: thin black solid;
    }
    
    th {
		background-color: gray;
    	color: #ffffff;
    	padding: 5px;
    }
    
    td {
		padding: 5px;
    	margin: 2px;
    }
    
    .menubar {
    	margin-left: 4px;
    	font-size: 16px;
    	width: 100%;
    }
    
    .menuitem {
		width: 150px;
        padding: 5px;
        float: left;
    	text-align: center;
    	font-size: 16px;
    }
    
    .submenu {
		font-size: 13px;
    }
    
    #m_account {
		background-color: #33CC33;
    	margin: 2px;
    	border: thin black solid;
    	text-align: center;
    }
    
    #m_account a {
		color: #ffffff;
    	text-decoration:none;
    }
    
    #m_quote {
		background-color: #FF00FF;
    	margin: 2px;
    	border: thin black solid;
    	text-align: center;
    }
    
    #m_quote a {
		color: #ffffff;
    	text-decoration:none;
    }
    
	#lblm_quote {
		border-bottom: thin black solid;
    	text-align: center;
    	width: 100%;
    }
    
    #lblm_account {
		border-bottom: thin black solid;
    	text-align: center;
    	width: 100%;
    }
    
    #bodycontent { {/literal}
		background-color: #{$bgcolor};
    	width: 970px;
    	border: dotted 1px black;
    	padding: 5px;
    	margin: 1px;
  {literal}}
    
    /* div {
		float: left;
		font-family:"Times New Roman";
		font-size: 18pt; */
/* } */
/*FF5050*/
 #m_project {
		background-color: #3366FF;
    	margin: 2px;
    	border: thin black solid;
    	text-align: center;
    }
    
    #m_project a {
		color: #ffffff;
    	text-decoration:none;
    }
    
	#lblm_project {
		border-bottom: thin black solid;
    	text-align: center;
    	width: 100%;
    }
    
	#m_invoice {
		background-color: #FF5050;
    	margin: 2px;
    	border: thin black solid;
    	text-align: center;
    }
    
    #m_invoice a {
		color: #ffffff;
    	text-decoration:none;
    }
    
    /*FFCC00*/
    
    
	#lblm_invoice {
		border-bottom: thin black solid;
    	text-align: center;
    	width: 100%;
    }
    
    #m_user {
		background-color: #FFCC00;
    	margin: 2px;
    	border: thin black solid;
    	text-align: center;
    }
    
    #m_user a {
		color: #ffffff;
    	text-decoration:none;
    }
    
	#lblm_user {
		border-bottom: thin black solid;
    	text-align: center;
    	width: 100%;
    }
    
	#m_setting {
		background-color: #9933FF;
    	margin: 2px;
    	border: thin black solid;
    	text-align: center;
    }
    
    #m_setting a {
		color: #ffffff;
    	text-decoration:none;
    }
    
	#lblm_setting {
		border-bottom: thin black solid;
    	text-align: center;
    	width: 100%;
    }
    	
    div.crlf {
    	float: left;
    	font-family:"Times New Roman";
    	clear:both;
    	font-size: 18pt;
    	width: 300px;
    	text-align: right;
    	padding-right: 10px;
    	margin-top: 10px;
}
    div.fld {
    	float: left;
    	width: 300px;
    	margin-top: 10px;
}
   input, select {
   	font-family:"Times New Roman";
		font-size: 18pt;
	}
	
	th {
	font-size: 13px;
	text-align: center;
	
	}
	
	
	.reqfld {
		background-color: yellow;
	}
	
	/** 9933FF **/
	
	
</style>
{/literal}
</head>
<body class="yui-skin-sam">
{include file='home/menu.tpl'}
<div style='clear:both;height: 1px;'>&nbsp;</div>
<div id='bodycontent' style='float: left;'>