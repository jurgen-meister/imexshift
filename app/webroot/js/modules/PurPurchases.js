$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation

	var globalPeriod = $('#globalPeriod').text(); // this value is obtained from the main template.

	var arrayItemsAlreadySaved = []; 
	var itemsCounter = 0;
	var arraySupplierItemsAlreadySaved = []; 
	startEventsWhenExistsItems();
	
	var arrayCostsAlreadySaved = []; 
	startEventsWhenExistsCosts();
	
	var arrayPaysAlreadySaved = []; 
	startEventsWhenExistsPays();

	var payDebt = 0;
	startEventsWhenExistsDebts();
	
	function startEventsWhenExistsDebts(){		
		payDebt =0;
		var	payPaid = getTotalPay();
		var payTotal = getTotal();
		payDebt = Number(payTotal) - Number(payPaid);
		return payDebt
	}
	
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
	
	//gets a list of the supplier ids in the document details
	function suppliersListWhenExistsItems(){
		var arrayAux = [];
		arraySupplierItemsAlreadySaved = [];
		arrayAux = getItemsDetails();
		if(arrayAux[0] != 0){
			for(var i=0; i< arrayAux.length; i++){
				 arraySupplierItemsAlreadySaved[i] = arrayAux[i]['inv_supplier_id'];
			}
		}
		if(arraySupplierItemsAlreadySaved.length == 0){  //For fix undefined index
			arraySupplierItemsAlreadySaved = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
		}
		
		return arraySupplierItemsAlreadySaved; //NOT SURE TO PUT THIS LINE	
	}

	function startEventsWhenExistsItems(){
		var arrayAux = [];
		arrayAux = getItemsDetails();
		if(arrayAux[0] != 0){
			for(var i=0; i< arrayAux.length; i++){
				arrayItemsAlreadySaved[i] = arrayAux[i]['inv_item_id'];
				arraySupplierItemsAlreadySaved[i] = arrayAux[i]['inv_supplier_id'];
				createEventClickEditItemButton(arrayAux[i]['inv_item_id'],arrayAux[i]['inv_supplier_id']);
				createEventClickDeleteItemButton(arrayAux[i]['inv_item_id'],arrayAux[i]['inv_supplier_id']);	
				itemsCounter = itemsCounter + 1;  //like this cause iteration something++ apparently not supported by javascript, gave me NaN error				 
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
		/*else{
			alert('esta vacio');
		}*/
	}
	
	//When exist pays, it starts its events and fills arrayPaysAlreadySaved
	function startEventsWhenExistsPays(){		/*STANDBY*/
		var arrayAux = [];
		arrayAux = getPaysDetails();
		if(arrayAux[0] != 0){
			for(var i=0; i< arrayAux.length; i++){
				arrayPaysAlreadySaved[i] = arrayAux[i]['date'];
				createEventClickEditPayButton(arrayAux[i]['date']);
				createEventClickDeletePayButton(arrayAux[i]['date']);			 
			}
		}
		/*else{
			alert('esta vacio');
		}*/
	}
	//validates before add item quantity
	function validateItem(supplier, item, quantity, exFobPrice){
		var error = '';
		if(supplier === ''){error+='<li>El campo "Proveedor" no puede estar vacio</li>';}
		if(item === ''){error+='<li>El campo "Item" no puede estar vacio</li>';}
		if(quantity === ''){
			error+='<li>El campo "Cantidad" no puede estar vacio</li>'; 
		}else{
			if(parseInt(quantity, 10) === 0){
				error+='<li>El campo "Cantidad" no puede ser cero</li>'; 
			}
		}
		if(exFobPrice === ''){
			error+='<li>El campo "Precio Unitario" no puede estar vacio</li>'; 
		}/*else{
//o si puede ser cero el precio?			
			if(parseFloat(price).toFixed(2) == 0){
				error+='<li>El campo "P/U" no puede ser cero</li>'; 
			}
		}*/		
		return error;
	}
	
	function validateCost(costCodeName, costExAmount){
		var error = '';		
		if(costExAmount == ''){
			error+='<li>El campo "Monto" no puede estar vacio</li>'; 
		}else{
//o si puede ser cero el precio?			
			if(parseFloat(costExAmount).toFixed(2) == 0){
				error+='<li>El campo "Monto" no puede ser cero</li>'; 
			}
		}
		if(costCodeName == ''){error+='<li>El campo "Costo" no puede estar vacio</li>';}
		return error;
	}
	
	function validateEditPay(payDate, payAmount, payHiddenAmount){
		var error = '';
		if(payDate == ''){
			error+='<li>El campo "Fecha" no puede estar vacio</li>'; 
		}		
		if(payAmount == ''){
			error+='<li>El campo "Monto a Pagar" no puede estar vacio</li>'; 
		}else{
			var payDebt2 = Number(payDebt) + Number(payHiddenAmount);
			if(parseFloat(payAmount).toFixed(2) == 0){
				error+='<li>El campo "Monto a Pagar" no puede ser cero</li>'; 
			}else if (payAmount > payDebt2){
				error+='<li>El campo "Monto a Pagar" no puede ser mayor a la deuda</li>'; 
			}
		}
		return error;
	}
	
	function validateAddPay(payDate, payAmount){
		var error = '';
		if(payDate == ''){
			error+='<li>El campo "Fecha" no puede estar vacio</li>'; 
		}else{
			var arrayAux = [];
			var myDate = payDate.split('/');
			var dateId = myDate[2]+"-"+myDate[1]+"-"+myDate[0];
			arrayAux = getPaysDetails();
			if(arrayAux[0] != 0){
				for(var i=0; i< arrayAux.length; i++){
					if(dateId == (arrayAux[i]['date'])){
						error+='<li>La "Fecha" ya existe</li>'; 
					}  
				}
			}
		}
		
		if(payAmount == ''){
			error+='<li>El campo "Monto a Pagar" no puede estar vacio</li>'; 
		}else{
			if(payAmount > payDebt){
				error+='<li>El campo "Monto a Pagar" no puede ser mayor a la deuda</li>'; 
			}
			if(parseFloat(payAmount).toFixed(2) == 0){
				error+='<li>El campo "Monto a Pagar" no puede ser cero</li>'; 
			}
		}
		return error;
	}
	
	function validateBeforeSaveAll(arrayItemsDetails){
		var error = '';
		var date = $('#txtDate').val();
		var dateYear = date.split('/');
		if(date === ''){	error+='<li> El campo "Fecha" no puede estar vacio </li>'; }
		if(dateYear[2] !== globalPeriod){	error+='<li> El año '+dateYear[2]+' de la fecha del documento no es valida, ya que se encuentra en la gestión '+ globalPeriod +'.</li>'; }
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
	
	function initiateModalPay(){
		$('#modalAddPay').modal({
					show: 'true',
					backdrop:'static'
		});
	}
	
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

	function validateOnlyFloatNumbers(event){
		// Allow backspace,	tab, decimal point
		if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 110 || event.keyCode == 190) {
			// let it happen, don't do anything
		}
		else {
			// Ensure that it is a number and stop the keypress
			if ( (event.keyCode < 96 || event.keyCode > 105) ) { //habilita keypad
				if ((event.keyCode < 48 || event.keyCode > 57 ) ) {
					
						event.preventDefault(); 					
					
				}
			}   
		}
	}
	
	function initiateModalAddItem(){
		var error = validateBeforeSaveAll([{0:0}]);//I send [{0:0}] 'cause it doesn't care to validate if arrayItemsDetails is empty or not
		if( error === ''){
			if(arrayItemsAlreadySaved.length == 0){  //For fix undefined index
				arrayItemsAlreadySaved = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
				arraySupplierItemsAlreadySaved = [0]
			}
			$('#btnModalAddItem').show();
			$('#btnModalEditItem').hide();
			$('#boxModalValidateItem').html('');//clear error message
			ajax_initiate_modal_add_item_in(arrayItemsAlreadySaved, arraySupplierItemsAlreadySaved);
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}	
	}
	
	function initiateModalAddCost(){
		var error = validateBeforeSaveAll([{0:0}]);//I send [{0:0}] 'cause it doesn't care to validate if arrayItemsDetails is empty or not
		if( error === ''){	
			if(arrayCostsAlreadySaved.length == 0){  //For fix undefined index
				arrayCostsAlreadySaved = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
			}
			$('#btnModalAddCost').show();
			$('#btnModalEditCost').hide();
			$('#boxModalValidateCost').html('');//clear error message
			ajax_initiate_modal_add_cost(arrayCostsAlreadySaved);
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}	
	}
	
	function initiateModalAddPay(){
		var error = validateBeforeSaveAll([{0:0}]);//I send [{0:0}] 'cause it doesn't care to validate if arrayItemsDetails is empty or not
		if( error === ''){	
			if(arrayPaysAlreadySaved.length === 0){  //For fix undefined index
				arrayPaysAlreadySaved = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
			}
			$('#btnModalAddPay').show();
			$('#btnModalEditPay').hide();
			$('#boxModalValidatePay').html('');//clear error message
			ajax_initiate_modal_add_pay(arrayPaysAlreadySaved, parseFloat(payDebt).toFixed(2));
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}	
	}
	
	function initiateModalEditItem(objectTableRowSelected){
		var error = validateBeforeSaveAll([{0:0}]);//I send [{0:0}] 'cause it doesn't care to validate if arrayItemsDetails is empty or not
		if( error === ''){
			var itemIdForEdit = objectTableRowSelected.find('#txtItemId').val();  
			var supplierIdForEdit = objectTableRowSelected.find('#txtSupplierId'+itemIdForEdit).val();
			$('#btnModalAddItem').hide();
			$('#btnModalEditItem').show();
			$('#boxModalValidatePay').html('');//clear error message
			$('#cbxModalSuppliers').empty();
			$('#cbxModalSuppliers').append('<option value="'+supplierIdForEdit+'">'+objectTableRowSelected.find('#spaSupplier'+itemIdForEdit).text()+'</option>');
			$('#cbxModalItems').empty();
			$('#cbxModalItems').append('<option value="'+itemIdForEdit+'">'+objectTableRowSelected.find('td:first').text()+'</option>');
			$('#txtModalPrice').val(objectTableRowSelected.find('#spaExFobPrice'+itemIdForEdit+'s'+supplierIdForEdit).text());
			$('#txtModalQuantity').val(objectTableRowSelected.find('#spaQuantity'+itemIdForEdit+'s'+supplierIdForEdit).text());
			initiateModal();
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}	
	}
	
	function initiateModalEditCost(objectTableRowSelected){
		var error = validateBeforeSaveAll([{0:0}]);//I send [{0:0}] 'cause it doesn't care to validate if arrayItemsDetails is empty or not
		if( error === ''){
			var costIdForEdit = objectTableRowSelected.find('#txtCostId').val();  //
			$('#btnModalAddCost').hide();
			$('#btnModalEditCost').show();
			$('#boxModalValidateCost').html('');//clear error message
			$('#txtModalCostExAmount').val(objectTableRowSelected.find('#spaCostExAmount'+costIdForEdit).text());
			$('#cbxModalCosts').empty();
			$('#cbxModalCosts').append('<option value="'+costIdForEdit+'">'+objectTableRowSelected.find('td:first').text()+'</option>');
			initiateModalCost();
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
	}
	
	function initiateModalEditPay(objectTableRowSelected){
		var error = validateBeforeSaveAll([{0:0}]);//I send [{0:0}] 'cause it doesn't care to validate if arrayItemsDetails is empty or not
		if( error === ''){
			var payIdForEdit = objectTableRowSelected.find('#txtPayDate').val();  //
			$('#btnModalAddPay').hide();
			$('#btnModalEditPay').show();
			$('#boxModalValidatePay').html('');//clear error message
			$('#txtModalDate').val(objectTableRowSelected.find('#spaPayDate'+payIdForEdit).text());
			$('#txtModalPaidAmount').val(objectTableRowSelected.find('#spaPayAmount'+payIdForEdit).text());
			$('#txtModalDescription').val(objectTableRowSelected.find('#spaPayDescription'+payIdForEdit).text());
			$('#txtModalAmountHidden').val(objectTableRowSelected.find('#spaPayAmount'+payIdForEdit).text());
			initiateModalPay();
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}	
	}
	
	function createEventClickEditItemButton(itemId, supplierId){
			$('#btnEditItem'+itemId+'s'+supplierId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr')
					initiateModalEditItem(objectTableRowSelected);
					return false; //avoid page refresh
			});
	}
	
	function createEventClickDeleteItemButton(itemId, supplierId){
		$('#btnDeleteItem'+itemId+'s'+supplierId).bind("click",function(e){ //must be binded 'cause loaded live with javascript'
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
					ajax_save_movement('DELETE', 'ORDER_PENDANT', objectTableRowSelected/*, []*/);
				}
				if(arr[3] == 'save_invoice'){
					ajax_save_movement('DELETE', 'PINVOICE_PENDANT', objectTableRowSelected/*, []*/);
				}
				return false; //avoid page refresh
			});
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
	}
	
	function deletePay(objectTableRowSelected){
		//var arrayPaysDetails = getPaysDetails();
		var error = validateBeforeSaveAll([{0:0}]);//Send [{0:0}] 'cause I won't use arrayItemsDetails classic validation, I will use it differently for this case (as done below)
		if( error === ''){
			showBittionAlertModal({content:'¿Está seguro de eliminar este pago?'});
			$('#bittionBtnYes').click(function(){
				ajax_save_movement('DELETE_PAY', 'PINVOICE_PENDANT', objectTableRowSelected/*, []*/);
				return false;
			});
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
	}
	
	function deleteCost(objectTableRowSelected){
		//var arrayCostsDetails = getCostsDetails();
		var error = validateBeforeSaveAll([{0:0}]);//Send [{0:0}] 'cause I won't use arrayItemsDetails classic validation, I will use it differently for this case (as done below)
		if( error === ''){
			showBittionAlertModal({content:'¿Está seguro de eliminar este costo?'});
			$('#bittionBtnYes').click(function(){
				ajax_save_movement('DELETE_COST', 'PINVOICE_PENDANT', objectTableRowSelected/*, []*/);
				return false;
			});
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
	}
	
//	function deleteList(supplier){
//		if ( $('#txtItemId').length ){
//		
//			if(confirm('Esta por cambiar de proveedor, esto borrara la lista de items esta seguro?')){	
//				$('#tablaItems tbody tr').each(function(){
//					var objectTableRowSelected = $('#txtItemId').closest('tr')
//					var itemIdForDelete = objectTableRowSelected.find('#txtItemId').val();  //
//					arrayItemsAlreadySaved = jQuery.grep(arrayItemsAlreadySaved, function(value){
//						return value != itemIdForDelete;
//					});
//					objectTableRowSelected.remove();
//				})				
//			}else{
//		//		alert(supplier);
//				$('#cbxSuppliers').val(supplier);
//			}
//		}
//	}
	
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
	
	function createEventClickEditPayButton(dateId){
			$('#btnEditPay'+dateId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr')
					startEventsWhenExistsDebts();
					initiateModalEditPay(objectTableRowSelected);
					return false; //avoid page refresh
			});
	}
	
	function createEventClickDeletePayButton(dateId){
		$('#btnDeletePay'+dateId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr')
					deletePay(objectTableRowSelected);
					return false; //avoid page refresh
		});
	}
	
	
	// (GC Ztep 3) function to fill Items list when saved in modal triggered by addItem()
	function createRowItemTable(itemId, itemCodeName, exFobPrice, quantity, supplier, supplierId, subtotal){
		var row = '<tr id="itemRow'+itemId+'s'+supplierId+'" >';
		row +='<td><span id="spaItemName'+itemId+'">'+itemCodeName+'</span><input type="hidden" value="'+itemId+'" id="txtItemId" ></td>';
		row +='<td><span id="spaExFobPrice'+itemId+'s'+supplierId+'">'+exFobPrice+'</span></td>';
		row +='<td><span id="spaQuantity'+itemId+'s'+supplierId+'">'+quantity+'</span></td>';
		row +='<td><span id="spaSupplier'+itemId+'">'+supplier+'</span><input type="hidden" value="'+supplierId+'" id="txtSupplierId'+itemId+'" ></td>';
		row +='<td><span id="spaSubtotal'+itemId+'s'+supplierId+'">'+subtotal+'</span></td>';
		row +='<td class="columnItemsButtons">';
		row +='<a class="btn btn-primary" href="#" id="btnEditItem'+itemId+'s'+supplierId+'" title="Editar"><i class="icon-pencil icon-white"></i></a> ';
		row +='<a class="btn btn-danger" href="#" id="btnDeleteItem'+itemId+'s'+supplierId+'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
		row +='</td>';
		row +='</tr>'
		$('#tablaItems tbody').prepend(row);
	}
	
	function createRowCostTable(costId, costCodeName, costExAmount){
		var row = '<tr id="costRow'+costId+'" >';
		row +='<td><span id="spaCostName'+costId+'">'+costCodeName+'</span><input type="hidden" value="'+costId+'" id="txtCostId" ></td>';
		row +='<td><span id="spaCostExAmount'+costId+'">'+costExAmount+'</span></td>';
		row +='<td class="columnCostsButtons">';
		row +='<a class="btn btn-primary" href="#" id="btnEditCost'+costId+'" title="Editar"><i class="icon-pencil icon-white"></i></a> ';
		row +='<a class="btn btn-danger" href="#" id="btnDeleteCost'+costId+'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
		row +='</td>';
		row +='</tr>'
		$('#tablaCosts > tbody:last').append(row);
	}
	//genera el codigo HTML para la creacion de una fila de la tabla de Pagos
	function createRowPayTable(dateId, payDate, payAmount, payDescription){
		var row = '<tr id="payRow'+dateId+'" >';
		row +='<td><span id="spaPayDate'+dateId+'">'+payDate+'</span><input type="hidden" value="'+dateId+'" id="txtPayDate" ></td>';
		row +='<td><span id="spaPayAmount'+dateId+'">'+payAmount+'</span></td>';
		row +='<td><span id="spaPayDescription'+dateId+'">'+payDescription+'</span></td>';
		row +='<td class="columnPaysButtons">';
		row +='<a class="btn btn-primary" href="#" id="btnEditPay'+dateId+'" title="Editar"><i class="icon-pencil icon-white"></i></a> ';
		row +='<a class="btn btn-danger" href="#" id="btnDeletePay'+dateId+'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
		row +='</td>';
		row +='</tr>'
		$('#tablaPays > tbody:last').append(row);
	}
	
	

	
//	function updateMultipleStocks(arrayItemsStocks, controlName){
//		var auxItemsStocks = [];
//		for(var i=0; i<arrayItemsStocks.length; i++){
//			auxItemsStocks = arrayItemsStocks[i].split('=>');//  item5=>9stock
//			$('#'+controlName+auxItemsStocks[0]).text(auxItemsStocks[1]);  //update only if quantities are APPROVED
//		}
//	}
	
	
	
	// (GC Ztep 2) function to fill Items list when (saved in modal)
	function addItem(){
		var supplier = $('#cbxModalSuppliers option:selected').text();
		var quantity = $('#txtModalQuantity').val();
		var itemCodeName = $('#cbxModalItems option:selected').text();
		var exFobPrice = $('#txtModalPrice').val();
		
		var error = validateItem(supplier, itemCodeName, quantity, parseFloat(exFobPrice).toFixed(2)); 
		if(error == ''){
			if(arr[3] == 'save_order'){
				ajax_save_movement('ADD', 'ORDER_PENDANT', ''/*, []*/);
			}
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('ADD', 'PINVOICE_PENDANT', ''/*, []*/);
			}
		}else{
			$('#boxModalValidateItem').html('<ul>'+error+'</ul>');
		}
	}
	
	function editItem(){
		var supplier = $('#cbxModalSuppliers option:selected').text();
		var quantity = $('#txtModalQuantity').val();
		var itemCodeName = $('#cbxModalItems option:selected').text();
		var exFobPrice = $('#txtModalPrice').val();
		var error = validateItem(supplier, itemCodeName, quantity, parseFloat(exFobPrice).toFixed(2)); 
		if(error == ''){
			
			if(arr[3] == 'save_order'){
				ajax_save_movement('EDIT', 'ORDER_PENDANT', ''/*, []*/);
			}
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('EDIT', 'PINVOICE_PENDANT', ''/*, []*/);
			}
		}else{
			$('#boxModalValidateItem').html('<ul>'+error+'</ul>');
		}
	}
		
	function addPay(){
		var payDate = $('#txtModalDate').val();
		var payAmount = $('#txtModalPaidAmount').val();
		var error = validateAddPay(payDate, parseFloat(payAmount).toFixed(2));  
		if(error == ''){
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('ADD_PAY', 'PINVOICE_PENDANT', ''/*, []*/);
			}
		}else{
			$('#boxModalValidatePay').html('<ul>'+error+'</ul>');
		}
	}
	
	function editPay(){
		var payDate = $('#txtModalDate').val();
		var payAmount = $('#txtModalPaidAmount').val();
		var payHiddenAmount = $('#txtModalAmountHidden').val();
		var error = validateEditPay(payDate, parseFloat(payAmount).toFixed(2), parseFloat(payHiddenAmount).toFixed(2));  
		if(error == ''){
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('EDIT_PAY', 'PINVOICE_PENDANT', ''/*, []*/);
			}
		}else{
			$('#boxModalValidatePay').html('<ul>'+error+'</ul>');
		}
	}	
		
	function addCost(){
		var costCodeName = $('#cbxModalCosts option:selected').text();
		var costExAmount = $('#txtModalCostExAmount').val();
		var error = validateCost(costCodeName, parseFloat(costExAmount).toFixed(2)); 
		if(error == ''){
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('ADD_COST', 'PINVOICE_PENDANT', ''/*, []*/);
			}
		}else{
			$('#boxModalValidateCost').html('<ul>'+error+'</ul>');
		}
	}
	
	function editCost(){
		var costId = $('#cbxModalCosts').val();
		var costCodeName = $('#cbxModalCosts option:selected').text();
		var costExAmount = $('#txtModalCostExAmount').val();
		var error = validateCost(costCodeName, parseFloat(costExAmount).toFixed(2)); 
		if(error == ''){
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('EDIT_COST', 'PINVOICE_PENDANT', ''/*, []*/);
			}
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
				 var exFobPrice = (arrayAux[i]['ex_fob_price']);
				 var quantity = (arrayAux[i]['quantity']);
				 total = total + (exFobPrice*quantity);
			}
		}
		return total; 	
	}
	
	function getTotalPay(){
		var arrayAux = [];
		var total = 0;
		arrayAux = getPaysDetails();
		if(arrayAux[0] != 0){
			for(var i=0; i< arrayAux.length; i++){
				 var amount = (arrayAux[i]['amount']);
				 total = total + Number(amount);
			}
		}
		return total; 	
	}
	
	function getTotalCost(){
		var arrayAux = [];
		var total = 0;
		arrayAux = getCostsDetails();
		if(arrayAux[0] != 0){
			for(var i=0; i< arrayAux.length; i++){
				 var exAmount = (arrayAux[i]['ex_amount']);
				 total = total + Number(exAmount);
			}
		}
		return total; 	
	}
	
	//get all items for save a purchase
	function getItemsDetails(){		
		var arrayItemsDetails = [];
		var itemId = '';
		var itemExFobPrice = '';
		var itemQuantity = '';
		var itemSupplierId = '';
		
		var exRate = $('#txtExRate').val();
	//	var itemExRate = '';
		var itemFobPrice = '';
//		var itemQuantityDocument = '';
		
		
		
		$('#tablaItems tbody tr').each(function(){		
			itemId = $(this).find('#txtItemId').val();
			itemSupplierId = $(this).find('#txtSupplierId'+itemId).val();
			itemExFobPrice = $(this).find('#spaExFobPrice'+itemId+'s'+itemSupplierId).text();
			itemQuantity = $(this).find('#spaQuantity'+itemId+'s'+itemSupplierId).text();
	//		itemExRate = $('#txtExRate').val();
			itemFobPrice = itemExFobPrice * exRate;//(parseFloat(itemExPrice).toFixed(2))
			arrayItemsDetails.push({'inv_item_id':itemId, 'ex_fob_price':itemExFobPrice, 'quantity':itemQuantity, 'inv_supplier_id':itemSupplierId, 'fob_price':parseFloat(itemFobPrice).toFixed(2)});
			
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
		var costExAmount = '';
		
		$('#tablaCosts tbody tr').each(function(){		
			costId = $(this).find('#txtCostId').val();
			costExAmount = $(this).find('#spaCostExAmount'+costId).text();
			
			arrayCostsDetails.push({'inv_price_type_id':costId, 'ex_amount':costExAmount});
		});
		
		if(arrayCostsDetails.length == 0){  //For fix undefined index
			arrayCostsDetails = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
		}
		
		return arrayCostsDetails; 		
	}
	
	function getPaysDetails(){		
		var arrayPaysDetails = [];
		var dateId = '';
		var payDate = '';
		var payAmount = '';
		var payDescription = '';
		
		$('#tablaPays tbody tr').each(function(){		
			dateId = $(this).find('#txtPayDate').val();
			payDate = $(this).find('#spaPayDate'+dateId).text();
			payAmount = $(this).find('#spaPayAmount'+dateId).text();
			payDescription = $(this).find('#spaPayDescription'+dateId).text();
			
			arrayPaysDetails.push({'date':dateId, 'amount':payAmount,'description':payDescription});
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
				ajax_save_movement('DEFAULT', 'ORDER_PENDANT', ''/*, []*/);
			}
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('DEFAULT', 'PINVOICE_PENDANT', ''/*, []*/);
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
					ajax_save_movement('DEFAULT', 'ORDER_APPROVED', ''/*, arrayForValidate*/);
				}
				if(arr[3] == 'save_invoice'){
					ajax_save_movement('DEFAULT', 'PINVOICE_APPROVED', ''/*, arrayForValidate*/);
				}
			}else{
				$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
			}
			hideBittionAlertModal();
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
//			var arrayForValidate = [];
//			arrayForValidate = getItemsDetails();
			if(arr[3] == 'save_order'){
				ajax_save_movement('DEFAULT', 'ORDER_CANCELLED', ''/*, arrayForValidate*/);
			}
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('DEFAULT', 'PINVOICE_CANCELLED', ''/*, arrayForValidate*/);
			}
			hideBittionAlertModal();
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
					index = 'index_order';
					type = 'ORDER_LOGIC_DELETED';
					break;	
				case 'save_invoice':
					index = 'index_invoice';
					type = 'PINVOICE_LOGIC_DELETED';
					break;	
			}
			ajax_logic_delete(purchaseId, type, index, genCode);
			hideBittionAlertModal();
		});
	}
	//************************************************************************//
	//////////////////////////////////END-FUNCTIONS//////////////////////
	//************************************************************************//
	
	
	
	
	//************************************************************************//
	//////////////////////////////////BEGIN-CONTROLS EVENTS/////////////////////
	//************************************************************************//
	$('#txtModalPrice').keydown(function(event) {
			validateOnlyFloatNumbers(event);			
	});
	$('#txtModalQuantity').keydown(function(event) {
			validateOnlyIntegers(event);			
	});
	$('#txtModalCostExAmount').keydown(function(event) {
			validateOnlyFloatNumbers(event);			
	});
	$('#txtModalPaidAmount').keydown(function(event) {
			validateOnlyFloatNumbers(event);			
	});
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
	
