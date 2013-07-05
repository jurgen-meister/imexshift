<?php echo $this->Html->script('modules/PurPurchases', FALSE); ?>


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
								case 'INVOICE_PENDANT':
									$documentStateColor = 'label-warning';
									$documentStateName = 'FACTURA PENDIENTE';
									break;
								case 'INVOICE_APPROVED':
									$documentStateColor = 'label-success';
									$documentStateName = 'FACTURA APROBADA';
									break;
								case 'INVOICE_CANCELLED':
									$documentStateColor = 'label-important';
									$documentStateName = 'FACTURA CANCELADA';
									break;
							}
						?>
<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="icon-edit"></i>								
			</span>
			<h5>Factura de Compra</h5>
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
		<?php echo $this->BootstrapForm->create('PurPurchase', array('class' => 'form-horizontal'));?>
		<fieldset>
	<!--	<legend><?php echo __('Factura de Compra'); ?></legend>-->
	<!-- ////////////////////////////////// FIN - INICIO FORM /////////////////////////////////////// -->			
				
				
				<!-- ////////////////////////////////// INICIO - TABLA ESTADO PROCESO Y DOCUMENTO /////////////////////////////////////// -->
		<!--		<div class="row-fluid">
					<div class="span7">
						
					</div>
					<div class="span2" >
						Estado Documento:
						<?php
							switch ($documentState){
								case '':
									$stateColor = '#BBBBBB';
									$stateName = 'Sin estado';
									break;
								case 'INVOICE_PENDANT':
									$stateColor = '#F99C17';
									$stateName = 'Factura Pendiente';
									break;
								case 'INVOICE_APPROVED':
									$stateColor = '#54AA54';
									$stateName = 'Factura Aprobada';
									break;
								case 'INVOICE_CANCELLED':
									$stateColor = '#BD362F';
									$stateName = 'Factura Cancelada';
									break;
							}
						?>
						<table id="tableProcessState" class="table table-bordered table-condensed">
							<tr>
								<td id="columnStatePurchase" style="background-color:<?php echo $stateColor; ?>; color: white"><?php echo $stateName;?></td>
							</tr>
						</table>
						
					</div>
					<div class="span3"></div>
				</div> -->
				<!-- ////////////////////////////////// FIN - TABLA ESTADO PROCESO Y DOCUMENTO /////////////////////////////////////// -->
				
				
				
				<!-- ////////////////////////////////// INICIO CAMPOS FORMULARIOS ORDEN COMPRA /////////////////////////////////////// -->
				<?php
				
				//////////////////////////////////START - block when APPROVED or CANCELLED///////////////////////////////////////////////////
				$disable = 'disabled';
				$disable2 = 'disabled';
