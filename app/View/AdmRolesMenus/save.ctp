<?php //echo $this->Html->script('checkboxtree/jquery-1.4.4', FALSE);  ?>
<?php //echo $this->Html->script('checkboxtree/jquery-ui-1.8.12.custom.min', FALSE);  ?>
<?php echo $this->Html->script('checkboxtree/jquery.checkboxtree', FALSE); ?>
<?php echo $this->Html->css('jquery.checkboxtree'); ?>
<?php echo $this->Html->script('modules/AdmRolesMenus', FALSE); ?>

<!-- ************************************************************************************************************************ -->
<div class="span12"><!-- START CONTAINER FLUID/ROW FLUID/SPAN12 - FORMATO DE #UNICORN -->
	<!-- ************************************************************************************************************************ -->
	<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 1/2 *************************************** -->
	<div class="widget-box">
		<div class="widget-content nopadding">
			<button type="submit" class="btn btn-primary" id="saveButton">Guardar Cambios</button>
		</div>
	</div>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="icon-edit"></i>								
			</span>
			<h5>Roles Menus</h5>
		</div>
		<div class="widget-content nopadding">
			<?php echo $this->BootstrapForm->create('AdmRolesMenu', array('class' => 'form-horizontal')); ?>
			<fieldset>
				<?php
				echo $this->BootstrapForm->input('adm_role_id', array(
					'label' => 'Rol:',
					'id' => 'roles',
					'class' => 'span4'
				));
				echo $this->BootstrapForm->input('adm_module_id', array(
					'id' => 'parentMenus',
					'label' => 'Menu Padre:',
					'options' => $parentsMenus,
					'class' => 'span4'
				));
				?>		
				<?php //echo $this->BootstrapForm->submit('Guardar', array('id'=>'saveButton')); ?>		
				<div id="message" style="text-align: center;"></div>
				<div id="processing" style="text-align: center;"></div>

				<div class="control-group">
					<label for="AdmRolesMenuAdmRoleId" class="control-label">Menus:</label>
					<div class="controls" id="boxChkTree">
						<?php
						//echo '<br>';
						//echo '<div id="boxChkTree">';
						echo $chkTree;
						//echo '</div>';
						?>
					</div>
				</div>
			</fieldset>
<?php echo $this->BootstrapForm->end(); ?>
			<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
		</div> <!-- Belongs to: <div class="widget-content nopadding"> -->
	</div> <!-- Belongs to: <div class="widget-box"> -->
	<!-- //******************************** END - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
</div>

