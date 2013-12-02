<?php
App::uses('AppController', 'Controller');
/**
 * AdmTransitions Controller
 *
 * @property AdmTransition $AdmTransition
 */
class AdmTransitionsController extends AppController {

/**
 *  Layout
 *
 * @var string
 */
//	public $layout = 'default';

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
/*
	public  function isAuthorized($user){
		return $this->Permission->isAllowed($this->name, $this->action, $this->Session->read('Permission.'.$this->name));
	}
*/
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->AdmTransition->bindModel(array(
			'hasOne'=>array(
				'AdmController'=> array(
					'foreignKey' => false,
					'conditions' => array('AdmTransaction.adm_controller_id = AdmController.id')
				)
			)
		));
		$this->paginate = array(
			'order'=>array('AdmController.name'=>'ASC'),
			'limit' => 20,
		);
		$this->AdmTransition->recursive = 0;
		$this->set('admTransitions', $this->paginate('AdmTransition'));
	}

/**
 * add method
 *
 * @return void
 */
	
	public function life_cycles(){
//		$admModules = $this->AdmTransition->AdmAction->AdmController->AdmModule->find('list', array('order'=>'AdmModule.id'));
//		$initialModule =  key($admModules);
//		$admControllers = $this->AdmTransition->AdmAction->AdmController->find('list', array('conditions'=>array('AdmController.adm_module_id'=>$initialModule)));
//		
//		//$initialController = Inflector::camelize(reset($admControllers));
//		//$idController = key($admControllers);
//		//$admActions = $this->_getActions($initialController, $idController);
//		if(count($admControllers) == 0){
//				$admControllers[""]="--- Vacio ---";
//				//$admActions = array();
//		}
//		$this->set(compact('admModules', 'admControllers'));
		
	}
	
	public function ajax_list_controllers(){
		if($this->RequestHandler->isAjax()){
			$initialModule =  $this->request->data['module'];
			$admControllers = $this->AdmTransition->AdmAction->AdmController->find('list', array('conditions'=>array('AdmController.adm_module_id'=>$initialModule)));
		
			if(count($admControllers) == 0){
					$admControllers[""]="--- Vacio ---";
					//$admActions = array();
			}
			
			$this->set(compact('admControllers'));
		}else{
			$this->redirect($this->Auth->logout());
		}
	}
	
	public function add() {
		if ($this->request->is('post')) {
			$this->AdmTransition->create();
			if ($this->AdmTransition->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm transition')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm transition')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$admStates = $this->_createComposeStatesList();//$this->AdmTransition->AdmState->find('list');
		$admTransactions = $this->_createComposeTransactionList();//$this->AdmTransition->AdmTransaction->find('list');
        $admFinalStates = $this->_createComposeStatesList();//$this->AdmTransition->AdmState->find('list');
		$this->set(compact('admStates', 'admTransactions', 'admFinalStates'));
		//debug($this->_createComposeStatesList());
		//debug($this->_createComposeTransactionList());
	}

	
	private function _createComposeStatesList(){
		$admStates = $this->AdmTransition->AdmState->find('all', array(
			'fields'=>array('AdmState.id', 'AdmState.name', 'AdmController.name'),
			'order'=>array('AdmController.name'=>'ASC'),
			'recursive'=>0
		));
		$array=array();
		for($i=0; $i<count($admStates); $i++){
			$array[$admStates[$i]['AdmState']['id']]=$admStates[$i]['AdmController']['name'].'->'.$admStates[$i]['AdmState']['name'];
		}
		return $array;
	}
	
	private function _createComposeTransactionList(){
		$admTransaction = $this->AdmTransition->AdmTransaction->find('all', array(
			'fields'=>array('AdmTransaction.id', 'AdmTransaction.name', 'AdmController.name'),
			'order'=>array('AdmController.name'=>'ASC'),
			'recursive'=>0
		));
		$array=array();
		for($i=0; $i<count($admTransaction); $i++){
			$array[$admTransaction[$i]['AdmTransaction']['id']]=$admTransaction[$i]['AdmController']['name'].'->'.$admTransaction[$i]['AdmTransaction']['name'];
		}
		return $array;
	}
	/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->AdmTransition->id = $id;
		if (!$this->AdmTransition->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm transition')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
                    $this->request->data['AdmTransition']['lc_action']='MODIFY';
			if ($this->AdmTransition->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm transition')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm transition')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmTransition->read(null, $id);
		}
		$admStates = $this->_createComposeStatesList();//$this->AdmTransition->AdmState->find('list');
		$admTransactions = $this->_createComposeTransactionList();//$this->AdmTransition->AdmTransaction->find('list');
        $admFinalStates = $this->_createComposeStatesList();//$this->AdmTransition->AdmState->find('list');
		$this->set(compact('admStates', 'admTransactions', 'admFinalStates'));
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
		$this->AdmTransition->id = $id;
		if (!$this->AdmTransition->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm transition')));
		}
		if ($this->AdmTransition->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm transition')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm transition')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
