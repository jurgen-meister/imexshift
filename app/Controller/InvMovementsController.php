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
/**
 * index method
 *
 * @return void
 */
	
	/*
	public function index() {
		$this->InvMovement->recursive = 0;
		$this->set('invMovements', $this->paginate());
	}
	*/
	
	
	///////////////////////////////////////// My fuctions - BEGIN///////////////////////////////////////////////
	
	public function index_in() {
		
		$this->paginate = array(
		// 'conditions' => $conditions,
		// 'order' => array('InvMovement.code DESC'),
		
		'conditions'=>array(
			 //'InvMovement.lc_state !='=>'LOGIC_DELETE',
			 'InvMovementType.status'=> 'entrada'
		 ),
		//'recursive'=>2,	
		'order'=> array('InvMovement.id'),
		'limit' => 20,
		/*
		'joins' => array(
			array(
				'alias'=>'InvMovementType',
				'table'=>'inv_movement_types',
				'type'=>'INNER',
				'conditions'=>'`InvMovementType`.`id` = `InvMovement`.`inv_movement_type_id`'
			)
		),
		 * 
		 */
		);
		
		//$array = array('uno','dos','tres');
		
		//$this->InvMovement->recursive = 0;
		//debug($this->paginate('InvMovement'));
		//print_r($this->paginate('InvMovement'));
		$this->set('invMovements', $this->paginate('InvMovement'));
		//$this->set('array', $array);
	}

	public function save_in($id = null){
		$invWarehouses = $this->InvMovement->InvWarehouse->find('list');
		$invMovementTypes = $this->InvMovement->InvMovementType->find('list', array(
			'conditions'=>array('InvMovementType.status'=>'entrada', 'InvMovementType.document'=>0)//0 'cause don't have system document
		));
		
		$this->InvMovement->recursive = -1;
		$this->request->data = $this->InvMovement->read(null, $id);
		$date='';
		/*
		if($this->request->data['InvMovement']['date'] <> ''){
			$date = date("d/m/Y", strtotime($this->request->data['InvMovement']['date']));//$this->request->data['InvMovement']['date'];
		}
		*/
		$invMovementDetails = array();
		$documentState = '';
		if($id <> null){
			$date = date("d/m/Y", strtotime($this->request->data['InvMovement']['date']));//$this->request->data['InvMovement']['date'];
			//debug($this->_get_movements_details($id));
			$invMovementDetails = $this->_get_movements_details($id);
			$documentState =$this->request->data['InvMovement']['lc_state'];
		}
		
		$this->set(compact('invMovementTypes', 'invWarehouses', 'id', 'date', 'invMovementDetails', 'documentState'));
		//echo $id;
		//debug($this->request->data);
		
	}
	
	
	public function ajax_initiate_modal_add_item_in(){
		if($this->RequestHandler->isAjax()){
						
			$itemsAlreadySaved = $this->request->data['itemsAlreadySaved'];
			$warehouse = $this->request->data['warehouse'];
			$items = $this->InvMovement->InvMovementDetail->InvItem->find('list', array(
				'conditions'=>array(
					'NOT'=>array('InvItem.id'=>$itemsAlreadySaved)
				),
				'recursive'=>-1,
				//'fields'=>array('InvItem.id', 'CONCAT(InvItem.code, '-', InvItem.name)')
			));
			$firstItemListed = key($items);
			$stock = $this->_find_stock($firstItemListed, $warehouse);
			
			$this->set(compact('items', 'stock'));
		}
	}
	
	private function _get_movements_details($idMovement){
		$movementDetails = $this->InvMovement->InvMovementDetail->find('all', array(
			//'recursive'=>-1,
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

	
	public function ajax_update_stock(){
		if($this->RequestHandler->isAjax()){
			$item = $this->request->data['item'];
			$warehouse = $this->request->data['warehouse'];
			$stock = $this->_find_stock($item, $warehouse);
			$this->set(compact('stock'));
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	
	private function _generate_code($keyword){
		$period = $this->Session->read('Period.year');
		$movementType = 'entrada';
		if($keyword == 'SAL'){$movementType = 'salida';}
		$movements = $this->InvMovement->find('count', array('conditions'=>array('InvMovementType.status'=>$movementType))); // there are duplicates :S, unless there is no movement delete
		$quantity = $movements + 1; 
		//$quantity = $this->InvMovement->getLastInsertID(); //hmm..
		$code = 'MOV-'.$period.'-'.$keyword.'-'.$quantity;
		return $code;
	}
	
	
	public function ajax_save_movement_in(){
		if($this->RequestHandler->isAjax()){
			
			////////////////////////////////////////////INICIO-CAPTURAR AJAX////////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$movementId = $this->request->data['movementId'];
			$date = $this->request->data['date'];
			$warehouse = $this->request->data['warehouse'];
			$movementType = $this->request->data['movementType'];
			$description = $this->request->data['description'];
			////////////////////////////////////////////FIN-CAPTURAR AJAX////////////////////////////////////////////////////////
			
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'inv_warehouse_id'=>$warehouse, 'inv_movement_type_id'=>$movementType, 'description'=>$description);
			
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
	
	public function ajax_change_state_approved_movement_in(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////INICIO-CAPTURAR AJAX/////////////////////////////////////////////////////
			$movementId = $this->request->data['movementId'];
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$warehouse = $this->request->data['warehouse'];
			////////////////////////////////////////////FIN-CAPTURAR AJAX/////////////////////////////////////////////////////
						
			$data = array('id'=>$movementId, 'lc_state'=>'APPROVED');
			if($this->InvMovement->save($data)){
				$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
				echo $strItemsStock;
			}
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
			if($error == ''){
				$data = array('id'=>$movementId, 'lc_state'=>'CANCELLED');
				if($this->InvMovement->save($data)){
					$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
					echo 'cancelado|'.$strItemsStock;
				}
			}else{
				echo 'error|'.$error;
			}
						
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

	private function _validateItemsStocksOut($arrayItemsDetails, $idWarehouse){
		$strItemsStockError = '';
		for($i = 0; $i<count($arrayItemsDetails); $i++){
				$updatedStock = $this->_find_stock($arrayItemsDetails[$i]['inv_item_id'], $idWarehouse);
				if($updatedStock < $arrayItemsDetails[$i]['quantity']){
					$strItemsStockError .= $arrayItemsDetails[$i]['inv_item_id'].'=>'.$updatedStock.',';
				}
		}
		return $strItemsStockError;
	}
	///////////////////////////////////////// My fuctions - FINISH	 ///////////////////////////////////////////////
/*
	public function index_out() {
		
		$this->paginate = array(
		// 'conditions' => $conditions,
		 'order' => array('InvMovement.code DESC'),
		 'conditions'=>array(
			 'InvMovement.lc_transaction !='=>'LOGIC_DELETE',
			 'InvMovementType.status'=> 'salida'
			 ),
		 'limit' => 20
		);
		
		$this->InvMovement->recursive = 0;
		$this->set('invMovements', $this->paginate('InvMovement'));
	}
*/
	
	
/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->InvMovement->id = $id;
		if (!$this->InvMovement->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv movement')));
		}
		$this->set('invMovement', $this->InvMovement->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->InvMovement->create();
			if ($this->InvMovement->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('inv movement')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('inv movement')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$invMovementTypes = $this->InvMovement->InvMovementType->find('list');
		$this->set(compact('invMovementTypes'));
	}
	
/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->InvMovement->id = $id;
		if (!$this->InvMovement->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv movement')));
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->InvMovement->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('inv movement')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('inv movement')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->InvMovement->read(null, $id);
		}
		$invMovementTypes = $this->InvMovement->InvMovementType->find('list');
		$this->set(compact('invMovementTypes'));
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
		$this->InvMovement->id = $id;
		if (!$this->InvMovement->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv movement')));
		}
		if ($this->InvMovement->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('inv movement')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('inv movement')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
