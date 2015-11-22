focusTab = '_option1';

YAHOO.namespace('com.thecodecentral');

function init() {
	
	//add the progress bar
	CreateYUIProgressBar();
	
	var oGetCreditApproval = CreateYUIButton('button', 'Get Credit Approval', 'get_credit_approval', 'Get Credit Approval');
	
	var oDownload_Proposal = CreateYUIButton('push', 'Download Proposal', 'download_proposal', 'Download Proposal');
	 
	oDownload_Proposal.on("click", onDownload_ProposalClick); 

	function onDownload_ProposalClick(p_oEvent) { 
	   		showLightBox('proposal_download_lightbox_dialog');
	} 
	
	
	var oProposal_Won = CreateYUIButton('push', 'Proposal Won', 'proposal_won', 'Proposal Won'); 
	
	oProposal_Won.on("click", onProposal_WonClick); 

	function onProposal_WonClick(p_oEvent) { 
	   		showLightBox('propsal_won_lightbox_dialog');
	} 
	
	
	
	////////////// Radio Buttons
	var oButtonGroup1 = new YAHOO.widget.ButtonGroup({  
	                                id:  "opt_complexity",  
	                                name:  "opt_complexity",  
	                                container:  "complexity" }); 
	 
	oButtonGroup1.addButtons([ 
	 
	    { label: "Simple", value: "s", checked: true }, 
	    { label: "Complex", value: "c" } 
	]); 
	
	var oDataProcceing_Complexity = new YAHOO.widget.ButtonGroup({  
	                                id:  "complexity_button_data_proccessing",  
	                                name:  "complexity_button_data_proccessing",  
	                                container:  "complexity_button_data_proccessing" }); 
	 
	oDataProcceing_Complexity.addButtons([ 
	 
	    { label: "Simple", value: "simple", checked: true }, 
	    { label: "Complex", value: "complex" } 
	]); 

	var oButtonGroup2 = new YAHOO.widget.ButtonGroup({  
	                                id:  "overlay",  
	                                name:  "overlay",  
	                                container:  "overlay" }); 
	 
	oButtonGroup2.addButtons([ 
	 
	    { label: "Yes", value: "yes", checked: true }, 
	    { label: "No", value: "no" } 
	]);		
	
var add_screen_options = {
	'id' : "entry_screen",
	'visible': false,	
	'width' : "500px"	
};
	
var add_programming_cost_options = {
	'id' : "add_programming_fees",
	'visible': false,	
	'modal': false,
	'width' : "600px"
};

var calculate_number_options = {
	'id' : "calculate_number",
	'visible': false,	
	'width' : "470px"
};

var add_project_managment_fees_options = {
	'id' : "add_project_managment_fees",
	'visible': false,	
	'width' : "470px"
};

var adding_data_processing_fees_options = {
	'id' : "adding_data_processing_fees",
	'visible': false,	
	'modal': false,
	'width' : "550px"	
};

var adding_sample_cost_tags_options = {
	'id' : "adding_sample_cost_tags",
	'visible': false,	
	'width' : "1125px",
	'modal' : false	
};

var tags_lightbox_options = {
  'id' : "tags_lightbox",
  'width' : "350px",
  'fixedcenter' : true,
  'visible': false,
  'draggable:': true,
  'modal': true,
  'close':true,
  'constraintoviewport': true
 };	 


var add_vendor_lightbox_options = {
		'id' : "add_vendor_lightbox",
		'visible': false,	
		'width' : "350px"	
	}
	
var proposal_approval_lightbox_options = { 
  'id' : "proposal_approval_lightbox", 
  'width' : "800px",   
  'visible': false  
 }; 
 	

var proposal_download_lightbox_options = { 
	'id' : "proposal_download_lightbox", 
	'width' : "600px", 	
	'visible': false	
}; 

var propsal_won_lightbox_options = { 
  'id' : "propsal_won_lightbox", 
  'width' : "600px",  
  'visible': false 
 }; 

 
var assign_to_person_lightbox_options = { 
  'id' : "assign_to_person_lightbox", 
  'width' : "400px", 
  'fixedcenter' : true, 
  'visible': false, 
  'draggable:': true, 
  'modal': true, 
  'close':true, 
  'constraintoviewport': true 
 };  
	 
var add_contact_lightbox_options = { 
	'id' : "add_contact_lightbox", 
	'width' : "400px", 		
	'visible': false		
};  

var prospect_to_customer_options = { 
	'id' : "prospect_to_custoemr_reivew_request_dev", 
	'width' : "500px", 		
	'visible': false		
};

var msg_options = { 
	'id' : "msg_light_box_div", 
	'width' : "300px", 		
	'visible': false		
};

var help_discount_options = { 
	'id' : "help_to_calculate_discount_div", 
	'width' : "300px", 		
	'visible': false		
};

var revisions = { 
	'id' : "revision_list", 
	'width' : "600px", 		
	'visible': false		
};

 /* */
prospect_to_customer_dialog = CreateUserLightBox(prospect_to_customer_options); 
msg_dialog 				 = CreateUserLightBox(msg_options); 

tags_dialog = CreateUserLightBox(tags_lightbox_options);
add_vendor_dialog = CreateUserLightBox(add_vendor_lightbox_options);

adding_sample_cost_tags_dialog 		= CreateUserLightBox(adding_sample_cost_tags_options);
add_programming_cost_dialog 			= CreateUserLightBox(add_programming_cost_options);
calculate_number_dialog 				= CreateUserLightBox(calculate_number_options);
add_project_managment_fees_dialog 	= CreateUserLightBox(add_project_managment_fees_options);
adding_data_processing_fees_dialog 	= CreateUserLightBox(adding_data_processing_fees_options);
proposal_approval_lightbox_dialog 	= CreateUserLightBox(proposal_approval_lightbox_options);
proposal_download_lightbox_dialog 	= CreateUserLightBox(proposal_download_lightbox_options);
propsal_won_lightbox_dialog 			= CreateUserLightBox(propsal_won_lightbox_options);
assign_to_person_lightbox_dialog 	= CreateUserLightBox(assign_to_person_lightbox_options);
help_discount_lightbox_dialog 	= CreateUserLightBox(help_discount_options);
screen_dialog = CreateUserLightBox(add_screen_options);

add_contact_lightbox_dialog = CreateUserLightBox(add_contact_lightbox_options);

showLightBox('screen_dialog');
revision_list_lightbox					= CreateUserLightBox(revisions);


showLightBox('screen_dialog');
num = $("#revision_no").text().split('.');

for(i=1; i < 5; i++) {
	$("#rev_no" + i).text(num[0] + '.' + i);
}
};

