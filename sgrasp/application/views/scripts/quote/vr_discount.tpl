
<form name='searchform' id='searchform' action="?e={"action=save_discount&id=`$proposal.proposal_id`"|url_encrypt}" method="POST" enctype="multipart/form-data">

<TABLE class=groupborder cellSpacing=0 width="70%" align="center">
   <TR>
      <TD class=tab>
<TABLE border='0' width='100%' cellpadding='0' cellspacing='0' style='background-color:white;'>
<TR>
   <TD align='center'>
      <table width='100%' align='center'>
      <tr>
         <td align=left class='header1'>
            <b>Project Setup</b>
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
</td></tr>
<tr><td>
<DIV id='proposal_search' style="display: block;">
<table width="100%">
<tr class="header1">
   <td rowspan="2">Pricing Items</td>
   <td rowspan="2">Base Pricing</td>
   <td rowspan="2">Inflator</td>
   <td colspan="4" align="center">Discount</td>
   <td rowspan="2">Net Pricing</td>
</tr>
<tr class="header1">
   <td>Contracted</td>
   <td>Non Contracted</td>
   <td>Promotional</td>
   <td>Ad-Hoc</td>
</tr>
{section name=id loop=$list.budget_setup}
   <tr class='{cycle values=tab,tab1}'>
      <td nowrap align="left">
         {$list.budget_setup[id].pricing_item_description}
      </td>
      <td align="right">
         ${$list.budget_setup[id].license_level_price}
      </td>
      <td align="right">
         {$list.budget_setup[id].inflator}%
      </td>
      <td align="right">
         {$list.budget_setup[id].contracted_discount}%
      </td>
      <td align="right">
         {$list.budget_setup[id].non_contracted_discount}%
      </td>
      <td align="right">
         {$list.budget_setup[id].promotional_discount}%
      </td>
      <td align="right">
         {$list.budget_setup[id].ad_hoc_discount}%
      </td>
      <td align="right">
         ${$list.budget_setup[id].net_price}
      </td>
   </tr>
<input type="hidden" name="proposal_revision_price_{$list.budget_setup[id].pricing_item_id}" value="{$list.budget_setup[id].proposal_revision_pricing_id}">
{/section}
   <tr class='tab'>
      <td nowrap align="left">
         <strong>Project Setup</strong>
      </td>
      <td align="center">
         -
      </td>
      <td align="right">
         {$list.group_discount.setup[0].inflator}%
      </td>
      <td align="right">
         {$list.group_discount.setup[0].contracted_discount}%
      </td>
      <td align="right">
         {$list.group_discount.setup[0].non_contracted_discount}%
      </td>
      <td align="right">
         {$list.group_discount.setup[0].promotional_discount}%
      </td>
      <td align="right">
         {$list.group_discount.setup[0].ad_hoc_discount}%
      </td>
      <td>
         -
      </td>
   </tr>
</table>
</div>
</td>
</tr>
</table>
<br><br>

<TABLE class=groupborder cellSpacing=0 width="70%" align="center">
   <TR>
      <TD class=tab>
<TABLE border='0' width='100%' cellpadding='0' cellspacing='0' style='background-color:white;'>
<TR>
   <TD align='center'>
      <table width='100%' align='center'>
      <tr>
         <td align=left class='header1'>
            <b>Hosting Cost</b>
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
</td></tr>
<tr><td>
<DIV id='proposal_search' style="display: block;">
<table width="100%">
<tr class="header1">
   <td rowspan="2">Pricing Items</td>
   <td rowspan="2">Base Pricing</td>
   <td rowspan="2">Inflator</td>
   <td colspan="4" align="center">Discount</td>
   <td rowspan="2">Net Pricing</td>
</tr>
<tr class="header1">
   <td>Contracted</td>
   <td>Non Contracted</td>
   <td>Promotional</td>
   <td>Ad-Hoc</td>
</tr>

{section name=id loop=$list.budget_hosting}
   <tr class='{cycle values=tab,tab1}'>
      <td nowrap align="left">
         {$list.budget_hosting[id].pricing_item_description}
      </td>
      <td align="right">
         ${$list.budget_hosting[id].license_level_price}
      </td>
      <td align="right">
         {$list.budget_hosting[id].inflator}
      </td>
      <td align="right">
         {$list.budget_hosting[id].contracted_discount}
      </td>
      <td align="right">
         {$list.budget_hosting[id].non_contracted_discount}
      </td>
      <td align="right">
         {$list.budget_hosting[id].promotional_discount}
      </td>
      <td align="right">
         {$list.budget_hosting[id].ad_hoc_discount}%
      </td>
      <td align="right">
         ${$list.budget_hosting[id].net_price}
      </td>
   </tr>
<input type="hidden" name="proposal_revision_price_{$list.budget_hosting[id].pricing_item_id}" value="{$list.budget_hosting[id].proposal_revision_pricing_id}">
{/section}
<tr class='tab'>
      <td nowrap align="left">
         <strong>Hosting Discount</strong>
      </td>
      <td align="center">
         -
      </td>
      <td align="right">
         {$list.group_discount.hosting[0].inflator}%
      </td>
      <td align="right">
         {$list.group_discount.hosting[0].contracted_discount}%
      </td>
      <td align="right">
         {$list.group_discount.hosting[0].non_contracted_discount}%
      </td>
      <td align="right">
         {$list.group_discount.hosting[0].promotional_discount}%
      </td>
      <td align="right">
         {$list.group_discount.hosting[0].ad_hoc_discount}%
      </td>
      <td>
         -
      </td>
   </tr>
