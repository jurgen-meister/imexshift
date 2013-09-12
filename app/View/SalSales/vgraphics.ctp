<?php echo $this->Html->script('jquery.flot.min', FALSE); ?>
<?php echo $this->Html->script('jquery.flot.pie.min', FALSE); ?>
<?php echo $this->Html->script('jquery.flot.resize.min', FALSE); ?>
<?php echo $this->Html->script('unicorn', FALSE); ?>
<?php echo $this->Html->script('modules/SalGraphics', FALSE); ?>


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
				echo $this->BootstrapForm->input('currency', array(
					'label' => 'Cambio:',
					'id'=>'cbxCurrency',
					'type'=>'select',
					'options'=>array("bolivianos"=>"BOLIVIANOS", "dolares"=>"DOLARES")
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
	<div class="span12">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class=" icon-signal"></i>
				</span>
				<h5>Ventas - Meses</h5>
			</div>
			<div class="widget-content nopadding">
				<div class="bars"></div>
			</div>	
		</div>
	</div>
</div>

<!-- *********************************************** #UNICORN SEARCH WRAP ********************************************-->
		
	
	
<!-- ************************************************************************************************************************ -->
</div><!-- END CONTAINER FLUID/ROW FLUID/SPAN12 - FROM MAIN TEMPLATE #UNICORN
<!-- ************************************************************************************************************************ -->