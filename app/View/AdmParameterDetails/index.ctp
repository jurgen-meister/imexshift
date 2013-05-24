<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('Lista de %s', __('Parametros Detalle'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_parameter_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('par_int1');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('par_int2');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('par_char1');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('par_char2');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('par_num1');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('par_num2');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('par_bool1');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('par_bool2');?></th>				
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admParameterDetails as $admParameterDetail): ?>
			<tr>
				<td><?php echo h($admParameterDetail['AdmParameterDetail']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($admParameterDetail['AdmParameter']['name'], array('controller' => 'adm_parameters', 'action' => 'view', $admParameterDetail['AdmParameter']['id'])); ?>
				</td>
				<td><?php echo h($admParameterDetail['AdmParameterDetail']['par_int1']); ?>&nbsp;</td>
				<td><?php echo h($admParameterDetail['AdmParameterDetail']['par_int2']); ?>&nbsp;</td>
				<td><?php echo h($admParameterDetail['AdmParameterDetail']['par_char1']); ?>&nbsp;</td>
				<td><?php echo h($admParameterDetail['AdmParameterDetail']['par_char2']); ?>&nbsp;</td>
				<td><?php echo h($admParameterDetail['AdmParameterDetail']['par_num1']); ?>&nbsp;</td>
				<td><?php echo h($admParameterDetail['AdmParameterDetail']['par_num2']); ?>&nbsp;</td>
				<td><?php echo h($admParameterDetail['AdmParameterDetail']['par_bool1']); ?>&nbsp;</td>
				<td><?php echo h($admParameterDetail['AdmParameterDetail']['par_bool2']); ?>&nbsp;</td>				
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admParameterDetail['AdmParameterDetail']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admParameterDetail['AdmParameterDetail']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admParameterDetail['AdmParameterDetail']['id']), null, __('Are you sure you want to delete # %s?', $admParameterDetail['AdmParameterDetail']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
<!--	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Parameter Detail')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Parameters')), array('controller' => 'adm_parameters', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Parameter')), array('controller' => 'adm_parameters', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>-->
</div>