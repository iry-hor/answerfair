<?php 


class ProjectImage 
{
	public static function image($name, $htmlOptions) 
	{
		echo CHtml::image(Yii::app()->baseUrl.'/resources/images/'.$name, '', $htmlOptions);	
	}
}