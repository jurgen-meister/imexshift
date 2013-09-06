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
			case 'PINVOICE_PENDANT':
				$documentStateColor = 'label-warning';
				$documentStateName = 'FACTURA PENDIENTE';
				break;
			case 'PINVOICE_APPROVED':
				$documentStateColor = 'label-success';
				$documentStateName = 'FACTURA APROBADA';
				break;
			case 'PINVOICE_CANCELLED':
				$documentStateColor = 'label-important';
				$documentStateName = 'FACTURA CANCELADA';
				break;
		}
	?>
	<!-- //////////////////////////// Start - buttons /////////////////////////////////-->
	<div class="widget-box">
		<div class="widget-content nopadding">
			<?php 
				/////////////////START - SETTINGS BUTTON CANCEL /////////////////
				$url=array('action'=>'index_invoice');
				$parameters = $this->passedArgs;
				if(!isset($parameters['search'])){
//					unset($parameters['document_code']);
					unset($parameters['code']);
				}
				unset($parameters['id']);
				echo $this->Html->link('<i class=" icon-arrow-left"></i> Volver', array_merge($url,$parameters), array('class'=>'btn', 'escape'=>false)).' ';
				//////////////////END - SETTINGS BUTTON CANCEL /////////////////
			?>

			<?php 
				switch ($documentState){
							case '':
								$displayApproved = 'none';
								$displayCancelled = 'none';
								break;
							case 'PINVOICE_PENDANT':
								$displayApproved = 'inline';
								$displayCancelled = 'none';
								break;
							case 'PINVOICE_APPROVED':
								$displayApproved = 'none';
								$displayCancelled = 'inline';
								break;
							case 'PINVOICE_CANCELLED':
								$displayApproved = 'none';
								$displayCancelled = 'none';
								break;
						}
			?>
			<?php
			if($documentState == 'PINVOICE_PENDANT' OR $documentState == ''){
				echo $this->BootstrapForm->submit('Guardar Cambios',array('class'=>'btn btn-primary','div'=>false, 'id'=>'btnSaveAll'));	
			}
			?>
			<a href="#" id="btnApproveState" class="btn btn-success" style="display:<?php echo $displayApproved;?>"> Aprobar Factura de Compra</a>
			<a href="#" id="btnLogicDeleteState" class="btn btn-danger" style="display:<?php echo $displayApproved;?>"><i class=" icon-trash icon-white"></i> Eliminar</a>
			<a href="#" id="btnCancellState" class="btn btn-danger" style="display:<?php echo $displayCancelled;?>"> Cancelar Factura de Compra</a>
			<?php
				$displayPrint = 'none';
				if($id <> ''){
					$displayPrint = 'inline';
				}
				echo $this->Html->link('<i class="icon-print icon-white"></i> Imprimir', array('action' => 'view_document_movement_pdf', $id.'.pdf'), array('class'=>'btn btn-primary','style'=>'display:'.$displayPrint, 'escape'=>false, 'title'=>'Nuevo', 'id'=>'btnPrint', 'target'=>'_blank')); 

			?>
			
			
			
			
		</div>
	</div>
	<!-- //////////////////////////// End - buttons /////////////////////////////////-->
	
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="icon-edit"></i>								
			</span>
			<h5>Factura de Compra</h5>
			<span id="documentState" class="label <?php echo $documentStateColor;?>"><?php echo $documentStateName;?></span>
		</div>
		<div class="widget-content nopadding">
			
	<!-- //******************************** END - #UNICORN  WRAP FORM BOX PART 1/2 *************************************** -->

	
	<!-- ////////////////////////////////// START - FORM STARTS ///////////////////////////////////// -->
		<?php echo $this->BootstrapForm->create('PurPurchase', array('class' => 'form-horizontal'));?>
		<fieldset>
	<!-- ////////////////////////////////// END - FORM ENDS /////////////////////////////////////// -->			
					
				
				<!-- ////////////////////////////////// START FORM INVOICE FIELDS /////////////////////////////////////// -->
				<?php
				//////////////////////////////////START - block when APPROVED or CANCELLED///////////////////////////////////////////////////
				$disable = 'disabled';
				$disable2 = 'disabled';
