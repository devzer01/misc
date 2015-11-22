ie = document.all?1:0
ns4 = document.layers?1:0


var http = GetHTTPObject();
var http_1 = GetHTTPObject();
var http_2 = GetHTTPObject();
var http_3 = GetHTTPObject();
var http_4 = GetHTTPObject();
var http_5 = GetHTTPObject();
var http_6 = GetHTTPObject();
var http_7 = GetHTTPObject();
var http_8 = GetHTTPObject();
var http_9 = GetHTTPObject();
var http_10 = GetHTTPObject();

var timerId = null;
var XML_REQUEST_COMPLETE = 4;
//var HTTP_RESPONSE_OK = 200;
var ASYNC_REQUEST = true;

var HTTP_READY_STATE_COMPLETED = 4;
var HTTP_RESPONSE_OK				= 200;

function GetParentElement(e, tagName, tagRecursive){
   if( ie ){
   	while( e.tagName != tagName ){
      	e = e.parentElement;
   	}
   } else {
   	while( e.tagName != tagName ){
   		e = e.parentNode;
   	}
   }
   if( tagRecursive > 0 ){
   	if( ie ) {
       	e = GetParentElement( e.parentElement, tagName, tagRecursive-1 );
   	} else {
   		e = GetParentElement( e.parentNode, tagName, tagRecursive-1 );
   	}
   }
   return e;
}





function ClearListBox(e){
  for (var i=e.options.length-1; i>=0; i--){
    e.options[i] = null;
  }
  e.selectedIndex = -1;
}

function AddOptionToListBox(e, name, value){
   e.options[e.options.length] = new Option(name, value);
}


function popup(url, windowName, width, height)
{
   spec = 'width='+width+',height='+height+',scrollbars=yes,resizable=yes';
   window.open(url, windowName, spec);
}





