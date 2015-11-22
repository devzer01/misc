/**
* ValidateAccountByName()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function ValidateAccountByName(account_name)
{

   var f = document.forms[0];
   var account_type;
   var url;

   account_type = GetAccountType();

   url = '?action=validate_account&account_name='+account_name+'&account_type='+account_type;

   window.open(url,'app_bg');
}

/**
* ResetValidation()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function ResetValidation(obj_name, event_handler)
{
   var o_td = document.getElementById('td_'+obj_name);
   o_td.innerHTML = "<input type='text' name='"+obj_name+"' onchange='"+event_handler+"'; size='30'>";
}

/**
* ValidateAeAmByAccountId()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function ValidateAeAmByAccountId(o)
{
   var account_id = o.value;
   var account_type = false;
   var url;

   account_type = GetAccountType();

   if (account_id != '') {
      url = '?action=validate_aeam&account_id='+account_id+'&account_type='+account_type;
      window.open(url,'app_bg');
   }

}

/**
* GetAccountType()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function GetAccountType()
{
   var f = document.forms[0];
   var account_type = false;

    //find out which account type is checked
   for (i=0; i < f.account_type.length; i++) {
      if (f.account_type[i].checked)
         account_type = f.account_type[i].value;
   }

   return account_type;
}

/**
* GetGroupMembers()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function GetGroupMembers(o)
{
   var functional_group_id = o.value;

   if (functional_group_id != '') {
      url = "?action=get_group_members&functional_group_id="+functional_group_id;
      window.open(url,'app_bg');
   }

}

function FilterCountryByRegionId(o)
{
   var region_id = o.value;

   if (region_id != '') {
      url = "?action=get_country_by_region&region_id="+region_id;
      window.open(url,'app_bg');
   }
}

function FilterRegionByCountryCode(o)
{
   var country_code = o.value;

   if (country_code != '') {
      url = "?action=get_region_by_country&country_code="+country_code;
      window.open(url,'app_bg');
   }

}


function ToggleAdvanceSearch()
{
   var advanced = 0;

   for (i=0;i<6;i++) {
      var a_search = document.getElementById('advance_search_'+i);
      if (a_search.style.display == 'block' || a_search.style.display == '') {
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
      o_a.style.display = 'block';
      o_b.style.display = 'none';
   } else {
      o_a.style.display = 'none';
      o_b.style.display = 'block';
   }

   var o_advanced_flag = document.getElementById('advanced_flag');
   o_advanced_flag.value = advanced;

}

function ValidateAccountName(o)
{
   var account_name = o.value;
   var obj_name     = o.name;
   var account_type = GetAccountType();
   url = '?action=validate_account_search&account_name='+account_name+'&account_type='+account_type+'&obj_name='+obj_name;
   url += '&func_name=ValidateAccountName';
   window.open(url,'app_bg');

}

function ValidateAccountId(o)
{
   var account_id   = o.value;
   var obj_name     = o.name;
   var account_type = GetAccountType();
   url = '?action=validate_account_search&account_id='+account_id+'&account_type='+account_type+'&obj_name='+obj_name;
   url += '&func_name=ValidateAccountId';
   window.open(url,'app_bg');

}

function GetGroupMembersSearch(o)
{
   var functional_group_id = o.value;

   if (functional_group_id != '') {
      url = "?action=get_group_members&functional_group_id="+functional_group_id+'&add_blank_option=1';
      url += '&sel_name=sc_fg_login'
      window.open(url,'app_bg');
   }
}

function toggleDetail(section_id)
{
   //alert(section_id);
   var o_tr = document.getElementsByTagName('tr');
   var toggle = 0;

   for (i=0;i < o_tr.length; i++) {
      if (o_tr[i].id == 'dtl_'+section_id) {

         if (o_tr[i].style.display == 'none') {
            o_tr[i].style.display = 'block';
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

   var img_name = document.all(div_id+'_1:arrow').src;

   if (img_name.indexOf('minus') == -1) {
      toggle = 0;
   }

   for (i=0;i < o_tr.length; i++) {
      if (o_tr[i].id != '') {
         var tr_name = o_tr[i].id.substring(0,4);
         var section_id = o_tr[i].id.substring(4);
         if (tr_name == 'dtl_') {
            if (toggle == 0) {
               o_tr[i].style.display = 'block';
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
      document.all(div_id+'_1:arrow').src='/images/rollminus.gif';
      document.all(div_id+'_2:arrow').src='/images/rollminus.gif';

   } else {
      document.all(div_id+'_1:arrow').src='/images/rollplus.gif';
      document.all(div_id+'_2:arrow').src='/images/rollplus.gif';
   }
}

function ClearSearch(f)
{
   for (i=0; i < f.elements.length; i++) {

      if (f.elements[i].type == 'text') {
         f.elements[i].value = '';
      } else if (f.elements[i].type == 'select-one') {
         f.elements[i].selectedIndex = 0;
      }
   }

   url = '?e=l0J/pvq/QVOfDBahnoubRnVu2imFQBIs';
   window.open(url,'_self');

}

function FormatCurrency(o)
{
   var number = o.value;

   if (o.value == '') return true;

   var re_first_alpha = new RegExp("^[^0-9]", "");
   var re_comma_replace = new RegExp(",", "g");
   var re_not_decimal = new RegExp("^[0-9]+\.?[0-9][0-9]$");

   number = number.replace(re_first_alpha, "");
   number = number.replace(re_comma_replace, "");


   if (number.match(re_not_decimal)) {
      o.value = number;
      return true;
   }

   o.style.backgroundColor = 'yellow';
   alert('Invalid Currency Format');
   return false;
}

function DisplayWonRow(o)
{
   var tr_won_date = document.getElementById('tr_won_date')
   var won_date = document.getElementById('won_date');
   var d = document.getElementById('js_today');

   if (o.value == 2) {
      tr_won_date.style.display = 'block';
      won_date.value = d.value;

   } else {
      tr_won_date.style.display = 'none';
      won_date.value = '';
   }

}

function CheckEdit(f)
{
   var status = f.elements.proposal_status_id;
   var project_value = f.elements.project_value;

   if (status.value == 2 && project_value.value == '') {
      alert('You must specify a Project Value to Change Status to Won');
      return false;
   }

   return true;

}

var field_names = new Array ('country_name','study_programming_type_id','translation', 'translation_language_code',
                             'overlay','overlay_language_code', 'study_datasource_id', 'incidence_rate',
                             'completes', 'questions_programmed', 'questions_per_interview', 'questions_per_screener',
                             'data_recording_hours', 'data_tab_hours', 'data_import_hours', 'data_export_hours', 'open_end_questions',
                             'incidence_of_open_end', 'avg_words_per_open_end', 'open_end_text_coding_hours',
                             'client_portal_type_id', 'client_portal_programming_hours', 'sub_group_description', 'panel_import_hours');

///////////////////////////
function copy_countries(c_id, o_id)
{
   var num_countries = document.getElementsByName('number_of_countries')[0].value;
   var copy_selected = document.getElementById('copy_selected');
   
   for (i=1; i <= num_countries; i++ ) {
      if (i == c_id)
         continue;
      for (c=0; c < field_names.length; c++) {
         
         var obj_src = document.getElementsByName(field_names[c]+'_option_'+o_id+'_country_'+c_id)[0];
         
         if (obj_src) {
            if (copy_selected.checked == true) {
               
               var sel = document.getElementsByName('sel_'+field_names[c]+'_option_'+o_id+'_country_'+c_id)[0];
               
               if (sel) {
               
                  if (sel.checked == true) {
                     document.getElementsByName(field_names[c]+'_option_'+o_id+'_country_'+i)[0].value = obj_src.value;
                  }
               }
                 
            } else {
               document.getElementsByName(field_names[c]+'_option_'+o_id+'_country_'+i)[0].value = obj_src.value;
            }
         }
         
      }

   }
}

///////////////////////////
function copy_options(o_id, c_id)
{
   var num_options = document.getElementsByName('number_of_options')[0].value;

   for (i=1; i <= num_options; i++ ) {
      if (i == o_id)
         continue;
      for (c=0; c < field_names.length; c++) {
         var obj_src = document.getElementsByName(field_names[c]+'_option_'+o_id+'_country_'+c_id)[0];
         if (obj_src) {
            document.getElementsByName(field_names[c]+'_option_'+i+'_country_'+c_id)[0].value = obj_src.value;
         }
      }

   }
}

function copy_option(o, option, country)
{
	if (option > 1) return true;
   var num_options = document.getElementsByName('number_of_options')[0].value;

   for (i=1; i <= num_options; i++ ) {
      if (i == option)
         continue;

		var obj_target = document.getElementsByName('country_name_option_'+i+'_country_'+country)[0];
      if (obj_target) {
      	obj_target.value = o.value;
   	}
  	}
}

////////////////////////////////
function apply_adhoc_discount(adhoc_discount, item_num)
{
   var o_net_price = document.getElementsByName('net_pricing_'+item_num)[0];
   
   net_price = o_net_price.value - (o_net_price.value * (adhoc_discount / 100));
   
   if (net_price > 0) {
      
      o_net_price.value = net_price;
      
   } else {
      alert('Invalid Discount Amount');
      document.getElementsByName('ad_hoc_discount_'+item_num)[0].value = '0.00';
   }
}

////////////////////////////////
function check_values()
{
   //loop through the country and options

}

/**
* calculate_panel()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun Jan 08 16:57:07 PST 2006
*/
function calculate_panel(o)
{
	var name = o.name;
   var value = o.value;

   var re_option_id = new RegExp("[0-9]+$", "");

   var option_id = name.match(re_option_id);
   
   if (!option_id) return false;
   
   var panel = document.getElementsByName('amount_3_'+ option_id)[0];
   var n = document.getElementsByName('n_'+ option_id)[0].value;
   
   panel.value = value * n;
	
   return true;
}

