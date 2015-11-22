<?xml version='1.0' encoding='iso-8859-1'?>
<rows>     	
<head>
<column width="100" type="ed" align="right" color="white" sort="str">Choice ID</column>
<column width="100" type="ed" align="right" color="white" sort="str">name</column>
<column width="100" type="ed" align="right" color="white" sort="str">custom</column>
<settings>
<colwidth>px</colwidth>
</settings>
</head>
{foreach from=$dp item=d}
<row id="{$d.id}">
<cell>{$d.choiceid|escape}</cell>
<cell>{$d.name}</cell>
<cell>{$d.custom|escape}</cell>            
</row>
{/foreach}
</rows>