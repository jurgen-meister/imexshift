<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmParameter', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Añadir %s', __('Parametro')); ?></legend>
				<?php
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
				<?php echo $this->BootstrapForm->submit(__('Guardar'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
<!--	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Parameters')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Parameter Details')), array('controller' => 'adm_parameter_details', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Parameter Detail')), array('controller' => 'adm_parameter_details', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>-->
</div>