<?php 
/* 
* Smarty plugin 
* ------------------------------------------------------------- 
* Type:     modifier 
* Name:     number_format 
* File:     modifier.number_format.php 
* Purpose:  format currency amount 
* Input:    string: money value 
*           decimals: number of decimal places 
*           dec_point: string for decimal 
*           thousands_sep: string for thousands separation 
* Example:  {$value|number_format:2:".":","} 
* Author:   Nayana Hettiarachchi
*/ 

function smarty_modifier_number_format($string, $decimals=2, $dec_point=".", $thousands_sep=",") 
{ 
   if (is_numeric($string)) // check if it's a number 
   { 
      return number_format($string, $decimals, $dec_point, $thousands_sep); 
   } 
   else 
   { 
      return $string; 
   } 
} 

/* vim: set expandtab: */ 