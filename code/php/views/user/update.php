<?php
/* @var $this UserController */
/* @var $model User */

?>

<h1>Update User <?php echo $model->user_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>