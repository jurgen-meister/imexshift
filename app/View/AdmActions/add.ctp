<?php echo $this->Html->script('AdmActions', FALSE); ?>
<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmAction', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Add %s', __('Adm Action')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('adm_module_id', array('label'=>'Modulos', 'id'=>'modules'));
				
				echo '<div id="boxControllers">';
				echo $this->BootstrapForm->input('adm_controller_id', array('label'=>'Controladores', 'id'=>'controllers'));

				echo '<div id="boxActions">';
				echo $this->BootstrapForm->input('adm_action_id', array('id'=>'actions', 'name'=>'AdmAction[name]', 'label'=>'Acciones:'
					,'required' => 'required'
					,'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;'
					));
				echo '</div>';
				echo '</div>';

				echo $this->BootstrapForm->input('description', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				?>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
		<div id="message" style="text-align: center;"></div>
		<div id="processing" style="text-align: center;"></div>
	</div>
</div>