function calculate_total(o)
{
   var name = o.name;
   var value = o.value;
   var update_cpi = 0;
   
   if (name.substr(0,9) == 'amount_3_') {
   	update_cpi = 1;
   }

   var re_option_id = new RegExp("[0-9]+$", "");

   var option_id = name.match(re_option_id);

   if (!option_id) return false;

   amount = 0;
   

   for (i=1; i <= 4; i++) {
      var pitem = document.getElementsByName('amount_'+ i + '_' + option_id)[0];
      if (!pitem) continue;
      temp = parseFloat(pitem.value);
      if (isNaN(temp)) continue;
      amount += temp;
   }


   document.getElementsByName('total_'+ option_id)[0].value = amount;

   var n = document.getElementsByName('n_'+ option_id)[0].value;
   var cpc = amount / n;

   if (update_cpi == 1) {
   	cpi = n / value;
   	document.getElementsByName('cpi_'+ option_id)[0].value = cpi;	
   }
   
   document.getElementsByName('cpc_'+ option_id)[0].value = cpc;
   

}

////////////////////////////////////////////////////////
function display_add_prospect()
{
   var dv = document.getElementById('dv_add_prospect');
   dv.style.display = 'block';
}

///////////////////////////////////////////////////////
function adjust_option_type(option_type_id)
{
   var o_country = document.getElementsByName('number_of_countries')[0];
   var o_option  = document.getElementsByName('number_of_options')[0];
   
   if (option_type_id == 1) {
      o_country.value = 1;   
   }
}

////////////////////////////////////////////////////////
/**
* validate_number_of_countries()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Dec 22 23:36:17 PST 2005
*/
function validate_number_of_countries(n)
{
   var o_country = document.getElementsByName('number_of_countries')[0];
   var o_option_type = document.getElementsByName('proposal_option_type_id')[0];
   
   if (o_option_type.value == 1 && n != 1) {
      alert('Please Change The Proposal Option Type In order to Change Number of Countries');
      o_country.value = 1;
   }
}

