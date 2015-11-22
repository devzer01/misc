<html>
<head>
{literal}
	<style>
		body {
			background-color: white;
		}
		
		th {
			background-color: gray;
			color: black;
			margin: 4px;
			padding: 4px;
			border: 2px solid black;
		}
		
		.total {
			border: 1px solid black;
		}
		
		.bd {
			border: 1px solid black;
		}
		.inv_title {
			font-weight: bold;
			font-size: 28px;
			line-height: 26px;
			color: #eee;
			position: absolute;
			z-index: 0;
			text-transform: uppercase;
			text-align: center;
			width: 250px;
		}
	</style>
{/literal}
</head>
<body>
<table>
	<tr><td>
		<span class="title">Estimate: {$quote.proposal_id}</span>
	</td></tr>
	<tr><td>
		<table width='800px'>
			<tr>
				<td style='width: 250px'>{$office.company_name}<br />{$office.street1}<br />{$office.city} {$office.state}&nbsp;&nbsp;{$office.postalcode}<br />{$office.country_code} <br/> Phone: {$office.phone}</td>
				<td style='width: 250px'><div class="inv_title">ESTIMATE</div></td>
				<td style='width: 300px' rowspan='2' valign='top'><img src="{$logopath}" width="150" height="55" alt="" border="0" /></td>
			</tr>
			<tr>
				<td style='height: 90px;' colspan='3'>&nbsp;</td>
			</tr>
			<tr style='margin-top: 50px;'>
				<td>
					{$quote.account_name}<br />{$contact.contact_name}<br />{$contact.street1}<br />{$contact.city} {$contact.province}&nbsp;&nbsp;{$contact.postal}<br />{$contact.country}
				</td>
				<td></td>
				<td></td>
			</tr>
		</table>
	</td></tr>
	<tr><td>
		<table>
			<tr>
				<td style='width: 250px;'>&nbsp;</td>
				<td style='width: 250px;'>&nbsp;</td>
				<td>
					<table width="300px;" align="right" cellpadding="2" cellspacing="0" id="inv_box">
<tr>
<th>Estimate #:</th>
<td class='bd'>{$quote.proposal_id}</td>
</tr>
<tr>
<th>Date:</th>

<td class='bd'>{$quote.quote_date|date_format}</td>
</tr>
<tr>
<th>Estimate Total:</th>
<td class='bd'>{$settings[$smarty.const.SYSTEM_CURRENCY].value}{$total|number_format:2}</td>
</tr>
</table>
				</td>
			</tr>
		</table>
	</td></tr>
	<tr><td>
		<table style='margin-top: 75px;'>
			<tr><td>
				<table width="710px;" border=1 cellpadding="2" cellspacing="0" id="inv_items">
					<tr>
					<th>Item</th>
					
					<th>Description</th>
					<th style="width: 95px;">Cost&nbsp;({$settings[$smarty.const.SYSTEM_CURRENCY].value})</th>
					<th>Units</th>
					<th class="end">Total&nbsp;({$settings[$smarty.const.SYSTEM_CURRENCY].value})</th>
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
			</td></tr>
		</table>
	</td></tr>
	<tr><td>
		<table>
			<tr>
			<td style='width: 200px;'>&nbsp;</td>
			<td style='width: 200px;'>&nbsp;</td>
				<td>
					<table width='300px;' cellpadding="2" cellspacing="0">
						<tr>
						<th><strong>Subtotal:</strong></th>
						<td class='bd'><strong>{$settings[$smarty.const.SYSTEM_CURRENCY].value}{$total|number_format:2}</strong></td>
						</tr>
						<tr>
						<th class="total">Estimate Total:</th>
						<td class="total">{$settings[$smarty.const.SYSTEM_CURRENCY].value}{$total|number_format:2}</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td></tr>
</table>
</body></html>