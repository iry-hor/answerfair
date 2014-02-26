<?php

class AwardController extends Controller
{

	public function filters()
	{
		return array(
			'accessControl + grade', // perform access control for CRUD operations
			'postOnly + create, grade', // we only allow deletion via POST request
		);
	}

	public function accessRules()
	{
	      
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'view' actions
				'actions'=>array('create'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
      	
	public function actionCreate() 
	{
	      //@todo possibe to get bad data in here from fake post activity?
		$question_id = $_POST['question_id'];
		$answer_id = $_POST['answer_id'];
		
            
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$model = $this->saveNewAward($question_id, $answer_id, Pricing::AWARD_AMOUNT);
                  
			$activeRow = UserLedgerController::getActiveRecord($model->answer->user_id);
                          	 	
            	$newRow = $activeRow->recordTransaction(
            	     LedgerTransactions::MAKE_AWARD, 
            	     Pricing::AWARD_AMOUNT, 
            	     $model->award_id);
			
			$this->saveModel($newRow);
			$this->saveModel($activeRow);

			$activeSystemRow = SystemLedgerController::getActiveRecord();
                  $newSystemRow = $activeSystemRow->recordTransaction(
                        LedgerTransactions::MAKE_AWARD,
                        Pricing::AWARD_AMOUNT, 
                        $model->award_id);
                  
			$this->saveModel($newSystemRow);
			$this->saveModel($activeSystemRow);
			
			$transaction->commit();
				
			$this->redirect(array('question/view','id'=>$model->question_id));
		} catch(Exception $e) {
			$transaction->rollback();
			throw $e;
		}		
	}
	
      public function actionRate() 
      {
            $model = $this->loadModel($_POST['award_id']);
            $model->rating = $_POST['grade'];  // ok bad naming job... 
            $model->save();
            
            //@todo should be Ajaxed in the future
            $this->redirect(array('question/view','id'=>$model->question_id));                                         
      }
      
	public function saveNewAward($questionId, $answerId, $amount)
	{
		$model = new Award;
		$model->awarding_user_id = Yii::app()->user->id;
		$model->question_id = $questionId;
		$model->answer_id = $answerId;
		$model->award_amount = $amount;
		$this->saveModel($model);

		return $model;		
	}
      
	public function loadModel($id)
	{
		$model=Award::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


}
