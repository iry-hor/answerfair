<?php

class UserLedgerController extends Controller
{
	
	public static function getActiveRecord($userId) 
	{
		$model = UserLedger::model()->findByAttributes(array(
				'user_id'=>$userId,
				'active'=>TRUE));	
                        
            if(empty($model))
                  throw new Exception('Unable to pull active user ledger record');	
            
            return $model;
	}
      
      // @todo To be used when we're upddating the ledger.  prevents race conditions, etc. 
      // requires a lock field to be added and possibly a process id?  
      public static function acquireActiveRecord($userId) 
      {                 	            
      }
         
}

?>