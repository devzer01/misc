function checkPartnerIDs(f) {

var pID = "first";
	for(i=0; i<f.elements.length; i++) {
		if (f.elements[i].name=="brcheck[]" && f.elements[i].checked) {
			if (pID=="first") {
				pID = f.elements[i].value.substr(0, f.elements[i].value.indexOf("||"));
			}else{
				if (f.elements[i].value.substr(0, f.elements[i].value.indexOf("||")) != pID) {
					alert("You cannot merge items from different partners !!!");
					return false;
				}
			}
		}
	}
	if (confirm("Are you sure you want to merge the selected BR's ???")) {
		alert("Please type in a Study Name once the merge is complete !!!");
		return true;
	}else{
		return false;
	}
}

function save_param(key,id,value) {
	window.open('index.php?action=background&bg_action=saveparam/'+key+'/'+id+'/'+value+'/','app_bg');
	return true;
}

function save_memo(br_id, value) {
	window.open('index.php?action=background&bg_action=savememo/'+br_id+'/'+value, 'app_bg');
	return true
}

function disp_mbr_contact(partner_id,br_id,mbr_id,bl_type) {
	var url = "index.php?action=mbr_contacts&partner_id="+partner_id+"&br_id="+br_id+"&mbr_id="+mbr_id+"&bl_type="+bl_type;
	var style = "width=620,height=500,status=yes,scrollbars=yes";
	var wintwo = window.open(url,'wdContact',style);
	wintwo.focus();
}

