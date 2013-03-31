<?php 
		
		echo $this->BootstrapForm->input('avaliable', array(
					'label' => 'Stock:',
					'value'=>$avaliableQuantity,
					'style'=>'width:60px; background-color:#EEEEEE',
					'maxlength'=>'7',
					'id'=>'avaliable',
					'name'=>'InvMovement[avaliable]'
					)
				);
		
		/*
		echo $this->BootstrapForm->input('stock_blocked', array(
			'label' => 'Stock:',
			'value'=>$avaliableQuantity,
			'style'=>'width:60px',
			'maxlength'=>'7',
			'disabled'=>'disabled',
			'id'=>'avalaible'
			)
		);
		 * 
		 */
?>
<br>