<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('InvPrice', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Agregar %s', __('Precio')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('inv_item_id', array(
					'label' => 'Item:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('inv_price_type_id', array(
					'label' => 'Tipo de Precio:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('price', array(
					'label' => 'Monto:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('description', array(
					'label' => 'Descripccion:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);				
				?>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
<!--	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Prices')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Items')), array('controller' => 'inv_items', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Item')), array('controller' => 'inv_items', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>-->
</div>