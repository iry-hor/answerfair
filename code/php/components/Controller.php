<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
      
      
      public function init() 
      {
            parent::init();
            if($this->isMobileUserAgent())
                  Yii::app()->setTheme('mobile');
      }      
            
      public function isMobileUserAgent() {
            return strpos($_SERVER['HTTP_USER_AGENT'], 'Android') || 
                   strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone');
      }    



      public function getAccountPopup() 
      {
      	if(Yii::app()->user->isGuest) {
      		return;
      	}
            $model = User::model()->findByPk(Yii::app()->user->id);
            $this->renderPartial('//user/_popup',array(
                  'model'=>$model,
            ));
      }      
      
      public function text($view, $message) 
      {
            return Yii::t($this->makeCategoryFromViewFile($view),$message);            
      }
      
      public function echoText($view, $message) 
      {
            echo $this->text($view, $message);
      }
      
      private function makeCategoryFromViewFile($fullPath) 
      {
            $start = strrpos($fullPath, 'views') + strlen('views/');
            $length = (strlen($fullPath) - strlen('.php')) - $start;
            
            $viewPath = substr($fullPath, $start, $length);
            

            return $viewPath;      
      }
      

      
      public function registerStyleSheets() 
      {   
            $cssDir = dirname(__FILE__).'/../../css/';
            $cssDir = Yii::app()->getAssetManager()->publish($cssDir);
                        
            $cs = Yii::app()->getClientScript();
            $cs->registerCssFile($cssDir.'/custom.css');
            $cs->registerCssFile($cssDir.'/color.css');   
            $cs->registerCssFile($cssDir.'/layout.css');
            
            $cs->registerCssFile($cssDir.'/css/blueprint_css/screen.css');
            $cs->registerCssFile($cssDir.'/css/blueprint_css/print.css');    
            $cs->registerCssFile($cssDir.'/css/blueprint_css/ie.css');

            $cs->registerCssFile($cssDir.'/css/yii_css/main.css');
            $cs->registerCssFile($cssDir.'/css/yii_css/form.css');    
                   
            $cs->registerCssFile($cssDir.'/css/qtip_css/jquery.qtip.min.css');                
      }      
      
      public function registerJavascript() 
      {            
            $jsDir = dirname(__FILE__).'/../../js/';
            $jsDir = Yii::app()->getAssetManager()->publish($jsDir);
                        
            $cs = Yii::app()->getClientScript();
            $cs->registerScriptFile($jsDir.'/jquery.blockUI.js');
            $cs->registerScriptFile($jsDir.'/jquery.qtip.min.js');   
            $cs->registerScriptFile($jsDir.'/markdown.js'); 
            
            $cs->registerCoreScript('jquery');
      }
      
      
      
      
      public function showLogin() 
      {
      	if(!isset($_REQUEST['r']))
      		return true;
            
            return !($_REQUEST['r'] == 'site/login' || $_REQUEST['r'] == 'user/create');
      }
          
      // Used so that transactions can be strucutred as try/catch blocks.  Basically
      // makes save errors "noisy".   
      public function saveModel($model) 
      {
            $success = $model->save();
            if(!$success) {
                  throw new CException($this->errorsToString($model));
            }
      }
      
      // called by saveModel, this could probably be improved.
      function errorsToString($model) {
            $errors = '';
            foreach ($model->getErrors() as $attrname => $errlist){
                  foreach ($errlist as $err) {
                        $errors .= "    $err\n";
                  }
            }
            return $errors;
      }
      
}