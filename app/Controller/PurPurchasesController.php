<?php
App::uses('AppController', 'Controller');
/**
 * PurPurchases Controller
 *
 * @property PurPurchase $PurPurchase
 */
class PurPurchasesController extends AppController {

/**
 *  Layout
 *
 * @var string
 */
	public $layout = 'default';

/**
 * Helpers
 *
 * @var array
 */
	//public $helpers = array('TwitterBootstrap.BootstrapHtml', 'TwitterBootstrap.BootstrapForm', 'TwitterBootstrap.BootstrapPaginator');
/**
 * Components
 *
 * @var array
 */
	//public $components = array('Session');

	//*******************************************************************************************************//
	///////////////////////////////////////// START - FUNCTIONS ///////////////////////////////////////////////
	//*******************************************************************************************************//
	
	//////////////////////////////////////////// START - SAVE ///////////////////////////////////////////////
	
	public function save_order(){
		$id = '';
		if(isset($this->passedArgs['id'])){
			$id = $this->passedArgs['id'];
		}
		$this->loadModel('AdmParameter');
		$currency = $this->AdmParameter->AdmParameterDetail->find('first', array(
			//	'fields'=>array('AdmParameterDetail.id'),
				'conditions'=>array(
					'AdmParameter.name'=>'Moneda',
					'AdmParameterDetail.par_char1'=>'Dolares'
				)
			//	'recursive'=>-1
			)); 
//		debug($currency);
		$currencyId = $currency['AdmParameterDetail']['id'];
//		debug($currencyId);
		
		
		
//		$invSuppliers = $this->PurPurchase->InvSupplier->find('list');
		
		$this->PurPurchase->recursive = -1;
		$this->request->data = $this->PurPurchase->read(null, $id);
		$genericCode ='';
		$purDetails = array();
		$documentState = '';
		$date=date('d/m/Y');
		
		//////////////////////////////////////////////////////////
		$this->loadModel('AdmExchangeRate');
		$xxxRate = $this->AdmExchangeRate->find('first', array(
				'fields'=>array('AdmExchangeRate.value'),
				'conditions'=>array(
					'AdmExchangeRate.currency'=>$currencyId,
					'AdmExchangeRate.date'=>$date
				),
				'recursive'=>-1
			)); 		
//		debug($xxxRate);
		
		
		$exRate = $xxxRate['AdmExchangeRate']['value'];	//esto tiene q llamar al cambio del dia
		////////////////////////////////////////////////////////////
//		debug($exRate);
		if($id <> null){
			$date = date("d/m/Y", strtotime($this->request->data['PurPurchase']['date']));//$this->request->data['InvMovement']['date'];
			$purDetails = $this->_get_movements_details($id);
			$documentState =$this->request->data['PurPurchase']['lc_state'];
			$genericCode = $this->request->data['PurPurchase']['code'];
			//////////////////////////////////////////////////////////
//			$this->loadModel('AdmExchangeRate');
//			$xxxRate = $this->AdmExchangeRate->find('first', array(
//					'fields'=>array('AdmExchangeRate.value'),
//					'conditions'=>array(
//						'AdmExchangeRate.currency'=>$currencyId,
//						'AdmExchangeRate.date'=>$date
//					),
//					'recursive'=>-1
//				)); 		
//	//		debug($xxxRate);


//			$exRate = $xxxRate['AdmExchangeRate']['value'];
			
			$exRate = $this->request->data['PurPurchase']['ex_rate'];
			////////////////////////////////////////////////////////// arriba jala de la fecha guardada, abajo jala de el exRate guardado,
			////////////////////////////////////////////que si el usuario cambia en adm_exchanges_rates puede ser diferente a lo guardado
			
			
		}
		$this->set(compact(/*'invSuppliers', */'id', 'date', 'purDetails', 'documentState', 'genericCode', 'exRate'));
	}
	
	public function save_invoice(){
		$id = '';
		if(isset($this->passedArgs['id'])){
			$id = $this->passedArgs['id'];
		}
		$this->loadModel('AdmParameter');
		$currency = $this->AdmParameter->AdmParameterDetail->find('first', array(
			//	'fields'=>array('AdmParameterDetail.id'),
				'conditions'=>array(
					'AdmParameter.name'=>'Moneda',
					'AdmParameterDetail.par_char1'=>'Dolares'
				)
			//	'recursive'=>-1
			)); 
//		debug($currency);
		$currencyId = $currency['AdmParameterDetail']['id'];
//		debug($currencyId);
		
		
//		$invSuppliers = $this->PurPurchase->InvSupplier->find('list');
				
		$this->PurPurchase->recursive = -1;
		$this->request->data = $this->PurPurchase->read(null, $id);
		$date='';
		$genericCode ='';
		$originCode = '';
		$purDetails = array();
		$purPrices = array();
		$purPayments = array();
		$documentState = '';
	//	$exRate = '8.00';	//esto tiene q llamar al cambio del dia
		if($id <> null){
			$date = date("d/m/Y", strtotime($this->request->data['PurPurchase']['date']));//$this->request->data['InvMovement']['date'];
			$purDetails = $this->_get_movements_details($id);
			$purPrices = $this->_get_costs_details($id);
			$purPayments = $this->_get_pays_details($id);
			$documentState =$this->request->data['PurPurchase']['lc_state'];
			$genericCode = $this->request->data['PurPurchase']['code'];
			//buscar el codigo del documento origen
			$originDocCode = $this->PurPurchase->find('first', array(
				'fields'=>array('PurPurchase.doc_code'),
				'conditions'=>array(
					'PurPurchase.code'=>$genericCode,
					'PurPurchase.lc_state LIKE'=> '%ORDER%'
					)
			));
			$originCode = $originDocCode['PurPurchase']['doc_code'];
			
			
			
			$exRate = $this->request->data['PurPurchase']['ex_rate'];
		}
		
			
		$this->set(compact(/*'invSuppliers', */'id', 'date', 'purDetails', 'purPrices', 'purPayments', 'documentState', 'genericCode', 'originCode', 'exRate'));
//debug($this->request->data);
	}
	//START - AJAX START - AJAX START - AJAX START - AJAX START - AJAX START - AJAX START - AJAX
	
	public function ajax_initiate_modal_add_item_in(){
		if($this->RequestHandler->isAjax()){
						
			$itemsAlreadySaved = $this->request->data['itemsAlreadySaved'];
			$supplierItemsAlreadySaved = $this->request->data['supplierItemsAlreadySaved'];
			
			//$supplier = $this->request->data['supplier'];
			$invSuppliers = $this->PurPurchase->PurDetail->InvItem->InvItemsSupplier->InvSupplier->find('list');
//			debug($invSuppliers);
			$supplier = key($invSuppliers);
			$itemsBySupplier = $this->PurPurchase->PurDetail->InvItem->InvItemsSupplier->find('list', array(
				'fields'=>array('InvItemsSupplier.inv_item_id'),
				'conditions'=>array(
					'InvItemsSupplier.inv_supplier_id'=>$supplier
				),
				'recursive'=>-1
			)); 	
			
			$itemsAlreadyTakenFromSupplier = [];
			for($i=0; $i<count($itemsAlreadySaved); $i++){
				if($supplierItemsAlreadySaved[$i] == $supplier){
					$itemsAlreadyTakenFromSupplier[] = $itemsAlreadySaved[$i];
				}	
			}
//			debug($itemsAlreadyTakenFromSupplier);			
			$items = $this->PurPurchase->PurDetail->InvItem->find('list', array(
				'conditions'=>array(
					'NOT'=>array('InvItem.id'=>$itemsAlreadyTakenFromSupplier)
					,'InvItem.id'=>$itemsBySupplier
				),
				'recursive'=>-1,
				'order'=>array('InvItem.code')
			));
//debug($supplier);			
//debug($items);
//debug($this->request->data);
		// gets the first price in the list of the item prices
		$firstItemListed = key($items);
		$priceDirty = $this->PurPurchase->PurDetail->InvItem->InvPrice->find('first', array(
			'fields'=>array('InvPrice.price'),
			'order' => array('InvPrice.date_created' => 'desc'),
			'conditions'=>array(
				'InvPrice.inv_item_id'=>$firstItemListed
				)
		));
//debug($priceDirty);
		if($priceDirty==array()){
			$price = 0;
		}  else {
			
			$price = $priceDirty['InvPrice']['price'];
		}
				
			$this->set(compact('items', 'price', 'invSuppliers', 'supplier'));
		}
	}
	
	public function ajax_update_items_modal(){
		if($this->RequestHandler->isAjax()){
			$itemsAlreadySaved = $this->request->data['itemsAlreadySaved'];
			$supplierItemsAlreadySaved = $this->request->data['supplierItemsAlreadySaved'];
			$supplier = $this->request->data['supplier'];
			
			$itemsBySupplier = $this->PurPurchase->PurDetail->InvItem->InvItemsSupplier->find('list', array(
				'fields'=>array('InvItemsSupplier.inv_item_id'),
				'conditions'=>array(
					'InvItemsSupplier.inv_supplier_id'=>$supplier
				),
				'recursive'=>-1
			)); 	
			
			$itemsAlreadyTakenFromSupplier = [];
			for($i=0; $i<count($itemsAlreadySaved); $i++){
				if($supplierItemsAlreadySaved[$i] == $supplier){
					$itemsAlreadyTakenFromSupplier[] = $itemsAlreadySaved[$i];
				}	
			}
			
			$items = $this->PurPurchase->PurDetail->InvItem->find('list', array(
				'conditions'=>array(
					'NOT'=>array('InvItem.id'=>$itemsAlreadyTakenFromSupplier)
					,'InvItem.id'=>$itemsBySupplier
				),
				'recursive'=>-1,
				'order'=>array('InvItem.code')
			));
//			debug($items);
			$item = key($items);
			//////////////////////CAMBIAR POR EL ALGORITMO QUE SACA EL PRECIO PRORRATEADO////////////////
			$priceDirty = $this->PurPurchase->PurDetail->InvItem->InvPrice->find('first', array(
			'fields'=>array('InvPrice.price'),
			'order' => array('InvPrice.date_created' => 'desc'),
			'conditions'=>array(
				'InvPrice.inv_item_id'=>$item
				)
			));
			if($priceDirty==array()){
			$price = 0;
			}  else {
			
			$price = $priceDirty['InvPrice']['price'];
			}
			//////////////////////CAMBIAR POR EL ALGORITMO QUE SACA EL PRECIO PRORRATEADO////////////////
			//$stock = $this->_find_stock($item, $warehouse);
//			$stocks = $this->_get_stocks($item, $warehouse);
//			$stock = $this->_find_item_stock($stocks, $item);
			$this->set(compact('items', 'price'/*, 'stock'*/));
		}
	}
	
