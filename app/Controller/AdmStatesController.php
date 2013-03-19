<?php
App::uses('AppController', 'Controller');
/**
 * AdmStates Controller
 *
 * @property AdmState $AdmState
 */
class AdmStatesController extends AppController {

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
//	public $helpers = array('TwitterBootstrap.BootstrapHtml', 'TwitterBootstrap.BootstrapForm', 'TwitterBootstrap.BootstrapPaginator');
/**
 * Components
 *
 * @var array
 */
//	public $components = array('Session');

	public  function isAuthorized($user){
		return $this->Permission->isAllowed($this->name, $this->action, $this->Session->read('Permission.'.$this->name));
	}
	
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->AdmState->recursive = 0;
		$this->set('admStates', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->AdmState->id = $id;
		if (!$this->AdmState->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm state')));
		}
		$this->set('admState', $this->AdmState->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->AdmState->create();
			if ($this->AdmState->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm state')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm state')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$admControllers = $this->AdmState->AdmController->find('list');
		$this->set(compact('admControllers'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->AdmState->id = $id;
		if (!$this->AdmState->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm state')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
                    $this->request->data['AdmState']['lc_action']='MODIFY';
			if ($this->AdmState->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm state')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm state')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmState->read(null, $id);
		}
		$admControllers = $this->AdmState->AdmController->find('list');
		$this->set(compact('admControllers'));
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
		$this->AdmState->id = $id;
		if (!$this->AdmState->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm state')));
		}
		if ($this->AdmState->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm state')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm state')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
