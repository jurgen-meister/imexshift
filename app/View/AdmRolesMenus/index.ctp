<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm Roles Menus'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_role_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_menu_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admRolesMenus as $admRolesMenu): ?>
			<tr>
				<td><?php echo h($admRolesMenu['AdmRolesMenu']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($admRolesMenu['AdmRole']['name'], array('controller' => 'adm_roles', 'action' => 'view', $admRolesMenu['AdmRole']['id'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link($admRolesMenu['AdmMenu']['name'], array('controller' => 'adm_menus', 'action' => 'view', $admRolesMenu['AdmMenu']['id'])); ?>
				</td>
				<td><?php echo h($admRolesMenu['AdmRolesMenu']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($admRolesMenu['AdmRolesMenu']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($admRolesMenu['AdmRolesMenu']['creator']); ?>&nbsp;</td>
				<td><?php echo h($admRolesMenu['AdmRolesMenu']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($admRolesMenu['AdmRolesMenu']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($admRolesMenu['AdmRolesMenu']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admRolesMenu['AdmRolesMenu']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admRolesMenu['AdmRolesMenu']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admRolesMenu['AdmRolesMenu']['id']), null, __('Are you sure you want to delete # %s?', $admRolesMenu['AdmRolesMenu']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
</div>