<?php

/**
 * This is the model class for table "tbl_answer".
 *
 * The followings are the available columns in table 'tbl_answer':
 * @property string $answer_id
 * @property string $question_id
 * @property string $user_id
 * @property string $entry_time
 * @property string $answer_text
 * @property string $answer_text_cipher
 * @property string $in_short
 * @property string $in_short_cipher
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Question $question
 * @property Award[] $awards
 */
class Answer extends CActiveRecord
{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return 'tbl_answer';
	}


	public function rules()
	{
		return array(
			array('in_short', 'length', 'max'=>100),
                  //@todo - what should we restrict answer_text to?
                  array('answer_text', 'length', 'max'=>10000),			
			array('answer_text, in_short', 'safe'), 
		);
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'question' => array(self::BELONGS_TO, 'Question', 'question_id'),
			'awards' => array(self::HAS_MANY, 'Award', 'answer_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'answer_id' => 'Answer',
			'question_id' => 'Question',
			'user_id' => 'User',
			'entry_time' => 'Entry Time',
			'answer_text' => 'Answer Text',
			'answer_text_cipher' => 'Answer Text Cipher',
			'in_short' => 'In Short',
			'in_short_cipher' => 'In Short Cipher',
		);
	}


	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('question_id',$this->question_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('entry_time',$this->entry_time,true);
		$criteria->compare('answer_text',$this->answer_text,true);
		$criteria->compare('in_short',$this->in_short,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}


	public function behaviors()
	{
	     return array(
	           'CTimestampBehavior' => array(
                        'class' => 'zii.behaviors.CTimestampBehavior',
                        'createAttribute' => 'entry_time',
                        'updateAttribute' => NULL,
                        'setUpdateOnCreate' => true,
				),
                  'ObscureBehavior' => array(
                        'class'=>'application.behaviors.ObscureBehavior')
		);
	}
		
	public function getTotalAwards()
	{
	     //@todo Just a note that this assumes Partial or Split Awards are not allowed.
	     $totalAwards = count($this->awards) * Pricing::AWARD_AMOUNT;
	     return DataFormatter::getInstance()->formatMoney($totalAwards);
	}
	
	
	public function getEntryTime()
	{
	      return DataFormatter::getInstance()->formatDate($this->entry_time, true);
	}
	

    public function notUsersAnswer() 
    {
          if(Yii::app()->user->isGuest)
                return true;
          return $this->user_id != Yii::app()->user->id;            
    }
	
}