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
		$this->paginate = array(
			'conditions' => array(
				'SalSale.lc_state !='=>'ORDER_LOGIC_DELETED'
				,'SalSale.lc_state LIKE'=> '%ORDER%'
			),
			'order' => array('SalSale.id' => 'desc'),
			'limit' => 15
		);
		$this->SalSale->recursive = 0;
		$this->set('salSales', $this->paginate());
	}
	
	public function save_order(){
		$id = '';
		if(isset($this->passedArgs['id'])){
			$id = $this->passedArgs['id'];
		}

		$this->loadModel('AdmUser');
		
		$salAdmUsers = $this->AdmUser->AdmProfile->find('list');
	//	debug($salAdmUsers);
		array_unshift($salAdmUsers,"Sin Vendedor");
	
		$salCustomers = $this->SalSale->SalEmployee->SalCustomer->find('list'/*, array('conditions'=>array('SalCustomer.location'=>'COCHABAMBA'))*/);
		$customer = key($salCustomers);
		$salEmployees = $this->SalSale->SalEmployee->find('list', array('conditions'=>array('SalEmployee.sal_customer_id'=>$customer)));
	//	$taxNumber = key($salCustomers);
		$salTaxNumbers = $this->SalSale->SalTaxNumber->find('list', array('conditions'=>array('SalTaxNumber.sal_customer_id'=>$customer)));
			
		$this->SalSale->recursive = -1;
		$this->request->data = $this->SalSale->read(null, $id);
	//	$date='';
		$date=date('d/m/Y');
		$exRate = '8.00';
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
		array_unshift($salAdmUsers,"Sin Vendedor");
	
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
		if($id <> null){
			$date = date("d/m/Y", strtotime($this->request->data['SalSale']['date']));
			$salDetails = $this->_get_movements_details($id);
	//		$purPrices = $this->_get_costs_details($id);
	//		$salPayments = $this->_get_pays_details($id);
			$documentState =$this->request->data['SalSale']['lc_state'];
			$genericCode = $this->request->data['SalSale']['code'];
			//buscar el codigo del documento origen
			$originDocCode = $this->SalSale->find('first', array(
				'fields'=>array('SalSale.doc_code'),
				'conditions'=>array(
					'SalSale.code'=>$genericCode
					)
			));
			$originCode = $originDocCode['SalSale']['doc_code'];
			$employeeId = $this->request->data['SalSale']['sal_employee_id'];
			$customerId = $this->SalSale->SalEmployee->find('list', array('fields'=>array('SalEmployee.sal_customer_id'),'conditions'=>array('SalEmployee.id'=>$employeeId)));
			$salEmployees = $this->SalSale->SalEmployee->find('list', array('conditions'=>array('SalEmployee.sal_customer_id'=>$customerId)));
			$salTaxNumbers = $this->SalSale->SalTaxNumber->find('list', array('conditions'=>array('SalTaxNumber.sal_customer_id'=>$customerId)));		
			
			$admProfileId = $this->request->data['SalSale']['salesman_id'];
			$admUserId = $this->AdmUser->AdmProfile->find('list', array('fields'=>array('AdmProfile.id'),'conditions'=>array('AdmProfile.adm_user_id'=>$admProfileId)));
		}
		
			
		$this->set(compact('salCustomers','customerId', 'salTaxNumbers', 'salEmployees','employeeId', 'salAdmUsers', 'admUserId','id', 'date', 'salDetails', 'salPayments', 'documentState', 'genericCode', 'originCode'));
//debug($this->request->data);
	}
	
	public function _get_movements_details($idMovement){
		$movementDetails = $this->SalSale->SalDetail->find('all', array(
			'conditions'=>array(
				'SalDetail.sal_sale_id'=>$idMovement
				),																									                             /*REVISAR ESTO V*/
			'fields'=>array('InvItem.name', 'InvItem.code', 'SalDetail.price', 'SalDetail.quantity','SalDetail.inv_warehouse_id', 'InvItem.id', 'InvWarehouse.name','InvWarehouse.id', 'InvItem.id'/*, 'PurPurchase.inv_supplier_id','InvPrice.price'*/)
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
				'price'=>$value['SalDetail']['price'],//llamar precio
				'cantidad'=>$value['SalDetail']['quantity'],//llamar cantidad
				'warehouseId'=>$value['InvWarehouse']['id'],
				'warehouse'=>$value['InvWarehouse']['name'],//llamar almacen
				'stock'=> $this->_find_stock($value['InvItem']['id'], $value['SalDetail']['inv_warehouse_id'])
				);
		}
//debug($formatedMovementDetails);		
		return $formatedMovementDetails;
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
//			$warehouse = $this->request->data['warehouse'];
//			$supplier = $this->request->data['supplier'];
//			$itemsBySupplier = $this->PurPurchase->InvSupplier->InvItemsSupplier->find('list', array(
//				'fields'=>array('InvItemsSupplier.inv_item_id'),
//				'conditions'=>array(
//					'InvItemsSupplier.inv_supplier_id'=>$supplier
//				),
//				'recursive'=>-1
//			)); 
//debug($itemsBySupplier);			
			$items = $this->SalSale->SalDetail->InvItem->find('list', array(
				'conditions'=>array(
					'NOT'=>array('InvItem.id'=>$itemsAlreadySaved)
					
					/*,'InvItem.id'=>$itemsBySupplier*/
				),
				'recursive'=>-1
				//'fields'=>array('InvItem.id', 'CONCAT(InvItem.code, '-', InvItem.name)')
			));
			
			$invWarehouses = $this->SalSale->SalDetail->InvItem->InvMovementDetail->InvMovement->InvWarehouse->find('list');
			
			
			$firstItemListed = key($items);
			
			$warehouse = key($invWarehouses);
			
			$stock = $this->_find_stock($firstItemListed, $warehouse);
			
//debug($items);
//debug($invWarehouses);
//debug($firstItemListed);
//debug($warehouse);
		// gets the first price in the list of the item prices
		//$firstItemListed = key($items);
		$priceDirty = $this->SalSale->SalDetail->InvItem->InvPrice->find('first', array(
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
//			$warehouse = $this->request->data['warehouse']; //if it's warehouse_transfer is OUT
//			$warehouse2 = $this->request->data['warehouse2'];//if it's warehouse_transfer is IN
//			$transfer = $this->request->data['transfer'];
			
//			$stock = $this->_find_stock($item, $warehouse);//if it's warehouse_transfer is OUT
//			$stock2 ='';
//			if($transfer == 'warehouses_transfer'){
//				$stock2 = $this->_find_stock($item, $warehouse2);//if it's warehouse_transfer is IN	
//			}
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
			$this->set(compact('price'));
		}
	}
	
	
	public function ajax_update_stock_modal_1(){
		if($this->RequestHandler->isAjax()){
			$item = $this->request->data['item'];
			$warehouse = $this->request->data['warehouse'];
//			$warehouse = $this->request->data['warehouse']; //if it's warehouse_transfer is OUT
//			$warehouse2 = $this->request->data['warehouse2'];//if it's warehouse_transfer is IN
//			$transfer = $this->request->data['transfer'];
			
//			$stock = $this->_find_stock($item, $warehouse);//if it's warehouse_transfer is OUT
//			$stock2 ='';
//			if($transfer == 'warehouses_transfer'){
//				$stock2 = $this->_find_stock($item, $warehouse2);//if it's warehouse_transfer is IN	
//			}
//			$priceDirty = $this->SalSale->SalDetail->InvItem->InvPrice->find('first', array(
//			'fields'=>array('InvPrice.price'),
//			'order' => array('InvPrice.date_created' => 'desc'),
//			'conditions'=>array(
//				'InvPrice.inv_item_id'=>$item
//				)
//			));
//			if($priceDirty==array()){
//			$price = 0;
//		}  else {
//			
//			$price = $priceDirty['InvPrice']['price'];
//		}
			//$invWarehouses = $this->SalSale->SalDetail->InvItem->InvMovementDetail->InvMovement->InvWarehouse->find('list');
			
			
			//$warehouse = key($invWarehouses);
			
			$stock = $this->_find_stock($item, $warehouse);			
			
			$this->set(compact('stock'));
		}
	}
	
	
	public function ajax_save_movement_in(){
		if($this->RequestHandler->isAjax()){
			
			////////////////////////////////////////////INICIO-CAPTURAR AJAX////////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$purchaseId = $this->request->data['purchaseId'];
//			$warehouse = $this->request->data['warehouse'];

			$this->loadModel('AdmUser');
			
			$date = $this->request->data['date'];
		//	$supplier = $this->request->data['supplier'];
			$employee = $this->request->data['employee'];
			$taxNumber = $this->request->data['taxNumber'];
			$admProfileId = $this->request->data['salesman'];
			$description = $this->request->data['description'];
			$exRate = $this->request->data['exRate'];
			$note_code = $this->request->data['note_code'];
		//	$movementType = $this->request->data['movementType'];
		//	$documentCode = $this->request->data['documentCode'];
			$admUserId = $this->AdmUser->AdmProfile->find('list', array(
			'fields'=>array('AdmProfile.adm_user_id'),
			'conditions'=>array('AdmProfile.id'=>$admProfileId)
			));
			
			$salesman = key($this->AdmUser->find('list', array(
			'conditions'=>array('AdmUser.id'=>$admUserId)
			)));
			
			//$salesman = $this->request->data['salesman'];
//			$salesman = key($this->SalSale->AdmUser->find('list', array(
//			//'fields'=>array('AdmProfile.adm_user_id'),
//			'conditions'=>array('AdmUser.id'=>$salesman2)
//			)));
			////////////////////////////////////////////FIN-CAPTURAR AJAX////////////////////////////////////////////////////////
			
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'sal_employee_id'=>$employee,'sal_tax_number_id'=>$taxNumber,'salesman_id'=>$salesman,'note_code'=>$note_code,/*'inv_warehouse_id'=>$warehouse, 'inv_movement_type_id'=>$movementType,*/ 'ex_rate'=>$exRate,'description'=>$description);
			
//			$arrayMovement['document_code']=$documentCode;
			
		//	print_r($admUserId);
		//	print_r($salesman);
		//	print_r($purchaseId);
			
			$movementCode = '';
			$movementDocCode = '';
			if($purchaseId <> ''){//update
				$arrayMovement['id'] = $purchaseId;
			}else{//insert
				$movementCode = $this->_generate_code('VEN');
				$movementDocCode = $this->_generate_doc_code('NOT');
				$arrayMovement['lc_state'] = 'ORDER_PENDANT';
				$arrayMovement['lc_transaction'] = 'CREATE';
				$arrayMovement['code'] = $movementCode;
	$arrayMovement['doc_code'] = $movementDocCode;
//	$arrayMovement['inv_supplier_id'] = $supplier;
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
			$arrayMovement['lc_state'] = 'ORDER_APPROVED';
			$arrayMovement['id'] = $purchaseId;
			
			$arrayInvoice = array('date'=>$date, 'sal_employee_id'=>$employee,'sal_tax_number_id'=>$taxNumber,'salesman_id'=>$salesman, 'description'=>$description, 'note_code'=>$note_code);		
			$movementDocCode = $this->_generate_doc_code('FAC');
			$arrayInvoice['lc_state'] = 'INVOICE_PENDANT';
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
	
	private function _generate_code(){
		$period = $this->Session->read('Period.name');
//		$period = $this->Session->read('Period.year');
//		$movementType = '';
//		if($keyword == 'ENT'){$movementType = 'entrada';}
//		if($keyword == 'SAL'){$movementType = 'salida';}
		$movements = $this->SalSale->find('count', array('conditions'=>array('SalSale.lc_state'=>array('ORDER_PENDANT','ORDER_APPROVED','ORDER_CANCELLED')))); // there are duplicates :S, unless there is no movement delete
		$quantity = $movements + 1; 
		//$quantity = $this->InvMovement->getLastInsertID(); //hmm..
		$code = 'VEN-'.$period.'-'.$quantity;
		return $code;
	}
	
	private function _generate_doc_code($keyword){
		$period = $this->Session->read('Period.name');

		if ($keyword == 'NOT'){
			$movements = $this->SalSale->find('count', array('conditions'=>array('SalSale.lc_state'=>array('ORDER_PENDANT','ORDER_APPROVED','ORDER_CANCELLED')))); // there are duplicates :S, unless there is no movement delete
			
		}elseif ($keyword == 'FAC'){
			$movements = $this->SalSale->find('count', array('conditions'=>array('SalSale.lc_state'=>array('INVOICE_PENDANT','INVOICE_APPROVED','INVOICE_CANCELLED')))); // there are duplicates :S, unless there is no movement delete
			
		}
		$quantity = $movements + 1; 
		$docCode = $keyword.'-'.$period.'-'.$quantity;
		return $docCode;
	}
	
	public function index_invoice(){
		$this->paginate = array(
			'conditions' => array(
				'SalSale.lc_state !='=>'INVOICE_LOGIC_DELETED',
				'SalSale.lc_state LIKE'=> '%INVOICE%',
			),
			'order' => array('SalSale.id' => 'desc'),
			'limit' => 15
		);
		$this->SalSale->recursive = 0;
		$this->set('salSales', $this->paginate());
	}
	
	
	
	
	
		public function ajax_initiate_modal_add_pay(){
		if($this->RequestHandler->isAjax()){
		//				$cost = $this->request->data['cost'];
			$paysAlreadySaved = $this->request->data['paysAlreadySaved'];
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
				
			$this->set(compact('pays'/*, 'amount'*/));
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
