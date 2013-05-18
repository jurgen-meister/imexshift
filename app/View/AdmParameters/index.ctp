<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('Lista de %s', __('Parametros'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Nombre');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Descripccion');?></th>				
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admParameters as $admParameter): ?>
			<tr>
				<td><?php echo h($admParameter['AdmParameter']['id']); ?>&nbsp;</td>
				<td><?php echo h($admParameter['AdmParameter']['name']); ?>&nbsp;</td>
				<td><?php echo h($admParameter['AdmParameter']['description']); ?>&nbsp;</td>				
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admParameter['AdmParameter']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admParameter['AdmParameter']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admParameter['AdmParameter']['id']), null, __('Are you sure you want to delete # %s?', $admParameter['AdmParameter']['id'])); ?>
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
			<li><?php echo $this->Html->link(__('New %s', __('Adm Parameter')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Parameter Details')), array('controller' => 'adm_parameter_details', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Parameter Detail')), array('controller' => 'adm_parameter_details', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>-->
</div>