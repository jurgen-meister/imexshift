<?php
						
		echo $this->BootstrapForm->input('items_id', array(				
		'label' => 'Item:',
		'id'=>'cbxModalItems',
		'class'=>'span12',
		));
		echo '<br>';
		
		echo '<div id="boxModalStock">';
		if($transfer == 'warehouses_transfer'){
			$labelStock = 'Stock Origen:';
		}else{
			$labelStock = 'Stock:';
		}
		echo'<br>';
		echo $this->BootstrapForm->input('stock', array(
		'label' => $labelStock,
		'id'=>'txtModalStock',
		'value'=>$stock,
		'style'=>'background-color:#EEEEEE',
		'class'=>'input-small',
		'maxlength'=>'15'
		));
		
		if($transfer == 'warehouses_transfer'){
			echo '<br>';
			echo $this->BootstrapForm->input('stock2', array(				
			'label' => 'Stock Destino:',
			'id'=>'txtModalStock2',
			'value'=>$stock2,
			'style'=>'background-color:#EEEEEE',
			'class'=>'input-small',
			'maxlength'=>'15'
			));
		}
		echo '</div>';	
		//echo '<br>';
		
			
	
		
		
?>