{include file='home/header.tpl}
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/fonts/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/button/assets/skins/sam/button.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/container/assets/skins/sam/container.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/calendar/assets/skins/sam/calendar.css" />

<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/element/element-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/button/button-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/container/container-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/calendar/calendar-min.js"></script>
<style>
{literal}
	div {
		float: left;
		font-family:"Times New Roman";
	}
	div#chart {
		float: left;
		font-family:"Times New Roman";
		font-size: 10pt;
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
		font-size: 10pt;
}
#contacts div {
		font-family:"Times New Roman";
		font-size: 12pt;
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
    font-size: 13px;
}

.portlet_data {
	padding: 5px; 
	text-align: center; 
	border-right: 1px solid white;
	font-size: 12px;
}

.tab {
	padding: 5px; 
	text-align: center; 
	border-right: 1px solid white;
	font-size: 12px;
}

.tab1 {
	padding: 5px; 
	text-align: center; 
	border-right: 1px solid white;
	font-size: 12px;
	background-color: gray;
	color: white;
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

.elmtitle {
	clear:both;
	width: 100px;
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

{/literal}
</style>
<div id='main' style='width: 300px; height: 200px; border: 1px solid black; padding: 2px 0px 2px 0px; background-color: white; padding: 5px;'>
<div style='float: right; text-align: right;' class='accountname'>{$atypes[$account.account_type_id]} #[{$project.id}] {$project.project_name}</div>
<div style='float: right; text-align: right; font-size: 16pt; padding-right: 10px;'>{$project.account_name} <br/><a href='mailto:{$contact.contact_email}'>{$contact.contact_name} {$account.contacts[0].contact_last_name}</a> {$account.contacts[0].contact_designation}</div>
<div style='float: right; text-align: right; font-size: 14pt; padding-right: 10px;'>{$contact.phone|phone_format:"`$contact.country_code_prefix`"} {$contact.country_description}</div>
</div>
<div id='main' style='width: 630px; height: 200px; border: 1px solid black; background-color: white; padding: 5px; margin-left: 10px;'>
	<div style='clear: both; float: right; text-align: right; font-size: 14pt; padding-right: 10px;'>
		<table style='width: 600px; padding: 5px 0px 2px 0px; border: 1px solid black;'>
	<tr>
		<th class='portlet_header' style='width: 180px;'>Taskname</th>
		<th class='portlet_header' style='width: 150px;'>Assinged</th>
		<th class='portlet_header' style='width: 160px;'>Due Date</th>
		<th class='portlet_header' style='width: 40px;'> - </th>
	</tr>
	{foreach from=$projecttasks item=task}
		<form method='post' action='/Project/deltask'>
		<tr class='{cycle values="tab,tab1"}'>
			<td>{$task.task_name}</td>
			<td>{$task.first_name} {$task.last_name}</td>
			<td>{$task.due_date}</td>
			<td><input type='submit' value='D' name='btnpress' /></td>
		</tr>
		<input type='hidden' name='project_task_id' value='{$task.id}' />
		<input type='hidden' name='project_id' value='{$project.id}' />
		</form>
	{/foreach}
	<form method='post' action='/Project/savetask'>
	<tr><!--  id='addtasktemplate' style='display:block;'> -->
		<td>
			<select name='taskname'>
				{foreach from=$tasks item=task}
					<option value='{$task.id}'>{$task.description}</option>
				{/foreach}
			</select>
		</td>
		<td>
			<select name='assign'>
				{foreach from=$users item=user}
					<option value='{$user.user_id}'>{$user.first_name} {$user.last_name}</option>
				{/foreach}
			</select>
		</td>
		<td>
			<input type='text' size='10' name='quotedate' id='quotedate' /> 
			<button type="button" id="show" title="Show Calendar"><img src='/images/calbtn.gif' /></button>
			<div id="cal1Container"></div>
		</td>
		<td>
			<input type='hidden' name='project_id' value='{$project.id}' />			
			<input type='submit' value='S' name='btnpress' />
		</td>
	</tr>
	</form>
	</table>
	</div>
</div>
<div id='contacts' style='margin-top: 10px; width: 300px; height: 600px; border: 1px solid black; padding: 2px 0px 2px 0px; background-color: white; padding: 5px;'>
&nbsp;
This space is available for operational workflow of your organization, send us an email to have this plugin enabled. 
</div>
<div style='width: 630px;'>
<div id='params' style='margin-top: 10px; width: 630px; height: 200px; border: 1px solid black; background-color: white; padding: 5px; margin-left: 10px;'>
	<div style='clear: both; float: right; text-align: right; font-size: 14pt; padding-right: 10px;'>
	<table style='width: 600px; padding: 5px 0px 2px 0px; border: 1px solid black;'>
	<tr>
		<th class='portlet_header' style='width: 180px;'>Parameter</th>
		<th class='portlet_header' style='width: 150px;'>Value</th>
	</tr>
	<form method='post' action='/project/saveattr'>
	{foreach from=$attrs item=attr}
	<tr>
		<td>
			{$attr.description}
		</td>
		<td>
			<input type='{$attr.type}' name='{$attr.key}' size='{$attr.size}' value='{$attrvals[$attr.key].value}' />
		</td>
	</tr>
	{foreachelse}
		<tr>
		<td colspan='2'>Don't See Any Params? Ask your administrator to customize your installation.</td>
		</tr>
	{/foreach}
	<tr>
		<input type='hidden' name='project_id' value='{$project.id}' />
		<td colspan='2'><input type='button' value='Save' onclick='this.form.submit();' /></td>
	</tr>
	</form>
	</table></div>
</div>

<div id='files' style='margin-top: 10px; width: 630px; height: 200px; border: 1px solid black; background-color: white; padding: 5px; margin-left: 10px;'>
	<div style='clear: both; float: right; text-align: right; font-size: 14pt; padding-right: 10px;'>
	<table style='width: 600px; padding: 5px 0px 2px 0px; border: 1px solid black;'>
	<tr>
		<th class='portlet_header' style='width: 180px;'>Timestamp</th>
		<th class='portlet_header' style='width: 180px;'>User</th>
		<th class='portlet_header' style='width: 180px;'>File</th>
		<th class='portlet_header' style='width: 150px;'>Description</th>		
	</tr>
	{foreach from=$files item=file}
		<tr class='{cycle values="tab,tab1"}'>
			<td>{$file.created_date}</td>
			<td>{$file.created_by}</td>
			<td><a href='/project/getfile/id/{$file.id}'>{$file.project_file_name}</a></td>
			<td>{$file.project_file_memo}</td>
		</tr>
	{/foreach}
	<tr>
		<td colspan='4'>
			<form method='post' action='/project/savefile' enctype="multipart/form-data">
				<input type='file' name='fileupload' />
				<input type='hidden' name='project_id' value='{$project.id}' /><br/>
				<textarea name='filememo'></textarea>
				<input type='button' name='btnSaveFile' value='Upload' onclick='this.form.submit();' />
			</form>
		</td>
	</tr>
	</table></div>
</div>

<div id='Memo' style='margin-top: 10px; width: 630px; border: 1px solid black; background-color: white; padding: 5px; margin-left: 10px;'>
	<div style='clear: both; float: right; text-align: right; font-size: 14pt; padding-right: 10px;'>
	<table style='width: 600px; padding: 5px 0px 2px 0px; border: 1px solid black;'>
	<tr>
		<th class='portlet_header' style='width: 180px;'>Timestamp</th>
		<th class='portlet_header' style='width: 180px;'>User</th>
		<th class='portlet_header' style='width: 150px;'>Memo</th>		
	</tr>
	{foreach from=$memos item=memo}
		<tr class='{cycle values="tab,tab1"}'>
			<td>{$memo.created_date}</td>
			<td>{$memo.created_by}</td>
			<td>{$memo.memo}</td>
		</tr>
	{/foreach}
	<tr>
		<td colspan=3>
			<form method='post' action='/project/savememo'>
				<textarea name='memo'></textarea>
				<input type='button' onclick='this.form.submit();' value='Add Memo' />
				<input type='hidden' name='project_id' value='{$project.id}' />
			</form>
		</td>
	</tr>
	</table></div>
</div>
</div>
{literal}
	<script>
	YAHOO.util.Event.onDOMReady(function(){

        var Event = YAHOO.util.Event,
            Dom = YAHOO.util.Dom,
            dialog,
            calendar;

        var showBtn = Dom.get("show");

        Event.on(showBtn, "click", function() {

            // Lazy Dialog Creation - Wait to create the Dialog, and setup document click listeners, until the first time the button is clicked.
            if (!dialog) {

                // Hide Calendar if we click anywhere in the document other than the calendar
                Event.on(document, "click", function(e) {
                    var el = Event.getTarget(e);
                    var dialogEl = dialog.element;
                    if (el != dialogEl && !Dom.isAncestor(dialogEl, el) && el != showBtn && !Dom.isAncestor(showBtn, el)) {
                        dialog.hide();
                    }
                });

                function resetHandler() {
                    // Reset the current calendar page to the select date, or 
                    // to today if nothing is selected.
                    var selDates = calendar.getSelectedDates();
                    var resetDate;
        
                    if (selDates.length > 0) {
                        resetDate = selDates[0];
                    } else {
                        resetDate = calendar.today;
                    }
        
                    calendar.cfg.setProperty("pagedate", resetDate);
                    calendar.render();
                }
        
                function closeHandler() {
                    dialog.hide();
                }

                dialog = new YAHOO.widget.Dialog("container", {
                    visible:false,
                    context:["show", "tl", "bl"],
                    buttons:[ {text:"Reset", handler: resetHandler, isDefault:true}, {text:"Close", handler: closeHandler}],
                    draggable:false,
                    close:true
                });
                dialog.setHeader('Pick A Date');
                dialog.setBody('<div id="cal"></div>');
                dialog.render(document.body);

                dialog.showEvent.subscribe(function() {
                    if (YAHOO.env.ua.ie) {
                        // Since we're hiding the table using yui-overlay-hidden, we 
                        // want to let the dialog know that the content size has changed, when
                        // shown
                        dialog.fireEvent("changeContent");
                    }
                });
            }

            // Lazy Calendar Creation - Wait to create the Calendar until the first time the button is clicked.
            if (!calendar) {

                calendar = new YAHOO.widget.Calendar("cal", {
                    iframe:false,          // Turn iframe off, since container has iframe support.
                    hide_blank_weeks:true  // Enable, to demonstrate how we handle changing height, using changeContent
                });
                calendar.render();

                calendar.selectEvent.subscribe(function() {
                    if (calendar.getSelectedDates().length > 0) {

                        var selDate = calendar.getSelectedDates()[0];

                        // Pretty Date Output, using Calendar's Locale values: Friday, 8 February 2008
                        //var wStr = calendar.cfg.getProperty("WEEKDAYS_LONG")[selDate.getDay()];
                        var dStr = selDate.getDate();
                        var mStr = selDate.getMonth();
                        //var mStr = calendar.cfg.getProperty("MONTHS_LONG")[selDate.getMonth()];
                        var yStr = selDate.getFullYear();
        
                        Dom.get("quotedate").value = yStr + "-" + mStr + "-" + dStr;
                    } else {
                        Dom.get("quotedate").value = "";
                    }
                    dialog.hide();
                });

                calendar.renderEvent.subscribe(function() {
                    // Tell Dialog it's contents have changed, which allows 
                    // container to redraw the underlay (for IE6/Safari2)
                    dialog.fireEvent("changeContent");
                });
            }

            var seldate = calendar.getSelectedDates();

            if (seldate.length > 0) {
                // Set the pagedate to show the selected date if it exists
                calendar.cfg.setProperty("pagedate", seldate[0]);
                calendar.render();
            }

            dialog.show();
        });
    });
	</script>
{/literal}
 {include file='home/footer.tpl}