<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'account-hiring-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

	<?php echo Yii::app()->getParams()->emailConfig['smtpServer']; ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'account_status_id'); ?>
		<?php echo $form->textField($model,'account_status_id'); ?>
		<?php echo $form->error($model,'account_status_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'account_type_id'); ?>
		<?php echo $form->textField($model,'account_type_id'); ?>
		<?php echo $form->error($model,'account_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'account_name'); ?>
		<?php echo $form->textField($model,'account_name'); ?>
		<?php echo $form->error($model,'account_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'country_code'); ?>
		<?php echo $form->textField($model,'country_code'); ?>
		<?php echo $form->error($model,'country_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_by'); ?>
		<?php echo $form->textField($model,'created_by'); ?>
		<?php echo $form->error($model,'created_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'modified_by'); ?>
		<?php echo $form->textField($model,'modified_by'); ?>
		<?php echo $form->error($model,'modified_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'total_revenue'); ?>
		<?php echo $form->textField($model,'total_revenue'); ?>
		<?php echo $form->error($model,'total_revenue'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_date'); ?>
		<?php echo $form->textField($model,'created_date'); ?>
		<?php echo $form->error($model,'created_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'modified_date'); ?>
		<?php echo $form->textField($model,'modified_date'); ?>
		<?php echo $form->error($model,'modified_date'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'modified_date'); ?>
		<?php echo $form->fileField($model,'account_logo'); ?>
		<?php echo $form->error($model,'account_logo'); ?>
	</div>


	<div class="row buttons">
	<?php echo $test['param']; ?>
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->