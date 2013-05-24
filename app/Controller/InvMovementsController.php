<?php
App::uses('AppController', 'Controller');
/**
 * InvMovements Controller
 *
 * @property InvMovement $InvMovement
 */
class InvMovementsController extends AppController {

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
//	public $helpers = array('TwitterBootstrap.BootstrapHtml', 'TwitterBootstrap.BootstrapForm', 'TwitterBootstrap.BootstrapPaginator');
/**
 * Components
 *
 * @var array
 */
//	public $components = array('Session');
	//*******************************************************************************************************//
	///////////////////////////////////////// START - FUNCTIONS ///////////////////////////////////////////////
	//*******************************************************************************************************//
	
	//////////////////////////////////////////// START - PDF ///////////////////////////////////////////////
	public function view_document_movement_pdf($id = null) {
		
		$this->InvMovement->id = $id;
		
		if (!$this->InvMovement->exists()) {
			throw new NotFoundException(__('Invalid post'));
		}
		// increase memory limit in PHP 
		ini_set('memory_limit', '512M');
		$movement = $this->InvMovement->read(null, $id);
		
		if($movement['InvMovement']['inv_movement_type_id'] == 4){
			$this->redirect(array('action'=>'index_warehouses_transfer'));
		}
		
		if($movement['InvMovement']['inv_movement_type_id'] == 3){
			
			$movementIdOut = $this->InvMovement->find('all', array(
				'conditions'=>array(
					'InvMovement.document_code'=>$movement['InvMovement']['document_code'],
					'InvMovement.inv_movement_type_id ='=>4
			)));//Out Origin
			$movement['Transfer']['code'] = $movementIdOut[0]['InvMovement']['code'];
			$movement['Transfer']['warehouseName'] = $movementIdOut[0]['InvWarehouse']['name'];
		}
		
		
		$details=$this->_get_movements_details_without_stock($id);
		$this->set('movement', $movement);
		$this->set('details', $details);
	}
	//////////////////////////////////////////// END - PDF /////////////////////////////////////////////////
	
	
	//////////////////////////////////////////// START - INDEX ///////////////////////////////////////////////
	
