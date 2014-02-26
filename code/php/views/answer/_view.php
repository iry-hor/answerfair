<?php
/* @var $this QuestionController */
/* @var $data Answer */
?>


<div class='answer_container' id='<?php echo $data->answer_id;?>'>
      <div class='boxed-group'>     

            <div class='answer_info_box'>
                  <div class='centered text-emphasize inline-block margin-tb-3'><?php echo CHtml::encode(money_format('%n', $data->award_total)); ?></div>
                  <div class='centered text-deemphasize inline-block margin-tb-3'>Income</div>              
            </div>           
            
            <div class='answer_info_box'>
                  <div class='centered text-emphasize inline-block margin-tb-3'><?php echo DataFormatter::getInstance()->formatDate($data->entry_time); ?></div>
                  <div class='centered text-deemphasize inline-block margin-tb-3'>Entered</div>              
            </div> 

            <div class='answer_info_box'>
                  <div class='centered text-emphasize inline-block margin-tb-3'><?php echo CHtml::encode($data->user->user_name); ?></div>         
                   <div class='centered text-deemphasize inline-block margin-tb-3'>Author</div>  
            </div> 

            <div class='float-right padding-5'>
                  <?php 
                  $usersAward = $this->getUsersAward($data->answer_id, $awards);
                  if(!empty($usersAward)) {
                        echo $this->renderPartial('/answer/_grade',array('data'=>$usersAward)); 
                  }
                  ?>     
            </div>


      </div>            
            
      <div class='float-right inline-block'>
            <?php     
            if(empty($awards) && $data->notUsersAnswer()) {
                  echo $this->getAwardButton($data);      
            } 
            ?>     
      </div>
           
      
      
      <div class='answer_center'>
      	<?php $this->echoAwardIcon($data->answer_id, $awards); ?>
            <div class='text-emphasize'><?php echo CHtml::encode($data->in_short); ?></div>
            <div class='scroll_wrapper'><p class='white-space-pre-wrap'><?php echo CHtml::encode($data->answer_text); ?></p></div>          
      </div> 
</div>
