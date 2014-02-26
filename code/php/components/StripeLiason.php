<?php 

class StripeLiason 
{            
      
      public function charge($card, $amount) 
      {             
            try {
                  $charge = json_decode(Stripe_Charge::create(array(
                    "amount" => $amount, // amount in cents, again
                    "currency" => "usd",
                    "card" => $card,
                    "description" => 'user xref ' . Yii::app()->user->id)),true);
                  
                  return $charge;
                 
            } catch(Stripe_CardError $e) {
                  // Ideally we'd like to extract the data from the stripe error and re-throw our own
                  // so that this class is the only php class that needs to know anything about
                  // how stripe works, as its name implies                  
                  throw $e;
            }                
      }
      
      public function transfer($name, $bank, $amount, $email) 
      {
            try { 
                  $recipient = json_decode(Stripe_Recipient::create(array(
                              "name" => $name,
                              "type" => "individual",
                              "bank_account" => $bank,
                              "email" => $email)),true);
      
                  $transfer = json_decode(Stripe_Transfer::create(array(
                              "amount" => $amount * 100, // amount in cents
                              "currency" => "usd",
                              "recipient" => $recipient['id'])), true);
                              
                  return $transfer;
                              
            } catch(StripeError $e) {
                  // Ideally we'd like to extract the data from the stripe error and re-throw our own
                  // so that this class is the only php class that needs to know anything about
                  // how stripe works, as its name implies  
                  throw $e;  
            }
      }      



      
      private static $_instance;    
      private function __construct() {}             
      public static function getInstance() 
      {
            if(!isset(self::$_instance)) {
                  self::$_instance = new StripeLiason;
                  Stripe::setApiKey(Yii::app()->params['stripekeys']['secret_key']);
            }
            return self::$_instance;
      }

      
      
}
