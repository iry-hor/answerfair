<?php
/* @var $this QuestionController */
/* @var $model Question */
/* @var $access QuestionAccess */
?>

<div class='question_header'>
      
      <div class="question_item_view">
            <!-- This could be a call to renderPartial('_view'), right? Would this content ever be different than q
                  Questions appearing in list form?  -->
            <div class='question_text_box padding-5'>
                  <span class='header_text'><?php echo DataFormatter::getInstance()->formatQuestion($model->question_text); ?></span>
            </div>
            
            <div class='question_info_box'>
                  <div class='centered text-emphasize inline'><?php echo CHtml::encode($model->answer_count); ?>   </div>
                  <div class='centered text-deemphasize inline'><?php $this->echoText(__FILE__,'Answers'); ?></div>         
            </div>
            
            <?php if($model->answer_count > 0) { ?>
                  <div class='question_info_box'>
                        <div class='centered text-emphasize inline'><?php echo CHtml::encode($model->getCapitalization()); ?></div>
                        <div class='centered text-deemphasize inline'><?php $this->echoText(__FILE__,'Income'); ?></div>              
                  </div>
            
                              
                  <div class='question_info_box'>
                        <div class='centered text-emphasize inline'><?php echo CHtml::encode($model->getLastUpdateTime()); ?></div>
                        <div class='centered text-deemphasize inline'><?php $this->echoText(__FILE__,'Last Answer'); ?></div>              
                  </div> 
                  
            <?php } ?>
            <div class='question_info_box'>
                  <div class='centered text-emphasize inline'><?php echo CHtml::encode(count($model->watchers)); ?></div>
                  <div class='centered text-deemphasize inline'><?php $this->echoText(__FILE__,'Watching'); ?></div>              
            </div>       
            
            <div class='question_info_box'>
                  <?php echo CHtml::link($this->echoText(__FILE__,'Watch This Question'), array('questionWatch/create'), array('class'=>'no-decoration text-deemphasize')); ?>
            </div>
      
      </div>
</div>
      <table class='layout_table'><tr>
            <td>
                  <div class='answer_component'><?php $this->getAnswerComponent($model->question_id, $userAnswer, $access, $answerDataProvider->totalItemCount > 0); ?>         
                  </div>
            </td>       
            <td>     
            <div class='float-right align-right'>
                  <?php $this->getAccessComponent($model->question_id, $access, $answerDataProvider->totalItemCount > 0); ?>
            </div>
            </td>                      
      </tr></table>     
      
</div>


<div class='answer_set_header blue_purple'>
      <div class='float-left'>Answers are ordered by Award Totals</div>
</div>     

<div class='breadcrumb-bar blue_purple'></div>          
       
<?php 

      $this->widget('zii.widgets.CListView',array(
            'dataProvider'=>$answerDataProvider,
            'itemView'=> empty($access) ? '/answer/_hidden' : '/answer/_view',   
            'viewData' => array('awards'=>$awards),    
            'summaryText'=>''
      )); 
?>

<script> 
      $(document).ready(function(){
            $("#accessButton").click(function(){
                  $("#accessButton").html('Just a sec...');
                  showConfirmDialog();
            });                                         
      });
      
      
      
      function showConfirmDialog() {
            var xmlhttp;

            if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                  xmlhttp=new XMLHttpRequest();
            }
            else {// code for IE6, IE5
                  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange=function() {
                  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        $.blockUI.defaults.css = {}; 
                        $.blockUI({ 
                              message: xmlhttp.responseText,
                              css: { 
                                padding:              5, 
                                left:                 '35%',
                                top:                  '5%',
                                'margin-left':        'auto',
                                'margin-right':       'auto',                                                           
                                color:                '#000', 
                                backgroundColor:      '#fff', 
                                cursor:               'normal',
                                'border-bottom-radius':  '0.5em',                                
                              }
                        
                        });  
                        $('.blockOverlay').attr('title','Click to unblock').click($.unblockUI); 
                        $("#accessButton").html('Reveal Answers');
                  } 
            }
            
            var url = '<?php echo Yii::app()->createUrl('questionAccess/confirm'); ?>';
                       
            xmlhttp.open("POST", url, true);
            xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xmlhttp.send("<?php echo 'qid=' . $model->question_id . '&question_text='  . $model->question_text . '&YII_CSRF_TOKEN=' . Yii::app()->request->csrfToken;; ?>");        
      }      
      
      
</script>
