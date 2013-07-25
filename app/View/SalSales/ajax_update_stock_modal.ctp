<?php
		echo $this->BootstrapForm->input('sale_price', array(				
			'label' => 'Precio Unitario:',
			'id'=>'txtModalPrice',
			'value'=>$price,
			'class'=>'input-small',
			'maxlength'=>'15'
		));
?>
