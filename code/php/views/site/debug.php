<?php
/* @var $this SiteController */
/* @var $message String */

?>

<h2>Error <?php echo $code; ?></h2>

<div class="error white-space-pre-wrap">
      <?php echo CHtml::encode($message); ?>
      <?php echo CHtml::encode(var_dump(debug_backtrace())); ?>      
</div>