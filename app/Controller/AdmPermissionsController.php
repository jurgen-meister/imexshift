<?php
App::uses('AppController', 'Controller');
/**
 * AdmPermissions Controller
 *
 * @property AdmPermission $AdmPermission
 */
class AdmPermissionsController extends AppController {

/**
 *  Layout
 *
 * @var string
 */
	public $layout = 'bootstrap';

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('TwitterBootstrap.BootstrapHtml', 'TwitterBootstrap.BootstrapForm', 'TwitterBootstrap.BootstrapPaginator');
/**
 * Components
 *
 * @var array
 */
	public $components = array('Session');
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->AdmPermission->recursive = 0;
		$this->set('admPermissions', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->AdmPermission->id = $id;
		if (!$this->AdmPermission->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm permission')));
		}
		$this->set('admPermission', $this->AdmPermission->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->AdmPermission->create();
			if ($this->AdmPermission->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm permission')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm permission')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$admRoles = $this->AdmPermission->AdmRole->find('list');
		$admActions = $this->AdmPermission->AdmAction->find('list');
		$this->set(compact('admRoles', 'admActions'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->AdmPermission->id = $id;
		if (!$this->AdmPermission->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm permission')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->AdmPermission->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm permission')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm permission')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmPermission->read(null, $id);
		}
		$admRoles = $this->AdmPermission->AdmRole->find('list');
		$admActions = $this->AdmPermission->AdmAction->find('list');
		$this->set(compact('admRoles', 'admActions'));
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
		$this->AdmPermission->id = $id;
		if (!$this->AdmPermission->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm permission')));
		}
		if ($this->AdmPermission->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm permission')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm permission')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
