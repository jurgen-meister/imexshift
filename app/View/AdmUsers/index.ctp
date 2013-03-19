<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm Users'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_job_title_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('login');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('password');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('active');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('active_date');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admUsers as $admUser): ?>
			<tr>
				<td><?php echo h($admUser['AdmUser']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($admUser['AdmJobTitle']['name'], array('controller' => 'adm_job_titles', 'action' => 'view', $admUser['AdmJobTitle']['id'])); ?>
				</td>
				<td><?php echo h($admUser['AdmUser']['login']); ?>&nbsp;</td>
				<td><?php echo h($admUser['AdmUser']['password']); ?>&nbsp;</td>
				<td><?php echo h($admUser['AdmUser']['active']); ?>&nbsp;</td>
				<td><?php echo h($admUser['AdmUser']['active_date']); ?>&nbsp;</td>
				<td><?php echo h($admUser['AdmUser']['lc_state']); ?>&nbsp;</td>
				
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admUser['AdmUser']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admUser['AdmUser']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admUser['AdmUser']['id']), null, __('Are you sure you want to delete # %s?', $admUser['AdmUser']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
</div>