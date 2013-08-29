<div class="span12">
	<div class="widget-box">
	<div class="widget-title">
		<span class="icon">
			<i class="icon-edit"></i>								
		</span>
		<h5>Cliente</h5>			
	</div>
	<?php echo $this->BootstrapForm->create('SalCustomer', array('class' => 'form-horizontal'));?>
		<fieldset>			
			<?php
			echo $this->BootstrapForm->input('name', array(
				//'rows' => 2,
				'label' => '*Nombre',
				'required' => 'required',
				//'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;'
			));
			echo $this->BootstrapForm->input('address',array(
				//'rows' => 2,
				'label' => 'Direccion'
			));
			echo $this->BootstrapForm->input('phone',array(
				//'rows' => 2,
				'label' => 'Telf./Cel.'
			));
			echo $this->BootstrapForm->input('location',array(
				'label' => 'Ciudad'
			));
			echo $this->BootstrapForm->input('email');				
			?>
	<div class="widget-box">
	<div class="widget-title">
		<span class="icon">
			<i class="icon-edit"></i>								
		</span>
		<h5>NIT</h5>			
	</div>
	<div class="widget-content nopadding">	
		<?php			
			echo $this->BootstrapForm->input('nit', array(
				'style' => 'width: 400px',
				'label' => '*NIT',
				'required' => 'required',
				//'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;'
			));
			echo $this->BootstrapForm->input('nitname', array(
	//				'rows' => 2,
				'style' => 'width: 400px',
				'label' => '*A nombre de',
				'required' => 'required',
				//'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;'
			));				
		?>
	</div>
	</div>
	
	<div class="widget-box">
	<div class="widget-title">
		<span class="icon">
			<i class="icon-edit"></i>								
		</span>
		<h5>Encargado</h5>			
	</div>
	<div class="widget-content nopadding">	
		<?php			
			echo $this->BootstrapForm->input('empname', array(
				//'rows' => 3,
				'style' => 'width: 400px',
				'label' => '*Nombre',
				'required' => 'required',
				//'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;'
			));				
			echo $this->BootstrapForm->input('empphone', array(
				'label' => 'Telf./Cel.',
			));
			echo $this->BootstrapForm->input('empemail', array(
				'label' => 'Email')
			);			
		?>
	</div>
	</div>
		
			<div class="row-fluid">
				<div class="span2"></div>
				<div class="span6">
				<div class="btn-toolbar">
				<?php echo $this->BootstrapForm->submit('Guardar', array('id'=>'saveButton', 'class' => 'btn btn-primary', 'div' => false));
					   echo $this->Html->link('Cancelar', array('action' => 'index'), array('class'=>'btn') );
				?>
				</div>				
				</div>
				<div class="span4"></div>
			</div>	
		</fieldset>
		</div>
		</div>
	<?php echo $this->BootstrapForm->end();?>
</div>