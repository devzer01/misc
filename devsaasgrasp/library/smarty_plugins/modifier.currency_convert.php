<?php
/* 
* Smarty plugin 
* ------------------------------------------------------------- 
* Type:     modifier 
* Name:     currency_convert 
* File:     modifier.currency_convert.php 
* Purpose:  Convert a USD amount using specified exchange rate
* Input:    float: USD amount, float: exchange_rate, int $direction (1 for USD->foreign, -1 for foreign->USD;
* Example:  {$value|currency_convert} 
* Author:   Nayana Hettiarachchi
*/ 
//require_once($_SERVER['DOCUMENT_ROOT']."/class/encryption.class");

function smarty_modifier_currency_convert($usd_amount, $exchange_rate, $direction=1)
{	if ($direction==1)
	  return $usd_amount / $exchange_rate;
	elseif($direction==-1)
	  return $usd_amount * $exchange_rate;
	else return $usd_amount;
} 


/* vim: set expandtab: */

?>
