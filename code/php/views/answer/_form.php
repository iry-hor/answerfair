<?php
/* @var $this AnswerController */
/* @var $model Answer */
/* @var $form CActiveForm */
?>

<div class='with_margin'>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'answer-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

      <div class="row">
            <?php echo $form->label($model,'in_short'); ?>
            <?php echo $form->textField($model,'in_short',array('id'=>'in_short_input','maxlength'=>100, 'class'=>'form_field')); ?>
            <?php echo $form->error($model,'in_short'); ?>
      </div>
      <br>
	<div class="row">
		<?php echo $form->label($model,'answer_text'); ?>
		<?php echo $form->textArea($model,'answer_text',array('class'=>'answer_text_area form_field')); ?>
		<?php echo $form->error($model,'answer_text'); ?>
	</div>
      <br>
	<div class="row buttons">
	      
	      
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>'wider button green')); ?>
	</div>

<?php $this->endWidget(); ?>

<!--
<script>
      
	CKEDITOR.replace( 'Answer[answer_text]', {
		toolbar: [
			    { name: 'document', items: [ 'Source' ] },
			    { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline' ] }
				],
	});	
	
</script>
-->
</div><!-- form -->

