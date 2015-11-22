var ACCOUNT_CUSTOMER = 1;


function UpdateAccountSubType(account_type_id)
{
	
	values = '';
	
	var obj 					= document.getElementById('tr_has_product_account');
	
	var obj_prod_id 		= document.getElementById('tr_get_account_detail');	

	var obj_r_product_id = document.getElementById('r_product_account_id');
		
	obj_r_product_id.name = 'to_rename'; 
	
	display_get_account_detail = 0;
	for (i=0; i < account_type_id.length; i++) {
		if (account_type_id[i].selected) {
			values += account_type_id[i].value + ",";
			
			if (account_type_id[i].value == ACCOUNT_CUSTOMER) {
				display_get_account_detail = 1;				
			}
			
		}
	}
 	var url = "/Account/listsubtype/type/" + account_type_id.value;
	//AbortCurrentCallIfInUse(http);
	http.open("GET", url, true);
	http.onreadystatechange = AddAccountSubTypes;
	http.send("");
	
	if (display_get_account_detail == 1) {
		obj.style.display = '';
	} else {
		var obj2 = document.getElementById('client_has_account');
		obj2.checked = false;
		obj.style.display = 'none';
		obj_prod_id.style.display = 'none';
		
	}
	
}


function FillContact(num)
{
	var street_one	= document.getElementById('address_street_1');
	var street_two	= document.getElementById('address_street_2');
	var zip_code	= document.getElementById('address_zip');
	var state	= document.getElementById('address_state');
	var city	= document.getElementById('address_city');
	var validation	= document.getElementById('validation');
	
	
	street_one.value = document.getElementById('street_one_'+ num).value;
	street_two.value = document.getElementById('street_two_'+ num).value;
	zip_code.value = document.getElementById('zip_code_'+ num).value;
	state.value = document.getElementById('state_'+ num).value;
	city.value = document.getElementById('city_'+ num).value;
	
	validation.value = true;	
	
}

function SetValidation()
{
	var validation	= document.getElementById('validation');
	validation.value = false;
	
}

function UpdateEditAccountSubType(account_type_id)
{
	values = '';
	for (i=0; i < account_type_id.length; i++) {
		if (account_type_id[i].selected) {
			values += account_type_id[i].value;		
		}
	}
	
 	var url = "index.php?action=get_account_sub_type&account_type_id="+values;
	AbortCurrentCallIfInUse(http);
	http.open("GET", url, true);
	http.onreadystatechange = AddAccountSubTypes;
	http.send("");
}


function AddAccountSubTypes()
{
	if (http.readyState != 4) return;
   if (http.status != 200) { alert('Receive HTTP response '+http.status); return; }
   
   var root = http.responseXML;
	if (!root){
   	alert("Error when retrieving study lists.\n\n"+http.responseText);
      return;
	}
   
	obj_account_sub_type_id = document.getElementById('account_sub_type_id');
	
	ClearListBox(obj_account_sub_type_id);
	
	// Add new selections to study dropdown boxes
   var account_sub_types = root.getElementsByTagName('account_sub_type');
   for (var s=0; s<account_sub_types.length; s++){
   	var account_sub_type_id = account_sub_types[s].getAttribute('account_sub_type_id');
      var account_sub_type_descrption = account_sub_types[s].getAttribute('account_sub_type_description');
      
      AddOptionToListBox(obj_account_sub_type_id, account_sub_type_descrption, account_sub_type_id);
	}
}

var timeoutId;
var textBox;
var oldval;
var sectionId;

/**
* TimeOutCaller()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 20 18:57:15 PST 2006
*/
function TimeOutCaller(tb, section)
{
	if (timeoutId != null) {
      window.clearTimeout(timeoutId);
      textBox = tb;
      sectionId = section;
   }
   timeoutId = window.setTimeout("GetMatchingUsers()", 800);	
}

/**
* GetMatchingUsers()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 20 18:27:09 PST 2006
*/
function GetMatchingUsers()
{
	user_name = textBox.value;
	url = "?action=get_matching_users&query="+ user_name +"&dest="+ sectionId;
	AbortCurrentCallIfInUse(http);
	http.open("GET", url, true);
	http.onreadystatechange = AddMatchingUsers;
	http.send("");
	
}

