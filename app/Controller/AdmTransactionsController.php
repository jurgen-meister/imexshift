<?php
App::uses('AppController', 'Controller');
/**
 * AdmTransactions Controller
 *
 * @property AdmTransaction $AdmTransaction
 */
class AdmTransactionsController extends AppController {

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
	public  function isAuthorized($user){
		return $this->Permission->isAllowed($this->name, $this->action, $this->Session->read('Permission.'.$this->name));
	}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->AdmTransaction->recursive = 0;
		$this->set('admTransactions', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->AdmTransaction->id = $id;
		if (!$this->AdmTransaction->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm transaction')));
		}
		$this->set('admTransaction', $this->AdmTransaction->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		//Section where the controls of the page are loaded		
		$admModules = $this->AdmTransaction->AdmController->AdmModule->find('list', array('order'=>'AdmModule.id'));
		if(count($admModules) != 0)
		{
			$initialModule = key($admModules);
			$admControllers = $this->AdmTransaction->AdmController->find('list', array('conditions'=>array('AdmController.adm_module_id'=>$initialModule)));
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
			$this->AdmTransaction->create();			
			if ($this->AdmTransaction->save($this->request->data)) {
			//if ($this->AdmTransaction->save($this->request->data, array('fieldList' => array('adm_controller_id', 'name', 'description', 'sentence')))) {				
				$this->Session->setFlash(
					__('The %s has been saved', __('adm transaction')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm transaction')),
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
		$this->AdmTransaction->id = $id;		
		if (!$this->AdmTransaction->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm transaction')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {			
			$this->request->data['AdmTransaction']['lc_transaction']='MODIFY';
			if ($this->AdmTransaction->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm transaction')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm transaction')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmTransaction->read(null, $id);
		}
//		$admControllers = $this->AdmTransaction->AdmController->find('list');
//		$this->set(compact('admControllers'));
		
		//Section where the controls of the page are loaded		
		$admModules = $this->AdmTransaction->AdmController->AdmModule->find('list', array('order'=>'AdmModule.id'));
		if(count($admModules) != 0)
		{
			$initialModule = key($admModules);
			$admControllers = $this->AdmTransaction->AdmController->find('list', array('conditions'=>array('AdmController.adm_module_id'=>$initialModule)));
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
		$this->AdmTransaction->id = $id;
		if (!$this->AdmTransaction->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm transaction')));
		}
		if ($this->AdmTransaction->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm transaction')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm transaction')),
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
			$admControllers = $this->AdmTransaction->AdmController->find('list', array('conditions'=>array('AdmController.adm_module_id'=>$initalModule)));
			if(count($admControllers) == 0)
			{
				$admControllers[""] = "--- Vacio ---";
			}			
			$this->set(compact('admControllers'));
		}	
	}
}
