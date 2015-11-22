{include file='home/header.tpl'}
<table style='width: 98%; background-color: white;'> 
    	<tr> 
            <th width="100" type="ed" align="right" color="white" sort="str">Name</th> 
          	<th width="70" type="ed" align="right" color="white" sort="str">Account Type</th> 
          	<th width="70" type="ed" align="right" color="white" sort="str">Country</th> 
          	<th width="100" type="ed" align="right" color="white" sort="str">Creator</th> 
          	<th width="70" type="ed" align="right" color="white" sort="str">Date</th> 
          	<th width="70" type="ed" align="right" color="white" sort="str">YTD Revenue</th>
        </tr> 
{foreach from=$accounts item=account}
	<tr id="{$account.account_id}" class='{cycle values="tab,tab1"}'> 
			<td><a href='/account/view/id/{$account.account_id}'>{$account.account_name}</a></td> 
            <td>{$at[$account.account_type_id].account_type_description}</td> 
            <td>{$country[$account.country_code].country_description}</td>
            <td>{$user[$account.created_by].last_name}</td>
            <td>{$account.created_date|date_format}</td>
            <td>{$account.total_revenue|number_format}</td> 
    </tr>
{/foreach}
</table>
{include file='home/footer.tpl'}