<?php		
			echo $this->BootstrapForm->input('stock', array(				
				'label' => 'Stock:',
				'id'=>'txtModalStock',
				'value'=>$stock,
				'disabled'=>'disabled',
				'style'=>'background-color:#EEEEEE',
				'class'=>'input-small',
				'maxlength'=>'15'
			));		
?>
