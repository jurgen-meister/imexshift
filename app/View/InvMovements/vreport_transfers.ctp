<span style="font-size: 25px; font-weight: bold">IMEXPORT</span><span style="font-weight: bold">SRL</span>
<hr style="height: 2px; color: #000; background-color: #000;">
<div style="font-size: 20px; font-weight: bold; text-align:center; text-decoration: underline;">MOVIMIENTOS DE ALMACEN: <?php echo strtoupper($initialData['movementTypeName']);?></div>
<br>
<table class="report-table" border="0" style="border-collapse:collapse; width:100%;">
	<thead>
	<tr style="text-align:center">
		<th style="width:20%">Fecha Inicio:</th>
		<th style="width:20%">Fecha Fin:</th>
		<th style="width:20%">Almacen</th>
		<th style="width:20%">Almacen a Comparar</th>
		<th style="width:20%">Tipo de Cambio:</th>
	</tr>
	</thead>
	<tbody>
		<tr style="text-align:center">
			<td><?php echo $initialData['startDate'];?></td>
			<td><?php echo $initialData['finishDate'];?></td>
			<td><?php echo $initialData['warehouseName'];?></td>
			<td><?php echo $initialData['warehouseName2'];?></td>
			<td><?php echo $initialData['currency'];?></td>
		</tr>
	</tbody>
</table>
<hr style="height: 1px; color: #444; background-color: #444;">
<?php 
	$currencyAbbr = $initialData['currencyAbbreviation'];
	$warehousesInOut = array($initialData['warehouse']=>$initialData['warehouseName'], $initialData['warehouse2']=>$initialData['warehouseName2']);
	foreach($itemsMovements as $val){ 
	$quantityTotal = 0;
	$countMovements = 0;
	
	if(isset($val['Movements'])){
		$countMovements = 1;
	}
	$counter = 1;
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
		<?php if($countMovements == 1){?>
			<thead>
				<tr> <th style="width:100%" colspan="13">Movimientos</th></tr>

				<tr>
					<th>Fecha</th>
					<th>Codigo <br> Transfer</th>
					<th>Codigo <br> Salida</th>
					<th>Codigo <br> Entrada</th>
					<th>Almacen <br> Origen <br> (Salida)</th>
					<th>Almacen <br> Destino <br> (Entrada)</th>
					<th>Cant. <br> (Uni)</th>
					<th>P.FOB <br><?php echo $currencyAbbr ; ?></th>
					<th>P.FOB x Cant. <br><?php echo $currencyAbbr ; ?></th>
					<th>P.CIF <br><?php echo $currencyAbbr ; ?></th>
					<th>P.CIF x Cant. <br><?php echo $currencyAbbr ; ?></th>
					<th>P.Venta <br><?php echo $currencyAbbr ; ?></th>
					<th>P.Venta x Cant. <br><?php echo $currencyAbbr ; ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($val['Movements'] as $movement){?>
				<?php 
					if(($counter % 2) == 1){
						$codeIN = $movement['code'];
						$warehouseIN = $movement['warehouse'];
					}
					if(($counter % 2) == 0){ 
						$codeOUT = $movement['code'];
						$warehouseOUT = $movement['warehouse'];
				?>
					<tr style="text-align:center;">
						<td style="text-align:left;" ><?php echo $movement['date'];?></td>
						<td style="text-align:left;"><?php echo $movement['document_code'];?></td>
						<td style="text-align:left;"><?php echo $codeOUT;?></td>
						<td style="text-align:left;"><?php echo $codeIN;?></td>
						<td style="text-align:left;"><?php echo $warehousesInOut[$warehouseOUT];?></td>
						<td style="text-align:left;"><?php echo $warehousesInOut[$warehouseIN];?></td>
						<td style="font-weight:bold;"><?php echo $movement['quantity'];?></td>
						<td ><?php echo $movement['fob'];?></td>
						<td style="font-weight:bold;"><?php echo number_format($movement['fobQuantity'],2);?></td>
						<td ><?php echo $movement['cif'];?></td>
						<td style="font-weight:bold;"><?php echo number_format($movement['cifQuantity'],2);?></td>
						<td ><?php echo $movement['sale'];?></td>
						<td style="font-weight:bold;"><?php echo number_format($movement['saleQuantity'],2);?></td>
					</tr>
					<?php $quantityTotal = $quantityTotal + $movement['quantity'];?>
					<?php } $counter++;  //module?>
			<?php }//foreach movement?>		
					<tr style="text-align:center;font-weight:bold;">
						<td colspan="6" style="text-align:right; padding-right: 10px">Total: </td>
						<td ><?php echo $quantityTotal; ?></td>
						<td ></td>
						<td ><?php echo number_format(($val['TotalMovements']['fobQuantityTotal'] / 2),2); ?></td>
						<td ></td>
						<td ><?php echo number_format(($val['TotalMovements']['cifQuantityTotal'] / 2),2); ?></td>
						<td ></td>
						<td ><?php echo number_format(($val['TotalMovements']['saleQuantityTotal'] / 2),2); ?></td>
					</tr>
		<?php }else{?>
				<thead>
				<tr> <th style="width:100%" colspan="10">Movimientos</th></tr>
				</thead>
				<tbody>
					<tr style="text-align:center;"><td>SIN MOVIMIENTOS</td></tr>
				</tbody>
		<?php }?>		
	</table>
	<hr style="height: 1px; color: #CCC; background-color: #CCC;">

<?php } ?>