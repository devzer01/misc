<!--<script language="javascript">

	var ACM_TIER_1_SETUP_ALLOWED = {$smarty.session.ACM_TIER_1_SETUP_ALLOWED|default:0};

	{literal}
	function CheckPermission(perm, value, message, obj)
	{
		if (eval(perm) == 0 && obj.value == value) {
			alert(message);
			obj.value = document.getElementById('OLD__' + obj.name).value
		}
	}
	{/literal}
</script>-->

{**include file='common/div_header.tpl' 
			div_title='Attributes' 
			div_name='account_attr' 
			div_width='80%' 
			div_align='center'**}
<table>			
<form method="POST"        
		action="/Account/SaveAccountAttributes/id/{$account.account_id}" id="frm_attr" name="frm_attr">
			
<tr class="tab1">
	<td align="right"><strong>Require a P.O. Number for BR</strong></td>
	<td>
      {if $security.ACM_CHANGE_AMRC_ACCT_APPROVAL eq "1"}
      <input type="checkbox"
             name="ARMC_PO_REQUIRED" {if $account.attr.ARMC_PO_REQUIRED eq "1"}checked{/if}>
      {else}
      <input type="checkbox"
             name="ARMC_PO_REQUIRED" disabled {if $account.attr.ARMC_PO_REQUIRED eq "1"}checked{/if}>
      <input type="hidden" name="ARMC_PO_REQUIRED" {if $account.attr.ARMC_PO_REQUIRED eq "1"}value="1" {else}
             value="0"{/if}>
      {/if}
	</td>
	<td align="right"><strong>Require a Client Job Number</strong></td>
	<td>
      {if $security.ACM_CHANGE_AMRC_ACCT_APPROVAL eq "1"}
      <input type="checkbox"
             name="ARMC_JOB_REQUIRED" {if $account.attr.ARMC_JOB_REQUIRED eq "1"}checked{/if}>
      {else}
      <input type="checkbox"
             name="ARMC_JOB_REQUIRED" disabled {if $account.attr.ARMC_JOB_REQUIRED eq "1"}checked{/if}>
      <input type="hidden" name="ARMC_JOB_REQUIRED" {if $account.attr.ARMC_JOB_REQUIRED eq "1"}value="1" {else}
             value="0"{/if}>
      {/if}
	</td>
</tr>			

<tr class="tab">
	<td align="right"><strong>Require a Project Manager Name</strong></td>
	<td>
      {if $security.ACM_CHANGE_AMRC_ACCT_APPROVAL eq "1"}
      <input type="checkbox"
             name="ARMC_PM_REQUIRED" {if $account.attr.ARMC_PM_REQUIRED eq "1"}checked{/if}>
      {else}
      <input type="checkbox"
             name="ARMC_PM_REQUIRED" disabled {if $account.attr.ARMC_PM_REQUIRED eq "1"}checked{/if}>
      <input type="hidden" name="ARMC_PM_REQUIRED" {if $account.attr.ARMC_PM_REQUIRED eq "1"}value="1" {else}
             value="0"{/if}>
      {/if}
	</td>
	<td align="right"><strong>Can Send Satisfaction Survey</strong></td>
	<td>
	{if $security.ACM_CAN_SET_SAT_SURVEY_SEND_FLAG eq "1"}
		<input type="checkbox" 
				 name="STM_SEND_SAT_SURVEY"  {if $account.attr.STM_SEND_SAT_SURVEY eq "1"}checked{/if}>	
	{else}
	<input type="checkbox" 
				 name="STM_SEND_SAT_SURVEY" disabled {if $account.attr.STM_SEND_SAT_SURVEY eq "1"}checked{/if}>
	<input type="hidden" name="STM_SEND_SAT_SURVEY" {if $account.attr.STM_SEND_SAT_SURVEY eq "1"}value="1" {else}
				 	value="0"{/if}>
	{/if}		
	</td>
</tr>			

<tr class="tab1">
	<td align="right"><strong>Oracle Account Number</strong></td>
	<td>
		<input type="hidden" 
				 name="ARMC_ORA_ACCOUNT_ID"
				 value="{$account.attr.ARMC_ORA_ACCOUNT_ID}">
            {$account.attr.ARMC_ORA_ACCOUNT_ID}
	</td>
	<td align="right"><strong>Contractually bound to create low value proposals</strong></td>
	<td>
	{if $security.ACM_CHANGE_PGEN_BOUND_LOW_PROPOSAL eq "1"}
		<input type="checkbox" 
				 name="PGEN_BOUND_LOW_PROPOSAL"  {if $account.attr.PGEN_BOUND_LOW_PROPOSAL eq "1"}checked{/if}>	
	{else}
	<input type="checkbox" 
				 name="PGEN_BOUND_LOW_PROPOSAL" disabled {if $account.attr.PGEN_BOUND_LOW_PROPOSAL eq "1"}checked{/if}>
	<input type="hidden" name="PGEN_BOUND_LOW_PROPOSAL" {if $account.attr.PGEN_BOUND_LOW_PROPOSAL eq "1"}value="1" {else}
				 	value="0"{/if}>
	{/if}	
	</td>
