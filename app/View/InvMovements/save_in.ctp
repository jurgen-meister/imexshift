<?php //echo $this->Html->script('jquery-1.8.3', FALSE); ?>
<?php echo $this->Html->script('InvMovements', FALSE); ?>
<?php echo $this->Html->script('glDatePicker', FALSE); ?>
<?php echo $this->Html->css('glDatePicker.flatwhite'); ?>

<!-- ************************************************************************************************************************ -->
<div class="span9"><!-- INICIO CONTAINER FLUID/ROW FLUID/SPAN9 - Del Template Principal (SPAN3 reservado para menu izquierdo) -->
<!-- ************************************************************************************************************************ -->

	<!-- ////////////////////////////////// INICIO - INICIO FORM ///////////////////////////////////// -->
		<?php echo $this->BootstrapForm->create('InvMovement', array('class' => 'form-horizontal'));?>
		<fieldset>
		<legend><?php echo __('Entrada de Almacen'); ?></legend>
	<!-- ////////////////////////////////// FIN - INICIO FORM /////////////////////////////////////// -->			
				
				
				<!-- ////////////////////////////////// INICIO - TABLA ESTADO PROCESO Y DOCUMENTO /////////////////////////////////////// -->
				<div class="row-fluid">
					<div class="span7">
						<!--Pedido | Orden | Compra | Ingreso-->
					</div>
					<div class="span2" >
						Estado Proceso:
						<?php
							switch ($documentState){
								case '':
									$stateColor = '#BBBBBB';
									break;
								case 'PENDANT':
									$stateColor = '#F99C17';
									break;
								case 'APPROVED':
									$stateColor = '#54AA54';
									break;
								case 'CANCELLED':
									$stateColor = '#BD362F';
									break;
							}
						?>
						<table id="tableProcessState" class="table table-bordered table-condensed">
							<tr>
								<td id="rowStatesMovementIn" style="background-color:<?php echo $stateColor; ?>; color: white">Entrada</td>
							</tr>
						</table>
						
					</div>
					<div class="span3"></div>
				</div>
				<!-- ////////////////////////////////// FIN - TABLA ESTADO PROCESO Y DOCUMENTO /////////////////////////////////////// -->
				
				
				
				<!-- ////////////////////////////////// INICIO CAMPOS FORMULARIOS MOVIMIENTO /////////////////////////////////////// -->
				<?php

				echo $this->BootstrapForm->input('movement_hidden', array(
					'id'=>'movement_hidden',
					'value'=>$id,
					'type'=>'hidden'
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
					'helpInline' => '<a class="btn btn-primary" href="#" id="addMovementType" title="Nuevo Tipo Movimiento"><i class="icon-plus icon-white"></i></a><span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('description', array(
					'rows' => 2,
					'style'=>'width:400px',
					'label' => 'Descripción:',
					'id'=>'description'
				));
				?>
				<!-- ////////////////////////////////// FIN CAMPOS FORMULARIOS MOVIMIENTO /////////////////////////////////////// -->
				
				
				
				<!-- ////////////////////////////////// INICIO - ITEMS /////////////////////////////////////// -->
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#">Items</a>
					</li>
				</ul>
				

				<div class="row-fluid">
					
					<div class="span1"></div>
					
					<div id="boxTable" class="span8">
						
						<?php if($documentState <> 'CANCELLED'){ ?>
						<a class="btn btn-primary" href='#' id="btnAddItem" title="Adicionar Item"><i class="icon-plus icon-white"></i></a>
						<?php } ?>
						<p></p>
						
						<table class="table table-bordered table-condensed table-striped" id="tablaItems">
							<thead>
								<tr>
									<th>Item</th>
									<th>Stock</th>
									<th>Cantidad</th>
									<?php if($documentState <> 'CANCELLED'){ ?>
									<th></th>
									<?php }?>
								</tr>
							</thead>
							<tbody>
								<?php
								for($i=0; $i<count($invMovementDetails); $i++){
									echo '<tr>';
										echo '<td>'.$invMovementDetails[$i]['item'].'<input type="hidden" value="'.$invMovementDetails[$i]['itemId'].'" id="item_hidden" ></td>';
										echo '<td><span id="stock_hidden'.$invMovementDetails[$i]['itemId'].'">'.$invMovementDetails[$i]['stock'].'</span></td>';
										echo '<td><span id="quantity_hidden'.$invMovementDetails[$i]['itemId'].'">'.$invMovementDetails[$i]['cantidad'].'</span></td>';
										if($documentState <> 'CANCELLED'){
											echo '<td>';
											echo '<a class="btn btn-primary" href="#" id="btnEditItem'.$invMovementDetails[$i]['itemId'].'" title="Editar"><i class="icon-pencil icon-white"></i></a>
												
												<a class="btn btn-danger" href="#" id="btnDeleteItem'.$invMovementDetails[$i]['itemId'].'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
											echo '</td>';
										}
									echo '</tr>';								
								}
								?>
							</tbody>
						</table>
					</div>
					
					<div class="span3"></div>
					
				</div>
			<!-- ////////////////////////////////// FIN ITEMS /////////////////////////////////////// -->

				
			<!-- ////////////////////////////////// INICIO BOTONES /////////////////////////////////////// -->
			<div class="form-actions">

				<div class="btn-toolbar">
						<?php 
							if($documentState <> 'CANCELLED'){
								echo $this->BootstrapForm->submit('Guardar Cambios',array('class'=>'btn btn-primary','div'=>false, 'id'=>'btnSaveAll'));	
								
							}
							echo $this->Html->link('Cancelar', array('action'=>'index_in'), array('class'=>'btn') );
						?>

						<?php 
							switch ($documentState){
										case '':
											$displayApproved = 'none';
											$displayCancelled = 'none';
											break;
										case 'PENDANT':
											$displayApproved = 'inline';
											$displayCancelled = 'none';
											break;
										case 'APPROVED':
											$displayApproved = 'none';
											$displayCancelled = 'inline';
											break;
										case 'CANCELLED':
											$displayApproved = 'none';
											$displayCancelled = 'none';
											break;
									}
						?>

						<a href="#" id="btnApproveState" class="btn btn-success" style="display:<?php echo $displayApproved;?>"> Aprobar Entrada Almacen</a>
						<a href="#" id="btnCancellState" class="btn btn-danger" style="display:<?php echo $displayCancelled;?>"> Cancelar Entrada Almacen</a>
				</div>
			
			</div>
			<!-- ////////////////////////////////// FIN BOTONES /////////////////////////////////////// -->
	
			
	<!-- ////////////////////////////////// INICIO - FIN FORM ///////////////////////////////////// -->		
	</fieldset>
	<?php echo $this->BootstrapForm->end();?>
	<!-- ////////////////////////////////// FIN - FIN FORM ///////////////////////////////////// -->
	
	
	<!-- ////////////////////////////////// INICIO MENSAJES /////////////////////////////////////// -->
	<div id="processing"></div>
	<div id="boxMessage"></div>
	<!-- ////////////////////////////////// FIN MENSAJES /////////////////////////////////////// -->
	
	
<!-- ************************************************************************************************************************ -->
</div><!-- FIN CONTAINER FLUID/ROW FLUID/SPAN9 - Del Template Principal (SPAN3 reservado para menu izquierdo) -->
<!-- ************************************************************************************************************************ -->




<!-- ////////////////////////////////// INICIO MODAL (Esta fuera del span9 pero sigue pertenciendo al template principal CONTAINER FLUID/ROW FLUID) ////////////////////////////// -->
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
<!-- ////////////////////////////////// FIN MODAL (Esta fuera del span9 pero sigue pertenciendo al template principal CONTAINER FLUID/ROW FLUID) ////////////////////////////// -->