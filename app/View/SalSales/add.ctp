	<div class="span9">
		<?php echo $this->BootstrapForm->create('SalSale', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Adicionar %s', __('Nota de Venta')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('sal_employee_id', array(
					'label' => 'Encargado',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('sal_tax_number_id', array(
					'label' => 'NIT',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('code', array(
					'label' => 'Código',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('doc_code', array(
					'label' => 'Código Documento',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('date_in', array(
					'label' => 'Fecha',
					'id' => 'txtDate',
					'value' => $date,
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('description', array(
					'label' => 'Descripcion',
					'rows' => 5
					
				));				
				?>
				<div class="row-fluid">
					<div class="span2"></div>
					<div class="span6">
					<div class="btn-toolbar">
					<?php echo $this->BootstrapForm->submit('Guardar', array('id'=>'saveButton', 'class' => 'btn btn-primary', 'div' => false));
						   echo $this->Html->link('Cancelar', array('action' => 'index'), array('class'=>'btn') );
					?>
					</div>				
					</div>
					<div class="span4"></div>
				</div>	
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>