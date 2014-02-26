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
class User extends CActiveRecord
{

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
                  array('user_name', 'length', 'max'=>50),
                  array('email', 'length', 'max'=>100),
                  array('password', 'length', 'max'=>60),
                  array('user_name, email','unique'),
                  array('email','required'),
                  array('user_id, user_name, email, password, password_repeat', 'safe', 'on'=>'search'),
                  array('password', 'length', 'min'=>7),
                  array('email', 'email'),                  
            );
      }
      

      public function relations()
      {
            return array(
                  'answers' => array(self::HAS_MANY, 'Answer', 'user_id'),
                  'awards' => array(self::HAS_MANY, 'Award', 'awarding_user_id'),
            );
      }


      private $_sessionUser = null;
      public static function sessionUser()
      {
            if(Yii::app()->user->isGuest) {
                 $_sessionUser = null;
                  //@todo bring isGuest property into User object ?      
            }
            else {
                  // Not sure if 2nd part of conditional is necessary but it's extra safety.
                  if(empty($_sessionUser) || $_sessonUser->user_id != Yii::app()->user->id) {
                        $_sessionUser = User::model()->findByPk(Yii::app()->user->id);
                  }
                  
            }
            return $_sessionUser;
      }
      
      public function getCredits() 
      {
            $bal;
            $searchModel = new UserLedger;
            $searchModel->user_id = $this->user_id;
            $searchModel->active = 1;
            
            $dataProvider = $searchModel->search();     
            $balanceRows = $dataProvider->getData();
            
            if(empty($balanceRows)) {
                   $bal = 0;
            } else {
                        
                  $ledger = $balanceRows[0];  
                  $bal = $ledger->ending_credits;
            }

            return $bal;            
      }
      
      public function getBalance()
      {           
            $bal;
            $searchModel = new UserLedger;
            $searchModel->user_id = $this->user_id;
            $searchModel->active = 1;
            
            $dataProvider = $searchModel->search();     
            $balanceRows = $dataProvider->getData();
            
            if(empty($balanceRows)) {
                   $bal = "Account Not Initialized";
            } else {
                        
                  $ledger = $balanceRows[0];  
                  $bal = $ledger->ending_balance;
            }

            return $bal;
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
