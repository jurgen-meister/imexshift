<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmParameterDetail', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Edit %s', __('Adm Parameter Detail')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('adm_parameter_id', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('par_int1');
				echo $this->BootstrapForm->input('par_int2');
				echo $this->BootstrapForm->input('par_char1');
				echo $this->BootstrapForm->input('par_char2');
				echo $this->BootstrapForm->input('par_num1');
				echo $this->BootstrapForm->input('par_num2');
				echo $this->BootstrapForm->input('par_bool1');
				echo $this->BootstrapForm->input('par_bool2');				
				echo $this->BootstrapForm->hidden('id');
				?>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
<!--	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('AdmParameterDetail.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('AdmParameterDetail.id'))); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Parameter Details')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Parameters')), array('controller' => 'adm_parameters', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Parameter')), array('controller' => 'adm_parameters', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>-->
</div>