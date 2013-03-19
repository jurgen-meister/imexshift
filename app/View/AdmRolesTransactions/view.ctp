<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Roles Transaction');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admRolesTransaction['AdmRolesTransaction']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Role'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admRolesTransaction['AdmRole']['name'], array('controller' => 'adm_roles', 'action' => 'view', $admRolesTransaction['AdmRole']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Transaction'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admRolesTransaction['AdmTransaction']['name'], array('controller' => 'adm_transactions', 'action' => 'view', $admRolesTransaction['AdmTransaction']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admRolesTransaction['AdmRolesTransaction']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($admRolesTransaction['AdmRolesTransaction']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admRolesTransaction['AdmRolesTransaction']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($admRolesTransaction['AdmRolesTransaction']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admRolesTransaction['AdmRolesTransaction']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($admRolesTransaction['AdmRolesTransaction']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Roles Transaction')), array('action' => 'edit', $admRolesTransaction['AdmRolesTransaction']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Roles Transaction')), array('action' => 'delete', $admRolesTransaction['AdmRolesTransaction']['id']), null, __('Are you sure you want to delete # %s?', $admRolesTransaction['AdmRolesTransaction']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Roles Transactions')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Roles Transaction')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Roles')), array('controller' => 'adm_roles', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Role')), array('controller' => 'adm_roles', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Transactions')), array('controller' => 'adm_transactions', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Transaction')), array('controller' => 'adm_transactions', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

