
<div class='padded text-emphasize'><?php $this->echoText(__FILE__,'Fund Your Account'); ?></div>

<div class='padding-5' id='deposit_form_container'>            
      <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'deposit-form',
            'action'=>array('account/deposit'),
            'method'=>'post',
            )); ?>
        
           <span class='button green fixed_width' onclick='showCheckout(200)'>$2</span>
           <span class='button green fixed_width' onclick='showCheckout(500)'>$5</span>
           <span class='button green fixed_width' onclick='showCheckout(1000)'>$10</span>
           <span class='button green fixed_width' onclick='showCheckout(2000)'>$20</span>                  
      
      <?php $this->endWidget(); ?>     
</div>

<script>

      function showCheckout(amount) 
      {
            var token = function(res) {
                  var $input = $('<input type=hidden name=stripeToken />').val(res.id);
                  var $charge = $('<input type=hidden name=amount />').val(amount);

                  $('#deposit-form').append($input).append($charge).submit();
            };
                    
            StripeCheckout.open({           
              key:         '<?php echo Yii::app()->params['stripekeys']['publishable_key']; ?>',
              amount:      amount,
              currency:    'usd',
              name:        '<?php $this->echoText(__FILE__,"Answer Fair"); ?>',
              description: '<?php $this->echoText(__FILE__,"Fund Account with USD"); ?> ' + amount/100,
              panelLabel:  '<?php $this->echoText(__FILE__,"Charge My Card"); ?>',
              token:       token
            });    
                        
            return false;                
      }
</script>













