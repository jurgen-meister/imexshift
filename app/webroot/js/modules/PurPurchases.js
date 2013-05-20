$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation

	var arrayItemsAlreadySaved = []; 
	startEventsWhenExistsItems();
	
	var arrayCostsAlreadySaved = []; 
	startEventsWhenExistsCosts();
	
	clearFieldsForFirefox();


	
	
	
	
	//************************************************************************//
	//////////////////////////////////BEGIN-FUNCTIONS////////////////
	//************************************************************************//
	//firefox doesn't clear by himself the fields when there is a refresh in a new form
	function clearFieldsForFirefox(){
/*ch*/		var urlController = ['save_order', 'save_invoice'];
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
	
	function startEventsWhenExistsCosts(){		/*STANDBY*/
		var arrayAux = [];
		arrayAux = getCostsDetails();
		if(arrayAux[0] != 0){
			for(var i=0; i< arrayAux.length; i++){
				 arrayCostsAlreadySaved[i] = arrayAux[i]['inv_price_type_id'];
				 createEventClickEditCostButton(arrayAux[i]['inv_price_type_id']);
				 createEventClickDeleteCostButton(arrayAux[i]['inv_price_type_id']);			 
			}
		}
		/*else{
			alert('esta vacio');
		}*/
	}
	
	//validates before add item quantity
	function validateItem(item, quantity, price/*, documentQuantity*/){
		var error = '';
		if(quantity == ''){
			error+='<li>El campo "Cantidad" no puede estar vacio</li>'; 
		}else{
			if(parseInt(quantity, 10) == 0){
				
				error+='<li>El campo "Cantidad" no puede ser cero</li>'; 
			}
			
//			if ($('#txtModalQuantityDocument').length > 0){//existe
//				if(parseInt(quantity, 10) > $('#txtModalQuantityDocument').val()){
//					error+='<li>La "Cantidad" de entrada no puede ser mayor a la "Compra"</li>'; 
//				}
//			}
		}
		
		if(price == ''){
			error+='<li>El campo "P/U" no puede estar vacio</li>'; 
		}else{
//o si puede ser cero el precio?			
			if(parseFloat(price).toFixed(2) == 0){
				
				error+='<li>El campo "P/U" no puede ser cero</li>'; 
			}
		}
		
		if(item == ''){error+='<li>El campo "Item" no puede estar vacio</li>';}
		
		return error;
	}
	
	function validateCost(cost, amount/*, documentQuantity*/){
		var error = '';
//		if(quantity == ''){
//			error+='<li>El campo "Cantidad" no puede estar vacio</li>'; 
//		}else{
//			if(parseInt(quantity, 10) == 0){
//				
//				error+='<li>El campo "Cantidad" no puede ser cero</li>'; 
//			}
//			
////			if ($('#txtModalQuantityDocument').length > 0){//existe
////				if(parseInt(quantity, 10) > $('#txtModalQuantityDocument').val()){
////					error+='<li>La "Cantidad" de entrada no puede ser mayor a la "Compra"</li>'; 
////				}
////			}
//		}
		
		if(amount == ''){
			error+='<li>El campo "Monto" no puede estar vacio</li>'; 
		}else{
//o si puede ser cero el precio?			
			if(parseFloat(amount).toFixed(2) == 0){
				
				error+='<li>El campo "Monto" no puede ser cero</li>'; 
			}
		}
		
		if(cost == ''){error+='<li>El campo "Costo" no puede estar vacio</li>';}
		
		return error;
	}
	
	function validateBeforeSaveAll(arrayItemsDetails){
		var error = '';
		var date = $('#txtDate').val();
		if(date == ''){	error+='<li> El campo "Fecha" no puede estar vacio </li>'; }
		if(arrayItemsDetails[0] == 0){error+='<li> Debe existir al menos 1 "Item" </li>';}
//		if(arr[3] == 'save_invoice'){
//				if(arrayCostsDetails[0] == 0){error+='<li> Debe existir al menos 1 "Costo" </li>';}
//			}
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
			case 'ORDER_PENDANT':
				$('#documentState').addClass('label-warning');
				$('#documentState').text('ORDEN PENDIENTE');
				break;
			case 'ORDER_APPROVED':
				$('#documentState').removeClass('label-warning').addClass('label-success');
				$('#documentState').text('ORDEN APROBADA');
				break;
			case 'ORDER_CANCELLED':
				$('#documentState').removeClass('label-success').addClass('label-important');
				$('#documentState').text('ORDEN CANCELADA');
				break;
				case 'INVOICE_PENDANT':
				$('#documentState').addClass('label-warning');
				$('#documentState').text('FACTURA PENDIENTE');
				break;
			case 'INVOICE_APPROVED':
				$('#documentState').removeClass('label-warning').addClass('label-success');
				$('#documentState').text('FACTURA APROBADA');
				break;
			case 'INVOICE_CANCELLED':
				$('#documentState').removeClass('label-success').addClass('label-important');
				$('#documentState').text('FACTURA CANCELADA');
				break;
		}
	}
	
	function initiateModal(){
		$('#modalAddItem').modal({
					show: 'true',
					backdrop:'static'
		});
	}
	
	function initiateModalCost(){
		$('#modalAddCost').modal({
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
		//mostrar boton guardar(new) del modal
		$('#btnModalAddItem').show();
		//ocultar boton guardar(edit) del modal
		$('#btnModalEditItem').hide();
		$('#boxModalValidateItem').html('');//clear error message
		ajax_initiate_modal_add_item_in(arrayItemsAlreadySaved);
	}
	
	function initiateModalAddCost(){
		if(arrayCostsAlreadySaved.length == 0){  //For fix undefined index
			arrayCostsAlreadySaved = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
		}
		$('#btnModalAddCost').show();
		$('#btnModalEditCost').hide();
		$('#boxModalValidateCost').html('');//clear error message
		ajax_initiate_modal_add_cost(arrayCostsAlreadySaved);
	}
	
	function initiateModalEditItem(objectTableRowSelected){
		var itemIdForEdit = objectTableRowSelected.find('#txtItemId').val();  //
		$('#btnModalAddItem').hide();
		$('#btnModalEditItem').show();
		$('#boxModalValidateItem').html('');//clear error message
		$('#txtModalQuantity').val(objectTableRowSelected.find('#spaQuantity'+itemIdForEdit).text());
		$('#txtModalPrice').val(objectTableRowSelected.find('#spaPrice'+itemIdForEdit).text());
//		$('#txtModalPrice').keypress(function(){return false;});
/*		if ($('#txtModalQuantityDocument').length > 0){//existe
			$('#txtModalQuantityDocument').val(objectTableRowSelected.find('#spaQuantityDocument'+itemIdForEdit).text());
			$('#txtModalQuantityDocument').keypress(function(){return false;});
		}
*/	/*	if($('#cbxWarehouses2').length > 0){
			$('#txtModalStock2').val(objectTableRowSelected.find('#spaStock2-'+itemIdForEdit).text());
			$('#txtModalStock2').keypress(function(){return false;});
		}*/
		$('#cbxModalItems').empty();
		$('#cbxModalItems').append('<option value="'+itemIdForEdit+'">'+objectTableRowSelected.find('td:first').text()+'</option>');
		initiateModal();
	}
	
	function initiateModalEditCost(objectTableRowSelected){
		var costIdForEdit = objectTableRowSelected.find('#txtCostId').val();  //
		$('#btnModalAddCost').hide();
		$('#btnModalEditCost').show();
		$('#boxModalValidateCost').html('');//clear error message
//		$('#txtModalQuantity').val(objectTableRowSelected.find('#spaQuantity'+itemIdForEdit).text());
		$('#txtModalAmount').val(objectTableRowSelected.find('#spaAmount'+costIdForEdit).text());
//		$('#txtModalPrice').keypress(function(){return false;});
//		if ($('#txtModalQuantityDocument').length > 0){//existe
//			$('#txtModalQuantityDocument').val(objectTableRowSelected.find('#spaQuantityDocument'+itemIdForEdit).text());
//			$('#txtModalQuantityDocument').keypress(function(){return false;});
//		}
	/*	if($('#cbxWarehouses2').length > 0){
			$('#txtModalStock2').val(objectTableRowSelected.find('#spaStock2-'+itemIdForEdit).text());
			$('#txtModalStock2').keypress(function(){return false;});
		}*/
		$('#cbxModalCosts').empty();
		$('#cbxModalCosts').append('<option value="'+costIdForEdit+'">'+objectTableRowSelected.find('td:first').text()+'</option>');
		initiateModalCost();
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
	
	function deleteList(supplier){
		if ( $('#txtItemId').length ){
		
			if(confirm('Esta por cambiar de proveedor, esto borrara la lista de items esta seguro?')){	
				$('#tablaItems tbody tr').each(function(){
					var objectTableRowSelected = $('#txtItemId').closest('tr')
					var itemIdForDelete = objectTableRowSelected.find('#txtItemId').val();  //
					arrayItemsAlreadySaved = jQuery.grep(arrayItemsAlreadySaved, function(value){
						return value != itemIdForDelete;
					});
					objectTableRowSelected.remove();
				})				
			}else{
		//		alert(supplier);
				$('#cbxSuppliers').val(supplier);
			}
		}
	}
	
	function createEventClickEditCostButton(costId){
			$('#btnEditCost'+costId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr')
					initiateModalEditCost(objectTableRowSelected);
					return false; //avoid page refresh
			});
	}
	
	function createEventClickDeleteCostButton(costId){
		$('#btnDeleteCost'+costId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr')
					deleteCost(objectTableRowSelected);
					return false; //avoid page refresh
		});
	}
	
	function deleteCost(objectTableRowSelected){
		if(confirm('Esta seguro de Eliminar el costo?')){	

			var costIdForDelete = objectTableRowSelected.find('#txtCostId').val();  //
			arrayCostsAlreadySaved = jQuery.grep(arrayCostsAlreadySaved, function(value){
				return value != costIdForDelete;
			});
			objectTableRowSelected.remove();
		}
	}
	// (GC Ztep 3) function to fill Items list when saved in modal triggered by addItem()
	function createRowItemTable(itemId, itemCodeName, price, quantity, subtotal){
		var row = '<tr>';
		row +='<td><span id="spaItemName'+itemId+'">'+itemCodeName+'</span><input type="hidden" value="'+itemId+'" id="txtItemId" ></td>';
		row +='<td><span id="spaPrice'+itemId+'">'+price+'</span></td>';
		row +='<td><span id="spaQuantity'+itemId+'">'+quantity+'</span></td>';
		row +='<td><span id="spaSubtotal'+itemId+'">'+subtotal+'</span></td>';
		row +='<td class="columnItemsButtons">';
		row +='<a class="btn btn-primary" href="#" id="btnEditItem'+itemId+'" title="Editar"><i class="icon-pencil icon-white"></i></a> ';
		row +='<a class="btn btn-danger" href="#" id="btnDeleteItem'+itemId+'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
		row +='</td>';
		row +='</tr>'
		$('#tablaItems > tbody:last').append(row);
	}
	
	function createRowCostTable(costId, costCodeName, amount/*, quantity, subtotal*/){
		var row = '<tr>';
		row +='<td><span id="spaCostName'+costId+'">'+costCodeName+'</span><input type="hidden" value="'+costId+'" id="txtCostId" ></td>';
		row +='<td><span id="spaAmount'+costId+'">'+amount+'</span></td>';
//		row +='<td><span id="spaQuantity'+itemId+'">'+quantity+'</span></td>';
//		row +='<td><span id="spaSubtotal'+itemId+'">'+subtotal+'</span></td>';
		row +='<td class="columnCostsButtons">';
		row +='<a class="btn btn-primary" href="#" id="btnEditCost'+costId+'" title="Editar"><i class="icon-pencil icon-white"></i></a> ';
		row +='<a class="btn btn-danger" href="#" id="btnDeleteCost'+costId+'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
		row +='</td>';
		row +='</tr>'
		$('#tablaCosts > tbody:last').append(row);
	}
	
	// (GC Ztep 2) function to fill Items list when (saved in modal)
	function addItem(){
		var quantity = $('#txtModalQuantity').val();
		var itemId = $('#cbxModalItems').val();
		var itemCodeName = $('#cbxModalItems option:selected').text();
		var price = $('#txtModalPrice').val();
	var subtotal = ((quantity) * (price));
		var error = validateItem(itemCodeName, quantity, parseFloat(price).toFixed(2)/*, ''*/); 
		if(error == ''){
			
			createRowItemTable(itemId, itemCodeName, parseFloat(price).toFixed(2), parseInt(quantity,10)/*, stock2*/, subtotal);
			createEventClickEditItemButton(itemId);
			createEventClickDeleteItemButton(itemId);
			arrayItemsAlreadySaved.push(itemId);  //push into array of the added item
			$('#modalAddItem').modal('hide');
		}else{
			$('#boxModalValidateItem').html('<ul>'+error+'</ul>');
		}
	}
	
	function editItem(){
		var itemId = $('#cbxModalItems').val();
		var quantity = $('#txtModalQuantity').val();
		var itemCodeName = $('#cbxModalItems option:selected').text();
var price = $('#txtModalPrice').val();
var subtotal = ((quantity) * (price));
		var error = validateItem(itemCodeName, quantity, parseFloat(price).toFixed(2)/*, ''*/); 
		if(error == ''){
			$('#spaQuantity'+itemId).text(parseInt(quantity,10));
			$('#spaPrice'+itemId).text(parseFloat(price).toFixed(2));
			$('#spaSubtotal'+itemId).text(parseFloat(subtotal).toFixed(2));
			$('#modalAddItem').modal('hide');
		}else{
			$('#boxModalValidateItem').html('<ul>'+error+'</ul>');
		}
	}
		
		
		
	function addCost(){
//		var quantity = $('#txtModalQuantity').val();
		var costId = $('#cbxModalCosts').val();
		var costCodeName = $('#cbxModalCosts option:selected').text();
		var amount = $('#txtModalAmount').val();
//	var subtotal = ((quantity) * (price));
		var error = validateCost(costCodeName, parseFloat(amount).toFixed(2)/*, ''*/); 
		if(error == ''){
			
			createRowCostTable(costId, costCodeName, parseFloat(amount).toFixed(2)/*, parseInt(quantity,10)*//*, stock2*//*, subtotal*/);
			createEventClickEditCostButton(costId);
			createEventClickDeleteCostButton(costId);
			arrayCostsAlreadySaved.push(costId);  //push into array of the added item
			$('#modalAddCost').modal('hide');
		}else{
			$('#boxModalValidateCost').html('<ul>'+error+'</ul>');
		}
	}
	
	function editCost(){
		var costId = $('#cbxModalCosts').val();
//		var quantity = $('#txtModalQuantity').val();
		var costCodeName = $('#cbxModalCosts option:selected').text();
var amount = $('#txtModalAmount').val();
//var subtotal = ((quantity) * (price));
		var error = validateCost(costCodeName,/* quantity,*/ parseFloat(amount).toFixed(2)/*, ''*/); 
		if(error == ''){
//			$('#spaQuantity'+itemId).text(parseInt(quantity,10));
			$('#spaAmount'+costId).text(parseFloat(amount).toFixed(2));
//			$('#spaSubtotal'+itemId).text(parseFloat(subtotal).toFixed(2));
			$('#modalAddCost').modal('hide');
		}else{
			$('#boxModalValidateCost').html('<ul>'+error+'</ul>');
		}
	}	
	//get all items for save a purchase
	function getItemsDetails(){		
		var arrayItemsDetails = [];
		var itemId = '';
		var itemPrice = '';
		var itemQuantity = '';
//		var itemQuantityDocument = '';
		
		$('#tablaItems tbody tr').each(function(){		
			itemId = $(this).find('#txtItemId').val();
			itemPrice = $(this).find('#spaPrice'+itemId).text();
			itemQuantity = $(this).find('#spaQuantity'+itemId).text();
	
/*			if ($('#spaQuantityDocument'+itemId).length > 0){//exists
				itemQuantityDocument = $(this).find('#spaQuantityDocument'+itemId).text();
			}
*/			
			arrayItemsDetails.push({'inv_item_id':itemId, 'price':itemPrice, 'quantity':itemQuantity/*, 'quantity_document':itemQuantityDocument*//*, 'stock2':itemStock2*/});
			
		});
		
		if(arrayItemsDetails.length == 0){  //For fix undefined index
			arrayItemsDetails = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
		}
		
		return arrayItemsDetails; 		
	}
	
	//get all costs for save a invoice
	function getCostsDetails(){		
		var arrayCostsDetails = [];
		var costId = '';
		var costAmount = '';
//		var itemQuantity = '';
//		var itemQuantityDocument = '';
		
		$('#tablaCosts tbody tr').each(function(){		
			costId = $(this).find('#txtCostId').val();
			costAmount = $(this).find('#spaAmount'+costId).text();
//			itemQuantity = $(this).find('#spaQuantity'+itemId).text();
	
//			if ($('#spaQuantityDocument'+itemId).length > 0){//exists
//				itemQuantityDocument = $(this).find('#spaQuantityDocument'+itemId).text();
//			}
			
			arrayCostsDetails.push({'inv_price_type_id':costId, 'amount':costAmount/*, 'quantity':itemQuantity, 'quantity_document':itemQuantityDocument*//*, 'stock2':itemStock2*/});
			
		});
		
		if(arrayCostsDetails.length == 0){  //For fix undefined index
			arrayCostsDetails = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
		}
		
		return arrayCostsDetails; 		
	}
	
	//show message of procesing for ajax
	function showProcessing(){
        $('#processing').text("Procesando...");
    }
	
	function saveAll(){
		var arrayItemsDetails = [];
		arrayItemsDetails = getItemsDetails();
		var arrayCostsDetails = [];
		arrayCostsDetails = getCostsDetails();
		var error = validateBeforeSaveAll(arrayItemsDetails);
		if( error == ''){
			if(arr[3] == 'save_order'/* || arr[3] == 'save_purchase_in'*/){
				ajax_save_movement_in(arrayItemsDetails);
			}
			if(arr[3] == 'save_invoice'){
				ajax_save_invoice(arrayItemsDetails, arrayCostsDetails);
			}
//			if(arr[3] == 'save_warehouses_transfer'){
//				//alert('funciona para transferencias entre almacenes');
//				ajax_save_warehouses_transfer(arrayItemsDetails);
//			}
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
	}
	
	// (AEA Ztep 2) action when button Aprobar Entrada Almacen is pressed
	function changeStateApproved(){
			var arrayItemsDetails = [];
			arrayItemsDetails = getItemsDetails();
			var arrayCostsDetails = [];
			arrayCostsDetails = getCostsDetails();
var error = validateBeforeSaveAll(arrayItemsDetails);
if( error == ''){
		if(confirm('Al APROBAR este documento ya no se podra hacer mas modificaciones. Esta seguro?')){

			if(arr[3] == 'save_order' /*|| arr[3] == 'save_purchase_in'*/){
				ajax_change_state_approved_movement_in(arrayItemsDetails);
			}
			if(arr[3]=='save_invoice'){
				ajax_change_state_approved_invoice(arrayItemsDetails, arrayCostsDetails);
			}
//			if(arr[3] == 'save_warehouses_transfer'){
//				ajax_change_state_approved_warehouses_transfer(arrayItemsDetails);
//			}
		}
}else{
	$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
}
		
	}
	// (CEA Ztep 2) action when button Cancelar Entrada Almacen is pressed
	function changeStateCancelled(){
		if(confirm('Al CANCELAR este documento ya no sera valido y no habra marcha atras. Esta seguro?')){
			//$('#cbxWarehouses').removeAttr('disabled');
			var arrayItemsDetails = [];
			arrayItemsDetails = getItemsDetails();
			if(arr[3] == 'save_order' /*|| arr[3] == 'save_purchase_in'*/){
				ajax_change_state_cancelled_movement_in(arrayItemsDetails);
			}
//			if(arr[3]=='save_out'){
//				ajax_change_state_cancelled_movement_out(arrayItemsDetails);
//			}
//			if(arr[3] == 'save_warehouses_transfer'){
//				ajax_change_state_cancelled_warehouses_transfer(arrayItemsDetails);
//			}
		}
	}
	
	
	function changeStateLogicDeleted(){
		if(confirm('Al CANCELAR este documento ya no sera valido y no habra marcha atras. Esta seguro?')){
			//$('#cbxWarehouses').removeAttr('disabled');
			var arrayItemsDetails = [];
			arrayItemsDetails = getItemsDetails();
			if(arr[3] == 'save_order' /*|| arr[3] == 'save_purchase_in'*/){
				ajax_change_state_logic_deleted_movement_in(arrayItemsDetails);
			}
//			if(arr[3]=='save_out'){
//				ajax_change_state_cancelled_movement_out(arrayItemsDetails);
//			}
//			if(arr[3] == 'save_warehouses_transfer'){
//				ajax_change_state_cancelled_warehouses_transfer(arrayItemsDetails);
//			}
		}
	}
	//************************************************************************//
	//////////////////////////////////END-FUNCTIONS//////////////////////
	//************************************************************************//
	
	
	
	
	//************************************************************************//
	//////////////////////////////////BEGIN-CONTROLS EVENTS/////////////////////
	//************************************************************************//
	//Validate only numbers
	$('#txtModalAmount').keydown(function(event) {
			validateOnlyNumbers(event);			
	});
	//Validate only numbers
	$('#txtModalQuantity').keydown(function(event) {
			validateOnlyNumbers(event);			
	});
	//Calendar script
	$("#txtDate").datepicker({
	  showButtonPanel: true
   });
//	$('#txtDate').glDatePicker(
//	{
//		cssName: 'flatwhite',		
//		onClick: function(target, cell, date, data) {
//			var correctMonth = date.getMonth() + 1;
//			target.val(date.getDate() + ' / ' +
//						correctMonth + ' / ' +
//						date.getFullYear());
//
//			if(data != null) {
//				alert(data.message + '\n' + date);
//			}
//		
//		}
//	});
	//Call modal
	$('#btnAddItem').click(function(){
		initiateModalAddItem();
		return false; //avoid page refresh
	});
	
	// (GC Ztep 1) action when button Guardar on the modal is pressed
	$('#btnModalAddItem').click(function(){
		addItem();
		return false; //avoid page refresh
	});
	
	//edit an existing item quantity
	$('#btnModalEditItem').click(function(){
		editItem();
		return false; //avoid page refresh
	});
	
	//saves all order
	$('#btnSaveAll').click(function(){
		saveAll();
		return false; //avoid page refresh
	});
	
	$('#btnAddCost').click(function(){
		initiateModalAddCost();
		return false; //avoid page refresh
	});
	$('#btnModalAddCost').click(function(){
		addCost();
		return false; //avoid page refresh
	});
	
	//edit an existing item quantity
	$('#btnModalEditCost').click(function(){
		editCost();
		return false; //avoid page refresh
	});
	////////////////
	
	// (AEA Ztep 1) action when button Aprobar Entrada Almacen is pressed
	$('#btnApproveState').click(function(){
		//alert('Se aprueba entrada');
		changeStateApproved();
		return false;
	});
	// (CEA Ztep 1) action when button Cancelar Entrada Almacen is pressed
	$('#btnCancellState').click(function(){
		//alert('Se cancela entrada');
		changeStateCancelled();
		return false;
	});
	
	$('#btnLogicDeleteState').click(function(){
		//alert('Se cancela entrada');
		changeStateLogicDeleted();
		return false;
	});
	
	$('#cbxSuppliers').data('pre', $(this).val());
	$('#cbxSuppliers').change(function(){
	var supplier = $(this).data('pre');
		deleteList(supplier);
	$(this).data('pre', $(this).val());
		return false; //avoid page refresh
	});
  

	$('#txtDate').keypress(function(){return false;});
	$('#txtCode').keypress(function(){return false;});
//	if ($('#txtDocumentCode').length > 0){//existe
//		$('#txtDocumentCode').keypress(function(){return false;});
//	}
	//************************************************************************//
	//////////////////////////////////END-CONTROLS EVENTS//////////////////////
	//************************************************************************//
	
	
	
	
	//************************************************************************//
	//////////////////////////////////BEGIN-AJAX FUNCTIONS//////////////////////
	////************************************************************************//
	
	
	
	//Save order IN
	function ajax_save_movement_in(arrayItemsDetails){
//		var movementType =1;//Purchase
//		var documentCode ='NO';
//		if ($('#txtDocumentCode').length > 0){//existe
//			documentCode = $('#txtDocumentCode').val();
//		}
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_save_movement_in",			
            data:{arrayItemsDetails: arrayItemsDetails 
				  ,purchaseId:$('#txtPurchaseIdHidden').val()
				  ,date:$('#txtDate').val()
				  ,supplier:$('#cbxSuppliers').val()
				  ,description:$('#txtDescription').val()
//				  ,documentCode:documentCode
			  },
            beforeSend: showProcessing(),
            success: function(data){
				var arrayCatch = data.split('|');

				if(arrayCatch[0] == 'insertado'){ 
					$('#txtCode').val(arrayCatch[1]);
//					$('#columnStatePurchase').css('background-color','#F99C17');
//					$('#columnStatePurchase').text('Orden Pendiente');
changeLabelDocumentState('ORDER_PENDANT'); //#UNICORN
					$('#btnApproveState').show();
$('#btnLogicDeleteState').show();
					$('#txtPurchaseIdHidden').val(arrayCatch[2]);
					$('#txtGenericCode').val(arrayCatch[3]);
					$('#cbxSuppliers').attr('disabled','disabled');
				}
		
				//update items stocks
				//var arrayItemsStocks = arrayCatch[1].split(',');
				//updateMultipleStocks(arrayItemsStocks, 'spaStock');
				$('#btnPrint').show();
				$('#boxMessage').html('<div class="alert alert-success">\n\
				<button type="button" class="close" data-dismiss="alert">&times;</button>Guardado con exito<div>');
				$('#processing').text('');
/*					$('#boxMessage').html('<div class="alert alert-success">\n\
					<button type="button" class="close" data-dismiss="alert">&times;</button>Guardado con exito<div>');
					$('#processing').text('');*/
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	function ajax_save_invoice(arrayItemsDetails, arrayCostsDetails){
//		var movementType =1;//Purchase
//		var documentCode ='NO';
//		if ($('#txtDocumentCode').length > 0){//existe
//			documentCode = $('#txtDocumentCode').val();
//		}
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_save_invoice",			
            data:{arrayItemsDetails: arrayItemsDetails 
				  ,arrayCostsDetails: arrayCostsDetails	
				  ,purchaseId:$('#txtPurchaseIdHidden').val()
				  ,date:$('#txtDate').val()
				  ,supplier:$('#cbxSuppliers').val()
				  ,description:$('#txtDescription').val()
//				  ,documentCode:documentCode
			  },
            beforeSend: showProcessing(),
            success: function(data){
				var arrayCatch = data.split('|');

				if(arrayCatch[0] == 'insertado'){ 
					$('#txtCode').val(arrayCatch[1]);
//					$('#columnStatePurchase').css('background-color','#F99C17');
//					$('#columnStatePurchase').text('Factura Pendiente');

changeLabelDocumentState('INVOICE_PENDANT'); //#UNICORN
					$('#btnApproveState').show();
					$('#txtPurchaseIdHidden').val(arrayCatch[2]);
					$('#txtGenericCode').val(arrayCatch[3]);
				}
		
				//update items stocks
				//var arrayItemsStocks = arrayCatch[1].split(',');
				//updateMultipleStocks(arrayItemsStocks, 'spaStock');
				$('#btnPrint').show();
				$('#boxMessage').html('<div class="alert alert-success">\n\
				<button type="button" class="close" data-dismiss="alert">&times;</button>Guardado con exito<div>');
				$('#processing').text('');
/*					$('#boxMessage').html('<div class="alert alert-success">\n\
					<button type="button" class="close" data-dismiss="alert">&times;</button>Guardado con exito<div>');
					$('#processing').text('');*/
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	// (AEA Ztep 3) action when button Aprobar Entrada Almacen is pressed
	function ajax_change_state_approved_movement_in(arrayItemsDetails){
//		var movementType =1;//Purchase
//		var documentCode ='NO';
//		if ($('#cbxSuppliers').length > 0){//existe
//			movementType = $('#cbxSuppliers').val();
//		}
//		if ($('#txtDocumentCode').length > 0){//existe
//			documentCode = $('#txtDocumentCode').val();
//		}
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_change_state_approved_movement_in",			
            data:{arrayItemsDetails: arrayItemsDetails 
				  ,purchaseId:$('#txtPurchaseIdHidden').val()
				  ,date:$('#txtDate').val()
				  ,supplier:$('#cbxSuppliers').val()	
				  ,description:$('#txtDescription').val()
				  ,genericCode:$('#txtGenericCode').val()
//				  ,documentCode:documentCode
			  },
            beforeSend: showProcessing(),
            success: function(data){			
				var arrayCatch = data.split('|');
				if(arrayCatch[0] == 'aprobado'){
//					$('#columnStatePurchase').css('background-color','#54AA54');
//					$('#columnStatePurchase').text('Orden Aprobada');

changeLabelDocumentState('ORDER_APPROVED'); //#UNICORN
					$('#btnApproveState').hide();
					$('#btnCancellState').show();
					$('#btnSaveAll').hide();
$('#btnLogicDeleteState').hide();
	//				$('#btnAddMovementType').hide();
					if ($('#btnAddItem').length > 0){//existe
						$('#btnAddItem').hide();
					}
					$('.columnItemsButtons').hide();

	//				if ($('#txtDocumentCode').length > 0){//existe
	//					$('#txtDocumentCode').attr('disabled','disabled');
	//				}
					$('#txtDate').attr('disabled','disabled');
					$('#txtCode').attr('disabled','disabled');
					$('#cbxSuppliers').attr('disabled','disabled');
					$('#txtDescription').attr('disabled','disabled');
					$('#boxMessage').html('<div class="alert alert-success">\n\
					<button type="button" class="close" data-dismiss="alert">&times;</button>Orden Aprobada con exito<div>');
				}
				$('#processing').text('');
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	function ajax_change_state_approved_invoice(arrayItemsDetails, arrayCostsDetails){
//		var movementType =1;//Purchase
//		var documentCode ='NO';
//		if ($('#cbxSuppliers').length > 0){//existe
//			movementType = $('#cbxSuppliers').val();
//		}
//		if ($('#txtDocumentCode').length > 0){//existe
//			documentCode = $('#txtDocumentCode').val();
//		}
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_change_state_approved_invoice",			
            data:{arrayItemsDetails: arrayItemsDetails 
				,arrayCostsDetails: arrayCostsDetails	
				  ,purchaseId:$('#txtPurchaseIdHidden').val()
				  ,date:$('#txtDate').val()
				  ,supplier:$('#cbxSuppliers').val()	
				  ,description:$('#txtDescription').val()
				  ,genericCode:$('#txtGenericCode').val()
//				  ,documentCode:documentCode
			  },
            beforeSend: showProcessing(),
            success: function(data){			
				var arrayCatch = data.split('|');
				if(arrayCatch[0] == 'aprobado'){
//					$('#columnStatePurchase').css('background-color','#54AA54');
//					$('#columnStatePurchase').text('Orden Aprobada');

changeLabelDocumentState('INVOICE_APPROVED'); //#UNICORN
					$('#btnApproveState').hide();
					$('#btnCancellState').show();
					$('#btnSaveAll').hide();
	//				$('#btnAddMovementType').hide();
					if ($('#btnAddItem').length > 0){//existe
						$('#btnAddItem').hide();
					}
					if ($('#btnAddCost').length > 0){//existe
						$('#btnAddCost').hide();
					}
						$('.columnItemsButtons').hide();
$('.columnCostsButtons').hide();

	//				if ($('#txtDocumentCode').length > 0){//existe
	//					$('#txtDocumentCode').attr('disabled','disabled');
	//				}
					$('#txtDate').attr('disabled','disabled');
					$('#txtCode').attr('disabled','disabled');
					$('#cbxSuppliers').attr('disabled','disabled');
					$('#txtDescription').attr('disabled','disabled');
					$('#boxMessage').html('<div class="alert alert-success">\n\
					<button type="button" class="close" data-dismiss="alert">&times;</button>Orden Aprobada con exito<div>');
				}
				$('#processing').text('');
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	// (CEA Ztep 3) action when button Cancelar Entrada Almacen is pressed
	function ajax_change_state_cancelled_movement_in(arrayItemsDetails){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_change_state_cancelled_movement_in",			
            data:{arrayItemsDetails: arrayItemsDetails 
				  ,purchaseId:$('#txtPurchaseIdHidden').val()
			  },
            beforeSend:showProcessing(),
            success: function(data){
				var arrayCatch = data.split('|');
//				var arrayItemsStocks = arrayCatch[1].split(',');
				if(arrayCatch[0] == 'cancelado'){
//					updateMultipleStocks(arrayItemsStocks, 'spaStock');
//					$('#columnStatePurchase').css('background-color','#BD362F');
//					$('#columnStatePurchase').text('Orden Cancelada');

changeLabelDocumentState('ORDER_CANCELLED'); //#UNICORN
					$('#btnCancellState').hide();
					$('#boxMessage').html('<div class="alert alert-success">\n\
					<button type="button" class="close" data-dismiss="alert">&times;</button>Orden Cancelada con exito<div>');
				}
// REVISAR SI ES NECESARIO COMPROBAR LO DEL STOCK EN EL REMITO CON CANCELAR LA ORDEN				
//				if(arrayCatch[0] == 'error'){
//					var error = validateBeforeMoveOut(arrayItemsStocks, 'spaStock');
//					$('#boxMessage').html('<div class="alert alert-error">\n\
//					<button type="button" class="close" data-dismiss="alert">&times;</button><p>No se pudo "Cancelar" la entrada debido a falta de stock:</p><ul>//'+error+'</ul><div>');
//				}
				$('#processing').text('');
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	function ajax_change_state_logic_deleted_movement_in(arrayItemsDetails){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_change_state_logic_deleted_movement_in",			
            data:{arrayItemsDetails: arrayItemsDetails 
				  ,purchaseId:$('#txtPurchaseIdHidden').val()
			  },
            beforeSend:showProcessing(),
            success: function(data){
				var arrayCatch = data.split('|');
//				var arrayItemsStocks = arrayCatch[1].split(',');
				if(arrayCatch[0] == 'borradologico'){
//					updateMultipleStocks(arrayItemsStocks, 'spaStock');
//					$('#columnStatePurchase').css('background-color','#BD362F');
//					$('#columnStatePurchase').text('Orden Cancelada');

//changeLabelDocumentState('ORDER_LOGIC_DELETED'); //#UNICORN
//					$('#btnLogicDeleteState').hide();
//self.location = moduleController + "index_order";
setTimeout(function() {
  self.location = moduleController + "index_order";
}, 2000);
//window.location = moduleController + "index_order";
//window.location.href = moduleController + "index_order";
					$('#boxMessage').html('<div class="alert alert-success">\n\
					<button type="button" class="close" data-dismiss="alert">&times;</button>Orden Cancelada con exito<div>');

				}
// REVISAR SI ES NECESARIO COMPROBAR LO DEL STOCK EN EL REMITO CON CANCELAR LA ORDEN				
//				if(arrayCatch[0] == 'error'){
//					var error = validateBeforeMoveOut(arrayItemsStocks, 'spaStock');
//					$('#boxMessage').html('<div class="alert alert-error">\n\
//					<button type="button" class="close" data-dismiss="alert">&times;</button><p>No se pudo "Cancelar" la entrada debido a falta de stock:</p><ul>//'+error+'</ul><div>');
//				}
//				$('#processing').text('');
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	//Get items and prices for the fist item when inititates modal
	function ajax_initiate_modal_add_item_in(itemsAlreadySaved){
		 $.ajax({
            type:"POST",
            url:moduleController + "ajax_initiate_modal_add_item_in",			
  /*data*/  data:{itemsAlreadySaved: itemsAlreadySaved, supplier: $('#cbxSuppliers').val()/*, transfer:transfer, warehouse2:warehouse2*/},
            beforeSend: showProcessing(),
            success: function(data){
				$('#processing').text('');
				$('#boxModalInitiateItemPrice').html(data);
				$('#txtModalQuantity').val('');  
				initiateModal()
				$('#cbxModalItems').bind("change",function(){ //must be binded 'cause dropbox is loaded by a previous ajax'
					ajax_update_stock_modal();
				});
//				$('#txtModalPrice').keypress(function(){return false;});
				
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	function ajax_initiate_modal_add_cost(costsAlreadySaved){
		 $.ajax({
            type:"POST",
            url:moduleController + "ajax_initiate_modal_add_cost",			
  /*data*/  data:{costsAlreadySaved: costsAlreadySaved/*, supplier: $('#cbxSuppliers').val()*//*, transfer:transfer, warehouse2:warehouse2*/},
            beforeSend: showProcessing(),
            success: function(data){
				$('#processing').text('');
				$('#boxModalInitiateCost').html(data);
				$('#txtModalAmount').val('');  
				initiateModalCost()
/*				$('#cbxModalCosts').bind("change",function(){ //must be binded 'cause dropbox is loaded by a previous ajax'
					ajax_update_amount();
				});
*///				$('#txtModalPrice').keypress(function(){return false;});
				
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	//Update price
	function ajax_update_stock_modal(){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_update_stock_modal",			
            data:{item: $('#cbxModalItems').val()},
            beforeSend: showProcessing(),
            success: function(data){
				$('#processing').text("");
				$('#boxModalPrice').html(data);
//				$('#txtModalPrice').bind("keypress",function(){ //must be binded 'cause input is re-loaded by a previous ajax'
//					return false;
//				});
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
