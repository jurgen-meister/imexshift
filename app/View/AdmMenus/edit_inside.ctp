<?php //echo $this->Html->script('AdmMenus', FALSE); ?>
<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmMenu', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Edit %s', __('Adm Menu')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('adm_module_id', array(
					'label'=>'Modulo'
					,'id'=>'modules'
					,'disabled'=>'true'
				));
				echo $this->BootstrapForm->input('adm_action_id', array(
					'label'=>'Control->Acción'
					,'disabled'=>'true'
				));
				echo $this->BootstrapForm->input('name', array(
					'label'=>'Nombre menu',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->hidden('id');
				echo $this->BootstrapForm->input( 'module', array( 
					'value' => $module  , 'name'=>'AdmMenu[adm_module_id]'
					,'type' => 'hidden'
					) 
					
				);
				echo $this->BootstrapForm->input( 'action', array( 
					'value' => $action  , 'name'=>'AdmMenu[adm_action_id]'
					,'type' => 'hidden'
					) 
				);
				?>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
		<div id="message" style="text-align: center;"></div>
		<div id="processing" style="text-align: center;"></div>
	</div>
</div>