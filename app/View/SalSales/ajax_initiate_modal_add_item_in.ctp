<?php
		echo $this->BootstrapForm->input('inv_warehouse_id', array(				
		'label' => 'Almacén:',
		'id'=>'cbxModalWarehouses',
		'class'=>'input-xlarge'
		));

		echo '<div id="boxModalItemPriceStock">';
			//////////////////////////////////////
			echo $this->BootstrapForm->input('items_id', array(				
			'label' => 'Item:',
			'id'=>'cbxModalItems',
			'class'=>'input-xlarge'
			));

			echo '<div id="boxModalPrice">';
				echo $this->BootstrapForm->input('sale_price', array(				
				'label' => 'Precio Unitario:',
				'id'=>'txtModalPrice',
				'value'=>$price,
				'class'=>'input-small',
				'maxlength'=>'15'
				));
			echo '</div>';	

			echo '<div id="boxModalStock">';
				echo $this->BootstrapForm->input('stock', array(				
				'label' => 'Stock:',
				'id'=>'txtModalStock',
				'value'=>$stock,
				'disabled'=>'disabled',	
				'style'=>'background-color:#EEEEEE',
				'class'=>'input-small',
				'maxlength'=>'15'
				));
			echo '</div>';	
			//////////////////////////////////////
		echo '</div>';
?>