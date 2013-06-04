<?php
App::uses('AppController', 'Controller');
/**
 * AdmLogins Controller
 *
 * @property AdmLogin $AdmLogin
 */
class AdmLoginsController extends AppController {

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
		$this->AdmLogin->recursive = 0;
		$this->set('admLogins', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->AdmLogin->id = $id;
		if (!$this->AdmLogin->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm login')));
		}
		$this->set('admLogin', $this->AdmLogin->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->AdmLogin->create();
			if ($this->AdmLogin->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm login')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm login')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$admUsers = $this->AdmLogin->AdmUser->find('list');
		$this->set(compact('admUsers'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->AdmLogin->id = $id;
		if (!$this->AdmLogin->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm login')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->AdmLogin->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm login')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm login')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmLogin->read(null, $id);
		}
		$admUsers = $this->AdmLogin->AdmUser->find('list');
		$this->set(compact('admUsers'));
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
		$this->AdmLogin->id = $id;
		if (!$this->AdmLogin->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm login')));
		}
		if ($this->AdmLogin->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm login')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm login')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