/**
* AddMatchingUsers()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 20 18:28:21 PST 2006
*/
function AddMatchingUsers()
{
	if (http.readyState != 4) return;
   if (http.status != 200) { alert('Receive HTTP response '+http.status); return; }
   
   var root = http.responseXML;
	if (!root){
   	alert("Error when retrieving study lists.\n\n"+http.responseText);
      return;
	}
   
	var meta = root.getElementsByTagName('meta');
	var dest_id = meta[0].getAttribute('dest');
	
	obj_login = document.getElementById('login_'+dest_id);
	
	ClearListBox(obj_login);
	
	// Add new selections to study dropdown boxes
   var user = root.getElementsByTagName('user');
   for (var s=0; s<user.length; s++){
   	var login = user[s].getAttribute('login');
      var user_name = user[s].getAttribute('user_name');
      
      AddOptionToListBox(obj_login, login + " - " + user_name, login);
	}
	
}

/**
* ClientHasAccount()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Feb 21 12:53:12 PST 2006
*/
function ClientHasAccount(obj)
{
	var obj2 				= document.getElementById('tr_get_account_detail');
	var obj_r_product_id = document.getElementById('r_product_account_id');

	if (obj.checked) {
		obj2.style.display = '';
		obj_r_product_id.name = 'r_product_account_id'; 		
	} else {
		obj2.style.display = 'none';
		obj_r_product_id.name = 'to_rename'; 
	}
}

/**
* SetPricingRegime()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Mar 21 14:32:45 PST 2006
*/
function SetPricingRegime(product_id, row)
{
	var account_sub_type = document.getElementsByName('account_sub_type_id')[0].value;
	var o_pricing_regime = document.getElementsByName('pricing_regime_id_'+row)[0];
	
	if (account_sub_type == 2 || account_sub_type == 4) {
		o_pricing_regime.value = 3;
	} else {
		o_pricing_regime.value = 2;
	}
	
	
}

/**
* ValidateExtra()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jun 26 09:59:11 PDT 2006
*/
function ValidateExtra(country_code)
{
	var form = document.getElementById('searchform');
	
	var country_required_zip      = new Array('USA', 'CAN', 'AUS');
	var country_required_state    = new Array('USA', 'CAN', 'AUS');
	var country_required_province = new Array('GBR');
	
	var obj_zip      = document.getElementById('span_address_zip');
	var obj_state    = document.getElementById('span_address_state');
	var obj_province = document.getElementById('span_address_province');
	
	var element_zip       = document.getElementById('r_address_zip');
	var element_state     = document.getElementById('r_address_state');
	var element_province  = document.getElementById('r_address_province');
	
	
	var html_zip      = '';
	var html_state    = '';
	var html_province = '';
		
	
	/* 
	 * ZIP CODE
	 * if the country is in the zip required country list we set the zip code as required 
	 */
	if (country_required_zip.inArray(country_code) && element_zip == null) {		
		
		/* add to our form */
		form.appendChild(GetValidationElement('address_zip', 'Zip Code is Required', 'required'));
		
		/* set the astrisk on our span */
		html_zip = "*";
		
	} else if(element_zip != null) {
		form.removeChild(element_zip);
	}
	
	obj_zip.innerHTML = html_zip;
	
	/*
	 * State Province
	 *
	 */
	
	if (country_required_state.inArray(country_code) && element_state == null) {		
		
		/* add to our form */
		form.appendChild(GetValidationElement('address_state', 'State is Required', 'required'));
		
		/* set the astrisk on our span */
		html_state = "*";
		
	} else if(element_state != null) {
		form.removeChild(form.r_address_state);
	}
	
	obj_state.innerHTML = html_state;
	
	if (country_required_province.inArray(country_code) && element_province == null) {		
		
		/* add to our form */
		form.appendChild(GetValidationElement('address_province', 'Province is Required', 'required'));
		
		/* set the astrisk on our span */
		html_province = "*";
		
	} else if(element_province != null) {
		form.removeChild(form.r_address_province);
	}
	
	obj_province.innerHTML = html_province;
	
	
	
	
	
	
	return true;
	
}

/**
* GetValidationElement()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jun 26 10:53:26 PDT 2006
*/
function GetValidationElement(name, message, flag)
{
	if (flag == 'required') 
		name = 'r_' + name;
	
	/* create the hidden element */
	var hidden = document.createElement("INPUT");
		
	/* set the attributes */
	hidden.type  = 'hidden';
	hidden.name  = name
	hidden.id    =  name
	hidden.value = message;
	
	return hidden;
}

/**
* SetLoginAfterUpdate()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Jun 26 18:45:26 PDT 2006
*/
function SetLoginAfterUpdate(arg1, arg2)
{
	var element_name = arg1.name;
	var row_id       = element_name.match("[0-9]+$");
	var hidden_login = document.getElementById('login_' + row_id);
	
	var nodes = arg2.childNodes.length;
	
	for (i=0; i < nodes; i++) {
		if (arg2.childNodes[i].type == 'hidden') {
			hidden_login.value = arg2.childNodes[i].value;
			/* set the login id to the hidden field */
		}
	}
	
	return true;
}

