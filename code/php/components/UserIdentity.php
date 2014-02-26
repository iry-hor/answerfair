<?php

class UserIdentity extends CUserIdentity
{
      private $_id;
      private $_admin;
      
      public function authenticate()
      {
            $user = User::model()->findByAttributes(array('email'=>$this->username));
            
            if($user === null) {
                  $this->errorCode=self::ERROR_USERNAME_INVALID;
            }
            else if(!$this->comparePasswords($this->password, $user->password)) { // commented out MD5 encryption.
                  $this->errorCode=self::ERROR_PASSWORD_INVALID;
            }
            else {
                  $this->_id = $user->user_id;
                  $this->_admin = $user->admin;
                  $this->errorCode = self::ERROR_NONE;
            }
            return !$this->errorCode;
      }
 
 
      private function comparePasswords($pass, $hash) 
      {
            $hasher = new PasswordHash(8, false);
            return $hasher->CheckPassword($pass, $hash);
      }
 
      public function getId()
      {
            return $this->_id;
      }
    
      public function getAdmin() 
      {
            return $this->_admin;      
      }


}