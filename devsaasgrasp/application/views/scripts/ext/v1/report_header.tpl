<table cellspacing="0" cellpadding="0" class="mainDataOutput">
   <tr>
      <td colspan="2" align="center"></td>
   </tr>
   <tr>
      <td class="dataOutputHeader"><span>{$meta.report_title}</span>&nbsp;
         <img src="/images/ext/plus.gif" width="20" height="12" name="pic_report_list" id="pic_report_list" 
              onClick="toggleDisplay('div_report_list','pic_report_list','images/plus.gif','images/minus.gif');">
      </td>
      <td class="dataOutputCustomize" width="100%">
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
                                 
                                 <script>
                                 var frmScroll = document.table_header_scroll
                                 </script>
         </form>
      </td>
   </tr>
   <tr>
   <td colspan="2">
      <div id="div_report_list" style="display:">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="innerDataTable">
         
         <tr>
				<td class="innerTableHeader">{$meta.report_title}</td>
         </tr>
         
      </table>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="innerDataTable">
         <form method='post' action='?{$smarty.server.QUERY_STRING}' id='tblfrm1'>
            <tr>
            {section name=id loop=$header}
	           <TD align='left' style='cursor:pointer;' class='columnHeader' nowrap>
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
         