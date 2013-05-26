	<div class="span9">
		<?php echo $this->BootstrapForm->create('SalCustomer', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Editar %s', __('Cliente')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('name', array(
					'rows' => 3,
					'label' => 'Nombre',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('address',array(
					'rows' => 3,
					'label' => 'Direccion'
				));
				echo $this->BootstrapForm->input('phone',array(
					'rows' => 3,
					'label' => 'Telf./Cel.'
				));
				echo $this->BootstrapForm->input('email');				
				echo $this->BootstrapForm->hidden('id');
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