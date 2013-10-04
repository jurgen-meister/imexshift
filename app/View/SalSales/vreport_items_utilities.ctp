<span style="font-size: 25px; font-weight: bold">IMEXPORT</span><span style="font-weight: bold">SRL</span>
<hr style="height: 2px; color: #000; background-color: #000;">
<div style="font-size: 20px; font-weight: bold; text-align:center; text-decoration: underline;">UTILIDADES DE ITEMS</div>
<br>
<?php 
$currencyAbbr = '(Bs)';
if($data["currency"]=="DOLARES"){
	$currencyAbbr = '($us)';
}
?>

<table class="report-table" border="0" style="border-collapse:collapse; width:100%;">
	<thead>
	<tr style="text-align:center">
		<th style="width:33%">Fecha Inicio:</th>
		<th style="width:33%">Fecha Fin:</th>
		<th style="width:33%">Tipo de Cambio:</th>
	</tr>
	</thead>
	<tbody>
		<tr style="text-align:center">
			<td><?php echo $data['startDate'];?></td>
			<td><?php echo $data['finishDate'];?></td>
			<td><?php echo $data['currency'];?></td>
		</tr>
	</tbody>
</table>
<hr style="height: 1px; color: #444; background-color: #444;">


<table class="report-table" border="1" style="border-collapse:collapse; width:100%;">
	<thead>
				<tr>
					<th>#</th>
					<th>Item (Unidades)</th>
					<th>Total Venta <?php echo $currencyAbbr;?></th>
					<th>Total Costo CIF <?php echo $currencyAbbr;?></th>
					<th>Utilidad <?php echo $currencyAbbr;?></th>
					<th>%M</th>
				</tr>
			</thead>
			<?php $counter=1; ?>
			<?php foreach($dataDetails as $dataDetail){?>
			<tr>
					<td style="text-align: center;"><?php echo $counter;?></td>
					<td style="padding-left: 10px;"><?php echo $dataDetail["full_name"];?></td>
					<td style="text-align: center;"><?php echo number_format($dataDetail["sale"],2);?></td>
					<td style="text-align: center;"><?php echo number_format($dataDetail["cif"],2);?></td>
					<td style="text-align: center;"><?php echo number_format($dataDetail["utility"],2);?></td>
					<td style="text-align: center;"><?php echo number_format($dataDetail["margin"],2);?></td>
			</tr>
			<?php $counter++;
			}
			?>
</table>
<br>
