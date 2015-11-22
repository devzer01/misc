<?php
$dbname = 'harvestor';
$url    = '';
require_once 'include/config.php';

require_once 'lib/curling.php';
require_once 'lib/extractor.php';
require_once 'lib/harvestor.php';

//stage 1
////*[@id="sitemap_date"]
$url   = "http://www.complaints.com/site-map/date/";
//$xpath = "//table[@id='sitemap_date']/tr/td/*";
//$xpath = "//div[@id='content']/div[1]/div[2]/div[1]/ul/li";
////div[contains(concat(' ',normalize-space(@class),' '),' foo ')]
$xpath = "//div[@class='site-map-page-row'][1]/div[1]/ul/li/a";

$harvestor = new harvestor($url, $xpath);
$recs = $harvestor->get_results();

print("Got " . $recs->length . " records(s)\n");


for ($i=0; $i < $recs->length; $i++) {
	if ($recs->item($i)->nodeName == 'a') {
		$link = $recs->item($i)->attributes->getNamedItem('href')->nodeValue;
		$sql = "SELECT id, parsed FROM harvestor_links WHERE uri = '" . mysql_real_escape_string($link) . "'";
		$rs = mysql_query($sql, $con) or die(mysql_error());
		if (mysql_num_rows($rs) == 0) {
			$sql = "INSERT INTO harvestor_links (uri, added) VALUES ('" . mysql_real_escape_string($link) . "',NOW())";
			mysql_query($sql, $con) or die(mysql_error());
		}
	}
}

//stage 2
$sql = "SELECT id, uri FROM harvestor_links WHERE parent_link_id IS NULL AND parsed IS NULL";
$rs = mysql_query($sql, $con) or die(mysql_error());

//$xpath = '//div[@id="content"]/div/table/tr/td/div/font/b/*';
$xpath   = "//div[@class='site-map-page-row']/*/ul/li/a";
while($r = mysql_fetch_assoc($rs)) 
{
	$harvestor = new harvestor('http://complaints.com' . $r['uri'], $xpath);	
	$recs = $harvestor->get_results();
	
	printf("Got %d records(s)\n", $recs->length);
	
	$sql = "UPDATE harvestor_links SET parsed = NOW() WHERE id = " . $r['id'];
	mysql_query($sql, $con) or die(mysql_error());
	
	printf("Got %d records(s)\n", $recs->length);
	
	for ($i=0; $i < $recs->length; $i++) {
		//printf("The result %s %s \n", $recs->item($i)->nodeName, $recs->item($i)->nodeValue);
		if ($recs->item($i)->nodeName == 'a') {
			$link = $recs->item($i)->attributes->getNamedItem('href')->nodeValue;
			$sql = "SELECT id, parsed FROM harvestor_links WHERE uri = '" . mysql_real_escape_string($link) . "'";
			$rs_links = mysql_query($sql, $con) or die(mysql_error());
			if (mysql_num_rows($rs_links) == 0) {
				$sql = "INSERT INTO harvestor_links (parent_link_id, uri, added) VALUES (" . $r['id'] . ",'" . mysql_real_escape_string($link) . "',NOW())";
				mysql_query($sql, $con) or die(mysql_error());
			}
		}
	}
	$interval = rand(1,3);
	printf("Sleeping for %d second(s)", $interval);
	sleep($interval);
	
}


//stage 3
$sql = "SELECT id, uri FROM harvestor_links WHERE parent_link_id IS NOT NULL and parsed IS NULL";
$rs = mysql_query($sql, $con) or die(mysql_error());

$xpath = "//div[@id='content']";
while($r = mysql_fetch_assoc($rs)) {
	
	$page = 'http:' . $r['uri']; //page;
	printf("Trying %s page \n", $page);
	$harvestor = new harvestor($page, $xpath);
	$recs = $harvestor->get_results();
	$sql = "UPDATE harvestor_links SET parsed = NOW() WHERE id = " . $r['id'];
	mysql_query($sql, $con) or die(mysql_error());
	if ($recs->length == 0) continue;
	
	$record['heading'] = $harvestor->query("//div[@class='title-wrapper']/h1")->item(0)->nodeValue;	
	$body_rec    = $harvestor->query("//div[@class='complaint-content-wrapper']/p"); //, $recs->item(0))->item(0)->nodeValue;
	
	for ($i = 0 ; $i < $body_rec->length; $i++) {
		$record['body'] .= $body_rec->item($i)->nodeValue;
 	}
	
 	$record['product']  = trim($harvestor->query("//div[@class='company-info-wrapper']/table/tr[1]/td[2]")->item(0)->nodeValue);
 	$record['company']   = trim($harvestor->query("//div[@class='company-info-wrapper']/table/tr[2]/td[2]")->item(0)->nodeValue);
	$record['location']   = trim($harvestor->query("//div[@class='company-info-wrapper']/table/tr[3]/td[2]")->item(0)->nodeValue);
	$record['url']   = trim($harvestor->query("//div[@class='company-info-wrapper']/table/tr[4]/td[2]")->item(0)->nodeValue);
	
	$sql = "INSERT INTO harvestor_data (link_id, added, data_record) VALUES (" . $r['id'] . ",NOW(), '" . mysql_real_escape_string(serialize($record)) . "')";// "
	mysql_query($sql, $con) or die(mysql_error());
	
	$interval = rand(1,3);
	printf("Sleeping for %d second(s)", $interval);
	sleep($interval);
}
