function ValidateAccountName(o)
{
   var account_name = o.value;
   var obj_name     = o.name;
   //alert("Obj name : "+obj_name+", Account name : "+account_name);
   url = '?action=validate_account_search&account_name='+account_name+'&obj_name='+obj_name;
   url += '&func_name=ValidateAccountName';
   window.open(url,'app_bg');

}

function ShowStudyCostComments(study_id)
{		
	url 	= '/app/Study/DisplayStudyCostLog/study_id/'+study_id;	
   popup(url, "study_cost_log_"+study_id, 850, 400);	
}

function ResetValidation(obj_name, func_call) {
   //alert("Obj name : "+obj_name+", function call : "+func_call);
   document.getElementById("td_"+obj_name).innerHTML = "<input type='text' name='"+obj_name+"' id='"+obj_name+"' onchange='"+func_call+";' value=''>";
}

function displayAddCommentOptions(type) {
   if (type>100) {
      document.getElementById("AddCommentSecondOptionLine").style.display="";
      document.getElementById("armc_budget_line_item_id").selectedIndex = 0;
   }else{
      document.getElementById("AddCommentSecondOptionLine").style.display="none";
      document.getElementById("armc_budget_line_item_id").selectedIndex = 1;
   }
}

function DisplayUsers(armc_id) {
   url = '?action=display_armc_users&armc_id='+armc_id;
   spec = 'width=500,height=500,scrollbars=yes,resizable=no';
   window.open(url,'app_popup_armc_user',spec);
   return 1;

}

function UpdateUserList(ids, names, index) {
   selected = document.getElementById("role_id_"+index).selectedIndex;
   //alert(selected);
   //alert(ids[selected]);
   //alert(names[selected]);
   dest = document.getElementById("user_id_"+index);
   for (var i=dest.options.length-1; i>=0; i--){
      dest.options[i] = null;
   }
   dest.selectedIndex = -1;
   for(i=0; i<ids[selected].length; i++) {
      dest.options[dest.options.length] = new Option(names[selected][i], ids[selected][i]);
   }
}

function displayAddTypes(ids, names, index) {
   //alert("index:"+index+", id:"+ids[index][0]+", name:"+names[index][0]);
   types = document.getElementById("armc_type");
   switch (index) {
      case 0 :
         document.getElementById("AddTypesOption").style.display='none';
         types.selectedIndex = 0;
         break;
      default :
         document.getElementById("AddTypesOption").style.display='';
         types.length = ids[index].length;
         for(i=0; i<ids[index].length; i++)
            types.options[i]=new Option(names[index][i], ids[index][i]);
         break;
   }
   displayAddOptions("-1");
}

function displayAddOptions(type) {
   switch (type) {
      case "-1" :
         document.getElementById("AddSecondOptionStudy1").style.display='none';
         document.getElementById("AddSecondOptionNonstudy1").style.display = 'none';
         document.getElementById("AddSecondOptionNonstudy2").style.display = 'none';
         document.getElementById("AddSecondOptionStudy1").style.display = 'none';
         document.getElementById("CurrencyOption1").style.display = 'none';
         document.getElementById("dv_valid").style.display = 'none';
         document.getElementById("option_study_close").style.display = 'none';
         break;

      case "1" :
         document.getElementById("option_study_close").style.display = '';
         document.getElementById("study_id").value = "";
         document.getElementById("account_id").selectedIndex = 1;
         document.getElementById("study_name").value = "N/A";
         document.getElementById("AddSecondOptionStudy1").style.display='';
         document.getElementById("dv_valid").style.display = 'none';
         document.getElementById("AddSecondOptionNonstudy1").style.display = 'none';
         document.getElementById("AddSecondOptionNonstudy2").style.display = 'none';
         document.getElementById("CurrencyOption1").style.display = '';
         document.getElementById("close_study_when_invoiced").checked = true;     
         break;
         
      case "11" :
         document.getElementById("account_id").selectedIndex = 1;
         document.getElementById("study_name").value = "N/A";
         document.getElementById("study_id").value = "";
         document.getElementById("AddSecondOptionNonstudy1").style.display = 'none';
         document.getElementById("AddSecondOptionNonstudy2").style.display = 'none';
         document.getElementById("dv_valid").style.display = 'none';
         document.getElementById("AddSecondOptionStudy1").style.display='';
         document.getElementById("CurrencyOption1").style.display = '';
         document.getElementById("option_study_close").style.display = 'none';
         break;

      default :
         document.getElementById("study_id").value = "N/A";
         document.getElementById("account_id").value = "";
         document.getElementById("study_name").value = "";
         document.getElementById("AddSecondOptionStudy1").style.display='none';
         document.getElementById("dv_valid").style.display = 'none';
         document.getElementById("AddSecondOptionNonstudy1").style.display = '';
         document.getElementById("AddSecondOptionNonstudy2").style.display = '';
         document.getElementById("CurrencyOption1").style.display = '';
         document.getElementById("option_study_close").style.display = 'none';
   }
}