YAHOO.util.Event.addListener(window, "load", init); 

//jqury jscripts
$(document).ready(function () {
	
	$('#get_credit_approval').click(function () {
		showLightBox('prospect_to_customer_dialog');
	});
	
}); 


$(function() {      
    $(document).ajaxSend(function() {  
    	  progress_bar.hide();  		       
        progress_bar.show();  
    });
    $(document).ajaxStop(function() {      
        progress_bar.hide(); 
    });
    $(document).ajaxError(function() {
    	alert('Connection Faild. Please Try Again...!');
    });
    
});


//get countries which set for the proposal and set them on the Sample fees light box country list
function SetSampleFeeCountry()
{
	var selected_country 			= document.getElementById('listRight');	
	var sample_consumer_country 	= document.getElementById('sample_consumer_country');
	var sample_b2b_country 			= document.getElementById('sample_b2b_country');
	
	
	consumer_List 	= new Array( selected_country.options.length );
	
	b2b_List 			= new Array( selected_country.options.length );
	
	for(var len = 0; len < selected_country.options.length; len++ ) {
    	if ( selected_country.options[ len ] != null )
    	{
			consumer_List[ len ] = new Option( selected_country.options[ len ].text, selected_country.options[ len ].value);
			b2b_List[ len ] = new Option( selected_country.options[ len ].text, selected_country.options[ len ].value);
		}
	}
	
	sample_consumer_country.options.length = 0 ;
	for ( var j = 0; j < consumer_List.length; j++ ) {
		if ( consumer_List[ j ] != null ) {
      		sample_consumer_country.options[ j ] = consumer_List[ j ];
      		
		}
  	}	
  	  	
  	sample_b2b_country.options.length = 0;
  	for ( var i = 0; i < b2b_List.length; i++ ) {
		if ( b2b_List[ i ] != null ) {      		
      		sample_b2b_country.options[ i ] = b2b_List[ i ];
		}
  	}  
  	
//	var ml_fml = new YAHOO.widget.ButtonGroup("ml_fml_div");
//  	var rows = '<table width="100%" align="center">'+
//  					'<tr style="height:20px"><td>&nbsp;</td>'+
//  					'<td class="Text11Bold" width="10%">Gender</td><td class="Text11Bold" width="15%">Age<br>Min  Max</td><td class="Text11Bold" width="30%">Sample description</td><td class="Text11Bold"  width="20%">Incidence of Targeted General Sample </td><td class="Text11Bold" width="20%">Total Number of Sample </td></tr>' 	 ;
//  	for(var i = 0; i < selected_country.options.length; ++i) {
//  		if ((i%2)== 1 )
//  		{
//  			var css_class = 'tab1' ;
//  		}
//  		else 
//  		{
//  			var css_class = 'tab' ;
//  		}
//		rows += '<tr  class="'+css_class+'" style="height:20px"><td width="15%" class="Text11Bold">' + selected_country.options[i].text + 
//					'</td><td><div id="ml_fml_div" class="yui-buttongroup">'+
//							'<input type="button" name="opt_ml_fml" value="M">'+
//							'<input type="button" name="opt_ml_fml" value="F">'+
//						'</div></td>'+
//						'<td><input name="textfield" type="text" id="textfield" size="3" />&nbsp;&nbsp;&nbsp;'+
//					'<input name="textfield2" type="text" id="textfield2" size="3" />&nbsp</td><td>'+
//					'<input type="text" id="grid_sample_description_'+i+'" size="32" /></td><td><input type="text" id="grid_incidence_'+i+'" size="10" /></td>'+
//					'<td><input type="text" id="grid_total_sample_'+i+'" size="10" /></td></tr>';		
//	}
//	rows += '<tr><td width="50%" colspan="3" align="right"><div id="submitaddfee1_grid" onclick="submit_adding_sample_cost_tags_dialog()"></div></td><td width="50%" colspan="3" align="left"><div id="submitcancel_grid" onclick=" hideLightBox("adding_sample_cost_tags_dialog");"></div></td></tr></table>' ;
//	
//	$('#grid_rows').html(rows);
}



