<span style="font-size: 30px; font-weight: bold">IMEXPORT</span><span style="font-weight: bold">SRL</span>
<!--<div style="height: 2px; background-color: black" ></div>-->
<hr style="height: 2px; color: #000; background-color: #000;">
<?php foreach($auxArray as $val){ ?>
	<p>Item: <?php echo $val['codeName']; ?></p>
	<p>Marca: <?php echo $val['brands']; ?></p>
	<p>Categoria: <?php echo $val['categories']; ?></p>
	<p>Cantidad Total: <?php echo $val['totalQuantity']; ?></p>

	<?php if(count($val['movements']) > 0){ ?>
	<table class="report-table" border="1" style="border-collapse:collapse; width:100%;">
								<thead>
									<tr> <th style="width:100%" colspan="3">MOVIMIENTOS</th></tr>
										
									<tr>
										<th style="width:50%">Fecha</th>
										<th style="width:25%">Codigo</th>
										<th style="width:25%">Cantidad</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($val['movements'] as $movement){?>
									<tr>
										<td style="width:50%;text-align:center"><?php echo $movement['date'];?></td>
										<td style="width:25%;text-align:center"><?php echo $movement['code'];?></td>
										<td style="width:25%;text-align:center"><?php echo $movement['quantity'];?></td>
									</tr>
									<?php } ?>
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
