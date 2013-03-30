<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('InvItem', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Add %s', __('Inv Item')); ?></legend>
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
								'after' => $this->BootstrapForm->input('Crear Marca',array(
									'type' => 'button',
									'href' => '#myModal', 
									'role' => 'button', 
									'class' => 'btn btn-info', 						
									'data-toggle' =>'modal',
									'label' => false,						
									'div' => false,						
									)
								),					
								'label' => 'Marca:',
								'required' => 'required',					
								'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;'
								)
							);
							echo $this->BootstrapForm->input('inv_category_id', array(
								'after' => $this->BootstrapForm->input('Crear Categoría', array(
									'type' => 'button',
									'href' => '#myModal', 
									'role' => 'button', 
									'class' => 'btn btn-info', 						
									'data-toggle' =>'modal',
									'label' => false,						
									'div' => false,
									)
								),
								'label' => 'Categoría:',
								'required' => 'required',
								'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
							);				
							echo $this->BootstrapForm->input('name', array(
								'label' => 'Nombre:',
								'required' => 'required',
								'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
							);
							echo $this->BootstrapForm->input('description', array(
								//'class=' => 'input-xxlarge',
								//'type' => 'text',
								'rows' => 5,
								'label' => 'Descripccion:',
								'required' => 'required',
								'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')				
							);
							echo $this->BootstrapForm->input('min_quantity', array(
								'label' => 'Cantidad Mínima:',
								'default'=>0,
								'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')				
							);
							echo $this->BootstrapForm->input('factory_code', array(
								'required' => 'required',
								'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
							);
							echo $this->BootstrapForm->input('picture', array(
								'required' => 'required',
								'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
							);				
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
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</div>
						</div>
					</div>
				</div>
					
				
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
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
<!--	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Items')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Brands')), array('controller' => 'inv_brands', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Brand')), array('controller' => 'inv_brands', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Prices')), array('controller' => 'inv_prices', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Price')), array('controller' => 'inv_prices', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movements')), array('controller' => 'inv_movements', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement')), array('controller' => 'inv_movements', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Items Suppliers')), array('controller' => 'inv_items_suppliers', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Items Supplier')), array('controller' => 'inv_items_suppliers', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>-->
</div>