<table width='{$meta.portlet_width|default:"80%"}'>
<tr>
	<td class="CellTableHead">Proposals Aging</td>
</tr>

<tr>
	<td>
		<DIV id='study_list' style="display: block;">
      <table width='100%' align='center' id='tblhead' >
         <tr>
            {section name=id loop=$header}
            	<TD align='left' style='cursor=Hand;' class='header1'>
             		<B>{$header[id].title}</B>
               </TD>
            {/section}
         </tr>
{section name=id loop=$data}

<tr class="{cycle values='tab,tab1}">
   <td>{$data[id].period}</td>
   <td>{$data[id].proposal}</td>
   <td>{$data[id].revision}</td>
   <td align="right">${$data[id].total_value|number_format:2:".":","}</td>
   <td>{$data[id].in_progress}</td>
   <td>{$data[id].postponed}</td>
   <td>{$data[id].cancelled}</td>
   <td>{$data[id].lost}</td>
   <td>{$data[id].won}</td>
   <td align="right">${$data[id].won_value|number_format:2:".":","}</td>
   <td>{$data[id].win_rate|number_format:2:".":","}%</td>
   <td>{$data[id].capture_rate|number_format:2:".":","}%</td>
</tr>
{sectionelse}
	<tr class="tab1">
		<td colspan="4">
			<i>No Recent Proposals</i>
		</td>
	</tr>
{/section}
</table>
</div>
</td></tr>
</table>

