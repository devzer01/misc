
function addGroup() {
	var button = document.getElementById('startAddButton');
	var tr1 = document.getElementById('hideme');
	var tr2 = document.getElementById('hidemetoo');
	var tr3 = document.getElementById('hidemethree');
	tr1.style.display = "block";
	tr2.style.display = "block";
	tr3.style.display = "block";
	button.style.display = "none";
}

function changeOwner() {
	var b = document.getElementById('changeOwnerButton');
	var tr1 = document.getElementById('newOwnerRow');
	var tr2 = document.getElementById('newOwnerRow2');
	b.style.display = "none";
	tr1.style.display = "block";
	tr2.style.display = "block";
}





function confirmRemoveGroup(url) {
	if (confirm("Are you sure you want to completely remove this functional group?")) {
		window.location = url;
	}
}

var timeoutId;
var oldval;
var textBox;

function userSearch(tb) {
	if (timeoutId != null) {
		window.clearTimeout(timeoutId);
		textBox = tb;
	}
	timeoutId = window.setTimeout("timeoutTrigger()", 1000);
}

function timeoutTrigger() {
	if (textBox.value.length > 1 && textBox.value != oldval) {
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

function confirmRemoveFgroupUser(url) {
	if (confirm("Are you sure you want to completely remove this user from this functional group?")) {
		window.location = url;
	}
}