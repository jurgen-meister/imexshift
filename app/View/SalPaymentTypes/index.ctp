	<div class="span9">
		<h2>
			<?php echo $this->Html->link('<i class="icon-plus icon-white"></i>', array('action' => 'add'), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Nuevo'));?>
			<?php echo __('Lista de %s', __('Tipos de Cobro'));?>
		</h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Página {:page} de {:pages}, mostrando {:current} registros de {:count} en total, comenzando en {:start}, terminando en {:end}')));?>
		</p>
			<?php $cont = $this->BootstrapPaginator->counter('{:start}');?>
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<th><?php echo "#";?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name', 'Nombre');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('description', 'Descripccion');?></th>				
				<th class="actions"><?php echo __('Acciones');?></th>
			</tr>
		<?php foreach ($salPaymentTypes as $salPaymentType): ?>
			<tr>
				<td><?php echo $cont++;?></td>
				<td><?php echo h($salPaymentType['SalPaymentType']['name']); ?>&nbsp;</td>
				<td><?php echo h($salPaymentType['SalPaymentType']['description']); ?>&nbsp;</td>				
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $salPaymentType['SalPaymentType']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $salPaymentType['SalPaymentType']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $salPaymentType['SalPaymentType']['id']), null, __('Are you sure you want to delete # %s?', $salPaymentType['SalPaymentType']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>