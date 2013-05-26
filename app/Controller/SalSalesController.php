<?php
App::uses('AppController', 'Controller');
/**
 * SalSales Controller
 *
 * @property SalSale $SalSale
 */
class SalSalesController extends AppController {

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
		$this->SalSale->recursive = 0;
		$this->set('salSales', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->SalSale->id = $id;
		if (!$this->SalSale->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sal sale')));
		}
		$this->set('salSale', $this->SalSale->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SalSale->create();
			if ($this->SalSale->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('sal sale')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('sal sale')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$salEmployees = $this->SalSale->SalEmployee->find('list');
		$salTaxNumbers = $this->SalSale->SalTaxNumber->find('list');
		$this->set(compact('salEmployees', 'salTaxNumbers'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->SalSale->id = $id;
		if (!$this->SalSale->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sal sale')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SalSale->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('sal sale')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('sal sale')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->SalSale->read(null, $id);
		}
		$salEmployees = $this->SalSale->SalEmployee->find('list');
		$salTaxNumbers = $this->SalSale->SalTaxNumber->find('list');
		$this->set(compact('salEmployees', 'salTaxNumbers'));
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
		$this->SalSale->id = $id;
		if (!$this->SalSale->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sal sale')));
		}
		if ($this->SalSale->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('sal sale')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('sal sale')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
