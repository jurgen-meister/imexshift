<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmExchangeRate', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Adicionar %s', __('Tipo de Cambio')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('adm_parameter_detail_id', array(
					'label' => 'Moneda:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('value', array(
					'label' => 'Monto:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);				
				?>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
<!--	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Exchange Rates')), array('action' => 'index'));?></li>
		</ul>
		</div>
	</div>-->
</div>