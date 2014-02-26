<?php
/* @var $this AnswerController */
/* @var $model Answer */
/* @var $form CActiveForm */
?>

<div class='with_margin'>

<?php $form=$this->beginWidget('CActiveForm', array(
      'id'=>'markdown-answer-form',
      'enableAjaxValidation'=>false,
)); ?>



      <?php echo $form->errorSummary($model); ?>

      <div class="row">
            <?php echo $form->label($model,'in_short'); ?>
            <?php echo $form->textField($model,'in_short',array('id'=>'in_short_input','maxlength'=>100, 'class'=>'form_field')); ?>
            <?php echo $form->error($model,'in_short'); ?>
      </div>
      <br />
      <br />
     
      <div class="row">      
            <?php echo $form->label($model,'answer_text'); ?>
            <form >
              <div id="radio" class='float-right'>
                <input type="radio" id="radio1" name="radio" checked="checked" /><label for="radio1" class='smaller'>Plain Text</label>
                <input type="radio" id="radio2" name="radio" /><label for="radio2" class='smaller'>Markdown</label>
                <input type="radio" id="radio3" name="radio" /><label for="radio3" class='smaller'>Rich Text</label>
              </div>
            </form>      
      </div>
      
      <div class="row">           
                  <?php echo $form->textArea($model,'answer_text',array('class'=>'answer_markdown_area form_field','id'=>'markdown_area')); ?>              
                  <div class='answer_preview_area' id='preview' class='right_mirror'><?php echo CHtml::encode($model->answer_text); ?></div>
                  <?php echo $form->error($model,'answer_text'); ?>
      </div>
      
      <br />
      <div class="row buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>'wider button green')); ?>
      </div>
	  
	  
	  <?php echo $this->renderPartial('_markdown'); ?>
</div>

<?php $this->endWidget(); ?>


<script>
      $(document).ready(function(){
            $("#markdown_area").keydown(function(){
                  $('#preview').html(markdown.toHTML($("#markdown_area").val()));
            });
      });       
</script>


