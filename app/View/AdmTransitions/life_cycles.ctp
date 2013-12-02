<?php echo $this->Html->script('jquery.validate', FALSE); ?>
<?php // echo $this->Html->script('jquery.dataTables.min.js', FALSE);    ?>
<?php echo $this->Html->script('modules/AdmTransitions', FALSE); ?>
<!--<div style=" font-size: 28px; font-weight: bold; ">
	<a href="#" class="btn btn-primary" title="Nuevo" id="btnAdd"><i class="icon-plus icon-white"></i></a>
<?php // echo __('%s', __('Ciclos de Vida')); ?>
</div>-->
<div class="span12">
	<h3><a href="#" class="btn btn-primary" title="Nuevo" id="btnAdd"><i class="icon-plus icon-white"></i></a>
<!--			<h3>	-->
		<?php echo __('%s', __('Ciclos de Vida')); ?>
	</h3>
	<div class="row-fluid">
			<?php
			echo $this->BootstrapForm->create('Controller', array('class' => 'form-horiziontal'));
			echo'<fieldset>';
			echo $this->BootstrapForm->input('controller', array(
				'type' => 'select',
				'options' => array(1, 2, 3, 4, 5),
				'id' => 'cbxController',
				'label' => 'Controlador',
//				'div' => false,
				'class' => 'span4',
				'selected' => 0,
				'options' => $controllers
			));
			echo'</fieldset>';
			echo $this->BootstrapForm->end();
			?>
</div>

	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box ">
				<div class="widget-title">
					<ul class="nav nav-tabs">
						<li id="headtabTransition" class="active"><a data-toggle="tab" href="#tab1">Transiciones</a></li>
						<li id="headtabState" ><a data-toggle="tab" href="#tab2">Estados</a></li>
						<li id="headtabTransaction" ><a data-toggle="tab" href="#tab3">Transacciones</a></li>
					</ul>
				</div>
				<div class="widget-content tab-content nopadding"><!-- nopadding -->
					<div id="tab1" class="tab-pane active">
						<!-----------------------------------------Start-Tab1-------------------------------------------------->
						<!--<a href="#" class="btn btn-primary" title="Nuevo" id="btnAddLifeCycle"><i class="icon-plus icon-white"></i></a>-->
						<table class="table table-bordered table-hover" id="dataTableTransition">
							<thead>
							<th style="width: 4%">#</th>
							<th>Estado Inicial</th>
							<th>Transacción</th>
							<th>Estado Final</th>
							<th></th>
							</thead>
							<tbody>
							</tbody>
						</table>
						<!-------------------------------------------End-Tab1------------------------------------------------>
					</div>
					<div id="tab2" class="tab-pane">
						<!--<a href="#" class="btn btn-primary" title="Nuevo" id="btnAddState"><i class="icon-plus icon-white"></i></a>-->
						<!-----------------------------------------Start-Tab2-------------------------------------------------->
						<table class="table table-bordered table-hover" id="dataTableStates">
							<thead>
							<th style="width: 4%">#</th>
							<th>Nombre</th>
							<th>Descripción</th>
							<th></th>
							</thead>
							<tbody>
							</tbody>
						</table>
						<!-------------------------------------------End-Tab2------------------------------------------------>
					</div>
					<div id="tab3" class="tab-pane">
						<!-----------------------------------------Start-Tab2-------------------------------------------------->
						<!--<a href="#" class="btn btn-primary" title="Nuevo" id="btnAddTransaction"><i class="icon-plus icon-white"></i></a>-->
						<table class="table table-bordered table-hover" id="dataTableTransactions">
							<thead>
							<th style="width: 4%">#</th>
							<th>Nombre</th>
							<th>Descripción</th>
							<th>Sentencia</th>
							<th></th>
							</thead>
							<tbody>
							</tbody>
						</table>
						<!-------------------------------------------End-Tab2------------------------------------------------>
					</div>
				</div>
				<div id="boxLoading"></div>
			</div>
		</div>
	</div>
</div>

<!--<ul class="nav nav-tabs" id="myTab">
	<li class="active"><a href="#states" data-toggle="tab">Estados</a></li>
	<li><a href="#transactions" data-toggle="tab">Transacciones</a></li>
	<li><a href="#transitions" data-toggle="tab">Transiciones</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="states">.LUSTRANDO BOTAS..</div>
	<div class="tab-pane" id="transactions">meteorito</div>
	<div class="tab-pane" id="transitions">saisisez</div>
</div>				-->
