$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');
	
	////////////paths validations///////////////

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
	
	$('#mydate').glDatePicker(
	{
		cssName: 'flatwhite',		
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
	
	
	
	
	/*
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
	
	*/
	
	//call datepicker
	//$('#mydate').glDatePicker();
	
	
});














