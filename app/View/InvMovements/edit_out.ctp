<?php echo $this->Html->script('jquery-1.8.3', FALSE); ?>
<?php echo $this->Html->script('InvMovements', FALSE); ?>
<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('InvMovement', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Editar Salida Almacen'); ?></legend>
				<?php
				echo $this->BootstrapForm->input('date');
				echo $this->BootstrapForm->input('inv_warehouse_id', array(
					'disabled'=>'disabled',
					'label'=>'Almacen:',
					'id'=>'warehouses',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('inv_movement_type_id', array(
					'disabled'=>'disabled',
					'label'=>'Tipo Movimiento:',
					'id'=>'movement_types',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('inv_item_id', array(
					'disabled'=>'disabled',
					'label'=>'Item:',
					'id'=>'items',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				
				
				echo $this->BootstrapForm->input('avaliable', array(
					'label' => 'Stock:',
					'value'=>$avaliableQuantity,
					'style'=>'width:60px; background-color:#EEEEEE',
					'maxlength'=>'7',
					'id'=>'avaliable',
					'name'=>'InvMovement[avaliable]'
					)
				);
				
				echo $this->BootstrapForm->input('quantity', array(
					'style'=>'width:60px',
					'maxlength'=>'7',
					'label'=>'Cantidad Salida:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				
				echo $this->BootstrapForm->input('description', array(
					'label'=>'Descripción:',
					'style'=>'width:400px',
					'label' => 'Descripción:',
				));
				
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
			<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('InvMovement.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('InvMovement.id'))); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movements')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Items')), array('controller' => 'inv_items', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Item')), array('controller' => 'inv_items', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Warehouses')), array('controller' => 'inv_warehouses', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Warehouse')), array('controller' => 'inv_warehouses', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movement Types')), array('controller' => 'inv_movement_types', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement Type')), array('controller' => 'inv_movement_types', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>
</div>