<span style="font-size: 25px; font-weight: bold">IMEXPORT</span><span style="font-weight: bold">SRL</span>
<hr style="height: 2px; color: #000; background-color: #000;">
<?php
$reportTypeName = " (DETALLADO) ";
if($initialData['detail'] == "NO"){
	$reportTypeName = " (TOTALES) ";
}
?>	
<div style="font-size: 20px; font-weight: bold; text-align:center; text-decoration: underline;">MOVIMIENTOS DE ALMACEN<?php echo $reportTypeName;?>: <?php echo strtoupper($initialData['movementTypeName']);?></div>
<br>

<table class="report-table" border="0" style="border-collapse:collapse; width:100%;">
	<thead>
	<tr style="text-align:center">
		<th style="width:25%">Fecha Inicio:</th>
		<th style="width:25%">Fecha Fin:</th>
		<th style="width:25%">Almacen:</th>
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
	//debug($initialData['detail']);
	$currencyAbbr = $initialData['currencyAbbreviation'];
	$globalStock = 0;
	$globalQuanttityIn = 0;
	$globalQuanttityOut = 0;
	$globalQuantityFOB = 0;
	$globalQuantityCIF = 0;
	$globalQuantitySALE = 0;
	
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
		$colspanTableHeader = 13;
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
			
			<?php if($initialData['detail'] == 'YES'){ //start - detail YES?>
			<tr >
				<th colspan="6" style="text-align:right; padding-right: 10px">Stock Inicial:</th>
				<th ><?php echo $stockQuantity; ?></th>
				<th colspan="6"></th>
			</tr>	
				
				<tr>
					<th>Fecha</th>
					<th>Codigo</th>
					<th>Codigo <br> Ref</th>
					<th>Nota <br> Remisión</th>
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
				<?php }else{ //end - detail YES?>
				<tr>
					<th></th>
					<th>Cant. Ent <br>(Uni)</th>
					<th>Cant. Sal<br>(Uni)</th>
					<th>Stock <br> (Uni)</th>
					<th>P.FOB x Cant. <br><?php echo $currencyAbbr ; ?></th>
					<th>P.CIF x Cant. <br><?php echo $currencyAbbr ; ?></th>
					<th>P.Venta x Cant. <br><?php echo $currencyAbbr ; ?></th>
				</tr>
				<?php } //end - detail NO?>
			<?php }else{?>
				<?php if($initialData['detail'] == 'YES'){ //start - detail YES?>
			<tr >
				<th  style="text-align:right; padding-right: 10px; width: 50%;">Stock Inicial:</th>
				<th  style="text-align:left; padding-left: 10px; width: 50%;"><?php echo $stockQuantity; ?></th>
			</tr>	
				<?php }//end - detail YES?>	
			<?php }?>
		</thead>

		<tbody>
			<?php 
				if($countMovements == 1){
					foreach($val['Movements'] as $movement){
			?>
			
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
				//debug($stockQuantity);
			?>
			
			<?php if($initialData['detail'] == 'YES'){ //start - detail YES?>
					<tr style="text-align:center;">
						<td style="text-align:left;" ><?php echo $movement['date'];?></td>
						<td style="text-align:left;"><?php echo $movement['code'];?></td>
						<td style="text-align:left;"><?php echo $movement['document_code'];?></td>
						<td style="text-align:left;"><?php echo $movement['note_code'];?></td>
						
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
					<?php } //end - detail YES?>

			<?php } //loop ends ?>

					<tr style="text-align:center;font-weight:bold;">
						<?php 
						$extraEmptyTotalTds = "<td ></td>";
						if($initialData['detail'] == 'YES'){ //start - detail YES?>
						<td colspan="4" style="text-align:right; padding-right: 10px">Total: </td>
						<?php }else{//end - detail YES ?>
						<td>Total: </td>
						<?php 
							$extraEmptyTotalTds = "";
						}//end - detail NO
						?>
						<td ><?php if($inQuantityTotal == 0){echo '-';}else{echo $inQuantityTotal;} ?></td>
						<td ><?php if($outQuantityTotal == 0){echo '-';}else{echo $outQuantityTotal;} ?></td>
						<td ><?php echo $stockQuantity; ?></td>
						<?php echo $extraEmptyTotalTds;?>
						<td ><?php echo number_format($val['TotalMovements']['fobQuantityTotal'],2); ?></td>
						<?php echo $extraEmptyTotalTds;?>
						<td ><?php echo number_format($val['TotalMovements']['cifQuantityTotal'],2); ?></td>
						<?php echo $extraEmptyTotalTds;?>
						<td ><?php echo number_format($val['TotalMovements']['saleQuantityTotal'],2); ?></td>
					</tr>
				<?php if($initialData['detail'] == 'YES'){ //start - detail YES?>
				<tr style="font-weight:bold;">
				<th colspan="6" style="text-align:right; padding-right: 10px">Stock Final:</td>
				<th style="text-align:center;"><?php echo $stockQuantity; ?></td>
				<th colspan="6"></td>
				<?php }//end - detail YES?>	
			</tr>	
			<?php }else{//$countMovements == 1 ?>
					<tr style="text-align:center;">
						<td colspan="2">SIN MOVIMIENTOS</td>
					</tr>
					<?php if($initialData['detail'] == 'YES'){ //start - detail YES?>
					<tr>
						<td  style="text-align:right; padding-right: 10px; font-weight:bold; width: 50%;">Stock Final: </td>
						<td  style="text-align:left; padding-left: 10px; font-weight:bold; width: 50%;"><?php echo $stockQuantity; ?></td>
					</tr>
					<?php }//end - detail YES?>	
			<?php } ?>
	</table>
	<hr style="height: 1px; color: #CCC; background-color: #CCC;">

