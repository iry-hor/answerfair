<h1>System Ledger</h1>


<?php 


      $dataProvider = new CActiveDataProvider('SystemLedger',array(
            'criteria'=>array(
                  'order'=>'transaction_time DESC'
             ),
            'pagination'=>array(
                  'pageSize'=>30
            ),
      ));      
      


      $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'system-ledgergrid',
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