function SetCloseStudyStatus(checkbox) 
{
	switch(checkbox.checked) {
		
		case true :
			document.getElementById("close_study_when_invoiced").checked = false;
		    break;
		
		case false :
		    document.getElementById("close_study_when_invoiced").checked = true;
		    break;
	}
}

function displayExchangeRate(rates, index, value) {
   document.getElementById('exchange_rate').value = rates[index];
   document.getElementById('exchange_rate_explanation').innerHTML=' USD for 1 '+value;
   if (value=="USD") {
      document.getElementById('exchange_rate').value = '1';
      document.getElementById('exchange_rate').style.display='none';
      document.getElementById('exchange_rate_explanation').style.display='none';
   }else{
      document.getElementById('exchange_rate').style.display='';
      document.getElementById('exchange_rate_explanation').style.display='';
   }
}

//Function Clear
function Clear(element) {
   document.getElementById(element).value = '';
}

//Function ClearSearch
function ClearARMCSearch(){
   Clear('search_armc_id');
   document.getElementById('fast_search_armc_date').selectedIndex = 1;
   ChangeSearchDates(document.getElementById('fast_search_armc_date').value, 'armc');
   Clear('search_study_id'); Clear('account');
   document.getElementById("search_account_id").selectedIndex = 0;

   document.getElementById('search_type_id').selectedIndex = 0;
   document.getElementById('search_status_id').selectedIndex = 0;
   document.getElementById('search_account_executive_id').selectedIndex = 0;
   document.getElementById('search_account_manager_id').selectedIndex = 0;
   Clear('search_invoice_number');
   Clear('search_invoice_date_start'); Clear('search_invoice_date_end');
   Clear('search_job_number'); Clear('search_po_number'); Clear('search_vendor_po_number');
   document.getElementById('search_merged').selectedIndex = 0;
   document.getElementById('search_month_end').selectedIndex = 0;
}

function toggleAdvancedSearch() {
   if (document.getElementById('advanced_search_0').style.display=='none') {
      document.getElementById('advanced_search_0').style.display='';
      document.getElementById('advanced_search_1').style.display='';
      document.getElementById('advanced_search_2').style.display='';
      document.getElementById('advanced_search_3').style.display='';
      document.getElementById('advanced_search_link').innerHTML = "Basic Search";
   }else{
      document.getElementById('advanced_search_0').style.display='none';
      document.getElementById('advanced_search_1').style.display='none';
      document.getElementById('advanced_search_2').style.display='none';
      document.getElementById('advanced_search_3').style.display='none';
      document.getElementById('advanced_search_link').innerHTML = "Advanced Search";
   }
}

function ClearDateSearch(){
   Clear('report_created_date_start'); Clear('report_created_date_end');
   Clear('report_invoice_date_start'); Clear('report_invoice_date_end');
}

