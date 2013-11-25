<?php // echo $this->Html->script('jquery.validate', FALSE);?>
<?php echo $this->BootstrapForm->create('AdmParameter', array('class' => 'form-horizontal', 'id'=>'formSave')); ?>
<fieldset>			
	<?php
	echo $this->BootstrapForm->hidden('id');
	echo $this->BootstrapForm->input('name', array(
		'label' => 'Nombre:',
		'required' => 'required',
//		'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
	));
	echo $this->BootstrapForm->input('description', array(
		'label' => 'Descripción:',
		'required' => 'required',
//		'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
	));
	?>
	<div class="form-actions" style="text-align: center">
		<?php
		echo $this->BootstrapForm->submit('Guardar', array('id' => 'btnSave', 'class' => 'btn btn-primary', 'div' => false));
		?>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
	</div>			
</fieldset>
<?php echo $this->BootstrapForm->end(); ?>