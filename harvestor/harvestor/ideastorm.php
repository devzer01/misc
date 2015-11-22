<?php

$con = mysql_connect('localhost:/var/run/mysqld/mysqld.sock', 'root', '');
mysql_select_db('ideastorm', $con) or die(mysql_error());
/**
 * $query = '//book/chapter/para/informaltable/tgroup/tbody/row/entry[. = "en"]';
 
 * elements
 * database http://www.ideastorm.com/ 
 * record -> /html/body/div[3]/div/div[2]/div/div[3]/form/div/span/div/table 
 * votes //div[@class='voteTotal']/*
 * title -> /html/body/div[3]/div/div[2]/div/div[3]/form/div/span/div/table/tbody/tr/td[2]/table/tbody/tr/td/span/a
 * 
 * 		foreach ($entries as $entry) {
    		echo "Found {$entry->previousSibling->previousSibling->nodeValue}," .
         		" by {$entry->previousSibling->nodeValue}\n";
		}

 */
// recordset /html/body/div[3]/div/div[2]/div/div[3]/form/div
// record //span div table
///html/body/div[3]/div/div[2]/div/div[3]/form/div/span/div/table/tbody/tr/td[2]/table/tbody/tr/td/span/a

if (!isset($argv[1]) && !isset($argv[2])) {
	print("usage:" . $argv[0] . " start_page max_page\n");
	exit(0);
}

$start_page = $argv[1];
$max_page = $argv[2];
for ($page = $start_page; $page <= $max_page; $page++) 
{
	$url = "http://www.ideastorm.com/ideaList?lsi=0&p=" . $page;
	print("Grabing " . $url . "\n");
	$harvestor = new harvestor($url, "//div[@class='baseIdeaList']/*");
	$nrec = $harvestor->get_results();
	print("Got $nrec records(s)\n");
	
	$field_classes = array('votes' => array(
											'class' => 'voteTotal', 
											'element' => 'div', 
											'db' => 'total_votes'
	),
						   'uri' => array(
											'class' => 'ideaTitle userContent', 
											'element' => 'span',
											'suffix'  => 'a', 
											'attr' => 'href',
											'db' => 'uri'
	), 
						   'title' => array(
											'class' => 'ideaTitle userContent', 
											'element' => 'span',
											'suffix'  => 'a', 
											'db' => 'title'
	),
						'comment_count' => array(
											'class' => 'ideaComments',
											'element' => 'span',
											'db'     => 'comment_count'
	),
						'username_expert' => array(
											'class' => 'baseUserExpert IdeaOtherContents',
											'element' => 'a',
											'suffix'  => 'span',
											'db' => 'username_expert'
	), 
						'username' => array(
											'class' => 'baseUserNormal IdeaOtherContents',
											'element' => 'a',
											'suffix'  => 'span',
											'db' => 'username'
	), 
						'user_uri' => array(
											'class'   => 'baseUserExpert IdeaOtherContents',
											'db'      => 'username_uri',
											'element' => 'a',
											'attr'    => 'href'
	),
						'user_uri' => array(
											'class'   => 'baseUserNormal IdeaOtherContents',
											'db'      => 'username_uri',
											'element' => 'a',
											'attr'    => 'href'
	),
						'category' => array(
											'class' => 'ideaCategories',
											'db'    => 'category',
											'element' => 'span'
	),
						'created' => array(
											'class' => 'commenterDate',
											'db'      => 'created',
											'element' => 'span',
											'istime'  => 1,
	), 
						'status'  => array(
											'class' => 'ideaStatusContents',
											'db'    => 'status',
											'element' => 'span'
	),
						'text' => array(
											'class' => 'baseIdeaBody',
											'element' => 'span',
											'db' => 'idea_text'
	)
	);
	
	
	$records = $harvestor->get_result($field_classes);
	
	foreach($records as $record) {
		$fields = array_keys($record);
		$cols = array();
		$vals = array();
		foreach($fields as $field) 
		{
			if ($record[$field] != '') {
				$cols[] = $field_classes[$field]['db'];
				$vals[] = mysql_real_escape_string($record[$field]);
			}
		}
		
		$sql = "INSERT INTO idea (" . implode(",", $cols) . ") VALUES ('" . implode("','", $vals) . "') ";
		if ($argv[3] == 'debug') print("$sql\n");
		mysql_query($sql, $con) or die(mysql_error());
	}
	$q = "INSERT INTO progress (page_number) values (" . $page . ")";
	mysql_query($q, $con) or die(mysql_error());
	print("Processing page " . $page . "/" . $max_page ."\n");
	$interval = rand(10, 50);
	print("waiting $interval seconds \n");
	sleep($interval);
}



