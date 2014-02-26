<br>
<br>
<h1><?php $this->echoText(__FILE__,'Time To Cash in on All That Stuff You Know'); ?></h1>

<!-- Attributtion of image per license agreement -->
<?php echo ProjectImage::image('money-jar-icon.png',array('class'=>'float-right','title'=>'Image courtesy of http://artdesigner.lv')); ?>

<div class='white-space-pre-wrap'><?php $this->echoText(__FILE__,'About Answers'); ?></div>
      
      
<br>
<br> 
<div class='search_header larger'><?php $this->echoText(__FILE__,'Recent Questions'); ?></div>  


<?php 

      $dataProvider = new CActiveDataProvider('Question',array(
          'criteria'=>array(
              'order'=>'entry_time DESC',
              'limit'=>40,
           ),
          'pagination'=>array(
              'pageSize'=>20
           ),
      ));
      
      $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$dataProvider,
            'itemView'=>'/question/_view',
      ));
            
?>


            
<script> 
      $(document).ready(function() {
            $(".question_row").mouseenter(function(){
                  $(this).find('#focus-control').removeClass("invisible");               
            })
            
            $(".question_row").mouseleave(function(){
                  $(this).find('#focus-control').addClass("invisible");                   
            })            
      });
                  
</script>
                     