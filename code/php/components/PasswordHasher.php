<?php 

class PasswordHasher 
{

      
      public function hashPassword($pass) {
            $hasher = new PasswordHash(8, false);
            $hash = $hasher->HashPassword($pass);
            if (strlen($hash) < 20)
                  throw new CHttpException(300,'Failed to hash new password');
            unset($hasher);             
            return $hash;      
      }
        
      public function checkPassword($pass) {
            $hasher = new PasswordHash(8, false);
            return $hasher->CheckPassword($pass);            
      }  
        
      private static $_instance;    
      private function __construct() {}                   
      public static function getInstance() 
      {
            if(!isset(self::$_instance)) {
                  self::$_instance = new PasswordHasher;

            }
            return self::$_instance;
      } 
}

