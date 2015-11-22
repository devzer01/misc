<?php
require_once 'db/commonDB.class.php';
/**
 * HomeController
 * 
 * @author
 * @version 
 */
require_once 'Zend/Controller/Action.php';
class HomeController extends Zend_Controller_Action
{
    /**
     * 
     * @var unknown_type
     */
    protected $host = NULL;
    
    /**
     * 
     * @var unknown_type
     */
    protected $path = NULL;
    
	public function init()
	{
		$front = Zend_Controller_Front::getInstance();
    	
    	$front->setParam('noErrorHandler', true);
    	$front->throwExceptions(true);
    	
      	$this->host = $_SERVER['HTTP_HOST'];
    	$this->path = $_SERVER['DOCUMENT_ROOT'];
    	
    	//printf("Path %s", $this->path);
      	$this->view   = new Zend_View_Smarty($this->path . '/../application/views/scripts');
        $viewRenderer = $this->_helper->getHelper('viewRenderer');
        $viewRenderer->setView($this->view)
        //             ->setViewBasePathSpec($view->_smarty->template_dir)
                     ->setViewScriptPathSpec(':controller/:action.:suffix')
                     ->setViewScriptPathNoControllerSpec(':action.:suffix')
                     ->setViewSuffix('tpl');
	}
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
       
    }
}