	public function ajax_initiate_modal_add_cost(){
		if($this->RequestHandler->isAjax()){
		//				$cost = $this->request->data['cost'];
			$costsAlreadySaved = $this->request->data['costsAlreadySaved'];
//			$warehouse = $this->request->data['warehouse'];
		//	$supplier = $this->request->data['supplier'];
//	//		$itemsBySupplier = $this->PurPurchase->InvSupplier->InvItemsSupplier->find('list', array(
//				'fields'=>array('InvItemsSupplier.inv_item_id'),
//				'conditions'=>array(
//					'InvItemsSupplier.inv_supplier_id'=>$supplier
//				),
//				'recursive'=>-1
//			)); 
//debug($itemsBySupplier);			
			$costs = $this->PurPurchase->PurPrice->InvPriceType->find('list', array(
					'fields'=>array('InvPriceType.name'),
					'conditions'=>array(
						'NOT'=>array('InvPriceType.id'=>$costsAlreadySaved,'InvPriceType.name'=>array('VENTA','FOB','CIF'))
				),
				
				'recursive'=>-1
				//'fields'=>array('InvItem.id', 'CONCAT(InvItem.code, '-', InvItem.name)')
			));
//debug($costs);			
//debug($items);
//debug($this->request->data);
		// gets the first price in the list of the item prices
//		$firstItemListed = key($items);
//		$priceDirty = $this->PurPurchase->PurDetail->InvItem->InvPrice->find('first', array(
//			'fields'=>array('InvPrice.price'),
//			'order' => array('InvPrice.date_created' => 'desc'),
//			'conditions'=>array(
//				'InvPrice.inv_item_id'=>$firstItemListed
//				)
//		));
////debug($priceDirty);
//		if($priceDirty==array()){
//			$price = 0;
//		}  else {
//			
//			$price = $priceDirty['InvPrice']['price'];
//		}
//			$amountDirty = $this->PurPurchase->PurPrice->find('first', array(
//			'fields'=>array('PurPrice.amount'),
//	//		'order' => array('rice.date_created' => 'desc'),
//			'conditions'=>array(
//				'PurPrice.inv_price_type_id'=>$costsAlreadySaved
//				)
//			));
//			if($amountDirty==array()){
//			$amount = 0;
//		}  else {
//			
//			$amount = $amountDirty['PurPrice']['amount'];
//		}
				
			$this->set(compact('costs'/*, 'amount'*/));
		}
	}
	
	//Hace un find en la BD de los elementos que se mostraran en el combobox 
	public function ajax_initiate_modal_add_pay(){
		if($this->RequestHandler->isAjax()){
			$paysAlreadySaved = $this->request->data['paysAlreadySaved'];
			$payDebt = $this->request->data['payDebt'];
//			debug($payDebt);
			$datePay=date('d/m/Y');
//			$debt = $this->SalSale->SalPayment->find('list', array(
//					'fields'=>array('SalPayment.amount'),
//					'conditions'=>array(
//						'SalPayment.date'=>$paysAlreadySaved
//				),
//				'recursive'=>-1
//			));
//	debug($debt);
//			$warehouse = $this->request->data['warehouse'];
		//	$supplier = $this->request->data['supplier'];
//	//		$itemsBySupplier = $this->PurPurchase->InvSupplier->InvItemsSupplier->find('list', array(
//				'fields'=>array('InvItemsSupplier.inv_item_id'),
//				'conditions'=>array(
//					'InvItemsSupplier.inv_supplier_id'=>$supplier
//				),
//				'recursive'=>-1
//			)); 
//debug($itemsBySupplier);			
			$pays = $this->PurPurchase->PurPayment->PurPaymentType->find('list', array(
					'fields'=>array('PurPaymentType.name'),
					'conditions'=>array(
//						'NOT'=>array('InvPriceType.id'=>$paysAlreadySaved) /*aca se hace la discriminacion de items seleccionados*/
				),
				
				'recursive'=>-1
				//'fields'=>array('InvItem.id', 'CONCAT(InvItem.code, '-', InvItem.name)')
			));
//debug($supplier);			
//debug($items);
//debug($this->request->data);
		// gets the first price in the list of the item prices
//		$firstItemListed = key($items);
//		$priceDirty = $this->PurPurchase->PurDetail->InvItem->InvPrice->find('first', array(
//			'fields'=>array('InvPrice.price'),
//			'order' => array('InvPrice.date_created' => 'desc'),
//			'conditions'=>array(
//				'InvPrice.inv_item_id'=>$firstItemListed
//				)
//		));
////debug($priceDirty);
//		if($priceDirty==array()){
//			$price = 0;
//		}  else {
//			
//			$price = $priceDirty['InvPrice']['price'];
//		}
//			$amountDirty = $this->PurPurchase->PurPrice->find('first', array(
//			'fields'=>array('PurPrice.amount'),
//	//		'order' => array('rice.date_created' => 'desc'),
//			'conditions'=>array(
//				'PurPrice.inv_price_type_id'=>$costsAlreadySaved
//				)
//			));
//			if($amountDirty==array()){
//			$amount = 0;
//		}  else {
//			
//			$amount = $amountDirty['PurPrice']['amount'];
//		}
				
			$this->set(compact('pays', 'datePay', 'payDebt'/*, 'amount'*/));
		}
	}
	
	public function ajax_update_stock_modal(){
		if($this->RequestHandler->isAjax()){
			$item = $this->request->data['item'];
			//////////////////////CAMBIAR POR EL ALGORITMO QUE SACA EL PRECIO PRORRATEADO////////////////
			$priceDirty = $this->PurPurchase->PurDetail->InvItem->InvPrice->find('first', array(
			'fields'=>array('InvPrice.price'),
			'order' => array('InvPrice.date_created' => 'desc'),
			'conditions'=>array(
				'InvPrice.inv_item_id'=>$item
				)
			));
			if($priceDirty==array()){
			$price = 0;
			}else{
			
			$price = $priceDirty['InvPrice']['price'];
			}
			//////////////////////CAMBIAR POR EL ALGORITMO QUE SACA EL PRECIO PRORRATEADO////////////////
			$this->set(compact('price'));
		}
	}
	
	//no se utiliza pq no tiene que mostrar ningun valos en otro campo a partir del elemento elegido en el combobox
	public function ajax_update_amount(){
		if($this->RequestHandler->isAjax()){
			$cost = $this->request->data['cost'];
//			$warehouse = $this->request->data['warehouse']; //if it's warehouse_transfer is OUT
//			$warehouse2 = $this->request->data['warehouse2'];//if it's warehouse_transfer is IN
//			$transfer = $this->request->data['transfer'];
			
//			$stock = $this->_find_stock($item, $warehouse);//if it's warehouse_transfer is OUT
//			$stock2 ='';
//			if($transfer == 'warehouses_transfer'){
//				$stock2 = $this->_find_stock($item, $warehouse2);//if it's warehouse_transfer is IN	
//			}
			$amountDirty = $this->PurPurchase->PurPrice->find('first', array(
			'fields'=>array('PurPrice.amount'),
	//		'order' => array('rice.date_created' => 'desc'),
			'conditions'=>array(
				'PurPrice.inv_price_type_id'=>$cost
				)
			));
			if($amountDirty==array()){
			$amount = 0;
		}  else {
			
			$amount = $amountDirty['PurPrice']['amount'];
		}
			$this->set(compact('amount'));
		}
	}
	
	public function ajax_update_ex_rate(){
		if($this->RequestHandler->isAjax()){
			$date = $this->request->data['date']; 
			
			$this->loadModel('AdmParameter');
			$currency = $this->AdmParameter->AdmParameterDetail->find('first', array(
				//	'fields'=>array('AdmParameterDetail.id'),
					'conditions'=>array(
						'AdmParameter.name'=>'Moneda',
						'AdmParameterDetail.par_char1'=>'Dolares'
					)
				//	'recursive'=>-1
				)); 
	//		debug($currency);
			$currencyId = $currency['AdmParameterDetail']['id'];
	//		debug($currencyId);
			$this->loadModel('AdmExchangeRate');
			$xxxRate = $this->AdmExchangeRate->find('first', array(
					'fields'=>array('AdmExchangeRate.value'),
					'conditions'=>array(
						'AdmExchangeRate.currency'=>$currencyId,
						'AdmExchangeRate.date'=>$date
					),
					'recursive'=>-1
				)); 		
	//		debug($xxxRate);
			$exRate = $xxxRate['AdmExchangeRate']['value'];
		
			$this->set(compact('exRate'));			
		}else{
			$this->redirect($this->Auth->logout());
		}
	}
	
	
	//aca tendria que poder calcular el pago adeudado en base a los pagos guardados
//	public function ajax_update_pay(){
//		if($this->RequestHandler->isAjax()){
//			$pay = $this->request->data['pay'];
//			$amountDirty = $this->PurPurchase->PurPrice->find('first', array(
//			'fields'=>array('PurPrice.amount'),
//			'conditions'=>array(
//				'PurPrice.inv_price_type_id'=>$cost
//				)
//			));
//			if($amountDirty==array()){
//			$amount = 0;
//		}  else {
//			$amount = $amountDirty['PurPrice']['amount'];
//		}
//			$this->set(compact('amount'));
//		}
//	}
	
	public function ajax_save_movement(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////START - RECIEVE AJAX////////////////////////////////////////////////////////
			//For making algorithm
			$ACTION = $this->request->data['ACTION'];
			$OPERATION= $this->request->data['OPERATION'];
			$STATE = $this->request->data['STATE'];//also for Movement
			$OPERATION3 = $OPERATION;
//			$OPERATION4 = $OPERATION;
			//Sale
			$purchaseId = $this->request->data['purchaseId'];
			$movementDocCode = $this->request->data['movementDocCode'];
			$movementCode = $this->request->data['movementCode'];
			$noteCode = $this->request->data['noteCode'];
			$date = $this->request->data['date'];
			$supplierId = $this->request->data['supplierId'];
//			debug($supplier);
//			$employee = $this->request->data['employee'];
//			$taxNumber = $this->request->data['taxNumber'];
//			$admProfileId = $this->request->data['salesman'];
			///////////////////////////////////////////////////////
//			$this->loadModel('AdmUser');
//			$admUserId = $this->AdmUser->AdmProfile->find('list', array(
//			'fields'=>array('AdmProfile.adm_user_id'),
//			'conditions'=>array('AdmProfile.id'=>$admProfileId)
//			));
//			
//			$salesman = key($this->AdmUser->find('list', array(
//			'conditions'=>array('AdmUser.id'=>$admUserId)
//			)));
			///////////////////////////////////////////////////////
			$description = $this->request->data['description'];
			$exRate = $this->request->data['exRate'];
			//Sale Details
//			$warehouseId = $this->request->data['warehouseId'];
			$itemId = $this->request->data['itemId'];
			$exFobPrice = $this->request->data['exFobPrice'];
			$quantity = $this->request->data['quantity'];
//			$cifPrice =  $this->_get_price($itemId, $date, 'CIF', 'bs');//$this->request->data['cifPrice'];
//			$exCifPrice = $this->_get_price($itemId, $date, 'CIF', 'dolar');//$this->request->data['exCifPrice'];
			//For prices IF DETAILS ARE PASSED / IF ACTION ADD OR EDIT
//			$exFobPrice =  $this->_get_price($itemId, $date, 'FOB', 'dolar');
//			$fobPrice =  $exFobPrice * $exRate;//$this->_get_price($itemId, $date, 'FOB', 'bs');
			$fobPrice = $exFobPrice * $exRate;
			$total = $this->request->data['total'];
			$totalCost = $this->request->data['totalCost'];
			if (($ACTION == 'save_invoice' && $OPERATION == 'ADD_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_PAY')) {
//				$dateId = $this->request->data['dateId'];
				$payDate = $this->request->data['payDate'];
				$payAmount = $this->request->data['payAmount'];
				$payDescription = $this->request->data['payDescription'];
			}
			if (($ACTION == 'save_invoice' && $OPERATION == 'ADD_COST') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_COST') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_COST')) {
//				$dateId = $this->request->data['dateId'];
				$costId = $this->request->data['costId'];
				$costExAmount = $this->request->data['costExAmount'];
			}
			//For validate before approve OUT or cancelled IN
			$arrayForValidate = array();
			if(isset($this->request->data['arrayForValidate'])){$arrayForValidate = $this->request->data['arrayForValidate'];}
			//Internal variables
			$error=0;
			$movementDocCode3 = '';
//			$movementDocCode4 = '';
			////////////////////////////////////////////END - RECIEVE AJAX////////////////////////////////////////////////////////
			
