<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
<link type="text/css" rel="stylesheet" href="/js/gantt/dhtmlxGantt/codebase/dhtmlxgantt.css">
<script type="text/javascript" language="JavaScript" src="/js/gantt/dhtmlxGantt/codebase/dhtmlxcommon.js"></script>
<script type="text/javascript" language="JavaScript" src="/js/gantt/dhtmlxGantt/codebase/dhtmlxgantt.js"></script>
</head>
<body onload="createChartControl('GanttDiv');">

<script>
{literal}
function createChartControl(htmlDiv1)
{
    // Create Gantt control
    var ganttChartControl = new GanttChart();
    // Setup paths and behavior
    ganttChartControl.setImagePath("/js/gantt/dhtmlxGantt/codebase/imgs/");
    // Build control on the page
    ganttChartControl.setEditable(true);
    ganttChartControl.showNewProject(true);
    ganttChartControl.create(htmlDiv1);
    // Load data structure		
    ganttChartControl.loadData("/project/gantt/id/1",true,true);
}
{/literal}
</script>
<div style="width:850px; height:320px; position:relative" id="GanttDiv"></div>
</body>
</html>