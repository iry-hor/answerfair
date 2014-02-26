<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="language" content="en" />
      
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/code/css/qtip_css/jquery.qtip.min.css" />  

      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/code/css/blueprint_css/print.css" media="print" />
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/code/css/blueprint_css/screen.css" media="screen, projection" />

      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/code/css/yii_css/main.css" />
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/code/css/yii_css/form.css" />
	
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/code/css/custom.css" />
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/code/css/layout.css" />
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/code/css/color.css" />      
   
      <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />            
         
      <title><?php echo CHtml::encode($this->pageTitle); ?></title>
      
</head>
<body>
      <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
      <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
      <script src="http://malsup.github.io/jquery.blockUI.js"></script>
      <script src="https://js.stripe.com/v2/"></script>     
      <script src="https://checkout.stripe.com/v2/checkout.js"></script>
	  
      <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.qtip.min.js"></script>      
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
      

<!-- Site Header -->
      <div id='site_header' class='white_smoke'>
            <div class='sizing_container'>
                  <span class='top_nav float-left'>
                        <?php echo CHtml::link($this->text(__FILE__,'Home'), Yii::app()->homeUrl, array('class'=>'top_link')); ?>                 
                  </span>          
                  
                  <?php $form=$this->beginWidget('CActiveForm', 
						array('action'=>array('myisamQuestion/index'),
						'method'=>'GET',
						'htmlOptions'=>array('class'=>'float-left'))); ?>                          
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
      
<!-- Body -->

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


<!-- Footer -->
      <div class='site_footer'>
            <div class='sizing_container footer'>
                  <div class=''>
                        <div class='text-deemphasize'><?php echo $this->text(__FILE__,'WorldsFair Software © ') . date("Y"); ?></div>
                  </div>
            </div>
      </div>
      
<!-- Account Popup -->
  	
      <div id='account_popup_wrapper'>
            <?php $this->getAccountPopup(); ?>
      </div>
      
      <div id='login_blocker' style='display:none'>
            <?php $this->renderPartial('//site/login',array('model'=> new LoginForm)); ?>
      </div>
      
      
<!-- QTip Tooltips -->      
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

</body>
</html>
