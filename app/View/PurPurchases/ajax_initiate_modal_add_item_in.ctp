<?php
						
		echo $this->BootstrapForm->input('items_id', array(				
		'label' => 'Item:',
		'id'=>'cbxModalItems',
		'class'=>'span12'
		));
		echo '<br>';
		echo '<br>';
		echo '<div id="boxModalPrice">';
			echo $this->BootstrapForm->input('price', array(				
			'label' => 'Precio Unitario:',
			'id'=>'txtModalPrice',
			'value'=>$price,
			'class'=>'input-small',
			'maxlength'=>'15'
			));
		echo '</div>';	
		
		
?>