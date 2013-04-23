<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Inv Movement');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($invMovement['InvMovement']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Code'); ?></dt>
			<dd>
				<?php echo h($invMovement['InvMovement']['code']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date'); ?></dt>
			<dd>
				<?php echo h($invMovement['InvMovement']['date']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Description'); ?></dt>
			<dd>
				<?php echo h($invMovement['InvMovement']['description']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Inv Movement Type'); ?></dt>
			<dd>
				<?php echo $this->Html->link($invMovement['InvMovementType']['name'], array('controller' => 'inv_movement_types', 'action' => 'view', $invMovement['InvMovementType']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Document'); ?></dt>
			<dd>
				<?php echo h($invMovement['InvMovement']['document']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($invMovement['InvMovement']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transition'); ?></dt>
			<dd>
				<?php echo h($invMovement['InvMovement']['lc_transition']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Inv Movement')), array('action' => 'edit', $invMovement['InvMovement']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Inv Movement')), array('action' => 'delete', $invMovement['InvMovement']['id']), null, __('Are you sure you want to delete # %s?', $invMovement['InvMovement']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movements')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movement Types')), array('controller' => 'inv_movement_types', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement Type')), array('controller' => 'inv_movement_types', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movement Details')), array('controller' => 'inv_movement_details', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement Detail')), array('controller' => 'inv_movement_details', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Inv Movement Details')); ?></h3>
	<?php if (!empty($invMovement['InvMovementDetail'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Inv Item Id'); ?></th>
				<th><?php echo __('Inv Warehouse Id'); ?></th>
				<th><?php echo __('Inv Movement Id'); ?></th>
				<th><?php echo __('Quantity'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Transaction'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($invMovement['InvMovementDetail'] as $invMovementDetail): ?>
			<tr>
				<td><?php echo $invMovementDetail['id'];?></td>
				<td><?php echo $invMovementDetail['inv_item_id'];?></td>
				<td><?php echo $invMovementDetail['inv_warehouse_id'];?></td>
				<td><?php echo $invMovementDetail['inv_movement_id'];?></td>
				<td><?php echo $invMovementDetail['quantity'];?></td>
				<td><?php echo $invMovementDetail['lc_state'];?></td>
				<td><?php echo $invMovementDetail['lc_transaction'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'inv_movement_details', 'action' => 'view', $invMovementDetail['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'inv_movement_details', 'action' => 'edit', $invMovementDetail['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'inv_movement_details', 'action' => 'delete', $invMovementDetail['id']), null, __('Are you sure you want to delete # %s?', $invMovementDetail['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement Detail')), array('controller' => 'inv_movement_details', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
