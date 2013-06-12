<!-- ************************************************************************************************************************ -->
<div class="span12"><!-- START CONTAINER FLUID/ROW FLUID/SPAN12 - FORMATO DE #UNICORN -->
<!-- ************************************************************************************************************************ -->
<h3>
<?php //debug($admTransitions);
echo $this->Html->link('<i class="icon-plus icon-white"></i>', array('action' => 'add'), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Nuevo')); 
?>
<?php echo __(' Transiciones');?></h3>

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
				<th><?php echo $this->BootstrapPaginator->sort('AdmController.id','Controlador');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_state_id', 'Estado Incial');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_transaction_id', 'Transacción');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('adm_final_state_id', 'Estado Final');?></th>
				<th></th>
			</tr>
		<?php foreach ($admTransitions as $admTransition): ?>
			<tr>
				<td><?php echo $cont++; ?>&nbsp;</td>
				<td>
					<?php echo $admTransition['AdmController']['name']; ?>
				</td>
				<td>
					<?php echo $admTransition['AdmState']['name']; ?>
				</td>
				<td>
					<?php echo $admTransition['AdmTransaction']['name']; ?>
				</td>
				<td>
					<?php echo $admTransition['AdmFinalState']['name']; ?>
				</td>
				<td>
					<?php 
					$url['action'] = 'edit';
					echo $this->Html->link('<i class="icon-pencil icon-white"></i>'.__(''),  array_merge($url,array($admTransition['AdmTransition']['id'])), array('class'=>'btn btn-primary', 'escape'=>false, 'title'=>'Editar')); 
					echo ' '.$this->Form->postLink('<i class="icon-trash icon-white"></i>', array('action' => 'delete', $admTransition['AdmTransition']['id']), array('class'=>'btn btn-danger', 'escape'=>false, 'title'=>'Eliminar'), __('¿Esta seguro de borrar?', $admTransition['AdmTransition']['id']));
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
<!-- ************************************************************************************************************************ --></div></div>