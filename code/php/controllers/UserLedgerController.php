<?php

class UserLedgerController extends Controller
{
	
	public static function getActiveRecord($userId) 
	{
		return UserLedger::model()->findByAttributes(array(
				'user_id'=>$userId,
				'active'=>TRUE));		
	}
}

?>