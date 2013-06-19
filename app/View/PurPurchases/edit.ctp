<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('PurPurchase', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Edit %s', __('Pur Purchase')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('inv_supplier_id', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('code', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('date', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('description', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('lc_state', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('lc_transaction', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('creator', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('date_created', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('modifier');
				echo $this->BootstrapForm->input('date_modified');
				echo $this->BootstrapForm->hidden('id');
				?>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('PurPurchase.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('PurPurchase.id'))); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Pur Purchases')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Suppliers')), array('controller' => 'inv_suppliers', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Supplier')), array('controller' => 'inv_suppliers', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Pur Prices')), array('controller' => 'pur_prices', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Pur Price')), array('controller' => 'pur_prices', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Pur Payments')), array('controller' => 'pur_payments', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Pur Payment')), array('controller' => 'pur_payments', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Pur Details')), array('controller' => 'pur_details', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Pur Detail')), array('controller' => 'pur_details', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>
</div>
