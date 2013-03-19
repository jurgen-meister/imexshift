<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Inv Brands'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('description');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('country_source');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transasction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($invBrands as $invBrand): ?>
			<tr>
				<td><?php echo h($invBrand['InvBrand']['id']); ?>&nbsp;</td>
				<td><?php echo h($invBrand['InvBrand']['name']); ?>&nbsp;</td>
				<td><?php echo h($invBrand['InvBrand']['description']); ?>&nbsp;</td>
				<td><?php echo h($invBrand['InvBrand']['country_source']); ?>&nbsp;</td>
				<td><?php echo h($invBrand['InvBrand']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($invBrand['InvBrand']['lc_transasction']); ?>&nbsp;</td>
				<td><?php echo h($invBrand['InvBrand']['creator']); ?>&nbsp;</td>
				<td><?php echo h($invBrand['InvBrand']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($invBrand['InvBrand']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($invBrand['InvBrand']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $invBrand['InvBrand']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $invBrand['InvBrand']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $invBrand['InvBrand']['id']), null, __('Are you sure you want to delete # %s?', $invBrand['InvBrand']['id'])); ?>
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
			<li><?php echo $this->Html->link(__('New %s', __('Inv Brand')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Items')), array('controller' => 'inv_items', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Item')), array('controller' => 'inv_items', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>