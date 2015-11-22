<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty strip_tags modifier plugin
 *
 * Type:     modifier<br>
 * Name:     strip_tags<br>
 * Purpose:  strip html tags from text
 * @link http://smarty.php.net/manual/en/language.modifier.strip.tags.php
 *          strip_tags (Smarty online manual)
 * @param string
 * @param boolean
 * @return string
 * @author Nayana Hettiarachchi
 */
function smarty_modifier_strip_tags_selected($string, $replace_with_space = true)
{
    if ($replace_with_space) {
        $string  = preg_replace("'<style[^>]*>.*</style>'siU",'',$string);
        return  preg_replace('!<[^>]*?>!', ' ', $string);
    } else {
        $string  = preg_replace("'<style[^>]*>.*</style>'siU",'',$string);
        //return strip_tags($string);
        return strip_tags($string, "<p>,<table>,<tr>,<td>,<th>,<br>");
    }
}

/* vim: set expandtab: */