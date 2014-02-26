<?php
/* @var $this SiteController */
/* @var $model NewUser */
/* @var $form CActiveForm */
?>


<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
      'id'=>'user-registration',
      'action'=>array('user/create'),
      'enableAjaxValidation'=>true,
)); ?>

      <div class="row">
            <?php echo $form->label($model,'email'); ?>
            <?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>100,'class'=>'form_field')); ?>
            <?php echo $form->error($model,'email'); ?>
      </div>

      <div class="row">
            <?php echo $form->label($model,'password'); ?>
            <?php echo $form->passwordField($model,'password',array('size'=>50,'minlength'=>7,'maxlength'=>20,'class'=>'form_field')); ?>
            <?php echo $form->error($model,'password'); ?>

      </div>
      
      <div class="row">
            <?php echo $form->label($model,'password_repeat'); ?>
            <?php echo $form->passwordField($model,'password_repeat',array('size'=>50, 'class'=>'form_field')); ?>
            <?php echo $form->error($model,'password_repeat'); ?>   
      </div>


      <div class="row buttons">
            <?php echo CHtml::submitButton('Register',array('class'=>'wider button blue')); ?>
      </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
