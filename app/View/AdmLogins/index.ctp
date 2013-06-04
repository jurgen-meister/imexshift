<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm Logins'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_user_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admLogins as $admLogin): ?>
			<tr>
				<td><?php echo h($admLogin['AdmLogin']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($admLogin['AdmUser']['id'], array('controller' => 'adm_users', 'action' => 'view', $admLogin['AdmUser']['id'])); ?>
				</td>
				<td><?php echo h($admLogin['AdmLogin']['date']); ?>&nbsp;</td>
				<td><?php echo h($admLogin['AdmLogin']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($admLogin['AdmLogin']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($admLogin['AdmLogin']['creator']); ?>&nbsp;</td>
				<td><?php echo h($admLogin['AdmLogin']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($admLogin['AdmLogin']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($admLogin['AdmLogin']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admLogin['AdmLogin']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admLogin['AdmLogin']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admLogin['AdmLogin']['id']), null, __('Are you sure you want to delete # %s?', $admLogin['AdmLogin']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Login')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Users')), array('controller' => 'adm_users', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm User')), array('controller' => 'adm_users', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>