<?php //echo $this->Html->script('jquery-1.8.3', FALSE); ?>
<?php echo $this->Html->script('InvMovements', FALSE); ?>
<?php echo $this->Html->script('glDatePicker', FALSE); ?>
<?php echo $this->Html->css('glDatePicker.flatwhite'); ?>
<div class="row-fluid">
	<!-- INICIO ROW FLUID -->
	<div class="span9">
		<!-- INICIO CONTAINER - CLASS SPAN 9 -->
		
		<?php echo $this->BootstrapForm->create('InvMovement', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Entrada de Almacen'); ?></legend>
				
				<!-- showing process and document states-->
				<div class="row-fluid">
					<div class="span3" >
						Estado Proceso:
						
						<table id="tableDocumentState" class="table table-bordered table-condensed">
							<tr>
								<td style="background-color:#BBBBBB; color: white; ">Pedido</td>
								<td style="background-color:#BBBBBB; color: white">Orden</td>
								<td style="background-color:green; color: white">Compra</td>
							</tr>
						</table>
						
					</div>
					<div class="span9">
						<!--Pedido | Orden | Compra | Ingreso-->
					</div>
				</div>
				<p></p>
				<!-- showing process and document states-->
				
				
				<?php
				echo $this->BootstrapForm->input('date_in', array(
					'required' => 'required',
					'label' => 'Fecha:',
					'id'=>'date',
					'maxlength'=>'0',
					'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
				));
				echo $this->BootstrapForm->input('inv_warehouse_id', array(
					'required' => 'required',
					'label' => 'Almacen:',
					'id'=>'warehouses',
					'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
				));
				
				echo $this->BootstrapForm->input('inv_movement_type_id', array(
					'label' => 'Tipo Movimiento:',
					'id'=>'movementTypes',
					'required' => 'required',
					'helpInline' => '<a class="btn" href="#" id="addMovementType" title="Adicionar Tipo Movimiento"><i class="icon-plus-sign"></i></a><span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('description', array(
					'rows' => 2,
					'style'=>'width:400px',
					'label' => 'Descripción:',
					'id'=>'description'
				));
				?>
							
				<!-- /////////////////////////////////////// -->
				<a class="btn" href='#' id="addItem"><i class="icon-plus-sign"></i> Adicionar Item</a>
				<p></p>
				<div class="row-fluid">
					<div id="boxTable" class="span5">
						<table class="table table-bordered table-condensed table-striped" id="tablaItems">
							<thead>
								<tr>
									<th>#</th>
									<th>Item</th>
									<th>Stock</th>
									<th>Cantidad</th>
									<th></th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>	
				<!-- ////////////////////////////////////// -->

			<div class="form-actions">
				<?php 
					echo $this->BootstrapForm->submit('Guardar',array('class'=>'btn btn-primary','div'=>false,));	
					echo ' ';
					echo $this->Html->link('Cancelar', array('action'=>'index_in'), array('class'=>'btn') );
				?>
				<a href="#" class="btn btn-success"><i class="icon-ok icon-white"></i> Aprobar Entrada Almacen</a>
			</div>
				
		</fieldset>
	<?php echo $this->BootstrapForm->end();?>
		
		
		<!-- ////////////////////////////////// INICIO MODAL ////////////////////////////// -->
			
			<div id="modalAddItem" class="modal hide fade ">
				  
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h3 id="myModalLabel">Cantidad Item</h3>
				  </div>
				  
				  <div class="modal-body form-horizontal">
					<!--<p>One fine body…</p>-->
					<?php
					echo '<div id="boxIntiateModal">';
						//HERE GOES ITEM AND STOCK with Ajax
					echo '</div>';
					
					echo $this->BootstrapForm->input('quantity', array(				
					'label' => 'Cantidad:',
					'id'=>'quantity',
					'style'=>'width:100px',
					'maxlength'=>'10',
					'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
					));
					?>
					  <div id="itemSaveError" class="alert-error"></div> 
				  </div>
				  
				  <div class="modal-footer">
					<a href='#' class="btn btn-primary" id="saveItem">Aceptar</a>
					<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
				  </div>
					
			</div>

		<!-- ////////////////////////////////// FIN MODAL ////////////////////////////// -->
		
		
				
	
				

		
		<div id="processing"></div>
		<!-- FIN CONTAINER - CLASS SPAN 9 -->
	</div>
	<!-- FIN ROW FLUID -->
</div>
