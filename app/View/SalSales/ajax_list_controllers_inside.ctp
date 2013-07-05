<?php

echo $this->BootstrapForm->input('sal_employee_id', array(
					'required' => 'required',
					'label' => 'Encargado:',
/*js*/				'id'=>'cbxEmployees'
					//'value'=>$invWarehouses,
//					'disabled'=>$disable,
		//			'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
				));
//echo $this->BootstrapForm->input('adm_controller_id', array(
//	'label'=>'Controladores'
//	, 'id'=>'controllers'
//	, 'name'=>'AdmMenu[adm_controller_id]')
//	);

//echo '<br>';

echo $this->BootstrapForm->input('sal_tax_number_id', array(
					'required' => 'required',
					'label' => 'NIT - Nombre:',
/*js*/				'id'=>'cbxTaxNumbers'
					//'value'=>$invWarehouses,
			//		'disabled'=>$disable,
		//			'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
				));

//echo '<br>';
//echo '<div id="boxActions">';
//echo $this->BootstrapForm->input('adm_action_id', array('id'=>'actions', 'name'=>'AdmMenu[adm_action_id]', 'label'=>'Acciones:'
//								,'required' => 'required'
//								,'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;'
//								));
//echo '<br>';
//echo '</div>';



?>