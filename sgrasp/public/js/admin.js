function showRename(type_id) {
	var obj = document.getElementById("ren_"+type_id);
	obj.style.display = (obj.style.display == "none" ? "block" : "none") ;
}

function moveUp(id) {
	var sf = document.getElementById('sortform');
	var found = false;
	for (var x=0; x<sf.elements.length; x++) {
		if (sf.elements[x].name == 'sort_'+id && !found) {
			found = true;
			if (sf.elements[x-1] != null) {
				sf.elements[x-1].value = parseInt(sf.elements[x-1].value) + 1;
				sf.elements[x].value = parseInt(sf.elements[x].value) - 1;
			}
		}
	}
	sf.submit();
}

function moveDown(id) {
	var sf = document.getElementById('sortform');
	var found = false;
	for (var x=sf.elements.length-1; x>=0; x--) {
		if (sf.elements[x].name == 'sort_'+id && !found) {
			found = true;
			if (sf.elements[x+1] != null) {
				sf.elements[x+1].value = parseInt(sf.elements[x+1].value) - 1;
				sf.elements[x].value = parseInt(sf.elements[x].value) + 1;
			}
		}
	}
	sf.submit();
}

function newrole() {
	var rb_td = document.getElementById('rb_td');
	rb_td.style.display = "none";

	var a_td = document.getElementById('a_td');
	a_td.style.display = "block";
}








function togglePrimary(type_id) {
	var study_type_id = document.getElementById("study_type_id").value;
	var box = document.getElementById("pri_"+type_id);

	if (box.checked) {
		window.location = "index.php?action=set_primary&study_task_id="+type_id+"&study_type_id="+study_type_id;
	} else {
		window.location = "index.php?action=unset_primary&study_task_id="+type_id+"&study_type_id="+study_type_id;
	}
}

function toggleMemo(type_id) {
	var study_type_id = document.getElementById("study_type_id").value;
	var box = document.getElementById("mem_"+type_id);

	if (box.checked) {
		window.location = "index.php?action=set_memo&study_task_id="+type_id+"&study_type_id="+study_type_id;
	} else {
		window.location = "index.php?action=unset_memo&study_task_id="+type_id+"&study_type_id="+study_type_id;
	}
}


function confirmDelete(id) {
	if (confirm("Are you sure you want to delete this study task from this study type?")) {
		window.location="index.php?action=delete_task&id="+id+"&study_type_id="+document.getElementById('study_type_id').value;
	}
}

function add_role() {
	var d = document.getElementsByName('description')[0];
	window.location = "index.php?action=add_role&description="+escape(d.value);
}

function remove_role(id) {
	if (confirm("Are you sure you want to remove this role?")) {
		window.location = "index.php?action=remove_role&role_id="+escape(id);
	}
}

function remove_user_role(id, r_id) {
	if (confirm("Are you sure you want to remove this user role?")) {
		window.location = "index.php?action=remove_user_role&id="+escape(id)+"&r_id="+r_id;
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
	timeoutId = window.setTimeout("timeoutTrigger()", 800);
}

function timeoutTrigger() {
	if (textBox!=null && textBox.value!=null && textBox.value.length > 1 && textBox.value != oldval) {
		oldval = textBox.value;
		window.open("?action=user_search&val="+textBox.value+"&name="+textBox.name, 'app_bg');
	}
}

function display_more_accounts(iteration) {
   if (iteration < 10) {
      trs = document.getElementsByTagName("tr");
      for(i=0; i<trs.length; i++) {
         if (trs[i].id.search("slave_account_") != -1) {
            id = trs[i].id.substring(trs[i].id.lastIndexOf("_")+1);
            if (id <= iteration) {
               trs[i].style.display = "";
               document.getElementById("display_more_accounts_"+id).style.display="none";
               document.getElementById("display_less_accounts_"+id).style.display="none";
            }else if (id == (iteration+1)) {
               trs[i].style.display = "";
               document.getElementById("display_more_accounts_"+id).style.display="";
               document.getElementById("display_less_accounts_"+id).style.display="";
            } else {
               trs[i].style.display = "none";
            }
         }
      }
   }
}

function display_less_accounts(iteration) {
   if (iteration > 1) {
      trs = document.getElementsByTagName("tr");
      for(i=0; i<trs.length; i++) {
         if (trs[i].id.search("slave_account_") != -1) {
            id = trs[i].id.substring(trs[i].id.lastIndexOf("_")+1);
            if (id < (iteration-1)) {
               trs[i].style.display = "";
               document.getElementById("display_more_accounts_"+id).style.display="none";
               document.getElementById("display_less_accounts_"+id).style.display="none";
            }else if (id == (iteration-1)) {
               trs[i].style.display = "";
               document.getElementById("display_more_accounts_"+id).style.display="";
               document.getElementById("display_less_accounts_"+id).style.display="";
            } else {
               trs[i].style.display = "none";
            }
         }
      }
   }
}




function changeSelect(sel) {
	window.location.href = sel.options[sel.selectedIndex].value;
}

function showDeletedRows() {
	var l = document.getElementById("href_deleted").value;
	window.location.href = l;
}

function reloc(id) {
	var h = document.getElementById(id).value;
	window.location.href = h;
}

function editRow(enc) {
	var scrolling = true;
	var w = 600;
	var h = 400;
	popper(enc, scrolling, w, h);
}

function deleteRow(enc) {
	if (confirm("Are you sure you want to delete this row?\n\n(It will actually be deleted from the database!)")) {
		window.open("?e="+enc,'app_bg');
	}
}

function editAttr(enc) {
	var scrolling = true;
	var w = 600;
	var h = 200;
	popper(enc, scrolling, w, h);
}

function deleteAttr(enc) {
	if (confirm("Are you sure you want to delete this attribute?\n\n(It will actually be deleted from the database!)")) {
		window.open("?e="+enc,'app_bg');
	}
}

function manageAttrs(id) {
	var enc = document.getElementById(id).value;
	var scrolling = true;
	var w = 650;
	var h = 500;
	popper(enc, scrolling, w, h);
}

function popper(e,s,w,h) {
	var newleft = screen.width/2 - w/2;
	var newtop = screen.height/2 - h/2;
	var pop = window.open("?e="+e, "pop", "width="+w+",height="+h+",top="+newtop+",left="+newleft+",resizable=yes,status=no,scrollbars="+(s?"yes":"no"), 'pop');
	pop.focus();
}

function deleteAttrDef(enc) {
	if (confirm("Are you sure you want to delete this attribute definition?\n\n(It will actually be deleted from the database!)")) {
		window.location.href="?e="+enc;
	}
}
function setVal(sel, name) {
	var o = document.getElementById(name);
	var v = sel.options[sel.selectedIndex].value;
	o.value = v;
}