<?php

class QuestionController extends Controller
{


	public $layout='//layouts/column1'; //changed from column2
      
	public function filters()
	{
		return array(
			'accessControl -create', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
    }
      
	public function accessRules()
	{
	      
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','search','viewDemo','create'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			
			array('deny',  // deny all users
				'users'=>array('*'),
			),
            );
	}
      
      
	public function actionView($id)
	{
	             
            $answerDataProvider=new CActiveDataProvider('Answer',array(
                'criteria'=>array(
                    'condition'=>'question_id=:questionId',
                    'params'=>array(':questionId'=>$id,
                                    //':userId'=>Yii::app()->user->id
                                    ),
                    
                    'order'=>'award_total DESC, entry_time ASC'
                  ),
                'pagination'=>array(
                    'pageSize'=>10
                 ),
            ));
            
            $access = array();
            $awards = array();
            $userAnswer = null;
            
            if(Yii::app()->user->isGuest) {
            	//Yii::app()->user->setFlash('success','Please Note:  You will not be able to reveal the answers until you login');          	
            }           
            else {
            	
                  $uid = Yii::app()->user->id;
                  $access = QuestionAccess::model()->findByPk(array('question_id'=>$id,'user_id'=>$uid));
                  $userAnswer = Answer::model()->findByAttributes(array('question_id'=>$id,'user_id'=>$uid));
                  if(!empty($access)) {
                        $awards = Award::model()->findAllByAttributes(array('question_id'=>$id,'awarding_user_id'=>$uid));             
                  }                                   
            } 

			$this->render('view',array(
				'model'=>$this->loadModel($id),
				'answerDataProvider'=>$answerDataProvider,
				'access'=>$access,
				'awards'=>$awards,
				'userAnswer'=>$userAnswer,
            ));
            
		}

      public function getAwardButton($answer)
      { 
            $url = Yii::app()->baseUrl.'/index.php?r=award/create';		  
            $output = CHtml::openTag('form',array('action'=>$url,'method'=>'post'));
            $output .= CHtml::hiddenField('answer_id', $answer->answer_id);
            $output .= CHtml::hiddenField('question_id', $answer->question_id);
            $output .= CHtml::hiddenField('YII_CSRF_TOKEN',Yii::app()->request->csrfToken);            
            $output .= CHtml::submitButton('Make Award', array('class'=>'award-button green'));
            $output .= CHtml::closeTag('form');             
            return $output;   
      }
      
        
      // @todo this function and getAcessComponenent are the 'messiest' controller methods.
      // could do with some cleaing up.  Normalizing tag/link construction, just allowing 
      // access rules to take care of action branching (instead of proactively setting the action here) 
      public function getAnswerComponent($qid, $userAnswer, $access, $questionHasAnswers)
      {
            echo CHtml::openTag('div',array('class'=>'answer_component float-left'));
            
            if(empty($userAnswer)) {

                  //@todo -- non-normal url construction
                  $url = Yii::app()->baseUrl.'/index.php?r=answer/create';
                  
                  $buttonClass = $questionHasAnswers ? 'button grey' : 'button green';
                              
                  echo CHtml::openTag('form',array('action'=>$url,'method'=>'post'));
                  echo CHtml::hiddenField('qid', $qid);
				  echo CHtml::hiddenField('YII_CSRF_TOKEN',Yii::app()->request->csrfToken);				  
                  echo CHtml::submitButton('Answer Yourself', array('class'=>$buttonClass));
                  echo CHtml::closeTag('form');
                  
            } else {
                        
                  //@todo need to handle multiple answers      
                  echo CHtml::tag('span',array('class'=>'text-deemphasize'),'You Answered ' . $userAnswer->getEntryTime());
                  echo CHtml::openTag('span', array('class'=>'padding-left-10'));
                  if(empty($userAnswer->awards)) {
                        echo CHtml::link('Edit Answer', array('answer/update', 'id'=>$userAnswer->answer_id));  
                  } else {
                        //@todo this isn't working b/c we're not using _POST, I think.  should we be?  review documentation
                        echo CHtml::link('Answer Again', array('answer/create', 'qid'=>$userAnswer->question_id));
                  }
                  echo CHtml::closeTag('span');
            }
      
            echo CHtml::closeTag('div');
      }

      public function getAccessComponent($qid, $userAccess, $questionHasAnswers) 
      {
            echo CHtml::openTag('span',array('class'=>'float-right align-right'));
            
            if($questionHasAnswers) {
                  
                  if(Yii::app()->user->isGuest) {
                        echo CHtml::link('Reveal Answers', array('site/login'), array('class'=>'button green hover-white'));             
                  }
                  else {
                        if(empty($userAccess)) {
                              
                              // @todo -- Test and then Re-use this syntax everywhere we're doing findByPk stuff...
                              $user = User::sessionUser();
                              if($user->balance > abs(PRICING::ACCESS_COST)) {
                                    echo CHtml::tag('div', array('class'=>'button green','id'=>'accessButton'),'Reveal Answers');
                              } else {
                                    echo CHtml::link('Reveal Answers',array('user/view'), array('class'=>'button green hover-white'));
                              }
                        } else {
                             echo CHtml::tag('div',array('class'=>'text-deemphasize'),'You Purchased access ' . $userAccess->getAccessTime());
                        }     
                  }
            } 

            echo CHtml::closeTag('span');
      }

      


	public function actionCreate($search = '')
	{
		$model = new Question;
            $model->question_text = $search;
            
            // Email is required for questions being entered by guests.  But that 
            // email is saved as a user and the notification is ultimately linked to 
            // a user_id.  So this is way to workaround the email requirement.  
            // Should probably have a separate Form model for GuestQuestionEntry...
            // that would avoid this hackiness.   On the other hand, didn't want to 
            // proliferate models.  
            if(!Yii::app()->user->isGuest) {
                $model->email = 'dummy@email.com';  
            }
                                  
		if(isset($_POST['Question'])) {
			$model->attributes=$_POST['Question'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->question_id));
		}
		
		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Question::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

      // @todo Still not confident that we've found the best place for this code.
      // relocated from Award model class after demo data effort.
      public function getAwardFromAppUser($question_id, $answer_id)
      {
            $model = Award::model()->findByAttributes(array(
                        'awarding_user_id'=>Yii::app()->user->id,
                        'question_id'=>$question_id,
                        'answer_id'=>$answer_id
            ));
      
            return !empty($model);
      }     
	  
        
        
      public function echoAwardIcon($answer_id, $userAwards) 
      {
            $award = $this->getUsersAward($answer_id, $userAwards);
            if(!empty($award)) {
                  echo ProjectImage::image('award.png', array(
                        'class'=>'float-right', 
                        'title'=>'You made an award to this answer'
                  ));                                                 
            }     
      }   
      
      
      public function getUsersAward($answerId, $userAwards)
      {           
            foreach ($userAwards as $award) {
                  if($award->answer_id == $answerId) 
                        return $award;
            }                
            return NULL;
      }
            

}
