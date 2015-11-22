<?php 
require_once('curling.php');
libxml_use_internal_errors(true);
$url = "http://www.putlocker.com/file/CB79E6201EDBA3ED";

$curl = new curling();
$output = $curl->get_page($url); //call your landing page url from here
$dom_document = new DOMDocument();

$dom_document->loadHTML($output);

$dom_xpath = new DOMXPath($dom_document);
foreach($dom_xpath->query("//input[@name=\"hash\"]/@value") as $node) $hash=$node->textContent;
$data = array('hash' => $hash, 'confirm' => 'Continue%20as%20Free%20User');

$output2 = $curl->post_page($url, $data);
$curl->close_session();
echo $output2;