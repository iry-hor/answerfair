<?php

/**
 * WithdrawForm class.
 * WithdrawForm is the data structure for keeping
 * user withdrawal request data. It is used by the 'actionWithdraw' action of 'AccountController'.
 */
class WithdrawForm extends CFormModel
{
      public $account_name;
      public $stripeToken;
      public $amount;     
      public $password;     


      public function rules()
      {
            return array(
                  array('password, account_name, stripeToken, amount', 'required'),
                  array('password', 'authenticate'),
                  array('$account_name, stripeToken, amount, password', 'safe')
                  //array('amount', 'value', 'lessthan'=>)
            );
      }

      public function attributeLabels()
      {
            return array(
                  'account_name'=>'Bank Account Name',
                  'amount'=>'Withdrawal Amount',
                  'password'=>'Password',                                   
            );
      }

      public function authenticate($attribute, $params)
      {
            $user = User::model()->findByPk(Yii::app()->user->id);
            $_identity = new UserIdentity($user->email, $this->password);
            if(!$_identity->authenticate())
                  $this->addError('password','Incorrect username or password');
      }



      public function save() 
      {
            
            $ledgerRow = null;
            try {
                  $ledgerRow = $this->recordWithdrawal($user, $model->amount);      
            }
            catch(Exception $e) {
                  Yii::app()->user->setFlash('error', $e->getMessage());
                  return;
            }   

            $record = null;
            try {
                  $record = StripeLiason::getInstance()->transfer($model->account_name, $model->stripeToken, $model->amount, $user->email);
            }
            catch(Exception $e) {
                  Yii::app()->user->setFlash('error', 'There was an error sending your request to Stripe.  Please contact support to have this matter looked into.  Stripe Error:  ' . $e->getMessage());
                  // @todo - we could try to undo the withdraw request here b/c we know we didn't
                  // send it succesfully to Stripe.   As it is now,
                  return;   
            }      
            
            try {
                  $ledgerRow->reference = 'Stripe [ ' . $record['id'] . ' ]';
                  $ledgerRow->save();
            }
            catch(Exception $e) {
                  //@todo support will need to find out why this failed and update the reference if possible.
                  Yii::app()->user->setFlash('error', 'We successfully sent your withraw request to Stripe, but failed to update the reference field which should container your stripe lookup id.  Please contact support to resolve this matter.  Error was:  ' . $e->getMessage());
                  return;   
            }                      
      }


      private function recordWithdrawal($user, $amount) 
      {
            $amount = -1 * $amount;
            
            $activeLedger = UserLedger::model()->findByAttributes(array('user_id'=>$user->user_id,'active'=>1));  
            $newLedger = $activeLedger->recordTransaction(LedgerTransactions::WITHDRAWAL, $amount, 
                  LedgerTransactions::FAILED_STRIPE_REF);    
            // In the above line, we record a failed action with stripe.  We will overwrite this
            // reference after we get a success response from Stripe, at which point the tfer will be pending
            
            $transaction = Yii::app()->db->beginTransaction();                                                  
            try {                              
                  $this->saveModel($newLedger);
                  $this->saveModel($activeLedger);
                  
                  $activeSysLedger = SystemLedgerController::getActiveRecord();            
                  $newSysLedger = $activeSysLedger->recordTransaction(LedgerTransactions::WITHDRAWAL, $amount, $newLedger->id);
            
                  $this->saveModel($newSysLedger);        
                  $this->saveModel($activeSysLedger);

                  $transaction->commit();         

            } catch(Exception $e) {
                  $transaction->rollback();
                  throw $e;
            }  
            
            return $newLedger;
      }      


}
