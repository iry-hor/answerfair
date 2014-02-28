<?php

function _joinpath($dir1, $dir2) {
    return realpath($dir1 . '/' . $dir2);
}
 
$homePath      = dirname(__FILE__) . '/../../..';
$protectedPath = _joinpath($homePath, 'code/php');
$runtimePath   = _joinpath($homePath, 'runtime');

return array(
      
	'basePath'    => $protectedPath,
	'runtimePath' => $runtimePath,
	
      'name'=>'Answer Fair',
      
      'language'=>'en',
      // preloading 'log' component
      'preload'=>array('log'),

      // autoloading model and component classes
      'import'=>array(
            'application.models.*',
            'application.models.demo.*' ,    			
            'application.models.forms.*' ,    
            'application.components.*',
            'application.controllers.*',
            'application.controllers.demo.*',
            'application.extensions.phpass.*',
            'application.extensions.stripe.lib.*', 
                          
      ),

      'modules'=>array(
            // uncomment the following to enable the Gii tool
            'gii'=>array(
                  'class'=>'system.gii.GiiModule',
                  'password'=>false,
                  // If removed, Gii defaults to localhost only. Edit carefully to taste.
                  'ipFilters'=>array('127.0.0.1','::1'),
            ),
            
      ),

      // application components
      'components'=>array(
            'user'=>array(
                  // enable cookie-based authentication
                  'allowAutoLogin'=>true,
            ),
            'request'=>array(
                  'enableCsrfValidation'=>true,
                  'enableCookieValidation'=>true,                  
            ),
            'messages' => array(
                  'class' => 'CDbMessageSource',
                  'cachingDuration'=>10,
            ),
         
            'db'=>array(
                  'connectionString' => 'mysql:host=127.0.0.1;dbname=pennyturk',
                  'username' => 'penny',
                  'password' => 'penny',
                  'charset' => 'utf8',
                  'emulatePrepare'=>true,
            ),
            'errorHandler'=>array(
                  'errorAction'=>'site/error',
            ),
            'log'=>array(
                  'class'=>'CLogRouter',
                  'routes'=>array(
                        array(
                              'class'=>'CFileLogRoute',
                              'levels'=>'error, warning',
                        ),
                        // uncomment the following to show log messages on web pages
                        
                        array(
                              'class'=>'CWebLogRoute',
                        ),
                        
                        
                  ),
            ),
      ),

      // application-level parameters that can be accessed
      // using Yii::app()->params['paramName']
      'params'=>array(
            // this is used in contact page
            'adminEmail'=>'webmaster@example.com',
            'stripekeys'=>array(
              "secret_key"      => "sk_test_6dvLCjB49u4oNpOLNOJ6UCi6",
              "publishable_key" => "pk_test_CiaiMm6Jg19JgCBnmow3VKC4"
            ),
            'initial_credits' => 5,
      ),
);