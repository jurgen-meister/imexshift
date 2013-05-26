	<div class="span9">
		<h2>
			<?php echo $this->Html->link('<i class="icon-plus icon-white"></i>', array('action' => 'add'), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Nuevo'));?>
			<?php echo __('Lista de %s', __('NITs'));?>			
		</h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Página {:page} de {:pages}, mostrando {:current} registros de {:count} en total, comenzando en {:start}, terminando en {:end}')));?>
		</p>
			<?php $cont = $this->BootstrapPaginator->counter('{:start}');?>
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<th><?php echo "#";?></th>
				<th><?php echo $this->BootstrapPaginator->sort('sal_customer_id', 'Cliente');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('nit');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name','Nombre');?></th>
				<th class="actions"><?php echo __('Acciones');?></th>
			</tr>
		<?php foreach ($salTaxNumbers as $salTaxNumber): ?>
			<tr>
				<td><?php echo $cont++;?></td>
				<td>
					<?php echo $this->Html->link($salTaxNumber['SalCustomer']['name'], array('controller' => 'sal_customers', 'action' => 'view', $salTaxNumber['SalCustomer']['id'])); ?>
				</td>
				<td><?php echo h($salTaxNumber['SalTaxNumber']['nit']); ?>&nbsp;</td>
				<td><?php echo h($salTaxNumber['SalTaxNumber']['name']); ?>&nbsp;</td>				
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $salTaxNumber['SalTaxNumber']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $salTaxNumber['SalTaxNumber']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $salTaxNumber['SalTaxNumber']['id']), null, __('Are you sure you want to delete # %s?', $salTaxNumber['SalTaxNumber']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>