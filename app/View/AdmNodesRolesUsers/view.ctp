<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Nodes Roles User');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admNodesRolesUser['AdmNodesRolesUser']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm User'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admNodesRolesUser['AdmUser']['id'], array('controller' => 'adm_users', 'action' => 'view', $admNodesRolesUser['AdmUser']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Role'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admNodesRolesUser['AdmRole']['name'], array('controller' => 'adm_roles', 'action' => 'view', $admNodesRolesUser['AdmRole']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Node'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admNodesRolesUser['AdmNode']['name'], array('controller' => 'adm_nodes', 'action' => 'view', $admNodesRolesUser['AdmNode']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Active'); ?></dt>
			<dd>
				<?php echo h($admNodesRolesUser['AdmNodesRolesUser']['active']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Active Date'); ?></dt>
			<dd>
				<?php echo h($admNodesRolesUser['AdmNodesRolesUser']['active_date']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admNodesRolesUser['AdmNodesRolesUser']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Action'); ?></dt>
			<dd>
				<?php echo h($admNodesRolesUser['AdmNodesRolesUser']['lc_action']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admNodesRolesUser['AdmNodesRolesUser']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Created'); ?></dt>
			<dd>
				<?php echo h($admNodesRolesUser['AdmNodesRolesUser']['created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admNodesRolesUser['AdmNodesRolesUser']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modified'); ?></dt>
			<dd>
				<?php echo h($admNodesRolesUser['AdmNodesRolesUser']['modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Nodes Roles User')), array('action' => 'edit', $admNodesRolesUser['AdmNodesRolesUser']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Nodes Roles User')), array('action' => 'delete', $admNodesRolesUser['AdmNodesRolesUser']['id']), null, __('Are you sure you want to delete # %s?', $admNodesRolesUser['AdmNodesRolesUser']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Nodes Roles Users')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Nodes Roles User')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Users')), array('controller' => 'adm_users', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm User')), array('controller' => 'adm_users', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Roles')), array('controller' => 'adm_roles', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Role')), array('controller' => 'adm_roles', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Nodes')), array('controller' => 'adm_nodes', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Node')), array('controller' => 'adm_nodes', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

