

ie = document.all?1:0
ns4 = document.layers?1:0


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

function CopyListBoxOptions(src, dest){
   total_options = src.options.length;
   for (var i=0; i<total_options; i++){
      dest.options[dest.options.length] = new Option(src.options[i].text, src.options[i].value);
   }
}


function popup(url, windowName, width, height)
{
   spec = 'width='+width+',height='+height+',scrollbars=yes,resizable=yes';
   window.open(url, windowName, spec);
}


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

////////////////////////////////////////////////////////////
function upload_file(f)
{
   var spec_lock = document.getElementById('spec_lock');
   if (spec_lock.value == 1) {
      alert('Changes to Specification is locked, only your upload files will be uploaded');
      f.file_upload.value = 1;
   }

   f.submit();
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

var XML_REQUEST_COMPLETE = 4;
var HTTP_RESPONSE_OK = 200;
var ASYNC_REQUEST = true;
