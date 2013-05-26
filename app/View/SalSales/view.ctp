<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Sal Sale');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($salSale['SalSale']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Sal Employee'); ?></dt>
			<dd>
				<?php echo $this->Html->link($salSale['SalEmployee']['id'], array('controller' => 'sal_employees', 'action' => 'view', $salSale['SalEmployee']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Sal Tax Number'); ?></dt>
			<dd>
				<?php echo $this->Html->link($salSale['SalTaxNumber']['name'], array('controller' => 'sal_tax_numbers', 'action' => 'view', $salSale['SalTaxNumber']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Code'); ?></dt>
			<dd>
				<?php echo h($salSale['SalSale']['code']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Doc Code'); ?></dt>
			<dd>
				<?php echo h($salSale['SalSale']['doc_code']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date'); ?></dt>
			<dd>
				<?php echo h($salSale['SalSale']['date']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Description'); ?></dt>
			<dd>
				<?php echo h($salSale['SalSale']['description']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($salSale['SalSale']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($salSale['SalSale']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($salSale['SalSale']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($salSale['SalSale']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($salSale['SalSale']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($salSale['SalSale']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Sal Sale')), array('action' => 'edit', $salSale['SalSale']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Sal Sale')), array('action' => 'delete', $salSale['SalSale']['id']), null, __('Are you sure you want to delete # %s?', $salSale['SalSale']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Sal Sales')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Sal Sale')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Sal Employees')), array('controller' => 'sal_employees', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Sal Employee')), array('controller' => 'sal_employees', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Sal Tax Numbers')), array('controller' => 'sal_tax_numbers', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Sal Tax Number')), array('controller' => 'sal_tax_numbers', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Sal Payments')), array('controller' => 'sal_payments', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Sal Payment')), array('controller' => 'sal_payments', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Sal Details')), array('controller' => 'sal_details', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Sal Detail')), array('controller' => 'sal_details', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Sal Payments')); ?></h3>
	<?php if (!empty($salSale['SalPayment'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Sal Payment Type Id'); ?></th>
				<th><?php echo __('Sal Sale Id'); ?></th>
				<th><?php echo __('Description'); ?></th>
				<th><?php echo __('Amount'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Transaction'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Date Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Date Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($salSale['SalPayment'] as $salPayment): ?>
			<tr>
				<td><?php echo $salPayment['id'];?></td>
				<td><?php echo $salPayment['sal_payment_type_id'];?></td>
				<td><?php echo $salPayment['sal_sale_id'];?></td>
				<td><?php echo $salPayment['description'];?></td>
				<td><?php echo $salPayment['amount'];?></td>
				<td><?php echo $salPayment['lc_state'];?></td>
				<td><?php echo $salPayment['lc_transaction'];?></td>
				<td><?php echo $salPayment['creator'];?></td>
				<td><?php echo $salPayment['date_created'];?></td>
				<td><?php echo $salPayment['modifier'];?></td>
				<td><?php echo $salPayment['date_modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'sal_payments', 'action' => 'view', $salPayment['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'sal_payments', 'action' => 'edit', $salPayment['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'sal_payments', 'action' => 'delete', $salPayment['id']), null, __('Are you sure you want to delete # %s?', $salPayment['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Sal Payment')), array('controller' => 'sal_payments', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Sal Details')); ?></h3>
	<?php if (!empty($salSale['SalDetail'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Sal Sale Id'); ?></th>
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
		<?php foreach ($salSale['SalDetail'] as $salDetail): ?>
			<tr>
				<td><?php echo $salDetail['id'];?></td>
				<td><?php echo $salDetail['sal_sale_id'];?></td>
				<td><?php echo $salDetail['inv_item_id'];?></td>
				<td><?php echo $salDetail['quantity'];?></td>
				<td><?php echo $salDetail['lc_state'];?></td>
				<td><?php echo $salDetail['lc_transaction'];?></td>
				<td><?php echo $salDetail['creator'];?></td>
				<td><?php echo $salDetail['date_created'];?></td>
				<td><?php echo $salDetail['modifier'];?></td>
				<td><?php echo $salDetail['date_modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'sal_details', 'action' => 'view', $salDetail['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'sal_details', 'action' => 'edit', $salDetail['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'sal_details', 'action' => 'delete', $salDetail['id']), null, __('Are you sure you want to delete # %s?', $salDetail['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Sal Detail')), array('controller' => 'sal_details', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
