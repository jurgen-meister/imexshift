<span style="font-size: 25px; font-weight: bold">IMEXPORT</span><span style="font-weight: bold">SRL</span>
<hr style="height: 2px; color: #000; background-color: #000;">
<div style="font-size: 20px; font-weight: bold; text-align:center; text-decoration: underline;">MOVIMIENTOS DE ALMACEN: <?php echo strtoupper($initialData['movementTypeName']);?></div>
<br>

<table class="report-table" border="0" style="border-collapse:collapse; width:100%;">
	<thead>
	<tr style="text-align:center">
		<th style="width:25%">Fecha Inicio:</th>
		<th style="width:25%">Fecha Fin:</th>
		<th style="width:25%">Almacen</th>
		<th style="width:25%">Tipo de Cambio:</th>
	</tr>
	</thead>
	<tbody>
		<tr style="text-align:center">
			<td><?php echo $initialData['startDate'];?></td>
			<td><?php echo $initialData['finishDate'];?></td>
			<td><?php echo strtoupper($initialData['warehouseName']);?></td>
			<td><?php echo $initialData['currency'];?></td>
		</tr>
	</tbody>
</table>
<hr style="height: 1px; color: #444; background-color: #444;">
<?php 
	$currencyAbbr = $initialData['currencyAbbreviation'];
	foreach($itemsMovements as $val){ 
	$quantityTotal = 0;
	$stockQuantity = 0;
	$inQuantityTotal = 0;
	$outQuantityTotal = 0;
	//$stock=0;
	$countMovements = 0;
	$colspanTableHeader = 2;
	if(isset($val['Movements'])){
		$countMovements = 1;
		$colspanTableHeader = 12;
	}
	
	foreach ($initialStocks as $valStock) {
		if($valStock['InvMovementDetail']['inv_item_id'] == $val['Item']['id']){
			$stockQuantity=$valStock[0]['stock'];
		}
	}
?>
	<table class="report-table" border="0" style="border-collapse:collapse; width:100%;">
		<tr>
			<td colspan="2" ><span style="font-weight:bold;">Item: </span><?php echo $val['Item']['codeName']; ?></td>
		</tr>
		<tr>
			<td><span style="font-weight:bold;">Categoria: </span><?php echo $val['Item']['category']; ?></td>
			<td><span style="font-weight:bold;">Marca: </span><?php echo $val['Item']['brand']; ?></td>
		</tr>
	</table>	
	<table class="report-table" border="1" style="border-collapse:collapse; width:100%;">
		
		<thead>
			<tr> <th style="width:100%" colspan="<?php echo $colspanTableHeader;?>">Movimientos</th></tr>
			<?php if($countMovements == 1){ ?>
			<tr >
				<th colspan="5" style="text-align:right; padding-right: 10px">Stock Inicial:</th>
				<th ><?php echo $stockQuantity; ?></th>
				<th colspan="6"></th>
			</tr>	
			<tr>
				<th>Fecha</th>
				<th>Codigo</th>
				<th>Codigo <br> Ref</th>
				<th>Cant. Ent <br>(Uni)</th>
				<th>Cant. Sal<br>(Uni)</th>
				<th>Stock <br> (Uni)</th>
				<th>P.FOB <br><?php echo $currencyAbbr ; ?></th>
				<th>P.FOB x Cant. <br><?php echo $currencyAbbr ; ?></th>
				<th>P.CIF <br><?php echo $currencyAbbr ; ?></th>
				<th>P.CIF x Cant. <br><?php echo $currencyAbbr ; ?></th>
				<th>P.Venta <br><?php echo $currencyAbbr ; ?></th>
				<th>P.Venta x Cant. <br><?php echo $currencyAbbr ; ?></th>
			</tr>
			<?php }else{?>
			<tr >
				<th  style="text-align:right; padding-right: 10px; width: 50%;">Stock Inicial:</th>
				<th  style="text-align:left; padding-left: 10px; width: 50%;"><?php echo $stockQuantity; ?></th>
			</tr>	
			<?php }?>
		</thead>

		<tbody>
			<?php 
				if($countMovements == 1){
					foreach($val['Movements'] as $movement){
			?>
					<tr style="text-align:center;">
						<td style="text-align:left;" ><?php echo $movement['date'];?></td>
						<td style="text-align:left;"><?php echo $movement['code'];?></td>
						<td style="text-align:left;"><?php echo $movement['document_code'];?></td>
						<?php
							$inQuantity = '-';
							$outQuantity = '-';

							if($movement['status'] == 'entrada'){
								$inQuantity = $movement['quantity'];
								$stockQuantity = $stockQuantity + $inQuantity;
								$inQuantityTotal = $inQuantityTotal + $inQuantity;  
							}else{//salida
								$outQuantity = $movement['quantity'];
								$stockQuantity = $stockQuantity - $outQuantity;
								$outQuantityTotal = $outQuantityTotal + $outQuantity;  
							}
						?>
						<td style="font-weight:bold;"><?php echo $inQuantity;?></td>
						<td style="font-weight:bold;"><?php echo $outQuantity;?></td>
						<td style="font-weight:bold;"><?php echo $stockQuantity;?></td>


						<td ><?php echo $movement['fob'];?></td>
						<td style="font-weight:bold;"><?php echo number_format($movement['fobQuantity'],2);?></td>
						<td ><?php echo $movement['cif'];?></td>
						<td style="font-weight:bold;"><?php echo number_format($movement['cifQuantity'],2);?></td>
						<td ><?php echo $movement['sale'];?></td>
						<td style="font-weight:bold;"><?php echo number_format($movement['saleQuantity'],2);?></td>
					</tr>
					<?php $quantityTotal = $quantityTotal + $movement['quantity'];?>


			<?php } //loop ends ?>

					<tr style="text-align:center;font-weight:bold;">
						<td colspan="3" style="text-align:right; padding-right: 10px">Total: </td>
						<td ><?php if($inQuantityTotal == 0){echo '-';}else{echo $inQuantityTotal;} ?></td>
						<td ><?php if($outQuantityTotal == 0){echo '-';}else{echo $outQuantityTotal;} ?></td>
						<td ><?php echo $stockQuantity; ?></td>
						<td ></td>
						<td ><?php echo number_format($val['TotalMovements']['fobQuantityTotal'],2); ?></td>
						<td ></td>
						<td ><?php echo number_format($val['TotalMovements']['cifQuantityTotal'],2); ?></td>
						<td ></td>
						<td ><?php echo number_format($val['TotalMovements']['saleQuantityTotal'],2); ?></td>
					</tr>

				<tr style="font-weight:bold;">
				<th colspan="5" style="text-align:right; padding-right: 10px">Stock Final:</td>
				<th style="text-align:center;"><?php echo $stockQuantity; ?></td>
				<th colspan="6"></td>
			</tr>	
			<?php }else{//$countMovements == 1 ?>
					<tr style="text-align:center;">
						<td colspan="2">SIN MOVIMIENTOS</td>
					</tr>
					<tr>
						<td  style="text-align:right; padding-right: 10px; font-weight:bold; width: 50%;">Stock Final: </td>
						<td  style="text-align:left; padding-left: 10px; font-weight:bold; width: 50%;"><?php echo $stockQuantity; ?></td>
					</tr>
			<?php } ?>
	</table>
	<hr style="height: 1px; color: #CCC; background-color: #CCC;">

<?php } ?>