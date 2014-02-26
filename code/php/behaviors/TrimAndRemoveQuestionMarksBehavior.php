<?php 
class TrimAndRemoveQuestionMarksBehavior extends CActiveRecordBehavior 
{
      public function beforeSave($event) 
      {
            $rawText = $this->getOwner()->question_text;
            $this->getOwner()->question_text = str_replace('?','',trim($rawText));    
      }  
      

} 

