<?php
/* @var $this UserController */
/* @var $model User */

?>


<div class='spacer'></div>
<div class='float-right registration_box'>
      <h1>Awesome, Let's Get You Started!</h1>
      <div>Registration is as easy as email, password, password.</div>
      <?php echo $this->renderPartial('_register', array('model'=>$model)); ?>      
      <div class='text-deemphasize smaller'>By Clicking "Register" you agree to the <?php echo Yii::app()->name; ?></div>
      <div class='text-deemphasize smaller'><a>Terms of Service</a>.</div>
</div>



