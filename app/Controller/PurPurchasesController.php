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
		$invSuppliers = $this->PurPurchase->InvSupplier->find('list');
	/*	$invMovementTypes = $this->InvMovement->InvMovementType->find('list', array(
			'conditions'=>array('InvMovementType.status'=>'entrada', 'InvMovementType.document'=>0)//0 'cause don't have system document
		)); */
		
		$this->PurPurchase->recursive = -1;
		$this->request->data = $this->PurPurchase->read(null, $id);
		$date='';
		$genericCode ='';
		//debug($this->request->data);
		$purDetails = array();
		$documentState = '';
		if($id <> null){
			$date = date("d/m/Y", strtotime($this->request->data['PurPurchase']['date']));//$this->request->data['InvMovement']['date'];
			$purDetails = $this->_get_movements_details($id);
			$documentState =$this->request->data['PurPurchase']['lc_state'];
			$genericCode = $this->request->data['PurPurchase']['code'];
		}
		$this->set(compact('invSuppliers', 'id', 'date', 'purDetails', 'documentState', 'genericCode'));
//debug($this->request->data);
	}
	
	public function save_invoice(){
		$id = '';
		if(isset($this->passedArgs['id'])){
			$id = $this->passedArgs['id'];
		}
		$invSuppliers = $this->PurPurchase->InvSupplier->find('list');
	/*	$invMovementTypes = $this->InvMovement->InvMovementType->find('list', array(
			'conditions'=>array('InvMovementType.status'=>'entrada', 'InvMovementType.document'=>0)//0 'cause don't have system document
		)); */
				
		$this->PurPurchase->recursive = -1;
		$this->request->data = $this->PurPurchase->read(null, $id);
		$date='';
		$genericCode ='';
		$originCode = '';
		//debug($this->request->data);
		$purDetails = array();
		$purPrices = array();
		$documentState = '';
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
					'PurPurchase.code'=>$genericCode
					)
			));
			$originCode = $originDocCode['PurPurchase']['doc_code'];;
		}
		
			
		$this->set(compact('invSuppliers', 'id', 'date', 'purDetails', 'purPrices', 'purPayments', 'documentState', 'genericCode', 'originCode'));
