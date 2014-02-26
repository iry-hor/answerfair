<?php
/* @var $this MyisamQuestionController */
/* @var $data array */
?>

<div class="question_item_view">
      
      <div class='question_text_box'>
            <span class='big_question_hyperlink'><a><?php echo DataFormatter::getInstance()->formatQuestion($data['title']); ?></a></span>            
      </div>
      
      <div class='question_info_box'>
            <div class='centered text-emphasize inline'><?php echo $data['answer_count']; ?></div>
            <div class='centered text-deemphasize inline'>Answers</div>         
      </div>

      <?php if($data['answer_count'] > 0) { ?>
            <div class='question_info_box'>
                  <div class='centered text-emphasize  inline'><?php echo '$0.00'; ?></div>
                  <div class='centered text-deemphasize  inline'>Income</div>              
            </div>
                              
            <div class='question_info_box'>
                  <div class='centered text-emphasize inline'><?php echo DataFormatter::getInstance()->formatTimestamp($data['last_activity_date']); ?></div>
                  <div class='centered text-deemphasize inline'>Last Answer</div>              
            </div> 
            
      <?php } ?>
                 
      
</div>


