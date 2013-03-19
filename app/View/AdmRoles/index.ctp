<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm Roles'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('description');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admRoles as $admRole): ?>
			<tr>
				<td><?php echo h($admRole['AdmRole']['id']); ?>&nbsp;</td>
				<td><?php echo h($admRole['AdmRole']['name']); ?>&nbsp;</td>
				<td><?php echo h($admRole['AdmRole']['description']); ?>&nbsp;</td>
				<td><?php echo h($admRole['AdmRole']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($admRole['AdmRole']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($admRole['AdmRole']['creator']); ?>&nbsp;</td>
				<td><?php echo h($admRole['AdmRole']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($admRole['AdmRole']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($admRole['AdmRole']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admRole['AdmRole']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admRole['AdmRole']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admRole['AdmRole']['id']), null, __('Are you sure you want to delete # %s?', $admRole['AdmRole']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
</div>