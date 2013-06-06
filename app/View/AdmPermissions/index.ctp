<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm Permissions'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_role_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_action_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admPermissions as $admPermission): ?>
			<tr>
				<td><?php echo h($admPermission['AdmPermission']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($admPermission['AdmRole']['name'], array('controller' => 'adm_roles', 'action' => 'view', $admPermission['AdmRole']['id'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link($admPermission['AdmAction']['name'], array('controller' => 'adm_actions', 'action' => 'view', $admPermission['AdmAction']['id'])); ?>
				</td>
				<td><?php echo h($admPermission['AdmPermission']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($admPermission['AdmPermission']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($admPermission['AdmPermission']['creator']); ?>&nbsp;</td>
				<td><?php echo h($admPermission['AdmPermission']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($admPermission['AdmPermission']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($admPermission['AdmPermission']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admPermission['AdmPermission']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admPermission['AdmPermission']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admPermission['AdmPermission']['id']), null, __('Are you sure you want to delete # %s?', $admPermission['AdmPermission']['id'])); ?>
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
			<li><?php echo $this->Html->link(__('New %s', __('Adm Permission')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Roles')), array('controller' => 'adm_roles', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Role')), array('controller' => 'adm_roles', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Actions')), array('controller' => 'adm_actions', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Action')), array('controller' => 'adm_actions', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>