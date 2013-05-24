<div class="span9">
	<h2>	<?php echo $this->Html->link('<i class="icon-plus icon-white"></i>', array('action' => 'save_order'), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Nuevo')); ?>
			<?php echo __('Ordenes de %s', __('Compra'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Página {:page} de {:pages}, mostrando {:current} registros de {:count} total, comenzando en {:start}, terminando en {:end}')));?>
		</p>
			<?php $cont = $this->BootstrapPaginator->counter('{:start}'); ?>
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('#');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Proveedor');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Código');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Fecha');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Descripccion');?></th>				
				<th><?php echo $this->BootstrapPaginator->sort('lc_state', 'Estado Documento');?></th>				
			</tr>
		<?php foreach ($purPurchases as $purPurchase): ?>
			<tr>
				<td><?php echo $cont++;?></td>				
				<td>
					<?php echo $this->Html->link($purPurchase['InvSupplier']['name'], array('controller' => 'inv_suppliers', 'action' => 'view', $purPurchase['InvSupplier']['id'])); ?>
				</td>
				<td><?php echo h($purPurchase['PurPurchase']['code']); ?>&nbsp;</td>
				<td><?php echo h($purPurchase['PurPurchase']['date']); ?>&nbsp;</td>
				<td><?php echo h($purPurchase['PurPurchase']['description']); ?>&nbsp;</td>				
				<td><?php 
						$documentState = $purPurchase['PurPurchase']['lc_state'];
						switch ($documentState){
							case 'ORDER_PENDANT':
								$stateColor = 'btn-warning';
								$stateName = 'Pendiente';
								break;
							case 'ORDER_APPROVED':
								$stateColor = 'btn-succes';
								$stateName = 'Aprobado';
								break;
							case 'ORDER_CANCELED':
								$stateColor = 'btn-danger';
								$stateName = 'Cancelado';
								break;						
						}
						echo $this->Html->link('<i class="icon-pencil icon-white"></i>'.__(' '.$stateName),  array('action' => 'edit', $purPurchase['PurPurchase']['id']), array('class'=>'btn '.$stateColor, 'escape'=>false, 'title'=>'Editar')); 
					?>&nbsp;
				</td>
				
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>