//set revision number
function setRevisionNumber(cont) 
{
	$("#revision_no").text($("#" + cont).text());
	hideLightBox('revision_list_lightbox');
}

//Display the Project managment Fees edit light box
function DisplayProjectManagmentFees(id) {		
		$.get('/app/Proposal/DisplayProjectManagmentFeesLightBox', {element_id: id}, function() { TestCallback('add_project_managment_fees_dialog'); });				
		return false;
}

//Display the Edit data processing light box
function DisplayEditDataProcessing(id)
{	
	$.get('/app/Proposal/DisplayEditDataProcessingLightBox', {element_id: id}, function() { TestCallback('adding_data_processing_fees_dialog'); });				
	return false;
}

//Display the Edit data vendor light box of B2B tab
function DisplayEditVendor(id)
{
	$.get('/app/Proposal/DisplayEditVendorLightBox', {element_id: id}, function() { TestCallback('add_vendor_dialog'); });				
	return false;
}

//Display the Edit Programming fees light box
function DisplayProgrammingFees(id)
{	
	$.get('/app/Proposal/DisplayEditProgrammingFeesLightBox', {element_id: id}, function() { TestCallback('add_programming_cost_dialog'); });
}

//Display the Edit Sample Fees light box
function DisplayEditSampleFees(id, tab_num)
{
	$.get('/app/Proposal/DisplayEditSampleFeesLightBox', {element_id: id}, function() { changeTab(tab_num, tab_adding_sample_cost_tags_dialog);  TestCallback('adding_sample_cost_tags_dialog'); });
}

//Display Consumer Grid
function  DisplayConsumerGrid()
{
	if(document.getElementById('listRight').options.length == 0)
	{ 
		$("#div_selected_countries_error_msg").html('<font color="Red">Please Select Countries</font>');	
		
	} 
	else 
	{	
		var list = document.getElementById('listRight');	
		var countries = '' ;
		for(var i = 0; i < list.options.length; ++i)	
		{
			countries +=  list.options[i].text + ',' ;
		 
		}	
		countries = countries.substring(0,(countries.length - 1)) ;	
		
		$.get('/app/Proposal/DisplayAddConsumerGrid',{countries: countries},  function() {TestCallback('adding_sample_cost_tags_dialog'); });
	}
}
// Common call back function to show the light box dialog.
function TestCallback(dialog)
{
	//dialog.show();
	showLightBox(dialog);
}

