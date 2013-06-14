<?php echo $this->Html->script('modules/AdmMenus', FALSE); ?>
<!-- ************************************************************************************************************************ -->
<div class="span12"><!-- START CONTAINER FLUID/ROW FLUID/SPAN12 - FORMATO DE #UNICORN -->
<!-- ************************************************************************************************************************ -->
<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 1/2 *************************************** -->
<div class="widget-box">
	<div class="widget-title">
		<span class="icon">
			<i class="icon-edit"></i>								
		</span>
		<h5>Crear menu/permiso interno</h5>
	</div>
	<div class="widget-content nopadding">
		<?php echo $this->BootstrapForm->create('AdmMenu', array('class' => 'form-horizontal'));?>
			<fieldset>
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
				<div class="form-actions" style="text-align: center">
					<?php echo $this->BootstrapForm->submit(__('Crear menu/permiso'), array('div'=>false, 'class'=>'btn-primary'));?>
					<?php echo ' '.$this->Html->link('Cancelar', array('action'=>'index_inside'), array('class'=>'btn') );?>
				</div>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
		<div id="message" style="text-align: center;"></div>
		<div id="processing" style="text-align: center;"></div>
		<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
	</div> <!-- Belongs to: <div class="widget-content nopadding"> -->
</div> <!-- Belongs to: <div class="widget-box"> -->
<!-- //******************************** END - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
</div>