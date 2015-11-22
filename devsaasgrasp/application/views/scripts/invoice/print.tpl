<html>
<head></head>
{debug}
{literal}
<script type="text/javascript">
	try {
		this.print();
	}
	catch(e) {
		window.onload = window.print;
	}
</script>
{/literal}
<body>
<link href="/css/platform.css" rel="stylesheet" type="text/css" />
	<style>
	{literal}
		body {
			background-color: white;
		}
		
		
	{/literal}		
	</style>
<div class="container" style='background-color: white;'>

<div class="clearb"></div>

<h2 class="button-panel-title">
	<span class="title">Invoice: {$armc.armc_id}</span>
</h2>

	<div class='invoice-container' style='background-color: white;'>
		<div class="status-sent tooble-cursor">
							<div class="tooble">
					<b class="iefix-4"></b>
						Your client has been notified. When they login <span></span>the estimate will be visible for printing.
				</div>
					</div>
		
	<div id="inv" class="t01-01">	

<div class="inv_title estimate">
INVOICE	</div>
<div id="inv_top">
<div id="inv_address">
<div class="inv_from">
{$office.company_name}<br />{$office.street1}<br />{$office.city} {$office.state}&nbsp;&nbsp;{$office.postalcode}<br />{$office.country_code}
<span class="phone"><br/>Phone: {$office.phone}</span>
</div>
<div id="inv_to">
{$quote.account_name}<br />{$contact.contact_name}<br />{$contact.street1}<br />{$contact.city} {$contact.province}&nbsp;&nbsp;{$contact.postal}<br />{$contact.country}

</div>
</div>
<div id="inv_logo">
<div class="inv_from">
<img src="/Settings/getlogo" width="150" height="55" alt="" border="0" /><br />
<div class='inv_abn'></div>
</div>
<table align="right" cellpadding="2" cellspacing="0" id="inv_box">
<tr>
<th>Estimate #:</th>
<td>{$armc.armc_id}</td>
</tr>
<tr>
<th>Date:</th>

<td>{$armc.invoice_date|date_format}</td>
</tr>
<tr>
<th>Estimate Total:</th>
<td>${$total|number_format:2}</td>
</tr>
</table>
</div>
<div class="inv_topspace"><img src="https://fb-assets.com/images/spacer.gif" width="1" height="1" alt="" /></div>
</div>
<div class="inv_items_container">
<table width="100%" cellpadding="2" cellspacing="0" id="inv_items">
<tr>
<th>Item</th>

<th>Description</th>
<th style="width: 95px;">Cost&nbsp;($)</th>
<th>Units</th>
<th class="end">Total&nbsp;($)</th>
</tr>
{section name=id loop=$items}
<tr>
<td class="inv_col_item">
{$items[id].sku}
</td>
<td>{$items[id].description}</td>

<td class="inv_col_cost">{$items[id].unit_cost|number_format}</td>
<td class="inv_col_qty">{$items[id].units}</td>
<td class="inv_col_price">{$items[id].total|number_format}</td>
</tr>
{/section}
</table>
</div>
<table width="100%" cellpadding="0" cellspacing="0" id="inv_totals">
<tr><td>{$terms.comment}
<table cellpadding="2" cellspacing="0">
<tr>
<th><strong>Subtotal:</strong></th>
<td><strong>${$total|number_format:2}</strong></td>
</tr>
<tr>
<th class="total">Estimate Total:</th>
<td class="total">${$total|number_format:2}</td>
</tr>
</table>
<div class="clearb"></div>
</td></tr></table>
<div class="tearoff">	
<table id="stub_summary" cellspacing="1" align="center">
<tr>
<th>Invoice #:</th>
<th>Invoice Date:</th>
<th>Amount Due  USD:</th>
<th>Amount Paid:</th>
</tr>
<tr>	
<td>0000001</td>

<td>November 23, 2011</td>
<td><strong>$250.00</strong></td>
<td></td>
</tr>
</table>
<div class="from">
All India Resturant<br />Suman Bista<br />1734 Marine Drive<br />West Vancouver BC&nbsp;&nbsp;M6B 3R7<br />Canada
</div>
<div class="to">
GLOBAL ENTERPRISE MANAGEMENT SOLUTIONS<br />15405 DES MOINES MEMORIAL DR APT F203<br />BURIEN WA&nbsp;&nbsp;98148<br />United States

</div>
<div style="clear: both;"></div>
</div>
</div>
</div>
</div>
</body></html>