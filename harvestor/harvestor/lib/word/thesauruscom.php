<?php

//include '../curling.php';
//include '../extractor.php';
//include '../harvestor.php';

//if (!isset($argv[1])) {
//	printf("Usage: %s word \n", $argv[0]);
//	exit(0);
//}
//
//$dict = new dictionary();
//$result = $dict->lookup($argv[1]);
//printf("Result was %s \n", $result);

class dictionary 
{

	private $_thesaurus = NULL;
	
	private $_con       = NULL;
	
	private $_cache     = NULL;
	
	private $_last_connect_ts = NULL;
	
	private $__aspell   = NULL;
	
	private $_dict      = NULL;
	
	public function __construct()
	{
		$this->_thesaurus = new thesauruscom();
		$this->_con = mysql_connect('localhost:/var/run/mysqld/mysqld.sock', 'root', '', true);
		mysql_select_db("words", $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
		
		$this->__aspell = enchant_broker_init();
		if (enchant_broker_dict_exists($this->__aspell,'en_US')) {
			$this->_dict = enchant_broker_request_dict($this->__aspell, 'en_US');
		}
	}
	
	public function isverb($word)
	{
		if ($this->lookup($word)) {
			$sql = "SELECT word_type FROM word WHERE word = '" . mysql_real_escape_string($word) . "' AND word_type = 'verb' ";
			$rs = mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
			if (mysql_num_rows($rs) > 0) return true;
		}
		return false;
	}
	
	public function isnoun($word)
	{
		if ($this->lookup($word)) {
			$sql = "SELECT word_type FROM word WHERE word = '" . mysql_real_escape_string($word) . "' AND word_type = 'noun' ";
			$rs = mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
			if (mysql_num_rows($rs) > 0) return true;
		}
		return false;
	}
	
	public function isspelled($word)
	{
		if (!enchant_dict_check($this->_dict, $word)) {
			return false;
		}
		return true;
	}
	
	public function lookup($word) 
	{
		$this->_cache = NULL;
		if (!enchant_dict_check($this->_dict, $word)) {
			$suggest = enchant_dict_suggest($this->_dict, $word);
			if (count($suggest) > 0) {
				printf("possible miss-spelled word %s replacing with %s\n", $word, $suggest[0]);
				$word = $suggest[0];
			} else {
				return false;
			}
		}
		if ($this->__internal($word) == false) {
			$current_ts = time();
			if (($current_ts - $this->_last_connect_ts) <= 2) {
				$interval = rand(1,4);
				printf("Pausing for %d second(s) \n", $interval);
				sleep($interval);
			} 
			$this->__external($word);
		}
		
		return $this->__internal($word);
	}
	
	private function __internal($word)
	{
		if ($this->_cache != NULL) return $this->_cache;
		$sql = "SELECT id FROM word WHERE word = '" . mysql_real_escape_string($word) . "' AND word_type IS NOT NULL";
		$rs = mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
		
		if (mysql_num_rows($rs) == 0) return false;
		
		$r = mysql_fetch_assoc($rs);
		
		$sql = "SELECT count(*) AS count FROM reference WHERE primary_word_id = " . $r['id'] . " AND reference_type_id = 1 ";
		$rs = mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
		$rc = mysql_fetch_assoc($rs);
		printf("Found %d matching synonum(s)\n", $rc['count']);
		$max = $rc['count'];
		$rand = rand(1, ($max - 1));
		
		$sql = "SELECT word FROM word WHERE id = (SELECT reference_word_id FROM reference WHERE primary_word_id = " . $r['id'] . " AND  reference_type_id = 1 LIMIT " . ($rand) . ",1) ";
		$rs = mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
		$rword = mysql_fetch_assoc($rs);
		$this->_cache = $rword['word'];
		return $rword['word']; 
	}
	
	
	private function __external($word) 
	{
		$this->_last_connect_ts = time();
		$records = $this->_thesaurus->lookup($word);
		
		foreach($records as $record) {
			
			$sql = "SELECT id, word_type FROM word WHERE word = '" . mysql_real_escape_string($record['word']) . "'";
			$rs = mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
			if (mysql_num_rows($rs) == 0) {
				$sql = "INSERT INTO word (word, word_type) VALUES ('" . mysql_real_escape_string($record['word']) . "','" . $record['word_type'] . "')";
				mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
				$word_id = mysql_insert_id($this->_con);
			} else {
				$r = mysql_fetch_assoc($rs);
				$word_id = $r['id'];
				if ($r['word_type'] == NULL) {
					$sql = "UPDATE word SET word_type = '" . mysql_real_escape_string($record['word_type']) . "' WHERE id = " . $word_id;
					mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
				}
			}
			
			if (isset($record['adjective'])) {
				foreach ($record['adjective'] as $adj) {
					$adj = trim($adj);
					$sql = "INSERT INTO adjective (word_id, adjective) VALUES (" . $word_id . ", '" . mysql_real_escape_string($adj) . "')";
					mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
				}
			} else { 
				foreach ($record['definition'] as $def) {
					$def = trim($def);
					$sql = "INSERT INTO definition (word_id, definition) VALUES (" . $word_id . ", '" . mysql_real_escape_string($def) . "')";
					mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
				}
			}
			
			
			
			foreach ($record['synonums'] as $synonum) {
				if (trim($synonum) == "") continue;
				$synonum = trim(preg_replace("/\n/", "", $synonum));
				
				$sql = "SELECT id FROM word WHERE word = '" . mysql_real_escape_string($synonum) . "'";
				$rs = mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
				if (mysql_num_rows($rs) == 0) {
					$sql  = "INSERT INTO word (word) VALUES ('" . mysql_real_escape_string($synonum) . "')";
					mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
					$ref_word_id = mysql_insert_id($this->_con);
				} else {
					$r = mysql_fetch_assoc($rs);
					$ref_word_id = $r['id'];
				}
				
				$sql = "INSERT INTO reference (primary_word_id, reference_word_id, reference_type_id) "
				     . " VALUES (" . $word_id . "," . $ref_word_id . ", 1) ";
				mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
			}
			
			foreach ($record['antonums'] as $antonum) {
				if (trim($antonum) == "") continue;
				$antonum = trim(preg_replace("/\n/", "", $antonum));
				$sql = "SELECT id FROM word WHERE word = '" . mysql_real_escape_string($antonum) . "'";
				$rs = mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
				if (mysql_num_rows($rs) == 0) {
					$sql  = "INSERT INTO word (word) VALUES ('" . mysql_real_escape_string($antonum) . "')";
					mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
				
					$ref_word_id = mysql_insert_id($this->_con);
				} else {
					$r = mysql_fetch_assoc($rs);
					$ref_word_id = $r['id'];
				}
				
				$sql = "INSERT INTO reference (primary_word_id, reference_word_id, reference_type_id) "
				     . " VALUES (" . $word_id . "," . $ref_word_id . ", 2) ";
				mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
			}
			
			if (isset($record['notes'])) {
				$sql = "INSERT INTO note (word_id, note) VALUES (" . $word_id . ",'" . mysql_real_escape_string($record['notes'][0]) ."')";
				mysql_query($sql, $this->_con) or die(__FILE__ . ":" . __LINE__ . " " . mysql_error());
			}
		}

	}
}


class thesauruscom 
{
	//lookup record record //table[@class="the_content"]/* (or not *)
	//lookup word span/[@class="queryn"]/b
	//word_type tr[2]/td[2]/span/* or span[@class="hwc"]
	//audio file embed[@id="speaker"] attributes->getNamedItem("flashvars")
	//definition tr[3]/td[2]/span/* or span[@class="hwc"]
	//synonums tr[4]/td[2]/span/span[@class="hwc"] 
	public function lookup($word)
	{
		$url = "http://thesaurus.com/browse/" . $word;
		$xpath = '//table[@class="the_content"]';
		
		printf("Trying %s \n", $url);
		$harvestor = new harvestor($url, $xpath);
		$recs = $harvestor->get_results();
		printf("Found %d record(s)\n", $recs->length);
		
		$records = array();
		
		for ($i=0; $i<$recs->length; $i++) 
		{
			$record = array();
			if ($i >= 1) {
				$xpath_word = 'tr/td[2]/a';
				//printf("Trying step %d for word %s\n", $i, $word);
				@$word = $harvestor->get_result($xpath_word, $recs->item($i))->item(0)->nodeValue;
			} 
			$record['word'] = $word;
			
			$xpath_word_type = 'tr[2]/td[2]/i';
			$record['word_type'] = $harvestor->get_result($xpath_word_type, $recs->item($i))->item(0)->nodeValue;
			
			$xpath_related   = 'tr[3]/td[1]';
			$items = $harvestor->get_result($xpath_related, $recs->item($i));
			$tr3_title = trim(preg_replace("/\n/", "", $items->item(0)->nodeValue));
			$key = 'definition';
			//printf("Row 3 title length is %d item desc is %s\n", $items->length, $tr3_title);
			
			if ($tr3_title == "Related Adjectives:") {
					$key = 'adjective';
			} 
			
			
			$xpath_definition = 'tr[3]/td[2]';
			$record[$key] = explode(",", $harvestor->get_result($xpath_definition , $recs->item($i))->item(0)->nodeValue);
			
			
			//Related Adjectives
			$xpath_synonum   = 'tr[4]/td[2]/span';
			$record['synonums'] = explode(",",$harvestor->get_result($xpath_synonum , $recs->item($i))->item(0)->nodeValue);
			
			$xpath_notes = 'tr[5]/td';
			@$notes_title = $harvestor->get_result($xpath_notes , $recs->item($i))->item(0)->nodeValue;
			$key = "antonums";
			//printf("Notes %s title\n", $notes_title);
			if (trim($notes_title) == "Notes:") {
				$key = "notes";
				$record['antonums'] = array();
			} 
			
			$xpath_antonums  = 'tr[5]/td[2]/span';
			@$record[$key]  = explode(",", $harvestor->get_result($xpath_antonums , $recs->item($i))->item(0)->nodeValue);	
			
			
			

			$records[] = $record;
		}
		
		return $records;
		
	}
}

?>