<?php
/* @var $this MyisamQuestionController */
/* @var $dataProvider CActiveDataProvider */
/* @var $searchText String */

$this->breadcrumbs=array(
      'Questions',
);
?>

<div class='search_header'>
      
      <div class='float-left bottom'>
            <div class='larger padding-top-10'><?php $this->echoText(__FILE__,'Search Results'); ?></div>
            
            <?php if($dataProvider->totalItemCount > 0) { ?>            
                  <div class='text-deemphasize'>Search Terms: <b><?php echo $searchText; ?></b></div>
            <?php } ?>            
      </div>
      
      
      <?php if($dataProvider->totalItemCount > 0) { ?>
      <div class='float-right'>
            <div>Can't find a match?</div> 
            <?php echo CHtml::link('Add Question', array('question/create', 'search'=>$searchText), array('class'=>'button green hover-white float-right')); ?>    
      </div>
      <?php } ?>
</div>


<?php $this->widget('zii.widgets.CListView', array(
      'dataProvider'=>$dataProvider,
      'itemView'=> $external ? '_external_view' : '_view',
)); ?>


<?php if($dataProvider->totalItemCount == 0) { ?>
      <br />
      <br />
      <br /> 
      <h1><?php $this->echoText(__FILE__,'Add Your Question'); ?></h1>    
      <div><?php $this->echoText(__FILE__,'No Results'); ?></div>
      
      <br />       
      <?php echo $this->renderPartial('//question/_form', array('model'=>$model)); ?>

<?php } ?>