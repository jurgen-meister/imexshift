	<div class="span9">
		<?php echo $this->BootstrapForm->create('InvItem', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Adicionar %s', __('Item')); ?></legend>				
					<?php
					echo $this->BootstrapForm->input('code', array(								
						'label' => 'Código:',
						'required' => 'required',
						'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;'
						)
					);
					echo $this->BootstrapForm->input('inv_brand_id', array(
//						'after' => $this->BootstrapForm->input('Crear Marca',array(
//							'type' => 'button',
//							'href' => '#myModal', 
//							'role' => 'button', 
//							'class' => 'btn btn-info', 						
//							'data-toggle' =>'modal',
//							'label' => false,						
//							'div' => false,						
//							)
//						),					
						'label' => 'Marca:',
						'required' => 'required',					
						'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;'
						)
					);
					echo $this->BootstrapForm->input('inv_category_id', array(
//						'after' => $this->BootstrapForm->input('Crear Categoría', array(
//							'type' => 'button',
//							'href' => '#myModal', 
//							'role' => 'button', 
//							'class' => 'btn btn-info', 						
//							'data-toggle' =>'modal',
//							'label' => false,						
//							'div' => false,
//							)
//						),
						'label' => 'Categoría:',
						'required' => 'required',
						'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
					);				
					echo $this->BootstrapForm->input('name', array(
						'label' => 'Nombre:',
						'rows' => 3,
						'required' => 'required',
						'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
					);
					echo $this->BootstrapForm->input('description', array(
						//'class=' => 'input-xxlarge',
						//'type' => 'text',
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
					echo $this->BootstrapForm->input('picture', array(
						)
					);				
					?>		
				
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
		<?php echo $this->BootstrapForm->end();?>
	</div>
	<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modal header</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button class="btn btn-primary">Save changes</button>
  </div>
</div>