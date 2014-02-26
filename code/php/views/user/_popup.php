<?php
/* @var $this SiteController */
/* @var $model User */

?>

<div class='account_popup'>
	      <div class='popup_section'>
	            <div class='text-emphasize''><?php echo $model->user_name; ?></div>
	            <div class='text-deemphasize''><?php echo $model->email; ?></div>       
	      </div> 
	       
	      <div class='popup_section'>
	      	<div>
		            <span class='padding-right-5'>Current Balance</span>
		            <span class='text-emphasize float-right'><?php echo DataFormatter::getInstance()->formatMoney($model->balance); ?></span> 
	            </div>
	            <?php if($model->credits > 0) { ?>
		            <div>
			            <span class='padding-right-5'>Current Credits</span>
			            <span class='text-emphasize float-right'><?php echo $model->credits; ?></span> 
		            </div>                      
	            <?php } ?>
	      </div>
	      <?php if($model->getBalance() < 1.00) { ?>
		      <div class='popup_section'>    
		      	<div class='text-emphasize'></div>
		      	<div class='text-deemphasize'><?php echo 'You need to add cash to your account before you can gain access to question answers'; ?> </div> 
		            <div class='centered margin-5' ><?php echo $this->renderPartial('//account/_deposit'); ?></div>
		      </div>
		      <!-- 
		      	If we follow a rule of only one high-emphasis (green) button per page, then we need to 
		      	have different class for the View account button when we're also showing Deposit controls 
	    		-->
		</div> <!--  account_popup end -->
	      <div class='popup_button_panel'>
	            <?php echo CHtml::link('Logout', array('site/logout', 'id'=>$model->user_id), array('class'=>'button grey float-left')); ?>           
	            <?php echo CHtml::link('View Account', array('user/view'), array('class'=>'button  grey float-right')); ?>
	      </div> 	      
      <?php }  else { ?>   
		</div> <!--  account_popup end -->           
	      <div class='popup_button_panel'>
	            <?php echo CHtml::link('Logout', array('site/logout', 'id'=>$model->user_id), array('class'=>'button grey float-left')); ?>           
	            <?php echo CHtml::link('View Account', array('user/view'), array('class'=>'button green float-right')); ?>
	      </div> 
      <?php } ?>       
</div> 