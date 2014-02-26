<?php

class SiteController extends Controller
{

      public function actions()
      {
            return array(
                  // captcha action renders the CAPTCHA image displayed on the contact page
                  'captcha'=>array(
                        'class'=>'CCaptchaAction',
                        'backColor'=>0xFFFFFF,
                  ),
                  // page action renders "static" pages stored under 'protected/views/site/pages'
                  // They can be accessed via: index.php?r=site/page&view=FileName
                  'page'=>array(
                        'class'=>'CViewAction',
                  ),
            );
      }

      public function actionIndex()
      {           
            if(Yii::app()->user->isGuest)  {
                  $model = new NewUser;                  
                  $this->render('landing', array('model'=>$model));                                    
            }           
            else {
                  $this->render('index');                                       
                  //$this->render(Yii::app()->user->getState('admin') ? 'admin' : 'index');   
            }    
      }
      
      public function actionLandingAnswers() 
      {
            $this->render('landing_answers');            
      }
      


      public function actionError()
      {           
            $errorViewPage = 'error';
            if(!Yii::app()->user->isGuest && Yii::app()->user->getState('admin', FALSE)) {
                  $errorViewPage = 'debug';
            }
            
            if($error=Yii::app()->errorHandler->error)
            {
                  if(Yii::app()->request->isAjaxRequest) {
                        echo $error['message'];
                  }      
                  else {
                        $this->render($errorViewPage, $error);          
                  }
            }
      }

      public function actionLogin()
      {
            $model = new LoginForm;
			$model->rememberMe = true;
            if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
            {
                  echo CActiveForm::validate($model);
                  Yii::app()->end();
            }

            // collect user input data
            if(isset($_POST['LoginForm']))
            {
                  $model->attributes=$_POST['LoginForm'];
                  // validate user input and redirect to the previous page if valid
                  if($model->validate() && $model->login())
                        $this->redirect(Yii::app()->user->returnUrl);
            }
            // display the login form
            $this->render('login',array('model'=>$model));
      }
      
      


      public function actionLogout()
      {
            Yii::app()->user->logout();
            $this->redirect(Yii::app()->homeUrl);
      }
         
}

