<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('Entradas de Compras al Almacen');?></h2>
		<p>
			<?php echo $this->Html->image("add.png");
			echo $this->Html->link(('Nuevo'), array('action' => 'add_in')); 
			?>
		</p>
		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Pagina {:page} de {:pages}, mostrando {:current} registros de {:count} total, comenzando en  {:start}, terminando en {:end}')));?>
		</p>
		<?php $cont = 1;?>
		<table class="table table-striped table-bordered">
			<tr>
				<th><?php echo "#";?></th>
				<th><?php echo $this->BootstrapPaginator->sort('code', 'Codigo');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('inv_movement_type_id', 'Movimiento');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('inv_warehouse_id', 'Almacen');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('inv_item_id', 'Item');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date', 'Fecha');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('quantity', 'Cantidad');?></th>

				<th class="actions"><?php echo __('Acciones');?></th>
			</tr>
		<?php foreach ($invMovements as $invMovement): ?>
			<tr>
				<td><?php echo $cont++;?></td>
				<td><?php echo h($invMovement['InvMovement']['code']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($invMovement['InvMovementType']['name'], array('controller' => 'inv_movement_types', 'action' => 'view', $invMovement['InvMovementType']['id'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link($invMovement['InvWarehouse']['name'], array('controller' => 'inv_warehouses', 'action' => 'view', $invMovement['InvWarehouse']['id'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link($invMovement['InvItem']['name'], array('controller' => 'inv_items', 'action' => 'view', $invMovement['InvItem']['id'])); ?>
				</td>
				<td>
					<?php 
					echo date("d/m/Y", strtotime($invMovement['InvMovement']['date']));
					?>
					&nbsp;</td>
				<td><?php echo h($invMovement['InvMovement']['quantity']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('Ver'), array('action' => 'view', $invMovement['InvMovement']['id'])); ?>
					
					<?php 
						echo $this->Html->link(__('Editar'), array('action' => 'edit_in', $invMovement['InvMovement']['id'])); 
					?>
					<?php echo $this->Form->postLink(__('Eliminar'), array('action' => 'delete', $invMovement['InvMovement']['id']), null, __('Are you sure you want to delete # %s?', $invMovement['InvMovement']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
</div>