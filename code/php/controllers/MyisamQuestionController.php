<?php

class MyisamquestionController extends Controller
{
      public function filters()
      {
      }

      public function accessRules()
      {
            
            return array(
                  array('allow', 
                        'actions'=>array('index'),
                        'users'=>array('*'),
                  ),
                  array('deny',
                        'users'=>array('*'),
                  ),
            );
      }   
      
	  
	  // @todo review this for SQL Injection protection 
      public function actionIndex() 
      {
            if(!isset($_GET['q']))
                  return;

            $search = $_GET['q'];    
            $matchExpression = "MATCH(question_text) AGAINST (:q)";

            $dataProvider = new CActiveDataProvider('MyisamQuestion',array(
                'criteria'=>array(
                    'having'=>"{$matchExpression} > .2",
                    'order'=>$matchExpression,
                    'params'=>array(':q'=>Yii::app()->db->quoteValue($search)),
                 ),

                'pagination'=>array(
                    'pageSize'=>20
                 ),
            ));
           
           $model = new Question;
           $model->question_text = $search; 
            
           $this->render('index',array(
                  'dataProvider'=>$dataProvider,
                  'searchText'=>$search,
                  'model'=>$model,
                  'external'=>FALSE,
            ));           
           
      }      
      
      
}
