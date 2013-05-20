<?php
						
		echo $this->BootstrapForm->input('costs_id', array(				
		'label' => 'Cost:',
		'id'=>'cbxModalCosts',
		'class'=>'input-xlarge',
		'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
		));
/*		echo '<br>';
		
		echo '<div id="boxModalAmount">';
			echo $this->BootstrapForm->input('amount', array(				
			'label' => 'Monto:',
			'id'=>'txtModalAmount',
			'value'=>$amount,
			'style'=>'background-color:#EEEEEE',
			'class'=>'input-small',
			'maxlength'=>'15'
			));
		echo '</div>';		
		echo '<br>';
*/		
		
?>