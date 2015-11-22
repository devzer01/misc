/*
 Study Manager business logic validation and function points
 $Id$
 */

var stm_version = '1.1';
var stm_cost_changed = false;

function ShowStudyCostComments(study_id)
{		
	url 	= '/app/Study/DisplayStudyCostLog/study_id/'+study_id;	
   popup(url, "study_cost_log_"+study_id, 850, 400);
}

function check_routed() {
   datasources = document.forms["mainform"].elements["study_datasource_id[]"];
   sum = 0;
   for(i=0; i<datasources.length; i++) {
      if (datasources[i].checked)
         sum = sum + datasources[i].value;
   }
   tracker = document.forms["mainform"].elements["tracker"];
   if ((sum != 1) || tracker.checked) {
      document.forms["mainform"].elements["routed"].disabled = true;
   } else {
      document.forms["mainform"].elements["routed"].disabled = false;
   }
}

function calculate_n_complete(trigger) {
   n_complete = document.forms["frm_attr"].N_COMPLETE.value;
   n_complete_gmi = document.forms["frm_attr"].N_COMPLETE_GMI.value;
   n_complete_non_gmi = document.forms["frm_attr"].N_COMPLETE_NON_GMI.value;
   if (trigger == "N_COMPLETE") {
      n_complete_non_gmi = n_complete - n_complete_gmi;
      if (n_complete_non_gmi < 0) {
         n_complete_gmi = n_complete;
         n_complete_non_gmi = 0;
      }
   } else if (trigger == "N_COMPLETE_GMI") {
      n_complete_non_gmi = n_complete - n_complete_gmi;
      if (n_complete_non_gmi < 0) {
         n_complete = n_complete_gmi;
         n_complete_non_gmi = 0;
      }
   } else {
      n_complete_gmi = n_complete - n_complete_non_gmi;
      if (n_complete_gmi < 0) {
         n_complete = n_complete_non_gmi;
         n_complete_gmi = 0;
      }
   }
   document.forms["frm_attr"].N_COMPLETE.value = n_complete;
   document.forms["frm_attr"].N_COMPLETE_GMI.value = n_complete_gmi;
   document.forms["frm_attr"].N_COMPLETE_NON_GMI.value = n_complete_non_gmi;
}

function toggle_comments_display() {
   i1 = document.getElementById('toggle_comments_1');
   cs = document.getElementsByTagName('tr');
   if (i1.src.indexOf("/images/ext/plus.gif") != -1) {
      src = "/images/ext/minus.gif";
      style = "";
   }else{
      src = "/images/ext/plus.gif";
      style = "none";
   }
   i1.src = src;
   for(i=0; i<cs.length; i++) {
      if (cs[i].id.indexOf("comment_id_") != -1) {
         id = cs[i].id.substr(cs[i].id.lastIndexOf("_")+1);
         document.getElementById('comment_pic_'+id).src = src;
         cs[i].style.display = style;
      }
   }
}

function toggle_comments_display_hb() {
   a = document.getElementById('toggle_comments');
   cs = document.getElementsByTagName('tr');
   if (a.innerHTML == "expand all") {
      html = "collapse";
      style = "";
   }else{
      html = "expand";
      style = "none";
   }
   a.innerHTML = html+" all";
   for(i=0; i<cs.length; i++) {
      if (cs[i].id.indexOf("comment_id_") != -1) {
         id = cs[i].id.substr(cs[i].id.lastIndexOf("_")+1);
         document.getElementById('toggle_comment_id_'+id).innerHTML = html;
         cs[i].style.display = style;
      }
   }
}

function toggle_comment_display(id) {
   a = document.getElementById('toggle_comment_id_'+id);
   c = document.getElementById('comment_id_'+id);
   if (a.innerHTML == "expand") {
      a.innerHTML = "collapse";
      c.style.display = "";
   }else{
      a.innerHTML = "expand";
      c.style.display = "none";
   }
}


function ToggleAdvanceSearch()
{
   var advanced = 0;

   for (i=0;i<9;i++) {
      var a_search = document.getElementById('advance_search_'+i);
      if (!a_search){
      	  break;
      }
      if (a_search.style.display == '') {
         a_search.style.display = 'none';
         advanced = 0;
      } else {
         a_search.style.display = '';
         advanced = 1;
      }
   }

   var o_a = document.getElementById('div_advance_search');
   var o_b = document.getElementById('div_basic_search');


   if (advanced == 0) {
      o_a.style.display = '';
      o_b.style.display = 'none';
   } else {
      o_a.style.display = 'none';
      o_b.style.display = '';
   }

   var o_advanced_flag = document.getElementById('advanced_flag');
   o_advanced_flag.value = advanced;

}



function ValidateAccountByName(account_name)
{
   var f = document.forms[0];
   var url = '?action=validate_account&account_name='+account_name;

   window.open(url,'app_bg');
}


//////////////////////////////////////////////////
function get_functional_members(group_id,task_id)
{
   if (group_id != '') {
      url = '?action=get_functional_members&group_id='+group_id+'&task_id='+task_id;
      window.open(url,'app_bg');
   }
}

/////////////////////////////////
function change_task_owner(timeline_id)
{
   url = '?action=change_task_owner&timeline_id='+timeline_id;
   window.open(url,'app_bg');
}

//////////////////////////////////
function set_study_on_hold(f)
{
   var reason = '';

   f.elements.notes.value = prompt('Please enter reason for study on hold');
   f.elements.action.value = 'study_on_hold';
   f.submit();
   return true;
}

