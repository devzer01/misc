<?php
$dbname = 'ideastorm';
$url    = '';
require_once 'include/config.php';

require_once 'lib/curling.php';
require_once 'lib/extractor.php';
require_once 'lib/harvestor.php';

//stage 1
////*[@id="sitemap_date"]
$sql = "SELECT ididea, uri FROM idea WHERE comment_count > 0 AND uri_parsed IS NULL";
$rs = mysql_query($sql, $con) or die(mysql_error());

$xpath = '//div[@class="commentdetails"]/table/tbody/tr';
while($r = mysql_fetch_assoc($rs)) 
{
	$url = "http://www.ideastorm.com" . $r['uri'];
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
			$username     = $harvestor->get_result('td[1]/span/span/span/span/a/span', $recs->item($i))->item(0)->nodeValue;
			$username_uri = $harvestor->get_result('td[1]/span/span/span/span/a', $recs->item($i))->item(0)->attributes->getNamedItem('href')->nodeValue;
			$comment_date = $harvestor->get_result('td[1]/span[2]/span/span', $recs->item($i))->item(0)->nodeValue;
			$comment      = $harvestor->get_result('td[2]/div/div/span', $recs->item($i))->item(0)->nodeValue;

			//commenterDate td[2]/div/div/span
			//printf("Username %s URI %s date %s comment %s \n", $username, $username_uri, $comment_date, $comment);
			
			$q = "INSERT into idea_comment (idea_id, username, username_uri, comment_date, parsed, comment) "
			   . "VALUES (" . $r['ididea'] . ", '" . mysql_real_escape_string($username) . "', '" . mysql_real_escape_string($username_uri) . "', '" . date("Y-m-d", strtotime($comment_date)) . "', NOW(), '" . mysql_real_escape_string($comment) . "')";
			mysql_query($q, $con) or die(mysql_error());
		}
	
		$interval = rand(1,3);
		printf("Sleeping for %d second(s)\n", $interval);
		sleep($interval);
	}
	
	$sql = "UPDATE idea SET uri_parsed = NOW() WHERE ididea = " . $r['ididea'];
	mysql_query($sql, $con) or die(mysql_error());
}
