<?php echo $this->Html->script('modules/SalSales', FALSE); ?>


<!-- ************************************************************************************************************************ -->
<div class="span12"><!-- START CONTAINER FLUID/ROW FLUID/SPAN12 - FORMATO DE #UNICORN -->
<!-- ************************************************************************************************************************ -->
	<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 1/2 *************************************** -->
						<?php
							switch ($documentState){
								case '':
									$documentStateColor = '';
									$documentStateName = 'SIN ESTADO';
									break;
								case 'ORDER_PENDANT':
									$documentStateColor = 'label-warning';
									$documentStateName = 'NOTA PENDIENTE';
									break;
								case 'ORDER_APPROVED':
									$documentStateColor = 'label-success';
									$documentStateName = 'NOTA APROBADA';
									break;
								case 'ORDER_CANCELLED':
									$documentStateColor = 'label-important';
									$documentStateName = 'NOTA CANCELADA';
									break;
							}
						?>
<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="icon-edit"></i>								
			</span>
			<h5>Nota de Venta</h5>
			<span id="documentState" class="label <?php echo $documentStateColor;?>"><?php echo $documentStateName;?></span>
		</div>
		<div class="widget-content nopadding">
		<!-- //////////////////////////// START - IF NEEDED BREADCRUMB, SHOW PROCESS STATE //////////////////////// -->
		<!--
		<div id="breadcrumb">
			<a href="#" title="Go to Home" class="tip-bottom">Orden Compra</a>
			<a href="#" class="current">Remito</a>
		</div>
		-->
		<!-- //////////////////////////// END - IF NEEDED BREADCRUMB, SHOW PROCESS STATE //////////////////////// -->
	<!-- //******************************** END - #UNICORN  WRAP FORM BOX PART 1/2 *************************************** -->

	
	<!-- ////////////////////////////////// INICIO - INICIO FORM ///////////////////////////////////// -->
		<?php echo $this->BootstrapForm->create('SalSale', array('class' => 'form-horizontal'));?>
		<fieldset>
	<!--	<legend><?php echo __('Nota de Venta'); ?></legend> -->
	<!-- ////////////////////////////////// FIN - INICIO FORM /////////////////////////////////////// -->			
				
				
				<!-- ////////////////////////////////// INICIO - TABLA ESTADO PROCESO Y DOCUMENTO /////////////////////////////////////// -->
				<!--
				<div class="row-fluid">
					<div class="span7">
						
					</div>
					<div class="span2" >
						Estado Documento:
						
						<table id="tableProcessState" class="table table-bordered table-condensed">
							<tr>
								<td id="columnStatePurchase" style="background-color:<?php echo $stateColor; ?>; color: white"><?php echo $stateName;?></td>
							</tr>
						</table>
						
					</div>
					<div class="span3"></div>
				</div>
				-->
				<!-- ////////////////////////////////// FIN - TABLA ESTADO PROCESO Y DOCUMENTO /////////////////////////////////////// -->
				
				
				
				<!-- ////////////////////////////////// INICIO CAMPOS FORMULARIOS ORDEN COMPRA /////////////////////////////////////// -->
				<?php
				
				//////////////////////////////////START - block when APPROVED or CANCELLED///////////////////////////////////////////////////
				$disable = 'disabled';
				$rate_disable = 'disabled';
