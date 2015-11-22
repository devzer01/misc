{include file='home/header.tpl}
 <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/fonts/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/button/assets/skins/sam/button.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/container/assets/skins/sam/container.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/calendar/assets/skins/sam/calendar.css" />

<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/element/element-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/button/button-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/container/container-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/calendar/calendar-min.js"></script>
	<link href="/css/platform.css" rel="stylesheet" type="text/css" />
	<style>
	{literal}
		body {
			background-color: white;
		}
		
		div {
			float: left;
			padding: 5px;
			margin: 2px;
		}
		
		input {
			height: 25px;
		}
		
		.itemheader {
			background-color: #dedede; 
			color: #000000; 
			font-weight: 800; 
			padding: 7px 0px 2px 0px; 
			text-align: center; 
			margin: 0px 4px 0px 4px;
		}
	</style>
	<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js'></script>
	{/literal}
<div style='background-color: white;'>
<form method='post' action='/invoice/savedetail/id/{$invoice.armc_id}'>
	<div class='helpbox' style='display:none;'>
		How to create an element x <br/>
		How to create an element x <br/>
		How to create an element x <br/>
	</div>
<div style='border: 1px solid black; width: 800px;'>	
<div style='clear:both; float: left;'><strong>Invoice</strong>: {$invoice.armc_id}</div>
<div style='float: right;'><strong>PO Number</strong>: <input type='text' size='20' name='ponumber' id='ponumber' value='{$invoice.ponumber}'/> </div>
<div style='clear:both; float: left;'><strong>Account</strong>: {$invoice.account_name}</div>
<div style='float: right;'>
	<strong>Date</strong>: 
	<input type='text' size='20' name='invoicedate' id='invoicedate' value='{$invoice.invoice_date}'/> <button type="button" id="show" title="Show Calendar"><img src='/images/calbtn.gif' /></button>
	<div id="cal1Container"></div>
</div>
<div style='clear:both; float: left;'><strong>Contact</strong>: {$contact.contact_name}</div>
<div style='float: right;'><strong>Discount</strong>:<input style='text-align: right;' type='text' size='5' name='discount' id='discount' value='{$invoice.discount}'/></div>
</div>
<div style='width: 800px; float: left; clear: both; border: 1px solid black;'>
<div class='itemheader' style='clear: both; width: 100px;'>SKU</div>
<div class='itemheader' style='width: 300px;'>Description</div>
<div class='itemheader' style='width: 100px;'>Unit Cost</div>
<div class='itemheader' style='width: 100px;'>Units</div>
<div class='itemheader' style='width: 100px;'>Total</div>

{section name=qlines start=0 loop=5 step=1}
<div style='clear: both; width: 100px;'>
	<input style='width: 80px;' type='text' value='{$lines[$smarty.section.qlines.index].sku}' name='sku_{$smarty.section.qlines.index}' id='sku_{$smarty.section.qlines.index}' size=5 />
</div>
<div style='width: 300px;'>
	<input style='width: 280px;' type='text' value='{$lines[$smarty.section.qlines.index].description}' name='desc_{$smarty.section.qlines.index}' id='desc_{$smarty.section.qlines.index}' size=5 />
</div>
<div style='width: 100px;'>
	<input class='unitcost' type='text' value='{$lines[$smarty.section.qlines.index].unit_cost}' name='uc_{$smarty.section.qlines.index}' id='uc_{$smarty.section.qlines.index}' size=5 />
</div>
<div style='width: 100px;'>
	<input class='units' type='text' value='{$lines[$smarty.section.qlines.index].units}' name='u_{$smarty.section.qlines.index}' id='u_{$smarty.section.qlines.index}' size=5 />
</div>
<div style='width: 100px;'>
	<input class='total' type='text' value='{$lines[$smarty.section.qlines.index].total}' name='t_{$smarty.section.qlines.index}' id='t_{$smarty.section.qlines.index}' size=5 />
</div>
{/section}
</div>
<div style='clear:both; float: left; width: 800px; border: 1px solid black;'>
<div style='width: 45%; float: left; border: 1px solid black;'>
<div style='width: 100%; float: left;'>Terms</div> 
<div style='float: left;'><textarea name='terms' style='width: 300px;'>{$terms.comment}</textarea></div>
</div>
<div style='width: 45%; float: left; border: 1px solid black;'>
<div style='width: 100%; float: left;'>Message</div>
<div style='float: left; width: 100%; height: 60px;'><textarea name='notes' style='width: 90%; height: 90%;'>{$notes.comment}</textarea></div>
</div></div>
<div style='clear:both;'>
	<input type='button' value='Save Invoice' onclick='this.form.submit();' />
	<input type='button' value='Cancel' onclick='cancelQuote();' />
</div> 
</form></div>
{literal}
	<script>
		$(".unitcost").change(function() {
				var re = /[0-9]+/g;
				var tokens = $(this).attr('id').match(re);
				var units  = $("#u_" + tokens[0]).val();
				if (units != '') {
					$("#t_" + tokens[0]).val($(this).val() * units); // * $("#u_" + tokens[0].val());
				}
			});
		$(".units").change(function() {
				var re = /[0-9]+/g;
				var tokens = $(this).attr('id').match(re);
				var unitcost  = $("#uc_" + tokens[0]).val();
				if (unitcost != '') {
					$("#t_" + tokens[0]).val($(this).val() * unitcost); // * $("#u_" + tokens[0].val());
				}
			});
			
	YAHOO.util.Event.onDOMReady(function(){

        var Event = YAHOO.util.Event,
            Dom = YAHOO.util.Dom,
            dialog,
            calendar;

        var showBtn = Dom.get("show");

        Event.on(showBtn, "click", function() {

            // Lazy Dialog Creation - Wait to create the Dialog, and setup document click listeners, until the first time the button is clicked.
            if (!dialog) {

                // Hide Calendar if we click anywhere in the document other than the calendar
                Event.on(document, "click", function(e) {
                    var el = Event.getTarget(e);
                    var dialogEl = dialog.element;
                    if (el != dialogEl && !Dom.isAncestor(dialogEl, el) && el != showBtn && !Dom.isAncestor(showBtn, el)) {
                        dialog.hide();
                    }
                });

                function resetHandler() {
                    // Reset the current calendar page to the select date, or 
                    // to today if nothing is selected.
                    var selDates = calendar.getSelectedDates();
                    var resetDate;
        
                    if (selDates.length > 0) {
                        resetDate = selDates[0];
                    } else {
                        resetDate = calendar.today;
                    }
        
                    calendar.cfg.setProperty("pagedate", resetDate);
                    calendar.render();
                }
        
                function closeHandler() {
                    dialog.hide();
                }

                dialog = new YAHOO.widget.Dialog("container", {
                    visible:false,
                    context:["show", "tl", "bl"],
                    buttons:[ {text:"Reset", handler: resetHandler, isDefault:true}, {text:"Close", handler: closeHandler}],
                    draggable:false,
                    close:true
                });
                dialog.setHeader('Pick A Date');
                dialog.setBody('<div id="cal"></div>');
                dialog.render(document.body);

                dialog.showEvent.subscribe(function() {
                    if (YAHOO.env.ua.ie) {
                        // Since we're hiding the table using yui-overlay-hidden, we 
                        // want to let the dialog know that the content size has changed, when
                        // shown
                        dialog.fireEvent("changeContent");
                    }
                });
            }

            // Lazy Calendar Creation - Wait to create the Calendar until the first time the button is clicked.
            if (!calendar) {

                calendar = new YAHOO.widget.Calendar("cal", {
                    iframe:false,          // Turn iframe off, since container has iframe support.
                    hide_blank_weeks:true  // Enable, to demonstrate how we handle changing height, using changeContent
                });
                calendar.render();

                calendar.selectEvent.subscribe(function() {
                    if (calendar.getSelectedDates().length > 0) {

                        var selDate = calendar.getSelectedDates()[0];

                        // Pretty Date Output, using Calendar's Locale values: Friday, 8 February 2008
                        //var wStr = calendar.cfg.getProperty("WEEKDAYS_LONG")[selDate.getDay()];
                        var dStr = selDate.getDate();
                        var mStr = selDate.getMonth();
                        //var mStr = calendar.cfg.getProperty("MONTHS_LONG")[selDate.getMonth()];
                        var yStr = selDate.getFullYear();
        
                        Dom.get("invoicedate").value = yStr + "-" + mStr + "-" + dStr;
                    } else {
                        Dom.get("invoicedate").value = "";
                    }
                    dialog.hide();
                });

                calendar.renderEvent.subscribe(function() {
                    // Tell Dialog it's contents have changed, which allows 
                    // container to redraw the underlay (for IE6/Safari2)
                    dialog.fireEvent("changeContent");
                });
            }

            var seldate = calendar.getSelectedDates();

            if (seldate.length > 0) {
                // Set the pagedate to show the selected date if it exists
                calendar.cfg.setProperty("pagedate", seldate[0]);
                calendar.render();
            }

            dialog.show();
        });
    });
	</script>
{/literal}
</body>
</html>