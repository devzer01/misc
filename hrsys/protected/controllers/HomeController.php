<?php

class HomeController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionHiring()
	{
		$model=new Account();

    // uncomment the following code to enable ajax-based validation
    /*
    if(isset($_POST['ajax']) && $_POST['ajax']==='account-hiring-form')
    {
        echo CActiveForm::validate($model);
        Yii::app()->end();
    }
    */
		$test = new Test();
		
		$test->write = 'test';

	    if(isset($_POST['Account']))
	    {
	        $model->attributes = $_POST['Account'];
	        $model->account_logo = CUploadedFile::getInstance($model,'account_logo');
	        if($model->validate())
	        {
	            // form inputs are valid, do something here
	            return;
	        }
	    }
    $this->render('hiring',array('model'=>$model));
	} 

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}