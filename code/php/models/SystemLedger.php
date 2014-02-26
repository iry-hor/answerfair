<?php

/**
 * This is the model class for table "tbl_system_ledger".
 *
 * The followings are the available columns in table 'tbl_system_ledger':
 * @property string $id
 * @property string $parent_id 
 * @property string $transaction_time
 * @property integer $beginning_balance
 * @property string $transaction_type
 * @property integer $transaction_amount
 * @property integer $ending_balance
 * @property integer $active
 * @property string $reference
 */
class SystemLedger extends CActiveRecord
{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
      
      public $persist = true;
      
	public function tableName()
	{
		return 'tbl_system_ledger';
	}
	
	
	public function behaviors()
	{
	     return array(
		    'CTimestampBehavior' => array(
		          'class' => 'zii.behaviors.CTimestampBehavior',
		          'createAttribute' => 'transaction_time',
			    'updateAttribute' => NULL,
			    'setUpdateOnCreate' => true,
		    ),
		);
	}
	

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'transaction_time' => 'Transaction Time',
			'beginning_balance' => 'Beginning Balance',
			'transaction_type' => 'Transaction Type',
			'transaction_amount' => 'Transaction Amount',
			'ending_balance' => 'Ending Balance',
			'active' => 'Active',
			'reference' => 'Reference',
                  'active' => 'Active',
                  'ending' => 'Balance',
                  'credit' => '+',
                  'debit' => '-',			
		);
	}

      
	
	public function recordTransaction($type, $amount, $reference)
	{
		$newRow = new SystemLedger;
            $newRow->parent_id = $this->id;
		$newRow->beginning_balance = $this->ending_balance;
		$newRow->transaction_type = $type;
		$newRow->transaction_amount = (-1 * $amount);
		$newRow->ending_balance = $newRow->beginning_balance + $newRow->transaction_amount;
		$newRow->reference = $reference;
		$newRow->active = 1;
	
		$this->active = 0;
		return $newRow;
	}
      
      public function getDate() 
      {
            return DataFormatter::getInstance()->formatDateOnly($this->transaction_time);      
      }
      
      public function getEnding() 
      {
           return DataFormatter::getInstance()->formatMoney($this->ending_balance);  
      }
      
      public function getCredit() 
      {
            return ($this->transaction_amount > 0) ? DataFormatter::getInstance()->formatMoney($this->transaction_amount) : '';   
      }
      
      public function getDebit() 
      {
            return ($this->transaction_amount < 0) ? DataFormatter::getInstance()->formatMoney(abs($this->transaction_amount)) : '';               
      }	
      
      public $typeDescriptions = array(
            LedgerTransactions::ACCESS_QUESTION => 'Access',
            LedgerTransactions::SUBMIT_ANSWER => 'Answer',            
            LedgerTransactions::MAKE_AWARD => 'Award',
            LedgerTransactions::DEPOSIT => 'Deposit', 
            LedgerTransactions::WITHDRAWAL => 'Withdraw',
            LedgerTransactions::INITIALIZATION => 'Account Setup', 
      );
      
      public function getType() 
      {
            return $this->typeDescriptions[$this->transaction_type];            
      }
      	
      public function getRef() 
      {
            $desc = '';        
      
            if($this->transaction_type == LedgerTransactions::ACCESS_QUESTION) {   
                  $question = Question::model()->findByPk($this->reference);
                  $desc = $question->question_text;
            }
            else if($this->transaction_type == LedgerTransactions::SUBMIT_ANSWER) {
                  $answer = Answer::model()->findByPk($this->reference);
                  $desc = $answer->question->question_text;                  
            }
            else if($this->transaction_type == LedgerTransactions::MAKE_AWARD) {   
                  $award = Award::model()->findByPk($this->reference);
                  $desc = $award->question->question_text;
            }     
            else {
                  $desc = $this->reference;                     
            }
             
            return $desc;
      }      
      	
}