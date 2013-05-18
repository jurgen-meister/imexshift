<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Exchange Rate');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admExchangeRate['AdmExchangeRate']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Currency'); ?></dt>
			<dd>
				<?php echo h($admExchangeRate['AdmExchangeRate']['currency']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Value'); ?></dt>
			<dd>
				<?php echo h($admExchangeRate['AdmExchangeRate']['value']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admExchangeRate['AdmExchangeRate']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($admExchangeRate['AdmExchangeRate']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admExchangeRate['AdmExchangeRate']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($admExchangeRate['AdmExchangeRate']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admExchangeRate['AdmExchangeRate']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($admExchangeRate['AdmExchangeRate']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Exchange Rate')), array('action' => 'edit', $admExchangeRate['AdmExchangeRate']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Exchange Rate')), array('action' => 'delete', $admExchangeRate['AdmExchangeRate']['id']), null, __('Are you sure you want to delete # %s?', $admExchangeRate['AdmExchangeRate']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Exchange Rates')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Exchange Rate')), array('action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

