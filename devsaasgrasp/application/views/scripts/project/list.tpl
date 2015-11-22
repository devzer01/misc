{include file='home/header.tpl}
<table style='width: 98%; background-color: white;'> 
<tr>
	<th>Alert</th>
	<th>Project</th>
	<th>Account</th>
	<th>Status</th>
</tr>
{foreach from=$projects item=project}
<tr id="{$project.pjm_id}">
<td>{$project.alert_level_description|escape}</td>
<td><a href='/Project/detail/id/{$project.id}'>{$project.id} - {$project.project_name|escape}</a></cell>
<td><a href='/Account/view/id/{$project.account_id}'>{$project.account_id|escape} - {$project.account_name|escape}</a></cell>
<td>{$project.pjm_status_description}</td>
</tr>
{/foreach}
</table>
{include file='home/footer.tpl}