	<div class="span9">
		<h2><?php echo $this->Html->link('<i class="icon-plus icon-white"></i>', array('action' => 'add'), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Nuevo'));?>
			<?php echo __('Lista de %s', __('Precios'));?>
		</h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Página {:page} de {:pages}, mostrando {:current} registros de {:count} en total, comenzando en {:start}, terminando en {:end}')));?>
		</p>
		<?php $cont = $this->BootstrapPaginator->counter('{:start}');?>
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<th><?php echo "#";?></th>
				<th><?php echo $this->BootstrapPaginator->sort('inv_item_id', 'Item');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('inv_price_type_id', 'Tipo de Precio');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('price', 'Monto');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('description', 'Descripcion');?></th>				
				<th class="actions"><?php echo __('Acciones');?></th>
			</tr>
		<?php foreach ($invPrices as $invPrice): ?>
			<tr>
				<td><?php echo $cont++;?></td>
				<td>
					<?php echo $this->Html->link($invPrice['InvItem']['full_name'], array('controller' => 'inv_items', 'action' => 'view', $invPrice['InvItem']['id'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link($invPrice['InvPriceType']['name'], array('controller' => 'inv_price_types', 'action' => 'view', $invPrice['InvPriceType']['id'])); ?>
				</td>
				<td><?php echo h($invPrice['InvPrice']['price']); ?>&nbsp;</td>
				<td><?php echo h($invPrice['InvPrice']['description']); ?>&nbsp;</td>				
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $invPrice['InvPrice']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $invPrice['InvPrice']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $invPrice['InvPrice']['id']), null, __('Are you sure you want to delete # %s?', $invPrice['InvPrice']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>	