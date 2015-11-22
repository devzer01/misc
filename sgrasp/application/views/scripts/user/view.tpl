{include file='home/header.tpl}
<style>
{literal}
	body {  background-color: #ffffff;  padding: 5px; margin: 0px; }
 
/* div {
		float: left;
		font-family:"Times New Roman";
		font-size: 18pt;
} */
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

.elmtitle {
	clear:both;
	width: 100px;
}

{/literal}
</style>
{literal}
<script>

</script>
{/literal}
<script src="http://yui.yahooapis.com/3.4.1/build/yui/yui-min.js"></script>
<div class='yui3-skin-sam' style='clear:both; width: 900px;'>
<div id="demo" style='width: 900px;'>
    <ul>
        <li><a href="#userprofile">Employee Profile</a></li>
        <li><a href="#lifecycle">Life Cycle</a></li>
        <li><a href="#salary">Salary</a></li>
        <li><a href="#leaves">Leaves</a></li>
    </ul>
    <div>
        <div id="userprofile" style='width: 900px;'>
<form method='post' action='/User/saveattr'>
<h2>Employee Profile</h2>
<table style='width: 850px;'>
<tr><td>Employee No.</td><td><input type='text' name='employee_no' value='{$attr.employee_no.user_value}' size=5 /> </td><td colspan='2'></td></tr>
<tr><td>Title </td><td><select name='title'><option value='mr'>Mr.</option><option value='ms'>Ms.</option><option value='mrs'>Mrs.</option></select> </td><td colspan='2'></td></tr>
<tr><td>First Name </td><td><input type='text' name='firstname' value='{$attr.firstname.user_value}' size='20' /> </td><td>Last Name </td><td><input type='text' value='{$attr.lastname.user_value}' name='lastname' size='20' /> </td></tr>
<tr><td>Initials </td><td><input type='text' name='initials' size='5' value='{$attr.initials.user_value}' /> </td><td colspan='2'></td></tr>
<tr><td>Maiden Name </td><td><input type='text' name='maidenname' value='{$attr.maidenname.user_value}' size='20' /> </td><td colspan='2'></td>
<tr><td>Payroll Name </td><td><input type='text' name='payrollname' value='{$attr.payrollname.user_value}' size='20' /></td><td>Known As</td><td> <input type='text' name='knownas' value='{$attr.knownas.user_value}' size='20' /> </td></tr>
<tr><td>System Id </td><td><input type='text' name='systemid' size='20' value='{$attr.systemid.user_value}' /> </td></tr>
<tr><td>Designation </td><td><input type='text' name='designation' size='20' value='{$attr.designation.user_value}' /> </td></tr>
<tr><td>Grade </td><td><input type='text' name='grade' size='20' value='{$attr.grade.user_value}' /> </td></tr>
<tr><td>Telephone Extension </td><td><input type='text' name='telephone_ext' value='{$attr.telephone_ext.user_value}' size='20' /> </td></tr>
<tr><td>Payrolled </td><td><input type='text' name='payrolled' size='20' value='{$attr.payrolled.user_value}' /> </td></tr>
<tr><td>Office Classification Status </td><td><input type='text' name='office_classification' value='{$attr.office_classification.user_value}' size='20' /> </td></tr>
<tr><td>Work Status </td><td><input type='text' name='workstatus' size='20' value='{$attr.workstatus.user_value}' /> </td></tr>
<tr><td>Hours worked per week </td><td><input type='text' name='hoursworked' size='20' value='{$attr.hoursworked.user_value}' /> </td></tr>
<tr><td>No of Units </td><td><input type='text' name='no_of_units' size='20' value='{$attr.no_of_units.user_value}' /> </td></tr>
<tr><td>Original Start Date </td><td><input type='text' name='startdate' size='20' value='{$attr.startdate.user_value}' /> </td></tr>
<tr><td>Service Date </td><td><input type='text' name='servicedate' size='20' value='{$attr.servicedate.user_value}' /> </td></tr>
<tr><td>Expected Date of Retirement  </td><td><input type='text' name='expected_retirement' value='{$attr.expected_retirement.user_value}' size='20' /> </td></tr>
<tr><td>Statutory Classification </td><td><input type='text' name='statutory_classification' value='{$attr.statutory_classification.user_value}' size='20' /> </td></tr>
<tr><td>Notice Period </td><td><input type='text' name='notice_period' value='{$attr.notice_period.user_value}' size='20' /> </td></tr>
<tr><td>Notice Period in Units </td><td><input type='text' name='notice_period_units' value='{$attr.notice_period_units.user_value}' size='20' /> </td></tr>
<tr><td>Country </td><td><input type='text' name='country' value='{$attr.country.user_value}' size='20' /> </td></tr>
<tr><td>MSPF/EPF No </td><td><input type='text' name='mspfepf' value='{$attr.mspfepf.user_value}' size='20' /> </td></tr>
<tr><td>Employment Status </td><td><input type='text' name='employment_status' value='{$attr.employment_status.user_value}' size='20' /> </td></tr>
<tr><td>Confirmation Expected Date </td><td><input type='text' name='confirmation_date' value='{$attr.confirmation_date.user_value}' size='20' /> </td></tr>
<tr><td>Occupational Code </td><td><input type='text' name='occupy_code' value='{$attr.occupy_code.user_value}' size='20' /> </td></tr>
<tr><td>Contract Start </td><td><input type='text' name='contract_start' value='{$attr.contract_start.user_value}' size='20' /> </td><td> End </td><td><input type='text' name='contract_end' value='{$attr.contract_end.user_value}' size='20' /> </td></tr>
<tr><td>Id No </td><td><input type='text' name='nicid' value='{$attr.nicid.user_value}' size='20' /> </td></tr>
<tr><td>Passport No </td><td><input type='text' name='passport_number' value='{$attr.passport_number.user_value}' size='20' /> </td></tr>
<tr><td>Date of Birth </td><td><input type='text' name='dateofbirth' value='{$attr.dateofbirth.user_value}' size='20' /> </td></tr>
<tr><td>Gender </td><td><input type='text' name='gender' value='{$attr.gender.user_value}' size='20' /> </td></tr>
<tr><td>Nationality  </td><td><input type='text' name='nationality' value='{$attr.nationality.user_value}' size='20' /> </td></tr>
<tr><td>Religion </td><td><input type='text' name='religion' value='{$attr.religion.user_value}' size='20' /> </td></tr>
<tr><td>Marital Status </td><td><input type='text' name='marital_status' value='{$attr.marital_status.user_value}' size='20' /></td></tr>
<tr><td>Blood Group </td><td><input type='text' name='blood_group' value='{$attr.blood_group.user_value}' size='20' /> </td></tr>
<tr><td colspan='4'>Home Address</td></tr> 
<tr><td>Address 1 </td><td><input type='text' name='address1' value='{$attr.address1.user_value}' size='20' /> </td><td> Address 2 </td><td><input type='text' name='address2' value='{$attr.address2.user_value}' size='20' /> </td></tr> 
<tr><td>Country Postal Code </td><td><input type='text' name='postal_code' value='{$attr.postal_code.user_value}' size='20' /> </td><td colspan='2'></td></tr>

<tr><td colspan='4'>
<input type='hidden' name='user_id' value='{$user.user_id}' />
<input type='submit' value='Save Profile' /></td></tr>
</table>
</form>
</div>
 <div id="lifecycle" style='width: 900px'>
 <h2>Employee Life Cycle</h2>
 	<table style='width: 850px;'>
 		<tr>
 			<td>Date</td>
 			<td>Admin</td>
 			<td>Action</td>
 			<td>Comment</td>
 		</tr>
 		{foreach from=$lifecycles item=lifecycle}
 			<tr class='{cycle values="tab,tab1"}'>
 				<td>{$lifecycle.created_date}</td>
 				<td>{$users[$lifecycle.created_by]}</td>
 				<td>{$user_comment_types[$lifecycle.user_comment_type_id]}</td>
 				<td>{$lifecycle.comment}</td>
 			</tr>
 		{/foreach}
 		
 		<tr>
 			<td colspan='4'>
 			<form method='post' action='/User/savecomment'>
 				<select name='comment_type_id'>
 					{html_options options=$user_comment_types}
 				</select>
 			
 				<textarea name='comment'></textarea>
 				<input type='hidden' name='user_id' value='{$user.user_id}' />
 				<input type='submit' value='Save' />
 			</form>
 			</td>
 			
 		</tr>
 		
 	</table>
 </div>
<div id="salary" style='width: 900px;'>
<h2>Salary Dashboard</h2>
<table style='width: 850px;'>
	<tr>
		<td>Basic Compensation</td>
		<td><input type='text' name='basic' /></td>
	</tr>
	<tr>
		<td>EPF</td>
		<td><input type='text' name='epf' /></td>
	</tr>
	<tr>
		<td>ETF</td>
		<td><input type='text' name='etf' /></td>
	</tr>
	<tr>
		<td>Pay</td>
		<td><input type='text' name='pay' /></td>
	</tr>
</table>
<h3>Bank Details</h3>
<h3>Remitence</h3>
</div>
<div id="leaves" style='900px;'>
 <h2>Leaves Dashboard</h2>
 <table style='width: 850px;'>
 	<tr>
 		<td></td><td>Entitled for 2012</td><td>Leaves Taken</td><td>Pending Approval</td><td>Future Approved</td><td>Remaining</td>
 	</tr>
 	<tr>
 		<td>Annual</td>
 		<td>10</td>
 		<td>10</td>
 		<td>10</td>
 		<td>10</td>
 		<td>10</td>
 	</tr>
	<tr>
 		<td>Medical</td>
 		<td>10</td>
 		<td>10</td>
 		<td>10</td>
 		<td>10</td>
 		<td>10</td>
 	</tr>
 	<tr>
 		<td>Authorized Lieu</td>
 		<td>10</td>
 		<td>10</td>
 		<td>10</td>
 		<td>10</td>
 		<td>10</td>
 	</tr>
 </table>
 <form method='post' action='/User/leave'>
 <table>
 	<tr>
 		<td>Leave Type</td>
 		<td><select name='leave_type_id'>
 			<option value='1'>Personal</option>
 			<option value='2'>Medical</option>
 		</select>
 		</td>
 	</tr>
 	<tr>
 		<td>From Date</td>
 		<td><input type='text' name='leave_from' /></td>
 	</tr>
	 	<tr>
 		<td>To Date</td>
 		<td><input type='text' name='leave_to' /></td>
 	</tr>
	 	<tr>
 		<td>Total Number of Days</td>
 		<td><input type='text' name='total_days' /></td>
 	</tr>
	 	<tr>
 		<td>Comments</td>
 		<td><textarea name='comments'></textarea>
 		</td>
 	</tr>
	<tr>
 		<td>Contact Number</td>
 		<td><input type='text' name='contact_number' />
 		</td>
 	</tr>
 	<tr>
 		<td colspan='2'>
 		<input type='hidden' name='user_id' value='{$user.user_id}' />
 		<input type='submit' value='Leave Request' /></td>
 	</tr>
 </table>
 
 <table>
 	<tr>
 		<td>Leave Type</td><td>Leave From</td><td>Leave To</td><td>Status</td><td>Request Date</td>
 	</tr>
 	{foreach from=$leaves item=leave}
 		<tr>
 			<td>{$leave.leave_type_id}</td>
 			<td>{$leave.leave_from}</td>
 			<td>{$leave.leave_to}</td>
 			<td>{$leave.approved_by}</td>
 			<td>{$leave.created_date}</td>
 		</tr>
 	{/foreach}
 </table>
 
 </form>
 </div>
</div>
</div></div>
<script>{literal}
// Create a new YUI instance and populate it with the required modules.
YUI().use('tabview', function (Y) {
	var tabview = new Y.TabView({
        srcNode: '#demo'
    });

    tabview.render();
});{/literal}
</script>
 {include file='home/footer.tpl}