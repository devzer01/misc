<?php

// In a real application this would use a database, and not a session!
session_start();

require_once '../library/google/src/apiClient.php';
//require_once '../library/google/src/contrib/apiBuzzService.php';

$client = new apiClient();
// Visit https://code.google.com/apis/console to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
$client->setClientId('708667817077.apps.googleusercontent.com');
$client->setClientSecret('fmclMOe1vJYf8suwr0zzlH9t');
$client->setRedirectUri('http://dev.ersp.corp-gems.com/oauth2callback.php');
$client->setApplicationName("GRASP");
//$buzz = new apiBuzzService($client);

if (isset($_SESSION['access_token'])) {
  $client->setAccessToken($_SESSION['access_token']);
} else {
  $client->setAccessToken($client->authenticate());
}
$_SESSION['access_token'] = $client->getAccessToken();

if (isset($_GET['code'])) {
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

// Make an authenticated request to the Buzz API.
if ($client->getAccessToken()) {
	echo "Got Client Token";
//  $me = $buzz->getPeople('@me');
//  $ident = '<img src="%s"> <a href="%s">%s</a>';
//  printf($ident, $me['thumbnailUrl'], $me['profileUrl'], $me['displayName']);
}

//
//error_reporting(E_ALL);
//$url = "https://accounts.google.com/o/oauth2/token";
//    	$code = $_GET['code'];
//    	$client_id = '708667817077.apps.googleusercontent.com';
//    	$client_secret = 'fmclMOe1vJYf8suwr0zzlH9t';
//    	$redirect_url = 'http://dev.ersp.corp-gems.com/oauth2callback.php';
//    	$grant_type = 'authorization_code';
//    	$data = array(
//    		'code' => $code,
//    		'client_id' => $client_id,
//    		'client_secret' => $client_secret,
//    		'redirect_url'  => $redirect_url,
//    		'grant_type'    => $grant_type
//    	);
//    	print_r($data);
//    	$ch = curl_init();
//    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//    	curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);
//    	// set URL and other appropriate options
//		curl_setopt($ch, CURLOPT_URL, $url);
//		curl_setopt($ch, CURLOPT_HEADER, true);
//    	curl_setopt($ch, CURLOPT_POST, 1);
//    	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    	curl_setopt($ch, CURLOPT_VERBOSE, true);
//    	$response = curl_exec($ch);
//    	echo $response;
//    	printf("Response %s\n", $response);
//    	
//    	curl_close($ch);
//
////header("Location: /Auth/oauth/state/" . $_GET['state'] . "/code/" . urlencode($_GET['code']));
////https://oauth2-login-demo.appspot.com/code?state=profile&code=4/P7q7W91a-oMsCeLvIaQm6bTrgtp7