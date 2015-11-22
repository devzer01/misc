<table width='{$meta.portlet_width|default:"80%"}'>
<tr>
	<td class="CellTableHead">Proposals Waiting Approval</td>
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
{section name=id loop=$list}

<tr class="{cycle values='tab,tab1}">
   <td>
	   <a {popup text="`$list[id].proposal_name`" delay="300"} href='/app/pgen/index.php?e={"action=display_detail&proposal_id=`$list[id].proposal_id`"|url_encrypt}'>{$list[id].proposal_name|truncate:30:"...":true}</a>
   </td>
   <td>
      <a {popup text="`$list[id].account_name`" delay="300"} href="/app/acm/?action=display_account_detail&account_id={$list[id].account_id}">{$list[id].account_name|truncate:30:"...":true}</a>
   </td>
   <td align="right">${$list[id].max_amount|number_format:2:".":","}</td>
   <td>{$list[id].ae_name}</td>
   <td>{$list[id].am_name}</td>
   <td>{$list[id].age} Day(s)</td>
</tr>
{sectionelse}
	<tr class="tab1">
		<td colspan="4">
			<i>No Proposals Waiting Approval</i>
		</td>
	</tr>
{/section}
</table>
</div>
</td></tr>
</table>

