<?php if($chk != ''):
	echo $chk;
else:
?>

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
				echo $this->Form->input('chkMenus', array(
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
<?php endif;?>