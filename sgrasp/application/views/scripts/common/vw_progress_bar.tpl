<br>
<br>
<table align="center">
	<tr>
		<td align="center">
			<div align="center" id="progressBar" 
			     style="position:relative; width:{$meta.width}px; height:{$meta.height}px;">
			
				<div style="background-color: #CCC; position:absolute; top:1px; left:1px; width:{$meta.region_width}px; height:{$meta.region_height}px; z-index:100; font-size:1px;" id="progressRemain"></div>
				
				<div style="background-color: #FF0000; position:absolute; top:1px; left:1px; width:0px; height:{$meta.region_height}px; z-index:110; font-size:1px;" id="progressCompleted"></div>
				
				<input style="position:absolute; left:{$meta.status_pos}; background-color: transparent; border: 0px; z-index: 120; " type="text" id="progressStatus" size="30" readonly>
			</div>
		</td>
	</tr>
</table>


<script>
function setProgress(prcnt)
{ldelim}
	var w = {$meta.block_size} * prcnt;
	document.getElementById('progressCompleted').style.width = w+'px';
	document.getElementById('progressStatus').value = prcnt+"% done";
{rdelim}
</script>

