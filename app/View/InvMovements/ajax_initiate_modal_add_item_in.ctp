<?php
						
		echo $this->BootstrapForm->input('items_id', array(				
		'label' => 'Item:',
		'id'=>'cbxModalItems',
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