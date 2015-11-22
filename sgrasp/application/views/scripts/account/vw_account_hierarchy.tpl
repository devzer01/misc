{include file='common/div_header.tpl' 
			div_title='Related Accounts' 
			div_name='related_accounts' 
			div_width='80%' 
			div_align='center'}
			
<tr class="header1">
	<td align="left">Account ID</td>
	<td align="left">Account Name</td>
	<td align="left">Type</td>
	<td align="left">Country</td>
	<td align="left">Assigned By</td>
	<td align="left">Assigned Date</td>
	{if $related_account_flag eq 1 }
	<td align="left">Delete</td>
	{/if}
</tr>
{foreach from=$acc_child item=acc key=account_id}
<tr class="{cycle values='tab,tab1'}">
	<td align="left">
	{if $acc.depth neq 1}
	   <img src="../../images/trans.gif" height="12" width="{math equation="(x - y) * z " x=$acc.depth y=2 z=16 }"><img src="../../images/corner-dots.gif">  
	{/if}
	<a href="?action=display_account_detail&account_id={$acc.account.child_account_id}">{$acc.account.child_account_id}</a>
	</td>
	<td align="left"><a href="?action=display_account_detail&account_id={$acc.account.child_account_id}">
	{$acc.account.child_account_name}</a>
	</td>
	<td align="left">{$acc.account.account_hierarchy_type_description}</td>
	<td align="center">{$acc.account.country_description}</td>
	<td align="left">{$acc.account.assigned_by}</td>
	<td align="left">{$acc.account.created_date}</td>
	{if $related_account_flag eq 1 }
	<td><a href="?action=delete_related_account&main_id={$account.parent_acc_id}&account_id={$acc.account.child_account_id}&parent_id={$acc.account.parent_account_id}
	">Delete</a></td>
	{/if}
</tr>
{foreachelse}
<tr class="tab">
	<td>No Related Accounts Found.</td>
</tr>
{/foreach}

{if $related_account_flag eq 1 }
<tr class="tab">
	<td colspan="7" align="center">
	<input type="button" value="Add Related Account" onclick="javascript:HideShow('add');" id="btaddrelated">
	<form method="post" action="?action=add_related_childaccount" name="add_childaccount" id="add_childaccount" style="display:none;">
		Account No.
	   <input id='account'  
       type='text'  
       value="{$meta.account_id|default:'Type Account Name or ID'}"  
       name='account'  
       size='20'  
       onkeyup="TimeOutCaller('HBJAXCall', 'lookup_account', this.value, 'account_id', 'DisplayListValues', 'account_id', 'account_name', 'account');" 
                 onclick="ClearValue(this, 'Type Account Name or ID')">
	
	   <span id='span_account'>
      <select name="account_id" id="account_id">
   	<option value=""></option>
	   </select>
		
	   <input type="hidden" name="r_account_id" value="Child account required" id="r_account_id">&nbsp;&nbsp;
	   Hierarchy Type :
	   <select name="account_hierarchy_type" id="account_hierarchy_type">
	   	{html_options values=$hierarchy_types.id output=$hierarchy_types.description}
	   </select>
   	</span>
   	<input type="hidden" name="parent_acc_id" id="parent_acc_id" value="{$account.parent_acc_id}">
   	<input type="submit" value="Add Child Account"> &nbsp; <input type="button" onclick="javascript:HideShow('cancel');" value="Cancel">
	</form>
	</td>
</tr>
{/if}
{include file='common/div_footer.tpl'}