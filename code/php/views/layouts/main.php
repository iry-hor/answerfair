<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="language" content="en" />
      
      <!-- blueprint CSS framework -->
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.qtip.min.css" />  
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/custom.css" />
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/layout.css" />
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/color.css" />      
   
      <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />            
     
      <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />         
      
      <link rel="apple-touch-icon" sizes="57x57" href="apple-icon-57x57.png" />
      <link rel="apple-touch-icon" sizes="72x72" href="apple-icon-72x72.png" />
      <link rel="apple-touch-icon" sizes="114x114" href="apple-icon-114x114.png" />
      <link rel="apple-touch-icon" sizes="144x144" href="apple-icon-144x144.png" />           
      
      
      <title><?php echo CHtml::encode($this->pageTitle); ?></title>
      
</head>
<body>
      <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
      <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
      <script src="http://malsup.github.io/jquery.blockUI.js"></script>
      <script type="text/javascript" src="https://js.stripe.com/v2/"></script>     
      <script src="https://checkout.stripe.com/v2/checkout.js"></script>
      <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.qtip.min.js"></script>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/ckeditor/ckeditor.js"></script>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/ckeditor/config.js"></script> 	     
      
      <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/markdown.js"></script>    
      
      
      <script>
      $(document).ready(function(){
                         
            $("#account_button").click(function(){
                  var pos = $(this).offset();
                  // @TODO not good behavior when window is resized - possible solution would be to hide on lost focus
                  $("#account_popup_wrapper").css( { "left": pos.left-180 + "px", "top":pos.top+30 + "px" } );                      
                  $('#account_popup_wrapper').toggle();              
            });
    
      });    
      </script>
      

<!-- Site Header ------------------------------------------------------>
      <div id='site_header' class='white_smoke'>
            <div class='sizing_container'>
                  <span class='top_nav float-left'>
                        <?php echo CHtml::link($this->text(__FILE__,'Home'), Yii::app()->homeUrl, array('class'=>'top_link')); ?>                 
                  </span>          
                  
                  <?php $form=$this->beginWidget('CActiveForm', array('action'=>array('myisamQuestion/index'),'method'=>'GET','htmlOptions'=>array('class'=>'float-left'))); ?>                          
                        <input type='text' class='search_field' name='q' placeholder='<?php $this->echoText(__FILE__,'Search for Your Question'); ?>'/>            
                  <?php $this->endWidget(); ?>     
                  
                  
                  <span class='top_nav float-left'>
                        <?php echo CHtml::link($this->text(__FILE__,'About'), array('site/page','view'=>'about'), array('class'=>'top_link')); ?> .                    
                        <?php echo CHtml::link($this->text(__FILE__,'Contact'), array('site/page','view'=>'contact'), array('class'=>'top_link')); ?>              
                  </span>


                  <?php 
                  if($this->showLogin()) {  
                        if(Yii::app()->user->isGuest) {
                              echo CHtml::openTag('span',array('class'=>'top_nav float-right')); 
                              echo CHtml::link($this->text(__FILE__,'Login'), array('site/login'), array('class'=>'top_link'));
                              echo CHtml::closeTag('span');
                        } else { 
                              echo CHtml::tag('span', array('class'=>'unselectable button grey float-right','id'=>'account_button'), $this->text(__FILE__, 'Account')); 
                        }
                  } ?>        
            </div>
      </div>
      
      <div class='breadcrumb-bar blue_purple'> 
            <div class='sizing_container'></div>
      </div>
      
<!-- Body ------------------------------------------------------------->

      <div class='site_body'>
            <div class='sizing_container'>
                  <?php if(Yii::app()->user->hasFlash('notice')):?>
                      <div class="flash flash_notice">
                          <?php echo Yii::app()->user->getFlash('notice'); ?>
                      </div>
                  <?php endif; ?>
                  
                  <?php if(Yii::app()->user->hasFlash('error')):?>
                      <div class="flash flash_error">
                          <?php echo Yii::app()->user->getFlash('error'); ?>
                      </div>
                  <?php endif; ?>
                  <?php if(Yii::app()->user->hasFlash('success')):?>
                      <div class="flash flash_success">
                          <?php echo Yii::app()->user->getFlash('success'); ?>
                      </div>
                  <?php endif; ?>                  
                  
                  <?php echo $content; ?>
            </div>
            <div class="clear"></div>
      </div>


<!-- Footer ----------------------------------------------------------->
      <div class='site_footer'>
            <div class='sizing_container footer'>
                  <div class=''>
                        <div class='text-deemphasize'><?php echo $this->text(__FILE__,'WorldsFair Software © ') . date("Y"); ?></div>
                  </div>
            </div>
      </div>
      
<!-- Account Popup ---------------------------------------------------->
  	
      <div id='account_popup_wrapper'>
            <?php $this->getAccountPopup(); ?>
      </div>
      
      <div id='login_blocker' style='display:none'>
            <?php $this->renderPartial('//site/login',array('model'=> new LoginForm)); ?>
      </div>
      
      
      
<script>
	$('[title!=""]').qtip({
   		position:  {
   			my:  'top center',
			at:  'bottom center',
   		},
		style: { 
			classes: 'custom-tooltip'
		}
	});
	
	
</script>  


<script>
// Include the UserVoice JavaScript SDK (only needed once on a page)
UserVoice=window.UserVoice||[];(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/M489jsF0mVtEnDrk7TkkDQ.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})();

//
// UserVoice Javascript SDK developer documentation:
// https://www.uservoice.com/o/javascript-sdk
//

// Set colors
UserVoice.push(['set', {
  accent_color: '#448dd6',
  trigger_color: 'white',
  trigger_background_color: '#6aba2e'
}]);

// Identify the user and pass traits
// To enable, replace sample data with actual user traits and uncomment the line
UserVoice.push(['identify', {
  //email:      'john.doe@example.com', // User’s email address
  //name:       'John Doe', // User’s real name
  //created_at: 1364406966, // Unix timestamp for the date the user signed up
  //id:         123, // Optional: Unique id of the user (if set, this should not change)
  //type:       'Owner', // Optional: segment your users by type
  //account: {
  //  id:           123, // Optional: associate multiple users with a single account
  //  name:         'Acme, Co.', // Account name
  //  created_at:   1364406966, // Unix timestamp for the date the account was created
  //  monthly_rate: 9.99, // Decimal; monthly rate of the account
  //  ltv:          1495.00, // Decimal; lifetime value of the account
  //  plan:         'Enhanced' // Plan name for the account
  //}
}]);

// Add default trigger to the bottom-right corner of the window:
UserVoice.push(['addTrigger', { mode: 'contact', trigger_position: 'bottom-right' }]);

// Or, use your own custom trigger:
//UserVoice.push(['addTrigger', '#id', { mode: 'contact' }]);

// Autoprompt for Satisfaction and SmartVote (only displayed under certain conditions)
UserVoice.push(['autoprompt', {}]);
</script>

</body>
</html>
