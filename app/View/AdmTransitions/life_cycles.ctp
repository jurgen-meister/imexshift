<?php echo $this->Html->script('AdmTransitions', FALSE); ?>
<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmTransition', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Add %s', __('Adm Transition')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('adm_module_id', array('label'=>'Modulos', 'id'=>'modules'));
				
				echo '<div id="boxControllers">';
				echo $this->BootstrapForm->input('adm_controller_id', array('label'=>'Controladores', 'id'=>'controllers'));
				echo '</div">';
				?>
<ul class="nav nav-tabs" id="myTab">
  <li class="active"><a href="#states" data-toggle="tab">Estados</a></li>
  <li><a href="#transactions" data-toggle="tab">Transacciones</a></li>
  <li><a href="#transitions" data-toggle="tab">Transiciones</a></li>
</ul>
<div class="tab-content">
  <div class="tab-pane active" id="states">.LUSTRANDO BOTAS..</div>
  <div class="tab-pane" id="transactions">meteorito</div>
  <div class="tab-pane" id="transitions">saisisez</div>
</div>				
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
		<div id="message" style="text-align: center;"></div>
		<div id="processing" style="text-align: center;"></div>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Transitions')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm States')), array('controller' => 'adm_states', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm State')), array('controller' => 'adm_states', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Actions')), array('controller' => 'adm_actions', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Action')), array('controller' => 'adm_actions', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>
</div>