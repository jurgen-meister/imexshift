<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Inv Document Type');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($invDocumentType['InvDocumentType']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($invDocumentType['InvDocumentType']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Ref Table'); ?></dt>
			<dd>
				<?php echo h($invDocumentType['InvDocumentType']['ref_table']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($invDocumentType['InvDocumentType']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($invDocumentType['InvDocumentType']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($invDocumentType['InvDocumentType']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($invDocumentType['InvDocumentType']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($invDocumentType['InvDocumentType']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($invDocumentType['InvDocumentType']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Inv Document Type')), array('action' => 'edit', $invDocumentType['InvDocumentType']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Inv Document Type')), array('action' => 'delete', $invDocumentType['InvDocumentType']['id']), null, __('Are you sure you want to delete # %s?', $invDocumentType['InvDocumentType']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Document Types')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Document Type')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movements')), array('controller' => 'inv_movements', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement')), array('controller' => 'inv_movements', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Inv Movements')); ?></h3>
	<?php if (!empty($invDocumentType['InvMovement'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Inv Item Id'); ?></th>
				<th><?php echo __('Inv Warehouse Id'); ?></th>
				<th><?php echo __('Inv Document Type Id'); ?></th>
				<th><?php echo __('Document'); ?></th>
				<th><?php echo __('Code'); ?></th>
				<th><?php echo __('Date'); ?></th>
				<th><?php echo __('Description'); ?></th>
				<th><?php echo __('Quantity'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Transaction'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Date Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Date Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($invDocumentType['InvMovement'] as $invMovement): ?>
			<tr>
				<td><?php echo $invMovement['id'];?></td>
				<td><?php echo $invMovement['inv_item_id'];?></td>
				<td><?php echo $invMovement['inv_warehouse_id'];?></td>
				<td><?php echo $invMovement['inv_document_type_id'];?></td>
				<td><?php echo $invMovement['document'];?></td>
				<td><?php echo $invMovement['code'];?></td>
				<td><?php echo $invMovement['date'];?></td>
				<td><?php echo $invMovement['description'];?></td>
				<td><?php echo $invMovement['quantity'];?></td>
				<td><?php echo $invMovement['lc_state'];?></td>
				<td><?php echo $invMovement['lc_transaction'];?></td>
				<td><?php echo $invMovement['creator'];?></td>
				<td><?php echo $invMovement['date_created'];?></td>
				<td><?php echo $invMovement['modifier'];?></td>
				<td><?php echo $invMovement['date_modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'inv_movements', 'action' => 'view', $invMovement['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'inv_movements', 'action' => 'edit', $invMovement['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'inv_movements', 'action' => 'delete', $invMovement['id']), null, __('Are you sure you want to delete # %s?', $invMovement['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement')), array('controller' => 'inv_movements', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