/* workaround static variable */
var next_id = new Object();

next_id.user_role = 3;

/**
* AddUserRoleRow()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Jun 27 10:18:46 PDT 2006
*/
function AddUserRoleRow()
{
	var tb     = document.getElementById('tb_user_role');
	var footer = document.getElementById('tr_footer');
	
	var class_name = (GetLastTrClass(tb) == 'tab1') ? 'tab' : 'tab1' ;
	
	var STATIC_ROWS = 5;
	
	/* need to find out what the current max id is */
	if (next_id.user_role == 3) {
		next_id.user_role += tb.rows.length - STATIC_ROWS;
	}
	
	
	var TD_NAME = 1;
	var TD_PRODUCT = 2;
	var TD_ROLE = 3;
	
	var tr = document.createElement("TR");
	tr.className = class_name;
	
	for (i=1; i < 4; i++) {
		
		var td = document.createElement("TD");
		td.align = 'left';
		
		switch (i) {
			
			case TD_NAME:
				/* create the text element */
				var obj_text = document.createElement('input');
				obj_text.type = 'text';
				obj_text.size = 30;
				obj_text.name = 'name_' + next_id.user_role;
				obj_text.id   = 'name_' + next_id.user_role;
				
				td.appendChild(obj_text);
				/* create the span element */
				//<span name='loader_{$smarty.section.au.iteration}' id='loader_{$smarty.section.au.iteration}' style="display: none;">
	      	//	<img src="/images/ajax/indicator_arrows.gif">
	      	//</span>
	      	var obj_span = document.getElementById('loader_1');
	      	var node = obj_span.cloneNode(true);
	      	node.id   = 'loader_' + next_id.user_role;
	      	node.name = 'loader_' + next_id.user_role;
	      	td.appendChild(node);
	      	
	      	//add div <div class="autocomplete" id="user_list_{$smarty.section.au.iteration}"></div>
	      	var obj_div = document.getElementById('user_list_1');
	      	var node = obj_div.cloneNode(true);
	      	node.id = 'user_list_' + next_id.user_role;
	      	node.name = 'user_list_' + next_id.user_role;
	      	td.appendChild(node);
	      	
	      	//add hidden <input type="hidden" name="login_{$smarty.section.au.iteration}" id='login_{$smarty.section.au.iteration}' value="{$account.user[au].user_id}">
	      	var obj_hidden = document.createElement('input');
	      	obj_hidden.type = 'hidden';
	      	obj_hidden.name = 'login_' + next_id.user_role;
	      	obj_hidden.id   = 'login_' + next_id.user_role;
				td.appendChild(obj_hidden);
				
				break;
				
			case TD_PRODUCT:
				var obj_product_id = document.getElementById('product_id_1');
				var node = obj_product_id.cloneNode(true);
				node.id = 'product_id_' + next_id.user_role;
				node.name = 'product_id_' + next_id.user_role;
				td.appendChild(node);
				break;
				
			case TD_ROLE:
				var obj_role_id = document.getElementById('role_id_1');
				
				var node  = obj_role_id.cloneNode(true);
				node.id   = 'role_id_' + next_id.user_role;
				node.name = 'role_id_' + next_id.user_role;
				
				td.appendChild(node);
				break;
			
			
		}
		
		tr.appendChild(td);
	}
	
	/* add our row before the footer */
	tb.insertBefore(tr, footer);
	
	/* create the ajax autocompleter instance */
	new Ajax.Autocompleter('name_' + next_id.user_role, 
	      		'user_list_' + next_id.user_role, 
	      		'?action=get_matching_users&row_id=' + next_id.user_role, 
	      		{
	      			afterUpdateElement: SetLoginAfterUpdate, 
	      			minChars: 3,
	      			indicator: 'loader_' + next_id.user_role
	      		}
	      	);
	
	/* increment the id */
	next_id.user_role++;
	
	return true;
	
}

/**
* GetLastTrClass()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Jun 27 10:55:33 PDT 2006
*/
function GetLastTrClass(table)
{
	return table.rows[( table.rows.length - 2 )].className;
}

