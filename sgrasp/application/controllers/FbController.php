<?php
define('DEFAULT_PASSWORD', 1);
require_once('facebook.php');
require_once('kinesis.php');

class FbController extends Zend_Controller_Action
{
	protected $user = NULL;

	protected $user_profile = NULL;
	
	protected $facebook = NULL;
	
    public function init()
    {
    	$front = Zend_Controller_Front::getInstance();
    	
    	$front->setParam('noErrorHandler', true);
    	
    	$config = array();
    	$config['appId'] = '325030227510034';
    	$config['secret'] = 'c92bd9f674d7a8586dccbdf24e229a7c';
    	$config['fileUpload'] = false; // optional
    	
    	$this->facebook = new Facebook($config);
    	
//     	$registry = new Zend_Registry(array('facebook' => $facebook));
//     	Zend_Registry::setInstance($registry);
    	
//     	$facebook = Zend_Registry::get('facebook');
    	
    	// Get User ID
    	$this->user = $this->facebook->getUser();
    	
    	if ($this->user) {
    		try {
    			// Proceed knowing you have a logged in user who's authenticated.
    			$this->user_profile = $this->facebook->api('/me');
    		} catch (FacebookApiException $e) {
    			error_log($e);
    			$user = null;
    		}
    	}
    	
    }
    
    public function authAction()
    {
    	$app_id = "325030227510034";
    	
    	$canvas_page = "http://dev.gotgods.com/fb/";
    	
    	$auth_url = "https://www.facebook.com/dialog/oauth?client_id="
    	. $app_id . "&redirect_uri=" . urlencode($canvas_page);
    	
    	$signed_request = $_REQUEST["signed_request"];
    	
    	list($encoded_sig, $payload) = explode('.', $signed_request, 2);
    	
    	$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
    	
    	if (empty($data["user_id"])) {
    		echo("<script> top.location.href='" . $auth_url . "'</script>");
    	} else {
    		echo ("Welcome User: " . $data["user_id"]);
    	}
    }

    public function registerAction()
    {
		$kn = new kinesis();
    	//print_r($ret);
    	$params = $this->getRequest()->getParams();
    	try {
    		$ret = $kn->login('Grasp', 'V8UAEyP09X');
    		$kn->createPanelist($params['firstname'], $params['lastname'], 
    					$params['email'], $params['gender'], $params['dob'], 
    					DEFAULT_PASSWORD, $params['uid']);
    		$kn->logout();    
    	} catch (Exception $e) {
    		printf("Error %s", $e->getMessage());
    	}
    }
    
    public function activityAction()
    {
    	$kn = new kinesis();
    	try {
    		$ret = $kn->portellogin($this->user_profile['email']);
    		$kn->portalactivity();
    	} catch (Exception $e) {
    		printf("Error %s", $e->getMessage());
    	}
    }
    
    public function listAction()
    {
    	$kn = new kinesis();
    	try {
    		$ret = $kn->portellogin($this->user_profile['email']);
    		$kn->getsurveys();
    	} catch (Exception $e) {
    		printf("Error %s", $e->getMessage());
    	}
    }
    
    public function homeAction()
    {
    	$kn = new kinesis();
    	try {
    		$ret = $kn->portellogin($this->user_profile['email']);
    		$this->view->surveys  = $kn->getsurveys();
    		$this->view->activity = $kn->portalactivity();
    		$this->view->profile  = $this->user_profile; 
    		$this->view->category = $kn->getCharityCategory();
    		$this->view->charity  = $kn->getCharityRecords('C1_Arts_Culture_and_Humanities');
    	} catch (Exception $e) {
    		printf("Error %s", $e->getMessage());
    	}
    }
    
    public function indexAction()
    {
		// Login or logout url will be needed depending on current user state.
		if ($this->user) {
		  $this->view->logoutUrl = $this->facebook->getLogoutUrl();
		} else {
		  $this->view->loginUrl = $this->facebook->getLoginUrl();
		}
		
		// This call will always work since we are fetching public data.
		$this->view->naitik = $this->facebook->api('/naitik');
		
		$this->view->user = $this->user;
		$this->view->facebook = $this->facebook;
		$this->view->user_profile = $this->user_profile;
		
		$kn = new kinesis();
		try {
			if($kn->portellogin($this->user_profile['email'])) {
				$this->_forward('home');
				return true;
			}
			
		} catch (Exception $e) {
			printf("Error %s", $e->getMessage());
		}
    }


}

