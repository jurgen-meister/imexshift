<?php
App::uses('AppController', 'Controller');
/**
 * AdmUserRestrictions Controller
 *
 * @property AdmUserRestriction $AdmUserRestriction
 */
class AdmUserRestrictionsController extends AppController {

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
		$this->AdmUserRestriction->recursive = 0;
		$this->set('admUserRestrictions', $this->paginate());
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->AdmUserRestriction->create();
			if ($this->AdmUserRestriction->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm user restriction')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm user restriction')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$admUsers = $this->AdmUserRestriction->AdmUser->find('list');
		$admRoles = $this->AdmUserRestriction->AdmRole->find('list');
		$admAreas = $this->AdmUserRestriction->AdmArea->find('list');
		$this->set(compact('admUsers', 'admRoles', 'admAreas'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->AdmUserRestriction->id = $id;
		if (!$this->AdmUserRestriction->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm user restriction')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->AdmUserRestriction->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm user restriction')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm user restriction')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmUserRestriction->read(null, $id);
		}
		$admUsers = $this->AdmUserRestriction->AdmUser->find('list');
		$admRoles = $this->AdmUserRestriction->AdmRole->find('list');
		$admAreas = $this->AdmUserRestriction->AdmArea->find('list');
		$this->set(compact('admUsers', 'admRoles', 'admAreas'));
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
		$this->AdmUserRestriction->id = $id;
		if (!$this->AdmUserRestriction->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm user restriction')));
		}
		if ($this->AdmUserRestriction->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm user restriction')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm user restriction')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
