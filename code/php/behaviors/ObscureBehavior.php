<?php 
class ObscureBehavior extends CActiveRecordBehavior 
{
      public function beforeSave($event) 
      {
            $this->getOwner()->answer_text_cipher = $this->obscure($this->getOwner()->answer_text);
            $this->getOwner()->in_short_cipher = $this->obscure($this->getOwner()->in_short);            
      }     
         
      private function obscure($plainText) 
      {
            $maxIndex = count($this->cipherDict) - 1;
            
            $cipherWords = array();
            $plainWords = explode(' ',$plainText);
            foreach($plainWords as $plainWord) {
                  array_push($cipherWords, $this->cipherDict[ min(strlen($plainWord), $maxIndex) ]);
            }
            return implode(' ', $cipherWords);
      }  
      
      private $cipherDict = array(
            'p',
            're',
            'tty',
            'weak',
            'ciphe',
            'rwould',
            'ntyousa',
            'yitsjust',
            'alenghtba',
            'seddictloo',
            'kupdoyoukno',
            'whatdictsize',
            'isemailmeifyo',
            'figureitoutttys'            
      );
} 