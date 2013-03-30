<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Inv Movement Types'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('status');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('document');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('ref_table');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($invMovementTypes as $invMovementType): ?>
			<tr>
				<td><?php echo h($invMovementType['InvMovementType']['id']); ?>&nbsp;</td>
				<td><?php echo h($invMovementType['InvMovementType']['name']); ?>&nbsp;</td>
				<td><?php echo h($invMovementType['InvMovementType']['status']); ?>&nbsp;</td>
				<td><?php echo h($invMovementType['InvMovementType']['document']); ?>&nbsp;</td>
				<td><?php echo h($invMovementType['InvMovementType']['ref_table']); ?>&nbsp;</td>
				<td><?php echo h($invMovementType['InvMovementType']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($invMovementType['InvMovementType']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($invMovementType['InvMovementType']['creator']); ?>&nbsp;</td>
				<td><?php echo h($invMovementType['InvMovementType']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($invMovementType['InvMovementType']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($invMovementType['InvMovementType']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $invMovementType['InvMovementType']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $invMovementType['InvMovementType']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $invMovementType['InvMovementType']['id']), null, __('Are you sure you want to delete # %s?', $invMovementType['InvMovementType']['id'])); ?>
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
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement Type')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movements')), array('controller' => 'inv_movements', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement')), array('controller' => 'inv_movements', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>