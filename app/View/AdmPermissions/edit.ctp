<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmPermission', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Edit %s', __('Adm Permission')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('adm_role_id', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('adm_action_id', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('lc_state', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('lc_transaction', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('creator', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('date_created', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('modifier');
				echo $this->BootstrapForm->input('date_modified');
				echo $this->BootstrapForm->hidden('id');
				?>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('AdmPermission.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('AdmPermission.id'))); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Permissions')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Roles')), array('controller' => 'adm_roles', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Role')), array('controller' => 'adm_roles', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Actions')), array('controller' => 'adm_actions', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Action')), array('controller' => 'adm_actions', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>
</div>