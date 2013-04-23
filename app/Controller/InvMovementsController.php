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
		/*
		'conditions'=>array(
			 'InvMovement.lc_transaction !='=>'LOGIC_DELETE',
			 'InvMovementType.status'=> 'entrada'
			 ),
		 * 
		 */
		//'recursive'=>2,	
		//'order'=> array('InvMovement.id DESC'),
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
		$this->set('invMovements', $this->paginate('InvMovement'));
		//$this->set('array', $array);
	}

	public function save_in(){
		$invWarehouses = $this->InvMovement->InvWarehouse->find('list');
		$invMovementTypes = $this->InvMovement->InvMovementType->find('list');
		$this->set(compact('invMovementTypes', 'invWarehouses'));
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
			//-1//Enviar via request ajax todos los datos del formulario
			if($this->Session->check('movement_in')){
				//$array = array();
				$array = $this->Session->read('movement_in');
				$strError = '';
				//0// hacer esta validacion solo si es lc_state = cancelled_movement_in
				if(count($array) > 0){
					//$this->Session->write('movement_in.'.$idItem.'.quantity', $quantity);
					foreach ($array as $value) {
						//1//busco stock de idItem y comparo con anidado
						//2//si comparacion no es buena lo guardo en una cadena separada por _ para que lea jquery
						//3//en jquery marca con class="error" la row con problema y mando una alerta
					}
				//0//	
				
				//4//si la cadena error es vacia entonces se puede guardar, sino mandar cadena -> Coded
					if($strError == ''){
						//5//Save => add or edit (ver si hace automatico con el id, porque la otra vez no queria funcionar :S)
					}
					
				}
			}
		}
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
