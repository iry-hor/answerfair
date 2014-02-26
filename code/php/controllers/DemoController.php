<?php

class DemoController extends Controller
{


      public function actionCreateAward() 
      {          
             Yii::app()->user->setState('demoAward', DemoData::instance()->makeUserAward($_POST['answer_id']));                    
            $this->redirect(array('demo/viewQuestion'));            
      }
      
      public function actionWatchQuestion() 
      {
            echo true;
      }


      public function actionCreateAnswer()
      {
            $model = new Answer;
            
            if(isset($_POST['Answer'])) {
                  $model->attributes=$_POST['Answer'];
                  $now = new DateTime;
                  $model->entry_time = $now->format('Y-m-d H:i:s');                  
                  // It would be nice to just say save here too, but that means a new DemoAnswer Class
                  // or overriding CActiveRecord???  Is that the way to go?
                  
                  Yii::app()->user->setState('demoAnswer', $model);
                  $this->redirect(array('demo/viewQuestion','id'=>$model->question_id));
            }

            $this->render('demoAnswer/create',array(
                  'model'=>$model,
            ));
      }
      

      public function actionUpdateAnswer()
      {         
            $model = Yii::app()->user->getState('demoAnswer');
     
            if(isset($_POST['Answer'])) { 
                  $model->attributes=$_POST['Answer'];
                  
                  Yii::app()->user->setState('demoAnswer', $model);
                  $this->redirect(array('demo/viewQuestion','id'=>$model->question_id));
            }
            
            $this->render('demoAnswer/update',array(
                  'model'=>$model,
            ));
      }
  
      public function actionConfirmAccess() 
      {
            
            $activeLedgerRow = DemoData::instance()->getDemoLedgerRow();
                  
            $this->renderPartial('demoQuestionAccess/_confirm',array(
                  'ledgerRow'=>$activeLedgerRow,
                  'question_id'=>$_POST['qid'],
                  'question_text'=>$_POST['question_text'],
            ));   
      }
     
      
      
      public function actionCreateAccess()
      {   
            Yii::app()->user->setState('demoQuestionAccess', DemoData::instance()->makeQuestionAccess());  
            $this->redirect(array('demo/viewQuestion'));
      }
      

	  

      public function actionViewQuestion() 
      {

            Yii::app()->user->setFlash('success','This is look into how question pages look on our site.  You can hover over any item to see an detailed explanation of its function.');  
              
            $demoData = DemoData::instance();
            $demoQuestion = $demoData->getQuestionTwo();
			
            $answerDataProvider = new CActiveDataProvider('DemoAnswer',array(
                'criteria'=>array(
                    'condition'=>'question_id=:questionId',
                    'params'=>array(':questionId'=>2),					
                    'order'=>'award_total DESC, entry_time ASC'
                  ),
                'pagination'=>array(
                    'pageSize'=>10
                 ),
            ));            
            
            //$demoDataProvider = new AnswerDataProviderMock($demoQuestion->answers);

            $access = Yii::app()->user->getState('demoQuestionAccess');        
            $awards = Yii::app()->user->getState('demoAward');
            $userAnswer = Yii::app()->user->getState('demoAnswer');
                             
            $this->render('demoQuestion/view',array(
                  'model'=>$demoQuestion,
                  'answerDataProvider'=>$answerDataProvider,
                  'access'=>$access,
                  'awards'=>$awards,
                  'userAnswer'=>$userAnswer,
            ));            
      }

      public function getAwardButton($answer)
      {            
            $url = Yii::app()->createUrl('demo/createAward');
            $output = CHtml::openTag('form', array('action'=>$url,'method'=>'post'));
            $output .= CHtml::hiddenField('answer_id', $answer->answer_id);
            $output .= CHtml::hiddenField('YII_CSRF_TOKEN',Yii::app()->request->csrfToken);
            $output .= CHtml::submitButton('Make Award', array('class'=>'award-button green','title'=>'After reading through all the answers, click on this control to give your access fee to the answers author.'));
            $output .= CHtml::closeTag('form');             
            return $output;   
      }
      
            
      public function getAwardIcon($award) 
      {
            if(empty($award))
                  return;
            
            $output = CHtml::openTag('a', array('href'=>'','title'=>'This icon indicates that you awarded your access fee to this particular answer'));
            $output .= CHtml::image(Yii::app()->baseUrl. '/images/award.png');
            $output .= CHtml::closeTag('a');
            
            return $output;                      
      }

      public function getAwardFromAppUser($answer_id)
      {
            $demoAward = Yii::app()->user->getState('demoAward');
            if(empty($demoAward)) 
                  return false;
            
            return $demoAward->answer_id == $answer_id;
      }     
      
      public function notUsersAnswer($answer) 
      {
            return true;        
      }


}