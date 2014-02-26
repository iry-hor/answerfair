<?php

/**
 * This is the model class for table "tbl_question_access".
 *
 * The followings are the available columns in table 'tbl_question_access':
 * @property string $question_id
 * @property string $user_id
 * @property string $access_time
 */
class QuestionAccess extends CActiveRecord
{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'tbl_question_access';
	}

      
      public function getAccessTime()
      {
            return DataFormatter::getInstance()->formatDate($this->access_time, true);
      }
      
      
      public function behaviors() 
      {
            return array('CTimestampBehavior' => array(
                  'class' => 'zii.behaviors.CTimestampBehavior',
                  'createAttribute' => 'access_time',
                  'updateAttribute' => NULL,
                  'setUpdateOnCreate' => TRUE,
            ));
      }
}