{literal}
<script>
	function ConfigureAuction()
	{
		document.getElementById('frm_auction').submit();
	}
</script>
{/literal}

{include file='common/div_header.tpl' 
		   div_name='auction'
		   div_title='Configure Auction'
			div_width='70%'}
<form id='frm_auction' name='frm_auction' method="POST" action="?action=configure_auction_list">
{section name="options" 
         loop=$revision.number_of_options}
         
   {assign var="c_options" 
           value=$options[$smarty.section.options.iteration]}

<tr class="header1">
   <td align="left">
   	&nbsp;
   </td>
   {section name="countries" 
            loop=$revision.number_of_countries}
      <td align="center">
         {$c_options[$smarty.section.countries.iteration].country_description}
         {if $revision.proposal_option_type_id eq $smarty.const.PROPOSAL_OPTION_TYPE_SINGLE_SUB}
         	- {$c_options[$smarty.section.countries.iteration].sub_group_description}
         {/if}
      </td>
   {/section}
</tr>

<tr>
   <td><strong>Option {$smarty.section.options.iteration}</strong></td>
   {section name="countries" 
            loop=$revision.number_of_countries}
            {assign var=proposal_revision_option_id value=$c_options[$smarty.section.countries.iteration].proposal_revision_option_id}
      <td class="tab" align="center">
         <input type="checkbox" name="proposal_revision_option_id_{$proposal_revision_option_id}">
         {foreach from=$auctions[$proposal_revision_option_id] item=auction key=proposal_auction_id name=fe_auction_list}
         
	         {capture name='info'}
	         	<strong>Auction: </strong>{$auction.auction_name}<br/>
	         	<strong>Low Bid:</strong>{$auction.current_bid}<br/>
	         	<strong>Low Bid Vendor:</strong>{$auction.cb_account_name}<br/>
	         	<strong>Status:</strong>{$auction.proposal_auction_status_description}<br/>
	         {/capture}
         
         	<a {popup text="`$smarty.capture.info`" delay="300"} href="?action=display_auction_detail&proposal_revision_id={$revision.proposal_revision_id}&proposal_auction_id={$auction.proposal_auction_id}&proposal_id={$proposal.proposal_id}">#{$proposal_auction_id}</a>&nbsp;&nbsp;
         {/foreach}
         
      </td>
   {/section}
</tr>

{/section}

<tr>
	<td colspan="{$revision.number_of_countries}" align="center">
		<input type="button" value="Configure Auction" onclick="ConfigureAuction();">
		<input type="hidden" name="number_of_options" value="{$revision.number_of_options}">
		<input type="hidden" name="number_of_countries" value="{$revision.number_of_countries}">
		<input type="hidden" name="proposal_revision_id" value="{$revision.proposal_revision_id}">
		<input type="hidden" name="proposal_id" value="{$revision.proposal_id}">
		<input type="hidden" name="update_options" value="{$meta.update_options}">
	</td>
	<td>&nbsp;</td>
</tr>
</form>
{include file='common/div_footer.tpl'}

