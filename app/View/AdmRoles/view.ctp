<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Role');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admRole['AdmRole']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Module'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admRole['AdmModule']['name'], array('controller' => 'adm_modules', 'action' => 'view', $admRole['AdmModule']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($admRole['AdmRole']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Description'); ?></dt>
			<dd>
				<?php echo h($admRole['AdmRole']['description']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admRole['AdmRole']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($admRole['AdmRole']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admRole['AdmRole']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($admRole['AdmRole']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admRole['AdmRole']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($admRole['AdmRole']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Role')), array('action' => 'edit', $admRole['AdmRole']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Role')), array('action' => 'delete', $admRole['AdmRole']['id']), null, __('Are you sure you want to delete # %s?', $admRole['AdmRole']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Roles')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Role')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Modules')), array('controller' => 'adm_modules', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Module')), array('controller' => 'adm_modules', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Nodes Roles Users')), array('controller' => 'adm_nodes_roles_users', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Nodes Roles User')), array('controller' => 'adm_nodes_roles_users', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Roles Menus')), array('controller' => 'adm_roles_menus', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Roles Menu')), array('controller' => 'adm_roles_menus', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Roles Transactions')), array('controller' => 'adm_roles_transactions', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Roles Transaction')), array('controller' => 'adm_roles_transactions', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm Nodes Roles Users')); ?></h3>
	<?php if (!empty($admRole['AdmNodesRolesUser'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Adm User Id'); ?></th>
				<th><?php echo __('Adm Role Id'); ?></th>
				<th><?php echo __('Adm Node Id'); ?></th>
				<th><?php echo __('Active'); ?></th>
				<th><?php echo __('Active Date'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Transaction'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Date Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Date Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admRole['AdmNodesRolesUser'] as $admNodesRolesUser): ?>
			<tr>
				<td><?php echo $admNodesRolesUser['id'];?></td>
				<td><?php echo $admNodesRolesUser['adm_user_id'];?></td>
				<td><?php echo $admNodesRolesUser['adm_role_id'];?></td>
				<td><?php echo $admNodesRolesUser['adm_node_id'];?></td>
				<td><?php echo $admNodesRolesUser['active'];?></td>
				<td><?php echo $admNodesRolesUser['active_date'];?></td>
				<td><?php echo $admNodesRolesUser['lc_state'];?></td>
				<td><?php echo $admNodesRolesUser['lc_transaction'];?></td>
				<td><?php echo $admNodesRolesUser['creator'];?></td>
				<td><?php echo $admNodesRolesUser['date_created'];?></td>
				<td><?php echo $admNodesRolesUser['modifier'];?></td>
				<td><?php echo $admNodesRolesUser['date_modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'adm_nodes_roles_users', 'action' => 'view', $admNodesRolesUser['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'adm_nodes_roles_users', 'action' => 'edit', $admNodesRolesUser['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'adm_nodes_roles_users', 'action' => 'delete', $admNodesRolesUser['id']), null, __('Are you sure you want to delete # %s?', $admNodesRolesUser['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Adm Nodes Roles User')), array('controller' => 'adm_nodes_roles_users', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm Roles Menus')); ?></h3>
	<?php if (!empty($admRole['AdmRolesMenu'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Adm Role Id'); ?></th>
				<th><?php echo __('Adm Menu Id'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Transaction'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Date Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Date Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admRole['AdmRolesMenu'] as $admRolesMenu): ?>
			<tr>
				<td><?php echo $admRolesMenu['id'];?></td>
				<td><?php echo $admRolesMenu['adm_role_id'];?></td>
				<td><?php echo $admRolesMenu['adm_menu_id'];?></td>
				<td><?php echo $admRolesMenu['lc_state'];?></td>
				<td><?php echo $admRolesMenu['lc_transaction'];?></td>
				<td><?php echo $admRolesMenu['creator'];?></td>
				<td><?php echo $admRolesMenu['date_created'];?></td>
				<td><?php echo $admRolesMenu['modifier'];?></td>
				<td><?php echo $admRolesMenu['date_modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'adm_roles_menus', 'action' => 'view', $admRolesMenu['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'adm_roles_menus', 'action' => 'edit', $admRolesMenu['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'adm_roles_menus', 'action' => 'delete', $admRolesMenu['id']), null, __('Are you sure you want to delete # %s?', $admRolesMenu['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Adm Roles Menu')), array('controller' => 'adm_roles_menus', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm Roles Transactions')); ?></h3>
	<?php if (!empty($admRole['AdmRolesTransaction'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Adm Role Id'); ?></th>
				<th><?php echo __('Adm Transaction Id'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Transaction'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Date Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Date Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admRole['AdmRolesTransaction'] as $admRolesTransaction): ?>
			<tr>
				<td><?php echo $admRolesTransaction['id'];?></td>
				<td><?php echo $admRolesTransaction['adm_role_id'];?></td>
				<td><?php echo $admRolesTransaction['adm_transaction_id'];?></td>
				<td><?php echo $admRolesTransaction['lc_state'];?></td>
				<td><?php echo $admRolesTransaction['lc_transaction'];?></td>
				<td><?php echo $admRolesTransaction['creator'];?></td>
				<td><?php echo $admRolesTransaction['date_created'];?></td>
				<td><?php echo $admRolesTransaction['modifier'];?></td>
				<td><?php echo $admRolesTransaction['date_modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'adm_roles_transactions', 'action' => 'view', $admRolesTransaction['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'adm_roles_transactions', 'action' => 'edit', $admRolesTransaction['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'adm_roles_transactions', 'action' => 'delete', $admRolesTransaction['id']), null, __('Are you sure you want to delete # %s?', $admRolesTransaction['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Adm Roles Transaction')), array('controller' => 'adm_roles_transactions', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
