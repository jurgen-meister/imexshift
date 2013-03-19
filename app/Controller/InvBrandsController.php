<?php
App::uses('AppController', 'Controller');
/**
 * InvBrands Controller
 *
 * @property InvBrand $InvBrand
 */
class InvBrandsController extends AppController {

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
		$this->InvBrand->recursive = 0;
		$this->set('invBrands', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->InvBrand->id = $id;
		if (!$this->InvBrand->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv brand')));
		}
		$this->set('invBrand', $this->InvBrand->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->InvBrand->create();
			if ($this->InvBrand->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('inv brand')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('inv brand')),
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
		$this->InvBrand->id = $id;
		if (!$this->InvBrand->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv brand')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->InvBrand->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('inv brand')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('inv brand')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->InvBrand->read(null, $id);
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
		$this->InvBrand->id = $id;
		if (!$this->InvBrand->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv brand')));
		}
		if ($this->InvBrand->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('inv brand')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('inv brand')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
