function addGroup() {
	var button = document.getElementById('startAddButton');
	var tr1 = document.getElementById('hideme');
	var tr2 = document.getElementById('hidemetoo');
	tr1.style.display = "block";
	tr2.style.display = "block";
	button.style.display = "none";
}


var timeoutId;
var oldval;
var textBox;

function userSearch(tb) {
	if (timeoutId != null) {
		window.clearTimeout(timeoutId);
		textBox = tb;
	}
	timeoutId = window.setTimeout("timeoutTrigger()", 800);
}

function timeoutTrigger() {
	if (textBox!=null && textBox.value!=null && textBox.value.length > 1 && textBox.value != oldval) {
		oldval = textBox.value;
		window.open("?action=user_search&val="+textBox.value+"&name="+textBox.name, 'app_bg');
	}
}

function userSearchConfirm(name) {
	var sel = document.getElementById(name+"_select");
	if (!sel) return false;
	var v = sel.options[sel.selectedIndex].value;
	if (v == "") {
		alert("No matching users");
		return false;
	}
}

function confirmRemoveGroup(url) {
	if (confirm("Are you sure you want to completely remove this security group?")) {
		window.location = url;
	}
}

function showEditSec(sec_id) {
	var url = "?action=vw_edit_sec&id="+sec_id;
	popper(url, true, 600, 200);
}

function popper(e,s,w,h) {
	var newleft = screen.width/2 - w/2;
	var newtop = screen.height/2 - h/2;
	var pop = window.open(e, "pop", "width="+w+",height="+h+",top="+newtop+",left="+newleft+",resizable=yes,status=no,scrollbars="+(s?"yes":"no"), 'pop');
	pop.focus();
}

function confirmRemoveUser(url) {
	if (confirm("Are you sure you want to completely remove this user from this security group?")) {
		window.location = url;
	}
}
