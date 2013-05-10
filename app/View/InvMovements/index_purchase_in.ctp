
<div class="span9">
		<h2><?php echo __('Entradas de Compras al Almacén');?></h2>
		
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
				<!--<th><?php //echo ('Codigo Entrada Almacen');?></th>-->
				<th><?php echo 'Acción';?></th>
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
				<!--<td></td>-->
				<td><?php
					echo $this->Html->link('<i class="icon-circle-arrow-right icon-white"></i>'.__(' Entrada Almacen'), array('action' => 'save_purchase_in', $purPurchase['PurPurchase']['code']), array('class'=>'btn btn-primary', 'escape'=>false)); 
					?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
</div>