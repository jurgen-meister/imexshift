<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Period');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Initial Date'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['initial_date']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Final Date'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['final_date']); ?>
				&nbsp;
			</dd>	
			
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['lc_state']); ?>
				&nbsp;
			</dd>
			
			<dt><?php echo __('Lc Action'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['lc_action']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Created'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['modifier']); ?>
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
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Period')), array('action' => 'edit', $admPeriod['AdmPeriod']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Period')), array('action' => 'delete', $admPeriod['AdmPeriod']['id']), null, __('Are you sure you want to delete # %s?', $admPeriod['AdmPeriod']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Periods')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Period')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Nodes')), array('controller' => 'adm_nodes', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Node')), array('controller' => 'adm_nodes', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm Nodes')); ?></h3>
	<?php if (!empty($admPeriod['AdmNode'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Adm Period Id'); ?></th>
				<th><?php echo __('Name'); ?></th>
				<th><?php echo __('Parent Node'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Action'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admPeriod['AdmNode'] as $admNode): ?>
			<tr>
				<td><?php echo $admNode['id'];?></td>
				<td><?php echo $admNode['adm_period_id'];?></td>
				<td><?php echo $admNode['name'];?></td>
				<td><?php echo $admNode['parent_node'];?></td>
				<td><?php echo $admNode['lc_state'];?></td>
				<td><?php echo $admNode['lc_action'];?></td>
				<td><?php echo $admNode['creator'];?></td>
				<td><?php echo $admNode['created'];?></td>
				<td><?php echo $admNode['modifier'];?></td>
				<td><?php echo $admNode['modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'adm_nodes', 'action' => 'view', $admNode['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'adm_nodes', 'action' => 'edit', $admNode['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'adm_nodes', 'action' => 'delete', $admNode['id']), null, __('Are you sure you want to delete # %s?', $admNode['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Adm Node')), array('controller' => 'adm_nodes', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
