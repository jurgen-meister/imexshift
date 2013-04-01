<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('InvItem', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Modificar %s', __('Item')); ?></legend>
				<div class="accordion" id="details">
					<div class="accordion-group">
					  <div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#details" href="#collapse-details">
						  Detalles
						</a>
					  </div>
					  <div id="collapse-details" class="accordion-body collapse in">
						<div class="accordion-inner">
							<?php
							echo $this->BootstrapForm->input('code', array(
								'disabled' => true,					
								'label' => 'Código:',
								'required' => 'required',																		
								)
							);

							echo $this->BootstrapForm->input('inv_brand_id', array(
								'label' => 'Marca:',
								'required' => 'required',
								'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
							);
							echo $this->BootstrapForm->input('inv_category_id', array(
								'label' => 'Categoría:',
								'required' => 'required',
								'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
							);							
							echo $this->BootstrapForm->input('name', array(
								'label' => 'Nombre:',
								'required' => 'required',
								'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
							);
							echo $this->BootstrapForm->input('description', array(
								'rows' => 5,
								'style'=>'width:400px',
								'label' => 'Descripccion:',
								'required' => 'required',
								'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
							);
							echo $this->BootstrapForm->input('min_quantity', array(
								'label' => 'Cantidad Mínima:',
								'default'=>0,)				
							);
							echo $this->BootstrapForm->input('factory_code', array(
								)
							);
							echo $this->BootstrapForm->input('picture', array(
								)
							);				
							echo $this->BootstrapForm->hidden('id');
							?>
				</div>
					  </div>
					</div>					
				  </div>
				<div class="accordion" id="prices">
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#prices" href="#collapse-prices">
								Precios
							</a>							
						</div>
						<div id="collapse-prices" class="accordion-body collapse in">
							<div class="accordion-inner">
							</div>
						</div>
					</div>
				</div>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>	
</div>