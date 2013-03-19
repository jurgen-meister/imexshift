<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('Lista menus');?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_module_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_action_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('Menu interno');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('order_menu');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('parent_node');?></th>
				
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($admMenus as $admMenu): ?>
			<tr>
				<td><?php echo h($admMenu['AdmMenu']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($admMenu['AdmModule']['name'], array('controller' => 'adm_modules', 'action' => 'view', $admMenu['AdmModule']['id'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link($admMenu['AdmAction']['name'], array('controller' => 'adm_actions', 'action' => 'view', $admMenu['AdmAction']['id'])); ?>
				</td>
				<td>
					<?php if($admMenu['AdmMenu']['inside'] == null):?>
					<?php echo "NO"; ?>
					<?php else: ?>
					<?php echo "SI"; ?>
					<?php endif;?>
				</td>
				<td><?php echo h($admMenu['AdmMenu']['name']); ?>&nbsp;</td>
				<td><?php echo h($admMenu['AdmMenu']['order_menu']); ?>&nbsp;</td>
				<td><?php echo h($admMenu['AdmMenu']['parent_node']); ?>&nbsp;</td>
				
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $admMenu['AdmMenu']['id'])); ?>
					<?php if($admMenu['AdmMenu']['inside'] == null):?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $admMenu['AdmMenu']['id'])); ?>
					<?php else:?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit_inside', $admMenu['AdmMenu']['id'])); ?>
					<?php endif;?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $admMenu['AdmMenu']['id']), null, __('Are you sure you want to delete # %s?', $admMenu['AdmMenu']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
</div>