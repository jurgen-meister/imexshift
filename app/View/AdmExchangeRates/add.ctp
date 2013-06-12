<!-- ************************************************************************************************************************ -->
<div class="span12"><!-- START CONTAINER FLUID/ROW FLUID/SPAN12 - FORMATO DE #UNICORN -->
<!-- ************************************************************************************************************************ -->
<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 1/2 *************************************** -->
<div class="widget-box">
	<div class="widget-title">
		<span class="icon">
			<i class="icon-edit"></i>								
		</span>
		<h5>Crear Tipo de Cambio </h5>
	</div>
	<div class="widget-content nopadding">
		<?php echo $this->BootstrapForm->create('AdmExchangeRate', array('class' => 'form-horizontal'));?>
			<fieldset>
				<?php
				echo $this->BootstrapForm->input('adm_parameter_detail_id', array(
					'label' => 'Moneda:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('value', array(
					'label' => 'Monto:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;')
				);				
				?>
				<div class="form-actions" style="text-align: center">
				<?php echo $this->BootstrapForm->submit(__('Guardar Cambios'), array('div'=>false, 'class'=>'btn-primary'));?>
				<?php echo ' '.$this->Html->link('Cancelar', array_merge(array('action'=>'index')), array('class'=>'btn') );?>
			</div>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
	</div> <!-- Belongs to: <div class="widget-content nopadding"> -->
</div> <!-- Belongs to: <div class="widget-box"> -->
<!-- //******************************** END - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
</div>