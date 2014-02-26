<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->breadcrumbs=array(
	'Questions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Question', 'url'=>array('index')),
	array('label'=>'Manage Question', 'url'=>array('admin')),
);
?>

<h1><?php $this->echoText(__FILE__,'Submit Your Question'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>



