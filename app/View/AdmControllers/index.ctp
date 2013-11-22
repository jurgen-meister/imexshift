<?php echo $this->Html->script('jquery.dataTables.min.js', FALSE); ?>
<?php echo $this->Html->script('modules/AdmControllers', FALSE); ?>
<!-- ************************************************************************************************************************ -->
<div class="span12"><!-- START CONTAINER FLUID/ROW FLUID/SPAN12 - FORMATO DE #UNICORN -->
	<!-- ************************************************************************************************************************ -->
	<h3>
		<?php
		echo $this->Html->link('<i class="icon-plus icon-white"></i>', array('action' => 'add'), array('class' => 'btn btn-primary', 'escape' => false, 'title' => 'Nuevo'));
		?>
		<?php echo __(' Controladores'); ?></h3>

	<!-- *********************************************** #UNICORN TABLE WRAP ********************************************-->
	<div class="widget-box">
<!--		<div class="widget-title">
			<span class="icon">
				<i class="icon-th"></i>
			</span>
			<h5><?php //echo $this->BootstrapPaginator->counter(array('format' => __('Página {:page} de {:pages}, mostrando {:current} de un total de {:count} registros'))); ?></h5>
		</div>-->
		<div class="widget-content nopadding">
			<!-- *********************************************** #UNICORN TABLE WRAP ********************************************-->

			<?php //$cont = $this->BootstrapPaginator->counter('{:start}'); ?>
			<table class="table table-bordered table-hover" id="myDataTable">
				<thead>
					<tr>
						<th><?php echo '#'; ?></th>
						<th><?php echo 'Módulo'; ?></th>
						<th><?php echo 'Nombre'; ?></th>
						<th><?php echo 'Iniciales'; ?></th>
						<th><?php echo 'Descripción'; ?></th>
						<th style="width: 15%"></th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>

			<!-- *********************************************** #UNICORN TABLE WRAP ********************************************-->
		</div>
	</div>
	<!-- *********************************************** #UNICORN TABLE WRAP ********************************************-->
	<?php //echo $this->BootstrapPaginator->pagination(); ?>
	<!-- ************************************************************************************************************************ -->
</div><!-- FIN CONTAINER FLUID/ROW FLUID/SPAN12 - Del Template Principal #UNICORN
<!-- ************************************************************************************************************************ --></div>