class harvestor {
	
	private $_uri = NULL;
	
	private $_record = NULL;
	
	private $_extractor = NULL;
	
	private $_result = NULL;
	
	public function __construct($uri, $record)
	{
		$this->_uri = $uri;
		$this->_record = $record;
	}
	
	public function get_results()
	{
		$curling = new curling();
		$this->_extractor = new extractor($curling->get_page($this->_uri));
		
		return $this->_extractor->query($this->_record);
	}
	
	public function get_result($fields) 
	{
		$records = array();
		$results = $this->_extractor->fetch();
		for ($i=0; $i < $results->length - 1; $i++) 
		{
			$record = array();
			foreach($fields as $field_name => $attr) 
			{
				$xpath = '//' . $attr['element'] . '[@class=\'' . $attr['class'] . '\']';
				if (isset($attr['suffix'])) {
					$xpath .= "/child::*";
				}
				$list = $this->_extractor->field($xpath, $results->item($i));
				if (!isset($attr['attr'])) {
					if ($list->item($i)) {
						$record[$field_name] = $list->item($i)->textContent;
					} else {
						print("Error $xpath \n");
					}		
				} else {
					if ($list->item($i)) {
						$record[$field_name] = $list->item($i)->attributes->getNamedItem("href")->nodeValue;
					} else {
						print("Error $xpath \n");
					}
				}
				
				if (isset($attr['istime'])) {
					$record[$field_name] = date("Y-m-d", strtotime($record[$field_name]));
				}
			}
			$records[] = $record;
		}
		return $records;
	}
	
}

/**
 * 
 * Enter description here ...
 * @author root
 *
 */
class extractor {
	
	private $_doc = NULL;
	
	private $_xpath = NULL;
	
	private $_result = NULL;
	
	public function __construct($html)
	{
		$this->_doc = new DomDocument();

		// We need to validate our document before refering to the id
		$this->_doc->validateOnParse = false;
		$this->_doc->recover = false;
		$this->_doc->strictErrorChecking = false;
		@$this->_doc->loadHTML($html);
		
		$this->_xpath = new DOMXPath($this->_doc); //we don't need a member doc since its a 1 time deal
	}
	
	public function query($query)
	{		
		$this->_result = $this->_xpath->query($query);
		return $this->_result->length;
	}
	
	public function field($query, $context)
	{
		return $this->_xpath->query($query, $context);
	}
	
	public function fetch()
	{
		return $this->_result;
	}
	
}

/**
 * 
 * Enter description here ...
 * @author root
 *
 */
class curling {
	
	private $_ch = NULL;	
	
	private $_file = NULL;
	
	private $_html = NULL;
	
	public function __construct()
	{
		$this->_ch = curl_init();
		
		curl_setopt($this->_ch, CURLOPT_VERBOSE, 0);
		curl_setopt($this->_ch, CURLOPT_HEADER, 0);
		curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, 1);
		
	}
	
	private function write($res, $data)
	{
		$this->_html .= $data;
		return strlen($data);
	}

	function get_page($url)
	{
		curl_setopt($this->_ch, CURLOPT_URL, $url);
		$this->_html = curl_exec($this->_ch);
		curl_close($this->_ch);
		
		return $this->_html;
	}

}
