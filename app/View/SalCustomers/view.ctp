<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Sal Customer');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($salCustomer['SalCustomer']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($salCustomer['SalCustomer']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Address'); ?></dt>
			<dd>
				<?php echo h($salCustomer['SalCustomer']['address']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Phone'); ?></dt>
			<dd>
				<?php echo h($salCustomer['SalCustomer']['phone']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Email'); ?></dt>
			<dd>
				<?php echo h($salCustomer['SalCustomer']['email']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($salCustomer['SalCustomer']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($salCustomer['SalCustomer']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($salCustomer['SalCustomer']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($salCustomer['SalCustomer']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($salCustomer['SalCustomer']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($salCustomer['SalCustomer']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Sal Customer')), array('action' => 'edit', $salCustomer['SalCustomer']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Sal Customer')), array('action' => 'delete', $salCustomer['SalCustomer']['id']), null, __('Are you sure you want to delete # %s?', $salCustomer['SalCustomer']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Sal Customers')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Sal Customer')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Sal Buyers')), array('controller' => 'sal_buyers', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Sal Buyer')), array('controller' => 'sal_buyers', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Sal Buyers')); ?></h3>
	<?php if (!empty($salCustomer['SalBuyer'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Sal Customer Id'); ?></th>
				<th><?php echo __('Nit'); ?></th>
				<th><?php echo __('Name'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Transaction'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Date Created'); ?></th>
				<th><?php echo __('Date Modified'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($salCustomer['SalBuyer'] as $salBuyer): ?>
			<tr>
				<td><?php echo $salBuyer['id'];?></td>
				<td><?php echo $salBuyer['sal_customer_id'];?></td>
				<td><?php echo $salBuyer['nit'];?></td>
				<td><?php echo $salBuyer['name'];?></td>
				<td><?php echo $salBuyer['lc_state'];?></td>
				<td><?php echo $salBuyer['lc_transaction'];?></td>
				<td><?php echo $salBuyer['creator'];?></td>
				<td><?php echo $salBuyer['date_created'];?></td>
				<td><?php echo $salBuyer['date_modified'];?></td>
				<td><?php echo $salBuyer['modifier'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'sal_buyers', 'action' => 'view', $salBuyer['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'sal_buyers', 'action' => 'edit', $salBuyer['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'sal_buyers', 'action' => 'delete', $salBuyer['id']), null, __('Are you sure you want to delete # %s?', $salBuyer['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Sal Buyer')), array('controller' => 'sal_buyers', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
