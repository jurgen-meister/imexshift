<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Controller');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admController['AdmController']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Module'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admController['AdmModule']['name'], array('controller' => 'adm_modules', 'action' => 'view', $admController['AdmModule']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($admController['AdmController']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Initials'); ?></dt>
			<dd>
				<?php echo h($admController['AdmController']['initials']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Description'); ?></dt>
			<dd>
				<?php echo h($admController['AdmController']['description']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admController['AdmController']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Action'); ?></dt>
			<dd>
				<?php echo h($admController['AdmController']['lc_action']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admController['AdmController']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Created'); ?></dt>
			<dd>
				<?php echo h($admController['AdmController']['created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admController['AdmController']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modified'); ?></dt>
			<dd>
				<?php echo h($admController['AdmController']['modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Controller')), array('action' => 'edit', $admController['AdmController']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Controller')), array('action' => 'delete', $admController['AdmController']['id']), null, __('Are you sure you want to delete # %s?', $admController['AdmController']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Controllers')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Controller')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Modules')), array('controller' => 'adm_modules', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Module')), array('controller' => 'adm_modules', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm States')), array('controller' => 'adm_states', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm State')), array('controller' => 'adm_states', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Actions')), array('controller' => 'adm_actions', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Action')), array('controller' => 'adm_actions', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm States')); ?></h3>
	<?php if (!empty($admController['AdmState'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Adm Controller Id'); ?></th>
				<th><?php echo __('Name'); ?></th>
				<th><?php echo __('Description'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Action'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admController['AdmState'] as $admState): ?>
			<tr>
				<td><?php echo $admState['id'];?></td>
				<td><?php echo $admState['adm_controller_id'];?></td>
				<td><?php echo $admState['name'];?></td>
				<td><?php echo $admState['description'];?></td>
				<td><?php echo $admState['lc_state'];?></td>
				<td><?php echo $admState['lc_action'];?></td>
				<td><?php echo $admState['creator'];?></td>
				<td><?php echo $admState['created'];?></td>
				<td><?php echo $admState['modifier'];?></td>
				<td><?php echo $admState['modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'adm_states', 'action' => 'view', $admState['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'adm_states', 'action' => 'edit', $admState['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'adm_states', 'action' => 'delete', $admState['id']), null, __('Are you sure you want to delete # %s?', $admState['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Adm State')), array('controller' => 'adm_states', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm Actions')); ?></h3>
	<?php if (!empty($admController['AdmAction'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Adm Controller Id'); ?></th>
				<th><?php echo __('Name'); ?></th>
				<th><?php echo __('Description'); ?></th>
				<th><?php echo __('Sentence'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Action'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admController['AdmAction'] as $admAction): ?>
			<tr>
				<td><?php echo $admAction['id'];?></td>
				<td><?php echo $admAction['adm_controller_id'];?></td>
				<td><?php echo $admAction['name'];?></td>
				<td><?php echo $admAction['description'];?></td>
				<td><?php echo $admAction['sentence'];?></td>
				<td><?php echo $admAction['lc_state'];?></td>
				<td><?php echo $admAction['lc_action'];?></td>
				<td><?php echo $admAction['creator'];?></td>
				<td><?php echo $admAction['created'];?></td>
				<td><?php echo $admAction['modifier'];?></td>
				<td><?php echo $admAction['modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'adm_actions', 'action' => 'view', $admAction['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'adm_actions', 'action' => 'edit', $admAction['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'adm_actions', 'action' => 'delete', $admAction['id']), null, __('Are you sure you want to delete # %s?', $admAction['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Adm Action')), array('controller' => 'adm_actions', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
