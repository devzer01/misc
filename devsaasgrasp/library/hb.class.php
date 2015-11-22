<?php


/**
* __autoload()
*
* @param string $class_name Name of The Class Being Loaded
* @return
* @since  - 18:49:07
*/
function __autoload($class_name)
{
	$base = dirname(__FILE__);
	
	$class_name = preg_replace("/_/", DIRECTORY_SEPARATOR, $class_name);
	$stage1 = $base . "/" . $class_name .".class.php";
	if (is_readable($stage1)) require_once($stage1);
	$stage2 = $base . "/" . $class_name .".class";
	if (is_readable($stage2)) require_once($stage2);
	$stage3 = $base . "/Hb/" . $class_name . ".class";
	if (is_readable($stage3)) require_once($stage3);
	return true;
}

//function CreateSmartyArray($rs,$index_field,$description)
//{
//   while($r = mysql_fetch_assoc($rs)) {
//      $data[$r[$index_field]] = $r[$description];
//   }
//
//   return $data;
//}

//require_once 'XML/RPC.php';

define ('COMMON_REPORTING_TYPE_FICTIONAL', 3);

/*
   $Id: functions.inc 96638 2008-07-29 06:20:57Z svithanapathirana $
   all business logic functions can be used in all modules
 */

require_once('db/userDB.class.php');

function array_clean(&$array) {
	
	foreach ($array as $index => $value) {
		unset($array[$index]);
	}
	
	return $array;
}

function array_remval($val, &$arr){
  $i = array_search($val,$arr);
  if($i===false)return false;
  
  $arr = array_merge(array_slice($arr, 0,$i), array_slice($arr, $i+1));
   
  return true;
}


/**
* Utf8SafeAscii()
*
* @param
* @param 
* @return
* @since  - 15:15:14
*/
function Utf8SafeAscii($text)
{
	$mapping = array(128 => '&#8364;',
                 130 => '&#8218;',
                 131 => '&#402;',
                 132 => '&#8222;',
                 133 => '&#8230;',
                 134 => '&#8224;',
                 135 => '&#8225;',
                 136 => '&#710;',
                 137 => '&#8240;',
                 138 => '&#352;',
                 139 => '&#8249;',
                 140 => '&#338;',
                 142 => '&#381;',
                 145 => '&#8216;',
                 146 => '&#8217;',
                 147 => '&#8220;',
                 148 => '&#8221;',
                 149 => '&#8226;',
                 150 => '&#8211;',
                 151 => '&#8212;',
                 152 => '&#732;',
                 153 => '&#8482;',
                 154 => '&#353;',
                 155 => '&#8250;',
                 156 => '&#339;',
                 158 => '&#382;',
                 159 => '&#376;');
	
	foreach ($mapping as $ascii => $html) {
		$text = preg_replace("/". chr($ascii)  ."/", $html, $text);
	}

	return $text;
	
}

/**
* array_diff_key()
*
* @param -
* @param - 
* @author - harsha
* @since  - Wed Dec 13 10:58:02 IST 2006 
*/
//function array_diff_key($array1, $array2)
//{
//	return  array_flip(array_diff(array_flip($array1), array_flip($array2)));	
//	
//}
/// OODE BLOCK COMMENTED ABOVE FOR PHP 5.1.x up compatibility

function GetPHPVersion() 
{
	$version = explode('.', phpversion());
	return $version[0] . $version[1];
}

function is_php50() 
{ 
	return GetPHPVersion() == 50; 
}

function is_php51() 
{ 
	return GetPHPVersion() == 51; 
}

function is_php52() 
{ 
	return GetPHPVersion() == 52; 
}


/**
* isExternalUser()
*
* @param
* @param 
* @return
* @since  - 14:59:59
*/
function isExternalUser()
{
	return (isset($_SESSION['user_type_id']) && $_SESSION['user_type_id'] == USER_TYPE_EXTERNAL) ? true : false;	
}

/**
* isInternalUser()
*
* @param
* @param 
* @return
* @since  - 15:00:15
*/
function isInternalUser()
{
	return (isset($_SESSION['user_type_id']) && $_SESSION['user_type_id'] == USER_TYPE_INTERNAL) ? true : false;
}

/**
* isExternalAdmin()
*
* @param
* @param 
* @return
* @since  - 15:00:27
*/
function isExternalAdmin()
{
	return (isset($_SESSION['user_portal_access_type_id']) && $_SESSION['user_portal_access_type_id'] == 3) ? true : false;
}

/**
* GetExternalPrimaryAccount()
*
* @param
* @param 
* @return
* @since  - 15:00:37
*/
function GetExternalPrimaryAccount()
{
	return (isset($_SESSION['user_primary_account_id']) && is_numeric($_SESSION['user_primary_account_id'])) ? $_SESSION['user_primary_account_id'] : 0;
}

/**
* GetExternalContactId()
*
* @param
* @param 
* @return
* @since  - 15:04:42
*/
function GetExternalContactId()
{
	return (isset($_SESSION['user_primary_contact_id']) && is_numeric($_SESSION['user_primary_contact_id'])) ? $_SESSION['user_primary_contact_id'] : 0;
}

/**
* ArrayMergeKeepKeys()
*
* @param
* @param 
* @return
* @since  - 08:24:08
*/
function ArrayMergeKeepKeys()
{
	$arg_list = func_get_args();
 
	foreach((array)$arg_list as $arg){
   	foreach((array)$arg as $K => $V){
      	$Zoo[$K]=$V;
    	}
  	}
   return $Zoo;
}

