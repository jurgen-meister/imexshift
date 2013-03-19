<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Inv Supplier Contacts'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('inv_supplier_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('phone');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('job_title');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($invSupplierContacts as $invSupplierContact): ?>
			<tr>
				<td><?php echo h($invSupplierContact['InvSupplierContact']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($invSupplierContact['InvSupplier']['name'], array('controller' => 'inv_suppliers', 'action' => 'view', $invSupplierContact['InvSupplier']['id'])); ?>
				</td>
				<td><?php echo h($invSupplierContact['InvSupplierContact']['name']); ?>&nbsp;</td>
				<td><?php echo h($invSupplierContact['InvSupplierContact']['phone']); ?>&nbsp;</td>
				<td><?php echo h($invSupplierContact['InvSupplierContact']['job_title']); ?>&nbsp;</td>
				<td><?php echo h($invSupplierContact['InvSupplierContact']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($invSupplierContact['InvSupplierContact']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($invSupplierContact['InvSupplierContact']['creator']); ?>&nbsp;</td>
				<td><?php echo h($invSupplierContact['InvSupplierContact']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($invSupplierContact['InvSupplierContact']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($invSupplierContact['InvSupplierContact']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $invSupplierContact['InvSupplierContact']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $invSupplierContact['InvSupplierContact']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $invSupplierContact['InvSupplierContact']['id']), null, __('Are you sure you want to delete # %s?', $invSupplierContact['InvSupplierContact']['id'])); ?>
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
			<li><?php echo $this->Html->link(__('New %s', __('Inv Supplier Contact')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Suppliers')), array('controller' => 'inv_suppliers', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Supplier')), array('controller' => 'inv_suppliers', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>