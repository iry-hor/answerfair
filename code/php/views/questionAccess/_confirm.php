
<div class='confirm_dialog'>
      <div>Please Confirm that you want to purchase access to the Answers to the question</div>

      <br>
      <div class='text-emphasize'><?php echo DataFormatter::getInstance()->formatQuestion($question_text); ?></div>
      <br>
      
      <div class='text-deemphasize'>Once you purchase access to question you will have access to that question permanently.  You will also be able to submit your own answer for no additional fee
      </div>
      <br>              
      
      <table class='confirm_table'>
            <tr>
                  <td class='right-align text-deemphasize'>YOUR ACCOUNT BALANCE</td>
                  <td class='right-align text-emphasize'><?php echo DataFormatter::getInstance()->formatMoney($ledgerRow->ending_balance); ?></td>                               
            </tr>
            <tr>
                  <td class='right-align text-deemphasize'>ACCESS FEE</td>
                  <td class='right-align text-emphasize'><?php echo DataFormatter::getInstance()->formatMoney(abs(Pricing::ACCESS_COST)); ?></td>                                
            </tr>

      </table>    
      <br>
      <div class='centered'>                                
            <a onclick='unblock()' class='button hover-black'>Cancel</a><span class='horizontal_spacer'></span>
            <a onclick='proceed()' class='button hover-white green'>Get Access</a>
      </div>
</div>



<script>
      function unblock() { $.unblockUI(); }
      function proceed() {
            var xmlhttp;

            if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                  xmlhttp=new XMLHttpRequest();
            }
            else {// code for IE6, IE5
                  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange=function() {
                  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        
                        $.unblockUI();
                        location.reload();
                        // error messages, refresh if successful
                  }
            }
            
            var url = '<?php echo Yii::app()->createUrl('questionAccess/create'); ?>';  
               
            xmlhttp.open("POST", url, true);
            xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xmlhttp.send("qid=<?php echo $question_id . '&YII_CSRF_TOKEN=' . Yii::app()->request->csrfToken;; ?>");             
      }
</script>