<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Login');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admLogin['AdmLogin']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm User'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admLogin['AdmUser']['id'], array('controller' => 'adm_users', 'action' => 'view', $admLogin['AdmUser']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date'); ?></dt>
			<dd>
				<?php echo h($admLogin['AdmLogin']['date']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admLogin['AdmLogin']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($admLogin['AdmLogin']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admLogin['AdmLogin']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($admLogin['AdmLogin']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admLogin['AdmLogin']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($admLogin['AdmLogin']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Login')), array('action' => 'edit', $admLogin['AdmLogin']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Login')), array('action' => 'delete', $admLogin['AdmLogin']['id']), null, __('Are you sure you want to delete # %s?', $admLogin['AdmLogin']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Logins')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Login')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Users')), array('controller' => 'adm_users', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm User')), array('controller' => 'adm_users', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

