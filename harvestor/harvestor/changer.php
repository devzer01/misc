<?php
$dbname = 'harvestor';
$url    = '';
require_once 'include/config.php';

require_once 'lib/curling.php';
require_once 'lib/extractor.php';
require_once 'lib/harvestor.php';

require_once 'lib/word/thesauruscom.php';

require_once 'lib/rewrite/articlechangernet.php';

$debug = false;

if (isset($argv[1]) && $argv[1] == 'debug') $debug = true;

$limit = 10;

if ($debug) $limit = 1;

$sql = "SELECT id, data_record FROM harvestor_data WHERE parsed IS NULL LIMIT " . $limit;
$rs = mysql_query($sql, $con) or die(mysql_error());

$changer = new articlechangernet();

while ($r = mysql_fetch_assoc($rs)) {
	
	$data = unserialize($r['data_record']);
	
	if (trim($data['body']) == '') {
		printf("Skipping Empty Record %d \n", $r['id']);
		$sql = "UPDATE harvestor_data SET parsed = NOW() WHERE id = " . $r['id'];
		mysql_query($sql, $con) or die(mysql_error());
	}

	$body = $data['body'];
	
	$data['body'] = $changer->change($data['body']);
	$data['heading'] = $changer->change($data['heading']);
	
	if (trim($body) == $data['body']) {
		echo "No Change";
	}
	
	
	$sql = "INSERT INTO harvestor_data_rewrite (id, data_record) VALUES (" . $r['id'] .", '" . mysql_real_escape_string(serialize($data)) . "')";
	mysql_query($sql, $con) or die(mysql_error());
	
	$sql = "UPDATE harvestor_data SET parsed = NOW() WHERE id = " . $r['id'];
	mysql_query($sql, $con) or die(mysql_error());
	
	$interval = rand(1,4);
	printf("Sleeping %d second(s)\n", $interval);
	sleep($interval);
	
}
