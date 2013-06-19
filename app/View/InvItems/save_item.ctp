<?php echo $this->Html->script('modules/InvItems', FALSE); ?>
<div class="span12">
	
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="icon-edit"></i>								
			</span>
			<h5>Adicionar Item</h5>			
		</div>
	<div class="widget-content nopadding">
		<?php echo $this->BootstrapForm->create('InvItem', array('class' => 'form-horizontal'));?>
			<fieldset>				
					<?php
					echo $this->BootstrapForm->input('item_hidden',array(
						'id' => 'txtItemIdHidden',
						'type' => 'hidden',
						'value' => $id
					));
					
					echo $this->BootstrapForm->input('inv_supplier_id', array(
						'id' => 'supplier',
						'label' => 'Proveedor',
						'required' => 'required',
						'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;'
						)
					);
					
					echo $this->BootstrapForm->input('code', array(
						'id' => 'code',
						'style' => 'width:400px',
						'label' => 'Código',
						'required' => 'required',
						'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;'
						)
					);
					echo $this->BootstrapForm->input('inv_brand_id', array(
//						'after' => $this->BootstrapForm->input('Crear Marca',array(
//							'type' => 'button',
//							'href' => '#modalAddBrand', 
//							'role' => 'button', 
//							'class' => 'btn btn-info', 						
//							'data-toggle' =>'modal',
//							'label' => false,						
//							'div' => false,						
//							)
//						),					
						'id' => 'brand',
						'label' => 'Marca',
						'required' => 'required',					
						'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;'
						)
					);
					echo $this->BootstrapForm->input('inv_category_id', array(
//						'after' => $this->BootstrapForm->input('Crear Categoría', array(
//							'type' => 'button',
//							'href' => '#modalAddCategorie', 
//							'role' => 'button', 
//							'class' => 'btn btn-info', 						
//							'data-toggle' =>'modal',
//							'label' => false,						
//							'div' => false,
//							)
//						),
						'id' => 'category',
						'label' => 'Categoría',
						'required' => 'required',
						'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
					);				
					echo $this->BootstrapForm->input('name', array(
						'id' => 'name',
						'style' => 'width:400px',
						'label' => 'Nombre',
						'rows' => 3,
						'required' => 'required',
						'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
					);
					echo $this->BootstrapForm->input('description', array(
						//'class=' => 'input-xxlarge',
						//'type' => 'text',
						'id' => 'description',
						'rows' => 5,
						'style'=>'width:400px',
						'label' => 'Descripccion',
						'required' => 'required',
						'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')				
					);
					echo $this->BootstrapForm->input('min_quantity', array(
						'id' => 'minquantity',
						'label' => 'Cantidad Mínima',
						'default'=>0,)				
					);							
					echo $this->BootstrapForm->input('picture', array(
						'id' => 'picture',
						)
					);				
					?>
				  
	  
	  <!-- ////////////////////////////////// INICIO - PRECIOS /////////////////////////////////////// -->
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="icon-briefcase"></i>								
			</span>
			<h5>Precios</h5>			
		</div>
	<div class="widget-content nopadding">
		
	<div class="row-fluid">
		<div class="span1"></div>
		<div id="boxTable" class="span8">
			<?php if($id <> null){?>
			<a class="btn btn-primary" href='#' id="btnAddPrice" title="Adicionar Precio"><i class="icon-plus icon-white"></i></a>
			<p></p>
			<?php }?>
			<table class="table table-bordered table-condensed table-striped table-hover" id="tablaPrecios">
				<thead>
					<tr>
						<th>Tipo de Precio</th>
						<th>Fecha</th>
						<th>Monto</th>
						<th>Descripcion</th>
						<th class="columnItemsButtons"></th>
					</tr>
				</thead>
				<tbody>
					<?php for($i=0; $i<count($invPrices); $i++){?>
						<tr>							
							<td>
								<span id="<?php echo 'spaPriceType'.$invPrices[$i]['priceId']?>">
								<?php 
										echo $this->BootstrapForm->input($invPrices[$i]['priceId'], array(
											'type' => 'hidden',
											'value' => $invPrices[$i]['priceId'],
											'id' => 'txtPriceId'
											//'id' => 'txtPriceId'.$invPrices[$i]['priceId']
											));
										
										echo $this->BootstrapForm->input($invPrices[$i]['priceId'], array(
											'type' => 'hidden',
											'value' => $invPrices[$i]['itemId'],
											'id' => 'txtItemId'
											//'id' => 'txtPriceId'.$invPrices[$i]['priceId']
											));
										
										echo h($invPrices[$i]['priceType']); 
								?> 
								</span>
							</td>
							<td><span id="<?php echo 'spaDate'.$invPrices[$i]['priceId']?>"><?php echo h(date("d/m/Y",  strtotime($invPrices[$i]['date']))); ?></span></td>
							<td><span id="<?php echo 'spaPrice'.$invPrices[$i]['priceId']?>"><?php echo h($invPrices[$i]['price']); ?></span></td>
							<td><span id="<?php echo 'spaDescription'.$invPrices[$i]['priceId']?>"><?php echo h($invPrices[$i]['description']); ?></span></td>
							<td class="columnItemsButtons">
								<?php 
//									echo $this->Html->link('<i class= "icon-pencil icon-white"></i>','#', array(
//									'id' => 'btnEditPrice'.$invPrices[$i]['priceId'],
//									'class' => 'btn btn-info',
//									'escape'=>false, 
//									'title'=>'Editar'
//									));
									
									echo $this->Html->link('<i class= "icon-trash icon-white"></i>','#', array(
									'id' => 'btnDeletePrice'.$invPrices[$i]['priceId'],
									'class' => 'btn btn-danger',
									'escape'=>false, 
									'title'=>'Eliminar'
									)); 
								?>
							</td>
							
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="span3"></div>
	</div>		
	</div>
	</div>			
				<div class="row-fluid">
					<div class="span2"></div>
					<div class="span10">
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
	</div>

		<div id="boxMessage"></div>
		<div id="processing"></div>
	</div>

	<!-- Prices Modal -->
<div id="modalAddPrice" class="modal hide fade ">
				  
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
	  <h3 id="myModalLabel">Precios</h3>
	</div>

	<div class="modal-body form-horizontal">
	  
	  <?php
		echo '<div id="boxModalIntiatePrice">';
		
		echo $this->BootstrapForm->input('invPriceTypes', array(
			'id' => 'cbxModalPriceTypes',
			'label' => 'Tipo de Precio:',			
			'required' => 'required',
			'div' => false,
			'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')			
		);
		echo '</div>';
		echo '<br>';
		echo $this->BootstrapForm->input('date', array(			
			'id' => 'txtModalDate',
			'label' => 'Fecha:',
			'required' => 'required',			
			'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
		);		
		echo $this->BootstrapForm->input('price', array(
			'id' => 'txtModalPrice',
			'label' => 'Monto:',
			'required' => 'required',
			'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
		);
		echo $this->BootstrapForm->input('description', array(
			'id' => 'txtModalDescription',
			'label' => 'Descripcion:',
		));						
	  ?>
	<div id="boxModalValidatePrice" class="alert-error"></div> 
	</div>

	<div class="modal-footer">
	  <a href='#' class="btn btn-primary" id="btnModalAddPrice">Guardar</a>
	  <a href='#' class="btn btn-primary" id="btnModalEditPrice">Guardar</a>
	  <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>

	</div>
					
</div>


	<!-- Brands Modal -->
<div id="modalAddBrand" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Nueva Marca</h3>
  </div>
  <div class="modal-body form-horizontal">
    <?php	echo'<div id="boxModalAddBrand">';
			echo $this->BootstrapForm->input('name', array(
				'rows' => 3,				
				'label' => 'Nombre',
				'required' => 'required',
				'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
			);
			echo $this->BootstrapForm->input('description', array(
				'rows' => 5,
				//'style'=>'width:300px',
				'label' => 'Descripcion',
				'required' => 'required',
				'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
			);
			echo $this->BootstrapForm->input('country_source', array(
				'label' => 'Pais de Origen',
				'required' => 'required',
				'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
			);
			echo '</div>';
			?>	
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
    <button class="btn btn-primary" id="btnModalAddBrand">Guardar</button>
  </div>
</div>

	<!-- Categories Modal -->
<div id="modalAddCategorie" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Nueva Categoria</h3>
  </div>
  <div class="modal-body form-horizontal">
    
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
    <button class="btn btn-primary" id="btnModalAddCategorie">Guardar</button>
  </div>
</div>