/**
* display_portal_hours()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Dec 23 03:26:03 PST 2005
*/
function display_portal_hours(o, option, country)
{
   var target = document.getElementById('dv_portal_hours_'+ option +'_' +country);
   
   if (o.value == 2) {
      target.style.display = 'block'; 
      return true;
   } 
   
   target.style.display = 'none';
   return false;
}

/**
* display_translation_language_list()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Dec 23 03:41:31 PST 2005
*/
function display_translation_language_list(o, option, country)
{     
   var target = document.getElementById('dv_translation_language_' + option +'_' + country);   
   
   if (o.value == 1) {
      target.style.display = 'block';
      return true;
   }
   
   target.style.display = 'none';
   return false;
}

/**
* display_overlay_language_list()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Dec 23 03:50:43 PST 2005
*/
function display_overlay_language_list(o, option, country)
{
   var target = document.getElementById('dv_overlay_language_' + option +'_' + country);
   if (o.value == 1) {
      target.style.display = 'block';
      return true;
   }
   
   target.style.display = 'none';
   return false;
   
}

/**
* validate_add_revision()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Dec 28 21:12:30 PST 2005
*/
function validate_add_revision(f)
{
   var proposal_option_type_id = document.getElementsByName('proposal_option_type_id')[0];
   var sample_type_id          = document.getElementsByName('sample_type_id[]')[0];
   var number_of_countries     = document.getElementsByName('number_of_countries')[0];
   
   //alert(sample_type_id.length);
   
   msg    = '';
   submit = 1;
   
   if (proposal_option_type_id.value == 0) {
      submit = 0;
      msg += 'Proposal Option Type not selected \r\n';
   }
   
   if (number_of_countries.value == 1 && proposal_option_type_id.value == 3) {
      submit = 0;
      msg += 'Number of Countries Must Be Greater than 1 if multi-country proposal\r\n';
   }
   
   sample_type_ok = 0;
   
   for (i=0; i < sample_type_id.length; i++) {
      if (sample_type_id[i].selected == true) {
         sample_type_ok = 1;
         break;
      }
         
   }
   
   if (sample_type_ok == 0) {
      submit = 0;
      msg += 'Please Select Sample Type';
   }
   
   
   
   if (submit == 0) {
      msg = 'Please correct the following errors \r\n\r\n' + msg;
      alert(msg);
      
      return false;
   }
   
   f.submit();
   return true;
   
}

/**
* set_pricing_method()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Tue Jan 03 16:44:58 PST 2006
*/
function set_pricing_method(o)
{	
	if (o.name == 'proposal_type_id') {
   	custom_pricing = new Array(6, 7, 8, 9);
	} else if (o.name == 'sample_type_id[]') {
		custom_pricing = new Array(2, 3, 4, 5, 6, 7);
	}
   var o_pricing_method = document.getElementsByName('pricing_type_id')[0];
   
   for (i=0; i < custom_pricing.length; i++) {
      if (custom_pricing[i] == o.value) {
         o_pricing_method.value = 2;
         return true;
      }
   }
   
   o_pricing_method.value = 1;
   return true;
   
}

/**
* copy_value()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Tue Jan 03 17:20:20 PST 2006
*/
function copy_value(src, option, country)
{
   var do_click = 0;
   
   if (country != 1 || option != 1) return true;
   
   if (src.type == 'select-one') do_click = 1;
   
   var src_name = src.name;
   var src_value = src.value;
  
   var re = RegExp('[A-Za-z_]+', '');
   var re_cut_us = RegExp("_option_$", "g");
   
   var number_of_countries = document.getElementsByName('number_of_countries')[0];
   var number_of_options = document.getElementsByName('number_of_options')[0];
   
   src_name = String(src_name.match(re));
   src_name = src_name.replace(re_cut_us, '');
   
   for (o=1; o <= number_of_options.value; o++ ) {
      for (c=1; c <= number_of_countries.value; c++ ) {
         if (o == 1 && c == 1) continue;
         var target = document.getElementsByName(src_name+'_option_'+o+'_country_'+c)[0];
         target.value = src_value;
         if (do_click == 1) {
            target.onchange();
         }
      }
   }  
   return true;
}

/**
* Test<()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Tue Jan 03 18:23:18 PST 2006
*/
function TestM()
{
   alert('1');
   
}

//if (typeof(HTMLElement) != 'undefined') {

//   HTMLElement.prototype.click = function() {
//      var evt = this.ownerDocument.createEvent('MouseEvents');
//      evt.initMouseEvent('click', true, true, this.ownerDocument.defaultView, 1, 0, 0, 0, 0, false, false, false, false, 0, null);
//      this.dispatchEvent(evt);
//   }
//}

function prepare_rte() {
	//make sure hidden and iframe values are in sync before submitting form
	//to sync only 1 rte, use updateRTE(rte)
	//to sync all rtes, use updateRTEs
	//updateRTE('qualifying_criteria');
	updateRTEs();
	
	//change the following line to true to submit form
	return true;
}

function copy_country(src, option, country) 
{
	var src_value = src.value;
	var number_of_countries = document.getElementsByName('number_of_countries')[0];
   var number_of_options = document.getElementsByName('number_of_options')[0];
   
   for (o=1; o <= number_of_options.value; o++ ) {
      for (c=1; c <= number_of_countries.value; c++ ) {
         if (o == option && c == country) continue;
         var target = document.getElementsByName('country_name_option_'+o+'_country_'+c)[0];
         target.value = src_value;
      }
   }  

   return true;
}

/**
* validate_proposal_won()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sat Jan 07 20:06:28 PST 2006
*/
function validate_proposal_won()
{
	var selected_option = document.getElementsByName('selected_option');
	var selected_country = document.getElementsByName('selected_country[]');	
	var single_country  = document.getElementsByName('single_country')[0].value;
	
	option_selected = 0;
	country_selected = 0;
	
	for (i=0; i < selected_option.length; i++) {
		if (selected_option[i].checked) {
			option_selected = 1;
		}
	}
	
	for (i=0; i < selected_country.length; i++) {
		if (selected_country[i].checked || single_country == 1) {
			country_selected = 1;
		}
	}
	
	if (option_selected == 0) {
		alert('Please Select an Option to Create Final Revision');
		return false;
	}
	
	if (country_selected == 0) {
		alert('Please Select At Least One Country to Create Final Revision');
		return false;
	}
	
	return true;
	
}

