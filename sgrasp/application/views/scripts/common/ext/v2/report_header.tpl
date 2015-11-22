<table cellspacing="0" cellpadding="0" class="mainDataOutput" align="center">
   <tr>
      <td colspan="4" align="center"></td>
   </tr>
   <tr>
      <td class="dataOutputHeaderLeft"><img src="/images/ext/dataheaderleft.gif"></td>
      <td class="dataOutputHeader" width="50%" nowrap>{$meta.report_title}&nbsp;&nbsp;
         <img src="/images/ext/minus.gif" width="20" height="12" name="pic_{$div_name}" id="pic_{$div_name}" onClick="toggleDisplay('div_{$div_name}','pic_{$div_name}','/images/ext/plus.gif','/images/ext/minus.gif');">
      </td>
      <form name="table_header_scroll" method="POST" action="?{$smarty.server.QUERY_STRING}">
      <td align="right" class="dataOutputCustomize" nowrap width="50%">
                                 {if $meta.start neq "0"}
                                    <A href="#" onclick="document.table_header_scroll.start.value = 0; document.table_header_scroll.submit();">&lt;&lt;</a>&nbsp;&nbsp;
                                    &nbsp;
                                    <A href="#" onclick="document.table_header_scroll.direction.value='down'; document.table_header_scroll.submit();">&lt;</a>&nbsp;&nbsp;
                                 {/if}
                                 <select name="start" onchange="this.form.submit();">
                                    {html_options options=$meta.page_list selected=$meta.start}
                                 </select>&nbsp;&nbsp;
                                 {if $meta.start < $meta.last_page_start }
                                    <A href="#" onclick="document.forms.table_header_scroll.direction.value='up'; document.table_header_scroll.submit();">&gt;</a>&nbsp;
                                    <A href="#" onclick="document.forms.table_header_scroll.start.value={$meta.last_page_start}; document.table_header_scroll.submit();">&gt;&gt;</a>&nbsp;&nbsp;
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
      </td>
                                 <input type="hidden" name="direction" id="direction" value="">
                                 <input type="hidden" name="display_all" id="display_all" value="{$meta.display_all}">
                                 <input type="hidden" name="filter_off" id="filter_off" value="{$meta.filter_off}">
                                 </form>
		<td class="dataOutputHeaderRight"><img src="/images/ext/dataheaderright.gif"></td>
   </tr>
   
   <tr>
      <td colspan="4">
         <div id="div_{$div_name}" style="display:block;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="innerDataTable">
               <tr>
                  <td colspan="99" class="innerTableHeader">{$meta.report_title}</td>
               </tr>
               <form method='post' action='?{$smarty.server.QUERY_STRING}' id='tblfrm1'>
                  <tr>
                     {section name=id loop=$header}
                     <TD align='left' style='cursor:pointer;' class='columnHeader' nowrap>
                     {if $meta.sort_by_column eq $header[id].field}
                        <A class='header' onclick='document.forms.tblfrm1.ENC_sort.value="{"`$header[id].field` DESC"|url_encrypt}"; document.forms.tblfrm1.submit();' style='cursor:pointer; color:#ffffff; width:100%;'><B>{$header[id].title}</B></A>
                        <img src="/images/sort1.gif">
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
         