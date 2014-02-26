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
          
      
      <div class='answer_center'>
            <div class='cipher_text text_emphasize'><?php echo CHtml::encode($data->in_short); ?></div>
            <div class='scroll_wrapper'><p class='cipher_text white-space-pre-wrap'><?php echo CHtml::encode($data->answer_text); ?></p></div>                        
      </div>    
</div>




<script>

$('.answer_center').qtip({
	content: {
		text: 'You will be able to see the basic structure and length of the answer text, but we blur out the actual content until you authorize us to deduct the $1 fee from your account balance' 
	},
	position: {
        	target: 'mouse',
		adjust: { y: 10, x: 10 }
    	},
	style: { 
		classes: 'custom-tooltip'
	}	
});

</script>
