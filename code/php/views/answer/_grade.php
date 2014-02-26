<?php
/* @var $this QuestionController */
/* @var $data Award */
?>


<?php if(empty($data->rating)) { ?>

      <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'grade-form',
            'action'=>array('//award/rate'),
            'enableAjaxValidation'=>false,
      )); ?>
      
      <div class='inline-block text-deemphasize margin-tb-3 padding-right-5'>Grade</div>
      <div class='hover-button inline-block text-emphasize padding-3-5 a_color' onclick="saveGrade('A')"> A </div>
      <div class='hover-button inline-block text-emphasize padding-3-5 b_color' onclick="saveGrade('B')"> B </div>
      <div class='hover-button inline-block text-emphasize padding-3-5 c_color' onclick="saveGrade('C')"> C </div>
      <div class='hover-button inline-block text-emphasize padding-3-5 d_color' onclick="saveGrade('D')"> D </div>
      
      <?php $this->endWidget(); ?>
      
<?php } else { ?>
      <div class='inline-block text-deemphasize margin-tb-3 padding-right-5'>Grade</div>
      <div class='inline-block text-emphasize margin-tb-3 padding-right-5'><?php echo $data->rating; ?></div>               
<?php } ?>      
<script>

function saveGrade(grade) 
{
      var $award = $('<input type=hidden name=award_id />').val(<?php echo $data->award_id; ?>);           
      var $grade = $('<input type=hidden name=grade />').val(grade);      
      $('#grade-form').append($grade).append($award).submit();
}

</script>



       