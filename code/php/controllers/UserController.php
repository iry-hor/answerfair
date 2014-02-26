<?php

class UserController extends Controller
{
      public $layout='//layouts/column1';

      public function filters()
      {
            return array(
                  'accessControl -create',
                  'postOnly + delete',
            );
      }

      public function accessRules()
      {        
            return array(
                  array('allow',
                        'actions'=>array('view','username','password','email','withdraw'),
                        'users'=>array('@'),
                  ),                
                  array('deny',  // deny all users
                        'users'=>array('*'),
                  ),
            );
      }

      //@todo should we reinstate the ID parameter?  
      public function actionView()
      {
            $this->render('view',array(
                  'model'=>$this->loadModel(Yii::app()->user->id),
                  'activeEdit'=>'none',
                  'changePasswordForm'=> new ChangePasswordForm,
                  'withdrawForm'=>new WithdrawForm,   
            ));
      }

      public function actionCreate()
      {
            $model = new NewUser;            
            if(isset($_POST['NewUser']))
            {      
                  $model->attributes = $_POST['NewUser'];       
                  if($model->save() && $this->loginNewUser($model)) {
                        $this->redirect(array('site/index'));
                  }       
            }

            $this->render('create',array(
                  'model'=>$model,
            ));
      }

      public function actionUsername() 
      {      
            $model = $this->loadModel(Yii::app()->user->id);
            $activeEdit = 'none';             
       
            if(isset($_POST['User'])) {      
                  $model->user_name = $_POST['User']['user_name'];       
                  if($model->save()) {                                      
                        Yii::app()->user->setFlash('success', 'User Name successfully changed');
                  } else {
                        $activeEdit = 'username';  
                  }    
            }
            
            $this->render('view',array(
                  'model'=>$model,
                  'activeEdit'=>$activeEdit,
                  'changePasswordForm'=> new ChangePasswordForm,
                  'withdrawForm'=>new WithdrawForm,
            ));      
      }


      public function actionPassword() 
      {
            
            $model = $this->loadModel(Yii::app()->user->id);
            $passwordFormModel = new ChangePasswordForm;
            $activeEdit = 'none';
            if(isset($_POST['ChangePasswordForm'])) {
                  $passwordFormModel->attributes = $_POST['ChangePasswordForm'];

                  if($passwordFormModel->validate() && $passwordFormModel->changePassword()) {
                        Yii::app()->user->setFlash('success', 'Password successfully changed');
                  } else {
                        $activeEdit = 'password';
                  }
                  
            }
            
            $this->render('view',array(
                  'model'=>$model,
                  'activeEdit'=>$activeEdit,
                  'changePasswordModel'=> $passwordFormModel,
                  'withdrawForm'=>new WithdrawForm,                      
            ));
      }
      


      public function actionEmail() 
      {
            $model = $this->loadModel(Yii::app()->user->id);
            $activeEdit = 'none';            
            if(isset($_POST['User']))
            {      
                  $model->email = $_POST['User']['email'];       
                  if($model->save()) {                                      
                        Yii::app()->user->setFlash('success', 'Email successfully changed');
                  } else {
                        $activeEdit = 'email';
                  }      
            }

            $this->render('view',array(
                  'model'=>$model,
                  'activeEdit'=>$activeEdit,
                  'changePasswordForm'=> new ChangePasswordForm,
                  'withdrawForm'=>new WithdrawForm,                  
            ));
      }


      public function actionWithdraw() 
      {
            $model = new WithdrawForm;
            
            $model->attributes = $_POST['WithdrawForm'];
            
            $user = User::model()->findByPk(Yii::app()->user->id);
                        
            if(!$model->validate()) {
                  
                  $this->render('view',array(
                        'model'=>$user,
                        'activeEdit'=>'withdraw',
                        'changePasswordForm'=> new ChangePasswordForm,
                        'withdrawForm'=>$model,                      
                  ));            
                  return;    
            }
                  
            
            
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
                       
            $this->render('view',array(
                  'model'=>$user,
                  'activeEdit'=>'none',
                  'changePasswordForm'=> new ChangePasswordForm,
                  'withdrawForm'=>$model,                      
            ));                  
                                   
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

            
      
      public function loginNewUser($model) 
      {
            $loginForm = new LoginForm();
            $loginForm->email = $model->email;
            $loginForm->password = $model->password_repeat;
            return $loginForm->login();         
      }
      
      protected function performAjaxValidation($model)
      {
          if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
          {
              echo CActiveForm::validate($model);
              Yii::app()->end();
          }
      }      
          

      public function loadModel($id)
      {
            $model=User::model()->findByPk($id);
            
            // users are 0only allowed to view their own account pages, because right now
            // we don't have public profiles            
            if($id !== Yii::app()->user->id)
                  throw new CHttpException(804,'You are not permissioned to view this user.');
            
            if($model === null)
                  throw new CHttpException(404,'The requested page does not exist.');
            return $model;
      }

}