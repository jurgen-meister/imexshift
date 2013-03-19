<?php echo $this->Html->script('AdmControllers', FALSE); ?>
<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmController', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Add %s', __('Adm Controller')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('adm_module_id', array('label'=>'Modulos:', 'id'=>'modules'));
				echo '<div id="boxControllers">';
				//echo $this->BootstrapForm->input('adm_controllers_id', array('type'=>'select', 'multiple'=>'checkbox', 'selected'=>$checkedControllers,'id'=>'controllers', 'label'=>'Controladores:' ));
				echo $this->BootstrapForm->input('adm_controllers_id', array('id'=>'controllers', 'label'=>'Controladores:', 'name'=>'AdmController[name]' 
					,'required' => 'required'
					,'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;'
					));
				echo '</div>';
				echo $this->BootstrapForm->input('description', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('initials', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				?>
				<?php echo $this->BootstrapForm->submit('Guardar', array('id'=>'saveButton')); ?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
		<div id="message" style="text-align: center;"></div>
		<div id="processing" style="text-align: center;"></div>
	</div>
</div>