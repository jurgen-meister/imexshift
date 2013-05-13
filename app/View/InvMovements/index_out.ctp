<!--<div class="row-fluid">--> <!-- No va porque ya esta dentro del row-fluid del container del template principal-->
<?php echo  $this->BootstrapPaginator->options(array('url' => $this->passedArgs));?>
<!-- ************************************************************************************************************************ -->
<div class="span9"><!-- INICIO CONTAINER FLUID/ROW FLUID/SPAN9 - Del Template Principal (SPAN3 reservado para menu izquierdo) -->
<!-- ************************************************************************************************************************ -->
		<h2><?php
			echo $this->Html->link('<i class="icon-plus icon-white"></i>', array('action' => 'save_out'), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Nuevo')); 
			?>
<?php echo __(' Salidas del Almacén');?></h2>
		
			<!--<a href="save_in" id="btnChangeState" class="btn btn-primary" title="Nueva entrada a almacén"><i class="icon-plus icon-white"></i> Nuevo</a>-->
			
		
		<!-- ////////////////////////////////////////INCIO - FORMULARIO BUSQUEDA////////////////////////////////////////////////-->
		<?php echo $this->BootstrapForm->create('InvMovement', array('class' => 'form-search', 'novalidate' => true));?>
		<fieldset>
		<legend><?php echo __(''); ?></legend>
					<?php
					echo $this->BootstrapForm->input('code', array(				
									//'label' => 'Codigo Entrada:',
									'id'=>'txtCode',
									'value'=>$code,
									'placeholder'=>'Codigo Entrada'
									));
					?>
					<?php
					echo $this->BootstrapForm->input('document_code', array(				
							//'label' => 'Codigo Compra:',
							'id'=>'txtCodeDocument',
							'value'=>$document_code,
							'placeholder'=>'Codigo Documento'
							));
					?>
				<?php
					echo $this->BootstrapForm->submit('<i class="icon-search icon-white"></i>',array('class'=>'btn btn-primary','div'=>false, 'id'=>'btnSearch', 'title'=>'Buscar'));
				?>
		</fieldset>
		<?php echo $this->BootstrapForm->end();?>
		<!-- ////////////////////////////////////////FIN - FORMULARIO BUSQUEDA////////////////////////////////////////////////-->
		
		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Pagina {:page} de {:pages}, mostrando {:current} registros de {:count} total, comenzando en  {:start}, terminando en {:end}')));?>
		</p>
		<?php $cont = $this->BootstrapPaginator->counter('{:start}');?>
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<th><?php echo "#";?></th>
				<th><?php echo 'Codigo Entrada';?></th>
				<th><?php echo 'Codigo Documento';?></th>
				<th><?php echo $this->BootstrapPaginator->sort('inv_movement_type_id', 'Movimiento');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date', 'Fecha');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('inv_warehouse_id', 'Almacen');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state', 'Estado Documento');?></th>
			</tr>
		<?php foreach ($invMovements as $invMovement): ?>
			<tr>
				<td><?php echo $cont++;?></td>
				<td><?php echo h($invMovement['InvMovement']['code']); ?>&nbsp;</td>
				<td>
					<?php 
					
					//if(isset($invMovement['InvMovement']['document_code'])){
					echo h($invMovement['InvMovement']['document_code']); 
					//}else{
					//	echo 'Sin codigo';
					//}
					?>
					&nbsp;
				</td>
				<td>
					<?php echo h($invMovement['InvMovementType']['name']); ?>
				</td>
				<td>
					<?php 
					echo date("d/m/Y", strtotime($invMovement['InvMovement']['date']));
					?>
					&nbsp;
				</td>
				<td>
					<?php echo h($invMovement['InvWarehouse']['name']); ?>
				</td>
				<td>
					<?php 
					
					$documentState = $invMovement['InvMovement']['lc_state'];
					switch ($documentState){
								case 'PENDANT':
									$stateColor = 'btn-warning';
									$stateName = 'Pendiente';
									break;
								case 'APPROVED':
									$stateColor = 'btn-success';
									$stateName = 'Aprobado';
									break;
								case 'CANCELLED':
									$stateColor = 'btn-danger';
									$stateName = 'Cancelado';
									break;
							}
					///////////START - SETTING URL AND PARAMETERS/////////////
					$url = array();
					$parameters = $this->passedArgs;
					if($invMovement['InvMovement']['inv_movement_type_id'] == 1){//Compra
						//$url['action']='save_purchase_in';
						$url['action']='save_sale_out';
						$parameters['document_code']=$invMovement['InvMovement']['document_code'];
						$parameters['id']=$invMovement['InvMovement']['id'];
					}else{
						$url['action'] = 'save_out';
						$parameters['id']=$invMovement['InvMovement']['id'];
					}
					
					////////////END - SETTING URL AND PARAMETERS//////////////
					echo $this->Html->link('<i class="icon-pencil icon-white"></i>'.__(' '.$stateName),  array_merge($url,$parameters), array('class'=>'btn '.$stateColor, 'escape'=>false, 'title'=>'Editar')); 
					?>&nbsp;
				</td>
				
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
		
<!-- ************************************************************************************************************************ -->
</div><!-- FIN CONTAINER FLUID/ROW FLUID/SPAN9 - Del Template Principal (SPAN3 reservado para menu izquierdo) -->
<!-- ************************************************************************************************************************ -->
<!--</div>--><!-- No va porque ya esta dentro del row-fluid del container del template principal-->