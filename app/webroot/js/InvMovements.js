$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation

	var arrayItemsAlreadySaved = []; 
	startEventsWhenExistsItems();
	
	/////////////START - Token Status///////////////
	/*
	var tokenStatus = '';
	var tokenIn = 'entrada';
	var tokenOut = 'salida';
	if($('txtTokenStatusHidden').length > 0){ //exists
		tokenStatus = $('txtTokenStatusHidden').val();
	}
	*/
	/////////////FINISH - Token Status//////////////
	
	clearFieldsForFirefox();

	//************************************************************************//
	//////////////////////////////////BEGIN-FUNCTIONS////////////////
	//************************************************************************//
	//firefox doesn't clear by himself the fields when there is a new form :@
	function clearFieldsForFirefox(){
		if(arr[3] == 'save_in'){
			if(arr[4] == null){
				$('input').val('');//empty all inputs including hidden thks jquery 
			}
		}
	}
	
	//When exist items, it starts its events and fills arrayItemsAlreadySaved
	function startEventsWhenExistsItems(){
		var arrayAux = [];
		arrayAux = getItemsDetails();
		if(arrayAux[0] != 0){
			for(var i=0; i< arrayAux.length; i++){
				 arrayItemsAlreadySaved[i] = arrayAux[i]['inv_item_id'];
				 createEventClickEditItemButton(arrayAux[i]['inv_item_id']);
				 createEventClickDeleteItemButton(arrayAux[i]['inv_item_id']);			 
			}
		}
		/*else{
			alert('esta vacio');
		}*/
	}
	
	//validates before add item quantity
	function validateItem(item, quantity, documentQuantity){
		var error = '';
		if(quantity == ''){
			error+='<li>El campo "Cantidad" no puede estar vacio</li>'; 
		}else{
			if(parseInt(quantity, 10) == 0){
				
				error+='<li>El campo "Cantidad" no puede ser cero</li>'; 
			}
			if ($('#txtModalQuantityDocument').length > 0){//existe
				if(parseInt(quantity, 10) > $('#txtModalQuantityDocument').val()){
					error+='<li>La "Cantidad" de entrada no puede ser mayor a la "Compra"</li>'; 
				}
			}
		}
		if(item == ''){error+='<li>El campo "Item" no puede estar vacio</li>';}
		
		return error;
	}
	
	function validateBeforeSaveAll(arrayItemsDetails){
		var error = '';
		var date = $('#txtDate').val();
		var warehouses = $('#cbxWarehouses').text();
		if ($('#cbxMovementTypes').length > 0){//existe
			var movementTypes = $('#cbxMovementTypes').text();
			if(movementTypes == ''){	error+='<li> El campo "Tipo Movimiento" no puede estar vacio </li>'; }
		}
		if(date == ''){	error+='<li> El campo "Fecha" no puede estar vacio </li>'; }
		if(warehouses == ''){	error+='<li> El campo "Almacen" no puede estar vacio </li>'; }
		
		if(arrayItemsDetails[0] == 0){error+='<li> Debe existir al menos 1 "Item" </li>';}
		
		var itemZero = findIfOneItemHasQuantityZero(arrayItemsDetails);
		if(itemZero > 0){error+='<li> Se encontraron '+ itemZero +' "Items" con "Cantidad" 0, no puede existir ninguno </li>';}
		
		return error;
	}
	
	function findIfOneItemHasQuantityZero(arrayItemsDetails){
		var cont = 0;
		for(var i = 0; i < arrayItemsDetails.length; i++){
			if(parseInt(arrayItemsDetails[i]['quantity'],10) == 0){
				cont++;
			}
		}
		return cont;
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
	
	function validateWarehouse(){
		var arrayItemsDetails = [];
		arrayItemsDetails = getItemsDetails();
		
		if(arrayItemsDetails[0] != 0){
			ajax_update_multiple_stocks(arrayItemsDetails);
			alert('Se cambio de "Almacen", se actualizara los "Stocks" de los "Items"');
		}
	}
	
	function validateCancelMovementIn(arrayItemsStocksErrors){
		var error = '';
		var auxItemsStocks = [];
		for(var i=0; i<arrayItemsStocksErrors.length; i++){
			auxItemsStocks = arrayItemsStocksErrors[i].split('=>');//  item5=>9stock
			$('#txtStock'+auxItemsStocks[0]).text(auxItemsStocks[1]);
			if(auxItemsStocks[0] != ''){
				error+='<li>'+$('#spaItemName'+auxItemsStocks[0]).text()+': la "Cantidad = '+$('#spaQuantity'+auxItemsStocks[0]).text()+'" es mayor su "Stock = '+auxItemsStocks[1]+'" </li>';	
			}
		}
		return error;
	}
	
	function initiateModalAddItem(){
		if(arrayItemsAlreadySaved.length == 0){  //For fix undefined index
			arrayItemsAlreadySaved = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
		}
		$('#btnModalAddItem').show();
		$('#btnModalEditItem').hide();
		$('#boxModalValidateItem').html('');//clear error message
		ajax_initiate_modal_add_item_in(arrayItemsAlreadySaved);
	}
	
	function initiateModalEditItem(objectTableRowSelected){
		var itemIdForEdit = objectTableRowSelected.find('#txtItemId').val();  //
		$('#btnModalAddItem').hide();
		$('#btnModalEditItem').show();
		$('#boxModalValidateItem').html('');//clear error message
		$('#txtModalQuantity').val(objectTableRowSelected.find('#spaQuantity'+itemIdForEdit).text());
		$('#txtModalStock').val(objectTableRowSelected.find('#txtStock'+itemIdForEdit).text());
		$('#txtModalStock').keypress(function(){return false;});
		if ($('#txtModalQuantityDocument').length > 0){//existe
			$('#txtModalQuantityDocument').val(objectTableRowSelected.find('#spaQuantityDocument'+itemIdForEdit).text());
			$('#txtModalQuantityDocument').keypress(function(){return false;});
		}
		$('#cbxModalItems').empty();
		$('#cbxModalItems').append('<option value="'+itemIdForEdit+'">'+objectTableRowSelected.find('td').text()+'</option>');
		initiateModal();
	}
	
	function createEventClickEditItemButton(itemId){
			$('#btnEditItem'+itemId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr')
					initiateModalEditItem(objectTableRowSelected);
					return false; //avoid page refresh
			});
	}
	
	function createEventClickDeleteItemButton(itemId){
		$('#btnDeleteItem'+itemId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr')
					deleteItem(objectTableRowSelected);
					return false; //avoid page refresh
		});
	}
	
	function deleteItem(objectTableRowSelected){
		if(confirm('Esta seguro de Eliminar el item?')){	

			var itemIdForDelete = objectTableRowSelected.find('#txtItemId').val();  //
			arrayItemsAlreadySaved = jQuery.grep(arrayItemsAlreadySaved, function(value){
				return value != itemIdForDelete;
			});
			objectTableRowSelected.remove();
		}
	}
	
	function createRowItemTable(itemId, itemCodeName, stock, quantity){
		$('#tablaItems > tbody:last').append('<tr>\n\
												<td><span id="spaItemName'+itemId+'">'+itemCodeName+'</span><input type="hidden" value="'+itemId+'" id="txtItemId" ></td>\n\
												<td><span id="txtStock'+itemId+'">'+stock+'</span></td>\n\
												<td><span id="spaQuantity'+itemId+'">'+quantity+'</span></td>\n\
												<td class="columnItemsButtons">\n\
													<a class="btn btn-primary" href="#" id="btnEditItem'+itemId+'" title="Editar"><i class="icon-pencil icon-white"></i></a>\n\
													<a class="btn btn-danger" href="#" id="btnDeleteItem'+itemId+'" title="Eliminar"><i class="icon-trash icon-white"></i></a>\n\
												</td>\n\
											 </tr>');
	}
	
	function editItem(){
		var itemId = $('#cbxModalItems').val();
		var quantity = $('#txtModalQuantity').val();
		var itemCodeName = $('#cbxModalItems option:selected').text();
		var error = validateItem(itemCodeName, quantity, ''); 
		if(error == ''){
			$('#spaQuantity'+itemId).text(parseInt(quantity,10));
			$('#modalAddItem').modal('hide');
		}else{
			$('#boxModalValidateItem').html('<ul>'+error+'</ul>');
		}
	}
	
	function addItem(){
		var quantity = $('#txtModalQuantity').val();
		var itemId = $('#cbxModalItems').val();
		var itemCodeName = $('#cbxModalItems option:selected').text();
		var stock = $('#txtModalStock').val();
		var error = validateItem(itemCodeName, quantity, ''); 
		if(error == ''){
			createRowItemTable(itemId, itemCodeName, stock, parseInt(quantity,10));
			createEventClickEditItemButton(itemId);
			createEventClickDeleteItemButton(itemId);
			arrayItemsAlreadySaved.push(itemId);  //push into array of the added item
			$('#modalAddItem').modal('hide');
		}else{
			$('#boxModalValidateItem').html('<ul>'+error+'</ul>');
		}
	}
	
	//get all items for save a movement
	function getItemsDetails(){		
		var arrayItemsDetails = [];
		var itemId = '';
		var itemStock = '';
		var itemQuantity = '';
		var itemQuantityDocument = '';
		
		$('#tablaItems tbody tr').each(function(){		
			itemId = $(this).find('#txtItemId').val();
			itemStock = $(this).find('#txtStock'+itemId).text();
			itemQuantity = $(this).find('#spaQuantity'+itemId).text();
	
			if ($('#spaQuantityDocument'+itemId).length > 0){//existe
				itemQuantityDocument = $(this).find('#spaQuantityDocument'+itemId).text();
			}
			
			arrayItemsDetails.push({'inv_item_id':itemId, 'stock':itemStock, 'quantity':itemQuantity, 'quantity_document':itemQuantityDocument});
			
		});
		
		if(arrayItemsDetails.length == 0){  //For fix undefined index
			arrayItemsDetails = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
		}
		
		return arrayItemsDetails; 		
	}
	
	//show message of procesing for ajax
	function showProcessing(){
        $('#processing').text("Procesando...");
    }
	
	function saveAll(){
		var arrayItemsDetails = [];
		arrayItemsDetails = getItemsDetails();
		
		var error = validateBeforeSaveAll(arrayItemsDetails);
		if( error == ''){
				ajax_save_movement_in(arrayItemsDetails);
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
	}
	
	function updateMultipleStocks(arrayItemsStocks){
		var auxItemsStocks = [];
		for(var i=0; i<arrayItemsStocks.length; i++){
			auxItemsStocks = arrayItemsStocks[i].split('=>');//  item5=>9stock
			$('#txtStock'+auxItemsStocks[0]).text(auxItemsStocks[1]);  //update only if quantities are APPROVED
		}
	}
	
	function changeStateApproved(){
		if(confirm('Al APROBAR este documento ya no se podra hacer mas modificaciones. Esta seguro?')){
			var arrayItemsDetails = [];
			arrayItemsDetails = getItemsDetails();
			ajax_change_state_approved_movement_in(arrayItemsDetails);
		}
	}
	
	function changeStateCancelled(){
		if(confirm('Al CANCELAR este documento ya no sera valido y no habra marcha atras. Esta seguro?')){
			$('#cbxWarehouses').removeAttr('disabled');
			var arrayItemsDetails = [];
			arrayItemsDetails = getItemsDetails();
			ajax_change_state_cancelled_movement_in(arrayItemsDetails);
		}
	}
	
	//************************************************************************//
	//////////////////////////////////END-FUNCTIONS//////////////////////
	//************************************************************************//
	
	
	
	
	//************************************************************************//
	//////////////////////////////////BEGIN-CONTROLS EVENTS/////////////////////
	//************************************************************************//
	//Validate only numbers
	$('#txtModalQuantity').keydown(function(event) {
			validateOnlyNumbers(event);			
	});
	//Calendar script
	$('#txtDate').glDatePicker(
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
	$('#btnModalAddItem').click(function(){
		addItem();
		return false; //avoid page refresh
	});
	
	//edit an existing item quantity
	$('#btnModalEditItem').click(function(){
		editItem();
		return false; //avoid page refresh
	});
	
	//saves all movement
	$('#btnSaveAll').click(function(){
		saveAll();
		return false; //avoid page refresh
	});
	////////////////
	$('#btnApproveState').click(function(){
		//alert('Se aprueba entrada');
		changeStateApproved();
		return false;
	});
	$('#btnCancellState').click(function(){
		//alert('Se cancela entrada');
		changeStateCancelled();
		return false;
	});
	
	$('#cbxWarehouses').change(function(){
		validateWarehouse();
	});
	
	$('#txtDate').keypress(function(){return false;});
	$('#txtCode').keypress(function(){return false;});
	if ($('#txtDocumentCode').length > 0){//existe
		$('#txtDocumentCode').keypress(function(){return false;});
	}
	//************************************************************************//
	//////////////////////////////////END-CONTROLS EVENTS//////////////////////
	//************************************************************************//
	
	
	
	
	//************************************************************************//
	//////////////////////////////////BEGIN-AJAX FUNCTIONS//////////////////////
	////************************************************************************//
	
	
	
	//Save movement IN
	function ajax_save_movement_in(arrayItemsDetails){
		var movementType =1;
		var documentCode ='NINGUNO';
		if ($('#cbxMovementTypes').length > 0){//existe
			movementType = $('#cbxMovementTypes').val();
		}
		if ($('#txtDocumentCode').length > 0){//existe
			documentCode = $('#txtDocumentCode').val();
		}
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_save_movement_in",			
            data:{arrayItemsDetails: arrayItemsDetails 
				  ,movementId:$('#txtMovementIdHidden').val()
				  ,date:$('#txtDate').val()
				  ,warehouse:$('#cbxWarehouses').val()
				  ,movementType:movementType
				  ,description:$('#txtDescription').val()
				  ,documentCode:documentCode
			  },
            beforeSend: showProcessing(),
            success: function(data){
				$('#processing').text('');
				
				var arrayCatch = data.split('|');

				if(arrayCatch[0] == 'insertado'){ 
					$('#txtCode').val(arrayCatch[2]);
					$('#columnStateMovementIn').css('background-color','#F99C17');
					$('#columnStateMovementIn').text('Pendiente');
					$('#btnApproveState').show();
					$('#txtMovementIdHidden').val(arrayCatch[3]);
				}
				
				//update items stocks
				var arrayItemsStocks = arrayCatch[1].split(',');
				updateMultipleStocks(arrayItemsStocks);

				
					$('#boxMessage').html('<div class="alert alert-success">\n\
					<button type="button" class="close" data-dismiss="alert">&times;</button>Guardado con exito<div>');
				
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	
	function ajax_change_state_approved_movement_in(arrayItemsDetails){
		var movementType =1;
		var documentCode ='';
		if ($('#cbxMovementTypes').length > 0){//existe
			movementType = $('#cbxMovementTypes').val();
		}
		if ($('#txtDocumentCode').length > 0){//existe
			documentCode = $('#txtDocumentCode').val();
		}
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_change_state_approved_movement_in",			
            data:{arrayItemsDetails: arrayItemsDetails 
				  ,movementId:$('#txtMovementIdHidden').val()
				  ,date:$('#txtDate').val()
				  ,warehouse:$('#cbxWarehouses').val()
				  ,movementType:movementType
				  ,description:$('#txtDescription').val()
				  ,documentCode:documentCode
			  },
            beforeSend: showProcessing(),
            success: function(data){
				var arrayItemsStocks = data.split(',');
				updateMultipleStocks(arrayItemsStocks);
				$('#columnStateMovementIn').css('background-color','#54AA54');
				$('#columnStateMovementIn').text('Aprobado');
				$('#btnApproveState').hide();
				$('#btnCancellState').show();
				$('#btnSaveAll').hide();
				$('#btnAddMovementType').hide();
				if ($('#btnAddItem').length > 0){//existe
					$('#btnAddItem').hide();
				}
				$('.columnItemsButtons').hide();
				
				if ($('#txtDocumentCode').length > 0){//existe
					$('#txtDocumentCode').attr('disabled','disabled');
				}
				$('#txtDate').attr('disabled','disabled');
				$('#txtCode').attr('disabled','disabled');
				$('#cbxWarehouses').attr('disabled','disabled');
				if ($('#cbxMovementTypes').length > 0){//existe
					$('#cbxMovementTypes').attr('disabled','disabled');
				}
				$('#txtDescription').attr('disabled','disabled');
				
				$('#processing').text('');
				$('#boxMessage').html('<div class="alert alert-success">\n\
				<button type="button" class="close" data-dismiss="alert">&times;</button>Aprobado con exito<div>');
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	function ajax_change_state_cancelled_movement_in(arrayItemsDetails){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_change_state_cancelled_movement_in",			
            data:{arrayItemsDetails: arrayItemsDetails 
				  ,movementId:$('#txtMovementIdHidden').val()
			  },
            beforeSend:showProcessing(),
            success: function(data){
				var arrayCatch = data.split('|');
				var arrayItemsStocks = arrayCatch[1].split(',');
				if(arrayCatch[0] == 'cancelado'){
					updateMultipleStocks(arrayItemsStocks);
					$('#columnStateMovementIn').css('background-color','#BD362F');
					$('#columnStateMovementIn').text('Cancelado');
					$('#btnCancellState').hide();
					$('#boxMessage').html('<div class="alert alert-success">\n\
					<button type="button" class="close" data-dismiss="alert">&times;</button>Cancelado con exito<div>');
				}
				if(arrayCatch[0] == 'error'){
					var error = validateCancelMovementIn(arrayItemsStocks);
					$('#boxMessage').html('<div class="alert alert-error">\n\
					<button type="button" class="close" data-dismiss="alert">&times;</button><p>No se pudo cancelar debido a falta de stock:</p><ul>'+error+'</ul><div>');
				}
				$('#processing').text('');
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	
	
	
	//Get items and stock for the fist item when inititates modal
	function ajax_initiate_modal_add_item_in(itemsAlreadySaved){
		 $.ajax({
            type:"POST",
            url:moduleController + "ajax_initiate_modal_add_item_in",			
            data:{itemsAlreadySaved: itemsAlreadySaved, warehouse: $('#cbxWarehouses').val()},
            beforeSend: showProcessing(),
            success: function(data){
				$('#processing').text('');
				//$('#itemSaveError').text('');
				$('#boxModalIntiateItemStock').html(data);
				$('#txtModalQuantity').val('');  
				initiateModal()
				$('#cbxModalItems').bind("change",function(){ //must be binded 'cause dropbox is loaded by a previous ajax'
					ajax_update_stock();
				});
				$('#txtModalStock').keypress(function(){
					return false;
				});
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	//Update one stock value
	function ajax_update_stock(){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_update_stock",			
            data:{warehouse: $('#cbxWarehouses').val(), item: $('#cbxModalItems').val()},
            beforeSend: showProcessing(),
            success: function(data){
				$('#processing').text("");
				$('#boxModalStock').html(data);
				$('#txtModalStock').bind("keypress",function(){ //must be binded 'cause input is re-loaded by a previous ajax'
					return false;
				});
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	//Update one stock value
	function ajax_update_multiple_stocks(arrayItemsDetails){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_update_multiple_stocks",			
            data:{warehouse: $('#cbxWarehouses').val(), arrayItemsDetails: arrayItemsDetails},
            beforeSend: showProcessing(),
            success: function(data){
				var arrayItemsStocks = data.split(',');
				updateMultipleStocks(arrayItemsStocks);
				$('#processing').text('');
				//$('#boxModalStock').html(data);
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	
	
	
	
	//************************************************************************//
	//////////////////////////////////END-AJAX FUNCTIONS////////////////////////
	//************************************************************************//
	
//END SCRIPT	
});
