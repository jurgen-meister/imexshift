
<div class="span9">
		<h2><?php echo __('Compras');?></h2>
		<p>
			<a href="save_in" id="btnChangeState" class="btn btn-primary" title="Nuevo"><i class="icon-plus icon-white"></i> Nuevo</a>
			
		</p>
		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Pagina {:page} de {:pages}, mostrando {:current} registros de {:count} total, comenzando en  {:start}, terminando en {:end}')));?>
		</p>
		<?php $cont = 1;?>
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<th><?php echo "#";?></th>
				<th><?php echo $this->BootstrapPaginator->sort('code', 'Codigo Compra');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date', 'Fecha');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('inv_supplier_id', 'Proveedor');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Accion');?></th>
			</tr>
		<?php foreach ($purPurchases as $purPurchase): ?>
			<tr>
				<td><?php echo $cont++;?></td>
				<td><?php echo h($purPurchase['PurPurchase']['code']); ?>&nbsp;</td>
				<td>
					<?php 
					echo date("d/m/Y", strtotime($purPurchase['PurPurchase']['date']));
					?>
					&nbsp;
				</td>
				<td>
					<?php echo h($purPurchase['InvSupplier']['name']); ?>
				</td>
				<td><?php
					echo $this->Html->link('<i class="icon-pencil icon-white"></i>'.__(' Aprobado'), array('action' => 'save_purchase', $purPurchase['PurPurchase']['id']), array('class'=>'btn btn-success', 'escape'=>false, 'title'=>'Editar')); 
					?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
</div>