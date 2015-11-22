<html><head>
{literal}
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<link rel="STYLESHEET" type="text/css" href="/dhtmlx.css">
    <script src="/dhtmlx.js"></script>
  <style>
		body {
			background-color: white;
			padding: 5px;
		}
		.sp {
			padding: 3px ;
		    margin: 2px;
		    font-size: 24px;
		    width: 400px;
		    text-align: right;
		}
	</style>
	<script>
		function displayChoices() {
			$('#clayer').clone().appendTo('#choices');
		}
		
		function displayAddForm() {
			$('#addform').show();
		}
	</script>
{/literal}
</head>
<body>
<div style='width: 950px;'>
<input type='button' value='Add' onclick='javascript:displayAddForm();' />
<div style='width: 400px; float: left;'>
<div id="gridbox" style="width:390px;height:400px"></div>
<br/><br/>
</div>
<div style='width: 550px; float: left;'>
<div id='addform' style='display:none;'>
<form name="theForm" method="post" action="/project/savedatapoint">
<div class='sp'>Label <input type='text' name='label' size='30' /></div>
<div class='sp'>Description <input type='text' name='qtext' size='30' /></div>
<div class='sp'>Type <select name="type" id="dp_type"><option value="radio">Single choice (radio)</option><option value="checkbox">Multiple choice (checkbox)</option><option value="openend">Open end</option><option value="date">Date</option></select></div>
<div class='sp'>Choices <input type='button' value='add' onclick='displayChoices();' />
	<div id='choices' style='float: left; width: 800px;'>
		<div id='clayer' style='clear:both;'>
			<div style='float: left; padding: 5px;'> Name <input type='text' name='name[]' /> </div>
			<div style='float: left; padding: 5px; '> Value <input type='text' name='custom[]' /> </div>
		</div>
	</div>
</div>
<div class='sp'><input type='button' value='Add Project' onclick='this.form.submit();' /></div>
</form>
</div>
<div id='groupdetail' style='width:490px; height: 400px;'>
	
</div>

</div>
</div>
{literal}
<script>
mygrid = new dhtmlXGridObject('gridbox');
mygrid.setImagePath("/imgs/");//path to images required by grid
mygrid.setHeader("Column A, Column B");//set column names
mygrid.setInitWidths("100,250");//set column width in px
mygrid.setColAlign("right,left");//set column values align
mygrid.setColTypes("ro,ed");//set column types
mygrid.setColSorting("int,str");//set sorting
mygrid.init();//initialize grid
mygrid.setSkin("dhx_skyblue");//set grid skin
mygrid.loadXML("/project/datapoints");//load data
mygrid.attachEvent("onRowSelect", showChoice);

function showChoice(rowid, oldid) {
	var choicegrid = new dhtmlXGridObject('groupdetail');
	choicegrid.setImagePath("/imgs/");//path to images required by grid
	choicegrid.setHeader("Column A, Column B");//set column names
	choicegrid.setInitWidths("100,250");//set column width in px
	choicegrid.setColAlign("right,left");//set column values align
	choicegrid.setColTypes("ro,ed");//set column types
	choicegrid.setColSorting("int,str");//set sorting
	choicegrid.init();//initialize grid
	choicegrid.setSkin("dhx_skyblue");//set grid skin
	choicegrid.loadXML("/project/datapointchoices/id/" + rowid);//load data
	
}
</script>
{/literal}

</body>
</html>