<?php echo $this->Html->script('jquery.dataTables.min.js', FALSE); ?>
<?php echo $this->Html->script('jquery.uniform.js', FALSE); ?>
<?php echo $this->Html->script('modules/InvReports', FALSE); ?>

<!-- ************************************************************************************************************************ -->
<div class="span12"><!-- START CONTAINER FLUID/ROW FLUID/SPAN12 - FROM MAIN TEMPLATE #UNICORN -->
<!-- ************************************************************************************************************************ -->
	<!-- //////////////////////////// Start - buttons /////////////////////////////////-->
	<div class="widget-box">
		<div class="widget-content nopadding">
			<?php 
				/////////////////START - SETTINGS BUTTON CANCEL /////////////////
				//echo $this->Html->link('<i class="icon-cog icon-white"></i> Generar Reporte', array('#'), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Nuevo', 'id'=>'btnPrint')); 
			?>
			<a href="#" id="btnGenerateReport" class="btn btn-primary noPrint "><i class="icon-cog icon-white"></i> Generar Reporte</a>
			<div id="boxMessage"></div>
		</div>
	</div>
	<!-- //////////////////////////// End - buttons /////////////////////////////////-->

		<!-- *********************************************** #UNICORN SEARCH WRAP ********************************************-->
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class=" icon-align-justify"></i>
				</span>
				<h5>Reporte de Movimientos de Inventario</h5>
			</div>
			<div class="widget-content nopadding">
			<!-- ////////////////////////////////////////START - FILTERS////////////////////////////////////////////////-->
				
					<form class="form-horizontal">
					  
						<?php 
						echo '<div class="row-fluid">';
						  echo $this->BootstrapForm->input('start_date', array(
							'label' => '* Fecha Inicio:',
							'id'=>'txtReportStartDate'
						  ));
						  
						  echo $this->BootstrapForm->input('finish_date', array(
							'label' => '* Fecha Fin:',
							'id'=>'txtReportFinishDate'
						  ));
						echo '</div>';
						
						echo '<div class="row-fluid">';
						  echo $this->BootstrapForm->input('movement_type', array(
							'label' => '* Tipo de Movimiento:',
							'id'=>'cbxReportMovementTypes',
							'type'=>'select',
							'class'=>'span4',  	
							'options'=>array(0=>'TODAS LAS ENTRADAS', 1=>'Entradas de compra', 5=>'Entradas de apertura',4=>'Entradas de traspaso', 6=>'Entradas otras', 999=>'TODAS LAS SALIDAS', 2=>'Salidas de venta', 3=>'Salidas de traspaso', 7=>'Salidas otras', 1000=>'ENTRADAS Y SALIDAS', 1001=>'TRASPASOS ENTRE ALMACENES')  
						  ));
						echo '</div>';
						  
						echo '<div class="row-fluid">';
						  echo $this->BootstrapForm->input('warehouse', array(
							'label' => '* Almacen:',
							'id'=>'cbxReportWarehouses',
							'type'=>'select',
							//'multiple'=>'multiple',
							'options'=>$warehouse,
							//'selected'=>  array_keys($warehouse),
							'class'=>'span6'  
						  ));
						echo '</div>';
						
						echo '<div class="row-fluid">';
						  echo $this->BootstrapForm->input('currency', array(
							'label' => '* Tipo de Cambio:',
							'id'=>'cbxReportCurrency',
							'type'=>'select',
							//'multiple'=>'multiple',
							'options'=>array('BOLIVIANOS'=>'Bolivianos', 'DOLARES AMERICANOS'=>'Dolares Americanos'),
							//'selected'=>  array_keys($warehouse),
							'class'=>'span6'  
						  ));
						echo '</div>';
						
						echo '<div class="row-fluid">';
						  echo $this->BootstrapForm->input('type', array(
							'label' => '* Agrupar por:',
							'id'=>'cbxReportGroupTypes',
							'type'=>'select',
							'class'=>'span4',    
							'options'=>array('none'=>'Ninguno','brand'=>'Marca','category'=> 'Categoria')  
						  ));
						echo '</div>';	  
				?>
			</form>
			
			<div id="boxProcessing" align="center"></div>
			<div id="boxGroupItemsAndFilters">
				<table class="table table-bordered data-table with-check">
					<thead>
					<tr>
						<th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" checked="checked" /></th>
						<th>Item</th>
						<th>Marca</th>
						<th>Categoria</th>
					</tr>
					</thead>

					<tbody>
					<?php foreach($item as $val){ ?>	
					<tr>
						<td><input type="checkbox" checked="checked" value="<?php echo $val['InvItem']['id'];?>" /></td>
						<td><?php echo '[ '.$val['InvItem']['code'].' ] '.$val['InvItem']['name'];?></td>
						<td><?php echo $val['InvBrand']['name'];?></td>
						<td><?php echo $val['InvCategory']['name'];?></td>
					</tr>
					<?php } ?>
					</tbody>
				</table>  
			</div>
			<!-- ////////////////////////////////////////END - FILTERS////////////////////////////////////////////////-->		
			</div>
		<!-- *********************************************** #UNICORN SEARCH WRAP ********************************************-->
		</div>
		
<!-- ************************************************************************************************************************ -->
</div><!-- END CONTAINER FLUID/ROW FLUID/SPAN12 - FROM MAIN TEMPLATE #UNICORN
<!-- ************************************************************************************************************************ -->
