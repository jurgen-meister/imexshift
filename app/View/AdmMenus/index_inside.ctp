<?php echo $this->Html->script('modules/AdmMenus', FALSE); ?>
<!-- ************************************************************************************************************************ -->
<div class="span12"><!-- START CONTAINER FLUID/ROW FLUID/SPAN12 - FORMATO DE #UNICORN -->
<!-- ************************************************************************************************************************ -->
<h3>
<?php
echo $this->Html->link('<i class="icon-plus icon-white"></i>', array('action' => 'add_inside'), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Nuevo')); 
?>
<?php echo __(' Menus/Permisos Internos');?></h3>

<!-- *********************************************** #UNICORN SEARCH WRAP ********************************************-->
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>Filtro</h5>
			</div>
			<div class="widget-content nopadding">
			<!-- ////////////////////////////////////////INCIO - FORMULARIO BUSQUEDA////////////////////////////////////////////////-->
			<?php echo $this->BootstrapForm->create('formAdmMenuIndexOut', array('id'=>'formAdmMenuIndexOut','class' => 'form-search', 'novalidate' => true));?>
			<fieldset>
						<?php
						echo $this->BootstrapForm->input('modules', array(				
										//'label' => 'Módulo:',
										'id'=>'cbxSearchModules',
										//'value'=>$code,
										'options'=>$modules,
										'type'=>'select',
										'placeholder'=>'Codigo Entrada'
										));
						?>
			</fieldset>
			<?php echo $this->BootstrapForm->end();?>
			<!-- ////////////////////////////////////////FIN - FORMULARIO BUSQUEDA////////////////////////////////////////////////-->		
			</div>
		</div>
		<!-- *********************************************** #UNICORN SEARCH WRAP ********************************************-->


		<!-- *********************************************** #UNICORN TABLE WRAP ********************************************-->
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5><?php echo $this->BootstrapPaginator->counter(array('format' => __('Página {:page} de {:pages}, mostrando {:current} de un total de {:count} registros')));?></h5>
			</div>
			<div class="widget-content nopadding">
		<!-- *********************************************** #UNICORN TABLE WRAP ********************************************-->
		
		<?php $cont = $this->BootstrapPaginator->counter('{:start}');?>
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<th><?php echo '#';?></th>
				<th><?php echo $this->BootstrapPaginator->sort('name', 'Menu/Permiso');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('AdmController.id', 'Controlador');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_action_id', 'Acción');?></th>
				<th></th>
			</tr>
		<?php foreach ($admMenus as $admMenu): ?>
			<tr>
				<td><?php echo $cont++; ?>&nbsp;</td>
				<td><?php echo h($admMenu['AdmMenu']['name']); ?>&nbsp;</td>
				<td><?php echo $admMenu['AdmController']['name'];?></td>
				<td><?php echo strtolower($admMenu['AdmAction']['name']); ?></td>
				<td>
					<?php 
					$url['action'] = 'edit_inside';
					echo $this->Html->link('<i class="icon-pencil icon-white"></i>'.__(''),  array_merge($url,array($admMenu['AdmMenu']['id'])), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Editar')); 
					echo ' '.$this->Form->postLink('<i class="icon-trash icon-white"></i>', array('action' => 'delete_inside', $admMenu['AdmMenu']['id']), array('class'=>'btn btn-danger', 'escape'=>false, 'title'=>'Eliminar'), __('¿Esta seguro de borrar este menu?', $admMenu['AdmMenu']['id']));
					?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<!-- *********************************************** #UNICORN TABLE WRAP ********************************************-->
		</div>
	</div>
	<!-- *********************************************** #UNICORN TABLE WRAP ********************************************-->
		<?php echo $this->BootstrapPaginator->pagination(); ?>
<!-- ************************************************************************************************************************ -->
</div><!-- FIN CONTAINER FLUID/ROW FLUID/SPAN12 - Del Template Principal #UNICORN
<!-- ************************************************************************************************************************ -->