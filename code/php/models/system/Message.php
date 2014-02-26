<?php

/**
 * This is the model class for table "Message".
 *
 * The followings are the available columns in table 'Message':
 * @property string $id
 * @property string $language
 * @property string $translation
 *
 * The followings are the available model relations:
 * @property SourceMessage $sourceMessage
 */
class Message extends CActiveRecord
{

      public static function model($className=__CLASS__)
      {
            return parent::model($className);
      }

      public function tableName()
      {
            return 'Message';
      }

      public function relations()
      {
            return array(
                  '$sourceMessage' => array(self::BELONGS_TO, 'SourceMessage', 'id'),
            );
      }

      public function rules()
      {
            return array(
                  array('message', 'safe'), 
            );
      }
      
      public static function getMessage($category, $messageText) 
      {
            $sourceMessage = SourceMessage::model()->findByAttributes(array('category'=>$category, 'message'=>$messageText));      
            $id = $sourceMessage->id;
            
            //@ todo generalize past 'en' only as the lang
            $message = Message::model()->findByPk(array('id'=>$id, 'language'=>'en'));
            // not sure the above line is necessary, I think we might be always able to 
            // do "new" even if there's a row on the table.
            if(empty($message)) {
                  $message = new Message;
                  $message->id=$id;
                  $message->langauge = 'en';
            }
            return $message;
      }      
       
}