/**
* select_service()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Jan 19 21:28:30 PST 2006
*/
function select_service(o)
{
	var service_hosting = document.getElementsByName('S_3')[0];
	if (o.value != 5) {
		service_hosting.checked = false;
		return true;
	}
	
	
	service_hosting.checked = true;
	
}

/**
* validate_service()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Jan 19 21:32:23 PST 2006
*/
function validate_service(o)
{
	if (o.name != 'S_3') {
		return true;
	}
	
	var proposal_type = document.getElementsByName('proposal_type_id')[0];
	
	if (proposal_type.value == 1 && o.checked == true) {
		alert('You can not select hosting for Sample Only Proposals');
		o.checked = false;
	} else if (proposal_type.value == 5 && o.checked == false) {
		alert('You Must Select Hosting for Hosted-CAWI');
		o.checked = true;
	}
	
	return true;
	
}

/**
* call_url()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Mar 06 10:35:57 PST 2006
*/
function call_url(url)
{
	window.open(url,'_new');
	return true;
}

/**
* CancelProposal()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Mar 28 06:40:39 PST 2006
*/
function CancelProposal()
{
	if (!confirm('Are You Sure You\'d Like to Cancel This Proposal')) 
		return false;

	var url = '?action=display_proposal_list';	
	
	if (CancelProposal.arguments.length == 1) { //2nd screen
		url = '?action=delete_proposal&proposal_id='+ CancelProposal.arguments[0];	
	} else if (CancelProposal.arguments.length == 2) { //all other screen
		url = '?action=delete_proposal&proposal_id='+ CancelProposal.arguments[0] + '&proposal_revision_id=' + CancelProposal.arguments[1];	
	}
	
	
	window.open(url,'_top');
	return true;
	
}

/**
* AjaxSaveComment()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Mar 28 08:49:03 PST 2006
*/
function AjaxSaveComment(proposal_id)
{
	url = '?send_xml_comment_list=1&action=save_comment&proposal_id='+proposal_id;
	
	var proposal_comment_text = document.getElementById('proposal_comment_text').value;
	
	AbortCurrentCallIfInUse(http);
	http.open("POST", url, true);
	http.setRequestHeader("Method", "POST "+url+" HTTP/1.1");
	http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	http.onreadystatechange = AddCommentToTable
	http.send("proposal_comment_text="+proposal_comment_text);
	
	ToggleSavingCommentBanner(1);
	
	ClearTable();
	
	
	
}

/**
* AjaxGetComment()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Mar 28 12:13:34 PST 2006
*/
function AjaxGetComment(proposal_id)
{
	url = '?send_xml_comment_list=1&action=get_comments&proposal_id='+proposal_id;
	
	AbortCurrentCallIfInUse(http);
	http.open("GET", url, true);
	http.onreadystatechange = AddCommentToTable
	http.send("");
	
	ToggleSavingCommentBanner(1);
	
	ClearTable();
}

/**
* AddCommentToTable()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Mar 28 08:51:10 PST 2006
*/
function AddCommentToTable()
{
	if (http.readyState != HTTP_READY_STATE_COMPLETED) 
		return true;
	
	var root = http.responseXML;
	if (!root){
   	alert("Error when retrieving study lists.\n\n"+http.responseText);
   	
   	//AddRowToTable('proposal_comment', 'Name', 'Date', 'Status', 'Comment');
      return;
	}
	
	
	
	var e = root.getElementsByTagName('comment');
	
	for (s=0; s < e.length; s++) {
		var comment_user = e[s].getAttribute('comment_user');
		var comment_date = e[s].getAttribute('comment_date');
		var comment_status = e[s].getAttribute('comment_status');
		var comment_text = unescape(e[s].getAttribute('comment_text'));
		
		AddRowToTable('proposal_comment', comment_user, comment_date, comment_status, comment_text);
		
	}
	
	ToggleSavingCommentBanner(0);
	document.getElementById('proposal_comment_text').value = '';
}

/**
* AddRowToTable()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Mar 28 09:08:55 PST 2006
*/
function AddRowToTable(table)
{
	//column text are passed in as dynamic arguments
	
	var t = document.getElementById(table);
	
   var tb = t.getElementsByTagName("TBODY")[0];
   var row = document.createElement("TR");
   
   for (i=1; i < AddRowToTable.arguments.length; i++) {
   
   	var td_field = document.createElement("TD");
   	//td_field.appendChild(document.createTextNode(AddRowToTable.arguments[i]));
   	td_field.innerHTML = AddRowToTable.arguments[i];
   	row.appendChild(td_field);
   }

   row.className = 'tab1';
   
   tb.appendChild(row);
}

/**
* ClearTable()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Mar 28 09:17:24 PST 2006
*/
function ClearTable()
{
	var t = document.getElementById('proposal_comment');
	
	 var rows = t.rows.length;
	
	for (r=0; r < rows; r++) {
		if (t.rows[1].className != 'header1') {
			t.deleteRow(1);
		}
	}
	
	return true;
	
}

/**
* DisplaySavingCommentBanner()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Mar 28 10:06:22 PST 2006
*/
function ToggleSavingCommentBanner(toggle)
{
	var saving_comment_text = document.getElementById('saving_comment_text');
	var save_comment_text = document.getElementById('save_comment_text');
	
	if (toggle == 1) {
		saving_comment_text.style.display = '';
		save_comment_text.style.display = 'none';
	} else {
		saving_comment_text.style.display = 'none';
		save_comment_text.style.display = '';
	}
	return true;
}

/**
* LoadReport()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Apr 18 15:46:07 PDT 2006
*/
function LoadReport(section_name)
{
	var placement = document.getElementById('placement');
	var current_section = placement.value;
	placement.value = section_name;
	
	//reset drill down
	document.getElementById('drill_filter').value = 0;
	document.getElementById('drill_from'.value = 0);
	
	SearchReport();
	new Effect.BlindUp('div_'+current_section,{duration:2.0});
	
	//get the search filters
	//post to HB
	//set the call back function
	
}

