<?xml version="1.0" encoding="utf-8"?>
<accounts>
{section name=id loop=$data}
	<account account_id="{$data[id].account_id}" 
			   account_name="{$data[id].account_name|escape:url}" />
{/section}
<meta element="{$meta.element}" target="{$meta.target}" key="{$meta.key}" description="{$meta.description}" />
</accounts>