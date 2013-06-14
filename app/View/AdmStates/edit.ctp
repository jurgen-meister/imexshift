<?php echo $this->Html->script('modules/AdmStates', FALSE); ?>
<!-- ************************************************************************************************************************ -->
<div class="span12"><!-- START CONTAINER FLUID/ROW FLUID/SPAN12 - FORMATO DE #UNICORN -->
<!-- ************************************************************************************************************************ -->
<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 1/2 *************************************** -->
<div class="widget-box">
	<div class="widget-title">
		<span class="icon">
			<i class="icon-edit"></i>								
		</span>
		<h5>Editar Estado</h5>
	</div>
	<div class="widget-content nopadding">
		<?php echo $this->BootstrapForm->create('AdmState', array('class' => 'form-horizontal'));?>
			<fieldset>
				<?php
				echo $this->BootstrapForm->input('adm_module_id', array(
					'id'=>'modules', 
					'label'=>'Modulo:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo '<div id="boxControllers">';
				echo $this->BootstrapForm->input('adm_controller_id', array(
					'id'=>'controllers', 
					'label'=>'Controlador:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo '</div>';
				echo $this->BootstrapForm->input('name', array(
					'label' => 'Nombre:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('description', array(
					'label' => 'Descripccion:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);		
				echo $this->BootstrapForm->hidden('id');
				?>
				<div class="form-actions" style="text-align: center">
				<?php echo $this->BootstrapForm->submit(__('Guardar Cambios'), array('div'=>false, 'class'=>'btn btn-primary'));?>
				<?php echo ' '.$this->Html->link('Cancelar', array_merge(array('action'=>'index')), array('class'=>'btn') );?>
			</div>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
	</div> <!-- Belongs to: <div class="widget-content nopadding"> -->
</div> <!-- Belongs to: <div class="widget-box"> -->
<!-- //******************************** END - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
</div>