/////////////////////////////////////////
function create_template(f)
{
   var study_id     = f.elements.study_id.value;

   //Disregard certian required fields on save_all_sections below.
   var disregard_required_list = new Array('r_spec_change_notes', 'r_sample_plan_notes', 'r_SAMPLE_PLAN_TYPE');
   for (i=0; i<disregard_required_list.length; i++) {
      req = document.getElementById(disregard_required_list[i]);
      if (req) {
         req.name = req.name.replace('r_', 'req_');
      }
   }
   //findout if the timeline is saved
   //if the attributes are non blank
   if (save_all_sections(f)) {
      setTimeout("create_template_page("+study_id+")",1000);
   }

   return true;
}

function check_sample(f) {
	sum = 0;
   for(i=0; i<f.elements["study_datasource_id[]"].length; i++) {
		if (f.elements["study_datasource_id[]"][i].checked) {
			sum = sum + parseInt(f.elements["study_datasource_id[]"][i].value);
		}
	}
	ok = true;
   if (f.datasource_sum.value == 1 && sum > 1) {
		ok = confirm("Setting a sample source different than GTM will turn off Routing. Are you sure???");
   }
	if (ok) {
		f.submit();
	}
}

function check_specs(f) {
 //	alert(f.ISTRACKER.checked);
 //	alert(f.ISTRACKER_OLD.value);
 	if (f.ISTRACKER.checked && f.ISTRACKER_OLD.value=="") {
      if (!confirm("Setting the study as tracker will turn off Routing. Are you sure???")) {
         return false;
      }
      f.ROUTED_CLIENT_APPROVED.value = "off";
      f.ROUTED_SB_APPROVED.value = "off";
 	}
 	 	
 	if( i_check(f.PVALUE,1) ||  i_check(f.QVALUE,1)){
 		var errStr = "";
 		
 		if(i_check(f.PVALUE,1) && i_check(f.QVALUE,1))
 			errStr += "Study Estimated Value and Study Quoted Value";
 		else if(i_check(f.PVALUE,1))
 			errStr += "Study Estimated Value";
 		else if(i_check(f.QVALUE,1))
 			errStr += "Study Quoted Value";	
 			
 		alert(errStr + " should be numeric.\n\nPlease enter a value in the following format: ####.## Please remove letters, dollar signs and commas.");
 		return false;
 	}
 	
	check(f);
}


////////////////////////////////////////
function save_all(f)
{
   var study_id     = f.elements.study_id.value;
   var time_zone_id = f.elements.time_zone_id.value;

   if(save_all_sections(f)) {
      setTimeout("reload_study_page("+study_id+","+time_zone_id+")",1000);
      return true;
   }
   return false;
}

Array.prototype.in_array = function ( obj ) {
	var len = this.length;
	for ( var x = 0 ; x <= len ; x++ ) {
		if ( this[x] == obj ) return true;
	}
	return false;
}

///////////////////////////////////////
function save_all_sections(f)
{
   var disregard_forms = new Array('frm_ticket_comm', 'frm_client_contact', 'frm_vendor_contact');

   for (i=0;i<document.forms.length;i++)
   {
      if (disregard_forms.in_array(document.forms[i].name)) {
         continue;
      }
      document.forms[i].target = 'app_bg_'+i;
      //dont validate if we are dealing with /frm_stm_status/frm_stm_spec/
      if (document.forms[i].name == 'frm_stm_status' || document.forms[i].name == 'frm_stm_spec'){
         for (x=0;x<document.forms[i].elements.length;x++)
         {
            if (document.forms[i].elements[x].type == 'textarea' && document.forms[i].elements[x].value != '') {
               //alert(document.forms[i].action);
               document.forms[i].submit();
            }
         }
      } else if (document.forms[i].name == 'mainform') {
         if (validateTimelineInput(document.forms[i])) {
            check(document.forms[i]);
         } else {
            return false;
         }
      } else if (document.forms[i].name == 'frm_action') {
         continue;
      } else {
         if (!check(document.forms[i])) {
            return false;
         }
      }
   }
   return true;
}

////////////////////////////////////////
function create_template_page(study_id)
{
   document.location.href = "?action=vw_template&study_id="+study_id;
   return true;
}

/////////////////////////////////////////
function reload_study_page(study_id,time_zone_id)
{
   document.location.href = "?action=vw_detail&study_id="+study_id+"&time_zone_id="+time_zone_id;
   return true;
}

/////////////////////////////////////////
function set_study_off_hold(f)
{
   var reason = '';

   f.elements.notes.value = prompt('Please enter reason for study off hold');
   f.elements.action.value = 'study_off_hold';
   f.submit();
   return true;
}

//////////////////////////////////////////
function set_study_close(f)
{
   var reason = '';

   f.elements.notes.value = prompt('Please enter reason for closing study');
   f.elements.action.value = 'study_close';
   f.submit();
   return true;
}

//////////////////////////////////////////
function set_study_open(f)
{
   var reason = '';

   f.elements.notes.value = prompt('Please enter reason for study open');
   f.elements.action.value = 'study_open';
   f.submit();
   return true;
}

//////////////////////////////////////////
function delete_study(f)
{
	if(confirm('Are you sure you want to delete this study ?')) {
		f.elements.action.value = 'do_del';
		f.submit();
		return true;
	}
	return false;
}

//////////////////////////////////////////
function submitPage(filter,f)
{
   f.filter.value=filter;
   f.submit();
   return true;
}

//////////////////////////////////////////
function taskCompleted(timeline_id,f)
{
   return true;
   if(confirm('Do you like to make changes to the subsequent steps ?') && f.elements.task_completed.value == 0) {
      f.elements.task_completed.value = timeline_id;
   }
   return true;
}

//////////////////////////////////////////
function changeFilter(f,study_status_id)
{
   f.elements.study_status_id.value = study_status_id;
   f.submit();
}

