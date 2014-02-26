<?php
/* @var $this DemoController */
/* @var $data DemoAnswer */
?>


<div class='header_text'>Your Answer to the Question</div>
<div class='low_emphasis_text'><?php echo $model->question->question_text; ?></div>      

<br/>

<?php echo $this->renderPartial('demoAnswer/_form', array('model'=>$model)); ?>