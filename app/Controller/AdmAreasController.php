<?php
App::uses('AppController', 'Controller');
/**
 * AdmAreas Controller
 *
 * @property AdmArea $AdmArea
 */
class AdmAreasController extends AppController {

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
		$this->AdmArea->recursive = 0;
		$this->set('admAreas', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->AdmArea->id = $id;
		if (!$this->AdmArea->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm area')));
		}
		$this->set('admArea', $this->AdmArea->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->AdmArea->create();
			if ($this->AdmArea->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm area')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm area')),
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
		$this->AdmArea->id = $id;
		if (!$this->AdmArea->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm area')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->AdmArea->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm area')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm area')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmArea->read(null, $id);
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
		$this->AdmArea->id = $id;
		if (!$this->AdmArea->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm area')));
		}
		if ($this->AdmArea->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm area')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm area')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
