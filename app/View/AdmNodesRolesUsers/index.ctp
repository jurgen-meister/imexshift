<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm Nodes Roles Users'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_user_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_role_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Area Empresa');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('active');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('active_date');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admNodesRolesUsers as $admNodesRolesUser): ?>
			<tr>
				<td><?php echo h($admNodesRolesUser['AdmNodesRolesUser']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($admNodesRolesUser['AdmUser']['login'], array('controller' => 'adm_users', 'action' => 'view', $admNodesRolesUser['AdmUser']['id'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link($admNodesRolesUser['AdmRole']['name'], array('controller' => 'adm_roles', 'action' => 'view', $admNodesRolesUser['AdmRole']['id'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link($admNodesRolesUser['AdmNode']['name'], array('controller' => 'adm_nodes', 'action' => 'view', $admNodesRolesUser['AdmNode']['id'])); ?>
				</td>
				<td><?php echo h($admNodesRolesUser['AdmNodesRolesUser']['active']); ?>&nbsp;</td>
				<td><?php echo h($admNodesRolesUser['AdmNodesRolesUser']['active_date']); ?>&nbsp;</td>
				<td><?php echo h($admNodesRolesUser['AdmNodesRolesUser']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($admNodesRolesUser['AdmNodesRolesUser']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($admNodesRolesUser['AdmNodesRolesUser']['creator']); ?>&nbsp;</td>
				<td><?php echo h($admNodesRolesUser['AdmNodesRolesUser']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($admNodesRolesUser['AdmNodesRolesUser']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($admNodesRolesUser['AdmNodesRolesUser']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admNodesRolesUser['AdmNodesRolesUser']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admNodesRolesUser['AdmNodesRolesUser']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admNodesRolesUser['AdmNodesRolesUser']['id']), null, __('Are you sure you want to delete # %s?', $admNodesRolesUser['AdmNodesRolesUser']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
</div>