<?php
/* @var $this SiteController */
/* @var $model ChangePasswordForm */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
      'id'=>'password-form',
      'action'=>array('//user/password')
)); ?>

      
      <?php echo $form->errorSummary($model); ?>

      <div class="row">
            <?php echo $form->label($model,'old_password'); ?>
            <?php echo $form->passwordField($model,'old_password',array('size'=>50,'maxlength'=>50,'class'=>'form_field')); ?>
            <?php echo $form->error($model,'old_password'); ?>
      </div>
      <div class="row">
            <?php echo $form->label($model,'new_password'); ?>
            <?php echo $form->passwordField($model,'new_password',array('size'=>50,'maxlength'=>50,'class'=>'form_field')); ?>
            <?php echo $form->error($model,'new_password'); ?>
      </div>
      <div class="row">
            <?php echo $form->label($model,'Repeat New Password'); ?>
            <?php echo $form->passwordField($model,'new_password_repeat',array('size'=>50,'maxlength'=>50,'class'=>'form_field')); ?>
            <?php echo $form->error($model,'new_password_repeat'); ?>
      </div>      
      <div class="row buttons">
            <?php echo CHtml::submitButton('Save Changes',array('class'=>'button green')); ?>
      </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
