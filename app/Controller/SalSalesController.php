<?php
App::uses('AppController', 'Controller');
/**
 * SalSales Controller
 *
 * @property SalSale $SalSale
 */
class SalSalesController extends AppController {

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
	//public $components = array('Session')
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
			if(isset($this->request->data['SalSale']['doc_code']) && $this->request->data['SalSale']['doc_code']){
				$parameters['doc_code'] = trim(strip_tags($this->request->data['SalSale']['doc_code']));
			}else{
				$empty++;
			}
			if(isset($this->request->data['SalSale']['note_code']) && $this->request->data['SalSale']['note_code']){
				$parameters['note_code'] = trim(strip_tags($this->request->data['SalSale']['note_code']));
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
			$filters['SalSale.doc_code LIKE'] = '%'.strtoupper($this->passedArgs['doc_code']).'%';
			$doc_code = $this->passedArgs['doc_code'];
		}
		if(isset($this->passedArgs['note_code'])){
			$filters['SalSale.note_code LIKE'] = '%'.strtoupper($this->passedArgs['note_code']).'%';
			$note_code = $this->passedArgs['note_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->SalSale->bindModel(array('hasOne'=>array('SalCustomer'=>array('foreignKey'=>false,'conditions'=> array('SalEmployee.sal_customer_id = SalCustomer.id')))));
		
		$this->paginate = array(
			"conditions"=>array(
				"SalSale.lc_state !="=>"NOTE_LOGIC_DELETED",
				'SalSale.lc_state LIKE'=> '%NOTE%',
				"to_char(SalSale.date,'YYYY')"=> $period,
			//	"InvMovementType.status"=> "entrada",
				$filters
			 ),
			"recursive"=>0,
			"fields"=>array("SalSale.id", "SalSale.code", "SalSale.doc_code", "SalSale.date", "SalSale.note_code", "SalSale.sal_employee_id", "SalEmployee.name", "SalSale.lc_state", "SalCustomer.name"),
			"order"=> array("SalSale.id"=>"desc"),
			"limit" => 15,
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('salSales', $this->paginate('SalSale'));
		$this->set('doc_code', $doc_code);
		$this->set('note_code', $note_code);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		
		
		
//		$this->paginate = array(
//			'conditions' => array(
//				'SalSale.lc_state !='=>'NOTE_LOGIC_DELETED'
//				,'SalSale.lc_state LIKE'=> '%ORDER%'
//			),
//			'order' => array('SalSale.id' => 'desc'),
//			'limit' => 15
//		);
//		$this->SalSale->recursive = 0;
//		$this->set('salSales', $this->paginate());
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
			if(isset($this->request->data['SalSale']['doc_code']) && $this->request->data['SalSale']['doc_code']){
				$parameters['doc_code'] = trim(strip_tags($this->request->data['SalSale']['doc_code']));
			}else{
				$empty++;
			}
			if(isset($this->request->data['SalSale']['note_code']) && $this->request->data['SalSale']['note_code']){
				$parameters['note_code'] = trim(strip_tags($this->request->data['SalSale']['note_code']));
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
			$filters['SalSale.doc_code LIKE'] = '%'.strtoupper($this->passedArgs['doc_code']).'%';
			$doc_code = $this->passedArgs['doc_code'];
		}
		if(isset($this->passedArgs['note_code'])){
			$filters['SalSale.note_code LIKE'] = '%'.strtoupper($this->passedArgs['note_code']).'%';
			$note_code = $this->passedArgs['note_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->SalSale->bindModel(array('hasOne'=>array('SalCustomer'=>array('foreignKey'=>false,'conditions'=> array('SalEmployee.sal_customer_id = SalCustomer.id')))));
		
		$this->paginate = array(
			"conditions"=>array(
				"SalSale.lc_state !="=>"SINVOICE_LOGIC_DELETED",
				'SalSale.lc_state LIKE'=> '%SINVOICE%',
				"to_char(SalSale.date,'YYYY')"=> $period,
				$filters
			 ),
			"recursive"=>0,
			"fields"=>array("SalSale.id", "SalSale.code", "SalSale.doc_code", "SalSale.date", "SalSale.note_code", "SalSale.sal_employee_id", "SalEmployee.name", "SalSale.lc_state", "SalCustomer.name"),
			"order"=> array("SalSale.id"=>"desc"),
			"limit" => 15,
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('salSales', $this->paginate('SalSale'));
		$this->set('doc_code', $doc_code);
		$this->set('note_code', $note_code);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
	}
	
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
		
		
		$this->loadModel('AdmUser');
		
		$salAdmUsers = $this->AdmUser->AdmProfile->find('list');
		//array_unshift($salAdmUsers,"Sin Vendedor"); //REVISAR ESTO ARRUINA EL CODIGO Q BOTA EL DROPDOWN
		$salCustomers = $this->SalSale->SalEmployee->SalCustomer->find('list'/*, array('conditions'=>array('SalCustomer.location'=>'COCHABAMBA'))*/);
		$customer = key($salCustomers);
		$salEmployees = $this->SalSale->SalEmployee->find('list', array('conditions'=>array('SalEmployee.sal_customer_id'=>$customer)));
	//	$taxNumber = key($salCustomers);
		$salTaxNumbers = $this->SalSale->SalTaxNumber->find('list', array('conditions'=>array('SalTaxNumber.sal_customer_id'=>$customer)));
			
		$this->SalSale->recursive = -1;
		$this->request->data = $this->SalSale->read(null, $id);
	//	$date='';
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
		$genericCode ='';
		//debug($this->request->data);
	//	debug($salAdmUsers);
		$salDetails = array();
		$documentState = '';
		$customerId = '';
		$admUserId = '';
		if($id <> null){
			$date = date("d/m/Y", strtotime($this->request->data['SalSale']['date']));
			$salDetails = $this->_get_movements_details($id);
			$documentState =$this->request->data['SalSale']['lc_state'];
			$genericCode = $this->request->data['SalSale']['code'];
			
			$employeeId = $this->request->data['SalSale']['sal_employee_id'];
			$customerId = $this->SalSale->SalEmployee->find('list', array('fields'=>array('SalEmployee.sal_customer_id'),'conditions'=>array('SalEmployee.id'=>$employeeId)));
			$salEmployees = $this->SalSale->SalEmployee->find('list', array('conditions'=>array('SalEmployee.sal_customer_id'=>$customerId)));
			$salTaxNumbers = $this->SalSale->SalTaxNumber->find('list', array('conditions'=>array('SalTaxNumber.sal_customer_id'=>$customerId)));		
			
			$admProfileId = $this->request->data['SalSale']['salesman_id'];
			$admUserId = $this->AdmUser->AdmProfile->find('list', array('fields'=>array('AdmProfile.id'),'conditions'=>array('AdmProfile.adm_user_id'=>$admProfileId)));
//		debug($admProfileId);
//			debug($admUserId);
			$exRate = $this->request->data['SalSale']['ex_rate'];
		}
		$this->set(compact('salCustomers','customerId', 'salTaxNumbers', 'salEmployees','employeeId', 'salAdmUsers', 'admUserId','id', 'date', 'salDetails', 'documentState', 'genericCode', 'exRate'));
		//debug($admProfileId);
	//	debug($salAdmUsers);
	}
	
	public function save_invoice(){
		$id = '';
		if(isset($this->passedArgs['id'])){
			$id = $this->passedArgs['id'];
		}
		$this->loadModel('AdmUser');
		
		$salAdmUsers = $this->AdmUser->AdmProfile->find('list');
	//	debug($salAdmUsers);
		//array_unshift($salAdmUsers,"Sin Vendedor");//REVISAR ESTO ARRUINA EL CODIGO Q BOTA EL DROPDOWN
	
		$salCustomers = $this->SalSale->SalEmployee->SalCustomer->find('list');
		$customer = key($salCustomers);
		$salEmployees = $this->SalSale->SalEmployee->find('list', array('conditions'=>array('SalEmployee.sal_customer_id'=>$customer)));
	//	$taxNumber = key($salCustomers);
		$salTaxNumbers = $this->SalSale->SalTaxNumber->find('list', array('conditions'=>array('SalTaxNumber.sal_customer_id'=>$customer)));
	
				
		$this->SalSale->recursive = -1;
		$this->request->data = $this->SalSale->read(null, $id);
		$date='';
		$genericCode ='';
		$originCode = '';
		$customerId = '';
		$admUserId = '';
		//debug($this->request->data);
		$salDetails = array();
		$salPayments = array();
//		$purPrices = array();
		$documentState = '';
		$exRate = '8.00';	//esto tiene q llamar al cambio del dia
		if($id <> null){
			$date = date("d/m/Y", strtotime($this->request->data['SalSale']['date']));
			$salDetails = $this->_get_movements_details($id);
	//		$purPrices = $this->_get_costs_details($id);
			$salPayments = $this->_get_pays_details($id);
			$documentState =$this->request->data['SalSale']['lc_state'];
			$genericCode = $this->request->data['SalSale']['code'];
			//buscar el codigo del documento origen
			$originDocCode = $this->SalSale->find('first', array(
				'fields'=>array('SalSale.doc_code'),
				'conditions'=>array(
					'SalSale.code'=>$genericCode,
					'SalSale.lc_state LIKE'=> '%NOTE%'
					)
			));
			$originCode = $originDocCode['SalSale']['doc_code'];
			$employeeId = $this->request->data['SalSale']['sal_employee_id'];
			$customerId = $this->SalSale->SalEmployee->find('list', array('fields'=>array('SalEmployee.sal_customer_id'),'conditions'=>array('SalEmployee.id'=>$employeeId)));
			$salEmployees = $this->SalSale->SalEmployee->find('list', array('conditions'=>array('SalEmployee.sal_customer_id'=>$customerId)));
			$salTaxNumbers = $this->SalSale->SalTaxNumber->find('list', array('conditions'=>array('SalTaxNumber.sal_customer_id'=>$customerId)));		
			
			$admProfileId = $this->request->data['SalSale']['salesman_id'];
			$admUserId = $this->AdmUser->AdmProfile->find('list', array('fields'=>array('AdmProfile.id'),'conditions'=>array('AdmProfile.adm_user_id'=>$admProfileId)));
			$exRate = $this->request->data['SalSale']['ex_rate'];
		}
		
			
		$this->set(compact('salCustomers','customerId', 'salTaxNumbers', 'salEmployees','employeeId', 'salAdmUsers', 'admUserId','id', 'date', 'salDetails', 'salPayments', 'documentState', 'genericCode', 'originCode', 'exRate'));
//debug($this->request->data);
	}
	
	public function _get_movements_details($idMovement){
		$movementDetails = $this->SalSale->SalDetail->find('all', array(
			'conditions'=>array(
				'SalDetail.sal_sale_id'=>$idMovement
				),																									                             /*REVISAR ESTO V*/
			'fields'=>array('InvItem.name', 'InvItem.code', 'SalDetail.sale_price', 'SalDetail.quantity','SalDetail.inv_warehouse_id', 'InvItem.id', 'InvWarehouse.name','InvWarehouse.id', 'InvItem.id')
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
				'salePrice'=>$value['SalDetail']['sale_price'],//llamar precio
				'cantidad'=>$value['SalDetail']['quantity'],//llamar cantidad
				'warehouseId'=>$value['InvWarehouse']['id'],
				'warehouse'=>$value['InvWarehouse']['name'],//llamar almacen
//	'cifPrice'=>$value['SalDetail']['cif_price'],
//	'exCifPrice'=>$value['SalDetail']['ex_cif_price'],
				'stock'=> $this->_find_stock($value['InvItem']['id'], $value['SalDetail']['inv_warehouse_id'])
				
				);
		}
//debug($formatedMovementDetails);		
		return $formatedMovementDetails;
	}
	
	public function _get_pays_details($idMovement){
		$paymentDetails = $this->SalSale->SalPayment->find('all', array(
			'conditions'=>array(
				'SalPayment.sal_sale_id'=>$idMovement
				),																									                            
			'fields'=>array('SalPayment.date', 'SalPayment.amount','SalPayment.description')
			));
		
		$formatedPaymentDetails = array();
		foreach ($paymentDetails as $key => $value) {
			$formatedPaymentDetails[$key] = array(
				'dateId'=>$value['SalPayment']['date'],//llamar precio
				//'payDate'=>strftime("%A, %d de %B de %Y", strtotime($value['SalPayment']['date'])),
				'payDate'=>strftime("%d/%m/%Y", strtotime($value['SalPayment']['date'])),
				'payAmount'=>$value['SalPayment']['amount'],//llamar cantidad
				'payDescription'=>$value['SalPayment']['description']
				);
		}
//debug($formatedPaymentDetails);		strftime("%A, %d de %B de %Y", $value['SalPayment']['date'])
		return $formatedPaymentDetails;
	}
	
	public function ajax_list_controllers_inside(){
		if($this->RequestHandler->isAjax()){
			$customer = $this->request->data['customer']; //???????????????????
		//	print_r( $customer);
		//	$admControllers = $this->AdmMenu->AdmAction->AdmController->find('list', array('conditions'=>array('AdmController.adm_module_id'=>$module)));
			$salEmployees = $this->SalSale->SalEmployee->find('list', array('conditions'=>array('SalEmployee.sal_customer_id'=>$customer)));
			$salTaxNumbers = $this->SalSale->SalTaxNumber->find('list', array('conditions'=>array('SalTaxNumber.sal_customer_id'=>$customer)));

		//	print_r( $salEmployees);
		//	$controller = key($admControllers);
		//	$employee = key($salEmployees);
			//$admActions = $this->AdmMenu->AdmAction->find('list', array('conditions'=>array('AdmAction.adm_controller_id'=>$controller)));
		//	$admActions = $this->_list_action_inside($controller);
			$this->set(compact('salEmployees', 'salTaxNumbers'/*'admControllers','admActions'*/));			
		}else{
			$this->redirect($this->Auth->logout());
		}
	}
	
	public function ajax_initiate_modal_add_item_in(){
		if($this->RequestHandler->isAjax()){
						
			$itemsAlreadySaved = $this->request->data['itemsAlreadySaved'];
			$warehouseItemsAlreadySaved = $this->request->data['warehouseItemsAlreadySaved'];
			
			$invWarehouses = $this->SalSale->SalDetail->InvItem->InvMovementDetail->InvMovement->InvWarehouse->find('list');
			
			$warehouse = key($invWarehouses);
			
			$itemsAlreadySavedInWarehouse = [];
			for($i=0; $i<count($itemsAlreadySaved); $i++){
				if($warehouseItemsAlreadySaved[$i] == $warehouse){
					$itemsAlreadySavedInWarehouse[] = $itemsAlreadySaved[$i];
				}	
			}
			
			$items = $this->SalSale->SalDetail->InvItem->find('list', array(
				'conditions'=>array(
					'NOT'=>array('InvItem.id'=>$itemsAlreadySavedInWarehouse)
				),
				'recursive'=>-1,
				'order'=>array('InvItem.code')
			));
			
			$firstItemListed = key($items);
			
			//$stock = $this->_find_stock($firstItemListed, $warehouse);
			$stocks = $this->_get_stocks($firstItemListed, $warehouse);
			$stock = $this->_find_item_stock($stocks, $firstItemListed);
			$priceDirty = $this->SalSale->SalDetail->InvItem->InvPrice->find('first', array(
				'fields'=>array('InvPrice.price'),
				'order' => array('InvPrice.date_created' => 'desc'),
				'conditions'=>array(
					'InvPrice.inv_item_id'=>$firstItemListed
					)
			));
			if($priceDirty==array()){
				$price = 0;
			}  else {

				$price = $priceDirty['InvPrice']['price'];
			}

				$this->set(compact('items', 'price', 'invWarehouses', 'stock', 'warehouse'));
			}
	}
	
	public function ajax_initiate_modal_edit_item_in(){
		if($this->RequestHandler->isAjax()){
						
		//	$itemsAlreadySaved = $this->request->data['itemsAlreadySaved'];
//			$warehouse = $this->request->data['warehouse'];
			$item = $this->request->data['item'];
			$warehouse = $this->request->data['warehouse'];
//			$supplier = $this->request->data['supplier'];
//			$itemsBySupplier = $this->PurPurchase->InvSupplier->InvItemsSupplier->find('list', array(
//				'fields'=>array('InvItemsSupplier.inv_item_id'),
//				'conditions'=>array(
//					'InvItemsSupplier.inv_supplier_id'=>$supplier
//				),
//				'recursive'=>-1
//			)); 
//debug($itemsBySupplier);			
//			$items = $this->SalSale->SalDetail->InvItem->find('list', array(
//				'conditions'=>array(
//					'NOT'=>array('InvItem.id'=>$itemsAlreadySaved)
//					
//					/*,'InvItem.id'=>$itemsBySupplier*/
//				),
//				'recursive'=>-1
//				//'fields'=>array('InvItem.id', 'CONCAT(InvItem.code, '-', InvItem.name)')
//			));
			
			$invWarehouses = $this->SalSale->SalDetail->InvItem->InvMovementDetail->InvMovement->InvWarehouse->find('list');
			
			
	//		$firstItemListed = key($items);
			
		//	$warehouse = key($invWarehouses);
			
			$stock = $this->_find_stock($item/*$firstItemListed*/, $warehouse);
			

//debug($invWarehouses);
//debug($item);
//debug($warehouse);
//debug($stock);
//debug($invWarehouses);
////debug($this->request->data);
		// gets the first price in the list of the item prices
		//$firstItemListed = key($items);
		$priceDirty = $this->SalSale->SalDetail->InvItem->InvPrice->find('first', array(
			'fields'=>array('InvPrice.price'),
			'order' => array('InvPrice.date_created' => 'desc'),
			'conditions'=>array(
				'InvPrice.inv_item_id'=>/*$firstItemListed*/$item
				)
		));
////debug($priceDirty);
		if($priceDirty==array()){
			$price = 0;
		}  else {
			
			$price = $priceDirty['InvPrice']['price'];
		}
				
			$this->set(compact(/*'item', */'price','invWarehouses', 'stock', 'warehouse'));
		}
	}
	
	public function ajax_update_stock_modal(){
		if($this->RequestHandler->isAjax()){
			$item = $this->request->data['item'];
			//////////////////////CAMBIAR POR EL ALGORITMO QUE SACA EL PRECIO PRORRATEADO////////////////
			$priceDirty = $this->SalSale->SalDetail->InvItem->InvPrice->find('first', array(
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
			$this->set(compact('price'));
		}
	}
	
	
	public function ajax_update_stock_modal_1(){
		if($this->RequestHandler->isAjax()){
			$item = $this->request->data['item'];
			$warehouse = $this->request->data['warehouse'];
			
			$stock = $this->_find_stock($item, $warehouse);			
			
			$this->set(compact('stock'));
		}
	}
	
	public function ajax_update_items_modal(){
		if($this->RequestHandler->isAjax()){
			$itemsAlreadySaved = $this->request->data['itemsAlreadySaved'];
			$warehouseItemsAlreadySaved = $this->request->data['warehouseItemsAlreadySaved'];
			$warehouse = $this->request->data['warehouse'];
			
			$itemsAlreadySavedInWarehouse = [];
			for($i=0; $i<count($itemsAlreadySaved); $i++){
				if($warehouseItemsAlreadySaved[$i] == $warehouse){
					$itemsAlreadySavedInWarehouse[] = $itemsAlreadySaved[$i];
				}	
			}
			
			$items = $this->SalSale->SalDetail->InvItem->find('list', array(
				'conditions'=>array(
					'NOT'=>array('InvItem.id'=>$itemsAlreadySavedInWarehouse)
				),
				'recursive'=>-1,
				'order'=>array('InvItem.code')
			));
			
			$item = key($items);
			//////////////////////CAMBIAR POR EL ALGORITMO QUE SACA EL PRECIO PRORRATEADO////////////////
			$priceDirty = $this->SalSale->SalDetail->InvItem->InvPrice->find('first', array(
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
			$stocks = $this->_get_stocks($item, $warehouse);
			$stock = $this->_find_item_stock($stocks, $item);
			$this->set(compact('items', 'price', 'stock'));
		}
	}
	
	private function _get_price($itemId, $date, $type, $currType){
		$this->loadModel('InvPrice');
		//To change UK date format to US date format
		$bits = explode('/',$date);
		$date = $bits[1].'/'.$bits[0].'/'.$bits[2];
		//To get id of the price type
		$typeId = $this->InvPrice->InvPriceType->find('list', array(
			'fields'=>array(
				'InvPriceType.id'
				),
			'conditions'=>array(
				'InvPriceType.name'=>$type
				)
			));
		//To get the history of prices
		$prices = $this->InvPrice->find('list', array(
			'fields'=>array(
				'InvPrice.id',
				'InvPrice.date'
				),
			'conditions'=>array(
				'InvPrice.inv_item_id'=>$itemId,
				'InvPrice.inv_price_type_id'=>$typeId//'InvPrice.inv_price_type_id'=>1
				)
			));
		if($prices <> null){
			//To get the list of subtracted dates in unix time format
			foreach($prices as $id => $day){
				$interval[$id] = abs(strtotime($date) - strtotime($day));
			}
			asort($interval);
			$closest = key($interval);
			//To get the price
			if($currType == 'dolar'){
				$priceField = $this->InvPrice->find('first', array(
				'fields'=>array(
					'InvPrice.ex_price'
					),
				'conditions'=>array(
					'InvPrice.id'=>$closest
					)
				));
				$price = $priceField['InvPrice']['ex_price'];
			}else{
				$priceField = $this->InvPrice->find('first', array(
				'fields'=>array(
					'InvPrice.price'
					),
				'conditions'=>array(
					'InvPrice.id'=>$closest
					)
				));
				$price = $priceField['InvPrice']['price'];
			}
			if ($price == null){
				$price = 0;
			}
		}else{
			$price = 0;
		}
		//debug($price);
		return $price;
	}
	
	
	public function ajax_save_movement(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////START - RECIEVE AJAX////////////////////////////////////////////////////////
			//For making algorithm
			$ACTION = $this->request->data['ACTION'];
			$OPERATION= $this->request->data['OPERATION'];
			$STATE = $this->request->data['STATE'];//also for Movement
			$OPERATION3 = $OPERATION;
			$OPERATION4 = $OPERATION;
			//Sale
			$purchaseId = $this->request->data['purchaseId'];
			$movementDocCode = $this->request->data['movementDocCode'];
			$movementCode = $this->request->data['movementCode'];
			$noteCode = $this->request->data['noteCode'];
			$date = $this->request->data['date'];
			$employee = $this->request->data['employee'];
			$taxNumber = $this->request->data['taxNumber'];
			$admProfileId = $this->request->data['salesman'];
			///////////////////////////////////////////////////////
			$this->loadModel('AdmUser');
			$admUserId = $this->AdmUser->AdmProfile->find('list', array(
			'fields'=>array('AdmProfile.adm_user_id'),
			'conditions'=>array('AdmProfile.id'=>$admProfileId)
			));
			
			$salesman = key($this->AdmUser->find('list', array(
			'conditions'=>array('AdmUser.id'=>$admUserId)
			)));
			///////////////////////////////////////////////////////
			$description = $this->request->data['description'];
			$exRate = $this->request->data['exRate'];
			//Sale Details
			$warehouseId = $this->request->data['warehouseId'];
			$itemId = $this->request->data['itemId'];
			$salePrice = $this->request->data['salePrice'];
			$quantity = $this->request->data['quantity'];
//			$cifPrice =  $this->_get_price($itemId, $date, 'CIF', 'bs');//$this->request->data['cifPrice'];
//			$exCifPrice = $this->_get_price($itemId, $date, 'CIF', 'dolar');//$this->request->data['exCifPrice'];
			//For prices IF DETAILS ARE PASSED / IF ACTION ADD OR EDIT
//			$exFobPrice =  $this->_get_price($itemId, $date, 'FOB', 'dolar');
//			$fobPrice =  $exFobPrice * $exRate;//$this->_get_price($itemId, $date, 'FOB', 'bs');
			$exSalePrice = $salePrice / $exRate;
			if (($ACTION == 'save_invoice' && $OPERATION == 'ADD_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_PAY')) {
//				$dateId = $this->request->data['dateId'];
				$payDate = $this->request->data['payDate'];
				$payAmount = $this->request->data['payAmount'];
				$payDescription = $this->request->data['payDescription'];
			}
			//For validate before approve OUT or cancelled IN
			$arrayForValidate = array();
			if(isset($this->request->data['arrayForValidate'])){$arrayForValidate = $this->request->data['arrayForValidate'];}
			//Internal variables
			$error=0;
			$movementDocCode3 = '';
			$movementDocCode4 = '';
			////////////////////////////////////////////END - RECIEVE AJAX////////////////////////////////////////////////////////
			
			////////////////////////////////////////////////START - SET DATA/////////////////////////////////////////////////////
			$arrayMovement['note_code']=$noteCode;
			$arrayMovement['date']=$date;
			$arrayMovement['sal_employee_id']=$employee;
			$arrayMovement['sal_tax_number_id']=$taxNumber;
			$arrayMovement['salesman_id']=$salesman;
			$arrayMovement['description']=$description;
			$arrayMovement['ex_rate']=$exRate;
			$arrayMovement['lc_state']=$STATE;
			if ($ACTION == 'save_order'){
				//header for invoice
				$arrayMovement2['note_code']=$noteCode;
				$arrayMovement2['date']=$date;
				$arrayMovement2['sal_employee_id']=$employee;
				$arrayMovement2['sal_tax_number_id']=$taxNumber;
				$arrayMovement2['salesman_id']=$salesman;
				$arrayMovement2['description']=$description;
				$arrayMovement2['ex_rate']=$exRate;
				//header for movement
				$arrayMovement3['date']=$date;
				$arrayMovement3['inv_warehouse_id']=$warehouseId;
				$arrayMovement3['inv_movement_type_id']=2;
				$arrayMovement3['description']=$description;
				
				if ($STATE == 'NOTE_APPROVED') {
					$arrayMovement2['lc_state']='SINVOICE_PENDANT';
				}elseif ($STATE == 'NOTE_PENDANT') {
					$arrayMovement2['lc_state']='DRAFT';
					$arrayMovement3['lc_state']='DRAFT';
					$arrayMovement4['lc_state']='DRAFT';
//					debug($arrayMovement3['lc_state']);
				}
			}elseif(($ACTION == 'save_invoice' && $OPERATION == 'ADD_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_PAY')){
				$arrayPayDetails = array('sal_payment_type_id'=>1, 
										'date'=>$payDate,
										//'description'=>"'$payDescription'",
										'description'=>$payDescription,
										'amount'=>$payAmount, 'ex_amount'=>($payAmount / $exRate)
										);
			}elseif ($ACTION == 'save_invoice') {
				//header for movement
				$arrayMovement3['date']=$date;
				$arrayMovement3['inv_warehouse_id']=$warehouseId;
				$arrayMovement3['inv_movement_type_id']=2;
				$arrayMovement3['description']=$description;
				if ($STATE == 'SINVOICE_PENDANT') {
					$arrayMovement3['lc_state']='PENDANT';//ESTO ESTA SOBREESCRITO POR LO Q DIGA $arrayMovement5
					$arrayMovement4['lc_state']='PENDANT';//ESTO ESTA SOBREESCRITO POR LO Q DIGA $arrayMovement5
				}
			}			
			$arrayMovementDetails = array('inv_warehouse_id'=>$warehouseId, 
										'inv_item_id'=>$itemId,
										'sale_price'=>$salePrice, 'ex_sale_price'=>$exSalePrice,
										'quantity'=>$quantity, 
										/*'cif_price'=>$cifPrice, 'ex_cif_price'=>$exCifPrice, 
										'fob_price'=>$fobPrice, 'ex_fob_price'=>$exFobPrice*/);
			if ($ACTION == 'save_order'){
				$stocks = $this->_get_stocks($itemId, $warehouseId);
				$stock = $this->_find_item_stock($stocks, $itemId);
				$arrayMovement3['type']=1;

				$arrayMovement4['date']=$date;
				$arrayMovement4['inv_warehouse_id']=$warehouseId;
				$arrayMovement4['inv_movement_type_id']=2;
				$arrayMovement4['description']=$description;
				$arrayMovement4['type']=2;
				$surplus = $quantity - $stock;
				if($quantity > $stock){
					$arrayMovementDetails3 = array('inv_item_id'=>$itemId, 'quantity'=>$stock);
					$OPERATION4 = 'ADD';
				}else{
					$arrayMovementDetails3 = array('inv_item_id'=>$itemId, 'quantity'=>$quantity);
				}	
				$arrayMovementDetails4 = array('inv_item_id'=>$itemId, 'quantity'=>$surplus);
			}elseif ($ACTION == 'save_invoice' && $OPERATION != 'ADD_PAY' && $OPERATION != 'EDIT_PAY' && $OPERATION != 'DELETE_PAY') {
				$stocks = $this->_get_stocks($itemId, $warehouseId);
				$stock = $this->_find_item_stock($stocks, $itemId);
				$arrayMovement3['type']=1;
				$arrayMovement4['date']=$date;
				$arrayMovement4['inv_warehouse_id']=$warehouseId;
				$arrayMovement4['inv_movement_type_id']=2;
				$arrayMovement4['description']=$description;
				$arrayMovement4['type']=2;
				$surplus = $quantity - $stock;
				if($quantity > $stock){
					$arrayMovementDetails3 = array('inv_item_id'=>$itemId, 'quantity'=>$stock);
					$OPERATION4 = 'ADD';
				}else{
					$arrayMovementDetails3 = array('inv_item_id'=>$itemId, 'quantity'=>$quantity);
				}	
				$arrayMovementDetails4 = array('inv_item_id'=>$itemId, 'quantity'=>$surplus);
			}
			//INSERT OR UPDATE
			if($purchaseId == ''){//INSERT
				switch ($ACTION) {
					case 'save_order':
						//SALES NOTE
						$movementCode = $this->_generate_code('VEN');
						$movementDocCode = $this->_generate_doc_code('NOT');
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
						$arrayMovement4['document_code'] = $movementCode;
						$arrayMovement4['code'] = $movementDocCode2;
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
					$arrayMovement3['id'] = $this->_get_doc_id(null, $movementCode, 1, $warehouseId);
					if($arrayMovement3['id'] === null){
						$arrayMovement3['document_code'] = $movementCode;
						$arrayMovement3['code'] = 'NO';
					}
					//movement id type 2(NO hay stock)
					$arrayMovement4['id'] = $this->_get_doc_id(null, $movementCode, 2, $warehouseId);
					if(($arrayMovement4['id'] === null) && ($quantity > $stock)){
						$arrayMovement4['document_code'] = $movementCode;
						$arrayMovement4['code'] = 'NO';
					}
					if($quantity > $stock){//CHEKAR BIEN ESTO, CREO Q YA NO VA!!!
						$arrayMovement4['document_code'] = $movementCode;
						$arrayMovement4['code'] = 'NO';
					}
//					REVISAR SI ESTO SERVIA PARA ALGO |||||| REVISAR SI ESTO SERVIA PARA ALGO ||||| REVISAR SI ESTO SERVIA PARA ALGO  					
//					if(($arrayMovement4['id'] <> null) && ($quantity <= $stock)){
//						$OPERATION4 = 'DELETE';
//					}
					if ($STATE == 'NOTE_APPROVED') {
						//FOR INVOICE
						$movementDocCode2 = $this->_generate_doc_code('VFA');
						$arrayMovement2['doc_code'] = $movementDocCode2;
					}
				}elseif ($ACTION == 'save_invoice' && $OPERATION != 'ADD_PAY' && $OPERATION != 'EDIT_PAY' && $OPERATION != 'DELETE_PAY') {
					//movement id type 1(hay stock)
					$arrayMovement3['id'] = $this->_get_doc_id(null, $movementCode, 1, $warehouseId);
					if($arrayMovement3['id'] === null){//SI NO HAY EL DOCUMENTO (CON STOCK) SE CREA
						$arrayMovement3['document_code'] = $movementCode;
						$movementDocCode3 = $this->_generate_movement_code('SAL',null);
						$arrayMovement3['code'] = $movementDocCode3;//'NO';
					}
					//movement id type 2(NO hay stock)
					$arrayMovement4['id'] = $this->_get_doc_id(null, $movementCode, 2, $warehouseId);
					if(($arrayMovement4['id'] === null) && ($quantity > $stock)){//SI NO HAY EL DOCUMENTO (SIN STOCK), Y LA CANTIDAD SOBREPASA EL STOCK SE CREA
						$arrayMovement4['document_code'] = $movementCode;
						$movementDocCode4 = $this->_generate_movement_code('SAL',null);
						$arrayMovement4['code'] = $movementDocCode4;//'NO';
					}
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
				if($movementDocCode4 == 'error'){$error++;}
			}
			//-------------------------FOR DELETING HEAD ON MOVEMENTS RELATED ON save_order--------------------------------
//			if(($ACTION == 'save_order' && $OPERATION3 == 'DELETE') || ($ACTION == 'save_order' && $OPERATION4 == 'DELETE')){	
			$arrayMovement6 = null;
			if(($ACTION == 'save_order' && $OPERATION3 == 'DELETE' && $OPERATION4 == 'DELETE')){//TOMANDO EN CUENTA QUE SIEMPRE QUE $OPERATION3 == 'DELETE' TAMBIEN $OPERATION4 == 'DELETE' Y VICEVERSA
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
				if (($arrayMovement4['id'] != null)||($arrayMovementDetails4['inv_item_id'] != null)){
					$rest4 = $this->InvMovement->InvMovementDetail->find('count', array(
						'conditions'=>array(
							'NOT'=>array(
								'AND'=>array(
									'InvMovementDetail.inv_movement_id'=>$arrayMovement4['id']
									,'InvMovementDetail.inv_item_id'=>$arrayMovementDetails4['inv_item_id']
									)
								)
							,'InvMovementDetail.inv_movement_id'=>$arrayMovement4['id']
							),
						'recursive'=>0
					));
				}	
				if(($rest3 == 0) && ($rest4 == 0) && ($arrayMovement3['id'] != null) && ($arrayMovement4['id'] != null)){
					$arrayMovement6 = array(
						array('InvMovement.id' => array($arrayMovement3['id'],$arrayMovement4['id']))
					);
				}elseif(($rest3 == 0) && ($arrayMovement3['id'] != null)){
					$arrayMovement6 = array(
						array('InvMovement.id' => $arrayMovement3['id'])
					);
				}elseif(($rest4 == 0) && ($arrayMovement4['id'] != null)){
					$arrayMovement6 = array(
						 array('InvMovement.id' => $arrayMovement4['id'])
					);
				}
//				else{
//					$arrayMovement6 = null;
//				}
			}
			//---------------------------FOR DELETING HEAD ON MOVEMENTS RELATED ON save_order------------------------------
//			-------------------------FOR UPDATING HEAD ON DELETED MOVEMENTS ON save_invoice--------------------------------
//			if(($ACTION == 'save_invoice' && $OPERATION3 == 'DELETE') || ($ACTION == 'save_invoice' && $OPERATION4 == 'DELETE')){	
			$draftId3 = null;
			$draftId4 = null;
			if(($ACTION == 'save_invoice' && $OPERATION3 == 'DELETE' && $OPERATION4 == 'DELETE')){//TOMANDO EN CUENTA QUE SIEMPRE QUE $OPERATION3 == 'DELETE' TAMBIEN $OPERATION4 == 'DELETE' Y VICEVERSA
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
				if (($arrayMovement4['id'] != null)||($arrayMovementDetails4['inv_item_id'] != null)){
					$rest4 = $this->InvMovement->InvMovementDetail->find('count', array(
						'conditions'=>array(
							'NOT'=>array(
								'AND'=>array(
									'InvMovementDetail.inv_movement_id'=>$arrayMovement4['id']
									,'InvMovementDetail.inv_item_id'=>$arrayMovementDetails4['inv_item_id']
									)
								)
							,'InvMovementDetail.inv_movement_id'=>$arrayMovement4['id']
							),
						'recursive'=>0
					));
				}	
				if(($rest3 == 0) && ($rest4 == 0) && ($arrayMovement3['id'] != null) && ($arrayMovement4['id'] != null)){
					$draftId3 = $arrayMovement3['id'];
					$draftId4 = $arrayMovement4['id'];
//					echo "<br>1<br>";
//					debug($draftId3);
//					debug($draftId4);
				}elseif(($rest3 == 0) && ($arrayMovement3['id'] != null)){
					$draftId3 = $arrayMovement3['id'];
//					$draftId4 = null;
//					echo "<br>2<br>";
//					debug($draftId3);
				}elseif(($rest4 == 0) && ($arrayMovement4['id'] != null)){
					$draftId4 = $arrayMovement4['id'];
//					$draftId3 = null;
//					echo "<br>3<br>";
//					debug($draftId4);
				}
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
			if(($arrayMovement5 <> null)&&($STATE == 'NOTE_CANCELLED')){
				for($i=0;$i<count($arrayMovement5);$i++){
					$arrayMovement5[$i]['InvMovement']['lc_state'] = 'DRAFT';
				}
			}elseif(($arrayMovement5 <> null)&&($STATE == 'NOTE_APPROVED')) {
				for($i=0;$i<count($arrayMovement5);$i++){
					$movementDocCode5 = $this->_generate_movement_code('SAL','inc');
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
					if(($ACTION == 'save_invoice' && $OPERATION3 == 'DELETE') || ($ACTION == 'save_invoice' && $OPERATION4 == 'DELETE')){		
						if($arrayMovement5[$i]['InvMovement']['id'] === $draftId3){
							$arrayMovement5[$i]['InvMovement']['lc_state']='DRAFT';
						}
						if($arrayMovement5[$i]['InvMovement']['id'] === $draftId4){
							$arrayMovement5[$i]['InvMovement']['lc_state']='DRAFT';
						}
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
			$dataMovement = array('SalSale'=>$arrayMovement);
			if ($ACTION == 'save_order'){
				$this->loadModel('InvMovement');
				//for invoice
				$dataMovement2 = array('SalSale'=>$arrayMovement2);
				//for movement
				$dataMovement3 = array('InvMovement'=>$arrayMovement3);
				$dataMovementDetail3 = array('InvMovementDetail'=> $arrayMovementDetails3);
				$dataMovement4 = array('InvMovement'=>$arrayMovement4);
				$dataMovementDetail4 = array('InvMovementDetail'=> $arrayMovementDetails4);
				if($arrayMovement5 <> null){
					$dataMovement5 = $arrayMovement5;
				}	
				if((($ACTION == 'save_order' && $OPERATION3 == 'DELETE' && $arrayMovement6 <> null) || ($ACTION == 'save_order' && $OPERATION4 == 'DELETE' && $arrayMovement6 <> null)) ){
					$dataMovement6 = $arrayMovement6;
				}	
				$dataPayDetail = null;
			}elseif (($ACTION == 'save_invoice' && $OPERATION == 'ADD_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_PAY')) {
				$dataPayDetail = array('SalPayment'=> $arrayPayDetails);
				if($arrayMovement5 <> null){
					$dataMovement5 = $arrayMovement5;
				}	
			}elseif ($ACTION == 'save_invoice') {
				$this->loadModel('InvMovement');
				//for movement
				$dataMovement3 = array('InvMovement'=>$arrayMovement3);
				$dataMovementDetail3 = array('InvMovementDetail'=> $arrayMovementDetails3);
				$dataMovement4 = array('InvMovement'=>$arrayMovement4);
				$dataMovementDetail4 = array('InvMovementDetail'=> $arrayMovementDetails4);
				if($arrayMovement5 <> null){
					$dataMovement5 = $arrayMovement5;
				}	
				if((($ACTION == 'save_order' && $OPERATION3 == 'DELETE' && $arrayMovement6 <> null) || ($ACTION == 'save_order' && $OPERATION4 == 'DELETE' && $arrayMovement6 <> null)) ){
					$dataMovement6 = $arrayMovement6;
				}	
				$dataPayDetail = null;
			}
			$dataMovementDetail = array('SalDetail'=> $arrayMovementDetails);
			
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
					if($validation['error'] == 0){
							$res = $this->SalSale->saveMovement($dataMovement, $dataMovementDetail, $OPERATION, $ACTION, $movementDocCode, $dataPayDetail);
							if ($ACTION == 'save_order'){
								$res2 = $this->SalSale->saveMovement($dataMovement2, $dataMovementDetail, $OPERATION, $ACTION, $movementDocCode, null);
								if(($stock != 0)||(($OPERATION3 == 'DELETE')&&($arrayMovement3['id']!==null))){
									//used to insert/update type 1 detail movements 
									//used to delete movement details type 1
//									echo "ini3";
									$res3 = $this->InvMovement->saveMovement($dataMovement3, $dataMovementDetail3, $OPERATION3, 'save_in', null, $movementDocCode3);
//									echo "fin3";
								}
								if(($quantity > $stock)||(($OPERATION4 == 'DELETE')&&($arrayMovement4['id']!==null))){	//($quantity > $stock) doesn't work when stock changes
									//used to insert/update type 2 detail movements									
									//used to delete movement details type 2
//									echo "ini4";
									$res4 = $this->InvMovement->saveMovement($dataMovement4, $dataMovementDetail4, $OPERATION4, 'save_in', null, $movementDocCode4);
//									echo "fin4";
								}	
								if($arrayMovement5 <> null){
									//used to update movements head
//									echo "ini5";
									$res5 = $this->InvMovement->saveMovement($dataMovement5, null, 'UPDATEHEAD', null, null, null);
//									echo "fin5";
								}
								if((($ACTION == 'save_order' && $OPERATION3 == 'DELETE' && $arrayMovement6 <> null) || ($ACTION == 'save_order' && $OPERATION4 == 'DELETE' && $arrayMovement6 <> null)) ){
//									echo "ini6";
									$res6 = $this->InvMovement->saveMovement($dataMovement6, null, 'DELETEHEAD', null, null, null);
//									echo "fin6";
								}
								
							}elseif ($ACTION == 'save_invoice' && $OPERATION != 'ADD_PAY' && $OPERATION != 'EDIT_PAY' && $OPERATION != 'DELETE_PAY') {
								if(($stock != 0)||(($OPERATION3 == 'DELETE')&&($arrayMovement3['id']!==null))){
									//used to insert/update type 1 detail movements 
									//used to delete movement details type 1
//									echo "ini3";
									$res3 = $this->InvMovement->saveMovement($dataMovement3, $dataMovementDetail3, $OPERATION3, 'save_in', null, $movementDocCode3);
//									echo "fin3";
								}
								if(($quantity > $stock)||(($OPERATION4 == 'DELETE')&&($arrayMovement4['id']!==null))){	//($quantity > $stock) doesn't work when stock changes
									//used to insert/update type 2 detail movements									
									//used to delete movement details type 2
//									echo "ini4";
									$res4 = $this->InvMovement->saveMovement($dataMovement4, $dataMovementDetail4, $OPERATION4, 'save_in', null, $movementDocCode4);
//									echo "fin4";
								}	
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
							}elseif(($ACTION == 'save_invoice' && $OPERATION == 'ADD_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_PAY')){
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
			$invoiceId = $this->SalSale->find('list', array(
				'fields'=>array('SalSale.id'),
				'conditions'=>array(
					'SalSale.code'=>$movementCode,
					"SalSale.id !="=>$purchaseId
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

			$this->loadModel('AdmUser');
			
			$date = $this->request->data['date'];
			$employee = $this->request->data['employee'];
			$taxNumber = $this->request->data['taxNumber'];
			$admProfileId = $this->request->data['salesman'];
			$description = $this->request->data['description'];
			$exRate = $this->request->data['exRate'];
			$note_code = $this->request->data['note_code'];
			
			$admUserId = $this->AdmUser->AdmProfile->find('list', array(
			'fields'=>array('AdmProfile.adm_user_id'),
			'conditions'=>array('AdmProfile.id'=>$admProfileId)
			));
			
			$salesman = key($this->AdmUser->find('list', array(
			'conditions'=>array('AdmUser.id'=>$admUserId)
			)));
			////////////////////////////////////////////FIN-CAPTURAR AJAX////////////////////////////////////////////////////////

			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'sal_employee_id'=>$employee,'sal_tax_number_id'=>$taxNumber,'salesman_id'=>$salesman,'note_code'=>$note_code,'ex_rate'=>$exRate,'description'=>$description);
					
			$movementCode = '';
			$movementDocCode = '';
			if($purchaseId <> ''){//update
				$arrayMovement['id'] = $purchaseId;
			}else{//insert
				$movementCode = $this->_generate_code('VEN');
				$movementDocCode = $this->_generate_doc_code('NOT');
				$arrayMovement['lc_state'] = 'NOTE_PENDANT';
				$arrayMovement['lc_transaction'] = 'CREATE';
				$arrayMovement['code'] = $movementCode;
	$arrayMovement['doc_code'] = $movementDocCode;
	$arrayMovement['sal_employee_id'] = $employee;
	$arrayMovement['sal_tax_number_id'] = $taxNumber;
	$arrayMovement['salesman_id'] = $salesman;
			}
			
			$data = array('SalSale'=>$arrayMovement, 'SalDetail'=>$arrayItemsDetails);
		//	print_r($this->request->data);
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
			

			////////////////////////////////////////////INICIO-SAVE////////////////////////////////////////////////////////
			if($purchaseId <> ''){//update
				if($this->SalSale->SalDetail->deleteAll(array('SalDetail.sal_sale_id'=>$purchaseId))){
		//		
					if($this->SalSale->saveAssociated($data)){
						
//						$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $supplier);
						echo 'modificado|'/*.$strItemsStock*/;
						//print_r($data);
					}
				}
			}else{//insert
		//		print_r($data);
				if($this->SalSale->saveAssociated($data)){
//					$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $supplier);
					$purchaseIdInserted = $this->SalSale->id;
				//	print_r($data);
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
//			$arrayCostsDetails = $this->request->data['arrayCostsDetails'];
			$arrayPaysDetails = $this->request->data['arrayPaysDetails'];
			$purchaseId = $this->request->data['purchaseId'];

			$this->loadModel('AdmUser');
			
			$date = $this->request->data['date'];
//			$supplier = $this->request->data['supplier'];
			$employee = $this->request->data['employee'];
			$taxNumber = $this->request->data['taxNumber'];
			$admProfileId = $this->request->data['salesman'];
			$exRate = $this->request->data['exRate'];
			$description = $this->request->data['description'];
			$note_code = $this->request->data['note_code'];
			
			$admUserId = $this->AdmUser->AdmProfile->find('list', array(
			'fields'=>array('AdmProfile.adm_user_id'),
			'conditions'=>array('AdmProfile.id'=>$admProfileId)
			));
			
			$salesman = key($this->AdmUser->find('list', array(
			'conditions'=>array('AdmUser.id'=>$admUserId)
			)));
			////////////////////////////////////////////FIN-CAPTURAR AJAX////////////////////////////////////////////////////////
			
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'sal_employee_id'=>$employee,'sal_tax_number_id'=>$taxNumber,'salesman_id'=>$salesman,'note_code'=>$note_code,/*'inv_warehouse_id'=>$warehouse, 'inv_movement_type_id'=>$movementType,*/ 'ex_rate'=>$exRate,'description'=>$description);
			
			$movementCode = '';
			$movementDocCode = '';
			if($purchaseId <> ''){//update
				$arrayMovement['id'] = $purchaseId;
			}
			
			//data sin costos ni pagos
			$data = array('SalSale'=>$arrayMovement, 'SalDetail'=>$arrayItemsDetails);
			//data con costos
	//		$data2 = array('PurPurchase'=>$arrayMovement, 'PurDetail'=>$arrayItemsDetails, 'PurPrice'=>$arrayCostsDetails);
			//data con pagos
			$data3 = array('SalSale'=>$arrayMovement, 'SalDetail'=>$arrayItemsDetails, 'SalPayment'=>$arrayPaysDetails);
			//data con pagos y costos
	//		$data4 = array('PurPurchase'=>$arrayMovement, 'PurDetail'=>$arrayItemsDetails, 'PurPrice'=>$arrayCostsDetails, 'PurPayment'=>$arrayPaysDetails);
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
			//print_r($data4);

			////////////////////////////////////////////INICIO-SAVE////////////////////////////////////////////////////////
			if($purchaseId <> ''){
			/*	if(($arrayCostsDetails <> array(0)) && ($arrayPaysDetails <> array(0)) ){
					if(($this->PurPurchase->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPrice->deleteAll(array('PurPrice.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPayment->deleteAll(array('PurPayment.pur_purchase_id'=>$purchaseId))) ){
			
						if($this->PurPurchase->saveAssociated($data4)){
							echo 'modificado| cost pay d&&d&&d';
						}
					}
				}elseif ($arrayCostsDetails <> array(0)) {
					if(($this->PurPurchase->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPrice->deleteAll(array('PurPrice.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPayment->deleteAll(array('PurPayment.pur_purchase_id'=>$purchaseId))) ){
				
						if($this->PurPurchase->saveAssociated($data2)){
							echo 'modificado| cost d&&d&&d';
						}
					}
				}else*/if ($arrayPaysDetails <> array(0)) {
					if(($this->SalSale->SalDetail->deleteAll(array('SalDetail.sal_sale_id'=>$purchaseId)))&&($this->SalSale->SalPayment->deleteAll(array('SalPayment.sal_sale_id'=>$purchaseId))) ){
			//		print_r($data3);
						if($this->SalSale->saveAssociated($data3)){
							echo 'modificado| pay d&&d&&d';
						}
					}
				}else{
					if(($this->SalSale->SalDetail->deleteAll(array('SalDetail.sal_sale_id'=>$purchaseId)))&&($this->SalSale->SalPayment->deleteAll(array('SalPayment.sal_sale_id'=>$purchaseId))) ){
				//		print_r($data);
						if($this->SalSale->saveAssociated($data)){
							echo 'modificado| d&&d&&d';
						}
					}
				}
				
			}else{//insert
				if($this->SalSale->saveAssociated($data3)){
					$purchaseIdInserted = $this->SalSale->id;
						echo 'insertado|'/*.$strItemsStock.'|'*/.$movementDocCode.'|'.$purchaseIdInserted.'|'.$movementCode;
				}
			}
			////////////////////////////////////////////FIN-SAVE////////////////////////////////////////////////////////
		
		}
	}
	
	public function ajax_change_state_approved_movement_in(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////INICIO-CAPTURAR AJAX/////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$purchaseId = $this->request->data['purchaseId'];
	
			$this->loadModel('AdmUser');
			
			$date = $this->request->data['date'];
			$employee = $this->request->data['employee'];
			$taxNumber = $this->request->data['taxNumber'];
			$admProfileId = $this->request->data['salesman'];
			$description = $this->request->data['description'];
			$exRate = $this->request->data['exRate'];
			$note_code = $this->request->data['note_code'];
	
			$admUserId = $this->AdmUser->AdmProfile->find('list', array(
			'fields'=>array('AdmProfile.adm_user_id'),
			'conditions'=>array('AdmProfile.id'=>$admProfileId)
			));
			
			$salesman = key($this->AdmUser->find('list', array(
			'conditions'=>array('AdmUser.id'=>$admUserId)
			)));
			
			$generalCode = $this->request->data['genericCode'];
			////////////////////////////////////////////FIN-CAPTURAR AJAX/////////////////////////////////////////////////////
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'sal_employee_id'=>$employee,'sal_tax_number_id'=>$taxNumber,'salesman_id'=>$salesman, 'description'=>$description, 'note_code'=>$note_code, 'ex_rate'=>$exRate);
			$arrayMovement['lc_state'] = 'NOTE_APPROVED';
			$arrayMovement['id'] = $purchaseId;
			
			$arrayInvoice = array('date'=>$date, 'sal_employee_id'=>$employee,'sal_tax_number_id'=>$taxNumber,'salesman_id'=>$salesman, 'description'=>$description, 'note_code'=>$note_code, 'ex_rate'=>$exRate);		
			$movementDocCode = $this->_generate_doc_code('FAC');
			$arrayInvoice['lc_state'] = 'SINVOICE_PENDANT';
			$arrayInvoice['lc_transaction'] = 'CREATE';
			$arrayInvoice['code'] = $generalCode;
			$arrayInvoice['doc_code'] = $movementDocCode;
//			$arrayInvoice['inv_supplier_id'] = $supplier;

			
			$data = array('SalSale'=>$arrayMovement, 'SalDetail'=>$arrayItemsDetails);
			$dataInv = array('SalSale'=>$arrayInvoice, 'SalDetail'=>$arrayItemsDetails);
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////

			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
			
			//print_r($code);
//			print_r($data);
//			print_r($dataInv);
			////////////////////////////////////////////INICIO-SAVE////////////////////////////////////////////////////////
			if($purchaseId <> ''){//update
				if($this->SalSale->SalDetail->deleteAll(array('SalDetail.sal_sale_id'=>$purchaseId))){
					if(($this->SalSale->saveAssociated($data))&&($this->SalSale->saveAssociated($dataInv))){
						
						//////////////////////////////////////////////////////////

				//////////////////////////////////////////////////////////////////////		
						echo 'aprobado|';
						//print_r($data);
			//print_r($dataInv);
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
			//$arrayCostsDetails = $this->request->data['arrayCostsDetails'];
			$arrayPaysDetails = $this->request->data['arrayPaysDetails'];

			$purchaseId = $this->request->data['purchaseId'];
			$this->loadModel('AdmUser');
			
			$date = $this->request->data['date'];
//			$supplier = $this->request->data['supplier'];
			$employee = $this->request->data['employee'];
			$taxNumber = $this->request->data['taxNumber'];
			$admProfileId = $this->request->data['salesman'];
			$exRate = $this->request->data['exRate'];
			$description = $this->request->data['description'];
			$note_code = $this->request->data['note_code'];
			
			$admUserId = $this->AdmUser->AdmProfile->find('list', array(
			'fields'=>array('AdmProfile.adm_user_id'),
			'conditions'=>array('AdmProfile.id'=>$admProfileId)
			));
			
			$salesman = key($this->AdmUser->find('list', array(
			'conditions'=>array('AdmUser.id'=>$admUserId)
			)));
			////////////////////////////////////////////FIN-CAPTURAR AJAX////////////////////////////////////////////////////////
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'sal_employee_id'=>$employee,'sal_tax_number_id'=>$taxNumber,'salesman_id'=>$salesman,'note_code'=>$note_code,/*'inv_warehouse_id'=>$warehouse, 'inv_movement_type_id'=>$movementType,*/ 'ex_rate'=>$exRate,'description'=>$description);
			$arrayMovement['lc_state'] = 'SINVOICE_APPROVED';
			$arrayMovement['id'] = $purchaseId;
			
//			//data sin costos ni pagos
			$data = array('SalSale'=>$arrayMovement, 'SalDetail'=>$arrayItemsDetails);
			//data con costos
	//		$data2 = array('PurPurchase'=>$arrayMovement, 'PurDetail'=>$arrayItemsDetails, 'PurPrice'=>$arrayCostsDetails);
			//data con pagos
			$data3 = array('SalSale'=>$arrayMovement, 'SalDetail'=>$arrayItemsDetails, 'SalPayment'=>$arrayPaysDetails);
			//data con pagos y costos
	//		$data4 = array('PurPurchase'=>$arrayMovement, 'PurDetail'=>$arrayItemsDetails, 'PurPrice'=>$arrayCostsDetails, 'PurPayment'=>$arrayPaysDetails);
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
			//print_r($code);
//			print_r($data);
//			print_r($dataInv);
			////////////////////////////////////////////INICIO-SAVE////////////////////////////////////////////////////////
			if($purchaseId <> ''){
			/*	if(($arrayCostsDetails <> array(0)) && ($arrayPaysDetails <> array(0)) ){
					if(($this->PurPurchase->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPrice->deleteAll(array('PurPrice.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPayment->deleteAll(array('PurPayment.pur_purchase_id'=>$purchaseId))) ){
			
						if($this->PurPurchase->saveAssociated($data4)){
							echo 'modificado| cost pay d&&d&&d';
						}
					}
				}elseif ($arrayCostsDetails <> array(0)) {
					if(($this->PurPurchase->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPrice->deleteAll(array('PurPrice.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPayment->deleteAll(array('PurPayment.pur_purchase_id'=>$purchaseId))) ){
				
						if($this->PurPurchase->saveAssociated($data2)){
							echo 'modificado| cost d&&d&&d';
						}
					}
				}else*/if ($arrayPaysDetails <> array(0)) {
					if(($this->SalSale->SalDetail->deleteAll(array('SalDetail.sal_sale_id'=>$purchaseId)))&&($this->SalSale->SalPayment->deleteAll(array('SalPayment.sal_sale_id'=>$purchaseId))) ){
			//		print_r($data3);
						if($this->SalSale->saveAssociated($data3)){
							echo 'aprobado| pay d&&d&&d';
						}
					}
				}else{
					if(($this->SalSale->SalDetail->deleteAll(array('SalDetail.sal_sale_id'=>$purchaseId)))&&($this->SalSale->SalPayment->deleteAll(array('SalPayment.sal_sale_id'=>$purchaseId))) ){
				//		print_r($data);
						if($this->SalSale->saveAssociated($data)){
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
				$data = array('id'=>$purchaseId, 'lc_state'=>'NOTE_CANCELLED');
				if($this->SalSale->save($data)){
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
				$data = array('id'=>$purchaseId, 'lc_state'=>'SINVOICE_CANCELLED');
				if($this->SalSale->save($data)){
//					$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
					echo 'cancelado|'/*.$strItemsStock*/;
				}
//			}else{
//				echo 'error|'.$error['itemsStocks'];
//			}
						
		}
	}
	
	public function ajax_logic_delete(){
		if($this->RequestHandler->isAjax()){
			$purchaseId = $this->request->data['purchaseId'];
			$type = $this->request->data['type'];	
			$genCode = $this->request->data['genCode'];
				if($this->SalSale->updateAll(array('SalSale.lc_state'=>"'$type'"), array('SalSale.id'=>$purchaseId)) 
						){
					echo 'success';
				}
				if($type === 'SINVOICE_LOGIC_DELETED'){
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
				$movements = $this->SalSale->find('count', array(
					'conditions'=>array('SalSale.lc_state'=>array('NOTE_PENDANT','NOTE_APPROVED','NOTE_CANCELLED','NOTE_LOGIC_DELETED'))
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
	
	private function _generate_doc_code($keyword){
		$period = $this->Session->read('Period.name');
		if($period <> ''){
			try{
				if ($keyword == 'NOT'){
					$movements = $this->SalSale->find('count', array(
						'conditions'=>array('SalSale.lc_state'=>array('NOTE_PENDANT','NOTE_APPROVED','NOTE_CANCELLED','NOTE_LOGIC_DELETED'))
					)); 
				}elseif ($keyword == 'VFA'){
					$movements = $this->SalSale->find('count', array(
						'conditions'=>array('SalSale.lc_state'=>array('SINVOICE_PENDANT','SINVOICE_APPROVED','SINVOICE_CANCELLED','SINVOICE_LOGIC_DELETED'))
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
			$pays = $this->SalSale->SalPayment->SalPaymentType->find('list', array(
					'fields'=>array('SalPaymentType.name'),
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
	
///////////////////////////////////////////*********************************************************************************///////////////	
	
	public function index() {
		$this->SalSale->recursive = 0;
		$this->set('salSales', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->SalSale->id = $id;
		if (!$this->SalSale->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sal sale')));
		}
		$this->set('salSale', $this->SalSale->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$date='';
		if ($this->request->is('post')) {
			$this->SalSale->create();
			if ($this->SalSale->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('sal sale')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('sal sale')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$salEmployees = $this->SalSale->SalEmployee->find('list',array(
			'fields' => array('SalEmployee.id','SalEmployee.full_name')
		));
		$salTaxNumbers = $this->SalSale->SalTaxNumber->find('list', array(
			'fields' => array('SalTaxNumber.id', 'SalTaxNumber.full_nit')
		));
		$this->set(compact('salEmployees', 'salTaxNumbers','date'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->SalSale->id = $id;
		if (!$this->SalSale->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sal sale')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SalSale->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('sal sale')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('sal sale')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->SalSale->read(null, $id);
		}
		$salEmployees = $this->SalSale->SalEmployee->find('list');
		$salTaxNumbers = $this->SalSale->SalTaxNumber->find('list');
		$this->set(compact('salEmployees', 'salTaxNumbers'));
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
		$this->SalSale->id = $id;
		if (!$this->SalSale->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sal sale')));
		}
		if ($this->SalSale->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('sal sale')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('sal sale')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
	
/*********************************************************************************************************************/
/***********************************************************************************************************************/
/*********************************************************************************************************************/
/***********************************************************************************************************************/
/*********************************************************************************************************************/
/***********************************************************************************************************************/
	
private function _find_stock($idItem, $idWarehouse){		
		$movementsIn = $this->_get_quantity_movements_item($idItem, $idWarehouse, 'entrada');
		$movementsOut = $this->_get_quantity_movements_item($idItem, $idWarehouse, 'salida');
		$add = array_sum($movementsIn);
		$sub = array_sum($movementsOut);
		$stock = $add - $sub;
		return $stock;
	}
	
	private function _get_quantity_movements_item($idItem, $idWarehouse, $status){
		//******************************************************************************//
		//unbind for perfomance InvItem 'cause it isn't needed
//		$this->InvMovement->InvMovementDetail->unbindModel(array(
//			'belongsTo' => array('InvItem')
//		));
//		//Add association for InvMovementType
		$this->SalSale->SalDetail->InvItem->InvMovementDetail->bindModel(array(
			'hasOne'=>array(
				'InvMovementType'=>array(
					'foreignKey'=>false,
					'conditions'=> array('InvMovement.inv_movement_type_id = InvMovementType.id')
				)
				
			)
		));
		//******************************************************************************//
		//Movements
//		$movs = $this->SalSale->SalDetail->InvItem->InvMovementDetail->InvMovement->find('all', array(	
//			'fields'=>array('InvMovement.inv_warehouse_id', 'InvMovement.lc_state'),
//			'conditions'=>array(
//				'InvMovement.inv_warehouse_id'=>$idWarehouse,
//				'InvMovementDetail.inv_item_id'=>$idItem,
//				'InvMovementType.status'=>$status,
//				'InvMovement.lc_state'=>'APPROVED',
//				)
//		));
		
	//	$movements = $this->InvMovement->InvMovementDetail->find('all', array(
		$movements = $this->SalSale->SalDetail->InvItem->InvMovementDetail->find('all', array(	
			'fields'=>array('InvMovementDetail.inv_movement_id', 'InvMovementDetail.quantity'),
			'conditions'=>array(
				'InvMovement.inv_warehouse_id'=>$idWarehouse,
				'InvMovementDetail.inv_item_id'=>$idItem,
				'InvMovementType.status'=>$status,
				'InvMovement.lc_state'=>'APPROVED',
				)
		));
		//Give format to nested array movements
		$movementsCleaned = $this->_clean_nested_arrays($movements);
		return $movementsCleaned;
	}
	
	private function _clean_nested_arrays($array){
		$clean = array();
		foreach ($array as $key => $value) {
			$clean[$key] = $value['InvMovementDetail']['quantity'];
		}
		return $clean;
	}	
}
