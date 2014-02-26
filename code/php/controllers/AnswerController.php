<?php

class AnswerController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1'; 
      private $_question = null;
           
        
      protected function loadQuestion($qid)
      { 
            //if the project property is null, create it based on input id      
            if($this->_question === null)  {
               $this->_question = Question::model()->findByPk($qid);
               
               if($this->_question === null) {
                   throw new CHttpException(404,'The requested question does not exist.');
               }
            }
            return $this->_question;
      }
        
        
      public function filterQuestionContext($filterChain) 
      {
            if(isset($_POST['qid']))
                $this->loadQuestion($_POST['qid']);
            else 
                throw new CHttpException(403,'Must specify a question before performing this action.');
            //complete the running of other filters and execute the requested action     
            
            $filterChain->run();
      }    
    
    

	public function filters()
	{
		return array(
			'accessControl', 
			'questionContext + create'
		);
	}

	public function accessRules()
	{
		return array(
			array('allow', 
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),		
			array('deny',  
				'users'=>array('*'),
			),
		);
	}

	public function actionCreate()
	{
		$activeLedgerRow = UserLedgerController::getActiveRecord(Yii::app()->user->id);
		$cost = $this->determineCostToAnswer();
            
		$validator = $this->validateRequest($activeLedgerRow, $cost);
		if($validator->haltOperation) {
			$this->redirect($validator->redirectUrl);
			return;
		}
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$model = $this->insertAnswerRow();		
			$this->processAnswerPayment($activeLedgerRow, $model, $cost);
			$transaction->commit();
			
			$this->redirect(array('answer/update','id'=>$model->answer_id));
		} catch(Exception $e) {
			$transaction->rollback();
			throw $e;
		}
	}
	
      private function determineCostToAnswer() 
      {
            $cost = Pricing::ANSWER_COST;
            // If user bought access they get one and only answer for free
            if($this->userHasQuestionAccess() && $this->userHasNotAnsweredAlready()) {
                  $cost = 0;   
            } 
            return $cost;     
      }
      
      
      private function userHasQuestionAccess() 
      {
            $uid = Yii::app()->user->id ;
            $qid = $this->_question->question_id;
            $access = QuestionAccess::model()->findByAttributes(array('question_id'=>$qid,'user_id'=>$uid));  
            return !empty($access);
      }
      
      private function userHasNotAnsweredAlready() 
      {
            $uid = Yii::app()->user->id ;
            $qid = $this->_question->question_id;
            
            $answers = Answer::model()->findByAttributes(array('question_id'=>$qid,'user_id'=>$uid));
                  
            return empty($answers);
      }
            
      
	
	private function insertAnswerRow() 
	{
		$model = new Answer;
		$model->question_id = $this->_question->question_id;
		$model->user_id = Yii::app()->user->id;	
		$model->in_short = '';
		$model->answer_text = '';
		
		$success = $model->save();
		if(!$success) {
			throw new CHttpException(900, $this->errorsToString($model));
		}
		return $model;		
	}
	
	function errorsToString($model) {
		$errors = '';
		foreach ($model->getErrors() as $attrname => $errlist){
			foreach ($errlist as $err) {
				$errors .= "    $err\n";
			}
		}
		return $errors;
	}
	
	
	private function processAnswerPayment($activeLedgerRow, $answer, $cost) 
	{
		$newRow = $activeLedgerRow->recordCreditsFirstTransaction(
		    LedgerTransactions::SUBMIT_ANSWER, 
		    Pricing::ANSWER_COST, 
		    $answer->answer_id);
                
		$this->saveModel($activeLedgerRow);
		$this->saveModel($newRow);
		
		$currentSystemRecord = SystemLedgerController::getActiveRecord();
		$newSystemRow = $currentSystemRecord->recordTransaction(
		    LedgerTransactions::SUBMIT_ANSWER, 
		    Pricing::ANSWER_COST, 
		    $answer->answer_id);
		
		$this->saveModel($newSystemRow);
            
            // @todo this is where the current object design model breaks down...
		if($currentSystemRecord->persist)
		    $this->saveModel($currentSystemRecord);	
        
	}

	
	// @todo need to take more refined action based on error, currently messages aren't being displayed
	private function validateRequest($activeLedgerRow, $cost)
	{
	      
        $cost = $this->determineCostToAnswer();
		$validator = new Validator;
		
		if(empty($activeLedgerRow)) {
			$validator->haltOperation = TRUE;
			$validator->message = 'Account Not Initialized';
			$validator->redirectUrl = array('//user/view');
			 
		} else if($activeLedgerRow->getEndingSuperBalance() < $cost) {
			$validator->haltOperation = TRUE;
			$validator->message = 'Insufficient Funds';
			$validator->redirectUrl = array('//user/view');
		}
		 
		return $validator;
	}	
	


	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		
            
            // business rule is that you can't edit an answer that has awards against it
            // the ui should prevent this, but here we have a 2nd server side check.
            // need to handle the return acount better however.  @todo
		if(!empty($model->awards)) {                 
                  return false;   
		}
                  
            
		if(isset($_POST['Answer'])) {	
			$model->attributes=$_POST['Answer'];
			
			if($model->save())
				$this->redirect(array('question/view','id'=>$model->question_id));
		} else {
			//what to do?
		}
            
		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Answer::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='answer-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
    // We shouldn't be invoked if the user is a guest because this method is only
    // called when we're accessing a question in full view mode, but the check has
    // been put to prevent bombing... came up in demo Testing, which is also why
    // this method has been relocated the Controller (from Model)
    public function notUsersAward($award) 
    {
          if(Yii::app()->user->isGuest)
                return true;
          return $award->user_id != Yii::app()->user->id;            
    }

}
