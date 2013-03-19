<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Action');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admAction['AdmAction']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Controller'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admAction['AdmController']['name'], array('controller' => 'adm_controllers', 'action' => 'view', $admAction['AdmController']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($admAction['AdmAction']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Description'); ?></dt>
			<dd>
				<?php echo h($admAction['AdmAction']['description']); ?>
				&nbsp;
			</dd>
			
	</div>
</div>
