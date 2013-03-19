<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Adm Profiles'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_user_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('first_name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('last_name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('birthdate');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('birthplace');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('nationality');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('identity_document');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('place_of_issue');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('address');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('email');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('phone');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_state');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('lc_action');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('creator');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modifier');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modified');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admProfiles as $admProfile): ?>
			<tr>
				<td><?php echo h($admProfile['AdmProfile']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($admProfile['AdmUser']['id'], array('controller' => 'adm_users', 'action' => 'view', $admProfile['AdmUser']['id'])); ?>
				</td>
				<td><?php echo h($admProfile['AdmProfile']['first_name']); ?>&nbsp;</td>
				<td><?php echo h($admProfile['AdmProfile']['last_name']); ?>&nbsp;</td>
				<td><?php echo h($admProfile['AdmProfile']['birthdate']); ?>&nbsp;</td>
				<td><?php echo h($admProfile['AdmProfile']['birthplace']); ?>&nbsp;</td>
				<td><?php echo h($admProfile['AdmProfile']['nationality']); ?>&nbsp;</td>
				<td><?php echo h($admProfile['AdmProfile']['identity_document']); ?>&nbsp;</td>
				<td><?php echo h($admProfile['AdmProfile']['place_of_issue']); ?>&nbsp;</td>
				<td><?php echo h($admProfile['AdmProfile']['address']); ?>&nbsp;</td>
				<td><?php echo h($admProfile['AdmProfile']['email']); ?>&nbsp;</td>
				<td><?php echo h($admProfile['AdmProfile']['phone']); ?>&nbsp;</td>
				<td><?php echo h($admProfile['AdmProfile']['lc_state']); ?>&nbsp;</td>
				<td><?php echo h($admProfile['AdmProfile']['lc_action']); ?>&nbsp;</td>
				<td><?php echo h($admProfile['AdmProfile']['creator']); ?>&nbsp;</td>
				<td><?php echo h($admProfile['AdmProfile']['created']); ?>&nbsp;</td>
				<td><?php echo h($admProfile['AdmProfile']['modifier']); ?>&nbsp;</td>
				<td><?php echo h($admProfile['AdmProfile']['modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admProfile['AdmProfile']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admProfile['AdmProfile']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admProfile['AdmProfile']['id']), null, __('Are you sure you want to delete # %s?', $admProfile['AdmProfile']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
</div>