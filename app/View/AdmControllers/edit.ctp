<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmController', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Edit %s', __('Adm Controller')); ?></legend>
				<?php
				/*
				echo $this->BootstrapForm->input('adm_module_id', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				 * 
				 */
				echo $this->BootstrapForm->input('name', array('disabled'=>'disabled'
				));
				echo $this->BootstrapForm->input('initials', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('description', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);				
				echo $this->BootstrapForm->hidden('id');
				?>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
</div>