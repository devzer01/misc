<ul class='contacts'>
{section name=account loop=$data}
	<li class='contact'>
   	<div class='name'>{$data[account].account_name}</div>
    	<input type='hidden' name='account_id' value='{$data[account].account_id}'>
   </li>
{/section}
</ul>