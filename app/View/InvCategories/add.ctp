<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('InvCategory', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Add %s', __('Inv Category')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('name', array(
					'label' => 'Nombre',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				//descripcion -> description
				echo $this->BootstrapForm->input('descripcion', array(
					'label' => 'Descripcion',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
//				echo $this->BootstrapForm->input('lc_state', array(
//					'required' => 'required',
//					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
//				);
//				echo $this->BootstrapForm->input('lc_transaction', array(
//					'required' => 'required',
//					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
//				);
//				echo $this->BootstrapForm->input('creator', array(
//					'required' => 'required',
//					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
//				);
//				echo $this->BootstrapForm->input('date_created', array(
//					'required' => 'required',
//					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
//				);
//				echo $this->BootstrapForm->input('modifier', array(
//					'required' => 'required',
//					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
//				);
//				echo $this->BootstrapForm->input('date_modified', array(
//					'required' => 'required',
//					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
//				);
				?>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Categories')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Items')), array('controller' => 'inv_items', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Item')), array('controller' => 'inv_items', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>
</div>