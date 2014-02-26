<?php

class DemoData 
{

      private $demoQuestionId = -100;
      private $demoAwardId = -400;
      
      public static $demoSessionUserId = -200;
      
      public function getQuestionOne() 
      {
           $question = new Question;
           $question->question_id = $this->demoQuestionId;
           $question->question_text = 'What type of legal entity should I choose for my new business';
           $question->answer_count = 5;
           $question->capitalization = '$185.00';
           $question->last_update_time = '2013-09-18 12:20:00';
           $question->entry_time = '20013-09-18 12:20:00';                                                   
           return $question;
      }

      public function getQuestionTwo() 
      {
          $question = new Question;
          $question->question_id = $this->demoQuestionId;
          $question->question_text = 'How can I remove screws with stripped heads';
          $question->answer_count = 5;
          $question->capitalization = '$114.00';
          $question->last_update_time = '2013-04-16 12:20:00';
          $question->entry_time = '20012-12-12 12:20:00';    		  
                                         
          return $question;
      }
            
   
      public function makeUserAward($answerId) 
      {
            $award = new Award;
            $award->answer_id = $answerId;
            $award->awarding_user_id = self::$demoSessionUserId;
            $award->question_id = $this->demoQuestionId;            
            $award->award_amount = Pricing::AWARD_AMOUNT;
            
            return $award;
           
      }
      
      public function makeQuestionAccess() 
      {
            $now = new DateTime;
            $access = new QuestionAccess;
            $access->question_id = $this->demoQuestionId;     
            $access->user_id = self::$demoSessionUserId;
            $access->access_time = $now->format('Y-m-d H:i:s');
            return $access;
      }
      
      public function getDemoLedgerRow() 
      {
            $userLedger = new UserLedger;
            $userLedger->ending_balance = 130.00;
            
            return $userLedger;
      }
  
               
      private function __construct() {}             
      public static function instance() 
      {
            static $inst = null;
            if($inst === null) {
                  $inst = new DemoData();
            }
            return $inst;
      }    
       
}

?>