function disp_contact(partner_id, br_id, bl_type) {
	var url = "index.php?action=contacts&partner_id="+partner_id+"&br_id="+br_id+"&bl_type="+bl_type;
	var style = "width=620,height=500,status=yes,scrollbars=yes";
	var wintwo = window.open(url,'wdContact',style);
	wintwo.focus();
}


  function calc_all(objName, iRow) {
  	 var regexp = /^S/;
	if (objName.search(regexp) == -1) {
		proposed_units = document.getElementById('units_'+iRow).value;
		actual_units = document.getElementById('A_units_'+iRow).value;
		cost_per_unit = document.getElementById('cost_pu_'+iRow).value;

		document.getElementById('proposed_'+iRow).value = (cost_per_unit * proposed_units).toFixed(2);
  		document.getElementById('actual_'+iRow).value = (cost_per_unit * actual_units ).toFixed(2);
		//now do net and %
		document.getElementById('net_'+iRow).value = (document.getElementById('actual_'+iRow).value - document.getElementById('proposed_'+iRow).value).toFixed(2);
		if (document.getElementById('proposed_'+iRow).value != 0) {
			document.getElementById('delta_'+iRow).value = (document.getElementById('net_'+iRow).value / document.getElementById('proposed_'+iRow).value * 100).toFixed(2);
		}else{
			document.getElementById('delta_'+iRow).value = '0.00';
		}
	} else {
		proposed_units = document.getElementById('S_units_'+iRow).value;
		actual_units = document.getElementById('S_A_units_'+iRow).value;
		cost_per_unit = document.getElementById('S_cost_pu_'+iRow).value;

		document.getElementById('S_proposed_'+iRow).value = (cost_per_unit * proposed_units).toFixed(2);
  		document.getElementById('S_actual_'+iRow).value = (cost_per_unit * actual_units ).toFixed(2);
		//now do net and %
		document.getElementById('S_net_'+iRow).value = (document.getElementById('S_actual_'+iRow).value - document.getElementById('S_proposed_'+iRow).value).toFixed(2);
		if (document.getElementById('S_proposed_'+iRow).value != 0) {
			document.getElementById('S_delta_'+iRow).value = (document.getElementById('S_net_'+iRow).value / document.getElementById('S_proposed_'+iRow).value * 100).toFixed(2);
		}else{
			document.getElementById('S_delta_'+iRow).value = '0.00';
		}
	}
	do_totals();

  }

  function do_totals() {
//  	var saved_lines = document.all('br_line_id').value; //saved lines
//  	var sline = saved_lines.split('/');
	var t_proposed = 0;
	var t_actual = 0;
	var t_net = 0;
	for (var i=0;i < (document.budget_lines.elements.length - 1);i++) {

		if (document.budget_lines.elements[i].name.search('proposed') != -1) {
			if (document.budget_lines.elements[i].value != "")
				t_proposed += parseFloat(document.budget_lines.elements[i].value);
		}else if (document.budget_lines.elements[i].name.search('actual') != -1) {
			if (document.budget_lines.elements[i].value != "")
				t_actual += parseFloat(document.budget_lines.elements[i].value);
		}else if (document.budget_lines.elements[i].name.search('net') != -1) {
			if (document.budget_lines.elements[i].value != "")
				t_net += parseFloat(document.budget_lines.elements[i].value);
		}
	}

	document.all('t_prop').value = t_proposed.toFixed(2);
	document.all('t_act').value = t_actual.toFixed(2);
	document.all('t_nt').value = t_net.toFixed(2);
	if (t_proposed != 0)
		document.all('t_dlt').value = (t_net / t_proposed * 100).toFixed(2);
	else
		document.all('t_dlt').value = "0.00";
  }

  function validateBR(isAM, poRequired, pmRequired, jobRequired, brType) {
	if (isAM) {
		if (poRequired == 'true') {
			if (document.all('po_number').value == '') {
				alert('This Billing Report Requires a P.O. Number, Can not continue!');
				return false;
			}
		}
		if (pmRequired == 'true') {
			if (document.all('pm_name').value == '') {
				alert('This Billing Report Requires a Internal Project Manager Name, Can not continue!');
				return false;
			}
		}
		if (jobRequired == 'true') {
			if (document.all('job_number').value == '') {
				alert('This Billing Report Requires a Internal Job Number, can not continue!');
				return false;
			}
		}
		if (brType == '1') {
			if (document.all('po_number').value == '') {
				alert('This Credit Memo Requires an Invoice Number, Can Not Continue!');
				return false;
			}
		}
	}

	return true;
  }

  function set_datatype(objSel,iRow) {
  	//alert(objSel.value);

	var regexp = /^S/;
	var obj_array = new Array('unit_type','cost_pu','units','A_units','proposed','actual','net','delta');
	if (objSel.value == 'clear') {
		if (objSel.name.search(regexp) == -1) {
			//just clear out the row
			document.all('unit_type_'+iRow).value = '';
			document.all('cost_pu_'+iRow).value = '';
			document.all('units_'+iRow).value = '';
			document.all('A_units_'+iRow).value = '';
		} else {
			for (var i=0;i<obj_array.length;i++) {
				document.all('S_'+obj_array[i]+'_'+iRow).value = '';
			}
		}
	} else {
		if (objSel.name.search(regexp) == -1) {
			document.all('unit_type_'+iRow).value = budget_array[objSel.value];
			document.all('cost_pu_'+iRow).value = price_array[objSel.value];
		} else {
			document.all('S_unit_type_'+iRow).value = budget_array[objSel.value];
			document.all('S_cost_pu_'+iRow).value = price_array[objSel.value];
		}
		calc_all(objSel.name, iRow);
	}
  }

  function force_memo(line_id,br_id, br_status) {

  	if (document.all('memo_set_'+line_id).value == 0) {
			if (br_status == 1 || br_status == 4 || br_status == 10) {
				alert('Your Actions Require a Mandatory Memo');
				document.all('memo_set_'+line_id).value = 1;

				window.open('index.php?action=memos&do=add&br_line_id='+line_id+'&main=0&br_id='+br_id, 'wdMemo', 'width=400,height=300,scrollbars=yes');
			} else {
				return true;
			}
  	}
  }

  function displaySecondOption(value) {
  	switch (value) {
  		case "-1" : {
  			document.getElementById("secondOption").style.display = 'none';
  			document.getElementById("thirdOption_study_1").style.display = 'none';
  			document.getElementById("thirdOption_study_2").style.display = 'none';
  			document.getElementById("thirdOption_study_3").style.display = 'none';
  			document.getElementById("thirdOption_nonstudy_1").style.display = 'none';
  			document.getElementById("thirdOption_nonstudy_2").style.display = 'none';
  			document.getElementById("thirdOption_nonstudy_3").style.display = 'none';
  		}break;
  		case "0" : {
  			document.getElementById("secondOption").style.display = 'block';
  			document.getElementById("secondOptionText").innerHTML = "BR Type";
  		}break;
  		case "2" : {
  			document.getElementById("secondOption").style.display = 'block';
  			document.getElementById("secondOptionText").innerHTML = "Retainer Type";
  		}break;
  	}
  }

  function displayThirdOption(value) {
      document.getElementById("thirdOption_study_1").style.display = 'none';
  	   document.getElementById("thirdOption_study_2").style.display = 'none';
  		document.getElementById("thirdOption_study_3").style.display = 'none';
  		document.getElementById("thirdOption_nonstudy_1").style.display = 'none';
  		document.getElementById("thirdOption_nonstudy_2").style.display = 'none';
  		document.getElementById("thirdOption_nonstudy_3").style.display = 'none';

  		document.getElementById("thirdOption_"+value+"_1").style.display='block';
  		document.getElementById("thirdOption_"+value+"_2").style.display='block';
  		document.getElementById("thirdOption_"+value+"_3").style.display='block';
  }

  function displaySearchBySecondOption(value) {
  	document.getElementById('secondOption_partner').style.display = 'none';
  	document.getElementById('secondOption_study').style.display = 'none';

   	document.getElementById('secondOption_'+value).style.display = 'block';
}

	function validateInput()
	{
		//check if the required fields are there
		if (document.getElementById('thirdOption_study_1').style.display=='block') {
			if (document.getElementById('studyid').value == '') {
				alert('Required Valid Study ID');
				return false;
			}
		} else if (document.getElementById('thirdOption_nonstudy_1').style.display=='block') {
			if (document.getElementById('partner_id').value == '') {
				alert('Partner ID Required');
				return false;
			} else if (document.getElementById('study_name').value == '') {
				alert('Project Name Required');
				return false;
			}
		}
		return true;
	}

function validateSearchForm()
{
	var partnerID = document.all('partnerID').value;
	var StudyID = document.all('studyID').value;

	if (partnerID || StudyID) {
		return true;
	}

	alert('Please enter, Partner ID or Study ID');
	return false;
}

function toggle_all_br(f) {
	for(i=0; i<f.elements.length; i++) {
		if (f.elements[i].name.substr(0,3)=="br_") {
			f.elements[i].checked = document.getElementById("br_all").checked;
		}
	}

}
