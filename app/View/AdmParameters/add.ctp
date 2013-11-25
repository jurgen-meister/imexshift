<div class="span12">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="icon-edit"></i>								
			</span>
			<h5>Adicionar Parametro</h5>			
		</div>
		<div class="widget-content nopadding">
			<?php echo $this->BootstrapForm->create('AdmParameter', array('class' => 'form-horizontal')); ?>
			<fieldset>			
				<?php
				echo $this->BootstrapForm->input('name', array(
					'label' => 'Nombre:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('description', array(
					'label' => 'Descripcion:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				?>
				<div class="form-actions" style="text-align: center">
					<?php
					echo $this->BootstrapForm->submit('Guardar', array('id' => 'saveButton', 'class' => 'btn btn-primary', 'div' => false));
					echo $this->Html->link('Cancelar', array('action' => 'index'), array('class' => 'btn'));
					?>
				</div>			
			</fieldset>
			<?php echo $this->BootstrapForm->end(); ?>
		</div>
	</div>
</div>