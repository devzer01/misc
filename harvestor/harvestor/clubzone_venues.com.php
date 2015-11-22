<?php
$dbname = 'business';
$url    = '';
require_once 'include/config.php';

require_once 'lib/curling.php';
require_once 'lib/extractor.php';
require_once 'lib/harvestor.php';

if (!isset($argv[1]) || !isset($argv[2])) {
	printf("usage: %s category location\n", $argv[0]);
	exit(0);
}

if ($argv[1] == 'db' && $argv[2] == 'db') {
	
	$sql = "select c.province_state, c.location, c.id as city_id, ct.category, ct.id as category_id from city AS c "
	     . "JOIN category AS ct "
	     . "LEFT OUTER JOIN category_location AS cc ON c.id = cc.location_id "
	     . " AND ct.id = cc.category_id "
	     . "WHERE ct.parsed IS NULL AND c.parsed IS NULL AND cc.location_id IS NULL AND"
	     . " cc.category_id IS NULL LIMIT 1";
	
	$rs = mysql_query($sql, $con) or die(mysql_error());
	$r = mysql_fetch_assoc($rs);
	$location = $r['location'] . ' ' . $r['province_state'];
	$category = $r['category'];
	
	$location = preg_replace("/ /", "+", $location);
	$category = preg_replace("/ /", "+", $category);
	
	printf("Location %s Category %s\n", $location, $category);
	
	$sql = "INSERT INTO category_location (location_id, category_id) VALUES (" . $r['city_id'] . "," .  $r['category_id'] . ") ";
	mysql_query($sql, $con) or die(mysql_error());
		
} else {
	$category = $argv[1];
	$location = $argv[2];
}
//stage 1
////*[@id="sitemap_date"]
$url   = "http://www.yellowpages.ca/search/si/1/" . $category . "/" . $location;
$xpath = '//*[@class="listingDetail"]';
printf("Trying URL %s \n", $url);

$harvestor = new harvestor($url, $xpath);
$recs = $harvestor->get_results();
@$pages = explode(" ", trim($harvestor->query('//*[@class="pagingNumOfPages"]')->item(0)->nodeValue));
if (count($pages) == 1) {
	$pages[3] = 1;
}
@printf("Got %d records(s) and %d page(s)\n", $recs->length, $pages[3]);
for ($page = 1; $page <= $pages[3]; $page++) {	
	if ($page != 1) {
		$nurl = "http://www.yellowpages.ca/search/si/" . $page . "/" . $category . "/" . $location;
		$harvestor = new harvestor($nurl, $xpath);
		$recs = $harvestor->get_results();
		printf("Got %s url with %d record(s)\n", $nurl, $recs->length);
	}
	
	for ($i=0; $i < $recs->length; $i++) 
	{
		$listing_title = trim($harvestor->get_result('h3/*', $recs->item($i))->item(0)->nodeValue);
		$listing_phone = trim($harvestor->get_result('div/*', $recs->item($i))->item(0)->nodeValue);
		
		$sql = "SELECT id FROM business WHERE phone = '" . mysql_real_escape_string($listing_phone) . "'";
		$rs = mysql_query($sql, $con) or die(mysql_error());
		if (mysql_num_rows($rs) > 0) continue;
		
		$listing_note  = trim($harvestor->get_result('div[2]/*', $recs->item($i))->item(0)->nodeValue);
		$listing_addr  = explode(",", $listing_note);
		if (count($listing_addr) == 1) {
			@$listing_addr  = explode(",", trim($harvestor->get_result('div[3]/*', $recs->item($i))->item(0)->nodeValue));
		}
		$street = trim($listing_addr[0]);
		@$city   = trim($listing_addr[1]);
		@$state  = substr(trim($listing_addr[2]), 0, 2);
		@$postal = substr(trim($listing_addr[2]), 3);
		
		$listing_category  = preg_replace("/Category:/", "", trim($harvestor->get_result('div[2]/*', $recs->item($i))->item(1)->nodeValue));
		@$listing_category  .= "|" . trim($harvestor->get_result('div[2]/*', $recs->item($i))->item(2)->nodeValue);
		
		$sql = "INSERT INTO business (name, phone, street, city, state_province, postal_zip, category, note) "
		     . "VALUES ('" . mysql_real_escape_string($listing_title) . "', '" . mysql_real_escape_string($listing_phone) . "', '" . mysql_real_escape_string($street) . "', '" . mysql_real_escape_string($city) . "', '" . mysql_real_escape_string($state) . "', '" . mysql_real_escape_string($postal) . "', '" . mysql_real_escape_string($listing_category) . "', '" . mysql_real_escape_string($listing_note) . "')";
		mysql_query($sql, $con) or die(mysql_error());
		//printf("Item %s %s %s %s\n", $listing_title, $listing_phone, $listing_addr, $listing_category);
	}
	
	$rand = rand(1,4);
	printf("Sleeping %d second(s)\n", $rand);
	sleep($rand);
}