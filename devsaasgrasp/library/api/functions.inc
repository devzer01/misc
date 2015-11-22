<?php
require_once 'XML/RPC/Server.php';

/**
* CallModule()
*
* @param
* @param - 
* @return
* @throws
* @access
* @global
* @since  - Wed Jan 25 11:08:56 PST 2006
*/
function CallModule($msg)
{
   global $cfg;
//   print_r($request);
//   $req = $request->getParam(0);
//   print_r($req->getVal());
//   $msg = DeconStruct($req->scalarval());
//   
   if (!isset($_SESSION['admin_id'])) {
      $_SESSION['admin_id'] = 10367;
   }
   
   //$file_list = array(functions', 'constant');
   //foreach ($file_list as $key => $value) {
   require_once('app/'. $msg['module_code'] .'_functions.inc');
   //}
   
   $func_name = "HBRPC_". $msg['function_name'];   
   
   if (!function_exists($func_name)) {
      $error['error_code'] = 1;
      $error['error_description']  = 'Method Not Found';
      return $error; //new XML_RPC_Response(ProcessStruct($error));   
   }

   return call_user_func($func_name, $msg['data']);
   
//   $res = ProcessStruct($response);
//   return new XML_RPC_Response($res);   
}

/**
 * API Frontend for StudyPriority Method
 *
 * @param mixed $params
 */
function GetStudyPriority($params)
{
   global $cfg;   
   require_once($_SERVER['DOCUMENT_ROOT'] . '/common/common.inc');
   
   if ($params->getNumParams() == 0) {
      $score = HBRPCCall('pjm', 'GetStudyPriority', array('no_param' => 1));
      return new XML_RPC_Response(ProcessStruct($score));      
   }
   
   $req = $params->getParam(0);
   $list = $req->scalarval();
   
   if (count($list) > 0) {
      foreach ($list as $s_o) 
      {
         $study[] = $s_o->scalarval();
      }
   }
  
   $score = HBRPCCall('pjm', 'GetStudyPriority', array('study_id' => $study));
   return new XML_RPC_Response(ProcessStruct($score));      
   
}

?>