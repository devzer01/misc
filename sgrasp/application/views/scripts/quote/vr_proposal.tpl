{include file='common/div_header.tpl'
			div_title='Standard Pricing'
			div_name='new_proposal'
			div_width='80%'
			div_align='center'}
			
<tr><td>

<form name='searchform' 
      id='searchform' 
      action="?e={"action=finalize_proposal&proposal_id=`$revision.proposal_id`&proposal_revision_id=`$revision.proposal_revision_id`"|url_encrypt}" 
      method="POST" 
      enctype="multipart/form-data">

<!-- Proposal ID {$revision.proposal_id} Revision {$revision.proposal_revision_id} -->
{assign var=pricing_item_group_id value=0}
{section name="options" 
         loop=$revision.number_of_options}

         <table width="100%">
         
            <tr>
               <td align="left"><strong>Option {$smarty.section.options.iteration}</strong></td>
               <td colspan="{$revision.number_of_countries}">&nbsp;</td>
               <td>&nbsp;</td>
            </tr>
         
            <tr class="header1">
               <td align="center">Pricing Item</td>
               {section name="countries"
               			loop=$revision.number_of_countries}   
                     <td align="center">
                        <strong>{$country[$smarty.section.options.iteration][$smarty.section.countries.iteration]}</strong>
                        {if $meta.display_txt_sub_group eq "1"}
                        	<br>{$p_subgroup[$smarty.section.options.iteration][$smarty.section.countries.iteration]}
                        {/if}
                     </td>
					{/section}
                  
               <td align="center"><strong>Total</strong></td>
            </tr>
   
            {foreach name=proposal_budget_1 
                     item=pb 
                     from=$p_options[$smarty.section.options.iteration]}
      
                     {if $pb[0].pricing_item_group_id neq $pricing_item_group_id}

                        <tr class="header1">
                           <td align="left">
                              {$pb[0].pricing_item_group_description}
                           </td>
                           <td colspan="{$revision.number_of_countries}">&nbsp;</td>
                           <td>&nbsp;</td>
                        </tr>
         
                        {assign var=pricing_item_group_id 
                                value=$pb[0].pricing_item_group_id}
      
                     {/if}
                     
                     {if $pb[0].proposal_budget_line_item_description neq ""}
         
                     <tr class="{cycle values="tab,tab1"}">
                     
                        <td align="left">
                           {if $pb[0].value_type eq "T"}
                              <strong>{$pb[0].proposal_budget_line_item_description}</strong>
                           {else}   
                              {$pb[0].proposal_budget_line_item_description}
                           {/if}   
                        </td>
                        
                        {assign var=sort_order 
                                value=$pb[0].sort_order}
                                
                        {section name="countries" 
                                 loop=$revision.number_of_countries}
                                 
                        <td align="right">
               
                           {if $pb[countries].value_type eq "C"}
                              &#{$meta.currency_unicode};{$pb[countries].amount|number_format:$pb[countries].precision:".":","}
                           {elseif $pb[countries].value_type eq "T"}   
                              <strong>&#{$meta.currency_unicode};{$pb[countries].amount|number_format:$pb[countries].precision:".":","}</strong>
                           {else}
                              {$pb[countries].amount|number_format:$pb[countries].precision:".":","}
                           {/if}
                        </td>
                     {/section}
                     <td align="right">
                        {if $pb[0].value_type eq "C"}
                           -
                           {** $total[$smarty.section.options.iteration][$sort_order]|number_format:3:".":"," **}
                        {elseif $pb[0].value_type eq "T"}
                           <strong>&#{$meta.currency_unicode};{$total[$smarty.section.options.iteration][$sort_order]|number_format:$pb[0].precision:".":","}</strong>
                        {else}
                           -
                           {** $total[$smarty.section.options.iteration][$sort_order]|number_format:$pb[countries].precision:".":"," **}
                        {/if}
                     </td>
                  </tr>
               {/if}
            {/foreach}
            
            <tr class="header1">
            	<td align="left"><strong>Total</strong></td>
            	{section name="countries" 
         					loop=$revision.number_of_countries}
         			<td align="right">&#{$meta.currency_unicode};{$summary[$smarty.section.options.iteration][$smarty.section.countries.iteration].total|number_format:2:".":","}</td>			
         		{/section}
         		<td align="right">&#{$meta.currency_unicode};{$option_summary[$smarty.section.options.iteration].total|number_format:2:".":","}</td>
            </tr>
            
            <tr class="header1">
            	<td align="left"><strong>CPC</strong></td>
            	{section name="countries" 
         					loop=$revision.number_of_countries}
         			<td align="right">&#{$meta.currency_unicode};{$summary[$smarty.section.options.iteration][$smarty.section.countries.iteration].cpc|number_format:2:".":","}</td>			
         		{/section}
         		<td align="right">&#{$meta.currency_unicode};{$option_summary[$smarty.section.options.iteration].cpc|number_format:2:".":","}</td>
            </tr>
            
            <tr>
               <td>&nbsp;</td>
               <td colspan="{$revision.number_of_countries}">&nbsp;</td>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>&nbsp;</td>
               <td colspan="{$revision.number_of_countries}">&nbsp;</td>
               <td>&nbsp;</td>
            </tr>

			</table>
{/section}
</form>

</td></tr>

{include file='common/div_footer.tpl'}