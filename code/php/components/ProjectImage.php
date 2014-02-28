<?php 


class ProjectImage 
{
	public static function image($name, $htmlOptions) 
	{
            $imgFile = dirname(__FILE__).'/../../../resources/images/'.$name;
            $imgFile = Yii::app()->getAssetManager()->publish($imgFile);            
            
		echo CHtml::image($imgFile, '', $htmlOptions);	
	}
}