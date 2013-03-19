<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Inv Warehouses'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('code');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('location');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('address');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($invWarehouses as $invWarehouse): ?>
			<tr>
				<td><?php echo h($invWarehouse['InvWarehouse']['id']); ?>&nbsp;</td>
				<td><?php echo h($invWarehouse['InvWarehouse']['code']); ?>&nbsp;</td>
				<td><?php echo h($invWarehouse['InvWarehouse']['name']); ?>&nbsp;</td>
				<td><?php echo h($invWarehouse['InvWarehouse']['location']); ?>&nbsp;</td>
				<td><?php echo h($invWarehouse['InvWarehouse']['address']); ?>&nbsp;</td>
				<td><?php echo h($invWarehouse['InvWarehouse']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($invWarehouse['InvWarehouse']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($invWarehouse['InvWarehouse']['creator']); ?>&nbsp;</td>
				<td><?php echo h($invWarehouse['InvWarehouse']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($invWarehouse['InvWarehouse']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($invWarehouse['InvWarehouse']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $invWarehouse['InvWarehouse']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $invWarehouse['InvWarehouse']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $invWarehouse['InvWarehouse']['id']), null, __('Are you sure you want to delete # %s?', $invWarehouse['InvWarehouse']['id'])); ?>
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
			<li><?php echo $this->Html->link(__('New %s', __('Inv Warehouse')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movements')), array('controller' => 'inv_movements', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement')), array('controller' => 'inv_movements', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>