			////////////////////////////////////////////////START - SET DATA/////////////////////////////////////////////////////
			$arrayMovement['note_code']=$noteCode;
			$arrayMovement['date']=$date;
//			$arrayMovement['sal_employee_id']=$employee;
//			$arrayMovement['sal_tax_number_id']=$taxNumber;
//			$arrayMovement['salesman_id']=$salesman;
			$arrayMovement['inv_supplier_id']=$supplierId;
			$arrayMovement['description']=$description;
			$arrayMovement['ex_rate']=$exRate;
			$arrayMovement['lc_state']=$STATE;
			if ($ACTION == 'save_order'){
				//header for invoice
				$arrayMovement2['note_code']=$noteCode;
				$arrayMovement2['date']=$date;
//				$arrayMovement2['sal_employee_id']=$employee;
//				$arrayMovement2['sal_tax_number_id']=$taxNumber;
//				$arrayMovement2['salesman_id']=$salesman;
				$arrayMovement2['inv_supplier_id']=$supplierId;
				$arrayMovement2['description']=$description;
				$arrayMovement2['ex_rate']=$exRate;
				//header for movement
				$arrayMovement3['date']=$date;
				$arrayMovement3['inv_warehouse_id']=2;//EL ALTO 2 MANUALMENTE VER COMO ELEGIR ESTO
				$arrayMovement3['inv_movement_type_id']=1; //Reynaldo Rojas Compra = 1
				$arrayMovement3['description']=$description;
				
				if ($STATE == 'ORDER_APPROVED') {
					$arrayMovement2['lc_state']='PINVOICE_PENDANT';
				}elseif ($STATE == 'ORDER_PENDANT') {
					$arrayMovement2['lc_state']='DRAFT';
					$arrayMovement3['lc_state']='DRAFT';
//					$arrayMovement4['lc_state']='DRAFT';
//					debug($arrayMovement3['lc_state']);
				}
			}elseif(($ACTION == 'save_invoice' && $OPERATION == 'ADD_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_PAY')){
				$arrayPayDetails = array('pur_payment_type_id'=>1, 
										'date'=>$payDate,
										//'description'=>"'$payDescription'",
										'description'=>$payDescription,
										'amount'=>$payAmount, 'ex_amount'=>($payAmount / $exRate)
										);
			}elseif(($ACTION == 'save_invoice' && $OPERATION == 'ADD_COST') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_COST') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_COST')){
				$arrayCostDetails = array('inv_price_type_id'=>$costId,
										'ex_amount'=>$costExAmount, 'amount'=>($costExAmount * $exRate)
										);
			}elseif($ACTION == 'save_invoice') {
				//header for movement
				$arrayMovement3['date']=$date;
				$arrayMovement3['inv_warehouse_id']=2;//EL ALTO 2 MANUALMENTE VER COMO ELEGIR ESTO
				$arrayMovement3['inv_movement_type_id']=1; //Reynaldo Rojas Compra = 1
				$arrayMovement3['description']=$description;
				if ($STATE == 'PINVOICE_PENDANT') {
					$arrayMovement3['lc_state']='PENDANT';//ESTO ESTA SOBREESCRITO POR LO Q DIGA $arrayMovement5
//					$arrayMovement4['lc_state']='PENDANT';//ESTO ESTA SOBREESCRITO POR LO Q DIGA $arrayMovement5
				}
			}			
			$arrayMovementDetails = array('inv_supplier_id'=>$supplierId,  
										'inv_item_id'=>$itemId,
										'ex_fob_price'=>$exFobPrice, 'fob_price'=>$fobPrice,
										'quantity'=>$quantity, 
										/*'cif_price'=>$cifPrice, 'ex_cif_price'=>$exCifPrice, 
										'fob_price'=>$fobPrice, 'ex_fob_price'=>$exFobPrice*/);
			if ($ACTION == 'save_order'){
//				$stocks = $this->_get_stocks($itemId, 1);//$warehouseId);
//				$stock = $this->_find_item_stock($stocks, $itemId);
				$arrayMovement3['type']=1;

//				$arrayMovement4['date']=$date;
//				$arrayMovement4['inv_warehouse_id']=2;//EL ALTO 2 MANUALMENTE VER COMO ELEGIR ESTO
//				$arrayMovement4['inv_movement_type_id']=1; //Reynaldo Rojas Compra = 1
//				$arrayMovement4['description']=$description;
//				$arrayMovement4['type']=2;
//				$surplus = $quantity - $stock;
//				if($quantity > $stock){
//					$arrayMovementDetails3 = array('inv_item_id'=>$itemId, 'quantity'=>$stock);
//					$OPERATION4 = 'ADD';
//				}else{
					$arrayMovementDetails3 = array('inv_item_id'=>$itemId, 'quantity'=>$quantity);
//				}	
//				$arrayMovementDetails4 = array('inv_item_id'=>$itemId, 'quantity'=>$surplus);
			}elseif ($ACTION == 'save_invoice' && $OPERATION != 'ADD_PAY' && $OPERATION != 'EDIT_PAY' && $OPERATION != 'DELETE_PAY') {
//				$stocks = $this->_get_stocks($itemId, $warehouseId);
//				$stock = $this->_find_item_stock($stocks, $itemId);
				$arrayMovement3['type']=1;
//				$arrayMovement4['date']=$date;
//				$arrayMovement4['inv_warehouse_id']=$warehouseId;
//				$arrayMovement4['inv_movement_type_id']=2;
//				$arrayMovement4['description']=$description;
//				$arrayMovement4['type']=2;
//				$surplus = $quantity - $stock;
//				if($quantity > $stock){
//					$arrayMovementDetails3 = array('inv_item_id'=>$itemId, 'quantity'=>$stock);
//					$OPERATION4 = 'ADD';
//				}else{
					$arrayMovementDetails3 = array('inv_item_id'=>$itemId, 'quantity'=>$quantity);
//				}	
//				$arrayMovementDetails4 = array('inv_item_id'=>$itemId, 'quantity'=>$surplus);
			}
			//INSERT OR UPDATE
			if($purchaseId == ''){//INSERT
				switch ($ACTION) {
					case 'save_order':
						//SALES NOTE
						$movementCode = $this->_generate_code('COM');
						$movementDocCode = $this->_generate_doc_code('ORD');
						$arrayMovement['code'] = $movementCode;
						$arrayMovement['doc_code'] = $movementDocCode;
						//SALES INVOICE
						$movementDocCode2 = 'NO';
						$arrayMovement2['code'] = $movementCode;
						$arrayMovement2['doc_code'] = $movementDocCode2;
						//MOVEMENT type 1(hay stock)
						$arrayMovement3['document_code'] = $movementCode;
						$arrayMovement3['code'] = $movementDocCode2;
						//MOVEMENT type 2(NO hay stock)
//						$arrayMovement4['document_code'] = $movementCode;
//						$arrayMovement4['code'] = $movementDocCode2;
						break;
				}
				if($movementCode == 'error'){$error++;}
				if($movementDocCode == 'error'){$error++;}
				if($movementDocCode2 == 'error'){$error++;}
			}else{//UPDATE
				//sale note id
				$arrayMovement['id'] = $purchaseId;
				if ($ACTION == 'save_order'){
					//sale invoice id
					$arrayMovement2['id'] = $this->_get_doc_id($purchaseId, $movementCode, null, null);
					//movement id type 1(hay stock)
					$arrayMovement3['id'] = $this->_get_doc_id(null, $movementCode, 1, 2);//$warehouseId);
					if($arrayMovement3['id'] === null){
						$arrayMovement3['document_code'] = $movementCode;
						$arrayMovement3['code'] = 'NO';
					}
					//movement id type 2(NO hay stock)
//					$arrayMovement4['id'] = $this->_get_doc_id(null, $movementCode, 2, 2);//$warehouseId);
//					if(($arrayMovement4['id'] === null) && ($quantity > $stock)){
//						$arrayMovement4['document_code'] = $movementCode;
//						$arrayMovement4['code'] = 'NO';
//					}
//					if($quantity > $stock){//CHEKAR BIEN ESTO, CREO Q YA NO VA!!!
//						$arrayMovement4['document_code'] = $movementCode;
//						$arrayMovement4['code'] = 'NO';
//					}
//					REVISAR SI ESTO SERVIA PARA ALGO |||||| REVISAR SI ESTO SERVIA PARA ALGO ||||| REVISAR SI ESTO SERVIA PARA ALGO  					
//					if(($arrayMovement4['id'] <> null) && ($quantity <= $stock)){
//						$OPERATION4 = 'DELETE';
//					}
					if ($STATE == 'ORDER_APPROVED') {
						//FOR INVOICE
						$movementDocCode2 = $this->_generate_doc_code('CFA');
						$arrayMovement2['doc_code'] = $movementDocCode2;
					}
				}elseif ($ACTION == 'save_invoice' && $OPERATION != 'ADD_PAY' && $OPERATION != 'EDIT_PAY' && $OPERATION != 'DELETE_PAY' && $OPERATION != 'ADD_COST' && $OPERATION != 'EDIT_COST' && $OPERATION != 'DELETE_COST') {
					//movement id type 1(hay stock)
					$arrayMovement3['id'] = $this->_get_doc_id(null, $movementCode, 1, 2);//$warehouseId);
					if($arrayMovement3['id'] === null){//SI NO HAY EL DOCUMENTO (CON STOCK) SE CREA
						$arrayMovement3['document_code'] = $movementCode;
						$movementDocCode3 = $this->_generate_movement_code('SAL',null);
						$arrayMovement3['code'] = $movementDocCode3;//'NO';
					}
					//movement id type 2(NO hay stock)
//					$arrayMovement4['id'] = $this->_get_doc_id(null, $movementCode, 2, $warehouseId);
//					if(($arrayMovement4['id'] === null) && ($quantity > $stock)){//SI NO HAY EL DOCUMENTO (SIN STOCK), Y LA CANTIDAD SOBREPASA EL STOCK SE CREA
//						$arrayMovement4['document_code'] = $movementCode;
//						$movementDocCode4 = $this->_generate_movement_code('SAL',null);
//						$arrayMovement4['code'] = $movementDocCode4;//'NO';
//					}
//					if($quantity > $stock){
//						$arrayMovement4['document_code'] = $movementCode;
//						$movementDocCode4 = $this->_generate_movement_code('SAL',null);
//						$arrayMovement4['code'] = $movementDocCode4;//'NO';
//					}
//					REVISAR SI ESTO SERVIA PARA ALGO |||||| REVISAR SI ESTO SERVIA PARA ALGO ||||| REVISAR SI ESTO SERVIA PARA ALGO  
//					if(($arrayMovement4['id'] <> null) && ($quantity <= $stock)){
//						$OPERATION4 = 'DELETE';
//					}
				}
				if($movementDocCode3 == 'error'){$error++;}
//				if($movementDocCode4 == 'error'){$error++;}
			}
			//-------------------------FOR DELETING HEAD ON MOVEMENTS RELATED ON save_order--------------------------------
