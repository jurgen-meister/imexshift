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
	$('#cbxWarehouses').select2(); // to create advanced select or combobox
	
	clearFieldsForFirefox();

	//************************************************************************//
	//////////////////////////////////BEGIN-FUNCTIONS////////////////
	//************************************************************************//
	//firefox doesn't clear by himself the fields when there is a refresh in a new form
	function clearFieldsForFirefox(){
		var urlController = ['save_in', 'save_out', 'save_warehouses_transfer'];
		for(var i=0;i < urlController.length; i++ ){
			if(arr[3] == urlController[i]){
				if(arr[4] == null){
					$('input').val('');//empty all inputs including hidden thks jquery 
					$('textarea').val('');
				}
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
			//That was used to validate item quantity is greater than the quantity send in Purchase IN
			/*
			if ($('#txtModalQuantityDocument').length > 0){//existe
				if(parseInt(quantity, 10) > $('#txtModalQuantityDocument').val()){
					error+='<li>La "Cantidad" de entrada no puede ser mayor a la "Compra"</li>'; 
				}
			}
			*/
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

		if ($('#cbxWarehouses2').length > 0){//existe
			if($('#cbxWarehouses').val() == $('#cbxWarehouses2').val()){
				error+='<li> No se puede hacer una transferencia al mismo almacen </li>';
			}
		}

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
	
	
	function changeLabelDocumentState(state){
		switch(state)
		{
			case 'PENDANT':
				$('#documentState').addClass('label-warning');
				$('#documentState').text('PENDIENTE');
				break;
			case 'APPROVED':
				$('#documentState').removeClass('label-warning').addClass('label-success');
				$('#documentState').text('APROBADO');
				break;
			case 'CANCELLED':
				$('#documentState').removeClass('label-success').addClass('label-important');
				$('#documentState').text('CANCELADO');
				break;
		}
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

	function updateItemsWarehouseStocks(warehouse, controlName){
		var arrayItemsDetails = [];
		arrayItemsDetails = getItemsDetails();

		if(arrayItemsDetails[0] != 0){
			ajax_update_multiple_stocks(arrayItemsDetails, warehouse, controlName);
			alert('Se cambio de "Almacen", se actualizara los "Stocks" de los "Items"');
		}
	}

	function validateBeforeMoveOut(arrayItemsStocksErrors, controlName){
		var error = '';
		var arrItemsStatusStock = [];
		var arrStatusStock =[];
		var itemId = '';
		var status = '';
		var stock = '';
		for(var i=0; i<arrayItemsStocksErrors.length; i++){
			arrItemsStatusStock = arrayItemsStocksErrors[i].split('=>');//  item=>status:stock
			itemId = arrItemsStatusStock[0];
			if(itemId != ''){//if exist itemId in the array splited because a,b,'' because last field is empty
				arrStatusStock = arrItemsStatusStock[1].split(':');//status:stock
				status = arrStatusStock[0];
				stock = arrStatusStock[1];
				if(status == 'error'){ 
						error+='<li>'+$('#spaItemName'+itemId).text()+': la "Cantidad = '+$('#spaQuantity'+itemId).text()+'" es mayor su "Stock = '+stock+'" </li>';	
				}
				$('#'+controlName+itemId).text(stock);
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
		$('#txtModalStock').val(objectTableRowSelected.find('#spaStock'+itemIdForEdit).text());
		$('#txtModalStock').keypress(function(){return false;});
		if ($('#txtModalQuantityDocument').length > 0){//existe
			$('#txtModalQuantityDocument').val(objectTableRowSelected.find('#spaQuantityDocument'+itemIdForEdit).text());
			$('#txtModalQuantityDocument').keypress(function(){return false;});
		}
		if($('#cbxWarehouses2').length > 0){
			$('#txtModalStock2').val(objectTableRowSelected.find('#spaStock2-'+itemIdForEdit).text());
			$('#txtModalStock2').keypress(function(){return false;});
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

	function createRowItemTable(itemId, itemCodeName, stock, quantity, stock2){
		var row = '<tr>';
		row +='<td><span id="spaItemName'+itemId+'">'+itemCodeName+'</span><input type="hidden" value="'+itemId+'" id="txtItemId" ></td>';
		row +='<td><span id="spaStock'+itemId+'">'+stock+'</span></td>';
		if(stock2 != ''){
			row +='<td><span id="spaStock2-'+itemId+'">'+stock2+'</span></td>';
		}
		row +='<td><span id="spaQuantity'+itemId+'">'+quantity+'</span></td>';
		row +='<td class="columnItemsButtons">';
		row +='<a class="btn btn-primary" href="#" id="btnEditItem'+itemId+'" title="Editar"><i class="icon-pencil icon-white"></i></a> ';
		row +='<a class="btn btn-danger" href="#" id="btnDeleteItem'+itemId+'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
		row +='</td>';
		row +='</tr>'
		$('#tablaItems > tbody:last').append(row);
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
		var stock2 = '';
		if(arr[3] == 'save_warehouses_transfer'){
			stock2 = $('#txtModalStock2').val();
		}
		var error = validateItem(itemCodeName, quantity, ''); 
		if(error == ''){
			createRowItemTable(itemId, itemCodeName, stock, parseInt(quantity,10), stock2);
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
		var itemStock2 = '';
		var itemQuantity = '';
		var itemQuantityDocument = '';

		$('#tablaItems tbody tr').each(function(){		
			itemId = $(this).find('#txtItemId').val();
			itemStock = $(this).find('#spaStock'+itemId).text();

			if ($('#spaStock2-'+itemId).length > 0){//exists warehouse_transfer
				itemStock2 = $(this).find('#spaStock2-'+itemId).text();
			}

			itemQuantity = $(this).find('#spaQuantity'+itemId).text();

			if ($('#spaQuantityDocument'+itemId).length > 0){//exists
				itemQuantityDocument = $(this).find('#spaQuantityDocument'+itemId).text();
			}

			arrayItemsDetails.push({'inv_item_id':itemId, 'stock':itemStock, 'quantity':itemQuantity, 'quantity_document':itemQuantityDocument, 'stock2':itemStock2});

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
	
	function saveAll(){
		var arrayItemsDetails = [];
		arrayItemsDetails = getItemsDetails();

		var error = validateBeforeSaveAll(arrayItemsDetails);
		if( error == ''){
			if(arr[3] == 'save_in' || arr[3] == 'save_purchase_in'){
				ajax_save_movement_in(arrayItemsDetails);
			}
			if(arr[3] == 'save_out' || arr[3] == 'save_sale_out'){
				ajax_save_movement_out(arrayItemsDetails);
			}
			if(arr[3] == 'save_warehouses_transfer'){
				ajax_save_warehouses_transfer(arrayItemsDetails);
			}
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
	}

	function updateMultipleStocks(arrayItemsStocks, controlName){
		var auxItemsStocks = [];
		for(var i=0; i<arrayItemsStocks.length; i++){
			auxItemsStocks = arrayItemsStocks[i].split('=>');//  item5=>9stock
			$('#'+controlName+auxItemsStocks[0]).text(auxItemsStocks[1]);  //update only if quantities are APPROVED
		}
	}

	function changeStateApproved(){
		if(confirm('Al APROBAR este documento ya no se podra hacer mas modificaciones. Esta seguro?')){
			var arrayItemsDetails = [];
			arrayItemsDetails = getItemsDetails();
			var error = validateBeforeSaveAll(arrayItemsDetails);
			if( error == ''){
				if(arr[3] == 'save_in' || arr[3] == 'save_purchase_in'){
					ajax_change_state_approved_movement_in(arrayItemsDetails);
				}
				if(arr[3]=='save_out' || arr[3] == 'save_sale_out'){
					ajax_change_state_approved_movement_out(arrayItemsDetails);
				}
				if(arr[3] == 'save_warehouses_transfer'){
					ajax_change_state_approved_warehouses_transfer(arrayItemsDetails);
				}
			}else{
				$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
			}
			
		}
	}

	function changeStateCancelled(){
		if(confirm('Al CANCELAR este documento ya no sera valido y no habra marcha atras. Esta seguro?')){
			//$('#cbxWarehouses').removeAttr('disabled');
			var arrayItemsDetails = [];
			arrayItemsDetails = getItemsDetails();
			if(arr[3] == 'save_in' || arr[3] == 'save_purchase_in'){
				ajax_change_state_cancelled_movement_in(arrayItemsDetails);
			}
			if(arr[3]=='save_out' || arr[3] == 'save_sale_out'){
				ajax_change_state_cancelled_movement_out(arrayItemsDetails);
			}
			if(arr[3] == 'save_warehouses_transfer'){
				ajax_change_state_cancelled_warehouses_transfer(arrayItemsDetails);
			}
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
	/*
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
	*/
  
   $("#txtDate").datepicker({
	  showButtonPanel: true
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
		//validateWarehouse();
		var warehouse=$('#cbxWarehouses').val();
		var controlName ='spaStock';
		updateItemsWarehouseStocks(warehouse, controlName)
	});

	$('#cbxWarehouses2').change(function(){
		//validateWarehouse();
		var warehouse=$('#cbxWarehouses2').val();
		var controlName ='spaStock2-';
		updateItemsWarehouseStocks(warehouse, controlName)
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
		var movementType =1;//Purchase
		var documentCode ='NO';
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
				var arrayCatch = data.split('|');

				if(arrayCatch[0] == 'insertado'){ 
					$('#txtCode').val(arrayCatch[2]);
					//$('#columnStateMovementIn').css('background-color','#F99C17');
					//$('#columnStateMovementIn').text('Pendiente');	
					changeLabelDocumentState('PENDANT'); //#UNICORN
					
					$('#btnApproveState').show();
					$('#txtMovementIdHidden').val(arrayCatch[3]);
				}				
				
				//update items stocks
				var arrayItemsStocks = arrayCatch[1].split(',');
				updateMultipleStocks(arrayItemsStocks, 'spaStock');
				$('#btnPrint').show();
				showGrowlMessage('ok', 'Cambios guardados.');
				//$('#boxMessage').html('<div class="alert alert-success">\n\
				//<button type="button" class="close" data-dismiss="alert">&times;</button>Guardado con exito<div>');
				$('#processing').text('');
			},
			error:function(data){
				//$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text('');
			}
        });
	}
	
	//Save movement OUT
	function ajax_save_movement_out(arrayItemsDetails){
		var movementType =2; //Sale
		var documentCode ='NO';
		if ($('#cbxMovementTypes').length > 0){//existe
			movementType = $('#cbxMovementTypes').val();
		}
		if ($('#txtDocumentCode').length > 0){//existe
			documentCode = $('#txtDocumentCode').val();
		}
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_save_movement_out",			
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

				var arrayCatch = data.split('|');

				if(arrayCatch[0] == 'insertado'){ 
					$('#txtCode').val(arrayCatch[2]);
					//$('#columnStateMovementIn').css('background-color','#F99C17');
					//$('#columnStateMovementIn').text('Pendiente');
					changeLabelDocumentState('PENDANT');//#UNICORN
					
					$('#btnApproveState').show();
					$('#txtMovementIdHidden').val(arrayCatch[3]);
				}

				//update items stocks
				var arrayItemsStocks = arrayCatch[1].split(',');
				updateMultipleStocks(arrayItemsStocks, 'spaStock');

				$('#btnPrint').show();	
				//$('#boxMessage').html('<div class="alert alert-success">\n\
				//<button type="button" class="close" data-dismiss="alert">&times;</button>Guardado con exito<div>');
				showGrowlMessage('ok', 'Cambios guardados.');
				$('#processing').text('');
			},
			error:function(data){
				//$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text('');
			}
        });
	}

	//Save movement Warehouse transfer
	function ajax_save_warehouses_transfer(arrayItemsDetails){
			$.ajax({
            type:"POST",
            url:moduleController + "ajax_save_warehouses_transfer",			
            data:{arrayItemsDetails: arrayItemsDetails 
				  ,movementId:$('#txtMovementIdHidden').val()
				  ,date:$('#txtDate').val()
				  ,warehouseOut:$('#cbxWarehouses').val()
				  ,warehouseIn:$('#cbxWarehouses2').val()
				  ,description:$('#txtDescription').val()
				  ,documentCode:$('#txtDocumentCode').val()//transfer code for both parts
			  },
            beforeSend: showProcessing(),
            success: function(data){
				var arrayCatch = data.split('|');
				var arrayItemsStocks = [];
				if(arrayCatch[0] == 'insertado'){ 
					$('#txtDocumentCode').val(arrayCatch[2]);
					//$('#columnStateMovementIn').css('background-color','#F99C17');
					//$('#columnStateMovementIn').text('Pendiente');
					changeLabelDocumentState('PENDANT');//#UNICORN
					
					$('#btnApproveState').show();
					$('#txtMovementIdHidden').val(arrayCatch[3]);
					//update items stocks when transfer
					arrayItemsStocks = arrayCatch[4].split(',');
					updateMultipleStocks(arrayItemsStocks, 'spaStock2-');
				}

				//update items stocks
				arrayItemsStocks = arrayCatch[1].split(',');
				updateMultipleStocks(arrayItemsStocks, 'spaStock');

				if(arrayCatch[0] == 'modificado'){ 
					//update items stocks when transfer
					arrayItemsStocks = arrayCatch[2].split(',');
					updateMultipleStocks(arrayItemsStocks, 'spaStock2-');
				}
				$('#btnPrint').show();
				//$('#boxMessage').html('<div class="alert alert-success">\n\
				//<button type="button" class="close" data-dismiss="alert">&times;</button>Guardado con exito<div>');
				showGrowlMessage('ok', 'Cambios guardados.');
				$('#processing').text('');

			},
			error:function(data){
				//$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text('');
			}
        });
	}

	function ajax_change_state_approved_warehouses_transfer(arrayItemsDetails){//almoste the same as movement out

		$.ajax({
            type:"POST",
            url:moduleController + "ajax_change_state_approved_warehouses_transfer",			
            data:{arrayItemsDetails: arrayItemsDetails 
				  ,movementId:$('#txtMovementIdHidden').val()
				  ,date:$('#txtDate').val()
				  ,warehouseOut:$('#cbxWarehouses').val()
				  ,warehouseIn:$('#cbxWarehouses2').val()
				  ,description:$('#txtDescription').val()
				  ,documentCode:$('#txtDocumentCode').val()//transfer code for both parts
			  },
            beforeSend: showProcessing(),
            success: function(data){
				var arrayCatch = data.split('|');
				var arrayItemsStocks = arrayCatch[1].split(',');
				if(arrayCatch[0] == 'aprobado'){
					updateMultipleStocks(arrayItemsStocks, 'spaStock');
					arrayItemsStocks = arrayCatch[2].split(',')
					updateMultipleStocks(arrayItemsStocks, 'spaStock2-');
					//$('#columnStateMovementIn').css('background-color','#54AA54');
					//$('#columnStateMovementIn').text('Aprobado');
					changeLabelDocumentState('APPROVED');//#UNICORN
					
					$('#btnApproveState').hide();
					$('#btnCancellState').show();
					$('#btnSaveAll').hide();
					//$('#btnAddMovementType').hide();
					if ($('#btnAddItem').length > 0){//existe
						$('#btnAddItem').hide();
					}
					$('.columnItemsButtons').hide();

					//if ($('#txtDocumentCode').length > 0){//existe
						$('#txtDocumentCode').attr('disabled','disabled');
					//}
					$('#txtDate').attr('disabled','disabled');
					$('#cbxWarehouses').attr('disabled','disabled');
					$('#cbxWarehouses2').attr('disabled','disabled');
					if ($('#cbxMovementTypes').length > 0){//existe
						$('#cbxMovementTypes').attr('disabled','disabled');
					}
					$('#txtDescription').attr('disabled','disabled');

					//$('#processing').text('');
					//$('#boxMessage').html('<div class="alert alert-success">\n\
					//<button type="button" class="close" data-dismiss="alert">&times;</button>Aprobado con exito<div>');
					showGrowlMessage('ok', 'Transferencia aprobada.');
				}
				if(arrayCatch[0] == 'error'){
					var error = validateBeforeMoveOut(arrayItemsStocks, 'spaStock');
					arrayItemsStocks = arrayCatch[2].split(',')
					updateMultipleStocks(arrayItemsStocks, 'spaStock2-');
					//$('#boxMessage').html('<div class="alert alert-error">\n\
					//<button type="button" class="close" data-dismiss="alert">&times;</button><p>No se pudo Aprobar el traspaso porque falta "Stock" para la "Salida" del "Almacen Origen":</p><ul>'+error+'</ul><div>');
					showGrowlMessage('error', '<p>No se pudo Aprobar el traspaso porque falta "Stock" para la "Salida" del "Almacen Origen":</p><ul>'+error+'</ul>', true);
				}
				$('#processing').text('');

			},
			error:function(data){
				//$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text('');
			}
        });
	}

	function ajax_change_state_cancelled_warehouses_transfer(arrayItemsDetails){//almoste the same as movement out

		$.ajax({
            type:"POST",
            url:moduleController + "ajax_change_state_cancelled_warehouses_transfer",			
            data:{arrayItemsDetails: arrayItemsDetails 
				  ,movementId:$('#txtMovementIdHidden').val()
				  ,documentCode:$('#txtDocumentCode').val()//transfer code for both parts
			  },
            beforeSend: showProcessing(),
            success: function(data){
				var arrayCatch = data.split('|');
				var arrayItemsStocks = arrayCatch[1].split(',');//in, destino
				if(arrayCatch[0] == 'cancelado'){
					updateMultipleStocks(arrayItemsStocks, 'spaStock2-');//in, destino
					arrayItemsStocks = arrayCatch[2].split(',');
					updateMultipleStocks(arrayItemsStocks, 'spaStock');//out, origen
					//$('#columnStateMovementIn').css('background-color','#BD362F');
					//$('#columnStateMovementIn').text('Cancelado');
					changeLabelDocumentState('CANCELLED');//#UNICORN
					
					$('#btnCancellState').hide();
					//$('#boxMessage').html('<div class="alert alert-success">\n\
					//<button type="button" class="close" data-dismiss="alert">&times;</button>Cancelado con exito<div>');
					showGrowlMessage('ok', 'Transferencia cancelada.');
				}
				if(arrayCatch[0] == 'error'){
					var error = validateBeforeMoveOut(arrayItemsStocks, 'spaStock2-');//in, destino
					arrayItemsStocks = arrayCatch[2].split(',')//out, origen
					updateMultipleStocks(arrayItemsStocks, 'spaStock');
					//$('#boxMessage').html('<div class="alert alert-error">\n\
					//<button type="button" class="close" data-dismiss="alert">&times;</button><p>No se pudo Cancelar el traspaso porque falta "Stock" para la "Salida" del "Almacen Destino":</p><ul>'+error+'</ul><div>');
					showGrowlMessage('error', '<p>No se pudo Cancelar el traspaso porque falta "Stock" para la "Salida" del "Almacen Destino":</p><ul>'+error+'</ul>', true);
				}
				$('#processing').text('');

			},
			error:function(data){
				//$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text('');
			}
        });
	}

	function ajax_change_state_approved_movement_in(arrayItemsDetails){
		var movementType =1;//Purchase
		var documentCode ='NO';
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
				var arrayCatch = data.split('|');
				var arrayItemsStocks = arrayCatch[1].split(',');
				if(arrayCatch[0] == 'aprobado'){
					updateMultipleStocks(arrayItemsStocks, 'spaStock');
					//$('#columnStateMovementIn').css('background-color','#54AA54');
					//$('#columnStateMovementIn').text('Aprobado');
					changeLabelDocumentState('APPROVED');//#UNICORN
					
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
					//$('#boxMessage').html('<div class="alert alert-success">\n\
					//<button type="button" class="close" data-dismiss="alert">&times;</button>Aprobado con exito<div>');
					showGrowlMessage('ok', 'Entrada aprobada.');
				}
				$('#processing').text('');
			},
			error:function(data){
				//$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text('');
			}
        });
	}

	function ajax_change_state_approved_movement_out(arrayItemsDetails){
		var movementType =2;//Sale
		var documentCode ='NO';
		if ($('#cbxMovementTypes').length > 0){//existe
			movementType = $('#cbxMovementTypes').val();
		}
		if ($('#txtDocumentCode').length > 0){//existe
			documentCode = $('#txtDocumentCode').val();
		}
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_change_state_approved_movement_out",			
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
				var arrayCatch = data.split('|');
				var arrayItemsStocks = arrayCatch[1].split(',');
				if(arrayCatch[0] == 'aprobado'){
					updateMultipleStocks(arrayItemsStocks, 'spaStock');
					//$('#columnStateMovementIn').css('background-color','#54AA54');
					//$('#columnStateMovementIn').text('Aprobado');
					changeLabelDocumentState('APPROVED');//#UNICORN
					
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
					//$('#boxMessage').html('<div class="alert alert-success">\n\
					//<button type="button" class="close" data-dismiss="alert">&times;</button>Aprobado con exito<div>');
					showGrowlMessage('ok', 'Salida aprobada.');
				}
				if(arrayCatch[0] == 'error'){
					var error = validateBeforeMoveOut(arrayItemsStocks, 'spaStock');
					//$('#boxMessage').html('<div class="alert alert-error">\n\
					//<button type="button" class="close" data-dismiss="alert">&times;</button><p>No se pudo "Aprobar" la salida debido a falta de stock:</p><ul>'+error+'</ul><div>');
					showGrowlMessage('error', '<p>No se pudo "Aprobar" la salida debido a falta de stock:</p><ul>'+error+'</ul>', true);
				}
				$('#processing').text('');

			},
			error:function(data){
				//$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				showGrowlMessage('error', 'Vuelva a intentarlo.');
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
					updateMultipleStocks(arrayItemsStocks, 'spaStock');
					//$('#columnStateMovementIn').css('background-color','#BD362F');
					//$('#columnStateMovementIn').text('Cancelado');
					changeLabelDocumentState('CANCELLED');//#UNICORN
					
					$('#btnCancellState').hide();
					//$('#boxMessage').html('<div class="alert alert-success">\n\
					//<button type="button" class="close" data-dismiss="alert">&times;</button>Cancelado con exito<div>');
					showGrowlMessage('ok', 'Entrada cancelada.');
				}
				if(arrayCatch[0] == 'error'){
					var error = validateBeforeMoveOut(arrayItemsStocks, 'spaStock');
					//$('#boxMessage').html('<div class="alert alert-error">\n\
					//<button type="button" class="close" data-dismiss="alert">&times;</button><p>No se pudo "Cancelar" la entrada debido a falta de stock:</p><ul>'+error+'</ul><div>');
					showGrowlMessage('error', '<p>No se pudo "Cancelar" la entrada debido a falta de stock:</p><ul>'+error+'</ul>', true);
				}
				$('#processing').text('');
			},
			error:function(data){
				//$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text('');
			}
        });
	}

	function ajax_change_state_cancelled_movement_out(arrayItemsDetails){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_change_state_cancelled_movement_out",			
            data:{arrayItemsDetails: arrayItemsDetails 
				  ,movementId:$('#txtMovementIdHidden').val()
			  },
            beforeSend:showProcessing(),
            success: function(data){
				var arrayCatch = data.split('|');
				var arrayItemsStocks = arrayCatch[1].split(',');
				if(arrayCatch[0] == 'cancelado'){
					updateMultipleStocks(arrayItemsStocks, 'spaStock');
					//$('#columnStateMovementIn').css('background-color','#BD362F');
					//$('#columnStateMovementIn').text('Cancelado');
					changeLabelDocumentState('CANCELLED');//#UNICORN
					
					$('#btnCancellState').hide();
					//$('#boxMessage').html('<div class="alert alert-success">\n\
					//<button type="button" class="close" data-dismiss="alert">&times;</button>Cancelado con exito<div>');
					showGrowlMessage('ok', 'Salida cancelada.');
				}
				$('#processing').text('');
			},
			error:function(data){
				//$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text('');
			}
        });
	}


	//Get items and stock for the fist item when inititates modal
	function ajax_initiate_modal_add_item_in(itemsAlreadySaved){
		var transfer = '';
		var warehouse2 = '';
		if(arr[3] == 'save_warehouses_transfer'){
			transfer = 'warehouses_transfer';
			warehouse2 = $('#cbxWarehouses2').val();
		}
		 $.ajax({
            type:"POST",
            url:moduleController + "ajax_initiate_modal_add_item_in",			
            data:{itemsAlreadySaved: itemsAlreadySaved, warehouse: $('#cbxWarehouses').val(), transfer:transfer, warehouse2:warehouse2},
            beforeSend: showProcessing(),
            success: function(data){
				$('#processing').text('');
				//$('#itemSaveError').text('');
				$('#boxModalIntiateItemStock').html(data);
				$('#txtModalQuantity').val('');  
				initiateModal();
				//if($('#txtModalStock').length > 0){
				
				$('#cbxModalItems').bind("change",function(){ //must be binded 'cause dropbox is loaded by a previous ajax'
					ajax_update_stock_modal();
				});
				
				$('#txtModalStock').keypress(function(){return false;});
				if($('#cbxWarehouses2').length > 0){
					$('#txtModalStock2').keypress(function(){return false;});	
				}
				//}
				//$('#cbxModalItems').select2();

			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}

	//Update one stock value
	function ajax_update_stock_modal(){
		var transfer = '';
		var warehouse2 = '';
		if(arr[3] == 'save_warehouses_transfer'){
			transfer = 'warehouses_transfer';
			warehouse2 = $('#cbxWarehouses2').val();
		}
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_update_stock_modal",			
            data:{warehouse: $('#cbxWarehouses').val(), item: $('#cbxModalItems').val(), transfer:transfer, warehouse2:warehouse2},
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
	function ajax_update_multiple_stocks(arrayItemsDetails, warehouse, controlName){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_update_multiple_stocks",			
            data:{warehouse: warehouse, arrayItemsDetails: arrayItemsDetails},
            beforeSend: showProcessing(),
            success: function(data){
				var arrayItemsStocks = data.split(',');
				updateMultipleStocks(arrayItemsStocks, controlName);
				$('#processing').text('');
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