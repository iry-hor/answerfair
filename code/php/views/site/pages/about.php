<?php
/* @var $this SiteController */
?>

<div class='documentation'>
      
      <div class='documentation_header'>
            <span class='text-emphasize'>THE BASICS</span>
            <span><?php echo CHtml::link($this->text(__FILE__,'LEGAL'), array('site/page','view'=>'legal'), array('class'=>'strip-decoration text-deemphasize')); ?>  	        
            </span>
      </div>
	  
	  
	  	  
	  <h2><?php echo $this->text(__FILE__,'THE GIST'); ?></h2>
	  <p class='white-space-pre-wrap'><?php echo $this->text(__FILE__,'gist content'); ?></p>

	  <h2><?php echo $this->text(__FILE__,'THE WHY'); ?></h2>
	  <p class='white-space-pre-wrap'><?php echo $this->text(__FILE__,'why content'); ?></p>

	  <h2><?php echo $this->text(__FILE__,'PRICING'); ?></h2>
	  <p class='white-space-pre-wrap'><?php echo $this->text(__FILE__,'pricing content'); ?></p>
	  
	  <h2><?php echo $this->text(__FILE__,'FUNDING YOUR ACCOUNT'); ?></h2>
	  <p class='white-space-pre-wrap'><?php echo $this->text(__FILE__,'funding content'); ?></p>
	       
</div>
