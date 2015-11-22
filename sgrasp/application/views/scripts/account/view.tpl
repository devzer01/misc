{include file='home/header.tpl'}
<style>
{literal} 
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
div.pdata div {
		font-family:"Times New Roman";
		font-size: 12pt;
}

div.pdata table {
		font-family:"Times New Roman";
		font-size: 12pt;
		border: none;
}


.accountname {
		font-family:"Times New Roman";
		font-size: 20pt;
		padding-right: 10px;
		text-align: right;
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

#calander {
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
<div id='main' style='width: 300px; height: 200px; border: 1px solid black; background-color: white; padding: 5px;'>
<div style='padding: 5px 0px 0px 10px; float: right;'>
	<img style='width: 100px; height: 100px;' src='/imgs/logo_default.png' /></div>
<!-- <div style='padding: 5px 0px 0px 10px; float: left; font-size: 10pt; overflow: hidden; height: 100px;'>
	{** $wy->description() **}
</div>
 --><div style='clear: both; float: right; text-align: right;' class='accountname'>{$atypes[$account.account_type_id]} #[{$account.account_id}] {$account.account_name}</div>
<div style='clear: both; float: right; text-align: right; font-size: 16pt; padding-right: 10px;'><a href='mailto:{$primary_contact.contact_email}'>{$primary_contact.contact_first_name} {$primary_contact.contact_last_name}</a></div>
<div style='clear: both; float: right; text-align: right; font-size: 14pt; padding-right: 10px;'>{$primary_contact.contact_phone|phone_format:"`$contact.country_code_prefix`"} - {$country[$account.country_code].country_description}</div>
</div>
<div id='main' style='width: 300px; height: 200px; border: 1px solid black; background-color: white; padding: 5px; margin-left: 10px;'>
	<div style='height: 100px; padding: 5px 0px 0px 10px; float: right;'>Address
		[<a href='/Account/contact/id/{$account.account_id}'>EDIT</a>]<img style='width: 50px; height: 50px;' src='/imgs/icon-building.jpg' />
	</div>
	<div style='clear: both; float: right; text-align: right; font-size: 14pt; padding-right: 10px;'>
		{$primary_contact.street1}<br/>
		{$primary_contact.city}, {$primary_contact.state} {$primary_contact.postalcode}<br/>
		{$country[$primary_contact.country_code].country_description}
	</div>
</div>
<div id='main' style='width: 300px; height: 200px; border: 1px solid black; background-color: white; padding: 5px; margin-left: 10px;'>
	<div style='height: 60px; padding: 5px 0px 0px 10px; float: right;'>Memo
		[<a href='/Account/memo/id/{$account.account_id}'>EDIT</a>]<img style='width: 50px; height: 50px;' src='/imgs/Memo.png' />
	</div>
	<div style='clear: both; float: right; text-align: right; font-size: 14pt; padding-right: 10px;'>
		{$accountmemo.comment_text} 
	</div>
</div>

<div style='clear: both; width: 950px;'>
<!--<div style='width: 400px; float: left; text-align: right; font-size: 18pt;'>-->
<!--	<span>22</span> Projects <span>5</span> Quotes <br/> <span>$12,233</span> Pending $54,293 YTD-->
<!--</div>-->
<div id='contact' style='background-color: white; width: 450px; height: 230px; margin-top: 5px; float: left; border: 1px solid black; padding: 5px 5px 5px 10px;'>
<div class='portlet_header' style='width: 95%'>Contacts</div>
<div class='pdata'>	
	{foreach from=$contacts item=contact}
	<div style='width: 400px; padding: 5px 0px 2px 0px;'>
		[<a href='/Contact/edit/id/{$contact.contact_id}/'>Edit</a>]<a style='text-decoration: none;' href="mailto:{$contact.contact_email}">{$contact.contact_first_name} {$contact.contact_last_name}</a> - {$contact.contact_phone}
	</div>
{/foreach}
	 [<a href='/Contact/new/account_id/{$account.account_id}'>ADD</a>]
 </div>
</div>
<div id='quotes' style='border: 1px solid black; width: 450px; margin-top: 5px; float: right; padding: 5px 5px 5px 10px; background-color: white;'>
<div class='portlet_header' style='width: 95%'>Quotes</div>
<div class='pdata'>
         {foreach from=$quotes item=quote}
         		<table>
         			<tr></tr>
         			<tr>
         				<td><a href='/Quote/quote/id/{$quote.proposal_id}'>{$quote.proposal_id} - {$quote.proposal_name}</a></td>
         			</tr>
         		</table>
         {/foreach}
</div>
</div>
<div id='projects' style='border: 1px solid black; width: 450px; margin-top: 5px; float: right; padding: 5px 5px 5px 10px; background-color: white;'>
<div class='portlet_header' style='width: 95%'>Projects</div>
<div class='pdata'>
         {foreach from=$projects item=project}
         <div style='width: 400px;'>
         	<div class='portlet_data'><a href='/Project/detail/id/{$project.id}'>{$project.project_name}</a></div>
         </div>
         {/foreach}
</div></div>     	
<div id='invoices' style='width: 450px; margin-top: 5px; float: right; border: 1px solid black; padding: 5px 5px 5px 10px; background-color: white;'>
<div class='portlet_header' style='width: 95%'>Invoice</div>
	{foreach from=$invoices item=invoice}
         <div style='width: 400px;'>
         	<div class='portlet_data'>{$log.cdate|date_format} {$event_desc[$log.typeid]}</div>
         </div>
         {/foreach}
</div>
<div id='filemanager' style='clear: both; background-color: white; width: 450px; height: 230px; margin-top: 5px; float: left; border: 1px solid black; padding: 5px 5px 5px 10px;'>
<div class='portlet_header' style='width: 95%'>File Manager</div>
<div class='pdata'>	
	{foreach from=$files item=file}
	<div style='width: 400px; padding: 5px 0px 2px 0px;'>
		<a href='/Account/download/id/{$file.account_file_id}/'>{$file.account_file_name|truncate:30}</a> - {$file.account_file_title}
	</div>
{/foreach}
	 [<a href='/Account/newfile/id/{$account.account_id}'>ADD</a>]
 </div>
</div>
</div>
</body></html>