function ToggleGroupDetails(armc_id, detail_ids) {
   if (document.getElementById("group_details_"+armc_id+"_"+detail_ids[1]).style.display == 'none') {
      disp = "";
      document.getElementById("arrow_"+armc_id+":arrow").src = "/images/rollminus.gif";
   }else{
      disp = "none";
      document.getElementById("arrow_"+armc_id+":arrow").src = "/images/rollplus.gif";
   }
   document.getElementById("group_details_"+armc_id+"_top").style.display=disp;
   for (id=1; id<detail_ids.length; id++) {
      document.getElementById("group_details_"+armc_id+"_"+detail_ids[id]).style.display = disp;
   }
   document.getElementById("group_details_"+armc_id+"_bottom").style.display=disp;

}

function CalculateTotals(code, symbol) {
   var total_proposed = 0;
   var total_actual = 0;
   var total_net = 0;
   var total_delta = 0;

   i = 0;
   while (document.getElementById("proposed_quantity_"+i) != null) {
      total_proposed = total_proposed + document.getElementById("proposed_quantity_"+i).value*document.getElementById("proposed_rate_"+i).value;
      total_actual = total_actual + document.getElementById("actual_quantity_"+i).value*document.getElementById("actual_rate_"+i).value
      i = i+1;
   }
   i = 0;
   while (document.getElementById("new_proposed_quantity_"+i) != null) {
      total_proposed = total_proposed + document.getElementById("new_proposed_quantity_"+i).value*document.getElementById("new_proposed_rate_"+i).value;
      total_actual = total_actual + document.getElementById("new_actual_quantity_"+i).value*document.getElementById("new_actual_rate_"+i).value
      i = i+1;
   }
   total_net = total_actual - total_proposed;
   if (total_proposed != 0)
      total_delta = 100 * total_net / total_proposed;

   document.getElementById(code+"_total_proposed").innerHTML = symbol+" "+total_proposed.toFixed(2);
   document.getElementById(code+"_total_actual").innerHTML = symbol+" "+total_actual.toFixed(2);
   document.getElementById(code+"_total_net").innerHTML = symbol+" "+total_net.toFixed(2);
   document.getElementById(code+"_total_delta").innerHTML = total_delta.toFixed(2)+"%";
}

function CalculateLineAmounts(id, name, code, symbol) {
   var regex = /new_/;
   if (name.search(regex) != -1) {
      prefix = 'new_';
   }else{
      prefix = '';
   }

   //alert("Change on prefix : "+prefix+", id : "+id+" initiated by "+name);

   proposed_rate = document.getElementById(prefix+"proposed_rate_"+id).value;
   proposed_quantity = document.getElementById(prefix+"proposed_quantity_"+id).value;
   proposed_amount = proposed_rate * proposed_quantity;
   document.getElementById(code+'_'+prefix+"proposed_amount_"+id).innerHTML = symbol+" "+proposed_amount.toFixed(2);

   actual_rate = document.getElementById(prefix+"actual_rate_"+id).value;
   actual_quantity = document.getElementById(prefix+"actual_quantity_"+id).value;
   actual_amount = actual_rate * actual_quantity;
   document.getElementById(code+'_'+prefix+"actual_amount_"+id).innerHTML = symbol+" "+actual_amount.toFixed(2);

   net = actual_amount - proposed_amount;
   document.getElementById(code+'_'+prefix+"net_"+id).innerHTML = symbol+" "+net.toFixed(2);

   delta = 0;
   if (proposed_amount != 0) {
      delta = net*100 / proposed_amount;
   }
   document.getElementById(code+'_'+prefix+"delta_"+id).innerHTML = delta.toFixed(2)+"%";
   CalculateTotals(code, symbol);
}

