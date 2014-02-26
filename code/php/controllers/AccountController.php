<?php

class AccountController extends Controller
{
     
      public function filters()
      {
            return array(
                  'accessControl', 
                  'postOnly',
            );
      }
      
      public function accessRules()
      {
            
            return array(
                  array('allow', 
                        'actions'=>array('deposit','withdraw'),
                        'users'=>array('@'),
                  ),
                  array('deny',
                        'users'=>array('*'),
                  ),
            );
      }      
      
      public function actionDeposit() 
      {
            $card = $_POST['stripeToken'];
            $amount = $_POST['amount'];
            
            try {
                  $record = StripeLiason::getInstance()->charge($card, $amount);
                  $success = $this->recordDeposit($record);   
                  Yii::app()->user->setFlash('success', 'Your deposit has been made.');
                   
            } catch(Exception $e) {
                  Yii::app()->user->setFlash('error', $e->getMessage());
            }
           
            // @todo or refresh here?           
            $this->redirect(array('user/view'));
      }
        
      
      // @todo are we a little uncomfortable with this active row business.... what if somehow there are rows
      // but they all get set to 0, then we're going to re-initialize the account here, erroneously...      
      private function recordDeposit($chargeArray) 
      {
            $success = false;
            $uid = Yii::app()->user->id;           
            $amount = $chargeArray['amount'] / 100;   //Stripe transactions are denominated in pennies
            $credits = $chargeArray['fee'] / 100;     //Stripe transactions are denominated in pennies
            
            $cash = $amount - $credits;
            
            $ref = 'Stripe[' . $chargeArray['id'] . ']';          
            
            $activeLedger = UserLedger::model()->findByAttributes(array('user_id'=>$uid,'active'=>1));  
            $newLedger = $activeLedger->recordTransaction(LedgerTransactions::DEPOSIT,$cash, $ref, $credits);
    
            $transaction = Yii::app()->db->beginTransaction();                                                  
            
            try {          
                  $this->saveModel($newLedger);
                  
                  if(!empty($activeLedger))
                        $this->saveModel($activeLedger);
                  
                  $transaction->commit();
                  $success = true;
            } catch(Exception $e) {
                  $transaction->rollback();
                  throw $e;
                  // @todo - we've got real problems here because we've already successfully charged the card
                  // need to model this out 
                  // should:  alert user that there was a problem.  notify admin.  give them the 
                  // stripe reference ID.
            }
            return $success;
      }
}

?>