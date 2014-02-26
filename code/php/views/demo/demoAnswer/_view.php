<?php
/* @var $this DemoController */
/* @var $data DemoAnswer */
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
                  <div class='centered text-emphasize inline-block margin-tb-3'><?php echo CHtml::encode($data->user_name); ?></div>         
                   <div class='centered text-deemphasize inline-block margin-tb-3'>Author</div>  
            </div> 

      </div>            
            
      <div class='float-right inline-block'>
            <?php if($showAwardButton) { ?> 
                  <?php echo $this->getAwardButton($data); ?>      
            <?php } ?>            
      </div>
           
      <div class='answer_center'>
            <?php 
            if($this->getAwardFromAppUser($data->answer_id)) {
				echo ProjectImage::image('award.png',array('class'=>'float-right', 'title'=>'You made your award to this answer'));
            }
            ?>            
            
            <div class='text-emphasize'><?php echo CHtml::encode($data->in_short); ?></div>
            <div class='scroll_wrapper'><p class='white-space-pre-wrap'><?php echo CHtml::encode($data->answer_text); ?></p></div>                        
      </div>    
</div>
