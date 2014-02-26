<?php

/**
 * This is the model class for table "Message".
 *
 * The followings are the available columns in table 'SourceMessage':
 * @property string $id
 * @property string $category
 * @property string $message
 *
 * The followings are the available model relations:
 * @property Message[] $messages
 */
class SourceMessage extends CActiveRecord
{

      public static function model($className=__CLASS__)
      {
            return parent::model($className);
      }

      public function tableName()
      {
            return 'SourceMessage';
      }

      public function relations()
      {
            return array(
                  'messages' => array(self::HAS_MANY, 'Message', 'id'),
            );
      }       
}