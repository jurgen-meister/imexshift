<?php
						
		echo $this->Form->input('inv_price_type_id', array(
			'id' => 'cbxModalPriceTypes',
			'label' => 'Tipo de Precio:',			
			'required' => 'required',
			'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
		);	
		
?>