//				$btnAddMovementType = '';
//				
				if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){
					$disable = 'enabled';	
//					$btnAddMovementType = '<a class="btn btn-primary" href="#" id="btnAddMovementType" title="Nuevo Tipo Movimiento"><i class="icon-plus icon-white"></i></a>';
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
					'disabled'=>$disable2,
					'placeholder'=>'El sistema generará el código',
					//'data-toggle'=>'tooltip',
					//'data-placement'=>'top',
				));
				
				echo $this->BootstrapForm->input('origin_code', array(
					//'id'=>'code',
/*copiar a Purchases.js*/		'id'=>'txtOriginCode',
					'label'=>'Documento Origen:',
					'style'=>'background-color:#EEEEEE',
					'disabled'=>$disable2,
					'value'=>$originCode,
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
			'label' => 'No. Factura Compra:',
			//'type'=>'hidden'

		));
				
				echo $this->BootstrapForm->input('date_in', array(
					'required' => 'required',
					'label' => 'Fecha:',
					'id'=>'txtDate',
					'value'=>$date,
					'disabled'=>$disable,
					'maxlength'=>'0',
//					'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
				));
				
				
				
				echo $this->BootstrapForm->input('inv_supplier_id', array(
					'required' => 'required',
					'label' => 'Proveedor:',
/*js*/				'id'=>'cbxSuppliers',
					//'value'=>$invWarehouses,
					'disabled'=>$disable2,
	//				'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
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
				
				
				?>
				<!-- ////////////////////////////////// FIN CAMPOS FORMULARIOS MOVIMIENTO /////////////////////////////////////// -->
				
				
				
						<!-- ////////////////////////////////// INICIO - ITEMS /////////////////////////////////////// -->
		<!-- 		<ul class="nav nav-tabs">
					<li class="active">
						<a href="#">Items</a>
					</li>
				</ul> -->
				

				<div class="row-fluid">
					
		<!-- 			<div class="span1"></div>
					
					<div id="boxTable" class="span8"> -->
						
						<?php if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){ ?>
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
									<th>Subtotal</th>
									<?php if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){ ?>
									<th class="columnItemsButtons"></th>
									<?php }?>
								</tr>
							</thead>
							<tbody>
								<?php
								$total = 0;
								for($i=0; $i<count($purDetails); $i++){
									$subtotal = ($purDetails[$i]['cantidad'])*($purDetails[$i]['price']);
									echo '<tr>';
										echo '<td><span id="spaItemName'.$purDetails[$i]['itemId'].'">'.$purDetails[$i]['item'].'</span><input type="hidden" value="'.$purDetails[$i]['itemId'].'" id="txtItemId" ></td>';
										echo '<td><span id="spaPrice'.$purDetails[$i]['itemId'].'">'.$purDetails[$i]['price'].'</span></td>';
										echo '<td><span id="spaQuantity'.$purDetails[$i]['itemId'].'">'.$purDetails[$i]['cantidad'].'</span></td>';
										echo '<td><span id="spaSubtotal'.$purDetails[$i]['itemId'].'">'.$subtotal.'</span></td>';
										if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){
											echo '<td class="columnItemsButtons">';
											echo '<a class="btn btn-primary" href="#" id="btnEditItem'.$purDetails[$i]['itemId'].'" title="Editar"><i class="icon-pencil icon-white"></i></a>
												
												<a class="btn btn-danger" href="#" id="btnDeleteItem'.$purDetails[$i]['itemId'].'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
											echo '</td>';
										}
									echo '</tr>';	
									$total += $subtotal;
								}
//								echo '<tr>';
//										echo '<th></th>';
//										echo '<th></th>';
//										echo '<th>Total:</th>';
//										echo '<th>'.$xxxtotal.'</th>';
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
	<!--				<table class="table table-condensed table-striped table-hover">
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
<!-- ////////////////////////////////// INICIO - COSTO /////////////////////////////////////// -->
	<!--			<ul class="nav nav-tabs">
					<li class="active">
						<a href="#">Costos Adicionales de Importación</a>
					</li>
				</ul>-->
				

				<div class="row-fluid">
					
			<!--		<div class="span1"></div>
					
					<div id="boxTable" class="span8">-->
						
						<?php if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){ ?>
						<a class="btn btn-primary" href='#' id="btnAddCost" title="Adicionar Costo"><i class="icon-plus icon-white"></i></a>
						<?php } ?>
						<p></p>
						
						<table class="table table-bordered table-condensed table-striped table-hover" id="tablaCosts">
							<thead>
								<tr>
									<th>Costos Adicionales de Importación</th>
<!--									<th>Stock</th>-->
									<th>Momto</th>
									
									<?php if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){ ?>
									<th class="columnCostsButtons"></th>
									<?php }?>
								</tr>
							</thead>
							<tbody>
								<?php
								$total = 0;
								for($i=0; $i<count($purPrices); $i++){
								//	$subtotal = ($purPrices[$i]['cantidad'])*($purPrices[$i]['price']);
									echo '<tr>';
										echo '<td><span id="spaCostName'.$purPrices[$i]['costId'].'">'.$purPrices[$i]['cost'].'</span><input type="hidden" value="'.$purPrices[$i]['costId'].'" id="txtCostId" ></td>';
										echo '<td><span id="spaAmount'.$purPrices[$i]['costId'].'">'.$purPrices[$i]['amount'].'</span></td>';
										if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){
											echo '<td class="columnCostsButtons">';
											echo '<a class="btn btn-primary" href="#" id="btnEditCost'.$purPrices[$i]['costId'].'" title="Editar"><i class="icon-pencil icon-white"></i></a>
												
												<a class="btn btn-danger" href="#" id="btnDeleteCost'.$purPrices[$i]['costId'].'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
											echo '</td>';
										}
									echo '</tr>';	
									$total += $subtotal;
								}
