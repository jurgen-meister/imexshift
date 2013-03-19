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
				echo $this->BootstrapForm->input('adm_action_id', array('label'=>'Control->Acción'
				//,'required' => 'required'
				//,'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;'	
					));
				echo $this->BootstrapForm->input('adm_menu_id', array('label'=>'Padre' 
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
				echo $this->BootstrapForm->hidden('id');
				?>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
		<div id="message" style="text-align: center;"></div>
		<div id="processing" style="text-align: center;"></div>
	</div>
</div>