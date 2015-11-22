{literal}
<style>
	TR {
		FONT-FAMILY: Tahoma, Geneva, Arial, sans-serif; 
		FONT-SIZE: 8pt;
		TEXT-ALIGN: left;
	}
</style>
{/literal}

{include file='common/div_header.tpl'
			div_title='Panel Options'
			div_name='p_panel_options'
			div_width='70%'
			div_align='center'}

<tr><td>
{assign var="description_count" value="0"}  
{foreach from=$panel_options key="option_count" item="option"}

<table width="100%">

<tr class="header1">
   <td width='10%'>
      <strong>Option {$option_count}</strong>
   </td>
</tr>
</table>

{foreach from=$option key="country_count" item="country"}
<table width='100%' id='panel_details_{$option_count}_{$country_count}'>
<tr class="header1">
   <td width='15%'>Country Name</td>
   <td width='15%'>Sample Type</td>
   <td width='5%'>Completes</td>
   <td width='5%'>Incidence</td>
   <td width='5%'>Questionnaire Length</td>
   <td width='15%'>Extras</td>
   <td width='5%'>CPC</td>
   <td width='5%'>Total Cost</td>
   <td width='5%'>Adjustments</td>
</tr>

{foreach from=$country key="sample_type_id" item="sample_type"}
{foreach from=$sample_type item="sample"}
{assign var="proposal_revision_panel_id" value=$sample.proposal_revision_panel_id}
{assign var="suffix" value="_option_"|cat:$option_count|cat:"_country_"|cat:$country_count|cat:"_"|cat:$sample.sample_type_id}
{if $sample.prime eq "0"}
{assign var="suffix" value=$suffix|cat:"_"|cat:$description_count}
{/if}
<tr class="{cycle values='tab, tab1'}" 
    id="row_{$option_count}_{$country_count}_{$sample.sample_type_id}_{$sample.proposal_revision_panel_id}" 
    name="row_{$option_count}_{$country_count}_{$sample.sample_type_id}"
    style='text-align:right; '>            
      <td valign="top" style='text-align:left; '>
         {if $sample.sample_type_id eq "0"}
         <strong>{$sample.country_description}</strong>
         {/if}
      </td>
      <td valign="top" style='text-align:left; '>
      {if $sample.prime eq "1"}
      		<strong>{$sample.sample_type_description}</strong>
      {else}
      	    {$sample.sample_type_description}
      {/if}	
      </td>
      <td valign="top">{$sample.completes}</td>
      <td valign="top">{$sample.incidence}</td>
      <td valign="top">{$sample.question_length}</td>
      <td valign="top">
      {if $sample.prime neq "1"}
		<table>
			{foreach from=$sample.extras item="extra_description" key="list_id"}
			<tr>
			<td>
			{$extra_description}
			</td>
			</tr>
			{/foreach}
	      	{assign var="description_count" value=`$description_count+1`}  
		</table>
      {/if}
      </td>
      <td>${$sample.cost_per_complete|number_format}</td>
      <td>${$sample.total_cost|number_format}</td>
      <td>${$sample.adjustment|number_format}</td>
</tr>
{/foreach}
{/foreach}
</table>
{/foreach}

{/foreach}

</tr></td>

{include file='common/div_footer.tpl'}
