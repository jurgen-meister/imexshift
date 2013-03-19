<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmUser', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Edit %s', __('Adm User')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('adm_job_title_id', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('login', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('password', array(
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
//				echo $this->BootstrapForm->input('lc_state', array(
//					'required' => 'required',
//					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
//				);
//				echo $this->BootstrapForm->input('lc_action', array(
//					'required' => 'required',
//					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
//				);
//				echo $this->BootstrapForm->input('creator', array(
//					'required' => 'required',
//					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
//				);
//				echo $this->BootstrapForm->input('modifier');
				echo $this->BootstrapForm->hidden('id');
				?>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
</div>