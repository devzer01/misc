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

function smarty_modifier_url_encrypt($url) 
{
	return $url;
   //return "&".$url;
   //$e = new Encryption();
   //return $e->Encrypt($url);
} 


/* vim: set expandtab: */