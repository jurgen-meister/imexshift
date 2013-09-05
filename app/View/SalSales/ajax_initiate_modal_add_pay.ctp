<?php
						
//		echo $this->BootstrapForm->input('pays_id', array(				
//		'label' => 'Pagos:',
//		'id'=>'cbxModalPays',
//		'class'=>'input-xlarge',
//		'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
//		));
	
	echo $this->BootstrapForm->input('date', array(	
		'required' => 'required',
		'label' => 'Fecha:',
		'id'=>'txtModalDate',
		'value'=>$datePay,
		'class'=>'span3',
		'maxlength'=>'15'
	));
					
?>