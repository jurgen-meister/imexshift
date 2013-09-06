$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation

	var globalPeriod = $('#globalPeriod').text(); // this value is obtained from the main template.
	
	var arrayItemsAlreadySaved = []; 
	var arrayWarehouseItemsAlreadySaved = []; 
//var itemsCounter = 0;
	startEventsWhenExistsItems();
	
	var arrayPaysAlreadySaved = []; 
	startEventsWhenExistsPays();
	
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
// itemsCounter = itemsCounter + 1;				 
			}
		}
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
	
	function initiateModalAddPay(){
		var error = validateBeforeSaveAll([{0:0}]);//I send [{0:0}] 'cause it doesn't care to validate if arrayItemsDetails is empty or not
		if( error === ''){
			if(arrayPaysAlreadySaved.length === 0){  //For fix undefined index
				arrayPaysAlreadySaved = [0] //if there isn't any row, the array must have at least one field 0 otherwise it sends null
			}
			$('#btnModalAddPay').show();
			$('#btnModalEditPay').hide();
			$('#boxModalValidatePay').html('');//clear error message
			ajax_initiate_modal_add_pay(arrayPaysAlreadySaved,payDebt);
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
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
//			$('#cbxModalPays').empty();
//			$('#cbxModalPays').append('<option value="'+payIdForEdit+'">'+objectTableRowSelected.find('td:first').text()+'</option>');
			initiateModalPay();
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}	
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
	
	function createEventClickEditPayButton(dateId){
			$('#btnEditPay'+dateId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr')
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
	
	function deletePay(objectTableRowSelected){
		var arrayPaysDetails = getPaysDetails();
		var error = validateBeforeSaveAll([{0:0}]);//Send [{0:0}] 'cause I won't use arrayItemsDetails classic validation, I will use it differently for this case (as done below)
	//	if(arrayPaysDetails.length === 1){error+='<li> Debe existir al menos 1 "Pago" </li>';}
		if( error === ''){
			showBittionAlertModal({content:'¿Está seguro de eliminar este pago?'});
			$('#bittionBtnYes').click(function(){
				ajax_save_movement('DELETE_PAY', 'SINVOICE_PENDANT', objectTableRowSelected, []);
				return false;
			});
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
//		if(confirm('Esta seguro de Eliminar el pago?')){	
//
//			var payIdForDelete = objectTableRowSelected.find('#txtPayId').val();  //
//			arrayPaysAlreadySaved = jQuery.grep(arrayPaysAlreadySaved, function(value){
//				return value != payIdForDelete;
//			});
//			objectTableRowSelected.remove();
//		}
	}
	
	// (GC Ztep 3) function to fill Items list when saved in modal triggered by addItem() //type="hidden"
	function createRowItemTable(itemId, itemCodeName, salePrice, quantity, warehouse, warehouseId, stock, subtotal){
		var row = '<tr id="itemRow'+itemId+'w'+warehouseId+'" >';
		row +='<td><span id="spaItemName'+itemId+'">'+itemCodeName+'</span><input type="hidden" value="'+itemId+'" id="txtItemId" ></td>';
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
		
		var dateId = '';
		var payDate = '';
		var payAmount = 0;
		var payDescription = '';
		
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
		if(OPERATION === 'ADD' || OPERATION === 'EDIT' || OPERATION === 'ADD_PAY' || OPERATION === 'EDIT_PAY'){
			warehouseId = $('#cbxModalWarehouses').val();		
			itemId = $('#cbxModalItems').val();
			salePrice = $('#txtModalPrice').val();
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
		
		if(OPERATION === 'DELETE_PAY'){
			payDate = objectTableRowSelected.find('#txtPayDate').val();
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
				
				,'dateId':dateId
				,'payDate':payDate
				,'payAmount':payAmount
				,'payDescription':payDescription
		
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
	
	function setOnPendant(DATA, ACTION, OPERATION, STATE, objectTableRowSelected, warehouseId, warehouse, itemId, itemCodeName, salePrice, stock, quantity, subtotal, dateId, payDate, payAmount, payDescription){
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
			$('#total').text(parseFloat(getTotal()).toFixed(2)+' Bs.');
			$('#modalAddItem').modal('hide');
			highlightTemporally('#itemRow'+itemId+'w'+warehouseId);
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
			$('#spaQuantity'+itemId+'w'+warehouseId).text(parseInt(quantity,10));
			$('#spaSalePrice'+itemId+'w'+warehouseId).text(parseFloat(salePrice).toFixed(2));	
			$('#spaSubtotal'+itemId+'w'+warehouseId).text(parseFloat(Number(quantity) * Number(salePrice)).toFixed(2));
			$('#total').text(parseFloat(getTotal()).toFixed(2)+' Bs.');
			$('#modalAddItem').modal('hide');
			highlightTemporally('#itemRow'+itemId+'w'+warehouseId);
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
			var itemIdForDelete = objectTableRowSelected.find('#txtItemId').val();
			subtotal = $('#spaSubtotal'+itemIdForDelete+'w'+warehouseId).text();		
			hideBittionAlertModal();
			
			objectTableRowSelected.fadeOut("slow", function() {
				$(this).remove();
			});
			itemsListWhenExistsItems();
			warehouseListWhenExistsItems();
			$('#countItems').text(arrayItemsAlreadySaved.length-1);	//because arrayItemsAlreadySaved updates after all is done
			$('#total').text(parseFloat(getTotal()-subtotal).toFixed(2)+' Bs.');
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
						setOnPendant(dataReceived, ACTION, OPERATION, STATE, objectTableRowSelected, dataSent['warehouseId'], dataSent['warehouse'], dataSent['itemId'], dataSent['itemCodeName'], dataSent['salePrice'], dataSent['stock'], dataSent['quantity'], dataSent['subtotal'], dataSent['dateId'], dataSent['payDate'], dataSent['payAmount'], dataSent['payDescription']);
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
	
	function addPay(){
//		var payId = $('#txtPayDate').val();
		var payDate = $('#txtModalDate').val();
		var payAmount = $('#txtModalPaidAmount').val();
//		var payDescription = $('#txtModalDescription').val();
		var error = validatePay(payDate, parseFloat(payAmount).toFixed(2));  
		if(error == ''){
			if(arr[3] == 'save_invoice'){
				ajax_save_movement('ADD_PAY', 'SINVOICE_PENDANT', '', []);
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
				ajax_save_movement('EDIT_PAY', 'SINVOICE_PENDANT', '', []);
			}
		}else{
			$('#boxModalValidateItem').html('<ul>'+error+'</ul>');
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
	
	//edit an existing item quantity
	$('#btnModalEditPay').click(function(){
		editPay();
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
