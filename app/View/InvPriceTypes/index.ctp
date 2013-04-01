<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('%s', __('Tipos de Precios'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('description');?></th>				
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($invPriceTypes as $invPriceType): ?>
			<tr>
				<td><?php echo h($invPriceType['InvPriceType']['id']); ?>&nbsp;</td>
				<td><?php echo h($invPriceType['InvPriceType']['name']); ?>&nbsp;</td>
				<td><?php echo h($invPriceType['InvPriceType']['description']); ?>&nbsp;</td>				
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $invPriceType['InvPriceType']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $invPriceType['InvPriceType']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $invPriceType['InvPriceType']['id']), null, __('Are you sure you want to delete # %s?', $invPriceType['InvPriceType']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
<!--	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Price Type')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Prices')), array('controller' => 'inv_prices', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Price')), array('controller' => 'inv_prices', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>-->
</div>