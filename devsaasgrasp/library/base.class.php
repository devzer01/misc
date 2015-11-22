<?php

/**
* Common base class for all classes
*
* As a kind of common base class holds
* configuration values (e.g. error handling) and debugging
* methods
*
* @author
* @version 	$Id: class.base.php,v 0.1 2004/12/03 20:30:42 uw Exp $
* @package
*/

#define ('RCPT','miops@gmi-mr.com');
#define ('SUBJECT','HB Error');
#define ('FROM','error-daemon@hb.gmi-mr.com');


/* this will be the base class for all classes */

class base {

	/**
	* base - Constructor
	*
	* @param
	* @access	public
	* @see
	* @since	0.1
	*/
	function base()
	{

	}

	/**
	* errorAlert - Sends the error alert email
	*
	* @param	string	$errorMessage
	* @access	public
	* @see
	* @since	0.1
	*/

	function errorAlert($errorMessage)
	{
	   global  $o;
		$body .= $this->getSessionVars() . $this->getSystemVars() . $this->GetPostVars() . $this->GetGetVars() .  $errorMessage . print_r($o, true);
		$this->sendEmail(RCPT,SUBJECT,$body,"From: hb-daemon@hb.gmi-mr.com");
		die("System Error occured, System Admin has been notified<br>Please go <a href='javascript:history.back();'>back</a> and try again. If the error persists, the System Admin will try to resolve the problem.");
	} // end func errorAlert

	/**
	* getSessionVars - creates the sessionVars string for printing
	* in the error message
	*
	* @param
	* @access	public
	* @see		errorAlert
	* @since	0.1
	*/

	function getSessionVars()
	{
		$sessionVars = 'Session vars';
		foreach($_SESSION as $key => $value) {
			$sessionVars .= "$key -> $value \n";
		}
		$sessionVars .= "============================================================\n";
		return $sessionVars;
	} // end func getSessionVars

	/**
	* getSystemVars - creates the systemVars string for printing
	* in the error message
	*
	* @param
	* @access	private
	* @see		errorAlert()
	* @since	0.1
	*/

	function getSystemVars()
	{
		$serverVars = '';
		foreach($_SERVER as $key => $value) {
			$serverVars .= "$key -> $value \n";
		}

		$serverVars .= "==============================================================\n";
		return $serverVars;
	} // end func getSystemVars

	/**
	* debugPrint - Shows the debug message
	*
	* @param	string	$debugString
	* @access	public
	* @see
	* @since	0.1
	*/

	function debugPrint($debugString)
	{
		if (ISDEV) {
			echo "$debugString\n";
		}

	} // end func debugPrint

	/**
	* GetPostVars()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Aug 15 17:51:17 PDT 2005
	*/
	function GetPostVars()
	{
	   foreach ($_POST as $key => $val) {
	      $post_vars .= $key . " => " . $val . "\n";
	   }

	   $post_vars .= "========================================================================\n";

	   return $post_vars;
	}

	/**
	* GetGetVars()
	*
	* @param
	* @param -
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Aug 15 17:55:03 PDT 2005
	*/
	function GetGetVars()
	{
	   foreach ($_GET as $key => $val) {
	      $get_vars .= $key . " => " . $val . "\n";
	   }

	   $get_vars .= "==========================================================================\n";

	   return $get_vars;
	}

	/**
	* SendEmail()
	*
	* @param - string $to
	* @param - string $subject
	* @param - string $body
	* @param - string $from
	* @return
	* @throws
	* @access
	* @global
	* @since  - Mon Aug 15 17:43:37 PDT 2005
	*/
	function SendEmail($to, $subject, $body, $from)
	{
	   if (ISDEV) {
      	$to = RCPT;
			echo "<pre>".$body."</pre>";
     	}

     	//mail($to,$subject,$body,$from) or die("unable to report error to system admin, killing session");

	}
}

?>
