<script src='/js/validate.js'></script>
<script src='/js/acm.js'></script>
{popup_init src="/js/overlib/overlib.js"}

{if $meta.message neq ""}
<div align="center">
   <font color="Red"><strong>{$meta.message}</strong></font>
</div>
{/if}

{include file='account/vw_summary.tpl'}

<br><br>
<form name='searchform' id='searchform' action="/Account/savecontact" method="POST">

<table>
<tr>
<td colspan="4">
	<table>
	<tr><td colspan="5" >
			<font color="Red"><strong>	
			{if $status_code == 2 }
			 Invalid Address. Please select a recommended address shown below
			{elseif $status_code == 3	}
			Invalid Address.Please check the address values entered
			{/if}
			</strong></font>	
			</td></tr>
	<tr>	
	{section name=id loop=$recommended_addresses}
		<td>		
			<table>
				<tr>
				<td><input type="radio" name="address_group" value="1" onclick="FillContact({$smarty.section.id.index});"></td>
				</tr>
				<tr class="tab">
				<td>				
				{$recommended_addresses[id]->GetAddressStreet1()}<br>
				{$recommended_addresses[id]->GetAddressStreet2()}<br>
				{$recommended_addresses[id]->GetAddressCity()}<br>
				{$recommended_addresses[id]->GetAddressState()}<br>
				{$recommended_addresses[id]->GetAddressZip()}<br>
				{$recommended_addresses[id]->GetAddressCountryCode()}<br>
				</td>
				</tr>
				<input type="hidden" name="street_one_{$smarty.section.id.index}" id="street_one_{$smarty.section.id.index}" value="{$recommended_addresses[id]->GetAddressStreet1()}">
				<input type="hidden" name="street_two_{$smarty.section.id.index}" id="street_two_{$smarty.section.id.index}" value="{$recommended_addresses[id]->GetAddressStreet2()}">
				<input type="hidden" name="zip_code_{$smarty.section.id.index}" id="zip_code_{$smarty.section.id.index}" value="{$recommended_addresses[id]->GetAddressZip()}">
				<input type="hidden" name="state_{$smarty.section.id.index}" id="state_{$smarty.section.id.index}" value="{$recommended_addresses[id]->GetAddressState()}">
				<input type="hidden" name="city_{$smarty.section.id.index}" id="city_{$smarty.section.id.index}" value="{$recommended_addresses[id]->GetAddressCity()}">
		   </table>	
		</td>		
	{/section}	
		</tr>
	</table>
	</td>
