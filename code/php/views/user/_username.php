<?php
/* @var $this SiteController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
      'id'=>'username-form',
      'action'=>array('user/username'),
)); ?>


      <?php echo $form->errorSummary($model); ?>

      <div class="row">
            <?php echo $form->label($model,'New User Name'); ?>
            <?php echo $form->textField($model,'user_name',array('size'=>50,'maxlength'=>50,'class'=>'form_field')); ?>
            <?php echo $form->error($model,'user_name'); ?>
      </div>

      <div class="row buttons">
            <?php echo CHtml::submitButton('Save Changes',array('class'=>'button green')); ?>
      </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
