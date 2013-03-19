<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Module');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admModule['AdmModule']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($admModule['AdmModule']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Initials'); ?></dt>
			<dd>
				<?php echo h($admModule['AdmModule']['initials']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Description'); ?></dt>
			<dd>
				<?php echo h($admModule['AdmModule']['description']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admModule['AdmModule']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($admModule['AdmModule']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admModule['AdmModule']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($admModule['AdmModule']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admModule['AdmModule']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($admModule['AdmModule']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Module')), array('action' => 'edit', $admModule['AdmModule']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Module')), array('action' => 'delete', $admModule['AdmModule']['id']), null, __('Are you sure you want to delete # %s?', $admModule['AdmModule']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Modules')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Module')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Controllers')), array('controller' => 'adm_controllers', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Controller')), array('controller' => 'adm_controllers', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm Controllers')); ?></h3>
	<?php if (!empty($admModule['AdmController'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Adm Module Id'); ?></th>
				<th><?php echo __('Name'); ?></th>
				<th><?php echo __('Initials'); ?></th>
				<th><?php echo __('Description'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Transaction'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Date Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Date Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admModule['AdmController'] as $admController): ?>
			<tr>
				<td><?php echo $admController['id'];?></td>
				<td><?php echo $admController['adm_module_id'];?></td>
				<td><?php echo $admController['name'];?></td>
				<td><?php echo $admController['initials'];?></td>
				<td><?php echo $admController['description'];?></td>
				<td><?php echo $admController['lc_state'];?></td>
				<td><?php echo $admController['lc_transaction'];?></td>
				<td><?php echo $admController['creator'];?></td>
				<td><?php echo $admController['date_created'];?></td>
				<td><?php echo $admController['modifier'];?></td>
				<td><?php echo $admController['date_modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'adm_controllers', 'action' => 'view', $admController['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'adm_controllers', 'action' => 'edit', $admController['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'adm_controllers', 'action' => 'delete', $admController['id']), null, __('Are you sure you want to delete # %s?', $admController['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Adm Controller')), array('controller' => 'adm_controllers', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
