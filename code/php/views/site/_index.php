
            
<div class='central_proposition'>

      <div class='overflow-hidden padded'>
           <a class='plain_link' onclick='actionQuestion()'><span class='dark_blue_gradient landing_button'>Q</span></a>
            <span class='or_separator'><?php echo $this->echoText(__FILE__,' - or - '); ?></span>
             <?php echo CHtml::openTag('a',array('class'=>'plain_link','href'=>CHtml::normalizeUrl(array('site/landingAnswers')))); ?>                   
                  <span class='red_gradient landing_button'>A</span>
             <?php echo CHtml::closeTag('a'); ?>          
      </div>
      <div class='padded'>
            
            <a class='plain_link' onclick='actionQuestion()'>
                  <span><?php echo $this->echoText(__FILE__,'SUBMIT A QUESTION'); ?></</span>
            </a>
            <span class='padded'>|</span>
            
            <?php echo CHtml::openTag('a',array('class'=>'plain_link','href'=>CHtml::normalizeUrl(array('site/landingAnswers')))); ?>  
                  <span><?php echo $this->echoText(__FILE__,'ANSWER QUESTIONS'); ?></</span>           
            <?php echo CHtml::closeTag('a'); ?>
      </div>
      
      <div class='hidden' id='question_action_section'>
            <?php $form=$this->beginWidget('CActiveForm', array('action'=>array('myisamQuestion/index'),'method'=>'GET')); ?>                          
                  <input id='big_question_field' class='giant_form_field form_field' type='text' name='q' placeholder='<?php echo $this->echoText(__FILE__,'What would you pay a dollar to know?'); ?>' />
            <?php $this->endWidget(); ?>
      </div>
       
</div>
      

<script>
      function actionQuestion() {
            $('#question_action_section').toggle('fast');
            $("#big_question_field").focus();

      }
       
</script>

