$(document).ready(function(){
//START SCRIPT

///Url Paths
var path = window.location.pathname;
var arr = path.split('/');
var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation


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
            data:{year: $('#cbxYear').val(), priceType:$('#cbxPriceType').val() ,currency:$('#cbxCurrency').val() ,item:$('#cbxItem').val()},
			beforeSend: function(){
				$('#processing').text("Procesando...");
			},
            success: function(data){
				//var arrayData = data.split(",");
				var barOptions = createBarOptions();
				
				//Display graph    
				$.plot($(".bars"), createBarData(data), barOptions);
				
				//hide message
				$('#processing').text("");
			},
			error:function(data){
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
	$('#cbxItem, #cbxYear, #cbxCurrency, #cbxPriceType').change(function(){
		ajax_get_graphics_data();
	});
	

	
	
//END SCRIPT	
});
		