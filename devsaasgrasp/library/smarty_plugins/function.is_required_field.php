<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {cycle} function plugin
 *
 * Type:     function<br>
 * Name:     cycle<br>
 * Date:     May 3, 2002<br>
 * Purpose:  cycle through given values<br>
 * Input:
 *         - name = name of cycle (optional)
 *         - values = comma separated list of values to cycle,
 *                    or an array of values to cycle
 *                    (this can be left out for subsequent calls)
 *         - reset = boolean - resets given var to true
 *         - print = boolean - print var or not. default is true
 *         - advance = boolean - whether or not to advance the cycle
 *         - delimiter = the value delimiter, default is ","
 *         - assign = boolean, assigns to template var instead of
 *                    printed.
 *
 * Examples:<br>
 * <pre>
 * {cycle values="#eeeeee,#d0d0d0d"}
 * {cycle name=row values="one,two,three" reset=true}
 * {cycle name=row}
 * </pre>
 * @link http://smarty.php.net/manual/en/language.function.cycle.php {cycle}
 *       (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com>
 * @author credit to Mark Priatel <mpriatel@rogers.com>
 * @author credit to Gerard <gerard@interfold.com>
 * @author credit to Jason Sweat <jsweat_php@yahoo.com>
 * @version  1.3
 * @param array
 * @param Smarty
 * @return string|null
 */
function smarty_function_is_required_field($params, &$smarty)
{
	
	$field_name = $params['name'];
	$validation = $params['validation'];
	$retval     = '';
	
	if (!isset($validation[$field_name])) 
		return $retval;
		
	$attr = $validation[$field_name];
	
	if ($attr['required']) {
		$retval = "* <input type='hidden' name='r_". $field_name ."' value='". $attr['fail_message'] ."'> ";
	}
    
    
    return $retval;
}

/* vim: set expandtab: */

?>
