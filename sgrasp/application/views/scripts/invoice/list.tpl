{include file='home/header.tpl'}
    <table style='width: 98%; background-color: white;'> 
    	<tr> 
    		<th width="100" type="ed" align="right" color="white" sort="str">Account</th>
            <th width="100" type="ed" align="right" color="white" sort="str">Project</th> 
          	<th width="70" type="ed" align="right" color="white" sort="str">Type</th> 
          	<th width="100" type="ed" align="right" color="white" sort="str">Date</th> 
          	<th width="70" type="ed" align="right" color="white" sort="str">Amount</th> 
          	<th width="100" type="ed" align="right" color="white" sort="str">Status</th> 
      </tr>        
        {foreach from=$armcs item=invoice} 
        <tr>
        	<td>{$invoice.account_id}</td>
        	<td><a href='/Invoice/invoice/id/{$invoice.armc_id}'>{$invoice.invoice_name}</a></td>
        	<td>{$invoice.armc_type_id}</td>
        	<td>{$invoice.created_date}</td>
        	<td>${$invoice.invoice_amount|number_format:2}</td>
        	<td>{$invoice.armc_status_id}</td>
        </tr>
        {/foreach}
    </table>
{include file='home/footer.tpl'}