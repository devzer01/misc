<html>
<head><link rel="STYLESHEET" type="text/css" href="/dhtmlx.css">
<script src="/dhtmlx.js" type="text/javascript"></script>
<style>
{literal}
	body {  background-color: #ffffff;  }
 
	div {
		float: left;
		font-family:"Times New Roman";
		font-size: 18pt;
}
    div.crlf {
    	clear:both;
    	width: 250px;
    	text-align: left;
    	padding-right: 10px;
    	margin-top: 10px;
    	float:left;
}
    div.fld {
    	width: 250px;
    	margin-top: 10px;
    	float: left;
}
   input, select {
   	font-family:"Times New Roman";
		font-size: 18pt;
}
#contacts div {
		font-family:"Times New Roman";
		font-size: 12pt;
}
.accountname {
		font-family:"Times New Roman";
		font-size: 20pt;
		padding-left: 10px;
		text-align: left;
}
.portlet_header {
	background-color: #323232; 
	color: #ffffff; 
	width: 150px; 
	padding: 5px; 
	text-align: center; 
	border-right: 1px solid white;
}

.portlet_data {
	padding: 5px; 
	text-align: center; 
	border-right: 1px solid white;
}

#auditlog {
	margin-top: 10px;
	margin-left: 10px;
}


#auditlog div {
	font-family:"Times New Roman";
		font-size: 12pt;
	
}

#support {
	margin-top: 10px;
	margin-left: 10px;
}
#support div {
	font-family:"Times New Roman";
		font-size: 12pt;
	
}


{/literal}
</style>
   </head>
   <body>
<div style='width: 950px; float: left;'>
<div id='main' style='width: 550px; float: left;'>
<div style='float: left; clear:both;' class='accountname'>{$atypes[$account.account_type_id]} #[{$account.account_id}] {$account.account_name}</div>
<div style='float: left; clear:both; font-size: 16pt;'>{$account.contacts[0].contact_first_name} {$account.contacts[0].contact_last_name} {$account.contacts[0].contact_email}</div>
</div>
<div style='width: 400px; float: left; text-align: right; font-size: 18pt;'>
	<span>22</span> Projects <span>5</span> Quotes <br/> <span>$12,233</span> Pending $54,293 YTD
</div>
</div>
<div style='width: 950px;'>
<div style='float: left; width: 850px; text-align: center; padding-top: 20px; margin-bottom: 10px;'>
	[ Settings ]
	[ Quote ]
	[ Project ]
	[ Meeting ]
	[ Ticket ]
	[ Resources ]
</div>
<div style='clear: both; padding-left: 20px; padding-top: 10px;'>
	<img src='/imgs/graph.png' />
</div>
<div id='auditlog' style='border: 1px solid black; width: 500px; float: right; border: 1px solid black; padding: 5px 5px 5px 10px;'>
<div class='portlet_header' style='width: 85%'>Audit Log & Internal Notes</div>
         {foreach from=$audit_log item=log}
         <div style='width: 500px;'>
         	<div class='portlet_data'>{$log.cdate|date_format} {$event_desc[$log.typeid]}</div>
         </div>
         {/foreach}
</div>
<div id='support' style='border: 1px solid black; width: 500px; float: right; border: 1px solid black; padding: 5px 5px 5px 10px;'>
<div class='portlet_header' style='width: 85%'>Tickets & External Notes</div>
         {foreach from=$audit_log item=log}
         <div style='width: 500px;'>
         	<div class='portlet_data'>{$log.cdate|date_format} {$event_desc[$log.typeid]}</div>
         </div>
         {/foreach}
</div>       	
<div id='contacts' style='width: 500px; margin-top: 5px; float: right; border: 1px solid black; padding: 5px 5px 5px 10px;'>
<div style='width: 590px; padding: 5px 0px 2px 0px;'>
	<div class='portlet_header'>Contact</div>
	<div style='background-color: #323232; color: #ffffff; width: 150px; padding: 5px; text-align: center; border-right: 1px solid white;'>Number</div>
	<div style='background-color: #323232; color: #ffffff; width: 150px; padding: 5px; text-align: center;'>Time / Timezone</div>
</div>
	{section name=id loop=$account.contacts}
	<div style='width: 590px; padding: 5px 0px 2px 0px;'>
		<div style='width: 200px;'><a style='text-decoration: none;' href="mailto:{$account.contacts[id].contact_email}">{$account.contacts[id].contact_first_name} {$account.contacts[id].contact_last_name}</a></div>
		<div style='width: 150px;'>{$account.contacts[id].contact_phone_number|phone_format}</div>
		<div style='width: 150px;'>PDT</div>
	</div>
{sectionelse}
	<div><i>No Contacts</i></div>
{/section}
</div></div></div>
</body></html>