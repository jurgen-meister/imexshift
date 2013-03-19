<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Transition');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admTransition['AdmTransition']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm State'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admTransition['AdmState']['name'], array('controller' => 'adm_states', 'action' => 'view', $admTransition['AdmState']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Action'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admTransition['AdmTransaction']['name'], array('controller' => 'adm_transactions', 'action' => 'view', $admTransition['AdmTransaction']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Final State Id'); ?></dt>
			<dd>
				<?php echo h($admTransition['AdmTransition']['adm_final_state_id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admTransition['AdmTransition']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Action'); ?></dt>
			<dd>
				<?php echo h($admTransition['AdmTransition']['lc_action']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admTransition['AdmTransition']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Created'); ?></dt>
			<dd>
				<?php echo h($admTransition['AdmTransition']['created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admTransition['AdmTransition']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modified'); ?></dt>
			<dd>
				<?php echo h($admTransition['AdmTransition']['modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Transition')), array('action' => 'edit', $admTransition['AdmTransition']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Transition')), array('action' => 'delete', $admTransition['AdmTransition']['id']), null, __('Are you sure you want to delete # %s?', $admTransition['AdmTransition']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Transitions')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Transition')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm States')), array('controller' => 'adm_states', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm State')), array('controller' => 'adm_states', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Actions')), array('controller' => 'adm_actions', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Action')), array('controller' => 'adm_actions', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

