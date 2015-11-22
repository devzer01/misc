{assign var='dvt' value="
         		Proposal - <strong>`$proposal.proposal_id`</strong> <br>
            	Status - <strong>
            					`$proposal.proposal_status_description` -- `$proposal.proposal_sub_status_description`
            				</strong> 
            	<br>
         		Created By - <strong>`$proposal.created_by_name`</strong> <br>
         		Created Date - <strong>`$proposal.created_date`</strong> <br>"}

{include file='common/div_header.tpl'
			div_title='Summary'
			div_title_2=$dvt
			div_name='summary'
			div_width='70%'
			div_align='center'}

   <tr class="tab1">
      <td align="left">
         <strong>{$lang.ACCOUNT_TYPE}</strong>
      </td>
      <td>
         {if $proposal.account_type eq $smarty.const.ACCOUNT_TYPE_CUSTOMER}
            Client
         {else}
            Prospect
         {/if}
      </td>
      <td>
         <strong>{$lang.ACCOUNT}</strong>
      </td>
      <td>
      	{if $proposal.account_type eq "C"}
            <a href="/forms/gw-netmr-partner.php?partner_id={$proposal.account_id}" class="netmr" target="_blank">{$proposal.account_id}</a>&nbsp;
			{else}
				{$proposal.account_id}
         {/if}
         - &nbsp;&nbsp;<a href="/app/acm/?action=display_account_detail&account_id={$proposal.account_id}">{$proposal.account_name}</a>
      </td>
   </td>
   </tr>
   <tr class="tab">
      <td align="left">
         <strong>{$lang.PROPOSAL_NAME}</strong>
      </td>
      <td align="left">
         {$proposal.proposal_name}
      </td>
      <td align="left">
         <strong>{$lang.CONTACT_NAME}</strong>
      </td>
      <td align="left">
         <a href="mailto:{$proposal.c_email_address}">{$proposal.c_first_name} {$proposal.c_last_name}</a>
      </td>
   </tr>
   <tr class="tab1">
      <td align="left">
         <strong>{$lang.ACCOUNT_EXECUTIVE}</strong>
      </td>
      <td align="left" name='td_ae' id='td_ae'>
         {$proposal.ae_name}
      </td>
      <td align="left">
         <strong>{$lang.ACCOUNT_MANAGER}</strong>
      </td>
      <td align="left" name='td_am' id='td_am'>
         {$proposal.am_name}
      </td>
   </tr>
   <tr class="tab">
      <td align="left">
         <strong>{$lang.REGION}</strong>
      </td>
      <td align="left">
         {$proposal.region_description}
      </td>
      <td align="left">
         <strong>{$lang.COUNTRY}</strong>
      </td>
      <td align="left" name='td_country' id='td_country'>
          {$proposal.country_description}
      </td>
   </tr>
   <tr class="tab1">
       <td align="left">
         <strong>{$lang.PRICING_REGIME}</strong>
      </td>
      <td align="left">
         {$proposal.pricing_regime_description}
      </td>
      <td align="left">
         <strong>License Level</strong>
      </td>
      <td align="left">
          {$proposal.license_level_description}
      </td>
   </tr>
   
{include file='common/div_footer.tpl'}