$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');
	
	////////////paths validations///////////////

	
	//Calendar script
	$('#date').glDatePicker(
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
	
	
	//////****************Core = add, validate, save, edit, delete, etc******************///////////
	//Validate only numbers
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
	

	///**************EVENTS***************
	var rowsNumber = 0; //static variable for rows items
	
	//Call modal
	$('#addItem').click(function(){
		//var itemsAlreadySaved = '';  //string version
		var itemsAlreadySaved = []; //array version
		rowsNumber = 0;
		$('#tablaItems tbody tr').each(function(){
			//itemsAlreadySaved = itemsAlreadySaved +',' +$(this).find("#item_hidden").val();    //string version
			itemsAlreadySaved.push($(this).find("#item_hidden").val());	   
			rowsNumber++;
		})
		
		if(rowsNumber == 0){  //For fix undefined index
			itemsAlreadySaved = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
		}
		
		ajax_initiate_modal_add_item_in(itemsAlreadySaved);

	});
	
	//Merge item into the table
	
	
	
	$('#saveItem').click(function(){
		
		
		
		var number = rowsNumber + 1;
		var quantity = $('#quantity').val();
		var itemId = $('#items').val();
		var itemName = $('#items option:selected').text();
		var stock = $('#stock').val();
		var error = validateSaveItem(itemName, quantity, ''); 
		if(error == ''){
			$('#tablaItems > tbody:last').append('<tr>\n\
												<td>'+number+'</td>\n\
												<td>'+itemName+'<input type="hidden" value="'+itemId+'" id="item_hidden" ></td>\n\
												<td>'+stock+'<input type="hidden" value="'+stock+'" id="stock_hidden" ></td>\n\
												<td>'+quantity+'<input type="hidden" value="'+quantity+'" id="quantity_hidden" ></td>\n\
												<td>\n\
													<a class="btn" href="#" id="editItem" title="Editar"><i class="icon-pencil"></i></a>\n\
													<a class="btn" href="#" id="deleteItem" title="Eliminar"><i class="icon-trash"></i></a>\n\
												</td>\n\
											 </tr>');
			$('#modalAddItem').modal('hide');
		}else{
			//alert('no puede haber campos vacios');
			$('#itemSaveError').html(error);
		}
		
		
	});
	
	function validateSaveItem(item, quantity, documentQuantity){
		var error = '';
		if(quantity == ''){	error+='- El campo "Cantidad" no puede estar vacio <br>'; }
		if(item == ''){	error+='- El campo "Item" no puede estar vacio <br>'; }
		return error;
	}
	
	///**************AJAX AND OTHER FUNCTIONS***************
	
	
	function ajax_initiate_modal_add_item_in(itemsAlreadySaved){
		 $.ajax({
            type:"POST",
            url:moduleController + "ajax_initiate_modal_add_item_in",			
            data:{itemsAlreadySaved: itemsAlreadySaved, warehouse: $('#warehouses').val()},
            beforeSend: showProcessing,
            success: function(data){
				$('#processing').text('');
				$('#itemSaveError').text('');
				$('#boxIntiateModal').html(data);
				$('#quantity').val('');
				$('#modalAddItem').modal({
					show: 'true',
					backdrop:'static'
				});
				$('#items').bind("change",function(){ //must be binded 'cause dropbox is loaded by a previous ajax'
					ajax_update_stock();
				});
				$('#stock').keypress(function(){
					return false;
				});
			}
        });
	}
	
	function ajax_update_stock(){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_update_stock",			
            data:{warehouse: $('#warehouses').val(), item: $('#items').val()},
            beforeSend: showProcessing,
            success: function(data){
				$('#processing').text("");
				$('#boxStock').html(data);
				$('#stock').bind("keypress",function(){ //must be binded 'cause input is re-loaded by a previous ajax'
					return false;
				});
			}
        });
	}
	
	function showProcessing(){
        $("#processing").text("Procesando...");
    }
	
	
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