//				$supplier_disable = 'disabled';
//				$btnAddMovementType = '';
//				
				if($documentState == 'ORDER_PENDANT'){
					$disable = 'enabled';	
					$rate_disable = 'enabled';
//					$supplier_disable = 'disabled';
//					$btnAddMovementType = '<a class="btn btn-primary" href="#" id="btnAddMovementType" title="Nuevo Tipo Movimiento"><i class="icon-plus icon-white"></i></a>';
				}
				if($documentState == ''){
					$disable = 'enabled';
//					$supplier_disable = 'enabled';
				}
				
				//////////////////////////////////END - block when APPROVED or CANCELLED///////////////////////////////////////////////////
				/*
				echo $this->BootstrapForm->input('token_status_hidden', array(
					'id'=>'txtTokenStatusHidden',
					'value'=>'entrada',
					'type'=>'hidden'
				));
				*/
				echo $this->BootstrapForm->input('purchase_hidden', array(
					//'id'=>'movement_hidden',
					'id'=>'txtPurchaseIdHidden',
					'value'=>$id,
					'type'=>'hidden'
				));
							
				echo $this->BootstrapForm->input('doc_code', array(
					//'id'=>'code',
					'id'=>'txtCode',
					'label'=>'Código:',
					'style'=>'background-color:#EEEEEE',
					'disabled'=>$disable,
					'placeholder'=>'El sistema generará el código',
					//'data-toggle'=>'tooltip',
					//'data-placement'=>'top',
				));
				
				echo $this->BootstrapForm->input('generic_code', array(
					'id'=>'txtGenericCode',
					'value'=>$genericCode,
					'type'=>'hidden'
				
				));
				
		echo $this->BootstrapForm->input('note_code', array(
			'id'=>'txtNoteCode',
	//		'value'=>$noteCode,
			'label' => 'No. Nota de Remision:',
			//'type'=>'hidden'

		));
				
				echo $this->BootstrapForm->input('date_in', array(
					'required' => 'required',
					'label' => 'Fecha:',
					'id'=>'txtDate',
					'value'=>$date,
					'disabled'=>$disable,
					'maxlength'=>'0',
		//			'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
				));
				
				
				
				echo $this->BootstrapForm->input('sal_customer_id', array(
					'required' => 'required',
					'label' => 'Cliente:',
/*js*/				'id'=>'cbxCustomers',
					//'value'=>$invWarehouses,
					'selected' => $customerId,
					
					'disabled'=>$disable
		//			'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
				));
				
			echo '<div id="boxControllers">';
				echo $this->BootstrapForm->input('sal_employee_id', array(
					'required' => 'required',
					'label' => 'Encargado:',
/*js*/				'id'=>'cbxEmployees'
				//	'selected' => 207
					//'value'=>$invWarehouses,
//					'disabled'=>$disable,
		//			'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
				));
			

				echo $this->BootstrapForm->input('sal_tax_number_id', array(
					'required' => 'required',
					'label' => 'NIT - Nombre:',
/*js*/				'id'=>'cbxTaxNumbers',
					//'value'=>$invWarehouses,
					'disabled'=>$disable,
		//			'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
				));
			echo '</div>';
			
				echo $this->BootstrapForm->input('sal_adm_user_id', array(
					'required' => 'required',
					'label' => 'Vendedor:',
/*js*/				'id'=>'cbxSalesman',
					//'value'=>$invWarehouses,
					'selected' => $admUserId,
					'disabled'=>$disable
		//			'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
				));

//				echo $this->BootstrapForm->input('inv_movement_type_id', array(
//					'label' => 'Tipo Movimiento:',
//					'id'=>'cbxMovementTypes',
//					'disabled'=>$disable,
//					'required' => 'required',
//					'helpInline' => /*$btnAddMovementType.*/'<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;')
//				);
				
				echo $this->BootstrapForm->input('description', array(
					'rows' => 2,
					//'style'=>'width:400px',//#UNICORN, COMMENT OR REPONSIVE DOESN'T WORK
					'label' => 'Descripción:',
					'disabled'=>$disable,
					'id'=>'txtDescription'
				));
				
				echo $this->BootstrapForm->input('ex_rate', array(
					'label' => 'Tipo de Cambio:',
					'value'=>$exRate,
				//	'placeholder'=>'El sistema generará el código',
					'disabled'=>$rate_disable,
				//	'type' => $type,
					'id'=>'txtExRate'
				));
				
				?>
				<!-- ////////////////////////////////// FIN CAMPOS FORMULARIOS MOVIMIENTO /////////////////////////////////////// -->
				
				
				
						<!-- ////////////////////////////////// INICIO - ITEMS /////////////////////////////////////// -->
			<!--	<ul class="nav nav-tabs">
					<li class="active">
						<a href="#">Items</a>
					</li>
				</ul> -->
				

				<div class="row-fluid">
			<!--		
					<div class="span1"></div>
					
					<div id="boxTable" class="span8">-->
						
						<?php if($documentState == 'ORDER_PENDANT' OR $documentState == ''){ ?>
						<a class="btn btn-primary" href='#' id="btnAddItem" title="Adicionar Item"><i class="icon-plus icon-white"></i></a>
						<?php } ?>
						<p></p>
						
						<table class="table table-bordered table-condensed table-striped table-hover" id="tablaItems">
							<thead>
								<tr>
									<th>Item</th>
