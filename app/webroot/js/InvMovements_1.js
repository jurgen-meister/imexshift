$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');
	
	////////////paths validations///////////////
	/*
	if(arr[3].substr(0, 4) == 'edit'){
		alert('editar salida o entrada');
		$('#warehouses').keypress(function(){return false;});
		$('#movement_types').change(function(){return false;});
		$('#items').change(function(){return false;});
	}
	*/
   /*
   	if(arr[3].substr(0, 3) == 'add'){
		 $('#warehouses option:nth-child(1)').attr("selected", "selected");
		 $('#movement_types option:nth-child(1)').attr("selected", "selected");
		 $('#items option:nth-child(1)').attr("selected", "selected");
	}
	*/
	/////////numeric validation//////
	$("#quantity").keydown(function(event) {
						// Allow only backspace and delete
						if (event.keyCode == 8 || event.keyCode == 9 ) {
							// let it happen, don't do anything
						}
						else {
							// Ensure that it is a number and stop the keypress
							if ( (event.keyCode < 96 || event.keyCode > 105) ) { //habilita keypad
								if ( (event.keyCode < 48 || event.keyCode > 57) ) {
									event.preventDefault(); 
								}
							}   
						 }
	});
	
	
	//Events
    $('#movement_types').change(function(){
        
		//alert('reset all items and quantities, must list items by documents if they have one');
		//ajax_list_items_by_movement_type();
		clearTextboxes();
		
    });
	
	$('#avaliable').keypress(function(){
		return false;
    });
	
	
   $('#warehouses').change(function(){
		ajax_update_avaliable_quantity();		
    });
   
    $('#items').change(function(){
		ajax_update_avaliable_quantity();		
    });
   
   
   // Ajax methods
  
   function ajax_update_avaliable_quantity(){
       $.ajax({
            type:"POST",
            url:moduleController + "ajax_update_avaliable_quantity",			
            data:{warehouse: $("#warehouses").val(), item: $("#items").val()},
            beforeSend: showProcessing,
            success: function(data){
				$("#processing").text("");
				$("#boxAvaliable").html(data);
				$('#avaliable').bind("keypress",function(){
					return false;
				});
			}
        });
    }
	
	function ajax_list_items_by_movement_type(){
		//Need at least one document to finish this
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_list_items_by_movement_type",			
            data:{warehouse: $("#warehouses").val(), movement_type: $("#movement_types").val()},
            beforeSend: showProcessing,
            success: function(data){
				$("#processing").text("");
				$("#boxItemAvaliable").html(data);
			}
        });
	}
	///// Client Side Methods
	function showProcessing(){
        $("#processing").text("Procesando...");
    }
	
	function clearTextboxes(){
		$('#quantity').val('');
		$('#description').val('');
	}
	
	
	
	//call datepicker
	//$('#mydate').glDatePicker();
	
	$('#mydate').glDatePicker(
	{
		//showAlways: false,
		cssName: 'flatwhite',
		//cssName: 'darkneon',
		//selectedDate: new Date(2013, 0, 5),
		/*
		specialDates: [
			{
				date: new Date(2013, 0, 8),
				data: { message: 'Meeting every day 8 of the month' },
				repeatMonth: true
			},
			{
				date: new Date(0, 0, 1),
				data: { message: 'Happy New Year!' },
				repeatYear: true
			},
		],*/
		
		onClick: function(target, cell, date, data) {
			var correctMonth = date.getMonth() + 1;
			target.val(date.getDate() + ' / ' +
						correctMonth + ' / ' +
						date.getFullYear());

			if(data != null) {
				alert(data.message + '\n' + date);
			}
		}
		
	});
	
	
});














