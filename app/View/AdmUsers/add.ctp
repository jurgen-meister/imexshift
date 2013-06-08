<?php echo $this->Html->script('modules/AdmUsers', FALSE);?>
<?php //echo $this->Html->css('select2');?>
<?php //echo $this->Html->css('uniform');?>


<?php //echo $this->Html->script('jquery.uniform', FALSE);//Select and deselect all checkbox?>
<?php //echo $this->Html->script('select2.min', FALSE);?>
<?php echo $this->Html->script('jquery.validate', FALSE);?>



			
<!-- ************************************************************************************************************************ -->
<div class="span12"><!-- START CONTAINER FLUID/ROW FLUID/SPAN12 - FORMATO DE #UNICORN -->
<!-- ************************************************************************************************************************ -->
<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 1/2 *************************************** -->
<div class="widget-box">
	<div class="widget-title">
		<span class="icon">
			<i class="icon-edit"></i>								
		</span>
		<h5>Registro de Usuario</h5>
	</div>
	<div class="widget-content nopadding">
		<?php
		echo $this->BootstrapForm->create('AdmUser', array('class' => 'form-horizontal'));
				echo $this->BootstrapForm->input('di_number', array(
					'label' => '* Documento identidad:',
					'id'=>'txtDiNumber',
					'name'=>'txtDiNumber',
					//'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;'
				));
				echo $this->BootstrapForm->input('di_place', array(
					'label' => '* Expedido:',
					'id'=>'txtDiPlace',
					'data-provide'=>'typeahead',
					'data-items'=>4,
					'data-source'=>'["La Paz", "Cochabamba", "Santa Cruz", "Oruro", "Tarija", "Pando", "Beni", "Potosi", "Chuquisaca"]',
					'name'=>'txtDiPlace',
					'placeholder'=>'Ej: La Paz, Cochabamba, etc'
				//	'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;'
				));
				echo $this->BootstrapForm->input('first_name', array(
					'label' => '* Nombres:',
					'id'=>'txtFirstName',
					'name'=>'txtFirstName',
					//'class'=>'uneditable-input',
						//'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;'
				));
				echo $this->BootstrapForm->input('last_name1', array(
					'label' => '* Apellido paterno:',
					'id'=>'txtLastName1',
					'name'=>'txtLastName1',
						//'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;'
				));
				echo $this->BootstrapForm->input('last_name2', array(
					'label' => '* Apellido materno:',
					'id'=>'txtLastName2',
					'name'=>'txtLastName2',
						//'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;'
				));
				/*
				echo $this->BootstrapForm->input('login', array(
					'required' => 'required',
					'after'=>'<button type="button" class="btn btn-primary" id="generarUsuario">Generar</button>',
					'id'=>'txtLogin',
					'name'=>'txtLogin',
					'label' => '* Usuario:',
					'placeholder'=>'Puede crear su propio usuario o generarlo con el sistema',
					//'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;'
				));
				*/
				echo $this->BootstrapForm->input('active', array(
					'label'=>'* Activo:',
					'type'=>'select',
					'options'=>array('1'=>'SI','0'=>'NO'),
					'id'=>'cbxActive',
					'name'=>'cbxActive',
					//'placeholder'=>'Fecha en que el usuario dejará de estar activo',
					//'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;'
				));
				
				echo $this->BootstrapForm->input('active_date', array(
					'label'=>'* Fecha actividad:',
					'type'=>'text',
					'id'=>'txtActiveDate',
					'name'=>'txtActiveDate',
					//'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;'
				));
				
				echo $this->BootstrapForm->input('email', array(
					//'required' => 'required',
					'id'=>'txtEmail',
					'name'=>'txtEmail',
					'label' => '* Correo electrónico:',
					'type'=>'text',
						//'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;'
				));
				
				echo $this->BootstrapForm->input('job', array(
					//'required' => 'required',
					'label' => '* Cargo:',
					'id'=>'txtJob',
					'name'=>'txtJob',
					//'type'=>'select',
					'placeholder'=>'Ej: Gerente, Vendedor, etc',
						//'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;'
				));
				
				echo $this->BootstrapForm->input('birthdate', array(
					'id'=>'txtBirthdate',
					'name'=>'txtBirthdate',
					'label' => '* Fecha nacimiento:',
						//'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;'
				));
				
				echo $this->BootstrapForm->input('birthplace', array(
					'id'=>'txtBirthplace',
					'name'=>'txtBirthplace',
					'label' => '* Pais Origen:',
					//'value'=>'Bolivia'
					//'placeholder'=>'Ciudad, Pais',
				//	'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;'
				));
				
				echo $this->BootstrapForm->input('address', array(
					'id'=>'txtAddress',
					'name'=>'txtAddress',
					'label' => 'Dirección:',
					//'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;'
				));
				
				echo $this->BootstrapForm->input('phone', array(
					'id'=>'txtPhone',
					'name'=>'txtPhone',
					'label' => 'Telefono:',
					//'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;'
				));
	?>
		<div class="form-actions" style="text-align: center">
		<?php echo $this->BootstrapForm->submit('Guardar Cambios',array('class'=>'btn btn-primary','div'=>false, 'id'=>'btnSaveAll'));?>
		</div>
		<?php		echo $this->BootstrapForm->end();
				?>
<!-- //******************************** END - #UNICORN  WRAP FORM BOX PART 1/2 *************************************** -->	
	
	

<!-- //******************************** START - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
	</div> <!-- Belongs to: <div class="widget-content nopadding"> -->
</div> <!-- Belongs to: <div class="widget-box"> -->
<!-- //******************************** END - #UNICORN  WRAP FORM BOX PART 2/2 *************************************** -->
