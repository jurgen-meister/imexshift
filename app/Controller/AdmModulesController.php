<?php
App::uses('AppController', 'Controller');
/**
 * AdmModules Controller
 *
 * @property AdmModule $AdmModule
 */
class AdmModulesController extends AppController {

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
		$this->AdmModule->recursive = 0;
		$this->set('admModules', $this->paginate());
	}

	
/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->AdmModule->id = $id;
		if (!$this->AdmModule->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm module')));
		}
		$this->set('admModule', $this->AdmModule->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->AdmModule->create();
			if ($this->AdmModule->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm module')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm module')),
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
		$this->AdmModule->id = $id;
		if (!$this->AdmModule->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm module')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['AdmModule']['lc_transaction']='MODIFY';
			if ($this->AdmModule->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm module')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm module')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmModule->read(null, $id);
		}
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
		$this->AdmModule->id = $id;
		if (!$this->AdmModule->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm module')));
		}
		if ($this->AdmModule->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm module')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm module')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
