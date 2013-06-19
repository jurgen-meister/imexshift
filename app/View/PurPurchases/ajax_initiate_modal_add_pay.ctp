<?php
						
		echo $this->BootstrapForm->input('pays_id', array(				
		'label' => 'Pagos:',
		'id'=>'cbxModalPays',
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