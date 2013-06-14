<?php
App::uses('AppController', 'Controller');
/**
 * AdmProfiles Controller
 *
 * @property AdmProfile $AdmProfile
 */
class AdmProfilesController extends AppController {

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
	


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->AdmProfile->recursive = 0;
		$this->set('admProfiles', $this->paginate());
	}


/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->loadModel('AdmParameterDetail');
		$this->loadModel('AdmParameter');
		$admParameterDetails = $this->AdmParameter->AdmParameterDetail->find('all',array(			
			'order' => 'AdmParameterDetail.id',
			//'contain' => array('AdmParameter' => array('conditions' => array('AdmParameter.name' => 'Lugar Expedicion'))),
			'conditions' => array('AdmParameter.name' => 'Lugar Expedicion'),
			'fields' => array('AdmParameterDetail.id', 'AdmParameterDetail.par_char1')					
		));
		
		if(count($admParameterDetails) != 0)
		{
			
		}
		else
		{
			$admParameterDetails[""] = "--- Vacio ---";
		}
		$this->set(compact('admParameterDetails'));
		if ($this->request->is('post')) {
			$this->AdmProfile->create();
			if ($this->AdmProfile->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm profile')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm profile')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$admUsers = $this->AdmProfile->AdmUser->find('list');
		$this->set(compact('admUsers'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->AdmProfile->id = $id;
		if (!$this->AdmProfile->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm profile')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
                     $this->request->data['AdmProfile']['lc_transaction']='MODIFY';
			if ($this->AdmProfile->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm profile')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm profile')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmProfile->read(null, $id);
		}
		$admUsers = $this->AdmProfile->AdmUser->find('list');
		$this->set(compact('admUsers'));
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
		$this->AdmProfile->id = $id;
		if (!$this->AdmProfile->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm profile')));
		}
		if ($this->AdmProfile->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm profile')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm profile')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
