<?php 
/**
 *  @version  2.0.alpha
 *  @author nayana
 */

require_once 'Zend/Controller/Action.php';

class CommonController extends Zend_Controller_Action
{
	
	public function DisplayModuleColumnAction()
	{
		$request = Hb_Util_Request_Request::GetInstance();					
				

		$module_id = ($this->getRequest()->getParam('module'));	
		
		$module_columns 	= Hb_App_ObjectHelper::GetMapper('Hb_App_Common_FrontPageModuleColumns')->Find($module_id);
		$module 				= Hb_App_ObjectHelper::GetMapper('Hb_App_Common_FrontPageModule')->Find($module_id);
		$view = new Hb_View_Common_DisplayFrontPageColumn();
		$view->SetModuleColumns($module_columns);
		$view->SetModule($module);
		$view->SetFrontPageManager(new Hb_App_Common_FrontPageManager($module_id, $_SESSION['admin_id']));
		
		$view->Display();

	}
	
	public function SaveDashBoardAction()
	{
		$request = Hb_Util_Request_Request::GetInstance();		
		$columns	= '';
		$module_columns = Hb_App_ObjectHelper::GetMapper('Hb_App_Common_FrontPageModuleColumns')->Find($request->getParam('module_id'));
				
		foreach ($module_columns as $column){
			
			if($request->hasParam('module_column_'.$column->GetFrontPageModuleColumnID())) {
				$columns	.= $column->GetFrontPageModuleColumnID().":";				
			}			
		}
		
		$columns = substr($columns, 0, (strlen($columns)-1));
		
		//check whether user a column module saved
		$front_page_module_user = Hb_App_ObjectHelper::GetMapper('Hb_App_Common_FrontPageModuleUser')->FindByUser($_SESSION['admin_id'], $request->getParam('module_id'));
		
		//update
		if($front_page_module_user) {			
			if($columns != '') {
				$front_page_module_user->SetUserColumnPrefer($columns);
			}
		}else {		
			if($columns != '') {
				$new_fontpage_module_user = new Hb_App_Common_FrontPageModuleUser(null, $request->getParam('module_id'), $_SESSION['admin_id'], $columns);
			}
		}
		
		$this->getResponse()->setRedirect("/app/pjm/index.php?action=close_projects");   
  		return true;
		
		
	}
	
}

?>