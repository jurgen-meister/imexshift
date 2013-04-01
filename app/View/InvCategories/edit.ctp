<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('InvCategory', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Editar Categoria'); ?></legend>
				<?php
				echo $this->BootstrapForm->input('name', array(
					'label'=>'Nombre:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				// change descripcion -> description
				echo $this->BootstrapForm->input('descripcion', array(
					'label'=>'Descripcion:',
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
			<li><?php echo $this->Form->postLink(__('Eliminar'), array('action' => 'delete', $this->Form->value('InvCategory.id')), null, __('Esta seguro de eliminar?', $this->Form->value('InvCategory.id'))); ?></li>
			<li><?php echo $this->Html->link(__('Lista de Categorias'), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('Lista de Items'), array('controller' => 'inv_items', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('Nuevo Item'), array('controller' => 'inv_items', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>
</div>