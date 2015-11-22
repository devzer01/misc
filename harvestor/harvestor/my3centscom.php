<?php

define('MAX_RECORD', 117595);

$dbname = 'harvestor';
$url    = '';
require_once 'include/config.php';

require_once 'lib/curling.php';
require_once 'lib/extractor.php';
require_once 'lib/harvestor.php';

for ($index=78579; $index <= MAX_RECORD; $index++) {
	$record = array();
	
	$url = 'http://www.my3cents.com/showReview.cgi?id=' . $index;
	$xpath = "//span[@class='fn']";
	
	$sql = "SELECT id, parsed FROM harvestor_links WHERE uri = '" . mysql_real_escape_string($url) . "'";
	$rs_links = mysql_query($sql, $con) or die(mysql_error());
	if (mysql_num_rows($rs_links) != 0) continue;	
	
	printf("Trying %s \n", $url);
	
	try {
		$harvestor = new harvestor($url, $xpath);
		$recs = $harvestor->get_results();
	} catch (Exception $e) {
		printf("Timeout connecting trying next\n");
		continue;
	}
	//heading //span[@class='fn']
	$record['company'] = $recs->item(0)->nodeValue;
	
	$recs = $harvestor->query("//span[@class='summary']");
	
	//summary
	$record['heading'] = $recs->item(0)->nodeValue;
	
	//body 
	$recs = $harvestor->query("//span[@class='review']");
	
	for ($i=0; $i < $recs->length; $i++) {
		
		$record['body'] .= "<br/><br/>" . $recs->item($i)->nodeValue;
			
	}
	
	$sql = "INSERT INTO harvestor_links (uri, added, parsed) VALUES ('" . mysql_real_escape_string($url) . "',NOW(), NOW())";
	mysql_query($sql, $con) or die(mysql_error());
	$link_id = mysql_insert_id($con);
	
	$sql = "INSERT INTO harvestor_data (link_id, added, data_record) VALUES (" . $link_id . ",NOW(), '" . mysql_real_escape_string(serialize($record)) . "')";// "
	mysql_query($sql, $con) or die(mysql_error());
	
	$interval = rand(1,3);
	printf("Sleeping for %d second(s) \n", $interval);
	sleep($interval);
	
}
