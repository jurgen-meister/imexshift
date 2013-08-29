$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation

	var globalPeriod = $('#globalPeriod').text(); // this value is obtained from the main template.
	
	var arrayItemsAlreadySaved = []; 
	var arrayWarehouseItemsAlreadySaved = []; 
	startEventsWhenExistsItems();
	
	var arrayCostsAlreadySaved = []; 
	startEventsWhenExistsCosts();
	
	var arrayPaysAlreadySaved = []; 
	startEventsWhenExistsPays();
	
	
	
	
	//gets a list of the item ids in the document details
	function itemsListWhenExistsItems(){
		var arrayAux = [];
		arrayItemsAlreadySaved = [];
		arrayAux = getItemsDetails();
		if(arrayAux[0] != 0){
			for(var i=0; i< arrayAux.length; i++){
				 arrayItemsAlreadySaved[i] = arrayAux[i]['inv_item_id'];
			}
		}
		if(arrayItemsAlreadySaved.length == 0){  //For fix undefined index
			arrayItemsAlreadySaved = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
		}
		
		return arrayItemsAlreadySaved; //NOT SURE TO PUT THIS LINE	
	}
	
	//gets a list of the warehouse ids in the document details
	function warehouseListWhenExistsItems(){
		var arrayAux = [];
		arrayWarehouseItemsAlreadySaved = [];
		arrayAux = getItemsDetails();
		if(arrayAux[0] != 0){
			for(var i=0; i< arrayAux.length; i++){
				 arrayWarehouseItemsAlreadySaved[i] = arrayAux[i]['inv_warehouse_id'];
			}
		}
		if(arrayWarehouseItemsAlreadySaved.length == 0){  //For fix undefined index
			arrayWarehouseItemsAlreadySaved = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
		}
		
		return arrayWarehouseItemsAlreadySaved; //NOT SURE TO PUT THIS LINE	
	}
	
	//When exist items, it starts its events and fills arrayItemsAlreadySaved
	function startEventsWhenExistsItems(){
		var arrayAux = [];
		arrayAux = getItemsDetails();
		if(arrayAux[0] != 0){
			for(var i=0; i< arrayAux.length; i++){
				 arrayItemsAlreadySaved[i] = arrayAux[i]['inv_item_id'];
				 arrayWarehouseItemsAlreadySaved[i] = arrayAux[i]['inv_warehouse_id'];
				 createEventClickEditItemButton(arrayAux[i]['inv_item_id'],arrayAux[i]['inv_warehouse_id']);
				 createEventClickDeleteItemButton(arrayAux[i]['inv_item_id'],arrayAux[i]['inv_warehouse_id']);			 
			}
		}
	}
		
	//When exist costs, it starts its events and fills arrayCostsAlreadySaved
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
	}
	
	//When exist pays, it starts its events and fills arrayPaysAlreadySaved
	function startEventsWhenExistsPays(){		/*STANDBY*/
		var arrayAux = [];
		arrayAux = getPaysDetails();
		if(arrayAux[0] != 0){
			for(var i=0; i< arrayAux.length; i++){
				 arrayPaysAlreadySaved[i] = arrayAux[i]['pur_payment_type_id'];
				 createEventClickEditPayButton(arrayAux[i]['pur_payment_type_id']);
				 createEventClickDeletePayButton(arrayAux[i]['pur_payment_type_id']);			 
			}
		}
	}
	//validates before add item warehouse price and quantity
	function validateItem(warehouse, item, salePrice, quantity){
		var error = '';
		if(warehouse === ''){error+='<li>El campo "Almacen" no puede estar vacio</li>';}
		if(item === ''){error+='<li>El campo "Item" no puede estar vacio</li>';}
		if(quantity === ''){
			error+='<li>El campo "Cantidad" no puede estar vacio</li>'; 
		}else{
			if(parseInt(quantity, 10) === 0){
				error+='<li>El campo "Cantidad" no puede ser cero</li>'; 
			}
		}
//		if(salePrice === ''){
//			error+='<li>El campo "Precio Unitario" no puede estar vacio</li>'; 
//		}else{
//			if(parseFloat(salePrice).toFixed(2) === 0.00){
//				error+='<li>El campo "Precio Unitario" no puede ser cero</li>'; 
//			}	
//		}
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
	
	function validatePay(pay, amount/*, documentQuantity*/){
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
			error+='<li>El campo "Monto Pagado" no puede estar vacio</li>'; 
		}else{
//o si puede ser cero el precio?			
			if(parseFloat(amount).toFixed(2) == 0){
				
				error+='<li>El campo "Monto Pagado" no puede ser cero</li>'; 
			}
		}
		
		if(pay == ''){error+='<li>El campo "Pagos" no puede estar vacio</li>';}
		
		return error;
	}
	
	function validateBeforeSaveAll(arrayItemsDetails){
		var error = '';
		var date = $('#txtDate').val();
		var dateYear = date.split('/');
		var clients = $('#cbxCustomers').text();
		var employees = $('#cbxEmployees').text();
		var taxNumbers = $('#cbxTaxNumbers').text();
		var salesmen = $('#cbxSalesman').text();
		if(date === ''){	error+='<li> El campo "Fecha" no puede estar vacio </li>'; }
		if(dateYear[2] !== globalPeriod){	error+='<li> El año '+dateYear[2]+' de la fecha del documento no es valida, ya que se encuentra en la gestión '+ globalPeriod +'.</li>'; }
		if(clients === ''){	error+='<li> El campo "Cliente" no puede estar vacio </li>'; }
		if(employees === ''){	error+='<li> El campo "Encargado" no puede estar vacio </li>'; }
		if(taxNumbers === ''){	error+='<li> El campo "NIT - Nombre" no puede estar vacio </li>'; }
		if(salesmen === ''){	error+='<li> El campo "Vendedor" no puede estar vacio </li>'; }
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
			case 'NOTE_PENDANT':
				$('#documentState').addClass('label-warning');
				$('#documentState').text('NOTA PENDIENTE');
				break;
			case 'NOTE_APPROVED':
				$('#documentState').removeClass('label-warning').addClass('label-success');
				$('#documentState').text('NOTA APROBADA');
				break;
			case 'NOTE_CANCELLED':
				$('#documentState').removeClass('label-success').addClass('label-important');
				$('#documentState').text('NOTA CANCELADA');
				break;
				case 'SINVOICE_PENDANT':
				$('#documentState').addClass('label-warning');
				$('#documentState').text('FACTURA PENDIENTE');
				break;
			case 'SINVOICE_APPROVED':
				$('#documentState').removeClass('label-warning').addClass('label-success');
				$('#documentState').text('FACTURA APROBADA');
				break;
			case 'SINVOICE_CANCELLED':
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
	
	function initiateModalPay(){
		$('#modalAddPay').modal({
					show: 'true',
					backdrop:'static'
		});
	}
	
	

	function initiateModalAddItem(){
		var error = validateBeforeSaveAll([{0:0}]);//I send [{0:0}] 'cause it doesn't care to validate if arrayItemsDetails is empty or not
		if( error === ''){
			if(arrayItemsAlreadySaved.length == 0){  //For fix undefined index
				arrayItemsAlreadySaved = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
				arrayWarehouseItemsAlreadySaved = [0]
			}
			$('#btnModalAddItem').show();
			$('#btnModalEditItem').hide();
			$('#boxModalValidateItem').html('');//clear error message
			ajax_initiate_modal_add_item_in(arrayItemsAlreadySaved, arrayWarehouseItemsAlreadySaved);
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
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
	
	function initiateModalAddPay(){
		if(arrayPaysAlreadySaved.length == 0){  //For fix undefined index
			arrayPaysAlreadySaved = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
		}
		$('#btnModalAddPay').show();
		$('#btnModalEditPay').hide();
		$('#boxModalValidatePay').html('');//clear error message
		ajax_initiate_modal_add_pay(arrayPaysAlreadySaved);
	}
	
	function initiateModalEditItem(objectTableRowSelected){
		var error = validateBeforeSaveAll([{0:0}]);//I send [{0:0}] 'cause it doesn't care to validate if arrayItemsDetails is empty or not
		if( error === ''){
			var itemIdForEdit = objectTableRowSelected.find('#txtItemId').val();
			var warehouseIdForEdit = objectTableRowSelected.find('#txtWarehouseId'+itemIdForEdit).val();
			$('#btnModalAddItem').hide();
			$('#btnModalEditItem').show();
			$('#boxModalValidateItem').html('');//clear error message
			$('#cbxModalWarehouses').empty();
			$('#cbxModalWarehouses').append('<option value="'+warehouseIdForEdit+'">'+objectTableRowSelected.find('#spaWarehouse'+itemIdForEdit).text()+'</option>');
			$('#cbxModalItems').empty();
			$('#cbxModalItems').append('<option value="'+itemIdForEdit+'">'+objectTableRowSelected.find('td:first').text()+'</option>');
			$('#txtModalPrice').val(objectTableRowSelected.find('#spaSalePrice'+itemIdForEdit+'w'+warehouseIdForEdit).text());
			$('#txtModalStock').val(objectTableRowSelected.find('#spaStock'+itemIdForEdit).text());
			$('#txtModalQuantity').val(objectTableRowSelected.find('#spaQuantity'+itemIdForEdit+'w'+warehouseIdForEdit).text());
			initiateModal()
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
	}
	
	
	
	function ajax_initiate_modal_edit_item_in(objectTableRowSelected){
		var itemIdForEdit = objectTableRowSelected.find('#txtItemId').val();
		var warehouseIdForEdit = objectTableRowSelected.find('#txtWarehouseId'+itemIdForEdit).val();
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_initiate_modal_edit_item_in",			
  /*data*/  data:{//itemsAlreadySaved: itemsAlreadySaved
				/*, customer: $('#cbxCustomers').val()
				, employee: $('#cbxEmployees').val()
				, taxNumber: $('#cbxTaxNumbers').val()*/
				warehouse: warehouseIdForEdit	
				, item: itemIdForEdit},				
            beforeSend: showProcessing(),
            success: function(data){
			$('#processing').text('');
			$('#boxModalInitiateItemPrice').html(data);///////////////////////////////////////////////
		
			$('#txtModalQuantity').val(objectTableRowSelected.find('#spaQuantity'+itemIdForEdit).text());
			$('#txtModalPrice').val(objectTableRowSelected.find('#spaSalePrice'+itemIdForEdit).text());

			$('#cbxModalItems').empty();
			$('#cbxModalItems').append('<option value="'+itemIdForEdit+'">'+objectTableRowSelected.find('td:first').text()+'</option>');
			initiateModal()//;		
				
				
			$('#cbxModalWarehouses').empty();
			$('#cbxModalWarehouses').append('<option value="'+warehouseIdForEdit+'">'+objectTableRowSelected.find('#spaWarehouse'+itemIdForEdit).text()+'</option>');

			//	$('#boxModalInitiateItemPrice').html(data);
			//	$('#txtModalQuantity').val('');  
			//	initiateModal()
//				$('#cbxModalItems').bind("change",function(){ //must be binded 'cause dropbox is loaded by a previous ajax'
//					//este es para el precio
//					ajax_update_stock_modal();
//					//este es para el stock
//					ajax_update_stock_modal_1();
//				});
			$('#cbxModalWarehouses').bind("change",function(){ //must be binded 'cause dropbox is loaded by a previous ajax'
				//este es para el stock
				ajax_update_stock_modal_1();
			});
//				$('#txtModalPrice').keypress(function(){return false;});
			$('#cbxModalItems').select2();
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
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
	
	function initiateModalEditPay(objectTableRowSelected){
		var payIdForEdit = objectTableRowSelected.find('#txtPayId').val();  //
		$('#btnModalAddPay').hide();
		$('#btnModalEditPay').show();
		$('#boxModalValidatePay').html('');//clear error message
//		$('#txtModalQuantity').val(objectTableRowSelected.find('#spaQuantity'+itemIdForEdit).text());
		$('#txtModalDate').val(objectTableRowSelected.find('#spaDate'+payIdForEdit).text());
		$('#txtModalDueDate').val(objectTableRowSelected.find('#spaDueDate'+payIdForEdit).text());
		$('#txtModalPaidAmount').val(objectTableRowSelected.find('#spaPaidAmount'+payIdForEdit).text());
		$('#txtModalDescription').val(objectTableRowSelected.find('#spaDescription'+payIdForEdit).text());
		$('#txtModalState').val(objectTableRowSelected.find('#spaState'+payIdForEdit).text());
//		$('#txtModalPrice').keypress(function(){return false;});
//		if ($('#txtModalQuantityDocument').length > 0){//existe
//			$('#txtModalQuantityDocument').val(objectTableRowSelected.find('#spaQuantityDocument'+itemIdForEdit).text());
//			$('#txtModalQuantityDocument').keypress(function(){return false;});
//		}
	/*	if($('#cbxWarehouses2').length > 0){
			$('#txtModalStock2').val(objectTableRowSelected.find('#spaStock2-'+itemIdForEdit).text());
			$('#txtModalStock2').keypress(function(){return false;});
		}*/
		$('#cbxModalPays').empty();
		$('#cbxModalPays').append('<option value="'+payIdForEdit+'">'+objectTableRowSelected.find('td:first').text()+'</option>');
		initiateModalPay();
	}
	
	function createEventClickEditItemButton(itemId,warehouseId){
			$('#btnEditItem'+itemId+'w'+warehouseId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr')
					initiateModalEditItem(objectTableRowSelected);
					return false; //avoid page refresh
			});
	}
	
	function createEventClickDeleteItemButton(itemId,warehouseId){
		$('#btnDeleteItem'+itemId+'w'+warehouseId).bind("click",function(e){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr')
					deleteItem(objectTableRowSelected);
					//return false; //avoid page refresh
					e.preventDefault()
		});
	}
	
	function deleteItem(objectTableRowSelected){
		
		var arrayItemsDetails = getItemsDetails();
		var error = validateBeforeSaveAll([{0:0}]);//Send [{0:0}] 'cause I won't use arrayItemsDetails classic validation, I will use it differently for this case (as done below)
		if(arrayItemsDetails.length === 1){error+='<li> Debe existir al menos 1 "Item" </li>';}
		if( error === ''){
			showBittionAlertModal({content:'¿Está seguro de eliminar este item?'});
			$('#bittionBtnYes').click(function(){
				if(arr[3] == 'save_order'){
					ajax_save_movement('DELETE', 'NOTE_PENDANT', objectTableRowSelected, []);
				}
				if(arr[3] == 'save_invoice'){
					ajax_save_movement('DELETE', 'SINVOICE_PENDANT', objectTableRowSelected, []);
				}
				return false; //avoid page refresh
			});
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}

//			var itemIdForDelete = objectTableRowSelected.find('#txtItemId').val();  //
//			//	this should be a function
//			var subtotal = $('#spaSubtotal'+itemIdForDelete).text();	
//			var total = parseFloat($('#total').text()) - Number(subtotal);
//			//	this should be a function
//			$('#total').text(parseFloat(total).toFixed(2)+' Bs.');
//			
//			objectTableRowSelected.remove();
//			itemsListWhenExistsItems();
//			warehouseListWhenExistsItems();
		
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
	
	function createEventClickEditPayButton(payId){
			$('#btnEditPay'+payId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr')
					initiateModalEditPay(objectTableRowSelected);
					return false; //avoid page refresh
			});
	}
	
	function createEventClickDeletePayButton(payId){
		$('#btnDeletePay'+payId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr')
					deletePay(objectTableRowSelected);
					return false; //avoid page refresh
		});
	}
	
	function deletePay(objectTableRowSelected){
		if(confirm('Esta seguro de Eliminar el pago?')){	

			var payIdForDelete = objectTableRowSelected.find('#txtPayId').val();  //
			arrayPaysAlreadySaved = jQuery.grep(arrayPaysAlreadySaved, function(value){
				return value != payIdForDelete;
			});
			objectTableRowSelected.remove();
		}
	}
	
	// (GC Ztep 3) function to fill Items list when saved in modal triggered by addItem() //type="hidden"
	function createRowItemTable(itemId, itemCodeName, salePrice, quantity, warehouse, warehouseId, stock, subtotal){
		var row = '<tr id="itemRow'+itemId+'w'+warehouseId+'" >';
		row +='<td><span id="spaItemName'+itemId+'">'+itemCodeName+'</span><input  value="'+itemId+'" id="txtItemId" ></td>';
		row +='<td><span id="spaSalePrice'+itemId+'w'+warehouseId+'">'+salePrice+'</span></td>';
		row +='<td><span id="spaQuantity'+itemId+'w'+warehouseId+'">'+quantity+'</span></td>';
		row +='<td><span id="spaWarehouse'+itemId+'">'+warehouse+'</span><input type="hidden" value="'+warehouseId+'" id="txtWarehouseId'+itemId+'" ></td>';
		row +='<td><span id="spaStock'+itemId+'">'+stock+'</span></td>';
		row +='<td><span id="spaSubtotal'+itemId+'w'+warehouseId+'">'+subtotal+'</span></td>';
		row +='<td class="columnItemsButtons">';
		row +='<a class="btn btn-primary" href="#" id="btnEditItem'+itemId+'w'+warehouseId+'" title="Editar"><i class="icon-pencil icon-white"></i></a> ';
		row +='<a class="btn btn-danger" href="#" id="btnDeleteItem'+itemId+'w'+warehouseId+'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
		row +='</td>';
		row +='</tr>'
		$('#tablaItems').prepend(row);
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
	//payId, payCodeName, payDate, payDueDate, parseFloat(amount).toFixed(2), description, state
	//genera el codigo HTML para la creacion de una fila de la tabla de Pagos
	function createRowPayTable(payId, payCodeName, payDate, payDueDate, amount, debtAmount, description, state/*, quantity, subtotal*/){
		var row = '<tr>';
		row +='<td><span id="spaPayName'+payId+'">'+payCodeName+'</span><input type="hidden" value="'+payId+'" id="txtPayId" ></td>';
		row +='<td><span id="spaDate'+payId+'">'+payDate+'</span></td>';
		row +='<td><span id="spaDueDate'+payId+'">'+payDueDate+'</span></td>';
		row +='<td><span id="spaPaidAmount'+payId+'">'+amount+'</span></td>';
		row +='<td><span id="spaDebtAmount'+payId+'">'+debtAmount+'</span></td>';
		row +='<td><span id="spaDescription'+payId+'">'+description+'</span></td>';
		row +='<td><span id="spaState'+payId+'">'+state+'</span></td>';
		row +='<td class="columnPaysButtons">';
		row +='<a class="btn btn-primary" href="#" id="btnEditPay'+payId+'" title="Editar"><i class="icon-pencil icon-white"></i></a> ';
		row +='<a class="btn btn-danger" href="#" id="btnDeletePay'+payId+'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
		row +='</td>';
		row +='</tr>'
		$('#tablaPays > tbody:last').append(row);
	}
	
	//*****************************************************************************************************************************//
	function setOnData(ACTION, OPERATION, STATE, objectTableRowSelected, arrayForValidate){
		var DATA = [];
		//constants
		var purchaseId=$('#txtPurchaseIdHidden').val();
		var	movementDocCode = $('#txtCode').val();
		var	movementCode = $('#txtGenericCode').val();
		var noteCode=$('#txtNoteCode').val();
		var date=$('#txtDate').val();
		var employee=$('#cbxEmployees').val();
		var taxNumber=$('#cbxTaxNumbers').val();
		var salesman=$('#cbxSalesman').val();
		var description=$('#txtDescription').val();
		var exRate=$('#txtExRate').val();
		//variables
		var warehouseId = 0;
		var itemId = 0;
		var salePrice = 0.00;
		var quantity = 0;
//		var cifPrice = 0.00;	//temp var
//		var exCifPrice = 0.00;	//temp var
		
		var subtotal = 0.00;
	//	var total = 0.00;
		//only used for ADD
		var warehouse = '';
		var itemCodeName = '';
		var stock = 0;
		
		//Sale setup variables
//		if((ACTION !== 'save_order') || (ACTION !== 'save_out')){
//			movementDocCode = $('#txtCode').val();
//			movementCode = $('#txtGenericCode').val();
//		}
		
//		if((ACTION === 'save_purchase_in' || ACTION === 'save_sale_out') && (movementId === '')  && (movementId === '')){
//			arrayForValidate = getItemsDetails();
//		}
		
//		if(ACTION === 'save_warehouses_transfer'){
//			warehouseId2 = $('#cbxWarehouses2').val();
//		}	
		//SaleDetails(Item) setup variables
		if(OPERATION === 'ADD' || OPERATION === 'EDIT'){
			warehouseId = $('#cbxModalWarehouses').val();		
			itemId = $('#cbxModalItems').val();
			salePrice = $('#txtModalPrice').val();
			quantity = $('#txtModalQuantity').val();

//			
//			total = parseFloat($('#total').text()) + Number(subtotal);
			if(OPERATION === 'ADD'){
				warehouse = $('#cbxModalWarehouses option:selected').text();
				itemCodeName = $('#cbxModalItems option:selected').text();
				stock = $('#txtModalStock').val();
//				cifPrice = 0.00;	//temp var
//				exCifPrice = 0.00;	//temp var
				subtotal = Number(quantity) * Number(salePrice);
			}
		}
		if(OPERATION === 'DELETE'){
			itemId = objectTableRowSelected.find('#txtItemId').val();
			warehouseId = objectTableRowSelected.find('#txtWarehouseId'+itemId).val();
		}
		//setting data
		DATA ={	'purchaseId':purchaseId
				,'movementDocCode':movementDocCode
				,'movementCode':movementCode
				,'noteCode':noteCode
				,'date':date
				,'employee':employee
				,'taxNumber':taxNumber
				,'salesman':salesman
				,'description':description	
				,'exRate':exRate

				,'warehouseId':warehouseId
				,'warehouse':warehouse
				,'itemId':itemId
				,'salePrice':salePrice
				,'quantity':quantity	
//				,'cifPrice':cifPrice
//				,'exCifPrice':exCifPrice
				
				,'subtotal':subtotal
			//	,'total':total
				
				,'ACTION':ACTION
				,'OPERATION':OPERATION
				,'STATE':STATE

				,itemCodeName:itemCodeName
				,stock:stock
				,arrayForValidate:arrayForValidate
			  };
		  
		return DATA;
	}
	
	function highlightTemporally(id){
		//$('#itemRow'+dataSent['itemId']).delay(8000).removeAttr('style');
			$(id).fadeIn(4000).css("background-color","#FFFF66");
			setTimeout(function() {
				$(id).removeAttr('style');
				//$('#itemRow'+itemId).animate({ background: '#fed900'}, "slow");
				 //$('#itemRow'+itemId).fadeOut(400);
				 //$('#itemRow'+itemId).fadeIn(4000).css("background-color","red");
				 //$('#itemRow'+itemId).animate({ backgroundColor: "#f6f6f6" }, 'slow');
			}, 4000);
	}
	
	function setOnPendant(DATA, ACTION, OPERATION, STATE, objectTableRowSelected, warehouseId, warehouse, itemId, itemCodeName, salePrice, stock, quantity, subtotal){
		if($('#txtPurchaseIdHidden').val() === ''){
//			if(ACTION === 'save_warehouses_transfer'){
//				$('#txtDocumentCode').val(DATA[2]);
//			}else{
				$('#txtCode').val(DATA[2]);
				$('#txtGenericCode').val(DATA[3]);
//			}
			
			$('#btnApproveState, #btnPrint, #btnLogicDeleteState').show();
			$('#txtPurchaseIdHidden').val(DATA[1]);
			changeLabelDocumentState(STATE); //#UNICORN
		}
		/////////////************************************////////////////////////
		//Item's table setup
		if(OPERATION === 'ADD'){
			createRowItemTable(itemId, itemCodeName, parseFloat(salePrice).toFixed(2), parseInt(quantity,10), warehouse, warehouseId, stock, parseFloat(subtotal).toFixed(2));
			createEventClickEditItemButton(itemId, warehouseId);
			createEventClickDeleteItemButton(itemId, warehouseId);
			arrayItemsAlreadySaved.push(itemId);  //push into array of the added item
			arrayWarehouseItemsAlreadySaved.push(warehouseId);  //push into array of the added warehouses	
			$('#countItems').text(arrayItemsAlreadySaved.length);
//			var subtotalw = Number(quantity) * Number(salePrice);
//			$('#spaSubtotal'+itemId).text(parseFloat().toFixed(2));
//			var total = $('#total').text();
//			var total = getTotal()
			$('#total').text(parseFloat(getTotal()).toFixed(2)+' Bs.');
			
			$('#modalAddItem').modal('hide');
			highlightTemporally('#itemRow'+itemId+'w'+warehouseId);
		}
		if(OPERATION === 'EDIT'){
			$('#spaQuantity'+itemId+'w'+warehouseId).text(parseInt(quantity,10));
			$('#spaSalePrice'+itemId+'w'+warehouseId).text(parseFloat(salePrice).toFixed(2));	
			$('#spaSubtotal'+itemId+'w'+warehouseId).text(parseFloat(Number(quantity) * Number(salePrice)).toFixed(2));
		//	$('#total').text(parseFloat(total).toFixed(2)+' Bs.');
			$('#total').text(parseFloat(getTotal()).toFixed(2)+' Bs.');
			$('#modalAddItem').modal('hide');
			highlightTemporally('#itemRow'+itemId+'w'+warehouseId);
		}
		if(OPERATION === 'DELETE'){					
			var itemIdForDelete = objectTableRowSelected.find('#txtItemId').val();  //
			//	this should be a function
			subtotal = $('#spaSubtotal'+itemIdForDelete+'w'+warehouseId).text();	
	//		total = parseFloat($('#total').text()) - Number(subtotal);
			//	this should be a function
	//		$('#total').text(parseFloat(total).toFixed(2)+' Bs.');			
			
			hideBittionAlertModal();
			
			objectTableRowSelected.fadeOut("slow", function() {
				$(this).remove();
			});
			itemsListWhenExistsItems();
			warehouseListWhenExistsItems();
			$('#countItems').text(arrayItemsAlreadySaved.length-1);	//because arrayItemsAlreadySaved updates after all is done
			$('#total').text(parseFloat(getTotal()-subtotal).toFixed(2)+' Bs.');
			
		}
		showGrowlMessage('ok', 'Cambios guardados.');
	}
	
	function setOnApproved(DATA, STATE, ACTION){
		$('#txtCode').val(DATA[2]);
		$('#txtGenericCode').val(DATA[3]);
		$('#btnApproveState, #btnLogicDeleteState, #btnSaveAll, .columnItemsButtons').hide();
		$('#btnCancellState').show();
		$('#txtCode, #txtNoteCode, #txtDate, #cbxCustomers, #cbxEmployees, #cbxTaxNumbers, #cbxSalesman, #txtDescription, #txtExRate').attr('disabled','disabled');
		if ($('#btnAddItem').length > 0){//existe
			$('#btnAddItem').hide();
		}
		
//		if (ACTION === 'save_in' || ACTION === 'save_out'){
//			$('#cbxMovementTypes').attr('disabled','disabled');
//		}else{
//			$('#txtDocumentCode').attr('disabled','disabled');
//		}
//		if(ACTION === 'save_warehouses_transfer'){
//			$('#cbxWarehouses2').attr('disabled','disabled');
//		}
		changeLabelDocumentState(STATE); //#UNICORN
		showGrowlMessage('ok', 'Aprobado.');
	}
	
	function setOnCancelled(STATE){
		$('#btnCancellState').hide();
		changeLabelDocumentState(STATE); //#UNICORN
		showGrowlMessage('ok', 'Cancelado.');
	}
	
	function ajax_save_movement(OPERATION, STATE, objectTableRowSelected, arrayForValidate){//SAVE_IN/ADD/PENDANT
		var ACTION = arr[3];
		var dataSent = setOnData(ACTION, OPERATION, STATE, objectTableRowSelected, arrayForValidate);
		//Ajax Interaction	
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_save_movement",//saveSale			
            data:dataSent,
            beforeSend: showProcessing(),
            success: function(data){
				$('#boxMessage').html('');//this for order goes here
				$('#processing').text('');//this must go at the begining not at the end, otherwise, it won't work when validation is send
				var dataReceived = data.split('|');
				//////////////////////////////////////////
				if(dataReceived[0] === 'NOTE_APPROVED' || dataReceived[0] === 'NOTE_CANCELLED'){
						var arrayItemsStocks = dataReceived[3].split(',');
						updateMultipleStocks(arrayItemsStocks, 'spaStock');//What is this for???????????
				}
				switch(dataReceived[0]){
					case 'NOTE_PENDANT':
						setOnPendant(dataReceived, ACTION, OPERATION, STATE, objectTableRowSelected, dataSent['warehouseId'], dataSent['warehouse'], dataSent['itemId'], dataSent['itemCodeName'], dataSent['salePrice'], dataSent['stock'], dataSent['quantity'], dataSent['subtotal']);
						break;
					case 'NOTE_APPROVED':
						setOnApproved(dataReceived, STATE, ACTION);
						break;
					case 'NOTE_CANCELLED':
						setOnCancelled(STATE);
						break;
					case 'SINVOICE_PENDANT':
						setOnPendant(dataReceived, ACTION, OPERATION, STATE, objectTableRowSelected, dataSent['warehouseId'], dataSent['warehouse'], dataSent['itemId'], dataSent['itemCodeName'], dataSent['salePrice'], dataSent['stock'], dataSent['quantity'], dataSent['subtotal']);
						break;
					case 'SINVOICE_APPROVED':
						setOnApproved(dataReceived, STATE, ACTION);
						break;
					case 'SINVOICE_CANCELLED':
						setOnCancelled(STATE);
						break;
					case 'VALIDATION':
						setOnValidation(dataReceived, ACTION);
						break;
					case 'ERROR':
						setOnError();
						break;
				}
			},
			error:function(data){
				$('#boxMessage').html(''); 
				$('#processing').text(''); 
				setOnError();
			}
        });
	}
	
	function updateMultipleStocks(arrayItemsStocks, controlName){
		var auxItemsStocks = [];
		for(var i=0; i<arrayItemsStocks.length; i++){
			auxItemsStocks = arrayItemsStocks[i].split('=>');//  item5=>9stock
			$('#'+controlName+auxItemsStocks[0]).text(auxItemsStocks[1]);  //update only if quantities are APPROVED
		}
	}
	
	// Triggered when Guardar Modal button is pressed
	function addItem(){
//		var warehouseId = $('#cbxModalWarehouses').val();		
		var warehouse = $('#cbxModalWarehouses option:selected').text();
//		var itemId = $('#cbxModalItems').val();
		var itemCodeName = $('#cbxModalItems option:selected').text();
		var salePrice = $('#txtModalPrice').val();
//		var stock = $('#txtModalStock').val();
		var quantity = $('#txtModalQuantity').val();
//		var cifPrice = 0.00;	//temp var
//		var exCifPrice = 0.00;	//temp var
		
//		var subtotal = Number(quantity) * Number(salePrice);
//		var total = parseFloat($('#total').text()) + Number(subtotal);

		var error = validateItem(warehouse, itemCodeName, salePrice, quantity); 
		if(error == ''){
			if(arr[3] == 'save_order'){
				ajax_save_movement('ADD', 'NOTE_PENDANT', '', []);
			}
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('ADD', 'SINVOICE_PENDANT', '', []);
			}
//			var total = getTotal()
//			$('#total').text(parseFloat(total).toFixed(2)+' Bs.');
		}else{
			$('#boxModalValidateItem').html('<ul>'+error+'</ul>');
		}
	}
	
	function editItem(){
//		var itemId = $('#cbxModalItems').val();
		var warehouse = $('#cbxModalWarehouses option:selected').text();
		var itemCodeName = $('#cbxModalItems option:selected').text();	
		var salePrice = $('#txtModalPrice').val();
		var quantity = $('#txtModalQuantity').val();
//		var warehouseId = $('#cbxModalWarehouses').val();		
		
//		var stock = $('#txtModalStock').val();
//		var cifPrice = $('#txtCifPrice').val();
//		var exCifPrice = $('#txtCifExPrice').val();
//		
//		var subtotal = $('#spaSubtotal'+itemId).text();
//		var total = parseFloat($('#total').text()) + Number(subtotal);
		
		var error = validateItem(warehouse, itemCodeName, salePrice, quantity); 
		if(error == ''){
			if(arr[3] == 'save_order'){
				ajax_save_movement('EDIT', 'NOTE_PENDANT', '', []);
			}
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('EDIT', 'SINVOICE_PENDANT', '', []);
			}
//			$('#spaQuantity'+itemId).text(parseInt(quantity,10));
//			$('#spaSalePrice'+itemId).text(parseFloat(salePrice).toFixed(2));
//			
//			$('#spaWarehouse'+itemId).text(warehouse);
//			$('#txtWarehouseId'+itemId).val(warehouseId); ////////////////
//			$('#spaStock'+itemId).text(stock);
//			
//			$('#spaSubtotal'+itemId).text(parseFloat(subtotal).toFixed(2));
//			$('#modalAddItem').modal('hide');
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
	
	function addPay(){
		var payId = $('#cbxModalPays').val();
		var payCodeName = $('#cbxModalPays option:selected').text();
		var payDate = $('#txtModalDate').val();
		var payDueDate = $('#txtModalDueDate').val();
		var amount = $('#txtModalPaidAmount').val();
		var description = $('#txtModalDescription').val();
		var state = $('#txtModalState').val();
		var debtAmount = 0;
//	var subtotal = ((quantity) * (price));
		var error = validatePay(payCodeName, parseFloat(amount).toFixed(2)/*, ''*/); 
		if(error == ''){
			
			createRowPayTable(payId, payCodeName, payDate, payDueDate, parseFloat(amount).toFixed(2), parseFloat(debtAmount).toFixed(2), description, state/*, subtotal*/);
			createEventClickEditPayButton(payId);
			createEventClickDeletePayButton(payId);
			arrayPaysAlreadySaved.push(payId);  //push into array of the added item
			$('#modalAddPay').modal('hide');
		}else{
			$('#boxModalValidatePay').html('<ul>'+error+'</ul>');
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
	
	function getTotal(){
		var arrayAux = [];
		var total = 0;
		arrayAux = getItemsDetails();
		if(arrayAux[0] != 0){
			for(var i=0; i< arrayAux.length; i++){
				 var salePrice = (arrayAux[i]['sale_price']);
				 var quantity = (arrayAux[i]['quantity']);
				 total = total + (salePrice*quantity);
			}
		}
		return total; 	
	}
	
	//get all items for save a purchase
	function getItemsDetails(){		
		var arrayItemsDetails = [];
		var itemId = '';
		var itemSalePrice = '';
		var itemQuantity = '';
		var itemWarehouseId = '';
//		var itemCifPrice = '';
//		var itemExCifPrice = '';
var exRate = $('#txtExRate').val();
	
		var itemExSalePrice = '';	//??????????????????????
		
		$('#tablaItems tbody tr').each(function(){		
			itemId = $(this).find('#txtItemId').val();
			itemWarehouseId = $(this).find('#txtWarehouseId'+itemId).val();
			itemSalePrice = $(this).find('#spaSalePrice'+itemId+'w'+itemWarehouseId).text();
			itemQuantity = $(this).find('#spaQuantity'+itemId+'w'+itemWarehouseId).text();
			
			
//			itemCifPrice = $(this).find('#txtCifPrice').val();
//			itemExCifPrice = $(this).find('#txtCifExPrice').val();
/*			if ($('#spaQuantityDocument'+itemId).length > 0){//exists
				itemQuantityDocument = $(this).find('#spaQuantityDocument'+itemId).text();
			}
*/			itemExSalePrice = itemSalePrice / exRate;//?????????????????????????
			arrayItemsDetails.push({'inv_item_id':itemId, 'sale_price':itemSalePrice, 'quantity':itemQuantity, 'inv_warehouse_id':itemWarehouseId, 'ex_sale_price':parseFloat(itemExSalePrice).toFixed(2)});
			
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
	
	function getPaysDetails(){		
		var arrayPaysDetails = [];
		var payId = '';
		var payDate = '';
		var payDueDate = '';
		var payAmount = '';
//		var payDebtAmount = '';
		var payDescription = '';
		var payState = '';
//		var itemQuantity = '';
//		var itemQuantityDocument = '';
		
		$('#tablaPays tbody tr').each(function(){		
			payId = $(this).find('#txtPayId').val();
		//	costAmount = $(this).find('#spaAmount'+costId).text();
			payDate = $(this).find('#spaDate'+payId).text();
			payDueDate = $(this).find('#spaDueDate'+payId).text();
			payAmount = $(this).find('#spaPaidAmount'+payId).text();
//			payDebtAmount = $(this).find('#spaDebtAmount'+payId).text();
			payDescription = $(this).find('#spaDescription'+payId).text();
			payState = $(this).find('#spaState'+payId).text();
//			itemQuantity = $(this).find('#spaQuantity'+itemId).text();
	
//			if ($('#spaQuantityDocument'+itemId).length > 0){//exists
//				itemQuantityDocument = $(this).find('#spaQuantityDocument'+itemId).text();
//			}
			
			arrayPaysDetails.push({'pur_payment_type_id':payId, 'date':payDate, 'due_date':payDueDate, 'amount':payAmount,'description':payDescription, 'lc_state':payState  /*, 'quantity':itemQuantity, 'quantity_document':itemQuantityDocument*//*, 'stock2':itemStock2*/});
			
		});
		
		if(arrayPaysDetails.length == 0){  //For fix undefined index
			arrayPaysDetails = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
		}
		
		return arrayPaysDetails; 		
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
//		var arrayCostsDetails = [];
//		arrayCostsDetails = getCostsDetails();
//		var arrayPaysDetails = [];
//		arrayPaysDetails = getPaysDetails();
		var error = validateBeforeSaveAll(arrayItemsDetails);
		if( error == ''){
			if(arr[3] == 'save_order'){
				ajax_save_movement('DEFAULT', 'NOTE_PENDANT', '', []);
			}
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('DEFAULT', 'SINVOICE_PENDANT', '', []);
			}
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
	}
	
	// (AEA Ztep 2) action when button Aprobar Entrada Almacen is pressed
	function changeStateApproved(){
		showBittionAlertModal({content:'Al APROBAR este documento ya no se podrá hacer más modificaciones. ¿Está seguro?'});
		$('#bittionBtnYes').click(function(){
			var arrayForValidate = [];
			arrayForValidate = getItemsDetails();
			var error = validateBeforeSaveAll(arrayForValidate);
			if( error === ''){
				if(arr[3] == 'save_order'){
					ajax_save_movement('DEFAULT', 'NOTE_APPROVED', '', arrayForValidate);
				}
				if(arr[3] == 'save_invoice'){
					ajax_save_movement('DEFAULT', 'SINVOICE_APPROVED', '', arrayForValidate);
				}
			}else{
				$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
			}
			hideBittionAlertModal();
			
//			var arrayItemsDetails = [];
//			arrayItemsDetails = getItemsDetails();
//			var arrayCostsDetails = [];
//			arrayCostsDetails = getCostsDetails();
//			var arrayPaysDetails = [];
//			arrayPaysDetails = getPaysDetails();
//			var error = validateBeforeSaveAll(arrayItemsDetails);
//			if( error === ''){
//				if(arr[3] == 'save_order'){
//					ajax_change_state_approved_movement_in(arrayItemsDetails);
//				}
//				if(arr[3]=='save_invoice'){
//					ajax_change_state_approved_invoice(arrayItemsDetails,/* arrayCostsDetails,*/ arrayPaysDetails);
//				}
//			}else{
//				$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
//			}
//			hideBittionAlertModal();
		});
	}
	// (CEA Ztep 2) action when button Cancelar Entrada Almacen is pressed
	function changeStateCancelled(){
		showBittionAlertModal({content:'Al CANCELAR este documento ya no será válido y no habrá marcha atrás. ¿Está seguro?'});
		$('#bittionBtnYes').click(function(){
//			var arrayItemsDetails = [];
//			arrayItemsDetails = getItemsDetails();
//			var arrayCostsDetails = [];
//			arrayCostsDetails = getCostsDetails();
//			var arrayPaysDetails = [];
//			arrayPaysDetails = getPaysDetails();
			var arrayForValidate = [];
			arrayForValidate = getItemsDetails();
			if(arr[3] == 'save_order'){
				ajax_save_movement('DEFAULT', 'NOTE_CANCELLED', '', arrayForValidate);
			}
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('DEFAULT', 'SINVOICE_CANCELLED', '', arrayForValidate);
			}
			hideBittionAlertModal();
			
//			if(arr[3] == 'save_order' /*|| arr[3] == 'save_purchase_in'*/){
//				ajax_change_state_cancelled_movement_in(arrayItemsDetails);
//			}
//			if(arr[3]=='save_invoice'){
//				ajax_change_state_cancelled_invoice(arrayItemsDetails/*, arrayCostsDetails*/, arrayPaysDetails);			
//			}
//			hideBittionAlertModal();
		});
	}
	
	
	function changeStateLogicDeleted(){
		showBittionAlertModal({content:'¿Está seguro de eliminar este documento en estado Pendiente?'});
		$('#bittionBtnYes').click(function(){
			var purchaseId = $('#txtPurchaseIdHidden').val();
			var genCode = $('#txtGenericCode').val();
//			var purchaseId2=0;
			var type;
//			var type2=0;
			var index;
			switch(arr[3]){
				case 'save_order':
//					purchaseId2 = Number(purchaseId) + 1;
					index = 'index_order';
					type = 'NOTE_LOGIC_DELETED';
//					type2 = 'DRAFT';
					break;	
				case 'save_invoice':
					index = 'index_invoice';
					type = 'SINVOICE_LOGIC_DELETED';
					break;	
//				case 'save_purchase_in':
//					index = 'index_purchase_in';
//					break;	
//				case 'save_sale_out':
//					index = 'index_sale_out';
//					break;	
//				case 'save_warehouses_transfer':
//					index = 'index_warehouses_transfer';
//					code = $('#txtDocumentCode').val();
//					type = 'transfer';
//					break;	
			}
			ajax_logic_delete(purchaseId,/* purchaseId2, */type,/* type2,*/ index, genCode);
			hideBittionAlertModal();
		});
	}
	//************************************************************************//
	//////////////////////////////////END-FUNCTIONS//////////////////////
	//************************************************************************//
	
	
	
	
	//************************************************************************//
	//////////////////////////////////BEGIN-CONTROLS EVENTS/////////////////////
	//************************************************************************//
	//Validate only numbers
	$('#txtModalAmount').keydown(function(event) {
			validateOnlyIntegers(event);			
	});
	//Validate only numbers
	$('#txtModalQuantity').keydown(function(event) {
			validateOnlyIntegers(event);			
	});
	
	function validateOnlyIntegers(event){
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
	
	//Calendar script
	$("#txtDate").datepicker({
	  showButtonPanel: true
	});
	
	$('#txtDate').focusout(function() {
			ajax_update_ex_rate();			
	});
	
	function ajax_update_ex_rate(){
		$.ajax({
		    type:"POST",
		    url:moduleController + "ajax_update_ex_rate",			
		    data:{date: $("#txtDate").val()},
		    beforeSend: showProcessing(),
				success:function(data){
					$("#processing").text("");
					$("#boxExRate").html(data);
				}
		});
    }
	
	$("#txtModalDate").datepicker({
	  showButtonPanel: true
	});
	
	$("#txtModalDueDate").datepicker({
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
		itemsListWhenExistsItems();			//NEEDS TO BE RUN BEFORE MODAL TO UPDATE ITEMS LIST BY WAREHOUSE
			warehouseListWhenExistsItems();	//NEEDS TO BE RUN BEFORE MODAL TO UPDATE ITEMS LIST BY WAREHOUSE
		initiateModalAddItem();
		return false; //avoid page refresh
	});
	
	//function when button Guardar on the modal is pressed
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
	
	//function triggered when PAYS plus icon is clicked
	$('#btnAddPay').click(function(){
		initiateModalAddPay();
		return false; //avoid page refresh
	});
	
	$('#btnModalAddPay').click(function(){
		addPay();
		return false; //avoid page refresh
	});
	////////////////
	
	// action when button Aprobar Entrada is pressed
	$('#btnApproveState').click(function(){
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
	
	$('#cbxCustomers').select2();
	
	$('#cbxSuppliers').data('pre', $(this).val());
	$('#cbxSuppliers').change(function(){
	var supplier = $(this).data('pre');
		deleteList(supplier);
	$(this).data('pre', $(this).val());
		return false; //avoid page refresh
	});
  
	//accion al seleccionar un cliente
	$('#cbxCustomers').change(function(){
        ajax_list_controllers_inside();		
    });
	
	function ajax_list_controllers_inside(){
        $.ajax({
            type:"POST",
            url:moduleController + "ajax_list_controllers_inside",			
            data:{customer: $("#cbxCustomers").val()},
            beforeSend: showProcessing(),
            //success: showControllersInside
			success:function(data){
				$("#processing").text("");
		        $("#boxControllers").html(data);
				//showControllersInside(data);
//				$('#controllers').bind("change",function(){
//					 ajax_list_actions_inside();
//				});
			}
        });
    }
	
//	function showControllersInside(data){
//        $("#processing").text("");
//        $("#boxControllers").html(data);
//    }

	$('#txtDate').keypress(function(){return false;});
	$('#txtModalDate').keypress(function(){return false;});
	$('#txtModalDueDate').keypress(function(){return false;});
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
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_save_movement_in",			
            data:{arrayItemsDetails: arrayItemsDetails 
				  ,purchaseId:$('#txtPurchaseIdHidden').val()
				  ,date:$('#txtDate').val()
				  ,customer:$('#cbxCustomers').val()
				  ,employee:$('#cbxEmployees').val()
				  ,taxNumber:$('#cbxTaxNumbers').val()
				  ,salesman:$('#cbxSalesman').val()
				  ,note_code:$('#txtNoteCode').val()
				  ,description:$('#txtDescription').val()
				  ,exRate:$('#txtExRate').val()
			  },
            beforeSend: showProcessing(),
            success: function(data){
				var arrayCatch = data.split('|');

				if(arrayCatch[0] == 'insertado'){ 
					$('#txtCode').val(arrayCatch[1]);
//					$('#columnStatePurchase').css('background-color','#F99C17');
//					$('#columnStatePurchase').text('Orden Pendiente');
changeLabelDocumentState('NOTE_PENDANT'); //#UNICORN
					$('#btnApproveState').show();
$('#btnLogicDeleteState').show();
					$('#txtPurchaseIdHidden').val(arrayCatch[2]);
					$('#txtGenericCode').val(arrayCatch[3]);
		//			$('#cbxSuppliers').attr('disabled','disabled');
		$('#txtExRate').removeAttr('disabled');
				}
		
				//update items stocks
				//var arrayItemsStocks = arrayCatch[1].split(',');
				//updateMultipleStocks(arrayItemsStocks, 'spaStock');
				$('#btnPrint').show();
				$('#boxMessage').html('');
				showGrowlMessage('ok', 'Cambios guardados.');
				
				$('#processing').text('');
/*					$('#boxMessage').html('<div class="alert alert-success">\n\
					<button type="button" class="close" data-dismiss="alert">&times;</button>Guardado con exito<div>');
					$('#processing').text('');*/
			},
			error:function(data){
				//$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text('');
			}
        });
	}
	
	function ajax_save_invoice(arrayItemsDetails,/* arrayCostsDetails,*/ arrayPaysDetails ){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_save_invoice",			
            data:{arrayItemsDetails: arrayItemsDetails 
				 // ,arrayCostsDetails: arrayCostsDetails	
				  ,arrayPaysDetails: arrayPaysDetails	
				  ,purchaseId:$('#txtPurchaseIdHidden').val()
				  ,date:$('#txtDate').val()
				  ,customer:$('#cbxCustomers').val()
				  ,employee:$('#cbxEmployees').val()
				  ,taxNumber:$('#cbxTaxNumbers').val()
				  ,salesman:$('#cbxSalesman').val()
				  ,note_code:$('#txtNoteCode').val()
				  ,description:$('#txtDescription').val()
				  ,exRate:$('#txtExRate').val()
			  },
            beforeSend: showProcessing(),
            success: function(data){
				var arrayCatch = data.split('|');

				if(arrayCatch[0] == 'insertado'){ 
					$('#txtCode').val(arrayCatch[1]);

changeLabelDocumentState('SINVOICE_PENDANT'); //#UNICORN
					$('#btnApproveState').show();
					$('#txtPurchaseIdHidden').val(arrayCatch[2]);
					$('#txtGenericCode').val(arrayCatch[3]);
					$('#txtExRate').removeAttr('disabled');
				}
		
				$('#btnPrint').show();
				$('#boxMessage').html('');
				showGrowlMessage('ok', 'Cambios guardados.');
				$('#processing').text('');
/*					$('#boxMessage').html('<div class="alert alert-success">\n\
					<button type="button" class="close" data-dismiss="alert">&times;</button>Guardado con exito<div>');
					$('#processing').text('');*/
			},
			error:function(data){
				//$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text('');
			}
        });
	}
	
	// (AEA Ztep 3) action when button Aprobar Entrada Almacen is pressed
	function ajax_change_state_approved_movement_in(arrayItemsDetails){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_change_state_approved_movement_in",			
            data:{arrayItemsDetails: arrayItemsDetails 
				  ,purchaseId:$('#txtPurchaseIdHidden').val()
				  ,date:$('#txtDate').val()
				  ,customer:$('#cbxCustomers').val()
				  ,employee:$('#cbxEmployees').val()
				  ,taxNumber:$('#cbxTaxNumbers').val()
				  ,salesman:$('#cbxSalesman').val()	
				  ,description:$('#txtDescription').val()
				  ,exRate:$('#txtExRate').val()
				  ,note_code:$('#txtNoteCode').val()
				  ,genericCode:$('#txtGenericCode').val()
			  },
            beforeSend: showProcessing(),
            success: function(data){			
				var arrayCatch = data.split('|');
				if(arrayCatch[0] == 'aprobado'){
//					$('#columnStatePurchase').css('background-color','#54AA54');
//					$('#columnStatePurchase').text('Orden Aprobada');

changeLabelDocumentState('NOTE_APPROVED'); //#UNICORN
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
					
					$('#boxMessage').html('');
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
	
	function ajax_change_state_approved_invoice(arrayItemsDetails,/* arrayCostsDetails,*/ arrayPaysDetails){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_change_state_approved_invoice",			
            data:{arrayItemsDetails: arrayItemsDetails 
			//	,arrayCostsDetails: arrayCostsDetails
				,arrayPaysDetails: arrayPaysDetails
				  ,purchaseId:$('#txtPurchaseIdHidden').val()
				  ,date:$('#txtDate').val()
				  ,customer:$('#cbxCustomers').val()
				  ,employee:$('#cbxEmployees').val()
				  ,taxNumber:$('#cbxTaxNumbers').val()
				  ,salesman:$('#cbxSalesman').val()	
				  ,description:$('#txtDescription').val()
				  ,exRate:$('#txtExRate').val()
				  ,note_code:$('#txtNoteCode').val()
				  ,genericCode:$('#txtGenericCode').val()
			  },
            beforeSend: showProcessing(),
            success: function(data){			
				var arrayCatch = data.split('|');
				if(arrayCatch[0] == 'aprobado'){
//					$('#columnStatePurchase').css('background-color','#54AA54');
//					$('#columnStatePurchase').text('Orden Aprobada');

changeLabelDocumentState('SINVOICE_APPROVED'); //#UNICORN
					$('#btnApproveState').hide();
					$('#btnCancellState').show();
					$('#btnSaveAll').hide();
					$('#btnLogicDeleteState').hide();
	//				$('#btnAddMovementType').hide();
					if ($('#btnAddItem').length > 0){//existe
						$('#btnAddItem').hide();
					}
					if ($('#btnAddCost').length > 0){//existe
						$('#btnAddCost').hide();
					}
						$('.columnItemsButtons').hide();
//$('.columnCostsButtons').hide();

	//				if ($('#txtDocumentCode').length > 0){//existe
	//					$('#txtDocumentCode').attr('disabled','disabled');
	//				}
					$('#txtDate').attr('disabled','disabled');
					$('#txtCode').attr('disabled','disabled');
					//$('#cbxSuppliers').attr('disabled','disabled');
					$('#txtDescription').attr('disabled','disabled');
					$('#boxMessage').html('');
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

changeLabelDocumentState('NOTE_CANCELLED'); //#UNICORN
					$('#btnCancellState').hide();
					
					showGrowlMessage('ok', 'Entrada cancelada.');
					$('#boxMessage').html('');
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
				//$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text('');
			}
        });
	}
	
	function ajax_change_state_cancelled_invoice(arrayItemsDetails, /*arrayCostsDetails,*/ arrayPaysDetails){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_change_state_cancelled_invoice",			
            data:{arrayItemsDetails: arrayItemsDetails 
			//	  ,arrayCostsDetails: arrayCostsDetails
				  ,arrayPaysDetails: arrayPaysDetails
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

changeLabelDocumentState('NOTE_CANCELLED'); //#UNICORN
					$('#btnCancellState').hide();
					showGrowlMessage('ok', 'Entrada cancelada.');
					$('#boxMessage').html('');
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
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text('');
			}
        });
	}
	
	function ajax_logic_delete(purchaseId,/* purchaseId2, */type, /*type2,*/ index, genCode){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_logic_delete",			
            data:{purchaseId: purchaseId
			//	,purchaseId2: purchaseId2
				,type: type
			//	,type2: type2
				,genCode: genCode
			},
            success: function(data){
				if(data === 'success'){
					showBittionAlertModal({content:'Se eliminó el documento en estado Pendiente', btnYes:'Aceptar', btnNo:''});
					$('#bittionBtnYes').click(function(){
						window.location = moduleController + index;
					});
					
				}else{
					showGrowlMessage('error', 'Vuelva a intentarlo.');
				}
			},
			error:function(data){
				showGrowlMessage('error', 'Vuelva a intentarlo.');
			}
        });
	}
	
	//Get prices and stock for the fist item when inititates modal
	function ajax_initiate_modal_add_item_in(itemsAlreadySaved, warehouseItemsAlreadySaved){
		 $.ajax({
            type:"POST",
            url:moduleController + "ajax_initiate_modal_add_item_in",			
			data:{itemsAlreadySaved: itemsAlreadySaved,
				warehouseItemsAlreadySaved: warehouseItemsAlreadySaved},				
            beforeSend: showProcessing(),
            success: function(data){
				$('#processing').text('');
				$('#boxModalInitiateItemPrice').html(data);
				$('#txtModalQuantity').val('');  
				initiateModal()
				
				$('#cbxModalWarehouses').bind("change",function(){ //must be binded 'cause dropbox is loaded by a previous ajax'
					//este es para los items precio y stock
					ajax_update_items_modal(itemsAlreadySaved, warehouseItemsAlreadySaved);
				});
				
				$('#cbxModalItems').bind("change",function(){ //must be binded 'cause dropbox is loaded by a previous ajax'
					//ajax_update_price_modal
					ajax_update_stock_modal();
					//ajax_update_stock_modal
					ajax_update_stock_modal_1();
				});
				$('#cbxModalItems').select2();
				
				$('#txtModalStock').keypress(function(){return false;});//find out why this is necessary
			},
			error:function(data){
				showGrowlMessage('error', 'Vuelva a intentarlo.');
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
	
	function ajax_initiate_modal_add_pay(paysAlreadySaved){
		 $.ajax({
            type:"POST",
            url:moduleController + "ajax_initiate_modal_add_pay",			
  /*data*/  data:{paysAlreadySaved: paysAlreadySaved/*, supplier: $('#cbxSuppliers').val()*//*, transfer:transfer, warehouse2:warehouse2*/},
            beforeSend: showProcessing(),
            success: function(data){
				$('#processing').text('');
				$('#boxModalInitiatePay').html(data);
				$('#txtModalDate').val('');  
				$('#txtModalDueDate').val('');  
				$('#txtModalPaidAmount').val('');  
				$('#txtModalDescription').val('');  
				$('#txtModalState').val(''); 
				initiateModalPay()
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
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	//Update stock
	function ajax_update_stock_modal_1(){ 
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_update_stock_modal_1",			
            data:{item: $('#cbxModalItems').val(),
				warehouse: $('#cbxModalWarehouses').val()},
            beforeSend: showProcessing(),
            success: function(data){
				$('#processing').text("");
				$('#boxModalStock').html(data);
				$('#txtModalStock').bind("keypress",function(){ //must be binded 'cause input is re-loaded by a previous ajax'
					return false;	//find out why this is necessary
				});
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	function ajax_update_items_modal(itemsAlreadySaved, warehouseItemsAlreadySaved){ 
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_update_items_modal",			
            data:{itemsAlreadySaved: itemsAlreadySaved,
				warehouseItemsAlreadySaved: warehouseItemsAlreadySaved,
				warehouse: $('#cbxModalWarehouses').val()},
            beforeSend: showProcessing(),
            success: function(data){
				$('#processing').text("");
				$('#boxModalItemPriceStock').html(data);
			
				$('#cbxModalItems').bind("change",function(){ //must be binded 'cause dropbox is loaded by a previous ajax'
					//este es para el stock
					ajax_update_stock_modal_1();
					//este es para el precio
					ajax_update_stock_modal();
				});
				$('#cbxModalItems').select2();	
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