/////////////////////////////////////////
// add .00 suffix to a number
////////////////////////////////////////
function format_currency(t)
{
   var re = /[0-9]+\.[0-9]{2}/;
   if(t.value.match(re)){
      return 0;
   } else if (t.value == '') {
      return 0;
   }

   if (t.value == Math.round(t.value)){
      t.value = t.value+'.00';
   } else if (t.value*10 == Math.round(t.value*10)){
      t.value = t.value+'0';
   }
   return 1;
}

//////////////////////////////////////
function annoy_user()
{
   var objects = document.all;
   btntext = "<input type=button value='save all'>";
   var oBtn = document.createElement(btntext);
   if (objects != null)
   {
     for(i=1;i < objects.length; i++) {
         if (objects.item(i).tagName == 'INPUT')
         {
            if (objects.item(i).type == 'button') {
               var button = objects.item(i);
               button.style.position = 'absolute';
               button.style.left = (Math.random() * 800)+'px';
               button.style.top = (Math.random() * 600)+'px';
            }
         }
      }
   }
   my_test.appendChild(oBtn);
   setTimeout("annoy_user()",10);
}

///////////////////////////////////////
function validate_specification_input(f)
{
   var f = document.forms.frm_attr;
   var re = /DR_/;
   var ra = /DO_/;
   var msg = '';

   var spec_lock = document.getElementById('spec_lock');

   if (spec_lock.value == 1) {
      return 1;
   }

   for (i=0;i<f.elements.length;i++) {
      if (f.elements[i].type == 'hidden' && f.elements[i].name.match(re)) {

         var field = f.elements[i].name.substring(3);
         var obj = eval('f.elements.'+field);

         if (obj.value == '') {
            obj.style.backgroundColor = 'red';
            msg += f.elements[i].value+'\n';
         } else {
            obj.style.backgroundColor = 'white';
         }

      } else if (f.elements[i].type == 'hidden' && f.elements[i].name.match(ra)) {
         //validating optional fields
         var ref_string = f.elements[i].name.substring(3);
         var names = ref_string.split("_OR_");
         var objects = Array();
         var validated = 0;

         for (x=0;x<names.length;x++) {
            objects[x] = eval('f.elements.'+names[x]);

            objects[x].style.backgroundColor = 'white';
            if (objects[x].value != ''){
               validated = 1;
            }
         }

         if (!validated) {
            for (x=0;x<objects.length;x++)
               objects[x].style.backgroundColor = 'red';
            msg += f.elements[i].value+'\n';
         }
      }
   }

   if (msg != '') {
      alert('Following Items in Study Specification Are Required Before Saving Timeline\n\n'+msg);
      return 0;
   }

   f.target = 'app_bg_0';
   f.submit();

   return 1;

}

///////////////////////////////////////
function validateTimelineInput(f)
{
   re = new RegExp('task_[0-9]+');
   n_re = new RegExp('[0-9]+');
   p_re = new RegExp('^primary_task_');
   var return_value = 1;
   var error_message = '';
   var oPrevDate = null;
   var is_timeline_created = (document.mainform.timeline_created.value == 'Y');

   for(var i=0;i<f.elements.length;i++) {
      if (f.elements[i].name.match(re) && f.elements[i].type == 'checkbox') {

         //describe our objectss here
         var item_number   = f.elements[i].name.match(n_re);
         var item_name     = (document.getElementById("study_task_description_"+item_number)).value;
         var functional_group_item = document.getElementById("functional_group_"+item_number);
         var due_datetime  = document.getElementById('due_datetime_'+item_number);


         if(f.elements[i].checked) {
            //check if the duration is blank
            if(due_datetime.value == '') {
               due_datetime.style.backgroundColor = 'red';
               error_message += "Due Date/Time is required for "+item_name+"\n";
            } else {
               //check if duration is a number
               number_re = new RegExp('^[0-9]{4}\-[0-9]{2}\-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$');
               if(!(due_datetime.value.match(number_re))) {
                  due_datetime.style.backgroundColor = 'red';
                  error_message += "Date/Time must be in valid format (YYYY-mm-dd HH:mm:ss) for "+item_name+"\n";
               } else {
                  //reset background change
                  due_datetime.style.color = 'black';
                  due_datetime.style.backgroundColor = 'white';
               }
            }

            //functional group drop down list
            if(functional_group_item.value == '') {
               functional_group_item.style.backgroundColor = 'red';
               error_message += "Functional Group is required for "+item_name+"\n";
            } else {
           	   functional_group_item.style.color = 'black';
               functional_group_item.style.backgroundColor = 'white';
            }
         } else {
            //the checkbox was unchecked so we are reseting the background colors
            due_datetime.style.color = 'black';
            due_datetime.style.backgroundColor = 'white';
            functional_group_item.style.color = 'black';
            functional_group_item.style.backgroundColor = 'white';
         }
         continue;
      }

      if(f.elements[i].name.match(p_re) && f.elements[i].type == 'hidden') {
         //validating primary task time gaps
         var item_number  = f.elements[i].name.match(n_re);
         var due_datetime = GetFirstElementByName('due_datetime_'+item_number);
         var curest_datetime = GetFirstElementByName('curest_datetime_'+item_number);
         var comp_datetime = GetFirstElementByName('comp_datetime_'+item_number);
         var item_name    = (GetFirstElementByName("study_task_description_"+item_number)).value;

         // see if the task is completed.  A task is completed is the COMPLETE checkbox is selected or there is no COMPLETE checkbox fo the task
         var is_completed = true;
         var task_completed = GetFirstElementByName('task_complete_'+item_number);
         if ((task_completed && !task_completed.checked) || !is_timeline_created){
     		is_completed  = false;
         }

        var oDueDate = ElementToDate(due_datetime);
		var oCurEstDate = ElementToDate(curest_datetime);
		var oCompDate = ElementToDate(comp_datetime);

         if (oPrevDate && due_datetime.value != '') {
            //check if the current date time is greater than the previous

            var elapse_time = oDueDate.getTime() - oPrevDate.getTime();
            if (elapse_time < 0) {
               due_datetime.style.backgroundColor = 'red';
               error_message += "Due date for '"+item_name+"' ("+oCurEstDate.toString()+") must not occur prior to previous task ("+oPrevDate.toString()+")\n";

               var coll_functional_edit = document.getElementsByName('edit_study_task_'+item_number);
               if (coll_functional_edit.length){
	              showDueDateInput(item_number);
               	  coll_functional_edit.item(0).value = 1;
               }

            } else {
               due_datetime.style.color = 'black';
               due_datetime.style.backgroundColor = 'white';
            }
         }

         oPrevDate = oDueDate;
         if (oCurEstDate < oPrevDate){ oPrevDate = oCurEstDate; }
         if (is_completed && (oCompDate < oPrevDate)){ oPrevDate = oCompDate; }

         continue;
      }
   }

   if(error_message != '') {
      alert("=== FOLLOWING ERRORS FOUND IN YOUR INPUT ===\n\n"+error_message);
      return 0;
   }
   return 1;
}

