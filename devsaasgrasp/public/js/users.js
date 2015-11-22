function changeDisplay(step) {
	var TYPE_NONE = 0;
	var TYPE_PHONE = 1;
	var TYPE_ADDRESS = 2;
	var ctype = 0;
	if (document.all('phoneOrAddress')[0].checked == true) ctype = 1;
	if (document.all('phoneOrAddress')[1].checked == true) ctype = 2;
	if (ctype == TYPE_NONE) {
		//hide just about everything
		hidePhone();
		hideAddress();
	} else if (ctype == TYPE_PHONE) {
		//show phone table, hide others
		showPhone();
		hideAddress();
	} else if (ctype == TYPE_ADDRESS) {
		//show address table, hide others
		showAddress();
		hidePhone();
	}
}

function hidePhone() {
	document.all('phoneTable').style.display = 'none';
}

function hideAddress() {
	document.all('addressTable').style.display = 'none';
}

function showPhone() {
	document.all('phoneTable').style.display = 'block';
}

function showAddress() {
	document.all('addressTable').style.display = 'block';
}

function removeContactConfirm(url) {
	if (confirm("Are you sure you want to remove this contact?")) {
		window.location = url;
	}
}
function removePhoneConfirm(url) {
	if (confirm("Are you sure you want to remove this phone number?")) {
		window.location = url;
	}
}
function removeAddressConfirm(url) {
	if (confirm("Are you sure you want to remove this address?")) {
		window.location = url;
	}
}

function checkPasswords(form) {
	if (form.newpass1.value == "") {
		alert("You must enter a password!");
		form.newpass1.focus();
		form.newpass1.select();
		return false;
	} else if (form.newpass1.value != form.newpass2.value) {
		alert("The two passwords do not match");
		return false;
	} else if (form.newpass1.value.length < 7) {
		alert("Passwords must be at least 7 characters long")
		return false;
	} else {
		form.submit();
		return true;
	}
}

function checkPhoneType(form) {
	if (form.phone_type_id.options[form.phone_type_id.selectedIndex].value == 5) {
		form.phone_country_code.disabled=true;
		form.contact_phone_ext.disabled=true;
	} else {
		form.phone_country_code.disabled=false;
		form.contact_phone_ext.disabled=false;
	}
}

function selfCheck(form) {
	if (form.contact_type_id.options[form.contact_type_id.selectedIndex].value == 3) {
		//fill in appropriate fields automatically... if possible
		form.contact_first_name.value = form.self_first_name.value;
		form.contact_last_name.value = form.self_last_name.value;
		form.contact_email.value = form.self_email_address.value;
	}
}

function showRights(num) {
	var obj = document.getElementById("rights_"+num);
	var pic = document.getElementById("exp_rights_"+num);
	if (obj.style.display == "none") {
		obj.style.display = "block";
		pic.src = "/images/rollminus.gif";
	} else {
		obj.style.display = "none";
		pic.src = "/images/rollplus.gif";
	}
}

function expand(num) {
	var t = document.getElementById("t_"+num);
	var i = document.getElementById("i_"+num);
	if (t.style.display == "none") {
		t.style.display = "block";
		i.src = "/images/rollminus.gif";
	} else {
		t.style.display = "none";
		i.src = "/images/rollplus.gif";
	}
}

function showAddReportee() {
	var button1 = document.getElementById("r_button1");
	var table1 = document.getElementById("r_table");
	button1.style.display = "none";
	table1.style.display="block";
}











function confirmRemoveReportee(url) {
	if (confirm("Are you sure you want to remove this user as a reportee?")) {
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
