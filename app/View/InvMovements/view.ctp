<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Inv Movement');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($invMovement['InvMovement']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Inv Item'); ?></dt>
			<dd>
				<?php echo $this->Html->link($invMovement['InvItem']['name'], array('controller' => 'inv_items', 'action' => 'view', $invMovement['InvItem']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Inv Warehouse'); ?></dt>
			<dd>
				<?php echo $this->Html->link($invMovement['InvWarehouse']['name'], array('controller' => 'inv_warehouses', 'action' => 'view', $invMovement['InvWarehouse']['id'])); ?>
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
			<dt><?php echo __('Quantity'); ?></dt>
			<dd>
				<?php echo h($invMovement['InvMovement']['quantity']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($invMovement['InvMovement']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($invMovement['InvMovement']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($invMovement['InvMovement']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($invMovement['InvMovement']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($invMovement['InvMovement']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($invMovement['InvMovement']['date_modified']); ?>
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
			<li><?php echo $this->Html->link(__('List %s', __('Inv Items')), array('controller' => 'inv_items', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Item')), array('controller' => 'inv_items', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Warehouses')), array('controller' => 'inv_warehouses', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Warehouse')), array('controller' => 'inv_warehouses', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Movement Types')), array('controller' => 'inv_movement_types', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Movement Type')), array('controller' => 'inv_movement_types', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