</tr>			

<tr class="tab">
	<td align="right"><strong>BR Requires AE Approval</strong></td>
	<td>
	{if $security.ACM_CHANGE_AMRC_AE_APPROVAL eq "1"}
		<input type="checkbox" 
				 name="AMRC_AE_APPROVAL_REQUIRED"  {if $account.attr.AMRC_AE_APPROVAL_REQUIRED eq "1"}checked{/if}>	
	{else}
	<input type="checkbox" 
				 name="AMRC_AE_APPROVAL_REQUIRED" disabled {if $account.attr.AMRC_AE_APPROVAL_REQUIRED eq "1"}checked{/if}>
	<input type="hidden" name="AMRC_AE_APPROVAL_REQUIRED" {if $account.attr.AMRC_AE_APPROVAL_REQUIRED eq "1"}value="1" {else}
				 	value="0"{/if}>
	{/if}	
	</td>
        <td align="right"><strong>BR Requires ACCT Approval</strong></td>
        <td>
                {if $security.ACM_CHANGE_AMRC_ACCT_APPROVAL eq "1"}
                <input type="checkbox"
                        name="AMRC_ACCT_APPROVAL_REQUIRED"  {if $account.attr.AMRC_ACCT_APPROVAL_REQUIRED eq "1"}checked{/if}>
                {else}
                <input type="checkbox"
                        name="AMRC_ACCT_APPROVAL_REQUIRED" disabled {if $account.attr.AMRC_ACCT_APPROVAL_REQUIRED eq "1"}checked{/if}>
                <input type="hidden" name="AMRC_ACCT_APPROVAL_REQUIRED" {if $account.attr.AMRC_ACCT_APPROVAL_REQUIRED eq "1"}value="1" {else}
                        value="0"{/if}>
        {/if}
        </td>
</tr>		

<tr class="tab1">
	<td align="right"><strong>Preferred Invoice Method</strong></td>
	<td>
      {if $security.ACM_CHANGE_AMRC_ACCT_APPROVAL eq "1"}
      Print <input type="checkbox"
             name="ARMC_PRINT_INVOICE"  {if $account.attr.ARMC_PRINT_INVOICE eq "1"}checked{/if}>
      &nbsp;&nbsp;
      Email <input type="checkbox"
             name="ARMC_EMAIL_INVOICE"  {if $account.attr.ARMC_EMAIL_INVOICE eq "1"}checked{/if}>
      &nbsp;&nbsp;
      Fax <input type="checkbox"
             name="ARMC_FAX_INVOICE"  {if $account.attr.ARMC_FAX_INVOICE eq "1"}checked{/if}>
      {else}
      Print <input type="checkbox"
             name="ARMC_PRINT_INVOICE" disabled {if $account.attr.ARMC_PRINT_INVOICE eq "1"}checked{/if}>
      <input type="hidden" name="ARMC_PRINT_INVOICE" {if $account.attr.ARMC_PRINT_INVOICE eq "1"}value="1" {else}
             value="0"{/if}>
      &nbsp;&nbsp;
      Email <input type="checkbox"
             name="ARMC_EMAIL_INVOICE" disabled {if $account.attr.ARMC_EMAIL_INVOICE eq "1"}checked{/if}>
      <input type="hidden" name="ARMC_EMAIL_INVOICE" {if $account.attr.ARMC_EMAIL_INVOICE eq "1"}value="1" {else}
             value="0"{/if}>
      &nbsp;&nbsp;
      Fax <input type="checkbox"
             name="ARMC_FAX_INVOICE" disabled {if $account.attr.ARMC_FAX_INVOICE eq "1"}checked{/if}>
      <input type="hidden" name="ARMC_FAX_INVOICE" {if $account.attr.ARMC_FAX_INVOICE eq "1"}value="1" {else}
             value="0"{/if}>
      {/if}
	</td>
	<td align="right"><strong>Alternative Billing Company Name</strong></td>
	<td>
   {assign var="disabled" value=" disabled=\"disabled\""}
   {if $security.ACM_CHANGE_AMRC_ACCT_APPROVAL eq "1"}
   {assign var="disabled" value=""}
   {/if}
	<input type="text" 
				  name="ARMC_ALTERNATE_MAIL_NAME" 
				  size="20" 
				  value="{$account.attr.ARMC_ALTERNATE_MAIL_NAME}"{$disabled}></td>
