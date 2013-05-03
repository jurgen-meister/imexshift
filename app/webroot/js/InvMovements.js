$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation

	var arrayItemsAlreadySaved = []; 
	startEventsWhenExistsItems();
	

	//************************************************************************//
	//////////////////////////////////BEGIN-FUNCTIONS////////////////
	//************************************************************************//
	//When exist items, it starts its events and fills arrayItemsAlreadySaved
	function startEventsWhenExistsItems(){
		var arrayAux = [];
		arrayAux = getItemsDetails();
		if(arrayAux[0] != 0){
			for(var i=0; i< arrayAux.length; i++){
				 arrayItemsAlreadySaved[i] = arrayAux[i]['InvMovementDetail']['inv_item_id'];
				 createEventClickEditItemButton(arrayAux[i]['InvMovementDetail']['inv_item_id']);
				 createEventClickDeleteItemButton(arrayAux[i]['InvMovementDetail']['inv_item_id']);			 
			}
		}
		/*else{
			alert('esta vacio');
		}*/
	}
	
	//validates before add item quantity
	function validateItem(item, quantity, documentQuantity){
		var error = '';
		if(quantity == ''){	error+='- El campo "Cantidad" no puede estar vacio <br>'; }
		if(item == ''){	error+='- El campo "Item" no puede estar vacio <br>'; }
		return error;
	}
	
	function validateBeforeSaveAll(arrayItemsDetails){
		var error = '';
		var date = $('#date').val();
		var warehouses = $('#warehouses').text();
		var movementTypes = $('#movementTypes').text();
		var movement_hidden = $('#movement_hidden').val();

		if(date == ''){	error+='- El campo "Fecha" no puede estar vacio <br>'; }
		if(warehouses == ''){	error+='- El campo "Almacen" no puede estar vacio <br>'; }
		if(movementTypes == ''){	error+='- El campo "Tipo Movimiento" no puede estar vacio <br>'; }
		if(movement_hidden == ''){	//if it's new
			if(arrayItemsDetails[0] == 0){
				error+='- Debe existir al menos 1 "Item" <br>'; 
			}
		}
		return error;
	}
	
	function initiateModal(){
		$('#modalAddItem').modal({
					show: 'true',
					backdrop:'static'
		});
	}
	
	function validateOnlyNumbers(event){
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
	}
	
	function initiateModalAddItem(){
		if(arrayItemsAlreadySaved.length == 0){  //For fix undefined index
			arrayItemsAlreadySaved = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
		}
		$('#btnSaveAddItem').show();
		$('#btnSaveEditItem').hide();
		$('#boxValidateItem').html('');//clear error message
		ajax_initiate_modal_add_item_in(arrayItemsAlreadySaved);
	}
	
	function initiateModalEditItem(objectTableRowSelected){
		var itemIdForEdit = objectTableRowSelected.find('#item_hidden').val();  //
		$('#btnSaveAddItem').hide();
		$('#btnSaveEditItem').show();
		$('#boxValidateItem').html('');//clear error message
		$('#quantity').val(objectTableRowSelected.find('#quantity_hidden'+itemIdForEdit).text());
		$('#stock').val(objectTableRowSelected.find('#stock_hidden'+itemIdForEdit).text());
		$('#items').empty();
		$('#items').append('<option value="'+itemIdForEdit+'">'+objectTableRowSelected.find('td').text()+'</option>');
		initiateModal();
	}
	
	function createEventClickEditItemButton(itemId){
			$("#btnEditItem"+itemId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr')
					initiateModalEditItem(objectTableRowSelected);
					return false; //avoid page refresh
			});
	}
	
	function createEventClickDeleteItemButton(itemId){
		$("#btnDeleteItem"+itemId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr')
					deleteItem(objectTableRowSelected);
					return false; //avoid page refresh
		});
	}
	
	function deleteItem(objectTableRowSelected){
		if(confirm('Esta seguro de Eliminar el item?')){	

			var itemIdForDelete = objectTableRowSelected.find('#item_hidden').val();  //
			arrayItemsAlreadySaved = jQuery.grep(arrayItemsAlreadySaved, function(value){
				return value != itemIdForDelete;
			});
			objectTableRowSelected.remove();
		}
	}
	
	function createRowItemTable(itemId, itemCodeName, stock, quantity){
		$('#tablaItems > tbody:last').append('<tr>\n\
												<td>'+itemCodeName+'<input type="hidden" value="'+itemId+'" id="item_hidden" ></td>\n\
												<td><span id="stock_hidden'+itemId+'">'+stock+'</span></td>\n\
												<td><span id="quantity_hidden'+itemId+'">'+quantity+'</span></td>\n\
												<td>\n\
													<a class="btn" href="#" id="btnEditItem'+itemId+'" title="Editar"><i class="icon-pencil"></i></a>\n\
													<a class="btn" href="#" id="btnDeleteItem'+itemId+'" title="Eliminar"><i class="icon-trash"></i></a>\n\
												</td>\n\
											 </tr>');
	}
	
	function editItem(){
		var itemId = $('#items').val();
		var quantity = $('#quantity').val();
		var itemCodeName = $('#items option:selected').text();
		var error = validateItem(itemCodeName, quantity, ''); 
		if(error == ''){
			$('#quantity_hidden'+itemId).text(quantity);
			$('#modalAddItem').modal('hide');
		}else{
			$('#boxValidateItem').html(error);
		}
	}
	
	function addItem(){
		var quantity = $('#quantity').val();
		var itemId = $('#items').val();
		var itemCodeName = $('#items option:selected').text();
		var stock = $('#stock').val();
		var error = validateItem(itemCodeName, quantity, ''); 
		if(error == ''){
			createRowItemTable(itemId, itemCodeName, stock, quantity);
			createEventClickEditItemButton(itemId);
			createEventClickDeleteItemButton(itemId);
			arrayItemsAlreadySaved.push(itemId);  //push into array of the added item
			$('#modalAddItem').modal('hide');
		}else{
			$('#boxValidateItem').html(error);
		}
	}
	
	//get all items for save a movement
	function getItemsDetails(){		
		var arrayItemsDetails = [];
		var itemId = '';
		var itemStock = '';
		var itemQuantity = '';
		
		$('#tablaItems tbody tr').each(function(){		
			itemId = $(this).find('#item_hidden').val();
			itemStock = $(this).find('#stock_hidden'+itemId).text();
			itemQuantity = $(this).find('#quantity_hidden'+itemId).text();
			arrayItemsDetails.push({'InvMovementDetail':{'inv_item_id':itemId, 'stock':itemStock, 'quantity':itemQuantity}});

		});
		
		if(arrayItemsDetails.length == 0){  //For fix undefined index
			arrayItemsDetails = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
		}
		
		return arrayItemsDetails; 		
	}
	
	//show message of procesing for ajax
	function showProcessing(){
        $("#processing").text("Procesando...");
    }
	//************************************************************************//
	//////////////////////////////////END-FUNCTIONS//////////////////////
	//************************************************************************//
	
	
	
	
	//************************************************************************//
	//////////////////////////////////BEGIN-CONTROLS EVENTS/////////////////////
	//************************************************************************//
	//Validate only numbers
	$("#quantity").keydown(function(event) {
			validateOnlyNumbers(event);			
	});
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
	//Call modal
	$('#btnAddItem').click(function(){
		initiateModalAddItem();
		return false; //avoid page refresh
	});
	//Add a new item quantity
	$('#btnSaveAddItem').click(function(){
		addItem();
		return false; //avoid page refresh
	});
	
	//edit an existing item quantity
	$('#btnSaveEditItem').click(function(){
		editItem();
		return false; //avoid page refresh
	});
	
	//saves all movement
	$('#btnSaveAll').click(function(){
		
		var arrayItemsDetails = [];
		arrayItemsDetails = getItemsDetails();
		
		
		var error = validateBeforeSaveAll(arrayItemsDetails);
		if( error == ''){
			ajax_save_movement_in(arrayItemsDetails);
		}else{
			$('#boxMessage').html('<div class="alert-error">'+error+'</div>');
		}
		
		return false; //avoid page refresh
	});
	
	$('#btnChangeState').click(function(){
		alert('aqui ira validaciones y cambios estado del documento');
		return false;
	});
	
	//************************************************************************//
	//////////////////////////////////END-CONTROLS EVENTS//////////////////////
	//************************************************************************//
	
	
	
	
	//************************************************************************//
	//////////////////////////////////BEGIN-AJAX FUNCTIONS//////////////////////
	////************************************************************************//
	
	
	//Save movement IN
	function ajax_save_movement_in(arrayItemsDetails){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_save_movement_in",			
            data:{arrayItemsDetails: arrayItemsDetails},
            beforeSend: showProcessing(),
            success: function(data){
				$('#processing').text('');
				$('#boxMessage').text(data);
				$('#btnChangeState').hide();
			}
        });
	}
	
	//Get items and stock for the fist item when inititates modal
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
	
	//Update one stock value
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
	
	//************************************************************************//
	//////////////////////////////////END-AJAX FUNCTIONS////////////////////////
	//************************************************************************//
	
//END SCRIPT	
});
