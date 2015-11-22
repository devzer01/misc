<?xml version="1.0" encoding="utf-8"?>
<account_sub_types>
{section name=id loop=$sub_type}
	<account_sub_type account_sub_type_id="{$sub_type[id].account_sub_type_id}" 
							account_sub_type_description="{$sub_type[id].account_sub_type_description}" />
{/section}
</account_sub_types>