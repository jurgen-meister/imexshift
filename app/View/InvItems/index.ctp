<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Inv Items'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('inv_brand_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('inv_category_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('code');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('description');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('factory_code');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('picture');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($invItems as $invItem): ?>
			<tr>
				<td><?php echo h($invItem['InvItem']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($invItem['InvBrand']['name'], array('controller' => 'inv_brands', 'action' => 'view', $invItem['InvBrand']['id'])); ?>
				</td>
				<td><?php echo h($invItem['InvItem']['inv_category_id']); ?>&nbsp;</td>
				<td><?php echo h($invItem['InvItem']['code']); ?>&nbsp;</td>
				<td><?php echo h($invItem['InvItem']['name']); ?>&nbsp;</td>
				<td><?php echo h($invItem['InvItem']['description']); ?>&nbsp;</td>
				<td><?php echo h($invItem['InvItem']['factory_code']); ?>&nbsp;</td>
				<td><?php echo h($invItem['InvItem']['picture']); ?>&nbsp;</td>
				<td><?php echo h($invItem['InvItem']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($invItem['InvItem']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($invItem['InvItem']['creator']); ?>&nbsp;</td>
				<td><?php echo h($invItem['InvItem']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($invItem['InvItem']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($invItem['InvItem']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $invItem['InvItem']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $invItem['InvItem']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $invItem['InvItem']['id']), null, __('Are you sure you want to delete # %s?', $invItem['InvItem']['id'])); ?>
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
			<li><?php echo $this->Html->link(__('New %s', __('Inv Item')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Brands')), array('controller' => 'inv_brands', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Brand')), array('controller' => 'inv_brands', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Prices')), array('controller' => 'inv_prices', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Price')), array('controller' => 'inv_prices', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movements')), array('controller' => 'inv_movements', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement')), array('controller' => 'inv_movements', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Items Suppliers')), array('controller' => 'inv_items_suppliers', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Items Supplier')), array('controller' => 'inv_items_suppliers', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>