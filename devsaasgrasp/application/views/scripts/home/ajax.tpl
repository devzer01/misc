{popup_init src="/js/overlib/overlib.js"}

<script src="/js/ptm.js"></script>
<script src="/js/scriptaculous/prototype.js" type="text/javascript"></script>
<script src="/js/scriptaculous/scriptaculous.js" type="text/javascript"></script>

{literal}
<style>

	.dark_border {
		border: 1px solid black;
		background-color: red;
	}
	
	div {
		/* height: 50px; */
	}

</style>
{/literal}

{if $meta.message neq ""}
<div align="center"><font color="Red">{$meta.message}</font></div>
{/if}

<table align="center" width="100%">
	{section name=ptl loop=10 step=2 start=1}
	<tr>
		<td width='3%'>&nbsp;</td>
		{section name=dv loop=$smarty.section.ptl.index+2 start=$smarty.section.ptl.index}
		<td align="center" valign="top" width='40%'>
			<div id='portlet_{$smarty.section.dv.index}'></div>
		</td>
		<input type="hidden" id="sort_order_{$smarty.section.dv.index}" name="sort_order_{$smarty.section.dv.index}" value="">
		<td width='3%'>&nbsp;</td>
		{/section}
	</tr>
	{/section}
	
</table>

{section name=ptl loop=$list.portlet}
<script language="javascript">
	GetAjaxPortlet('portlet_{$list.portlet[ptl].sort_order}', {$list.portlet[ptl].portlet_id}, {$list.portlet[ptl].sort_order});
</script>
{/section}

