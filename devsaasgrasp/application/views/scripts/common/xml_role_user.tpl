<?xml version="1.0" encoding="utf-8"?>
<users>
{section name=id loop=$data}
	<user user_id="{$data[id].login}" 
			   user_name="{$data[id].name}" />
{/section}
<meta element="{$meta.element}" target="{$meta.target}" key="{$meta.key}" description="{$meta.description}" />
</users>