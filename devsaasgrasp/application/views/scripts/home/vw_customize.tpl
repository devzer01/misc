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

<form method="POST" action="?action=save_dashboard">
<table align="center">
	<tr class="tab">
		<td>
			<table>
				<tr class="tab">
					<td><div id='trashcan'><img src='/images/trash.gif'></div></td>
					<td>&nbsp;&nbsp;&nbsp;</td>
					<td>
			To Customize Dashboard, Drag Any Portlet To Any Where That Turns Red to Re-Order. <br> 
			Drag to Trashcan To Delete A Portlet <br>
			To Add a New Portlet Select From The Drop Down List and Press Add. <br> 
			Once you Completed The Customization Press Save. <br>
					</td>
				</tr>
			</table>
		</td>
		<td>
			&nbsp;
		</td>
		<td>
			<select name="portlet_id" id="portlet_id">
				<option value="">Select Portlet</option>
				{html_options options=$list.all_portlet}
			</select>
			<input type="button"
					 value="Add"
					 onClick="AddPortlet()">
			&nbsp;&nbsp;
			<input type="button"
					 value="Save"
					 onclick="this.form.submit();">
		</td>
	</tr>
</table>

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
</form>

{section name=ptl loop=$list.portlet}
<script language="javascript">
	GetAjaxPortlet('portlet_{$list.portlet[ptl].sort_order}', {$list.portlet[ptl].portlet_id}, {$list.portlet[ptl].sort_order});
	var sort_order_{$list.portlet[ptl].sort_order} = document.getElementById('sort_order_{$list.portlet[ptl].sort_order}');
	sort_order_{$list.portlet[ptl].sort_order}.value = {$list.portlet[ptl].portlet_id};
</script>
{/section}

{section name=di loop=10}
	<script>
		new Draggable('portlet_{$smarty.section.di.iteration}', {ldelim}revert:true{rdelim});
		
		Droppables.add('portlet_{$smarty.section.di.iteration}',{ldelim}
		hoverclass: 'dark_border',
	   onDrop: function(element) 
	     {ldelim} 
	     		var temp_html = $('portlet_{$smarty.section.di.iteration}').innerHTML;
	     		$('portlet_{$smarty.section.di.iteration}').innerHTML = element.innerHTML;
	     		element.innerHTML = temp_html;
	     		var re_portlet_id = new RegExp("[0-9]+$", "");
			   var portlet_id = element.id.match(re_portlet_id);
			   var sort_order = document.getElementById('sort_order_'+portlet_id);
			   var destination_order = document.getElementById('sort_order_'+{$smarty.section.di.iteration});
			   var temp_order = destination_order.value;
			   destination_order.value = sort_order.value;
			   sort_order.value = temp_order;
	     		{rdelim}{rdelim});	
	</script>
{/section}

{literal}
<script>
	// * trash can code */
	Droppables.add('trashcan',{
	hoverclass: 'dark_border',
   onDrop: function(element) 
     { 
     		//var temp_html = $('portlet_10').innerHTML;
     		//$('portlet_10').innerHTML = element.innerHTML;
     		element.innerHTML = '&nbsp;';
     		var re_portlet_id = new RegExp("[0-9]+$", "");
			var portlet_id = element.id.match(re_portlet_id);
			var sort_order = document.getElementById('sort_order_'+portlet_id);
			sort_order.value = '';   
     }});
</script>
{/literal}