/**
* DisplayCustom()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Apr 24 22:42:39 PDT 2006
*/
function DisplayCustom()
{
	var placement = document.getElementById('placement');
	var current_section = placement.value;
	placement.value = 'custom';
	
	new Effect.BlindUp('div_'+current_section,{duration:2.0});
	new Effect.BlindDown('div_custom',{duration:2.0});
}

/**
* DisplayReport()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Apr 18 15:47:20 PDT 2006
*/
function DisplayReport()
{
	
	if (http.readyState != HTTP_READY_STATE_COMPLETED) 
		return true;
	
	var root = http.responseXML;
	if (!root){
		var target = document.getElementById('div_global');
		$(target).innerHTML = http.responseText;
		HideAnimated();
		new Effect.BlindDown('div_global',{duration:2.0});
   	//alert("Error when retrieving Report.\n\n"+http.responseText);
   	
   	//AddRowToTable('proposal_comment', 'Name', 'Date', 'Status', 'Comment');
      return;
	}
	
	var body = root.getElementsByTagName('report_html');
	var html = body[0].firstChild.nodeValue;
	
	var meta = root.getElementsByTagName('meta');
	var target_div = meta[0].getAttribute('placement');
	
	var target = document.getElementById('div_'+target_div);
	HideAnimated();
	$(target).innerHTML = html;
   //new Effect.BlindUp('old-search-results',{duration:.8});
   new Effect.BlindDown('div_'+target_div,{duration:2.0});
	
}

/**
* DisplayAnimated()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Apr 20 14:36:04 PDT 2006
*/
function DisplayAnimated()
{
	//progressBarInit();
	var div_animated = document.getElementById('div_animated');
	div_animated.style.display = 'block';
}

/**
* HideAnimated()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Apr 20 14:36:52 PDT 2006
*/
function HideAnimated()
{
	var div_animated = document.getElementById('div_animated');
	div_animated.style.display = 'none';
}

/**
* ValidateReport()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue May 16 15:37:02 PDT 2006
*/
function ValidateReport(req_items, errors)
{
	var length = req_items.length;
	
	for (var i=0; i < length; i++)
	{
		
		var obj = document.getElementById(req_items[i]);
		if (obj.value == '')
		{
			alert(errors[i]);
			obj.focus();
			return false;
		}
		
	}
	
	return true;
	
}

/**
* SearchReport()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Apr 19 07:05:55 PDT 2006
*/
function SearchReport()
{
	var req_items = new Array('report_id');
	var errors    = new Array('Report Type is Required');
	
	
	if (!ValidateReport(req_items, errors))
		return false;
		
	DisplayAnimated();
	
	var url = '?action=get_report_data';
	var params = '';
	var p_value = 0;
	
	var exclude_items = new Array('normalize_by')
	
	var f = document.getElementById('pgen_report');
	
	for (i=0; i < f.elements.length; i++) {
		
		if (exclude_items.inArray(f.elements[i].name))
			continue;
		
		if (f.elements[i].type == 'checkbox') {
			if (f.elements[i].checked == true) {
				p_value = 1;
			} else {
				p_value = 0;
			}
			
			params += f.elements[i].name + '=' + p_value + '&';
		} else if (f.elements[i].type == 'select-multiple') {
			
			for (e=0; e < f.elements[i].length; e++) {
				if (f.elements[i].options[e].selected) {
					params += f.elements[i].name + '=' + f.elements[i].options[e].value + '&';
				}
			}
		} else if (f.elements[i].type == 'radio') {
			
			if (f.elements[i].checked) {
				params += f.elements[i].name + '=' + f.elements[i].value + '&';
			}
			
		} else {
			params += f.elements[i].name + '=' + f.elements[i].value + '&';
		}
	}
	
	AbortCurrentCallIfInUse(http);
	http.open("POST", url, true);
	http.setRequestHeader("Method", "POST "+url+" HTTP/1.1");
	http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	http.onreadystatechange = DisplayReport;
	http.send(params);
	
	//ToggleSavingCommentBanner(1);
	
	//ClearTable();
}

/**
* DrillDown()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Apr 19 11:54:50 PDT 2006
*/
function DrillDown(drill_filter)
{
	var placement = document.getElementById('placement');
	var o_drill_filter = document.getElementById('drill_filter');
	var o_drill_from = document.getElementById('drill_from');
	
	o_drill_from.value   = placement.value;
	o_drill_filter.value = drill_filter;
	
	placement.selectedIndex = placement.selectedIndex + 1;
	
	SearchReport();
	
	new Effect.BlindUp('div_'+o_drill_from.value,{duration:.8});
}

/**
* ToggleUserSection()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Apr 24 14:12:01 PDT 2006
*/
function ToggleUserSection()
{
	var a_office = document.getElementById('a_office');
	var a_person   = document.getElementById('a_person');
	var a_country   = document.getElementById('a_country');
	var base_filter = document.getElementById('base_filter');
	
	var u_group = new Array();
	u_group[0] = new Option('Person', 'person');
	u_group[1] = new Option('Office', 'office');
	
	var a_group = new Array();
	a_group[0] = new Option('Country', 'country');
	
	if (base_filter.value == 'user')  {
		a_office.style.display = '';
		a_person.style.display   = '';
		a_country.style.display = 'none';
		
		RemoveElementsFromSelect('placement', a_group);
		AddElementsToSelect('placement', u_group, 2);
		
	} else {
		a_office.style.display = 'none';
		a_person.style.display   = 'none';
		a_country.style.display = '';
		RemoveElementsFromSelect('placement', u_group);
		AddElementsToSelect('placement', a_group, 2);
	}
	
}

/**
* AddElementsToSelect();()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Apr 24 14:18:14 PDT 2006
*/
function AddElementsToSelect(obj, options, index)
{
	var o = document.getElementById(obj);
	
	for (i=0; i < options.length; i++) {
		o.options.add(options[i], index);
	}
	
	//history.go(0);
	
}