///////////////////////////////////////////////////////
function can_save_specs()
{
   var spec_lock = document.getElementById('spec_lock');

   if(spec_lock.value == 1) {
      alert('*** Specification changes are not allowed *** \nPlease Use Specification Change Tracking to Make Your Changes');
      return 0;
   }
   return 1;
}

function confirm_reset_specs(form)
{
   if (document.getElementById('spec_lock').value==1)
      alert('The study specifications are locked !!!');
   else {
      if (confirm('Are you sure you want to clear all specifications ???')) {
         document.getElementById('reset').value=1;
         form.submit();
      }
   }
}

////////////////////////////////////////////////////////
function show_templates(f)
{

   var oTemplateList = document.getElementById('tr_templates');
   if (f.elements.study_type_id.value == 0) {
      oTemplateList.style.display = '';
      document.getElementById('tr_nontemplate1').style.display='none';
      document.getElementById('tr_nontemplate2').style.display='none';
      return true;
   }else{
      oTemplateList.style.display = 'none';
      document.getElementById('tr_nontemplate1').style.display='';
      document.getElementById('tr_nontemplate2').style.display='';
   	return true;
   }
   return false;
}

function select_template(value) {

   country_code = value.substr(value.indexOf("//")+2);
   if (country_code=="")
      country_code="USA";
   countries = document.getElementById('study_country_code');
   for(i=0; i<countries.length; i++) {
      if (countries.options[i].value==country_code)
         selected_index=i;
   }
   countries.selectedIndex = selected_index;
}

////////////////////////////////////////////////////////
function showDueDateInput(number)
{
   var oTxt = document.getElementById('div_due_date_text_'+number);
   var oInput = document.getElementById('div_due_date_'+number);

   oTxt.style.display = 'none';
   oInput.style.display = 'block';

}

/////////////////////////////////////////////////////////
function GetDateArray(date_time)
{
   var ar_date_time = new Array();
   var ar_slice     = new Array(4,7,10,13,16,19);
   var start        = 0;

   for (var i=0;i<ar_slice.length;i++)
   {
      ar_date_time[i] = date_time.slice(start,ar_slice[i]);
      start = ar_slice[i] + 1;
   }

   return ar_date_time;
}

/////////////////////////////////////////////////////////
function ElementToDate(element)
{
   var slices = new Array();
   var ar_slice     = new Array(4,7,10,13,16,19);
   var start        = 0;

	if (!element || element.value == ''){
		return new Date();
	}

   for (var i=0;i<ar_slice.length;i++)
   {
      slices[i] = element.value.slice(start,ar_slice[i]);
      start = ar_slice[i] + 1;
   }

   return new Date(slices[0], slices[1]-1, slices[2], slices[3], slices[4], slices[5]);
}






/////////////////////////////////////////////////////////
function change_task_detail(task_id)
{
	
	var oFunctionalGroup = document.getElementById('functional_group_'+task_id);
	oFunctionalGroup.style.display = 'block';

	var oDueDateText = document.getElementById('div_due_date_text_'+task_id);
	oDueDateText.style.display = 'none';

	var oDueDate = document.getElementById('div_due_date_'+task_id);
	oDueDate.style.display = 'block';

	var oFunctionalEdit = document.getElementById('edit_study_task_'+task_id);
	oFunctionalEdit.value = 1;

	return 1;
}

/////////////////////////////////////////////////////////
function change_template_task_detail(task_id)
{
   var oFunctionalGroup = document.getElementById('functional_group_'+task_id);
	oFunctionalGroup.style.display = 'block';

	var oFunctionalEdit = document.getElementById('edit_study_task_'+task_id);
	oFunctionalEdit.value = 1;

	return 1;
}

/////////////////////////////////////////////////////////
function update_template_task_detail(task_id)
{
   var oFunctionalGroup = document.getElementById('functional_group_'+task_id);
	oFunctionalGroup.style.display = 'block';

	var oFunctionalText = document.getElementById('div_functional_group_'+task_id);
	oFunctionalText.style.display = 'none';

	var oFunctionalEdit = document.getElementById('edit_study_task_'+task_id);
	oFunctionalEdit.value = 1;

	var template_id = document.getElementById('template_id');
	var task_owner  = document.getElementById('functional_user_login_'+task_id);

	if (!template_id) {
	  url = '?action=change_task_owner&timeline_id='+task_id+'&task_owner='+task_owner.value;
	} else {
	  url = '?action=change_task_owner&timeline_id='+task_id+'&template_id='+template_id.value+'&task_owner='+task_owner.value;
	}
	window.open(url,'app_bg');
	return 1;

}

