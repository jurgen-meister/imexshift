<?php echo $this->Html->script('AdmMenus', FALSE); ?>
<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmMenu', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Nuevo menu externo (Se mostraran en el arbol menu)'); ?></legend>
				<?php
				echo $this->BootstrapForm->input('adm_module_id', array(
					'label'=>'Modulo'
					,'id'=>'modules'
				));
				echo $this->BootstrapForm->input('name', array(
					'label'=>'Nombre menu',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('order_menu', array(
					'label'=>'Orden menu',
					'default'=>0,
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				
				echo '<div id="boxActions">';
				echo $this->BootstrapForm->input('adm_action_id', array('default'=>0, 'label'=>'Control->Acción'
				//,'required' => 'required'
				//,'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;'	
					));
				echo $this->BootstrapForm->input('adm_menu_id', array('label'=>'Padre', 'default'=>0
				,'name'=>'AdmMenu[parent_node]'	
				//,'required' => 'required'
				//,'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;'	
				));
				echo '</div>';
				/*
				echo $this->BootstrapForm->input('inside', array(
					'label'=>'Menu interno'
				));
				 */
				?>
				<?php echo $this->BootstrapForm->submit(__('Guardar'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
		<div id="message" style="text-align: center;"></div>
		<div id="processing" style="text-align: center;"></div>
	</div>
</div>