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
                  $activeLedger = UserLedgerController::getActiveRecord(Yii::app()->user->id);                         
                  $record = StripeLiason::getInstance()->charge($card, $amount);
                  $success = $this->recordDeposit($activeLedger, $record);
                     
                  Yii::app()->user->setFlash('success', 'Your deposit has been made.'); 
            } catch(Exception $e) {
                  Yii::app()->user->setFlash('error', $e->getMessage());
            }
           
            // @todo or refresh here?           
            $this->redirect(array('user/view'));
      }
        

      
      // @todo are we a little uncomfortable with this active row business.... what if somehow there are rows
      // but they all get set to 0, then we're going to re-initialize the account here, erroneously...      
      private function recordDeposit($activeLedger, $chargeArray) 
      {
   
            $amount = $chargeArray['amount'] / 100;   //Stripe transactions are denominated in pennies
            $credits = $chargeArray['fee'] / 100;     //Stripe transactions are denominated in pennies
            $cash = $amount - $credits;
            $ref = 'Stripe[' . $chargeArray['id'] . ']';          
            
            $transaction = Yii::app()->db->beginTransaction();                                                  
            
            try {     
                  $newLedger = $activeLedger->recordTransaction(LedgerTransactions::DEPOSIT,$cash, $ref, $credits);
                  $this->saveModel($newLedger);
                  
                  if(!empty($activeLedger))
                        $this->saveModel($activeLedger);
                  
                  $transaction->commit();
            } catch(Exception $e) {
                  $transaction->rollback();
                  throw $e;
                  // @todo - we've got real problems here because we've already successfully charged the card
                  // need to model this out 
                  // should:  alert user that there was a problem.  notify admin.  give them the 
                  // stripe reference ID.
            }
      }
}

?>