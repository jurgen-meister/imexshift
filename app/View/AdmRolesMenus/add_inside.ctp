<?php  
//use this old version for compatibility "add action" older version array set even empty, new version not set if empty. Share action save
echo $this->Html->script('checkboxtree/jquery-1.4.4', FALSE);
?>
<?php echo $this->Html->script('modules/AdmRolesMenus', FALSE); ?>
<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('Asignar roles menus internos');?></h2>
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
		<button type="submit" class="btn" id="saveButton">Guardar</button>
		<div id="message" style="text-align: center;"></div>
		<div id="processing" style="text-align: center;"></div>

		<div id="boxMenusInside">
		
			<?php $cont =1;?>
			<table class="table table-bordered">
					<tr>
						<th>#</th>
						<th>Controlador</th>
						<th>Menus internos</th>
					</tr>
			<?php foreach ($controllers as $key => $controller): ?>
				<tr>
					<td width="5%"><?php echo $cont;?></td>
					<td width="25%"><?php echo $controller; ?>&nbsp;</td>
					<td width="70%">
						<?php 				
						echo $this->Form->input('nose]', array(
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
</div>



