<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('Tipos de Movimiento');?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>
<?php $cont = 1;?>
		<table class="table">
			<tr>
				<th><?php echo h('#');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name', 'Nombre');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('status', 'Status');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('document', 'Documento');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('ref_table', 'Tabla BD Ref');?></th>
				<th class="actions"><?php echo __('Acciones');?></th>
			</tr>
		<?php foreach ($invMovementTypes as $invMovementType): ?>
			<tr>
				<td><?php echo h($cont); ?>&nbsp;</td>
				<td><?php echo h($invMovementType['InvMovementType']['name']); ?>&nbsp;</td>
				<td><?php echo h($invMovementType['InvMovementType']['status']); ?>&nbsp;</td>
				<td><?php 
				if($invMovementType['InvMovementType']['document'] == 1){
					echo "Si";
				}else{
					echo "No";
				}
				?>&nbsp;</td>
				<td><?php echo h($invMovementType['InvMovementType']['ref_table']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('Ver'), array('action' => 'view', $invMovementType['InvMovementType']['id'])); ?>
					<?php echo $this->Html->link(__('Editar'), array('action' => 'edit', $invMovementType['InvMovementType']['id'])); ?>
					<?php echo $this->Form->postLink(__('Eliminar'), array('action' => 'delete', $invMovementType['InvMovementType']['id']), null, __('Are you sure you want to delete # %s?', $invMovementType['InvMovementType']['id'])); ?>
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
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement Type')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movements')), array('controller' => 'inv_movements', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement')), array('controller' => 'inv_movements', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>