</tr>		

<tr class="tab">
	<td align="right"><strong>Retainer Balance on Account</strong></td>
	<td>
		<input type="hidden" 
				 name="ARMC_RETAINER_VALUE" 
				 size="10" 
				 value="{$account.attr.ARMC_RETAINER_VALUE}">
             {$account.attr.ARMC_RETAINER_VALUE}
	</td>
	<td align="right"><strong>URL</strong></td>
	<td>
		<input type="text" 
				 name="GLOBAL_COMPANY_URL" 
				 size="30" 
				 value="{$account.attr.GLOBAL_COMPANY_URL}">
	</td>
</tr>		

<tr class="tab1">
	<td align="right"><strong>Study Handled By AM</strong></td>
	<td>
		<input type="checkbox" 
				 name="GLOBAL_STUDY_HANDLED_BY_AM" 
				 {if $account.attr.GLOBAL_STUDY_HANDLED_BY_AM eq "1"}checked{/if}>
	</td>
	<td align="right"><strong>Account Tier</strong></td>
	<td>
		<input type="text" 
				 name="GLOBAL_ACCOUNT_TIER_LEVEL" 
				 size="3"				
				 value="{$account.attr.GLOBAL_ACCOUNT_TIER_LEVEL|default:0}">
		<input type="hidden" id="OLD__GLOBAL_ACCOUNT_TIER_LEVEL" value="{$account.attr.GLOBAL_ACCOUNT_TIER_LEVEL|default:0}">
	</td>
</tr>		

<tr class="tab">
	<td align="right"><strong>Preferred Currency</strong></td>
	<td>
      {assign var="disabled" value=" disabled=\"disabled\""}
      {if $display.preferred_currency eq "1"}
      {assign var="disabled" value=""}
      {/if}
		<select name="GLOBAL_PREFFERED_CURRENCY"{$disabled}>
			<option value="">Select Currency</option>
			{html_options options=$list.currency selected=$account.attr.GLOBAL_PREFFERED_CURRENCY}
		</select>
				 
	</td>
        <td align="right"><strong>Month End Billing</strong></td>
        <td>
           {if $security.ACM_CHANGE_AMRC_ACCT_APPROVAL eq "1"}
           <input type="checkbox"
                  name="ARMC_MONTH_END_BILLING"  {if $account.attr.ARMC_MONTH_END_BILLING eq "1"}checked{/if}>
           {else}
           <input type="checkbox"
                  name="ARMC_MONTH_END_BILLING" disabled {if $account.attr.ARMC_MONTH_END_BILLING eq "1"}checked{/if}>
           <input type="hidden" name="ARMC_MONTH_END_BILLING" {if $account.attr.ARMC_MONTH_END_BILLING eq "1"}value="1" {else}
                  value="0"{/if}>
           {/if}
        </td>
</tr>		
<!--start of lead create section-->
<tr class="tab1">
 <td align="right"><strong>Lead Create Date</strong></td>
 <td>
 	{if $security.ACM_CAN_SET_LEAD_CREATION_DATA eq 1}
 		<input type='text' name='GLOBAL_ACM_LEAD_CREATE_DATE' size='10' value="{$account.attr.GLOBAL_ACM_LEAD_CREATE_DATE}" >
 		<a href="javascript:void(0);" onclick="if(self.gfPop)gfPop.fPopCalendar(document.frm_attr.GLOBAL_ACM_LEAD_CREATE_DATE);return false;" HIDEFOCUS >
      <img name="popcal" align="absmiddle" src="/js/calendar2/calbtn.gif" width="34" height="22" border="0" alt=""  > </a>
 	{else}
 		<input type='text' name='GLOBAL_ACM_LEAD_CREATE_DATE' size='10' value="{$account.attr.GLOBAL_ACM_LEAD_CREATE_DATE}" disabled >
 		<input type="hidden" name="GLOBAL_ACM_LEAD_CREATE_DATE" value="{$account.attr.GLOBAL_ACM_LEAD_CREATE_DATE}" > 	
 	{/if} 	 
 </td>
 <td align="right"><strong>Marketing Campaign ID</strong></td>
 <td>
 {if $security.ACM_CAN_SET_LEAD_CREATION_DATA eq 1}
 	<input type='text' name='GLOBAL_ACM_CAMPAIGN_ID' size='10' value="{$account.attr.GLOBAL_ACM_CAMPAIGN_ID}" ></td>
 {else}
 	<input type='text' name='GLOBAL_ACM_CAMPAIGN_ID' size='10' value="{$account.attr.GLOBAL_ACM_CAMPAIGN_ID}" disabled ></td>
 	<input type="hidden" name="GLOBAL_ACM_CAMPAIGN_ID" value="{$account.attr.GLOBAL_ACM_CAMPAIGN_ID}" > 	
 {/if}
