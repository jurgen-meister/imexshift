<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('InvMovementType', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Nuevo Tipo de Movimiento'); ?></legend>
				<?php
				echo $this->BootstrapForm->input('name', array(
					'label'=>'Nombre:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('status_id', array(
					'label'=>'Status:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('document_id', array(
					'label'=>'Tiene Documento:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('ref_table', array(
				'label'=>'Tabla BD Ref:',
				'required' => 'required',
				'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;')
				);
				?>
				<?php echo $this->BootstrapForm->submit(__('Guardar'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movement Types')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movements')), array('controller' => 'inv_movements', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement')), array('controller' => 'inv_movements', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>
</div>