<?php

//require '../curling.php';
//require '../extractor.php';
//require '../harvestor.php';

//$original = "Hello How Are you Doing";
//
//$changer = new articlechangernet();
//$change = $changer->change($original);
//printf("Original %s\n Changed %s\n", $original, $change);

class articlechangernet {
	
	///login?destination=node/11 
	//name/pass form_build_id => form-cr11eHpiknZRxBCdNkIN8vr_aWh10TD9t_laWCdqhfw  form_id => user_login_block
	//                                                      form-cr11eHpiknZRxBCdNkIN8vr_aWh10TD9t_laWCdqhfw

	private $auth_uri = 'http://www.articlechanger.net/login?destination=node/11';
	
	private $auth_params = array(
								'name' => 'devzer0', 
								'pass' => 'V8UAEuP09X', 
								'form_build_id' => 'form-cr11eHpiknZRxBCdNkIN8vr_aWh10TD9t_laWCdqhfw',
								'form_id'       => 'user_login_block'
							);

	/**
		@var curling 
	 */
	private $_curl   = NULL;
	
	public function __construct()
	{
		$this->_curl = new curling();
		$response = $this->_curl->post_page($this->auth_uri, $this->auth_params);
		//printf("Login %s\n", $response);
	}
	
	public function change($data)
	{
		$postdata = array(
			'upmethod'	 => '0',
			'downmethod' =>	'0',
			'idhash'     => '',	
			'articlein'	 => $data,
			'uploadFile' => '',	
			'articlechoose' => '',	
			'optSwapParagraphs' => '1',
			'optCustomSynonyms' => '1',
			'optConvertFormat'  => '',	
			'submit' => 'Click Here to Rewrite Article!',
			'articleout' => ''
		);
		
		$response = $this->_curl->post_page('http://www.articlechanger.net/', $postdata);
		
		$extractor = new extractor($response);
		
		$rec = $extractor->query('//textarea[@id="articleout"]');
		if ($rec->length == 1) {
			return $rec->item(0)->nodeValue;
		}
		
		return false;
	}
}