//								echo '<tr>';
//										echo '<th></th>';
//										echo '<th></th>';
//										echo '<th>Total:</th>';
//										echo '<th>'.$xxxtotal.'</th>';
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
		<!--			<table class="table table-condensed table-striped table-hover">
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
			<!-- ////////////////////////////////// FIN COSTO /////////////////////////////////////// -->
				
			<!-- ////////////////////////////////// INICIO - PAGO /////////////////////////////////////// -->
	<!--			<ul class="nav nav-tabs">
					<li class="active">
						<a href="#">Costos Adicionales de Importación</a>
					</li>
				</ul>-->
				

				<div class="row-fluid">
					
			<!--		<div class="span1"></div>
					
					<div id="boxTable" class="span8">-->
						
						<?php if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){ ?>
						<a class="btn btn-primary" href='#' id="btnAddPay" title="Adicionar Pago"><i class="icon-plus icon-white"></i></a>
						<?php } ?>
						<p></p>
						
						<table class="table table-bordered table-condensed table-striped table-hover" id="tablaPays">
							<thead>
								<tr>
									<th>Pago</th>
									<th>Fecha Pago</th>
									<th>Fecha Limite</th>
									<th>Monto</th>
									<th>Deuda</th>
									<th>Descripcion</th>
									<th>Estado</th>
									<?php if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){ ?>
									<th class="columnPaysButtons"></th>
									<?php }?>
								</tr>
							</thead>
							<tbody>
								<?php
								$total = 0;
								$debtAmount = 0;
								for($i=0; $i<count($purPayments); $i++){
								//	$subtotal = ($purPrices[$i]['cantidad'])*($purPrices[$i]['price']);
									echo '<tr>';
										echo '<td><span id="spaPayName'.$purPayments[$i]['payId'].'">'.$purPayments[$i]['pay'].'</span><input type="hidden" value="'.$purPayments[$i]['payId'].'" id="txtPayId" ></td>';
										echo '<td><span id="spaDate'.$purPayments[$i]['payId'].'">'.$purPayments[$i]['date'].'</span></td>';
										echo '<td><span id="spaDueDate'.$purPayments[$i]['payId'].'">'.$purPayments[$i]['dueDate'].'</span></td>';
										echo '<td><span id="spaPaidAmount'.$purPayments[$i]['payId'].'">'.$purPayments[$i]['paidAmount'].'</span></td>';
/*calculado ---> */						echo '<td><span id="spaDebtAmount'.$purPayments[$i]['payId'].'">'.$debtAmount.'</span></td>';
										echo '<td><span id="spaDescription'.$purPayments[$i]['payId'].'">'.$purPayments[$i]['description'].'</span></td>';
										echo '<td><span id="spaState'.$purPayments[$i]['payId'].'">'.$purPayments[$i]['state'].'</span></td>';
										
										if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){
											echo '<td class="columnPaysButtons">';
											echo '<a class="btn btn-primary" href="#" id="btnEditPay'.$purPayments[$i]['payId'].'" title="Editar"><i class="icon-pencil icon-white"></i></a>
												
												<a class="btn btn-danger" href="#" id="btnDeletePay'.$purPayments[$i]['payId'].'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
											echo '</td>';
										}
									echo '</tr>';	
									$total += $subtotal;
								}
