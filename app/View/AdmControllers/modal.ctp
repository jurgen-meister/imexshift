<?php //echo $this->Html->script('modules/AdmControllers', FALSE); ?>
<?php echo $this->BootstrapForm->button("PRUEBA", array("id"=>"btnPrueba", "type"=>"button"));?>
<?php echo $this->BootstrapForm->create('AdmController', array('class' => 'form-horizontal')); ?>
<fieldset>
	<?php
	echo $this->BootstrapForm->input('adm_module_id', array('label' => 'Modulos:', 'id' => 'modules'));
	echo '<div id="boxControllers">';
	echo $this->BootstrapForm->input('adm_controllers_id', array('id' => 'controllers', 'label' => 'Controladores:', 'name' => 'AdmController[name]'
		, 'required' => 'required'
		, 'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;'
	));
	echo '</div>';
	echo $this->BootstrapForm->input('description', array(
		'required' => 'required',
		'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;')
	);
	echo $this->BootstrapForm->input('initials', array(
		'required' => 'required',
		'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;')
	);
	?>
	<div class="form-actions" style="text-align: center">
		<?php echo $this->BootstrapForm->submit(__('Guardar Cambios'), array('div' => false, 'class' => 'btn btn-primary')); ?>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
	</div>
</fieldset>
<?php echo $this->BootstrapForm->end(); ?>