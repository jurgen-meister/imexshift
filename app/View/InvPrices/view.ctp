<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Inv Price');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($invPrice['InvPrice']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Inv Item'); ?></dt>
			<dd>
				<?php echo $this->Html->link($invPrice['InvItem']['code'], array('controller' => 'inv_items', 'action' => 'view', $invPrice['InvItem']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Inv Price Type Id'); ?></dt>
			<dd>
				<?php echo h($invPrice['InvPrice']['inv_price_type_id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Price'); ?></dt>
			<dd>
				<?php echo h($invPrice['InvPrice']['price']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Description'); ?></dt>
			<dd>
				<?php echo h($invPrice['InvPrice']['description']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($invPrice['InvPrice']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($invPrice['InvPrice']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($invPrice['InvPrice']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($invPrice['InvPrice']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($invPrice['InvPrice']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($invPrice['InvPrice']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Inv Price')), array('action' => 'edit', $invPrice['InvPrice']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Inv Price')), array('action' => 'delete', $invPrice['InvPrice']['id']), null, __('Are you sure you want to delete # %s?', $invPrice['InvPrice']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Prices')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Price')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Items')), array('controller' => 'inv_items', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Item')), array('controller' => 'inv_items', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

