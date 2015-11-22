<TABLE class=groupborder cellSpacing=0 width="99%">
   <TR>
      <TD>
         <TABLE border='0'
                width='100%'
                cellpadding='0'
                cellspacing='0'
                style='background-color:white;'>
            <TR>
               <TD align='center'>
                  <table width='100%' align='center'>
                     <tr>
                        <td align=left class='header1'>
                           <b>{$meta.report_title}</b>
                        </TD>
                        <TD class=disabled
                            vAlign=top
                            align=right
                            width="75%"
                            rowSpan=2>

                           <table width="100%">
         	                 <tr>
         		                <td align="left">
         		                   {if $meta.header_table neq ""}
         		                      {include file=$meta.header_table}
         		                   {/if}
         		                </td>
         		                <td align="right">
         		                   <form name="table_header_scroll" method="POST" action="?{$smarty.server.QUERY_STRING}">
                                 {if $meta.start neq "0"}
                                    <A href="#" onclick="document.table_header_scroll.start.value = 0; document.table_header_scroll.submit();">&lt;&lt;</a>&nbsp;&nbsp;
                                    &nbsp;
                                    <A href="#" onclick="document.table_header_scroll.direction.value='down'; document.table_header_scroll.submit();">&lt;</a>&nbsp;&nbsp;
                                 {/if}
                                 <select name="start" onchange="this.form.submit();">
                                    {html_options options=$meta.page_list selected=$meta.start}
                                 </select>&nbsp;&nbsp;
                                 {if $meta.start < $meta.last_page_start }
                                    <A href="#" onclick="document.table_header_scroll.direction.value='up'; document.table_header_scroll.submit();">&gt;</a>&nbsp;
                                    <A href="#" onclick="document.table_header_scroll.start.value={$meta.last_page_start}; document.table_header_scroll.submit();">&gt;&gt;</a>&nbsp;&nbsp;
                                 {/if}
                                 #/Page&nbsp;
                                 <select name="page_size" onchange="this.form.submit();">
                                    {html_options values=$meta.page_size_list output=$meta.page_size_list selected=$meta.page_size}
                                 </select>&nbsp;&nbsp;
                                 {if $meta.total_rows > $meta.page_rows}
                                    [<A href='#' onclick="document.table_header_scroll.display_all.value=1; document.table_header_scroll.submit();">Display All</a>]&nbsp;&nbsp;
                                 {/if}
                                 {if $meta.filter_on eq "1"}
                                    [<A href='#' onclick="document.table_header_scroll.filter_off.value=1; document.table_header_scroll.submit();">Filter Off</a>]
                                 {/if}
                                 <input type="hidden" name="direction" id="direction" value="">
                                 <input type="hidden" name="display_all" id="display_all" value="0">
                                 <input type="hidden" name="filter_off" id="filter_off" value="0">
                                 <input type="hidden" name="export_excel" id="export_excel" value="0">
                                 </form>
                                 <script>
                                    var frmScroll = document.table_header_scroll
                                 </script>
                              </td>
				                 </tr>
			                  </table>
                        </TD>
                     </TR>
                     <TR>
                        <TD class=tab vAlign=bottom align='left'>
                           <A onmouseover="status='Show/Hide'; return true;" onmouseout="status=''; return true;" href="javascript:toggleWindow('report_list');">
                              <IMG id=report_list:arrow alt=Show/Hide src="/images/rollminus.gif" border=0>
                           </A>
                        </TD>
                     </TR>
                  </TABLE>
               </td>
            </tr>
         </table>
            <DIV id='report_list' style="display: block;">
             <table border='0' cellpadding='0' cellspacing='0' width='100%'>
									<tr>
  										<td class="CellTableHead">{$meta.report_title}</td>
									</tr>
								</table>
               <table width='100%' align='center' id='tblhead' >
                  <form method='post' action='?{$smarty.server.QUERY_STRING}' id='tblfrm1'>
                  <tr>
                     {section name=id loop=$header}
	                   <TD align='left' style='cursor:pointer;' class='header1' nowrap>
                       {if $meta.sort_by_column eq $header[id].field}
		                   <A class='header' onclick='document.forms.tblfrm1.ENC_sort.value="{"`$header[id].field` DESC"|url_encrypt}"; document.forms.tblfrm1.submit();' style='cursor:pointer; color:#ffffff; width:100%;'><B>{$header[id].title}</B></A>
		                   <img src="/images/sort1.gif">
		                 {elseif $header[id].field eq ""}
		                 		<B>{$header[id].title}</B>
                       {else}
		                   <A class='header' onclick='document.forms.tblfrm1.ENC_sort.value="{"`$header[id].field`"|url_encrypt}"; document.forms.tblfrm1.submit();' style='cursor:pointer; color:#ffffff; width:100%;'><B>{$header[id].title}</B></A>
		                   {if $meta.sort_by_column eq $header[id].field|cat:" DESC"}
		                   <img src="/images/sort2.gif">
		                   {/if}
		                 {/if}
                      </TD>
                     {/section}
                  </tr>
                     <input type='hidden' name='ENC_sort'>
               </form>