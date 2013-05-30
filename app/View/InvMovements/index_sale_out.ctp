<?php echo  $this->BootstrapPaginator->options(array('url' => $this->passedArgs));?>
<!-- ************************************************************************************************************************ -->
<div class="span12"><!-- START CONTAINER FLUID/ROW FLUID/SPAN12 - FORMATO DE #UNICORN -->
<!-- ************************************************************************************************************************ -->
		<h3><?php echo __('Salidas de Ventas del Almacen');?></h3>
		
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
						echo $this->BootstrapForm->input('document_code', array(				
								'id'=>'txtCodeDocument',
								'value'=>$document_code,
								'placeholder'=>'Codigo Venta'
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
				<h5><?php echo $this->BootstrapPaginator->counter(array('format' => __('Página {:page} de {:pages}, mostrando {:current} de un total de {:count} registros')));?></h5>
			</div>
			<div class="widget-content nopadding">
		<!-- *********************************************** #UNICORN TABLE WRAP ********************************************-->
		
		
		<?php $cont = $this->BootstrapPaginator->counter('{:start}');?>
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<th><?php echo "#";?></th>
				<th><?php echo $this->BootstrapPaginator->sort('code', 'Codigo Venta');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date', 'Fecha');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('SalCustomer.name', 'Cliente');?></th>
				<!--<th><?php //echo ('Codigo Salida Almacen');?></th>-->
				<th><?php echo 'Acción';?></th>
			</tr>
		<?php //debug($salSales);
		foreach ($salSales as $salSale): ?>
			<tr>
				<td><?php echo $cont++;?></td>
				<td><?php echo h($salSale['SalSale']['code']); ?>&nbsp;</td>
				<td>
					<?php 
					echo date("d/m/Y", strtotime($salSale['SalSale']['date']));
					?>
					&nbsp;
				</td>
				<td>
					<?php echo h($salSale['SalCustomer']['name']); ?>
				</td>
				<!--<td></td>-->
				<td><?php
				///////////START - SETTING URL AND PARAMETERS/////////////
					$url = array('action'=>'save_sale_out');
					$parameters = $this->passedArgs;
					$parameters['document_code']=$salSale['SalSale']['code'];
				////////////END - SETTING URL AND PARAMETERS//////////////
					$movementsSize = count($movements);
					if($movementsSize > 0){
						for($i=0; $i<$movementsSize; $i++){
							if(trim($movements[$i]['InvMovement']['document_code']) == trim($salSale['SalSale']['code'])){
								if($movements[$i]['InvMovement']['lc_state'] == 'PENDANT'){
									$btnColor = 'btn-warning';
									$btnName = ' Salida Pendiente';
								}elseif( $movements[$i]['InvMovement']['lc_state'] == 'APPROVED'){
									$btnColor = 'btn-success';
									$btnName = ' Salida Aprobada';
								}else{
									$btnColor = 'btn-primary';
									$btnName = ' Salida Nueva';
								}
							}
						}
					}else{
						$btnColor = 'btn-primary';
						$btnName = ' Salida Nueva';
					}
					echo $this->Html->link('<i class="icon-circle-arrow-right icon-white"></i>'.__($btnName), array_merge($url, $parameters), array('class'=>'btn '.$btnColor, 'escape'=>false)); 
					?>
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
</div><!-- FIN CONTAINER FLUID/ROW FLUID/SPAN12 - Del Template Principal #UNICORN-->