//			if(($ACTION == 'save_order' && $OPERATION3 == 'DELETE') || ($ACTION == 'save_order' && $OPERATION4 == 'DELETE')){	
			$arrayMovement6 = null;
			if(($ACTION == 'save_order' && $OPERATION3 == 'DELETE'/* && $OPERATION4 == 'DELETE'*/)){//TOMANDO EN CUENTA QUE SIEMPRE QUE $OPERATION3 == 'DELETE' TAMBIEN $OPERATION4 == 'DELETE' Y VICEVERSA
				if (($arrayMovement3['id'] != null)||($arrayMovementDetails3['inv_item_id'] != null)){
					$rest3 = $this->InvMovement->InvMovementDetail->find('count', array(
						'conditions'=>array(
							'NOT'=>array(
								'AND'=>array(
									'InvMovementDetail.inv_movement_id'=>$arrayMovement3['id']
									,'InvMovementDetail.inv_item_id'=>$arrayMovementDetails3['inv_item_id']
									)
								)
							,'InvMovementDetail.inv_movement_id'=>$arrayMovement3['id']
							),
						'recursive'=>0
					));
				}
//				if (($arrayMovement4['id'] != null)||($arrayMovementDetails4['inv_item_id'] != null)){
//					$rest4 = $this->InvMovement->InvMovementDetail->find('count', array(
//						'conditions'=>array(
//							'NOT'=>array(
//								'AND'=>array(
//									'InvMovementDetail.inv_movement_id'=>$arrayMovement4['id']
//									,'InvMovementDetail.inv_item_id'=>$arrayMovementDetails4['inv_item_id']
//									)
//								)
//							,'InvMovementDetail.inv_movement_id'=>$arrayMovement4['id']
//							),
//						'recursive'=>0
//					));
//				}	
//				if(($rest3 == 0) && ($rest4 == 0) && ($arrayMovement3['id'] != null) && ($arrayMovement4['id'] != null)){
//					$arrayMovement6 = array(
//						array('InvMovement.id' => array($arrayMovement3['id'],$arrayMovement4['id']))
//					);
//				}else
					if(($rest3 == 0) && ($arrayMovement3['id'] != null)){
					$arrayMovement6 = array(
						array('InvMovement.id' => $arrayMovement3['id'])
					);
				}
//				elseif(($rest4 == 0) && ($arrayMovement4['id'] != null)){
//					$arrayMovement6 = array(
//						 array('InvMovement.id' => $arrayMovement4['id'])
//					);
//				}
//				else{
//					$arrayMovement6 = null;
//				}
			}
			//---------------------------FOR DELETING HEAD ON MOVEMENTS RELATED ON save_order------------------------------
//			-------------------------FOR UPDATING HEAD ON DELETED MOVEMENTS ON save_invoice--------------------------------
//			if(($ACTION == 'save_invoice' && $OPERATION3 == 'DELETE') || ($ACTION == 'save_invoice' && $OPERATION4 == 'DELETE')){	
			$draftId3 = null;
//			$draftId4 = null;
			if(($ACTION == 'save_invoice' && $OPERATION3 == 'DELETE' /*&& $OPERATION4 == 'DELETE'*/)){//TOMANDO EN CUENTA QUE SIEMPRE QUE $OPERATION3 == 'DELETE' TAMBIEN $OPERATION4 == 'DELETE' Y VICEVERSA
				if (($arrayMovement3['id'] != null)||($arrayMovementDetails3['inv_item_id'] != null)){
					$rest3 = $this->InvMovement->InvMovementDetail->find('count', array(
						'conditions'=>array(
							'NOT'=>array(
								'AND'=>array(
									'InvMovementDetail.inv_movement_id'=>$arrayMovement3['id']
									,'InvMovementDetail.inv_item_id'=>$arrayMovementDetails3['inv_item_id']
									)
								)
							,'InvMovementDetail.inv_movement_id'=>$arrayMovement3['id']
							),
						'recursive'=>0
					));
				}
//				if (($arrayMovement4['id'] != null)||($arrayMovementDetails4['inv_item_id'] != null)){
//					$rest4 = $this->InvMovement->InvMovementDetail->find('count', array(
//						'conditions'=>array(
//							'NOT'=>array(
//								'AND'=>array(
//									'InvMovementDetail.inv_movement_id'=>$arrayMovement4['id']
//									,'InvMovementDetail.inv_item_id'=>$arrayMovementDetails4['inv_item_id']
//									)
//								)
//							,'InvMovementDetail.inv_movement_id'=>$arrayMovement4['id']
//							),
//						'recursive'=>0
//					));
//				}	
//				if(($rest3 == 0) && ($rest4 == 0) && ($arrayMovement3['id'] != null) && ($arrayMovement4['id'] != null)){
//					$draftId3 = $arrayMovement3['id'];
//					$draftId4 = $arrayMovement4['id'];
//					echo "<br>1<br>";
//					debug($draftId3);
//					debug($draftId4);
//				}else
					if(($rest3 == 0) && ($arrayMovement3['id'] != null)){
					$draftId3 = $arrayMovement3['id'];
//					$draftId4 = null;
//					echo "<br>2<br>";
//					debug($draftId3);
				}
//				elseif(($rest4 == 0) && ($arrayMovement4['id'] != null)){
//					$draftId4 = $arrayMovement4['id'];
//					$draftId3 = null;
//					echo "<br>3<br>";
//					debug($draftId4);
//				}
//				else{
//					$draftId3 = null;
//					$draftId4 = null;
//				}
			}
//			---------------------------FOR UPDATING HEAD ON DELETED MOVEMENTS ON save_invoice------------------------------
			//*********************************************************MAKE AN IF WHEN $STATE == DEFAULT
			$this->loadModel('InvMovement');
			$arrayMovement5 = $this->InvMovement->find('all', array(
				'fields'=>array(
					'InvMovement.id'
//					,'InvMovement.date'
//					,'InvMovement.description'
//					,'InvMovement.lc_state'
//					,'InvMovement.inv_warehouse_id'
					),
				'conditions'=>array(
						'InvMovement.document_code'=>$movementCode
					)
				,'order' => array('InvMovement.id' => 'ASC')
				,'recursive'=>0
			));
			if(($arrayMovement5 <> null)&&($STATE == 'ORDER_CANCELLED')){
				for($i=0;$i<count($arrayMovement5);$i++){
					$arrayMovement5[$i]['InvMovement']['lc_state'] = 'DRAFT';
				}
			}elseif(($arrayMovement5 <> null)&&($STATE == 'ORDER_APPROVED')) {
				for($i=0;$i<count($arrayMovement5);$i++){
					$movementDocCode5 = $this->_generate_movement_code('ENT','inc');
					$arrayMovement5[$i]['InvMovement']['lc_state']='PENDANT';
					$arrayMovement5[$i]['InvMovement']['code'] = $movementDocCode5;
					$arrayMovement5[$i]['InvMovement']['date'] = $date;
					$arrayMovement5[$i]['InvMovement']['description'] = $description;
				}
			}elseif($arrayMovement5 <> null){
				for($i=0;$i<count($arrayMovement5);$i++){
					$arrayMovement5[$i]['InvMovement']['date'] = $date;
					$arrayMovement5[$i]['InvMovement']['description'] = $description;
					/////////////////////////////////////////////////////////////////
					if(($ACTION == 'save_invoice' && $OPERATION3 == 'DELETE')/* || ($ACTION == 'save_invoice' && $OPERATION4 == 'DELETE')*/){		
						if($arrayMovement5[$i]['InvMovement']['id'] === $draftId3){
							$arrayMovement5[$i]['InvMovement']['lc_state']='DRAFT';
						}
//						if($arrayMovement5[$i]['InvMovement']['id'] === $draftId4){
//							$arrayMovement5[$i]['InvMovement']['lc_state']='DRAFT';
//						}
					}	
					/////////////////////////////////////////////////////////////////
				}
			}
			//*********************************************************
			//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//			if ($ACTION == 'save_invoice' && $STATE == 'SINVOICE_PENDANT'){
