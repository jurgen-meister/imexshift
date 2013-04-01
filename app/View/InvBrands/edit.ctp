<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('InvBrand', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Editar Marca'); ?></legend>
				<?php
				echo $this->BootstrapForm->input('name', array(
					'label'=>'Nombre:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('description', array(
					'label'=>'Descripcion:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('country_source', array(
					'label' => 'Pais de Origen:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				
				echo $this->BootstrapForm->hidden('id');
				?>
				<?php echo $this->BootstrapForm->submit(__('Guardar'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Form->postLink(__('Eliminar'), array('action' => 'delete', $this->Form->value('InvBrand.id')), null, __('Esta seguro de eliminar?', $this->Form->value('InvBrand.id'))); ?></li>
			<li><?php echo $this->Html->link(__('Lista de Marcas'), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('Lista de Items'), array('controller' => 'inv_items', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('Nuevo Item'), array('controller' => 'inv_items', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>
</div>