</table>
</div>
</td>
</tr>
</table>
<br><br>

<TABLE class=groupborder cellSpacing=0 width="70%" align="center">
   <TR>
      <TD class=tab>
<TABLE border='0' width='100%' cellpadding='0' cellspacing='0' style='background-color:white;'>
<TR>
   <TD align='center'>
      <table width='100%' align='center'>
      <tr>
         <td align=left class='header1'>
            <b>Data Processing</b>
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
</td></tr>
<tr><td>
<DIV id='proposal_search' style="display: block;">
<table width="100%">
<tr class="header1">
   <td rowspan="2">Pricing Items</td>
   <td rowspan="2">Base Pricing</td>
   <td rowspan="2">Inflator</td>
   <td colspan="4" align="center">Discount</td>
   <td rowspan="2">Net Pricing</td>
</tr>
<tr class="header1">
   <td>Contracted</td>
   <td>Non Contracted</td>
   <td>Promotional</td>
   <td>Ad-Hoc</td>
</tr>

{section name=id loop=$list.budget_dp}
   <tr class='{cycle values=tab,tab1}'>
      <td nowrap align="left">
         {$list.budget_dp[id].pricing_item_description}
      </td>
      <td align="right">
         ${$list.budget_dp[id].license_level_price}
      </td>
      <td align="right">
         {$list.budget_dp[id].inflator}%
      </td>
      <td align="right">
         {$list.budget_dp[id].contracted_discount}%
      </td>
      <td align="right">
         {$list.budget_dp[id].non_contracted_discount}%
      </td>
      <td align="right">
         {$list.budget_dp[id].promotional_discount}%
      </td>
      <td align="right">
         {$list.budget_dp[id].ad_hoc_discount}%
      </td>
      <td align="right">
         ${$list.budget_dp[id].net_price}
      </td>
   </tr>
<input type="hidden" name="proposal_revision_price_{$list.budget_dp[id].pricing_item_id}" value="{$list.budget_dp[id].proposal_revision_pricing_id}">
{/section}
<tr class='tab'>
      <td nowrap align="left">
         <strong>DP Discount</strong>
      </td>
     <td align="center">
         -
      </td>
      <td align="right">
         {$list.group_discount.dp[0].inflator}%
      </td>
      <td align="right">
         {$list.group_discount.dp[0].contracted_discount}%
      </td>
      <td align="right">
         {$list.group_discount.dp[0].non_contracted_discount}%
      </td>
      <td align="right">
         {$list.group_discount.dp[0].promotional_discount}%
      </td>
      <td align="right">
         {$list.group_discount.dp[0].ad_hoc_discount}%
      </td>
      <td align="center">
         -
      </td>
   </tr>
</table>
</div>
</td>
</tr>
</table>
<br><br>

<TABLE class=groupborder cellSpacing=0 width="70%" align="center">
   <TR>
      <TD class=tab>
<TABLE border='0' width='100%' cellpadding='0' cellspacing='0' style='background-color:white;'>
<TR>
   <TD align='center'>
      <table width='100%' align='center'>
      <tr>
         <td align=left class='header1'>
            <b>Panel Cost</b>
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
</td></tr>
<tr><td>
<DIV id='proposal_search' style="display: block;">
<table width="100%">
<tr class="header1">
   <td rowspan="2">Panel Cost Type</td>
   <td rowspan="2">Base Pricing</td>
   <td rowspan="2">Inflator</td>
   <td colspan="4" align="center">Discounts</td>
   <td rowspan="2">Net Pricing</td>
</tr>
<tr class="header1">
   <td>Contracted</td>
   <td>Non Contracted</td>
   <td>Promotional</td>
   <td>Ad-Hoc</td>
</tr>
{section name=id loop=$list.budget_panel}
<tr class="{cycle values=tab,tab1}">
   <td nowrap align="left">
      {$list.budget_panel[id].panel_cost_type_description}&nbsp;&nbsp;&nbsp;&nbsp;
   </td>
   <td nowrap>&nbsp;&nbsp;</td>
   <td align="right">
      {$list.budget_panel[id].inflator}%
   </td>
   <td align="right">
      {$list.budget_panel[id].contracted_discount}%
   </td>
   <td align="right">
      {$list.budget_panel[id].non_contracted_discount}%
   </td>
   <td align="right">
      {$list.budget_panel[id].promotional_discount}%
   </td>
   <td align="right">
      {$list.budget_panel[id].ad_hoc_discount}%
   </td>
   <td>&nbsp;&nbsp;</td>
</tr>
{/section}
<tr class='tab1'>
      <td nowrap align="left">
         <strong>Panel Discount</strong>
      </td>
      <td>&nbsp;&nbsp;</td>
      <td align="right">
         {$list.group_discount.panel[0].inflator}%
      </td>
      <td align="right">
         {$list.group_discount.panel[0].contracted_discount}%
      </td>
      <td align="right">
         {$list.group_discount.panel[0].non_contracted_discount}%
      </td>
      <td align="right">
         {$list.group_discount.panel[0].promotional_discount}%
      </td>
      <td align="right">
         {$list.group_discount.panel[0].ad_hoc_discount}%
      </td>
      <td>&nbsp;&nbsp;</td>
   </tr>
</table>
</div>
</td></tr></table>



<br>

{include file='app/pgen/vw_volume_discount.tpl}

