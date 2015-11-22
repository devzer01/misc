<div>Date Time</div>
         <div>Activity</div>
         <div>Actor &amp;/div>
         {foreach from=$audit_log item=log}
         	<div>{$audit->GetCreatedDate()}</div>
         	<div>{$log->GetCommentText()|nl2br}</div>
         	<div>{$item1->GetUser()->GetFirstName()} {$item1->GetUser()->GetLastName()}</div>
         {/foreach]
       	<div><textarea rows=5 cols=60 name='auditlog_comment'></textarea></div>

<script>
function saveComments() 
{
	//Account/SaveAccountLog
	
}
</script>
