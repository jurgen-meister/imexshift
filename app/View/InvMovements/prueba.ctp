<span style="font-size: 25px; font-weight: bold">IMEXPORT</span><span style="font-weight: bold">SRL</span>
<!--<div style="height: 2px; background-color: black" ></div>-->
<hr style="height: 2px; color: #000; background-color: #000;">
<div style="font-size: 20px; font-weight: bold; text-align:center; text-decoration: underline;">MOVIMIENTOS DE ALMACEN: <?php echo $documentHeader['movementTypeName'];?></div>
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
			<td><?php echo $documentHeader['currencyName'];?></td>
		</tr>
	</tbody>
</table>
<hr style="height: 1px; color: #444; background-color: #444;">
<?php foreach($auxArray as $val){ ?>
	
	
	
	
	<table class="report-table" border="0" style="border-collapse:collapse; width:100%;">
		<tr>
			<td colspan="2" ><span style="font-weight:bold;">Item: </span><?php echo $val['codeName']; ?></td>
		</tr>
		<tr>
			<td><span style="font-weight:bold;">Categoria: </span><?php echo $val['categories']; ?></td>
			<td><span style="font-weight:bold;">Marca: </span><?php echo $val['brands']; ?></td>
		</tr>
	</table>	
		
	
	
	<?php if(count($val['movements']) > 0){ ?>
	<table class="report-table" border="1" style="border-collapse:collapse; width:100%;">
								<thead>
									<tr> <th style="width:100%" colspan="9">Movimientos</th></tr>
										
									<tr>
										<th >Fecha</th>
										<th >Codigo</th>
										<th >Cant (Uni)</th>
										<th >P.FOB (Bs)</th>
										<th >P.FOBxCant (Bs)</th>
										<th >P.CIF (Bs)</th>
										<th >P.CIFxCant (Bs)</th>
										<th >P.Venta (Bs)</th>
										<th >P.VentaxCant (Bs)</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($val['movements'] as $movement){?>
									<tr>
										<td style="text-align:center"><?php echo $movement['date'];?></td>
										<td style="text-align:center"><?php echo $movement['code'];?></td>
										<td style="text-align:center;font-weight:bold;"><?php echo $movement['quantity'];?></td>
										<td style="text-align:center"><?php echo $movement['fob'];?></td>
										<td style="text-align:center; font-weight:bold;"><?php echo $movement['fobQuantity'];?></td>
										<td style="text-align:center"><?php echo $movement['cif'];?></td>
										<td style="text-align:center; font-weight:bold;"><?php echo $movement['cifQuantity'];?></td>
										<td style="text-align:center"><?php echo $movement['sale'];?></td>
										<td style="text-align:center; font-weight:bold;"><?php echo $movement['saleQuantity'];?></td>
									</tr>
									<?php } ?>
									<tr>
										<td colspan="2" style="text-align:right;font-weight:bold; padding-right: 10px">Total: </td>
										<td style="text-align:center;font-weight:bold;"><?php echo $val['totalQuantity']; ?></td>
										<td style="text-align:center;font-weight:bold;"><?php echo $val['totalFob']; ?></td>
										<td style="text-align:center;font-weight:bold;"><?php echo $val['totalFobQuantity']; ?></td>
										<td style="text-align:center;font-weight:bold;"><?php echo $val['totalCif']; ?></td>
										<td style="text-align:center;font-weight:bold;"><?php echo $val['totalCifQuantity']; ?></td>
										<td style="text-align:center;font-weight:bold;"><?php echo $val['totalSale']; ?></td>
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

