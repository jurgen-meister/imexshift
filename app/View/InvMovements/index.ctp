<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Inv Movements'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('code');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('inv_movement_type_id', 'Movimiento');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Status');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('inv_warehouse_id', 'Almacen');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('inv_item_id', 'Item');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date', 'Fecha');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('quantity', 'Cantidad');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('document');?></th>

				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($invMovements as $invMovement): ?>
			<tr>
				<td><?php echo h($invMovement['InvMovement']['code']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($invMovement['InvMovementType']['name'], array('controller' => 'inv_movement_types', 'action' => 'view', $invMovement['InvMovementType']['id'])); ?>
				</td>
				<td>
					<?php echo h($invMovement['InvMovementType']['status']); ?>
				</td>
				<td>
					<?php echo $this->Html->link($invMovement['InvWarehouse']['name'], array('controller' => 'inv_warehouses', 'action' => 'view', $invMovement['InvWarehouse']['id'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link($invMovement['InvItem']['name'], array('controller' => 'inv_items', 'action' => 'view', $invMovement['InvItem']['id'])); ?>
				</td>
				<td><?php echo h($invMovement['InvMovement']['date']); ?>&nbsp;</td>
				<td><?php echo h($invMovement['InvMovement']['quantity']); ?>&nbsp;</td>
				<td><?php echo h($invMovement['InvMovement']['document']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $invMovement['InvMovement']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $invMovement['InvMovement']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $invMovement['InvMovement']['id']), null, __('Are you sure you want to delete # %s?', $invMovement['InvMovement']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement')), array('action' => 'add_in')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Items')), array('controller' => 'inv_items', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Item')), array('controller' => 'inv_items', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Warehouses')), array('controller' => 'inv_warehouses', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Warehouse')), array('controller' => 'inv_warehouses', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movement Types')), array('controller' => 'inv_movement_types', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement Type')), array('controller' => 'inv_movement_types', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>