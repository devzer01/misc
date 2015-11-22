<?php

class IndexController extends Zend_Controller_Action
{
    protected $host = NULL;

    public function init()
    {
        $this->host = $_SERVER['HTTP_HOST'];
    }

    public function indexAction()
    {
        if (!isset($_SESSION['authkey'])) {
        	$this->_helper->redirector->setCode(301);
            $this->_redirect('http://' . $this->host . '/auth');
        }
    }

	public function menuAction()
	{
		$this->getResponse()->setHeader("Content-Type", "text/xml");
	}
	
	public function toolbarAction()
	{
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
	}
	
	public function scheduleAction()
	{
		
	}
	
	public function dirAction()
	{
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
	}
	
	public function treeAction()
	{
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
	}

}

