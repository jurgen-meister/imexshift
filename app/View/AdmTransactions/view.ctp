<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Transaction');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admTransaction['AdmTransaction']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Controller'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admTransaction['AdmController']['name'], array('controller' => 'adm_controllers', 'action' => 'view', $admTransaction['AdmController']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($admTransaction['AdmTransaction']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Description'); ?></dt>
			<dd>
				<?php echo h($admTransaction['AdmTransaction']['description']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Sentence'); ?></dt>
			<dd>
				<?php echo h($admTransaction['AdmTransaction']['sentence']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admTransaction['AdmTransaction']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($admTransaction['AdmTransaction']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admTransaction['AdmTransaction']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($admTransaction['AdmTransaction']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admTransaction['AdmTransaction']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($admTransaction['AdmTransaction']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Transaction')), array('action' => 'edit', $admTransaction['AdmTransaction']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Transaction')), array('action' => 'delete', $admTransaction['AdmTransaction']['id']), null, __('Are you sure you want to delete # %s?', $admTransaction['AdmTransaction']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Transactions')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Transaction')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Controllers')), array('controller' => 'adm_controllers', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Controller')), array('controller' => 'adm_controllers', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Transitions')), array('controller' => 'adm_transitions', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Transition')), array('controller' => 'adm_transitions', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Roles Transactions')), array('controller' => 'adm_roles_transactions', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Roles Transaction')), array('controller' => 'adm_roles_transactions', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm Transitions')); ?></h3>
	<?php if (!empty($admTransaction['AdmTransition'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Adm State Id'); ?></th>
				<th><?php echo __('Adm Transaction Id'); ?></th>
				<th><?php echo __('Adm Final State Id'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Transaction'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Date Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Date Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admTransaction['AdmTransition'] as $admTransition): ?>
			<tr>
				<td><?php echo $admTransition['id'];?></td>
				<td><?php echo $admTransition['adm_state_id'];?></td>
				<td><?php echo $admTransition['adm_transaction_id'];?></td>
				<td><?php echo $admTransition['adm_final_state_id'];?></td>
				<td><?php echo $admTransition['lc_state'];?></td>
				<td><?php echo $admTransition['lc_transaction'];?></td>
				<td><?php echo $admTransition['creator'];?></td>
				<td><?php echo $admTransition['date_created'];?></td>
				<td><?php echo $admTransition['modifier'];?></td>
				<td><?php echo $admTransition['date_modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'adm_transitions', 'action' => 'view', $admTransition['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'adm_transitions', 'action' => 'edit', $admTransition['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'adm_transitions', 'action' => 'delete', $admTransition['id']), null, __('Are you sure you want to delete # %s?', $admTransition['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Adm Transition')), array('controller' => 'adm_transitions', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm Roles Transactions')); ?></h3>
	<?php if (!empty($admTransaction['AdmRolesTransaction'])):?>
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
		<?php foreach ($admTransaction['AdmRolesTransaction'] as $admRolesTransaction): ?>
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
