<script src='/js/validate.js'></script>
<script src='/js/tab.js'></script>
<script src='/js/pgen.js'></script>
{popup_init src="/js/overlib/overlib.js"}

{include file='quote/summary.tpl}
<br>
<div id='prop_comment_top'>&nbsp;</div>
<br>

{assign var=dvt value="Revision - <strong>`$revision.revision`</strong> <br>
         	Status - <strong>`$revision.proposal_revision_status_description`</strong> <br>
         	Created By - <strong>`$revision.created_by_name`</strong> <br>"}
         	
{include file='common/div_header.tpl'
			div_title='Revision Summary'
			div_name='revision_summary'
			div_width='70%'
			div_align='center'
			div_title_2=$dvt}

   <tr class="tab1">
      <td align="left" width="25%">
         <strong>{$lang.PROPOSAL_TYPE}</strong>
      </td>
      <td>
         {$revision.proposal_type_description}
      </td>
      <td align="left" width="25%">
         <strong>Pricing Method</strong>
      </td>
      <td>
         {$revision.pricing_type_description}
      </td>
   </tr>
   <tr class="tab">
      <td align="left">
         <strong>{$lang.PROJECT_SETUP_PERIOD}</strong>
      </td>
      <td>
         {$revision.study_setup_duration_description}
      </td>
      <td align="left">
         <strong>{$lang.FIELD_WORK_PERIOD}</strong>
      </td>
      <td>
         {$revision.study_fieldwork_duration_description}
      </td>
   </tr>
   <tr class="tab">
      <td align="left">
         <strong>{$lang.DATA_PROCESSING_PERIOD}</strong>
      </td>
      <td>
         {$revision.study_data_processing_duration_description}
      </td>
      <td align="left">
         <strong>{$lang.PROPOSAL_OPTION_TYPE}</strong>
      </td>
      <td>
         {$revision.proposal_option_type_description}
      </td>
      </tr>
   <tr>
   <tr class="tab1">
      <td align="left">
         <strong>Sample Type</strong>
      </td>
      <td align="left">
         {foreach name=prst item=sample_type from=$sample_types}
            {$sample_type.sample_type_description} <br>
         {/foreach}
      </td>
      <td>
         <strong>{$lang.NUMBER_OF_COUNTRIES}</strong>&nbsp;
         {$revision.number_of_countries}
      </td>
      <td>
         <strong>{$lang.NUMBER_OF_STUDY_OPTIONS}</strong>&nbsp;
            {$revision.number_of_options}
      </td>
   </tr>
   <tr class="tab">
      <td align="left">
         <strong>General Comment</strong>
      </td>
      <td colspan="3" style="border: 1px solid black;">
            {$comment.general_comment}
      </td>
   </tr>
   <tr class="tab">
      <td align="left">
         <strong>{$lang.QUALIFYING_CRITERIA}</strong>
      </td>
      <td colspan="3" style="border: 1px solid black;">
            {$comment.qualifying_criteria}
      </td>
   </tr>
   <tr class="tab1">
      <td align="left">
         <strong>{$lang.QUALIFYING_CRITERIA} File</strong>
      </td>
      <td colspan="3">
         {foreach name=qf item=qffile from=$qf_file}
            <a href="?action=get_revision_file&revision_file_id={$qffile.proposal_revision_file_id}">{$qffile.file_name}</a>
         {/foreach}
      </td>
   </tr>
   <tr class="tab">
      <td align="left">
         <strong>Final Deliverables</strong>
      </td>
      <td colspan="3" style="border: 1px solid black;">
            {$comment.final_deliverables}
      </td>
   </tr>
   <tr class="tab">
      <td align="left"><strong>Services Provided</strong></td>
      <td colspan="3">
         <table align="center" width="100%">
         {section name=main_service loop=$orglist}
            <tr class="header1">
               <td colspan="6"><strong>{$orglist[$smarty.section.main_service.iteration].group_description}</strong></td>
            </tr>
            {section name=sub_service loop=$orglist[$smarty.section.main_service.iteration]}
            <tr>
               {section name=service loop=$orglist[$smarty.section.main_service.iteration][$smarty.section.sub_service.iteration]}
                  <td align="left">{$orglist[$smarty.section.main_service.iteration][$smarty.section.sub_service.iteration][$smarty.section.service.iteration].service_description}</td>
               {/section}
            </tr>
            {/section}
         {/section}
         </table>

      </td>
   </tr>
</table>

{include file='common/div_footer.tpl'}

<br>
<br>

{include file=quote/vr_approval.tpl}
<br>
<br>

{include file='common/div_header.tpl'
			div_title='Dispositions'
			div_name='p_dispositions'
			div_width='70%'
			div_align='center'}

<tr>
   <td align="center">
	<input type='hidden' name='frm_confirm' id='frm_confirm' value='{$meta.popup_low_value_confirmation}'>
	{literal}
	<script language='javascript'>
	   function DisplayWarnings() {
		if (document.getElementById('frm_confirm').value) {
		   return confirm('This proposal is BELOW the minimum engagement fee required by company policy, and the account is NOT setup as contractually obligated to conduct low value projects');
	        }
                return true;
	   }
	</script>
	{/literal}
   	
   	{if $meta.display_button_revise eq "1"}
	      <input type="button" 
	             onclick="document.location.href='?e={"action=copy_revision&proposal_id=`$revision.proposal_id`&proposal_revision_id=`$revision.proposal_revision_id`"|url_encrypt}';" 
	             value="Revise Proposal">
	      &nbsp;
      {/if}
      
      {if $meta.display_button_send_client eq "1"}
	      <input type="button" 
	             onclick="if (DisplayWarnings()) {ldelim} document.location.href='?e={"action=send_to_client&proposal_id=`$revision.proposal_id`&proposal_revision_id=`$revision.proposal_revision_id`"|url_encrypt}'; {rdelim}" 
	             value="Send PDF to Client">
	      &nbsp;
	   {/if}
	   
	   {if $meta.display_button_send_client eq "1"}
	      <input type="button" 
	             onclick="if (DisplayWarnings()) {ldelim} document.location.href='?e={"action=send_to_client&proposal_id=`$revision.proposal_id`&proposal_revision_id=`$revision.proposal_revision_id`&include_xls=1"|url_encrypt}'; {rdelim}" 
	             value="Send PDF to Client With Pricing XLS">
	      &nbsp;
	   {/if}
	   
	   {if $meta.display_button_download eq "1"}
	      <input type="button" 
	             onclick="if (DisplayWarnings()) {ldelim} document.location.href='?e={"action=download_proposal_document&proposal_id=`$revision.proposal_id`&proposal_revision_id=`$revision.proposal_revision_id`"|url_encrypt}'; {rdelim}"
	              value="Download PDF">
	      &nbsp;
		{/if}
		
      {if $meta.display_button_send_approval eq "1"}
         <input type="button" 
                onclick="document.location.href='?e={"action=approval_request&proposal_id=`$revision.proposal_id`&proposal_revision_id=`$revision.proposal_revision_id`"|url_encrypt}';" 
                value="Send For Approval">
         &nbsp;
      {/if}
      
      {if $meta.display_button_won eq "1"}
         <input type="button" 
                onclick="document.location.href='?e={"action=display_proposal_won&proposal_id=`$revision.proposal_id`&proposal_revision_id=`$revision.proposal_revision_id`"|url_encrypt}';" 
                value="Proposal Won">
         &nbsp;
      {/if}   
      
       {if $display.proposal_status_review eq "1"}
         <input type="button" 
                onclick="document.location.href='?e={"action=check_account_status&proposal_id=`$revision.proposal_id`&proposal_revision_id=`$revision.proposal_revision_id`"|url_encrypt}';" 
                value="Check Account Status">
         &nbsp;
      {/if}
      
      
      {if $meta.display_button_manual_sent eq "1"}
         <input type="button" 
                onclick="if (DisplayWarnings()) {ldelim} document.location.href='?e={"action=download_proposal_document&proposal_id=`$revision.proposal_id`&proposal_revision_id=`$revision.proposal_revision_id`&manual_sent=1"|url_encrypt}'; {rdelim}"
                value="Download PDF to be Sent Manually">
         &nbsp;
      {/if}   
      
      {if $meta.display_button_download eq "1"}
         <input type="button" 
                onclick="document.location.href='?e={"action=download_pricing_summary&proposal_id=`$revision.proposal_id`&proposal_revision_id=`$revision.proposal_revision_id`&manual_sent=1"|url_encrypt}';"
                value="Download XLS Pricing Summary">
         &nbsp;
      {/if}   
   </td>
</tr>
<tr>
	<td align="center">
      {if $meta.display_currency_converter eq "1"}
      	<form method="POST">
      	<input type="hidden" name="currency_code" value="{$meta.preffered_currency}">
      	<input type="button"
                onclick="this.form.submit();"
                value='Convert To {$meta.preffered_currency}'>
      	<input type="hidden" name="convert_currency" value="1">
      	</form>
      	&nbsp;&nbsp;
      	<form method="POST">
      	<input type="hidden" name="currency_code" value="USD">
      	<input type="button"
                onclick="this.form.submit();"
                value='Convert To USD'>
      	<input type="hidden" name="convert_currency" value="0">
      	</form>
      {/if}
   </td>
</tr>

{include file='common/div_footer.tpl'}

<div align="center">
<ul id="tablist">
<li><a href="" onClick="return expandcontent('sc3', this)">Pricing</a></li>
<li><a href="" onClick="return expandcontent('sc2', this)">Options</a></li>
{if $revision.pricing_type_id eq $smarty.const.PRICING_TYPE_CUSTOM}
	<li><a href="" onClick="return expandcontent('sc4', this)">Standard Proposal </a></li>
   <li><a href="" class="current" onClick="return expandcontent('sc1', this)">Custom Proposal </a></li>
{else}
	<li><a href="" class="current" onClick="return expandcontent('sc1', this)">Standard Proposal </a></li>
{/if}
	{if $revision.panel_details neq "0"}
		<li><a href="" onClick="return expandcontent('dvpanel', this)">Panel Pricing</a></li>
	{/if}
</ul>
</div>

<DIV id="tabcontentcontainer" align="center">

<div id="sc3" class="tabcontent">
{include file='quote/vr_discount.tpl}
</div>

<div id="sc2" class="tabcontent">
{** if $revision.proposal_option_type_id eq $smarty.const.PROPOSAL_OPTION_TYPE_SINGLE **}
{if $revision.number_of_countries eq 1 && $revision.number_of_options > 1}
   {include file='app/pgen/vr_options_single.tpl'}
{else}
   {include file='quote/vr_options.tpl}
{/if}
</div>

{if $revision.panel_details neq "0"}
<div id="dvpanel" class="tabcontent">
	{include file='quote/vr_panel.tpl}
</div>
{/if}


{if $revision.pricing_type_id eq $smarty.const.PRICING_TYPE_CUSTOM}
   <div id="sc1" class="tabcontent">
      {if $revision.number_of_countries eq 1 && $revision.number_of_options > 1}
         {include file='app/pgen/vr_custom_single.tpl}
      {else}
         {include file='quote/vr_custom.tpl}
      {/if}
   </div>
   <div id="sc4" class="tabcontent">
	{if $revision.number_of_countries eq 1 && $revision.number_of_options > 1}
	   {include file='app/pgen/vr_proposal_single.tpl}
	{else}
	   {include file='quote/vr_proposal.tpl}
	{/if}
	</div>
{else}
	<div id="sc1" class="tabcontent">
	{if $revision.number_of_countries eq 1 && $revision.number_of_options > 1}
	   {include file='app/pgen/vr_proposal_single.tpl}
	{else}
	   {include file='quote/vr_proposal.tpl}
	{/if}
	</div>
{/if}
</DIV>

<br>
<br>
<div id='prop_auction_configure'>
	{include file='quote/vw_auction_setup_single.tpl'}
	{** include file='app/pgen/vw_auction_setup.tpl' **}
</div>


<br>
<br>
<div id='prop_comment_bottom'>
{include file='quote/vw_comment.tpl'}
</div>

{literal}
<script>
		new Draggable('prop_comment_top', {revert:true});
		new Draggable('prop_comment_bottom', {revert:true});
		
		Droppables.add('prop_comment_top',{
		hoverclass: 'dark_border',
	   onDrop: function(element) 
	     { 
	     		$('prop_comment_top').innerHTML = element.innerHTML;
	     		element.innerHTML = '&nbsp';
	     		}});	
	     		
		Droppables.add('prop_comment_bottom',{
		hoverclass: 'dark_border',
	   onDrop: function(element) 
	     { 
	     		$('prop_comment_bottom').innerHTML = element.innerHTML;
	     		element.innerHTML = '&nbsp';
	     		}});	
</script>
{/literal}
