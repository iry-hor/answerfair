<?php
/* @var $this UserController */
/* @var $model WithdrawForm */
?>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script>


       Stripe.setPublishableKey('<?php echo Yii::app()->params['stripekeys']['publishable_key']; ?>');
       
       var stripeResponseHandler = function(status, response) {
            var $form = $('#withdrawForm');
 
            if (response.error) {
                  $form.find('#transferErrors').text(response.error.message).show();
            } else {
                  // token contains id, last4, and card type
                  var token = response.id;
                  // Insert the token into the form so it gets submitted to the server
                  $form.append($('<input type="hidden" name="WithdrawForm[stripeToken]" />').val(token));
                  // and re-submit
                  $form.get(0).submit();
                  
            }
            $('#wait-icon').toggle();
            $('#withraw_form_submit').prop('disabled', false);
            //$.unblockUI();
      };      
      
      jQuery(function($) {
            $('#withdrawForm').submit(function(e) {
                  $('#withraw_form_submit').prop('disabled', true);
                  $('#wait-icon').toggle();
                  
                  var $form = $(this);
                                     
                  Stripe.bankAccount.createToken({
                            country: 'US',
                            routingNumber: $('#routing-number').val(),
                            accountNumber: $('#account-number').val(),
                  }, stripeResponseHandler);
                  
                  // Prevent the form from submitting with the default action
                  return false;
            })
      });     
  
</script>


<?php $form=$this->beginWidget('CActiveForm', 
      array('id'=>'withdrawForm','action'=>array('user/withdraw'),'method'=>'post',)); ?>    
                             
      <div id='withdraw-panel' class='input-form'>
            <div class='invisible flash flash_error' id='transferErrors'>  
            </div>
            <div class='form_header'>
                  <div class='larger text-emphasize'><?php $this->echoText(__FILE__,'Withdraw Funds Form'); ?></div>
                  <div><?php $this->echoText(__FILE__,'Please provide the following information so we can process your request'); ?></div>
            </div>

            <br>
            <table>
                  <tr><td>
                        <?php echo $form->textField($model,'account_name',array('class'=>'form_field','id'=>'account-name','size'=>'60', 'placeholder'=>'Name on Account')); ?>
                        <div class='info-label'><?php $this->echoText(__FILE__,'The full, legal name associated with your checking account'); ?></div>           
                  </td></tr>
                  <tr><td>
                        <input class='form_field' id='routing-number' type='text' size='25' placeholder='Routing Number'>
                        <div class='info-label'><?php $this->echoText(__FILE__,'Your Bank\'s Routing Number'); ?></div>
                   </td></tr>
                  <tr><td>
                        <input class='form_field' id='account-number' type='text' size='25' placeholder='Account Number'>
                        <div class='info-label'><?php $this->echoText(__FILE__,'Your Checking Account Number'); ?></div>
                  </td></tr>           
                  <tr><td>
                        <img height='24' src="images/stripe/dark_outline.png" class='float-right small_margin'>    
                        <div style='border-bottom: 1px solid darkgrey; overflow:hidden'><br></div>
                  </td></tr>
                  <tr><td>
                        <?php echo $form->textField($model,'amount',array('class'=>'form_field','placeholder'=>'Withdrawal Amount','size'=>25)); ?>
                        <?php echo $form->error($model,'amount',array('style'=>'display:inline')); ?>                           
                        <div class='info-label'><?php $this->echoText(__FILE__,'Max is ') . $user->balance;?></div>
                  </td></tr>
                  <tr><td>
                        <?php echo $form->passwordField($model,'password',array('class'=>'form_field','size'=>25,'placeholder'=>'Answer Fair Password')); ?>
                        <?php echo $form->error($model,'password',array('style'=>'color:red; font-size:.9em; display:inline')); ?>          
                        <div class='info-label'><?php $this->echoText(__FILE__,'For your security, pleaes re-provide your password'); ?></div>
                  </td></tr>
                  <tr><td>
                        <br>
                        <button type='submit' id='withraw_form_submit' class='button green margin_left'>Submit Request</button>
						
                        <?php echo ProjectImage::image('ajax-loader.gif', '', array('class'=>'invisible','id'=>'wait-icon', 'height'=>'20px')); ?>
						
						
                  </td></tr>                   
            </table>
      </div>                 
<?php $this->endWidget(); ?>     



<script>
      
      $(document).ready(function(){
            //showDemoData();
      });        
            
      function showDemoData() {
           $('#account-name').val('Abraham Lincoln');
           $('#routing-number').val('110000000');    
           $('#account-number').val('000123456789');
           $('#amount').val('12.34');                  
           $('#password').val('dimock');    
      }
      
</script>