//				if($draftId3 == null){
//					$arrayMovement3['lc_state']='PENDANT';
//				}
//				if($draftId4 == null){
//					$arrayMovement4['lc_state']='PENDANT';
//				}
//			}
			//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			$dataMovement = array('PurPurchase'=>$arrayMovement);
			if ($ACTION == 'save_order'){
				$this->loadModel('InvMovement');
				//for invoice
				$dataMovement2 = array('PurPurchase'=>$arrayMovement2);
				//for movement
				$dataMovement3 = array('InvMovement'=>$arrayMovement3);
				$dataMovementDetail3 = array('InvMovementDetail'=> $arrayMovementDetails3);
//				$dataMovement4 = array('InvMovement'=>$arrayMovement4);
//				$dataMovementDetail4 = array('InvMovementDetail'=> $arrayMovementDetails4);
				if($arrayMovement5 <> null){
					$dataMovement5 = $arrayMovement5;
				}	
				if((($ACTION == 'save_order' && $OPERATION3 == 'DELETE' && $arrayMovement6 <> null)/* || ($ACTION == 'save_order' && $OPERATION4 == 'DELETE' && $arrayMovement6 <> null)*/) ){
					$dataMovement6 = $arrayMovement6;
				}	
				$dataPayDetail = null;
				$dataCostDetail = null;
			}elseif (($ACTION == 'save_invoice' && $OPERATION == 'ADD_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_PAY')) {
				$dataPayDetail = array('PurPayment'=> $arrayPayDetails);
				if($arrayMovement5 <> null){
					$dataMovement5 = $arrayMovement5;
				}	
				$dataCostDetail = null;
			}elseif (($ACTION == 'save_invoice' && $OPERATION == 'ADD_COST') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_COST') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_COST')) {
				$dataCostDetail = array('PurPrice'=> $arrayCostDetails);
				if($arrayMovement5 <> null){
					$dataMovement5 = $arrayMovement5;
				}	
				$dataPayDetail = null;
			}elseif ($ACTION == 'save_invoice') {
				$this->loadModel('InvMovement');
				//for movement
				$dataMovement3 = array('InvMovement'=>$arrayMovement3);
				$dataMovementDetail3 = array('InvMovementDetail'=> $arrayMovementDetails3);
//				$dataMovement4 = array('InvMovement'=>$arrayMovement4);
//				$dataMovementDetail4 = array('InvMovementDetail'=> $arrayMovementDetails4);
				if($arrayMovement5 <> null){
					$dataMovement5 = $arrayMovement5;
				}	
				if((($ACTION == 'save_order' && $OPERATION3 == 'DELETE' && $arrayMovement6 <> null) /*|| ($ACTION == 'save_order' && $OPERATION4 == 'DELETE' && $arrayMovement6 <> null)*/) ){
					$dataMovement6 = $arrayMovement6;
				}	
				$dataPayDetail = null;
				$dataCostDetail = null;
			}
			$dataMovementDetail = array('PurDetail'=> $arrayMovementDetails);
			
			////////////////////////////////////////////////END - SET DATA//////////////////////////////////////////////////////
			
			$validation['error'] = 0;
			$strItemsStock = '';
			////////////////////////////////////////////START- CORE SAVE////////////////////////////////////////////////////////
			if($error == 0){
				/////////////////////START - SAVE/////////////////////////////	
//				echo 'OPERATION';
//					debug($OPERATION);
//				echo 'ACTION';
//					debug($ACTION);
//				echo '$dataMovement';	
//					debug($dataMovement);
//				echo '$dataMovementDetail';	
//					debug($dataMovementDetail);
//				echo '------------------------------------------------ <br>';
//				echo 'OPERATION2';
//					debug($OPERATION);
//				echo 'ACTION';
//					debug($ACTION);
//				echo '$dataMovement2';	
//					debug($dataMovement2);
//				echo '$dataMovementDetail2';	
//					debug($dataMovementDetail);
//				echo '------------------------------------------------ <br>';
//				echo 'STOCK';
//					debug($stock);
//				echo 'OPERATION3';
//					debug($OPERATION3);
//				echo '$dataMovement3';	
//					debug($dataMovement3);
//				echo '$dataMovementDetail3';	
//					debug($dataMovementDetail3);
//				echo '------------------------------------------------ <br>';	
//				echo 'QUANTITY';
//					debug($quantity);
//				echo 'OPERATION4';
//					debug($OPERATION4);
//				echo '$dataMovement4';	
//					debug($dataMovement4);
//				echo '$dataMovementDetail4';	
//					debug($dataMovementDetail4);
//				echo '------------------------------------------------ <br>';	
//				echo '$arrayMovement5';	
//					debug($arrayMovement5);
//				echo '------------------------------------------------ <br>';	
//				debug($arrayMovement6);
//				debug($dataMovement6);
//				echo '------------------------------------------------ <br>';
//				echo '$dataPayDetail';
//				debug($dataPayDetail);
//				debug($arrayPayDetails);
//				debug($dataPayDetail);
//				debug($supplier);
//				debug($dataCostDetail);
//				debug($total);
//				debug($totalCost);
					if($validation['error'] == 0){
							$res = $this->PurPurchase->saveMovement($dataMovement, $dataMovementDetail, $OPERATION, $ACTION, $movementDocCode, $dataPayDetail, $dataCostDetail);
							if ($ACTION == 'save_order'){
								$res2 = $this->PurPurchase->saveMovement($dataMovement2, $dataMovementDetail, $OPERATION, $ACTION, $movementDocCode, null, null);
								if(($OPERATION3 != 'DEFAULT')){
									//used to insert/update type 1 detail movements 
									//used to delete movement details type 1
//									echo "ini3";
									$res3 = $this->InvMovement->saveMovement($dataMovement3, $dataMovementDetail3, $OPERATION3, 'save_in', null, $movementDocCode3);
//									echo "fin3";
								}
//								if(($quantity > $stock)||(($OPERATION4 == 'DELETE')&&($arrayMovement4['id']!==null))){	//($quantity > $stock) doesn't work when stock changes
//									//used to insert/update type 2 detail movements									
//									//used to delete movement details type 2
////									echo "ini4";
//									$res4 = $this->InvMovement->saveMovement($dataMovement4, $dataMovementDetail4, $OPERATION4, 'save_in', null, $movementDocCode4);
////									echo "fin4";
//								}	
								if($arrayMovement5 <> null){
									//used to update movements head
//									echo "ini5";
									$res5 = $this->InvMovement->saveMovement($dataMovement5, null, 'UPDATEHEAD', null, null, null);
//									echo "fin5";
								}
								if((($ACTION == 'save_order' && $OPERATION3 == 'DELETE' && $arrayMovement6 <> null)/*|| ($ACTION == 'save_order' && $OPERATION4 == 'DELETE' && $arrayMovement6 <> null)*/) ){
//									echo "ini6";
									$res6 = $this->InvMovement->saveMovement($dataMovement6, null, 'DELETEHEAD', null, null, null);
//									echo "fin6";
								}
								
							}elseif ($ACTION == 'save_invoice' && $OPERATION != 'ADD_PAY' && $OPERATION != 'EDIT_PAY' && $OPERATION != 'DELETE_PAY' && $OPERATION != 'ADD_COST' && $OPERATION != 'EDIT_COST' && $OPERATION != 'DELETE_COST') {
								if(($OPERATION3 != 'DEFAULT')){
									//used to insert/update type 1 detail movements 
									//used to delete movement details type 1
//									echo "ini3";
									$res3 = $this->InvMovement->saveMovement($dataMovement3, $dataMovementDetail3, $OPERATION3, 'save_in', null, $movementDocCode3);
//									echo "fin3";
								}
//								if(($quantity > $stock)||(($OPERATION4 == 'DELETE')&&($arrayMovement4['id']!==null))){	//($quantity > $stock) doesn't work when stock changes
//									//used to insert/update type 2 detail movements									
//									//used to delete movement details type 2
////									echo "ini4";
//									$res4 = $this->InvMovement->saveMovement($dataMovement4, $dataMovementDetail4, $OPERATION4, 'save_in', null, $movementDocCode4);
////									echo "fin4";
//								}	
								if($arrayMovement5 <> null){
									//used to update movements head
									//LO QUE ENTRE AQUI SOBREESCRIBE LA CABECERA DE $dataMovement3 y $dataMovement4
//									echo "ini5";
									$res5 = $this->InvMovement->saveMovement($dataMovement5, null, 'UPDATEHEAD', null, null, null);
//									echo "fin5";
								}
//								if((($OPERATION3 == 'DELETE' || $OPERATION4 == 'DELETE') && $arrayMovement6 <> null)){
//									$res6 = $this->InvMovement->saveMovement($dataMovement6, null, 'DELETEHEAD', null);
//								}
							}elseif(($ACTION == 'save_invoice' && $OPERATION == 'ADD_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'ADD_COST') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_COST') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_COST')){
								if($arrayMovement5 <> null){
									//used to update movements head
									$res5 = $this->InvMovement->saveMovement($dataMovement5, null, 'UPDATEHEAD', null, null, null);
								}
							}
						if(($res <> 'error')||($res2 <> 'error')){
							$movementIdSaved = $res;	//sal_sales NOTE id
							if ($ACTION == 'save_order'){
								$movementIdSaved2 = $res2;	//sal_sales INVOICE id
							}
							$strItemsStockDestination = '';
							echo $STATE.'|'.$movementIdSaved.'|'.$movementDocCode.'|'.$movementCode.'|'.$strItemsStock.$strItemsStockDestination;
						}else{
							echo 'ERROR|onSaving';
						}
					}else{
							echo 'VALIDATION|'.$validation['itemsStocks'].$strItemsStock;
					}

				/////////////////////END - SAVE////////////////////////////////	
			}else{
				echo 'ERROR|onGeneratingParameters';
			}
			////////////////////////////////////////////END-CORE SAVE////////////////////////////////////////////////////////
		}
	}
	
	private function _get_doc_id($purchaseId, $movementCode, $type, $warehouseId){
		if ($purchaseId <> null) {
			$invoiceId = $this->PurPurchase->find('list', array(
				'fields'=>array('PurPurchase.id'),
				'conditions'=>array(
					'PurPurchase.code'=>$movementCode,
					"PurPurchase.id !="=>$purchaseId
					)
			));
			$docId = key($invoiceId);
		}else{
			$this->loadModel('InvMovement');
			$movementId = $this->InvMovement->find('list', array(
				'fields'=>array('InvMovement.id'),
				'conditions'=>array(
					'InvMovement.document_code'=>$movementCode,
					'InvMovement.type'=>$type,
					'InvMovement.inv_warehouse_id'=>$warehouseId,
					)
			));
			$docId = key($movementId);
		}
		return $docId;
	}	
	
	public function ajax_save_movement_in(){
		if($this->RequestHandler->isAjax()){
			
			////////////////////////////////////////////INICIO-CAPTURAR AJAX////////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$purchaseId = $this->request->data['purchaseId'];
//			$warehouse = $this->request->data['warehouse'];

			$date = $this->request->data['date'];
			$supplier = $this->request->data['supplier'];
			$description = $this->request->data['description'];
			$exRate = $this->request->data['exRate'];
//			$movementType = $this->request->data['movementType'];
//			$documentCode = $this->request->data['documentCode'];
			$note_code = $this->request->data['note_code'];
			////////////////////////////////////////////FIN-CAPTURAR AJAX////////////////////////////////////////////////////////
			
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'supplier'=>$supplier,/*'inv_warehouse_id'=>$warehouse, 'inv_movement_type_id'=>$movementType,*/'ex_rate'=>$exRate, 'description'=>$description,'note_code'=>$note_code);
			
//			$arrayMovement['document_code']=$documentCode;
			
			//print_r($arrayMovement);
			
			$movementCode = '';
			$movementDocCode = '';
			if($purchaseId <> ''){//update
				$arrayMovement['id'] = $purchaseId;
			}else{//insert
				$movementCode = $this->_generate_code('COM');
				$movementDocCode = $this->_generate_doc_code('ORD');
				$arrayMovement['lc_state'] = 'ORDER_PENDANT';
				$arrayMovement['lc_transaction'] = 'CREATE';
				$arrayMovement['code'] = $movementCode;
	$arrayMovement['doc_code'] = $movementDocCode;
	$arrayMovement['inv_supplier_id'] = $supplier;
			}
			
			$data = array('PurPurchase'=>$arrayMovement, 'PurDetail'=>$arrayItemsDetails);
//			print_r($data);
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
			

			////////////////////////////////////////////INICIO-SAVE////////////////////////////////////////////////////////
			if($purchaseId <> ''){//update
				if($this->PurPurchase->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$purchaseId))){
					if($this->PurPurchase->saveAssociated($data)){
//						$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $supplier);
						echo 'modificado|'/*.$strItemsStock*/;
					}
				}
			}else{//insert
				if($this->PurPurchase->saveAssociated($data)){
//					$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $supplier);
					$purchaseIdInserted = $this->PurPurchase->id;
						echo 'insertado|'/*.$strItemsStock.'|'*/.$movementDocCode.'|'.$purchaseIdInserted.'|'.$movementCode;
				}
			}
			////////////////////////////////////////////FIN-SAVE////////////////////////////////////////////////////////
		
		}
	}
	
	
	public function ajax_save_invoice(){
		if($this->RequestHandler->isAjax()){
			
			////////////////////////////////////////////INICIO-CAPTURAR AJAX////////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];	
			$arrayCostsDetails = $this->request->data['arrayCostsDetails'];
			$arrayPaysDetails = $this->request->data['arrayPaysDetails'];
			$purchaseId = $this->request->data['purchaseId'];

			$date = $this->request->data['date'];
			$supplier = $this->request->data['supplier'];
			$description = $this->request->data['description'];
			$exRate = $this->request->data['exRate'];
			$note_code = $this->request->data['note_code'];
			////////////////////////////////////////////FIN-CAPTURAR AJAX////////////////////////////////////////////////////////
			
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'supplier'=>$supplier, 'ex_rate'=>$exRate, 'description'=>$description,'note_code'=>$note_code);
			
		
			$movementCode = '';
			$movementDocCode = '';
			if($purchaseId <> ''){//update
				$arrayMovement['id'] = $purchaseId;
			}
			
			//data sin costos ni pagos
			$data = array('PurPurchase'=>$arrayMovement, 'PurDetail'=>$arrayItemsDetails);
			//data con costos
			$data2 = array('PurPurchase'=>$arrayMovement, 'PurDetail'=>$arrayItemsDetails, 'PurPrice'=>$arrayCostsDetails);
			//data con pagos
			$data3 = array('PurPurchase'=>$arrayMovement, 'PurDetail'=>$arrayItemsDetails, 'PurPayment'=>$arrayPaysDetails);
			//data con pagos y costos
			$data4 = array('PurPurchase'=>$arrayMovement, 'PurDetail'=>$arrayItemsDetails, 'PurPrice'=>$arrayCostsDetails, 'PurPayment'=>$arrayPaysDetails);
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
			//print_r($data4);

			////////////////////////////////////////////INICIO-SAVE////////////////////////////////////////////////////////
			if($purchaseId <> ''){//update	
				if(($arrayCostsDetails <> array(0)) && ($arrayPaysDetails <> array(0)) ){
					if(($this->PurPurchase->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPrice->deleteAll(array('PurPrice.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPayment->deleteAll(array('PurPayment.pur_purchase_id'=>$purchaseId))) ){
				//		print_r($data4);
						if($this->PurPurchase->saveAssociated($data4)){
							echo 'modificado| cost pay d&&d&&d';
						}
					}
				}elseif ($arrayCostsDetails <> array(0)) {
					if(($this->PurPurchase->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPrice->deleteAll(array('PurPrice.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPayment->deleteAll(array('PurPayment.pur_purchase_id'=>$purchaseId))) ){
				//		print_r($data2);
						if($this->PurPurchase->saveAssociated($data2)){
							echo 'modificado| cost d&&d&&d';
						}
					}
				}elseif ($arrayPaysDetails <> array(0)) {
					if(($this->PurPurchase->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPrice->deleteAll(array('PurPrice.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPayment->deleteAll(array('PurPayment.pur_purchase_id'=>$purchaseId))) ){
				//		print_r($data3);
						if($this->PurPurchase->saveAssociated($data3)){
							echo 'modificado| pay d&&d&&d';
						}
					}
				}else{
					if(($this->PurPurchase->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPrice->deleteAll(array('PurPrice.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPayment->deleteAll(array('PurPayment.pur_purchase_id'=>$purchaseId))) ){
				//		print_r($data);
						if($this->PurPurchase->saveAssociated($data)){
							echo 'modificado| d&&d&&d';
						}
					}
				}
				
			}else{//insert
				if($this->PurPurchase->saveAssociated($data4)){
					$purchaseIdInserted = $this->PurPurchase->id;
						echo 'insertado|'/*.$strItemsStock.'|'*/.$movementDocCode.'|'.$purchaseIdInserted.'|'.$movementCode;
				}
			}
			////////////////////////////////////////////FIN-SAVE////////////////////////////////////////////////////////
		
		}
	}
	
	// (AEA Ztep 3.1) action when button Aprobar Entrada Almacen is pressed
	public function ajax_change_state_approved_movement_in(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////INICIO-CAPTURAR AJAX/////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$purchaseId = $this->request->data['purchaseId'];
			$supplier = $this->request->data['supplier'];

			$date = $this->request->data['date'];
			$description = $this->request->data['description'];
			$exRate = $this->request->data['exRate'];
			$note_code = $this->request->data['note_code'];
	$generalCode = $this->request->data['genericCode'];
//			$movementType = $this->request->data['movementType'];
//			$documentCode = $this->request->data['documentCode'];
			////////////////////////////////////////////FIN-CAPTURAR AJAX/////////////////////////////////////////////////////
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'inv_supplier_id'=>$supplier, 'description'=>$description,'note_code'=>$note_code, 'ex_rate'=>$exRate);
			$arrayMovement['lc_state'] = 'ORDER_APPROVED';
			$arrayMovement['id'] = $purchaseId;
			
			$arrayInvoice = array('date'=>$date, 'inv_supplier_id'=>$supplier, 'description'=>$description,'note_code'=>$note_code, 'ex_rate'=>$exRate/*, 'code'=>$code*/);
	/*X*/		
			$movementDocCode = $this->_generate_doc_code('FAC');
			$arrayInvoice['lc_state'] = 'INVOICE_PENDANT';
			$arrayInvoice['lc_transaction'] = 'CREATE';
			$arrayInvoice['code'] = $generalCode;
	$arrayInvoice['doc_code'] = $movementDocCode;
	$arrayInvoice['inv_supplier_id'] = $supplier;	//<--- creo q no es necesario
//			if($documentCode <> ''){
//				$arrayMovement['document_code']=$documentCode;
//			}
			
			$data = array('PurPurchase'=>$arrayMovement, 'PurDetail'=>$arrayItemsDetails);
			$dataInv = array('PurPurchase'=>$arrayInvoice, 'PurDetail'=>$arrayItemsDetails);
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
//			$arrayRemit = array('date'=>$date, 'inv_warehouse_id'=>$warehouse, 'inv_movement_type_id'=>$movementType, 'description'=>$description);
//			$arrayRemit['lc_state'] = '';
//			$arrayRemit['id'] = $movementId;
//			if($documentCode <> ''){
//				$arrayRemit['document_code']=$documentCode;
//			}
//			
//			$dataRem = array('InvMovement'=>$arrayRemit, 'InvMovementDetail'=>$arrayItemsDetails);
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
			
			//print_r($code);
			//print_r($arrayItemsDetails);
//			print_r($dataInv);
			////////////////////////////////////////////INICIO-SAVE////////////////////////////////////////////////////////
			if($purchaseId <> ''){//update
				if($this->PurPurchase->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$purchaseId))){
					if(($this->PurPurchase->saveAssociated($data))&&($this->PurPurchase->saveAssociated($dataInv))){
						
						//////////////////////////////////////////////////////////
//						if($this->InvMovement->InvMovementDetail->deleteAll(array('InvMovementDetail.inv_movement_id'=>$movementId))){
//					if($this->InvMovement->saveAssociated($data)){
//						$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
//						echo 'aprobado|'. $strItemsStock;
//					}
//				}
				//////////////////////////////////////////////////////////////////////		
//						$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
						echo 'aprobado|'/*. $strItemsStock*/;
					}
				}
			}
			////////////////////////////////////////////FIN-SAVE////////////////////////////////////////////////////////
		}
	}

	public function ajax_change_state_approved_invoice(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////INICIO-CAPTURAR AJAX/////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];	
			$arrayCostsDetails = $this->request->data['arrayCostsDetails'];
			$arrayPaysDetails = $this->request->data['arrayPaysDetails'];

			$purchaseId = $this->request->data['purchaseId'];
			$supplier = $this->request->data['supplier'];

			$date = $this->request->data['date'];
			$description = $this->request->data['description'];
			$exRate = $this->request->data['exRate'];
			$note_code = $this->request->data['note_code'];
			////////////////////////////////////////////FIN-CAPTURAR AJAX/////////////////////////////////////////////////////
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'inv_supplier_id'=>$supplier, 'description'=>$description,'ex_rate'=>$exRate, 'note_code'=>$note_code);
			$arrayMovement['lc_state'] = 'INVOICE_APPROVED';
			$arrayMovement['id'] = $purchaseId;
			
			//data sin costos ni pagos
			$data = array('PurPurchase'=>$arrayMovement, 'PurDetail'=>$arrayItemsDetails);
			//data con costos
			$data2 = array('PurPurchase'=>$arrayMovement, 'PurDetail'=>$arrayItemsDetails, 'PurPrice'=>$arrayCostsDetails);
			//data con pagos
			$data3 = array('PurPurchase'=>$arrayMovement, 'PurDetail'=>$arrayItemsDetails, 'PurPayment'=>$arrayPaysDetails);
			//data con pagos y costos
			$data4 = array('PurPurchase'=>$arrayMovement, 'PurDetail'=>$arrayItemsDetails, 'PurPrice'=>$arrayCostsDetails, 'PurPayment'=>$arrayPaysDetails);
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
			//print_r($code);
//			print_r($data);
//			print_r($dataInv);
			////////////////////////////////////////////INICIO-SAVE////////////////////////////////////////////////////////
			if($purchaseId <> ''){//update
				if(($arrayCostsDetails <> array(0)) && ($arrayPaysDetails <> array(0)) ){
					if(($this->PurPurchase->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPrice->deleteAll(array('PurPrice.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPayment->deleteAll(array('PurPayment.pur_purchase_id'=>$purchaseId))) ){
				//		print_r($data4);
						if($this->PurPurchase->saveAssociated($data4)){
							echo 'aprobado| cost pay d&&d&&d';
						}
					}
				}elseif ($arrayCostsDetails <> array(0)) {
					if(($this->PurPurchase->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPrice->deleteAll(array('PurPrice.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPayment->deleteAll(array('PurPayment.pur_purchase_id'=>$purchaseId))) ){
				//		print_r($data2);
						if($this->PurPurchase->saveAssociated($data2)){
							echo 'aprobado| cost d&&d&&d';
						}
					}
				}elseif ($arrayPaysDetails <> array(0)) {
					if(($this->PurPurchase->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPrice->deleteAll(array('PurPrice.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPayment->deleteAll(array('PurPayment.pur_purchase_id'=>$purchaseId))) ){
				//		print_r($data3);
						if($this->PurPurchase->saveAssociated($data3)){
							echo 'aprobado| pay d&&d&&d';
						}
					}
				}else{
					if(($this->PurPurchase->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPrice->deleteAll(array('PurPrice.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPayment->deleteAll(array('PurPayment.pur_purchase_id'=>$purchaseId))) ){
				//		print_r($data);
						if($this->PurPurchase->saveAssociated($data)){
							echo 'aprobado| d&&d&&d';
						}
					}
				}	
			}
			////////////////////////////////////////////FIN-SAVE////////////////////////////////////////////////////////
		}
	}
	
	public function ajax_change_state_cancelled_movement_in(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////INICIO-CAPTURAR AJAX/////////////////////////////////////////////////////
			$purchaseId = $this->request->data['purchaseId'];
//			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			//$warehouse = $this->request->data['warehouse']; //combobox is disabled doesn't send nothing
//			$warehouse = $this->InvMovement->field('InvMovement.inv_warehouse_id', array('InvMovement.id'=>$movementId));
			//debug($warehouse);
			////////////////////////////////////////////FIN-CAPTURAR AJAX/////////////////////////////////////////////////////
//			$error=$this->_validateItemsStocksOut($arrayItemsDetails, $warehouse);
//			if($error['error'] == 0){
				$data = array('id'=>$purchaseId, 'lc_state'=>'ORDER_CANCELLED');
				if($this->PurPurchase->save($data)){
//					$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
					echo 'cancelado|'/*.$strItemsStock*/;
				}
//			}else{
//				echo 'error|'.$error['itemsStocks'];
//			}
						
		}
	}
	
	public function ajax_change_state_cancelled_invoice(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////INICIO-CAPTURAR AJAX/////////////////////////////////////////////////////
			$purchaseId = $this->request->data['purchaseId'];
//			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			//$warehouse = $this->request->data['warehouse']; //combobox is disabled doesn't send nothing
//			$warehouse = $this->InvMovement->field('InvMovement.inv_warehouse_id', array('InvMovement.id'=>$movementId));
			//debug($warehouse);
			////////////////////////////////////////////FIN-CAPTURAR AJAX/////////////////////////////////////////////////////
//			$error=$this->_validateItemsStocksOut($arrayItemsDetails, $warehouse);
//			if($error['error'] == 0){
				$data = array('id'=>$purchaseId, 'lc_state'=>'INVOICE_CANCELLED');
				if($this->PurPurchase->save($data)){
//					$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
					echo 'cancelado|'/*.$strItemsStock*/;
				}
//			}else{
//				echo 'error|'.$error['itemsStocks'];
//			}
						
		}
	}
	
//	public function ajax_logic_delete(){
//		if($this->RequestHandler->isAjax()){
//			$doc_code = $this->request->data['doc_code'];		
//			$type = $this->request->data['type'];	
//			if($this->PurPurchase->updateAll(array('PurPurchase.lc_state'=>"'$type'"), array('PurPurchase.doc_code'=>$doc_code))){
//				echo 'success';
//			}
//		}
//	}
	public function ajax_logic_delete(){
		if($this->RequestHandler->isAjax()){
			$purchaseId = $this->request->data['purchaseId'];
			$type = $this->request->data['type'];	
			$genCode = $this->request->data['genCode'];
				if($this->SalSale->updateAll(array('PurPurchase.lc_state'=>"'$type'"), array('PurPurchase.id'=>$purchaseId)) 
						){
					echo 'success';
				}
				if($type === 'PINVOICE_LOGIC_DELETED'){
					$this->loadModel('InvMovement');
					$arrayMovement5 = $this->InvMovement->find('all', array(
						'fields'=>array(
							'InvMovement.id'
							,'InvMovement.date'
							,'InvMovement.description'
							),
						'conditions'=>array(
								'InvMovement.document_code'=>$genCode
							)
						,'order' => array('InvMovement.id' => 'ASC')
						,'recursive'=>0
					));
					if($arrayMovement5 <> null){
						for($i=0;$i<count($arrayMovement5);$i++){
							$arrayMovement5[$i]['InvMovement']['lc_state'] = 'DRAFT';
							$arrayMovement5[$i]['InvMovement']['code'] = 'NO'; //not sure to put this
						}
					}
					if($arrayMovement5 <> null){
						$dataMovement5 = $arrayMovement5;
					}
					if($arrayMovement5 <> null){
						$res5 = $this->InvMovement->saveMovement($dataMovement5, null, null, null);
					}
				}
		}
	}
	// END - AJAX END - AJAX END - AJAX END - AJAX END - AJAX END - AJAX END - AJAX END - AJAX END - AJAX
	
	// START - PRIVATE START - PRIVATE START - PRIVATE START - PRIVATE START - PRIVATE START - PRIVATE
	
//	private function _createStringItemsStocksUpdated($arrayItemsDetails, $idSupplier){
//		////////////////////////////////////////////INICIO-CREAR CADENA ITEMS STOCK ACUTALIZADOS//////////////////////////////
//			$strItemsStock = '';
//			for($i = 0; $i<count($arrayItemsDetails); $i++){
//				$updatedStock = $this->_find_stock($arrayItemsDetails[$i]['inv_item_id'], $idSupplier);
//				$strItemsStock .= $arrayItemsDetails[$i]['inv_item_id'].'=>'.$updatedStock.',';
//			}
//			////////////////////////////////////////////FIN-CREAR CADENA ITEMS STOCK ACUTALIZADOS/////////////////////////////////
//			return $strItemsStock;
//	}
	
	
//	private function _generate_code(){
//		$period = $this->Session->read('Period.name');
////		$period = $this->Session->read('Period.year');
////		$movementType = '';
////		if($keyword == 'ENT'){$movementType = 'entrada';}
////		if($keyword == 'SAL'){$movementType = 'salida';}
//		$movements = $this->PurPurchase->find('count', array('conditions'=>array('PurPurchase.lc_state'=>array('ORDER_PENDANT','ORDER_APPROVED','ORDER_CANCELLED','ORDER_LOGIC_DELETED')))); // there are duplicates :S, unless there is no movement delete
//		$quantity = $movements + 1; 
//		//$quantity = $this->InvMovement->getLastInsertID(); //hmm..
//		$code = 'COM-'.$period.'-'.$quantity;
//		return $code;
//	}
	
	private function _get_stocks($items, $warehouse, $limitDate = '', $dateOperator = '<='){
		$this->loadModel('InvMovement');
		$this->InvMovement->InvMovementDetail->unbindModel(array('belongsTo' => array('InvItem')));
		$this->InvMovement->InvMovementDetail->bindModel(array(
			'hasOne'=>array(
				'InvMovementType'=>array(
					'foreignKey'=>false,
					'conditions'=> array('InvMovement.inv_movement_type_id = InvMovementType.id')
				)
				
			)
		));
		$dateRanges = array();
		if($limitDate <> ''){
			$dateRanges = array('InvMovement.date '.$dateOperator => $limitDate);
		}
		
		$movements = $this->InvMovement->InvMovementDetail->find('all', array(
			'fields'=>array(
				"InvMovementDetail.inv_item_id", 
				"(SUM(CASE WHEN \"InvMovementType\".\"status\" = 'entrada' AND \"InvMovement\".\"lc_state\" = 'APPROVED' THEN \"InvMovementDetail\".\"quantity\" ELSE 0 END))-
				(SUM(CASE WHEN \"InvMovementType\".\"status\" = 'salida' AND \"InvMovement\".\"lc_state\" = 'APPROVED' THEN \"InvMovementDetail\".\"quantity\" ELSE 0 END)) AS stock"
				),
			'conditions'=>array(
				'InvMovement.inv_warehouse_id'=>$warehouse,
				'InvMovementDetail.inv_item_id'=>$items,
				$dateRanges
				),
			'group'=>array('InvMovementDetail.inv_item_id'),
			'order'=>array('InvMovementDetail.inv_item_id')
		));
		//the array format is like this:
		/*
		array(
			(int) 0 => array(
				'InvMovementDetail' => array(
					'inv_item_id' => (int) 9
				),
				(int) 0 => array(
					'stock' => '20'
				)
			),...etc,etc
		)	*/
		return $movements;
	}
	
	private function _find_item_stock($stocks, $item){
		foreach($stocks as $stock){//find required stock inside stocks array 
			if($item == $stock['InvMovementDetail']['inv_item_id']){
				return $stock[0]['stock'];
			}
		}
		//this fixes in case there isn't any item inside movement_details yet with a determinated warehouse
		return 0;
	}
	
	private function _generate_code($keyword){
		$period = $this->Session->read('Period.name');
		if($period <> ''){
			try{
				$movements = $this->PurPurchase->find('count', array(
					'conditions'=>array('PurPurchase.lc_state'=>array('ORDER_PENDANT','ORDER_APPROVED','ORDER_CANCELLED','ORDER_LOGIC_DELETED'))
				));
			}catch(Exception $e){
				return 'error';
			}
		}else{
			return 'error';
		}
		
		$quantity = $movements + 1; 
		$code = $keyword.'-'.$period.'-'.$quantity;
		return $code;
	}
	
//	private function _generate_doc_code($keyword){
//		$period = $this->Session->read('Period.name');
//
//		if ($keyword == 'ORD'){
//			$movements = $this->PurPurchase->find('count', array('conditions'=>array('PurPurchase.lc_state'=>array('ORDER_PENDANT','ORDER_APPROVED','ORDER_CANCELLED','ORDER_LOGIC_DELETED')))); // there are duplicates :S, unless there is no movement delete
//			
//		}elseif ($keyword == 'FAC'){
//			$movements = $this->PurPurchase->find('count', array('conditions'=>array('PurPurchase.lc_state'=>array('INVOICE_PENDANT','INVOICE_APPROVED','INVOICE_CANCELLED','INVOICE_LOGIC_DELETED')))); // there are duplicates :S, unless there is no movement delete
//			
//		}
//		$quantity = $movements + 1; 
//		$docCode = $keyword.'-'.$period.'-'.$quantity;
//		return $docCode;
//	}
	
	private function _generate_doc_code($keyword){
		$period = $this->Session->read('Period.name');
		if($period <> ''){
			try{
				if ($keyword == 'ORD'){
					$movements = $this->PurPurchase->find('count', array(
						'conditions'=>array('PurPurchase.lc_state'=>array('ORDER_PENDANT','ORDER_APPROVED','ORDER_CANCELLED','ORDER_LOGIC_DELETED'))
					)); 
				}elseif ($keyword == 'CFA'){
					$movements = $this->PurPurchase->find('count', array(
						'conditions'=>array('PurPurchase.lc_state'=>array('PINVOICE_PENDANT','PINVOICE_APPROVED','PINVOICE_CANCELLED','PINVOICE_LOGIC_DELETED'))
					));
				}
			}catch(Exception $e){
				return 'error';
			}
		}else{
			return 'error';
		}
		
		$quantity = $movements + 1; 
		$docCode = $keyword.'-'.$period.'-'.$quantity;
		return $docCode;
	}
	
	private function _generate_movement_code($keyword, $type){
		$this->loadModel('InvMovement');
		$period = $this->Session->read('Period.name');
		$movementType = '';
		if($keyword == 'ENT'){$movementType = 'entrada';}
		if($keyword == 'SAL'){$movementType = 'salida';}
		if($period <> ''){
			try{
				$movements = $this->InvMovement->find('count', array(
					'conditions'=>array(
						'InvMovementType.status'=>$movementType
						,'InvMovement.code !='=>'NO'
						)
				)); 
			}catch(Exception $e){
				return 'error';
			}
			
//			$movementss = $this->InvMovement->find('all', array(
//					'conditions'=>array('InvMovementType.status'=>$movementType)
//				)); 
//		echo '------------------------------------------------ <br>';		
//		echo '---movements count--- <br>';	
//		debug($movements);
//		echo '---movements --- <br>';	
//		debug($movementss);
//		echo '----movement type------- <br>';
//		debug($movementType);
//		echo '------------------------------------------------ <br>';
			
		}else{
			return 'error';
		}
		if($type == 'inc'){
			static $inc = 0;
			$quantity = $movements + 1 + $inc;
			$inc++;
		}else{
			$quantity = $movements + 1; 
		}
		$code = $keyword.'-'.$period.'-'.$quantity;
		return $code;
	}
	
	public function _get_movements_details($idMovement){
		$movementDetails = $this->PurPurchase->PurDetail->find('all', array(
			'conditions'=>array(
				'PurDetail.pur_purchase_id'=>$idMovement
				),
			'fields'=>array('InvItem.name', 'InvItem.code', 'PurDetail.ex_fob_price', 'PurDetail.quantity','PurDetail.inv_supplier_id', 'InvItem.id', 'InvSupplier.name','InvSupplier.id',)
			));
		
		$formatedMovementDetails = array();
		foreach ($movementDetails as $key => $value) {
			// gets the first price in the list of the item prices
//			$priceDirty = $this->PurPurchase->PurDetail->InvItem->InvPrice->find('first', array(
//					'fields'=>array('InvPrice.price'),
//					'order' => array('InvPrice.date_created' => 'desc'),
//					'conditions'=>array(
//						'InvPrice.inv_item_id'=>$value['InvItem']['id']
//						)
//				));
				//$price = $priceDirty['InvPrice']['price'];
			
			$formatedMovementDetails[$key] = array(
				'itemId'=>$value['InvItem']['id'],
				'item'=>'[ '. $value['InvItem']['code'].' ] '.$value['InvItem']['name'],
				'exFobPrice'=>$value['PurDetail']['ex_fob_price'],//llamar precio
				'cantidad'=>$value['PurDetail']['quantity'],//llamar cantidad
				'supplierId'=>$value['InvSupplier']['id'],
				'supplier'=>$value['InvSupplier']['name'],//llamar almacen
				);
		}
//debug($formatedMovementDetails);		
		return $formatedMovementDetails;
	}
	
	public function _get_costs_details($idMovement){
		$movementDetails = $this->PurPurchase->PurPrice->find('all', array(
			'conditions'=>array(
				'PurPrice.pur_purchase_id'=>$idMovement
				),
			'fields'=>array('InvPriceType.name', 'PurPrice.ex_amount', 'InvPriceType.id'/*, 'PurPurchase.inv_supplier_id','InvPrice.price'*/)
			));
		
		$formatedMovementDetails = array();
		foreach ($movementDetails as $key => $value) {
			// gets the first price in the list of the item prices
//			$priceDirty = $this->PurPurchase->PurDetail->InvItem->InvPrice->find('first', array(
//					'fields'=>array('InvPrice.price'),
//					'order' => array('InvPrice.date_created' => 'desc'),
//					'conditions'=>array(
//						'InvPrice.inv_item_id'=>$value['InvItem']['id']
//						)
//				));
				//$price = $priceDirty['InvPrice']['price'];
			
			$formatedMovementDetails[$key] = array(
				'costId'=>$value['InvPriceType']['id'],
				'costCodeName'=>$value['InvPriceType']['name'],
				'costExAmount'=>$value['PurPrice']['ex_amount']//llamar precio
				);
		}
//debug($formatedMovementDetails);		
		return $formatedMovementDetails;
	}
	
	public function _get_pays_details($idMovement){
		$paymentDetails = $this->PurPurchase->PurPayment->find('all', array(
			'conditions'=>array(
				'PurPayment.pur_purchase_id'=>$idMovement
				),
			'fields'=>array('PurPayment.date', 'PurPayment.amount', 'PurPayment.description')
			));
		
		$formatedPaymentDetails = array();
		foreach ($paymentDetails as $key => $value) {
			$formatedPaymentDetails[$key] = array(
				'dateId'=>$value['PurPayment']['date'],//llamar precio
				//'payDate'=>strftime("%A, %d de %B de %Y", strtotime($value['SalPayment']['date'])),
				'payDate'=>strftime("%d/%m/%Y", strtotime($value['PurPayment']['date'])),
				'payAmount'=>$value['PurPayment']['amount'],//llamar cantidad
				'payDescription'=>$value['PurPayment']['description']
				);
		}
//debug($formatedPaymentDetails);		strftime("%A, %d de %B de %Y", $value['SalPayment']['date'])
		return $formatedPaymentDetails;
	}
/**
 * index method
 *
 * @return void
 */
	public function index_order() {	
		
		///////////////////////////////////////START - CREATING VARIABLES//////////////////////////////////////
		$filters = array();
		$doc_code = '';
		$note_code = '';
		$period = $this->Session->read('Period.name');
		///////////////////////////////////////END - CREATING VARIABLES////////////////////////////////////////
		
		////////////////////////////START - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		if($this->request->is("post")) {
			$url = array('action'=>'index_order');
			$parameters = array();
			$empty=0;
			if(isset($this->request->data['PurPurchase']['code']) && $this->request->data['PurPurchase']['doc_code']){
				$parameters['doc_code'] = trim(strip_tags($this->request->data['PurPurchase']['doc_code']));
			}else{
				$empty++;
			}
			if(isset($this->request->data['PurPurchase']['doc_code']) && $this->request->data['PurPurchase']['note_code']){
				$parameters['note_code'] = trim(strip_tags($this->request->data['PurPurchase']['note_code']));
			}else{
				$empty++;
			}
			if($empty == 2){
				$parameters['search']='empty';
			}else{
				$parameters['search']='yes';
			}
			$this->redirect(array_merge($url,$parameters));
		}
		////////////////////////////END - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////

		////////////////////////////START - SETTING URL FILTERS//////////////////////////////////////
		if(isset($this->passedArgs['doc_code'])){
			$filters['PurPurchase.code LIKE'] = '%'.strtoupper($this->passedArgs['doc_code']).'%';
			$code = $this->passedArgs['doc_code'];
		}
		if(isset($this->passedArgs['doc_code'])){
			$filters['PurPurchase.doc_code LIKE'] = '%'.strtoupper($this->passedArgs['note_code']).'%';
			$doc_code = $this->passedArgs['note_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->paginate = array(
			"conditions"=>array(
				"PurPurchase.lc_state !="=>"ORDER_LOGIC_DELETED",
				'PurPurchase.lc_state LIKE'=> '%ORDER%',
				"to_char(PurPurchase.date,'YYYY')"=> $period,
			//	"InvMovementType.status"=> "entrada",
				$filters
			 ),
			"recursive"=>0,
			"fields"=>array("PurPurchase.id", "PurPurchase.code", "PurPurchase.doc_code", "PurPurchase.date", "PurPurchase.note_code", /*"PurPurchase.inv_supplier_id", "InvSupplier.name",*/ "PurPurchase.lc_state"),
			"order"=> array("PurPurchase.id"=>"desc"),
			"limit" => 15,
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('purPurchases', $this->paginate('PurPurchase'));
		$this->set('doc_code', $doc_code);
		$this->set('note_code', $note_code);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		
//		$this->paginate = array(
//			'conditions' => array(
//				'PurPurchase.lc_state !='=>'ORDER_LOGIC_DELETED'
//				,'PurPurchase.lc_state LIKE'=> '%ORDER%'
//			),
//			'order' => array('PurPurchase.id' => 'desc'),
//			'limit' => 15
//		);
//		$this->PurPurchase->recursive = 0;
//		$this->set('purPurchases', $this->paginate());
	}
	
	public function index_invoice(){
		///////////////////////////////////////START - CREATING VARIABLES//////////////////////////////////////
		$filters = array();
		$doc_code = '';
		$note_code = '';
		$period = $this->Session->read('Period.name');
		///////////////////////////////////////END - CREATING VARIABLES////////////////////////////////////////
		
		////////////////////////////START - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		if($this->request->is("post")) {
			$url = array('action'=>'index_invoice');
			$parameters = array();
			$empty=0;
			if(isset($this->request->data['PurPurchase']['doc_code']) && $this->request->data['PurPurchase']['doc_code']){
				$parameters['doc_code'] = trim(strip_tags($this->request->data['PurPurchase']['doc_code']));
			}else{
				$empty++;
			}
			if(isset($this->request->data['PurPurchase']['note_code']) && $this->request->data['PurPurchase']['note_code']){
				$parameters['note_code'] = trim(strip_tags($this->request->data['PurPurchase']['note_code']));
			}else{
				$empty++;
			}
			if($empty == 2){
				$parameters['search']='empty';
			}else{
				$parameters['search']='yes';
			}
			$this->redirect(array_merge($url,$parameters));
		}
		////////////////////////////END - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////

		////////////////////////////START - SETTING URL FILTERS//////////////////////////////////////
		if(isset($this->passedArgs['doc_code'])){
			$filters['PurPurchase.code LIKE'] = '%'.strtoupper($this->passedArgs['doc_code']).'%';
			$doc_code = $this->passedArgs['doc_code'];
		}
		if(isset($this->passedArgs['note_code'])){
			$filters['PurPurchase.doc_code LIKE'] = '%'.strtoupper($this->passedArgs['note_code']).'%';
			$note_code = $this->passedArgs['note_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->paginate = array(
			"conditions"=>array(
				"PurPurchase.lc_state !="=>"PINVOICE_LOGIC_DELETED",
				'PurPurchase.lc_state LIKE'=> '%PINVOICE%',
				"to_char(PurPurchase.date,'YYYY')"=> $period,
			//	"InvMovementType.status"=> "entrada",
				$filters
			 ),
			"recursive"=>0,
			"fields"=>array("PurPurchase.id", "PurPurchase.code", "PurPurchase.doc_code", "PurPurchase.date", "PurPurchase.note_code",/*"PurPurchase.inv_supplier_id", "InvSupplier.name",*/ "PurPurchase.lc_state"),
			"order"=> array("PurPurchase.id"=>"desc"),
			"limit" => 15,
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('purPurchases', $this->paginate('PurPurchase'));
		$this->set('doc_code', $doc_code);
		$this->set('note_code', $note_code);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		
		
//		$this->paginate = array(
//			'conditions' => array(
//				'PurPurchase.lc_state !='=>'INVOICE_LOGIC_DELETED',
//				'PurPurchase.lc_state LIKE'=> '%INVOICE%',
//			),
//			'order' => array('PurPurchase.id' => 'desc'),
//			'limit' => 15
//		);
//		$this->PurPurchase->recursive = 0;
//		$this->set('purPurchases', $this->paginate());
	}
	
	public function index_remit(){
		$this->paginate = array(
			'conditions' => array(
				'PurPurchase.lc_state !='=>'LOGIC_DELETE',
				'PurPurchase.lc_state LIKE'=> '%REMIT%',
			),
			'order' => array('PurPurchase.id' => 'desc'),
			'limit' => 15
		);
		$this->PurPurchase->recursive = 0;
		$this->set('purPurchases', $this->paginate());
	}

	/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->PurPurchase->id = $id;
		if (!$this->PurPurchase->exists()) {
			throw new NotFoundException(__('Invalid %s', __('pur purchase')));
		}
		$this->set('purPurchase', $this->PurPurchase->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->PurPurchase->create();
			if ($this->PurPurchase->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('pur purchase')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('pur purchase')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$invSuppliers = $this->PurPurchase->InvSupplier->find('list');
		$this->set(compact('invSuppliers'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->PurPurchase->id = $id;
		if (!$this->PurPurchase->exists()) {
			throw new NotFoundException(__('Invalid %s', __('pur purchase')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->PurPurchase->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('pur purchase')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('pur purchase')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->PurPurchase->read(null, $id);
		}
		$invSuppliers = $this->PurPurchase->InvSupplier->find('list');
		$this->set(compact('invSuppliers'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->PurPurchase->id = $id;
		if (!$this->PurPurchase->exists()) {
			throw new NotFoundException(__('Invalid %s', __('pur purchase')));
		}
		if ($this->PurPurchase->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('pur purchase')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('pur purchase')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
	
	
	
	//////////////////////////////////////////START-GRAPHICS//////////////////////////////////////////
	public function vgraphics(){
		$this->loadModel("AdmPeriod");
		$years = $this->AdmPeriod->find("list", array(
			"order"=>array("name"=>"desc"),
			"fields"=>array("name", "name")
			)
		);
		
		$this->loadModel("InvItem");
		
		$itemsClean = $this->InvItem->find("list", array('order'=>array('InvItem.code')));
		$items[0]="TODOS";
		foreach ($itemsClean as $key => $value) {
			$items[$key] = $value;
		}
		
		$this->loadModel("InvPriceType");
		$priceTypes = $this->InvPriceType->find("list", array("conditions"=>array("name"=>array("FOB", "CIF"))));
		
		$this->set(compact("years", "items", "priceTypes"));
		//debug($this->_get_bars_sales_and_time("2013", "0"));
	}
	
	public function ajax_get_graphics_data(){
		if($this->RequestHandler->isAjax()){
			$year = $this->request->data['year'];
			$currency = $this->request->data['currency'];
			$item = $this->request->data['item'];
			$priceType = $this->request->data['priceType'];;
			$string = $this->_get_bars_purchases_and_time($year, $item, $currency, $priceType);
			echo $string;
		}
//		$string .= '30|54|12|114|64|100|98|80|10|50|169|222';
	}
	
	private function _get_bars_purchases_and_time($year, $item, $currency, $priceType){
		$conditionItem = null;
		$dataString = "";
		
		if($item > 0){
			$conditionItem = array("PurDetail.inv_item_id" => $item);
		}
		
		$currencyType = "price";
		if($currency == "dolares"){
			$currencyType = "ex_price";
		}
		
		//*****************************************************************************//
		$data = $this->PurPurchase->PurDetail->find('all', array(
			"fields"=>array(
				"to_char(\"PurPurchase\".\"date\",'mm') AS month",
				'SUM("PurDetail"."quantity" * (SELECT '.$currencyType.'  FROM inv_prices where inv_item_id = "PurDetail"."inv_item_id" AND date <= "PurPurchase"."date" AND inv_price_type_id='.$priceType.' order by date DESC, date_created DESC LIMIT 1))'
			),
			"conditions"=>array(
				"to_char(PurPurchase.date,'YYYY')"=>$year,
				"PurPurchase.lc_state"=>"PINVOICE_APPROVED",
				$conditionItem
			),
			'group'=>array("to_char(PurPurchase.date,'mm')")
		));
		//*****************************************************************************//
		
		
		//format data on string to response ajax request
		$months = array(1,2,3,4,5,6,7,8,9,10,11,12);
		
		foreach ($months as $month) {
			$exist = 0;
			foreach ($data as $value) {
				if($month == (int)$value[0]['month']){
					$dataString .= $value[0]['sum']."|";
					//debug($dataString);
					$exist++;
				}
			}
			if($exist == 0){
				$dataString .= "0|";
			}
		}
		
		return substr($dataString, 0, -1);
	}
	
	//////////////////////////////////////////END-GRAPHICS//////////////////////////////////////////

//////END CLASS
}
