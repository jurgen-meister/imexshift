<?php echo $this->Html->script('AdmRolesTransactions', FALSE); ?>
<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmRolesTransaction', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Add %s', __('Adm Roles Transaction')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('adm_role_id', array('label'=>'Rol de Usuario', 'id'=>'roles'));

				echo $this->BootstrapForm->input('adm_module_id', array('label'=>'Modulos', 'id'=>'modules'));

				echo '<div id="boxControllers">';
				echo $this->BootstrapForm->input('adm_controller_id', array('label'=>'Controladores', 'id'=>'controllers'));

				echo '<div id="boxTransactions">';
				echo $this->BootstrapForm->input('adm_transaction_id', array('type'=>'select', 'multiple'=>'checkbox', 'id'=>'transactions', 'label'=>'Transacciones:', 'selected' => $checkedTransactions ));
				echo '</div>';
				echo '</div>';
				?>
				<?php echo $this->BootstrapForm->submit('Guardar', array('id'=>'saveButton')); ?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
		<div id="message" style="text-align: center;"></div>
		<div id="processing" style="text-align: center;"></div>
	</div>
</div>