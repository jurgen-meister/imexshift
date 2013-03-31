<?php //echo $this->Html->script('jquery-1.4.4', FALSE); ?>
<?php echo $this->Html->script('jquery-1.8.3', FALSE); ?>
<?php echo $this->Html->script('InvMovements', FALSE); ?>
<?php //echo $this->Html->css('form-blockscreen'); ?>
<?php echo $this->Html->script('glDatePicker', FALSE); ?>
<?php echo $this->Html->css('glDatePicker.flatwhite'); ?>

<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('InvMovement', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Entrada Almacen'); ?></legend>
				<!--<input type="text" id="mydate" />-->
				<?php
				/*
				echo $this->BootstrapForm->input('inv_item_id', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;')
				);
				 */
				/*
				echo $this->BootstrapForm->input('date_extra', array(
					'type'=>'date'
				));
				 */
				echo $this->BootstrapForm->input('date_in', array(
					'required' => 'required',
					'label' => 'Fecha Entrada:',
					'id'=>'mydate',
					'maxlength'=>'0',
					'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;')
				);
				
				echo $this->BootstrapForm->input('inv_warehouse_id', array(
					'required' => 'required',
					'label' => 'Almacen:',
					'id'=>'warehouses',
					'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;')
				);
				
				echo $this->BootstrapForm->input('inv_movement_type_id', array(
					'required' => 'required',
					'id'=>'movement_types',
					'label' => 'Tipo Movimiento:',
					'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;')
				);
				
				echo '<div id="boxItemAvaliable">';
				echo $this->BootstrapForm->input('inv_item_id', array(
					'id'=>'items',
					'label'=>'Item:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;')
				);
				
				echo '<div id="boxAvaliable">';
				echo $this->BootstrapForm->input('avaliable', array(
					'label' => 'Stock:',
					'value'=>$avaliableQuantity,
					'style'=>'width:60px; background-color:#EEEEEE',
					'maxlength'=>'7',
					'id'=>'avaliable',
					'name'=>'InvMovement[avaliable]'
					)
				);
				echo '</div>';
				echo '</div>';
				
				echo $this->BootstrapForm->input('quantity', array(
					'style'=>'width:60px',
					'maxlength'=>'7',
					'label' => 'Cantidad Entrada:',
					'id'=>'quantity',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;')
				);
				
				echo $this->BootstrapForm->input('description', array(
					//'required' => 'required',
					'style'=>'width:400px',
					'label' => 'Descripción:',
					'id'=>'description'
					//,'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;'
				)
				);
				?>
				<?php echo $this->BootstrapForm->submit(__('Guardar'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
			<div id="processing"></div>
	</div>
	
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movements')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Items')), array('controller' => 'inv_items', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Item')), array('controller' => 'inv_items', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Warehouses')), array('controller' => 'inv_warehouses', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Warehouse')), array('controller' => 'inv_warehouses', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movement Concepts')), array('controller' => 'inv_movement_concepts', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement Concept')), array('controller' => 'inv_movement_concepts', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>
</div>