</tr>
<!--end of lead create section-->

{if $meta.customer_type eq 1 }
<tr class="tab1">
	<td align="left" colspan="4"><strong>Accounting Attributes :</strong> </td>
</tr>
<tr class="tab">
	<td align="right"><strong>Accounting Approval Required for Oracle Update</strong></td>
	<td>
	{if $security.ACM_CAN_SET_ORA_UPDATE eq "1"}
		<input type="checkbox" 
				 name="ORA_UPDATE_ACCT_APPROVAL_REQUIRED"  {if $account.attr.ORA_UPDATE_ACCT_APPROVAL_REQUIRED eq "1"}checked{/if}>	
	{else}
	<input type="checkbox" 
				 name="ORA_UPDATE_ACCT_APPROVAL_REQUIRED" disabled {if $account.attr.ORA_UPDATE_ACCT_APPROVAL_REQUIRED eq "1"}checked{/if}>
	<input type="hidden" name="ORA_UPDATE_ACCT_APPROVAL_REQUIRED" {if $account.attr.ORA_UPDATE_ACCT_APPROVAL_REQUIRED eq "1"}value="1" {else}
				 	value="0"{/if}>
	{/if}					 
	</td>
   <td align="right"><strong>Accounting Approved For Oracle Update</strong></td>
   <td>
   {if $security.ACM_CAN_SET_ORA_UPDATE eq "1"}
		<input type="checkbox" 
				 name="ORA_UPDATE_ACCT_APPROVED"  {if $account.attr.ORA_UPDATE_ACCT_APPROVED eq "1"}checked{/if}>	
	{else}
	<input type="checkbox" 
				 name="ORA_UPDATE_ACCT_APPROVED" disabled {if $account.attr.ORA_UPDATE_ACCT_APPROVED eq "1"}checked{/if}>
	<input type="hidden" name="ORA_UPDATE_ACCT_APPROVED" {if $account.attr.ORA_UPDATE_ACCT_APPROVED eq "1"}value="1" {else}
				 	value="0"{/if}>
	{/if}		

        </td>
</tr>

<tr class="tab1">
	<td align="right"><strong>Tax Code</strong></td>
	<td>
      {assign var="disabled" value=" disabled=\"disabled\""}
      {if $security.ACM_CHANGE_AMRC_ACCT_APPROVAL eq "1"}
      {assign var="disabled" value=""}
      {/if}
		<select name="ARMC_ORA_TAX_CODE"{$disabled}>
		{if $list.tax_code_num gt 1}
			<option value="">--Please select the tax code--</option>
		{/if}
			{html_options options=$list.tax_code selected=$account.attr.ARMC_ORA_TAX_CODE}
		</select>
				 
	</td>
   <td align="right"><strong>Payment Term</strong></td>
   <td>
      {assign var="disabled" value=" disabled=\"disabled\""}
      {if $security.ACM_CHANGE_AMRC_ACCT_APPROVAL eq "1"}
      {assign var="disabled" value=""}
      {/if}
      <select name="ARMC_ORA_PAYMENT_TERM"{$disabled}>
			<option value="">--Please select the payment terms--</option>
			{html_options options=$list.payment_terms selected=$account.attr.ARMC_ORA_PAYMENT_TERM}
		</select>

        </td>
</tr>		

{/if}
<tr class="tab">
	<td align="center" colspan="4">
		<input type="button" value="Save" onclick="this.form.submit();">
		{if $meta.new_account eq 0 && $meta.customer_type eq 1 }
		<input type="button" value="Update Oracle Customer" onclick="javascript:window.location='?action=update_oracle_customer&account_id={$account.account_id}'"> &nbsp;
		{/if}	
		{if $meta.new_account eq 1}
		 <input type="button" 
                onclick="window.location.href = '/Account/list';" 
                value="Cancel">
		 {/if}
	</td>
</tr>
</form>
</table>			
{**include file='common/div_footer.tpl'**}
