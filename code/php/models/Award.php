<?php

/**
 * This is the model class for table "tbl_award".
 *
 * The followings are the available columns in table 'tbl_award':
 * @property string $award_id
 * @property string $awarding_user_id
 * @property string $question_id
 * @property string $answer_id
 * @property integer $award_amount
 * @property string $rating
 * 
 *
 * The followings are the available model relations:
 * @property User $awardingUser
 * @property Question $question
 * @property Answer $answer
 */
class Award extends CActiveRecord
{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'tbl_award';
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'awardingUser' => array(self::BELONGS_TO, 'User', 'awarding_user_id'),
			'question' => array(self::BELONGS_TO, 'Question', 'question_id'),
			'answer' => array(self::BELONGS_TO, 'Answer', 'answer_id'),
		);
	}

      public function rules()
      {
            return array(
                  array('answer_id, question_id, rating', 'safe'), 
            );
      }      

	public function attributeLabels()
	{
		return array(
			'award_id' => 'Award',
			'awarding_user_id' => 'Awarding User',
			'question_id' => 'Question',
			'answer_id' => 'Answer',
			'award_amount' => 'Award Amount',
			'award_status' => 'Award Status',
			'rating' => 'Grade'
		);
	}

	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$criteria=new CDbCriteria;
		$criteria->compare('awarding_user_id',$this->awarding_user_id,true);
		$criteria->compare('question_id',$this->question_id,true);
		$criteria->compare('answer_id',$this->answer_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}