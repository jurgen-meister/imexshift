$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation
	
	var globalPeriod = $('#globalPeriod').text(); // this value is obtained from the main template.
	var arrayItemsAlreadySaved = []; 
	startEventsWhenExistsItems();
	

	//When exist items, it starts its events and fills arrayItemsAlreadySaved
	function startEventsWhenExistsItems(){
		var arrayAux = [];
		arrayAux = getItemsDetails();
		if(arrayAux[0] !== 0){
			for(var i=0; i< arrayAux.length; i++){
				 arrayItemsAlreadySaved[i] = arrayAux[i]['inv_item_id'];
				 createEventClickEditItemButton(arrayAux[i]['inv_item_id']);
				 createEventClickDeleteItemButton(arrayAux[i]['inv_item_id']);			 
			}
		}
	}

	//validates before add item quantity
	function validateItem(item, quantity, documentQuantity){
		var error = '';
		if(quantity === ''){
			error+='<li>El campo "Cantidad" no puede estar vacio</li>'; 
		}else{
			if(parseInt(quantity, 10) === 0){

				error+='<li>El campo "Cantidad" no puede ser cero</li>'; 
			}
		}
		if(item === ''){error+='<li>El campo "Item" no puede estar vacio</li>';}

		return error;
	}

	function validateBeforeSaveAll(arrayItemsDetails){
		var error = '';
		var date = $('#txtDate').val();
		var dateYear = date.split('/');
		var warehouses = $('#cbxWarehouses').text();
		if ($('#cbxMovementTypes').length > 0){//existe
			var movementTypes = $('#cbxMovementTypes').text();
			if(movementTypes === ''){	error+='<li> El campo "Tipo Movimiento" no puede estar vacio </li>'; }
		}
		if(date === ''){	error+='<li> El campo "Fecha" no puede estar vacio </li>'; }
		
		if(dateYear[2] !== globalPeriod){	error+='<li> El año '+dateYear[2]+' de la fecha del documento no es valida, ya que se encuentra en la gestión '+ globalPeriod +'.</li>'; }
		if(warehouses === ''){	error+='<li> El campo "Almacen" no puede estar vacio </li>'; }

		if ($('#cbxWarehouses2').length > 0){//existe
			if($('#cbxWarehouses').val() === $('#cbxWarehouses2').val()){
				error+='<li> No se puede hacer una transferencia al mismo almacen </li>';
			}
		}

		if(arrayItemsDetails[0] === 0){error+='<li> Debe existir al menos 1 "Item" </li>';}

		var itemZero = findIfOneItemHasQuantityZero(arrayItemsDetails);
		if(itemZero > 0){error+='<li> Se encontraron '+ itemZero +' "Items" con "Cantidad" 0, no puede existir ninguno </li>';}

		return error;
	}

	function findIfOneItemHasQuantityZero(arrayItemsDetails){
		var cont = 0;
		for(var i = 0; i < arrayItemsDetails.length; i++){
			if(parseInt(arrayItemsDetails[i]['quantity'],10) === 0){
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
		if (event.keyCode === 8 || event.keyCode === 9 ) {
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

		if(arrayItemsDetails[0] !== 0){
			ajax_update_multiple_stocks(arrayItemsDetails, warehouse, controlName);
			//alert('Se cambio de "Almacen", se actualizara los "Stocks" de los "Items"');
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
			if(itemId !== ''){//if exist itemId in the array splited because a,b,'' because last field is empty
				arrStatusStock = arrItemsStatusStock[1].split(':');//status:stock
				status = arrStatusStock[0];
				stock = arrStatusStock[1];
				if(status === 'error'){ 
						error+='<li>'+$('#spaItemName'+itemId).text()+': El "Stock = '+stock+'" no es suficiente para la "Cantidad = '+$('#spaQuantity'+itemId).text()+'" requerida.</li>';	
				}
				$('#'+controlName+itemId).text(stock);
			}
		}
		return error;
	}

	function initiateModalAddItem(){
		var error = validateBeforeSaveAll([{0:0}]);//I send [{0:0}] 'cause it doesn't care to validate if arrayItemsDetails is empty or not
		if( error === ''){
			if(arrayItemsAlreadySaved.length === 0){  //For fix undefined index
				arrayItemsAlreadySaved = [0]; //if there isn't any row, the array must have at least one field 0 otherwise it sends null
			}
			$('#btnModalAddItem').show();
			$('#btnModalEditItem').hide();
			$('#boxModalValidateItem').html('');//clear error message
			ajax_initiate_modal_add_item_in(arrayItemsAlreadySaved);
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
	}

	function initiateModalEditItem(objectTableRowSelected){
		var error = validateBeforeSaveAll([{0:0}]);//I send [{0:0}] 'cause it doesn't care to validate if arrayItemsDetails is empty or not
		if( error === ''){
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
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
		
	}

	function createEventClickEditItemButton(itemId){
			$('#btnEditItem'+itemId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr');
					initiateModalEditItem(objectTableRowSelected);
					return false; //avoid page refresh
			});
	}

	function createEventClickDeleteItemButton(itemId){
		$('#btnDeleteItem'+itemId).bind("click",function(){ //must be binded 'cause loaded live with javascript'
					var objectTableRowSelected = $(this).closest('tr');
					deleteItem(objectTableRowSelected);
					return false; //avoid page refresh
		});
	}

	function deleteItem(objectTableRowSelected){
		//var arrayItemsDetails = [];
		var arrayItemsDetails = getItemsDetails();
		var error = validateBeforeSaveAll([{0:0}]);//Send [{0:0}] 'cause I won't use arrayItemsDetails classic validation, I will use it differently for this case (as done below)
		if(arrayItemsDetails.length === 1){error+='<li> Debe existir al menos 1 "Item" </li>';}
		if( error === ''){
			showBittionAlertModal({content:'¿Está seguro de eliminar este item?'});
			$('#bittionBtnYes').click(function(){
				ajax_save_movement('DELETE', 'PENDANT', objectTableRowSelected, []);
				/*
				//var itemId = objectTableRowSelected.find('#txtItemId').val();//$('#cbxModalItems').val();
				if(arr[3] === 'save_in'){//0
					//ajax_save_item('DELETEITEM', 0, itemId, '', '', '', '', objectTableRowSelected);
					ajax_save_movement('save_in', 'DELETE', 'PENDANT', objectTableRowSelected);
				}
				if(arr[3] === 'save_purchase_in'){//1
					ajax_save_movement('save_purchase_in', 'DELETE', 'PENDANT', objectTableRowSelected);
				}
				if(arr[3] === 'save_out'){//0
					ajax_save_movement('save_out', 'DELETE', 'PENDANT', objectTableRowSelected);
				}
				if(arr[3] === 'save_sale_out'){//2
					ajax_save_movement('save_sale_out', 'DELETE', 'PENDANT', objectTableRowSelected);
				}
				if(arr[3] === 'save_warehouse_transfer'){//2
					ajax_save_movement('save_warehouse_transfer', 'DELETE', 'PENDANT', objectTableRowSelected);
				}
				*/
			});
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
	}

	function createRowItemTable(itemId, itemCodeName, stock, quantity, stock2){
		var row = '<tr>';
		row +='<td><span id="spaItemName'+itemId+'">'+itemCodeName+'</span><input type="hidden" value="'+itemId+'" id="txtItemId" ></td>';
		row +='<td><span id="spaStock'+itemId+'">'+stock+'</span></td>';
		if(stock2 !== ''){
			row +='<td><span id="spaStock2-'+itemId+'">'+stock2+'</span></td>';
		}
		row +='<td><span id="spaQuantity'+itemId+'">'+quantity+'</span></td>';
		row +='<td class="columnItemsButtons">';
		row +='<a class="btn btn-primary" href="#" id="btnEditItem'+itemId+'" title="Editar"><i class="icon-pencil icon-white"></i></a> ';
		row +='<a class="btn btn-danger" href="#" id="btnDeleteItem'+itemId+'" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
		row +='</td>';
		row +='</tr>';
		$('#tablaItems > tbody:last').append(row);
	}

	function editItem(){
		var itemId = $('#cbxModalItems').val();
		var quantity = $('#txtModalQuantity').val();
		var itemCodeName = $('#cbxModalItems option:selected').text();
		var error = validateItem(itemCodeName, quantity, ''); 
		if(error === ''){
			ajax_save_movement('EDIT', 'PENDANT', '', []);
			/*
			if(arr[3] === 'save_in'){//0
				//ajax_save_item('EDITITEM', 0, itemId, quantity, itemCodeName, '', '','');
				ajax_save_movement('save_in', 'EDIT', 'PENDANT', '');
			}
			if(arr[3] === 'save_purchase_in'){//1
				ajax_save_movement('save_purchase_in', 'EDIT', 'PENDANT', '');
			}
			if(arr[3] === 'save_out'){//0
				ajax_save_movement('save_out', 'EDIT', 'PENDANT', '');
			}
			if(arr[3] === 'save_sale_out'){//2
				ajax_save_movement('save_sale_out', 'EDIT', 'PENDANT', '');
			}
			if(arr[3] === 'save_warehouses_transfer'){//2
				ajax_save_movement('save_warehouses_transfer', 'EDIT', 'PENDANT', '');
			}
			*/
		}else{
			$('#boxModalValidateItem').html('<ul>'+error+'</ul>');
		}
	}

	function addItem(){
		
		var itemId = $('#cbxModalItems').val();
		var quantity = $('#txtModalQuantity').val();
		var itemCodeName = $('#cbxModalItems option:selected').text();//
		var stock = $('#txtModalStock').val();
		var stock2 = '';
		if(arr[3] === 'save_warehouses_transfer'){
			stock2 = $('#txtModalStock2').val();
		}
		
		var error = validateItem(itemCodeName, quantity, ''); 
		if(error === ''){
			ajax_save_movement('ADD', 'PENDANT', '', []);
			/*
			if(arr[3] === 'save_in'){//0
				//ajax_save_item('ADDITEM', 0, itemId, quantity, itemCodeName, stock, stock2,'');
				ajax_save_movement('save_in', 'ADD', 'PENDANT', '');
			}
			if(arr[3] === 'save_purchase_in'){//1
				ajax_save_movement('save_purchase_in', 'ADD', 'PENDANT', '');
			}
			if(arr[3] === 'save_out'){//0
				ajax_save_movement('save_out', 'ADD', 'PENDANT', '');
			}
			if(arr[3] === 'save_sale_out'){//2
				ajax_save_movement('save_sale_out', 'ADD', 'PENDANT', '');
			}
			//transfer goes here
			if(arr[3] === 'save_warehouses_transfer'){
				ajax_save_movement('save_warehouses_transfer', 'ADD', 'PENDANT', '');
			}
			*/
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

		if(arrayItemsDetails.length === 0){  //For fix undefined index
			arrayItemsDetails = [0]; //if there isn't any row, the array must have at least one field 0 otherwise it sends null
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
		if( error === ''){
			ajax_save_movement('DEFAULT', 'PENDANT', '', []);
			//I send [{0:0}] 'cause don't not need to update stock in PENDANT 
			/*
			if(arr[3] === 'save_in'){
				//ajax_save_movement([{0:0}], 'ENT', 0, 'PENDANT');
				ajax_save_movement('save_in', 'DEFAULT', 'PENDANT', '');
			}
			if(arr[3] === 'save_purchase_in'){
				ajax_save_movement('save_purchase_in', 'DEFAULT', 'PENDANT', '');
			}
			if(arr[3] === 'save_out'){
				ajax_save_movement('save_out', 'DEFAULT', 'PENDANT', '');
			}
			if(arr[3] === 'save_sale_out'){
				ajax_save_movement('save_sale_out', 'DEFAULT', 'PENDANT', '');
			}
			if(arr[3] === 'save_warehouses_transfer'){
				ajax_save_movement('save_warehouses_transfer', 'DEFAULT', 'PENDANT', '');
			}
			*/
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
		showBittionAlertModal({content:'Al APROBAR este documento ya no se podrá hacer más modificaciones. ¿Está seguro?'});
		$('#bittionBtnYes').click(function(){
			var arrayForValidate = [];
			arrayForValidate = getItemsDetails();
			var error = validateBeforeSaveAll(arrayForValidate);
			if( error === ''){
				ajax_save_movement('DEFAULT', 'APPROVED', '', arrayForValidate);
				/*
				if(arr[3] === 'save_in'){
					ajax_save_movement(arrayItemsDetails, 'ENT', 0, 'APPROVED');
				}
				if(arr[3] === 'save_purchase_in'){
					ajax_save_movement(arrayItemsDetails, 'ENT', 1, 'APPROVED');
				}
				if(arr[3] === 'save_out'){
					ajax_save_movement(arrayItemsDetails, 'SAL', 0, 'APPROVED');
				}
				if(arr[3] === 'save_sale_out'){
					ajax_save_movement(arrayItemsDetails, 'SAL', 2, 'APPROVED');
				}
				if(arr[3] === 'save_warehouses_transfer'){
					ajax_save_warehouses_transfer(arrayItemsDetails, 'APPROVED');
				}
				*/
			}else{
				$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
			}
			hideBittionAlertModal();
		});
	}

	function changeStateCancelled(){
		showBittionAlertModal({content:'Al CANCELAR este documento ya no será válido y no habrá marcha atrás. ¿Está seguro?'});
		$('#bittionBtnYes').click(function(){
			//var arrayItemsDetails = [];
			//arrayItemsDetails = getItemsDetails();
			var arrayForValidate = [];
			arrayForValidate = getItemsDetails();
			ajax_save_movement('DEFAULT', 'CANCELLED', '', arrayForValidate);
			/*
			if(arr[3] === 'save_in'){
				ajax_save_movement(arrayItemsDetails, 'ENT', 0, 'CANCELLED');
			}
			if(arr[3] === 'save_purchase_in'){
				ajax_save_movement(arrayItemsDetails, 'ENT', 1, 'CANCELLED');
			}
			if(arr[3] === 'save_out'){
				ajax_save_movement(arrayItemsDetails, 'SAL', 0, 'CANCELLED');
			}
			if(arr[3] === 'save_sale_out'){
				ajax_save_movement(arrayItemsDetails, 'SAL', 2, 'CANCELLED');
			}
			if(arr[3] === 'save_warehouses_transfer'){
				ajax_save_warehouses_transfer(arrayItemsDetails, 'CANCELLED');
			}
			*/
			hideBittionAlertModal();
		});
	}
	
	function deleteStatePendant(){
		showBittionAlertModal({content:'¿Está seguro de eliminar este documento en estado Pendiente?'});
		$('#bittionBtnYes').click(function(){
			var code = $('#txtCode').val();
			var type ='normal';
			var index;
			switch(arr[3]){
				case 'save_in':
					index = 'index_in';
					break;	
				case 'save_out':
					index = 'index_out';
					break;	
				case 'save_purchase_in':
					index = 'index_purchase_in';
					break;	
				case 'save_sale_out':
					index = 'index_sale_out';
					break;	
				case 'save_warehouses_transfer':
					index = 'index_warehouses_transfer';
					code = $('#txtDocumentCode').val();
					type = 'transfer';
					break;	
			}
			ajax_logic_delete(code, type, index);
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
	$('#txtModalQuantity').keydown(function(event) {
			validateOnlyNumbers(event);			
	});

	//Calendar script
   $("#txtDate").datepicker({
	  showButtonPanel: true
   });
   
   //Logic delete state pendant
   $('#btnLogicDelete').click(function(){
	  deleteStatePendant(); 
   });
   
	//Call modal
	$('#btnAddItem').click(function(){
		initiateModalAddItem();
		return false; //avoid page refresh
	});
	//Add a new item quantity
	$('#btnModalAddItem').click(function(){
		addItem();
		//ajax_save_item();
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
		updateItemsWarehouseStocks(warehouse, controlName);
	});

	$('#cbxWarehouses2').change(function(){
		//validateWarehouse();
		var warehouse=$('#cbxWarehouses2').val();
		var controlName ='spaStock2-';
		updateItemsWarehouseStocks(warehouse, controlName);
	});

	$('#txtDate').keydown(function(e){e.preventDefault();});
	$('#txtCode').keydown(function(e){e.preventDefault();});
	if ($('#txtDocumentCode').length > 0){//existe
		$('#txtDocumentCode').keydown(function(e){e.preventDefault();});
	}
	
	//************************************************************************//
	//////////////////////////////////END-CONTROLS EVENTS//////////////////////
	//************************************************************************//




	//************************************************************************//
	//////////////////////////////////BEGIN-AJAX FUNCTIONS//////////////////////
	////************************************************************************//
	function ajax_delete_item(){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_delete_item",			
            data:{ 
				
			},
			beforeSend: showProcessing(),
			success: function(data){
				
			},
			error:function(data){
		
			}
		});
	}
	
	
	//*****************************************************************************************************************************//
	function setOnData(ACTION, OPERATION, STATE, objectTableRowSelected, arrayForValidate){
		var DATA = [];
		//constants
		var movementId=$('#txtMovementIdHidden').val();
		var date = $('#txtDate').val();
		var warehouseId = $('#cbxWarehouses').val();
		var description = $('#txtDescription').val();
		//variables
		var code = '';
		var documentCode = '';
		var warehouseId2 = 0;
		var movementTypeId = 0;
		var itemId = 0;
		var quantity = 0;
		//only used for ADD
		var itemCodeName = '';
		var stock = 0;
		var stock2 = '';//otherwise create one more column on details table
		
		//for validate
		//var arrayForValidate = [];
		
		//Movement setup variables
		if((ACTION !== 'save_in') || (ACTION !== 'save_out')){
			documentCode = $('#txtDocumentCode').val();
			movementTypeId = $('#cbxMovementTypes').val();
		}
		/*
		if((ACTION === 'save_in') || (ACTION === 'save_purchase_in')){
			if(STATE === 'APPROVED'){
				arrayForValidate = [];
			}
		}
		if((ACTION === 'save_out') || (ACTION === 'save_sale_out')){
			if(STATE === 'CANCELLED'){
				arrayForValidate = [];
			}
		}
		*/
		
		if((ACTION === 'save_purchase_in' || ACTION === 'save_sale_out') && (movementId === '')  && (movementId === '')){
			arrayForValidate = getItemsDetails();
		}
		
		if(ACTION === 'save_warehouses_transfer'){
			warehouseId2 = $('#cbxWarehouses2').val();
		}
		if(ACTION !== 'save_warehouse_transfer'){
			code = $('#txtCode').val();
		}
		//MovementDetails(Item) setup variables
		if(OPERATION === 'ADD' || OPERATION === 'EDIT'){
			itemId = $('#cbxModalItems').val();
			quantity = $('#txtModalQuantity').val();
			if(OPERATION === 'ADD'){
				itemCodeName = $('#cbxModalItems option:selected').text();
				stock = $('#txtModalStock').val();
				if(ACTION > 'save_warehouse_transfer'){//exists
					stock2 = $('#txtModalStock2').val();
				}
			}
		}
		if(OPERATION === 'DELETE'){
			itemId = objectTableRowSelected.find('#txtItemId').val();
		}
		//setting data
		DATA ={   'movementId':movementId
				  ,'date':date
				  ,'warehouseId':warehouseId
				  ,'description':description  
				  ,'code':code
				  ,'documentCode':documentCode
				  ,'warehouseId2':warehouseId2
				  ,'movementTypeId':movementTypeId
				  ,'itemId':itemId
				  ,'quantity':quantity
				  ,'ACTION':ACTION
				  ,'OPERATION':OPERATION
				  ,'STATE':STATE
				  ,itemCodeName:itemCodeName
				  ,stock:stock
				  ,stock2:stock2
				  ,arrayForValidate:arrayForValidate
			  };
		  
		return DATA;
	}
	
	
	function setOnPendant(DATA, ACTION, OPERATION, STATE, objectTableRowSelected , itemId, itemCodeName, quantity, stock, stock2){
		if($('#txtMovementIdHidden').val() === ''){
			if(ACTION === 'save_warehouses_transfer'){
				$('#txtDocumentCode').val(DATA[2]);
			}else{
				$('#txtCode').val(DATA[2]);
			}
			
			$('#btnApproveState, #btnPrint, #btnLogicDelete').show();
			$('#txtMovementIdHidden').val(DATA[1]);
			changeLabelDocumentState(STATE); //#UNICORN
		}
		/////////////************************************////////////////////////
		//Item's table setup
		if(OPERATION === 'ADD'){
			createRowItemTable(itemId, itemCodeName, stock, parseInt(quantity,10), stock2);
			createEventClickEditItemButton(itemId);
			createEventClickDeleteItemButton(itemId);
			arrayItemsAlreadySaved.push(itemId);  //push into array of the added item
			$('#modalAddItem').modal('hide');
		}
		if(OPERATION === 'EDIT'){
			$('#spaQuantity'+itemId).text(parseInt(quantity,10));
			$('#modalAddItem').modal('hide');
		}
		if(OPERATION === 'DELETE'){
			arrayItemsAlreadySaved = jQuery.grep(arrayItemsAlreadySaved, function(value){
				return value !== itemId;
			});
			objectTableRowSelected.remove();
			hideBittionAlertModal();
		}
		showGrowlMessage('ok', 'Cambios guardados.');
	}
	
	function setOnApproved(DATA, STATE, ACTION){
		$('#txtCode').val(DATA[2]);
		$('#btnApproveState, #btnLogicDelete, #btnSaveAll, #btnAddMovementType, .columnItemsButtons').hide();
		$('#btnCancellState').show();
		$('#txtDate, #txtCode, #cbxWarehouses, #txtDescription').attr('disabled','disabled');
		if ($('#btnAddItem').length > 0){//existe
			$('#btnAddItem').hide();
		}
		//if ($('#txtDocumentCode').length > 0){//existe
		//	$('#txtDocumentCode').attr('disabled','disabled');
		//}
		if (ACTION === 'save_in' || ACTION === 'save_out'){
			$('#cbxMovementTypes').attr('disabled','disabled');
		}else{
			$('#txtDocumentCode').attr('disabled','disabled');
		}
		if(ACTION === 'save_warehouses_transfer'){
			$('#cbxWarehouses2').attr('disabled','disabled');
		}
		changeLabelDocumentState(STATE); //#UNICORN
		showGrowlMessage('ok', 'Aprobado.');
	}
	
	function setOnCancelled(STATE){
		$('#btnCancellState').hide();
		changeLabelDocumentState(STATE); //#UNICORN
		showGrowlMessage('ok', 'Cancelado.');
	}
	
	function setOnValidation(DATA, ACTION){
		var arrayItemsStocks = DATA[1].split(',');
		var validation = '';
		
		if(ACTION === 'save_warehouses_transfer'){
			if(DATA[3] === 'APPROVED'){
				validation = validateBeforeMoveOut(arrayItemsStocks, 'spaStock');
				var arrayItemsStocksDestination = DATA[2].split(',');
				updateMultipleStocks(arrayItemsStocksDestination, 'spaStock2-');
			}else{
				validation = validateBeforeMoveOut(arrayItemsStocks, 'spaStock2-');
				var arrayItemsStocksDestination = DATA[2].split(',');
				updateMultipleStocks(arrayItemsStocksDestination, 'spaStock');
			}
		}else{
			validation = validateBeforeMoveOut(arrayItemsStocks, 'spaStock');
		}
		$('#boxMessage').html('<div class="alert alert-error">\n\
		<button type="button" class="close" data-dismiss="alert">&times;</button>\n\
		<p>No se pudo realizar la operación debido a falta de STOCK:</p><ul>'+validation+'</ul><div>');
	}
	
	function setOnError(){
		showGrowlMessage('error', 'Vuelva a intentarlo.');
	}
	
	//Save movement 
	function ajax_save_movement(OPERATION, STATE, objectTableRowSelected, arrayForValidate){//SAVE_IN/ADD/PENDANT
		var ACTION = arr[3];
		var dataSent = setOnData(ACTION, OPERATION, STATE, objectTableRowSelected, arrayForValidate);
		//Ajax Interaction	
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_save_movement",//saveMovement			
            data:dataSent,
            beforeSend: showProcessing(),
            success: function(data){
				$('#boxMessage').html('');//this for order goes here
				$('#processing').text('');//this must go at the begining not at the end, otherwise, it won't work when validation is send
				var dataReceived = data.split('|');
				//////////////////////////////////////////
				if(dataReceived[0] === 'APPROVED' || dataReceived[0] === 'CANCELLED'){
					//update items stocks if no error
					//if(dataReceived[3] !== 'undefined'){ //it's not necesary 'cause setOnValidation will trigger before
						var arrayItemsStocks = dataReceived[3].split(',');
						updateMultipleStocks(arrayItemsStocks, 'spaStock');
						if(ACTION === 'save_warehouses_transfer'){
							var arrayItemsStocksDestination = dataReceived[4].split(',');
							updateMultipleStocks(arrayItemsStocksDestination, 'spaStock2-');
						}
					//}
				}
				switch(dataReceived[0]){
					case 'PENDANT':
						setOnPendant(dataReceived, ACTION, OPERATION, STATE, objectTableRowSelected , dataSent['itemId'], dataSent['itemCodeName'], dataSent['quantity'], dataSent['stock'], dataSent['stock2']);
						break;
					case 'APPROVED':
						setOnApproved(dataReceived, STATE, ACTION);
						break;
					case 'CANCELLED':
						setOnCancelled(STATE);
						break;
					case 'VALIDATION':
						setOnValidation(dataReceived, ACTION);
						//alert('jfjfjfj');
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

	
	
	//Get items and stock for the fist item when inititates modal
	function ajax_initiate_modal_add_item_in(itemsAlreadySaved){
		var transfer = '';
		var warehouse2 = '';
		if(arr[3] === 'save_warehouses_transfer'){
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
				
				$('#cbxModalItems').select2();
				//$('#cbxModalItems').css('z-index', '9999999');

			},
			error:function(data){
				//$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text('');
			}
        });
	}

	//Update one stock value
	function ajax_update_stock_modal(){
		var transfer = '';
		var warehouse2 = '';
		if(arr[3] === 'save_warehouses_transfer'){
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
            beforeSend: showBittionAlertModal({content:'Actualizando stocks...', btnYes:'', btnNo:''}),
            success: function(data){
				var arrayItemsStocks = data.split(',');
				updateMultipleStocks(arrayItemsStocks, controlName);
				$('#processing').text('');
				hideBittionAlertModal();
			},
			error:function(data){
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
				$('#processing').text('');
			}
        });
	}

	function ajax_logic_delete(code, type, index){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_logic_delete",			
            data:{code: code, type: type},
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

	//************************************************************************//
	//////////////////////////////////END-AJAX FUNCTIONS////////////////////////
	//************************************************************************//


//END SCRIPT	
});