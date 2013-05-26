	<div class="span9">
		<?php echo $this->BootstrapForm->create('SalSale', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Edit %s', __('Sal Sale')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('sal_employee_id', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('sal_tax_number_id', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('code', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('doc_code', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('date', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('description');				
				echo $this->BootstrapForm->hidden('id');
				?>
				<div class="row-fluid">
					<div class="span2"></div>
					<div class="span6">
					<div class="btn-toolbar">
					<?php echo $this->BootstrapForm->submit('Guardar', array('id'=>'saveButton', 'class' => 'btn btn-primary', 'div' => false));
						   echo $this->Html->link('Cancelar', array('action' => 'index'), array('class'=>'btn') );
					?>
					</div>				
					</div>
					<div class="span4"></div>
				</div>	
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>