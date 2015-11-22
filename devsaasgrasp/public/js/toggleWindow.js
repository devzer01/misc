function isCollapsed(div_id) {
	if(document.getElementById(div_id).style.display=='none'){
		return true;
	}else{
		return false;
	}
}

// toggleWindow Javascript
function toggleWindow(div_id)
	{
	if (isCollapsed(div_id)) {
		expandWindow(div_id);
   	}
	else
	{
		collapseWindow(div_id);
	}
}

// expandWindow
function expandWindow(div_id) {
	document.getElementById(div_id).style.display='';
	document.getElementById(div_id+':arrow').src='/images/rollminus.gif';
}

// collapseWindow
function collapseWindow(div_id) {
	document.getElementById(div_id).style.display='none';
	document.getElementById(div_id+':arrow').src='/images/rollplus.gif';
}

//////////////////////////////////////////////////
function newConfirm(title,mess,icon,defbut,mods) {
   var IE4 = document.all;
   if (IE4) {
      icon = (icon==0) ? 0 : 2;
      defbut = (defbut==0) ? 0 : 1;
      retVal = makeMsgBox(title,mess,icon,4,defbut,mods);
      retVal = (retVal==6);
   }
   else {
      retVal = confirm(mess);
   }
   return retVal;
}
