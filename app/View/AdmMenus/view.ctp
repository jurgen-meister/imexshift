<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Menu');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admMenu['AdmMenu']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Module'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admMenu['AdmModule']['name'], array('controller' => 'adm_modules', 'action' => 'view', $admMenu['AdmModule']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Action'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admMenu['AdmAction']['name'], array('controller' => 'adm_actions', 'action' => 'view', $admMenu['AdmAction']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($admMenu['AdmMenu']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Order Menu'); ?></dt>
			<dd>
				<?php echo h($admMenu['AdmMenu']['order_menu']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Parent Node'); ?></dt>
			<dd>
				<?php echo h($admMenu['AdmMenu']['parent_node']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admMenu['AdmMenu']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($admMenu['AdmMenu']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admMenu['AdmMenu']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($admMenu['AdmMenu']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admMenu['AdmMenu']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($admMenu['AdmMenu']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Menu')), array('action' => 'edit', $admMenu['AdmMenu']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Menu')), array('action' => 'delete', $admMenu['AdmMenu']['id']), null, __('Are you sure you want to delete # %s?', $admMenu['AdmMenu']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Menus')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Menu')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Modules')), array('controller' => 'adm_modules', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Module')), array('controller' => 'adm_modules', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Actions')), array('controller' => 'adm_actions', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Action')), array('controller' => 'adm_actions', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Roles Menus')), array('controller' => 'adm_roles_menus', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Roles Menu')), array('controller' => 'adm_roles_menus', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm Roles Menus')); ?></h3>
	<?php if (!empty($admMenu['AdmRolesMenu'])):?>
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
		<?php foreach ($admMenu['AdmRolesMenu'] as $admRolesMenu): ?>
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