/////////////////////////////////////////////////////////
function update_task_detail(task_id)
{
	var oFunctionalGroup = document.getElementById('functional_group_'+task_id);
	oFunctionalGroup.style.display = 'block';

	var oFunctionalText = document.getElementById('div_functional_group_'+task_id);
	oFunctionalText.style.display = 'none';

	var oFunctionalEdit = document.getElementById('edit_study_task_'+task_id);
	oFunctionalEdit.value = 1;

	var oDueDateText = document.getElementById('div_due_date_text_'+task_id);
	oDueDateText.style.display = 'none';

	var oDueDate = document.getElementById('div_due_date_'+task_id);
	oDueDate.style.display = 'block';

	var task_owner  = document.getElementById('functional_user_login_'+task_id);

	url = '?action=change_task_owner&timeline_id='+task_id+'&task_owner='+task_owner.value;
   window.open(url,'app_bg');

	return 1;
}

////////////////////////////////////////////////////////////////
function cancel_disposition(f)
{
   var oTrText = document.getElementById('tr_txt_notes');
   var oTrDisp = document.getElementById('tr_disposition');

   oTrText.style.display = 'none';

   oTrDisp.style.display = 'block';

   f.elements.action.value = '';

   return true;
}


////////////////////////////////////////////////////////
function show_log_text(f,action)
{

   var display_message = get_display_message(action);

   if (!newConfirm("Study Manager "+stm_version,"Are you sure, You want to "+display_message+"?",1,1,0)) {
	  return false;
   }

   var oTrText = document.getElementById('tr_txt_notes');
   var oTxt    = document.getElementById('txt_notes');
   var oTrDisp = document.getElementById('tr_disposition');
   var oDvMsg  = document.getElementById('dv_message');

   //display the table row
   oTrText.style.display = 'block';

   //hide the rest of the buttons
   oTrDisp.style.display = 'none';

   //set focus to the text area
   oTxt.focus();

   //set the form action
   f.elements.action.value = action;

   oDvMsg.innerText = display_message;

   return true;
}

///////////////////////////////////////////////////////////
function get_display_message(action)
{
   var msg = '';

   switch (action)
   {
      case 'reset_timeline':
         msg = 'Resetting Timeline';
         break;
      case 'study_on_hold':
         msg = 'Set Study On Hold';
         break;
      case 'study_off_hold':
         msg = 'Set Study Off Hold';
         break;
      case 'do_del':
         msg = 'Delete Study';
         break;
      case 'study_close':
         msg = 'Close Study';
         break;
      case 'study_open':
         msg = 'Open Study';
         break;
      default:
         break;
   }

   return msg;

}

////////////////////////////////////////////////////////////
function reset_timeline()
{
   if (newConfirm("Study Manager "+stm_version,"Are you sure, Reseting timeline will erase the current progress ?",1,1,0)) {
	  url = '?action=reset_timeline&study_id='+study_id;
	  window.open(url,'app_bg');
	  return 1;
   }
   return 0;
}

///////////////////////////////////////////////////////////
function display_sample_source(study_id)
{
	url = '?action=display_sample_source&study_id='+study_id;
	spec = 'width=250,height=300,scrollbars=yes,resizable=yes';
	window.open(url,'app_popup',spec);
	return 1;
}

///////////////////////////////////////////////////////////
function display_sample_types(study_id)
{
	url = '?action=display_sample_types&study_id='+study_id;
	spec = 'width=250,height=300,scrollbars=yes,resizable=yes';
	window.open(url,'app_popup',spec);
	return 1;
}

function display_products(study_id) {
   url = "?action=display_products&study_id="+study_id;
	spec = 'width=250,height=300,scrollbars=yes,resizable=yes';
	window.open(url,'app_popup',spec);
	return 1;
}

function display_vendors(study_id) {
   url = "?action=display_vendors&study_id="+study_id;
	spec = 'width=500,height=300,scrollbars=yes,resizable=yes';
	window.open(url,'app_popup',spec);
	return 1;
}

function display_country(study_id) {
   url = "?action=display_country&study_id="+study_id;
	spec = 'width=250,height=300,scrollbars=yes,resizable=yes';
	window.open(url,'app_popup',spec);
	return 1;
}

function display_router(study_id) {
   url = "?action=display_router&study_id="+study_id;
   spec = "width=350,height=300,scrollbars=yes,resizable=yes";
   window.open(url, 'app_popup', spec);
   return 1;
}

///////////////////////////////////////////////////////////
function display_study_type(study_id)
{
	url = '?action=display_study_type&study_id='+study_id;
	spec = 'width=250,height=300,scrollbars=yes,resizable=yes';
	window.open(url,'app_popup',spec);
	return 1;
}

///////////////////////////////////////////////////////////
function display_proposal(study_id)
{
	url = '?action=modify_study_proposal&study_id='+study_id;
	spec = 'width=250,height=300,scrollbars=yes,resizable=yes';
	window.open(url,'app_popup',spec);
	return 1;
}

function display_spec_copy(study_id)
{
	url = '?action=copy_study_spec&study_id='+study_id;
	spec = 'width=550,height=300,scrollbars=yes,resizable=yes';
	window.open(url,'app_popup',spec);
	return 1;
}


///////////////////////////////////////////////////////////
function copy_notes(f)
{
   //copy the notes from textarea to our hidden field
   f.elements.notes.value = f.elements.txt_notes.value;
   return true;
}

