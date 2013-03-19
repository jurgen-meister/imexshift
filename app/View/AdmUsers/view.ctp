<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm User');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admUser['AdmUser']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Job Title'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admUser['AdmJobTitle']['name'], array('controller' => 'adm_job_titles', 'action' => 'view', $admUser['AdmJobTitle']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Login'); ?></dt>
			<dd>
				<?php echo h($admUser['AdmUser']['login']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Password'); ?></dt>
			<dd>
				<?php echo h($admUser['AdmUser']['password']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Active'); ?></dt>
			<dd>
				<?php echo h($admUser['AdmUser']['active']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Active Date'); ?></dt>
			<dd>
				<?php echo h($admUser['AdmUser']['active_date']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admUser['AdmUser']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Action'); ?></dt>
			<dd>
				<?php echo h($admUser['AdmUser']['lc_action']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admUser['AdmUser']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Created'); ?></dt>
			<dd>
				<?php echo h($admUser['AdmUser']['created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admUser['AdmUser']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modified'); ?></dt>
			<dd>
				<?php echo h($admUser['AdmUser']['modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm User')), array('action' => 'edit', $admUser['AdmUser']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm User')), array('action' => 'delete', $admUser['AdmUser']['id']), null, __('Are you sure you want to delete # %s?', $admUser['AdmUser']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Users')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm User')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Job Titles')), array('controller' => 'adm_job_titles', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Job Title')), array('controller' => 'adm_job_titles', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Profiles')), array('controller' => 'adm_profiles', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Profile')), array('controller' => 'adm_profiles', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Nodes Roles Users')), array('controller' => 'adm_nodes_roles_users', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Nodes Roles User')), array('controller' => 'adm_nodes_roles_users', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm Profiles')); ?></h3>
	<?php if (!empty($admUser['AdmProfile'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Adm User Id'); ?></th>
				<th><?php echo __('First Name'); ?></th>
				<th><?php echo __('Last Name'); ?></th>
				<th><?php echo __('Birthdate'); ?></th>
				<th><?php echo __('Birthplace'); ?></th>
				<th><?php echo __('Nationality'); ?></th>
				<th><?php echo __('Identity Document'); ?></th>
				<th><?php echo __('Place Of Issue'); ?></th>
				<th><?php echo __('Address'); ?></th>
				<th><?php echo __('Email'); ?></th>
				<th><?php echo __('Phone'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Action'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admUser['AdmProfile'] as $admProfile): ?>
			<tr>
				<td><?php echo $admProfile['id'];?></td>
				<td><?php echo $admProfile['adm_user_id'];?></td>
				<td><?php echo $admProfile['first_name'];?></td>
				<td><?php echo $admProfile['last_name'];?></td>
				<td><?php echo $admProfile['birthdate'];?></td>
				<td><?php echo $admProfile['birthplace'];?></td>
				<td><?php echo $admProfile['nationality'];?></td>
				<td><?php echo $admProfile['identity_document'];?></td>
				<td><?php echo $admProfile['place_of_issue'];?></td>
				<td><?php echo $admProfile['address'];?></td>
				<td><?php echo $admProfile['email'];?></td>
				<td><?php echo $admProfile['phone'];?></td>
				<td><?php echo $admProfile['lc_state'];?></td>
				<td><?php echo $admProfile['lc_action'];?></td>
				<td><?php echo $admProfile['creator'];?></td>
				<td><?php echo $admProfile['created'];?></td>
				<td><?php echo $admProfile['modifier'];?></td>
				<td><?php echo $admProfile['modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'adm_profiles', 'action' => 'view', $admProfile['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'adm_profiles', 'action' => 'edit', $admProfile['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'adm_profiles', 'action' => 'delete', $admProfile['id']), null, __('Are you sure you want to delete # %s?', $admProfile['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Adm Profile')), array('controller' => 'adm_profiles', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm Nodes Roles Users')); ?></h3>
	<?php if (!empty($admUser['AdmNodesRolesUser'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Adm User Id'); ?></th>
				<th><?php echo __('Adm Role Id'); ?></th>
				<th><?php echo __('Adm Node Id'); ?></th>
				<th><?php echo __('Active'); ?></th>
				<th><?php echo __('Active Date'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Action'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admUser['AdmNodesRolesUser'] as $admNodesRolesUser): ?>
			<tr>
				<td><?php echo $admNodesRolesUser['id'];?></td>
				<td><?php echo $admNodesRolesUser['adm_user_id'];?></td>
				<td><?php echo $admNodesRolesUser['adm_role_id'];?></td>
				<td><?php echo $admNodesRolesUser['adm_node_id'];?></td>
				<td><?php echo $admNodesRolesUser['active'];?></td>
				<td><?php echo $admNodesRolesUser['active_date'];?></td>
				<td><?php echo $admNodesRolesUser['lc_state'];?></td>
				<td><?php echo $admNodesRolesUser['lc_action'];?></td>
				<td><?php echo $admNodesRolesUser['creator'];?></td>
				<td><?php echo $admNodesRolesUser['created'];?></td>
				<td><?php echo $admNodesRolesUser['modifier'];?></td>
				<td><?php echo $admNodesRolesUser['modified'];?></td>
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
