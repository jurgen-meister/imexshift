<?php
App::uses('AppController', 'Controller');
/**
 * InvDocumentTypes Controller
 *
 * @property InvDocumentType $InvDocumentType
 */
class InvDocumentTypesController extends AppController {

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
		$this->InvDocumentType->recursive = 0;
		$this->set('invDocumentTypes', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->InvDocumentType->id = $id;
		if (!$this->InvDocumentType->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv document type')));
		}
		$this->set('invDocumentType', $this->InvDocumentType->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->InvDocumentType->create();
			if ($this->InvDocumentType->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('inv document type')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('inv document type')),
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
		$this->InvDocumentType->id = $id;
		if (!$this->InvDocumentType->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv document type')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->InvDocumentType->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('inv document type')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('inv document type')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->InvDocumentType->read(null, $id);
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
		$this->InvDocumentType->id = $id;
		if (!$this->InvDocumentType->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv document type')));
		}
		if ($this->InvDocumentType->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('inv document type')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('inv document type')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
