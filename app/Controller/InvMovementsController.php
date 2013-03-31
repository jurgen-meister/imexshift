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
//	public $layout = 'bootstrap';

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
	public function index() {
		
	$this->paginate = array(
    // 'conditions' => $conditions,
     'order' => array('InvMovement.code DESC'),
	 'conditions'=>array('InvMovement.lc_transaction !='=>'LOGIC_DELETE'),
     'limit' => 20
	);
		
		$this->InvMovement->recursive = 0;
		$this->set('invMovements', $this->paginate('InvMovement'));
	}

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
			
			//************
			//validation strat
			//************ 
		/*		 $this->InvMovement->validate['quantity']['higherThanStock'] = array(
					  ); 
				 $this->validate()
		 * 
		 */
			//$this->InvMovement->validates(array('fieldList' => array('quantity'=>array('notempty'))));
			//unset($this->InvMovement->validate['quantity']['higherThanStock']);
				// valid
			//************
			//validation end
			//************ 
			
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
		$invItems = $this->InvMovement->InvItem->find('list');
		$invWarehouses = $this->InvMovement->InvWarehouse->find('list');
		$invMovementTypes = $this->InvMovement->InvMovementType->find('list');
		$this->set(compact('invItems', 'invWarehouses', 'invMovementTypes'));
	}

	public function add_out(){
		if ($this->request->is('post')) {
			$this->InvMovement->create();
			$arrayDate =explode('/',$this->request->data['InvMovement']['date_in']);
			$this->request->data['InvMovement']['date']=array(
				'month'=>trim($arrayDate[1]),
				'day'=>trim($arrayDate[0]),
				'year'=>trim($arrayDate[2])
			);
			
			$this->request->data['InvMovement']['code'] = $this->_generate_code();
			///////////////////////////
			$this->request->data['InvMovement']['creator'] = $this->Auth->user('id');
			///////////////////////////
			if($this->_verify_document($this->request->data['InvMovement']['inv_movement_type_id'])){
				$this->request->data['InvMovement']['document'] = 11;
				//debo buscar su documento en su respectiva tabla varios if case
			}else{
				$this->request->data['InvMovement']['document'] = 0;
			}
			//debug($this->_verify_document($this->request->data['InvMovement']['inv_movement_type_id']));
			
			//debug($this->request->data);
			
			//LAST VALIDATION
			//Validates if stock wasn't changed by other users before saving the movement
			//$stockFinalCheck = $this->_find_available_quantity($this->request->data['InvMovement']['inv_item_id'], $this->request->data['InvMovement']['inv_warehouse_id']);
			//debug($stockFinalCheck);
			/*
			if($this->request->data['InvMovement']['quantity'] > $stockFinalCheck ){
				////////////////////////////////////////////////////////
				$this->Session->setFlash(
					__('No se guardo, probablemente el stock se modifico por otro usuario antes de guardar su movimiento'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
				//$this->redirect(array('action' => 'add_out'));
			 
				////////////////////////////////////////////////////////
			}/*
			else{
			 * 
			 */
				////////////////////////////////////////////////////////
				//echo "SE PUEDE GUARDAR TODO JOIA";
				if ($this->InvMovement->save($this->request->data)) {

					$this->Session->setFlash(
						__('MOVIMIENTO GUARDADO'),
						'alert',
						array(
							'plugin' => 'TwitterBootstrap',
							'class' => 'alert-success'
						)
					);
					$this->redirect(array('action' => 'index'));
				} else {
					//$avaliableQuantity=$this->request->data['InvMovement']['avaliable'];
					//$this->set(compact('avaliableQuantity'));
					$this->Session->setFlash(
						__('No se guardo, vuelva a intentar'),
						'alert',
						array(
							'plugin' => 'TwitterBootstrap',
							'class' => 'alert-error'
						)
					);
					//$this->redirect(array('action' => 'add_out'));
				}
				////////////////////////////////////////////////////////
			}
			/*
			
			*/
			
		//};
			
		
		$invItems = $this->InvMovement->InvItem->find('list');
		$firstItem = key($invItems);
		$invWarehouses = $this->InvMovement->InvWarehouse->find('list');
		$firstWarehouse = key($invWarehouses);
		$invMovementTypes = $this->InvMovement->InvMovementType->find('list', array('conditions'=>array('status'=>'salida')));
		$avaliableQuantity = $this->_find_available_quantity($firstItem, $firstWarehouse);
		if ($this->request->is('post')) {
			$avaliableQuantity = $this->_find_available_quantity($this->request->data['InvMovement']['inv_item_id'], $this->request->data['InvMovement']['inv_warehouse_id']);
		}
		$this->set(compact('invItems', 'invWarehouses', 'invMovementTypes', 'avaliableQuantity'));
	}
	
	public function add_in(){
		if ($this->request->is('post')) {
			$this->InvMovement->create();
			$arrayDate =explode('/',$this->request->data['InvMovement']['date_in']);
			$this->request->data['InvMovement']['date']=array(
				'month'=>trim($arrayDate[1]),
				'day'=>trim($arrayDate[0]),
				'year'=>trim($arrayDate[2])
			);
			
			$this->request->data['InvMovement']['code'] = $this->_generate_code();
			///////////////////////////
			$this->request->data['InvMovement']['creator'] = $this->Auth->user('id');
			///////////////////////////
			if($this->_verify_document($this->request->data['InvMovement']['inv_movement_type_id'])){
				$this->request->data['InvMovement']['document'] = 11;
				//debo buscar su documento en su respectiva tabla varios if case
			}else{
				$this->request->data['InvMovement']['document'] = 0;
			}
			//debug($this->_verify_document($this->request->data['InvMovement']['inv_movement_type_id']));
			
			//debug($this->request->data);
			
			//*****Here I disable this specific rule in the model****//
			unset($this->InvMovement->validate['quantity']['higherThanStock']);
			$this->request->data['InvMovement']['status'] = "IN"; //With this I disable beforesave in the model
			//*************************************************************//
			if ($this->InvMovement->save($this->request->data)) {
				
				$this->Session->setFlash(
					__('Guardado con exito'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('No se pudo guardar, intentelo de nuevo'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
		
			}
			 
		}
		$invItems = $this->InvMovement->InvItem->find('list');
		$firstItem = key($invItems);
		$invWarehouses = $this->InvMovement->InvWarehouse->find('list');
		$firstWarehouse = key($invWarehouses);
		$invMovementTypes = $this->InvMovement->InvMovementType->find('list', array('conditions'=>array('status'=>'entrada')));
		
		$avaliableQuantity = $this->_find_available_quantity($firstItem, $firstWarehouse);
		
		$this->set(compact('invItems', 'invWarehouses', 'invMovementTypes', 'avaliableQuantity'));
		//debug($this->_list_items_by_movement_type());
	}
	
	public function ajax_update_avaliable_quantity(){
		if($this->RequestHandler->isAjax()){
			$idWarehouse = $this->request->data['warehouse'];		
			$idItem = $this->request->data['item'];
			$avaliableQuantity = $this->_find_available_quantity($idItem, $idWarehouse);
			$this->set(compact('avaliableQuantity'));
		}
	}
	
	public function ajax_list_items_by_movement_type(){
		if($this->RequestHandler->isAjax()){
			$idWarehouse = $this->request->data['warehouse'];	
			$idMovementType = $this->request->data['movement_type'];
			$array = $this->_list_items_by_movement_type();
			//idItem = must catch it from array
			//$quantity = $this->_find_available_quantity($idItem, $idWarehouse)
			/////Resume//
			/*
			 IF MovementType has document = 1 then
			 1.- find al documents and their items, show in combobox like this:
			 CODE-->ITEM
			 ELSE
			 2.- list all items
			 END IF
			  
			 3.- Update new quantity according to warehouse and item in the textBox
			  
			 4.- Update both in the view
			 */
		}
	}
	
	public function ajax_validate_movement_out(){
		if($this->RequestHandler->isAjax()){
			$idWarehouse = $this->request->data['warehouse'];	
			$idItem = $this->request->data['item'];
			$insertedQuantity = $this->request->data['quantity'];
			$avalaibleQuantity = $this->_find_available_quantity($idItem, $idWarehouse);
			$answer = 'ok';
			if($insertedQuantity > $avalaibleQuantity){
				$answer = "higher";
			}
			
			echo $answer;
		}
		
	}
	
	
	private function _list_items_by_movement_type(){
		//must list all items according to the document, else list all items
		//I can't finish this until we have a sale o purchase form
		$array=$this->InvMovement->find('all', array(
			'conditions'=>array()
		));
		debug($array);
	}
	
	private function _find_available_quantity($idItem, $idWarehouse){
		/*
		$this->InvMovement->unbindModel(
				array('belongsTo' => array('InvMovementType')
		));
		
		$this->InvMovement->bindModel(array(
			'hasOne' => array(
				'InvMovementType' => array(
					'foreignKey' => false,
					'conditions' => array('InvMovement.inv_movement_type_id = InvMovementType.id')
				)
			)
		));
		*/
		$stockIns = $this->InvMovement->find('all', array(
			'conditions'=>array('InvMovement.inv_item_id'=> $idItem,'InvMovement.inv_warehouse_id'=>$idWarehouse, 'InvMovement.lc_transaction !='=>'LOGIC_DELETE', 'InvMovementType.status' => 'entrada'),
			'fields'=>array('id', 'quantity')
		));
		
		$stockInsCleaned = $this->_clean_nested_arrays($stockIns);
		
		$stockOuts = $this->InvMovement->find('all', array(
			'conditions'=>array('InvMovement.inv_item_id'=> $idItem,'InvMovement.inv_warehouse_id'=>$idWarehouse, 'InvMovement.lc_transaction !='=>'LOGIC_DELETE', 'InvMovementType.status' => 'salida'),
			//'contain' => array('InvMovement'=>array('InvMovementType')),
			'fields'=>array('id', 'quantity')
		));
		
		$stockOutsCleaned = $this->_clean_nested_arrays($stockOuts);
		
		$add = array_sum($stockInsCleaned);
		//debug($add);
		$sub = array_sum($stockOutsCleaned);
		//debug($sub);
		$availableQuantity = $add - $sub;
		
		//debug($availableQuantity);
		
		//$sum = array_sum($availableQuantity);
		return $availableQuantity;
	}
	
	private function _clean_nested_arrays($array){
		$clean = array();
		foreach ($array as $key => $value) {
			$clean[$key] = $value['InvMovement']['quantity'];
		}
		return $clean;
	}
	
	private function _count_warehouse_movements(){
		$movements = $this->InvMovement->find('count');
		return $movements;
	}
	
	private function _generate_code(){
		$period = $this->Session->read('Period.year');
		$quantity = $this->_count_warehouse_movements() + 1; // there are duplicates :S, unless there is no movement delete
		//$quantity = $this->InvMovement->getLastInsertID(); //hmm..
		$code = 'MOV-'.$period.'-'.$quantity;
		return $code;
	}

	private function _verify_document($idMovementType){
		$quantity = $this->InvMovement->InvMovementType->find('count', array(
			'conditions'=>array('InvMovementType.id'=>$idMovementType, 'InvMovementType.document'=>1)
		));
		if($quantity > 0){
			return true;
		}
		return false;
	}
	
	public function ajax_list_items(){
		echo "hello world";
	}
	
/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit_in($id = null) {
		$this->InvMovement->id = $id;
		if (!$this->InvMovement->exists()) {
			throw new NotFoundException(__('Movimiento invalido'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->InvMovement->save($this->request->data)) {
				$this->Session->setFlash(
					__('Se guardo con exito'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('No se pudo guardar, intentelo de nuevo'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
		$this->request->data = $this->InvMovement->read(null, $id);
		$invItems = $this->InvMovement->InvItem->find('list');
		$invWarehouses = $this->InvMovement->InvWarehouse->find('list');
		$invMovementTypes = $this->InvMovement->InvMovementType->find('list');
		$firstItem = $this->request->data['InvMovement']['inv_item_id'];
		$firstWarehouse = $this->request->data['InvMovement']['inv_warehouse_id'];
		$avaliableQuantity = $this->_find_available_quantity($firstItem, $firstWarehouse);
		$this->set(compact('invItems', 'invWarehouses', 'invMovementTypes', 'avaliableQuantity'));
		}
		
	}

	public function edit_out($id = null) {
		$this->InvMovement->id = $id;
		if (!$this->InvMovement->exists()) {
			throw new NotFoundException(__('Movimiento invalido'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->InvMovement->save($this->request->data)) {
				$this->Session->setFlash(
					__('Se guardo con exito'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('No se pudo guardar, intentelo de nuevo'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			
		$this->request->data = $this->InvMovement->read(null, $id);
		//debug($this->request->data);
		$invItems = $this->InvMovement->InvItem->find('list');
		$invWarehouses = $this->InvMovement->InvWarehouse->find('list');
		$invMovementTypes = $this->InvMovement->InvMovementType->find('list');
		$firstItem = $this->request->data['InvMovement']['inv_item_id'];
		$firstWarehouse = $this->request->data['InvMovement']['inv_warehouse_id'];
		$avaliableQuantity = $this->_find_available_quantity($firstItem, $firstWarehouse);
		$this->set(compact('invItems', 'invWarehouses', 'invMovementTypes', 'avaliableQuantity'));
		}
		
	}
/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		///////////////// Si no es post hace excepcion ////////////////////////
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		////////////////////////Si no existe no borra////////////////////////////////////
		$this->InvMovement->id = $id; // agrega id 
		if (!$this->InvMovement->exists()) {
			throw new NotFoundException(__('Movimiento invalido'));
		}
		///////////////////////// si borra, corta///////////////////
		/*
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
		 */
		//Aqui hare borrado logico con update 
		//$this->InvMovement->set('lc_transaction', 'LOGIC DELETE');
		//$this->request->data['InvMovement']['lc_transaction'] = 'LOGIC DELETE';
		//$this->request->data['InvMovement']['id'] = $id;
		//debug($this->request->data);
		
		$update = $this->InvMovement->updateAll(
				array('InvMovement.lc_transaction'=>"'LOGIC_DELETE'"),
				array('InvMovement.id'=>$id)
		);
		
		//debug($update);
		
		
		if($update){
			$this->Session->setFlash(
				__('Se elimino correctamente'),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		
		
		//$this->InvMovement->set($id);
		/////////////////////////// Si no borra////////////////////////
		$this->Session->setFlash(
			__('No se pudo eliminar'),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
		 
		/////////////////////////////////////////////////
	}
}
