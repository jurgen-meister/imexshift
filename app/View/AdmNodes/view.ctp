<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Node');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admNode['AdmNode']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Period'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admNode['AdmPeriod']['name'], array('controller' => 'adm_periods', 'action' => 'view', $admNode['AdmPeriod']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($admNode['AdmNode']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Parent Node'); ?></dt>
			<dd>
				<?php echo h($admNode['AdmNode']['parent_node']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admNode['AdmNode']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Action'); ?></dt>
			<dd>
				<?php echo h($admNode['AdmNode']['lc_action']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admNode['AdmNode']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Created'); ?></dt>
			<dd>
				<?php echo h($admNode['AdmNode']['created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admNode['AdmNode']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modified'); ?></dt>
			<dd>
				<?php echo h($admNode['AdmNode']['modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Node')), array('action' => 'edit', $admNode['AdmNode']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Node')), array('action' => 'delete', $admNode['AdmNode']['id']), null, __('Are you sure you want to delete # %s?', $admNode['AdmNode']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Nodes')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Node')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Periods')), array('controller' => 'adm_periods', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Period')), array('controller' => 'adm_periods', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Job Titles')), array('controller' => 'adm_job_titles', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Job Title')), array('controller' => 'adm_job_titles', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm Job Titles')); ?></h3>
	<?php if (!empty($admNode['AdmJobTitle'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Adm Node Id'); ?></th>
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
		<?php foreach ($admNode['AdmJobTitle'] as $admJobTitle): ?>
			<tr>
				<td><?php echo $admJobTitle['id'];?></td>
				<td><?php echo $admJobTitle['adm_node_id'];?></td>
				<td><?php echo $admJobTitle['name'];?></td>
				<td><?php echo $admJobTitle['description'];?></td>
				<td><?php echo $admJobTitle['lc_state'];?></td>
				<td><?php echo $admJobTitle['lc_action'];?></td>
				<td><?php echo $admJobTitle['creator'];?></td>
				<td><?php echo $admJobTitle['created'];?></td>
				<td><?php echo $admJobTitle['modifier'];?></td>
				<td><?php echo $admJobTitle['modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'adm_job_titles', 'action' => 'view', $admJobTitle['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'adm_job_titles', 'action' => 'edit', $admJobTitle['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'adm_job_titles', 'action' => 'delete', $admJobTitle['id']), null, __('Are you sure you want to delete # %s?', $admJobTitle['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Adm Job Title')), array('controller' => 'adm_job_titles', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
