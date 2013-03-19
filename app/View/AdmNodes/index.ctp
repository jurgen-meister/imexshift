<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm Nodes'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_period_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('parent_node');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admNodes as $admNode): ?>
			<tr>
				<td><?php echo h($admNode['AdmNode']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($admNode['AdmPeriod']['name'], array('controller' => 'adm_periods', 'action' => 'view', $admNode['AdmPeriod']['id'])); ?>
				</td>
				<td><?php echo h($admNode['AdmNode']['name']); ?>&nbsp;</td>
				<td><?php echo h($admNode['AdmNode']['parent_node']); ?>&nbsp;</td>
				<td><?php echo h($admNode['AdmNode']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($admNode['AdmNode']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($admNode['AdmNode']['creator']); ?>&nbsp;</td>
				<td><?php echo h($admNode['AdmNode']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($admNode['AdmNode']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($admNode['AdmNode']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admNode['AdmNode']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admNode['AdmNode']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admNode['AdmNode']['id']), null, __('Are you sure you want to delete # %s?', $admNode['AdmNode']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
</div>