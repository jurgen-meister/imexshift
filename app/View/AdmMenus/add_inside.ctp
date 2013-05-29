<?php echo $this->Html->script('modules/AdmMenus', FALSE); ?>
<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmMenu', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Nuevo menu interno (Apareceran en el interior de cada interfaz)'); ?></legend>
				<?php
				
				echo $this->BootstrapForm->input('adm_module_id', array('label'=>'Modulos', 'id'=>'modules_inside'));
				
				echo '<div id="boxControllers">';
				echo $this->BootstrapForm->input('adm_controller_id', array('label'=>'Controladores', 'id'=>'controllers'));

				echo '<div id="boxActions">';
				echo $this->BootstrapForm->input('adm_action_id', array('id'=>'actions', 'label'=>'Acciones:'
					,'required' => 'required'
					,'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;'
					));
				echo '</div>';
				echo '</div>';
				echo $this->BootstrapForm->input('name', array(
					'label'=>'Nombre menu',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				?>
				<?php echo $this->BootstrapForm->submit(__('Guardar'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
		<div id="message" style="text-align: center;"></div>
		<div id="processing" style="text-align: center;"></div>
	</div>
</div>