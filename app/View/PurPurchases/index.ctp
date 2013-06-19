<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Pur Purchases'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('inv_supplier_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('code');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('description');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($purPurchases as $purPurchase): ?>
			<tr>
				<td><?php echo h($purPurchase['PurPurchase']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($purPurchase['InvSupplier']['name'], array('controller' => 'inv_suppliers', 'action' => 'view', $purPurchase['InvSupplier']['id'])); ?>
				</td>
				<td><?php echo h($purPurchase['PurPurchase']['code']); ?>&nbsp;</td>
				<td><?php echo h($purPurchase['PurPurchase']['date']); ?>&nbsp;</td>
				<td><?php echo h($purPurchase['PurPurchase']['description']); ?>&nbsp;</td>
				<td><?php echo h($purPurchase['PurPurchase']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($purPurchase['PurPurchase']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($purPurchase['PurPurchase']['creator']); ?>&nbsp;</td>
				<td><?php echo h($purPurchase['PurPurchase']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($purPurchase['PurPurchase']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($purPurchase['PurPurchase']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $purPurchase['PurPurchase']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $purPurchase['PurPurchase']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $purPurchase['PurPurchase']['id']), null, __('Are you sure you want to delete # %s?', $purPurchase['PurPurchase']['id'])); ?>
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
			<li><?php echo $this->Html->link(__('New %s', __('Pur Purchase')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Suppliers')), array('controller' => 'inv_suppliers', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Supplier')), array('controller' => 'inv_suppliers', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Pur Prices')), array('controller' => 'pur_prices', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Pur Price')), array('controller' => 'pur_prices', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Pur Payments')), array('controller' => 'pur_payments', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Pur Payment')), array('controller' => 'pur_payments', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Pur Details')), array('controller' => 'pur_details', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Pur Detail')), array('controller' => 'pur_details', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>
