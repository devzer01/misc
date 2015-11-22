{include file='home/header.tpl'}
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
	
	<div class="button-panel">	
					<a href="/invoice/detail/invoice/{$invoice.armc_id}" class="button"><span>Edit</span></a>
				
					<span class="spacer"></span>
					<a href="/invoice/pdf/invoice/{$invoice.armc_id}" class="button"><span>PDF</span></a>

					<span class="spacer"></span>
					<a href="/invoice/print/invoice/{$invoice.armc_id}" class="button"><span>Print</span></a>
		
	</div>
	<span class="title">Invoice: {$invoice.armc_id}</span>
</h2>

	<div class='invoice-container'>
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
{$officeaddress.company_name}<br />{$officeaddress.street1}<br />{$officeaddress.city} {$officeaddress.state}&nbsp;&nbsp;{$officeaddress.postalcode}<br />{$officeaddress.country_code}
<span class="phone"><br/>Phone: {$officeaddress.phone}</span>
</div>
<div id="inv_to">
{$invoice.account_name}<br />{$contact.contact_name}<br />{$contact.address_1}<br />{$contact.city} {$contact.province}&nbsp;&nbsp;{$contact.postal}<br />{$contact.country}

</div>
</div>
<div id="inv_logo">
<div class="inv_from">
<img src="/Settings/getlogo" width="150" height="55" alt="" border="0" /><br />
<div class='inv_abn'></div>
</div>
<table align="right" cellpadding="2" cellspacing="0" id="inv_box">
<tr>
<th>Invoice #:</th>
<td>{$invoice.armc_id}</td>
</tr>
<tr>
<th>Date:</th>
<td>{$invoice.invoice_date|date_format}</td>
</tr>
<tr>
<th>Invoice Total:</th>
<td>${$invoice.invoice_amount|number_format:2}</td>
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
{foreach from=$items item=item}
<tr>
<td class="inv_col_item">
{$item.sku}
</td>
<td>{$item.description}</td>

<td class="inv_col_cost">{$item.unit_cost|number_format}</td>
<td class="inv_col_qty">{$item.units}</td>
<td class="inv_col_price">{$item.total|number_format}</td>
</tr>
{/foreach}
</table>
</div>
<table width="100%" cellpadding="0" cellspacing="0" id="inv_totals">
<tr><td>{$terms.comment}
<table cellpadding="2" cellspacing="0">
<tr>
<th><strong>Subtotal:</strong></th>
<td><strong>${$invoice.invoice_amount|number_format:2}</strong></td>
</tr>
<tr>
<th class='total'><strong>Discount:</strong></th>
<td class='total'><strong>{$invoice.discount|number_format:2}%</strong></td>
</tr>
<tr>
<th class="total">Invoice Total:</th>
<td class="total">${math equation="t - (t * y / 100)" t=$invoice.invoice_amount y=$invoice.discount format="%.2f"}</td>
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
{include file='home/footer.tpl'}