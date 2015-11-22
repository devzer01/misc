<?php

class MailController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	//https://accounts.google.com/o/oauth2/auth
   		$config = array(
			'callbackUrl' => 'http://dev.ersp.corp-gems.com/oauth2callback',
		    'siteUrl' => 'https://accounts.google.com/o/oauth2/auth',
		    'consumerKey' => '708667817077.apps.googleusercontent.com',
		    'consumerSecret' => 'fmclMOe1vJYf8suwr0zzlH9t'
    	);
    	$consumer = new Zend_Oauth_Consumer($config);
    	
    	$client = $token->getHttpClient($configuration);
		$client->setUri('http://twitter.com/statuses/update.json');
		$client->setMethod(Zend_Http_Client::POST);
		$client->setParameterPost('status', $statusMessage);
		$response = $client->request();
    	
        $feed = new Zend_Feed_Atom('https://mail.google.com/mail/u/1/feed/atom');
        $this->view->feed = $feed;
    }

    public function listAction()
    {
        $this->getResponse()->setHeader("Content-Type", "text/xml");
    }

}

