/**
* display_message_list()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 03 12:40:34 PST 2006
*/
function display_message_list(o)
{
   var module_id = o.value;
   
   url = "?action=display_message_list&module_id="+ module_id;
   window.open(url,'app_bg');
   
   return true;
}

/**
* display_possible_values()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 03 13:54:53 PST 2006
*/
function display_possible_values(o, target)
{
   if (o.value == '') return false;
   
   url = "?action=display_possible_values&message_type_rule_def_id=" + o.value +"&target="+target;
   window.open(url, 'app_bg')
   
   return true;
}

/**
* add_selected_values()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 03 14:25:31 PST 2006
*/
function add_selected_values(target)
{
   var src = document.getElementsByName('attr_src_'+target)[0];
   var trg = document.getElementsByName('attr_value_'+target+'[]')[0];
   
   var trg_length = trg.length;
   for (i=0; i < src.length; i++ ) {
      if (src.options[i].selected == true) {
         //alert(src.options[i].text);
         trg.options[trg_length] = new Option(src.options[i].text, src.options[i].value);
         trg_length = trg.length;
      }
   }
}

/**
* display_add_rule()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 03 15:06:01 PST 2006
*/
function display_add_rule()
{
	/* uncheck the get all message checkbox if the user is customizing */
	var obj_get_all_msg = document.getElementsByName('get_all_messages')[0];
	
	if (obj_get_all_msg.checked == true) {
		obj_get_all_msg.checked = false;
	}
	
	
   var message_type_user_id = document.getElementsByName('message_type_user_id')[0].value;
   var number_of_rule_elements = parseInt(document.getElementsByName('number_of_rule_elements')[0].value);
   
   var t = document.getElementById('table_rule');
   var tb = t.getElementsByTagName("TBODY")[0];
   var row = document.createElement("TR");
   var td_field = document.createElement("TD");
   //td.appendChild(document.createTextNode("column 1"))
   //td_field.innerHTML = "<strong>Field Name</strong>";
   td_field.id = 'rule_row_'+ number_of_rule_elements + '_td_field';
   td_field.colspan = 1;
   
   var td_value = document.createElement("TD");
   //td.appendChild(document.createTextNode("column 1"))
   //td_field.innerHTML = "<strong>Field Name</strong>";
   td_value.id = 'rule_row_'+ number_of_rule_elements + '_td_value';
   td_value.colspan = 3;
   
   row.appendChild(td_field);
   row.appendChild(td_value);
   row.className = 'tab1';
   
   
   tb.appendChild(row);
   
   url = '?action=display_attr_list&target='+ number_of_rule_elements +'&message_type_user_id='+message_type_user_id;

   document.getElementsByName('number_of_rule_elements')[0].value = number_of_rule_elements + 1;
   
   window.open(url,'app_bg');
   
}

/**
* SelectAllMultiSelect()()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 09 17:25:40 PST 2006
*/
function SelectAllMultiSelect(f)
{
   for (i=0; i < f.elements.length; i++) {
      if (f.elements[i].type == 'select-multiple') {
         //alert(f.elements[i].name.substring(0,10));
         if (f.elements[i].name.substring(0,10) == 'attr_value') {
            //alert(f.elements[i].name);
            for (e=1; e < f.elements[i].options.length; e++) {
               f.elements[i].options[e].selected = true;
            }
         }
      }
   }
   
   return true;
}

/**
* ExpandMessage()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 31 18:26:39 PST 2006
*/
function ExpandMessage(message_id)
{
	var detail = document.getElementById('detail_'+ message_id);
	var summary = document.getElementById('summary_'+ message_id);
	
	if (detail.style.display == 'none') {
		detail.style.display = '';
		summary.style.display = 'none';
	} else {
		detail.style.display = 'none';
		summary.style.display = '';
	}
	
	summary.focus();
	
}

/**
* SetAccountAfterUpdate()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - 12:45:28
*/
function SetAccountAfterUpdate(arg1, arg2)
{
	var obj_account = document.getElementById('account');
	var account_name = obj_account.value;
	obj_account.value = '';

	
	var account_id = '';	
	
	var nodes = arg2.childNodes.length;
	
	for (i=0; i < nodes; i++) {
		if (arg2.childNodes[i].type == 'hidden') {			
			account_id = arg2.childNodes[i].value;
		}
	}
	
	var obj_accounts_selected = document.getElementById('accounts_selected');
	
	var opt = document.createElement("OPTION");
	opt.value = account_id;
	opt.innerHTML = account_name;
	
	obj_accounts_selected.appendChild(opt);
	
	
	
	return true;
	
}

/**
* DisplaySubLevel()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - 13:51:13
*/
function DisplaySubLevel(subscription_by)
{
	var obj_tr_module = document.getElementById('tr_module');
	var obj_tr_account = document.getElementById('tr_account');
	
	if (subscription_by == 'account') {
		obj_tr_module.style.display = 'none';
		obj_tr_account.style.display = '';
		return true;
	} 
	
	obj_tr_account.style.display = 'none';
	obj_tr_module.style.display = '';
	
	return true;
	
}

/**
* ToggleSubSubMenu()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - 20:36:43
*/
function ToggleSubSubMenu(sub_menu_id)
{
	switch (sub_menu_id) {
		case 'rule_list':
		
			var urls   = new Array('?action=display_rule_list&type=by_module', '?action=display_rule_list&type=by_account');
			var titles = new Array('By Module', 'By Account');
			html = GetSubMenuHtml(urls, titles);				
			break;
	}
	
	var div_sub_sub_menu = document.getElementById('subsubmenu2');
	div_sub_sub_menu.innerHTML = html;
	div_sub_sub_menu.style.display = 'block';
	
}

/**
* DisplaySubMenu()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - 20:40:18
*/
function GetSubMenuHtml(urls, titles)
{
	if (urls.length != titles.length) {
		alert('Definition Length doesn\'t match Titles');
		return false;
	}
	
	var html = '';
	
	for (i=0; i < urls.length; i++) {
		html += "<strong><a href='" + urls[i] +"' class='LinkNav3' id='sub_submenu_" + i + "'>"+ titles[i] +"</a></strong>&nbsp;&nbsp;";
	}
	
	return html;
	
}