//////////////////////////////////////
function addRecord(stype, rptid,SID) {
//////////////////////////////////////	
	
	/* this function opens a new window for adding differnt segments of the weekly report 
	   set winspecs according to the type of segment we are opening, 
	   	-- Project Load needs more height 
	   we dont do anything we session yet but can be used later on for security purpose
	*/	
	var winspecs; 
	if (stype == 'PR' || stype == 'FL' || stype == 'SP' || stype == 'CC' || stype == 'AP' || stype == 'PL' || stype == 'CP' ) {
		winspecs = 'width=450,height=400,resizable=yes';
	} else {
		winspecs = 'width=500,height=500';
	}
	wdSegment = window.open('/lib/inframe.php?url=/forms/frm_wklyrpt_sgmnt.php&rptid='+rptid+'&stype='+stype+'&PHPSESID='+SID,'_wdsegment',winspecs);
	wdSegment.focus();
}

/////////////////////////////////////////
function updateRecord(stype,skey,rptid,SID,attr_id) {
////////////////////////////////////////
	var winspecs; 
	if (stype == 'PR' || stype == 'FL' || stype == 'SP' || stype == 'CC') {
		winspecs = 'width=450,height=400,resizable=yes';
	} else {
		winspecs = 'width=500,height=500';
	}
	wdSegment = window.open('/lib/inframe.php?url=/forms/frm_wklyrpt_sgmnt.php&rptid='+rptid+'&stype='+stype+'&PHPSESID='+SID+'&skey='+skey+'&attr_id='+attr_id,'_wdsegment',winspecs);
	//wdSegment.focus();
	
}

/////////////////////////////////////////
function chkLimit(objTxt,limit) {
/////////////////////////////////////////
	var svalue = objTxt.value;
	if (svalue > limit) {
		alert('Please enter a number between 1 and '+limit);
		objTxt.select();
		objTxt.focus();
		return false;
	} 
	return true;
}

///////////////////////////////////////////
function settime(oSegtime) {
///////////////////////////////////////////
	var now=new Date;
	hour=now.getHours();
	minutes=now.getMinutes();
	document.forms[0].elements[oSegtime].value=hour+":"+minutes;
}

function preport(session,rptid,appname) {
	var url = "/forms/rpt_print.php?PHPSESID="+session+"&rpt="+rptid+"&appname="+appname;
	wdreport = window.open(url,'wdreport','fullscreen=0,resizable=yes,scrollbars=yes');
	wdreport.focus();
	//window print
}

function saveComments(comments,rptid,session) {
	var url = "/forms/frm_sv_comment.php?PHPSESID="+session+"&rptid="+rptid+"&comments="+comments;
	wdComment = window.open(url,'wdComment',"width=0;height=0;");
}

function addRecord_v2(modname) {
	document.all('add_'+modname).style.display = 'block';
	document.all('rpt_'+modname).style.display = 'none';
	document.all('partner_id').focus();
	//add code if the mod isnt on the screen then display not allowed message
	return true;
}

function reqOpen(rptid) {
	document.all('dvreq').style.display = 'block';
	document.all('dvwklyrpt').style.display = 'none';
	document.all('req_report_id').value = rptid;
	return true;
}

function select_all() {
	for (var i=0;i<document.all.length;i++) {
		if (document.all(i).type == 'checkbox') {
			document.all(i).checked = 'true';
		}
	}
}

function view_projects(emp,session,rptid) {
	//open a window show list of active projects let the user select the once to be included for the current weeek
	
}

function editform(frmName) {
	for (var i=0;i < frmName.length;i++) {
		if (frmName[i].type != 'hidden') {
			//alert(frmName[i].type);
			frmName[i].style.display = 'block';
			document.all('dv_'+frmName[i].name).style.display = 'none';
		}
	}
}

function saveform(frmName) {
	for (var i=0;i < frmName.length;i++) {
		if (frmName[i].type != 'hidden') {
			//alert(frmName[i].type);
			frmName[i].style.display = 'none';
			if (frmName[i].type != 'select-one') {
				document.all('dv_'+frmName[i].name).innerText = frmName[i].value;
			} else {
				document.all('dv_'+frmName[i].name).innerText = frmName[i].options[frmName[i].options.selectedIndex].text;
			}
			document.all('dv_'+frmName[i].name).style.display = 'block';
		}
	}
}