	public function index_in() {
		
		//debug($this->request->params);
		//debug($this->passedArgs);
		///////////////////////////////////////START - CREATING VARIABLES//////////////////////////////////////
		$filters = array();
		$code = '';
		$document_code = '';
		///////////////////////////////////////END - CREATING VARIABLES////////////////////////////////////////
		
		
		////////////////////////////START - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		if($this->request->is("post")) {
			$url = array('action'=>'index_in');
			$parameters = array();
			$empty=0;
			if(isset($this->request->data['InvMovement']['code']) && $this->request->data['InvMovement']['code']){
				$parameters['code'] = trim(strip_tags($this->request->data['InvMovement']['code']));
			}else{
				$empty++;
			}
			if(isset($this->request->data['InvMovement']['document_code']) && $this->request->data['InvMovement']['document_code']){
				$parameters['document_code'] = trim(strip_tags($this->request->data['InvMovement']['document_code']));
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
		if(isset($this->passedArgs['code'])){
			$filters['InvMovement.code LIKE'] = '%'.strtoupper($this->passedArgs['code']).'%';
			$code = $this->passedArgs['code'];
		}
		if(isset($this->passedArgs['document_code'])){
			$filters['InvMovement.document_code LIKE'] = '%'.strtoupper($this->passedArgs['document_code']).'%';
			$document_code = $this->passedArgs['document_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->paginate = array(
			'conditions'=>array(
				'InvMovement.lc_state !='=>'LOGIC_DELETE',
				'InvMovementType.status'=> 'entrada',
				$filters
			 ),
			//'recursive'=>2,	
			'order'=> array('InvMovement.id'=>'desc'),
			'limit' => 15,
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		
		
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('invMovements', $this->paginate('InvMovement'));
		$this->set('code', $code);
		$this->set('document_code', $document_code);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
	}
	
	public function index_out() {
		
		//debug($this->request->params);
		//debug($this->passedArgs);
		///////////////////////////////////////START - CREATING VARIABLES//////////////////////////////////////
		$filters = array();
		$code = '';
		$document_code = '';
		///////////////////////////////////////END - CREATING VARIABLES////////////////////////////////////////
		
		
		////////////////////////////START - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		if($this->request->is("post")) {
			$url = array('action'=>'index_out');
			$parameters = array();
			$empty=0;
			if(isset($this->request->data['InvMovement']['code']) && $this->request->data['InvMovement']['code']){
				$parameters['code'] = trim(strip_tags($this->request->data['InvMovement']['code']));
			}else{
				$empty++;
			}
			if(isset($this->request->data['InvMovement']['document_code']) && $this->request->data['InvMovement']['document_code']){
				$parameters['document_code'] = trim(strip_tags($this->request->data['InvMovement']['document_code']));
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
		if(isset($this->passedArgs['code'])){
			$filters['InvMovement.code LIKE'] = '%'.strtoupper($this->passedArgs['code']).'%';
			$code = $this->passedArgs['code'];
		}
		if(isset($this->passedArgs['document_code'])){
			$filters['InvMovement.document_code LIKE'] = '%'.strtoupper($this->passedArgs['document_code']).'%';
			$document_code = $this->passedArgs['document_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->paginate = array(
			'conditions'=>array(
				'InvMovement.lc_state !='=>'LOGIC_DELETE',
				'InvMovementType.status'=> 'salida',
				$filters
			 ),
			//'recursive'=>2,	
			'order'=> array('InvMovement.id'=>'desc'),
			'limit' => 15,
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		
		
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('invMovements', $this->paginate('InvMovement'));
		$this->set('code', $code);
		$this->set('document_code', $document_code);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
	}
	
	
	public function index_purchase_in(){
	
		///////////////////////////////////////START - CREATING VARIABLES//////////////////////////////////////
		$filters = array();
		$document_code = '';  //seria code de pur_purchases
		///////////////////////////////////////END - CREATING VARIABLES////////////////////////////////////////
		
		
		////////////////////////////START - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		if($this->request->is("post")) {
			$url = array('action'=>'index_purchase_in');
			$parameters = array();
			$empty=0;
			if(isset($this->request->data['InvMovement']['document_code']) && $this->request->data['InvMovement']['document_code']){
				$parameters['document_code'] = trim(strip_tags($this->request->data['InvMovement']['document_code']));
			}else{
				$empty++;
			}
			if($empty == 1){
				$parameters['search']='empty';
			}else{
				$parameters['search']='yes';
			}
			
			$this->redirect(array_merge($url,$parameters));
		}
		////////////////////////////END - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		
		$this->loadModel('PurPurchase');
		
		////////////////////////////START - SETTING URL FILTERS//////////////////////////////////////
		if(isset($this->passedArgs['document_code'])){
			$filters['PurPurchase.code LIKE'] = '%'.strtoupper($this->passedArgs['document_code']).'%';
			$document_code = $this->passedArgs['document_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		//Add association, is working but paginate method is not sending or uses its conditions
		/*
		$this->PurPurchase->bindModel(array(
			'hasOne'=>array(
				'InvMovement'=>array(
					'foreignKey'=>false,
					'conditions'=> array('InvMovement.document_code = PurPurchase.code')
				)
				
			)
		));
		*/
		$this->paginate = array(
			'conditions'=>array(
				//'PurPurchase.lc_state =' => 'INVOICE_APPROVED',
				'PurPurchase.lc_state !='=>'LOGIC_DELETE',
				'PurPurchase.lc_state'=>'ORDER_APPROVED',
				$filters
			 ),
			'recursive'=>0,	
			'order'=> array('PurPurchase.id'=>'desc'),
			'limit' => 15,
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$pagination = $this->paginate('PurPurchase');
		$paginatedCodes = array();
		for($i = 0; $i<count($pagination); $i++){ 
			$paginatedCodes[$i] = $pagination[$i]['PurPurchase']['code'];
		}
		//debug($paginatedCodes);
		$movements = $this->InvMovement->find('all',array(
			'conditions'=>array('InvMovement.inv_movement_type_id'=>1, 'InvMovement.document_code'=>$paginatedCodes,'NOT'=>array('InvMovement.lc_state'=>array('LOGIC_DELETE', 'CANCELLED'))),
			'fields'=>array('InvMovement.lc_state', 'InvMovement.document_code'),
			'recursive'=>-1
		));
		//debug($this->paginate('PurPurchase'));
		//debug($movements);
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('purPurchases', $pagination);
		$this->set('document_code', $document_code);
		$this->set('movements', $movements);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
	}
	
	///////////////////////////////////////////// END - INDEX ////////////////////////////////////////////////
	
	
	//////////////////////////////////////////// START - SAVE ///////////////////////////////////////////////
	
	public function save_in(){
		$id = '';
		if(isset($this->passedArgs['id'])){
			$id = $this->passedArgs['id'];
		}
		$invWarehouses = $this->InvMovement->InvWarehouse->find('list');
		$invMovementTypes = $this->InvMovement->InvMovementType->find('list', array(
			'conditions'=>array('InvMovementType.status'=>'entrada', 'InvMovementType.document'=>0)//0 'cause don't have system document
		));
		
		$this->InvMovement->recursive = -1;
		$this->request->data = $this->InvMovement->read(null, $id);
		$date='';
		//debug($this->request->data);
		$invMovementDetails = array();
		$documentState = '';
		if($id <> null){
			$date = date("d/m/Y", strtotime($this->request->data['InvMovement']['date']));//$this->request->data['InvMovement']['date'];
			$invMovementDetails = $this->_get_movements_details($id);
			$documentState =$this->request->data['InvMovement']['lc_state'];
		}
		$this->set(compact('invMovementTypes','invWarehouses', 'id', 'date', 'invMovementDetails', 'documentState'));
	}
	
	public function save_out(){
		$id = '';
		if(isset($this->passedArgs['id'])){
			$id = $this->passedArgs['id'];
		}
		$invWarehouses = $this->InvMovement->InvWarehouse->find('list');
		$invMovementTypes = $this->InvMovement->InvMovementType->find('list', array(
			'conditions'=>array('InvMovementType.status'=>'salida', 'InvMovementType.document'=>0)//0 'cause don't have system document
		));
		
		$this->InvMovement->recursive = -1;
		$this->request->data = $this->InvMovement->read(null, $id);
		$date='';

		$invMovementDetails = array();
		$documentState = '';
		if($id <> null){
			$date = date("d/m/Y", strtotime($this->request->data['InvMovement']['date']));//$this->request->data['InvMovement']['date'];
			$invMovementDetails = $this->_get_movements_details($id);
			$documentState =$this->request->data['InvMovement']['lc_state'];
		}
		$this->set(compact('invMovementTypes','invWarehouses', 'id', 'date', 'invMovementDetails', 'documentState'));
	}
	
	public function save_purchase_in(){
		//debug($purchase);
		////////////////////////////////INICIO - VALIDAR SI ID COMPRA NO ESTA VACIO///////////////////////////////////
		$idMovement = '';
		$documentCode = '';
		if(isset($this->passedArgs['id'])){
			$idMovement = $this->passedArgs['id'];
		}
		if(isset($this->passedArgs['document_code'])){
			$documentCode = $this->passedArgs['document_code'];
		}
		
		if($documentCode == ''){
			$this->redirect(array('action' => 'index_purchase_in'));
			//echo 'codigo vacio';
		}
		////////////////////////////////FIN - VALIDAR SI ID COMPRA NO ESTA VACIO/////////////////////////////////////


		////////////////////////////////INICIO - VALIDAR SI CODIGO COMPRA EXISTE///////////////////////////////////
		$this->loadModel('PurPurchase');	
		$idPurchase = $this->PurPurchase->field('PurPurchase.id', array('PurPurchase.code'=>$documentCode));
		if(!$idPurchase){
			$this->redirect(array('action' => 'index_purchase_in'));
			//echo 'no existe codigo compra';
		}
		////////////////////////////////FIN - VALIDAR SI ID COMPRA EXISTE/////////////////////////////////////
		
		////////////////////////////////INICIO - DECLARAR VARIABLES///////////////////////////////////
		$arrayAux = array();
		$invWarehouses = $this->InvMovement->InvWarehouse->find('list');
		$firstWarehouse = key($invWarehouses);
		$invMovementDetails = array();
		$documentState = '';
		$id='';
		$date = '';
		////////////////////////////////FIN - DECLARAR VARIABLES///////////////////////////////////
		
		
		
		if($idMovement <> ''){//Si idMovimiento esta lleno, mostrar todo, hasta cancelados en index_in
			$this->InvMovement->recursive = -1;
			$arrayAux = $this->InvMovement->find('all', array('conditions'=>array(
			 'InvMovement.document_code'=>$documentCode
			,'InvMovement.id'=>$idMovement
			)));
			if(count($arrayAux) == 0){//si no existe el movimiento
				$this->redirect(array('action' => 'index_in'));
			}
		}else{//Si idMovimiento esta vacio, mostrar solo (nuevo, pendiente o aprobado) en index_save_in
			$this->InvMovement->recursive = -1;
			$arrayAux = $this->InvMovement->find('all', array('conditions'=>array(
			'InvMovement.document_code'=>trim($documentCode), 'InvMovement.lc_state'=>array('APPROVED','PENDANT')
			)));
		}
		
		//mostrar cancelados
		
		//mostrar activos
		

		////////////////////////////////INICIO - LLENAR VISTA ///////////////////////////////////////////////
		if(count($arrayAux) > 0){ //UPDATE
			$this->request->data = $arrayAux[0];
			$date = date("d/m/Y", strtotime($this->request->data['InvMovement']['date']));//$this->request->data['InvMovement']['date'];
			$id = $this->request->data['InvMovement']['id'];
			$invMovementDetails = array();//$this->_get_movements_details($id);
			$documentState =$this->request->data['InvMovement']['lc_state'];
			
			$arrPurchases = $this->_get_purchases_details($idPurchase, $firstWarehouse, 'editar');//$firstWarehouse no se usara porque es "editar", sino doble query para stock
			$arrMovementsSaved = $this->_get_movements_details($id);
			foreach ($arrMovementsSaved as $key => $value) {
				$invMovementDetails[$key]['itemId']=$value['itemId'];
				$invMovementDetails[$key]['item']=$value['item'];
				$invMovementDetails[$key]['cantidadCompra']=$arrPurchases[$key]['cantidadCompra'];
				$invMovementDetails[$key]['stock']=$value['stock'];
				$invMovementDetails[$key]['cantidad']=$value['cantidad'];
			}
		}else{//INSERT
			$invMovementDetails = $this->_get_purchases_details($idPurchase, $firstWarehouse,'nuevo');
		}
		
		$this->set(compact('invWarehouses', 'id', 'documentCode', 'date', 'invMovementDetails', 'documentState', 'idMovement'));
		////////////////////////////////FIN - LLENAR VISTA //////////////////////////////////////////////////
		
	}
	
	public function save_warehouses_transfer(){
		/////////////////////////////////////////START - VARIABLES DECLARATION///////////////////
		$documentCode = '';
		
		$warehouseIn ='';
		$warehouseOut ='';
		$movementIdIn = '';
		$movementIdOut = '';
		$date = '';
		$invMovementDetailsOut = array();
		$invMovementDetailsIn = array();
		$documentCode = '';
		$documentState = '';
		///////////////////////////////////////////END - VARIABLES DECLARATION///////////////////
		
		/////////////////////////////////////////START - VIEW VALIDATION FOR MODIFY///////////////////
		if(isset($this->passedArgs['document_code'])){
			$documentCode = $this->passedArgs['document_code'];
			$movementIdIn = $this->InvMovement->field('InvMovement.id', array(
				'InvMovement.document_code'=>$documentCode,
				'InvMovement.inv_movement_type_id ='=>4//In Destination
			));
			$movementIdOut = $this->InvMovement->field('InvMovement.id', array(
					'InvMovement.document_code'=>$documentCode,
					'InvMovement.inv_movement_type_id ='=>3//Out Origin
			));
			$url = '';
			if($movementIdIn == '' OR $movementIdOut == ''){
				if(isset($this->passedArgs['origin'])){
					if($this->passedArgs['origin'] == 'in'){
						$url = array('action'=>'index_in');
					}elseif($this->passedArgs['origin'] == 'out'){
						$url = array('action'=>'index_out');
					}
					$this->redirect($url);
				}else{
					$this->redirect(array('action'=>'index_in'));
				}
			}
			
			$warehouseIn = $this->InvMovement->field('InvMovement.inv_warehouse_id', array('InvMovement.id'=>$movementIdIn));
			$this->InvMovement->recursive = -1;
			$this->request->data = $this->InvMovement->read(null, $movementIdOut);
			$date = date("d/m/Y", strtotime($this->request->data['InvMovement']['date']));
			//$warehouseOut = $this->InvMovement->field('InvMovement.inv_warehouse_id', array('InvMovement.id'=>$movementIdOut));
			$warehouseOut = $this->request->data['InvMovement']['inv_warehouse_id'];
			$documentState =$this->request->data['InvMovement']['lc_state'];
			$invMovementDetailsOut = $this->_get_movements_details($movementIdOut);
			$invMovementDetailsIn = $this->_get_movements_details($movementIdIn);
			
		}
		///////////////////////////////////////////END - VIEW VALIDATION FOR MODIFY///////////////////
		$warehouses = $this->InvMovement->InvWarehouse->find('list');
		
		
		
		$this->set(compact('warehouses','warehouseIn','warehouseOut', 'movementIdOut', 'date', 'invMovementDetailsOut', 'invMovementDetailsIn', 'documentState', 'documentCode'));
	}
	
	public function index_warehouses_transfer(){

		///////////////////////////////////////START - CREATING VARIABLES//////////////////////////////////////
		$filters = array();
		$document_code = '';
		///////////////////////////////////////END - CREATING VARIABLES////////////////////////////////////////
		
		
		////////////////////////////START - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		if($this->request->is("post")) {
			$url = array('action'=>'index_warehouses_transfer');
			$parameters = array();
			$empty=0;
			if(isset($this->request->data['InvMovement']['document_code']) && $this->request->data['InvMovement']['document_code']){
				$parameters['document_code'] = trim(strip_tags($this->request->data['InvMovement']['document_code']));
			}else{
				$empty++;
			}
			if($empty == 1){
				$parameters['search']='empty';
			}else{
				$parameters['search']='yes';
			}
			$this->redirect(array_merge($url,$parameters));
		}
		////////////////////////////END - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		
		
		
		////////////////////////////START - SETTING URL FILTERS//////////////////////////////////////
		if(isset($this->passedArgs['document_code'])){
			$filters['InvMovement.document_code LIKE'] = '%'.strtoupper($this->passedArgs['document_code']).'%';
			$document_code = $this->passedArgs['document_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->paginate = array(
			'conditions'=>array(
				'InvMovement.lc_state !='=>'LOGIC_DELETE',
				'InvMovement.inv_movement_type_id'=> 3,//out
				$filters
			 ),
			//'recursive'=>2,	
			'order'=> array('InvMovement.id'=>'desc'),
			'limit' => 15,
		);
		
		$pagination = $this->paginate('InvMovement');
		//debug($pagination);
		$paginatedDocumentCodes = array();
		for($i = 0; $i<count($pagination); $i++){ 
			$paginatedDocumentCodes[$i] = $pagination[$i]['InvMovement']['document_code'];
		}
		$warehouseDestination = $this->InvMovement->find('all', array(
				'conditions'=>array(
					'InvMovement.lc_state !='=>'LOGIC_DELETE',
					'InvMovement.document_code'=>$paginatedDocumentCodes,
					'InvMovement.inv_movement_type_id'=> 4,//in
					$filters
				 ),
				 'fields'=>array('InvMovement.id','InvMovement.inv_warehouse_id','InvWarehouse.name', 'InvMovement.document_code')
			));
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		//debug($warehouseDestination);
		
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('invMovements', $pagination);
		$this->set('document_code', $document_code);
		$this->set('warehouseDestination',$warehouseDestination);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
	}
	
	
	//////////////////////////////////////////// END - SAVE /////////////////////////////////////////////////
	
	
	//////////////////////////////////////////// START - AJAX ///////////////////////////////////////////////
	
	public function ajax_initiate_modal_add_item_in(){
		if($this->RequestHandler->isAjax()){
						
			$itemsAlreadySaved = $this->request->data['itemsAlreadySaved'];
			$warehouse = $this->request->data['warehouse']; //if it's warehouse_transfer is OUT
			$warehouse2 = $this->request->data['warehouse2'];//if it's warehouse_transfer is IN
			$transfer = $this->request->data['transfer'];
			
			$items = $this->InvMovement->InvMovementDetail->InvItem->find('list', array(
				'conditions'=>array(
					'NOT'=>array('InvItem.id'=>$itemsAlreadySaved)
				),
				'recursive'=>-1,
				//'fields'=>array('InvItem.id', 'CONCAT(InvItem.code, '-', InvItem.name)')
			));
			$firstItemListed = key($items);
			$stock = $this->_find_stock($firstItemListed, $warehouse); //if it's warehouse_transfer is OUT
			$stock2 = '';
			if($transfer == 'warehouses_transfer'){
				$stock2 = $this->_find_stock($firstItemListed, $warehouse2);//if it's warehouse_transfer is IN	
			}
			//debug($stock2);
			$this->set(compact('items', 'stock', 'stock2', 'transfer'));
		}
	}
	
	public function ajax_update_stock_modal(){
		if($this->RequestHandler->isAjax()){
			$item = $this->request->data['item'];
			$warehouse = $this->request->data['warehouse']; //if it's warehouse_transfer is OUT
			$warehouse2 = $this->request->data['warehouse2'];//if it's warehouse_transfer is IN
			$transfer = $this->request->data['transfer'];
			
			$stock = $this->_find_stock($item, $warehouse);//if it's warehouse_transfer is OUT
			$stock2 ='';
			if($transfer == 'warehouses_transfer'){
				$stock2 = $this->_find_stock($item, $warehouse2);//if it's warehouse_transfer is IN	
			}
			
			$this->set(compact('stock', 'stock2', 'transfer'));
		}
	}

	public function ajax_create_temporal_movement_in(){
		if($this->RequestHandler->isAjax()){
			$idItem = $this->request->data['item'];
			$quantity = $this->request->data['quantity'];
			//$stock = $this->request->data['stock']; //It'll be used in movement_out
			//$purchasedQuantity = $this->request->data['purchasedQuantity']; //It'll be used when a purchase is send and also a validation will appear
			//Ex: if($quantity > $purchasedQuantity){//send string error} 
			$this->Session->write('movement_in.'.$idItem, $quantity);  //The same as movemement_in(23=>(quantity1),34=>(quantity2))
			echo 'success';
			//$this->Session->write('movement_in.stock');//It'll be used in movement_out, or maybe when cancelling an approved movement_in
		}
		
	}
	
	public function ajax_save_movement_in(){
		if($this->RequestHandler->isAjax()){
			
			////////////////////////////////////////////INICIO-CAPTURAR AJAX////////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$movementId = $this->request->data['movementId'];
			$warehouse = $this->request->data['warehouse'];

			$date = $this->request->data['date'];
			$description = $this->request->data['description'];
			$movementType = $this->request->data['movementType'];
			$documentCode = $this->request->data['documentCode'];
			
			////////////////////////////////////////////FIN-CAPTURAR AJAX////////////////////////////////////////////////////////
			
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'inv_warehouse_id'=>$warehouse, 'inv_movement_type_id'=>$movementType, 'description'=>$description);
			
			$arrayMovement['document_code']=$documentCode;
			
			//print_r($arrayMovement);
			
			$movementCode = '';
			if($movementId <> ''){//update
				$arrayMovement['id'] = $movementId;
			}else{//insert
				$movementCode = $this->_generate_code('ENT');
				$arrayMovement['lc_state'] = 'PENDANT';
				$arrayMovement['lc_transaction'] = 'CREATE';
				$arrayMovement['code'] = $movementCode;
			}
			
			$data = array('InvMovement'=>$arrayMovement, 'InvMovementDetail'=>$arrayItemsDetails);
			//print_r($data);
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
			

			////////////////////////////////////////////INICIO-SAVE////////////////////////////////////////////////////////
			if($movementId <> ''){//update
				if($this->InvMovement->InvMovementDetail->deleteAll(array('InvMovementDetail.inv_movement_id'=>$movementId))){
					if($this->InvMovement->saveAssociated($data)){
						$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
						echo 'modificado|'.$strItemsStock;
					}
				}
			}else{//insert
				if($this->InvMovement->saveAssociated($data)){
					$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
					$movementIdInserted = $this->InvMovement->id;
						echo 'insertado|'.$strItemsStock.'|'.$movementCode.'|'.$movementIdInserted;
				}
			}
			////////////////////////////////////////////FIN-SAVE////////////////////////////////////////////////////////
		
		}
	}

	public function ajax_save_movement_out(){
		if($this->RequestHandler->isAjax()){
			
			////////////////////////////////////////////INICIO-CAPTURAR AJAX////////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$movementId = $this->request->data['movementId'];
			$warehouse = $this->request->data['warehouse'];

			$date = $this->request->data['date'];
			$description = $this->request->data['description'];
			$movementType = $this->request->data['movementType'];
			$documentCode = $this->request->data['documentCode'];
			
			////////////////////////////////////////////FIN-CAPTURAR AJAX////////////////////////////////////////////////////////
			
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'inv_warehouse_id'=>$warehouse, 'inv_movement_type_id'=>$movementType, 'description'=>$description);
			
			$arrayMovement['document_code']=$documentCode;
			
			//print_r($arrayMovement);
			
			$movementCode = '';
			if($movementId <> ''){//update
				$arrayMovement['id'] = $movementId;
			}else{//insert
				$movementCode = $this->_generate_code('SAL');
				$arrayMovement['lc_state'] = 'PENDANT';
				$arrayMovement['lc_transaction'] = 'CREATE';
				$arrayMovement['code'] = $movementCode;
			}
			
			$data = array('InvMovement'=>$arrayMovement, 'InvMovementDetail'=>$arrayItemsDetails);
			//print_r($data);
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
			

			////////////////////////////////////////////INICIO-SAVE////////////////////////////////////////////////////////
			if($movementId <> ''){//update
				if($this->InvMovement->InvMovementDetail->deleteAll(array('InvMovementDetail.inv_movement_id'=>$movementId))){
					if($this->InvMovement->saveAssociated($data)){
						$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
						echo 'modificado|'.$strItemsStock;
					}
				}
			}else{//insert
				if($this->InvMovement->saveAssociated($data)){
					$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
					$movementIdInserted = $this->InvMovement->id;
						echo 'insertado|'.$strItemsStock.'|'.$movementCode.'|'.$movementIdInserted;
				}
			}
			////////////////////////////////////////////FIN-SAVE////////////////////////////////////////////////////////
		
		}
	}
	
	public function ajax_save_warehouses_transfer(){
		if($this->RequestHandler->isAjax()){
			
			////////////////////////////////////////////INICIO-CAPTURAR AJAX////////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$documentCode = $this->request->data['documentCode'];
			$movementIdOut = $this->request->data['movementId'];
			//debug($movementIdOut);
			$movementIdIn = '';
			if($documentCode <> ''){
				$movementIdIn = $this->InvMovement->field('InvMovement.id', array(
					'InvMovement.document_code'=>$documentCode,
					'InvMovement.id !='=>$movementIdOut
					));
			}else{
				$documentCode = $this->_generate_document_code_transfer('TRA-ALM');
			}
			//debug($movementIdIn);
			$date = $this->request->data['date'];
			$warehouseOut = $this->request->data['warehouseOut'];
			$warehouseIn = $this->request->data['warehouseIn'];
			
			$description = $this->request->data['description'];
			$movementTypeOut = 3;//Traspaso Almacenes Salida
			$movementTypeIn = 4;//Traspaso Almacenes Entrada
			
			
			////////////////////////////////////////////FIN-CAPTURAR AJAX////////////////////////////////////////////////////////
			
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovementOut = array('date'=>$date, 'inv_warehouse_id'=>$warehouseOut, 'inv_movement_type_id'=>$movementTypeOut, 'description'=>$description);
			$arrayMovementIn = array('date'=>$date, 'inv_warehouse_id'=>$warehouseIn, 'inv_movement_type_id'=>$movementTypeIn, 'description'=>$description);
			
			
			//print_r($arrayMovement);
			
			if($movementIdOut <> ''){//update
				$arrayMovementOut['id'] = $movementIdOut;
				$arrayMovementIn['id'] = $movementIdIn;
			}else{//insert
				$arrayMovementOut['lc_state'] = 'PENDANT';
				$arrayMovementOut['lc_transaction'] = 'CREATE';
				$arrayMovementOut['document_code']=$documentCode;
				$arrayMovementOut['code'] = $this->_generate_code('SAL');
				
				$arrayMovementIn['lc_state'] = 'PENDANT';
				$arrayMovementIn['lc_transaction'] = 'CREATE';
				$arrayMovementIn['document_code']=$documentCode;
				$arrayMovementIn['code'] = $this->_generate_code('ENT');
			}
			
			$dataOut = array('InvMovement'=>$arrayMovementOut, 'InvMovementDetail'=>$arrayItemsDetails);
			$dataIn = array('InvMovement'=>$arrayMovementIn, 'InvMovementDetail'=>$arrayItemsDetails);
			//debug($dataOut);
			//debug($dataIn);
			//print_r($data);
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
			

			////////////////////////////////////////////INICIO-SAVE////////////////////////////////////////////////////////
			if($movementIdOut <> ''){//update
				$this->InvMovement->InvMovementDetail->deleteAll(array('InvMovementDetail.inv_movement_id'=>$movementIdOut));
				$this->InvMovement->InvMovementDetail->deleteAll(array('InvMovementDetail.inv_movement_id'=>$movementIdIn));
				$this->InvMovement->saveAssociated($dataOut);
				$this->InvMovement->saveAssociated($dataIn);
				$strItemsStockOut = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouseOut);
				$strItemsStockIn = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouseIn);
				echo 'modificado|'.$strItemsStockOut.'|'.$strItemsStockIn;
			}else{//insert
				$this->InvMovement->saveAssociated($dataOut);
				$movementIdInsertedOut = $this->InvMovement->id;
				$this->InvMovement->saveAssociated($dataIn);
				$strItemsStockOut = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouseOut);
				$strItemsStockIn = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouseIn);
				echo 'insertado|'.$strItemsStockOut.'|'.$documentCode.'|'.$movementIdInsertedOut.'|'.$strItemsStockIn;
			}
			////////////////////////////////////////////FIN-SAVE////////////////////////////////////////////////////////
		
		}
	}
	
	public function ajax_change_state_approved_warehouses_transfer(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////INICIO-CAPTURAR AJAX////////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$documentCode = $this->request->data['documentCode'];
			$movementIdOut = $this->request->data['movementId'];
			//debug($movementIdOut);
			$movementIdIn = '';
			if($documentCode <> ''){
				$movementIdIn = $this->InvMovement->field('InvMovement.id', array(
					'InvMovement.document_code'=>$documentCode,
					'InvMovement.id !='=>$movementIdOut
					));
			}
			//debug($movementIdIn);
			$date = $this->request->data['date'];
			$warehouseOut = $this->request->data['warehouseOut'];
			$warehouseIn = $this->request->data['warehouseIn'];
			
			$description = $this->request->data['description'];
			$movementTypeOut = 3;//Traspaso Almacenes Salida
			$movementTypeIn = 4;//Traspaso Almacenes Entrada
			////////////////////////////////////////////FIN-CAPTURAR AJAX////////////////////////////////////////////////////////
			
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovementOut = array('date'=>$date, 'inv_warehouse_id'=>$warehouseOut, 'inv_movement_type_id'=>$movementTypeOut, 'description'=>$description);
			$arrayMovementIn = array('date'=>$date, 'inv_warehouse_id'=>$warehouseIn, 'inv_movement_type_id'=>$movementTypeIn, 'description'=>$description);
			
			if($movementIdOut <> ''){//update
				$arrayMovementOut['id'] = $movementIdOut;
				$arrayMovementIn['id'] = $movementIdIn;
				$arrayMovementOut['lc_state'] = 'APPROVED';
				$arrayMovementIn['lc_state'] = 'APPROVED';
				$arrayMovementOut['lc_transaction'] = 'MODIFY';
				$arrayMovementIn['lc_transaction'] = 'MODIFY';
			}
			/*
			else{//insert
				$arrayMovementOut['lc_state'] = 'PENDANT';
				$arrayMovementOut['lc_transaction'] = 'CREATE';
				$arrayMovementOut['document_code']=$documentCode;
				$arrayMovementOut['code'] = $this->_generate_code('SAL');
				
				$arrayMovementIn['lc_state'] = 'PENDANT';
				$arrayMovementIn['lc_transaction'] = 'CREATE';
				$arrayMovementIn['document_code']=$documentCode;
				$arrayMovementIn['code'] = $this->_generate_code('ENT');
			}
			*/
			$dataOut = array('InvMovement'=>$arrayMovementOut, 'InvMovementDetail'=>$arrayItemsDetails);
			$dataIn = array('InvMovement'=>$arrayMovementIn, 'InvMovementDetail'=>$arrayItemsDetails);
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
				
					
			////////////////////////////////////////////INICIO-SAVE////////////////////////////////////////////////////////
			$errorOut=$this->_validateItemsStocksOut($arrayItemsDetails, $warehouseOut);//show errors of movement OUT 'cause movement IN doesn't need validation
			if($errorOut['error'] == 0){
				/*
				if($movementId <> ''){//update
					if($this->InvMovement->InvMovementDetail->deleteAll(array('InvMovementDetail.inv_movement_id'=>$movementId))){
						if($this->InvMovement->saveAssociated($data)){
							$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
							echo 'aprobado|'.$strItemsStock;
						}
					}
				}*/
				if($movementIdOut <> ''){//update
					$this->InvMovement->InvMovementDetail->deleteAll(array('InvMovementDetail.inv_movement_id'=>$movementIdOut));
					$this->InvMovement->InvMovementDetail->deleteAll(array('InvMovementDetail.inv_movement_id'=>$movementIdIn));
					$this->InvMovement->saveAssociated($dataOut);
					$this->InvMovement->saveAssociated($dataIn);
					$strItemsStockOut = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouseOut);
					$strItemsStockIn = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouseIn);
					echo 'aprobado|'.$strItemsStockOut.'|'.$strItemsStockIn;
				}
			}else{
				$strItemsStockIn = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouseIn);
				echo 'error|'.$errorOut['itemsStocks'].'|'. $strItemsStockIn;
			}
			
