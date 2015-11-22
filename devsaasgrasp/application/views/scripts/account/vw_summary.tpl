{include file='common/div_header.tpl' 
			div_title='Summary' 
			div_name='account_summary' 
			div_width='80%' 
			div_align='center'
			div_title_2="`$header_text`"}
<table>
   <tr>
      <td align="right">
         {$lang.ACCOUNT} ID
      </td>
      <td align="left">
      	{$account.account_id}
      </td>
      <td align="right">
         {$lang.ACCOUNT} Name
      </td>
      <td align="left">
          {$account.account_name}
      </td>
   </tr>
   
   <tr>
      <td align="right">
         {$lang.REGION}
      </td>
      <td align="left">
         {$account.region_description}
      </td>
      <td align="right">
         {$lang.COUNTRY}
      </td>
      <td align="left">
          {$account.country_description}
      </td>
   </tr>   
    <tr>
      <td align="right">
         {$lang.ACCOUNT_TYPE} / SubType
      </td>
      <td align="left">
         {section name=id loop=$account.account_type}
         	{$account.account_type[id].account_type_description} 
         {sectionelse}
         	<i>No Account Type Defined</i>
         {/section}
         {section name=id loop=$account.account_sub_type}
         	{$account.account_sub_type[id].account_sub_type_description}
         {sectionelse}
         	<i>No Account Sub Type Defined</i>
         {/section}
      </td>
   </tr>
   {if $display.view_credit_hold_status eq 1}
   <tr>
   	<td align="right">Credit Status</td>
   	<td align="left" colspan="3"></td>
   </tr>
   {/if}
   <tr>
    <td align="right">
         Current Credit Limit
      </td>
      <td align="left">
      </td>
      <td align="right">
      {if $display.edit_credit_limit eq 1}
      	<input type="button" value="Edit Credit Limit" onclick="document.location='/app/Account/DisplayEditCreditLimit/account_id/{$account.account_id}'" />{/if}</td></tr></table></td>
      <td align="left">&nbsp;
         
      </td>
      <td align="left">&nbsp;
      	
      </td>
   </tr>
   
   
</table>
</div>