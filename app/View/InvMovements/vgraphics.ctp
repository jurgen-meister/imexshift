
<?php //echo $this->Html->script('excanvas.min', FALSE); ?>
<?php //echo $this->Html->script('jquery.min', FALSE); ?>
<?php //echo $this->Html->script('jquery.ui.custom', FALSE); ?>
<?php //echo $this->Html->script('bootstrap.min', FALSE); ?>

<?php echo $this->Html->script('jquery.flot.min', FALSE); ?>
<?php echo $this->Html->script('jquery.flot.pie.min', FALSE); ?>
<?php echo $this->Html->script('jquery.flot.resize.min', FALSE); ?>
<?php echo $this->Html->script('unicorn', FALSE); ?>
<?php echo $this->Html->script('modules/InvGraphics', FALSE); ?>


<!-- ************************************************************************************************************************ -->
<div class="span12"><!-- START CONTAINER FLUID/ROW FLUID/SPAN12 - FROM MAIN TEMPLATE #UNICORN -->
<!-- ************************************************************************************************************************ -->
	<!-- //////////////////////////// Start - buttons /////////////////////////////////-->
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class=" icon-search"></i>
			</span>
			<h5>Filtros</h5>
		</div>
		<div class="widget-content nopadding">
			<?php 
				/////////////////START - SETTINGS BUTTON CANCEL /////////////////
				//echo $this->Html->link('<i class="icon-cog icon-white"></i> Generar Reporte', array('#'), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Nuevo', 'id'=>'btnPrint')); 
			?>
			<?php echo $this->BootstrapForm->create('InvMovement', array('class' => 'form-horizontal', 'novalidate' => true));?>
				<?php
				echo $this->BootstrapForm->input('year', array(
					'label' => 'Gestión:',
					'id'=>'cbxYear',
					'type'=>'select',
					'class'=>'span2',
					'options'=>$years 
				));
				echo $this->BootstrapForm->input('warehouse', array(
					'label' => 'Almacen:',
					'id'=>'cbxWarehouse',
					'type'=>'select',
					'options'=>$warehouses,
				));
				echo $this->BootstrapForm->input('item', array(
					'label' => 'Item:',
					'id'=>'cbxItem',
					'class'=>'span8',
					'type'=>'select',
					'options'=>$items,
				));
				?>
			<label id="processing"></label>
			<?php echo $this->BootstrapForm->end();?>
			<div id="boxMessage"></div>
		</div>
	</div>
	<!-- //////////////////////////// End - buttons /////////////////////////////////-->
	
<!-- *********************************************** #UNICORN SEARCH WRAP ********************************************-->
<div class="row-fluid">
	<div class="span8">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class=" icon-signal"></i>
				</span>
				<h5>Entradas (Items - Meses)</h5>
			</div>
			<div class="widget-content nopadding">
				<div class="bars"></div>
			</div>	
		</div>
	</div>
	<div class="span4">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class=" icon-signal"></i>
				</span>
				<h5>Entradas (Items - Tipos)</h5>
			</div>
			<div class="widget-content nopadding">
				<div class="pie"></div>
			</div>	
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span8">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class=" icon-signal"></i>
				</span>
				<h5>Salidas (Items - Meses)</h5>
			</div>
			<div class="widget-content nopadding">
				<div class="bars2"></div>
			</div>	
		</div>
	</div>
	<div class="span4">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class=" icon-signal"></i>
				</span>
				<h5>Salidas (Items - Tipos)</h5>
			</div>
			<div class="widget-content nopadding">
				<div class="pie2"></div>
			</div>	
		</div>
	</div>
</div>
<!-- *********************************************** #UNICORN SEARCH WRAP ********************************************-->
		
	
	
<!-- ************************************************************************************************************************ -->
</div><!-- END CONTAINER FLUID/ROW FLUID/SPAN12 - FROM MAIN TEMPLATE #UNICORN
<!-- ************************************************************************************************************************ -->