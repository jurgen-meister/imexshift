	<div class="span9">
		<h2>
			<?php echo $this->Html->link('<i class="icon-plus icon-white"></i>', array('action' => 'add'), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Nuevo'));?>
			<?php echo __('Lista de %s', __('Clientes'));?>
		</h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Página {:page} de {:pages}, mostrando {:current} registros de {:count} en total, comenzando en {:start}, terminando en {:end}')));?>
		</p>
			<?php $cont = $this->BootstrapPaginator->counter('{:start}');?>
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<th><?php echo "#";?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name','Nombre');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('address','Direccion');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('phone','Telf./Cel.');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('email');?></th>				
				<th class="actions"><?php echo __('Acciones');?></th>
			</tr>
		<?php foreach ($salCustomers as $salCustomer): ?>
			<tr>
				<td><?php echo $cont++;?></td>
				<td><?php echo h($salCustomer['SalCustomer']['name']); ?>&nbsp;</td>
				<td><?php echo h($salCustomer['SalCustomer']['address']); ?>&nbsp;</td>
				<td><?php echo h($salCustomer['SalCustomer']['phone']); ?>&nbsp;</td>
				<td><?php echo h($salCustomer['SalCustomer']['email']); ?>&nbsp;</td>				
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $salCustomer['SalCustomer']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $salCustomer['SalCustomer']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $salCustomer['SalCustomer']['id']), null, __('Are you sure you want to delete # %s?', $salCustomer['SalCustomer']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
</div>