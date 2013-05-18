<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Parameter');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admParameter['AdmParameter']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Name'); ?></dt>
			<dd>
				<?php echo h($admParameter['AdmParameter']['name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Description'); ?></dt>
			<dd>
				<?php echo h($admParameter['AdmParameter']['description']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admParameter['AdmParameter']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($admParameter['AdmParameter']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admParameter['AdmParameter']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($admParameter['AdmParameter']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admParameter['AdmParameter']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($admParameter['AdmParameter']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Parameter')), array('action' => 'edit', $admParameter['AdmParameter']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Parameter')), array('action' => 'delete', $admParameter['AdmParameter']['id']), null, __('Are you sure you want to delete # %s?', $admParameter['AdmParameter']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Parameters')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Parameter')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Parameter Details')), array('controller' => 'adm_parameter_details', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Parameter Detail')), array('controller' => 'adm_parameter_details', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('Related %s', __('Adm Parameter Details')); ?></h3>
	<?php if (!empty($admParameter['AdmParameterDetail'])):?>
		<table class="table">
			<tr>
				<th><?php echo __('Id'); ?></th>
				<th><?php echo __('Adm Parameter Id'); ?></th>
				<th><?php echo __('Par Int1'); ?></th>
				<th><?php echo __('Par Int2'); ?></th>
				<th><?php echo __('Par Char1'); ?></th>
				<th><?php echo __('Par Char2'); ?></th>
				<th><?php echo __('Par Num1'); ?></th>
				<th><?php echo __('Par Num2'); ?></th>
				<th><?php echo __('Par Bool1'); ?></th>
				<th><?php echo __('Par Bool2'); ?></th>
				<th><?php echo __('Lc State'); ?></th>
				<th><?php echo __('Lc Transaction'); ?></th>
				<th><?php echo __('Creator'); ?></th>
				<th><?php echo __('Date Created'); ?></th>
				<th><?php echo __('Modifier'); ?></th>
				<th><?php echo __('Date Modified'); ?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admParameter['AdmParameterDetail'] as $admParameterDetail): ?>
			<tr>
				<td><?php echo $admParameterDetail['id'];?></td>
				<td><?php echo $admParameterDetail['adm_parameter_id'];?></td>
				<td><?php echo $admParameterDetail['par_int1'];?></td>
				<td><?php echo $admParameterDetail['par_int2'];?></td>
				<td><?php echo $admParameterDetail['par_char1'];?></td>
				<td><?php echo $admParameterDetail['par_char2'];?></td>
				<td><?php echo $admParameterDetail['par_num1'];?></td>
				<td><?php echo $admParameterDetail['par_num2'];?></td>
				<td><?php echo $admParameterDetail['par_bool1'];?></td>
				<td><?php echo $admParameterDetail['par_bool2'];?></td>
				<td><?php echo $admParameterDetail['lc_state'];?></td>
				<td><?php echo $admParameterDetail['lc_transaction'];?></td>
				<td><?php echo $admParameterDetail['creator'];?></td>
				<td><?php echo $admParameterDetail['date_created'];?></td>
				<td><?php echo $admParameterDetail['modifier'];?></td>
				<td><?php echo $admParameterDetail['date_modified'];?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'adm_parameter_details', 'action' => 'view', $admParameterDetail['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'adm_parameter_details', 'action' => 'edit', $admParameterDetail['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'adm_parameter_details', 'action' => 'delete', $admParameterDetail['id']), null, __('Are you sure you want to delete # %s?', $admParameterDetail['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
	<div class="span3">
		<ul class="nav nav-list">
			<li><?php echo $this->Html->link(__('New %s', __('Adm Parameter Detail')), array('controller' => 'adm_parameter_details', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