/**
* RemoveElementsFromSelct()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Apr 24 14:18:22 PDT 2006
*/
function RemoveElementsFromSelect(obj, options)
{
	var o = document.getElementById(obj);
	
	for (i=0; i < options.length; i++) {
		
		for (op = 0; op < o.options.length; op++) {
			
			if (o.options[op].value == options[i].value) {
				o.options[op] = null;		
			}
			
		}
	}
}

/**
* AddToCustom()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Apr 24 21:04:13 PDT 2006
*/
function AddToCustom(section)
{
	new Effect.Shake('div_'+section);
	
	var div_custom = document.getElementById('div_custom');
	var tbl_custom = document.getElementById('tbl_custom');
	var div_section = document.getElementById('div_'+section);
	
	var new_div = document.createElement("DIV");
	new_div.innerHTML = div_section.innerHTML;
	
	var tb = tbl_custom.getElementsByTagName('TBODY')[0];
	var new_row = document.createElement("TR");
	var new_cell = document.createElement("TD");	
	
	var form = new_div.getElementsByTagName('FORM')[0];
	var random = Math.random() * 3000;
	random = Math.round(random);
	
	for (i=0; i < form.elements.length; i++) {
		//lets make the checkbox display and also prefix the name with custom so we have unique IDs
		if (form[i].type == 'checkbox') 
		{
			form[i].style.display = '';		
			form[i].name          = 'custom_' + form[i].name + '_' + random;
			form[i].id            = 'custom_' + form[i].id  + '_' + random;
			
		} else if (form[i].type == 'select-one') {
			form[i].style.display = '';		
			//form[i].name = 'custom_' + form[i].name;
			form[i].id = 'custom_' + form[i].id;
		} else {
			//alert(form[i].type);
		}
	}
	
	//alert(form.name);
	
	form.name = 'custom_' + form.name + '_' + random;
	form.id   = 'custom_' + form.id + '_' + random;
	
	//alert(form.name);
	
	
	new_cell.appendChild(new_div);
	//new_cell.innerHTML = 'Test';
	new_row.appendChild(new_cell);
	tb.appendChild(new_row);
	
	//alert(tbl_custom.rows.length);
	
	//div_custom.appendChild(new_div);
	Array.prototype.inArray = function (value)
// Returns true if the passed value is found in the
// array.  Returns false if it is not.
{
    var i;
    for (i=0; i < this.length; i++) {
        // Matches identical (===), not just similar (==).
        if (this[i] === value) {
            return true;
        }
    }
    return false;
};
}

/**
* DisplayCustomize()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Apr 25 13:38:21 PDT 2006
*/
function DisplayCustomize()
{
	var div_configure = document.getElementById('div_configure');
	new Effect.Grow('div_configure', {direction: 'center'});
		
}

/**
* CloseCustomize()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Apr 25 14:23:04 PDT 2006
*/
function CloseCustomize()
{
	var div_configure = document.getElementById('div_configure');
	new Effect.Shrink('div_configure');
}

/**
* CloseDiv()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Apr 26 08:47:12 PDT 2006var req_items = new Array('report_id');
	var errors    = new Array('Report Type is Required');
	
	
	if (!ValidateReport(req_items, errors))
		return false;
*/
function CloseDiv(div_id)
{
	new Effect.Fold('div_'+div_id, {afterFinish: RemoveChild});	
}

/**
* RemoveChild()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Apr 27 11:58:48 PDT 2006
*/
function RemoveChild(obj)
{
	var div_id = obj.element.id;
	var unique = div_id.match("_(.*)$");
	
	var td = document.getElementById('td_'+unique[1]);
	
	if (td) {
		var div = document.getElementById('div_'+unique[1]);
		td.removeChild(div);
   }

	
}

/**
* ToggleTierList()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Apr 26 13:06:24 PDT 2006
*/
function ToggleTierList()
{
	var tier_on = document.getElementsByName('tier_on')[0];
	var tier    = document.getElementById('tier');
	
	if (tier_on.checked) {
		tier.style.display = '';
	} else {
		tier.style.display = 'none';
	}
	
}

/**
* ToggleBlock()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue May 09 10:41:06 PDT 2006
*/
function ToggleBlock(id, sw)
{
	var block = document.getElementById(id);
	//display
	if (sw.value == 1) 
		block.style.display = '';
	else 
		block.style.display = 'none';
		
	return true;
	
}

/**
* GraphSelected()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Apr 26 15:51:46 PDT 2006
*/
function GraphSelected()
{
	var tbl_custom = document.getElementById('tbl_custom');
	var inputs = tbl_custom.getElementsByTagName('INPUT');
	
	var selected_forms = new Array();
	var xml = new Array();
	var str_xml = '';
	
	var index = 0;
	
	//browsing throgh all elements
	for (i=0; i < inputs.length; i++) {
		if (inputs[i].type == 'checkbox' && inputs[i].checked) {
			var primary_key = inputs[i].name.match("custom_chk_(.*)$");
			selected_forms[index] = primary_key[1];
			index++;
		}
	}
	
	for (fi=0; fi < selected_forms.length; fi++) {
		
		var f = document.getElementById('custom_frm_'+selected_forms[fi]);
		
		for (i=0; i < f.elements.length; i++) {
			str_xml += "<" + f[i].name + ">" + f[i].value + "</" + f[i].name + ">";
		}
		
		xml[fi] = str_xml;
		str_xml = '';
	}
	
	var xml_header = "<xml version='1.0'><form>";
	var xml_footer = "</form></xml>";
	
	for (xi=0; xi < xml.length; xi++) {
		str_xml += "<form_" + xi + ">" + xml[xi] + "</form_" + xi + ">";
	}
	
	var post = xml_header + str_xml + xml_footer;
	
	CallDrawGraph(post);
	
}