///////////////////////////////////////////////////////////
function display_study_user(study_id)
{
	url = '?action=display_study_user&study_id='+study_id;
	spec = 'width=500,height=500,scrollbars=yes,resizable=yes';
	window.open(url,'app_popup_study_user',spec);
	return 1;
}

function delete_vendor(study_account_id) {
	url = '?action=delete_vendor&study_account_id='+study_account_id;
	spec = 'width=500,height=500,scrollbars=yes,resizable=yes';
	window.open(url,'app_popup_study_user',spec);
	return 1;
}

////////////////////////////////////////////////////////////
function show_error_message()
{
   var oErrorMessage = document.getElementById('error_message');
   if (!oErrorMessage)
      return false;
   if (oErrorMessage.value != '')
   {
      alert(oErrorMessage.value);
      return false;
   }
   return true;
}

/////////////////////////////////////////////////////////////
function toggle_timeline_alert(alert_to,timeline_id)
{
   re = /on\./;
   switch (alert_to) {
      case 'MOBILE':
         oImg = document.getElementById('img_cellphone_'+timeline_id);
         if (oImg.src.match(re)) {
            oImg.src = '/images/cellphone_off.gif';
         } else {
            oImg.src = '/images/cellphone_on.gif';
         }
         break;
      case 'EMAIL':
         oImg = document.getElementById('img_email_'+timeline_id);
         if (oImg.src.match(re)) {
            oImg.src = '/images/email_off.gif';
         } else {
            oImg.src = '/images/email_on.gif';
         }
         break;
      case 'JABBER':
         oImg = document.getElementById('img_jabber_'+timeline_id);
         if (oImg.src.match(re)) {
            oImg.src = '/images/tipic_off.gif';
         } else {
            oImg.src = '/images/tipic_on.gif';
         }
         break;
      default:
         break;
   }

   url = '?action=toggle_timeline_alert&alert_to='+alert_to+'&timeline_id='+timeline_id;
   window.open(url,'app_bg');


   return 1;
}

////////////////////////////////////////////////////////////////
function toggle_timeline_log(timeline_id)
{
   var oTimelineLog = document.getElementById('timeline_log_'+timeline_id);
   if (oTimelineLog.style.display == 'none') {
      oTimelineLog.style.display = '';
   } else {
      oTimelineLog.style.display = 'none';
   }
}

/////////////////////////////////////////////////////////////////
function timeline_watchers(timeline_id)
{
   var study_id = document.getElementById('study_id');

   url = '?action=display_task_watchers&study_timeline_id='+timeline_id+'&study_id='+study_id.value;
   spec = 'width=500,height=500,scrollbars=yes,resizable=yes';
   window.open(url,'app_popup_timeline_watchers',spec);
}

/////////////////////////////////////////////////////////////////
function display_comment_response(comment_id)
{
   var oResponse = document.getElementById('tr_study_comment_'+comment_id);
   if (oResponse.style.display == 'none') {
      oResponse.style.display = 'block';
   } else {
      oResponse.style.display = 'none';
   }
   return 1;
}

////////////////////////////////////////////////////////////////////
function display_partner_contact(partner_id,study_id)
{
   var url = '?action=display_partner_contact&partner_id='+partner_id+'&study_id='+study_id;
   var spec = 'width=500,height=500,scrollbars=yes,resizable=yes';
   wd = window.open(url,'app_popup_partner_contact',spec);
   wd.focus();
}

function display_vendor_contact(study_account_id, study_id) {
   var url = '?action=display_vendor_contact&study_account_id='+study_account_id+'&study_id='+study_id;
   var spec = 'width=500,height=500,scrollbars=yes,resizable=yes';
   wd = window.open(url,'app_popup_vendor_contact',spec);
   wd.focus();
}

/////////////////////////////////////////////////////////////////////
function toggle_contact_status(f)
{
   if (!is_checked(f)) {
      alert('Toggle Who ?');
      return false;
   }

   f.elements.action.value = 'toggle_contact_status';
   f.submit();
}

function toggle_vendor_contact_status(f) {
   if (!is_checked(f)) {
      alert('Toggle Who ?');
      return false;
   }

   f.elements.action.value = 'toggle_vendor_contact_status';
   f.submit();
}

/////////////////////////////////////////////////////////////////////
function is_checked(f)
{
   for (i=0;i<f.elements.length;i++) {
      if (f.elements[i].type == 'checkbox' && f.elements[i].checked)
         return true;
   }
   return false;
}

////////////////////////////////////////////////////////////////////
function expand_task_log()
{
   var expand_task_log = document.getElementById('expand_task_log');
   if (!expand_task_log)
      return false;
   if (expand_task_log.value == '')
      return false;

   toggle_timeline_log(expand_task_log.value);

   var timeline_comment = document.getElementById('timeline_comment_'+expand_task_log.value);
   timeline_comment.focus();
   return true;
}

/////////////////////////////////////////////////////////////////////
function send_schedule(study_id)
{
   url = '?action=send_schedule&study_id='+study_id;
   var spec = 'width=500,height=500,scrollbars=yes,resizable=yes';
   wd = window.open(url,'app_popup_send_schedule',spec);
   wd.focus();
}

///////////////////////////////////////////////////////////
function display_job_number(study_id)
{
	url = '?action=display_job_number&study_id='+study_id;
	spec = 'width=300,height=200,scrollbars=yes,resizable=yes';
	window.open(url,'app_popup_job_number',spec);
	return 1;
}

