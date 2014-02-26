<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */
?>

<h1>Login</h1>


<div class='padding-bottom-5'>
     If you haven't registered with us previously you can do it here: 
     <a href='<?php echo Yii::app()->baseUrl.'/index.php?r=user/create';?>'>Register</a>
</div>

<p class='text-emphasize'>Please fill out the following form with your login credentials:</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email', array('size'=>50,'class'=>'form_field')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'password'); ?>
		<?php echo $form->passwordField($model,'password', array('size'=>50,'class'=>'form_field')); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>
      <br>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Login',array('class'=>'wider button green')); ?>
	</div>
      <br/>
      <br/>

      
<?php $this->endWidget(); ?>
</div><!-- form -->
