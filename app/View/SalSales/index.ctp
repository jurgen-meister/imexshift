	<div class="span9">
		<h2>
			<?php echo $this->Html->link('<i class="icon-plus icon-white"></i>', array('action' => 'add'), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Nuevo'));?>
			<?php echo __('Lista de %s', __('Notas de Venta'));?>
		</h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Página {:page} de {:pages}, mostrando {:current} registros de {:count} en total, comenzando en {:start}, terminando en {:end}')));?>
		</p>
			<?php $cont = $this->BootstrapPaginator->counter('{:start}');?>
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<th><?php echo "#";?></th>
				<th><?php echo $this->BootstrapPaginator->sort('sal_employee_id', 'Encargado');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('sal_tax_number_id', 'NIT');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('code', 'Código');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('doc_code', 'Cod.Doc.');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date', 'Fecha');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('description', 'Descripcion');?></th>				
				<th class="actions"><?php echo __('Acciones');?></th>
			</tr>
		<?php foreach ($salSales as $salSale): ?>
			<tr>
				<td><?php echo $cont++;?></td>
				<td>
					<?php echo $this->Html->link($salSale['SalEmployee']['id'], array('controller' => 'sal_employees', 'action' => 'view', $salSale['SalEmployee']['id'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link($salSale['SalTaxNumber']['name'], array('controller' => 'sal_tax_numbers', 'action' => 'view', $salSale['SalTaxNumber']['id'])); ?>
				</td>
				<td><?php echo h($salSale['SalSale']['code']); ?>&nbsp;</td>
				<td><?php echo h($salSale['SalSale']['doc_code']); ?>&nbsp;</td>
				<td><?php echo h($salSale['SalSale']['date']); ?>&nbsp;</td>
				<td><?php echo h($salSale['SalSale']['description']); ?>&nbsp;</td>				
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $salSale['SalSale']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $salSale['SalSale']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $salSale['SalSale']['id']), null, __('Are you sure you want to delete # %s?', $salSale['SalSale']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>