<?php
App::uses('AppController', 'Controller');
/**
 * InvPrices Controller
 *
 * @property InvPrice $InvPrice
 */
class InvPricesController extends AppController {

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
		$this->InvPrice->recursive = 0;
		$this->set('invPrices', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->InvPrice->id = $id;
		if (!$this->InvPrice->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv price')));
		}
		$this->set('invPrice', $this->InvPrice->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		//Section where the controls of the page are loaded		
		$invItems = $this->InvPrice->InvItem->find('list', array('order' => 'InvItem.id'));
		if(count($invItems) == 0)
		{
			$invItems[""] = '--- Vacio ---';
		}
		
		$invPriceTypes = $this->InvPrice->InvPriceType->find('list', array('order' => 'InvPriceType.id'));
		if(count($invPriceTypes) == 0)
		{
			$invTypePrices[""] = '--- Vacio ---';
		}
		$this->set(compact('invItems', 'invPriceTypes'));	
		
		if ($this->request->is('post')) {
			$this->InvPrice->create();
			if ($this->InvPrice->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('inv price')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('inv price')),
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
		$this->InvPrice->id = $id;
		if (!$this->InvPrice->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv price')));
		}
		//Section where the controls of the page are loaded		
		$invItems = $this->InvPrice->InvItem->find('list', array('order' => 'InvItem.id'));
		if(count($invItems) == 0)
		{
			$invItems[""] = '--- Vacio ---';
		}
		
		$invPriceTypes = $this->InvPrice->InvPriceType->find('list', array('order' => 'InvPriceType.id'));
		if(count($invPriceTypes) == 0)
		{
			$invTypePrices[""] = '--- Vacio ---';
		}
		$this->set(compact('invItems', 'invPriceTypes'));	
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['InvPrice']['lc_transaction']='MODIFY';
			if ($this->InvPrice->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('inv price')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('inv price')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->InvPrice->read(null, $id);
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
		$this->InvPrice->id = $id;
		if (!$this->InvPrice->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv price')));
		}
		if ($this->InvPrice->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('inv price')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('inv price')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
