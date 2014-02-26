<?php

/**
 * This is the model class for table "tbl_question_watch".
 *
 * The followings are the available columns in table 'tbl_question_watch':
 * @property string $question_id
 * @property integer $user_id
 */
class QuestionWatch extends CActiveRecord
{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return 'tbl_question_watch';
	}


	public function rules()
	{
		return array(
			array('question_id, user_id', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('question_id', 'length', 'max'=>11),
			array('question_id, user_id', 'safe'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('question_id',$this->question_id,true);
		$criteria->compare('user_id',$this->user_id);
		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}