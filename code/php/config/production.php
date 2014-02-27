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
                  'cachingDuration'=>3600,
            ),

            'db'=>array(
                  'connectionString' => 'mysql:host=127.0.0.1;dbname=pennyturk',
                  'username' => 'penny',
                  'password' => 'penny4488s',
                  'charset' => 'utf8',
                  'emulatePrepare'=>true,
                  'schemaCachingDuration'=>3600,
            ),
		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning'
			      ),
			),
		),
	),

	'params'=>array(
	     //@todo configure mail extensions
		'adminEmail'=>'webmaster@answerfair.com',
            'stripekeys'=>array(
              "secret_key"      => "sk_live_v3Ic8GU6vjNQiYftu9Kg5jt7",
              "publishable_key" => "pk_live_V0l0aqD4oySs5RwiYsrbeUqf"
            ),
            'initial_credits' => 5,            
	),
);

