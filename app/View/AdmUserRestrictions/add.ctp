<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmUserRestriction', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Add %s', __('Adm User Restriction')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('adm_user_id', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('adm_role_id', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('adm_area_id', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('active', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('active_date', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('period', array(
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
				?>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm User Restrictions')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Users')), array('controller' => 'adm_users', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm User')), array('controller' => 'adm_users', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Roles')), array('controller' => 'adm_roles', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Role')), array('controller' => 'adm_roles', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Areas')), array('controller' => 'adm_areas', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Area')), array('controller' => 'adm_areas', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>
</div>