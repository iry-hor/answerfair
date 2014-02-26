<?php

/**
 * This is the model class for table "tbl_myisam_question".
 *
 * The followings are the available columns in table 'tbl_myisam_question':
 * @property string $question_id
 * @property string $question_text
 * @property integer $answer_count
 * @property string $capitalization
 * @property string $last_update_time
 */
class MyisamQuestion extends CActiveRecord
{
      
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'tbl_myisam_question';
	}

	public function rules() { return array(); }
	public function relations() { return array(); }

	public function attributeLabels()
	{
		return array(
			'question_id' => 'Question',
			'question_text' => 'Question Text',
			'answer_count' => 'Answer Count',
			'capitalization' => 'Capitalization',
			'last_update_time' => 'Last Update Time',
		);
	}

      public function getLastUpdateTime() 
      {
            return DataFormatter::getInstance()->formatDate($this->last_update_time);
      } 
      
      public function getCapitalization() 
      {
            return DataFormatter::getInstance()->formatMoney($this->capitalization);      
      }         
     

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('question_id',$this->question_id,true);
		$criteria->compare('question_text',$this->question_text,true);
		$criteria->compare('answer_count',$this->answer_count);
		$criteria->compare('capitalization',$this->capitalization,true);
		$criteria->compare('last_update_time',$this->last_update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}