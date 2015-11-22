<?php

class curling {

	private $_ch = NULL;	
	
	private $_file = NULL;
	
	private $_html = NULL;
	
	private $_cookiejar = NULL;
	
	public function __construct()
	{
		$this->init();
	}
	
	private function init()
	{
		$this->_ch = curl_init();
		
		curl_setopt($this->_ch, CURLOPT_VERBOSE, 0);
		curl_setopt($this->_ch, CURLOPT_HEADER, 0);
		curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->_ch, CURLOPT_TIMEOUT,1000);
		curl_setopt($this->_ch, CURLOPT_COOKIEFILE, "cookies.txt");
		curl_setopt($this->_ch, CURLOPT_COOKIEJAR, "cookies.txt");
		curl_setopt($this->_ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $res
	 * @param unknown_type $data
	 */
	private function write($res, $data)
	{
		$this->_html .= $data;
		return strlen($data);
	}

	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $url
	 */
	function get_page($url)
	{
		if ($this->ch == NULL) $this->init();
		
		curl_setopt($this->_ch, CURLOPT_URL, $url);
		curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT, 5);
		$this->_html = curl_exec($this->_ch);
		if (curl_errno($this->_ch) !== 0) {
			throw new Exception("unable to retrive content", 100);
		}
		curl_close($this->_ch);
		
		return $this->_html;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $url
	 * @param unknown_type $data
	 */
	function post_page($url, $data)
	{
		
		if ($this->ch == NULL) $this->init();
		
		curl_setopt($this->_ch, CURLOPT_URL, $url);
		curl_setopt($this->_ch, CURLOPT_POST, true);
		curl_setopt($this->_ch, CURLOPT_POSTFIELDS, http_build_query($data));
		
		$this->_html = curl_exec($this->_ch);
		//curl_close($this->_ch);
		
		return $this->_html;
	}
	
	function close_session()
	{
		curl_close($this->_ch);
	}
	
}

?>