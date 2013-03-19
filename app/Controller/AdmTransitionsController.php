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
	public  function isAuthorized($user){
		return $this->Permission->isAllowed($this->name, $this->action, $this->Session->read('Permission.'.$this->name));
	}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->AdmTransition->recursive = 0;
		$this->set('admTransitions', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->AdmTransition->id = $id;
		if (!$this->AdmTransition->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm transition')));
		}
		$this->set('admTransition', $this->AdmTransition->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	
	public function life_cycles(){
		$admModules = $this->AdmTransition->AdmAction->AdmController->AdmModule->find('list', array('order'=>'AdmModule.id'));
		$initialModule =  key($admModules);
		$admControllers = $this->AdmTransition->AdmAction->AdmController->find('list', array('conditions'=>array('AdmController.adm_module_id'=>$initialModule)));
		
		//$initialController = Inflector::camelize(reset($admControllers));
		//$idController = key($admControllers);
		//$admActions = $this->_getActions($initialController, $idController);
		if(count($admControllers) == 0){
				$admControllers[""]="--- Vacio ---";
				//$admActions = array();
		}
		$this->set(compact('admModules', 'admControllers'));
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
		$admStates = $this->AdmTransition->AdmState->find('list');
		$admTransactions = $this->AdmTransition->AdmTransaction->find('list');
        $admFinalStates = $this->AdmTransition->AdmState->find('list');
		$this->set(compact('admStates', 'admTransactions', 'admFinalStates'));
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
		$admStates = $this->AdmTransition->AdmState->find('list');
		$admTransactions = $this->AdmTransition->AdmTransaction->find('list');
        $admFinalStates = $this->AdmTransition->AdmState->find('list');
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
