<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm Actions'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_controller_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('description');?></th>
				
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admActions as $admAction): ?>
			<tr>
				<td><?php echo h($admAction['AdmAction']['id']); ?>&nbsp;</td>
				
				<td>
					<?php echo $this->Html->link($admAction['AdmController']['name'], array('controller' => 'adm_controllers', 'action' => 'view', $admAction['AdmController']['id'])); ?>
				</td>
				<td><?php echo h($admAction['AdmAction']['name']); ?>&nbsp;</td>
				<td><?php echo h($admAction['AdmAction']['description']); ?>&nbsp;</td>
				
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admAction['AdmAction']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admAction['AdmAction']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admAction['AdmAction']['id']), null, __('Are you sure you want to delete # %s?', $admAction['AdmAction']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
</div>