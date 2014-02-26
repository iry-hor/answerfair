<?php

/**
 * This is the model class for table "tbl_user_ledger".
 *
 * The followings are the available columns in table 'tbl_user_ledger':
 * @property string $id
 * @property string $parent_id
 * @property string $user_id
 * @property string $transaction_time
 * @property integer $beginning_balance
 * @property integer $beginning_credits
 * @property integer $transaction_type
 * @property integer $transaction_amount
 * @property integer $ending_balance
 * @property integer $ending_credits
 * @property integer $active
 *
 * The followings are the available model relations:
 * @property User $user
 */
class UserLedger extends CActiveRecord
{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
      

	public function tableName()
	{
		return 'tbl_user_ledger';
	}


	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}


	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'transaction_time' => 'Transaction Time',
			'beginning_balance' => 'Beginning Balance',
			'beginning_credits' => 'Beginning Credits',
			'transaction_type' => 'Transaction Type',
			'transaction_description' => 'Transaction Description',
			'transaction_amount' => 'Transaction Amount',
			'ending_balance' => 'Ending Balance',
			'ending_credits' => 'Ending Credits',
			'active' => 'Active',
			'ending' => 'Balance',
			'credit' => '+',
			'debit' => '-',
		);
	}
      

	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('active',$this->active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	public function recordTransaction($type, $amount, $reference, $credits = 0)
	{
		$newRow = new UserLedger();
		$newRow->user_id = $this->user_id;
            $newRow->parent_id = $this->id;
		$newRow->transaction_type = $type;
		$newRow->beginning_balance = $this->ending_balance;
		$newRow->beginning_credits = $this->ending_credits;
		$newRow->transaction_amount = $amount;
		$newRow->ending_balance = $newRow->beginning_balance + $amount;
            $newRow->ending_credits = $newRow->beginning_credits + $credits;
            
            if($newRow->ending_balance < 0) {
                  throw new InsufficientFundsException('Your account does not have a balance greater than your withdrawal request.');
            }
              
		$newRow->reference = $reference;
		$newRow->active = 1;
		$this->active = 0;
            
		return $newRow;	
	}
	
	public function recordCreditsFirstTransaction($type, $amount, $reference) 
	{		
		$credits = 0;
		$cash = 0;
		
		if($this->ending_credits > abs($amount)) {
			$credits = $amount;
		}
		else {
			$credits = $this->ending_credits;
                  $cash = $amount + $credits;            // a little counter-intuitive b/c $amount is signed negative
		}
		
		$newRow = new UserLedger();
            $newRow->parent_id = $this->id;            
		$newRow->user_id = $this->user_id;
		$newRow->transaction_type = $type;
		$newRow->beginning_balance = $this->ending_balance;
		$newRow->beginning_credits = $this->ending_credits;		
		$newRow->transaction_amount = $cash;
		$newRow->ending_balance = $newRow->beginning_balance + $newRow->transaction_amount;
		$newRow->ending_credits = $newRow->beginning_credits + $credits;
		$newRow->reference = $reference;
		$newRow->active = 1;
		
		$this->active = 0;
		return $newRow;		
	}
	

	
	public function getEndingSuperBalance() 
	{
		return $this->ending_balance + $this->ending_credits;	
	}
      
      
      public function getSuper() 
      {
            return DataFormatter::getInstance()->formatSuperBalance($this->getEndingSuperBalance());
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