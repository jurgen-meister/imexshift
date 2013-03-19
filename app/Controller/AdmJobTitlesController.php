<?php
App::uses('AppController', 'Controller');
/**
 * AdmJobTitles Controller
 *
 * @property AdmJobTitle $AdmJobTitle
 */
class AdmJobTitlesController extends AppController {

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
		$this->AdmJobTitle->recursive = 0;
		$this->set('admJobTitles', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->AdmJobTitle->id = $id;
		if (!$this->AdmJobTitle->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm job title')));
		}
		$this->set('admJobTitle', $this->AdmJobTitle->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->AdmJobTitle->create();
			if ($this->AdmJobTitle->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm job title')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm job title')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$admNodes = $this->AdmJobTitle->AdmNode->find('list');
		$this->set(compact('admNodes'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->AdmJobTitle->id = $id;
		if (!$this->AdmJobTitle->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm job title')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
                     $this->request->data['AdmJobTitle']['lc_transaction']='MODIFY';
			if ($this->AdmJobTitle->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm job title')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm job title')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmJobTitle->read(null, $id);
		}
		$admNodes = $this->AdmJobTitle->AdmNode->find('list');
		$this->set(compact('admNodes'));
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
		$this->AdmJobTitle->id = $id;
		if (!$this->AdmJobTitle->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm job title')));
		}
		if ($this->AdmJobTitle->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm job title')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm job title')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
