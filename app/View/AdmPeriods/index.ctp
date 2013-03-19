<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm Periods'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('year');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admPeriods as $admPeriod): ?>
			<tr>
				<td><?php echo h($admPeriod['AdmPeriod']['id']); ?>&nbsp;</td>
				<td><?php echo h($admPeriod['AdmPeriod']['year']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admPeriod['AdmPeriod']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admPeriod['AdmPeriod']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admPeriod['AdmPeriod']['id']), null, __('Are you sure you want to delete # %s?', $admPeriod['AdmPeriod']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
</div>