<?php
$dbname = 'harvestor';
$url    = '';
require_once 'include/config.php';

require_once 'lib/curling.php';
require_once 'lib/extractor.php';
require_once 'lib/harvestor.php';

require_once 'lib/word/thesauruscom.php';

$debug = false;

if (isset($argv[1]) && $argv[1] == 'debug') $debug = true;

$limit = 10;

if ($debug) $limit = 1;

$sql = "SELECT id, data_record FROM harvestor_data WHERE parsed IS NULL LIMIT " . $limit;

$rs = mysql_query($sql, $con) or die(mysql_error());

$dict = new dictionary();

while ($r = mysql_fetch_assoc($rs)) {
	
	$data = unserialize($r['data_record']);
	
	$words = explode(" ", $data['body']);
	$total_words = count($words);
	$skipped     = 0;
	$notfound    = 0;
	$replaced    = 0;
	
	if ($debug) {
		$skip_words     = array();
		$notfound_words = array();
	}
	
	printf("Total word(s) %d \n", $total_words);
	
	for($i = 0; $i < count($words); $i++) {
		if (strlen($words[$i]) <= 5) {
			$skipped++;
			if ($debug) {
				$skip_words[] = $words[$i];
			}
			continue;	
		}
		
		//skip the words with apostephie
		if (preg_match("/'/", $words[$i])) {
			$skipped++;
			if ($debug) {
				$skip_words[] = $words[$i];
			}
			continue;
		}
		
		//add punchuation extract and reset
		$period = false;
		if (preg_match("/\.$/", $words[$i])) {
			$period = true;
			$words[$i] = preg_replace("/\.$/", "", $words[$i]);
		}
		
		$exclam = false;
		$excl_match = array();
		if (preg_match("/([^a-zA-Z]+)$/", $words[$i], $excl_match)) {
			$exclam = true;
			$words[$i] = preg_replace("/[^a-zA-Z]+$/", "", $words[$i]);
		}
		
		
		$result = $dict->lookup($words[$i]);
		
		if ($result !== false) {
			$words[$i] = $result;
			if ($exclam) $words[$i] .= $excl_match[0];
			if ($period) $words[$i] .= ".";
			$replaced++;
		} else {
			$notfound++;
			if ($debug) {
				$notfound_words[] = $words[$i];
			}
		}
	}
	
	printf("Out of %d words, %d replaced, %d skipped, %d not found\n", $total_words, $replaced, $skipped, $notfound);

	if ($debug) {
		printf("Not found words %s \n", print_r($notfound_words, true));
	}
	
	$headings = explode(" ", $data['heading']);
	
	for($i = 0; $i < count($headings); $i++) {
		if (strlen($headings[$i]) <= 5) continue;
		
		$result = $dict->lookup($headings[$i]);
		
		if ($result !== false) {
			$headings[$i] = $result;
		}
	}
	
	$data['heading'] = implode(" ", $headings);
	$data['body']    = implode(" ", $words);
	
	$sql = "INSERT INTO harvestor_data_rewrite (id, data_record) VALUES (" . $r['id'] .", '" . mysql_real_escape_string(serialize($data)) . "')";
	mysql_query($sql, $con) or die(mysql_error());
	
	$sql = "UPDATE harvestor_data SET parsed = NOW() WHERE id = " . $r['id'];
	mysql_query($sql, $con) or die(mysql_error());
	
}