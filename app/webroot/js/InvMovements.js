$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation
	///////Var for functionality//////
	var arrayItemsAlreadySaved = []; 
	
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
	
	
	//Call modal
	$('#btnAddItem').click(function(){

	   if(arrayItemsAlreadySaved.length == 0){  //For fix undefined index
			arrayItemsAlreadySaved = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
		}
	   
		ajax_initiate_modal_add_item_in(arrayItemsAlreadySaved);
		$('#btnSaveAddItem').show();
		$('#btnSaveEditItem').hide();
	});
	
	//Merge item into the table
	$('#btnSaveAddItem').click(function(){
		//var number = rowsItemsCount + 1;
		
		var quantity = $('#quantity').val();
		var itemId = $('#items').val();
		var itemCodeName = $('#items option:selected').text();
		var stock = $('#stock').val();
		var error = validateSaveItem(itemCodeName, quantity, ''); 
		if(error == ''){
			$('#tablaItems > tbody:last').append('<tr>\n\
												<td>'+itemCodeName+'<input type="hidden" value="'+itemId+'" id="item_hidden" ></td>\n\
												<td><span id="stock_hidden'+itemId+'">'+stock+'</span></td>\n\
												<td><span id="quantity_hidden'+itemId+'">'+quantity+'</span></td>\n\
												<td>\n\
													<a class="btn" href="#" id="btnEditItem'+itemId+'" title="Editar"><i class="icon-pencil"></i></a>\n\
													<a class="btn" href="#" id="btnDeleteItem'+itemId+'" title="Eliminar"><i class="icon-trash"></i></a>\n\
												</td>\n\
											 </tr>');
			
			$("#btnEditItem"+itemId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr')
					editItemsTableRow(objectTableRowSelected);
			});
			
			$("#btnDeleteItem"+itemId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr')
					deleteItemsTableRow(objectTableRowSelected);
			});
			
			
			arrayItemsAlreadySaved.push(itemId);  //push into array of the added item
			
			$('#modalAddItem').modal('hide');
						
		}else{
			//alert('no puede haber campos vacios');
			$('#itemSaveError').html(error);
		}
		
		
	});
	
	
	$('#btnPrueba').click(function(){
		alert(arrayItemsAlreadySaved);
		alert($('items').val());
	});
	
	$('#btnSaveEditItem').click(function(){
		
		var itemId = $('#items').val();
		//var stock = $('#stock').val();//stock is static
		var quantity = $('#quantity').val();
		
		//$('#stock_hidden'+itemId).text(stock); //stock is static
		$('#quantity_hidden'+itemId).text(quantity);
		$('#modalAddItem').modal('hide');
		//alert('aqui se editara sobre la misma fila ->' + itemId);
	});
	
	function validateSaveItem(item, quantity, documentQuantity){
		var error = '';
		if(quantity == ''){	error+='- El campo "Cantidad" no puede estar vacio <br>'; }
		if(item == ''){	error+='- El campo "Item" no puede estar vacio <br>'; }
		return error;
	}
	
	
	function deleteItemsTableRow(objectTableRowSelected){
		
		if(confirm('Esta seguro de Eliminar el item?')){	

			var itemIdForDelete = objectTableRowSelected.find('#item_hidden').val();  //
			arrayItemsAlreadySaved = jQuery.grep(arrayItemsAlreadySaved, function(value){
				return value != itemIdForDelete;
			});
			//alert(arrayItemsAlreadySaved);
			objectTableRowSelected.remove();
		}
	
	}
	
	function editItemsTableRow(objectTableRowSelected){
		var itemIdForEdit = objectTableRowSelected.find('#item_hidden').val();  //
		//alert(itemIdForEdit);
		
		$('#btnSaveAddItem').hide();
		$('#btnSaveEditItem').show();
		
		//$('#stock').val(objectTableRowSelected.find('#stock_hidden'+itemIdForEdit).text()); //stock is static
		$('#quantity').val(objectTableRowSelected.find('#quantity_hidden'+itemIdForEdit).text());
		$('#items').empty();

		//$('#items option:selected').val(itemIdForEdit);
		//$('#items option:selected').text(objectTableRowSelected.find('td').text());
		$('#items').append('<option value="'+itemIdForEdit+'">'+objectTableRowSelected.find('td').text()+'</option>');
		
		initiateModal();
	}
	
	
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
				initiateModal()
				$('#items').bind("change",function(){ //must be binded 'cause dropbox is loaded by a previous ajax'
					ajax_update_stock();
				});
				$('#stock').keypress(function(){
					return false;
				});
			}
        });
	}
	
	function initiateModal(){
		$('#modalAddItem').modal({
					show: 'true',
					backdrop:'static'
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














