<?php
/* @var $this UserController */
/* @var $model User */
?>


<?php if($model->getBalance() < 1.00) { ?>
           
      <div class='flash flash_success'>Note:  Your account needs to be funded before you can access any Answer content.    
      <div class='centered' id='quick_deposit_panel'>
            <?php echo $this->renderPartial('//account/_deposit'); ?>
      </div>
      </div> 
<?php } ?>

<h1>Account Information</h1>
 
<table class="view_fields_table">

<!-- Email --------------------------------------------------------- -->
      <tr class="field_row">
            <td class="field_name">Email</td>
            <td class="field_value"><?php echo $model->email; ?></td>
            <td class="field_edit">
                  <span class="change" id="nameChange"><a onclick='editEmail()'>Edit</a></span>
            </td>
      </tr>
      <tr>
            <td colspan=3>            
                  <div class='<?php echo $activeEdit == 'email' ? '' : 'invisible'; ?> inline_form' id='email_form_container'>
                        <?php echo $this->renderPartial('_email', array('model'=>$model)); ?>
                  </div>      
            </td>            
      </tr>
<!-- User Name ----------------------------------------------------- -->

      <tr class="field_row">
            <td class="field_name">User Name</td>
            <td class="field_value"><span id="nameSpan"><?php echo $model->user_name; ?></span></td>
            <td class="field_edit">
                  <span class="change" id="nameChange"><a onclick='editUserName()'>Edit</a></span>
            </td>
      </tr>
      <tr>
            <td colspan=3>                           
                  <div class='<?php echo $activeEdit == 'username' ? '' : 'invisible'; ?> inline_form' id='username_form_container'>
                        <?php echo $this->renderPartial('_username', array('model'=>$model)); ?>
                  </div>      
            </td>    
      </tr>    
<!-- Password ------------------------------------------------------ -->    
      <tr class="field_row">
            <td class="field_name">Password</td>
            <td class="field_value"><span id="nameSpan">*******</span></td>
            <td class="field_edit">
                  <span class="change" id="nameChange"><a onclick='editPassword()'>Edit</a></span>
            </td>
      </tr>
      <tr>
      <td colspan=3>            
            <div class='<?php echo $activeEdit == 'password' ? '' : 'invisible'; ?> inline_form' id='password_form_container'>
                  <?php echo $this->renderPartial('_password', array('model'=>$changePasswordForm)); ?>
            </div>      
      </td>      
      </tr>
         
<!-- Current Balance  ---------------------------------------------- -->                                
      <tr class="field_row">
            <td class="field_name">Current Balance</td>
            <td class="field_value"><span id="nameSpan"><?php echo $model->getBalance(); ?></span></td>
            <td class="field_edit">
                  <span class="change" id="nameChange">
                  <?php 
                        if($model->getBalance() < Rules::DEPOSIT_MAX_BAL) {
                              echo "<span class='change' id='nameChange'><a onclick='deposit()'>Deposit</a></span>";
                        }
                        if ($model->getBalance() > Rules::WITHDRAW_MIN_BAL) { 
                              echo "<span class='change' id='nameChange'><a onclick='withdraw()'>Withdraw</a></span>";
                        } 
                  ?>
                  </span>
            </td>
      </tr>
      <tr>
            <td colspan=3>            
                  <div class='<?php echo $activeEdit == 'withdraw' ? '' : 'invisible'; ?> inline_form' id='withdraw_form_container'>
                        <?php echo $this->renderPartial('//account/_withdraw', array('model'=>$withdrawForm,'user'=>$model)); ?>
                  </div>
                  <div class='<?php echo $activeEdit == 'deposit' ? '' : 'invisible'; ?> inline_form align-right' id='deposit_form_container'>
                        <?php echo $this->renderPartial('//account/_deposit', array('model'=>$model)); ?>
                  </div>                             
            </td>
  
      </tr>
<!-- Current Credits  ---------------------------------------------- -->                                
      <tr class="field_row">
            <td class="field_name">Current Credits</td>
            <td class="field_value"><span id="nameSpan"><?php echo $model->getCredits(); ?></span></td>
            <td class="field_edit"></td>
      </tr>  
          
</table>




<h1 class='user_view_header'>Transaction History</h1>
<?php 

      $dataProvider = new CActiveDataProvider('UserLedger',array(
            'criteria'=>array(
                  'condition'=>'user_id=:userId',
                  'params'=>array(':userId'=>$model->user_id),
                  'order'=>'transaction_time DESC'
             ),
            'pagination'=>array(
                  'pageSize'=>30
            ),
      ));      
      
      $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'user-ledgergrid',
            'dataProvider'=>$dataProvider,
            'columns'=>array(
                  array('name'=>'date','htmlOptions'=>array('style' => 'white-space:nowrap;')),           
                  array('name'=>'type','htmlOptions'=>array('style' => 'white-space:nowrap;')), 
                  array('name'=>'ref','htmlOptions'=>array('style' => 'white-space:nowrap;overflow:hidden;')), 
                  array('name'=>'credit','htmlOptions'=>array('style' => 'text-align: right;')),                                      
                  array('name'=>'debit','htmlOptions'=>array('style' => 'text-align: right;')),    
                  array('name'=>'ending','htmlOptions'=>array('style' => 'text-align: right;')),    
                  
            ),
      )); 

?>



<script> 

      function editUserName() {
            $('#username_form_container').slideToggle('fast');
            $('#username_form_container input:first').focus();
      }
      
      function editEmail() {
            $('#email_form_container').slideToggle('fast');   
            $('#email_form_container input:first').focus();
      }
      
      function editPassword() {
            $('#password_form_container').slideToggle('fast');
            $('#password_form_container input:first').focus();           
      }
      
      function deposit() {
            $('#withdraw_form_container').hide();
            $('#deposit_form_container').slideToggle('fast');     
      }
      
      function withdraw() {
            $('#deposit_form_container').hide();           
            $('#withdraw_form_container').slideToggle('fast');
            $('#withdraw_form_container input:first').focus();             
      }

      
</script>



