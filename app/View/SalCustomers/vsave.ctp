<?php echo $this->Html->script('modules/SalCustomers', FALSE); ?>
<div class="span12">
	
	<div class="widget-box">
		<div class="widget-content nopadding">
			<?php
//			echo $this->Html->link('Cancelar', array('action' => 'index'), array('class' => 'btn'));
			$url=array("action"=>"index");
			$parameters = array();
			echo $this->Html->link('<i class=" icon-arrow-left"></i> Volver', array_merge($url,$parameters), array('class'=>'btn', 'escape'=>false)).' ';
			echo $this->BootstrapForm->submit('Guardar Cambios', array('id' => 'saveButton', 'class' => 'btn btn-primary', 'div' => false));
			?>
		</div>
	</div>
	
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="icon-edit"></i>								
			</span>
			<h5>Cliente</h5>			
		</div>
		<?php echo $this->BootstrapForm->create('SalCustomer', array('class' => 'form-horizontal')); ?>
		<?php
		echo $this->BootstrapForm->input('idCustomer', array(
			'type'=>'text',
			'id'=>'txtIdCustomer'
		));
		echo $this->BootstrapForm->input('name', array(
			'label' => "Nombre:"
				)
		);
		echo $this->BootstrapForm->input('address', array(
			'label' => "Dirección:"
			, 'placeholder' => 'Dirección, ciudad, (pais)'
		));
		echo $this->BootstrapForm->input('phone', array(
			'label' => "Telefono:"
		));
		echo $this->BootstrapForm->input('email', array(
			'label' => "Correo Electrónico:"
		));
		echo $this->BootstrapForm->end();
		?>
		
<?php echo $this->BootstrapForm->end();?>
		
		
		
		<div class="widget-box">
			<div class="widget-title">
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#tab1">Empleados</a></li>
					<li><a data-toggle="tab" href="#tab2">Nits</a></li>
				</ul>
			</div>
			<div class="widget-content tab-content">
				<div id="tab1" class="tab-pane active">
					<?php
					echo $this->BootstrapForm->create('SalEmployee', array('class' => 'form-inline'));
					echo $this->BootstrapForm->input('idEmployee', array('placeholder' => "id", 'class'=>'span1', 'id'=>'txtIdEmployee', 'type'=>'text'));
					echo $this->BootstrapForm->input('nameEmployee', array('placeholder' => "Nombre", 'class'=>'span3', 'id'=>'txtNameEmployee'));
					echo $this->BootstrapForm->input('phoneEmployee', array('placeholder' => "Telefono", 'class'=>'span2', 'id'=>'txtPhoneEmployee'));
					echo $this->BootstrapForm->input('emailEmployee', array('placeholder' => "Correo electrónico", 'class'=>'span2', 'id'=>'txtEmailEmployee'));
					echo $this->BootstrapForm->submit('<i class="icon-plus icon-white"></i> ', array('id' => 'btnAddEmployee', 'class' => 'btn btn-primary', 'div' => false, 'title' => 'Nuevo Empleado'));
					echo $this->BootstrapForm->submit('Guardar', array('id' => 'btnEditEmployee', 'class' => 'btn btn-primary', 'div' => false));
					echo $this->BootstrapForm->submit('Cancelar', array('id' => 'btnCancelEmployee', 'class' => 'btn btn-cancel', 'div' => false));
					echo $this->BootstrapForm->end();
					?>
					<br>
					<table class="table table-striped table-bordered table-hover" id="tblEmployees">
						<thead>
							<tr>
								<th>#</th>
								<th>Nombre</th>
								<th>Telefono</th>
								<th>Correo Electrónico</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="text-align: center;"><span class="spaNumber">1</span> <input type="text" value="<?php echo"111";?>" class="spaIdEmployee"></td>
								<td><span class="spaNameEmployee"><?php echo"Jaqueline Ayala";?></span></td>
								<td><span class="spaPhoneEmployee"><?php echo"";?></span></td>
								<td><span class="spaEmailEmployee"><?php echo"";?></span></td>
								<td>
									<?php
									echo $this->Html->link('<i class="icon-pencil icon-white"></i>', array('action' => 'vsave'), array('class' => 'btn btn-primary btnRowEditEmployee', 'escape' => false, 'title' => 'Editar'));
									echo ' '.$this->Html->link('<i class="icon-trash icon-white"></i>', array('action' => 'vsave'), array('class' => 'btn btn-danger btnRowDeleteEmployee', 'escape' => false, 'title' => 'Eliminar'));
									?>
								</td>
							</tr>
							
							<tr>
								<td style="text-align: center;"><span class="spaNumber">2</span> <input type="text" value="<?php echo"222";?>" class="spaIdEmployee"></td>
								<td><span class="spaNameEmployee"><?php echo"Pamela Sanchez";?></span></td>
								<td><span class="spaPhoneEmployee"><?php echo"";?></span></td>
								<td><span class="spaEmailEmployee"><?php echo"";?></span></td>
								<td>
									<?php
									echo $this->Html->link('<i class="icon-pencil icon-white"></i>', array('action' => 'vsave'), array('class' => 'btn btn-primary btnRowEditEmployee', 'escape' => false, 'title' => 'Editar'));
									echo ' '.$this->Html->link('<i class="icon-trash icon-white"></i>', array('action' => 'vsave'), array('class' => 'btn btn-danger btnRowDeleteEmployee', 'escape' => false, 'title' => 'Eliminar'));
									?>
								</td>
							</tr>
							
						</tbody>
					</table>		

				</div>


				<div id="tab2" class="tab-pane">
					youall	
				</div>
			</div>
		</div>


	</div>
</div>
<?php echo $this->BootstrapForm->end(); ?>


