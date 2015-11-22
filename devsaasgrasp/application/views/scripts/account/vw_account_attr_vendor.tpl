{include file='common/div_header.tpl' 
			div_title='Attributes' 
			div_name='account_attr' 
			div_width='80%' 
			div_align='center'}
			
<form method="POST"
		action="/app/Account/SaveAccountAttributes/account_id/{$account.account_id}">
{if $smarty.session.user_type_id eq "1"}			
<tr class="tab1">
	<td align="right"><strong>Require a GMI P.O. Number for BR</strong></td>
	<td>
		<input type="checkbox" 
				 name="ARMC_GMI_PO_REQUIRED" 
				 {if $account.attr.ARMC_GMI_PO_REQUIRED eq "1"}checked{/if}>
	</td>
	<td align="right"><strong>Require a Vendor P.O Number for BR</strong></td>
	<td>
		<input type="checkbox" 
				 name="ARMC_VENDOR_PO_REQUIRED"
				 {if $account.attr.ARMC_VENDOR_PO_REQUIRED eq "1"}checked{/if}>
	</td>
</tr>			

<tr class="tab">
	<td align="right"><strong>Payment Approval Reviewed By Vendor Supervisor</strong></td>
	<td>
		<input type="checkbox" 
				 name="ARMC_PAYMENT_AUTH_SUPERVISOR_REQUIRED" 
				 {if $account.attr.ARMC_PAYMENT_AUTH_SUPERVISOR_REQUIRED eq "1"}checked{/if}>
	</td>
	<td align="right"><strong>Payment Approval Reviewed By Vendor Manager</strong></td>
	<td>
		<input type="checkbox" 
				 name="ARMC_PAYMENT_AUTH_MANAGER_REQUIRED"
				 {if $account.attr.ARMC_PAYMENT_AUTH_MANAGER_REQUIRED eq "1"}checked{/if}>
	</td>
</tr>			

<tr class="tab1">
	<td align="right"><strong>Account Status</strong></td>
	<td>
		<select name="GLOBAL_ACCOUNT_STATUS_ID">
			<option value="">Select Status</option>
			{html_options options=$list.account_status selected=$account.attr.GLOBAL_ACCOUNT_STATUS_ID}
		</select>
				 
	</td>
	<td align="right"><strong>GMI Credit Balance</strong></td>
	<td>
		<input type="text" 
				 name="ARMC_CREDIT_BALANCE" 
				 size="10" 
				 value="{$account.attr.ARMC_CREDIT_BALANCE}">
	</td>
</tr>		
{/if}
<tr class="tab">
	<td align="right"><strong>Activley Managed Panel</strong></td>
	<td>
		<input type="checkbox" 
				 name="GLOBAL_MANAGED_PANEL" 
				 {if $account.attr.GLOBAL_MANAGED_PANEL eq "1"}checked{/if}>
	</td>
	<td align="right"><strong>Compliance With All Industry Research Standards</strong></td>
	<td>
		<input type="checkbox" 
				 name="GLOBAL_INDUSTRY_STANDARD_COMPLIANCE"
				 {if $account.attr.GLOBAL_INDUSTRY_STANDARD_COMPLIANCE eq "1"}checked{/if}>
	</td>
</tr>			

<tr class="tab1">
	<td align="right"><strong>Compliance with all Regional / National / Local Legislation in Regard to MR</strong></td>
	<td>
		<input type="checkbox" 
				 name="GLOBAL_LEGAL_COMPLIANCE" 
				 {if $account.attr.GLOBAL_LEGAL_COMPLIANCE eq "1"}checked{/if}>
	</td>
	<td align="right"><strong>From Initial Contact, Invites Should Be Sent In (hrs):</strong></td>
	<td>
		<select name="GLOBAL_INVITE_AVAIABILITY">
			<option value="">Select Time</option>
			{html_options options=$list.time_segment selected=$account.attr.GLOBAL_INVITE_AVAIABILITY}
		</select>
	</td> 
</tr>			