//								echo '<tr>';
//										echo '<th></th>';
//										echo '<th></th>';
//										echo '<th>Total:</th>';
//										echo '<th>'.$xxxtotal.'</th>';
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
		<!--			<table class="table table-condensed table-striped table-hover">
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
			<!-- ////////////////////////////////// FIN PAGO /////////////////////////////////////// -->
			
			<!-- ////////////////////////////////// INICIO BOTONES /////////////////////////////////////// -->
			<!--<div class="form-actions">--><!-- no sirve se desconfigura los botones en modo tablet -->
			<div class="row-fluid"> <!-- INICIO - row fluid para alinear los botones -->
				<div class="span2"></div> <!-- INICIO Y FIN - ESPACIO A LA IZQUIERDA -->
				<div class="span6">	<!-- INICIO - span 6 -->
					<div class="btn-toolbar"> <!-- INICIO - toolbar para dejar espacio entre botones -->
							<?php 
								if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){
									echo $this->BootstrapForm->submit('Guardar Cambios',array('class'=>'btn btn-primary','div'=>false, 'id'=>'btnSaveAll'));	

								}
								/////////////////START - SETTINGS BUTTON CANCEL /////////////////
								$url=array('action'=>'index_invoice');
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
											case 'INVOICE_PENDANT':
												$displayApproved = 'inline';
												$displayCancelled = 'none';
												$displayLogicDelete = 'inline';
												break;
											case 'INVOICE_APPROVED':
												$displayApproved = 'none';
												$displayCancelled = 'inline';
												$displayLogicDelete = 'none';
												break;
											case 'INVOICE_CANCELLED':
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
							<a href="#" id="btnApproveState" class="btn btn-success" style="display:<?php echo $displayApproved;?>"> Aprobar Factura de Compra</a>
							<a href="#" id="btnCancellState" class="btn btn-danger" style="display:<?php echo $displayCancelled;?>"> Cancelar Factura de Compra</a>
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
					echo '</div>';

					echo $this->BootstrapForm->input('quantity', array(				
					'label' => 'Cantidad:',
					'id'=>'txtModalQuantity',
					'class'=>'input-small',
					//'value'=>'6',
					'maxlength'=>'10',
					'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
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




<!-- ////////////////////////////////// INICIO MODAL COSTS (Esta fuera del span9 pero sigue pertenciendo al template principal CONTAINER FLUID/ROW FLUID) ////////////////////////////// -->
			<div id="modalAddCost" class="modal hide fade ">
				  
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h3 id="myModalLabel">Montos</h3>
				  </div>
				  
				  <div class="modal-body form-horizontal">
					<!--<p>One fine body…</p>-->
					<?php
					echo '<div id="boxModalInitiateCost">';
						//////////////////////////////////////

						echo $this->BootstrapForm->input('costs_id', array(				
						'label' => 'Costo:',
						'id'=>'cbxModalCosts',
						'class'=>'input-xlarge',
				//		'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
						));
						echo '<br>';
//						$amount='';
//						echo '<div id="boxModalAmount">';
							

//						echo '</div>';		
//						echo '<br>';

						//////////////////////////////////////
					echo '</div>';
					echo $this->BootstrapForm->input('amount', array(				
							'label' => 'Montito:',
							'id'=>'txtModalAmount',
						//	'value'=>$amount,
							'style'=>'background-color:#EEEEEE',
							'class'=>'input-small',
							'maxlength'=>'15'
							));
//					echo $this->BootstrapForm->input('quantity', array(				
//					'label' => 'Cantidad:',
//					'id'=>'txtModalQuantity',
//					'class'=>'input-small',
//					//'value'=>'6',
//					'maxlength'=>'10',
//					'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
//					));
					?>
					  <div id="boxModalValidateCost" class="alert-error"></div> 
				  </div>
				  
				  <div class="modal-footer">
					 <!-- Ztep 0 Save button from modal triggers btnModalAddItem -->
					<a href='#' class="btn btn-primary" id="btnModalAddCost">Guardar add cost</a>
					<a href='#' class="btn btn-primary" id="btnModalEditCost">Guardar edit cost</a>
					<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
					
				  </div>
					
			</div>
<!-- ////////////////////////////////// FIN MODAL COSTS(Esta fuera del span9 pero sigue pertenciendo al template principal CONTAINER FLUID/ROW FLUID) ////////////////////////////// -->

<!-- ////////////////////////////////// INICIO MODAL PAYS(Esta fuera del span9 pero sigue pertenciendo al template principal CONTAINER FLUID/ROW FLUID) ////////////////////////////// -->
			<div id="modalAddPay" class="modal hide fade ">
				  
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h3 id="myModalLabel">Pagos</h3>
				  </div>
				  
				  <div class="modal-body form-horizontal">
					<!--<p>One fine body…</p>-->
					<?php
					echo '<div id="boxModalInitiatePay">';
						//////////////////////////////////////

						echo $this->BootstrapForm->input('pays_id', array(				
							'label' => 'Pagos:',
							'id'=>'cbxModalPays',
							'class'=>'input-xlarge',
					//		'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
							));
						echo '<br>';
//						$amount='';
//						echo '<div id="boxModalAmount">';
							

//						echo '</div>';		
//						echo '<br>';

						//////////////////////////////////////
//						echo $this->BootstrapForm->input('date_in', array(
//					'required' => 'required',
//					'label' => 'Fecha:',
//					'id'=>'txtDate',
//					'value'=>$date,
//					'disabled'=>$disable,
//					'maxlength'=>'0',
////					'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
//				));
						
					echo '</div>';
					echo $this->BootstrapForm->input('date', array(		
					//		'required' => 'required',
							'label' => 'Fecha Pago:',
							'id'=>'txtModalDate',
							));
					echo '<br>';
					
//					echo '</div>';
					echo $this->BootstrapForm->input('due_date', array(				
							'label' => 'Fecha Limite:',
							'id'=>'txtModalDueDate',
							));
					echo '<br>';
					
//					echo '</div>';
//					echo $this->BootstrapForm->input('debt_amount', array(				
//							'label' => 'Deuda:',
//							'id'=>'txtModalDebtAmount',
//						//	'value'=>$amount,
//							'style'=>'background-color:#EEEEEE',
//							'class'=>'input-small',
//							'maxlength'=>'15'
//							));
//					echo '<br>';
					
//					echo '</div>';
					echo $this->BootstrapForm->input('amount', array(				
							'label' => 'Monto Pagado:',
							'id'=>'txtModalPaidAmount',
						//	'value'=>$amount,
							'style'=>'background-color:#EEEEEE',
							'class'=>'input-small',
							'maxlength'=>'15'
							));
					echo '<br>';
					
//					echo '</div>';
					echo $this->BootstrapForm->input('description', array(				
							'label' => 'Descripcion:',
							'id'=>'txtModalDescription',
						//	'value'=>$amount,
							'style'=>'background-color:#EEEEEE',
							'class'=>'input-small',
							'maxlength'=>'15'
							));
					echo '<br>';
					
//					echo '</div>';
					echo $this->BootstrapForm->input('state', array(				
							'label' => 'Estado:',
							'id'=>'txtModalState',
						//	'value'=>$amount,
							'style'=>'background-color:#EEEEEE',
							'class'=>'input-small',
							'maxlength'=>'15'
							));
					echo '<br>';

//					echo $this->BootstrapForm->input('quantity', array(				
//					'label' => 'Cantidad:',
//					'id'=>'txtModalQuantity',
//					'class'=>'input-small',
//					//'value'=>'6',
//					'maxlength'=>'10',
//					'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
//					));
					?>
					  <div id="boxModalValidatePay" class="alert-error"></div> 
				  </div>
				  
				  <div class="modal-footer">
					 <!-- Ztep 0 Save button from modal triggers btnModalAddItem -->
					<a href='#' class="btn btn-primary" id="btnModalAddPay">Guardar add cost</a>
					<a href='#' class="btn btn-primary" id="btnModalEditPay">Guardar edit cost</a>
					<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
					
				  </div>
					
			</div>
<!-- ////////////////////////////////// FIN MODAL PAYS (Esta fuera del span9 pero sigue pertenciendo al template principal CONTAINER FLUID/ROW FLUID) ////////////////////////////// -->