/**
* DoAnalysis()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Apr 28 07:40:51 PDT 2006
*/
function DoAnalysis(type)
{
	var req_items = new Array('custom_report_id');
	var errors    = new Array('Report Type For Analysis is Required');
	
	
	if (!ValidateReport(req_items, errors))
		return false;
	
	var tbl_custom = document.getElementById('tbl_custom');
	var inputs = tbl_custom.getElementsByTagName('INPUT');
	
	var selected_forms = new Array();
	var xml = new Array();
	var str_xml = '';
	
	var index = 0;
	
	//browsing throgh all elements
	for (i=0; i < inputs.length; i++) {
		if (inputs[i].type == 'checkbox' && inputs[i].checked) {
			var primary_key = inputs[i].name.match("custom_chk_(.*)$");
			selected_forms[index] = primary_key[1];
			index++;
		}
	}
	
	//prepare XML with the forms inside
	for (fi=0; fi < selected_forms.length; fi++) {
		
		var f = document.getElementById('custom_frm_'+selected_forms[fi]);
		
		for (i=0; i < f.elements.length; i++) {
			str_xml += "<" + f[i].name + ">" + f[i].value + "</" + f[i].name + ">";
		}
		
		xml[fi] = str_xml;
		str_xml = '';
	}
	
	var xml_header = "<xml version='1.0'>";
	var xml_footer = "</xml>";
	
	for (xi=0; xi < xml.length; xi++) {
		str_xml += "<form_" + xi + ">" + xml[xi] + "</form_" + xi + ">";
	}
	
	//check the normalize drop down list
	var normalize_by     = document.getElementById('custom_normalize_by').value;
	var role_id          = document.getElementById('custom_role_id').value;
	var report_name      = document.getElementById('custom_graph_name').value;
	var analysis_range   = document.getElementById('custom_analysis_range').value;
	var report_id        = document.getElementById('custom_report_id').value;
	var debug            = (document.getElementById('debug').checked) ? 1 : 0;
	
	
	var $meta  = '<meta>';
	    $meta += '<normalize_by>' + normalize_by + '</normalize_by>';
	    $meta += '<role_id>' + role_id + '</role_id>';
	    $meta += '<report_name>' + report_name +'</report_name>';
	    $meta += '<analysis_range>' + analysis_range +'</analysis_range>';
	    $meta += '<debug>' + debug +'</debug>';
	    $meta += '<report_id>' + report_id +'</report_id>';
	    $meta += '</meta>';	
	
	var post = xml_header + '<form>' + str_xml + '</form>' + $meta + xml_footer;

	var sub_action = 'draw_custom_graph';
	
	if (type == 'analyze')
		sub_action = 'draw_normalize_graph';
	
	var url = '?action=get_report_data&sub_action=' + sub_action;
	
	PostXmlDataForAjax(url, post);
		
	//CallNormalizeGraph(post);
	
}

/**
* Publish()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Apr 28 13:57:12 PDT 2006
*/
function Publish()
{
	var div_custom = document.getElementById('div_custom');
	var publish = document.getElementById('publish');
	var html = div_custom.innerHTML;
	
	publish.elements.html.value = html;
	
	publish.submit();
	
	
}

/**
* PostXmlDataForAjax()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue May 16 13:23:39 PDT 2006
*/
function PostXmlDataForAjax(url, post)
{
	AbortCurrentCallIfInUse(http);
	http.open("POST", url, true);
	http.setRequestHeader("Method", "POST "+url+" HTTP/1.1");
	//http.setRequestHeader("Content-Type", "text/xml");
	http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	http.onreadystatechange = DrawCustomGraph;
	http.send(post);
}

/**
* CallNormalizeGraph()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Apr 28 07:46:02 PDT 2006
*/
function CallNormalizeGraph(post)
{
	var url = '?action=get_report_data&sub_action=draw_normalize_graph';
	AbortCurrentCallIfInUse(http);
	http.open("POST", url, true);
	http.setRequestHeader("Method", "POST "+url+" HTTP/1.1");
	//http.setRequestHeader("Content-Type", "text/xml");
	http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	http.onreadystatechange = DrawCustomGraph;
	http.send(post);
}

/**
* CallDrawGraph()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Apr 26 20:16:38 PDT 2006
*/
function CallDrawGraph(post)
{
	var url = '?action=get_report_data&sub_action=draw_custom_graph';
	AbortCurrentCallIfInUse(http);
	http.open("POST", url, true);
	http.setRequestHeader("Method", "POST "+url+" HTTP/1.1");
	//http.setRequestHeader("Content-Type", "text/xml");
	http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	http.onreadystatechange = DrawCustomGraph;
	http.send(post);
	
}

/**
* DrawCustomGraph()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Apr 26 20:27:44 PDT 2006
*/
function DrawCustomGraph()
{
	if (http.readyState != HTTP_READY_STATE_COMPLETED) 
		return true;
	
	var root = http.responseXML;
	if (!root){
   		var target = document.getElementById('div_global');
			$(target).innerHTML = http.responseText;
			HideAnimated();
			new Effect.BlindDown('div_global',{duration:2.0});
   		//alert("Error when retrieving Report.\n\n"+http.responseText);
   	
   		//AddRowToTable('proposal_comment', 'Name', 'Date', 'Status', 'Comment');
      	return;
	}
	
	var body = root.getElementsByTagName('report_html');
	var html = body[0].firstChild.nodeValue;
	
	
	var tbl_custom = document.getElementById('tbl_analysis');
	var div = document.createElement("DIV");
	var container = document.createElement("DIV");
	var cell = document.createElement("TD");
	var row = document.createElement("TR");
	var tb = tbl_custom.getElementsByTagName('TBODY')[0];
	
	var div_id = Math.random() * 100000;
	//alert(div_id);
	
	var header = "<div class='header1' style='text-align:right;'><a class='LinkNav2' href=\"javascript:CloseDiv('"+ div_id +"');\">Close</a></div>"
	
	div.innerHTML = header + html;
	div.id = 'div_' + div_id;
	div.setClass = 'header1';
	
	cell.appendChild(div);
	
	cell.id = 'td_' + div_id;
	
	row.appendChild(cell);
	tb.appendChild(row);
	
//	for (r=0; r < tb.rows.length; r++) {
//		if (tb.rows[r].cells.length == 2 && tb.rows[r].cells[1].innerHTML == '') {
//			//alert(tb.rows[r].cells[1].innerHTML); // == ''
//			tb.rows[r].cells[1].appendChild(div);
//			tb.rows[r].cells[1].id = 'td_' + div_id;
//			break;
//		} else if (tb.rows[r].cells.length == 1) {
//			tb.rows[r].appendChild(cell);
//			break;		
//		}
//	}
	
}

