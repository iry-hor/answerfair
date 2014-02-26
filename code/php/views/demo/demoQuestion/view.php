<?php
/* @var $this QuestionController */
/* @var $model Question */
/* @var $access QuestionAccess */
?>

<div class='question_header'>
      
      <div class="question_item_view">
            
            <div class='question_text_box padding-5'>  
                  <span class='header_text' title='An example of the type of inquiry you could find on our site'>
                        <?php echo DataFormatter::getInstance()->formatQuestion($model->question_text); ?>
                  </span>
            </div>
                      
            <div class='question_info_box' title='The total number of answers'>
             
                  <div class='centered text-emphasize inline'><?php echo CHtml::encode($model->answer_count); ?>   </div>
                  <div class='centered text-deemphasize inline'>Answers</div>         
            </div>
            

            <div class='question_info_box' title="How much has been paid to access this question's answers">
                  <div class='centered text-emphasize inline'><?php echo CHtml::encode($model->getCapitalization()); ?></div>
                  <div class='centered text-deemphasize inline'>Income</div>              
            </div>
      
                        
            <div class='question_info_box' title='The last time any answer was entered'>
                  <div class='centered text-emphasize inline'><?php echo CHtml::encode($model->getLastUpdateTime()); ?></div>
                  <div class='centered text-deemphasize inline'>Last Answer</div>              
            </div> 
                  
      
            <div class='question_info_box' title='The number of people who are receiving updates when new answers are added to this question'>
                  
                  <div class='centered text-emphasize inline'>12</div>
                  <div class='centered text-deemphasize inline'>Watching</div>              
            
            </div>       
            <div class='question_info_box' title='After clicking on this link you would also start getting notifications when new answers are added'>
                  <?php echo CHtml::label('Watch This Question', '',array('class'=>'pointer text-deemphasize','id'=>'watch-link')); ?>
            </div>           
      
      </div>
</div>
      <table class='layout_table'>  
      <tr>
            <td>
                  <div class='answer_component'>
                        <?php 
                        if(empty($userAnswer)) {
                              echo "<div>";
                              echo CHtml::link('Answer Yourself', array('demo/createAnswer'), array('class'=>'button grey','title'=>"Submit your own answer to the question and start getting paid yourself"));                             
                              echo '</div>';
                        } else {
                              echo CHtml::tag('div', array('class'=>'text-deemphasize inline margin-right-10'),'You Answered on ' . $userAnswer->getEntryTime());
                              echo CHtml::link('Edit Answer', array('demo/updateAnswer'), array('class'=>'no-decoration')); 
                        }
                        ?>    
                  </div>
            </td>       
            <td>     
            <div class='float-right align-right'>
                  <?php 
                  if(empty($access)) {                             
                        echo CHtml::tag('div', array('class'=>'button green','id'=>'accessButton','title'=>'This button will ask you to confirm that you want to pay the access fee required to un-blur the answer text.  If you have not made an initial deposit to your account you will be asked to do so.'),'Reveal Answers');
                  } else {
                        echo CHtml::tag('div',array('class'=>'text-deemphasize'),'You Purchased access ' . $access->getAccessTime());
                  }
                  ?>         
            </div>
            </td>                      
      </tr>
      </table>     
</div>


<div class='answer_set_header blue_purple'>
      <div class='float-left'>Answers are ordered by Award Totals</div>
</div>     

<div class='breadcrumb-bar blue_purple'></div>          
       
<?php 

      $this->widget('zii.widgets.CListView',array(
            'dataProvider'=>$answerDataProvider,
            'itemView'=> empty($access) ? 'demoAnswer/_hidden' : 'demoAnswer/_view',   
            'viewData' => array('showAwardButton'=>empty($awards)),    
            'summaryText'=>''
      )); 
?>

<script> 
      $(document).ready(function(){
            $("#accessButton").click(function(){
                  $("#accessButton").html('Just a sec...');
                  showConfirmDialog();
            });              
            
            $('#watch-link').click(function(){
                  $('#watch-link').html('You are Watching');
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
            
            var url = '<?php echo Yii::app()->createUrl('demo/confirmAccess'); ?>';
                       
            xmlhttp.open("POST", url, true);
            xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            
            xmlhttp.send("<?php echo 'qid=' . $model->question_id . '&question_text='  . $model->question_text . '&YII_CSRF_TOKEN=' . Yii::app()->request->csrfToken; ?>");               
            
      }      
      
      
</script>
