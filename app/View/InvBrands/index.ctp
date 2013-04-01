<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('Marcas');?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>
	<?php $cont=1;?>
		<table class="table">
			<tr>
				<th><?php echo h('#');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name', 'Nombre');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('description', 'Descripcion');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('country_source', 'Pais de Origen:');?></th>
				<th class="actions"><?php echo __('Acciones');?></th>
			</tr>
		<?php foreach ($invBrands as $invBrand): ?>
			<tr>
				<td><?php echo h($cont); ?>&nbsp;</td>
				<td><?php echo h($invBrand['InvBrand']['name']); ?>&nbsp;</td>
				<td><?php echo h($invBrand['InvBrand']['description']); ?>&nbsp;</td>
				<td><?php echo h($invBrand['InvBrand']['country_source']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('Ver'), array('action' => 'view', $invBrand['InvBrand']['id'])); ?>
					<?php echo $this->Html->link(__('Editar'), array('action' => 'edit', $invBrand['InvBrand']['id'])); ?>
					<?php echo $this->Form->postLink(__('Eliminar'), array('action' => 'delete', $invBrand['InvBrand']['id']), null, __('Esta seguro de eliminar?', $invBrand['InvBrand']['id'])); ?>
				</td>
			</tr>
		<?php $cont++; endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Nuevas Marcas'), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('Lista de Items'), array('controller' => 'inv_items', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('Nuevo Item'), array('controller' => 'inv_items', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>