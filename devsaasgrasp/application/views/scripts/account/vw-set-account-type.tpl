<script src='/js/common.js'></script>
<script src='/js/validate.js'></script>
<script src='/js/acm.js'></script>
{popup_init src="/js/overlib/overlib.js"}
{debug}
{if $meta.message neq ""}
<div align="center">
   <font color="Red"><strong>{$meta.message}</strong></font>
</div>
{/if}


<TABLE class=groupborder cellSpacing=0 width="70%" align="center">
        <TR>
          <TD class=tab>
<TABLE border='0' width='100%' cellpadding='0' cellspacing='0' style='background-color:white;'>
<TR>
   <TD align='center'>
      <table width='100%' align='center'>
      <tr>
         <td align=left class='header1'>
            <b>Specify Account Type</b>
         </TD>
         <TD class=disabled vAlign=top align=right width="75%" rowSpan=2>&nbsp;</TD>
      </TR>
      <TR>
         <TD class=tab vAlign=bottom align='left'>
            <A onmouseover="status='Show/Hide'; return true;" onmouseout="status=''; return true;" href="javascript:toggleWindow('proposal_search');">
            <IMG id=proposal_search:arrow alt=Show/Hide src="/images/rollminus.gif" border=0></A>
         </TD>
      </TR></TABLE>
   </td>
</tr>
</table>
</td></tr><tr><td>
<DIV id='proposal_search' style="display: block;">

<form name='searchform' 
      id='searchform' 
      action="/Account/savetype" 
      method="POST">

<table width="100%">
   <tr class="tab1">
      <td align="right" width="50%">
         <strong>{$lang.ACCOUNT_NAME}</strong>
      </td>
      <td align="left" id='td_account_name' name='td_account_name'>
         
         {$account.account_name}
         
      </td>
   </tr>

   <tr class="tab">
      <td align="right">
         <strong>Account Type</strong>
      </td>
      <td align="left" name='td_country' id='td_country'>
          <select name='account_type_id' id='account_type_id' onchange="UpdateAccountSubType(this);">
          	<option value="">Select Account Type</option>
            {html_options options=$list.account_type selected=$account.account_type}
         </select>
      </td>
      <input type="hidden" name="r_account_type_id" value="Account Type is Required">
   </tr>
   
    <tr class="tab1">
      <td align="right">
         <strong>Account Sub Type</strong>
      </td>
      <td align="left" name='td_country' id='td_country'>
         <select name='account_sub_type_id' id='account_sub_type_id' >
         	{html_options options=$list.account_sub_type selected=$account.account_sub_type}
         </select>
      </td>
      <input type="hidden" name="r_account_sub_type_id" value="Account Sub Type is Required">
   </tr>
   
   <tr class=tab id='tr_has_product_account' style="display: none;">
   	<td align="right"><strong>This Client Already Has An Account in a Product</td>
   	<td align="left"><input type="checkbox" name="client_has_account" id='client_has_account' onclick="ClientHasAccount(this);"></td>
   </tr>
   
   <tr class='tab1' id="tr_get_account_detail" style="display: none;">
   	<td align="right"><strong>Product Detail / ID</strong></td>
   	<td align="left">
   		<select name="product_id">
   			<option value="1">Net-MR</option>
   		</select>
   		&nbsp;&nbsp; <input type="text" name="product_account_id" id="product_account_id" size="10">
   		<input type="hidden" name="get_account_detail" value="0" id="get_account_detail">
   		<input type="hidden" name="to_rename" id="r_product_account_id"  value="Product ID is Required">
   	</td>
   </tr>
   
   <tr class="tab1">
      <td colspan="2" align="center">
      	<input type="hidden"
             	 name="account_id"
             	 value="{$account.account_id}">
         <input type="button" 
                onclick="check(this.form);" 
                value="Next ">
         &nbsp;&nbsp;
         <input type="button" 
                onclick="window.location.href = '/Account/list';" 
                value="Cancel">
      </td>
   </tr>   
</table>
</form>
</div>
</td>
</tr>
</table>