next_id.panel_country = 2;
/**
* AddPanelCountryRow()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Jun 27 12:34:59 PDT 2006
*/
function AddPanelCountryRow()
{
	var tb     = document.getElementById('tb_panel_country');
	var footer = document.getElementById('tr_footer');
	var new_rows = document.getElementById('new_rows');
	var update_rows = document.getElementById('update_rows');
	
	var class_name = (GetLastTrClass(tb) == 'tab1') ? 'tab' : 'tab1' ;
	
	var STATIC_ROWS = parseInt(new_rows.value) + parseInt(update_rows.value);
	
	/* need to find out what the current max id is */
	if (next_id.panel_country == 2) {
		//alert(STATIC_ROWS);
		next_id.panel_country += tb.rows.length - STATIC_ROWS;
	}
	
	var TD_COUNTRY = 1;
	var TD_COUNT = 2;
	var TD_RESPONSE = 3;
	
	var tr = document.createElement("TR");
	tr.className = class_name;
	
	for (i=1; i < 4; i++) {
		
		var td = document.createElement("TD");
		td.align = 'left';
		
		switch (i) {
			
			case TD_COUNTRY:
				var obj_select = document.getElementById('country_code_1');
				var node       = obj_select.cloneNode(true);
				node.id        = 'country_code_' + next_id.panel_country;
				node.name      = 'country_code_' + next_id.panel_country;
				node.selectedIndex = -1;
				td.appendChild(node);
				break;
				
			case TD_COUNT:
				var obj_panel_count = document.getElementById('panel_count_1');
				var node = obj_panel_count.cloneNode(true);
				node.id   = 'panel_count_' + next_id.panel_country;
				node.name = 'panel_count_' + next_id.panel_country;
				node.value = '';
				td.appendChild(node);
				break;
				
			case TD_RESPONSE:
				var obj_response_rate = document.getElementById('response_rate_1');
				var node  = obj_response_rate.cloneNode(true);
				node.id   = 'response_rate_' + next_id.panel_country;
				node.name = 'response_rate_' + next_id.panel_country;
				node.value = '';
				td.appendChild(node);
				break;
		}
		
		tr.appendChild(td);
	}
	
	/* add our row before the footer */
	tb.insertBefore(tr, footer);
	
	
	new_rows.value = next_id.panel_country;
	
	/* increment the id */
	next_id.panel_country++;
	
	
	return true;
	
}

function ValidateCreditLimit()
{
	old_credit_limit = document.getElementById('old_credit_limit').value;
	new_credit_limit = document.getElementById('new_credit_limit').value;
	
	if(old_credit_limit != new_credit_limit) {
		comment = document.getElementById('comment').value;
		if(comment == "") {
			alert("Enter the reason why you want to change the credit limit");
			return false;
		}
	} else {
		return false;
	}
	return true;
}

function CheckUserPassword(frm)
{ 	
	if(check(frm) && CheckPoratal()) {
		frm.submit();
	}	
	return false;
}

function CheckPoratal()
{
	if(document.searchform.PORTAL_ACCESS_TYPE_ID.value > 1) {		
		var password  = document.searchform.PORTAL_PASSWORD;
		if(password.value == '') {
			alert('Please enter a Portal Password..!');
			password.focus();
			password.style.backgroundColor = "yellow";			
			return false;
		}
	}
	return true;
	
}

function IsNumeric(strString)
{
   var strValidChars = "0123456789.-";
   var strChar;
   var blnResult = true;

   if (strString.length == 0) return false;

   for (i = 0; i < strString.length && blnResult == true; i++)
      {
      strChar = strString.charAt(i);
      if (strValidChars.indexOf(strChar) == -1)
         {
         blnResult = false;
         }
      }
   return blnResult;
}

function CheckCreditLimit(frm, id)
{ 
	var r = true;
	if(document.getElementById(id).value != '') {		
		r=IsNumeric(document.getElementById(id).value);  
	}
	
	if(!r){ 
		alert('Please enter numbers for the Credit limit!'); return false; 
	} else { 
		check(frm);		
	}
	
	
}


function CheckAvailability(index)
{
	for(var i= 0; i<5; i++) {
		if(i == index) continue;
		if( document.getElementById("product_id_" + i) && document.getElementById("product_id_" + i).value == document.getElementById("product_id_" + index).value && document.getElementById("product_id_" + index).selectedIndex != 0 ){
			alert("Duplicate Product, Please select another.");
			document.getElementById("product_id_" + index).selectedIndex = 0;
			return true;
		}
	}
	
}

function display_add_vendor(account_id)
{
	url = '/app/Account/DisplayAddAccountVendor/account_id/'+account_id;
	spec = 'width=500,height=500,scrollbars=yes,resizable=yes';
	window.open(url,'app_popup_acm_user',spec);
	return 1;
}