<!-- ************************************************************************************************************************ -->
<div class="span12"><!-- START CONTAINER FLUID/ROW FLUID/SPAN12 - FORMATO DE #UNICORN -->
<!-- ************************************************************************************************************************ -->
<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 1/2 *************************************** -->
<div class="widget-box">
	<div class="widget-title">
		<span class="icon">
			<i class="icon-edit"></i>								
		</span>
		<h5>Editar menu/permiso interno</h5>
	</div>
	<div class="widget-content nopadding">
		<?php echo $this->BootstrapForm->create('AdmMenu', array('class' => 'form-horizontal'));?>
			<fieldset>
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
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;'
				));
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
				<div class="form-actions" style="text-align: center">
					<?php echo $this->BootstrapForm->submit(__('Guardar cambios'), array('div'=>false, 'class'=>'btn btn-primary'));?>
					<?php echo ' '.$this->Html->link('Cancelar', array('action'=>'index_inside'), array('class'=>'btn') );?>
				</div>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
		<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
	</div> <!-- Belongs to: <div class="widget-content nopadding"> -->
</div> <!-- Belongs to: <div class="widget-box"> -->
<!-- //******************************** END - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
</div>