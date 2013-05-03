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
					<div class="span7">
						<!--Pedido | Orden | Compra | Ingreso-->
					</div>
					<div class="span5" >
						Estado Proceso:
						
						<table id="tableDocumentState" class="table table-bordered table-condensed">
							<tr>
								<td style="background-color:#BBBBBB; color: white; ">Pedido</td>
								<td style="background-color:#BBBBBB; color: white">Orden</td>
								<td style="background-color:#BBBBBB; color: white">Compra</td>
								<td style="background-color:#f99c17; color: white">Entrada</td>
							</tr>
						</table>
						
					</div>
					
				</div>
				<p></p>
				<!-- showing process and document states-->
				
				
				<?php
				
				echo $this->BootstrapForm->input('movement_hidden', array(
					'id'=>'movement_hidden',
					'value'=>$id
				));
				
				echo $this->BootstrapForm->input('date_in', array(
					'required' => 'required',
					'label' => 'Fecha:',
					'id'=>'date',
					'value'=>$date,
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
					'helpInline' => '<a class="btn" href="#" id="addMovementType" title="Nuevo Tipo Movimiento"><i class="icon-plus-sign"></i></a><span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('description', array(
					'rows' => 2,
					'style'=>'width:400px',
					'label' => 'Descripción:',
					'id'=>'description'
				));
				?>
							
				<!-- ////////////////////////////////// INICIO - ITEMS /////////////////////////////////////// -->
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#">Items</a>
					</li>
				</ul>
				
				
				<div class="row-fluid">
					<div class="span1"></div>
					<div id="boxTable" class="span8">
						<a class="btn" href='#' id="btnAddItem" title="Adicionar Item"><i class="icon-plus-sign"></i></a>
						<p></p>
						<table class="table table-bordered table-condensed table-striped" id="tablaItems">
							<thead>
								<tr>
									<th>Item</th>
									<th>Stock</th>
									<th>Cantidad</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php
								for($i=0; $i<count($invMovementDetails); $i++){
									echo '<tr>';
										echo '<td>'.$invMovementDetails[$i]['item'].'<input type="hidden" value="'.$invMovementDetails[$i]['itemId'].'" id="item_hidden" ></td>';
										echo '<td><span id="stock_hidden'.$invMovementDetails[$i]['itemId'].'">'.$invMovementDetails[$i]['stock'].'</span></td>';
										echo '<td><span id="quantity_hidden'.$invMovementDetails[$i]['itemId'].'">'.$invMovementDetails[$i]['cantidad'].'</span></td>';
										echo '<td>
												<a class="btn" href="#" id="btnEditItem'.$invMovementDetails[$i]['itemId'].'" title="Editar"><i class="icon-pencil"></i></a>
												
												<a class="btn" href="#" id="btnDeleteItem'.$invMovementDetails[$i]['itemId'].'" title="Eliminar"><i class="icon-trash"></i></a>
											  </td>';
									echo '</tr>';
									/*
									//no sirve de esta forma porque este creando el javascript antes de que se llama al jquery, tiene que ir en el script no mas
									echo '<script type="text/javascript">';
									echo '
										$(document).ready(function(){
											$("#btnEditItem2").click(function(){
														//var objectTableRowSelected = $(this).closest("tr")
														//editItemsTableRow(objectTableRowSelected);
														alert("fdgfdgfd");
														//return false; 
											});
											
											});
									';
									echo '</script>';
									*/
								}
								/*
								echo '<script type="text/javascript">
									$(window).load(function() {
									alert("vjjkfjgf");
									});
									</script>';
								 * 
								 */
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="span3"></div>
				<!-- ////////////////////////////////// FIN ITEMS /////////////////////////////////////// -->

			<div class="form-actions">
				<?php 
					echo $this->BootstrapForm->submit('Guardar Cambios',array('class'=>'btn btn-primary','div'=>false, 'id'=>'btnSaveAll'));	
					echo ' ';
					echo $this->Html->link('Cancelar', array('action'=>'index_in'), array('class'=>'btn') );
				?>
				<a href="#" id="btnChangeState" class="btn btn-success"><i class="icon-ok icon-white"></i> Aprobar Entrada Almacen</a>
			</div>
				<div id="boxMessage"></div>
		</fieldset>
	<?php echo $this->BootstrapForm->end();?>
	<div id="processing"></div>
	
		
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
						//////////////////////////////////////
					
						echo $this->BootstrapForm->input('items_id', array(				
						'label' => 'Item:',
						'id'=>'items',
						'class'=>'input-xlarge',
						'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
						));
						echo '<br>';
						$stock='';
						echo '<div id="boxStock">';
							echo $this->BootstrapForm->input('stock', array(				
							'label' => 'Stock:',
							'id'=>'stock',
							'value'=>$stock,
							'style'=>'background-color:#EEEEEE',
							'class'=>'input-small',
							'maxlength'=>'15'
							));
						
						echo '</div>';		
						echo '<br>';
					
						//////////////////////////////////////
					echo '</div>';
										
					echo $this->BootstrapForm->input('quantity', array(				
					'label' => 'Cantidad:',
					'id'=>'quantity',
					'class'=>'input-small',
					//'value'=>'6',
					'maxlength'=>'10',
					'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
					));
					?>
					  <div id="boxValidateItem" class="alert-error"></div> 
				  </div>
				  
				  <div class="modal-footer">
					<a href='#' class="btn btn-primary" id="btnSaveAddItem">Guardar</a>
					<a href='#' class="btn btn-primary" id="btnSaveEditItem">Guardar</a>
					<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
					
				  </div>
					
			</div>
		<!-- ////////////////////////////////// FIN MODAL ////////////////////////////// -->


		<!-- FIN CONTAINER - CLASS SPAN 9 -->
	</div>
	<!-- FIN ROW FLUID -->
</div>
