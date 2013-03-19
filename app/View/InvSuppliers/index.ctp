<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Inv Suppliers'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('code');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('location');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adress');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('phone');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($invSuppliers as $invSupplier): ?>
			<tr>
				<td><?php echo h($invSupplier['InvSupplier']['id']); ?>&nbsp;</td>
				<td><?php echo h($invSupplier['InvSupplier']['code']); ?>&nbsp;</td>
				<td><?php echo h($invSupplier['InvSupplier']['name']); ?>&nbsp;</td>
				<td><?php echo h($invSupplier['InvSupplier']['location']); ?>&nbsp;</td>
				<td><?php echo h($invSupplier['InvSupplier']['adress']); ?>&nbsp;</td>
				<td><?php echo h($invSupplier['InvSupplier']['phone']); ?>&nbsp;</td>
				<td><?php echo h($invSupplier['InvSupplier']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($invSupplier['InvSupplier']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($invSupplier['InvSupplier']['creator']); ?>&nbsp;</td>
				<td><?php echo h($invSupplier['InvSupplier']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($invSupplier['InvSupplier']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($invSupplier['InvSupplier']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $invSupplier['InvSupplier']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $invSupplier['InvSupplier']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $invSupplier['InvSupplier']['id']), null, __('Are you sure you want to delete # %s?', $invSupplier['InvSupplier']['id'])); ?>
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
			<li><?php echo $this->Html->link(__('New %s', __('Inv Supplier')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Supplier Contacts')), array('controller' => 'inv_supplier_contacts', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Supplier Contact')), array('controller' => 'inv_supplier_contacts', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Items Suppliers')), array('controller' => 'inv_items_suppliers', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Items Supplier')), array('controller' => 'inv_items_suppliers', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>