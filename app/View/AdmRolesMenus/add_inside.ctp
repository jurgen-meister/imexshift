<?php  
//use this old version for compatibility "add action" older version array set even empty, new version not set if empty. Share action save
//echo $this->Html->script('checkboxtree/jquery-1.4.4', FALSE);
?>
<?php echo $this->Html->script('modules/AdmRolesMenus', FALSE); ?>
<!-- ************************************************************************************************************************ -->
<div class="span12"><!-- START CONTAINER FLUID/ROW FLUID/SPAN12 - FORMATO DE #UNICORN -->
<!-- ************************************************************************************************************************ -->
<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 1/2 *************************************** -->
<div class="widget-box">
	<div class="widget-title">
		<span class="icon">
			<i class="icon-edit"></i>								
		</span>
		<h5>Asignar Roles Menus/Permisos Internos</h5>
	</div>
	<div class="widget-content nopadding">
		<?php echo $this->BootstrapForm->create('AdmRolesMenu', array('class' => 'form-horizontal'));?>
			<fieldset>
		<?php
		echo $this->BootstrapForm->input('adm_role_id', array(
					'id'=>'roles_inside'
					//,'required' => 'required',
					//'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;'
				));
		echo $this->BootstrapForm->input('adm_module_id', array(
			'id'=>'modules_inside'
			//,'required' => 'required',
			//'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;'
		));
		?>
					</fieldset>
		<?php echo $this->BootstrapForm->end();?>
		<div id="message" style="text-align: center;"></div>
		<div id="processing" style="text-align: center;"></div>

		<div id="boxMenusInside">
		
			<?php $cont =1;?>
			<table class="table table-bordered">
					<tr>
						<th>#</th>
						<th>Controlador</th>
						<th>Menus/Permisos Internos</th>
					</tr>
			<?php foreach ($controllers as $key => $controller): ?>
				<tr>
					<td><?php echo $cont;?></td>
					<td><?php echo $controller; ?>&nbsp;</td>
					<td>
						<?php 				
						echo $this->BootstrapForm->input('chkMenus', array(
							'label' => '',
							'type' => 'select',
							'multiple' => 'checkbox inline',
							'options' => $menusCheckBoxes[$key],//array(1,2,3),
							'selected' => $checks//array(5,6)
						)); 
						?>
					</td>
				</tr>
			<?php $cont++;?>
			<?php endforeach; ?>
			
		</div>
		</table>
		</div>
	
		<div class="form-actions" style="text-align: center">
		<button type="submit" class="btn btn-primary" id="saveButton">Guardar Cambios</button>
		</div>
			<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
	</div> <!-- Belongs to: <div class="widget-content nopadding"> -->
</div> <!-- Belongs to: <div class="widget-box"> -->
<!-- //******************************** END - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
</div>