//				
				if($documentState == 'PINVOICE_PENDANT' OR $documentState == ''){
					$disable = 'enabled';
				}
				
				//////////////////////////////////END - block when APPROVED or CANCELLED///////////////////////////////////////////////////
				
				echo $this->BootstrapForm->input('purchase_hidden', array(
					'id'=>'txtPurchaseIdHidden',
					'value'=>$id,
					'type'=>'hidden'
				));
							
				echo $this->BootstrapForm->input('doc_code', array(
					'id'=>'txtCode',
					'label'=>'Código:',
					'style'=>'background-color:#EEEEEE',
					'disabled'=>$disable2,
					'placeholder'=>'El sistema generará el código'
				));
				
				echo $this->BootstrapForm->input('origin_code', array(
					'id'=>'txtOriginCode',
					'label'=>'Documento Origen:',
					'style'=>'background-color:#EEEEEE',
					'disabled'=>$disable2,
					'value'=>$originCode,
				));
				
				echo $this->BootstrapForm->input('generic_code', array(
					'id'=>'txtGenericCode',
					'value'=>$genericCode,
					'type'=>'hidden'
				
				));
				
				echo $this->BootstrapForm->input('note_code', array(
					'id'=>'txtNoteCode',
					'label' => 'No. Factura Compra:',
					'disabled'=>$disable
				));
				
				echo $this->BootstrapForm->input('date_in', array(
					'label' => 'Fecha:',
					'id'=>'txtDate',
					'value'=>$date,
					'disabled'=>$disable,
					'maxlength'=>'0'
				));
				
				echo $this->BootstrapForm->input('inv_supplier_id', array(
					'label' => 'Proveedor:',
					'id'=>'cbxSuppliers',
					'disabled'=>$disable2
				));
				
				echo $this->BootstrapForm->input('description', array(
					'rows' => 2,
					'label' => 'Descripción:',
					'disabled'=>$disable,
					'id'=>'txtDescription'
				));
				
				echo '<div id="boxExRate">';
					echo $this->BootstrapForm->input('ex_rate', array(
						'label' => 'Tipo de Cambio:',
						'value'=>$exRate,
						'disabled'=>'disabled',
						'id'=>'txtExRate',
						'type'=>'text'
					));
				echo '</div>';
				?>
				<!-- ////////////////////////////////// END FORM INVOICE FIELDS /////////////////////////////////////// -->
				
					<!-- ////////////////////////////////// START MESSAGES /////////////////////////////////////// -->
					<div id="boxMessage"></div>
					<div id="processing"></div>
					<!-- ////////////////////////////////// END MESSAGES /////////////////////////////////////// -->

	<!-- ////////////////////////////////// START - END FORM ///////////////////////////////////// -->		
	</fieldset>
	<?php echo $this->BootstrapForm->end();?>
	<!-- ////////////////////////////////// END - END FORM ///////////////////////////////////// -->
	
	<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
		</div> <!-- Belongs to: <div class="widget-content nopadding"> -->
	</div> <!-- Belongs to: <div class="widget-box"> -->
	<!-- //******************************** END - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
	
	<!-- ////////////////////////////////// START - INVOICE DETAILS /////////////////////////////////////// -->
	
	<div class="widget-box">
		<div class="widget-title">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#tab1">Items</a></li>
				<li><a data-toggle="tab" href="#tab2">Costos</a></li>
                <li><a data-toggle="tab" href="#tab3">Pagos</a></li>
			</ul>
		</div>
		<div class="widget-content tab-content">
			<div id="tab1" class="tab-pane active">
				<!-- ////////////////////////////////// START - INVOICE ITEMS DETAILS /////////////////////////////////////// -->		
				<?php if($documentState == 'PINVOICE_PENDANT' OR $documentState == ''){ ?>
					<a class="btn btn-primary" href='#' id="btnAddItem" title="Adicionar Item"><i class="icon-plus icon-white"></i></a>
				<?php } ?>
						<?php $limit = count($purDetails); $counter = $limit;?>
						<table class="table table-bordered table-hover data-table" id="tablaItems">
							<thead>
								<tr>
									<th>Item ( <span id="countItems"><?php echo $limit;?> </span> )</th>
									<th>Precio Unitario</th>
									<th>Cantidad</th>
									<th>Subtotal</th>
									<?php if($documentState == 'PINVOICE_PENDANT' OR $documentState == ''){ ?>
									<th class="columnItemsButtons"></th>
									<?php }?>
								</tr>
							</thead>
							<tbody>
								<?php
								$total = '0.00';
								for($i=0; $i<$limit; $i++){
									$subtotal = ($purDetails[$i]['cantidad'])*($purDetails[$i]['exFobPrice']);
									echo '<tr id="itemRow'.$purDetails[$i]['itemId'].'">';
										echo '<td><span id="spaItemName'.$purDetails[$i]['itemId'].'">'.$purDetails[$i]['item'].'</span><input  value="'.$purDetails[$i]['itemId'].'" id="txtItemId" ></td>';
										echo '<td><span id="spaExFobPrice'.$purDetails[$i]['itemId'].'">'.$purDetails[$i]['exFobPrice'].'</span></td>';
										echo '<td><span id="spaQuantity'.$purDetails[$i]['itemId'].'">'.$purDetails[$i]['cantidad'].'</span></td>';
										echo '<td><span id="spaSubtotal'.$purDetails[$i]['itemId'].'">'.number_format($subtotal, 2, '.', '').'</span></td>';
										
										if($documentState == 'PINVOICE_PENDANT' OR $documentState == ''){
											echo '<td class="columnItemsButtons">';
											echo '<a class="btn btn-primary" href="#" id="btnEditItem'.$purDetails[$i]['itemId'].'" title="Editar"><i class="icon-pencil icon-white"></i></a>
												
												<a class="btn btn-danger" href="#" id="btnDeleteItem'.$purDetails[$i]['itemId'].'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
											echo '</td>';
										}
									echo '</tr>';	
									$total += $subtotal;
								}?>
							</tbody>
						</table>
					
				<div class="row-fluid"> <!-- vers si borrar este row-fluid creo q si -->
					
					<?php if($documentState == 'PINVOICE_APPROVED'){ ?>
						<div class="span10">	</div>
						<div class="span1">
							<h4>Total:</h4>	
						</div>
						<div class="span1">
							<h4 id="total" ><?php echo number_format($total, 2, '.', '').' $us.'; ?></h4>
						</div>
					<?php }  else { ?>
						<div class="span8">	</div>
						<div class="span1">
							<h4>Total:</h4>	
						</div>
						<div class="span3">
							<h4 id="total" ><?php echo number_format($total, 2, '.', '').' $us.'; ?></h4>
						</div>
					<?php }?>
					
				</div>
				<!-- ////////////////////////////////// END INVOICE ITEMS DETAILS /////////////////////////////////////// -->	
			</div>
			<div id="tab2" class="tab-pane">
				<!-- ////////////////////////////////// START - INVOICE COST DETAILS /////////////////////////////////////// -->
				<?php if($documentState == 'PINVOICE_PENDANT' OR $documentState == ''){ ?>
					<a class="btn btn-primary" href='#' id="btnAddCost" title="Adicionar Costo"><i class="icon-plus icon-white"></i></a>
				<?php } ?>
						
						<table class="table table-bordered table-striped table-hover" id="tablaCosts">
							<thead>
								<tr>
									<th>Costos Adicionales de Importación</th>
									<th>Monto</th>
									
									<?php if($documentState == 'PINVOICE_PENDANT' OR $documentState == ''){ ?>
									<th class="columnCostsButtons"></th>
									<?php }?>
								</tr>
							</thead>
							<tbody>
								<?php
								$totalcost = 0;
								for($i=0; $i<count($purPrices); $i++){
									echo '<tr>';
										echo '<td><span id="spaCostName'.$purPrices[$i]['costId'].'">'.$purPrices[$i]['cost'].'</span><input type="hidden" value="'.$purPrices[$i]['costId'].'" id="txtCostId" ></td>';
										echo '<td><span id="spaAmount'.$purPrices[$i]['costId'].'">'.$purPrices[$i]['amount'].'</span></td>';
										if($documentState == 'PINVOICE_PENDANT' OR $documentState == ''){
											echo '<td class="columnCostsButtons">';
											echo '<a class="btn btn-primary" href="#" id="btnEditCost'.$purPrices[$i]['costId'].'" title="Editar"><i class="icon-pencil icon-white"></i></a>
												
												<a class="btn btn-danger" href="#" id="btnDeleteCost'.$purPrices[$i]['costId'].'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
											echo '</td>';
										}
									echo '</tr>';	
									$totalcost += $purPrices[$i]['amount'];
								}?>
							</tbody>
						</table>
					
				<div class="row-fluid"> <!-- vers si borrar este row-fluid creo q si -->
					
					<?php if($documentState == 'PINVOICE_APPROVED'){ ?>
						<div class="span10">	</div>
						<div class="span1">
							<h4>Total:</h4>	
						</div>
						<div class="span1">
							<h4 id="totalcost" ><?php echo $totalcost.' $us'; ?></h4>
						</div>
					<?php }  else { ?>
						<div class="span8">	</div>
						<div class="span1">
							<h4>Total:</h4>	
						</div>
						<div class="span3">
							<h4 id="totalcost" ><?php echo $totalcost.' $us'; ?></h4>
						</div>
					<?php }?>
					
				</div>
				<!-- ////////////////////////////////// END INVOICE COST DETAILS /////////////////////////////////////// -->
			</div>
            <div id="tab3" class="tab-pane">
				<!-- ////////////////////////////////// START - INVOICE PAY DETAILS /////////////////////////////////////// -->
				<?php if($documentState == 'PINVOICE_PENDANT' OR $documentState == ''){ ?>
					<a class="btn btn-primary" href='#' id="btnAddPay" title="Adicionar Pago"><i class="icon-plus icon-white"></i></a>
				<?php } ?>
						<?php $limit2 = count($purPayments); $counter2 = $limit2;?>
						<table class="table table-bordered table-hover data-table" id="tablaPays">
							<thead>
								<tr>
									<th>Fecha Pago</th>
									<th>Monto</th>
									<th>Descripcion</th>
									<?php if($documentState == 'PINVOICE_PENDANT' OR $documentState == ''){ ?>
									<th class="columnPaysButtons"></th>
									<?php }?>
								</tr>
							</thead>
							<tbody>
								<?php
								$total2 = '0.00';
								for($i=0; $i<$limit2; $i++){
									echo '<tr id="payRow'.$purPayments[$i]['dateId'].'" >';
										echo '<td><span id="spaPayDate'.$purPayments[$i]['dateId'].'">'.$purPayments[$i]['payDate'].'</span><input  value="'.$purPayments[$i]['dateId'].'" id="txtPayDate" ></td>';
										echo '<td><span id="spaPayAmount'.$purPayments[$i]['dateId'].'">'.$purPayments[$i]['payAmount'].'</span></td>';
										echo '<td><span id="spaPayDescription'.$purPayments[$i]['dateId'].'">'.$purPayments[$i]['payDescription'].'</span></td>';
										
										if($documentState == 'PINVOICE_PENDANT' OR $documentState == ''){
											echo '<td class="columnPaysButtons">';
											echo '<a class="btn btn-primary" href="#" id="btnEditPay'.$purPayments[$i]['dateId'].'" title="Editar"><i class="icon-pencil icon-white"></i></a>
												
												<a class="btn btn-danger" href="#" id="btnDeletePay'.$purPayments[$i]['dateId'].'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
											echo '</td>';
										}
									echo '</tr>';	
									$total2 += $purPayments[$i]['payAmount'];
								}?>
							</tbody>							
						</table>
					
				<div class="row-fluid"> <!-- vers si borrar este row-fluid creo q si -->
					
					<?php if($documentState == 'PINVOICE_APPROVED'){ ?>
						<div class="span10">	</div>
						<div class="span1">
							<h4>Total:</h4>	
						</div>
						<div class="span1">
							<h4 id="total" ><?php echo number_format($total2, 2, '.', '').' Bs.'; ?></h4>
						</div>
					<?php }  else { ?>
						<div class="span8">	</div>
						<div class="span1">
							<h4>Total:</h4>	
						</div>
						<div class="span3">
							<h4 id="total" ><?php echo number_format($total2, 2, '.', '').' Bs.'; ?></h4>
						</div>
					<?php }?>
					
				</div>
				<!-- ////////////////////////////////// END INVOICE PAY DETAILS /////////////////////////////////////// -->
			</div>
		</div>                            
	</div>
								
	<!-- ////////////////////////////////// END INVOICE DETAILS /////////////////////////////////////// -->
		
