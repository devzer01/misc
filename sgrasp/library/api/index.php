<?php

	require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.inc');
	require_once($_SERVER['DOCUMENT_ROOT'] .'/class/dbConnect.php');
	require_once($_SERVER['DOCUMENT_ROOT'] .'/common/functions.inc');
	require_once 'XML/RPC/Server.php';
  
	require_once('functions.inc');
	
   $server = new XML_RPC_Server(
       array(
           'HBRPC.CallModule' =>
               array('function' => 'CallModule'),
           'HBRPC.GetStudyPriority' => 
               array(
                  'function' => 'GetStudyPriority',
                  'signature' => 
                        array(
                           array('struct', 'struct'),
                           array('struct')
                        )
               
               )
      )
   ); 
   
   /*
   
   */
?>
