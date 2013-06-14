<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm User Restrictions'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_user_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_role_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_area_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('active');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('active_date');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('period');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admUserRestrictions as $admUserRestriction): ?>
			<tr>
				<td><?php echo h($admUserRestriction['AdmUserRestriction']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($admUserRestriction['AdmUser']['id'], array('controller' => 'adm_users', 'action' => 'view', $admUserRestriction['AdmUser']['id'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link($admUserRestriction['AdmRole']['name'], array('controller' => 'adm_roles', 'action' => 'view', $admUserRestriction['AdmRole']['id'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link($admUserRestriction['AdmArea']['name'], array('controller' => 'adm_areas', 'action' => 'view', $admUserRestriction['AdmArea']['id'])); ?>
				</td>
				<td><?php echo h($admUserRestriction['AdmUserRestriction']['active']); ?>&nbsp;</td>
				<td><?php echo h($admUserRestriction['AdmUserRestriction']['active_date']); ?>&nbsp;</td>
				<td><?php echo h($admUserRestriction['AdmUserRestriction']['period']); ?>&nbsp;</td>
				<td><?php echo h($admUserRestriction['AdmUserRestriction']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($admUserRestriction['AdmUserRestriction']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($admUserRestriction['AdmUserRestriction']['creator']); ?>&nbsp;</td>
				<td><?php echo h($admUserRestriction['AdmUserRestriction']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($admUserRestriction['AdmUserRestriction']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($admUserRestriction['AdmUserRestriction']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admUserRestriction['AdmUserRestriction']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admUserRestriction['AdmUserRestriction']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admUserRestriction['AdmUserRestriction']['id']), null, __('Are you sure you want to delete # %s?', $admUserRestriction['AdmUserRestriction']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm User Restriction')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Users')), array('controller' => 'adm_users', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm User')), array('controller' => 'adm_users', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Roles')), array('controller' => 'adm_roles', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Role')), array('controller' => 'adm_roles', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Areas')), array('controller' => 'adm_areas', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Area')), array('controller' => 'adm_areas', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>