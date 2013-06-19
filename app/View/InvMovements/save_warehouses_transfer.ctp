<?php echo $this->Html->script('modules/InvMovements', FALSE); ?>

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
			case 'PENDANT':
				$documentStateColor = 'label-warning';
				$documentStateName = 'PENDIENTE';
				break;
			case 'APPROVED':
				$documentStateColor = 'label-success';
				$documentStateName = 'APROBADO';
				break;
			case 'CANCELLED':
				$documentStateColor = 'label-important';
				$documentStateName = 'CANCELADO';
				break;
		}
	?>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="icon-edit"></i>								
			</span>
			<h5>Traspaso entre Almacenes</h5>
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
		<?php echo $this->BootstrapForm->create('InvMovement', array('class' => 'form-horizontal'));?>
		<fieldset>
	<!-- ////////////////////////////////// FIN - INICIO FORM /////////////////////////////////////// -->			
				
				<!-- ////////////////////////////////// INICIO CAMPOS FORMULARIOS MOVIMIENTO /////////////////////////////////////// -->
				<?php
				
				//////////////////////////////////START - block when APPROVED or CANCELLED///////////////////////////////////////////////////
				$disable = 'disabled';
				//$btnAddMovementType = '';
				
				if($documentState == 'PENDANT' OR $documentState == ''){
					$disable = 'enabled';	
					//$btnAddMovementType = '<a class="btn btn-primary" href="#" id="btnAddMovementType" title="Nuevo Tipo Movimiento"><i class="icon-plus icon-white"></i></a>';
				}
				
				//////////////////////////////////END - block when APPROVED or CANCELLED///////////////////////////////////////////////////
				/*
				echo $this->BootstrapForm->input('token_status_hidden', array(
					'id'=>'txtTokenStatusHidden',
					'value'=>'entrada',
					'type'=>'hidden'
				));
				*/
				echo $this->BootstrapForm->input('movement_hidden', array(
					'id'=>'txtMovementIdHidden',
					'value'=>$movementIdOut,
					'type'=>'hidden'
				));
				
				echo $this->BootstrapForm->input('code', array(
					'id'=>'txtCode',
					'label'=>'Codigo:',
					'style'=>'background-color:#EEEEEE',
					'disabled'=>$disable,
					'type'=>'hidden',
					'placeholder'=>'El sistema generará el código',
				));
				
				echo $this->BootstrapForm->input('document_code', array(
					'id'=>'txtDocumentCode',
					'label'=>'Codigo Traspaso:',
					'style'=>'background-color:#EEEEEE',
					'disabled'=>$disable,
					'value'=>$documentCode,
					'placeholder'=>'El sistema generará el código',
				));
				
				echo $this->BootstrapForm->input('date_in', array(
					'required' => 'required',
					'label' => 'Fecha:',
					'id'=>'txtDate',
					'value'=>$date,
					'disabled'=>$disable,
					'maxlength'=>'0',
					//'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
				));
				
				//debug($warehouses);				
				echo $this->BootstrapForm->input('warehouseOrigin', array(
					'required' => 'required',
					'label' => 'Almacen Origen:',
					'id'=>'cbxWarehouses',
					'options'=>$warehouses,
					'value'=>$warehouseOut,
					'disabled'=>$disable,
					'type'=>'select',
					//'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
				));
				
				echo $this->BootstrapForm->input('warehouseDestination', array(
					'required' => 'required',
					'label' => 'Almacen Destino:',
					'id'=>'cbxWarehouses2',
					'options'=>$warehouses,
					'value'=>$warehouseIn,
					'disabled'=>$disable,
					'type'=>'select',
					//'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
				));

				
				echo $this->BootstrapForm->input('description', array(
					'rows' => 2,
					//'style'=>'width:400px',
					'label' => 'Descripción:',
					'disabled'=>$disable,
					'id'=>'txtDescription'
				));
				?>
				<!-- ////////////////////////////////// FIN CAMPOS FORMULARIOS MOVIMIENTO /////////////////////////////////////// -->
				
				
				
				<!-- ////////////////////////////////// INICIO - ITEMS /////////////////////////////////////// -->
				<div class="row-fluid">
						<?php if($documentState == 'PENDANT' OR $documentState == ''){ ?>
						<a class="btn btn-primary" href='#' id="btnAddItem" title="Adicionar Item"><i class="icon-plus icon-white"></i></a>
						<?php } ?>
						<table class="table table-bordered table-condensed table-striped table-hover" id="tablaItems">
							<thead>
								<tr>
									<th>Item (unidad)</th>
									<th>Stock Origen</th>
									<th>Stock Destino</th>
									<th>Cantidad</th>
									<?php if($documentState == 'PENDANT' OR $documentState == ''){ ?>
									<th class="columnItemsButtons"></th>
									<?php }?>
								</tr>
							</thead>
							<tbody>
								<?php
								//debug($invMovementDetailsOut);
								//debug($invMovementDetailsIn);
								for($i=0; $i<count($invMovementDetailsOut); $i++){
									echo '<tr>';
										echo '<td><span id="spaItemName'.$invMovementDetailsOut[$i]['itemId'].'">'.$invMovementDetailsOut[$i]['item'].'</span><input type="hidden" value="'.$invMovementDetailsOut[$i]['itemId'].'" id="txtItemId" ></td>';
										echo '<td><span id="spaStock'.$invMovementDetailsOut[$i]['itemId'].'">'.$invMovementDetailsOut[$i]['stock'].'</span></td>';
										echo '<td><span id="spaStock2-'.$invMovementDetailsOut[$i]['itemId'].'">'.$invMovementDetailsIn[$i]['stock'].'</span></td>';
										echo '<td><span id="spaQuantity'.$invMovementDetailsOut[$i]['itemId'].'">'.$invMovementDetailsOut[$i]['cantidad'].'</span></td>';
										if($documentState == 'PENDANT' OR $documentState == ''){
											echo '<td class="columnItemsButtons">';
											echo '<a class="btn btn-primary" href="#" id="btnEditItem'.$invMovementDetailsOut[$i]['itemId'].'" title="Editar"><i class="icon-pencil icon-white"></i></a>
												
												<a class="btn btn-danger" href="#" id="btnDeleteItem'.$invMovementDetailsOut[$i]['itemId'].'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
											echo '</td>';
										}
									echo '</tr>';								
								}
								?>
							</tbody>
						</table>
				</div>
			<!-- ////////////////////////////////// FIN ITEMS /////////////////////////////////////// -->

				
			<!-- ////////////////////////////////// INICIO BOTONES /////////////////////////////////////// -->
			<!--<div class="form-actions">--><!-- no sirve se desconfigura los botones en modo tablet -->
			<div class="row-fluid"> <!-- INICIO - row fluid para alinear los botones -->
				<div class="span2"></div> <!-- INICIO Y FIN - ESPACIO A LA IZQUIERDA -->
				<div class="span10">	<!-- INICIO - span 6 -->
					<div class="btn-toolbar"> <!-- INICIO - toolbar para dejar espacio entre botones -->
							<?php 
								if($documentState == 'PENDANT' OR $documentState == ''){
									echo $this->BootstrapForm->submit('Guardar Cambios',array('class'=>'btn btn-primary','div'=>false, 'id'=>'btnSaveAll'));	

								}
								/////////////////START - SETTINGS BUTTON CANCEL /////////////////
								$parameters = $this->passedArgs;
								if(isset($parameters['origin'])){
									if($parameters['origin']=='in'){
										$url=array('action'=>'index_in');
									}elseif($parameters['origin']=='out'){
										$url=array('action'=>'index_out');	
									}
									if(!isset($parameters['search'])){
										unset($parameters['document_code']);
										unset($parameters['code']);
									}
								}else{
									$url=array('action'=>'index_warehouses_transfer');
								}
								if(!isset($parameters['search'])){
									unset($parameters['document_code']);
								}else{
									if($parameters['search'] == 'empty'){
										unset($parameters['document_code']);
									}
								}
								//unset($parameters['id']);
								echo $this->Html->link('Cancelar', array_merge($url,$parameters), array('class'=>'btn') );
								//////////////////END - SETTINGS BUTTON CANCEL /////////////////
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
							<?php
								$displayPrint = 'none';
								if($movementIdOut <> ''){
									$displayPrint = 'inline';
								}
								echo $this->Html->link('<i class="icon-print icon-white"></i> Imprimir', array('action' => 'view_document_movement_pdf', $movementIdOut.'.pdf'), array('class'=>'btn btn-primary','style'=>'display:'.$displayPrint, 'escape'=>false, 'title'=>'Nuevo', 'id'=>'btnPrint', 'target'=>'_blank')); 
								
							?>
							<a href="#" id="btnApproveState" class="btn btn-success" style="display:<?php echo $displayApproved;?>"> Aprobar Transferencia Almacen</a>
							<a href="#" id="btnCancellState" class="btn btn-danger" style="display:<?php echo $displayCancelled;?>"> Cancelar Transferencia Almacen</a>
					</div> <!-- FIN - toolbar para dejar espacio entre botones -->
				</div> <!-- FIN - span 10 -->
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
				  
				  <div class="modal-body">
					<!--<p>One fine body…</p>-->
					<?php
					echo '<div id="boxModalIntiateItemStock">';
						//////////////////////////////////////

						echo $this->BootstrapForm->input('items_id', array(				
						'label' => 'Item:',
						'id'=>'cbxModalItems',
						'class'=>'input-xlarge',
						'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
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
					'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
					));
					?>
					  <div id="boxModalValidateItem" class="alert-error"></div> 
				  </div>
				  
				  <div class="modal-footer">
					<a href='#' class="btn btn-primary" id="btnModalAddItem">Guardar</a>
					<a href='#' class="btn btn-primary" id="btnModalEditItem">Guardar</a>
					<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
					
				  </div>
					
			</div>
<!-- ////////////////////////////////// FIN MODAL (Esta fuera del span9 pero sigue pertenciendo al template principal CONTAINER FLUID/ROW FLUID) ////////////////////////////// -->