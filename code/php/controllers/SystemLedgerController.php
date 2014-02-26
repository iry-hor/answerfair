<?php

class SystemLedgerController extends Controller
{

	public static function getActiveRecord() 
	{
	      $activeRecord = SystemLedger::model()->findByAttributes(array('active'=>TRUE));    
		return empty($activeRecord) ? self::initializeLedger() : $activeRecord;
	}
      
      private static function initializeLedger() 
      {
            $initial = new SystemLedger;
            $initial->ending_balance = 0;
            $initial->persist = false;
            return $initial;
      }
}