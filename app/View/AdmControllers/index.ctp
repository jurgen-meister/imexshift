<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm Controllers'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_module_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('initials');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('description');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admControllers as $admController): ?>
			<tr>
				<td><?php echo h($admController['AdmController']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($admController['AdmModule']['name'], array('controller' => 'adm_modules', 'action' => 'view', $admController['AdmModule']['id'])); ?>
				</td>
				<td><?php echo h($admController['AdmController']['name']); ?>&nbsp;</td>
				<td><?php echo h($admController['AdmController']['initials']); ?>&nbsp;</td>
				<td><?php echo h($admController['AdmController']['description']); ?>&nbsp;</td>
				<td><?php echo h($admController['AdmController']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($admController['AdmController']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($admController['AdmController']['creator']); ?>&nbsp;</td>
				<td><?php echo h($admController['AdmController']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($admController['AdmController']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($admController['AdmController']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admController['AdmController']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admController['AdmController']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admController['AdmController']['id']), null, __('Are you sure you want to delete # %s?', $admController['AdmController']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
</div>