<?php
/* @var $this SiteController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
      'id'=>'email-form',
      'action'=>array('user/email')
)); ?>

      <?php echo $form->errorSummary($model); ?>

      <div class="row">
            <?php echo $form->label($model, 'New Email'); ?>
            <?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>50,'class'=>'form_field')); ?>
            <?php echo $form->error($model,'email'); ?>
      </div>

      <div class="row buttons">
            <?php echo CHtml::submitButton('Save Changes',array('class'=>'button green')); ?>
      </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