			////////////////////////////////////////////FIN-SAVE////////////////////////////////////////////////////////		
		}
	}
	
	
	public function ajax_change_state_cancelled_warehouses_transfer(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////INICIO-CAPTURAR AJAX////////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$documentCode = $this->request->data['documentCode'];
			$movementIdOut = $this->request->data['movementId'];
			//$warehouseOut = $this->request->data['warehouseOut'];
			//$warehouseIn = $this->request->data['warehouseIn'];
			
			
			
			$movementIdIn = '';
			if($documentCode <> ''){
				$movementIdIn = $this->InvMovement->field('InvMovement.id', array(
					'InvMovement.document_code'=>$documentCode,
					'InvMovement.id !='=>$movementIdOut
					));
			}
			$warehouseOut = $this->InvMovement->field('InvMovement.inv_warehouse_id', array('InvMovement.id'=>$movementIdOut));
			$warehouseIn = $this->InvMovement->field('InvMovement.inv_warehouse_id', array('InvMovement.id'=>$movementIdIn));
			////////////////////////////////////////////FIN-CAPTURAR AJAX////////////////////////////////////////////////////////
			
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovementOut = array();
			$arrayMovementIn = array();
			
			if($movementIdOut <> ''){//update
				$arrayMovementOut['id'] = $movementIdOut;
				$arrayMovementIn['id'] = $movementIdIn;
				$arrayMovementOut['lc_state'] = 'CANCELLED';
				$arrayMovementIn['lc_state'] = 'CANCELLED';
				$arrayMovementOut['lc_transaction'] = 'MODIFY';
				$arrayMovementIn['lc_transaction'] = 'MODIFY';
			}
			
			$data = array(array('InvMovement'=>$arrayMovementOut), array('InvMovement'=>$arrayMovementIn));
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
				
					
			////////////////////////////////////////////INICIO-SAVE////////////////////////////////////////////////////////
			$errorIn=$this->_validateItemsStocksOut($arrayItemsDetails, $warehouseIn);
			if($errorIn['error'] == 0){
				if($this->InvMovement->saveMany($data)){
					$strItemsStockOut = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouseOut);
					$strItemsStockIn = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouseIn);
					echo 'cancelado|'.$strItemsStockIn.'|'.$strItemsStockOut;
				}
			}else{
				$strItemsStockOut = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouseOut);
				echo 'error|'.$errorIn['itemsStocks'].'|'.$strItemsStockOut;
			}
			////////////////////////////////////////////FIN-SAVE////////////////////////////////////////////////////////		
		}
	}
	
	
	public function ajax_change_state_approved_movement_in(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////INICIO-CAPTURAR AJAX/////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$movementId = $this->request->data['movementId'];
			$warehouse = $this->request->data['warehouse'];

			$date = $this->request->data['date'];
			$description = $this->request->data['description'];
			$movementType = $this->request->data['movementType'];
			$documentCode = $this->request->data['documentCode'];
			////////////////////////////////////////////FIN-CAPTURAR AJAX/////////////////////////////////////////////////////
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'inv_warehouse_id'=>$warehouse, 'inv_movement_type_id'=>$movementType, 'description'=>$description);
			$arrayMovement['lc_state'] = 'APPROVED';
			$arrayMovement['id'] = $movementId;
			if($documentCode <> ''){
				$arrayMovement['document_code']=$documentCode;
			}
			
			$data = array('InvMovement'=>$arrayMovement, 'InvMovementDetail'=>$arrayItemsDetails);
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
			
			////////////////////////////////////////////INICIO-SAVE////////////////////////////////////////////////////////
			if($movementId <> ''){//update
				if($this->InvMovement->InvMovementDetail->deleteAll(array('InvMovementDetail.inv_movement_id'=>$movementId))){
					if($this->InvMovement->saveAssociated($data)){
						$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
						echo 'aprobado|'. $strItemsStock;
					}
				}
			}
			////////////////////////////////////////////FIN-SAVE////////////////////////////////////////////////////////
		}
	}

	public function ajax_change_state_approved_movement_out(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////INICIO-CAPTURAR AJAX/////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$movementId = $this->request->data['movementId'];
			$warehouse = $this->request->data['warehouse'];

			$date = $this->request->data['date'];
			$description = $this->request->data['description'];
			$movementType = $this->request->data['movementType'];
			$documentCode = $this->request->data['documentCode'];
			////////////////////////////////////////////FIN-CAPTURAR AJAX/////////////////////////////////////////////////////
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'inv_warehouse_id'=>$warehouse, 'inv_movement_type_id'=>$movementType, 'description'=>$description);
			$arrayMovement['lc_state'] = 'APPROVED';
			$arrayMovement['id'] = $movementId;
			if($documentCode <> ''){
				$arrayMovement['document_code']=$documentCode;
			}
			
			$data = array('InvMovement'=>$arrayMovement, 'InvMovementDetail'=>$arrayItemsDetails);
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
			
			////////////////////////////////////////////INICIO-SAVE////////////////////////////////////////////////////////
			$error=$this->_validateItemsStocksOut($arrayItemsDetails, $warehouse);
			if($error['error'] == 0){
				if($movementId <> ''){//update
					if($this->InvMovement->InvMovementDetail->deleteAll(array('InvMovementDetail.inv_movement_id'=>$movementId))){
						if($this->InvMovement->saveAssociated($data)){
							$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
							echo 'aprobado|'.$strItemsStock;
						}
					}
				}
			}else{
				echo 'error|'.$error['itemsStocks'];
			}
			
			////////////////////////////////////////////FIN-SAVE////////////////////////////////////////////////////////		
		}
	}
	
	
	
	public function ajax_change_state_cancelled_movement_in(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////INICIO-CAPTURAR AJAX/////////////////////////////////////////////////////
			$movementId = $this->request->data['movementId'];
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			//$warehouse = $this->request->data['warehouse']; //combobox is disabled doesn't send nothing
			$warehouse = $this->InvMovement->field('InvMovement.inv_warehouse_id', array('InvMovement.id'=>$movementId));
			//debug($warehouse);
			////////////////////////////////////////////FIN-CAPTURAR AJAX/////////////////////////////////////////////////////
			$error=$this->_validateItemsStocksOut($arrayItemsDetails, $warehouse);
			if($error['error'] == 0){
				$data = array('id'=>$movementId, 'lc_state'=>'CANCELLED');
				if($this->InvMovement->save($data)){
					$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
					echo 'cancelado|'.$strItemsStock;
				}
			}else{
				echo 'error|'.$error['itemsStocks'];
			}
						
		}
	}
	
	public function ajax_change_state_cancelled_movement_out(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////INICIO-CAPTURAR AJAX/////////////////////////////////////////////////////
			$movementId = $this->request->data['movementId'];
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			//$warehouse = $this->request->data['warehouse']; //combobox is disabled doesn't send nothing
			$warehouseId = $this->InvMovement->field('InvMovement.inv_warehouse_id', array('InvMovement.id'=>$movementId));
			//debug($warehouse);
			////////////////////////////////////////////FIN-CAPTURAR AJAX/////////////////////////////////////////////////////
			//$error=$this->_validateItemsStocksOut($arrayItemsDetails, $warehouseId);
			//if($error == ''){
				$data = array('id'=>$movementId, 'lc_state'=>'CANCELLED');
				if($this->InvMovement->save($data)){
					$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouseId);
					echo 'cancelado|'.$strItemsStock;
				}
			//}else{
			//	echo 'error|'.$error;
			//}
						
		}
	}
	
	public function ajax_update_multiple_stocks(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////INICIO-CAPTURAR AJAX/////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$warehouse = $this->request->data['warehouse'];
			////////////////////////////////////////////FIN-CAPTURAR AJAX////////////////////////////////////////////////////////
			
			////////////////////////////////////////////INICIO-CADENA ITEMS STOCKS///////////////////////////////////////////////
			$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
			echo $strItemsStock;
		}
	}
	//////////////////////////////////////////// END - AJAX /////////////////////////////////////////////////
	
	
	
	//////////////////////////////////////////// START - PRIVATE ///////////////////////////////////////////////
	
	private function _get_movements_details($idMovement){
		$movementDetails = $this->InvMovement->InvMovementDetail->find('all', array(
			'conditions'=>array('InvMovementDetail.inv_movement_id'=>$idMovement),
			'fields'=>array('InvItem.name', 'InvItem.code', 'InvMovementDetail.quantity', 'InvItem.id', 'InvMovement.inv_warehouse_id')
			));
		$formatedMovementDetails = array();
		foreach ($movementDetails as $key => $value) {
			$formatedMovementDetails[$key] = array(
				'itemId'=>$value['InvItem']['id'],
				'item'=>'[ '. $value['InvItem']['code'].' ] '.$value['InvItem']['name'],
				'stock'=> $this->_find_stock($value['InvItem']['id'], $value['InvMovement']['inv_warehouse_id']),//llamar funcion
				'cantidad'=>$value['InvMovementDetail']['quantity']//llamar cantidad
				);
		}
		
		return $formatedMovementDetails;
	}
	
	private function _get_movements_details_without_stock($idMovement){
		$movementDetails = $this->InvMovement->InvMovementDetail->find('all', array(
			'conditions'=>array('InvMovementDetail.inv_movement_id'=>$idMovement),
			'fields'=>array('InvItem.name', 'InvItem.code', 'InvMovementDetail.quantity', 'InvItem.id', 'InvMovement.inv_warehouse_id')
			));
		$formatedMovementDetails = array();
		foreach ($movementDetails as $key => $value) {
			$formatedMovementDetails[$key] = array(
				'itemId'=>$value['InvItem']['id'],
				'item'=>'[ '. $value['InvItem']['code'].' ] '.$value['InvItem']['name'],
				//'stock'=> $this->_find_stock($value['InvItem']['id'], $value['InvMovement']['inv_warehouse_id']),//llamar funcion
				'cantidad'=>$value['InvMovementDetail']['quantity']//llamar cantidad
				);
		}
		
		return $formatedMovementDetails;
	}
	
	private function _get_purchases_details($idPurchase, $idWarehouse, $state){
		$stock = 0;
		$this->loadModel('PurDetail');
		$purchaseDetails = $this->PurDetail->find('all', array(
		'conditions'=>array('PurDetail.pur_purchase_id'=>$idPurchase),
		'fields'=>array('InvItem.name', 'InvItem.code', 'PurDetail.quantity', 'InvItem.id')
		));
		$formatedPurchaseDetails = array();
		foreach ($purchaseDetails as $key => $value) {
			
			if($state == 'nuevo'){
				$stock = $this->_find_stock($value['InvItem']['id'], $idWarehouse);
			}
			$formatedPurchaseDetails[$key] = array(
				'itemId'=>$value['InvItem']['id'],
				'item'=>'[ '. $value['InvItem']['code'].' ] '.$value['InvItem']['name'],
				'cantidadCompra'=>$value['PurDetail']['quantity'],
				'stock'=> $stock,//llamar funcion
				'cantidad'=>$value['PurDetail']['quantity']
				);
		}
		//debug($formatedPurchaseDetails);
		return $formatedPurchaseDetails;
	}
	
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
		$this->InvMovement->InvMovementDetail->unbindModel(array(
			'belongsTo' => array('InvItem')
		));
		//Add association for InvMovementType
		$this->InvMovement->InvMovementDetail->bindModel(array(
			'hasOne'=>array(
				'InvMovementType'=>array(
					'foreignKey'=>false,
					'conditions'=> array('InvMovement.inv_movement_type_id = InvMovementType.id')
				)
				
			)
		));
		//******************************************************************************//
		//Movements
		$movements = $this->InvMovement->InvMovementDetail->find('all', array(
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
	
	private function _generate_code($keyword){
		$period = $this->Session->read('Period.year');
		$movementType = '';
		if($keyword == 'ENT'){$movementType = 'entrada';}
		if($keyword == 'SAL'){$movementType = 'salida';}
		$movements = $this->InvMovement->find('count', array('conditions'=>array('InvMovementType.status'=>$movementType))); // there are duplicates :S, unless there is no movement delete
		$quantity = $movements + 1; 
		//$quantity = $this->InvMovement->getLastInsertID(); //hmm..
		$code = 'MOV-'.$period.'-'.$keyword.'-'.$quantity;
		return $code;
	}
	
	private function _generate_document_code_transfer($keyword){
		$period = $this->Session->read('Period.year');
		if($keyword == 'TRA-ALM'){$idMovementType = 3;}
		$transfers = $this->InvMovement->find('count', array('conditions'=>array('InvMovement.inv_movement_type_id'=>$idMovementType))); 
		$quantity = $transfers + 1; 
		//$quantity = $this->InvMovement->getLastInsertID(); //hmm..
		$code = 'MOV-'.$period.'-'.$keyword.'-'.$quantity;
		return $code;
	}
	
	private function _validateItemsStocksOut($arrayItemsDetails, $warehouse){
		$strItemsStockErrorSuccess = '';
		$cont=0;
		for($i = 0; $i<count($arrayItemsDetails); $i++){
				$updatedStock = $this->_find_stock($arrayItemsDetails[$i]['inv_item_id'], $warehouse);
				if($updatedStock < $arrayItemsDetails[$i]['quantity']){
					$strItemsStockErrorSuccess .= $arrayItemsDetails[$i]['inv_item_id'].'=>error:'.$updatedStock.','; //error
					$cont++;
				}else{
					$strItemsStockErrorSuccess .= $arrayItemsDetails[$i]['inv_item_id'].'=>success:'.$updatedStock.',';//success
				}
		}
		return array('error'=>$cont, 'itemsStocks'=>$strItemsStockErrorSuccess);
	}
	
	private function _createStringItemsStocksUpdated($arrayItemsDetails, $idWarehouse){
		////////////////////////////////////////////INICIO-CREAR CADENA ITEMS STOCK ACUTALIZADOS//////////////////////////////
			$strItemsStock = '';
			for($i = 0; $i<count($arrayItemsDetails); $i++){
				$updatedStock = $this->_find_stock($arrayItemsDetails[$i]['inv_item_id'], $idWarehouse);
				$strItemsStock .= $arrayItemsDetails[$i]['inv_item_id'].'=>'.$updatedStock.',';
			}
			////////////////////////////////////////////FIN-CREAR CADENA ITEMS STOCK ACUTALIZADOS/////////////////////////////////
			return $strItemsStock;
	}
	//////////////////////////////////////////// END - PRIVATE /////////////////////////////////////////////////
	
	

	

	//*******************************************************************************************************//
	/////////////////////////////////////////// END - FUNCTIONS ///////////////////////////////////////////////
	//*******************************************************************************************************//
}
