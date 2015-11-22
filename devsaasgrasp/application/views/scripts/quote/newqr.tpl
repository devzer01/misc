<html>
<head>
<link rel="STYLESHEET" type="text/css" href="/dhtmlx.css">
<script src="/dhtmlx.js" type="text/javascript"></script>
<script src='/js/validate.js'></script>
<script src='/js/pgen.js'></script>
<!-- Combo-handled YUI CSS files: -->
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/combo?2.9.0/build/editor/assets/skins/sam/simpleeditor.css">
<!-- Combo-handled YUI JS files: -->
<script type="text/javascript" src="http://yui.yahooapis.com/combo?2.9.0/build/yahoo-dom-event/yahoo-dom-event.js&2.9.0/build/container/container_core-min.js&2.9.0/build/element/element-min.js&2.9.0/build/editor/simpleeditor-min.js"></script>
</head>
<body class="yui-skin-sam">
<form name='searchform' 
                  id='searchform' 
                  action="/quote/saveqr/quote/{$proposal.proposal_id}" 
                  method="POST" 
                  enctype="multipart/form-data">
            
            {include file='common/div_header.tpl'
            			div_title='Add Revision'
            			div_name='add_revisions'
            			div_width='70%'
            			div_align='center'}            

               <tr class="tab1">
                  <td align="left" width="25%">
                     <strong>{$lang.PROPOSAL_TYPE}</strong>
                  </td>
      
                  <td>
                     <select name="proposal_type_id" onchange="set_pricing_method(this) && select_service(this);"><!--onkeypress="set_pricing_method(this.value);">-->
                        {html_options options=$list.proposal_type 
                                      selected=$proposal.proposal_type_id}
                     </select>
                  </td>
                  <td align="left">
                     <strong>Pricing Method</strong>
                  </td>
                  <td>
                     <select name="pricing_type_id">
                        {html_options options=$list.pricing_type 
                                      selected=$proposal.pricing_type_id}
                     </select>
                     <input type="hidden" name="study_interview_type_id" value="0">
                  </td>
               </tr>
               
               <tr class="tab">
                  <td align="left">
                     <strong>{$lang.PROJECT_SETUP_PERIOD}</strong>
                  </td>
                  <td>
                     <select name="study_setup_duration_id">
                        {html_options options=$list.fieldwork_duration 
                                      selected=$proposal.study_setup_duration_id}
                     </select>
                  </td>
                  <td align="left">
                     <strong>{$lang.FIELD_WORK_PERIOD}</strong>
                  </td>
                  <td>
                     <select name="study_fieldwork_duration_id">
                        {html_options options=$list.fieldwork_duration 
                                      selected=$proposal.study_fieldwork_duration_id}
                     </select>
                  </td>
               </tr>
               
               <tr class="tab1">
                  <td align="left">
                     <strong>{$lang.DATA_PROCESSING_PERIOD}</strong>
                  </td>
                  <td>
                     <select name="study_data_processing_duration_id">
                        {html_options options=$list.fieldwork_duration 
                                      selected=$proposal.study_data_processing_duration_id}
                     </select>
                  </td>
                   <td align="left">
                     <strong>{$lang.PROPOSAL_OPTION_TYPE}</strong>
                  </td>
                  <td>
                     <select name="proposal_option_type_id">
                        <option value="0">Select Option Type</option>
                        {html_options options=$list.proposal_option_type 
                                      selected=$proposal.proposal_option_type_id}
                     </select>
                  </td>
               </tr>
               
               <tr class="tab">
                  <td align="left">
                     <strong>Sample Type</strong>
                  </td>
                  <td align="left">
                     <select name="sample_type_id[]" multiple size="3">
                        {html_options options=$list.sample_type 
                                      selected=$list.sample_type_selected}
                     </select>
                  </td>
                  <td>
                     <strong>{$lang.NUMBER_OF_COUNTRIES}</strong>&nbsp;
                     <input type="text" 
                            name="number_of_countries" 
                            value="{$revision.number_of_countries}" 
                            size="1" 
                            onchange="validate_number_of_countries(this.value);">
                     <input type="hidden" 
                            name="r_number_of_countries"
                            value="Number of countries is required">
                     <input type="hidden"
                            name="i_number_of_countries"
                            value="Number of countries must be a number">
                  </td>
                  <td>
                     <strong>{$lang.NUMBER_OF_STUDY_OPTIONS}</strong>&nbsp;
                     <input type="text" 
                            name="number_of_options" 
                            value="{$revision.number_of_options}" 
                            size="1">
                     <input type="hidden" 
                            name="r_number_of_options"
                            value="Number of Options is required">
                     <input type="hidden"
                            name="i_number_of_options"
                            value="Number of Options must be a number">
                  </td>
               </tr>
 {include file='common/div_footer.tpl'}
 <div id="a_tabbar" class="dhtmlxTabBar" imgpath="/imgs/" style="width:1000px; height:500px;"  skinColors="#FCFBFC,#F4F3EE" >
 {include file='common/div_header.tpl' 
			div_title='General Comment' 
			div_name='general_comment'} 
  			<tr><td>
            	<textarea id='general_comment' name="general_comment" cols="60" rows="20">{$list.general_comment}</textarea>
                  </td>
               </tr>
{include file='common/div_footer.tpl'}
{include file='common/div_header.tpl' 
			div_title='Qualifying Criteria' 
			div_name='qfc'}
			<tr><td>
                     <textarea id='qualifying_criteria' name="qualifying_criteria"  cols="150" rows="20">{$list.qualifying_criteria}</textarea>
                  </td>
               </tr>
               <tr><td>
                  <input type="file" name="qualifying_criteria_file">
                  {section name=fqc loop=$list.file_qc}
                     <a href="?action=get_revision_file&revision_file_id={$list.file_qc[qfc].proposal_revision_file_id}">{$list.file_qc[fqc].file_name}</a><br>
                  {/section}
                  </td>
               </tr>
{include file='common/div_footer.tpl'}
{include file='common/div_header.tpl' 
			div_title='Final Deliverables' 
			div_name='fdc'}
			<tr><td>
