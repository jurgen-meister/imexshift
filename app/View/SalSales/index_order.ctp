<!--<div class="row-fluid">--> <!-- No va porque ya esta dentro del row-fluid del container del template principal-->
<?php echo  $this->BootstrapPaginator->options(array('url' => $this->passedArgs));?>
<!-- ************************************************************************************************************************ -->
<div class="span12"><!-- START CONTAINER FLUID/ROW FLUID/SPAN12 - FORMATO DE #UNICORN -->
<!-- ************************************************************************************************************************ -->
<h3>	<?php echo $this->Html->link('<i class="icon-plus icon-white"></i>', array('action' => 'save_order'), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Nuevo')); ?>
			<?php echo __('Notas de %s', __('Venta'));?></h3>
<!-- *********************************************** #UNICORN SEARCH WRAP ********************************************-->
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>Filtro</h5>
			</div>
			<div class="widget-content nopadding">
			<!-- ////////////////////////////////////////INCIO - FORMULARIO BUSQUEDA////////////////////////////////////////////////-->
			<?php echo $this->BootstrapForm->create('InvMovement', array('class' => 'form-search', 'novalidate' => true));?>
			<fieldset>
						<?php
						echo $this->BootstrapForm->input('code', array(				
										//'label' => 'Codigo Entrada:',
										'id'=>'txtCode',
//										'value'=>$code,
										'placeholder'=>'Codigo Entrada'
										));
						?>
						<?php
						echo $this->BootstrapForm->input('document_code', array(				
								'id'=>'txtCodeDocument',
//								'value'=>$document_code,
								'placeholder'=>'Codigo Documento'
								));
						?>

					<?php
						echo $this->BootstrapForm->submit('<i class="icon-search icon-white"></i>',array('class'=>'btn btn-primary','div'=>false, 'id'=>'btnSearch', 'title'=>'Buscar'));
					?>

			</fieldset>
			<?php echo $this->BootstrapForm->end();?>
			<!-- ////////////////////////////////////////FIN - FORMULARIO BUSQUEDA////////////////////////////////////////////////-->		
			</div>
		</div>
		<!-- *********************************************** #UNICORN SEARCH WRAP ********************************************-->
<!-- *********************************************** #UNICORN TABLE WRAP ********************************************-->
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5><?php echo $this->BootstrapPaginator->counter(array('format' => __('Página {:page} de {:pages}, mostrando {:current} registros de {:count} total, comenzando en {:start}, terminando en {:end}')));?></h5>
		</div>
			<div class="widget-content nopadding">
		<!-- *********************************************** #UNICORN TABLE WRAP ********************************************-->

			<?php $cont = $this->BootstrapPaginator->counter('{:start}'); ?>
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('#');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Cliente'/*'Proveedor'*/);?></th>
<!--<th><?php echo $this->BootstrapPaginator->sort('NIT'/*'Proveedor'*/);?></th>
<th><?php echo $this->BootstrapPaginator->sort('Vendedor'/*'Proveedor'*/);?></th>-->
				<th><?php echo $this->BootstrapPaginator->sort('Código');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Fecha');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Descripccion');?></th>				
				<th><?php echo $this->BootstrapPaginator->sort('lc_state', 'Estado Documento');?></th>				
			</tr>
		<?php foreach ($salSales as $salSale): ?>
			<tr>
				<td><?php echo $cont++;?></td>				
				<td>
					<?php echo $this->Html->link($salSale['SalEmployee']['name'], array('controller' => 'sal_employees', 'action' => 'view', $salSale['SalEmployee']['id'])); ?>
				</td>
			<!--	<td>
					<?php echo $this->Html->link($salSale['SalTaxNumber']['nit']/*, array('controller' => 'inv_suppliers', 'action' => 'view', $purPurchase['InvSupplier']['id'])*/); ?>
				</td>
				<td>
					<?php echo $this->Html->link($salSale['AdmUser']['name']/*, array('controller' => 'inv_suppliers', 'action' => 'view', $purPurchase['InvSupplier']['id'])*/); ?>
				</td>-->
				<td><?php echo h($salSale['SalSale']['doc_code']); ?>&nbsp;</td>
				<td><?php echo h($salSale['SalSale']['date']); ?>&nbsp;</td>
				<td><?php echo h($salSale['SalSale']['description']); ?>&nbsp;</td>				
				<td><?php 
						$documentState = $salSale['SalSale']['lc_state'];
						switch ($documentState){
							case 'ORDER_PENDANT':
								$stateColor = 'btn-warning';
								$stateName = 'Nota Pendiente';
								break;
							case 'ORDER_APPROVED':
								$stateColor = 'btn-success';
								$stateName = 'Nota Aprobada';
								break;
							case 'ORDER_CANCELLED':
								$stateColor = 'btn-danger';
								$stateName = 'Nota Cancelada';
								break;						
						}
						
						///////////START - SETTING URL AND PARAMETERS/////////////
					$url = array();
					$parameters = $this->passedArgs;
						$url['action'] = 'save_order';
						$parameters['id']=$salSale['SalSale']['id'];
						
					////////////END - SETTING URL AND PARAMETERS//////////////
						
						echo $this->Html->link('<i class="icon-pencil icon-white"></i>'.__(' '.$stateName),  array_merge($url,$parameters), array('class'=>'btn '.$stateColor, 'escape'=>false, 'title'=>'Editar')); 
					
					?>&nbsp;
				</td>
				
			</tr>
		<?php endforeach; ?>
		</table>

		<!-- *********************************************** #UNICORN TABLE WRAP ********************************************-->
		</div>
	</div>
	<!-- *********************************************** #UNICORN TABLE WRAP ********************************************-->
		<?php echo $this->BootstrapPaginator->pagination(); ?>
<!-- ************************************************************************************************************************ -->
</div><!-- FIN CONTAINER FLUID/ROW FLUID/SPAN12 - Del Template Principal #UNICORN
<!-- ************************************************************************************************************************ -->
<!--</div>--><!-- No va porque ya esta dentro del row-fluid del container del template principal-->