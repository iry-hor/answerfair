<?php
/* @var $this SiteController */
?>

<br>
<br>
<div>

      <?php echo $this->renderPartial('_index'); ?>

      <div class='centered huge'><?php echo $this->text(__FILE__,'Welcome to ') . Yii::app()->name; ?></div>
      <div class='centered'><?php $this->echoText(__FILE__,'tagline'); ?></div>      
      <br />
      <br />    
      <br />
      <br />
</div>    

<div class='divider'> 
            
      <div class='landing_left'>
		  
		<h1><?php $this->echoText(__FILE__,'Take us for a Test Drive'); ?></h1>
		<div>Click on the car below to participate in an interactive demonstration of how our marketplace works.</div>

		<br />
            <div><a href='<?php echo CHtml::normalizeUrl(array('demo/viewQuestion')); ?>' class=''>
			
			<?php ProjectImage::image('test_drive.jpg',array('height'=>'100px')); ?>
		</a></div>		  
			<br />
			<div>You can also read more about us in our 
				<a href='<?php echo CHtml::normalizeUrl(array('site/page','view'=>'about')); ?>' class=''>About Section</a>
			</div>		  
		  			
      </div>    
          
      <div class='landing_right'>
            <h1> <?php $this->echoText(__FILE__, 'Register Now for Free'); ?></h1>
            <div><?php $this->echoText(__FILE__, 'Registration detail'); ?> </div>
            <br />
            <br />
            <?php echo $this->renderPartial('//user/_register', array('model'=>$model)); ?>           
            <div class='text-deemphasize smaller'><?php echo $this->text(__FILE__,'By Clicking "Register" you agree to the ') . Yii::app()->name; ?></div>
            <div class='text-deemphasize smaller'><a><?php $this->echoText(__FILE__,'Terms of Service'); ?></a></div>
      </div>
      
      <div style='clear:both'></div>
              
</div>
