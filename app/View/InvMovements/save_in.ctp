<?php //echo $this->Html->script('jquery-1.8.3', FALSE); ?>
<?php echo $this->Html->script('InvMovements', FALSE); ?>
<?php echo $this->Html->script('glDatePicker', FALSE); ?>
<?php echo $this->Html->css('glDatePicker.flatwhite'); ?>
<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('InvMovement', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Entrada de Almacen'); ?></legend>
				<?php
				echo $this->BootstrapForm->input('date_in', array(
					'required' => 'required',
					'label' => 'Fecha:',
					'id'=>'date',
					'maxlength'=>'0',
					'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('inv_warehouse_id', array(
					'required' => 'required',
					'label' => 'Almacen:',
					'id'=>'warehouses',
					'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('inv_movement_type_id', array(
					'label' => 'Tipo Movimiento:',
					'id'=>'movementTypes',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('description', array(
					'rows' => 2,
					'style'=>'width:400px',
					'label' => 'Descripción:',
					'id'=>'description'
				));
				?>
				
				<?php 
				//echo $this->Html->image("add.png");
				//echo $this->Html->link(('(+) Nuevo'), array('action' => 'save_in')); 
				?>
				<!-- Button to trigger modal -->
				<!--<a href="#myModal" role="button" class="btn" data-toggle="modal">Adicionar</a>-->
				<button type="button" id="addItem">Adicionar</button>
				<!-- Modal -->
				<div id="modalAddItem" class="modal hide fade">
				  
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h3 id="myModalLabel">Adicionar Item</h3>
				  </div>
				  
				  <div class="modal-body">
					<!--<p>One fine body…</p>-->
					<?php
					echo '<div id="boxIntiateModal">';
						/*
						echo $this->BootstrapForm->input('item_id', array(				
						'label' => 'Item:',
						'id'=>'Item',
						'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
						));

						echo $this->BootstrapForm->input('stock', array(				
						'label' => 'Stock:',
						'id'=>'stock'
						));
						*/
					echo '</div>';
					
					echo $this->BootstrapForm->input('quantity', array(				
					'label' => 'Cantidad:',
					'id'=>'quantity',
					'style'=>'width:100px',
					'maxlength'=>'10',
					'helpInline' => '<span class="label label-important">' . ('Obligatorio') . '</span>&nbsp;'
					));
					?>
				  </div>
				  
				  <div class="modal-footer">
					<button class="btn btn-primary" id="saveItem">Aceptar</button>
					<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
				  </div>
					
				</div>

				
				<table class="table table-bordered table-condensed table-striped" id="tablaItems">
					<thead>
						<tr>
							<th>#</th>
							<th>Item</th>
							<th>Stock</th>
							<th>Cantidad</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
				<div class="form-actions">
				<?php 
					echo $this->BootstrapForm->submit('Guardar',array('class'=>'btn btn-primary','div'=>false, 'style'=>'margin-right:10px;'));					
					echo $this->Html->link('Cancelar', array('action'=>'index_in'), array('class'=>'btn') );
				?>
				</div>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
		<div id="processing"></div>
	</div>
</div>