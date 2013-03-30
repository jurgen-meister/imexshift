<?php echo $this->Html->script('AdmStates', FALSE); ?>
<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmState', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Añadir %s', __('Estados')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('adm_module_id', array(
					'id'=>'modules',					
					'label'=>'Modulo:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo '<div id="boxControllers">';
				echo $this->BootstrapForm->input('adm_controller_id', array(
					'id'=>'controllers', 					
					'label'=>'Controlador:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);					
				echo '</div>';
				echo $this->BootstrapForm->input('name', array(
					'label' => 'Nombre:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('description', array(
					'label' => 'Descripcion:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);				
				?>
				<?php echo $this->BootstrapForm->submit('Guardar', array('id'=>'saveButton', 'class' => 'btn btn-primary'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
</div>