<?php echo $this->Html->script('checkboxtree/jquery-1.4.4', FALSE); ?>
<?php echo $this->Html->script('checkboxtree/jquery-ui-1.8.12.custom.min', FALSE); ?>
<?php echo $this->Html->script('checkboxtree/jquery.checkboxtree', FALSE); ?>
<?php echo $this->Html->css('jquery.checkboxtree');?>
<?php echo $this->Html->script('modules/AdmRolesMenus', FALSE); ?>

<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('AdmRolesMenu');?>
			<fieldset>
				<legend><?php echo __('Add %s', __('Adm Roles Menu')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('adm_role_id', array(
					'id'=>'roles'
				));
				echo $this->BootstrapForm->input('adm_module_id', array(
					'id'=>'modules'
				));
		?>		
		<?php //echo $this->BootstrapForm->submit('Guardar', array('id'=>'saveButton')); ?>		
		<button type="submit" class="btn" id="saveButton">Guardar</button>
		<div id="message" style="text-align: center;"></div>
		<div id="processing" style="text-align: center;"></div>
		<?php	echo '<br>';
				echo '<div id="boxChkTree">';
				echo $chkTree;
				echo '</div>';
				?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
		<!--
		<ul id="tree1">
			<li><input type="checkbox" name="chkN1[]" id="chkN1" value="1"  checked = "checked" > 1
				<ul>
					<li><input type="checkbox" name="chkN1[]" id="chkN1" value="1"  checked = "checked" > 1.1</li>
					<li><input type="checkbox" name="chkN1[]" id="chkN1" value="1"  checked = "checked" > 1.2
						<ul>
							<li><input type="checkbox" name="chkN1[]" id="chkN1" value="1"  checked = "checked" > 1.2.1</li>
						</ul>
					</li>
				</ul>
			</li>
			<li><input type="checkbox" name="chkN1[]" id="chkN1" value="1"  checked = "checked" > 2</li>
			<li><input type="checkbox" name="chkN1[]" id="chkN1" value="1"  checked = "checked" > 3</li>
		</ul>
		-->
	</div>
</div>