//	$("#txtModalDueDate").datepicker({
//	  showButtonPanel: true
//	});
	//Call modal
	$('#btnAddItem').click(function(){
		itemsListWhenExistsItems();			//NEEDS TO BE RUN BEFORE MODAL TO UPDATE ITEMS LIST BY SUPPLIER
		suppliersListWhenExistsItems();	//NEEDS TO BE RUN BEFORE MODAL TO UPDATE ITEMS LIST BY SUPPLIER
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
	
	//function triggered when PAYS plus icon is clicked
	$('#btnAddPay').click(function(){
		startEventsWhenExistsDebts();
		initiateModalAddPay();
		return false; //avoid page refresh
	});
	
	$('#btnModalAddPay').click(function(){
		addPay();
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
	
	//edit an existing item quantity
	$('#btnModalEditPay').click(function(){
		editPay();
		return false; //avoid page refresh
	});
	////////////////
	
	// (AEA Ztep 1) action when button Aprobar Entrada Almacen is pressed
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
		changeStateLogicDeleted();
		return false;
	});
	
//	$('#cbxSuppliers').data('pre', $(this).val());
//	$('#cbxSuppliers').change(function(){
//	var supplier = $(this).data('pre');
//		deleteList(supplier);
//	$(this).data('pre', $(this).val());
//		return false; //avoid page refresh
//	});
  

	$('#txtDate').keydown(function(e){e.preventDefault();});
	$('#txtModalDate').keypress(function(){return false;});
//	$('#txtModalDueDate').keypress(function(){return false;});
	$('#txtCode').keydown(function(e){e.preventDefault();});
	$('#txtOriginCode').keydown(function(e){e.preventDefault();});
	//************************************************************************//
	//////////////////////////////////END-CONTROLS EVENTS//////////////////////
	//************************************************************************//
	
	
	
	
	//************************************************************************//
	//////////////////////////////////BEGIN-AJAX FUNCTIONS//////////////////////
	////************************************************************************//
	
	//*****************************************************************************************************************************//
	function setOnData(ACTION, OPERATION, STATE, objectTableRowSelected/*, arrayForValidate*/){
		var DATA = [];
		//constants
		var purchaseId=$('#txtPurchaseIdHidden').val();
		var	movementDocCode = $('#txtCode').val();
		var	movementCode = $('#txtGenericCode').val();
		var noteCode=$('#txtNoteCode').val();
		var date=$('#txtDate').val();
		var description=$('#txtDescription').val();
		var exRate=$('#txtExRate').val();
		//variables
		var supplierId = 0;
		var itemId = 0;
		var exFobPrice = 0.00;
		var quantity = 0;
//		var cifPrice = 0.00;	//temp var
//		var exCifPrice = 0.00;	//temp var
		var subtotal = 0.00;
		
		var dateId = '';
		var payDate = '';
		var payAmount = 0;
		var payDescription = '';
		
		var costId = 0;
		var costExAmount = 0;
		var costCodeName = '';
		//only used for ADD
		var supplier = '';
		var itemCodeName = '';
//		var stock = 0;
		var arrayItemsDetails = [0];
//		var total=0;
//		var totalCost=0;
		
		if(ACTION === 'save_invoice' && STATE === 'PINVOICE_APPROVED'){
			arrayItemsDetails = getItemsDetails();
//			total = getTotal();
//			totalCost = getTotalCost();
		}
		//PurchaseDetails(Item) setup variables
		if(OPERATION === 'ADD' || OPERATION === 'EDIT' || OPERATION === 'ADD_PAY' || OPERATION === 'EDIT_PAY' || OPERATION === 'ADD_COST' || OPERATION === 'EDIT_COST'){	
			supplierId = $('#cbxModalSuppliers').val();
			itemId = $('#cbxModalItems').val();
			exFobPrice = $('#txtModalPrice').val();
			quantity = $('#txtModalQuantity').val();

			if(OPERATION === 'ADD_PAY' || OPERATION === 'EDIT_PAY' || OPERATION === 'ADD_COST' || OPERATION === 'EDIT_COST'){
				payDate = $('#txtModalDate').val();
				var myDate = payDate.split('/');
				dateId = myDate[2]+"-"+myDate[1]+"-"+myDate[0];
				payAmount = $('#txtModalPaidAmount').val();
				payDescription = $('#txtModalDescription').val();
				
				costId = $('#cbxModalCosts').val();
				costExAmount = $('#txtModalCostExAmount').val();
				costCodeName = $('#cbxModalCosts option:selected').text();
			}
			if(OPERATION === 'ADD'){
				supplier = $('#cbxModalSuppliers option:selected').text();
				itemCodeName = $('#cbxModalItems option:selected').text();
//				cifPrice = 0.00;	//temp var
//				exCifPrice = 0.00;	//temp var
				subtotal = Number(quantity) * Number(exFobPrice);
			}
		}
			
		if(OPERATION === 'DELETE'){
			itemId = objectTableRowSelected.find('#txtItemId').val();
			supplierId = objectTableRowSelected.find('#txtSupplierId'+itemId).val();
		}
		
		if(OPERATION === 'DELETE_PAY'){
			payDate = objectTableRowSelected.find('#txtPayDate').val();
		}
		
		if(OPERATION === 'DELETE_COST'){
			costId = objectTableRowSelected.find('#txtCostId').val();
		}
		//setting data
		DATA ={	'purchaseId':purchaseId
				,'movementDocCode':movementDocCode
				,'movementCode':movementCode
				,'noteCode':noteCode
				,'date':date
				,'description':description	
				,'exRate':exRate
				
				,'supplierId':supplierId
				,'supplier':supplier
				,'itemId':itemId
				,'exFobPrice':exFobPrice
				,'quantity':quantity	
//				,'cifPrice':cifPrice
//				,'exCifPrice':exCifPrice
				,'subtotal':subtotal
				
				,'dateId':dateId
				,'payDate':payDate
				,'payAmount':payAmount
				,'payDescription':payDescription
				
				,'costId':costId
				,'costExAmount':costExAmount
		
//				,'total':total
//				,'totalCost':totalCost
				,arrayItemsDetails:arrayItemsDetails
		
				,'ACTION':ACTION
				,'OPERATION':OPERATION
				,'STATE':STATE

				,itemCodeName:itemCodeName
				,costCodeName:costCodeName
//				,stock:stock
//				,arrayForValidate:arrayForValidate
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
	
	function setOnPendant(DATA, ACTION, OPERATION, STATE, objectTableRowSelected, supplierId, supplier, itemId, itemCodeName, exFobPrice, quantity, subtotal, dateId, payDate, payAmount, payDescription, costId, costExAmount, costCodeName){
		if($('#txtPurchaseIdHidden').val() === ''){
			$('#txtCode').val(DATA[2]);
			$('#txtGenericCode').val(DATA[3]);
			
			$('#btnApproveState, #btnPrint, #btnLogicDeleteState').show();
			$('#txtPurchaseIdHidden').val(DATA[1]);
			changeLabelDocumentState(STATE); //#UNICORN
		}
		/////////////************************************////////////////////////
		//Item's table setup
		if(OPERATION === 'ADD'){
			createRowItemTable(itemId, itemCodeName, parseFloat(exFobPrice).toFixed(2), parseInt(quantity,10), supplier, supplierId, parseFloat(subtotal).toFixed(2));
			createEventClickEditItemButton(itemId, supplierId);
			createEventClickDeleteItemButton(itemId, supplierId);
			arrayItemsAlreadySaved.push(itemId);  //push into array of the added item	
			arraySupplierItemsAlreadySaved.push(supplierId);  //push into array of the added warehouses	
			///////////////////
		   itemsCounter = itemsCounter + 1;
			//////////////////
			$('#countItems').text(itemsCounter);
			//$('#countItems').text(arrayItemsAlreadySaved.length);
			$('#total').text(parseFloat(getTotal()).toFixed(2)+' $us.');
			$('#modalAddItem').modal('hide');
			highlightTemporally('#itemRow'+itemId+'s'+supplierId);
		}	
		if(OPERATION === 'ADD_PAY'){
			createRowPayTable(dateId, payDate, parseFloat(payAmount).toFixed(2), payDescription);
			createEventClickEditPayButton(dateId);
			createEventClickDeletePayButton(dateId);
			arrayPaysAlreadySaved.push(dateId);  //push into array of the added date
			$('#total2').text(parseFloat(getTotalPay()).toFixed(2)+' Bs.');
			$('#modalAddPay').modal('hide');
			highlightTemporally('#payRow'+dateId);
		}		
		if(OPERATION === 'ADD_COST'){
			createRowCostTable(costId, costCodeName, parseFloat(costExAmount).toFixed(2));
			createEventClickEditCostButton(costId);
			createEventClickDeleteCostButton(costId);
			arrayCostsAlreadySaved.push(costId);  //push into array of the added date
			$('#total3').text(parseFloat(getTotalCost()).toFixed(2)+' $us.');
			$('#modalAddCost').modal('hide');
			highlightTemporally('#costRow'+costId);
		}
		if(OPERATION === 'EDIT'){
			$('#spaQuantity'+itemId+'s'+supplierId).text(parseInt(quantity,10));
			$('#spaExFobPrice'+itemId+'s'+supplierId).text(parseFloat(exFobPrice).toFixed(2));	
			$('#spaSubtotal'+itemId+'s'+supplierId).text(parseFloat(Number(quantity) * Number(exFobPrice)).toFixed(2));
			$('#total').text(parseFloat(getTotal()).toFixed(2)+' $us.');
			$('#modalAddItem').modal('hide');
			highlightTemporally('#itemRow'+itemId+'s'+supplierId);
		}	
		if(OPERATION === 'EDIT_PAY'){	
			$('#spaPayDate'+dateId).text(payDate);
			$('#spaPayAmount'+dateId).text(parseFloat(payAmount).toFixed(2));
			$('#spaPayDescription'+dateId).text(payDescription);
			$('#total2').text(parseFloat(getTotalPay()).toFixed(2)+' Bs.');	
			$('#modalAddPay').modal('hide');
			highlightTemporally('#payRow'+dateId);
		}
		if(OPERATION === 'EDIT_COST'){	
			$('#spaCostExAmount'+costId).text(parseFloat(costExAmount).toFixed(2));
			$('#total3').text(parseFloat(getTotalCost()).toFixed(2)+' $us.');	
			$('#modalAddCost').modal('hide');
			highlightTemporally('#costRow'+costId);
		}
		if(OPERATION === 'DELETE'){					
			var itemIdForDelete = objectTableRowSelected.find('#txtItemId').val();
			subtotal = $('#spaSubtotal'+itemIdForDelete+'s'+supplierId).text();		
			hideBittionAlertModal();
			
			objectTableRowSelected.fadeOut("slow", function() {
				$(this).remove();
			});
			itemsListWhenExistsItems();
			suppliersListWhenExistsItems();
			/////////////////////////
			itemsCounter = itemsCounter - 1;
			////////////////////////
			$('#countItems').text(itemsCounter);
			//$('#countItems').text(arrayItemsAlreadySaved.length-1);	//because arrayItemsAlreadySaved updates after all is done
			$('#total').text(parseFloat(getTotal()-subtotal).toFixed(2)+' $us.');
		}
		if(OPERATION === 'DELETE_PAY'){						
			arrayPaysAlreadySaved = jQuery.grep(arrayPaysAlreadySaved, function(value){
				return value !== payDate;
			});
			subtotal = $('#spaPayAmount'+payDate).text();			
			hideBittionAlertModal();
			objectTableRowSelected.fadeOut("slow", function() {
				$(this).remove();
			});
			$('#total2').text(parseFloat(getTotalPay()-subtotal).toFixed(2)+' Bs.');
		}
		if(OPERATION === 'DELETE_COST'){						
			arrayCostsAlreadySaved = jQuery.grep(arrayCostsAlreadySaved, function(value){
				return value !== costId;
			});
			subtotal = $('#spaCostExAmount'+costId).text();			
			hideBittionAlertModal();
			objectTableRowSelected.fadeOut("slow", function() {
				$(this).remove();
			});
			$('#total3').text(parseFloat(getTotalCost()-subtotal).toFixed(2)+' $us.');
		}
		showGrowlMessage('ok', 'Cambios guardados.');
	}
	
	function setOnApproved(DATA, STATE, ACTION){
		$('#txtCode').val(DATA[2]);
		$('#txtGenericCode').val(DATA[3]);
		$('#btnApproveState, #btnLogicDeleteState, #btnSaveAll, .columnItemsButtons').hide();
		$('#btnCancellState').show();
		$('#txtCode, #txtNoteCode, #txtDate, #txtDescription, #txtExRate').attr('disabled','disabled');
		if ($('#btnAddItem').length > 0){//existe
			$('#btnAddItem').hide();
		}
		changeLabelDocumentState(STATE); //#UNICORN
		showGrowlMessage('ok', 'Aprobado.');
	}
	
	function setOnCancelled(STATE){
		$('#btnCancellState').hide();
		changeLabelDocumentState(STATE); //#UNICORN
		showGrowlMessage('ok', 'Cancelado.');
	}
	
	function ajax_save_movement(OPERATION, STATE, objectTableRowSelected/*, arrayForValidate*/){//SAVE_IN/ADD/PENDANT
		var ACTION = arr[3];
		var dataSent = setOnData(ACTION, OPERATION, STATE, objectTableRowSelected/*, arrayForValidate*/);
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
//				if(dataReceived[0] === 'ORDER_APPROVED' || dataReceived[0] === 'ORDER_CANCELLED'){
//						var arrayItemsStocks = dataReceived[3].split(',');
//						updateMultipleStocks(arrayItemsStocks, 'spaStock');//What is this for???????????
//				}
				switch(dataReceived[0]){
					case 'ORDER_PENDANT':
						setOnPendant(dataReceived, ACTION, OPERATION, STATE, objectTableRowSelected, dataSent['supplierId'], dataSent['supplier'], dataSent['itemId'], dataSent['itemCodeName'], dataSent['exFobPrice'], dataSent['quantity'], dataSent['subtotal']);
						break;
					case 'ORDER_APPROVED':
						setOnApproved(dataReceived, STATE, ACTION);
						break;
					case 'ORDER_CANCELLED':
						setOnCancelled(STATE);
						break;
					case 'PINVOICE_PENDANT':
						setOnPendant(dataReceived, ACTION, OPERATION, STATE, objectTableRowSelected, dataSent['supplierId'], dataSent['supplier'], dataSent['itemId'], dataSent['itemCodeName'], dataSent['exFobPrice'], dataSent['quantity'], dataSent['subtotal'], dataSent['dateId'], dataSent['payDate'], dataSent['payAmount'], dataSent['payDescription'], dataSent['costId'], dataSent['costExAmount'], dataSent['costCodeName']);
						break;
					case 'PINVOICE_APPROVED':
						setOnApproved(dataReceived, STATE, ACTION);
						break;
					case 'PINVOICE_CANCELLED':
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
	
	//*************************************************************************************
	
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
	//Get items and prices for the fist item when inititates modal
	function ajax_initiate_modal_add_item_in(itemsAlreadySaved, supplierItemsAlreadySaved){
		 $.ajax({
            type:"POST",
            url:moduleController + "ajax_initiate_modal_add_item_in",			
  /*data*/  data:{itemsAlreadySaved: itemsAlreadySaved, 
					supplierItemsAlreadySaved: supplierItemsAlreadySaved},
            beforeSend: showProcessing(),
            success: function(data){
				$('#processing').text('');
				$('#boxModalInitiateItemPrice').html(data);
				$('#txtModalQuantity').val('');  
				initiateModal()
				$('#cbxModalSuppliers').bind("change",function(){ //must be binded 'cause dropbox is loaded by a previous ajax'
					//este es para los items precio y stock
					ajax_update_items_modal(itemsAlreadySaved, supplierItemsAlreadySaved);
				});
				$('#cbxModalItems').bind("change",function(){ //must be binded 'cause dropbox is loaded by a previous ajax'
					ajax_update_stock_modal();
				});
				$('#cbxModalItems').select2();
			},
			error:function(data){
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text('');
			}
        });
	}
	
	function ajax_update_items_modal(itemsAlreadySaved, supplierItemsAlreadySaved){ 
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_update_items_modal",			
            data:{itemsAlreadySaved: itemsAlreadySaved,
				supplierItemsAlreadySaved: supplierItemsAlreadySaved,
				supplier: $('#cbxModalSuppliers').val()},
            beforeSend: showProcessing(),
            success: function(data){
				$('#processing').text("");
				$('#boxModalItemPriceStock').html(data);
			
				$('#cbxModalItems').bind("change",function(){ //must be binded 'cause dropbox is loaded by a previous ajax'
					//este es para el stock
//					ajax_update_stock_modal_1();
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
	
	function ajax_initiate_modal_add_cost(costsAlreadySaved){
		 $.ajax({
            type:"POST",
            url:moduleController + "ajax_initiate_modal_add_cost",			
			  data:{costsAlreadySaved: costsAlreadySaved/*, supplier: $('#cbxSuppliers').val()*//*, transfer:transfer, warehouse2:warehouse2*/},
            beforeSend: showProcessing(),
            success: function(data){
				$('#processing').text('');
				$('#boxModalInitiateCost').html(data);
				$('#txtModalCostExAmount').val('');  
				initiateModalCost()
/*				$('#cbxModalCosts').bind("change",function(){ //must be binded 'cause dropbox is loaded by a previous ajax'
					ajax_update_amount();
				});
*///				$('#txtModalPrice').keypress(function(){return false;});
				$('#cbxModalCosts').select2();	
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	function ajax_initiate_modal_add_pay(paysAlreadySaved,payDebt){
		 $.ajax({
            type:"POST",
            url:moduleController + "ajax_initiate_modal_add_pay",			
		    data:{paysAlreadySaved: paysAlreadySaved,
					payDebt: payDebt},
            beforeSend: showProcessing(),
            success: function(data){
				$('#processing').text('');
				$('#boxModalInitiatePay').html(data); 
				$('#txtModalDescription').val('');  
				initiateModalPay()
				$("#txtModalDate").datepicker({
					showButtonPanel: true
				});
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
	
	
	//************************************************************************//
	//////////////////////////////////END-AJAX FUNCTIONS////////////////////////
	//************************************************************************//
	
//END SCRIPT	
});
