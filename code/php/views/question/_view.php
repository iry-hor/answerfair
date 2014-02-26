<?php
/* @var $this QuestionController */
/* @var $data Question */
?>

<div class='question_row'>
<div class="question_item_view">
      
      <div class='question_text_box'>
            <span class='big_question_hyperlink'><?php echo CHtml::link(DataFormatter::getInstance()->formatQuestion($data->question_text), array('question/view', 'id'=>$data->question_id)); ?></span>
      </div>
      
      <div class='question_info_box'>
            <div class='centered text-emphasize inline'><?php echo CHtml::encode($data->answer_count); ?>   </div>
            <div class='centered text-deemphasize inline'><?php $this->echoText(__FILE__,'Answers'); ?></div>         
      </div>
      
      <?php if($data->answer_count > 0) { ?>
            <div class='question_info_box'>
                  <div class='centered text-emphasize  inline'><?php echo CHtml::encode($data->getCapitalization()); ?></div>
                  <div class='centered text-deemphasize  inline'><?php $this->echoText(__FILE__,'Income'); ?></div>              
            </div>
      
                        
            <div class='question_info_box'>
                  <div class='centered text-emphasize inline'><?php echo CHtml::encode($data->getLastUpdateTime()); ?></div>
                  <div class='centered text-deemphasize inline'><?php $this->echoText(__FILE__,'Last Answer'); ?></div>              
            </div> 
            
      <?php } ?>
      <!--
      <div class='question_info_box'>
            <div class='centered text-emphasize inline'><?php echo CHtml::encode(count($data->watchers)); ?></div>
            <div class='centered text-deemphasize inline'><?php $this->echoText(__FILE__,'Watching'); ?></div>              
      </div>       
      -->
      
      
      <?php if($data->answer_count == 0) { ?>      
            <div class='question_info_box invisible' id='focus-control'>
                  <?php echo CHtml::link('Watch This Question', array('questionWatch/create'), array('class'=>'no-decoration text-deemphasize')); ?>
            </div>      
      <?php } ?>
                 
</div>
</div>