<!--									<th>Stock</th>-->
									<th>Precio Unitario</th>
									<th>Cantidad</th>
									<th>Almacen</th>
									<th>Stock</th>
									<th>Subtotal</th>
									<?php if($documentState == 'ORDER_PENDANT' OR $documentState == ''){ ?>
									<th class="columnItemsButtons"></th>
									<?php }?>
								</tr>
							</thead>
							<tbody>
								<?php
								$total = 0;
								for($i=0; $i<count($salDetails); $i++){
									$subtotal = ($salDetails[$i]['cantidad'])*($salDetails[$i]['price']);
									echo '<tr>';																							//type="hidden" txtWarehouseId
										echo '<td><span id="spaItemName'.$salDetails[$i]['itemId'].'">'.$salDetails[$i]['item'].'</span><input type="hidden" value="'.$salDetails[$i]['itemId'].'" id="txtItemId" ></td>';
										echo '<td><span id="spaPrice'.$salDetails[$i]['itemId'].'">'.$salDetails[$i]['price'].'</span></td>';
										echo '<td><span id="spaQuantity'.$salDetails[$i]['itemId'].'">'.$salDetails[$i]['cantidad'].'</span></td>';
								echo '<td><span id="spaWarehouse'.$salDetails[$i]['itemId'].'">'.$salDetails[$i]['warehouse'].'</span><input type="hidden" value="'.$salDetails[$i]['warehouseId'].'" id="txtWarehouseId'.$salDetails[$i]['itemId'].'" ></td>';
								echo '<td><span id="spaStock'.$salDetails[$i]['itemId'].'">'.$salDetails[$i]['stock'].'</span></td>';
										echo '<td><span id="spaSubtotal'.$salDetails[$i]['itemId'].'">'.$subtotal.'</span></td>';
										if($documentState == 'ORDER_PENDANT' OR $documentState == ''){
											echo '<td class="columnItemsButtons">';
											echo '<a class="btn btn-primary" href="#" id="btnEditItem'.$salDetails[$i]['itemId'].'" title="Editar"><i class="icon-pencil icon-white"></i></a>
												
												<a class="btn btn-danger" href="#" id="btnDeleteItem'.$salDetails[$i]['itemId'].'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
											echo '</td>';
										}
									echo '</tr>';	
									$total += $subtotal;
								}
//								echo '<tr>';
//										echo '<th></th>';
//										echo '<th></th>';
//										echo '<th>Total:</th>';
//										echo '<th>'.$total.'</th>';
//									echo '</tr>';
								?>
<!--								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>-->
							</tbody>
							
						</table>
<!--						
					<table class="table table-condensed table-striped table-hover">
						<thead>
								<tr>
									<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
									<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
									<th>Total:</th>
									<th><?php echo $total; ?></th>
								</tr>
							</thead>
					</table>
						
					</div>
					
					<div class="span3"></div>
					 -->
				</div>
			<!-- ////////////////////////////////// FIN ITEMS /////////////////////////////////////// -->

