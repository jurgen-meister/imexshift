<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmPeriod', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Add %s', __('Adm Period')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('year', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				?>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
</div>