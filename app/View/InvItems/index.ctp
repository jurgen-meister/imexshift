	<div class="span9">
		<h2><?php echo $this->Html->link('<i class="icon-plus icon-white"></i>', array('action' => 'add'), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Nuevo'));?>
			<?php echo __('Lista de %s', __('Items'));?></h2>
		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Página {:page} de {:pages}, mostrando {:current} registros de {:count} en total, comenzando en {:start}, terminando en {:end}')));?>
		</p>
		<?php $cont = $this->BootstrapPaginator->counter('{:start}');?>
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<th><?php echo "#";?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Marca');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Categoría');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Código');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Nombre');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Descipcion');?></th>								
				<th class="actions"><?php echo __('Acciones');?></th>
			</tr>
		<?php foreach ($invItems as $invItem): ?>
			<tr>
				<td><?php echo $cont++;?></td>
				<td>
					<?php echo $this->Html->link($invItem['InvBrand']['name'], array('controller' => 'inv_brands', 'action' => 'view', $invItem['InvBrand']['id'])); ?>					
				</td>
				<td>
					<?php echo $this->Html->link($invItem['InvCategory']['name'], array('controller' => 'inv_categories', 'action' => 'view', $invItem['InvCategory']['id'])); ?>
				</td>
				<td><?php echo h($invItem['InvItem']['code']); ?>&nbsp;</td>
				<td><?php echo h($invItem['InvItem']['name']); ?>&nbsp;</td>
				<td><?php echo h($invItem['InvItem']['description']); ?>&nbsp;</td>								
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $invItem['InvItem']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $invItem['InvItem']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $invItem['InvItem']['id']), null, __('Are you sure you want to delete # %s?', $invItem['InvItem']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>