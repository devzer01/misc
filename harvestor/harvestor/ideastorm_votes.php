<?php
$dbname = 'ideastorm';
$url    = '';
require_once 'include/config.php';

require_once 'lib/curling.php';
require_once 'lib/extractor.php';
require_once 'lib/harvestor.php';

//stage 1
////*[@id="sitemap_date"]
$sql = "SELECT ididea, uri FROM idea WHERE total_votes > 0 AND votes_parsed IS NULL";
$rs = mysql_query($sql, $con) or die(mysql_error());
$remaining = mysql_num_rows($rs);
printf("Ideas remain to parse %d \n", $remaining);
while($r = mysql_fetch_assoc($rs)) 
{
	$xpath = '//div[@class="ideaViewSection baseIdeaVotesUp"]/div/span/span/a/span';
	$url = "http://www.ideastorm.com" . $r['uri'];
	printf("Trying %s\n", $url);
	$harvestor = new harvestor($url, $xpath);
	$recs = $harvestor->get_results();
	printf("Total votes parsed %d\n", $recs->length);

	for ($i=0; $i<$recs->length; $i++) {
		$username = $recs->item($i)->nodeValue;
		//printf("Found Username %s\n", $username);
		$sql = "INSERT INTO idea_vote (idea_id, username, vote_type) VALUES (" . $r['ididea'] .",'" . mysql_real_escape_string($username) . "', 1)";
		mysql_query($sql, $con) or die(mysql_error());	
	}
	
	$xpath = '//div[@class="ideaViewSection baseIdeaVotesDown"]/div/span/span/a/span';
	$recs = $harvestor->query($xpath);
	printf("Total votes parsed %d\n", $recs->length);
	
	for ($i=0; $i<$recs->length; $i++) {
		$username = $recs->item($i)->nodeValue;
		$sql = "INSERT INTO idea_vote (idea_id, username, vote_type) VALUES (" . $r['ididea'] .",'" . mysql_real_escape_string($username) . "', 2)";
		mysql_query($sql, $con) or die(mysql_error());
	}
	$sql = "UPDATE idea SET votes_parsed = NOW() WHERE ididea = " . $r['ididea'];
	mysql_query($sql, $con) or die(mysql_error());
	$remaining--;
	$interval = rand(1,3);
	printf("Pausing for %d Second(s)\n", $interval);
	sleep($interval);
	if ($remaining % 10 == 0) printf("Ideas remain to parse %d \n", $remaining);
}
