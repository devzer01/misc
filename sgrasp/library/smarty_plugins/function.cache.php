<?php

function smarty_function_cache($params, &$smarty)
{  
  $version = smarty_function_svnrevision($smarty);
  echo preg_replace('!\.([a-z]+?)$!', ".v$version.\$1", $params['src']);
}

function smarty_function_svnrevision(&$smarty)
{	 
	//quick and dirty way to getting svn app revision number without depending on any extra php modules. 
	//@FIXME: This will read the /entries file for the revision each time it's invoked. While this file is small and the read is quick, we can do better

	$svn = file($_SERVER['DOCUMENT_ROOT'].'/.svn/entries');
	if (is_numeric(trim($svn[3]))) 
	{
		$version = $svn[3];
	} 
	else 
	{ 
		// pre 1.4 svn used xml for this file
		$version = explode('"', $svn[4]);
		$version = $version[1];    
	}

	return str_replace("\n", "",  $version);
}
?>