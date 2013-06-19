<?php
						
		echo $this->BootstrapForm->input('items_id', array(				
		'label' => 'Item:',
		'id'=>'cbxModalItems',
		'class'=>'input-xlarge',
		'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
		));
		echo '<br>';
		
		echo '<div id="boxModalPrice">';
			echo $this->BootstrapForm->input('price', array(				
			'label' => 'P/U i_m_a_i_i:',
			'id'=>'txtModalPrice',
			'value'=>$price,
			'style'=>'background-color:#EEEEEE',
			'class'=>'input-small',
			'maxlength'=>'15'
			));
		echo '</div>';		
		echo '<br>';
		
		echo $this->BootstrapForm->input('inv_warehouse_id', array(				
		'label' => 'Almacén:',
		'id'=>'cbxModalWarehouses',
		'class'=>'input-xlarge',
		'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
		));
		echo '<br>';
		
		echo '<div id="boxModalStock">';
			echo $this->BootstrapForm->input('stock', array(				
			'label' => 'Stock:',
			'id'=>'txtModalStock',
			'value'=>$stock,
			'style'=>'background-color:#EEEEEE',
			'class'=>'input-small',
			'maxlength'=>'15'
			));

		echo '</div>';		
		echo '<br>';
?>