//debug($this->request->data);
	}
	//START - AJAX START - AJAX START - AJAX START - AJAX START - AJAX START - AJAX START - AJAX
	
	public function ajax_initiate_modal_add_item_in(){
		if($this->RequestHandler->isAjax()){
						
			$itemsAlreadySaved = $this->request->data['itemsAlreadySaved'];
//			$warehouse = $this->request->data['warehouse'];
			$supplier = $this->request->data['supplier'];
			$itemsBySupplier = $this->PurPurchase->InvSupplier->InvItemsSupplier->find('list', array(
				'fields'=>array('InvItemsSupplier.inv_item_id'),
				'conditions'=>array(
					'InvItemsSupplier.inv_supplier_id'=>$supplier
				),
				'recursive'=>-1
			)); 
//debug($itemsBySupplier);			
			$items = $this->PurPurchase->PurDetail->InvItem->find('list', array(
				'conditions'=>array(
					'NOT'=>array('InvItem.id'=>$itemsAlreadySaved)
					
					,'InvItem.id'=>$itemsBySupplier
				),
				'recursive'=>-1
				//'fields'=>array('InvItem.id', 'CONCAT(InvItem.code, '-', InvItem.name)')
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
				
			$this->set(compact('items', 'price'));
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
						'NOT'=>array('InvPriceType.id'=>$costsAlreadySaved)
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
				
			$this->set(compact('costs'/*, 'amount'*/));
		}
	}
	
	//Hace un find en la BD de los elementos que se mostraran en el combobox 
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
				
			$this->set(compact('pays'/*, 'amount'*/));
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
	
	
	public function ajax_save_movement_in(){
		if($this->RequestHandler->isAjax()){
			
			////////////////////////////////////////////INICIO-CAPTURAR AJAX////////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$purchaseId = $this->request->data['purchaseId'];
//			$warehouse = $this->request->data['warehouse'];

			$date = $this->request->data['date'];
			$supplier = $this->request->data['supplier'];
			$description = $this->request->data['description'];
//			$movementType = $this->request->data['movementType'];
//			$documentCode = $this->request->data['documentCode'];
			
			////////////////////////////////////////////FIN-CAPTURAR AJAX////////////////////////////////////////////////////////
			
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'supplier'=>$supplier,/*'inv_warehouse_id'=>$warehouse, 'inv_movement_type_id'=>$movementType,*/ 'description'=>$description);
			
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
//			$warehouse = $this->request->data['warehouse'];

			$date = $this->request->data['date'];
			$supplier = $this->request->data['supplier'];
			$description = $this->request->data['description'];
//			$movementType = $this->request->data['movementType'];
//			$documentCode = $this->request->data['documentCode'];
			
			////////////////////////////////////////////FIN-CAPTURAR AJAX////////////////////////////////////////////////////////
			
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'supplier'=>$supplier, 'description'=>$description);
			
//			$arrayMovement['document_code']=$documentCode;
			
			//print_r($arrayMovement);
			
			$movementCode = '';
			$movementDocCode = '';
			if($purchaseId <> ''){//update
				$arrayMovement['id'] = $purchaseId;
			}else{//insert
				$movementCode = $this->_generate_code('COM');
				$movementDocCode = $this->_generate_doc_code('FAC');
				$arrayMovement['lc_state'] = 'INVOICE_PENDANT';
				$arrayMovement['lc_transaction'] = 'CREATE';
				$arrayMovement['code'] = $movementCode;
	$arrayMovement['doc_code'] = $movementDocCode;
	$arrayMovement['inv_supplier_id'] = $supplier;
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
//				if($arrayCostsDetails <> array()){	
//					if(($this->PurPurchase->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPrice->deleteAll(array('PurPrice.pur_purchase_id'=>$purchaseId))) ){
//						if($this->PurPurchase->saveAssociated($data2)){
//							echo 'modificado| cost d&&d';
//						}
//					}
//				}else{
//					if(($this->PurPurchase->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$purchaseId)))&&($this->PurPurchase->PurPrice->deleteAll(array('PurPrice.pur_purchase_id'=>$purchaseId))) ){
//						if($this->PurPurchase->saveAssociated($data)){
//							echo 'modificado|d&&d';
//						}
//					}
//				}	
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
				if($this->PurPurchase->saveAssociated($data2)){
//					$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $supplier);
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
	$generalCode = $this->request->data['genericCode'];
//			$movementType = $this->request->data['movementType'];
//			$documentCode = $this->request->data['documentCode'];
			////////////////////////////////////////////FIN-CAPTURAR AJAX/////////////////////////////////////////////////////
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'inv_supplier_id'=>$supplier, 'description'=>$description);
			$arrayMovement['lc_state'] = 'ORDER_APPROVED';
			$arrayMovement['id'] = $purchaseId;
			
			$arrayInvoice = array('date'=>$date, 'inv_supplier_id'=>$supplier, 'description'=>$description/*, 'code'=>$code*/);
	/*X*/		
			$movementDocCode = $this->_generate_doc_code('FAC');
			$arrayInvoice['lc_state'] = 'INVOICE_PENDANT';
			$arrayInvoice['lc_transaction'] = 'CREATE';
			$arrayInvoice['code'] = $generalCode;
	$arrayInvoice['doc_code'] = $movementDocCode;
	$arrayInvoice['inv_supplier_id'] = $supplier;
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
//			print_r($data);
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
//	$generalCode = $this->request->data['genericCode'];
//			$movementType = $this->request->data['movementType'];
//			$documentCode = $this->request->data['documentCode'];
			////////////////////////////////////////////FIN-CAPTURAR AJAX/////////////////////////////////////////////////////
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'inv_supplier_id'=>$supplier, 'description'=>$description);
			$arrayMovement['lc_state'] = 'INVOICE_APPROVED';
			$arrayMovement['id'] = $purchaseId;
			
//			$arrayInvoice = array('date'=>$date, 'inv_supplier_id'=>$supplier, 'description'=>$description/*, 'code'=>$code*/);
	/*X*/		
//			$movementDocCode = $this->_generate_doc_code('FAC');
//			$arrayInvoice['lc_state'] = 'INVOICE_PENDANT';
//			$arrayInvoice['lc_transaction'] = 'CREATE';
//			$arrayInvoice['code'] = $generalCode;
//	$arrayInvoice['doc_code'] = $movementDocCode;
//	$arrayInvoice['inv_supplier_id'] = $supplier;
//			if($documentCode <> ''){
//				$arrayMovement['document_code']=$documentCode;
//			}
			
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
	
	public function ajax_change_state_logic_deleted_movement_in(){
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
				$data = array('id'=>$purchaseId, 'lc_state'=>'ORDER_LOGIC_DELETED');
				if($this->PurPurchase->save($data)){
//					$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
					echo 'borradologico|'/*.$strItemsStock*/;
				}
//			}else{
//				echo 'error|'.$error['itemsStocks'];
//			}
						
		}
	}
	
	public function ajax_change_state_logic_deleted_invoice(){
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
				$data = array('id'=>$purchaseId, 'lc_state'=>'INVOICE_LOGIC_DELETED');
				if($this->PurPurchase->save($data)){
//					$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
					echo 'borradologico|'/*.$strItemsStock*/;
				}
//			}else{
//				echo 'error|'.$error['itemsStocks'];
//			}
						
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
	
	
	private function _generate_code(){
//		$period = $this->Session->read('Period.name');
		$period = $this->Session->read('Period.year');
//		$movementType = '';
//		if($keyword == 'ENT'){$movementType = 'entrada';}
//		if($keyword == 'SAL'){$movementType = 'salida';}
		$movements = $this->PurPurchase->find('count', array('conditions'=>array('PurPurchase.lc_state'=>array('ORDER_PENDANT','ORDER_APPROVED','ORDER_CANCELLED')))); // there are duplicates :S, unless there is no movement delete
		$quantity = $movements + 1; 
		//$quantity = $this->InvMovement->getLastInsertID(); //hmm..
		$code = 'COM-'.$period.'-'.$quantity;
		return $code;
	}
	
	private function _generate_doc_code($keyword){
//		$period = $this->Session->read('Period.name');
		$period = $this->Session->read('Period.year');
		$movements = $this->PurPurchase->find('count', array('conditions'=>array('PurPurchase.lc_state'=>array('ORDER_PENDANT','ORDER_APPROVED','ORDER_CANCELLED')))); // there are duplicates :S, unless there is no movement delete
		$quantity = $movements + 1; 
		//$quantity = $this->InvMovement->getLastInsertID(); //hmm..
		$docCode = $keyword.'-'.$period.'-'.$quantity;
		return $docCode;
	}
	
	public function _get_movements_details($idMovement){
		$movementDetails = $this->PurPurchase->PurDetail->find('all', array(
			'conditions'=>array(
				'PurDetail.pur_purchase_id'=>$idMovement
				),
			'fields'=>array('InvItem.name', 'InvItem.code', 'PurDetail.price', 'PurDetail.quantity', 'InvItem.id'/*, 'PurPurchase.inv_supplier_id','InvPrice.price'*/)
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
				'price'=>$value['PurDetail']['price'],//llamar precio
				'cantidad'=>$value['PurDetail']['quantity']//llamar cantidad
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
			'fields'=>array('InvPriceType.name', 'PurPrice.amount', 'InvPriceType.id'/*, 'PurPurchase.inv_supplier_id','InvPrice.price'*/)
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
				'cost'=>$value['InvPriceType']['name'],
				'amount'=>$value['PurPrice']['amount']//llamar precio
				);
		}
//debug($formatedMovementDetails);		
		return $formatedMovementDetails;
	}
	
	public function _get_pays_details($idMovement){
		$movementDetails = $this->PurPurchase->PurPayment->find('all', array(
			'conditions'=>array(
				'PurPayment.pur_purchase_id'=>$idMovement
				),
			'fields'=>array('PurPaymentType.name', 'PurPayment.date', 'PurPayment.due_date', 'PurPayment.amount', 'PurPayment.description', 'PurPayment.lc_state', 'PurPaymentType.id')
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
				'payId'=>$value['PurPaymentType']['id'],
				'pay'=>$value['PurPaymentType']['name'],
				'date'=>$value['PurPayment']['date'],
				'dueDate'=>$value['PurPayment']['due_date'],
				'paidAmount'=>$value['PurPayment']['amount'], //paidAmount ?
				'description'=>$value['PurPayment']['description'],
				'state'=>$value['PurPayment']['lc_state']
				);
		}
//debug($formatedMovementDetails);		
		return $formatedMovementDetails;
	}
/**
 * index method
 *
 * @return void
 */
	public function index_order() {	
		$this->paginate = array(
			'conditions' => array(
				'PurPurchase.lc_state !='=>'ORDER_LOGIC_DELETED'
				,'PurPurchase.lc_state LIKE'=> '%ORDER%'
			),
			'order' => array('PurPurchase.id' => 'desc'),
			'limit' => 15
		);
		$this->PurPurchase->recursive = 0;
		$this->set('purPurchases', $this->paginate());
	}
	
	public function index_invoice(){
		$this->paginate = array(
			'conditions' => array(
				'PurPurchase.lc_state !='=>'INVOICE_LOGIC_DELETED',
				'PurPurchase.lc_state LIKE'=> '%INVOICE%',
			),
			'order' => array('PurPurchase.id' => 'desc'),
			'limit' => 15
		);
		$this->PurPurchase->recursive = 0;
		$this->set('purPurchases', $this->paginate());
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
}