// Following function is obtained from http://www.rgagnon.com/jsdetails/js-0096.html
function UrlEncode(sStr) {
   return escape(sStr).
          replace(/\+/g, '%2B').
             replace(/\"/g,'%22').
                replace(/\'/g, '%27').
                   replace(/\//g,'%2F');
}











// Following function is obtained from http://jibbering.com/2002/4/httprequest.html and http://www.webpasties.com/xmlHttpRequest/xmlHttpRequest_tutorial_1.html
function GetHTTPObject() {
	var xmlhttp;
	/*@cc_on
	@if (@_jscript_version >= 5)
		try {
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (E) {
				xmlhttp = false;
				alert( "Unable to create XMLHttpRequest object. 1" );
			}
		}
	@else
		xmlhttp = false;
		alert( "Unable to create XMLHttpRequest object. 2" );
	@end @*/
	if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
		try {
			xmlhttp = new XMLHttpRequest();
		} catch (e) {
			xmlhttp = false;
			alert( "Unable to create XMLHttpRequest object. 3" );
		}
	}
	return xmlhttp;
}



function AbortCurrentCallIfInUse(xmlhttp) {
	if( isCallInProgress(xmlhttp) ){
		xmlhttp.abort();
	}
}

// Following function is obtained from http://ajaxblog.com/archives/2005/06/01/async-requests-over-an-unreliable-network
function isCallInProgress(xmlhttp) {
	switch ( xmlhttp.readyState ) {
       case 1, 2, 3:
           return true;
       	break;
       default: // Case 4 and 0
           return false;
       	break;
   }
}

var account_name;
var callback;
var target;
var timeout_id;

/**
* TimeOutCaller()
*
* @param
* @param -
* @return
* @throws
* @access
* @global //'LookupAccount', this.value, 'span_account', 'TestFunc'
* @since  - Mon Feb 20 18:57:15 PST 2006
*/
function TimeOutCaller(TimeOutFunction)
{	
	if (timeout_id != null) {
      window.clearTimeout(timeout_id);
   }
   str_caller = TimeOutFunction+"(";
   
   for (i=1; i < (TimeOutCaller.arguments.length - 1); i++ ) {
   	str_caller += "'" + TimeOutCaller.arguments[i] + "', ";
   }
   
   str_caller += "'" + TimeOutCaller.arguments[(TimeOutCaller.arguments.length - 1)] + "')";
   //str_caller = TimeOutFunction+"('"+ value +"','"+ target + "','" + CallBackFunction +"', '"+ key +"', '" + description +"', '"+ element +"')";
   timeout_id = window.setTimeout(str_caller, 800);	
}

/**
* HBJAXCall()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 23 23:08:04 PST 2006
*/
function HBJAXCall(action, value, target, callback, key, description, element)
{	
	url = "/common/index.php?action="+ action +"&value="+ value +"&target="+ target +"&key="+ key +"&description="+ description +"&element="+ element;
	AbortCurrentCallIfInUse(http);
	http.open("GET", url, true);
	http.onreadystatechange = eval(callback);
	http.send("");

}

/**
* LookupAccountContact()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Dec 2 PST 2008
*/
function LookupAccountContact(action, value, value2, callback)
{	
	if(value != ""){		
		url = "/common/index.php?action="+ action +"&value="+ value +"&value2="+ value2;		
		AbortCurrentCallIfInUse(http);
		http.open("GET", url, true);
		http.onreadystatechange = eval(callback);
		http.send("");
	}else{
		ClearTexBoxes();
	}
}

function DisplayContactValues()
{
	if (http.readyState != HTTP_READY_STATE_COMPLETED) 
		return true;
	
	var root = http.responseXML;
	if (!root){
   	alert("Error when retrieving study lists.\n\n"+http.responseText);
      return;
	}		
		
	var e = root.getElementsByTagName('contact');			
	
	document.getElementById('contact_first_name').value 	= e[0].getAttribute('contact_first_name');
	document.getElementById('contact_last_name').value 	= e[0].getAttribute('contact_last_name');
	document.getElementById('contact_email').value 			= e[0].getAttribute('contact_email');
	document.getElementById('contact_phone_number').value = e[0].getAttribute('phone_number');
	document.getElementById('address_street_1').value 		= e[0].getAttribute('street_one');
	document.getElementById('address_street_2').value 		= e[0].getAttribute('street_two');
	document.getElementById('address_city').value 			= e[0].getAttribute('city');
	document.getElementById('address_state').value 			= e[0].getAttribute('state');
	document.getElementById('address_zip').value 			= e[0].getAttribute('zip');
	document.getElementById('contact_fax_number').value 	= e[0].getAttribute('fax_number');
	document.getElementById('address_province').value 		= e[0].getAttribute('address_province');
	
	var obj = document.getElementById('address_country_code');   
  
	obj.selectedIndex = GetListIndexByValue(obj, e[0].getAttribute('country_code'));
		 
}

function GetListIndexByValue(obj, value)
{
	var options = obj.options;
	
	for(var i=0; i < obj.length; i++)
	{
		if(options[i].value == value)
		{
			return i;
		}
	}
	return -1;	
}


function ClearTexBoxes()
{
	document.getElementById('contact_first_name').value 	= "";
	document.getElementById('contact_last_name').value 	= "";
	document.getElementById('contact_email').value 			= "";
	document.getElementById('contact_phone_number').value = "";
	document.getElementById('address_street_1').value 		= "";
	document.getElementById('address_street_2').value 		= "";
	document.getElementById('address_city').value 			= "";
	document.getElementById('address_state').value 			= "";
	document.getElementById('address_zip').value 			= "";
	document.getElementById('contact_fax_number').value 	= "";
	document.getElementById('address_province').value 		= "";
	document.getElementById('address_country_code').selectedIndex = 0;
}

/**
* LookupAccount()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 23 15:31:21 PST 2006
*/
function LookupAccount(account, target, callback, key, description, element)
{
	url = "/common/index.php?action=lookup_account&account="+ account +"&target="+ target +"&key="+ key +"&description="+ description +"&element="+ element;
	AbortCurrentCallIfInUse(http);
	http.open("GET", url, true);
	http.onreadystatechange = eval(callback);
	http.send("");
}


/**
* LookupAccount()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 23 15:31:21 PST 2006
*/
function LookupUserRole(role_id, target, callback, key, description, element)
{
	url = "/common/index.php?action=lookup_role_user&role_id="+ role_id +"&target="+ target +"&key="+ key +"&description="+ description +"&element="+ element;
	AbortCurrentCallIfInUse(http);
	http.open("GET", url, true);
	http.onreadystatechange = eval(callback);
	http.send("");
}


/**
* DisplayListValues()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 23 15:46:47 PST 2006
*/
function DisplayListValues()
{
	if (http.readyState != HTTP_READY_STATE_COMPLETED) 
		return true;
	
	var root = http.responseXML;
	if (!root){
   	alert("Error when retrieving study lists.\n\n"+http.responseText);
      return;
	}
	
	var meta = root.getElementsByTagName('meta');
	
	var target_id   = meta[0].getAttribute('target');
	var description = meta[0].getAttribute('description');
	var key         = meta[0].getAttribute('key');
	var element     = meta[0].getAttribute('element');
	
	obj_account_id = document.getElementById(target_id);
	
	
	
	
	ClearListBox(obj_account_id);
	
	// Add new selections to study dropdown boxes
   var e = root.getElementsByTagName(element);
   
   if (e.length == 0) {
   	AddOptionToListBox(obj_account_id, 'No Values', '');
   	return false;
   }
   
   AddOptionToListBox(obj_account_id, 'Select From List', '');
   
   for (var s=0; s<e.length; s++){
   	var key_value = e[s].getAttribute(key);
      var description_value = unescape(e[s].getAttribute(description));
      
      AddOptionToListBox(obj_account_id, key_value + " - " + description_value, key_value);
	}
	
}

/**
* ClearValue()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 23 22:46:23 PST 2006
*/
function ClearValue(current, original_value)
{
	if (current.value == original_value) {
		current.value = '';
		current.focus();
	}
}

/**
* LookupContact()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 23 22:52:06 PST 2006
*/
function LookupContact(account_id, target, callback)
{
	//TODO
	
}

/**
* DisplayTableValues()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 24 18:30:04 PST 2006
*/
function DisplayTableValues(table)
{
	var number_of_columns = DisplayTableValues.arguments.length - 1;
	
	var tbl = document.getElementById(table);
  	var lastRow = tbl.rows.length;
  	// if there's no header row in the table, then iteration = lastRow + 1
  	var iteration = lastRow;
  	var row = tbl.insertRow(lastRow);
  	
  	var last_class = tbl.rows[(lastRow - 1)].className;
  	
  	if (last_class == 'tab') {
  		row.className = 'tab1';	
  	} else {
  		row.className = 'tab';
  	}
  	
  	
  
  	for (i=1; i < DisplayTableValues.arguments.length; i++) {
	  	var cellLeft = row.insertCell((i - 1));
	  	var textNode = document.createTextNode('td value');
	  	cellLeft.appendChild(textNode);
  	}	
}

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


function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function openRoboHelp(module_code) { 
   window.open("/help/?module_code="+module_code, 'hbhelp', 'status=0,toolbar=0,location=0,menubar=0,directories=0,scrollbars=1,resizable=1,dependent=1,width=900,height=600'); 
}

function ClearSearch(f) {
   for(i=0; i < f.length; i++) {
      switch (f.elements[i].type)
      {
         case "text": f.elements[i].value = ""; break;
         case "select-one": 
         case "select-multiple": f.elements[i].selectedIndex = 0; break;
      }
   }
   f.submit();
   
}

/**
* Chech for negative value is entered in the text box
* if so, will not allow to proceed.
* @author msilva
* @since 11-28-2007
*/
function isNegative(value)
{	
	return (parseFloat(value) < 0);
}

/**
* Create the YUI Tab view
*
*/
function TabView(element)
{
	var tabView = new YAHOO.widget.TabView(element);   
}

/**
* Create the YUI Button and check boxes
*
*/
function CreateYUIButton(ele_type, ele_label, ele_id, ele_value)
{	
	var obutton = 'button_'+ ele_id;
	
	var obutton = new YAHOO.widget.Button({ type:ele_type, label: ele_label, id: ele_id, name: ele_id, value:  ele_value, container: ele_id });
	
	return obutton;
}

function CreateRadioButton(radio_id, radio_name, radio_values)
{
	var oradiobutton = new YAHOO.widget.ButtonGroup({  
	                                id:  radio_id,  
	                                name:  radio_name,  
	                                container:  radio_id }); 

	var str = "";     
		                           
	for(var key in radio_values) {
	 str += '{ label: '+key+', value: '+radio_values[key]+' }';	   
	}  	
	oradiobutton.addButtons([str]); 
	
}

/**
* Create light box object and return
*
*/
function CreateUserLightBox(options)
{
	var default_args = {
		'id' : "lightbox_"+options['id'],
		'width' : "470px",
		'fixedcenter' : true,
		'visible': true,
		'draggable:': true,
		'modal': true,
		'close':true,
		'constraintoviewport': true
	}
	
	for(var index in default_args) {		
		if(typeof options[index] == "undefined") options[index] = default_args[index];					
	}
	 
	var dialog_box = options['id'];	
	
	dialog_box = new YAHOO.widget.Dialog(options['id'],
        { width : options['width'],
	   fixedcenter : options['fixedcenter'],
	   visible : options['visible'],
	   draggable: options['draggable'],
	   modal: options['modal'],
	   close: options['close'],
	   constraintoviewport : options['constraintoviewport']
	} );
	
	dialog_box.render();
	dialog_box.hide();
	
	
	return dialog_box;
}

//this function is to move selected options from one list box to another
//for usage demonstration check on primary_screen_main_tab_menu.tpl countries adding section
function moveDualList( srcList, destList, moveAll ) 
{
	if (  ( srcList.selectedIndex == -1 ) && ( moveAll == false )   )
  	{
		return;
	}
	
	newDestList = new Array( destList.options.length );
	var len = 0;
	
	for( len = 0; len < destList.options.length; len++ ) {
    	if ( destList.options[ len ] != null )
    	{
			newDestList[ len ] = new Option( destList.options[ len ].text, destList.options[ len ].value, destList.options[ len ].	defaultSelected, destList.options[ len ].selected );
		}

	}
	
	for( var i = 0; i < srcList.options.length; i++ ) { 
    	if ( srcList.options[i] != null && ( srcList.options[i].selected == true || moveAll ) )	{
       		newDestList[ len ] = new Option( srcList.options[i].text, srcList.options[i].value, srcList.options[i].defaultSelected, srcList.options[i].selected );

       		len++;
    	}
  	}
	
	for ( var j = 0; j < newDestList.length; j++ ) {
		if ( newDestList[ j ] != null ) {
      		destList.options[ j ] = newDestList[ j ];
		}
  	}

  	for( var i = srcList.options.length - 1; i >= 0; i-- ) { 
    	if ( srcList.options[i] != null && ( srcList.options[i].selected == true || moveAll ) ) {
       		srcList.options[i]       = null;
		}
  	}
}

/**
* Show the message box
*
*/
function ShowMSGBox(param1){
	 document.getElementById('message_description').innerHTML = "<h3>"+param1+"</h3>";
	 msg_dialog.show();
	 setTimeout("msgtimeout()",2000); 
}

/**
* Time out function for the message box
*
*/
function msgtimeout()
{
	msg_dialog.hide();
}

/**
* Create the Probress Bar
*
*/
function CreateYUIProgressBar()
{
	progress_bar =  
	        new YAHOO.widget.Panel("wait",   
	            { width:"240px",  
	              fixedcenter:true,  
	              close:false,  
	              draggable:false,  
	              zindex:4, 
	              modal:true, 
	              visible:false 
	            }  
	        ); 
	 
	progress_bar.setHeader("Loading, please wait..."); 
	progress_bar.setBody('<img src="/images/ajax_loader.gif" />'); 
	progress_bar.render(document.body);

}

	/* Load Study Category types for the Parent Study category type id
	*
	*/
	function LoadStudyCategoryChild(study_category_type, level)
	{				
		var level_1 = document.getElementById('td_study_category_level_1');
		var level_2 = document.getElementById('td_study_category_level_2');
		
		if(study_category_type.value == 0) {
			if(level == 1) {
			level_1.style.display = 'none';
			level_2.style.display = 'none';			
			}
			
			if(level == 2) {				
				level_2.style.display = 'none';
			}
			
			return false;
		}	
		
	 	var url = "/app/Proposal/LoadStudyCategoryChild/study_category_type_id/"+study_category_type.value +"/level/"+level;
	 	
		AbortCurrentCallIfInUse(http);
		http.open("GET", url, true);
		http.onreadystatechange = AddStudyCategorySubTypes;
		http.send("");
		
		if(level == 1) {
			level_1.style.display = '';
			level_2.style.display = 'none';			
		}
		
		if(level == 2) {
			level_1.style.display = '';
			level_2.style.display = '';
		}
		
	}
	
	function AddStudyCategorySubTypes()
	{
		if (http.readyState != 4) return;
	   if (http.status != 200) { alert('Receive HTTP response '+http.status); return; }
	   
	   var root = http.responseXML;
		if (!root){
	   	alert("Error when retrieving study lists.\n\n"+http.responseText);
	      return;
		}
		
		
		//get element from the response XML format
		var study_category_level 		= root.getElementsByTagName('study_category_level');
		var study_category_sub_types 	= root.getElementsByTagName('study_category_sub_type');
		
		//get the select box that data should be insert
		study_category_level_1_id = document.getElementById('study_category_level_'+study_category_level[0].getAttribute('study_category_level_id'));
		
		ClearListBox(study_category_level_1_id);
				
		//if the length of category types is zero 
		if(study_category_sub_types.length == 0){
			var level_1 = document.getElementById('td_study_category_level_1');
			var level_2 = document.getElementById('td_study_category_level_2');
			level_1.style.display = 'none';
			level_2.style.display = 'none';
			return false;
		}
		
		// If the level = 1 , set the default option
		if(study_category_level[0].getAttribute('study_category_level_id') == 1 ) {
			study_category_level_1 = document.getElementById('study_category_level_1');
			AddOptionToListBox(study_category_level_1, '[-- Select a Category --]', 0);
		}
		
		//load options values and description in to the select box
	   for (var s=0; s<study_category_sub_types.length; s++){
	   	var study_category_sub_type_id = study_category_sub_types[s].getAttribute('study_category_sub_type_id');
	      var study_category_sub_type_descrption = study_category_sub_types[s].getAttribute('study_category_sub_type_description');
	      	      
	      AddOptionToListBox(study_category_level_1_id, study_category_sub_type_descrption, study_category_sub_type_id);
	   }
		
	}

	
var selected=0;
function SelectRemoveAll(frm){
	if(selected){
		$("form#" + frm + " INPUT[type='checkbox']").attr('checked', false);
		document.getElementById('selectremoveall').value = 'Select All';
		selected=0;
	} else {
		$("form#" + frm + " INPUT[type='checkbox']").attr('checked', true);
		document.getElementById('selectremoveall').value = 'Clear All';
		selected=1;
	}
}