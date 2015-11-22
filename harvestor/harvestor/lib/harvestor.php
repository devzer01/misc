<?php

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
	
	public function query($xpath)
	{
		return $this->_extractor->query($xpath);
	}
	
	public function get_result($field, $record) 
	{
		if ($record == NULL) return false;
		return $this->_extractor->field($field, $record);
	}
	
	public function get_post_results($data)
	{
		
	}
}

?>