<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Parameter Detail');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admParameterDetail['AdmParameterDetail']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm Parameter'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admParameterDetail['AdmParameter']['name'], array('controller' => 'adm_parameters', 'action' => 'view', $admParameterDetail['AdmParameter']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Par Int1'); ?></dt>
			<dd>
				<?php echo h($admParameterDetail['AdmParameterDetail']['par_int1']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Par Int2'); ?></dt>
			<dd>
				<?php echo h($admParameterDetail['AdmParameterDetail']['par_int2']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Par Char1'); ?></dt>
			<dd>
				<?php echo h($admParameterDetail['AdmParameterDetail']['par_char1']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Par Char2'); ?></dt>
			<dd>
				<?php echo h($admParameterDetail['AdmParameterDetail']['par_char2']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Par Num1'); ?></dt>
			<dd>
				<?php echo h($admParameterDetail['AdmParameterDetail']['par_num1']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Par Num2'); ?></dt>
			<dd>
				<?php echo h($admParameterDetail['AdmParameterDetail']['par_num2']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Par Bool1'); ?></dt>
			<dd>
				<?php echo h($admParameterDetail['AdmParameterDetail']['par_bool1']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Par Bool2'); ?></dt>
			<dd>
				<?php echo h($admParameterDetail['AdmParameterDetail']['par_bool2']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admParameterDetail['AdmParameterDetail']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Transaction'); ?></dt>
			<dd>
				<?php echo h($admParameterDetail['AdmParameterDetail']['lc_transaction']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admParameterDetail['AdmParameterDetail']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Created'); ?></dt>
			<dd>
				<?php echo h($admParameterDetail['AdmParameterDetail']['date_created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admParameterDetail['AdmParameterDetail']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Date Modified'); ?></dt>
			<dd>
				<?php echo h($admParameterDetail['AdmParameterDetail']['date_modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Parameter Detail')), array('action' => 'edit', $admParameterDetail['AdmParameterDetail']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Parameter Detail')), array('action' => 'delete', $admParameterDetail['AdmParameterDetail']['id']), null, __('Are you sure you want to delete # %s?', $admParameterDetail['AdmParameterDetail']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Parameter Details')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Parameter Detail')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Parameters')), array('controller' => 'adm_parameters', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Parameter')), array('controller' => 'adm_parameters', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

