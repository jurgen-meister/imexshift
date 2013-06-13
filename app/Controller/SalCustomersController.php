<?php
App::uses('AppController', 'Controller');
/**
 * SalCustomers Controller
 *
 * @property SalCustomer $SalCustomer
 */
class SalCustomersController extends AppController {

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
	public $components = array('Session');
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->SalCustomer->recursive = 0;
		$this->set('salCustomers', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->SalCustomer->id = $id;
		if (!$this->SalCustomer->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sal customer')));
		}
		$this->set('salCustomer', $this->SalCustomer->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SalCustomer->create();
			if ($this->SalCustomer->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('sal customer')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('sal customer')),
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
		$this->SalCustomer->id = $id;
		if (!$this->SalCustomer->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sal customer')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['SalCustomer']['lc_transaction']='MODIFY';
			if ($this->SalCustomer->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('sal customer')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('sal customer')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->SalCustomer->read(null, $id);
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
		$this->SalCustomer->id = $id;
		if (!$this->SalCustomer->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sal customer')));
		}
		if ($this->SalCustomer->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('sal customer')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('sal customer')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
