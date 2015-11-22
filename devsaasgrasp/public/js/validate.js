var new_fieldname = "";
var bgcolor = "yellow";

function check(form)
{
   var message = "";
	var more_message = "";
	var showmsg = "no";
	var x = form.elements.length;
	x = x - 1;

	for (var i = 0; i <= x; i++) {
     	//var vldtype = form.elements[i].name.substring(0,2);
     	var field = form.elements[i].name.substring(2);
    	var obj = document.getElementById(field);
     
     //if (!obj) obj = document.getElementsByName(field)[0];
     	if (obj && obj.form != form) { /* test if the parent of the element is part of the form we wanted */
      	obj = null;
     	}

		if (!obj) {
     		var objs = document.getElementsByName(field);
      	for (var j=0; j<objs.length; j++){
              if (objs[j].form == form){
                 obj = objs[j];
                 break;
              }
      	}
    	}
      //var obj = eval('form.elements.'+field);
      var msg = form.elements[i].value;

     	var validation_type = form.elements[i].name.substring(0,2);

     if (obj) {

        switch (validation_type) {
            case 'r_':
               more_message = r_check(obj,msg);
               break;
            case 'i_':
               more_message = i_check(obj,msg);
               break;
            case 'e_':
               more_message = e_check(obj,msg);
               break;
            case 'd_':
               more_message = d_check(obj,msg);
               break;
            case 'm_':
               more_message = m_check(obj,msg);
               break;
            case 'n_':
               more_message = n_check(obj,msg);
               break;
				case 'p_':
					more_message = p_check(obj,msg);
					break;
				case 'g_':
					more_message = g_check(obj,msg);
					break;
				case 'o_':
					/* optional
					 * usage set o_field_name to bootstrap  
					 * field_name_parent_obj => dependent_field
					 * field_name_parent_value => dependent_value
					 */
					more_message = o_check(obj, msg);
					break;
					
            default:
               break;
        }
     }

     if (more_message != "") {
      if (message == "") {
         message = more_message;
         more_message="";
      } else {
         message = message + "\n" + more_message;
         more_message="";
      }
     }

     if (message > "") {
      showmsg = "yes";
     }
   }

   //This code will prevent a submit if data is incoorect
   if (showmsg == "yes") {
      var dv_valid = document.getElementById('dv_valid');
      if (dv_valid) {
         dv_valid.innerHTML = message;
         dv_valid.style.display = '';
      }

      alert("The following form field(s) were incomplete or incorrect:\n\n" + message + "\n\n Please complete or correct the form and submit again.");
      return 0;
   }
   var auto_submit = document.getElementById('auto_submit');

   if (!auto_submit || auto_submit.value == 1) {
      form.submit();
   }

   return 1;
}

/**
* o_check()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - 14:43:56
*/
function o_check(obj, msg)
{
	var parent_name  = document.getElementById(obj.name + '_parent_obj').value;
	var parent_value = document.getElementById(obj.name + '_parent_value').value;
	var current_value = '';
	
	/* i dont want the microsoft stupidity to break my code since when i say DocumentById i mean document By ID, Screw You Bill gates */
	if (!document.all) {
		var parent = document.getElementById(parent_name);
	}
	
	if (!parent) {
		parent = document.getElementsByName(parent_name);
		var len = parent.length;
		
		for(i=0; i < len; i++) {
			
			if (parent[i].type == 'radio' && parent[i].checked) {
				current_value = parent[i].value;
				break;
			}
			
		}
		
	} else {
		current_value = parent.value;
	}
	
	if (current_value == parent_value && obj.value == '') {
		obj.style.backgroundColor = bgcolor;
		return msg;
	}
	
	return '';
	
}

function r_check(obj,msg)
{
   var msg_addition = "";

   if (obj.type == "select-one") {
      var l = obj.selectedIndex;
      if (l == -1 || obj.options[l].value == "") {
		   msg_addition = msg;
         obj.style.backgroundColor = bgcolor;
      }
   }  else if (obj.value == "") {
      msg_addition = msg;
      obj.style.backgroundColor = bgcolor;
   }
   return(msg_addition);
}


function i_check(obj,msg)
{
	inputStr = obj.value.toString();

	if (inputStr.match(/[^0-9\.]+/)) {
      obj.style.backgroundColor = bgcolor;
      return msg;
   }

   obj.style.backgroundColor = 'white';

   return '';
 }

function p_check(obj,msg)
{
	inputStr = parseInt(obj.value);
	if (inputStr > 100 || inputStr < 0) {
		obj.style.backgroundColor = bgcolor;
		return msg;
	}

	obj.style.backgroundColor = 'white';
	return '';
}

function g_check(obj,msg) 
{
	inputStr = parseInt(obj.value);
	if (inputStr <= 0) {
		obj.style.backgroundColor = bgcolor;
		return msg;
	}
	
	obj.style.backgroundColor = 'white';
	return '';
}

function g_check(obj,msg) 
{
	inputStr = parseInt(obj.value);
	if (inputStr <= 0) {
		obj.style.backgroundColor = bgcolor;
		return msg;
	}
	
	obj.style.backgroundColor = 'white';
	return '';
}

function e_check(obj,msg)
{
   inputStr = obj.value.toString();

//   if (inputStr.match(/^( [a-zA-Z0-9] )+( [a-zA-Z0-9\._-] )*@( [a-zA-Z0-9_-] )+( [a-zA-Z0-9\._-] +)+$/)) {
   if (inputStr.match(/^([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}$/)) {
      return '';
   }
   obj.style.backgroundColor = bgcolor;
   return msg;
}


function d_check(obj,msg)
{
   inputStr = obj.value.toString();
   if (inputStr == '') {
      return '';
   }

   if(inputStr.match(/[0-9]{4}\-[0-1][0-9]\-[0-3][0-9]/)) {
      return '';
   }
   obj.style.backgroundColor = bgcolor;
   return msg;
}

function m_check(obj,msg)
{
   inputStr = obj.value.toString();
   if (inputStr == '') {
      return '';
   }

   if(inputStr.match(/[0-9]+\.?[0-9]*?$/)) {
      return '';
   }
   obj.style.backgroundColor = bgcolor;
   return msg;
}

function n_check(obj,msg)
{
   inputStr = obj.value.toString();

   obj.style.backgroundColor = bgcolor;
   return msg;
}


function check_date_range(from_date,end_date)
{			
	var f = from_date.value.split('-');
   var e = end_date.value.split('-'); 	
	
	var f_date = new Date(f[0],f[1]-1,f[2]);	
	var e_date = new Date(e[0],e[1]-1,e[2]);	
	
	if ( f_date > e_date )
	{		
		alert("Invalid Date Range!\nStart Date cannot be after End Date!");
		return 0 ;
	}	
	return 1 ;	
}
