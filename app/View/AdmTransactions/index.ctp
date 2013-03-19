<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm Transactions'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_controller_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('description');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('sentence');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admTransactions as $admTransaction): ?>
			<tr>
				<td><?php echo h($admTransaction['AdmTransaction']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($admTransaction['AdmController']['name'], array('controller' => 'adm_controllers', 'action' => 'view', $admTransaction['AdmController']['id'])); ?>
				</td>
				<td><?php echo h($admTransaction['AdmTransaction']['name']); ?>&nbsp;</td>
				<td><?php echo h($admTransaction['AdmTransaction']['description']); ?>&nbsp;</td>
				<td><?php echo h($admTransaction['AdmTransaction']['sentence']); ?>&nbsp;</td>
				<td><?php echo h($admTransaction['AdmTransaction']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($admTransaction['AdmTransaction']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($admTransaction['AdmTransaction']['creator']); ?>&nbsp;</td>
				<td><?php echo h($admTransaction['AdmTransaction']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($admTransaction['AdmTransaction']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($admTransaction['AdmTransaction']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admTransaction['AdmTransaction']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admTransaction['AdmTransaction']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admTransaction['AdmTransaction']['id']), null, __('Are you sure you want to delete # %s?', $admTransaction['AdmTransaction']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
</div>