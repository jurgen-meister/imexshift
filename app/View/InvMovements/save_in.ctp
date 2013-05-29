<?php echo $this->Html->script('modules/InvMovements', FALSE); // AFTER #UNICORN IMPLEMENTATION?> 

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
			<h5>Entrada al Almacen</h5>
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
		
		
	<!-- ////////////////////////////////// START - FORM STARTS ///////////////////////////////////// -->
		<?php echo $this->BootstrapForm->create('InvMovement', array('class' => 'form-horizontal'));?>
		<fieldset>
		<!--<legend><?php// echo __('Entrada al Almacén'); //COMMENTED DUE #UNICORN ?></legend>-->
	<!-- ////////////////////////////////// END - FORM ENDS /////////////////////////////////////// -->			
				
				
				<!-- ////////////////////////////////// START - TABLE STATE DOCUMENT PROCESS -> COMMENTED DUE #UNICORN/////////////////////////////////////// -->
				<!--
				<div class="row-fluid">
					<div class="span7">
						
					</div>
					<div class="span2" >
						Estado Documento:
						<?php/*
							switch ($documentState){
								case '':
									$stateColor = '#BBBBBB';
									$stateName = 'Sin estado';
									break;
								case 'PENDANT':
									$stateColor = '#F99C17';
									$stateName = 'Pendiente';
									break;
								case 'APPROVED':
									$stateColor = '#54AA54';
									$stateName = 'Aprobado';
									break;
								case 'CANCELLED':
									$stateColor = '#BD362F';
									$stateName = 'Cancelado';
									break;
							}*/
						?>
						<table id="tableProcessState" class="table table-bordered table-condensed">
							<tr>
								<td id="columnStateMovementIn" style="background-color:<?php //echo $stateColor; ?>; color: white"><?php //echo $stateName;?></td>
							</tr>
						</table>
						
					</div>
					<div class="span3"></div>
				</div>
				-->
				<!-- ////////////////////////////////// END - TABLE STATE DOCUMENT PROCESS  -> COMMENTED DUE #UNICORN /////////////////////////////////////// -->
				
				
				
				<!-- ////////////////////////////////// START FORM MOVEMENT FIELDS  /////////////////////////////////////// -->
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
					//'id'=>'movement_hidden',
					'id'=>'txtMovementIdHidden',
					'value'=>$id,
					'type'=>'hidden'
				));
				
				echo $this->BootstrapForm->input('code', array(
					//'id'=>'code',
					'id'=>'txtCode',
					'label'=>'Codigo:',
					'style'=>'background-color:#EEEEEE',
					'disabled'=>$disable,
					'placeholder'=>'El sistema generará el código',
					//'class'=>'controls'
					//'data-toggle'=>'tooltip',
					//'data-placement'=>'top',
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
				
				
				echo $this->BootstrapForm->input('inv_warehouse_id', array(
					'required' => 'required',
					'label' => 'Almacén:',
					'id'=>'cbxWarehouses',
					//'value'=>$invWarehouses,
					'disabled'=>$disable,
					//'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
				));

				echo $this->BootstrapForm->input('inv_movement_type_id', array(
					'label' => 'Tipo Movimiento:',
					'id'=>'cbxMovementTypes',
					'disabled'=>$disable,
					'required' => 'required',
					//'helpInline' => /*$btnAddMovementType.*/'<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
				));
				echo $this->BootstrapForm->input('description', array(
					'rows' => 2,
					//'style'=>'width:400px',//#UNICORN, COMMENT OR REPONSIVE DOESN'T WORK
					'label' => 'Descripción:',
					'disabled'=>$disable,
					'id'=>'txtDescription'
				));
				?>
				<!-- ////////////////////////////////// END FORM MOVEMENT FIELDS /////////////////////////////////////// -->
				
				
				
				<!-- ////////////////////////////////// START - MOVEMENT ITEMS DETAILS /////////////////////////////////////// -->
				
				

				<div class="row-fluid">
					
					<!--<div class="span1"></div>-->
					
					<!--<div id="boxTable" > It seems useless -->
					<!--
					<ul class="nav nav-tabs">
						<li class="active">

							<a href="#">Items</a>
						</li>
					</ul>
					-->
					<?php if($documentState == 'PENDANT' OR $documentState == ''){ ?>
						<a class="btn btn-primary" href='#' id="btnAddItem" title="Adicionar Item"><i class="icon-plus icon-white"></i></a>
					<?php } ?>
					
							<table class="table table-bordered table-striped table-hover" id="tablaItems">
								<thead>
									<tr>
										<th>Item (unidad)</th>
										<th>Stock</th>
										<th>Cantidad</th>
										<?php if($documentState == 'PENDANT' OR $documentState == ''){ ?>
										<th class="columnItemsButtons"></th>
										<?php }?>
									</tr>
								</thead>
								<tbody>
									<?php
									for($i=0; $i<count($invMovementDetails); $i++){
										echo '<tr>';
											echo '<td><span id="spaItemName'.$invMovementDetails[$i]['itemId'].'">'.$invMovementDetails[$i]['item'].'</span><input type="hidden" value="'.$invMovementDetails[$i]['itemId'].'" id="txtItemId" ></td>';
											echo '<td><span id="spaStock'.$invMovementDetails[$i]['itemId'].'">'.$invMovementDetails[$i]['stock'].'</span></td>';
											echo '<td><span id="spaQuantity'.$invMovementDetails[$i]['itemId'].'">'.$invMovementDetails[$i]['cantidad'].'</span></td>';
											if($documentState == 'PENDANT' OR $documentState == ''){
												echo '<td class="columnItemsButtons">';
												echo '<a class="btn btn-primary" href="#" id="btnEditItem'.$invMovementDetails[$i]['itemId'].'" title="Editar"><i class="icon-pencil icon-white"></i></a>
													<a class="btn btn-danger" href="#" id="btnDeleteItem'.$invMovementDetails[$i]['itemId'].'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
												echo '</td>';
											}
										echo '</tr>';								
									}
									?>
								</tbody>
							</table>
						
					<!--</div> It seems useless--> 
					
					<!--<div class="span3"></div>-->
					
				
			<!-- ////////////////////////////////// END MOVEMENT ITEMS DETAILS /////////////////////////////////////// -->

			<p></p>	
			<!-- ////////////////////////////////// START BUTTONS /////////////////////////////////////// -->
			<!--<div class="form-actions">--><!-- no sirve se desconfigura los botones en modo tablet -->
			<div class="row-fluid"> <!-- START - row fluid para alinear los botones -->
				<div class="span2"></div> <!-- START Y END - ESPACIO A LA IZQUIERDA -->
				<div class="span10">	<!-- START - span 6 -->
					<div class="btn-toolbar"> <!-- START - toolbar para dejar espacio entre botones -->
							<?php 
								if($documentState == 'PENDANT' OR $documentState == ''){
									echo $this->BootstrapForm->submit('Guardar Cambios',array('class'=>'btn btn-primary','div'=>false, 'id'=>'btnSaveAll'));	

								}
								/////////////////START - SETTINGS BUTTON CANCEL /////////////////
								$url=array('action'=>'index_in');
								$parameters = $this->passedArgs;
								if(!isset($parameters['search'])){
									unset($parameters['document_code']);
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
								if($id <> ''){
									$displayPrint = 'inline';
								}
								echo $this->Html->link('<i class="icon-print icon-white"></i> Imprimir', array('action' => 'view_document_movement_pdf', $id.'.pdf'), array('class'=>'btn btn-primary','style'=>'display:'.$displayPrint, 'escape'=>false, 'title'=>'Nuevo', 'id'=>'btnPrint', 'target'=>'_blank')); 
								
							?>
							<a href="#" id="btnApproveState" class="btn btn-success" style="display:<?php echo $displayApproved;?>"> Aprobar Entrada Almacen</a>
							<a href="#" id="btnCancellState" class="btn btn-danger" style="display:<?php echo $displayCancelled;?>"> Cancelar Entrada Almacen</a>
					</div> <!-- END - toolbar leave space between buttons -->
				</div> <!-- END - span 10 -->
			</div> <!-- END - row fluid for align buttons -->
			<!--</div>--><!-- Do not work, buttons are unordered in tablet mode class="form-actions" -->
			<!-- ////////////////////////////////// END BOTONES /////////////////////////////////////// -->

	<!-- ////////////////////////////////// START - END FORM ///////////////////////////////////// -->		
	</fieldset>
	<?php echo $this->BootstrapForm->end();?>
	<!-- ////////////////////////////////// END - END FORM ///////////////////////////////////// -->
	
	
	<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
		</div> <!-- Belongs to: <div class="widget-content nopadding"> -->
	</div> <!-- Belongs to: <div class="widget-box"> -->
	<!-- //******************************** END - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->

	
	
	<!-- ////////////////////////////////// START MESSAGES /////////////////////////////////////// -->
	<div id="boxMessage"></div>
	<div id="processing"></div>
	<!-- ////////////////////////////////// END MESSAGES /////////////////////////////////////// -->
	
	
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
<!-- ////////////////////////////////// END MODAL (Esta fuera del span9 pero sigue pertenciendo al template principal CONTAINER FLUID/ROW FLUID) ////////////////////////////// -->