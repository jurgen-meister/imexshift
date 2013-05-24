<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Error Message');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admErrorMessage['AdmErrorMessage']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Module'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admErrorMessage['AdmModule']['name'], array('controller' => 'adm_modules', 'action' => 'view', $admErrorMessage['AdmModule']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Code'); ?></dt>
			<dd>
				<?php echo h($admErrorMessage['AdmErrorMessage']['code']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Description'); ?></dt>
			<dd>
				<?php echo h($admErrorMessage['AdmErrorMessage']['description']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Reason'); ?></dt>
			<dd>
				<?php echo h($admErrorMessage['AdmErrorMessage']['reason']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Course To Follow'); ?></dt>
			<dd>
				<?php echo h($admErrorMessage['AdmErrorMessage']['course_to_follow']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Origin'); ?></dt>
			<dd>
				<?php echo h($admErrorMessage['AdmErrorMessage']['origin']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Comments'); ?></dt>
			<dd>
				<?php echo h($admErrorMessage['AdmErrorMessage']['comments']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admErrorMessage['AdmErrorMessage']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($admErrorMessage['AdmErrorMessage']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admErrorMessage['AdmErrorMessage']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($admErrorMessage['AdmErrorMessage']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admErrorMessage['AdmErrorMessage']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($admErrorMessage['AdmErrorMessage']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Error Message')), array('action' => 'edit', $admErrorMessage['AdmErrorMessage']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Error Message')), array('action' => 'delete', $admErrorMessage['AdmErrorMessage']['id']), null, __('Are you sure you want to delete # %s?', $admErrorMessage['AdmErrorMessage']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Error Messages')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Error Message')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Modules')), array('controller' => 'adm_modules', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Module')), array('controller' => 'adm_modules', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

