function toggleQuestion(gid, id) {
	//a is for answer!
	var a = document.getElementById("a_"+gid+"|"+id);
	if (a != null) {
		a.style.display = (a.style.display == "none" ? "block" : "none");
	}
}

function toggleGroup(id) {
	//arr is for the array of answers

	var temp = document.documentElement.getElementsByTagName("div");
	var arr = new Array();
	for (var x = 0; x<temp.length; x++) {
			if (temp[x].id != null && temp[x].id.split("|")[0].substr(2) == id) {
			arr.push(temp[x]);
		}
	}

	var newDisplay;

	if (isAllOpen(arr)) {
		newDisplay = "none";
	} else if (isAllClosed(arr)) {
		newDisplay = "block";
	} else {
		newDisplay = "block";
	}
	for (var x = 0; x < arr.length; x++) {
		arr[x].style.display = newDisplay;
	}
}

function isAllOpen(arr) {
	for (var x = 0; x < arr.length; x++) {
		if (arr[x].style.display == "none") return false;
	}
	return true;
}

function isAllClosed(arr) {
	for (var x = 0; x < arr.length; x++) {
		if (arr[x].style.display == "block") return false;
	}
	return true;
}


function checkLength(obj, len, counterId) {
	if (obj.value.length > len) obj.value = obj.value.substr(0, len);
	document.getElementById(counterId).value = obj.value.length;
}



function toggleSubject(id) {
	//s is for subject!
	var s = document.getElementById("s_"+id);
	if (s != null) {
		s.style.display = (s.style.display == "none" ? "block" : "none");
	}
}

function closeWindow() {
	window.close();
}

function closePopupAndReload() {
	window.opener.location.reload();
	closeWindow();
}

function parentReload(pop_location, parent_location) {
	window.opener.location = parent_location;
	window.location = pop_location;
}


var scrolling = false;

function setscroll() {
	scrolling = true;
}


function confirmDelete(form, str) {
	if (confirm(str)) {
		form.submit();
	}
}

function checkUpload(form) {
	var msg = "";
	var d = document.getElementsByName('date')[0];
	if (!d.value.match(/(19\d{2}|2\d{3})(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])/)) {
		msg += "Incorrect date format. Please enter the date in the following format: YYYYMMDD.\n";
		d.style.backgroundColor='yellow';
	} else {
		d.style.backgroundColor='';
	}
	
	var t = document.getElementsByName('title')[0];
	if (t.value == "") {
		msg += "Title is a required field.\n";
		t.style.backgroundColor='yellow';
	} else {
		t.style.backgroundColor='';
	}
	
	var v = document.getElementsByName('version')[0];
	if (v.value == "") {
		msg += "Version number is a required field.\n";
		v.style.backgroundColor='yellow';
	} else {
		v.style.backgroundColor='';
	}
	
	var f = document.getElementsByName('userfile')[0];
	if (f.value == "") {
		msg += "File upload is a required field.\n";
		f.style.backgroundColor='yellow';
	} else if (!f.value.match(/\.(doc|html|htm|txt|pdf|zip)$/)) {
//		msg += "Incorrect document type. Please select a Word document or an HTML file.\n";
		msg += "Incorrent docuemnt type. Allowed types are doc, html, htm, txt, pdf, and zip.\n";
		f.style.backgroundColor='yellow';
	} else {
		f.style.backgroundColor='';
	}
	if (msg != "") {
		alert("Errors found:\n\n"+msg);
	} else {
		form.submit();
	}
}


function editQuestionCheck(form) {
	var sel = document.getElementById('frequently_asked_question_group_id');
	if (sel.options[sel.selectedIndex].value == "-1") {
		alert("You must select a group to add the question to.");
		return;
	} else if (sel.options[sel.selectedIndex].value == "0" && document.getElementById('newGroupBox').value == "") {
		alert("Please specify a name for the new group.");
		return;
	} else {
		form.submit();
	}
}

function editQuestionDisplay() {
	var sel = document.getElementById('frequently_asked_question_group_id');
	var arr = document.getElementsByName('newGroupBox');
	if (sel.options[sel.selectedIndex].value == "0") {
		for (var x = 0; x < arr.length; x++) {
			arr[x].style.display = 'block';
		}
	} else {
		for (var x = 0; x < arr.length; x++) {
			arr[x].style.display = 'none';
		}
	}
}

