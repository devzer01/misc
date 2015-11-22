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
function GetAjaxPortlet(target, portlet_id, id)
{
	url = '?action=get_portlet_by_ajax&target='+target+'&portlet_id='+portlet_id;
	http_temp = eval('http_'+id);
	AbortCurrentCallIfInUse(http_temp);
	http_temp.open("GET", url, true);
	http_temp.onreadystatechange = new Function("DisplayPortletHtml("+id+")");
	http_temp.send("");
}

/**
* DisplayPortletHtml()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sat Mar 25 11:57:02 PST 2006
*/
function DisplayPortletHtml(id)
{
	
	http_temp = eval('http_'+id);
	if (http_temp.readyState != HTTP_READY_STATE_COMPLETED) 
		return true;
	
	var root = http_temp.responseXML;
	if (!root){
   		//alert("Error when retrieving study lists.\n\n"+http_temp.responseText);
      	return;
	}
	
	var body = root.getElementsByTagName('body');
	var html = body[0].firstChild.nodeValue;
	
	var target_elm = root.getElementsByTagName('target');
	var target_id = target_elm[0].firstChild.nodeValue;
	
	var target = document.getElementById(target_id);
	
	//target.innerHTML = URLDecode(unescape(html));
	target.innerHTML = html;
	//dynamiccontentNS6(target_id, html);

}

/*
function dynamiccontentNS6(elementid,content)
{
	if (document.getElementById && !document.all){
		rng = document.createRange();
		el = document.getElementById(elementid);
		rng.setStartBefore(el);
		htmlFrag = rng.createContextualFragment(content);
	
		while (el.hasChildNodes())
			el.removeChild(el.lastChild);
		el.appendChild(htmlFrag);
	}
} */

function URLDecode(encoded)
{
   // Replace + with ' '
   // Replace %xx with equivalent character
   // Put [ERROR] in output if %xx is invalid.
   var HEXCHARS = "0123456789ABCDEFabcdef"; 
   var plaintext = "";
   var i = 0;
   while (i < encoded.length) {
       var ch = encoded.charAt(i);
	   if (ch == "+") {
	       plaintext += " ";
	       i++;
//	   } else {
//	   	plaintext += ch;
//	   }
	   
	   } else if (ch == "%") {
			if (i < (encoded.length-2) 
					&& HEXCHARS.indexOf(encoded.charAt(i+1)) != -1 
					&& HEXCHARS.indexOf(encoded.charAt(i+2)) != -1 ) {
				plaintext += unescape( encoded.substr(i,3) );
				i += 3;
			} else {
				alert( 'Bad escape combination near ...' + encoded.substr(i) );
				plaintext += "%[ERROR]";
				i++;
			}
		} else {
		   plaintext += ch;
		   i++;
		}
	} // while
   return  plaintext;
};

/**
* AddPortlet()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun Mar 26 11:43:10 PST 2006
*/
function AddPortlet()
{
	var portlet_id = document.getElementById('portlet_id').value;
	
	if (portlet_id == '') {
		alert('Select a Portlet First');
		return false;
	}
	
	for (i=1; i < 10; i++) {
		var target_html = document.getElementById('portlet_'+i).innerHTML;
		
		if (target_html == '' || target_html == '&nbsp;') {
			GetAjaxPortlet('portlet_'+i, portlet_id, i);
			
			var sort_order = document.getElementById('sort_order_'+i);
			sort_order.value = portlet_id;
			return true;
		}
	}
}