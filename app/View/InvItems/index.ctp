<?php // echo  $this->BootstrapPaginator->options(array('url' => $this->passedArgs));?>	
<div class="span12">
		<h3><?php echo $this->Html->link('<i class="icon-plus icon-white"></i>', array('action' => 'save_item'), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Nuevo'));?>
			<?php echo __('Lista de %s', __('Items'));?>
		</h3>
		
		<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="icon-th"></i>
			</span>
			<h5><?php echo $this->BootstrapPaginator->counter(array('format' => __('Página {:page} de {:pages}, mostrando {:current} de un total de {:count} registros')));?></h5>
		</div>
		<div class="widget-content nopadding">
		
		<?php $cont = $this->BootstrapPaginator->counter('{:start}');?>
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<th><?php echo "#";?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Marca');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Categoría');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Código');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Nombre');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Descipcion');?></th>
<!--				<th><?php echo $this->BootstrapPaginator->sort('Stock');?></th>-->
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
<!--				<td><?php echo h($invItem['InvItem']['stock']); ?>&nbsp;</td>-->
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $invItem['InvItem']['id'])); ?>
					<?php 
//							$url = array();
//							$parameters = $this->passedArgs;
							
							
//							$url['action'] = 'save_item';
//							$parameters['id'] = $invItem['InvItem']['id'];
							echo $this->Html->link(__('Edit'), array('action' => 'save_item', $invItem['InvItem']['id'])); //array_merge($url,$parameters)); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $invItem['InvItem']['id']), null, __('Are you sure you want to delete # %s?', $invItem['InvItem']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
		</div>
		</div>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
</div>
