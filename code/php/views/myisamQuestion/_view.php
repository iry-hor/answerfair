<?php
/* @var $this MyisamQuestionController */
/* @var $data MyisamQuestion */
?>

<div class="question_item_view">
      
      <div class='question_text_box'>
            <span class='big_question_hyperlink'><?php echo CHtml::link(DataFormatter::getInstance()->formatQuestion($data->question_text), array('question/view', 'id'=>$data->question_id)); ?></span>
      </div>
      
      <div class='question_info_box'>
            <div class='centered text-emphasize inline'><?php echo CHtml::encode($data->answer_count); ?>   </div>
            <div class='centered text-deemphasize inline'>Answers</div>         
      </div>
      
      <?php if($data->answer_count > 0) { ?>
            <div class='question_info_box'>
                  <div class='centered text-emphasize  inline'><?php echo CHtml::encode($data->getCapitalization()); ?></div>
                  <div class='centered text-deemphasize  inline'>Income</div>              
            </div>
      
                        
            <div class='question_info_box'>
                  <div class='centered text-emphasize inline'><?php echo CHtml::encode($data->getLastUpdateTime()); ?></div>
                  <div class='centered text-deemphasize inline'>Last Answer</div>              
            </div> 
            
      <?php } ?>
           
      
</div>



