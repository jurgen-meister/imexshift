<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Permission');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admPermission['AdmPermission']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Role'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admPermission['AdmRole']['name'], array('controller' => 'adm_roles', 'action' => 'view', $admPermission['AdmRole']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Action'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admPermission['AdmAction']['name'], array('controller' => 'adm_actions', 'action' => 'view', $admPermission['AdmAction']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admPermission['AdmPermission']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($admPermission['AdmPermission']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admPermission['AdmPermission']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($admPermission['AdmPermission']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admPermission['AdmPermission']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($admPermission['AdmPermission']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Permission')), array('action' => 'edit', $admPermission['AdmPermission']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Permission')), array('action' => 'delete', $admPermission['AdmPermission']['id']), null, __('Are you sure you want to delete # %s?', $admPermission['AdmPermission']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Permissions')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Permission')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Roles')), array('controller' => 'adm_roles', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Role')), array('controller' => 'adm_roles', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Actions')), array('controller' => 'adm_actions', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Action')), array('controller' => 'adm_actions', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

