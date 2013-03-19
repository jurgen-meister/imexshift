<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm Transitions'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_state_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_transaction_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_final_state_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_transaction');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date_modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admTransitions as $admTransition): ?>
			<tr>
				<td><?php echo h($admTransition['AdmTransition']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($admTransition['AdmState']['name'], array('controller' => 'adm_states', 'action' => 'view', $admTransition['AdmState']['id'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link($admTransition['AdmAction']['name'], array('controller' => 'adm_actions', 'action' => 'view', $admTransition['AdmAction']['id'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link($admTransition['AdmFinalState']['name'], array('controller' => 'adm_states', 'action' => 'view', $admTransition['AdmFinalState']['id'])); ?>
				</td>
				<td><?php echo h($admTransition['AdmTransition']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($admTransition['AdmTransition']['lc_transaction']); ?>&nbsp;</td>
				<td><?php echo h($admTransition['AdmTransition']['creator']); ?>&nbsp;</td>
				<td><?php echo h($admTransition['AdmTransition']['date_created']); ?>&nbsp;</td>
				<td><?php echo h($admTransition['AdmTransition']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($admTransition['AdmTransition']['date_modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admTransition['AdmTransition']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admTransition['AdmTransition']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admTransition['AdmTransition']['id']), null, __('Are you sure you want to delete # %s?', $admTransition['AdmTransition']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
</div>