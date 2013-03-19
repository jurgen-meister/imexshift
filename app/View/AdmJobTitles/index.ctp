<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm Job Titles'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_node_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('description');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_action');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admJobTitles as $admJobTitle): ?>
			<tr>
				<td><?php echo h($admJobTitle['AdmJobTitle']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($admJobTitle['AdmNode']['name'], array('controller' => 'adm_nodes', 'action' => 'view', $admJobTitle['AdmNode']['id'])); ?>
				</td>
				<td><?php echo h($admJobTitle['AdmJobTitle']['name']); ?>&nbsp;</td>
				<td><?php echo h($admJobTitle['AdmJobTitle']['description']); ?>&nbsp;</td>
				<td><?php echo h($admJobTitle['AdmJobTitle']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($admJobTitle['AdmJobTitle']['lc_action']); ?>&nbsp;</td>
				<td><?php echo h($admJobTitle['AdmJobTitle']['creator']); ?>&nbsp;</td>
				<td><?php echo h($admJobTitle['AdmJobTitle']['created']); ?>&nbsp;</td>
				<td><?php echo h($admJobTitle['AdmJobTitle']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($admJobTitle['AdmJobTitle']['modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admJobTitle['AdmJobTitle']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admJobTitle['AdmJobTitle']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admJobTitle['AdmJobTitle']['id']), null, __('Are you sure you want to delete # %s?', $admJobTitle['AdmJobTitle']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
</div>