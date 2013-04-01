<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('InvSupplier', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Add %s', __('Inv Supplier')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('code', array(
					'label' => 'Código:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('name', array(
					'label' => 'Nombre:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('location', array(
					'label' => 'Locacion:',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('adress', array(
					'label' => 'Dirección:',
					
					)
				);
				echo $this->BootstrapForm->input('phone', array(
					'label' => 'Teléfono:',					
					)
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
			<li><?php echo $this->Html->link(__('List %s', __('Inv Suppliers')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Supplier Contacts')), array('controller' => 'inv_supplier_contacts', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Supplier Contact')), array('controller' => 'inv_supplier_contacts', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Inv Items Suppliers')), array('controller' => 'inv_items_suppliers', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Inv Items Supplier')), array('controller' => 'inv_items_suppliers', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>-->
</div>