<?php 
	//debug($stockQuantity);
	if($countMovements == 1){ 
		$globalStock = $globalStock + $stockQuantity;
	}
	//debug($globalStock);
	$globalQuanttityIn = $globalQuanttityIn + $inQuantityTotal;
	$globalQuanttityOut = $globalQuanttityOut + $outQuantityTotal;
	
	$globalQuantityFOB = $globalQuantityFOB + $val['TotalMovements']['fobQuantityTotal'];
	$globalQuantityCIF = $globalQuantityCIF + $val['TotalMovements']['cifQuantityTotal'];
	$globalQuantitySALE = $globalQuantitySALE + $val['TotalMovements']['saleQuantityTotal'];
?>
	
<?php } //end initial foreach?>

<?php 
if($initialData['detail'] == 'NO'){ //start - detail YES
?>
	<div style="font-size: 20px; font-weight: bold; text-align:center; text-decoration: underline;">TOTAL GLOBAL:</div>
	<br>
	<table class="report-table" border="1" style="border-collapse:collapse; width:100%;">
		<tr>
			<th></th>
			<th>Cant. Ent <br>(Uni)</th>
			<th>Cant. Sal<br>(Uni)</th>
			<th>Stock <br> (Uni)</th>
			<th>P.FOB x Cant. <br><?php echo $currencyAbbr ; ?></th>
			<th>P.CIF x Cant. <br><?php echo $currencyAbbr ; ?></th>
			<th>P.Venta x Cant. <br><?php echo $currencyAbbr ; ?></th>
		</tr>
		<tr style="text-align:center;font-weight:bold;">
			<td>TOTAL:</td>
			<td><?php echo $globalQuanttityIn;?></td>
			<td><?php echo $globalQuanttityOut;?></td>
			<td><?php echo $globalStock;?></td>
			<td><?php echo number_format($globalQuantityFOB,2);?></td>
			<td><?php echo number_format($globalQuantityCIF,2);?></td>
			<td><?php echo number_format($globalQuantitySALE,2);?></td>
		</tr>
	</table>	
	<br>
<?php	
}
?>