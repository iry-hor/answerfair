<?php

/**
 * This is the model class for table "tbl_question".
 *
 * The followings are the available columns in table 'tbl_question':
 * @property string $question_id
 * @property string $question_text
 * @property integer $answer_count
 * @property string $capitalization
 * @property string $last_update_time
 * @property string $entry_time 
 * 
 * The followings are the available model relations:
 * @property Answer[] $answers
 * @property Award[] $awards
 * @property User[] $users
 * @property User[] $watchers
 *  
 * */
class Question extends CActiveRecord
{
      
      // This email property is here to faciliate unregistered users entering questions.  
      // we could do a dedicated QuestionEntryForm class, but right now this does
      // the trick and is a lot less work for small hit in object design integrity.
      public $email;
                
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'tbl_question';
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
                  'TrimAndRemoveQuestionMarksBehavior' => array(
                        'class'=>'application.behaviors.TrimAndRemoveQuestionMarksBehavior'
                        )
            );            
            
      }
      
      
      // @todo look at error handling here....
      public function onAfterSave() 
      {
            $model = new QuestionWatch;
            $model->question_id = $this->question_id;
            if(Yii::app()->user->isGuest) {
                  $user = new User;
                  $user->email = $this->email;
                  $user->save();
                  
                  $model->user_id = $user->user_id;
            }
            else 
                  $model->user_id = Yii::app()->user->id;
            $model->save();      
      }

	public function rules()
	{
		return array(
			array('question_text, email', 'required'),
			array('question_text', 'length', 'max'=>200),
			array('question_text', 'safe', 'on'=>'search'),
                  array('question_text, email', 'safe'),			
			array('email', 'email'),
			
		);
	}


	public function relations()
	{
		return array(
			'answers' => array(self::HAS_MANY, 'Answer', 'question_id'),
			'awards' => array(self::HAS_MANY, 'Award', 'question_id'),
			'users' => array(self::MANY_MANY, 'User', 'tbl_question_access(question_id, user_id)'),
                  'watchers' => array(self::MANY_MANY, 'User', 'tbl_question_watch(question_id, user_id)'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'question_id' => 'Question',
			'question_text' => 'Question Text',
			'answer_count' => 'Answer Count',
			'capitalization' => 'Capitalization',
			'last_update_time' => 'Last Update Time',
                  'last_update_time' => 'Entry Time',			
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
      
}