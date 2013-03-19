<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Job Title');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admJobTitle['AdmJobTitle']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Node'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admJobTitle['AdmNode']['name'], array('controller' => 'adm_nodes', 'action' => 'view', $admJobTitle['AdmNode']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($admJobTitle['AdmJobTitle']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Description'); ?></dt>
			<dd>
				<?php echo h($admJobTitle['AdmJobTitle']['description']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admJobTitle['AdmJobTitle']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Action'); ?></dt>
			<dd>
				<?php echo h($admJobTitle['AdmJobTitle']['lc_action']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admJobTitle['AdmJobTitle']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Created'); ?></dt>
			<dd>
				<?php echo h($admJobTitle['AdmJobTitle']['created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admJobTitle['AdmJobTitle']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modified'); ?></dt>
			<dd>
				<?php echo h($admJobTitle['AdmJobTitle']['modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Job Title')), array('action' => 'edit', $admJobTitle['AdmJobTitle']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Job Title')), array('action' => 'delete', $admJobTitle['AdmJobTitle']['id']), null, __('Are you sure you want to delete # %s?', $admJobTitle['AdmJobTitle']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Job Titles')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Job Title')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Nodes')), array('controller' => 'adm_nodes', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Node')), array('controller' => 'adm_nodes', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Users')), array('controller' => 'adm_users', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm User')), array('controller' => 'adm_users', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm Users')); ?></h3>
	<?php if (!empty($admJobTitle['AdmUser'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Adm Job Title Id'); ?></th>
				<th><?php echo __('Login'); ?></th>
				<th><?php echo __('Password'); ?></th>
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
		<?php foreach ($admJobTitle['AdmUser'] as $admUser): ?>
			<tr>
				<td><?php echo $admUser['id'];?></td>
				<td><?php echo $admUser['adm_job_title_id'];?></td>
				<td><?php echo $admUser['login'];?></td>
				<td><?php echo $admUser['password'];?></td>
				<td><?php echo $admUser['active'];?></td>
				<td><?php echo $admUser['active_date'];?></td>
				<td><?php echo $admUser['lc_state'];?></td>
				<td><?php echo $admUser['lc_action'];?></td>
				<td><?php echo $admUser['creator'];?></td>
				<td><?php echo $admUser['created'];?></td>
				<td><?php echo $admUser['modifier'];?></td>
				<td><?php echo $admUser['modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'adm_users', 'action' => 'view', $admUser['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'adm_users', 'action' => 'edit', $admUser['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'adm_users', 'action' => 'delete', $admUser['id']), null, __('Are you sure you want to delete # %s?', $admUser['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Adm User')), array('controller' => 'adm_users', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
