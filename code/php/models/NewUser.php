<?php

/**
 * This is the model class for table "tbl_user".
 *
 * The followings are the available columns in table 'tbl_user':
 * @property string $user_id
 * @property string $user_name
 * @property string $email
 * @property string $password
 *
 * The followings are the available model relations:
 * @property Answer[] $answers
 * @property Award[] $awards
 */
class NewUser extends CActiveRecord
{


      public $password_repeat;

	
	/*
	This method is added to handle the case when the user has given us his/her email
	address (to be notified when a question is asked).  When it comes time to register,
	we want need to position the object to reflect the half-registration state of the user. 
	*/
	public function beforeValidate() 
	{ 
    	$user = User::model()->findByAttributes(array('email'=>$this->email));
		
		// Half registration conditions are defined by stubbed user table entry:  
		// 		email but nothing else		
		if(!empty($user) && empty($user->password)) {
			$this->isNewRecord = false;
			$this->user_id = $user->user_id;
			// Had to dig into the Validator code to realize that this is necessary
			// we've sort of side stepped a lot of the CActiveRecord code with this
			// approach, and here we're adding in one necessary piece of information
			// This whole half registration object implementation should be reviewed.
			$this->setOldPrimaryKey($user->user_id);
		}		
		return true;
      }
	
	
      public function afterValidate() 
      {
            parent::afterValidate();
            if(!$this->hasErrors())  {
                  $this->password = PasswordHasher::getInstance()->hashPassword($this->password);
            }  
      }
	
      public function save() 
      {
            $success = false;
            $transaction = Yii::app()->db->beginTransaction();       
            try {
                  if(!parent::save()) {
                        return false;
                  }          
                  
                  $this->initializeUserLedger();                  
                  $transaction->commit();
                  $success = true;      
            } catch(Exception $e) {
                  $transaction->rollback();
                  throw $e;
            }   
                       
            return $success;
      }
      
      
      private function initializeUserLedger($credits = 5) 
      {
            $ledger = new UserLedger;
            $ledger->user_id = $this->user_id;
            $ledger->beginning_balance = 0;
            $ledger->beginning_credits = 0;
            $ledger->transaction_type = LedgerTransactions::INITIALIZATION;
            $ledger->transaction_amount = 0;
            $ledger->ending_balance = 0;
            $ledger->ending_credits = $credits;     
            $ledger->reference = '';
            $ledger->active = 1;          
            $ledger->save();           
      }

      public static function model($className=__CLASS__)
      {
            return parent::model($className);
      }

      public function tableName()
      {
            return 'tbl_user';
      }

      public function rules()
      {
            return array(
                  array('password', 'compare'),
                  array('user_name', 'length', 'max'=>50),
                  array('email', 'length', 'max'=>100),
                  array('password', 'length', 'max'=>60),
                  array('user_name, email','unique'),
                  array('email, password, password_repeat','required'),
                  array('user_id, user_name, email, password, password_repeat', 'safe', 'on'=>'search'),
                  array('password', 'length', 'min'=>7),
                  array('email', 'email'),                  
            );
      }
      

      public function behaviors()
      {
           return array(
                 'CTimestampBehavior' => array(
                        'class' => 'zii.behaviors.CTimestampBehavior',
                        'createAttribute' => 'registration_time',
                        'updateAttribute' => NULL,
                        'setUpdateOnCreate' => true,
                        ),
            );
      }
            

      public function attributeLabels()
      {
            return array(
                  'user_id' => 'User',
                  'user_name' => 'User Name',
                  'email' => 'Email',
                  'password' => 'Password',
            );
      }

      public function search()
      {
            $criteria=new CDbCriteria;

            $criteria->compare('user_id',$this->user_id,true);
            $criteria->compare('user_name',$this->user_name,true);
            $criteria->compare('email',$this->email,true);
            $criteria->compare('password',$this->password,true);

            return new CActiveDataProvider($this, array(
                  'criteria'=>$criteria,
            ));
      }
      
}
