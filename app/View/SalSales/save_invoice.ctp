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
							case 'INVOICE_PENDANT':
								$displayApproved = 'inline';
								$displayCancelled = 'none';
								break;
							case 'INVOICE_APPROVED':
								$displayApproved = 'none';
								$displayCancelled = 'inline';
								break;
							case 'INVOICE_CANCELLED':
								$displayApproved = 'none';
								$displayCancelled = 'none';
								break;
						}
			?>
			<?php
			if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){
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
			<h5>Factura de Venta</h5>
			<span id="documentState" class="label <?php echo $documentStateColor;?>"><?php echo $documentStateName;?></span>
		</div>
		<div class="widget-content nopadding">
			
	<!-- //******************************** END - #UNICORN  WRAP FORM BOX PART 1/2 *************************************** -->

	
	<!-- ////////////////////////////////// START - FORM STARTS ///////////////////////////////////// -->
		<?php echo $this->BootstrapForm->create('SalSale', array('class' => 'form-horizontal'));?>
		<fieldset>
	<!-- ////////////////////////////////// END - FORM ENDS /////////////////////////////////////// -->			
				
				
				<!-- ////////////////////////////////// START FORM INVOICE FIELDS /////////////////////////////////////// -->
				<?php
				//////////////////////////////////START - block when APPROVED or CANCELLED///////////////////////////////////////////////////
				$disable = 'disabled';
				$disable2 = 'disabled';
				
				if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){
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
					'value'=>$originCode
				));
				
				echo $this->BootstrapForm->input('note_code', array(
					'id'=>'txtNoteCode',
					'label' => 'No. Nota de Remision:'
				));
				
				echo $this->BootstrapForm->input('generic_code', array(
					'id'=>'txtGenericCode',
					'value'=>$genericCode,
					'type'=>'hidden'
				));
				
				echo $this->BootstrapForm->input('date_in', array(
					'required' => 'required',
					'label' => 'Fecha:',
					'id'=>'txtDate',
					'value'=>$date,
					'disabled'=>$disable,
					'maxlength'=>'0'
				));				
				
				echo $this->BootstrapForm->input('sal_customer_id', array(
					'required' => 'required',
					'label' => 'Cliente:',
					'id'=>'cbxCustomers',
					'selected' => $customerId,
					'disabled'=>$disable
				));
				
				echo '<div id="boxControllers">';
					echo $this->BootstrapForm->input('sal_employee_id', array(
						'required' => 'required',
						'label' => 'Encargado:',
						'id'=>'cbxEmployees'
					));

					echo $this->BootstrapForm->input('sal_tax_number_id', array(
						'required' => 'required',
						'label' => 'NIT - Nombre:',
						'id'=>'cbxTaxNumbers',
						'disabled'=>$disable
					));
				echo '</div>';
			
				echo $this->BootstrapForm->input('sal_adm_user_id', array(
					'required' => 'required',
					'label' => 'Vendedor:',
					'id'=>'cbxSalesman',
					'selected' => $admUserId,
					'disabled'=>$disable
				));

				echo $this->BootstrapForm->input('description', array(
					'rows' => 2,
					'label' => 'Descripción:',
					'disabled'=>$disable,
					'id'=>'txtDescription'
				));
				
				echo $this->BootstrapForm->input('ex_rate', array(
					'label' => 'Tipo de Cambio:',
					'value'=>$exRate,
					'disabled'=>$disable,
					'id'=>'txtExRate'
				));
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
				<li><a data-toggle="tab" href="#tab2">Pagos</a></li>
			</ul>
		</div>
		<div class="widget-content tab-content">
			<div id="tab1" class="tab-pane active">
				<!-- ////////////////////////////////// START - INVOICE ITEMS DETAILS /////////////////////////////////////// -->
				<?php if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){ ?>
					<a class="btn btn-primary" href='#' id="btnAddItem" title="Adicionar Item"><i class="icon-plus icon-white"></i></a>
				<?php } ?>
						
						<table class="table table-bordered table-striped table-hover" id="tablaItems">
							<thead>
								<tr>
									<th>Item</th>
									<th>Precio Unitario</th>
									<th>Cantidad</th>
									<th>Almacen</th>
									<th>Stock</th>
									<th>Subtotal</th>
									<?php if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){ ?>
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
										if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){
											echo '<td class="columnItemsButtons">';
											echo '<a class="btn btn-primary" href="#" id="btnEditItem'.$salDetails[$i]['itemId'].'" title="Editar"><i class="icon-pencil icon-white"></i></a>
												
												<a class="btn btn-danger" href="#" id="btnDeleteItem'.$salDetails[$i]['itemId'].'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
											echo '</td>';
										}
									echo '</tr>';	
									$total += $subtotal;
								}?>
							</tbody>
						</table>
					
				<div class="row-fluid"> <!-- vers si borrar este row-fluid creo q si -->
					
					<?php if($documentState == 'INVOICE_APPROVED'){ ?>
						<div class="span10">	</div>
						<div class="span1">
							<h4>Total:</h4>	
						</div>
						<div class="span1">
							<h4 id="total" ><?php echo $total.' $us'; ?></h4>
						</div>
					<?php }  else { ?>
						<div class="span8">	</div>
						<div class="span1">
							<h4>Total:</h4>	
						</div>
						<div class="span3">
							<h4 id="total" ><?php echo $total.' $us'; ?></h4>
						</div>
					<?php }?>
					
				</div>
				<!-- ////////////////////////////////// END INVOICE ITEMS DETAILS /////////////////////////////////////// -->
			</div>
			<div id="tab2" class="tab-pane">
				<!-- ////////////////////////////////// START - INVOICE PAY DETAILS /////////////////////////////////////// -->
				<?php if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){ ?>
					<a class="btn btn-primary" href='#' id="btnAddPay" title="Adicionar Pago"><i class="icon-plus icon-white"></i></a>
				<?php } ?>
						
						<table class="table table-bordered table-striped table-hover" id="tablaPays">
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
								for($i=0; $i<count($salPayments); $i++){
									echo '<tr>';
										echo '<td><span id="spaPayName'.$salPayments[$i]['payId'].'">'.$salPayments[$i]['pay'].'</span><input type="hidden" value="'.$salPayments[$i]['payId'].'" id="txtPayId" ></td>';
										echo '<td><span id="spaDate'.$salPayments[$i]['payId'].'">'.$salPayments[$i]['date'].'</span></td>';
										echo '<td><span id="spaDueDate'.$salPayments[$i]['payId'].'">'.$salPayments[$i]['dueDate'].'</span></td>';
										echo '<td><span id="spaPaidAmount'.$salPayments[$i]['payId'].'">'.$salPayments[$i]['paidAmount'].'</span></td>';
/*calculado ---> */						echo '<td><span id="spaDebtAmount'.$salPayments[$i]['payId'].'">'.$debtAmount.'</span></td>';
										echo '<td><span id="spaDescription'.$salPayments[$i]['payId'].'">'.$salPayments[$i]['description'].'</span></td>';
										echo '<td><span id="spaState'.$salPayments[$i]['payId'].'">'.$salPayments[$i]['state'].'</span></td>';
										
										if($documentState == 'INVOICE_PENDANT' OR $documentState == ''){
											echo '<td class="columnPaysButtons">';
											echo '<a class="btn btn-primary" href="#" id="btnEditPay'.$salPayments[$i]['payId'].'" title="Editar"><i class="icon-pencil icon-white"></i></a>
												
												<a class="btn btn-danger" href="#" id="btnDeletePay'.$salPayments[$i]['payId'].'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
											echo '</td>';
										}
									echo '</tr>';	
									$total += $subtotal;
								}?>
							</tbody>
						</table>
				<div class="row-fluid"> <!-- vers si borrar este row-fluid creo q si -->
					
					<?php if($documentState == 'INVOICE_APPROVED'){ ?>
						<div class="span10">	</div>
						<div class="span1">
							<h4>Total:</h4>	
						</div>
						<div class="span1">
							<h4 id="total" ><?php echo $total.' $us'; ?></h4>
						</div>
					<?php }  else { ?>
						<div class="span8">	</div>
						<div class="span1">
							<h4>Total:</h4>	
						</div>
						<div class="span3">
							<h4 id="total" ><?php echo $total.' $us'; ?></h4>
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
					'maxlength'=>'10',
					'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
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
							'class'=>'input-xlarge'
							));
						echo '<br>';
					echo '</div>';
					echo $this->BootstrapForm->input('date', array(	
							'label' => 'Fecha Pago:',
							'id'=>'txtModalDate',
							));
					echo '<br>';
					
					echo $this->BootstrapForm->input('due_date', array(				
							'label' => 'Fecha Limite:',
							'id'=>'txtModalDueDate',
							));
					echo '<br>';
					
					echo $this->BootstrapForm->input('amount', array(				
							'label' => 'Monto Pagado:',
							'id'=>'txtModalPaidAmount',
							'style'=>'background-color:#EEEEEE',
							'class'=>'input-small',
							'maxlength'=>'15'
							));
					echo '<br>';
					
					echo $this->BootstrapForm->input('description', array(				
							'label' => 'Descripcion:',
							'id'=>'txtModalDescription',
							'style'=>'background-color:#EEEEEE',
							'class'=>'input-small',
							'maxlength'=>'15'
							));
					echo '<br>';
					
					echo $this->BootstrapForm->input('state', array(				
							'label' => 'Estado:',
							'id'=>'txtModalState',
							'style'=>'background-color:#EEEEEE',
							'class'=>'input-small',
							'maxlength'=>'15'
							));
					echo '<br>';

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