<textarea id='final_deliverable' name="final_deliverable"  cols="75" rows="20"></textarea>		
			</td></tr>
{include file='common/div_footer.tpl'}
{include file='common/div_header.tpl' 
			div_title='Services' 
			div_name='svc'}
			<tr><td>
                     <table align="center" width="100%">
                     {section name=main_service 
                              loop=$orglist}
                        <tr class="header1">
                           <td colspan="6"><strong>{$orglist[$smarty.section.main_service.iteration].group_description}</strong></td>
                        </tr>
                        {section name=sub_service 
                                 loop=$orglist[$smarty.section.main_service.iteration]}
                           <tr>
                              {section name=service 
                                       loop=$orglist[$smarty.section.main_service.iteration][$smarty.section.sub_service.iteration]}
                                 <td align="right">
                                 	<input type="checkbox" 
                     							 onclick="validate_service(this);"
                     							 id="S_{$orglist[$smarty.section.main_service.iteration][$smarty.section.sub_service.iteration][$smarty.section.service.iteration].service_id}"
                     							 name="S_{$orglist[$smarty.section.main_service.iteration][$smarty.section.sub_service.iteration][$smarty.section.service.iteration].service_id}" 
                     							 {$orglist[$smarty.section.main_service.iteration][$smarty.section.sub_service.iteration][$smarty.section.service.iteration].is_selected}>
                                 </td>
                                 <td align="left">{$orglist[$smarty.section.main_service.iteration][$smarty.section.sub_service.iteration][$smarty.section.service.iteration].service_description}</td>
                              {/section}
                           </tr>
                        {/section}
                     {/section}
                     </table>
                  </td>
               </tr>
 {include file='common/div_footer.tpl'}  
 {include file='common/div_header.tpl' 
			div_title='Internal Notes' 
			div_name='notes'}
{include file='quote/vw_comment.tpl'}
{include file='common/div_footer.tpl'}
</div>
   <table>
               <tr class="tab1">
                  <td colspan="4" align="center">
                     <strong>{$lang.REVIEW_PRICING_BEFORE_PROPOSAL}</strong>&nbsp;
                     <input type="checkbox" value="" name="review_discount">
                     {if $proposal.version > 1}
                     &nbsp;&nbsp;&nbsp;
                     <strong>Detailed Panel Calculation [Beta]</strong>&nbsp;
                     	{if $revision.panel_detail}
                     	<input type="checkbox" name="panel_detail" checked="checked">
                     	{else}
                     	<input type="checkbox" name="panel_detail">
                     	{/if}
                     {/if}
</table>                     
                    
                     <input type="button" 
                            onclick="window.location.href = '/quote/update/quote/{$proposal.proposal_id}';" 
                            value="Previous ">
                     &nbsp;&nbsp;
                     <input type="button" 
                            onclick="check(this.form) && this.form.submit();" 
                            value="Next ">
                     &nbsp;&nbsp;
                     <input type="button" 
                            onclick="CancelProposal({$proposal.proposal_id}, {$revision.proposal_revision_id|default:0});" 
                            value="Cancel">
               
         <input type="hidden" 
                name="account_id" 
                value="{$proposal.account_id}">
         <input type="hidden" 
                name="proposal_revision_id" 
                value="{$revision.proposal_revision_id}">
         <input type="hidden" 
                name="auto_submit" 
                value="0" 
                id="auto_submit">
</form>
{literal}
<script>
(function() {
    //Setup some private variables
    var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event;

        //The SimpleEditor config
        var myConfig = {
            height: '300px',
            width: '600px',
            dompath: true,
            focusAtStart: true
        };

    //Now let's load the SimpleEditor..
    var gcomment = new YAHOO.widget.SimpleEditor('general_comment', myConfig);
    gcomment.render();
    var qcomment = new YAHOO.widget.SimpleEditor('qualifying_criteria', myConfig);
    qcomment.render();
    var fcomment = new YAHOO.widget.SimpleEditor('final_deliverable', myConfig);
    fcomment.render();
})();
</script>
{/literal}
</body></html>