<tr class="tab">
	<td align="right"><strong>Panelists Can Be Directed to 3rd Party Site</strong></td>
	<td>
		<input type="checkbox" 
				 name="GLOBAL_REDIRECT_PANELIST_EXTERNAL" 
				 {if $account.attr.GLOBAL_REDIRECT_PANELIST_EXTERNAL eq "1"}checked{/if}>
	</td>
	<td align="right"><strong>Ability to Work With Unique URL`s</strong></td>
	<td>
		<input type="checkbox" 
				 name="GLOBAL_UNIQUE_URLS_OK"
				 {if $account.attr.GLOBAL_UNIQUE_URLS_OK eq "1"}checked{/if}>
	</td>
</tr>			

<tr class="tab1">
	<td align="right"><strong>Ability to Contact 24/7</strong></td>
	<td>
		<input type="checkbox" 
				 name="GLOBAL_247_SERVICE" 
				 {if $account.attr.GLOBAL_247_SERVICE eq "1"}checked{/if}>
	</td>
	<td align="right"><strong>Pricing Sheet Provided</strong></td>
	<td>
		<input type="checkbox" 
				 name="GLOBAL_HAVE_PRICING_SHEET"
				 {if $account.attr.GLOBAL_HAVE_PRICING_SHEET eq "1"}checked{/if}>
	</td>
</tr>		

<tr class="tab">
	<td align="right"><strong>Data Collection Capabilities</strong></td>
	<td>
		CATI
		<input type="checkbox" 
				 name="GLOBAL_DATA_COLLECTION_CATI" 
				 {if $account.attr.GLOBAL_DATA_COLLECTION_CATI eq "1"}checked{/if}>
		&nbsp;
		CAPI
		<input type="checkbox" 
				 name="GLOBAL_DATA_COLLECTION_CAPI" 
				 {if $account.attr.GLOBAL_DATA_COLLECTION_CAPI eq "1"}checked{/if}>
		&nbsp; <br />
		CAWI
		<input type="checkbox" 
				 name="GLOBAL_DATA_COLLECTION_CAWI" 
				 {if $account.attr.GLOBAL_DATA_COLLECTION_CAWI eq "1"}checked{/if}>
		&nbsp;
		Offline
		<input type="checkbox" 
				 name="GLOBAL_DATA_COLLECTION_OFFLINE" 
				 {if $account.attr.GLOBAL_DATA_COLLECTION_OFFLINE eq "1"}checked{/if}>
	</td>
	<td align="right"><strong>Real-Time De-Duplication Against Multiple Panel Sources</strong></td>
	<td>
		<input type="checkbox" 
				 name="GLOBAL_RT_DEDUPE_MULTI_SOURCE"
				 {if $account.attr.GLOBAL_RT_DEDUPE_MULTI_SOURCE eq "1"}checked{/if}>
	</td>
</tr>		

<tr class="tab1">
	<td align="right"><strong>Speciality Panel</strong></td>
	<td>
		Consumer (General)
		<input type="checkbox" 
				 name="GLOBAL_PANEL_CONSUMER_GENERAL" 
				 {if $account.attr.GLOBAL_PANEL_CONSUMER_GENERAL eq "1"}checked{/if}>
		&nbsp; <br>
		Medical
		<input type="checkbox" 
				 name="GLOBAL_PANEL_MEDICAL" 
				 {if $account.attr.GLOBAL_PANEL_MEDICAL eq "1"}checked{/if}>
		&nbsp; <br>
		B2B IT 
		<input type="checkbox" 
				 name="GLOBAL_PANEL_B2B_IT" 
				 {if $account.attr.GLOBAL_PANEL_B2B_IT eq "1"}checked{/if}>
		&nbsp; <br>
		Consumer (Medical)
		<input type="checkbox" 
				 name="GLOBAL_PANEL_CONSUMER_MEDICAL" 
				 {if $account.attr.GLOBAL_PANEL_CONSUMER_MEDICAL eq "1"}checked{/if}>
		&nbsp; <br>
		Consumer (Youth)
		<input type="checkbox" 
				 name="GLOBAL_PANEL_CONSUMER_YOUTH" 
				 {if $account.attr.GLOBAL_PANEL_CONSUMER_YOUTH eq "1"}checked{/if}>
		&nbsp; <br>
		B2B General
		<input type="checkbox" 
				 name="GLOBAL_PANEL_B2B_GENERAL" 
				 {if $account.attr.GLOBAL_PANEL_B2B_GENERAL eq "1"}checked{/if}>
	</td>
	<td align="right"><strong>Require Pass-Back Links</strong></td>
	<td>
		<input type="checkbox" 
				 name="GLOBAL_PASSBACK_LINK_REQUIRED"
				 {if $account.attr.GLOBAL_PASSBACK_LINK_REQUIRED eq "1"}checked{/if}>
	</td>
</tr>		

<tr class="tab">
	<td align="right"><strong>Launch on Weekend and Holidays</strong></td>
	<td>
		<input type="checkbox" 
				 name="GLOBAL_LAUNCH_HOLIDAY_WEEKEND" 
				 {if $account.attr.GLOBAL_LAUNCH_HOLIDAY_WEEKEND eq "1"}checked{/if}>
	</td>
	<td align="right"><strong>Willingness to Conduct Top-Ups</strong></td>
	<td>
		<input type="checkbox" 
				 name="GLOBAL_CONDUCT_TOP_UPS"
				 {if $account.attr.GLOBAL_CONDUCT_TOP_UPS eq "1"}checked{/if}>
	</td>
</tr>		


<tr class="tab1">
	<td align="center" colspan="4">
		<input type="button" value="Save" onclick="this.form.submit();">
		{if $meta.new_account eq 1}
		 <input type="button" 
                onclick="window.location.href = '?e={"action=display_list"|url_encrypt}';" 
                value="Cancel">
		 {/if}
	</td>
</tr>

</form>
			
{include file='common/div_footer.tpl'}