<!-- ************************************************************************************************************************ -->
</div><!-- END CONTAINER FLUID/ROW FLUID/SPAN12 - MAIN Template #UNICORN -->
<!-- ************************************************************************************************************************ --> 
	




<!-- ////////////////////////////////// START MODAL (Esta fuera del span9 pero sigue pertenciendo al template principal CONTAINER FLUID/ROW FLUID) ////////////////////////////// -->
			<div id="modalAddItem" class="modal hide fade ">
				  
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h3 id="myModalLabel">Cantidad Item</h3>
				  </div>
				  
				  <div class="modal-body">
					<!--<p>One fine body…</p>-->
					<?php
					echo '<div id="boxModalInitiateItemPrice">';
						//////////////////////////////////////
						echo $this->BootstrapForm->input('items_id', array(				
						'label' => 'Item:',
						'id'=>'cbxModalItems',
						'class'=>'span12'
						));
						
						$price='';
						echo '<div id="boxModalPrice">';
							echo $this->BootstrapForm->input('ex_fob_price', array(				
							'label' => 'Precio Unitario:',
							'id'=>'txtModalPrice',
							'value'=>$price,
							'class'=>'input-small',
							'maxlength'=>'15'
							));
						echo '</div>';		
						//////////////////////////////////////
					echo '</div>';

					echo $this->BootstrapForm->input('quantity', array(				
					'label' => 'Cantidad:',
					'id'=>'txtModalQuantity',
					'class'=>'span3',
					'maxlength'=>'10'
					));
					?>
					  <div id="boxModalValidateItem" class="alert-error"></div> 
				  </div>
				  
				  <div class="modal-footer">
					 <!-- Ztep 0 Save button from modal triggers btnModalAddItem -->
					<a href='#' class="btn btn-primary" id="btnModalAddItem">Guardar</a>
					<a href='#' class="btn btn-primary" id="btnModalEditItem">Guardar</a>
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
						));
						echo '<br>';

						//////////////////////////////////////
					echo '</div>';
					echo $this->BootstrapForm->input('amount', array(				
							'label' => 'Montito:',
							'id'=>'txtModalAmount',
							'style'=>'background-color:#EEEEEE',
							'class'=>'input-small',
							'maxlength'=>'15'
							));
					?>
					  <div id="boxModalValidateCost" class="alert-error"></div> 
				  </div>
				  
				  <div class="modal-footer">
					 <!-- Ztep 0 Save button from modal triggers btnModalAddItem -->
					<a href='#' class="btn btn-primary" id="btnModalAddCost">Guardar</a>
					<a href='#' class="btn btn-primary" id="btnModalEditCost">Guardar</a>
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
				  
				  <div class="modal-body">
					<!-- class="control-group"--> 
					<?php
					echo '<div id="boxModalInitiatePay">';
						$datePay = '';
						echo $this->BootstrapForm->input('date', array(	
								'label' => 'Fecha:',
								'id'=>'txtModalDate',
								'value'=>$datePay,
								'class'=>'span3',
								'maxlength'=>'15'
								));
//					
						$payDebt = '';
						echo $this->BootstrapForm->input('amount', array(				
								'label' => 'Monto a Pagar:',
								'id'=>'txtModalPaidAmount',
								'value'=>$payDebt,
								'class'=>'span3',
								'maxlength'=>'15'
								));
					echo '</div>';
					
					echo $this->BootstrapForm->input('description', array(				
							'label' => 'Descripcion:',
							'id'=>'txtModalDescription',
							'class'=>'span9',
							'rows' => 2
							));

					?>
					  <div id="boxModalValidatePay" class="alert-error"></div> 
				  </div>
				  
				  <div class="modal-footer">
					 <!-- Ztep 0 Save button from modal triggers btnModalAddItem -->
					<a href='#' class="btn btn-primary" id="btnModalAddPay">Guardar</a>
					<a href='#' class="btn btn-primary" id="btnModalEditPay">Guardar</a>
					<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
					
				  </div>
					
			</div>
<!-- ////////////////////////////////// FIN MODAL PAYS (Esta fuera del span9 pero sigue pertenciendo al template principal CONTAINER FLUID/ROW FLUID) ////////////////////////////// -->