<?php
						
		echo $this->BootstrapForm->input('items', array(				
		'label' => 'Item:',
		'id'=>'items',
		'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
		));
		echo '<br>';
		
		echo '<div id="boxStock">';
			echo $this->BootstrapForm->input('stock', array(				
			'label' => 'Stock:',
			'id'=>'stock',
			'value'=>$stock,
			'style'=>'width:100px; background-color:#EEEEEE',
			'maxlength'=>'15'
			));
		echo '</div>';		
		echo '<br>';
?>