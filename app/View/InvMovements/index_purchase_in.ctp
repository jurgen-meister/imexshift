<?php echo  $this->BootstrapPaginator->options(array('url' => $this->passedArgs));?>
<div class="span9">
		<h2><?php echo __('Entradas de Compras al Almacén');?></h2>
		<!-- ////////////////////////////////////////INCIO - FORMULARIO BUSQUEDA////////////////////////////////////////////////-->
		<?php echo $this->BootstrapForm->create('InvMovement', array('class' => 'form-search', 'novalidate' => true));?>
		<fieldset>
		<legend><?php echo __(''); ?></legend>
					<?php
					echo $this->BootstrapForm->input('document_code', array(				
							//'label' => 'Codigo Compra:',
							'id'=>'txtCodeDocument',
							'value'=>$document_code,
							'placeholder'=>'Codigo Compra'
							));
					?>
				<?php
					echo $this->BootstrapForm->submit('<i class="icon-search icon-white"></i>',array('class'=>'btn btn-primary','div'=>false, 'id'=>'btnSearch', 'title'=>'Buscar'));
				?>
		</fieldset>
		<?php echo $this->BootstrapForm->end();?>
		<!-- ////////////////////////////////////////FIN - FORMULARIO BUSQUEDA////////////////////////////////////////////////-->
		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Pagina {:page} de {:pages}, mostrando {:current} registros de {:count} total, comenzando en  {:start}, terminando en {:end}')));?>
		</p>
		<?php $cont = 1;?>
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<th><?php echo "#";?></th>
				<th><?php echo $this->BootstrapPaginator->sort('code', 'Codigo Compra');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('date', 'Fecha');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('inv_supplier_id', 'Proveedor');?></th>
				<!--<th><?php //echo ('Codigo Entrada Almacen');?></th>-->
				<th><?php echo 'Acción';?></th>
			</tr>
		<?php foreach ($purPurchases as $purPurchase): ?>
			<tr>
				<td><?php echo $cont++;?></td>
				<td><?php echo h($purPurchase['PurPurchase']['code']); ?>&nbsp;</td>
				<td>
					<?php 
					echo date("d/m/Y", strtotime($purPurchase['PurPurchase']['date']));
					?>
					&nbsp;
				</td>
				<td>
					<?php echo h($purPurchase['InvSupplier']['name']); ?>
				</td>
				<!--<td></td>-->
				<td><?php
				///////////START - SETTING URL AND PARAMETERS/////////////
					$url = array('action'=>'save_purchase_in');
					$parameters = $this->passedArgs;
					$parameters['document_code']=$purPurchase['PurPurchase']['code'];
				////////////END - SETTING URL AND PARAMETERS//////////////
					echo $this->Html->link('<i class="icon-circle-arrow-right icon-white"></i>'.__(' Entrada Almacen'), array_merge($url, $parameters), array('class'=>'btn btn-primary', 'escape'=>false)); 
					?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
</div>