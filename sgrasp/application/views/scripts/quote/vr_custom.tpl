<script src='/js/validate.js'></script>
<script src='/js/pgen.js'></script>
{popup_init src="/js/overlib/overlib.js"}

<TABLE class=groupborder cellSpacing=0 width="70%" align="center">
   <TR>
      <TD class=tab>
<TABLE border='0' width='100%' cellpadding='0' cellspacing='0' style='background-color:white;'>
<TR>
   <TD align='center'>
      <table width='100%' align='center'>
      <tr>
         <td align=left class='header1'>
            <b>Custom Pricing</b>
         </TD>
         <TD class=disabled vAlign=top align=right width="75%" rowSpan=2>
            &nbsp;
         </TD>
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
<DIV id='pgen_step4' style="display: block;">
<form name='searchform' id='searchform' action="?e={"action=save_custom_pricing&id=`$proposal.proposal_id`"|url_encrypt}" method="POST" enctype="multipart/form-data">
<table width="100%">
{section name="options" loop=$revision.number_of_options}

   
      <tr class="header1">
         <td align="left"><strong>Option {$smarty.section.options.iteration}</strong></td>
			{section name="countries"
         			loop=$revision.number_of_countries}
                  <td align="center">
                     <strong>
                     	{$options[$smarty.section.options.iteration][$smarty.section.countries.iteration].country_description}
                     	{if $meta.display_txt_sub_group eq "1"}
				            	<br>
									{$options[$smarty.section.options.iteration][$smarty.section.countries.iteration].sub_group_description}
								{/if}
                     </strong>
                  </td>
                  
         {/section}
      </tr>
      
      <tr class="tab1">
        		<td><strong>Number of Completed Interviews</strong></td>
        		{section name="countries"
        					loop=$revision.number_of_countries}
        			<td align="right">{$options[$smarty.section.options.iteration][$smarty.section.countries.iteration].completes}</td>
        		{/section}
        
        </tr>

        <tr class="tab">
        		<td><strong>Cost Per Interview (CPI)</strong></td>
        		{section name="countries"
        					loop=$revision.number_of_countries}
        			<td align="right">
        					&#{$meta.currency_unicode};{$options[$smarty.section.options.iteration][$smarty.section.countries.iteration].panel_cost_per_interview|number_format:"2":".":","}
        			</td>
        		{/section}
        
	</tr>

      {section name="pg" 
               loop=$pricing_group}
               
         <tr class="{cycle values='tab,tab1'}">
            <td align="left">
               <strong>{$pricing_group[pg].pricing_item_group_description}</strong>
            </td>
            {assign var="gp_id" 
                    value=$pricing_group[pg].pricing_item_group_id}
            
            {section name="countries" 
                     loop=$revision.number_of_countries}
                     
                     <td align="right">
                        &#{$meta.currency_unicode};{$custom[$smarty.section.options.iteration][$smarty.section.countries.iteration][$gp_id].amount|number_format:"2":".":","}
                     </td>
                     
            {/section}
         </tr>
         
      {/section}
      
      <tr class="tab1">
         <td align="left">
            <strong>Total</strong>
         </td>
         {section name="countries" 
                  loop=$revision.number_of_countries}
                  
            <td align="right">
               &#{$meta.currency_unicode};{$t_custom[$smarty.section.options.iteration][$smarty.section.countries.iteration]|number_format:"2":".":","}
            </td>
            
         {/section}
      </tr>

      <tr class="tab">
         <td align="left">
            <strong>Cost Per Complete (CPC)</strong>
         </td>
         {section name="countries" 
                  loop=$revision.number_of_countries}
                  
            <td align="right">
               &#{$meta.currency_unicode};{$cpc_custom[$smarty.section.options.iteration][$smarty.section.countries.iteration]|number_format:"2":".":","}
            </td>
            
         {/section}
      </tr>
   
{/section}
</table>
</form>
</div>
</td>
</tr>
</table>