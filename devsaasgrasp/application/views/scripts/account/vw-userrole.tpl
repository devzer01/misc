<script src="/js/scriptaculous/prototype.js" type="text/javascript"></script>
<script src="/js/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<script src='/js/validate.js'></script>
<script src='/js/acm.js'></script>

{popup_init src="/js/overlib/overlib.js"}

{if $meta.message neq ""}
<div align="center">
   <font color="Red"><strong>{$meta.message}</strong></font>
</div>
{/if}

<form name='searchform' 
      id='searchform' 
      action="/Account/saveuser/id/{$account.account_id}" 
      method="POST">


{**include file='common/div_header.tpl'
		   div_name='account_staff'
		   div_title='Account Staff (Internal)'
			div_width='40%'**}
<table>
<tbody id='tb_user_role'>
	<tr class="tab1">
      <td align="center" colspan="3">
         <strong>{$lang.ACCOUNT_NAME}</strong> : {$account.account_name}         
      </td>      
   </tr>
	<tr class="header1">
   	<td>Name</td>
   	<td>Product</td>
   	<td>Role</td>
   </tr>   
   
   {section name=au loop=$account.user}
   <tr class="{cycle values='tab,tab1'}">
	      {assign var=login value="`$account.user[au].user_id`"}
	      <td align="left">
	      	<input autocomplete="off" id="name_{$smarty.section.au.iteration}" name="name_{$smarty.section.au.iteration}" size="30" type="text" value="{$list.user[$login]}" />
	      	<span name='loader_{$smarty.section.au.iteration}' id='loader_{$smarty.section.au.iteration}' style="display: none;">
	      		<img src="/images/ajax/indicator_arrows.gif">
	      	</span>
	      	<div class="autocomplete" id="user_list_{$smarty.section.au.iteration}"></div>
	      		<script type="text/javascript">
	      			new Ajax.Autocompleter('name_{$smarty.section.au.iteration}', 
	      				'user_list_{$smarty.section.au.iteration}', 
	      				'/Account/userlookup/rowid/{$smarty.section.au.iteration}', 
	      				{ldelim}
	      					afterUpdateElement: SetLoginAfterUpdate, 
	      					minChars: 3,
	      					indicator: 'loader_{$smarty.section.au.iteration}'
	      				{rdelim}
	      				);
	      		</script>
	      		<br/>
	      	<input type="hidden" name="login_{$smarty.section.au.iteration}" id='login_{$smarty.section.au.iteration}' value="{$account.user[au].user_id}">
	      </td>
	      <td>
	      	<select name="product_id_{$smarty.section.au.iteration}" id='product_id_{$smarty.section.au.iteration}'>
	      		{html_options options=$list.product selected=$account.user[au].product_id}
	      	</select>
	      </td>
	      <td align="left">
	      	<select name="role_id_{$smarty.section.au.iteration}" id='role_id_{$smarty.section.au.iteration}'>
	      		{html_options options=$list.role selected=$account.user[au].role_id}
	      	</select>
	      </td>
	      <input type="hidden"
                name="account_user_id_{$smarty.section.au.iteration}"
                value="{$account.user[au].account_user_id}">
	   </tr>
   {/section}
   
   {section name=id loop=2 start=$smarty.section.au.iteration}
	   <tr class="{cycle values='tab,tab1'}">
	      <td align="left">
	      	<input autocomplete="off" id="name_{$smarty.section.id.index}" name="name_{$smarty.section.id.index}" size="30" type="text" value="" />
	      	<span name='loader_{$smarty.section.id.index}' id='loader_{$smarty.section.id.index}' style="display: none;">
	      		<img src="/images/ajax/indicator_arrows.gif">
	      	</span>
	      	<div class="autocomplete" id="user_list_{$smarty.section.id.index}"></div>
	      		<script type="text/javascript">
	      			new Ajax.Autocompleter('name_{$smarty.section.id.index}', 
	      				'user_list_{$smarty.section.id.index}', 
	      				'/Account/userlookup/rowid/{$smarty.section.id.index}', 
	      				{ldelim}
	      					afterUpdateElement: SetLoginAfterUpdate, 
	      					minChars: 3,
	      					indicator: 'loader_{$smarty.section.id.index}'
	      				{rdelim}
	      				);
	      		</script>
	      		<br/>
	      	<input type="hidden" name="login_{$smarty.section.id.index}" id='login_{$smarty.section.id.index}'>
	      </td>
	      <td>
	      	<select name="product_id_{$smarty.section.id.index}" id='product_id_{$smarty.section.id.index}'>
	      		{html_options options=$list.product}
	      	</select>
	      </td>
	      <td align="left">
	      	<select name="role_id_{$smarty.section.id.index}" id='role_id_{$smarty.section.id.index}'>
	      		{html_options options=$list.role}
	      	</select>
	      </td>
	   </tr>
   {/section}
   
    
   <tr class="tab1" id='tr_footer'>
      <td colspan="3" align="center">
      	<input type="button" 
                onclick="AddUserRoleRow();" 
                value="AddRow ">
      	&nbsp;&nbsp;
      	<input type="hidden"
             	 name="account_id"
             	 value="{$account.account_id}">
         <input type="button" 
                onclick="check(this.form);" 
                value="Next ">
         &nbsp;&nbsp;             
       
         {if $account.account_status eq "I"}
          <input type="button" 
                onclick="window.location.href = '/Account/list';"  
                value="Cancel">
<input type="hidden" name="new_account" value="1">
{else}
 <input type="button" 
                onclick="window.location.href ='/Account/view/id/{$account.account_id}';"  
                value="Cancel">
<input type="hidden" name="new_account" value="0">
{/if}
       <!--  <input type="hidden"
                name="new_account"
                value="{$account.new_account}">-->
      </td>
   </tr>   
</tbody>
   {**include file='common/div_footer.tpl'**}
 </table>  
</form>


