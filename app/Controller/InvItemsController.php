<?php
App::uses('AppController', 'Controller');
/**
 * InvItems Controller
 *
 * @property InvItem $InvItem
 */
class InvItemsController extends AppController {

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
		$this->InvItem->recursive = 0;
		$this->set('invItems', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->InvItem->id = $id;
		if (!$this->InvItem->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv item')));
		}
		$this->set('invItem', $this->InvItem->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		//Section where the controls of the page are loaded		
		$invBrands = $this->InvItem->InvBrand->find('list', array('order' => 'InvBrand.name'));
		if(count($invBrands) == 0)
		{
			$invBrands[""] = '--- Vacio ---';
		}
		
		$invCategories = $this->InvItem->InvCategory->find('list', array('order' => 'InvCategory.name'));
		if(count($invCategories) == 0)
		{
			$invCategories[""] = '--- Vacio ---';
		}		
		$this->set(compact('invBrands', 'invCategories'));	
		
		
		//Section where information is saved into the database
		if ($this->request->is('post')) {			
			$this->InvItem->create();
			$this->request->data['InvItem']['code'] = $this->_generateCode();
			if ($this->InvItem->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('inv item')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('inv item')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		
		
	}
	
	private function _generateCode(){
		$number = $this->InvItem->find('first', array(
			'fields' => array('MAX(InvItem.code) as code')		
		));	
		if($number[0]['code'] == null)
		{
			$number[0]['code'] = 1;
		}
		else 
		{
			$number[0]['code'] ++;
		}
		
		return $number[0]['code'];
		
	}

	/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->InvItem->id = $id;
		if (!$this->InvItem->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv item')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->InvItem->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('inv item')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('inv item')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->InvItem->read(null, $id);
		}
		$invBrands = $this->InvItem->InvBrand->find('list');
		$this->set(compact('invBrands'));
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
		$this->InvItem->id = $id;
		if (!$this->InvItem->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv item')));
		}
		if ($this->InvItem->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('inv item')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('inv item')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
