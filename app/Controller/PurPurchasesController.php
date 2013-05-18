<?php
App::uses('AppController', 'Controller');
/**
 * PurPurchases Controller
 *
 * @property PurPurchase $PurPurchase
 */
class PurPurchasesController extends AppController {

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
	public $components = array('Session');
/**
 * index method
 *
 * @return void
 */
	public function index_order() {
		$this->paginate = array(
			'conditions' => array(
				'PurPurchase.lc_state !='=>'LOGIC_DELETE',
				'PurPurchase.lc_state LIKE'=> '%ORDER%'
			),
			'order' => array('PurPurchase.id' => 'desc'),
			'limit' => 15
		);
		$this->PurPurchase->recursive = 0;
		$this->set('purPurchases', $this->paginate());
	}
	
	public function index_invoice(){
		$this->paginate = array(
			'conditions' => array(
				'PurPurchase.lc_state !='=>'LOGIC_DELETE',
				'PurPurchase.lc_state LIKE'=> '%INVOICE%',
			),
			'order' => array('PurPurchase.id' => 'desc'),
			'limit' => 15
		);
		$this->PurPurchase->recursive = 0;
		$this->set('purPurchases', $this->paginate());
	}
	
	public function index_remit(){
		$this->paginate = array(
			'conditions' => array(
				'PurPurchase.lc_state !='=>'LOGIC_DELETE',
				'PurPurchase.lc_state LIKE'=> '%REMIT%',
			),
			'order' => array('PurPurchase.id' => 'desc'),
			'limit' => 15
		);
		$this->PurPurchase->recursive = 0;
		$this->set('purPurchases', $this->paginate());
	}

	/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->PurPurchase->id = $id;
		if (!$this->PurPurchase->exists()) {
			throw new NotFoundException(__('Invalid %s', __('pur purchase')));
		}
		$this->set('purPurchase', $this->PurPurchase->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->PurPurchase->create();
			if ($this->PurPurchase->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('pur purchase')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('pur purchase')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$invSuppliers = $this->PurPurchase->InvSupplier->find('list');
		$this->set(compact('invSuppliers'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->PurPurchase->id = $id;
		if (!$this->PurPurchase->exists()) {
			throw new NotFoundException(__('Invalid %s', __('pur purchase')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->PurPurchase->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('pur purchase')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('pur purchase')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->PurPurchase->read(null, $id);
		}
		$invSuppliers = $this->PurPurchase->InvSupplier->find('list');
		$this->set(compact('invSuppliers'));
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
		$this->PurPurchase->id = $id;
		if (!$this->PurPurchase->exists()) {
			throw new NotFoundException(__('Invalid %s', __('pur purchase')));
		}
		if ($this->PurPurchase->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('pur purchase')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('pur purchase')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
