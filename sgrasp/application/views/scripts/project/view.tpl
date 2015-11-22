<html>
<head><link rel="STYLESHEET" type="text/css" href="/dhtmlx.css">
   <script src="/dhtmlx.js" type="text/javascript"></script>
   </head>
   <body>
{popup_init src="/js/overlib/overlib.js"}
{literal}
<script src='/js/common.js'></script>
<script src='/js/validate.js'></script>
<script src='/js/pjm.js'></script>
<style>
   td.title {
      font-weight: bold;
      vertical-align: top;
      padding-right: 1em;
   }
   tr.group td {
      padding-top: 1.5em;
   }
   .header1 A {
      cursor:pointer; width:100%; color:#ffffff;
   }
   .button_bar {
      margin:.3em .5em 0em 0em;
      text-align: left;
   }
</style>
{/literal}

<div style="margin-bottom:3em">{include file="project/vr_display_project_details_info.tpl"}</div>
<div id="a_tabbar" class="dhtmlxTabBar" imgpath="/imgs/" style="width:1000px; height:300px;"  skinColors="#FCFBFC,#F4F3EE" >
{include file="project/vr_display_project_details_studies.tpl"}
{include file="project/vr_display_project_details_contacts.tpl"}
{include file="project/vr_display_project_details_specs.tpl"}
{include file="project/vr_display_project_details_spec_changes.tpl"}
{include file="project/vr_display_project_details_comments.tpl"}
</div>
</body></html>

