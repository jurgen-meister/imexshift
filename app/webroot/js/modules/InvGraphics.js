$(document).ready(function(){
//START SCRIPT

///Url Paths
var path = window.location.pathname;
var arr = path.split('/');
var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation

	function createPieData(sentData){
		//Format expected from ajax request is label1-data1|label2-data2
		var firstSplitedData = sentData.split("|");
		var secondSplitedData = [];
		var finalData = [];
		var label = "";
		var data = "";

		for(var i=0; i < firstSplitedData.length; i++){
			secondSplitedData = firstSplitedData[i].split("-");
			label = secondSplitedData[0];
			data = parseInt(secondSplitedData[1]);
			finalData[i] = {label:label, data:data};
		}
		return finalData;
	}

	//var series = Math.floor(Math.random()*10)+1;


	function createPieOptions(){
		var options =
			{
				series: {
					pie: {
						show: true,
						radius: 3/4,
						label: {
							show: true,
							radius: 3/4,
							formatter: function(label, series){
								return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">'+label+'<br/>'+Math.round(series.percent)+'%</div>';
							},
							background: {
								opacity: 0.5,
								color: '#000'
							}
						},
						innerRadius: 0.2
					}
				},
					legend: {
						show: false
					}
			};
			return options;
	}

	function createBarData(sentData){
		var splitData = sentData.split("|");
		var data = [];
		var finalData = [];
			for (var i=0; i < 12; i++){
				data[i]=[i+1,parseInt(splitData[i])];
			}
		finalData.push({
			data:data,
			bars: {
				show: true, 
				barWidth: 0.5, 
				order: 1
			}
		});
		return finalData;
	}

	function createBarOptions(){
		var options =
				{
					legend: true,
					/////////////////////
					 xaxis:{
							   ticks: [
										[1, "Ene"], [2, "Feb"], [3, "Mar"], [4, "Abr"], [5, "May"], [6, "Jun"],
										[7, "Jul"], [8, "Ago"], [9, "Sep"], [10, "Oct"], [11, "Nov"], [12, "Dic"]
							   ]
						}    
				/////////////////////
				};
		return options;
	}
	/////////////////////////////////////////////////////////

	function ajax_get_graphics_data(){ 
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_get_graphics_data",			
            data:{year: $('#cbxYear').val(), warehouse:$('#cbxWarehouse').val(), item:$('#cbxItem').val()},
			beforeSend: function(){
				$('#processing').text("Procesando...");
			},
            success: function(data){
				var arrayData = data.split(",");
				var pieOptions = createPieOptions();
				var barOptions = createBarOptions();
				
				//Display graph    
				$.plot($(".pie"), createPieData(arrayData[0]), pieOptions);
				$.plot($(".pie2"), createPieData(arrayData[1]), pieOptions);
				$.plot($(".bars"), createBarData(arrayData[2]), barOptions);
				$.plot($(".bars2"), createBarData(arrayData[3]), barOptions);
				
				//hide message
				$('#processing').text("");
			},
			error:function(data){
				//hideBittionAlertModal();
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text("");
			}
        });
	}
	
	
	
	
	
	function showGrowlMessage(type, text, sticky){
		if(typeof(sticky)==='undefined') sticky = false;
		var title;
		var image;
		switch(type){
			case 'ok':
				title = 'EXITO!';
				image= '/imexport/img/check.png';
				break;
			case 'error':
				title = 'OCURRIO UN PROBLEMA!';
				image= '/imexport/img/error.png';
				break;
			case 'warning':
				title = 'PRECAUCIÓN!';
				image= '/imexport/img/warning.png';
				break;
		}
		$.gritter.add({
			title:	title,
			text: text,
			sticky: sticky,
			image: image
		});	
	}
	
	//EXECUTE onload
	ajax_get_graphics_data();
	$('#cbxItem').select2();
	
	//events
	$('#cbxWarehouse, #cbxItem, #cbxYear').change(function(){
		ajax_get_graphics_data();
	});
	
	////////////////////////////////////////////////////////
//	var data = createPieData("Compras-175|Traspasos-25|Aperturas-25|Otros-25");
//	var options = createPieOptions();
//	
//	
//	//Display graph    
//	$.plot($(".pie"), data, options);
//	$.plot($(".pie2"), data, options);
//	////////////////////////////////////////////////////////
//
//
//	data = createBarData("62867|0|0|0|0|0|0|0|8769|0|0|0");
//	options = createBarOptions();
//
//	//Display graph
//	$.plot($(".bars"), data, options);
//	$.plot($(".bars2"), data, options);
	
	
//END SCRIPT	
});
		