/**
* GetReportAttributes()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sat May 06 21:43:08 PDT 2006
*/
function GetReportAttributes(report_id)
{
	//alert(report_id);
	url = '?action=get_report_attributes&report_id='+ report_id;
	AbortCurrentCallIfInUse(http);
	http.open("GET", url, true);
	//http.setRequestHeader("Method", "POST "+url+" HTTP/1.1");
	//http.setRequestHeader("Content-Type", "text/xml");
	//http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	http.onreadystatechange = SetConfigureAttributes;
	http.send("");
}

/**
* GetDisAggrData()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue May 09 10:47:51 PDT 2006
*/
function GetDisAggrData(disaggr_by)
{
	var select = document.getElementById('disaggr_value');
	
	ClearListBox(select);
	
	if (disaggr_by == '') {
		select.style.display = 'none';
		return true;
	}
	
	url = '?action=get_dis_aggr_data&disaggr_by='+ disaggr_by;
	AbortCurrentCallIfInUse(http);
	http.open("GET", url, true);
	//http.setRequestHeader("Method", "POST "+url+" HTTP/1.1");
	//http.setRequestHeader("Content-Type", "text/xml");
	//http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	http.onreadystatechange = SetDisAggrValues;
	http.send("");
	select.style.display = '';
}

/**
* SetDisAggrValues()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue May 09 10:49:13 PDT 2006
*/
function SetDisAggrValues()
{
	if (http.readyState != HTTP_READY_STATE_COMPLETED) 
		return true;
	
	var root = http.responseXML;
	if (!root){
   	alert("Error when retrieving Report.\n\n"+http.responseText);
      return;
	}
	
	var items  = root.getElementsByTagName('item');
	var select = document.getElementById('disaggr_value');
	
	AddOptionToListBox(select, '[All]', '');
	
	for (var i=0; i < items.length; i++) {
		//FF
		if (items[i].childNodes[0].textContent) {
			var name  = items[i].childNodes[0].textContent;
			var value = items[i].childNodes[1].textContent;
		} else { //IE
			var name  = items[i].childNodes[0].text;
			var value = items[i].childNodes[1].text;
		}
		
		AddOptionToListBox(select, name, value);	
	}
	
}

/**
* SetConfigureAttributes()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun May 07 09:14:31 PDT 2006
*/
function SetConfigureAttributes()
{
	if (http.readyState != HTTP_READY_STATE_COMPLETED) 
		return true;
	
	var root = http.responseXML;
	if (!root){
   	alert("Error when retrieving Report.\n\n"+http.responseText);
      return;
	}
	
	var meta = root.getElementsByTagName('meta');
	var attr = meta[0].childNodes;
	var conf = new Object;
	
	
	conf = Xml2Object(attr);
	
	//set the attributes in the configure 
	if (conf.tabular == 1) {
		document.getElementById('tabular').disabled = false;
	}
	
	if (conf.graphical == 1) {
		document.getElementById('graphical').disabled = false;
	}
	
	/* populating available date ranges 
	if (conf.tabular_date_range) {
		
		//reference to the select element
		var select = document.getElementById('t_date_range');
		
		
				
		/* remove existing child elements */
		//ClearListBox(select);
		
		//we should insert the [All] option first
		//select.appendChild(GetHtmlOptionObject('', '[All]'));
		//AddOptionToListBox(select, '[All]', '');
		
	//	for (e in conf.tabular_date_range) {
		//	AddOptionToListBox(select, conf.tabular_date_range[e], e);
			////select.appendChild(GetHtmlOptionObject(e, conf.tabular_date_range[e]));
		//}
	//}
	
	/* populating available graphs */
	if (conf.graphs) {
		
		var select = document.getElementById('graph_type');
		
		/* remove existing child elements */
		ClearListBox(select);
		
		//we should insert the [All] option first
		//select.appendChild(GetHtmlOptionObject('', '[All]'));
		AddOptionToListBox(select, '[All]', '');
		
		for (e in conf.graphs) {
			//select.appendChild(GetHtmlOptionObject(e, conf.graphs[e]));
			AddOptionToListBox(select, conf.graphs[e], e);
		}
	}
}

/**
* GetHtmlOptionObject()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun May 07 13:41:09 PDT 2006
*/
function GetHtmlOptionObject(value, text)
{
	var o   = document.createElement("OPTION");
	o.text  = text;
	o.value = value;
	
	return o;
}


/**
* Xml2Object()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun May 07 11:17:45 PDT 2006
*/
function Xml2Object(attr)
{
	var conf = new Object;
	
	for (var i=0; i < attr.length; i++) {
		if (attr[i].childNodes.length > 1) {
			conf[attr[i].tagName] = Xml2Object(attr[i].childNodes);	
		} else {
			
			var value;
			
			if (attr[i].textContent) {
				value = attr[i].textContent;
			} else {
				value = attr[i].text;
			}
			
			conf[attr[i].tagName] = value;
		}
	}
	
	return conf;
	
}

/**
* AddToList()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue May 09 12:54:29 PDT 2006
*/
function AddToList(src, target)
{
	var src    = document.getElementById(src);
	var target = document.getElementById(target);
	
	for (var i=0; i < src.options.length; i++) {
		if (src.options[i].selected) {
//			alert(src.options[i].text);
			AddOptionToListBox(target, src.options[i].text, src.options[i].value);
			//src.options[i] = null;
		}
	}
	
	for (var i=0; i < target.options.length; i++) {
		if (!target.options[i].selected) {
			target.options[i].selected = true;
		}
	}
}

