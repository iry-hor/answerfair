<?php

/**
 * This is the model class for table "tbl_demo_answer".
 *
 * The followings are the available columns in table 'tbl_demo_answer':
 * @property string $answer_id
 * @property string $question_id
 * @property string $user_name
 * @property string $entry_time
 * @property string $answer_text
 * @property string $in_short
 * @property string $award_total
 *
 */
class DemoAnswer extends CActiveRecord
{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return 'tbl_demo_answer';
	}


	public function attributeLabels()
	{
		return array(
			'answer_id' => 'Answer',
			'question_id' => 'Question',
			'user_name' => 'User',
			'entry_time' => 'Entry Time',
			'answer_text' => 'Answer Text',
			'in_short' => 'In Short',
			'award_total' => 'Award Total'
		);
	}
}