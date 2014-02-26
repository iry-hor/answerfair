<?php 

class DataFormatter 
{
      
      public function formatDateOnly($input) 
      {
            $dateObject = ($input instanceof DateTime) ? $input : DateTime::createFromFormat('Y-m-d H:i:s', $input);            
            return empty($dateObject) ? '' : $dateObject->format('M-d'); 
      }
      
      public function formatDate($input, $verbose = false) 
      {
            $dateObject = ($input instanceof DateTime) ? $input : DateTime::createFromFormat('Y-m-d H:i:s', $input);
            return empty($dateObject) ? '' : $this->formatRecency($dateObject, $verbose);
      }
      
      public function formatTimestamp($timestamp) 
      {
            return $this->formatDate(new DateTime('@'.$timestamp));
      }
      
      public function formatMoney($input)
      {
            return is_numeric($input) ? money_format('%n',$input) : $input;
      }
      
      public function formatSuperBalance($input)
      {
            return is_numeric($input) ? number_format($input , 2) : $input;
      }
            
      public function formatQuestion($input) 
      {
            return CHtml::encode($input) . '?';
      }
      
      private function formatRecency($dateObject, $verbose) 
      {
            $now = new DateTime() ;
            $interval = $now->getTimestamp() - $dateObject->getTimestamp();
            $formattedTime = "";
     
            $prefix = $verbose ? 'on ' : '';            
            $suffix = $verbose ? ' ago' : '';

            
            if($interval < 60) {  //seconds in a minute
                  $formattedTime = "1m" . $suffix;
            }
            else if($interval < 3600) { //seconds in a hour
                  $formattedTime = floor($interval/60) . "m" . $suffix;
            }
            else if($interval < 86400) { //seconds in a day
                  $formattedTime = floor($interval/3600) . "h" . $suffix;
            }  
            else if($interval < 604800) { // seconds in a week
                  $formattedTime = floor($interval/86400) . "d" . $suffix;
            } 
            else {
                  if ($dateObject->format('Y') == $now->format('Y')) { // same calendar year
                        $formattedTime = $prefix . $dateObject->format('M d');
                  }
                  else {
                        $formattedTime = $prefix . $dateObject->format('M Y');
                  }
                  
            }
            return $formattedTime;               
      }     




      // Singleton Pattern
      private static $_instance;      
      private function __construct() {}             
      public static function getInstance() 
      {
            if(!isset(self::$_instance))
                  self::$_instance = new DataFormatter;
            return self::$_instance;
      }   
}
?>
