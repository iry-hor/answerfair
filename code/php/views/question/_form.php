<?php
/* @var $this QuestionController */
/* @var $model Question */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
      'action'=>array('//question/create'),
      'method'=>'POST',
	'id'=>'question-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->label($model,'question_text'); ?>
		<?php echo $form->textField($model,'question_text',array('id'=>'question_text_field','class'=>'form_field')); ?>
		<?php echo $form->error($model,'question_text'); ?>
	</div>

      <?php if(Yii::app()->user->isGuest) { ?>
            <div class="row">
                  <?php echo $form->label($model,'Your Email'); ?>
                  <?php echo $form->textField($model,'email',array('class'=>'form_field','size'=>'60')); ?>
                  <?php echo $form->error($model,'email'); ?>
            </div>
      <?php } ?>      
      <br />
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Add Question' : 'Save', array('class'=>'button green')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->