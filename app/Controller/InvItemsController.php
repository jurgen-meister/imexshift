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
	//public $components = array('Session');
//	public  function isAuthorized($user){
//		return $this->Permission->isAllowed($this->name, $this->action, $this->Session->read('Permission.'.$this->name));
//	}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$filters = array();
		$code = '';
		////////////////////////////START - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		if($this->request->is("post")) {
			$url = array('action'=>'index');
			$parameters = array();
			$empty=0;
			if(isset($this->request->data['InvItem']['code']) && $this->request->data['InvItem']['code']){
				$parameters['code'] = trim(strip_tags($this->request->data['InvItem']['code']));
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
		if(isset($this->passedArgs['code'])){
			$filters['InvItem.code LIKE'] = '%'.strtoupper($this->passedArgs['code']).'%';
			$code = $this->passedArgs['code'];
		}		
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////	
		
		$this->paginate = array(
			'conditions' => array($filters),
			'order' => array('InvItem.id' => 'asc'),
			'limit' => 15
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->InvItem->recursive = 0;
		
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('invItems', $this->paginate('InvItem'));
		$this->set('code', $code);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
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
 * save_item method
 *
 * @return void
 */	
	public function save_item($id = null){
		$id = '';
		if(isset($this->passedArgs['id'])){
			$id = $this->passedArgs['id'];
		}
		
		$invSuppliers = $this->InvItem->InvItemsSupplier->InvSupplier->find('list', array('order' => 'InvSupplier.name'));
		if(count($invSuppliers) == 0)
		{
			$invSuppliers[""] = '--- Vacio ---';
		}
		
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
		$invPrices = array();
		
		$this->InvItem->recursive = -1;
		$this->request->data = $this->InvItem->read(null,$id);		
		
		if($id <> null){
			$invPrices = $this->_get_prices($id);
			
		}
		
		$this->set(compact('id','invBrands', 'invCategories', 'invPrices', 'invSuppliers'));	
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
			if ($this->InvItem->save($this->request->data)) {
				$this->Session->setFlash(
					__('El Item se guardo satisfactoriamente'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('El Item no se pudo guardar, por favor intente de nuevo'),
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
		$this->InvItem->id = $id;
		if (!$this->InvItem->exists()) {
			throw new NotFoundException(__('Invalid %s', __('inv item')));
		}
		//$this->_view_Prices(1);
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['InvItem']['lc_transaction']='MODIFY';
			if ($this->InvItem->save($this->request->data)) {
				$this->Session->setFlash(
					__('El item fue modificado'),
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
				__('El Item fue Eliminado'),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('El Item no se pudo Eliminar'),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
	
	//////////////////////////////////////////// START - AJAX ///////////////////////////////////////////////
	
	public function ajax_initiate_modal_add_price(){
		if($this->RequestHandler->isAjax()){
						
			$pricesAlreadySaved = $this->request->data['pricesAlreadySaved'];
//			$warehouse = $this->request->data['warehouse']; //if it's warehouse_transfer is OUT
//			$warehouse2 = $this->request->data['warehouse2'];//if it's warehouse_transfer is IN
//			$transfer = $this->request->data['transfer'];
			
			$invPriceTypes = $this->InvItem->InvPrice->InvPriceType->find('list', array(
				//'recursive'=>-1,
				//'fields'=>array('InvItem.id', 'CONCAT(InvItem.code, '-', InvItem.name)')
			));	
			
			$this->set(compact('invPriceTypes'));
		}
	}
	
	public function ajax_save_price(){
		if($this->RequestHandler->isAjax()){
			
			$itemId = $this->request->data['itemId'];
			$priceTypeId = $this->request->data['priceTypeId'];		
			$priceDate = $this->request->data['priceDate'];
			$priceAmount = $this->request->data['priceAmount'];			
			$priceDescription = $this->request->data['priceDescription'];			
			
			$arrayPrice = array('inv_item_id'=>$itemId, 'inv_price_type_id'=>$priceTypeId, 'date'=> $priceDate, 'price'=>$priceAmount, 'description'=>$priceDescription);
			$data = array('InvPrice'=>$arrayPrice);

			if($this->InvItem->InvPrice->saveAssociated($data)){
					$priceIdInserted = $this->InvItem->InvPrice->id;
						echo 'insertado|'.$priceIdInserted;
				}
		}
	}
	
	
	public function ajax_delete_price(){
		if($this->RequestHandler->isAjax()){			
			
			$priceId = $this->request->data['priceId'];			
			
			$arrayPrice = array('inv_price_id'=>$priceId);
			$data = array('InvPrice'=>$arrayPrice);
			
			$this->InvItem->InvPrice->deleteAll(array('InvPrice.id' => $arrayPrice));

		}
	}
	
	public function ajax_save_item(){
		if($this->RequestHandler->isAjax()){
			
			$itemId = $this->request->data['itemId'];
			$itemSupplier = $this->request->data['itemSupplier'];
			$itemCode = $this->request->data['itemCode'];
			$itemBrand = $this->request->data['itemBrand'];
			$itemCategory = $this->request->data['itemCategory'];
			$itemName = $this->request->data['itemName'];
			$itemDescription = $this->request->data['itemDescription'];
			$itemMin = $this->request->data['itemMin'];
			$itemPic = $this->request->data['itemPic'];
			
			if($itemId <> null){
				$this->InvItem->id = $itemId;
				$arrayItem = array('inv_brand_id' => $itemBrand, 'inv_category_id' => $itemCategory, 
					   'code' => $itemCode,'name' => $itemName, 'description' => $itemDescription,
						'picture' => $itemPic, 'min_quantity' => $itemMin,'lc_transaction' => 'MODIFY');
			}
			
			else{
				$arrayItem = array('inv_brand_id' => $itemBrand, 'inv_category_id' => $itemCategory, 
					   'code' => $itemCode,'name' => $itemName, 'description' => $itemDescription,
						'picture' => $itemPic, 'min_quantity' => $itemMin);
			}	
			
			if($this->InvItem->save($arrayItem)){
				
				
				
			}
			
//			if($itemId <> null){
//				$arrayItemSupplier = array('inv_supplier_id' => $itemSupplier, 'inv_item_id' => $itemId);				
//			}
//			else {
//				$arrayItemSupplier = array('inv_supplier_id' => $itemSupplier, 'inv_item_id' => $itemId);				
//			}		
//			
//			$data = array('InvItem'=>$arrayItem, 'InvItemsSupplier'=>$arrayItemSupplier);
//
//			if($this->InvItem->InvItemsSupplier->saveAssociated($data)){
//					$priceIdInserted = $this->InvItem->InvPrice->id;
//						echo 'insertado|'.$priceIdInserted;
//				}
		}		
	}
	//////////////////////////////////////////// END - AJAX /////////////////////////////////////////////////
	
	//////////////////////////////////////////// START - PRIVATE ///////////////////////////////////////////////
	private function _get_prices($idPrice){
		$invPrices = $this->InvItem->InvPrice->find('all',array(
			'conditions' => array('InvPrice.inv_item_id' => $idPrice),
			'fields' => array('InvPrice.id','InvPriceType.name','InvPrice.date','InvPrice.price','InvPrice.description'),
			'order' => array('InvPrice.date' => 'desc'),
		));
		
		$formatedPrices = array();
		foreach ($invPrices as $key => $value){
			$formatedPrices[$key] = array(
				'itemId' => $idPrice,
				'priceId' => $value['InvPrice']['id'],
				'priceType' => $value['InvPriceType']['name'],
				'date' => $value['InvPrice']['date'],
				'price' => $value['InvPrice']['price'],
				'description' => $value['InvPrice']['description']
			);
		}
		
		return $formatedPrices;
	}
	//////////////////////////////////////////// END - PRIVATE /////////////////////////////////////////////////
}
