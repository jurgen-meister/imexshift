<?php echo $this->Html->script('modules/InvMovements', FALSE); ?>
<!-- ************************************************************************************************************************ -->
<div class="span12"><!-- START CONTAINER FLUID/ROW FLUID/SPAN12 - FROM MAIN TEMPLATE #UNICORN -->
<!-- ************************************************************************************************************************ -->
		<h3><?php
			//echo $this->Html->link('<i class="icon-plus icon-white"></i>', array('action' => 'save_in'), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Nuevo')); 
			?>
<?php echo __(' Reporte Movimientos');?></h3>
		
		<!-- *********************************************** #UNICORN SEARCH WRAP ********************************************-->
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>Filtro</h5>
			</div>
			<div class="widget-content nopadding">
			<!-- ////////////////////////////////////////START - FILTERS////////////////////////////////////////////////-->
				
					<form class="form-horizontal">
					  <div class="row-fluid">
						<div class="span3">
							<?php 
						  echo $this->BootstrapForm->input('start_date', array(
							'label' => 'Fecha Inicio:',
							'id'=>'cbxMovementTypes',
							//'div'=>false,
							//"class"=>"span4"  
							//'helpInline' =>'<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
						  ));
						  ?>
							<!--
						  <label>Fecha Inicio:</label>
						  <input type="text" class="input-xlarge" id="input01">
						-->
						</div>
						<div class="span3">
							<!--
							<label>Fecha Fin:</label>
							  <input type="text" class="input-xlarge" id="input01">
								-->
								<?php 
						  echo $this->BootstrapForm->input('finish_date', array(
							'label' => 'Fecha Fin:',
							'id'=>'cbxMovementTypes',
							//'div'=>false,
							//"class"=>"span4"  
								//'helpInline' => /*$btnAddMovementType.*/'<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
						  ));
						  ?>
						</div>
					  </div>
						
					  <div class="row-fluid">
						  <?php 
						  echo $this->BootstrapForm->input('inv_movement_type_id', array(
							'label' => 'Tipo Movimiento:',
							'id'=>'cbxMovementTypes',
							//'div'=>false,
							//"class"=>"span4"  
								//'helpInline' => /*$btnAddMovementType.*/'<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
						  ));
						  echo $this->BootstrapForm->input('inv_movement_type_id', array(
							'label' => 'Almacen:',
							'id'=>'cbxMovementTypes',
							//'div'=>false,
							//"class"=>"span4"  
						  ));
						  ?>
						  <div class="control-group">
						  
							<label for="InvMovementCode" class="control-label">Agrupar por:</label>
							<div class="controls">
							<table>
								<tr>
									<td>
										<label class="radio">
											<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">
											Marca 
										</label>
									</td>
									<td>
										<!--
										<select multiple="multiple">
										  <option>1</option>
										  <option>2</option>
										  <option>3</option>
										  <option>4</option>
										  <option>5</option>
										</select>
										-->
										<?php
										echo $this->BootstrapForm->input('brands', array(
											'label' => false,
											'type'=>'select',
											'options'=>$brand,
											'multiple'=>'multiple',
											'div'=>false,
											'id'=>'cbxMovementTypes',
										//	'selected'=>array(5)
										//	"class"=>"input-xlarge"
										//'placeholder'=>'jkfjkdsfd'
										));
										?>
									</td>
								</tr>
								<tr>
									<td>
										<label class="radio">
											<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">
											Categoria 
										</label>
									</td>
									<td>
										<?php
										echo $this->BootstrapForm->input('category', array(
											'label' => false,
											'type'=>'select',
											'options'=>$category,
											'multiple'=>'multiple',
											'div'=>false,
											'id'=>'cbxMovementTypes',
											'selected'=>array(5)
										));
										?>
									</td>
								</tr>
								<tr>
									<td><label class="radio">
											<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
											Ninguno
										</label>
									</td>
									<td></td>
								</tr>
							</table>
							</div><!-- class="controls" -->
						 </div><!-- class="control-group" -->
						  
					  </div><!-- class="row-fluid" -->
					</form>
			<br>
			<!-- ////////////////////////////////////////END - FILTERS////////////////////////////////////////////////-->		
			</div>
		<!-- *********************************************** #UNICORN SEARCH WRAP ********************************************-->
		</div>
		
<!-- ************************************************************************************************************************ -->
</div><!-- END CONTAINER FLUID/ROW FLUID/SPAN12 - FROM MAIN TEMPLATE #UNICORN
<!-- ************************************************************************************************************************ -->
