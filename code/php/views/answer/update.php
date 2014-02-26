<div class='header_text'><?php $this->echoText(__FILE__,'Your Answer to the Question'); ?></div>
<div class='low_emphasis_text'><?php echo $model->question->question_text; ?></div>      

<br/>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>