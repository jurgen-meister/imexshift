<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Roles Menu');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admRolesMenu['AdmRolesMenu']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Role'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admRolesMenu['AdmRole']['name'], array('controller' => 'adm_roles', 'action' => 'view', $admRolesMenu['AdmRole']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Menu'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admRolesMenu['AdmMenu']['name'], array('controller' => 'adm_menus', 'action' => 'view', $admRolesMenu['AdmMenu']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admRolesMenu['AdmRolesMenu']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($admRolesMenu['AdmRolesMenu']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admRolesMenu['AdmRolesMenu']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($admRolesMenu['AdmRolesMenu']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admRolesMenu['AdmRolesMenu']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($admRolesMenu['AdmRolesMenu']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Roles Menu')), array('action' => 'edit', $admRolesMenu['AdmRolesMenu']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Roles Menu')), array('action' => 'delete', $admRolesMenu['AdmRolesMenu']['id']), null, __('Are you sure you want to delete # %s?', $admRolesMenu['AdmRolesMenu']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Roles Menus')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Roles Menu')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Roles')), array('controller' => 'adm_roles', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Role')), array('controller' => 'adm_roles', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Menus')), array('controller' => 'adm_menus', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Menu')), array('controller' => 'adm_menus', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