<p></p>	
				
			<!-- ////////////////////////////////// INICIO BOTONES /////////////////////////////////////// -->
			<!--<div class="form-actions">--><!-- no sirve se desconfigura los botones en modo tablet -->
			<div class="row-fluid"> <!-- INICIO - row fluid para alinear los botones -->
				<div class="span2"></div> <!-- INICIO Y FIN - ESPACIO A LA IZQUIERDA -->
				<div class="span6">	<!-- INICIO - span 6 -->
					<div class="btn-toolbar"> <!-- INICIO - toolbar para dejar espacio entre botones -->
							<?php 
								if($documentState == 'ORDER_PENDANT' OR $documentState == ''){
									echo $this->BootstrapForm->submit('Guardar Cambios',array('class'=>'btn btn-primary','div'=>false, 'id'=>'btnSaveAll'));	

								}
								/////////////////START - SETTINGS BUTTON CANCEL /////////////////
								$url=array('action'=>'index_order');
								$parameters = $this->passedArgs;
								if(!isset($parameters['search'])){
//									unset($parameters['document_code']);
									unset($parameters['code']);
								}
								unset($parameters['id']);
								echo $this->Html->link('Cancelar', array_merge($url,$parameters), array('class'=>'btn') );
								//////////////////END - SETTINGS BUTTON CANCEL /////////////////
							?>

							<?php 
								switch ($documentState){
											case '':
												$displayApproved = 'none';
												$displayCancelled = 'none';
												$displayLogicDelete = 'none';
												break;
											case 'ORDER_PENDANT':
												$displayApproved = 'inline';
												$displayCancelled = 'none';
												$displayLogicDelete = 'inline';
												break;
											case 'ORDER_APPROVED':
												$displayApproved = 'none';
												$displayCancelled = 'inline';
												$displayLogicDelete = 'none';
												break;
											case 'ORDER_CANCELLED':
												$displayApproved = 'none';
												$displayCancelled = 'none';
												$displayLogicDelete = 'none';
												break;
										}
							?>
							<?php
								$displayPrint = 'none';
								if($id <> ''){
									$displayPrint = 'inline';
								}
								echo $this->Html->link('<i class="icon-print icon-white"></i> Imprimir', array('action' => 'view_document_movement_pdf', $id.'.pdf'), array('class'=>'btn btn-primary','style'=>'display:'.$displayPrint, 'escape'=>false, 'title'=>'Nuevo', 'id'=>'btnPrint', 'target'=>'_blank')); 
								
							?>
							<a href="#" id="btnApproveState" class="btn btn-success" style="display:<?php echo $displayApproved;?>"> Aprobar Nota de Venta</a>
							<a href="#" id="btnCancellState" class="btn btn-danger" style="display:<?php echo $displayCancelled;?>"> Cancelar Nota de Venta</a>
							<a href="#" id="btnLogicDeleteState" class="btn btn-danger" style="display:<?php echo $displayLogicDelete;?>"> Logic Delete</a>

					</div> <!-- FIN - toolbar para dejar espacio entre botones -->
				</div> <!-- FIN - span 6 -->
				<div class="span4"></div> <!-- INICIO Y FIN - ESPACIO A LA DERECHA PARA NO DEJAR HUECOS -->
			</div> <!-- FIN - row fluid para alinear los botones -->
			<!--</div>--><!-- no sirve se desconfigura los botones en modo tablet class="form-actions" -->
			<!-- ////////////////////////////////// FIN BOTONES /////////////////////////////////////// -->

	<!-- ////////////////////////////////// INICIO - FIN FORM ///////////////////////////////////// -->		
	</fieldset>
	<?php echo $this->BootstrapForm->end();?>
	<!-- ////////////////////////////////// FIN - FIN FORM ///////////////////////////////////// -->
	
	<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
		</div> <!-- Belongs to: <div class="widget-content nopadding"> -->
	</div> <!-- Belongs to: <div class="widget-box"> -->
	<!-- //******************************** END - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->

	<!-- ////////////////////////////////// INICIO MENSAJES /////////////////////////////////////// -->
	<div id="boxMessage"></div>
	<div id="processing"></div>
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
					echo '<div id="boxModalInitiateItemPrice">';
						//////////////////////////////////////

						echo $this->BootstrapForm->input('items_id', array(				
						'label' => 'Item:',
						'id'=>'cbxModalItems',
						'class'=>'input-xlarge',
						'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
						));
						echo '<br>';
						$price='';
						echo '<div id="boxModalPrice">';
							echo $this->BootstrapForm->input('price', array(				
							'label' => 'P/U s_o:',
							'id'=>'txtModalPrice',
							'value'=>$price,
							'style'=>'background-color:#EEEEEE',
							'class'=>'input-small',
							'maxlength'=>'15'
							));

						echo '</div>';		
						echo '<br>';
						//////////////////////////////////////
						////////////////////////////////////////
				//		echo '<div id="boxModalInitiateWarehouseStock">';
						//////////////////////////////////////
		
						echo $this->BootstrapForm->input('inv_warehouse_id', array(				
						'label' => 'Almacén:',
						'id'=>'cbxModalWarehouses',
						'class'=>'input-xlarge',
		//				'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
						));
						echo '<br>';
						$stock='';
						echo '<div id="boxModalStock">';
							echo $this->BootstrapForm->input('stock', array(				
							'label' => 'Stock:',
							'id'=>'txtModalStock',
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
					'id'=>'txtModalQuantity',
					'class'=>'input-small',
					//'value'=>'6',
					'maxlength'=>'10',
		//			'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
					));
					?>
					  <div id="boxModalValidateItem" class="alert-error"></div> 
				  </div>
				  
				  <div class="modal-footer">
					 <!-- Ztep 0 Save button from modal triggers btnModalAddItem -->
					<a href='#' class="btn btn-primary" id="btnModalAddItem">Guardar add</a>
					<a href='#' class="btn btn-primary" id="btnModalEditItem">Guardar edit</a>
					<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
					
				  </div>
					
			</div>
<!-- ////////////////////////////////// FIN MODAL (Esta fuera del span9 pero sigue pertenciendo al template principal CONTAINER FLUID/ROW FLUID) ////////////////////////////// -->