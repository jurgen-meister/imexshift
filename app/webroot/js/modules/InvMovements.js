$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation
	
	var globalPeriod = $('#globalPeriod').text(); // this value is obtained from the main template.
	var arrayItemsAlreadySaved = []; 
	startEventsWhenExistsItems();
	
	if(arr[3] === 'report'){
		$('select').select2();
		//$('select#cbxCategories,select#cbxWarehouses').select2();//doesn't work without specifying select 'cause extra div for multiple
		$("#txtReportStartDate, #txtReportFinishDate").datepicker({
			showButtonPanel: true
		});
		 
		startDataTable();
	}
	
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
	//$('#cbxWarehouses').select2(); // to create advanced select or combobox
	
	//clearFieldsForFirefox();

	//************************************************************************//
	//////////////////////////////////BEGIN-FUNCTIONS////////////////
	//************************************************************************//
	//firefox doesn't clear by himself the fields when there is a refresh in a new form
	/*
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
	*/
   
   function startDataTable(){
	   $('.data-table').dataTable({
			"bJQueryUI": true,
			//"sPaginationType": "full_numbers",
			"sDom": '<"">t<"F"f>i',
			"sScrollY": "200px",
			"bPaginate": false,
			"oLanguage": {
				"sSearch": "Filtrar:",
				 "sZeroRecords":  "No hay resultados que coincidan.",
				 //"sInfo":         "Ids from _START_ to _END_ of _TOTAL_ total" //when pagination exists
				 "sInfo": "Encontrados _TOTAL_ Items",
				 "sInfoEmpty": "Encontrados 0 Items",
				 "sInfoFiltered": "(filtrado de _MAX_ Items)"
			}
		});
		$('input[type=checkbox]').uniform();
		$("#title-table-checkbox").click(function() {
			var checkedStatus = this.checked;
			var checkbox = $(this).parents('.widget-box').find('tr td:first-child input:checkbox');		
			checkbox.each(function() {
				this.checked = checkedStatus;
				if (checkedStatus === this.checked) {
					$(this).closest('.checker > span').removeClass('checked');
				}
				if (this.checked) {
					$(this).closest('.checker > span').addClass('checked');
				}
			});
		});	
   }
   
   
	   
 
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
		if(arrayItemsAlreadySaved.length === 0){  //For fix undefined index
			arrayItemsAlreadySaved = [0]; //if there isn't any row, the array must have at least one field 0 otherwise it sends null
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
		showBittionAlertModal({content:'¿Está seguro de eliminar este item?'});
		$('#bittionBtnYes').click(function(){
			var itemIdForDelete = objectTableRowSelected.find('#txtItemId').val();  //
			arrayItemsAlreadySaved = jQuery.grep(arrayItemsAlreadySaved, function(value){
				return value !== itemIdForDelete;
			});
			objectTableRowSelected.remove();
			hideBittionAlertModal();
		});
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
		if(arr[3] === 'save_warehouses_transfer'){
			stock2 = $('#txtModalStock2').val();
		}
		var error = validateItem(itemCodeName, quantity, ''); 
		if(error === ''){
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
			if(arr[3] === 'save_in'){
				ajax_save_movement(arrayItemsDetails, 'ENT', 0, 'PENDANT');
			}
			if(arr[3] === 'save_purchase_in'){
				ajax_save_movement(arrayItemsDetails, 'ENT', 1, 'PENDANT');
			}
			if(arr[3] === 'save_out'){
				ajax_save_movement(arrayItemsDetails, 'SAL', 0, 'PENDANT');
			}
			if(arr[3] === 'save_sale_out'){
				ajax_save_movement(arrayItemsDetails, 'SAL', 2, 'PENDANT');
			}
			if(arr[3] === 'save_warehouses_transfer'){
				ajax_save_warehouses_transfer(arrayItemsDetails, 'PENDANT');
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
		showBittionAlertModal({content:'Al APROBAR este documento ya no se podrá hacer más modificaciones. ¿Está seguro?'});
		$('#bittionBtnYes').click(function(){
			var arrayItemsDetails = [];
			arrayItemsDetails = getItemsDetails();
			var error = validateBeforeSaveAll(arrayItemsDetails);
			if( error === ''){
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
			}else{
				$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
			}
			hideBittionAlertModal();
		});
	}

	function changeStateCancelled(){
		showBittionAlertModal({content:'Al CANCELAR este documento ya no será válido y no habrá marcha atrás. ¿Está seguro?'});
		$('#bittionBtnYes').click(function(){
			var arrayItemsDetails = [];
			arrayItemsDetails = getItemsDetails();
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
	
	
	function getGroupItemsAndFilters(){
		ajax_get_group_items_and_filters();
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
	
	$('#cbxReportGroupTypes').change(function(){
		getGroupItemsAndFilters();
	});
	//************************************************************************//
	//////////////////////////////////END-CONTROLS EVENTS//////////////////////
	//************************************************************************//




	//************************************************************************//
	//////////////////////////////////BEGIN-AJAX FUNCTIONS//////////////////////
	////************************************************************************//

	//Save movement (PENDANT, APPROVED, CANCELLED)
	function ajax_save_movement(arrayItemsDetails, movementStatus, movementType, movementState){
		var documentCode ='NO';
		if ($('#cbxMovementTypes').length > 0){//exists
			movementType = $('#cbxMovementTypes').val();
		}
		
		if ($('#txtDocumentCode').length > 0){//exists
			documentCode = $('#txtDocumentCode').val();
		}
		
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_save_movement",			
            data:{ arrayItemsDetails: arrayItemsDetails 
				  ,movementId:$('#txtMovementIdHidden').val()
				  ,date:$('#txtDate').val()
				  ,warehouse:$('#cbxWarehouses').val()
				  ,movementType:movementType
				  ,description:$('#txtDescription').val()
				  ,documentCode:documentCode
				  ,code:$('#txtCode').val()
				  ,movementStatus:movementStatus
				  ,movementState:movementState
			  },
            beforeSend: showProcessing(),
            success: function(data){
				$('#boxMessage').html('');//this for order goes here
				$('#processing').text('');//this must go at the begining not at the end, otherwise, it won't work when validation is send
				var arrayCatch = data.split('|');
				//////////////////////////////////////////
				if(arrayCatch[0] === 'PENDANT'){ 
					$('#txtCode').val(arrayCatch[2]);
					$('#btnApproveState, #btnPrint, #btnLogicDelete').show();
					$('#txtMovementIdHidden').val(arrayCatch[1]);
					changeLabelDocumentState(movementState); //#UNICORN
					showGrowlMessage('ok', 'Cambios guardados.');
				}				
				//////////////////////////////////////////
				if(arrayCatch[0] === 'APPROVED'){ 
					$('#btnApproveState, #btnLogicDelete, #btnSaveAll, #btnAddMovementType, .columnItemsButtons').hide();
					$('#btnCancellState').show();
					$('#txtDate, #txtCode, #cbxWarehouses, #txtDescription').attr('disabled','disabled');
					if ($('#btnAddItem').length > 0){//existe
						$('#btnAddItem').hide();
					}
					if ($('#txtDocumentCode').length > 0){//existe
						$('#txtDocumentCode').attr('disabled','disabled');
					}
					if ($('#cbxMovementTypes').length > 0){//existe
						$('#cbxMovementTypes').attr('disabled','disabled');
					}
					changeLabelDocumentState(movementState); //#UNICORN
					showGrowlMessage('ok', 'Aprobado.');
				}
				//////////////////////////////////////////
				if(arrayCatch[0] === 'CANCELLED'){ 
					$('#btnCancellState').hide();
					changeLabelDocumentState(movementState); //#UNICORN
					showGrowlMessage('ok', 'Cancelado.');
				}
				//////////////////////////////////////////
				if(arrayCatch[0] === 'VALIDATION'){
					var arrayItemsStocks = arrayCatch[1].split(',');
					var validation = validateBeforeMoveOut(arrayItemsStocks, 'spaStock');
					$('#boxMessage').html('<div class="alert alert-error">\n\
					<button type="button" class="close" data-dismiss="alert">&times;</button><p>No se pudo realizar la acción debido a falta de STOCK:</p><ul>'+validation+'</ul><div>');
				}
				//////////////////////////////////////////
				if(arrayCatch[0] !== 'ERROR'){
					//update items stocks
					var arrayItemsStocks = arrayCatch[3].split(',');
					updateMultipleStocks(arrayItemsStocks, 'spaStock');
				}else{
					showGrowlMessage('error!', 'Vuelva a intentarlo.');
				}
				//////////////////////////////////////////
				
			},
			error:function(data){
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text('');
			}
        });
	}
	
	

	//Save movement Warehouse transfer
	function ajax_save_warehouses_transfer(arrayItemsDetails, movementState){
			$.ajax({
            type:"POST",
            url:moduleController + "ajax_save_warehouses_transfer",			
            data:{ arrayItemsDetails: arrayItemsDetails 
				  ,movementId:$('#txtMovementIdHidden').val()
				  ,date:$('#txtDate').val()
				  ,warehouseOut:$('#cbxWarehouses').val()
				  ,warehouseIn:$('#cbxWarehouses2').val()
				  ,description:$('#txtDescription').val()
				  ,documentCode:$('#txtDocumentCode').val()//transfer code for both parts
				  ,movementState:movementState
			  },
            beforeSend: showProcessing(),
            success: function(data){
				$('#boxMessage').html('');
				$('#processing').text('');
				//////////////////////////////
				var arrayCatch = data.split('|');
				if(arrayCatch[0] === 'PENDANT'){ 
					$('#txtDocumentCode').val(arrayCatch[2]);
					$('#btnApproveState, #btnLogicDelete, #btnPrint').show();
					$('#txtMovementIdHidden').val(arrayCatch[1]);
					changeLabelDocumentState(movementState);//#UNICORN
					showGrowlMessage('ok', 'Cambios guardados.');
				}
				//////////////////////////////////////////
				if(arrayCatch[0] === 'APPROVED'){ 
					$('#btnApproveState, #btnLogicDelete, #btnSaveAll, .columnItemsButtons').hide();
					$('#btnCancellState').show();
					if ($('#btnAddItem').length > 0){//existe
						$('#btnAddItem').hide();
					}
					$('#txtDocumentCode, #txtDate, #cbxWarehouses, #cbxWarehouses2, #txtDescription').attr('disabled','disabled');
					if ($('#cbxMovementTypes').length > 0){//existe
						$('#cbxMovementTypes').attr('disabled','disabled');
					}
					changeLabelDocumentState(movementState); //#UNICORN
					showGrowlMessage('ok', 'Aprobado.');
				}
				//////////////////////////////////////////
				if(arrayCatch[0] === 'CANCELLED'){ 
					$('#btnCancellState').hide();
					changeLabelDocumentState(movementState); //#UNICORN
					showGrowlMessage('ok', 'Cancelado.');
				}
				//////////////////////////////////////////
				if(arrayCatch[0] === 'VALIDATION'){
					var arrayItemsStocks = arrayCatch[1].split(',');
					var validation = validateBeforeMoveOut(arrayItemsStocks, 'spaStock');
					$('#boxMessage').html('<div class="alert alert-error">\n\
					<button type="button" class="close" data-dismiss="alert">&times;</button><p>No se pudo realizar la acción debido a falta de STOCK:</p><ul>'+validation+'</ul><div>');
				}
				if(arrayCatch[0] !== 'ERROR'){
					//Update Origin/StockOut
					arrayItemsStocks = arrayCatch[3].split(',');
					updateMultipleStocks(arrayItemsStocks, 'spaStock');

					//Update Destination/StockIn
					arrayItemsStocks = arrayCatch[4].split(',');
					updateMultipleStocks(arrayItemsStocks, 'spaStock2-');
				}else{
					showGrowlMessage('error!', 'Vuelva a intentarlo.');
				}
			},
			error:function(data){
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#processing').text('');
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
				$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrio un problema, vuelva a intentarlo<div>');
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
	
	function ajax_get_group_items_and_filters(){ //Report
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_get_group_items_and_filters",			
            data:{type: $('#cbxReportGroupTypes').val()},
			beforeSend: function(){
				$('#boxMessage').text('Procesando...');
			},
            success: function(data){
				$('#boxGroupItemsAndFilters').html(data);
				$('select').select2();
				startDataTable();
				$('#boxGroupItemsAndFilters #cbxReportGroupFilters').bind("change",function(){ 
					var selected = new Array();
					$("#boxGroupItemsAndFilters #cbxReportGroupFilters option:selected").each(function () {
						selected.push($(this).val());
					});
					//alert('hola mundo');
					ajax_get_group_items(selected);
				});
				$('#boxMessage').text('');
			},
			error:function(data){
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#boxMessage').text('');
			}
        });
	}
	
	function ajax_get_group_items(selected){ //Report
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_get_group_items",			
            data:{type: $('#cbxReportGroupTypes').val(), selected: selected},
			beforeSend: function(){
				$('#boxMessage').text('Procesando...');
			},
            success: function(data){
				$('#boxGroupItems').html(data);
				startDataTable();
				$('#boxMessage').text('');
			},
			error:function(data){
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#boxMessage').text('');
			}
        });
	}
	
	
/*
//A simple Test
$('form #cbxReportWarehouses').change(function(){
	var str = "YOU SELECTED :";
	$("form #cbxReportWarehouses option:selected").each(function () {
	//str += $(this).text() + " ";
	str += $(this).val() + " ";
	});
   alert(str);
});
*/


	//************************************************************************//
	//////////////////////////////////END-AJAX FUNCTIONS////////////////////////
	//************************************************************************//


//END SCRIPT	
});