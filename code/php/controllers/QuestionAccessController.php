<?php

class QuestionAccessController extends Controller
{
      public function filters()
      {
            return array(
                  'accessControl + confirm, create', // perform access control for CRUD operations
                  'postOnly + create', // we only allow deletion via POST request
            );
      }

      public function accessRules()
      {
            
            return array(
                  array('allow',
                        'actions'=>array('create','confirm'),
                        'users'=>array('@'),
                  ),
                  array('deny',  // deny all users
                        'users'=>array('*'),
                  ),
            );
      }      
      
      public function actionConfirm() 
      {      
            $activeLedgerRow = UserLedgerController::getActiveRecord(Yii::app()->user->id); 
            
            // @todo should we invert this...? 
            if($activeLedgerRow->ending_balance < abs(Pricing::ACCESS_COST)) {
                  $this->redirect(array('user/view')); 
                  return false;                          
            }
            else {
                  $this->renderPartial('_confirm',array(
                        'ledgerRow'=>$activeLedgerRow,
                        'question_id'=>$_POST['qid'],
                        'question_text'=>$_POST['question_text'],
                  ));   
            }                                 
      }
     
      
      
      public function actionCreate()
      {    
      	$question_id = $_POST['qid'];
            $activeLedgerRow = UserLedgerController::getActiveRecord(Yii::app()->user->id);            
            $validator = $this->validateRequest($activeLedgerRow);	
            
            if($validator->haltOperation) {
            	$this->redirect($validator->redirectUrl);
            	return;
            }
            
           $transaction = Yii::app()->db->beginTransaction();
            try {
           		$this->processAccessPayment($activeLedgerRow, $question_id);
			$this->insertAccessRow($question_id);
           		$transaction->commit();          		
           		$this->redirect(array('question/view','id'=>$question_id));
            } catch(Exception $e) {
            	$transaction->rollback();
            	throw $e;
            }           	
      }
      
      // @todo need to take more refined action based on error, we only action the redirect currently
      private function validateRequest($activeLedgerRow) 
      {
      	$validator = new Validator;
      	
      	if(empty($activeLedgerRow)) {
      		$validator->haltOperation = TRUE;
      		$validator->message = 'Account Not Initialized';
      		$validator->redirectUrl = array('//user/view');
      			
      	} 
      	else if($activeLedgerRow->ending_balance < Pricing::ACCESS_COST) {
      		$validator->haltOperation = TRUE;
      		$validator->message = 'Insufficient Funds';
      		$validator->redirectUrl = array('//user/view');
      	}
      	
      	return $validator;
      }
      
	private function insertAccessRow($question_id) 
	{
		$model = new QuestionAccess;
		$model->question_id = $question_id;
		$model->user_id = Yii::app()->user->id;
		$this->saveModel($model);	
	}
      
      
      private function processAccessPayment($currentUserRecord, $question_id) 
      {           
            $newUserRow = $currentUserRecord->recordTransaction(
                  LedgerTransactions::ACCESS_QUESTION, 
                  Pricing::ACCESS_COST, 
                  $question_id);
                  
            $this->saveModel($currentUserRecord);
            $this->saveModel($newUserRow);        
            
            $currentSystemRecord = SystemLedgerController::getActiveRecord();            
            $newSystemRow = $currentSystemRecord->recordTransaction(
                  LedgerTransactions::ACCESS_QUESTION,
                  Pricing::ACCESS_COST, 
                  $question_id);
            
            $this->saveModel($currentSystemRecord);
            $this->saveModel($newSystemRow);          
       }

}