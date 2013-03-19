<?php
App::uses('AppController', 'Controller');
/**
 * AdmNodes Controller
 *
 * @property AdmNode $AdmNode
 */
class AdmNodesController extends AppController {

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
		$this->AdmNode->recursive = 0;
		$this->set('admNodes', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->AdmNode->id = $id;
		if (!$this->AdmNode->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm node')));
		}
		$this->set('admNode', $this->AdmNode->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->AdmNode->create();
			if ($this->AdmNode->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm node')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm node')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$admPeriods = $this->AdmNode->AdmPeriod->find('list');
		$parentNodes = $this->AdmNode->find('list');
		$parentNodes[0] = "Ninguno";
		$this->set(compact('admPeriods', 'parentNodes'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->AdmNode->id = $id;
		if (!$this->AdmNode->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm node')));
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			//debug($this->request->data);
			$this->request->data['AdmNode']['lc_transaction']='MODIFY';
			if ($this->AdmNode->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm node')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm node')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmNode->read(null, $id);
		}
		
		
		$admPeriods = $this->AdmNode->AdmPeriod->find('list');
		///////////////////////////////////////////////////////////
		$childrenAux = $this->AdmNode->find('list', array('fields'=>array('AdmNode.id', 'AdmNode.id') ,'conditions'=>array("AdmNode.parent_node"=>$id)));
		//debug($childrenAux);

		if(count($childrenAux)>0){ //fix bug last child
			$children = array_merge(array(intval($id)), $childrenAux); //array();
			do{
				$childrenAux = $this->AdmNode->find('list', array('fields'=>array('AdmNode.id', 'AdmNode.id') ,'conditions'=>array("AdmNode.parent_node"=>$childrenAux)));
				$children = array_merge($children, (array)$childrenAux);
			}while(count($childrenAux) > 0);
		}else{
			$children = intval($id);
		}
		//debug($children);
		
		$parentNodes = $this->AdmNode->find('list', array("conditions"=>array("NOT"=>array("AdmNode.id"=>$children))));
		//debug($parentNodes);
		$parentNodes[0] = "Ninguno";
		
		//$parentNodes = $this->AdmNode->find('list');
		//////////////////////////////////////////////////////////
		
		$this->set(compact('admPeriods', 'parentNodes'));
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
		$this->AdmNode->id = $id;
		if (!$this->AdmNode->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm node')));
		}
		
		//verify if exist child
		$child = $this->AdmNode->find('count', array('conditions'=>array("AdmNode.parent_node"=>$id)));
		if($child > 0){
			$this->Session->setFlash(
				__('Tiene hijos no se puede eliminar', __('adm menu')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-error'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		
		
		if ($this->AdmNode->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm node')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm node')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
