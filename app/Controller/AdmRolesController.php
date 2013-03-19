<?php
App::uses('AppController', 'Controller');
/**
 * AdmRoles Controller
 *
 * @property AdmRole $AdmRole
 */
class AdmRolesController extends AppController {

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
		$this->AdmRole->recursive = 0;
		$this->set('admRoles', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->AdmRole->id = $id;
		if (!$this->AdmRole->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm role')));
		}
		$this->set('admRole', $this->AdmRole->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->AdmRole->create();
			if ($this->AdmRole->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm role')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm role')),
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
		$this->AdmRole->id = $id;
		if (!$this->AdmRole->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm role')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['AdmRole']['lc_transaction']='MODIFY';
			if ($this->AdmRole->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm role')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm role')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmRole->read(null, $id);
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
		$this->AdmRole->id = $id;
		if (!$this->AdmRole->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm role')));
		}
		if ($this->AdmRole->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm role')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm role')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
