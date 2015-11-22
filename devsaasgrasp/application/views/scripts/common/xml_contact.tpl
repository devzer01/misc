<?xml version="1.0" encoding="utf-8"?>
<accounts>
{section name=id loop=$data}
	<contact contact_id="{$data[id].contact_id}" 
			   contact_name="{$data[id].contact_first_name} {$data[id].contact_last_name}" />
{/section}
<meta element="{$meta.element}" target="{$meta.target}" key="{$meta.key}" description="{$meta.description}" />
</accounts>