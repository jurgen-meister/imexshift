<?php
App::uses('AppController', 'Controller');
/**
 * InvWarehouses Controller
 *
 * @property InvWarehouse $InvWarehouse
 */
class InvWarehousesController extends AppController {

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
		$this->InvWarehouse->recursive = 0;
		$this->set('invWarehouses', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->InvWarehouse->id = $id;
		if (!$this->InvWarehouse->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv warehouse')));
		}
		$this->set('invWarehouse', $this->InvWarehouse->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->InvWarehouse->create();
			if ($this->InvWarehouse->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('inv warehouse')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('inv warehouse')),
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
		$this->InvWarehouse->id = $id;
		if (!$this->InvWarehouse->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv warehouse')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->InvWarehouse->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('inv warehouse')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('inv warehouse')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->InvWarehouse->read(null, $id);
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
		$this->InvWarehouse->id = $id;
		if (!$this->InvWarehouse->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv warehouse')));
		}
		if ($this->InvWarehouse->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('inv warehouse')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('inv warehouse')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
