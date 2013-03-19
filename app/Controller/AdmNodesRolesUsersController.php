<?php
App::uses('AppController', 'Controller');
/**
 * AdmNodesRolesUsers Controller
 *
 * @property AdmNodesRolesUser $AdmNodesRolesUser
 */
class AdmNodesRolesUsersController extends AppController {

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
		$this->AdmNodesRolesUser->recursive = 0;
		$this->set('admNodesRolesUsers', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->AdmNodesRolesUser->id = $id;
		if (!$this->AdmNodesRolesUser->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm nodes roles user')));
		}
		$this->set('admNodesRolesUser', $this->AdmNodesRolesUser->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->AdmNodesRolesUser->create();
			$this->AdmNodesRolesUser->updateAll(
					array('AdmNodesRolesUser.active' => 0), array('AdmNodesRolesUser.adm_user_id' => $this->request->data['AdmNodesRolesUser']['adm_user_id'])
					);
			if ($this->AdmNodesRolesUser->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm nodes roles user')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm nodes roles user')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$admUsers = $this->AdmNodesRolesUser->AdmUser->find('list', array('fields'=>array('id', 'login')));
		$admRoles = $this->AdmNodesRolesUser->AdmRole->find('list');
		$admNodes = $this->AdmNodesRolesUser->AdmNode->find('list');
		$actives = array("No","Si");
		$this->set(compact('admUsers', 'admRoles', 'admNodes', "actives"));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->AdmNodesRolesUser->id = $id;
		if (!$this->AdmNodesRolesUser->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm nodes roles user')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->AdmNodesRolesUser->updateAll(
					array('AdmNodesRolesUser.active' => 0), 
					array('AdmNodesRolesUser.adm_user_id' => $this->request->data['AdmNodesRolesUser']['adm_user_id'])
					);
			$this->request->data['AdmNodesRolesUser']['lc_transaction']='MODIFY';
			if ($this->AdmNodesRolesUser->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm nodes roles user')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm nodes roles user')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmNodesRolesUser->read(null, $id);
		}
		$admUsers = $this->AdmNodesRolesUser->AdmUser->find('list', array('fields'=>array('id', 'login')));
		$admRoles = $this->AdmNodesRolesUser->AdmRole->find('list');
		$admNodes = $this->AdmNodesRolesUser->AdmNode->find('list');
		$actives = array("No","Si");
		$this->set(compact('admUsers', 'admRoles', 'admNodes', "actives"));
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
		$this->AdmNodesRolesUser->id = $id;
		if (!$this->AdmNodesRolesUser->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm nodes roles user')));
		}
		if ($this->AdmNodesRolesUser->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm nodes roles user')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm nodes roles user')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
