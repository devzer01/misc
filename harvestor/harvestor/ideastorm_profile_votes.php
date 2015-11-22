<?php
$dbname = 'ideastorm';
$url    = '';
require_once 'include/config.php';

require_once 'lib/curling.php';
require_once 'lib/extractor.php';
require_once 'lib/harvestor.php';

//stage 1
////*[@id="sitemap_date"]
$sql = "SELECT id, username FROM idea_user WHERE parsed IS NULL GROUP BY username";
$rs = mysql_query($sql, $con) or die(mysql_error());
//table/tbody/tr/td[2]/table/tbody/tr/td/span/a
$xpath = '//div[@class="baseIdeaList"]/span/div/table/tr/td[2]/table/tr/td/a';
while($r = mysql_fetch_assoc($rs)) 
{
	$url = "http://www.ideastorm.com/ideaProfileVotedOn?cn=" . $r['username'];
	$page = 1;
	printf("Trying %s\n", $url);
	$harvestor = new harvestor($url, $xpath);
	$recs = $harvestor->get_results();
	$page_count = 1;
	if ($harvestor->query('//span[@class="paginationCount"]')->item(0)->nodeValue != NULL) {
		$strpagecount = $harvestor->query('//span[@class="paginationCount"]')->item(0)->nodeValue;
		list(,,,$page_count) = explode(" ", trim($strpagecount));
	}
	///html/body/div[3]/div/div[2]/div/div[3]/div[4]/span/span/span[2]
	printf("Got %d records(s) and %d pages\n", $recs->length, $page_count);
	for ($page=1; $page <= $page_count; $page++) {

		if ($page > 1) {
			$purl = $url . "&p=" . $page;
			printf("Grabing Sub Page %s\n", $purl);
			$harvestor = new harvestor($purl, $xpath);
			$recs = $harvestor->get_results();
			printf("Got %d records(s)\n", $recs->length);
		}
		
		for ($i=0; $i < $recs->length; $i++) 
		{
			if ($recs->item($i) == NULL) continue;
			$idea_uri = $recs->item($i)->attributes->getNamedItem('href')->nodeValue; 
			
			$sql = "SELECT ididea FROM idea WHERE uri = '" . mysql_real_escape_string($idea_uri) . "'";
			$rsidea = mysql_query($sql, $con) or die(mysql_error());
			$ididea = 0;
			if (mysql_num_rows($rsidea) == 0) {
				$sql = "INSERT INTO idea (uri, username) VALUES ('" . mysql_real_escape_string($idea_uri) . "', '" . mysql_real_escape_string($r['username']) . "')";
				mysql_query($sql, $con) or die(mysql_error());
				$ididea = mysql_insert_id($con);
			} else {
				$ridea = mysql_fetch_assoc($rsidea);
				$ididea = $ridea['ididea'];
			}
			
			$q = "INSERT into idea_user_vote (idea_id, username, idea_uri) "
			   . "VALUES (" . $ididea . ", '" . mysql_real_escape_string($r['username']) . "', '" . mysql_real_escape_string($idea_uri) . "')"; 
			mysql_query($q, $con) or die(mysql_error());
		}
	
		$interval = rand(1,3);
		printf("Sleeping for %d second(s)\n", $interval);
		sleep($interval);
	}
	
	$sql = "UPDATE idea_user SET parsed = NOW() WHERE username = '" . mysql_real_escape_string($r['username']) . "'";
	mysql_query($sql, $con) or die(mysql_error());
}
