<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm User Log');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admUserLog['AdmUserLog']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm User Restriction'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admUserLog['AdmUserRestriction']['id'], array('controller' => 'adm_user_restrictions', 'action' => 'view', $admUserLog['AdmUserRestriction']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Tipo'); ?></dt>
			<dd>
				<?php echo h($admUserLog['AdmUserLog']['tipo']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Success'); ?></dt>
			<dd>
				<?php echo h($admUserLog['AdmUserLog']['success']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admUserLog['AdmUserLog']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($admUserLog['AdmUserLog']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admUserLog['AdmUserLog']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($admUserLog['AdmUserLog']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admUserLog['AdmUserLog']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($admUserLog['AdmUserLog']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm User Log')), array('action' => 'edit', $admUserLog['AdmUserLog']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm User Log')), array('action' => 'delete', $admUserLog['AdmUserLog']['id']), null, __('Are you sure you want to delete # %s?', $admUserLog['AdmUserLog']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm User Logs')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm User Log')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm User Restrictions')), array('controller' => 'adm_user_restrictions', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm User Restriction')), array('controller' => 'adm_user_restrictions', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

