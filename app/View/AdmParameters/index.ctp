<?php echo $this->Html->script('jquery.validate', FALSE);?>
<?php echo $this->Html->script('jquery.dataTables.min.js', FALSE); ?>
<?php // echo $this->Html->script('mainBittion', FALSE);?>
<?php echo $this->Html->script('modules/AdmParameters', FALSE); ?>
<div class="span12">
	<h3><a href="#" class="btn btn-primary" title="Nuevo" id="btnAdd"><i class="icon-plus icon-white"></i></a>
		<?php echo __('%s', __('Parámetros')); ?>
	</h3>
	<div class="widget-box">
		<div class="widget-content nopadding">
			<table class="table table-bordered table-hover" id="dataTable">
				<thead>
					<th style="width: 4%">#</th>
					<th>Nombre</th>
					<th>Descripción</th>
					<th></th>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>