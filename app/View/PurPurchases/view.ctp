<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Pur Purchase');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($purPurchase['PurPurchase']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Inv Supplier'); ?></dt>
			<dd>
				<?php echo $this->Html->link($purPurchase['InvSupplier']['name'], array('controller' => 'inv_suppliers', 'action' => 'view', $purPurchase['InvSupplier']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Code'); ?></dt>
			<dd>
				<?php echo h($purPurchase['PurPurchase']['code']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date'); ?></dt>
			<dd>
				<?php echo h($purPurchase['PurPurchase']['date']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Description'); ?></dt>
			<dd>
				<?php echo h($purPurchase['PurPurchase']['description']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($purPurchase['PurPurchase']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($purPurchase['PurPurchase']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($purPurchase['PurPurchase']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($purPurchase['PurPurchase']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($purPurchase['PurPurchase']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($purPurchase['PurPurchase']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Pur Purchase')), array('action' => 'edit', $purPurchase['PurPurchase']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Pur Purchase')), array('action' => 'delete', $purPurchase['PurPurchase']['id']), null, __('Are you sure you want to delete # %s?', $purPurchase['PurPurchase']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Pur Purchases')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Pur Purchase')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Suppliers')), array('controller' => 'inv_suppliers', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Supplier')), array('controller' => 'inv_suppliers', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Pur Prices')), array('controller' => 'pur_prices', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Pur Price')), array('controller' => 'pur_prices', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Pur Payments')), array('controller' => 'pur_payments', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Pur Payment')), array('controller' => 'pur_payments', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Pur Details')), array('controller' => 'pur_details', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Pur Detail')), array('controller' => 'pur_details', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Pur Prices')); ?></h3>
	<?php if (!empty($purPurchase['PurPrice'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Inv Price Type Id'); ?></th>
				<th><?php echo __('Pur Purchase Id'); ?></th>
				<th><?php echo __('Amount'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Transaction'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Date Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Date Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($purPurchase['PurPrice'] as $purPrice): ?>
			<tr>
				<td><?php echo $purPrice['id'];?></td>
				<td><?php echo $purPrice['inv_price_type_id'];?></td>
				<td><?php echo $purPrice['pur_purchase_id'];?></td>
				<td><?php echo $purPrice['amount'];?></td>
				<td><?php echo $purPrice['lc_state'];?></td>
				<td><?php echo $purPrice['lc_transaction'];?></td>
				<td><?php echo $purPrice['creator'];?></td>
				<td><?php echo $purPrice['date_created'];?></td>
				<td><?php echo $purPrice['modifier'];?></td>
				<td><?php echo $purPrice['date_modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'pur_prices', 'action' => 'view', $purPrice['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'pur_prices', 'action' => 'edit', $purPrice['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'pur_prices', 'action' => 'delete', $purPrice['id']), null, __('Are you sure you want to delete # %s?', $purPrice['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Pur Price')), array('controller' => 'pur_prices', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Pur Payments')); ?></h3>
	<?php if (!empty($purPurchase['PurPayment'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Pur Purchase Id'); ?></th>
				<th><?php echo __('Pur Payment Type Id'); ?></th>
				<th><?php echo __('Due Date'); ?></th>
				<th><?php echo __('Amount'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Transaction'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Date Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Date Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($purPurchase['PurPayment'] as $purPayment): ?>
			<tr>
				<td><?php echo $purPayment['id'];?></td>
				<td><?php echo $purPayment['pur_purchase_id'];?></td>
				<td><?php echo $purPayment['pur_payment_type_id'];?></td>
				<td><?php echo $purPayment['due_date'];?></td>
				<td><?php echo $purPayment['amount'];?></td>
				<td><?php echo $purPayment['lc_state'];?></td>
				<td><?php echo $purPayment['lc_transaction'];?></td>
				<td><?php echo $purPayment['creator'];?></td>
				<td><?php echo $purPayment['date_created'];?></td>
				<td><?php echo $purPayment['modifier'];?></td>
				<td><?php echo $purPayment['date_modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'pur_payments', 'action' => 'view', $purPayment['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'pur_payments', 'action' => 'edit', $purPayment['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'pur_payments', 'action' => 'delete', $purPayment['id']), null, __('Are you sure you want to delete # %s?', $purPayment['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Pur Payment')), array('controller' => 'pur_payments', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Pur Details')); ?></h3>
	<?php if (!empty($purPurchase['PurDetail'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Pur Purchase Id'); ?></th>
				<th><?php echo __('Inv Item Id'); ?></th>
				<th><?php echo __('Quantity'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Transaction'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Date Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Date Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($purPurchase['PurDetail'] as $purDetail): ?>
			<tr>
				<td><?php echo $purDetail['id'];?></td>
				<td><?php echo $purDetail['pur_purchase_id'];?></td>
				<td><?php echo $purDetail['inv_item_id'];?></td>
				<td><?php echo $purDetail['quantity'];?></td>
				<td><?php echo $purDetail['lc_state'];?></td>
				<td><?php echo $purDetail['lc_transaction'];?></td>
				<td><?php echo $purDetail['creator'];?></td>
				<td><?php echo $purDetail['date_created'];?></td>
				<td><?php echo $purDetail['modifier'];?></td>
				<td><?php echo $purDetail['date_modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'pur_details', 'action' => 'view', $purDetail['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'pur_details', 'action' => 'edit', $purDetail['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'pur_details', 'action' => 'delete', $purDetail['id']), null, __('Are you sure you want to delete # %s?', $purDetail['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Pur Detail')), array('controller' => 'pur_details', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
