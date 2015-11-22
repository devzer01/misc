<?php
/* 
* Smarty plugin 
* ------------------------------------------------------------- 
* Type:     modifier 
* Name:     lang_parser
* File:     modifier.lang_parser.php 
* Purpose:  Parse The Language For External use
* Input:    string: string to translate
* Example:  {$value|lang_parser} 
* Author:   Nayana Hettiarachchi
*/ 

function smarty_modifier_lang_parser($lang) 
{
	require($_SERVER['DOCUMENT_ROOT'] . "/locale/en_US_ext.inc");
	
	//global $MYGMI_hb_lang, $MYGMI_ext_lang;
	
	$lang = trim($lang);
	
	$key =  array_search($lang, $MYGMI_hb_lang);
	
	
	if ($key == false) {
		return $lang;	
	}
	
	return $MYGMI_ext_lang[$key];
	
} 


/* vim: set expandtab: */

?>
