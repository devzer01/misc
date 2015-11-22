<?php

require_once "db/studyDB.class.php";
require_once "db/proposalDB.class.php";
require_once "db/commonDB.class.php";
require_once "db/accountDB.class.php";
require_once "app/pgen/ProposalManager.class.php";
require_once "app/pgen/RevisionManager.class.php";
require_once "app/pgen/PricingManager.class.php";
require_once "app/pgen/OptionManager.class.php";
require_once "app/pgen/WorkflowManager.class.php";
require_once "lang/en_US.inc";

define('PROPOSAL_COMMENT_TYPE_TERMS', 1);
define('PROPOSAL_COMMENT_TYPE_NOTES', 2);

class QuoteController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	 /* Initialize action controller here */
    	$front = Zend_Controller_Front::getInstance();
    	
    	$front->setParam('noErrorHandler', true);
    	$front->throwExceptions(true);
    	
      	$this->host = $_SERVER['HTTP_HOST'];
    	$this->path = $_SERVER['DOCUMENT_ROOT'];
    	
    	//printf("Path %s", $this->path);
      	$this->view   = new Zend_View_Smarty($this->path . '/../application/views/scripts');
        $viewRenderer = $this->_helper->getHelper('viewRenderer');
        $viewRenderer->setView($this->view)
                     //->setViewBasePathSpec($this->view->template_dir)
                     ->setViewScriptPathSpec(':controller/:action.:suffix')
                     ->setViewScriptPathNoControllerSpec(':action.:suffix')
                     ->setViewSuffix('tpl');
                     
        $config = Hb_Util_Config_SystemConfigReader::Read('smarty'); 
		$smarty = new Smarty();
		$smarty->template_dir = $this->path . '/../application/views/scripts/';
        $smarty->compile_dir  = '/var/tmp';
        $smarty->plugins_dir[] = $this->path . '/../library/smarty_plugins/';
        $smarty->force_compile = true;
        $smarty->compile_check = true;
        
        $config = new Zend_Config(
	        array(
	            'database' => array(
	                'adapter' => 'Mysqli',
	                'params'  => array(
	                    'host'     => '127.0.0.1',
	                    'dbname'   => $_SESSION['system'],
	                    'username' => 'root',
	                    'password' => '',
	                )
	            )
	        )
	    );
     
        $db = Zend_Db::factory($config->database);
        
        $registry = Zend_Registry::getInstance();
        $objects = array('smarty' => $smarty, 'db' => $db);
        
        if ($registry === NULL) {        
        	$registry = new Zend_Registry($objects);
        	Zend_Registry::setInstance($registry);
        } else {
        	$registry->set('db', $db);
        }
    	
        $this->view->bgcolor = 'FF99FF';
        
        //Zend_Registry::setInstance($registry);
    }
    
    /**
     * 
     * @return boolean
     */
    public function searchAction()
    {
        if ($this->getRequest()->getParam('dosearch', false) == false) {
        	return true;
        }
         
        $db = Zend_Registry::get('db');
         
        /* @var $db Zend_Db_Adapter_Mysqli */
        $params = $this->getRequest()->getParams();
        $sql = "SELECT proposal_id FROM proposal WHERE proposal_name LIKE ?";
        $results = $db->fetchAssoc($sql, array($this->getRequest()->getParam('pn', '%test%')));
         
        $this->_forward('list', null, null, array('results' => $results));
    }

    /**
     * 
     */
    public function indexAction()
    {
        // action body
    }
	
    /**
     * 
     */
	public function newAction()
	{
		  	
		
	}
	
	/**
	 * 
	 */
	public function skuAction()
	{
	    
	}
	
	/**
	 * 
	 */
	public function skusAction()
	{
	    $db = Zend_Registry::get('db');
		/* @var $db Zend_Db_Adapter_Mysqli */

	    $this->view->skus = $db->fetchAssoc("SELECT sku_id, sku_name, sku_description, unit_price, created_by, created_date FROM sku ");
	     
	}
	
	/**
	 * 
	 */
	public function saveskuAction()
	{
	    $db = Zend_Registry::get('db');
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    $params = $this->getRequest()->getParams();
	    
	    $sql = "INSERT INTO sku (sku_name, sku_description, unit_price, created_by, created_date, status) "
	         . "VALUES (?,?,?,?,NOW(), 'A')";
	    $db->query($sql, array($params['sku_name'], $params['sku_description'], $params['unit_price'], $_SESSION['admin_id']));
	    
	    $this->_helper->redirector->setCode(301);
	    $this->_redirect('/quote/skus/');
	}
	
	/**
	 * 
	 */
	public function skulookupAction()
	{
	    $lookup   = $this->getRequest()->getParam('name', false);
	    $callback = $this->getRequest()->getParam('callback', false);
	    if (!$lookup) return true;
	     
	    $sql = "SELECT sku_id, sku_name FROM sku WHERE sku_name LIKE ?";
	    $db = Zend_Registry::get('db');
	    /* @var $db Zend_Db_Adapter_Mysqli */
	     
	    $l = array();
	    $l['skus'] = $db->fetchAll($sql, array('%' . $lookup . '%'));
	     
	    $this->getResponse()->setHeader('Content-type', 'application/json');
	    $this->_helper->viewRenderer->setNoRender(true);
	    printf("%s(%s)", $callback, Zend_Json::encode($l));
	}
	
	/**
	 * 
	 */
	public function saveAction()
	{
		$db = Zend_Registry::get('db');
		
		/* @var $db Zend_Db_Adapter_Mysqli */
		$params = $this->getRequest()->getParams();
		$stmt = $db->query("INSERT INTO proposal (
			account_id, account_name, proposal_name, proposal_status_id,
				created_by, created_date, status) "
		     . "VALUES (?,?,?,?,?,NOW(),'A')", array(
		     		$params['account_id'], 
		     		$params['account_name'],
		     		$params['proposal_name'],
		     		PROPOSAL_STATUS_WORK_PROGRESS,
		     		$_SESSION['admin_id']));
		
		$quote_id = $db->lastInsertId();
		$sql = "INSERT INTO proposal_contact (proposal_id, contact_id, contact_name, created_by, created_date, status) "
		     . "VALUES (?,?,?,?, NOW(), 'A') ";
		$db->query($sql, array($quote_id, $params['contact_id'], $params['contact_name'], $_SESSION['admin_id']));
		
		//TODO: test to see the service template type of the Platform Account
		// then decide the next screen type
		
		$this->_helper->redirector->setCode(301);
        $this->_redirect('/quote/detail/quote/' . $quote_id);
		
	}
	
	public function editAction()
	{
	    
	}
	
	/**
	 * 
	 */
	public function detailAction() 
	{
		$db = Zend_Registry::get('db');
		
		/* @var $db Zend_Db_Adapter_Mysqli */
		$id = $this->getRequest()->getParam('quote', 0);
		
		$this->view->quote = $db->fetchRow("select * from proposal where proposal_id = ?", $id); 
		$this->view->contact = $db->fetchRow("select * from proposal_contact where proposal_id = ?", $id);
		
		$sql = "SELECT * FROM proposal_revision_item WHERE proposal_id = ? ";
		$this->view->lines = $db->fetchAll($sql, array($id));
		
		$sql = "SELECT * FROM proposal_comment WHERE proposal_id = ? AND comment_type = ? ";
		$this->view->terms = $db->fetchRow($sql, array($id, PROPOSAL_COMMENT_TYPE_TERMS));
		$this->view->notes = $db->fetchRow($sql, array($id, PROPOSAL_COMMENT_TYPE_NOTES));
	
	}
	
	public function navtreeAction()
	{
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
	}
	
	/**
	 * save quote detail lines
	 */
	public function savedetailAction()
	{
		$db = Zend_Registry::get('db');
		
		/* @var $db Zend_Db_Adapter_Mysqli */
		$params = $this->getRequest()->getParams();
		$id = $params['id'];
		
		$sql = "UPDATE proposal SET quote_date = ?, ponumber = ?, discount = ? WHERE proposal_id = ?";
		$data = array($params['quotedate'], $params['ponumber'], $params['discount'], $params['id']);		
		$db->query($sql, $data);
		
		
		$sql = "SELECT * FROM proposal_comment WHERE proposal_id = ? AND comment_type = ? ";
		$terms = $db->fetchRow($sql, array($id, PROPOSAL_COMMENT_TYPE_TERMS));
		
		if (!empty($terms)) {
		    
		    $sql = "UPDATE proposal_comment SET comment_text = ?, modified_by = ?, modified_date = NOW() WHERE proposal_id = ? AND comment_type = ? ";
		    $db->query($sql, array($params['terms'], $_SESSION['admin_id'], $id, PROPOSAL_COMMENT_TYPE_TERMS));
		    
		} else {
		
			$sql = "INSERT INTO proposal_comment (proposal_id, comment_type, comment_text, created_by, created_date, status) "
		    	 . "VALUES (?, ?, ?, ?, NOW(), 'A') ";
		
			$data = array($params['id'], PROPOSAL_COMMENT_TYPE_TERMS, $params['terms'], $_SESSION['admin_id']);
			$db->query($sql, $data);
		}
		
		$sql = "SELECT * FROM proposal_comment WHERE proposal_id = ? AND comment_type = ? ";
		$message = $db->fetchRow($sql, array($id, PROPOSAL_COMMENT_TYPE_NOTES));
		
		if (!empty($message)) {
		    $sql = "UPDATE proposal_comment SET comment_text = ?, modified_by = ?, modified_date = NOW() WHERE proposal_id = ? AND comment_type = ? ";
		    $db->query($sql, array($params['notes'], $_SESSION['admin_id'], $id, PROPOSAL_COMMENT_TYPE_NOTES));
		    
		} else {
		    
		    $sql = "INSERT INTO proposal_comment (proposal_id, comment_type, comment_text, created_by, created_date, status) "
		    . "VALUES (?, ?, ?, ?, NOW(), 'A') ";
		    
			$data = array($params['id'], PROPOSAL_COMMENT_TYPE_NOTES, $params['notes'], $_SESSION['admin_id']);
			$db->query($sql, $data);
		}
		
		$t = 0;
		
		$sql = "DELETE FROM proposal_revision_item WHERE proposal_id = ?";
		$db->query($sql, array($id));
		
		for ($i=0; $i < 10; $i++) {
			if (!isset($params['sku_' . $i]) || trim($params['sku_' . $i]) == '') continue;
			$sql = "INSERT INTO proposal_revision_item (proposal_id, sku, description, unit_cost, units, total, created_by, created_date, status) "
			     . "VALUES (?,?,?,?,?,?,?,NOW(), 'A') ";
			$data = array(
					$params['id'], 
					$params['sku_' . $i], 
					$params['desc_' . $i], 
					$params['uc_' . $i], 
					$params['u_' . $i], 
					$params['t_' . $i],
					$_SESSION['admin_id']);
			$t += $params['t_' . $i];
			$db->query($sql, $data);
		}
		
		$sql = "UPDATE proposal SET quote_value = ? WHERE proposal_id = ?";
		$db->query($sql, array($t, $params['id']));
		
		$this->_helper->redirector->setCode(301);
		$this->_redirect('/quote/quote/id/' . $params['id']);
	}
	
	/**
	 * 
	 */
	public function pdfAction()
	{
	    $db = Zend_Registry::get('db');
	    
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    $params = $this->getRequest()->getParams();
	    
	    //$this->view->user = array('account_name' => 'Research For Good Inc');
	     
	    $sql = "SELECT * FROM contact WHERE contact_type_id = ?";
	    $this->view->office = $db->fetchRow($sql, array(CONTACT_TYPE_OFFICE));
	     
	    $sql = "SELECT * FROM proposal WHERE proposal_id = ?";
	    $data = array($params['id']);
	    $this->view->quote = $db->fetchRow($sql, $data);
	     
	    $sql = "SELECT SUM(total) AS total FROM proposal_revision_item WHERE proposal_id = ? GROUP BY proposal_id ";
	    $this->view->total = $db->fetchOne($sql, $data);
	     
	    $sql = "SELECT * FROM proposal_contact WHERE proposal_id = ? ";
	    $this->view->contact = $db->fetchRow($sql, $data);
	     
	    $sql = "SELECT * FROM proposal_revision_item WHERE proposal_id = ? ";
	    $this->view->items = $db->fetchAll($sql, $data);
	     
	    $sql = "SELECT * FROM proposal_comment WHERE proposal_id = ?  AND comment_type = " . PROPOSAL_COMMENT_TYPE_TERMS;
	    $this->view->terms = $db->fetchRow($sql, $data);
	    
	    $this->view->logopath = 'http://' . $_SERVER['HTTP_HOST'] . '/images/res/' . $_SESSION['system'] . "/logo.jpg";
	    
	    $this->view->settings = $db->fetchAssoc("SELECT attr,value FROM setting");
	    
	    $file = $this->view->render('quote/pdf.tpl');
	    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/dompdf/www/test/pdfdoc.html', $file);
	     
	    $this->_helper->viewRenderer->setNoRender(true);
	    
	    $this->_helper->redirector->setCode(301);
	    $this->_redirect('/dompdf/dompdf.php?base_path=www%2Ftest%2F&options[Attachment]=0&input_file=pdfdoc.html#toolbar=0&view=FitH&statusbar=0&messages=0&navpanes=0');
	    
	     
	}
	
	/**
	 * 
	 */
	public function printAction()
	{
	    $db = Zend_Registry::get('db');
	     
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    $params = $this->getRequest()->getParams();
	     
	    //$this->view->user = array('account_name' => 'Research For Good Inc');
	    
	    $sql = "SELECT * FROM contact WHERE contact_type_id = ?";
	    $this->view->office = $db->fetchRow($sql, array(CONTACT_TYPE_OFFICE));
	    
	    $sql = "SELECT * FROM proposal WHERE proposal_id = ?";
	    $data = array($params['id']);
	    $this->view->quote = $db->fetchRow($sql, $data);
	    
	    $sql = "SELECT SUM(total) AS total FROM proposal_revision_item WHERE proposal_id = ? GROUP BY proposal_id ";
	    $this->view->total = $db->fetchOne($sql, $data);
	    
	    $sql = "SELECT * FROM proposal_contact WHERE proposal_id = ? ";
	    $this->view->contact = $db->fetchRow($sql, $data);
	    
	    $sql = "SELECT * FROM proposal_revision_item WHERE proposal_id = ? ";
	    $this->view->items = $db->fetchAll($sql, $data);
	    
	    $sql = "SELECT * FROM proposal_comment WHERE proposal_id = ?  AND comment_type = " . PROPOSAL_COMMENT_TYPE_TERMS;
	    $this->view->terms = $db->fetchRow($sql, $data);
	    
	    $this->view->settings = $db->fetchAssoc("SELECT attr,value FROM setting");
	}
	
	/**
	 * 
	 */
	public function quoteAction()
	{
	    $db = Zend_Registry::get('db');
	    
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    $params = $this->getRequest()->getParams();
	    
		//$this->view->user = array('account_name' => 'Research For Good Inc');
		
		$sql = "SELECT * FROM contact WHERE contact_type_id = ?";
		$this->view->office = $db->fetchRow($sql, array(CONTACT_TYPE_OFFICE));
		
		$sql = "SELECT * FROM proposal WHERE proposal_id = ?";
		$data = array($params['id']);
		$this->view->quote = $db->fetchRow($sql, $data);
		
		$sql = "SELECT SUM(total) AS total FROM proposal_revision_item WHERE proposal_id = ? GROUP BY proposal_id ";
		$this->view->total = $db->fetchOne($sql, $data);
		
		$sql = "SELECT * FROM proposal_contact WHERE proposal_id = ? ";
		$this->view->contact = $db->fetchRow($sql, $data);
		
		$sql = "SELECT * FROM proposal_revision_item WHERE proposal_id = ? ";
		$this->view->items = $db->fetchAll($sql, $data);
		
		$sql = "SELECT * FROM proposal_comment WHERE proposal_id = ?  AND comment_type = " . PROPOSAL_COMMENT_TYPE_TERMS;
		$this->view->terms = $db->fetchRow($sql, $data);
	    
		$this->view->settings = $db->fetchAssoc("SELECT attr,value FROM setting");
	}
	
	/**
	 * 
	 */
	public function logoAction()
	{
	    $db = Zend_Registry::get('db');
	    
	    /* @var $db Zend_Db_Adapter_Mysqli */
	    
	    $sql = "SELECT filename, filedata, filetype FROM file WHERE file_type_id = ?";
	    $row = $db->fetchRow($sql, array(FILE_TYPE_LOGO));
	    
	    $this->getResponse()->setHeader('Content-Type', $row['filetype']);
	    print $row['filedata'];
	}
	
	/**
	 * 
	 * New Quote Revision
	 */
	public function newqrAction()
	{
		$p = new proposalDB();
   		$s = new studyDB();
   		$id = $this->getRequest()->getParam('quote', 0);
   		$p->SetProposalId($id);
   
   		$proposal = $p->GetBasicDetail();
   
   		if ($this->getRequest()->getParam('rev', false) != false) {
      		$proposal['proposal_revision_id'] = $o['proposal_revision_id'];
   		}

   //we should pass revision id to this page when ever we are calling this page 
   //to load data which doesnt belong to the most recent revision.
   //get basic detail only load data from the latest revision
   //print_r($r);

   $list['p_status']   = CreateSmartyArray($p->GetProposalStatusList(), 'proposal_status_id', 'proposal_status_description');
   $list['proposal_type'] = CreateSmartyArray($p->GetProposalTypeList(), 'proposal_type_id', 'proposal_type_description');

   $list['data_collection_method'] = CreateSmartyArray($s->GetStudyInterviewTypes(), 'study_interview_type_id', 'study_interview_type_description');
   $list['fieldwork_duration'] = CreateSmartyArray($s->GetStudyFieldWorkDurations(), 'study_fieldwork_duration_id', 'study_fieldwork_duration_description');
   $list['proposal_option_type'] = CreateSmartyArray($p->GetProposalOptionTypes(), 'proposal_option_type_id', 'proposal_option_type_description');
   $list['sample_type'] = CreateSmartyArray($p->GetSampleTypes(), 'sample_type_id', 'sample_type_description');
   $list['pricing_type'] = CreateSmartyArray($p->GetPricingTypes(), 'pricing_type_id', 'pricing_type_description');

   $p->UpdateAttr('NEXT_ACTION', "display_add_revision");
   
   if ($proposal['proposal_revision_id'] != 0) {
      
      $p->SetRevisionId($proposal['proposal_revision_id']);
      $revision = $p->GetRevisionDetail();
      
      $list['file_qc'] = PrepareSmartyArray($p->GetRevisionFilesByType(PPM_FILE_QUALIFYING_CRITERIA));

      $rs = $p->GetRevisionSampleTypeIds($proposal['proposal_revision_id']);
      
      while ($r = mysql_fetch_assoc($rs)) {
         $list['sample_type_selected'][] = $r['sample_type_id'];
      }
 
      $service_list = PrepareSmartyArray($p->GetServiceListByRevision());
      
      $r = mysql_fetch_assoc($p->GetRevisionComment(PPM_COMMENT_QUALIFYING_CRITERIA));
      
      $list['qualifying_criteria'] = $r['comment'];
      
      $r = mysql_fetch_assoc($p->GetRevisionComment(PPM_COMMENT_FINAL_DELIVERABLES));
      
      $list['final_deliverable'] = $r['comment'];
      
      $r = mysql_fetch_assoc($p->GetRevisionComment(PPM_COMMENT_GENERAL));
      
      $list['general_comment'] = $r['comment'];
   
      $revision["panel_detail"] = $p->GetRevisionAttr("PPM_DETAILED_PANEL_CALCULATION");
   } else {

      $service_list = PrepareSmartyArray($p->GetServiceList());

      $proposal['study_setup_duration_id']           = 7;
      $proposal['study_fieldwork_duration_id']       = 2;
      $proposal['study_data_processing_duration_id'] = 6;
   
   }

   foreach ($service_list as $key => $value) {
   	$list['service_list'][$service_list[$key]['pricing_item_group_id']][] = $service_list[$key];
   }


   foreach ($list['service_list'] as $key => $value) {
      $counter = 1;
      $i = 1;

   	foreach ($list['service_list'][$key] as $x_key => $x_value) {
   	   $orglist[$key]['group_description'] = $list['service_list'][$key][$x_key]['pricing_item_group_description'];
   	   $orglist[$key][$counter][$i] = $list['service_list'][$key][$x_key];
   	   $i++;

   	   if ($i == 4) {
   	     $counter++;
   	     $i = 1;
   	   }

   	}
   }

	$lang = array();
   	$this->view->lang = $lang;
   	$this->view->proposal = $proposal;   	
   	if (isset($revision)) $this->view->revision  = $revision;
   	$this->view->list      = $list;
   	$this->view->orglist   = $orglist;
   
   }

   public function saveqrAction()
   {
   		$rm = new pgen_RevisionManager();
   		$rev_id = $rm->SaveRevision($this->getRequest()->getParams());
   		//options/rev/id;
   		$this->_helper->redirector->setCode(301);
        $this->_redirect('http://dev.ersp.corp-gems.com/quote/option/rev/' . $rev_id);
		
   }
   
   public function optionAction()
   {
   		$p = new proposalDB();
        $c = new commonDB();
        $s = new studyDB();
        
        $rev = $this->getRequest()->getParam('rev', 0);
        //get the basic proposal details
        $p->SetRevisionId($rev);
        $p->SetProposalId($p->GetProposalId());
        
        //timezone stuff
        $tz = GetTimeZone();
        $p->tz = $tz;
        
        $proposal = $p->GetBasicDetail();
        $revision = $p->GetRevisionDetail();
        
        $p->UpdateAttr('NEXT_ACTION', "display_options");
        
        //get country list
        $list['country'] = CreateSmartyArray($c->GetCountryList(), 'country_code', 'country_description');
        $list['study_programming_type'] = CreateSmartyArray($s->GetProgrammingTypes(), 'study_programming_type_id', 'study_programming_type_description');
        $list['yes_no'] = array("No" , "Yes");
        $list['sample_source'] = $s->getDataSources(); //this returns an array instead of a RS //we need to fix these things to be consistent
        $list['language'] = CreateSmartyArray($c->GetLanguageList(), 'language_code', 'language_description');
        $list['respondent_portal_type'] = CreateSmartyArray($p->GetRespondentPortalTypes(), 'respondent_portal_type_id', 'respondent_portal_type_description');
        
        $revision["panel_details"] = $p->GetRevisionAttr("PPM_DETAILED_PANEL_CALCULATION");
        
        //we need to see if the options has been saved
        $rs_options = $p->GetRevisionOptions();
        
        $o['update_options'] = 0;
        
        //we already have options saved
        if ($p->rows > 0)
            $o['update_options'] = 1;
            

        //for single country multi options
        if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] > 1) {
            while ($r_option = mysql_fetch_assoc($rs_options)) {
                $options[$r_option['option_number']] = $r_option;
            }
            
            $template_name = 'app/pgen/vw_options_single.tpl';
        } else {
            //this is for multi country multi options, single country single option
            while ($r_option = mysql_fetch_assoc($rs_options)) {
                $options[$r_option['option_number']][$r_option['sort_order']] = $r_option;
            }
            
            $template_name = 'app/pgen/vw_options.tpl';
        }
        
        $rs_services = $p->GetRevisionServiceList();
        while ($r_sl = mysql_fetch_assoc($rs_services)) {
            $services[$r_sl['service_id']] = 1;
        }
        
        $this->view->proposal = $proposal;
        $this->view->revision = $revision;
        $this->view->list = $list;
        if (isset($options)) $this->view->options = $options;
        $this->view->meta = $o;
        $this->view->services = $services;
        //$smarty->display($template_name);
        
   }
   
   public function saveoptionAction()
   {
   		$op = new pgen_OptionManager();
   		$rev = $op->SaveOptions($this->getRequest()->getParams());
   		$this->_helper->viewRenderer->setNoRender(true);
   		$this->_helper->redirector->setCode(301);
        $this->_redirect('http://dev.ersp.corp-gems.com/quote/price/rev/' . $rev);
   		//$this->getResponse()->setHeader('Location', '/quote/price/rev/' . $rev);
   }
   
   public function doneAction()
   {
   		$p = new proposalDB();
   		$o = array();
   		$o['proposal_revision_id'] = $this->getRequest()->getParam('rev', 0);
   		$p->SetRevisionId($o['proposal_revision_id']);
   		$o['proposal_id'] = $p->GetProposalId();
   
	   	$p->SetRevisionId($o['proposal_revision_id']);
	   	$p->SetProposalId($o['proposal_id']);
   
   
   		$p->UpdateAttr('NEXT_ACTION', '');
   		
   		$rev = $this->getRequest()->getParam('rev');
   		$this->_helper->viewRenderer->setNoRender(true);
   		$this->_helper->redirector->setCode(301);
        $this->_redirect('http://dev.ersp.corp-gems.com/quote/view/rev/' . $rev);
   		
   }
   
   public function viewAction()
   {
   		$o = array();
   		global $lbl;
   		$o['lbl'] = $lbl;
   		$o['proposal_id'] = $this->getRequest()->getParam('id', 0);
   		$p = new proposalDB();
   		$p->SetProposalId($o['proposal_id']);
   
   		//we should redirect here if the revision count is only 1
   
	   if ($_SESSION['user_type_id'] != USER_TYPE_EXTERNAL) {
		   if ($p->GetRevisionCount() <= 1) {
		   
		      $next_action = $p->GetAttr('NEXT_ACTION');
		      
		      if ($next_action != '') {
		         
		         $url = "action=". $next_action ."&proposal_id=". $o['proposal_id'];
		         
		         $revision_id = $p->GetAttr('WORKING_REVISION');
		         
		         if ($revision_id != '') {
		            $url .= "&proposal_revision_id=". $revision_id;
		         }
		         header("Location: ?e=". $e->Encrypt($url));
		         return true;
		      }
		   }
	   }
   
	   $template = 'app/pgen/vw_detail.tpl';
	   
	   if ($_SESSION['user_type_id'] == USER_TYPE_EXTERNAL) {
	   	$template = 'app/pgen/ext/vw_detail.tpl';
	   }
   
	   //timezone stuff
	   $tz = GetTimeZone();
	   $p->tz = $tz;
	
	   $proposal = $p->GetBasicDetail();
	   
	   $revisions = PrepareSmartyArray($p->GetRevisions());
	   $proposal['comments'] = PrepareSmartyArray($p->GetComments());
	   //print_r($proposal);
//	   if (isset($_SESSION['ppm_message']) && $_SESSION['ppm_message'] != '') {
//	   	$meta['message'] = $_SESSION['ppm_message'];
//	   	unset($_SESSION['ppm_message']);
//	   }
		$meta = array();
	
	   $this->view->lang = $o['lbl']; //);
	   $this->view->proposal = $proposal;
	   $this->view->revisions = $revisions;
	   $this->view->meta  = $meta;
   }
   
   public function viewrevAction()
   {
   		$p = new proposalDB();
   		$c = new commonDB();	
   		
   		$o = array();
   		$o['proposal_revision_id'] = $this->getRequest()->getParam('rev', 0);
   		$p->SetRevisionId($o['proposal_revision_id']);
   		$o['proposal_id'] = $p->GetProposalId();
   
	   	$p->SetRevisionId($o['proposal_revision_id']);
	   	$p->SetProposalId($o['proposal_id']);
	   
   $next_action = $p->GetAttr('NEXT_ACTION');
   $revision_id = $p->GetAttr('WORKING_REVISION');
      
//   if ($next_action != '' && $revision_id == $o['proposal_revision_id']) {         
//      $url = "action=". $next_action ."&proposal_id=". $o['proposal_id'] ."&proposal_revision_id=". $revision_id;
//      header("Location: ?e=". $e->Encrypt($url));
//      return true;
//   }
   
   //timezone stuff
   $tz = GetTimeZone();
   $p->tz = $tz;

   $proposal = $p->GetBasicDetail();
   $revision = $p->GetRevisionDetail();
   
   $meta['display_currency_converter'] = 0;
   $meta['preffered_currency']         = '';
   $meta['currency_unicode']           = 36;
   
   $convert_proposal_now = 0;
   $use_exchange_rate = 0;
   $preffered_currency = $p->GetAttr('GLOBAL_PREFFERED_CURRENCY');
   
   if ($preffered_currency && $preffered_currency != 'USD')
   {
   	$use_exchange_rate = 1;	
   	$meta['display_currency_converter'] = 1;
   	$meta['preffered_currency']         = $preffered_currency;
   	$exchange_rate      = $p->GetAttr('PPM_EXCHANGE_RATE_USED');
   	
   	//GET EXCHANGE RATE /* CHECK IF EXCHANGE RATE MATCHES */
   	if (!$exchange_rate) 
   	{
   	    switch ($proposal['version']) {
   	    	case 1:
   	    	    $exchange_rate = $c->GetExchangeRate($preffered_currency);
   	    	break;
   	    	
   	    	default:
   	    		$exchange_rate = $c->GetCurrencyMultiplier($preffered_currency);
   	    	break;
   	    }
   	    
   		
   		$p->SetAttr('PPM_EXCHANGE_RATE_USED', $exchange_rate);
   	}
   	
   	if ($o['convert_currency'] == 1)
   	{
   		$convert_proposal_now = 1;		
   		$meta['currency_unicode']           = $c->GetUnicodeCurrencySymbol($preffered_currency);
   	}
   }
   
   
   $sample_types = PrepareSmartyArray($p->GetRevisionSampleTypes());
   $qf_ctr = PrepareSmartyArray($p->GetRevisionComment(PPM_COMMENT_QUALIFYING_CRITERIA));
   $qf_file = PrepareSmartyArray($p->GetRevisionFilesByType(PPM_FILE_QUALIFYING_CRITERIA));
   $approval = PrepareSmartyArray($p->GetRevisionActionList());

   $service_list = PrepareSmartyArray($p->GetDetailedRevisionServiceList());

   print_r($service_list);
   //SERVICE LIST
   if (count($service_list) > 1) {
	   foreach ($service_list as $key => $value) {
	   	$list['service_list'][$service_list[$key]['pricing_item_group_id']][] = $service_list[$key];
	   }
   

	   foreach ($list['service_list'] as $key => $value) {
	      $counter = 1;
	      $i = 1;
	
	   	foreach ($list['service_list'][$key] as $x_key => $x_value) {
	   	   $orglist[$key]['group_description'] = $list['service_list'][$key][$x_key]['pricing_item_group_description'];
	   	   $orglist[$key][$counter][$i] = $list['service_list'][$key][$x_key];
	   	   $i++;
	
	   	   if ($i == 4) {
	   	     $counter++;
	   	     $i = 1;
	   	   }
	
	   	}
	   }
   }
   ////SERVICE LIST END
	   
   //options ////////////////////////////////////////////////////
    //we need to see if the options has been saved
   $rs_options = $p->GetRevisionOptions();
	$option_summary = array();
   //for single country multi options
   if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] > 1) {
      while ($r_option = mysql_fetch_assoc($rs_options)) {
      	$options[$r_option['option_number']] = $r_option;
      }

   } else {
      //this is for multi country multi options, single country single option
      while ($r_option = mysql_fetch_assoc($rs_options)) {
      	 @$option_summary[$r_option['option_number']]['completes'] += $r_option['completes'];
         $options[$r_option['option_number']][$r_option['sort_order']] = $r_option;
      }
   }

   $rs_services = $p->GetRevisionServiceList();
   while ($r_sl = mysql_fetch_assoc($rs_services)) {
      $services[$r_sl['service_id']] = 1;
   }
   //options END/////////////////////////////////////////////////

   $revision["panel_details"] = 1;
   
   if ($p->GetRevisionAttr("PPM_DETAILED_PANEL_CALCULATION") != 0) {
       $revision["panel_details"] = 1; 
   }
   
   // PANEL OPTIONS BEGIN
   if ($revision["panel_details"]) {
      $sample_type_rst = $p->GetRevisionSampleTypes();
      
      $sample_type_ids[0] = "All";
      
      while ($sample_type = mysql_fetch_assoc($sample_type_rst)) {
         $sample_type_ids[$sample_type["sample_type_id"]] = $sample_type["sample_type_description"];
      }
        

     $panel_options = array();
     for ($k = 1; $k <= $proposal['number_of_options']; $k ++) {
            
         $data_result = $p->GetProposalRevisionPanelData($o['proposal_revision_id'], $k);
            
         $c = 0;
         $old_country = -1;
         while ($row = mysql_fetch_assoc($data_result)) {
             if (! in_array($row["sample_type_id"], array_keys($sample_type_ids))) {
                continue;
             }
             if ($row["country_id"] != $old_country) {
                $old_country = $row["country_id"];
                $c ++;
                if ($c > $proposal['number_of_countries'])
                   break;
             }
               
             if (! $row['sample_type_id']) {
                $row['sample_type_description'] = 'All';
             }
                
             $row['extras'] = array();
                
             if (! $row['prime']) {
                $extras_result = $p->GetProposalCountryPanelExtraData($row['proposal_revision_panel_id']);
                    
                while ($row_extras = mysql_fetch_array($extras_result)) {
                   $row['extras'][$row_extras['list_id']] = $row_extras['item_description'] . " (" . $row_extras["premium"] . ")";
                }
                   
                $sample_des_result = $p->GetProposalCountryPanelAttr($row['proposal_revision_panel_id'], 'SAMPLE_TYPE_DESCRIPTION');
                $row['sample_type_description'] = mysql_result($sample_des_result, 0, 'proposal_revision_panel_attr_value');
             }
                
             $panel_options[$k][$c][$row["sample_type_id"]][] = $row;
         }
            
         for ($c = 1; $c <= $proposal['number_of_countries']; $c ++) {
             foreach ($sample_type_ids as $sample_type_id => $sample_type_description) {
                 if (! isset($panel_options[$k][$c][$sample_type_id])) {
                     $panel_options[$k][$c][$sample_type_id][] = array("proposal_revision_panel_id" => 0 , "completes" => 0 , "incidence" => 0 , "question_length" => 0 , "prime" => 1 , "sample_type_id" => $sample_type_id , "sample_type_description" => $sample_type_description , "country_id" => 0 , "country_code" => "" , "country_descriptoin" => "" , "extras" => array());
                 }
             }
         }
      }
   }
   // PANEL OPTIONS END
   
   //DISCOUNTS//
   $list['budget_setup'] = PrepareSmartyArray($p->GetRevisionPricingByGroup(PRICING_GROUP_SETUP));
   $list['budget_hosting'] = PrepareSmartyArray($p->GetRevisionPricingByGroup(PRICING_GROUP_HOSTING));
   $list['budget_dp'] = PrepareSmartyArray($p->GetRevisionPricingByGroup(PRICING_GROUP_DP));
   $list['volume_discount'] = PrepareSmartyArray($p->GetPricingVolumeDiscountByLicenseLevel($proposal['license_level_id']));
   $list['budget_panel'] = PrepareSmartyArray($p->GetRevisionPanelCost());
   $list['group_discount']['setup'] = PrepareSmartyArray($p->GetRevisionGroupDiscountByGroup(PRICING_GROUP_SETUP));
   $list['group_discount']['panel'] = PrepareSmartyArray($p->GetRevisionGroupDiscountByGroup(PRICING_GROUP_PANEL));
   $list['group_discount']['hosting'] = PrepareSmartyArray($p->GetRevisionGroupDiscountByGroup(PRICING_GROUP_HOSTING));
   $list['group_discount']['dp'] = PrepareSmartyArray($p->GetRevisionGroupDiscountByGroup(PRICING_GROUP_DP));
   //DISCOUNTS END///


   //PROPOSAL//
   $rs_proposal = $p->GetRevisionBudgetLineItems();

   $row = 1;
   $last_option_number = 0;

   if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] > 1) {

      while ($r = mysql_fetch_assoc($rs_proposal)) 
      {
      	/* if we are dealing with exchange rate then calculate $r['amount'] */
      	
      	if ($convert_proposal_now == 1 && $r['value_type'] != 'N')
      	{
      		$r['amount'] = $r['amount'] * $exchange_rate;
      		//$r['unicode'] = $currency_unicode_decimal;
      	}
      	
         $p_options[$r['sort_order']][$r['option_number']] = $r;
         $p_country[$r['option_number']] = $r['country_description'];
         $p_subgroup[$r['option_number']] = $options[$r['option_number']]['sub_group_description'];
         
         if ($r['value_type'] == 'T') {
            $summary[$r['option_number']]['total'] += $r['amount'];
         }
         
         $summary[$r['option_number']]['cpc'] = $summary[$r['option_number']]['total'] / $options[$r['option_number']]['completes'];
      }

   } else {

      while ($r = mysql_fetch_assoc($rs_proposal)) {

      	if ($convert_proposal_now == 1 && $r['value_type'] != 'N')
      	{
      		$r['amount'] = $r['amount'] * $exchange_rate;
      		//$r['unicode'] = $currency_unicode_decimal;
      	}
      	
         //the tricky part here is if we are under the assumption that country will align up together
         $p_options[$r['option_number']][$r['sort_order']][] = $r;
         @$p_total[$r['option_number']][$r['sort_order']] += $r['amount'];
         $p_country[$r['option_number']][$r['c_sort_order']] = $r['country_description'];
         $p_subgroup[$r['option_number']][$r['c_sort_order']] = $options[$r['option_number']][$r['c_sort_order']]['sub_group_description'];
         
         if ($r['value_type'] == 'T') {
            @$summary[$r['option_number']][$r['c_sort_order']]['total'] += $r['amount'];
            @$option_summary[$r['option_number']]['total'] += $r['amount'];
         }
         $option_summary[$r['option_number']]['cpc'] = 0;
         if($option_summary[$r['option_number']]['completes'] != 0) {
         	@$option_summary[$r['option_number']]['cpc'] = $option_summary[$r['option_number']]['total'] / $option_summary[$r['option_number']]['completes'];
         }
         $summary[$r['option_number']][$r['c_sort_order']]['cpc'] = 0;
         
         if ($options[$r['option_number']][$r['c_sort_order']]['completes'])
         	@$summary[$r['option_number']][$r['c_sort_order']]['cpc'] = $summary[$r['option_number']][$r['c_sort_order']]['total'] / $options[$r['option_number']][$r['c_sort_order']]['completes'];
         
      }
   }

   //PROPOSAL END//

   //CUSTOM PRICING//
   if ($revision['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
      $rs = $p->GetRevisionCustomPricing();
      
      if ($revision['number_of_countries'] == 1 && $revision['number_of_options'] > 1) {
         
         while ($r = mysql_fetch_assoc($rs)) {
         	
         	if ($convert_proposal_now == 1 && $r['value_type'] != 'N')
         	{
      			$r['amount'] = $r['amount'] * $exchange_rate;
         	}
         	
      	   $custom[$r['option_number']][$r['pricing_item_group_id']]['amount'] = $r['amount'];
      	   $custom[$r['option_number']][$r['pricing_item_group_id']]['prcp_id'] = $r['proposal_revision_custom_pricing_id'];
   
      	   $t_custom[$r['option_number']] += $r['amount'];
   
      	   //this is a way around of having to put another nested loop, either way its lost of CPU cycles 
      	   //but this method cost 2 less loops
            $cpc_custom[$r['option_number']] = $t_custom[$r['option_number']] / $options[$r['option_number']]['completes'];
         }         
         
      } else {
         while ($r = mysql_fetch_assoc($rs)) {
         	
         	if ($convert_proposal_now == 1 && $r['value_type'] != 'N')
      			$r['amount'] = $r['amount'] * $exchange_rate;
         	
      	   $custom[$r['option_number']][$r['sort_order']][$r['pricing_item_group_id']]['amount'] = $r['amount'];
      	   $custom[$r['option_number']][$r['sort_order']][$r['pricing_item_group_id']]['prcp_id'] = $r['proposal_revision_custom_pricing_id'];
   
      	   $t_custom[$r['option_number']][$r['sort_order']] += $r['amount'];
   
      	   //this is a way around of having to put another nested loop, either way its lost of CPU cycles but this method cost 2 less loops
            $cpc_custom[$r['option_number']][$r['sort_order']] = $t_custom[$r['option_number']][$r['sort_order']] / $options[$r['option_number']][$r['sort_order']]['completes'];
         }         
      }

      $pricing_group = PrepareSmartyArray($p->GetPricingItemGroups());

   }
   //CUSTOM PRICING END//
   
   //meta settings
   $meta['show_approval'] = 0;
   $meta['proposal_review_group_id'] = 0;
   
   //if status is waiting for approval check for the pending approvals //
   if ($revision['proposal_revision_status_id'] == PROPOSAL_REVISION_STATUS_WAITING_APPROVAL || 
         $revision['proposal_revision_status_id'] == PROPOSAL_REVISION_STATUS_PARTIALLY_APPROVED) {
      if ($p->isUserOnRevisionApprovalList($_SESSION['admin_id'])) {

         $meta['show_approval'] = 1;
         
         mysql_data_seek($p->rs, 0);
         
         $r = mysql_fetch_assoc($p->rs);
         
         $meta['proposal_review_group_id'] = $r['proposal_review_group_id'];
      }
      
      $list['proposal_action'] = CreateSmartyArray($p->GetProposalActionList(), 'proposal_action_id', 'proposal_action_description');
   }
   
   //see if this requires approval
   if ($revision['proposal_revision_status_id'] == 0 || 
   		$revision['proposal_revision_status_id'] == PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL ||
   		$revision['proposal_revision_status_id'] == PROPOSAL_REVISION_STATUS_WAITING_APPROVAL) {
      
   	$workflow = new pgen_WorkflowManager();
   	$status = $workflow->GetReviewStatusCode($o['proposal_id'], $o['proposal_revision_id']);
   	$approval_required = 0;
      
//      if ($status['cs_watch_list'] == 1) {
//         $meta['message'] = 'This Proposal Requires Client Services Review and Approval';
//         $approval_required = 1;
//      }
      
      if ((float)$status['max_amount'] > (float) 100000.00) {
         $meta['message'] = 'This Proposal Requires Client Services Review and Approval';
         $approval_required = 1;
      }
      
//      if ($status['pricing_type_id'] == PRICING_TYPE_CUSTOM) {
//         $meta['message'] = 'This is a Non Standard Proposal, Please Send For Approval';
//         $approval_required = 1;
//      }
      
      if ($approval_required == 1) {
      	if ($revision['proposal_revision_status_id'] != PROPOSAL_REVISION_STATUS_WAITING_APPROVAL) {
      		$p->UpdateRevisionStatus(PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL);
      	} else {
      		
      	}
      } else {
      	$meta['message'] = 'Proposal is Ready to be Sent to Client';
      	$p->UpdateRevisionStatus(PROPOSAL_REVISION_STATUS_READY_TO_SEND);
      }
      
      $revision = $p->GetRevisionDetail();
      
   }
   
   $revision["panel_details"] = 0;
   
   if ($p->GetRevisionAttr("PPM_DETAILED_PANEL_CALCULATION") != 0) {
       $revision["panel_details"] = 1; 
   }
   
   //comments
   $rs = $p->GetRevisionComments();
   
   while ($r = mysql_fetch_assoc($rs)) {
   	switch ($r['proposal_revision_comment_type_id']) {
   		case PPM_COMMENT_FINAL_DELIVERABLES:
   			$comment['final_deliverables'] = $r['comment'];
   			break;
   	   case PPM_COMMENT_QUALIFYING_CRITERIA:
   			$comment['qualifying_criteria'] = $r['comment'];
   			break;
   	   case PPM_COMMENT_GENERAL:
   	      $comment['general_comment'] = $r['comment'];
   	      break;
   		default:
   			break;
   	}
   }
   
   //handle disposition buttons
   $meta['display_button_revise']      = 1;
   $meta['display_button_send_client'] = 1;
   $meta['display_button_download']            = 1;
   $meta['display_button_send_approval']      = 1;
   $meta['display_button_won']                = 1;
   $meta['display_button_manual_sent']                = 1;
   $meta['display_txt_sub_group'] = 0;
   $display['proposal_status_review'] = 0;
   
   if ($revision['proposal_option_type_id'] == PROPOSAL_OPTION_TYPE_SINGLE_SUB) {
   	$meta['display_txt_sub_group'] = 1;	
   }
   
   //when requires approval
   switch ($revision['proposal_revision_status_id']) {
   	
   	case PROPOSAL_REVISION_STATUS_REQUIRED_APPROVAL:
   		$meta['display_button_send_client'] = 0;
   		$meta['display_button_download']    = 0;
   		$meta['display_button_won']         = 0;
   		$meta['display_button_manual_sent']  = 0;
   		break;
   		
   	case PROPOSAL_REVISION_STATUS_WAITING_APPROVAL:
   	case PROPOSAL_REVISION_STATUS_PARTIALLY_APPROVED:
   		$meta['display_button_send_client'] = 0;
   		$meta['display_button_download']    = 0;
   		$meta['display_button_won']         = 0;
   		$meta['display_button_manual_sent'] = 0;
   		break;
   		
   	case PROPOSAL_REVISION_STATUS_SENT_TO_CLIENT:
   		$meta['display_button_send_approval'] = 0;
   		$meta['display_button_send_client']   = 0;
   		$meta['display_button_manual_sent']   = 0;
   		break;
   		
   	case PROPOSAL_REVISION_STATUS_CLIENT_ACCEPTED:
   		$meta['display_button_send_client'] = 0;
   		$meta['display_button_send_approval']      = 0;
   		$meta['display_button_won']                = 0;
   		$meta['display_button_manual_sent']   = 0;
   		$meta['display_button_revise']      = 0;
   		break;
   		
   	case PROPOSAL_REVISION_STATUS_REVISED:
   		$meta['display_button_send_client'] = 0;
   		$meta['display_button_send_approval']      = 0;
   		$meta['display_button_manual_sent']  = 0;
   		break;
   		
   	case PROPOSAL_REVISION_STATUS_READY_TO_SEND:
   		$meta['display_button_send_approval']      = 0;
   		$meta['display_button_won']                = 0;	
   		break;
   		
   	case PROPOSAL_REVISION_STATUS_MANUAL_SENT:
   		$meta['display_button_send_approval'] = 0;
   		$meta['display_button_send_client']   = 0;
   		$meta['display_button_manual_sent']   = 0;
   		break;
   		
   	case PROPOSAL_REVISION_STATUS_APPROVED:
   		$meta['display_button_send_approval'] = 0;	
   		break;
   		
   	case PROPOSAL_REVISION_STATUS_REJECTED:
   		$meta['display_button_send_client'] = 0;
   		$meta['display_button_send_approval']      = 0;
   		$meta['display_button_won']                = 0;	
   		$meta['display_button_manual_sent']  = 0;
   		$meta['display_button_download'] = 0;

   	default:
   		break;
   }
   
   
   //handle messages
   
   
	if ($proposal['proposal_status_id'] == PROPOSAL_STATUS_ACCOUNT_REVIEW) {
		$display['proposal_status_review'] 	= 1;
		$meta['display_button_won']			= 0;
	}
   
   if ($use_exchange_rate)
   {
   	$meta['message'] .= "<br> This Proposal Will be Sent in ". $preffered_currency .", Todays Exchange Rate is ". $exchange_rate;
   }
   
   $account = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_Account')->Find($proposal['account_id']);
   $revision_value = $p->GetRevisionMaxPrice();
   
   /* @var $account Hb_App_Account_Account */
   $account_term = $account->GetAccountTerm();
   
	if ($account->GetAccountType() == ACCOUNT_TYPE_CUSTOMER && $account_term->GetCreditLimit() > 0 && $account_term->GetCreditLimit() < $revision_value['max_amount']) {
		$meta['message'] .= "<br> You are about to send out a proposal on an account which has a lower credit limit than the proposal amount";
	}

   $min_engagement_fee = Hb_App_ObjectHelper::GetMapper("Hb_App_Proposal_ProposalSetting")->FindBySettingName("MIN_PROPOSAL_ENGAGEMENT_VALUE")->GetSettingValue();
   if ($revision["min_amount"] < $min_engagement_fee) {
      try {
         $bound_low_proposal = $account->GetAttribute("PGEN_BOUND_LOW_PROPOSAL")->GetAttributeValue();
      }catch (Hb_Data_ObjectNotInCollectionException $e) {
         $bound_low_proposal = 0;
      }
   
      if (!$bound_low_proposal) {
         $meta['message'] .= "<br/>This proposal is BELOW the minimum engagement fee of $".number_format($min_engagement_fee, 2)." required by company policy, and the account is NOT setup as contractually obligated to conduct low value projects.";
         $meta['popup_low_value_confirmation'] = 1;
      }      
   }
   
   $template = 'app/pgen/vr_revision.tpl';
   
   if ($_SESSION['user_type_id'] == USER_TYPE_EXTERNAL) {
   	$template = 'app/pgen/ext/vr_revision.tpl';	
   }
   
   /* auction code */
   
   $db = new db_ppm();
   
   $list['auctions'] = $db->GetRevisionAuctionList($o['proposal_revision_id']);
   
   $auctions = array();
   
   foreach ($list['auctions'] as $auction) {
   	$auctions[$auction['proposal_revision_option_id']][$auction['proposal_auction_id']] = $auction;
   }
   
   /* */

   $this->view->revision = $revision; //TODO name revision COMPLETED
   $this->view->proposal = $proposal;
   $this->view->sample_types = $sample_types;
   $this->view->qf_ctr = $qf_ctr;
   $this->view->qf_file = $qf_file;
   $this->view->orglist = $orglist;
   $this->view->options = $options;
   $this->view->panel_options = $panel_options;
   $this->view->p_options = $p_options;
   $this->view->services = $services;
   $this->view->list = $list;
   $this->view->total = $p_total;
   $this->view->country = $p_country;
   $this->view->custom = $custom;
   $this->view->pricing_group = $pricing_group;
   $this->view->t_custom = $t_custom;
   $this->view->cpc_custom = $cpc_custom;
   $this->view->meta = $meta;
   $this->view->approval = $approval;
   $this->view->summary = $summary;
   $this->view->comment = $comment;
   $this->view->p_subgroup = $p_subgroup;
   $this->view->option_summary = $option_summary;
   $this->view->auctions = $auctions;
   $this->view->display = $display;
   
   }
   
   public function priceAction() 
   {
   		$p = new proposalDB();
   		$o = array();
   		$o['proposal_revision_id'] = $this->getRequest()->getParam('rev', 0);
		$p->SetRevisionId($o['proposal_revision_id']);
		$o['proposal_id'] = $p->getProposalId();   
   		$p->SetProposalId($o['proposal_id']);
   		

   //timezone stuff
   $tz = GetTimeZone();
   $p->tz = $tz;
   
   $proposal = $p->GetBasicDetail();
   $revision = $p->GetRevisionDetail();

   $rs = $p->GetRevisionBudgetLineItems();

   $row = 1;
   $last_option_number = 0;

   if ($proposal['number_of_countries'] == 1 && $proposal['number_of_options'] > 1) {

      while ($r = mysql_fetch_assoc($rs)) {
         $options[$r['sort_order']][$r['option_number']] = $r;
         $country[$r['option_number']] = $r['country_description'];
         
         if ($r['value_type'] == 'T') {
            $summary[$r['option_number']]['total'] += $r['amount'];
            
            $summary[$r['option_number']]['cpc'] = 0;
            
            if ($r['completes']) {
                $summary[$r['option_number']]['cpc'] = $summary[$r['option_number']]['total'] / $r['completes'];
            }
         }
         
      }

      $template = 'app/pgen/vw_review_proposal_single.tpl';

   } else {

      while ($r = mysql_fetch_assoc($rs)) {

         //the tricky part here is if we are under the assumption that country will align up together
         $options[$r['option_number']][$r['sort_order']][] = $r;
         $total[$r['option_number']][$r['sort_order']] += $r['amount'];
         $country[$r['option_number']][$r['c_sort_order']]['country_description'] = $r['country_description'];
         $country[$r['option_number']][$r['c_sort_order']]['sub_group_description'] = $r['sub_group_description'];
         
         
         if ($r['value_type'] == 'T') {
            $summary[$r['option_number']][$r['c_sort_order']]['total'] += $r['amount'];
            
            $summary[$r['option_number']][$r['c_sort_order']]['cpc'] = 0;
            if ($r['completes']) {
                $summary[$r['option_number']][$r['c_sort_order']]['cpc'] = $summary[$r['option_number']][$r['c_sort_order']]['total'] / $r['completes'];
            }
            
         }
      }

      $template = 'app/pgen/vw_review_proposal.tpl';
   }

	$meta['display_txt_sub_group'] = 0;
	
	if ($revision['proposal_option_type_id'] == PROPOSAL_OPTION_TYPE_SINGLE_SUB)
		$meta['display_txt_sub_group'] = 1;


	if (isset($_SESSION['ppm_message']) && $_SESSION['ppm_message'] != '') {
		$meta['message'] = $_SESSION['ppm_message'];
		unset($_SESSION['ppm_message']);
	}
   //$list['proposal_budget'] = PrepareSmartyArray($p->GetProposalBudgetLineItems());

   //DisplayHeader("Proposal Manager", "pgen");

   $this->view->list = $list;
   $this->view->proposal = $proposal;
   $this->view->revision = $revision;
   $this->view->options = $options;
   $this->view->total = $total;
   $this->view->country = $country;
   $this->view->summary = $summary;
   $this->view->meta = $meta;
   		
   }

   /**
    * 
    */
   public function listAction()
   {
       $results = $this->getRequest()->getParam('results', false);
        
       $sql = "SELECT p.*,pc.* FROM proposal AS p LEFT OUTER JOIN proposal_contact AS pc ON pc.proposal_id = p.proposal_id ORDER BY p.proposal_id DESC LIMIT 10";
       
       if ($results) {
       		$ids = array_keys($results);
       		$sql = "SELECT p.*, pc.* FROM proposal AS p LEFT OUTER JOIN proposal_contact AS pc ON pc.proposal_id = p.proposal_id WHERE account_id IN (" . mysql_real_escape_string(implode(",", $ids)) .")";
       }
        
       $db = Zend_Registry::get('db');
       /* @var $db Zend_Db_Adapter_Mysqli */

       $this->view->quotes  = $db->fetchAssoc($sql);
       $this->view->qs      = $db->fetchAssoc("SELECT proposal_status_id, proposal_status_description FROM proposal_status");
       $this->view->country = $db->fetchAssoc("SELECT country_code, country_description FROM country");
       $this->view->user    = $db->fetchAssoc("SELECT login, last_name FROM user");
   }
	
	public function menuAction()
	{
		$this->getResponse()->setHeader("Content-Type", "text/xml");
	}
	
	public function toolbarAction()
	{
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
	}
	
	public function treeAction()
	{
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
	}
	
	public function testlookupAction()
	{
	    
	}

}

