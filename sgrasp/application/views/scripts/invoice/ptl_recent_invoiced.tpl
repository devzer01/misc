<table width="100%">
<tr>
<td class="CellTableHead">
Recently Invoiced Billing Reports
</td>
</tr>
<tr>
   <td>
      <table width="100%">
      <tr>
	     <TD class='header1'><strong>BR#</strong></TD>
	     <td class="header1"><strong>Project Name</strong></td>
	     {if $columns.display_account_name}
	     <td class="header1"><strong>Account Name</strong></td>
	     {/if}
	     {if $columns.display_account_executive}
	     <td class="header1"><strong>AE</strong></td>
	     {/if}
	     {if $columns.display_account_manager}
	     <td class="header1"><strong>AM</strong></td>
	     {/if}
	     <td class="header1" align="center"><strong>Invoice Date</strong></td>
	     {if $columns.display_invoice_number}
	     <td class="header1" align="center"><strong>Invoice Number</strong></td>
	     {/if}
	     <td class="header1" align="right"><strong>Amount</strong></td>
      </tr>

      {section name=id loop=$list}
      <tr class='{cycle values="tab,tab1"}'>
	     <td align='left'><a href="/app/atm/armc/?e={$list[id].armc_link|url_encrypt}">{$list[id].armc_id}</a></td>
	     <td align='left'>{if $list[id].project_link}<a href="/app/stm/?e={"action=vw_detail&study_id=`$list[id].study_id`"|url_encrypt}">{$list[id].project_name}</a>{else}{$list[id].project_name}{/if}</td>
	     {if $columns.display_account_name}
	     <td align='left'><a href="/app/acm/?e={"action=display_account_detail&account_id=`$list[id].account_id`"|url_encrypt}">{$list[id].account_name}</a></td>
	     {/if}
	     {if $columns.display_account_executive}
	     <td align="left">{$list[id].account_executive}</td>
	     {/if}
	     {if $columns.display_account_manager}
	     <td align="left">{$list[id].account_manager}</td>
	     {/if}
	     <td align='center'>{$list[id].transaction_date|date_format:"%Y-%m-%d"}</td>
	     {if $columns.display_invoice_number}
	     <td align="center">{$list[id].transaction_number}</td>
	     {/if}
	     <td align='right'>{$list[id].amount}</td>
      </tr>
      {sectionelse}
      <tr class="tab1">
         <td colspan="{$columns.count+1}"><i>There are no invoiced Billing Reports</i></td>
      </tr>
      {/section}

      <tr class="tab1">
         <td align="right" colspan="{$columns.count}"><strong>Total:</strong></td>
         <td align="right"><strong>${$total|number_format}</td>
      </tr>
      </table>
   </td>
</tr>
</table>
