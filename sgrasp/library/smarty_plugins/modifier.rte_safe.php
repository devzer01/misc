<?php
/* 
* Smarty plugin 
* ------------------------------------------------------------- 
* Type:     modifier 
* Name:     url_encrypt 
* File:     modifier.url_encrypt.php 
* Purpose:  Encrypt a given String to a pre defined key
* Input:    string: url to encrypt 
* Example:  {$value|url_encrypt} 
* Author:   Nayana Hettiarachchi
*/ 
//require_once($_SERVER['DOCUMENT_ROOT']."/class/encryption.class");

function smarty_modifier_rte_safe($strText) 
{
   $tmpString = $strText;
	
	//convert all types of single quotes
	$tmpString = str_replace(chr(145), chr(39), $tmpString);
	$tmpString = str_replace(chr(146), chr(39), $tmpString);
	$tmpString = str_replace("'", "&#39;", $tmpString);
	
	//convert all types of double quotes
	$tmpString = str_replace(chr(147), chr(34), $tmpString);
	$tmpString = str_replace(chr(148), chr(34), $tmpString);
//	$tmpString = str_replace("\"", "\"", $tmpString);
	
	//replace carriage returns & line feeds
	$tmpString = str_replace(chr(10), " ", $tmpString);
	$tmpString = str_replace(chr(13), " ", $tmpString);
	
	return $tmpString;
} 


/* vim: set expandtab: */