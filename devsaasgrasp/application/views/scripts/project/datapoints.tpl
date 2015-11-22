<?xml version='1.0' encoding='iso-8859-1'?>
<rows>     	
<head>
<column width="100" type="ed" align="right" color="white" sort="str">Label</column>
<column width="100" type="ed" align="right" color="white" sort="str">Qtext</column>
<column width="100" type="ed" align="right" color="white" sort="str">Type</column>
<settings>
<colwidth>px</colwidth>
</settings>
</head>
{foreach from=$dp item=d}
<row id="{$d.id}">
<cell>{$d.label|escape}</cell>
<cell>{$d.qtext|escape}</cell>
<cell>{$d.type|escape}</cell>            
</row>
{/foreach}
</rows>