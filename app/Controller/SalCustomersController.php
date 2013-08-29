<?php
App::uses('AppController', 'Controller');
/**
 * SalCustomers Controller
 *
 * @property SalCustomer $SalCustomer
 */
class SalCustomersController extends AppController {

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
	public function index() {
		$filters = array();
		$name = '';
		////////////////////////////START - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		if($this->request->is("post")) {
			$url = array('action'=>'index');
			$parameters = array();
			$empty=0;
			if(isset($this->request->data['SalCustomer']['name']) && $this->request->data['SalCustomer']['name']){
				$parameters['name'] = trim(strip_tags($this->request->data['SalCustomer']['name']));
			}else{
				$empty++;
			}
			
			if($empty == 1){
				$parameters['search']='empty';
			}else{
				$parameters['search']='yes';
			}
			$this->redirect(array_merge($url,$parameters));
		}
		////////////////////////////END - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		
		////////////////////////////START - SETTING URL FILTERS//////////////////////////////////////
		if(isset($this->passedArgs['name'])){
			$filters['SalCustomer.name LIKE'] = '%'.strtoupper($this->passedArgs['name']).'%';
			$name = $this->passedArgs['name'];
		}		
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////	
		
		$this->paginate = array(
			'conditions' => array($filters),
			'order' => array('SalCustomer.name' => 'asc'),
			//'limit' => 15
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->SalCustomer->recursive = 0;
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('salCustomers', $this->paginate());
		$this->set('name', $name);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->SalCustomer->id = $id;
		if (!$this->SalCustomer->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sal customer')));
		}
		$this->set('salCustomer', $this->SalCustomer->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			//$this->SalCustomer->create();
			
			$arrayCustomer = array(	'name'=>$this->request->data['name'],
									'address'=>$this->request->data['address'],
									'phone'=>$this->request->data['phone'],
									'location'=>$this->request->data['location'],
									'email'=>$this->request->data['email']);
			
			$arrayNit = array(	'nit'=>$this->request->data['nit'],
								'name'=>$this->request->data['nitname']);
			
			$arrayEmployee = array(	'name'=>$this->request->data['empname'],
									'phone'=>$this->request->data['empphone'],
									'email'=>$this->request->data['empmail']);
			
			$data = array('SalCustomer'=>$arrayCustomer,'SalTaxNumber'=>$arrayNit,'SalEmployee'=>$arrayEmployee);
			
			
			if($this->SalCustomer->saveCustomer($data)){
				
			} else {
				
			}
				

			
//			if ($this->SalCustomer->save($this->request->data)) {
//				$this->Session->setFlash(
//					__('The %s has been saved', __('sal customer')),
//					'alert',
//					array(
//						'plugin' => 'TwitterBootstrap',
//						'class' => 'alert-success'
//					)
//				);
//				$this->redirect(array('action' => 'index'));
//			} else {
//				$this->Session->setFlash(
//					__('The %s could not be saved. Please, try again.', __('sal customer')),
//					'alert',
//					array(
//						'plugin' => 'TwitterBootstrap',
//						'class' => 'alert-error'
//					)
//				);
//			}
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->SalCustomer->id = $id;
		if (!$this->SalCustomer->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sal customer')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['SalCustomer']['lc_transaction']='MODIFY';
			if ($this->SalCustomer->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('sal customer')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('sal customer')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->SalCustomer->read(null, $id);
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
		$this->SalCustomer->id = $id;
		if (!$this->SalCustomer->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sal customer')));
		}
		if ($this->SalCustomer->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('sal customer')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('sal customer')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
