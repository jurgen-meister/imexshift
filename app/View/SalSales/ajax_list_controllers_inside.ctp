<?php
echo $this->BootstrapForm->input('sal_employee_id', array(
					'required' => 'required',
					'label' => 'Encargado:',
					'id'=>'cbxEmployees'
				));


echo $this->BootstrapForm->input('sal_tax_number_id', array(
					'required' => 'required',
					'label' => 'NIT - Nombre:',
					'id'=>'cbxTaxNumbers'
				));
?>