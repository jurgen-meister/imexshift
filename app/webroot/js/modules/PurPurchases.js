$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation

	var globalPeriod = $('#globalPeriod').text(); // this value is obtained from the main template.

	var arrayItemsAlreadySaved = []; 
	startEventsWhenExistsItems();
	
	var arrayCostsAlreadySaved = []; 
	startEventsWhenExistsCosts();
	
	var arrayPaysAlreadySaved = []; 
	startEventsWhenExistsPays();
	
	//clearFieldsForFirefox();

	
//	var payPaid = 0;
	var payDebt = 0;
//	var payTotal = 0; 
	startEventsWhenExistsDebts();
	
	function startEventsWhenExistsDebts(){		
		payDebt =0;
		var	payPaid = getTotalPay();
		var payTotal = getTotal();
		payDebt = Number(payTotal) - Number(payPaid);
		return payDebt
	}
	
	
	
	
	//************************************************************************//
	//////////////////////////////////BEGIN-FUNCTIONS////////////////
	//************************************************************************//
	//firefox doesn't clear by himself the fields when there is a refresh in a new form
//	function clearFieldsForFirefox(){
///*ch*/		var urlController = ['save_order', 'save_invoice'];
//		for(var i=0;i < urlController.length; i++ ){
//			if(arr[3] == urlController[i]){
//				if(arr[4] == null){
//					$('input').val('');//empty all inputs including hidden thks jquery 
//					$('textarea').val('');
//				}
//			}
//		}
//	}
	
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
				 arrayPaysAlreadySaved[i] = arrayAux[i]['pur_payment_type_id'];
				 createEventClickEditPayButton(arrayAux[i]['pur_payment_type_id']);
				 createEventClickDeletePayButton(arrayAux[i]['pur_payment_type_id']);			 
			}
		}
		/*else{
			alert('esta vacio');
		}*/
	}
	//validates before add item quantity
	function validateItem(item, quantity, exFobPrice/*, documentQuantity*/){
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
		
		if(exFobPrice == ''){
			error+='<li>El campo "P/U" no puede estar vacio</li>'; 
		}/*else{
//o si puede ser cero el precio?			
			if(parseFloat(price).toFixed(2) == 0){
				
				error+='<li>El campo "P/U" no puede ser cero</li>'; 
			}
		}*/
		
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
	
function validatePay(payDate, payAmount){
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
		
//		if(pay == ''){error+='<li>El campo "Pagos" no puede estar vacio</li>';}
		
		return error;
	}
	
	function validateBeforeSaveAll(arrayItemsDetails){
		var error = '';
		var date = $('#txtDate').val();
		var dateYear = date.split('/');
		if(date == ''){	error+='<li> El campo "Fecha" no puede estar vacio </li>'; }
		if(dateYear[2] !== globalPeriod){	error+='<li> El año '+dateYear[2]+' de la fecha del documento no es valida, ya que se encuentra en la gestión '+ globalPeriod +'.</li>'; }
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
	
	function initiateModalPay(){
		$('#modalAddPay').modal({
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
			}
			$('#btnModalAddItem').show();
			$('#btnModalEditItem').hide();
			$('#boxModalValidateItem').html('');//clear error message
			ajax_initiate_modal_add_item_in(arrayItemsAlreadySaved);
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
		ajax_initiate_modal_add_pay(arrayPaysAlreadySaved, payDebt);
	}
	
	function initiateModalEditItem(objectTableRowSelected){
		var error = validateBeforeSaveAll([{0:0}]);//I send [{0:0}] 'cause it doesn't care to validate if arrayItemsDetails is empty or not
		if( error === ''){
			var itemIdForEdit = objectTableRowSelected.find('#txtItemId').val();  //
			$('#btnModalAddItem').hide();
			$('#btnModalEditItem').show();
			$('#boxModalValidateItem').html('');//clear error message
			$('#txtModalQuantity').val(objectTableRowSelected.find('#spaQuantity'+itemIdForEdit).text());
			$('#txtModalPrice').val(objectTableRowSelected.find('#spaExFobPrice'+itemIdForEdit).text());
			$('#cbxModalItems').empty();
			$('#cbxModalItems').append('<option value="'+itemIdForEdit+'">'+objectTableRowSelected.find('td:first').text()+'</option>');
			initiateModal();
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}	
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
		var arrayItemsDetails = getItemsDetails();
		var error = validateBeforeSaveAll([{0:0}]);//Send [{0:0}] 'cause I won't use arrayItemsDetails classic validation, I will use it differently for this case (as done below)
		if(arrayItemsDetails.length === 1){error+='<li> Debe existir al menos 1 "Item" </li>';}
		if( error === ''){
			showBittionAlertModal({content:'¿Está seguro de eliminar este item?'});
			$('#bittionBtnYes').click(function(){
				if(arr[3] == 'save_order'){
					ajax_save_movement('DELETE', 'ORDER_PENDANT', objectTableRowSelected, []);
				}
				if(arr[3] == 'save_invoice'){
					ajax_save_movement('DELETE', 'PINVOICE_PENDANT', objectTableRowSelected, []);
				}
				return false; //avoid page refresh
			});
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
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
			
			//	this should be a function
			var subtotal = $('#spaAmount'+costIdForDelete).text();	
			var total = parseFloat($('#totalcost').text()) - Number(subtotal);
			//	this should be a function
			$('#totalcost').text(parseFloat(total).toFixed(2)+' $us');
			
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
	
	// (GC Ztep 3) function to fill Items list when saved in modal triggered by addItem()
	function createRowItemTable(itemId, itemCodeName, exFobPrice, quantity, subtotal){
		var row = '<tr id="itemRow'+itemId+'" >';
		row +='<td><span id="spaItemName'+itemId+'">'+itemCodeName+'</span><input  value="'+itemId+'" id="txtItemId" ></td>';
		row +='<td><span id="spaExFobPrice'+itemId+'">'+exFobPrice+'</span></td>';
		row +='<td><span id="spaQuantity'+itemId+'">'+quantity+'</span></td>';
		row +='<td><span id="spaSubtotal'+itemId+'">'+subtotal+'</span></td>';
		row +='<td class="columnItemsButtons">';
		row +='<a class="btn btn-primary" href="#" id="btnEditItem'+itemId+'" title="Editar"><i class="icon-pencil icon-white"></i></a> ';
		row +='<a class="btn btn-danger" href="#" id="btnDeleteItem'+itemId+'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
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
	function createRowPayTable(dateId, payDate, payAmount, payDescription){
		var row = '<tr id="payRow'+dateId+'" >';
		row +='<td><span id="spaPayDate'+dateId+'">'+payDate+'</span><input  value="'+dateId+'" id="txtPayDate" ></td>';
		row +='<td><span id="spaPayAmount'+dateId+'">'+payAmount+'</span></td>';
		row +='<td><span id="spaPayDescription'+dateId+'">'+payDescription+'</span></td>';
		row +='<td class="columnPaysButtons">';
		row +='<a class="btn btn-primary" href="#" id="btnEditPay'+dateId+'" title="Editar"><i class="icon-pencil icon-white"></i></a> ';
		row +='<a class="btn btn-danger" href="#" id="btnDeletePay'+dateId+'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
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
		var supplier=$('#cbxSuppliers').val()
		var description=$('#txtDescription').val();
		var exRate=$('#txtExRate').val();
		//variables
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
		
	//	var total = 0.00;
		//only used for ADD
		var itemCodeName = '';
//		var stock = 0;
		
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
		if(OPERATION === 'ADD' || OPERATION === 'EDIT' || OPERATION === 'ADD_PAY' || OPERATION === 'EDIT_PAY'){		
			itemId = $('#cbxModalItems').val();
			exFobPrice = $('#txtModalPrice').val();
			quantity = $('#txtModalQuantity').val();

			if(OPERATION === 'ADD_PAY' || OPERATION === 'EDIT_PAY'){
				payDate = $('#txtModalDate').val();
				var myDate = payDate.split('/');
				dateId = myDate[2]+"-"+myDate[1]+"-"+myDate[0];
				payAmount = $('#txtModalPaidAmount').val();
				payDescription = $('#txtModalDescription').val();
			}
//			total = parseFloat($('#total').text()) + Number(subtotal);
			if(OPERATION === 'ADD'){
				itemCodeName = $('#cbxModalItems option:selected').text();
//				stock = $('#txtModalStock').val();
//				cifPrice = 0.00;	//temp var
//				exCifPrice = 0.00;	//temp var
				subtotal = Number(quantity) * Number(exFobPrice);
			}
		}
			
		if(OPERATION === 'DELETE'){
			itemId = objectTableRowSelected.find('#txtItemId').val();
		}
		
		if(OPERATION === 'DELETE_PAY'){
			payDate = objectTableRowSelected.find('#txtPayDate').val();
		}
		//setting data
		DATA ={	'purchaseId':purchaseId
				,'movementDocCode':movementDocCode
				,'movementCode':movementCode
				,'noteCode':noteCode
				,'date':date
				,'supplier':supplier
				,'description':description	
				,'exRate':exRate

				,'itemId':itemId
				,'exFobPrice':exFobPrice
				,'quantity':quantity	
//				,'cifPrice':cifPrice
//				,'exCifPrice':exCifPrice
				,'subtotal':subtotal
			//	,'total':total
				
				,'dateId':dateId
				,'payDate':payDate
				,'payAmount':payAmount
				,'payDescription':payDescription
		
				,'ACTION':ACTION
				,'OPERATION':OPERATION
				,'STATE':STATE

				,itemCodeName:itemCodeName
//				,stock:stock
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
	
	function setOnPendant(DATA, ACTION, OPERATION, STATE, objectTableRowSelected, itemId, itemCodeName, exFobPrice, /*stock,*/ quantity, subtotal, dateId, payDate, payAmount, payDescription){
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
			createRowItemTable(itemId, itemCodeName, parseFloat(exFobPrice).toFixed(2), parseInt(quantity,10), parseFloat(subtotal).toFixed(2));
			createEventClickEditItemButton(itemId);
			createEventClickDeleteItemButton(itemId);
			arrayItemsAlreadySaved.push(itemId);  //push into array of the added item	
			$('#countItems').text(arrayItemsAlreadySaved.length);
			$('#total').text(parseFloat(getTotal()).toFixed(2)+' $us.');
			$('#modalAddItem').modal('hide');
			highlightTemporally('#itemRow'+itemId);
		}	
		if(OPERATION === 'ADD_PAY'){
			createRowPayTable(dateId, payDate, parseFloat(payAmount).toFixed(2), payDescription);
			createEventClickEditPayButton(dateId);
			createEventClickDeletePayButton(dateId);
			arrayPaysAlreadySaved.push(dateId);  //push into array of the added date
$('#total2').text(parseFloat(getTotalPay()).toFixed(2)+' Bs.');	
//payPaid = parseFloat(getTotalPay()).toFixed(2);
			$('#modalAddPay').modal('hide');
			highlightTemporally('#payRow'+dateId);
		}
		if(OPERATION === 'EDIT'){
			$('#spaQuantity'+itemId).text(parseInt(quantity,10));
			$('#spaExFobPrice'+itemId).text(parseFloat(exFobPrice).toFixed(2));	
			$('#spaSubtotal'+itemId).text(parseFloat(Number(quantity) * Number(exFobPrice)).toFixed(2));
			$('#total').text(parseFloat(getTotal()).toFixed(2)+' $us.');
			$('#modalAddItem').modal('hide');
			highlightTemporally('#itemRow'+itemId);
		}	
		if(OPERATION === 'EDIT_PAY'){	
			$('#spaPayDate'+dateId).text(payDate);
			$('#spaPayAmount'+dateId).text(parseFloat(payAmount).toFixed(2));
			$('#spaPayDescription'+dateId).text(payDescription);
$('#total2').text(parseFloat(getTotalPay()).toFixed(2)+' Bs.');	
//payPaid = parseFloat(getTotalPay()).toFixed(2);
			$('#modalAddPay').modal('hide');
			highlightTemporally('#payRow'+dateId);
		}
		if(OPERATION === 'DELETE'){					
			arrayItemsAlreadySaved = jQuery.grep(arrayItemsAlreadySaved, function(value){
				return value !== itemId;
			});
			hideBittionAlertModal();
			
			objectTableRowSelected.fadeOut("slow", function() {
				$(this).remove();
			});
//			/////////////////////////
//			itemsCounter = itemsCounter - 1;
//			////////////////////////
//			$('#countItems').text(itemsCounter);
			$('#countItems').text(arrayItemsAlreadySaved.length-1);	//because arrayItemsAlreadySaved updates after all is done
			$('#total').text(parseFloat(getTotal()-subtotal).toFixed(2)+' $us.');
		}
		if(OPERATION === 'DELETE_PAY'){						
			//-----------------------------------------------------------------------------------------------------------------
			arrayPaysAlreadySaved = jQuery.grep(arrayPaysAlreadySaved, function(value){
				return value !== payDate;
			});
			//-----------------------------------------------------------------------------------------------------------------
subtotal = $('#spaPayAmount'+payDate).text();			
			hideBittionAlertModal();
			
			objectTableRowSelected.fadeOut("slow", function() {
				$(this).remove();
			});
$('#total2').text(parseFloat(getTotalPay()-subtotal).toFixed(2)+' Bs.');
//payPaid = parseFloat(getTotalPay()-subtotal).toFixed(2);
		}
		showGrowlMessage('ok', 'Cambios guardados.');
	}
	
	function setOnApproved(DATA, STATE, ACTION){
		$('#txtCode').val(DATA[2]);
		$('#txtGenericCode').val(DATA[3]);
		$('#btnApproveState, #btnLogicDeleteState, #btnSaveAll, .columnItemsButtons').hide();
		$('#btnCancellState').show();
		$('#txtCode, #txtNoteCode, #txtDate, #cbxSuppliers, #txtDescription, #txtExRate').attr('disabled','disabled');
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
				if(dataReceived[0] === 'ORDER_APPROVED' || dataReceived[0] === 'ORDER_CANCELLED'){
						var arrayItemsStocks = dataReceived[3].split(',');
						updateMultipleStocks(arrayItemsStocks, 'spaStock');//What is this for???????????
				}
				switch(dataReceived[0]){
					case 'ORDER_PENDANT':
						setOnPendant(dataReceived, ACTION, OPERATION, STATE, objectTableRowSelected, dataSent['itemId'], dataSent['itemCodeName'], dataSent['exFobPrice'], /*dataSent['stock'],*/ dataSent['quantity'], dataSent['subtotal']);
						break;
					case 'ORDER_APPROVED':
						setOnApproved(dataReceived, STATE, ACTION);
						break;
					case 'ORDER_CANCELLED':
						setOnCancelled(STATE);
						break;
					case 'PINVOICE_PENDANT':
						setOnPendant(dataReceived, ACTION, OPERATION, STATE, objectTableRowSelected, dataSent['itemId'], dataSent['itemCodeName'], dataSent['exFobPrice'], /*dataSent['stock'],*/ dataSent['quantity'], dataSent['subtotal'], dataSent['dateId'], dataSent['payDate'], dataSent['payAmount'], dataSent['payDescription']);
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
	
	function updateMultipleStocks(arrayItemsStocks, controlName){
		var auxItemsStocks = [];
		for(var i=0; i<arrayItemsStocks.length; i++){
			auxItemsStocks = arrayItemsStocks[i].split('=>');//  item5=>9stock
			$('#'+controlName+auxItemsStocks[0]).text(auxItemsStocks[1]);  //update only if quantities are APPROVED
		}
	}
	
	
	
	// (GC Ztep 2) function to fill Items list when (saved in modal)
	function addItem(){
		var quantity = $('#txtModalQuantity').val();
		var itemId = $('#cbxModalItems').val();
		var itemCodeName = $('#cbxModalItems option:selected').text();
		var exFobPrice = $('#txtModalPrice').val();
		
		var error = validateItem(itemCodeName, quantity, parseFloat(exFobPrice).toFixed(2)); 
		if(error == ''){
			if(arr[3] == 'save_order'){
				ajax_save_movement('ADD', 'ORDER_PENDANT', '', []);
			}
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('ADD', 'PINVOICE_PENDANT', '', []);
			}
//	var subtotal = Number(quantity) * Number(exFobPrice);
//	var total = parseFloat($('#total').text()) + Number(subtotal);
//		var error = validateItem(itemCodeName, quantity, parseFloat(exFobPrice).toFixed(2)/*, ''*/); 
//		if(error == ''){
//			
//			createRowItemTable(itemId, itemCodeName, parseFloat(exFobPrice).toFixed(2), parseInt(quantity,10)/*, stock2*/, parseFloat(subtotal).toFixed(2));
//			createEventClickEditItemButton(itemId);
//			createEventClickDeleteItemButton(itemId);
//			arrayItemsAlreadySaved.push(itemId);  //push into array of the added item
//			$('#modalAddItem').modal('hide');
//			$('#total').text(parseFloat(total).toFixed(2)+' $us');
		}else{
			$('#boxModalValidateItem').html('<ul>'+error+'</ul>');
		}
	}
	
	function editItem(){
		var itemId = $('#cbxModalItems').val();
		var quantity = $('#txtModalQuantity').val();
		var itemCodeName = $('#cbxModalItems option:selected').text();
		var exFobPrice = $('#txtModalPrice').val();
		var error = validateItem(itemCodeName, quantity, parseFloat(exFobPrice).toFixed(2)); 
		if(error == ''){
			
			if(arr[3] == 'save_order'){
				ajax_save_movement('EDIT', 'ORDER_PENDANT', '', []);
			}
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('EDIT', 'PINVOICE_PENDANT', '', []);
			}
//			$('#spaQuantity'+itemId).text(parseInt(quantity,10));
//			$('#spaExFobPrice'+itemId).text(parseFloat(exFobPrice).toFixed(2));
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
var total = parseFloat($('#totalcost').text()) + Number(amount);
		var error = validateCost(costCodeName, parseFloat(amount).toFixed(2)/*, ''*/); 
		if(error == ''){
			
			createRowCostTable(costId, costCodeName, parseFloat(amount).toFixed(2)/*, parseInt(quantity,10)*//*, stock2*//*, subtotal*/);
			createEventClickEditCostButton(costId);
			createEventClickDeleteCostButton(costId);
			arrayCostsAlreadySaved.push(costId);  //push into array of the added item
			$('#modalAddCost').modal('hide');
			$('#totalcost').text(parseFloat(total).toFixed(2)+' $us');
		}else{
			$('#boxModalValidateCost').html('<ul>'+error+'</ul>');
		}
	}
	
	function addPay(){
//		var payId = $('#txtPayDate').val();
		var payDate = $('#txtModalDate').val();
		var payAmount = $('#txtModalPaidAmount').val();
//		var payDescription = $('#txtModalDescription').val();
		var error = validatePay(payDate, parseFloat(payAmount).toFixed(2));  
		if(error == ''){
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('ADD_PAY', 'PINVOICE_PENDANT', '', []);
			}
		}else{
			$('#boxModalValidatePay').html('<ul>'+error+'</ul>');
		}
	}
	
	function editPay(){
		var payDate = $('#txtModalDate').val();
		var payAmount = $('#txtModalPaidAmount').val();
		
		var error = validatePay(payDate, parseFloat(payAmount).toFixed(2));  
		if(error == ''){
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('EDIT_PAY', 'PINVOICE_PENDANT', '', []);
			}
		}else{
			$('#boxModalValidateItem').html('<ul>'+error+'</ul>');
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
				 var salePrice = (arrayAux[i]['ex_fob_price']);
				 var quantity = (arrayAux[i]['quantity']);
				 total = total + (salePrice*quantity);
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
//				 var quantity = (arrayAux[i]['quantity']);
				 total = total + Number(amount);
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
		
		var exRate = $('#txtExRate').val();
	//	var itemExRate = '';
		var itemFobPrice = '';
//		var itemQuantityDocument = '';
		
		
		
		$('#tablaItems tbody tr').each(function(){		
			itemId = $(this).find('#txtItemId').val();
			itemExFobPrice = $(this).find('#spaExFobPrice'+itemId).text();
			itemQuantity = $(this).find('#spaQuantity'+itemId).text();
	
/*			if ($('#spaQuantityDocument'+itemId).length > 0){//exists
				itemQuantityDocument = $(this).find('#spaQuantityDocument'+itemId).text();
			}
*/	//		itemExRate = $('#txtExRate').val();
			itemFobPrice = itemExFobPrice * exRate;//(parseFloat(itemExPrice).toFixed(2))
			arrayItemsDetails.push({'inv_item_id':itemId, 'ex_fob_price':itemExFobPrice, 'quantity':itemQuantity, /*'ex_rate':itemExRate,*/ 'fob_price':parseFloat(itemFobPrice).toFixed(2)/*, 'quantity_document':itemQuantityDocument*//*, 'stock2':itemStock2*/});
			
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
				ajax_save_movement('DEFAULT', 'ORDER_PENDANT', '', []);
			}
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('DEFAULT', 'PINVOICE_PENDANT', '', []);
			}
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
		
//		var arrayItemsDetails = [];
//		arrayItemsDetails = getItemsDetails();
//		var arrayCostsDetails = [];
//		arrayCostsDetails = getCostsDetails();
//		var arrayPaysDetails = [];
//		arrayPaysDetails = getPaysDetails();
//		var error = validateBeforeSaveAll(arrayItemsDetails);
//		if( error == ''){
//			if(arr[3] == 'save_order'){
//				ajax_save_movement_in(arrayItemsDetails);
//			}
//			if(arr[3] == 'save_invoice'){
//				ajax_save_invoice(arrayItemsDetails, arrayCostsDetails, arrayPaysDetails);
//			}
//		}else{
//			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
//		}
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
					ajax_save_movement('DEFAULT', 'ORDER_APPROVED', '', arrayForValidate);
				}
				if(arr[3] == 'save_invoice'){
					ajax_save_movement('DEFAULT', 'PINVOICE_APPROVED', '', arrayForValidate);
				}
			}else{
				$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
			}
			hideBittionAlertModal();
		});	
//		showBittionAlertModal({content:'Al APROBAR este documento ya no se podrá hacer más modificaciones. ¿Está seguro?'});
//		$('#bittionBtnYes').click(function(){
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
//					ajax_change_state_approved_invoice(arrayItemsDetails, arrayCostsDetails, arrayPaysDetails);	
//				}	
//			}else{
//				$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
//			}
//			hideBittionAlertModal();
//		});
	}
	// (CEA Ztep 2) action when button Cancelar Entrada Almacen is pressed
	function changeStateCancelled(){
		showBittionAlertModal({content:'Al CANCELAR este documento ya no será válido y no habrá marcha atrás. ¿Está seguro?'});
		$('#bittionBtnYes').click(function(){
			var arrayItemsDetails = [];
			arrayItemsDetails = getItemsDetails();
			var arrayCostsDetails = [];
			arrayCostsDetails = getCostsDetails();
			var arrayPaysDetails = [];
			arrayPaysDetails = getPaysDetails();
			if(arr[3] == 'save_order' /*|| arr[3] == 'save_purchase_in'*/){
				ajax_change_state_cancelled_movement_in(arrayItemsDetails);
			}
			if(arr[3]=='save_invoice'){
				ajax_change_state_cancelled_invoice(arrayItemsDetails, arrayCostsDetails, arrayPaysDetails);			
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
//					purchaseId2 = Number(purchaseId) + 1;
					index = 'index_order';
					type = 'ORDER_LOGIC_DELETED';
//					type2 = 'DRAFT';
					break;	
				case 'save_invoice':
					index = 'index_invoice';
					type = 'PINVOICE_LOGIC_DELETED';
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
	
	$('#txtExRate').keydown(function(event) {
			validateOnlyFloatNumbers(event);			
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
		changeStateLogicDeleted();
//		return false;
	});
	
	$('#cbxSuppliers').data('pre', $(this).val());
	$('#cbxSuppliers').change(function(){
	var supplier = $(this).data('pre');
		deleteList(supplier);
	$(this).data('pre', $(this).val());
		return false; //avoid page refresh
	});
  

	$('#txtDate').keydown(function(e){e.preventDefault();});
	$('#txtModalDate').keypress(function(){return false;});
	$('#txtModalDueDate').keypress(function(){return false;});
	$('#txtCode').keydown(function(e){e.preventDefault();});
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
				  ,supplier:$('#cbxSuppliers').val()
				  ,note_code:$('#txtNoteCode').val()
				  ,description:$('#txtDescription').val()
				  ,exRate:$('#txtExRate').val()
			  },
            beforeSend: showProcessing(),
            success: function(data){
				var arrayCatch = data.split('|');

				if(arrayCatch[0] == 'insertado'){ 
					$('#txtCode').val(arrayCatch[1]);
changeLabelDocumentState('ORDER_PENDANT'); //#UNICORN
					$('#btnApproveState').show();
$('#btnLogicDeleteState').show();
					$('#txtPurchaseIdHidden').val(arrayCatch[2]);
					$('#txtGenericCode').val(arrayCatch[3]);
					$('#cbxSuppliers').attr('disabled','disabled');
					$('#txtExRate').removeAttr('disabled');
				}
				
				$('#btnPrint').show();
				$('#boxMessage').html('');
				showGrowlMessage('ok', 'Cambios guardados.');
//				$('#boxMessage').html('<div class="alert alert-success">\n\
//				<button type="button" class="close" data-dismiss="alert">&times;</button>Guardado con exito<div>');
				$('#processing').text('');
/*					$('#boxMessage').html('<div class="alert alert-success">\n\
					<button type="button" class="close" data-dismiss="alert">&times;</button>Guardado con exito<div>');
					$('#processing').text('');*/
			},
			error:function(data){
				showGrowlMessage('error', 'Vuelva a intentarlo.');
//				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
	function ajax_save_invoice(arrayItemsDetails, arrayCostsDetails, arrayPaysDetails ){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_save_invoice",			
            data:{arrayItemsDetails: arrayItemsDetails 
				  ,arrayCostsDetails: arrayCostsDetails	
				  ,arrayPaysDetails: arrayPaysDetails	
				  ,purchaseId:$('#txtPurchaseIdHidden').val()
				  ,date:$('#txtDate').val()
				  ,supplier:$('#cbxSuppliers').val()
				  ,note_code:$('#txtNoteCode').val()
				  ,description:$('#txtDescription').val()
				  ,exRate:$('#txtExRate').val()
			  },
            beforeSend: showProcessing(),
            success: function(data){
				var arrayCatch = data.split('|');

				if(arrayCatch[0] == 'insertado'){ 
					$('#txtCode').val(arrayCatch[1]);

changeLabelDocumentState('INVOICE_PENDANT'); //#UNICORN
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
				  ,supplier:$('#cbxSuppliers').val()	
				  ,description:$('#txtDescription').val()
				   ,exRate:$('#txtExRate').val()
				  ,note_code:$('#txtNoteCode').val()
				  ,genericCode:$('#txtGenericCode').val()
			  },
            beforeSend: showProcessing(),
            success: function(data){			
				var arrayCatch = data.split('|');
				if(arrayCatch[0] == 'aprobado'){

changeLabelDocumentState('ORDER_APPROVED'); //#UNICORN
					$('#btnApproveState').hide();
					$('#btnCancellState').show();
					$('#btnSaveAll').hide();
					$('#btnLogicDeleteState').hide();
					if ($('#btnAddItem').length > 0){//existe
						$('#btnAddItem').hide();
					}
					$('.columnItemsButtons').hide();

					$('#txtDate').attr('disabled','disabled');
					$('#txtCode').attr('disabled','disabled');
					$('#cbxSuppliers').attr('disabled','disabled');
					$('#txtDescription').attr('disabled','disabled');
					$('#txtExRate').attr('disabled','disabled');
					
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
	
	function ajax_change_state_approved_invoice(arrayItemsDetails, arrayCostsDetails, arrayPaysDetails){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_change_state_approved_invoice",			
            data:{arrayItemsDetails: arrayItemsDetails 
				,arrayCostsDetails: arrayCostsDetails
				,arrayPaysDetails: arrayPaysDetails
				  ,purchaseId:$('#txtPurchaseIdHidden').val()
				  ,date:$('#txtDate').val()
				  ,supplier:$('#cbxSuppliers').val()	
				  ,description:$('#txtDescription').val()
				   ,exRate:$('#txtExRate').val()
				  ,note_code:$('#txtNoteCode').val()
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
					$('#btnLogicDeleteState').hide();
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
					$('#txtExRate').attr('disabled','disabled');
					
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

changeLabelDocumentState('ORDER_CANCELLED'); //#UNICORN
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
	
	function ajax_change_state_cancelled_invoice(arrayItemsDetails, arrayCostsDetails, arrayPaysDetails){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_change_state_cancelled_invoice",			
            data:{arrayItemsDetails: arrayItemsDetails 
				  ,arrayCostsDetails: arrayCostsDetails
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

changeLabelDocumentState('ORDER_CANCELLED'); //#UNICORN
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
	
//	function ajax_logic_delete(doc_code, type, index){
//		$.ajax({
//            type:"POST",
//            url:moduleController + "ajax_logic_delete",			
//            data:{doc_code: doc_code, type: type},
//            success: function(data){
//				if(data === 'success'){
//					showBittionAlertModal({content:'Se eliminó el documento en estado Pendiente', btnYes:'Aceptar', btnNo:''});
//					$('#bittionBtnYes').click(function(){
//						window.location = moduleController + index;
//					});
//					
//				}else{
//					showGrowlMessage('error', 'Vuelva a intentarlo.');
//				}
//			},
//			error:function(data){
//				showGrowlMessage('error', 'Vuelva a intentarlo.');
//			}
//        });
//	}
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
				$('#cbxModalItems').select2();
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
				$('#cbxModalCosts').select2();	
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}
	
//	function ajax_initiate_modal_add_pay(paysAlreadySaved){
//		 $.ajax({
//            type:"POST",
//            url:moduleController + "ajax_initiate_modal_add_pay",			
//  /*data*/  data:{paysAlreadySaved: paysAlreadySaved/*, supplier: $('#cbxSuppliers').val()*//*, transfer:transfer, warehouse2:warehouse2*/},
//            beforeSend: showProcessing(),
//            success: function(data){
//				$('#processing').text('');
//				$('#boxModalInitiatePay').html(data);
//				$('#txtModalDate').val('');  
//				$('#txtModalDueDate').val('');  
//				$('#txtModalPaidAmount').val('');  
//				$('#txtModalDescription').val('');  
//				$('#txtModalState').val(''); 
//				initiateModalPay()
///*				$('#cbxModalCosts').bind("change",function(){ //must be binded 'cause dropbox is loaded by a previous ajax'
//					ajax_update_amount();
//				});
//*///				$('#txtModalPrice').keypress(function(){return false;});
//				
//			},
//			error:function(data){
//				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
//				$('#processing').text('');
//			}
//        });
//	}
	
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
