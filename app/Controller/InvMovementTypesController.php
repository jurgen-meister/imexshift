<?php
App::uses('AppController', 'Controller');
/**
 * InvMovementTypes Controller
 *
 * @property InvMovementType $InvMovementType
 */
class InvMovementTypesController extends AppController {

/**
 *  Layout
 *
 * @var string
 */
//	public $layout = 'bootstrap';

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
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->InvMovementType->recursive = 0;
		$this->set('invMovementTypes', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->InvMovementType->id = $id;
		if (!$this->InvMovementType->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv movement type')));
		}
		$this->set('invMovementType', $this->InvMovementType->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->InvMovementType->create();
			if ($this->InvMovementType->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('inv movement type')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('inv movement type')),
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
		$this->InvMovementType->id = $id;
		if (!$this->InvMovementType->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv movement type')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->InvMovementType->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('inv movement type')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('inv movement type')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->InvMovementType->read(null, $id);
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
		$this->InvMovementType->id = $id;
		if (!$this->InvMovementType->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv movement type')));
		}
		if ($this->InvMovementType->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('inv movement type')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('inv movement type')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
