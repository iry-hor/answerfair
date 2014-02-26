<?php

/**
 * ChangePasswordForm class.
 * ChangePasswordForm is the data structure for allowing users to 
 * change their account password.
 * It is used by the 'changePassword' action of 'UserController'.
 */
class ChangePasswordForm extends CFormModel
{
      public $old_password;
      public $new_password;
      public $new_password_repeat;

      private $_identity;

      public function rules()
      {
            return array(
                  array('old_password, new_password, new_password_repeat', 'required'),
                  array('old_password', 'authenticate'),
                  array('new_password', 'compare'),
                  array('new_password', 'length', 'min'=>7),
            );
      }

      public function attributeLabels()
      {
            return array(
                  'oldPassword'=>'Please Provide Your Old Password',
                  'new_password'=>'New Password',                                   
            );
      }

      public function authenticate($attribute, $params)
      {
            $user = User::model()->findByPk(Yii::app()->user->id);
            
            if(!$this->hasErrors())
            {
                  $this->_identity = new UserIdentity($user->email, $this->old_password);
                  if(!$this->_identity->authenticate())
                        $this->addError('oldPassword','Incorrect username or password.');
            }
      }


      public function changePassword()
      {
            $user = User::model()->findByPk(Yii::app()->user->id);               
            
            if($this->_identity===null)
            {                
                  $this->_identity = new UserIdentity($user->email,$this->old_password);
                  $this->_identity->authenticate();
            }
            
            if($this->_identity->errorCode === UserIdentity::ERROR_NONE)
            {
                  $user->password = PasswordHasher::getInstance()->hashPassword($this->new_password);
                  $user->save();
                  return true;
            }
            else
                  return false;
      }
}
