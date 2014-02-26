<?php
/* @var $this AnswerController */
/* @var $data Answer */
?>

<div class='answer_container' id='<?php echo $data->answer_id;?>'>
      <div class='boxed-group'>     

            <div class='answer_info_box' title="How much this answer has been awarded">
                  <div class='centered text-emphasize inline-block margin-tb-3'><?php echo CHtml::encode(money_format('%n', $data->award_total)); ?></div>
                  <div class='centered text-deemphasize inline-block margin-tb-3'>Income</div>              
            </div>           
            
            <div class='answer_info_box' title='Entry or last update time.  Answers can be updated until they receive awards after which they cannot. '>
                  <div class='centered text-emphasize inline-block margin-tb-3'><?php echo DataFormatter::getInstance()->formatDate($data->entry_time); ?></div>
                  <div class='centered text-deemphasize inline-block margin-tb-3'>Entered</div>              
            </div> 

            <div class='answer_info_box' title="The Author">
                  <div class='centered text-emphasize inline-block margin-tb-3'><?php echo CHtml::encode($data->user->user_name); ?></div>         
                   <div class='centered text-deemphasize inline-block margin-tb-3'>Author</div>  
            </div> 

      </div>            
            
      <div class='answer_center'>
            <div class='cipher_text'><?php echo CHtml::encode($data->in_short_cipher); ?></div>           
            <div class='scroll_wrapper'><p class='white-space-pre-wrap cipher_text'><?php echo CHtml::encode($data->answer_text_cipher); ?></p></div>                             
      </div>    
</div>