///////////////////////////////////////////////////////////
function display_po_number(study_id)
{
	url = '?action=display_po_number&study_id='+study_id;
	spec = 'width=300,height=200,scrollbars=yes,resizable=yes';
	window.open(url,'app_popup_po_number',spec);
	return 1;
}

///////////////////////////////////////////////////////////
function display_on_response_quota_title(study_id, quota_id)
{
   url = '?action=display_on_response_quota_title&study_id='+study_id+'&study_quota_on_response_id='+quota_id;
	spec = 'width=300,height=200,scrollbars=yes,resizable=yes';
	window.open(url,'app_popup_on_response_quota_title',spec);
	return 1;
}

////////////////////////////////////////////////////////////
function upload_file(f)
{
//   var spec_lock = document.getElementById('spec_lock');
//   if (spec_lock.value == 1) {
//      alert('Changes to Specification is locked, only your upload files will be uploaded');
//      f.file_upload.value = 1;
//   }

   f.submit();
//   check(f);
}

function view_attr_history(id) {
   url = '?action=view_attr_history&study_attr_id='+id;
   specs = 'width=300,height=200,resizable=yes,scrollbars=yes'
   window.open(url, 'attr_history', specs);
}

function CheckTickBoxes() {
	var cont = document.version_diff.version;
	var ctr = 0;
	for(i=0; i < cont.length; i++) {
		if(cont[i].checked == true) {
			ctr = ctr + 1;
		}
	}
		
	if(ctr != 2) {
		alert("Please check two versions to compare");
		return false;
	}
	return true;
}

function HideModify() {
	var modi = document.getElementById("quota_desc_modify");
	modi.innerHTML = "";
		
	var control = document.getElementById("quota_desc_text");
	control.innerHTML = "";
	document.getElementById("QUOTA_DESC").style.display = '';
}
	
function ViewQuotaHistory(study_id) {
   url = '?action=view_quota_history&study_id=' + study_id;
   specs = 'width=400,height=300,resizable=yes,scrollbars=yes'
   window.open(url, 'quota_history', specs);
}
/////////////////////////////////////////
function save_study_contact_type(f, contact_id)
{
   var contact_type_id = f.value;

   if (contact_type_id != '') {
      url = '?action=save_study_contact_type&contact_type_id=' + contact_type_id + '&contact_id=' + contact_id;
      window.open(url,'app_bg');
   }
}

function save_study_vendor_contact_type(f, contact_id) {
   var contact_type_id = f.value;

   if (contact_type_id != '') {
      url = '?action=save_study_vendor_contact_type&contact_type_id=' + contact_type_id + '&study_account_contact_id=' + contact_id;
      window.open(url);
   }
}

function select_tcm_contacts(name, value) {
   len = name.search("_");
   t = name.substr(0, len);
   checkboxes = document.getElementsByTagName("input");
   for(i=0; i<checkboxes.length; i++) {
      if (checkboxes[i].name.substr(0, len+13) == t+'_tcm_contact_') {
         checkboxes[i].checked = value;
      }
   }
}

function recalculate_total() {
   inputs = document.getElementsByTagName("input");
   total_proposed = 0;
   total_actual = 0;
   for(i=0; i<inputs.length; i++) {
      if (inputs[i].name.substring(0, 13)=="proposed_rate") {
         total_proposed = total_proposed + inputs[i].value.replace(new RegExp("[^0-9.]"), "") * document.getElementById("proposed_quantity"+inputs[i].name.substring(13)).value.replace(new RegExp("[^0-9.]"), "");
      }
      if (inputs[i].name.substring(0, 11)=="actual_rate") {
         total_actual = total_actual + inputs[i].value.replace(new RegExp("[^0-9.]"), "") * document.getElementById("actual_quantity"+inputs[i].name.substring(11)).value.replace(new RegExp("[^0-9.]"), "");
      }
   }
   document.getElementById("total_proposed").innerHTML = "$"+total_proposed.toFixed(2);
   document.getElementById("total_actual").innerHTML = "$"+total_actual.toFixed(2);
}

function update_cost(postfix) {	
   if (postfix != '') {
      if (document.getElementById('study_cost_comment'+postfix).value=="") {
         comment = prompt("Changing this field requires a mandatory explanation !!!", '');
         while ((comment == null) || (comment == "")) {
            comment = prompt("Changing this field requires a mandatory explanation !!!", '');
         }
         document.getElementById('study_cost_comment'+postfix).value = comment;
      }
   }
   proposed_cost = document.getElementById('proposed_rate'+postfix).value.replace(new RegExp("[^0-9.]"), "")*document.getElementById('proposed_quantity'+postfix).value.replace(new RegExp("[^0-9.]"), "");
   actual_cost = document.getElementById('actual_rate'+postfix).value.replace(new RegExp("[^0-9.]"), "")*document.getElementById('actual_quantity'+postfix).value.replace(new RegExp("[^0-9.]"), "");
   
   invoice_cost = document.getElementById('invoice_rate'+postfix).value.replace(new RegExp("[^0-9.]"), "")*document.getElementById('invoice_quantity'+postfix).value.replace(new RegExp("[^0-9.]"), "");
   
   document.getElementById('proposed_cost'+postfix).innerHTML = "$"+proposed_cost.toFixed(2);
   document.getElementById('actual_cost'+postfix).innerHTML = "$"+actual_cost.toFixed(2);
   document.getElementById('invoice_cost'+postfix).innerHTML = "$"+invoice_cost.toFixed(2);
   recalculate_total();
}

