<span style="font-size: 25px; font-weight: bold">IMEXPORT</span><span style="font-weight: bold">SRL</span>
<hr style="height: 2px; color: #000; background-color: #000;">
<div style="font-size: 20px; font-weight: bold; text-align:center; text-decoration: underline;">MOVIMIENTOS DE ALMACEN: <?php echo strtoupper($documentHeader['movementTypeName']);?></div>
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
			<td><?php echo $documentHeader['startDate'];?></td>
			<td><?php echo $documentHeader['finishDate'];?></td>
			<td><?php echo $documentHeader['warehouseName'];?></td>
			<td><?php echo $documentHeader['currency'];?></td>
		</tr>
	</tbody>
</table>
<hr style="height: 1px; color: #444; background-color: #444;">
<?php 
	$currencyAbbr = '';
	switch ($documentHeader['currency']) {
	 case 'BOLIVIANOS':
		$currencyAbbr = '<br>(Bs)';	 
		break;
	case 'DOLARES AMERICANOS':
		$currencyAbbr = '<br>($us)';	 
		break;
	}
?>
<?php 
	foreach($auxArray as $val){ 
?>
	
<?php 
$thQuantity = '<th>Cant. <br> (Uni)</th>';
$trMovementColspan = 10;
$tdTotalColspan = 3;
$stocksRow = '';
if($documentHeader['movementType'] == 1000){//IN AND OUTS
	$initialStock = $val['startDateStock'];
	$thQuantity = '<th>Cant. Ent <br>(Uni)</th><th>Cant. Sal<br>(Uni)</th><th>Stock <br> (Uni)</th>';
	$trMovementColspan=12;
	//$tdTotalQuantityColspan=5;
	$stocksRow = '<tr>';
	$stocksRow .= '<td><span style="font-weight:bold;">Stock Inicial: </span>'.$initialStock.'</td>';
	$stocksRow .= '<td><span style="font-weight:bold;">Stock Final: </span>'.$val['finishDateStock'].'</td>';
	$stocksRow .= '</tr>';
}
?>
	
	
	<table class="report-table" border="0" style="border-collapse:collapse; width:100%;">
		<tr>
			<td colspan="2" ><span style="font-weight:bold;">Item: </span><?php echo $val['codeName']; ?></td>
		</tr>
		<tr>
			<td><span style="font-weight:bold;">Categoria: </span><?php echo $val['categories']; ?></td>
			<td><span style="font-weight:bold;">Marca: </span><?php echo $val['brands']; ?></td>
		</tr>
		<?php echo $stocksRow;?>
	</table>	
	


	<?php if(count($val['movements']) > 0){ ?>
	<table class="report-table" border="1" style="border-collapse:collapse; width:100%;">
								<thead>
									<tr> <th style="width:100%" colspan="<?php echo $trMovementColspan;?>">Movimientos</th></tr>
										
									<tr>
										<th>Fecha</th>
										<th>Codigo</th>
										<th>Codigo <br> Ref</th>
										<?php echo $thQuantity; ?>
										<th>P.FOB <?php echo $currencyAbbr ; ?></th>
										<th>P.FOB x Cant. <?php echo $currencyAbbr ; ?></th>
										<th>P.CIF <?php echo $currencyAbbr ; ?></th>
										<th>P.CIF x Cant. <?php echo $currencyAbbr ; ?></th>
										<th>P.Venta <?php echo $currencyAbbr ; ?></th>
										<th>P.Venta x Cant. <?php echo $currencyAbbr ; ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($val['movements'] as $movement){?>
									<tr>
										<td ><?php echo $movement['date'];?></td>
										<td ><?php echo $movement['code'];?></td>
										<td ><?php echo $movement['document_code'];?></td>
										<?php 
										if($movement['status'] == ''){
											echo '<td style="text-align:center;font-weight:bold;">'.$movement['quantity'].'</td>';
										}else{
											if($movement['status'] == 'entrada'){
												echo '<td style="text-align:center;font-weight:bold;">'.$movement['quantity'].'</td>';
												echo '<td style="text-align:center;font-weight:bold;">-</td>';
												$initialStock = $initialStock + $movement['quantity'];
											}elseif ($movement['status'] == 'salida') {
												echo '<td style="text-align:center;font-weight:bold;">-</td>';
												echo '<td style="text-align:center;font-weight:bold;">'.$movement['quantity'].'</td>';
												$initialStock = $initialStock - $movement['quantity'];
											}
											echo '<td style="text-align:center;font-weight:bold;">'.$initialStock.'</td>';
										}
										?>
										<td style="text-align:center"><?php echo $movement['fob'];?></td>
										<td style="text-align:center; font-weight:bold;"><?php echo $movement['fobQuantity'];?></td>
										<td style="text-align:center"><?php echo $movement['cif'];?></td>
										<td style="text-align:center; font-weight:bold;"><?php echo $movement['cifQuantity'];?></td>
										<td style="text-align:center"><?php echo $movement['sale'];?></td>
										<td style="text-align:center; font-weight:bold;"><?php echo $movement['saleQuantity'];?></td>
									</tr>
									<?php } ?>
									<tr>
										<td colspan="<?php echo $tdTotalColspan;?>" style="text-align:right;font-weight:bold; padding-right: 10px">Total: </td>
										<?php 
										if($movement['status'] == ''){
											echo '<td style="text-align:center;font-weight:bold;">'.$val['totalQuantity'].'</td>';
										}else{
											$totalQuantityIN = $val['totalQuantityIN'];
											if($totalQuantityIN == 0){
												$totalQuantityIN = '-';
											}
											$totalQuantityOUT = $val['totalQuantityOUT'];
											if($totalQuantityOUT == 0){
												$totalQuantityOUT = '-';
											}
											echo '<td style="text-align:center;font-weight:bold;">'.$totalQuantityIN.'</td>';
											echo '<td style="text-align:center;font-weight:bold;">'.$totalQuantityOUT.'</td>';
											echo '<td style="text-align:center;font-weight:bold;">'.$val['finishDateStock'].'</td>';
										}
										?>
										<td style="text-align:center;font-weight:bold;"><?php //echo $val['totalFob']; ?></td>
										<td style="text-align:center;font-weight:bold;"><?php echo $val['totalFobQuantity']; ?></td>
										<td style="text-align:center;font-weight:bold;"><?php //echo $val['totalCif']; ?></td>
										<td style="text-align:center;font-weight:bold;"><?php echo $val['totalCifQuantity']; ?></td>
										<td style="text-align:center;font-weight:bold;"><?php //echo $val['totalSale']; ?></td>
										<td style="text-align:center;font-weight:bold;"><?php echo $val['totalSaleQuantity']; ?></td>
									</tr>
								</tbody>
							</table>
	<?php }else{ ?>
	<p> SIN MOVIMIENTOS</p>
	<table class="report-table" border="1" style="border-collapse:collapse; width:100%;">
	<thead>
		<tr><th>MOVIMIENTOS</th></tr>
		<tbody><tr><td style="text-align:center">Sin movimientos</td></tr></tbody>
	</thead>
	</table>
	<?php } ?>
<br>
<!-- <div style="height: 1px; background-color: black"></div> -->
<hr style="height: 1px; color: #CCC; background-color: #CCC;">
<?php }//debug($auxArray);?>

