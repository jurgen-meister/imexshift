<?php
App::uses('AppController', 'Controller');
/**
 * AdmStates Controller
 *
 * @property AdmState $AdmState
 */
class AdmStatesController extends AppController {

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
		$this->AdmState->recursive = 0;
		 $this->paginate = array(
			'order'=>array('AdmController.name'=>'asc'),
			'limit' => 20
		);
		$this->set('admStates', $this->paginate());
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		//Section where the controls of the page are loaded
		$admModules = $this->AdmState->AdmController->AdmModule->find('list', array('order'=>'AdmModule.id'));
		if(count($admModules) != 0)
		{
			$initialModule = key($admModules);
			$admControllers = $this->AdmState->AdmController->find('list', array('conditions'=>array('AdmController.adm_module_id'=>$initialModule)));
			if(count($admControllers) != 0)
			{
				
			}
			else
			{
				$admControllers[""] = "--- Vacio ---";
			}
		}
		else
		{
			$admModules[""] = "--- Vacio ---";
		}
		$this->set(compact('admModules', 'admControllers'));
		
		//Section where information is saved into the database
		if ($this->request->is('post')) {
			$this->AdmState->create();
			if ($this->AdmState->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm state')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm state')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->AdmState->id = $id;
		if (!$this->AdmState->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm state')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['AdmState']['lc_transaction']='MODIFY';
			if ($this->AdmState->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm state')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm state')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmState->read(null, $id);
		}
		//$admControllers = $this->AdmState->AdmController->find('list');
		//$this->set(compact('admControllers'));
		
		//Section where the controls of the page are loaded		
		$admModules = $this->AdmState->AdmController->AdmModule->find('list', array('order'=>'AdmModule.id'));
		if(count($admModules) != 0)
		{
			$initialModule = key($admModules);
			$admControllers = $this->AdmState->AdmController->find('list', array('conditions'=>array('AdmController.adm_module_id'=>$initialModule)));
			if(count($admControllers) != 0)
			{
				
			}
			else
			{
				$admControllers[""] = "--- Vacio ---";
			}
		}
		else
		{
			$admModules[""] = "--- Vacio ---";
		}
		$this->set(compact('admModules', 'admControllers'));
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
		$this->AdmState->id = $id;
		if (!$this->AdmState->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm state')));
		}
		
		//verify if exist child
		$child = $this->AdmState->AdmTransition->find('count', array('conditions'=>array("AdmTransition.adm_state_id"=>$id)));
		if($child > 0){
			$this->Session->setFlash(
				__('Tiene hijos no se puede eliminar'),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-error'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		///////////////
		
		if ($this->AdmState->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm state')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm state')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
	
	public function ajax_list_controllers() {
		if($this->RequestHandler->isAjax())
		{
			$initalModule = strtolower($this->request->data['module']);
			$admControllers = $this->AdmState->AdmController->find('list', array('conditions'=>array('AdmController.adm_module_id'=>$initalModule)));
			if(count($admControllers) == 0)
			{
				$admControllers[""] = "--- Vacio ---";
			}			
			$this->set(compact('admControllers'));
		}	
	}
}