function moveUp(form) {
	var sel = document.getElementById('sel');
	var last;
	for (var x = 0; x < sel.options.length; x++) {
		if (sel.options[x].selected == false) {
			last = sel.options[x];
		} else {
			document.getElementById('sort_'+sel.options[x].value).value = parseInt(document.getElementById('sort_'+sel.options[x].value).value) - 1;
			
			if (last != null && last.selected == false) {
				document.getElementById('sort_'+last.value).value = parseInt(document.getElementById('sort_'+last.value).value) + 1;
			}
		}
	}
	form.submit();
}

function moveDown(form) {
	var sel = document.getElementById('sel');
	var last;
	for (var x = sel.options.length - 1; x >= 0; x--) {
		if (sel.options[x].selected == false) {
			last = sel.options[x];
		} else {
			document.getElementById('sort_'+sel.options[x].value).value = parseInt(document.getElementById('sort_'+sel.options[x].value).value) + 1;
			if (last != null && last.selected == false) {
				document.getElementById('sort_'+last.value).value = parseInt(document.getElementById('sort_'+last.value).value)-+ 1;
			}
		}
	}
	form.submit();
}

function validateHelpForm(form) {
	var msg = "";
	var subject = document.getElementsByName('subject')[0];
	if (subject.value == "") {
		msg += ", Subject";
		subject.style.backgroundColor='Yellow';
	} else {
		subject.style.backgroundColor='';
	}
	var module = document.getElementsByName('module')[0];
	if (module.options[module.selectedIndex].value == "0") {
		msg += ", Module";
		module.style.backgroundColor='Yellow';
	} else {
		module.style.backgroundColor='';
	}
	var description = document.getElementsByName('description')[0];
	if (description.value == "") {
		msg += ", Description";
		description.style.backgroundColor='Yellow';
	} else {
		description.style.backgroundColor='';
	}
	
	if (msg != "") {
		alert("Missing Information: "+msg.substring(2,msg.length));
	} else {
		form.submit();
	}
	
}





function showPopup(page, arg, w, h) {
	var newleft = screen.width/2 - w/2;
	var newtop = screen.height/2 - h/2;
	
	var pop = window.open("index.php?action="+page+"&arg="+arg, "pop", "width="+w+",height="+h+",top="+newtop+",left="+newleft+",resizable=yes,status=no,scrollbars="+(scrolling?"yes":"no"), 'pop');
	pop.focus();
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


function loadComponents(sel) {
	var id;
	var op = sel.options[sel.selectedIndex];
	if (op != null) {
		id = op.value;
	} else {
		id = 0;
	}
	window.open("?action=get_components&id="+id, 'app_bg');
}


function addOtherContact() {
	var sel = document.getElementsByName('other_contact_login')[0];
	var id = document.getElementsByName('id')[0].value;
	var newlogin = sel.options[sel.selectedIndex].value;
	var newtext = sel.options[sel.selectedIndex].text;
	var s = document.getElementsByName('other_contacts_login')[0];
	var f = true;
	
	for (var x=0; x<s.options.length; x++) {
		if (s.options[x].value == newlogin) f = false;
	}
	if (f) window.open("index.php?action=add_other_contact&login="+newlogin+"&id="+id, 'app_bg');
}

function removeOtherContacts(sel) {
	var id = document.getElementsByName('id')[0].value;
	var sel = document.getElementsByName(sel)[0];
	var str = "";
	for (var x = 0; x < sel.options.length; x++) {
		if (sel.options[x].selected) {
			str = str + "&rm_"+sel.options[x].value;
		}
	}
	if (str != "") window.open("index.php?action=remove_other_contacts&id="+id+str, 'app_bg');
}



var arr;

function bugccheck(form) {
	arr = new Array();
	var rad = document.getElementsByName("comment_action");
	if (rad[0].checked) {
		//going to bugzilla... need to check all the fields
		var l = form.elements.length;
		for (var x=0; x<l; x++) {
			var n = form.elements[x].name;
			if (n.substring(0,2) == "--") {
				arr.push(x);	// ;)  cheat by changing the names of the fields...
				form.elements[x].name = n.substring(2);
			}
		}
		if (!check(form)) {
			//need to restore the names
			var x;
			while (x = arr.pop()) {
				var n = form.elements[x].name;
				form.elements[x].name = "--"+n;
			}
		}
	} else {
		//just sending an email response... only need to validate the description
		return check(form);
	}
}