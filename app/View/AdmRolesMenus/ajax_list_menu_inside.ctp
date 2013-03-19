<?php if($chk != ''):
	echo $chk;
else:
?>

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
<?php endif;?>