function ChangeBudgetLine(arg1, arg2, arg3, arg4, arg5, code, symbol, exchange_rate) {
   var regex = /new_/;
   //alert("Search result : "+arg4.name.search(regex));
   if (arg5.name.search(regex) != -1) {
      //alert("Found new "+arg4.name)
      prefix = 'new_';
   }else{
      //alert("Not found new !!!");
      prefix = '';
   }
   //alert(prefix+"actual_rate_"+arg1);
   quantity = arg3[arg5.selectedIndex];
   rate = arg2[arg5.selectedIndex]/exchange_rate;
   document.getElementById(prefix+"armc_budget_line_item_description_"+arg1).value = arg4[arg5.selectedIndex];
   document.getElementById(prefix+"actual_rate_"+arg1).value = rate.toFixed(3);
   document.getElementById(prefix+"actual_quantity_"+arg1).value = quantity;

   CalculateLineAmounts(arg1, arg5.name, code, symbol);
}

function ChangeContact(index, details, prefix) {

   document.getElementById("td_"+prefix+"contact_address1").innerHTML = details[index][3];
   document.getElementById("td_"+prefix+"contact_address2").innerHTML = details[index][4];
   document.getElementById("td_"+prefix+"contact_city_state_zip").innerHTML = details[index][5]+" / "+details[index][6]+" / "+details[index][7];
   document.getElementById("td_"+prefix+"contact_country").innerHTML = details[index][9];
   document.getElementById("td_"+prefix+"contact_phone").innerHTML = details[index][10];
   document.getElementById("td_"+prefix+"contact_fax").innerHTML = details[index][11];
   document.getElementById("td_"+prefix+"contact_email").innerHTML = details[index][12];

   document.getElementById(prefix+"contact_salutation").value = details[index][0];
   document.getElementById(prefix+"contact_first_name").value = details[index][1];
   document.getElementById(prefix+"contact_last_name").value = details[index][2];
   document.getElementById(prefix+"contact_address1").value = details[index][3];
   document.getElementById(prefix+"contact_address2").value = details[index][4];
   document.getElementById(prefix+"contact_city").value = details[index][5];
   document.getElementById(prefix+"contact_state").value = details[index][6];
   document.getElementById(prefix+"contact_zip").value = details[index][7];
   document.getElementById(prefix+"contact_country_code").value = details[index][8];
   document.getElementById(prefix+"contact_phone").value = details[index][10];
   document.getElementById(prefix+"contact_fax").value = details[index][11];
   document.getElementById(prefix+"contact_email").value = details[index][12];

}

function toggleDetail(section_id)
{
   //alert(section_id);
   var o_tr = document.getElementsByTagName('tr');
   var toggle = 0;

   for (i=0;i < o_tr.length; i++) {
      if (o_tr[i].id == 'dtl_'+section_id) {

         if (o_tr[i].style.display == 'none') {
            o_tr[i].style.display = '';
            toggle = 1;
         } else {
            o_tr[i].style.display = 'none';
            toggle = 0;
         }
      }
   }

   var img = document.getElementById('img_'+section_id+':arrow');

   if (toggle == 0) {
      img.src='/images/rollplus.gif';
   } else {
      img.src='/images/rollminus.gif';
   }
}

function toggleMainWindow(div_id)
{
   var o_tr = document.getElementsByTagName('tr');
   var toggle = 1;

   var img_name = document.getElementById(div_id+'_1:arrow').src;

   if (img_name.indexOf('minus') == -1) {
      toggle = 0;
   }

   for (i=0;i < o_tr.length; i++) {
      if (o_tr[i].id != '') {
         var tr_name = o_tr[i].id.substring(0,4);
         var section_id = o_tr[i].id.substring(4);
         if (tr_name == 'dtl_') {
            if (toggle == 0) {
               o_tr[i].style.display = '';
            } else {
               o_tr[i].style.display = 'none';
            }

            var img = document.getElementById('img_'+section_id+':arrow');

            if (toggle == 1) {
               img.src='/images/rollplus.gif';
            } else {
               img.src='/images/rollminus.gif';
            }
         }
      }
   }

   if (toggle == 0) {
      document.getElementById(div_id+'_1:arrow').src='/images/rollminus.gif';
      document.getElementById(div_id+'_2:arrow').src='/images/rollminus.gif';

   } else {
      document.getElementById(div_id+'_1:arrow').src='/images/rollplus.gif';
      document.getElementById(div_id+'_2:arrow').src='/images/rollplus.gif';
   }
}