//Edit the Other Service tag lines
function EditOtherServiceTag(sel, tab)
{		
	document.getElementById('service_option1').selectedIndex = sel;
	var org_value = $('#service_input'+tab+'_'+sel).html();

	if(org_value.indexOf('<input') == -1) {
		var replace_html = $('#service_input_option1_mainservice').html();	
		
		replace_html = replace_html.replace(/_mainservice/g, '_'+sel);
		replace_html = replace_html.replace(/_maininput/g, '_'+sel);
		replace_html = replace_html.replace(/,''/g, ",'"+org_value+"'");		
		org_value_arr = org_value.split(' ');
		replace_html = replace_html.replace(/ value=\"\"/g, 'value="'+org_value_arr[0]+'"');
		replace_html = replace_html.replace(/_rep_value/g, org_value);
		
		$('#service_input'+tab+'_'+sel).html(replace_html); 	
	}
}

// Show error message light box
focusEl = false;
timerId = 0;
po = 0;
function showErrorMsg(title,message,timeout,nextfocus,parentObj){
	if(nextfocus) focusEl = nextfocus;
	
	$('#message_title').html(title);
	$('#message_description').html(message);
	
	if(timeout>0){
		//timerId=setTimeout("hideErrorMsg()", (timeout*1000));
	}
	uninitLBHotKeys();
	po = parentObj;
	$.hotkeys.add('Esc',function(){hideErrorMsg()}); 	
	msg_dialog.show();
}


// Hide error message light box
function hideErrorMsg(){
	 hideLightBoxSub('msg_dialog');
	$('#'+focusEl).focus();
	clearTimeout(timerId);
	if(!po){
		initHotKeys();
	}else{
		initLBHotKeys(po);
	}
	po = 0;
}

// Checking if the country list is empty before adding sample
function checkCountryList(){
	if(document.getElementById('listRight').options.length == 0)
	{ 
		$("#div_selected_countries_error_msg").html('<font color="Red">Please Select Countries</font>');
	} else {
		RemoveCountriesNotSelectedErrorMsg() ;
		showLightBox('adding_sample_cost_tags_dialog');
		SetSampleFeeCountry(); 
	}
}

function RemoveCountriesNotSelectedErrorMsg()
{
	$("#div_selected_countries_error_msg").html('');	
}

// Enable primary screen key cuts
function initHotKeys(){
	$.hotkeys.add('Alt+p',{propagate: false},function(){showLightBox('add_programming_cost_dialog')});
	$.hotkeys.add('Alt+s',{propagate: false},function(){checkCountryList();}); 
	$.hotkeys.add('Alt+m',{propagate: false},function(){showLightBox('add_project_managment_fees_dialog')}); 
	$.hotkeys.add('Alt+d',{propagate: false},function(){showLightBox('adding_data_processing_fees_dialog')}); 
	$.hotkeys.add('Alt+l',{propagate: false},function(){showLightBox('proposal_download_lightbox_dialog')}); 
	$.hotkeys.add('Alt+w',{propagate: false},function(){showLightBox('propsal_won_lightbox_dialog')}); 
	$.hotkeys.add('Alt+g',{propagate: false},function(){showLightBox('proposal_approval_lightbox_dialog')}); 
	$.hotkeys.add('Alt+a',{propagate: false},function(){showLightBox('assign_to_person_lightbox_dialog')}); 
	$.hotkeys.add('Alt+c',{propagate: false},function(){showLightBox('add_contact_lightbox_dialog')}); 
	$.hotkeys.add('Ctrl+o',{propagate: false},function(){CopyOption()});
	$.hotkeys.add('Shift+left',{propagate: false},function(){prevTab(eval('tabView'))});
	$.hotkeys.add('Shift+right',{propagate: false},function(){nextTab(eval('tabView'))});
}

// Disable primary screen key cuts
function uninitHotKeys(){
	$.hotkeys.remove('Alt+p');
	$.hotkeys.remove('Alt+s'); 
	$.hotkeys.remove('Alt+m'); 
	$.hotkeys.remove('Alt+d'); 
	$.hotkeys.remove('Alt+n'); 
	$.hotkeys.remove('Alt+l'); 
	$.hotkeys.remove('Alt+w'); 
	$.hotkeys.remove('Alt+g'); 
	$.hotkeys.remove('Alt+a'); 
	$.hotkeys.remove('Alt+c'); 
	$.hotkeys.remove('Ctrl+o');
	$.hotkeys.remove('Shift+left');
	$.hotkeys.remove('Shift+right');
}

// Enable light box key cuts
function initLBHotKeys(lb){
	$.hotkeys.add('Esc',{propagate: false},function(){hideLightBox(lb)}); 	
	$.hotkeys.add('Alt+c',{propagate: false},function(){hideLightBox(lb)});
	$.hotkeys.add('Ctrl+s',{propagate: false},function(){ eval('submit_'+lb+'()'); });
	$.hotkeys.add('Shift+left',{propagate: false},function(){ prevTab(eval('tab_'+lb))});
	$.hotkeys.add('Shift+right',{propagate: false},function(){ nextTab(eval('tab_'+lb)) });
}

// Disable light box key cuts
function uninitLBHotKeys(){
	$.hotkeys.remove('Esc'); 	
	$.hotkeys.remove('Alt+c');
	$.hotkeys.remove('Ctrl+s');
}

// Open action for main light boxes (enable key cuts)
function showLightBox(obj){
	eval(obj+'.show()');
	uninitHotKeys();
	initLBHotKeys(obj);
}

// Close action for main light boxes (disable key cuts)
function hideLightBox(obj){
	eval(obj+'.hide()');
	uninitLBHotKeys();
	initHotKeys();
}

// Open action for sub light boxes (enable key cuts)
function showLightBoxSub(obj,parentObj){
	eval(obj+'.show()');
	uninitLBHotKeys();
	$.hotkeys.add('Esc',{propagate: false},function(){hideLightBoxSub(obj,parentObj)}); 	
	$.hotkeys.add('Alt+c',{propagate: false},function(){hideLightBoxSub(obj,parentObj)});
	$.hotkeys.add('Ctrl+s',{propagate: false},function(){ eval('submit_'+obj+'()'); });
}

// Close action for sub light boxes (disable key cuts)
function hideLightBoxSub(obj,parentObj){
	eval(obj+'.hide()');
	uninitLBHotKeys();
	initLBHotKeys(parentObj);
}

// Capture the event of closing the light box by close button
function closeBtnLB(e){
	if(e.target.className.indexOf('close')!=-1){
		initHotKeys();
	}
}

//Display the Discount text
function DisplayDiscountText(id, tab, qty)
{	
	var initial_str = $('#'+id+tab+'_link').text();
	
	var number = '';
	if(initial_str.indexOf('...') == -1){
		number = initial_str.substring(initial_str.indexOf('$')+1 , initial_str.indexOf('('));	
		number = number.trim();
	}	

	var input_str = '<input type="text" id="'+id+'_text'+tab+'" size="5" value="'+number+'" name="'+id+'_text'+tab+'" onkeydown="SetDiscountValue(event, \''+id+'\', \''+tab+'\', \''+initial_str+'\', \''+qty+'\')"; /> <img src="/images/tick_blue.gif" width="18" height="17" title="Ok" onClick="submitDiscountInput(\''+id+'\', \''+tab+'\', \''+qty+'\'); return false;" alt="Ok" style="cursor: pointer" />&nbsp;<img src="/images/help.gif" width="16" height="16" title="Help me to calculate" onclick="ShowHelpDiscountCalculate(\''+id+'\', \''+tab+'\', \''+qty+'\', \''+initial_str+'\'); return false;" alt="Help me to calculate" style="cursor: pointer;" />';
	
	$('#'+id+tab).html(input_str);
	$('#'+id+'_text'+tab).focus();
}

// Cancel action for discount input text box
function cancelDiscountInput(id,tab,initial_str, qty){
	var can_str = '<a href="" title="Click here to add discount" onclick="DisplayDiscountText(\''+id+'\', \''+tab+'\', \''+qty+'\');return false;"> '+initial_str+' </a>'		
	$('#'+id+tab).html(can_str);
	
}

// Submit action for discount input text box
function submitDiscountInput(id,tab,qty){
			var discount = ($('#'+id+'_text'+tab).val()) * 1;	
			var item_id  	= id+tab;
			item_id 			= item_id.replace(/_discount/, '');
			item_value 		= $('#'+item_id).html();	
			item_value 		= item_value.substr(1);
			item_value		= replaceDollar(item_value).toString();
			item_value 		= item_value.replace(/,/g,'');			
			item_value *= 1;			
			
			var presantage = (discount/item_value) * 100;			
					
			var amount = '<a href="" title="Click here to add discount" onclick="DisplayDiscountText(\''+id+'\', \''+tab+'\', \''+qty+'\');return false;"> '+'$'+discount.toFixed(2)+' ('+presantage.toFixed(2)+'%)'+' </a>';			
			$('#'+id+tab).html(amount);
	
}



//execute when the Enter button or cancel button press on the text box
function SetDiscountValue(event, id, tab, initial_str, qty)
{		
	switch (event.keyCode) {
		case 13:
			submitDiscountInput(id, tab, qty);
			break;
			
		case 27:
			cancelDiscountInput(id,tab,initial_str, qty);
			break;
	}
}

// Setting values for the help me calculate light box and opening it
function ShowHelpDiscountCalculate(id, tab, qty, initial_str)
{		
	var item_id  	= id+tab;

	$('#discount_callback_div').val(item_id);
	item_id 			= item_id.replace(/_discount/, '');
	item_value 		= $('#'+item_id).html();			
	item_value 		= item_value.substr(1);	
	item_value		= replaceDollar(item_value);

	resetDiscountFields();
	
	$('#input_rate').val(item_value/qty);

	$('#help_discount_item_value_div_id').html(item_value);
	$('#help_discount_quantity_div_id').html(qty);
	
	// If there is a discount set for this already, restore the values in the fields in help light box
	if(initial_str.indexOf('...') == -1){
		premium = initial_str.split('(');
		premium = parseFloat(replaceDollar(premium[0]));
		rate = premium / qty;
		// calculate the current discount rate and call calculate function to populate rest of the values
		$("#input_rate").val((item_value/qty)-rate);
		calculateNetValue();
	}
	
	showLightBox('help_discount_lightbox_dialog');
}

//Disble the text box and enable the secondary text box
function DisableTextBox(id, sec_id,country_id)
{
	doDisableTexBox(id);
	doEnableTextBox(sec_id);
	
	if ( $("#selected_tab").val() == 'consumer_grid' )
	{
		CalculateTotalCPIForGrid(country_id) ;		
	}
	else
	{
		CalculateTotalCPI() ;
	}
}

function CalculateTotalCPI()
{
	var cpi_value 				= $('#premium_interview_cost').text()*1;
	var cpi_value_original 	= cpi_value;
	var original_primium_interview_amount 		= trim($('#primium_interview_amount').val());
	var original_interview_percentage_cost 	= trim($('#interview_percentage_cost').val());	
	
	primium_interview_amount 	= GetRealValue(original_primium_interview_amount)*1;
	interview_percentage_cost 	= GetRealValue(original_interview_percentage_cost)*1;		
	
	if($("input[@id='cpi_calculate_total_amount']").is(":checked") && original_primium_interview_amount != '' && checknumber(primium_interview_amount)){		
		cpi_value = CalculateCPCValue(cpi_value, primium_interview_amount, CheckCalSymbol(original_primium_interview_amount.substring(0,1)));
	}
	
	if($("input[@id='cpi_calculate_total_precentage']").is(":checked") && original_interview_percentage_cost != '' && checknumber(interview_percentage_cost)){
		interview_percentage_cost = cpi_value_original * (interview_percentage_cost/100);		
		cpi_value = CalculateCPCValue(cpi_value, interview_percentage_cost, CheckCalSymbol(original_interview_percentage_cost.substring(0,1)));
	}
	cpi_value = cpi_value.toFixed(2);
	$('#interview_total_cost').text(cpi_value);
	
}


function CalculateTotalCPIForGrid(country_id)
{
	
	var cpi_value 				= $('#grid_premium_interview_cost_'+country_id).text()*1;	
	var cpi_value_original 	= cpi_value;
	var original_primium_interview_amount 		= trim($('#grid_primium_interview_amount_'+country_id).val());
	var original_interview_percentage_cost 	= trim($('#grid_interview_percentage_cost_'+country_id).val());	
	
	primium_interview_amount 	= GetRealValue(original_primium_interview_amount)*1;
	interview_percentage_cost 	= GetRealValue(original_interview_percentage_cost)*1;		
	
	if($('input[@id=grid_cpi_calculate_total_amount_'+country_id + ']').is(":checked") && original_primium_interview_amount != '' && checknumber(primium_interview_amount)){		
		cpi_value = CalculateCPCValue(cpi_value, primium_interview_amount, CheckCalSymbol(original_primium_interview_amount.substring(0,1)));
	}	
	
	if($('input[@id=grid_cpi_calculate_total_precentage_'+country_id + ']').is(":checked") && original_interview_percentage_cost != '' && checknumber(interview_percentage_cost)){
		interview_percentage_cost = cpi_value_original * (interview_percentage_cost/100);		
		cpi_value = CalculateCPCValue(cpi_value, interview_percentage_cost, CheckCalSymbol(original_interview_percentage_cost.substring(0,1)));
	}
	
	cpi_value = cpi_value.toFixed(2);
	$('#interview_total_cost_consumer_grid_'+country_id).text(cpi_value);	
}

function CheckPricingChangeReasonValidation(id,country_id)
{	
	if ($("#selected_tab").val() == 'consumer_grid')
	{
		if ($('#'+id+country_id).val() != '' )
		{			
			$("#grid_explanation_for_pricing_change_div_"+ country_id).html('<select name="grid_cpi_change_reason_' + country_id + '" id="grid_cpi_change_reason_'+ country_id + '" onchange="populateFields(\'grid_cpi_change_reason_\','+country_id+',\'dropdown\');" style="width:125px">'+
	        		'<option>[---Select Reason---]</option>' +
	        		'<option>Teens</option>'+
	        		'<option>Sensitive Topic</option>' +
	        		'<option>Other</option>' +
	        	'</select>'+
	        	'<textarea name="grid_explanation_for_pricing_change_'+ country_id + '" id="grid_explanation_for_pricing_change_' + country_id + '" cols="18" rows="1" onkeyup="populateFields(\'grid_explanation_for_pricing_change_\','+country_id+');"></textarea>');	
		
		}
		else
		{			
			$("#grid_explanation_for_pricing_change_div_"+ country_id).html('');
		}
	}
	else
	{
		if ($('#'+id).val() == '' )
		{
			$("#explanation_for_pricing_change_div").html('<textarea name="explanation_for_pricing_change" cols="35" rows="3" id="explanation_for_pricing_change">');	
		}
	}
}

//Replace CPI Change Explanation Text Area of Consumer Grid
function ReplaceCPIChangeExplanation(arr_country)
{
	for (var country_id in arr_country)
	{	
		if ($('#grid_explanation_for_pricing_change_'+country_id).val() == '')
		{			
			if(($('input[@id=grid_cpi_calculate_total_amount_'+country_id +']').is(":checked") && $('#grid_primium_interview_amount_'+country_id).val() != '' ) || ($('input[@id=grid_cpi_calculate_total_precentage_'+country_id+ ']').is(":checked") && $('#grid_interview_percentage_cost_'+country_id).val() != '')) 
			{				
				$("#grid_explanation_for_pricing_change_div_"+ country_id).html('<select name="grid_cpi_change_reason_' + country_id + '" id="grid_cpi_change_reason_'+ country_id + '">'+
	        		'<option value="">[---Select Reason---]</option>' +
	        		'<option value="Teens">Teens</option>'+
	        		'<option value="Sensitive Topic">Sensitive Topic</option>' +
	        		'<option value="Other">Other</option>' +
	        	'</select>'+
	        	'<textarea name="grid_explanation_for_pricing_change_'+ country_id + '" id="grid_explanation_for_pricing_change_' + country_id + '" cols="18" class="required" rows="1"></textarea>');	
			}
		 }
   }
}

//Validate Age For Proposal
function ValidateAgeForProposal()
{
	var arr_country = getArrSelectedCountry() ;
	//alert('ValidateAgeForProposal') ;
	var valid = true ;
	for (var country_id in arr_country)
	{		
		if ( toNumber($('#grid_age_min_'+country_id).val()) > toNumber($('#grid_age_max_'+country_id).val()))
		{	
			//alert()
			$('#div_age_error_msg_'+country_id).html('<font color="Red">Invalid Min and Max Age</font>');	
			valid = false ;
		}
		else
		{
			$('#div_age_error_msg_'+country_id).html('');	
			valid = true ;
		}
   }
   return valid ;
}

//Validate Age of the Proposal for a given country
function ValidateAgeForProposalCountry(country_id)
{	
	if ( toNumber($('#grid_age_min_'+country_id).val()) > toNumber($('#grid_age_max_'+country_id).val()))
	{	
		$('#div_age_error_msg_'+country_id).html('<font color="Red">Invalid Min and Max Age</font>');
	}
	else
	{
		$('#div_age_error_msg_'+country_id).html('');
	}
}

//Convert to Number
function toNumber(str, isInteger, roundNum) 
{
  var num;
  var strType = typeof(str);
  
  if (strType == "string") {
    // Strip non-numeric chars and convert to number
    num = Number(str.replace(/[^0-9-.]/g, ""));
    return num;
  } 
  else if (strType != "number") 
  {
    // Return NaN if not a number
    return NaN;
  }
}

//Get Array of Selected Countries
function getArrSelectedCountry()
{
	var selected_country 			= document.getElementById('listRight');	
	var arr_country = new Array() ;
	for(var i = 0; i < selected_country.options.length; ++i)	
	{
		arr_country[i] =  selected_country.options[i].text;
	 
	}
	return 	arr_country ;
}
  
//Add Consumer Grid
function addFeeGrid(){	
			
	var arr_country = getArrSelectedCountry() ;
	
	ReplaceCPIChangeExplanation(arr_country) ;
	var is_age_valid = ValidateAgeForProposal(arr_country) ;
	
	if(!$("#consumer_grid_frm").valid() || is_age_valid == false)
	{				  	  
		return false;
	}
	
	$('#sample_costs'+focusTab+' tbody').html('');
	for (var i=0; i < arr_country.length; i++ )
	{ 
		$.get('/app/Proposal/AddProposalSampleFees/country/'+arr_country[i]+'/sample_des/'+$('#grid_sample_description_'+i).val()+'/incidence/'+$('#grid_incidence_'+i).val()+'/total_no_questions/'+$('#grid_sample_num_questions_'+i).val()+'/tot_number_of_samples/'+$('#grid_total_sample_'+i).val()+'/type/Consumer/focusTab/'+focusTab+'/mode/add');
	}		
	hideLightBox('adding_sample_cost_tags_dialog');	
}

function populateFields(id,current_country_id,element_type)
{	
	if (current_country_id == 0)
	{
		var arr_country = getArrSelectedCountry() ;			
		for (var country_id in arr_country)
		{
			if (country_id > 0)
			{	
				if ( element_type == 'radio_button')
				{	
					$('input[@id='+id+country_id+']').attr( "checked", "checked" )
					$('input[@id='+id+'0]').attr( "checked", "checked" )
				}
				else if (element_type == 'dropdown')
				{
					var initial_val = $('select#grid_cpi_change_reason_'+current_country_id+' option:selected').text(); 						
					$('select#grid_cpi_change_reason_'+country_id+' option:selected').text(initial_val); 
				}
				else
				{
					$('#'+id+country_id).val($('#'+id+'0').val());		
				}
			}		
			$('#'+id+country_id).focus() ;		
		}
		$('#'+id+'0').focus() ;	
	}
}

//Select Gender according to the Gender values of the 1st country
function onMFButtonClick(p_oEvent,arr_id) 
{  
  var buttons            = arr_id[0] ;  
  var id 					 = arr_id[1] ;
  var current_country_id = arr_id[2] ;
  if (current_country_id == 0)
  {
		var arr_country = getArrSelectedCountry() ;
		for (var country_id=0; country_id < arr_country.length; country_id++ )
		{
			if (country_id > 0)
			{	
				var button_0 		= buttons[0] ;;
				var current_button = buttons[country_id] ;
				if (button_0.get("checked") == true)
				{
					current_button.set("checked", true); 
				}
				else
				{
					current_button.set("checked", false); 
				}
			}
		}
  }
}
		
//common method to disable a text box by id
function doDisableTexBox(id)
{
	$('#'+id).attr("disabled" , "disabled");	
}

//common method to enable a text box by id
function doEnableTextBox(id)
{
	$('#'+id).attr("disabled" , "");	
}


function disableSelectedOption(listbox){
	listbox.options[listbox.selectedIndex].disabled = true;
	//listbox.options[listbox.selectedIndex].style.visibility = 'hidden';
}

function enabledOption(listbox,i){
	listbox.options[i].disabled = false;
	//listbox.options[i].style.visibility = 'visible';
}

function DisplayTotNumberOfQuestionsText(id, tab)
{	
	var initial_str 	= $('#'+id+tab).html();	
	initial_str 		= initial_str.substring(initial_str.indexOf('">')+2 , initial_str.indexOf('</a>'));
	initial_val			= initial_str.trim();	
	
	var input_str = '<input type="text" id="'+id+'_text'+tab+'" size="5" value="'+initial_val+'" name"'+id+'_text'+tab+'" onkeydown="SetTotNumberOfQuestions(event, \''+id+'\', \''+tab+'\', \''+initial_val+'\')"; /><img src="/images/tick_blue.gif" width="18" height="17" title="Ok" onClick="submitTotNumberOfQuestionsInput(\''+id+'\', \''+tab+'\'); return false;" alt="Ok" style="cursor: pointer" />&nbsp;<img src="/images/delete.gif" width="8" height="8" title="Cancel" onclick="cancelTotNumberOfQuestionsInput(\''+id+'\', \''+tab+'\', \''+initial_val+'\');return false;" alt="Cancel" style="cursor: pointer" />';	

	$('#'+id+tab).html(input_str);
	$('#'+id+'_text'+tab).focus();
}

//execute when the Enter button or cancel button press on the text box
function SetTotNumberOfQuestions(event, id, tab, initial_str)
{
	switch (event.keyCode) {
		case 13:
			submitTotNumberOfQuestionsInput(id, tab);
			break;
			
		case 27:
			cancelTotNumberOfQuestionsInput(id,tab,initial_str);
			break;
	}
}

function submitTotNumberOfQuestionsInput(id,tab){
			var new_ques_val  = $('#'+id+'_text'+tab).val() ;			
			
			if ( new_ques_val.length == 0	)
			{
				new_ques_val = 0 ;
			}
			var new_input_str = '<a href="" title="Click here to edit total number of questions" onclick="DisplayTotNumberOfQuestionsText(\''+id+'\', \''+tab+'\');return false;"> '+new_ques_val+' </a>';			
			$('#'+id+tab).html(new_input_str);
	
}

function cancelTotNumberOfQuestionsInput(id,tab,initial_str){
	var can_str = '<a href="" title="Click here to edit total number of questions" onclick="DisplayTotNumberOfQuestionsText(\''+id+'\', \''+tab+'\');return false;"> '+initial_str+' </a>'		
	$('#'+id+tab).html(can_str);
	
}

function DisplayIncidenceText(id, tab)
{	
	var initial_str 	= $('#'+id+tab).html();	
	initial_str 		= initial_str.substring(initial_str.indexOf('">')+2 , initial_str.indexOf('</a>'));
	initial_val			= initial_str.trim();	
	
	var input_str = '<input type="text" id="'+id+'_text'+tab+'" size="5" value="'+initial_val+'" name"'+id+'_text'+tab+'" onkeydown="SetIncidence(event, \''+id+'\', \''+tab+'\', \''+initial_val+'\')"; /><img src="/images/tick_blue.gif" width="18" height="17" title="Ok" onClick="submitIncidenceInput(\''+id+'\', \''+tab+'\'); return false;" alt="Ok" style="cursor: pointer" />&nbsp;<img src="/images/delete.gif" width="8" height="8" title="Cancel" onclick="cancelIncidenceInput(\''+id+'\', \''+tab+'\', \''+initial_val+'\');return false;" alt="Cancel" style="cursor: pointer" />';	

	$('#'+id+tab).html(input_str);
	$('#'+id+'_text'+tab).focus();
}

//execute when the Enter button or cancel button press on the text box
function SetIncidence(event, id, tab, initial_str)
{
	switch (event.keyCode) {
		case 13:
			submitIncidenceInput(id, tab);
			break;
			
		case 27:
			cancelIncidenceInput(id,tab,initial_str);
			break;
	}
}

function submitIncidenceInput(id,tab){
			var new_incidence_val  = $('#'+id+'_text'+tab).val() ;			
			
			if ( new_incidence_val.length == 0	)
			{
				new_incidence_val = 0 ;
			}
			var new_input_str = '<a href="" title="Click here to edit Incidence" onclick="DisplayIncidenceText(\''+id+'\', \''+tab+'\');return false;"> '+new_incidence_val+' </a>';			
			$('#'+id+tab).html(new_input_str);
	
}

function cancelIncidenceInput(id,tab,initial_str){
	var can_str = '<a href="" title="Click here to edit Incidence" onclick="DisplayIncidenceText(\''+id+'\', \''+tab+'\');return false;"> '+initial_str+' </a>'		
	$('#'+id+tab).html(can_str);
	
}


function DisplayQuantityText(id, tab)
{	
	var initial_str 	= $('#'+id+tab).html();	
	initial_str 		= initial_str.substring(initial_str.indexOf('">')+2 , initial_str.indexOf('</a>'));
	initial_val			= initial_str.trim();	
	
	var input_str = '<input type="text" id="'+id+'_text'+tab+'" size="5" value="'+initial_val+'" name"'+id+'_text'+tab+'" onkeydown="SetQuantity(event, \''+id+'\', \''+tab+'\', \''+initial_val+'\')"; /><img src="/images/tick_blue.gif" width="18" height="17" title="Ok" onClick="submitQuantityInput(\''+id+'\', \''+tab+'\'); return false;" alt="Ok" style="cursor: pointer" />&nbsp;<img src="/images/delete.gif" width="8" height="8" title="Cancel" onclick="cancelQuantityInput(\''+id+'\', \''+tab+'\', \''+initial_val+'\');return false;" alt="Cancel" style="cursor: pointer" />';	

	$('#'+id+tab).html(input_str);
	$('#'+id+'_text'+tab).focus();
}

//execute when the Enter button or cancel button press on the text box
function SetQuantity(event, id, tab, initial_str)
{
	switch (event.keyCode) {
		case 13:
			submitQuantityInput(id, tab);
			break;
			
		case 27:
			cancelQuantityInput(id,tab,initial_str);
			break;
	}
}

function submitQuantityInput(id,tab){
			var new_quantity_val  = $('#'+id+'_text'+tab).val() ;			
			
			if ( new_quantity_val.length == 0	)
			{
				new_quantity_val = 0 ;
			}
			var new_input_str = '<a href="" title="Click here to edit Quantity" onclick="DisplayQuantityText(\''+id+'\', \''+tab+'\');return false;"> '+new_quantity_val+' </a>';			
			$('#'+id+tab).html(new_input_str);
	
}

function cancelQuantityInput(id,tab,initial_str){
	var can_str = '<a href="" title="Click here to edit Quantity" onclick="DisplayQuantityText(\''+id+'\', \''+tab+'\');return false;"> '+initial_str+' </a>'		
	$('#'+id+tab).html(can_str);
	
}
