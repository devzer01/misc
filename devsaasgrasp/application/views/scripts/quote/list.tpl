{include file='home/header.tpl'}
<table style='width: 98%; background-color: white;'> 
<tr>
<th style='text-align: center;'>Quote</th>
<th style='text-align: center;'>Account</th>
<th style='text-align: center;'>Recepient</th>
<th style='text-align: center;'>Date</th>
<th style='text-align: center;'>Value</th>
<th style='text-align: center;'>Status</th>
</tr>
{foreach item=quote from=$quotes}
<tr id='{$quote.proposal_id}'>
	<td><a href='/Quote/quote/id/{$quote.proposal_id}'>{$quote.proposal_id} - {$quote.proposal_name}</a></td> 
	<td>{$quote.account_name}</td>   
   	<td>{$quote.contact_name} {$quote.last_name}</td>
   	<td>{$quote.quote_date|date_format:"%Y-%m-%d"}</td>
   	<td>${$quote.quote_value|number_format:2}</td>
   	<td>{$qs[$quote.proposal_status_id].proposal_status_description}</td>
</tr>
{/foreach}
</table>
{include file='home/footer.tpl'}