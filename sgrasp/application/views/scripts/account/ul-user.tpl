<ul class='contacts'>
{section name=contact loop=$data}
	<li class='contact'>
   	<div class='name'>{$data[contact].first_name} {$data[contact].last_name}</div>
    	<div class='email'>
    		<span class='informal'>{$data[contact].email_address}</span>
    	</div>
    	<input type='hidden' name='login' value='{$data[contact].user_id}'>
   </li>
{/section}
</ul>
<!-- remember to rename login to user_id -->
