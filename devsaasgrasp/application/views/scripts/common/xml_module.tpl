<?xml version="1.0" encoding="utf-8"?>
<modules>
{section name=id loop=$list}
	<module module_id="{$list[id].module_id}" 
			  module_description="{$list[id].module_description}" />
{/section}
<meta element="{$meta.element}" target="{$meta.target}" key="{$meta.key}" description="{$meta.description}" />
</modules>