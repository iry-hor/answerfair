<?php

class QuestionWatchController extends Controller
{
      public function filters()
      {
            return array(
                  'accessControl', // perform access control for CRUD operations
                  'postOnly + create', // we only allow deletion via POST request
            );
      }

      public function accessRules()
      {
            
            return array(
                  array('allow',
                        'actions'=>array('create','confirm'),
                        'users'=>array('@'),
                  ),
                  array('deny',  // deny all users
                        'users'=>array('*'),
                  ),
            );
      }      
      
      
      public function actionCreate()
      {
            $success = false;
            $model = new QuestionWatch;
            if(isset($_POST['QuestionWatch'])) {
                  $model->attributes=$_POST['QuestionWatch'];
                  if($model->save())
                        $success = true;
            }
            
            // JSON encode? 
            echo $success;
      }

      public function actionDelete()
      {
            $success = false;
            $model = new QuestionWatch;
            if(isset($_POST['QuestionWatch'])) {
                  $model->attributes=$_POST['QuestionWatch'];
                  if($model->delete())
                        $success = true;
            }
            // JSON encode?
            echo $success;
      }

}

