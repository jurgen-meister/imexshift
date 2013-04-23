<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('InvMovement', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Edit %s', __('Inv Movement')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('code', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('date', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('description', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('inv_movement_type_id', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('document');
				echo $this->BootstrapForm->input('lc_state', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('lc_transition', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
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
			<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('InvMovement.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('InvMovement.id'))); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movements')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movement Types')), array('controller' => 'inv_movement_types', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement Type')), array('controller' => 'inv_movement_types', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movement Details')), array('controller' => 'inv_movement_details', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement Detail')), array('controller' => 'inv_movement_details', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>
</div>