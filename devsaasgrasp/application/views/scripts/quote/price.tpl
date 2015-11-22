<script src='/js/validate.js'></script>
<script src='/js/pgen.js'></script>
{popup_init src="/js/overlib/overlib.js"}

{if $meta.message neq ""}
<div align="center">
   <font color="Red"><strong>{$meta.message}</strong></font>
</div>
{/if}

{include file='quote/summary.tpl}
<br>
<div id='prop_comment_top'>&nbsp;</div>
<br>


<form action="/quote/done/rev/{$revision.proposal_revision_id}" 
      method="POST" 
      enctype="multipart/form-data">

{include file='common/div_header.tpl'
			div_title='Review'
			div_name='p_review'
			div_align='center'
			div_width='70%'}

	<tr><td>

<!-- Proposal ID {$revision.proposal_id} Revision {$revision.proposal_revision_id} -->
{assign var=pricing_item_group_id value=0}
{section name="options" 
         loop=$revision.number_of_options}

         <table width="100%">
               
            <tr class="header1">
               <td align="left"><strong>Option {$smarty.section.options.iteration}</strong></td>
               <td align="center" colspan="{$revision.number_of_countries}"></td>
               <td align="center"><strong>&nbsp;</strong></td>
            </tr>
         
            <tr class="header1">
               <td align="center">Pricing Item</td>
                  
               {section name="countries"
               			loop=$revision.number_of_countries}   
                     <td align="center">
                        <strong>{$country[$smarty.section.options.iteration][$smarty.section.countries.iteration].country_description}</strong>
                        {if $meta.display_txt_sub_group eq "1"}
                        	<br>{$country[$smarty.section.options.iteration][$smarty.section.countries.iteration].sub_group_description}
                        {/if}
                     </td>
					{/section}
                  
               <td align="center"><strong>Total</strong></td>
            </tr>
   
            {foreach name=proposal_budget_1 
                     item=pb 
                     from=$options[$smarty.section.options.iteration]}
      
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
                     
                        <td>
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
                              ${$pb[countries].amount|number_format:3:".":","}
                           {elseif $pb[countries].value_type eq "T"}   
                              <strong>${$pb[countries].amount|number_format:3:".":","}</strong>
                           {else}
                              {$pb[countries].amount}
                           {/if}
                        </td>
                     {/section}
                     <td align="right">
                        {if $pb[0].value_type eq "C"}
                           -
                           {** $total[$smarty.section.options.iteration][$sort_order]|number_format:3:".":"," **}
                        {elseif $pb[0].value_type eq "T"}
                           <strong>${$total[$smarty.section.options.iteration][$sort_order]|number_format:3:".":","}</strong>
                        {else}
                           -
                           {** $total[$smarty.section.options.iteration][$sort_order]|number_format:3:".":"," **}
                        {/if}
                     </td>
                  </tr>
               {/if}
            {/foreach}
            
            <tr class="header1">
            	<td><strong>Total</strong></td>
            	{section name="countries" 
         					loop=$revision.number_of_countries}
         			<td align="right">${$summary[$smarty.section.options.iteration][$smarty.section.countries.iteration].total|number_format:3:".":","}</td>			
         		{/section}
         		<td>&nbsp;</td>
            </tr>
            
            <tr class="header1">
            	<td><strong>CPC</strong></td>
            	{section name="countries" 
         					loop=$revision.number_of_countries}
         			<td align="right">${$summary[$smarty.section.options.iteration][$smarty.section.countries.iteration].cpc|number_format:3:".":","}</td>			
         		{/section}
         		<td>&nbsp;</td>
            </tr>

            
				<tr class="tab1">
				   	<td>&nbsp;</td>
				      <td colspan="{$revision.number_of_countries}" align="center">
				      	<input type="button" 
                				 onclick="window.location.href = '/quote/done/rev/{$revision.proposal_revision_id}';"
                				 value="Previous ">
      					&nbsp;&nbsp;
				         <input type="button" 
      							 onclick="this.form.submit();" 
      							 value="Next ">
				         &nbsp;&nbsp;
				         <input type="button" 
					 				 onclick="CancelProposal({$revision.proposal_id}, {$revision.proposal_revision_id|default:0});" 
                				 value="Cancel">
				      </td>
				      <td>&nbsp;</td>
				</tr>
			</table>
{/section}

</td></tr>

{include file='common/div_footer.tpl'}

</form>
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