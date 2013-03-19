<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm Modules'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('initials');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('description');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admModules as $admModule): ?>
			<tr>
				<td><?php echo h($admModule['AdmModule']['id']); ?>&nbsp;</td>
				<td><?php echo h($admModule['AdmModule']['name']); ?>&nbsp;</td>
				<td><?php echo h($admModule['AdmModule']['initials']); ?>&nbsp;</td>
				<td><?php echo h($admModule['AdmModule']['description']); ?>&nbsp;</td>
				<td><?php echo h($admModule['AdmModule']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($admModule['AdmModule']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($admModule['AdmModule']['creator']); ?>&nbsp;</td>
				<td><?php echo h($admModule['AdmModule']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($admModule['AdmModule']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($admModule['AdmModule']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admModule['AdmModule']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admModule['AdmModule']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admModule['AdmModule']['id']), null, __('Are you sure you want to delete # %s?', $admModule['AdmModule']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
</div>