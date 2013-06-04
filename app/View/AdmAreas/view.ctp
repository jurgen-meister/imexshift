<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Area');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admArea['AdmArea']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($admArea['AdmArea']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Parent Node'); ?></dt>
			<dd>
				<?php echo h($admArea['AdmArea']['parent_node']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Period'); ?></dt>
			<dd>
				<?php echo h($admArea['AdmArea']['period']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admArea['AdmArea']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($admArea['AdmArea']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admArea['AdmArea']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($admArea['AdmArea']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admArea['AdmArea']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($admArea['AdmArea']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Area')), array('action' => 'edit', $admArea['AdmArea']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Area')), array('action' => 'delete', $admArea['AdmArea']['id']), null, __('Are you sure you want to delete # %s?', $admArea['AdmArea']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Areas')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Area')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm User Restrictions')), array('controller' => 'adm_user_restrictions', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm User Restriction')), array('controller' => 'adm_user_restrictions', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm User Restrictions')); ?></h3>
	<?php if (!empty($admArea['AdmUserRestriction'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Adm User Id'); ?></th>
				<th><?php echo __('Adm Role Id'); ?></th>
				<th><?php echo __('Adm Area Id'); ?></th>
				<th><?php echo __('Active'); ?></th>
				<th><?php echo __('Active Date'); ?></th>
				<th><?php echo __('Period'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Transaction'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Date Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Date Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admArea['AdmUserRestriction'] as $admUserRestriction): ?>
			<tr>
				<td><?php echo $admUserRestriction['id'];?></td>
				<td><?php echo $admUserRestriction['adm_user_id'];?></td>
				<td><?php echo $admUserRestriction['adm_role_id'];?></td>
				<td><?php echo $admUserRestriction['adm_area_id'];?></td>
				<td><?php echo $admUserRestriction['active'];?></td>
				<td><?php echo $admUserRestriction['active_date'];?></td>
				<td><?php echo $admUserRestriction['period'];?></td>
				<td><?php echo $admUserRestriction['lc_state'];?></td>
				<td><?php echo $admUserRestriction['lc_transaction'];?></td>
				<td><?php echo $admUserRestriction['creator'];?></td>
				<td><?php echo $admUserRestriction['date_created'];?></td>
				<td><?php echo $admUserRestriction['modifier'];?></td>
				<td><?php echo $admUserRestriction['date_modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'adm_user_restrictions', 'action' => 'view', $admUserRestriction['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'adm_user_restrictions', 'action' => 'edit', $admUserRestriction['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'adm_user_restrictions', 'action' => 'delete', $admUserRestriction['id']), null, __('Are you sure you want to delete # %s?', $admUserRestriction['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Adm User Restriction')), array('controller' => 'adm_user_restrictions', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
