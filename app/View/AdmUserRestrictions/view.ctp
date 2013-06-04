<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm User Restriction');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admUserRestriction['AdmUserRestriction']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm User'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admUserRestriction['AdmUser']['id'], array('controller' => 'adm_users', 'action' => 'view', $admUserRestriction['AdmUser']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Role'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admUserRestriction['AdmRole']['name'], array('controller' => 'adm_roles', 'action' => 'view', $admUserRestriction['AdmRole']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Area'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admUserRestriction['AdmArea']['name'], array('controller' => 'adm_areas', 'action' => 'view', $admUserRestriction['AdmArea']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Active'); ?></dt>
			<dd>
				<?php echo h($admUserRestriction['AdmUserRestriction']['active']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Active Date'); ?></dt>
			<dd>
				<?php echo h($admUserRestriction['AdmUserRestriction']['active_date']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Period'); ?></dt>
			<dd>
				<?php echo h($admUserRestriction['AdmUserRestriction']['period']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admUserRestriction['AdmUserRestriction']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($admUserRestriction['AdmUserRestriction']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admUserRestriction['AdmUserRestriction']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($admUserRestriction['AdmUserRestriction']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admUserRestriction['AdmUserRestriction']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($admUserRestriction['AdmUserRestriction']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm User Restriction')), array('action' => 'edit', $admUserRestriction['AdmUserRestriction']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm User Restriction')), array('action' => 'delete', $admUserRestriction['AdmUserRestriction']['id']), null, __('Are you sure you want to delete # %s?', $admUserRestriction['AdmUserRestriction']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm User Restrictions')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm User Restriction')), array('action' => 'add')); ?> </li>
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

