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
			$this->SalCustomer->create();
			debug($this->request->data);
			if ($this->SalCustomer->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('sal customer')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				//$this->redirect(array('action' => 'index'));
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
	
	////////////////////////////////////////////////////////////BEGINS MULTI INTERFACE/////////////////////////////////////////////////////////////////////////////////
	
	public function vsave(){
		
		
		
		
	}
	
	public function ajax_save_customer(){
		if($this->RequestHandler->isAjax()){
			$data = array();
			if(isset($this->request->data['id']) && $this->request->data['id'] <> ""){
				$data["SalCustomer"]["id"] = $this->request->data['id'];
			}else{
				$this->SalCustomer->create();
			}
			$data["SalCustomer"]["name"] = $this->request->data['name'];
			$data["SalCustomer"]["address"] = $this->request->data['address'];
			$data["SalCustomer"]["phone"] = $this->request->data['phone'];
			$data["SalCustomer"]["email"] = $this->request->data['email'];
			
			//debug($data);
			
			if($this->SalCustomer->save($data)){
				echo "success|".$this->SalCustomer->id;
			}
		}
	}
	
	public function ajax_save_employee(){
		if($this->RequestHandler->isAjax()){
			$data = array();
			$action = "add";
			if(isset($this->request->data['id']) && $this->request->data['id'] <> ""){
				$data["SalEmployee"]["id"] = $this->request->data['id'];
				$action = "edit";
			}else{
				$this->SalCustomer->SalEmployee->create();
			}
			$data["SalEmployee"]["sal_customer_id"] = $this->request->data['idCustomer'];
			$data["SalEmployee"]["name"] = $this->request->data['name'];
			$data["SalEmployee"]["phone"] = $this->request->data['phone'];
			$data["SalEmployee"]["email"] = $this->request->data['email'];
			
			//debug($data);
			
			if($this->SalCustomer->SalEmployee->save($data)){
				echo "success|".$this->SalCustomer->SalEmployee->id."|".$action;
			}
		}
	}
	
	public function ajax_delete_employee(){
		if($this->RequestHandler->isAjax()){
			$id = $this->request->data['id'];
			
			$children = $this->SalCustomer->SalEmployee->SalSale->find("count", array("conditions"=>array("SalSale.sal_employee_id"=>$id)));
			if($children == 0){
				$this->SalCustomer->SalEmployee->id = $id;
				if($this->SalCustomer->SalEmployee->delete()){
					echo "success";
				}else{
					echo "error";
				}
			}else{
				echo "children";
			}
			
		}
	}
	
	
	
//END OF THE CLASS	
}