function change_vendors(vendor_array, default_rate_array, postfix) {
   index = document.getElementById('study_cost_type_id'+postfix).selectedIndex;
   document.getElementById('proposed_rate'+postfix).value = default_rate_array[index];
   document.getElementById('actual_rate'+postfix).value = default_rate_array[index];
   document.getElementById('invoice_rate'+postfix).value = default_rate_array[index];
   
   if (postfix != '') {
       update_cost(postfix);
   }
   vendor_list = document.getElementById('account'+postfix);
   vendor_list.length = vendor_array[index].length+1;
   vendor_list.options[0] = new Option("[--Not specified--]", "");
   for(i=0; i<vendor_array[index].length; i++) {
      str = new String(vendor_array[index][i]);
      name = str.substring(str.indexOf('//')+2);
      vendor_list.options[i+1] = new Option(name, str);
   }
}

function GetFirstElementByName(name)
{
	var collection = document.getElementsByName(name);
	if (collection.length){
		return collection.item(0);
	} else {
		return null;
	}
}


function popup(url, windowName, width, height)
{
   var spec = 'width='+width+',height='+height+',scrollbars=yes,resizable=yes';
   var wd = window.open(url, windowName, spec);
   wd.focus();

}

function TripStudyCostEditFlag()
{
	   stm_cost_changed = true;
	
}

function DoCostChangeAlert()
{
	
 	document.getElementById('study_cost_comment').style.display="";
 	document.getElementById('save_reason').style.display="";
 	document.getElementById('cancle').style.display="";
 	document.getElementById('study_comment_type').style.display="";
 	document.getElementById('save_cost_item').style.display="none";
 	
}

function DoSaveReason()
{
 	document.getElementById('study_cost_comment').style.display="none";
 	document.getElementById('save_reason').style.display="none";
 	document.getElementById('cancle').style.display="none";
 	document.getElementById('study_comment_type').style.display="none";
 	document.getElementById('save_cost_item').style.display="";	
	
}

function DoCancel()
{
 	document.getElementById('study_cost_comment').style.display="none";
 	document.getElementById('save_reason').style.display="none";
 	document.getElementById('cancle').style.display="none";
 	document.getElementById('study_comment_type').style.display="none";
 	document.getElementById('save_cost_item').style.display="";
 	stm_cost_changed = false;	
}

function ControlEditValues(num, set) {
	
	//if the study invoiced blocking all the controls from creating of new study cost
	if(num == null && set == null) {
		if(document.getElementById("study_invoiced").value == 1) {
			document.getElementById("study_cost_type_id").disabled = true;
			document.getElementById("reference_number").disabled = true;
			document.getElementById("account").disabled = true;
			document.getElementById("contact_name").disabled = true;
			document.getElementById("contact_email").disabled = true;
			document.getElementById("proposed_rate").disabled = true;
			document.getElementById("proposed_quantity").disabled = true;
			document.getElementById("actual_rate").disabled = true;
			document.getElementById("actual_quantity").disabled = true;
			document.getElementById("invoice_rate").disabled = true;
			document.getElementById("invoice_quantity").disabled = true;
			document.getElementById("approval").disabled = true;
			document.getElementById("study_cost_file").disabled = true;
			document.getElementById("study_cost_file_title").disabled = true;
			document.getElementById("file_type").disabled = true;
		}
		return;
	}
	//approval list box option is dissabled if the invoice amounts and study files are missing
	if(set == "approval") {
		rateval = document.getElementById("invoice_rate_" + num).value;
		qtyval = document.getElementById("invoice_quantity_" + num).value;
		files_flag = document.getElementById("study_cost_files_flag").value;
		
		if(rateval == '' && qtyval == '' && files_flag == '') {
			document.getElementById('approval_' + num).disabled = true;
		}
		return;
	}
	
	var rate	= set + "_rate_" + num;
	var qty	= set + "_quantity_" + num;
	var ponum	= "ponum_" + num;	
	
	rate 	= document.getElementById(rate);
	qty  	= document.getElementById(qty);
	ponum	= document.getElementById(ponum);

	//if the proposed values are entered and PO number set
	if(rate.value != "" && qty.value != "" && ponum.value != "" && set == 'proposed') {
		rate.disabled=true;
		qty.disabled=true;
	}
	
	//if the study is invoiced 
	if(document.getElementById("study_invoiced").value != "") {
		rate.disabled=true;
		qty.disabled=true;
	}
	
	//if actual values receive focus, invoice box should be enabled
	if(set == "actual") {
		document.getElementById("invoice_rate_" + num).disabled=false;
		document.getElementById("invoice_quantity_" + num).disabled=false;
	}
	
	//lock invoice values if actual cost exists and not approved 
	if(set == "invoice") {
		
		rateval = document.getElementById("actual_rate_" + num).value;
		qtyval = document.getElementById("actual_quantity_" + num).value;
		invoice_status = document.getElementById("old_approval_" + num).value;
		
		if((rateval == '' || qtyval == '') && invoice_status != "Not sure yet") {
			document.getElementById("invoice_rate_" + num).disabled=true;
			document.getElementById("invoice_quantity_" + num).disabled=true;
			document.getElementById("approval_" + num).disabled=true;
		} else {
			document.getElementById("invoice_rate_" + num).disabled=false;
			document.getElementById("invoice_quantity_" + num).disabled=false;
			document.getElementById("approval_" + num).disabled=false;
		}
	}
}


function CheckActualValues(id)
{
	if(document.getElementById('can_edit_invoice_value').value == 0) {	
		if(document.getElementById('actual_rate').value != '' && document.getElementById('actual_quantity').value != ''){
			document.getElementById("invoice_rate").readOnly 		= false;
			document.getElementById("invoice_quantity").readOnly 	= false;
		}else {
			document.getElementById("invoice_rate").readOnly 		= true;
			document.getElementById("invoice_quantity").readOnly 	= true;
			document.getElementById("invoice_quantity").value 		= '';			
		}
	}
}

