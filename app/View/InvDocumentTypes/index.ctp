<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Inv Document Types'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('ref_table');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($invDocumentTypes as $invDocumentType): ?>
			<tr>
				<td><?php echo h($invDocumentType['InvDocumentType']['id']); ?>&nbsp;</td>
				<td><?php echo h($invDocumentType['InvDocumentType']['name']); ?>&nbsp;</td>
				<td><?php echo h($invDocumentType['InvDocumentType']['ref_table']); ?>&nbsp;</td>
				<td><?php echo h($invDocumentType['InvDocumentType']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($invDocumentType['InvDocumentType']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($invDocumentType['InvDocumentType']['creator']); ?>&nbsp;</td>
				<td><?php echo h($invDocumentType['InvDocumentType']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($invDocumentType['InvDocumentType']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($invDocumentType['InvDocumentType']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $invDocumentType['InvDocumentType']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $invDocumentType['InvDocumentType']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $invDocumentType['InvDocumentType']['id']), null, __('Are you sure you want to delete # %s?', $invDocumentType['InvDocumentType']['id'])); ?>
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
			<li><?php echo $this->Html->link(__('New %s', __('Inv Document Type')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movements')), array('controller' => 'inv_movements', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement')), array('controller' => 'inv_movements', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>