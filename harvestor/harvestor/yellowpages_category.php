<?php
$dbname = 'business';
$url    = '';
require_once 'include/config.php';

require_once 'lib/curling.php';
require_once 'lib/extractor.php';
require_once 'lib/harvestor.php';

//stage 1
////*[@id="sitemap_date"]
$url   = "http://www.yellowpages.ca/business/00868575.html";
$xpath = '//*[@class="ypgCatTitle"]/a';

$harvestor = new harvestor($url, $xpath);
$recs = $harvestor->get_results();
printf("Got %d records(s)\n", $recs->length);
//
for ($i=0; $i < $recs->length; $i++) 
{
	$uri = $recs->item($i)->attributes->getNamedItem('href')->nodeValue;
	$location = $recs->item($i)->nodeValue;
//
	printf("Location %s url %s\n", $location, $uri);
	$sql = "SELECT id, uri FROM location WHERE uri = '" . mysql_real_escape_string($uri) ."'";
	$rs = mysql_query($sql, $con) or die(mysql_error());
//	
	if (mysql_num_rows($rs) == 0) {
		$sql = "INSERT INTO location (location, uri) "
	    	 . "VALUES ('" . mysql_real_escape_string($location) . "', '" . mysql_real_escape_string($uri) . "')";
		mysql_query($sql, $con) or die(mysql_error());
	}
}

$sql = "SELECT id, uri FROM location WHERE parsed IS NULL ";
$rs = mysql_query($sql) or die(mysql_error());
$xpath = '//*[@class="ypgSubcategory"]';
while ($r = mysql_fetch_assoc($rs)) 
{
	$harvestor = new harvestor('http://www.yellowpages.ca' . $r['uri'], $xpath);
	$recs = $harvestor->get_results();
	printf("Got %d records(s) from URL %s\n", $recs->length, $r['uri']);
	$sql = "UPDATE location SET parsed = NOW() WHERE id = " . $r['id'];
	mysql_query($sql, $con) or die(mysql_error());
	for ($i=0; $i < $recs->length; $i++) 
	{
		$uri = $recs->item($i)->attributes->getNamedItem('href')->nodeValue;
		$location = $recs->item($i)->nodeValue;
	
		printf("Location %s url %s\n", $location, $uri);
		
		$sql = "INSERT INTO location (location, uri) "
		     . "VALUES ('" . mysql_real_escape_string($location) . "', '" . mysql_real_escape_string($uri) . "')";
		mysql_query($sql, $con) or die(mysql_error());
	}
	$rand = rand(1,4);
	printf("Sleeping %d second(s)\n", $rand);
	sleep($rand);
}