/**
* GetLastDateOfMonth()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Nov 03 11:52:52 PST 2005 */
function GetLastDateOfMonth($timestamp) {
    $months = array(0, 31, 20, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    $month =  date("n", $timestamp);
    $year =  date("Y", $timestamp);

    if ($month != 2) {
       return $months[$month];
    }

    if (($year % 4) == 0 && ($year % 400) != 0) {
       return 29;
    }

    return 28;

}


/**
 * Formats filesizes into 'human-readable' format.
 *
 * Currently doesn't do anything useful, other than adding a 'B'.
 */
function FormatFilesize ($size) {
	return $size."B";
}


/**
 * Loads data from a mysql result set into corresponding smarty variables.
 *
 * If a result set is passed with columns 'first_name', 'last_name', and 'email_address', this function
 *  will assign corresponding smarty variables that are arrays named 'first_names', 'last_names', and
 *  'email_addresss' (sic). An 's' is concatenated to the column names to make the smarty names. If you
 *  want different names, use the var_maps parameter.
 *
 * @param	mixed	$rs			The mysql result set to parse.
 * @param	object	$smarty		The document's smarty object.
 * @param	array	$var_maps	An optional array of mysql column names to their corresponding smarty array names.
 * @param	array	$func_maps	An optional array of mysql column names and the functions to map onto them.
 * @return
 * @access
 * @since	HEM 1.0, BRD 1.5
 */
function DBToSmarty($rs, &$smarty, $var_maps = null, $func_maps = null) {
	while ($row = mysql_fetch_assoc($rs)) {
		foreach ($row as $key=>$val) {
			if (isset($var_maps[$key])) {
				$x[$var_maps[$key]][] = (isset($func_maps[$key]) ? call_user_func($func_maps[$key], $val) : $val);
			} else {
				$x[$key.'s'][] = (isset($func_maps[$key]) ? call_user_func($func_maps[$key], $val) : $val);
			}
		}
	}
	if (!is_null($x)) {
		foreach ($x as $key=>$val) {
			$smarty->assign($key, $x[$key]);
		}
	}
}



/**
* Validate()
*
* @param	array	$o		The parsed url arguments
* @return
* @throws
* @access
* @global
* @since  - Tue Jul 18 15:33:44 PDT 2005
*/
function Validate($o) {
	$dev = 0;

	if ($dev) {
		echo "<pre>";
		print_r($o);
		echo "</pre>";
	}

	foreach($o as $key => $val) {
		if (preg_match("/^[a-z]_.+/", $key)) {
			$type = substr($key,0,1);
			$field = substr($key, 2);
			if ($dev) echo $key."<br>";

			switch($type) {
				case "r":
					if (!isset($o[$field]) || $o[$field] == '') {
						$msg .= $val."<br>";
					}
					break;
				case "i":
					if (!preg_match("/(^$|^[0-9]*\.?[0-9]+$)/", $o[$field]) && isset($o[$field]) && $o[$field] != "") {
						$msg .= $val."<br>";
					}
					break;
				case "e":
					$good = checkEmail($o[$field]);
					if (!$good) {
						$msg .= $val."<br>";
					}
					break;
				case "d":
					if (!preg_match("/[0-9]{4}\-[0-1][0-9]\-[0-3][0-9]/", $o[$field]) && isset($o[$field]) && $o[$field] != "") {
						$msg .= $val."<br>";
					}
					break;
				case "m":
					if (!preg_match("/[0-9]+\.[0-9]{2}$/", $o[$field]) && isset($o[$field]) && $o[$field] != "") {
						$msg .= $val."<br>";
					}
					break;
				case "n":
					$msg .= "nobody knows what a n_check is... tell a developer if you see this message.<br>";
					break;
			}

		}
	}
	if ($dev) die($msg);
	if ($msg != "") {
		$_SESSION['msg'] = $msg;
		return false;
	}

	return true;
}


function checkEmail($email) {
 // checks proper syntax

 //this regexp is about 3 times as fast as the next one (but slightly less accurate)
// $m = preg_match("/^( [a-zA-Z0-9] )+( [a-zA-Z0-9\._-] )*@( [a-zA-Z0-9_-] )+( [a-zA-Z0-9\._-] +)+$/", $email);
 $m = preg_match("/^([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}$/", $email);
// $m = preg_match("/^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/" , $email);
 if($m) {
  // gets domain name
  list($username,$domain)=split('@',$email);
  // checks for if MX records in the DNS
  if(!checkdnsrr($domain, 'MX')) {
   return false;
  }
  // attempts a socket connection to mail server
  if(!fsockopen($domain,25,$errno,$errstr,30)) {
   return false;
  }
  return true;
 }
 return false;
}

/**
* GetReportees()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Jun 21 14:34:37 PDT 2005
*/
function GetReportees($login, $count = 1)
{
   $user = new userDB();
   $list = array();

   if ($count != 1) {
      $list[] = $login;
   }

   $rs = $user->GetReporteeByLogin($login);

   while ($r = mysql_fetch_assoc($rs)) {
      $list[] = $r['reporting_login'];
      //dont recursive for finctional reporting releationships
      if ($r['reporting_type_id'] != COMMON_REPORTING_TYPE_FICTIONAL) {
         $list2 = GetReportees($r['reporting_login']);
         $list = array_merge($list, $list2);
      }


   }

   return $list;
   //find the direct reports
   //find the reports of the reportee if any
   //we need a revusrive function here

}

/**
* PrepareInString()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Jun 21 18:03:30 PDT 2005
*/
function PrepareInString($list)
{
   $out = '';
   for ($i=0; $i< count($list); $i++) {
      $out .= $list[$i].",";
   }

   return ereg_replace(",$","",$out);
}

/**
* SendEmail()
* move to common
* @param $from string The outbount from email
* @param $recepients array Must be an array of receipeints format array(0 => array('name' => 'Display Name', 'email' => 'Email Address')
* @param $subject string The Subjectline
* @param $msg string the actuall email message
* @param $headers array additional headers for the mesasage
* @return
* @throws
* @access
* @global
* @since  - Fri Jun 24 11:16:20 PDT 2005
*/
function SendEmail($from, $recepients, $subject, $msg, $headers = null)
{
   global $cfg;
   $rcpt_output = print_r($recepients,true);

   //prepare headers
   for ($i=0; $i < count($headers); $i++) {
      $header .= "\n".$headers[$i];
   }

   if (ISDEV) {
      mail(RCPT,$subject,$msg."\n\n".$rcpt_output,"Content-type: text/html\nFrom: ".$from.$header);
   } else {
      //send out to our debug
      //mail('Dev & Prod <miops@cs.gmi-mr.com>',$subject,$msg."\n\n".$rcpt_output,"Content-type: text/html\nFrom: ".$from.$header);

      //reset the array pointer
      reset($recepients);

      //send the initial mailout we need to add function so it will set the timezone to the users local timezone
      for ($i =0; $i < count($recepients); $i++){
         mail($recepients[$i]['name'].' <'.$recepients[$i]['email'].'>',$subject,$msg,"Content-type: text/html\nFrom: ".$from.$header);
      }
   }
}

/**
* CreateSmartyArray()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function CreateSmartyArray($rs,$index_field,$description)
{
	$data = array();
	
   while($r = mysql_fetch_assoc($rs)) {
      $data[$r[$index_field]] = $r[$description];
   }

   return $data;
}

/**
* PrepareSmartyArray()
*
* @param
* @param - reference to timelineid
* @return
* @throws
* @access
* @global
* @since  - Mon Jun 06 14:43:54 PDT 2005
*/
function PrepareSmartyArray($rs, $key = 'auto')
{
	$data = array();
	
   while ($r = mysql_fetch_assoc($rs)) {
   	if ($key == 'auto') {
   		$data[] = $r;	
		} else {
			$data[$r[$key]] = $r;
		}
      
   }
   return $data;
}

/**
* BuildPageSize()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun Jul 24 15:39:51 PDT 2005
*/
function BuildPageLimit(&$o, &$start, &$page_size)
{
   if (isset($o['page_size']) && $o['page_size'] != '') {
      $page_size = $o['page_size'];
   }

   //page size block
   if (isset($o['start']) && $o['direction'] == 'up') {
      $o['start'] = $o['start'] + $page_size;
      $start = $o['start'];
   } elseif (isset($o['start']) && $o['direction'] == 'down') {
      $o['start'] = $o['start'] - $page_size;
      $start = $o['start'];
   } elseif (!isset($o['start'])) {
      $o['start'] = $start;
   } else {
      $start = $o['start'];
   }

   $o['current_page'] = (int) $start / $page_size;

   if ($start < 0) {
      $start = 0;
      $o['start'] = 0;
   }

   $o['s'] = 1;
   //end page size block
}

/**
* GetFileData()
*
* @param - string $file name of the file input object
* @param -
* @return - associative array data, name, size, type, type_id, title
* @throws
* @access
* @global
* @since  - Thu Jul 07 10:24:39 PDT 2005
*/
function GetFileData($file)
{
   global $o;
   $common = new commonDB();

   if ($_FILES[$file]['size'] == 0) {
      return false;
   }

   $p = fopen($_FILES[$file]['tmp_name'], 'r');
   $f['data'] = fread($p, filesize($_FILES[$file]['tmp_name']));
   fclose($p);

   $f['name'] = $_FILES[$file]['name'];
   $f['size'] = $_FILES[$file]['size'];
   $f['type'] = $_FILES[$file]['type'];

   $f['type_id'] = $common->GetFileTypeIdByName($f['type']);

   $f['title'] = ($o[$file.'_title'] != '') ? $o[$file.'_title'] : $f['name'];

   if(!$f['type_id']) 
   {
      $common->SetFileType($f['type'], $_SESSION['admin_id']);
      $f['type_id'] = $common->lastID;
   }

   return $f;

}

/**
* SendMimeMail()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Nov 22 21:27:16 PST 2005
*/
function SendMimeMail($from, $return_path, $subject, $body, $files, $rcpt )
{

   // Instantiate a new HTML Mime Mail object
   $mail = new htmlMimeMail();

   // Set the From and Reply-To headers
   $mail->setFrom($from);
   $mail->setReturnPath($return_path);

   // Set the Subject
   $mail->setSubject($subject);

   // Set the body
   $mail->setHtml($body);

   foreach ($files as $file) {
   	$attachment = $mail->getFile($file['file_name']);
   	$mail->addAttachment($attachment, $file['file_title']);
   }

   if (ISDEV) {
      $result = $mail->send(array(RCPT));
   } else {
      $result = $mail->send($rcpt);
   }
}

/**
* RteSafe()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 04 16:06:24 PST 2006
*/
function RteSafe($strText)
{
	//returns safe code for preloading in the RTE
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

/**
* CreatePdf()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Dec 23 22:47:18 PST 2005
*/
function CreatePdf($in_file, $out_file, $header = array(), $footer = array())
{

   $default_domain = SERVER_NAME;

   $pdf = new HTML_ToPDF($in_file, $default_domain, $out_file);

   $pdf->setUseCSS(true);
   $pdf->setUseColor(true);
   $pdf->setUnderlineLinks(true);

   foreach ($footer as $align => $text) {
      $pdf->setFooter($align, $text);
   }
   
   foreach ($header as $al => $tx) {
   	$pdf->setHeader($al, $tx);
   }

   $result = $pdf->convert();

   // Check if the result was an error
   if (PEAR::isError($result)) {   	
      die($result->getMessage());
      return false;
   }

   //now we can save the file to the database if we wanted
   //if we save the pdf in the database then we should keep a timestamp of the generated time and
   //if any changes were made to pricing we will need to generate a new one
}

/**
* HBRPCCall()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 25 10:59:33 PST 2006
*/
function HBRPCCall($module_code, $function_name, $params)
{
	CleanUnsupportParams($params);
	
	global $cfg;
   $msg['module_code'] = $module_code;
   $msg['data']        = $params;
//   $msg['function_name'] = $function_name;
   $msg['function_name'] = strtoupper($module_code) . "_" . $function_name;
	
   //$request = array(ProcessStruct($msg));
   //$rpc_msg = new XML_RPC_Message('HBRPC.CallModule', $);
	//print_r($rpc_msg);
   //$c = new commonDB();
   //$module_server = $c->GetModuleServerByModuleCode($module_code);

   //if ($module_server == 'localhost') {
   	//@runkit_function_remove("hbrpc_getattrvalues");
   //	@runkit_function_remove("hbrpc_getportlet");
      require_once("api/functions.inc");
      

      return CallModule($msg);

//   } else {
//
//      $cli = new XML_RPC_Client('/api/index.php', $module_server);
//      $cli->setDebug(0);
//
//      $resp = $cli->send($rpc_msg);
//
//      if (!$resp) {
//         echo 'Communication error: ' . $cli->errstr;
//         return false;
//      }
//   }
//
//   if (!$resp->faultCode()) {
//      $val = $resp->value();
//      return DeconStruct($val->scalarval());
//   } else {
//      echo 'Fault Code: ' . $resp->faultCode() . "\n";
//      echo 'Fault Reason: ' . $resp->faultString() . "\n";
//   }

}

/**
* ProcessStruct()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 25 09:08:20 PST 2006
*/
function ProcessStruct($array)
{
   if (!is_array($array)) {
      return new XML_RPC_Value('Empty', 'string');  
   }
   
   foreach ($array as $key => $value) {
   	if (is_array($array[$key])) {
   	  $x[$key] = ProcessStruct($array[$key]);
   	} else {
   	   $x[$key] = GetXMLRPCValueObject($array[$key]);
   	}
   }
   $obj = new XML_RPC_Value($x, 'struct');
   return $obj;
}

/**
* GetXMLRPCValueObject()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 25 07:56:33 PST 2006
*/
function GetXMLRPCValueObject($value)
{
   $type = 'string';
   if (gettype($value) == 'integer')
      $type = 'int';

   $obj = new XML_RPC_Value($value, $type);
   return $obj;
}

/**
* DeconStruct()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 25 11:05:30 PST 2006
*/
function DeconStruct($resp)
{
//   if (!is_array($resp)) {
//      return "Empty";
//   }
   
   foreach ($resp as $key => $value) {
      if (is_object($resp[$key])) {
         $val[$key] = $resp[$key]->scalarval();
         if (is_array($val[$key])) {
            $val[$key] = DeconStruct($val[$key]);
         }
      }
   }

   return $val;

}

/**
* DisplayHeader()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function DisplayHeader($module_name, $module_code, $action = '', $display_menu = 1, $send_output = 1)
{
   global $version, $build, $smarty; //we need globals here for our configs and version stuff
   $meta['module_name'] = $module_name;
   $meta['module_code'] = $module_code;
   $meta['version'] = $version;
   $meta['build'] = $build;
   $meta['action'] = $action;
   $meta['css_file'] = 'netmr50.css'; //default
   $meta['display_menu'] = $display_menu;
   
   if (isset($_SESSION['css_file']) && $_SESSION['css_file'] != '') {
   	$meta['css_file'] = $_SESSION['css_file']; //default
	}
	
	$ext_template_version = '2';
	
	/* work around for the absence of template_dir inside smarty */
	$smarty->assign('template_dir', $smarty->template_dir); 
	
	$menu_name = 'common/header.tpl';
	
	if ($_SESSION['user_type_id'] == 2) {
	   
	   $_SESSION['is_vendor'] = 0;
	   
	   require_once($_SERVER['DOCUMENT_ROOT'] .'/class/dbClass/accountDB.class');
	   //require_once($_SERVER['DOCUMENT_ROOT'] .'/app/acm/constant.inc'); //Rmoved as per HB-402
	   //require_once($_SERVER['DOCUMENT_ROOT'] .'/common/constant.inc'); //Rmoved as per HB-402
	   
	   require_once($_SERVER['DOCUMENT_ROOT'] .'/class/rpc/magpierss/rss_fetch.inc');

	   $a = new accountDB();
	   $a->SetAccountId($_SESSION['user_primary_account_id']);
	   
	   if ($a->isVendor()) {
	      $_SESSION['is_vendor'] = 1;
	      $menu_name = 'common/header_client.tpl';
	      
      } else {
	   
   	   $hbrss = Hb_Util_Rss::GetInstance(RSS_FEED_MYGMI_ADS);
   	   $rss = new MagpieRSS($hbrss->content);
   	   $rss = array_slice($rss->items, rand(0, count($rss->items) - 1), 1);
   	   $smarty->assign('rss_feed_1', $rss);
   	   
   	   $hbrss2 = Hb_Util_Rss::GetInstance(RSS_FEED_MYGMI_OFFICE);
   	   $xml = new SimpleXMLElement($hbrss2->content);
   
   	   $smarty->assign('rss_office', $xml);
   	   
   	   $ac['contacts']  = PrepareSmartyArray($a->GetAllAccountContactsDetails(1));
   	   $ac['user']      = PrepareSmartyArray($a->GetAccountUsers());
   	
   	   $smarty->assign('ac', $ac);
   		$menu_name = 'common/ext/v'. $ext_template_version .'/header.tpl';
      }
	}

   $smarty->assign('menu', $meta);
   
   if ($send_output == 1) {
   	$smarty->display($menu_name);
   	//$smarty->display('common/header.tpl');
   	return true;	
   }
   
   return $smarty->fetch($menu_name);
	//return $smarty->fetch('common/header.tpl');
   
}

/**
* DisplayFooter()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function DisplayFooter()
{
   global $smarty;
   
   $template = 'common/footer.tpl';
   
   $ext_template_version = 2;
   
   if ($_SESSION['user_type_id'] == 2 && $_SESSION['is_vendor'] == 0) {
   	$template = 'common/ext/v'. $ext_template_version .'/footer.tpl';	
	}
   
   $smarty->display($template);
   return true;
}

/**
* LookupAccount()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 23 16:44:03 PST 2006
*/
function LookupAccount($o)
{
	global $smarty;

	if (is_numeric($o['value'])) {
	   $data = HBRPCCall("acm", "GetAccountById", array('account_id' => $o['value']));
   } else {
      $data = HBRPCCall("acm", "GetAccountByName", array('account_name' => $o['value']));   
   }

	
	$meta['target'] = $o['target'];
	$meta['key']    = $o['key'];
	$meta['description'] = $o['description'];
	$meta['element'] = $o['element'];

	$smarty->assign('data', $data);
	$smarty->assign('meta', $meta);

	header("Content-type: text/xml");
	$smarty->display('common/xml_account.tpl');

	return true;
}

/**
* LookupAccountContact()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since Mon Dec 31 08:25:23 PST 2008
*/
function LookupAccountContact($o)
{
	global $smarty;
	
	$data = HBRPCCall("acm", "GetAccountContactDetails", array('account_id' => $o['value2'], 'contact_id' => $o['value'] ));
	
	$smarty->assign('data', $data);
	$smarty->assign('meta', $meta);

	header("Content-type: text/xml");
	$smarty->display('common/xml_account_contact.tpl');

	return true;
} 



/**
* LookupContact()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 24 10:18:27 PST 2006
*/
function LookupContact($o)
{
	global $smarty;

	$data = HBRPCCall("acm", "GetAccountContacts", array('account_id' => $o['value']));
	$meta['target'] = $o['target'];
	$meta['key']    = $o['key'];
	$meta['description'] = $o['description'];
	$meta['element'] = $o['element'];

	$smarty->assign('data', $data['data']);
	$smarty->assign('meta', $meta);

	header("Content-type: text/xml");
	$smarty->display('common/xml_contact.tpl');

	return true;
}


function LookupContactDetails($o)
{
	global $smarty;

	$contact_ids = array();
	
	$contact_ids = explode(",", $o['contact_ids']);
	
	foreach ($contact_ids as $contact_id) {
		
		if (!is_numeric($contact_id)) continue;
		
		$data = HBRPCCall("acm", "GetAccountContactDetails", 
			array(
				'account_id' => $o['account_id'],
				'contact_id' => $contact_id
			)
		);
	
		$contact[] = $data;	
	}
	
	$meta['target_id'] = $o['target_id'];
	
	$smarty->assign('data', $contact);
	$smarty->assign('meta', $meta);

	header("Content-type: text/xml");
	$smarty->display('common/xml_contact_details.tpl');

	return true;
}

/**
* GetMimeParts()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 27 07:31:18 PST 2006
*/
function GetMimeParts($object, &$msg)
{
	$ctype=trim($object->ctype_primary)."/".trim($object->ctype_secondary);
	
	if ($object->disposition == 'attachment') {
		
		switch ($ctype) {
			case "image/jpeg":
			case "image/gif":
				$name = trim($object->headers['name']);
				$cid = trim($object->headers['content-id']);
				$cid = str_replace("<","",$cid);
				$cid = str_replace(">","",$cid);
				if(empty($name))
					trim(strtok($object->headers['content-type'],"="));
				$name = trim(strtok("=\""));
				$msg['file'][] = array('file_name' => $name, 'data' => base64_encode($object->body));
				break;

			default:
				$name=trim($object->headers['name']);
				if(empty($name))
					trim(strtok($object->headers['content-type'],"="));
				$name = trim(strtok("=\""));
				$msg['file'][] = array('file_name' => $name, 'data' => base64_encode($object->body));
				break;
			
		}
		
	} else {
		switch($ctype)
		{
			case "text/html":
				$msg['body']['html'] = $object->body;
				break;

			case "text/plain":
				$enc = $object->headers['content-transfer-encoding'];
				$msg['body']['text'] = $object->body;
				break;
		}
	}
	
	return $msg;
}

/**
* ParseMimeObject()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 27 07:38:28 PST 2006
*/
function ParseMimeObject($obj, &$msg)
{
	if (isset($obj->parts)) {
		foreach ($obj->parts as $part) {
			ParseMimeObject($part, $msg);
		}
	} else {
		GetMimeParts($obj, $msg);
	}
	return $msg;
}

/**
* GetTimeZone()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function GetTimeZone($o = array())
{
   $timezone = '';
   $user = new userDB();
   if (isset($o['time_zone_id']) && $o['time_zone_id'] != '') {
      $timezone = ereg_replace('\.',':',$user->getTimeZone($o)); //replace the dot with : for timestamping
   } else {
      $timezone = ereg_replace('\.',':',$user->getDefaultTimeZone(array('login' => $_SESSION['admin_id']))); //replace the dot with : for timestamping
   }

   if (preg_match('/^[0-9]/',$timezone)) {
      $timezone = "+".$timezone;
   }

   return $timezone;
}

/**
* DisplayProgressBar()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Mar 09 11:00:01 PST 2006
*/
function DisplayProgressBar($width = 150, $height = 15)
{
	global $smarty;

    $meta['region_width'] = $width - 2;
    $meta['region_height'] = $height - 2;

    $meta['status_pos'] = $width + 5;
    $meta['block_size'] = ($width - 2) / 100;
    $meta['width'] = $width;
    $meta['height'] = $height;

    $smarty->assign('meta', $meta);
    $smarty->display('common/vw_progress_bar.tpl');

    flush();
}

/**
* SetProgress()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Mar 09 11:07:33 PST 2006
*/
function SetProgress($percent_completed)
{
	 print <<<END
<script>setProgress({$percent_completed});</script>
END;
    flush();
}

define ('SUNDAY', 0);
define ('MONDAY', 1);
define ('TUESDAY', 2);
define ('WEDNESDAY', 3);
define ('THURSDAY', 4);
define ('FRIDAY', 5);
define ('SATURDAY', 6);

/**
* GetLastDayOfWeek()
*
* @param  integer $timestamp - current time
* @param  integer $day_of_week - the day we want in last week (0=Sunday, 6=Saturday)
* @return integer the day of last (Mon,Tue,Wed...)
* @throws
* @access
* @global
* @since  - 2006-01-18 TChan */
function GetLastDayOfWeek($timestamp, $day_of_week)
{
   $current_day = gmdate('w', $timestamp);

   if( $current_day <= $day_of_week ){
      return $timestamp - ($current_day + 7 - $day_of_week)*60*60*24;
   } else {
      return $timestamp - ($current_day - $day_of_week )*60*60*24;
   }
}


/**
* SetTimeOfDate()
*
* @param  integer $timestamp - the date/time to be modified
* @param  integer $hour
* @param  integer $minute
* @param  integer $second
* @return integer Unix timestamp with the time portion modified; date is the same
* @throws
* @access
* @global
* @since  - 2006-01-18 TChan */
function SetTimeOfDate($timestamp, $hour, $minute, $second)
{
   // note: GMT function is used for safety in case server's timezone is not  GMT
   $date = gmdate("Y-m-d",$timestamp);
   $time = sprintf("%2d:%2d:%2d", $hour, $minute, $second);
   return strtotime("$date $time GMT");
}

/**
* SetTimeToZero()
*
* @param  integer $timestamp - the date/time to be modified
* @return integer Unix timestamp with the time portion set to 00:00:00; date is the same
* @throws
* @access
* @global
* @since  - 2006-01-18 TChan */
function SetTimeToZero($timestamp)
{
   return SetTimeOfDate($timestamp, 0, 0, 0);
}

/**
* CleanUnsupportParams()
*
* @param mixed/array $params parameters to be clean up before sending to XML_RPC_Message()
* @return
* @throws
* @access
* @global
* @since - 2006-03-14 TChan
*/
function CleanUnsupportParams(&$params){
   if (!is_array($params)){
      $params = preg_replace('/[\x80-\xFF]/', " ", $params);
      return;
   }

   foreach ($params as $key => $value){
      if (is_array($value)){
         // if it is an empty array, set it to array(0) since XML_RPC_Message does not support array()
         if (!count($value)){
            $params[$key] = array(0);
         // if the array has elements, do a recursive cleanup
         } else {
            CleanUnsupportParams($params[$key]);
         }
      } else {
         $params[$key] = preg_replace('/[\x80-\xFF]/', "?", $value);

      }
   }
}

/**
* DisplayNoDataImage()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 03 17:40:52 PST 2006
*/
function DisplayNoDataImage($width = 300, $height = 100)
{

	// Setup a basic canvas we can work
	$g = new CanvasGraph($width,$height,'auto');
	$g->SetMargin(5,11,6,11);
 	$g->SetShadow();
 	$g->SetMarginColor("teal");

	 // We need to stroke the plotarea and margin before we add the
	 // text since we otherwise would overwrite the text.
	 $g->InitFrame();
	
	 // Draw a text box in the middle
	 $txt="No Data to Display";
	 $t = new Text($txt,$width/2,$height/2);
	 $t->SetFont(FF_ARIAL,FS_BOLD,10);
	
	 // How should the text box interpret the coordinates?
	 $t->Align('center','center');
	
	 // How should the paragraph be aligned?
	 $t->ParagraphAlign('center');
	
	 // Add a box around the text, white fill, black border and gray shadow
	 $t->SetBox("white","black","gray");
	
	 // Stroke the text
	 $t->Stroke($g->img);
	
	 // Stroke the graph
	 $g->Stroke();
	
	 return true;
}

function in_array_any($needle, $haystack, $strict=false)
{
   if (is_array($needle)) {
      foreach($needle AS $value) {
         if (in_array($value, $haystack, $strict)) {
            return true;
         }
      }
      return false;
   } else {
      return in_array($needle, $haystack, $strict);
   }
}

/**
* GetSmartyObject()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 22 10:37:00 PST 2006
*/
function &GetSmartyObject()
{
   require_once('/usr/local/lib/php/Smarty/Smarty.class.php');
   global $cfg;
   $smarty = new Smarty();
   
   $smarty->template_dir = '/var/www/smarty/' . $servername . '/templates';
   $smarty->compile_dir = '/var/www/smarty/' . $servername . '/templates_c';
   $smarty->cache_dir = '/var/www/smarty/' . $servername . '/cache';
   $smarty->config_dir = '/var/www/smarty/' . $servername . '/configs';
   
   
   $smarty->plugins_dir = array('plugins', $cfg['base_dir'].'/include/smarty_plugins');
   
   //require_once ($smarty->_get_plugin_filepath('modifier', 'url_encrypt'));
   
   //$smarty->register_modifier('url_encrypt', 'url_encrypt');
   
   
   $smarty->compile_check = TRUE;
   $smarty->force_compile = TRUE;
   
   return $smarty;
   
}

/* new layout */

class Common {
	
	protected $__o = null;
	
	protected $__text = '';
	
	protected $smarty = null;
	
	protected $encrypt = null;
	
	protected $list = array();
	
	/**
	* __construct()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 21:14:10
	*/
	function __construct()
	{
		
	}
	
	/**
	* LoadSmarty()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 21:15:36
	*/
	protected function LoadSmarty()
	{
		global $smarty;
		
		$this->smarty = $smarty;

//		$this->smarty->template_dir = '/var/www/smarty/' . $servername . '/templates';
//		$this->smarty->compile_dir  = '/var/www/smarty/' . $servername . '/templates_c';
//		$this->smarty->cache_dir    = '/var/www/smarty/' . $servername . '/cache';
//		$this->smarty->config_dir   = '/var/www/smarty/' . $servername . '/configs';
//		$this->smarty->plugins_dir  = array('plugins', $cfg['base_dir'].'/include/smarty_plugins');
//
//		$this->smarty->compile_check = TRUE;
//		$this->smarty->force_compile = false;
	}
	
	/**
	* LoadEncryption()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - 21:19:20
	*/
	protected function LoadEncryption()
	{
		$this->encrypt = new Encryption();
	}
	
	//protected $db = 
	
/**
* SetParams()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Apr 17 14:42:03 PDT 2006
*/
function SetParams($o)
{
	$this->__o = $o;
}
	
	/**
* GetLastDateOfMonth()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Nov 03 11:52:52 PST 2005 */
function GetLastDateOfMonth($timestamp) {
    $months = array(0, 31, 20, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    $month =  date("n", $timestamp);
    $year =  date("Y", $timestamp);
    
    if ($month != 2) {
       return $months[$month];
    }

    if (($year % 4) == 0 && ($year % 400) != 0) {
       return 29;
    }

    return 28;

}


/**
 * Formats filesizes into 'human-readable' format.
 *
 * Currently doesn't do anything useful, other than adding a 'B'.
 */
function FormatFilesize ($size) {
	return $size."B";
}


/**
 * Loads data from a mysql result set into corresponding smarty variables.
 *
 * If a result set is passed with columns 'first_name', 'last_name', and 'email_address', this function
 *  will assign corresponding smarty variables that are arrays named 'first_names', 'last_names', and
 *  'email_addresss' (sic). An 's' is concatenated to the column names to make the smarty names. If you
 *  want different names, use the var_maps parameter.
 *
 * @param	mixed	$rs			The mysql result set to parse.
 * @param	object	$smarty		The document's smarty object.
 * @param	array	$var_maps	An optional array of mysql column names to their corresponding smarty array names.
 * @param	array	$func_maps	An optional array of mysql column names and the functions to map onto them.
 * @return
 * @access
 * @since	HEM 1.0, BRD 1.5
 */
function DBToSmarty($rs, &$smarty, $var_maps = null, $func_maps = null) {
	while ($row = mysql_fetch_assoc($rs)) {
		foreach ($row as $key=>$val) {
			if (isset($var_maps[$key])) {
				$x[$var_maps[$key]][] = (isset($func_maps[$key]) ? call_user_func($func_maps[$key], $val) : $val);
			} else {
				$x[$key.'s'][] = (isset($func_maps[$key]) ? call_user_func($func_maps[$key], $val) : $val);
			}
		}
	}
	if (!is_null($x)) {
		foreach ($x as $key=>$val) {
			$smarty->assign($key, $x[$key]);
		}
	}
}



/**
* Validate()
*
* @param	array	$o		The parsed url arguments
* @return
* @throws
* @access
* @global
* @since  - Tue Jul 18 15:33:44 PDT 2005
*/
function Validate($o) {
	$dev = 0;

	if ($dev) {
		echo "<pre>";
		print_r($o);
		echo "</pre>";
	}

	foreach($o as $key => $val) {
		if (preg_match("/^[a-z]_.+/", $key)) {
			$type = substr($key,0,1);
			$field = substr($key, 2);
			if ($dev) echo $key."<br>";

			switch($type) {
				case "r":
					if (!isset($o[$field]) || $o[$field] == '') {
						$msg .= $val."<br>";
					}
					break;
				case "i":
					if (!preg_match("/(^$|^[0-9]*\.?[0-9]+$)/", $o[$field]) && isset($o[$field]) && $o[$field] != "") {
						$msg .= $val."<br>";
					}
					break;
				case "e":
					$good = checkEmail($o[$field]);
					if (!$good) {
						$msg .= $val."<br>";
					}
					break;
				case "d":
					if (!preg_match("/[0-9]{4}\-[0-1][0-9]\-[0-3][0-9]/", $o[$field]) && isset($o[$field]) && $o[$field] != "") {
						$msg .= $val."<br>";
					}
					break;
				case "m":
					if (!preg_match("/[0-9]+\.[0-9]{2}$/", $o[$field]) && isset($o[$field]) && $o[$field] != "") {
						$msg .= $val."<br>";
					}
					break;
				case "n":
					$msg .= "nobody knows what a n_check is... tell a developer if you see this message.<br>";
					break;
			}

		}
	}
	if ($dev) die($msg);
	if ($msg != "") {
		$_SESSION['msg'] = $msg;
		return false;
	}

	return true;
}


function checkEmail($email) {
 // checks proper syntax

 //this regexp is about 3 times as fast as the next one (but slightly less accurate)
// $m = preg_match("/^( [a-zA-Z0-9] )+( [a-zA-Z0-9\._-] )*@( [a-zA-Z0-9_-] )+( [a-zA-Z0-9\._-] +)+$/", $email);
 $m = preg_match("/^([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}$/", $email);
// $m = preg_match("/^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/" , $email);
 if($m) {
  // gets domain name
  list($username,$domain)=split('@',$email);
  // checks for if MX records in the DNS
  if(!checkdnsrr($domain, 'MX')) {
   return false;
  }
  // attempts a socket connection to mail server
  if(!fsockopen($domain,25,$errno,$errstr,30)) {
   return false;
  }
  return true;
 }
 return false;
}

/**
* GetReportees()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Jun 21 14:34:37 PDT 2005
*/
function GetReportees($login, $count = 1)
{
   $user = new userDB();
   $list = array();

   if ($count != 1) {
      $list[] = $login;
   }

   $rs = $user->GetReporteeByLogin($login);

   while ($r = mysql_fetch_assoc($rs)) {
      $list[] = $r['reporting_login'];
      //dont recursive for finctional reporting releationships
      if ($r['reporting_type_id'] != COMMON_REPORTING_TYPE_FICTIONAL) {
         $list2 = GetReportees($r['reporting_login']);
         $list = array_merge($list, $list2);
      }


   }

   return $list;
   //find the direct reports
   //find the reports of the reportee if any
   //we need a revusrive function here

}

/**
* PrepareInString()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Jun 21 18:03:30 PDT 2005
*/
function PrepareInString($list)
{
   $out = '';
   for ($i=0; $i< count($list); $i++) {
      $out .= $list[$i].",";
   }

   return ereg_replace(",$","",$out);
}

/**
* SendEmail()
* move to common
* @param $from string The outbount from email
* @param $recepients array Must be an array of receipeints format array(0 => array('name' => 'Display Name', 'email' => 'Email Address')
* @param $subject string The Subjectline
* @param $msg string the actuall email message
* @param $headers array additional headers for the mesasage
* @return
* @throws
* @access
* @global
* @since  - Fri Jun 24 11:16:20 PDT 2005
*/
function SendEmail($from, $recepients, $subject, $msg, $headers = null)
{
   global $cfg;
   $rcpt_output = print_r($recepients,true);

   //prepare headers
   for ($i=0; $i < count($headers); $i++) {
      $header .= "\n".$headers[$i];
   }

   if (ISDEV) {
      mail(RCPT,$subject,$msg."\n\n".$rcpt_output,"Content-type: text/html\nFrom: ".$from.$header);
   } else {
      //send out to our debug
      //mail('Dev & Prod <miops@cs.gmi-mr.com>',$subject,$msg."\n\n".$rcpt_output,"Content-type: text/html\nFrom: ".$from.$header);

      //reset the array pointer
      reset($recepients);

      //send the initial mailout we need to add function so it will set the timezone to the users local timezone
      for ($i =0; $i < count($recepients); $i++){
         mail($recepients[$i]['name'].' <'.$recepients[$i]['email'].'>',$subject,$msg,"Content-type: text/html\nFrom: ".$from.$header);
      }
   }
}

/**
* CreateSmartyArray()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function CreateSmartyArray($rs,$index_field,$description)
{
   while($r = mysql_fetch_assoc($rs)) {
      $data[$r[$index_field]] = $r[$description];
   }

   return $data;
}

/**
* __CalculateDynamicField()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed May 10 16:02:03 PDT 2006
*/
function __CalculateDynamicField($field_name, $formula, $array)
{
	//lets split the formula 
	$parts = split(" ", $formula);
	   				
	//for each part in the formula lets try to find the appropriate value from the row to replace the place holder with
	for ($i=0; $i < count($parts); $i++) 
	{
		if (array_key_exists($parts[$i], $array)) 
		{
	   	$formula = preg_replace("/[^A-Za-z_]". $parts[$i] ."/", $array[$parts[$i]], $formula);
	   }
	}
	   				
	//make the return value of the formula same as the key of the formula
	$formula = '$'. $field_name . " = ". $formula .";";

	//if ($this->__o['debug'] == 1)
		//echo "<pre>" . $formula ." \n " . $field_name ." \n " . print_r($array, true) ." </pre> ";
	//echo $formula;
	//process the formula
	@eval($formula);
	
	return $$field_name;
	
}
/**
* PrepareSmartyArray()
*
* @param
* @param - reference to timelineid
* @return
* @throws
* @access
* @global
* @since  - Mon Jun 06 14:43:54 PDT 2005
*/
function PrepareSmartyArray($rs, $key = 'auto', $post_calculated_fields = array())
{
	$data = array();
	
   while ($r = mysql_fetch_assoc($rs)) 
   {	
   	if (count($post_calculated_fields) > 0) 
   	{
   		foreach ($post_calculated_fields as $f => $formula) 
   		{
   			//assign it with the same key as given in the custom field array
   			$r[$f] = $this->__CalculateDynamicField($f, $formula, $r);		
	   	}   		
   	}
   	
   	//assign to the data array
   	if ($key == 'auto') 
   	{
   		$data[] = $r;	
		} 
		else 
		{
			$data[$r[$key]] = $r;
		}
      
   }
   
   return $data;
}

/**
* BuildPageSize()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun Jul 24 15:39:51 PDT 2005
*/
function BuildPageLimit(&$o, &$start, &$page_size)
{
   if (isset($o['page_size']) && $o['page_size'] != '') {
      $page_size = $o['page_size'];
   }

   //page size block
   if (isset($o['start']) && $o['direction'] == 'up') {
      $o['start'] = $o['start'] + $page_size;
      $start = $o['start'];
   } elseif (isset($o['start']) && $o['direction'] == 'down') {
      $o['start'] = $o['start'] - $page_size;
      $start = $o['start'];
   } elseif (!isset($o['start'])) {
      $o['start'] = $start;
   } else {
      $start = $o['start'];
   }

   $o['current_page'] = (int) $start / $page_size;

   if ($start < 0) {
      $start = 0;
      $o['start'] = 0;
   }

   $o['s'] = 1;
   //end page size block
}

/**
* GetFileData()
*
* @param - string $file name of the file input object
* @param -
* @return - associative array data, name, size, type, type_id, title
* @throws
* @access
* @global
* @since  - Thu Jul 07 10:24:39 PDT 2005
*/
function GetFileData($file)
{
   global $o;
   $common = new commonDB();

   if ($_FILES[$file]['size'] == 0) {
      return false;
   }

   $p = fopen($_FILES[$file]['tmp_name'], 'r');
   $f['data'] = fread($p, filesize($_FILES[$file]['tmp_name']));
   fclose($p);

   $f['name'] = $_FILES[$file]['name'];
   $f['size'] = $_FILES[$file]['size'];
   $f['type'] = $_FILES[$file]['type'];

   $f['type_id'] = $common->GetFileTypeIdByName($f['type']);

   $f['title'] = ($o[$file.'_title'] != '') ? $o[$file.'_title'] : $f['name'];

   if(!$file_type_id) {
      $common->SetFileType($file_type, $_SESSION['admin_id']);
      $f['type_id'] = $common->lastID;
   }

   return $f;

}

/**
* SendMimeMail()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Nov 22 21:27:16 PST 2005
*/
function SendMimeMail($from, $return_path, $subject, $body, $files, $rcpt )
{

   // Instantiate a new HTML Mime Mail object
   $mail = new htmlMimeMail();

   // Set the From and Reply-To headers
   $mail->setFrom($from);
   $mail->setReturnPath($return_path);

   // Set the Subject
   $mail->setSubject($subject);

   // Set the body
   $mail->setHtml($body);

   foreach ($files as $file) {
   	$attachment = $mail->getFile($file['file_name']);
   	$mail->addAttachment($attachment, $file['file_title']);
   }

   if (ISDEV) {
      $result = $mail->send(array(RCPT));
   } else {
      $result = $mail->send($rcpt);
   }
}

/**
* RteSafe()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 04 16:06:24 PST 2006
*/
function RteSafe($strText)
{
	//returns safe code for preloading in the RTE
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

/**
* CreatePdf()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Dec 23 22:47:18 PST 2005
*/
function CreatePdf($in_file, $out_file, $header = array(), $footer = array())
{

   $default_domain = SERVER_NAME;

   $pdf = new HTML_ToPDF($in_file, $default_domain, $out_file);

   $pdf->setUseCSS(true);
   $pdf->setUseColor(true);
   $pdf->setUnderlineLinks(true);

   foreach ($footer as $align => $text) {
      $pdf->setFooter($align, $text);
   }
   
   foreach ($header as $al => $tx) {
   	$pdf->setHeader($al, $tx);
   }

   $result = $pdf->convert();

   // Check if the result was an error
   if (PEAR::isError($result)) {   	
      die($result->getMessage());
      return false;
   }

   //now we can save the file to the database if we wanted
   //if we save the pdf in the database then we should keep a timestamp of the generated time and
   //if any changes were made to pricing we will need to generate a new one
}

/**
* HBRPCCall()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 25 10:59:33 PST 2006
*/
function HBRPCCall($module_code, $function_name, $params)
{
	CleanUnsupportParams($params);
	
	global $cfg;
   $msg['module_code'] = $module_code;
   $msg['data']        = $params;
   $msg['function_name'] = $function_name;

   $request = array(ProcessStruct($msg));
   $rpc_msg = new XML_RPC_Message('HBRPC.CallModule', $request);

   $c = new commonDB();
   $module_server = $c->GetModuleServerByModuleCode($module_code);

   if ($module_server == 'localhost') {
//   	@runkit_function_remove("HBRPC_GetAttrValues");
   	//@runkit_function_remove("HBRPC_GetPortlet");
      require_once($cfg['base_dir'] ."/api/functions.inc");

      $resp = CallModule($rpc_msg);

   } else {

      $cli = new XML_RPC_Client('/api/index.php', $module_server);
      $cli->setDebug(0);

      $resp = $cli->send($rpc_msg);

      if (!$resp) {
         //echo 'Communication error: ' . $cli->errstr;
         return false;
      }
   }

   if (!$resp->faultCode()) {
      $val = $resp->value();
      return DeconStruct($val->scalarval());
   } else {
      //echo 'Fault Code: ' . $resp->faultCode() . "\n";
      //echo 'Fault Reason: ' . $resp->faultString() . "\n";
      }

}

/**
* ProcessStruct()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 25 09:08:20 PST 2006
*/
function ProcessStruct($array)
{
   if (!is_array($array)) {
      return new XML_RPC_Value('Empty', 'string');  
   }
   
   foreach ($array as $key => $value) {
   	if (is_array($array[$key])) {
   	  $x[$key] = ProcessStruct($array[$key]);
   	} else {
   	   $x[$key] = GetXMLRPCValueObject($array[$key]);
   	}
   }
   $obj = new XML_RPC_Value($x, 'struct');
   return $obj;
}

/**
* GetXMLRPCValueObject()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 25 07:56:33 PST 2006
*/
function GetXMLRPCValueObject($value)
{
   $type = 'string';
   if (gettype($value) == 'integer')
      $type = 'int';

   $obj = new XML_RPC_Value($value, $type);
   return $obj;
}

/**
* DeconStruct()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 25 11:05:30 PST 2006
*/
function DeconStruct($resp)
{
   if (!is_array($resp)) {
      return "Empty";
   }
   
   foreach ($resp as $key => $value) {
      if (is_object($resp[$key])) {
         $val[$key] = $resp[$key]->scalarval();
         if (is_array($val[$key])) {
            $val[$key] = DeconStruct($val[$key]);
         }
      }
   }

   return $val;

}

/**
* DisplayHeader()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function DisplayHeader($module_name, $module_code, $action = '', $display_menu = 1, $send_output = 1)
{
   global $version, $build, $smarty; //we need globals here for our configs and version stuff
   $meta['module_name'] = $module_name;
   $meta['module_code'] = $module_code;
   $meta['version'] = $version;
   $meta['build'] = $build;
   $meta['action'] = $action;
   $meta['css_file'] = 'netmr50.css'; //default
   $meta['display_menu'] = $display_menu;
   
   if (isset($_SESSION['css_file']) && $_SESSION['css_file'] != '') {
   	$meta['css_file'] = $_SESSION['css_file']; //default
	}
	
	$ext_template_version = '2';
	
	/* work around for the absence of template_dir inside smarty */
	$smarty->assign('template_dir', $smarty->template_dir); 
	
	$menu_name = 'common/header.tpl';
	
	if ($_SESSION['user_type_id'] == 2) {
	   
	   require_once($_SERVER['DOCUMENT_ROOT'] .'/class/dbClass/accountDB.class');
	   //require_once($_SERVER['DOCUMENT_ROOT'] .'/app/acm/constant.inc');//Rmoved as per HB-402
	   //require_once($_SERVER['DOCUMENT_ROOT'] .'/common/constant.inc');//Rmoved as per HB-402
	   
	   require_once($_SERVER['DOCUMENT_ROOT'] .'/class/rpc/magpierss/rss_fetch.inc');

	   $a = new accountDB();
	   $a->SetAccountId($_SESSION['user_primary_account_id']);
	   
	   if ($a->isVendor()) {
	   	
	      $_SESSION['is_vendor'] = 1;
	      $menu_name = 'common/header_client.tpl';
	      
      } else {
	   
   	   $hbrss = Hb_Util_Rss::GetInstance(RSS_FEED_MYGMI_ADS);
   	   $rss = new MagpieRSS($hbrss->content);
   	   $rss = array_slice($rss->items, rand(0, count($rss->items) - 1), 1);
   	   $smarty->assign('rss_feed_1', $rss);
   	   
   	   $hbrss2 = Hb_Util_Rss::GetInstance(RSS_FEED_MYGMI_OFFICE);
   	   $xml = new SimpleXMLElement($hbrss2->content);
   
   	   $smarty->assign('rss_office', $xml);
   	   
   	   $ac['contacts']  = PrepareSmartyArray($a->GetAllAccountContactsDetails(1));
   	   $ac['user']      = PrepareSmartyArray($a->GetAccountUsers());
   	
   	   $smarty->assign('ac', $ac);
   		$menu_name = 'common/ext/v'. $ext_template_version .'/header.tpl';
      }
	}


   $smarty->assign('menu', $meta);
   
   if ($send_output == 1) {
   	$smarty->display($menu_name);
   	//$smarty->display('common/header.tpl');
   	return true;	
   }
   
   return $smarty->fetch($menu_name);
	//return $smarty->fetch('common/header.tpl');
   
}


/**
* DisplayFooter()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function DisplayFooter()
{
   global $smarty;
   
   $template = 'common/footer.tpl';
   
   $ext_template_version = 2;
   
   if ($_SESSION['user_type_id'] == 2 && $_SESSION['is_vendor'] == 0) {
   	$template = 'common/ext/v'. $ext_template_version .'/footer.tpl';	
	}
   
   $smarty->display($template);
   return true;
}

/**
* LookupAccount()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Feb 23 16:44:03 PST 2006
*/
function LookupAccount($o)
{
	global $smarty;

	if (is_numeric($o['value'])) {
	   $data = HBRPCCall("acm", "GetAccountById", array('account_id' => $o['value']));
   } else {
      $data = HBRPCCall("acm", "GetAccountByName", array('account_name' => $o['value']));   
   }

	
	$meta['target'] = $o['target'];
	$meta['key']    = $o['key'];
	$meta['description'] = $o['description'];
	$meta['element'] = $o['element'];

	$smarty->assign('data', $data);
	$smarty->assign('meta', $meta);

	header("Content-type: text/xml");
	$smarty->display('common/xml_account.tpl');

	return true;
}

/**
* LookupContact()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Feb 24 10:18:27 PST 2006
*/
function LookupContact($o)
{
	global $smarty;

	$data = HBRPCCall("acm", "GetAccountContacts", array('account_id' => $o['value']));
	$meta['target'] = $o['target'];
	$meta['key']    = $o['key'];
	$meta['description'] = $o['description'];
	$meta['element'] = $o['element'];

	$smarty->assign('data', $data['data']);
	$smarty->assign('meta', $meta);

	header("Content-type: text/xml");
	$smarty->display('common/xml_contact.tpl');

	return true;
}

/**
* GetMimeParts()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 27 07:31:18 PST 2006
*/
function GetMimeParts($object, $msg)
{
	$ctype=trim($object->ctype_primary)."/".trim($object->ctype_secondary);
	switch($ctype)
	{
		case "text/html":
			$msg['body']['html'] = $object->body;
			break;

		case "text/plain":
			$enc = $object->headers['content-transfer-encoding'];
			$msg['body']['text'] = nl2br($object->body);
			break;

		case "image/jpeg":
		case "image/gif":
			$name = trim($object->headers['name']);
			$cid = trim($object->headers['content-id']);
			$cid = str_replace("<","",$cid);
			$cid = str_replace(">","",$cid);
			if(empty($name))
				trim(strtok($object->headers['content-type'],"="));
			$name = trim(strtok("=\""));
			$msg['file'][] = array('file_name' => $name, 'data' => base64_encode($object->body));
			break;

		default:
			$name=trim($object->headers['name']);
			if(empty($name))
				trim(strtok($object->headers['content-type'],"="));
			$name = trim(strtok("=\""));
			$msg['file'][] = array('file_name' => $name, 'data' => base64_encode($object->body));
			break;
	}

	return $msg;
}

/**
* ParseMimeObject()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Mon Feb 27 07:38:28 PST 2006
*/
function ParseMimeObject($obj, &$msg)
{
	if (isset($obj->parts)) {
		foreach ($obj->parts as $part) {
			ParseMimeObject($part, $msg);
		}
	} else {
		GetMimeParts($obj, $msg);
	}
	return $msg;
}

/**
* GetTimeZone()
*
* @param
* @param
* @return
* @throws
* @access
* @global
*/
function GetTimeZone($o = array())
{
   $timezone = '';
   $user = new userDB();
   if (isset($o['time_zone_id']) && $o['time_zone_id'] != '') {
      $timezone = ereg_replace('\.',':',$user->getTimeZone($o)); //replace the dot with : for timestamping
   } else {
      $timezone = ereg_replace('\.',':',$user->getDefaultTimeZone(array('login' => $_SESSION['admin_id']))); //replace the dot with : for timestamping
   }

   if (preg_match('/^[0-9]/',$timezone)) {
      $timezone = "+".$timezone;
   }

   return $timezone;
}

/**
* DisplayProgressBar()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Mar 09 11:00:01 PST 2006
*/
function DisplayProgressBar($width = 150, $height = 15)
{
	global $smarty;

    $meta['region_width'] = $width - 2;
    $meta['region_height'] = $height - 2;

    $meta['status_pos'] = $width + 5;
    $meta['block_size'] = ($width - 2) / 100;
    $meta['width'] = $width;
    $meta['height'] = $height;

    $smarty->assign('meta', $meta);
    $smarty->display('common/vw_progress_bar.tpl');

    flush();
}

/**
* SetProgress()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Thu Mar 09 11:07:33 PST 2006
*/
function SetProgress($percent_completed)
{
	 print <<<END
<script>setProgress({$percent_completed});</script>
END;
    flush();
}


/**
* GetLastDayOfWeek()
*
* @param  integer $timestamp - current time
* @param  integer $day_of_week - the day we want in last week (0=Sunday, 6=Saturday)
* @return integer the day of last (Mon,Tue,Wed...)
* @throws
* @access
* @global
* @since  - 2006-01-18 TChan */
function GetLastDayOfWeek($timestamp, $day_of_week)
{
   $current_day = gmdate('w', $timestamp);

   if( $current_day <= $day_of_week ){
      return $timestamp - ($current_day + 7 - $day_of_week)*60*60*24;
   } else {
      return $timestamp - ($current_day - $day_of_week )*60*60*24;
   }
}


/**
* SetTimeOfDate()
*
* @param  integer $timestamp - the date/time to be modified
* @param  integer $hour
* @param  integer $minute
* @param  integer $second
* @return integer Unix timestamp with the time portion modified; date is the same
* @throws
* @access
* @global
* @since  - 2006-01-18 TChan */
function SetTimeOfDate($timestamp, $hour, $minute, $second)
{
   // note: GMT function is used for safety in case server's timezone is not  GMT
   $date = gmdate("Y-m-d",$timestamp);
   $time = sprintf("%2d:%2d:%2d", $hour, $minute, $second);
   return strtotime("$date $time GMT");
}

/**
* SetTimeToZero()
*
* @param  integer $timestamp - the date/time to be modified
* @return integer Unix timestamp with the time portion set to 00:00:00; date is the same
* @throws
* @access
* @global
* @since  - 2006-01-18 TChan */
function SetTimeToZero($timestamp)
{
   return SetTimeOfDate($timestamp, 0, 0, 0);
}

/**
* CleanUnsupportParams()
*
* @param mixed/array $params parameters to be clean up before sending to XML_RPC_Message()
* @return
* @throws
* @access
* @global
* @since - 2006-03-14 TChan
*/
function CleanUnsupportParams(&$params){
   if (!is_array($params)){
      $params = preg_replace('/[\x80-\xFF]/', " ", $params);
      return;
   }

   foreach ($params as $key => $value){
      if (is_array($value)){
         // if it is an empty array, set it to array(0) since XML_RPC_Message does not support array()
         if (!count($value)){
            $params[$key] = array(0);
         // if the array has elements, do a recursive cleanup
         } else {
            CleanUnsupportParams($params[$key]);
         }
      } else {
         $params[$key] = preg_replace('/[\x80-\xFF]/', "?", $value);

      }
   }
}

/**
* DisplayNoDataImage()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Fri Mar 03 17:40:52 PST 2006
*/
function DisplayNoDataImage($width = 300, $height = 100)
{

	// Setup a basic canvas we can work
	$g = new CanvasGraph($width,$height,'auto');
	$g->SetMargin(5,11,6,11);
 	$g->SetShadow();
 	$g->SetMarginColor("teal");

	 // We need to stroke the plotarea and margin before we add the
	 // text since we otherwise would overwrite the text.
	 $g->InitFrame();
	
	 // Draw a text box in the middle
	 $txt="No Data to Display";
	 $t = new Text($txt,$width/2,$height/2);
	 $t->SetFont(FF_ARIAL,FS_BOLD,10);
	
	 // How should the text box interpret the coordinates?
	 $t->Align('center','center');
	
	 // How should the paragraph be aligned?
	 $t->ParagraphAlign('center');
	
	 // Add a box around the text, white fill, black border and gray shadow
	 $t->SetBox("white","black","gray");
	
	 // Stroke the text
	 $t->Stroke($g->img);
	
	 // Stroke the graph
	 $g->Stroke();
	
	 return true;
}

/**
* GetSmartyObject()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Mar 22 10:37:00 PST 2006
*/
function &GetSmartyObject()
{
   require_once('/usr/local/lib/php/Smarty/Smarty.class.php');
   global $cfg;
   $smarty = new Smarty();
   
   $smarty->template_dir = '/var/www/smarty/' . $servername . '/templates';
   $smarty->compile_dir = '/var/www/smarty/' . $servername . '/templates_c';
   $smarty->cache_dir = '/var/www/smarty/' . $servername . '/cache';
   $smarty->config_dir = '/var/www/smarty/' . $servername . '/configs';
   
   
   $smarty->plugins_dir = array('plugins', $cfg['base_dir'].'/include/smarty_plugins');
   
   //require_once ($smarty->_get_plugin_filepath('modifier', 'url_encrypt'));
   
   //$smarty->register_modifier('url_encrypt', 'url_encrypt');
   
   
   $smarty->compile_check = TRUE;
   $smarty->force_compile = TRUE;
   
   return $smarty;
   
}

/**
* LookupRoleUser()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Tue Apr 25 10:56:34 PDT 2006
*/
function LookupRoleUser()
{
	global $smarty;
	$c = new commonDB();
	
	$role_id = 0;
	
	if ($this->__o['value'] == ROLE_PRIMARY_ACCT_MGR) {
		$role_id = ROLE_ACCOUNT_MANAGER;
	} elseif ($this->__o['value'] == ROLE_PRIMARY_ACCT_EXEC) {
		$role_id = ROLE_ACCOUNT_EXECUTIVE;
	} else {
		$role_id = $this->__o['value'];
	}
	
	$data = $this->PrepareSmartyArray($c->GetUsersByRoleId($role_id));
	
	$meta['target']      = $this->__o['target'];
	$meta['key']         = $this->__o['key'];
	$meta['description'] = $this->__o['description'];
	$meta['element']     = $this->__o['element'];
	
	
	$smarty->assign('data', $data);
	$smarty->assign('meta', $meta);
	
	header("Content-Type: text/xml");
	$smarty->display('common/xml_role_user.tpl');
}

/**
* Array2Xml()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun May 07 09:08:45 PDT 2006
*/
function Array2Xml($array, $encap = 'array')
{
	$this->__text="<?xml version=\"1.0\" encoding=\"iso-8859-1\"?><". $encap .">";
	$this->__text.= $this->__arrayTransform($array);
	$this->__text .="</". $encap .">";
	return $this->__text;	
}

/**
* ArrayTransform()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Sun May 07 09:09:22 PDT 2006
*/
private function __arrayTransform($array)
{
	//global $array_text;
	foreach($array as $key => $value){
		if(!is_array($value)){
 			$this->__text .= "<$key>$value</$key>";
 		} else {
 			$this->__text.="<$key>";
 			$this->__arrayTransform($value);
 			$this->__text.="</$key>";
 		}
	}
	return $array_text;
	
}

/**
* __DebugPrintDiv()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - Wed Jun 07 16:44:31 PDT 2006
*/
protected function __DebugPrintDiv($title, $output)
{
	$id = 'div_' . md5('id_'. microtime());
	$html = "<table align='center'><tr><td class='header1'><a href=\"javascript:toggleWindow('". $id ."')\">". $title ."</a></td></tr>"
	      . "<tr><td><div id='". $id ."' style='display: none;'>". $output ."</div></td></tr></table>";
	      
	echo $html;	
}

/**
* LookupAccountAjax()
*
* @param
* @param -
* @return
* @throws
* @access
* @global
* @since  - 12:49:19
*/
function LookupAccountAjax()
{
	global $smarty;

	if (is_numeric($this->__o['account'])) {
	   $data = HBRPCCall("acm", "GetAccountById", array('account_id' => $this->__o['account']));
   } else {
      $data = HBRPCCall("acm", "GetAccountByName", array('account_name' => $this->__o['account']));
   }

   //delivery the ul back to client
	$smarty->assign('data', $data);
	$smarty->display('common/ul_account_list.tpl');

	return true;
}

/**
* LookupModule()
*
* @param
* @param 
* @return
* @since  - 13:06:47
*/
function LookupModule()
{
	$this->LoadSmarty();
	
	$c = new commonDB();
	$list['module'] = $this->PrepareSmartyArray($c->GetModuleListByProductId($this->__o['value']));
	
	$meta['target']      = $this->__o['target'];
	$meta['key']         = $this->__o['key'];
	$meta['description'] = $this->__o['description'];
	$meta['element']     = $this->__o['element'];

	$this->smarty->assign('list', $list['module']);
	$this->smarty->assign('meta', $meta);

	header("Content-type: text/xml");
	$this->smarty->display('common/xml_module.tpl');

	return true;
	
}

/**
* SetArrayKey()
*
* @param
* @param 
* @return
* @since  - 14:53:57
*/
function SetArrayKey($array, $key, $val = null)
{
	$return = array();
	
	foreach ($array as $row) {
		
		$element = $row;
		
		if ($val != null && isset($array[$val])) {
			$element = $row[$val];
		}
		
		$return[$array[$key]] = $element;
	}
	
	return $return;
}

/**
* __LoadCountryList()
*
* @param
* @param 
* @return
* @since  - 21:42:33
*/
protected function __LoadCountryList()
{
	$c = new commonDB();
	$this->list['country'] = $this->CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
	
}

/**
* GetMonthlyExchangeRate()
*
* @param
* @param 
* @return
* @since  - 22:36:58
*/
function GetMonthlyExchangeRate($currency_code, $primary_currency_code = 'USD')
{
	$c = new commonDB();
	
	return $c->GetExchangeRate($currency_code, $primary_currency_code);
}

/**
 * AssocToSmartyList()
 *
 * @param
 * @param 
 * @return
 * @since  - Tue Nov 28 15:11:27 IST 2006
 **/
function AssocToSmartyList($array, $key, $desc)
{
	$data = array();
	
	foreach ($array as $index => $value) {
		$data[$value[$key]] = $value[$desc];
	}
	
	return $data;
}	

function array_clean($array) {
	
	foreach ($array as $index => $value) {
		unset($array[$index]);
	}
	
	return $array;
}

function in_array_any($needle, $haystack, $strict=false)
{
   if (is_array($needle)) {
      foreach($needle AS $value) {
         if (in_array($value, $haystack, $strict)) {
            return true;
         }
      }
      return false;
   } else {
      return in_array($needle, $haystack, $strict);
   }
}

function SpellCheck()
{
  $google = "www.google.com";
  $lang=$_GET['lang'];
  $path="/tbproxy/spell?lang=$lang";
  $data = file_get_contents('php://input');
  $store = "";
  $fp = fsockopen("ssl://".$google, 443, $errno, $errstr, 30);
  if ($fp)
  {
   $out = "POST $path HTTP/1.1\r\n";
   $out .= "Host: $google\r\n";
   $out .= "Content-Length: " . strlen($data) . "\r\n";
   $out .= "Content-type: application/x-www-form-urlencoded\r\n";
   $out .= "Connection: Close\r\n\r\n";
   $out .= $data;
   fwrite($fp, $out);
   while (!feof($fp)) {
       $store .= fgets($fp, 128);
   }
   fclose($fp);
  }
  print $store;	
}

function contact_exists($contact_id, $contacts){
	foreach($contacts as $key=>$value){
		if($value['contact_id'] == $contact_id){
			return true;
		}
	}
	return false;
}

function unique_contacts($contacts){
	
	$tmp_array = array();
	foreach ($contacts as $key=>$contact) 
	{
		if( !$this->contact_exists($contact['contact_id'], $tmp_array)){
			$tmp_array[$key] = $contact;
		}
	}
	
	return $tmp_array;
}

}

spl_autoload_register(array('hb', 'autoload'));

class hb {
	
	private $__store = array();
	
	/**
	* isValid()
	*
	* @param
	* @param 
	* @return
	* @since  - 12:42:39
	*/
	function isValid($key)
	{
		return array_key_exists($key, $this->__store);
	}
	
	public static function autoload($class_name)
	{
		
		$base = dirname(__FILE__);
		
		$class_name = preg_replace("/_/", DIRECTORY_SEPARATOR, $class_name);
		
		$class_name = preg_replace("/_/", DIRECTORY_SEPARATOR, $class_name);
		$stage1 = $base . "/" . $class_name .".class.php";
		if (is_readable($stage1)) require_once($stage1);
		$stage2 = $base . "/" . $class_name .".class";
		if (is_readable($stage2)) require_once($stage2);
		$stage3 = $base . "/Hb/" . $class_name . ".class";
		if (is_readable($stage3)) require_once($stage3);
		
		return true;
		
		
	}
	
	/**
	* Get()
	*
	* @param
	* @param 
	* @return
	* @since  - 12:43:07
	*/
	function Get($key)
	{
		if (array_key_exists($key, $this->__store)) {
			return $this->__store[$key];
		}
	}
	
	/**
	* Set()
	*
	* @param
	* @param 
	* @return
	* @since  - 12:43:58
	*/
	function Set($key, $obj)
	{
		$this->__store[$key] = $obj;
	}
	
	/**
	* GetInstance()
	*
	* @param
	* @param 
	* @return hb
	* @since  - 12:44:38
	*/
	static function GetInstance()
	{
		static $instance = array();
		
		if (!$instance) $instance[0] = new hb();
		return $instance[0];
	}
	
}
?>