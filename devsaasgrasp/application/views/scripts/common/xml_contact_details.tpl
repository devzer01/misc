<?xml version="1.0" encoding="utf-8"?>
<contacts>
{section name=id loop=$data}
	<contact contact_id="{$data[id].contact_id}" 
			   contact_title="{$data[id].contact_title}"
			   account_contact_id="{$data[id].account_contact_id}"
				contact_name="{$data[id].contact_first_name} {$data[id].contact_last_name}"
			   contact_email="{$data[id].contact_email}" />
{/section}
<meta target_id="{$meta.target_id}" />
</contacts>