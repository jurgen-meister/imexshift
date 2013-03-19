<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm State');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admState['AdmState']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Controller'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admState['AdmController']['name'], array('controller' => 'adm_controllers', 'action' => 'view', $admState['AdmController']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($admState['AdmState']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Description'); ?></dt>
			<dd>
				<?php echo h($admState['AdmState']['description']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admState['AdmState']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Action'); ?></dt>
			<dd>
				<?php echo h($admState['AdmState']['lc_action']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admState['AdmState']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Created'); ?></dt>
			<dd>
				<?php echo h($admState['AdmState']['created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admState['AdmState']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modified'); ?></dt>
			<dd>
				<?php echo h($admState['AdmState']['modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm State')), array('action' => 'edit', $admState['AdmState']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm State')), array('action' => 'delete', $admState['AdmState']['id']), null, __('Are you sure you want to delete # %s?', $admState['AdmState']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm States')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm State')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Controllers')), array('controller' => 'adm_controllers', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Controller')), array('controller' => 'adm_controllers', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Transitions')), array('controller' => 'adm_transitions', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Transition')), array('controller' => 'adm_transitions', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm Transitions')); ?></h3>
	<?php if (!empty($admState['AdmTransition'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Adm State Id'); ?></th>
				<th><?php echo __('Adm Action Id'); ?></th>
				<th><?php echo __('Adm Final State Id'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Action'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admState['AdmTransition'] as $admTransition): ?>
			<tr>
				<td><?php echo $admTransition['id'];?></td>
				<td><?php echo $admTransition['adm_state_id'];?></td>
				<td><?php echo $admTransition['adm_action_id'];?></td>
				<td><?php echo $admTransition['adm_final_state_id'];?></td>
				<td><?php echo $admTransition['lc_state'];?></td>
				<td><?php echo $admTransition['lc_action'];?></td>
				<td><?php echo $admTransition['creator'];?></td>
				<td><?php echo $admTransition['created'];?></td>
				<td><?php echo $admTransition['modifier'];?></td>
				<td><?php echo $admTransition['modified'];?></td>
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
