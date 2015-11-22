<?php

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
		return $this->_result;
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

?>