function UseRetainer(retainer_value, armc_budget_line_item_def_id) {
   document.getElementById("retainer").style.display='none';
   document.getElementById("new_armc_budget_line_item_def_id_0").value = armc_budget_line_item_def_id;
   document.getElementById("new_actual_quantity_0").value = 1;
   document.getElementById("new_actual_rate_0").value = retainer_value;
   CalculateLineAmounts(0, "new_");
}

function ChangeReason() {
   if (document.getElementById("reason").value=="Other" || document.getElementById("reason").value=="Multiple") {
      document.getElementById("save_param").value = "";
      document.getElementById("reason_description").style.display = "";
      document.getElementById("reason_description").value = "";
      if (document.getElementById("reason").value=="Multiple") {
         document.getElementById("reason_description").value = "Multiple : ";
      }
   }else{
      document.getElementById("save_param").value = document.getElementById("reason").value;
      document.getElementById("reason_description").style.display = "none";
   }
}

function DoStall(reasons) {
   document.getElementById('save_action').value='stall';
   opt = document.getElementById("reason");
   opt.length = reasons.length+3;
   opt.options[0].value="";
   opt.options[0].text="[-- Select a reson for stalling the BR --]";
   for(i=0; i<reasons.length; i++) {
     opt.options[i+1].value = reasons[i];
	  opt.options[i+1].text = reasons[i];
   }
   opt.options[i+1].value = "Multiple";
   opt.options[i+1].text = "Two or more from above";
   opt.options[i+2].value="Other";
   opt.options[i+2].text="Other";
   opt.selectedIndex = 0;
   ChangeReason();
   document.getElementById("reason_tr").style.display="";
   document.getElementById("save_tr").style.display="none";
	//document.getElementById('save_param').value = memo;
	//document.getElementById('save').submit();
}

function DoDelete(reasons) {
   document.getElementById('save_action').value='delete';
   opt = document.getElementById("reason");
   opt.length = reasons.length+2;
   opt.options[0].value="";
   opt.options[0].text="[-- Select a reson for deleting the BR --]";
   for(i=0; i<reasons.length; i++) {
     opt.options[i+1].value = reasons[i];
	  opt.options[i+1].text = reasons[i];
   }
   opt.options[i+1].value="Other";
   opt.options[i+1].text="Other";
   opt.selectedIndex = 0;
   ChangeReason();
   document.getElementById("reason_tr").style.display="";
   document.getElementById("save_tr").style.display="none";
	//document.getElementById('save_param').value = memo;
	//document.getElementById('save').submit();

//	if (confirm('Are you sure you want to delete this Billing Report ???')) {
//	   memo = "";
//	   while (memo == "" && memo!=null) {
//		    memo = prompt('Deleting a BR requires a mandatory explanation !!!', '');
//	   }
//		if (memo != null) {
//			document.getElementById('save_action').value='delete';
//			document.getElementById('save_param').value = memo;
//			document.getElementById('save').submit();
//		}
//	}
}

function ForceEditComment(id, item_id) {	
	val = document.getElementById('old_'+item_id+id).value;	
   if (document.getElementById('edit_comment_'+id).value==""){
      comment = prompt("Changing this field requires a mandatory explanation !!!", '');
		if(comment == null || comment == ""){				
			document.getElementById(item_id+id).value = val;
			document.getElementById('edit_comment_'+id).value==""			
		}else {			
			document.getElementById('edit_comment_'+id).value = comment;			
		}
   }
}

