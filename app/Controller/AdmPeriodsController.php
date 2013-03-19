<?php
App::uses('AppController', 'Controller');
/**
 * AdmPeriods Controller
 *
 * @property AdmPeriod $AdmPeriod
 */
class AdmPeriodsController extends AppController {

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
	//public $components = array('Session');
	public  function isAuthorized($user){
		return $this->Permission->isAllowed($this->name, $this->action, $this->Session->read('Permission.'.$this->name));
	}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->AdmPeriod->recursive = 0;
		$this->set('admPeriods', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->AdmPeriod->id = $id;
		if (!$this->AdmPeriod->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm period')));
		}
		$this->set('admPeriod', $this->AdmPeriod->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->AdmPeriod->create();
			if ($this->AdmPeriod->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm period')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm period')),
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
		$this->AdmPeriod->id = $id;
		if (!$this->AdmPeriod->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm period')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['AdmPeriod']['lc_transaction']='MODIFY';
			if ($this->AdmPeriod->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm period')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm period')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmPeriod->read(null, $id);
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
		$this->AdmPeriod->id = $id;
		if (!$this->AdmPeriod->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm period')));
		}
		if ($this->AdmPeriod->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm period')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm period')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
