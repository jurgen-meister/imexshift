<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm Areas'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('parent_node');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('period');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admAreas as $admArea): ?>
			<tr>
				<td><?php echo h($admArea['AdmArea']['id']); ?>&nbsp;</td>
				<td><?php echo h($admArea['AdmArea']['name']); ?>&nbsp;</td>
				<td><?php echo h($admArea['AdmArea']['parent_node']); ?>&nbsp;</td>
				<td><?php echo h($admArea['AdmArea']['period']); ?>&nbsp;</td>
				<td><?php echo h($admArea['AdmArea']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($admArea['AdmArea']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($admArea['AdmArea']['creator']); ?>&nbsp;</td>
				<td><?php echo h($admArea['AdmArea']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($admArea['AdmArea']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($admArea['AdmArea']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admArea['AdmArea']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admArea['AdmArea']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admArea['AdmArea']['id']), null, __('Are you sure you want to delete # %s?', $admArea['AdmArea']['id'])); ?>
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
			<li><?php echo $this->Html->link(__('New %s', __('Adm Area')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm User Restrictions')), array('controller' => 'adm_user_restrictions', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm User Restriction')), array('controller' => 'adm_user_restrictions', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>