function ForceDenyComment(id, by) {
   if (!document.getElementById(by+'_approval_'+id).checked) {
      comment = prompt("Denying the line requires a mandatory explanation !!!");
      if ((comment!=null) && (comment!="")) {
         document.getElementById("deny_comment_"+id).value = comment;
      }else{
         document.getElementById(by+"_approval_"+id).checked = true;
      }
   }
}

function DoCorrect(reasons) {
   document.getElementById('save_action').value='correct';
   opt = document.getElementById("reason");
   opt.length = reasons.length+2;
   opt.options[0].value="";
   opt.options[0].text="[-- Select a reson for correcting the BR --]";
   for(i=0; i<reasons.length; i++) {
     opt.options[i+1].value = reasons[i];
	  opt.options[i+1].text = reasons[i];
   }
   opt.options[i+1].value="Other";
   opt.options[i+1].text="Other";
   opt.selectedIndex = 0;
   ChangeReason();
   document.getElementById("reason_tr").style.display="";
   document.getElementById("save_tr").style.display="none";
//	if (confirm('Are you sure you want to make corrections on this Billing Report ???')) {
//	   memo = "";
//	   while (memo == "" && memo!=null) {
//		    memo = prompt('Making corrections on a BR requires a mandatory explanation !!!', '');
//	   }
//		if (memo != null) {
//			document.getElementById('save_action').value='correct';
//			document.getElementById('save_param').value = memo;
//			document.getElementById('save').submit();
//		}
//	}
}

function DoDeny(by, section, form) {
   comment = prompt("Denying the "+section+" section requires a mandatory explanation !!!", '');
   if ((comment != null) && (comment!="")) {
      document.getElementById(section+'_comment').value = comment;
      document.getElementById(section+'_approve_by').value = by;
      document.getElementById(section+'_approve').value = 0;
      form.submit();
   }
}

function ChangeSearchDates(value, name) {
   var from = value.substr(0, value.indexOf("//"));
   var to = value.substr(value.indexOf("//")+2);
   document.getElementById('search_'+name+'_date_start').value = from;
   document.getElementById('search_'+name+'_date_end').value = to;

}

function DisplayAccounts() {
   DisplayListValues();
   document.getElementById("override_viewmybr").style.display='';
}

function CheckStudyCostApprovals() {
   inputs = document.getElementsByTagName("input");
   for(i=0; i<inputs.length; i++)
   {
      if (inputs[i].name.substring(0, 13) == "study_cost_id") 
      {
         study_cost_id = inputs[i].value;
         if (!document.getElementById("study_cost_approval_"+study_cost_id).checked) {
            comment = prompt("Why did you not approve "+document.getElementById("study_cost_description_"+study_cost_id).value+" ???", "");
            while ((comment == null) || (comment == "")) {
               if (comment == null) {
                  return false;
               } else {
                  comment = prompt("Why did you not approve "+document.getElementById("study_cost_description_"+study_cost_id).value+" ???", "");
               }
            }
            document.getElementById("study_cost_comment_"+study_cost_id).value = comment;         
         }
      }
   }
   return true;
}
  
function UpdateStudyCostApprovalAction(id, checked, action_checked, action_unchecked)
{
  if (checked) {
     action = action_checked;
  } else {
     action = action_unchecked;
  }
  document.getElementById("study_cost_action_"+id).value = action;
}

/**
* Function to check the negative values of Actual Qty, & Actual Rate boxes, in Billing Lines
* @author msilva
* @since 11-28-2007
*/
function checkCMValues(txtObj)
{
	if(isNegative(txtObj.value))
	{
		document.getElementById("save_for_later").disabled=true;
		document.getElementById("save_for_approval").disabled=true;
		alert("Please enter positive values for line items in a credit memo!");
		txtObj.focus()
		return;	
	}

	document.getElementById("save_for_later").disabled=false;
	document.getElementById("save_for_approval").disabled=false;
	
	return true;
}