</tr>

   <tr class="tab">
      <td align="right">
         <strong>Salutation / First Name</strong>
      </td>
      <td align="left"> 
      	{if $account.contact.contact_title == "" } 
      		{assign var="title" value=$meta.contact_title}
      	{else}
      	 	{assign var="title" value=$account.contact.contact_title}
      	 {/if}
      	 <select name="contact_title">
      	 	{html_options values=$list.title 
      	 					  output=$list.title 
      	 					  selected=$title}
      	 </select>
      	&nbsp;&nbsp;
          <input type="text" name="contact_first_name" size="20" value="{if $account.contact.contact_first_name == ''}{$meta.contact_first_name}{else}{$account.contact.contact_first_name}{/if}">
          {is_required_field name='contact_first_name' validation=$template_attr.validation}
      </td>
       <td align="right">
         <strong>Last Name</strong>
      </td>
      <td align="left">
          <input type="text" name="contact_last_name" size="20" value="{if $account.contact.contact_last_name == ''}{$meta.contact_last_name}{else}{$account.contact.contact_last_name}{/if}">
          {is_required_field name='contact_last_name' validation=$template_attr.validation}
      </td>
   </tr>
   
   <tr class="tab1">
      <td align="right">
         <strong>Email</strong>
      </td>
      <td align="left">
          <input type="text" name="contact_email" size="20" value="{if $account.contact.contact_email == ''}{$meta.contact_email}{else}{$account.contact.contact_email} {/if}">
          {is_required_field name='contact_email' validation=$template_attr.validation}
      </td>
      
      <td align="right">
         <strong>Phone</strong>
      </td>
      <td align="left">
          <input type="text" name="contact_phone_number" size="20" value="{if $account.contact.contact_phone_number == ''}{$meta.contact_phone_number}{else}{$account.contact.contact_phone_number}{/if}">
          {is_required_field name='contact_phone_number' validation=$template_attr.validation}
      </td>
   </tr>
   
   <tr class="tab1">
      <td align="right">
         <strong>Street 1</strong>
      </td>
      <td align="left">
          <input type="text" name="address_street_1" id="address_street_1" size="20" value="{if $account.contact.address_street_1 == ''}{$meta.address_street_1}{else}{$account.contact.address_street_1}{/if}" onchange="SetValidation()">
          {is_required_field name='address_street_1' validation=$template_attr.validation}
      </td>
      
      <td align="right">
         <strong>Street 2</strong>
      </td>
      <td align="left">
          <input type="text" name="address_street_2" id="address_street_2" size="20" value="{if $account.contact.address_street_2 == ''}{$meta.address_street_2}{else}{$account.contact.address_street_2}{/if}" onchange="SetValidation()">
      </td>
   </tr>
   
    
   <tr class="tab1">
      <td align="right">
         <strong>City</strong>
      </td>
      <td align="left">
          <input type="text" name="address_city" id="address_city" size="20" value="{if $account.contact.address_city == ''}{$meta.address_city}{else}{$account.contact.address_city}{/if}" onchange="SetValidation()">
          {is_required_field name='address_city' validation=$template_attr.validation}
      </td>
      
      <td align="right">
         <strong>State/Province</strong>
      </td>
      <td align="left">
          <input type="text" name="address_state" id="address_state" size="2" value="{if $account.contact.address_state == ''}{$meta.address_state}{else}{$account.contact.address_state}{/if}" onchange="SetValidation()"> <span id='span_address_state'></span>/
          <input type="text" name="address_province" size="20" value="{if $account.contact.address_province == ''}{$meta.address_province}{else}{$account.contact.address_province}{/if}"> <span id='span_address_province'></span>
      </td>
   </tr>
   
   <tr class="tab">
      <td align="right">
         <strong>Zip/Postal Code</strong>
      </td>
      <td align="left">
          <input type="text" name="address_zip" id="address_zip" size="20" value="{if $account.contact.address_zip == ''}{$meta.address_zip}{else}{$account.contact.address_zip}{/if}" onchange="SetValidation()">
          <span id='span_address_zip'></span>
      </td>
      
      <td align="right">
         <strong>Country</strong>
      </td>
      <td align="left">
      	 {if $account.contact.address_country_code == "" } 
      	 	{assign var="country_code" value=$meta.address_country_code}
      	 {else}
      	 	{assign var="country_code" value=$account.contact.address_country_code}
      	 {/if}
          <select name="address_country_code" onchange="ValidateExtra(this.value);">
          	<option value="">Select Country</option>
          	{html_options options=$list.country selected=$country_code}
          </select>
          {is_required_field name='address_country_code' validation=$template_attr.validation}
      </td>      
   </tr>
   
   <tr class="tab1">
      <td align="right">
         <strong>Fax</strong>
      </td>
      <td align="left">
          <input type="text" name="contact_fax_number" size="20" value="{if $account.contact.contact_fax_number == ''}{$meta.contact_fax_number}{else}{$account.contact.contact_fax_number}{/if}">
      </td>
      
      <td align="right">
         <strong>Contact Type</strong>
      </td>
      <td align="left">
          <select name="contact_type_id">
          	{html_options options=$list.contact_type 
          					  selected=$account.contact.contact_type_id}
          </select>
      </td>      
   </tr>
   
   <tr class="tab">
   	{if $smarty.session.user_portal_access_type_id eq "3" or $smarty.session.user_type_id eq "1"}
	      <td align="right">
	         <strong>Portal Access</strong>
	      </td>
	      <td align="left">
	      {if $account.contact.attr.PORTAL_ACCESS_TYPE_ID == "" } 
      		{assign var="PORTAL_ACCESS_TYPE_ID" value=$meta.PORTAL_ACCESS_TYPE_ID}
      	{else}
      	 	{assign var="PORTAL_ACCESS_TYPE_ID" value=$account.contact.attr.PORTAL_ACCESS_TYPE_ID}
      	 {/if}
	      	 <select name="PORTAL_ACCESS_TYPE_ID" >
	      	 	<option value="">Select Portal Access</option>
	      	 	{html_options options=$list.portal_access_type selected=$PORTAL_ACCESS_TYPE_ID}
	      	 </select>
	          <!--<input type="text" name="contact_fax_number" size="20" value="{$account.contact.contact_fax_number}">-->
	      </td>
	   {else}
	   	<td colspan="2">&nbsp;</td>
	   {/if}
      
      <td align="right">
         <strong>Portal Password</strong>
      </td>
      <td align="left">      	
          <input type="text" name="PORTAL_PASSWORD" size="20" value="{if $account.contact.attr.PORTAL_PASSWORD == ''}{$meta.PORTAL_PASSWORD}{else}{$account.contact.attr.PORTAL_PASSWORD}{/if}">
      </td>      
   </tr>  
    
   <tr class="tab1">
      <td colspan="4" align="center">
      	<input type="hidden"
             	 name="account_id"
             	 value="{$account.account_id}">
      	<input type="hidden"
             	 name="new_account"
             	 value="{$meta.new_account|default:0}">
      	<input type="hidden"
             	 name="contact_id"
             	 value="{$account.contact.contact_id|default:0}">
      	<input type="hidden"
             	 name="address_id"
             	 value="{$account.contact.address_id|default:0}">
      	<input type="hidden"
             	 name="contact_phone_id"
             	 value="{$account.contact.contact_phone_id|default:0}">
      	<input type="hidden"
             	 name="contact_fax_id"
             	 value="{$account.contact.contact_fax_id|default:0}">
         <input type="button" 
                onclick="CheckUserPassword(this.form);" 
                value="Next ">
         <input type="hidden" name="auto_submit" id="auto_submit" value="0">
         &nbsp;&nbsp;
         {if $meta.new_account eq 1 }
			<input type="button" 
                onclick="window.location.href ='?e={"action=display_account_list"|url_encrypt}';"   
                value="Cancel">
         {else}        
         <input type="button" 
                onclick="window.location.href ='?e={"action=display_account_detail&account_id=`$account.account_id`"|url_encrypt}';"   
                value="Cancel">
         {/if}
         
        
      </td>
   </tr>   
   <input type="hidden" name="validation" id="validation" value="false">
   
</form>
</table>