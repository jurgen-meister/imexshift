<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Period');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['date_modified']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($admPeriod['AdmPeriod']['name']); ?>
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
		</ul>
		</div>
	</div>
</div>

