{include file='home/header.tpl'}
    <table style='width: 98%; background-color: white;'> 
    	<tr> 
          	<th width="70" type="ed" align="right" color="white" sort="str">Name</th> 
          	<th width="100" type="ed" align="right" color="white" sort="str">Email</th>
          	<th width="70" type="ed" align="right" color="white" sort="str">Type</th>  
          	<th width="70" type="ed" align="right" color="white" sort="str">Last Login</th> 
          	<th width="100" type="ed" align="right" color="white" sort="str">Status</th> 
      </tr>        
        {foreach from=$users item=user} 
        <tr>
        	<td><a href='/User/view/id/{$user.user_id}'>{$user.first_name} {$user.last_name}</a></td>
        	<td>{$user.email_address}</td>
        	<td>{$user.user_type_id}</td>
        	<td>{$user.last_login}</td>
        	<td>{$user.status}</td>
        </tr>
        {/foreach}
    </table>
{include file='home/footer.tpl'}