<?php
define('DEFAULT_PORTAL', 1);
define('DEFAULT_PANEL', 2); //please use panel 2 for testing.
define('DEFAULT_LIST', 10);
/**
 * 
 * kinesis - sdk
 * 
 * @author nayana
 * @version 0.0.5-php
 *
 * @todo facebook has location ids, we can reference to the citynames for easy geo lookup
 */
class kinesis {
	
	private $curl = NULL;
	
	private $endpoint = 'https://web2.kinesissurvey.com/researchforgoodpanel/api.pro';
	
	private $sessionkey = NULL;
	
	private $publisher_id = 'LAB42';
	
	private $portal_seskey = NULL;
	
	private $gotgods = 'http://www.saasgrasp.com';
	
	/**
	 * 
	 * @param string $key force a session key
	 */
	public function __construct($key = NULL)
	{
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curl, CURLOPT_HEADER, 0);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
		
		if (!is_null($key)) {
			$this->sessionkey = $key;
		}
	}
	
	/**
	 * 
	 * @param unknown_type $method
	 * @param unknown_type $data
	 * @return mixed
	 */
	private function dopost($method = 'integration.auth.login', $data)
	{
		$url = $this->endpoint . '?method=' . $method;
		$data = "data=" . json_encode($data);
		curl_setopt($this->curl, CURLOPT_URL, $url);
		curl_setopt($this->curl, CURLOPT_POST, 1);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_HEADER, 0);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); // RETURN THE CONTENTS curl_setopt($ch, CURLOPT_TIMEOUT, 10); // TIMEOUT IN 10 SECONDS
		
		// grab URL and pass it to the browser
		$html = curl_exec($this->curl);
		
		//print_r($html);
		
		// close cURL resource, and free up system resources
		//curl_close($this->curl);
		
		return json_decode($html);
	}
	
	/**
	 * must authenticate and create a session prior to executing other api methods
	 * 
	 * @todo the secondary methods at this time do not test for an existing session and may throw exceptions
	 * @param string $username username for api - lab42
	 * @param string $password password for api - 12345678
	 * @throws Exception
	 * @return mixed
	 */
	public function login($username, $password) 
	{
		$data = new stdClass();
		$data->force = TRUE;
		$data->username = $username;
		$data->password = $password;
		
		$data = $this->dopost('integration.auth.login', $data);
		//print_r($data);
		if ($data->success) {
			$this->sessionkey = $data->data->sesKey;
		} else {
			throw new Exception("error login", 1);
		}
		return $data;
	}
	
	/**
	 * 
	 */
	public function logout()
	{
		$data = new stdClass();
		$data->sesKey = $this->sessionkey;
		return $this->dopost('integration.auth.logout', $data);
	}
	
	/**
	 * 
	 */
	private function selectPanel()
	{
		$data = new stdClass();
		$data->sesKey = $this->sessionkey;
		$data->panelid = DEFAULT_PANEL;
		return $this->dopost('integration.panel.select', $data);
	}
	
	/**
	 * Creates a panelist in the remote system
	 * 
	 * @param string $firstname
	 * @param string $lastname
	 * @param string $email
	 * @param int $gender
	 * @param date $dob mm/dd/yyyy
	 * @param string $password
	 * @param string $ident
	 * @param int $lang
	 * @return int panelist identification
	 */
	public function createPanelist($firstname, $lastname, $email, $gender, $dob, $password, $ident, $lang = 1)
	{
		$this->selectPanel();
// 		if (!is_numeric($gender)) {
// 			throw new Exception("Invalid Gender Value (1=male, 2=female)", 2);	
// 		}

		$data = new stdClass();
		$data->sesKey = $this->sessionkey;
		
		$params = array(
				'email' => $email, 
				'password' => $password, 
				'identifier' => $ident, 
				'sourceid' => $this->publisher_id,
				'subscribed' => 'yes',
				'Qfname'     => $firstname,
				'Qlname'     => $lastname,
				'Qgender'    => $gender,
				'Qbirthday'  => $dob,
				'Qcountry'   => 1
				);
		
		foreach($params as $label => $answer) {
			$set = new stdClass();
			$set->label = $label;
			$set->answer = $answer;
			$data->settings[] = $set;
		}
				
		//email , password, identifier, sourceid, language, subscribed
		return $this->dopost('integration.panelist.create', $data);
	}
	
	/**
	 * login a user to the panel portal for querying available surveys and
	 * activity
	 * 
	 * @param unknown_type $email
	 */
	public function portellogin($email)
	{
		$auth = new stdClass();
		$auth->username = $email;
		$auth->password = DEFAULT_PASSWORD;
		$auth->panelid  = DEFAULT_PANEL;
		$auth->portalid = DEFAULT_PORTAL;
		$data = $this->dopost('portal.auth.login', $auth);
		//print_r($data);
		if (!$data->success) return false;
		$this->portal_seskey = $data->seskey;
		return $data;
		//echo $this->portal_seskey;
	}
	
	/**
	 * 
	 * returns a jason array of completed surveys
	 * 
	 * @return mixed
	 */
	public function portalactivity()
	{	
		$lst = new stdClass();
		$lst->seskey = $this->portal_seskey;
		$lst->limit = DEFAULT_LIST;
		return $this->dopost('portal.panelist.activity', $lst);
		//portal.panelist.activity
	}
	
	/**
	 * returns a jason array of surveys to be taken 
	 * @return mixed
	 */
	public function getsurveys()
	{
		
		$lst = new stdClass();
		$lst->seskey = $this->portal_seskey;
		$lst->limit = DEFAULT_LIST;
		return $this->dopost('portal.survey.available', $lst);
		
	}
	
	/**
	 * 
	 * @return mixed
	 */
	public function portal_authvalidate()
	{
		$lst = new stdClass();
		$lst->seskey = $this->portal_seskey;
		return $this->dopost('portal.auth.validate', $lst);
	}
	
	/**
	 * 
	 * @param string $firstname
	 * @param string $lastname
	 * @param string $email
	 * @param int $gender
	 * @param date $dob
	 * @param string $password
	 * @param string $ident
	 * @param int $lang
	 */
	public function panelistupdate($firstname, $lastname, $email, $gender, $dob, $password, $ident, $lang = 1)
	{
		$this->selectPanel();
		// 		if (!is_numeric($gender)) {
		// 			throw new Exception("Invalid Gender Value (1=male, 2=female)", 2);
		// 		}
		
		$data = new stdClass();
		$data->sesKey = $this->sessionkey;
		
		$params = array(
				'email' => $email,
				'password' => $password,
				'identifier' => $ident,
				'sourceid' => $this->publisher_id,
				'subscribed' => 'yes',
				'Qfname'     => $firstname,
				'Qlname'     => $lastname,
				'Qgender'    => $gender,
				'Qbirthday'  => $dob,
				'Qcountry'   => 1
		);
		
		foreach($params as $label => $answer) {
			$set = new stdClass();
			$set->label = $label;
			$set->answer = $answer;
			$data->settings[] = $set;
		}
		return $this->dopost('integration.panelist.update', $data);
	}

	/**
	 * 
	 * @param email $email
	 * @param datapoint $datapoint
	 * @param value $value
	 */
	public function setPanelistAttr($email, $datapoint, $value) 
	{
		$this->selectPanel();
		
		$data = new stdClass();
		$data->sesKey = $this->sessionkey;
		
		$elm = new stdClass();
		$elm->label = 'email';
		$elm->answer = $email;
		$data->settings[] = $elm;
		
		$set = new stdClass();
		$set->label = $datapoint;
		$set->answer = $value;
		$data->settings[] = $set;
		
		return $this->dopost('integration.panelist.update', $data);
	}
	
	/**
	 * 
	 * @param string $email
	 * @param mixed $attr (array ('datapoint' => 'value'))
	 */
	public function setPanelistAttrBatch($email, $attr)
	{
		$this->selectPanel();
		
		$data = new stdClass();
		$data->sesKey = $this->sessionkey;
		
		$elm = new stdClass();
		$elm->label = 'email';
		$elm->answer = $email;
		$data->settings[] = $elm;
		
		foreach ($attr as $label => $answer) {
			$set = new stdClass();
			$set->label = $label;
			$set->answer = $answer;
			$data->settings[] = $set;
		}
		
		return $this->dopost('integration.panelist.update', $data);
	}
	
	public function dataPointCreate($label, $qtext, $type, $choices = array())
	{
		$this->selectPanel();
		
		$data = new stdClass();
		$data->sesKey = $this->sessionkey;
		
		$dp = new stdClass();
		$dp->label = $label;
		$dp->qtext = $qtext;
		$dp->type  = $type;
		
		if (!empty($choices)) {
			foreach ($choices as $choice) {
				$ch = new stdClass();
				$ch->choiceid = $choice['choiceid'];
				$ch->name     = $choice['name'];
				$ch->custom   = $choice['custom'];
				
				$dp->choices[] = $ch;
			}
		}
		
		$data->settings = $dp;
		
		return $this->dopost('integration.datapoint.create', $data);
	}
	
	public function dataPointDelete()
	{
		//integration.datapoint.delete
	}

	/**
	 * @param string $category name of the charity category
	 */
	public function getCharityRecords($category)
	{
		return $this->dogod('search', array('category' => $category));
	}
	
	/**
     * get a list of charity categories available
	 */
	public function getCharityCategory()
	{
		return $this->dogod('category', array('null'));
	}
	
	/**
     * 
	 */
	protected function dogod($method, $data = array())
	{
		$url = $this->gotgods . '/index/' . $method;
		$data = "data=" . json_encode($data);
		curl_setopt($this->curl, CURLOPT_URL, $url);
		curl_setopt($this->curl, CURLOPT_POST, 1);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_HEADER, 0);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); // RETURN THE CONTENTS curl_setopt($ch, CURLOPT_TIMEOUT, 10); // TIMEOUT IN 10 SECONDS
		
		// grab URL and pass it to the browser
		$html = curl_exec($this->curl);
		
		//print_r($html);
		
		// close cURL resource, and free up system resources
		//curl_close($this->curl);
		
		return json_decode($html);
	}
}